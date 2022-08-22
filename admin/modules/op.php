<?php
include('database.php');

if (isset($_GET['get_user'])) {
    $id = $_GET['get_user'];
    $sql = "SELECT * FROM `res_user_pass_manager` WHERE id = $id";
    $query = $con->query($sql);
    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        exit(json_encode($row));
    } else {
        exit('notFound');
    }
}
if (isset($_POST['user-pass-login'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $sql = "SELECT * FROM `res_user_pass_manager` WHERE email = '$email' AND password = '$pass'";
    $query = $con->query($sql);
    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        $_SESSION['user-manager']['username'] = $row['username'];
        $_SESSION['user-manager']['id'] = $row['id'];
        $_SESSION['user-manager']['email'] = $row['email'];
        header('location:./my-passwords.php');
        exit('success');
    } else {
        $_SESSION['message']['msg'] = "user email or password is incorrect";
        $_SESSION['message']['type'] = "error";
        header('location: ./user-manager-login.php');
        exit('notFound');
    }
}
if (isset($_GET['get_file'])) {
    $id = $_GET['get_file'];
    $sql = "SELECT * FROM res_files_passwords WHERE file_id = $id";
    $query = $con->query($sql);
    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        $user = $row['user'];
        $sql = "SELECT * FROM res_user_pass_manager WHERE id = $user";
        $k = $con->query($sql);
        if ($k->num_rows > 0) {
            $row['user'] = $k->fetch_assoc();
        }
        exit(json_encode($row));
    } else {
        exit('notFound');
    }
}

if (isset($_POST['setFile'])) {
    $file_id = mysqli_real_escape_string($con, $_POST['file_id']);
    $file_title = mysqli_real_escape_string($con, $_POST['file_title']);
    $file_pass = mysqli_real_escape_string($con, $_POST['file_pass']);
    $sql = "SELECT * FROM `res_files_passwords` WHERE `file_id` = $file_id";
    $query = $con->query($sql);
    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        $fid = $row['file_id'];
        if ($fid == $file_id) {
            $sql = "UPDATE `res_files_passwords` SET  `file_pass` = '$file_pass', `file_title` = '$file_title' WHERE `file_id` = $file_id";
            $query = $con->query($sql);
            exit('updated');
        } else {
            $sql = "INSERT INTO `res_files_passwords` (`file_id`, `file_title`, `file_pass`) VALUES ('$file_id', '$file_title', '$file_pass')";
            $query = $con->query($sql);
            exit('success');
        }
    } else {
        $sql = "INSERT INTO `res_files_passwords` (`file_id`, `file_title`, `file_pass`) VALUES ('$file_id', '$file_title', '$file_pass')";
        $query = $con->query($sql);
        exit('success');
    }
}
if (isset($_GET['setUserFile'])) {
    if (isset($_SESSION['user-manager']) && $_SESSION['user-manager']['username']) {
        $file_id = mysqli_real_escape_string($con, $_GET['file_id']);
        $user_id = $_SESSION['user-manager']['id'];
        $file_title = mysqli_real_escape_string($con, $_GET['file_title']);
        $file_pass = mysqli_real_escape_string($con, $_GET['file_pass']);
        $sql = "SELECT * FROM `res_files_passwords` WHERE `file_title` = '$file_title'";
        $query = $con->query($sql);
        if ($query->num_rows > 0) {
            $row = $query->fetch_assoc();
            $fid = $row['file_id'];
            if ($fid == $file_id) {
                $sql = "UPDATE `res_files_passwords` SET  `file_pass` = '$file_pass', `file_title` = '$file_title' WHERE `file_id` = $file_id AND user = $user_id";
                $query = $con->query($sql);
                exit('updated');
            } else {
                $sql = "INSERT INTO `res_files_passwords` (`file_id`, `file_title`, `file_pass`,`user`) VALUES ('$file_id', '$file_title', '$file_pass', $user_id)";
                $query = $con->query($sql);
                exit('success');
            }
        } else {
            $sql = "INSERT INTO `res_files_passwords` (`file_id`, `file_title`, `file_pass`, `user`) VALUES ('$file_id', '$file_title', '$file_pass', $user_id)";
            $query = $con->query($sql);
            exit('success');
        }
    } else {
        exit('userNotLogged');
    }
}

if (isset($_GET['pass_search'])) {
    $q = mysqli_real_escape_string($con, $_GET['pass_search']);
    $sql = "SELECT * FROM `res_files_passwords` WHERE `file_id` LIKE '$q' OR `file_title` LIKE '%$q%' OR `file_pass` LIKE '%$q%' ORDER BY `file_id` ASC LIMIT 10";
    $query = $con->query($sql);
    if ($query->num_rows > 0) {
        $data = $query->fetch_all(MYSQLI_ASSOC);
        exit(json_encode($data));
    } else {
        json_encode([]);
    }
}

if (isset($_GET['q'])) {
    $q = mysqli_real_escape_string($con, $_GET['q']);
    $sql = "SELECT `file_id`, `title` FROM `res_files` WHERE file_id LIKE '$q' OR `title` LIKE '%$q%' ORDER BY `file_id` ASC LIMIT 10";
    $query = $con->query($sql);
    if ($query->num_rows > 0) {
        $data = $query->fetch_all(MYSQLI_ASSOC);
        exit(json_encode($data));
    } else {
        json_encode([]);
    }
}


