<section class="row">

    <article id="article" class="container bloque-ultimo-producto">
        <div class="etiqueta">
            <div class="etiqueta-texto-20">
                <?php
                if (get_id_idioma() == 3) {
                    echo "NOTICIAS";
                } elseif (get_id_idioma() == 2) {
                    echo "NEWS";
                }
                ?>
            </div>
        </div>
        <br>
        <?php
        if (isset($arrContent) and is_array($arrContent)) {
            foreach ($arrContent as $news) {
                if ($news['estatus'] == 1) {
                    ?>

                    <div class="row">
                        <div class="col-lg-8 div-contenido">
                            <h1 class="titulo" style="color:red;">
                                <?php echo $news['titulo'];
                                ?>
                            </h1>
                            <div class="descripcion"><?php echo $news['contenido']; ?></div>
                        </div>
                        <div class="col-lg-4 div-imagen">
                            <?php echo '<img class="img-responsive" src="' . image('media/news/pt' . $news['path_img'], 'img_news') . '"
                            alt="' . $news['titulo'] . '" title="' . $news['titulo'] . '">';
                            ?>
                        </div>
                    </div>

                    <hr>
                <?php }
            }
        } else {
            echo '<div class="col-lg-12">';

            show_msj_error('Page Not Found', false);
            echo '</div>';
        }
        ?>
    </article>
</section>
