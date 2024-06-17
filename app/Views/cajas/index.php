<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Gestión Cajas
<?= $this->endSection('title');
if (empty($caja)) {?>
<?= $this->section('content'); ?>
<a href="<?php echo base_url('cajas/new'); ?>" class="btn btn-primary mb-2">Apertura</a>
<?php }else{?>
<a href="<?php echo base_url('cajas/'. $caja['id'] . '/edit'); ?>" class="btn btn-primary mb-2">Editar Monto</a>
<?php }?>

<div class="card">
    <div class="card-header">
        <h4>Gestión Cajas</h4>
    </div>
    <div class="card-body">
        <?php if (!empty(session()->getFlashdata('respuesta'))) { ?>
            <div class="alert alert-<?php echo session()->getFlashdata('respuesta')['type']; ?>">
                <?php echo session()->getFlashdata('respuesta')['msg']; ?>
            </div>
        <?php } ?>
    </div>
    
</div>
<?= $this->endSection('content'); ?>

<?= $this->section('js'); ?>
<script src="<?php echo base_url('assets/js/pages/cajas.js'); ?>"></script>
<?= $this->endSection('js'); ?>