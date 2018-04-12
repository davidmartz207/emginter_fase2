<div class="row">
    <div class="container bottom-50">
        <div class="col-lg-3"></div>
        <div class="col-lg-6" id="login">
            <?php
            if(isset($sMsjError) and $sMsjError){
              show_msj_error($sMsjError);
            }elseif(isset($sMsjConf) and $sMsjConf){
              show_msj_confirmacion($sMsjConf);
            }
            ?>
            <form role="form" method="POST" action="<?php echo site_url('login/conect'); ?>">
                 <div class="form-group">
                      <label for="txtEmail"><?php echo lang('field_email_sm'); ?> <span class="asterisco_rojo">*</span></label>
                      <input type="email" class="form-control"  
                             placeholder="<?php echo lang('field_email_lg'); ?>" id="txtEmail" name="txtEmail" 
                             value="<?php echo set_value('txtEmail'); ?>">
                 </div>
                 <div class="form-group">
                      <label for="txtPassword"><?php echo lang('field_password_sm'); ?> <span class="asterisco_rojo">*</span></label>
                      <input type="password" class="form-control" placeholder="<?php echo lang('field_password_lg'); ?>" 
                             id="txtPassword" name="txtPassword">
                 </div>
                 <div class="text-center">
                     <a href="<?php echo site_url('login'); ?>/conect/true" class="btn btn-default"><?php echo lang('recover_password'); ?></a>
                     <button type="submit" name="btIngresar" id="btIngresar" class="btn btn-ppal" value="1"><?php echo lang('login'); ?></button>
                 </div>
            </form>
        </div>
        <div class="col-lg-3"></div>
    </div>
</div>