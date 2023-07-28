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
        <div class="row">
            <div class="col-12">
                <h4 class="card-title mb-4">Recaudación del <?php echo date('d/m/Y', strtotime($dateStart)); ?> al <?php echo date('d/m/Y', strtotime($dateEnd)); ?></h4>
                <div class="row align-items-center">
                    <div class="row mb-3">
                        <div class="col-12">
                            <p class="text-muted mb-2">Total de Efectivo</p>
                            <h5>€ <?php echo number_format($collectionDay['cash'], 2, ".", ','); ?></h5>
                        </div>
                        <div class="col-12 ">
                            <p class="text-muted mb-2">Total de Tarjeta</p>
                            <h5>€ <?php echo number_format($collectionDay['card'], 2, ".", ','); ?></h5>
                        </div>
                        <div class="col-12">
                            <h4 class="mt-4 font-weight-bold mb-2">
                                Total: € <?php echo number_format($collectionDay['total'], 2, ".", ','); ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="table-responsive">
                    <table id="dt-list-report" class="table table-hover table-borderless">
                        <thead style="background-color: #dce9f1;">
                            <th><strong>Fecha</strong></th>
                            <th class="text-end"><strong>Efectivo</strong></th>
                            <th class="text-end"><strong>Tarjeta</strong></th>
                            <th class="text-end"><strong>Total</strong></th>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < $totalRows; $i++) { ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($dataTable[$i]->date)); ?></td>
                                    <td class="text-end">€ <?php echo number_format($dataTable[$i]->cash, 2, ".", ','); ?></td>
                                    <td class="text-end">€ <?php echo number_format($dataTable[$i]->creditCard, 2, ".", ','); ?></td>
                                    <td class="text-end">€ <?php echo number_format($dataTable[$i]->total, 2, ".", ','); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</body>

<script>
    $(document).ready(function() {
        window.print();
    });
</script>