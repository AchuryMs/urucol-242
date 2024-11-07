<?php
require_once('../tcpdf/tcpdf.php');
require_once('../db.php');

// Consultar los empleados
$sql = "SELECT E.codigo, E.nombre_empleado, D.nombre AS dependencia, C.nombre AS cargo, E.fecha_ingreso, E.sueldo,
               EPS.nombre AS eps, ARL.nombre AS arl
        FROM empleado E
        JOIN dependencia D ON E.id_dependencia = D.id_dependencia
        JOIN cargo C ON E.id_cargo = C.id_cargo
        JOIN empleado_eps EE ON E.codigo = EE.id_empleado
        JOIN eps EPS ON EE.id_eps = EPS.id_eps
        JOIN empleado_arl EA ON E.codigo = EA.id_empleado
        JOIN arl ARL ON EA.id_arl = ARL.id_arl
        ORDER BY E.nombre_empleado";
$result = mysqli_query($conn, $sql);

class PDF extends TCPDF
{
    // Cabecera
    public function Header()
    {
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 10, 'Reporte Individual de Empleados', 0, 1, 'C');
        $this->Ln(5);
    }

    // Pie de página
    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }

    // Título del capítulo
    public function ChapterTitle($title)
    {
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 6, $title, 0, 1, 'L');
        $this->Ln(4);
    }

    // Cuerpo del capítulo
    public function ChapterBody($body)
    {
        $this->SetFont('helvetica', '', 12);
        $this->MultiCell(0, 10, $body);
        $this->Ln();
    }
}

// Crear el objeto PDF
$pdf = new PDF();
$pdf->AddPage();

while ($row = mysqli_fetch_assoc($result)) {
    $pdf->ChapterTitle('Empleado: ' . $row['nombre_empleado']);
    $pdf->ChapterBody(
        "Código: " . $row['codigo'] . "\nCargo: " . $row['cargo'] . "\nDependencia: " . $row['dependencia'] .
        "\nFecha de Ingreso: " . $row['fecha_ingreso'] . "\nSueldo: " . $row['sueldo'] .
        "\nEPS: " . $row['eps'] . "\nARL: " . $row['arl']
    );
}

$pdf->Output();
?>
