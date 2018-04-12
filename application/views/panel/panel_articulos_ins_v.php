
<!-- Page content -->
<div class="page-content">
    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-laptop"></i> Crear contenido<small></small></h5>
        <div class="btn-group">
        </div>
    </div>
    <!-- /page title -->

    <div class="row">
        <div class="col-lg-12">
            <?php
             if(isset($sMsjError) and $sMsjError){
               show_msj_error($sMsjError,false);
             }elseif(isset($sMsjConf) and $sMsjConf){
               show_msj_confirmacion($sMsjConf,false);
             }
             echo form_open_multipart('panel_articulos/ins','role="form"');?>
                <br>  
                <div class="row">
                      <div class="col-lg-12">
                          <div class="form-group">
                                 <label for="selTiposContenidos">Tipo de Contenido</label>
                                <select class="form-control" name="selTiposContenidos">
                                  <?php
                                      foreach($arrTiposContenidos as $i => $item){
                                          echo '<option value="'.$i.'" '
                                                  .((isset($selTiposContenidos) AND $selTiposContenidos==$i) ? ' selected' : '')
                                                  .'>'.$item.'</option>';
                                      }
                                   ?>   
                              </select>
                          </div>
                      </div>
                  </div>
                  <hr>
                  <div class="row">
                       <div class="col-lg-12">
                            <div class="form-group">
                                 <label for="txtAsunto_en">Título (Ingles)</label>
                                 <input type="text" class="form-control" id="txtAsunto_en" name="txtAsunto_en" 
                                        placeholder="" value="<?php echo $txtAsunto_en; ?>" maxlength="250">
                            </div>
                       </div>
                  </div>
                  <div class="row">
                     <div class="col-lg-12">
                         <label for="txtaDescripcion_en">Contenido (Ingles)</label>
                         <textarea maxlength="<?php echo $limit_caracteres; ?>" class="form-control" id="txtaDescripcion_en" name="txtaDescripcion_en" placeholder="Contenido en Ingles" rows="10"><?php echo $txtaDescripcion_en; ?></textarea>
                         <?php echo display_ckeditor($ckeditor_en); ?>
                     </div>
                  </div>
                  <hr>
                  <div class="row">
                    <label class="col-sm-2 control-label">Imagen:</label>
                    <div class="col-sm-10">
                        <input type="file" name="filFILE">
                        <p class="red">Recomedado tamaño de imagen 300x230 píxeles.</p>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                       <div class="col-lg-12">
                            <div class="form-group">
                                 <label for="txtAsunto_es">Título (Español)</label>
                                 <input type="text" class="form-control" id="txtAsunto_es" name="txtAsunto_es" 
                                        placeholder="" value="<?php echo $txtAsunto_es; ?>" maxlength="250">
                            </div>
                       </div>
                  </div>
                  <div class="row">
                     <div class="col-lg-12">
                         <label for="txtaDescripcion_es">Contenido (Español)</label>
                         <textarea maxlength="<?php echo $limit_caracteres; ?>" class="form-control" id="txtaDescripcion_es" name="txtaDescripcion_es" placeholder="Contenido en Español" rows="10"><?php echo $txtaDescripcion_es; ?></textarea>
                         <?php echo display_ckeditor($ckeditor_es); ?>
                     </div>
                  </div>
                  <hr>
                  <hr>
                  <div class="row">
                     <div class="col-lg-12">
                        <a href="<?php echo site_url('panel_articulos'); ?>" class="btn btn-default text-center">Lista de Artículos</a>
                        <button type="submit" name="btSubmit" value="btSubmit" class="btn btn-success text-center"><span class="fa fa-save"> Publicar</button>
                     </div>   
                  </div>
                  <br><br>
             </form>
        </div>
    </div>
    <!-- /with titles (frame) -->