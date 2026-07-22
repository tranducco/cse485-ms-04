<?php
session_start();
require_once 'config.php';

$error = '';
$success = '';

// Xử lý thông báo từ session (PRG Pattern)
if (isset($_SESSION['msg'])) {
    $success = $_SESSION['msg'];
    unset($_SESSION['msg']);
}
if (isset($_SESSION['err'])) {
    $error = $_SESSION['err'];
    unset($_SESSION['err']);
}

// Xử lý form Thêm mới (Create)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');

    if (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
        $_SESSION['err'] = "Tên danh mục phải từ 2 đến 100 ký tự.";
    } else {
        try {
            $stmt = db()->prepare('INSERT INTO categories (name, description) VALUES (?, ?)');
            $stmt->execute([$name, $desc]);
            $_SESSION['msg'] = "Thêm danh mục thành công!";
        } catch (PDOException $e) {
            // Bắt lỗi trùng UNIQUE constraint (mã 23000)
            if ($e->getCode() == 23000) {
                $_SESSION['err'] = "Lỗi: Tên danh mục '$name' đã tồn tại!";
            } else {
                $_SESSION['err'] = "Lỗi CSDL: " . $e->getMessage();
            }
        }
    }
    header('Location: index.php');
    exit;
}

// Lấy danh sách (Read)
$stmt = db()->query('SELECT id, name, description, created_at FROM categories ORDER BY id DESC');
$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Danh mục - PDO</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #f4f4f4; }
        .alert-success { color: green; font-weight: bold; }
        .alert-error { color: red; font-weight: bold; }
        .form-group { margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Quản lý Danh mục (MiniShop)</h1>

    <?php if ($success): ?><p class="alert-success"><?= h($success) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="alert-error"><?= h($error) ?></p><?php endif; ?>

    <div style="border: 1px solid #ccc; padding: 15px; width: 400px;">
        <h3>Thêm danh mục mới</h3>
        <form method="POST" action="index.php">
            <input type="hidden" name="action" value="create">
            <div class="form-group">
                <label>Tên danh mục (bắt buộc):</label><br>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Mô tả:</label><br>
                <input type="text" name="description">
            </div>
            <button type="submit">Thêm mới</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Mô tả</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cat): ?>
            <tr>
                <td><?= $cat['id'] ?></td>
                <td><?= h($cat['name']) ?></td>
                <td><?= h($cat['description'] ?? '') ?></td>
                <td><?= $cat['created_at'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $cat['id'] ?>">Sửa</a> | 
                    <form method="POST" action="delete.php" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                        <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                        <button type="submit" style="color: red; cursor: pointer; border: none; background: none; text-decoration: underline;">Xóa</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>