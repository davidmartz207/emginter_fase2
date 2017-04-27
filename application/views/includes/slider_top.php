<?php 
    if(isset($show_slider) and $show_slider){
        $arrSliderTop = get_slider();
        //echo '<pre>',print_r($arrSliderTop),'</pre>';
        $items        = '';

        if(is_array($arrSliderTop)){
            $total           = count($arrSliderTop);
            $arrLast_product = get_types_product(10000);
            //echo '<pre>',print_r($arrLast_product),'</pre>';
?>
<div id="galeria-superior" class="carousel slide galeria-superior" data-ride="carousel">
<ol class="carousel-indicators">
<?php 
      for($i=0;$i<count($arrLast_product);$i++){
          $active = (empty($i) ? 'active' : '');
          echo '<li data-target="#galeria-superior" data-slide-to="'.$i.'" class="'.$active.'"></li>';
      }
      ?>
</ol>

<div class="carousel-inner" role="listbox">
<?php

        $counter_slides=-1;

        foreach($arrLast_product as $i => $item){

            $counter_slides++;

            if($counter_slides>=$total){
                $counter_slides=0;
            }

            $active = (empty($i) ? ' active' : '');
            echo '<div class="item'.$active.'">'; ?>
    <form id="formSearchProducts" role="form" method="POST" action="<?php echo site_url('products'); ?>" style="background: transparent none repeat scroll 0% 0%;padding: 0px;">
                <input type="hidden" id="hddLastField" name="hddLastField" value="btSearchProductType"/>
                <input type="hidden" id="" name="selTiposProductosDownloads" value="<?=$arrLast_product[$i]['tipo_id'] ?>"/>
                <?php 
                echo '<img class="img-slider img-responsive" src="'.image('media/top_gallery/'.$arrSliderTop[$counter_slides]['path_img'], 'slider_top').'" title="'.$arrSliderTop[$counter_slides]['nombre'].'">';
                if(isset($arrLast_product[$i])){ // _slider
                    echo '
                    <div class="carousel-caption hidden-xs">
                        <div class="descripcion ">
                        <div class="row">
                        <div class="col-lg-12">
                         <div class="col-lg-4 ">
                            <div class="imagen  ">

                                <img class="image img-responsive" width="90px" height="90px" src="'.image($arrLast_product[$i]['path_img'], 'product').'">
                            </div>
                            </div>
                             <div class="col-lg-8">
                            <div class="content ">
                                <div class="sub-title "><p class="letra_descripcion">'.$arrLast_product[$i]['tipo_producto'].' </p></div>

                            </div>
                            </div>
                            <div class="col-lg-12 ">
                            <div class="link text-center">
                                    <button type="submit" class="btn btn-ppal" name="btSearchProductType" value="1" >'.lang('more').'</button>
                                </div>
                            </div>
                          </div>
                            </div>
                        </div>
                    </div>';#end div descripcion
                }
                ?>
                </form>
                <?php
            echo '</div>';
         }#foreach
        ?>
</div>
<a class="left carousel-control" href="#galeria-superior" role="button" data-slide="prev">
<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
<span class="sr-only">Previous</span>
</a>
<a class="right carousel-control" href="#galeria-superior" role="button" data-slide="next">
<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
<span class="sr-only">Next</span>
</a>
</div>
<?php 
    }#if 2
}# if 1
?>