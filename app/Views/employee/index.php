<!-- CSS-->
<link href="<?php echo base_url('assets/css/datatable/dataTables.bootstrap5.min.css'); ?>" rel="stylesheet" type="text/css" />

<div class="row">
    <div class="col-12">
        <h1 class="text-primary">Empleados</h1>
    </div>
</div>
<div class="card mt-3">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <button id="btn-create" class="btn btn-primary">Crear Empleado</button>
            </div>
        </div>
        <div class="table-responsive mt-5">
            <table id="dataTable" class="table" style="width: 100%;">
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
</div>

<!-- JS -->
<script src="<?php echo base_url('assets/js/datatable/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/datatable/dataTables.bootstrap5.min.js'); ?>"></script>

<script>
    $('#btn-create').on('click', function() { // CREATE EMPLOYEE
        $.ajax({
            type: "post",
            url: "<?php echo base_url('Employee/showModalEmployee'); ?>",
            data: {
                'action': 'create'
            },
            dataType: "html",
            success: function(htmlResponse) {
                $('#main-modal').html(htmlResponse);
            },
            error: function(error) {
                showToast('error', 'Ha ocurrido un error');
            }
        });
    }); // OK

    var dataTable = $('#dataTable').DataTable({
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
            url: "<?php echo base_url('Employee/processingEmployee'); ?>",
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
    }); // OK

    dataTable.on('click', '.switch', function(event) { // ACTIVE OR INACTIVE

        let userID = $(this).attr('data-id');
        let status = $(this).attr('data-status');
        let newStatus = '';

        if (status == 0)
            newStatus = 1;
        else if (status == 1)
            newStatus = 0;

        $.ajax({

            type: "post",
            url: "<?php echo base_url('Employee/changeUserStatus'); ?>",
            data: {
                'userID': userID,
                'status': newStatus
            },
            dataType: "json",

        }).done(function(jsonResponse) {

            if (jsonResponse.error == 0) // SUCCESS
            {
                showToast('success', jsonResponse.msg);
                dataTable.draw();
            } else // ERROR
                showToast('success', jsonResponse.msg);

            if (jsonResponse.error == 2) // SESSION EXPIRED
                window.location.href = '<?php echo base_url('Admin'); ?>?msg="sessionExpired"';

        }).fail(function(error) {
            showToast('error', 'Ha ocurrido un error');
        });
    }); // OK

    dataTable.on('click', '.btn-actions-clave', function(event) { // SET OR UPDATE CLAVE
        event.preventDefault();
        $.ajax({
            type: "post",
            url: "<?php echo base_url('Employee/showModalSetClave'); ?>",
            data: {
                'userID': $(this).attr('data-id'),
                'action': $(this).attr('data-action'),
            },
            dataType: "html",
        }).done(function(htmlResponse) {
            $('#main-modal').html(htmlResponse);
        }).fail(function(error) {
            showToast('error', 'Ha ocurrido un error');
        });
    }); // OK

    dataTable.on('click', '.btn-edit-employee', function(event) { // UPDATE
        event.preventDefault();
        $.ajax({
            type: "post",
            url: "<?php echo base_url('Employee/showModalEmployee'); ?>",
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
    }); // OK
</script>