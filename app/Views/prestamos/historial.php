<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Historial préstamos
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Historial préstamos</h4>
    </div>
    <div class="card-body">
        <input type="hidden" id="fecha_actual" value="<?= date('Y-m-d') ?>">
        <?php if (!empty(session()->getFlashdata('respuesta'))) { ?>
            <div class="alert alert-<?= session()->getFlashdata('respuesta')['type']; ?>">
                <?= session()->getFlashdata('respuesta')['msg']; ?>
            </div>
        <?php } ?>
        <div class="form-group">
            <label for="estadoSelector">Mostrar:</label>
            <select class="form-control" id="estadoSelector">
                <option value="activos">Préstamos Activos</option>
                <option value="liquidados">Préstamos Liquidados</option>
            </select>
        </div>
        <div class="table-responsive">
            <table class="table table-striped nowrap" id="tblPrestamos" style="width:100%">
                <thead>
                    <tr>
                        <th>Acciones</th>
                        <th class="text-center">#</th>
                        <th>Cliente</th>
                        <th>Importe</th>
                        <th>Modalidad</th>
                        <th>Fecha de vencimiento</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection('content'); ?>

<?= $this->section('js'); ?>
<script src="<?= base_url('assets/js/pages/prestamos-historial.js'); ?>"></script>
<?= $this->endSection('js'); ?>
