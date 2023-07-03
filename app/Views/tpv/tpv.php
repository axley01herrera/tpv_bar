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
            <div class="col-12 col-lg-2 mt-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-purple">Mesas</h5>
                        <div class="row">
                            <div class="col-12">
                                <?php for($i = 1; $i <= 20; $i++) {?>
                                    <button class="btn btn-soft-purple mt-2 ml-2 ms-2 btn-table" value="<?php echo 'S'.$i; ?>"><?php echo 'S'.$i; ?></button>
                                <?php } ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-2">
                            <div class="col-12">
                                <?php for($i = 1; $i <= 20; $i++) {?>
                                    <button class="btn btn-soft-purple mt-2 ml-2 ms-2 btn-table" value="<?php echo 'T'.$i; ?>"><?php echo 'T'.$i; ?></button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-purple">Mesas Abiertas</h5>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="text-purple">Categorías</h5>
                        <div class="row">
                            <div class="col-12">
                                <?php for($i = 0; $i < $countCategory; $i++) {?>
                                <button id="<?php echo $category[$i]->id; ?>" class="btn btn-lg btn-soft-purple mt-2 ml-2 ms-2"><?php echo $category[$i]->name; ?></button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="text-purple">Productos</h5>
                        <div class="row">
                            <div class="col-12">
                                <?php for($i = 0; $i < $countProducts; $i++) {?>
                                    <button type="button" class="btn btn-outline-purple mt-2 ml-2 ms-2"><?php echo $products[$i]->name; ?> <br> <?php echo '€ '.number_format($products[$i]->price, 2,".",','); ?></button>                                
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="text-purple">Ticket</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>