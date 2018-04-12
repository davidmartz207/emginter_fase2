<!-- Page content -->
<div class="page-content">

    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-table"></i> Catálogos <small>puede ver, editar y eliminar cualquier item.</small></h5>
        <div class="btn-group">
            <a href="#" class="btn btn-link btn-lg btn-icon dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i></a>
        </div>
    </div>
    <!-- /page title -->

    <!-- div>
        <a class="btn btn-default" href="<?php echo site_url('panel_catalogs').'/ins';?>">Agregar</a>
        <hr>
    </div -->

    <?php
        $flag = false;
        if(!is_array($arrResult) or !count($arrResult)>0){
           show_msj_info('<b>No se han agregado registros.</b>',false);
           echo '<br>';
        }else{
            $flag= true;
        }
    ?>
    
    <?php
            if(isset($sMsjError) and $sMsjError){
              show_msj_error($sMsjError,FALSE);
            }elseif(isset($sMsjConf) and $sMsjConf){
              show_msj_confirmacion($sMsjConf,FALSE);
            }
            echo '<br>';
      ?>
    
    <!-- Table with footer -->
    <div class="panel panel-default">
        <div class="panel-heading"><h6 class="panel-title">Lista</h6>  <a href="<?php echo site_url('panel_catalogs').'/ins/'; ?>" class="btn btn-default btn-icon btn-xs tip" title="Crear"><i class="fa fa-plus"></i></a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width:30px;"><span title="Identificador">ID</span></th>
                        <th>Linea</th>
                        <th>Nombre Ingles</th>
                        <th>Nombre Español</th>
                        <th style="width:70px;">Estatus</th>
                        <th style="width:100px;">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($flag){
                        //echo '<pre>',print_r($arrResult),'</pre>';
                        foreach($arrResult as $item){
                            echo '<tr>
                                <td>'.$item['id'].'</td>
                                <td>'.$item['linea'].'</td>
                                <td>'.$item['nombre_en'].'</td>
                                <td>'.$item['nombre_es'].'</td>
                                <td>'.$item['estatus'].'</td>
                                <td>
                                    <div class="table-controls">
                                        <a href="'.site_url('panel_catalogs').'/upd/'.$item['id'].'" class="btn btn-default btn-icon btn-xs tip" title="Editar"><i class="fa fa-pencil"></i></a>
                                        <a href="'.site_url('panel_catalogs').'/del/'.$item['id'].'" class="btEliminar btn btn-default btn-icon btn-xs tip" title="Eliminar"><i class="fa fa-times"></i></a>
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
            <div class="table-actions">
                <form action="<?php echo site_url('panel_catalogs'); ?>" method="POST">
                    <select name="selOrderBy" class="select-liquid">
                        <option value="">Ordenar por</option>
                        <option value="1">ID</option>
                        <option value="2">Nombre Ingles</option>
                        <option value="3">Nombre Español</option>
                        <option value="5">Estatus</option>
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
            <?php echo $pagination; ?>
        </div>
    </div>
    <!-- /table with footer -->