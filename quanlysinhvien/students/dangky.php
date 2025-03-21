<?php
require_once "../config/database.php";
session_start();

if (!isset($_SESSION["MaSV"])) {
    header("Location: login.php");
    exit();
}

$MaSV = $_SESSION["MaSV"];

// ✅ Xử lý lưu đăng ký vào CSDL
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save"])) {
    // 🔹 Kiểm tra xem sinh viên đã có MaDK chưa
    $query = "SELECT MaDK FROM DangKy WHERE MaSV = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$MaSV]);
    $dangky = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dangky) {
        // Nếu chưa có, tạo mới bản ghi trong bảng DangKy
        $query = "INSERT INTO DangKy (NgayDK, MaSV) VALUES (NOW(), ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$MaSV]);

        // Lấy MaDK vừa tạo
        $MaDK = $conn->lastInsertId();
    } else {
        $MaDK = $dangky["MaDK"];
    }

    // 🔹 Kiểm tra các học phần đã đăng ký
    if (!empty($_SESSION["hocphans"])) {
        foreach ($_SESSION["hocphans"] as $MaHP) {
            // Kiểm tra xem học phần này đã được đăng ký chưa
            $query = "SELECT * FROM ChiTietDangKy WHERE MaDK = ? AND MaHP = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$MaDK, $MaHP]);
            $exists = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$exists) {
                // Nếu chưa có, thêm vào bảng ChiTietDangKy
                $query = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->execute([$MaDK, $MaHP]);
            }
        }
    }

    $_SESSION["luu_thanh_cong"] = true;
    header("Location: dangky.php");
    exit();
}

// ✅ Lấy danh sách học phần đã đăng ký + tính số lượng học phần + tổng số tín chỉ
$query = "SELECT HP.MaHP, HP.TenHP, HP.SoTinChi 
          FROM ChiTietDangKy CTDK
          JOIN HocPhan HP ON CTDK.MaHP = HP.MaHP
          JOIN DangKy DK ON CTDK.MaDK = DK.MaDK
          WHERE DK.MaSV = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$MaSV]);
$hocphans = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 🔹 Lưu danh sách học phần vào SESSION để dùng khi lưu
$_SESSION["hocphans"] = array_column($hocphans, 'MaHP');

// 🔹 Đếm số lượng học phần và tính tổng số tín chỉ
$soLuongHP = count($hocphans);
$tongTinChi = array_sum(array_column($hocphans, 'SoTinChi'));
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Ký Học Phần</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <nav>
        <a href="index.php">Sinh Viên</a>
        <a href="hocphan.php">Học Phần</a>
        <a href="dangky.php"><strong>Đăng Ký</strong></a>
        <a href="logout.php">Đăng Xuất</a>
    </nav>

    <h2>Đăng Ký Học Phần</h2>

    <!-- ✅ Hiển thị thông báo lưu thành công -->
    <?php if (isset($_SESSION["luu_thanh_cong"])): ?>
        <p style="color: green;">✅ Đăng ký đã được lưu vào CSDL thành công!</p>
        <?php unset($_SESSION["luu_thanh_cong"]); ?>
    <?php endif; ?>

    <p><strong>Số lượng học phần đã đăng ký:</strong> <?= $soLuongHP ?> </p>
    <p><strong>Tổng số tín chỉ:</strong> <?= $tongTinChi ?> </p>

    <?php if ($soLuongHP > 0): ?>
        <table border="1">
            <tr>
                <th>Mã Học Phần</th>
                <th>Tên Học Phần</th>
                <th>Số Tín Chỉ</th>
                <th>Hành Động</th>
            </tr>
            <?php foreach ($hocphans as $hp): ?>
                <tr>
                    <td><?= $hp["MaHP"] ?></td>
                    <td><?= $hp["TenHP"] ?></td>
                    <td><?= $hp["SoTinChi"] ?></td>
                    <td><a href="dangky.php?action=delete&MaHP=<?= $hp["MaHP"] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa học phần này?');">Xóa</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <a href="dangky.php?action=deleteAll" onclick="return confirm('Bạn có chắc chắn muốn xóa tất cả học phần?');">❌ Xóa Tất Cả</a>
        <br><br>
        
        <!-- ✅ Nút Lưu Đăng Ký -->
        <form method="post">
            <button type="submit" name="save">💾 Lưu Đăng Ký</button>
        </form>

    <?php else: ?>
        <p>Chưa đăng ký học phần nào.</p>
    <?php endif; ?>
</body>
</html>
