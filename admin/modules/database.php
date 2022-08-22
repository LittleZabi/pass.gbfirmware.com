<?php
session_start();
// $_SESSION['user']['username'] = 'admin';
// $_SESSION['user']['email'] = 'mtcomputers.tkw@gmail.com';
// $_SESSION['user']['fullname'] = 'System Administrator';
// unset($_SESSION['user']);
if ($_SERVER['REMOTE_ADDR'] == '::1') {
    $con = mysqli_connect('localhost', 'root', '', 'gbfirmw1_res');
} else {
    $con = mysqli_connect('localhost', 'gbfirmw1_res', 'Abbas786@786@', 'gbfirmw1_res');
}
