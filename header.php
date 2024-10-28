<?php
include 'connectiondb.php';

// Kiểm tra session
if (!isset($_SESSION['KhachHangID'])) {
    header("Location: ../chieuphim/login.php");
    exit();
}

// Lấy thông tin khách hàng và chức vụ
$sql_customer = "SELECT k.ho_ten, k.chucvuID, c.tenChucVu 
                 FROM khachhang k 
                 JOIN chucvu c ON k.chucvuID = c.chucvuID 
                 WHERE k.KhachHangID = ?";
$stmt_customer = $conn->prepare($sql_customer);
$stmt_customer->bind_param("s", $_SESSION['KhachHangID']);
$stmt_customer->execute();
$result_customer = $stmt_customer->get_result();
$customer = $result_customer->fetch_assoc();

$ho_ten = $customer['ho_ten'];
$chucvuID = $customer['chucvuID'];
$isStaff = ($chucvuID == 2 || $chucvuID == 3); // Quản lý hoặc nhân viên
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body style="background-color: gainsboro;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <h3><a class="navbar-brand" href="formtrangchu02.php" style="color: red;">Rạp Phim XYZ</a></h3>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <?php if ($isStaff): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Quản lý
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="quanlynhanvien.php"><i class="fas fa-users"></i> Thông tin Nhân viên</a></li>
                            <li><a class="dropdown-item" href="suatchieu.php"><i class="fas fa-calendar-alt"></i> Suất chiếu</a></li>
                            <li><a class="dropdown-item" href="phim.php"><i class="fas fa-video"></i> Phim</a></li>
                            <li><a class="dropdown-item" href="cosovatchat.php"><i class="fas fa-ticket-alt"></i> Quản lý cơ sở vật chất</a></li>
                            <li><a class="dropdown-item" href="quanlykhuyenmai.php"><i class="fas fa-tags"></i> Quản lý khuyến mãi</a></li>
                            <li><a class="dropdown-item" href="nguoncungcapphim.php"><i class="fas fa-film"></i> Nguồn cung cấp phim</a></li>
                            <li><a class="dropdown-item" href="thongkebaocao.php"><i class="fas fa-chart-line"></i> Thống kê và báo cáo</a></li>
                            <li><a class="dropdown-item" href="quanlykhachhang.php"><i class="fas fa-user-friends"></i> Quản lý Khách Hàng</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="lichsudatve.php"><i class="fas fa-calendar-check"></i> Vé của tôi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="uudai.php"><i class="fas fa-tags"></i> Ưu đãi</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($ho_ten); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-in-alt"></i> Đăng Xuất</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>