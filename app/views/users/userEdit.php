<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Editar un usuario</h1>
        <a href="UsersController.php?name=users_list" class="btn btn-primary">Regresar al listado</a>
    </div>
    <hr class="mb-5">
    <div class="row">
        <div class="col-lg-4 m-auto mt-3">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" id="editForm">
                <input type="hidden" name="id" value="<?= htmlspecialchars($data_user["id"]); ?>">
                <div id="alertContainer">
                    <?= isset($alert) ? $alert : ''; ?>
                </div>
                <div class="form-group">
                    <label><b>Selecciona la tienda</b></label>
                    <select name="storeId" id="storeId" class="form-control">
                        <?php foreach ($stores as $store) :
                            /*  if ($_SESSION['storeId'] == $store["id"]) : */
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
                    <label for="role"><b>Rol</b></label>
                    <select name="role" id="role" class="form-control" disabled>
                        <?php foreach (getRoles() as $roleItem) : ?>
                            <option value="<?= htmlspecialchars($roleItem["id"]) ?>" <?php if ($data_user["role"] == $roleItem["id"]) echo 'selected'; ?>>
                                <?= htmlspecialchars($roleItem["name"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name"><b>Nombre</b></label>
                    <input type="text" placeholder="Ingrese nombre" class="form-control" name="name" id="name" value="<?= htmlspecialchars($data_user["name"]); ?>">
                </div>
                <div class="form-group">
                    <label for="email"><b>Correo</b></label>
                    <input type="text" placeholder="Ingrese correo" class="form-control" name="email" id="email" value="<?= htmlspecialchars($data_user["email"]); ?>">
                </div>
                <button type="submit" class="btn btn-primary text-center w-100" name="edit_user" value="edit_user"><i class="fas fa-user-edit"></i> Editar Usuario</button>
            </form>
        </div>
    </div>
</div>