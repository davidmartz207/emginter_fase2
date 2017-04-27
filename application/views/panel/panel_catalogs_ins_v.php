<!-- Page content -->
<div class="page-content">

    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-archive"></i> Catálogos <small></small></h5>
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
             echo form_open_multipart('panel_catalogs/ins','role="form" class="form-horizontal form-bordered"');
             ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">Agregar
                        </h6>
                    </div>
                    <div class="panel-body">

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
                            <label class="col-sm-2 control-label">Imagen:</label>
                            <div class="col-sm-10">
                                <input type="file" name="filImage">
                                <p class="red">Recomedado tamaño de imagen 280x350 píxeles.</p>
                            </div>
                       </div>
                        
                       <div class="form-group">
                            <label class="col-sm-2 control-label">Archivo PDF:</label>
                            <div class="col-sm-10">
                                <input type="file" name="filFILE">
                                <p style="color:blue;margin-top: 10px;">Seleccione el catálogo en versión PDF sólo si desea subirlo a Emginter.com</p>
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
                            <input type="submit" name="btSubmit" class="btn btn-primary" value="Agregar Catálogo">
                       </div>
                        
                       <div class="form-group">
                            <p style="padding: 20px;">
                               <b>NOTAS:</b>
                               <br>- Si se rellena el campo <b>Enlace externo</b>, el sistema suministrará ese enlace, al usuario final.
                               <br>- Si se deja vacio el campo <b>Enlace externo</b>, el sistema suministrará al usuario final, el enlace al <b>Archivo PDF</b> alojado en <b>Emginter.com</b>.
                               <br>- Si ambos campos estan ocupados, el sistema suministrará al usuario final, el <b>Enlace externo</b>.
                            </p>
                       </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /with titles (frame) -->
</div>