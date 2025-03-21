<?php
require_once "../config/database.php";

$query = "SELECT * FROM SinhVien";
$stmt = $conn->prepare($query);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Sinh Viên</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
<div class="navbar">
        <a href="index.php">Sinh Viên</a>
        <a href="hocphan.php">Học Phần</a>
        <a href="dangky.php">Đăng Ký</a>
        <a href="login.php">Đăng Nhập</a>
    </div>
    <h2>Danh Sách Sinh Viên</h2>
    <a href="create.php">Thêm Sinh Viên</a>
    <table border="1">
        <tr>
            <th>Mã SV</th>
            <th>Họ Tên</th>
            <th>Giới Tính</th>
            <th>Ngày Sinh</th>
            <th>Hình</th>
            <th>Mã Ngành</th>
            <th>Hành Động</th>
        </tr>
        <?php foreach ($students as $sv): ?>
            <tr>
                <td><?= $sv["MaSV"] ?></td>
                <td><?= $sv["HoTen"] ?></td>
                <td><?= $sv["GioiTinh"] ?></td>
                <td><?= $sv["NgaySinh"] ?></td>
                <td><img src="../uploads/<?= $sv["Hinh"] ?>" width="50"></td>
                <td><?= $sv["MaNganh"] ?></td>
                <td>
                    <a href="detail.php?MaSV=<?= $sv["MaSV"] ?>">Xem</a>
                    <a href="edit.php?MaSV=<?= $sv["MaSV"] ?>">Sửa</a>
                    <a href="delete.php?MaSV=<?= $sv["MaSV"] ?>">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
