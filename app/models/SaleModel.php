<?php
require_once(__DIR__ . "/../../config/db.php");

// Registrar cliente desde la creación de la venta
if (isset($_POST["action"]) and $_POST["action"] == "client_register") {
    $alert = "";
    $dni = $_POST['client_dni'] ?? 1;
    $name = $_POST['client_name'] ?? "nombre_hardcode";
    $phoneNumber = $_POST['client_phone'] ?? 1;
    $address = $_POST['client_address'] ?? "direccion_hardcode";
    $email = $_POST['client_email'] ?? "correo_hardcode";
    $userCreatedId = $_SESSION["id_user"] ?? 9;
    $branchId = $_SESSION["branchId"];
    $storeId = $_SESSION['storeId'];

    $query = mysqli_query($MYSQLI, "SELECT * FROM clients WHERE EMAIL = '$email'");
    $result = mysqli_fetch_array($query);

    if ($result > 0) {
        $alert = '<div class="alert alert-danger" role="alert">El email ya existe.</div>';
    } else {
        $query_insert = mysqli_query($MYSQLI, "INSERT INTO clients (DNI, NAME, phoneNumber, ADDRESS, EMAIL, branchId, UserCreatedId, storeId) VALUES ($dni, '$name', '$phoneNumber', '$address', '$email', $branchId, $userCreatedId, $storeId)");

        if ($query_insert) {
            $alert = '<div class="alert alert-primary" role="alert">Cliente registrado satisfactoriamente.</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al guardar el cliente.</div>';
        }
    }
}

if(isset($_POST['action']) && $_POST['action']== 'ExtraCost') {
    session_start();
    $tipo = $_POST['tipo'];
    $monto = $_POST['monto'];
    $token = md5($_SESSION['user_id']);

    $query_procesar = mysqli_query($MYSQLI,"CALL sp_register_extra_cost ('$token', $monto,'$tipo')");
    echo json_encode(["status" => "success", "message" => "Gasto extra agregado."]);
    exit;
}

// Mostrar los totales actualizados, incluyendo gastos extra
if (isset($_POST['action']) && $_POST['action'] === 'getTotals') {
    session_start();
    $subtotal = 0;
    $totalCosto = 0;
    $totalVenta = 0;
    $extraCostsTotal = 0;
    
    $query_totals = mysqli_query($MYSQLI, "SELECT * FROM temporal_ventas WHERE token_user = '" . md5($_SESSION['user_id']) . "'");
    while ($data = mysqli_fetch_assoc($query_totals)) {
        $precioTotal = round($data['PrecioFinal'], 2);
        $cantidad = $data['quantity'];
        $totalProduct = round($precioTotal * $cantidad, 2);
        $subtotal += $totalProduct;
        $totalCosto += round($data['costo'] * $cantidad, 2);
        $totalVenta += $totalProduct;
    }
    
    if (!empty($_SESSION['extra_costs'])) {
        foreach ($_SESSION['extra_costs'] as $extra) {
            $extraCostsTotal += $extra['monto'];
        }
    }
    
    $impuesto = $subtotal * 0.16;
    $totalConIva = round($subtotal + $impuesto, 2);
    $totalFinal = $totalConIva + $extraCostsTotal;
    $rentabilidad = $totalVenta - $totalCosto - $extraCostsTotal;
    
    $detalleTotales = '<tr>
            <td colspan="5"><b>Subtotal</b></td>
            <td><b>$ ' . number_format($subtotal, 2) . '</b></td>
        </tr>
        <tr>
            <td colspan="5"><b>IVA (16%)</b></td>
            <td><b>$ ' . number_format($impuesto, 2) . '</b></td>
        </tr>';
    
    if ($extraCostsTotal > 0) {
        foreach ($_SESSION['extra_costs'] as $extra) {
            $detalleTotales .= '<tr>
                <td colspan="5"><b>' . htmlspecialchars($extra['tipo']) . '</b></td>
                <td><b>$ ' . number_format($extra['monto'], 2) . '</b></td>
            </tr>';
        }
    }
    
    $detalleTotales .= '<tr>
            <td colspan="5"><b>Total con IVA</b></td>
            <td><b>$ ' . number_format($totalConIva, 2) . '</b></td>
        </tr>
        <tr>
            <td colspan="5"><b>Total Final (incluyendo gastos extra)</b></td>
            <td><b>$ ' . number_format($totalFinal, 2) . '</b></td>
        </tr>
        <tr>
            <td colspan="5"><b>Rentabilidad</b></td>
            <td><b>$ ' . number_format($rentabilidad, 2) . '</b></td>
        </tr>';
    
    echo json_encode(["detalle_totales" => $detalleTotales], JSON_UNESCAPED_UNICODE);
    mysqli_close($MYSQLI);
    exit;
}


