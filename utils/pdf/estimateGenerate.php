<?php

session_start();

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/fpdf/fpdf.php";

$client_id = $_REQUEST['clientId'];
$numero_factura = $_REQUEST['estimateId'];
$total_with_discount = $_REQUEST["totalWithDiscount"];

// Consultas preparadas para seguridad
$data_store = mysqli_prepare($MYSQLI, "SELECT * FROM stores");
mysqli_stmt_execute($data_store);
$resultado_configuracion = mysqli_stmt_get_result($data_store);
$configuracion = mysqli_fetch_assoc($resultado_configuracion);

$stmt_presupuestos = mysqli_prepare($MYSQLI, "SELECT * FROM estimates WHERE id = ?");
mysqli_stmt_bind_param($stmt_presupuestos, "i", $numero_factura);
mysqli_stmt_execute($stmt_presupuestos);
$result_venta = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_presupuestos));

$stmt_clientes = mysqli_prepare($MYSQLI, "SELECT * FROM clients WHERE ID = ?");
mysqli_stmt_bind_param($stmt_clientes, "i", $client_id);
mysqli_stmt_execute($stmt_clientes);
$result_cliente = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_clientes));

$stmt_productos = mysqli_prepare($MYSQLI, "SELECT d.invoiceNumber, d.productId, d.quantity, p.id, p.name, p.PrecioFinal FROM budget_details d INNER JOIN products p ON d.invoiceNumber = ? WHERE d.productId = p.id");
mysqli_stmt_bind_param($stmt_productos, "i", $numero_factura);
mysqli_stmt_execute($stmt_productos);
$productos_result = mysqli_stmt_get_result($stmt_productos);

$pdf = new FPDF('P', 'mm', array(120, 200));
$pdf->AddPage();
$pdf->SetMargins(8, 0, 0);
$pdf->Image("./img/logo_promobranding.png", 12, 12, 20, 20, 'PNG');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(92, 5, " ", 0, 2, 'R');
$pdf->SetFont('Arial', '', 4);
$pdf->Cell(90, 5, "Grupo SANDRAGON", 0, 2, 'R');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(100, 5, "", 0, 2, 'R');
$pdf->Image("./img/whatsapp.png", 76, 27, 6, 6, 'PNG');
$pdf->Cell(90, 5, "3311884390", 0, 2, 'R');
$pdf->Ln(12);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(25, 5, "PRESUPUESTO Nro: ", 0, 0, 'L');
$pdf->Cell(6, 5, ($numero_factura), 0, 0, 'L');
$pdf->SetFont('Arial', '', 5);
$pdf->Cell(34, 5, "NO VALIDO COMO FACTURA ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(14, 5, "Fecha y Hora: ", 0, 0, 'L');
$pdf->Cell(10, 5, ($result_venta['date']), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(20, 5, "-----------------------------------------------------------------------------------------------------------------------", 0, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(100, 5, "Datos del cliente", 0, 1, 'C');
$pdf->Cell(18, 5, mb_convert_encoding("Razón Social: ", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(5, 5, ($result_cliente['name']), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(13, 5, mb_convert_encoding("Dirección: ", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(25, 5, ($result_cliente['address']), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(6, 5, ("DNI: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(25, 5, ($result_cliente['dni']), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(12, 5, mb_convert_encoding("Teléfono: ", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(25, 5, ($result_cliente['phoneNumber']), 0, 1, 'L');
$pdf->Cell(20, 5, "-----------------------------------------------------------------------------------------------------------------------", 0, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(100, 5, "Detalle de Productos", 0, 1, 'C');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(65, 5, 'Nombre', 0, 0, 'L');
$pdf->Cell(12, 5, 'Cant', 0, 0, 'L');
$pdf->Cell(14, 5, 'Precio', 0, 0, 'L');
$pdf->Cell(0, 5, 'Total', 0, 1, 'L');
$pdf->SetFont('Arial', '', 5.9);
while ($row = mysqli_fetch_assoc($productos_result)) {
	$pdf->Cell(68, 5, strtoupper(($row['name'])), 0, 0, 'L');
	$pdf->Cell(8, 5, $row['quantity'], 0, 0, 'L');
	$pdf->Cell(14, 5, "$ " .  number_format($row['price'], 2, '.', ','), 0, 0, 'L');
	$importe = number_format($row['quantity'] * $row['price'], 2, '.', ',');
	$pdf->Cell(15, 5, "$ " . $importe, 0, 1, 'L');
}
$pdf->Cell(0, 5, "--------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(96, 5, 'Total: $ ' . number_format($result_venta['total'], 2, '.', ','), 0, 1, 'R');
$pdf->Ln();
if ($_REQUEST['totalWithDiscount'] == "" || $_REQUEST['totalWithDiscount'] == " " || $_REQUEST['totalWithDiscount'] == 0 || $_REQUEST['totalWithDiscount'] == "0") {
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->Cell(40, 5, "Atendido por: " . $_SESSION['name'], 0, 0, 'L');
	$pdf->Ln(10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(100, 5, ("Gracias por su consulta"), 0, 1, 'C');
	$pdf->Output("Presupuesto$numero_factura.pdf", "I");
} else {
	$pdf->Cell(96, 5, 'Total Con Descuento: $ ' . $_REQUEST['totalWithDiscount'], 0, 1, 'R');
	$pdf->Ln();
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->Cell(40, 5, "Atendido por: " . $_SESSION['name'], 0, 0, 'L');
	$pdf->Ln(10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(100, 5, ("Gracias por su consulta"), 0, 1, 'C');
	$pdf->Output("Presupuesto$numero_factura.pdf", "I");
}

// Cerrar conexiones y liberar recursos
mysqli_stmt_close($data_store);
mysqli_stmt_close($stmt_presupuestos);
mysqli_stmt_close($stmt_clientes);
mysqli_stmt_close($stmt_productos);
