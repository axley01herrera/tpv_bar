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
                        <label for="txt-cat">Categoría</label>
                        <input id="txt-cat" type="text" class="form-control modal-required focus" value="<?php echo @$category[0]->name; ?>" />
                        <p id="msg-txt-cat" class="text-danger text-end"></p>
                    </div>
                </div>
            </div>
            <?php echo view('admin/modals/modalFooter'); ?>
        </div>
    </div>
</div>

<script>
    $('#btn-modal-submit').on('click', function () {

        let resultCheckRequiredValues = checkRequiredValues('modal-required');

        if(resultCheckRequiredValues == 0) {

            $('#btn-modal-submit').removeAttr('disabled');

            let url = '';
            let action = '<?php echo $action; ?>';

            if(action == 'create')
                url = '<?php echo base_url('Product/createCat'); ?>';
            else if(action == 'update')
                url = '<?php echo base_url('Product/updateCat'); ?>';

            $.ajax({

                type: "post",
                url: url,
                data: {
                    'categoryName': $('#txt-cat').val(),
                    'categoryID': '<?php echo @$category[0]->id; ?>'
                },
                dataType: "json",

                success: function (jsonResponse) {

                    if(jsonResponse.error == 0) { // SUCCESS
                        closeModal();
                        window.location.reload();
                        showToast('success', jsonResponse.msg);
                    } else if (jsonResponse.error == 1) { // ERROR
                        showToast('error', jsonResponse.msg);
                        $('#txt-cat').addClass('is-invalid');
                        $('#msg-txt-cat').html(jsonResponse.msg);
                    } else if (jsonResponse.error == 2) // SESSION EXPIRED
                        window.location.href = '<?php echo base_url('Admin');?>?msg="sessionExpired"';
                },
                error: function (error) {
                    showToast('error', 'Ha ocurrido un error');
                }
            });
        }
    });
</script>