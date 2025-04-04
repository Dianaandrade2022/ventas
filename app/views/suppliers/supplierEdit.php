<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Editar proveedor</h1>
        <a href="SuppliersController.php?name=suppliers_list" class="btn btn-primary">Regresar al listado</a>
    </div>
    <hr class="mb-5">
    <div class="row">
        <div class="col-lg-4 m-auto mt-3">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="hidden" name="id" value="<?= $data_supplier["id"]; ?>">
                <div id="alertContainer">
                    <?= isset($alert) ? $alert : ''; ?>
                </div>
                <div class="form-group">
                    <label><b>Selecciona la tienda</b></label>
                    <select name="storeId" id="storeId" class="form-control">
                        <?php foreach ($stores as $store) :
                            /* if ($_SESSION['storeId'] == $store["id"]) : */
                        ?>
                            <option value="<?= htmlspecialchars($store["id"]) ?>">
                                <?= htmlspecialchars($store["name"]) ?>
                            </option>
                        <?php /* endif; */
                        endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><b>Selecciona la sucursal</b></label>
                    <select name="branchId" id="branchId" class="form-control">
                        <?php foreach ($branches as $branch) :
                            /* if ($_SESSION['branchId'] == $branch["id"]) : */
                        ?>
                            <option value="<?= htmlspecialchars($branch["id"]) ?>">
                                <?= htmlspecialchars($branch["name"]) ?>
                            </option>
                        <?php /* endif; */
                        endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name"><b>Nombre</b></label>
                    <input type="text" placeholder="Ingrese Nombre" name="name" class="form-control" value="<?= $data_supplier["name"]; ?>">
                </div>
                <div class="form-group">
                    <label for="phoneNumber"><b>Teléfono</b></label>
                    <input type="number" placeholder="Ingrese Teléfono" name="phoneNumber" class="form-control" value="<?= $data_supplier["phoneNumber"]; ?>">
                </div>
                <div class="form-group">
                    <label for="address"><b>Dirección</b></label>
                    <input type="text" placeholder="Ingrese Direccion" name="address" class="form-control" value="<?= $data_supplier["address"]; ?>">
                </div>
                <button type="submit" class="btn btn-primary text-center w-100" name="edit_supplier" value="edit_supplier"><i class="fas fa-user-edit"></i> Editar proveedor</button>
            </form>
        </div>
    </div>
</div>