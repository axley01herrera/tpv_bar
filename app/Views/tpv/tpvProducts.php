<style>
    .scrollable-product {
        max-height: 400px;
        overflow-y: auto;
    }
</style>
<div class="row scrollable-product">
    <div class="col-12">
        <?php for ($i = 0; $i < $countProducts; $i++) { ?>
            <button data-id="<?php echo $products[$i]->id; ?>" type="button" class="product btn btn-outline-purple mt-2 ml-2 ms-2 fs-5"><?php echo $products[$i]->name; ?> <br> <?php echo '€ ' . number_format($products[$i]->price, 2, ".", ','); ?></button>
        <?php } ?>
    </div>
</div>
<script>
    $('.product').on('click', function () { // ADD PRODUCT TO TICKET

        let productID = $(this).attr('data-id');

        $.ajax({
            type: "post",
            url: "<?php echo base_url('TPV/createTicket'); ?>",
            data: {
               'tableID': tableID,
               'productID': productID
            },
            dataType: "html",
            success: function (htmlResponse) {
                $('#main-ticket').html(htmlResponse);
            },
            error: function (error) {
                showToast('error', 'Ha ocurrido un error');
            }
        });
        
    });
</script>