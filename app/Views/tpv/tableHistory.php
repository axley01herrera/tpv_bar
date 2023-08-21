<!-- DATA TABLE CSS -->
<link href="<?php echo base_url('assets/css/datatable/dataTables.bootstrap5.min.css'); ?>" rel="stylesheet" type="text/css" />
<div class="table-responsive">
    <table id="dt-history" class="table table-hover table-borderless" style="width: 100%;">
        <thead style="background-color: #dce9f1;">
            <th><strong>TableID</strong></th>
            <th><strong>Mesa</strong></th>
            <th><strong>Fecha de Apertura</strong></th>
            <th><strong>Fecha de Cierre</strong></th>
            <th><strong>Empleado</strong></th>
            <th><strong>Tipo de Pago</strong></th>
            <th><strong>Monto</strong></th>
            <th class="text-center"><strong>Acciones</strong></th>
        </thead>
    </table>
</div>

<!-- DATA TABLE JS -->
<script src="<?php echo base_url('assets/js/datatable/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/datatable/dataTables.bootstrap5.min.js'); ?>"></script>

<script>
    var visible = '<?php echo @$visibleActions; ?>';
    if (visible == '') visible = false;
    else visible = true;

    var dtHistory = $('#dt-history').DataTable({

        destroy: true,
        processing: true,
        serverSide: true,
        responsive: true,
        bAutoWidth: true,
        pageLength: 10,
        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ],
        language: {
            url: '<?php echo base_url('assets/libs/dataTable/es.json'); ?>'
        },
        ajax: {
            url: "<?php echo base_url('Administrator/dtProcessingHistory'); ?>",
            type: "POST"
        },
        order: [
            [0, 'desc']
        ],
        columns: [{
                data: 'tableID',
                visible: false
            },
            {
                data: 'tableName'
            },
            {
                data: 'dateOpen'
            },
            {
                data: 'dateClose'
            },
            {
                data: 'employee',
            },
            {
                data: 'payType',
            },
            {
                data: 'amount',
            },
            {
                data: 'actions',
                class: 'text-center',
                orderable: false,
                searchable: false,
                visible: visible
            }
        ],
    });

    dtHistory.on('click', '.btn-open', function() { // REOPEN TABLE

        let id = $(this).attr('data-id');

        $.ajax({
            type: "post",
            url: "<?php echo base_url('TPV/reopenTable'); ?>",
            data: {
                'id': id
            },
            dataType: "json",
            success: function(jsonResponse) {

                if (jsonResponse.error == 0) { // SUCCESS
                    showToast('success', jsonResponse.msg);
                    dtHistory.draw();
                    window.location.href = "<?php echo base_url('TPV/tpv'); ?>" + '/' + id;
                } else { // ERROR
                    showToast('error', jsonResponse.msg);

                    if (jsonResponse.error == 2) // SESSION EXPIRED
                        window.location.href = '<?php echo base_url('Home'); ?>?msg=1';
                }
            },
            error: function(error) {
                showToast('error', 'Ha ocurrido un error');
            }
        });
    });

    dtHistory.on('click', '.btn-cancel', function() { // CANCEL 

        let id = $(this).attr('data-id');

        Swal.fire({ // ALERT WARNING
            title: 'Está seguro?',
            text: "Esta acción no es reversible y aparecerá registrada en el tablero del Administrador!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#74788d',
            confirmButtonText: 'Si, cancelar la mesa!',
            cancelButtonText: 'Salir',
            customClass: {
                confirmButton: 'delete'
            }
        });

        $('.delete').on('click', function() // ACTION DELETE
            {
                $.ajax({
                    type: "post",
                    url: "<?php echo base_url('TPV/cancelTable'); ?>",
                    data: {
                        'id': id
                    },
                    dataType: "json",
                    success: function(jsonResponse) {

                        if (jsonResponse.error == 0) { // SUCCESS
                            showToast('success', jsonResponse.msg);
                            dtHistory.draw();
                        } else { // ERROR
                            showToast('error', jsonResponse.msg);

                            if (jsonResponse.error == 2) // SESSION EXPIRED
                                window.location.href = '<?php echo base_url('Home'); ?>?msg=1';
                        }
                    },
                    error: function(error) {
                        showToast('error', 'Ha ocurrido un error');
                    }
                });
            });
    });

    dtHistory.on('click', '.btn-print', function() { // PRINT TICKET

        let id = $(this).attr('data-id');
        let url = "<?php echo base_url('TPV/printTicket'); ?>" + '/' + id;
        window.open(url, '_blank');

    });
</script>