<?php
session_start();
require_once 'config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Thiếu ID danh mục.");
}

// Lấy thông tin cũ
$stmt = db()->prepare('SELECT * FROM categories WHERE id = ?');
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    die("Không tìm thấy danh mục.");
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');

    if (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
        $error = "Tên danh mục phải từ 2 đến 100 ký tự.";
    } else {
        try {
            $stmt = db()->prepare('UPDATE categories SET name = ?, description = ? WHERE id = ?');
            $stmt->execute([$name, $desc, $id]);
            $_SESSION['msg'] = "Cập nhật thành công!";
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Tên danh mục '$name' đã bị trùng với danh mục khác!";
            } else {
                $error = "Lỗi CSDL: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Danh mục</title>
</head>
<body style="font-family: sans-serif; padding: 20px;">
    <h2>Sửa Danh mục: <?= h($category['name']) ?></h2>
    <?php if ($error): ?><p style="color: red;"><?= h($error) ?></p><?php endif; ?>
    
    <form method="POST">
        <p>
            <label>Tên danh mục:</label><br>
            <input type="text" name="name" value="<?= h($category['name']) ?>" required>
        </p>
        <p>
            <label>Mô tả:</label><br>
            <input type="text" name="description" value="<?= h($category['description'] ?? '') ?>">
        </p>
        <button type="submit">Lưu thay đổi</button>
        <a href="index.php">Hủy</a>
    </form>
</body>
</html>