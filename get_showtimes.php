<?php
// Remove HTML comments and ensure no whitespace before <?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'connectiondb.php';

// Set proper JSON header before any output
header('Content-Type: application/json');

// Validate input parameters
if (!isset($_GET['phim_id']) || !isset($_GET['ngay_chieu'])) {
    echo json_encode(['error' => 'Missing parameters']);
    exit();
}

$phim_id = $_GET['phim_id'];
$ngay_chieu = $_GET['ngay_chieu'];

// Format date from YYYYMMDD to YYYY-MM-DD
$formatted_date = date('Y-m-d', strtotime($ngay_chieu));

try {
    // Prepare and execute query
    $sql = "SELECT suat_id, gio_chieu, gia, phong_id 
            FROM suatchieu 
            WHERE phim_id = ? 
            AND DATE(gio_chieu) = ?
            ORDER BY gio_chieu";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $phim_id, $formatted_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $showtimes = [];
    while ($row = $result->fetch_assoc()) {
        $showtimes[] = [
            'suat_id' => $row['suat_id'],
            'gio_chieu' => date('H:i', strtotime($row['gio_chieu'])),
            'gia' => (int)$row['gia'], // Đảm bảo giá là số nguyên
            'phong_id' => $row['phong_id']
        ];
    }

    echo json_encode($showtimes);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}