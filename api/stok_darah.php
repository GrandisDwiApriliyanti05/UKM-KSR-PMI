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
                $query = "SELECT * FROM Stok_Darah WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':id', $_GET['id']);
                $stmt->execute();
                $data = $stmt->fetch();
            } else {
                $query = "SELECT * FROM Stok_Darah ORDER BY golongan_darah";
                $stmt = $db->query($query);
                $data = $stmt->fetchAll();
            }
            echo json_encode(["success" => true, "data" => $data]);
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;

    case 'POST':
        try {
            // Auto update status berdasarkan jumlah stok
            $status = $input['status'] ?? '';
            if (empty($status)) {
                if ($input['jumlah_stok'] == 0) {
                    $status = 'Habis';
                } elseif ($input['jumlah_stok'] < 50) {
                    $status = 'Kritis';
                } elseif ($input['jumlah_stok'] < 100) {
                    $status = 'Menipis';
                } else {
                    $status = 'Tersedia';
                }
            }
            
            $query = "INSERT INTO Stok_Darah (golongan_darah, jumlah_stok, minimum_stok, maksimum_stok, status, keterangan) 
                      VALUES (:gol, :jml, :min, :max, :status, :ket)";
            $stmt = $db->prepare($query);
            
            $gol = $input['golongan_darah'];
            $jml = $input['jumlah_stok'];
            $min = $input['minimum_stok'] ?? 50;
            $max = $input['maksimum_stok'] ?? 500;
            $ket = $input['keterangan'] ?? '';
            
            $stmt->bindParam(':gol', $gol);
            $stmt->bindParam(':jml', $jml);
            $stmt->bindParam(':min', $min);
            $stmt->bindParam(':max', $max);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':ket', $ket);
            
            if($stmt->execute()) {
                echo json_encode([
                    "success" => true, 
                    "message" => "Stok darah berhasil ditambahkan",
                    "id" => $db->lastInsertId()
                ]);
            }
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;

    case 'PUT':
        try {
            $query = "UPDATE Stok_Darah SET 
                      golongan_darah = :gol,
                      jumlah_stok = :jml, 
                      minimum_stok = :min,
                      maksimum_stok = :max,
                      status = :status,
                      keterangan = :ket 
                      WHERE id = :id";
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':gol', $input['golongan_darah']);
            $stmt->bindParam(':jml', $input['jumlah_stok']);
            $stmt->bindParam(':min', $input['minimum_stok']);
            $stmt->bindParam(':max', $input['maksimum_stok']);
            $stmt->bindParam(':status', $input['status']);
            $stmt->bindParam(':ket', $input['keterangan']);
            $stmt->bindParam(':id', $input['id']);
            
            if($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Stok darah berhasil diupdate"]);
            }
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;

    case 'DELETE':
        try {
            $query = "DELETE FROM Stok_Darah WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $input['id']);
            
            if($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Stok darah berhasil dihapus"]);
            }
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;
}
?>