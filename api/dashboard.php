<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Total Pendonor Aktif
    $query1 = "SELECT COUNT(*) as total FROM Pendonor WHERE status_donor = 'Aktif'";
    $stmt1 = $db->query($query1);
    $pendonor = $stmt1->fetch();

    // Total Stok Darah
    $query2 = "SELECT SUM(jumlah_stok) as total FROM Stok_Darah";
    $stmt2 = $db->query($query2);
    $stok = $stmt2->fetch();

    // Permintaan Hari Ini
    $query3 = "SELECT COUNT(*) as total FROM Permintaan_Darah WHERE tanggal_permintaan = CURDATE()";
    $stmt3 = $db->query($query3);
    $permintaan = $stmt3->fetch();

    // Jadwal Bulan Ini
    $query4 = "SELECT COUNT(*) as total FROM Jadwal_Donor 
               WHERE MONTH(tanggal) = MONTH(CURDATE()) 
               AND YEAR(tanggal) = YEAR(CURDATE())";
    $stmt4 = $db->query($query4);
    $jadwal = $stmt4->fetch();

    // Stok per Golongan
    $query5 = "SELECT golongan_darah, jumlah_stok, status FROM Stok_Darah ORDER BY golongan_darah";
    $stmt5 = $db->query($query5);
    $stok_per_golongan = $stmt5->fetchAll();

    // Permintaan Terbaru
    $query6 = "SELECT * FROM Permintaan_Darah ORDER BY created_at DESC LIMIT 5";
    $stmt6 = $db->query($query6);
    $permintaan_terbaru = $stmt6->fetchAll();

    // Donor Terbaru
    $query7 = "SELECT r.*, p.nama as nama_pendonor 
               FROM Riwayat_Donor r 
               LEFT JOIN Pendonor p ON r.id_pendonor = p.id 
               ORDER BY r.tanggal_donor DESC LIMIT 5";
    $stmt7 = $db->query($query7);
    $donor_terbaru = $stmt7->fetchAll();

    echo json_encode([
        "success" => true,
        "data" => [
            "total_pendonor" => $pendonor['total'],
            "total_stok" => $stok['total'] ?? 0,
            "permintaan_hari_ini" => $permintaan['total'],
            "jadwal_bulan_ini" => $jadwal['total'],
            "stok_per_golongan" => $stok_per_golongan,
            "permintaan_terbaru" => $permintaan_terbaru,
            "donor_terbaru" => $donor_terbaru
        ]
    ]);
} catch(PDOException $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>