<?php

require_once(__DIR__ . "/../../config/db.php");
setlocale(LC_MONETARY, 'es_MX');


// Registrar cliente desde la creacion del presupuesto
if (isset($_POST["action"]) and $_POST["action"] == "client_register") {

    $alert = "";
    $dni = $_POST['client_dni'] ?? 1;
    $name = $_POST['client_name'] ?? "nombre_hardcode";
    $phoneNumber = $_POST['client_phone'] ?? 1;
    $address = $_POST['client_address'] ?? "direccion_hardcode";
    $email = $_POST['client_email'] ?? "correo_hardcode";
    $userCreatedId = $_SESSION["id_user"] ?? 9;
    $branchId = $_SESSION["branchId"];
    $storeId = $_POST['storeId'] ?? 0;
    /* $storeId = $_POST['storeId']; */

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

// AJAX, Anular presupuesto
if (isset($_POST['action']) and $_POST['action'] == 'cancelEstimate') {

    session_start();

    $data = "";
    $token = md5($_SESSION['id_user']);
    $query_del = mysqli_query($MYSQLI, "DELETE FROM temporary_details WHERE tokenUser = '$token'");

    mysqli_close($MYSQLI);

    if ($query_del) {
        echo 'ok';
    } else {
        $data = 0;
    }

    exit;
}

// Eliminar product del listado temporal
if (isset($_POST['action']) and $_POST['action'] == 'eliminarProducto') {

    session_start();

    if (empty($_POST['id_detalle'])) {
        echo 'error';
    } else {
        $id_detalle = $_POST['id_detalle'];
        $token = md5($_SESSION['id_user']);
        $query_iva = mysqli_query($MYSQLI, "SELECT * FROM stores");
        $result_iva = mysqli_num_rows($query_iva);
        $query_detalle_tmp = mysqli_query($MYSQLI, "CALL sp_delete_temporal_detail($id_detalle,'$token')");
        $result = mysqli_num_rows($query_detalle_tmp);
        $detalleTabla = '';
        $cost = 0;
        $sub_total = 0;
        $iva = 0;
        $total = 0;
        $data = "";
        $arrayDatadata = array();

        if ($result > 0) {

            if ($result_iva > 0) {
                $info_iva = mysqli_fetch_assoc($query_iva);
                $iva = $info_iva['iva'];
            }

            while ($data = mysqli_fetch_assoc($query_detalle_tmp)) {
                $precioTotal = round($data['cost'] * $data['sellingPrice'] + $data['quantity'], 3);
                $sub_total = round($sub_total + $precioTotal, 2);
                $total = round($total + $precioTotal, 2);

                $detalleTabla .= '<tr>
            <td>' . $data['productId'] . '</td>
            <td colspan="2">' . $data['name'] . '</td>
            <td>' . $data['quantity'] . '</td>
            <td>$ ' . number_format($cost, 2) . '</td>
            <td>$ ' . number_format($data['sellingPrice'], 2) . '</td>
            <td>$ ' . number_format($precioTotal, 2) . '</td>
            <td class="text-center">
                <a href="#" class="btn btn-danger" onclick="event.preventDefault(); deleteEstimateDetail(' . $data['id'] . ');"><i class="fa fa-trash-o"></i>Eliminar Producto</a>
            </td>
            </tr>';
            }

            $impuesto = round(($sub_total * $iva) / 100, 2);
            $tl_sniva = round($sub_total - $impuesto, 2);
            $total = round($tl_sniva + $impuesto, 2);
            $detalleTotales = '<tr>
                <td colspan="5"><b>Sub_Total</b></td>
                <td><b>$ ' . number_format($tl_sniva, 2) . '</b></td>
                </tr>
                <tr>
                    <td colspan="5"><b>IVA (' . $iva . ')</b></td>
                    <td><b>$ ' . number_format($impuesto, 2) . '</b></td>
                </tr>
                <tr>
                    <td colspan="5"><b>Total</b></td>
                    <td><b>$ ' . number_format($total, 2) . '</b></td>
                </tr>
                <tr>
                    <td colspan="5" class="text-left" id="text_total_con_descuento" style="display: none;"><b>Total Con Descuento</b></td>
                    <td colspan="2" class="text-left pl-1"><b><span style="display:none;" id="span_total_con_descuento">$</span> <input type="text" id="total" value=0 style="border:none; readonly; display:none"></b></input></td>
                    <td colspan="5" class="text-right">
                      <a href="newSimate.php" class="btn btn-success mx-2 px-4" id="apply3PercentDiscount" onclick="event.preventDefault(); apply3PercentDiscount(' . $total . ');">3%</a>
                      <a href="newSimate.php" class="btn btn-success mx-2 px-4" id="apply5PercentDiscount" onclick="event.preventDefault(); apply5PercentDiscount(' . $total . ');">5%</a>
                    </td>
                </tr>';

            $arrayData['detalle'] = $detalleTabla;
            $arrayData['totales'] = $detalleTotales;

            echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
        } else {
            $data = 0;
        }
        mysqli_close($MYSQLI);
    }
    exit;
}

// Generar nuevo presupuesto
// Generar nuevo presupuesto
if (isset($_POST['action']) && $_POST['action'] == 'procesarPresupuesto') {

    session_start();

    $client_id = intval($_POST['clientId'] ?? 0);
    $total = intval($_POST['total']);

    $token = md5($_SESSION['user_id'] ?? '');
    $userCreatedId = intval($_SESSION['user_id'] ?? 0);
    $branchId = intval($_SESSION['branchId'] ?? 0);
    $products = json_decode($_POST['products'], true);
    $tipo = $_POST['tipo'] ?? '';

    $storeId = isset($_POST['storeId']) ? intval($_POST['storeId']) : 0;

    if (!$client_id || !$userCreatedId || !$branchId) {
        die("Datos insuficientes para procesar el presupuesto.");
    }



        $query_procesar = mysqli_query($MYSQLI, "CALL sp_process_budget($userCreatedId, $client_id, $total, $branchId, $storeId, '$token')");

        if (!$query_procesar) {
            die("Error al ejecutar el procedimiento almacenado: " . mysqli_error($MYSQLI));
        }

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


if (isset($_POST['action']) && $_POST['action'] == 'addProductToDetail') {
    session_start();

    if (empty($_POST['product']) || empty($_POST['product_quantity'])) {
        $error = array(
            'status' => 'error',
            'message' => 'Error: No se recibieron los datos necesarios.',
            'details' => array(
                'product' => $_POST['product'] ?? '',
                'product_quantity' => $_POST['product_quantity'] ?? ''
            )
        );
    } else {
        $product = $_POST['product'];
        $userCreatedId = $_SESSION['user_id'];
        $quantity = $_POST['product_quantity'];
        $precioFinal = $_POST['precioFinal'];
        $status = $_POST['estatus'];
        $total = $_POST['total'];
        $cost = $_POST['cost'] ?? 0;
        $tokenUser = md5($_SESSION['user_id']);
        if (preg_match('/^\d+$/', $product)) {
            $codproducto = intval($product);
            $query_detalle_temp = mysqli_query($MYSQLI, "CALL sp_add_temporal_code_detail($codproducto, $quantity, '$tokenUser',$cost,$userCreatedId,$precioFinal,'$status')");
        } else {
            $product = mysqli_real_escape_string($MYSQLI, $product);
            $stmt = $MYSQLI->prepare("CALL sp_add_temporal_name_detail(?, ?, ?,?,?,?,?)");
            $stmt->bind_param("sisssss", $product, $quantity, $tokenUser, $cost, $userCreatedId,$precioFinal);
            $stmt->execute();
            $query_detalle_temp = $stmt->get_result();
            $stmt->close();
        }

        $impuesto = $total * .16;
        $result = mysqli_num_rows($query_detalle_temp);
        $detalleTabla = '';
        $arrayData = array();
        $subtotal = 0;
        $totalVenta = 0;
        $totalCosto = 0;


        while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
            $precioTotal = round($data['PrecioFinal'], 2);
            $cantidad = $data['quantity'];
            $subtotal += $precioTotal; 
            $sub_total = round($subtotal, 2);
            $totalProduct = round($precioTotal *  $cantidad, 2);
            $totalCosto += round($data['costo'] * $cantidad, 2);
            $totalVenta += $totalProduct;
            $estados = [
                "comprado" => ["nombre" => "Comprado", "color" => "background:rgb(86, 171, 235);"], // Azul suave
                "en_taller" => ["nombre" => "En taller", "color" => "background:rgb(120, 144, 156);"], // Gris azulado
                "en_entrega" => ["nombre" => "En entrega", "color" => "background:rgb(129, 212, 250);"], // Celeste
                "entregado" => ["nombre" => "Entregado", "color" => "background:rgb(100, 221, 168);"] // Verde menta
            ];
            
            $estado = $data['estado'];
            $nombreEstado = isset($estados[$estado]) ? $estados[$estado]['nombre'] : "Desconocido";
            $colorFondo = isset($estados[$estado]) ? $estados[$estado]['color'] : "";
            $detalleTabla .=
                '<tr>
                    <td colspan="2" class="product_id">' . $data['productId'] . '</td>
                    <td colspan="2">' . $data['name'] . '</td>
                    <td class="product_quantity">' . $data['quantity'] . '</td>
                    <td class="price">$ ' . number_format($data['PrecioFinal'], 2) . '</td>
                    <td class="cost">$ ' . number_format($data['costo'] ?? 0, 2) . '</td>
                    <td class="total">$ ' . number_format($totalProduct, 2) . '</td>
                    <td class="status" style="' . $colorFondo . '">' . $nombreEstado . '</td>
                    <td class="text-center">
                        <a href="#" class="btn btn-danger" onclick="event.preventDefault(); deleteProductDetail(' . $data['id'] . ');"><i class="fa fa-trash-o"></i> Eliminar Producto</a>
                    </td>
                </tr>';
        }
        if (!$query_detalle_temp) {
            echo json_encode(['error' => mysqli_error($MYSQLI)]);
            exit;
        }
    
        $detalleTotales = '
        <tr>
              <td colspan="5"><b>Total del costo extra </b></td>
            <td class="cost">$ ' . number_format($totalCosto, 2) . '</td>
        </tr>
        <tr>
              <td colspan="5"><b>Total de Venta</b></td>
             <td class="total" >$ ' . number_format($totalVenta  , 2) . '</td>
        </tr>
        ';


        $arrayData['detalle'] = $detalleTabla;
        $arrayData['totales'] = $detalleTotales;

        echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);

        mysqli_close($MYSQLI);
    }
    exit;
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

if (isset($_POST['action']) and $_POST['action'] == 'updateStatus') {
    session_start();
    $id = $_POST['estimateId'];
    $status = $_POST['status'];

    $query_update = mysqli_query($MYSQLI, "UPDATE estimates SET status = '$status' WHERE id = $id");

    if ($query_update) {
        echo json_encode(["status" => "success", "message" => "Estatus actualizado."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al actualizar el estatus."]);
    }

    mysqli_close($MYSQLI);
    exit;
}

function getStimates(): array
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    $query = "
    SELECT 
            estimates.*, 
            users.name AS user, 
            estimates.userCreatedId AS user_id,
            budget_details.PrecioFinal,  -- PrecioFinal de budget_details
            budget_details.costo,  
            budget_details.sellingPrice  -- Costo de budget_details
        FROM 
            estimates 
        JOIN 
            users 
        ON 
            estimates.userCreatedId = users.id 
        JOIN 
            budget_details 
        ON 
            estimates.id = budget_details.id 
        ORDER BY 
            estimates.date DESC";
    $result = mysqli_query($MYSQLI, $query);

    if (!$result) {
        die("Query execution error: " . mysqli_error($MYSQLI));
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

    mysqli_free_result($result);

    return $rows;
}
