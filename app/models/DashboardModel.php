<?php

require_once(__DIR__ . "/../../config/db.php");


function getDataCardOne()
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    $data = [];
    
    // Consulta para obtener el total de facturas del mes actual
    $query_total_facturas = $MYSQLI->prepare("
        SELECT COUNT(*) AS total_facturas 
        FROM invoices 
        WHERE MONTH(date) = MONTH(CURRENT_DATE()) 
        AND YEAR(date) = YEAR(CURRENT_DATE())");

    $query_total_facturas->execute();
    $result_total_facturas = $query_total_facturas->get_result();
    $row_total_facturas = $result_total_facturas->fetch_assoc();
    $total_ventas = $row_total_facturas['total_facturas'];
    $query_total_facturas->close();

    // Consulta para obtener los totales del mes pasado y actual
    $query_ventas = $MYSQLI->prepare("
        SELECT
            SUM(CASE WHEN MONTH(date) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(date) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH) THEN total ELSE 0 END) AS total_mes_pasado,
            SUM(CASE WHEN MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE()) THEN total ELSE 0 END) AS total_mes_actual
        FROM invoices");

    $query_ventas->execute();
    $result_ventas = $query_ventas->get_result();
    $row_ventas = $result_ventas->fetch_assoc();
    $total_mes_pasado = $row_ventas['total_mes_pasado'];
    $total_mes_actual = $row_ventas['total_mes_actual'];
    $query_ventas->close();

    // Calcular el porcentaje de diferencia si el mes pasado tiene ventas mayores que cero
    if ($total_mes_pasado > 0) {
        $diferencia = $total_mes_actual - $total_mes_pasado;
        $porcentaje_diferencia_ventas = ($diferencia / $total_mes_pasado) * 100;
    } else {
        $porcentaje_diferencia_ventas = 0;
    }

    // Guardar los resultados en el array $data
    $data['porcentaje_diferencia_ventas'] = $porcentaje_diferencia_ventas;
    $data['total_ventas'] = $total_ventas;

    return $data;
}

function getDataCardTwo()
{

    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    // Consulta para obtener el total de presupuestos del mes actual
    $query_insert_presupuestos = $MYSQLI->prepare("SELECT COUNT(*) AS total_presupuestos 
                                    FROM estimates 
                                    WHERE MONTH(date) = MONTH(CURRENT_DATE()) 
                                      AND YEAR(date) = YEAR(CURRENT_DATE());");

    $query_insert_presupuestos->execute();
    $result_sql_presupuestos = $query_insert_presupuestos->get_result()->fetch_assoc();
    $total_presupuestos = $result_sql_presupuestos ? $result_sql_presupuestos["total_presupuestos"] : 0;
    $query_insert_presupuestos->close();

    // Consulta para obtener los totales del mes pasado y del mes actual
    $query_insert_totales = $MYSQLI->prepare("SELECT SUM(CASE WHEN MONTH(date) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) 
           AND YEAR(date) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH) 
           THEN total ELSE 0 END) AS total_mes_pasado,
           SUM(CASE WHEN MONTH(date) = MONTH(CURRENT_DATE()) 
           AND YEAR(date) = YEAR(CURRENT_DATE()) 
           THEN total ELSE 0 END) AS total_mes_actual
           FROM estimates;");

    $query_insert_totales->execute();
    $data_totales = $query_insert_totales->get_result()->fetch_assoc();
    $total_mes_pasado = $data_totales['total_mes_pasado'];
    $total_mes_actual = $data_totales['total_mes_actual'];

    // Cálculo del porcentaje de crecimiento o decrecimiento
    if ($total_mes_pasado > 0) {
        $diferencia = $total_mes_actual - $total_mes_pasado;
        $porcentaje_diferencia_presupuestos = ($diferencia / $total_mes_pasado) * 100;
    } else {
        $porcentaje_diferencia_presupuestos = 0;
    }

    // Retornar los datos como un array asociativo
    return array(
        'total_presupuestos' => $total_presupuestos,
        'porcentaje_diferencia_presupuestos' => $porcentaje_diferencia_presupuestos
    );
}

// Aquí defines la función getTotalForMonth fuera de la función principal
function getTotalForMonth($year, $month)
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }
    $queryMonth = "
    SELECT
        SUM(CASE
            WHEN total IS NOT NULL AND total > 0 THEN total
            ELSE total
        END) AS total
    FROM invoices
    WHERE MONTH(date) = ? AND YEAR(date) = ?";

    $stmt = $MYSQLI->prepare($queryMonth);
    $stmt->bind_param("ii", $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        return $data['total'] ?? 0;  // Devolver 0 si no hay resultados
    } else {
        return false;
    }
}

