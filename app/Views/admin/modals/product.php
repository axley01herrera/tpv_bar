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
                        <input id="txt-name" type="text" class="form-control modal-required focus" value="<?php echo @$product[0]->name; ?>">
                        <p id="msg-txt-name" class="text-danger text-end"></p>
                    </div>
                    <div class="col-6">
                        <label for="txt-price">Precio</label>
                        <input id="txt-price" type="text" class="form-control modal-required focus" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="<?php echo @$product[0]->price; ?>">
                        <p id="msg-txt-price" class="text-danger text-end"></p>
                    </div>
                    <div class="col-6">
                        <label for="sel-cat">Categoría</label>
                        <select id="sel-cat" class="form-control modal-required focus" style="width: 100%;">
                            <option value=""></option>
                            <?php for ($i = 0; $i < $countCategories; $i++) { ?>
                                <option <?php if (!empty($product[0]->fk_category)) {
                                            if ($product[0]->fk_category == $categories[$i]->id) echo 'selected';
                                        } ?> value="<?php echo $categories[$i]->id ?>"><?php echo $categories[$i]->name ?></option>
                            <?php }; ?>
                        </select>
                        <p id="msg-sel-cat" class="text-danger text-end"></p>
                    </div>
                    <div class="col-12">
                        <label for="txt-description">Descripción (Opcional)</label>
                        <textarea id="txt-description" class="form-control" rows="3"><?php echo @$product[0]->description; ?></textarea>
                    </div>
                </div>
            </div>
            <?php echo view('admin/modals/modalFooter'); ?>
        </div>
    </div>
</div>

<script>
    $('#btn-modal-submit').on('click', function() { // SUBMIT

        let resultCheckRequiredValues = checkRequiredValues('modal-required');

        if (resultCheckRequiredValues == 0) {

            $('#btn-modal-submit').attr('disabled', true);
            let action = '<?php echo $action; ?>';
            let url = '';

            if (action == 'create')
                url = '<?php echo base_url('Administrator/createProduct'); ?>';
            else if (action == 'update')
                url = '<?php echo base_url('Administrator/updateProduct'); ?>';

            $.ajax({

                type: "post",
                url: url,
                data: {
                    'productName': $('#txt-name').val(),
                    'productPrice': $('#txt-price').val(),
                    'categoryID': $('#sel-cat').val(),
                    'productDescription': $('#txt-description').val(),
                    'productID': '<?php echo @$product[0]->id; ?>'
                },
                dataType: "json",
                success: function(jsonResponse) {

                    if (jsonResponse.error == 0) { // SUCCESS
                     
                        showToast('success', jsonResponse.msg);
                        dtPrducts.draw();
                        closeModal();

                    } else { // ERROR

                        showToast('error', jsonResponse.msg);

                        if(jsonResponse.code == 104) { // ERROR DUPLICATE RECORD
                            $('#txt-name').addClass('is-invalid');
                            $('#msg-txt-name').html(jsonResponse.msg);
                        }

                        if (jsonResponse.code == 103) // SESSION EXPIRED
                            window.location.href = '<?php echo base_url('Home'); ?>?msg=1';

                        $('#btn-modal-submit').removeAttr('disabled');
                    }
                },
                error: function(error) {
                    showToast('error', 'Ha ocurrido un error');
                }
            });

        }
    });

    $('#sel-cat').select2({ // SEL CAT
        placeholder: {
            id: '',
            text: ''
        },
        dropdownParent: $("#modal")
    }).on('select2:open', function() {

        let selectContainer = $(this).next('.select2-container');

        if (selectContainer.length != 0) {
            selectContainer.css('border', '#e2e5e8');
            selectContainer.css('border-radius', '.25rem');
        }

        let inputID = $(this).attr("id");
        $('#msg-' + inputID).html("");

    });
</script>