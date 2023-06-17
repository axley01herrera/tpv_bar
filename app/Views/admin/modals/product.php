<div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <!-- TITLE -->
                <h5 class="modal-title" id="staticBackdropLabel"><?php echo $title; ?></h5>
                <!-- CLOSE -->
                <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mt-2">
                        <label for="txt-name">Nombre del Producto</label>
                        <input id="txt-name" type="text" class="form-control modal-required focus" value="<?php echo @$user_data[0]->name; ?>">
                        <p id="msg-txt-name" class="text-danger text-end"></p>
                    </div>
                    <div class="col-6">
                        <label for="txt-price">Precio</label>
                        <input id="txt-price" type="text" class="form-control modal-required focus" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="<?php echo @$user_data[0]->price; ?>">
                        <p id="msg-txt-price" class="text-danger text-end"></p>
                    </div>
                    <div class="col-6">
                        <label for="sel-cat">Categoría</label>
                        <select id="sel-cat" class="form-control modal-required focus" style="width: 100%;">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category->id ?>"><?= $category->nameCat ?></option>
                        <?php endforeach; ?>
                        </select>
                        <p id="msg-sel-cat" class="text-danger text-end"></p>
                    </div>
                    <div class="col-12">
                        <label for="txt-description">Descripción</label>
                        <input id="txt-description" class="form-control modal-required" rows="3" value="<?php echo @$user_data[0]->description; ?>"></input>
                    </div>
                </div>
            </div>
            <?php echo view('admin/modals/modalFooter'); ?>
        </div>
    </div>
</div>

<script>
    $('#btn-modal-submit').on('click', function() { // SUBMIT

        let action = "<?php echo $action; ?>";

        let resultCheckRequiredValues = checkRequiredValues('modal-required');

        if (resultCheckRequiredValues == 0) {

            if (action == 'create')
                ajaxCreate();
            else if (action == 'update')
                ajxUpdate();

        }
    });

    function ajaxCreate() {

        $.ajax({

            type: "post",
            url: "<?php echo base_url('Product/createProduct'); ?>",
            data: {
                'name': $('#txt-name').val(),
                'cat': $('#sel-cat').val(),
                'price': $('#txt-price').val(),
                'description': $('#txt-description').val(),
            },
            dataType: "json",

        }).done(function(jsonResponse) {

            if (jsonResponse.error == 0) // SUCCESS
            {
                showToast('success', 'Proceso exitoso');

                closeModal();

                dataTable.draw();

            } else // ERROR
            {
                showToast('error', 'Ya existe un producto con ese nombre.');
        
            }

            if (jsonResponse.error == 2) // SESSION EXPIRED
                window.location.href = "<?php echo base_url('Admin'); ?>";


            if (jsonResponse.error == 3)
                $("#txt-name").addClass('is-invalid');

        }).fail(function(error) {

            showToast('error', 'Ha ocurrido un error');

        });

    }

    function ajxUpdate() {

        $.ajax({

            type: "post",
            url: "<?php echo base_url('Product/updateProduct'); ?>",
            data: {
                'userID': '<?php echo @$user_data[0]->id; ?>',
                'name': $('#txt-name').val(),
                'cat': $('#sel-cat').val(),
                'price': $('#txt-price').val(),
                'description': $('#txt-description').val()                
            },
            dataType: "json",

        }).done(function(jsonResponse) {

            if (jsonResponse.error == 0) // SUCCESS
            {
                showToast('success', 'Proceso exitoso');

                dataTable.draw();

                closeModal();

            } else // ERROR
            {
                showToast('error', 'Ya existe un producto con ese nombre.');
                $("#txt-name").addClass('is-invalid');

            }

            if (jsonResponse.error == 2) // SESSION EXPIRED
                window.location.href = "<?php echo base_url('Admin'); ?>";
                

        }).fail(function(error) {

            showToast('error', 'Ha ocurrido un error');
        })
    }
</script>