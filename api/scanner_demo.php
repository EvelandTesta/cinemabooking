<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Scanner Tiket - FlickBook</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-96 text-center">
        <h2 class="text-xl font-bold mb-6 text-slate-800">Scanner Petugas</h2>
        
        <input type="text" id="kode" 
               class="w-full p-3 border border-slate-300 rounded-lg mb-4 focus:ring-2 focus:ring-violet outline-none" 
               placeholder="Masukkan Kode Tiket...">
        
        <button onclick="scanTiket()" 
                class="w-full bg-violet text-white font-bold py-3 rounded-lg hover:bg-violet/90 transition">
            SCAN TIKET
        </button>

        <div id="result" class="mt-6 p-4 rounded-lg font-bold text-sm hidden"></div>
    </div>

<script>
async function scanTiket() {
    const kode = document.getElementById('kode').value;
    const resultDiv = document.getElementById('result');
    
    try {
        const res = await fetch('scan.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ kode_tiket: kode })
        });
        
        const message = await res.text();
        
        resultDiv.innerText = message;
        resultDiv.classList.remove('hidden', 'bg-green-100', 'text-green-700', 'bg-red-100', 'text-red-700');
        
        if (message.includes("BERHASIL")) {
            resultDiv.classList.add('bg-green-100', 'text-green-700');
        } else {
            resultDiv.classList.add('bg-red-100', 'text-red-700');
        }
    } catch (err) {
        resultDiv.innerText = "Error koneksi ke server!";
        resultDiv.classList.add('bg-red-100', 'text-red-700');
    }
}
</script>
</body>
</html>