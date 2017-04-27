<!-- Page content -->
<div class="page-content">

    <!-- Page title -->
    <div class="page-title">
        <h5><i class="fa fa-archive"></i> Importar/Procesar Productos por lotes <small>En tan sólo dos pasos podrá subir archivos de productos y procesarlos</small></h5>
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
             }elseif(isset($sMsjInfo) and $sMsjInfo){
               show_msj_info($sMsjInfo,false);
             }elseif(isset($sMsjWarning) and $sMsjWarning){
                 show_msj_warning($sMsjWarning,false);
             }
             echo '<br>';
             echo form_open_multipart('panel_products/import','role="form" class="form-horizontal form-bordered"');
             ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <?php if(!$file){ ?>
                                <img src="<?php echo base_url().'includes/images/paso1.png'; ?>"> Subir archivo 
                            <?php }else{?>
                                <img src="<?php echo base_url().'includes/images/paso2.png'; ?>"> Procesar archivo
                           <?php } ?>
                        </h6>
                    </div>
                    <div class="panel-body">
                         <?php if(!$file){ ?>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">
                                    Seleccione un archivo<br>
                                    <strong>Formatos permitidos XLS, XLSX.</strong>
                                </label>
                                <div class="col-sm-4 text-center">
                                    <input type="file" name="filFILE">
                                </div>
                                <div class="col-sm-4 form-actions text-right">
                                    <input type="submit" name="btUpload" class="btn btn-primary" value="Importar Archivo">
                                </div>
                            </div>
                            <?php if(isset($arrEstadisticas) AND is_array($arrEstadisticas)){
                                echo '<hr><h3>Resultado del procesamiento</h3>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tipos de productos</th>
                                            <th>Tipos de motores</th>
                                            <th>Productos</th>
                                            <th>Aplicaciones</th>
                                            <th>Marcas</th>
                                            <th>Modelos</th>
                                         </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>'.$arrEstadisticas['tipos_productos_ins'].'</td>
                                            <td>'.$arrEstadisticas['tipos_motores_ins'].'</td>
                                            <td>'.$arrEstadisticas['productos_ins'].'</td>
                                            <td>'.$arrEstadisticas['productos_app_ins'].'</td>
                                            <td>'.$arrEstadisticas['marcas_ins'].'</td>
                                            <td>'.$arrEstadisticas['modelos_ins'].'</td>
                                        </tr>
                                    </tbody>
                                </table>';
                            }
                        }else{?>
                            <div id="import_botones">
                                <div class="form-group">
                                    <h3 class="text-center">
                                        El archivo se subió correctamente, ¿Desea continuar con el procesamiento?
                                    </h3>                                    
                                </div>
                                <div class="form-actions text-center">
                                    <input type="submit" name="btUploadNew" class="btn btn-default" value="No, importar otro archivo">&nbsp;&nbsp;
                                    <input type="submit" name="btProcess" id="btProcess" class="btn btn-primary" value="Sí, procesar el archivo">
                                </div>      
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label style="color:red;font-weight: bold;">Opciones de importación</label>
                                    </div>        
                                    <div class="col-sm-12">
                                        <select id="selOpcionesImportacion" name="selOpcionesImportacion" class="form-control">
                                            <?php 
                                            echo '<option value="">-- Seleccione --</option>';
                                            foreach($arrOpcionesImportacion as $id => $item){
                                                $selected = $selOpcionesImportacion == $id ? 'selected="selected"' : '';
                                                echo '<option value="'.$id.'" '.$selected.'>'.$item.'</option>';
                                            }
                                            ?>
                                        </select>
                                     </div>
                               </div>
                            </div>
                            <div id="import_ajax" class="text-center" style="display:none;">
                                <div class="form-group">
                                    <h3 class="text-center">Procesando, por favor espere..</h3>
                                </div>
                                <div class="form-group">
                                    <img src="<?php echo base_url().'includes/images/loading.gif'; ?>">
                                    <p style="color:red;"><strong>No recargue la página, ni haga click en otra opción hasta que el proceso culmine.</strong></p>
                                </div>
                            </div>
                            <hr>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tipo de archivo</th>
                                        <th>Tamaño del archivo</th>
                                     </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo isset($type) ? $type : ''; ?></td>
                                        <td><?php echo isset($size) ? $size.'B' : ''; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php } ?>                        
                    </div>
                </div>
            </form>
        </div>
    </div>
