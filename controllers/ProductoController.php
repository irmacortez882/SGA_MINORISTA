<?php

class ProductoController {

    // ==========================================
    // CREAR PRODUCTO
    // ==========================================
    static public function ctrCrearProducto() {
        if (isset($_POST["nuevoNombreProducto"])) {
            
            $datos = array(
                "nombre_producto" => $_POST["nuevoNombreProducto"],
                "precio_venta" => $_POST["nuevoPrecioVenta"],
                "stock_actual" => $_POST["nuevoStockActual"],
                "stock_minimo" => $_POST["nuevoStockMinimo"],
                "id_categoria" => $_POST["nuevaCategoria"],
                "id_proveedor" => $_POST["nuevoProveedor"]
            );

            $respuesta = Producto::guardarProductoModel($datos);

            if ($respuesta == "ok") {
                echo '<script>
                    alert("El producto ha sido guardado correctamente");
                    window.location = "index.php?action=productos";
                </script>';
            } else {
                echo '<script>
                    alert("¡Error al guardar el producto!");
                </script>';
            }
        }
    }

    // ==========================================
    // MOSTRAR PRODUCTOS
    // ==========================================
    static public function ctrMostrarProductos($item, $valor) {
        $respuesta = Producto::listarProductosModel();
        return $respuesta;
    }

    // ==========================================
    // EDITAR PRODUCTO
    // ==========================================
    static public function ctrEditarProducto() {
        if (isset($_POST["editarNombreProducto"])) {
            
            $datos = array(
                "id_producto" => $_POST["idProducto"],
                "nombre_producto" => $_POST["editarNombreProducto"],
                "precio_venta" => $_POST["editarPrecioVenta"],
                "stock_actual" => $_POST["editarStockActual"],
                "stock_minimo" => $_POST["editarStockMinimo"],
                "id_categoria" => $_POST["editarCategoria"],
                "id_proveedor" => $_POST["editarProveedor"]
            );

            $respuesta = Producto::actualizarProductoModel($datos);

            if ($respuesta == "ok") {
                echo '<script>
                    alert("El producto ha sido actualizado correctamente");
                    window.location = "index.php?action=productos";
                </script>';
            } else {
                echo '<script>
                    alert("¡Error al actualizar el producto!");
                </script>';
            }
        }
    }

    // ==========================================
    // INGRESAR STOCK (REPOSICIÓN)
    // ==========================================
    static public function ctrIngresarStock() {
        if (isset($_POST["idProductoStock"]) && isset($_POST["cantidadIngresar"])) {

            $idProducto = intval($_POST["idProductoStock"]);
            $cantidad   = intval($_POST["cantidadIngresar"]);

            if ($idProducto <= 0 || $cantidad <= 0) {
                echo '<script>
                    alert("Seleccioná un producto y una cantidad mayor a cero.");
                </script>';
                return;
            }

            $respuesta = Producto::ingresarStockModel($idProducto, $cantidad);

            if ($respuesta == "ok") {
                echo '<script>
                    alert("¡Stock ingresado correctamente!");
                    window.location = "index.php?action=productos";
                </script>';
            } else {
                echo '<script>
                    alert("¡Error al ingresar el stock!");
                </script>';
            }
        }
    }

    // ==========================================
    // BORRAR PRODUCTO
    // ==========================================
    static public function ctrBorrarProducto() {
        if (isset($_GET["idProducto"])) {
            
            $datos = $_GET["idProducto"];
            $respuesta = Producto::eliminarProductoModel($datos);

            if ($respuesta == "ok") {
                echo '<script>
                    alert("El producto ha sido borrado correctamente");
                    window.location = "index.php?action=productos";
                </script>';
            }
        }
    }
}