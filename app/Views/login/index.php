<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>TPV</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="TPV" name="description" />
    <meta content="Axley Herrera" name="author" />

    <link rel="shortcut icon" href="<?php echo base_url('assets/images/app-icon.ico'); ?>">
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/css/icons.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/css/app.min.css'); ?>" id="app-style" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/libs/sweetalert/sweetalert2.css'); ?>" id="app-style" rel="stylesheet" type="text/css" />

    <script src="<?php echo base_url('assets/libs/jquery/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/libs/metismenujs/metismenujs.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/libs/simplebar/simplebar.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/libs/feather-icons/feather.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/libs/sweetalert/sweetalert2.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/customApp.js'); ?>"></script>
</head>

<body>
    <div class="authentication-bg min-vh-100">
        <div class="bg-overlay bg-white"></div>
        <div class="container">
            <div class="d-flex flex-column min-vh-100 px-3 pt-4">
                <div class="row justify-content-center my-auto">
                    <div class="col-md-8 col-lg-6 col-xl-4">
                        <div class="text-center py-5">
                            <div class="user-thumb mb-4 mb-md-5">
                                <img src="<?php echo base_url('assets/images/users/user.png'); ?>" class="rounded-circle img-thumbnail avatar-lg" alt="thumbnail">
                                <h1>Bienvenido</h1>
                                <h5>Inicia sessión para continuar</h5>
                            </div>
                            <div class="form-floating form-floating-custom mb-3">
                                <select id="input-email" class="form-select required focus">
                                    <option hidden value="">Seleccione su usuario</option>
                                    <?php
                                    for ($i = 0; $i < $countUsers; $i++) {
                                    ?>
                                        <option value="<?php echo $users[$i]->id; ?>"><?php echo $users[$i]->user; ?></option>
                                    <?php } ?>
                                </select>
                                <label for="input-email">Email</label>
                                <div class="form-floating-icon">
                                    <i class="uil uil-envelope-alt"></i>
                                </div>
                            </div>
                            <div class="form-floating form-floating-custom mb-3">
                                <input type="password" id="input-password" class="form-control required focus" placeholder="Contraseña">
                                <label for="input-password">Contraseña</label>
                                <div class="form-floating-icon">
                                    <i class="uil uil-padlock"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button id="btn-submit" type="button" class="btn btn-info w-100" type="button">Entrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<?php echo view('global/form_validation'); ?>

<script>
    $(document).ready(function() {

        let msg = '<?php echo @$msg; ?>';

        if(msg != '')
            showToast('error', msg);

        $('#btn-submit').on('click', function() {
            let resultCheckRequiredValues = checkRequiredValues('required');
            if (resultCheckRequiredValues == 0) {
                $.ajax({
                    type: "post",
                    url: "<?php echo base_url('Home/login'); ?>",
                    data: {
                        'user': $('#input-email').val(),
                        'pass': $('#input-password').val()
                    },
                    dataType: "json",
                    success: function(jsonResponse) {
                        if (jsonResponse.error == 0) {
                            window.location.href = "<?php echo base_url('TPV'); ?>"
                        } else if (jsonResponse.error == 1) {
                            showToast('error', jsonResponse.msg);
                            if (jsonResponse.msg == 'Rectifique su contraseña')
                                $('#input-password').addClass('is-invalid');
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