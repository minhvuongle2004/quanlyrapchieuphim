<?php include 'connectiondb.php';
session_start();


if (isset($_POST['add'])) {
    $ncc_id = $_POST['ncc_id'];
    $ten_nha_cung_cap = $_POST['ten_nha_cung_cap'];
    $dia_chi = $_POST['dia_chi'];
    $sdt = $_POST['sdt'];
    $email = $_POST['email'];
    $phim_id = $_POST['phim_id'];

    $sql = "INSERT INTO nhacungcapphim (ncc_id, ten_nha_cung_cap, dia_chi, sdt, email, phim_id) 
            VALUES ('$ncc_id','$ten_nha_cung_cap', '$dia_chi', '$sdt', '$email', '$phim_id')";
    $conn->query($sql);
}
if (isset($_POST['edit'])) {
    $ncc_id = $_POST['ncc_id'];
    $ten_nha_cung_cap = $_POST['ten_nha_cung_cap'];
    $dia_chi = $_POST['dia_chi'];
    $sdt = $_POST['sdt'];
    $email = $_POST['email'];

    $sql = "UPDATE nhacungcapphim 
            SET ten_nha_cung_cap='$ten_nha_cung_cap', dia_chi='$dia_chi', 
                sdt='$sdt', email='$email'
            WHERE ncc_id = '$ncc_id' ";
    $conn->query($sql);
}
if (isset($_GET['delete'])) {
    $ncc_id = $_GET['delete'];
    $conn->query("DELETE FROM nhacungcapphim WHERE ncc_id = '$ncc_id'");
}
$result = $conn->query("SELECT * FROM nhacungcapphim")
?>
<title>Quản lý Rạp Phim</title>

<?php include 'header.php' ?>
<!-- Nội dung nguồn cung cấp phim -->
<div class="container my-5" id="nguonPhim">
    <h1 class="text-center" style="color: darkblue;">Nguồn Cung Cấp Phim</h1>

    <h5 class="mt-4">Thêm/Sửa Nhà Cung Cấp Phim Mới</h5>
    <form action="" method="post" id="employeeForm">
        <input type="hidden" name="action" id="formAction" value="add">
        <div class="mb-3">
    
            <input type="text" name="ncc_id" class="form-control" id="ncc_id" placeholder="Nhập ID nhà cung cấp" required>
        </div>
        <div class="mb-3">
            <input type="text" name="ten_nha_cung_cap" class="form-control" id="ten_nha_cung_cap" placeholder="Nhập tên nhà cung cấp" required>
        </div>
        <div class="mb-3">
 
            <input type="text" name="dia_chi" class="form-control" id="dia_chi" placeholder="Nhập địa chỉ" required>
        </div>
        <div class="mb-3">
      
            <input type="tel" name="sdt" class="form-control" id="sdt" placeholder="Nhập số điện thoại" required>
        </div>
        <div class="mb-3">
         
            <input type="email" name="email" class="form-control" id="email" placeholder="Nhập email" required>
        </div>
        <button type="submit" name="add" id="submitBtn" class="btn btn-primary">Thêm </button>
    </form>

    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm nhà cung cấp...">
    </div>
    <div class="mb-4">
        <h5>Danh Sách Các Nhà Cung Cấp Phim</h5>
        <table class="table table-bordered" id="employeeTable">
            <thead>
                <tr>
                    <th>ID Nhà Cung Cấp</th>
                    <th>Tên Nhà Cung Cấp</th>
                    <th>Địa Chỉ</th>
                    <th>Số Điện Thoại</th>
                    <th>Email</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['ncc_id']; ?></td>
                        <td><?php echo $row['ten_nha_cung_cap']; ?></td>
                        <td><?php echo $row['dia_chi']; ?></td>
                        <td><?php echo $row['sdt']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['ncc_id']; ?>">Sửa</button>
                            <a href="?delete=<?php echo $row['ncc_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>

    

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

                document.getElementById('ncc_id').value = cells[0].innerText;
                document.getElementById('ten_nha_cung_cap').value = cells[1].innerText;
                document.getElementById('dia_chi').value = cells[2].innerText;
                document.getElementById('sdt').value = cells[3].innerText;
                document.getElementById('email').value = cells[4].innerText;


                formAction.value = 'edit';
                submitBtn.textContent = 'Cập nhật';
                submitBtn.name = 'edit';
                document.getElementById('ncc_id').readOnly = true;
            });
        });

        form.addEventListener('reset', function() {
            formAction.value = 'add';
            submitBtn.textContent = 'Thêm ';
            submitBtn.name = 'add';
            document.getElementById('ncc_id').readOnly = false;
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