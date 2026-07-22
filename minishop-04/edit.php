<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    exit('ID không hợp lệ');
}

// Lấy dữ liệu cũ
$stmt = db()->prepare(
    'SELECT id, name, description 
     FROM categories 
     WHERE id = ?'
);

$stmt->execute([$id]);

$category = $stmt->fetch();

if (!$category) {
    exit('Không tìm thấy danh mục');
}


$error = '';


// Xử lý cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');


    // Validate
    if ($name === '') {

        $error = 'Tên danh mục không được để trống.';

    } elseif (mb_strlen($name) < 2 || mb_strlen($name) > 100) {

        $error = 'Tên danh mục phải từ 2 đến 100 ký tự.';

    } else {

        try {

            $stmt = db()->prepare(
                'UPDATE categories
                 SET name = ?, description = ?
                 WHERE id = ?'
            );

            $stmt->execute([
                $name,
                $description !== '' ? $description : null,
                $id
            ]);


            header('Location: list.php');
            exit;


        } catch (PDOException $e) {

            if ($e->getCode() === '23000') {
                $error = 'Tên danh mục đã tồn tại.';
            } else {
                $error = 'Có lỗi khi cập nhật.';
            }
        }
    }


    // Giữ dữ liệu người dùng vừa nhập
    $category['name'] = $name;
    $category['description'] = $description;
}

?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa Category</title>
</head>


<body>

<h1>Sửa danh mục #<?= (int)$category['id'] ?></h1>


<?php if ($error): ?>

<p style="color:red;">
    <?= h($error) ?>
</p>

<?php endif; ?>


<form method="post">

    <label>
        Tên danh mục:
        <input
            type="text"
            name="name"
            value="<?= h($category['name']) ?>"
            required
        >
    </label>


    <br><br>


    <label>
        Mô tả:
        <input
            type="text"
            name="description"
            value="<?= h((string)$category['description']) ?>"
        >
    </label>


    <br><br>


    <button type="submit">
        Cập nhật
    </button>

</form>


<br>

<a href="list.php">
    ← Quay lại
</a>


</body>
</html>