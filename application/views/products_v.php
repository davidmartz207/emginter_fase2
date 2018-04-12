<script>
    //variables para el select dinamico con jcombo
    var dirproductos  = '<?php echo site_url('products'); ?>'
    var selected_value = "<?php echo (isset($selTiposProductosDownloads) ? $selTiposProductosDownloads : 0); ?>";
    var initial_text = "<?php echo lang('selected_product_type'); ?>";
    pagina = "products";

    var strlinea = '<?php echo $this->arrDatos["linea"];?>';
    //si la busqueda está iniciada

</script>

<div class="container">
    <div class="row">
        <div class="col-lg-4  logo-linea-parent" id="emg-line-logo">
            <img id="imgemg" src="<?=base_url("media/lineas/emg.jpg")?>"class="logo-linea img-responsive <?php if($linea=="emg"){ echo "logo-linea-active"; } ?> " data-linea="emg">
        </div>
        <div class="col-lg-4 logo-linea-parent" id="perfection-line-logo">
            <img id="imgperfection" src="<?=base_url("media/lineas/perfection.jpg")?>" class="logo-linea img-responsive <?php if($linea=="perfection"){ echo "logo-linea-active"; } ?> " data-linea="perfection">
        </div>
        <div class="col-lg-4 logo-linea-parent" id="mrc-line-logo">
            <img id="imgmrc" src="<?=base_url("media/lineas/mrc.jpg")?>" class="logo-linea img-responsive <?php if($linea=="mrc"){ echo "logo-linea-active"; } ?> " data-linea="mrc">
        </div>
    </div>
</div>

