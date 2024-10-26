<title>Quản lý Rạp Phim</title>

<?php
session_start();
include 'header.php';
if (isset($_POST['add'])) {
    $phim_id = $_POST['phong_id'];
    $ten_phim = $_POST['ten_phong'];
    $the_loai = $_POST['tong_ghe'];

    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    $sql = "INSERT INTO phongchieu  (phim_id, ten_phong, tong_ghe) 
            VALUES (?, ?, ?)";

    // Sử dụng Prepared Statement để tránh SQL injection
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param(
            "ssi",
            $phong_id,
            $ten_phong,
            $tong_ghe,
        );

        if ($stmt->execute()) {
            echo "<script>alert('Thêm phòng chiếu thành công'); window.location.href='phongchieu.php';</script>";
        } else {
            echo "Lỗi khi thêm phim: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Lỗi prepared statement: " . $conn->error;
    }
}
if (isset($_POST['edit'])) {
    $phong_id = $_POST['phong_id'];
    $ten_phong = $_POST['ten_phong'];
    $tong_ghe = $_POST['tong_ghe'];
    $sql = "UPDATE phongchieu
            SET ten_phong='$ten_phong', tong_ghe='$tong_ghe'
            WHERE phong_id='$phong_id'";
    if ($conn->query($sql)) {
        echo "<script>alert('Sửa phòng chiếu thành công'); window.location.href='phongchieu.php';</script>";
    } else {
        echo "<script>alert('Có lỗi khi sửa phòng chiếu'); window.location.href='phongchieu.php';</script>" . $conn->error;
    }
}
if (isset($_GET['delete'])) {
    $phong_id = $_GET['delete'];
    $sql = "DELETE FROM phongchieu WHERE phong_id='$phong_id'";
    if ($conn->query($sql)) {
        echo "<script>alert('XÓa phòng chiếu thành công'); window.location.href='phongchieu.php';</script>";
    } else {
        echo "<script>alert('Có lỗi khi xóa phòng chiếu'); window.location.href='phongchieu.php';</script>"  . $conn->error;
    }
}
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}
$sql = "SELECT * FROM phongchieu WHERE phong_id LIKE '%$search%'";
$result = $conn->query($sql);
?>


<div class="container my-5" id="phongChieu">
    <h1 class="text-center" style="color: darkblue;">Thông Tin Phòng Chiếu</h1>

    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm phòng chiếu...">
    </div>

    <table class="table table-bordered" id="employeeTable">
        <thead>
            <tr>
                <th>ID Phòng</th>
                <th>Tên Phòng</th>
                <th>Tổng Ghế</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['phong_id'] ?></td>
                    <td><?= $row['ten_phong'] ?></td>
                    <td><?= $row['tong_ghe'] ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['phong_id']; ?>">Sửa</button>
                        <a href="?delete=<?php echo $row['phong_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa phòng này?')">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h6>Thêm Phòng Chiếu Mới</h6>
    <form method="POST" action="" id="employeeForm" class="form-inline">
        <input type="hidden" name="action" id="formAction" value="add">
        <input type="text" name="phong_id" id="phong_id" class="form-control mb-2 mr-sm-2" placeholder="ID Phòng" required>
        <input type="text"  name="ten_phong" id="ten_phong" class="form-control mb-2 mr-sm-2" placeholder="Tên Phòng" required>
        <input type="number" name="tong_ghe" id="tong_ghe" class="form-control mb-2 mr-sm-2" placeholder="Tổng Ghế" required>
        <button type="submit" name="add" value="add" class="btn btn-primary" id="submitBtn" style="background-color: red;">Thêm</button>
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

                document.getElementById('phong_id').value = cells[0].innerText;
                document.getElementById('ten_phong').value = cells[1].innerText;
                document.getElementById('tong_ghe').value = cells[2].innerText;
               
                formAction.value = 'edit';
                submitBtn.textContent = 'Cập nhật phòng chiếu';
                submitBtn.name = 'edit';
                document.getElementById('phong_id').readOnly = true;
            });
        });

        form.addEventListener('reset', function() {
            formAction.value = 'add';
            submitBtn.textContent = 'Thêm phòng chiếu';
            submitBtn.name = 'add';
            document.getElementById('phong_id').readOnly = false;
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