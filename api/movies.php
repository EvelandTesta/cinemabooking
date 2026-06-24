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
        if(isset($_GET['movie_id'])) {
            $stmt = $db->prepare("SELECT * FROM movies WHERE movie_id = :movie_id");
            $stmt->bindParam(':movie_id', $_GET['movie_id']);
            $stmt->execute();
            $movie = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($movie) {
                Response::success("Movie found", $movie);
            } else {
                Response::error("Movie not found", 404);
            }
        } 
        
        elseif(isset($_GET['genre'])) {
            $stmt = $db->prepare("SELECT * FROM movies WHERE genre = :genre ORDER BY judul ASC");
            $stmt->bindParam(':genre', $_GET['genre']);
            $stmt->execute();
            $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if($movies) {
                Response::success("Movies in genre: " . $_GET['genre'], $movies);
            } else {
                Response::error("No movies found in this genre", 404);
            }
        }
        
        elseif(isset($_GET['search'])) {
            $search = "%" . $_GET['search'] . "%";
            $stmt = $db->prepare("
                SELECT * FROM movies 
                WHERE judul LIKE :search OR genre LIKE :search
                ORDER BY judul ASC
            ");
            $stmt->bindParam(':search', $search);
            $stmt->execute();
            $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            Response::success("Search results", $movies);
        }
        
        else {
            $query = "SELECT * FROM movies";
            if(isset($_GET['status_tayang'])) {
                $query .= " WHERE status_tayang = :status";
            }
            $query .= " ORDER BY judul ASC";
            
            $stmt = $db->prepare($query);
            if(isset($_GET['status_tayang'])) {
                $stmt->bindParam(':status', $_GET['status_tayang']);
            }
            $stmt->execute();
            $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
            Response::success("Movies retrieved", $movies);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->judul) && !empty($data->durasi)) {
            // FIX: Mengganti deskripsi menjadi sinopsis, serta menambahkan kolom poster_url
            $query = "INSERT INTO movies (judul, durasi, rating_umur, sinopsis, poster_url, genre, status_tayang) 
                      VALUES (:judul, :durasi, :rating_umur, :sinopsis, :poster_url, :genre, :status_tayang)";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':judul', $data->judul);
            $stmt->bindParam(':durasi', $data->durasi);
            $stmt->bindParam(':rating_umur', $data->rating_umur);
            $stmt->bindParam(':sinopsis', $data->sinopsis);
            $stmt->bindParam(':poster_url', $data->poster_url);
            $stmt->bindParam(':genre', $data->genre);
            $stmt->bindParam(':status_tayang', $data->status_tayang);
            
            if($stmt->execute()) {
                Response::success("Movie created successfully", ["movie_id" => $db->lastInsertId()]);
            } else {
                Response::error("Failed to create movie", 500);
            }
        } else {
            Response::error("Incomplete data", 400);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->movie_id)) {
            try {
                // Fetch the existing movie first to make sure it exists
                $check_stmt = $db->prepare("SELECT * FROM movies WHERE movie_id = :movie_id");
                $check_stmt->bindParam(':movie_id', $data->movie_id);
                $check_stmt->execute();
                $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);
                
                if(!$existing) {
                    Response::error("Movie not found", 404);
                    exit();
                }
                
                // Fields that can be updated
                $fields_to_update = [];
                $params = [':movie_id' => $data->movie_id];
                
                $allowed_fields = ['judul', 'durasi', 'rating_umur', 'sinopsis', 'poster_url', 'genre', 'status_tayang', 'trailer_url'];
                
                foreach($allowed_fields as $field) {
                    if(property_exists($data, $field)) {
                        $fields_to_update[] = "$field = :$field";
                        $params[":$field"] = $data->$field;
                    }
                }
                
                if(empty($fields_to_update)) {
                    Response::error("No fields to update", 400);
                    exit();
                }
                
                $query = "UPDATE movies SET " . implode(", ", $fields_to_update) . " WHERE movie_id = :movie_id";
                $stmt = $db->prepare($query);
                
                if($stmt->execute($params)) {
                    Response::success("Movie updated successfully", [
                        "message" => "Update berhasil",
                        "timestamp" => date('Y-m-d H:i:s')
                    ]);
                } else {
                    Response::error("Failed to update movie", 500);
                }
            } catch(PDOException $e) {
                Response::error("Internal Server Error: " . $e->getMessage(), 500);
            }
        } else {
            Response::error("Movie ID required", 400);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->movie_id)) {
            $query = "DELETE FROM movies WHERE movie_id = :movie_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':movie_id', $data->movie_id);
            
            if($stmt->execute()) {
                Response::success("Movie deleted successfully", [
                    "message" => "Data telah dihapus dari database",
                    "status" => "deleted"
                ]);
            } else {
                Response::error("Failed to delete movie", 500);
            }
        } else {
            Response::error("Movie ID required", 400);
        }
        break;

    default:
        Response::error("Method not allowed", 405);
        break;
}
?>