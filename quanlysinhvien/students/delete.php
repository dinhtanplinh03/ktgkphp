<?php
require_once "../config/database.php";

if (!isset($_GET["MaSV"])) {
    die("Không có mã sinh viên!");
}

$MaSV = $_GET["MaSV"];

// Lấy thông tin sinh viên để hiển thị
$query = "SELECT * FROM SinhVien WHERE MaSV = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$MaSV]);
$sv = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sv) {
    die("Sinh viên không tồn tại!");
}

// Xử lý khi xác nhận xóa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = "DELETE FROM SinhVien WHERE MaSV = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$MaSV]);

    // Quay về danh sách sau khi xóa
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xóa Thông Tin Sinh Viên</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <div class="container">
        <h2>XÓA THÔNG TIN</h2>
        <p class="confirm-text">Are you sure you want to delete this?</p>

        <div class="student-info">
            <table>
                <tr>
                    <th>Họ Tên</th>
                    <td><?= $sv["HoTen"] ?></td>
                </tr>
                <tr>
                    <th>Giới Tính</th>
                    <td><?= $sv["GioiTinh"] ?></td>
                </tr>
                <tr>
                    <th>Ngày Sinh</th>
                    <td><?= $sv["NgaySinh"] ?></td>
                </tr>
                <tr>
                    <th>Hình</th>
                    <td><img src="../uploads/<?= $sv["Hinh"] ?>" width="150" alt="Ảnh sinh viên"></td>
                </tr>
                <tr>
                    <th>Ngành</th>
                    <td><?= $sv["Nganh"] ?? "Không có thông tin" ?></td>
                </tr>
            </table>
        </div>

        <form method="post">
            <button type="submit" class="btn btn-danger">Delete</button>
            <a href="index.php" class="btn btn-secondary">Back to List</a>
        </form>
    </div>
</body>
</html>
