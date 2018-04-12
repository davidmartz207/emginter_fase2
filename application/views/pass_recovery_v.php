<div class="row">
     <div class="container">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
             <?php
             if(isset($sMsjError) and $sMsjError){
               show_msj_error($sMsjError);
             }elseif(isset($sMsjConf) and $sMsjConf){
               show_msj_confirmacion($sMsjConf);
             }
             ?>
             <form role="form" method="POST" action="<?php echo site_url('pass_recovery/send_email'); ?>">
                  <div class="form-group">
                       <label for="txtEmail"><?php echo lang('field_email_sm'); ?> <span class="asterisco_rojo">*</span></label>
                       <input type="email" class="form-control"  
                              placeholder="<?php echo lang('field_email_lg'); ?>" id="txtEmail" name="txtEmail" 
                              value="<?php echo isset($login_email) ? $login_email : set_value('txtEmail'); ?>"> 
                  </div>
                  <div class="text-center">
                       <button type="submit" name="btIngresar" class="btn btn-danger"><?php echo lang('send'); ?></button>
                  </div>
             </form>     
        </div>
        <div class="col-lg-3"></div>
    </div>
    <div class="space-200"></div>
</div>
<!-- end background home -->