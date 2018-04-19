            <!-- Footer -->
            <div class="footer">
                <div class="alert alert-info fade in widget-inner"><button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="fa fa-info"></i><b>Para optimizar el proceso de importación de productos, por favor valide que el documento
                    Excel tenga las siguientes carasterísticas:</b>
                    <br><br>
                    <ul>
                        <li>Tamaño máximo del archivo: <?php echo ini_get('upload_max_filesize');?>.</li>
                        <li>Las columnas OEM, Tipo de Producto (Ingles y español), código SKU,
                            Marca, Modelo y Tipo de motor, son obligatorias.</li>
                        <li>Si el archivo es muy grande, subirlo por partes.</li>
                        <li>Deben mantenerse los nombres y orden de las columnas en el archivo, de la siguiente manera:
                        <br><br>
                        <?php
                        $arrFormatoCamposExcel = array(
                            'OEM',#0
                            'New Release?',#1
                            'Product Type English',#Product Type #2
                            'Product Type Spanish',#Product Type #3
                            'SMP/Anchor',#4
                            'Wells/Westar',#5
                            'SKU',#6
                            'Sell Price',#7
                            'Marca',#8
                            'Modelo',#9
                            'Motor',#10
                            'Año 1',#11
                            'Año 2',#12
                            'Año 3',#13
                            'Año 4',#14
                            'Año 5',#15
                            'Año 6',#16
                            'Año 7',#17
                            'Año 8',#18
                            'Año 9',#19
                            'Año 10',#20
                            'Año 11',#21
                            'Año 12',#22
                            'Año 13',#23
                            'Año 14',#24
                            'Año 15',#25
                            'Año 16',#26
                            'Año 17',#27
                            'Año 18',#28
                            'Año 19',#29
                            'Año 20',#30
                            'Año 21',#31
                            'Año 22',#32
                            'Año 23',#33
                            'Año 24',#34
                            'Año 25',#35
                            'Año 26',#36
                            'Año 27',#37
                            'Año 28',#38
                            'Año 29',#39
                            'Año 30',#40
                            'Año 31',#41
                            'Año 32',#42
                            'Año 33',#43
                            'Año 34',#44
                            'Año 35',#45
                            'Año 36',#46
                            'Año 37',#7
                            'Año 38',#48
                            'Año 39',#49
                            'Año 40',#50
                            'Linea',#51
                            'Flywhel',#52
                            'Cover',#53
                            'Disc Diameter - N. Disc Splines - Disc Hub Size',#54
                            'Notes 1',#57
                            'Notes 2',#58
                            'DAI'

                        );
                        echo ' '.implode(' | ',$arrFormatoCamposExcel);
                        ?>.
                        </li>
                    </ul>
                    
               </div>
            </div>
            <!-- /footer -->

        </div>
    <!-- /page content -->

    </div>
    <!-- page container -->
    
    <script src="<?php echo base_url(); ?>includes/js/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>includes/js/bootstrap.min.js"></script>
    <script>
        var strlinea, busquedaon = '<?php if(isset($arrProducts) and is_array($arrProducts) and count($arrProducts)>0){echo true; }else { echo false; }?>'
    </script>
    <script src="<?php echo base_url(); ?>includes/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>includes/js/jcombo/jquery.jCombo.min.js"></script>

