<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios();
    
    echo '$("#frecuencia_dia").hide(); $("#frecuencia_semana").hide(); $("#frecuencia_mes").hide();';
    
    switch ($arrRespuesta[0]['frec_seleccion']) {
        case "1": echo '$("#frecuencia_dia").show();'; break;
        case "2": echo '$("#frecuencia_semana").show();'; break;
        case "3": echo '$("#frecuencia_mes").show();'; break;

        default:
            break;
    }
    
    echo 'frec_dia_semana_sel("' . $arrRespuesta[0]['frec_dia_semana_sel'] . '");';
    echo 'frec_dia_semana_sel_brm("' . $arrRespuesta[0]['frec_dia_semana_sel_brm'] . '");';
    echo 'frec_mes_sel("' . $arrRespuesta[0]['frec_mes_sel'] . '");';
    
?>
    $(function(){
        
        $("#frec_seleccion").on('change', function(){
            
            $("#frecuencia_dia").hide();
            $("#frecuencia_semana").hide();
            $("#frecuencia_mes").hide();
            
            var valor = $('#frec_seleccion option:selected').val();
            
            switch (valor) {
                case "1": $("#frecuencia_dia").fadeIn(500); break;
                case "2": $("#frecuencia_semana").fadeIn(500); break;
                case "3": $("#frecuencia_mes").fadeIn(500); break;

                default: break;
            }
        });
        
        $("#frec_dia_semana_sel").on('change', function(){
            
            var valor = $('#frec_dia_semana_sel option:selected').val();
            frec_dia_semana_sel(valor);
        });
        
        $("#frec_dia_semana_sel_brm").on('change', function(){
            
            var valor = $('#frec_dia_semana_sel_brm option:selected').val();
            frec_dia_semana_sel_brm(valor);
        });
        
        $("#frec_mes_sel").on('change', function(){
            var valor = $('#frec_mes_sel option:selected').val();
            frec_mes_sel(valor);
        });
        
        // Cada vez que cambie el valor de las cajas, se actualiza el valor
        $('.suma_campos_dias input[type="number"]').on('keyup change', function(){
            suma_dias();
        });
        
        $('.suma_campos_semanas input[type="number"]').on('keyup change', function(){
            suma_semanas();
        });
        
        $('.suma_campos_meses input[type="number"]').on('keyup change', function(){
            suma_meses();
        });
        
        // Llamar a las funciones iniciales
        suma_dias();
        suma_semanas();
        suma_meses();
    });
    
    function frec_dia_semana_sel(valor)
    {
        $("#dia_semana1").show();
        $("#dia_semana2").show();
        $("#dia_semana3").show();
        $("#dia_semana4").show();

        switch (valor) {
            case "1": $("#dia_semana1").hide(); break;
            case "2": $("#dia_semana2").hide(); break;
            case "3": $("#dia_semana3").hide(); break;
            case "4": $("#dia_semana4").hide(); break;

            default: 
                
                $("#dia_semana1").hide();
                $("#dia_semana2").hide();
                $("#dia_semana3").hide();
                $("#dia_semana4").hide();
                
                break;
        }
    }
    
    function frec_dia_semana_sel_brm(valor)
    {
        switch (valor) {
            case "1": $("#brm2").html("Regular"); $("#brm3").html("Malo"); break;
            case "2": $("#brm2").html("Bueno"); $("#brm3").html("Malo"); break;
            case "3": $("#brm2").html("Bueno"); $("#brm3").html("Regular"); break;

            default: $("#brm2").html("Sin selección"); $("#brm3").html("Sin selección"); break;
        }
    }
    
    function frec_mes_sel(valor)
    {
        switch (valor) {
            case "1":

                $("#mes1").html("Enero");
                $("#mes2").html("Febrero");
                $("#mes3").html("Marzo");

                break;

            case "2":

                $("#mes1").html("Febrero");
                $("#mes2").html("Marzo");
                $("#mes3").html("Abril");

                break;

            case "3":

                $("#mes1").html("Marzo");
                $("#mes2").html("Abril");
                $("#mes3").html("Mayo");

                break;

            case "4":

                $("#mes1").html("Abril");
                $("#mes2").html("Mayo");
                $("#mes3").html("Junio");

                break;

            case "5":

                $("#mes1").html("Mayo");
                $("#mes2").html("Junio");
                $("#mes3").html("Julio");

                break;

            case "6":

                $("#mes1").html("Junio");
                $("#mes2").html("Julio");
                $("#mes3").html("Agosto");

                break;

            case "7":

                $("#mes1").html("Julio");
                $("#mes2").html("Agosto");
                $("#mes3").html("Septiembre");

                break;

            case "8":

                $("#mes1").html("Agosto");
                $("#mes2").html("Septiembre");
                $("#mes3").html("Octubre");

                break;

            case "9":

                $("#mes1").html("Septiembre");
                $("#mes2").html("Octubre");
                $("#mes3").html("Noviembre");

                break;

            case "10":

                $("#mes1").html("Octubre");
                $("#mes2").html("Noviembre");
                $("#mes3").html("Diciembre");
                
                break;

            case "11":

                $("#mes1").html("Noviembre");
                $("#mes2").html("Diciembre");
                $("#mes3").html("Enero");

                break;

            case "12":

                $("#mes1").html("Diciembre");
                $("#mes2").html("Enero");
                $("#mes3").html("Febrero");

                break;

            default:

                $("#mes1").html("Sin selección");
                $("#mes2").html("Sin selección");
                $("#mes3").html("Sin selección");

                break;
        }
    }
    
    function suma_dias()
    {
        var suma_total = 
        parseFloat($("#frec_dia_lunes").val() || 0) + 
        parseFloat($("#frec_dia_martes").val() || 0) + 
        parseFloat($("#frec_dia_miercoles").val() || 0) + 
        parseFloat($("#frec_dia_jueves").val() || 0) + 
        parseFloat($("#frec_dia_viernes").val() || 0) + 
        parseFloat($("#frec_dia_sabado").val() || 0) + 
        parseFloat($("#frec_dia_domingo").val() || 0);

        $("#suma_dias").html("Bs. " + suma_total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
    }
    
    function suma_semanas()
    {
        var suma_total = 
        parseFloat($("#frec_sem_semana1_monto").val() || 0) + 
        parseFloat($("#frec_sem_semana2_monto").val() || 0) + 
        parseFloat($("#frec_sem_semana3_monto").val() || 0) + 
        parseFloat($("#frec_sem_semana4_monto").val() || 0);

        $("#suma_semanas").html("Bs. " + suma_total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
    }
    
    function suma_meses()
    {
        var suma_total = 0;
        var mes1 = parseFloat($("#frec_mes_mes1_monto").val() || 0);
        var mes2 = parseFloat($("#frec_mes_mes2_monto").val() || 0);
        var mes3 = parseFloat($("#frec_mes_mes3_monto").val() || 0);
        
        var contador = 0;
        
        if(mes1 != 0) { contador++ } if(mes2 != 0) { contador++ } if(mes3 != 0) { contador++ }
        
        suma_total = mes1 + mes2 + mes3;
        
        if(contador == 0) {contador = 1;}

        $("#suma_meses").html("Bs. " + (suma_total/contador).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
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
    
    <nav class="navbar sticky-top <?php echo $clase_navbar_extra; ?>"> <?php echo $this->mfunciones_generales->getContenidoNavApp($estructura_id, $vista_actual); ?> </nav>

    <div class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">
    
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <div class='col-sm-12'style="text-align: center;"> <label class='label-campo' for=''> Seleccione la Frecuencia de Ventas: </label><br /><?php echo $arrCajasHTML['frec_seleccion']; ?></div>
                
        <div style="clear: both"></div>

        <br />
        
        <div id="frecuencia_dia">
        
            <div class="panel panel-default">
                <div class="panel-heading">Frecuencia de Ventas</div>
                <div class="panel-body">

                    <div class='col-sm-6'>

                        <div class="container suma_campos_dias" style="margin-top: 5px;">
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo texto-centrado' for=''>DÍA</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo' for='frec_dia_lunes'><?php echo $this->lang->line('frec_dia_lunes'); ?>:</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <?php echo $arrCajasHTML['frec_dia_lunes']; ?>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo' for='frec_dia_martes'><?php echo $this->lang->line('frec_dia_martes'); ?>:</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <?php echo $arrCajasHTML['frec_dia_martes']; ?>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo' for='frec_dia_miercoles'><?php echo $this->lang->line('frec_dia_miercoles'); ?>:</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <?php echo $arrCajasHTML['frec_dia_miercoles']; ?>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo' for='frec_dia_jueves'><?php echo $this->lang->line('frec_dia_jueves'); ?>:</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <?php echo $arrCajasHTML['frec_dia_jueves']; ?>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo' for='frec_dia_viernes'><?php echo $this->lang->line('frec_dia_viernes'); ?>:</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <?php echo $arrCajasHTML['frec_dia_viernes']; ?>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo' for='frec_dia_sabado'><?php echo $this->lang->line('frec_dia_sabado'); ?>:</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <?php echo $arrCajasHTML['frec_dia_sabado']; ?>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo' for='frec_dia_domingo'><?php echo $this->lang->line('frec_dia_domingo'); ?>:</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <?php echo $arrCajasHTML['frec_dia_domingo']; ?>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo texto-centrado' for=''>TOTAL</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <label class='label-campo' for='' id="suma_dias">  </label>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class='col-sm-6'><div class='form-group' style="text-align: center;">
                        <br /><br /><label class='label-campo' for=''>Esta semana se considera como: </label>
                        <br /><br /><?php echo $arrCajasHTML['frec_dia_semana_sel']; ?>
                        <br /><br /><?php echo $arrCajasHTML['frec_dia_semana_sel_brm']; ?>
                    </div></div>

                </div>
            </div>

            <div class='col-sm-6'>
            
                <div class="panel panel-default">
                    <div class="panel-heading">Completar que monto considera venta Buena Regular y Mala</div>
                    <div class="panel-body">

                        <div class='col-sm-6'>

                            <div class="container" style="margin-top: 5px;">
                                <div class="row">

                                    <div class="col" style="text-align: center;">
                                        <label class='label-campo texto-centrado' for=''>CRITERIO</label>
                                    </div>
                                    <div class="col" style="text-align: center;">
                                        <label class='label-campo texto-centrado' for=''>IMPORTE</label>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col" style="text-align: center;">
                                        <label class='label-campo texto-centrado' for='' id='brm2'></label>
                                    </div>
                                    <div class="col" style="text-align: center;">
                                        <?php echo $arrCajasHTML['frec_dia_semana_monto2']; ?>
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col" style="text-align: center;">
                                        <label class='label-campo texto-centrado' for='' id='brm3'></label>
                                    </div>
                                    <div class="col" style="text-align: center;">
                                        <?php echo $arrCajasHTML['frec_dia_semana_monto3']; ?>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
            
            <div class='col-sm-6'>
                
                <div class="panel panel-default">
                    <div class="panel-heading">Variación de las ventas de las otras semanas respecto a la semana de referencia según su categorización</div>
                    <div class="panel-body brm_semana" style="text-align: center;">

                        <div class='col-sm-12'><div class='form-group' id='dia_semana1'><label class='label-campo' for=''>PRIMERA:</label><br /><?php echo $arrCajasHTML['frec_dia_eval_semana1_brm']; ?></div></div>
                        <div class='col-sm-12'><div class='form-group' id='dia_semana2'><label class='label-campo' for=''>SEGUNDA:</label><br /><?php echo $arrCajasHTML['frec_dia_eval_semana2_brm']; ?></div></div>
                        <div class='col-sm-12'><div class='form-group' id='dia_semana3'><label class='label-campo' for=''>TERCERA:</label><br /><?php echo $arrCajasHTML['frec_dia_eval_semana3_brm']; ?></div></div>
                        <div class='col-sm-12'><div class='form-group' id='dia_semana4'><label class='label-campo' for=''>CUARTA:</label><br /><?php echo $arrCajasHTML['frec_dia_eval_semana4_brm']; ?></div></div>

                    </div>
                </div>
                
            </div>

        </div>
        
        <div id="frecuencia_semana">
        
            <div class="panel panel-default">
                <div class="panel-heading">Frecuencia semanal o Quincenal</div>
                <div class="panel-body">

                    <div class='col-sm-6'>

                        <div class="container suma_campos_semanas" style="margin-top: 5px;">
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo texto-centrado' for=''>SEMANA</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo' for='frec_sem_semana1_monto'><?php echo $this->lang->line('frec_sem_semana1_monto'); ?>:</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <?php echo $arrCajasHTML['frec_sem_semana1_monto']; ?>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo' for='frec_sem_semana2_monto'><?php echo $this->lang->line('frec_sem_semana2_monto'); ?>:</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <?php echo $arrCajasHTML['frec_sem_semana2_monto']; ?>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo' for='frec_sem_semana3_monto'><?php echo $this->lang->line('frec_sem_semana3_monto'); ?>:</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <?php echo $arrCajasHTML['frec_sem_semana3_monto']; ?>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo' for='frec_sem_semana4_monto'><?php echo $this->lang->line('frec_sem_semana4_monto'); ?>:</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <?php echo $arrCajasHTML['frec_sem_semana4_monto']; ?>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo texto-centrado' for=''>TOTAL</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <label class='label-campo' for='' id="suma_semanas">  </label>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>
            </div>
    
        </div>
        
        <div id="frecuencia_mes">
            
            <div class="panel panel-default">
                <div class="panel-heading">Frecuencia Mensual (Ultimos 3 Meses)</div>
                <div class="panel-body">

                    <div class='col-sm-6'><div class='form-group'><label class='label-campo' for=''>Seleccione el primer Mes:</label><?php echo $arrCajasHTML['frec_mes_sel']; ?></div></div>

                    <div class='col-sm-6'>

                        <div class="container suma_campos_meses" style="margin-top: 5px;">
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo texto-centrado' for=''>MES</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo texto-centrado' for='' id='mes1'></label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <?php echo $arrCajasHTML['frec_mes_mes1_monto']; ?>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo texto-centrado' for='' id='mes2'></label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <?php echo $arrCajasHTML['frec_mes_mes2_monto']; ?>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo texto-centrado' for='' id='mes3'></label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <?php echo $arrCajasHTML['frec_mes_mes3_monto']; ?>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo texto-centrado' for=''>PROMEDIO MENSUAL</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <label class='label-campo' for='' id="suma_meses">  </label>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
    
        </div>
            
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>