<h5 class="text-dark">Tablero</h5>
<!-- COLLECTION DAY -->
<div class="row">

    <div class="col-12 col-lg-4">
        <div id="main-collectionDay"></div>
        <div id="main-chartWeek"></div>
        <div id="main-employees"></div>
        <div id="main-products"></div>
    </div>

    <div class="col-12 col-lg-8">
        <div id="main-chartMont"></div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Historial de Mesas Cerradas</h4>
                <?php echo view('tpv/tableHistory'); ?>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Historial de Mesas Canceladas</h4>
                <?php echo view('tpv/historyCancelTable'); ?>
            </div>
        </div>
    </div>

</div>

<script>
    var config = '<?php echo $config[0]->name; ?>';
    if (config == '')
        $('#btn-config').trigger('click');

    getCollectionDay();
    getChartWeek();
    getChartMont();
    getEmployeesInfo();
    getProductInfo();

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

    function getChartMont(year = '') {

        $.ajax({
            type: "post",
            url: "<?php echo base_url('Administrator/getChartMont'); ?>",
            data: {
                'year': year
            },
            dataType: "html",
            success: function(htmlResponse) {
                $('#main-chartMont').html(htmlResponse);
            },
            error: function(error) {
                showToast('error', 'Ha ocurrido un error');
            }
        });
    }

    function getEmployeesInfo() {

        $.ajax({
            type: "post",
            url: "<?php echo base_url('Administrator/getEmployeesInfo'); ?>",
            dataType: "html",
            success: function(htmlResponse) {
                $('#main-employees').html(htmlResponse);
            },
            error: function(error) {
                showToast('error', 'Ha ocurrido un error');
            }
        });
    }

    function getProductInfo() {

        $.ajax({
            type: "post",
            url: "<?php echo base_url('Administrator/getProductInfo'); ?>",
            dataType: "html",
            success: function(htmlResponse) {
                $('#main-products').html(htmlResponse);
            },
            error: function(error) {
                showToast('error', 'Ha ocurrido un error');
            }
        });
    }
</script>