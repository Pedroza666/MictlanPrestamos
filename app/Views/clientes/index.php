<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Gestión Clientes
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<a href="<?php echo base_url('clientes/new'); ?>" class="btn btn-primary mb-2">Nuevo</a>
<div class="card">
    <div class="card-header">
        <h4>Gestión Clientes</h4>
    </div>
    <div class="card-body">
        <?php if (!empty(session()->getFlashdata('respuesta'))) { ?>
            <div class="alert alert-<?php echo session()->getFlashdata('respuesta')['type']; ?>">
                <?php echo session()->getFlashdata('respuesta')['msg']; ?>
            </div>
        <?php } ?>
        <div class="table-responsive">
            <table class="table table-striped nowrap" id="tblClientes" style="width:100%">
                <thead>
                    <tr>
                        <th>Acciones</th>
                        <th>#</th>
                        <th>Tipo de Identificación</th>
                        <th>Número de Identificación</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Dirección</th>
                        <th>Foto Identificación</th>
                        <th>Foto Identificación Reverso</th>
                        <th>Foto Domicilio</th>
                        <th>Foto Cliente</th>
                        <th>Nombre Referencia</th>
                        <th>Dirección Referencia</th>
                        <th>Teléfono Referencia</th>
                        <th>estado</th>
                        
                        
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection('content'); ?>

<?= $this->section('js'); ?>
<script src="<?php echo base_url('assets/js/pages/clientes.js'); ?>"></script>
<?= $this->endSection('js'); ?>