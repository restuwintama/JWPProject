<?php
$user_logged_in = isset($_SESSION['user_id']);
$is_admin = ($user_logged_in && $_SESSION['role'] === 'admin');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arunika Outdoor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .fade-in { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-stone-100 text-stone-800 font-sans min-h-screen flex flex-col">

<nav class="bg-emerald-700 text-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <a href="?p=home" class="flex items-center space-x-2 font-black text-xl tracking-wide">
            <div class="bg-white text-emerald-700 rounded-full w-8 h-8 flex items-center justify-center">⛰️</div>
            <span>Arunika <span class="font-light">Outdoor</span></span>
        </a>
        <div class="flex items-center space-x-6 text-sm font-medium">
            <?php if ($is_admin): ?>
                <a href="?p=admin" class="hover:text-emerald-200">Dashboard Admin</a>
            <?php else: ?>
                <a href="?p=home" class="hover:text-emerald-200">Beranda</a>
                <a href="?p=shop" class="hover:text-emerald-200">Katalog Sewa & Beli</a>
                <a href="?p=cart" class="hover:text-emerald-200 flex items-center">
                    🛒 Keranjang (<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>)
                </a>
            <?php endif; ?>

            <?php if ($user_logged_in): ?>
                <div class="border-l border-emerald-600 pl-4 flex items-center space-x-4">
                    <a href="?p=profile" class="hover:text-emerald-200">Halo, <?= $_SESSION['nama_lengkap'] ?></a>
                    <a href="?p=logout" class="bg-emerald-900 hover:bg-emerald-800 px-3 py-1.5 rounded transition">Keluar</a>
                </div>
            <?php else: ?>
                <div class="border-l border-emerald-600 pl-4">
                    <a href="?p=login" class="bg-amber-500 hover:bg-amber-600 text-stone-900 px-4 py-1.5 rounded-full font-bold transition">Masuk</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="container mx-auto px-4 py-8 flex-1">