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
                                <a class="dropdown-item" href="<?php echo base_url('Home'); ?>"> Salir </a>
                            </li>
                        </ul>
                    </li>
                </ul>
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
                                <?php for ($i = 1; $i <= 50; $i++) { ?>
                                    <button <?php
                                            if (in_array('S' . $i, array_column($table, 'tableName'))) { ?> class="btn btn-lg mt-3 ml-3 ms-3 btn-purple" disabled <?php } else { ?> class="btn btn-lg btn-soft-purple mt-3 ml-3 ms-3 btn-table" <?php } ?> value="<?php echo 'S' . $i; ?>"><?php echo 'S' . $i; ?>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>

                        <h5 class="text-dark">Terraza</h5>

                        <div class="row mt-2">
                            <div class="col-12">
                                <?php for ($i = 1; $i <= 50; $i++) { ?>
                                    <button <?php
                                            if (in_array('T' . $i, array_column($table, 'tableName'))) { ?> class="btn btn-lg mt-3 ml-3 ms-3 btn-purple" disabled <?php } else { ?> class="btn btn-lg btn-soft-purple mt-3 ml-3 ms-3 btn-table" <?php } ?> value="<?php echo 'T' . $i; ?>"><?php echo 'T' . $i; ?>
                                    </button> <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 ">
                <div class="card">
                    <div class="card-body">

                        <h5 class="text-darj">Mesas Abiertas</h5>

                        <table class="table table-hover table-borderless">
                            <thead>

                                <th class="text-purple text-center"></th>
                                <th class="text-purple text-center"></th>
                                <th class="text-purple text-center"></th>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < $countTable; $i++) { ?>

                                    <tr>
                                        <td class="text-center"><a href="<?php echo base_url('TPV/tpv') . '/' . $table[$i]->tableID; ?>">
                                                <h5><span class="badge badge-soft-purple"><?php echo $table[$i]->tableName; ?></span></h5>
                                            </a></td>
                                        <td class="text-center"><a href="<?php echo base_url('TPV/tpv') . '/' . $table[$i]->tableID; ?>">
                                                <h5 class="text-purple"><?php echo date('d-m-Y h:i:s A', strtotime($table[$i]->dateOpen)); ?></h5>
                                            </a></td>
                                        <td class="text-center"><a href="<?php echo base_url('TPV/tpv') . '/' . $table[$i]->tableID; ?>">
                                                <h5 class="text-success"><?php echo '€ ' . number_format($table[$i]->price, 2, ".", ','); ?></h5>
                                            </a></td>
                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>
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

                if (jsonResponse.error == 0)  // SUCCESS
                    window.location.href = "<?php echo base_url('TPV/tpv') ?>" + '/' + jsonResponse.id;
                else if (jsonResponse.error == 1)  // ERROR
                    showToast('error', 'Ha ocurrido un error');
                else if (jsonResponse.error == 2)  // SESSION EXPIRED
                    window.location.href = '<?php echo base_url('Home'); ?>?msg=Sesión Expireda';
                
            },
            error: function(error) {
                showToast('error', 'Ha ocurrido un error');
            }
        });

    });
</script>