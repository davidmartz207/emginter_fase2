<!-- Page content -->
<div class="page-content">

    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-laptop"></i> Inicio <small></small></h5>
        <div class="btn-group">
        </div>
    </div>
    <!-- /page title -->


    <!-- Statistics -->
    <ul class="row stats">
        <li class="col-lg-4"><a href="<?php echo site_url('panel_usuarios'); ?>" class="btn btn-success"><?php echo $num_users; ?></a> <span>Usuarios Registrados</span></li>
        <li class="col-lg-4"><a href="<?php echo site_url('panel_usuarios').'/users_in_wait'; ?>" class="btn btn-danger"><?php echo $num_users_in_wait; ?></a> <span>Usuarios por Aprobar</span></li>
        <li class="col-lg-4"><a href="<?php echo site_url('panel_products'); ?>" class="btn btn-info"><?php echo $num_products; ?></a> <span>Productos Creados</span></li>
    </ul>
    <!-- /statistics -->
    
    <!-- With titles (frame) -->
    <h4>Últimos Productos Agregados</h4>
    <div class="row">
        <?php
              if(!is_array($arrProducts) or !count($arrProducts)>0){
                 show_msj_info('<b>No se han agregado productos hasta el momento.</b>',false);
              }else{
                  foreach($arrProducts as $item){
                      echo '<div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="widget">
                                    <div class="thumbnail">
                                        <div class="thumb">
                                            <img src="'.image($item['path_img'], 'product').'">
                                            <div class="thumb-options">
                                                <span>
                                                    <a href="'.site_url('product').'/index/'.$item['url_post']
                                                        .'" class="btn btn-icon btn-default" title="Ver"><i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="'.site_url('panel_products').'/app/'.$item['id'].'" class="btn btn-default btn-icon btn-xs tip" title="Gestionar las aplicaciones de este Producto"><i class="fa fa-pencil"></i> App</a>
                                                    <a href="'.site_url('panel_products').'/upd/'.$item['id'].'" class="btn btn-icon btn-default" title="Editar"><i class="fa fa-pencil"></i></a>
                                                    <a href="'.site_url('panel_products').'/del/'.$item['id'].'" class="btEliminar btn btn-icon btn-default" title="Eliminar"><i class="fa fa-times"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="caption" style="border-top:1px solid #eee;text-align:center;height:100px;">
                                            <p><strong>Código SKU:</strong> '.$item['sku'].'</p>
                                            <p><strong>Tipo:</strong> '.$item['tipo_producto'].'</p>
                                            <p><strong>OEM:</strong> '.$item['oem'].'</p>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                  }
              }
       ?>
    </div>
    <!-- /with titles (frame) -->