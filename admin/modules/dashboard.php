<?php

function pre($str)
{
    echo '<pre>';
    print_r($str);
    echo '</pre>';
}
$datestamp = date('c');
function getPastMonth($datestamp)
{
    $pastMonth = $datestamp;
    $pastMonth = explode('-', $pastMonth)[1];
    $pastMonth = intval($pastMonth) - 1;
    $u = '';
    $i = 0;
    foreach (explode('-', $datestamp) as $k) {
        if ($i == 1) {
            $u .= '-' . $pastMonth;
        } else {
            if ($i == 0) $u .=  $k;
            else $u .= '-' . $k;
        }
        $i++;
    }
    return $u;
}
function get24Hours($datestamp)
{
    $hours24 = $datestamp;
    $hours24 = explode('-', $hours24)[2];
    $hours24 = intval($hours24) - 1;
    $u = '';
    $i = 0;
    if ($hours24 < 1) $hours24 = 1;
    foreach (explode('-', $datestamp) as $k) {
        if ($i == 2) {
            $u .= '-' . $hours24;
        } else {
            if ($i == 0) $u .=  $k;
            else $u .= '-' . $k;
        }
        $i++;
    }
    return $u;
}

function getAnually($datestamp)
{
    $year = $datestamp;
    $year = explode('-', $year)[0];
    $year = intval($year) - 1;
    $u = '';
    $i = 0;
    foreach (explode('-', $datestamp) as $k) {
        if ($i == 0) {
            $u .= $year;
        } else {
            $u .= '-' . $k;
        }
        $i++;
    }
    return $u;
}
$nowDate = $datestamp;
$pastMonth = getPastMonth($datestamp);
$pastYear = getAnually($datestamp);
$last24h = get24Hours($datestamp);
// get last 24 hours
$sql = "SELECT * 
FROM res_password_visitors
WHERE DATE(createdAt) = DATE(CAST(NOW() - INTERVAL 1 DAY AS DATE));";
$k = $con->query($sql);
$last24h = $k->num_rows;
//get monthly visitors`
$sql = "SELECT id FROM res_password_visitors
WHERE createdAt BETWEEN '$pastMonth' AND '$nowDate';";
$k = $con->query($sql);
$monthly = $k->num_rows;
//get monthly annually
$sql = "SELECT id FROM res_password_visitors
WHERE createdAt BETWEEN '$pastYear' AND '$nowDate';";
$k = $con->query($sql);
$annually = $k->num_rows;
$sql  = "SELECT id FROM res_files_passwords WHERE 1 ORDER BY id DESC";
$k = $con->query($sql);
$totalfiles = $k->num_rows;
$sql  = "SELECT id FROM res_user_pass_manager WHERE 1 ORDER BY id DESC";
$k = $con->query($sql);
$totalUsers = $k->num_rows;
