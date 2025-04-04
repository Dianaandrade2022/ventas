<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Listado de cotizaciones</h1>
        <a href="EstimatesController.php?name=estimate_new" class="btn btn-primary">Nueva cotizacion</a>
    </div>
    <hr class="mb-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="table">
                    <thead style="background-color:#4B75F2; color: #fff">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Total Con Descuento</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rows = getStimates();
                        foreach ($rows as $row) :
                            // Mostrar todos los registros si el rol es 1
                            // Si el rol es diferente de 1, mostrar solo los registros que coincidan con el branchId
                            /* if ($_SESSION['role'] === 1 && $_SESSION['branchId'] == $row['branchId']) : */
                        ?>
                            <tr>
                                <td class="text-center"><?= htmlspecialchars($row['id']) ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['date']) ?></td>
                                <td class="text-center">$ <?= htmlspecialchars(number_format($row['total'], 2)) ?></td>
                                <td class="text-center">$ <?= htmlspecialchars(number_format($row['totalWithDiscount'], 2)) ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="btn btn-primary download_estimate" totalWithDiscount="<?= htmlspecialchars($row['0']) ?>" clientId="<?= htmlspecialchars($row['clientId']) ?>" estimateId="<?= htmlspecialchars($row['id']) ?>">Descargar</button>
                                    </div>

                                </td>
                            </tr>
                        <?php
                        /* endif; */
                        endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>