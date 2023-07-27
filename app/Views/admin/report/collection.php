<!-- DATA TABLE CSS -->
<link href="<?php echo base_url('assets/css/datatable/dataTables.bootstrap5.min.css'); ?>" rel="stylesheet" type="text/css" />

<div class="col-12 col-lg-4">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Recaudación</h4>
            <div class="row align-items-center">
                <div class="row mb-3">
                    <div class="col-12 col-lg-6 text-center">
                        <p class="text-muted mb-2">Efectivo</p>
                        <h5><small class="badge badge-soft-success font-13 ms-2"><img src="<?php echo base_url('assets/images/dcash.png'); ?>" alt="cash" width="25px"></small> € <?php echo number_format($collectionDay['cash'], 2, ".", ','); ?></h5>
                    </div>
                    <div class="col-12 col-lg-6 text-center">
                        <p class="text-muted mb-2">Tarjeta</p>
                        <h5><small class="badge badge-soft-success font-13 ms-2"><img src="<?php echo base_url('assets/images/dcreditcard.png'); ?>" alt="credit card" width="25px"></small> € <?php echo number_format($collectionDay['card'], 2, ".", ','); ?></h5>
                    </div>
                    <div class="col-12 text-center">
                        <h4 class="mt-4 font-weight-bold mb-2">
                            Total: € <?php echo number_format($collectionDay['total'], 2, ".", ','); ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-4">Listado</h4>
        <div class="table-responsive">
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
</div>

<div class="col-12 text-end">
    <button id="btn-printReport" class="btn btn-success">Imprimir</button>
</div>

<!-- DATA TABLE JS -->
<script src="<?php echo base_url('assets/js/datatable/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/datatable/dataTables.bootstrap5.min.js'); ?>"></script>

<script>
    var dtListReport = $('#dt-list-report').DataTable({

        destroy: true,
        processing: false,
        serverSide: false,
        responsive: true,
        bAutoWidth: true,
        pageLength: 10,
        sort: false,
        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ],
        language: {
            url: '<?php echo base_url('assets/libs/dataTable/es.json'); ?>'
        },
    });

    $('#btn-printReport').on('click', function() {

        let dateStart = encodeURIComponent('<?php echo $dateStart; ?>');
        let dateEnd = encodeURIComponent('<?php echo $dateEnd; ?>');

        let url = "<?php echo base_url('Administrator/printReport'); ?>" + '?dateStart=' + dateStart + '&dateEnd=' + dateEnd;
        window.open(url, '_blank');

    });
</script>