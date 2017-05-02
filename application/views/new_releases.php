<?php
if(isset($arrNewRelease) and is_array($arrNewRelease)){ ?>
<div id="new-reales-home" class="row">
<div class="container">
<h2><i class="rojo">NEW</i> <i class="blanco">RELEASES</i></h2>
<?php
            echo '<div id="owl-new-release">';
            foreach($arrNewRelease as $item){
                echo '
                <div class="item">
                    <a href="'.site_url('product').'/index/'.$item['url_post'].'">
                        <img class="img-responsive img-productos" src="'.image($item['path_img'],'product').'" 
                        alt="'.$item['titulo'].'" title="'.$item['titulo'].'">
                    </a>
                    <div class="titulo"><a href="'.site_url('product').'/index/'.$item['url_post'].'">'.$item['titulo'].'</a></div>
                </div>';
             }
             echo '</div>';
            ?>
</div>
</div>
<?php } ?>