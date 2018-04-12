<div id="contact_page" class="row">


     <div class="container">
         <div class="row">

         <div class="col-lg-6">
         <?php
         if(isset($sMsjError) and $sMsjError){
             show_msj_error($sMsjError);
         }elseif(isset($sMsjConf) and $sMsjConf){
             show_msj_confirmacion($sMsjConf);
         }
         echo form_open_multipart('contact/send','role="form"');?>
         <div class="texto">
             <p><?php echo get_config_db('texto_contacto'); ?></p>
         </div>
         <div class="form-group">
             <label for="selTipoContacto"><?php echo lang('field_contact_type'); ?> <span class="asterisco_rojo">*</span></label>
             <select id="selTipoContacto" name="selTipoContacto" class="form-control">
                 <?php
                 if(isset($arrTiposContactos) and is_array($arrTiposContactos)){
                     echo '<option value="">'.lang('field_select').'</option>';
                     foreach($arrTiposContactos as $item){
                         $selected = $selTipoContacto == $item['nombre'] ? 'selected="selected"' : '';
                         echo '<option value="'.$item['nombre'].'" '.$selected.'>'.$item['nombre'].'</option>';
                     }
                 }
                 ?>
             </select>
         </div>
         <div class="form-group">
             <label for="txtNombre"><?php echo lang('field_full_name'); ?> <span class="asterisco_rojo">*</span></label>
             <input type="text" class="form-control" id="txtNombre" name="txtNombre"
                    placeholder="" value="<?php echo isset($txtNombre) ? $txtNombre : ''; ?>">
         </div>
         <div class="form-group">
             <label for="txtEmail"><?php echo lang('field_email_sm'); ?> <span class="asterisco_rojo">*</span></label>
             <input type="text" class="form-control" id="txtEmail" name="txtEmail"
                    placeholder="" value="<?php echo isset($txtEmail) ? $txtEmail : ''; ?>">
         </div>
         <div class="form-group">
             <label for="txtCompany"><?php echo lang('field_company'); ?></label>
             <input type="text" class="form-control" id="txtCompany" name="txtCompany"
                    placeholder=""
                    value="<?php echo isset($txtCompany) ? $txtCompany : ''; ?>">
         </div>
         <div class="form-group">
             <label for="selPaises"><?php echo lang('field_country'); ?> <span class="asterisco_rojo">*</span></label>
             <select id="selPaises" name="selPaises" class="form-control">
                 <?php
                 if(isset($arrPaises) and is_array($arrPaises)){
                     echo '<option value="">'.lang('field_select').'</option>';
                     foreach($arrPaises as $item){
                         $selected = $selPaises == $item['name'] ? 'selected="selected"' : '';
                         echo '<option value="'.$item['name'].'" '.$selected.'>'.$item['name'].'</option>';
                     }
                 }
                 ?>
             </select>
         </div>
         <div class="form-group">
             <label for="txtAddressCompany"><?php echo lang('field_address_company'); ?></label>
             <input type="text" class="form-control" id="txtAddressCompany" name="txtAddressCompany"
                    placeholder=""
                    value="<?php echo isset($txtAddressCompany) ? $txtAddressCompany : ''; ?>">
         </div>
         <fieldset class="col-lg-12">
             <legend><?php echo lang('field_contact_phone'); ?></legend>
             <div class="col-lg-4">
                 <label for="txtCountryCode"><?php echo lang('field_country_code'); ?> <span class="asterisco_rojo">*</span></label>
                 <input type="text" class="form-control" id="txtCountryCode" name="txtCountryCode"
                        placeholder=""
                        value="<?php echo isset($txtCountryCode) ? $txtCountryCode : ''; ?>">
             </div>
             <div class="col-lg-4">
                 <label for="txtCityCode"><?php echo lang('field_city_code'); ?> <span class="asterisco_rojo">*</span></label>
                 <input type="text" class="form-control" id="txtCityCode" name="txtCityCode"
                        placeholder=""
                        value="<?php echo isset($txtCityCode) ? $txtCityCode : ''; ?>">
             </div>
             <div class="col-lg-4">
                 <label for="txtPhoneNumber"><?php echo lang('field_number_phone'); ?> <span class="asterisco_rojo">*</span></label>
                 <input type="text" class="form-control" id="txtPhoneNumber" name="txtPhoneNumber"
                        placeholder=""
                        value="<?php echo isset($txtPhoneNumber) ? $txtPhoneNumber : ''; ?>">
             </div>
         </fieldset>

         <div class="form-group">
             <label for="txtaComentarios"><?php echo lang('field_comments'); ?> <span class="asterisco_rojo">*</span></label>
             <textarea class="form-control" id="txtaComentarios" maxlength="10000"
                       name="txtaComentarios" rows="4"><?php echo isset($txtaComentarios) ? $txtaComentarios : ''; ?></textarea>
         </div>

         <div class="text-center">
             <button type="submit" class="btn btn-danger text-center"><?php echo lang('send'); ?></button>
         </div>
         </form>



     </div>
         <div class="col-lg-6">

             <iframe src="https://www.google.com/maps/d/u/0/embed?mid=19gnjpEv7txTj7BpIZ_-Ty6iGD-c" width="100%" height="480px"></iframe>

             <div class="row">
                 <div class="col-lg-12" id="sidebar">
                     <div class="bloque-info-contacto ">
                         <h2><?php echo set_color_text(lang('contact_information')); ?></h2>
                         <p><?php echo get_config_db('direccion'); ?></p>
                         <p><?php echo lang('field_number_phone'); ?>: <?php echo get_config_db('telefono'); ?></p>
                         <p><?php echo lang('field_fax'); ?>: <?php echo get_config_db('fax'); ?></p>
                         <p><?php echo lang('field_email_sm'); ?>: <a href="mailto:<?php echo get_config_db('email_public'); ?>"><?php echo get_config_db('email_public'); ?></a></p>
                     </div>

                     <div class="bloque-info-horario-trabajo">
                         <h2><?php echo set_color_text(lang('working_hours_sm')); ?></h2>
                         <p><?php echo get_config_db('horarios_jornada_laboral'); ?></p>
                     </div>
                 </div>
             </div>

         </div>
     </div>


     </div>

</div>