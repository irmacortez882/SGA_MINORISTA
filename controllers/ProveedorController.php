<?php

class ProveedorController {

    // ==========================================
    // CREAR PROVEEDOR
    // ==========================================
    static public function ctrCrearProveedor() {
        if (isset($_POST["nuevoNombreProveedor"])) {
            
            $datos = array(
                "nombre_proveedor" => $_POST["nuevoNombreProveedor"],
                "telefono" => $_POST["nuevoTelefono"]
            );

            $respuesta = Proveedor::guardarProveedorModel($datos);

            if ($respuesta == "ok") {
                echo '<script>
                    alert("El proveedor ha sido guardado correctamente");
                    window.location = "proveedores";
                </script>';
            } else {
                echo '<script>
                    alert("¡Error al guardar el proveedor!");
                </script>';
            }
        }
    }

    // ==========================================
    // MOSTRAR PROVEEDORES
    // ==========================================
    static public function ctrMostrarProveedores($item, $valor) {
        $respuesta = Proveedor::listarProveedoresModel();
        return $respuesta;
    }

    // ==========================================
    // EDITAR PROVEEDOR
    // ==========================================
    static public function ctrEditarProveedor() {
        if (isset($_POST["editarNombreProveedor"])) {
            
            $datos = array(
                "id_proveedor" => $_POST["idProveedor"],
                "nombre_proveedor" => $_POST["editarNombreProveedor"],
                "telefono" => $_POST["editarTelefono"]
            );

            $respuesta = Proveedor::actualizarProveedorModel($datos);

            if ($respuesta == "ok") {
                echo '<script>
                    alert("El proveedor ha sido actualizado correctamente");
                    window.location = "proveedores";
                </script>';
            } else {
                echo '<script>
                    alert("¡Error al actualizar el proveedor!");
                </script>';
            }
        }
    }

    // ==========================================
    // BORRAR PROVEEDOR
    // ==========================================
    static public function ctrBorrarProveedor() {
        if (isset($_GET["idProveedor"])) {
            
            $datos = $_GET["idProveedor"];
            $respuesta = Proveedor::eliminarProveedorModel($datos);

            if ($respuesta == "ok") {
                echo '<script>
                    alert("El proveedor ha sido borrado correctamente");
                    window.location = "proveedores";
                </script>';
            }
        }
    }
}
