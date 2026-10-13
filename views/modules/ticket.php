<div class="ticket-container" id="ticketImpresion">
    <div class="ticket-header">
        <h3>SGA Minorista</h3>
        <p>Comercio Minorista de Gestión</p>
        <p>CUIT: 30-12345678-9</p>
        <p>Dirección: Av. Mitre 1234, Vicente López</p>
        <hr>
        <p><strong>Factura / Ticket:</strong> <span id="nroTicket">0001-00000123</span></p>
        <p><strong>Fecha:</strong> <span id="fechaTicket">13/10/2026 12:49</span></p>
        <p><strong>Cliente:</strong> Consumidor Final</p>
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
                <!-- Ejemplo de filas que se llenarán con JS -->
                <tr>
                    <td>2</td>
                    <td>Fideos Matarazzo 500g</td>
                    <td>$ 1.800</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Aceite Girasol 1.5L</td>
                    <td>$ 2.500</td>
                </tr>
            </tbody>
        </table>
        <hr>
        <div class="ticket-totals">
            <p><strong>Total a Pagar:</strong> <span id="totalTicket">$ 4.300</span></p>
        </div>
    </div>

    <div class="ticket-footer">
        <p>¡Gracias por su compra!</p>
        <p>Comprobante no válido como factura fiscal</p>
    </div>
</div>

<button onclick="window.print()" class="btn-imprimir">Imprimir Ticket</button>

<style>
/* Estilos compactos estilo impresora térmica */
.ticket-container {
    width: 300px;
    background: #fff;
    padding: 15px;
    font-family: 'Courier New', Courier, monospace;
    font-size: 12px;
    color: #000;
    border: 1px solid #ddd;
    margin: 20px auto;
}
.ticket-header, .ticket-footer {
    text-align: center;
}
.ticket-header p, .ticket-footer p, .ticket-totals p {
    margin: 3px 0;
}
.ticket-table {
    width: 100%;
    border-collapse: collapse;
    margin: 10px 0;
}
.ticket-table th, .ticket-table td {
    text-align: left;
    padding: 4px 0;
    font-size: 11px;
}
.ticket-table th {
    border-bottom: 1px dashed #000;
    border-top: 1px dashed #000;
}
.ticket-totals {
    text-align: right;
    font-size: 14px;
}
.btn-imprimir {
    display: block;
    margin: 0 auto;
    padding: 8px 16px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.btn-imprimir:hover {
    background-color: #218838;
}

/* Regla especial para que al mandar a imprimir salga solo el ticket */
@media print {
    body * {
        visibility: hidden;
    }
    #ticketImpresion, #ticketImpresion * {
        visibility: visible;
    }
    #ticketImpresion {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none;
    }
    .btn-imprimir {
        display: none;
    }
}
</style>