<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - FlickBook</title>
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
        <div class="container mx-auto px-6 flex justify-between items-center max-w-5xl">
            <a href="index.php" class="text-2xl font-bold tracking-wider text-slate-900">Flick<span class="text-violet">Book</span></a>
            <span class="text-xs font-bold text-amber-600 bg-amber-50 px-3 py-1.5 rounded-xl border border-amber-200 uppercase tracking-wider">Selesaikan Pembayaran</span>
        </div>
    </header>

    <main class="container mx-auto px-4 max-w-xl pt-10 pb-24">
        
        <div class="bg-white rounded-3xl shadow-xl border border-slate-200/60 overflow-hidden transition-all">
            
            <div class="h-2 bg-gradient-to-r from-violet to-fuchsia-500"></div>

            <div class="p-8">
                <div class="text-center pb-6 border-b border-slate-100">
                    <h2 class="text-[10px] uppercase font-black tracking-widest text-slate-400">Invoice Tagihan</h2>
                    <h1 id="invoice-id" class="text-2xl font-mono font-black text-slate-800 mt-1">#BOOK-0000</h1>
                </div>

                <div class="mt-6 space-y-5">
                    <div>
                        <label class="text-[10px] text-slate-400 block uppercase font-black tracking-wider">Film</label>
                        <span id="pay-movie-title" class="text-lg font-black text-slate-900 leading-tight">Memuat info film...</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] text-slate-400 block uppercase font-black tracking-wider">Bioskop</label>
                            <span id="pay-cinema" class="text-sm font-bold text-slate-700">-</span>
                        </div>
                        <div>
                            <label class="text-[10px] text-slate-400 block uppercase font-black tracking-wider">Studio</label>
                            <span id="pay-studio" class="text-sm font-bold text-slate-700">-</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-b border-slate-100 pb-5">
                        <div>
                            <label class="text-[10px] text-slate-400 block uppercase font-black tracking-wider">Tanggal & Jam</label>
                            <span id="pay-datetime" class="text-sm font-bold text-slate-700">-</span>
                        </div>
                        <div>
                            <label class="text-[10px] text-slate-400 block uppercase font-black tracking-wider">Kursi</label>
                            <span id="pay-seats" class="text-sm font-extrabold text-violet">-</span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <div class="flex justify-between text-xs text-slate-400 font-semibold mb-1.5">
                            <span>Harga per Tiket:</span>
                            <span id="pay-price-single" class="text-slate-700 font-bold">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-xs text-slate-400 font-semibold mb-4">
                            <span>Jumlah Tiket:</span>
                            <span id="pay-ticket-count" class="text-slate-700 font-bold">0x</span>
                        </div>
                        
                        <div class="flex justify-between items-center bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Total Bayar:</span>
                            <span id="pay-total" class="text-2xl font-black text-emerald-600">Rp 0</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 space-y-6">
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200/80 space-y-4">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">Metode Pembayaran</h3>
                        
                        <div class="grid grid-cols-1">
                            <label class="flex items-center justify-between p-4 bg-white border-2 border-violet rounded-2xl shadow-xs">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="payment_method" value="QRIS" checked class="accent-violet w-4 h-4">
                                    <span class="text-sm font-black text-slate-800">QRIS Instant Payment</span>
                                </div>
                                <span class="text-[10px] bg-emerald-100 text-emerald-700 font-black px-2 py-0.5 rounded-md uppercase tracking-wide">Automated</span>
                            </label>
                        </div>

                        <div id="area-qris" class="pt-4 border-t border-slate-200/80 flex flex-col items-center justify-center">
                            <p class="text-xs text-slate-400 mb-4 text-center font-medium">Silakan pindai kode QRIS di bawah ini melalui dompet digital atau aplikasi mobile banking Anda:</p>
                            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm transition hover:shadow-md">
                                <img id="qris-image" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=FlickBook-QRIS-Invoice" alt="QRIS" class="w-36 h-36">
                            </div>
                            <p class="text-[9px] text-violet font-mono mt-3 font-black tracking-widest bg-violet/5 px-3 py-1 rounded-md">MERCHANT: FLICKBOOK CINEMA MANAGEMENT</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <button id="btn-pay-now" class="w-full bg-violet hover:bg-violet/90 text-white font-black py-4 rounded-2xl shadow-lg shadow-violet/10 hover:shadow-xl transition-all duration-200 text-sm tracking-wider uppercase">
                            Simulasi Bayar Sekarang
                        </button>
                        <a href="index.php" class="w-full block text-center text-xs text-slate-400 hover:text-red-500 font-semibold pt-2 transition duration-150">
                            Batalkan & Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        const API_BASE_URL = "<?php echo getenv('API_BASE_URL'); ?>";

        const urlParams = new URLSearchParams(window.location.search);
        const bookingId = urlParams.get('booking_id');

        if (!bookingId) {
            alert("ID Pemesanan tidak valid!");
            window.location.href = "index.php";
        }

        let paymentId = null; 

        async function fetchBookingInvoice() {
            try {
                const response = await fetch(`${API_BASE_URL}/bookings.php?booking_id=${bookingId}`);
                const result = await response.json();

                if (result.status === "success" && result.data) {
                    const invoice = result.data;

                    document.getElementById("invoice-id").innerText = `#BOOK-00${invoice.booking_id}`;
                    document.getElementById("pay-movie-title").innerText = invoice.movie_title || "Judul Film";
                    document.getElementById("pay-cinema").innerText = invoice.nama_bioskop || "-";
                    document.getElementById("pay-studio").innerText = invoice.nama_studio || "-";
                    
                    const tanggal = invoice.tanggal || invoice.Tanggal || "";
                    const jam = invoice.jam || invoice.Jam || "";
                    document.getElementById("pay-datetime").innerText = `${tanggal} [${jam.substring(0,5)}]`;

                    let nomorKursiInvoice = "Sudah Terpilih";

                    if (invoice.tickets && invoice.tickets.length > 0) {
                        const daftarKursi = invoice.tickets.map(tkt => `${tkt.baris}${tkt.nomor_kursi}`);
                        nomorKursiInvoice = daftarKursi.join(", ");
                    }

                    document.getElementById("pay-seats").innerText = nomorKursiInvoice;
                    const jumlahTiketTerbeli = invoice.tickets ? invoice.tickets.length : 1;
                    document.getElementById("pay-ticket-count").innerText = `${jumlahTiketTerbeli}x`;

                    const hargaSingle = parseInt(invoice.harga_tiket || invoice.Harga_tiket || 0);
                    document.getElementById("pay-price-single").innerText = `Rp ${hargaSingle.toLocaleString('id-ID')}`;

                    const subtotalKotor = hargaSingle * jumlahTiketTerbeli;

                    let diskonSquad = 0;
                    let diskonNightOwl = 0;
                    let listPromo = [];

                    if (jumlahTiketTerbeli >= 3) {
                        diskonSquad = subtotalKotor * 0.10;
                        listPromo.push("FRIENDS & FAMILY (10%)");
                    }

                    const stringJam = invoice.jam || invoice.Jam || "00:00:00";
                    const angkaJamTayang = parseInt(stringJam.substring(0, 2));

                    if (angkaJamTayang >= 21) {
                        diskonNightOwl = subtotalKotor * 0.05;
                        listPromo.push("NIGHT OWL (5%)");
                    }

                    const totalDiskonNominal = diskonSquad + diskonNightOwl;
                    const totalHargaFinal = subtotalKotor - totalDiskonNominal;

                    let namaPromoFinal = "Tidak Ada Promo";
                    if (listPromo.length === 2) {
                        namaPromoFinal = "NIGHT FAMILY & FRIENDS OF THE OWL (15%)";
                    } else if (listPromo.length === 1) {
                        namaPromoFinal = listPromo[0];
                    }

                    if (invoice.payment) {
                        paymentId = invoice.payment.payment_id;
                    } else {
                        paymentId = invoice.booking_id;
                    }

                    const payTotalElement = document.getElementById("pay-total");
                    
                    let promoInfoBox = document.getElementById("pay-promo-box");
                    if (!promoInfoBox) {
                        promoInfoBox = document.createElement("div");
                        promoInfoBox.id = "pay-promo-box";
                        promoInfoBox.className = "bg-slate-50 p-4 rounded-2xl border border-slate-200 my-4 text-xs space-y-2";
                        payTotalElement.parentNode.insertBefore(promoInfoBox, payTotalElement);
                    }

                    promoInfoBox.innerHTML = `
                        <div class="flex justify-between text-slate-500 font-semibold"><span>Subtotal:</span><span class="font-bold text-slate-700">Rp ${subtotalKotor.toLocaleString('id-ID')}</span></div>
                        <div class="flex justify-between text-violet font-bold"><span>Promo Aktif:</span><span class="font-black">${namaPromoFinal}</span></div>
                        <div class="flex justify-between text-emerald-600 font-bold"><span>Potongan:</span><span class="font-extrabold">- Rp ${totalDiskonNominal.toLocaleString('id-ID')}</span></div>
                    `;

                    payTotalElement.innerText = `Rp ${totalHargaFinal.toLocaleString('id-ID')}`;
                    
                    document.getElementById("btn-pay-now").setAttribute("data-amount", totalHargaFinal);

                } else {
                    alert("Tagihan tidak ditemukan atau sudah kedaluwarsa.");
                    window.location.href = "index.php";
                }
            } catch (error) {
                console.error("Gagal memuat invoice pembayaran:", error);
            }
        }

        async function processPayment() {
            const btnPay = document.getElementById("btn-pay-now");
            btnPay.disabled = true;
            btnPay.innerText = "Memverifikasi Pembayaran...";

            try {
                const response = await fetch(`${API_BASE_URL}/payments.php`, {
                    method: "PUT",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        payment_id: parseInt(paymentId),
                        status_pembayaran: "paid"
                    })
                });

                const result = await response.json();

                if (result.status === "success") {
                    
                    const resInvoice = await fetch(`${API_BASE_URL}/bookings.php?booking_id=${bookingId}`);
                    const dataInvoice = await resInvoice.json();

                    if (dataInvoice.status === "success" && dataInvoice.data.tickets) {
                        for (const ticket of dataInvoice.data.tickets) {
                            await fetch(`${API_BASE_URL}/seat_availability.php`, {
                                method: "PUT",
                                headers: { "Content-Type": "application/json" },
                                body: JSON.stringify({
                                    showtime_id: parseInt(bookingId), 
                                    seat_id: parseInt(ticket.seat_id),
                                    status_kursi: "occupied" 
                                })
                            });
                        }
                    }

                    alert("Pembayaran Sukses! Kursi Anda telah diamankan permanen.");
                    window.location.href = `ticket.php?booking_id=${bookingId}`;
                } else {
                    alert("Pembayaran Gagal: " + result.message);
                    btnPay.disabled = false;
                    btnPay.innerText = "Simulasi Bayar Sekarang";
                }
            } catch (error) {
                console.error("Error saat memproses pembayaran:", error);
                btnPay.disabled = false;
                btnPay.innerText = "Simulasi Bayar Sekarang";
            }
        }

        document.addEventListener("DOMContentLoaded", fetchBookingInvoice);
        document.getElementById("btn-pay-now").addEventListener("click", processPayment);
    </script>
</body>
</html>