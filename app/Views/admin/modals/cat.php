<div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <!-- TITLE -->
                <h5 class="modal-title" id="staticBackdropLabel"><?php echo $title; ?></h5>
                <!-- CLOSE -->
                <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- MODAL CONTENT -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mt-2">
                        <!-- TXT CATEGORY -->
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
    $('#btn-modal-submit').on('click', function() { // SUBMIT

        let resultCheckRequiredValues = checkRequiredValues('modal-required');

        if (resultCheckRequiredValues == 0) {

            $('#btn-modal-submit').attr('disabled', true);

            let url = '';
            let action = '<?php echo $action; ?>';

            if (action == 'create')
                url = '<?php echo base_url('Administrator/createCat'); ?>';
            else if (action == 'update')
                url = '<?php echo base_url('Administrator/updateCat'); ?>';

            $.ajax({

                type: "post",
                url: url,
                data: {
                    'categoryName': $('#txt-cat').val(),
                    'categoryID': '<?php echo @$category[0]->id; ?>'
                },
                dataType: "json",

                success: function(jsonResponse) {

                    if (jsonResponse.error == 0) { // SUCCESS

                        showToast('success', jsonResponse.msg);
                        window.location.reload();
                        closeModal();

                    } else { // ERROR

                        showToast('error', jsonResponse.msg);

                        if (jsonResponse.code == 103) // ERROR SESSION EXPIRED
                            window.location.href = '<?php echo base_url('Home'); ?>?msg=1';
                            
                        else if (jsonResponse.code == 104) // ERRROR DUPLICATE RECORD
                            $("#txt-cat").addClass('is-invalid');

                        $('#btn-modal-submit').removeAttr('disabled');
                    }
                },
                error: function(error) {
                    showToast('error', 'Ha ocurrido un error');
                }
            });
        }
    });
</script>