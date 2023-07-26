<div class="row mt-5">
    <div class="col-12">
        <div id="main-collection"></div>
    </div>
</div>

<script>

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

    function getUserProduction() {

        $.ajax({
            type: "post",
            url: "<?php echo base_url('Administrator/getUserProduction'); ?>",
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
</script>