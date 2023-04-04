<?php
session_start();
if ($_SERVER['REMOTE_ADDR'] == '::1') {
    $con = mysqli_connect('localhost', 'root', '', 'gbfirmw1_res');
} else {
    $con = mysqli_connect('localhost', '', '', '');
}
