<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Aumentar Productos De Manera Masiva</h1>
        <a href="ProductsController.php?name=product_list" class="btn btn-primary">Regresar a productos</a>
    </div>
    <hr class="mb-5">
    <div class="row mt-3">
        <div class="m-auto">
            <h1 class="mb-5">⚠️ AUMENTARAS TODOS LOS PRODUCTOS EN ESTA SECCIÓN ⚠️</h1>
            <form class="d-flex flex-column text-center fw-5 pb-5" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <h3 class="p-3">POR FAVOR, INTRODUCE EL PROCENTAJE A AUMENTAR</h3>
                <input class="form-control text-center mb-3 w-50 mx-auto rounded-5" type="number" placeholder="0%" name="porcentaje" required>
                <!-- <input class="form-control text-center mb-3 w-50 mx-auto rounded-5" type="number" value="<?php echo $storeId = $_SESSION['storeId']; ?>" required> -->
                <button class="btn btn-primary mt-3 w-25 mx-auto" type="submit" name="name" value="product_increment_price_massive">AUMENTAR</button>
            </form>
            <?php
            if (isset($alert) and $alert != "") {
                echo "<div class='alert alert-primary' role='alert'>$alert</div>";
            }
            ?>
        </div>
    </div>
</div>