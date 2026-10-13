<?php
// Obtener historial de ventas ordenadas por fecha
$ventas = VentaController::listarVentasController();
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-file-text-o"></i> Historial de Facturas y Ventas</h1>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Facturas Registradas (Ordenadas por Fecha)</h3>
                <a href="index.php?action=crear-venta" class="btn btn-success pull-right">
                    <i class="fa fa-plus"></i> Nueva Venta
                </a>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive">
                    <thead>
                        <tr>
                            <th style="width:10px">#</th>
                            <th>Nº Factura</th>
                            <th>Fecha y Hora</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ventas as $index => $v): ?>
                            <tr>
                                <td><?php echo ($index + 1); ?></td>
                                <td><strong><?php echo $v["codigo_factura"]; ?></strong></td>
                                <td><?php echo date("d/m/Y H:i", strtotime($v["fecha_hora"])); ?></td>
                                <td>$<?php echo number_format($v["total"], 2); ?></td>
                                <td>
                                    <a href="index.php?action=ver-venta&id=<?php echo $v["id_venta"]; ?>" class="btn btn-info btn-xs">
                                        <i class="fa fa-eye"></i> Ver Detalle
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($ventas)): ?>
                            <tr><td colspan="5" class="text-center">No hay ventas registradas aún.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Modal / Contenedor Oculto del Ticket -->
<div id="modalTicket" class="modal-ticket-overlay" style="display: none;">
    <div class="modal-ticket-content">
        <span class="btn-cerrar-modal" onclick="cerrarTicket()">&times;</span>
        
        <!-- Aquí metemos el diseño que armamos antes -->
        <div class="ticket-container" id="ticketImpresion">
            <div class="ticket-header">
                <h3>SGA Minorista</h3>
                <p>Comercio Minorista de Gestión</p>
                <p>CUIT: 30-12345678-9</p>
                <hr>
                <p><strong>Ticket:</strong> <span id="nroTicket">0001-00000000</span></p>
                <p><strong>Fecha:</strong> <span id="fechaTicket"></span></p>
                <hr>
            </div>

            <div class="ticket-body">
                <table class="ticket-table">
                    <thead>
                        <tr>
                            <th>Cant</th>
                            <th>Descripción</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="detalleTicketItems">
                        {/* Los productos se inyectarán dinámicamente con JavaScript */}
                    </tbody>
                </table>
                <hr>
                <div class="ticket-totals">
                    <p><strong>Total:</strong> <span id="totalTicket">$ 0.00</span></p>
                </div>
            </div>

            <div class="ticket-footer">
                <p>¡Gracias por su compra!</p>
            </div>
        </div>

        <button onclick="window.print()" class="btn-imprimir">Imprimir Ticket</button>
    </div>
</div>
function mostrarTicket(datosVenta) {
    // 1. Rellenamos los datos básicos
    document.getElementById('nroTicket').innerText = datosVenta.codigo;
    document.getElementById('fechaTicket').innerText = datosVenta.fecha;
    document.getElementById('totalTicket').innerText = '$ ' + datosVenta.total;

    // 2. Limpiamos y cargamos los productos en la tabla del ticket
    let tbody = document.getElementById('detalleTicketItems');
    tbody.innerHTML = '';
    
    datosVenta.productos.forEach(item => {
        let fila = `<tr>
            <td>${item.cantidad}</td>
            <td>${item.descripcion}</td>
            <td>$ ${item.subtotal}</td>
        </tr>`;
        tbody.innerHTML += fila;
    });

    // 3. Mostramos el modal
    document.getElementById('modalTicket').style.display = 'flex';
}

function cerrarTicket() {
    document.getElementById('modalTicket').style.display = 'none';
}