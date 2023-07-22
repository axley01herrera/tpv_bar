<!-- DATA TABLE CSS -->
<link href="<?php echo base_url('assets/css/datatable/dataTables.bootstrap5.min.css'); ?>" rel="stylesheet" type="text/css" />

<table id="dt-history" class="table table-hover table-borderless">
    <thead>
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

<!-- DATA TABLE JS -->
<script src="<?php echo base_url('assets/js/datatable/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/datatable/dataTables.bootstrap5.min.js'); ?>"></script>

<script>
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
        columns: [
            {
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
                searchable: false
            }
        ],
    });
</script>