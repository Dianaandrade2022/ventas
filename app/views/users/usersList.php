<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Listado de usuarios</h1>
        <?php if ($_SESSION['role'] == 1) { ?>
            <a href="UsersController.php?name=user_new" class="btn btn-primary">Nuevo usuario</a>
        <?php } ?>
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
                            <th class="text-center">CORREO</th>
                            <th class="text-center">ROL</th>
                            <th class="text-center">Razón Social</th>
                            <?php if ($_SESSION['role'] == 1) : ?>
                                <th class="text-center">ACCIONES</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rows = getUsers();
                        foreach ($rows as $row) :
                            $branch = getNameBranchById($row['branchId']);
                            /* if ($_SESSION['role'] == 1 && $_SESSION['branchId'] == $branch['id']) : */
                        ?>
                            <tr>    
                                <td class="text-center"><?= htmlspecialchars($row['id_usuario']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['nombre_usuario']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['email']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['nombre_rol']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($branch['name'] ?? 'N/A'); ?></td>

                                <?php if ($_SESSION['role'] == 1) : ?>
                                    <td class="text-center">
                                        <div style="font-size: 12px; display:flex; flex-direction:row; justify-content: center; gap: 10px">
                                            <a href="UsersController.php?id=<?= $row['id_usuario']; ?>&branchId=<?= $row['branchId']; ?>" class="btn btn-primary"><i class='fa fa-edit'></i> Editar</a>
                                            <form action="UsersController.php?delete=<?= $row['id_usuario']; ?>" method="POST" class="confirmar d-inline">
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