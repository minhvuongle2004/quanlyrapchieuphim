<title>Quản lý nhân sự</title>
<?php 
session_start();
include 'header.php' ;

if (isset($_POST['add'])) {
    $chucvuID = $_POST['chucvuID'];
    $tenChucVu = $_POST['tenChucVu'];

    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    $sql = "INSERT INTO chucvu (chucvuID, tenChucVu) 
            VALUES (?, ?)";

    // Sử dụng Prepared Statement để tránh SQL injection
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param(
            "is",
            $chucvuID,
            $tenChucVu,
           
        );

        if ($stmt->execute()) {
            echo "<script>alert('Thêm  thành công'); window.location.href='chucvu.php';</script>";

        } else {
            echo "Lỗi : " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Lỗi prepared statement: " . $conn->error;
    }
}
if (isset($_GET['delete'])) {
    $chucvuID = $_GET['delete'];
    $sql = "DELETE FROM chucvu WHERE chucvuID='$chucvuID'";
    if($conn->query($sql)){
        echo "<script>alert('Xóa  thành công'); window.location.href='chucvu.php';</script>";
    }else{
        echo "<script>alert('Có lỗi'); window.location.href='chucvu.php';</script>"  . $conn->error;
    }
}


$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}
$sql = "SELECT * FROM chucvu WHERE chucvuID LIKE '%$search%'";
$result = $conn->query($sql);
?>

<div class="container my-5" id="quanLyChucVu">
    <h1 class="text-center" style="color: darkblue;">Quản Lý Chức Vụ</h1>

    <div class="mb-4">
        <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm chức vụ...">
    </div>
    <h3>Danh Sách Chức Vụ</h3>
    <table class="table table-striped" id="employeeTable">
        <thead>
            <tr>
                <th>ID Chức Vụ</th>
                <th>Tên Chức Vụ</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody id="tableChucVu">
        <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['chucvuID'] ?></td>
                    <td><?= $row['tenChucVu'] ?></td>
                    <td>
                        <a href="?delete=<?php echo $row['chucvuID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa chức vụ này?')">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div class="mb-4">
        <h3>Thêm Chức Vụ Mới</h3>
        <form method="post" action="" id="employeeForm" class="form-inline">
        <div class="mb-3">
                <label for="chucvuID" class="form-label">ID Chức Vụ</label>
                <input type="text" name="chucvuID" class="form-control" id="chucvuID" required>
            </div>
            <div class="mb-3">
                <label for="tenChucVu" class="form-label">Tên Chức Vụ</label>
                <input type="text" name="tenChucVu" class="form-control" id="tenChucVu" required>
            </div>
            <button type="submit" name="add" value="add" class="btn btn-primary" id="submitBtn" >Thêm </button>
            </form>
    </div>

    
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('employeeForm');
        const submitBtn = document.getElementById('submitBtn');
        const formAction = document.getElementById('formAction');

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