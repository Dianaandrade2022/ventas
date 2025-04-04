<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>Listado de clientes</h1>
        <a href="ClientsController.php?name=client_new" class="btn btn-primary">Nuevo cliente</a>
    </div>
    <hr class="mb-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="table">
                    <thead>
                        <tr style="background-color:#4B75F2; color: #fff">
                            <th class="text-center">#</th>
                            <th class="text-center">ID</th>
                            <th class="text-center">NOMBRE</th>
                            <th class="text-center">TELÉFONO</th>
                            <th class="text-center">DIRECCIÓN</th>
                            <th class="text-center">CORREO</th>
                            <?php if ($_SESSION['role'] == 1) : ?>
                                <th class="text-center">ACCIONES</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rows = getClients();
                        foreach ($rows as $row) :
                            /* if ($_SESSION['role'] == 1 && $_SESSION['branchId'] == $row['branchId']) : */
                        ?>
                            <tr>
                                <td class="text-center"><?= $row['id']; ?></td>
                                <td class="text-center"><?= $row['dni']; ?></td>
                                <td class="text-center"><?= $row['name']; ?></td>
                                <td class="text-center"><?= $row['phoneNumber']; ?></td>
                                <td class="text-center"><?= $row['address']; ?></td>
                                <td class="text-center"><?= $row['email']; ?></td>
                                <?php if ($_SESSION['role'] == 1) : ?>
                                    <td>
                                        <div style="font-size: 12px; display:flex; flex-direction:row; justify-content: center; gap: 10px">
                                            <a href="ClientsController.php?id=<?= $row['id']; ?>&user_id=<?= $_SESSION['user_id']; ?>" class="btn btn-primary"><i class='fa fa-edit'> Editar</i></a>
                                            <a href="ClientsController.php?delete=<?= $row['id']; ?>" class="btn btn-danger"><i class='fa fa-trash-o'></i> Eliminar</a>
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