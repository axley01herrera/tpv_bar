<div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <!-- TITLE -->
                <h5 class="modal-title" id="staticBackdropLabel"><?php echo $title; ?></h5>
                <!-- CLOSE -->
                <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mt-2">
                        <label for="txt-name">Nombre del Producto</label>
                        <input id="txt-name" type="text" class="form-control modal-required focus" value="<?php echo @$user_data[0]->name; ?>">
                        <p id="msg-txt-name" class="text-danger text-end"></p>
                    </div>
                    <div class="col-6">
                        <label for="txt-cost">Precio</label>
                        <input id="txt-cost" type="text" class="form-control modal-required focus modal-email" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="<?php echo @$user_data[0]->cost; ?>">
                        <p id="msg-txt-cost" class="text-danger text-end"></p>
                    </div>
                    <div class="col-6">
                        <label for="sel-cat">Categoría</label>
                        <select id="sel-cat" class="form-control" style="width: 100%;">
                            <option value=""></option>
                        </select>
                        <p id="msg-sel-cat" class="text-danger text-end"></p>
                    </div>
                    <div class="col-12">
                        <label for="txt-cost">Descripción</label>
                        <textarea id="txt-description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <?php echo view('admin/modals/modalFooter'); ?>
        </div>
    </div>
</div>