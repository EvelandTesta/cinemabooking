<?php include 'env.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets - FlickBook</title>
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
    <style>
        /* Responsive Grid for ticket cards */
        .ticket-grid {
            display: grid !important;
            grid-template-columns: 1fr !important;
            gap: 32px !important;
        }

        @media (min-width: 768px) {
            .ticket-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }

        @media (min-width: 1200px) {
            .ticket-grid {
                grid-template-columns: repeat(3, 1fr) !important;
            }
        }

        /* Ticket Card Styling */
        .ticket-card {
            background: #ffffff !important;
            border-radius: 24px !important;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
            overflow: hidden !important;
            transition: transform 0.3s ease, box-shadow 0.3s ease !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .ticket-card:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08) !important;
        }

        /* Button Styling */
        .ticket-btn {
            display: block !important;
            width: 100% !important;
            text-align: center !important;
            background: #7C3AED !important; /* violet */
            color: #ffffff !important;
            padding: 14px 20px !important;
            border-radius: 16px !important;
            font-weight: 700 !important;
            text-decoration: none !important;
            transition: background 0.2s ease, transform 0.1s ease !important;
            border: none !important;
            cursor: pointer !important;
        }

        .ticket-btn:hover {
            background: #6D28D9 !important; /* darker violet */
        }
        
        .ticket-btn:active {
            transform: scale(0.98) !important;
        }

        /* Badges */
        .paid-badge {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            padding: 4px 12px !important;
            border-radius: 9999px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            display: inline-block !important;
        }
    </style>
</head>

<body class="bg-primary text-slate-800">

<header class="bg-white shadow-sm border-b border-slate-200 sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
        <a href="index.php" class="text-2xl font-bold text-slate-900 tracking-wider">
            Flick<span class="text-violet">Book</span>
        </a>
        <div class="flex items-center gap-3">
            <a id="admin-dashboard-btn" href="admin.php"
               class="hidden bg-slate-800 hover:bg-slate-700 text-white px-5 py-2 rounded-xl font-bold text-sm transition tracking-wide">
                Dashboard Admin
            </a>
            <a href="index.php" class="bg-violet hover:bg-violet-600 text-white px-5 py-2 rounded-xl font-bold text-sm transition tracking-wide">
                Kembali ke Home
            </a>
        </div>
    </div>
</header>

<main class="container mx-auto px-4 max-w-7xl py-12">

    <!-- UPCOMING -->
    <section class="mb-20">
        <div class="mb-10">
            <h1 class="text-4xl font-black text-slate-900">
                My Tickets
            </h1>

            <p class="text-slate-500 mt-2">
                Tiket yang telah dibeli dan belum lewat jadwal tayangnya.
            </p>
        </div>

        <div id="upcoming-container" class="ticket-grid">

        </div>
    </section>

    <!-- HISTORY -->
    <section>

        <div class="mb-10">
            <h1 class="text-4xl font-black text-slate-900">
                Ticket History
            </h1>

            <p class="text-slate-500 mt-2">
                Riwayat film yang pernah ditonton.
            </p>
        </div>

        <div id="history-container" class="ticket-grid">

        </div>

    </section>

</main>


<script>

const API_BASE_URL = "<?php echo getenv('API_BASE_URL'); ?>";

function checkUserSession() {

    const userId = sessionStorage.getItem("user_id");

    if (!userId) {
        window.location.href = "login.php";
        return;
    }

    // Check if admin to show dashboard button
    const userRole = sessionStorage.getItem("user_role");
    const adminBtn = document.getElementById("admin-dashboard-btn");
    if (userRole === "admin" && adminBtn) {
        adminBtn.classList.remove("hidden");
    }

}

async function loadTickets() {

    const userId = sessionStorage.getItem("user_id");

    try {

        const response = await fetch(
            `${API_BASE_URL}/bookings.php?user_id=${userId}`
        );

        const result = await response.json();

        if (result.status !== "success") {
            return;
        }

        renderTickets(result.data);

    }

    catch(error) {

        console.error(error);

    }

}

function renderTickets(bookings) {

    const upcomingContainer =
        document.getElementById("upcoming-container");

    const historyContainer =
        document.getElementById("history-container");

    upcomingContainer.innerHTML = "";
    historyContainer.innerHTML = "";

    const now = new Date();

    bookings.forEach(ticket => {

        if(ticket.status_pembayaran !== "paid")
            return;

        const tanggal = ticket.Tanggal;
        const jam = ticket.Jam;

        const showDate =
            new Date(`${tanggal} ${jam}`);

        const diffMs = showDate - now;

        let reminderText = "";
        let reminderColor = "text-emerald-600";

        if(diffMs > 0){

            const diffMinutes =
                Math.floor(diffMs / 1000 / 60);

            if(diffMinutes <= 30){

                reminderText =
                    "🚨 Film segera dimulai!";

                reminderColor =
                    "text-red-600";

            }

            else if(diffMinutes <= 120){

                const hour =
                    Math.floor(diffMinutes / 60);

                const minute =
                    diffMinutes % 60;

                reminderText =
                    `⚠ Film dimulai dalam ${hour} jam ${minute} menit`;

                reminderColor =
                    "text-yellow-600";

            }

            else{

                reminderText =
                    "Upcoming";

            }

        }

        const card = `
        <div class="ticket-card">
            <div class="p-7">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="font-black text-xl text-slate-900 leading-snug">
                            ${ticket.movie_title}
                        </h2>
                        <p class="text-slate-500 mt-1 text-sm">
                            ${ticket.nama_bioskop}
                        </p>
                    </div>
                    <span class="paid-badge">
                        PAID
                    </span>
                </div>

                <div class="mt-6 space-y-3 text-sm">
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-slate-400">Studio</span>
                        <span class="font-bold text-slate-800">${ticket.nama_studio}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-slate-400">Tanggal</span>
                        <span class="font-bold text-slate-800">${ticket.Tanggal}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-slate-400">Jam</span>
                        <span class="font-bold text-slate-800">${ticket.Jam.substring(0,5)}</span>
                    </div>
                    <div class="flex justify-between pt-1">
                        <span class="text-slate-400">Total Bayar</span>
                        <span class="font-black text-violet text-base">
                            Rp ${parseInt(ticket.Total_bayar).toLocaleString('id-ID')}
                        </span>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <span class="${reminderColor} text-xs font-bold bg-slate-100 px-3 py-1 rounded-md">
                        ${reminderText}
                    </span>
                </div>

                <div class="mt-6">
                    <a href="ticket.php?booking_id=${ticket.booking_id}" class="ticket-btn">
                        Lihat Tiket
                    </a>
                </div>
            </div>
        </div>
        `;

        if(showDate > now){

            upcomingContainer.innerHTML += card;

        }

        else{

            historyContainer.innerHTML += card;

        }

    });

    if(upcomingContainer.innerHTML === ""){

        upcomingContainer.innerHTML = `
        <div class="col-span-full text-center py-20">

            <h2 class="font-bold text-2xl text-slate-400">
                Belum ada tiket aktif
            </h2>

        </div>
        `;

    }

    if(historyContainer.innerHTML === ""){

        historyContainer.innerHTML = `
        <div class="col-span-full text-center py-20">

            <h2 class="font-bold text-2xl text-slate-400">
                Belum ada riwayat film
            </h2>

        </div>
        `;

    }

}

document.addEventListener("DOMContentLoaded", () => {

    checkUserSession();

    loadTickets();

});

</script>

</body>
</html>

