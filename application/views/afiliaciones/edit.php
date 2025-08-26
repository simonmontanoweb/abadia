<div class="d-flex justify-content-between align-items-center mb-3">
    <h1><?php echo html_escape($title); ?></h1>
    <div>
        <a href="<?php echo site_url('afiliaciones'); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Editar Afiliación</h3>
    </div>
    <div class="card-body">
        <?php echo form_open('afiliaciones/update/' . $afiliacion->id); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="asesor_id">Asesor Responsable</label>
                        <select name="asesor_id" id="asesor_id" class="form-control" required>
                            <?php foreach ($asesores as $asesor): ?>
                                <option value="<?php echo $asesor->id; ?>" <?php echo ($asesor->id == $afiliacion->asesor_id) ? 'selected' : ''; ?>>
                                    <?php echo html_escape($asesor->nombres . ' ' . $asesor->apellidos); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="plan_type">Tipo de Plan</label>
                        <select name="plan_type" id="plan_type" class="form-control" required>
                            <option value="esenciaSC" <?php echo ($afiliacion->plan_type == 'esenciaSC') ? 'selected' : ''; ?>>Esencia S/C ($10)</option>
                            <option value="esencia" <?php echo ($afiliacion->plan_type == 'esencia') ? 'selected' : ''; ?>>Esencia ($20)</option>
                            <option value="cobertura" <?php echo ($afiliacion->plan_type == 'cobertura') ? 'selected' : ''; ?>>Cobertura ($30)</option>
                            <option value="proteccion" <?php echo ($afiliacion->plan_type == 'proteccion') ? 'selected' : ''; ?>>Protección ($40)</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="plan_amount">Monto del Plan</label>
                        <input type="number" name="plan_amount" id="plan_amount" class="form-control" 
                               value="<?php echo $afiliacion->plan_amount; ?>" step="0.01" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cuotas">Número de Cuotas</label>
                        <input type="number" name="cuotas" id="cuotas" class="form-control" 
                               value="<?php echo $afiliacion->cuotas; ?>" min="1" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment_type">Forma de Pago</label>
                        <select name="payment_type" id="payment_type" class="form-control" required>
                            <option value="nomina" <?php echo ($afiliacion->payment_type == 'nomina') ? 'selected' : ''; ?>>Nómina</option>
                            <option value="domiciliacion" <?php echo ($afiliacion->payment_type == 'domiciliacion') ? 'selected' : ''; ?>>Domiciliación</option>
                            <option value="consignacion" <?php echo ($afiliacion->payment_type == 'consignacion') ? 'selected' : ''; ?>>Consignación Directa</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div id="datosBancarios" <?php echo ($afiliacion->payment_type != 'domiciliacion') ? 'style="display:none;"' : ''; ?>>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="bank_name">Banco</label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control" 
                                   value="<?php echo html_escape($afiliacion->bank_name); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="account_number">N° de Cuenta</label>
                            <input type="text" name="account_number" id="account_number" class="form-control" 
                                   value="<?php echo html_escape($afiliacion->account_number); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="account_type">Tipo de Cuenta</label>
                            <select name="account_type" id="account_type" class="form-control">
                                <option value="">Seleccione...</option>
                                <option value="ahorro" <?php echo ($afiliacion->account_type == 'ahorro') ? 'selected' : ''; ?>>Ahorro</option>
                                <option value="corriente" <?php echo ($afiliacion->account_type == 'corriente') ? 'selected' : ''; ?>>Corriente</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea name="observaciones" id="observaciones" class="form-control" rows="3"><?php echo html_escape($afiliacion->observaciones); ?></textarea>
            </div>
            
            <div class="text-right">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#payment_type').change(function() {
        if ($(this).val() == 'domiciliacion') {
            $('#datosBancarios').slideDown();
        } else {
            $('#datosBancarios').slideUp();
        }
    });
});
</script>