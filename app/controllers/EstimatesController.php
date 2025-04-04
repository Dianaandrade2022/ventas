<?php

session_start();

if (!isset($_SESSION['loggedin']) && $_GET['page'] != 'login') {
    header("Location: ../../index.php?page=login");
    exit();
}

include_once(__DIR__ . "/../views/header.php");
include_once(__DIR__ . "/../views/nav.php");
require_once(__DIR__ . "/../models/EstimateModel.php");

// Mostramos la vista para generar un nuevo presupuesto
if (isset($_REQUEST["name"]) && $_REQUEST["name"] !== "estimates_list") {
    require_once(__DIR__ . "/../views/estimates/estimateRegister.php");
}

// Mostramos la lista de presupuestos
if (isset($_REQUEST["name"]) && $_REQUEST["name"] === "estimates_list") {
    require_once(__DIR__ . "/../views/estimates/estimatesList.php");
}

include_once(__DIR__ . "/../views/footer.php");
