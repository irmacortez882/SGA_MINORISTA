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
        if (isset($_POST["codigo_factura"])) {
            
            // Decodificamos el string JSON que viene del input del carrito en JS
            $listaProductos = isset($_POST["listaProductos"]) ? json_decode($_POST["listaProductos"], true) : [];

            $datos = array(
                "codigo_factura" => $_POST["codigo_factura"],
                "total"          => $_POST["totalVenta"] ?? 0,
                "productos"      => $listaProductos
            );

            $respuesta = Venta::guardarVentaModel($datos);

            if ($respuesta) {
                // $respuesta trae el id_venta generado, redirigimos al ticket/factura como antes
                echo '<script>
                    alert("Venta registrada correctamente y stock actualizado.");
                    window.open("index.php?action=ver-ventas&id=' . $respuesta . '", "_blank");
                    window.location = "index.php?action=crear-venta";
                </script>';
            } else {
                echo '<script>
                    alert("Error al registrar la venta. Verifique los datos o el stock.");
                </script>';
            }
        }
    }
}