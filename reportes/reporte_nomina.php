<?php
// Incluir la librería TCPDF
require_once('../tcpdf/tcpdf.php');

// Conexión a la base de datos
include('../db.php');

// Crear la instancia de TCPDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 12);

// Título del reporte
$pdf->Cell(0, 10, 'Reporte de Nomina', 0, 1, 'C');

// Consultar los datos de los empleados
$sql = "SELECT E.codigo, E.nombre_empleado, D.id_dependencia, C.id_cargo, E.fecha_ingreso, E.sueldo
        FROM empleado E
        JOIN dependencia D ON E.id_dependencia = D.id_dependencia
        JOIN cargo C ON E.id_cargo = C.id_cargo";
$result = mysqli_query($conn, $sql);

// Encabezados de la tabla
$pdf->SetFont('helvetica', 'B', 10); // Cambiar a una fuente más pequeña
$pdf->Cell(30, 10, 'Codigo', 1, 0, 'C');
$pdf->Cell(60, 10, 'Nombre', 1, 0, 'C');
$pdf->Cell(40, 10, 'Dependencia', 1, 0, 'C');
$pdf->Cell(40, 10, 'Cargo', 1, 0, 'C');
$pdf->Cell(40, 10, 'Fecha Ingreso', 1, 0, 'C');
$pdf->Cell(40, 10, 'Sueldo', 1, 1, 'C');
$pdf->SetFont('helvetica', '', 10); // Volver a una fuente más normal para los datos

// Mostrar los datos de los empleados
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(30, 10, $row['codigo'], 1, 0, 'C');
    $pdf->Cell(60, 10, $row['nombre_empleado'], 1, 0, 'C');
    $pdf->Cell(40, 10, $row['id_dependencia'], 1, 0, 'C');
    $pdf->Cell(40, 10, $row['id_cargo'], 1, 0, 'C');
    $pdf->Cell(40, 10, $row['fecha_ingreso'], 1, 0, 'C');
    $pdf->Cell(40, 10, '$' . number_format($row['sueldo'], 2), 1, 1, 'C');
}

// Generar el PDF
$pdf->Output();
?>
