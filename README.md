# 🩸 PMI Smart Blood Management System

[![PHP](https://img.shields.io/badge/PHP-8.1+-blue?style=for-the-badge&logo=php)]()
[![MySQL](https://img.shields.io/badge/MySQL-8.0-orange?style=for-the-badge&logo=mysql)]()
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple?style=for-the-badge&logo=bootstrap)]()
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

> **Sistem Informasi Manajemen Stok Darah dan Donor Darah PMI berbasis Web.** Aplikasi modern untuk mengelola stok darah, data pendonor, permintaan darah dari rumah sakit, jadwal kegiatan donor, dan laporan real-time dengan desain profesional dan responsive.

---

## 🚀 Demo & Deploy

### 🔗 **Aplikasi Live:** [https://pmi-smart-blood.000webhostapp.com](https://pmi-smart-blood.000webhostapp.com)

> **⚠️ Catatan:** 
> - Aplikasi ini memerlukan hosting dengan support **PHP + MySQL**
> - Untuk demo lokal, gunakan **XAMPP/WAMP/Laragon**
> - Link di atas adalah contoh - ganti dengan domain/hosting Anda

### 📱 **Video Demo:** [Tonton di YouTube](https://youtube.com) *(optional)*

---

## 📋 Fitur Utama

### 📊 **Dashboard**
- Statistik real-time (Total pendonor, stok darah, permintaan, jadwal)
- Grafik ketersediaan darah per golongan
- Timeline aktivitas terbaru
- Status sistem monitoring

### 🩸 **Manajemen Stok Darah**
- Monitoring stok darah A, B, AB, O
- Indikator status (Tersedia, Menipis, Kritis, Habis)
- Auto-update status berdasarkan jumlah stok
- Grafik ketersediaan darah interaktif

### 👥 **Manajemen Pendonor**
- Database pendonor terintegrasi
- Pencarian dan filter data
- Export data ke PDF/Excel
- Riwayat donor per individu
- Validasi NIK unik

### 📋 **Permintaan Darah**
- Sistem permintaan dari rumah sakit
- Tracking status (Menunggu, Diproses, Selesai, Ditolak)
- Prioritas permintaan (Normal, Urgent, Emergency)
- Auto-generate kode permintaan

### 📅 **Jadwal Donor**
- Penjadwalan kegiatan donor darah
- Manajemen lokasi dan waktu
- Target dan realisasi pendonor
- Status kegiatan (Dijadwalkan, Berlangsung, Selesai)

### 📜 **Riwayat Donor**
- Tracking historis donor
- Data tekanan darah & hemoglobin
- Volume darah per donasi
- Status keberhasilan donor

### 📈 **Laporan & Analisis**
- Laporan bulanan/tahunan
- Grafik tren donor
- Statistik permintaan vs ketersediaan
- Export laporan PDF/Excel

### ⚙️ **Pengaturan Sistem**
- Konfigurasi minimum/maksimum stok
- Data profil PMI
- Manajemen user & role
- Log aktivitas sistem

---

## 🛠️ Teknologi yang Digunakan

| Kategori | Teknologi | Versi |
|----------|-----------|-------|
| **Frontend** | HTML5, CSS3, JavaScript | ES6+ |
| **UI Framework** | Bootstrap 5 | 5.3.x |
| **Chart Library** | Chart.js | 4.x |
| **Icons** | Font Awesome | 6.4.x |
| **Backend** | PHP Native (PDO) | 8.1+ |
| **Database** | MySQL | 8.0+ |
| **Server** | Apache (XAMPP) | 2.4.x |

---

## 📦 Instalasi & Setup

### **Persyaratan Sistem**
- PHP >= 8.1
- MySQL >= 8.0
- Apache/Nginx Web Server
- Minimal RAM 2GB
- Storage 100MB

### **Langkah Instalasi (Lokal - XAMPP)**

#### 1. **Download & Install XAMPP**
```bash
# Download XAMPP dari:
https://www.apachefriends.org/download.html

# Install XAMPP di:
C:\xampp\ (Windows)
/opt/lampp/ (Linux)
/Applications/XAMPP/ (Mac)
