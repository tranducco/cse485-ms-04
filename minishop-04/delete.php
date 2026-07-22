<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';


$id = (int)($_GET['id'] ?? 0);


if ($id <= 0) {
    exit('ID không hợp lệ');
}


$stmt = db()->prepare(
    'DELETE FROM categories WHERE id = ?'
);


$stmt->execute([$id]);


header('Location: list.php');
exit;