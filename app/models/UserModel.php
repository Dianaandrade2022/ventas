<?php

require_once(__DIR__ . "/../../config/db.php");
require_once(__DIR__ . "/../../functions/functions.php");

// EDITAMOS AL USUARIO
/* if (isset($_POST["edit_user"]) == "edit_user") {

    $alert = "";

    if (empty($_POST['name']) || empty($_POST['email'])) {
        $alert = '<p class"error">Todo los campos son requeridos</p>';
    } else {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $userUpdatedId = $_SESSION['id_user'];
        // $storeId = $_SESSION['storeId'];
        $storeId = $_POST['storeId'];

        $sql_update = mysqli_query(MYSQLI, "UPDATE users SET NAME = '$name', EMAIL = '$email', ROLE = '$role', userUpdatedId = $userUpdatedId, storeId = $storeId WHERE ID = $id");
        $alert = '<p>Usuario Actualizado</p>';
    }
} */

function login($email, $password) {
    global $MYSQLI;

    $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = $MYSQLI->prepare($query);

    if(!$stmt) {
        die("Error al preparar la consulta: " . mysqli_error($MYSQLI));
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    $data_user_active = mysqli_fetch_assoc($result);

    if (!$data_user_active) {
        return false;
    }
        if (password_verify($password, $data_user_active['password'])) {
            $_SESSION["active"]=true;
            $_SESSION['user_id'] = $data_user_active['id'];
            $_SESSION['name'] = $data_user_active['name'];
            $_SESSION['email'] = $data_user_active['email'];
            $_SESSION['role'] = $data_user_active['role'];
            $_SESSION['branchId'] = $data_user_active['branchId'];
            $_SESSION['storeId'] = $data_user_active['storeId'];
            $_SESSION['loggedin'] = true;

            mysqli_stmt_close($stmt);

            return true;
        }
   
        return false;
}

function addUser()
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    $alert = "";

    if (empty($_POST['role']) || empty($_POST['email']) || empty($_POST['branchId']) || empty($_POST['name']) || empty($_POST['password'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios.</div>';
    } else {
        $role = $_POST['role'];
        $email = $_POST['email'];
        $branchId = $_POST['branchId'];
        $name = $_POST['name'];
        $password = $_POST['password'];
        $userCreatedId = $_SESSION['user_id'];
        /* $storeId = $_SESSION['storeId']; */
        $storeId = $_POST['storeId'];


        $stmt = $MYSQLI->prepare("SELECT * FROM users WHERE EMAIL = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $alert = '<div class="alert alert-danger" role="alert">El email ya está registrado con otro usuario.</div>';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt_insert = $MYSQLI->prepare("INSERT INTO users (ROLE, EMAIL, branchId, NAME, PASSWORD, userCreatedId, storeId) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("sssssii", $role, $email, $branchId, $name, $hashed_password, $userCreatedId, $storeId);

            if ($stmt_insert->execute()) {
                $alert = '<div class="alert alert-primary" role="alert">Usuario Registrado.</div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">Error al registrar el usuario.</div>';
            }
            $stmt_insert->close();
        }
        $stmt->close();
    }
    return $alert;
}

function getUserById($user_id)
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    if ($MYSQLI->connect_error) {
        die("Error de conexión: " . $MYSQLI->connect_error);
    }

    $stmt = $MYSQLI->prepare("SELECT * FROM users WHERE ID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result_user = $stmt->get_result();

    if ($result_user->num_rows == 0) {
        $stmt->close();
        return null;
    }

    $data_user = $result_user->fetch_assoc();
    $stmt->close();

    return $data_user;
}

function getUsers()
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }
    $query = "SELECT u.id as id_usuario, u.name as nombre_usuario, u.email, r.name as nombre_rol, u.branchId 
              FROM users u 
              INNER JOIN roles r ON u.role = r.id";
    $result = mysqli_query($MYSQLI, $query);

    if (!$result) {
        die("Query execution error: " . mysqli_error($MYSQLI));
    }

    $users = [];
    while ($user = mysqli_fetch_assoc($result)) {
        $users[] = $user;
    }

    mysqli_free_result($result);

    return $users;
}

