<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Check</title>
    <link rel="stylesheet" href="static/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    include("./admin/modules/database.php");
    include('./set-visitor.php');
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
                <div style="display:flex;justify-content:center">
                    <button onclick="document.querySelector('.pass__modal').style.display = 'none'">Ok</button>
                    <?php
                    if ($type == 'success') {
                    ?>
                        <button style="background: #4f5bb8" onclick="Copy(this,'<?php echo $message; ?>')">Copy to clipboard</button>
                    <?php
                    }
                    ?>

                </div>
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
                <h2>GBfirmware <br />Password Checker</h2>
            </div>
            <form class="pass__form" action="./api.php" method="POST">
                <p class="pass_title">
                    Enter your email and the file name.
                </p>
                <input type="email" placeholder="Email" name="email" id="email">
                <input type="text" name="file__name" placeholder="file name" id="file__name">
                <input type="submit" name="submit-file" id="file__name" value="Check">

            </form>
        </div>
    </div>
</body>

</html>

<script>
    const Copy = (btn, str) => {
        let textarea = document.createElement("textarea");
        textarea.style.position = "fixed";
        textarea.style.top = 0;
        textarea.style.left = 0;
        textarea.style.width = "2em";
        textarea.style.height = "2em";
        textarea.style.border = "none";
        textarea.style.outline = "none";
        textarea.style.boxShadow = "none";
        textarea.style.background = "transparent";
        textarea.value = str;
        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();
        try {
            document.execCommand("copy");
            btn.innerHTML = 'Copied!';
            btn.style.background = '#436cfb';
        } catch (error) {
            console.log("Oops, unalbe top copy");
        }
        document.body.removeChild(textarea);
    };
</script>