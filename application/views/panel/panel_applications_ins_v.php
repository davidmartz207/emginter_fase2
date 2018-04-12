<!-- Page content -->
<div class="page-content">

    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-archive"></i> Aplicaciones del Producto <small>En tan sólo dos pasos podrá crear productos y agregar sus aplicaciones</small></h5>
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
             echo form_open_multipart('panel_applications/ins','role="form" class="form-horizontal form-bordered"');
             ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">Paso <img src="<?php echo base_url().'includes/images/paso2.png';?>"> - 
                            <b>Aplicaciones al Producto. (Código SKU: <?php echo isset($sku) ? $sku : ''; ?>)</b>
                        </h6>
                    </div>
                    <div class="panel-body">
                        <div>
                            <a class="btn btn-default" href="<?php echo site_url('product').'/index/'.$url;?>">Ver el Producto</a>
                            <a class="btn btn-default" href="<?php echo site_url('panel_products').'/upd/'.$id_producto;?>">Editar el Producto</a>
                            <hr>
                        </div>
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
                        <div class="form-actions text-right">
                            <input type="submit" name="btSubmit" class="btn btn-primary" value="Agregar Aplicación">
                        </div>
                    </div>
                </div>
            </form>
            
            <hr>
            <!-- Table with footer -->
            <div class="table-footer">
                <div class="table-actions">
                    <form action="<?php echo site_url('panel_applications'); ?>" method="POST">
                        <select name="selOrderBy" class="select-liquid">
                            <option value="">Search por</option>
                            <option value="1">ID</option>
                            <option value="2">Marca</option>
                            <option value="3">Modelo</option>
                            <option value="4">Tipo de Motor</option>
                            <option value="5">Años</option>
                            <option value="6">Estatus</option>
                            <!-- option value="7">Fecha de Creación</option -->
                        </select>
                        <select name="selUpDown" class="select-liquid">
                            <option value="1">ASC</option>
                            <option value="2">DESC</option>                        
                        </select>
                        <button class="btn btn-default btn-icon btn-xs" type="submit">
                            <i class="fa fa-hand-o-right"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><h6 class="panel-title">Aplicaciones</h6></div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width:30px;"><span title="Identificador">ID</span></th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Tipo de Motor</th>
                                <th>Años</th>
                                <!-- th>Fecha de Creación</th -->
                                <th style="width:70px;">Estatus</th>
                                <th style="width:100px;">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($arrDatos) and is_array($arrDatos)){
                                foreach($arrDatos as $arrData){
                                    echo '<tr>
                                        <td>'.$arrData['id'].'</td>
                                        <td>'.$arrData['marca'].'</td>
                                        <td>'.$arrData['modelo'].'</td>
                                        <td>'.$arrData['tipo_motor'].'</td>
                                        <td>'.$arrData['years'].'</td>
                                        <!-- td>'.$arrData['fecha_registro'].'</td -->
                                        <td>'.$arrData['estatus'].'</td>';
                                    echo '<td><div class="table-controls">';
                                        echo '<a href="'.site_url('panel_applications').'/upd/'.$arrData['id'].'" class="btn btn-default btn-icon btn-xs tip" title="Edit"><i class="fa fa-pencil"></i></a>';
                                        echo '<a href="'.site_url('panel_applications').'/del/'.$arrData['id'].'" class="btEliminar btn btn-default btn-icon btn-xs tip" title="Delete"><i class="fa fa-times"></i></a>';
                                    echo '</div></td>';
                                    echo '</tr>';
                                }
                            }else{
                                show_msj_info('No se han encontrado aplicacciones para este producto.',false);
                            }#end $arrDatos ?>
                        </tbody>
                    </table>
                    <div class="pagination"><?php echo $pagination; ?></div>
                </div>
            </div>
            <!-- /table with footer -->
        </div>
    </div>
    <!-- /with titles (frame) -->
</div>