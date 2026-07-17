<?php
$notif = '';
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
} elseif (isset($_POST['checkout'])) {
    if (empty($_SESSION['cart'])) {
        $notif = "<div class='bg-red-500 text-white p-3 text-center rounded-xl mb-6'>Keranjang masih kosong!</div>";
    } else {
        $id_pelanggan = $_SESSION['user_id'];
        $total = 0;
        foreach($_SESSION['cart'] as $item) { $total += ($item['price'] * $item['qty']); }
        
        mysqli_query($conn, "INSERT INTO orders (id_pelanggan, total) VALUES ($id_pelanggan, $total)");
        $_SESSION['cart'] = [];
        $notif = "<div class='bg-emerald-500 text-white p-3 text-center rounded-xl mb-6'>Pesanan berhasil dibuat. Menunggu konfirmasi admin.</div>";
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
        
        <div class="flex justify-between items-center bg-stone-50 p-6 rounded-2xl border border-stone-200">
            <form method="POST"><button type="submit" name="clear_cart" class="text-red-500 hover:underline font-bold text-sm">Kosongkan Keranjang</button></form>
            <div class="text-xl font-bold">
                Total Estimasi: <span class="text-emerald-700 font-black text-2xl ml-2">Rp <?= number_format($total,0,',','.') ?></span>
            </div>
        </div>

        <div class="mt-8 border-t pt-8">
            <?php if(isset($_SESSION['user_id'])): ?>
                <form method="POST" class="text-right">
                    <button type="submit" name="checkout" class="bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 px-10 rounded-xl transition shadow-lg text-lg">PROSES CHECKOUT</button>
                </form>
            <?php else: ?>
                <div class="bg-amber-100 text-amber-800 p-4 rounded-xl text-center font-bold">
                    Anda harus <a href="?p=login" class="underline">Login</a> terlebih dahulu untuk melakukan Checkout.
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>