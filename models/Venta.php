<?php

if (file_exists(__DIR__ . "/../config/conexion.php")) {
    require_once __DIR__ . "/../config/conexion.php";
} elseif (file_exists(__DIR__ . "/../config/Conexion.php")) {
    require_once __DIR__ . "/../config/Conexion.php";
} else {
    die("Error: No se pudo encontrar el archivo de conexión.");
}

class Venta {

    public static function guardarVentaModel($datos) {
        try {
            $db = Conexion::conectar();
            $db->beginTransaction();

            $idUsuario = $_SESSION['id_usuario'] ?? $_SESSION['id'] ?? 1;

            // 1. Insertar el encabezado de la venta
            $sqlVenta = "INSERT INTO ventas (numero_comprobante, tipo_comprobante, fecha_venta, total, id_usuario, estado) 
                         VALUES (:num, 'FAC', NOW(), :total, :id_usuario, '1')";
            $stmtVenta = $db->prepare($sqlVenta);
            $stmtVenta->execute([
                ':num'        => $datos['codigo_factura'],
                ':total'      => $datos['total'],
                ':id_usuario' => $idUsuario
            ]);

            $idVentaGenerado = $db->lastInsertId();

            // 2. Decodificar JSON del carrito proveniente de JS
            $productos = is_array($datos['productos']) ? $datos['productos']
             : json_decode($datos['productos'], true);

            // 🔍 AGREGAR ESTO PARA DEPURAR EN EL LOG DE XAMPP:
             error_log("CONTENIDO RECIBIDO EN PRODUCTOS: " . print_r($productos, true));     
            
            if (is_array($productos)) {
                $sqlDetalle = "INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio_unitario) 
                               VALUES (:id_venta, :id_producto, :cantidad, :precio)";
                
                // DESCUENTO DIRECTO EN TABLA PRODUCTOS
                $sqlStock = "UPDATE productos 
                             SET stock_actual = stock_actual - :cantidad 
                             WHERE id_producto = :id_producto";

                $stmtDetalle = $db->prepare($sqlDetalle);
                $stmtStock   = $db->prepare($sqlStock);

                foreach ($productos as $prod) {
                    // Mapeo exacto del JSON que manda crear-venta.php: {id, descripcion, precio, cantidad, total}
                    $idProducto = $prod['id_producto'] ?? $prod['id'] ?? null;
                    $cantidad   = $prod['cantidad'] ?? $prod['cant'] ?? 1;
                    $precio     = $prod['precio'] ?? $prod['precio_venta'] ?? 0;

                    if (!$idProducto) continue;

                    // Insertar detalle
                    $stmtDetalle->execute([
                        ':id_venta'    => $idVentaGenerado,
                        ':id_producto' => $idProducto,
                        ':cantidad'    => $cantidad,
                        ':precio'      => $precio
                    ]);

                    // Descontar Stock
                    $stmtStock->execute([
                        ':cantidad'    => $cantidad,
                        ':id_producto' => $idProducto
                    ]);
                }
            }

            $db->commit();
            return $idVentaGenerado;

        } catch (Exception $e) {
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            error_log("Error en Venta::guardarVentaModel -> " . $e->getMessage());
            return false;
        }
    }

    public static function listarVentasModel() {
        try {
            $db = Conexion::conectar();
            $sql = "SELECT v.*, u.nombre AS nombre_usuario 
                    FROM ventas v
                    LEFT JOIN usuarios u ON v.id_usuario = u.id_usuario
                    ORDER BY v.id_venta DESC";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en Venta::listarVentasModel -> " . $e->getMessage());
            return [];
        }
    }

    public static function obtenerVentaPorIdModel($idVenta) {
        return self::obtenerVentaPorId($idVenta);
    }

    public static function obtenerDetallePorVentaModel($idVenta) {
        return self::obtenerDetallePorVenta($idVenta);
    }

    public static function obtenerVentaPorId($idVenta) {
        try {
            $db = Conexion::conectar();
            $sql = "SELECT v.*, u.nombre AS nombre_usuario 
                    FROM ventas v
                    LEFT JOIN usuarios u ON v.id_usuario = u.id_usuario
                    WHERE v.id_venta = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $idVenta]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en Venta::obtenerVentaPorId -> " . $e->getMessage());
            return false;
        }
    }

    public static function obtenerDetallePorVenta($idVenta) {
        try {
            $db = Conexion::conectar();
            $sql = "SELECT dv.*, p.nombre AS nombre_producto, p.codigo_barras 
                    FROM detalle_ventas dv
                    INNER JOIN productos p ON dv.id_producto = p.id_producto
                    WHERE dv.id_venta = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $idVenta]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en Venta::obtenerDetallePorVenta -> " . $e->getMessage());
            return [];
        }
    }
}