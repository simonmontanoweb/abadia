<div class="d-flex justify-content-between align-items-center mb-3">
    <h1><?php echo html_escape($title); ?></h1>
    <div>
        <a href="<?php echo site_url('afiliaciones'); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <?php if ($this->ion_auth->is_admin() || 
                  $this->ion_auth->in_group('leadership') || 
                  $afiliacion->created_by == $this->ion_auth->user()->row()->id): ?>
            <a href="<?php echo site_url('afiliaciones/edit/'.$afiliacion->id); ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Información de la Afiliación</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>N° de Contrato:</strong> <?php echo html_escape($afiliacion->contract_number); ?></p>
                        <p><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($afiliacion->fecha)); ?></p>
                        <p><strong>Tipo de Plan:</strong> <?php echo html_escape($afiliacion->plan_type); ?></p>
                        <p><strong>Monto del Plan:</strong> $<?php echo number_format($afiliacion->plan_amount, 2); ?></p>
                        <p><strong>Número de Cuotas:</strong> <?php echo html_escape($afiliacion->cuotas); ?></p>
                        <p><strong>Valor por Cuota:</strong> $<?php echo number_format($afiliacion->plan_amount / $afiliacion->cuotas, 2); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Forma de Pago:</strong> <?php echo html_escape($afiliacion->payment_type); ?></p>
                        <?php if ($afiliacion->payment_type == 'domiciliacion'): ?>
                            <p><strong>Banco:</strong> <?php echo html_escape($afiliacion->bank_name); ?></p>
                            <p><strong>N° de Cuenta:</strong> <?php echo html_escape($afiliacion->account_number); ?></p>
                            <p><strong>Tipo de Cuenta:</strong> <?php echo html_escape($afiliacion->account_type); ?></p>
                        <?php endif; ?>
                        <p><strong>Asesor:</strong> <?php echo html_escape($afiliacion->asesor_nombres . ' ' . $afiliacion->asesor_apellidos); ?></p>
                        <p><strong>Creado por:</strong> <?php echo html_escape($afiliacion->created_by_firstname . ' ' . $afiliacion->created_by_lastname); ?></p>
                    </div>
                </div>
                
                <?php if (!empty($afiliacion->observaciones)): ?>
                    <div class="mt-3">
                        <h5>Observaciones:</h5>
                        <p><?php echo nl2br(html_escape($afiliacion->observaciones)); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Titular</h3>
            </div>
            <div class="card-body text-center">
                <img src="<?php echo !empty($afiliacion->titular_foto) ? base_url($afiliacion->titular_foto) : base_url('uploads/default_avatar.png'); ?>" 
                     class="rounded-circle mb-3" width="100" height="100" alt="Foto del Titular">
                <h5><?php echo html_escape($afiliacion->titular_nombres . ' ' . $afiliacion->titular_apellidos); ?></h5>
                <p><strong>Cédula:</strong> <?php echo html_escape($afiliacion->titular_cedula); ?></p>
                <p><strong>Fecha de Nacimiento:</strong> <?php echo date('d/m/Y', strtotime($afiliacion->titular_birthdate)); ?></p>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($afiliacion->familiares)): ?>
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Grupo Familiar</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre Completo</th>
                                <th>Cédula</th>
                                <th>Fecha de Nacimiento</th>
                                <th>Parentesco</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($afiliacion->familiares as $familiar): ?>
                                <tr>
                                    <td><?php echo html_escape($familiar->nombres . ' ' . $familiar->apellidos); ?></td>
                                    <td><?php echo html_escape($familiar->cedula); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($familiar->birthdate)); ?></td>
                                    <td><?php echo html_escape($familiar->parentesco); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>