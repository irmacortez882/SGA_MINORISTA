<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Carga limpia de modelos
$modelsPath = [__DIR__ . "/../models/Venta.php", "models/Venta.php", __DIR__ . "/../models/Producto.php", "models/Producto.php"];
foreach ($modelsPath as $file) {
    if (file_exists($file)) {
        require_once $file;
    }
}

class VentaController {

    public static function listarVentasController() {
        return Venta::listarVentasModel();
    }

    public static function ctrCrearVenta() {
        if (!isset($_POST["totalVenta"])) {
            return;
        }

        // Indicar al navegador que la respuesta será un JSON estricto
        header('Content-Type: application/json; charset=utf-8');

        $listaProductos = isset($_POST["listaProductos"]) ? json_decode($_POST["listaProductos"], true) : [];

        $datos = array(
            "codigo_factura" => $_POST["codigo_factura"] ?? "",
            "total"          => $_POST["totalVenta"] ?? 0,
            "productos"      => $listaProductos
        );

        $respuesta = Venta::guardarVentaModel($datos);

        if ($respuesta && is_array($respuesta) && (($respuesta["status"] === true || $respuesta["status"] === "ok"))) {
            echo json_encode([
                "status"         => "ok",
                "id_venta"       => $respuesta["id_venta"],
                "codigo_factura" => $respuesta["codigo_factura"]
            ]);
        } else {
            // Capturamos el mensaje técnico devuelto por el modelo o ponemos uno por defecto
            $mensajeError = (is_array($respuesta) && isset($respuesta["mensaje"])) 
                ? $respuesta["mensaje"] 
                : "Error al registrar la venta. Verifique stock insuficiente o problemas en la transacción.";

            echo json_encode([
                "status"  => "error",
                "mensaje" => $mensajeError
            ]);
        }
        
        exit();
    }
}