<?php

session_start();

if (!isset($_SESSION['loggedin']) && $_GET['page'] != 'login') {
    header("Location: ../../index.php?page=login");
    exit();
}

include_once(__DIR__ . "/../views/header.php");
include_once(__DIR__ . "/../views/nav.php");
require_once(__DIR__ . "/../models/SupplierModel.php");

$alert = '';
$data_supplier = [];

// MOSTRAMOS LA VISTA PARA DAR DE ALTA UN NUEVO PROVEEDOR
if (isset($_REQUEST["name"]) && $_REQUEST["name"] === "supplier_new" && !isset($_POST["add_supplier"])) {
    $branches = getBranches();
    $stores = getStores();
    require_once(__DIR__ . "/../views/suppliers/supplierRegister.php");
}

// RECIBIMOS EL POST Y CREAMOS UN NUEVO PROVEEDOR
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_supplier"])) {
    $branches = getBranches();
    $stores = getStores();
    $alert = addSupplier();
    require_once(__DIR__ . "/../views/suppliers/supplierRegister.php");
}

// Mostramos la lista de clientes
if (isset($_REQUEST["name"]) && $_REQUEST["name"] === "suppliers_list") {
    require_once(__DIR__ . "/../views/suppliers/suppliersList.php");
}

// Editamos el cliente
if (isset($_REQUEST["id"]) && is_numeric($_REQUEST["id"]) && !isset($_POST["edit_supplier"])) {
    $branches = getBranches();
    $stores = getStores();
    $data_supplier = getSupplierById($_REQUEST["id"]);
    require_once(__DIR__ . "/../views/suppliers/supplierEdit.php");
}

// ACTUALIZAMOS EL PROVEEDOR EDITADO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit_supplier"])) {
    $branches = getBranches();
    $stores = getStores();
    $alert = editSupplier();
    $data_supplier = getSupplierById($_POST["id"]);
    require_once(__DIR__ . "/../views/suppliers/supplierEdit.php");
}

// ELIMINAMOS UN PROVEEDOR
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    deleteSupplier($_GET['delete']);
    require_once(__DIR__ . "/../views/suppliers/suppliersList.php");
}

include_once(__DIR__ . "/../views/footer.php");
