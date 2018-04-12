<?php
$this->load->view('panel/includes/cabecera_html',$title);
?>
<div id="panel_modal_product_type_ins_v" title="Tipos de Productos" style="display:none">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel-heading">
                <h6 class="panel-title"><div id="msj"></div></h6>
            </div>
            <div class="panel-body" style="border: none;">
               <div class="form-group">
                    <label class="col-sm-5 control-label">Nombre (Ingles):<span class="asterisco_rojo">*</span></label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="txtNombre_en" name="txtNombre_en" 
                         placeholder="" value="<?php echo isset($txtNombre_en) ? $txtNombre_en : ''; ?>">
                    </div>
               </div>
               <br><br>
               <div class="form-group">
                    <label class="col-sm-5 control-label">Nombre (Español):<span class="asterisco_rojo">*</span></label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="txtNombre_es" name="txtNombre_es" 
                         placeholder="" value="<?php echo isset($txtNombre_es) ? $txtNombre_es : ''; ?>">
                    </div>
               </div>
            </div>
        </div>
    </div>
</div>