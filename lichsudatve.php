<?php
include 'connectiondb.php';
session_start();

if (!isset($_SESSION['KhachHangID'])) {
    header("Location: login.php");
    exit();
}

// Xử lý hủy vé
if (isset($_GET['cancel'])) {
    $booking_id = $_GET['cancel'];

    // Kiểm tra thời gian chiếu và điều kiện hủy vé
    $check_sql = "SELECT v.*, sc.gio_chieu 
                  FROM ve v
                  JOIN suatchieu sc ON v.suat_id = sc.suat_id
                  WHERE v.booking_id = ? AND v.KhachHangID = ?
                  LIMIT 1";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $booking_id, $_SESSION['KhachHangID']);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $ticket = $result->fetch_assoc();

    if ($ticket) {
        $showtime = new DateTime($ticket['gio_chieu']);
        $now = new DateTime();
        $interval = $now->diff($showtime);
        $hours_difference = ($interval->days * 24) + $interval->h;

        if ($hours_difference >= 24) {
            // Thực hiện hủy tất cả vé trong cùng booking_id
            $sql = "DELETE FROM ve WHERE booking_id = ? AND KhachHangID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $booking_id, $_SESSION['KhachHangID']);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $success_message = "Hủy vé thành công!";
            } else {
                $error_message = "Không thể hủy vé!";
            }
        } else {
            $error_message = "Không thể hủy vé khi đã gần đến giờ chiếu (ít hơn 24 giờ)!";
        }
    } else {
        $error_message = "Không tìm thấy vé!";
    }
}

// Thiết lập phân trang
$records_per_page = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($page - 1) * $records_per_page;

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_condition = "WHERE v.KhachHangID = ?";
if ($search != '') {
    $search_condition .= " AND (v.ve_id LIKE ? OR p.ten_phim LIKE ? OR sc.gio_chieu LIKE ?)";
}

// Đếm tổng số bản ghi
$count_sql = "SELECT COUNT(*) as total 
              FROM ve v 
              JOIN suatchieu sc ON v.suat_id = sc.suat_id 
              JOIN phim p ON sc.phim_id = p.phim_id " . $search_condition;

$stmt = $conn->prepare($count_sql);
if ($search != '') {
    $search_param = "%$search%";
    $stmt->bind_param("ssss", $_SESSION['KhachHangID'], $search_param, $search_param, $search_param);
} else {
    $stmt->bind_param("s", $_SESSION['KhachHangID']);
}
$stmt->execute();
$total_records = $stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

// Lấy dữ liệu cho trang hiện tại
$sql = "SELECT v.ve_id, v.booking_id, p.ten_phim, sc.gio_chieu, sc.phong_id,
        GROUP_CONCAT(v.ghe_id ORDER BY v.ghe_id ASC) as ghe_ids,
        SUM(v.gia_ve) as tong_gia_ve, MIN(v.ngay_mua) as ngay_mua
        FROM ve v 
        JOIN suatchieu sc ON v.suat_id = sc.suat_id 
        JOIN phim p ON sc.phim_id = p.phim_id " .
    $search_condition .
    " GROUP BY v.booking_id, p.ten_phim, sc.gio_chieu, sc.phong_id 
          ORDER BY v.ngay_mua DESC LIMIT ?, ?";

$stmt = $conn->prepare($sql);
if ($search != '') {
    $search_param = "%$search%";
    $stmt->bind_param("sssii", $_SESSION['KhachHangID'], $search_param, $search_param, $search_param, $start_from, $records_per_page);
} else {
    $stmt->bind_param("sii", $_SESSION['KhachHangID'], $start_from, $records_per_page);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Lịch Sử Đặt Vé</title>
    <?php include 'header.php'; ?>
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Lịch Sử Đặt Vé</h1>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Form tìm kiếm -->
        <div class="mb-3">
            <form action="" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo mã vé, tên phim hoặc ngày chiếu..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary ms-2">Tìm kiếm</button>
            </form>
        </div>

        <!-- Bảng lịch sử đặt vé -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Mã Vé</th>
                        <th>Tên Phim</th>
                        <th>Thời Gian Chiếu</th>
                        <th>Phòng</th>
                        <th>Ghế</th>
                        <th>Giá Vé</th>
                        <th>Ngày Đặt</th>
                        <th>Trạng Thái</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()):
                        $showtime = new DateTime($row['gio_chieu']);
                        $now = new DateTime();
                        $interval = $now->diff($showtime);
                        $hours_difference = ($interval->days * 24) + $interval->h;
                        $can_cancel = $hours_difference >= 24;
                        $is_past = $now > $showtime;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['ten_phim']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row['gio_chieu'])); ?></td>
                            <td><?php echo htmlspecialchars($row['phong_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['ghe_ids']); ?></td>
                            <td><?php echo number_format($row['tong_gia_ve'], 0, ',', '.'); ?> VNĐ</td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row['ngay_mua'])); ?></td>
                            <td>
                                <?php if ($is_past): ?>
                                    <span class="badge bg-secondary">Đã chiếu</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Sắp chiếu</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$is_past && $can_cancel): ?>
                                    <a href="?cancel=<?php echo $row['booking_id']; ?>&page=<?php echo $page; ?>&search=<?php echo urlencode($search); ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn hủy các vé này?')">
                                        Hủy vé
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" disabled>Không thể hủy</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=1&search=<?php echo urlencode($search); ?>">Đầu</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo ($page - 1); ?>&search=<?php echo urlencode($search); ?>">Trước</a>
                    </li>
                <?php endif; ?>

                <?php
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);

                for ($i = $start_page; $i <= $end_page; $i++):
                ?>
                    <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo ($page + 1); ?>&search=<?php echo urlencode($search); ?>">Sau</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $total_pages; ?>&search=<?php echo urlencode($search); ?>">Cuối</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>