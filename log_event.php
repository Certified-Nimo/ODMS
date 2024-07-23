<?php
function logEvent($user, $event, $httpMethod, $route, $statusCode) {
    $timestamp = date('Y-m-d H:i:s');
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    
    // Database connection
    $host = 'localhost';
    $db = 'odms_db';
    $dbUser = 'root';
    $dbPass = '';
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $dbUser, $dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO logs (timestamp, ip_address, user, event, http_method, route, status_code) 
                               VALUES (:timestamp, :ip_address, :user, :event, :http_method, :route, :status_code)");

        $stmt->execute([
            ':timestamp' => $timestamp,
            ':ip_address' => $ipAddress,
            ':user' => $user,
            ':event' => $event,
            ':http_method' => $httpMethod,
            ':route' => $route,
            ':status_code' => $statusCode
        ]);

    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}
?>
