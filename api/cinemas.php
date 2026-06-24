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
        if(isset($_GET['cinema_id'])) {
            $stmt = $db->prepare("SELECT * FROM cinemas WHERE cinema_id = :cinema_id");
            $stmt->bindParam(':cinema_id', $_GET['cinema_id']);
            $stmt->execute();
            $cinema = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($cinema) {
                // Get studios for this cinema
                $stmtStudios = $db->prepare("SELECT * FROM studios WHERE cinema_id = :cinema_id");
                $stmtStudios->bindParam(':cinema_id', $_GET['cinema_id']);
                $stmtStudios->execute();
                $cinema['studios'] = $stmtStudios->fetchAll(PDO::FETCH_ASSOC);
                
                Response::success("Cinema found", $cinema);
            } else {
                Response::error("Cinema not found", 404);
            }
        } 
        
        elseif(isset($_GET['today_showtimes'])) {
            $today = date('Y-m-d');
            $stmt = $db->query("
                SELECT DISTINCT c.*, m.judul as movie_title, sh.Jam
                FROM cinemas c
                INNER JOIN studios st ON c.cinema_id = st.cinema_id
                INNER JOIN showtimes sh ON st.studio_id = sh.studio_id
                INNER JOIN movies m ON sh.movie_id = m.movie_id
                WHERE sh.Tanggal = '$today'
                ORDER BY c.nama_bioskop, sh.Jam
            ");
            $cinemas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            Response::success("Cinemas with today's showtimes", $cinemas);
        }

        elseif(isset($_GET['kota'])) {
            $stmt = $db->prepare("
                SELECT c.*, COUNT(s.studio_id) as total_studios
                FROM cinemas c
                LEFT JOIN studios s ON c.cinema_id = s.cinema_id
                WHERE c.kota = :kota
                GROUP BY c.cinema_id
            ");
            $stmt->bindParam(':kota', $_GET['kota']);
            $stmt->execute();
            $cinemas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if($cinemas) {
                Response::success("Cinemas in " . $_GET['kota'], $cinemas);
            } else {
                Response::error("No cinemas found in this city", 404);
            }
        }
        
        else {
            $stmt = $db->query("SELECT * FROM cinemas ORDER BY nama_bioskop ASC");
            $cinemas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            Response::success("Cinemas retrieved", $cinemas);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->nama_bioskop) && !empty($data->lokasi) && !empty($data->kota)) {
            $query = "INSERT INTO cinemas (nama_bioskop, lokasi, kota) 
                      VALUES (:nama_bioskop, :lokasi, :kota)";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':nama_bioskop', $data->nama_bioskop);
            $stmt->bindParam(':lokasi', $data->lokasi);
            $stmt->bindParam(':kota', $data->kota);
            
            if($stmt->execute()) {
                Response::success("Cinema created successfully", ["cinema_id" => $db->lastInsertId()]);
            } else {
                Response::error("Failed to create cinema", 500);
            }
        } else {
            Response::error("Incomplete data", 400);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->cinema_id)) {
            $query = "UPDATE cinemas SET 
                      nama_bioskop = :nama_bioskop, 
                      lokasi = :lokasi, 
                      kota = :kota 
                      WHERE cinema_id = :cinema_id";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':cinema_id', $data->cinema_id);
            $stmt->bindParam(':nama_bioskop', $data->nama_bioskop);
            $stmt->bindParam(':lokasi', $data->lokasi);
            $stmt->bindParam(':kota', $data->kota);
            
            if($stmt->execute()) {
                Response::success("Cinema updated successfully", ["cinema_id" => $data->cinema_id]);
            } else {
                Response::error("Failed to update cinema", 500);
            }
        } else {
            Response::error("Cinema ID required", 400);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->cinema_id)) {
            $query = "DELETE FROM cinemas WHERE cinema_id = :cinema_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':cinema_id', $data->cinema_id);
            
            if($stmt->execute()) {
                Response::success("Cinema deleted successfully", ["cinema_id" => $data->cinema_id]);
            } else {
                Response::error("Failed to delete cinema", 500);
            }
        } else {
            Response::error("Cinema ID required", 400);
        }
        break;

    default:
        Response::error("Method not allowed", 405);
        break;
}
?>