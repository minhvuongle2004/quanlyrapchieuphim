
<title>Quản lý Rạp Phim</title>
<style>
    /* CSS tùy chỉnh cho các button */
    .custom-btn {
        width: 200px;
        margin: 10px;
        padding: 15px;
        font-size: 18px;
        font-weight: bold;
        background-color: #007bff;
        color: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s, transform 0.2s;
    }

    .custom-btn:hover {
        background-color: #0056b3;
        transform: translateY(-3px);
    }

    .custom-btn i {
        margin-right: 8px;
    }
</style>
<?php
session_start();

include 'header.php' ?>


<div class="container my-5" id="heThongBanVe">
    <h1 class="text-center" style="color: darkblue;"></h1>
    <br><br><br>
    <div class="d-flex justify-content-center flex-wrap">
        <a href="phongchieu.php" class="custom-btn text-center" style="background-color: darkred;"><i class="fas fa-door-open"></i> Phòng chiếu</a>
        <a href="ghe.php" class="custom-btn text-center" style="background-color: darkred;"><i class="fas fa-chair"></i> Ghế</a>
    </div>
</div>

<?php
include 'footer.php';
?>