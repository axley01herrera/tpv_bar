<!-- DATA TABLE CSS -->
<link href="<?php echo base_url('assets/css/datatable/dataTables.bootstrap5.min.css'); ?>" rel="stylesheet" type="text/css" />

<h4 class="card-title mb-4">Listado de Recaudación Desglozado</h4>
<div class="table-responsive">
    <table id="dt-list-report" class="table table-hover table-borderless" style="width: 100%;">
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
</script>