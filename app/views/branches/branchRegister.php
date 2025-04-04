<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Agregar nueva sucursal</h1>
        <a href="BranchesController.php?name=branches_list" class="btn btn-primary">Regresar al listado</a>
    </div>
    <hr class="mb-5">
    <div class="row">
        <div class="col-lg-4 m-auto">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="hidden" name="name" value="branch_new">
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
                    <label for="cuit"><b>RFC</b></label>
                    <input type="text" placeholder="Ingrese el RFC" name="cuit" class="form-control">
                </div>
                <div class="form-group">
                    <label for="name"><b>Nombre</b></label>
                    <input type="text" placeholder="Ingrese el nombre" name="name" class="form-control">
                </div>
                <div class="form-group">
                    <label for="email"><b>Correo de contacto</b></label>
                    <input type="text" placeholder="Ingrese el email" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label for="phoneNumber"><b>Telefono de contacto</b></label>
                    <input type="text" placeholder="Ingrese el teléfono" name="phoneNumber" class="form-control">
                </div>
                <div class="form-group">
                    <label for="address"><b>Dirección</b></label>
                    <input type="text" placeholder="Ingrese la dirección" name="address" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary text-center w-100" name="add_branch" value="add_branch">Guardar sucursal</button>
            </form>
        </div>
    </div>
</div>