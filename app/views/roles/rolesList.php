<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Listado de roles</h1>
        <a href="RolesController.php?name=role_new" class="btn btn-primary">Nuevo rol</a>
    </div>
    <hr class="mb-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="table">
                    <thead>
                        <tr style="background-color:#4B75F2; color: #fff">
                            <th class="text-center">#</th>
                            <th class="text-center">NOMBRE</th>
                            <th class="text-center">DESCRIPCIÓN</th>
                            <?php if ($_SESSION['role'] == 1) : ?>
                                <th class="text-center">ACCIONES</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rows = getRoles();
                        foreach ($rows as $row) :
                            /* if ($_SESSION['role'] == 1 && $_SESSION['branchId'] == $row['branchId']) : */
                        ?>
                            <tr>
                                <td class="text-center"><?= $row['id']; ?></td>
                                <td class="text-center"><?= $row['name']; ?></td>
                                <td class="text-center"><?= $row['description']; ?></td>
                                <?php if ($_SESSION['role'] == 1) : ?>
                                    <td class="text-center">
                                        <div style="font-size: 12px; display:flex; flex-direction:row; justify-content: center; gap: 10px">
                                            <a href="RolesController.php?id=<?= $row['id']; ?>" class="btn btn-primary"><i class='fa fa-edit'> Editar</i></a>
                                            <form action="RolesController.php?delete=<?= $row['id']; ?>" method="POST" class="confirmar d-inline">
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
</div>