<!-- Page content -->
<div class="page-content">

    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-archive"></i> Usuarios <small></small></h5>
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
             echo form_open_multipart('panel_usuarios/upd/'.$id,'role="form" class="form-horizontal form-bordered"');
             ?>
                <input type="hidden" name="hddId" value="<?php echo $id; ?>">
                <input type="hidden" name="hddImage" value="<?php echo $imagen_actual; ?>">
                <input type="hidden" name="hddEstatus" value="<?php echo $hddEstatus; ?>">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">Editar usuario
                        </h6>
                    </div>
                    <div class="panel-body">
                        <!--div class="form-group">
                            <div class="col-lg-6">
                                <img src="<?php echo image('media/usuarios/'.$imagen_actual, 'product'); ?>" />
                            </div>
                            <div class="col-lg-6">
                                <label>Seleccione una nueva imagen sólo si desea cambiar la actual</label>
                                <input type=file size=60 name="filFILE"><br><br>
                            </div>
                        </div-->
                        
                        <?php if($id<>1): ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="selTiposUsuarios">Tipos de Usuarios:<span class="asterisco_rojo">*</span></label>
                                <div class="col-sm-10">
                                    <select id="selTiposUsuarios" name="selTiposUsuarios" class="form-control">
                                        <?php 
                                        if(isset($arrTiposUsuarios) and is_array($arrTiposUsuarios)){
                                            echo '<option value="">-- Seleccione --</option>';
                                            foreach($arrTiposUsuarios as $item){
                                                $selected = $selTiposUsuarios == $item['id'] ? 'selected="selected"' : '';
                                                echo '<option value="'.$item['id'].'" '.$selected.'>'.$item['nombre'].'</option>';
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
                                        <option value="2" <?php echo $selEstatus==2 ? 'selected="selected"' : ''; ?>>Por aprobar</option>
                                    </select>
                                 </div>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Nombre:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="txtNombre" name="txtNombre" 
                                 placeholder="" value="<?php echo isset($txtNombre) ? $txtNombre : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Apellido:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="txtApellido" name="txtApellido" 
                                 placeholder="" value="<?php echo isset($txtApellido) ? $txtApellido : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Email:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="txtEmail" name="txtEmail" 
                                 placeholder="" value="<?php echo isset($txtEmail) ? $txtEmail : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Empresa:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="txtCompany" name="txtCompany" 
                                 placeholder="" value="<?php echo isset($txtCompany) ? $txtCompany : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Dirección de la Empresa:</label>
                            <div class="col-sm-10">
                                <textarea id="txtAddressCompany" name="txtAddressCompany" class="form-control" cols="5" rows="5"><?php echo isset($txtAddressCompany) ? $txtAddressCompany : ''; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Teléfono:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="txtPhoneNumber" name="txtPhoneNumber" 
                                 placeholder="" value="<?php echo isset($txtPhoneNumber) ? $txtPhoneNumber : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Fax:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="txtFax" name="txtFax" 
                                 placeholder="" value="<?php echo isset($txtFax) ? $txtFax : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Contraseña:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="txtPassword" name="txtPassword" 
                                 placeholder="" value="">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Confirmar contraseña:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="txtPassword2" name="txtPassword2" 
                                 placeholder="" value="">
                            </div>
                        </div>

                        <div class="form-actions text-right">
                            <input type="submit" name="btUpdate" class="btn btn-primary" value="Editar Usuario">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /with titles (frame) -->
</div>