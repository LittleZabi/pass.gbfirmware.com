<?php
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
    <link rel="stylesheet" href="static/style.css">
    <link rel="stylesheet" href="./static/theme-dark.css">
</head>

<body>
    <?php
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message']['msg'] ?? "Nothing ";
        $type = $_SESSION['message']['type'] ?? "error";
    ?>

        <div class="pass__modal">
            <div class="pass__inner_modal">
                <?php
                if ($type == 'success') {

                ?>
                    <div class="good-icon">
                        <span class="line1"></span>
                        <span class="line2"></span>
                    </div>
                <?php } else {
                ?>

                    <div class="cross-icon">
                        <span class="line1"></span>
                        <span class="line2"></span>
                    </div>
                <?php
                }
                ?>
                <h2><?php echo $type; ?></h2>
                <span class="message"><?php echo $message; ?></span>
                <button onclick="document.querySelector('.pass__modal').style.display = 'none'">Ok</button>
            </div>
        </div>
    <?php
        unset($_SESSION['message']);
    }

    ?>
    <div class="main-view">
        <header>
            <nav class="page-size">
                <div class="header-left">Passwords Manager</div>
                <div class="header-btn">
                    <a href="./manager.php">Passwords</a>
                    <a href="./users-manager.php" class="active">Users</a>
                </div>
                <div class="header-right">Admin</div>
            </nav>
        </header>
        <main>
            <div class="page-size container">
                <div class="left">
                    <div class="form-view">
                        <span class="search-title"></span>
                        <input type="hidden" id="search_in_passwords" placeholder="Enter name, password, id to search file...">
                        <br>
                    </div>
                    <div class="left-list">
                    </div>
                </div>
                <div class="right">
                    <button onclick="window.location.href = window.location.href" style="float: right;margin-right:38px;width: 189px;background: #403692;">Add new user</button>
                    <br>
                    <br>
                    <form class="form-view" id="user-form" action="./admin-api.php" method="POST">
                        <br>
                        <fieldset>
                            <legend>Add new users as low level admin</legend>
                            <label for="username">Enter username</label>
                            <input required type="text" name="username" id="username" placeholder="Enter user fullname...">
                            <label for="email">Enter email address</label>
                            <input required type="text" name="email" id="email" placeholder="Enter email address...">
                            <label for="password">Enter your password</label>
                            <input required type="password" name="password" id="password" placeholder="Enter password...">
                            <label for="re-password">Enter your password again</label>
                            <input required type="password" name="re-password" id="re-password" placeholder="Enter password again...">
                            <input type="hidden" name="user-manage-action" value="new-user">
                            <button type="submit">Save user</button>
                        </fieldset>
                        <br><br>
                        <div class="result">
                            <span class="title"></span>
                        </div>
                    </form>
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
<script src="./static/user-main.js"></script>