<?php

session_start();

if (!isset($_SESSION['loggedin']) && $_GET['page'] != 'login') {
    header("Location: ../../index.php?page=login");
    exit();
}

include_once(__DIR__ . "/../views/header.php");
include_once(__DIR__ . "/../views/nav.php");
require_once(__DIR__ . "/../models/RoleModel.php");

$alert = '';
$data_role = [];

// MOSTRAMOS LA VISTA PARA DAR DE ALTA UN NUEVO ROL
if (isset($_REQUEST["name"]) && $_REQUEST["name"] === "role_new" && !isset($_POST["add_role"])) {
    $branches = getBranches();
    $stores = getStores();
    require_once(__DIR__ . "/../views/roles/roleRegister.php");
}

// RECIBIMOS EL POST Y CREAMOS UN NUEVO ROL
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_role"])) {
    $branches = getBranches();
    $stores = getStores();
    $alert = addRole();
    require_once(__DIR__ . "/../views/roles/roleRegister.php");
}

// MOSTRAMOS LA LISTA DE roles
if (isset($_REQUEST["name"]) && $_REQUEST["name"] === "roles_list") {
    require_once(__DIR__ . "/../views/roles/rolesList.php");
}

// Editamos el cliente
if (isset($_REQUEST["id"]) && is_numeric($_REQUEST["id"]) && !isset($_POST["edit_role"])) {
    $branches = getBranches();
    $stores = getStores();
    $data_role = getRoleById($_REQUEST["id"]);
    require_once(__DIR__ . "/../views/roles/roleEdit.php");
}

// ACTUALIZAMOS EL ROL EDITADO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit_role"])) {
    $branches = getBranches();
    $stores = getStores();
    $alert = editRole();
    $data_role = getRoleById($_POST["id"]);
    require_once(__DIR__ . "/../views/roles/roleEdit.php");
}

// ELIMINAMOS UN ROL
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    deleteRole($_GET['delete']);
    require_once(__DIR__ . "/../views/roles/rolesList.php");
}

include_once(__DIR__ . "/../views/footer.php");
