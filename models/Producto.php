<?php
require_once __DIR__ . "/../config/conexion.php";

class Producto {

    // ==========================================
    // LISTAR PRODUCTOS (CON JOIN A CATEGORÍAS Y PROVEEDORES)
    // ==========================================
    public static function listarProductosModel() {
        $stmt = Conexion::conectar()->prepare("
            SELECT 
                p.id_producto, 
                p.nombre_producto, 
                p.precio_venta, 
                p.stock_actual, 
                p.stock_minimo, 
                c.nombre_categoria, 
                pr.nombre_proveedor 
            FROM productos p
            INNER JOIN categorias c ON p.id_categoria = c.id_categoría
            INNER JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor
            ORDER BY p.nombre_producto ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ==========================================
    // GUARDAR PRODUCTO
    // ==========================================
    public static function guardarProductoModel($datos) {
        $stmt = Conexion::conectar()->prepare("INSERT INTO productos (nombre_producto, precio_venta, stock_actual, stock_minimo, id_categoria, id_proveedor) VALUES (:nombre_producto, :precio_venta, :stock_actual, :stock_minimo, :id_categoria, :id_proveedor)");
        
        $stmt->bindParam(":nombre_producto", $datos["nombre_producto"], PDO::PARAM_STR);
        $stmt->bindParam(":precio_venta", $datos["precio_venta"], PDO::PARAM_STR);
        $stmt->bindParam(":stock_actual", $datos["stock_actual"], PDO::PARAM_INT);
        $stmt->bindParam(":stock_minimo", $datos["stock_minimo"], PDO::PARAM_INT);
        $stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_INT);
        $stmt->bindParam(":id_proveedor", $datos["id_proveedor"], PDO::PARAM_INT);

        if ($stmt->execute()) { 
            return "ok"; 
        } else { 
            return "error"; 
        }
    }

    // ==========================================
    // ACTUALIZAR PRODUCTO
    // ==========================================
    public static function actualizarProductoModel($datos) {
        $stmt = Conexion::conectar()->prepare("UPDATE productos SET nombre_producto = :nombre_producto, precio_venta = :precio_venta, stock_actual = :stock_actual, stock_minimo = :stock_minimo, id_categoria = :id_categoria, id_proveedor = :id_proveedor WHERE id_producto = :id_producto");
        
        $stmt->bindParam(":id_producto", $datos["id_producto"], PDO::PARAM_INT);
        $stmt->bindParam(":nombre_producto", $datos["nombre_producto"], PDO::PARAM_STR);
        $stmt->bindParam(":precio_venta", $datos["precio_venta"], PDO::PARAM_STR);
        $stmt->bindParam(":stock_actual", $datos["stock_actual"], PDO::PARAM_INT);
        $stmt->bindParam(":stock_minimo", $datos["stock_minimo"], PDO::PARAM_INT);
        $stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_INT);
        $stmt->bindParam(":id_proveedor", $datos["id_proveedor"], PDO::PARAM_INT);

        if ($stmt->execute()) { 
            return "ok"; 
        } else { 
            return "error"; 
        }
    }

    // ==========================================
    // ELIMINAR PRODUCTO
    // ==========================================
    public static function eliminarProductoModel($id) {
        $stmt = Conexion::conectar()->prepare("DELETE FROM productos WHERE id_producto = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) { 
            return "ok"; 
        } else { 
            return "error"; 
        }
    }
}