<style>
    .scrollable-product {
        max-height: 600px;
        overflow-y: auto;
    }
</style>
<div class="scrollable-product mt-5">
    <?php for ($i = 0; $i < $countProducts; $i++) { ?>
        <button data-id="<?php echo $products[$i]->id; ?>" type="button" class="product btn btn-sm btn-outline-dark mt-2 ml-2 ms-2"><?php echo $products[$i]->name; ?> <br> <?php echo '€ ' . number_format($products[$i]->price, 2, ".", ','); ?></button>
    <?php } ?>
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