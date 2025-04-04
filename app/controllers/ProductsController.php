<?php

session_start();

if (!isset($_SESSION['loggedin']) && $_GET['page'] != 'login') {
    header("Location: ../../index.php?page=login");
    exit();
}

include_once(__DIR__ . "/../views/header.php");
include_once(__DIR__ . "/../views/nav.php");
require_once(__DIR__ . "/../models/ProductModel.php");

$alert = "";
$data_producto = [];

// AGREGAMOS UN NUEVO PRODUCTO
if (isset($_REQUEST["name"]) && $_REQUEST["name"] === "product_new") {
    $branches = getBranches();
    $stores = getStores();
    require_once(__DIR__ . "/../views/products/productRegister.php");
}

// AGREGAMOS UN NUEVO PRODUCTO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_product"])) {
    $branches = getBranches();
    $stores = getStores();
    $alert = addProduct();
    require_once(__DIR__ . "/../views/products/productRegister.php");
}

// LISTAMOS LOS products
if (isset($_REQUEST["name"]) && $_REQUEST["name"] === "product_list") {
    require_once(__DIR__ . "/../views/products/productList.php");
}

// Editamos el product
if (isset($_REQUEST["id"]) && isset($_REQUEST["user_id"]) && isset($_REQUEST["provider_id"])) {
    $branches = getBranches();
    $stores = getStores();
    $data_producto = getProductByid();
    require_once(__DIR__ . "/../views/products/productEdit.php");
}

// MOSTRAMOS EL PRODUCTO EDITADO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit_product"])) {
    $branches = getBranches();
    $stores = getStores();
    $alert = editProduct();
    $data_producto = getProductByid();
    require_once(__DIR__ . "/../views/products/productEdit.php");
}

// ELIMINAMOS UN PRODUCTO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_product"])) {
    $alert = deleteProductById();
    require_once(__DIR__ . "/../views/products/productList.php");
}

// AUMENTAMOS DE MANERA MASIVA TODOS LOS PRODUCTOS
if (isset($_REQUEST["name"]) and $_REQUEST["name"] === "product_increment_price_massive") {
    $alert = newPriceMassive();
    require_once(__DIR__ . "/../views/products/massivePriceIncrease.php");
}

// AUMENTAMOS TODOS LOS PRODUCTOS DE UN PROVEEDOR
if (isset($_REQUEST["name"]) and $_REQUEST["name"] === "product_increment_price_for_supplier") {
    $alert = updatedProduct();
    require_once(__DIR__ . "/../views/products/increasePricesBySupplier.php");
}

include_once(__DIR__ . "/../views/footer.php");
