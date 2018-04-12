<!-- Page content -->
<div class="page-content">

    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-archive"></i> Tipos de Motores <small></small></h5>
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
             echo form_open_multipart('panel_engine_type/ins','role="form" class="form-horizontal form-bordered"');
             ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">Agregar
                        </h6>
                    </div>
                    <div class="panel-body">

                       <div class="form-group">
                            <label class="col-sm-2 control-label">Nombre:<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="txtNombre_en" name="txtNombre_en" 
                                 placeholder="" value="<?php echo isset($txtNombre_en) ? $txtNombre_en : ''; ?>">
                            </div>
                       </div>

                       <!--div class="form-group">
                            <label class="col-sm-2 control-label">Nombre (Español):<span class="asterisco_rojo">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="txtNombre_es" name="txtNombre_es" 
                                 placeholder="" value="<?php echo isset($txtNombre_es) ? $txtNombre_es : ''; ?>">
                            </div>
                       </div-->

                       <div class="form-actions text-right">
                            <input type="submit" name="btSubmit" class="btn btn-primary" value="Crear">
                       </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /with titles (frame) -->
</div>