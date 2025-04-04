<?php

session_start();

if (!isset($_SESSION['loggedin']) && $_GET['page'] != 'login') {
    header("Location: ../../index.php?page=login");
    exit();
}

include_once(__DIR__ . "/../views/header.php");
include_once(__DIR__ . "/../views/nav.php");
require_once(__DIR__ . "/../models/SaleModel.php");

$message = "";
$range = "";
$rows = [];

// Mostramos la vista para generar una nueva venta
if (isset($_REQUEST["name"]) && $_REQUEST["name"] !== "sales_report" && $_REQUEST["name"] !== "sales_list") {
    require_once(__DIR__ . "/../views/sales/saleRegister.php");
}

// MOSTRAMOS EL LISTADO DE VENTAS
if (isset($_REQUEST["name"]) && $_REQUEST["name"] === "sales_list") {
    require_once(__DIR__ . "/../views/sales/salesList.php");
}

// GENERAMOS EL REPORTE DE VENTAS
if (isset($_REQUEST["name"]) && $_REQUEST["name"] === "sales_report") {

    function getReportSales($from, $to)
    {
        $rows = getSalesForDate($from, $to);

        if ($rows) {
            return $rows;
        }

        return [];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generar_reporte'])) {
        $from = $_POST['from'];
        $to = $_POST['to'];
        $rows = getReportSales($from, $to);

        $message = "<p style='text-align:right; color: #000'>Reporte de ventas</p>";
        $range = "<p style='text-align:right; color: #000'>Desde " . htmlspecialchars($from) . " Hasta " . htmlspecialchars($to) . "</p>";
    }

    require_once(__DIR__ . "/../views/sales/salesReport.php");
}

include_once(__DIR__ . "/../views/footer.php");
