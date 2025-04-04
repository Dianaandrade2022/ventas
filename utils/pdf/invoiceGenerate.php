<?php

session_start();

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/fpdf/fpdf.php";

$client_id = $_REQUEST['clientId'];
$numero_factura = $_REQUEST['invoiceId'];
$total = $_REQUEST['totalWithDiscount'];

// Consultas utilizando prepared statements
$data_store = mysqli_prepare($MYSQLI, "SELECT * FROM stores");
mysqli_stmt_execute($data_store);
$resultado_configuracion = mysqli_stmt_get_result($data_store);
$configuracion = mysqli_fetch_assoc($resultado_configuracion);

$stmt_ventas = mysqli_prepare($MYSQLI, "SELECT * FROM invoices WHERE id = ?");
mysqli_stmt_bind_param($stmt_ventas, "i", $numero_factura);
mysqli_stmt_execute($stmt_ventas);
$result_venta = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_ventas));

$stmt_clientes = mysqli_prepare($MYSQLI, "SELECT * FROM clients WHERE ID = ?");
mysqli_stmt_bind_param($stmt_clientes, "i", $client_id);
mysqli_stmt_execute($stmt_clientes);
$result_cliente = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_clientes));

$query = "SELECT d.invoiceNumber, d.productId, d.quantity, p.id, p.name, p.price, d.costo,d.PrecioFinal, d.extracost, d.sellingPrice, d.tipodecosto 
          FROM invoice_details d 
          INNER JOIN products p 
          ON d.invoiceNumber = ? 
          WHERE d.productId = p.id";

$stmt_productos = mysqli_prepare($MYSQLI, $query);
mysqli_stmt_bind_param($stmt_productos, "i", $numero_factura);
mysqli_stmt_execute($stmt_productos);
$productos_result = mysqli_stmt_get_result($stmt_productos);

$pdf = new FPDF('P', 'mm', array(120, 200));
$pdf->AddPage();
$pdf->SetMargins(8, 0, 0);
$pdf->Image("./img/logo_promobranding.png", 12, 12, 20, 20, 'PNG');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(92, 5, "Promobranding - SP ", 0, 2, 'R');
$pdf->SetFont('Arial', '', 4);
$pdf->Cell(90, 5, "Calle Periferico Sur 6255, Lopez Cotilla, 45610 San Pedro Tlaquepaque, Jalisco ", 0, 2, 'R');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(100, 5, "", 0, 2, 'R');
$pdf->Image("./img/whatsapp.png", 76, 27, 6, 6, 'PNG');
$pdf->Cell(90, 5, "331-188-4390", 0, 2, 'R');
$pdf->Ln(12);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(20, 5, "FACTURA Nro: ", 0, 0, 'L');
$pdf->Cell(20, 5, $numero_factura, 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(20, 5, "Fecha y Hora: ", 0, 0, 'L');
$pdf->Cell(0, 5, $result_venta['date'], 0, 1, 'L');
$pdf->Cell(20, 5, "-----------------------------------------------------------------------------------------------------------------------", 0, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(100, 5, "Datos del cliente", 0, 1, 'C');
$pdf->Cell(18, 5, mb_convert_encoding("Razón Social: ", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(5, 5, $result_cliente['name'], 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(13, 5, mb_convert_encoding("Dirección: ", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(25, 5, $result_cliente['address'], 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(6, 5, "DNI: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(25, 5, $result_cliente['dni'], 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(12, 5, mb_convert_encoding("Teléfono: ", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(25, 5, $result_cliente['phoneNumber'], 0, 1, 'L');
$pdf->Cell(20, 5, "-----------------------------------------------------------------------------------------------------------------------", 0, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(100, 5, "Detalle de Productos", 0, 1, 'C');
$pdf->Ln();
$productos_array = [];
$mostrarCostoExtra = false;
$mostrarTipoCosto = false;

while ($row = mysqli_fetch_assoc($productos_result)) {
    if (!empty($row['extracost']) && $row['extracost'] != '0') {
        $mostrarCostoExtra = true;
    }
    if (!empty($row['tipodecosto'])) {
        $mostrarTipoCosto = true;
    }
    $productos_array[] = $row; 
}

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(45, 5, 'Nombre', 0, 0, 'L');
$pdf->Cell(12, 5, 'Cant', 0, 0, 'L');
$pdf->Cell(14, 5, 'Precio', 0, 0, 'L');

if ($mostrarCostoExtra) {
    $pdf->Cell(14, 5, 'Costo', 0, 0, 'L');
}
if ($mostrarTipoCosto) {
    $pdf->Cell(14, 5, 'Tipo', 0, 0, 'L');
}

$pdf->Cell(15, 5, 'Total', 0, 1, 'L');
$pdf->SetFont('Arial', '', 5.9);

// Ahora recorremos los productos guardados en el array
foreach ($productos_array as $row) {
    $pdf->Cell(47, 5, strtoupper($row['name']), 0, 0, 'L');
    $pdf->Cell(10, 5, $row['quantity'], 0, 0, 'L');
    $pdf->Cell(14, 5, "$ " . number_format($row['PrecioFinal'], 2, '.', ','), 0, 0, 'L');

    if ($mostrarCostoExtra) {
        $pdf->Cell(14, 5, "$ " . number_format($row['extracost'], 2, '.', ','), 0, 0, 'L');
    }

    if ($mostrarTipoCosto) {
        $pdf->Cell(14, 5, strtoupper($row['tipodecosto']), 0, 0, 'L');
    }

    $importe = number_format(($row['quantity'] * $row['PrecioFinal']), 2, '.', ',');
    $pdf->Cell(15, 5, "$ " . $importe, 0, 1, 'L');
}

$pdf->Cell(0, 5, "--------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);


//mostrar conteo 
// Mostrar total
$total_formateado = number_format($result_venta['total'], 2, '.', ',');
$pdf->Cell(96, 5, 'Total: $ ' . $total_formateado, 0, 1, 'R');


;


$pdf->Ln();
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(40, 5, "Atendido por: " . $_SESSION['name'], 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(100, 5, "Gracias por su compra", 0, 1, 'C');

$pdf->Output("Factura$numero_factura.pdf", "I");

// Cerrar conexiones y liberar recursos
mysqli_stmt_close($data_store);
mysqli_stmt_close($stmt_ventas);
mysqli_stmt_close($stmt_clientes);
mysqli_stmt_close($stmt_productos);
