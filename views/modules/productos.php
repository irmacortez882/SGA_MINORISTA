<?php
// modules/productos.php
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Administrar Productos
    </h1>
    <ol class="breadcrumb">
      <li><a href="index.php?action=inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Administrar productos</li>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      
      <div class="box-header with-border">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProducto">
          <i class="fa fa-plus"></i> Agregar Producto
        </button>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalIngresarStock">
          <i class="fa fa-plus-circle"></i> Ingresar Stock
        </button>
      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
        
        <thead>
         <tr>
           <th style="width:10px">#</th>
           <th>Nombre del Producto</th>
           <th>Precio Venta</th>
           <th>Stock Actual</th>
           <th>Stock Mínimo</th>
           <th>Categoría</th>
           <th>Proveedor</th>
           <th>Acciones</th>
         </tr>
        </thead>

        <tbody>

        <?php
        $item = null;
        $valor = null;
        
        $productos = ProductoController::ctrMostrarProductos($item, $valor);

        foreach ($productos as $key => $value) {
          
          // Lógica de alerta de stock mínimo
          if ($value["stock_actual"] <= $value["stock_minimo"]) {
              $alertaStock = 'style="background-color: #f2dede; font-weight: bold;"';
              $badgeStock = ' <span class="label label-danger">¡Bajo stock!</span>';
          } else {
              $alertaStock = '';
              $badgeStock = '';
          }

          echo '<tr>
                  <td>'.($key+1).'</td>
                  <td>'.$value["nombre_producto"].'</td>
                  <td>$ '.$value["precio_venta"].'</td>
                  <td '.$alertaStock.'>'.$value["stock_actual"].$badgeStock.'</td>
                  <td>'.$value["stock_minimo"].'</td>
                  <td>'.$value["nombre_categoria"].'</td>
                  <td>'.$value["nombre_proveedor"].'</td>
                  <td>
                    <div class="btn-group">
                      <button type="button" class="btn btn-warning btnEditarProducto" idProducto="'.$value["id_producto"].'" data-toggle="modal" data-target="#modalEditarProducto"><i class="fa fa-pencil"></i></button>
                      <a class="btn btn-danger btnEliminarProducto" href="index.php?action=productos&idProducto='.$value["id_producto"].'"><i class="fa fa-times"></i></a>
                    </div>
                  </td>
                </tr>';
        }
        ?>

        </tbody>

       </table>

      </div>

    </div>
  </section>
</div>

<!-- =======================================
MODAL INGRESAR STOCK (REPOSICIÓN)
======================================= -->
<div id="modalIngresarStock" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

      <form role="form" method="post">

        <div class="modal-header" style="background:#00a65a; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Ingresar Stock</h4>
        </div>

        <div class="modal-body">
          <div class="box-body">

            <div class="form-group">
              <label>Seleccionar producto</label>
              <select class="form-control input-lg" id="selectProductoStock" name="idProductoStock" required>
                <option value="">Seleccionar producto</option>
                <?php
                $listaProductosStock = ProductoController::ctrMostrarProductos(null, null);
                foreach ($listaProductosStock as $prod) {
                  echo '<option value="'.$prod["id_producto"].'" data-stock="'.$prod["stock_actual"].'" data-categoria="'.$prod["nombre_categoria"].'" data-proveedor="'.$prod["nombre_proveedor"].'">'
                    .$prod["nombre_producto"].' (Stock actual: '.$prod["stock_actual"].')
                  </option>';
                }
                ?>
              </select>
            </div>

            <div class="well well-sm" id="infoProductoStock" style="display:none;">
              <strong>Stock actual:</strong> <span id="lblStockActual">0</span><br>
              <strong>Categoría:</strong> <span id="lblCategoriaStock">-</span><br>
              <strong>Proveedor:</strong> <span id="lblProveedorStock">-</span>
            </div>

            <div class="form-group">
              <label>Cantidad a ingresar</label>
              <input type="number" min="1" step="1" class="form-control input-lg" name="cantidadIngresar" placeholder="Ingresar cantidad" required>
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-success">Guardar ingreso</button>
        </div>

        <?php
        $ingresarStock = new ProductoController();
        $ingresarStock->ctrIngresarStock();
        ?>

      </form>

    </div>
  </div>
