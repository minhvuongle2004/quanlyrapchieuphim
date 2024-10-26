<title>Quản lý Khách Hàng</title>
<?php 
session_start();

include 'header.php';

$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}
$sql = "SELECT * FROM khachhang WHERE KhachHangID LIKE '%$search%'";
$result = $conn->query($sql);
?>
<style>
    body {
        background-color: gainsboro;
    }
</style>

<div class="container my-5">
    <h1 class="text-center" style="color: darkred;">Danh Sách Khách Hàng</h1>

    <div class="mb-3">
        <input type="text" id="search" class="form-control" placeholder="Tìm kiếm khách hàng...">
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Họ Tên</th>
                <th>SĐT</th>
                <th>Email</th>
                <th>Mật Khẩu</th>
            </tr>
        </thead>
        <tbody id="customerTable">
        <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['KhachHangID'] ?></td>
                    <td><?= $row['ho_ten'] ?></td>
                    <td><?= $row['sdt'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['mat_khau'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    // JavaScript để xử lý tìm kiếm
    document.getElementById('search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#customerTable tr');

        rows.forEach(row => {
            const cells = row.getElementsByTagName('td');
            let isMatch = false;

            for (let i = 1; i < cells.length - 1; i++) { // Bỏ qua ID và Hành Động
                if (cells[i].textContent.toLowerCase().includes(searchTerm)) {
                    isMatch = true;
                    break;
                }
            }

            row.style.display = isMatch ? '' : 'none';
        });
    });
</script>

<?php include 'footer.php'; ?>
