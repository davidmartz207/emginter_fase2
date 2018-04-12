<!-- Page content -->
<div class="page-content">

    <!-- Page title -->
        <div class="page-title">
        <h5><i class="fa fa-table"></i> Usuarios <small></small></h5>
        <div class="btn-group">
            <a href="#" class="btn btn-link btn-lg btn-icon dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i></a>
        </div>
    </div>
    <!-- /page title -->

    <div>
        <a class="btn btn-default" href="<?php echo site_url('panel_usuarios').'/ins';?>">Crear Usuario</a>
        <hr>
    </div>

    <?php
        $flag = false;
        if(!is_array($arrResult) or !count($arrResult)>0){
           show_msj_info('<b>No se han encontrado registros.</b>',false);
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
      ?>
    
    <!-- Table with footer -->
    <div class="panel panel-default">
        <div class="panel-heading"><h6 class="panel-title">Lista</h6></div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width:30px;"><span title="Identificador">ID</span></th>
                        <th>Tipo de Usuario</th>
                        <th>Nombre Completo</th>
                        <th>Email</th>
                        <th style="width:100px;">Estatus</th>
                        <th style="width:200px;">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($flag){
                        //echo '<pre>',print_r($arrResult),'</pre>';
                        foreach($arrResult as $item){
                            echo '<tr>
                                <td>'.$item['id'].'</td>
                                <td>'.$item['tipo_usuario'].'</td>
                                <td>'.$item['nombre'].' '.$item['apellido'].'</td>
                                <td>'.$item['email'].'</td>
                                <td>'.$item['estatus'].'</td>
                                <td>';
                            if(user_is_admin()){
                                echo '<div class="table-controls">';
                                
                                if($item['id']<>1 and $item['id_estatus'] == 2){
                                    $email = urlencode(base64_encode($item['email']));
                                    echo '<a href="'.site_url('panel_usuarios').'/enabled_user/'.$item['id'].'/'.$email.'" class="btAprobar btn btn-default btn-icon btn-xs tip" title="Aprobar la cuenta de usuario"><i class="fa fa-thumbs-up"></i> aprobar</a>';
                                }
                                            
                                echo '<a href="'.site_url('panel_usuarios').'/upd/'.$item['id'].'" class="btn btn-default btn-icon btn-xs tip" title="Editar"><i class="fa fa-pencil"></i></a>';
                                
                                if($item['id']<>1){
                                    echo '<a href="'.site_url('panel_usuarios').'/del/'.$item['id'].'" class="btEliminar btn btn-default btn-icon btn-xs tip" title="Eliminar"><i class="fa fa-times"></i></a>';
                                }
                                
                                echo '</div>';
                            } 
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
                <form action="<?php echo site_url('panel_usuarios'); ?>" method="POST">
                    <select name="selOrderBy" class="select-liquid">
                        <option value="">Ordenar por</option>
                        <option value="1">ID</option>
                        <option value="2">Tipo de Usuario</option>
                        <option value="3">Nombre</option>
                        <option value="4">Apellido</option>
                        <option value="5">Email</option>
                        <option value="6">Estatus</option>
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