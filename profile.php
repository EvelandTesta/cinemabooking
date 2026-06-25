<?php include 'env.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - FlickBook</title>
    <link rel="stylesheet" href="./dist/css/style.css">
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
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-slate-200">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <a href="<?php echo getenv('APP_URL'); ?>" class="text-2xl font-bold text-slate-900">
                Flick<span class="text-violet">Book</span>
            </a>

            <div class="flex items-center gap-3">
                <a id="admin-dashboard-btn" href="admin.php"
                   class="hidden bg-slate-800 hover:bg-slate-700 text-white px-5 py-2 rounded-xl font-bold text-sm transition">
                    Dashboard Admin
                </a>
                <a href="<?php echo getenv('APP_URL'); ?>"
                   class="bg-violet hover:bg-violet-600 text-white px-5 py-2 rounded-xl font-bold text-sm transition">
                    Kembali ke Home
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center py-12 px-4">
        <div class="bg-white p-8 md:p-10 rounded-3xl shadow-2xl shadow-slate-200/80 border border-slate-100 w-full max-w-md">
            
            <div class="text-center mb-8">
                <!-- Avatar circle -->
                <div id="profile-avatar-large" class="w-20 h-20 rounded-full mx-auto flex items-center justify-center text-white font-black text-3xl mb-4"
                     style="background: linear-gradient(135deg, #7C3AED, #5b21b6); box-shadow: 0 10px 25px rgba(124, 58, 237, 0.35);">
                    U
                </div>
                <h2 id="profile-title-name" class="text-2xl font-black text-slate-900">Nama Pengguna</h2>
                <p id="profile-title-role" class="text-xs font-bold text-violet uppercase tracking-widest mt-1">Role: User</p>
            </div>

            <form id="profile-edit-form" class="space-y-5">
                <div>
                    <label for="profile-nama" class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-2">Username</label>
                    <input type="text" id="profile-nama" required 
                           placeholder="Masukkan Username Baru" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:border-violet focus:ring-2 focus:ring-violet/20 transition duration-150">
                </div>

                <div>
                    <label for="profile-email" class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-2">Alamat Email</label>
                    <input type="email" id="profile-email" required 
                           placeholder="nama@email.com" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:border-violet focus:ring-2 focus:ring-violet/20 transition duration-150">
                </div>

                <div>
                    <label for="profile-hp" class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-2">No. Handphone</label>
                    <input type="text" id="profile-hp" required 
                           placeholder="08123456789" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:border-violet focus:ring-2 focus:ring-violet/20 transition duration-150">
                </div>

                <div class="pt-4">
                    <button type="submit" id="btn-save-profile"
                            class="w-full bg-violet hover:bg-violet-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-violet/20 hover:scale-[1.01] active:scale-[0.99] transition duration-200 cursor-pointer text-sm tracking-wider uppercase">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-slate-200"></div>
                <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Keamanan Akun</span>
                <div class="flex-1 h-px bg-slate-200"></div>
            </div>

            <!-- Ganti Password Form -->
            <form id="change-password-form" class="space-y-4">
                <div>
                    <label for="cp-current" class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-2">Password Saat Ini</label>
                    <input type="password" id="cp-current" required
                           placeholder="Masukkan password saat ini"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:border-violet focus:ring-2 focus:ring-violet/20 transition duration-150">
                </div>
                <div>
                    <label for="cp-new" class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-2">Password Baru</label>
                    <input type="password" id="cp-new" required
                           placeholder="Minimal 6 karakter"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:border-violet focus:ring-2 focus:ring-violet/20 transition duration-150">
                </div>
                <div>
                    <label for="cp-confirm" class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-2">Konfirmasi Password Baru</label>
                    <input type="password" id="cp-confirm" required
                           placeholder="Ulangi password baru"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:border-violet focus:ring-2 focus:ring-violet/20 transition duration-150">
                </div>
                <div class="pt-1">
                    <button type="submit" id="btn-change-password"
                            class="w-full bg-slate-800 hover:bg-slate-900 text-white font-black py-4 rounded-2xl shadow-lg shadow-slate-800/15 hover:scale-[1.01] active:scale-[0.99] transition duration-200 cursor-pointer text-sm tracking-wider uppercase">
                        Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 text-center text-xs text-slate-400 border-t border-slate-200 bg-white">
        &copy; 2026 FlickBook Cinema System. All rights reserved.
    </footer>

    <script>
        const API_BASE_URL = "<?php echo getenv('API_BASE_URL'); ?>";

        // Validasi Sesi Pengguna
        function checkUserSession() {
            const userId = sessionStorage.getItem("user_id");
            if (!userId) {
                window.location.href = "login.php";
                return null;
            }
            // Check if admin to show dashboard button
            const userRole = sessionStorage.getItem("user_role");
            const adminBtn = document.getElementById("admin-dashboard-btn");
            if (userRole === "admin" && adminBtn) {
                adminBtn.classList.remove("hidden");
            }
            return userId;
        }

        // Ambil Data Profil Pengguna dari Database
        async function loadUserProfile() {
            const userId = checkUserSession();
            if (!userId) return;

            try {
                const response = await fetch(`${API_BASE_URL}/users.php?user_id=${userId}`);
                const result = await response.json();

                if (result.status === "success" && result.data) {
                    const user = result.data;
                    
                    // Isi form input
                    document.getElementById("profile-nama").value = user.nama || "";
                    document.getElementById("profile-email").value = user.email || "";
                    document.getElementById("profile-hp").value = user.no_hp || "";
                    
                    // Perbarui header card profile
                    const initial = (user.nama || "User").charAt(0).toUpperCase();
                    document.getElementById("profile-avatar-large").innerText = initial;
                    document.getElementById("profile-title-name").innerText = user.nama || "Nama Pengguna";
                    document.getElementById("profile-title-role").innerText = `Role: ${user.role || 'user'}`;
                } else {
                    alert("Gagal memuat profil: " + result.message);
                }
            } catch (error) {
                console.error("Error loading profile:", error);
                alert("Terjadi kesalahan koneksi server saat mengambil data profil.");
            }
        }

        // Submit form perubahan data profil
        document.getElementById("profile-edit-form").addEventListener("submit", async (e) => {
            e.preventDefault();
            const userId = checkUserSession();
            if (!userId) return;

            const btnSave = document.getElementById("btn-save-profile");
            const nama = document.getElementById("profile-nama").value;
            const email = document.getElementById("profile-email").value;
            const no_hp = document.getElementById("profile-hp").value;
            const roleText = document.getElementById("profile-title-role").innerText;
            const role = roleText.replace("Role: ", "").trim();

            btnSave.disabled = true;
            btnSave.innerText = "Menyimpan Perubahan...";

            try {
                const response = await fetch(`${API_BASE_URL}/users.php`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: userId, nama, email, no_hp, role })
                });

                const result = await response.json();

                if (result.status === "success") {
                    sessionStorage.setItem("user_name", nama);
                    alert("Profil berhasil diperbarui!");
                    loadUserProfile();
                } else {
                    alert("Gagal memperbarui profil: " + result.message);
                }
            } catch (error) {
                console.error("Error saving profile:", error);
                alert("Terjadi kesalahan koneksi server saat memperbarui profil.");
            } finally {
                btnSave.disabled = false;
                btnSave.innerText = "Simpan Perubahan";
            }
        });

        // Submit form ganti password
        document.getElementById("change-password-form").addEventListener("submit", async (e) => {
            e.preventDefault();
            const userId = checkUserSession();
            if (!userId) return;

            const currentPassword = document.getElementById("cp-current").value;
            const newPassword = document.getElementById("cp-new").value;
            const confirmPassword = document.getElementById("cp-confirm").value;

            if (newPassword.length < 6) {
                alert("Password baru minimal 6 karakter.");
                return;
            }
            if (newPassword !== confirmPassword) {
                alert("Konfirmasi password baru tidak cocok!");
                return;
            }

            const btn = document.getElementById("btn-change-password");
            btn.disabled = true;
            btn.innerText = "Menyimpan...";

            try {
                const response = await fetch(`${API_BASE_URL}/users.php`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        user_id: userId,
                        current_password: currentPassword,
                        new_password: newPassword
                    })
                });

                const result = await response.json();

                if (result.status === "success") {
                    alert("Password berhasil diperbarui! Silakan login kembali dengan password baru.");
                    sessionStorage.clear();
                    window.location.href = "logout.php";
                } else {
                    alert("Gagal: " + (result.message || "Terjadi kesalahan."));
                }
            } catch (error) {
                console.error("Error changing password:", error);
                alert("Terjadi kesalahan koneksi server.");
            } finally {
                btn.disabled = false;
                btn.innerText = "Simpan Password Baru";
            }
        });

        // Load data saat halaman selesai dimuat
        document.addEventListener("DOMContentLoaded", () => {
            loadUserProfile();
        });
    </script>
</body>
</html>
