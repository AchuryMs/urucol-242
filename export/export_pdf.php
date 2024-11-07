<?php
require('../fpdf/fpdf.php');
include '../db.php';

$report = $_GET['report'];
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

switch ($report) {
    case 'individual':
        $pdf->Cell(40, 10, 'Reporte de Información Individual');
        $pdf->Ln();
        $pdf->Cell(40, 10, 'Nombre');
        $pdf->Cell(40, 10, 'Cargo');
        $pdf->Cell(40, 10, 'Dependencia');
        $pdf->Cell(40, 10, 'Salario');
        $pdf->Cell(40, 10, 'EPS');
        $pdf->Cell(40, 10, 'Pensión');
        $pdf->Ln();
        $sql = "SELECT E.nombre_empleado, C.nombre AS cargo, D.nombre AS dependencia, 
                       E.sueldo, E.eps, E.institucion_pension
                FROM Empleado E
                JOIN Cargo C ON E.id_cargo = C.id_cargo
                JOIN Dependencia D ON E.id_dependencia = D.id_dependencia";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $pdf->Cell(40, 10, $row['nombre_empleado']);
            $pdf->Cell(40, 10, $row['cargo']);
            $pdf->Cell(40, 10, $row['dependencia']);
            $pdf->Cell(40, 10, $row['sueldo']);
            $pdf->Cell(40, 10, $row['eps']);
            $pdf->Cell(40, 10, $row['institucion_pension']);
            $pdf->Ln();
        }
        break;
    // Agrega más casos para los otros reportes
}

$pdf->Output();
?>
