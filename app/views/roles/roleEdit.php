<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Editar rol</h1>
        <a href="RolesController.php?name=roles_list" class="btn btn-primary">Regresar al listado</a>
    </div>
    <hr class="mb-5">
    <div class="row">
        <div class="col-lg-4 m-auto mt-3">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="hidden" name="id" value="<?= $data_role["id"]; ?>">
                <div id="alertContainer">
                    <?php echo isset($alert) ? $alert : ''; ?>
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
                    <input type="text" placeholder="Ingrese Nombre" name="name" class="form-control" value="<?= $data_role["name"]; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="description"><b>Descripción</b></label>
                    <textarea name="description" class="form-control" rows="7" style="resize: none;"><?= htmlspecialchars($data_role["description"]); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary text-center w-100" name="edit_role" value="edit_role"><i class="fas fa-user-edit"></i> Editar role</button>
            </form>
        </div>
    </div>
</div>