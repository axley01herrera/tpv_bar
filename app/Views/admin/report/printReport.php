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
    <div class="container mt-5">
        <div class="col-4">
            <h4 class="card-title mb-4">Recaudación</h4>
            <div class="row align-items-center">
                <div class="row mb-3">
                    <div class="col-12 col-lg-6">
                        <p class="text-muted mb-2">Efectivo</p>
                        <h5><small class="badge badge-soft-success font-13 ms-2"><img src="<?php echo base_url('assets/images/dcash.png'); ?>" alt="cash" width="25px"></small> € <?php echo number_format($collectionDay['cash'], 2, ".", ','); ?></h5>
                    </div>
                    <div class="col-12 col-lg-6">
                        <p class="text-muted mb-2">Tarjeta</p>
                        <h5><small class="badge badge-soft-success font-13 ms-2"><img src="<?php echo base_url('assets/images/dcreditcard.png'); ?>" alt="credit card" width="25px"></small> € <?php echo number_format($collectionDay['card'], 2, ".", ','); ?></h5>
                    </div>
                </div>
            </div>
            <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center">
                Total: € <?php echo number_format($collectionDay['total'], 2, ".", ','); ?>
            </h4>
        </div>
        <div class="col-12">
            <table id="dt-list-report" class="table table-hover table-borderless">
                <thead style="background-color: #dce9f1;">
                    <th><strong>Fecha</strong></th>
                    <th class="text-center"><strong>Tipo de Pago</strong></th>
                    <th class="text-end"><strong>Monto</strong></th>
                </thead>
                <tbody>
                    <?php
                    $count = sizeof($collectionDay['data']);
                    for ($i = 0; $i < $count; $i++) {
                    ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($collectionDay['data'][$i]->date)); ?></td>
                            <td class="text-center">
                                <?php if ($collectionDay['data'][$i]->payType == 1) { ?>
                                    <img src="<?php echo base_url('assets/images/dcash.png'); ?>" alt="cash" width="25px">
                                <?php } else if ($collectionDay['data'][$i]->payType == 2) { ?>
                                    <img src="<?php echo base_url('assets/images/dcreditcard.png'); ?>" alt="credit card" width="25px">
                                <?php } ?>
                            </td>
                            <td class="text-end">€ <?php echo number_format($collectionDay['data'][$i]->amount, 2, ".", ','); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

<script>
    $(document).ready(function () {
        window.print();
    });
</script>