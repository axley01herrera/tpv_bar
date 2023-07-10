<!-- HEADER -->
<?php echo view('layout/headerAuthentication'); ?>
<!-- CONTENT -->
<div class="authentication-bg min-vh-100">
    <div class="container">
        <div class="d-flex flex-column min-vh-100 px-3 pt-4">
            <!-- AUTH TOP BAR -->
            <?php echo view('authentication/topBar'); ?>
            <div class="row justify-content-center my-auto">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="text-center py-5">
                        <div class="user-thumb mb-4 mb-md-5">
                            <img src="<?php echo base_url('assets/images/users/user.png'); ?>" class="rounded-circle img-thumbnail avatar-lg" alt="thumbnail">
                            <h1>Bienvenido</h1>
                            <h5>Inicia sessión para continuar</h5>
                        </div>
                        <div class="form-floating form-floating-custom mb-3">
                            <!-- SEL USER -->
                            <select id="sel-user" class="form-select required focus">
                                <option hidden value="">Seleccione su usuario</option>
                                <?php for ($i = 0; $i < $countEmployees; $i++) { ?>
                                    <option value="<?php echo $employees[$i]->id; ?>"><?php echo $employees[$i]->user; ?></option>
                                <?php } ?>
                            </select>
                            <label for="sel-user">Usuario</label>
                            <div class="form-floating-icon">
                                <i class="uil uil-user"></i>
                            </div>
                        </div>
                        <div class="form-floating form-floating-custom mb-3">
                            <!-- TXT PASSWORD-->
                            <input type="password" id="txt-password" class="form-control required focus" placeholder="Contraseña">
                            <label for="txt-password">Contraseña</label>
                            <div class="form-floating-icon">
                                <i class="uil uil-padlock"></i>
                            </div>
                        </div>
                        <!-- BTN LOGIN -->
                        <div class="mt-3">
                            <button id="btn-login" type="button" class="btn btn-info" type="button">Entrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- COPY RIGHT -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="text-center p-4">
                        <h5 class="mb-0">© <script>
                                document.write(new Date().getFullYear())
                            </script> Creado por Axley Herrera Vázquez</h5>
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

        let msg = '<?php echo @$msg; ?>';

        if (msg != '')
            showToast('error', msg);

        $('#btn-login').on('click', function() { // BTN-LOGIN

            let resultCheckRequiredValues = checkRequiredValues('required'); // CHECK REQUIRED VALUES

            if (resultCheckRequiredValues == 0) {

                $('#btn-login').attr('disabled', true); // DISABLED LOGIN BTN

                let user = $('#sel-user').val();
                let password = $('#txt-password').val();

                $.ajax({
                    type: "post",
                    url: "<?php echo base_url('Home/login'); ?>",
                    data: {
                        'user': user,
                        'password': password
                    },
                    dataType: "json",

                    success: function(jsonResponse) {

                        if (jsonResponse.error == 0) // SUCCESS AUTH
                            window.location.href = "<?php echo base_url('TPV'); ?>"
                        else if (jsonResponse.error == 1) { // ERROR AUTH

                            showToast('error', jsonResponse.msg); // SHOW MSG

                            if (jsonResponse.code == 100) // CASE INVALID PASSWORD SET INVALID CLASS TO INPUT
                                $('#txt-password').addClass('is-invalid');

                            $('#btn-login').removeAttr('disabled');
                        }
                    },
                    error: function(error) {
                        showToast('error', 'Ha ocurrido un error');
                    }
                });
            } else {
                showToast('error', 'Introduzca sus credenciales');
            }
        });
    });
</script>