<?php include 'env.php'; ?>

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

        <div class="mt-6 text-center space-y-2">
            <p class="text-xs text-slate-500 font-semibold">
                Belum punya akun? <a href="register.php" class="text-violet hover:underline font-black">Daftar di sini</a>
            </p>
            <p class="text-xs text-slate-400">
                <button type="button" id="btn-forgot-password" onclick="openForgotModal()" class="text-violet hover:underline font-black cursor-pointer bg-transparent border-none p-0">Lupa Password?</button>
            </p>
        </div>
    </div>

    <!-- ===== MODAL FORGOT PASSWORD ===== -->
    <div id="forgot-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
        <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl p-8 relative" style="animation: modalIn 0.25s ease-out;">
            <style>@keyframes modalIn { from { opacity:0; transform:scale(0.95) translateY(-12px); } to { opacity:1; transform:scale(1) translateY(0); } }</style>

            <button onclick="closeForgotModal()" class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-700 text-xl font-bold transition">✕</button>

            <!-- STEP 1: Masukkan Email -->
            <div id="forgot-step-1">
                <div class="text-center mb-7">
                    <div class="w-14 h-14 rounded-2xl bg-violet/10 flex items-center justify-center mx-auto mb-4" style="box-shadow:0 4px 20px rgba(139,92,246,0.15);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-violet" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    </div>
                    <h2 class="text-xl font-black text-slate-900">Lupa Password?</h2>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Masukkan email yang terdaftar untuk mendapat kode reset.</p>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="forgot-email" class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-2">Alamat Email</label>
                        <input type="email" id="forgot-email" placeholder="nama@email.com" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-900 focus:outline-none focus:border-violet focus:ring-2 focus:ring-violet/20 transition duration-150">
                    </div>
                    <button id="btn-send-code" onclick="sendResetCode()" type="button"
                            class="w-full bg-violet hover:bg-violet-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-violet/20 hover:scale-[1.01] active:scale-[0.99] transition duration-200 text-sm tracking-wider uppercase">
                        Kirim Kode Reset
                    </button>
                </div>
            </div>

            <!-- STEP 2: Masukkan Token + Password Baru (tersembunyi awalnya) -->
            <div id="forgot-step-2" class="hidden">
                <div class="text-center mb-7">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center mx-auto mb-4" style="box-shadow:0 4px 20px rgba(16,185,129,0.15);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-xl font-black text-slate-900">Kode Reset Anda</h2>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Salin kode di bawah, lalu masukkan beserta password baru.</p>
                    <!-- Token display box -->
                    <div id="reset-token-box" class="mt-4 bg-violet/5 border-2 border-violet/20 rounded-2xl py-4 px-6">
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1">Kode Reset (berlaku 10 menit)</p>
                        <p id="reset-token-display" class="text-3xl font-black tracking-[0.35em] text-violet">------</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="inp-reset-token" class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-2">Kode Reset</label>
                        <input type="text" id="inp-reset-token" placeholder="Masukkan 6 digit kode" maxlength="6" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-900 focus:outline-none focus:border-violet focus:ring-2 focus:ring-violet/20 transition duration-150 text-center tracking-widest font-black text-lg">
                    </div>
                    <div>
                        <label for="inp-new-password" class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-2">Password Baru</label>
                        <input type="password" id="inp-new-password" placeholder="Minimal 6 karakter" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-900 focus:outline-none focus:border-violet focus:ring-2 focus:ring-violet/20 transition duration-150">
                    </div>
                    <div>
                        <label for="inp-confirm-password" class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-2">Konfirmasi Password</label>
                        <input type="password" id="inp-confirm-password" placeholder="Ulangi password baru" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-900 focus:outline-none focus:border-violet focus:ring-2 focus:ring-violet/20 transition duration-150">
                    </div>
                    <button id="btn-reset-pw" onclick="doResetPassword()" type="button"
                            class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-emerald-500/20 hover:scale-[1.01] active:scale-[0.99] transition duration-200 text-sm tracking-wider uppercase">
                        Reset Password
                    </button>
                    <button onclick="backToStep1()" type="button" class="w-full text-xs text-slate-400 hover:text-violet font-semibold transition py-1">
                        ← Gunakan Email Lain
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_BASE_URL = "<?php echo getenv('API_BASE_URL'); ?>";

        // ─── LOGIN FORM ───
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
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nama, password })
                });

                const result = await response.json();

                if (result.status === "success" && result.data) {
                    const user = result.data;
                    sessionStorage.setItem("user_id", user.user_id);
                    sessionStorage.setItem("user_name", user.nama || "User");
                    sessionStorage.setItem("user_role", user.role || "user");

                    alert(`Selamat datang kembali, ${user.nama || "User"}!`);

                    if (user.role === "admin") {
                        window.location.href = "admin.php";
                    } else {
                        window.location.href = "<?php echo getenv('APP_URL'); ?>";
                    }
                } else {
                    alert("Gagal masuk: " + (result.message || "Nama atau Kata Sandi salah!"));
                    btnLogin.disabled = false;
                    btnLogin.innerText = "LOGIN";
                }
            } catch (error) {
                console.error("Autentikasi API Error:", error);
                alert("Terjadi kesalahan fatal pada server autentikasi.");
                btnLogin.disabled = false;
                btnLogin.innerText = "LOGIN";
            }
        });

        // ─── FORGOT PASSWORD ───
        let forgotUserId = null;

        function openForgotModal() {
            document.getElementById('forgot-modal').classList.remove('hidden');
            backToStep1();
        }

        function closeForgotModal() {
            document.getElementById('forgot-modal').classList.add('hidden');
            forgotUserId = null;
        }

        function backToStep1() {
            document.getElementById('forgot-step-1').classList.remove('hidden');
            document.getElementById('forgot-step-2').classList.add('hidden');
            document.getElementById('forgot-email').value = '';
            document.getElementById('inp-reset-token').value = '';
            document.getElementById('inp-new-password').value = '';
            document.getElementById('inp-confirm-password').value = '';
            document.getElementById('reset-token-display').innerText = '------';
            forgotUserId = null;
        }

        async function sendResetCode() {
            const email = document.getElementById('forgot-email').value.trim();
            if (!email) { alert('Masukkan email terlebih dahulu.'); return; }

            const btn = document.getElementById('btn-send-code');
            btn.disabled = true;
            btn.innerText = 'Mengirim...';

            try {
                const res = await fetch(`${API_BASE_URL}/forgot_password.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email })
                });
                const result = await res.json();

                if (result.status === 'success' && result.data) {
                    forgotUserId = result.data.user_id;
                    // Tampilkan token langsung di UI (simulasi, tanpa email server)
                    document.getElementById('reset-token-display').innerText = result.data.token;
                    document.getElementById('inp-reset-token').value = result.data.token;
                    document.getElementById('forgot-step-1').classList.add('hidden');
                    document.getElementById('forgot-step-2').classList.remove('hidden');
                } else {
                    alert('Gagal: ' + (result.message || 'Email tidak ditemukan.'));
                }
            } catch (err) {
                alert('Terjadi kesalahan koneksi server.');
                console.error(err);
            } finally {
                btn.disabled = false;
                btn.innerText = 'Kirim Kode Reset';
            }
        }

        async function doResetPassword() {
            const token = document.getElementById('inp-reset-token').value.trim();
            const newPassword = document.getElementById('inp-new-password').value;
            const confirmPassword = document.getElementById('inp-confirm-password').value;

            if (!token || token.length !== 6) { alert('Masukkan kode reset 6 digit dengan benar.'); return; }
            if (newPassword.length < 6) { alert('Password baru minimal 6 karakter.'); return; }
            if (newPassword !== confirmPassword) { alert('Konfirmasi password tidak cocok!'); return; }
            if (!forgotUserId) { alert('Sesi tidak valid, silakan ulangi dari awal.'); backToStep1(); return; }

            const btn = document.getElementById('btn-reset-pw');
            btn.disabled = true;
            btn.innerText = 'Memproses...';

            try {
                const res = await fetch(`${API_BASE_URL}/forgot_password.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: forgotUserId, token, new_password: newPassword })
                });
                const result = await res.json();

                if (result.status === 'success') {
                    alert('Password berhasil direset! Silakan login dengan password baru Anda.');
                    closeForgotModal();
                } else {
                    alert('Gagal reset password: ' + (result.message || 'Kode tidak valid atau sudah kedaluwarsa.'));
                }
            } catch (err) {
                alert('Terjadi kesalahan koneksi server.');
                console.error(err);
            } finally {
                btn.disabled = false;
                btn.innerText = 'Reset Password';
            }
        }

        // Tutup modal jika klik di luar
        document.getElementById('forgot-modal').addEventListener('click', function(e) {
            if (e.target === this) closeForgotModal();
        });
    </script>
</body>
</html>