// Ahora puedes definir tu función principal sin redeclarar getTotalForMonth()
function getDataCardAndGraphic()
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    // Consulta para obtener el total recaudado del mes actual
    $query_actual = "
        SELECT
    SUM(CASE WHEN MONTH(date) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(date) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH) THEN total ELSE 0 END) AS total_mes_pasado,
    SUM(CASE WHEN MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE()) THEN total ELSE 0 END) AS total_mes_actual
FROM invoices;";

    $stmt_actual = $MYSQLI->prepare($query_actual);
    $stmt_actual->execute();
    $result_actual = $stmt_actual->get_result();

    // Obtener el total recaudado del mes actual
    if ($result_actual && $result_actual->num_rows > 0) {
        $data_actual = $result_actual->fetch_assoc();
        $total_recaudado_mes_actual = is_numeric($data_actual['total_mes_actual']) ? number_format($data_actual['total_mes_actual'], 2) : "0.00";
    } else {
        $total_recaudado_mes_actual = 0;
    }

    $stmt_actual->close();

    // Obtener el mes y año actual y anterior
    $current_month = date('m');
    $current_year = date('Y');
    $previous_month = date('m', strtotime('-1 month'));
    $previous_year = date('Y', strtotime('-1 month'));

    // Obtener el total del mes anterior
    $total_mes_anterior = getTotalForMonth($previous_year, $previous_month);
    if ($total_mes_anterior === false) {
        echo "Error en la consulta del mes anterior: " . $MYSQLI->error;
        exit;
    }

    // Obtener el total del mes actual
    $total_mes_actual = getTotalForMonth($current_year, $current_month);
    if ($total_mes_actual === false) {
        echo "Error en la consulta del mes actual: " . $MYSQLI->error;
        exit;
    }

    // Calcular la diferencia y el porcentaje
    if ($total_mes_anterior > 0) {
        $diferencia = $total_mes_actual - $total_mes_anterior;
        $porcentaje_diferencia = ($diferencia / $total_mes_anterior) * 100;
        $porcentaje_diferencia_recaudado = round($porcentaje_diferencia, 2) . "%";
    } else {
        $porcentaje_diferencia_recaudado = 0;
    }


    return array(
        'total_recaudado_mes_actual' => $total_recaudado_mes_actual,
        'porcentaje_diferencia_recaudado' => $porcentaje_diferencia_recaudado
    );
}

// OBTENEMOS LA SUMA DEL TOTAL RECAUDADO DEL DIA
function getTotalForToday()
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    $queryFT = "
        SELECT
            SUM(CASE
                WHEN total IS NOT NULL AND total > 0 THEN total
                ELSE total
            END) AS total
        FROM invoices
        WHERE DATE(date) = CURRENT_DATE()";

    $stmt = $MYSQLI->prepare($queryFT);
    
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $MYSQLI->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $data = $result->fetch_assoc();
        return $data['total'] ?? 0;
    } else {
        die("Error en la consulta del día actual: " . $MYSQLI->error);
    }
}


$total_recaudado_hoy = getTotalForToday();
$total_recaudado_hoy_formatted = $total_recaudado_hoy;
$fecha_actual = date("d.m.Y");

function getDataGraphic()
{
    global $MYSQLI; // Usar la variable global correctamente

    if (!$MYSQLI) {
        die("Error: No se estableció conexión con la base de datos.");
    }

    // Array para almacenar los valores por mes
    $valores_por_mes = array(
        'January' => 0,
        'February' => 0,
        'March' => 0,
        'April' => 0,
        'May' => 0,
        'June' => 0,
        'July' => 0,
        'August' => 0,
        'September' => 0,
        'October' => 0,
        'November' => 0,
        'December' => 0,
    );

    // Consulta SQL con parámetros para storeId y branchId
    $sql = "SELECT id, date, userCreatedId, clientId, total
    FROM invoices";

    // Preparar la consulta
    $stmt = $MYSQLI->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Recorre cada fila de resultados
        while ($row = $result->fetch_assoc()) {
            // Obtener el mes de la date de la factura
            $mes = date('F', strtotime($row['date']));

            // Verificar si totalWithDiscount es mayor que cero
            if ($row["total"] > 0) {
                // Si es mayor, suma totalWithDiscount al mes correspondiente
                $valores_por_mes[$mes] += $row["total"];
            }
        }

        // Liberar el resultado
        $stmt->free_result();
        $stmt->close();

        // Formatear los valores del array con dos decimales y convertirlos a números
        foreach ($valores_por_mes as $mes => $valor) {
            $valores_por_mes[$mes] = floatval(str_replace(',', '', $valor));
        }
    }

    // Construir array de datos para el gráfico
    $data = array_values($valores_por_mes);

    // Imprimir los datos para el gráfico
    $valores_grafico = json_encode($data);

    return $valores_grafico;
}