// AJAX, Anular Venta
if (isset($_POST['action']) and $_POST['action'] == 'cancelSale') {
    session_start();

    $data = "";
    $token = md5($_SESSION['user_id']);
    $query_del = mysqli_query($MYSQLI, "DELETE FROM temporary_details WHERE tokenUser = '$token'");

    mysqli_close($MYSQLI);

    if ($query_del) {
        echo 'ok';
    } else {
        $data = 0;
    }

    exit;
}


if (isset($_POST['action']) and $_POST['action'] == 'updateStatus') {
    session_start();
    $id = $_POST['saleId'];
    $status = $_POST['status'];

    $query_update = mysqli_query($MYSQLI, "UPDATE invoices SET status = '$status' WHERE id = $id");

    if ($query_update) {
        echo json_encode(["status" => "success", "message" => "Estatus actualizado."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al actualizar el estatus."]);
    }

    mysqli_close($MYSQLI);
    exit;
}

// AJAX, Generar nueva venta
if (isset($_POST['action']) and $_POST['action'] == 'processSale') {
    session_start();

    $client_id = intval($_POST['clientId']);
    $token = md5($_SESSION['user_id']);
    $userCreatedId = intval($_SESSION['user_id']);
    $total = intval($_POST['total']);
    $branchId = intval($_SESSION['branchId']);
    $products = json_decode($_POST['products'], true);
    
    $storeId = isset($_SESSION['storeId']) ? intval($_SESSION['storeId']) : 0;
    $tipo = $_POST['tipo'] ?? '';


    // Verifica storeId
    if ($storeId == 0) {
        die(json_encode(["error" => "storeId no es válido."]));
    }

    print_r($_POST);

    $query_procesar = mysqli_query($MYSQLI, "CALL sp_process_sale($userCreatedId, $client_id, '$token', $total, $branchId, $storeId)");

    if (!$query_procesar) {
        die(json_encode(["error" => "Error en la consulta: " . mysqli_error($MYSQLI)]));
    }

    // Si hay más resultados, avanzar hasta el SELECT final
    if ($MYSQLI->more_results()) {
        $MYSQLI->next_result();
    }

    $data = mysqli_fetch_assoc($query_procesar);

    if ($data) {
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["error" => "No se pudo obtener la factura generada"]);
    }

    mysqli_close($MYSQLI);
    exit;
}
    



function getSales() {
    global $MYSQLI;

    $query = "
        SELECT 
            invoices.*, 
            users.name AS user, 
            invoices.userCreatedId AS user_id,
            invoice_details.PrecioFinal,  -- PrecioFinal de invoice_details
            invoice_details.costo,  
            invoice_details.sellingPrice  -- Costo de invoice_details
        FROM 
            invoices 
        JOIN 
            users 
        ON 
            invoices.userCreatedId = users.id 
        JOIN 
            invoice_details 
        ON 
            invoices.id = invoice_details.id 
        ORDER BY 
            invoices.date DESC
    ";

    $result = mysqli_query($MYSQLI, $query);

    if (!$result) {
        die("Error en la consulta: " . mysqli_error($MYSQLI));
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Calculamos la rentabilidad si el costo es mayor a cero
        $costo = $row['costo'] ?? 0;
        $precioFinal = $row['PrecioFinal'] ?? 0;
        $rentabilidad = (($row['PrecioFinal'] - $row['sellingPrice'] - $row['costo']) / ($row['sellingPrice'] + $row['costo'])) * 100;


        // Agregamos la rentabilidad a la fila
        $row['rentabilidad'] = $rentabilidad;

        $rows[] = $row;
    }


    return $rows;
}
function getSalesForDate(string $from, string $to): array
{
    global $MYSQLI;

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    $query = "SELECT * FROM invoices WHERE DATE(DATE) BETWEEN '$from' AND '$to'";
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
