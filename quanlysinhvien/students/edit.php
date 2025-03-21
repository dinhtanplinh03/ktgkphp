<?php
require_once "../config/database.php";

$MaSV = $_GET["MaSV"];
$query = "SELECT * FROM SinhVien WHERE MaSV = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$MaSV]);
$sv = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $HoTen = $_POST["HoTen"];
    $GioiTinh = $_POST["GioiTinh"];
    $NgaySinh = $_POST["NgaySinh"];
    $MaNganh = $_POST["MaNganh"]; // Lấy mã ngành từ form

    // Xử lý upload ảnh nếu có file mới
    if (!empty($_FILES["Hinh"]["name"])) {
        $targetDir = "../uploads/";
        $fileName = basename($_FILES["Hinh"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Kiểm tra định dạng ảnh
        $allowTypes = ["jpg", "png", "jpeg", "gif"];
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES["Hinh"]["tmp_name"], $targetFilePath)) {
                // Cập nhật CSDL với ảnh mới
                $query = "UPDATE SinhVien SET HoTen = ?, GioiTinh = ?, NgaySinh = ?, Hinh = ?, MaNganh = ? WHERE MaSV = ?";
                $stmt = $conn->prepare($query);
                $stmt->execute([$HoTen, $GioiTinh, $NgaySinh, $fileName, $MaNganh, $MaSV]);
            }
        } else {
            echo "Chỉ hỗ trợ định dạng JPG, JPEG, PNG, GIF!";
        }
    } else {
        // Cập nhật không đổi ảnh
        $query = "UPDATE SinhVien SET HoTen = ?, GioiTinh = ?, NgaySinh = ?, MaNganh = ? WHERE MaSV = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$HoTen, $GioiTinh, $NgaySinh, $MaNganh, $MaSV]);
    }

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Sinh Viên</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <h2>Sửa Sinh Viên</h2>
    <form method="post" enctype="multipart/form-data">
        Họ Tên: <input type="text" name="HoTen" value="<?= $sv["HoTen"] ?>" required><br>
        Giới Tính: 
        <select name="GioiTinh">
            <option value="Nam" <?= ($sv["GioiTinh"] == "Nam") ? "selected" : "" ?>>Nam</option>
            <option value="Nữ" <?= ($sv["GioiTinh"] == "Nữ") ? "selected" : "" ?>>Nữ</option>
        </select><br>
        Ngày Sinh: <input type="date" name="NgaySinh" value="<?= $sv["NgaySinh"] ?>" required><br>
        Hình Ảnh: <input type="file" name="Hinh"><br>
        <img src="../uploads/<?= $sv["Hinh"] ?>" width="100"><br> <!-- Hiển thị ảnh hiện tại -->
        Mã Ngành: <input type="text" name="MaNganh" value="<?= $sv["MaNganh"] ?>" required><br>
        <button type="submit">Cập Nhật</button>
    </form>
</body>
</html>
