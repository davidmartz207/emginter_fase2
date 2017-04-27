<!-- Page content -->
<div class="page-content">

    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-archive"></i> Editar configuración <small>edite la configuración global del sitio en los idiomas correspondientes</small></h5>
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
             echo form_open_multipart('panel_config/upd/'.$id,'role="form" class="form-horizontal form-bordered"');
             ?>
                <input type="hidden" name="hddId" value="<?php echo $id; ?>">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">Configuración para el sitio en el idioma <strong><?php echo $selIdiomas; ?></strong></h6>
                    </div>
                    <div class="panel-body">
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="selEstatus">Estatus:</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="selEstatus" name="selEstatus">
                                    <option value="1" <?php echo $selEstatus==1 ? 'selected="selected"' : ''; ?>>Activo</option>
                                    <option value="0" <?php echo $selEstatus==0 ? 'selected="selected"' : ''; ?>>Inactivo</option>
                                </select>
                             </div>
                        </div>

                       <div class="form-group">
                            <label class="col-sm-3 control-label">Teléfono:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="txtTelefono" name="txtTelefono" 
                                 placeholder="" value="<?php echo isset($txtTelefono) ? $txtTelefono : ''; ?>">
                            </div>
                       </div>
                        
                       <div class="form-group">
                            <label class="col-sm-3 control-label">Fax:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="txtFax" name="txtFax" 
                                 placeholder="" value="<?php echo isset($txtFax) ? $txtFax : ''; ?>">
                            </div>
                       </div>

                       <div class="form-group">
                            <label class="col-sm-3 control-label">Email de Contacto:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="txtEmail_contact" name="txtEmail_contact" 
                                 placeholder="" value="<?php echo isset($txtEmail_contact) ? $txtEmail_contact : ''; ?>">
                            </div>
                       </div>
                        
                       <div class="form-group">
                            <label class="col-sm-3 control-label">Email de Pedidos:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="txtEmail_public" name="txtEmail_public" 
                                 placeholder="" value="<?php echo isset($txtEmail_public) ? $txtEmail_public : ''; ?>">
                            </div>
                       </div>
                        
                       <div class="form-group">
                            <label class="col-sm-3 control-label">Dirección <span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="txtaDireccion" maxlength="10000" 
                                   name="txtaDireccion" rows="4"><?php echo isset($txtaDireccion) ? $txtaDireccion : ''; ?></textarea>
                            </div>
                            <?php echo display_ckeditor($ckeditor_txtaDireccion); ?>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Horarios Jornada Laboral <span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="txtaHorariosJornadaLaboral" maxlength="10000" 
                                   name="txtaHorariosJornadaLaboral" rows="4"><?php echo isset($txtaHorariosJornadaLaboral) ? $txtaHorariosJornadaLaboral : ''; ?></textarea>
                            </div>
                            <?php echo display_ckeditor($ckeditor_txtaHorariosJornadaLaboral); ?>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Mensaje de registro <span class="asterisco_rojo">*</span> (se envía por Email)</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="txtaMsjRegistro" maxlength="10000" 
                                   name="txtaMsjRegistro" rows="7"><?php echo isset($txtaMsjRegistro) ? $txtaMsjRegistro : ''; ?></textarea>
                            </div>
                            <?php echo display_ckeditor($ckeditor_txtaMsjRegistro); ?>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Mensaje de aprobación de registro <span class="asterisco_rojo">*</span> (se envía por Email)</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="txtaMsjRegistroAprobacion" maxlength="10000" 
                                   name="txtaMsjRegistroAprobacion" rows="7"><?php echo isset($txtaMsjRegistroAprobacion) ? $txtaMsjRegistroAprobacion : ''; ?></textarea>
                            </div>
                            <?php echo display_ckeditor($ckeditor_txtaMsjRegistroAprobacion); ?>
                        </div>
                        
                        <hr>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Mensaje página de <?php echo anchor('products','Productos','target="_blank"'); ?></label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="txtaTextoProductos" maxlength="10000" 
                                   name="txtaTextoProductos" rows="7"><?php echo isset($txtaTextoProductos) ? $txtaTextoProductos : ''; ?></textarea>
                                <?php echo display_ckeditor($ckeditor_txtaTextoProductos); ?>
                            </div>
                        </div>
                        
                         <div class="form-group">
                            <label class="col-sm-3 control-label">Mensaje página de <?php echo anchor('catalog_products','Catálogos','target="_blank"'); ?></label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="txtaTextoGuiaProductos" maxlength="10000" 
                                   name="txtaTextoGuiaProductos" rows="7"><?php echo isset($txtaTextoGuiaProductos) ? $txtaTextoGuiaProductos : ''; ?></textarea>
                                <?php echo display_ckeditor($ckeditor_txtaTextoGuiaProductos); ?>
                            </div>
                        </div>
                        
                         <div class="form-group">
                            <label class="col-sm-3 control-label">Mensaje página de <?php echo anchor('contact','Contacto','target="_blank"'); ?></label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="txtaTextoContacto" maxlength="10000" 
                                   name="txtaTextoContacto" rows="7"><?php echo isset($txtaTextoContacto) ? $txtaTextoContacto : ''; ?></textarea>
                                <?php echo display_ckeditor($ckeditor_txtaTextoContacto); ?>
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