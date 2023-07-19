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
                    <div class="col-12 col-lg-6">
                        <label for="txt-hall">Sala (mesas)</label>
                        <input type="number" id="txt-hall" class="form-control modal-required number focus" min="0" value="<?php echo $hall; ?>" />
                        <p id="msg-txt-hall" class="text-danger text-end"></p>
                    </div>
                    <div class="col-12 col-lg-6">
                        <label for="txt-terrace">Terraza (mesas)</label>
                        <input type="number" id="txt-terrace" class="form-control modal-required number focus" min="0" value="<?php echo $terrace; ?>" />
                        <p id="msg-txt-terrace" class="text-danger text-end"></p>
                    </div>
                </div>
            </div>
            <?php echo view('admin/modals/modalFooter'); ?>
        </div>
    </div>
</div>

<script>
    $('#btn-modal-submit').on('click', function() {

        let resultCheckRequiredValues = checkRequiredValues('modal-required');

        if (resultCheckRequiredValues == 0) {

            $('#btn-modal-submit').attr('disabled', true);

            $.ajax({
                type: "post",
                url: "<?php echo base_url('Administrator/setConfig'); ?>",
                data: {
                    'hall': $('#txt-hall').val(),
                    'terrace': $('#txt-terrace').val(),
                },
                dataType: "json",
                success: function(jsonResponse) {

                    if (jsonResponse.error == 0) { // SUCCESS

                        showToast('success', jsonResponse.msg);
                        closeModal();

                    } else { // ERROR

                        showToast('error', jsonResponse.msg);

                        if (jsonResponse.code == 103) // ERROR SESSION EXPIRED
                            window.location.href = '<?php echo base_url('Home'); ?>?msg=Sesion Expirada';

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