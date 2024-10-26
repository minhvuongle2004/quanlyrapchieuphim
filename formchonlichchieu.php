<?php
session_start(); // Bắt đầu session

// Kiểm tra đăng nhập
if (!isset($_SESSION['KhachHangID'])) {
    header("Location: ../chieuphim/login.php");
    exit();
}

$KhachHangID = $_SESSION['KhachHangID'];
$ho_ten = $_SESSION['ho_ten'];

// Include header với Bootstrap
?>
<!DOCTYPE html>
<html lang="vi">


<title>Đặt vé</title>
<style>
    .welcome-banner {
        background-color: #f8f9fa;
        padding: 10px 20px;
        margin-bottom: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .customer-info {
        color: #666;
        font-size: 0.9em;
    }
</style>


<?php
include "header.php";

if (!isset($_GET['phim_id'])) {
    header('Location: formtrangchu02.php');
    exit();
}

$phim_id = $_GET['phim_id'];

// Lấy thông tin phim
include 'connectiondb.php';
$sql_phim = "SELECT * FROM phim WHERE phim_id = ?";
$stmt = $conn->prepare($sql_phim);
$stmt->bind_param("s", $phim_id);
$stmt->execute();
$result_phim = $stmt->get_result();
$phim = $result_phim->fetch_assoc();

if (!$phim) {
    header('Location: formtrangchu02.php');
    exit();
}
?>

<div class="container mt-4">
    <!-- Banner chào mừng -->
    <div class="welcome-banner">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Xin chào, <?php echo htmlspecialchars($ho_ten); ?></h5>
            <div class="customer-info">
                ID: <?php echo htmlspecialchars($KhachHangID); ?>
            </div>
        </div>
    </div>

    <h2 class="text-center"><?php echo htmlspecialchars($phim['ten_phim']); ?></h2>
    <div class="row">
        <div class="col-md-4">
            <img src="<?php echo htmlspecialchars($phim['img_url']); ?>"
                class="img-fluid rounded"
                alt="<?php echo htmlspecialchars($phim['ten_phim']); ?>">
        </div>
        <div class="col-md-8">
            <?php include "chonngay.php"; ?>
        </div>
    </div>
</div>

<?php include "chonghe.php"; ?>

<script>
    function getSelectDay(cinemaId, selectedDate, event) { // Thêm tham số event
        // Cập nhật giao diện các ngày đã chọn
        document.querySelectorAll('.toggle-tabs li').forEach(li => {
            li.classList.remove('current');
        });

        // Chỉ thực hiện việc tìm và thêm class khi có event
        if (event && event.target) {
            const dayElement = event.target.closest('.day') || event.target;
            if (dayElement) {
                const liElement = dayElement.closest('li');
                if (liElement) {
                    liElement.classList.add('current');
                }
            }
        }

        // Hiển thị khung giờ cho ngày đã chọn
        const showtimes = document.getElementById('showtimes');
        showtimes.style.display = 'block';

        const showtimesButtons = document.getElementById('showtimes-buttons');
        showtimesButtons.innerHTML = '';

        // Debug log
        console.log('Fetching showtimes for:', selectedDate);

        // Gọi API để lấy suất chiếu
        fetch(`get_showtimes.php?phim_id=<?php echo $_GET['phim_id']; ?>&ngay_chieu=${selectedDate}&KhachHangID=<?php echo $KhachHangID; ?>`)
            .then(response => response.json())
            .then(data => {
                console.log('Showtime data:', data);
                if (data.length === 0) {
                    showtimesButtons.innerHTML = '<p>Không có suất chiếu trong ngày này</p>';
                    return;
                }

                data.forEach(showtime => {
                    const button = document.createElement('button');
                    button.className = 'btn btn-primary m-2';
                    const gia = parseInt(showtime.gia);
                    button.textContent = `${showtime.gio_chieu} - ${gia.toLocaleString('vi-VN')}đ - Phòng ${showtime.phong_id}`;

                    button.onclick = () => {
                        openSeatSelection(showtime.suat_id, gia, showtime.phong_id);
                    };
                    showtimesButtons.appendChild(button);
                });
            })
            .catch(error => {
                console.error('Error fetching showtimes:', error);
                showtimesButtons.innerHTML = '<p>Đã có lỗi xảy ra khi tải suất chiếu</p>';
            });
    }
</script>

<?php include "footer.php"; ?>