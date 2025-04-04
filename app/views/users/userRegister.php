<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Agregar un nuevo usuario</h1>
        <a href="UsersController.php?name=users_list" class="btn btn-primary">Regresar al listado</a>
    </div>
    <hr class="mb-5">
    <div class="row">
        <div class="col-lg-4 m-auto">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="hidden" name="name" value="user_new">
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
                    <label><b>Selecciona el rol del usuario</b></label>
                    <select name="role" id="role" class="form-control">
                        <?php foreach ($roles as $role) : ?>
                            <option value="<?= htmlspecialchars($role["id"]) ?>">
                                <?= htmlspecialchars($role["name"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name"><b>Nombre</b></label>
                    <input type="text" class="form-control" placeholder="Ingrese el nombre" name="name">
                </div>
                <div class="form-group">
                    <label for="email"><b>Correo</b></label>
                    <input type="email" class="form-control" placeholder="Ingrese email" name="email">
                </div>
                <div class="form-group">
                    <label for="password"><b>Contraseña</b></label>
                    <input type="password" class="form-control" placeholder="Ingrese contraseña" name="password">
                </div>
                <button type="submit" class="btn btn-primary text-center w-100" name="add_user" value="add_user">Guardar Usuario</button>
            </form>
        </div>
    </div>
</div>