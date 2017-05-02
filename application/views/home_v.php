<?php 
//echo '<pre>',print_r($arrTextoHome),'</pre>';
if(isset($arrTextoHome) and is_array($arrTextoHome)){ ?>
<div class="row">
<div id="article" class="container bloque-ultimo-producto">
<div class="col-lg-8 div-contenido">
<div class="titulo">
<?php echo '<a href="'.$arrTextoHome['url_post'].'">'
                                   .set_color_text($arrTextoHome['titulo'])
                               .'</a>';
                    ?>
</div>
<div class="descripcion"><?php echo $arrTextoHome['contenido']; ?></div>
<div class="enlace">
<?php echo '<a class="btn btn-ppal" href="'.$arrTextoHome['url_post'].'">'.lang('more').'</a>';
                    ?>
</div>
</div>
<div class="col-lg-4 div-imagen">
<?php echo '<img class="img-responsive" src="'.image('media/contenidos/'.$arrTextoHome['path_img'], 'texto_home').'" 
                            alt="'.$arrTextoHome['titulo'].'" title="'.$arrTextoHome['titulo'].'">';
                ?>
</div>
</div>
</div>
<?php }

//echo '<pre>',print_r($arrImageLink),'</pre>';
if(isset($arrImageLink) and is_array($arrImageLink)){ ?>
<div class="row">
<div id="div-publicidad-mini-banners" class="container">
<div class="col-lg-4"><!-- id="div-publicidad-mini-banners-left" -->
<?php 
if(isset($arrImageLink[1])){
    echo '<a href="'.$arrImageLink[1]['enlace'].'" title="'.$arrImageLink[1]['nombre'].'">
                <img class="img-responsive" src="'.image('media/banners/'.$arrImageLink[1]['path_img'], 'publi_image').'">
            </a>';
}
?>
</div>
<div class="col-lg-4"><!-- id="div-publicidad-mini-banners-center" -->
<?php 
if(isset($arrImageLink[2])){
    echo '<a href="'.$arrImageLink[2]['enlace'].'" title="'.$arrImageLink[2]['nombre'].'">
                <img class="img-responsive" src="'.image('media/banners/'.$arrImageLink[2]['path_img'], 'publi_image').'">
          </a>';
}
?>
</div>
<div class="col-lg-4"><!-- id="div-publicidad-mini-banners-right" -->
<?php 
if(isset($arrImageLink[0])){
    echo '<a href="'.$arrImageLink[0]['enlace'].'" title="'.$arrImageLink[0]['nombre'].'">
                <img class="img-responsive" src="'.image('media/banners/'.$arrImageLink[0]['path_img'], 'publi_image').'">
          </a>';
}
?>
</div>
</div>
</div>
<?php }
 ?>
<div class="row">
<div class="container">
<div class="col-lg-12 text-center">
<div class="nuestros-productos">
<div class="top-30">
<a href="<?php echo site_url('catalog_products'); ?>"><?php echo set_label('label_catalog'); ?></a>
</div>
<div class="tipos-producto-home">
<?php
                    //echo '<pre>',print_r($arrNewRelease),'</pre>';
                    if(isset($arrTiposProductos) and is_array($arrTiposProductos)){
                        echo '<ul>';
                        foreach($arrTiposProductos as $item){
                            echo '<li><a href="'.$item['enlace'].'?p=home">'.$item['nombre'].'</a></li>';
                         }
                         echo '</ul>';
                    } ?>
</div>
</div>
</div>
</div>
</div>