<?php include 'env.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - FlickBook</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        indigoAccent: '#4f46e5',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans flex items-center justify-center min-h-screen antialiased py-10">

    <div class="bg-white p-10 rounded-3xl shadow-2xl shadow-slate-200/70 border border-slate-100 w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-4xl font-black tracking-tighter text-slate-950">Flick<span class="text-indigoAccent">Book</span></h1>
            <p class="text-sm font-semibold text-slate-500 mt-2">Buat akun barumu sekarang</p>
        </div>

        <div id="error-message-box" class="hidden mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-xs font-bold rounded-2xl flex items-center gap-2 transition duration-200">
            <svg class="w-4 h-4 shrink-0 fill-current" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 011.414 0L10 8.586l1.293-1.293a1 1 0 111.414 1.414L11.414 10l1.293 1.293a1 1 0 01-1.414 1.414L10 11.414l-1.293 1.293a1 1 0 01-1.414-1.414L8.586 10 7.293 8.707a1 1 0 010-1.414z"/></svg>
            <span id="error-text">Terjadi kesalahan pendaftaran.</span>
        </div>

        <form id="register-form" class="space-y-5">
            <div>
                <label for="input-nama" class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-2">Nama / Username</label>
                <input type="text" id="input-nama" name="nama" required 
                       placeholder="Pilih nama pengguna Anda" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:border-indigoAccent focus:ring-2 focus:ring-indigoAccent/20 transition duration-150">
            </div>

            <div>
                <label for="input-email" class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-2">Alamat Email</label>
                <input type="email" id="input-email" name="email" required 
                       placeholder="contoh@email.com" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:border-indigoAccent focus:ring-2 focus:ring-indigoAccent/20 transition duration-150">
            </div>

            <div>
                <label for="input-nohp" class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-2">Nomor HP</label>
                <input type="tel" id="input-nohp" name="no_hp" required 
                       placeholder="Contoh: 08123456789" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:border-indigoAccent focus:ring-2 focus:ring-indigoAccent/20 transition duration-150">
            </div>

            <div>
                <label for="input-password" class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-2">Kata Sandi</label>
                <input type="password" id="input-password" name="password" required 
                       placeholder="Minimal 6 karakter" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:border-indigoAccent focus:ring-2 focus:ring-indigoAccent/20 transition duration-150">
            </div>

            <div>
                <label for="input-confirm-password" class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-2">Konfirmasi Kata Sandi</label>
                <input type="password" id="input-confirm-password" required 
                       placeholder="Ulangi kata sandi Anda" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:border-indigoAccent focus:ring-2 focus:ring-indigoAccent/20 transition duration-150">
            </div>

            <button type="submit" id="btn-register"
                    class="w-full bg-indigoAccent hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-lg shadow-indigoAccent/20 hover:scale-[1.01] active:scale-[0.99] transition duration-200 cursor-pointer text-sm tracking-wider uppercase">
                Daftar Akun
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-xs text-slate-500 font-semibold">
                Sudah punya akun? <a href="login.php" class="text-indigoAccent hover:underline font-black">Masuk di sini</a>
            </p>
        </div>
    </div>

    <script>
        const API_BASE_URL = "<?php echo getenv('API_BASE_URL'); ?>";

        // Fungsi pembantu untuk memicu pesan eror visual tanpa menutup jalannya alur input data
        function triggerError(message) {
            const errorBox = document.getElementById("error-message-box");
            const errorText = document.getElementById("error-text");
            
            errorText.innerText = message;
            errorBox.classList.remove("hidden");
            
            // Mengembalikan fokus view browser ke area atas agar notifikasi langsung terbaca
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        document.getElementById("register-form").addEventListener("submit", async (e) => {
            e.preventDefault();
            const btnRegister = document.getElementById("btn-register");
            const errorBox = document.getElementById("error-message-box");
            
            // Sembunyikan box eror setiap kali form dikirim ulang untuk pembaharuan state
            errorBox.classList.add("hidden");
            
            const nama = document.getElementById("input-nama").value;
            const email = document.getElementById("input-email").value;
            const no_hp = document.getElementById("input-nohp").value;
            const password = document.getElementById("input-password").value;
            const confirmPassword = document.getElementById("input-confirm-password").value;

            // VALIDASI FRONTEND
            if (password !== confirmPassword) {
                triggerError("Konfirmasi kata sandi tidak cocok!");
                return;
            }

            if (password.length < 6) {
                triggerError("Kata sandi terlalu pendek, minimal wajib memuat 6 karakter!");
                return;
            }

            btnRegister.disabled = true;
            btnRegister.innerText = "Mendaftarkan Akun...";

            try {
                const response = await fetch(`${API_BASE_URL}/signup.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ nama, email, no_hp, password })
                });

                const result = await response.json();

                if (result.status === "success") {
                    alert("Pendaftaran Berhasil! Silakan masuk menggunakan akun baru Anda.");
                    window.location.href = "login.php";
                } else {
                    // FIX: Menyuntikkan pesan penolakan spesifik dari database backend ke komponen UI
                    triggerError(result.message || "Gagal mendaftar karena kendala internal server.");
                    btnRegister.disabled = false;
                    btnRegister.innerText = "Daftar Akun";
                }
            } catch (error) {
                console.error("Register API Error:", error);
                triggerError("Gagal terhubung ke server autentikasi. Pastikan koneksi lokal Anda aktif.");
                btnRegister.disabled = false;
                btnRegister.innerText = "Daftar Akun";
            }
        });
    </script>
</body>
</html>