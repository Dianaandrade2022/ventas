<?php

require_once(__DIR__ . "/../../config/db.php");

// BUSCAR CLIENTE CUANDO ESTAMOS EN LA VISTA DE VENTAS O PRESUPUESTOS
if (isset($_POST['action']) && $_POST['action'] == 'clientSearch') {

    if (!empty($_POST['client_dni'])) {

        $dni = $_POST['client_dni'];
        $query = mysqli_query($MYSQLI, "SELECT * FROM clients WHERE DNI LIKE '$dni'");
        $result = mysqli_num_rows($query);
        $data = '';

        if ($result > 0) {
            $data = mysqli_fetch_assoc($query);
        } else {
            $data = 0;
        }

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function addClient()
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    $alert = "";

    if (empty($_POST['dni']) || empty($_POST['name']) || empty($_POST['phoneNumber']) || empty($_POST['address']) || empty($_POST['email'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios.</div>';
    } else {
        $dni = filter_var($_POST['dni'], FILTER_SANITIZE_NUMBER_INT);
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
        $phoneNumber = filter_var($_POST['phoneNumber'], FILTER_SANITIZE_NUMBER_INT);
        $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $branchId = $_SESSION['branchId'];
        /* $storeId = $_SESSION['storeId']; */
        $storeId = $_POST['storeId'];
        $usuario_id = $_SESSION['user_id'];

        if ($MYSQLI->connect_error) {
            die("Error de conexión: " . $MYSQLI->connect_error);
        }
 
        if (is_numeric($dni) && $dni != 0) {
            $stmt = $MYSQLI->prepare("SELECT * FROM clients WHERE DNI = ?");
            $stmt->bind_param("i", $dni);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $alert = '<div class="alert alert-danger" role="alert">El DNI ya existe.</div>';
            } else {
                $stmt_insert = $MYSQLI->prepare("INSERT INTO clients (DNI, NAME, phoneNumber, ADDRESS, EMAIL, branchId, storeId, UserCreatedId) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt_insert->bind_param("issssiii", $dni, $name, $phoneNumber, $address, $email, $branchId, $storeId, $usuario_id);

                if ($stmt_insert->execute()) {
                    $alert = '<div class="alert alert-primary" role="alert">Cliente registrado satisfactoriamente.</div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">Error al guardar el cliente.</div>';
                }
                $stmt_insert->close();
            }
            $stmt->close();
        }
    }
    return $alert;
}

function getClients()
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    $query = "SELECT * FROM clients";
    $result = mysqli_query($MYSQLI, $query);

    if (!$result) {
        die("Query execution error: " . mysqli_error($MYSQLI));
    }

    $clients = [];

    while ($client = mysqli_fetch_assoc($result)) {
        $clients[] = $client;
    }
    mysqli_free_result($result);

    return $clients;
}

function getClientById($client_id)
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    if ($MYSQLI->connect_error) {
        die("Error de conexión: " . $MYSQLI->connect_error);
    }

    $stmt = $MYSQLI->prepare("SELECT * FROM clients WHERE ID = ?");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result_cliente = $stmt->get_result();

    if ($result_cliente->num_rows == 0) {
        $stmt->close();
        return null;
    }

    $data_cliente = $result_cliente->fetch_assoc();
    $stmt->close();

    return $data_cliente;
}

function editClient()
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }
    
    $alert = "";

    if (empty($_POST['name']) || empty($_POST['phoneNumber']) || empty($_POST['address'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios.</div>';
    } else {
        $client_id = $_POST['id'];
        $name = $_POST['name'];
        $phoneNumber = $_POST['phoneNumber'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $userUpdatedId = $_SESSION['id_user'];
        /* $storeId = $_SESSION['storeId']; */
        $storeId = $_POST['storeId'];

        $stmt = $MYSQLI->prepare("UPDATE clients SET name = ?, phoneNumber = ?, address = ?, EMAIL = ?, userUpdatedId = ?, storeId = ?, updatedAt = NOW() WHERE ID = ?");
        $stmt->bind_param("ssssiii", $name, $phoneNumber, $address, $email, $userUpdatedId, $storeId, $client_id);

        if ($stmt->execute()) {
            $alert = '<div class="alert alert-primary" role="alert">Cliente actualizado correctamente.</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al actualizar el cliente.</div>';
        }

        $stmt->close();
    }

    return $alert;
}

function deleteClient($id)
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    if (is_numeric($id)) {
        $stmt = $MYSQLI->prepare("DELETE FROM clients WHERE ID = ?");
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
