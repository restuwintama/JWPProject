<?php
session_start();

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'arunika_db';

$conn_init = mysqli_connect($db_host, $db_user, $db_pass);
if (!$conn_init) { 
    die("Koneksi MySQL gagal. Pastikan XAMPP (MySQL) sudah di-START: " . mysqli_connect_error()); 
}

mysqli_query($conn_init, "CREATE DATABASE IF NOT EXISTS $db_name");
mysqli_close($conn_init);

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS users (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    nama_lengkap VARCHAR(100),
    alamat TEXT,
    no_telepon VARCHAR(20),
    email VARCHAR(100),
    role ENUM('admin', 'user') DEFAULT 'user'
)");

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS products (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(100),
    kategori VARCHAR(50),
    tipe_layanan ENUM('Sewa', 'Beli') DEFAULT 'Sewa',
    harga INT,
    stok INT,
    deskripsi TEXT,
    gambar VARCHAR(255)
)");

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS orders (
    id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
    id_pelanggan INT,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    total INT,
    status VARCHAR(50) DEFAULT 'Menunggu Konfirmasi',
    FOREIGN KEY (id_pelanggan) REFERENCES users(id_pelanggan)
)");

$check_admin = mysqli_query($conn, "SELECT * FROM users WHERE username='admin'");
if (mysqli_num_rows($check_admin) == 0) {
    mysqli_query($conn, "INSERT INTO users (username, password, nama_lengkap, role) VALUES ('admin', '123', 'Administrator Basecamp', 'admin')");
}

$check_products = mysqli_query($conn, "SELECT * FROM products");
if (mysqli_num_rows($check_products) == 0) {
    mysqli_query($conn, "INSERT INTO products (nama_produk, kategori, tipe_layanan, harga, stok, deskripsi, gambar) VALUES 
    ('Tenda Dome 4P', 'Tenda', 'Sewa', 45000, 10, 'Tenda kapasitas 4 orang anti badai.', 'https://images.unsplash.com/photo-1510312305653-8ed496efae75?auto=format&fit=crop&w=400&q=80'),
    ('Carrier 60L Consina', 'Carrier', 'Sewa', 35000, 15, 'Tas gunung ukuran 60 Liter nyaman di punggung.', 'https://images.unsplash.com/photo-1622260614153-03223fb72052?auto=format&fit=crop&w=400&q=80'),
    ('Sleeping Bag Polar', 'Tidur', 'Beli', 150000, 20, 'Kantung tidur baru (Brand New) berbahan polar hangat.', 'https://images.unsplash.com/photo-1542617651-7f972b2203ba?auto=format&fit=crop&w=400&q=80'),
    ('Headlamp LED', 'Penerangan', 'Beli', 85000, 30, 'Senter kepala LED rechargeable terang dan awet.', 'https://images.unsplash.com/photo-1517429128955-67ff5c1e29f4?auto=format&fit=crop&w=400&q=80')");
}
?>