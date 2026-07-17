<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { 
    echo "<script>window.location.href='?p=home';</script>"; exit; 
}

$tab = isset($_GET['t']) ? $_GET['t'] : 'orders';

// Fungsi Helper Upload Gambar
function uploadGambar($file, $old_path) {
    if (isset($file) && $file['error'] === 0) {
        $dir = 'uploads/';
        if (!is_dir($dir)) mkdir($dir, 0777, true); // Buat folder otomatis
        $filename = $dir . time() . '_' . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $filename)) return $filename;
    }
    return $old_path; // Jika tidak ada foto baru, gunakan foto lama
}

// Aksi Konfirmasi Pesanan
if(isset($_GET['acc'])) { mysqli_query($conn, "UPDATE orders SET status='Disetujui' WHERE id_pesanan=" . $_GET['acc']); echo "<script>window.location.href='?p=admin';</script>"; }
if(isset($_GET['tolak'])) { mysqli_query($conn, "UPDATE orders SET status='Ditolak' WHERE id_pesanan=" . $_GET['tolak']); echo "<script>window.location.href='?p=admin';</script>"; }

// Aksi CRUD Produk (Tambah, Edit, Hapus)
if(isset($_POST['add_product'])) {
    $n = $_POST['nama_produk']; $k = $_POST['kategori']; $t = $_POST['tipe_layanan'];
    $h = $_POST['harga']; $s = $_POST['stok']; $d = $_POST['deskripsi']; 
    $g = uploadGambar($_FILES['gambar_file'], '');
    mysqli_query($conn, "INSERT INTO products (nama_produk, kategori, tipe_layanan, harga, stok, deskripsi, gambar) VALUES ('$n', '$k', '$t', '$h', '$s', '$d', '$g')");
    echo "<script>window.location.href='?p=admin&t=products';</script>";
}
if(isset($_POST['edit_product'])) {
    $id = $_POST['id_produk']; $n = $_POST['nama_produk']; $k = $_POST['kategori']; $t = $_POST['tipe_layanan'];
    $h = $_POST['harga']; $s = $_POST['stok']; $d = $_POST['deskripsi']; 
    $g = uploadGambar($_FILES['gambar_file'], $_POST['gambar_lama']);
    mysqli_query($conn, "UPDATE products SET nama_produk='$n', kategori='$k', tipe_layanan='$t', harga='$h', stok='$s', deskripsi='$d', gambar='$g' WHERE id_produk=$id");
    echo "<script>window.location.href='?p=admin&t=products';</script>";
}
if(isset($_GET['del_product'])) {
    mysqli_query($conn, "DELETE FROM products WHERE id_produk=" . $_GET['del_product']);
    echo "<script>window.location.href='?p=admin&t=products';</script>";
}

// Menarik Data
$orders = mysqli_query($conn, "SELECT o.*, u.nama_lengkap FROM orders o JOIN users u ON o.id_pelanggan = u.id_pelanggan ORDER BY o.tanggal DESC");
$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id_produk DESC");
$customers = mysqli_query($conn, "SELECT * FROM users WHERE role='user' ORDER BY id_pelanggan DESC");

// Query Statistik
$total_trx = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders WHERE DATE(tanggal) = CURDATE()"));
$trx_selesai = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders WHERE status='Disetujui'"));
$total_pelanggan = mysqli_num_rows($customers);
?>

