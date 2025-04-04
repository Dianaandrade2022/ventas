<?php

session_start();

if (!isset($_SESSION['loggedin']) && $_GET['page'] != 'login') {
    header("Location: ../../index.php?page=login");
    exit();
}

include_once(__DIR__ . "/../views/header.php");
include_once(__DIR__ . "/../views/nav.php");
require_once(__DIR__ . "/../models/UserModel.php");

$alert = '';
$data_user = [];

// MOSTRAMOS LA VISTA PARA DAR DE ALTA UN NUEVO USUARIO
if (isset($_REQUEST["name"]) && $_REQUEST["name"] === "user_new" && !isset($_POST["add_user"])) {
    $branches = getBranches();
    $stores = getStores();
    $roles = getRoles();
    require_once(__DIR__ . "/../views/users/userRegister.php");
}

// RECIBIMOS EL POST Y CREAMOS UN NUEVO USUARIO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_user"])) {
    $branches = getBranches();
    $stores = getStores();
    $roles = getRoles();
    $alert = addUser();
    require_once(__DIR__ . "/../views/users/userRegister.php");
}

// MOSTRAMOS LA LISTA DE users
if (isset($_REQUEST["name"]) && $_REQUEST["name"] === "users_list") {
    require_once(__DIR__ . "/../views/users/usersList.php");
}

// EDITAMOS UN USUARIO
if (isset($_REQUEST["id"]) && is_numeric($_REQUEST["id"]) && !isset($_POST["edit_user"]) and !isset($_REQUEST["password_update"])) {
    $branches = getBranches();
    $stores = getStores();
    $data_user = getUserById($_REQUEST["id"]);
    require_once(__DIR__ . "/../views/users/userEdit.php");
}

// ACTUALIZAMOS EL USUARIO EDITADO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit_user"])) {
    $branches = getBranches();
    $stores = getStores();
    $alert = editUser();
    $data_user = getUserById($_POST["id"]);
    $data_company_user = getNameBranchById($_POST["id"]);
    require_once(__DIR__ . "/../views/users/userEdit.php");
}

// MOSTRAMOS FORMULARIO PARA ACTUALIZAR LA CONTRASEÑA DE UN USUARIO
if (isset($_GET["password_update"])) {
    require_once(__DIR__ . "/../views/users/userUpdatePassword.php");
}

// ACTUALIZAMOS LA CONTRASEÑA DE UN USUARIO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["password_update"])) {
    $alert = userUpdatePassword();
    require_once(__DIR__ . "/../views/users/userUpdatePassword.php");
}

// ELIMINAMOS UN USUARIO
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    deleteUser($_GET['delete']);
    require_once(__DIR__ . "/../views/users/usersList.php");
}

include_once(__DIR__ . "/../views/footer.php");
