<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Afiliaciones</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('dashboard'); ?>">Inicio</a></li>
                        <li class="breadcrumb-item active">Afiliaciones</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Información Importante</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> No hay agentes registrados</h5>
                        <p>Actualmente no hay agentes registrados en el sistema. Para poder crear afiliaciones, primero debe registrar al menos un agente de ventas.</p>
                    </div>
                    
                    <div class="text-center">
                        <p>Para continuar con el proceso de afiliaciones, por favor:</p>
                        
                        <?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('leadership')): ?>
                            <a href="<?php echo site_url('agentes/create'); ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus"></i> Registrar Nuevo Agente
                            </a>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <p>Por favor, contacte a un administrador para que registre un agente en el sistema.</p>
                                <p>Una vez que se haya registrado al menos un agente, podrá acceder a las funcionalidades de afiliación.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mt-4">
                        <h5>Proceso Recomendado:</h5>
                        <ol>
                            <li>Registrar un nuevo agente de ventas con todos sus datos</li>
                            <li>Una vez registrado el agente, volver a esta sección</li>
                            <li>Crear la afiliación asignando el agente recién registrado</li>
                        </ol>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-right">
                        <a href="<?php echo site_url('dashboard'); ?>" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>