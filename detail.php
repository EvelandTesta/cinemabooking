<?php include 'env.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Film - FlickBook</title>
    <link rel="stylesheet" href="./dist/css/style.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <header class="bg-white shadow-sm w-full flex items-center z-10 border-b border-slate-100">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between relative py-4 max-w-4xl mx-auto">
                <a href="index.php" class="text-2xl font-bold tracking-wider text-slate-900">Flick<span class="text-violet">Book</span></a>
                <a href="index.php" class="text-sm font-semibold text-violet hover:underline">← Kembali ke Beranda</a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-6 max-w-4xl pt-12 pb-24">
        
        <div class="flex flex-col md:flex-row items-start gap-8 mb-10 pb-6">
            <div class="w-full md:w-max flex justify-center shrink-0">
                <div class="bg-dark rounded-xl overflow-hidden shadow-lg w-[220px] h-[330px]">
                    <img id="movie-poster"
                        src="https://images.unsplash.com/photo-1635805737707-575885ab0820?auto=format&fit=crop&w=600&q=80"
                        alt="Poster Film"
                        class="w-full h-full object-cover object-center">
                </div>
            </div>

            <div class="flex-1 flex flex-col pt-2">
                <p class="text-xs font-bold text-cyan-600 mb-1 tracking-wide">
                    Tayang : <span id="movie-release-date"></span>
                </p>
                
                <h1 id="movie-title" class="font-black text-3xl sm:text-4xl text-slate-800 tracking-tight uppercase leading-none mb-3">
                    Memuat Judul Film...
                </h1>
                
                <p id="movie-genre" class="text-xs font-medium text-slate-500 mb-6">
                    Genre
                </p>
                
                <div class="flex flex-wrap items-center gap-6 text-sm text-slate-700">
                    <a id="trailer-link" href="#" target="_blank" 
                    class="inline-flex items-center group cursor-pointer bg-slate-100 hover:bg-violet/10 border border-slate-200 hover:border-violet px-4 py-2 rounded-full transition-all duration-300">
                        
                        <div class="w-8 h-8 rounded-full bg-violet text-white flex items-center justify-center mr-3 group-hover:scale-110 transition duration-300">
                            <svg class="w-4 h-4 fill-current ml-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                        
                        <span class="font-black text-slate-800 text-xs sm:text-sm group-hover:text-violet transition tracking-wide uppercase">
                            Lihat Trailer
                        </span>
                    </a>
                    
                    <div class="hidden sm:block h-6 w-px bg-slate-200"></div>

                    <div class="flex items-center space-x-2">
                        <span id="movie-duration-badge" class="bg-slate-200/70 text-slate-700 text-xs font-semibold px-2.5 py-1 rounded-md">
                            1h 51m
                        </span>
                        <span id="movie-rating-badge" class="text-[10px] font-black px-2.5 py-1 rounded-md tracking-wider uppercase shadow-md transition-all duration-300">-</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-b border-slate-200 mb-8">
            <nav class="flex space-x-8 text-sm font-medium">
                <button onclick="switchTab('jadwal')" id="tab-jadwal-btn" class="border-b-2 border-violet text-slate-900 pb-3 px-1 font-bold transition duration-150 cursor-pointer">
                    Jadwal
                </button>
                <button onclick="switchTab('detail')" id="tab-detail-btn" class="border-b-2 border-transparent text-slate-400 hover:text-slate-600 pb-3 px-1 transition duration-150 cursor-pointer">
                    Detail
                </button>
            </nav>
        </div>

        <div id="tab-content-jadwal" class="block">
            <div id="cinemas-container" class="space-y-5">
                <div class="text-center py-6 text-slate-500 text-sm">Memuat jadwal tayang...</div>
            </div>
        </div>

        <div id="tab-content-detail" class="hidden">
            <div class="bg-white p-6 rounded-xl shadow-xs border border-slate-200/80">
                <h3 class="font-bold text-base text-slate-900 mb-3">Sinopsis Resmi</h3>
                <p id="movie-desc" class="text-slate-600 leading-relaxed whitespace-pre-line text-sm">
                    Memuat deskripsi sinopsis film...
                </p>
            </div>
        </div>

    </main>

    <footer class="py-6 text-center text-xs text-slate-400 border-t border-slate-200 bg-white">
        &copy; 2026 FlickBook Cinema System.
    </footer>

    <script>
        const API_BASE_URL = "<?php echo getenv('API_BASE_URL'); ?>";

        const urlParams = new URLSearchParams(window.location.search);
        const movieId = urlParams.get('movie_id');

        if (!movieId) {
            alert("ID Film tidak ditemukan!");
            window.location.href = "index.php";
        }

        function switchTab(target) {
            const jadwalBtn = document.getElementById("tab-jadwal-btn");
            const detailBtn = document.getElementById("tab-detail-btn");
            const jadwalContent = document.getElementById("tab-content-jadwal");
            const detailContent = document.getElementById("tab-content-detail");

            if (target === 'jadwal') {
                jadwalBtn.className = "border-b-2 border-violet text-slate-900 pb-3 px-1 font-bold transition duration-150 cursor-pointer";
                detailBtn.className = "border-b-2 border-transparent text-slate-400 hover:text-slate-600 pb-3 px-1 transition duration-150 cursor-pointer";
                jadwalContent.classList.remove("hidden");
                detailContent.classList.add("hidden");
            } else {
                detailBtn.className = "border-b-2 border-violet text-slate-900 pb-3 px-1 font-bold transition duration-150 cursor-pointer";
                jadwalBtn.className = "border-b-2 border-transparent text-slate-400 hover:text-slate-600 pb-3 px-1 transition duration-150 cursor-pointer";
                detailContent.classList.remove("hidden");
                jadwalContent.classList.add("hidden");
            }
        }

        async function fetchMovieDetail() {
            try {
                const response = await fetch(`${API_BASE_URL}/movies.php?movie_id=${movieId}`);
                const result = await response.json();

                if (result.status === "success" && result.data) {
                    const movie = result.data;
                    
                    document.getElementById("movie-title").innerText = movie.judul;
                    document.getElementById("movie-genre").innerText = movie.genre;
                    document.getElementById("movie-desc").innerText = movie.sinopsis || "Sinopsis tidak tersedia.";
                    
                    const totalMenit = parseInt(movie.durasi) || 0;
                    const jam = Math.floor(totalMenit / 60);
                    const sisaMenit = totalMenit % 60;
                    document.getElementById("movie-duration-badge").innerText = `${jam}h ${sisaMenit}m`;
                    
                    // FALLBACK CHECKING VARIABEL RATING USIA
                    const rawRating = movie.rating_umur || movie.Rating_umur || movie.Rating_Umur || movie.rating || "SU";
                    const ratingBadge = document.getElementById("movie-rating-badge");
                    
                    ratingBadge.innerText = rawRating;

                    // FIX KONSISTENSI VISUAL: Terapkan gaya warna seragam Violet + Glow Effect yang menonjol
                    ratingBadge.className = "text-[10px] font-black px-2.5 py-1 rounded-md tracking-wider uppercase bg-violet text-white shadow-lg shadow-violet/40 border border-violet-400/20";
                    
                    if (movie.poster_url) {
                        document.getElementById("movie-poster").src = movie.poster_url;
                    }

                    const trailerLink = document.getElementById("trailer-link");

                    if (movie.trailer_url && movie.trailer_url.trim() !== "") {
                        trailerLink.href = movie.trailer_url;
                        trailerLink.style.display = "inline-flex"; 
                    } else {
                        trailerLink.style.display = "none"; 
                    }
                }
            } catch (error) {
                console.error("Error fetching movie detail:", error);
            }
        }

        async function fetchMovieShowtimes() {
            try {
                const response = await fetch(`${API_BASE_URL}/showtimes.php?movie_id=${movieId}`);
                const result = await response.json();
                
                const container = document.getElementById("cinemas-container");
                container.innerHTML = ""; 

                if (result.status === "success" && Array.isArray(result.data) && result.data.length > 0) {
                    const groupedByCinema = {};

                    result.data.forEach(st => {
                        const cinemaName = st.nama_bioskop || "Bioskop FlickBook";
                        if (!groupedByCinema[cinemaName]) {
                            groupedByCinema[cinemaName] = [];
                        }
                        groupedByCinema[cinemaName].push(st);
                    });

                    for (const cinemaName in groupedByCinema) {
                        let showtimesButtonsHTML = "";
                        const cinemaType = groupedByCinema[cinemaName][0].tipe_bioskop || "Reguler";

                        groupedByCinema[cinemaName].forEach(st => {
                            const stringJam = st.jam || st.Jam || "00:00:00";
                            const hargaTiket = parseInt(st.harga_tiket || st.Harga_tiket || 0);

                            showtimesButtonsHTML += `
                                <a href="select_seat.php?showtime_id=${st.showtime_id}" 
                                class="relative z-30 block bg-slate-100 border border-slate-200 text-slate-800 px-3 py-2 rounded-xl font-medium text-xs text-center hover:bg-violet hover:text-white hover:border-violet transition duration-200 shadow-xs cursor-pointer group">
                                    <div class="text-[9px] text-slate-400 group-hover:text-violet-200 transition font-semibold uppercase tracking-wider">${st.nama_studio || 'Studio'}</div>
                                    <div class="text-sm font-bold mt-0.5">${stringJam.substring(0, 5)}</div>
                                    <div class="text-[10px] text-emerald-600 font-bold mt-0.5 group-hover:text-white transition">Rp ${hargaTiket.toLocaleString('id-ID')}</div>
                                </a>
                            `;
                        });

                        const cinemaCardHTML = `
                            <div class="bg-white p-5 rounded-xl shadow-xs border border-slate-200 transition duration-200 hover:shadow-sm">
                                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                                    <h3 class="font-bold text-sm text-slate-900 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-violet shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        ${cinemaName}
                                    </h3>
                                    <span class="text-[10px] bg-violet/10 text-violet font-bold px-2 py-0.5 rounded tracking-wide uppercase">${cinemaType}</span>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5">
                                    ${showtimesButtonsHTML}
                                </div>
                            </div>
                        `;
                        container.innerHTML += cinemaCardHTML;
                    }

                } else {
                    container.innerHTML = `
                        <div class="w-full text-center py-16 flex flex-col items-center justify-center">
                            <div class="flex flex-col space-y-2 mb-4 animate-pulse">
                                <div class="w-10 h-1.5 bg-teal-600 rounded-full transform rotate-3"></div>
                                <div class="w-10 h-1.5 bg-teal-600 rounded-full transform -rotate-3"></div>
                            </div>
                            <h3 class="text-slate-800 font-bold text-base mb-1">Film belum tayang, nih</h3>
                            <p class="text-slate-400 text-xs max-w-xs leading-relaxed">
                                Kalau udah, nanti balik ke sini untuk beli tiket, ya.
                            </p>
                        </div>`;
                }
            } catch (error) {
                console.error("Error fetching showtimes:", error);
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            fetchMovieDetail();
            fetchMovieShowtimes();
        });
    </script>

</body>
</html>