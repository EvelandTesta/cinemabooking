<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Legal & Bantuan - FlickBook</title>
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
<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <header class="bg-white shadow-sm w-full py-4 border-b border-slate-100 sticky top-0 z-50">
        <div class="container mx-auto px-6 flex justify-between items-center max-w-4xl">
            <a href="index.php" class="text-2xl font-bold tracking-wider text-slate-900">Flick<span class="text-violet">Book</span></a>
            <a href="index.php" class="text-sm font-semibold text-violet hover:underline">← Kembali ke Beranda</a>
        </div>
    </header>

    <main class="container mx-auto px-6 max-w-4xl pt-10 pb-24">
        
        <div class="border-b border-slate-200 mb-8">
            <nav class="flex space-x-6 text-sm font-bold">
                <button onclick="switchLegalTab('bantuan')" id="tab-bantuan-btn" class="border-b-2 border-violet text-slate-900 pb-3 px-1 transition duration-150 cursor-pointer">
                    Pusat Bantuan (FAQ)
                </button>
                <button onclick="switchLegalTab('syarat')" id="tab-syarat-btn" class="border-b-2 border-transparent text-slate-400 hover:text-slate-600 pb-3 px-1 transition duration-150 cursor-pointer">
                    Syarat & Ketentuan
                </button>
                <button onclick="switchLegalTab('privasi')" id="tab-privasi-btn" class="border-b-2 border-transparent text-slate-400 hover:text-slate-600 pb-3 px-1 transition duration-150 cursor-pointer">
                    Kebijakan Privasi
                </button>
            </nav>
        </div>

        <div id="content-legal-bantuan" class="block space-y-4">
            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-xs">
                <h3 class="font-black text-slate-900 mb-2">Bagaimana cara melakukan pemesanan tiket?</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Pemesanan dilakukan dengan memilih film yang diinginkan,lalu jadwal tayang dan posisi tempat duduk. Selamat menonton!.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-xs">
                <h3 class="font-black text-slate-900 mb-2">Apakah Promo FRIENDS & FAMILIY dan NIGHT OWLS bisa digabung?</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Tentu bisa! Dengan memilih 3 atau lebih kursi pada jam tayang diatas jam 21:00 maka anda bisa menikmati promo NIGHT FAMILY & FRIENDS OF THE OWL, potongan hingga 15%!</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-xs">
                <h3 class="font-black text-slate-900 mb-2">Kapan project WPS ini akan selesai???</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Gak tau, Frontend Developer dan Backend Developer sama-sama capek.</p>
            </div>
        </div>

        <div id="content-legal-syarat" class="hidden bg-white p-8 rounded-3xl border border-slate-200/60 shadow-xs space-y-6">
            <h2 class="text-lg font-black text-slate-900 border-b border-slate-100 pb-3">Syarat & Ketentuan Penggunaan Layanan</h2>
            <div class="space-y-4 text-sm text-slate-600 leading-relaxed">
                <p>1. <strong>Ketentuan Akun:</strong> Pengguna wajib memberikan informasi nama, email, dan nomor handphone yang valid saat melakukan registrasi sistem transaksi FlickBook Cinema.</p>
                <p>2. <strong>Mekanisme Transaksi:</strong> Seluruh pemesanan kursi bersifat mengikat sementara selama sesi hold qris berlangsung. FlickBook berhak membatalkan antrean booking jika terindikasi adanya kegagalan mutasi server local.</p>
                <p>3. <strong>Kebijakan Operasional Bioskop:</strong> Pengguna wajib mematuhi batas rating umur film (SU, R13, D17) yang tertera pada detail informasi film saat memasuki studio teater fisik.</p>
            </div>
        </div>

        <div id="content-legal-privasi" class="hidden bg-white p-8 rounded-3xl border border-slate-200/60 shadow-xs space-y-6">
            <h2 class="text-lg font-black text-slate-900 border-b border-slate-100 pb-3">Kebijakan Perlindungan Data Pengguna</h2>
            <div class="space-y-4 text-sm text-slate-600 leading-relaxed">
                <p>FlickBook Cinema System berkomitmen penuh untuk menjaga integritas data pribadi pengguna. Informasi kredensial berupa kata sandi diamankan secara mutlak menggunakan algoritma kriptografi satu arah <code>PASSWORD_BCRYPT</code> di dalam kluster database server kami.</p>
                <p>Kami tidak menjual, menyewakan, atau mendistribusikan riwayat transaksi tiket, nomor handphone, atau log aktivitas penonton kepada pihak ketiga eksternal mana pun untuk keperluan komersial di luar ekosistem manajemen FlickBook.</p>
            </div>
        </div>

    </main>

    <script>
        function switchLegalTab(target) {
            const tabs = ['bantuan', 'syarat', 'privasi'];
            
            tabs.forEach(tab => {
                const btn = document.getElementById(`tab-${tab}-btn`);
                const content = document.getElementById(`content-legal-${tab}`);
                
                if (tab === target) {
                    btn.className = "border-b-2 border-violet text-slate-900 pb-3 px-1 transition duration-150 cursor-pointer";
                    content.classList.remove("hidden");
                    content.classList.add("block");
                } else {
                    btn.className = "border-b-2 border-transparent text-slate-400 hover:text-slate-600 pb-3 px-1 transition duration-150 cursor-pointer";
                    content.classList.remove("block");
                    content.classList.add("hidden");
                }
            });
        }
    </script>
</body>
</html>