if (isset($_POST['user-manage-action'])) {
    if (isset($_SESSION['user']) && $_SESSION['user']['username'] != '') {
        $action = $con->real_escape_string($_POST['user-manage-action']);
        $username = $con->real_escape_string($_POST['username']);
        $email = $con->real_escape_string($_POST['email']);
        $password = $con->real_escape_string($_POST['password']);
        if ($username == '' && $email == '') {
            exit('inputNotFilled');
        }
        if ($action == 'update-user') {
            $id = $_POST['user-id'];
            $sql = "SELECT * FROM `res_user_pass_manager` WHERE `id` = $id";
            $query = $con->query($sql);
            if ($query->num_rows > 0) {
                $sql = "UPDATE `res_user_pass_manager` SET `password` = '$password', `email` = '$email', `username` = '$username' WHERE `id` = $id";
                $q = $con->query($sql);
                if ($q) {
                    exit('updated');
                }
                exit();
            } else {
                exit('userNotExist');
            }
            exit();
        }
        if ($action == 'new-user') {
            $sql = "SELECT * FROM `res_user_pass_manager` WHERE `email` = '$email'";
            $query = $con->query($sql);
            if ($query->num_rows > 0) {
                exit('userExist');
            } else {
                $sql = "INSERT INTO `res_user_pass_manager` (`username`, `email`, `password`) VALUES('$username', '$email', '$password')";
                $q = $con->query($sql);
                if ($q) {
                    exit('success');
                }
                exit();
            }
        }
    } else {
        exit('userNotLogged');
    }
}
if (isset($_GET['pass_files'])) {
    if (isset($_SESSION['user']) && $_SESSION['user']['username'] != '') {
        $sql = "SELECT * FROM `res_files_passwords` WHERE 1 ORDER BY `id` DESC LIMIT 15";
        $query = $con->query($sql);
        if ($query->num_rows > 0) {
            $data = $query->fetch_all(MYSQLI_ASSOC);
            exit(json_encode($data));
        } else {
            exit('notFound');
        }
    } else {
        exit('userNotLogged');
    }
}
if (isset($_GET['my-pass'])) {
    if (isset($_SESSION['user-manager']) && $_SESSION['user-manager']['username'] != '') {
        $id = $_SESSION['user-manager']['id'];
        $sql = "SELECT * FROM `res_files_passwords` WHERE user = $id ORDER BY `id` DESC LIMIT 50";
        $query = $con->query($sql);
        if ($query->num_rows > 0) {
            $data = $query->fetch_all(MYSQLI_ASSOC);
            exit(json_encode($data));
        } else {
            exit('notFound');
        }
    } else {
        exit('userNotLogged');
    }
}
if (isset($_GET['get_users'])) {
    if (isset($_SESSION['user']) && $_SESSION['user']['username'] != '') {
        $sql = "SELECT * FROM `res_user_pass_manager` WHERE 1 ORDER BY `id` DESC";
        $query = $con->query($sql);
        if ($query->num_rows > 0) {
            $data = $query->fetch_all(MYSQLI_ASSOC);
            exit(json_encode($data));
        } else {
            exit('notFound');
        }
    } else {
        exit('userNotLogged');
    }
}
if (isset($_GET['delete_user'])) {
    $id = $_GET['delete_user'];
    if (isset($_SESSION['user']) && $_SESSION['user']['username'] != '') {
        $sql = "DELETE FROM `res_user_pass_manager` WHERE id = $id";
        $query = $con->query($sql);
        exit('success');
    } else {
        exit('userNotLogged');
    }
}
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM res_files_passwords WHERE `id` = $id";
    $k = $con->query($sql);
    exit('success');
}
if (isset($_POST['submit-login'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $password = md5($password);
    $sql = "SELECT `username`,`fullname`,`email`,`permissions` FROM `res_users` WHERE `username` = '$username' AND `password` = '$password'";
    $query = mysqli_query($con, $sql);
    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        $permissions = $row['permissions'];
        $permissions = json_decode($permissions);
        if (isset($permissions->products_view) && $permissions->products_view == 1) {
            $_SESSION['user']['username'] = $row['username'];
            $_SESSION['user']['email'] = $row['email'];
            $_SESSION['user']['fullname'] = $row['fullname'];
            header('location: ./manager.php');
        } else {
            $_SESSION['message']['msg'] = "Access Denied!";
            $_SESSION['message']['type'] = "error";
            header('location: login.php');
        }
    } else {
        $_SESSION['message']['msg'] = "user email or password is incorrect";
        $_SESSION['message']['type'] = "error";
        header('location: login.php');
    }
}
if (isset($_POST['user-pass-login'])) {
    $username = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $password = md5($password);
    $sql = "SELECT * FROM `res_user_pass_manager` WHERE `email` = '$username' AND `password` = '$password'";
    $query = mysqli_query($con, $sql);
    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        $_SESSION['user-manager']['username'] = $row['username'];
        $_SESSION['user-manager']['email'] = $row['email'];
        $_SESSION['user-manager']['slug'] = $row['slug'];
        header('location: ./my-passwords.php');
    } else {
        $_SESSION['message']['msg'] = "user email or password is incorrect";
        $_SESSION['message']['type'] = "error";
        header('location: login.php');
    }
}
