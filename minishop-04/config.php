<?php
declare(strict_types=1);

function db(): PDO {
    // Sử dụng static để dùng chung 1 kết nối, không mở lại nhiều lần
    static $pdo = null;
    
    if ($pdo === null) {
        $dsn = 'mysql:host=127.0.0.1;dbname=minishop_cse485;charset=utf8mb4';
        $user = 'root';
        $pass = ''; // Mặc định của XAMPP thường để trống
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            die("Lỗi kết nối CSDL: " . $e->getMessage());
        }
    }
    return $pdo;
}

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>