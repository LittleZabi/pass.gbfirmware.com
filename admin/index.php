<?php
define('PATH', __DIR__);
define('ROOT_VIEW', '/page/view.php?slug=');
require_once('./modules/constants.php');
require_once('./modules/database.php');
if (isset($_SESSION['user']) && $_SESSION['user']['username'] != '') {
  echo '';
} else {
  $_SESSION['message']['msg'] = "Login first then you can manage passwords.";
  $_SESSION['message']['type'] = "error";
  header('location:./login.php');
}

$page = 'dashboard';
if (isset($_GET['page'])) {
  $page = $_GET['page'] ?? 'dashboard';
  if (!file_exists('./pages/' . $page . '.phtml')) {
    $page = 'dashboard';
  }
}
$userFlag = $_SESSION['user']['level'];
$userAction = $userFlag == 'low-level' ? 0 : 1;
?>
<?php require_once('./pages/header.php'); ?>
<?php require_once('./pages/modal.php'); ?>

<body id="page-top">
  <div id="wrapper">
    <?php require_once('./pages/sidebar.php'); ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?php require_once('./pages/navbar.php'); ?>
        <?php require_once('./pages/' . $page . '.phtml'); ?>
      </div>
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Password Burner <?php echo date('Y'); ?> Created by LittleZabi and Blueterminal Lab</span>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <?php require_once('./pages/scripts.php'); ?>
</body>

</html>