function editUser()
{
    global $MYSQLI; 

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    $alert = "";

    if (empty($_POST['name']) || empty($_POST['email'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios.</div>';
    } else {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $userUpdatedId = $_SESSION['user_id'];
        /* $storeId = $_SESSION['storeId']; */
        $storeId = $_POST['storeId'];

        $stmt = $MYSQLI->prepare("UPDATE users SET NAME = ?, EMAIL = ?, userUpdatedId = ?, storeId = ?, updatedAt = NOW() WHERE ID = ?");
        $stmt->bind_param("ssiii", $name, $email, $userUpdatedId, $storeId, $id);

        if ($stmt->execute()) {
            $alert = '<div class="alert alert-primary" role="alert">Usuario actualizado correctamente.</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al actualizar el usuario.</div>';
        }

        $stmt->close();
    }

    return $alert;
}

function userUpdatePassword()
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    $alert = '';

    if (empty($_POST['password_actual']) || empty($_POST['password_new']) || empty($_POST['password_new_repeat'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios.</div>';
    } elseif ($_POST['password_new'] !== $_POST['password_new_repeat']) {
        $alert = '<div class="alert alert-danger" role="alert">Las nuevas contraseñas no coinciden.</div>';
    } else {
        $id = $_POST['id'];
        $password_actual = $_POST['password_actual'];
        $password_new = $_POST['password_new'];
        $userUpdatedId = $_SESSION['user_id'];
        /* $storeId = $_SESSION['storeId']; */
        $storeId = isset($_POST['storeId']) ? $_POST['storeId'] : null;

        $stmt = $MYSQLI->prepare("SELECT password FROM users WHERE ID = ?");
        $stmt->bind_param("i", $userUpdatedId);
        $stmt->execute();
        $result_user = $stmt->get_result();

        if ($result_user->num_rows == 0) {
            $alert = '<div class="alert alert-danger" role="alert">Usuario no encontrado.</div>';
        } else {
            $data_user = $result_user->fetch_assoc();
            $hashed_password = $data_user["password"];

            if (password_verify($password_actual, $hashed_password)) {
                $hashed_password_new = password_hash($password_new, PASSWORD_DEFAULT);

                if ($storeId !== null) {
                    $update_stmt = $MYSQLI->prepare("UPDATE users SET PASSWORD = ?, userUpdatedId = ?, storeId = ?, updatedAt = NOW() WHERE ID = ?");
                    $update_stmt->bind_param("siii", $hashed_password_new, $userUpdatedId, $storeId, $id);
                } else {
                    $update_stmt = $MYSQLI->prepare("UPDATE users SET PASSWORD = ?, userUpdatedId = ?, updatedAt = NOW() WHERE ID = ?");
                    $update_stmt->bind_param("sii", $hashed_password_new, $userUpdatedId, $id);
                }

                if ($update_stmt->execute()) {
                    $alert = '<div class="alert alert-primary" role="alert">Contraseña actualizada correctamente.</div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">Error al actualizar la contraseña.</div>';
                }
            } else {
                $alert = '<div class="alert alert-danger" role="alert">La contraseña actual es incorrecta.</div>';
            }
        }
    }

    return $alert;
}

function deleteUser($id)
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    if (is_numeric($id)) {
        $stmt = $MYSQLI->prepare("DELETE FROM users WHERE ID = ?");
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

/* function getNameRoleById()
{

    $stmt = $MYSQLI->prepare("SELECT * FROM roles WHERE ID = ?");
    $stmt->bind_param("i", $_SESSION['role']);
    $stmt->execute();
    $result_role = $stmt->get_result();

    if ($result_role->num_rows == 0) {
        $stmt->close();
        return null;
    }

    $data_name_role = $result_role->fetch_assoc();
    $stmt->close();

    return $data_name_role;
} */

function getRoles()
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }
    $query = "SELECT * FROM roles";
    $result = mysqli_query($MYSQLI, $query);

    if (!$result) {
        die("Query execution error: " . mysqli_error($MYSQLI));
    }

    $roles = [];

    while ($role = mysqli_fetch_assoc($result)) {
        $roles[] = $role;
    }

    mysqli_free_result($result);

    return $roles;
}

function getNameBranchById($branchId)
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    $stmt = $MYSQLI->prepare("SELECT * FROM branches WHERE ID = ?");
    $stmt->bind_param("i", $branchId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return null;
    }

    $branch = $result->fetch_assoc();
    $stmt->close();

    return $branch;
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
?>
