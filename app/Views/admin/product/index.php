<link href="<?php echo base_url('assets/css/datatable/dataTables.bootstrap5.min.css'); ?>" rel="stylesheet" type="text/css" />
<div class="row">
    <div class="col-12">
        <h1 class="text-primary">Productos</h1>
    </div>
</div>
<div class="card mt-3">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <button id="btn-newProduct" class="btn btn-primary">Nuevo Producto</button>
                <button id="btn-newCat" class="btn btn-success">Nueva Categoría</button>
            </div>
        </div>
        <div class="table-responsive mt-5">
            <table id="dataTable" class="table" style="width: 100%;">
                <thead>
                    <tr>
                        <th><strong>Producto</strong></th>
                        <th><strong>Categoría</strong></th>
                        <th><strong>Precio</strong></th>
                        <th><strong>Descripción</strong></th>
                        <th class="text"><strong>Estado</strong></th>
                        <th class="text-end"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script src="<?php echo base_url('assets/js/datatable/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/datatable/dataTables.bootstrap5.min.js'); ?>"></script>

<script>
    $('#btn-newProduct').on('click', function() { // NEW PRODUCT

        $.ajax({

            type: "post",
            url: "<?php echo base_url('Product/showModalProduct'); ?>",
            data: {
                action: 'create',
            },
            dataType: "html",
            success: function(htmlResponse) {
                $('#main-modal').html(htmlResponse);
            },
            error: function(error) {
                showToast('error', 'Ha ocurrido un error');
            }
        });

    });

    $('#btn-newCat').on('click', function() {

        $.ajax({

            type: "post",
            url: "<?php echo base_url('Product/showModalCat') ?>",
            data: {
                action: 'create',
            },
            dataType: "html",
            success: function(htmlResponse) {
                $('#main-modal').html(htmlResponse);
            },
            error: function(error) {
                showToast('error', 'Ha ocurrido un error');
            }
        });
    });

    var dataTable = $('#dataTable').DataTable({

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
            url: "<?php echo base_url('Product/processingProduct'); ?>",
            type: "POST"
        },
        order: [
            [0, 'asc']
        ],
        columns: [{
                data: 'name'
            },
            {
                data: 'cat'
            },
            {
                data: 'price'
            },
            {
                data: 'description'
            },
            {
                data: 'status',
                orderable: false,
                searchable: false
            },
            {
                data: 'switch',
                orderable: false,
                searchable: false
            },
            {
                data: 'action',
                class: 'text-center',
                orderable: false,
                searchable: false
            }
        ],
    });

    dataTable.on('click', '.switch', function(event) { // ACTIVE OR INACTIVE USER

        let status = $(this).attr('data-status');
        let newStatus = '';

        let userID = $(this).attr('data-id');

        if (status == 0)
            newStatus = 1;
        else if (status == 1)
            newStatus = 0;

        $.ajax({

            type: "post",
            url: "<?php echo base_url('Product/changeProductStatus'); ?>",
            data: {
                'userID': userID,
                'status': newStatus
            },
            dataType: "json",

        }).done(function(jsonResponse) {

            if (jsonResponse.error == 0) // SUCCESS
            {
                showToast('success', 'Proceso exitoso');

                dataTable.draw();
            } else // ERROR
            {
                showToast('error', 'Ha ocurrido un error');
            }

            if (jsonResponse.error == 2) // SESSION EXPIRED
                window.location.href = '<?php echo base_url('Admin'); ?>'

        }).fail(function(error) {

            showToast('error', 'Ha ocurrido un error');
        });
    });

    dataTable.on('click', '.btn-edit-product', function(event) { // UPDATE

        event.preventDefault();

        $.ajax({

            type: "post",
            url: "<?php echo base_url('Product/showModalProduct'); ?>",
            data: {
                'userID': $(this).attr('data-id'),
                'action': 'update',
            },
            dataType: "html",

        }).done(function(htmlResponse) {

            $('#main-modal').html(htmlResponse);

        }).fail(function(error) {

            showToast('error', 'Ha ocurrido un error');

        });
    });

    dataTable.on('click', '.btn-delete-product', function(event) { // DELETE

        event.preventDefault();

        let userID = $(this).attr('data-id');

        $.ajax({

            type: "post",
            url: "<?php echo base_url('Product/deleteProduct'); ?>",
            data: {
                'userID': userID,
                'action': 'delete',
            },
            dataType: "json",

        }).done(function(jsonResponse) {

            if (jsonResponse.error == 0) // SUCCESS
            {
                showToast('success', 'Proceso exitoso');

                dataTable.draw();

            } else // ERROR
            {
                showToast('error', 'Ha ocurrido un error');

            }

            if (jsonResponse.error == 2) // SESSION EXPIRED
                window.location.href = "<?php echo base_url('Admin'); ?>";

        }).fail(function(error) {

            showToast('error', 'Ha ocurrido un error');

        });
    });
</script>