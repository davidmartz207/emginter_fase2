<?php
if(isset($show_news) and $show_news){
?>
<div class="row">
    <div class="container">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <i class="rojo"><h2 style="margin-top: 0px;margin-bottom: 0px;font-size:25px;">
                        <?php
                            if(get_id_idioma()==3){
                            echo "NOTICIAS";
                            }
                            elseif(get_id_idioma()==2){
                            echo "NEWS";
                            }
                        ?>
                        </h2></i>
                </div>
            </div>
            <div class="row">
        <?php
    if(isset($show_news) and $show_news){
        $arrNewsTop = get_news();
    }
    foreach($arrNewsTop as $news){
    ?>
        <a href="<?=site_url("news/index/".$news["id"])?>" style="color:black;">
            <div class="col-lg-2">
                <div class="row">
                    <div class="col-lg-12" style="min-height: 150px;">
                        <img class="img-responsive" src="<?=base_url($news["path_img"])?>" >
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <h5>
                            <?=$news["titulo"]?>
                        </h5>
                    </div>
                </div>
            </div>
            </a>
    <?php
    }
    ?>
            </div>
        </div>
    </div>
</div>
<?php
}
?>