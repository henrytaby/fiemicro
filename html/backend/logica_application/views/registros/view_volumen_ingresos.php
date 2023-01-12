<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios('sin_combo');
    
?>
    
    $(document).ready(function() {
        $("#transporte_cliente_frecuencia").togglebutton();
        $("div.modal-backdrop").remove();
        
        $(document).ready(function(){ 
            $('.suma_campos_lineas').find("input[type=text]").each(function(ev)
            {
                $(this).attr("placeholder", " :: Libre a utilizar ::");
            });
        });
        
        suma_campos();
    });
    
    // Cada vez que cambie el valor de las cajas, se actualiza el valor
    $('.declaracion_cliente input[type="number"]').on('keyup change', function(){
        suma_campos();
    });
    
    $('.capacidad_instalada input[type="number"]').on('keyup change', function(){
        suma_campos();
    });
    
    $("#transporte_cliente_frecuencia").on('change', function(){
        suma_campos();
    });
    
    ver_bloques("<?php echo $arrRespuesta[0]['transporte_tipo_prestatario']; ?>", "<?php echo $arrRespuesta[0]['transporte_tipo_transporte']; ?>");
    
    <?php
    
    $aux_columnas = 'col-sm-4';
    $aux_columnas_1 = 'col-sm-4';
    
    if($arrRespuesta[0]['transporte_tipo_transporte'] >= 4){ $aux_columnas = 'col-sm-6'; $aux_columnas_1 = 'col-sm-12'; }
    if($arrRespuesta[0]['transporte_tipo_prestatario'] == 2){ $aux_columnas = 'col-sm-12'; }
    
    
    ?>
    
    
    $(".titulo_vuelta").html("<?php echo ($arrRespuesta[0]['transporte_tipo_transporte']>=4 ? 'CARRERA' : 'VUELTA'); ?>");
    
    function ver_bloques(transporte_tipo_prestatario, transporte_tipo_transporte)
    {
        $(".suma_campos_dias").show();
        $(".suma_campos_lineas").show();
        $(".suma_campos_vueltas").show();
        
        $(".capacidad_instalada").show();
        $(".suma_campos_rotacion").show();
        $(".suma_campos_tramo").show();
        $(".capacidad_instalada").show();
        
        switch (transporte_tipo_prestatario) {
            case "2":

                $(".suma_campos_lineas").hide();
                $(".suma_campos_vueltas").hide();
                $(".capacidad_instalada").hide();

                break;

            default:

                break;
        }
        
        switch (transporte_tipo_transporte) {
            case "4":
            case "5":
            case "6":

                $(".suma_campos_lineas").hide();
                $(".suma_campos_rotacion").hide();
                $(".suma_campos_tramo").hide();

                break;

            default:

                break;
        }
    }
    
    function suma_campos()
    {
        
        var transporte_tipo_transporte = <?php echo $arrRespuesta[0]['transporte_tipo_transporte']; ?>;
        
        // DIAS TRABAJDOS
        
        var frecuencia_seleccionada = parseInt($('#transporte_cliente_frecuencia option:selected').val() || 0);
            
        switch (frecuencia_seleccionada) {
            case 1:
                $(".seleccion_frecuencia").html("Semana");
                break;

            case 2:
                $(".seleccion_frecuencia").html("Quincena");
                break;

            case 4:
                $(".seleccion_frecuencia").html("Mes");
                break;

            default:
                $(".seleccion_frecuencia").html("Sin Seleccion");
                break;
        }

        var dias_considerados = <?php echo $arrRespuesta[0]['transporte_preg_trabaja_semana']; ?> * frecuencia_seleccionada;
            $("#transporte_preg_trabaja_semana").html((dias_considerados).toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        
        // DIAS
        
        var transporte_cliente_dia_lunes = parseFloat($("#transporte_cliente_dia_lunes").val() || 0);
        var transporte_cliente_dia_martes = parseFloat($("#transporte_cliente_dia_martes").val() || 0);
        var transporte_cliente_dia_miercoles = parseFloat($("#transporte_cliente_dia_miercoles").val() || 0);
        var transporte_cliente_dia_jueves = parseFloat($("#transporte_cliente_dia_jueves").val() || 0);
        var transporte_cliente_dia_viernes = parseFloat($("#transporte_cliente_dia_viernes").val() || 0);
        var transporte_cliente_dia_sabado = parseFloat($("#transporte_cliente_dia_sabado").val() || 0);
        var transporte_cliente_dia_domingo = parseFloat($("#transporte_cliente_dia_domingo").val() || 0);
        
        var suma_dias = 
        transporte_cliente_dia_lunes + 
        transporte_cliente_dia_martes + 
        transporte_cliente_dia_miercoles + 
        transporte_cliente_dia_jueves + 
        transporte_cliente_dia_viernes + 
        transporte_cliente_dia_sabado + 
        transporte_cliente_dia_domingo;
        
        $("#suma_total_dias").html(suma_dias.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));

        var contador_dias = 0;
            
        if(transporte_cliente_dia_lunes != 0){contador_dias++;}
        if(transporte_cliente_dia_martes != 0){contador_dias++;}
        if(transporte_cliente_dia_miercoles != 0){contador_dias++;}
        if(transporte_cliente_dia_jueves != 0){contador_dias++;}
        if(transporte_cliente_dia_viernes != 0){contador_dias++;}
        if(transporte_cliente_dia_sabado != 0){contador_dias++;}
        if(transporte_cliente_dia_domingo != 0){contador_dias++;}

        
        var frecuencia_total_dias = 0;
        
        if(contador_dias != 0)
        {
            frecuencia_total_dias = (suma_dias/contador_dias) * dias_considerados;
        }

        $("#frecuencia_total_dias").html((frecuencia_total_dias).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));

        // LINEAS

        var suma_linea_min = 
        parseFloat($("#transporte_cliente_linea1_min").val() || 0) + 
        parseFloat($("#transporte_cliente_linea2_min").val() || 0) + 
        parseFloat($("#transporte_cliente_linea3_min").val() || 0) + 
        parseFloat($("#transporte_cliente_linea4_min").val() || 0) + 
        parseFloat($("#transporte_cliente_linea5_min").val() || 0) + 
        parseFloat($("#transporte_cliente_linea6_min").val() || 0) + 
        parseFloat($("#transporte_cliente_linea7_min").val() || 0);

        $("#suma_linea_min").html(suma_linea_min.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        
        var suma_linea_max = 
        parseFloat($("#transporte_cliente_linea1_max").val() || 0) + 
        parseFloat($("#transporte_cliente_linea2_max").val() || 0) + 
        parseFloat($("#transporte_cliente_linea3_max").val() || 0) + 
        parseFloat($("#transporte_cliente_linea4_max").val() || 0) + 
        parseFloat($("#transporte_cliente_linea5_max").val() || 0) + 
        parseFloat($("#transporte_cliente_linea6_max").val() || 0) + 
        parseFloat($("#transporte_cliente_linea7_max").val() || 0);

        $("#suma_linea_max").html(suma_linea_max.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));

        // Promedio
        
            var transporte_cliente_linea1_value = ((parseFloat($("#transporte_cliente_linea1_max").val() || 0) + 
            parseFloat($("#transporte_cliente_linea1_min").val() || 0))/2);
    
            var transporte_cliente_linea2_value = ((parseFloat($("#transporte_cliente_linea2_max").val() || 0) + 
            parseFloat($("#transporte_cliente_linea2_min").val() || 0))/2);
    
            var transporte_cliente_linea3_value = ((parseFloat($("#transporte_cliente_linea3_max").val() || 0) + 
            parseFloat($("#transporte_cliente_linea3_min").val() || 0))/2);
    
            var transporte_cliente_linea4_value = ((parseFloat($("#transporte_cliente_linea4_max").val() || 0) + 
            parseFloat($("#transporte_cliente_linea4_min").val() || 0))/2);
    
            var transporte_cliente_linea5_value = ((parseFloat($("#transporte_cliente_linea5_max").val() || 0) + 
            parseFloat($("#transporte_cliente_linea5_min").val() || 0))/2);
    
            var transporte_cliente_linea6_value = ((parseFloat($("#transporte_cliente_linea6_max").val() || 0) + 
            parseFloat($("#transporte_cliente_linea6_min").val() || 0))/2);
    
            var transporte_cliente_linea7_value = ((parseFloat($("#transporte_cliente_linea7_max").val() || 0) + 
            parseFloat($("#transporte_cliente_linea7_min").val() || 0))/2);
        
            $("#transporte_cliente_linea1_value").html(transporte_cliente_linea1_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
            $("#transporte_cliente_linea2_value").html(transporte_cliente_linea2_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
            $("#transporte_cliente_linea3_value").html(transporte_cliente_linea3_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
            $("#transporte_cliente_linea4_value").html(transporte_cliente_linea4_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
            $("#transporte_cliente_linea5_value").html(transporte_cliente_linea5_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
            $("#transporte_cliente_linea6_value").html(transporte_cliente_linea6_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
            $("#transporte_cliente_linea7_value").html(transporte_cliente_linea7_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
    
            var suma_linea_promedio = (suma_linea_max+suma_linea_min)/2;
            $("#suma_linea_promedio").html(suma_linea_promedio.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        
            var frecuencia_total_dias = 0;

             var contador_lineas = 0;
            
            if(transporte_cliente_linea1_value != 0){contador_lineas++;}
            if(transporte_cliente_linea2_value != 0){contador_lineas++;}
            if(transporte_cliente_linea3_value != 0){contador_lineas++;}
            if(transporte_cliente_linea4_value != 0){contador_lineas++;}
            if(transporte_cliente_linea5_value != 0){contador_lineas++;}
            if(transporte_cliente_linea6_value != 0){contador_lineas++;}
            if(transporte_cliente_linea7_value != 0){contador_lineas++;}
        
            var frecuencia_total_linea = 0;
        
            if(contador_lineas != 0)
            {
                frecuencia_total_linea = (suma_linea_promedio/contador_lineas) * dias_considerados;
            }

            $("#frecuencia_total_linea").html((frecuencia_total_linea).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        
        // VUELTAS
        
        var suma_vuelta_importe = 
        parseFloat($("#transporte_cliente_vueta_buena_monto").val() || 0) + 
        parseFloat($("#transporte_cliente_vueta_regular_monto").val() || 0) + 
        parseFloat($("#transporte_cliente_vueta_mala_monto").val() || 0);

        $("#suma_vuelta_importe").html(suma_vuelta_importe.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        
        var suma_vuelta_veces = 
        parseFloat($("#transporte_cliente_vueta_buena_numero").val() || 0) + 
        parseFloat($("#transporte_cliente_vueta_regular_numero").val() || 0) + 
        parseFloat($("#transporte_cliente_vueta_mala_numero").val() || 0);

        $("#suma_vuelta_veces").html(suma_vuelta_veces.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        
        // Promedio
            var transporte_cliente_vuelta1_value = parseFloat($("#transporte_cliente_vueta_buena_monto").val() || 0) * parseFloat($("#transporte_cliente_vueta_buena_numero").val() || 0);
            $("#transporte_cliente_vuelta1_value").html(transporte_cliente_vuelta1_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
            
            var transporte_cliente_vuelta2_value = parseFloat($("#transporte_cliente_vueta_regular_monto").val() || 0) * parseFloat($("#transporte_cliente_vueta_regular_numero").val() || 0);
            $("#transporte_cliente_vuelta2_value").html(transporte_cliente_vuelta2_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
            
            var transporte_cliente_vuelta3_value = parseFloat($("#transporte_cliente_vueta_mala_monto").val() || 0) * parseFloat($("#transporte_cliente_vueta_mala_numero").val() || 0);
            $("#transporte_cliente_vuelta3_value").html(transporte_cliente_vuelta3_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
            
            var suma_vuelta_promedio = (transporte_cliente_vuelta1_value+transporte_cliente_vuelta2_value+transporte_cliente_vuelta3_value);
            $("#suma_vuelta_promedio").html(suma_vuelta_promedio.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
            
            $("#frecuencia_total_vuelta").html((suma_vuelta_promedio*dias_considerados).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
            
        // Capacidad
        
        var transporte_capacidad_sin_rotacion = parseInt($("#transporte_capacidad_sin_rotacion").val() || 0);
        var transporte_capacidad_con_rotacion = parseInt($("#transporte_capacidad_con_rotacion").val() || 0);
        var suma_total_rotacion = 0;
        
        if(transporte_capacidad_sin_rotacion != 0)
        {
            suma_total_rotacion = transporte_capacidad_con_rotacion/transporte_capacidad_sin_rotacion;
        }
        $("#suma_total_rotacion").html((suma_total_rotacion).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        
        
        var suma_total_tramo = 0;
        
        var transporte_capacidad_tramo_largo_pasajero = parseInt($("#transporte_capacidad_tramo_largo_pasajero").val() || 0);
        var transporte_capacidad_tramo_corto_pasajero = parseInt($("#transporte_capacidad_tramo_corto_pasajero").val() || 0);
        var transporte_capacidad_tramo_largo_precio = parseFloat($("#transporte_capacidad_tramo_largo_precio").val() || 0);
        var transporte_capacidad_tramo_corto_precio = parseFloat($("#transporte_capacidad_tramo_corto_precio").val() || 0);
        
        if((transporte_capacidad_tramo_largo_pasajero+transporte_capacidad_tramo_corto_pasajero) != 0)
        {
            suma_total_tramo = (transporte_capacidad_tramo_largo_precio*transporte_capacidad_tramo_largo_pasajero)/(transporte_capacidad_tramo_largo_pasajero+transporte_capacidad_tramo_corto_pasajero)+(transporte_capacidad_tramo_corto_precio*transporte_capacidad_tramo_corto_pasajero)/(transporte_capacidad_tramo_largo_pasajero+transporte_capacidad_tramo_corto_pasajero);
        }
        
        $("#suma_total_tramo").html((suma_total_tramo).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        
        // Total Ingreso
        
        var total_pasajeros_bueno = transporte_capacidad_con_rotacion * parseFloat($("#transporte_vuelta_buena_ocupacion").val() || 0) * 2;
        var total_pasajeros_regular = transporte_capacidad_con_rotacion * parseFloat($("#transporte_vuelta_regular_ocupacion").val() || 0) * 2;
        var total_pasajeros_malo = transporte_capacidad_con_rotacion * parseFloat($("#transporte_vuelta_mala_ocupacion").val() || 0) * 2;
        
        $("#total_pasajeros_bueno").html((total_pasajeros_bueno/100).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        $("#total_pasajeros_regular").html((total_pasajeros_regular/100).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        $("#total_pasajeros_malo").html((total_pasajeros_malo/100).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        
        if(parseInt(transporte_tipo_transporte) >= 4)
        {
            var total_ingreso_bueno = parseFloat($("#total_ingreso_bueno").val() || 0)*100;
            var total_ingreso_regular = parseFloat($("#total_ingreso_regular").val() || 0)*100;
            var total_ingreso_malo = parseFloat($("#total_ingreso_malo").val() || 0)*100;
        }
        else
        {
            $('#total_ingreso_bueno').attr('readonly', true);
            $('#total_ingreso_regular').attr('readonly', true);
            $('#total_ingreso_malo').attr('readonly', true);
            
            var total_ingreso_bueno = suma_total_tramo * total_pasajeros_bueno;
            var total_ingreso_regular = suma_total_tramo * total_pasajeros_regular;
            var total_ingreso_malo = suma_total_tramo * total_pasajeros_malo;
            
            $("#total_ingreso_bueno").val((total_ingreso_bueno/100).toFixed(2));
            $("#total_ingreso_regular").val((total_ingreso_regular/100).toFixed(2));
            $("#total_ingreso_malo").val((total_ingreso_malo/100).toFixed(2));
        }
        
        var transporte_vuelta_buena_veces = parseFloat($("#transporte_vuelta_buena_veces").val() || 0);
        var transporte_vuelta_regular_veces = parseFloat($("#transporte_vuelta_regular_veces").val() || 0);
        var transporte_vuelta_mala_veces = parseFloat($("#transporte_vuelta_mala_veces").val() || 0);
        
        var total_total_bueno = transporte_vuelta_buena_veces * total_ingreso_bueno;
        var total_total_regular = transporte_vuelta_regular_veces * total_ingreso_regular;
        var total_total_malo = transporte_vuelta_mala_veces * total_ingreso_malo;
        
        $("#total_total_bueno").html((total_total_bueno/100).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        $("#total_total_regular").html((total_total_regular/100).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        $("#total_total_malo").html((total_total_malo/100).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        
        var ingreso_promedio_vuelta = ((total_ingreso_bueno + total_ingreso_regular + total_ingreso_malo)/3);
        $("#ingreso_promedio_vuelta").html((ingreso_promedio_vuelta/100).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        
        var total_total = 0;
        
        if((transporte_vuelta_buena_veces+transporte_vuelta_regular_veces+transporte_vuelta_mala_veces) != 0)
        {
            total_total = (total_total_bueno+total_total_regular+total_total_malo)/(transporte_vuelta_buena_veces+transporte_vuelta_regular_veces+transporte_vuelta_mala_veces);
        }
        
        var ingreso_mensual = total_total * (transporte_vuelta_buena_veces+transporte_vuelta_regular_veces+transporte_vuelta_mala_veces) * dias_considerados;
        $("#ingreso_mensual").html((ingreso_mensual/100).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        
    }
    
</script>

    <?php

        if($vista_actual == '0')
        {
            $clase_contenido_extra = 'contenido_formulario-nopasos';
            $clase_navbar_extra = 'navbar-nopasos';
        }
        else
        {
            $clase_contenido_extra = '';
            $clase_navbar_extra = '';
        }
    ?>

<form id="FormularioRegistroLista" method="post">
    
    <input type="hidden" name="estructura_id" id="estructura_id" value="<?php echo $estructura_id; ?>" />
    <input type="hidden" name="codigo_rubro" id="codigo_rubro" value="<?php echo $codigo_rubro; ?>" />
    <input type="hidden" name="vista_actual" id="vista_actual" value="<?php echo $vista_actual; ?>" />
    <input type="hidden" name="home_ant_sig" id="home_ant_sig" value="" />
    <input type="hidden" name="sin_guardar" id="sin_guardar" value="0" />
    
    <input type="hidden" name="transporte_tipo_prestatario" value="<?php echo $arrRespuesta[0]['transporte_tipo_prestatario']; ?>" />
    <input type="hidden" name="transporte_tipo_transporte" value="<?php echo $arrRespuesta[0]['transporte_tipo_transporte']; ?>" />
    
    <nav class="navbar sticky-top <?php echo $clase_navbar_extra; ?>"> <?php echo $this->mfunciones_generales->getContenidoNavApp($estructura_id, $vista_actual); ?> </nav>

    <div class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">
    
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <div class='col-sm-12'style="text-align: center;"> <label class='label-campo texto-centrado panel-heading' for=''>II. ESTIMACIÓN DEL VOLUMEN DE INGRESOS PERIODICOS</label></div>
        
        <div class='col-sm-12 panel-default'style="text-align: right;"> 
            
            <label class='label-campo texto-centrado' for=''>DÍAS CONSIDERADOS</label>
            <label class='label-campo texto-centrado panel-heading' for='' id="transporte_preg_trabaja_semana"></label>
            
            <?php echo $arrCajasHTML['transporte_cliente_frecuencia']; ?>
            <br /><br />
        
        </div>
        
        <div style="clear: both"></div>
        
        <div class="panel panel-default declaracion_cliente">
            <div class="panel-heading">SEGÚN DECLARACIÓN DEL CLIENTE</div>
            <div class="panel-body">

                <div class='<?php echo $aux_columnas; ?> suma_campos_dias'>

                    <br />
                    <div class="container" style="margin-top: 5px;">
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>INFORMACIÓN POR DIA</label>
                            </div>
                        </div>
                        
                        <div class="row">

                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>POR DIA (Ultima semana)</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>PROMEDIO</label>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for='transporte_cliente_dia_lunes'><?php echo $this->lang->line('transporte_cliente_dia_lunes'); ?>:</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_dia_lunes']; ?>
                            </div>
                        </div>
                        
                        <div class="row">

                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for='transporte_cliente_dia_martes'><?php echo $this->lang->line('transporte_cliente_dia_martes'); ?>:</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_dia_martes']; ?>
                            </div>

                        </div>
                        
                        <div class="row">

                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for='transporte_cliente_dia_miercoles'><?php echo $this->lang->line('transporte_cliente_dia_miercoles'); ?>:</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_dia_miercoles']; ?>
                            </div>

                        </div>
                        
                        <div class="row">

                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for='transporte_cliente_dia_jueves'><?php echo $this->lang->line('transporte_cliente_dia_jueves'); ?>:</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_dia_jueves']; ?>
                            </div>

                        </div>
                        
                        <div class="row">

                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for='transporte_cliente_dia_viernes'><?php echo $this->lang->line('transporte_cliente_dia_viernes'); ?>:</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_dia_viernes']; ?>
                            </div>

                        </div>
                        
                        <div class="row">

                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for='transporte_cliente_dia_sabado'><?php echo $this->lang->line('transporte_cliente_dia_sabado'); ?>:</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_dia_sabado']; ?>
                            </div>

                        </div>
                        
                        <div class="row">

                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for='transporte_cliente_dia_domingo'><?php echo $this->lang->line('transporte_cliente_dia_domingo'); ?>:</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_dia_domingo']; ?>
                            </div>

                        </div>
                        
                        <div class="row">

                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>TOTAL</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for='' id="suma_total_dias"></label>
                            </div>

                        </div>
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='label-campo texto-centrado panel-heading seleccion_frecuencia' for=''></label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='label-campo texto-centrado panel-heading' for='' id="frecuencia_total_dias"></label>
                            </div>
                        </div>
                        
                    </div>

                </div>
                
                <div class='<?php echo $aux_columnas; ?> suma_campos_lineas'>

                    <br />
                    <div class="container" style="margin-top: 5px;">
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>INFORMACIÓN POR LÍNEA</label>
                            </div>
                        </div>
                        
                        <div class="row">

                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>LÍNEA</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>MÍNIMO</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>MÁXIMO</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>PROMEDIO</label>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea1_texto']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea1_min']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea1_max']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' id='transporte_cliente_linea1_value'></label>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea2_texto']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea2_min']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea2_max']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' id='transporte_cliente_linea2_value'></label>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea3_texto']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea3_min']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea3_max']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' id='transporte_cliente_linea3_value'></label>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea4_texto']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea4_min']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea4_max']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' id='transporte_cliente_linea4_value'></label>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea5_texto']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea5_min']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea5_max']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' id='transporte_cliente_linea5_value'></label>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea6_texto']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea6_min']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea6_max']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' id='transporte_cliente_linea6_value'></label>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea7_texto']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea7_min']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_linea7_max']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' id='transporte_cliente_linea7_value'></label>
                            </div>
                        </div>
                        
                        <div class="row">

                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>TOTAL</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for='' id="suma_linea_min"></label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for='' id="suma_linea_max"></label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for='' id="suma_linea_promedio"></label>
                            </div>

                        </div>
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='label-campo texto-centrado panel-heading seleccion_frecuencia' for=''></label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='label-campo texto-centrado panel-heading' for='' id="frecuencia_total_linea"></label>
                            </div>
                        </div>
                        
                    </div>

                </div>
                
                <div class='<?php echo $aux_columnas; ?> suma_campos_vueltas'>

                    <br />
                    <div class="container" style="margin-top: 5px;">
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>INFORMACIÓN POR <span class="titulo_vuelta"></span></label>
                            </div>
                        </div>
                        
                        <div class="row">

                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''><span class="titulo_vuelta"></span></label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>IMPORTE</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>VECES</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>PROMEDIO</label>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo'>BUENAS</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_vueta_buena_monto']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_vueta_buena_numero']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' id='transporte_cliente_vuelta1_value'></label>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo'>REG.</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_vueta_regular_monto']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_vueta_regular_numero']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' id='transporte_cliente_vuelta2_value'></label>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo'>MALAS</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_vueta_mala_monto']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_cliente_vueta_mala_numero']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' id='transporte_cliente_vuelta3_value'></label>
                            </div>
                        </div>
                        
                        <div class="row">

                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>TOTAL</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for='' id="suma_vuelta_importe"></label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for='' id="suma_vuelta_veces"></label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for='' id="suma_vuelta_promedio"></label>
                            </div>

                        </div>
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='label-campo texto-centrado panel-heading seleccion_frecuencia' for=''></label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='label-campo texto-centrado panel-heading' for='' id="frecuencia_total_vuelta"></label>
                            </div>
                        </div>
                        
                    </div>

                </div>

            </div>
        </div>
        
        <div style="clear: both"></div>
        
        <div class="panel panel-default capacidad_instalada">
            <div class="panel-heading">SEGÚN CAPACIDAD INSTALADA DEL VEHÍCULO</div>
            <div class="panel-body">
                
                <div class='<?php echo $aux_columnas_1; ?> suma_campos_rotacion'>

                    <br /><br />
                    <div class="container" style="margin-top: 5px;">

                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for='transporte_capacidad_sin_rotacion'><?php echo $this->lang->line('transporte_capacidad_sin_rotacion'); ?>:</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_capacidad_sin_rotacion']; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for='transporte_capacidad_con_rotacion'><?php echo $this->lang->line('transporte_capacidad_con_rotacion'); ?>:</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_capacidad_con_rotacion']; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>ROTACIÓN</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for='' id="suma_total_rotacion"></label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class='<?php echo $aux_columnas_1; ?> suma_campos_tramo'>

                    <br /><br />
                    <div class="container" style="margin-top: 5px;">

                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for='transporte_capacidad_tramo_largo_pasajero'><?php echo $this->lang->line('transporte_capacidad_tramo_largo_pasajero'); ?>:</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_capacidad_tramo_largo_pasajero']; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for='transporte_capacidad_tramo_corto_pasajero'><?php echo $this->lang->line('transporte_capacidad_tramo_corto_pasajero'); ?>:</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_capacidad_tramo_corto_pasajero']; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for='transporte_capacidad_tramo_largo_precio'><?php echo $this->lang->line('transporte_capacidad_tramo_largo_precio'); ?>:</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_capacidad_tramo_largo_precio']; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for='transporte_capacidad_tramo_corto_precio'><?php echo $this->lang->line('transporte_capacidad_tramo_corto_precio'); ?>:</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_capacidad_tramo_corto_precio']; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>PRECIO PROMEDIO</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for='' id="suma_total_tramo"></label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class='<?php echo $aux_columnas_1; ?> suma_campos_ocupacion'>

                    <br /><br />
                    <div class="container" style="margin-top: 5px;">

                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo titulo_vuelta' for=''></label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for=''>%OCUPACIÓN</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for=''>VECES</label>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>BUENA</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_vuelta_buena_ocupacion']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_vuelta_buena_veces']; ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>REGULAR</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_vuelta_regular_ocupacion']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_vuelta_regular_veces']; ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>MALA</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_vuelta_mala_ocupacion']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['transporte_vuelta_mala_veces']; ?>
                            </div>
                        </div>
                        
                        <br />
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for=''></label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for=''>BUENA</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for=''>REGULAR</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo' for=''>MALA</label>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''># Pasajeros</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for='' id="total_pasajeros_bueno"></label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for='' id="total_pasajeros_regular"></label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for='' id="total_pasajeros_malo"></label>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>Ingreso</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['total_ingreso_bueno']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['total_ingreso_regular']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['total_ingreso_malo']; ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>Total</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for='' id="total_total_bueno"></label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for='' id="total_total_regular"></label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for='' id="total_total_malo"></label>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>INGRESO PROMEDIO POR <span class="titulo_vuelta"></span></label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for='' id="ingreso_promedio_vuelta"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for=''>INGRESO MENSUAL</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='text-reducido label-campo texto-centrado' for='' id="ingreso_mensual"></label>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
        
        <br />
        
    </div>
        
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>