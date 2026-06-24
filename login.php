<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FlickBook</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        violet: '#8b5cf6',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-100 text-slate-800 font-sans flex items-center justify-center min-h-screen antialiased">

    <div class="bg-white p-10 rounded-3xl shadow-2xl shadow-slate-200/70 border border-slate-100 w-full max-w-sm">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-black tracking-tighter text-slate-950">Flick<span class="text-violet">Book</span></h1>
            <p class="text-sm font-semibold text-slate-500 mt-2">Selamat Datang!</p>
        </div>

        <form id="login-form" class="space-y-6">
            <div>
                <label for="input-nama" class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-2">USERNAME</label>
                <input type="text" id="input-nama" name="nama" required 
                       placeholder="Masukkan Username" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-900 focus:outline-none focus:border-violet focus:ring-2 focus:ring-violet/20 transition duration-150">
            </div>

            <div>
                <label for="input-password" class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-2">Kata Sandi</label>
                <input type="password" id="input-password" name="password" required 
                       placeholder="Masukkan Kata Sandi Anda" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-900 focus:outline-none focus:border-violet focus:ring-2 focus:ring-violet/20 transition duration-150">
            </div>

            <button type="submit" id="btn-login"
                    class="w-full bg-violet hover:bg-violet-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-violet/20 hover:scale-[1.01] active:scale-[0.99] transition duration-200 cursor-pointer text-sm tracking-wider uppercase">
                LOGIN
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-xs text-slate-500 font-semibold">
                Belum punya akun? <a href="register.php" class="text-violet hover:underline font-black">Daftar di sini</a>
            </p>
        </div>
    </div>

    <script>
        const API_BASE_URL = getenv('API_BASE_URL');

        document.getElementById("login-form").addEventListener("submit", async (e) => {
            e.preventDefault();
            const btnLogin = document.getElementById("btn-login");
            
            const nama = document.getElementById("input-nama").value;
            const password = document.getElementById("input-password").value;

            btnLogin.disabled = true;
            btnLogin.innerText = "Memverifikasi Autentikasi...";

            try {
                const response = await fetch(`${API_BASE_URL}/login.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ nama, password })
                });

                const result = await response.json();

                if (result.status === "success" && result.data) {
                    const user = result.data;
                    
                    // Menyimpan data ke memori browser (Session Storage)
                    sessionStorage.setItem("user_id", user.user_id);
                    sessionStorage.setItem("user_name", user.nama || "User");
                    
                    // Menyimpan data role (admin/user) ke browser
                    sessionStorage.setItem("user_role", user.role || "user");

                    alert(`Selamat datang kembali, ${user.nama || "User"}!`);
                    
                    // ==========================================================
                    // PENGALIHAN OTOMATIS BERDASARKAN ROLE
                    // ==========================================================
                    if (user.role === "admin") {
                        // Jika admin, langsung arahkan ke Dashboard Admin
                        window.location.href = "admin.php";
                    } else {
                        // Jika user biasa, diarahkan ke halaman utama
                        window.location.href = "index.php";
                    }
                    
                } else {
                    alert("Gagal masuk: " + (result.message || "Nama atau Kata Sandi salah!"));
                    btnLogin.disabled = false;
                    btnLogin.innerText = "Masuk ke Sistem";
                }
            } catch (error) {
                console.error("Autentikasi API Error:", error);
                alert("Terjadi kesalahan fatal pada server autentikasi.");
                btnLogin.disabled = false;
                btnLogin.innerText = "Masuk ke Sistem";
            }
        });
    </script>
</body>
</html>