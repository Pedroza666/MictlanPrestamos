<?php
// Realizar la consulta a la base de datos para obtener el nombre
$adminModel = new \App\Models\AdminModel();
$adminData = $adminModel->first();
$nombreEmpresa = isset($adminData['nombre']) ? $adminData['nombre'] : 'Nombre Predeterminado';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf_token" content="<?= csrf_token() ?>">
  <meta name="csrf_hash" content="<?= csrf_hash() ?>">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?= htmlspecialchars($nombreEmpresa) ?> | <?= $this->renderSection('title'); ?></title>
  <?= $this->include('layouts/styles.php'); ?>
  <link rel='shortcut icon' type='image/x-icon' href='<?= base_url('assets/img/logo.png'); ?>' />
</head>