</div>

<!-- =======================================
MODAL AGREGAR PRODUCTO
======================================= -->
<div id="modalAgregarProducto" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

      <form role="form" method="post">

        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Agregar Producto</h4>
        </div>

        <div class="modal-body">
          <div class="box-body">

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>
                <input type="text" class="form-control input-lg" name="nuevoNombreProducto" placeholder="Ingresar nombre del producto" required>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span>
                <select class="form-control input-lg" name="nuevaCategoria" required>
                  <option value="">Seleccionar categoría</option>
                  <?php
                  $categorias = CategoriaController::ctrMostrarCategorias(null, null);
                  foreach ($categorias as $key => $cat) {
                    echo '<option value="'.$cat["id_categoria"].'">'.$cat["nombre_categoria"].'</option>';
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-truck"></i></span>
                <select class="form-control input-lg" name="nuevoProveedor" required>
                  <option value="">Seleccionar proveedor</option>
                  <?php
                  $proveedores = ProveedorController::ctrMostrarProveedores(null, null);
                  foreach ($proveedores as $key => $prov) {
                    echo '<option value="'.$prov["id_proveedor"].'">'.$prov["nombre_proveedor"].'</option>';
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
                <input type="number" class="form-control input-lg" name="nuevoStockActual" placeholder="Stock actual" required>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-warning"></i></span>
                <input type="number" class="form-control input-lg" name="nuevoStockMinimo" placeholder="Stock mínimo" required>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                <input type="number" step="0.01" class="form-control input-lg" name="nuevoPrecioVenta" placeholder="Precio de venta" required>
              </div>
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-primary">Guardar producto</button>
        </div>

        <?php
        $crearProducto = new ProductoController();
        $crearProducto->ctrCrearProducto();
        ?>

      </form>

    </div>
  </div>
</div>

<!-- =======================================
MODAL EDITAR PRODUCTO
======================================= -->
<div id="modalEditarProducto" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

      <form role="form" method="post">

        <div class="modal-header" style="background:#f39c12; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Editar Producto</h4>
        </div>

        <div class="modal-body">
          <div class="box-body">

            <input type="hidden" id="idProducto" name="idProducto">

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>
                <input type="text" class="form-control input-lg" id="editarNombreProducto" name="editarNombreProducto" required>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span>
                <select class="form-control input-lg" name="editarCategoria" required>
                  <option id="editarCategoriaSelect" value=""></option>
                  <?php
                  $categorias = CategoriaController::ctrMostrarCategorias(null, null);
                  foreach ($categorias as $key => $cat) {
                    echo '<option value="'.$cat["id_categoria"].'">'.$cat["nombre_categoria"].'</option>';
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-truck"></i></span>
                <select class="form-control input-lg" name="editarProveedor" required>
                  <option id="editarProveedorSelect" value=""></option>
                  <?php
                  $proveedores = ProveedorController::ctrMostrarProveedores(null, null);
                  foreach ($proveedores as $key => $prov) {
                    echo '<option value="'.$prov["id_proveedor"].'">'.$prov["nombre_proveedor"].'</option>';
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
                <input type="number" class="form-control input-lg" id="editarStockActual" name="editarStockActual" required>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-warning"></i></span>
                <input type="number" class="form-control input-lg" id="editarStockMinimo" name="editarStockMinimo" required>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                <input type="number" step="0.01" class="form-control input-lg" id="editarPrecioVenta" name="editarPrecioVenta" required>
              </div>
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-warning">Guardar cambios</button>
        </div>

        <?php
        $editarProducto = new ProductoController();
        $editarProducto->ctrEditarProducto();
        ?>

      </form>

    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  $("#selectProductoStock").on("change", function() {
    var opt = $(this).find("option:selected");
    if (!$(this).val()) {
      $("#infoProductoStock").hide();
      return;
    }
    $("#lblStockActual").text(opt.attr("data-stock") || 0);
    $("#lblCategoriaStock").text(opt.attr("data-categoria") || "-");
    $("#lblProveedorStock").text(opt.attr("data-proveedor") || "-");
    $("#infoProductoStock").show();
  });
});
</script>

      
       
                   
       