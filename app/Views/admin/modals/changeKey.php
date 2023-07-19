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
                    <div class="col-12">
                        <label for="txt-newPassword">Nueva Contraseña</label>
                        <input id="txt-newPassword" type="password" class="form-control modal-required focus" />
                        <p id="msg-txt-newPassword" class="text-danger text-end"></p>
                    </div>
                    <div class="col-12">
                        <label for="txt-repeatNewPassword">Repita su nueva Contraseña</label>
                        <input id="txt-repeatNewPassword" type="password" class="form-control modal-required focus" />
                        <p id="msg-txt-repeatNewPassword" class="text-danger text-end"></p>
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
        let password = $('#txt-newPassword').val();
        let repeatPassword = $('#txt-repeatNewPassword').val();

        if (resultCheckRequiredValues == 0) {

            if(password == repeatPassword) {

                $('btn-modal-submit').attr('disabled', true);
    
                $.ajax({
                    type: "post",
                    url: '<?php echo base_url('Administrator/updateKey'); ?>',
                    data: {
                        password: password,
                    },
                    dataType: "json",
                    success: function(jsonResponse) {
    
                        if (jsonResponse.error == 0) { // SUCCESS
    
                            showToast('success', jsonResponse.msg);
                            closeModal();
    
                        } else // ERROR
                            showToast('error', jsonResponse.msg);
    
                        if (jsonResponse.code == 103) // SESSION EXPIRED
                            window.location.href = '<?php echo base_url('Home'); ?>?msg=Sesion Expirada';
                    },
                    error: function(error) {
                        showToast('error', 'Ha ocurrido un error');
                    }
                });
            } else { // ERROR PASSWORD NO MATCH
                $('#txt-repeatNewPassword').addClass('is-invalid');
                $('#msg-txt-repeatNewPassword').html('La contraseña no coincide');
            }
        }
    });
</script>