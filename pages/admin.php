<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { 
    echo "<script>window.location.href='?p=home';</script>"; 
    exit; 
}

if(isset($_GET['acc'])) {
    $id_acc = $_GET['acc'];
    mysqli_query($conn, "UPDATE orders SET status='Disetujui' WHERE id_pesanan=$id_acc");
    echo "<script>window.location.href='?p=admin';</script>";
}

// Menarik data pesanan
$orders = mysqli_query($conn, "SELECT o.*, u.nama_lengkap FROM orders o JOIN users u ON o.id_pelanggan = u.id_pelanggan ORDER BY o.tanggal DESC");
?>

<div class="bg-white p-8 rounded-3xl shadow-sm border border-stone-200">
    <h2 class="text-2xl font-black mb-6">Data Booking Masuk (Admin)</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead class="bg-stone-100 text-stone-600">
                <tr>
                    <th class="p-4 border-b">ID Pesanan</th>
                    <th class="p-4 border-b">Tgl/Waktu</th>
                    <th class="p-4 border-b">Pelanggan</th>
                    <th class="p-4 border-b">Total Bayar</th>
                    <th class="p-4 border-b">Status</th>
                    <th class="p-4 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($orders)): ?>
                <tr class="border-b hover:bg-stone-50">
                    <td class="p-4 font-mono font-bold">#<?= $row['id_pesanan'] ?></td>
                    <td class="p-4 text-stone-500"><?= $row['tanggal'] ?></td>
                    <td class="p-4 font-bold"><?= $row['nama_lengkap'] ?></td>
                    <td class="p-4 font-black text-emerald-700">Rp <?= number_format($row['total'],0,',','.') ?></td>
                    <td class="p-4">
                        <span class="px-2 py-1 text-xs rounded font-bold <?= $row['status'] == 'Disetujui' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <?php if($row['status'] == 'Menunggu Konfirmasi'): ?>
                            <a href="?p=admin&acc=<?= $row['id_pesanan'] ?>" class="bg-emerald-500 text-white px-3 py-1.5 rounded font-bold text-xs hover:bg-emerald-600">Setujui</a>
                        <?php else: ?>
                            <span class="text-stone-400 text-xs italic">Selesai</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>