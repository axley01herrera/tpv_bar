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
            <div class="col-12 text-end">
                <h1 class="mt-1 text-purple">Mesa: <?php echo $tableInfo[0]->tableID; ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="text-purple">Categorías</h5>
                        <div class="row">
                            <div class="col-12">
                                <button id="0" class="btn active btn-lg btn-purple mt-2 ml-2 ms-2 cat">Todo</button>
                                <?php for ($i = 0; $i < $countCategory; $i++) { ?>
                                    <button id="<?php echo $category[$i]->id; ?>" class="btn btn-lg btn-soft-purple mt-2 ml-2 ms-2 cat"><?php echo $category[$i]->name; ?></button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="text-purple">Productos</h5>
                        <div id="main-product">
                            <?php include('tpvProducts.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="text-purple">Ticket</h5>
                        <div class="row">
                            <div class="col-12 text-end">
                                <button class="btn btn-success">Cobrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    var tableID = '<?php echo $tableID; ?>';
    $('.cat').on('click', function() { // FILTER BY CATEGORY

        let catgoryID = $(this).attr('id');

        if (catgoryID == 0)
            window.location.reload();
        else {

            $('#0').removeClass('btn-purple');
            $('#0').addClass('btn-soft-purple');

            $.ajax({
                type: "post",
                url: "<?php echo base_url('TPV/getProductsbyCat'); ?>",
                data: {
                    'catgoryID': catgoryID
                },
                dataType: "html",

                success: function(htmlResponse) {
                    $('#main-product').html(htmlResponse);
                },
                error: function(error) {
                    showToast('error', 'Ha ocurrido un error');
                }
            });
        }
    });
</script>