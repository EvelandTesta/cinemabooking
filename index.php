<?php include 'env.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlickBook</title>
    <link rel="stylesheet" href="./dist/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        /* Dynamic styling for main-header (Always white box behind navbar) */
        #main-header {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            z-index: 50 !important;
            background: rgba(255, 255, 255, 0.85) !important; /* Glassmorphic whitebox */
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
            transition: all 0.3s ease-in-out !important;
        }
        
        #main-header.scrolled {
            background: rgba(255, 255, 255, 0.96) !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1) !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08) !important;
        }
        
        /* Logo color (always dark) */
        #logo {
            color: #030712 !important;
            transition: color 0.3s ease-in-out !important;
        }
        
        /* Nav Menu Links color (always dark, hover violet) */
        .nav-link {
            color: #374151 !important; /* slate-700 */
            transition: color 0.3s ease-in-out !important;
        }
        .nav-link:hover {
            color: #7C3AED !important;
        }
        
        /* Hamburger line color (always dark) */
        .hamburger-line {
            background-color: #030712 !important;
            transition: background-color 0.3s ease-in-out !important;
        }
        
        /* Mobile menu responsive design */
        @media (max-width: 1023px) {
            #nav-menu {
                background-color: #ffffff !important;
                border: 1px solid rgba(0, 0, 0, 0.08) !important;
            }
            #main-header.scrolled #nav-menu {
                background-color: #ffffff !important;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
            }
        }
        
        /* Perfect Circle Avatars (Guaranteed No-Stretch) */
        .profile-avatar-circle {
            width: 36px !important;
            height: 36px !important;
            min-width: 36px !important;
            min-height: 36px !important;
            max-width: 36px !important;
            max-height: 36px !important;
            border-radius: 9999px !important;
            aspect-ratio: 1/1 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
        }
        
        .dropdown-avatar-circle {
            width: 40px !important;
            height: 40px !important;
            min-width: 40px !important;
            min-height: 40px !important;
            max-width: 40px !important;
            max-height: 40px !important;
            border-radius: 9999px !important;
            aspect-ratio: 1/1 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
        }
        
        /* Dropdown Item Styling (Guaranteed No-Wrap & Perfect Spacing) */
        .dropdown-item {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            padding: 10px 16px !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            white-space: nowrap !important;
            transition: all 0.2s ease-in-out !important;
            text-decoration: none !important;
        }
    </style>
