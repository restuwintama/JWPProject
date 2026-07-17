<?php
$notif = '';
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
} elseif (isset($_POST['checkout'])) {
    if (empty($_SESSION['cart'])) {
        $notif = "<div class='bg-red-500 text-white p-3 text-center rounded-xl mb-6'>Keranjang masih kosong!</div>";
    } else {
        $id_pelanggan = $_SESSION['user_id'];
        $subtotal = 0;
        foreach($_SESSION['cart'] as $item) { $subtotal += ($item['price'] * $item['qty']); }
        $total = $subtotal + 5000; // Tambah biaya admin
        
        mysqli_query($conn, "INSERT INTO orders (id_pelanggan, total) VALUES ($id_pelanggan, $total)");
        
        // Simpan data invoice sementara ke session untuk ditampilkan di halaman checkout
        $_SESSION['invoice'] = [
            'id' => mysqli_insert_id($conn),
            'items' => $_SESSION['cart'],
            'subtotal' => $subtotal,
            'total' => $total,
            'date' => date('d-m-Y H:i')
        ];
        
        $_SESSION['cart'] = []; // Kosongkan keranjang
        echo "<script>window.location.href='?p=checkout';</script>";
        exit;
    }
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
?>

<?= $notif ?>
<div class="max-w-4xl mx-auto bg-white p-8 rounded-3xl shadow-sm border border-stone-200">
    <h2 class="text-2xl font-black mb-6 border-b pb-4">Keranjang Anda</h2>
    
    <?php if(empty($cart)): ?>
        <div class="text-center py-10 text-stone-500">
            <p class="mb-4">Keranjang masih kosong.</p>
            <a href="?p=shop" class="bg-emerald-600 text-white px-6 py-2 rounded-full">Mulai Belanja</a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto mb-6">
            <table class="w-full text-left border-collapse">
                <thead class="bg-stone-50 text-stone-600 text-sm">
                    <tr>
                        <th class="p-4 border-b">Produk</th>
                        <th class="p-4 border-b">Tipe</th>
                        <th class="p-4 border-b">Harga</th>
                        <th class="p-4 border-b text-center">Qty</th>
                        <th class="p-4 border-b">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($cart as $item): 
                        $sub = $item['price'] * $item['qty']; $total += $sub; 
                    ?>
                    <tr class="border-b hover:bg-stone-50">
                        <td class="p-4 flex items-center space-x-3">
                            <img src="<?= $item['image'] ?>" class="w-12 h-12 rounded object-cover">
                            <span class="font-bold"><?= $item['name'] ?></span>
                        </td>
                        <td class="p-4"><span class="px-2 py-1 text-xs rounded font-bold bg-stone-200"><?= $item['type'] ?></span></td>
                        <td class="p-4 text-stone-600">Rp <?= number_format($item['price'],0,',','.') ?></td>
                        <td class="p-4 text-center font-bold"><?= $item['qty'] ?></td>
                        <td class="p-4 font-black text-emerald-700">Rp <?= number_format($sub,0,',','.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="flex flex-col sm:flex-row justify-between items-center bg-stone-50 p-6 rounded-2xl border border-stone-200">
            <form method="POST"><button type="submit" name="clear_cart" class="text-red-500 hover:underline font-bold text-sm">Kosongkan Keranjang</button></form>
            <div class="text-right mt-4 sm:mt-0">
                <div class="text-sm text-stone-500 mb-1">Subtotal Alat: Rp <?= number_format($total,0,',','.') ?></div>
                <div class="text-sm text-stone-500 mb-2">Biaya Admin: Rp 5.000</div>
                <div class="text-xl font-bold text-stone-800 bg-white px-6 py-2 rounded-xl shadow-sm border border-stone-100">
                    Total Estimasi: <span class="text-emerald-700 font-black text-2xl ml-2">Rp <?= number_format($total + 5000,0,',','.') ?></span>
                </div>
            </div>
        </div>

        <div class="mt-8 border-t border-stone-200 pt-8">
            <?php if(isset($_SESSION['user_id'])): 
                // Ambil data user terkini untuk konfirmasi alamat
                $u_id = $_SESSION['user_id'];
                $u_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_lengkap, no_telepon, alamat FROM users WHERE id_pelanggan=$u_id"));
            ?>
                <div class="bg-blue-50 border border-blue-200 p-6 rounded-2xl mb-6">
                    <h3 class="font-black text-blue-800 mb-4 border-b border-blue-200 pb-2">📍 Konfirmasi Data Pengambil / Pengiriman</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-900">
                        <div><span class="font-bold block text-blue-700">Nama Penerima:</span> <?= $u_data['nama_lengkap'] ?></div>
                        <div><span class="font-bold block text-blue-700">No. HP / WA:</span> <?= $u_data['no_telepon'] ?></div>
                        <div class="md:col-span-2"><span class="font-bold block text-blue-700">Alamat Terdaftar:</span> <?= $u_data['alamat'] ?></div>
                    </div>
                    <div class="mt-4 text-xs font-bold text-blue-600">
                        * Ingin mengubah alamat atau nomor telepon? <a href="?p=profile" class="underline hover:text-blue-800">Edit Profil Anda di sini</a> sebelum checkout.
                    </div>
                </div>

                <form method="POST" class="text-right">
                    <button type="submit" name="checkout" class="bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 px-10 rounded-xl transition shadow-lg text-lg">KONFIRMASI & PROSES CHECKOUT</button>
                </form>
            <?php else: ?>
                <div class="bg-amber-100 text-amber-800 p-4 rounded-xl text-center font-bold">
                    Anda harus <a href="?p=login" class="underline">Login</a> terlebih dahulu untuk melakukan Checkout.
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>