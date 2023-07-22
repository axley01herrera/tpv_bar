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
                    <div class="col-12 col-md-6 col-lg-6">
                        <label for="txt-name">Nombre</label>
                        <input id="txt-name" type="text" class="form-control modal-required focus" value="<?php echo @$userData[0]->name; ?>" />
                        <p id="msg-txt-name" class="text-danger text-end"></p>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <label for="txt-lastName">Apellido</label>
                        <input id="txt-lastName" type="text" class="form-control modal-required focus" value="<?php echo @$userData[0]->lastName; ?>" />
                        <p id="msg-txt-lastName" class="text-danger text-end"></p>
                    </div>
                    <div class="col-12">
                        <label for="txt-user">Usuario</label>
                        <input id="txt-user" type="text" class="form-control modal-required focus modal-user" value="<?php echo @$userData[0]->user; ?>" />
                        <p id="msg-txt-user" class="text-danger text-end"></p>
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

            $('btn-modal-submit').attr('disabled', true);

            let url = '';

            if (action == 'create')
                url = '<?php echo base_url('Administrator/createEmployee'); ?>';
            else if (action == 'update')
                url = '<?php echo base_url('Administrator/updateEmployee'); ?>';

            $.ajax({
                type: "post",
                url: url,
                data: {
                    'name': $('#txt-name').val(),
                    'lastName': $('#txt-lastName').val(),
                    'user': $('#txt-user').val(),
                    'userID': '<?php echo @$userData[0]->id; ?>',
                },
                dataType: "json",
                success: function(jsonResponse) {

                    if (jsonResponse.error == 0) { // SUCCESS

                        showToast('success', jsonResponse.msg);
                        dtEmployee.draw();
                        closeModal();

                    } else // ERROR
                        showToast('error', jsonResponse.msg);

                    if (jsonResponse.code == 103) // SESSION EXPIRED
                        window.location.href = '<?php echo base_url('Home'); ?>?msg=1';

                    if (jsonResponse.code == 104) // ERRROR USER EXIST
                        $("#txt-user").addClass('is-invalid');
                },
                error: function(error) {
                    showToast('error', 'Ha ocurrido un error');
                }
            });
        }
    });
</script>