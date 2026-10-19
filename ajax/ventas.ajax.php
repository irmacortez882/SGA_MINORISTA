<?php
require_once "../config/conexion.php";
require_once "../controllers/VentaController.php";
require_once "../models/Venta.php";

class AjaxVentas {

    public $idVenta;

    // 1. Obtener detalles de una venta existente para modales o vistas dinámicas
    public function ajaxObtenerVenta() {
        $idVenta = $this->idVenta;

        // Reutilizamos los métodos del Modelo Venta
        $venta = Venta::obtenerVentaPorId($idVenta);
        $productos = Venta::obtenerDetallePorVenta($idVenta);

        $respuesta = [
            "venta"     => $venta,
            "productos" => $productos
        ];

        echo json_encode($respuesta);
        exit();
    }
}

// Manejo de peticiones AJAX
if (isset($_POST["idVenta"])) {
    $ajax = new AjaxVentas();
    $ajax->idVenta = $_POST["idVenta"];
    $ajax->ajaxObtenerVenta();
}

// Petición para crear la venta desde el formulario con AJAX
if (isset($_POST["totalVenta"])) {
    VentaController::ctrCrearVenta();
}