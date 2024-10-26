<?php
session_start(); 

if (!isset($_SESSION['KhachHangID'])) {
    header("Location: ../chieuphim/login.php");
    exit();
}

// Thông tin người dùng từ session
$KhachHangID = $_SESSION['KhachHangID'];
$ho_ten = $_SESSION['ho_ten'];
$email = $_SESSION['email'];

$host = 'localhost';
$dbname = 'baitaplon';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM phim WHERE img_url IS NOT NULL AND img_url != ''";
$result = $conn->query($sql);
$all_movies = [];
while ($row = $result->fetch_assoc()) {
    $all_movies[] = $row;
}
?>

    <title>Quản lý Rạp Phim</title>
    <style>
        .welcome-message {
            background-color: #f8f9fa;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 10px 0;
        }
        
        .carousel-item {
            height: 400px;
            overflow: hidden;
        }

        .carousel-item img {
            height: 100%;
            width: 100%;
            object-fit: cover;
        }

        .movie-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .movie-item {
            position: relative;
            transition: transform 0.3s ease;
        }

        .movie-item:hover {
            transform: scale(1.05);
        }

        .movie-item img {
            width: 100%;
            height: 375px;
            object-fit: cover;
            border-radius: 8px;
        }

        .button-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
        }

        .movie-item:hover .button-container {
            display: block;
        }

        .btn-custom {
            background-color: red;
            color: white;
            border-radius: 25px;
            padding: 10px 20px;
            text-align: center;
        }

        .btn-custom:hover {
            background-color: rgba(255, 0, 0, 0.8) !important;
            color: white;
        }
    </style>
<?php include 'header.php' ?>

<!-- Movie Carousel -->
<div id="movieCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-inner">
        <?php foreach($all_movies as $index => $movie): ?>
        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
            <img src="<?php echo htmlspecialchars($movie['img_url']); ?>" 
                 class="d-block w-100" 
                 alt="<?php echo htmlspecialchars($movie['ten_phim']); ?>">
            <div class="carousel-caption">
                <h3><?php echo htmlspecialchars($movie['ten_phim']); ?></h3>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#movieCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#movieCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<div class="container my-5">
    <h1 class="text-center" style="color: darkred;">Danh Sách Phim Hot</h1>
    <div class="movie-grid">
        <?php foreach($all_movies as $movie): ?>
        <div class="movie-item">
            <img src="<?= htmlspecialchars($movie['img_url']) ?>" 
                 alt="<?= htmlspecialchars($movie['ten_phim']) ?>">
            <div class="button-container">
                <a href="formchonlichchieu.php?phim_id=<?= $movie['phim_id'] ?>&KhachHangID=<?= $KhachHangID ?>" 
                   class="btn btn-custom">Đặt Vé</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

