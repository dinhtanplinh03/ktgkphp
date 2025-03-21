<?php
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $MaSV = $_POST["MaSV"];
    $HoTen = $_POST["HoTen"];
    $GioiTinh = $_POST["GioiTinh"];
    $NgaySinh = $_POST["NgaySinh"];
    $MaNganh = $_POST["MaNganh"]; // Lấy mã ngành từ form
    $Hinh = $_FILES["Hinh"]["name"];

    // Upload ảnh lên thư mục uploads/
    move_uploaded_file($_FILES["Hinh"]["tmp_name"], "../uploads/" . $Hinh);

    // Thêm dữ liệu vào database
    $query = "INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$MaSV, $HoTen, $GioiTinh, $NgaySinh, $Hinh, $MaNganh]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Sinh Viên</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <h2>Thêm Sinh Viên</h2>
    <form method="post" enctype="multipart/form-data">
        Mã SV: <input type="text" name="MaSV" required><br>
        Họ Tên: <input type="text" name="HoTen" required><br>
        Giới Tính: 
        <select name="GioiTinh">
            <option value="Nam">Nam</option>
            <option value="Nữ">Nữ</option>
        </select><br>
        Ngày Sinh: <input type="date" name="NgaySinh" required><br>
        Hình: <input type="file" name="Hinh" required><br>
        Mã Ngành: <input type="text" name="MaNganh" required><br>
        <button type="submit">Lưu</button>
    </form>
</body>
</html>
