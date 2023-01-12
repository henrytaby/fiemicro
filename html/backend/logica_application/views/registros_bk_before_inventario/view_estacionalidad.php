<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios();
    
    $calculo_lead = $this->mfunciones_generales->CalculoLead($estructura_id, 'estacionalidad');
    
    echo 'estacion_sel("' . $arrRespuesta[0]['estacion_sel'] . '");';
    echo 'estacion_sel_mes("' . $arrRespuesta[0]['estacion_sel_mes'] . '");';
    echo 'estacion_sel_arb("' . $arrRespuesta[0]['estacion_sel_arb'] . '");';
    echo 'montos_mes();';
    
    
?>
    $(function(){
        
        $("#estacion_sel").on('change', function(){
            
            var valor = $('#estacion_sel option:selected').val();
            estacion_sel(valor);
        });
        
        $("#estacion_sel_mes").on('change', function(){
            
            var valor = $('#estacion_sel_mes option:selected').val();
            estacion_sel_mes(valor);
        });
        
        $("#estacion_sel_arb").on('change', function(){
            
            var valor = $('#estacion_sel_arb option:selected').val();
            estacion_sel_arb(valor);
            montos_mes();
        });
        
        $('input[name="capacidad_criterio"]').on('change', function(){
            montos_mes();
        });
        
        // Cada vez que cambie el valor de las cajas, se actualiza el valor
        $('.calculo_estacionalidad input[type="number"]').on('keyup change', function(){
            montos_mes();
        });
        
        $('.texto_monto select').on('change', function(){
            montos_mes();
        });
        
    });
    
    function estacion_sel(valor)
    {
        $("#estacion_no").hide();
        $("#estacion_si").hide();

        switch (valor) {
            case "0": $("#estacion_no").fadeIn(500); break;
            case "1": $("#estacion_si").fadeIn(500); break;

            default: break;
        }
    }
    
    function estacion_sel_mes(valor)
    {
        $("#estacion_ene").show();
        $("#estacion_feb").show();
        $("#estacion_mar").show();
        $("#estacion_abr").show();
        $("#estacion_may").show();
        $("#estacion_jun").show();
        $("#estacion_jul").show();
        $("#estacion_ago").show();
        $("#estacion_sep").show();
        $("#estacion_oct").show();
        $("#estacion_nov").show();
        $("#estacion_dic").show();

        switch (valor) {
            case "1": $("#estacion_ene").hide(); break;
            case "2": $("#estacion_feb").hide(); break;
            case "3": $("#estacion_mar").hide(); break;
            case "4": $("#estacion_abr").hide(); break;
            case "5": $("#estacion_may").hide(); break;
            case "6": $("#estacion_jun").hide(); break;
            case "7": $("#estacion_jul").hide(); break;
            case "8": $("#estacion_ago").hide(); break;
            case "9": $("#estacion_sep").hide(); break;
            case "10": $("#estacion_oct").hide(); break;
            case "11": $("#estacion_nov").hide(); break;
            case "12": $("#estacion_dic").hide(); break;

            default: break;
        }
    }
    
    function estacion_sel_arb(valor)
    {
        $("#estacion_regular").hide();
        $("#estacion_otro").hide();

        switch (valor) {
            case "1": $("#estacion_otro").fadeIn(500); $("#arm2").html("Regular"); $("#arm3").html("Bajo"); break;
            case "2": $("#estacion_regular").fadeIn(500); break;
            case "3": $("#estacion_otro").fadeIn(500); $("#arm2").html("Alta"); $("#arm3").html("Regular"); break;

            default: $("#arm2").html("Sin selección"); $("#arm3").html("Sin selección"); break;
        }
    }
    
    function montos_mes()
    {
        // Capturar el valor del Radio seleccionado
        
        var valor_seleccion=0;
        
        switch ($("input:radio[name ='capacidad_criterio']:checked").val()) 
        {
            case "1": valor_seleccion = $("#criterio1").val(); break;
            case "2": valor_seleccion = $("#criterio2").val(); break;
            case "3": valor_seleccion = $("#criterio3").val(); break;
            case "4": valor_seleccion = $("#capacidad_monto_manual").val(); break;

            default: valor_seleccion = "0.00"; break;
        }
        
        $("#monto_criterio_seleccionado").val(valor_seleccion);
        
        // Saber los montos de la estacionalidad
        
        var estacion_sel_arb = $('#estacion_sel_arb option:selected').val();
        
        var alta=0;
        var regular=0;
        var baja=0;
        
        switch (estacion_sel_arb) 
        {
            case "1":

                alta = valor_seleccion;
                regular = $("#estacion_monto2").val();
                baja = $("#estacion_monto3").val();

                break;
                
            case "2":
            case "3":

                alta = $("#estacion_monto2").val();
                regular = $("#estacion_monto3").val();
                baja = valor_seleccion;

                break;

            default:

                alta = $("#estacion_monto2").val();
                regular = $("#estacion_monto3").val();
                baja = valor_seleccion;

                break;
        }
        
        alta = (parseFloat(alta || 0)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ',');
        regular = (parseFloat(regular || 0)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ',');
        baja = (parseFloat(baja || 0)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ',');
        
        var elemento_id = '';
        
        $('#meses_monto').find('select')
        .each(function() {
            elemento_id = $(this).attr('id');
    
            switch ($('#' + elemento_id + ' option:selected').val()) 
            {
                case "1": $('#' + elemento_id + '_monto').html("Bs. " + alta); break;
                case "2": $('#' + elemento_id + '_monto').html("Bs. " + regular); break;
                case "3": $('#' + elemento_id + '_monto').html("Bs. " + baja); break;
                case "4": $('#' + elemento_id + '_monto').html("Bs. 0.00"); break;

                default: $('#' + elemento_id + '_monto').html("Sin Selección"); break;
            }
            
        });
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
    
    <input type="hidden" id="criterio1" value="<?php echo $calculo_lead->criterio_frecuencia_ingreso; ?>" />
    <input type="hidden" id="criterio2" value="<?php echo $calculo_lead->criterio_principales_productos; ?>" />
    <input type="hidden" id="criterio3" value="<?php echo $calculo_lead->criterio_materia_prima; ?>" />
    
    <input type="hidden" id="monto_criterio_seleccionado" name="monto_criterio_seleccionado" />
    
    <nav class="navbar sticky-top <?php echo $clase_navbar_extra; ?>"> <?php echo $this->mfunciones_generales->getContenidoNavApp($estructura_id, $vista_actual); ?> </nav>

    <div class="contenido_formulario container calculo_estacionalidad <?php echo $clase_contenido_extra; ?>">
    
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <div class="panel panel-default">
            <div class="panel-heading">Seleccione el Criterio para Estimar la Capacidad de Pago</div>
            <div class="panel-body">

                <div class="container">
                    <div class="row">

                        <div class="col" style="text-align: justify;">
                            <input id="seleccion1" name="capacidad_criterio" type="radio" class="" value="1" <?php if($arrRespuesta[0]["capacidad_criterio"]==1) echo "checked='checked'"; ?> />
                            <label for="seleccion1" class="label-campo label-amplio"><span></span>Ventas Declaradas Según Frecuencia del Ingreso</label>
                        </div>
                        <div class="col columna_middle" style="text-align: center;">
                            <label for="seleccion1" class="label-campo">Bs. <?php echo number_format($calculo_lead->criterio_frecuencia_ingreso, 2, ',', '.'); ?></label>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col" style="text-align: justify;">
                            <input id="seleccion2" name="capacidad_criterio" type="radio" class="" value="2" <?php if($arrRespuesta[0]["capacidad_criterio"]==2) echo "checked='checked'"; ?> />
                            <label for="seleccion2" class="label-campo label-amplio"><span></span>Ventas por Principales Productos Comercializados</label>
                        </div>
                        <div class="col columna_middle" style="text-align: center;">
                            <label for="seleccion2" class="label-campo">Bs. <?php echo number_format($calculo_lead->criterio_principales_productos, 2, ',', '.'); ?></label>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col" style="text-align: justify;">
                            <input id="seleccion3" name="capacidad_criterio" type="radio" class="" value="3" <?php if($arrRespuesta[0]["capacidad_criterio"]==3) echo "checked='checked'"; ?> />
                            <label for="seleccion3" class="label-campo label-amplio"><span></span><?php echo ($codigo_rubro==3 ? 'Ventas Según Compras a Principales Proveedores' : 'Ventas Según Compras de Materia Prima'); ?></label>
                        </div>
                        <div class="col columna_middle" style="text-align: center;">
                            <label for="seleccion3" class="label-campo">Bs. <?php echo number_format($calculo_lead->criterio_materia_prima, 2, ',', '.'); ?></label>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col" style="text-align: justify;">
                            <input id="seleccion4" name="capacidad_criterio" type="radio" class="" value="4" <?php if($arrRespuesta[0]["capacidad_criterio"]==4) echo "checked='checked'"; ?> />
                            <label for="seleccion4" class="label-campo label-amplio"><span></span>Cruce Personalizado</label>
                        </div>
                        <div class="col columna_middle" style="text-align: center;">
                            <?php echo $arrCajasHTML['capacidad_monto_manual']; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        
        <div class='col-sm-12'style="text-align: center;"> <label class='label-campo' for=''>¿La actividad tiene estacionalidad marcada?</label><br /><?php echo $arrCajasHTML['estacion_sel']; ?></div>
                
        <div style="clear: both"></div>

        <br />
        
        <div id="estacion_no" style="text-align: center;">
            <label class='label-campo texto-centrado' for=''><?php echo $this->lang->line('estacion_no_accion'); ?></label>
        </div>
        
        <div id="estacion_si">
        
            <div class="panel panel-default">
                <div class="panel-heading">Identifique la estacionalidad del mes de evaluación y categorizar con Alta, Regular o Baja</div>
                <div class="panel-body">

                    <div class='col-sm-12'style="text-align: center;"> <?php echo $arrCajasHTML['estacion_sel_mes']; ?><br /><br /><?php echo $arrCajasHTML['estacion_sel_arb']; ?></div>


                </div>
            </div>
            
            <div id="estacion_regular" style="text-align: center;">
                <label class='label-campo texto-centrado' for=''><?php echo $this->lang->line('estacion_no_accion'); ?></label>
            </div>
            
            <div id="estacion_otro">
                
                <div class="panel panel-default">
                    <div class="panel-heading"> Determinación del Ingreso para Actividades con Estacionalidad Marcada </div>
                    <div class="panel-body">

                        <div class="container">
                            <div class="row">

                                <div class="col" style="text-align: justify;">
                                    <label class='label-campo texto-centrado' for=''>CRITERIO</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: justify;">
                                    <label class='label-campo texto-centrado' for='' id='arm2'></label>
                                </div>
                                <div class="col" style="text-align: justify;">
                                    <?php echo $arrCajasHTML['estacion_monto2']; ?>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: justify;">
                                    <label class='label-campo texto-centrado' for='' id='arm3'></label>
                                </div>
                                <div class="col" style="text-align: justify;">
                                    <?php echo $arrCajasHTML['estacion_monto3']; ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="panel panel-default">
                    <div class="panel-heading">Categorización de los demás Meses</div>
                    <div class="panel-body" id="meses_monto">
                        
                        <div class='col-sm-6 texto_monto'style="text-align: justify;" id="estacion_ene"> <table border="0" style="width: 300px;"><tr><td style="width: 50%; text-align: left;"> <label class='label-campo texto-centrado' for=''> <?php echo $this->lang->line('estacion_ene_arb'); ?> </label> </td><td style="width: 50%; text-align: right;"> <label id="estacion_ene_arb_monto" class='label-campo' for=''> </label></td></tr></table><?php echo $arrCajasHTML['estacion_ene_arb']; ?></div>
                        <div class='col-sm-6 texto_monto'style="text-align: justify;" id="estacion_feb"> <table border="0" style="width: 300px;"><tr><td style="width: 50%; text-align: left;"> <label class='label-campo texto-centrado' for=''> <?php echo $this->lang->line('estacion_feb_arb'); ?> </label> </td><td style="width: 50%; text-align: right;"> <label id="estacion_feb_arb_monto" class='label-campo' for=''> </label></td></tr></table><?php echo $arrCajasHTML['estacion_feb_arb']; ?></div>
                        <div class='col-sm-6 texto_monto'style="text-align: justify;" id="estacion_mar"> <table border="0" style="width: 300px;"><tr><td style="width: 50%; text-align: left;"> <label class='label-campo texto-centrado' for=''> <?php echo $this->lang->line('estacion_mar_arb'); ?> </label> </td><td style="width: 50%; text-align: right;"> <label id="estacion_mar_arb_monto" class='label-campo' for=''> </label></td></tr></table><?php echo $arrCajasHTML['estacion_mar_arb']; ?></div>
                        <div class='col-sm-6 texto_monto'style="text-align: justify;" id="estacion_abr"> <table border="0" style="width: 300px;"><tr><td style="width: 50%; text-align: left;"> <label class='label-campo texto-centrado' for=''> <?php echo $this->lang->line('estacion_abr_arb'); ?> </label> </td><td style="width: 50%; text-align: right;"> <label id="estacion_abr_arb_monto" class='label-campo' for=''> </label></td></tr></table><?php echo $arrCajasHTML['estacion_abr_arb']; ?></div>
                        <div class='col-sm-6 texto_monto'style="text-align: justify;" id="estacion_may"> <table border="0" style="width: 300px;"><tr><td style="width: 50%; text-align: left;"> <label class='label-campo texto-centrado' for=''> <?php echo $this->lang->line('estacion_may_arb'); ?> </label> </td><td style="width: 50%; text-align: right;"> <label id="estacion_may_arb_monto" class='label-campo' for=''> </label></td></tr></table><?php echo $arrCajasHTML['estacion_may_arb']; ?></div>
                        <div class='col-sm-6 texto_monto'style="text-align: justify;" id="estacion_jun"> <table border="0" style="width: 300px;"><tr><td style="width: 50%; text-align: left;"> <label class='label-campo texto-centrado' for=''> <?php echo $this->lang->line('estacion_jun_arb'); ?> </label> </td><td style="width: 50%; text-align: right;"> <label id="estacion_jun_arb_monto" class='label-campo' for=''> </label></td></tr></table><?php echo $arrCajasHTML['estacion_jun_arb']; ?></div>
                        <div class='col-sm-6 texto_monto'style="text-align: justify;" id="estacion_jul"> <table border="0" style="width: 300px;"><tr><td style="width: 50%; text-align: left;"> <label class='label-campo texto-centrado' for=''> <?php echo $this->lang->line('estacion_jul_arb'); ?> </label> </td><td style="width: 50%; text-align: right;"> <label id="estacion_jul_arb_monto" class='label-campo' for=''> </label></td></tr></table><?php echo $arrCajasHTML['estacion_jul_arb']; ?></div>
                        <div class='col-sm-6 texto_monto'style="text-align: justify;" id="estacion_ago"> <table border="0" style="width: 300px;"><tr><td style="width: 50%; text-align: left;"> <label class='label-campo texto-centrado' for=''> <?php echo $this->lang->line('estacion_ago_arb'); ?> </label> </td><td style="width: 50%; text-align: right;"> <label id="estacion_ago_arb_monto" class='label-campo' for=''> </label></td></tr></table><?php echo $arrCajasHTML['estacion_ago_arb']; ?></div>
                        <div class='col-sm-6 texto_monto'style="text-align: justify;" id="estacion_sep"> <table border="0" style="width: 300px;"><tr><td style="width: 50%; text-align: left;"> <label class='label-campo texto-centrado' for=''> <?php echo $this->lang->line('estacion_sep_arb'); ?> </label> </td><td style="width: 50%; text-align: right;"> <label id="estacion_sep_arb_monto" class='label-campo' for=''> </label></td></tr></table><?php echo $arrCajasHTML['estacion_sep_arb']; ?></div>
                        <div class='col-sm-6 texto_monto'style="text-align: justify;" id="estacion_oct"> <table border="0" style="width: 300px;"><tr><td style="width: 50%; text-align: left;"> <label class='label-campo texto-centrado' for=''> <?php echo $this->lang->line('estacion_oct_arb'); ?> </label> </td><td style="width: 50%; text-align: right;"> <label id="estacion_oct_arb_monto" class='label-campo' for=''> </label></td></tr></table><?php echo $arrCajasHTML['estacion_oct_arb']; ?></div>
                        <div class='col-sm-6 texto_monto'style="text-align: justify;" id="estacion_nov"> <table border="0" style="width: 300px;"><tr><td style="width: 50%; text-align: left;"> <label class='label-campo texto-centrado' for=''> <?php echo $this->lang->line('estacion_nov_arb'); ?> </label> </td><td style="width: 50%; text-align: right;"> <label id="estacion_nov_arb_monto" class='label-campo' for=''> </label></td></tr></table><?php echo $arrCajasHTML['estacion_nov_arb']; ?></div>
                        <div class='col-sm-6 texto_monto'style="text-align: justify;" id="estacion_dic"> <table border="0" style="width: 300px;"><tr><td style="width: 50%; text-align: left;"> <label class='label-campo texto-centrado' for=''> <?php echo $this->lang->line('estacion_dic_arb'); ?> </label> </td><td style="width: 50%; text-align: right;"> <label id="estacion_dic_arb_monto" class='label-campo' for=''> </label></td></tr></table><?php echo $arrCajasHTML['estacion_dic_arb']; ?></div>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
        
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>