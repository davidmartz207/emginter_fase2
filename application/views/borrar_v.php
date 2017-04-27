<!-- load background home -->
<div class="backdefault">
     <div class="container">
          <div class="row">
               <div class="col-lg-4" id="logointer">
                    <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>includes/images/img/logo-soulseekrs-inter.png"></a>
               </div>
               <?php $this->load->view('includes/menu'); ?>
          </div>
          <div class="row" id="perfil">
               <div class="col-lg-4 col-lg-offset-2 text-center">
                    <div id="carousel-wrapper_master">
			<div id="carousel-wrapper">
				<div id="carousel">
                                    <?php if(is_array($arrImagenes)){
                                        foreach($arrImagenes as $index => $arrImagen){
                                            echo '<span id="'.$arrImagen['id_slider'].'">
                                                    <img src="'.$arrImagen['ruta_img'].'">
                                                  </span>';
                                        }
                                    }else{
                                        echo '<span id="default"><img style="width:250px;height:285px;" src="'.base_url()
										     .'includes/images/img/icono-perfil.png" ></span>';
                                    }
                                    ?>
				</div>
			</div>
                        <?php 
                        if(is_array($arrImagenes)){
                            echo '<div id="thumbs-wrapper">
                                  <div id="thumbs">';
                            foreach($arrImagenes as $index => $arrImagen){
                                echo '<a href="#'.$arrImagen['id_slider'].'" '
                                        .($arrImagen['es_img_principal'] ? 'class="selected"' : '').'>
                                        <img src="'.$arrImagen['ruta_thumb'].'" />
                                      </a>';
                            }
                            echo '
                            </div>
                            <a id="prev" href="#"></a>
                            <a id="next" href="#"></a>
                            </div>';
                        }else{
                            show_msj_info('No se encontraron imágenes miniaturas.');
                            echo '<br>';
                        }
                        ?>
		</div>
               </div>              
               <div class="col-lg-4" id="perfil-info">
                    <div id="contador_ofrendas" title="Se han hecho <?php echo $arrPerfil['num_ofrendas']; ?> Ofrendas.">
                        <span>
                            <?php echo empty($arrPerfil['num_ofrendas']) ? 0 : $arrPerfil['num_ofrendas']; ?>
                        </span>
                    </div>
                    <h1><strong><?php echo $arrPerfil['primer_nombre'].' '.$arrPerfil['segundo_nombre']; ?><br/>
                    <?php echo $arrPerfil['primer_apellido'].' '.$arrPerfil['segundo_apellido']; ?></strong></h1>
                    <p><span><strong><?php echo $arrPerfil['fecha_rango']; ?></strong></span></p>
                    <br/>
                    <p><?php echo $arrPerfil['descripcion']; ?></p>
                    <div style="text-align:right;" id="share_buttons"></div>
               </div>
          </div>
     </div>
</div>
<!-- end background home -->

<!-- load background red perfil -->
<div class="backredperfil">
    <a name="addcoment"></a>
     <div class="container" id="addcoment">
          <div class="row" >
               <div class="col-lg-12 text-center">
                    <h3>Nuevo Comentario</h3>
                    <p>Completa el siguiente formulario y envía un comentario a tu ser querido</p>
               </div>
          </div>
          <div id="div_comment_error" class="alert alert-danger alert-dismissable" style="display: none;"></div>
          <div id="div_comment_info" class="alert alert-info alert-dismissable" style="display: none;"></div>
          <form action="<?php echo site_url('perfil/submit'); ?>" class="form-inline" id="form_coment" method="POST">
            <input type="hidden" id="hdd_id_perfil_sq" name="hdd_id_perfil_sq" value="<?php echo $arrPerfil['id_perfil_sq']; ?>">
            <input type="hidden" id="hdd_code" name="hdd_code">
            <input type="hidden" id="hdd_res" name="hdd_res">
            <input type="hidden" id="hdd_optImg" name="hdd_optImg">
            <input type="hidden" id="hdd_submit_type" name="hdd_submit_type">
            <div class="row" id="formcoment">
                <div id="comment-txt-name" class="col-lg-4 col-lg-offset-2">
                    <input type="text" class="form-control" placeholder="Ingrese su nombre"
                    id="txtNombre" name="txtNombre" value="<?php echo isset($sMsjConf) ? '' : set_value('txtNombre'); ?>">
                </div>
                <div id="comment-txt-email" class="col-lg-4">
                    <input type="email" class="form-control" placeholder="Ingrese su email"
                    id="txtEmail" name="txtEmail" value="<?php echo isset($sMsjConf) ? '' : set_value('txtEmail'); ?>">
                </div>
                <div id="comment-txt-comment" class="row">
                     <div class="col-lg-8 col-lg-offset-2">
                          <textarea class="form-control" id="txtComentario" name="txtComentario" 
                          placeholder="Ingrese su comentario" maxlength="800" rows="3"><?php echo isset($sMsjConf) ? '' : set_value('txtComentario'); ?></textarea>
                     </div>
                </div>
                <div class="row text-center">
                     <div class="col-lg-8 col-lg-offset-2">
                          <button type="button" id="submit_coment" class="btn btn-default">Enviar</button>
                     </div>
                </div>
              </div>
          </form>
     </div>
</div>
<!-- end background red perfil-->

