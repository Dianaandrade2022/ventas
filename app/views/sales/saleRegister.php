<div class="row wrapper border-bottom white-bg page-heading animated fadeInRight">
    <div>
        <a href="#" class="btn btn-primary btn-sm m-3" id="client_new_btn"><i class="fa fa-user-plus"> Nuevo Cliente </i> </a>
    </div>
</div>
<div class="container-fluid">
<div class="modal fade" id="additionalCostsModal" tabindex="-1" aria-labelledby="additionalCostsModalLabel" style="display: none;" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="additionalCostsModalLabel">Agregar Costos Adicionales</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group my-4">
                <h1 class="text-center">Datos Del Cliente</h1>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                        <input type="hidden" name="action" value="client_register">
                        <input type="hidden" name="name" value="sale_new">
                        <div id="alertContainer">
                            <div id="alertContainer">
                                <?= isset($alert) ? $alert : ''; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><b>DNI</b></label>
                                    <input type="number" name="client_dni" id="client_dni" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><b>Nombre (Razón social)</b></label>
                                    <input type="text" name="client_name" id="client_name" class="form-control" disabled required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><b>Teléfono</b></label>
                                    <input type="number" name="client_phone" id="client_phone" class="form-control" disabled required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><b>Dirección</b></label>
                                    <input type="text" name="client_address" id="client_address" class="form-control" disabled required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><b>Correo</b></label>
                                    <input type="text" name="client_email" id="client_email" class="form-control" disabled required>
                                </div>
                            </div>
                            <div class="mt-4" id="client_register_div" style="display: none;">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-user-plus"> Registrar Nuevo Cliente </i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <h1 class="text-center mt-4">Datos del proyecto</h1>
            <div class="row d-flex justify-content-between">
                <div class="mx-5">
                    <div class="form-group">
                        <label><i class="fa fa-user"></i> VENDEDOR</label>
                        <p><?php echo $_SESSION['name']; ?></p>
                    </div>
                </div>
                <div class="mx-5">
                    <div id="acciones_venta" class="form-group">
                        <a href="#" class="btn btn-danger mx-2" id="cancel_sale_btn"><i class="fa fa-times" aria-hidden="true"></i> Anular </a>
                        <a href="#" class="btn btn-primary" id="sale_btn"><i class="fa fa-dollar"></i> Generar </a>
                        <a href="#" class="btn btn-secondary" id="extra_btn"><i class="fa fa-plus"></i> Gasto extra</a>  
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr style="background-color:#4B75F2; color: #fff">
                            <th colspan="2">Nombre / Id producto</th>
                            <th>Descripción</th>
                            <th>Stock</th>
                            <th width="50px">Cantidad</th>
                            <th>Precio</th>
                            <th width="80px">Costo taller</th>
                            <th>Precio Final</th>
                            <th>Estatus</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                        <tr>
                            <td><input class="form-control" type="text" name="product_number" id="product_number"></td>
                            <td id="name" colspan="2">-</td>
                            <td id="stock">-</td>
                            <td><input class="form-control" type="text" name="product_quantity" id="product_quantity" min="1" disabled></td>
                            <td id="price">0.0 </td>
                            <td><input class="form-control" type="number" name="cost" id="cost" value="0.00"  min="0.00" ></td>
                            <td id="total" hidden>0.0 </td>
                            <td><input class="form-control" type="number" name="precioFinal" id="precioFinal" value="0.00" ></td>
                            
                            <td>
                                <select name="status" id="status" class="form-control">
                                    <option value="comprado" style="background:rgb(86, 171, 235);">Comprado</option>
                                    <option value="en_taller" style="background:rgb(120, 144, 156);">En taller</option>
                                    <option value="en_entrega" style="background:rgb(129, 212, 250);">En entrega</option>
                                    <option value="entregado" style="background:rgb(100, 221, 168);">Entregado</option>
                                </select>
                            </td>
                            <td class="text-center"><a href="#" id="product_add" class="btn btn-primary" style="display: none;"><i class="fa fa-check" aria-hidden="true"></i> Agregar</a></td>
                        </tr>
                        <tr style="background-color:#4B75F2; color: #fff">
                            <th colspan="2">Nombre / Id producto</th>
                            <th colspan="2">Descripción</th>
                            <th>Cantidad</th>
                            <th>Precio Final</th>
                            <th>Costo taller</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="sale_detail">
                        <!-- Contenido ajax -->
                    </tbody>
                    <tfoot id="detalle_totales">
                        <!-- Contenido ajax -->
                
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
