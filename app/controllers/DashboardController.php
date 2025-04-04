<?php

session_start();

if (!isset($_SESSION['loggedin']) && $_GET['page'] != 'login') {
    header("Location: ../../index.php?page=login");
    exit();
}

include_once(__DIR__ . "/../views/header.php");
include_once(__DIR__ . "/../views/nav.php");
require_once(__DIR__ . "/../models/DashboardModel.php");

$dataCardOne = getDataCardOne();
$porcentaje = isset($dataCardOne["porcentaje_diferencia_ventas"]) ? $dataCardOne["porcentaje_diferencia_ventas"] : 0;
$total_ventas = isset($dataCardOne["total_ventas"]) ? $dataCardOne["total_ventas"] : 0;

$dataCardTwo = getDataCardTwo();
$total_presupuestos = isset($dataCardTwo["total_presupuestos"]) ? $dataCardTwo["total_presupuestos"] : 0;
$porcentaje_diferencia_presupuestos = isset($dataCardTwo["porcentaje_diferencia_presupuestos"]) ? $dataCardTwo["porcentaje_diferencia_presupuestos"] : 0;

$dataCardAndGraphic = getDataCardAndGraphic();
$total_recaudado_mes_actual = isset($dataCardAndGraphic["total_recaudado_mes_actual"]) ? $dataCardAndGraphic["total_recaudado_mes_actual"] : 0;
$porcentaje_diferencia_recaudado = isset($dataCardAndGraphic["porcentaje_diferencia_recaudado"]) ? $dataCardAndGraphic["porcentaje_diferencia_recaudado"] : 0;

$valores_grafico = getDataGraphic();

require_once(__DIR__ . "/../views/dashboard.php");
include_once(__DIR__ . "/../views/footer.php");
