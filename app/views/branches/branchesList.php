<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Listado de sucursales</h1>
        <a href="BranchesController.php?name=branch_new" class="btn btn-primary">Nueva sucursal</a>
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
                            <th class="text-center">NOMBRE</th>
                            <th class="text-center">TELEFONO</th>
                            <th class="text-center">DIRECCIÓN</th>
                            <th class="text-center">CORREO</th>
                            <?php if ($_SESSION['role'] == 1) : ?>
                                <th class="text-center">ACCIONES</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rows = getBranches();
                        foreach ($rows as $row) :
                            /* if ($_SESSION['role'] == 1 && $_SESSION['branchId'] == $row['id']) : */

                        ?>
                            <tr>
                                <td class="text-center"><?= $row['id']; ?></td>
                                <td class="text-center"><?= $row['RFC']; ?></td>
                                <td class="text-center"><?= $row['name']; ?></td>
                                <td class="text-center"><?= $row['phoneNumber']; ?></td>
                                <td class="text-center"><?= $row['address']; ?></td>
                                <td class="text-center"><?= $row['email']; ?></td>
                                <?php if ($_SESSION['role'] == 1) : ?>
                                    <td>
                                        <div style="font-size: 12px; display:flex; flex-direction:row; justify-content: center; gap: 10px">

                                            <a href="BranchesController.php?id=<?= $row['id']; ?>" class="btn btn-primary"><i class='fa fa-edit'> Editar</i></a>
                                            <form action="BranchesController.php?delete=<?= $row['id']; ?>" method="POST" class="confirmar d-inline">
                                                <button class="btn btn-danger" type="submit"><i class='fa fa-trash-o'></i> Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php
                        /* endif; */
                        endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>