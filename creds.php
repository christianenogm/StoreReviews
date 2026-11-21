<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$database = getenv('MYSQLDATABASE') ?: getenv('DB_NAME') ?: 'reviews';
$username = getenv('MYSQLUSER') ?: getenv('DB_USER') ?: 'reviews_system';
$password = getenv('MYSQLPASSWORD') ?: getenv('DB_PASSWORD') ?: 'foobar';
$host = getenv('MYSQLHOST') ?: getenv('DB_HOST') ?: 'localhost';
$port = (int) (getenv('MYSQLPORT') ?: getenv('DB_PORT') ?: 3306);

try {
    $conn = mysqli_connect($host, $username, $password, $database, $port);
    mysqli_set_charset($conn, 'utf8mb4');
} catch (mysqli_sql_exception $exception) {
    http_response_code(500);
    exit('Database connection failed. Please verify the database configuration.');
}
?>