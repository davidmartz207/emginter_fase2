<!-- Page content -->
<div class="page-content">

    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-archive"></i> Productos <small></small></h5>
        <div class="btn-group">
        </div>
    </div>
    <!-- /page title -->

    <div class="row">
        <div class="col-lg-12">
            <?php
             if(isset($sMsjError) and $sMsjError){
               show_msj_error($sMsjError,false);
             }elseif(isset($sMsjConf) and $sMsjConf){
               show_msj_confirmacion($sMsjConf,false);
             }
             echo '<br>';
             echo form_open_multipart('panel_products/upd/'.$id,'role="form" class="form-horizontal form-bordered"');
             ?>
                <input type="hidden" name="hddId" value="<?php echo $id; ?>">
                <input type="hidden" name="hddImage" value="<?php echo $imagen_actual; ?>">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">Editar producto: <?php echo isset($txtSKU) ? $txtSKU : ''; ?></h6>
                    </div>
                    <div class="panel-body">
                        <div class="form-actions text-right">
                            <input type="submit" name="btEditar" class="btn btn-primary" value="Editar Producto">
                        </div>
                        <div class="form-group">
                            <div class="col-lg-6">
                                <img src="<?php echo image($imagen_actual, 'product'); ?>" />
                            </div>
                            <div class="col-lg-6">
                                <label>Seleccione una nueva imagen sólo si desea cambiar la actual</label>
                                <input type=file size=60 name="filFILE"><br><br>
                                <p class="red">Recomedado tamaño de imagen 400x300 píxeles.</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Línea:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <select id="linea" name="selLinea" class="form-control" >
                                    <?php
                                    if(isset($lineas) and is_array($lineas)){
                                        echo '<option value="">-- Seleccione --</option>';
                                        foreach($lineas as $linea){
                                            $selected = $selLinea == $linea->key ? 'selected="selected"' : '';
                                            echo '<option value="'.$linea->key.'" '.$selected.'>'.ucfirst($linea->nombre).'</option>';
                                        }                             
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="selEstatus">Estatus:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="selEstatus" name="selEstatus">
                                    <option value="1" <?php echo $selEstatus==1 ? 'selected="selected"' : ''; ?>>Activo</option>
                                    <option value="0" <?php echo $selEstatus==0 ? 'selected="selected"' : ''; ?>>Inactivo</option>
                                </select>
                             </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Código SKU:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="txtSKU" name="txtSKU" 
                                 placeholder="" value="<?php echo isset($txtSKU) ? $txtSKU : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="selTiposProductos">Tipo de Producto:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-7">
                                <select class="form-control" id="selTiposProductos" name="selTiposProductos"></select>
                            </div>
                            <div class="col-sm-3">
                               <a href="#" id="btTiposProductos" name="btTiposProductos" class="btn btn-xs btn-default">Agregar producto</a>
                            </div>
                       </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="selNewRelease">New Release:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <select id="selNewRelease" name="selNewRelease" class="form-control">
                                    <?php 
                                    if(isset($arrNewRelease) and is_array($arrNewRelease)){
                                        echo '<option value="">-- Seleccione --</option>';
                                        foreach($arrNewRelease as $item){
                                            $selected = $selNewRelease == $item['id'] ? 'selected="selected"' : '';
                                            echo '<option value="'.$item['id'].'" '.$selected.'>'.$item['nombre'].'</option>';
                                        }                             
                                    }
                                    ?>
                                </select>
                             </div>
                       </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Descripción (Ingles):<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <textarea id="txtaDescripcionEn" name="txtaDescripcionEn" class="form-control" cols="5" rows="5"><?php echo isset($txtaDescripcionEn) ? $txtaDescripcionEn : ''; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Descripción (Español):</label>
                            <div class="col-sm-10">
                                <textarea id="txtaDescripcionEs" name="txtaDescripcionEs" class="form-control" cols="5" rows="5"><?php echo isset($txtaDescripcionEs) ? $txtaDescripcionEs : ''; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Precio / Sell Price</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="txtSellPrice" name="txtSellPrice" 
                                 placeholder="" value="<?php echo isset($txtSellPrice) ? $txtSellPrice : ''; ?>">
                            </div>
                        </div>

                        <fieldset id="fieldset_competition" class="col-lg-12">
                            <legend>Competition Part Number</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">OEM:<span class="asterisco_rojo">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="txtOEM" name="txtOEM" 
                                     data-role="tagsinput" value="<?php echo isset($txtOEM) ? $txtOEM : ''; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" id="label1">SMP:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="txtSMP" name="txtSMP" 
                                     data-role="tagsinput" value="<?php echo isset($txtSMP) ? $txtSMP : ''; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" id="label2">Wells:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="txtWells" name="txtWells" 
                                     data-role="tagsinput" value="<?php echo isset($txtWells) ? $txtWells : ''; ?>">
                                </div>
                            </div>
                            <div  id="fieldset_mrc">
                            <div class="form-group" >
                                <label class="col-sm-2 control-label">DAI:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="txtWells" name="txtDai"
                                           data-role="tagsinput" value="<?php echo isset($txtDai) ? $txtDai : ''; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Notes:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="notes" name="notes"
                                           value="<?php echo isset($notes) ? $notes : ''; ?>">
                                </div>
                            </div>
                            </div>
                        </fieldset>
                        <fieldset id="fieldset_perfection" style="display:none;" class="col-lg-12">
                            <legend>Perfection</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Flywheel:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="flywheel" name="flywheel"
                                            value="<?php echo isset($flywheel) ? $flywheel : ''; ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Cover:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="cover" name="cover"
                                            value="<?php echo isset($cover) ? $cover : ''; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Disc Diameter <br> N. Disc Splines <br> Disc Hub Size</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="disc_info" name="disc_info"
                                            value="<?php echo isset($disc_info) ? $disc_info : ''; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Notes:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="notesmrc" name="notesmrc"
                                           value="<?php echo isset($notes) ? $notes : ''; ?>">
                                </div>
                            </div>
                        </fieldset>
                        <div class="form-actions text-right">
                            <input type="submit" name="btEditar" class="btn btn-primary" value="Editar Producto">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /with titles (frame) -->
</div>
