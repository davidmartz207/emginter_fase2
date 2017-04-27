<section class="row">
    <article id="article" class="container bloque-ultimo-producto">
        <?php
        //echo '<pre>',print_r($arrContent),'</pre>';
        if(isset($arrContent) and is_array($arrContent)){ ?>
            <div class="col-lg-8 div-contenido">
                <h1 class="titulo">
                    <?php echo set_color_text($arrContent['titulo']); ?>
                </h1>
                <div class="descripcion"><?php echo $arrContent['contenido']; ?></div>
            </div>
            <div class="col-lg-4 div-imagen">
                <?php echo '<img src="'.image('media/contenidos/'.$arrContent['path_img'], 'texto_home').'" 
                            alt="'.$arrContent['titulo'].'" title="'.$arrContent['titulo'].'">';
                ?>
                <div class="top-30"><a href="<?php echo site_url('catalog_products'); ?>"><?php echo set_label('label_catalog'); ?></a></div>
            </div>
        <?php }else {
             echo '<div class="col-lg-12">';
             show_msj_error('Page Not Found',false);
             echo '</div>';
        }
        ?>
    </article>
</section>
