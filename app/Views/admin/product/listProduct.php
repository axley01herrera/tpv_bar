<!-- CSS-->
<link href="<?php echo base_url('assets/css/datatable/dataTables.bootstrap5.min.css'); ?>" rel="stylesheet" type="text/css" />

<div class="row">
    <div class="col-12">
        <h1 class="text-dark">Productos y Categorías</h1>
    </div>
</div>
<div class="row">
    <div class="col-12 text-end">
        <button id="btn-newProduct" class="btn btn-primary">Nuevo Producto</button>
        <button id="btn-newCat" class="btn btn-primary">Nueva Categoría</button>
    </div>
</div>
<div class="card mt-3">
    <div class="card-body">
        <table id="dataTable" class="table" style="width: 100%;">
            <thead>
                <tr>
                    <th><strong>Producto</strong></th>
                    <th><strong>Categoría</strong></th>
                    <th><strong>Precio</strong></th>
                    <th><strong>Descripción</strong></th>
                    <th class="text"><strong>Estado</strong></th>
                    <th class="text-center"></th>
                    <th class="text-end"></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <table id="dt-cat" class="table" style="width: 100%;">
            <thead>
                <tr>
                    <th><strong>Categorías</strong></th>
                    <th class="text-end"></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- JS -->
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

    $('#btn-newCat').on('click', function() { // NEW CATEGORY

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
            url: "<?php echo base_url('Administrator/dtProcessingProducts'); ?>",
            type: "POST"
        },
        order: [
            [0, 'asc']
        ],
        columns: [{
                data: 'productName'
            },
            {
                data: 'category'
            },
            {
                data: 'price'
            },
            {
                data: 'description',
                orderable: false,
                searchable: false
            },
            {
                data: 'status'
            },
            {
                data: 'switch',
                class: 'text-center',
                orderable: false,
                searchable: false
            },
            {
                data: 'action',
                class: 'text-end',
                orderable: false,
                searchable: false
            }
        ],
    });

    dataTable.on('click', '.switch', function(event) { // ACTIVE OR INACTIVE

        let status = $(this).attr('data-status');
        let newStatus = '';

        let id = $(this).attr('data-id');

        if (status == 0)
            newStatus = 1;
        else if (status == 1)
            newStatus = 0;

        $.ajax({

            type: "post",
            url: "<?php echo base_url('Product/changeProductStatus'); ?>",
            data: {
                'productID': id,
                'status': newStatus
            },
            dataType: "json",

        }).done(function(jsonResponse) {

            if (jsonResponse.error == 0) // SUCCESS
            {
                showToast('success', jsonResponse.msg);
                dataTable.draw();
            } else // ERROR
                showToast('error', jsonResponse.msg);

            if (jsonResponse.error == 2) // SESSION EXPIRED
                window.location.href = '<?php echo base_url('Admin'); ?>?msg="sessionExpired"';

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
                'productID': $(this).attr('data-id'),
                'action': 'update',
            },
            dataType: "html",

        }).done(function(htmlResponse) {

            $('#main-modal').html(htmlResponse);

        }).fail(function(error) {

            showToast('error', 'Ha ocurrido un error');

        });
    });

    var dtCat = $('#dt-cat').DataTable({

        destroy: true,
        processing: true,
        serverSide: false,
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
            url: "<?php echo base_url('Product/catDataTable'); ?>",
            type: "POST"
        },
        order: [
            [0, 'asc']
        ],
        columns: [{
                data: 'category'
            },
            {
                data: 'action',
                class: 'text-end',
                orderable: false,
                searchable: false
            }
        ],
    });

    dtCat.on('click', '.btn-edit-cat', function() { // UPDATE CATEGORY

        $.ajax({

            type: "post",
            url: "<?php echo base_url('Product/showModalCat'); ?>",
            data: {
                'categoryID': $(this).attr('data-id'),
                'action': 'update',
            },
            dataType: "html",

        }).done(function(htmlResponse) {

            $('#main-modal').html(htmlResponse);

        }).fail(function(error) {

            showToast('error', 'Ha ocurrido un error');

        });
    });
</script>