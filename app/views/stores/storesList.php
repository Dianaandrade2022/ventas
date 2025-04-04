<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Listado de tiendas</h1>
        <a href="StoresController.php?name=store_new" class="btn btn-primary">Nueva tienda</a>
    </div>
    <hr class="mb-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="table">
                    <thead>
                        <tr style="background-color:#4B75F2; color: #fff">
                            <th class="text-center">#</th>
                            <th class="text-center">RFC</th>
                            <th class="text-center">NOMBRE DE FANTASIA</th>
                            <th class="text-center">NOMBRE (RAZÓN SOCIAL)</th>
                            <th class="text-center">TELEFONO</th>
                            <th class="text-center">EMAIL</th>
                            <th class="text-center">DIRECCION</th>
                            <th class="text-center">IVA</th>
                            <th class="text-center">SUCURSAL</th>
                            <?php if ($_SESSION['role'] == 1) : ?>
                                <th class="text-center">ACCIONES</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rows = getStores();
                        $branchId = getBranches();

                        if ($rows > 0) :
                            foreach ($rows as $row) :
                                /* foreach ($branchId as $branch) : */
                                /* if ($_SESSION['role'] == 1 && $_SESSION['branchId'] == $branch['id']) : */
                        ?>
                                <tr>
                                    <td class="text-center"><?= $row['id']; ?></td>
                                    <td class="text-center"><?= $row['rfc']; ?></td>
                                    <td class="text-center"><?= $row['name']; ?></td>
                                    <td class="text-center"><?= $row['tradeName']; ?></td>
                                    <td class="text-center"><?= $row['phoneNumber']; ?></td>
                                    <td class="text-center"><?= $row['email']; ?></td>
                                    <td class="text-center"><?= $row['address']; ?></td>
                                    <td class="text-center"><?= $row['iva']; ?></td>
                                    <td class="text-center"><?= $row["name"]; ?></td>
                                    <?php
                                    if ($_SESSION['role'] == 1) : ?>
                                        <td>
                                            <div style="font-size: 12px; display:flex; flex-direction:row; justify-content: center;">
                                                <a href="StoresController.php?id=<?= $row['id']; ?>&user_id=<?= $_SESSION['user_id']; ?>" class="btn btn-primary mx-2">Editar <i class='fa fa-edit'></i></a>
                                                <form action="StoresController.php?delete=<?= $row['id']; ?>" method="POST" class="confirmar fa-trash">
                                                    <button class="btn btn-danger" type="submit">Eliminar <i class='fa fa-trash-o'></i> </button>
                                                </form>
                                            </div>
                                        </td>
                                    <?php endif ?>
                                </tr>
                            <?php
                            /* endif; */
                            /* endforeach; */
                            endforeach; ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!--                         <?php
                                /* $rows = getStores();
                        $branchId = getBranches();

                        // Verificar si $branchId es un array válido y no está vacío
                        if (is_array($branchId) && !empty($branchId)) :
                            foreach ($rows as $row) :
                                // Verificar si la sesión tiene rol 1 y la sucursal actual coincide con alguna de $branchId
                                $matchedBranch = false;
                                foreach ($branchId as $branch) {
                                    if ($_SESSION['role'] == 1 && $_SESSION['branchId'] == $branch["id"]) {
                                        $matchedBranch = true;
                                        break; // Salir del bucle si se encuentra la coincidencia
                                    }
                                }

                                if ($_SESSION['role'] == 1 && $matchedBranch) :
                        ?>
                                    <tr>
                                        <td class="text-center"><?= $row['id']; ?></td>
                                        <td class="text-center"><?= $row['cuit_cuil']; ?></td>
                                        <td class="text-center"><?= $row['name']; ?></td>
                                        <td class="text-center"><?= $row['tradeName']; ?></td>
                                        <td class="text-center"><?= $row['phoneNumber']; ?></td>
                                        <td class="text-center"><?= $row['email']; ?></td>
                                        <td class="text-center"><?= $row['address']; ?></td>
                                        <td class="text-center"><?= $row['iva']; ?></td>
                                        <td class="text-center"><?php echo getBranchById($branch['id'])["name"]; ?></td>
                                        <?php if ($_SESSION['role'] == 1) : ?>
                                            <td>
                                                <div style="font-size: 12px; display:flex; flex-direction:row; justify-content: center;">
                                                    <a href="StoresController.php?id=<?= $row['id']; ?>&id_user=<?= $_SESSION['id_user']; ?>" class="btn btn-primary mx-2">Editar <i class='fa fa-edit'></i></a>
                                                    <form action="StoresController.php?delete=<?= $row['id']; ?>" method="POST" class="confirmar fa-trash">
                                                        <button class="btn btn-danger" type="submit">Eliminar <i class='fa fa-trash-o'></i> </button>
                                                    </form>
                                                </div>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                        <?php
                                endif;
                            endforeach;
                        endif; */
                                ?> -->