<?php
require_once 'koneksi.php';

$page = isset($_GET['p']) ? $_GET['p'] : 'home';

// Handle Logout khusus
if ($page == 'logout') {
    session_destroy();
    header("Location: index.php");
    exit();
}

include 'header.php';

$allowed_pages = ['home', 'shop', 'cart', 'login', 'register', 'profile', 'admin'];

if (in_array($page, $allowed_pages)) {
    // Memasukkan isi halaman yang sesuai dari folder 'pages'
    include "pages/{$page}.php";
} else {
    echo "<div class='text-center py-20 text-xl font-bold'>Halaman tidak ditemukan (404).</div>";
}

include 'footer.php';
?>