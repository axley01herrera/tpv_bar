<div class="row">
    <div class="col-12">
        <?php for ($i = 0; $i < $countProducts; $i++) { ?>
            <button id="<?php echo $products[$i]->id; ?>" type="button" class="product btn btn-outline-purple mt-2 ml-2 ms-2 fs-5"><?php echo $products[$i]->name; ?> <br> <?php echo '€ ' . number_format($products[$i]->price, 2, ".", ','); ?></button>
        <?php } ?>
    </div>
</div>
<script>
    $('.product').on('click', function () {
        
    });
</script>