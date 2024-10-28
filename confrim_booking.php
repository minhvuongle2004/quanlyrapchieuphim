<?php
session_start();

if (!isset($_SESSION['KhachHangID']) || !isset($_GET['booking_id'])) {
    header('Location: formtrangchu02.php');
    exit();
}

include 'connectiondb.php';

// Lấy thông tin vé và đặt chỗ
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

// Xử lý khi người dùng xác nhận thanh toán
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm_payment'])) {
        // Cập nhật trạng thái thanh toán
        $updateSql = "UPDATE ve SET trang_thai_thanh_toan = 'Đã thanh toán' WHERE booking_id = ? AND KhachHangID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ss", $_GET['booking_id'], $_SESSION['KhachHangID']);
        
        if ($updateStmt->execute()) {
            // Chuyển hướng đến trang booking success
            header("Location: booking_success.php?booking_id=" . $_GET['booking_id']);
            exit();
        }
    }
}

include "header.php";
?>
<title>Xác nhận đặt vé</title>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Xác nhận thông tin đặt vé</h4>
                </div>
                <div class="card-body">
                    <div class="booking-details">
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
                                <p><strong>Ghế đã chọn:</strong> <?php echo htmlspecialchars($booking['ghe_ids']); ?></p>
                                <p><strong>Tổng tiền:</strong> <?php echo number_format($booking['tong_gia_ve'], 0, ',', '.'); ?> VNĐ</p>
                            </div>
                        </div>

                        <div class="payment-info mt-4">
                            <h5>Thông tin thanh toán:</h5>
                            <div class="card">
                                <div class="card-body">
                                    <p><strong>Phương thức thanh toán:</strong> TRANSFER</p>
                                    <div class="bank-details">
                                        <p><strong>Thông tin chuyển khoản:</strong></p>
                                        <p>Ngân hàng: Viettinbank</p>
                                        <p>Số tài khoản: 106873937060</p>
                                        <p>Chủ tài khoản: Minh Vương cực đỉnhh</p>
                                        <p>Nội dung chuyển khoản: <?php echo htmlspecialchars($booking['booking_id']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form method="POST" class="mt-4">
                            <div class="d-flex justify-content-between">
                                <button type="submit" name="confirm_payment" class="btn btn-success btn-lg">
                                    <i class="fas fa-check-circle me-2"></i>Xác nhận thanh toán
                                </button>
                                <a href="change_payment.php?booking_id=<?php echo htmlspecialchars($_GET['booking_id']); ?>" class="btn btn-outline-primary btn-lg">
                                    <i class="fas fa-edit me-2"></i>Đổi phương thức thanh toán
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Lưu ý:</strong> Vui lòng thanh toán trong vòng 15 phút để giữ ghế. Sau thời gian này, đặt vé sẽ tự động hủy.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .booking-details {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
    }

    .bank-details {
        background-color: #fff;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #dee2e6;
    }

    .bank-details p {
        margin-bottom: 0.5rem;
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
    }
</style>

<?php include "footer.php"; ?>