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
                        <label for="txt-clave">Clave</label>
                        <input id="txt-clave" type="password" class="form-control modal-required focus">
                        <p id="msg-txt-clave" class="text-danger text-end"></p>
                    </div>
                    <div class="col-12 mt-2">
                        <label for="txt-clave2">Confirme la Clave</label>
                        <input id="txt-clave2" type="password" class="form-control modal-required focus">
                        <p id="msg-txt-clave2" class="text-danger text-end"></p>
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

            let clave = $('#txt-clave').val();
            let clave2 = $('#txt-clave2').val();

            if (clave == clave2) {

                $('btn-modal-submit').attr('disabled', true);

                $.ajax({
                    type: "post",
                    url: "<?php echo base_url('Administrator/setClave'); ?>",
                    data: {
                        'clave': clave,
                        'id': '<?php echo $id; ?>'
                    },
                    dataType: "json",
                }).done(function(jsonResponse) {

                    if (jsonResponse.error == 0) { // SUCCESS

                        showToast('success', jsonResponse.msg);
                        dtEmployee.draw();
                        closeModal();

                    } else // ERROR
                        showToast('error', jsonResponse.msg);

                    if (jsonResponse.code == 103) // SESSION EXPIRED
                        window.location.href = '<?php echo base_url('Home'); ?>?msg=Sesion Expirada';

                }).fail(function(error) {
                    showToast('error', 'Ha ocurrido un error');
                });
            } else {
                $('#txt-clave2').addClass('is-invalid');
                $('#msg-txt-clave2').html('Las claves no coinciden');
            }
        }
    });
</script>