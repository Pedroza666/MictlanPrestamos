<nav class="navbar navbar-expand-lg main-navbar sticky">
    <div class="form-inline me-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-bs-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                    <i data-feather="maximize"></i>
                </a></li>
        </ul>
    </div>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown"><a href="#" data-bs-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image" src="<?= base_url('assets/img/user.png'); ?>" class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
                <div class="dropdown-title">Hello Sarah Smith</div>
                <a href="#" class="dropdown-item has-icon"> <i class="far
										fa-user"></i> Perfil
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?php echo base_url('usuarios/logout'); ?>" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>
                    Cerrar Sesión
                </a>
            </div>
        </li>
    </ul>
</nav>

<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="<?php echo base_url() ?>">
                <img alt="image" src="<?= base_url('assets/img/logo.png'); ?>" class="header-logo" />
                <span class="logo-name"> Sistema Administrativo </span>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Menu</li>
            <li class="dropdown active">
                <a href="index.html" class="nav-link"><i class="fa-solid fa-chart-pie mx-2"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i class="fa-solid fa-gear mx-2"></i><span>Administración</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url('usuarios'); ?>">Usuarios</a></li>
                    <li><a class="nav-link" href="<?php echo base_url('admin'); ?>">Configuración</a></li>
                    <li><a class="nav-link" href="widget-data.html">Backup</a></li>
                </ul>
            </li>
            <li>
                <a href="#" class="nav-link">
                <i class="fa-solid fa-user-lock mx-2"></i>
                
                    <span> Roles</span></a>

            </li>
            <li>
                <a href="<?php echo base_url('clientes'); ?>" class="nav-link">
                    <i class="fa-solid fa-users mx-2"></i>
                    <span>Clientes</span></a>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i class="fa-solid fa-wallet mx-2"></i><span>Prestamos</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url('prestamos'); ?>">Prestamo</a></li>
                    <li><a class="nav-link" href="<?php echo base_url('prestamos/historial'); ?>">Historial</a></li>
                </ul>
            </li>
            <li>
                <a href="<?php echo base_url('cajas'); ?>" class="nav-link">
                <i class="fa-solid fa-sack-dollar mx-2"></i>
                    <span>Cajas</span></a>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i class="fa-solid fa-file-pdf mx-2"></i><span>Reportes PDF</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url('usuarios'); ?>">Actual</a></li>
                    <li><a class="nav-link" href="<?php echo base_url('admin'); ?>">Ultimos 7 días</a></li>
                    <li><a class="nav-link" href="<?php echo base_url('admin'); ?>">Ultimos 30 días</a></li>
                    <li><a class="nav-link" href="<?php echo base_url('admin'); ?>">Mes Anterior</a></li>
                    <li><a class="nav-link" href="<?php echo base_url('admin'); ?>">Mes Actual</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i class="fa-solid fa-file-excel mx-2"></i><span>Reportes EXCEL</span></a>
                <ul class="dropdown-menu">
                <li><a class="nav-link" href="<?php echo base_url('usuarios'); ?>">Actual</a></li>
                    <li><a class="nav-link" href="<?php echo base_url('admin'); ?>">Ultimos 7 días</a></li>
                    <li><a class="nav-link" href="<?php echo base_url('admin'); ?>">Ultimos 30 días</a></li>
                    <li><a class="nav-link" href="<?php echo base_url('admin'); ?>">Mes Anterior</a></li>
                    <li><a class="nav-link" href="<?php echo base_url('admin'); ?>">Mes Actual</a></li>
                </ul>
            </li>


        </ul>
    </aside>
</div>