</head>
<body class="bg-primary"> 
    
    <header id="main-header" class="w-full flex items-center transition-all duration-300">
        <div class="container mx-auto px-4 max-w-287.5">
        <div class="flex items-center justify-between relative w-full">
            
            <div class="shrink-0">
                <a href="#home" id="logo" class="text-xl sm:text-2xl font-bold tracking-wider block py-5 sm:py-6">
                    Flick<span class="text-violet">Book</span>
                </a>
            </div>

            <div class="flex items-center gap-4 sm:gap-6">
                
                <nav id="nav-menu" class="hidden sm:block">
                    <ul class="flex items-center gap-2 md:gap-4">
                        <li class="group"><a href="#home" class="nav-link text-sm md:text-base py-2 px-2 md:px-4 flex group-hover:text-violet">Home</a></li>
                        <li class="group"><a href="#now-showing" class="nav-link text-sm md:text-base py-2 px-2 md:px-4 flex group-hover:text-violet">Now Showing</a></li>
                        <li class="group"><a href="#coming-soon" class="nav-link text-sm md:text-base py-2 px-2 md:px-4 flex group-hover:text-violet">Coming Soon</a></li>
                        <li class="group"><a href="#promo" class="nav-link text-sm md:text-base py-2 px-2 md:px-4 flex group-hover:text-violet">Promo</a></li>
                        <li class="group"><a href="#partners" class="nav-link text-sm md:text-base py-2 px-2 md:px-4 flex group-hover:text-violet">Partners</a></li>
                    </ul>
                </nav>

                <div id="profile-wrapper" class="hidden relative items-center">
                    <button id="profile-btn" onclick="toggleProfileDropdown()"
                        title="Profile"
                        class="profile-avatar-circle"
                        style="background: linear-gradient(135deg, #7C3AED, #5b21b6); cursor: pointer; border: none;">
                        <span id="profile-avatar" class="text-white font-black text-sm leading-none">U</span>
                    </button>

                    <div id="profile-dropdown" class="hidden absolute right-0 top-full mt-2 w-60 rounded-2xl overflow-hidden z-50"
                        style="background:#fff; box-shadow:0 8px 40px rgba(0,0,0,0.15), 0 2px 8px rgba(0,0,0,0.08); border:1px solid rgba(0,0,0,0.07);">

                        <div class="px-4 py-4" style="background: linear-gradient(135deg, #7C3AED 0%, #5b21b6 100%);">
                            <div class="flex items-center gap-3">
                                <div id="dropdown-avatar" class="dropdown-avatar-circle"
                                    style="background:rgba(255,255,255,0.2); border:2px solid rgba(255,255,255,0.4); color:#fff; font-weight:900; font-size:16px;">U</div>
                                <div style="overflow:hidden;">
                                    <p id="dropdown-name" class="font-black text-white text-sm truncate">User</p>
                                    <span id="dropdown-role"
                                        class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wide mt-0.5"
                                        style="background:rgba(255,255,255,0.25); color:#fff;">user</span>
                                </div>
                            </div>
                        </div>

                        <div class="block sm:hidden py-1 border-b border-slate-100">
                            <div class="px-4 py-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Navigasi</div>
                            <a href="#home" class="dropdown-item" style="color:#1e1b4b;" onmouseover="this.style.background='#f5f3ff'; this.style.color='#7C3AED';" onmouseout="this.style.background=''; this.style.color='#1e1b4b';">Home</a>
                            <a href="#now-showing" class="dropdown-item" style="color:#1e1b4b;" onmouseover="this.style.background='#f5f3ff'; this.style.color='#7C3AED';" onmouseout="this.style.background=''; this.style.color='#1e1b4b';">Now Showing</a>
                            <a href="#coming-soon" class="dropdown-item" style="color:#1e1b4b;" onmouseover="this.style.background='#f5f3ff'; this.style.color='#7C3AED';" onmouseout="this.style.background=''; this.style.color='#1e1b4b';">Coming Soon</a>
                            <a href="#promo" class="dropdown-item" style="color:#1e1b4b;" onmouseover="this.style.background='#f5f3ff'; this.style.color='#7C3AED';" onmouseout="this.style.background=''; this.style.color='#1e1b4b';">Promo</a>
                            <a href="#partners" class="dropdown-item" style="color:#1e1b4b;" onmouseover="this.style.background='#f5f3ff'; this.style.color='#7C3AED';" onmouseout="this.style.background=''; this.style.color='#1e1b4b';">Partners</a>
                        </div>

                        <div class="py-1">
                            <div class="px-4 py-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider block sm:hidden">Akun Saya</div>
                            <a href="profile.php" class="dropdown-item" style="color:#1e1b4b;" onmouseover="this.style.background='#f5f3ff'; this.style.color='#7C3AED';" onmouseout="this.style.background=''; this.style.color='#1e1b4b';">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil Saya
                            </a>
                            <a href="my_tickets.php" class="dropdown-item" style="color:#1e1b4b;" onmouseover="this.style.background='#f5f3ff'; this.style.color='#7C3AED';" onmouseout="this.style.background=''; this.style.color='#1e1b4b';">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                Tiket Saya
                            </a>
                            <a href="etc.php" class="dropdown-item" style="color:#1e1b4b;" onmouseover="this.style.background='#f5f3ff'; this.style.color='#7C3AED';" onmouseout="this.style.background=''; this.style.color='#1e1b4b';">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                Pusat Bantuan
                            </a>
                        </div>

                        <div style="border-top:1px solid #f1f5f9;">
                            <a href="logout.php" class="dropdown-item" style="color:#ef4444;" onmouseover="this.style.background='#fff5f5';" onmouseout="this.style.background='';">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </header>

    <section id="home" class="pt-32 pb-16 overflow-hidden bg-primary">
        <div class="container mx-auto px-4 max-w-287.5">
            <div class="swiper mySwiper w-full rounded-2xl shadow-2xl overflow-hidden">
                <div class="swiper-wrapper">
                    <div class="swiper-slide w-full">
                        <img src="dist/img/pjsk.jpg" alt="Landing Page" class="w-full aspect-16/8 md:aspect-21/9 object-cover pointer-events-none">
                    </div>
                    <div class="swiper-slide w-full">
                        <img src="dist/img/calamity.jpg" alt="Landing Page" class="w-full aspect-16/8 md:aspect-21/9 object-cover pointer-events-none">
                    </div>
                    <div class="swiper-slide w-full">
                        <img src="dist/img/the-nightmare-before-christmas-movie.jpg" alt="Landing Page" class="w-full aspect-16/8 md:aspect-21/9 object-cover pointer-events-none">
                    </div>
                </div>
                <div class="swiper-pagination bottom-4!"></div>
            </div>
        </div>
    </section>

    <section id="now-showing" class="pt-16 pb-24 bg-primary">
        <div class="container mx-auto px-4 max-w-287.5">
            <div class="w-full px-4 mb-12">
                <div class="max-w-xl">
                    <h4 class="font-bold text-lg text-violet uppercase tracking-wider mb-2">Sedang Tayang</h4>
                    <h2 class="font-bold text-slate-900 text-3xl sm:text-4xl">Now Showing</h2>
                    <div class="w-16 h-1 bg-violet mt-3 rounded-full"></div>
                </div>
            </div>
            <div id="now-showing-container" class="flex flex-wrap -mx-4"></div>
        </div>
    </section>

    <section id="coming-soon" class="pt-24 pb-24 bg-dark">
        <div class="container mx-auto px-4 max-w-287.5">
            <div class="w-full px-4 mb-10">
                <h4 class="font-bold text-lg text-violet uppercase tracking-wider mb-2">Akan Datang</h4>
                <h2 class="font-bold text-white text-3xl sm:text-4xl">Coming Soon</h2>
                <div class="w-16 h-1 bg-violet mt-3 rounded-full"></div>
            </div>
            
            <div id="coming-soon-container" class="flex flex-wrap -mx-4"></div>

            <div class="w-full flex justify-center mt-10">
                <button id="view-more-btn" onclick="loadNextComingSoonPage()" class="hidden text-sm font-bold text-white border border-slate-800 bg-slate-900 px-8 py-3 rounded-lg hover:bg-violet hover:text-dark transition duration-300">
                    View More
                </button>
            </div>
        </div>
    </section>

    <section id="promo" class="bg-primary" style="padding-top: 8rem; padding-bottom: 8rem;">
        <div class="container mx-auto px-4 max-w-287.5">
            <div class="w-full mb-14 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-lg text-violet uppercase tracking-wider mb-2">Penawaran Menarik</h4>
                    <h2 class="font-bold text-slate-900 text-3xl sm:text-4xl">Promo & Event Terkini</h2>
                    <div class="w-16 h-1 bg-violet mt-3 rounded-full"></div>
                </div>  
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 w-full">
                <div class="group relative h-105 rounded-2xl overflow-hidden shadow-xl bg-slate-900 flex flex-col justify-between p-8">
                    <img src="https://images.unsplash.com/photo-1574267432553-4b4628081c31?auto=format&fit=crop&w=800&q=80" 
                         alt="Squad Promo" 
                         class="absolute inset-0 w-full h-full object-cover opacity-55 transition duration-500 group-hover:scale-102 pointer-events-none">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent z-0"></div>
                    
                    <div class="z-10">
                        <span class="bg-violet text-white text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider shadow-md inline-block">
                            FRIENDS & FAMILY
                        </span>
                    </div>
                    
                    <div class="relative z-10 w-full pt-24">
                        <h3 class="text-white font-bold text-2xl mb-2 leading-snug">
                            Promo Squad: Diskon 10% Min. Pembelian 3 Tiket
                        </h3>
                        <p class="text-slate-300 text-xs max-w-md line-clamp-2 mb-5 leading-relaxed">
                            Beli minimal 3 kursi dalam satu kali transaksi untuk film apa saja, maka nikmati potongan subtotal sebesar 10% tanpa syarat!
                        </p>
                        <a href="#now-showing" class="inline-flex items-center text-xs font-bold text-white group-hover:text-violet transition">
                            Pilih Kursi Sekarang <svg class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>

                <div class="group relative h-105 rounded-2xl overflow-hidden shadow-xl bg-slate-900 flex flex-col justify-between p-8">
                    <img src="https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?auto=format&fit=crop&w=800&q=80" 
                         alt="Night Owl Promo" 
                         class="absolute inset-0 w-full h-full object-cover opacity-55 transition duration-500 group-hover:scale-102 pointer-events-none">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent z-0"></div>
                    
                    <div class="z-10">
                        <span class="bg-amber-500 text-slate-900 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider shadow-md inline-block">
                            Night Owl Special
                        </span>
                    </div>
                    
                    <div class="relative z-10 w-full pt-24">
                        <h3 class="text-white font-bold text-2xl mb-2 leading-snug">
                            Night Owl Promo: Diskon 5% untuk Jam Tayang > 21:00
                        </h3>
                        <p class="text-slate-300 text-xs max-w-md line-clamp-2 mb-5 leading-relaxed">
                            Suka menonton malam-malam? Amankan jadwal tayang bioskop di atas jam sembilan malam, dapatkan potongan harga langsung sebesar 5%.
                        </p>
                        <a href="#now-showing" class="inline-flex items-center text-xs font-bold text-white group-hover:text-violet transition">
                            Cek Jadwal Midnight <svg class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="partners" class="pt-16 pb-16 bg-slate-500">
        <div class="container mx-auto px-4 max-w-287.5">
            <div class="w-full px-4 mb-6 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-white text-3xl sm:text-4xl">Our Partners</h2>
                </div>
            </div>
            <div class="w-full px-4">
                <div class="flex flex-wrap items-center justify-evenly">
                    <a href="https://21cineplex.com/" target="_blank" rel="noopener noreferrer" class="max-w-30 mx-4 py-4 grayscale opacity-60 transition duration-500 hover:grayscale-0 hover:opacity-100 lg:mx-6 xl:mx-8">
                        <img src="dist/img/partners/XXI.svg" alt="XXI">
                    </a>
                    <a href="https://www.cgv.id/" target="_blank" rel="noopener noreferrer" class="max-w-30 mx-4 py-4 grayscale opacity-60 transition duration-500 hover:grayscale-0 hover:opacity-100 lg:mx-6 xl:mx-8">
                        <img src="dist/img/partners/CGV_logo.svg" alt="CGV">
                    </a>
                    <a href="https://cinepolis.co.id/" target="_blank" rel="noopener noreferrer" class="max-w-50 mx-4 py-4 grayscale opacity-60 transition duration-500 hover:grayscale-0 hover:opacity-100 lg:mx-6 xl:mx-8">
                        <img src="dist/img/partners/Cinepolis.svg" alt="Cinepolis">
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer class="pt-16 pb-8 bg-dark text-slate-400 border-t border-slate-900">
        <div class="container mx-auto px-4 max-w-287.5">
            <div class="flex flex-wrap -mx-4 mb-12 justify-between">
                <div class="w-full md:w-1/2 lg:w-4/12 px-4 mb-8 lg:mb-0">
                    <span class="text-2xl font-bold text-white tracking-wider">Flick <span class="text-violet">Book</span></span>
                    <p class="text-sm leading-relaxed max-w-sm mt-2">Solusi terbaik dan tercepat untuk memesan tiket bioskop favoritmu secara online. Tanpa antre, aman, dan terpercaya.</p>
                </div>
                <div class="w-1/2 md:w-1/4 lg:w-2/12 px-4 mb-8 lg:mb-0">
                    <h3 class="font-semibold text-base text-white mb-4 uppercase tracking-wider">Jelajahi</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#home" class="text-base inline-block hover:text-violet transition duration-200">Home</a></li>
                        <li><a href="#now-showing" class="text-base inline-block hover:text-violet transition duration-200">Now Showing</a></li>
                        <li><a href="#coming-soon" class="text-base inline-block hover:text-violet transition duration-200">Coming Soon</a></li>
                        <li><a href="#promo" class="text-base inline-block hover:text-violet transition duration-200">Promo</a></li>
                        <li><a href="#partners" class="text-base inline-block hover:text-violet transition duration-200">Partners</a></li>
                    </ul>
                </div>
                <div class="w-1/2 md:w-1/4 lg:w-2/12 px-4 mb-8 lg:mb-0">
                    <h3 class="font-semibold text-base text-white mb-4 uppercase tracking-wider">Bantuan</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="etc.php" class="text-base inline-block hover:text-violet transition duration-200">Pusat Bantuan</a></li>
                        <li><a href="etc.php" class="text-base inline-block hover:text-violet transition duration-200">Syarat & Ketentuan</a></li>
                        <li><a href="etc.php" class="text-base inline-block hover:text-violet transition duration-200">Kebijakan Privasi</a></li>
                        <li><a href="logout.php" class="text-base inline-block hover:text-violet transition duration-200">LOGOUT</a></li>
                    </ul>
                </div>
            </div>
            <hr class="border-slate-900 my-6">
            <div class="flex flex-wrap items-center justify-between dynamic-Footer-bottom">
                <div class="w-full text-center">
                    <p class="text-xs text-slate-500">&copy; 2026 FlickBook Cinema System.</p>
                </div>
            </div>
        </div>
    </footer
     
    <script src="./dist/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <script>
        // Inisialisasi Swiper Slider
        var swiper = new Swiper(".mySwiper", {
            loop: true,
            grabCursor: true,
            effect: "creative",
            speed: 800, 
            creativeEffect: {
                prev: { shadow: true, translate: ["-120%", 0, -500], scale: 0.8, blur: 15 },
                next: { translate: ["120%", 0, -500], scale: 0.8, blur: 15 },
            },
            autoplay: { delay: 10000, disableOnInteraction: false },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '"><span class="progress-bar-fill"></span></span>';
                },
            },
        });

        // Tetap menggunakan base URL bawaan dari modifikasi temanmu
        const API_BASE_URL = "<?php echo getenv('API_BASE_URL'); ?>";

        // State global untuk data pagination coming soon
        let globalComingSoonMovies = [];
        let comingSoonCurrentLimit = 4; // Mengatur limit awal default 4 film

        // Fungsi Pembantu Rating Class
        function getRatingClass(ratingString) {
            return "bg-violet text-white shadow-lg shadow-violet/40 font-black border border-violet-400/20";
        }

        // Ambil Data Film dari API
        async function loadMoviesData() {
            try {
                const response = await fetch(`${API_BASE_URL}/movies.php`);
                const result = await response.json();

                if (result.status === "success" && Array.isArray(result.data)) {
                    const allMovies = result.data;
                    const nowShowingMovies = allMovies.filter(movie => movie.status_tayang === 'now_playing');
                    
                    // Simpan data upcoming di variabel global agar bisa dipagination
                    globalComingSoonMovies = allMovies.filter(movie => movie.status_tayang === 'upcoming');

                    renderNowShowing(nowShowingMovies);
                    
                    // Jalankan fungsi pagination coming soon
                    comingSoonCurrentLimit = 4;
                    renderComingSoonPagination();
                } else {
                    console.error("Gagal memuat data film dari API:", result.message);
                }
            } catch (error) {
                console.error("Terjadi kesalahan koneksi ke server API:", error);
            }
        }

        // Render Film Now Showing
        function renderNowShowing(movies) {
            const container = document.getElementById("now-showing-container");
            container.innerHTML = ""; 

            if (movies.length === 0) {
                container.innerHTML = `<div class="w-full text-center py-8 text-slate-500">Belum ada film yang sedang tayang saat ini.</div>`;
                return;
            }

            movies.forEach(movie => {
                const rawRating = movie.rating_umur || movie.Rating_umur || movie.Rating_Umur || movie.rating || "SU";
                const colorClass = getRatingClass(rawRating);

                const cardHTML = `
                <div class="px-4 w-1/2 lg:w-1/4 mb-8 group">
                    <a href="detail.php?movie_id=${movie.movie_id}" class="block bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden transition duration-300 hover:-translate-y-2 hover:shadow-xl flex flex-col h-full relative">
                        <div class="relative aspect-2/3 overflow-hidden bg-dark">
                            <span class="absolute top-3 left-3 ${colorClass} text-xs font-bold px-2.5 py-1 rounded-md z-30 uppercase tracking-wide shadow-md">${rawRating}</span>
                            <img src="${movie.poster_url || 'https://images.unsplash.com/photo-1635805737707-575885ab0820?auto=format&fit=crop&w=600&q=80'}" alt="${movie.judul}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105 pointer-events-none">
                            <div class="absolute bottom-0 inset-x-0 bg-white/80 backdrop-blur-sm p-4 text-center transition duration-300 group-hover:opacity-0 group-hover:pointer-events-none z-10 border-t border-slate-200">
                                <h3 class="font-bold text-sm text-slate-800 line-clamp-1">${movie.judul}</h3>
                            </div>
                            <div class="absolute inset-0 bg-black/80 opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col items-center justify-center text-center p-5 z-20">
                                <span class="text-xs text-violet font-medium mb-2 tracking-wide uppercase">${movie.genre}</span>
                                <h3 class="font-bold text-base text-white line-clamp-3 px-2">${movie.judul}</h3>
                                <span class="mt-4 inline-block bg-violet text-white text-xs font-semibold px-4 py-1.5 rounded-full shadow-md">Beli Tiket</span>
                            </div>
                        </div>
                    </a>
                </div>`;
                container.innerHTML += cardHTML;
            });
        }

        // Render Film Coming Soon Dengan Sistem Pagination Slice
        function renderComingSoonPagination() {
            const container = document.getElementById("coming-soon-container");
            const viewMoreBtn = document.getElementById("view-more-btn");
            container.innerHTML = ""; 

            if (globalComingSoonMovies.length === 0) {
                container.innerHTML = `<div class="w-full text-center py-8 text-slate-400">Belum ada daftar film yang akan datang.</div>`;
                viewMoreBtn.classList.add("hidden");
                return;
            }

            // Memotong array data film dari indeks 0 sampai batas limit saat ini
            const slicedMovies = globalComingSoonMovies.slice(0, comingSoonCurrentLimit);

            slicedMovies.forEach(movie => {
                const rating = movie.rating_umur || "SU";
                const colorClass = getRatingClass(rating);

                const cardHTML = `
                <div class="px-4 w-1/2 lg:w-1/4 mb-8 group">
                    <a href="detail.php?movie_id=${movie.movie_id}" class="flex flex-col h-full">
                        <div class="relative aspect-2/3 rounded-xl overflow-hidden bg-slate-900 shadow-lg">
                            <span class="absolute top-3 left-3 ${colorClass} text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wide shadow-md z-10">${rating}</span>
                            <img src="${movie.poster_url || 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?auto=format&fit=crop&w=600&q=80'}" alt="${movie.judul}" class="w-full h-full object-cover transition duration-300 group-hover:opacity-80 pointer-events-none">
                            <div class="absolute bottom-0 inset-x-0 bg-black py-2.5 text-center border-t border-slate-900">
                                <span class="text-white text-xs font-bold uppercase tracking-widest">Coming Soon</span>
                            </div>
                        </div>
                        <div class="mt-4 px-1">
                            <h3 class="font-bold text-base text-white transition duration-300 group-hover:text-violet line-clamp-1">${movie.judul}</h3>
                            <p class="text-xs text-slate-400 mt-1 font-medium">${movie.genre}</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">Durasi: ${movie.durasi} Menit</p>
                        </div>
                    </a>
                </div>`;
                container.innerHTML += cardHTML;
            });

            // Tampilkan tombol View More jika jumlah seluruh film lebih besar dari limit saat ini
            if (globalComingSoonMovies.length > comingSoonCurrentLimit) {
                viewMoreBtn.classList.remove("hidden");
            } else {
                viewMoreBtn.classList.add("hidden");
            }
        }

        // Aksi ketika tombol View More ditekan (pagination menambahkan 4 item berikutnya)
        function loadNextComingSoonPage() {
            comingSoonCurrentLimit += 4;
            renderComingSoonPagination();
        }

        // Toggle profile dropdown
        function toggleProfileDropdown() {
            const dd = document.getElementById('profile-dropdown');
            dd.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('profile-wrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                document.getElementById('profile-dropdown').classList.add('hidden');
            }
        });

        // Validasi Sesi Pengguna & Hak Akses Admin
        function checkUserSession() {
            const userId = sessionStorage.getItem("user_id");
            const userRole = sessionStorage.getItem("user_role");
            const userName = sessionStorage.getItem("user_name") || "User";

            if (!userId) {
                window.location.href = "login.php";
                return;
            }

            // Populate profile avatar initial
            const initial = userName.charAt(0).toUpperCase();
            const profileWrapper = document.getElementById('profile-wrapper');
            profileWrapper.classList.remove('hidden');
            profileWrapper.classList.add('flex');

            document.getElementById('profile-avatar').innerText = initial;
            document.getElementById('dropdown-name').innerText = userName;
            document.getElementById('dropdown-avatar').innerText = initial;

            // Role badge color
            const roleEl = document.getElementById('dropdown-role');
            roleEl.innerText = userRole || 'user';
            
            // Remove existing admin link if any (to prevent duplicates)
            const existingAdminLink = document.getElementById('admin-dashboard-link');
            if (existingAdminLink) {
                existingAdminLink.remove();
            }

            if (userRole === 'admin') {
                roleEl.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-violet/15 text-violet uppercase tracking-wide';
                // Dynamically insert Dashboard Admin link
                const dropdownMenu = document.querySelector('#profile-dropdown .py-1');
                if (dropdownMenu) {
                    const adminLink = document.createElement('a');
                    adminLink.id = 'admin-dashboard-link';
                    adminLink.href = 'admin.php';
                    adminLink.className = 'dropdown-item flex';
                    adminLink.style.color = '#1e1b4b';
                    adminLink.onmouseover = function() {
                        this.style.background = '#f5f3ff';
                        this.style.color = '#7C3AED';
                    };
                    adminLink.onmouseout = function() {
                        this.style.background = '';
                        this.style.color = '#1e1b4b';
                    };
                    adminLink.innerHTML = `
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                        Dashboard Admin
                    `;
                    dropdownMenu.insertBefore(adminLink, dropdownMenu.firstChild);
                }
            } else {
                roleEl.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-600 uppercase tracking-wide';
            }
        }

        // ─── NAVBAR SCROLL EFFECT ───
        function handleNavbarScroll() {
            const header = document.getElementById('main-header');
            if (window.scrollY > 60) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }

        window.addEventListener('scroll', handleNavbarScroll);

        // FIX UTAMA: Jalankan fungsi otomatis saat halaman selesai dimuat (DOM Ready)
        document.addEventListener("DOMContentLoaded", () => {
            checkUserSession();
            loadMoviesData();
            handleNavbarScroll();
        });
    </script>
</body>
</html>