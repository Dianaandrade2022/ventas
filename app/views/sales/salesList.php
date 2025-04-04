<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Listado de proyectos</h1>
        <a href="SalesController.php?name=sale_new" class="btn btn-primary">Nuevo proyecto</a>
    </div>
    <hr class="mb-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="table">
                    <thead>
                        <tr style="background-color:#213a53; color: #fff">
                            <th class="text-center">#</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Vendedor</th>
                            <th class="text-center">Rentabilidad</th>
                            <th class="text-center">Total ($)</th>
                            <th class="text-center">Estatus</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php
    $rows = getSales(); // Obtén todas las ventas
    foreach ($rows as $row) :
        // Mostrar todas las ventas si el rol es 1 (admin)
        // Mostrar solo las ventas del usuario si el rol no es 1
        if ($_SESSION['role'] === 1 || $row['user_id'] == $_SESSION['user_id']) :
    ?>
        <tr>
            <td class="text-center" ><?= htmlspecialchars($row['id'] ?? '') ?></td>
            <td class="text-center"><?= htmlspecialchars($row['date'] ?? '') ?></td>
            <td class="text-center"><?= htmlspecialchars($row['user'] ?? '') ?></td>
            <td class="text-center"><?= number_format($row['rentabilidad'], 2) ?>%</td>
            <td class="text-center">$ <?= htmlspecialchars(number_format($row['total'] ?? 0, 2)) ?></td>
            <td class="text-center">
                <?php if ($row['status'] === 'activo') : ?>
                    <span class="badge badge-success">Activo</span>
                <?php else : ?>
                    <span class="badge badge-danger">Concluido</span>
                <?php endif; ?>
            </td>
            <td class="text-center">
                <div class="d-flex flex-direction-row justify-content-center">
                    <button type="button" class="btn btn-primary download_invoice" totalWithDiscount="<?= htmlspecialchars($row['totalWithDiscount'] ?? 0) ?>" clientId="<?= htmlspecialchars($row['clientId'] ?? '') ?>" invoiceId="<?= htmlspecialchars($row['id'] ?? '') ?>">Descargar</button>

                    <?php if ($_SESSION['role'] === 1) : ?>
                        <button type="button" class="btn btn-secondary ml-2" data-toggle="modal" data-target="#editStatusModal" data-id="<?= htmlspecialchars($row['id'] ?? '') ?>" data-status="<?= htmlspecialchars($row['status'] ?? '') ?>" <?= $row['status'] === 'concluido' ? 'disabled' : '' ?>>Editar</button>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    <?php
        endif;
    endforeach;
    ?>
</tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar el estatus -->
<div class="modal fade" id="editStatusModal" tabindex="-1" role="dialog" aria-labelledby="editStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStatusModalLabel">Editar Estatus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <for method="POST">
                    <input type="hidden" name="action" value="edit_status">
                    <input type="hidden" value="<?= htmlspecialchars($row['id'] ?? '') ?>" name="saleId" id="saleId">
                    <div class="form-group">
                        <label for="status">Estatus</label>
                        <select name="status" id="status" class="form-control">
                            <option value="activo">Activo</option>
                            <option value="concluido">Concluido</option>
                        </select>
                    </div>
                    <button id="updatestatus" class="btn btn-primary">Guardar cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>
