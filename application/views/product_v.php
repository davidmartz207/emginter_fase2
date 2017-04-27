<section class="row">
    <article id="article" class="container">
        <?php
        if(get_rol()=='Administrador'){
            echo '<a title="'.lang('label_go_back').'" href="'.site_url('panel_products').'"><i class="fa fa-reply fa-2x"></i></a>';
        }else{
            echo '<a title="'.lang('label_go_back').'" href="'.site_url('productss').'"><i class="fa fa-reply fa-2x"></i></a>';
        }
        //echo '<pre>',print_r($arrContent),'</pre>';
        if(isset($arrContent) and is_array($arrContent)){ ?>
            <div class="row node-product">
                <?php 
                echo '<div class="col-lg-12 icon-pdf">
                        <a title="'.lang('print_out').'" target="_blank" href="'.site_url('product').'/print_out/'.$arrContent['id'].'"><img src="'.base_url().'includes/images/icon-print.png"></a>
                        <a class="link_print_pdf" title="'.lang('print_pdf').'" href="'.site_url('product').'/pdf/'.$arrContent['id'].'"><img src="'.base_url().'includes/images/icon-pdf.png"></a>
                      </div>';
                echo '<div class="col-lg-3">
                        <img class="img-responsive image" src="'.image($arrContent['path_img'], 'product').'">
                    </div>';
                echo '<div class="col-lg-9">';
                    echo '<h1 class="titulo">'.$arrContent['titulo'].'</h1>';
                    echo '<div class="datos-linea">'.$arrContent['datos_linea'].'</div>';
                    echo '<div class="descripcion"><p>'.$arrContent['descripcion'].'</p></div>';

                    if(isset($arrContent['arrApplicaciones']) and is_array($arrContent['arrApplicaciones'])){ ?>
                    <div class="panel panel-default">
                        <div class="panel-heading"><h6 class="panel-title"><?php echo lang('applications'); ?></h6></div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('field_manufactute_model'); ?></th>
                                        <th><?php echo lang('field_engine_type'); ?></th>
                                        <th><?php echo lang('field_years'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach($arrContent['arrApplicaciones'] as $arrData){
                                        echo '<tr>
                                            <td>'.$arrData['marca_modelo'].'</td>
                                            <td>'.$arrData['tipo_motor'].'</td>
                                            <td>'.$arrData['years'].'</td>';
                                        echo '</tr>';
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /table with footer -->
                    <?php }#end tabla?>
                </div>
			</div>
        <?php }else {
             echo '<div class="col-lg-12">';
                show_msj_error('Page Not Found',false);
             echo '</div>';
        }
        ?>
    </article>
</section>
