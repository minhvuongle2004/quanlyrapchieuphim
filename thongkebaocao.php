<title>Quản lý Rạp Phim</title>

<?php 
session_start();

include 'header.php' ?>


<!-- Nội dung Thống Kê và Báo Cáo -->
<div class="container my-5" id="thongKeBaoCao">
    <h1 class="text-center" style="color: darkblue;">Thống Kê </h1>

    <div class="row">
        <div class="col-md-6">
            <canvas id="revenueChart"></canvas>
        </div>
        <div class="col-md-6">
            <h5>Báo Cáo Doanh Thu</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tháng</th>
                        <th>Doanh Thu (VNĐ)</th>
                        <th>Số Vé Bán Ra</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tháng 1</td>
                        <td>10,000,000</td>
                        <td>150</td>
                    </tr>
                    <tr>
                        <td>Tháng 2</td>
                        <td>15,000,000</td>
                        <td>200</td>
                    </tr>
                    <tr>
                        <td>Tháng 3</td>
                        <td>20,000,000</td>
                        <td>250</td>
                    </tr>
                    <tr>
                        <td>Tháng 4</td>
                        <td>25,000,000</td>
                        <td>300</td>
                    </tr>
                    <tr>
                        <td>Tháng 5</td>
                        <td>30,000,000</td>
                        <td>350</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h5>Cơ Sở Vật Chất</h5>
            <ul class="list-group">
                <li class="list-group-item">Phòng chiếu: 5</li>
                <li class="list-group-item">Ghế ngồi: 400</li>
                <li class="list-group-item">Hệ thống âm thanh: Dolby Surround</li>
                <li class="list-group-item">Hệ thống ánh sáng: LED</li>
            </ul>
        </div>
        <div class="col-md-6">
            <h5>Chi Phí Duy Trì Rạp</h5>
            <ul class="list-group">
                <li class="list-group-item">Tiền thuê mặt bằng: 10,000,000 VNĐ</li>
                <li class="list-group-item">Tiền điện hàng tháng: 5,000,000 VNĐ</li>
                <li class="list-group-item">Chi phí bảo trì: 3,000,000 VNĐ</li>
                <li class="list-group-item">Chi phí nhân viên: 20,000,000 VNĐ</li>
            </ul>
        </div>
    </div>
</div>
<div class="container my-5" id="thongKeBaoCao">
    <h1 class="text-center" style="color: darkblue;"> Báo Cáo</h1>

    <div class="row">
        <div class="col-md-6 mb-3">
            <h6>Số lượng vé bán ra:</h6>
            <input type="number" class="form-control" placeholder="Nhập số lượng vé" required>
        </div>
        <div class="col-md-6 mb-3">
            <h6>Doanh thu theo tháng:</h6>
            <input type="number" class="form-control" placeholder="Nhập doanh thu" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <h6>Cơ sở vật chất:</h6>
            <textarea class="form-control" rows="3" placeholder="Nhập thông tin cơ sở vật chất" required></textarea>
        </div>
        <div class="col-md-6 mb-3">
            <h6>Chi phí duy trì rạp:</h6>
            <input type="number" class="form-control" placeholder="Nhập chi phí" required>
        </div>
    </div>

    <button class="btn btn-primary" id="createReportBtn">Tạo Báo Cáo</button>

    <h6 class="mt-5">Báo Cáo Đã Tạo:</h6>
    <div id="reportOutput" class="border p-3" style="display: none;">
        <h6>Báo Cáo:</h6>
        <p id="reportContent"></p>
    </div>
</div>

<script>
    document.getElementById('createReportBtn').addEventListener('click', function() {
        const ticketSales = document.querySelector('input[placeholder="Nhập số lượng vé"]').value;
        const revenue = document.querySelector('input[placeholder="Nhập doanh thu"]').value;
        const facilities = document.querySelector('textarea[placeholder="Nhập thông tin cơ sở vật chất"]').value;
        const maintenanceCost = document.querySelector('input[placeholder="Nhập chi phí"]').value;

        const reportContent = `
            Số lượng vé bán ra: ${ticketSales} <br>
            Doanh thu theo tháng: ${revenue} <br>
            Cơ sở vật chất: ${facilities} <br>
            Chi phí duy trì rạp: ${maintenanceCost}
        `;

        document.getElementById('reportContent').innerHTML = reportContent;
        document.getElementById('reportOutput').style.display = 'block';
    });

    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5'],
            datasets: [{
                label: 'Doanh Thu (VNĐ)',
                data: [10000000, 15000000, 20000000, 25000000, 30000000],
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<?php include 'footer.php'; ?>