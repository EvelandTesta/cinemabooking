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
        if(isset($_GET['payment_id'])) {
            $stmt = $db->prepare("
                SELECT p.*, b.booking_id, u.nama as user_name, u.email
                FROM payments p
                LEFT JOIN bookings b ON p.booking_id = b.booking_id
                LEFT JOIN users u ON b.user_id = u.user_id
                WHERE p.payment_id = :payment_id
            ");
            $stmt->bindParam(':payment_id', $_GET['payment_id']);
            $stmt->execute();
            $payment = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($payment) {
                Response::success("Payment found", $payment);
            } else {
                Response::error("Payment not found", 404);
            }
        } 
        
        elseif(isset($_GET['booking_id'])) {
            $stmt = $db->prepare("
                SELECT p.*, u.nama as user_name, u.email,
                       m.judul as movie_title, s.Jam, s.Tanggal
                FROM payments p
                LEFT JOIN bookings b ON p.booking_id = b.booking_id
                LEFT JOIN users u ON b.user_id = u.user_id
                LEFT JOIN showtimes s ON b.showtime_id = s.showtime_id
                LEFT JOIN movies m ON s.movie_id = m.movie_id
                WHERE p.booking_id = :booking_id
            ");
            $stmt->bindParam(':booking_id', $_GET['booking_id']);
            $stmt->execute();
            $payment = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($payment) {
                Response::success("Payment found", $payment);
            } else {
                Response::error("Payment not found", 404);
            }
        }


        elseif(isset($_GET['status_pembayaran'])) {
            $stmt = $db->prepare("
                SELECT p.*, b.booking_id, u.nama as user_name, u.email, u.no_hp,
                       m.judul as movie_title, s.Jam, s.Tanggal
                FROM payments p
                LEFT JOIN bookings b ON p.booking_id = b.booking_id
                LEFT JOIN users u ON b.user_id = u.user_id
                LEFT JOIN showtimes s ON b.showtime_id = s.showtime_id
                LEFT JOIN movies m ON s.movie_id = m.movie_id
                WHERE p.status_pembayaran = :status
                ORDER BY p.tanggal_bayar DESC
            ");
            $stmt->bindParam(':status', $_GET['status_pembayaran']);
            $stmt->execute();
            $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            Response::success("Payments with status: " . $_GET['status_pembayaran'], $payments);
        }
        
        else {
            $stmt = $db->query("
                SELECT p.*, b.booking_id, u.nama as user_name
                FROM payments p
                LEFT JOIN bookings b ON p.booking_id = b.booking_id
                LEFT JOIN users u ON b.user_id = u.user_id
                ORDER BY p.tanggal_bayar DESC
            ");
            $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            Response::success("Payments retrieved", $payments);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->booking_id) && !empty($data->metode_pembayaran) && !empty($data->Total_bayar)) {
            $query = "INSERT INTO payments (booking_id, metode_pembayaran, Total_bayar, status_pembayaran, tanggal_bayar) 
                      VALUES (:booking_id, :metode_pembayaran, :Total_bayar, 'paid', NOW())";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':booking_id', $data->booking_id);
            $stmt->bindParam(':metode_pembayaran', $data->metode_pembayaran);
            $stmt->bindParam(':Total_bayar', $data->Total_bayar);
            
            if($stmt->execute()) {
                // Update booking status
                $stmtBooking = $db->prepare("UPDATE bookings SET status_booking = 'confirmed' WHERE booking_id = :booking_id");
                $stmtBooking->bindParam(':booking_id', $data->booking_id);
                $stmtBooking->execute();
                
                Response::success("Payment processed successfully", ["payment_id" => $db->lastInsertId()]);
            } else {
                Response::error("Failed to process payment", 500);
            }
        } else {
            Response::error("Incomplete data", 400);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->payment_id)) {
            $query = "UPDATE payments SET 
                      status_pembayaran = :status_pembayaran,
                      tanggal_bayar = NOW()
                      WHERE payment_id = :payment_id";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':payment_id', $data->payment_id);
            $stmt->bindParam(':status_pembayaran', $data->status_pembayaran);
            
            if($stmt->execute()) {
                // Get booking_id
                $stmtBooking = $db->prepare("SELECT booking_id FROM payments WHERE payment_id = :payment_id");
                $stmtBooking->bindParam(':payment_id', $data->payment_id);
                $stmtBooking->execute();
                $payment = $stmtBooking->fetch(PDO::FETCH_ASSOC);
                
                // Update booking status
                $status_booking = ($data->status_pembayaran == 'paid') ? 'confirmed' : 'cancelled';
                $stmtUpdateBooking = $db->prepare("UPDATE bookings SET status_booking = :status WHERE booking_id = :booking_id");
                $stmtUpdateBooking->bindParam(':status', $status_booking);
                $stmtUpdateBooking->bindParam(':booking_id', $payment['booking_id']);
                $stmtUpdateBooking->execute();
                
                Response::success("Payment updated successfully", ["payment_id" => $data->payment_id, "status" => $data->status_pembayaran]);
            } else {
                Response::error("Failed to update payment", 500);
            }
        } else {
            Response::error("Payment ID required", 400);
        }
        break;

    default:
        Response::error("Method not allowed", 405);
        break;
}
?>