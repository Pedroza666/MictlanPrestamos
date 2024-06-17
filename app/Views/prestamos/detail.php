<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Detalles del préstamo
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Detalles del préstamo</h4>
    </div>
    <div class="card-body">

        <?php if (!empty(session()->getFlashdata('respuesta'))) { ?>
            <div class="alert alert-<?php echo session()->getFlashdata('respuesta')['type']; ?>">
                <?php echo session()->getFlashdata('respuesta')['msg']; ?>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><i class="fas fa-id-card"></i> N° de identidad: <?php echo $prestamo['num_identidad']; ?></li>
                            <li class="list-group-item"><i class="fas fa-list"></i> Cliente: <?php echo $prestamo['cliente'] . ' ' . $prestamo['apellido']; ?></li>
                            <li class="list-group-item"><i class="fas fa-calendar"></i> Fecha :
                                <?php
                                $dato = $prestamo['fecha'];
                                $fecha = date('Y-m-d', strtotime($dato));
                                $hora = date('h:i A', strtotime($dato));
                                echo fechaPerzo($fecha) . ' ' . $hora;
                                ?></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><i class="fas fa-tag"></i> N° de cuotas: <?php echo $prestamo['cuotas']; ?></li>
                            <li class="list-group-item"><i class="fas fa-calendar"></i> Modalidad: <?php echo $prestamo['modalidad'] ?></li>
                            <li class="list-group-item"><i class="fas fa-user"></i> Atendió : <?php echo $prestamo['usuario'] . ' ' . $prestamo['user_apellido']; ?></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-12">
                <div class="mb-3">
                    <a href="<?php echo base_url('prestamos/'. $prestamo['id'] .'/reporte'); ?> " target="_blank" class="btn btn-primary"><i class="fas fa-file-pdf"></i> Generar contrato</a>
                    <button type="button " id="btnCorreo" class="btn btn-warning"><i class="fas fa-envelope"></i> Enviar Correo</button>
                    <button type="button " id="btnWhatsApp" class="btn btn-success"><i class="fab fa-whatsapp-square"></i> WhatsApp</button>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Cuotas </th>
                                <th scope="col">Vencimiento </th>
                                <th scope="col">Importe x Cuota</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            $date = date('Y-m-d');
                            $botonHabilitado = true; // Variable para habilitar los botones de pago
                            foreach ($detalles as $detalle) {
                                $botonDesactivado = $detalle['estado'] == 0;
                                $total += $detalle['importe_cuota'];
                                $color = substr(md5($detalle['id']), 0, 6);
                                $estado = '<span class="badge badge-danger">PENDIENTE</span>';
                                if ($date > $detalle['fecha_venc'] && $detalle['estado'] == 1) {
                                    $class = 'bg-danger';
                                } else if ($date == $detalle['fecha_venc'] && $detalle['estado'] == 1) {
                                    $class = 'warning';
                                } else {
                                    $class = '';
                                    if ($detalle['estado'] == 1) {
                                        $estado =  '<span class="badge badge-danger">PENDIENTE</span>';
                                    } else {
                                        $estado =  '<span class="badge badge-success">PAGADO</span>';
                                    }
                                }
                            ?>
                                <tr class="<?php echo $class; ?>">
                                    <td scope="row">
                                        <input type="hidden" name="correo" value="<?php echo $prestamo['correo']; ?>">
                                        <button type="button" class="btn" style="background-color: #<?php echo $color; ?>">
                                            <input type="hidden" name="cuota" value="<?php echo $detalle['cuota']; ?>">
                                            Cuota <span class="badge badge-transparent text-dark"><?php echo $detalle['cuota']; ?></span>
                                        </button>
                                    </td>
                                    <td scope="row"><?php echo fechaPerzo($detalle['fecha_venc']); ?></td>
                                    <td scope="row">
                                        <input type="hidden" name="importe_cuota" value="<?php echo $detalle['importe_cuota']; ?>">
                                        <span class="badge badge-success text-dark"><?php echo $detalle['importe_cuota']; ?></span>
                                    </td>
                                    <td scope="row"><?php echo $estado; ?></td>
                                    <td>
                                        <form action="<?php echo base_url('prestamos/' . $detalle['id']); ?>" method="post" class="formEstado">
                                            <input type="hidden" name="correo" value="<?= $prestamo['correo']; ?>">
                                            <input type="hidden" name="cuota" value="<?= $detalle['cuota']; ?>">
                                            <input type="hidden" name="importe_cuota" value="<?= $detalle['importe_cuota']; ?>">
                                            <input type="hidden" name="_method" value="PUT">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-primary btn-pagar" <?php echo $botonDesactivado || !$botonHabilitado ? 'disabled' : ''; ?>>
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                            <?php
                                            // Después de procesar un pago, deshabilita el siguiente botón
                                            if (!$botonDesactivado) {
                                                $botonHabilitado = false;
                                            }
                                            ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="3" class="text-end">
                                    <h2>TOTAL <?php echo number_format($total, 2); ?></h2>
                                </td>
                                <td colspan="2"></td>

                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection('content'); ?>

<?= $this->section('modal'); ?>
<div class="modal fade" id="modalMensaje" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Mensaje</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo base_url('prestamos/enviarCorreo'); ?>" method="post">
                <div class="modal-body">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id_prestamo" value="<?php echo $prestamo['id']; ?>">
                    <input type="hidden" name="correo" value="<?php echo $prestamo['correo']; ?>">
                    <?php if (isset($validator)) { ?>
                        <span class="text-danger"><?php echo $validator->getError('correo'); ?></span>
                    <?php } ?>

                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Mensaje</label>
                        <textarea class="form-control" name="mensaje" id="mensaje" rows="3"></textarea>
                        <?php if (isset($validator)) { ?>
                            <span class="text-danger"><?php echo $validator->getError('mensaje'); ?></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary m-t-15 waves-effect">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalWhatsApp" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >Mensaje</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body">
                    <input type="hidden" id="num-whatsapp"  value="<?php echo $prestamo['whatsapp']; ?>">
                    <div class="mb-3">
                        <label for="mensaje-whatsapp" class="form-label">Mensaje</label>
                        <textarea class="form-control" id="mensaje-whatsapp" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnWhatsappWeb" class="btn btn-primary m-t-15 waves-effect">Enviar</button>
                </div>
        </div>
    </div>
</div>

<?= $this->endSection('modal'); ?>


<?= $this->section('js'); ?>
<script src="<?php echo base_url('assets/js/pages/prestamo_detail.js'); ?>"></script>
<script>
    <?php if (isset($validator)) { ?>
        myModal.show();
    <?php } ?>
</script>
<?= $this->endSection('js'); ?>
