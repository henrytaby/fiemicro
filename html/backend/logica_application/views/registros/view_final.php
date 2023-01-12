<script type="text/javascript">
<?php

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios();
    
    switch ((int)$codigo_rubro) {
        
        // Solicitud de Crédito
        case 6:
            
            $arrDatos = $this->mfunciones_microcreditos->ObtenerDetalleSolicitudCredito($estructura_id);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDatos);
            $arrDatos[0]['onboarding'] = 1;
            
            // Se llama a la función para obtener las estadisticas de tiempo del Registro de Rubro
            $calculo_tiempo = $this->mfunciones_microcreditos->SolTiempoRegistroRubro($arrDatos[0]['sol_asignado_fecha'], $arrDatos[0]['sol_registro_completado_fecha']);
            
            $contenidoNavApp = $this->mfunciones_microcreditos->getContenidoNavApp($estructura_id, $vista_actual);
            
            $aux_text = '<label class="label-campo texto-centrado" for=""><i class="fa fa-info-circle" aria-hidden="true"></i> Importante: El cliente debe firmar el formulario de Solicitud de Crédito. </label><br /><br />';
            
            break;

        // Normalizador/Cobrador
        case 13:
            
            $arrDatos = $this->mfunciones_cobranzas->ObtenerDetalleRegistro($estructura_id);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDatos);
            $arrDatos[0]['onboarding'] = 1;
            
            // Se llama a la función para obtener las estadisticas de tiempo del Registro de Rubro
            $calculo_tiempo = $this->mfunciones_cobranzas->RegTiempoRegistroRubro($arrDatos[0]['norm_fecha'], $arrDatos[0]['norm_registro_completado_fecha']);
            
            $contenidoNavApp = $this->mfunciones_cobranzas->getContenidoNavApp($estructura_id, $vista_actual);
            
            $aux_text = '<label class="label-campo texto-centrado" for=""><i class="fa fa-info-circle" aria-hidden="true"></i> ' . $this->lang->line('norm_texto_final') . ' </label><br /><br />';
            
            break;
        
        default:

            // Se llama a la función para obtener las estadisticas de tiempo del Registro de Rubro
            $calculo_tiempo = $this->mfunciones_generales->TiempoRegistroRubro($estructura_id);

            // Se obtiene Datos del Registro
            $arrDatos = $this->mfunciones_logica->select_datos_generales($estructura_id);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDatos);

            $contenidoNavApp = $this->mfunciones_generales->getContenidoNavApp($estructura_id, $vista_actual);
            
            $aux_text = '';
            
            break;
    }
?>
    
</script>

    <?php

        if($vista_actual == '0' || $vista_actual == 'view_final')
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
    
    <nav class="navbar sticky-top <?php echo $clase_navbar_extra; ?>"> <?php echo $contenidoNavApp; ?> </nav>

    <div class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">
    
        <br /><br />
        
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <br />
        
        <div style="text-align: center;">
            <label class='label-campo texto-centrado panel-heading color-azul' for=''><i class="fa fa-check-circle" aria-hidden="true"></i> ¡Finalizó el registro de la Información! </label>
            <?php echo $aux_text; ?>
        </div>
        
        <div class='col-sm-6'>
        
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-clock-o" aria-hidden="true"></i> Estadísticas del Registro</div>
                <div class="panel-body">
                    
                    <div class="container">
                        <div class="row">
                            <div class="col" style="text-align: justify;">
                                <label class='label-campo texto-centrado' for=''> Inicio del Registro </label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='label-campo texto-centrado' for=''> <?php echo $calculo_tiempo->aux_fecha_ini; ?> </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="text-align: justify;">
                                <label class='label-campo texto-centrado' for=''> Fin del Registro </label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='label-campo texto-centrado' for=''> <?php echo $calculo_tiempo->aux_fecha_fin; ?> </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="text-align: justify;">
                                <label class='label-campo texto-centrado' for=''> Horas Laborales </label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='label-campo texto-centrado' for=''> <?php echo $calculo_tiempo->calculo_tiempo; ?> </label>
                            </div>
                        </div>
                        
                        <?php
                        if($arrDatos[0]['onboarding'] == 0)
                        {
                        ?>
                            <div class="row">
                                <div class="col" style="text-align: justify;">
                                    <label class='label-campo texto-centrado' for=''> <br />Tiempo Asignado </label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <label class='label-campo texto-centrado' for=''> <br /><?php echo $calculo_tiempo->tiempo_etapa; ?> </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col" style="text-align: justify;">
                                    <label class='label-campo texto-centrado' for=''> Resultado </label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <label class='label-campo texto-centrado' for=''> <?php echo $calculo_tiempo->calculo_estado; ?> </label>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        
        </div>
        
        <div class='col-sm-6'>
            
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-lightbulb-o" aria-hidden="true"></i> ¿Que puede hacer ahora?</div>
                <div class="panel-body">
                    
                     <div style="text-align: justify;">
                        <label class='label-campo texto-centrado' for=''><i class="fa fa-check-square-o" aria-hidden="true"></i> Si lo requiere, puede verificar cualquier paso (considere que las estadísticas y tiempos se actualizarán) </label>
                        <br /><label class='label-campo texto-centrado' for=''><i class="fa fa-check-square-o" aria-hidden="true"></i> Volver al Master de Navegación, para continuar con el registro y/o realizar las acciones correspondientes. </label>
                    </div>
                    
                </div>
            </div>
            
        </div>
        
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>