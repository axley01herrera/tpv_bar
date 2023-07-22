<!-- DATA TABLE CSS-->
<link href="<?php echo base_url('assets/css/datatable/dataTables.bootstrap5.min.css'); ?>" rel="stylesheet" type="text/css" />

<h5 class="text-dark">Productos y Categorías</h5>

<div class="row mt-3">
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <button id="btn-newCat" class="btn btn-primary mb-3">Nueva Categoría</button>
                <div class="table-responsive">
                    <table id="dt-cat" class="table table-hover table-borderless" style="width: 100%;">
                        <thead>
                            <tr>
                                <th><strong>Categorías</strong></th>
                                <th class="text-end"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-body">
                <button id="btn-newProduct" class="btn btn-primary mb-3">Nuevo Producto</button>
                <div class="table-responsive">
                    <table id="dt-products" class="table table-hover table-borderless" style="width: 100%;">
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
        </div>
    </div>
</div>

<!-- DATA TABLE JS -->
<script src="<?php echo base_url('assets/js/datatable/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/datatable/dataTables.bootstrap5.min.js'); ?>"></script>

<script>
    $('#btn-newProduct').on('click', function() { // NEW PRODUCT

        $.ajax({
            type: "post",
            url: "<?php echo base_url('Administrator/showModalProduct'); ?>",
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
            url: "<?php echo base_url('Administrator/showModalCat') ?>",
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

    var dtPrducts = $('#dt-products').DataTable({

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

    dtPrducts.on('click', '.switch', function(event) { // ACTIVE OR INACTIVE PRODUCTS

        let status = $(this).attr('data-status');
        let newStatus = '';

        let id = $(this).attr('data-id');

        if (status == 0)
            newStatus = 1;
        else if (status == 1)
            newStatus = 0;

        $.ajax({

            type: "post",
            url: "<?php echo base_url('Administrator/changeProductStatus'); ?>",
            data: {
                'productID': id,
                'status': newStatus
            },
            dataType: "json",

        }).done(function(jsonResponse) {

            if (jsonResponse.error == 0) { // SUCCESS

                showToast('success', jsonResponse.msg);
                dtPrducts.draw();
            } else { // ERROR

                showToast('error', jsonResponse.msg);
                if (jsonResponse.code == 103) // SESSION EXPIRED
                    window.location.href = '<?php echo base_url('Home'); ?>?msg=Sesion Expirada';
            }
        }).fail(function(error) {
            showToast('error', 'Ha ocurrido un error');
        });
    });

    dtPrducts.on('click', '.btn-edit-product', function(event) { // UPDATE PRODUCT

        event.preventDefault();

        $.ajax({

            type: "post",
            url: "<?php echo base_url('Administrator/showModalProduct'); ?>",
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
            url: "<?php echo base_url('Administrator/catDataTable'); ?>",
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
            url: "<?php echo base_url('Administrator/showModalCat'); ?>",
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