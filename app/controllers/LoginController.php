<?php

session_start();

require_once(__DIR__ . "/../../config/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    require_once(__DIR__ . "/../../functions/functions.php");
    require_once(__DIR__ . "/../models/UserModel.php");

    if (!isset($_POST['csrf_token'])) {
        die("CSRF token missing");
    }

    $csrf_token = $_POST['csrf_token'];

    $email = mysqli_real_escape_string($MYSQLI, sanitizeInput($_POST["email"]));
    $password = mysqli_real_escape_string($MYSQLI, sanitizeInput($_POST["password"]));

    if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        die("Invalid CSRF token");
    }

    if (validateEmail($email) && validatePassword($password)) {

        if (!login($email, $password)) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header("Location: ../../index.php?page=login");
            exit;
        }

        if ($_SESSION['role'] === 1) {
            header("Location: DashboardController.php");
            exit;
        } else {
            header("Location: /app/controllers/SalesController.php?name=sales_list");
            exit;
        }
    } else {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        header("Location: ../../index.php?page=login");
        exit;
    }
} else {
    $csrf_token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $csrf_token;
    require_once(__DIR__ . "/../views/login.php");
}