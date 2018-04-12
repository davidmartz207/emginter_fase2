<div class="row">
     <div class="container">
         <div class="col-lg-2"></div>
         <div class="col-lg-8">
            <?php
             if(isset($sMsjError) and $sMsjError){
               show_msj_error($sMsjError);
             }elseif(isset($sMsjConf) and $sMsjConf){
               show_msj_confirmacion($sMsjConf);
             }
             echo form_open_multipart('my_account/upd','role="form"');?>
                 <div class="form-group">
                     <label for="txtNombre"><?php echo lang('field_first_name'); ?> <span class="asterisco_rojo">*</span></label>
                     <input type="text" class="form-control" id="txtNombre" name="txtNombre" 
                            placeholder="" value="<?php echo isset($arrDatos['nombre']) ? $arrDatos['nombre'] : set_value('txtNombre'); ?>">
                 </div>
                 <div class="form-group">
                     <label for="txtApellido"><?php echo lang('field_last_name'); ?> <span class="asterisco_rojo">*</span></label>
                     <input type="text" class="form-control" id="txtApellido" name="txtApellido" 
                                 placeholder="" value="<?php echo isset($arrDatos['apellido']) ? $arrDatos['apellido'] : set_value('txtApellido'); ?>">
                 </div>
                 <div class="form-group">
                     <label for="txtEmail"><?php echo lang('field_email_sm'); ?> <span class="asterisco_rojo">*</span></label>
                      <input type="text" class="form-control" id="txtEmail" name="txtEmail" 
                             placeholder="" value="<?php echo isset($arrDatos['email']) ? $arrDatos['email'] : set_value('txtEmail'); ?>">
                 </div>
                 <fieldset class="col-lg-12">
                    <legend><?php echo lang('field_company_details'); ?></legend>
                    <div class="form-group">
                         <label for="txtCompany"><?php echo lang('field_company'); ?> <span class="asterisco_rojo">*</span></label>
                         <input type="text" class="form-control" id="txtCompany" name="txtCompany" 
                                value="<?php echo isset($arrDatos['empresa']) ? $arrDatos['empresa'] : set_value('txtCompany'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="txtAddressCompany"><?php echo lang('field_address_company'); ?></label>
                        <textarea class="form-control" id="txtAddressCompany" maxlength="10000" 
                            name="txtAddressCompany" rows="4"><?php echo isset($arrDatos['direccion_empresa']) ? $arrDatos['direccion_empresa'] : set_value('txtAddressCompany'); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="txtPhoneNumber"><?php echo lang('field_number_phone'); ?> <span class="asterisco_rojo">*</span></label>
                        <input type="text" class="form-control" id="txtPhoneNumber" name="txtPhoneNumber" 
                             value="<?php echo isset($arrDatos['telefono']) ? $arrDatos['telefono'] : set_value('txtPhoneNumber'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="txtFax"><?php echo lang('field_fax'); ?></label>
                        <input type="text" class="form-control" id="txtFax" name="txtFax" 
                             value="<?php echo isset($arrDatos['fax']) ? $arrDatos['fax'] : set_value('txtFax'); ?>">
                    </div>
                 </fieldset>
                 <div class="form-group row">
                     <div class="col-lg-6">
                        <label for="txtPassword_new"><?php echo lang('field_password_new'); ?></label>
                        <input type="password" class="form-control" placeholder="" 
                             id="txtPassword_new" name="txtPassword_new">
                      </div>
                      <div class="col-lg-6">
                        <label for="txtPassword_new_2"><?php echo lang('field_password_new_conf'); ?></label>
                        <input type="password" class="form-control" placeholder="" 
                             id="txtPassword_new_2" name="txtPassword_new_2">
                      </div>
                 </div>
                 <hr>
                 <div class="form-group">
                      <label for="txtPassword_old"><?php echo lang('field_password_old'); ?> <span class="asterisco_rojo">*</span></label>
                      <input type="password" class="form-control" placeholder="" 
                             id="txtPassword_old" name="txtPassword_old">
                 </div>
                 <hr>
                 <div class="text-center">
                    <button type="submit" name="btUpdate" value="btUpdate" class="btn btn-danger text-center"><?php echo lang('save'); ?></button>
                 </div>
             </form>
          </div>
          <div class="col-lg-2" id="sidebar">
          </div>
     </div>
</div>