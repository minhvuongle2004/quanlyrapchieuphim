<?php
// Hàm để lấy tên thứ trong tuần bằng tiếng Việt
function getDayOfWeek($date) {
    $days = array('CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7');
    return $days[date('w', strtotime($date))];
}

// Lấy ngày hiện tại
$currentDate = new DateTime();
?>

<div class="container">
    <!-- Danh sách chọn ngày -->
    <div class="mb-4">
        <h4>Chọn ngày:</h4>
        <ul class="toggle-tabs list-unstyled d-flex flex-wrap">
            <?php
            // Hiển thị 10 ngày kể từ ngày hiện tại
            for ($i = 0; $i < 10; $i++) {
                $date = clone $currentDate;
                $date->modify("+$i days");
                
                $month = $date->format('m');
                $dayOfWeek = getDayOfWeek($date->format('Y-m-d'));
                $dayOfMonth = $date->format('d');
                $fullDate = $date->format('Ymd');
                
                $isCurrentDay = ($i === 0) ? 'current' : '';
            ?>
                <li class="<?php echo $isCurrentDay; ?> m-2">
                    <div class="day border rounded p-2 text-center cursor-pointer" 
                         onclick="getSelectDay('24020100', '<?php echo $fullDate; ?>', event);"
                         style="cursor: pointer; min-width: 80px;">
                        <div class="month"><?php echo $month; ?></div>
                        <div class="weekday"><?php echo $dayOfWeek; ?></div>
                        <div class="date"><strong><?php echo $dayOfMonth; ?></strong></div>
                    </div>
                </li>
            <?php
            }
            ?>
        </ul>
    </div>

    <div id="showtimes" class="showtimes" style="display: none;">
        <h4>Khung Giờ Chiếu:</h4>
        <div id="showtimes-buttons" class="mb-4">
        </div>
    </div>
</div>

<style>
.day {
    transition: all 0.3s ease;
}
.day:hover {
    background-color: #f0f0f0;
}
li.current .day {
    background-color: #007bff;
    color: white;
}
.toggle-tabs {
    overflow-x: auto;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    const formattedDate = today.getFullYear() +
        String(today.getMonth() + 1).padStart(2, '0') +
        String(today.getDate()).padStart(2, '0');
    getSelectDay('24020100', formattedDate, null);
});
</script>