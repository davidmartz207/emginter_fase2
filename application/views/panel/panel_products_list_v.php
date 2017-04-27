<!-- Page content -->
<div class="page-content">

    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-table"></i> Productos <small>puede ver, editar y eliminar cualquier producto.</small></h5>
        <div class="btn-group">
            <a href="#" class="btn btn-link btn-lg btn-icon dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i></a>
        </div>
    </div>
    <!-- /page title -->
    <form action="<?php echo site_url('panel_products'); ?>" method="POST" id="form_search_simple">
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label>Tipo de Producto:</label>
                    <select class="form-control" id="selTiposProductosN" name="selTiposProductos"></select>
                </div>
                <div class="col-sm-3">
                    <label>SKU / EMG:</label>
                    <input type="text" class="form-control text-input-limp" id="txtSKU" name="txtSKU"
                    placeholder="Introduzca el SKU / EMG" value="<?php echo isset($txtSKU) ? $txtSKU : ''; ?>">
                </div>
                <div class="form-actions" style="margin-top:10px;border-top:1px dashed #eee;padding-top: 10px;">
                    <button type="submit" name="btBuscar" value="btBuscar" class="btn btn-primary btn-xs"><i class="fa fa-search"> BUSCAR</i></button>
                </div>
            </div>
            
        </div>
    </form>
    <form action="<?php echo site_url('panel_products'); ?>" method="POST" id="form_search_advanced">
        
        <div id="panel-busqueda" class="panel panel-default">
            <div class="panel-heading">
                <h6 class="panel-title"><i class="fa fa-search"></i> FILTROS</h6>
                <a id="panel-busqueda-toogle" class="btn btn-default btn-xs">Ocultar / Mostrar <i class="fa fa-arrows-v"></i></a>

            </div>
            <div id="panel-busqueda-body" class="panel-body" style="display: none;">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Tipo de Producto:</label>
                            <select class="form-control" id="selTiposProductos" name="selTiposProductos"></select>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px;border-top:1px dashed #eee;padding-top: 10px;">
                        <div class="col-sm-3">
                            <label>SKU / EMG:</label>
                            <input type="text" class="form-control  text-input-limp" id="txtSKU" name="txtSKU"
                            placeholder="Introduzca el SKU / EMG" value="<?php echo isset($txtSKU) ? $txtSKU : ''; ?>">
                        </div>
                        <div class="col-sm-3">
                            <label>Número OE:</label>
                            <input type="text" class="form-control tagsinput text-input-limp" id="txtOEM" name="txtOEM"
                            placeholder="Introduzca el OE" value="<?php echo isset($txtOEM) ? $txtOEM : ''; ?>">
                        </div>
                        <div class="col-sm-3">
                            <label>SMP:</label>
                            <input type="text" class="form-control text-input-limp" id="txtSMP" name="txtSMP"
                            placeholder="Introduzca el SMP" value="<?php echo isset($txtSMP) ? $txtSMP : ''; ?>">
                        </div>
                        <div class="col-sm-3">
                            <label>WELLS:</label>
                            <input type="text" class="form-control text-input-limp" id="txtWELLS" name="txtWELLS"
                            placeholder="Introduzca el WELLS" value="<?php echo isset($txtWELLS) ? $txtWELLS : ''; ?>">
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px;border-top:1px dashed #eee;padding-top: 10px;">
                        <div class="col-sm-3">
                            <label>Año:</label> 
                            <input type="text" class="form-control text-input-limp" id="txtYears" name="txtYears"
                                 placeholder="Introduzca el(los) año(s)" value="<?php echo isset($txtYears) ? $txtYears : ''; ?>">
                            <span class="label label-info label-block text-center">
                                Separados por coma: 1978, 2010
                            </span>
                        </div>
                        <div class="col-sm-3">
                            <label>Marca:</label>
                            <select class="form-control" id="selMarcas" name="selMarcas"></select>
                        </div>
                        <div class="col-sm-3">
                            <label>Modelo:</label>
                            <select class="form-control" id="selModelos" name="selModelos"></select>
                            <span class="label label-info label-block text-center">
                                Seleccione Marca, luego Modelo
                            </span>
                        </div>
                        <div class="col-sm-3">
                            <label>Tipo de Motor:</label>
                            <select class="form-control" id="selTiposMotores" name="selTiposMotores"></select>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:10px;border-top:1px dashed #eee;padding-top: 10px;text-align: right;">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4">
                        <select name="selOrderBy" class="form-control">
                           <option value="0">Ordenar Por</option>
                           <!-- option value="1" <?php echo ($selOrderBy==1 ? 'selected="selected"' : ''); ?>>ID</option -->
                           <option value="2" <?php echo ($selOrderBy==2 ? 'selected="selected"' : ''); ?>>Tipo de Producto</option>
                           <option value="3" <?php echo ($selOrderBy==3 ? 'selected="selected"' : ''); ?>>SKU</option>
                           <option value="4" <?php echo ($selOrderBy==4 ? 'selected="selected"' : ''); ?>>OEM</option>
                           <option value="5" <?php echo ($selOrderBy==5 ? 'selected="selected"' : ''); ?>>SMP</option>
                           <option value="6" <?php echo ($selOrderBy==6 ? 'selected="selected"' : ''); ?>>WELLS</option>
                           <option value="20">Estatus</option>
                       </select>
                  </div>
                  <div class="col-sm-4">
                       <select name="selUpDown" class="form-control">
                            <option value="0">-- seleccione --</option>
                            <option value="1" <?php echo ($selUpDown==1 ? 'selected="selected"' : ''); ?>>De Menor a Mayor</option>
                            <option value="2" <?php echo ($selUpDown==2 ? 'selected="selected"' : ''); ?>>De Mayor a Menor</option>
                       </select>
                   </div>
                </div>

                <div class="form-actions text-right" style="margin-top:10px;border-top:1px dashed #eee;padding-top: 10px;">
                    <button type="submit" name="btBuscar" value="btBuscar" class="btn btn-primary btn-xs">BUSCAR <i class="fa fa-search"></i></button>
                    <button type="button" id="reset_search_form" name="btReset" value="btReset" class="btn btn-primary btn-xs">LIMPIAR <i class="fa fa-eraser"></i></button>

                </div>

            </div>
        </div>

        <?php
        if(isset($sMsjError) and $sMsjError){
          show_msj_error($sMsjError,false);
        }elseif(isset($sMsjConf) and $sMsjConf){
          show_msj_confirmacion($sMsjConf,false);
        }
        $flag = false;
        if(!is_array($arrProducts) or !count($arrProducts)>0){
           show_msj_error('<b>NINGÚN PRODUCTO COINCIDE CON LOS CRITERIOS DE BÚSQUEDA..</b> <span class="fa fa-frown-o"></span>',false);
           echo '<br>';
        }else{
            $flag= true;
        }

        if($flag){
        ?>
            <!-- Table with footer -->
            <div class="panel panel-default">
                <div class="panel-heading"><h6 class="panel-title">Lista de Productos</h6></div>

                <div class="table-footer">
                    <?php echo $pagination; ?>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width:30px;"><span title="Identificador">ID</span></th>
                                <th>Tipo</th>
                                <th>SKU</th>
                                <th>OEM</th>
                                <th>SMP</th>
                                <th>WELLS</th>
                                <th style="width:70px;">Estatus</th>
                                <th style="width:170px;">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if($flag){
                                foreach($arrProducts as $item){
                                    echo '<tr>
                                        <td>'.$item['id'].'</td>
                                        <td>'.$item['tipo_producto'].'</td>
                                        <td>'.$item['sku'].'</td>
                                        <td>'.$item['oem'].'</td>
                                        <td>'.$item['smp'].'</td>
                                        <td>'.$item['wells'].'</td>
                                        <td>'.$item['estatus'].'</td>
                                        <td>
                                             <div class="table-controls">
                                                <a href="'.site_url('panel_products').'/app/'.$item['id'].'" class="btn btn-default btn-icon btn-xs tip" title="Gestionar las aplicaciones de este Producto"><i class="fa fa-pencil"></i> App</a>
                                                <a href="'.site_url('product').'/index/'.$item['url_post'].'" 
                                                    class="btn btn-default btn-icon btn-xs tip" title="Ver"><i class="fa fa-eye"></i>
                                                </a>
                                                <a href="'.site_url('panel_products').'/upd/'.$item['id'].'" class="btn btn-default btn-icon btn-xs tip" title="Editar"><i class="fa fa-pencil"></i></a>
                                                <a href="'.site_url('panel_products').'/del/'.$item['id'].'" class="btEliminar btn btn-default btn-icon btn-xs tip" title="Eliminar"><i class="fa fa-times"></i></a>
                                            </div>                            
                                        </td>
                                    </tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <!-- div class="table-actions">
                        <select name="selOrderBy" class="select-liquid">
                            <option value="">Ordenar por</option>
                            <option value="1">ID</option>
                            <option value="2">Tipo de Prodcuto</option>
                            <option value="3">OEM</option>
                            <option value="4">SKU</option>
                            <option value="5">Estatus</option>
                        </select>
                        <select name="selUpDown" class="select-liquid">
                            <option value="1">ASC</option>
                            <option value="2">DESC</option>                        
                        </select>
                        <button class="btn btn-default btn-icon btn-xs" name="btSubmit" type="submit">
                            <i class="fa fa-hand-o-right"></i>
                        </button>
                    </div -->
                    <?php echo $pagination; ?>
                </div>
            </div>
            <!-- /table with footer -->
        <?php }?>
    </form>
