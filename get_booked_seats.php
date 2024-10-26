<?php
header('Content-Type: application/json');
include 'connectiondb.php';

if (!isset($_GET['suat_id'])) {
    echo json_encode([]);
    exit;
}

$suat_id = $_GET['suat_id'];

// Lấy danh sách ghế đã đặt cho suất chiếu
$sql = "SELECT ghe_id FROM ve WHERE suat_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $suat_id);
$stmt->execute();
$result = $stmt->get_result();

$booked_seats = [];
while ($row = $result->fetch_assoc()) {
    $booked_seats[] = $row['ghe_id'];
}

echo json_encode($booked_seats);