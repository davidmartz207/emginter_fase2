<!-- load footer -->
<div class="row">
<footer>
<div class="backgfooter">
     <div class="container" id="footer">
          <div class="row">
               <div class="col-lg-9">
                    <h5>
                        <a class="reset_result" href="<?php echo site_url('home'); ?>"><?php echo lang('home'); ?></a> |
                        <a class="reset_result" href="<?php echo site_url('about_us'); ?>"><?php echo lang('about_us'); ?></a> |
                        <a class="reset_result" href="<?php echo site_url('products'); ?>"><?php echo lang('products'); ?></a> |
                        <a class="reset_result" href="<?php echo site_url('orders'); ?>"><?php echo lang('orders'); ?></a> |
                        <a class="reset_result" href="<?php echo site_url('contact'); ?>"><?php echo lang('contact'); ?></a>
                    </h5>
                    <p>
                        <span class="icono-casa"><i class="fa fa-home fa-fw"></i>&nbsp;<?php echo strip_tags(get_config_db('direccion')); ?></span> | <span class="icono-tlf"><i class="fa fa-phone"></i>&nbsp;<?php echo get_config_db('telefono'); ?></span> | <span class="icono-email"><i class="fa fa-envelope"></i>&nbsp;<a href="mailto:<?php echo get_config_db('email_public'); ?>"><?php echo get_config_db('email_public'); ?></a></span>
                    </p>
               </div>
               <div class="col-lg-3">
               </div>
               <div class="col-lg-3">
               </div>
               <div class="col-lg-3">
                    <h5><?php echo lang('label_connect_footer'); ?></h5>
                    <p class="stay_connected">
                        <a href="http://twitter.com/EMGIntern" target="_blank" title="Twitter"><i class="fa fa-twitter"></i></a>
                        &nbsp;&nbsp;&nbsp;
                        <a href="http://www.facebook.com/emginter/" target="_blank" title="Facebook"><i class="fa fa-facebook"></i></a>
                    </p>
               </div>
          </div>
         <div class="row">
             <div class="col-lg-6">
                 <div class="fb-page" data-href="https://www.facebook.com/emginter" data-tabs="biograf&#xed;a" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/emginter" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/emginter">EMG International</a></blockquote></div>
             </div>
             <div class="col-lg-6">

                 <a class="twitter-timeline" data-width="340" data-height="200" href="https://twitter.com/EMGIntern">Tweets by EMGIntern</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
             </div>

         </div>
     </div>
</div>
<!-- end footer -->

<!-- load footerbar -->
<div class="footerbar">
     <div class="container">
          <div class="row">
               <div class="col-lg-12">
                    <?php $anho = date('Y'); ?>
                    <p>©Copyright <?php echo ($anho=='2014' ? $anho : '2014-'.$anho); ?>, EMG International. All rights reserved.</p>
               </div>
          </div>
     </div>
</div></div>
<!-- end footerbar -->
</footer>
<script src="<?php echo base_url(); ?>includes/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>includes/js/bootstrap.min.js"></script>
<script>
   var busquedaon = '<?php if(isset($arrProducts) and is_array($arrProducts) and count($arrProducts)>0){echo true; }else { echo false; }?>'
</script>


<script src="<?php echo base_url(); ?>includes/js/custom.js"></script>



<script>



    $(document).ready(function(){


        <?php
        if (is_array($this->session->userdata("ses_usuario"))){
        ?>
            //funcionalidad para manejar el tiempo de sesion
            ini();
            var tiempo;

            function ini() {
                tiempo = setTimeout(
                    function () {
                        window.location.href = "<?php echo site_url('logout'); ?>";
                    }
                    ,
                    900000
                );
            }
            //cuando se mueve el raton
            $('html body').on('mousemove', function () {
                parar();
            });
            //cuando se presiona cualquier tecla
            $('html body').on('keypress', function () {
                parar();
            });
            //se reinicia el timer
            function parar() {
                clearTimeout(tiempo);
                ini();
            }


        <?php
        }
        ?>
        //--------------------------------------------------------------------------

        $('html body').css({"height":"100%"});
        if($("body").height() < $(window).height()){
            $("footer").css({
                "position":"fixed",
                "left":"0px",
                "bottom":"0px",
                "width":"100%"
            });
        }



        $(".reset_result").click(function() {

            var url = "<?php echo site_url('products/clear_result'); ?>"; // El script a dónde se realizará la petición.

            var f = $(this);
            var formData = 1;
            //formData.append("dato", "valor");

            $.ajax({

                type: "POST",

                url: url,
                cache: false,
                contentType: false,
                processData: false,
                data: formData, // Adjuntar los campos del formulario enviado.
                success: function(data)
                {

                   // $("#file_img").hide();
                }

            });
        });


    });
</script>


