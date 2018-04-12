<div id="catalog_download_page" class="row">
    <div class="container">
        <div class="col-lg-12 bottom-100">
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
            
            <div class="col-lg-12 top-50 bottom-100">
                <div class="row">
                    <?php
                    # BLOQUE DE CATALOGOS DE PRODUCTOS ----------------------------------
                    if(isset($arrCatalog) and is_array($arrCatalog)){
                         echo '<div class="col-lg-2"></div>';
                         foreach($arrCatalog as $item){
                             echo '<div class="col-lg-4">
                                        <div class="enlace">
                                            <a href="'.$item['link'].'" target="'.$item['target'].'" download="'.$item['name_pdf'].'">'.$item['name'].'</a>
                                        </div>
                                        <div class="imagen">
                                            <a href="'.$item['link'].'" target="'.$item['target'].'" download="'.$item['name_pdf'].'">
                                                <img class="img-responsive" src="'.image($item['path_img'], 'catalogos_rectangular').'" alt="'.$item['name'].'" title="'.$item['name'].'">
                                            </a>
                                        </div>
                                    </div>';
                         }
                         echo '<div class="col-lg-2"></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
