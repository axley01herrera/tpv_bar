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
</script>