<?php
session_start();

if (!isset($_SESSION['KhachHangID']) || !isset($_GET['booking_id'])) {
    header('Location: formtrangchu02.php');
    exit();
}

include 'connectiondb.php';

// Lấy thông tin vé mới đặt
$sql = "SELECT v.*, p.ten_phim, sc.gio_chieu, sc.phong_id, 
        GROUP_CONCAT(v.ghe_id ORDER BY v.ghe_id ASC) as ghe_ids,
        SUM(v.gia_ve) as tong_gia_ve
        FROM ve v 
        JOIN suatchieu sc ON v.suat_id = sc.suat_id
        JOIN phim p ON sc.phim_id = p.phim_id
        WHERE v.KhachHangID = ? AND v.booking_id = ?
        GROUP BY v.booking_id, v.suat_id, p.ten_phim, sc.gio_chieu, sc.phong_id";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $_SESSION['KhachHangID'], $_GET['booking_id']);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

include "header.php";
?>
<title>Đặt vé</title>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center">
                    <div class="success-checkmark">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>

                    <h2 class="card-title mt-4">Đặt vé thành công!</h2>
                    <p class="text-muted">Cảm ơn bạn đã đặt vé. Dưới đây là thông tin chi tiết về vé của bạn.</p>

                    <div class="booking-details mt-4 text-start">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Thông tin phim:</h5>
                                <p><strong>Tên phim:</strong> <?php echo htmlspecialchars($booking['ten_phim']); ?></p>
                                <p><strong>Giờ chiếu:</strong> <?php echo htmlspecialchars($booking['gio_chieu']); ?></p>
                                <p><strong>Phòng:</strong> <?php echo htmlspecialchars($booking['phong_id']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h5>Thông tin vé:</h5>
                                <p><strong>Mã đặt vé:</strong> <?php echo htmlspecialchars($booking['booking_id']); ?></p>
                                <p><strong>Ghế:</strong> <?php echo htmlspecialchars($booking['ghe_ids']); ?></p>
                                <p><strong>Tổng giá vé:</strong> <?php echo number_format($booking['tong_gia_ve'], 0, ',', '.'); ?> VNĐ</p>
                                <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($booking['ngay_mua'])); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="qr-code mt-4">
                        <!-- Tạo mã QR với thông tin vé -->
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php
                                                                                                echo urlencode("Vé xem phim: " . $booking['ten_phim'] .
                                                                                                    "\nMã vé: " . $booking['ve_id'] .
                                                                                                    "\nGhế: " . $booking['ghe_id'] .
                                                                                                    "\nGiờ chiếu: " . $booking['gio_chieu']);
                                                                                                ?>" alt="QR Code">
                    </div>

                    <div class="mt-4">
                        <a href="formtrangchu02.php" class="btn btn-primary me-2">Về trang chủ</a>
                        <a href="lichsudatve.php" class="btn btn-outline-primary">Xem lịch sử đặt vé</a>
                    </div>

                    <div class="mt-4 text-muted small">
                        <p>* Vui lòng lưu lại mã QR để check-in tại rạp.</p>
                        <p>* Đến trước giờ chiếu 15-30 phút để không bỏ lỡ phần đầu của phim.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .success-checkmark {
        margin-bottom: 20px;
    }

    .booking-details {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
    }

    .qr-code {
        padding: 15px;
        background: white;
        display: inline-block;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .qr-code img {
        max-width: 150px;
        height: auto;
    }
</style>

<?php include "footer.php"; ?>