<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Editar tienda</h1>
        <a href="StoresController.php?name=stores_list" class="btn btn-primary">Regresar al listado</a>
    </div>
    <hr class="mb-5">
    <div class="row">
        <div class="col-lg-4 m-auto mt-3">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="hidden" name="id" value="<?= $data_store["id"]; ?>">
                <div id="alertContainer">
                    <?= isset($alert) ? $alert : ''; ?>
                </div>
                <div class="form-group">
                    <label for="cuit_cuil"><b>CUIT / CUIL</b></label>
                    <input type="text" placeholder="Ingrese RFC" name="rfc" class="form-control" value="<?= $data_store["rfc"]; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="name"><b>Nombre de fantasia</b></label>
                    <input type="text" placeholder="Ingrese nombre de fantasia" name="name" class="form-control" value="<?= $data_store["name"]; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="tradeName"><b>Nombre (razón social)</b></label>
                    <input type="text" placeholder="Ingrese Nombre" name="tradeName" class="form-control" value="<?= $data_store["tradeName"]; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="phoneNumber"><b>Teléfono</b></label>
                    <input type="number" placeholder="Ingrese Teléfono" name="phoneNumber" class="form-control" value="<?= $data_store["phoneNumber"]; ?>">
                </div>
                <div class="form-group">
                    <label for="email"><b>Email</b></label>
                    <input type="email" placeholder="Ingrese Email" name="email" class="form-control" value="<?= $data_store["email"]; ?>">
                </div>
                <div class="form-group">
                    <label for="address"><b>Dirección</b></label>
                    <input type="text" placeholder="Ingrese Direccion" name="address" class="form-control" value="<?= $data_store["address"]; ?>">
                </div>
                <div class="form-group">
                    <label for="iva"><b>IVA</b></label>
                    <input type="number" placeholder="Ingrese iva" name="iva" class="form-control" value="<?= $data_store["iva"]; ?>">
                </div>
                <button type="submit" class="btn btn-primary text-center w-100" name="edit_store" value="edit_store"><i class="fas fa-user-edit"></i> Editar Tienda</button>
            </form>
        </div>
    </div>
</div>