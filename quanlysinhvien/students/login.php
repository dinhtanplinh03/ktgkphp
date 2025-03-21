<?php
require_once "../config/database.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $MaSV = trim($_POST["MaSV"]);

    // Kiểm tra xem Mã SV có trong database không
    $query = "SELECT * FROM SinhVien WHERE MaSV = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$MaSV]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        // Lưu MaSV vào session
        $_SESSION["MaSV"] = $student["MaSV"];
        $_SESSION["HoTen"] = $student["HoTen"]; // Lưu thêm họ tên nếu cần

        // Điều hướng đến trang học phần sau khi đăng nhập thành công
        header("Location: hocphan.php");
        exit();
    } else {
        $error = "Mã sinh viên không tồn tại!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <nav>
        <a href="index.php">Sinh Viên</a>
        <a href="hocphan.php">Học Phần</a>
        <a href="dangky.php">Đăng Ký</a>
        <a href="login.php"><strong>Đăng Nhập</strong></a>
    </nav>

    <h2>ĐĂNG NHẬP</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    
    <form method="post">
        <label for="MaSV">Mã SV:</label>
        <input type="text" name="MaSV" id="MaSV" required>
        <button type="submit">Đăng Nhập</button>
    </form>

    <a href="students.php">Back to List</a>
</body>
</html>
