<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket Resmi - FlickBook</title>
    <link rel="stylesheet" href="./dist/css/style.css">
</head>
<body class="bg-slate-900 text-slate-100 font-sans">

    <main class="container mx-auto px-4 max-w-md pt-16 pb-24">
        
        <div class="bg-white text-slate-900 rounded-3xl shadow-2xl overflow-hidden border border-slate-200">
            
            <div class="bg-violet p-6 text-center text-white relative">
                <h1 class="text-xl font-black tracking-widest uppercase">Flick<span class="text-slate-900">Book</span> Ticket</h1>
                <p class="text-xs opacity-80 mt-1">Tunjukkan tiket ini ke petugas bioskop</p>
                
                <div class="absolute -bottom-3 -left-3 w-6 h-6 bg-slate-900 rounded-full"></div>
                <div class="absolute -bottom-3 -right-3 w-6 h-6 bg-slate-900 rounded-full"></div>
            </div>

            <div class="p-6 pt-8 space-y-5 border-b border-dashed border-slate-200 relative">
                <div class="text-center">
                    <span id="tkt-status" class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full border border-emerald-200 uppercase tracking-wider">
                        Confirmed / Paid
                    </span>
                    <h2 id="tkt-movie-title" class="text-2xl font-black text-slate-900 mt-3 leading-tight">Memuat Judul Film...</h2>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm border-t border-slate-100 pt-4">
                    <div>
                        <span class="text-xs text-slate-400 block uppercase font-bold">Bioskop</span>
                        <span id="tkt-cinema" class="font-bold text-slate-800">-</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block uppercase font-bold">Studio</span>
                        <span id="tkt-studio" class="font-bold text-slate-800">-</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-xs text-slate-400 block uppercase font-bold">Tanggal</span>
                        <span id="tkt-date" class="font-bold text-slate-800">-</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block uppercase font-bold">Jam Tayang</span>
                        <span id="tkt-time" class="font-bold text-slate-800">-</span>
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex justify-between items-center">
                    <div>
                        <span class="text-xs text-slate-400 block uppercase font-bold">Nomor Kursi</span>
                        <span id="tkt-seats" class="text-xl font-black text-violet">-</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-400 block uppercase font-bold">Total Harga</span>
                        <span id="tkt-total" class="text-sm font-bold text-slate-800">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-slate-50 flex flex-col items-center justify-center">
                <div class="bg-white p-3 rounded-2xl shadow-xs border border-slate-200">
                    <img id="tkt-qrcode" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=FlickBook-Ticket" alt="QR Code" class="w-32 h-32">
                </div>
                <p id="tkt-code-label" class="text-xs font-mono font-bold tracking-widest text-slate-400 mt-3 uppercase">FB-PENDING</p>
                
                <a href="index.php" class="w-full mt-6 bg-slate-900 hover:bg-slate-800 text-white text-center font-bold py-3 px-4 rounded-xl text-sm transition duration-150">
                    Kembali ke Beranda
                </a>
            </div>

        </div>
    </main>

    <script>
        const API_BASE_URL = "http://localhost/cinema_booking/api"; 

        const urlParams = new URLSearchParams(window.location.search);
        const bookingId = urlParams.get('booking_id');

        if (!bookingId) {
            alert("Akses tiket tidak sah!");
            window.location.href = "index.php";
        }

        async function loadFinalTicket() {
            try {
                // 1. Tembak API Bookings untuk menarik manifes e-ticket lengkap
                const response = await fetch(`${API_BASE_URL}/bookings.php?booking_id=${bookingId}`);
                const result = await response.json();

                if (result.status === "success" && result.data) {
                    const data = result.data;

                    // 2. Suntikkan informasi dasar komponen E-Tiket ke HTML DOM
                    document.getElementById("tkt-movie-title").innerText = data.movie_title || "Judul Film";
                    document.getElementById("tkt-cinema").innerText = data.nama_bioskop || "-";
                    document.getElementById("tkt-studio").innerText = data.nama_studio || "-";
                    document.getElementById("tkt-date").innerText = data.tanggal || data.Tanggal || "-";
                    
                    const jam = data.jam || data.Jam || "";
                    document.getElementById("tkt-time").innerText = jam.substring(0, 5) + " WIB";

                    // 3. Ekstrak dan petakan array manifests tiket kursi (Contoh: "A" + "1" = "A1")
                    let nomorKursiTercetak = "Terpilih";
                    if (data.tickets && data.tickets.length > 0) {
                        const daftarKursi = data.tickets.map(tkt => `${tkt.baris}${tkt.nomor_kursi}`);
                        nomorKursiTercetak = daftarKursi.join(", ");
                    }
                    document.getElementById("tkt-seats").innerText = nomorKursiTercetak;

                    // ===========================================================
                    // LOGIKA NYATA: HITUNG ULANG DISKON STACKING (ticket.php)
                    // ===========================================================
                    const hargaSatuan = parseInt(data.harga_tiket || data.Harga_tiket || 0);
                    const jumlahTiket = data.tickets ? data.tickets.length : 1;
                    const subtotalTiket = hargaSatuan * jumlahTiket;

                    let tktDiskonSquad = 0;
                    let tktDiskonNightOwl = 0;
                    let tktListPromo = [];

                    // Evaluasi Promo 1: Squad (10%) jika beli >= 3 tiket
                    if (jumlahTiket >= 3) {
                        tktDiskonSquad = subtotalTiket * 0.10;
                        tktListPromo.push("FRIENDS & FAMILY (10%)");
                    }

                    // Evaluasi Promo 2: Night Owl / Friends of Owl (5%) jika tayang >= 21:00
                    const tktStringJam = data.jam || data.Jam || "00:00:00";
                    const tktAngkaJam = parseInt(tktStringJam.substring(0, 2));

                    if (tktAngkaJam >= 21) {
                        tktDiskonNightOwl = subtotalTiket * 0.05;
                        tktListPromo.push("FRIENDS OF OWL (5%)");
                    }

                    // Akumulasi nominal potongan secara tumpuk (stacking)
                    const tktTotalDiskon = tktDiskonSquad + tktDiskonNightOwl;
                    const tktHargaBersih = subtotalTiket - tktTotalDiskon;

                    // Menggabungkan nama promo gabungan secara dinamis jika dua-duanya aktif
                    let tktNamaPromoFinal = "Tidak Ada Promo";
                    if (tktListPromo.length === 2) {
                        tktNamaPromoFinal = "NIGHT FAMILY & FRIENDS OF THE OWL (15%)";
                    } else if (tktListPromo.length === 1) {
                        tktNamaPromoFinal = tktListPromo[0];
                    }

                    // Suntikkan rincian invoice desimal di atas label total harga
                    const tktTotalElement = document.getElementById("tkt-total");
                    let tktPromoBox = document.getElementById("tkt-promo-box");
                    if (!tktPromoBox) {
                        tktPromoBox = document.createElement("div");
                        tktPromoBox.id = "tkt-promo-box";
                        tktPromoBox.className = "text-[11px] text-slate-400 mt-2 pt-2 border-t border-dashed border-slate-300 space-y-1";
                        tktTotalElement.parentNode.insertBefore(tktPromoBox, tktTotalElement);
                    }

                    tktPromoBox.innerHTML = `
                        <div class="flex justify-between"><span>Harga Normal:</span><span>Rp ${subtotalTiket.toLocaleString('id-ID')}</span></div>
                        <div class="flex justify-between text-violet font-semibold"><span>Promo:</span><span>${tktNamaPromoFinal}</span></div>
                        <div class="flex justify-between text-red-500"><span>Potongan:</span><span>- Rp ${tktTotalDiskon.toLocaleString('id-ID')}</span></div>
                    `;

                    // Cetak harga final yang lunas bersih dari promo menumpuk
                    tktTotalElement.innerText = `Rp ${tktHargaBersih.toLocaleString('id-ID')}`;
                    // ===========================================================

                    // 4. Update label kode transaksi dan QR Code dinamis
                    const codeLabel = `FB-00${data.booking_id}X${data.showtime_id}`;
                    document.getElementById("tkt-code-label").innerText = codeLabel;
                    document.getElementById("tkt-qrcode").src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${codeLabel}`;

                } else {
                    alert("Data tiket gagal diverifikasi.");
                    window.location.href = "index.php";
                }
            } catch (error) {
                console.error("Error loading ticket page:", error);
            }
        }

        // Panggil fungsi ketika DOM halaman selesai dimuat murni
        document.addEventListener("DOMContentLoaded", loadFinalTicket);
    </script>
</body>
</html>