<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Aumentar Productos Por Proveedor</h1>
        <a href="ProductsController.php?name=product_list" class="btn btn-primary">Regresar a productos</a>
    </div>
    <hr class="mb-5">
    <div class="row mt-3">
        <div class="col-lg-6 m-auto text-center">
            <form class="d-flex flex-column text-center fw-5 pb-5" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <div class="form-group">
                    <label>Selecciona el Proveedor</label>
                    <select id="aumentar_por_proveedor" name="aumentar_por_proveedor" class="form-control">
                        <?php foreach (getProviders() as $row) : ?>
                            <option value="<?= htmlspecialchars($row['id']); ?>"><?= htmlspecialchars($row['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <label class="py-3">POR FAVOR, INTRODUCE EL PORCENTAJE A AUMENTAR</label>
                <input class="text-center p-2 w-50 mx-auto" type="number" placeholder="%" name="porcentaje" required>
                <button class="btn btn-primary mt-3 w-25 mx-auto" type="submit" name="name" value="product_increment_price_for_supplier">AUMENTAR</button>
            </form>

            <?php if (isset($alert["alert"]) and $alert["alert"] !== '') : ?>
                <div class="alert alert-info mt-3"><?= $alert["alert"] ?></div>
            <?php elseif (isset($alert["porcentaje"]) and $alert["porcentaje"] != '' and isset($alert["nombre_proveedor"]) and $alert["nombre_proveedor"] != '') : ?>
                <div class="alert alert-success mt-3">
                    Se aplicó un aumento del <?= htmlspecialchars($alert["porcentaje"]) ?>% a todos los productos del supplier <?= htmlspecialchars($alert["nombre_proveedor"]) ?> satisfactoriamente.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>