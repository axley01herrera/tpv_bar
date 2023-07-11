<?php include('header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-end">
                <h1 class="mt-1 text-dark">Mesa: <?php echo $tableInfo[0]->tableID; ?> | <a class="text-danger" href="<?php echo base_url('TPV'); ?>">Salir</a></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="text-dark">Categorías</h5>
                        <div class="row">
                            <div class="col-12">
                                <button data-id="0" id="btn-all-products" class="btn active btn-lg btn-purple mt-2 ml-2 ms-2 cat">Todo</button>
                                <?php for ($i = 0; $i < $countCategory; $i++) { ?>
                                    <button data-id="<?php echo $category[$i]->id; ?>" class="btn btn-lg btn-soft-purple mt-2 ml-2 ms-2 cat"><?php echo $category[$i]->name; ?></button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="text-dark">Productos</h5>
                        <!-- MAIN PRODUCT -->
                        <div id="main-product">
                            <?php include('tpvProducts.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="text-dark">Ticket</h5>
                        <!-- MAIN TICKET-->
                        <div id="main-ticket" class="col-12">
                            <?php include('tpvTicket.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    var tableID = '<?php echo $tableID; ?>';
    $('.cat').on('click', function() { // FILTER BY CATEGORY

        let catgoryID = $(this).attr('data-id');

        if (catgoryID == 0)
            window.location.reload();
        else {

            $('#btn-all-products').removeClass('btn-purple');
            $('#btn-all-products').addClass('btn-soft-purple');

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