<!-- HEADER -->
<?php echo view('layout/headerAuthentication'); ?>
<!-- CONTENT -->
<div class="authentication-bg min-vh-100">
    <div class="container">
        <div class="d-flex flex-column min-vh-100 px-3 pt-4">
            <div class="row justify-content-center my-auto">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="text-center py-5">
                        <div class="user-thumb mb-4 mb-md-5">
                            <img src="<?php echo base_url('assets/images/users/user.png'); ?>" class="rounded-circle img-thumbnail avatar-lg" alt="thumbnail">
                            <h5 class="font-size-16 mt-3">Administrador</h5>
                        </div>
                        <!-- TXT PASSWORD -->
                        <div class="form-floating form-floating-custom mb-3">
                            <input type="password" id="txt-password" class="form-control required focus" placeholder="Contraseña">
                            <label for="txt-password">Contraseña</label>
                            <div class="form-floating-icon">
                                <i class="uil uil-padlock"></i>
                            </div>
                        </div>
                        <!-- BTN LOGIN -->
                        <div class="mt-3">
                            <button id="btn-login" type="button" class="btn btn-info w-100" type="button">Entrar</button>
                        </div>
                        <!-- LINK GO TO HOME -->
                        <div class="mt-3">
                            <a href="<?php echo base_url('Home'); ?>">Volver al Inicio!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- FOOTER -->
<?php
echo view('layout/footerAuthentication');
echo view('global/formValidation'); // ADD FORM VALIDATION 
?>

<script>
    $(document).ready(function() {

        $('#btn-login').on('click', function() { // BTN SUBMIT

            let resultCheckRequiredValues = checkRequiredValues('required'); // CHECK REQUIRED VALUES

            if (resultCheckRequiredValues == 0) {

                $('#btn-login').attr('disabled', true);
                let password = $('#txt-password').val();

                $.ajax({
                    type: "post",
                    url: "<?php echo base_url('Home/loginAdmin'); ?>",
                    data: {
                        'password': password
                    },
                    dataType: "json",

                    success: function(jsonResponse) {

                        if (jsonResponse.error == 0)
                            window.location.href = '<?php echo base_url('Administrator/dashboard'); ?>';
                        else if (jsonResponse.error == 1) {
                            showToast('error', 'Contraseña Incorrecta');
                            $('#input-password').addClass('is-invalid');
                            $('#btn-login').removeAttr('disabled');
                        }
                    },

                });

            } else
                showToast('error', 'Debe escribir su contraseña');
        });
    });
</script>