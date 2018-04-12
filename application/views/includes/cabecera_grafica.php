<nav class="responsive-navbar navbar navbar-inverse visible-xs visible-sm">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <span class="navbar-brand" href="#">MENU</span>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li>
            <a <?php echo (get_controller()=='home') ? 'class="active"' : '';?>
               href="<?php echo site_url('home'); ?>"><?php echo lang('home'); ?>
            </a>
        </li>
        <li>
            <a <?php echo (get_controller()=='about_us') ? 'class="active"' : '';?>
               href="<?php echo site_url('about-us'); ?>"><?php echo lang('about_us'); ?>
            </a>
        </li>
        <li>
            <a <?php echo (get_controller()=='products') ? 'class="active"' : '';?>
               href="<?php echo site_url('products/index1'); ?>"><?php echo lang('products'); ?>
            </a>
        </li>
        <li>
            <a <?php echo (get_controller()=='orders') ? 'class="active"' : '';?>
               href="<?php echo site_url('orders'); ?>"><?php echo lang('orders'); ?>
            </a>
        </li>
        <li>
            <a <?php echo (get_controller() == 'contact') ? 'class="active"' : ''; ?>
               href="<?php echo site_url('contact'); ?>"><?php echo lang('contact'); ?>
            </a>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<div id="div_user_menu_top" class="row">
    <div class="container">
        <div class="col-lg-12">
            <ul class="menu">
                <?php
                #canonical
                $controlador = get_controller();
                $lang        = (get_lang()=='en' ? 'es' : 'en');
                $img_es      = '<img src="'.base_url().'includes/images/flags/es.png"> Español';
                $img_en      = '<img src="'.base_url().'includes/images/flags/en.png"> English';
                $img         = $lang == 'es' ? $img_es : $img_en;
                if(isset($url_post)){
                        echo '<li><a class="bold reset_result" href="'.base_url().$lang.'/'.$url_post.'">'.$img.'</a></li>';
                }else{
                        echo '<li><a class="bold reset_result" href="'.base_url().$lang.'/'.$controlador.'">'.$img.'</a></li>';
                }
                #end flag

                if(!user_is_logged()){
                    echo '<li><a class="bold" href="'.site_url('login').'">'.lang('login').'</a></li>';
                    echo '<li><a class="bold" href="'.site_url('register').'">'.lang('register').'</a></li>';
                }else{
                    if(user_is_admin() or user_is_operador()){
                        echo '<li><a class="bold reset_result" href="'.site_url('panel_inicio').'">Panel</a></li>';
                    }
                    //echo '<li><a href="'.site_url('my_account').'">'.lang('my_account').'</a></li>';
                    echo '<li><a class="bold reset_result" href="'.site_url('logout').'">'.lang('logout').'</a></li>';
                    //echo '<li><span id="user-name">'.lang('welcome').' <b>'.get_name_usuario().'</b></span></li>';
                    echo '<li>
                            <a id="user-name" class="btn btn-danger btn-xs reset_result" href="'.site_url('my_account').'" role="button">
                                <span title="My Account">'.lang('welcome').' <b>'.get_name_usuario().'</b></span>
                            </a>
                          </li>';
                }
            ?>
            </ul>
        </div>
    </div>
</div>
<div id="header" class="row">
    <div class="container">

       <!-- <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
            <div class="header__logo">
                <a href="<?php echo site_url('home'); ?>">
                    <img width="68%" src="<?php echo base_url().'includes/images/logo.png'; ?>">
                </a>
            </div>
        </div>-->
        <div >
            <?php $this->load->view('includes/menu');  ?>
        </div>
    </div>
</div>
