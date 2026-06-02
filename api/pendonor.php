<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch($method) {
    case 'GET':
        try {
            if (isset($_GET['id'])) {
                // Ambil data 1 pendonor
                $query = "SELECT * FROM Pendonor WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':id', $_GET['id']);
                $stmt->execute();
                $data = $stmt->fetch();
            } else {
                // Ambil semua pendonor (dengan search)
                $search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
                $query = "SELECT * FROM Pendonor 
                          WHERE nama LIKE :search OR nik LIKE :search OR golongan_darah LIKE :search
                          ORDER BY created_at DESC";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':search', $search);
                $stmt->execute();
                $data = $stmt->fetchAll();
            }
            echo json_encode(["success" => true, "data" => $data]);
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;

    case 'POST':
        try {
            $query = "INSERT INTO Pendonor 
                      (nama, nik, jenis_kelamin, golongan_darah, alamat, no_hp, tanggal_lahir, berat_badan, status_donor) 
                      VALUES (:nama, :nik, :jk, :gol, :alamat, :hp, :tgl_lahir, :bb, :status)";
            $stmt = $db->prepare($query);
            
            $nama = $input['nama'];
            $nik = $input['nik'];
            $jk = $input['jenis_kelamin'];
            $gol = $input['golongan_darah'];
            $alamat = $input['alamat'] ?? '';
            $hp = $input['no_hp'] ?? '';
            $tgl_lahir = $input['tanggal_lahir'] ?? null;
            $bb = $input['berat_badan'] ?? null;
            $status = $input['status_donor'] ?? 'Aktif';
            
            $stmt->bindParam(':nama', $nama);
            $stmt->bindParam(':nik', $nik);
            $stmt->bindParam(':jk', $jk);
            $stmt->bindParam(':gol', $gol);
            $stmt->bindParam(':alamat', $alamat);
            $stmt->bindParam(':hp', $hp);
            $stmt->bindParam(':tgl_lahir', $tgl_lahir);
            $stmt->bindParam(':bb', $bb);
            $stmt->bindParam(':status', $status);
            
            if($stmt->execute()) {
                echo json_encode([
                    "success" => true, 
                    "message" => "Pendonor berhasil ditambahkan",
                    "id" => $db->lastInsertId()
                ]);
            }
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;

    case 'PUT':
        try {
            $query = "UPDATE Pendonor SET 
                      nama = :nama, 
                      nik = :nik, 
                      jenis_kelamin = :jk, 
                      golongan_darah = :gol, 
                      alamat = :alamat, 
                      no_hp = :hp,
                      tanggal_lahir = :tgl_lahir,
                      berat_badan = :bb,
                      status_donor = :status
                      WHERE id = :id";
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':nama', $input['nama']);
            $stmt->bindParam(':nik', $input['nik']);
            $stmt->bindParam(':jk', $input['jenis_kelamin']);
            $stmt->bindParam(':gol', $input['golongan_darah']);
            $stmt->bindParam(':alamat', $input['alamat']);
            $stmt->bindParam(':hp', $input['no_hp']);
            $stmt->bindParam(':tgl_lahir', $input['tanggal_lahir']);
            $stmt->bindParam(':bb', $input['berat_badan']);
            $stmt->bindParam(':status', $input['status_donor']);
            $stmt->bindParam(':id', $input['id']);
            
            if($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Data pendonor berhasil diupdate"]);
            }
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;

    case 'DELETE':
        try {
            $query = "DELETE FROM Pendonor WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $input['id']);
            if($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Pendonor berhasil dihapus"]);
            }
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;
}
?>