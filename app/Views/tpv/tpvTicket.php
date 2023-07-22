<style>
    .scrollable-ticket {
        max-height: 600px;
        overflow-y: auto;
    }
</style>
<?php 
    $total = 0;
?>
<div class="row scrollable-ticket">
    <?php 
        for ($i = 0; $i < $countTicket; $i++) {  

            $total = $total + $ticket[$i]->price;
    ?>

        <div class="col-12 mt-2">
            <div class="row">
                <div class="col-6 text-dark"><?php echo $ticket[$i]->name; ?></div>
                <div class="col-4 text-dark"><?php echo '€ ' . number_format($ticket[$i]->price, 2, ".", ','); ?></div>
                <div class="col-2"><button data-id="<?php echo $ticket[$i]->ticketID; ?>" class="btn btn-sm btn-outline-light del-ticket"><i class="text-danger mdi mdi-trash-can-outline"></i></button></div>
            </div>
        </div>
        

    <?php }
    if ($countTicket == 0) { ?>

        <div class="col-12 text-center">
            <span class="badge badge-outline-danger">No hay productos añadidos</span>
        </div>

    <?php } ?>

</div>

<?php if ($countTicket >  0) { ?>

    <div class="row mt-5">
        <div class="col-12 text-end">
            <h5>Total: <span><?php echo '€ '.number_format($total, 2, ".", ','); ?></span></h5> 
        </div>
        <div class="col-12 text-end">
            <button id="btn-charge" class="btn btn-sm btn-success">Cobrar</button>
        </div>
        
    </div>

<?php } ?>

<script>

    $('#btn-charge').on('click', function () {

        $.ajax({
            type: "post",
            url: "<?php echo base_url('TPV/showModalChargeType'); ?>",
            data: {
                'tableID': tableID,
                'amount': '<?php echo number_format($total, 2, ".", ','); ?>'
            },
            dataType: "html",
            success: function (htmlResponse) {
                $('#main-modal').html(htmlResponse);
            },
            error: function (error) {
                showToast('error', 'Ha ocurrido un error');
            }
        });
        
    });

    $('.del-ticket').on('click', function() { // DELETE ITEM FROM TICKET

        let ticketID = $(this).attr('data-id');

        $.ajax({
            type: "post",
            url: "<?php echo base_url('TPV/deleteTicket'); ?>",
            data: {
                'tableID': tableID,
                'ticketID': ticketID
            },
            dataType: "html",
            success: function (htmlResponse) {
                $('#main-ticket').html(htmlResponse);
            },
            error : function (error) {
                showToast('error', 'Ha ocurrido un error');
            }
        });

    });


</script>