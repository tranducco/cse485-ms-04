<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

$stmt = db()->query(
    'SELECT id, name, description, created_at
     FROM categories
     ORDER BY id'
);

$categories = $stmt->fetchAll();

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Categories</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        table {
            border-collapse: collapse;
            width: 800px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
        }

        th {
            background: #f2f2f2;
        }

        .btn {
            display: inline-block;
            margin-bottom: 15px;
            padding: 8px 12px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>
<body>

<h1>Quản lý Categories</h1>

<a class="btn" href="create.php">+ Thêm danh mục</a>

<table>
    <tr>
        <th>ID</th>
        <th>Tên danh mục</th>
        <th>Mô tả</th>
        <th>Ngày tạo</th>
        <th>Thao tác</th>
    </tr>

    <?php foreach ($categories as $category): ?>
        <tr>
            <td><?= (int)$category['id'] ?></td>
            <td><?= h($category['name']) ?></td>
            <td><?= h((string)$category['description']) ?></td>
            <td><?= h($category['created_at']) ?></td>
            <td>
                <a href="edit.php?id=<?= (int)$category['id'] ?>">Sửa</a> |
                <a href="delete.php?id=<?= (int)$category['id'] ?>"
                    onclick="return confirm('Bạn có chắc muốn xóa?');">
                    Xóa
                </a>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>