<!-- load comentarios -->
<div class="backcoment">
     <div class="container">
          <?php
            if(isset($sMsjConf) and $sMsjConf){
                show_msj_confirmacion($sMsjConf);
            }
          ?>
          <div class="row text-center">
               <div class="col-lg-6 col-lg-offset-3">
                    <h4>Últimos Comentarios</h4>
               </div>
          </div>
          <?php
              if(!is_array($arrComentarios) or !count($arrComentarios)>0){
                 show_msj_info('<b>No se han encontrado Comentarios Asociados.</b>');
                 echo '<br>';
              }else{ 
                  foreach($arrComentarios as $index => $arrComentario){
                      $str = '|'.$arrComentario['id_comentario'].'|'.$arrComentario['id_perfil_sq'];
                      $id  = 'comment-'.$arrComentario['id_comentario'].'-'.$arrComentario['id_perfil_sq'];
                      echo '<div class="row">
                          <a id="'.$id.'" name="'.$id.'"></a>
                                <div class="col-lg-6 col-lg-offset-3 allcoments">
                                     <h5>'.$arrComentario['autor']
                              .(es_mi_perfil($arrComentario['id_perfil_sq']) 
                           ? anchor('perfil/comment/'.urlencode(base64_encode('del'.$str)).'/#addcoment'
                             ,'<span style="float:right;cursor:pointer;" 
                               title="Eliminar comentario" class="glyphicon glyphicon-remove"></span>'
                               ,' class="comment_del" style="color: red"') : '')
                              .'</h5><p>';
                      if($arrComentario['ruta_img']){
                        echo '<img style="width:64px;height:64px;" src="'.base_url()
                             .'includes/images/icons/'.$arrComentario['ruta_img']
                             .'" class="img-responsive" alt="regalo">';
                      }
                      echo $arrComentario['comentario'];
                      echo '</p>',anchor('perfil/comment/'.urlencode(base64_encode('rep'.$str)).'/#'.$id 
                            ,'<span style="float:right;cursor:pointer;margin-top:-10px;" 
                               title="Reportar comentario como ofensivo" class="glyphicon glyphicon-flag"></span>'
                               ,' class="comment_report" style=""');
                      
                      if(isset($sMsjConf) and isset($id_comment_report) and 
                         $sMsjConf and $arrComentario['id_comentario']==$id_comment_report){
                     show_msj_info('Comentario marcado como ofensivo.');
                        echo '<br>';
                      }
                      echo '</div></div>';
                  }
              }
          ?>
     </div>
</div>

<div id="icons_sell" style="display:none" title="AÑADE A TUS COMENTARIOS UNA DE ESTAS OFRENDAS">
	<?php //echo '<pre>',print_r($arrIcons),'</pre>';
		if(isset($arrIcons) and is_array($arrIcons) and count($arrIcons)>0){
			$html_icons_div = '';
			$c_icons        = 0;
			echo '<ul class="nav nav-tabs" id="myTab">';
			foreach($arrIcons as $index => $arrIcon){
				#icons tabs
				echo '<li'.$arrIcon['active_tab'].'>
						<a title="'.$arrIcon['title'].'" data-toggle="tab" href="#'.$arrIcon['id_tipo_ofrenda'].'">
							<img class="icon-link" 
								src="'.base_url().'includes/images/icons/'.$arrIcon['name'].'.png" 
								class="img-responsive" alt="regalo">
						 </a> 
					  </li>';
				#sub icons
				$html_icons_div .= '<div id="'.$arrIcon['id_tipo_ofrenda'].'" class="tab-pane fade '.$arrIcon['active_div'].'">';
				$c_sub_icons = 0;
				foreach($arrIcon['arrIconsSub'] as $index => $arrIconsSub){
					$ruta_img = 'includes/images/icons/'.$arrIcon['name'].'/'.$arrIconsSub['name'];
					if(file_exists($ruta_img)){
						$id              = urlencode('opt-'.$arrIconsSub['id']);
						$rel             = encriptar_cadena($id . $arrIcon['name'] . $arrIconsSub['name']);
						$html_icons_div .= '<div class="icons-sub">
										   <img id="img-'.$arrIconsSub['id'].'" class="img-icons-sub" name="'.$rel.'"
										   src="'.base_url().$ruta_img.'" alt="">
										   <input type="radio" name="optIcons" id="'.$id.'" rel="'.$rel.'" 
										   value="'.urlencode(base64_encode($arrIconsSub['path'].'-'.$arrIconsSub['name'])).'"></div>';
						$c_sub_icons++;   
						//if($num_sub_icons==$c_sub_icons) break;
					}
				}#end foreach 2
				$html_icons_div .= '</div>';
				$c_icons++;   
				//if($num_icons==$c_icons) break;
			}#end foreach 1
			echo '</ul>';
			echo '<div class="tab-content" id="myTabContent">';
			   echo $html_icons_div;
			echo '</div>';
		}else{
			show_msj_warning('No se encontraron Iconos');
		}
	?>
	<div class="modal-footer"><!-- data-dismiss="modal" -->
		<div id="div_msj_error" class="alert alert-danger alert-dismissable" style="display: none;"></div>
		<div id="div_msj_info" class="alert alert-info alert-dismissable" style="display: none;"></div>
		<div class="buttons">
			<button type="button" id="submit_sell" class="btn btn-danger">Agregar Ofrenda</button>
			<a id="submit_free" class="text-link">ENVIAR SIN OFRENDA</a>
		</div>
	</div>
</div>