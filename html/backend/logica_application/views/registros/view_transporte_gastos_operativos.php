<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios('sin_combo');
    
?>
    
    $(document).ready(function(){ 
        $("div.modal-backdrop").remove();
        $('#gastos_operativos').find("input[type=text]").each(function(ev)
        {
            $(this).attr("placeholder", " :: Campo libre a utilizar ::");
        });
    });
    
    // Cada vez que cambie el valor de las cajas, se actualiza el valor
    $('.gastos_operativos input[type="number"]').on('keyup change', function(){
        gastos_operativos();
    });
    
    $(".gastos_operativos").on('change', function(){
        gastos_operativos();
    });

    // Llamar a las funciones iniciales
    gastos_operativos();
    
    function gastos_operativos()
    {
        var operativos_cambio_aceite_motor = 0;
        var operativos_cambio_aceite_caja = 0;
        var operativos_cambio_llanta_delanteras = 0;
        var operativos_cambio_llanta_traseras = 0;
        
        var operativos_cambio_bateria = 0;
        var operativos_cambio_balatas = 0;
        var operativos_revision_electrico = 0;
        var operativos_remachado_embrague = 0;
        var operativos_rectificacion_motor = 0;
        var operativos_cambio_rodamiento = 0;
        var operativos_reparaciones_menores = 0;
        var operativos_imprevistos = 0;
        var operativos_impuesto_propiedad = 0;
        var operativos_soat = 0;
        var operativos_roseta_inspeccion = 0;
        
        var operativos_otro_transporte_libre1 = 0;
        var operativos_otro_transporte_libre2 = 0;
        var operativos_otro_transporte_libre3 = 0;
        var operativos_otro_transporte_libre4 = 0;
        var operativos_otro_transporte_libre5 = 0;
        
        operativos_cambio_aceite_motor = parseFloat($("#operativos_cambio_aceite_motor_cantidad").val() || 0) * parseFloat($("#operativos_cambio_aceite_motor_monto").val() || 0) * (30/parseFloat($("#operativos_cambio_aceite_motor_frecuencia option:selected").val() || 0));
        operativos_cambio_aceite_caja = parseFloat($("#operativos_cambio_aceite_caja_cantidad").val() || 0) * parseFloat($("#operativos_cambio_aceite_caja_monto").val() || 0) * (30/parseFloat($("#operativos_cambio_aceite_caja_frecuencia option:selected").val() || 0));
        operativos_cambio_llanta_delanteras = parseFloat($("#operativos_cambio_llanta_delanteras_cantidad").val() || 0) * parseFloat($("#operativos_cambio_llanta_delanteras_monto").val() || 0) * (30/parseFloat($("#operativos_cambio_llanta_delanteras_frecuencia option:selected").val() || 0));
        operativos_cambio_llanta_traseras = parseFloat($("#operativos_cambio_llanta_traseras_cantidad").val() || 0) * parseFloat($("#operativos_cambio_llanta_traseras_monto").val() || 0) * (30/parseFloat($("#operativos_cambio_llanta_traseras_frecuencia option:selected").val() || 0));
        
        operativos_cambio_bateria = parseFloat($("#operativos_cambio_bateria_cantidad").val() || 0) * parseFloat($("#operativos_cambio_bateria_monto").val() || 0) * (30/parseFloat($("#operativos_cambio_bateria_frecuencia option:selected").val() || 0));
        operativos_cambio_balatas = parseFloat($("#operativos_cambio_balatas_cantidad").val() || 0) * parseFloat($("#operativos_cambio_balatas_monto").val() || 0) * (30/parseFloat($("#operativos_cambio_balatas_frecuencia option:selected").val() || 0));
        operativos_revision_electrico = parseFloat($("#operativos_revision_electrico_cantidad").val() || 0) * parseFloat($("#operativos_revision_electrico_monto").val() || 0) * (30/parseFloat($("#operativos_revision_electrico_frecuencia option:selected").val() || 0));
        operativos_remachado_embrague = parseFloat($("#operativos_remachado_embrague_cantidad").val() || 0) * parseFloat($("#operativos_remachado_embrague_monto").val() || 0) * (30/parseFloat($("#operativos_remachado_embrague_frecuencia option:selected").val() || 0));
        operativos_rectificacion_motor = parseFloat($("#operativos_rectificacion_motor_cantidad").val() || 0) * parseFloat($("#operativos_rectificacion_motor_monto").val() || 0) * (30/parseFloat($("#operativos_rectificacion_motor_frecuencia option:selected").val() || 0));
        operativos_cambio_rodamiento = parseFloat($("#operativos_cambio_rodamiento_cantidad").val() || 0) * parseFloat($("#operativos_cambio_rodamiento_monto").val() || 0) * (30/parseFloat($("#operativos_cambio_rodamiento_frecuencia option:selected").val() || 0));
        operativos_reparaciones_menores = parseFloat($("#operativos_reparaciones_menores_cantidad").val() || 0) * parseFloat($("#operativos_reparaciones_menores_monto").val() || 0) * (30/parseFloat($("#operativos_reparaciones_menores_frecuencia option:selected").val() || 0));
        operativos_imprevistos = parseFloat($("#operativos_imprevistos_cantidad").val() || 0) * parseFloat($("#operativos_imprevistos_monto").val() || 0) * (30/parseFloat($("#operativos_imprevistos_frecuencia option:selected").val() || 0));
        operativos_impuesto_propiedad = parseFloat($("#operativos_impuesto_propiedad_cantidad").val() || 0) * parseFloat($("#operativos_impuesto_propiedad_monto").val() || 0) * (30/parseFloat($("#operativos_impuesto_propiedad_frecuencia option:selected").val() || 0));
        operativos_soat = parseFloat($("#operativos_soat_cantidad").val() || 0) * parseFloat($("#operativos_soat_monto").val() || 0) * (30/parseFloat($("#operativos_soat_frecuencia option:selected").val() || 0));
        operativos_roseta_inspeccion = parseFloat($("#operativos_roseta_inspeccion_cantidad").val() || 0) * parseFloat($("#operativos_roseta_inspeccion_monto").val() || 0) * (30/parseFloat($("#operativos_roseta_inspeccion_frecuencia option:selected").val() || 0));

        operativos_otro_transporte_libre1 = parseFloat($("#operativos_otro_transporte_libre1_cantidad").val() || 0) * parseFloat($("#operativos_otro_transporte_libre1_monto").val() || 0) * (30/parseFloat($("#operativos_otro_transporte_libre1_frecuencia option:selected").val() || 0));
        operativos_otro_transporte_libre2 = parseFloat($("#operativos_otro_transporte_libre2_cantidad").val() || 0) * parseFloat($("#operativos_otro_transporte_libre2_monto").val() || 0) * (30/parseFloat($("#operativos_otro_transporte_libre2_frecuencia option:selected").val() || 0));
        operativos_otro_transporte_libre3 = parseFloat($("#operativos_otro_transporte_libre3_cantidad").val() || 0) * parseFloat($("#operativos_otro_transporte_libre3_monto").val() || 0) * (30/parseFloat($("#operativos_otro_transporte_libre3_frecuencia option:selected").val() || 0));
        operativos_otro_transporte_libre4 = parseFloat($("#operativos_otro_transporte_libre4_cantidad").val() || 0) * parseFloat($("#operativos_otro_transporte_libre4_monto").val() || 0) * (30/parseFloat($("#operativos_otro_transporte_libre4_frecuencia option:selected").val() || 0));
        operativos_otro_transporte_libre5 = parseFloat($("#operativos_otro_transporte_libre5_cantidad").val() || 0) * parseFloat($("#operativos_otro_transporte_libre5_monto").val() || 0) * (30/parseFloat($("#operativos_otro_transporte_libre5_frecuencia option:selected").val() || 0));
        
        var suma_total = 
        
        operativos_cambio_aceite_motor + 
        operativos_cambio_aceite_caja + 
        operativos_cambio_llanta_delanteras + 
        operativos_cambio_llanta_traseras + 
        operativos_cambio_bateria + 
        operativos_cambio_balatas + 
        operativos_revision_electrico + 
        operativos_remachado_embrague + 
        operativos_rectificacion_motor + 
        operativos_cambio_rodamiento + 
        operativos_reparaciones_menores + 
        operativos_imprevistos + 
        operativos_impuesto_propiedad + 
        operativos_soat + 
        operativos_roseta_inspeccion + 
        operativos_otro_transporte_libre1 + 
        operativos_otro_transporte_libre2 + 
        operativos_otro_transporte_libre3 + 
        operativos_otro_transporte_libre4 + 
        operativos_otro_transporte_libre5;

        $("#gasto_operativos").html("Bs. " + suma_total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
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

    <div id="gastos_operativos" class="contenido_formulario container gastos_operativos <?php echo $clase_contenido_extra; ?>">
    
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <div class='col-sm-12'style="text-align: center;"> <label class='label-campo texto-centrado panel-heading' for=''>Registre los Gastos Operativos</label></div>
        
        <div style="clear: both"></div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado' for='operativos_cambio_aceite_motor'><?php echo $this->lang->line('operativos_cambio_aceite_motor'); ?></label>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_aceite_motor_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_aceite_motor_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_aceite_motor_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_aceite_motor_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_aceite_motor_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_aceite_motor_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado' for='operativos_cambio_aceite_caja'><?php echo $this->lang->line('operativos_cambio_aceite_caja'); ?></label>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_aceite_caja_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_aceite_caja_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_aceite_caja_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_aceite_caja_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_aceite_caja_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_aceite_caja_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado' for='operativos_cambio_llanta_delanteras'><?php echo $this->lang->line('operativos_cambio_llanta_delanteras'); ?></label>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_llanta_delanteras_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_llanta_delanteras_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_llanta_delanteras_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_llanta_delanteras_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_llanta_delanteras_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_llanta_delanteras_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado' for='operativos_cambio_llanta_traseras'><?php echo $this->lang->line('operativos_cambio_llanta_traseras'); ?></label>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_llanta_traseras_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_llanta_traseras_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_llanta_traseras_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_llanta_traseras_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_llanta_traseras_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_llanta_traseras_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado' for='operativos_cambio_bateria'><?php echo $this->lang->line('operativos_cambio_bateria'); ?></label>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_bateria_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_bateria_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_bateria_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_bateria_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_bateria_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_bateria_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado' for='operativos_cambio_balatas'><?php echo $this->lang->line('operativos_cambio_balatas'); ?></label>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_balatas_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_balatas_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_balatas_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_balatas_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_balatas_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_balatas_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado' for='operativos_revision_electrico'><?php echo $this->lang->line('operativos_revision_electrico'); ?></label>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_revision_electrico_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_revision_electrico_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_revision_electrico_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_revision_electrico_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_revision_electrico_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_revision_electrico_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado' for='operativos_remachado_embrague'><?php echo $this->lang->line('operativos_remachado_embrague'); ?></label>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_remachado_embrague_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_remachado_embrague_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_remachado_embrague_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_remachado_embrague_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_remachado_embrague_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_remachado_embrague_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado' for='operativos_rectificacion_motor'><?php echo $this->lang->line('operativos_rectificacion_motor'); ?></label>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_rectificacion_motor_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_rectificacion_motor_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_rectificacion_motor_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_rectificacion_motor_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_rectificacion_motor_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_rectificacion_motor_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado' for='operativos_cambio_rodamiento'><?php echo $this->lang->line('operativos_cambio_rodamiento'); ?></label>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_rodamiento_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_rodamiento_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_rodamiento_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_rodamiento_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_cambio_rodamiento_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_cambio_rodamiento_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado' for='operativos_reparaciones_menores'><?php echo $this->lang->line('operativos_reparaciones_menores'); ?></label>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_reparaciones_menores_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_reparaciones_menores_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_reparaciones_menores_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_reparaciones_menores_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_reparaciones_menores_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_reparaciones_menores_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado' for='operativos_imprevistos'><?php echo $this->lang->line('operativos_imprevistos'); ?></label>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_imprevistos_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_imprevistos_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_imprevistos_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_imprevistos_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_imprevistos_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_imprevistos_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado' for='operativos_impuesto_propiedad'><?php echo $this->lang->line('operativos_impuesto_propiedad'); ?></label>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_impuesto_propiedad_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_impuesto_propiedad_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_impuesto_propiedad_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_impuesto_propiedad_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_impuesto_propiedad_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_impuesto_propiedad_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado' for='operativos_soat'><?php echo $this->lang->line('operativos_soat'); ?></label>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_soat_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_soat_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_soat_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_soat_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_soat_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_soat_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado' for='operativos_roseta_inspeccion'><?php echo $this->lang->line('operativos_roseta_inspeccion'); ?></label>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_roseta_inspeccion_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_roseta_inspeccion_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_roseta_inspeccion_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_roseta_inspeccion_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_roseta_inspeccion_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_roseta_inspeccion_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre1_texto']; ?>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_otro_transporte_libre1_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre1_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_otro_transporte_libre1_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre1_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_otro_transporte_libre1_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre1_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre2_texto']; ?>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_otro_transporte_libre2_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre2_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_otro_transporte_libre2_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre2_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_otro_transporte_libre2_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre2_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre3_texto']; ?>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_otro_transporte_libre3_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre3_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_otro_transporte_libre3_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre3_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_otro_transporte_libre3_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre3_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre4_texto']; ?>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_otro_transporte_libre4_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre4_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_otro_transporte_libre4_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre4_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_otro_transporte_libre4_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre4_monto']; ?>
                </div>
            </div>
        </div>
        
        <div class='col-sm-4' style="text-align: center;"> <br /><br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre5_texto']; ?>
                </div>
            </div>
            <div class="row"> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>CRITERIO</label> </div> <div class="col" style="text-align: center;"> <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label> </div> </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_otro_transporte_libre5_frecuencia'><?php echo $this->lang->line('operativo_frecuencia'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre5_frecuencia']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_otro_transporte_libre5_cantidad'><?php echo $this->lang->line('operativo_cantidad'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre5_cantidad']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='operativos_otro_transporte_libre5_monto'><?php echo $this->lang->line('operativo_monto'); ?></label>
                </div>
                <div class="col" style="text-align: center;">
                    <?php echo $arrCajasHTML['operativos_otro_transporte_libre5_monto']; ?>
                </div>
            </div>
        </div>
        
        <div style="clear: both"></div>
        <br />
        <div class="container">
            
            <div class="row">

                <div class="col" style="text-align: right;">
                    <label class='label-campo texto-centrado panel-heading' for=''>TOTAL GASTOS OPERATIVOS</label>
                </div>
                <div class="col" style="text-align: left;">
                    <label class='label-campo texto-centrado panel-heading' for='' id="gasto_operativos"></label>
                </div>

            </div>
        </div>
        
    </div>
        
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>