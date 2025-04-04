<?php

if (!isset($_SESSION['loggedin']) && $_GET['page'] != 'login') {
  header("Location: index.php?page=login");
  exit();
}

if (isset($_GET["page"])) {
  $page = $_GET["page"];
} else {
  $page = "/";
}

switch ($page) {
  case 'login':
    require_once("app/controllers/LoginController.php");
    break;

  default:
    require_once("app/controllers/LoginController.php");
    break;
}
?>
