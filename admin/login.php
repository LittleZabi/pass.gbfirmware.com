<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password manager Login</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    include("database.php");
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

    <div class="pass__main">
        <div class="pass__wrapper">
            <div class="pass__top">
                <img src="../assets/bg.jpg" alt="image....">
            </div>
            <div class="pass__mid">
                <h2>GBfirmware <br />Password Manager Login</h2>
            </div>
            <form class="pass__form" action="./admin-api.php" method="POST">
                <div class="admin-con">
                    <div>

                        <input onchange="handleAdminCon(this)" type="radio" name="admin-conf" value="super-admin" checked id="super-admin">
                        <label for="super-admin">Login as Super Admin</label>
                    </div>
                    <div>
                        <input onchange="handleAdminCon(this)" type="radio" name="admin-conf" value="low-admin" id="low-level-admin">
                        <label for="low-level-admin">Login as Low Level Admin</label>
                    </div>
                </div>
                <span class="email_span">Enter your username to login!</span>
                <input type="text" placeholder="username..." name="username" id="email">
                Enter your Password.
                <input type="password" name="password" placeholder="Your password">
                <input type="submit" name="submit-login" value="login">
            </form>
        </div>
    </div>
</body>

</html>
<script>
    const handleAdminCon = e => {
        const button = document.querySelector('input[type="submit"]')

        if (e.value === 'low-admin') {
            button.name = 'user-pass-login'
            try {
                document.querySelector('#email').placeholder = 'Enter your email address...'
                document.querySelector('.email_span').innerHTML = "Enter you email to login as a admin!"
            } catch (error) {

            }
        } else {
            button.name = 'submit-login';
            try {
                document.querySelector('#email').placeholder = 'Enter your username...'
                document.querySelector('.email_span').innerHTML = "Enter your username to login!"
            } catch (error) {

            }
        }
    }
</script>