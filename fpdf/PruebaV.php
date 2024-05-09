<?php

require('./fpdf.php');
require('../funciones.php');

class PDF extends FPDF
{
   private $idVenta; // Agregamos la propiedad para almacenar el ID de la venta
   private $datosVenta; // Agregamos una propiedad para almacenar los datos de la venta


   public function __construct($idVenta)
   {
       parent::__construct();
       $this->idVenta = $idVenta;
       $this->datosVenta = $this->obtenerDatosVenta(); // Llamamos al método para obtener los datos de la venta
   }

   private function obtenerDatosVenta()
   {
       include_once '../funciones.php';

       try {
           $conexion = conectarBaseDatos();
       } catch (\PDOException $e) {
           echo "Error de conexión: " . $e->getMessage();
       }

       $id_venta = $this->idVenta; // Utilizamos la propiedad de la clase

       $consulta_info = $conexion->prepare("SELECT v.id, v.fecha, v.total, u.usuario as usuario, c.nombre as cliente, pv.cantidad, pv.precio, p.nombre as producto
           FROM ventas v
           LEFT JOIN productos_ventas pv ON v.id = pv.idVenta
           LEFT JOIN productos p ON pv.idProducto = p.id
           LEFT JOIN clientes c ON v.idCliente = c.id
           LEFT JOIN usuarios u ON v.idUsuario = u.id
           WHERE v.id = :id_venta");
       $consulta_info->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);
       $consulta_info->execute();
       return $consulta_info->fetchAll(PDO::FETCH_OBJ);
   }

    function Header()
    {
        include_once '../funciones.php';

        try {
            $conexion = conectarBaseDatos();
        } catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }

        $id_venta = isset($_GET['id_venta']) ? $_GET['id_venta'] : null;

        $consulta_info = $conexion->prepare("SELECT v.id, v.fecha, v.total, u.usuario as usuario, c.nombre as cliente, pv.cantidad, pv.precio, p.nombre as producto
            FROM ventas v
            LEFT JOIN productos_ventas pv ON v.id = pv.idVenta
            LEFT JOIN productos p ON pv.idProducto = p.id
            LEFT JOIN clientes c ON v.idCliente = c.id
            LEFT JOIN usuarios u ON v.idUsuario = u.id
            WHERE v.id = :id_venta");
        $consulta_info->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);
        $consulta_info->execute();
        $datos_venta = $consulta_info->fetchAll(PDO::FETCH_OBJ);

        if (count($datos_venta) > 0) {
            $dato_info = $datos_venta[0];

            $this->Image('logo.png', 185, 5, 20);
            $this->SetFont('Arial', 'B', 19);
            $this->Cell(45);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(110, 15, utf8_decode('El Gorrión'), 1, 1, 'C', 0);
            $this->Ln(3);
            $this->SetTextColor(103);

            $this->Cell(44);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(96, 10, utf8_decode("Fecha : " . $dato_info->fecha), 0, 0, '', 0);
            $this->Ln(5);

            $this->Cell(44);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(85, 10, utf8_decode("Cliente : " . $dato_info->cliente), 0, 0, '', 0);
            $this->Ln(5);

            $this->Cell(44);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(85, 10, utf8_decode("Usuario : " . $dato_info->usuario), 0, 0, '', 0);
            $this->Ln(10);

            $this->SetTextColor(228, 100, 0);
            $this->Cell(50);
            $this->SetFont('Arial', 'B', 15);
            $this->Cell(100, 10, utf8_decode("REPORTE DE VENTAS"), 0, 1, 'C', 0);
            $this->Ln(7);

            $this->SetTextColor(0, 0, 0);

$this->SetFillColor(228, 100, 0);
$this->SetDrawColor(163, 163, 163);
$this->SetFont('Arial', 'B', 11);
$this->Cell(18, 10, utf8_decode('N°'), 1, 0, 'C', 1);
$this->Cell(70, 10, utf8_decode('PRODUCTOS'), 1, 0, 'C', 1);
$this->Cell(30, 10, utf8_decode('CANTIDAD'), 1, 0, 'C', 1);
$this->Cell(30, 10, utf8_decode('PRECIO'), 1, 0, 'C', 1);
$this->Cell(30, 10, utf8_decode('TOTAL'), 1, 1, 'C', 1);

foreach ($datos_venta as $producto) {
   // Establecer color del texto a negro para cada celda
   $this->SetTextColor(0, 0, 0);

   $this->Cell(18, 10, $producto->id, 1, 0, 'C', 0);
   $this->Cell(70, 10, utf8_decode($producto->producto), 1, 0, 'C', 0);
   $this->Cell(30, 10, $producto->cantidad, 1, 0, 'C', 0);
   $this->Cell(30, 10, utf8_decode('S/ ' . $producto->precio), 1, 0, 'C', 0);
   $this->Cell(30, 10, utf8_decode('S/ ' . $producto->cantidad * $producto->precio), 1, 1, 'C', 0);
           }

           $this->SetTextColor(0, 0, 0);
$this->Cell(148, 10, utf8_decode('Total'), 1, 0, 'C', 1);
$this->Cell(30, 10, utf8_decode('S/ ' . $dato_info->total), 1, 1, 'C', 0);
        }
    }

    function Footer()
    {
      $this->SetY(-15);
      $this->SetFont('Arial', 'I', 8);
      $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
      $this->SetY(-15);
      $this->SetFont('Arial', 'I', 8);
      $hoy = date('d/m/Y');
      $this->Cell(355, 10, utf8_decode($hoy), 0, 0, 'C');
    }

}

$idVenta = isset($_GET['id_venta']) ? $_GET['id_venta'] : 0; // Recupera el ID de la venta desde la URL

$pdf = new PDF($idVenta);
$pdf->AddPage();

// No necesitas obtener la venta aquí, ya que los datos se obtienen en la función Header()

ob_end_clean();
$pdf->Output('Prueba.pdf', 'I');


