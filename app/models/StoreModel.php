<?php

include_once(__DIR__ . "/../../config/db.php");

// ACTUALIZAMOS LOS DATOS DE LA TIENDA
/* if (isset($_POST["edit_store"]) == "edit_store") {

    $alert = "";

    if (empty($_POST['name']) || empty($_POST['phoneNumber']) || empty($_POST['address'])) {
        $alert = '<p class"error">Todos los campos son requeridos</p>';
    } else {
        $store_id = $_POST['id'];
        $name = $_POST['name'];
        $phoneNumber = $_POST['phoneNumber'];
        $address = $_POST['address'];
        $usuario_id = $_SESSION['id_user'];
        $result = 0;

        $sql_update = mysqli_query($MYSQLI, "UPDATE STORE _CONFIGURATIONS SET NAME = '$name' , phoneNumber = '$phoneNumber', ADDRESS = '$address', userCreatedId = $userCreatedId WHERE ID = $store_id");

        if ($sql_update) {
            $alert = '<p class"exito">Tienda actualizada correctamente</p>';
        } else {
            $alert = '<p class"error">Error al actualizar la tienda</p>';
        }
    }
} */

function addStore()
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    if (empty($_POST['phoneNumber']) || empty($_POST['address'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>';
    } else {
        $rfc = $_POST['rfc'];
        $name = $_POST['name'];
        $phoneNumber = $_POST['phoneNumber'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        /* $branchId = $_SESSION['branchId']; */
        $userCreatedId = $_SESSION['user_id'];

        // Consultar si el rfc ya está registrado
        $stmt = $MYSQLI->prepare("SELECT * FROM stores WHERE rfc = ?");
        $stmt->bind_param("s", $rfc);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $alert = '<div class="alert alert-danger" role="alert">El rfc ya está registrado con otra tienda</div>';
        } else {
            // Insertar nueva tienda
            $stmt_insert = $MYSQLI->prepare("INSERT INTO stores (rfc, NAME, phoneNumber, email, ADDRESS, userCreatedId) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("ssssii", $rfc, $name, $phoneNumber, $email, $address, $userCreatedId);

            if ($stmt_insert->execute()) {
                $alert = '<div class="alert alert-primary" role="alert">Tienda registrada</div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">Error al registrar tienda</div>';
            }

            $stmt_insert->close();
        }

        $stmt->close();
    }

    return $alert;
}

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

function getStoreById($id_store)
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    $stmt = $MYSQLI->prepare("SELECT * FROM stores WHERE ID = ?");
    $stmt->bind_param("i", $id_store);
    $stmt->execute();
    $result_store = $stmt->get_result();

    if ($result_store->num_rows == 0) {
        $stmt->close();
        return null;
    }

    $data_store = $result_store->fetch_assoc();
    $stmt->close();

    return $data_store;
}

function editStore()
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    $alert = "";

    if (empty($_POST['name']) || empty($_POST['phoneNumber']) || empty($_POST['address'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios.</div>';
    } else {
        $id = $_POST['id'];
        $rfc = $_POST['rfc'];
        $name = $_POST['name'];
        $tradeName     = $_POST['tradeName'];
        $phoneNumber = $_POST['phoneNumber'];
        $email     = $_POST['email'];
        $address = $_POST['address'];
        $iva     = $_POST['iva'];
        /* $branchId = $_SESSION['branchId']; */
        $userUpdatedId = $_SESSION['user_id'];

        $stmt = $MYSQLI->prepare("UPDATE stores SET rfc = ?, NAME = ?, tradeName = ?, phoneNumber = ?, EMAIL = ?, ADDRESS = ?, IVA = ?, userUpdatedId = ?, updatedAt = NOW() WHERE ID = ?");
        $stmt->bind_param("sssissiii", $rfc, $name, $tradeName, $phoneNumber, $email, $address, $iva, $userUpdatedId, $id);


        if ($stmt->execute()) {
            $alert = '<div class="alert alert-primary" role="alert">Tienda actualizada correctamente.</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al actualizar la tienda.</div>';
        }

        $stmt->close();
    }

    return $alert;
}

function deleteStore($id)
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    if (is_numeric($id)) {
        $stmt = $MYSQLI->prepare("DELETE FROM stores WHERE ID = ?");
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

function getBranchById($id)
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }
    $stmt = $MYSQLI->prepare("SELECT * FROM branches WHERE ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $branchId = $result->fetch_assoc();
    $stmt->close();
    return $branchId;
}

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
