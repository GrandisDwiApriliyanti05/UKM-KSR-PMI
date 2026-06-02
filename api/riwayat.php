<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch($method) {
    case 'GET':
        try {
            $query = "SELECT r.*, p.nama as nama_pendonor, p.golongan_darah as gol_pendonor
                      FROM Riwayat_Donor r
                      LEFT JOIN Pendonor p ON r.id_pendonor = p.id
                      ORDER BY r.tanggal_donor DESC";
            $stmt = $db->query($query);
            $data = $stmt->fetchAll();
            echo json_encode(["success" => true, "data" => $data]);
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;

    case 'POST':
        try {
            $query = "INSERT INTO Riwayat_Donor 
                      (id_pendonor, id_jadwal, tanggal_donor, lokasi_don, 
                       golongan_darah, volume_ml, tekanan_darah, hemoglobin, 
                       status, catatan, petugas) 
                      VALUES (:id_pendonor, :id_jadwal, :tgl, :lokasi, 
                              :gol, :vol, :tensi, :hb, :status, :catatan, :petugas)";
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':id_pendonor', $input['id_pendonor']);
            $stmt->bindParam(':id_jadwal', $input['id_jadwal']);
            $stmt->bindParam(':tgl', $input['tanggal_donor']);
            $stmt->bindParam(':lokasi', $input['lokasi_don']);
            $stmt->bindParam(':gol', $input['golongan_darah']);
            $stmt->bindParam(':vol', $input['volume_ml']);
            $stmt->bindParam(':tensi', $input['tekanan_darah']);
            $stmt->bindParam(':hb', $input['hemoglobin']);
            $stmt->bindParam(':status', $input['status']);
            $stmt->bindParam(':catatan', $input['catatan']);
            $stmt->bindParam(':petugas', $input['petugas']);
            
            if($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Riwayat berhasil ditambahkan"]);
            }
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;

    case 'DELETE':
        try {
            $query = "DELETE FROM Riwayat_Donor WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $input['id']);
            if($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Riwayat berhasil dihapus"]);
            }
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;
}
?>