<?php
require_once('../tcpdf/tcpdf.php'); // Asegúrate de que TCPDF esté correctamente configurado
require_once('../db.php');

// Consultar las novedades
$sql = "SELECT N.codigo, E.nombre_empleado, 
               N.novedad_incapacidad, N.novedad_vacaciones, N.dias_trabajados, 
               N.dias_incapacidad, N.dias_vacaciones, 
               N.fecha_inicio_vacaciones, N.fecha_fin_vacaciones, 
               N.fecha_inicio_incapacidad, N.fecha_fin_incapacidad, 
               N.bonificacion, N.transporte
        FROM novedades N
        JOIN empleado E ON N.codigo = E.codigo
        ORDER BY E.nombre_empleado";
$result = mysqli_query($conn, $sql);

class PDF extends TCPDF
{
    // Cabecera
    public function Header()
    {
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 10, 'Reporte de Novedades de Empleados', 0, 1, 'C');
        $this->Ln(5);
    }

    // Pie de página
    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página '.$this->PageNo(), 0, 0, 'C');
    }

    // Función para el título del capítulo
    public function ChapterTitle($title)
    {
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 6, $title, 0, 1, 'L');
        $this->Ln(4);
    }

    // Función para el contenido del capítulo
    public function ChapterBody($body)
    {
        $this->SetFont('helvetica', '', 12);
        $this->MultiCell(0, 10, $body);
        $this->Ln();
    }
}

// Crear el objeto PDF
$pdf = new PDF();

// Establecer la codificación UTF-8
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Recorrer los resultados de la base de datos y agregar los detalles al PDF
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->ChapterTitle('Empleado: '.$row['nombre_empleado']);
    $pdf->ChapterBody(
        "Código: ".$row['codigo']."\n".
        "Novedad Incapacidad: ".($row['novedad_incapacidad'] ? 'Sí' : 'No')."\n".
        "Novedad Vacaciones: ".($row['novedad_vacaciones'] ? 'Sí' : 'No')."\n".
        "Días Trabajados en el Mes: ".$row['dias_trabajados']."\n".
        "Días de Incapacidad: ".$row['dias_incapacidad']."\n".
        "Días de Vacaciones: ".$row['dias_vacaciones']."\n".
        "Fecha de Inicio de Vacaciones: ".($row['fecha_inicio_vacaciones'] ?: 'N/A')."\n".
        "Fecha de Fin de Vacaciones: ".($row['fecha_fin_vacaciones'] ?: 'N/A')."\n".
        "Fecha de Inicio de Incapacidad: ".($row['fecha_inicio_incapacidad'] ?: 'N/A')."\n".
        "Fecha de Fin de Incapacidad: ".($row['fecha_fin_incapacidad'] ?: 'N/A')."\n".
        "Bonificación: $".number_format($row['bonificacion'], 3)."\n".
        "Transporte: $".number_format($row['transporte'], 3)
    );
}

// Generar el archivo PDF
$pdf->Output();
?>
