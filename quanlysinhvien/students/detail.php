<?php
require_once "../config/database.php";

if (!isset($_GET["MaSV"])) {
    die("Mã sinh viên không hợp lệ!");
}

$MaSV = $_GET["MaSV"];

$query = "SELECT * FROM SinhVien WHERE MaSV = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$MaSV]);
$sv = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sv) {
    die("Sinh viên không tồn tại!");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi Tiết Sinh Viên</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <h2>Chi Tiết Sinh Viên</h2>
    <table border="1">
        <tr>
            <th>Mã SV</th>
            <td><?= htmlspecialchars($sv["MaSV"]) ?></td>
        </tr>
        <tr>
            <th>Họ Tên</th>
            <td><?= htmlspecialchars($sv["HoTen"]) ?></td>
        </tr>
        <tr>
            <th>Giới Tính</th>
            <td><?= htmlspecialchars($sv["GioiTinh"]) ?></td>
        </tr>
        <tr>
            <th>Ngày Sinh</th>
            <td><?= htmlspecialchars($sv["NgaySinh"]) ?></td>
        </tr>
        <tr>
            <th>Hình</th>
            <td><img src="../uploads/<?= htmlspecialchars($sv["Hinh"]) ?>" width="100"></td>
        </tr>
        <tr>
            <th>Mã Ngành</th>
            <td><?= htmlspecialchars($sv["MaNganh"]) ?></td>
        </tr>
    </table>

    <br>
    <a href="index.php">Quay lại</a>
    <a href="edit.php?MaSV=<?= htmlspecialchars($sv["MaSV"]) ?>">Sửa</a>
</body>
</html>
