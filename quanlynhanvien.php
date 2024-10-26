<?php
include 'connectiondb.php';
session_start();

if (isset($_POST['add'])) {
    $NhanVienID = $_POST['NhanVienID'];
    $ho_ten = $_POST['ho_ten'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $sdt = $_POST['sdt'];
    $email = $_POST['email'];
    $dia_chi = $_POST['dia_chi'];
    $chucvuID = $_POST['chucvuID'];
    $mat_khau = $_POST['mat_khau'];
    $luong = $_POST['luong'];


    $sql = "INSERT INTO nhanvien (NhanVienID, ho_ten, ngay_sinh,
                        gioi_tinh, sdt, email, dia_chi, chucvuID,
                        mat_khau, luong) 
            VALUES ('$NhanVienID','$ho_ten', '$ngay_sinh', '$gioi_tinh',
                    '$sdt', '$email', '$dia_chi', '$chucvuID', '$mat_khau',
                    '$luong')";
    if($conn->query($sql)){
        echo "<script>alert('Thêm nhân viên thành công'); window.location.href='quanlynhanvien.php';</script>";
    }else{
        echo "<script>alert('Có lỗi khi thêm nhân viên'); window.location.href='phim.php';</script>" . $conn->error;
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM nhanvien WHERE NhanVienID = '$id'";
    if($conn->query($sql)){
        echo "<script>alert('Xóa nhân viên thành công'); window.location.href='quanlynhanvien.php';</script>";
    }else{
        echo "<script>alert('Có lỗi khi xóa nhân viên'); window.location.href='phim.php';</script>" . $conn->error;
    }
}
if (isset($_POST['edit'])) {
    $NhanVienID = $_POST['NhanVienID'];
    $ho_ten = $_POST['ho_ten'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $sdt = $_POST['sdt'];
    $email = $_POST['email'];
    $dia_chi = $_POST['dia_chi'];
    $chucvuID = $_POST['chucvuID'];
    $mat_khau = $_POST['mat_khau'];
    $luong = $_POST['luong'];

    $sql = "UPDATE nhanvien SET ho_ten='$ho_ten', ngay_sinh='$ngay_sinh', gioi_tinh='$gioi_tinh', 
            sdt='$sdt', email='$email', dia_chi='$dia_chi', chucvuID='$chucvuID', mat_khau='$mat_khau', luong='$luong' 
            WHERE NhanVienID='$NhanVienID'";
    if($conn->query($sql)){
        echo "<script>alert('Sửa nhân viên thành công'); window.location.href='quanlynhanvien.php';</script>";
    }else{
        echo "<script>alert('Có lỗi khi sửa nhân viên'); window.location.href='phim.php';</script>" . $conn->error;
    }
}
$result = $conn->query("SELECT * FROM nhanvien ORDER BY chucvuID");
?>
<title>Quản lý Rạp Phim</title>

<?php include 'header.php' ?>


<div class="container my-5" id="quanLyNhanVien">
    <h1 class="text-center" style="color: darkblue;">Thông Tin Nhân Viên</h1>

    <!-- Nút dẫn đến trang quản lý chức vụ -->
    <div class="text-center mb-4">
        <a href="chucvu.php" class="btn btn-success"> Chức Vụ</a>
    </div>

    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm nhân viên...">
    </div>

    <table class="table table-bordered" id="employeeTable">
        <thead>
            <tr>
                <th>ID Nhân Viên</th>
                <th>Họ Tên</th>
                <th>Ngày Sinh</th>
                <th>Giới Tính</th>
                <th>SĐT</th>
                <th>Email</th>
                <th>Địa Chỉ</th>
                <th>ID Chức Vụ</th>
                <th>Mật Khẩu</th>
                <th>Lương</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['NhanVienID']; ?></td>
                    <td><?php echo $row['ho_ten']; ?></td>
                    <td><?php echo $row['ngay_sinh']; ?></td>
                    <td><?php echo $row['gioi_tinh']; ?></td>
                    <td><?php echo $row['sdt']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['dia_chi']; ?></td>
                    <td><?php echo $row['chucvuID']; ?></td>
                    <td>******</td>
                    <td><?php echo $row['luong']; ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['NhanVienID']; ?>">Sửa</button>
                        <a href="?delete=<?php echo $row['NhanVienID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h2>Thêm/Sửa Nhân Viên </h2>
    <form method="post" action="" id="employeeForm" class="mt-3">
        <input type="hidden" name="action" id="formAction" value="add">
        <div class="row">
            <div class="col-md-6 mb-3">
                <input type="text" name="NhanVienID" id="NhanVienID" class="form-control" placeholder="ID Nhân Viên" required>
            </div>
            <div class="col-md-6 mb-3">
                <input type="text" name="ho_ten" id="ho_ten" class="form-control" placeholder="Họ Tên" required>
            </div>
            <div class="col-md-6 mb-3">
                <input type="date" name="ngay_sinh" id="ngay_sinh" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <select name="gioi_tinh" id="gioi_tinh" class="form-control" required>
                    <option value="" disabled selected>Chọn Giới Tính</option>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                    <option value="Khác">Khác</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <input type="text" name="sdt" id="sdt" class="form-control" placeholder="Số Điện Thoại" required>
            </div>
            <div class="col-md-6 mb-3">
                <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="col-md-6 mb-3">
                <input type="text" name="dia_chi" id="dia_chi" class="form-control" placeholder="Địa Chỉ" required>
            </div>
            <div class="col-md-6 mb-3">
                <input type="text" name="chucvuID" id="chucvuID" class="form-control" placeholder="ID Chức Vụ" required>
            </div>
            <div class="col-md-6 mb-3">
                <input type="password" name="mat_khau" id="mat_khau" class="form-control" placeholder="Mật Khẩu" required>
            </div>
            <div class="col-md-6 mb-3">
                <input type="number" name="luong" id="luong" class="form-control" placeholder="Lương" required min="0" step="100000">
            </div>
        </div>
        <button type="submit" name="add" id="submitBtn" class="btn btn-primary">Thêm Nhân Viên</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('employeeForm');
        const submitBtn = document.getElementById('submitBtn');
        const formAction = document.getElementById('formAction');
        const editBtns = document.querySelectorAll('.edit-btn');

        editBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const row = this.closest('tr');
                const cells = row.getElementsByTagName('td');

                document.getElementById('NhanVienID').value = cells[0].innerText;
                document.getElementById('ho_ten').value = cells[1].innerText;
                document.getElementById('ngay_sinh').value = cells[2].innerText;
                document.getElementById('gioi_tinh').value = cells[3].innerText;
                document.getElementById('sdt').value = cells[4].innerText;
                document.getElementById('email').value = cells[5].innerText;
                document.getElementById('dia_chi').value = cells[6].innerText;
                document.getElementById('chucvuID').value = cells[7].innerText;
                document.getElementById('luong').value = cells[9].innerText;
                document.getElementById('mat_khau').value = cells[10].innerText;


                formAction.value = 'edit';
                submitBtn.textContent = 'Cập Nhật Nhân Viên';
                submitBtn.name = 'edit';
                document.getElementById('NhanVienID').readOnly = true;
            });
        });

        form.addEventListener('reset', function() {
            formAction.value = 'add';
            submitBtn.textContent = 'Thêm Nhân Viên';
            submitBtn.name = 'add';
            document.getElementById('NhanVienID').readOnly = false;
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