<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Modificar Cliente
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Modificar Cliente</h4>
    </div>
    <div class="card-body">
        <!-- Agregar un mensaje visible al inicio -->
        <div id="mensaje" class="alert alert-warning">
            Al modificar deberas cargar nuevamente las fotos, asegurate de hacerlo
        </div>
        <form action="<?php echo base_url('clientes/' . $cliente['id']); ?>" method="post" autocomplete="off" enctype="multipart/form-data">
        <?php echo csrf_field();?>
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id_cliente" value="<?php echo $cliente['id']; ?>">
            <div class="row">
                <div class="form-group col-lg-4">
                    <label>Tipo de Identificacion</label>
                    <input type="text" name="identidad" class="form-control" value="<?php echo set_value('identidad', $cliente['identidad']); ?>" placeholder="Documento de Identificación">
                    <?php if (!empty($errors['identidad'])) { ?>
                        <span class="text-danger"><?php echo $errors['identidad']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>N° de identificación</label>
                    <input type="text" name="num_identidad" class="form-control" value="<?php echo set_value('num_identidad', $cliente['num_identidad']); ?>" placeholder="N° de identificación">
                    <?php if (!empty($errors['num_identidad'])) { ?>
                        <span class="text-danger"><?php echo $errors['num_identidad']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="<?php echo set_value('nombre', $cliente['nombre']); ?>" placeholder="Nombre">
                    <?php if (!empty($errors['nombre'])) { ?>
                        <span class="text-danger"><?php echo $errors['nombre']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Apellidos</label>
                    <input type="text" name="apellido" class="form-control" value="<?php echo set_value('apellido', $cliente['apellido']); ?>" placeholder="Apellido">
                    <?php if (!empty($errors['apellido'])) { ?>
                        <span class="text-danger"><?php echo $errors['apellido']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Teléfono</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-phone"></i>
                            </div>
                        </div>
                        <input type="text" name="telefono" class="form-control phone-number" value="<?php echo set_value('telefono', $cliente['telefono']); ?>" placeholder="Telefono">
                    </div>
                    <?php if (!empty($errors['telefono'])) { ?>
                        <span class="text-danger"><?php echo $errors['telefono']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>WhatsApp</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fab fa-whatsapp-square"></i>
                            </div>
                        </div>
                        <input type="text" name="whatsapp" class="form-control phone-number" value="<?php echo set_value('whatsapp', $cliente['whatsapp']); ?>" placeholder="Whatsapp">
                    </div>
                    <?php if (!empty($errors['whatsapp'])) { ?>
                        <span class="text-danger"><?php echo $errors['whatsapp']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Correo</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                        <input type="text" name="correo" class="form-control" value="<?php echo set_value('correo', $cliente['correo']); ?>" placeholder="Correo">
                    </div>
                    <?php if (!empty($errors['correo'])) { ?>
                        <span class="text-danger"><?php echo $errors['correo']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Dirección</label>
                    <input type="text" name="direccion" class="form-control" value="<?php echo set_value('direccion', $cliente['direccion']); ?>" placeholder="Direccion">
                    <?php if (!empty($errors['direccion'])) { ?>
                        <span class="text-danger"><?php echo $errors['direccion']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Foto de Identificación</label>
                    <input type="file" name="foto_identificacion" class="form-control">
                    
                </div>
                <div class="form-group col-lg-4">
                    <label class="form-label">Foto Reverso Identificación</label>
                    <input type="file" name="foto_identificacion_reverso" class="form-control">
                </div>
                <div class="form-group col-lg-4">
                    <label class="form-label">Foto de Domicilio</label>
                    <input type="file" name="foto_domicilio" class="form-control">
                </div>
                
                <div class="form-group col-lg-4">
                    <label>Foto de Cliente</label>
                    <input type="file" name="foto_cliente" class="form-control">
                </div>
                <div class="form-group col-lg-4">
                    <label>Nombre de Referencia</label>
                    <input type="text" name="nombre_referencia" class="form-control" value="<?php echo set_value('nombre_referencia',$cliente['nombre_referencia']); ?>" placeholder="Nombre de Referencia">
                    
                </div>
                <div class="form-group col-lg-4">
                    <label>Dirección de Referencia</label>
                    <input type="text" name="direccion_referencia" class="form-control" value="<?php echo set_value('direccion_referencia',$cliente['direccion_referencia']); ?>" placeholder="Dirección de Referencia">
                    
                </div>
                <div class="form-group col-lg-4">
                    <label>Teléfono de Referencia</label>
                    <input type="text" name="telefono_referencia" class="form-control" value="<?php echo set_value('telefono_referencia', $cliente['telefono_referencia']); ?>" placeholder="Teléfono de Referencia">
                    
                </div>
            </div>
            <div class="text-end">
                <a href="<?php echo base_url('clientes'); ?>" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
<div id="imagenModal" class="modal">
    <span class="cerrar" onclick="cerrarModal()">&times;</span>
    <img class="modal-content" id="imagenSeleccionada">
</div>



<script>
    // JavaScript para ocultar el mensaje después de unos segundos
    setTimeout(function() {
        var mensaje = document.getElementById('mensaje');
        mensaje.style.display = 'none';
    }, 10000); // El mensaje se ocultará después de 10 segundos
</script>

<?= $this->endSection('content'); ?>