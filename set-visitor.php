<?php
function get_client_ip()
{
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
$ip = get_client_ip();

$sql = "SELECT
            *
        FROM
            res_password_visitors
        WHERE
            ip_address = '$ip';";
$q = $con->query($sql);
if ($q->num_rows > 0) {
    $sql = "
    UPDATE 
       res_password_visitors
    SET visits = visits + 1
    WHERE
    ip_address = '$ip'";
    $q = $con->query($sql);
} else {
    $sql = "INSERT INTO res_password_visitors (ip_address) VALUES('$ip')";
    $q = $con->query($sql);
}
