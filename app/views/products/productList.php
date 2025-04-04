<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Listado de productos</h1>
        <a href="ProductsController.php?name=product_new" class="btn btn-primary">Nuevo producto</a>
    </div>
    <hr class="mb-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="table">
                    <thead>
                        <tr style="background-color:#4B75F2; color: #fff">
                            <th class="text-center">ID</th>
                            <th class="text-center">NOMBRE</th>
                            <th class="text-center">PROVEEDOR</th>
                            <th class="text-center">PRECIO</th>
                            <th class="text-center">DISPONIBILIDAD</th>
                            <?php if ($_SESSION['role'] == 1) { ?>
                                <th class="text-center">ACCIONES</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $products = getProducts(); ?>
                        <?php foreach ($products as $data) : ?>
                            <?php /* if (isset($data['branchId']) && $_SESSION['role'] == 1 && $_SESSION['branchId'] == $data['branchId']) : */ ?>
                            <tr>
                                <td class="text-center"><?= htmlspecialchars($data['id']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($data['name']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($data['supplier']); ?></td>
                                <td class="text-center"><?= number_format(htmlspecialchars($data['price']), 2); ?></td>
                                <td class="text-center"><?= htmlspecialchars($data['stock']); ?></td>
                                <?php if ($_SESSION['role'] == 1) : ?>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <a href="ProductsController.php?id=<?= $data['id']; ?>&user_id=<?= $_SESSION['user_id']; ?>&provider_id=<?= $data['supplier']; ?>" class="btn btn-primary mx-2">Editar <i class='fa fa-edit'></i></a>
                                            <form action="ProductsController.php?id=<?= htmlspecialchars($data['id']); ?>" method="POST" class="confirmar d-inline">
                                                    
                                            </form>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                            <?php /* endif; */ ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>