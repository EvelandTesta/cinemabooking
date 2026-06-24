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
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        nama: nama,
                        email: email,
                        no_hp: no_hp,
                        role: role
                    })
                });

                const result = await response.json();

                if (result.status === "success") {
                    // Update session storage lokal
                    sessionStorage.setItem("user_name", nama);
                    
                    alert("Profil berhasil diperbarui!");
                    
                    // Reload data visual
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

        // Load data saat halaman selesai dimuat
        document.addEventListener("DOMContentLoaded", () => {
            loadUserProfile();
        });
    </script>
</body>
</html>
