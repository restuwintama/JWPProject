<?php
$notif = '';
if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    $check_uname = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($check_uname) > 0) {
        $notif = "<div class='bg-red-500 text-white p-3 text-center rounded-xl mb-6'>Username sudah digunakan!</div>";
    } else {
        mysqli_query($conn, "INSERT INTO users (username, password, nama_lengkap, email, no_telepon, alamat) 
                             VALUES ('$username', '$password', '$nama_lengkap', '$email', '$no_telepon', '$alamat')");
        $notif = "<div class='bg-emerald-500 text-white p-3 text-center rounded-xl mb-6'>Registrasi berhasil, silakan <a href='?p=login' class='underline font-bold'>Login di sini</a>.</div>";
    }
}
?>

<?= $notif ?>
<div class="max-w-xl mx-auto bg-white p-8 rounded-3xl shadow-sm border border-stone-200 mt-4">
    <h2 class="text-2xl font-black text-center mb-6">REGISTRASI MEMBER</h2>
    <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><label class="block text-sm font-bold mb-1">Username</label><input type="text" name="username" required class="w-full border p-2.5 rounded-xl bg-stone-50"></div>
        <div><label class="block text-sm font-bold mb-1">Password</label><input type="password" name="password" required class="w-full border p-2.5 rounded-xl bg-stone-50"></div>
        <div class="md:col-span-2"><label class="block text-sm font-bold mb-1">Nama Lengkap Sesuai KTP</label><input type="text" name="nama_lengkap" required class="w-full border p-2.5 rounded-xl bg-stone-50"></div>
        <div><label class="block text-sm font-bold mb-1">Email Aktif</label><input type="email" name="email" required class="w-full border p-2.5 rounded-xl bg-stone-50"></div>
        <div><label class="block text-sm font-bold mb-1">No. Telepon / WA</label><input type="text" name="no_telepon" required class="w-full border p-2.5 rounded-xl bg-stone-50"></div>
        <div class="md:col-span-2"><label class="block text-sm font-bold mb-1">Alamat Domisili Lengkap</label><textarea name="alamat" required rows="3" class="w-full border p-2.5 rounded-xl bg-stone-50"></textarea></div>
        
        <div class="md:col-span-2 mt-4">
            <button type="submit" name="register" class="w-full bg-stone-900 text-white py-3.5 rounded-xl font-bold text-lg shadow-lg">DAFTAR SEKARANG</button>
        </div>
    </form>
</div>

<script>
document.querySelector("form").addEventListener("submit", function(e) {
    let email = document.querySelector("input[name='email']").value;
    if (!email.includes("@gmail.com")) {
        alert("Peringatan: Email yang diisi harus valid dan disarankan menggunakan @gmail.com!");
        e.preventDefault();
    }
});
</script>