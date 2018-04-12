<!-- Page content -->
<div class="page-content">

    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-archive"></i> Noticias <small></small></h5>
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
             echo '<br>';
             echo form_open_multipart('panel_news/upd/'.$id,'role="form" class="form-horizontal form-bordered"');
             ?>
                <input type="hidden" name="hddId" value="<?php echo $id; ?>">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">Editar
                        </h6>
                    </div>
                    <div class="panel-body">
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="selEstatus">Estatus:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="selEstatus" name="selEstatus">
                                    <option value="1" <?php echo $selEstatus==1 ? 'selected="selected"' : ''; ?>>Publicada</option>
                                    <option value="0" <?php echo $selEstatus==0 ? 'selected="selected"' : ''; ?>>No Publicada</option>
                                </select>
                             </div>
                        </div>

                       <div class="form-group">
                            <label class="col-sm-2 control-label">Título (Inglés):<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="txtTitulo_en" name="txtTitulo_en" 
                                 placeholder="" value="<?php echo isset($txtTitulo_en) ? $txtTitulo_en : ''; ?>">
                            </div>
                       </div>

                       <div class="form-group">
                            <label class="col-sm-2 control-label">Título (Español):<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="txtTitulo_es" name="txtTitulo_es" 
                                 placeholder="" value="<?php echo isset($txtTitulo_es) ? $txtTitulo_es : ''; ?>">
                            </div>
                       </div>


                        <div class="form-group">
                            <label class="col-sm-2 control-label">Texto (Inglés):<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <textarea rows="20" type="text" class="form-control" id="txtTexto_en" name="txtTexto_en"
                                          placeholder=""><?php echo isset($txtTexto_en) ? $txtTexto_en : ''; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Texto (Español):<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <textarea rows="20" type="text" class="form-control" id="txtTexto_es" name="txtTexto_es"
                                          placeholder="" ><?php echo isset($txtTexto_es) ? $txtTexto_es : ''; ?></textarea>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-lg-6">
                                <img class="img-responsive" src="<?php echo image($imagen_actual, 'news'); ?>" />
                            </div>
                            <div class="col-lg-6">
                                <label>Seleccione una nueva imagen sólo si desea cambiar la actual</label>
                                <input type=file size=60 name="filFILE"><br><br>
                                <p class="red">Recomedado tamaño de imagen 800x800 píxeles.</p>
                            </div>
                        </div>
                        
                       <div class="form-actions text-right">
                            <input type="submit" name="btEditar" class="btn btn-primary" value="Editar">
                       </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /with titles (frame) -->
</div>