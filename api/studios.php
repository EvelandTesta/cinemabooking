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
        if(isset($_GET['studio_id'])) {
            $stmt = $db->prepare("
                SELECT s.*, c.nama_bioskop
                FROM studios s
                LEFT JOIN cinemas c ON s.cinema_id = c.cinema_id
                WHERE s.studio_id = :studio_id
            ");
            $stmt->bindParam(':studio_id', $_GET['studio_id']);
            $stmt->execute();
            $studio = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($studio) {
                // Get seats
                $stmtSeats = $db->prepare("SELECT * FROM seats WHERE studio_id = :studio_id ORDER BY baris, nomor_kursi");
                $stmtSeats->bindParam(':studio_id', $_GET['studio_id']);
                $stmtSeats->execute();
                $studio['seats'] = $stmtSeats->fetchAll(PDO::FETCH_ASSOC);
                
                Response::success("Studio found", $studio);
            } else {
                Response::error("Studio not found", 404);
            }
        } 
        
        elseif(isset($_GET['today_schedule'])) {
            $today = date('Y-m-d');
            $stmt = $db->prepare("
                SELECT DISTINCT s.*, c.nama_bioskop, m.judul as movie_title, sh.Jam
                FROM studios s
                LEFT JOIN cinemas c ON s.cinema_id = c.cinema_id
                LEFT JOIN showtimes sh ON s.studio_id = sh.studio_id
                LEFT JOIN movies m ON sh.movie_id = m.movie_id
                WHERE sh.Tanggal = :today
                ORDER BY s.nama_studio, sh.Jam
            ");
            $stmt->bindParam(':today', $today);
            $stmt->execute();
            $studios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            Response::success("Studios with today's schedule", $studios);
        }

        elseif(isset($_GET['availability'])) {
            $stmt = $db->query("
                SELECT s.studio_id, s.nama_studio, s.kapasitas, c.nama_bioskop,
                       COUNT(DISTINCT sh.showtime_id) as total_showtimes,
                       COUNT(DISTINCT CASE WHEN sh.Tanggal = CURDATE() THEN sh.showtime_id END) as today_showtimes
                FROM studios s
                LEFT JOIN cinemas c ON s.cinema_id = c.cinema_id
                LEFT JOIN showtimes sh ON s.studio_id = sh.studio_id
                GROUP BY s.studio_id
                ORDER BY c.nama_bioskop, s.nama_studio
            ");
            $studios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            Response::success("Studio availability status", $studios);
        }
        
        else {
            $stmt = $db->query("
                SELECT s.*, c.nama_bioskop
                FROM studios s
                LEFT JOIN cinemas c ON s.cinema_id = c.cinema_id
                ORDER BY c.nama_bioskop, s.nama_studio
            ");
            $studios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            Response::success("Studios retrieved", $studios);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->cinema_id) && !empty($data->nama_studio) && !empty($data->kapasitas)) {
            $query = "INSERT INTO studios (cinema_id, nama_studio, kapasitas) 
                      VALUES (:cinema_id, :nama_studio, :kapasitas)";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':cinema_id', $data->cinema_id);
            $stmt->bindParam(':nama_studio', $data->nama_studio);
            $stmt->bindParam(':kapasitas', $data->kapasitas);
            
            if($stmt->execute()) {
                Response::success("Studio created successfully", ["studio_id" => $db->lastInsertId()]);
            } else {
                Response::error("Failed to create studio", 500);
            }
        } else {
            Response::error("Incomplete data", 400);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->studio_id)) {
            $query = "UPDATE studios SET 
                      cinema_id = :cinema_id,
                      nama_studio = :nama_studio,
                      kapasitas = :kapasitas
                      WHERE studio_id = :studio_id";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':studio_id', $data->studio_id);
            $stmt->bindParam(':cinema_id', $data->cinema_id);
            $stmt->bindParam(':nama_studio', $data->nama_studio);
            $stmt->bindParam(':kapasitas', $data->kapasitas);
            
            if($stmt->execute()) {
                Response::success("Studio updated successfully",[
                    "studio_id" => $data->studio_id,
                    "nama_studio" => $data->nama_studio
                ]);
            } else {
                Response::error("Failed to update studio", 500);
            }
        } else {
            Response::error("Studio ID required", 400);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->studio_id)) {
            $query = "DELETE FROM studios WHERE studio_id = :studio_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':studio_id', $data->studio_id);
            
            if($stmt->execute()) {
                Response::success("Studio deleted successfully",["Deleted"]);
            } else {
                Response::error("Failed to delete studio", 500);
            }
        } else {
            Response::error("Studio ID required", 400);
        }
        break;

    default:
        Response::error("Method not allowed", 405);
        break;
}
?>