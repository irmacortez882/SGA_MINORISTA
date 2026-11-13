<?php
require_once __DIR__ . "/../config/conexion.php";

class Proveedor {

    public static function listarProveedoresModel() {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM proveedores ORDER BY nombre_proveedor ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function guardarProveedorModel($datos) {
        $stmt = Conexion::conectar()->prepare("INSERT INTO proveedores (nombre_proveedor, telefono) VALUES (:nombre_proveedor, :telefono)");
        $stmt->bindParam(":nombre_proveedor", $datos["nombre_proveedor"], PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);

        if ($stmt->execute()) { return "ok"; } else { return "error"; }
    }

    public static function actualizarProveedorModel($datos) {
        $stmt = Conexion::conectar()->prepare("UPDATE proveedores SET nombre_proveedor = :nombre_proveedor, telefono = :telefono WHERE id_proveedor = :id_proveedor");
        $stmt->bindParam(":nombre_proveedor", $datos["nombre_proveedor"], PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
        $stmt->bindParam(":id_proveedor", $datos["id_proveedor"], PDO::PARAM_INT);

        if ($stmt->execute()) { return "ok"; } else { return "error"; }
    }

    public static function eliminarProveedorModel($id) {
        $stmt = Conexion::conectar()->prepare("DELETE FROM proveedores WHERE id_proveedor = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) { return "ok"; } else { return "error"; }
    }
}
