<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <img alt="image" class="rounded-circle" src="../../public/img/user.png" />
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="block m-t-xs text-muted"><?= $_SESSION["email"] ?></span>

                        <span class="text-muted text-xs block"><?= $_SESSION["name"] ?> <b class="caret"></b></span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">

                        <li>
                            <a class="dropdown-item" href="UsersController.php?id=<?= $_SESSION["user_id"] ?>">Perfil</a>
                            <a class="dropdown-item" href="UsersController.php?id=<?= $_SESSION["user_id"] ?>&password_update">Cambiar contraseña</a>
                        </li>
                    </ul>
                </div>
            </li>   
            <?php if ($_SESSION['role'] === 1) { ?>
                <li>
                    <a href="DashboardController.php"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
                </li>
            <?php } ?>
            <li>
                <a href="#"><i class="fa fa-dollar"></i> <span class="nav-label">Proyectos</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li><a href="SalesController.php?name=sale_new">Nuevo</a></li>
                    <li><a href="SalesController.php?name=sales_list">Listado</a></li>
                    <?php if ($_SESSION['role'] === 1) { ?>
                        <li><a href="SalesController.php?name=sales_report">Reporte</a></li>
                    <?php } ?>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-bar-chart-o"></i> <span class="nav-label"> Cotizaciones </span><span class="fa arrow"></a>
                <ul class="nav nav-second-level collapse">
                    <li><a href="EstimatesController.php?name=estimate_new">Nuevo</a></li>
                    <li><a href="EstimatesController.php?name=estimates_list">Listado</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-truck"></i> <span class="nav-label">Proveedores</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li><a href="SuppliersController.php?name=supplier_new">Nuevo</a></li>
                    <li><a href="SuppliersController.php?name=suppliers_list">Listado</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-edit"></i> <span class="nav-label">Productos</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li><a href="ProductsController.php?name=product_new">Nuevo</a></li>
                    <li><a href="ProductsController.php?name=product_list">Listado</a></li>
                    <li><a href="ProductsController.php?name=product_increment_price_massive">Aumento masivo de precio</a></li>
                    <li><a href="ProductsController.php?name=product_increment_price_for_supplier">Aumento por proveedor</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-users"></i> <span class="nav-label">Clientes</span><span class="fa arrow"></a>
                <ul class="nav nav-second-level collapse">
                    <li><a href="ClientsController.php?name=client_new">Nuevo</a></li>
                    <li><a href="ClientsController.php?name=clients_list">Listado</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-address-card-o"></i> <span class="nav-label">Roles</span><span class="fa arrow"></a>
                <ul class="nav nav-second-level collapse">
                    <li><a href="RolesController.php?name=role_new">Nuevo</a></li>
                    <li><a href="RolesController.php?name=roles_list">Listado</a></li>
                </ul>
            </li>
            <?php if ($_SESSION['role'] === 1) { ?>
                <li>
                    <a href="#"><i class="fa fa-child"></i> <span class="nav-label">Usuarios</span><span class="fa arrow"></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="UsersController.php?name=user_new">Nuevo</a></li>
                        <li><a href="UsersController.php?name=users_list">Listado</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-building"></i> <span class="nav-label">Sucursales</span><span class="fa arrow"></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="BranchesController.php?name=branch_new">Nueva</a></li>
                        <li><a href="BranchesController.php?name=branches_list">Listado</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-cogs" aria-hidden="true"></i> <span class="nav-label">Tiendas</span><span class="fa arrow"></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="StoresController.php?name=store_new">Nueva</a></li>
                        <li><a href="StoresController.php?name=stores_list">Listado</a></li>
                    </ul>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>

<div id="page-wrapper" class="gray-bg pb-3">
    <div class="row border-bottom">
        <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="#"><i class="fa fa-bars"></i> </a>
            </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <!-- <a href="../../index.php?page=login"> <i class="fa fa-sign-out"></i> Log out </a> -->
                    <a href="../views/logout.php"> <i class="fa fa-sign-out"></i> Salir </a>
                </li>
            </ul>
        </nav>
    </div>