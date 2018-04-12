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
             echo form_open_multipart('register/process','role="form"');?>
                 <div class="form-group">
                     <label for="txtNombre"><?php echo lang('field_first_name'); ?> <span class="asterisco_rojo">*</span></label>
                     <input type="text" class="form-control" id="txtNombre" name="txtNombre" 
                                 placeholder="" value="<?php echo isset($txtNombre) ? $txtNombre : ''; ?>">
                 </div>
                 <div class="form-group">
                     <label for="txtApellido"><?php echo lang('field_last_name'); ?> <span class="asterisco_rojo">*</span></label>
                     <input type="text" class="form-control" id="txtApellido" name="txtApellido" 
                                 placeholder="" value="<?php echo isset($txtApellido) ? $txtApellido : ''; ?>">
                 </div>
                 <div class="form-group">
                     <label for="txtEmail"><?php echo lang('field_email_sm'); ?> <span class="asterisco_rojo">*</span></label>
                      <input type="text" class="form-control" id="txtEmail" name="txtEmail" 
                             placeholder="" value="<?php echo isset($txtEmail) ? $txtEmail : ''; ?>">
                 </div>
                 <fieldset class="col-lg-12">
                    <legend><?php echo lang('field_company_details'); ?></legend>
                    <div class="form-group">
                         <label for="txtCompany"><?php echo lang('field_company'); ?> <span class="asterisco_rojo">*</span></label>
                         <input type="text" class="form-control" id="txtCompany" name="txtCompany" 
                                placeholder="" 
                                value="<?php echo isset($txtCompany) ? $txtCompany : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="txtAddressCompany"><?php echo lang('field_address_company'); ?></label>
                        <textarea class="form-control" id="txtAddressCompany" maxlength="10000" 
                            name="txtAddressCompany" rows="4"><?php echo isset($txtAddressCompany) ? $txtAddressCompany : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="txtPhoneNumber"><?php echo lang('field_number_phone'); ?> <span class="asterisco_rojo">*</span></label>
                        <input type="text" class="form-control" id="txtPhoneNumber" name="txtPhoneNumber" 
                             placeholder="" 
                             value="<?php echo isset($txtPhoneNumber) ? $txtPhoneNumber : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="txtFax"><?php echo lang('field_fax'); ?></label>
                        <input type="text" class="form-control" id="txtFax" name="txtFax" 
                             placeholder="" 
                             value="<?php echo isset($txtFax) ? $txtFax : ''; ?>">
                    </div>
                 </fieldset>
                 <div class="form-group">
                      <label for="txtPassword"><?php echo lang('field_password_sm'); ?> <span class="asterisco_rojo">*</span></label>
                      <input type="password" class="form-control" placeholder="" 
                             id="txtPassword" name="txtPassword">
                 </div>
                 <div class="form-group">
                      <label for="txtPassword2"><?php echo lang('field_confirm_password'); ?> <span class="asterisco_rojo">*</span></label>
                      <input type="password" class="form-control" placeholder="" 
                             id="txtPassword2" name="txtPassword2">
                 </div>
                 <div class="text-center">
                    <button type="submit" class="btn btn-danger text-center"><?php echo lang('register'); ?></button>
                 </div>
             </form>
          </div>
          <div class="col-lg-2" id="sidebar">
          </div>
     </div>
</div>