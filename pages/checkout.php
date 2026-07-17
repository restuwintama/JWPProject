<?php
if (!isset($_SESSION['invoice'])) { 
    echo "<script>window.location.href='?p=home';</script>"; exit; 
}
$inv = $_SESSION['invoice'];
?>
<div class="max-w-3xl mx-auto py-12">
    <div class="bg-white p-10 shadow-2xl border-t-[12px] border-emerald-700 rounded-3xl">
        <div class="text-center mb-10 border-b border-stone-100 pb-8">
            <h1 class="text-3xl font-black uppercase tracking-widest text-stone-800">Nota Sewa Arunika</h1>
            <p class="text-stone-500 mt-2 font-medium">Jl. Lereng Gunung No. 7, Malang</p>
            <div class="inline-block mt-4 bg-stone-100 border border-stone-200 text-stone-700 px-6 py-2 rounded-full font-black text-lg">
               KODE BOOKING: #<?= $inv['id'] ?>
            </div>
            <p class="text-stone-400 text-sm mt-2">Tanggal: <?= $inv['date'] ?></p>
        </div>
        
        <table class="w-full mb-8 border-collapse">
            <thead class="bg-stone-50 text-left border-y border-stone-200">
                <tr>
                    <th class="p-4 font-bold text-stone-600 text-sm uppercase">Nama Alat</th>
                    <th class="p-4 text-center font-bold text-stone-600 text-sm uppercase">Qty</th>
                    <th class="p-4 text-right font-bold text-stone-600 text-sm uppercase">Tarif/Hari</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                <?php foreach($inv['items'] as $item): ?>
                <tr>
                    <td class="p-4 font-bold text-stone-800"><?= $item['name'] ?></td>
                    <td class="p-4 text-center font-medium text-stone-600"><?= $item['qty'] ?></td>
                    <td class="p-4 text-right font-medium text-stone-600">Rp <?= number_format($item['price'],0,',','.') ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colSpan="2" class="p-4 text-right font-bold text-stone-500 border-t border-stone-200 pt-6">Subtotal Alat/Hari</td>
                    <td class="p-4 text-right font-bold text-stone-800 border-t border-stone-200 pt-6">Rp <?= number_format($inv['subtotal'],0,',','.') ?></td>
                </tr>
                <tr>
                    <td colSpan="2" class="px-4 py-2 text-right font-bold text-stone-500">Biaya Admin Platform</td>
                    <td class="px-4 py-2 text-right text-stone-600 font-medium">Rp 5.000</td>
                </tr>
                <tr class="bg-emerald-50">
                    <td colSpan="2" class="p-5 text-right font-black text-lg text-emerald-900 rounded-bl-xl mt-4 border-t border-emerald-200">TOTAL ESTIMASI</td>
                    <td class="p-5 text-right font-black text-xl text-emerald-900 rounded-br-xl mt-4 border-t border-emerald-200">Rp <?= number_format($inv['total'],0,',','.') ?></td>
                </tr>
            </tbody>
        </table>

        <div class="bg-amber-50 p-5 rounded-2xl border border-amber-100 text-amber-800 text-sm mb-8">
            <p class="font-bold mb-2">📌 Catatan Penting:</p>
            <ul class="list-disc list-inside space-y-1 ml-1 opacity-90">
                <li>Tunjukkan bukti/kode booking ini ke admin Basecamp.</li>
                <li>Perhitungan 1 Hari = 24 Jam sejak barang diambil.</li>
                <li>Pesanan ini menunggu persetujuan (Acc) dari admin.</li>
            </ul>
        </div>
        
        <div class="mt-8 flex justify-center">
             <a href="?p=home" class="bg-stone-900 hover:bg-stone-800 text-white font-bold px-10 py-3.5 rounded-full transition shadow-lg">
               Kembali ke Beranda
             </a>
        </div>
    </div>
</div>