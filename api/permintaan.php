<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch($method) {
    case 'GET':
        try {
            $query = "SELECT * FROM Permintaan_Darah ORDER BY tanggal_permintaan DESC";
            $stmt = $db->query($query);
            $data = $stmt->fetchAll();
            echo json_encode(["success" => true, "data" => $data]);
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;

    case 'POST':
        try {
            $kode = 'PMT-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $query = "INSERT INTO Permintaan_Darah 
                      (kode_permintaan, nama_pasien, rumah_sakit, golongan_darah, 
                       jumlah_kantong, keperluan, prioritas, status, tanggal_permintaan) 
                      VALUES (:kode, :nama, :rs, :gol, :jml, :keperluan, :prio, :status, :tgl)";
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':kode', $kode);
            $stmt->bindParam(':nama', $input['nama_pasien']);
            $stmt->bindParam(':rs', $input['rumah_sakit']);
            $stmt->bindParam(':gol', $input['golongan_darah']);
            $stmt->bindParam(':jml', $input['jumlah_kantong']);
            $stmt->bindParam(':keperluan', $input['keperluan']);
            $stmt->bindParam(':prio', $input['prioritas']);
            $stmt->bindParam(':status', $input['status']);
            $stmt->bindParam(':tgl', $input['tanggal_permintaan']);
            
            if($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Permintaan berhasil ditambahkan"]);
            }
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;

    case 'PUT':
        try {
            $query = "UPDATE Permintaan_Darah SET 
                      nama_pasien = :nama, rumah_sakit = :rs, 
                      golongan_darah = :gol, jumlah_kantong = :jml,
                      status = :status WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':nama', $input['nama_pasien']);
            $stmt->bindParam(':rs', $input['rumah_sakit']);
            $stmt->bindParam(':gol', $input['golongan_darah']);
            $stmt->bindParam(':jml', $input['jumlah_kantong']);
            $stmt->bindParam(':status', $input['status']);
            $stmt->bindParam(':id', $input['id']);
            
            if($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Permintaan berhasil diupdate"]);
            }
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;

    case 'DELETE':
        try {
            $query = "DELETE FROM Permintaan_Darah WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $input['id']);
            if($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Permintaan berhasil dihapus"]);
            }
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;
}
?>