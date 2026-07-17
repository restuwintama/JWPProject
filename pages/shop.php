<?php
$notif = '';
if (isset($_POST['add_to_cart'])) {
    $id_produk = $_POST['id_produk'];
    if(!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
    
    $found = false;
    foreach($_SESSION['cart'] as &$item) {
        if($item['id'] == $id_produk) { $item['qty']++; $found = true; break; }
    }
    if(!$found) {
        $prod_query = mysqli_query($conn, "SELECT * FROM products WHERE id_produk=$id_produk");
        $p = mysqli_fetch_assoc($prod_query);
        $_SESSION['cart'][] = [
            'id' => $p['id_produk'], 'name' => $p['nama_produk'], 'price' => $p['harga'], 
            'qty' => 1, 'type' => $p['tipe_layanan'], 'image' => $p['gambar']
        ];
    }
    $notif = "<div class='bg-emerald-500 text-white p-3 text-center rounded-xl mb-6'>Alat ditambahkan ke keranjang!</div>";
}

$result = mysqli_query($conn, "SELECT * FROM products");
?>

<?= $notif ?>
<h2 class="text-3xl font-black text-center mb-8 text-stone-800">Katalog Alat Outdoor</h2>

<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden flex flex-col hover:shadow-lg transition relative">
            <div class="absolute top-3 right-3 text-xs font-bold px-3 py-1 rounded-full text-white <?= $row['tipe_layanan'] == 'Sewa' ? 'bg-emerald-500' : 'bg-blue-500' ?>">
                <?= $row['tipe_layanan'] == 'Sewa' ? 'Disewakan /Hari' : 'Dijual (Beli Putus)' ?>
            </div>
            
            <img src="<?= $row['gambar'] ?>" class="w-full h-48 object-cover">
            <div class="p-4 flex flex-col flex-grow">
                <span class="text-xs text-stone-400 font-bold uppercase mb-1"><?= $row['kategori'] ?></span>
                <h3 class="font-bold text-lg leading-tight mb-2"><?= $row['nama_produk'] ?></h3>
                <p class="text-sm text-stone-500 mb-4 flex-grow"><?= $row['deskripsi'] ?></p>
                
                <div class="flex justify-between items-center mt-auto border-t pt-4">
                    <span class="font-black text-emerald-700 text-lg">Rp <?= number_format($row['harga'],0,',','.') ?></span>
                    <span class="text-xs font-medium <?= $row['stok']>0 ? 'text-stone-600' : 'text-red-500' ?>">
                        <?= $row['stok']>0 ? 'Stok: '.$row['stok'] : 'Habis' ?>
                    </span>
                </div>
            </div>
            
            <form method="POST" class="p-4 pt-0">
                <input type="hidden" name="id_produk" value="<?= $row['id_produk'] ?>">
                <button type="submit" name="add_to_cart" <?= $row['stok']==0 ? 'disabled' : '' ?> class="w-full py-2 rounded-xl font-bold text-sm transition <?= $row['stok']==0 ? 'bg-stone-200 text-stone-400' : 'bg-stone-900 text-white hover:bg-stone-800' ?>">
                    + Masukkan Keranjang
                </button>
            </form>
        </div>
    <?php endwhile; ?>
</div>