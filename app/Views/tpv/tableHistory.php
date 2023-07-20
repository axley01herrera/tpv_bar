<!-- DATA TABLE CSS -->
<link href="<?php echo base_url('assets/css/datatable/dataTables.bootstrap5.min.css'); ?>" rel="stylesheet" type="text/css" />

<table id="dt-history" class="table table-hover table-borderless">
    <thead>
        <th>Mesa</th>
        <th>Apertura</th>
        <th>Cierre</th>
        <th>Empleado</th>
        <th>Tipo de Pago</th>
        <th>Cobro</th>
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
        pageLength: 5,
        lengthMenu: [
            [5, 25, 50, 100],
            [5, 25, 50, 100]
        ],
        language: {
            url: '<?php echo base_url('assets/libs/dataTable/es.json'); ?>'
        },
        ajax: {
            url: "<?php echo base_url('Administrator/dtProcessingHistory'); ?>",
            type: "POST"
        },
        order: [
            [1, 'desc']
        ],
        columns: [{
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
            }
        ],
    });
</script>