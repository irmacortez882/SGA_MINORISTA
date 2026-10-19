<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (file_exists(__DIR__ . "/../models/Venta.php")) {
    require_once __DIR__ . "/../models/Venta.php";
} elseif (file_exists("models/Venta.php")) {
    require_once "models/Venta.php";
}

if (file_exists(__DIR__ . "/../models/Producto.php")) {
    require_once __DIR__ . "/../models/Producto.php";
} elseif (file_exists("models/Producto.php")) {
    require_once "models/Producto.php";
}

class VentaController {

    public static function listarVentasController() {
        return Venta::listarVentasModel();
    }

    public static function ctrCrearVenta() {
        // Ahora permitimos que se dispare el guardado al recibir la señal de creación del formulario (ej. totalVenta)
        if (isset($_POST["totalVenta"])) {
            
            // Decodificamos el string JSON que viene del input del carrito en JS
            $listaProductos = isset($_POST["listaProductos"]) ? json_decode($_POST["listaProductos"], true) : [];

            // Nota: Ya no exigimos que $_POST["codigo_factura"] venga del formulario, 
            // porque el modelo se encarga de calcular automáticamente el correlativo (ej. 0001-00000001).
            $datos = array(
                "codigo_factura" => $_POST["codigo_factura"] ?? "", // Se mantiene opcional por compatibilidad
                "total"          => $_POST["totalVenta"] ?? 0,
                "productos"      => $listaProductos
            );

            $respuesta = Venta::guardarVentaModel($datos);

            // $respuesta ahora devuelve un array asociativo con status, id_venta y codigo_factura si es exitoso
            if ($respuesta && is_array($respuesta) && $respuesta["status"] === true) {
                $idVentaGenerado = $respuesta["id_venta"];
                $codigoGenerado = $respuesta["codigo_factura"];

                echo '<script>
                    alert("Venta registrada correctamente con factura ' . $codigoGenerado . ' y stock actualizado.");
                    window.open("index.php?action=ver-ventas&id=' . $idVentaGenerado . '", "_blank");
                    window.location = "index.php?action=crear-venta";
                </script>';
            } else {
                echo '<script>
                    alert("Error al registrar la venta. Verifique stock insuficiente o problemas en la transacción.");
                </script>';
            }
        }
    }
}