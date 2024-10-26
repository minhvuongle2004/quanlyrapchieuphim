<?php
session_start();
session_destroy();
header("Location: ../chieuphim/login.php");
exit();
?>
