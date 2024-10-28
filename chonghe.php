<?php
include 'connectiondb.php';

// Lấy danh sách hàng ghế
$sql_hangghe = "SELECT DISTINCT hang_ghe FROM ghe ORDER BY hang_ghe";
$result_hangghe = $conn->query($sql_hangghe);
?>

<!-- Modal chọn ghế -->
<div class="modal fade" id="seatSelectionModal" tabindex="-1" aria-labelledby="seatSelectionLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="seatSelectionLabel">Chọn ghế</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="screen mb-4">
                        <h6 class="mb-0">MÀN CHIẾU</h6>
                    </div>
                    <div class="seating-layout">
                        <div class="seat-container">
                            <!-- Seats will be dynamically added here -->
                        </div>
                    </div>
                </div>

                <!-- Thêm chú thích -->
                <div class="seat-legend mt-4">
                    <div class="d-flex justify-content-center gap-4">
                        <div class="legend-item">
                            <button class="btn seat" disabled>
                                <i class="fas fa-chair"></i>
                            </button>
                            <span>Ghế trống</span>
                        </div>
                        <div class="legend-item">
                            <button class="btn seat selected" disabled style="background-color: #28a745;">
                                <i class="fas fa-chair"></i>
                            </button>
                            <span>Ghế đã chọn</span>
                        </div>
                        <div class="legend-item">
                            <button class="btn seat disabled" disabled>
                                <i class="fas fa-chair"></i>
                            </button>
                            <span>Ghế đã đặt</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="seat-info mt-3 px-3">
                <h6 style="color: darkred;">Ghế đã chọn: <span id="selectedSeatsText"></span></h6>
                <h6>Tổng tiền: <span id="totalPrice"></span> VNĐ</h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-success" onclick="confirmSeats()">Xác nhận ghế</button>
            </div>
        </div>
    </div>
</div>

