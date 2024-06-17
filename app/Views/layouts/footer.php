<?php
// Realizar la consulta a la base de datos para obtener el nombre
$adminModel = new \App\Models\AdminModel();
$adminData = $adminModel->first();
$nombreEmpresa = isset($adminData['nombre']) ? $adminData['nombre'] : 'Nombre Predeterminado';
?>


<footer class="main-footer">
    <div class="footer-left">
        Copyright &copy; <div class="bullet"></div><a href="#"><?= htmlspecialchars($nombreEmpresa); ?></a>
    </div>
    <div class="footer-right">
    </div>
</footer>