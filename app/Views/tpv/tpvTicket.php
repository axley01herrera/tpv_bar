<style>
    .scrollable-ticket {
        max-height: 600px;
        overflow-y: auto;
    }
</style>
<div class="row scrollable-ticket">
    <?php for ($i = 0; $i < $countTicket; $i++) { ?>

        <div class="col-12">
            <div class="row mt-1">
                <div class="col-6 text-purple"><?php echo $ticket[$i]->name; ?></div>
                <div class="col-4"><?php echo '€ ' . number_format($ticket[$i]->price, 2, ".", ','); ?></div>
                <div class="col-2"><button data-id="<?php echo $ticket[$i]->ticketID; ?>" class="btn btn-sm btn-clean del-ticket"><i class="text-danger mdi mdi-trash-can-outline"></i></button></div>
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

    <div class="row">
        <div class="col-12 mt-5">
            <button class="btn btn-success">Cobrar</button>
        </div>
    </div>

<?php } ?>

<script>
    $('.del-ticket').on('click', function() {

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