<?php
header('location:./admin/');
exit();
include('database.php');
// unset($_SESSION['user']);
if (isset($_SESSION['user']) && $_SESSION['user']['username'] != '') {
    echo '';
} else {
    $_SESSION['message']['msg'] = "Login first then you can manage passwords.";
    $_SESSION['message']['type'] = "error";
    header('location:./login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Manager</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./static/manager.css">
    <link rel="stylesheet" href="./static/theme-dark.css">
</head>

<body>
    <div class="main-view">
        <header>
            <nav class="page-size">
                <div class="header-left">Passwords Manager</div>
                <div class="header-btn">
                    <a href="./manager.php" class="active">Passwords</a>
                    <a href="./users-manager.php">Users</a>
                </div>
                <div class="header-right">Admin</div>
            </nav>
        </header>
        <main>
            <div class="page-size container">
                <div class="left">
                    <div class="form-view">

                        <span class="search-title">Search in passwords.</span>
                        <input type="search" id="search_in_passwords" placeholder="Enter name, password, id to search file...">
                        <br>

                    </div>
                    <div class="left-list">

                    </div>

                    <!-- <section>
                    <span class="title">#12 - King of xiaomi King of xiaomi King of xiaomi</span>
                    <span class="pass">Out of the ashes</span>
                </section> -->
                </div>
                <div class="right">
                    <form class="form-view">
                        <span style="font-size: 14px;font-weight:normal;margin-left: 10px;">Search and add new files.</span>
                        <input type="search" name="strings" id="search_query" placeholder="Enter name, id to search file...">
                        <br><br>
                        <div class="result">
                            <span class="title"></span>
                        </div>
                    </form>
                    <div class="file-info">
                        <span class="no-file">No file selected</span>
                    </div>
                    <div class="message" id="message">
                        <span class="info">
                        </span>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <div class="page-size">Developed by: LittleZabi and Blueterminal Lab.</div>
        </footer>
    </div>
</body>

</html>
<!-- <script src="https://unpkg.com/axios/dist/axios.min.js"></script> -->
<script src="./static/axios.js"></script>
<script src="./static/main.js"></script>