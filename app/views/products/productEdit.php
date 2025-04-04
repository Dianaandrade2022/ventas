<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Editar un producto</h1>
        <a href="ProductsController.php?name=product_list" class="btn btn-primary">Regresar al listado</a>
    </div>
    <hr class="mb-5">
    <div class="row">
        <div class="col-lg-4 m-auto mt-3">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" id="editForm">
                <input type="hidden" name="productId" value="<?= isset($data_producto['productId']) ? $data_producto['productId'] : ''; ?>">
                <input type="hidden" name="user_id" value="<?= isset($_REQUEST["id_user"]) ? $_REQUEST["id_user"] : ''; ?>">
                <input type="hidden" name="provider_id" value="<?= isset($data_producto['id_proveedor']) ? $data_producto['id_proveedor'] : ''; ?>">
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
                    <label for="supplierId"><b>Proveedor</b></label>
                    <select name="supplierId" id="supplierId" class="form-control">
                        <?php foreach (getProviders() as $supplierItem) : ?>
                            <option value="<?= htmlspecialchars($supplierItem["id"]) ?>" <?= ($_REQUEST["provider_id"] == $supplierItem["id"]) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($supplierItem["name"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nombre_producto"><b>Nombre</b></label>
                    <input type="text" class="form-control" name="name" value="<?= isset($data_producto['nombre_producto']) ? htmlspecialchars($data_producto['nombre_producto']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="nuevo_precio"><b>Precio</b></label>
                    <input type="text" class="form-control" name="price" value="<?= isset($data_producto['precio_producto']) ? htmlspecialchars($data_producto['precio_producto']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="nueva_cantidad"><b>Cantidad</b></label>
                    <input type="text" class="form-control" name="quantity" value="<?= isset($data_producto['product_quantity']) ? htmlspecialchars($data_producto['product_quantity']) : ''; ?>">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary text-center w-100" name="edit_product" value="edit_product">Actualizar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>