<?php
if (!isset($_SESSION['user_id'])) { echo "<script>window.location.href='?p=login';</script>"; exit; }

$notif = '';
$id = $_SESSION['user_id'];

if (isset($_POST['update_profile'])) {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $update_query = "UPDATE users SET nama_lengkap='$nama_lengkap', email='$email', no_telepon='$no_telepon', alamat='$alamat'";
    if(!empty($password)) { $update_query .= ", password='$password'"; }
    $update_query .= " WHERE id_pelanggan=$id";
    
    mysqli_query($conn, $update_query);
    $_SESSION['nama_lengkap'] = $nama_lengkap;
    $notif = "<div class='bg-emerald-500 text-white p-3 text-center rounded-xl mb-6'>Profil berhasil diperbarui.</div>";
}

$user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id_pelanggan=$id"));
?>

<?= $notif ?>
<div class="max-w-xl mx-auto bg-white p-8 rounded-3xl shadow-sm border border-stone-200 mt-4">
    <h2 class="text-2xl font-black text-center mb-6 text-stone-800">PROFIL SAYA</h2>
    <div class="mb-6 bg-stone-100 p-4 rounded-xl border border-stone-200 text-sm">
        <span class="font-bold text-stone-500">ID Pelanggan:</span> #<?= $user_data['id_pelanggan'] ?> <br>
        <span class="font-bold text-stone-500">Username:</span> <?= $user_data['username'] ?> (Tidak dapat diubah)
    </div>
    
    <form method="POST" class="grid grid-cols-1 gap-4">
        <div><label class="block text-sm font-bold mb-1">Nama Lengkap</label>
             <input type="text" name="nama_lengkap" value="<?= $user_data['nama_lengkap'] ?>" required class="w-full border p-2.5 rounded-xl bg-stone-50"></div>
        <div><label class="block text-sm font-bold mb-1">Email Aktif</label>
             <input type="email" name="email" value="<?= $user_data['email'] ?>" required class="w-full border p-2.5 rounded-xl bg-stone-50"></div>
        <div><label class="block text-sm font-bold mb-1">No. Telepon / WA</label>
             <input type="text" name="no_telepon" value="<?= $user_data['no_telepon'] ?>" required class="w-full border p-2.5 rounded-xl bg-stone-50"></div>
        <div><label class="block text-sm font-bold mb-1">Alamat Domisili Lengkap</label>
             <textarea name="alamat" required rows="3" class="w-full border p-2.5 rounded-xl bg-stone-50"><?= $user_data['alamat'] ?></textarea></div>
        <div class="border-t pt-4 mt-2">
             <label class="block text-sm font-bold text-red-500 mb-1">Ubah Password (Kosongkan jika tidak ingin mengubah)</label>
             <input type="password" name="password" placeholder="Ketik password baru..." class="w-full border p-2.5 rounded-xl bg-stone-50 focus:border-red-500 outline-none"></div>
        
        <div class="mt-6">
            <button type="submit" name="update_profile" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3.5 rounded-xl font-bold shadow-md">SIMPAN PERUBAHAN</button>
        </div>
    </form>
</div>