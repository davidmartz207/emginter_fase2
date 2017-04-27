<!-- Page content -->
<div class="page-content">

    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-archive"></i> Mini Banners <small></small></h5>
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
             echo form_open_multipart('panel_banners/upd/'.$id,'role="form" class="form-horizontal form-bordered"');
             ?>
                <input type="hidden" name="hddId" value="<?php echo $id; ?>">
                <input type="hidden" name="hddImage" value="<?php echo $imagen_actual; ?>">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">Editar
                        </h6>
                    </div>
                    <div class="panel-body">
                        <div class="form-actions text-right">
                            <input type="submit" name="btEditar" class="btn btn-primary" value="Editar">
                       </div>
                        <div class="form-group">
                            <div class="col-lg-7">
                                <img src="<?php echo image('media/banners/'.$id.'/'.$imagen_actual, 'publi_image'); ?>" />
                            </div>
                            <div class="col-lg-5">
                                <label>Seleccione una nueva imagen sólo si desea cambiar la actual</label>
                                <input type=file size=60 name="filFILE"><br><br>
                                <p class="red">Recomedado tamaño de imagen 500x121 píxeles.</p>
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
                            <label class="col-sm-2 control-label" for="selIdiomas">Idiomas:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <select id="selIdiomas" name="selIdiomas" class="form-control">
                                    <?php 
                                    //echo "<pre>",print_r($arrIdiomas),"</pre>";
                                    if(isset($arrIdiomas) and is_array($arrIdiomas)){
                                        echo '<option value="">-- Seleccione --</option>';
                                        foreach($arrIdiomas as $item){
                                            $selected = $selIdiomas == $item['id'] ? 'selected="selected"' : '';
                                            echo '<option value="'.$item['id'].'" '.$selected.'>'.$item['nombre'].'</option>';
                                        }                             
                                    }
                                    ?>
                                </select>
                                <p>Seleccione "Not applicable" si desea que la imagen aparezca independientemente del idioma del usuario.</p>
                             </div>
                       </div>

                       <div class="form-group">
                            <label class="col-sm-2 control-label">Nombre:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="txtNombre" name="txtNombre" 
                                 placeholder="" value="<?php echo isset($txtNombre) ? $txtNombre : ''; ?>">
                            </div>
                       </div>

                       <div class="form-group">
                            <label class="col-sm-2 control-label">Enlace:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="txtEnlace" name="txtEnlace" 
                                 placeholder="" value="<?php echo isset($txtEnlace) ? $txtEnlace : ''; ?>">
                                <p>Puede colocar una URL o el nombre de un módulo, ej: products</p>
                            </div>
                       </div>
                        
                       <div class="form-actions text-right">
                            <input type="submit" name="btEditar" class="btn btn-primary" value="Editar">
                       </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /with titles (frame) -->
</div>