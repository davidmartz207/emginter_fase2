<!-- Page content -->
<div class="page-content">

    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-table"></i> Contenidos <small></small></h5>
        <div class="btn-group">
            <a href="#" class="btn btn-link btn-lg btn-icon dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i></a>
        </div>
    </div>
    <div class="row text-left">
            <a class="btn btn-primary" href="<?php echo site_url('panel_articulos/ins') ?>"><i class="fa fa-gavel"></i> Crear Artículo</a>
    </div>
    <hr>
    <?php
        if(isset($sMsjError) and $sMsjError){
          show_msj_error($sMsjError,false);echo '<br>';
        }elseif(isset($sMsjConf) and $sMsjConf){
          show_msj_confirmacion($sMsjConf,false);echo '<br>';
        }
             
        $flag = false;
        if(!is_array($arrDatos) or !count($arrDatos)>0){
           show_msj_info('<b>No se han encontrado registros.</b>',false);
           echo '<br>';
        }else{
            $flag= true;
        }
    ?>
    <!-- Table with footer -->
    <div class="panel panel-default">
        <div class="panel-heading"><h6 class="panel-title"></h6></div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width:30px;"><span title="Identificador">ID</span></th>
                        <th style="width:200px;">Tipo</th>
                        <th style="width:400px;">Título (Ingles / Español)</th>
                        <?php echo $restringir ? '' : '<th>Usuario</th>'; ?>
                        <th>Fecha Creación</th>
                        <th style="width:70px;">Estatus</th>
                        <th style="width:150px;">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($flag){
                        foreach($arrDatos as $index => $arrData){
                            echo '<tr>
                                <td>'.$arrData['id'].'</td>
                                <td>'.$arrData['tipo_contenido'].'</td>
                                <td><a target="_blank" href="'.$arrData['link'].'">'.$arrData['titulo_en'].'</a>
                                / <a target="_blank" href="'.$arrData['link'].'">'.$arrData['titulo_es'].'</a></td>';
                            echo $restringir ? '' : '<td>'.$arrData['usuario'].'</td>';
                            echo '<td>'.$arrData['fecha_registro'].'</td>
                                <td>'.$arrData['estatus'].'</td>
                                <td>';
                            echo '<div class="table-controls">';
                            if($arrData['estatus'] == 'Activo'){
                                echo '<a target="_blank" href="'.$arrData['link']
                                           .'" class="btn btn-default btn-icon btn-xs tip" title="Ver"><i class="fa fa-eye"></i>
                                      </a>';
                            }
                            echo '<a href="'.site_url('panel_articulos').'/upd/'.$arrData['id'].'" class="btn btn-default btn-icon btn-xs tip" title="Editar"><i class="fa fa-pencil"></i></a>';
                            echo $restringir ? '' : '<a href="'.site_url('panel_articulos').'/del/'.$arrData['id'].'" class="btEliminar btn btn-default btn-icon btn-xs tip" title="Eliminar"><i class="fa fa-times"></i></a>';
                            echo '</div>';
                            echo '
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
                <form action="<?php echo site_url('panel_articulos/index'); ?>" method="POST">
                    <select name="selOrderBy" class="select-liquid">
                        <option value="">Ordenar por</option>
                        <option value="1">ID</option>
                        <option value="2">Título En</option>
                        <option value="3">Título Es</option>
                        <?php echo $restringir ? '' : '<option value="4">Usuario</option>'; ?>
                        <option value="5">Estatus</option>
                        <option value="6">Fecha de Creación</option>
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