<style>
    .screen {
        background-color: #e0e0e0;
        padding: 15px;
        margin: 20px auto;
        max-width: 80%;
        border-radius: 5px;
    }

    .seating-layout {
        max-width: 900px;
        margin: 0 auto;
    }

    .seat {
        width: 40px;
        height: 40px;
        padding: 5px;
        margin: 3px;
        background-color: #f0f0f0;
        border: 1px solid #ddd;
        transition: all 0.3s ease;
        position: relative;
    }

    .seat span {
        font-size: 0.8em;
        position: absolute;
        bottom: 2px;
        left: 50%;
        transform: translateX(-50%);
    }

    .seat:hover {
        background-color: #e0e0e0;
    }

    .seat.selected {
        background-color: #28a745;
        color: white;
    }

    .seat.disabled {
        background-color: #cccccc;
        cursor: not-allowed;
        opacity: 0.5;
    }

    .seat-info {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin: 10px 0;
    }

    .hang-ghe {
        margin-bottom: 15px;
    }

    .row-label {
        width: 30px;
        text-align: center;
    }

    .seat-row {
        flex: 1;
        justify-content: center;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .legend-item .seat {
        margin: 0;
        cursor: default;
    }

    .seat-legend {
        border-top: 1px solid #dee2e6;
        padding-top: 15px;
    }
</style>

<script>
    let ticketPrice = 0;
    let selectedShowtime = null;

    function openSeatSelection(suatId, gia, phongId) {
        console.log('Opening seat selection:', suatId, gia, phongId);

        // Reset giá trị khi mở modal
        ticketPrice = parseInt(gia);
        selectedShowtime = suatId;

        resetSeatSelection();

        // Gọi API để lấy ghế theo phòng
        fetch(`get_seats.php?phong_id=${phongId}&suat_id=${suatId}`)
            .then(response => response.json())
            .then(data => {
                updateSeatLayout(data.seats, data.bookedSeats);
            })
            .catch(error => {
                console.error('Error fetching seats:', error);
            });

        // Mở modal
        const modal = new bootstrap.Modal(document.getElementById('seatSelectionModal'));
        modal.show();
    }

    function updateSeatLayout(seats, bookedSeats) {
        const seatContainer = document.querySelector('.seat-container');
        seatContainer.innerHTML = ''; // Xóa layout ghế cũ

        // Nhóm ghế theo hàng
        const seatsByRow = seats.reduce((acc, seat) => {
            if (!acc[seat.hang_ghe]) {
                acc[seat.hang_ghe] = [];
            }
            acc[seat.hang_ghe].push(seat);
            return acc;
        }, {});

        // Tạo layout ghế mới
        Object.keys(seatsByRow).sort().forEach(hang => {
            const hangDiv = document.createElement('div');
            hangDiv.className = 'hang-ghe mb-3';

            const rowContent = `
            <div class="d-flex align-items-center">
                <div class="row-label me-3">
                    <strong>${hang}</strong>
                </div>
                <div class="d-flex flex-wrap seat-row">
                    ${seatsByRow[hang].map(seat => `
                        <button class="btn seat m-1 ${bookedSeats.includes(seat.ghe_id) ? 'disabled' : ''}" 
                                onclick="selectSeat(this, event)" 
                                data-ghe-id="${seat.ghe_id}">
                            <span>${seat.ghe_id.substr(-2)}</span>
                        </button>
                    `).join('')}
                </div>
            </div>
        `;

            hangDiv.innerHTML = rowContent;
            seatContainer.appendChild(hangDiv);
        });
    }

    function selectSeat(seatButton, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        if (seatButton.classList.contains('disabled')) {
            return;
        }

        seatButton.classList.toggle('selected');
        updateSelectedSeats();

        console.log('Seat clicked:', seatButton.getAttribute('data-ghe-id'));
        console.log('Is selected:', seatButton.classList.contains('selected'));
    }

    function updateSelectedSeats() {
        const selectedSeats = document.querySelectorAll('.seat.selected');
        console.log('Number of selected seats:', selectedSeats.length);

        const seatIds = Array.from(selectedSeats).map(seat => seat.getAttribute('data-ghe-id'));
        console.log('Selected seat IDs:', seatIds);

        // Cập nhật danh sách ghế đã chọn
        document.getElementById('selectedSeatsText').textContent = seatIds.join(', ');

        // Tính tổng tiền dựa trên số ghế đã chọn
        const totalAmount = ticketPrice * seatIds.length;
        console.log('Số ghế:', seatIds.length);
        console.log('Giá tiền:', totalAmount);
        document.getElementById('totalPrice').textContent = totalAmount.toLocaleString('vi-VN');
    }

    function resetSeatSelection() {
        document.querySelectorAll('.seat').forEach(seat => {
            seat.classList.remove('selected');
        });
        document.getElementById('selectedSeatsText').textContent = '';
        document.getElementById('totalPrice').textContent = '0';
    }

    // Sự kiện khi modal đóng
    document.getElementById('seatSelectionModal').addEventListener('hidden.bs.modal', resetSeatSelection);

    function confirmSeats() {
        const selectedSeats = document.querySelectorAll('.seat.selected');
        const seatIds = Array.from(selectedSeats).map(seat => seat.getAttribute('data-ghe-id'));

        if (seatIds.length === 0) {
            alert('Vui lòng chọn ít nhất một ghế');
            return;
        }

        // Tính tổng tiền cho tất cả các ghế
        const totalAmount = ticketPrice * seatIds.length;

        // Hiển thị loading
        const confirmButton = document.querySelector('.btn-success');
        confirmButton.disabled = true;
        confirmButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...';

        // Gửi request đến server để xử lý đặt vé
        fetch('process_booking.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `suat_id=${selectedShowtime}&ghe_ids=${JSON.stringify(seatIds)}&gia_ve=${totalAmount}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = `confrim_booking.php?booking_id=${data.booking_id}`;
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi đặt vé');
                    confirmButton.disabled = false;
                    confirmButton.innerHTML = 'Xác nhận ghế';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi đặt vé');
                confirmButton.disabled = false;
                confirmButton.innerHTML = 'Xác nhận ghế';
            });
    }
</script>