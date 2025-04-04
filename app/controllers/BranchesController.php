<?php

session_start();

if (!isset($_SESSION['loggedin']) && $_GET['page'] != 'login') {
    header("Location: ../../index.php?page=login");
    exit();
}

include_once(__DIR__ . "/../views/header.php");
include_once(__DIR__ . "/../views/nav.php");
require_once(__DIR__ . "/../models/BranchModel.php");

$alert = '';
$data_branch = [];

// MOSTRAMOS LA VISTA PARA DAR DE ALTA UNA NUEVA SUCURSAL
if (isset($_REQUEST["name"]) && $_REQUEST["name"] === "branch_new" && !isset($_POST["add_branch"])) {
    $branches = getBranches();
    $stores = getStores();
    require_once(__DIR__ . "/../views/branches/branchRegister.php");
}

// RECIBIMOS EL POST Y CREAMOS UNA NUEVA SUCURSAL
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_branch"])) {
    $branches = getBranches();
    $stores = getStores();
    $alert = addBranch();
    require_once(__DIR__ . "/../views/branches/branchRegister.php");
}

// MOSTRAMOS LA LISTA DE SUCURSALES
if (isset($_REQUEST["name"]) && $_REQUEST["name"] === "branches_list") {
    require_once(__DIR__ . "/../views/branches/branchesList.php");
}

// EDITAMOS LA SUCURSAL
if (isset($_REQUEST["id"]) && is_numeric($_REQUEST["id"]) && !isset($_POST["edit_bach"])) {
    $branches = getBranches();
    $stores = getStores();
    $data_branch = getBranchById($_REQUEST["id"]);
    require_once(__DIR__ . "/../views/branches/branchEdit.php");
}

// ACTUALIZAMOS LA SUCURSAL EDITADA
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit_bach"])) {
    $branches = getBranches();
    $stores = getStores();
    $alert = editBranch();
    $data_branch = getBranchById($_POST["id"]);
    require_once(__DIR__ . "/../views/branches/branchEdit.php");
}

// ELIMINAMOS UNA SUCURSAL
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    deleteBranch($_GET['delete']);
    require_once(__DIR__ . "/../views/branches/branchesList.php");
}

include_once(__DIR__ . "/../views/footer.php");
