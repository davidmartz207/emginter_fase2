<div class="row">
    <div class="container">
        <?php 
        $arrrControles = array('home','about_us','content','product');
        if(in_array(get_controller(),$arrrControles)){ ?>
        <div id="destacados" class="col-lg-12">
            <p class="texto-home-is-our"><?php echo lang('texto_home'); ?></p>
            <?php echo set_label('label_is_our'); ?>
        <?php } ?>
            
        <?php 
        $arrrControles = array('login');
        if(in_array(get_controller(),$arrrControles)){ ?>
        <div id="destacados" class="col-lg-12 text-center">
            <?php echo set_label('label_login'); ?>
        <?php } ?>
            
        <?php 
        $arrrControles = array('downloads');
        if(in_array(get_controller(),$arrrControles)){ ?>
        <div id="destacados" class="col-lg-12 text-center">
            <?php echo set_label('label_download_catalog_guide'); ?>
        <?php } ?>
            
        <?php 
        $arrrControles = array('pass_recovery');
        if(in_array(get_controller(),$arrrControles)){ ?>
        <div id="destacados" class="col-lg-12 text-center">
            <?php echo set_label('label_pass_recovery'); ?>
        <?php } ?>

        <?php 
        $arrrControles = array('register');
        if(in_array(get_controller(),$arrrControles)){ ?>
        <div id="destacados" class="col-lg-12 text-center">
            <?php echo set_label('label_register'); ?>
        <?php } ?>
            
        <?php 
        $arrrControles = array('contact');
        if(in_array(get_controller(),$arrrControles)){ ?>
        <div id="destacados" class="col-lg-12 text-center">
            <?php echo set_label('label_contact'); ?>
        <?php } ?>

        <?php 
        $arrrControles = array('products');
        if(in_array(get_controller(),$arrrControles)){ ?>
        <div id="destacados" class="col-lg-12 text-center">
            <?php echo set_label('label_products_guide'); ?>
        <?php } ?>
            
        <?php 
        $arrrControles = array('catalog_products');
        if(in_array(get_controller(),$arrrControles)){ ?>
        <div id="destacados" class="col-lg-12 text-center">
            <?php echo set_label('label_catalog'); ?>
        <?php } ?>
            
        <?php 
        $arrrControles = array('orders');
        if(in_array(get_controller(),$arrrControles)){ ?>
        <div id="destacados" class="col-lg-12 text-center">
            <?php echo set_label('label_orders'); ?>
        <?php } ?>
            
        <?php 
        $arrrControles = array('my_account');
        if(in_array(get_controller(),$arrrControles)){ ?>
        <div id="destacados" class="col-lg-12 text-center">
            <?php echo set_label('label_my_account'); ?>
        <?php } ?>

        </div>
    </div>
</div>