<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Agregar una nueva tienda</h1>
        <a href="StoresController.php?name=stores_list" class="btn btn-primary">Regresar al listado</a>
    </div>
    <hr class="mb-5">
    <div class="row">
        <div class="col-lg-4 m-auto">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="hidden" name="name" value="store_new">
                <div id="alertContainer">
                    <?= isset($alert) ? $alert : ''; ?>
                </div>
                <div class="form-group">
                    <label for="rfc"><b>RFC</b></label>
                    <input type="text" placeholder="Ingrese el RFC" name="rfc" class="form-control">
                </div>
                <div class="form-group">
                    <label for="name"><b>Nombre</b></label>
                    <input type="text" placeholder="Ingrese nombre" name="name" class="form-control">
                </div>
                <div class="form-group">
                    <label for="phoneNumber"><b>Teléfono</b></label>
                    <input type="number" placeholder="Ingrese teléfono" name="phoneNumber" class="form-control">
                </div>
                <div class="form-group">
                    <label for="email"><b>Correo</b></label>
                    <input type="email" class="form-control" placeholder="Ingrese email" name="email">
                </div>
                <div class="form-group">
                    <label for="address"><b>Dirección</b></label>
                    <input type="text" placeholder="Ingrese dirección" name="address" class="form-control">
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary text-center w-100" name="add_store" value="add_store">Guardar Tienda</button>
                </div>
            </form>
        </div>
    </div>
</div>