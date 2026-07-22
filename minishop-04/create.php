<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

$message = '';
$error = '';

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

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
                'INSERT INTO categories (name, description)
                 VALUES (?, ?)'
            );

            $stmt->execute([
                $name,
                $description !== '' ? $description : null
            ]);

            header('Location: list.php');
            exit;

        } catch (PDOException $e) {

            // Lỗi UNIQUE name bị trùng
            if ($e->getCode() === '23000') {
                $error = 'Tên danh mục đã tồn tại.';
            } else {
                $error = 'Có lỗi xảy ra khi thêm dữ liệu.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Category</title>
</head>

<body>

<h1>Thêm danh mục</h1>

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
            value="<?= h($_POST['name'] ?? '') ?>"
            required
        >
    </label>

    <br><br>

    <label>
        Mô tả:
        <input 
            type="text" 
            name="description"
            value="<?= h($_POST['description'] ?? '') ?>"
        >
    </label>

    <br><br>

    <button type="submit">
        Thêm
    </button>

</form>

<br>

<a href="list.php">
    ← Quay lại danh sách
</a>

</body>
</html>