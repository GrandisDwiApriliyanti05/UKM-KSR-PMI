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
            $query = "SELECT * FROM Jadwal_Donor ORDER BY tanggal DESC";
            $stmt = $db->query($query);
            $data = $stmt->fetchAll();
            echo json_encode(["success" => true, "data" => $data]);
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;

    case 'POST':
        try {
            $query = "INSERT INTO Jadwal_Donor 
                      (nama_kegiatan, lokasi, tanggal, waktu_mulai, waktu_selesai, 
                       deskripsi, target_donor, penyelenggara, status) 
                      VALUES (:nama, :lokasi, :tgl, :mulai, :selesai, :desc, :target, :penyelenggara, :status)";
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':nama', $input['nama_kegiatan']);
            $stmt->bindParam(':lokasi', $input['lokasi']);
            $stmt->bindParam(':tgl', $input['tanggal']);
            $stmt->bindParam(':mulai', $input['waktu_mulai']);
            $stmt->bindParam(':selesai', $input['waktu_selesai']);
            $stmt->bindParam(':desc', $input['deskripsi']);
            $stmt->bindParam(':target', $input['target_donor']);
            $stmt->bindParam(':penyelenggara', $input['penyelenggara']);
            $stmt->bindParam(':status', $input['status']);
            
            if($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Jadwal berhasil ditambahkan"]);
            }
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;

    case 'PUT':
        try {
            $query = "UPDATE Jadwal_Donor SET 
                      nama_kegiatan = :nama, lokasi = :lokasi, 
                      tanggal = :tgl, deskripsi = :desc, status = :status 
                      WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':nama', $input['nama_kegiatan']);
            $stmt->bindParam(':lokasi', $input['lokasi']);
            $stmt->bindParam(':tgl', $input['tanggal']);
            $stmt->bindParam(':desc', $input['deskripsi']);
            $stmt->bindParam(':status', $input['status']);
            $stmt->bindParam(':id', $input['id']);
            
            if($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Jadwal berhasil diupdate"]);
            }
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;

    case 'DELETE':
        try {
            $query = "DELETE FROM Jadwal_Donor WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $input['id']);
            if($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Jadwal berhasil dihapus"]);
            }
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;
}
?>