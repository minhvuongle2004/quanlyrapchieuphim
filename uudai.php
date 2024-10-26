<title>Quản lý Rạp Phim</title>
<?php 
session_start(); 

if (!isset($_SESSION['KhachHangID'])) {
    header("Location: ../chieuphim/login.php");
    exit();
}
include 'connectiondb.php';
$result = $conn->query("SELECT * FROM khuyenmai");

include 'header.php' 

?>


<!-- Nội dung khuyến mại -->
<div class="container my-5" id="khuyenMai">
    <h1 class="text-center" style="color: darkblue;">Khuyến Mãi</h1>

    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm khuyến mãi...">
    </div>

    <h5 class="mt-4">Danh Sách Khuyến Mãi</h5>
    <table class="table table-bordered" id="employeeTable">
        <thead>
            <tr>
                <th>ID Khuyến Mãi</th>
                <th>Tên Khuyến Mãi</th>
                <th>Giảm Giá (%)</th>
                <th>Thời Gian Bắt Đầu</th>
                <th>Thời Gian Kết Thúc</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['km_id'] ?></td>
                    <td><?php echo $row['ten_khuyen_mai'] ?></td>
                    <td><?php echo $row['giam_gia'] ?></td>
                    <td><?php echo $row['ngay_bat_dau'] ?></td>
                    <td><?php echo $row['ngay_ket_thuc'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
include 'footer.php';
?>