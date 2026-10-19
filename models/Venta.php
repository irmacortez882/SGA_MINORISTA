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

            // 1. Obtener el último código de factura / número correlativo
            $sqlUltimo = "SELECT numero_comprobante FROM ventas ORDER BY id_venta DESC LIMIT 1";
            $stmtUltimo = $db->prepare($sqlUltimo);
            $stmtUltimo->execute();
            $ultimaVenta = $stmtUltimo->fetch(PDO::FETCH_ASSOC);

            if (!$ultimaVenta || empty($ultimaVenta['numero_comprobante'])) {
                $codigoFactura = "0001-00000001";
            } else {
                $partes = explode("-", $ultimaVenta['numero_comprobante']);
                $siguienteNumero = intval($partes[1]) + 1;
                $codigoFactura = $partes[0] . "-" . str_pad($siguienteNumero, 8, "0", STR_PAD_LEFT);
            }

            // 2. Insertar el encabezado de la venta usando el correlativo generado
            $sqlVenta = "INSERT INTO ventas (numero_comprobante, tipo_comprobante, fecha_venta, total, id_usuario, estado) 
                         VALUES (:num, 'FAC', NOW(), :total, :id_usuario, '1')";
            $stmtVenta = $db->prepare($sqlVenta);
            $stmtVenta->execute([
                ':num'        => $codigoFactura,
                ':total'      => $datos['total'],
                ':id_usuario' => $idUsuario
            ]);

            $idVentaGenerado = $db->lastInsertId();

            // 3. Decodificar JSON del carrito proveniente de JS
            $productos = is_array($datos['productos']) ? $datos['productos']
             : json_decode($datos['productos'], true);

            // Depuración en el log de XAMPP
            error_log("CONTENIDO RECIBIDO EN PRODUCTOS: " . print_r($productos, true));    
            
            if (is_array($productos)) {
                $sqlDetalle = "INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio_unitario) 
                               VALUES (:id_venta, :id_producto, :cantidad, :precio)";
                
                // Descuento y validación de stock en tabla productos
                $sqlStockCheck = "SELECT stock_actual FROM productos WHERE id_producto = :id_producto FOR UPDATE";
                $sqlStockUpdate = "UPDATE productos 
                                   SET stock_actual = stock_actual - :cantidad 
                                   WHERE id_producto = :id_producto";

                $stmtDetalle    = $db->prepare($sqlDetalle);
                $stmtStockCheck = $db->prepare($sqlStockCheck);
                $stmtStockUpdate = $db->prepare($sqlStockUpdate);

                foreach ($productos as $prod) {
                    $idProducto = $prod['id_producto'] ?? $prod['id'] ?? null;
                    $cantidad   = $prod['cantidad'] ?? $prod['cant'] ?? 1;
                    $precio     = $prod['precio'] ?? $prod['precio_venta'] ?? 0;

                    if (!$idProducto) continue;

                    // Verificar stock actual antes de descontar para asegurar consistencia
                    $stmtStockCheck->execute([':id_producto' => $idProducto]);
                    $prodActual = $stmtStockCheck->fetch(PDO::FETCH_ASSOC);

                    if ($prodActual && $prodActual['stock_actual'] >= $cantidad) {
                        // Insertar detalle
                        $stmtDetalle->execute([
                            ':id_venta'    => $idVentaGenerado,
                            ':id_producto' => $idProducto,
                            ':cantidad'    => $cantidad,
                            ':precio'      => $precio
                        ]);

                        // Descontar Stock
                        $stmtStockUpdate->execute([
                            ':cantidad'    => $cantidad,
                            ':id_producto' => $idProducto
                        ]);
                    } else {
                        throw new Exception("Stock insuficiente para el producto ID: " . $idProducto);
                    }
                }
            }

            $db->commit();
            return [
                "status" => true,
                "id_venta" => $idVentaGenerado,
                "codigo_factura" => $codigoFactura
            ];

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
            $sql = "SELECT dv.*, p.nombre_producto, p.codigo_barras 
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