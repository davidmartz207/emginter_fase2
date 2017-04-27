<div id="upoload-orders" class="row">
    <div class="container">
        <div class="col-lg-12">
            <?php 
            if(user_is_representante()){
                $lang = get_lang(); ?>
                <a name="upload"></a>
                <?php echo form_open_multipart('orders/import#upload','role="form" class="form-inline"'); ?>
                    <h3><?php echo lang('orders_text_upload');?></h3>
                    <?php
                    if(isset($sMsjError_upload) and $sMsjError_upload){
                        show_msj_error($sMsjError_upload,false);
                        echo '<br>';
                    }elseif(isset($sMsjConf_upload) and $sMsjConf_upload){
                        show_msj_confirmacion($sMsjConf_upload,false);
                        echo '<br>';
                    }elseif(isset($sMsjInfo_upload) and $sMsjInfo_upload){
                        show_msj_info($sMsjInfo_upload,false);
                        echo '<br>';
                    }elseif(isset($sMsjWarning_upload) and $sMsjWarning_upload){
                        show_msj_warning($sMsjWarning_upload,false);
                        echo '<br>';
                    }
                    $link_formato_1 = '';
                    $link_formato_2 = '';
                    if($lang=='en'){
                        $link_formato_1 = ' <a href="'.base_url().'/downloads/formats/format_po_web.zip" target="_blank"><i class="fa fa-download"></i> download the file</a>';
                        $link_formato_2 = ' <a href="'.base_url().'/downloads/formats/format_po_web.zip" target="_blank"><i class="fa fa-download"></i> download the necessary file</a>';
                    }elseif($lang=='es'){
                        $link_formato_1 = ' <a href="'.base_url().'/downloads/formats/format_po_web.zip" target="_blank"><i class="fa fa-download"></i> descargue el  archivo</a>';
                        $link_formato_2 = ' <a href="'.base_url().'/downloads/formats/format_po_web.zip" target="_blank"><i class="fa fa-download"></i> descargar el archivo necesario</a>';
                    }
                    ?>    
                    <!-- div class="alert alert-info alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php printf(lang('orders_msj_format_upload'),$link_formato_1);?>
                    </div -->

                    <div class="row" style="margin: 20px;">
                        <div class="col-lg-12">
                            <!-- button type="button" id="btFile" name="btFile" value="btUpload" class="btn btn-ppal"><i class="fa fa-file"></i> <?php echo lang('orders_label_file_upload');?></button -->
                            <input type="file" id="filFILE" name="filFILE" style="margin:0 auto;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <a class="btn btn-default" href="<?php echo site_url('orders'); ?>"><i class="fa fa-chevron-left"></i> <?php echo lang('orders_link_return'); ?></a>
                            <button type="submit" id="btUpload" name="btUpload" value="btUpload" class="btn btn-ppal"><i class="fa fa-cloud-upload"></i> <?php echo lang('orders_submit_upload');?></button>
                            <br><br>
                            <!-- label><?php echo lang('orders_label_file_upload');?></label -->
                            <ul>
                                <li class="help-block"><?php printf(lang('orders_desc_file_upload'),$link_formato_2);?></li>
                                <li class="help-block"><?php printf(lang('msj_file_fields_requerid_1'),$link_formato_2);?></li>
                                <li class="help-block"><?php printf(lang('msj_file_fields_requerid_2'),$link_formato_2);?></li>
                            </ul>
                        </div>
                    </div>
                </form>
            <?php }?>
        </div>
    </div>
</div>
