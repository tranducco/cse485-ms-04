<?php
session_start();
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');

    if (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
        $error = "Tên danh mục phải từ 2 đến 100 ký tự.";
    } else {
        try {
            $stmt = db()->prepare('INSERT INTO categories (name, description) VALUES (?, ?)');
            $stmt->execute([$name, $desc]);
            
            // Lưu thông báo thành công và chuyển hướng về danh sách
            $_SESSION['msg'] = "Thêm danh mục thành công!";
            header('Location: list.php');
            exit;
        } catch (PDOException $e) {
            // Bắt lỗi trùng lặp (UNIQUE constraint)
            if ($e->getCode() == 23000) {
                $error = "Lỗi: Tên danh mục '$name' đã tồn tại!";
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
    <title>Thêm Danh mục mới</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .form-container { border: 1px solid #ccc; padding: 20px; width: 400px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { font-weight: bold; display: block; margin-bottom: 5px;}
        .form-group input { width: 90%; padding: 8px; }
        .alert-error { color: red; font-weight: bold; margin-bottom: 15px; }
    </style>
</head>
<body>
    <h2>Thêm Danh mục mới</h2>
    
    <?php if ($error): ?>
        <div class="alert-error"><?= h($error) ?></div>
    <?php endif; ?>

    <div class="form-container">
        <!-- Form gửi dữ liệu dạng POST về chính file create.php -->
        <form method="POST" action="create.php">
            <div class="form-group">
                <label>Tên danh mục (bắt buộc):</label>
                <!-- Giữ lại giá trị user vừa nhập nếu có lỗi -->
                <input type="text" name="name" value="<?= h($_POST['name'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label>Mô tả:</label>
                <input type="text" name="description" value="<?= h($_POST['description'] ?? '') ?>">
            </div>
            
            <button type="submit" style="padding: 8px 15px; cursor: pointer;">Thêm mới</button>
            <a href="list.php" style="margin-left: 10px;">Hủy</a>
        </form>
    </div>
</body>
</html>