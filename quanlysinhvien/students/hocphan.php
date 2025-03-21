<?php
require_once "../config/database.php";

try {
    // Kiểm tra kết nối
    if (!$conn) {
        die("Lỗi kết nối cơ sở dữ liệu!");
    }

    // Lấy danh sách học phần
    $query = "SELECT * FROM HocPhan";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $hocphans = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Học Phần</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Sinh Viên</a>
            <a href="hocphan.php">Học Phần</a>
            <a href="dangky.php">Đăng Ký</a>
            <a href="login.php">Đăng Nhập</a>
        </nav>
    </header>
    
    <h2>Danh Sách Học Phần</h2>
    <table border="1">
        <tr>
            <th>Mã Học Phần</th>
            <th>Tên Học Phần</th>
            <th>Số Tín Chỉ</th>
            <th>Hành Động</th>
        </tr>
        <?php if (!empty($hocphans)): ?>
            <?php foreach ($hocphans as $hp): ?>
                <tr>
                    <td><?= htmlspecialchars($hp["MaHP"]) ?></td>
                    <td><?= htmlspecialchars($hp["TenHP"]) ?></td>
                    <td><?= htmlspecialchars($hp["SoTinChi"]) ?></td>
                    <td>
    <a href="dangky.php?action=add&MaHP=<?= $hp["MaHP"] ?>">Đăng Ký</a>
</td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Không có học phần nào.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
