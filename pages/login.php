<?php
$notif = '';
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    if ($user = mysqli_fetch_assoc($query)) {
        $_SESSION['user_id'] = $user['id_pelanggan'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        
        // Redirect berdasarkan role
        if ($user['role'] === 'admin') {
            echo "<script>window.location.href='?p=admin';</script>";
        } else {
            echo "<script>window.location.href='?p=home';</script>";
        }
        exit;
    } else {
        $notif = "<div class='bg-red-500 text-white p-3 text-center rounded-xl mb-6 font-bold'>Username atau password salah!</div>";
    }
}
?>

<?= $notif ?>
<div class="max-w-md mx-auto bg-white p-8 rounded-3xl shadow-sm border border-stone-200 mt-10">
    <h2 class="text-2xl font-black text-center mb-6">LOGIN BASECAMP</h2>
    <form method="POST" class="space-y-4" onsubmit="return validateLogin()">
        <div>
            <label class="block text-sm font-bold mb-1">Username</label>
            <input type="text" name="username" id="l_user" class="w-full border p-2.5 rounded-xl bg-stone-50">
        </div>
        <div class="relative">
            <label class="block text-sm font-bold mb-1">Password</label>
            <input type="password" name="password" id="l_pass" class="w-full border p-2.5 rounded-xl bg-stone-50">
            <button type="button" onclick="togglePass('l_pass')" class="absolute right-3 top-9 text-stone-500 hover:text-stone-800 text-sm font-bold">👁️</button>
        </div>
        <p id="error_msg" class="text-red-500 text-xs font-bold hidden">Tolong isi semua kolom form di atas!</p>
        <button type="submit" name="login" class="w-full bg-emerald-600 text-white py-3 rounded-xl font-bold">MASUK</button>
    </form>
    <p class="mt-6 text-center text-sm font-medium">Belum punya akun? <a href="?p=register" class="text-emerald-600 hover:underline">Daftar Member</a></p>
</div>

<script>
function togglePass(id) {
    let input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}
function validateLogin() {
    let u = document.getElementById('l_user').value;
    let p = document.getElementById('l_pass').value;
    if (u === "" || p === "") {
        document.getElementById('error_msg').classList.remove('hidden');
        return false;
    }
    return true;
}
</script>