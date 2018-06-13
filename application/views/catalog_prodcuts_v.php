<script>
    //variables para el select dinamico con jcombo
    var dirproductos  = '<?php echo site_url('products'); ?>'
    var selected_value = "<?php echo (isset($selTiposProductosDownloads) ? $selTiposProductosDownloads : 0); ?>";
    var initial_text = "<?php echo lang('selected_product_type'); ?>";
    pagina = "catalogs";
    var strlinea = '<?php echo $this->arrDatos["linea"];?>';
    //si la busqueda está iniciada

</script>
<div id="catalog_products_page" class="row">
    <div class="container">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-4"></div>
                <div class="col-lg-4 text-center">
                    <?php
                    if(isset($sMsjError) and $sMsjError){
                      show_msj_error($sMsjError);
                    }elseif(isset($sMsjConf) and $sMsjConf){
                      show_msj_confirmacion($sMsjConf);
                    }
                    ?>
                </div>
                <div class="col-lg-4"></div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-4  logo-linea-parent" id="emg-line-logo">
                    <img id="imgemg" src="<?=base_url("media/lineas/emg.jpg")?>"class="logo-linea img-responsive <?php if($linea=="emg"){ echo "logo-linea-active"; } ?> " data-linea="emg">
                </div>
                <div class="col-lg-4 logo-linea-parent" id="perfection-line-logo">
                    <img id="imgperfection" src="<?=base_url("media/lineas/perfection.jpg")?>" class="logo-linea img-responsive <?php if($linea=="perfection"){ echo "logo-linea-active"; } ?> " data-linea="perfection">
                </div>
                <div class="col-lg-4 logo-linea-parent" id="mrc-line-logo">
                    <img id="imgmrc" src="<?=base_url("media/lineas/mrc.jpg")?>" class="logo-linea img-responsive <?php if($linea=="mrc"){ echo "logo-linea-active"; } ?> " data-linea="mrc">
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="texto text-center">
                <?php echo get_config_db('texto_guia_productos'); ?>
            </div>
        </div>
        <div class="col-lg-12 bottom-200 formulario-productos" style="visibility: hidden;">
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-4 text-right">
                    <div class="etiqueta">
                        <div class="etiqueta-texto-20"><?php echo lang('label_interactive_catalog'); ?></div>
                    </div>
                    <br><br>
                    <!-- form -->
                    <form id="formSearchProducts" role="form" method="POST" action="<?php echo site_url('catalog_products'); ?>">
                        <input type="hidden" name="linea" id="linea" value="<?php if($linea=="mrc"){ echo "mrc"; }if($linea=="emg"){ echo "emg"; }if($linea=="perfection"){ echo "perfection"; }?>">

                        <div class="form-group">
                            <label class="lblSearchByVehicle lblSearchLbl">
                                <?php echo lang('label_search_by_vehicle'); ?>
                                <img id="img-processing-by-vehicle" class="disabled" src="<?php echo base_url(); ?>includes/images/ui-anim_basic_16x16.gif" alt="Processing"/>
                            </label>
                        </div>

                        <div id="divYears" class="form-group row">
                            <label for="selYears" class="col-sm-4 control-label">
                                <?php echo lang('field_years'); ?>
                            </label>
                            <div class="col-sm-8">
                                <select id="selYears" name="selYears" class="form-control border-red">
                                    <?php 
                                    if(isset($arrYears)){
                                        krsort($arrYears);
                                        foreach($arrYears as $key => $val){
                                            echo '<option value="'.$key.'">'.$val.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div id="divMarcas" class="form-group row">
                            <label for="selMarcas" class="col-sm-4 control-label"><?php echo lang('field_manufactute'); ?></label>
                            <div class="col-sm-8">
                                <select id="selMarcas" name="selMarcas" class="form-control"></select>
                            </div>
                        </div>

                        <div id="divModelos" class="form-group row">
                            <label for="selModelos" class="col-sm-4 control-label"><?php echo lang('field_model'); ?></label>
                            <div class="col-sm-8">
                                <select id="selModelos" name="selModelos" class="form-control"></select>
                            </div>
                        </div>

                        <div id="divTiposMotores" class="form-group row">
                            <label for="selTiposMotores" class="col-sm-4 control-label"><?php echo lang('field_engine_type'); ?></label>
                            <div class="col-sm-8">
                                <select id="selTiposMotores" name="selTiposMotores" class="form-control"></select>
                            </div>
                        </div>

                        <div id="divTiposProductos" class="form-group row">
                            <label for="selTiposProductos" class="col-sm-4 control-label"><?php echo lang('field_type_product'); ?></label>
                            <div class="col-sm-8">
                                <select id="selTiposProductos" name="selTiposProductos" class="form-control"></select>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group row text-center">
                            <button type="submit" id="btReset" name="btReset" class="btn btn-ppal" value="1"><?php echo lang('reset'); ?></button>
                            <button type="submit" id="btSearchPpal" name="btSearchPpal" class="btn btn-ppal" value="1" disabled="disabled"><?php echo lang('search'); ?></button>
                        </div>
                    </form> 
                </div>
                <div class="col-lg-7" style="width: 53.33333333%;">
                    <div class="etiqueta">
                        <div class="etiqueta-texto-20"><?php echo lang('label_download_catalog'); ?></div>
                    </div>
                    <br><br><br>
                    <div id="catalog_download_page" class="">
                        <div class="container">
                            <div class="row">
                                <div>
                                    <?php
                                    # BLOQUE DE CATALOGOS DE PRODUCTOS EMG----------------------------------
                                    if(isset($arrCatalog) and is_array($arrCatalog)){
                                        foreach($arrCatalog as $item) {
                                                $class =  'catalog_'.$item['linea'];
                                                echo '<div class="col-lg-3 '.$class.'">
                                                            <div class="enlace lblSearchLbl">
                                                                <a href="' . $item['link'] . '" target="' . $item['target'] . '" download="' . $item['name_pdf'] . '">' . $item['name'] . '</a>
                                                            </div>
                                                            <div class="imagen">
                                                                <a href="' . $item['link'] . '" target="' . $item['target'] . '" download="' . $item['name_pdf'] . '">
                                                                    <img class="img-responsive" src="' . image($item['path_img'], 'catalogos_rectangular') . '" alt="' . $item['name'] . '" title="' . $item['name'] . '">
                                                                </a>
                                                            </div>
                                                      </div>';

                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              <div class="col-lg-12 div-contenido">
                  <br>
                  <br>
                <?php

                
                //echo '<pre>',print_r($arrContent),'</pre>';
                if(isset($arrProducts) and is_array($arrProducts) and count($arrProducts)>0){
                    $arrIds = array();
                    foreach($arrProducts as $item){
                        $arrIds[] = $item['id'];
                        ?>
                        <div class="row product-row" id="listadoproducts">
                            <?php 
                            echo '<div class="col-lg-2" style="text-align:center;">
                                    <a href="'.site_url('product').'/index/'.$item['url_post'].'">
                                        <img class="img-responsive" src="'.image($item['path_img'], 'product').'" style="width: 100px;">
                                    </a>
                                </div>';
                            echo '<div class="col-lg-10 product-content">';
                            echo '<div class="titulo">';
                            echo '<a href="'.site_url('product').'/index/'.$item['url_post'].'">';
                            echo $item['titulo'];
                            echo '</a>';
                            echo '</div>';
                            echo '<div class="datos-linea">'.$item['datos_linea'].'</div>';
                            echo '<div class="descripcion"><p>'.$item['descripcion'].'</p></div>';




                                if(isset($item['arrApplicaciones']) and is_array($item['arrApplicaciones'])){ ?>
                                     <div class="panel panel-default">
                                         <div class="panel-heading"><h6 class="panel-title"><?php echo lang('applications'); ?></h6></div>
                                         <div class="table-responsive">
                                             <table class="table table-striped table-bordered">
                                                 <thead>
                                                     <tr>
                                                          <th style="min-width:270px;"><?php echo lang('field_manufactute_model'); ?></th>
                                                          <th style="min-width:140px;"><?php echo lang('field_engine_type'); ?></th>
                                                          <th><?php echo lang('field_years'); ?></th>
                                                     </tr>
                                                 </thead>
                                                 <tbody>
                                                     <?php 
                                                     foreach($item['arrApplicaciones'] as $arrData){
                                                         $anios=explode("-",$arrData['years']);
                                                         $mayor = isset($anios[1]) ? $anios[1]:"";
                                                         echo '<tr>
                                                             <td>'.$arrData['marca_modelo'].'</td>
                                                             <td>'.$arrData['tipo_motor'].'</td>
                                                             <td>'.$mayor .'-'.$anios[0].'</td>';
                                                         echo '</tr>';
                                                         $mayor ="";
                                                     } ?>
                                                 </tbody>
                                             </table>
                                         </div>
                                     </div>
                                <!-- /table with footer -->
                                <?php }#end tabla
                                
                                echo '<div class="control-cesta">
                                        <a class="btn btn-ppal" href="'.site_url('product').'/index/'.$item['url_post'].'">'.lang('more').'</a>                                   
                                      </div>';

                            echo '</div><!-- product-content -->';
                            ?>
                        </div><!-- row product-row -->
                        <?php 
                        }#endforeach
                        
                        # se encriptan los ids
                        /*$str_ids = implode('|',$arrIds);
                        $str_ids = base64_encode($str_ids);*/
                        
                        echo '<div class="print_result icon-pdf">
                             <a title="'.lang('print_out').'" target="_blank" href="'.site_url('products').'/print_out"><img src="'.base_url().'includes/images/icon-print.png"></a>';

                        if(isset($SESS_searcht) and $SESS_searcht<>'btSearchProductType'){
                                echo '<a title="'.lang('print_pdf').'" href="'.site_url('products').'/pdf"><img src="'.base_url().'includes/images/icon-pdf.png"></a>';
                        }
                        
                        echo '</div>';
                        ?>
                        <div class="pagination"><?php echo (isset($pagination) ? $pagination : ''); ?></div>
                    <?php 
                    # sihubo error
                    }elseif(isset($msjError) and $msjError){
                        echo '<div>';
                             show_msj_error($msjError,false);
                         echo '</div>';     
                    # si no hay mas que mostrar
                    }
                    ?>
               </div><!-- col-lg-8 div-contenido -->
            </div>
        </div>
    </div>
</div>



<?php
if(isset($arrCatalog) and is_array($arrCatalog) and count($arrCatalog)>0){
    ?>
    <script type="text/javascript">

        window.onload = function () {
            ScrollTo();
        }

        function ScrollTo(name="listadoproducts") {
            ScrollToResolver(document.getElementById(name));
        }

        function ScrollToResolver(elem) {
            var jump = parseInt(elem.getBoundingClientRect().top * .2);
            document.body.scrollTop += jump;
            document.documentElement.scrollTop += jump;
            if (!elem.lastjump || elem.lastjump > Math.abs(jump)) {
                elem.lastjump = Math.abs(jump);
                setTimeout(function() { ScrollToResolver(elem);}, "100");
            } else {
                elem.lastjump = null;
            }
        }
    </script>
<?php
}
?>
