<div id="orders" class="row">
    <div class="container">
        <div class="col-lg-12">
            <?php
            if(isset($sMsjError) and $sMsjError){
              show_msj_error($sMsjError);
            }elseif(isset($sMsjConf) and $sMsjConf){
              show_msj_confirmacion($sMsjConf);
            }
            ?>
            <form id="grilla" role="form" method="POST" action="<?php echo site_url('orders'); ?>">
                 <div class="row primera-linea" style="margin-top:10px;">
                    <div class="col-lg-4">
                        <label for="txtPO_CO"><?php echo lang('field_po_co'); ?> <span class="asterisco_rojo">*</span></label>
                        <input type="text" class="form-control" maxlength="50" 
                             placeholder="" id="txtPO_CO" name="txtPO_CO" 
                             value="<?php echo isset($arrResult['po_co']) ? $arrResult['po_co'] : ''; ?>">
                    </div>
                     <div class="col-lg-4"></div>
                     <div class="col-lg-4"></div>
                </div>
                 <?php if(user_is_representante()){?>
                    <div class="row" style="margin-top:10px;">
                        <div class="col-lg-12">
                            <h4><?php echo strtoupper(lang('label_order_data')); ?></h4>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px;">
                        <div class="col-lg-6">
                            <label for="txtCustomer_name"><?php echo lang('field_customer_name'); ?> <span class="asterisco_rojo">*</span></label>
                            <input type="text" class="form-control" maxlength="255" 
                                 placeholder="" id="txtCustomer_name" name="txtCustomer_name" 
                                 value="<?php echo isset($arrResult['customer_name']) ? $arrResult['customer_name'] : ''; ?>">
                        </div>
                        <div class="col-lg-6">
                            <label for="txtaShip_to"><?php echo lang('field_ship_to'); ?> <span class="asterisco_rojo">*</span></label>
                            <textarea id="txtaShip_to" name="txtaShip_to" row="4"
                            class="form-control"><?php echo isset($arrResult['ship_to']) ? $arrResult['ship_to'] : ''; ?></textarea>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px;">
                        <div class="col-lg-6">
                            <label for="txtaCustomer_address"><?php echo lang('field_customer_address'); ?> <span class="asterisco_rojo">*</span></label>
                            <textarea id="txtaCustomer_address" name="txtaCustomer_address" row="4"
                            class="form-control"><?php echo isset($arrResult['customer_address']) ? $arrResult['customer_address'] : ''; ?></textarea>
                        </div>
                        <div class="col-lg-6">
                            <label for="txtaAddress"><?php echo lang('field_address'); ?> <span class="asterisco_rojo">*</span></label>
                            <textarea id="txtaAddress" name="txtaAddress" row="4"
                            class="form-control"><?php echo isset($arrResult['address']) ? $arrResult['address'] : ''; ?></textarea>
                        </div>
                   </div>
                   <div class="row" style="margin-top:10px;">
                        <div class="col-lg-6">
                            <label for="txtCustomer_po_num"><?php echo lang('field_customer_po_num'); ?> <span class="asterisco_rojo">*</span></label>
                            <input type="text" class="form-control" maxlength="255" 
                                 placeholder="" id="txtCustomer_po_num" name="txtCustomer_po_num" 
                                 value="<?php echo isset($arrResult['customer_po_num']) ? $arrResult['customer_po_num'] : ''; ?>">
                        </div>
                        <div class="col-lg-6">
                            <label for="txtSales_rep"><?php echo lang('field_sales_rep'); ?> <span class="asterisco_rojo">*</span></label>
                            <input type="text" class="form-control" maxlength="255" 
                                 placeholder="" id="txtSales_rep" name="txtSales_rep" 
                                 value="<?php echo isset($arrResult['sales_rep']) ? $arrResult['sales_rep'] : get_user_full_name(); ?>">
                        </div>
                 </div>
                 <hr>
                 <?php } ?>
                 <div class="row" style="margin-top:10px;">
                    <div class="col-lg-12">
                        <h4><?php echo strtoupper(lang('label_product_data')); ?></h4>
                    </div>
                </div>
                <div class="row" style="margin-top:10px;">
                    <div class="col-lg-2">
                        <label for="txtCantidad"><?php echo lang('field_quantity'); ?><span class="asterisco_rojo">*</span></label>
                        <input type="text" class="form-control" maxlength="254" id="txtCantidad" name="txtCantidad" 
                               value="<?php echo isset($txtCantidad) ? $txtCantidad : ''; ?>">
                        <span id="error_cantidad" class="error"></span>
                    </div>
                    <div class="col-lg-2">
                        <label for="txtSKU"><?php echo lang('field_emg'); ?></label>
                        <input type="text" class="form-control" maxlength="254"  id="txtSKU" name="txtSKU" 
                               value="<?php echo isset($txtSKU) ? $txtSKU : ''; ?>">
                        <span id="error_sku" class="error"></span>
                    </div>
                    <div class="col-lg-2">
                        <label for="txtCustomizer"><?php echo lang('field_customer'); ?> <span class="asterisco_rojo">*</span></label>
                        <input type="text" class="form-control" maxlength="254" id="txtCustomizer" name="txtCustomizer" 
                               value="<?php echo isset($txtCustomizer) ? $txtCustomizer : ''; ?>">
                        <span id="error_customizer" class="error"></span>
                    </div>
                    <?php if(user_is_representante()){?>
                        <div class="col-lg-2">
                            <label for="txtUnitPrice"><?php echo lang('field_unit_price'); ?> <span class="asterisco_rojo">*</span></label>
                            <input type="text" class="form-control" maxlength="254" id="txtUnitPrice" name="txtUnitPrice" 
                                   value="<?php echo isset($txtUnitPrice) ? $txtUnitPrice : ''; ?>">
                            <span id="error_unit_price" class="error"></span>
                        </div>
                    <?php } ?>
                    <div class="col-lg-4">
                        <label for="txtDescripcion"><?php echo lang('field_description'); ?></label>
                        <input type="text" class="form-control" maxlength="254"  
                             placeholder="" id="txtDescripcion" name="txtDescripcion" 
                             value="<?php echo isset($txtDescripcion) ? $txtDescripcion : ''; ?>">
                        <span id="error_descripcion" class="error"></span>
                    </div>
               </div>
               <div class="row" style="margin-top:10px;">
                    <div class="col-lg-12" style="text-align: right;">
                        <button type="button" name="btAdd" id="btAdd" class="btn btn-ppal" value="1"><i class="glyphicon glyphicon-plus"></i> <?php echo lang('add'); ?></button>
                    </div>
                 </div>
                 <hr>
                 <div class="row">
                    <div class="panel panel-default">
                        <div class="table-responsive">
                            <table id="table-orders" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('label_item'); ?></th>
                                        <th><?php echo lang('field_quantity'); ?></th>
                                        <th><?php echo lang('field_emg'); ?></th>
                                        <th><?php echo lang('field_customer'); ?></th>
                                        <th><?php echo lang('field_description'); ?></th>
                                        
                                        <?php if(user_is_representante()){?>
                                            <th><?php echo lang('field_unit_price'); ?></th>
                                            <th><?php echo lang('field_total_price'); ?></th>
                                        <?php } ?>
                                        
                                        <th><?php echo lang('field_options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    //echo "<pre>",print_r($arrResult['arrProductos']),"</pre>";
                                    if(isset($arrResult['arrProductos']) and is_array($arrResult['arrProductos'])){
                                        $contador           = 0;
                                        $suma_total_order   = 0;
                                        foreach($arrResult['arrProductos'] as $i => $item){
                                            $tr_id = rnd_string(10);
                                            echo '<tr class="'.$i.'" id="'.$tr_id.'">';
                                            
                                            $contador++;
                                            $hddItemVal =  $contador."|".$item['cantidad']
                                                            ."|".$item['sku']
                                                            ."|".$item['customer']
                                                            ."|".$item['descripcion'];

                                            echo  '<td>'.$contador.'</td>';
                                            echo  '<td>'.$item['cantidad'].'</td>';
                                            echo  '<td>'.$item['sku'].'</td>';
                                            echo  '<td>'.$item['customer'].'</td>';
                                            echo  '<td>'.$item['descripcion'].'</td>';
                                            
                                            $ext_price = 0;
                                            if(user_is_representante()){
                                                if(!empty($item['cantidad']) and !empty($item['unit_price'])){
                                                    $unit_price         = $item['unit_price'];
                                                    $unit_price         = number_format($item['unit_price'],2,'.',',');
                                                    
                                                    $ext_price          = ($item['cantidad'] * $unit_price);
                                                    $suma_total_order   = ($suma_total_order + $ext_price);

                                                    echo '<td>'.$unit_price.'</td>';
                                                    echo '<td>'.$ext_price.'</td>';
                                                    $hddItemVal .= "|".$item['unit_price'];
                                                    $hddItemVal .= "|".$ext_price;
                                                }
                                            }
                                            
                                            echo  '<td>';
                                                echo '<a class="edit" data-toggle="modal" data-target="#ModalEditOrder"><i class="glyphicon glyphicon-pencil"></i></a>';
                                                echo '<a class="elimina"><i class="glyphicon glyphicon-remove"></i></a>';
                                                echo '<input type="hidden" id="hddItem_'.$tr_id.'" name="hddItem[]" value="'.$hddItemVal.'">
                                                      <input type="hidden" id="hddTotalPrice_'.$tr_id.'" name="hddTotalPrice[]" value="'.$ext_price.'">';
                                            echo  '</td>';
                                            echo  '</tr>';
                                        }
                                        $suma_total_order = number_format($suma_total_order, 2, '.',',');
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <div>
                            </div>
                        </div>
                    </div>
                    <!-- /table with footer -->
                   <div class="pagination"><?php //echo $pagination; ?></div>
                 </div>
                 <?php if(user_is_representante()){?>
                    <div id="total-order" class="row">
                       <div class="col-lg-9"></div>
                       <div class="col-lg-3">
                           <div id="bloque" class="col-lg-2">
                               <span class="titulo"><?php echo lang('field_total_order'); ?></span> 
                               <?php  //$arrResult['total_pedido'] ?>
                               <span id="precio_total_pedido" class="precio">$<?php echo isset($suma_total_order) ? $suma_total_order : '0'; ?></span>
                               <input type="hidden" id="hddPrecio_total_pedido" name="hddPrecio_total_pedido" value="<?php echo isset($suma_total_order) ? $suma_total_order : '0'; ?>">
                           </div>
                       </div>
                    </div>
                 <?php } ?>
                 <!-- div class="row">
                     <p><?php echo lang('orders_disclaimer_1'); ?></p>
                     <p><?php echo lang('orders_disclaimer_2'); ?></p>
                 </div -->
                 <hr>
                 <div class="row">
                     <button type="submit" name="btSaveOrder" id="btSaveOrder" class="btn btn-ppal" value="btSaveOrder"><i class="glyphicon glyphicon-floppy-disk"></i> <?php echo lang('save_orders'); ?></button>
                     <button type="submit" name="btProcess" id="btProcess" class="btn btn-ppal" value="btProcess"><i class="glyphicon glyphicon-ok-circle"></i> <?php echo lang('process_orders'); ?></button>
                     <?php if(user_is_representante()){ ?>
                        <a class="btn btn-ppal" href="<?php echo site_url('orders'); ?>/orders_upload"><i class="glyphicon glyphicon-import"></i> <?php echo lang('orders_submit_upload'); ?></a>
                     <?php } ?>
                 </div>
            </form>
            
            <div class="modal fade" id="ModalEditOrder" tabindex="-1" role="dialog" aria-labelledby="ModalEditOrder">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="ModalEditOrder"><?php echo lang('edit_orders'); ?></h4>
                        </div>
                        <div class="modal-body">
                            <form name="FormEditOrder">
                                <span id="error_edit" class="error row"></span>
                                <input type="hidden" id="hddEditItem" name="hddEditItem" value="">
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label class="control-label"><?php echo lang('field_quantity'); ?>:<span class="asterisco_rojo">*</span></label>
                                        <input type="text" class="form-control" id="txtEditCantidad" name="txtEditCantidad" maxlength="254">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label class="control-label"><?php echo lang('field_emg'); ?>:</label>
                                        <input type="text" class="form-control" id="txtEditSKU" name="txtEditSKU" maxlength="254">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label class="control-label"><?php echo lang('field_customer'); ?>:<span class="asterisco_rojo">*</span></label>
                                        <input type="text" class="form-control" id="txtEditCustomizer" name="txtEditCustomizer" maxlength="254">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label class="control-label"><?php echo lang('field_description'); ?>:</label>
                                        <textarea class="form-control" id="txtEditDescripcion" name="txtEditDescripcion" row="4"></textarea>
                                    </div>
                                </div>
                                <?php if(user_is_representante()){?>
                                    <div class="row">
                                        <div class="form-group col-lg-6">
                                            <label class="control-label"><?php echo lang('field_unit_price'); ?> <span class="asterisco_rojo">*</span></label>
                                            <input type="text" class="form-control" id="txtEditUnitPrice" name="txtEditUnitPrice"  maxlength="254">
                                        </div>
                                        <!-- div class="form-group col-lg-6">
                                            <label class="control-label"><?php echo lang('field_total_price'); ?>:</label>
                                            <input type="text" class="form-control" id="txtEditExitPrice" name="txtEditExitPrice" maxlength="254" readonly>
                                        </div -->
                                    </div>
                                <?php } ?>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btCancelOrder" name="btCancelOrder" class="btn btn-default" data-dismiss="modal"><?php echo lang('cancel_orders'); ?></button>
                            <button type="button" id="btEditOrder" name="btEditOrder" class="btn btn-ppal" value=""><i class="glyphicon glyphicon-floppy-disk"></i> <?php echo lang('edit'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
