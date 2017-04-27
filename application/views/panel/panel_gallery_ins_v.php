<!-- Page content -->
<div class="page-content">

    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-archive"></i> Galería Superior <small></small></h5>
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
             echo form_open_multipart('panel_gallery/ins','role="form" class="form-horizontal form-bordered"');
             ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">Agregar Imagen
                        </h6>
                    </div>
                    <div class="panel-body">

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
                            <label class="col-sm-2 control-label">Imagen:</label>
                            <div class="col-sm-10">
                                <input type="file" name="filFILE">
                                <p class="red">Recomedado tamaño de imagen 1600x380 píxeles.</p>
                            </div>
                       </div>
                        
                       <div class="form-actions text-right">
                            <input type="submit" name="btSubmit" class="btn btn-primary" value="Crear">
                       </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /with titles (frame) -->
</div>