<?php
include 'connectiondb.php';

$phong_id = $_GET['phong_id'];
$suat_id = $_GET['suat_id'];

// Lấy danh sách tất cả ghế của phòng
$sql_seats = "SELECT * FROM ghe WHERE phong_id = ? ORDER BY hang_ghe, ghe_id";
$stmt = $conn->prepare($sql_seats);
$stmt->bind_param("s", $phong_id);
$stmt->execute();
$result_seats = $stmt->get_result();
$seats = $result_seats->fetch_all(MYSQLI_ASSOC);

// Lấy danh sách ghế đã đặt của suất chiếu
$sql_booked = "SELECT ghe_id FROM ve WHERE suat_id = ?";
$stmt = $conn->prepare($sql_booked);
$stmt->bind_param("s", $suat_id);
$stmt->execute();
$result_booked = $stmt->get_result();
$bookedSeats = [];
while ($row = $result_booked->fetch_assoc()) {
    $bookedSeats[] = $row['ghe_id'];
}

// Trả về kết quả dưới dạng JSON
header('Content-Type: application/json');
echo json_encode([
    'seats' => $seats,
    'bookedSeats' => $bookedSeats
]);
