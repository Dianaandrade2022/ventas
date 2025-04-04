<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Editar sucursal</h1>
        <a href="BranchesController.php?name=branches_list" class="btn btn-primary">Regresar al listado</a>
    </div>
    <hr class="mb-5">
    <div class="row">
        <div class="col-lg-4 m-auto">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="hidden" name="id" value="<?= htmlspecialchars($data_branch['id']); ?>">
                <div id="alertContainer">
                    <?= isset($alert) ? $alert : ''; ?>
                </div>
                <div class="form-group">
                    <label><b>Selecciona la tienda</b></label>
                    <select name="storeId" id="storeId" class="form-control">
                        <?php
                        $rows = getBranches();
                        foreach ($stores as $store) :
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
                    <label for="cuit">CUIT</label>
                    <input type="text" placeholder="Ingrese RFC" name="RFC" class="form-control" value="<?= htmlspecialchars($data_branch['RFC']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" placeholder="Ingrese Nombre" name="name" class="form-control" value="<?= htmlspecialchars($data_branch['name']); ?>">
                </div>
                <div class="form-group">
                    <label for="phoneNumber">Teléfono de contacto</label>
                    <input type="text" placeholder="Ingrese Teléfono" name="phoneNumber" class="form-control" value="<?= htmlspecialchars($data_branch['phoneNumber']); ?>">
                </div>
                <div class="form-group">
                    <label for="address">Dirección</label>
                    <input type="text" placeholder="Ingrese Direccion" name="address" class="form-control" value="<?= htmlspecialchars($data_branch['address']); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Correo de contacto</label>
                    <input type="email" placeholder="Ingrese Correo" name="email" class="form-control" value="<?= htmlspecialchars($data_branch['email']); ?>">
                </div>
                <button type="submit" class="btn btn-primary text-center w-100" name="edit_bach" value="edit_bach"><i class="fas fa-user-edit"></i> Editar Sucursal</button>
            </form>
        </div>
    </div>
</div>