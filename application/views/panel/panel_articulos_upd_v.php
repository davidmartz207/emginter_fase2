<!-- Page content -->
<div class="page-content">
    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-laptop"></i> Editar contenido<small></small></h5>
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
             echo form_open_multipart('panel_articulos/upd/'.$id,'role="form"');?>
                  <input type="hidden" name="hddId" value="<?php echo $id; ?>">
                  <input type="hidden" name="hddURL" value="<?php echo $url_post; ?>">
                  <input type="hidden" name="hddImage" value="<?php echo $imagen_actual; ?>">
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
                  <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-lg-6">
                                <img src="<?php echo image('media/contenidos/'.$imagen_actual, 'texto_home'); ?>" />
                            </div>
                            <div class="col-lg-6">
                                <label>Seleccione una nueva imagen sólo si desea cambiar la actual</label>
                                <input type=file size=60 name="filFILE">
                                <p class="red">Recomedado tamaño de imagen 300x230 píxeles.</p>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="form-group">
                                 <label for="selEstatus">Estatus</label>
                                <select class="form-control" name="selEstatus">
                                  <?php
                                      foreach($arrEstatus as $i => $item){
                                          echo '<option value="'.$i.'" '
                                                .((isset($selEstatus) AND $selEstatus==$i) ? ' selected' : '').'>'.$item.'</option>';
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
                         <textarea maxlength="<?php echo $limit_caracteres; ?>" class="form-control" id="txtaDescripcion" name="txtaDescripcion_es" placeholder="Contenido en Español" rows="10"><?php echo $txtaDescripcion_es; ?></textarea>
                         <?php echo display_ckeditor($ckeditor_es); ?>
                     </div>
                  </div>
                  <hr>
                  <div class="row">
                     <div class="col-lg-12">
                        <a target="_blank" href="<?php echo $url_post; ?>" class="btn btn-info text-center">Ver Publicación</a>
                        <a href="<?php echo site_url('panel_articulos'); ?>" class="btn btn-default text-center">Lista de Artículos</a>
                        <button type="submit" name="btEditar" value="btEditar" class="btn btn-success text-center"><span class="fa fa-save"> Guardar Cambios</button> 
                     </div>   
                  </div>
                  <br><br>
             </form>
        </div>
    </div>
    <!-- /with titles (frame) -->