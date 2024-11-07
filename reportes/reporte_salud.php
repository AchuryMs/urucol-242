<?php
// Incluir la librería TCPDF
require_once('../tcpdf/tcpdf.php');

// Conexión a la base de datos
include('../db.php');

// Consultar la información de salud y pensión
$sql = "SELECT E.nombre_empleado, EPS.nombre AS eps, ARL.nombre AS arl, P.nombre AS pension
        FROM empleado E
        JOIN empleado_eps EE ON E.codigo= EE.id_empleado
        JOIN eps EPS ON EE.id_eps = EPS.id_eps
        JOIN empleado_arl EA ON E.codigo= EA.id_empleado
        JOIN arl ARL ON EA.id_arl = ARL.id_arl
        JOIN empleado_pension EP ON E.codigo= EP.id_empleado
        JOIN pension P ON EP.id_pension = P.id_pension
        ORDER BY E.nombre_empleado";
$result = mysqli_query($conn, $sql);

// Crear el objeto PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 12);

// Título del reporte
$pdf->Cell(0, 10, 'Reporte de Salud y Pensión de Empleados', 0, 1, 'C');
$pdf->Ln(5);

// Configurar el cuerpo del reporte
$pdf->SetFont('helvetica', '', 12);

while ($row = mysqli_fetch_assoc($result)) {
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Empleado: '.$row['nombre_empleado'], 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 12);
    $pdf->MultiCell(0, 10, "EPS: ".$row['eps']."\nARL: ".$row['arl']."\nPensión: ".$row['pension']);
    $pdf->Ln();
}

// Generar el PDF
$pdf->Output();
?>
