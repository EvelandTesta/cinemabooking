<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../config/response.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        // Kondisi 1: Jika mencari showtime_id spesifik (Untuk kebutuhan select_seat.php / payment.php)
        if(isset($_GET['showtime_id'])) {
            $stmt = $db->prepare("
                SELECT 
                    s.showtime_id, s.movie_id, s.studio_id,
                    s.jam AS jam, s.jam AS Jam, 
                    s.tanggal AS tanggal, s.tanggal AS Tanggal, 
                    s.harga_tiket AS harga_tiket, s.harga_tiket AS Harga_tiket,
                    m.judul, m.genre, st.nama_studio, c.nama_bioskop 
                FROM showtimes s
                LEFT JOIN movies m ON s.movie_id = m.movie_id
                LEFT JOIN studios st ON s.studio_id = st.studio_id
                LEFT JOIN cinemas c ON st.cinema_id = c.cinema_id
                WHERE s.showtime_id = :showtime_id
            ");
            $stmt->bindParam(':showtime_id', $_GET['showtime_id']);
            $stmt->execute();
            $showtime = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($showtime) {
                Response::success("Showtime found", $showtime);
            } else {
                Response::error("Showtime not found", 404);
            }
        } 
        
        // Kondisi 2: Jika mencari daftar jadwal dinamis berdasarkan movie_id (Untuk detail.php TIX ID style)
        elseif(isset($_GET['movie_id'])) {
            $stmt = $db->prepare("
                SELECT 
                    st.showtime_id, st.movie_id, st.studio_id,
                    st.jam AS jam, st.jam AS Jam,
                    st.tanggal AS tanggal, st.tanggal AS Tanggal,
                    st.harga_tiket AS harga_tiket, st.harga_tiket AS Harga_tiket,
                    s.nama_studio, c.nama_bioskop,
                    'Reguler' AS tipe_bioskop
                FROM showtimes st
                JOIN studios s ON st.studio_id = s.studio_id
                JOIN cinemas c ON s.cinema_id = c.cinema_id
                WHERE st.movie_id = :movie_id
                ORDER BY c.nama_bioskop ASC, st.jam ASC
            ");
            $stmt->bindParam(':movie_id', $_GET['movie_id']);
            $stmt->execute();
            $showtimes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            Response::success("Showtimes for movie retrieved", $showtimes);
        }
        
        // Kondisi 3: Default jika tidak ada parameter sama sekali (Mengambil semua jadwal)
        else {
            $stmt = $db->prepare("
                SELECT 
                    s.showtime_id, s.movie_id, s.studio_id,
                    s.jam AS jam, s.jam AS Jam,
                    s.tanggal AS tanggal, s.tanggal AS Tanggal,
                    s.harga_tiket AS harga_tiket, s.harga_tiket AS Harga_tiket,
                    m.judul, st.nama_studio, c.nama_bioskop 
                FROM showtimes s
                LEFT JOIN movies m ON s.movie_id = m.movie_id
                LEFT JOIN studios st ON s.studio_id = st.studio_id
                LEFT JOIN cinemas c ON st.cinema_id = c.cinema_id
                ORDER BY s.tanggal ASC, s.jam ASC
            ");
            $stmt->execute();
            $showtimes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            Response::success("All showtimes retrieved", $showtimes);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        // Ambil data dengan fallback huruf besar/kecil agar aman dari error input
        $inp_movie_id = !empty($data->movie_id) ? $data->movie_id : null;
        $inp_studio_id = !empty($data->studio_id) ? $data->studio_id : null;
        $inp_jam = !empty($data->Jam) ? $data->Jam : (!empty($data->jam) ? $data->jam : null);
        $inp_tanggal = !empty($data->Tanggal) ? $data->Tanggal : (!empty($data->tanggal) ? $data->tanggal : null);
        $inp_harga = !empty($data->Harga_tiket) ? $data->Harga_tiket : (!empty($data->harga_tiket) ? $data->harga_tiket : 0);

        if(!empty($inp_movie_id) && !empty($inp_studio_id) && !empty($inp_jam) && !empty($inp_tanggal)) {
            $query = "INSERT INTO showtimes (movie_id, studio_id, jam, tanggal, harga_tiket) 
                      VALUES (:movie_id, :studio_id, :jam, :tanggal, :harga_tiket)";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':movie_id', $inp_movie_id);
            $stmt->bindParam(':studio_id', $inp_studio_id);
            $stmt->bindParam(':jam', $inp_jam);
            $stmt->bindParam(':tanggal', $inp_tanggal);
            $stmt->bindParam(':harga_tiket', $inp_harga);
            
            if($stmt->execute()) {
                $showtime_id = $db->lastInsertId();
                
                // Otomatis meng-generate ketersediaan kursi di tabel seat_availability
                $stmtSeats = $db->prepare("SELECT seat_id FROM seats WHERE studio_id = :studio_id");
                $stmtSeats->bindParam(':studio_id', $inp_studio_id);
                $stmtSeats->execute();
                $seats = $stmtSeats->fetchAll(PDO::FETCH_ASSOC);
                
                foreach($seats as $seat) {
                    $stmtAvail = $db->prepare("
                        INSERT INTO seat_availability (showtime_id, seat_id, status_kursi, updated_at) 
                        VALUES (:showtime_id, :seat_id, 'available', NOW())
                    ");
                    $stmtAvail->bindParam(':showtime_id', $showtime_id);
                    $stmtAvail->bindParam(':seat_id', $seat['seat_id']);
                    $stmtAvail->execute();
                }
                
                Response::success("Showtime created successfully", ["showtime_id" => $showtime_id]);
            } else {
                Response::error("Failed to create showtime", 500);
            }
        } else {
            Response::error("Incomplete data", 400);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        $inp_jam = !empty($data->Jam) ? $data->Jam : (!empty($data->jam) ? $data->jam : null);
        $inp_tanggal = !empty($data->Tanggal) ? $data->Tanggal : (!empty($data->tanggal) ? $data->tanggal : null);
        $inp_harga = !empty($data->Harga_tiket) ? $data->Harga_tiket : (!empty($data->harga_tiket) ? $data->harga_tiket : 0);

        if(!empty($data->showtime_id)) {
            $query = "UPDATE showtimes SET 
                      movie_id = :movie_id, 
                      studio_id = :studio_id,
                      jam = :jam,
                      tanggal = :tanggal,
                      harga_tiket = :harga_tiket
                      WHERE showtime_id = :showtime_id";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':showtime_id', $data->showtime_id);
            $stmt->bindParam(':movie_id', $data->movie_id);
            $stmt->bindParam(':studio_id', $data->studio_id);
            $stmt->bindParam(':jam', $inp_jam);
            $stmt->bindParam(':tanggal', $inp_tanggal);
            $stmt->bindParam(':harga_tiket', $inp_harga);
            
            if($stmt->execute()) {
                Response::success("Showtime updated successfully", ["showtime_id" => $data->showtime_id]);
            } else {
                Response::error("Failed to update showtime", 500);
            }
        } else {
            Response::error("Showtime ID required", 400);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->showtime_id)) {
            $query = "DELETE FROM showtimes WHERE showtime_id = :showtime_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':showtime_id', $data->showtime_id);
            
            if($stmt->execute()) {
                Response::success("Showtime deleted successfully", ["showtime_id" => $data->showtime_id]);
            } else {
                Response::error("Failed to delete showtime", 500);
            }
        } else {
            Response::error("Showtime ID required", 400);
        }
        break;

    default:
        Response::error("Method not allowed", 405);
        break;
}
?>