<?php
if (!isset($_COOKIE['user_id'])) {
    header('Location: ../main/index.php');
    exit();
}
