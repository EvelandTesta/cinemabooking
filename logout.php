<?php
// Mengaktifkan penanganan session bawaan PHP jika proyek lu memanfaatkannya di backend
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Bersihkan seluruh array superglobal session di sisi server PHP
$_SESSION = array();

// 2. Hancurkan cookie session jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Hancurkan session server secara total
session_destroy();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keluar Sistem...</title>
    <script>
        // 4. Bersihkan data penyimpanan state di sisi client browser (sessionStorage)
        sessionStorage.clear();
        localStorage.removeItem("user_id"); // Mitigasi jika lu sempat pakai localStorage
        localStorage.removeItem("user_name");

        // 5. Alirkan navigasi kembali secara mutlak ke gerbang login
        window.location.href = "login.php";
    </script>
</head>
<body style="background-color: #f8fafc; font-family: sans-serif; display: flex; items-center: center; justify-content: center; min-height: 100vh; margin: 0;">
    <div style="text-align: center; color: #64748b;">
        <p style="font-weight: bold; font-size: 14px;">Memproses keluar dari sistem FlickBook...</p>
    </div>
</body>
</html>