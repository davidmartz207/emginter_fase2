<!-- Page content -->
<div class="page-content">

    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-archive"></i> Actualizar Catálogo <small></small></h5>
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
             echo form_open_multipart('panel_catalogs/upd/'.$id,'role="form" class="form-horizontal form-bordered"');
             ?>
                <input type="hidden" name="hddId" value="<?php echo $id; ?>">
                <input type="hidden" name="hddFile" value="<?php echo $file_actual; ?>">
                <input type="hidden" name="hddImage" value="<?php echo $imagen_actual; ?>">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">Editar
                        </h6>
                    </div>
                    <div class="panel-body">
                        
                        <div class="form-group">
                            <div class="col-lg-6">
                                <img src="<?php echo image($imagen_actual, 'catalogos'); ?>" />
                            </div>
                            <div class="col-lg-6">
                                <label>Seleccione una nueva imagen sólo si desea cambiar la actual</label>
                                <input type=file size=60 name="filImage"><br><br>
                                <p class="red">Recomedado tamaño de imagen 280x350 píxeles.</p>
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
                            <label class="col-sm-2 control-label">Nombre (Ingles):<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="txtNombre_en" name="txtNombre_en" 
                                 placeholder="" value="<?php echo isset($txtNombre_en) ? $txtNombre_en : ''; ?>">
                            </div>
                       </div>

                       <div class="form-group">
                            <label class="col-sm-2 control-label">Nombre (Español):<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="txtNombre_es" name="txtNombre_es" 
                                 placeholder="" value="<?php echo isset($txtNombre_es) ? $txtNombre_es : ''; ?>">
                            </div>
                       </div>
                        
                       <div class="form-group">
                            <div class="col-lg-6">
                                <?php if(!empty($file_actual)): ?>
                                    <img src="<?php echo base_url(); ?>includes/images/icon-pdf.png">
                                    <?php echo '<a href="'.base_url().'downloads/catalog/'.$id.'/'.$file_actual.'" target="_blank">'.$file_actual.'</a>'; ?>
                                <?php else: ?>
                                    <p style="font-weight: bold;padding: 40px;">Aún no se ha agregado ningún archivo<p>                                    
                                <?php endif; ?>
                            </div>
                            <div class="col-lg-6">
                                <label>Seleccione un nuevo archivo sólo si desea cambiar el actual (Max. <?php echo ini_get('upload_max_filesize'); ?>)</label>
                                <input type=file size=60 name="filFILE"><br><br>
                            </div>
                       </div>

                       <div class="form-group">
                            <label class="col-sm-2 control-label">Enlace Externo:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="txtLinkExterno" name="txtLinkExterno" 
                                 placeholder="Ej. http://dropbox.com/FRSCFSR.pdf" value="<?php echo isset($txtLinkExterno) ? $txtLinkExterno : ''; ?>">
                                <p style="color:red;">Introduzca un enlace sólo si desea que se descargue de un servidor externo</p>
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