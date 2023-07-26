<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>BAR TPV</title>
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
    <div class="container mt-1">
        <div class="row">
            <div class="col-12 text-center">
                <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="TPV BAR">
            </div>
            <div class="col-12 text-center mt-2">
                <h3><?php echo $config[0]->name; ?></h3>
                <h3>Mesa: <?php echo $tableInfo[0]->tableID; ?></h3>
            </div>
            <div class="col-12">
                <table class="table">
                    <?php for($i = 0; $i < $countTicket; $i++) { ?>
                        <tr>
                            <td><?php echo $ticket[$i]->name.' x'.$ticket[$i]->productCount; ?></td>
                            <td><?php echo '€ ' . number_format($ticket[$i]->totalPrice, 2, ".", ','); ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            <div class="col-12 text-end ">
                <h5>Total: <span><?php echo '€ '.number_format($tableInfo[0]->amount, 2, ".", ','); ?></span></h5> 
            </div>
            <div class="col-12 text-end ">
                Impuestos Incluidos <br> 
            </div>
            <div class="col-12 text-end">
                <?php echo date('d/m/Y g:i a', strtotime($tableInfo[0]->dateClose)); ?> <br>
                <i class="mdi mdi-account"></i> <?php echo $employee[0]->user; ?>
            </div>
            <div class="col-12 text-center ">
                <h3>Gracias por su visita!</h3>
            </div>
        </div>
    </div>
</body>

<script>
    $(document).ready(function () {
        window.print();
    });
</script>