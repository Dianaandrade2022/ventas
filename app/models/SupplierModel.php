<?php

include_once(__DIR__ . "/../../config/db.php");

// ACTUALIZAMOS LOS DATOS DEL PROVEEDOR
/* if (isset($_POST["edit_provider"]) == "edit_provider") {

    $alert = "";

    if (empty($_POST['name']) || empty($_POST['phoneNumber']) || empty($_POST['address'])) {
        $alert = '<p class"error">Todo los campos son requeridos</p>';
    } else {
        $id_proveedor = $_POST['id'];
        $name = $_POST['name'];
        $phoneNumber = $_POST['phoneNumber'];
        $address = $_POST['address'];
        $userUpdatedId = $_SESSION['id_user'];
        // $storeId = $_SESSION['storeId'];
        $storeId = $_POST['storeId'];

        $result = 0;

        $sql_update = mysqli_query($MYSQLI "UPDATE suppliers SET NAME = '$name' , phoneNumber = '$phoneNumber', ADDRESS = '$address', storeId = $storeId, userUpdatedId = $userUpdatedId WHERE ID = $id_proveedor");

        if ($sql_update) {
            $alert = '<p class"exito">Proveedor actualizado correctamente</p>';
        } else {
            $alert = '<p class"error">Error al Actualizar el supplier</p>';
        }
    }
} */

function addSupplier()
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }
    $alert = "";

    // Verificar si los campos obligatorios están presentes y no están vacíos
    if (empty($_POST['name']) || empty($_POST['phoneNumber']) || empty($_POST['address'])) {
        $alert = '<div class="alert alert-danger" role="alert"> Todos los campos son obligatorios </div>';
    } else {
        // Obtener datos del formulario
        $name = $_POST['name'];
        $phoneNumber = $_POST['phoneNumber'];
        $address = $_POST['address'];
        $userCreatedId = $_SESSION['user_id'];
        $branchId = $_SESSION['branchId'];
        /* $storeId = $_SESSION['storeId']; */
        $storeId = $_POST['storeId'];

        // Validar si el phoneNumber ya está registrado con otro proveedor
        $query = $MYSQLI->prepare("SELECT * FROM suppliers WHERE phoneNumber = ?");
        $query->bind_param("s", $phoneNumber);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $alert = '<div class="alert alert-danger" role="alert"> El phoneNumber ya está registrado con otro proveedor </div>';
        } else {
            // Insertar nuevo proveedor
            $query_insert = $MYSQLI->prepare("INSERT INTO suppliers (NAME, phoneNumber, ADDRESS, branchId, userCreatedId, storeId) VALUES (?, ?, ?, ?, ?, ?)");
            $query_insert->bind_param("sssiis", $name, $phoneNumber, $address, $branchId, $userCreatedId, $storeId);
            $query_insert->execute();

            if ($query_insert->affected_rows > 0) {
                $alert = '<div class="alert alert-primary" role="alert"> Proveedor registrado correctamente </div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert"> Error al registrar proveedor </div>';
            }

            // Cerrar consulta preparada de inserción
            $query_insert->close();
        }

        // Cerrar consulta preparada de selección
        $query->close();
    }

    // Cerrar conexión MySQLi
   $MYSQLI->close();

    return $alert;
}

function getSuppliers()
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    $query = "SELECT * FROM suppliers ORDER BY NAME ASC";
    $result = mysqli_query($MYSQLI, $query);

    if (!$result) {
        die("Query execution error: " . mysqli_error($MYSQLI));
    }

    $rows = [];


    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    mysqli_free_result($result);


    return $rows;

}

function getSupplierById($id_provider)
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    if ($MYSQLI->connect_error) {
        die("Error de conexión: " .  $MYSQLI->connect_error);
    }

    $stmt = $MYSQLI->prepare("SELECT * FROM suppliers WHERE ID = ?");
    $stmt->bind_param("i", $id_provider);
    $stmt->execute();
    $result_supplier = $stmt->get_result();

    if ($result_supplier->num_rows == 0) {
        $stmt->close();
        return null;
    }

    $data_supplier = $result_supplier->fetch_assoc();
    $stmt->close();

    return $data_supplier;
}

function editSupplier()
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }
    $alert = "";

    if (empty($_POST['name']) || empty($_POST['phoneNumber']) || empty($_POST['address'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios.</div>';
    } else {
        $supplier_id = $_POST['id'];
        $name = $_POST['name'];
        $phoneNumber = $_POST['phoneNumber'];
        $address = $_POST['address'];
        $userUpdatedId = $_SESSION['user_id'];
        /* $storeId = $_SESSION['storeId']; */
        $storeId = $_POST['storeId'];

        $stmt = $MYSQLI->prepare("UPDATE suppliers SET NAME = ?, phoneNumber = ?, ADDRESS = ?, userUpdatedId = ?, storeId = ?, updatedAt = NOW() WHERE ID = ?");
        $stmt->bind_param("sssiii", $name, $phoneNumber, $address, $userUpdatedId, $storeId,  $supplier_id);

        if ($stmt->execute()) {
            $alert = '<div class="alert alert-primary" role="alert">Proveedor actualizado correctamente.</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al actualizar el supplier.</div>';
        }

        $stmt->close();
    }

    return $alert;
}

function deleteSupplier($id)
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    if (is_numeric($id)) {
        $stmt = $MYSQLI->prepare("DELETE FROM suppliers WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }
    return false;
}

// ESTO LO USAMOS EN LA CREACION Y EDICION DEL PROVEEDOR
function getStores()
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    $query = "SELECT * FROM stores ORDER BY NAME ASC";
    $result = mysqli_query($MYSQLI, $query);

    if (!$result) {
        die("Query execution error: " . mysqli_error($MYSQLI));
    }

    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    mysqli_free_result($result);


    return $rows;
}

// ESTO LO USAMOS EN LA CREACION Y EDICION DEL PROVEEDOR
function getBranches()
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    $query = "SELECT * FROM branches";
    $result = mysqli_query($MYSQLI, $query);

    if (!$result) {
        die("Query execution error: " . mysqli_error($MYSQLI));
    }
    $branches = [];
    while ($role = mysqli_fetch_assoc($result)) {
        $branches[] = $role;
    }
    mysqli_free_result($result);
    return $branches;
}
