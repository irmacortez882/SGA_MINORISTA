<?php
require_once "controllers/VentaController.php";
require_once "controllers/ProductoController.php";
require_once "models/Producto.php";
require_once "models/Venta.php";
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Registrar Nueva Venta
            <small>Facturación de Productos</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php?action=inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Crear Venta</li>
        </ol>
    </section>

    <section class="content">
        <form id="formFactura" class="row">
            
            <!-- COLUMNA IZQUIERDA: SELECCIÓN DE PRODUCTOS -->
            <div class="col-md-5 col-xs-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">1. Seleccionar Producto</h3>
                    </div>
                    <div class="box-body">
                        
                        <!-- SELECCIÓN DE PRODUCTO -->
                        <div class="form-group">
                            <label for="selectProducto">Producto:</label>
                            <select class="form-control" id="selectProducto" style="width: 100%;">
                                <option value="">-- Seleccionar Producto --</option>
                                <?php
                                try {
                                    $productos = ProductoController::listarProductosController();

                                    if (!empty($productos) && is_array($productos)) {
                                        foreach ($productos as $key => $value) {
                                            $idProd = $value["id_producto"] ?? $value["id"] ?? 0;
                                            $desc   = htmlspecialchars($value["nombre_producto"] ?? $value["nombre"] ?? $value["descripcion"] ?? "Producto");
                                            $precio = $value["precio_venta"] ?? $value["precio_unitario"] ?? $value["precio"] ?? 0;
                                            $stock  = $value["stock_actual"] ?? $value["stock"] ?? $value["cant_stock"] ?? 0;

                                            echo '<option value="'.$idProd.'" data-precio="'.$precio.'" data-stock="'.$stock.'" data-descripcion="'.$desc.'">'.$desc.' (Stock: '.$stock.') - $'.number_format($precio, 2, ',', '.').'</option>';
                                        }
                                    }
                                } catch (Exception $e) {
                                    echo '<option value="">Error al cargar productos</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <!-- CANTIDAD -->
                        <div class="form-group">
                            <label for="cantProducto">Cantidad:</label>
                            <input type="number" class="form-control" id="cantProducto" min="1" value="1">
                        </div>

                        <button type="button" class="btn btn-primary btn-block" id="btnAgregarCarrito">
                            <i class="fa fa-cart-plus"></i> Agregar al Carrito
                        </button>

                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA: DETALLE Y TOTALES DE LA FACTURA -->
            <div class="col-md-7 col-xs-12">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">2. Detalle de la Factura</h3>
                    </div>
                    <div class="box-body">
                        
                        <!-- TABLA DEL CARRITO -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tablaCarrito">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">Cant.</th>
                                        <th style="width: 45%;">Producto</th>
                                        <th style="width: 20%;">Precio U.</th>
                                        <th style="width: 20%;">Subtotal</th>
                                        <th style="width: 5%;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="filaVacia">
                                        <td colspan="5" class="text-center">No hay productos en la cesta</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <hr>

                        <!-- MUESTRA DEL TOTAL -->
                        <div class="row">
                            <div class="col-xs-12 text-right">
                                <h2 style="margin:0; font-weight: bold;">Total: $<span id="lblTotal">0.00</span></h2>
                                <input type="hidden" name="totalVenta" id="totalVenta" value="0">
                                <input type="hidden" name="listaProductos" id="listaProductos" value="[]">
                            </div>
                        </div>

                    </div>
                    
                    <div class="box-footer text-right">
                        <!-- 1. Vaciar Carrito -->
                        <button type="button" class="btn btn-default pull-left" id="btnVaciarCarrito">
                            <i class="fa fa-trash"></i> Vaciar Carrito
                        </button>
                        
                        <!-- 2. Previsualización limpia de ticket borrador -->
                        <button type="button" class="btn btn-info" id="btnImprimirBorrador">
                            <i class="fa fa-print"></i> Imprimir / Guardar PDF
                        </button>

                        <!-- 3. Confirmar y Registrar en BD -->
                        <button type="submit" class="btn btn-success btn-lg" id="btnConfirmarVenta">
                            <i class="fa fa-check-circle"></i> Confirmar y Facturar
                        </button>
                    </div>

                </div>
            </div>

        </form>
    </section>
</div>

<!-- JAVASCRIPT CON AJAX PARA PROCESAR TRANSACCIÓN Y TICKET -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    var carrito = [];

    var btnAgregar  = $("#btnAgregarCarrito");
    var selectProd  = $("#selectProducto");
    var inputCant   = $("#cantProducto");
    var btnVaciar   = $("#btnVaciarCarrito");
    var btnBorrador = $("#btnImprimirBorrador");
    var form        = $("#formFactura");

    // 1. Agregar Producto
    btnAgregar.on("click", function(e) {
        e.preventDefault();

        var selectedOption = selectProd.find('option:selected');
        var id = selectProd.val();

        if (!id || id === "") {
            alert("Por favor, selecciona un producto.");
            return;
        }

        var descripcion = selectedOption.attr("data-descripcion") || selectedOption.text();
        var precio      = parseFloat(selectedOption.attr("data-precio") || 0);
        var stock       = parseInt(selectedOption.attr("data-stock") || 0);
        var cantidad    = parseInt(inputCant.val() || 1);

        if (isNaN(cantidad) || cantidad <= 0) {
            alert("Ingresa una cantidad válida.");
            return;
        }

        if (cantidad > stock) {
            alert("La cantidad supera el stock disponible (" + stock + ").");
            return;
        }

        var encontrado = false;
        for (var i = 0; i < carrito.length; i++) {
            if (carrito[i].id == id) {
                if ((carrito[i].cantidad + cantidad) > stock) {
                    alert("Supera el stock disponible.");
                    return;
                }
                carrito[i].cantidad += cantidad;
                carrito[i].total = carrito[i].cantidad * carrito[i].precio;
                encontrado = true;
                break;
            }
        }

        if (!encontrado) {
            carrito.push({
                id: id,
                descripcion: descripcion,
                precio: precio,
                cantidad: cantidad,
                total: cantidad * precio
            });
        }

        renderizarTabla();
        inputCant.val(1);
    });

    // 2. Vaciar Carrito
    btnVaciar.on("click", function() {
        carrito = [];
        renderizarTabla();
    });

    // 3. Imprimir Presupuesto/Borrador
    btnBorrador.on("click", function() {
        if (carrito.length === 0) {
            alert("Agrega al menos un producto para previsualizar.");
            return;
        }
        window.print();
    });

    // 4. Renderizar Tabla en HTML
    function renderizarTabla() {
        var tbody      = $("#tablaCarrito tbody");
        var lblTotal   = $("#lblTotal");
        var inputTotal = $("#totalVenta");
        var inputLista = $("#listaProductos");

        tbody.empty();
        var totalGeneral = 0;

        if (carrito.length === 0) {
            tbody.html('<tr id="filaVacia"><td colspan="5" class="text-center">No hay productos en la cesta</td></tr>');
        } else {
            for (var i = 0; i < carrito.length; i++) {
                var item = carrito[i];
                totalGeneral += item.total;

                var tr = $("<tr>");
                tr.html('<td>' + item.cantidad + '</td>' +
                    '<td>' + item.descripcion + '</td>' +
                    '<td>$' + item.precio.toFixed(2) + '</td>' +
                    '<td>$' + item.total.toFixed(2) + '</td>' +
                    '<td class="text-center">' +
                        '<button type="button" class="btn btn-danger btn-xs btnEliminar" data-id="' + item.id + '">' +
                            '<i class="fa fa-times"></i>' +
                        '</button>' +
                    '</td>');
                tbody.append(tr);
            }
        }

        lblTotal.text(totalGeneral.toFixed(2));
        inputTotal.val(totalGeneral);
        inputLista.val(JSON.stringify(carrito));

        $(".btnEliminar").on("click", function() {
            var idEliminar = $(this).attr("data-id");
            carrito = carrito.filter(function(p) { return p.id != idEliminar; });
            renderizarTabla();
        });
    }

    // 5. Enviar por AJAX al controlador para descontar stock, guardar y abrir el ticket
    form.on("submit", function(e) {
        e.preventDefault();

        if (carrito.length === 0) {
            alert("Debes agregar al menos un producto para facturar.");
            return;
        }

        var totalVenta = $("#totalVenta").val();
        var listaProductos = $("#listaProductos").val();

        var datos = new FormData();
        datos.append("totalVenta", totalVenta);
        datos.append("listaProductos", listaProductos);

        $.ajax({
            url: "ajax/ventas.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(respuesta) {
                if (respuesta && respuesta.status === "ok") {
                    alert("¡Venta registrada con éxito! Factura N°: " + respuesta.codigo_factura);
                    window.open("index.php?action=ver-ventas&id=" + respuesta.id_venta, "_blank");
                    window.location = "index.php?action=crear-venta";
                } else {
                    // Muestra el mensaje exacto que viene del servidor
                    alert("Error devuelto por el servidor: " + (respuesta.mensaje || JSON.stringify(respuesta)));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Muestra el texto bruto de la respuesta PHP si no es un JSON válido
                console.error("Respuesta raw del servidor:", jqXHR.responseText);
                alert("Error de sintaxis o excepción PHP. Revisá la consola (F12) en la pestaña Console/Network.");
            }
        });
    });

});
</script>