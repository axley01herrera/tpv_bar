<div class="card mt-2">
    <div class="card-body">
        <div class="row ">
            <div class="col-12">
                <div id="dt-report"></div>
            </div>
            <hr class="mt-2">
            <div class="col-12 mt-2 text-end">
                <div id="main-collection"></div>
            </div>
            <div class="col-12 text-end">
                <button id="btn-printReport" class="btn btn-success">Imprimir</button>
            </div>
        </div>
    </div>
</div>

<script>
    dtReport();
    getCollectionReport();

    function getCollectionReport() {

        $.ajax({
            type: "post",
            url: "<?php echo base_url('Administrator/getCollectionReport'); ?>",
            data: {
                'dateStart': '<?php echo $dateStart; ?>',
                'dateEnd': '<?php echo $dateEnd; ?>'
            },
            dataType: "html",
            success: function(response) {
                $('#main-collection').html(response);
            },
            error: function(error) {
                showToast('error', 'Ha ocurrido un error');
            }
        });
    }

    function dtReport() {

        $.ajax({
            type: "post",
            url: "<?php echo base_url('Administrator/dtReport'); ?>",
            data: {
                'dateStart': '<?php echo $dateStart; ?>',
                'dateEnd': '<?php echo $dateEnd; ?>'
            },
            dataType: "html",
            success: function(response) {
                $('#dt-report').html(response);
            },
            error: function(error) {
                showToast('error', 'Ha ocurrido un error');
            }
        });
    }

    $('#btn-printReport').on('click', function() {

        let dateStart = encodeURIComponent('<?php echo $dateStart; ?>');
        let dateEnd = encodeURIComponent('<?php echo $dateEnd; ?>');

        let url = "<?php echo base_url('Administrator/printReport'); ?>" + '?dateStart=' + dateStart + '&dateEnd=' + dateEnd;
        window.open(url, '_blank');

    });
</script>