<?php if(get_controller() == 'panel_products'){
        echo '<link rel="stylesheet" href="'.base_url().'includes/js/jquery-ui/jquery-ui.min.css">
            
              <style>
                .ui-datepicker-month,.ui-datepicker-year{color:#000;}
                .ui-dialog-titlebar{padding:4px;}
                .ui-dialog-buttonset{padding:4px;text-align:right;border: none;}
                .ui-dialog-titlebar-close{display:none;}
                .bootstrap-tagsinput{width: 100%;border-radius: 0px !important;}
                </style>';
        echo '<link rel="stylesheet" href="'.base_url().'includes/js/bootstrap-tagsinput/bootstrap-tagsinput.css">';
        echo '<script src="'.base_url().'includes/js/jquery-ui/jquery-ui.min.js"></script>';
        echo '<script src="'.base_url().'includes/js/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>';
        
        $this->load->view('panel/panel_modal_product_type_ins_v',$title);
    }
?>
<script>



$(document).ready(function(){

    //funcionalidad para manejar el tiempo de sesion
    ini();
    var tiempo;

    function ini() {
        tiempo = setTimeout(
            function () {
                window.location.href = "http://emginter.com/en/logout";
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

    $("#reset_search_form").on("click", function(e){
        e.preventDefault();

        $("#form_search_advanced").trigger("reset").find("input[type=text], textarea").attr('value', '');
        $("#form_search_advanced").trigger("reset").find("input[type=text], textarea").val('');
        $("#form_search_advanced").trigger("reset").find("select").val("0");
        $("#form_search_simple").trigger("reset").find("input[type=text], textarea").attr('value', '');
        $("#form_search_simple").trigger("reset").find("input[type=text], textarea").val('');
        $("#form_search_simple").trigger("reset").find("select").val("0");

    });


    <?php
    if(get_controller() == 'panel_products'){?>
        $( "#txtSKU" ).autocomplete({
            source: "<?php echo site_url('products'); ?>/get_json_sku",
            minLength: 1,
            select: function( event, ui ){}
        });
        $( "#txtOEM" ).autocomplete({
            source: "<?php echo site_url('products'); ?>/get_json_oem",
            minLength: 1,
            select: function( event, ui ){}
        });
        $( "#txtSMP" ).autocomplete({
            source: "<?php echo site_url('products'); ?>/get_json_smp",
            minLength: 1,
            select: function( event, ui ){}
        });
        $( "#txtWELLS" ).autocomplete({
            source: "<?php echo site_url('products'); ?>/get_json_wells",
            minLength: 1,
            select: function( event, ui ){}
        });
        
        $("#panel-busqueda-toogle").click(function(event){
            if(event.preventDefault){event.preventDefault();}else{return false;}
            $("#panel-busqueda-body").toggle("slow");
        });
        $("#btProcess").click(function(){
            $("#import_botones").css("display","none");
            $("#import_ajax").css("display","block");
        });
        function clear_msj(){$("#msj").html("");}
        $("#btTiposProductos").click(function(event){
            if(event.preventDefault){event.preventDefault();}else{return false;}
            $( "#panel_modal_product_type_ins_v" ).dialog({
                resizable: false,
                modal: true,
                width: 400,
                buttons: {
                    Agregar: function() {
                        $.ajax({
                            'url' : '<?php echo site_url('panel_product_type'); ?>/ins_ajax/',
                            'type' : 'POST',
                            'async':true,
                            'cache':false,
                            'data': "txtNombre_en="+$('#txtNombre_en').val()+"&txtNombre_es="+$('#txtNombre_es').val() + "&btAjax=1",
                            'success' : function(data){
                                var arrData = data.split("|");
                                if(arrData[0]=="error"){
                                    clear_msj();
                                    $("#msj").html(arrData[1]);
                                    $("#msj").css("color","red");
                                }else if(arrData[0]=="conf"){
                                    $("#msj").html(arrData[1]);
                                    $("#msj").css("color","green");
                                    $("#selTiposProductos").jCombo("<?php echo site_url('panel_products'); ?>/get_json_tipos_productos");
                                    $("#selTiposProductosN").jCombo("<?php echo site_url('panel_products'); ?>/get_json_tipos_productos");
                                    $("#panel_modal_product_type_ins_v").dialog("close");
                                }
                            }
                        });
                    },
                    Cancel: function() {
                        $( this ).dialog( "close" );
                    }
                }
           });
        });

        //Muestra y oculta campos en entrada de productos



    $("#fieldset_competition").hide();
    $("#fieldset_perfection").hide();

    $('#linea').on("change",function(){

            switch ($(this).val()){
                case ("perfection"): $("#fieldset_perfection").show();
                                     $("#fieldset_competition").hide();
                                     break;

                case ("emg"):        $("#fieldset_competition").show();
                                     $("#fieldset_perfection").hide();
                                     $("#fieldset_mrc").hide();
                                     $("#label1").text('SMP:');
                                     $("#label2").text('Wells:');
                                     break;

                case ("mrc"):        $("#fieldset_perfection").hide();
                                     $("#label1").text('Anchor:');
                                     $("#label2").text('Westar:');
                                     $("#fieldset_competition").show();
                                     $("#fieldset_mrc").show();


            }
        });
    //muestra los fieldset si estamos editando
	<?php if(isset($selLinea) || isset($linea) ){ ?>
            switch($("#linea").val()){
                case ("perfection"): $("#fieldset_perfection").show();
                                     $("#fieldset_competition").hide();
                                     break;

                case ("emg"):        $("#fieldset_competition").show();
                                     $("#fieldset_perfection").hide();
                                     $("#fieldset_mrc").hide();
                                     $("#label1").text('SMP:');
                                     $("#label2").text('Wells:');
                                     break;

                case ("mrc"):        $("#fieldset_perfection").hide();
                                     $("#label1").text('Anchor:');
                                     $("#label2").text('Westar:');
                                     $("#fieldset_competition").show();
                                     $("#fieldset_mrc").show();

            }
	<?php }?>
        /**/
        $.datepicker.regional['es'] = {
            yearRange: "-110:+0",
            closeText: 'Cerrar',
            prevText: '<Ant',
            nextText: 'Sig>',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['es']);
        $(".datepicker").datepicker({
                inline: true,
                changeMonth: true,
                changeYear: true
        });
        /**/
        $("#selTiposProductos").jCombo("<?php echo site_url('panel_products'); ?>/get_json_tipos_productos",{
           selected_value: "<?php echo empty($selTiposProductos) ? 0 : $selTiposProductos; ?>",
           initial_text: "-- seleccione --"
        });
        $("#selTiposProductosN").jCombo("<?php echo site_url('panel_products'); ?>/get_json_tipos_productos",{
           selected_value: "<?php echo empty($selTiposProductos) ? 0 : $selTiposProductos; ?>",
           initial_text: "-- seleccione --"
        });
    <?php }
    if(in_array(get_controller(),array('panel_products','panel_applications'))){?>
        $("#selMarcas").jCombo("<?php echo site_url('panel_products'); ?>/get_json_marcas",{
          selected_value: "<?php echo empty($selMarcas) ? 0 : $selMarcas; ?>",
          initial_text: "-- seleccione --"
        });
        $("#selModelos").jCombo("<?php echo site_url('panel_products'); ?>/get_json_modelos/",{
           parent: "#selMarcas",
           selected_value: "<?php echo empty($selModelos) ? 0 : $selModelos; ?>",
           initial_text: "-- seleccione --"
        });
        $("#selTiposMotores").jCombo("<?php echo site_url('panel_products'); ?>/get_json_tipos_motores",{
           selected_value: "<?php echo empty($selTiposMotores) ? 0 : $selTiposMotores; ?>",
           initial_text: "-- seleccione --"
        });
    <?php } ?>
    $('.btEliminar').click(function( event ) {
        if(!confirm("Si elimina este item, se eliminarán también todos los items relacionados en otras tablas. ¿Realmente desea eliminar este item?")) {
            if(event.preventDefault){event.preventDefault();}else{return false;}
        }
    });
});
</script>
</body>
</html>
