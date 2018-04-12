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
<!--
Ocultando los indicadores
<ol class="carousel-indicators">
<?php 
      for($i=0;$i<count($arrLast_product);$i++){
          $active = (empty($i) ? 'active' : '');
          echo '<li data-target="#galeria-superior" data-slide-to="'.$i.'" class="'.$active.'"></li>';
      }
      ?>
</ol>-->

<div class="carousel-inner" role="listbox">
    <div class="item active">
        <img class="img-slider img-responsive" src="<?=base_url("media/top_gallery/emg.jpg")?>" title="">
    </div>
    <div class="item">
        <img class="img-slider img-responsive" src="<?=base_url("media/top_gallery/perfection.jpg")?>" title="">
    </div>
    <div class="item">
        <img class="img-slider img-responsive" src="<?=base_url("media/top_gallery/mrc.jpg")?>" title="">
    </div>


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