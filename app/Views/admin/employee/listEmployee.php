<!-- DATA TABLE CSS -->
<link href="<?php echo base_url('assets/css/datatable/dataTables.bootstrap5.min.css'); ?>" rel="stylesheet" type="text/css" />

<h1 class="text-dark">Empleados</h1>

<div class="card">
    <div class="card-header">
        <button id="btn-create-employee" class="btn btn-primary">Nuevo Empleado</button>
    </div>
    <div class="card-body">
        <table id="dt-employee" class="table table-hover table-borderless" style="width: 100%;">
            <thead>
                <tr>
                    <th><strong>Nombre</strong></th>
                    <th><strong>Apellidos</strong></th>
                    <th><strong>Usuario</strong></th>
                    <th class="text"><strong>Estado</strong></th>
                    <th class=""></th>
                    <th class="text-end"></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- DATA TABLE JS -->
<script src="<?php echo base_url('assets/js/datatable/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/datatable/dataTables.bootstrap5.min.js'); ?>"></script>

<script>
    $('#btn-create-employee').on('click', function() { // CREATE EMPLOYEE

        $('#btn-create-employee').attr('disabled', true);

        $.ajax({
            type: "post",
            url: "<?php echo base_url('Administrator/showModalEmployee'); ?>",
            data: {
                'action': 'create'
            },
            dataType: "html",
            success: function(htmlResponse) {

                $('#btn-create-employee').removeAttr('disabled');
                $('#main-modal').html(htmlResponse);

            },
            error: function(error) {
                showToast('error', 'Ha ocurrido un error');
            }
        });
    });

    var dtEmployee = $('#dt-employee').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        responsive: true,
        bAutoWidth: true,
        pageLength: 10,
        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ],
        language: {
            url: '<?php echo base_url('assets/libs/dataTable/es.json'); ?>'
        },
        ajax: {
            url: "<?php echo base_url('Administrator/dtProcessingEmployees'); ?>",
            type: "POST"
        },
        order: [
            [0, 'asc']
        ],
        columns: [{
                data: 'name'
            },
            {
                data: 'lastName'
            },
            {
                data: 'user'
            },
            {
                data: 'status',
                orderable: false,
                searchable: false
            },
            {
                data: 'switch',
                orderable: false,
                searchable: false
            },
            {
                data: 'action',
                class: 'text-end',
                orderable: false,
                searchable: false
            }
        ],
    });

    dtEmployee.on('click', '.switch', function(event) { // ACTIVE OR INACTIVE EMPLOYEE

        let id = $(this).attr('data-id');
        let status = $(this).attr('data-status');
        let newStatus = '';

        if (status == 0)
            newStatus = 1;
        else if (status == 1)
            newStatus = 0;

        $.ajax({

            type: "post",
            url: "<?php echo base_url('Administrator/changeEmployeeStatus'); ?>",
            data: {
                'id': id,
                'status': newStatus
            },
            dataType: "json",

        }).done(function(jsonResponse) {

            if (jsonResponse.error == 0) { // SUCCESS

                showToast('success', jsonResponse.msg);
                dtEmployee.draw();
            } else { // ERROR

                showToast('success', jsonResponse.msg);

                if (jsonResponse.code == 103) // SESSION EXPIRED
                    window.location.href = '<?php echo base_url('Home'); ?>?msg=Sesion Expirada';
            }
        }).fail(function(error) {
            showToast('error', 'Ha ocurrido un error');
        });
    });

    dtEmployee.on('click', '.btn-actions-clave', function(event) { // SET OR UPDATE CLAVE TO EMPLOYEE

        event.preventDefault();

        $.ajax({
            type: "post",
            url: "<?php echo base_url('Administrator/showModalSetClave'); ?>",
            data: {
                'id': $(this).attr('data-id'),
                'action': $(this).attr('data-action'),
            },
            dataType: "html",
        }).done(function(htmlResponse) {
            $('#main-modal').html(htmlResponse);
        }).fail(function(error) {
            showToast('error', 'Ha ocurrido un error');
        });
    });

    dtEmployee.on('click', '.btn-edit-employee', function(event) { // UPDATE EMPLOYEE

        event.preventDefault();

        $.ajax({
            type: "post",
            url: "<?php echo base_url('Administrator/showModalEmployee'); ?>",
            data: {
                'userID': $(this).attr('data-id'),
                'action': 'update',
            },
            dataType: "html",
        }).done(function(htmlRespnse) {
            $('#main-modal').html(htmlRespnse);
        }).fail(function(error) {
            showToast('error', 'Ha ocurrido un error');
        });
    });
</script>