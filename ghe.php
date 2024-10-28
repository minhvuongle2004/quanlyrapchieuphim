<?php
session_start();
include 'connectiondb.php'; // Kết nối database

// Xác định trang hiện tại và số ghế trên mỗi trang
$limit = 15;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Đếm tổng số ghế với điều kiện tìm kiếm
$totalSeatsQuery = "SELECT COUNT(*) as total FROM ghe WHERE ghe_id LIKE '%$search%' OR phong_id LIKE '%$search%' OR hang_ghe LIKE '%$search%'";
$totalSeats = $conn->query($totalSeatsQuery)->fetch_assoc()['total'];
$totalPages = ceil($totalSeats / $limit);

// Lấy danh sách ghế cho trang hiện tại với điều kiện tìm kiếm
$resultQuery = "SELECT * FROM ghe WHERE ghe_id LIKE '%$search%' OR phong_id LIKE '%$search%' OR hang_ghe LIKE '%$search%' LIMIT $limit OFFSET $offset";
$result = $conn->query($resultQuery);

// Thêm ghế
if (isset($_POST['add'])) {
    $ghe_id = $_POST['ghe_id'];
    $phong_id = $_POST['phong_id'];
    $hang_ghe = $_POST['hang_ghe'];

    $sql = "INSERT INTO ghe (ghe_id, phong_id, hang_ghe) VALUES ('$ghe_id', '$phong_id', '$hang_ghe')";
    $conn->query($sql);
    header("Location: ghe.php?page=$page&search=$search");
}

// Cập nhật ghế
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $ghe_id = $_POST['ghe_id'];
    $phong_id = $_POST['phong_id'];
    $hang_ghe = $_POST['hang_ghe'];

    $sql = "UPDATE ghe SET ghe_id='$ghe_id', phong_id='$phong_id', hang_ghe='$hang_ghe' WHERE id=$id";
    $conn->query($sql);
    header("Location: ghe.php?page=$page&search=$search");
}

// Xóa ghế
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM ghe WHERE id=$id");
    header("Location: ghe.php?page=$page&search=$search");
}
?>

<title>Quản lý Ghế</title>
<?php include 'header.php'; ?>

<div class="container my-5" id="quanLyGhe">
    <h1 class="text-center" style="color: darkblue;">Quản Lý Ghế</h1>

    <h6>Thêm/Sửa Ghế</h6>
    <form id="addSeatForm" method="POST">
        <input type="hidden" name="id" id="id">
        <input type="text" name="ghe_id" id="ghe_id" class="form-control mb-2 mr-sm-2" placeholder="ID Ghế" required>
        <input type="text" name="phong_id" id="phong_id" class="form-control mb-2 mr-sm-2" placeholder="ID Phòng" required>
        <input type="text" name="hang_ghe" id="hang_ghe" class="form-control mb-2 mr-sm-2" placeholder="Hàng Ghế" required>
        <button type="submit" name="add" class="btn btn-primary" style="background-color: red;">Thêm</button>
    </form>

    <div class="mb-3">
        <form method="GET" action="ghe.php">
            <input type="text" name="search" id="searchSeatInput" class="form-control" placeholder="Tìm kiếm ghế..." value="<?php echo htmlspecialchars($search); ?>">
            <input type="hidden" name="page" value="1">
            <button type="submit" class="btn btn-primary mt-2">Tìm kiếm</button>
        </form>
    </div>

    <table class="table table-bordered" id="seatTable">
        <thead>
            <tr>
                <th>ID Ghế</th>
                <th>ID Phòng</th>
                <th>Hàng Ghế</th>
                <th>Tình Trạng</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['ghe_id']; ?></td>
                    <td><?php echo $row['phong_id']; ?></td>
                    <td><?php echo $row['hang_ghe']; ?></td>
                    <td><?php echo $row['tinh_trang']; ?></td>
                    <td>
                        <a href="?edit=<?php echo $row['id']; ?>&page=<?php echo $page; ?>&search=<?php echo urlencode($search); ?>" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="?delete=<?php echo $row['id']; ?>&page=<?php echo $page; ?>&search=<?php echo urlencode($search); ?>" class="btn btn-danger btn-sm">Xóa</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Phân trang -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php
            $start = max(1, $page - 2);
            $end = min($totalPages, $page + 2);

            if ($page > 1) {
                echo '<li class="page-item"><a class="page-link" href="ghe.php?page=' . ($page - 1) . '&search=' . urlencode($search) . '">Trước</a></li>';
            }

            for ($i = $start; $i <= $end; $i++) {
                echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '"><a class="page-link" href="ghe.php?page=' . $i . '&search=' . urlencode($search) . '">' . $i . '</a></li>';
            }

            if ($page < $totalPages) {
                echo '<li class="page-item"><a class="page-link" href="ghe.php?page=' . ($page + 1) . '&search=' . urlencode($search) . '">Sau</a></li>';
            }
            ?>
        </ul>
    </nav>

    
</div>

<script>
    const editButtons = document.querySelectorAll('a[href*="edit"]');
    editButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const row = this.closest('tr');
            const cells = row.getElementsByTagName('td');
            document.getElementById('id').value = this.getAttribute('href').split('=')[1];
            document.getElementById('ghe_id').value = cells[0].innerText;
            document.getElementById('phong_id').value = cells[1].innerText;
            document.getElementById('hang_ghe').value = cells[2].innerText;
            document.querySelector('button[name="add"]').name = 'edit';
        });
    });
</script>

<?php include 'footer.php'; ?>