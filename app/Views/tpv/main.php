<?php include('header.php'); ?>

<div class="container-fluid">

    <!-- TOP BAR -->

    <nav class="navbar navbar-expand-lg" style="position: top; background-color: #dce9f1;">
        <div class="container-fluid">
            <ul class="navbar-nav  me-5 mb-2 mb-lg-0 ">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-success" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo base_url('assets/images/users/user.png'); ?>" alt="user" class="img-fluid" width="50px">
                        <h5 class="text-dark text-center"><?php echo $user['name'] . ' ' . $user['lastName']; ?></h5>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="<?php echo base_url('TPV/historyTables'); ?>"> Historial de Mesas Cerradas</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo base_url('Home'); ?>"> Salir </a>
                        </li>
                    </ul>
                </li>

            </ul>
            <div class="d-flex">
                <h1><?php echo date('d/m/Y'); ?></h1>
            </div>
        </div>
    </nav>

    <!-- END TOP BAR -->

    <div class="row">

        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-body">

                    <h5 class="text-dark">Sala</h5>

                    <div class="row mb-5">
                        <div class="col-12">
                            <?php for ($i = 1; $i <= $hall; $i++) { ?>
                                <button <?php
                                        if (in_array('S' . $i, array_column($table, 'tableName'))) { ?> class="btn btn-lg mt-3 ml-3 ms-3 btn-dark" disabled <?php } else { ?> class="btn btn-lg btn-soft-dark mt-3 ml-3 ms-3 btn-table" <?php } ?> value="<?php echo 'S' . $i; ?>"><?php echo 'S' . $i; ?>
                                </button>
                            <?php } ?>
                        </div>
                    </div>

                    <h5 class="text-dark">Terraza</h5>

                    <div class="row mt-2">
                        <div class="col-12">
                            <?php for ($i = 1; $i <= $terrace; $i++) { ?>
                                <button <?php
                                        if (in_array('T' . $i, array_column($table, 'tableName'))) { ?> class="btn btn-lg mt-3 ml-3 ms-3 btn-dark" disabled <?php } else { ?> class="btn btn-lg btn-soft-dark mt-3 ml-3 ms-3 btn-table" <?php } ?> value="<?php echo 'T' . $i; ?>"><?php echo 'T' . $i; ?>
                                </button> <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-dark"><?php echo $countTable; ?> Mesas Abiertas</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless">
                            <thead>
                                <th class=""><strong>Mesa</strong></th>
                                <th class=""><strong>Fecha de Apertura</strong></th>
                                <th class=""><strong>Empleado</strong></th>
                                <th class=""><strong>Monto</strong></th>
                                <th class="text-end"></th>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < $countTable; $i++) { ?>

                                    <tr>
                                        <td>
                                            <a href="<?php echo base_url('TPV/tpv') . '/' . $table[$i]->tableID; ?>">
                                                <span class="badge badge-soft-dark"><?php echo $table[$i]->tableName; ?></span>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="text-dark" href="<?php echo base_url('TPV/tpv') . '/' . $table[$i]->tableID; ?>">
                                                <?php echo date('d/m/Y h:i A', strtotime($table[$i]->dateOpen)); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="text-dark" href="<?php echo base_url('TPV/tpv') . '/' . $table[$i]->tableID; ?>">
                                                <?php echo $table[$i]->name . ' ' . $table[$i]->lastName; ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="text-dark" href="<?php echo base_url('TPV/tpv') . '/' . $table[$i]->tableID; ?>">
                                                <?php echo '€ ' . number_format($table[$i]->price, 2, ".", ','); ?>
                                            </a>
                                        </td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-secondary" href="<?php echo base_url('TPV/tpv') . '/' . $table[$i]->tableID; ?>">
                                                <i class="mdi mdi-eye-settings-outline"></i>
                                            </a>
                                        </td>
                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>
<script>
    $('.btn-table').on('click', function() { // OPEN TABLE

        let value = $(this).val();

        $.ajax({
            type: "post",
            url: "<?php echo base_url('TPV/openTable'); ?>",
            data: {
                'table': value
            },
            dataType: "json",
            success: function(jsonResponse) {

                if (jsonResponse.error == 0) // SUCCESS
                    window.location.href = "<?php echo base_url('TPV/tpv') ?>" + '/' + jsonResponse.id;
                else if (jsonResponse.error == 1) // ERROR
                    showToast('error', 'Ha ocurrido un error');
                else if (jsonResponse.error == 2) // SESSION EXPIRED
                    window.location.href = '<?php echo base_url('Home'); ?>?msg=Su sesión ha expirado';

            },
            error: function(error) {
                showToast('error', 'Ha ocurrido un error');
            }
        });

    });
</script>