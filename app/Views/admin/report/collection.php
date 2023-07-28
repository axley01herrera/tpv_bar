
    <div class="row mb-3">
        <div class="col-12">
            <p class="text-muted mb-2">Efectivo</p>
            <h5>€ <?php echo number_format($collectionDay['cash'], 2, ".", ','); ?></h5>
        </div>
        <div class="col-12">
            <p class="text-muted mb-2">Tarjeta</p>
            <h5>€ <?php echo number_format($collectionDay['card'], 2, ".", ','); ?></h5>
        </div>
        <div class="col-12">
            <h4 class="mt-4 font-weight-bold mb-2">
                Total: € <?php echo number_format($collectionDay['total'], 2, ".", ','); ?>
            </h4>
        </div>
    </div>
