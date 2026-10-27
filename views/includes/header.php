<header class="main-header">

  <!-- Logo del Sistema -->
  <a href="<?php echo URL; ?>index.php?action=inicio" class="logo">
    <span class="logo-mini"><b>SGA</b></span>
    <span class="logo-lg"><b>SGA</b> Minorista</span>
  </a>

  <!-- Barra de Navegación Superior -->
  <nav class="navbar navbar-static-top" role="navigation">
    
    <!-- Botón para colapsar menú lateral -->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>

    <!-- Menú Superior Derecho (Accesos Directos) -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">

        <!-- 1. Botón Directo: Nueva Venta -->
        <li>
          <a href="<?php echo URL; ?>index.php?action=crear-venta">
            <i class="fa fa-shopping-cart"></i> <span>Nueva Venta</span>
          </a>
        </li>

        <!-- 2. Botón Directo: Listado de Notas de Crédito -->
        <li>
          <a href="<?php echo URL; ?>index.php?action=notas-credito">
            <i class="fa fa-list"></i> <span>Ver NC</span>
          </a>
        </li>

        <!-- 3. Botón Directo: Crear Nota de Crédito -->
        <li>
          <a href="<?php echo URL; ?>index.php?action=crear-nota-credito">
            <i class="fa fa-plus-circle"></i> <span>Nueva NC</span>
          </a>
        </li>

        <!-- 4. Menú Usuario / Salir -->
        <li>
          <a href="<?php echo URL; ?>index.php?action=salir">
            <i class="fa fa-power-off text-red"></i> <span>Salir</span>
          </a>
        </li>

      </ul>
    </div>

  </nav>

</header>