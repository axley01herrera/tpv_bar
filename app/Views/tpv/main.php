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

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-4 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-purple">Mesas</h5>
                        <div class="row">
                            <div class="col-12">
                                <?php for ($i = 1; $i <= 20; $i++) { ?>
                                    <button <?php
                                            if (in_array('S' . $i, array_column($table, 'tableID'))) { ?> class="btn btn-lg mt-3 ml-3 ms-3 btn-purple" disabled <?php } else { ?> class="btn btn-lg btn-soft-purple mt-3 ml-3 ms-3 btn-table" <?php } ?> value="<?php echo 'S' . $i; ?>"><?php echo 'S' . $i; ?>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-2">
                            <div class="col-12">
                                <?php for ($i = 1; $i <= 20; $i++) { ?>
                                    <button <?php
                                            if (in_array('T' . $i, array_column($table, 'tableID'))) { ?> class="btn btn-lg mt-3 ml-3 ms-3 btn-purple" disabled <?php } else { ?> class="btn btn-lg btn-soft-purple mt-3 ml-3 ms-3 btn-table" <?php } ?> value="<?php echo 'T' . $i; ?>"><?php echo 'T' . $i; ?>
                                    </button> <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-purple">Mesas Abiertas</h5>
                        <table class="table">
                            <thead>
                                <th class="text-purple"><strong>Mesa</strong></th>
                                <th class="text-purple"><strong>Fecha de Apertura</strong></th>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < $countTable; $i++) { ?>
                                    <tr>
                                        <td><?php echo $table[$i]->tableID; ?></td>
                                        <td><?php echo $table[$i]->dateOpen; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    $('.btn-table').on('click', function() {

        let value = $(this).val();
        console.log(value);

        $.ajax({
            type: "post",
            url: "<?php echo base_url('TPV/openTable'); ?>",
            data: {
                'table': value
            },
            dataType: "json",
            success: function(jsonResponse) {
                console.log(jsonResponse);

                if (jsonResponse.error == 0) { // SUCCESS
                    window.location.href = "<?php echo base_url('TPV/tpv') ?>" + '/' + jsonResponse.id;
                } else if (jsonResponse.error == 1) { // ERROR
                    showToast('error', 'Ha ocurrido un error');
                } else if (jsonResponse.error == 2) { // SESSION EXPIRED
                    window.location.href = '<?php echo base_url('Home'); ?>?msg=sessionExpired';
                }

            },
            error: function(error) {
                showToast('error', 'Ha ocurrido un error');
            }
        });

    });
</script>