<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Actualizar contraseña</h1>
        <a href="UsersController.php?name=users_list" class="btn btn-primary">Regresar al listado</a>
    </div>
    <hr class="mb-5">
    <div class="row">
        <div class="col-lg-4 m-auto mt-3">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="hidden" name="id" value="<?= $_REQUEST["id"]; ?>">
                <div id="alertContainer">
                    <?= isset($alert) ? $alert : ''; ?>
                </div>
                <div class="form-group">
                    <label for="password_actual"><b>Contraseña actual</b></label>
                    <input type="password" class="form-control" placeholder="Ingrese contraseña actual" name="password_actual">
                </div>
                <div class="form-group">
                    <label for="password_new"><b>Nueva contraseña</b></label>
                    <input type="password" class="form-control" placeholder="Ingrese la nueva contraseña" name="password_new">
                </div>
                <div class="form-group">
                    <label for="password_new_repeat"><b>Repite la nueva contraseña</b></label>
                    <input type="password" class="form-control" placeholder="Repite la nueva contraseña" name="password_new_repeat" id="password_new_repeat">
                </div>
                <button type="submit" class="btn btn-primary text-center w-100" name="password_update" value="password_update"><i class="fas fa-user-edit"></i> Actualizar contraseña</button>
            </form>
        </div>
    </div>
</div>