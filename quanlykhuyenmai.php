<?php include 'connectiondb.php';
session_start();

if (isset($_POST['add'])) {
    $km_id = $_POST['km_id'];
    $ten_khuyen_mai = $_POST['ten_khuyen_mai'];
    $giam_gia = $_POST['giam_gia'];
    $ngay_bat_dau = $_POST['ngay_bat_dau'];
    $ngay_ket_thuc = $_POST['ngay_ket_thuc'];

    $sql = "INSERT INTO khuyenmai(km_id, ten_khuyen_mai, 
                        giam_gia, ngay_bat_dau, 
                        ngay_ket_thuc)
            VALUES ('$km_id', '$ten_khuyen_mai', '$giam_gia', 
                    '$ngay_bat_dau', '$ngay_ket_thuc')";
    $conn->query($sql);
}

if (isset($_GET['delete'])) {
    $id = $_GET['km_id'];
    $sql = "DELETE FROM khuyenmai WHERE km_id = '$id'";
    $conn->query($sql);
}
if (isset($_POST['edit'])) {
    $km_id = $_POST['km_id'];
    $ten_khuyen_mai = $_POST['ten_khuyen_mai'];
    $giam_gia = $_POST['giam_gia'];
    $ngay_bat_dau = $_POST['ngay_bat_dau'];
    $ngay_ket_thuc = $_POST['ngay_ket_thuc'];

    $sql = "UPDATE khuyenmai 
    SET ten_khuyen_mai = '$ten_khuyen_mai',
        giam_gia = '$giam_gia', ngay_bat_dau = '$ngay_bat_dau',
        ngay_ket_thuc = '$ngay_ket_thuc'
    WHERE km_id = '$km_id'";
    $conn->query($sql);
}
$result = $conn->query("SELECT * FROM khuyenmai");
?>

<title>Quản lý Rạp Phim</title>

<?php include 'header.php' ?>

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
                <th>Hành Động</th>
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
                    <td>
                        <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['km_id']; ?>">Sửa</button>
                        <a href="?delete=<?php echo $row['km_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h5 class="mt-4">Thêm Khuyến Mãi Mới</h5>
    <form id="promotionForm" method="post" action="" class="mt-3">
        <input type="hidden" name="action" id="formAction" value="add">
        <div class="mb-3">
            <label for="km_id" class="form-label">ID Khuyến Mãi</label>
            <input type="text" name="km_id" class="form-control" id="km_id" placeholder="Nhập ID khuyến mãi" required>
        </div>
        <div class="mb-3">
            <label for="tenKhuyenMai" class="form-label">Tên Khuyến Mãi</label>
            <input type="text" name="ten_khuyen_mai" class="form-control" id="ten_khuyen_mai" placeholder="Nhập tên khuyến mãi" required>
        </div>
        <div class="mb-3">
            <label for="giamGia" class="form-label">Giảm Giá (%)</label>
            <input type="number" name="giam_gia" class="form-control" id="giam_gia" placeholder="Nhập phần trăm giảm giá" required>
        </div>
        <div class="mb-3">
            <label for="thoiGianBatDau" class="form-label">Thời Gian Bắt Đầu</label>
            <input type="date" name="ngay_bat_dau" class="form-control" id="ngay_bat_dau" required>
        </div>
        <div class="mb-3">
            <label for="thoiGianKetThuc" class="form-label">Thời Gian Kết Thúc</label>
            <input type="date" name="ngay_ket_thuc" class="form-control" id="ngay_ket_thuc" required>
        </div>
        <button type="submit" name="add" id="submitBtn" class="btn btn-primary">Thêm Khuyến Mãi</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('promotionForm');
        const submitBtn = document.getElementById('submitBtn');
        const formAction = document.getElementById('formAction');
        const editBtns = document.querySelectorAll('.edit-btn');

        editBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const row = this.closest('tr');
                const cells = row.getElementsByTagName('td');

                document.getElementById('km_id').value = cells[0].innerText;
                document.getElementById('ten_khuyen_mai').value = cells[1].innerText;
                document.getElementById('giam_gia').value = cells[2].innerText;
                document.getElementById('ngay_bat_dau').value = cells[3].innerText;
                document.getElementById('ngay_ket_thuc').value = cells[4].innerText;


                formAction.value = 'edit';
                submitBtn.textContent = 'Cập Nhật Khuyến Mãi';
                submitBtn.name = 'edit';
                document.getElementById('km_id').readOnly = true;
            });
        });

        form.addEventListener('reset', function() {
            formAction.value = 'add';
            submitBtn.textContent = 'Thêm Khuyến Mãi';
            submitBtn.name = 'add';
            document.getElementById('km_id').readOnly = false;
        });

        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('employeeTable');
        const rows = table.getElementsByTagName('tr');

        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let found = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.innerHTML.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
                row.style.display = found ? '' : 'none';
            }
        });
    });
</script>
<?php include 'footer.php'; ?>