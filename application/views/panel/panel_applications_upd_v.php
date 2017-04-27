<!-- Page content -->
<div class="page-content">

    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-archive"></i> Aplicaciones del Producto <small></small></h5>
        <div class="btn-group">
        </div>
    </div>
    <!-- /page title -->

    <div class="row">
        <div class="col-lg-12">
            <div>
                <a class="btn btn-default" href="<?php echo site_url('product').'/index/'.$url;?>">Ver el Producto</a>
                <a class="btn btn-default" href="<?php echo site_url('panel_products').'/upd/'.$id_producto;?>">Editar el Producto</a>
                <hr>
            </div>
            <?php
             if(isset($sMsjError) and $sMsjError){
               show_msj_error($sMsjError,false);
             }elseif(isset($sMsjConf) and $sMsjConf){
               show_msj_confirmacion($sMsjConf,false);
             }
             echo '<br>';
             echo form_open('panel_applications/upd/'.$id,'role="form" class="form-horizontal form-bordered"');
             ?>
                <input type="hidden" name="hddId" value="<?php echo $id; ?>">
                <input type="hidden" name="hddURL" value="<?php echo $url; ?>">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title"><b>Editar Aplicación del Producto. (Código SKU: <?php echo isset($sku) ? $sku : ''; ?>)</b>
                        </h6>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="selMarcas">Marca / Modelo: <span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-5">
                                <select class="form-control" id="selMarcas" name="selMarcas"></select>
                            </div>
                            <div class="col-sm-5">
                                <select class="form-control" id="selModelos" name="selModelos"></select>
                             </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="selTiposMotores">Tipo de Motor:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="selTiposMotores" name="selTiposMotores"></select>
                             </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Años:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="txtYears" name="txtYears" 
                                 value="<?php echo isset($txtYears) ? $txtYears : ''; ?>">
                                <p>Puede introducir varios años separandolos por coma (ej. 1978, 2010, 2018)</p>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="selEstatus">Estatus:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="selEstatus" name="selEstatus">
                                    <option value="1" <?php echo $selEstatus==1 ? 'selected="selected"' : ''; ?>>Activo</option>
                                    <option value="0" <?php echo $selEstatus==0 ? 'selected="selected"' : ''; ?>>Inactivo</option>
                                </select>
                             </div>
                        </div>
                        <div class="form-actions text-right">
                            <input type="submit" name="btEditar" class="btn btn-primary" value="Editar Aplicación">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /with titles (frame) -->
</div>