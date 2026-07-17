<?php
if (isset($_POST['add_to_cart'])) {
    $id = $_POST['id_produk'];
    $query = mysqli_query($conn, "SELECT * FROM products WHERE id_produk=$id");
    $p = mysqli_fetch_assoc($query);
    
    if ($p) {
        $item = [
            'id' => $p['id_produk'],
            'name' => $p['nama_produk'],
            'price' => $p['harga'],
            'image' => $p['gambar'],
            'type' => $p['tipe_layanan'],
            'qty' => 1
        ];
        
        $found = false;
        if(isset($_SESSION['cart'])){
            foreach($_SESSION['cart'] as &$c) {
                if($c['id'] == $id) { $c['qty']++; $found = true; break; }
            }
        } else {
            $_SESSION['cart'] = [];
        }
        
        if(!$found) { $_SESSION['cart'][] = $item; }
        echo "<script>alert('Produk berhasil ditambahkan ke keranjang!'); window.location.href='?p=shop';</script>";
    }
}

// Menarik semua data produk dari database
$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id_produk DESC");
?>

<div class="max-w-7xl mx-auto py-8">
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-black text-stone-800 uppercase tracking-widest">Katalog Alat Outdoor</h2>
        <p class="text-stone-500 mt-2 font-medium">Temukan perlengkapan terbaik untuk petualangan Anda.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php while($row = mysqli_fetch_assoc($products)): ?>
            <!-- Card Produk -->
            <div class="bg-white rounded-3xl shadow-sm border border-stone-200 overflow-hidden flex flex-col group hover:shadow-xl transition-all">
                
                <!-- Gambar & Badge -->
                <div class="relative h-48 bg-stone-100 overflow-hidden">
                    <img src="<?= $row['gambar'] ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php if($row['tipe_layanan'] == 'Beli'): ?>
                        <span class="absolute top-3 right-3 bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Dijual</span>
                    <?php else: ?>
                        <span class="absolute top-3 right-3 bg-stone-900/80 backdrop-blur text-white text-xs font-bold px-3 py-1 rounded-full shadow">Disewakan</span>
                    <?php endif; ?>
                </div>
                
                <!-- Info Produk -->
                <div class="p-5 flex flex-col flex-grow">
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-xs text-stone-400 font-bold uppercase"><?= $row['kategori'] ?></span>
                        <span class="text-xs text-stone-400 font-bold">ID: #<?= $row['id_produk'] ?></span>
                    </div>
                    <h3 class="font-bold text-lg leading-tight mb-2 text-stone-800"><?= $row['nama_produk'] ?></h3>
                    <p class="text-stone-500 text-sm mb-4 line-clamp-2"><?= $row['deskripsi'] ?></p>

                    <!-- Harga & Stok -->
                    <div class="mt-auto flex justify-between items-end mb-4 border-t border-stone-100 pt-4">
                        <div>
                            <span class="text-xl font-black text-emerald-700">Rp <?= number_format($row['harga'],0,',','.') ?></span>
                        </div>
                        <div class="text-xs text-stone-500 font-medium">
                            Stok: <?= $row['stok'] ?>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="space-y-2">
                        <button onclick="showDetail('<?= addslashes($row['nama_produk']) ?>', '<?= addslashes($row['deskripsi']) ?>', '<?= $row['gambar'] ?>', <?= $row['harga'] ?>)" class="w-full py-2 border border-stone-300 rounded-xl font-bold text-xs text-stone-600 hover:bg-stone-50 transition">Lihat Detail</button>
                        <form method="POST">
                            <input type="hidden" name="id_produk" value="<?= $row['id_produk'] ?>">
                            <button type="submit" name="add_to_cart" <?= $row['stok']==0 ? 'disabled' : '' ?> class="w-full py-2.5 rounded-xl font-bold text-sm transition <?= $row['stok']==0 ? 'bg-stone-200 text-stone-400 cursor-not-allowed' : 'bg-stone-900 text-white hover:bg-stone-800' ?>">
                                <?= $row['stok']==0 ? 'Habis Tersewa' : '+ Masukkan Keranjang' ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Modal Detail Produk (Hidden by default) -->
<div id="modalDetail" class="fixed inset-0 bg-black/60 z-[99] hidden flex justify-center items-center p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl p-6 max-w-lg w-full relative shadow-2xl">
        <button onclick="document.getElementById('modalDetail').classList.add('hidden')" class="absolute top-4 right-4 bg-stone-100 text-stone-500 hover:text-stone-900 w-8 h-8 flex items-center justify-center rounded-full hover:bg-stone-200 transition font-bold">✕</button>
        
        <img id="m_img" src="" class="w-full h-56 object-cover rounded-2xl mb-5 border border-stone-100 shadow-sm">
        
        <h3 id="m_title" class="text-2xl font-black mb-2 text-stone-800"></h3>
        <p id="m_desc" class="text-stone-600 mb-6 text-sm leading-relaxed"></p>
        
        <div class="flex items-center justify-between border-t border-stone-100 pt-4">
            <div class="text-sm font-bold text-stone-400 uppercase">Tarif / Harga</div>
            <div class="text-2xl font-black text-emerald-700">Rp <span id="m_price"></span></div>
        </div>
    </div>
</div>

<script>
// Fungsi untuk memunculkan popup detail produk
function showDetail(title, desc, img, price) {
    document.getElementById('m_title').innerText = title;
    document.getElementById('m_desc').innerText = desc;
    document.getElementById('m_img').src = img;
    document.getElementById('m_price').innerText = price.toLocaleString('id-ID');
    document.getElementById('modalDetail').classList.remove('hidden');
}
</script>