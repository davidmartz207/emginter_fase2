/**
 * Created by acampos on 5/5/17.
 */

$(document).ready(function(){
//si la pagina es productos
if(pagina == "products"){
    $(".logo-linea").removeClass("logo-linea-active");

    $("#sku_changeable").find(".lblSKU").html($("#sku_changeable").find(".lblSKU").data("lbl-"+$(".logo-linea.logo-linea-active").data("linea")));
    $("#sku_changeable").find("#txtSKU").attr("placeholder",$("#sku_changeable").find("#txtSKU").data("placeholder-"+$(".logo-linea.logo-linea-active").data("linea")));

    $(".logo-linea").on("click",function(){
        $(".formulario-productos").attr('style','');

        var linea;
        switch ($(this).data("linea")){
            case "emg" : linea = 1;
                break;

            case "perfection" : linea = 2;
                break;

            case "mrc" : linea = 3;
                break;
        }

        var direccion = dirproductos +"/get_json_all_products_type/"+linea;


        $("#selTiposProductosDownloads").jCombo(direccion,{
            selected_value: selected_value,
            initial_text: initial_text
        });




        $(".logo-linea").removeClass("logo-linea-active");
        $(this).addClass("logo-linea-active");
        $("#form-products").css("visibility", "visible");

        $("#formSearchProducts").find("#linea").val($(this).data("linea"));

        if( $(this).data("linea") =="perfection"){
            $("#perfection-line-logo").addClass('col-lg-offset-4');
            $("#emg-line-logo").addClass('hidden');
            $("#mrc-line-logo").addClass('hidden');
            $("#intercambio").addClass("hidden");
        }else if($(this).data("linea") =="emg") {
            $("#emg-line-logo").addClass('col-lg-offset-4');
            $("#perfection-line-logo").addClass('hidden');
            $("#mrc-line-logo").addClass('hidden');
            $("#intercambio").removeClass("hidden");
        }else if($(this).data("linea") =="mrc") {
            $("#mrc-line-logo").addClass('col-lg-offset-4');
            $("#emg-line-logo").addClass('hidden');
            $("#perfection-line-logo").addClass('hidden');
            $("#intercambio").removeClass("hidden");
        }


        $("#sku_changeable").find(".lblSKU").html($("#sku_changeable").find(".lblSKU").data("lbl-"+$(this).data("linea")));
        $("#sku_changeable").find("#txtSKU").attr("placeholder",$("#sku_changeable").find("#txtSKU").data("placeholder-"+$(this).data("linea")));

        // -----------------------------------------------------------------
        // search by SKU
        // -----------------------------------------------------------------


        $( "#txtSKU" ).autocomplete({
            source: dirproductos +"/get_json_sku/"+$(this).data("linea"),
            minLength: 1,
            select: function( event, ui ){
                $("#hddLastField").val("btSearchSKU");
                $("#btSearchSKU").removeAttr("disabled");
            }
        });

        // -----------------------------------------------------------------
        // search by competitor
        // -----------------------------------------------------------------

        $( "#txtCompetitor" ).autocomplete({
            source: dirproductos +"/get_json_competitor/"+$(this).data("linea"),
            minLength: 1,
            select: function( event, ui ){
                $("#hddLastField").val("btSearchCompetitor");
                $("#selCompetitor" ).focus();
            }
        });

    })

    //si la busqueda está iniciada
    if(busquedaon){

        $(".formulario-productos").attr('style','');
        //trigger del boton que pertenece.
        var cadena = String("#img"+strlinea);
        $(cadena).trigger("click");

    }

}

//si la pagina es catalogos
if(pagina == "catalogs"){
        $(".logo-linea").removeClass("logo-linea-active");

        $(".logo-linea").on("click",function(){
            $(".formulario-productos").attr('style','');

            var linea;
            switch ($(this).data("linea")){
                case "emg" : linea = 1;
                    break;

                case "perfection" : linea = 2;
                    break;

                case "mrc" : linea = 3;
                    break;
            }

            $(".logo-linea").removeClass("logo-linea-active");
            $(this).addClass("logo-linea-active");
            $("#form-products").css("visibility", "visible");

            $("#formSearchProducts").find("#linea").val($(this).data("linea"));

            if( $(this).data("linea") =="perfection"){
                $("#perfection-line-logo").addClass('col-lg-offset-4');
                $("#emg-line-logo").addClass('hidden');
                $("#mrc-line-logo").addClass('hidden');

                $(".catalog_perfection").removeClass('hidden');
                $(".catalog_emg").addClass('hidden');
                $(".catalog_mrc").addClass('hidden');

            }else if($(this).data("linea") =="emg") {
                $("#emg-line-logo").addClass('col-lg-offset-4');
                $("#perfection-line-logo").addClass('hidden');
                $("#mrc-line-logo").addClass('hidden');


                $(".catalog_emg").removeClass('hidden');
                $(".catalog_perfection").addClass('hidden');
                $(".catalog_mrc").addClass('hidden');
            }else if($(this).data("linea") =="mrc") {
                $("#mrc-line-logo").addClass('col-lg-offset-4');
                $("#emg-line-logo").addClass('hidden');
                $("#perfection-line-logo").addClass('hidden');


                $(".catalog_mrc").removeClass('hidden');
                $(".catalog_emg").addClass('hidden');
                $(".catalog_perfection").addClass('hidden');
            }

        });

        //si la busqueda está iniciada
        if(busquedaon){

            $(".formulario-productos").attr('style','');
            //trigger del boton que pertenece.
            var cadena = String("#img"+strlinea);
            $(cadena).trigger("click");

        }

    }


});