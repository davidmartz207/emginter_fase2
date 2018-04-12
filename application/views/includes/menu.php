
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="hidden-sm hidden-xs">
        <a class="navbar-brand" style="padding: 0px;" href="<?php echo site_url('home'); ?>">
            <img class="img img-responsive"  src="<?php echo base_url().'includes/images/logo.png'; ?>">
        </a>
    </div>
    <div id="menu-top" class="menu-superior hidden-sm hidden-xs">
        <ul>
            <li>
                <a  <?php echo (get_controller()=='home') ? 'class="active "' : 'class=" "';?> href="<?php echo site_url('home'); ?>">
                    <?php echo lang('home'); ?>
                </a>
            </li>
            <li >
                <a <?php echo (get_controller()=='about_us') ? 'class="active "' : 'class=" "';?> href="<?php echo site_url('about-us'); ?>"><?php echo lang('about_us'); ?>
                </a>
            </li>
            <li >
                <a <?php echo (get_controller()=='products') ? 'class="active "' : 'class=" "';?> href="<?php echo site_url('products').'/index1'; ?>"><?php echo lang('products'); ?>
                </a>
            </li>
            <?php
            $sesion_data=$this->session->userdata("ses_usuario");
            $session=$sesion_data["id_usuario"];
            if($session>0){
                ?>
                <li >
                    <a <?php echo (get_controller()=='catalog_products') ? 'class="active "' : 'class=" "';?> href="<?php echo site_url('catalog_products').'/index1'; ?>"><?php echo lang('catalog'); ?>
                    </a>
                </li>
                <?php
            }
            ?>
            <li >
                <a <?php echo (get_controller()=='orders') ? 'class="active "' : 'class=" "';?> href="<?php echo site_url('orders'); ?>"><?php echo lang('orders'); ?>
                </a>
            </li>
            <li >
                <a <?php echo (get_controller()=='news') ? 'class="active "' : 'class=" "';?> href="<?php echo site_url('news'); ?>"><?php echo lang('news'); ?>
                </a>
            </li>
            <li >
                <a <?php echo (get_controller()=='contact') ? 'class="active "' : 'class=" "'; ?> href="<?php echo site_url('contact'); ?>"><?php echo lang('contact'); ?>
                </a>
            </li>
        </ul>

    </div>
</nav>
