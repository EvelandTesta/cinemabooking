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
        if(isset($_GET['booking_id'])) {
            $stmt = $db->prepare("
                SELECT b.*, u.nama as user_name, u.email, u.no_hp,
                       s.Jam, s.Tanggal, s.Harga_tiket,
                       m.judul as movie_title,
                       st.nama_studio,
                       c.nama_bioskop
                FROM bookings b
                LEFT JOIN users u ON b.user_id = u.user_id
                LEFT JOIN showtimes s ON b.showtime_id = s.showtime_id
                LEFT JOIN movies m ON s.movie_id = m.movie_id
                LEFT JOIN studios st ON s.studio_id = st.studio_id
                LEFT JOIN cinemas c ON st.cinema_id = c.cinema_id
                WHERE b.booking_id = :booking_id
            ");
            $stmt->bindParam(':booking_id', $_GET['booking_id']);
            $stmt->execute();
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($booking) {
                // Get tickets booking
                $stmtTickets = $db->prepare("
                    SELECT t.*, se.baris, se.nomor_kursi
                    FROM tickets t
                    LEFT JOIN seats se ON t.seat_id = se.seat_id
                    WHERE t.booking_id = :booking_id
                ");
                $stmtTickets->bindParam(':booking_id', $_GET['booking_id']);
                $stmtTickets->execute();
                $booking['tickets'] = $stmtTickets->fetchAll(PDO::FETCH_ASSOC);
                
                // Get payment info
                $stmtPayment = $db->prepare("SELECT * FROM payments WHERE booking_id = :booking_id");
                $stmtPayment->bindParam(':booking_id', $_GET['booking_id']);
                $stmtPayment->execute();
                $booking['payment'] = $stmtPayment->fetch(PDO::FETCH_ASSOC);
                
                Response::success("Booking found", $booking);
            } else {
                Response::error("Booking not found", 404);
            }
        } 
        
        elseif(isset($_GET['start_date']) && isset($_GET['end_date'])) {
            $stmt = $db->prepare("
                SELECT b.*, u.nama as user_name, m.judul as movie_title,
                       s.Jam, s.Tanggal, p.Total_bayar, p.status_pembayaran
                FROM bookings b
                LEFT JOIN users u ON b.user_id = u.user_id
                LEFT JOIN showtimes s ON b.showtime_id = s.showtime_id
                LEFT JOIN movies m ON s.movie_id = m.movie_id
                LEFT JOIN payments p ON b.booking_id = p.booking_id
                WHERE DATE(b.tanggal_booking) BETWEEN :start_date AND :end_date
                ORDER BY b.tanggal_booking DESC
            ");
            $stmt->bindParam(':start_date', $_GET['start_date']);
            $stmt->bindParam(':end_date', $_GET['end_date']);
            $stmt->execute();
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            Response::success("Bookings in date range", $bookings);
        }

        elseif(isset($_GET['user_id'])) {
            $stmt = $db->prepare("
                SELECT b.*, m.judul as movie_title, s.Jam, s.Tanggal, st.nama_studio,
                       c.nama_bioskop, p.status_pembayaran, p.Total_bayar
                FROM bookings b
                LEFT JOIN showtimes s ON b.showtime_id = s.showtime_id
                LEFT JOIN movies m ON s.movie_id = m.movie_id
                LEFT JOIN studios st ON s.studio_id = st.studio_id
                LEFT JOIN cinemas c ON st.cinema_id = c.cinema_id
                LEFT JOIN payments p ON b.booking_id = p.booking_id
                WHERE b.user_id = :user_id
                ORDER BY b.tanggal_booking DESC
            ");
            $stmt->bindParam(':user_id', $_GET['user_id']);
            $stmt->execute();
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            Response::success("Bookings for user ID " . $_GET['user_id'], $bookings);
        }
        
        
        else {
            $stmt = $db->query("
                SELECT b.*, u.nama as user_name, m.judul as movie_title
                FROM bookings b
                LEFT JOIN users u ON b.user_id = u.user_id
                LEFT JOIN showtimes s ON b.showtime_id = s.showtime_id
                LEFT JOIN movies m ON s.movie_id = m.movie_id
                ORDER BY b.tanggal_booking DESC
            ");
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            Response::success("Bookings retrieved", $bookings);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->user_id) && !empty($data->showtime_id) && !empty($data->seats)) {
            try {
                $db->beginTransaction();
                
                // 1. Buat transaksi booking induk
                $query = "INSERT INTO bookings (user_id, showtime_id, tanggal_booking, status_booking) 
                          VALUES (:user_id, :showtime_id, NOW(), 'pending')";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':user_id', $data->user_id);
                $stmt->bindParam(':showtime_id', $data->showtime_id);
                $stmt->execute();
                
                $booking_id = $db->lastInsertId();
                
                // 2. Ambil harga tiket tunggal dari jadwal tayang
                $stmtPrice = $db->prepare("SELECT Harga_tiket FROM showtimes WHERE showtime_id = :showtime_id");
                $stmtPrice->bindParam(':showtime_id', $data->showtime_id);
                $stmtPrice->execute();
                $showtime = $stmtPrice->fetch(PDO::FETCH_ASSOC);
                
                // Perhitungan total harga asli (Harga tiket * Jumlah elemen array kursi)
                $total_harga = intval($showtime['Harga_tiket']) * count($data->seats);
                
                // 3. Masukkan manifes tiket per kursi & kunci status sementara di denah
                foreach($data->seats as $seat_id) {
                    $kode_tiket = 'TKT' . time() . rand(1000, 9999);
                    $stmtTicket = $db->prepare("
                        INSERT INTO tickets (booking_id, seat_id, showtime_id, kode_tiket) 
                        VALUES (:booking_id, :seat_id, :showtime_id, :kode_tiket)
                    ");
                    $stmtTicket->bindParam(':booking_id', $booking_id);
                    $stmtTicket->bindParam(':seat_id', $seat_id);
                    $stmtTicket->bindParam(':showtime_id', $data->showtime_id);
                    $stmtTicket->bindParam(':kode_tiket', $kode_tiket);
                    $stmtTicket->execute();
                    
                    $stmtAvail = $db->prepare("
                        UPDATE seat_availability 
                        SET status_kursi = 'booked', updated_at = NOW()
                        WHERE showtime_id = :showtime_id AND seat_id = :seat_id
                    ");
                    $stmtAvail->bindParam(':showtime_id', $data->showtime_id);
                    $stmtAvail->bindParam(':seat_id', $seat_id);
                    $stmtAvail->execute();
                }
                
                // 4. Catat tagihan pembayaran dengan variabel yang benar ($total_harga)
                $stmtPayment = $db->prepare("
                    INSERT INTO payments (booking_id, Total_bayar, status_pembayaran, tanggal_bayar) 
                    VALUES (:booking_id, :total_bayar, 'pending', NULL)
                ");
                $stmtPayment->bindParam(':booking_id', $booking_id);
                $stmtPayment->bindParam(':total_bayar', $total_harga); 
                $stmtPayment->execute();
                
                $db->commit();
                
                Response::success("Booking created successfully", [
                    "booking_id" => $booking_id,
                    "total_bayar" => $total_harga
                ]);
                
            } catch(Exception $e) {
                $db->rollBack();
                Response::error("Failed to create booking: " . $e->getMessage(), 500);
            }
        } else {
            Response::error("Incomplete data", 400);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->booking_id)) {
            $query = "UPDATE bookings SET 
                      status_booking = :status_booking
                      WHERE booking_id = :booking_id";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':booking_id', $data->booking_id);
            $stmt->bindParam(':status_booking', $data->status_booking);
            
            if($stmt->execute()) {
                Response::success("Booking updated successfully", ["booking_id" => $data->booking_id, "new_status" => $data->status_booking]);
            } else {
                Response::error("Failed to update booking", 500);
            }
        } else {
            Response::error("Booking ID required", 400);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->booking_id)) {
            $query = "DELETE FROM bookings WHERE booking_id = :booking_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':booking_id', $data->booking_id);
            
            if($stmt->execute()) {
                Response::success("Booking deleted successfully", ["booking_id" => $data->booking_id]);
            } else {
                Response::error("Failed to delete booking", 500);
            }
        } else {
            Response::error("Booking ID required", 400);
        }
        break;

    default:
        Response::error("Method not allowed", 405);
        break;
}
?>