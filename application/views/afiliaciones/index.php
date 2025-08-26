<div class="d-flex justify-content-between align-items-center mb-3">
    <h1><?php echo html_escape($title); ?></h1>
    <div>
        <a href="<?php echo site_url('afiliaciones/create'); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Afiliación
        </a>
    </div>
</div>

<div class="table-responsive">
    <table id="afiliacionesTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>N° Contrato</th>
                <th>Titular (Nombres)</th>
                <th>Titular (Apellidos)</th>
                <th>Cédula</th>
                <th>Asesor</th>
                <th>Plan</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded by DataTables -->
        </tbody>
    </table>
</div>

<script type="text/javascript" src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
$(document).ready(function () {
    var tabla = $('#afiliacionesTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "<?php echo base_url('afiliaciones/afiliaciones_list'); ?>",
            type: "POST"
        },
        columns: [
            { data: "id" },
            { data: "contract_number" },
            { data: "titular_nombres" },
            { data: "titular_apellidos" },
            { data: "titular_cedula" },
            { data: "asesor_nombres" },
            { data: "plan_type" },
            { data: "plan_amount" },
            { data: "fecha" },
            { data: "actions" }
        ],
        columnDefs: [
            {
                targets: 7, // Monto column
                render: function (data, type, row) {
                    return '$' + parseFloat(data).toFixed(2);
                }
            },
            {
                targets: 8, // Fecha column
                render: function (data, type, row) {
                    return moment(data).format('DD/MM/YYYY');
                }
            },
            {
                targets: 9, // Actions column
                orderable: false,
                searchable: false
            }
        ],
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
        }
    });

    // Evento para botón Eliminar
    $('#afiliacionesTable').on('click', '.delete-afiliacion', function () {
        const id = $(this).data('id');
        const contract = $(this).data('contract');
        const deleteUrl = "<?php echo base_url('afiliaciones/delete/'); ?>" + id;

        Swal.fire({
            title: `¿Eliminar la afiliación ${contract}?`,
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(deleteUrl, { 
                    id: id, 
                    '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' 
                }, function (response) {
                    if(response.success) {
                        Swal.fire({
                            title: 'Eliminado',
                            text: `La afiliación ${contract} fue eliminada exitosamente.`,
                            icon: 'success',
                            timer: 2500,
                            showConfirmButton: false
                        });
                        tabla.ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message || 'No se pudo completar la eliminación.',
                            icon: 'error'
                        });
                    }
                }, 'json').fail(function () {
                    Swal.fire({
                        title: 'Error de Comunicación',
                        text: 'No se pudo contactar al servidor.',
                        icon: 'error'
                    });
                });
            }
        });
    });
});
</script>