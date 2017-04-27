<!-- Page content -->
<div class="page-content">

    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-archive"></i> Marcas <small></small></h5>
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
             echo form_open_multipart('panel_manufacturer/upd/'.$id,'role="form" class="form-horizontal form-bordered"');
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
                                    <option value="1" <?php echo $selEstatus==1 ? 'selected="selected"' : ''; ?>>Activo</option>
                                    <option value="0" <?php echo $selEstatus==0 ? 'selected="selected"' : ''; ?>>Inactivo</option>
                                </select>
                             </div>
                        </div>

                       <div class="form-group">
                            <label class="col-sm-2 control-label">Nombre:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="txtNombre" name="txtNombre" 
                                 placeholder="" value="<?php echo isset($txtNombre) ? $txtNombre : ''; ?>">
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