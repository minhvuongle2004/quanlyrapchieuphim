<!DOCTYPE html>
<head>
    <!-- CSS Links -->
    <link rel="stylesheet" href="../hotel/assets/wow/animate.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        footer {
            font-family: "Roboto", sans-serif;
            background-color: #ccc;
            margin-top: 50px;
            padding: 40px 0;
        }
        
        footer h4 {
            margin-bottom: 20px;
            color: #333;
            font-size: 18px;
            font-weight: bold;
        }
        
        footer a {
            color: #555;
            text-decoration: none;
            line-height: 2;
            display: block;
        }
        
        footer a:hover {
            color: #000;
            text-decoration: underline;
        }
        
        .row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        /* Điều chỉnh các cột */
        .col-sm-4 {
            flex: 0 0 32%;  /* chiếm 32% chiều rộng của container */
        }

        .social {
            margin-top: 20px;
        }
        
        .social a {
            display: inline-block;
            margin-right: 15px;
            font-size: 24px;
        }
        
        .list-unstyled {
            padding-left: 0;
            list-style: none;
            margin: 0;
        }
        
        .list-unstyled li {
            margin-bottom: 10px;
        }

        .contact-info h5 {
            margin-top: 0;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <footer class="spacer">
        <div class="container">
            <div class="row">
                <!-- Phim Việt Nam Section -->
                <div class="col-sm-4">
                    <h4>Phim Việt Nam</h4>
                    <nav>
                        <a href="#">Giới Thiệu</a>
                        <a href="#">Tiện Ích Online</a>
                        <a href="#">Thẻ Quà Tặng</a>
                    </nav>
                </div>

                <!-- Điều khoản Section -->
                <div class="col-sm-4">
                    <h4>Điều khoản sử dụng</h4>
                    <ul class="list-unstyled">
                        <li><a href="rooms-tariff.php">Điều Khoản Chung</a></li>
                        <li><a href="tour.php">Chính Sách Bảo Mật</a></li>
                        <li><a href="contact.php">Câu Hỏi Thường Gặp</a></li>
                    </ul>
                </div>

                <!-- Contact Section -->
                <div class="col-sm-4">
                    <h4>Liên hệ với chúng tôi</h4>
                    <div class="contact-info">
                        <h5>Hotline: 0353234113</h5>
                    </div>
                    
                    <!-- Social Media Links -->
                    <div class="social">
                        <a href="https://web.facebook.com/profile.php?id=100086343945136" target="_blank">
                            <i class="fa-brands fa-facebook-square" data-toggle="tooltip" data-placement="top" title="Facebook"></i>
                        </a>
                        <a href="https://www.instagram.com/_lmin.vuong.04_/" target="_blank">
                            <i class="fa-brands fa-instagram" data-toggle="tooltip" data-placement="top" title="Instagram"></i>
                        </a>
                        <a href="https://www.tiktok.com/@xsly2004?lang=fr" target="_blank">
                            <i class="fa-brands fa-tiktok" data-toggle="tooltip" data-placement="top" title="TikTok"></i>
                        </a>
                        <a href="https://www.youtube.com/@vuongleminh928" target="_blank">
                            <i class="fa-brands fa-youtube-square" data-toggle="tooltip" data-placement="top" title="YouTube"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="../hotel/assets/jquery.js"></script>
    <script src="../hotel/assets/wow/wow.min.js"></script>
    <script src="../hotel/assets/uniform/js/jquery.uniform.js"></script>
    <script src="../hotel/assets/bootstrap/js/bootstrap.js"></script>
    <script src="../hotel/assets/mobile/touchSwipe.min.js"></script>
    <script src="../hotel/assets/respond/respond.js"></script>
    <script src="../hotel/assets/gallery/jquery.blueimp-gallery.min.js"></script>
    <script src="../hotel/assets/script.js"></script>
</body>
</html>