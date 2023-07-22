<!-- DATA TABLE CSS -->
<link href="<?php echo base_url('assets/css/datatable/dataTables.bootstrap5.min.css'); ?>" rel="stylesheet" type="text/css" />

<table id="dt-history-cancel" class="table table-hover table-borderless">
    <thead>
        <th><strong>TableID</strong></th>
        <th><strong>Estado</strong></th>
        <th><strong>Cancelada por</strong></th>
        <th><strong>Mesa</strong></th>
        <th><strong>Fecha de Apertura</strong></th>
        <th><strong>Fecha de Cierre</strong></th>
        <th><strong>Tipo de Pago</strong></th>
        <th><strong>Monto</strong></th>
    </thead>
</table>

<!-- DATA TABLE JS -->
<script src="<?php echo base_url('assets/js/datatable/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/datatable/dataTables.bootstrap5.min.js'); ?>"></script>

<script>
    var dtHistoryCancel = $('#dt-history-cancel').DataTable({

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
            url: "<?php echo base_url('Administrator/dtProcessingHistoryCancel'); ?>",
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
                data: 'status',
                orderable: false,
                searcheable: false
            },
            {
                data: 'employee'
            },
            {
                data: 'tableName',
            },
            {
                data: 'dateOpen'
            },
            {
                data: 'dateClose'
            },
            {
                data: 'payType',
            },
            {
                data: 'amount',
            },
        ],
    });
</script>