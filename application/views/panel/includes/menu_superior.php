<!-- Navbar -->
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <div class="hidden-lg pull-right">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-right">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="fa fa-chevron-down"></i>
                </button>

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar">
                    <span class="sr-only">Toggle sidebar</span>
                    <i class="fa fa-bars"></i>
                </button>
            </div>

            <ul class="nav navbar-nav navbar-left-custom">
                <li class="user dropdown">
                    <a href="<?php echo site_url('my_account'); ?>" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa  fa-male"></i>
                        <span><?php echo get_name_usuario().' | '.get_rol(); ?></span>
                    </a>
                    <!--ul class="dropdown-menu">
                        <li><a href="#"><i class="fa fa-user"></i> Profile</a></li>
                        <li><a href="#"><i class="fa fa-tasks"></i> Tasks</a></li>
                        <li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>
                        <li><a href="#"><i class="fa fa-mail-forward"></i> Logout</a></li>
                    </ul-->
                </li>
                <!--li><a class="nav-icon sidebar-toggle"><i class="fa fa-bars"></i></a></li-->
            </ul>
        </div>

        <ul class="nav navbar-nav navbar-right collapse" id="navbar-right">
            <!--li>
                <a href="#">
                    <i class="fa fa-rotate-right"></i>
                    <span>Updates</span>
                    <strong class="label label-danger">15</strong>
                </a>
            </li>

            <li>
                <a href="#">
                    <i class="fa fa-comments"></i>
                    <span>Messages</span>
                    <strong class="label label-danger">7</strong>
                </a>
            </li-->
            <li>
                <a href="<?php echo site_url('home'); ?>">
                    <i class="fa fa-fire"></i>
                    <span>Home</span>
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('logout'); ?>">
                    <i class="fa fa-sign-out"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- /navbar -->