<?php
if(in_array(get_controller(),array('orders','products'))){
    echo '<script src="'.base_url().'includes/js/jquery-ui/jquery-ui.min.js"></script>'; 
}
if(in_array(get_controller(),array('products','downloads'))){
    echo '<script type="text/javascript" src="'.base_url().'includes/js/jcombo/jquery.jCombo.min.js"></script>';
}
if(get_controller() == 'home'){
    echo '<script src="'.base_url().'includes/js/owl-carousel/owl.carousel.min.js"></script>';
}
if(get_controller() == 'orders'){
    echo '<script src="'.base_url().'includes/js/jquery.validate/jquery.validate.min.js"></script>';
    echo '<script src="'.base_url().'includes/js/mod_ppal.js"></script>';
}
?>
<script>

    $(document).ready(function(){



        <?php
        if(get_controller() == 'home'){ ?>
           $("#owl-slider-top").owlCarousel({
                singleItem: true,
                
                //Basic Speeds
                slideSpeed : 300,
                paginationSpeed : 800,
                rewindSpeed : 1000,

                //Autoplay
                autoPlay : true,
                stopOnHover : false,

                // Navigation
                navigation : false,
                navigationText : ["prev","next"],
                rewindNav : true,
                scrollPerPage : false,

                //Pagination
                pagination : true,
                paginationSpeed : 400,
                paginationNumbers: false,

                // Responsive
                responsive: true,
                responsiveRefreshRate : 200,
                responsiveBaseWidth: window
            });
            $("#owl-new-release").owlCarousel({
                autoPlay: 3000,
                items : 4,
                itemsDesktop : [1199,3],
                itemsDesktopSmall : [979,3]
            });
        <?php }#end panel_products
        
        if(get_controller() == 'orders'){?>
            //fn_cantidad();
            fn_dar_eliminar();
            fn_dar_editar()
            //fn_calcular_total();
            fn_clear_form();

            $("#grilla").validate({
                rules: { 
                   txtPO_CO: { 
                      required: true,
                      minlength: 1,
                      maxlength: 50
                   },
                   txtCustomer_name: { 
                      required: true,
                      minlength: 1, 
                      maxlength: 255
                   },
                   txtaShip_to: { 
                      required: true,
                      maxlength: 255,
                      ValidaAphanumeric: true
                   },
                   txtaCustomer_address: { 
                      required: true,
                      maxlength: 255
                   },
                   txtaAddress: { 
                      required: true,
                      maxlength: 255
                   },
                   txtCustomer_po_num: { 
                      required: true,
                      maxlength: 255
                   },
                   txtSales_rep: { 
                      required: true,
                      maxlength: 255
                   }

                },
                messages: {
                    txtPO_CO: {
                        required: "<?php echo lang('requerid'); ?>",
                        maxlength: "<?php echo lang('lenght_po_co'); ?>"
                    },
                    txtCustomer_name: {
                        required: "<?php echo lang('requerid'); ?>",
                        maxlength: "<?php echo lang('lenght_po_co'); ?>"
                    },
                    txtaShip_to: {
                        required: "<?php echo lang('requerid'); ?>",
                        maxlength: "<?php echo lang('lenght_po_co'); ?>"
                    },
                    txtaCustomer_address: {
                        required: "<?php echo lang('requerid'); ?>",
                        maxlength: "<?php echo lang('lenght_po_co'); ?>"
                    },
                    txtaAddress: {
                        required: "<?php echo lang('requerid'); ?>",
                        maxlength: "<?php echo lang('lenght_po_co'); ?>"
                    },
                    txtCustomer_po_num: {
                        required: "<?php echo lang('requerid'); ?>",
                        maxlength: "<?php echo lang('lenght_po_co'); ?>"
                    },
                    txtSales_rep: {
                        required: "<?php echo lang('requerid'); ?>",
                        maxlength: "<?php echo lang('lenght_po_co'); ?>"
                    }
                },
                submitHandler: function(form) {
                    var table_num_rows   = $("#table-orders tbody").find("tr").length;
                    if(table_num_rows < 1){
                        alert("<?php echo lang('error_table_num_rows'); ?>");
                        return false;
                    }else{
                        form.submit();
                    } 
                }
            });
            $.validator.addMethod("ValidaAphanumeric", function(value, element){
                return (value.match(/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s.,!"']+$/)) ? true : false;
            },"<?php echo lang('alphanumeric'); ?>");

            $.validator.addMethod("ValidZero", function(value, element) {
                return (value==0 ? false : true);
            },"<?php echo lang('error_zero'); ?>");
            
            $("#btAdd").click(function(){
                var limit_table = "<?php echo isset($limit_table_items) ? $limit_table_items : 100; ?>";
                var cantidad    = $("#table-orders tbody").find("tr").length;
                if(cantidad >= limit_table ){
                    alert("<?php echo lang('limit_table_items'); ?> " + limit_table + " items.");
                    return;
                }else{
                    
                    var user_repre      = "<?php echo (user_is_representante() ? '1' : '0'); ?>";
                    var cantidad        = $("#txtCantidad").val();
                    var sku             = $("#txtSKU").val();
                    var customizer      = $("#txtCustomizer").val();
                    var descripcion     = $("#txtDescripcion").val();
                    var unit_price      = (user_repre==1 ? $("#txtUnitPrice").val() : "");
                    $(".error").html("");

                    if(cantidad == ""){ $("#error_cantidad").html("<?php echo lang('requerid'); ?>");
                    }else if(isNaN(cantidad)){ $("#error_cantidad").html("<?php echo lang('numeric'); ?>");
                    }else if(cantidad < 1){$("#error_cantidad").html("<?php echo lang('error_zero'); ?>");
                    }else if(customizer == ""){$("#error_customizer").html("<?php echo lang('requerid'); ?>"); 
                    }else if(descripcion == "" && descripcion.length > 240){ $("#error_descripcion").html("<?php echo lang('lenght_descripcion'); ?>");
                    <?php // solo se validara si es usuario representante ?>
                    }else if(user_repre==1 && unit_price == ""){ $("#error_unit_price").html("<?php echo lang('requerid'); ?>");
                    }else if(user_repre==1 && unit_price=="0"){ $("#error_unit_price").html("<?php echo lang('error_zero'); ?>");
                    }else if(user_repre==1 && !valid_price(unit_price)){ $("#error_unit_price").html("<?php echo lang('valid_unit_price'); ?>");
                    }else if(user_repre==1 && unit_price < 1){ $("#error_unit_price").html("<?php echo lang('error_unit_price'); ?>");
                    }else{ fn_agregar();$(".error").html("");}
                }
            });  
  
            function fn_agregar(){
                var user_repre  = "<?php echo (user_is_representante() ? '1' : '0'); ?>";
                var tr_id       = rnd_string(10);
                var table_num_rows   = $("#table-orders tbody").find("tr").length;
                if(table_num_rows >= 1){
                    var par         = $('#table-orders tbody tr:last');
                    var numItem     = par.children("td:nth-child(1)");
                    numItem         = parseInt(numItem.html()) + 1;
                }else{
                    var numItem     = 1;
                } 
                

                var cadena      = "<tr id='" + tr_id  + "'>";
                cadena          = cadena + "<td>" + numItem + "</td>";
                cadena          = cadena + "<td>" + $("#txtCantidad").val() + "</td>";
                cadena          = cadena + "<td>" + $("#txtSKU").val() + "</td>";
                cadena          = cadena + "<td>" + $("#txtCustomizer").val() + "</td>";
                cadena          = cadena + "<td>" + $("#txtDescripcion").val() + "</td>";

                var hddItemVal  = $("#txtCantidad").val() + "|" + $("#txtSKU").val() 
                                  + "|" + $("#txtCustomizer").val() + "|" + $("#txtDescripcion").val();

                if(user_repre==1){
                    var reset_unit_price = reset_format_price($("#txtUnitPrice").val());
                    var format_unit_price = currency(reset_unit_price, 2,[',', "'", '.']);
                    cadena = cadena + "<td>" + format_unit_price + "</td>";
                    hddItemVal =  hddItemVal + "|" + format_unit_price;

                    /* caculo total cantidad x unit price */
                    var cantidad           = $("#txtCantidad").val();
                    var reset_total_price  = (cantidad * reset_unit_price);
                    var format_total_price = currency(reset_total_price, 2,[',', "'", '.']);
                    cadena = cadena + "<td>" + format_total_price + "</td>";
                    hddItemVal =  hddItemVal + "|" + format_total_price;
                }

                cadena = cadena + '<td>';
                cadena = cadena + '<a class="edit" data-toggle="modal" data-target="#ModalEditOrder"><i class="glyphicon glyphicon-pencil"></i></a>';
                cadena = cadena + '<a class="elimina"><i class="glyphicon glyphicon-remove"></i></a>';
                cadena = cadena + "<input type='hidden' id='hddItem_"+tr_id+"' name='hddItem[]' value='" + hddItemVal + "'>";
                cadena = cadena + "<input type='hidden' id='hddTotalPrice_"+tr_id+"' name='hddTotalPrice[]' value='" + format_total_price + "'>";
                    
                cadena = cadena + '</td>';

                $("#table-orders tbody").append(cadena);
                fn_dar_eliminar();
                fn_dar_editar()
                fn_calcular_total();
                fn_clear_form();
            }

            function fn_dar_editar(){
                $("a.edit").click(function(){
                    var par         = $(this).parent().parent();
                    var id_tr       = par.attr("id");
                    var Item        = par.children("td:nth-child(1)");
                    var Qty         = par.children("td:nth-child(2)");
                    var SKU         = par.children("td:nth-child(3)");
                    var Customizer  = par.children("td:nth-child(4)");
                    var Descripcion = par.children("td:nth-child(5)");
                    var UnitPrice   = par.children("td:nth-child(6)");
                    var ExtPrice    = par.children("td:nth-child(7)");
                    $('#hddEditItem').val(Item.html());
                    $('#txtEditCantidad').val(Qty.html());
                    $('#txtEditSKU').val(SKU.html());
                    $('#txtEditCustomizer').val(Customizer.html());
                    $('#txtEditDescripcion').val(Descripcion.html());
                    $('#txtEditUnitPrice').val(UnitPrice.html());
                    $('#txtEditExitPrice').val(ExtPrice.html());
                    $('#btEditOrder').val(id_tr);
                });
            }
            
            $("#btEditOrder").click(function(){
                var tr_id           = $(this).val();
                var user_repre      = "<?php echo (user_is_representante() ? '1' : '0'); ?>";
                var item            = $("#hddEditItem").val();
                var cantidad        = $("#txtEditCantidad").val();
                var sku             = $("#txtEditSKU").val();
                var customizer      = $("#txtEditCustomizer").val();
                var descripcion     = $("#txtEditDescripcion").val();
                var unit_price      = (user_repre==1 ? $("#txtEditUnitPrice").val() : "");
                $(".error").html("");

                if(cantidad == ""){ $("#error_edit").html("<?php echo lang('requerid_quantity'); ?>");
                }else if(isNaN(cantidad)){ $("#error_edit").html("<?php echo lang('numeric_quantity'); ?>");
                }else if(cantidad < 1){$("#error_edit").html("<?php echo lang('error_zero_quantity'); ?>");
                }else if(customizer == ""){$("#error_edit").html("<?php echo lang('requerid_customer'); ?>"); 
                }else if(descripcion == "" && descripcion.length > 240){ $("#error_edit").html("<?php echo lang('lenght_descripcion'); ?>");
                <?php // solo se validara si es usuario representante ?>
                }else if(user_repre==1 && unit_price == ""){ $("#error_edit").html("<?php echo lang('requerid_unit_price'); ?>");
                }else if(user_repre==1 && unit_price=="0"){ $("#error_edit").html("<?php echo lang('error_zero_unit_price'); ?>");
                }else if(user_repre==1 && !valid_price(unit_price)){ $("#error_edit").html("<?php echo lang('valid_unit_price'); ?>");
                }else if(user_repre==1 && unit_price < 1){ $("#error_edit").html("<?php echo lang('error_unit_price'); ?>");
                }else{ 
                    $(".error").html("");
                    
                    var hddItemVal  = $("#hddEditItem").val() 
                                      + "|" + $("#txtEditCantidad").val() 
                                      + "|" + $("#txtEditSKU").val() 
                                      + "|" + $("#txtEditCustomizer").val() 
                                      + "|" + $("#txtEditDescripcion").val();

                    var cadena      = "<td>" + $("#hddEditItem").val() + "</td>";
                    cadena          = cadena + "<td>" + $("#txtEditCantidad").val() + "</td>";
                    cadena          = cadena + "<td>" + $("#txtEditSKU").val() + "</td>";
                    cadena          = cadena + "<td>" + $("#txtEditCustomizer").val() + "</td>";
                    cadena          = cadena + "<td>" + $("#txtEditDescripcion").val() + "</td>";

                    if(user_repre==1){
                        var reset_unit_price = reset_format_price($("#txtEditUnitPrice").val());
                        var format_unit_price = currency(reset_unit_price, 2,[',', "'", '.']);
                        cadena = cadena + "<td>" + format_unit_price + "</td>";
                        hddItemVal =  hddItemVal + "|" + format_unit_price;

                        /* caculo total cantidad x unit price */
                        var cantidad           = $("#txtEditCantidad").val();
                        var reset_total_price  = (cantidad * reset_unit_price);
                        var format_total_price = currency(reset_total_price, 2,[',', "'", '.']);
                        cadena = cadena + "<td>" + format_total_price + "</td>";
                        hddItemVal =  hddItemVal + "|" + format_total_price;
                    }                

                    cadena = cadena + '<td>';
                    cadena = cadena + '<a class="edit" data-toggle="modal" data-target="#ModalEditOrder"><i class="glyphicon glyphicon-pencil"></i></a>';
                    cadena = cadena + '<a class="elimina"><i class="glyphicon glyphicon-remove"></i></a>';
                    cadena = cadena + "<input type='hidden' id='hddItem_"+tr_id+"' name='hddItem[]' value='" + hddItemVal + "'>";
                    cadena = cadena + "<input type='hidden' id='hddTotalPrice_"+tr_id+"' name='hddTotalPrice[]' value='" + format_total_price + "'>";
                    cadena = cadena + '</td>';

                    $("#table-orders").find("#"+tr_id).html(cadena);
                    $('#ModalEditOrder').modal('hide');
                    fn_dar_eliminar();
                    fn_dar_editar()
                    fn_calcular_total();
                    fn_clear_form();
                }
            });
            
            function fn_dar_eliminar(){
                $("a.elimina").click(function(){
                    $(this).parents("tr").fadeOut("normal", function(){
                        $(this).remove();
                        fn_calcular_total();
                    });
                });
            }
            
            function fn_calcular_total(){
                var user_repre = "<?php echo (user_is_representante() ? '1' : '0'); ?>";
                if(user_repre==1){
                    var total = 0;
                    $("[name*='hddTotalPrice']").each(function( index ) {
                        var reset_total_price = reset_format_price($(this).val());
                        total = parseFloat(total) + parseFloat(reset_total_price);
                    });
                    total = currency(total, 2,[',', "'", '.']);
                    $("#precio_total_pedido").html("$" + total);
                    $("#hddPrecio_total_pedido").val(total);
                }
            };
            
            function fn_clear_form(){
                var user_repre = "<?php echo (user_is_representante() ? '1' : '0'); ?>";
            
                $("#txtCantidad").val("");
                $("#txtSKU").val("");
                $("#txtCustomizer").val("");
                $("#txtDescripcion").val("");
                
                if(user_repre==1){
                    $("#txtUnitPrice").val("");
                }
            }
            
            function get_desc_by_sku(elemento,sku){
                if(elemento!="" && sku!=""){
                    $.ajax({
                        data: { txtSKU : sku},
                        url:   '<?php echo site_url('products'); ?>/get_json_product_type_name_by_sku/',
                        type:  'POST',
                        dataType: 'json',
                        success:function (data){$(elemento).val(data);},
                        error: function(){alert('Server Error');}
                    });
                }
            }
        function get_desc_by_custo(elemento,customi){
            if(elemento!="" && customi!=""){
                $.ajax({
                    data: { custo : customi},
                    url:   '<?php echo site_url('products'); ?>/get_json_product_type_name_by_custo/',
                    type:  'POST',
                    dataType: 'json',
                    success:function (data){$(elemento).val(data);},
                    error: function(){alert('Server Error');}
                });
            }
        }
            function fn_cantidad(){
                var cantidad = $("#grilla tbody").find("tr").length;
                $("#span_cantidad").html(cantidad);
            };
            
            /* upload xls */
            $("#btFile").click(function () {$("#filFILE").trigger('click');});
            /**/
            $( "#txtSKU" ).autocomplete({
                source: "<?php echo site_url('products'); ?>/get_json_sku",
                minLength: 1,
                select: function( event, ui ){
                    $( "#txtSKU" ).val("");
                    get_desc_by_sku('#txtDescripcion',ui.item.value);
                }
            });
            $( "#txtCustomizer" ).autocomplete({
                source: "<?php echo site_url('products'); ?>/get_json_competitor",
                minLength: 1,
                select: function( event, ui ){
                    $("#hddLastField").val("btSearchCompetitor");
                    $("#selCompetitor" ).focus();
                    get_desc_by_custo('#txtDescripcion',ui.item.value);
                }
            });
            
            $( "#txtEditSKU" ).autocomplete({
                source: "<?php echo site_url('products'); ?>/get_json_sku",
                minLength: 1,
                select: function( event, ui ){
                    $( "#txtEditSKU" ).val();
                    get_desc_by_sku('#txtEditDescripcion',ui.item.value);
                }
            });
        <?php }#end panel_products
        #'orderds'
      // echo (isset($selTiposProductosDownloads) ? $selTiposProductosDownloads : 0); 
        if(in_array(get_controller(),array('products'))){ ?>


            // -----------------------------------------------------------------
            // search by vehicle
            // -----------------------------------------------------------------
            $("#selYears").change(function(){

                var var_year        = $("#selYears").val();
                $("#selModelos").find('option').remove();
                $("#selModelos").attr("disabled","disabled");
                $("#selModelos").removeClass("border-red");
                
                $("#selTiposMotores").find('option').remove();
                $("#selTiposMotores").attr("disabled","disabled");
                $("#selTiposMotores").removeClass("border-red");
                
                $("#selTiposProductos").find('option').remove();
                $("#selTiposProductos").attr("disabled","disabled");
                $("#selTiposProductos").removeClass("border-red");
                
                $("#img-processing-by-vehicle").removeClass("disabled");
                $.ajax({
                    data: { year : var_year },
                    url:   '<?php echo site_url('products'); ?>/get_json_marcas/',
                    type:  'POST',
                    dataType: 'json',
                    success:function (data){
                        $("#selMarcas").removeAttr("disabled");
                        $("#selMarcas").find('option').remove();
                        $(data).each(function(i, v){ // indice, valor
                            $("#selMarcas").append('<option value="' + v[0] + '">' + v[1] + '</option>');
                        });
                        $("#img-processing-by-vehicle").addClass("disabled");
                        $("#selYears").removeClass("border-red");
                        $("#selMarcas").addClass("border-red");
                    },
                    error: function(){
                        alert('Server Error');
                    }
                });
            });
            $("#selMarcas").change(function(){
                var var_year        = $("#selYears").val();
                var var_id_marca    = $("#selMarcas").val();
                $("#selTiposMotores").find('option').remove();
                $("#selTiposMotores").attr("disabled","disabled");
                $("#selTiposMotores").removeClass("border-red");
                
                $("#selTiposProductos").find('option').remove();
                $("#selTiposProductos").attr("disabled","disabled");
                $("#selTiposProductos").removeClass("border-red");
                
                $("#img-processing-by-vehicle").removeClass("disabled");
                $.ajax({
                    data: { year : var_year,id_marca : var_id_marca },
                    url:   '<?php echo site_url('products'); ?>/get_json_modelos/',
                    type:  'POST',
                    dataType: 'json',
                    success:function (data){
                        $("#selModelos").removeAttr("disabled");
                        $("#selModelos").find('option').remove();
                        $(data).each(function(i, v){ // indice, valor
                            $("#selModelos").append('<option value="' + v[0] + '">' + v[1] + '</option>');
                        });
                        $("#img-processing-by-vehicle").addClass("disabled");
                        $("#selMarcas").removeClass("border-red");
                        $("#selModelos").addClass("border-red");
                    },
                    error: function(){
                        alert('Server Error');
                    }
                });
            });
            
            $("#selModelos").change(function(){
                var var_year        = $("#selYears").val();
                var var_id_marca    = $("#selMarcas").val();
                var var_id_modelo   = $("#selModelos").val();
                
                $("#selTiposProductos").find('option').remove();
                $("#selTiposProductos").attr("disabled","disabled");
                $("#selTiposProductos").removeClass("border-red");
                
                $("#img-processing-by-vehicle").removeClass("disabled");
                $.ajax({
                    data: { year : var_year,id_marca : var_id_marca,id_modelo : var_id_modelo },
                    url:   '<?php echo site_url('products'); ?>/get_json_tipos_motores/',
                    type:  'POST',
                    dataType: 'json',
                    success:function (data){
                        $("#selTiposMotores").removeAttr("disabled");
                        $("#selTiposMotores").find('option').remove();
                        $(data).each(function(i, v){ // indice, valor
                            $("#selTiposMotores").append('<option value="' + v[0] + '">' + v[1] + '</option>');
                        });
                        $("#img-processing-by-vehicle").addClass("disabled");
                        $("#selModelos").removeClass("border-red");
                        $("#selTiposMotores").addClass("border-red");
                    },
                    error: function(){
                        alert('Server Error');
                    }
                });
            });
            
            $("#selTiposMotores").change(function(){
                var var_year          = $("#selYears").val();
                var var_id_marca      = $("#selMarcas").val();
                var var_id_modelo     = $("#selModelos").val();
                var var_id_tipo_motor = $("#selTiposMotores").val();
                
                $("#img-processing-by-vehicle").removeClass("disabled");
                $.ajax({
                    data: { year : var_year,id_marca : var_id_marca,id_modelo : var_id_modelo, id_tipo_motor : var_id_tipo_motor },
                    url:   '<?php echo site_url('products'); ?>/get_json_tipos_productos/',
                    type:  'POST',
                    dataType: 'json',
                    success:function (data){
                        $("#selTiposProductos").removeAttr("disabled");
                        $("#selTiposProductos").find('option').remove();
                        $(data).each(function(i, v){ // indice, valor
                            $("#selTiposProductos").append('<option value="' + v[0] + '">' + v[1] + '</option>');
                        });
                        $("#img-processing-by-vehicle").addClass("disabled");
                        $("#selTiposMotores").removeClass("border-red");
                        $("#selTiposProductos").addClass("border-red");
                    },
                    error: function(){
                        alert('Server Error');
                    }
                });
            });
            
            $("#selMarcas").change(function(){
                var year  = $("#selYears").val();
                var marca = $("#selMarcas").val();
                if((year!=null && year!=0) && (marca!=null && marca!=0)){
                    $("#btSearchPpal").removeAttr("disabled");
                }else{
                    $("#btSearchPpal").attr("disabled","disabled");
                    $("#selMarcas").find('option').remove();
                    $("#selModelos").find('option').remove();
                    $("#selTiposMotores").find('option').remove();
                    $("#selTiposProductos").find('option').remove();
                }
            });

            // -----------------------------------------------------------------
            // search by product type
            // -----------------------------------------------------------------
            $( "#selTiposProductosDownloads" ).change(function(){
                var valor = $(this).val();
                if(valor !="0" && valor != ""){
                    $("#hddLastField").val("btSearchProductType");
                    $("#btSearchProductType").removeAttr("disabled");
                }
            });
            // -----------------------------------------------------------------
            // search by SKU
            // -----------------------------------------------------------------

            $( "#txtSKU" ).val("<?php echo (isset($txtSKU) ? $txtSKU : ''); ?>");
            // -----------------------------------------------------------------
            // search by competitor
            // -----------------------------------------------------------------

            $( "#txtCompetitor" ).val("<?php echo (isset($txtCompetitor) ? $txtCompetitor : ''); ?>");




        <?php }#end orders/products ?>



        //'find catalog'
        <?php 
        if(in_array(get_controller(),array('catalog_products'))){ ?>
            //$("#selTiposProductosDownloads").jCombo("<?php echo site_url('catalog_products'); ?>/get_json_all_products_type",{
            //  selected_value: "<?php //echo (isset($selTiposProductosDownloads) ? $selTiposProductosDownloads : 0); ?>",
            //  initial_text: "<?php echo lang('selected_product_type'); ?>"
            //});
            
            // -----------------------------------------------------------------
            // search by vehicle
            // -----------------------------------------------------------------
            $(document).ready(function(){-
                $('#selYears > option[value="<?php echo (isset($selYears) ? $selYears : ''); ?>"]').attr('selected', 'selected');
                <?php if(isset($selYears)):?>
                    selYears('load');
                <?php endif;?>
            });
            
            $("#selYears").change(function(){
                selYears('change');
            });
            function selYears(acccion){
                var var_year        = $("#selYears").val();
                $("#selModelos").find('option').remove();
                $("#selModelos").attr("disabled","disabled");
                $("#selModelos").removeClass("border-red");
                
                $("#selTiposMotores").find('option').remove();
                $("#selTiposMotores").attr("disabled","disabled");
                $("#selTiposMotores").removeClass("border-red");
                
                $("#selTiposProductos").find('option').remove();
                $("#selTiposProductos").attr("disabled","disabled");
                $("#selTiposProductos").removeClass("border-red");
                
                $("#img-processing-by-vehicle").removeClass("disabled");
                var linea = $("#linea").val();
                $.ajax({
                    data: { year : var_year , linea : linea },
                    url:   '<?php echo site_url('catalog_products'); ?>/get_json_marcas/',
                    type:  'POST',
                    dataType: 'json',
                    success:function (data){
                        $("#selMarcas").removeAttr("disabled");
                        $("#selMarcas").find('option').remove();
                        $(data).each(function(i, v){ // indice, valor
                            $("#selMarcas").append('<option value="' + v[0] + '">' + v[1] + '</option>');
                        });
                        $("#img-processing-by-vehicle").addClass("disabled");
                        $("#selYears").removeClass("border-red");
                        $("#selMarcas").addClass("border-red");
                        $('#selMarcas > option[value="<?php echo (isset($selMarcas) ? $selMarcas : ''); ?>"]').attr('selected', 'selected');
                        if(acccion=='load'){
                            <?php if(isset($selMarcas)):?>
                                selMarcas('load');
                            <?php endif;?>
                        }
                    },
                    error: function(){
                        alert('Server Error');
                    }
                });
            }
            $("#selMarcas").change(function(){
                selMarcas('change');
            });
            function selMarcas(acccion){
                var var_year        = $("#selYears").val();
                var var_id_marca    = $("#selMarcas").val();
                var linea = $("#linea").val();

                $("#selTiposMotores").find('option').remove();
                $("#selTiposMotores").attr("disabled","disabled");
                $("#selTiposMotores").removeClass("border-red");
                
                $("#selTiposProductos").find('option').remove();
                $("#selTiposProductos").attr("disabled","disabled");
                $("#selTiposProductos").removeClass("border-red");
                
                $("#img-processing-by-vehicle").removeClass("disabled");
                $.ajax({
                    data: { year : var_year,id_marca : var_id_marca , linea : linea},
                    url:   '<?php echo site_url('catalog_products'); ?>/get_json_modelos/',
                    type:  'POST',
                    dataType: 'json',
                    success:function (data){
                        $("#selModelos").removeAttr("disabled");
                        $("#selModelos").find('option').remove();
                        $(data).each(function(i, v){ // indice, valor
                            $("#selModelos").append('<option value="' + v[0] + '">' + v[1] + '</option>');
                        });
                        $("#img-processing-by-vehicle").addClass("disabled");
                        $("#selMarcas").removeClass("border-red");
                        $("#selModelos").addClass("border-red");
                        $('#selModelos > option[value="<?php echo (isset($selModelos) ? $selModelos : ''); ?>"]').attr('selected', 'selected');
                        
                        if(acccion=='load'){
                            <?php if(isset($selModelos)):?>
                                selModelos('load');
                            <?php endif;?>
                        }
                    },
                    error: function(){
                        alert('Server Error');
                    }
                });
            }
            
            $("#selModelos").change(function(){
                selModelos('change');
            });
            
            function selModelos(acccion){
                var var_year        = $("#selYears").val();
                var var_id_marca    = $("#selMarcas").val();
                var var_id_modelo   = $("#selModelos").val();
                var linea = $("#linea").val();

                $("#selTiposProductos").find('option').remove();
                $("#selTiposProductos").attr("disabled","disabled");
                $("#selTiposProductos").removeClass("border-red");
                
                $("#img-processing-by-vehicle").removeClass("disabled");
                $.ajax({
                    data: { year : var_year,id_marca : var_id_marca,id_modelo : var_id_modelo ,  linea : linea},
                    url:   '<?php echo site_url('catalog_products'); ?>/get_json_tipos_motores/',
                    type:  'POST',
                    dataType: 'json',
                    success:function (data){
                        $("#selTiposMotores").removeAttr("disabled");
                        $("#selTiposMotores").find('option').remove();
                        $(data).each(function(i, v){ // indice, valor
                            $("#selTiposMotores").append('<option value="' + v[0] + '">' + v[1] + '</option>');
                        });
                        $("#img-processing-by-vehicle").addClass("disabled");
                        $("#selModelos").removeClass("border-red");
                        $("#selTiposMotores").addClass("border-red");
                        $('#selTiposMotores > option[value="<?php echo (isset($selTiposMotores) ? $selTiposMotores : ''); ?>"]').attr('selected', 'selected');
                        if(acccion=='load'){
                            <?php if(isset($selTiposMotores)):?>
                                selTiposMotores('load');
                            <?php endif;?>
                        }
                    },
                    error: function(){
                        alert('Server Error');
                    }
                });
            }
            
            $("#selTiposMotores").change(function(){
                selTiposMotores('change');
            });
            function selTiposMotores(acccion){
                var var_year          = $("#selYears").val();
                var var_id_marca      = $("#selMarcas").val();
                var var_id_modelo     = $("#selModelos").val();
                var var_id_tipo_motor = $("#selTiposMotores").val();
                var linea = $("#linea").val();

                $("#img-processing-by-vehicle").removeClass("disabled");
                $.ajax({
                    data: { year : var_year,id_marca : var_id_marca,id_modelo : var_id_modelo, id_tipo_motor : var_id_tipo_motor,  linea : linea },
                    url:   '<?php echo site_url('catalog_products'); ?>/get_json_tipos_productos/',
                    type:  'POST',
                    dataType: 'json',
                    success:function (data){
                        $("#selTiposProductos").removeAttr("disabled");
                        $("#selTiposProductos").find('option').remove();
                        $(data).each(function(i, v){ // indice, valor
                            $("#selTiposProductos").append('<option value="' + v[0] + '">' + v[1] + '</option>');
                        });                       
                        $("#img-processing-by-vehicle").addClass("disabled");
                        $("#selTiposMotores").removeClass("border-red");
                        $("#selTiposProductos").addClass("border-red");
                        $('#selTiposProductos > option[value="<?php echo (isset($selTiposProductos) ? $selTiposProductos : ''); ?>"]').attr('selected', 'selected');
                        
                    },
                    error: function(){
                        alert('Server Error');
                    }
                });
            }
            
            $("#selMarcas").change(function(){
                var year  = $("#selYears").val();
                var marca = $("#selMarcas").val();
                if((year!=null && year!=0) && (marca!=null && marca!=0)){
                    $("#btSearchPpal").removeAttr("disabled");
                }else{
                    $("#btSearchPpal").attr("disabled","disabled");
                    $("#selMarcas").find('option').remove();
                    $("#selModelos").find('option').remove();
                    $("#selTiposMotores").find('option').remove();
                    $("#selTiposProductos").find('option').remove();
                }
            });
           
        <?php }#end find catalog ?>



    });
    <?php if(in_array(get_controller(),array('products'))){ ?>
    $(document).keypress(function(event){  
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            var hddLastField = $("#hddLastField").val();
            switch(hddLastField){
                case "btSearchProductType":{
                    $("#btSearchProductType").click();
                    break;
                }
                case "btSearchSKU":{
                    $("#btSearchSKU").click();
                    break;
                }
                case "btSearchCompetitor":{
                    $("#btSearchCompetitor").click();
                    break;
                }                
            }
            if(event.preventDefault){event.preventDefault();}else{return false;}
        }   
    });
    <?php }#end orders/products ?>
    </script>
</body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.9&appId=28948968859";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));




</script>
</html>


