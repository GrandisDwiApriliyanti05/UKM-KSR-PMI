-- ============================================
-- PMI SMART BLOOD MANAGEMENT SYSTEM
-- Database: pmi_blood_db
-- Tanggal: 02 Juni 2026
-- ============================================

CREATE DATABASE IF NOT EXISTS pmi_blood_db 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE pmi_blood_db;

-- ============================================
-- TABEL USERS
-- ============================================
DROP TABLE IF EXISTS Users;
CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    role ENUM('admin', 'petugas', 'viewer') DEFAULT 'admin',
    foto_profil VARCHAR(255) DEFAULT 'default.png',
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABEL PENDONOR
-- ============================================
DROP TABLE IF EXISTS Pendonor;
CREATE TABLE Pendonor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    nik VARCHAR(16) NOT NULL UNIQUE,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan') NOT NULL,
    golongan_darah ENUM('A', 'B', 'AB', 'O') NOT NULL,
    alamat TEXT,
    no_hp VARCHAR(15),
    tanggal_lahir DATE,
    berat_badan INT,
    status_donor ENUM('Aktif', 'Non-Aktif', 'Suspend') DEFAULT 'Aktif',
    total_donor INT DEFAULT 0,
    terakhir_donor DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_golongan (golongan_darah),
    INDEX idx_status (status_donor)
) ENGINE=InnoDB;

-- ============================================
-- TABEL STOK DARAH
-- ============================================
DROP TABLE IF EXISTS Stok_Darah;
CREATE TABLE Stok_Darah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    golongan_darah ENUM('A', 'B', 'AB', 'O') NOT NULL UNIQUE,
    jumlah_stok INT NOT NULL DEFAULT 0,
    minimum_stok INT DEFAULT 50,
    maksimum_stok INT DEFAULT 500,
    status ENUM('Tersedia', 'Menipis', 'Kritis', 'Habis') DEFAULT 'Tersedia',
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABEL PERMINTAAN DARAH
-- ============================================
DROP TABLE IF EXISTS Permintaan_Darah;
CREATE TABLE Permintaan_Darah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_permintaan VARCHAR(20) NOT NULL UNIQUE,
    nama_pasien VARCHAR(100) NOT NULL,
    rumah_sakit VARCHAR(100) NOT NULL,
    golongan_darah ENUM('A', 'B', 'AB', 'O') NOT NULL,
    jumlah_kantong INT NOT NULL,
    keperluan VARCHAR(255),
    prioritas ENUM('Normal', 'Urgent', 'Emergency') DEFAULT 'Normal',
    status ENUM('Menunggu', 'Diproses', 'Selesai', 'Ditolak') DEFAULT 'Menunggu',
    tanggal_permintaan DATE NOT NULL,
    tanggal_selesai DATE,
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ============================================
-- TABEL JADWAL DONOR
-- ============================================
DROP TABLE IF EXISTS Jadwal_Donor;
CREATE TABLE Jadwal_Donor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kegiatan VARCHAR(150) NOT NULL,
    lokasi VARCHAR(200) NOT NULL,
    tanggal DATE NOT NULL,
    waktu_mulai TIME,
    waktu_selesai TIME,
    deskripsi TEXT,
    target_donor INT DEFAULT 50,
    realisasi_donor INT DEFAULT 0,
    penyelenggara VARCHAR(100),
    status ENUM('Dijadwalkan', 'Berlangsung', 'Selesai', 'Dibatalkan') DEFAULT 'Dijadwalkan',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABEL RIWAYAT DONOR
