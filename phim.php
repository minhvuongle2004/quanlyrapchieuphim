<?php
session_start();
include 'connectiondb.php';

// Thêm phim
if (isset($_POST['add'])) {
    $phim_id = $_POST['phim_id'];
    $ten_phim = $_POST['ten_phim'];
    $the_loai = $_POST['the_loai'];
    $thoi_luong = $_POST['thoi_luong'];
    $dao_dien = $_POST['dao_dien'];
    $ngay_khoi_chieu = $_POST['ngay_khoi_chieu'];
    $ngay_ket_thuc = $_POST['ngay_ket_thuc'];
    $img_url = $_POST['img_url'];

    // Thêm code kiểm tra và hiển thị lỗi
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    $sql = "INSERT INTO phim (phim_id, ten_phim, the_loai, thoi_luong, dao_dien, 
            ngay_khoi_chieu, ngay_ket_thuc, img_url) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Sử dụng Prepared Statement để tránh SQL injection
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param(
            "sssissss",
            $phim_id,
            $ten_phim,
            $the_loai,
            $thoi_luong,
            $dao_dien,
            $ngay_khoi_chieu,
            $ngay_ket_thuc,
            $img_url
        );

        if ($stmt->execute()) {
            echo "<script>alert('Thêm phim thành công'); window.location.href='phim.php';</script>";

        } else {
            echo "Lỗi khi thêm phim: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Lỗi prepared statement: " . $conn->error;
    }
}

// Sửa phim
if (isset($_POST['edit'])) {
    $phim_id = $_POST['phim_id'];
    $ten_phim = $_POST['ten_phim'];
    $the_loai = $_POST['the_loai'];
    $thoi_luong = $_POST['thoi_luong'];
    $dao_dien = $_POST['dao_dien'];
    $ngay_khoi_chieu = $_POST['ngay_khoi_chieu'];
    $ngay_ket_thuc = $_POST['ngay_ket_thuc'];
    $img_url = $_POST['img_url'];

    $sql = "UPDATE phim
            SET ten_phim='$ten_phim', the_loai='$the_loai', thoi_luong='$thoi_luong', 
                dao_dien='$dao_dien', ngay_khoi_chieu='$ngay_khoi_chieu', 
                ngay_ket_thuc='$ngay_ket_thuc', img_url = '$img_url'
            WHERE phim_id='$phim_id'";
    if($conn->query($sql)){
        echo "<script>alert('Sửa phim thành công'); window.location.href='phim.php';</script>";
    }else{
        echo "<script>alert('Có lỗi khi sửa phim'); window.location.href='phim.php';</script>" . $conn->error;
    }
}

// Xóa phim
if (isset($_GET['delete'])) {
    $phim_id = $_GET['delete'];
    $sql = "DELETE FROM phim WHERE phim_id='$phim_id'";
    if($conn->query($sql)){
        echo "<script>alert('XÓa phim thành công'); window.location.href='phim.php';</script>";
    }else{
        echo "<script>alert('Có lỗi khi xóa phim'); window.location.href='phim.php';</script>"  . $conn->error;
    }
}

// Tìm kiếm phim
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}
$sql = "SELECT * FROM phim WHERE phim_id LIKE '%$search%'";
$result = $conn->query($sql);
include 'header.php'
?>

<title>Quản lý Rạp Phim</title>

<!-- Quản lý phim -->
<div class="container my-5" id="quanLyPhim">
    <h1 class="text-center" style="color: darkblue;">Quản Lý Phim</h1>

    <!-- Tìm kiếm phim -->
    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm phim...">
    </div>

    <!-- Bảng danh sách phim -->
    <table class="table table-bordered" style="width: max-content;" id="employeeTable">
        <thead>
            <tr>
                <th>Mã Phim</th>
                <th>Tên Phim</th>
                <th>Thể Loại</th>
                <th>Thời Lượng</th>
                <th>Đạo Diễn</th>
                <th>Ngày Khởi Chiếu</th>
                <th>Ngày Kết Thúc</th>
                <th>Url hình ảnh</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['phim_id'] ?></td>
                    <td><?= $row['ten_phim'] ?></td>
                    <td><?= $row['the_loai'] ?></td>
                    <td><?= $row['thoi_luong'] ?></td>
                    <td><?= $row['dao_dien'] ?></td>
                    <td><?= $row['ngay_khoi_chieu'] ?></td>
                    <td><?= $row['ngay_ket_thuc'] ?></td>
                    <td>
                        <?php
                        $img_url = $row['img_url'];
                        $short_url = (strlen($img_url) > 12) ? substr($img_url, 0, 12) . '...' : $img_url;
                        ?>
                        <a href="<?= $img_url ?>" target="_blank" title="<?= $img_url ?>"><?= $short_url ?></a>
                    </td>

                    <td>
                        <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['phim_id']; ?>">Sửa</button>
                        <a href="?delete=<?php echo $row['phim_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa phòng này?')">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Form thêm phim mới -->
    <h6>Thêm Phim Mới</h6>
    <form method="post" action="" id="employeeForm" class="form-inline">
        <input type="hidden" name="action" id="formAction" value="add">
        <input type="text" name="phim_id" id="phim_id" class="form-control mb-2 mr-sm-2" placeholder="ID Phim" required pattern="[A-Za-z0-9]+" title="Chỉ cho phép chữ và số">
        <input type="text" name="ten_phim" id="ten_phim" class="form-control mb-2 mr-sm-2" placeholder="Tên Phim" required>
        <input type="text" name="the_loai" id="the_loai" class="form-control mb-2 mr-sm-2" placeholder="Thể Loại" required>
        <input type="number" name="thoi_luong" id="thoi_luong" class="form-control mb-2 mr-sm-2" placeholder="Thời Lượng" required min="1">
        <input type="text" name="dao_dien" id="dao_dien" class="form-control mb-2 mr-sm-2" placeholder="Đạo Diễn" required>
        <input type="date" name="ngay_khoi_chieu" id="ngay_khoi_chieu" class="form-control mb-2 mr-sm-2" required>
        <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc" class="form-control mb-2 mr-sm-2" required>
        <input type="url" name="img_url" id="img_url" class="form-control mb-2 mr-sm-2" placeholder="URL hình ảnh" required>
        <button type="submit" name="add" value="add" class="btn btn-primary" id="submitBtn" style="background-color: red;">Thêm phim</button>
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

                document.getElementById('phim_id').value = cells[0].innerText;
                document.getElementById('ten_phim').value = cells[1].innerText;
                document.getElementById('the_loai').value = cells[2].innerText;
                document.getElementById('thoi_luong').value = cells[3].innerText;
                document.getElementById('dao_dien').value = cells[4].innerText;
                document.getElementById('ngay_khoi_chieu').value = cells[5].innerText;
                document.getElementById('ngay_ket_thuc').value = cells[6].innerText;
                document.getElementById('img_url').value = cells[7].innerText;



                formAction.value = 'edit';
                submitBtn.textContent = 'Cập nhật phim';
                submitBtn.name = 'edit';
                document.getElementById('phim_id').readOnly = true;
            });
        });

        form.addEventListener('reset', function() {
            formAction.value = 'add';
            submitBtn.textContent = 'Thêm phim';
            submitBtn.name = 'add';
            document.getElementById('phim_id').readOnly = false;
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
<?php
include 'footer.php';
?>