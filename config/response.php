<?php
class Response {
    public static function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header("Content-Type: application/json");
        echo json_encode($data);
        exit;
    }

    public static function success($message, $data = null) {
        self::json([
            "status" => "success",
            "message" => $message,
            "data" => $data
        ], 200);
    }

    public static function error($message, $statusCode = 400) {
        self::json([
            "status" => "error",
            "message" => $message
        ], $statusCode);
    }
}
?>