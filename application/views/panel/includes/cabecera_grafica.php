<!-- Page header -->
<div class="container">
    <div class="page-header">
        <div class="logo"><a href="<?php echo site_url('home'); ?>" title="Ir al Home">
                <img style="width:150px;height:100px;" src="<?php echo base_url(); ?>includes/images/logo.png" alt="Ir al Home"></a>
        </div>
        <ul class="middle-nav">
            <!--li><a href="#" class="btn btn-default"><i class="fa fa-comments-o"></i> <span>Support tickets</span></a><div class="label label-info">9</div></li-->
            <li><a class="btn btn-default" href="<?php echo site_url('panel_config'); ?>"><i class="fa fa-cogs"></i> Configuraciones</a></li>
            <?php if(user_is_admin()){ ?>
            <li>
                <a class="btn btn-default" href="<?php echo site_url('panel_usuarios'); ?>"><i class="fa fa-users"></i> Usuarios</a>
            </li>
            <li>
                <a class="btn btn-default" href="<?php echo site_url('panel_gallery'); ?>"><i class="fa fa-picture-o"></i> Galería Superior</a>
            </li>
            <li>
                <a class="btn btn-default" href="<?php echo site_url('panel_banners'); ?>"><i class="fa fa-picture-o"></i> Mini Banners</a>
            </li>
            <?php } ?>
            <li><a class="btn btn-default" href="<?php echo site_url('panel_articulos'); ?>"><i class="fa fa-pencil-square-o"></i> Contenidos</a></li>
            <li><a href="<?php echo site_url('panel_products'); ?>" class="btn btn-default"><i class="fa fa-users"></i> <span>Productos</span></a></li>
            <li><a href="<?php echo site_url('my_account'); ?>" class="btn btn-default"><i class="fa fa-male"></i> <span></span>Mi Cuenta</a></li>
            <!--li><a href="#" class="btn btn-default"><i class="fa fa-money"></i> <span>Billing panel</span></a></li-->
        </ul>
    </div>
</div>
<!-- /page header -->