-- ============================================
DROP TABLE IF EXISTS Riwayat_Donor;
CREATE TABLE Riwayat_Donor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pendonor INT NOT NULL,
    id_jadwal INT,
    tanggal_donor DATE NOT NULL,
    lokasi_don VARCHAR(200),
    golongan_darah ENUM('A', 'B', 'AB', 'O') NOT NULL,
    volume_ml INT DEFAULT 450,
    tekanan_darah VARCHAR(10),
    hemoglobin DECIMAL(4,2),
    status ENUM('Sukses', 'Gagal', 'Ditunda') DEFAULT 'Sukses',
    catatan TEXT,
    petugas VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pendonor) REFERENCES Pendonor(id) ON DELETE CASCADE,
    FOREIGN KEY (id_jadwal) REFERENCES Jadwal_Donor(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- TABEL LOG AKTIVITAS
-- ============================================
DROP TABLE IF EXISTS Log_Aktivitas;
CREATE TABLE Log_Aktivitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    aktivitas VARCHAR(100) NOT NULL,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES Users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- DATA SAMPLE
-- ============================================

-- Users (password: admin123)
INSERT INTO Users (username, password, nama_lengkap, email, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin PMI', 'admin@pmi-smart.id', 'admin'),
('petugas1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Budi Santoso', 'budi@pmi-smart.id', 'petugas'),
('petugas2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siti Rahayu', 'siti@pmi-smart.id', 'petugas');

-- Stok Darah
INSERT INTO Stok_Darah (golongan_darah, jumlah_stok, minimum_stok, maksimum_stok, status, keterangan) VALUES
('A', 120, 50, 500, 'Tersedia', 'Stok aman'),
('B', 95, 50, 500, 'Tersedia', 'Stok aman'),
('AB', 35, 50, 500, 'Menipis', 'Segera lakukan pengadaan'),
('O', 270, 50, 500, 'Tersedia', 'Stok berlebih');

-- Pendonor
INSERT INTO Pendonor (nama, nik, jenis_kelamin, golongan_darah, alamat, no_hp, tanggal_lahir, berat_badan, status_donor, total_donor, terakhir_donor) VALUES
('Budi Santoso', '3201012345678901', 'Laki-laki', 'A', 'Jl. Merdeka No. 10, Jakarta', '081234567890', '1990-05-15', 70, 'Aktif', 8, '2026-06-01'),
('Siti Nurhaliza', '3202023456789012', 'Perempuan', 'B', 'Jl. Sudirman No. 25, Bandung', '081234567891', '1992-08-20', 55, 'Aktif', 5, '2026-05-28'),
('Ahmad Rizki', '3203034567890123', 'Laki-laki', 'AB', 'Jl. Diponegoro No. 15, Surabaya', '081234567892', '1988-12-10', 75, 'Aktif', 12, '2026-05-25'),
('Dewi Lestari', '3204045678901234', 'Perempuan', 'O', 'Jl. Ahmad Yani No. 30, Semarang', '081234567893', '1995-03-22', 58, 'Aktif', 3, '2026-05-20'),
('Rudi Hermawan', '3205056789012345', 'Laki-laki', 'A', 'Jl. Gatot Subroto No. 5, Yogyakarta', '081234567894', '1985-07-18', 80, 'Non-Aktif', 15, '2026-05-15'),
('Maya Putri', '3206067890123456', 'Perempuan', 'O', 'Jl. Veteran No. 40, Malang', '081234567895', '1993-11-05', 52, 'Aktif', 7, '2026-05-10'),
('Hendra Wijaya', '3207078901234567', 'Laki-laki', 'B', 'Jl. Pahlawan No. 12, Medan', '081234567896', '1987-09-30', 72, 'Aktif', 10, '2026-04-28'),
('Lina Marlina', '3208089012345678', 'Perempuan', 'A', 'Jl. Kartini No. 8, Makassar', '081234567897', '1991-01-14', 56, 'Aktif', 4, '2026-04-20'),
('Agus Setiawan', '3209090123456789', 'Laki-laki', 'O', 'Jl. Pemuda No. 22, Palembang', '081234567898', '1989-06-25', 68, 'Aktif', 9, '2026-04-15'),
('Rina Kusuma', '3210101234567890', 'Perempuan', 'AB', 'Jl. Kenanga No. 18, Denpasar', '081234567899', '1994-04-08', 54, 'Aktif', 6, '2026-04-10');

-- Permintaan Darah
INSERT INTO Permintaan_Darah (kode_permintaan, nama_pasien, rumah_sakit, golongan_darah, jumlah_kantong, keperluan, prioritas, status, tanggal_permintaan) VALUES
('PMT-2026-001', 'Hendra Wijaya', 'RS Harapan Sehat', 'O', 3, 'Operasi jantung', 'Emergency', 'Menunggu', '2026-06-02'),
('PMT-2026-002', 'Rina Marlina', 'RS Medika Utama', 'A', 2, 'Kecelakaan', 'Urgent', 'Diproses', '2026-06-01'),
('PMT-2026-003', 'Agus Setiawan', 'RS Umum Daerah', 'B', 1, 'Thalasemia', 'Normal', 'Selesai', '2026-06-01'),
('PMT-2026-004', 'Lina Kusuma', 'RS Bunda Sejahtera', 'AB', 4, 'Melahirkan', 'Urgent', 'Selesai', '2026-05-31'),
('PMT-2026-005', 'Tono Prasetyo', 'RS Harapan Sehat', 'O', 2, 'DBD', 'Normal', 'Menunggu', '2026-05-30');

-- Jadwal Donor
INSERT INTO Jadwal_Donor (nama_kegiatan, lokasi, tanggal, waktu_mulai, waktu_selesai, deskripsi, target_donor, penyelenggara, status) VALUES
('Donor Darah Kampus', 'Universitas Indonesia, Depok', '2026-06-15', '08:00:00', '15:00:00', 'Kegiatan donor darah HUT PMI', 100, 'PMI Pusat', 'Dijadwalkan'),
('Donor Darah Mall', 'Grand Indonesia Mall, Jakarta', '2026-06-20', '10:00:00', '18:00:00', 'Booth donor darah umum', 50, 'PMI Jakarta', 'Dijadwalkan'),
('Donor Darah Instansi', 'Kantor Dinkes Provinsi', '2026-06-25', '09:00:00', '14:00:00', 'Donor darah bersama Dinkes', 75, 'Dinkes Jakarta', 'Dijadwalkan'),
('Donor Darah BUMN', 'Kantor Pusat PLN', '2026-05-20', '09:00:00', '15:00:00', 'Donor darah karyawan BUMN', 80, 'PMI Pusat', 'Selesai');

-- Riwayat Donor
INSERT INTO Riwayat_Donor (id_pendonor, tanggal_donor, lokasi_don, golongan_darah, volume_ml, tekanan_darah, hemoglobin, status, petugas) VALUES
(1, '2026-06-01', 'PMI Pusat Jakarta', 'A', 450, '120/80', 14.5, 'Sukses', 'dr. Andi'),
(2, '2026-05-28', 'Donor Darah Kampus UI', 'B', 350, '115/75', 13.8, 'Sukses', 'dr. Siti'),
(3, '2026-05-25', 'PMI Cabang Bandung', 'AB', 450, '125/85', 15.0, 'Sukses', 'dr. Budi'),
(4, '2026-05-20', 'Mall Grand Indonesia', 'O', 450, '110/70', 13.5, 'Sukses', 'dr. Rina'),
(5, '2026-05-15', 'Kantor Dinkes Jakarta', 'A', 450, '130/90', 12.8, 'Gagal', 'dr. Andi');

SELECT '✅ Database PMI berhasil dibuat!' AS Status;