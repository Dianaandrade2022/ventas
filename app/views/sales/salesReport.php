<div class="container-fluid">
    <div class="panel-body text-center" style="background-color:#213a53">
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="clearfix m-5">
            <input type="hidden" name="name" value="sales_report">
            <div class="form-group">
                <h2 class="text-white my-5">REPORTE DE VENTAS</h2>
                <h4 class="form-label text-white px-10 mb-4">ELIGE UN RANGO DE FECHAS</h4>
                <div class="input-group">
                    <input type="date" class="datepicker form-control" name="from" placeholder="Desde" required>
                    <input type="date" class="datepicker form-control" name="to" placeholder="Hasta" required>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" name="generar_reporte" value="generar_reporte" class="btn btn-primary mt-4">Generar Reporte</button>
            </div>
        </form>
    </div>

    <?= $message ?? '' ?>
    <?= $range ?? '' ?>

    <table class="table table-border mt-5" id="<?= isset($_POST['generar_reporte']) ? 'table' : '' ?>">
        <thead style="background-color:#293846; color: #fff">
            <tr>
                <th class="text-center">Número de factura</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">Subtotal</th>
                <th class="text-center">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            $total_with_discount = 0;

            if (!empty($rows)) :
                foreach ($rows as $row) :
                    $total += $row['total'];
                    /* if ($_SESSION['role'] === 1 && $_SESSION['branchId'] == $row['branchId']) : */

            ?>
                    <tr>
                        <td class="text-center"><?= htmlspecialchars($row['id']); ?></td>
                        <td class="text-center px-5"><?= htmlspecialchars($row['date']); ?></td>
                        <td class="text-center"> $ <?= number_format($row['total'], 2); ?></td>
                        <td class="text-center px-5">$ <?= number_format(($row['total'] * 0.16 ) + $row['total'], 2); ?></td>
                    </tr>
                <?php
                /* endif; */
                endforeach;
            else :
                ?>
                <tr>
                    <td colspan="4" class="text-center">
                        <h2>DEBES SELECCIONAR UN RANGO DE FECHA</h2>
                    </td>
                </tr>
            <?php
            endif;
            ?>
        </tbody>
        <tfoot>
            <tr class="text-right">
                <td class="text-left pl-5" colspan="2"> <span class="pl-5"><b>Totales</b></span></td>
                <td class="text-center"><b> $ <?= number_format($total, 2) ?> </b></td>
                <td class="text-center"><b> $ <?= number_format(($total*0.16)+$total, 2) ?> </b></td>
            </tr>
        </tfoot>
    </table>
</div>