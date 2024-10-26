<?php
header('Content-Type: application/json');
session_start();
include 'connectiondb.php';

if (!isset($_SESSION['KhachHangID'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit;
}

if (!isset($_POST['suat_id']) || !isset($_POST['ghe_ids'])) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin đặt vé']);
    exit;
}

$suat_id = $_POST['suat_id'];
$ghe_ids = json_decode($_POST['ghe_ids']);
$khach_hang_id = $_SESSION['KhachHangID'];

try {
    // Bắt đầu transaction
    $conn->begin_transaction();
    
    // Kiểm tra xem ghế đã được đặt chưa
    $sql_check = "SELECT ghe_id FROM ve WHERE suat_id = ? AND ghe_id IN (" . 
                 str_repeat('?,', count($ghe_ids) - 1) . "?)";
    $stmt_check = $conn->prepare($sql_check);
    $params = array_merge([$suat_id], $ghe_ids);
    $types = str_repeat('s', count($params));
    $stmt_check->bind_param($types, ...$params);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    
    if ($result->num_rows > 0) {
        throw new Exception('Một số ghế đã được đặt. Vui lòng chọn ghế khác.');
    }
    
    // Lấy giá vé từ suất chiếu
    $sql_price = "SELECT gia FROM suatchieu WHERE suat_id = ?";
    $stmt_price = $conn->prepare($sql_price);
    $stmt_price->bind_param("s", $suat_id);
    $stmt_price->execute();
    $price_result = $stmt_price->get_result();
    $price_row = $price_result->fetch_assoc();
    $gia_ve = $price_row['gia'];

    // Tạo booking_id
    $booking_id = 'BK' . date('YmdHis') . rand(1000, 9999);

    // Thêm vé cho từng ghế
    $sql_insert = "INSERT INTO ve (ve_id, suat_id, ghe_id, KhachHangID, gia_ve, booking_id, ngay_mua) 
                   VALUES (?, ?, ?, ?, ?, ?, NOW())";
    
    foreach($ghe_ids as $ghe_id) {
        $ve_id = 'V' . date('YmdHis') . rand(1000, 9999);
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssssss", $ve_id, $suat_id, $ghe_id, $khach_hang_id, $gia_ve, $booking_id);
        $stmt_insert->execute();
    }

    // Commit transaction
    $conn->commit();
    
    echo json_encode([
        'success' => true, 
        'booking_id' => $booking_id,
        'message' => 'Đặt vé thành công'
    ]);
    
} catch (Exception $e) {
    // Rollback nếu có lỗi
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}