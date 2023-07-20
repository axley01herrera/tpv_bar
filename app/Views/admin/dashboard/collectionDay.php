<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-4">Recaudación <?php echo date("d/m/Y"); ?></h4>
        <div class="row align-items-center">
            <div class="row mb-3">
                <div class="col-12 col-lg-6">
                    <p class="text-muted mb-2">Efectivo</p>
                    <h5><small class="badge badge-soft-success font-13 ms-2"><img src="<?php echo base_url('assets/images/dcash.png'); ?>" alt="cash" width="25px"></small> € <?php echo number_format($collectionDay['cash'], 2,".",','); ?></h5>
                </div>
                <div class="col-12 col-lg-6">
                    <p class="text-muted mb-2">Tarjeta</p>
                    <h5><small class="badge badge-soft-success font-13 ms-2"><img src="<?php echo base_url('assets/images/dcreditcard.png'); ?>" alt="credit card" width="25px"></small> € <?php echo number_format($collectionDay['card'], 2,".",','); ?></h5>
                </div>
            </div>
        </div>
    </div>
</div>