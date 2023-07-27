<div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <!-- TITLE -->
                <h5 class="modal-title" id="staticBackdropLabel"><?php echo $title; ?></h5>
                <!-- CLOSE -->
                <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- MODAL CONTENT -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-6 text-center">
                        <input id="radio-e" type="radio" class="radio-payType" name="radio-payType" value="1" /> <label for="radio-e">Efectivo</label>
                    </div>
                    <div class="col-6 text-center">
                        <input id="radio-t" type="radio" class="radio-payType" name="radio-payType" value="2" /> <label for="radio-t">Tarjeta</label>
                    </div>

                </div>
            </div>
            <?php echo view('admin/modals/modalFooter'); ?>
        </div>
    </div>
</div>

<script>
    var payType = '<?php echo @$payType; ?>';

    $('.radio-payType').on('click', function() {
        payType = $(this).val();
    });

    $('#btn-modal-submit').on('click', function() {

        if (payType != '') {

            $('#btn-modal-submit').attr('disabled', true);

            $.ajax({
                type: "post",
                url: "<?php echo base_url('TPV/chargeTable'); ?>",
                data: {
                    'tableID': '<?php echo $tableID; ?>',
                    'amount': '<?php echo $amount; ?>',
                    'payType': payType,
                },
                dataType: "json",
                success: function(jsonResponse) {

                    if (jsonResponse.error == 0) { // SUCCESS
                        showToast('success', jsonResponse.msg);
                        closeModal();
                        let id = <?php echo $tableID; ?>;
                        let url = "<?php echo base_url('TPV/printTicket'); ?>" + '/' + id;
                        window.open(url, '_blank');
                        window.location.href = '<?php echo base_url('TPV'); ?>'
                    } else { // ERROR
                        showToast('error', jsonResponse.msg);

                        if (jsonResponse.error == 2) // ERROR SESSION EXPIRED
                            window.location.href = '<?php echo base_url('Home'); ?>?msg=Su sesión ha expirado';
                    }

                },
                error: function(error) {
                    showToast('error', 'Debe seleccionar un método de pago');
                }
            });

        } else // ERROR REQUIRED SELECT PAYMENT TYPE
            showToast('error', 'Debe seleccionar un método de pago');

    });
</script>