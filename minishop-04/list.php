<?php
session_start();
require_once 'config.php';

$success = '';
$error = '';

// Lấy thông báo từ session (nếu có sau khi thêm/sửa/xóa)
if (isset($_SESSION['msg'])) {
    $success = $_SESSION['msg'];
    unset($_SESSION['msg']);
}
if (isset($_SESSION['err'])) {
    $error = $_SESSION['err'];
    unset($_SESSION['err']);
}

// Lấy danh sách (Read)
$stmt = db()->query('SELECT id, name, description, created_at FROM categories ORDER BY id DESC');
$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách Danh mục - PDO</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #f4f4f4; }
        .alert-success { color: green; font-weight: bold; }
        .alert-error { color: red; font-weight: bold; }
        .btn-add { padding: 8px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 10px;}
    </style>
</head>
<body>
    <h1>Danh sách Danh mục (MiniShop)</h1>

    <?php if ($success): ?><p class="alert-success"><?= h($success) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="alert-error"><?= h($error) ?></p><?php endif; ?>

    <!-- Nút chuyển sang trang thêm mới -->
    <a href="create.php" class="btn-add">+ Thêm danh mục mới</a>

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