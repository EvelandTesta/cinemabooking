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
        if(isset($_GET['seat_id'])) {
            $stmt = $db->prepare("
                SELECT s.*, st.nama_studio
                FROM seats s
                LEFT JOIN studios st ON s.studio_id = st.studio_id
                WHERE s.seat_id = :seat_id
            ");
            $stmt->bindParam(':seat_id', $_GET['seat_id']);
            $stmt->execute();
            $seat = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($seat) {
                Response::success("Seat found", $seat);
            } else {
                Response::error("Seat not found", 404);
            }
        } 
        
        elseif(isset($_GET['showtime_id']) && isset($_GET['available'])) {
            $stmt = $db->prepare("
                SELECT s.seat_id, s.baris, s.nomor_kursi, st.nama_studio,
                       sa.status_kursi
                FROM seats s
                LEFT JOIN studios st ON s.studio_id = st.studio_id
                LEFT JOIN seat_availability sa ON s.seat_id = sa.seat_id AND sa.showtime_id = :showtime_id
                WHERE sa.status_kursi = 'available' OR sa.seat_id IS NULL
                ORDER BY s.baris, s.nomor_kursi
            ");
            $stmt->bindParam(':showtime_id', $_GET['showtime_id']);
            $stmt->execute();
            $seats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            Response::success("Available seats for showtime", $seats);
        }

        elseif(isset($_GET['studio_id']) && isset($_GET['baris'])) {
            $stmt = $db->prepare("
                SELECT s.*, st.nama_studio
                FROM seats s
                LEFT JOIN studios st ON s.studio_id = st.studio_id
                WHERE s.studio_id = :studio_id AND s.baris = :baris
                ORDER BY s.nomor_kursi ASC
            ");
            $stmt->bindParam(':studio_id', $_GET['studio_id']);
            $stmt->bindParam(':baris', $_GET['baris']);
            $stmt->execute();
            $seats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            Response::success("Seats in row " . $_GET['baris'], $seats);
        }
        
        else {
            $query = "
                SELECT s.*, st.nama_studio, c.nama_bioskop
                FROM seats s
                LEFT JOIN studios st ON s.studio_id = st.studio_id
                LEFT JOIN cinemas c ON st.cinema_id = c.cinema_id
            ";
            
            if(isset($_GET['studio_id'])) {
                $query .= " WHERE s.studio_id = :studio_id";
            }
            
            $query .= " ORDER BY s.baris, s.nomor_kursi";
            
            $stmt = $db->prepare($query);
            if(isset($_GET['studio_id'])) {
                $stmt->bindParam(':studio_id', $_GET['studio_id']);
            }
            $stmt->execute();
            $seats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            Response::success("Seats retrieved", $seats);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->studio_id) && !empty($data->baris) && !empty($data->nomor_kursi)) {
            $query = "INSERT INTO seats (studio_id, baris, nomor_kursi) 
                      VALUES (:studio_id, :baris, :nomor_kursi)";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':studio_id', $data->studio_id);
            $stmt->bindParam(':baris', $data->baris);
            $stmt->bindParam(':nomor_kursi', $data->nomor_kursi);
            
            if($stmt->execute()) {
                Response::success("Seat created successfully", ["seat_id" => $db->lastInsertId()]);
            } else {
                Response::error("Failed to create seat", 500);
            }
        } else {
            Response::error("Incomplete data", 400);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->seat_id)) {
            $query = "UPDATE seats SET 
                      studio_id = :studio_id,
                      baris = :baris,
                      nomor_kursi = :nomor_kursi
                      WHERE seat_id = :seat_id";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':seat_id', $data->seat_id);
            $stmt->bindParam(':studio_id', $data->studio_id);
            $stmt->bindParam(':baris', $data->baris);
            $stmt->bindParam(':nomor_kursi', $data->nomor_kursi);
            
            if($stmt->execute()) {
                Response::success("Seat updated successfully", ["seat_id" => $data->seat_id]);
            } else {
                Response::error("Failed to update seat", 500);
            }
        } else {
            Response::error("Seat ID required", 400);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->seat_id)) {
            $query = "DELETE FROM seats WHERE seat_id = :seat_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':seat_id', $data->seat_id);
            
            if($stmt->execute()) {
                Response::success("Seat deleted successfully", ["seat_id" => $data->seat_id]);
            } else {
                Response::error("Failed to delete seat", 500);
            }
        } else {
            Response::error("Seat ID required", 400);
        }
        break;

    default:
        Response::error("Method not allowed", 405);
        break;
}
?>