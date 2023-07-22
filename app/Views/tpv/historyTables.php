<?php include('header.php'); ?>
<style>
    .card {
        background-color: #dce9f1;
    }
</style>
<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-12 text-end">
            <a class="btn btn-dark" href="<?php echo base_url('TPV'); ?>">Salir</a>
        </div>
    </div>
    <div class="card mt-2">
        <div class="card-header">
            <h5 class="text-dark">Historial de Mesas Cerradas</h5>
        </div>
        <div class="card-body">
            
            <div id="main-table-history"></div>
        </div>

    </div>
</div>

<script>
    getTableHistory();
    function getTableHistory() {

        $.ajax({
            type: "post",
            url: "<?php echo base_url('TPV/tableHistory'); ?>",
            dataType: "html",
            success: function(htmlResponse) {
                $('#main-table-history').html(htmlResponse);
            }
        });

    }
</script>