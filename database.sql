-- ============================================================
-- Database: e_pengaduan
-- Sistem E-Pengaduan (Ticketing/Helpdesk Layanan Internal)
-- ============================================================

CREATE DATABASE IF NOT EXISTS e_pengaduan
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE e_pengaduan;

-- ------------------------------------------------------------
-- Tabel: pengguna
-- Menyimpan data semua pengguna sistem (Admin, Petugas, Pelapor)
-- ------------------------------------------------------------
CREATE TABLE pengguna (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nama        VARCHAR(100)    NOT NULL,
    username    VARCHAR(50)     NOT NULL UNIQUE,
    password    VARCHAR(255)    NOT NULL,
    peran       ENUM('admin','petugas','pelapor') NOT NULL DEFAULT 'pelapor',
    email       VARCHAR(100)    DEFAULT NULL,
    created_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel: kategori
-- Kategori masalah pengaduan (dikelola oleh Admin)
-- ------------------------------------------------------------
CREATE TABLE kategori (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori   VARCHAR(100)    NOT NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel: pengaduan
-- Data tiket pengaduan yang dibuat oleh Pelapor
-- ------------------------------------------------------------
CREATE TABLE pengaduan (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT             NOT NULL,
    kategori_id INT             NOT NULL,
    judul       VARCHAR(200)    NOT NULL,
    deskripsi   TEXT            NOT NULL,
    status      ENUM('menunggu','diproses','selesai') NOT NULL DEFAULT 'menunggu',
    petugas_id  INT             DEFAULT NULL,
    created_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_pengaduan_user
        FOREIGN KEY (user_id) REFERENCES pengguna(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_pengaduan_kategori
        FOREIGN KEY (kategori_id) REFERENCES kategori(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_pengaduan_petugas
        FOREIGN KEY (petugas_id) REFERENCES pengguna(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel: tanggapan
-- Balasan/komentar pada tiket pengaduan
-- ------------------------------------------------------------
CREATE TABLE tanggapan (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    pengaduan_id    INT         NOT NULL,
    user_id         INT         NOT NULL,
    isi_tanggapan   TEXT        NOT NULL,
    created_at      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_tanggapan_pengaduan
        FOREIGN KEY (pengaduan_id) REFERENCES pengaduan(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_tanggapan_user
        FOREIGN KEY (user_id) REFERENCES pengguna(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- Data Awal (Seed)
-- ============================================================

-- Password default: "password123" (bcrypt hash)
INSERT INTO pengguna (nama, username, password, peran, email) VALUES
('Administrator',   'admin',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin',    'admin@pengaduan.local'),
('Budi Petugas',    'petugas1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'petugas',  'budi@pengaduan.local'),
('Sari Petugas',    'petugas2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'petugas',  'sari@pengaduan.local'),
('Andi Pelapor',    'pelapor1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pelapor',  'andi@pengaduan.local'),
('Dewi Pelapor',    'pelapor2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pelapor',  'dewi@pengaduan.local');

INSERT INTO kategori (nama_kategori) VALUES
('Jaringan & Internet'),
('Perangkat Keras'),
('Perangkat Lunak'),
('Akun & Akses'),
('Fasilitas Gedung'),
('Lainnya');

-- Contoh pengaduan awal
INSERT INTO pengaduan (user_id, kategori_id, judul, deskripsi, status, petugas_id) VALUES
(4, 1, 'WiFi lantai 3 tidak bisa konek',        'Sejak kemarin sore WiFi di lantai 3 tidak bisa diakses. Sudah coba restart perangkat tetapi tetap tidak bisa.', 'diproses',  2),
(4, 3, 'Microsoft Office error saat dibuka',     'Aplikasi Word dan Excel menampilkan pesan error saat dibuka. Sudah coba reinstall tapi masih sama.',           'menunggu',  NULL),
(5, 2, 'Printer ruang meeting mati total',       'Printer di ruang meeting lantai 2 tidak menyala sama sekali. Lampu indikator mati.',                           'selesai',   3),
(5, 4, 'Tidak bisa login ke sistem absensi',     'Akun saya di sistem absensi menampilkan pesan "akun terkunci". Mohon bantuan untuk reset.',                    'menunggu',  NULL);

-- Contoh tanggapan
INSERT INTO tanggapan (pengaduan_id, user_id, isi_tanggapan) VALUES
(1, 2, 'Terima kasih atas laporannya. Kami sedang melakukan pengecekan pada access point lantai 3.'),
(3, 3, 'Printer sudah diperbaiki. Masalah ada pada kabel power yang longgar. Silakan dicoba kembali.');
