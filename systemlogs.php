<?php
// Database connection
$host = 'localhost';
$db = 'odms_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT * FROM logs ORDER BY timestamp DESC");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Logs</title>
    <link rel="stylesheet" href="logs.css">
</head>
<body>
<h1><b><i>System Logs</i></b></h1>
    <div class="container mt-5">
      
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>IP Address</th>
                    <th>User</th>
                    <th>Event</th>
                    <th>HTTP Method</th>
                    <th>Route</th>
                    <th>Status Code</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?php echo $log['timestamp']; ?></td>
                        <td><?php echo $log['ip_address']; ?></td>
                        <td><?php echo $log['user']; ?></td>
                        <td><?php echo $log['event']; ?></td>
                        <td><?php echo $log['http_method']; ?></td>
                        <td><?php echo $log['route']; ?></td>
                        <td><?php echo $log['status_code']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
