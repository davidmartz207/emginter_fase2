<!-- Sidebar -->
<div class="sidebar collapse">
        <ul class="navigation">
        <li><a href="<?php echo site_url('panel_inicio'); ?>"><i class="fa fa-laptop"></i> Inicio</a></li>
        <li>
            <a href="<?php echo site_url('panel_catalogs'); ?>"><i class="fa fa-archive"></i> Catálogos de Productos</a>
        </li>
        <li>
            <a href="<?php echo site_url('panel_products'); ?>" class="expand"><i class="fa fa-archive"></i> Productos</a>
            <ul>
                <li><a href="<?php echo site_url('panel_products').'/ins'; ?>"><i class="fa fa-plus"></i> AGREGAR PRODUCTO</a></li>
                <li><a href="<?php echo site_url('panel_products'); ?>"><i class="fa fa-eye"></i> MOSTRAR TODO</a></li>
                <li><a href="<?php echo site_url('panel_products').'/import'; ?>"><i class="fa fa-cloud-upload"></i> IMPORTAR</a></li>
                <li><a href="<?php echo site_url('panel_products').'/export'; ?>"><i class="fa fa-cloud-download"></i> EXPORTAR</a></li>
            </ul>
        </li>
        <li>
            <a href="<?php echo site_url('panel_product_type'); ?>"><i class="fa fa-archive"></i> Tipos de Productos</a>
        </li>
        <li>
            <a href="<?php echo site_url('panel_engine_type'); ?>"><i class="fa fa-gears"></i> Tipos de Motores</a>
        </li>
        <li>
            <a href="<?php echo site_url('panel_manufacturer'); ?>"><i class="fa fa-tags"></i> Marcas</a>
        </li>
        <li>
            <a href="<?php echo site_url('panel_model'); ?>"><i class="fa fa-truck"></i> Modelos</a>
        </li>
    </ul>
</div>
<!-- /sidebar -->
