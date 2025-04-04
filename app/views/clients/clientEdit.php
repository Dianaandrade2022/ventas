<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Editar un cliente</h1>
        <a href="ClientsController.php?name=clients_list" class="btn btn-primary">Regresar al listado</a>
    </div>
    <hr class="mb-5">
    <div class="row">
        <div class="col-lg-4 m-auto mt-3">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="hidden" name="id" value="<?= $data_cliente["id"]; ?>">
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
                    <label for="dni">DNI</label>
                    <input type="number" placeholder="Ingrese dni" name="dni" class="form-control" value="<?= $data_cliente["dni"]; ?>">
                </div>
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" placeholder="Ingrese Nombre" name="name" class="form-control" value="<?= $data_cliente["name"]; ?>">
                </div>
                <div class="form-group">
                    <label for="phoneNumber">Teléfono</label>
                    <input type="number" placeholder="Ingrese Teléfono" name="phoneNumber" class="form-control" value="<?= $data_cliente["phoneNumber"]; ?>">
                </div>
                <div class="form-group">
                    <label for="address">Dirección</label>
                    <input type="text" placeholder="Ingrese Dirección" name="address" class="form-control" value="<?= $data_cliente["address"]; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Correo</label>
                    <input type="email" placeholder="Ingrese Correo" name="email" class="form-control" value="<?= $data_cliente["email"]; ?>">
                </div>
                <button type="submit" class="btn btn-primary text-center w-100" name="edit_client" value="edit_client"><i class="fas fa-user-edit"></i> Editar Cliente</button>
            </form>
        </div>
    </div>
</div>