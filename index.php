<?php
// Definir la URL absoluta del proyecto para evitar errores 404 en assets y redirecciones
define("URL", "http://localhost/SGA_MINORISTA/");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION["iniciarSesion"] = "ok"; // Forzar inicio de sesión para desarrollo
/*=============================================
1. INCLUSIÓN DE CONTROLADORES Y MODELOS
=============================================*/
require_once "controllers/PlantillaController.php";
require_once "controllers/VentaController.php";
require_once "controllers/ProductoController.php";
require_once "controllers/NotaCreditoController.php";

require_once "models/Venta.php";
require_once "models/Producto.php";
require_once "models/NotaCredito.php";

/*=============================================
2. ENRUTADOR DE PÁGINAS (CONTROLLER DE PLANTILLA)
=============================================*/
class Enrutador {

    public static function cargarPagina() {

        if (isset($_GET["action"])) {

            $ruta = $_GET["action"];

            // Lista blanca exacta según tus archivos en views/modules/
            if ($ruta == "inicio" ||
                $ruta == "categorias" ||
                $ruta == "crear-venta" ||
                $ruta == "ventas" ||
                $ruta == "ver-ventas" ||
                $ruta == "inventario" ||
                $ruta == "proveedores" ||
                $ruta == "informes" ||
                $ruta == "imprimir" ||
                $ruta == "login" ||
                $ruta == "notas-credito" ||
                $ruta == "productos" ||
                $ruta == "crear-nota-credito" ||
                $ruta == "emitir-nota-credito" ||
                $ruta == "salir" )
             {

                include "views/modules/" . $ruta . ".php";

            } else {

                include "views/modules/404.php";

            }

        } else {

            include "views/modules/inicio.php";

        }

    }

}

/*=============================================
3. INICIALIZAR LA PLANTILLA GENERAL
=============================================*/
$plantilla = new PlantillaController();
$plantilla->cargarPlantilla();