<div class="row" id="form-products" style="<?php if($linea!="perfection"&&$linea!="mrc"&&$linea!="emg"){ echo "visibility: hidden;"; } ?>">


	<div class="container page-producto">

		<div  class=" formulario-productos" style="visibility: hidden;">

        <?php
            echo '<div class="row">
						<div class="colg-12 texto-global text-center">
							<p>'.get_config_db('texto_productos').'</p>
						</div>
				   </div>';
            ?>
			<?php
			if(isset($sMsjError) and $sMsjError){
			  show_msj_error($sMsjError);
			}elseif(isset($sMsjConf) and $sMsjConf){
			  show_msj_confirmacion($sMsjConf);
			}
			?>
            <form  id="formSearchProducts" role="form" method="POST" action="<?php echo site_url('products'); ?>">
                <input type="hidden" name="linea" id="linea" value="<?php if($linea=="mrc"){ echo "mrc"; }if($linea=="emg"){ echo "emg"; }if($linea=="perfection"){ echo "perfection"; }?>">
			   <!--
				<label for="debug" style="color:red;font-weight: bold;margin-bottom: 10px;">Debug: <?php echo "$type_search"; ?></label>
				-->

			    <!-- Col #1 -->
                <div class="col-lg-1"></div>

                <div class="col-lg-4 ">
			        <div class="etiqueta">
			            <div class="etiqueta-texto-20"><?php echo lang('label_sarch_product'); ?></div>
			        </div>



			        <br><br><br>

			        <input type="hidden" id="hddLastField" name="hddLastField" value=""/>
					<div class="form-group">
						<label class="lblTiposProductos" for="selTiposProductosDownloads"><?php echo lang('field_type_product_1'); ?></label>
						<div class="input-group">
							<select class="form-control" id="selTiposProductosDownloads" name="selTiposProductosDownloads"></select>
							<span class="input-group-btn">
								<button type="submit" disabled="disabled" id="btSearchProductType" name="btSearchProductType" value="1" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
							</span>
						 </div><!-- /input-group -->
					</div>
					<hr>
					<div id="sku_changeable" class="form-group">
						<label class="lblSKU" data-lbl-emg="<?php echo lang('field_sku_emg'); ?>" data-lbl-perfection="<?php echo lang('field_sku_perfection'); ?>" data-lbl-mrc="<?php echo lang('field_sku_mrc'); ?>" for="txtSKU"><?php echo lang('field_sku_gen'); ?></label>
						<div class="input-group">
							<input type="text" class="form-control"
									   placeholder="<?php echo lang('field_sku_placeholder_gen'); ?>"
                                        data-placeholder-emg="<?php echo lang('field_sku_placeholder_emg'); ?>"
                                        data-placeholder-perfection="<?php echo lang('field_sku_placeholder_perfection'); ?>"
                                        data-placeholder-mrc="<?php echo lang('field_sku_placeholder_mrc'); ?>"
                                           id="txtSKU" name="txtSKU"
									   value="<?php //echo isset($txtSKU) ? $txtSKU : ''; ?>">

							<span class="input-group-btn">
								<button type="submit" id="btSearchSKU" name="btSearchSKU" value="1" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
							</span>
						 </div><!-- /input-group -->
					</div>
			    </div>
                <div class="col-lg-2"></div>

			    <!-- Col #2 -->
			    <div class="col-lg-4" id="intercambio" >
			        <div class="etiqueta">
			            <div class="etiqueta-texto-20"><?php echo lang('label_interchange'); ?></div>
			        </div>
			        <br><br><br>
					<div class="form-group">
                                            <label class="lblCompetidor" for="txtCompetitor"><?php echo lang('field_competitor'); ?></label>
                                            <div class="input-group">
                                            <input type="text" class="form-control"
								 placeholder="<?php echo lang('field_competitor_placeholder'); ?>"
								 id="txtCompetitor" name="txtCompetitor"
								 value="<?php //echo isset($txtCompetitor) ? $txtCompetitor : ''; ?>">
                                            <div class="input-group-btn">
                                                <button type="submit" id="btSearchCompetitor" name="btSearchCompetitor" class="btn btn-default"  value="btSearchCompetitor"><i class="glyphicon glyphicon-search"></i></button>
                                            </div>
					 </div>
					</div>

			    </div>

                <div class="col-lg-1"></div>


				 <!-- form
				 <hr>
				 <div class="form-group">
					  <label class="lblSearchByVehicle">
						  <?php echo lang('label_search_by_vehicle'); ?>
						  <img id="img-processing-by-vehicle" class="disabled" src="<?php echo base_url(); ?>includes/images/ui-anim_basic_16x16.gif" alt="Processing"/>
					  </label>
				 </div>

				 <div id="divYears" class="form-group row">
					<label for="selYears" class="col-sm-4 control-label">
						<?php echo lang('field_years'); ?>
					</label>
					<div class="col-sm-8">
					  <select id="selYears" name="selYears" class="form-control border-red">
						  <?php
						  if(isset($arrYears)){
							  foreach($arrYears as $key => $val){
								  echo '<option value="'.$key.'">'.$val.'</option>';
							  }
						  }
						  ?>
					  </select>
					</div>
				 </div>

				 <div id="divMarcas" class="form-group row">
					<label for="selMarcas" class="col-sm-4 control-label"><?php echo lang('field_manufactute'); ?></label>
					<div class="col-sm-8">
					  <select id="selMarcas" name="selMarcas" class="form-control"></select>
					</div>
				 </div>

				 <div id="divModelos" class="form-group row">
					<label for="selModelos" class="col-sm-4 control-label"><?php echo lang('field_model'); ?></label>
					<div class="col-sm-8">
					  <select id="selModelos" name="selModelos" class="form-control"></select>
					</div>
				 </div>

				 <div id="divTiposMotores" class="form-group row">
					<label for="selTiposMotores" class="col-sm-4 control-label"><?php echo lang('field_engine_type'); ?></label>
					<div class="col-sm-8">
					  <select id="selTiposMotores" name="selTiposMotores" class="form-control"></select>
					</div>
				 </div>

				 <div id="divTiposProductos" class="form-group row">
					<label for="selTiposProductos" class="col-sm-4 control-label"><?php echo lang('field_type_product'); ?></label>
					<div class="col-sm-8">
					  <select id="selTiposProductos" name="selTiposProductos" class="form-control"></select>
					</div>
				 </div> -->

				 <div class="form-group row text-center" style="margin-top: 300px;">
				 <hr>
					 <button type="submit" id="btReset" name="btReset" class="btn btn-ppal" value="1"><?php echo lang('reset'); ?></button>
					 <!-- <button type="submit" id="btSearchPpal" name="btSearchPpal" class="btn btn-ppal" value="1" disabled="disabled"><?php echo lang('search'); ?></button> -->
				 </div>
			</form>
		</div>

		<div class="col-lg-12 div-contenido">
				<?php


				//echo '<pre>',print_r($arrProducts),'</pre>';
				if(isset($arrProducts) and is_array($arrProducts) and count($arrProducts)>0){

					$arrIds = array();
					foreach($arrProducts as $item){
						$arrIds[] = $item['id'];
						?>
						<div class="row product-row" id="listadoproducts">
							<?php
							echo '<div class="col-lg-2" style="text-align:center;">
									<a href="'.site_url('product').'/index/'.$item['url_post'].'">
										<img class="img-responsive" src="'.image($item['path_img'], 'product').'" style="width: 100px;">
									</a>
								</div>';
							echo '<div class="col-lg-10 product-content">';
								echo '<div class="titulo">
										<a href="'.site_url('product').'/index/'.$item['url_post'].'">'.$item['titulo'].'</a>
									  </div>';
								echo '<div class="datos-linea">'.$item['datos_linea'].'</div>';
								echo '<div class="descripcion"><p>'.$item['descripcion'].'</p></div>';

								if(isset($item['arrApplicaciones']) and is_array($item['arrApplicaciones'])){ ?>
									 <div class="panel panel-default">
										 <div class="panel-heading"><h6 class="panel-title"><?php echo lang('applications'); ?></h6></div>
										 <div class="table-responsive">
											 <table class="table table-striped table-bordered">
												 <thead>
													 <tr>
														  <th style="min-width:270px;"><?php echo lang('field_manufactute_model'); ?></th>
                                                         <?php
                                                         if($session>0){
                                                         ?>
														  <th style="min-width:140px;"><?php echo lang('field_engine_type'); ?></th>
														  <th><?php echo lang('field_years'); ?></th>
                                                          <?php
                                                         }
                                                             ?>
													 </tr>
												 </thead>
												 <tbody>
													 <?php
													 foreach($item['arrApplicaciones'] as $arrData){
                                                         $anios=explode("-",$arrData['years']);

                                                         if(count($anios)>1){
                                                             $anio=$anios[1].'-'.$anios[0];
                                                         }
                                                         elseif(count($anios)==1){
                                                             $anio=$anios[0];

                                                         }

														 echo '<tr>
															 <td>'. $arrData['marca_modelo'].'</td>';

                                                         if($session>0){

															 echo '<td>'.$arrData['tipo_motor'].'</td>
															 <td>'.$anio.'</td>';
                                                         }
														 echo '</tr>';
													 } ?>
												 </tbody>
											 </table>
										 </div>
									 </div>
								<!-- /table with footer -->
								<?php }#end tabla

								echo '<div class="control-cesta">
										<a class="btn btn-ppal" href="'.site_url('product').'/index/'.$item['url_post'].'">'.lang('more').'</a>                                   
									  </div>';

							echo '</div><!-- product-content -->';
							?>
						</div><!-- row product-row -->
						<?php
						}#endforeach

						# se encriptan los ids
						/*$str_ids = implode('|',$arrIds);
						$str_ids = base64_encode($str_ids);

						echo '<div class="print_result icon-pdf">
							 <a title="'.lang('print_out').'" target="_blank" href="'.site_url('products').'/print_out"><img src="'.base_url().'includes/images/icon-print.png"></a>';

						if(isset($SESS_searcht) and $SESS_searcht<>'btSearchProductType'){
								echo '<a title="'.lang('print_pdf').'" href="'.site_url('products').'/pdf"><img src="'.base_url().'includes/images/icon-pdf.png"></a>';
						}

						echo '</div>';
						*/
						?>
						<div class="pagination"><?php echo (isset($pagination) ? $pagination : ''); ?></div>
					<?php
					# sihubo error
					}elseif(isset($msjError) and $msjError){
						echo '<div>';
							 show_msj_error($msjError,false);
						 echo '</div>';
					# si no hay mas que mostrar
					}
					?>
			   </div><!-- col-lg-8 div-contenido -->
			</div>
		</div>
	</div>
</div>

<?php
if(isset($arrProducts) and is_array($arrProducts) and count($arrProducts)>0){
?>
    <script type="text/javascript">

        window.onload = function () {
            ScrollTo();
        }

    function ScrollTo(name="listadoproducts") {
    ScrollToResolver(document.getElementById(name));
    }

    function ScrollToResolver(elem) {
    var jump = parseInt(elem.getBoundingClientRect().top * .2);
    document.body.scrollTop += jump;
    document.documentElement.scrollTop += jump;
    if (!elem.lastjump || elem.lastjump > Math.abs(jump)) {
    elem.lastjump = Math.abs(jump);
    setTimeout(function() { ScrollToResolver(elem);}, "100");
    } else {
    elem.lastjump = null;
    }
    }


    </script>
<?php
}
?>
