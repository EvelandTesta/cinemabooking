<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Kursi - FlickBook</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { violet: '#8b5cf6' } } } }
    </script>
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased">

    <header class="bg-slate-900 border-b border-slate-800 w-full py-4">
        <div class="container mx-auto px-6 flex justify-between items-center max-w-6xl">
            <a href="index.php" class="text-2xl font-bold tracking-wider text-white">Flick<span class="text-violet">Book</span></a>
            <button onclick="history.back()" class="text-sm text-violet hover:underline font-semibold">← Kembali ke Detail Film</button>
        </div>
    </header>

    <main class="container mx-auto px-6 max-w-6xl pt-8 pb-24 grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-slate-900 p-8 rounded-3xl border border-slate-800 shadow-2xl flex flex-col items-center">
            
            <div class="w-full flex items-center gap-6 mb-10 border-b border-slate-800 pb-6">
                
                <div>
                    <h1 id="showtime-movie-title" class="text-2xl font-black text-white tracking-tight">Memuat Film...</h1>
                    <p id="showtime-info" class="text-sm text-slate-400 mt-1">Memuat informasi bioskop & studio...</p>
                </div>
            </div>

            <div class="w-full max-w-xl mx-auto mb-16 text-center">
                <div class="w-full h-3 bg-gradient-to-r from-transparent via-violet/60 to-transparent rounded-full shadow-[0_4px_25px_rgba(139,92,246,0.5)]"></div>
                <p class="text-[10px] text-slate-500 uppercase tracking-[0.3em] mt-3 font-bold">Layar Bioskop Di Sini</p>
            </div>

            <div class="w-full overflow-x-auto py-2 flex justify-center">
                <div id="seating-grid" class="flex flex-col gap-4 min-w-max p-4 bg-slate-950/40 rounded-2xl border border-slate-800/50">
                    <p class="text-center text-slate-500 py-12 text-sm">Memuat denah kursi...</p>
                </div>
            </div>

            <div class="flex flex-wrap justify-center gap-6 text-xs mt-12 text-slate-400 border-t border-slate-800/60 w-full pt-6">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 bg-slate-800 border border-slate-700 rounded-md"></div>
                    <span>Tersedia</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 bg-violet rounded-md"></div>
                    <span>Dipilih</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 bg-slate-700 opacity-30 rounded-md"></div>
                    <span>Terisi</span>
                </div>
            </div>
        </div>

        <div class="bg-slate-900 p-8 rounded-3xl border border-slate-800 h-fit sticky top-8 shadow-2xl">
            <div>
                <h2 class="text-lg font-bold text-white border-b border-slate-800 pb-3 mb-5 tracking-tight">Ringkasan Pesanan</h2>
                
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between items-start">
                        <span class="text-slate-400">Kursi Dipilih:</span>
                        <span id="selected-seats-label" class="font-bold text-violet text-right max-w-[150px] break-words">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Harga per Tiket:</span>
                        <span id="ticket-price-label" class="font-semibold text-slate-200">Rp 0</span>
                    </div>
                </div>

                <hr class="border-slate-800 my-5">

                <div class="flex justify-between items-center">
                    <span class="text-base text-slate-400 font-medium">Total Pembayaran:</span>
                    <span id="total-price-label" class="text-2xl font-black text-emerald-400">Rp 0</span>
                </div>
            </div>

            <button id="btn-checkout" disabled
                    class="w-full mt-8 bg-slate-800 text-slate-500 font-bold py-4 px-4 rounded-xl cursor-not-allowed transition duration-200">
                Pilih Kursi Terlebih Dahulu
            </button>
        </div>

    </main>

    <script>
        const API_BASE_URL = "http://localhost/cinema_booking/api"; 

        const urlParams = new URLSearchParams(window.location.search);
        const showtimeId = urlParams.get('showtime_id');

        if (!showtimeId) {
            alert("Sesi jadwal tayang tidak valid!");
            window.location.href = "index.php";
        }

        let ticketPrice = 0;
        let selectedSeats = []; 

        async function fetchSeatingLayout() {
            try {
                const response = await fetch(`${API_BASE_URL}/seat_availability.php?showtime_id=${showtimeId}`);
                const result = await response.json();

                if (result.status === "success" && Array.isArray(result.data)) {
                    const dataKursi = result.data;

                    if (dataKursi.length > 0) {
                        const firstItem = dataKursi[0];

                        document.getElementById("showtime-movie-title").innerText = firstItem.movie_title || "FlickBook Movie";
                        if(firstItem.poster_url) {
                            document.getElementById("movie-poster-thumb").src = firstItem.poster_url;
                        }

                        let formatJam = "WIB";
                        if (firstItem.jam && typeof firstItem.jam === 'string') {
                            formatJam = firstItem.jam.substring(0, 5) + " WIB";
                        } else if (firstItem.Jam && typeof firstItem.Jam === 'string') {
                            formatJam = firstItem.Jam.substring(0, 5) + " WIB";
                        }

                        let formatTanggal = "Hari Ini";
                        if (firstItem.tanggal) {
                            formatTanggal = firstItem.tanggal;
                        } else if (firstItem.Tanggal) {
                            formatTanggal = firstItem.Tanggal;
                        }

                        let namaStudio = firstItem.nama_studio || "Studio Reguler";
                        document.getElementById("showtime-info").innerText = `${namaStudio} • ${formatTanggal} [${formatJam}]`;
                        
                        ticketPrice = parseInt(firstItem.harga_tiket) || parseInt(firstItem.Harga_tiket) || 50000; 
                        document.getElementById("ticket-price-label").innerText = `Rp ${ticketPrice.toLocaleString('id-ID')}`;
                    }

                    renderSeats(dataKursi);
                } else {
                    console.error("Gagal menarik data kursi:", result.message);
                }
            } catch (error) {
                console.error("Koneksi API Error:", error);
                document.getElementById("seating-grid").innerHTML = `<p class="text-center text-red-500 py-12">Terjadi kesalahan pemrosesan denah kursi.</p>`;
            }
        }

        // BERKOTAK DAN RAPI BERDASARKAN ROW GROUPING
        function renderSeats(seats) {
            const gridContainer = document.getElementById("seating-grid");
            gridContainer.innerHTML = "";

            const currentUserId = sessionStorage.getItem("user_id");

            // 1. Dapatkan daftar baris unik abjad (A, B, C...) lalu urutkan
            const uniqueRows = [...new Set(seats.map(s => s.baris))].sort();
            
            // 2. Cari jumlah kolom maksimal untuk pembentukan struktur grid template
            const maxSeatNum = Math.max(...seats.map(s => parseInt(s.nomor_kursi)));

            // 3. Loop per alfabet baris untuk membuat kontainer row tersendiri
            uniqueRows.forEach(rowLetter => {
                const rowDiv = document.createElement("div");
                // Grid horizontal per baris kursi dengan gap-x antar kursi
                rowDiv.className = "grid gap-2";
                rowDiv.style.gridTemplateColumns = `repeat(${maxSeatNum}, minmax(0, 1fr))`;

                // Filter & sorting kursi yang bersemayam di baris abjad ini (e.g. A1, A2, A3)
                const rowSeats = seats.filter(s => s.baris === rowLetter)
                                      .sort((a, b) => parseInt(a.nomor_kursi) - parseInt(b.nomor_kursi));

                rowSeats.forEach(seat => {
                    const seatLabel = `${seat.baris}${seat.nomor_kursi}`;
                    const isOccupied = seat.status_kursi === 'occupied';
                    const isMyTemporaryBook = (seat.status_kursi === 'booked' && seat.user_id == currentUserId);

                    const seatButton = document.createElement("button");
                    seatButton.innerText = seatLabel;
                    seatButton.dataset.id = seat.seat_id;
                    seatButton.dataset.label = seatLabel;

                    let baseClass = "w-10 h-10 text-[10px] font-bold rounded-lg flex items-center justify-center transition-all duration-150 border ";

                    if (isOccupied) {
                        baseClass += "bg-slate-800 opacity-20 text-slate-500 border-transparent cursor-not-allowed";
                        seatButton.disabled = true;
                    } else if (seat.status_kursi === 'booked' && !isMyTemporaryBook) {
                        baseClass += "bg-amber-600/20 text-amber-400 border-amber-500/30 cursor-not-allowed";
                        seatButton.disabled = true;
                    } else if (isMyTemporaryBook) {
                        baseClass += "bg-violet text-white border-violet cursor-pointer animate-pulse";
                        if (!selectedSeats.some(item => item.seat_id === seat.seat_id)) {
                            selectedSeats.push({ seat_id: seat.seat_id, label: seatLabel });
                        }
                    } else {
                        baseClass += "bg-slate-800 border-slate-700/80 text-slate-300 hover:bg-slate-700 hover:text-white hover:border-slate-500 cursor-pointer";
                    }

                    seatButton.className = baseClass;

                    if (!seatButton.disabled) {
                        seatButton.addEventListener("click", () => toggleSeatSelection(seatButton));
                    }

                    rowDiv.appendChild(seatButton);
                });

                gridContainer.appendChild(rowDiv);
            });

            updateOrderSummary();
        }

        function toggleSeatSelection(button) {
            const seatId = button.dataset.id;
            const seatLabel = button.dataset.label;

            const existingIndex = selectedSeats.findIndex(item => item.seat_id === seatId);

            if (existingIndex > -1) {
                selectedSeats.splice(existingIndex, 1);
                button.classList.remove("bg-violet", "text-white", "border-violet");
                button.classList.add("bg-slate-800", "text-slate-300", "border-slate-700");
            } else {
                selectedSeats.push({ seat_id: seatId, label: seatLabel });
                button.classList.remove("bg-slate-800", "text-slate-300", "border-slate-700");
                button.classList.add("bg-violet", "text-white", "border-violet");
            }

            updateOrderSummary();
        }

        function updateOrderSummary() {
            const btnCheckout = document.getElementById("btn-checkout");
            const selectedLabels = selectedSeats.map(s => s.label).join(", ");

            document.getElementById("selected-seats-label").innerText = selectedLabels || "-";
            
            const totalPayment = selectedSeats.length * ticketPrice;
            document.getElementById("total-price-label").innerText = `Rp ${totalPayment.toLocaleString('id-ID')}`;

            if (selectedSeats.length > 0) {
                btnCheckout.disabled = false;
                btnCheckout.className = "w-full mt-8 bg-violet hover:bg-violet/90 text-white font-bold py-4 px-4 rounded-xl shadow-lg shadow-violet/20 active:scale-[0.99] transition duration-200 cursor-pointer";
                btnCheckout.innerText = `Konfirmasi Pesanan (${selectedSeats.length} Kursi)`;
            } else {
                btnCheckout.disabled = true;
                btnCheckout.className = "w-full mt-8 bg-slate-800 text-slate-500 font-bold py-4 px-4 rounded-xl cursor-not-allowed transition duration-200";
                btnCheckout.innerText = "Pilih Kursi Terlebih Dahulu";
            }
        }

        document.addEventListener("DOMContentLoaded", fetchSeatingLayout);

        async function executeCheckout() {
            if (!selectedSeats || selectedSeats.length === 0) {
                alert("Silakan pilih minimal satu kursi terlebih dahulu!");
                return;
            }

            const btnCheckout = document.getElementById("btn-checkout");
            btnCheckout.disabled = true;
            btnCheckout.innerText = "Memproses Booking...";

            const seatIdsArray = selectedSeats.map(s => parseInt(s.seat_id));
            const activeUserId = sessionStorage.getItem("user_id") || 1;

            const payload = {
                user_id: parseInt(activeUserId),
                showtime_id: parseInt(showtimeId),
                seats: seatIdsArray
            };

            try {
                const response = await fetch(`${API_BASE_URL}/bookings.php`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.status === "success" && result.data) {
                    alert("Booking Berhasil! Kursi Anda telah dikunci sementara.");
                    window.location.href = `payment.php?booking_id=${result.data.booking_id}`;
                } else {
                    alert("Gagal melakukan booking: " + (result.message || "Eror tidak diketahui"));
                    updateOrderSummary();
                }
            } catch (error) {
                console.error("Error fatal saat memproses checkout:", error);
                alert("Terjadi kesalahan koneksi ke server API.");
                updateOrderSummary();
            }
        }

        document.getElementById("btn-checkout").addEventListener("click", executeCheckout);
    </script>
</body>
</html>