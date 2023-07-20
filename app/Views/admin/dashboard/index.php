<h1 class="text-dark">Tablero</h1>

<!-- COLLECTION DAY -->
<div class="row">

    <div class="col-12 col-lg-4">
        <div id="main-collectionDay"></div>
        <div id="main-chartWeek"></div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-body">
            <h4 class="card-title mb-4">Historial de mesas cerradas</h4>
                <?php echo view('tpv/tableHistory'); ?>
            </div>
        </div>
    </div>

</div>

<script>
    getCollectionDay();
    getChartWeek();

    function getCollectionDay() {

        $.ajax({
            type: "post",
            url: "<?php echo base_url('Administrator/getCollectionDay'); ?>",
            dataType: "html",
            success: function(htmlResponse) {
                $('#main-collectionDay').html(htmlResponse);
            },
            error: function(error) {
                showToast('error', 'Ha ocurrido un error');
            }
        });
    }

    function getChartWeek() {

        $.ajax({
            type: "post",
            url: "<?php echo base_url('Administrator/getChartWeek'); ?>",
            dataType: "html",
            success: function(htmlResponse) {
                $('#main-chartWeek').html(htmlResponse);
            },
            error: function(error) {
                showToast('error', 'Ha ocurrido un error');
            }
        });
    }
</script>