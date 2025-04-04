<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Agregar un nuevo producto</h1>
        <a href="ProductsController.php?name=product_list" class="btn btn-primary">Regresar al listado</a>
    </div>
    <hr class="mb-5">

    <div class="row">
        <div class="col-lg-4 m-auto">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="hidden" name="productId" value="<?= isset($data_producto['productId']) ? $data_producto['productId'] : ''; ?>">
                <input type="hidden" name="user_id" value="<?= isset($_REQUEST["id_user"]) ? $_REQUEST["id_user"] : ''; ?>">
                <input type="hidden" name="provider_id" value="<?= isset($data_producto['id_proveedor']) ? $data_producto['id_proveedor'] : ''; ?>">
                <div id="alertContainer">
                    <?= isset($alert) ? $alert : ''; ?>
                </div>
                <div class="form-group">
                    <label for="storeId"><b>Selecciona una tienda</b></label>
                    <select id="storeId" name="storeId" class="form-control">
                        <?php foreach (getStores() as $row) : ?>
                            <option value="<?= htmlspecialchars($row['id']); ?>"><?= htmlspecialchars($row['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><b>Selecciona la sucursal</b></label>
                    <select name="branchId" id="branchId" class="form-control">
                        <?php foreach ($branches as $branch) :
                             if ($_SESSION['branchId'] == $branch["id"]) : 
                        ?>
                            <option value="<?= htmlspecialchars($branch["id"]) ?>">
                                <?= htmlspecialchars($branch["name"]) ?>
                            </option>
                        <?php  endif; 
                        endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="supplier"><b>Proveedor</b></label>
                    <select id="supplier" name="supplier" class="form-control">
                        <?php foreach (getProviders() as $row) : ?>
                            <option value="<?= htmlspecialchars($row['id']); ?>"><?= htmlspecialchars($row['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="product"><b>Nombre</b></label>
                    <input type="text" placeholder="Ingrese el nombre del producto" name="name" class="form-control">
                </div>
                <div class="form-group">
                    <label for="price"><b>Precio</b></label>
                    <input type="text" placeholder="Ingrese el precio del producto" name="price" class="form-control">
                </div>
                <div class="form-group">
                    <label for="quantity"><b>Cantidad</b></label>
                    <input type="number" placeholder="Ingrese la cantidad del producto" name="quantity" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary text-center w-100" name="add_product" value="add_product">Guardar Producto</button>

            </form>
        </div>
    </div>
</div>