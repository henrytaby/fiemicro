<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios();
    
?>
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
         
        <div class="panel panel-default">
            <div class="panel-heading">Datos generales del solicitante</div>
            <div class="panel-body">
                
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='monto_manual'><?php echo $this->lang->line('monto_manual'); ?>:</label><?php echo $arrCajasHTML['monto_manual']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='general_solicitante'><?php echo $this->lang->line('general_solicitante'); ?>:</label><?php echo $arrCajasHTML['general_solicitante']; ?></div></div>
                
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='general_ci'><?php echo $this->lang->line('general_ci'); ?>:</label><?php echo $arrCajasHTML['general_ci']; ?> <br /><br /> <?php echo $arrCajasHTML['general_ci_extension']; ?></div></div>
                
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='general_telefono'><?php echo $this->lang->line('general_telefono'); ?>:</label><?php echo $arrCajasHTML['general_telefono']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='general_email'><?php echo $this->lang->line('general_email'); ?>:</label><?php echo $arrCajasHTML['general_email']; ?></div></div>
                
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='general_actividad'><?php echo $this->lang->line('general_actividad'); ?>:</label><?php echo $arrCajasHTML['general_actividad']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='general_destino'><?php echo $this->lang->line('general_destino'); ?>:</label><?php echo $arrCajasHTML['general_destino']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operacion_antiguedad'><?php echo $this->lang->line('operacion_antiguedad'); ?>:</label><?php echo $arrCajasHTML['operacion_antiguedad']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operacion_tiempo'><?php echo $this->lang->line('operacion_tiempo'); ?>:</label><?php echo $arrCajasHTML['operacion_tiempo']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operacion_efectivo'><?php echo $this->lang->line('operacion_efectivo'); ?>:</label><?php echo $arrCajasHTML['operacion_efectivo']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operacion_dias'><?php echo $this->lang->line('operacion_dias'); ?>:</label><?php echo $arrCajasHTML['operacion_dias']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='aclarar_contado'><?php echo $this->lang->line('aclarar_contado'); ?>:</label><?php echo $arrCajasHTML['aclarar_contado']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='aclarar_credito'><?php echo $this->lang->line('aclarar_credito'); ?>:</label><?php echo $arrCajasHTML['aclarar_credito']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_dia_lunes'><?php echo $this->lang->line('frec_dia_lunes'); ?>:</label><?php echo $arrCajasHTML['frec_dia_lunes']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_dia_martes'><?php echo $this->lang->line('frec_dia_martes'); ?>:</label><?php echo $arrCajasHTML['frec_dia_martes']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_dia_miercoles'><?php echo $this->lang->line('frec_dia_miercoles'); ?>:</label><?php echo $arrCajasHTML['frec_dia_miercoles']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_dia_jueves'><?php echo $this->lang->line('frec_dia_jueves'); ?>:</label><?php echo $arrCajasHTML['frec_dia_jueves']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_dia_viernes'><?php echo $this->lang->line('frec_dia_viernes'); ?>:</label><?php echo $arrCajasHTML['frec_dia_viernes']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_dia_sabado'><?php echo $this->lang->line('frec_dia_sabado'); ?>:</label><?php echo $arrCajasHTML['frec_dia_sabado']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_dia_domingo'><?php echo $this->lang->line('frec_dia_domingo'); ?>:</label><?php echo $arrCajasHTML['frec_dia_domingo']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_dia_monto_bueno'><?php echo $this->lang->line('frec_dia_monto_bueno'); ?>:</label><?php echo $arrCajasHTML['frec_dia_monto_bueno']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_dia_monto_regular'><?php echo $this->lang->line('frec_dia_monto_regular'); ?>:</label><?php echo $arrCajasHTML['frec_dia_monto_regular']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_dia_monto_malo'><?php echo $this->lang->line('frec_dia_monto_malo'); ?>:</label><?php echo $arrCajasHTML['frec_dia_monto_malo']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_dia_eval_semana1_monto'><?php echo $this->lang->line('frec_dia_eval_semana1_monto'); ?>:</label><?php echo $arrCajasHTML['frec_dia_eval_semana1_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_dia_eval_semana2_monto'><?php echo $this->lang->line('frec_dia_eval_semana2_monto'); ?>:</label><?php echo $arrCajasHTML['frec_dia_eval_semana2_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_dia_eval_semana3_monto'><?php echo $this->lang->line('frec_dia_eval_semana3_monto'); ?>:</label><?php echo $arrCajasHTML['frec_dia_eval_semana3_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_dia_eval_semana4_monto'><?php echo $this->lang->line('frec_dia_eval_semana4_monto'); ?>:</label><?php echo $arrCajasHTML['frec_dia_eval_semana4_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_sem_semana1_monto'><?php echo $this->lang->line('frec_sem_semana1_monto'); ?>:</label><?php echo $arrCajasHTML['frec_sem_semana1_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_sem_semana2_monto'><?php echo $this->lang->line('frec_sem_semana2_monto'); ?>:</label><?php echo $arrCajasHTML['frec_sem_semana2_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_sem_semana3_monto'><?php echo $this->lang->line('frec_sem_semana3_monto'); ?>:</label><?php echo $arrCajasHTML['frec_sem_semana3_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_sem_semana4_monto'><?php echo $this->lang->line('frec_sem_semana4_monto'); ?>:</label><?php echo $arrCajasHTML['frec_sem_semana4_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_mes_mes1'><?php echo $this->lang->line('frec_mes_mes1'); ?>:</label><?php echo $arrCajasHTML['frec_mes_mes1']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_mes_mes1_monto'><?php echo $this->lang->line('frec_mes_mes1_monto'); ?>:</label><?php echo $arrCajasHTML['frec_mes_mes1_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_mes_mes2'><?php echo $this->lang->line('frec_mes_mes2'); ?>:</label><?php echo $arrCajasHTML['frec_mes_mes2']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_mes_mes2_monto'><?php echo $this->lang->line('frec_mes_mes2_monto'); ?>:</label><?php echo $arrCajasHTML['frec_mes_mes2_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_mes_mes3'><?php echo $this->lang->line('frec_mes_mes3'); ?>:</label><?php echo $arrCajasHTML['frec_mes_mes3']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='frec_mes_mes3_monto'><?php echo $this->lang->line('frec_mes_mes3_monto'); ?>:</label><?php echo $arrCajasHTML['frec_mes_mes3_monto']; ?></div></div>

                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='margen_utilidad_productos'><?php echo $this->lang->line('margen_utilidad_productos'); ?>:</label><?php echo $arrCajasHTML['margen_utilidad_productos']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='porcentaje_participacion_proveedores'><?php echo $this->lang->line('porcentaje_participacion_proveedores'); ?>:</label><?php echo $arrCajasHTML['porcentaje_participacion_proveedores']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='estacion_alta_monto'><?php echo $this->lang->line('estacion_alta_monto'); ?>:</label><?php echo $arrCajasHTML['estacion_alta_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='estacion_regular_monto'><?php echo $this->lang->line('estacion_regular_monto'); ?>:</label><?php echo $arrCajasHTML['estacion_regular_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='estacion_bajo_monto'><?php echo $this->lang->line('estacion_bajo_monto'); ?>:</label><?php echo $arrCajasHTML['estacion_bajo_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_alq_energia_monto'><?php echo $this->lang->line('operativos_alq_energia_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_alq_energia_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_alq_energia_aclaracion'><?php echo $this->lang->line('operativos_alq_energia_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_alq_energia_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_alq_agua_monto'><?php echo $this->lang->line('operativos_alq_agua_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_alq_agua_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_alq_agua_aclaracion'><?php echo $this->lang->line('operativos_alq_agua_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_alq_agua_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_alq_internet_monto'><?php echo $this->lang->line('operativos_alq_internet_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_alq_internet_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_alq_internet_aclaracion'><?php echo $this->lang->line('operativos_alq_internet_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_alq_internet_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_alq_combustible_monto'><?php echo $this->lang->line('operativos_alq_combustible_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_alq_combustible_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_alq_combustible_aclaracion'><?php echo $this->lang->line('operativos_alq_combustible_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_alq_combustible_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_alq_libre1_texto'><?php echo $this->lang->line('operativos_alq_libre1_texto'); ?>:</label><?php echo $arrCajasHTML['operativos_alq_libre1_texto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_alq_libre1_monto'><?php echo $this->lang->line('operativos_alq_libre1_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_alq_libre1_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_alq_libre1_aclaracion'><?php echo $this->lang->line('operativos_alq_libre1_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_alq_libre1_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_alq_libre2_texto'><?php echo $this->lang->line('operativos_alq_libre2_texto'); ?>:</label><?php echo $arrCajasHTML['operativos_alq_libre2_texto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_alq_libre2_monto'><?php echo $this->lang->line('operativos_alq_libre2_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_alq_libre2_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_alq_libre2_aclaracion'><?php echo $this->lang->line('operativos_alq_libre2_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_alq_libre2_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_sal_aguinaldos_monto'><?php echo $this->lang->line('operativos_sal_aguinaldos_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_sal_aguinaldos_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_sal_aguinaldos_aclaracion'><?php echo $this->lang->line('operativos_sal_aguinaldos_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_sal_aguinaldos_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_sal_libre1_texto'><?php echo $this->lang->line('operativos_sal_libre1_texto'); ?>:</label><?php echo $arrCajasHTML['operativos_sal_libre1_texto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_sal_libre1_monto'><?php echo $this->lang->line('operativos_sal_libre1_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_sal_libre1_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_sal_libre1_aclaracion'><?php echo $this->lang->line('operativos_sal_libre1_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_sal_libre1_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_sal_libre2_texto'><?php echo $this->lang->line('operativos_sal_libre2_texto'); ?>:</label><?php echo $arrCajasHTML['operativos_sal_libre2_texto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_sal_libre2_monto'><?php echo $this->lang->line('operativos_sal_libre2_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_sal_libre2_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_sal_libre2_aclaracion'><?php echo $this->lang->line('operativos_sal_libre2_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_sal_libre2_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_sal_libre3_texto'><?php echo $this->lang->line('operativos_sal_libre3_texto'); ?>:</label><?php echo $arrCajasHTML['operativos_sal_libre3_texto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_sal_libre3_monto'><?php echo $this->lang->line('operativos_sal_libre3_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_sal_libre3_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_sal_libre3_aclaracion'><?php echo $this->lang->line('operativos_sal_libre3_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_sal_libre3_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_sal_libre4_texto'><?php echo $this->lang->line('operativos_sal_libre4_texto'); ?>:</label><?php echo $arrCajasHTML['operativos_sal_libre4_texto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_sal_libre4_monto'><?php echo $this->lang->line('operativos_sal_libre4_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_sal_libre4_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_sal_libre4_aclaracion'><?php echo $this->lang->line('operativos_sal_libre4_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_sal_libre4_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_transporte_monto'><?php echo $this->lang->line('operativos_otro_transporte_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_transporte_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_transporte_aclaracion'><?php echo $this->lang->line('operativos_otro_transporte_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_transporte_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_licencias_monto'><?php echo $this->lang->line('operativos_otro_licencias_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_licencias_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_licencias_aclaracion'><?php echo $this->lang->line('operativos_otro_licencias_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_licencias_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_alimentacion_monto'><?php echo $this->lang->line('operativos_otro_alimentacion_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_alimentacion_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_alimentacion_aclaracion'><?php echo $this->lang->line('operativos_otro_alimentacion_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_alimentacion_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_mant_vehiculo_monto'><?php echo $this->lang->line('operativos_otro_mant_vehiculo_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_mant_vehiculo_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_mant_vehiculo_aclaracion'><?php echo $this->lang->line('operativos_otro_mant_vehiculo_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_mant_vehiculo_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_mant_maquina_monto'><?php echo $this->lang->line('operativos_otro_mant_maquina_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_mant_maquina_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_mant_maquina_aclaracion'><?php echo $this->lang->line('operativos_otro_mant_maquina_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_mant_maquina_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_imprevistos_monto'><?php echo $this->lang->line('operativos_otro_imprevistos_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_imprevistos_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_imprevistos_aclaracion'><?php echo $this->lang->line('operativos_otro_imprevistos_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_imprevistos_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_otros_monto'><?php echo $this->lang->line('operativos_otro_otros_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_otros_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_otros_aclaracion'><?php echo $this->lang->line('operativos_otro_otros_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_otros_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_libre1_texto'><?php echo $this->lang->line('operativos_otro_libre1_texto'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_libre1_texto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_libre1_monto'><?php echo $this->lang->line('operativos_otro_libre1_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_libre1_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_libre1_aclaracion'><?php echo $this->lang->line('operativos_otro_libre1_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_libre1_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_libre2_texto'><?php echo $this->lang->line('operativos_otro_libre2_texto'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_libre2_texto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_libre2_monto'><?php echo $this->lang->line('operativos_otro_libre2_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_libre2_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_libre2_aclaracion'><?php echo $this->lang->line('operativos_otro_libre2_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_libre2_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_libre3_texto'><?php echo $this->lang->line('operativos_otro_libre3_texto'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_libre3_texto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_libre3_monto'><?php echo $this->lang->line('operativos_otro_libre3_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_libre3_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_libre3_aclaracion'><?php echo $this->lang->line('operativos_otro_libre3_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_libre3_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_libre4_texto'><?php echo $this->lang->line('operativos_otro_libre4_texto'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_libre4_texto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_libre4_monto'><?php echo $this->lang->line('operativos_otro_libre4_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_libre4_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_libre4_aclaracion'><?php echo $this->lang->line('operativos_otro_libre4_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_libre4_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_libre5_texto'><?php echo $this->lang->line('operativos_otro_libre5_texto'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_libre5_texto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_libre5_monto'><?php echo $this->lang->line('operativos_otro_libre5_monto'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_libre5_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operativos_otro_libre5_aclaracion'><?php echo $this->lang->line('operativos_otro_libre5_aclaracion'); ?>:</label><?php echo $arrCajasHTML['operativos_otro_libre5_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_dependientes_ingreso'><?php echo $this->lang->line('familiar_dependientes_ingreso'); ?>:</label><?php echo $arrCajasHTML['familiar_dependientes_ingreso']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_edad_hijos'><?php echo $this->lang->line('familiar_edad_hijos'); ?>:</label><?php echo $arrCajasHTML['familiar_edad_hijos']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_alimentacion_monto'><?php echo $this->lang->line('familiar_alimentacion_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_alimentacion_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_alimentacion_aclaracion'><?php echo $this->lang->line('familiar_alimentacion_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_alimentacion_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_energia_monto'><?php echo $this->lang->line('familiar_energia_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_energia_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_energia_aclaracion'><?php echo $this->lang->line('familiar_energia_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_energia_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_agua_monto'><?php echo $this->lang->line('familiar_agua_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_agua_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_agua_aclaracion'><?php echo $this->lang->line('familiar_agua_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_agua_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_gas_monto'><?php echo $this->lang->line('familiar_gas_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_gas_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_gas_aclaracion'><?php echo $this->lang->line('familiar_gas_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_gas_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_telefono_monto'><?php echo $this->lang->line('familiar_telefono_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_telefono_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_telefono_aclaracion'><?php echo $this->lang->line('familiar_telefono_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_telefono_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_celular_monto'><?php echo $this->lang->line('familiar_celular_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_celular_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_celular_aclaracion'><?php echo $this->lang->line('familiar_celular_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_celular_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_internet_monto'><?php echo $this->lang->line('familiar_internet_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_internet_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_internet_aclaracion'><?php echo $this->lang->line('familiar_internet_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_internet_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_tv_monto'><?php echo $this->lang->line('familiar_tv_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_tv_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_tv_aclaracion'><?php echo $this->lang->line('familiar_tv_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_tv_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_impuestos_monto'><?php echo $this->lang->line('familiar_impuestos_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_impuestos_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_impuestos_aclaracion'><?php echo $this->lang->line('familiar_impuestos_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_impuestos_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_alquileres_monto'><?php echo $this->lang->line('familiar_alquileres_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_alquileres_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_alquileres_aclaracion'><?php echo $this->lang->line('familiar_alquileres_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_alquileres_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_educacion_monto'><?php echo $this->lang->line('familiar_educacion_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_educacion_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_educacion_aclaracion'><?php echo $this->lang->line('familiar_educacion_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_educacion_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_transporte_monto'><?php echo $this->lang->line('familiar_transporte_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_transporte_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_transporte_aclaracion'><?php echo $this->lang->line('familiar_transporte_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_transporte_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_salud_monto'><?php echo $this->lang->line('familiar_salud_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_salud_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_salud_aclaracion'><?php echo $this->lang->line('familiar_salud_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_salud_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_empleada_monto'><?php echo $this->lang->line('familiar_empleada_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_empleada_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_empleada_aclaracion'><?php echo $this->lang->line('familiar_empleada_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_empleada_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_diversion_monto'><?php echo $this->lang->line('familiar_diversion_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_diversion_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_diversion_aclaracion'><?php echo $this->lang->line('familiar_diversion_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_diversion_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_vestimenta_monto'><?php echo $this->lang->line('familiar_vestimenta_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_vestimenta_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_vestimenta_aclaracion'><?php echo $this->lang->line('familiar_vestimenta_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_vestimenta_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_otros_monto'><?php echo $this->lang->line('familiar_otros_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_otros_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_otros_aclaracion'><?php echo $this->lang->line('familiar_otros_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_otros_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_libre1_texto'><?php echo $this->lang->line('familiar_libre1_texto'); ?>:</label><?php echo $arrCajasHTML['familiar_libre1_texto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_libre1_monto'><?php echo $this->lang->line('familiar_libre1_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_libre1_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_libre1_aclaracion'><?php echo $this->lang->line('familiar_libre1_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_libre1_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_libre2_texto'><?php echo $this->lang->line('familiar_libre2_texto'); ?>:</label><?php echo $arrCajasHTML['familiar_libre2_texto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_libre2_monto'><?php echo $this->lang->line('familiar_libre2_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_libre2_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_libre2_aclaracion'><?php echo $this->lang->line('familiar_libre2_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_libre2_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_libre3_texto'><?php echo $this->lang->line('familiar_libre3_texto'); ?>:</label><?php echo $arrCajasHTML['familiar_libre3_texto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_libre3_monto'><?php echo $this->lang->line('familiar_libre3_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_libre3_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_libre3_aclaracion'><?php echo $this->lang->line('familiar_libre3_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_libre3_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_libre4_texto'><?php echo $this->lang->line('familiar_libre4_texto'); ?>:</label><?php echo $arrCajasHTML['familiar_libre4_texto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_libre4_monto'><?php echo $this->lang->line('familiar_libre4_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_libre4_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_libre4_aclaracion'><?php echo $this->lang->line('familiar_libre4_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_libre4_aclaracion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_libre5_texto'><?php echo $this->lang->line('familiar_libre5_texto'); ?>:</label><?php echo $arrCajasHTML['familiar_libre5_texto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_libre5_monto'><?php echo $this->lang->line('familiar_libre5_monto'); ?>:</label><?php echo $arrCajasHTML['familiar_libre5_monto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='familiar_libre5_aclaracion'><?php echo $this->lang->line('familiar_libre5_aclaracion'); ?>:</label><?php echo $arrCajasHTML['familiar_libre5_aclaracion']; ?></div></div>

                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_amortizacion_otras_deudas'><?php echo $this->lang->line('extra_amortizacion_otras_deudas'); ?>:</label><?php echo $arrCajasHTML['extra_amortizacion_otras_deudas']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_cuota_maxima_credito'><?php echo $this->lang->line('extra_cuota_maxima_credito'); ?>:</label><?php echo $arrCajasHTML['extra_cuota_maxima_credito']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_amortizacion_credito'><?php echo $this->lang->line('extra_amortizacion_credito'); ?>:</label><?php echo $arrCajasHTML['extra_amortizacion_credito']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_efectivo_caja'><?php echo $this->lang->line('extra_efectivo_caja'); ?>:</label><?php echo $arrCajasHTML['extra_efectivo_caja']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_ahorro_dpf'><?php echo $this->lang->line('extra_ahorro_dpf'); ?>:</label><?php echo $arrCajasHTML['extra_ahorro_dpf']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_cuentas_cobrar'><?php echo $this->lang->line('extra_cuentas_cobrar'); ?>:</label><?php echo $arrCajasHTML['extra_cuentas_cobrar']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_inventario'><?php echo $this->lang->line('extra_inventario'); ?>:</label><?php echo $arrCajasHTML['extra_inventario']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_otros_activos_corrientes'><?php echo $this->lang->line('extra_otros_activos_corrientes'); ?>:</label><?php echo $arrCajasHTML['extra_otros_activos_corrientes']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_activo_fijo'><?php echo $this->lang->line('extra_activo_fijo'); ?>:</label><?php echo $arrCajasHTML['extra_activo_fijo']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_otros_activos_nocorrientes'><?php echo $this->lang->line('extra_otros_activos_nocorrientes'); ?>:</label><?php echo $arrCajasHTML['extra_otros_activos_nocorrientes']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_activos_actividades_secundarias'><?php echo $this->lang->line('extra_activos_actividades_secundarias'); ?>:</label><?php echo $arrCajasHTML['extra_activos_actividades_secundarias']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_inmuebles_terrenos'><?php echo $this->lang->line('extra_inmuebles_terrenos'); ?>:</label><?php echo $arrCajasHTML['extra_inmuebles_terrenos']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_bienes_hogar'><?php echo $this->lang->line('extra_bienes_hogar'); ?>:</label><?php echo $arrCajasHTML['extra_bienes_hogar']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_otros_activos_familiares'><?php echo $this->lang->line('extra_otros_activos_familiares'); ?>:</label><?php echo $arrCajasHTML['extra_otros_activos_familiares']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_endeudamiento_credito'><?php echo $this->lang->line('extra_endeudamiento_credito'); ?>:</label><?php echo $arrCajasHTML['extra_endeudamiento_credito']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_personal_ocupado'><?php echo $this->lang->line('extra_personal_ocupado'); ?>:</label><?php echo $arrCajasHTML['extra_personal_ocupado']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_cuentas_pagar_proveedores'><?php echo $this->lang->line('extra_cuentas_pagar_proveedores'); ?>:</label><?php echo $arrCajasHTML['extra_cuentas_pagar_proveedores']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_prestamos_financieras_corto'><?php echo $this->lang->line('extra_prestamos_financieras_corto'); ?>:</label><?php echo $arrCajasHTML['extra_prestamos_financieras_corto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_cuentas_pagar_corto'><?php echo $this->lang->line('extra_cuentas_pagar_corto'); ?>:</label><?php echo $arrCajasHTML['extra_cuentas_pagar_corto']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_prestamos_financieras_largo'><?php echo $this->lang->line('extra_prestamos_financieras_largo'); ?>:</label><?php echo $arrCajasHTML['extra_prestamos_financieras_largo']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_cuentas_pagar_largo'><?php echo $this->lang->line('extra_cuentas_pagar_largo'); ?>:</label><?php echo $arrCajasHTML['extra_cuentas_pagar_largo']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_pasivo_actividades_secundarias'><?php echo $this->lang->line('extra_pasivo_actividades_secundarias'); ?>:</label><?php echo $arrCajasHTML['extra_pasivo_actividades_secundarias']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='extra_pasivo_familiar'><?php echo $this->lang->line('extra_pasivo_familiar'); ?>:</label><?php echo $arrCajasHTML['extra_pasivo_familiar']; ?></div></div>

                
                <?php // MATERIA PRIMA ?>

                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='materia_nombre'><?php echo $this->lang->line('materia_nombre'); ?>:</label><?php echo $arrCajasHTML['materia_nombre']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='materia_frecuencia'><?php echo $this->lang->line('materia_frecuencia'); ?>:</label><?php echo $arrCajasHTML['materia_frecuencia']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='materia_unidad_compra'><?php echo $this->lang->line('materia_unidad_compra'); ?>:</label><?php echo $arrCajasHTML['materia_unidad_compra']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='materia_unidad_compra_cantidad'><?php echo $this->lang->line('materia_unidad_compra_cantidad'); ?>:</label><?php echo $arrCajasHTML['materia_unidad_compra_cantidad']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='materia_unidad_uso'><?php echo $this->lang->line('materia_unidad_uso'); ?>:</label><?php echo $arrCajasHTML['materia_unidad_uso']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='materia_unidad_uso_cantidad'><?php echo $this->lang->line('materia_unidad_uso_cantidad'); ?>:</label><?php echo $arrCajasHTML['materia_unidad_uso_cantidad']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='materia_unidad_proceso'><?php echo $this->lang->line('materia_unidad_proceso'); ?>:</label><?php echo $arrCajasHTML['materia_unidad_proceso']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='materia_producto_medida'><?php echo $this->lang->line('materia_producto_medida'); ?>:</label><?php echo $arrCajasHTML['materia_producto_medida']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='materia_producto_medida_cantidad'><?php echo $this->lang->line('materia_producto_medida_cantidad'); ?>:</label><?php echo $arrCajasHTML['materia_producto_medida_cantidad']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='materia_precio_unitario'><?php echo $this->lang->line('materia_precio_unitario'); ?>:</label><?php echo $arrCajasHTML['materia_precio_unitario']; ?></div></div>

                <?php // PROVEEDORES ?>

                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='proveedor_nombre'><?php echo $this->lang->line('proveedor_nombre'); ?>:</label><?php echo $arrCajasHTML['proveedor_nombre']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='proveedor_lugar_compra'><?php echo $this->lang->line('proveedor_lugar_compra'); ?>:</label><?php echo $arrCajasHTML['proveedor_lugar_compra']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='proveedor_frecuencia_dias'><?php echo $this->lang->line('proveedor_frecuencia_dias'); ?>:</label><?php echo $arrCajasHTML['proveedor_frecuencia_dias']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='proveedor_importe'><?php echo $this->lang->line('proveedor_importe'); ?>:</label><?php echo $arrCajasHTML['proveedor_importe']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='proveedor_fecha_ultima'><?php echo $this->lang->line('proveedor_fecha_ultima'); ?>:</label><?php echo $arrCajasHTML['proveedor_fecha_ultima']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='proveedor_aclaracion'><?php echo $this->lang->line('proveedor_aclaracion'); ?>:</label><?php echo $arrCajasHTML['proveedor_aclaracion']; ?></div></div>

                <?php // INVENTARIO ?>

                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='inventario_descripcion'><?php echo $this->lang->line('inventario_descripcion'); ?>:</label><?php echo $arrCajasHTML['inventario_descripcion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='inventario_frecuencia'><?php echo $this->lang->line('inventario_frecuencia'); ?>:</label><?php echo $arrCajasHTML['inventario_frecuencia']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='inventario_compra_cantidad'><?php echo $this->lang->line('inventario_compra_cantidad'); ?>:</label><?php echo $arrCajasHTML['inventario_compra_cantidad']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='inventario_compra_medida'><?php echo $this->lang->line('inventario_compra_medida'); ?>:</label><?php echo $arrCajasHTML['inventario_compra_medida']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='inventario_venta_cantidad'><?php echo $this->lang->line('inventario_venta_cantidad'); ?>:</label><?php echo $arrCajasHTML['inventario_venta_cantidad']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='inventario_venta_medida'><?php echo $this->lang->line('inventario_venta_medida'); ?>:</label><?php echo $arrCajasHTML['inventario_venta_medida']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='inventario_compra_precio'><?php echo $this->lang->line('inventario_compra_precio'); ?>:</label><?php echo $arrCajasHTML['inventario_compra_precio']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='inventario_venta_precio'><?php echo $this->lang->line('inventario_venta_precio'); ?>:</label><?php echo $arrCajasHTML['inventario_venta_precio']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='inventario_unidad_venta_compra'><?php echo $this->lang->line('inventario_unidad_venta_compra'); ?>:</label><?php echo $arrCajasHTML['inventario_unidad_venta_compra']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='inventario_categoria'><?php echo $this->lang->line('inventario_categoria'); ?>:</label><?php echo $arrCajasHTML['inventario_categoria']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='intentario_aclaracion'><?php echo $this->lang->line('intentario_aclaracion'); ?>:</label><?php echo $arrCajasHTML['intentario_aclaracion']; ?></div></div>

                <?php // PRODUCTO ?>

                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='producto_nombre'><?php echo $this->lang->line('producto_nombre'); ?>:</label><?php echo $arrCajasHTML['producto_nombre']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='producto_unidad'><?php echo $this->lang->line('producto_unidad'); ?>:</label><?php echo $arrCajasHTML['producto_unidad']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='producto_medida_cantidad'><?php echo $this->lang->line('producto_medida_cantidad'); ?>:</label><?php echo $arrCajasHTML['producto_medida_cantidad']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='producto_medida_precio'><?php echo $this->lang->line('producto_medida_precio'); ?>:</label><?php echo $arrCajasHTML['producto_medida_precio']; ?></div></div>

                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='detalle_descripcion'><?php echo $this->lang->line('detalle_descripcion'); ?>:</label><?php echo $arrCajasHTML['detalle_descripcion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='detalle_cantidad'><?php echo $this->lang->line('detalle_cantidad'); ?>:</label><?php echo $arrCajasHTML['detalle_cantidad']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='detalle_unidad'><?php echo $this->lang->line('detalle_unidad'); ?>:</label><?php echo $arrCajasHTML['detalle_unidad']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='detalle_costo_unitario'><?php echo $this->lang->line('detalle_costo_unitario'); ?>:</label><?php echo $arrCajasHTML['detalle_costo_unitario']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='detalle_costo_medida_unidad'><?php echo $this->lang->line('detalle_costo_medida_unidad'); ?>:</label><?php echo $arrCajasHTML['detalle_costo_medida_unidad']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='detalle_costo_medida_precio'><?php echo $this->lang->line('detalle_costo_medida_precio'); ?>:</label><?php echo $arrCajasHTML['detalle_costo_medida_precio']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='detalle_costo_medida_convertir'><?php echo $this->lang->line('detalle_costo_medida_convertir'); ?>:</label><?php echo $arrCajasHTML['detalle_costo_medida_convertir']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='detalle_costo_unidad_medida_uso'><?php echo $this->lang->line('detalle_costo_unidad_medida_uso'); ?>:</label><?php echo $arrCajasHTML['detalle_costo_unidad_medida_uso']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='detalle_costo_unidad_medida_cantidad'><?php echo $this->lang->line('detalle_costo_unidad_medida_cantidad'); ?>:</label><?php echo $arrCajasHTML['detalle_costo_unidad_medida_cantidad']; ?></div></div>

                
            </div>
        </div>
        
         <br /><br /><br />
    
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>