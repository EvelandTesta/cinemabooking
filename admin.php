<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FlickBook</title>
    <meta name="description" content="Panel admin FlickBook untuk mengelola film, bioskop, studio, jadwal tayang, dan pengguna.">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        violet: '#8b5cf6',
                        dark: '#0f172a'
                    }
                }
            }
        }
    </script>
    <style>
        .modal-enter { animation: modalIn 0.2s ease-out; }
        @keyframes modalIn { from { opacity:0; transform: scale(0.95) translateY(-10px); } to { opacity:1; transform: scale(1) translateY(0); } }
        .tab-active { background: #8b5cf6 !important; color: #fff !important; font-weight: 900 !important; }
        .toast { animation: toastIn 0.3s ease, toastOut 0.3s ease 2.7s forwards; }
        @keyframes toastIn { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        @keyframes toastOut { from { opacity:1; } to { opacity:0; transform:translateY(10px); } }
        ::-webkit-scrollbar { width: 6px; } ::-webkit-scrollbar-track { background: #1e293b; } ::-webkit-scrollbar-thumb { background: #475569; border-radius: 3px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col md:flex-row" style="display: none;">

    <!-- ============ SIDEBAR ============ -->
    <aside class="w-full md:w-64 bg-dark text-white flex flex-col z-40 md:sticky md:top-0 md:h-screen shadow-xl">
        <div class="p-6 border-b border-slate-800 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-black tracking-tighter text-white">Flick<span class="text-violet">Book</span></a>
            <span class="text-xs font-bold bg-violet/20 text-violet px-2 py-1 rounded border border-violet/30 uppercase">Admin</span>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <button onclick="switchTab('dashboard')" id="tab-dashboard" class="tab-active w-full flex items-center space-x-3 px-4 py-3 rounded-xl font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition duration-200 text-left text-sm">
                <span>Dashboard</span>
            </button>
            <button onclick="switchTab('movies')" id="tab-movies" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition duration-200 text-left text-sm">
                <span>Kelola Film</span>
            </button>
            <button onclick="switchTab('cinemas')" id="tab-cinemas" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition duration-200 text-left text-sm">
                <span>Kelola Bioskop</span>
            </button>
            <button onclick="switchTab('studios')" id="tab-studios" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition duration-200 text-left text-sm">
                <span>Kelola Studio</span>
            </button>
            <button onclick="switchTab('showtimes')" id="tab-showtimes" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition duration-200 text-left text-sm">
                <span>Jadwal Tayang</span>
            </button>
            <button onclick="switchTab('users')" id="tab-users" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition duration-200 text-left text-sm">
                <span>Pengguna</span>
            </button>
        </nav>

        <div class="p-4 border-t border-slate-800 space-y-2">
            <a href="index.php" class="w-full flex items-center justify-center space-x-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white py-2.5 rounded-xl text-xs font-bold transition duration-150">
                <span>Ke Halaman Utama</span>
            </a>
            <a href="logout.php" id="btn-logout-admin" class="w-full flex items-center justify-center space-x-2 bg-red-900/40 hover:bg-red-800/60 text-red-400 hover:text-red-200 py-2.5 rounded-xl text-xs font-bold transition duration-150 border border-red-800/30">
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- ============ MAIN CONTENT ============ -->
    <main class="flex-1 p-6 md:p-10 max-w-6xl overflow-x-hidden">

        <!-- Header Bar -->
        <div class="flex justify-between items-center border-b border-slate-200 pb-5 mb-8 gap-4">
            <div>
                <h1 id="welcome-text" class="text-2xl font-black text-slate-900 tracking-tight">Selamat Datang!</h1>
                <p class="text-xs text-slate-500 font-medium">Pusat Kendali Sistem — <span class="font-bold text-violet">db_bioskop</span></p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm text-right flex flex-col justify-center min-w-[160px]">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Sesi Aktif</span>
                <span id="session-admin-name" class="text-sm font-black text-dark block mt-0.5">-</span>
                <span id="session-admin-id" class="text-xs font-bold text-violet block">ID: -</span>
            </div>
        </div>

        <!-- ===== DASHBOARD ===== -->
        <div id="section-dashboard" class="block space-y-8">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition">
                    <span class="text-slate-400 font-bold text-xs uppercase tracking-wider block">Pengguna</span>
                    <span class="text-3xl font-black text-slate-900 mt-2 block" id="stat-users">—</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition">
                    <span class="text-slate-400 font-bold text-xs uppercase tracking-wider block">Film</span>
                    <span class="text-3xl font-black text-violet mt-2 block" id="stat-movies">—</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition">
                    <span class="text-slate-400 font-bold text-xs uppercase tracking-wider block">Bioskop</span>
                    <span class="text-3xl font-black text-slate-900 mt-2 block" id="stat-cinemas">—</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition">
                    <span class="text-slate-400 font-bold text-xs uppercase tracking-wider block">Studio</span>
                    <span class="text-3xl font-black text-amber-500 mt-2 block" id="stat-studios">—</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition">
                    <span class="text-slate-400 font-bold text-xs uppercase tracking-wider block">Jadwal</span>
                    <span class="text-3xl font-black text-emerald-600 mt-2 block" id="stat-showtimes">—</span>
                </div>
            </div>
        </div>

        <!-- ===== MOVIES ===== -->
        <div id="section-movies" class="hidden space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Katalog Film</h2>
                <button onclick="openMovieModal()" class="bg-violet hover:bg-violet-600 text-white font-bold px-4 py-2.5 rounded-xl text-sm transition shadow-md shadow-violet/20">+ Tambah Film</button>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="p-4">ID</th><th class="p-4">Judul</th><th class="p-4">Genre</th><th class="p-4">Durasi</th><th class="p-4">Rating</th><th class="p-4">Status</th><th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-movies-body" class="divide-y divide-slate-100 text-sm"></tbody>
                </table>
            </div>
        </div>

        <!-- ===== CINEMAS ===== -->
        <div id="section-cinemas" class="hidden space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Cabang Bioskop</h2>
                <button onclick="openCinemaModal()" class="bg-violet hover:bg-violet-600 text-white font-bold px-4 py-2.5 rounded-xl text-sm transition shadow-md shadow-violet/20">+ Tambah Bioskop</button>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="p-4">ID</th><th class="p-4">Nama Bioskop</th><th class="p-4">Kota</th><th class="p-4">Lokasi</th><th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-cinemas-body" class="divide-y divide-slate-100 text-sm"></tbody>
                </table>
            </div>
        </div>

        <!-- ===== STUDIOS ===== -->
        <div id="section-studios" class="hidden space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Data Studio</h2>
                <button onclick="openStudioModal()" class="bg-violet hover:bg-violet-600 text-white font-bold px-4 py-2.5 rounded-xl text-sm transition shadow-md shadow-violet/20">+ Tambah Studio</button>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[500px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="p-4">ID</th><th class="p-4">Nama Studio</th><th class="p-4">Bioskop</th><th class="p-4">Kapasitas</th><th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-studios-body" class="divide-y divide-slate-100 text-sm"></tbody>
                </table>
            </div>
        </div>

        <!-- ===== SHOWTIMES ===== -->
        <div id="section-showtimes" class="hidden space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Jadwal Tayang</h2>
                <button onclick="openShowtimeModal()" class="bg-violet hover:bg-violet-600 text-white font-bold px-4 py-2.5 rounded-xl text-sm transition shadow-md shadow-violet/20">+ Buat Jadwal</button>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="p-4">ID</th><th class="p-4">Film</th><th class="p-4">Studio</th><th class="p-4">Tanggal</th><th class="p-4">Jam</th><th class="p-4">Harga</th><th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-showtimes-body" class="divide-y divide-slate-100 text-sm"></tbody>
                </table>
            </div>
        </div>

        <!-- ===== USERS ===== -->
        <div id="section-users" class="hidden space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Daftar Pengguna</h2>
                <button onclick="openUserModal()" class="bg-violet hover:bg-violet-600 text-white font-bold px-4 py-2.5 rounded-xl text-sm transition shadow-md shadow-violet/20">+ Tambah User</button>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="p-4">ID</th><th class="p-4">Nama</th><th class="p-4">Email</th><th class="p-4">No. HP</th><th class="p-4">Role</th><th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-users-body" class="divide-y divide-slate-100 text-sm"></tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- ============ MODAL FORM ============ -->
    <div id="form-modal" class="fixed inset-0 bg-slate-900/70 z-50 flex items-center justify-center p-4 hidden backdrop-blur-sm">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto modal-enter">
            <div class="flex justify-between items-center border-b border-slate-100 p-6 sticky top-0 bg-white rounded-t-2xl z-10">
                <h3 id="modal-title" class="text-lg font-black text-slate-900">Form</h3>
                <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-700 transition font-bold text-xl leading-none">✕</button>
            </div>
            <form id="dynamic-form" onsubmit="submitForm(event)" class="p-6 space-y-4 text-sm">
                <div id="form-fields" class="space-y-4"></div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition">Batal</button>
                    <button type="submit" id="btn-submit-form" class="flex-1 bg-violet hover:bg-violet-600 text-white font-bold py-3 rounded-xl shadow-md shadow-violet/20 transition">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-[100] space-y-2 pointer-events-none"></div>

    <script>
        const API_BASE_URL = "<?php echo getenv('API_BASE_URL'); ?>";

        let currentTargetFitur = '';
        let currentEditId = null;
        let globalCachedMovies = [];
        let globalCachedCinemas = [];
        let globalCachedStudios = [];

        // ─── SESSION CHECK ───
        function checkAdminSession() {
            const userId = sessionStorage.getItem("user_id");
            const userRole = sessionStorage.getItem("user_role");
            const userName = sessionStorage.getItem("user_name") || "Administrator";

            if (!userId) {
                // Not logged in, redirect to login page
                window.location.href = "login.php";
                return;
            }

            if (userRole !== "admin") {
                // Logged in but not an admin, redirect to main page
                alert("Akses ditolak! Halaman ini hanya untuk Administrator.");
                window.location.href = "index.php";
                return;
            }

            // Show body if authorized
            document.body.style.display = "flex";

            document.getElementById('welcome-text').innerText = `Selamat Datang, ${userName}!`;
            document.getElementById('session-admin-name').innerText = userName;
            document.getElementById('session-admin-id').innerText = `User ID: #${userId}`;
        }
        checkAdminSession();

        // ─── TOAST ───
        function showToast(msg, type = 'success') {
            const c = document.getElementById('toast-container');
            const t = document.createElement('div');
            const colors = { success: 'bg-emerald-600', error: 'bg-red-600', info: 'bg-violet' };
            t.className = `toast pointer-events-auto ${colors[type] || colors.info} text-white text-sm font-bold px-5 py-3 rounded-xl shadow-xl max-w-xs`;
            t.innerText = msg;
            c.appendChild(t);
            setTimeout(() => t.remove(), 3200);
        }

        // ─── TAB SWITCHER ───
        const ALL_TABS = ['dashboard', 'movies', 'cinemas', 'studios', 'showtimes', 'users'];
        function switchTab(tabId) {
            ALL_TABS.forEach(t => {
                document.getElementById(`tab-${t}`).classList.toggle('tab-active', t === tabId);
                document.getElementById(`tab-${t}`).classList.toggle('text-slate-400', t !== tabId);
                const sec = document.getElementById(`section-${t}`);
                sec.classList.toggle('hidden', t !== tabId);
                sec.classList.toggle('block', t === tabId);
            });
        }

        // ─── FIELD BUILDER ───
        function buildField(label, id, type = 'text', opts = {}) {
            const baseClass = "w-full p-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-violet/30 focus:border-violet text-sm transition";
            if (type === 'select') {
                const options = (opts.options || []).map(o => `<option value="${o.value}" ${opts.value == o.value ? 'selected' : ''}>${o.label}</option>`).join('');
                return `<div><label class="block text-xs font-bold text-slate-500 mb-1.5">${label}</label><select id="${id}" class="${baseClass}">${options}</select></div>`;
            }
            if (type === 'textarea') {
                return `<div><label class="block text-xs font-bold text-slate-500 mb-1.5">${label}</label><textarea id="${id}" placeholder="${opts.placeholder || ''}" class="${baseClass} h-24 resize-none">${opts.value || ''}</textarea></div>`;
            }
            return `<div><label class="block text-xs font-bold text-slate-500 mb-1.5">${label}</label><input type="${type}" id="${id}" placeholder="${opts.placeholder || ''}" value="${opts.value || ''}" ${opts.required !== false ? 'required' : ''} class="${baseClass}"></div>`;
        }

        // ─── MODAL OPEN/CLOSE ───
        function openModal(title) {
            document.getElementById('modal-title').innerText = title;
            document.getElementById('form-modal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('form-modal').classList.add('hidden');
            currentEditId = null;
            document.getElementById('btn-submit-form').innerText = 'Simpan Data';
        }

        // ─── MOVIE MODAL ───
        function openMovieModal(data = null) {
            currentTargetFitur = 'movies';
            currentEditId = data ? data.movie_id : null;
            const d = data || {};
            document.getElementById('form-fields').innerHTML =
                buildField('Judul Film', 'inp-judul', 'text', { value: d.judul || '', placeholder: 'Masukkan judul film' }) +
                buildField('Genre', 'inp-genre', 'text', { value: d.genre || '', placeholder: 'ACTION / DRAMA / ...' }) +
                buildField('Durasi (Menit)', 'inp-durasi', 'number', { value: d.durasi || '', placeholder: '120' }) +
                buildField('Rating Umur', 'inp-rating-umur', 'select', { value: d.rating_umur, options: [{ value:'SU', label:'SU (Semua Umur)' }, { value:'R13+', label:'R13+ (Remaja)' }, { value:'D17+', label:'D17+ (Dewasa)' }] }) +
                buildField('Status Tayang', 'inp-status', 'select', { value: d.status_tayang, options: [{ value:'now_playing', label:'Now Showing' }, { value:'upcoming', label:'Upcoming' }] }) +
                buildField('URL Poster', 'inp-poster-url', 'text', { value: d.poster_url || '', placeholder: 'https://...', required: false }) +
                buildField('Link Trailer (YouTube)', 'inp-trailer-url', 'text', { value: d.trailer_url || '', placeholder: 'https://youtu.be/...', required: false }) +
                buildField('Sinopsis', 'inp-sinopsis', 'textarea', { value: d.sinopsis || '', placeholder: 'Tulis ringkasan cerita...' });
            openModal(data ? 'Edit Film' : 'Tambah Film Baru');
        }

        // ─── CINEMA MODAL ───
        function openCinemaModal(data = null) {
            currentTargetFitur = 'cinemas';
            currentEditId = data ? data.cinema_id : null;
            const d = data || {};
            document.getElementById('form-fields').innerHTML =
                buildField('Nama Bioskop', 'inp-nama', 'text', { value: d.nama_bioskop || '', placeholder: 'Contoh: CGV Grand Indonesia' }) +
                buildField('Kota', 'inp-kota', 'text', { value: d.kota || '', placeholder: 'Contoh: Jakarta' }) +
                buildField('Lokasi / Alamat', 'inp-lokasi', 'textarea', { value: d.lokasi || '', placeholder: 'Alamat lengkap...' });
            openModal(data ? 'Edit Bioskop' : 'Tambah Bioskop Baru');
        }

        // ─── STUDIO MODAL ───
        function openStudioModal(data = null) {
            currentTargetFitur = 'studios';
            currentEditId = data ? data.studio_id : null;
            const d = data || {};
            const cinemaOpts = globalCachedCinemas.map(c => ({ value: c.cinema_id, label: `${c.nama_bioskop} (${c.kota})` }));
            document.getElementById('form-fields').innerHTML =
                buildField('Nama Studio', 'inp-nama-studio', 'text', { value: d.nama_studio || '', placeholder: 'Studio 1' }) +
                buildField('Pilih Bioskop', 'inp-cinema-id', 'select', { value: d.cinema_id, options: cinemaOpts.length ? cinemaOpts : [{ value:'', label:'-- Tidak Ada Bioskop --' }] }) +
                buildField('Kapasitas Kursi', 'inp-kapasitas', 'number', { value: d.kapasitas || '', placeholder: '20' });
            openModal(data ? 'Edit Studio' : 'Tambah Studio Baru');
        }

        // ─── SHOWTIME MODAL ───
        function openShowtimeModal(data = null) {
            currentTargetFitur = 'showtimes';
            currentEditId = data ? data.showtime_id : null;
            const d = data || {};
            const movieOpts = globalCachedMovies.map(m => ({ value: m.movie_id, label: m.judul }));
            const studioOpts = globalCachedStudios.map(s => ({ value: s.studio_id, label: `#${s.studio_id} - ${s.nama_studio}` }));
            document.getElementById('form-fields').innerHTML =
                buildField('Film', 'inp-movie-id', 'select', { value: d.movie_id, options: movieOpts.length ? movieOpts : [{ value:'', label:'-- Tidak Ada Film --' }] }) +
                buildField('Studio', 'inp-studio-id', 'select', { value: d.studio_id, options: studioOpts.length ? studioOpts : [{ value:'', label:'-- Tidak Ada Studio --' }] }) +
                buildField('Tanggal', 'inp-tanggal', 'date', { value: d.tanggal || '' }) +
                buildField('Jam Tayang (HH:MM)', 'inp-jam', 'text', { value: d.jam ? d.jam.substring(0,5) : '', placeholder: '14:30' }) +
                buildField('Harga Tiket (Rp)', 'inp-harga', 'number', { value: d.harga_tiket || '', placeholder: '50000' });
            openModal(data ? 'Edit Jadwal' : 'Buat Jadwal Tayang');
        }

        // ─── USER MODAL ───
        function openUserModal(data = null) {
            currentTargetFitur = 'users';
            currentEditId = data ? data.user_id : null;
            const d = data || {};
            const isEdit = !!data;
            document.getElementById('form-fields').innerHTML =
                buildField('Nama Lengkap', 'inp-nama-user', 'text', { value: d.nama || '', placeholder: 'Nama pengguna' }) +
                buildField('Email', 'inp-email', 'email', { value: d.email || '', placeholder: 'user@email.com' }) +
                buildField('No. HP', 'inp-nohp', 'text', { value: d.no_hp || '', placeholder: '081234567890', required: false }) +
                (isEdit ? '' : buildField('Password', 'inp-password', 'password', { placeholder: 'Masukkan password' })) +
                buildField('Role', 'inp-role', 'select', { value: d.role || 'user', options: [{ value:'user', label:'User (Pelanggan)' }, { value:'admin', label:'Admin' }] });
            openModal(data ? 'Edit Pengguna' : 'Tambah Pengguna Baru');
        }

        // ─── SUBMIT FORM ───
        async function submitForm(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-submit-form');
            btn.disabled = true; btn.innerText = 'Menyimpan...';
            
            let payload = {};
            const isEdit = !!currentEditId;
            const method = isEdit ? 'PUT' : 'POST';

            try {
                if (currentTargetFitur === 'movies') {
                    payload = {
                        judul: document.getElementById('inp-judul').value,
                        genre: document.getElementById('inp-genre').value,
                        durasi: document.getElementById('inp-durasi').value,
                        rating_umur: document.getElementById('inp-rating-umur').value,
                        status_tayang: document.getElementById('inp-status').value,
                        poster_url: document.getElementById('inp-poster-url').value,
                        trailer_url: document.getElementById('inp-trailer-url').value,
                        sinopsis: document.getElementById('inp-sinopsis').value,
                    };
                    if (isEdit) payload.movie_id = currentEditId.toString();
                } else if (currentTargetFitur === 'cinemas') {
                    payload = {
                        nama_bioskop: document.getElementById('inp-nama').value,
                        kota: document.getElementById('inp-kota').value,
                        lokasi: document.getElementById('inp-lokasi').value,
                    };
                    if (isEdit) payload.cinema_id = currentEditId.toString();
                } else if (currentTargetFitur === 'studios') {
                    payload = {
                        nama_studio: document.getElementById('inp-nama-studio').value,
                        cinema_id: document.getElementById('inp-cinema-id').value,
                        kapasitas: document.getElementById('inp-kapasitas').value,
                    };
                    if (isEdit) payload.studio_id = currentEditId.toString();
                } else if (currentTargetFitur === 'showtimes') {
                    payload = {
                        movie_id: document.getElementById('inp-movie-id').value,
                        studio_id: document.getElementById('inp-studio-id').value,
                        tanggal: document.getElementById('inp-tanggal').value,
                        jam: document.getElementById('inp-jam').value,
                        harga_tiket: document.getElementById('inp-harga').value,
                    };
                    if (isEdit) payload.showtime_id = currentEditId.toString();
                } else if (currentTargetFitur === 'users') {
                    payload = {
                        nama: document.getElementById('inp-nama-user').value,
                        email: document.getElementById('inp-email').value,
                        no_hp: document.getElementById('inp-nohp').value,
                        role: document.getElementById('inp-role').value,
                    };
                    if (!isEdit) payload.password = document.getElementById('inp-password').value;
                    if (isEdit) payload.user_id = currentEditId.toString();
                }

                const res = await fetch(`${API_BASE_URL}/${currentTargetFitur}.php`, {
                    method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const resText = await res.text();
                let ok = res.ok;
                try { const j = JSON.parse(resText); if (j.status === 'error' || j.success === false) ok = false; } catch {}

                if (ok) {
                    showToast(isEdit ? 'Data berhasil diperbarui!' : 'Data berhasil ditambahkan!', 'success');
                    closeModal();
                    refreshDashboardData();
                } else {
                    showToast('Gagal menyimpan: ' + resText.substring(0, 80), 'error');
                }
            } catch (err) {
                showToast('Error koneksi: ' + err, 'error');
            } finally {
                btn.disabled = false; btn.innerText = 'Simpan Data';
            }
        }

        // ─── DELETE ───
        async function deleteData(fitur, keyName, idValue, labelName = '') {
            const konfirmasi = confirm(`Hapus "${labelName || 'item #'+idValue}" dari tabel ${fitur}?\n\nData yang dihapus tidak dapat dikembalikan.`);
            if (!konfirmasi) return;
            let payload = {};
            payload[keyName] = idValue.toString();
            try {
                const res = await fetch(`${API_BASE_URL}/${fitur}.php`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                if (res.ok) {
                    showToast('Data berhasil dihapus.', 'success');
                    refreshDashboardData();
                } else {
                    showToast('Gagal hapus. Mungkin ada relasi FK yang menghalangi.', 'error');
                }
            } catch (err) {
                showToast('Error: ' + err, 'error');
            }
        }

        // ─── UPDATE STATUS FILM ───
        async function updateMovieStatus(movieId, newStatus) {
            if (!confirm(`Ubah status film menjadi "${newStatus}"?`)) return;
            try {
                const res = await fetch(`${API_BASE_URL}/movies.php`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ movie_id: movieId.toString(), status_tayang: newStatus })
                });
                showToast(res.ok ? 'Status film diperbarui!' : 'Gagal update status', res.ok ? 'success' : 'error');
                refreshDashboardData();
            } catch (err) {
                showToast('Error: ' + err, 'error');
            }
        }

        // ─── LOAD DATA ───
        async function refreshDashboardData() {
            try {
                const [resUsers, resMovies, resCinemas, resStudios, resShowtimes] = await Promise.all([
                    fetch(`${API_BASE_URL}/users.php`).then(r => r.json()),
                    fetch(`${API_BASE_URL}/movies.php`).then(r => r.json()),
                    fetch(`${API_BASE_URL}/cinemas.php`).then(r => r.json()),
                    fetch(`${API_BASE_URL}/studios.php`).then(r => r.json()),
                    fetch(`${API_BASE_URL}/showtimes.php`).then(r => r.json()),
                ]);

                const users = resUsers.data || (Array.isArray(resUsers) ? resUsers : []);
                globalCachedMovies = resMovies.data || (Array.isArray(resMovies) ? resMovies : []);
                globalCachedCinemas = resCinemas.data || (Array.isArray(resCinemas) ? resCinemas : []);
                globalCachedStudios = resStudios.data || (Array.isArray(resStudios) ? resStudios : []);
                const showtimes = resShowtimes.data || (Array.isArray(resShowtimes) ? resShowtimes : []);

                document.getElementById('stat-users').innerText = users.length;
                document.getElementById('stat-movies').innerText = globalCachedMovies.length;
                document.getElementById('stat-cinemas').innerText = globalCachedCinemas.length;
                document.getElementById('stat-studios').innerText = globalCachedStudios.length;
                document.getElementById('stat-showtimes').innerText = showtimes.length;

                // Build cinema map for studios display
                const cinemaMap = {};
                globalCachedCinemas.forEach(c => cinemaMap[c.cinema_id] = c.nama_bioskop);
                const movieMap = {};
                globalCachedMovies.forEach(m => movieMap[m.movie_id] = m.judul);
                const studioMap = {};
                globalCachedStudios.forEach(s => studioMap[s.studio_id] = s.nama_studio);

                // USERS TABLE
                document.getElementById('table-users-body').innerHTML = users.length ? users.map(u => `
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 font-mono text-slate-400 text-xs">#${u.user_id}</td>
                        <td class="p-4 font-bold text-slate-900">${u.nama}</td>
                        <td class="p-4 text-slate-600">${u.email || '-'}</td>
                        <td class="p-4 text-slate-500">${u.no_hp || '-'}</td>
                        <td class="p-4"><span class="px-2 py-0.5 text-xs font-black rounded-full border ${u.role === 'admin' ? 'bg-violet/10 text-violet border-violet/20' : 'bg-slate-100 text-slate-600 border-slate-200'} uppercase">${u.role || 'user'}</span></td>
                        <td class="p-4 text-center whitespace-nowrap">
                            <button onclick='openUserModal(${JSON.stringify(u)})' class="text-blue-500 hover:text-blue-700 font-bold text-xs mr-3 cursor-pointer hover:underline">Edit</button>
                            <button onclick="deleteData('users','user_id',${u.user_id},'${u.nama}')" class="text-red-500 hover:text-red-700 font-bold text-xs cursor-pointer hover:underline">Hapus</button>
                        </td>
                    </tr>`).join('') : `<tr><td colspan="6" class="p-8 text-center text-slate-400 text-sm">Tidak ada data pengguna.</td></tr>`;

                // MOVIES TABLE
                document.getElementById('table-movies-body').innerHTML = globalCachedMovies.length ? globalCachedMovies.map(m => {
                    const isPlaying = (m.status_tayang || '').toLowerCase() === 'now_playing';
                    const targetStatus = isPlaying ? 'upcoming' : 'now_playing';
                    const btnLabel = isPlaying ? 'Set Upcoming' : 'Set Showing';
                    const btnCls = isPlaying ? 'text-amber-600 hover:text-amber-800' : 'text-emerald-600 hover:text-emerald-800';
                    return `<tr class="hover:bg-slate-50 transition">
                        <td class="p-4 font-mono text-slate-400 text-xs">#${m.movie_id}</td>
                        <td class="p-4 font-bold text-slate-900 max-w-[200px] truncate" title="${m.judul}">${m.judul}</td>
                        <td class="p-4 text-slate-500 text-xs">${m.genre || '-'}</td>
                        <td class="p-4 text-slate-500">${m.durasi ? m.durasi+' mnt' : '-'}</td>
                        <td class="p-4"><span class="px-2 py-0.5 text-xs font-bold rounded border border-slate-200 bg-slate-50">${m.rating_umur || '-'}</span></td>
                        <td class="p-4"><span class="px-2 py-0.5 text-xs font-black rounded-full ${isPlaying ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'}">${isPlaying ? 'Playing' : 'Upcoming'}</span></td>
                        <td class="p-4 text-center whitespace-nowrap">
                            <button onclick='openMovieModal(${JSON.stringify(m)})' class="text-blue-500 hover:text-blue-700 font-bold text-xs mr-2 cursor-pointer hover:underline">Edit</button>
                            <button onclick="updateMovieStatus(${m.movie_id},'${targetStatus}')" class="${btnCls} font-bold text-xs mr-2 cursor-pointer hover:underline">${btnLabel}</button>
                            <button onclick="deleteData('movies','movie_id',${m.movie_id},'${m.judul.replace(/'/g,"\\\'")}')" class="text-red-500 hover:text-red-700 font-bold text-xs cursor-pointer hover:underline">Hapus</button>
                        </td>
                    </tr>`;
                }).join('') : `<tr><td colspan="7" class="p-8 text-center text-slate-400 text-sm">Tidak ada film.</td></tr>`;

                // CINEMAS TABLE
                document.getElementById('table-cinemas-body').innerHTML = globalCachedCinemas.length ? globalCachedCinemas.map(c => `
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 font-mono text-slate-400 text-xs">#${c.cinema_id}</td>
                        <td class="p-4 font-bold text-slate-900">${c.nama_bioskop}</td>
                        <td class="p-4 font-medium text-slate-600">${c.kota || '-'}</td>
                        <td class="p-4 text-slate-500 text-xs max-w-[200px] truncate" title="${c.lokasi || ''}">${c.lokasi || '-'}</td>
                        <td class="p-4 text-center whitespace-nowrap">
                            <button onclick='openCinemaModal(${JSON.stringify(c)})' class="text-blue-500 hover:text-blue-700 font-bold text-xs mr-3 cursor-pointer hover:underline">Edit</button>
                            <button onclick="deleteData('cinemas','cinema_id',${c.cinema_id},'${c.nama_bioskop}')" class="text-red-500 hover:text-red-700 font-bold text-xs cursor-pointer hover:underline">Hapus</button>
                        </td>
                    </tr>`).join('') : `<tr><td colspan="5" class="p-8 text-center text-slate-400 text-sm">Tidak ada bioskop.</td></tr>`;

                // STUDIOS TABLE
                document.getElementById('table-studios-body').innerHTML = globalCachedStudios.length ? globalCachedStudios.map(st => `
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 font-mono text-slate-400 text-xs">#${st.studio_id}</td>
                        <td class="p-4 font-bold text-slate-900">${st.nama_studio}</td>
                        <td class="p-4 text-slate-600 text-sm">${cinemaMap[st.cinema_id] || 'Cinema #'+st.cinema_id}</td>
                        <td class="p-4 text-slate-500 font-medium">${st.kapasitas || '0'} kursi</td>
                        <td class="p-4 text-center whitespace-nowrap">
                            <button onclick='openStudioModal(${JSON.stringify(st)})' class="text-blue-500 hover:text-blue-700 font-bold text-xs mr-3 cursor-pointer hover:underline">Edit</button>
                            <button onclick="deleteData('studios','studio_id',${st.studio_id},'${st.nama_studio}')" class="text-red-500 hover:text-red-700 font-bold text-xs cursor-pointer hover:underline">Hapus</button>
                        </td>
                    </tr>`).join('') : `<tr><td colspan="5" class="p-8 text-center text-slate-400 text-sm">Tidak ada studio.</td></tr>`;

                // SHOWTIMES TABLE
                document.getElementById('table-showtimes-body').innerHTML = showtimes.length ? showtimes.map(s => `
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 font-mono text-slate-400 text-xs">#${s.showtime_id}</td>
                        <td class="p-4 text-slate-700 font-medium text-sm max-w-[160px] truncate" title="${movieMap[s.movie_id] || ''}">${movieMap[s.movie_id] || 'Film #'+s.movie_id}</td>
                        <td class="p-4 text-slate-600 text-sm">${studioMap[s.studio_id] || 'Studio #'+s.studio_id}</td>
                        <td class="p-4 text-slate-600 text-sm">${s.tanggal || '-'}</td>
                        <td class="p-4 font-bold text-violet text-sm">${(s.jam || '-').substring(0,5)}</td>
                        <td class="p-4 font-black text-slate-900">Rp ${parseInt(s.harga_tiket||0).toLocaleString('id-ID')}</td>
                        <td class="p-4 text-center whitespace-nowrap">
                            <button onclick='openShowtimeModal(${JSON.stringify(s)})' class="text-blue-500 hover:text-blue-700 font-bold text-xs mr-3 cursor-pointer hover:underline">Edit</button>
                            <button onclick="deleteData('showtimes','showtime_id',${s.showtime_id},'Jadwal #${s.showtime_id}')" class="text-red-500 hover:text-red-700 font-bold text-xs cursor-pointer hover:underline">Hapus</button>
                        </td>
                    </tr>`).join('') : `<tr><td colspan="7" class="p-8 text-center text-slate-400 text-sm">Tidak ada jadwal tayang.</td></tr>`;

            } catch (err) {
                console.error("Load data error:", err);
                showToast('Gagal memuat data dari API.', 'error');
            }
        }

        // Close modal on backdrop click
        document.getElementById('form-modal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        document.addEventListener('DOMContentLoaded', refreshDashboardData);
    </script>
</body>
</html>