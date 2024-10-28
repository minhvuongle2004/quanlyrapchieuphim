<?php
include 'connectiondb.php';
session_start();

// Xử lý các thao tác CRUD
if (isset($_POST['add'])) {
    $suat_id = $_POST['suat_id'];
    $phim_id = $_POST['phim_id'];
    $phong_id = $_POST['phong_id'];
    $gio_chieu = $_POST['gio_chieu'];
    $gia = $_POST['gia'];

    $sql = "INSERT INTO suatchieu (suat_id, phim_id, phong_id, gio_chieu, gia) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssd", $suat_id, $phim_id, $phong_id, $gio_chieu, $gia);
    $stmt->execute();
}

if (isset($_POST['edit'])) {
    $suat_id = $_POST['suat_id'];
    $phim_id = $_POST['phim_id'];
    $phong_id = $_POST['phong_id'];
    $gio_chieu = $_POST['gio_chieu'];
    $gia = $_POST['gia'];

    $sql = "UPDATE suatchieu SET phim_id = ?, phong_id = ?, gio_chieu = ?, gia = ? WHERE suat_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssds", $phim_id, $phong_id, $gio_chieu, $gia, $suat_id);
    $stmt->execute();
}

if (isset($_GET['delete'])) {
    $suat_id = $_GET['delete'];
    $sql = "DELETE FROM suatchieu WHERE suat_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $suat_id);
    $stmt->execute();
}

// Thiết lập phân trang
$records_per_page = 10; // Số bản ghi trên mỗi trang
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($page - 1) * $records_per_page;

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_condition = '';
if ($search != '') {
    $search_condition = "WHERE suat_id LIKE ? OR phim_id LIKE ? OR phong_id LIKE ?";
}

// Đếm tổng số bản ghi để tính số trang
$count_sql = "SELECT COUNT(*) as total FROM suatchieu " . $search_condition;
if ($search != '') {
    $stmt = $conn->prepare($count_sql);
    $search_param = "%$search%";
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
    $stmt->execute();
    $total_records = $stmt->get_result()->fetch_assoc()['total'];
} else {
    $total_records = $conn->query($count_sql)->fetch_assoc()['total'];
}

$total_pages = ceil($total_records / $records_per_page);

// Lấy dữ liệu cho trang hiện tại
$sql = "SELECT * FROM suatchieu " . $search_condition . " ORDER BY suat_id LIMIT ?, ?";
if ($search != '') {
    $stmt = $conn->prepare($sql);
    $search_param = "%$search%";
    $stmt->bind_param("sssii", $search_param, $search_param, $search_param, $start_from, $records_per_page);
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $start_from, $records_per_page);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<title>Quản lý Suất Chiếu</title>

<?php include 'header.php' ?>

<div class="container my-5" id="suatChieu">
    <h1 class="text-center" style="color: darkblue;">Quản lý Suất Chiếu</h1>

    <!-- Form thêm/sửa suất chiếu -->
    <h6>Thêm Suất Chiếu Mới</h6>
    <form method="post" action="" id="employeeForm" class="form-inline">
        <input type="hidden" name="action" id="formAction" value="add">
        <input type="text" name="suat_id" id="suat_id" class="form-control mb-2 mr-sm-2" placeholder="ID Suất" required>
        <input type="text" name="phim_id" id="phim_id" class="form-control mb-2 mr-sm-2" placeholder="ID Phim" required>
        <input type="text" name="phong_id" id="phong_id" class="form-control mb-2 mr-sm-2" placeholder="ID Phòng" required>
        <input type="datetime-local" name="gio_chieu" id="gio_chieu" class="form-control mb-2 mr-sm-2" required>
        <input type="number" name="gia" id="gia" class="form-control mb-2 mr-sm-2" placeholder="Giá (VNĐ)" required>

        <button type="submit" name="add" class="btn btn-primary" id="submitBtn" style="background-color: red;">Thêm suất chiếu</button>
    </form>

    <div class="mb-3">
        <form action="" method="GET" class="d-flex">
            <input type="text" name="search" id="searchInput" class="form-control" placeholder="Tìm kiếm suất chiếu..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary ms-2">Tìm kiếm</button>
        </form>
    </div>

    <table class="table table-bordered" id="employeeTable">
        <thead>
            <tr>
                <th>ID Suất</th>
                <th>ID Phim</th>
                <th>ID Phòng</th>
                <th>Giờ Chiếu</th>
                <th>Giá (VNĐ)</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['suat_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['phim_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['phong_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['gio_chieu']); ?></td>
                    <td><?php echo number_format($row['gia']); ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo htmlspecialchars($row['suat_id']); ?>">Sửa</button>
                        <a href="?delete=<?php echo htmlspecialchars($row['suat_id']); ?>&page=<?php echo $page; ?>&search=<?php echo urlencode($search); ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Bạn có chắc chắn muốn xóa suất chiếu này?')">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Phân trang -->
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php if($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=1&search=<?php echo urlencode($search); ?>">Đầu</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo ($page - 1); ?>&search=<?php echo urlencode($search); ?>">Trước</a>
                </li>
            <?php endif; ?>

            <?php
            // Hiển thị các số trang
            $start_page = max(1, $page - 2);
            $end_page = min($total_pages, $page + 2);

            for($i = $start_page; $i <= $end_page; $i++):
            ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo ($page + 1); ?>&search=<?php echo urlencode($search); ?>">Sau</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $total_pages; ?>&search=<?php echo urlencode($search); ?>">Cuối</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>


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

                document.getElementById('suat_id').value = cells[0].innerText;
                document.getElementById('phim_id').value = cells[1].innerText;
                document.getElementById('phong_id').value = cells[2].innerText;
                document.getElementById('gio_chieu').value = cells[3].innerText;
                document.getElementById('gia').value = cells[4].innerText.replace(/,/g, '');

                formAction.value = 'edit';
                submitBtn.textContent = 'Cập nhật suất chiếu';
                submitBtn.name = 'edit';
                document.getElementById('suat_id').readOnly = true;
            });
        });

        form.addEventListener('reset', function() {
            formAction.value = 'add';
            submitBtn.textContent = 'Thêm suất chiếu';
            submitBtn.name = 'add';
            document.getElementById('suat_id').readOnly = false;
        });
    });
</script>

<?php include 'footer.php'; ?>