<div class="flex flex-col md:flex-row gap-6">
    <!-- Sidebar -->
    <div class="w-full md:w-64 bg-stone-900 text-white rounded-2xl p-5 shadow-lg">
        <h3 class="font-black text-xl mb-4 border-b border-stone-700 pb-3">DASHBOARD</h3>
        <ul class="space-y-2">
            <li><a href="?p=admin&t=orders" class="block px-4 py-2 rounded-lg font-bold <?= $tab=='orders' ? 'bg-emerald-600' : 'hover:bg-stone-800' ?>">Pesanan Masuk</a></li>
            <li><a href="?p=admin&t=products" class="block px-4 py-2 rounded-lg font-bold <?= $tab=='products' ? 'bg-emerald-600' : 'hover:bg-stone-800' ?>">Kelola Produk</a></li>
            <li><a href="?p=admin&t=customers" class="block px-4 py-2 rounded-lg font-bold <?= $tab=='customers' ? 'bg-emerald-600' : 'hover:bg-stone-800' ?>">Data Pelanggan</a></li>
        </ul>
    </div>

    <!-- Content Area -->
    <div class="flex-1 bg-white p-6 rounded-2xl shadow-sm border border-stone-200">
        <?php if($tab == 'orders'): ?>
            <h2 class="text-2xl font-black mb-4 border-b pb-3">Dashboard & Data Booking</h2>
            
            <!-- Statistik Card -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-stone-50 p-4 rounded-xl border border-stone-200">
                    <div class="text-sm font-bold text-stone-500 mb-1">Transaksi Hari Ini</div>
                    <div class="text-3xl font-black text-stone-800"><?= $total_trx ?></div>
                </div>
                <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-200">
                    <div class="text-sm font-bold text-emerald-600 mb-1">Transaksi Disetujui (Selesai)</div>
                    <div class="text-3xl font-black text-emerald-700"><?= $trx_selesai ?></div>
                </div>
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                    <div class="text-sm font-bold text-blue-600 mb-1">Total Pelanggan Terdaftar</div>
                    <div class="text-3xl font-black text-blue-700"><?= $total_pelanggan ?></div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-stone-100 text-stone-600 border-b">
                        <tr><th class="p-3">ID</th><th class="p-3">Tanggal</th><th class="p-3">Penyewa</th><th class="p-3">Total</th><th class="p-3">Status</th><th class="p-3 text-center">Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($orders)): ?>
                        <tr class="border-b hover:bg-stone-50">
                            <td class="p-3 font-bold">#<?= $row['id_pesanan'] ?></td> <td class="p-3"><?= $row['tanggal'] ?></td>
                            <td class="p-3"><?= $row['nama_lengkap'] ?></td> <td class="p-3 font-bold text-emerald-700">Rp <?= number_format($row['total'],0,',','.') ?></td>
                            <td class="p-3">
                                <span class="px-2 py-1 text-xs rounded font-bold <?= $row['status'] == 'Disetujui' ? 'bg-emerald-100 text-emerald-700' : ($row['status'] == 'Ditolak' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <?php if($row['status'] == 'Menunggu Konfirmasi'): ?>
                                    <a href="?p=admin&acc=<?= $row['id_pesanan'] ?>" class="bg-emerald-500 text-white px-2 py-1 rounded text-xs font-bold shadow">Acc</a>
                                    <a href="?p=admin&tolak=<?= $row['id_pesanan'] ?>" class="bg-red-500 text-white px-2 py-1 rounded text-xs font-bold shadow">Tolak</a>
                                <?php else: ?>
                                    <span class="text-stone-400 italic text-xs">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif($tab == 'products'): ?>
            <div class="flex justify-between items-center mb-4 border-b pb-3">
                <h2 class="text-xl font-black">Kelola Produk Alat</h2>
                <button onclick="document.getElementById('formAdd').classList.toggle('hidden')" class="bg-emerald-600 text-white px-4 py-2 rounded-lg font-bold shadow text-sm">+ Tambah Baru</button>
            </div>

            <!-- Form Tambah (Hidden) -->
            <form id="formAdd" method="POST" enctype="multipart/form-data" class="hidden bg-stone-50 p-4 rounded-xl border mb-6 grid grid-cols-2 gap-4">
                <div><label class="text-xs font-bold block mb-1">Nama Alat</label><input type="text" name="nama_produk" required class="w-full border p-2 rounded"></div>
                <div><label class="text-xs font-bold block mb-1">Kategori</label><input type="text" name="kategori" required class="w-full border p-2 rounded"></div>
                <div><label class="text-xs font-bold block mb-1">Tipe</label><select name="tipe_layanan" class="w-full border p-2 rounded"><option>Sewa</option><option>Beli</option></select></div>
                <div><label class="text-xs font-bold block mb-1">Harga (Rp)</label><input type="number" name="harga" required class="w-full border p-2 rounded"></div>
                <div><label class="text-xs font-bold block mb-1">Stok</label><input type="number" name="stok" required class="w-full border p-2 rounded"></div>
                <div><label class="text-xs font-bold block mb-1">Upload Foto</label><input type="file" name="gambar_file" accept="image/*" class="w-full border p-1 rounded bg-white text-sm"></div>
                <div class="col-span-2"><label class="text-xs font-bold block mb-1">Deskripsi</label><textarea name="deskripsi" required class="w-full border p-2 rounded"></textarea></div>
                <button type="submit" name="add_product" class="col-span-2 bg-stone-900 text-white py-2 rounded font-bold">Simpan Produk</button>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-stone-100 text-stone-600 border-b"><tr><th class="p-3">ID</th><th class="p-3">Foto</th><th class="p-3">Nama Alat</th><th class="p-3">Harga</th><th class="p-3">Stok</th><th class="p-3">Aksi</th></tr></thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($products)): ?>
                        <tr class="border-b hover:bg-stone-50">
                            <td class="p-3 font-bold text-stone-400">#<?= $row['id_produk'] ?></td>
                            <td class="p-2"><img src="<?= $row['gambar'] ?>" class="w-10 h-10 object-cover rounded shadow-sm border" alt="img"></td>
                            <td class="p-3 font-bold"><?= $row['nama_produk'] ?> <span class="text-xs font-normal bg-stone-200 px-1 rounded ml-1"><?= $row['tipe_layanan'] ?></span></td>
                            <td class="p-3">Rp <?= number_format($row['harga'],0,',','.') ?></td> <td class="p-3"><?= $row['stok'] ?></td>
                            <td class="p-3">
                                <button onclick="editItem(<?= htmlspecialchars(json_encode($row)) ?>)" class="bg-blue-500 text-white px-2 py-1 rounded text-xs font-bold">Edit</button>
                                <a href="?p=admin&del_product=<?= $row['id_produk'] ?>" onclick="return confirm('Hapus alat ini?')" class="bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">Del</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Form Edit -->
            <form id="formEdit" method="POST" enctype="multipart/form-data" class="hidden mt-6 bg-blue-50 p-4 rounded-xl border border-blue-200 grid grid-cols-2 gap-4">
                <div class="col-span-2 font-black text-blue-800 border-b border-blue-200 pb-2">Edit Produk</div>
                <input type="hidden" name="id_produk" id="e_id">
                <input type="hidden" name="gambar_lama" id="e_gbr_lama"> <!-- Simpan path gambar lama -->
                <div><label class="text-xs font-bold block mb-1">Nama Alat</label><input type="text" name="nama_produk" id="e_nama" required class="w-full border p-2 rounded"></div>
                <div><label class="text-xs font-bold block mb-1">Kategori</label><input type="text" name="kategori" id="e_kat" required class="w-full border p-2 rounded"></div>
                <div><label class="text-xs font-bold block mb-1">Tipe</label><select name="tipe_layanan" id="e_tipe" class="w-full border p-2 rounded"><option>Sewa</option><option>Beli</option></select></div>
                <div><label class="text-xs font-bold block mb-1">Harga (Rp)</label><input type="number" name="harga" id="e_harga" required class="w-full border p-2 rounded"></div>
                <div><label class="text-xs font-bold block mb-1">Stok</label><input type="number" name="stok" id="e_stok" required class="w-full border p-2 rounded"></div>
                <div><label class="text-xs font-bold block mb-1">Update Foto (Opsional)</label><input type="file" name="gambar_file" accept="image/*" class="w-full border p-1 rounded bg-white text-sm"></div>
                <div class="col-span-2"><label class="text-xs font-bold block mb-1">Deskripsi</label><textarea name="deskripsi" id="e_desc" required class="w-full border p-2 rounded"></textarea></div>
                <button type="submit" name="edit_product" class="col-span-2 bg-blue-600 text-white py-2 rounded font-bold">Update Produk</button>
            </form>

            <script>
            function editItem(data) {
                document.getElementById('formEdit').classList.remove('hidden');
                document.getElementById('formAdd').classList.add('hidden');
                document.getElementById('e_id').value = data.id_produk;
                document.getElementById('e_gbr_lama').value = data.gambar;
                document.getElementById('e_nama').value = data.nama_produk;
                document.getElementById('e_kat').value = data.kategori;
                document.getElementById('e_tipe').value = data.tipe_layanan;
                document.getElementById('e_harga').value = data.harga;
                document.getElementById('e_stok').value = data.stok;
                document.getElementById('e_desc').value = data.deskripsi;
                document.getElementById('formEdit').scrollIntoView({behavior: "smooth"});
            }
            </script>
            
        <?php elseif($tab == 'customers'): ?>
            <h2 class="text-xl font-black mb-4 border-b pb-3">Data Member Pelanggan</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-stone-100 text-stone-600 border-b">
                        <tr><th class="p-3">ID</th><th class="p-3">Nama Lengkap</th><th class="p-3">Email</th><th class="p-3">No Telepon</th><th class="p-3">Alamat</th></tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($customers)): ?>
                        <tr class="border-b hover:bg-stone-50">
                            <td class="p-3 font-bold">#<?= $row['id_pelanggan'] ?></td>
                            <td class="p-3 font-bold"><?= $row['nama_lengkap'] ?></td>
                            <td class="p-3 text-stone-500"><?= $row['email'] ?></td>
                            <td class="p-3"><?= $row['no_telepon'] ?></td>
                            <td class="p-3 text-xs"><?= $row['alamat'] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>