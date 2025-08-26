<!-- Initialize TablesIgniter -->
<script>
$(document).ready(function() {
    $('#estados_table').TablesIgniter({
        url: '<?php echo site_url('ubicaciones/get_estados_list'); ?>',
        method: 'POST',
        language: {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
    
    // Delete estado
    let estadoId;
    $(document).on('click', '.delete-estado', function() {
        estadoId = $(this).data('id');
        const estadoName = $(this).data('name');
        $('#estadoName').text(estadoName);
        $('#deleteEstadoModal').modal('show');
    });
    
    $('#confirmDeleteEstado').click(function() {
        $.ajax({
            url: '<?php echo site_url('ubicaciones/delete_estado/'); ?>' + estadoId,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#deleteEstadoModal').modal('hide');
                    $('#estados_table').TablesIgniter().reload();
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Error al procesar la solicitud.');
            }
        });
    });
});
</script>