<div class="container">
    <!-- Encabezado de bienvenida -->
    <div class="p-4 p-md-5 mb-4 bg-white border rounded-3 shadow-sm">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge text-bg-primary mb-2">Panel Principal</span>
                <h1 class="h3 fw-bold mb-2">Sistema SGA Minorista</h1>
                <p class="text-muted mb-0">
                    Gestión comercial y administrativa para comercio minorista. Seleccioná un módulo para comenzar.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="<?php echo URL; ?>index.php?action=crear-venta" class="btn btn-primary btn-lg">
                    <i class="bi bi-cart-plus me-2"></i>Nueva Venta
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjetas de acceso rápido -->
    <div class="row g-4">
        <!-- Nueva Venta / Facturación -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">
                            <i class="bi bi-receipt fs-4"></i>
                        </div>
                        <h2 class="h5 card-title mb-0">Facturación y Ventas</h2>
                    </div>
                    <p class="card-text text-muted flex-grow-1">
                        Registrá nuevas operaciones de venta y emití comprobantes o tickets.
                    </p>
                    <a href="<?php echo URL; ?>index.php?action=crear-venta" class="btn btn-outline-primary mt-2">
                        Ir a Nueva Venta
                    </a>
                </div>
            </div>
        </div>

        <!-- Historial de Ventas -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-3 p-3 me-3">
                            <i class="bi bi-journal-check fs-4"></i>
                        </div>
                        <h2 class="h5 card-title mb-0">Listado de Ventas</h2>
                    </div>
                    <p class="card-text text-muted flex-grow-1">
                        Consultá las ventas realizadas y accedé al detalle de cada operación.
                    </p>
                    <a href="<?php echo URL; ?>index.php?action=ventas" class="btn btn-outline-success mt-2">
                        Ver Ventas
                    </a>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 text-info rounded-3 p-3 me-3">
                            <i class="bi bi-box-seam fs-4"></i>
                        </div>
                        <h2 class="h5 card-title mb-0">Productos</h2>
                    </div>
                    <p class="card-text text-muted flex-grow-1">
                        Administrá el catálogo de artículos, precios y disponibilidad.
                    </p>
                    <a href="<?php echo URL; ?>index.php?action=productos" class="btn btn-outline-info mt-2">
                        Gestionar Productos
                    </a>
                </div>
            </div>
        </div>

        <!-- Categorías -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3">
                            <i class="bi bi-tags fs-4"></i>
                        </div>
                        <h2 class="h5 card-title mb-0">Categorías</h2>
                    </div>
                    <p class="card-text text-muted flex-grow-1">
                        Organizá los productos por rubros o familias para facilitar su búsqueda.
                    </p>
                    <a href="<?php echo URL; ?>index.php?action=categorias" class="btn btn-outline-warning mt-2">
                        Ver Categorías
                    </a>
                </div>
            </div>
        </div>

        <!-- Proveedores -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-secondary bg-opacity-10 text-secondary rounded-3 p-3 me-3">
                            <i class="bi bi-truck fs-4"></i>
                        </div>
                        <h2 class="h5 card-title mb-0">Proveedores</h2>
                    </div>
                    <p class="card-text text-muted flex-grow-1">
                        Mantené actualizados los datos de contacto y abastecimiento de tus proveedores.
                    </p>
                    <a href="<?php echo URL; ?>index.php?action=proveedores" class="btn btn-outline-secondary mt-2">
                        Ver Proveedores
                    </a>
                </div>
            </div>
        </div>

        <!-- Inventario e Informes -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-dark bg-opacity-10 text-dark rounded-3 p-3 me-3">
                            <i class="bi bi-bar-chart-line fs-4"></i>
                        </div>
                        <h2 class="h5 card-title mb-0">Inventario e Informes</h2>
                    </div>
                    <p class="card-text text-muted flex-grow-1">
                        Revisá el control de stock y consultá reportes resumidos del sistema.
                    </p>
                    <div class="d-flex gap-2 mt-2">
                        <a href="<?php echo URL; ?>index.php?action=inventario" class="btn btn-outline-dark flex-fill">
                            Inventario
                        </a>
                        <a href="<?php echo URL; ?>index.php?action=informes" class="btn btn-outline-primary flex-fill">
                            Informes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>