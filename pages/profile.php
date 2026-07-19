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
    
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === 0) {
        $dir = 'uploads/';
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        $filename = $dir . time() . '_' . basename($_FILES['foto_profil']['name']);
        if (move_uploaded_file($_FILES['foto_profil']['tmp_name'], $filename)) {
            $update_query .= ", foto_profil='$filename'";
        }
    }
    
    if(!empty($password)) { $update_query .= ", password='$password'"; }
    $update_query .= " WHERE id_pelanggan=$id";
    
    if(mysqli_query($conn, $update_query)) {
        $_SESSION['nama_lengkap'] = $nama_lengkap;
        $notif = "<div class='bg-emerald-500 text-white p-3 text-center rounded-xl mb-6 font-bold'>Profil berhasil diperbarui.</div>";
    } else {
        $notif = "<div class='bg-red-500 text-white p-3 text-center rounded-xl mb-6 font-bold'>Gagal memperbarui profil.</div>";
    }
}

$user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id_pelanggan=$id"));
?>

<?= $notif ?>
<div class="max-w-2xl mx-auto bg-white p-8 rounded-3xl shadow-sm border border-stone-200 mt-6">
    <h2 class="text-2xl font-black mb-6 border-b pb-4">Profil Saya</h2>
    
    <div class="mb-6 bg-stone-100 p-4 rounded-xl border border-stone-200 text-sm flex items-center space-x-4">
        <img src="<?= !empty($user_data['foto_profil']) ? $user_data['foto_profil'] : 'https://via.placeholder.com/64' ?>" class="w-16 h-16 rounded-full object-cover border-2 border-stone-300">
        <div>
            <span class="font-bold text-stone-500">ID Pelanggan:</span> #<?= $user_data['id_pelanggan'] ?> <br>
            <span class="font-bold text-stone-500">Username:</span> <?= $user_data['username'] ?>
        </div>
    </div>
    
    <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-4">
        <div>
            <label class="block text-sm font-bold mb-1">Upload Foto Profil Baru (Opsional)</label>
            <input type="file" name="foto_profil" accept="image/*" class="w-full border p-1.5 rounded-xl bg-white text-sm">
        </div>
        <div>
            <label class="block text-sm font-bold mb-1">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="<?= $user_data['nama_lengkap'] ?>" required class="w-full border p-2.5 rounded-xl bg-stone-50">
        </div>
        <div>
            <label class="block text-sm font-bold mb-1">Email Aktif</label>
            <input type="email" name="email" value="<?= $user_data['email'] ?>" required class="w-full border p-2.5 rounded-xl bg-stone-50">
        </div>
        <div>
            <label class="block text-sm font-bold mb-1">No. Telepon / WA</label>
            <input type="text" name="no_telepon" value="<?= $user_data['no_telepon'] ?>" required class="w-full border p-2.5 rounded-xl bg-stone-50">
        </div>
        <div>
            <label class="block text-sm font-bold mb-1">Alamat Domisili Lengkap</label>
            <textarea name="alamat" required rows="3" class="w-full border p-2.5 rounded-xl bg-stone-50"><?= $user_data['alamat'] ?></textarea>
        </div>
        <div class="border-t pt-4 mt-2">
            <label class="block text-sm font-bold text-red-500 mb-1">Ubah Password (Kosongkan jika tidak ingin mengubah)</label>
            <div class="relative">
                <input type="password" name="password" id="p_pass" placeholder="Ketik password baru..." class="w-full border p-2.5 rounded-xl bg-stone-50 focus:border-red-500 outline-none">
                <button type="button" onclick="togglePass('p_pass')" class="absolute right-4 top-3 text-stone-500 hover:text-stone-800 text-sm font-bold">👁️</button>
            </div>
        </div>
        
        <div class="mt-6">
            <button type="submit" name="update_profile" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3.5 rounded-xl font-bold shadow-md">SIMPAN PERUBAHAN</button>
        </div>
    </form>
</div>

<script>
function togglePass(id) {
    let input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}
</script>