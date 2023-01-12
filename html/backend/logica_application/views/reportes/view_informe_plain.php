<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<?php

$alto_aux = "padding-top: 0px;";
$ancho_aux = "width: 100%; padding: 0px 0px;";

?>

<style>

    table, div{
        font-size: 10px; 
        font-family: chelvetica, tahoma;
        text-justify: inter-word;
        margin: 0px;
        /*line-height: 1.2;*/
    }
    
    th
    {
        background-color: #f5f5f5;
    }
    
    td, th
    {
        padding: 2px 5px;
    }
    
    .orSpan{
        position: absolute;
        margin-top: -1.3rem;
        margin-left:50%;
        padding:0 5px;
        background-color: white;
    }
    
    .borde
    {
        border: 1px solid #000000;
    }
    
    .borde_titulo
    {
        background-color: #f5f5f5;
        border: 1px solid #000000;
        font-weight: bold;
    }
    
    .borde_caqui
    {
        background-color: #ffcc99;
        border: 1px solid #000000;
        font-weight: bold;
        text-align: center;
    }
    
    .titulo_seccion
    {
        background-color: #0070c0;
        text-align: left;
        padding: 3px 5px;
        color: #ffffff;
        font-weight: bold;  
    }
    
</style>

<div align="center" style="<?php echo $ancho_aux; ?> text-align: justify;">
    
    <table style="width: 100%;">
        <tr>
            <td style="width: 50%; text-align: left;">
                <img style="width: 200px;" src="<?php echo $this->config->base_url(); ?>html_public/imagenes/reportes/logo.jpg" />
            </td>
            <td style="width: 50%; text-align: right; font-size: 16px;">
                <?php echo $fecha_actual; ?>
            </td>
        </tr>
    </table>
    
    <div align="center" style="<?php echo $ancho_aux; ?> text-align: center; text-justify: inter-word; font-size: 16px; font-weight: bold; margin: 50px 0px;">
        -------------------- Relevamiento de Información --------------------<!--|version|-->
    </div>
    
    <table style="width: 90%; margin: 50px 0px;" align="center" cellpadding="5">
        <tr>
            <td style="width: 40%; text-align: left; font-size: 16px; font-weight: bold;">
                Oficial de Negocios: 
            </td>
            <td style="width: 60%; text-align: left; font-size: 16px;">
                <?php echo $arrProspecto[0]['usuario_nombre']; ?>
            </td>
        </tr>
        <tr>
            <td style="width: 40%; text-align: left; font-size: 16px; font-weight: bold;">
                Sucursal: 
            </td>
            <td style="width: 60%; text-align: left; font-size: 16px;">
                <?php echo $arrProspecto[0]['estructura_regional_nombre']; ?>
            </td>
        </tr>
        <tr>
            <td style="width: 40%; text-align: left; font-size: 16px; font-weight: bold;">
                Solicitante del Crédito: 
            </td>
            <td style="width: 60%; text-align: left; font-size: 16px;">
                <?php echo $arrProspecto[0]['general_solicitante']; ?>
            </td>
        </tr>
        <tr>
            <td style="width: 40%; text-align: left; font-size: 16px; font-weight: bold;">
                Actividad: 
            </td>
            <td style="width: 60%; text-align: left; font-size: 16px;">
                <?php echo $arrProspecto[0]['general_actividad']; ?>
            </td>
        </tr>
        <tr>
            <td style="width: 40%; text-align: left; font-size: 16px; font-weight: bold;">
                Destino del Crédito: 
            </td>
            <td style="width: 60%; text-align: justify; font-size: 14px;">
                <?php echo nl2br($arrProspecto[0]['general_destino']); ?>
            </td>
        </tr>
        <tr>
            <td style="width: 40%; text-align: left; font-size: 16px; font-weight: bold;">
                Resultado de la Evaluación: 
            </td>
            <td style="width: 60%; text-align: justify; font-size: 16px;">
                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrProspecto[0]['prospecto_evaluacion'], 'prospecto_evaluacion'); ?>
            </td>
        </tr>
    </table>
    
    <table style="width: 100%;" align="center" cellpadding="5" cellspacing="0" class="tblListas Centrado" border="1">
        <thead>
            <tr class="FilaBlanca">
                <th style="width: 5%; text-align: center; font-size: 14px; font-weight: bold;">
                    Nro.
                </th>
                <th style="width: 20%; text-align: center; font-size: 14px;">
                    Rubro
                </th>
                <th style="width: 20%; text-align: center; font-size: 14px;">
                    Categoría
                </th>
                <th style="width: 35%; text-align: center; font-size: 14px;">
                    Actividad
                </th>
                <th style="width: 20%; text-align: center; font-size: 14px;">
                    Ingresos o Ventas de la Actividad
                </th>
            </tr>
        </thead>
        <tbody>
            
            <?php
    
            $i = 0;

            $arrAuxiliarIngresos = array();
            
            foreach ($arrFamiliar as $key => $value) 
            {
                $i++;
                $calculo_lead = $this->mfunciones_generales->CalculoLead($value["prospecto_id"], 'ingreso_ponderado');
                
                    // Cálculo auxiliar para la tabla de otros ingresos
                    
                    if($value['prospecto_principal'] == 0)
                    {
                        $arrAuxiliarIngresos[] = array(
                            "otros_id" => "1",
                            "prospecto_id" => "1",
                            "otros_descripcion_fuente" => $value['general_actividad'],
                            "otros_descripcion_respaldo" => "Estudio",
                            //"otros_monto" => $calculo_lead->utilidad_operativa
                            "otros_monto" => $calculo_lead->ingreso_mensual_promedio
                        );
                    }
                    
            ?>
            
            <tr>
                <td style="text-align: center; font-size: 14px;">
                    <?php echo $i; ?>
                </td>
                <td style="text-align: center; font-size: 14px;">
                    <?php echo $this->mfunciones_generales->GetValorCatalogo($value['camp_id'], 'nombre_rubro'); ?>
                </td>
                <td style="text-align: center; font-size: 14px;">
                    <?php echo ($value['prospecto_principal'] == 1 ? 'Principal' : 'Secundaria') ?>
                </td>
                <td style="text-align: center; font-size: 14px;">
                    <?php echo $value["general_actividad"]; ?>
                </td>
                <td style="text-align: right; font-size: 14px;">
                    Bs. <?php 
                        //echo number_format($calculo_lead->ingreso_mensual_promedio, 2, ',', '.');
                        echo number_format($calculo_lead->ingreso_mensual_promedio, 2, ',', '.');
                        ?>
                </td>
            </tr>
            
            <?php
            }
            ?>
            
        </tbody>
    </table>
    
    <br />
    
    <?php
    
    // SECCIÓN: OTROS INGRESOS
        
    if(in_array("transporte_otros_ingresos", $this->mfunciones_generales->getVistasRubro($arrFamiliar[0]['camp_id'])))
    {
    ?>
        <?php

        $suma_otros_ingresos = 0;

        $arrOtros_Ingresos = $this->mfunciones_logica->ObtenerListaOtrosIngresos($arrFamiliar[0]['prospecto_id'], -1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrOtros_Ingresos);

        if (isset($arrOtros_Ingresos[0]))
        {
        ?>

        <br />
        <div class="titulo_seccion" style="width: 100%; text-align: center; text-justify: inter-word; font-weight: bold; font-size: 14px;">
            Otros Ingresos
        </div>
    
            <table style="width: 100%;" align="center" cellpadding="5" cellspacing="0" class="tblListas Centrado" border="1">
            <thead>
                <tr class="FilaBlanca">
                    <th style="width: 40%; text-align: center; font-weight: bold; font-size: 14px;">
                        <?php echo $this->lang->line('otros_descripcion_fuente'); ?>
                    </th>
                    <th style="width: 40%; text-align: center; font-weight: bold; font-size: 14px;">
                        <?php echo $this->lang->line('otros_descripcion_respaldo'); ?>
                    </th>
                    <th style="width: 20%; text-align: center; font-weight: bold; font-size: 14px;">
                        <?php echo $this->lang->line('otros_monto'); ?>
                    </th>
                </tr>

                <?php

                foreach ($arrOtros_Ingresos as $key => $value) 
                {
                    $suma_otros_ingresos += $value["otros_monto"];
                ?>
                    <tr>
                        <td style="width: 40%; text-align: center; font-size: 14px;">
                            <?php echo $value["otros_descripcion_fuente"]; ?>
                        </td>
                        <td style="width: 40%; text-align: center; font-size: 14px;">
                            <?php echo $value["otros_descripcion_respaldo"]; ?>
                        </td>
                        <td style="width: 20%; text-align: right; font-size: 14px;">
                            <?php echo number_format($value["otros_monto"], 2, ',', '.'); ?>
                        </td>
                    </tr>

                <?php
                }
                ?>

                <tr>
                    <td colspan="2" style="text-align: right; font-weight: bold; font-size: 14px;" class="borde_titulo">
                        Total Otros Ingresos
                    </td>
                    <td style="text-align: right; font-size: 14px;" class="borde_titulo">
                        <?php echo number_format($suma_otros_ingresos, 2, ',', '.'); ?>
                    </td>
                </tr>

            </table>

            <br />

        <?php
        }
    }
    
    ?>
    
</div>

<?php

$j = 0;

foreach ($arrFamiliar as $key => $value) 
{
    /* NUEVA PÁGINA */ echo '<pagebreak />';
    
    // MUB TOTAL
    $mub_total = 0;
    
    $j++;

    $arrLead = $this->mfunciones_logica->ObtenerInfoProspecto($value['prospecto_id']);
    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrLead);
    
    $calculo_lead = $this->mfunciones_generales->CalculoLead($arrLead[0]['prospecto_id'], 'ingreso_ponderado');
    
?>

    <div align="center" style="<?php echo $ancho_aux; ?> text-align: justify;">

        <table style="width: 100%;">
            <tr>
                <td style="width: 40%; text-align: left;">
                    <img style="width: 200px;" src="<?php echo $this->config->base_url(); ?>html_public/imagenes/reportes/logo.jpg" />
                </td>
                <td style="width: 60%; text-align: right; font-size: 16px;">
                    
                    <?php
                    
                    $titular_nombre = $value['general_solicitante'];
        
                    $arrAuxConsulta = $this->mfunciones_logica->GetProspectoDepende($value['prospecto_id']);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrAuxConsulta);

                    if (isset($arrAuxConsulta[0])) 
                    {
                        $arrResultadoAux = $this->mfunciones_logica->select_info_dependencia($arrAuxConsulta[0]['general_depende']);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultadoAux);

                        $familia_nombre = 'FAMILIAR | ' . $arrResultadoAux[0]['general_solicitante'];
                    }
                    else
                    {
                        $familia_nombre = 'REGISTRO DEL TITULAR';
                    }
                    
                    ?>
                    
                    <table style="width: 100%;" border="0" cellspacing="0">
                        <tr>
                            <td style="width: 35%; text-align: right; font-weight: bold;">
                                NOMBRE DEL CLIENTE:
                            </td>
                            <td style="width: 65%; text-align: justify;" class="borde">
                                <?php echo $value['general_solicitante']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 35%; text-align: center; font-weight: bold; font-style: italic;">
                                <?php 
                                    // Actualización 22-01, se quita el contador de registros
                                    echo ($contenido_completo!='completo' ? '' : 'REGISTRO ' . $j . ' DE ' . count($arrFamiliar)); 
                                ?>  
                            </td>
                            <td style="width: 65%; text-align: right; font-style: italic;" class="">
                                <?php echo $familia_nombre; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <br />

        <div align="center" style="width: 100%; text-align: center; text-justify: inter-word; font-weight: bold;">
            FORMULARIO DE EVALUACIÓN DE CRÉDITOS MICRO A
        </div>

        <table style="width: 100%;">
            <tr>
                <td style="width: 30%; text-align: left;">

                </td>
                <td style="width: 40%; text-align: center; font-weight: bold;">
                    ACTIVIDAD <?php echo ($value['prospecto_principal'] == 1 ? 'PRINCIPAL' : 'SECUNDARIA') ?>
                </td>
                <td style="width: 15%; text-align: right; font-weight: bold;">
                    Moneda
                </td>
                <td style="width: 15%; text-align: center;" class="borde">
                    Bolivianos
                </td>
            </tr>
        </table>

        <br />

        <table style="width: 100%;" cellspacing="0">
            <tr>
                <td style="width: 25%; text-align: center;" class="borde_titulo">
                    Descripción de la Actividad
                </td>
                <td style="width: 50%; text-align: center;" class="borde">
                    <?php echo $value['general_actividad']; ?>
                </td>
                <td style="width: 10%; text-align: center;" class="borde_titulo">
                    Sector
                </td>
                <td style="width: 15%; text-align: center;" class="borde">
                    <?php echo $this->mfunciones_generales->GetValorCatalogo($value['camp_id'], 'nombre_rubro'); ?>
                </td>
            </tr>
            
            <tr>
                <td colspan="3" style="width: 85%; text-align: right; font-weight: bold;" class="">
                    Efectivo verificado al momento de la visita
                </td>
                <td style="width: 15%; text-align: right;" class="borde">
                    <?php echo number_format($arrLead[0]['operacion_efectivo'], 2, ',', '.'); ?>
                </td>
            </tr>
            
        </table>

        <br />

        <table style="width: 100%;" cellspacing="0">
            <tr>
                <td style="width: 25%; text-align: center;" class="borde_titulo">
                    Tiempo de antigüedad de la actividad desarrollada
                </td>
                <td style="width: 6%; text-align: center; font-weight: bold;" class="borde">
                    Años:
                </td>
                <td style="width: 6%; text-align: center;" class="borde">
                    <?php echo $arrLead[0]['operacion_antiguedad_ano']; ?>
                </td>
                <td style="width: 7%; text-align: center; font-weight: bold;" class="borde">
                    Meses:
                </td>
                <td style="width: 6%; text-align: center;" class="borde">
                    <?php echo $arrLead[0]['operacion_antiguedad_mes']; ?>
                </td>

                <td style="width: 25%; text-align: center;" class="borde_titulo">
                    Tiempo que desarrolla la actividad en el mismo lugar:
                </td>
                <td style="width: 6%; text-align: center; font-weight: bold;" class="borde">
                    Años:
                </td>
                <td style="width: 6%; text-align: center;" class="borde">
                    <?php echo $arrLead[0]['operacion_tiempo_ano']; ?>
                </td>
                <td style="width: 7%; text-align: center; font-weight: bold;" class="borde">
                    Meses:
                </td>
                <td style="width: 6%; text-align: center;" class="borde">
                    <?php echo $arrLead[0]['operacion_tiempo_mes']; ?>
                </td>
            </tr>
        </table>

        <br />
    
        <?php

            $rubro_unidad = $arrLead[0]['camp_id'];
            $frecuencia_seleccionada = $arrLead[0]['frec_seleccion'];
            
            $calculoLead = $this->mfunciones_generales->CalculoLead($value['prospecto_id'], 'ingreso_ponderado')
        ?>
    
    
        <?php
        
        // ********** TRANSPORTE INICIO **********
        
        // SECCIÓN: FUENTE GENERADORA

        if(in_array("fuente_generadora", $this->mfunciones_generales->getVistasRubro($rubro_unidad)))
        {
        ?>
            <table style="width: 100%;" cellspacing="0">
                <tr>
                    <td style="width: 20%; text-align: center;" class="borde_titulo">
                        TIPO DE PRESTATARIO
                    </td>
                    <td style="width: 40%; text-align: center;" class="borde">
                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['transporte_tipo_prestatario'], 'transporte_tipo_prestatario'); ?>
                    </td>
                    <td style="width: 20%; text-align: center;" class="borde_titulo">
                        TIPO DE TRANSPORTE
                    </td>
                    <td style="width: 20%; text-align: center;" class="borde">
                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['transporte_tipo_transporte'], 'transporte_tipo_transporte'); ?>
                    </td>
                </tr>
            </table>
        
            <br />
            
            <div class="titulo_seccion" style="width: 100%; text-justify: inter-word; font-weight: bold;">
                I. INFORMACIÓN DE LA FUENTE GENERADORA DE INGRESOS
            </div>
            <br />
            
            <?php
            
            $tipo_prestatario = $arrLead[0]['transporte_tipo_prestatario'];
            $tipo_transporte = $arrLead[0]['transporte_tipo_transporte'];
            $texto_no_corresponde = '<span style="color: #ff99ff; font-style: italic;">No Corresponde</span>';
            
            ?>
            
            <table style="width: 100%;" cellspacing="0">
                <tr>
                    <td style="width: 30%; text-align: center; height: 30px;" class="borde_titulo">
                        <?php echo $this->lang->line('transporte_preg_sindicato'); ?>
                    </td>
                    <td style="width: 20%; text-align: center; height: 30px;" class="borde">
                        <?php echo ($tipo_prestatario!=0 && $tipo_transporte!=5 ? $arrLead[0]['transporte_preg_sindicato'] : $texto_no_corresponde); ?>
                    </td>
                    <td style="width: 30%; text-align: center; height: 30px;" class="borde_titulo">
                        <?php echo $this->lang->line('transporte_preg_trabaja_dia'); ?>
                    </td>
                    <td style="width: 20%; text-align: center; height: 30px;" class="borde">
                        <?php echo ($tipo_prestatario!=0 || $tipo_transporte!=0 ? $this->mfunciones_generales->getFormatoFechaH_M($arrLead[0]['transporte_preg_trabaja_dia']) : $texto_no_corresponde); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%; text-align: center; height: 30px;" class="borde_titulo">
                        <?php echo $this->lang->line('transporte_preg_sindicato_lineas'); ?>
                    </td>
                    <td style="width: 20%; text-align: center; height: 30px;" class="borde">
                        <?php echo ($tipo_prestatario!=0 && $tipo_transporte<4 ? $arrLead[0]['transporte_preg_sindicato_lineas'] : $texto_no_corresponde); ?>
                    </td>
                    <td style="width: 30%; text-align: center; height: 30px;" class="borde_titulo">
                        <?php echo $this->lang->line('transporte_preg_jornada_inicia'); ?>
                    </td>
                    <td style="width: 20%; text-align: center; height: 30px;" class="borde">
                        <?php echo ($tipo_prestatario!=0 || $tipo_transporte!=0 ? $this->mfunciones_generales->getFormatoFechaH_M($arrLead[0]['transporte_preg_jornada_inicia']) : $texto_no_corresponde); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%; text-align: center; height: 30px;" class="borde_titulo">
                        <?php echo $this->lang->line('transporte_preg_sindicato_grupos'); ?>
                    </td>
                    <td style="width: 20%; text-align: center; height: 30px;" class="borde">
                        <?php echo ($tipo_prestatario!=0 && $tipo_transporte<4 ? $arrLead[0]['transporte_preg_sindicato_grupos'] : $texto_no_corresponde); ?>
                    </td>
                    <td style="width: 30%; text-align: center; height: 30px;" class="borde_titulo">
                        <?php echo $this->lang->line('transporte_preg_jornada_concluye'); ?>
                    </td>
                    <td style="width: 20%; text-align: center; height: 30px;" class="borde">
                        <?php echo ($tipo_prestatario!=0 || $tipo_transporte!=0 ? $this->mfunciones_generales->getFormatoFechaH_M($arrLead[0]['transporte_preg_jornada_concluye']) : $texto_no_corresponde); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%; text-align: center; height: 30px;" class="borde_titulo">
                        <?php echo $this->lang->line('transporte_preg_unidades_grupo'); ?>
                    </td>
                    <td style="width: 20%; text-align: center; height: 30px;" class="borde">
                        <?php echo ($tipo_prestatario!=0 && $tipo_transporte<4 ? $arrLead[0]['transporte_preg_unidades_grupo'] : $texto_no_corresponde); ?>
                    </td>
                    <td style="width: 30%; text-align: center; height: 30px;" class="borde_titulo">
                        <?php echo $this->lang->line('transporte_preg_tiempo_no_trabaja'); ?>
                    </td>
                    <td style="width: 20%; text-align: center; height: 30px;" class="borde">
                        <?php echo ($tipo_prestatario==2 && $tipo_transporte!=0 ? $texto_no_corresponde : $this->mfunciones_generales->getFormatoFechaH_M($arrLead[0]['transporte_preg_tiempo_no_trabaja'])); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%; text-align: center; height: 30px;" class="borde_titulo">
                        <?php echo $this->lang->line('transporte_preg_grupo_rota'); ?>
                    </td>
                    <td style="width: 20%; text-align: center; height: 30px;" class="borde">
                        <?php echo ($tipo_prestatario!=0 && $tipo_transporte<4 ? $arrLead[0]['transporte_preg_grupo_rota'] : $texto_no_corresponde); ?>
                    </td>
                    <td style="width: 30%; text-align: center; height: 30px;" class="borde_titulo">
                        <?php echo $this->lang->line('transporte_preg_tiempo_parada'); ?>
                    </td>
                    <td style="width: 20%; text-align: center; height: 30px;" class="borde">
                        <?php echo ($tipo_prestatario==2 || $tipo_transporte>=4  ? $texto_no_corresponde : $this->mfunciones_generales->getFormatoFechaH_M($arrLead[0]['transporte_preg_tiempo_parada'])); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%; text-align: center; height: 30px;" class="borde_titulo">
                        <?php echo $this->lang->line('transporte_preg_lineas_buenas'); ?>
                    </td>
                    <td style="width: 20%; text-align: center; height: 30px;" class="borde">
                        <?php echo ($tipo_prestatario!=0 && $tipo_transporte<4 ? $arrLead[0]['transporte_preg_lineas_buenas'] : $texto_no_corresponde); ?>
                    </td>
                    <td style="width: 30%; text-align: center; height: 30px;" class="borde_titulo">
                        <?php echo ($tipo_transporte<4 ? $this->lang->line('transporte_preg_tiempo_vuelta') : $this->lang->line('transporte_preg_tiempo_carrera')); ?>
                    </td>
                    <td style="width: 20%; text-align: center; height: 30px;" class="borde">
                        <?php echo ($tipo_prestatario==2 ? $texto_no_corresponde : $this->mfunciones_generales->getFormatoFechaH_M($arrLead[0]['transporte_preg_tiempo_vuelta'])); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%; text-align: center; height: 30px;" class="borde_titulo">
                        <?php echo $this->lang->line('transporte_preg_lineas_regulares'); ?>
                    </td>
                    <td style="width: 20%; text-align: center; height: 30px;" class="borde">
                        <?php echo ($tipo_prestatario!=0 && $tipo_transporte<4 ? $arrLead[0]['transporte_preg_lineas_regulares'] : $texto_no_corresponde); ?>
                    </td>
                    <td style="width: 30%; text-align: center; height: 30px;" class="borde_titulo">
                        <?php echo $this->lang->line('transporte_preg_vehiculo_ano'); ?>
                    </td>
                    <td style="width: 20%; text-align: center; height: 30px;" class="borde">
                        <?php echo ($tipo_prestatario!=0 || $tipo_transporte!=0 ? ($arrLead[0]['transporte_preg_vehiculo_ano']==1 ? 'Anterior a 1970' : $arrLead[0]['transporte_preg_vehiculo_ano']) : $texto_no_corresponde); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%; text-align: center; height: 30px;" class="borde_titulo">
                        <?php echo $this->lang->line('transporte_preg_lineas_malas'); ?>
                    </td>
                    <td style="width: 20%; text-align: center; height: 30px;" class="borde">
                        <?php echo ($tipo_prestatario!=0 && $tipo_transporte<4 ? $arrLead[0]['transporte_preg_lineas_malas'] : $texto_no_corresponde); ?>
                    </td>
                    <td style="width: 30%; text-align: center; height: 30px;" class="borde_titulo">
                        <?php echo $this->lang->line('transporte_preg_vehiculo_combustible'); ?>
                    </td>
                    <td style="width: 20%; text-align: center; height: 30px;" class="borde">
                        <?php echo ($tipo_prestatario!=0 || $tipo_transporte!=0 ? $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['transporte_preg_vehiculo_combustible'], 'transporte_preg_vehiculo_combustible') : $texto_no_corresponde); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%; text-align: center; height: 30px;" class="borde_titulo">
                        <?php echo $this->lang->line('transporte_preg_trabaja_semana'); ?>
                    </td>
                    <td style="width: 20%; text-align: center; height: 30px;" class="borde">
                        <?php echo ($tipo_prestatario!=0 || $tipo_transporte!=0 ? $arrLead[0]['transporte_preg_trabaja_semana'] : $texto_no_corresponde); ?>
                    </td>
                    <td style="width: 30%; text-align: center; height: 30px;" class="borde_titulo">
                        &nbsp;
                    </td>
                    <td style="width: 20%; text-align: center; height: 30px;" class="borde_titulo">
                        &nbsp;
                    </td>
                </tr>
            </table>
            
            <br />
            
        <?php
        }
        
        // SECCIÓN: FUENTE GENERADORA

        if(in_array("volumen_ingresos", $this->mfunciones_generales->getVistasRubro($rubro_unidad)))
        {
            $volumen_aux_columna_1 = '20%';
            $volumen_aux_columna_2 = '40%';
            $volumen_aux_columna_3 = '40%';
            
            $volumen_aux_columna_4 = '30%';
            $volumen_aux_columna_5 = '30%';
            $volumen_aux_columna_6 = '40%';
            
            $letra_auxiliar = 'font-size: 9px;';
            
            if($arrLead[0]['transporte_tipo_transporte'] >= 4){ $letra_auxiliar = 'font-size: 11px;'; $volumen_aux_columna_4 = '5%'; $volumen_aux_columna_5 = '5%'; $volumen_aux_columna_6 = '90%'; $volumen_aux_columna_1 = '45%'; $volumen_aux_columna_2 = '10%'; $volumen_aux_columna_3 = '45%'; }
            if($arrLead[0]['transporte_tipo_prestatario'] == 2){ $volumen_aux_columna_1 = '60%'; $volumen_aux_columna_2 = '20%'; $volumen_aux_columna_3 = '20%'; }
            
        ?>
            <div class="titulo_seccion" style="width: 100%; text-justify: inter-word; font-weight: bold;">
                II. ESTIMACIÓN DEL VOLUMEN DE INGRESOS PERIODICOS
            </div>
            <div class="titulo_seccion" style="width: 100%; text-justify: inter-word; font-weight: bold;">
                II.1. SEGÚN DECLARACIÓN DEL CLIENTE
            </div>
            <br />
            
            <table style="width: 100%;" cellspacing="0" cellpadding="5">
                <tr>
                    <td valign="top" style="width: <?php echo $volumen_aux_columna_1; ?>; text-align: left;">
                        
                        <table style="width: 100%;" cellspacing="0">
                            <tr>
                                <td style="width: 70%; text-align: center;" class="borde_titulo">
                                    POR DIA<br />(Ultima semana)
                                </td>
                                <td style="width: 30%; text-align: center;" class="borde_titulo">
                                    PROMEDIO
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde_titulo">
                                    <?php echo $this->lang->line('transporte_cliente_dia_lunes'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['transporte_cliente_dia_lunes'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde_titulo">
                                    <?php echo $this->lang->line('transporte_cliente_dia_martes'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['transporte_cliente_dia_martes'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde_titulo">
                                    <?php echo $this->lang->line('transporte_cliente_dia_miercoles'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['transporte_cliente_dia_miercoles'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde_titulo">
                                    <?php echo $this->lang->line('transporte_cliente_dia_jueves'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['transporte_cliente_dia_jueves'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde_titulo">
                                    <?php echo $this->lang->line('transporte_cliente_dia_viernes'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['transporte_cliente_dia_viernes'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde_titulo">
                                    <?php echo $this->lang->line('transporte_cliente_dia_sabado'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['transporte_cliente_dia_sabado'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde_titulo">
                                    <?php echo $this->lang->line('transporte_cliente_dia_domingo'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['transporte_cliente_dia_domingo'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde_titulo">
                                    TOTAL
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde_titulo">
                                    <?php echo number_format($calculoLead->cliente_dia_total, 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: left;" class="borde_titulo">
                                    DÍAS CONSIDERADOS
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde_titulo">
                                    <?php echo $calculoLead->dias_considerados; ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: left; text-transform: uppercase;" class="borde_titulo">
                                    <?php echo $calculoLead->cliente_frecuencia_texto; ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde_titulo">
                                    <?php echo number_format($calculoLead->cliente_dia_total_total, 2, ',', '.'); ?>
                                </td>
                            </tr>
                            
                        </table>
                        
                    </td>
                    <td valign="top" style="width: <?php echo $volumen_aux_columna_2; ?>; text-align: center;">
                    
                        <?php
                        if($tipo_transporte<4 && $tipo_prestatario != 2)
                        {
                        ?>
                        
                            <table style="width: 100%;" cellspacing="0">
                                <tr>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        POR LÍNEA
                                    </td>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        MÍNIMO
                                    </td>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        MÁXIMO
                                    </td>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        PROMEDIO
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 25%; text-align: center;" class="borde">
                                        <?php echo $arrLead[0]['transporte_cliente_linea1_texto']; ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_linea1_min'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_linea1_max'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->transporte_cliente_linea1_promedio, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 25%; text-align: center;" class="borde">
                                        <?php echo $arrLead[0]['transporte_cliente_linea2_texto']; ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_linea2_min'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_linea2_max'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->transporte_cliente_linea2_promedio, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 25%; text-align: center;" class="borde">
                                        <?php echo $arrLead[0]['transporte_cliente_linea3_texto']; ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_linea3_min'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_linea3_max'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->transporte_cliente_linea3_promedio, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 25%; text-align: center;" class="borde">
                                        <?php echo $arrLead[0]['transporte_cliente_linea4_texto']; ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_linea4_min'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_linea4_max'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->transporte_cliente_linea4_promedio, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 25%; text-align: center;" class="borde">
                                        <?php echo $arrLead[0]['transporte_cliente_linea5_texto']; ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_linea5_min'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_linea5_max'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->transporte_cliente_linea5_promedio, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 25%; text-align: center;" class="borde">
                                        <?php echo $arrLead[0]['transporte_cliente_linea6_texto']; ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_linea6_min'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_linea6_max'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->transporte_cliente_linea6_promedio, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 25%; text-align: center;" class="borde">
                                        <?php echo $arrLead[0]['transporte_cliente_linea7_texto']; ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_linea7_min'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_linea7_max'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->transporte_cliente_linea7_promedio, 2, ',', '.'); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 25%; text-align: right;" class="borde_titulo">
                                        TOTAL
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->transporte_cliente_linea_suma_minimo, 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->transporte_cliente_linea_suma_maximo, 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->transporte_cliente_linea_suma_promedio, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="width: 70%; text-align: left;" class="borde_titulo">
                                        DÍAS CONSIDERADOS
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde_titulo">
                                        <?php echo $calculoLead->dias_considerados; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="width: 70%; text-align: left; text-transform: uppercase;" class="borde_titulo">
                                        <?php echo $calculoLead->cliente_frecuencia_texto; ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->transporte_cliente_linea_total, 2, ',', '.'); ?>
                                    </td>
                                </tr>

                            </table>
                        <?php
                        }
                        ?>
                        
                    </td>
                    <td valign="top" style="width: <?php echo $volumen_aux_columna_3; ?>; text-align: right;">
                        
                        <?php
                        if($tipo_prestatario != 2)
                        {
                        ?>
                        
                            <table style="width: 100%;" cellspacing="0">
                                <tr>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        POR<br /><?php echo ($tipo_transporte<4 ? 'VUELTA' : 'CARRERA'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        IMPORTE
                                    </td>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        N° <?php echo ($tipo_transporte<4 ? 'VUELTA' : 'CARRERA'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        PROMEDIO
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 25%; text-align: center;" class="borde">
                                        BUENAS
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_vueta_buena_monto'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_vueta_buena_numero'], 0, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->transporte_cliente_vueta_buena_promedio, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 25%; text-align: center; font-size: 8px;" class="borde">
                                        REGULARES
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_vueta_regular_monto'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_vueta_regular_numero'], 0, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->transporte_cliente_vueta_regular_promedio, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 25%; text-align: center;" class="borde">
                                        MALAS
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_vueta_mala_monto'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_cliente_vueta_mala_numero'], 0, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->transporte_cliente_vueta_mala_promedio, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        &nbsp;
                                    </td>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        &nbsp;
                                    </td>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        &nbsp;
                                    </td>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        &nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        &nbsp;
                                    </td>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        &nbsp;
                                    </td>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        &nbsp;
                                    </td>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        &nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        &nbsp;
                                    </td>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        &nbsp;
                                    </td>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        &nbsp;
                                    </td>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        &nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        &nbsp;
                                    </td>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        &nbsp;
                                    </td>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        &nbsp;
                                    </td>
                                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                                        &nbsp;
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 25%; text-align: right;" class="borde_titulo">
                                        TOTAL
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->transporte_cliente_vueta_suma_importe, 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->transporte_cliente_vueta_suma_vuelta, 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 25%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->transporte_cliente_vueta_suma_promedio, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="width: 70%; text-align: left;" class="borde_titulo">
                                        DÍAS CONSIDERADOS
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde_titulo">
                                        <?php echo $calculoLead->dias_considerados; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="width: 70%; text-align: left; text-transform: uppercase;" class="borde_titulo">
                                        <?php echo $calculoLead->cliente_frecuencia_texto; ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->transporte_cliente_vueta_total, 2, ',', '.'); ?>
                                    </td>
                                </tr>

                            </table>
                        
                        <?php
                        }
                        ?>
                        
                    </td>
                </tr>
            </table>
        
            <br />
        
            <?php
            if($tipo_prestatario != 2)
            {
            ?>
            
                <div class="titulo_seccion" style="width: 100%; text-justify: inter-word; font-weight: bold;">
                    II.2. SEGÚN CAPACIDAD INSTALADA DEL VEHÍCULO
                </div>
                <br />

                <table style="width: 100%;" cellspacing="0" cellpadding="5">
                    <tr>
                        <td valign="top" style="width: <?php echo $volumen_aux_columna_4; ?>; text-align: left;">

                            <?php
                            if($tipo_transporte < 4)
                            {
                            ?>

                                <table style="width: 100%;" cellspacing="0">
                                    <tr>
                                        <td style="width: 80%; text-align: left; text-transform: uppercase; font-size: 9px;" class="borde_titulo">
                                            <?php echo $this->lang->line('transporte_capacidad_sin_rotacion'); ?>
                                        </td>
                                        <td style="width: 20%; text-align: right;" class="borde">
                                            <?php echo number_format($arrLead[0]['transporte_capacidad_sin_rotacion'], 0, ',', '.'); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 80%; text-align: left; text-transform: uppercase; font-size: 9px;" class="borde_titulo">
                                            <?php echo $this->lang->line('transporte_capacidad_con_rotacion'); ?>
                                        </td>
                                        <td style="width: 20%; text-align: right;" class="borde">
                                            <?php echo number_format($arrLead[0]['transporte_capacidad_con_rotacion'], 0, ',', '.'); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 80%; text-align: left; text-transform: uppercase; font-size: 9px;" class="borde_titulo">
                                            ROTACIÓN
                                        </td>
                                        <td style="width: 20%; text-align: right;" class="borde_titulo">
                                            <?php echo number_format(($arrLead[0]['transporte_capacidad_sin_rotacion']!=0 ? $arrLead[0]['transporte_capacidad_con_rotacion']/$arrLead[0]['transporte_capacidad_sin_rotacion'] : 0), 2, ',', '.'); ?>
                                        </td>
                                    </tr>
                                </table>

                            <?php
                            }
                            ?>

                        </td>
                        <td valign="top" style="width: <?php echo $volumen_aux_columna_5; ?>; text-align: center;">

                            <?php
                            if($tipo_transporte < 4)
                            {
                            ?>

                                <table style="width: 100%;" cellspacing="0">
                                    <tr>
                                        <td style="width: 80%; text-align: left; text-transform: uppercase; <?php echo $letra_auxiliar; ?>" class="borde_titulo">
                                            <?php echo $this->lang->line('transporte_capacidad_tramo_largo_pasajero'); ?>
                                        </td>
                                        <td style="width: 20%; text-align: right;" class="borde">
                                            <?php echo number_format($arrLead[0]['transporte_capacidad_tramo_largo_pasajero'], 0, ',', '.'); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 80%; text-align: left; text-transform: uppercase; <?php echo $letra_auxiliar; ?>" class="borde_titulo">
                                            <?php echo $this->lang->line('transporte_capacidad_tramo_corto_pasajero'); ?>
                                        </td>
                                        <td style="width: 20%; text-align: right;" class="borde">
                                            <?php echo number_format($arrLead[0]['transporte_capacidad_tramo_corto_pasajero'], 0, ',', '.'); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 80%; text-align: left; text-transform: uppercase; <?php echo $letra_auxiliar; ?>" class="borde_titulo">
                                            <?php echo $this->lang->line('transporte_capacidad_tramo_largo_precio'); ?>
                                        </td>
                                        <td style="width: 20%; text-align: right;" class="borde">
                                            <?php echo number_format($arrLead[0]['transporte_capacidad_tramo_largo_precio'], 2, ',', '.'); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 80%; text-align: left; text-transform: uppercase; <?php echo $letra_auxiliar; ?>" class="borde_titulo">
                                            <?php echo $this->lang->line('transporte_capacidad_tramo_corto_precio'); ?>
                                        </td>
                                        <td style="width: 20%; text-align: right;" class="borde">
                                            <?php echo number_format($arrLead[0]['transporte_capacidad_tramo_corto_precio'], 2, ',', '.'); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 80%; text-align: left; text-transform: uppercase; <?php echo $letra_auxiliar; ?>" class="borde_titulo">
                                            PRECIO PROMEDIO
                                        </td>
                                        <td style="width: 20%; text-align: right;" class="borde_titulo">
                                            <?php echo number_format($calculoLead->precio_promedio, 2, ',', '.'); ?>
                                        </td>
                                    </tr>
                                </table>

                            <?php
                            }
                            ?>

                        </td>
                        <td valign="top" style="width: <?php echo $volumen_aux_columna_6; ?>; text-align: right;">

                            <table style="width: 100%;" cellspacing="0">
                                <tr>
                                    <td style="width: 20%; text-align: center; text-transform: uppercase; <?php echo $letra_auxiliar; ?>" class="borde_titulo">
                                        <?php echo ($tipo_transporte<4 ? 'VUELTAS' : 'CARRERAS'); ?>
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: center;" class="borde_titulo">
                                        % OCUPACIÓN
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: center;" class="borde_titulo">
                                        # PASAJEROS
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: center;" class="borde_titulo">
                                        INGRESO
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: center;" class="borde_titulo">
                                        # <?php echo ($tipo_transporte<4 ? 'VUELTA' : 'CARRERA'); ?>
                                    </td>
                                    <td style="width: 16%; text-align: center;" class="borde_titulo">
                                        TOTAL
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 20%; <?php echo $letra_auxiliar; ?> text-align: center; text-transform: uppercase; <?php echo $letra_auxiliar; ?>" class="borde">
                                        BUENAS
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: center;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_vuelta_buena_ocupacion'], 2, ',', '.'); ?>%
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->cliente_capacidad_buena_pasajeros, 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->cliente_capacidad_buena_ingreso, 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_vuelta_buena_veces'], 0, ',', '.'); ?>
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->cliente_capacidad_buena_total, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 20%; <?php echo $letra_auxiliar; ?> text-align: center; text-transform: uppercase; <?php echo $letra_auxiliar; ?>" class="borde">
                                        REGULAR
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: center;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_vuelta_regular_ocupacion'], 2, ',', '.'); ?>%
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->cliente_capacidad_regular_pasajeros, 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->cliente_capacidad_regular_ingreso, 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_vuelta_regular_veces'], 0, ',', '.'); ?>
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->cliente_capacidad_regular_total, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 20%; <?php echo $letra_auxiliar; ?> text-align: center; text-transform: uppercase; <?php echo $letra_auxiliar; ?>" class="borde">
                                        MALAS
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: center;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_vuelta_mala_ocupacion'], 2, ',', '.'); ?>%
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->cliente_capacidad_mala_pasajeros, 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->cliente_capacidad_mala_ingreso, 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['transporte_vuelta_mala_veces'], 0, ',', '.'); ?>
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->cliente_capacidad_mala_total, 2, ',', '.'); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" style="width: 52%; <?php echo $letra_auxiliar; ?> text-align: center; text-transform: uppercase; <?php echo $letra_auxiliar; ?>" class="borde">
                                        INGRESO PROMEDIO POR <?php echo ($tipo_transporte<4 ? 'VUELTA' : 'CARRERA'); ?>
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->cliente_capacidad_ingreso_total, 2, ',', '.'); ?>
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: right;" class="">
                                        &nbsp;
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->cliente_capacidad_total, 2, ',', '.'); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4" style="width: 68%; <?php echo $letra_auxiliar; ?> text-align: center; text-transform: uppercase; <?php echo $letra_auxiliar; ?>" class="borde">
                                        INGRESO PROMEDIO POR <?php echo ($tipo_transporte<4 ? 'VUELTA' : 'CARRERA'); ?>
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: right;" class="">
                                        &nbsp;
                                    </td>
                                    <td style="width: 16%; <?php echo $letra_auxiliar; ?> text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculoLead->cliente_capacidad_ingreso_mensual, 2, ',', '.'); ?>
                                    </td>
                                </tr>


                            </table>

                        </td>
                    </tr>
                </table>
                
                <br />
            
            <?php
            }
            ?>
            
            <table style="width: 100%;" cellspacing="0">
                <tr>
                    <td style="width: 40%; text-align: center; background-color: #92d050;" class="borde_titulo">
                        CÁLCULO DE HORAS NETAS DE TRABAJO SEGÚN DECLARADO EN PUNTO I
                    </td>
                    <td style="width: 10%; text-align: center; background-color: #92d050;" class="borde_titulo">
                        <?php echo $calculoLead->horas_netas; ?>
                    </td>
                    <td style="width: 40%; text-align: center; background-color: #92d050;" class="borde_titulo">
                        CALCULO DE <?php echo ($tipo_transporte<4 ? 'VUELTAS' : 'CARRERAS'); ?> SEGÚN LO DECLARADO EN PUNTO I
                    </td>
                    <td style="width: 10%; text-align: center; background-color: #92d050;" class="borde_titulo">
                        <?php echo $calculoLead->calculo_vueltas; ?>
                    </td>
                </tr>
            </table>
            
            <br />
            
            <table align="left" style="width: 60%;" cellspacing="0">
                <tr>
                    <td style="width: 5%; text-align: center; font-weight: bold;" class="borde_caqui">
                        &nbsp;
                    </td>
                    <td colspan="2" style="width: 75%; text-align: center;" class="borde_caqui">
                        CRITERIO
                    </td>
                    <td style="width: 20%; text-align: center;" class="borde_caqui">
                        Bs. / MES
                    </td>
                </tr>
                 <tr>
                     <td style="width: 5%; text-align: center; font-weight: bold;" class="borde">
                         a)
                     </td>
                     <td colspan="2" style="width: 75%; text-align: justify;" class="borde">
                         Ingreso según declaración del cliente
                     </td>
                     <td style="width: 20%; text-align: right;" class="borde">
                         <?php echo number_format($calculo_lead->criterio_declaracion_cliente, 2, ',', '.'); ?>
                     </td>
                 </tr>
                 <tr>
                     <td style="width: 5%; text-align: center; font-weight: bold;" class="borde">
                         b)
                     </td>
                     <td colspan="2" style="width: 75%; text-align: justify;" class="borde">
                         Ingreso según capacidad instalada
                     </td>
                     <td style="width: 20%; text-align: right;" class="borde">
                         <?php echo number_format($calculo_lead->criterio_capacidad_instalada, 2, ',', '.'); ?>
                     </td>
                 </tr>
                 <tr>
                     <td style="width: 5%; text-align: center; font-weight: bold;" class="">
                         &nbsp;
                     </td>
                     <td style="width: 65%; text-align: center; font-style: italic;" class="borde">
                         Criterio seleccionado
                     </td>
                     <td style="width: 10%; text-align: center;" class="borde_titulo">
                         =>
                     </td>
                     <td style="width: 20%; text-align: right;" class="borde_titulo">
                         <?php echo number_format($calculo_lead->ingreso_mensual_promedio, 2, ',', '.'); ?>
                     </td>
                 </tr>
             </table>
            
        <?php
        }
        if(in_array("transporte_margen_utilidad", $this->mfunciones_generales->getVistasRubro($rubro_unidad)))
        {
        ?>
            <br />
            <div class="titulo_seccion" style="width: 100%; text-justify: inter-word; font-weight: bold;">
                III. ESTIMACIÓN DEL MARGEN DE UTILIDAD BRUTA
            </div>
            <br />
            
            <?php
            
            $suma_margen_egreso = 0;
            $suma_margen_ingreso = 0;
            
            $arrEgresos = $this->mfunciones_logica->ObtenerListaMargen($arrLead[0]['prospecto_id'], -1, 0);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEgresos);
            
            if (!isset($arrEgresos[0])) 
            {
                echo 'No se Registró Información de Egresos <br /><br />';
            }
            else
            {
            ?>
            
                <table align="center" style="width: 100%;" cellspacing="0">
                    <tr>
                        <th colspan="5" style="width: 30%; text-align: center; font-weight: bold;" class="borde">
                            INFORMACIÓN DE EGRESOS
                        </th>
                    </tr>
                    <tr>
                        <th style="width: 30%; text-align: center; font-weight: bold;" class="borde">
                            <?php echo $this->lang->line('margen_nombre'); ?>
                        </th>
                        <th style="width: 25%; text-align: center; font-weight: bold;" class="borde">
                            <?php echo $this->lang->line('margen_cantidad'); ?>
                        </th>
                        <th style="width: 15%; text-align: center; font-weight: bold;" class="borde">
                            <?php echo $this->lang->line('margen_unidad_medida'); ?>
                        </th>
                        <th style="width: 15%; text-align: center; font-weight: bold;" class="borde">
                            Costo Unitario
                        </th>
                        <th style="width: 15%; text-align: center; font-weight: bold;" class="borde">
                            Costo Total
                        </th>
                    </tr>

                    <?php
                    
                    foreach ($arrEgresos as $key => $value) 
                    {
                        if($value['margen_tipo'] == 0)
                        {
                            $margen_monto_total = $value["margen_cantidad"]*$value["margen_monto_unitario"];
                        }
                        else
                        {
                            $margen_monto_total = $value["margen_cantidad"]*$value["margen_pasajeros"]*$value["margen_monto_unitario"];
                        }
                        
                        $suma_margen_egreso += $margen_monto_total;
                    ?>
                        <tr>
                            <td style="width: 30%; text-align: center;" class="borde">
                                <?php echo $value["margen_nombre"]; ?>
                            </td>
                            <td style="width: 25%; text-align: right;" class="borde">
                                <?php echo number_format($value["margen_cantidad"], 2, ',', '.'); ?>
                            </td>
                            <td style="width: 15%; text-align: right;" class="borde">
                                <?php echo $value["margen_unidad_medida"]; ?>
                            </td>
                            <td style="width: 15%; text-align: right;" class="borde">
                                <?php echo number_format($value["margen_monto_unitario"], 2, ',', '.'); ?>
                            </td>
                            <td style="width: 15%; text-align: right;" class="borde">
                                <?php echo number_format($margen_monto_total, 2, ',', '.'); ?>
                            </td>
                        </tr>

                    <?php
                    }
                    ?>

                    <tr>
                        <td colspan="4" style="text-align: right; font-weight: bold;" class="borde_titulo">
                            TOTAL B => 
                        </td>
                        <td style="text-align: right;" class="borde_titulo">
                            <?php echo number_format($suma_margen_egreso, 2, ',', '.'); ?>
                        </td>
                    </tr>
                        
                </table>
            
                <br />
            
            <?php
            }
                
            $arrIngresos = $this->mfunciones_logica->ObtenerListaMargen($arrLead[0]['prospecto_id'], -1, 1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrIngresos);
            
            if (!isset($arrIngresos[0])) 
            {
                echo 'No se Registró Información de Ingresos <br /><br />';
            }
            else
            {
            ?>
            
                <table align="center" style="width: 100%;" cellspacing="0">
                    <tr>
                        <th colspan="6" style="width: 30%; text-align: center; font-weight: bold;" class="borde">
                            INFORMACIÓN DE INGRESOS
                        </th>
                    </tr>
                    <tr>
                        <th style="width: 25%; text-align: center; font-weight: bold;" class="borde">
                            <?php echo $this->lang->line('margen_nombre'); ?>
                        </th>
                        <th style="width: 15%; text-align: center; font-weight: bold;" class="borde">
                            <?php echo $this->lang->line('margen_cantidad'); ?>
                        </th>
                        <th style="width: 15%; text-align: center; font-weight: bold;" class="borde">
                            <?php echo $this->lang->line('margen_unidad_medida'); ?>
                        </th>
                        <th style="width: 15%; text-align: center; font-weight: bold;" class="borde">
                            <?php echo $this->lang->line('margen_pasajeros'); ?>
                        </th>
                        <th style="width: 15%; text-align: center; font-weight: bold;" class="borde">
                            Precio Unitario
                        </th>
                        <th style="width: 15%; text-align: center; font-weight: bold;" class="borde">
                            Ingreso Total
                        </th>
                    </tr>

                    <?php
                    
                    foreach ($arrIngresos as $key => $value) 
                    {
                        if($value['margen_tipo'] == 0)
                        {
                            $margen_monto_total = $value["margen_cantidad"]*$value["margen_monto_unitario"];
                        }
                        else
                        {
                            $margen_monto_total = $value["margen_cantidad"]*$value["margen_pasajeros"]*$value["margen_monto_unitario"];
                        }
                        
                        $suma_margen_ingreso += $margen_monto_total;
                    ?>
                        <tr>
                            <td style="width: 25%; text-align: center;" class="borde">
                                <?php echo $value["margen_nombre"]; ?>
                            </td>
                            <td style="width: 15%; text-align: right;" class="borde">
                                <?php echo number_format($value["margen_cantidad"], 2, ',', '.'); ?>
                            </td>
                            <td style="width: 15%; text-align: right;" class="borde">
                                <?php echo $value["margen_unidad_medida"]; ?>
                            </td>
                            <td style="width: 15%; text-align: right;" class="borde">
                                <?php echo number_format($value["margen_pasajeros"], 2, ',', '.'); ?>
                            </td>
                            <td style="width: 15%; text-align: right;" class="borde">
                                <?php echo number_format($value["margen_monto_unitario"], 2, ',', '.'); ?>
                            </td>
                            <td style="width: 15%; text-align: right;" class="borde">
                                <?php echo number_format($margen_monto_total, 2, ',', '.'); ?>
                            </td>
                        </tr>

                    <?php
                    }
                    ?>

                    <tr>
                        <td colspan="5" style="text-align: right; font-weight: bold;" class="borde_titulo">
                            TOTAL A => 
                        </td>
                        <td style="text-align: right;" class="borde_titulo">
                            <?php echo number_format($suma_margen_ingreso, 2, ',', '.'); ?>
                        </td>
                    </tr>
                        
                </table>
            
                <br />
                
            <?php
            }
            ?>
                
            <table align="center" style="width: 80%;" cellspacing="0">
                <tr>
                    <td style="width: 34%; text-align: center; font-weight: bold;" class="borde_titulo">
                        MARGEN DE UTILIDAD BRUTA
                    </td>
                    <td style="width: 33%; text-align: center; font-weight: bold;" class="borde_titulo">
                        ( A - B ) / A )*100
                    </td>
                    <td style="width: 33%; text-align: center; font-weight: bold;" class="borde_titulo">
                        <?php echo number_format($calculoLead->margen_utilidad_bruta*100, 2, ',', '.'); ?>%
                    </td>
                </tr>
            </table>

            <br />
        
        <?php
        }
        
        // ********** TRANSPORTE FIN **********

        // SECCIÓN: FRECUENCIA VENTAS

        if(in_array("frecuencia_venta", $this->mfunciones_generales->getVistasRubro($rubro_unidad)))
        {
        ?>

            <div class="titulo_seccion" style="width: 100%; text-justify: inter-word; font-weight: bold;">
                VENTAS SEGÚN FRECUENCIA (DIARIO, SEMANAL, QUINCENAL, MENSUAL)
            </div>

            <?php
            if($frecuencia_seleccionada == 1)
            {
                // Diaria
                
                $suma_frecuencia = 0;
                
                $suma_frecuencia += $arrLead[0]['frec_dia_lunes'];
                $suma_frecuencia += $arrLead[0]['frec_dia_martes'];
                $suma_frecuencia += $arrLead[0]['frec_dia_miercoles'];
                $suma_frecuencia += $arrLead[0]['frec_dia_jueves'];
                $suma_frecuencia += $arrLead[0]['frec_dia_viernes'];
                $suma_frecuencia += $arrLead[0]['frec_dia_sabado'];
                $suma_frecuencia += $arrLead[0]['frec_dia_domingo'];
                
                
                $frecuencia_dia_monto2 = $arrLead[0]['frec_dia_semana_monto2'];
                $frecuencia_dia_monto3 = $arrLead[0]['frec_dia_semana_monto3'];
                
                $frecuencia_dia_brm_monto1 = 0;
                $frecuencia_dia_brm_monto2 = 0;
                $frecuencia_dia_brm_monto3 = 0;
                
                switch ($arrLead[0]['frec_dia_semana_sel_brm'])
                {
                    case 1:

                        $frecuencia_dia_brm1 = "Buena";
                        $frecuencia_dia_brm2 = "Regular";
                        $frecuencia_dia_brm3 = "Mala";
                        
                        $frecuencia_dia_monto_bueno = $suma_frecuencia;
                        $frecuencia_dia_monto_regular = $frecuencia_dia_monto2;
                        $frecuencia_dia_monto_malo = $frecuencia_dia_monto3;
                        
                        break;

                    case 2:

                        $frecuencia_dia_brm1 = "Regular";
                        $frecuencia_dia_brm2 = "Buena";
                        $frecuencia_dia_brm3 = "Mala";
                        
                        $frecuencia_dia_monto_bueno = $frecuencia_dia_monto2;
                        $frecuencia_dia_monto_regular = $suma_frecuencia;
                        $frecuencia_dia_monto_malo = $frecuencia_dia_monto3;
                        
                        break;
                    
                    case 3:

                        $frecuencia_dia_brm1 = "Mala";
                        $frecuencia_dia_brm2 = "Buena";
                        $frecuencia_dia_brm3 = "Regular";
                        
                        $frecuencia_dia_monto_bueno = $frecuencia_dia_monto2;
                        $frecuencia_dia_monto_regular = $frecuencia_dia_monto3;
                        $frecuencia_dia_monto_malo = $suma_frecuencia;
                        
                        break;
                    
                    default:
                        
                        $frecuencia_dia_brm1 = "Mala";
                        $frecuencia_dia_brm2 = "Buena";
                        $frecuencia_dia_brm3 = "Regular";
                        
                        $frecuencia_dia_monto_bueno = $frecuencia_dia_monto2;
                        $frecuencia_dia_monto_regular = $frecuencia_dia_monto3;
                        $frecuencia_dia_monto_malo = $suma_frecuencia;
                        
                        break;
                }
                
                $frecuencia_dia_brm_monto1 = number_format($suma_frecuencia, 2, ',', '.');
                $frecuencia_dia_brm_monto2 = number_format($frecuencia_dia_monto2, 2, ',', '.');
                $frecuencia_dia_brm_monto3 = number_format($frecuencia_dia_monto3, 2, ',', '.');
                
                
            ?>
                <br />

                <div style="width: 100%; text-align: justify; text-justify: inter-word; font-weight: bold;">
                    FRECUENCIA DIARIA (Cuando el cliente vende todos los días, se debe recabar información de la última semana, ubicar la semana de evaluación dentro del mes, completar las demas semanas y categorizar cada una de ellas en BUENA, REGULAR, MALA)
                </div>

                <br />

                <table style="width: 100%;" cellspacing="0" cellpadding="5">
                    <tr>
                        <td style="width: 30%;" valign="top">

                            <table style="width: 100%;" cellspacing="0">

                                <tr>
                                    <td style="width: 50%;" class="borde_caqui">
                                        DÍA
                                    </td>
                                    <td style="width: 50%;" class="borde_caqui">
                                        IMPORTE BS.
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 50%; text-align: right;" class="borde_titulo">
                                        LUNES
                                    </td>
                                    <td style="width: 50%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['frec_dia_lunes'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; text-align: right;" class="borde_titulo">
                                        MARTES
                                    </td>
                                    <td style="width: 50%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['frec_dia_martes'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; text-align: right;" class="borde_titulo">
                                        MIERCOLES
                                    </td>
                                    <td style="width: 50%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['frec_dia_miercoles'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; text-align: right;" class="borde_titulo">
                                        JUEVES
                                    </td>
                                    <td style="width: 50%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['frec_dia_jueves'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; text-align: right;" class="borde_titulo">
                                        VIERNES
                                    </td>
                                    <td style="width: 50%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['frec_dia_viernes'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; text-align: right;" class="borde_titulo">
                                        SÁBADO
                                    </td>
                                    <td style="width: 50%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['frec_dia_sabado'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; text-align: right;" class="borde_titulo">
                                        DOMINGO
                                    </td>
                                    <td style="width: 50%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['frec_dia_domingo'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; text-align: right;" class="borde_titulo">
                                        TOTAL
                                    </td>
                                    <td style="width: 50%; text-align: right;" class="borde_titulo">
                                        <?php
                                        
                                        $frecuencia_dias_total = $arrLead[0]['frec_dia_lunes'] + $arrLead[0]['frec_dia_martes'] + $arrLead[0]['frec_dia_miercoles'] + $arrLead[0]['frec_dia_jueves'] + $arrLead[0]['frec_dia_viernes'] + $arrLead[0]['frec_dia_sabado'] + $arrLead[0]['frec_dia_domingo'];
                                        
                                        echo number_format($frecuencia_dias_total, 2, ',', '.');
                                        
                                        ?>
                                    </td>
                                </tr>

                            </table>


                        </td>

                        <td style="width: 35%;" valign="top">

                            <br /><br />

                            <table style="width: 100%;" cellspacing="0">

                                <tr>
                                    <td style="width: 50%; text-align: center; font-size: 8px;" class="">
                                        UBICACIÓN SEMANA
                                    </td>
                                    <td style="width: 50%; text-align: center; text-transform: uppercase;" class="borde_titulo">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['frec_dia_semana_sel'], 'frec_dia_semana_sel'); ?>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td style="width: 50%; text-align: center; font-size: 8px;" class="">
                                        CATEGORIZACIÓN
                                    </td>
                                    <td style="width: 50%; text-align: center; text-transform: uppercase;" class="borde_titulo">
                                        <?php echo $frecuencia_dia_brm1; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 50%; text-align: center; font-size: 8px;" class="">

                                    </td>
                                    <td style="width: 50%; text-align: right;" class="borde">
                                        <?php echo $frecuencia_dia_brm_monto1; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 50%; text-align: center; font-size: 8px;" class="">
                                        &nbsp;
                                    </td>
                                    <td style="width: 50%; text-align: center;" class="">
                                        &nbsp;
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 50%; text-align: center; text-transform: uppercase;" class="borde">
                                        <?php echo $frecuencia_dia_brm2; ?>
                                    </td>
                                    <td style="width: 50%; text-align: right;" class="borde">
                                        <?php echo $frecuencia_dia_brm_monto2; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 50%; text-align: center; text-transform: uppercase;" class="borde">
                                        <?php echo $frecuencia_dia_brm3; ?>
                                    </td>
                                    <td style="width: 50%; text-align: right;" class="borde">
                                        <?php echo $frecuencia_dia_brm_monto3; ?>
                                    </td>
                                </tr>

                            </table>
                        </td>

                        <td style="width: 35%;" valign="top">
                            <br /><br />

                            <?php
                            
                            $frecuencia_primera_monto = 0;
                            $frecuencia_segunda_monto = 0;
                            $frecuencia_tercera_monto = 0;
                            $frecuencia_cuarta_monto = 0;
                            
                            switch ($arrLead[0]['frec_dia_eval_semana1_brm']) {
                                case 1:
                                    
                                    $frecuencia_primera_monto = $frecuencia_dia_monto_bueno;

                                    break;
                                
                                case 2:
                                    
                                    $frecuencia_primera_monto = $frecuencia_dia_monto_regular;

                                    break;
                                
                                case 3:
                                    
                                    $frecuencia_primera_monto = $frecuencia_dia_monto_malo;

                                    break;

                                default:
                                    
                                    $frecuencia_primera_monto = 0;
                                    
                                    break;
                            }
                            
                            switch ($arrLead[0]['frec_dia_eval_semana2_brm']) {
                                case 1:

                                    $frecuencia_segunda_monto = $frecuencia_dia_monto_bueno;

                                    break;

                                case 2:

                                    $frecuencia_segunda_monto = $frecuencia_dia_monto_regular;

                                    break;

                                case 3:

                                    $frecuencia_segunda_monto = $frecuencia_dia_monto_malo;

                                    break;

                                default:

                                    $frecuencia_segunda_monto = 0;

                                    break;
                            }
                            
                            switch ($arrLead[0]['frec_dia_eval_semana3_brm']) {
                                case 1:

                                    $frecuencia_tercera_monto = $frecuencia_dia_monto_bueno;

                                    break;

                                case 2:

                                    $frecuencia_tercera_monto = $frecuencia_dia_monto_regular;

                                    break;

                                case 3:

                                    $frecuencia_tercera_monto = $frecuencia_dia_monto_malo;

                                    break;

                                default:

                                    $frecuencia_tercera_monto = 0;

                                    break;
                            }
                            
                            switch ($arrLead[0]['frec_dia_eval_semana4_brm']) {
                                case 1:

                                    $frecuencia_cuarta_monto = $frecuencia_dia_monto_bueno;

                                    break;

                                case 2:

                                    $frecuencia_cuarta_monto = $frecuencia_dia_monto_regular;

                                    break;

                                case 3:

                                    $frecuencia_cuarta_monto = $frecuencia_dia_monto_malo;

                                    break;

                                default:

                                    $frecuencia_cuarta_monto = 0;

                                    break;
                            }
                            
                            ?>
                            
                            <table style="width: 100%;" cellspacing="0">

                                <tr>
                                    <td style="width: 33%; text-align: center;" class="borde_caqui">
                                        PRIMERA
                                    </td>
                                    <td style="width: 33%; text-align: center;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['frec_dia_eval_semana1_brm'], 'brm'); ?>
                                    </td>
                                    <td style="width: 34%; text-align: right;" class="borde_caqui">
                                        <?php echo number_format($frecuencia_primera_monto, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 33%; text-align: center;" class="borde_caqui">
                                        SEGUNDA
                                    </td>
                                    <td style="width: 33%; text-align: center;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['frec_dia_eval_semana2_brm'], 'brm'); ?>
                                    </td>
                                    <td style="width: 34%; text-align: right;" class="borde_caqui">
                                        <?php echo number_format($frecuencia_segunda_monto, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 33%; text-align: center;" class="borde_caqui">
                                        TERCERA
                                    </td>
                                    <td style="width: 33%; text-align: center;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['frec_dia_eval_semana3_brm'], 'brm'); ?>
                                    </td>
                                    <td style="width: 34%; text-align: right;" class="borde_caqui">
                                        <?php echo number_format($frecuencia_tercera_monto, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 33%; text-align: center;" class="borde_caqui">
                                        CUARTA
                                    </td>
                                    <td style="width: 33%; text-align: center;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['frec_dia_eval_semana4_brm'], 'brm'); ?>
                                    </td>
                                    <td style="width: 34%; text-align: right;" class="borde_caqui">
                                        <?php echo number_format($frecuencia_cuarta_monto, 2, ',', '.'); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2" style="width: 66%; text-align: center; font-weight: bold;" class="">
                                        TOTAL VENTA MENSUAL
                                    </td>
                                    <td style="width: 34%; text-align: right;" class="borde_titulo">
                                        <?php 
                                        
                                        $frecuencia_venta_mensual =  $frecuencia_primera_monto + $frecuencia_segunda_monto + $frecuencia_tercera_monto + $frecuencia_cuarta_monto;
                                        
                                        echo number_format($frecuencia_venta_mensual, 2, ',', '.');
                                        
                                        ?>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>

                </table>

            <?php
            }

            if($frecuencia_seleccionada == 2)
            {
                // Semanal o Quincenal
                
            ?>
                <br /><br />

                <div style="width: 100%; text-align: justify; text-justify: inter-word; font-weight: bold;">
                    VARIACION DE LAS VENTAS DE LAS OTRAS SEMANAS RESPECTO A LA SEMANA DE REFERNCIA SEGÚN SU CATEGORIZACIÓN
                </div>

                <br />

                <table style="width: 90%;" cellspacing="0">

                    <tr>
                        <td style="width: 20%;" class="borde_caqui">
                            SEMANA
                        </td>
                        <td style="width: 20%;" class="borde_caqui">
                            SEMANA 1
                        </td>
                        <td style="width: 20%;" class="borde_caqui">
                            SEMANA 2
                        </td>
                        <td style="width: 20%;" class="borde_caqui">
                            SEMANA 3
                        </td>
                        <td style="width: 20%;" class="borde_caqui">
                            SEMANA 4
                        </td>
                    </tr>

                    <tr>
                        <td style="width: 20%;" class="borde_caqui">
                            IMPORTE BS.
                        </td>
                        <td style="width: 20%; text-align: right;" class="borde">
                            <?php echo number_format($arrLead[0]['frec_sem_semana1_monto'], 2, ',', '.'); ?>
                        </td>
                        <td style="width: 20%; text-align: right;" class="borde">
                            <?php echo number_format($arrLead[0]['frec_sem_semana2_monto'], 2, ',', '.'); ?>
                        </td>
                        <td style="width: 20%; text-align: right;" class="borde">
                            <?php echo number_format($arrLead[0]['frec_sem_semana3_monto'], 2, ',', '.'); ?>
                        </td>
                        <td style="width: 20%; text-align: right;" class="borde">
                            <?php echo number_format($arrLead[0]['frec_sem_semana4_monto'], 2, ',', '.'); ?>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4" style="width: 80%; font-weight: bold; text-align: right;" class="">
                            TOTAL MENSUAL
                        </td>
                        <td style="width: 20%; text-align: right;" class="borde_titulo">
                            <?php 
                                        
                            $frecuencia_venta_mensual =  $arrLead[0]['frec_sem_semana1_monto'] + $arrLead[0]['frec_sem_semana2_monto'] + $arrLead[0]['frec_sem_semana3_monto'] + $arrLead[0]['frec_sem_semana4_monto'];

                            echo number_format($frecuencia_venta_mensual, 2, ',', '.');

                            ?>
                        </td>
                    </tr>

                </table>

            <?php
            }

            if($frecuencia_seleccionada == 3)
            {
                // Mensual
                
                switch ($arrLead[0]['frec_mes_sel']) {
                    case 1:
                        $frecuncia_mes_texto1 = 'ENERO';
                        $frecuncia_mes_texto2 = 'FEBRERO';
                        $frecuncia_mes_texto3 = 'MARZO';
                        break;

                    case 2:
                        $frecuncia_mes_texto1 = 'FEBRERO';
                        $frecuncia_mes_texto2 = 'MARZO';
                        $frecuncia_mes_texto3 = 'ABRIL';
                        break;
                    
                    case 3:
                        $frecuncia_mes_texto1 = 'MARZO';
                        $frecuncia_mes_texto2 = 'ABRIL';
                        $frecuncia_mes_texto3 = 'MAYO';
                        break;
                    
                    case 4:
                        $frecuncia_mes_texto1 = 'ABRIL';
                        $frecuncia_mes_texto2 = 'MAYO';
                        $frecuncia_mes_texto3 = 'JUNIO';
                        break;
                    
                    case 5:
                        $frecuncia_mes_texto1 = 'MAYO';
                        $frecuncia_mes_texto2 = 'JUNIO';
                        $frecuncia_mes_texto3 = 'JULIO';
                        break;
                    
                    case 6:
                        $frecuncia_mes_texto1 = 'JUNIO';
                        $frecuncia_mes_texto2 = 'JULIO';
                        $frecuncia_mes_texto3 = 'AGOSTO';
                        break;
                    
                    case 7:
                        $frecuncia_mes_texto1 = 'JULIO';
                        $frecuncia_mes_texto2 = 'AGOSTO';
                        $frecuncia_mes_texto3 = 'SEPTIEMBRE';
                        break;
                    
                    case 8:
                        $frecuncia_mes_texto1 = 'AGOSTO';
                        $frecuncia_mes_texto2 = 'SEPTIEMBRE';
                        $frecuncia_mes_texto3 = 'OCTUBRE';
                        break;
                    
                    case 9:
                        $frecuncia_mes_texto1 = 'SEPTIEMBRE';
                        $frecuncia_mes_texto2 = 'OCTUBRE';
                        $frecuncia_mes_texto3 = 'NOVIEMBRE';
                        break;
                    
                    case 10:
                        $frecuncia_mes_texto1 = 'OCTUBRE';
                        $frecuncia_mes_texto2 = 'NOVIEMBRE';
                        $frecuncia_mes_texto3 = 'DICIEMBRE';
                        break;
                    
                    case 11:
                        $frecuncia_mes_texto1 = 'NOVIEMBRE';
                        $frecuncia_mes_texto2 = 'DICIEMBRE';
                        $frecuncia_mes_texto3 = 'ENERO';
                        break;
                    
                    case 12:
                        $frecuncia_mes_texto1 = 'DICIEMBRE';
                        $frecuncia_mes_texto2 = 'ENERO';
                        $frecuncia_mes_texto3 = 'FEBRERO';
                        break;
                    
                    default:
                        break;
                }
                
            ?>
                <br />

                <div style="width: 100%; text-align: justify; text-justify: inter-word; font-weight: bold;">
                    FRECUENCIA MENSUAL (Cuando el cliente vende una vez por mes, se puede utilizar tambien cuando existe registros o respaldos)
                </div>

                <br />

                <table style="width: 90%;" cellspacing="0">

                    <tr>
                        <td style="width: 25%;" class="borde_caqui">
                            MES (ult. 3 meses)
                        </td>
                        <td style="width: 25%;" class="borde_caqui">
                            <?php echo $frecuncia_mes_texto1; ?>
                        </td>
                        <td style="width: 25%;" class="borde_caqui">
                            <?php echo $frecuncia_mes_texto2; ?>
                        </td>
                        <td style="width: 25%;" class="borde_caqui">
                            <?php echo $frecuncia_mes_texto3; ?>
                        </td>
                    </tr>

                    <tr>
                        <td style="width: 25%;" class="borde_caqui">
                            IMPORTE BS.
                        </td>
                        <td style="width: 25%; text-align: right;" class="borde">
                            <?php echo number_format($arrLead[0]['frec_mes_mes1_monto'], 2, ',', '.'); ?>
                        </td>
                        <td style="width: 25%; text-align: right;" class="borde">
                            <?php echo number_format($arrLead[0]['frec_mes_mes2_monto'], 2, ',', '.'); ?>
                        </td>
                        <td style="width: 25%; text-align: right;" class="borde">
                            <?php echo number_format($arrLead[0]['frec_mes_mes3_monto'], 2, ',', '.'); ?>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" style="width: 75%; font-weight: bold; text-align: right;" class="">
                            PROMEDIO MENSUAL
                        </td>
                        <td style="width: 25%; text-align: right;" class="borde_titulo">
                            <?php 
                            
                            // Adecuacion promedio condicional
                            
                            $contador_condicional = 0;

                            if($arrLead[0]['frec_mes_mes1_monto'] != 0.00) { $contador_condicional++; }
                            if($arrLead[0]['frec_mes_mes2_monto'] != 0.00) { $contador_condicional++; }
                            if($arrLead[0]['frec_mes_mes3_monto'] != 0.00) { $contador_condicional++; }
                            
                            $frecuencia_venta_mensual = ($arrLead[0]['frec_mes_mes1_monto'] + $arrLead[0]['frec_mes_mes2_monto'] + $arrLead[0]['frec_mes_mes3_monto'])/($contador_condicional==0?1:$contador_condicional);

                            echo number_format($frecuencia_venta_mensual, 2, ',', '.');

                            ?>
                        </td>
                    </tr>

                </table>

            <?php
            }
            ?>

            <br /><br />

            <table align="center" style="width: 90%;">
                <tr>
                    <td style="width: 80%; text-align: right; font-weight: bold;">
                        TOTAL INGRESO MENSUAL POR DECLARACION DE INGRESOS
                    </td>
                    <td style="width: 20%; text-align: right; font-weight: bold;" class="borde_titulo">
                        <?php echo number_format($frecuencia_venta_mensual, 2, ',', '.'); ?>
                    </td>
                </tr>
            </table>

        <?php
        }
        
        // SECCIÓN: DATOS GENERALES

        if(in_array("margen_utilidad", $this->mfunciones_generales->getVistasRubro($rubro_unidad)))
        {
        ?>

            <br />
            
            <div class="titulo_seccion" style="width: 100%; text-justify: inter-word; font-weight: bold;">
                MARGEN DE UTILIDAD BRUTA DE PRINCIPALES PRODUCTOS COMERCIALIZADOS
            </div>
        
            <?php
            
            // 3. Datos del margen
        
            $arrMargen = $this->mfunciones_logica->ObtenerListaProductos($arrLead[0]['prospecto_id'], -1, 1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrMargen);
            
            if(count($arrMargen)>0)
            {
            
            ?>
            
                <table align="center" style="width: 100%;" cellspacing="0">
                    <tr>
                        <th style="width: 20%; text-align: center; font-weight: bold;" class="borde">
                            DESCRIPCION DEL PRODUCTO
                        </th>
                        <th style="width: 10%; text-align: center; font-weight: bold;" class="borde">
                            FRECUENCIA EN DIAS
                        </th>
                        <th style="width: 10%; text-align: center; font-weight: bold;" class="borde">
                            CANTIDAD VENDIDA
                        </th>
                        <th style="width: 10%; text-align: center; font-weight: bold;" class="borde">
                            UNIDAD DE MEDIDA DE VENTA
                        </th>
                        <th style="width: 10%; text-align: center; font-weight: bold;" class="borde">
                            COSTO UNITARIO Bs.
                        </th>
                        <th style="width: 10%; text-align: center; font-weight: bold;" class="borde">
                            PRECIO VENTA Bs.
                        </th>
                        <th style="width: 10%; text-align: center; font-weight: bold;" class="borde">
                            COSTO TOTAL Bs.
                        </th>
                        <th style="width: 10%; text-align: center; font-weight: bold;" class="borde">
                            INGRESO TOTAL Bs.
                        </th>
                        <th style="width: 10%; text-align: center; font-weight: bold;" class="borde">
                            MUB
                        </th>
                    </tr>

                    <?php

                    $suma_costo_total = 0;
                    $suma_ingreso_total = 0;
                    
                    foreach ($arrMargen as $key => $value) 
                    {
                    ?>
                        <tr>
                            <td style="width: 20%; text-align: center;" class="borde">
                                <?php echo $value['producto_nombre']; ?>
                            </td>
                            <td style="width: 10%; text-align: center;" class="borde">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($value['producto_frecuencia'], 'frecuencia_dias'); ?>
                            </td>
                            <td style="width: 10%; text-align: right;" class="borde">
                                <?php echo $value['producto_venta_cantidad']; ?>
                            </td>
                            <td style="width: 10%; text-align: center;" class="borde">
                                <?php echo $value['producto_venta_medida']; ?>
                            </td>
                            <td style="width: 10%; text-align: right;" class="borde">
                                <?php
                                
                                    $costo_unitario = $this->mfunciones_generales->ValorCostoProducto($value["producto_id"], $value["producto_opcion"], $value["producto_venta_costo"], $value["producto_compra_precio"], $value["producto_unidad_venta_unidad_compra"], $value["producto_costo_medida_cantidad"]); 
                                    
                                    echo number_format($costo_unitario, 2, ',', '.');
                                ?>
                            </td>
                            <td style="width: 10%; text-align: right;" class="borde">
                                <?php  echo number_format($value['producto_venta_precio'], 2, ',', '.'); ?>
                            </td>
                            <td style="width: 10%; text-align: right;" class="borde">
                                <?php 
                                    
                                    $costo_total = $this->mfunciones_generales->CalculoFecuencia($value["producto_venta_cantidad"], $costo_unitario, $value["producto_frecuencia"]);
                                    echo number_format($costo_total, 2, ',', '.');
                                
                                ?>
                            </td>
                            <td style="width: 10%; text-align: right;" class="borde">
                                <?php 
                                    
                                    $ingreso_total = $this->mfunciones_generales->CalculoFecuencia($value["producto_venta_cantidad"], $value["producto_venta_precio"], $value["producto_frecuencia"]);
                                    echo number_format($ingreso_total, 2, ',', '.');
                                
                                ?>
                            </td>
                            <td style="width: 10%; text-align: right;" class="borde">
                                <?php echo number_format(($ingreso_total!=0 ? (1-($costo_total/$ingreso_total))*100 : 0), 2, ',', '.'); ?>%
                            </td>
                        </tr>

                    <?php
                    
                        $suma_costo_total += $costo_total;
                        $suma_ingreso_total += $ingreso_total;
                    
                    }
                    ?>

                    <tr>
                        <td colspan="6" style="width: 70%; text-align: left; font-weight: bold;" class="borde">
                            TOTAL
                        </td>
                        <td style="width: 10%; text-align: right;" class="borde">
                            <?php echo number_format($suma_costo_total, 2, ',', '.'); ?>
                        </td>
                        <td style="width: 10%; text-align: right;" class="borde">
                            <?php echo number_format($suma_ingreso_total, 2, ',', '.'); ?>
                        </td>
                        <td style="width: 10%; text-align: right;" class="borde">
                            <?php 
                            
                            $mub_total = ($suma_ingreso_total!=0 ? (1-($suma_costo_total/$suma_ingreso_total)) : 0);
                            
                            echo number_format($mub_total*100, 2, ',', '.');
                            
                            ?>%
                        </td>

                    </tr>
                        
                </table>
            
                <br /><br />
            
                <table align="center" style="width: 100%;" cellspacing="0">
                    <tr>
                        <td style="width: 65%; text-align: right;" class="">
                            % DE PARTICIPACION DE PRINCIPALES PRODUCTOS EN LAS VENTAS
                        </td>
                        
                        <td style="width: 20%; text-align: center;" class="borde_titulo">
                            <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['margen_utilidad_productos'], 'combo_margen'); ?>
                        </td>
                        <td style="width: 15%; text-align: center;" class="borde">
                            <?php 
                            
                            $margen_utilidad_productos = $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['margen_utilidad_productos'], 'combo_margen_valor');
                            
                            echo $margen_utilidad_productos;
                            
                            ?>%
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 85%; text-align: right; font-weight: bold;" class="">
                            PROYECCIÓN DE VENTAS
                        </td>
                        <td style="width: 15%; text-align: right;" class="borde">
                            <?php echo number_format(($margen_utilidad_productos!=0 ? $suma_ingreso_total/($margen_utilidad_productos/100) : 0), 2, ',', '.'); ?>
                        </td>
                    </tr>
                </table>
    
    <?php
            }
            else
            {
                echo $this->lang->line('TablaNoRegistros');
            }
    
        }
        // SECCIÓN: DATOS PROVEEDORES

        if(in_array("proveedores", $this->mfunciones_generales->getVistasRubro($rubro_unidad)))
        {
        ?>
            <br />
            
            <div class="titulo_seccion" style="width: 100%; text-justify: inter-word; font-weight: bold;">
                COMPRAS A PRINCIPALES PROVEEDORES
            </div>
            <br />
            <div class="" style="width: 100%; text-justify: inter-word; font-weight: bold; background-color: #ffffff; color: #000000;">
                Obtener información sobre compras a principales proveedores, frecuencia de compra, importe promedio por cada compra, fecha de la última compra
            </div>
            <br />
            
            <?php
            
            // 4. Datos de Proveedores
        
            $arrProveedor = $this->mfunciones_logica->ObtenerListaProovedor($arrLead[0]['prospecto_id'], -1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrProveedor);
            
            if(count($arrProveedor)>0)
            {
            
            ?>
            
                <table align="center" style="width: 100%;" cellspacing="0">
                    <tr>
                        <th rowspan="2" style="width: 25%; text-align: center; font-weight: bold;" class="borde">
                            DATOS DEL PROVEEDOR
                        </th>
                        <th rowspan="2" style="width: 15%; text-align: center; font-weight: bold;" class="borde">
                            LUGAR DE COMPRA
                        </th>
                        <th colspan="3" style="width: 15%; text-align: center; font-weight: bold;" class="borde">
                            INFORMACION DE COMPRAS
                        </th>
                        <th rowspan="2" style="width: 15%; text-align: center; font-weight: bold;" class="borde">
                            PROMEDIO MENSUAL
                        </th>
                    </tr>
                    
                    <tr>
                        <th style="width: 15%; text-align: center; font-weight: bold;" class="borde">
                            FRECUENCIA DE COMPRA
                        </th>
                        <th style="width: 15%; text-align: center; font-weight: bold;" class="borde">
                            IMPORTE Bs.
                        </th>
                        <th style="width: 15%; text-align: center; font-weight: bold;" class="borde">
                            FECHA DE ULTIMA COMPRA
                        </th>
                    </tr>
                    
                    <?php
                    
                    $promedio_mensual_total = 0;
                    
                    foreach ($arrProveedor as $key => $value) 
                    {
                    ?>
                        <tr>
                            <td style="width: 25%; text-align: center;" class="borde">
                                <?php echo $value['proveedor_nombre']; ?>
                            </td>
                            <td style="width: 15%; text-align: center;" class="borde">
                                <?php echo $value['proveedor_lugar_compra']; ?>
                            </td>
                            <td style="width: 15%; text-align: right;" class="borde">
                                <?php echo $value['proveedor_frecuencia_dias']; ?>
                            </td>
                            <td style="width: 15%; text-align: right;" class="borde">
                                <?php echo number_format($value['proveedor_importe'], 2, ',', '.'); ?>
                            </td>
                            <td style="width: 15%; text-align: center;" class="borde">
                                <?php echo $this->mfunciones_generales->getFormatoFechaD_M_Y($value['proveedor_fecha_ultima']); ?>
                            </td>
                            <td style="width: 15%; text-align: right;" class="borde">
                                <?php
                                
                                $promedio_mensual = ($value['proveedor_frecuencia_dias']!=0 ? (30/$value['proveedor_frecuencia_dias'])*$value['proveedor_importe'] : 0);
                                echo number_format($promedio_mensual, 2, ',', '.');
                                
                                $promedio_mensual_total += $promedio_mensual;
                                
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            
                <br /><br />
                
                <table align="center" style="width: 100%;" cellspacing="0">
                    <tr>
                        <td colspan="2" style="width: 85%; text-align: right;" class="">
                            PROMEDIO DE COMPRAS A PRINCIPALES PROVEEDORES
                        </td>
                        <td style="width: 15%; text-align: right;" class="borde">
                            <?php  echo number_format($promedio_mensual_total, 2, ',', '.'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 65%; text-align: right;" class="">
                            % DE CONCENTRACION DE COMPRAS EN PRINCIPALES PROVEEDORES
                        </td>
                        <td style="width: 20%; text-align: center;" class="borde_titulo">
                            <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['porcentaje_participacion_proveedores'], 'combo_margen'); ?>
                        </td>
                        <td style="width: 15%; text-align: right;" class="borde">
                            <?php 
                            
                            $porcentaje_participacion_proveedores = $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['porcentaje_participacion_proveedores'], 'combo_margen_valor');
                            
                            echo $porcentaje_participacion_proveedores;
                            
                            ?>%
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 85%; text-align: right;" class="">
                            COMPRAS TOTALES INFERIDAS A PARTIR DE CONCENTRACIÓN EN PRINCIPALES PROVEEDORES
                        </td>
                        <td style="width: 15%; text-align: right;" class="borde">
                            <?php
                            
                            $totales_inferidas = ($porcentaje_participacion_proveedores!=0 ? $promedio_mensual_total/($porcentaje_participacion_proveedores/100) : 0);
                            
                            echo number_format($totales_inferidas, 2, ',', '.'); 
                            
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 85%; text-align: right; font-weight: bold;" class="">
                            PROYECCIÓN DE VENTAS A PARTIR DE COMPRAS
                        </td>
                        <td style="width: 15%; text-align: right;" class="borde">
                            <?php 
                            
                            echo number_format(((1-$mub_total)!=0 ? $totales_inferidas/(1-$mub_total) : 0), 2, ',', '.');
                            
                            ?>
                        </td>
                    </tr>
                </table>
            
            <?php
            }
            else
            {
                echo $this->lang->line('TablaNoRegistros');
            }
            ?>
                
        <?php
        }
        
        // SECCIÓN: COMPRA MATERIA PRIMA

        if(in_array("materia_prima", $this->mfunciones_generales->getVistasRubro($rubro_unidad)))
        {
        ?>
            <br />
            
            <div class="titulo_seccion" style="width: 100%; text-justify: inter-word; font-weight: bold;">
                COMPRA DE MATERIA PRIMA
            </div>
            <br />
            <div class="" style="width: 100%; text-justify: inter-word; font-weight: bold; background-color: #ffffff; color: #000000;">
                Obtener información sobre compras de materia prima, frecuencia de compra, cantidad promedio de compra. Se debe elegir la materia prima principal por producto)
            </div>
            <br />
            
            <?php
            
            // 5. Datos Materia Prima
        
            $arrMateria = $this->mfunciones_logica->ObtenerListaMateria($arrLead[0]['prospecto_id'], -1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrMateria);
            
            if(count($arrMateria)>0)
            {
            
            ?>
            
                <table align="center" style="width: 100%;" cellspacing="0">
                    <tr>
                        <th style="width: 9%; text-align: center; font-weight: bold;" class="borde">
                            MATERIA PRIMA
                        </th>
                        <th style="width: 9%; text-align: center; font-weight: bold;" class="borde">
                            UNIDAD DE COMPRA
                        </th>
                        <th style="width: 9%; text-align: center; font-weight: bold;" class="borde">
                            FRECUENCIA DE COMPRA
                        </th>
                        <th style="width: 9%; text-align: center; font-weight: bold;" class="borde">
                            CANTIDAD QUE COMPRA SEGÚN UNIDAD DE COMPRA
                        </th>
                        <th style="width: 9%; text-align: center; font-weight: bold;" class="borde">
                            UNIDAD DE USO
                        </th>
                        <th style="width: 9%; text-align: center; font-weight: bold;" class="borde">
                            CANTIDAD DE UNIDADES USO POR UNIDAD DE COMPRA
                        </th>
                        <th style="width: 9%; text-align: center; font-weight: bold;" class="borde">
                            UNIDADES DE USO QUE ENTRA POR PROCESO
                        </th>
                        <th style="width: 9%; text-align: center; font-weight: bold;" class="borde">
                            UNIDAD DE MEDIDA DEL PRODUCTO TERMINADO
                        </th>
                        <th style="width: 9%; text-align: center; font-weight: bold;" class="borde">
                            CANTIDAD DE PROD. TERM. POR PROCESO SEGÚN UNIDADES DE USO POR PROCESO
                        </th>
                        <th style="width: 9%; text-align: center; font-weight: bold;" class="borde">
                            PRECIO VENTA UNITARIO Bs. (Producto terminado)
                        </th>
                        <th style="width: 9%; text-align: center; font-weight: bold;" class="borde">
                            INGRESO ESTIMADO MENSUAL Bs.
                        </th>
                    </tr>
                    
                    <?php
                    
                    $promedio_mensual_total = 0;
                    
                    foreach ($arrMateria as $key => $value) 
                    {
                    ?>
                        <tr>
                            <td style="width: 9%; text-align: center;" class="borde">
                                <?php echo $value['materia_nombre']; ?>
                            </td>
                            <td style="width: 9%; text-align: center;" class="borde">
                                <?php echo $value['materia_unidad_compra']; ?>
                            </td>
                            <td style="width: 9%; text-align: center;" class="borde">
                                <?php echo $value['materia_frecuencia']; ?>
                            </td>
                            <td style="width: 9%; text-align: right;" class="borde">
                                <?php echo number_format($value['materia_unidad_compra_cantidad'], 2, ',', '.'); ?>
                            </td>
                            <td style="width: 9%; text-align: center;" class="borde">
                                <?php echo $value['materia_unidad_uso']; ?>
                            </td>
                            <td style="width: 9%; text-align: right;" class="borde">
                                <?php echo number_format($value['materia_unidad_uso_cantidad'], 2, ',', '.'); ?>
                            </td>
                            <td style="width: 9%; text-align: right;" class="borde">
                                <?php echo number_format($value['materia_unidad_proceso'], 2, ',', '.'); ?>
                            </td>
                            <td style="width: 9%; text-align: center;" class="borde">
                                <?php echo $value['materia_producto_medida']; ?>
                            </td>
                            <td style="width: 9%; text-align: right;" class="borde">
                                <?php echo number_format($value['materia_producto_medida_cantidad'], 2, ',', '.'); ?>
                            </td>
                            <td style="width: 9%; text-align: right;" class="borde">
                                <?php echo number_format($value['materia_precio_unitario'], 2, ',', '.'); ?>
                            </td>
                            <td style="width: 9%; text-align: right;" class="borde">
                                <?php
                                
                                $ingreso_estimado = $this->mfunciones_generales->CalculoFecuencia(($value['materia_unidad_compra_cantidad'] * $value['materia_producto_medida_cantidad'] * $value['materia_precio_unitario']), ($value['materia_unidad_proceso']!=0 ? $value['materia_unidad_uso_cantidad'] / $value['materia_unidad_proceso'] : 0), $value['materia_frecuencia']);
                                
                                echo number_format($ingreso_estimado, 2, ',', '.');
                                
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            
                <br /><br />
                
                <table align="center" style="width: 100%;" cellspacing="0">
                    <tr>
                        <td colspan="2" style="width: 85%; text-align: right; font-weight: bold;" class="">
                            TOTAL INGRESO SEGÚN COMPRA DE MATERIA PRIMA
                        </td>
                        <td style="width: 15%; text-align: right;" class="borde">
                            <?php  echo number_format($calculoLead->criterio_materia_prima, 2, ',', '.'); ?>
                        </td>
                    </tr>
                </table>
            
            <?php
            }
            else
            {
                echo $this->lang->line('TablaNoRegistros');
            }
            ?>
                
        <?php
        }
        
        // SECCIÓN: ESTACIONALIDAD

        if(in_array("estacionalidad", $this->mfunciones_generales->getVistasRubro($rubro_unidad)))
        {
        ?>
            <br />
            
            <div class="titulo_seccion" style="width: 100%; text-justify: inter-word; font-weight: bold;">
                SELECCIÓN DEL CRITERIO PARA ESTIMAR LA CAPACIDAD DE PAGO
            </div>
            
            <br />
                
            <table align="left" style="width: 100%;" cellspacing="0">
                <tr>
                    <td valign="top" style="width: 60%; text-align: left;">
                        
                        <table align="left" style="width: 95%;" cellspacing="0">
                           <tr>
                               <td style="width: 5%; text-align: center; font-weight: bold;" class="borde_caqui">
                                   &nbsp;
                               </td>
                               <td colspan="2" style="width: 75%; text-align: center;" class="borde_caqui">
                                   CRITERIO
                               </td>
                               <td style="width: 20%; text-align: center;" class="borde_caqui">
                                   Bs. / MES
                               </td>
                           </tr>
                            <tr>
                                <td style="width: 5%; text-align: center; font-weight: bold;" class="borde">
                                    a)
                                </td>
                                <td colspan="2" style="width: 75%; text-align: justify;" class="borde">
                                    Ventas Declaradas Según Frecuencia del Ingreso
                                </td>
                                <td style="width: 20%; text-align: right;" class="borde">
                                    <?php echo number_format($calculo_lead->criterio_frecuencia_ingreso, 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 5%; text-align: center; font-weight: bold;" class="borde">
                                    b)
                                </td>
                                <td colspan="2" style="width: 75%; text-align: justify;" class="borde">
                                    Ventas por Principales Productos Comercializados
                                </td>
                                <td style="width: 20%; text-align: right;" class="borde">
                                    <?php echo number_format($calculo_lead->criterio_principales_productos, 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 5%; text-align: center; font-weight: bold;" class="borde">
                                    c)
                                </td>
                                <td colspan="2" style="width: 75%; text-align: justify;" class="borde">
                                    <?php echo ($rubro_unidad==3 ? 'Ventas Según Compras a Principales Proveedores' : 'Ventas Según Compras de Materia Prima'); ?>
                                </td>
                                <td style="width: 20%; text-align: right;" class="borde">
                                    <?php echo number_format($calculo_lead->criterio_materia_prima, 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 5%; text-align: center; font-weight: bold;" class="borde">
                                    d)
                                </td>
                                <td colspan="2" style="width: 75%; text-align: justify;" class="borde">
                                    Cruce Personalizado
                                </td>
                                <td style="width: 20%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['capacidad_monto_manual'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 5%; text-align: center; font-weight: bold;" class="">
                                    &nbsp;
                                </td>
                                <td style="width: 65%; text-align: center; font-style: italic;" class="borde">
                                    Criterio seleccionado
                                </td>
                                <td style="width: 10%; text-align: center;" class="borde_titulo">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['capacidad_criterio'], 'criterio_inciso'); ?>
                                </td>
                                <td style="width: 20%; text-align: right;" class="borde_titulo">
                                    <?php echo number_format($calculo_lead->monto_criterio_seleccionado, 2, ',', '.'); ?>
                                </td>
                            </tr>
                        </table>
                        
                        <br /><br />
                        
                        <table align="left" style="width: 100%;" cellspacing="0">
                            <tr>
                                <td style="width: 60%; text-align: left;" class="borde">
                                    La actividad tiene estacionalidad marcada
                                </td>
                                <td style="width: 20%; text-align: center;" class="borde_titulo">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['estacion_sel'], 'si_no'); ?>
                                </td>
                                <td style="width: 20%; text-align: center;" class="">
                                    &nbsp;
                                </td>
                            </tr>
                            <?php
                            if($arrLead[0]['estacion_sel'] == 1)
                            {
                            ?>
                                <tr>
                                    <td style="width: 60%; text-align: right;" class="borde">
                                        Mes de análisis
                                    </td>
                                    <td style="width: 20%; text-align: center; text-transform: uppercase;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['estacion_sel_mes'], 'mes_literal'); ?>
                                    </td>
                                    <td style="width: 20%; text-align: center; font-weight: bold;" class="borde">
                                        Importe Bs.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 60%; text-align: left;" class="borde">
                                        Categorizar el mes de análisis (ALTO, BAJO)
                                    </td>
                                    <td style="width: 20%; text-align: center; font-weight: bold; text-transform: uppercase;" class="borde_titulo">
                                        <?php 

                                        echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['estacion_sel_arb'], 'arb');

                                        switch ($arrLead[0]['estacion_sel_arb']) {
                                            case 1:

                                                $estacionalidad_arb2 = 'Regular';
                                                $estacionalidad_arb3 = 'Bajo';

                                                $estacionalidad_monto_alto = $calculo_lead->monto_criterio_seleccionado;
                                                $estacionalidad_monto_regular = $arrLead[0]['estacion_monto2'];
                                                $estacionalidad_monto_bajo = $arrLead[0]['estacion_monto3'];

                                                break;

                                            case 2:

                                                $estacionalidad_arb2 = 'Alta';
                                                $estacionalidad_arb3 = 'Bajo';

                                                $estacionalidad_monto_alto = $arrLead[0]['estacion_monto2'];
                                                $estacionalidad_monto_regular = $calculo_lead->monto_criterio_seleccionado;
                                                $estacionalidad_monto_bajo = $arrLead[0]['estacion_monto3'];

                                                break;

                                            case 3:

                                                $estacionalidad_arb2 = 'Alta';
                                                $estacionalidad_arb3 = 'Regular';

                                                $estacionalidad_monto_alto = $arrLead[0]['estacion_monto2'];
                                                $estacionalidad_monto_regular = $arrLead[0]['estacion_monto3'];
                                                $estacionalidad_monto_bajo = $calculo_lead->monto_criterio_seleccionado;

                                                break;

                                            default:

                                                $estacionalidad_arb2 = 'Alta';
                                                $estacionalidad_arb3 = 'Regular';

                                                $estacionalidad_monto_alto = $arrLead[0]['estacion_monto2'];
                                                $estacionalidad_monto_regular = $arrLead[0]['estacion_monto3'];
                                                $estacionalidad_monto_bajo = $calculo_lead->monto_criterio_seleccionado;

                                                break;
                                        }


                                        ?>
                                    </td>
                                    <td style="width: 20%; text-align: right;" class="borde">
                                        <?php echo number_format($calculo_lead->monto_criterio_seleccionado, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                            
                            <?php
                            }
                            if($arrLead[0]['estacion_sel'] == 1 && $arrLead[0]['estacion_sel_arb'] != 2)
                            {
                            ?>
                                <tr>
                                    <td rowspan="2" style="width: 60%; text-align: right;" class="borde">
                                        Establecer el ingreso de los meses
                                    </td>
                                    <td style="width: 20%; text-align: center; font-weight: bold; text-transform: uppercase;" class="borde">
                                        <?php echo $estacionalidad_arb2; ?>
                                    </td>
                                    <td style="width: 20%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['estacion_monto2'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 20%; text-align: center; font-weight: bold; text-transform: uppercase;" class="borde">
                                        <?php echo $estacionalidad_arb3; ?>
                                    </td>
                                    <td style="width: 20%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['estacion_monto3'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>
                        
                    </td>
                    <td valign="top" style="width: 40%; text-align: right;">
                        
                        <?php
                        if($arrLead[0]['estacion_sel'] != 0 && $arrLead[0]['estacion_sel_arb'] != 2)
                        {
                        ?>
                        
                            <table align="center" style="width: 100%;" cellspacing="0">
                                <tr>
                                    <td style="width: 34%; text-align: center;" class="borde_caqui">
                                        Meses
                                    </td>
                                    <td style="width: 33%; text-align: center;" class="borde_caqui">
                                        Categorización
                                    </td>
                                    <td style="width: 33%; text-align: center;" class="borde_caqui">
                                        Ingreso Bs.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 34%; text-align: left;" class="borde_titulo">
                                        ENERO
                                    </td>
                                    <td style="width: 33%; text-align: center; text-transform: uppercase;" class="borde">
                                        <?php
                                        // !!!!!!
                                        $estacion_mes = 'estacion_ene_arb';
                                        // !!!!!!
                                        $numero_mes=1; echo ($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['estacion_sel_arb'], 'arb') : $this->mfunciones_generales->GetValorCatalogo($arrLead[0][$estacion_mes], 'arb')); 
                                        ?>
                                    </td>
                                    <td style="width: 33%; text-align: right;" class="borde">
                                        <?php

                                        switch ($arrLead[0][$estacion_mes]) {
                                            case 1: $estacion_mes_monto = $estacionalidad_monto_alto; break;
                                            case 2: $estacion_mes_monto = $estacionalidad_monto_regular; break;
                                            case 3: $estacion_mes_monto = $estacionalidad_monto_bajo; break;
                                            case 4: $estacion_mes_monto = 0; break;
                                            default: $estacion_mes_monto = 0; break;
                                        }

                                        echo number_format(($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $estacion_mes_monto=$calculo_lead->monto_criterio_seleccionado : $estacion_mes_monto), 2, ',', '.');

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 34%; text-align: left;" class="borde_titulo">
                                        FEBRERO
                                    </td>
                                    <td style="width: 33%; text-align: center; text-transform: uppercase;" class="borde">
                                        <?php
                                        // !!!!!!
                                        $estacion_mes = 'estacion_feb_arb';
                                        // !!!!!!
                                        $numero_mes=2; echo ($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['estacion_sel_arb'], 'arb') : $this->mfunciones_generales->GetValorCatalogo($arrLead[0][$estacion_mes], 'arb')); 
                                        ?>
                                    </td>
                                    <td style="width: 33%; text-align: right;" class="borde">
                                        <?php

                                        switch ($arrLead[0][$estacion_mes]) {
                                            case 1: $estacion_mes_monto = $estacionalidad_monto_alto; break;
                                            case 2: $estacion_mes_monto = $estacionalidad_monto_regular; break;
                                            case 3: $estacion_mes_monto = $estacionalidad_monto_bajo; break;
                                            case 4: $estacion_mes_monto = 0; break;
                                            default: $estacion_mes_monto = 0; break;
                                        }

                                        echo number_format(($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $estacion_mes_monto=$calculo_lead->monto_criterio_seleccionado : $estacion_mes_monto), 2, ',', '.');

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 34%; text-align: left;" class="borde_titulo">
                                        MARZO
                                    </td>
                                    <td style="width: 33%; text-align: center; text-transform: uppercase;" class="borde">
                                        <?php
                                        // !!!!!!
                                        $estacion_mes = 'estacion_mar_arb';
                                        // !!!!!!
                                        $numero_mes=3; echo ($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['estacion_sel_arb'], 'arb') : $this->mfunciones_generales->GetValorCatalogo($arrLead[0][$estacion_mes], 'arb')); 
                                        ?>
                                    </td>
                                    <td style="width: 33%; text-align: right;" class="borde">
                                        <?php

                                        switch ($arrLead[0][$estacion_mes]) {
                                            case 1: $estacion_mes_monto = $estacionalidad_monto_alto; break;
                                            case 2: $estacion_mes_monto = $estacionalidad_monto_regular; break;
                                            case 3: $estacion_mes_monto = $estacionalidad_monto_bajo; break;
                                            case 4: $estacion_mes_monto = 0; break;
                                            default: $estacion_mes_monto = 0; break;
                                        }

                                        echo number_format(($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $estacion_mes_monto=$calculo_lead->monto_criterio_seleccionado : $estacion_mes_monto), 2, ',', '.');

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 34%; text-align: left;" class="borde_titulo">
                                        ABRIL
                                    </td>
                                    <td style="width: 33%; text-align: center; text-transform: uppercase;" class="borde">
                                        <?php
                                        // !!!!!!
                                        $estacion_mes = 'estacion_abr_arb';
                                        // !!!!!!
                                        $numero_mes=4; echo ($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['estacion_sel_arb'], 'arb') : $this->mfunciones_generales->GetValorCatalogo($arrLead[0][$estacion_mes], 'arb')); 
                                        ?>
                                    </td>
                                    <td style="width: 33%; text-align: right;" class="borde">
                                        <?php

                                        switch ($arrLead[0][$estacion_mes]) {
                                            case 1: $estacion_mes_monto = $estacionalidad_monto_alto; break;
                                            case 2: $estacion_mes_monto = $estacionalidad_monto_regular; break;
                                            case 3: $estacion_mes_monto = $estacionalidad_monto_bajo; break;
                                            case 4: $estacion_mes_monto = 0; break;
                                            default: $estacion_mes_monto = 0; break;
                                        }

                                        echo number_format(($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $estacion_mes_monto=$calculo_lead->monto_criterio_seleccionado : $estacion_mes_monto), 2, ',', '.');

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 34%; text-align: left;" class="borde_titulo">
                                        MAYO
                                    </td>
                                    <td style="width: 33%; text-align: center; text-transform: uppercase;" class="borde">
                                        <?php
                                        // !!!!!!
                                        $estacion_mes = 'estacion_may_arb';
                                        // !!!!!!
                                        $numero_mes=5; echo ($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['estacion_sel_arb'], 'arb') : $this->mfunciones_generales->GetValorCatalogo($arrLead[0][$estacion_mes], 'arb')); 
                                        ?>
                                    </td>
                                    <td style="width: 33%; text-align: right;" class="borde">
                                        <?php

                                        switch ($arrLead[0][$estacion_mes]) {
                                            case 1: $estacion_mes_monto = $estacionalidad_monto_alto; break;
                                            case 2: $estacion_mes_monto = $estacionalidad_monto_regular; break;
                                            case 3: $estacion_mes_monto = $estacionalidad_monto_bajo; break;
                                            case 4: $estacion_mes_monto = 0; break;
                                            default: $estacion_mes_monto = 0; break;
                                        }

                                        echo number_format(($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $estacion_mes_monto=$calculo_lead->monto_criterio_seleccionado : $estacion_mes_monto), 2, ',', '.');

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 34%; text-align: left;" class="borde_titulo">
                                        JUNIO
                                    </td>
                                    <td style="width: 33%; text-align: center; text-transform: uppercase;" class="borde">
                                        <?php
                                        // !!!!!!
                                        $estacion_mes = 'estacion_jun_arb';
                                        // !!!!!!
                                        $numero_mes=6; echo ($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['estacion_sel_arb'], 'arb') : $this->mfunciones_generales->GetValorCatalogo($arrLead[0][$estacion_mes], 'arb')); 
                                        ?>
                                    </td>
                                    <td style="width: 33%; text-align: right;" class="borde">
                                        <?php

                                        switch ($arrLead[0][$estacion_mes]) {
                                            case 1: $estacion_mes_monto = $estacionalidad_monto_alto; break;
                                            case 2: $estacion_mes_monto = $estacionalidad_monto_regular; break;
                                            case 3: $estacion_mes_monto = $estacionalidad_monto_bajo; break;
                                            case 4: $estacion_mes_monto = 0; break;
                                            default: $estacion_mes_monto = 0; break;
                                        }

                                        echo number_format(($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $estacion_mes_monto=$calculo_lead->monto_criterio_seleccionado : $estacion_mes_monto), 2, ',', '.');

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 34%; text-align: left;" class="borde_titulo">
                                        JULIO
                                    </td>
                                    <td style="width: 33%; text-align: center; text-transform: uppercase;" class="borde">
                                        <?php
                                        // !!!!!!
                                        $estacion_mes = 'estacion_jul_arb';
                                        // !!!!!!
                                        $numero_mes=7; echo ($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['estacion_sel_arb'], 'arb') : $this->mfunciones_generales->GetValorCatalogo($arrLead[0][$estacion_mes], 'arb')); 
                                        ?>
                                    </td>
                                    <td style="width: 33%; text-align: right;" class="borde">
                                        <?php

                                        switch ($arrLead[0][$estacion_mes]) {
                                            case 1: $estacion_mes_monto = $estacionalidad_monto_alto; break;
                                            case 2: $estacion_mes_monto = $estacionalidad_monto_regular; break;
                                            case 3: $estacion_mes_monto = $estacionalidad_monto_bajo; break;
                                            case 4: $estacion_mes_monto = 0; break;
                                            default: $estacion_mes_monto = 0; break;
                                        }

                                        echo number_format(($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $estacion_mes_monto=$calculo_lead->monto_criterio_seleccionado : $estacion_mes_monto), 2, ',', '.');

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 34%; text-align: left;" class="borde_titulo">
                                        AGOSTO
                                    </td>
                                    <td style="width: 33%; text-align: center; text-transform: uppercase;" class="borde">
                                        <?php
                                        // !!!!!!
                                        $estacion_mes = 'estacion_ago_arb';
                                        // !!!!!!
                                        $numero_mes=8; echo ($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['estacion_sel_arb'], 'arb') : $this->mfunciones_generales->GetValorCatalogo($arrLead[0][$estacion_mes], 'arb')); 
                                        ?>
                                    </td>
                                    <td style="width: 33%; text-align: right;" class="borde">
                                        <?php

                                        switch ($arrLead[0][$estacion_mes]) {
                                            case 1: $estacion_mes_monto = $estacionalidad_monto_alto; break;
                                            case 2: $estacion_mes_monto = $estacionalidad_monto_regular; break;
                                            case 3: $estacion_mes_monto = $estacionalidad_monto_bajo; break;
                                            case 4: $estacion_mes_monto = 0; break;
                                            default: $estacion_mes_monto = 0; break;
                                        }

                                        echo number_format(($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $estacion_mes_monto=$calculo_lead->monto_criterio_seleccionado : $estacion_mes_monto), 2, ',', '.');

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 34%; text-align: left;" class="borde_titulo">
                                        SEPTIEMBRE
                                    </td>
                                    <td style="width: 33%; text-align: center; text-transform: uppercase;" class="borde">
                                        <?php
                                        // !!!!!!
                                        $estacion_mes = 'estacion_sep_arb';
                                        // !!!!!!
                                        $numero_mes=9; echo ($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['estacion_sel_arb'], 'arb') : $this->mfunciones_generales->GetValorCatalogo($arrLead[0][$estacion_mes], 'arb')); 
                                        ?>
                                    </td>
                                    <td style="width: 33%; text-align: right;" class="borde">
                                        <?php

                                        switch ($arrLead[0][$estacion_mes]) {
                                            case 1: $estacion_mes_monto = $estacionalidad_monto_alto; break;
                                            case 2: $estacion_mes_monto = $estacionalidad_monto_regular; break;
                                            case 3: $estacion_mes_monto = $estacionalidad_monto_bajo; break;
                                            case 4: $estacion_mes_monto = 0; break;
                                            default: $estacion_mes_monto = 0; break;
                                        }

                                        echo number_format(($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $estacion_mes_monto=$calculo_lead->monto_criterio_seleccionado : $estacion_mes_monto), 2, ',', '.');

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 34%; text-align: left;" class="borde_titulo">
                                        OCTUBRE
                                    </td>
                                    <td style="width: 33%; text-align: center; text-transform: uppercase;" class="borde">
                                        <?php
                                        // !!!!!!
                                        $estacion_mes = 'estacion_oct_arb';
                                        // !!!!!!
                                        $numero_mes=10; echo ($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['estacion_sel_arb'], 'arb') : $this->mfunciones_generales->GetValorCatalogo($arrLead[0][$estacion_mes], 'arb'));
                                        ?>
                                    </td>
                                    <td style="width: 33%; text-align: right;" class="borde">
                                        <?php

                                        switch ($arrLead[0][$estacion_mes]) {
                                            case 1: $estacion_mes_monto = $estacionalidad_monto_alto; break;
                                            case 2: $estacion_mes_monto = $estacionalidad_monto_regular; break;
                                            case 3: $estacion_mes_monto = $estacionalidad_monto_bajo; break;
                                            case 4: $estacion_mes_monto = 0; break;
                                            default: $estacion_mes_monto = 0; break;
                                        }

                                        echo number_format(($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $estacion_mes_monto=$calculo_lead->monto_criterio_seleccionado : $estacion_mes_monto), 2, ',', '.');

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 34%; text-align: left;" class="borde_titulo">
                                        NOVIEMBRE
                                    </td>
                                    <td style="width: 33%; text-align: center; text-transform: uppercase;" class="borde">
                                        <?php
                                        // !!!!!!
                                        $estacion_mes = 'estacion_nov_arb';
                                        // !!!!!!
                                        $numero_mes=11; echo ($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['estacion_sel_arb'], 'arb') : $this->mfunciones_generales->GetValorCatalogo($arrLead[0][$estacion_mes], 'arb')); 
                                        ?>
                                    </td>
                                    <td style="width: 33%; text-align: right;" class="borde">
                                        <?php

                                        switch ($arrLead[0][$estacion_mes]) {
                                            case 1: $estacion_mes_monto = $estacionalidad_monto_alto; break;
                                            case 2: $estacion_mes_monto = $estacionalidad_monto_regular; break;
                                            case 3: $estacion_mes_monto = $estacionalidad_monto_bajo; break;
                                            case 4: $estacion_mes_monto = 0; break;
                                            default: $estacion_mes_monto = 0; break;
                                        }

                                        echo number_format(($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $estacion_mes_monto=$calculo_lead->monto_criterio_seleccionado : $estacion_mes_monto), 2, ',', '.');

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 34%; text-align: left;" class="borde_titulo">
                                        DICIEMBRE
                                    </td>
                                    <td style="width: 33%; text-align: center; text-transform: uppercase;" class="borde">
                                        <?php
                                        // !!!!!!
                                        $estacion_mes = 'estacion_dic_arb';
                                        // !!!!!!
                                        $numero_mes=12; echo ($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['estacion_sel_arb'], 'arb') : $this->mfunciones_generales->GetValorCatalogo($arrLead[0][$estacion_mes], 'arb')); 
                                        ?>
                                    </td>
                                    <td style="width: 33%; text-align: right;" class="borde">
                                        <?php

                                        switch ($arrLead[0][$estacion_mes]) {
                                            case 1: $estacion_mes_monto = $estacionalidad_monto_alto; break;
                                            case 2: $estacion_mes_monto = $estacionalidad_monto_regular; break;
                                            case 3: $estacion_mes_monto = $estacionalidad_monto_bajo; break;
                                            case 4: $estacion_mes_monto = 0; break;
                                            default: $estacion_mes_monto = 0; break;
                                        }

                                        echo number_format(($arrLead[0]['estacion_sel_mes'] == $numero_mes ? $estacion_mes_monto=$calculo_lead->monto_criterio_seleccionado : $estacion_mes_monto), 2, ',', '.');

                                        ?>
                                    </td>
                                </tr>
                            </table>
                        
                        <?php
                        }
                        ?>
                        
                    </td>
                </tr>
            </table>
            
            <?php
            if($arrLead[0]['estacion_sel'] != 0 && $arrLead[0]['estacion_sel_arb'] != 2)
            {
            ?>

                <br />
                <div class="" style="width: 100%; text-justify: inter-word; font-weight: bold; background-color: #ffffff; color: #000000;">
                    DETERMINACION DEL INGRESO PARA ACTIVIDADES CON ESTACIONALIDAD MARCADA
                </div>
                <br />

                <table align="center" style="width: 60%;" cellspacing="0">
                    <tr>
                        <td style="width: 34%; text-align: center;" class="borde_caqui">
                            CATEGORIA
                        </td>
                        <td style="width: 33%; text-align: center;" class="borde_caqui">
                            INGRESO Bs.
                        </td>
                        <td style="width: 33%; text-align: center;" class="borde_caqui">
                            N° MESES
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 34%; text-align: center; font-weight: bold; text-transform: uppercase;" class="borde">
                            Regular
                        </td>
                        <td style="width: 33%; text-align: right;" class="borde">
                            <?php echo number_format($calculo_lead->mes_regular_suma, 2, ',', '.'); ?>
                        </td>
                        <td style="width: 33%; text-align: right;" class="borde">
                            <?php echo number_format($calculo_lead->mes_regular_contador, 0, ',', '.'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 34%; text-align: center; font-weight: bold; text-transform: uppercase;" class="borde">
                            Bajo
                        </td>
                        <td style="width: 33%; text-align: right;" class="borde">
                            <?php echo number_format($calculo_lead->mes_bajo_suma, 2, ',', '.'); ?>
                        </td>
                        <td style="width: 33%; text-align: right;" class="borde">
                            <?php echo number_format($calculo_lead->mes_bajo_contador, 0, ',', '.'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 34%; text-align: center; font-weight: bold; text-transform: uppercase;" class="borde">
                            TOTAL
                        </td>
                        <td style="width: 33%; text-align: right;" class="borde">
                            <?php echo number_format($calculo_lead->mes_bajo_suma+$calculo_lead->mes_regular_suma, 2, ',', '.'); ?>
                        </td>
                        <td style="width: 33%; text-align: right;" class="borde">
                            <?php echo number_format($calculo_lead->mes_bajo_contador+$calculo_lead->mes_regular_contador, 0, ',', '.'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 67%; text-align: right; text-transform: uppercase;" class="">
                            INGRESO MENSUAL PROMEDIO PONDERADO
                        </td>
                        <td style="width: 33%; text-align: right;" class="borde_titulo">
                            <?php echo number_format($calculo_lead->ingreso_mensual_promedio, 2, ',', '.'); ?>
                        </td>
                    </tr>
                </table>
            <?php
            }
            else
            {
            ?>
                <br />
                <table align="left" style="width: 60%;" cellspacing="0">
                    <tr>
                        <td style="width: 80%; text-align: right;" class="">
                            INGRESO MENSUAL PROMEDIO PONDERADO Bs.
                        </td>
                        <td style="width: 20%; text-align: right;" class="borde_titulo">
                            <?php echo number_format($calculo_lead->ingreso_mensual_promedio, 2, ',', '.'); ?>
                        </td>
                    </tr>
                </table>
        <?php
            }
        }
        
        // SECCIÓN: GASTOS OPERATIVOS Y FAMILIARES

        if(in_array("gastos_familiares", $this->mfunciones_generales->getVistasRubro($rubro_unidad)))
        {
        ?>
            <br />
            
            <div class="titulo_seccion" style="width: 100%; text-justify: inter-word; font-weight: bold; clear: both;">
                GASTOS OPERATIVOS Y FAMILIARES
            </div>
            <br />
            <div class="" style="width: 100%; text-justify: inter-word; font-weight: bold; background-color: #ffffff; color: #000000;">
                En este apartado podrá visualizar los Gastos Operativos y Familiares registrados
            </div>
            <br />
            
            <table align="center" style="width: 100%;" cellspacing="0">
                <tr>
                    <td valign="top" style="width: 50%; text-align: left;">
                        
                        <?php
                        if($rubro_unidad == 4)
                        {
                        ?>
                            <table align="left" style="width: 100%;" cellspacing="0">
                                <tr>
                                    <td colspan="5" style="width: 100%; text-align: center; font-size: 9px" class="borde_caqui">
                                        IV. ESTIMACIÓN DE LOS GASTOS OPERATIVOS
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 28%; text-align: center; font-size: 9px" class="borde_caqui">
                                        CONCEPTO
                                    </td>
                                    <td style="width: 18%; text-align: center; font-size: 9px" class="borde_caqui">
                                        FRECUENCIA
                                    </td>
                                    <td style="width: 18%; text-align: center; font-size: 9px" class="borde_caqui">
                                        CANTIDAD
                                    </td>
                                    <td style="width: 18%; text-align: center; font-size: 9px" class="borde_caqui">
                                        COSTO UNITARIO
                                    </td>
                                    <td style="width: 18%; text-align: center; font-size: 9px" class="borde_caqui">
                                        COSTO TOTAL
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->lang->line('operativos_cambio_aceite_motor'); ?>
                                    </td>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_cambio_aceite_motor_frecuencia'], 'frecuencia_dias'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_cambio_aceite_motor_cantidad'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_cambio_aceite_motor_monto'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                        <?php echo number_format($calculo_lead->operativos_cambio_aceite_motor, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->lang->line('operativos_cambio_aceite_caja'); ?>
                                    </td>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_cambio_aceite_caja_frecuencia'], 'frecuencia_dias'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_cambio_aceite_caja_cantidad'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_cambio_aceite_caja_monto'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                        <?php echo number_format($calculo_lead->operativos_cambio_aceite_caja, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->lang->line('operativos_cambio_llanta_delanteras'); ?>
                                    </td>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_cambio_llanta_delanteras_frecuencia'], 'frecuencia_dias'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_cambio_llanta_delanteras_cantidad'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_cambio_llanta_delanteras_monto'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                        <?php echo number_format($calculo_lead->operativos_cambio_llanta_delanteras, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->lang->line('operativos_cambio_llanta_traseras'); ?>
                                    </td>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_cambio_llanta_traseras_frecuencia'], 'frecuencia_dias'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_cambio_llanta_traseras_cantidad'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_cambio_llanta_traseras_monto'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                        <?php echo number_format($calculo_lead->operativos_cambio_llanta_traseras, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->lang->line('operativos_cambio_bateria'); ?>
                                    </td>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_cambio_bateria_frecuencia'], 'frecuencia_dias'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_cambio_bateria_cantidad'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_cambio_bateria_monto'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                        <?php echo number_format($calculo_lead->operativos_cambio_bateria, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->lang->line('operativos_cambio_balatas'); ?>
                                    </td>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_cambio_balatas_frecuencia'], 'frecuencia_dias'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_cambio_balatas_cantidad'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_cambio_balatas_monto'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                        <?php echo number_format($calculo_lead->operativos_cambio_balatas, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->lang->line('operativos_revision_electrico'); ?>
                                    </td>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_revision_electrico_frecuencia'], 'frecuencia_dias'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_revision_electrico_cantidad'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_revision_electrico_monto'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                        <?php echo number_format($calculo_lead->operativos_revision_electrico, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->lang->line('operativos_remachado_embrague'); ?>
                                    </td>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_remachado_embrague_frecuencia'], 'frecuencia_dias'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_remachado_embrague_cantidad'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_remachado_embrague_monto'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                        <?php echo number_format($calculo_lead->operativos_remachado_embrague, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->lang->line('operativos_rectificacion_motor'); ?>
                                    </td>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_rectificacion_motor_frecuencia'], 'frecuencia_dias'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_rectificacion_motor_cantidad'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_rectificacion_motor_monto'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                        <?php echo number_format($calculo_lead->operativos_rectificacion_motor, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->lang->line('operativos_cambio_rodamiento'); ?>
                                    </td>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_cambio_rodamiento_frecuencia'], 'frecuencia_dias'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_cambio_rodamiento_cantidad'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_cambio_rodamiento_monto'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                        <?php echo number_format($calculo_lead->operativos_cambio_rodamiento, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->lang->line('operativos_reparaciones_menores'); ?>
                                    </td>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_reparaciones_menores_frecuencia'], 'frecuencia_dias'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_reparaciones_menores_cantidad'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_reparaciones_menores_monto'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                        <?php echo number_format($calculo_lead->operativos_reparaciones_menores, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->lang->line('operativos_imprevistos'); ?>
                                    </td>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_imprevistos_frecuencia'], 'frecuencia_dias'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_imprevistos_cantidad'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_imprevistos_monto'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                        <?php echo number_format($calculo_lead->operativos_imprevistos, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->lang->line('operativos_impuesto_propiedad'); ?>
                                    </td>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_impuesto_propiedad_frecuencia'], 'frecuencia_dias'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_impuesto_propiedad_cantidad'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_impuesto_propiedad_monto'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                        <?php echo number_format($calculo_lead->operativos_impuesto_propiedad, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->lang->line('operativos_soat'); ?>
                                    </td>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_soat_frecuencia'], 'frecuencia_dias'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_soat_cantidad'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_soat_monto'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                        <?php echo number_format($calculo_lead->operativos_soat, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->lang->line('operativos_roseta_inspeccion'); ?>
                                    </td>
                                    <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_roseta_inspeccion_frecuencia'], 'frecuencia_dias'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_roseta_inspeccion_cantidad'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_roseta_inspeccion_monto'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                        <?php echo number_format($calculo_lead->operativos_roseta_inspeccion, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                
                                <?php
                                if($arrLead[0]['operativos_otro_transporte_libre1_monto'] != 0)
                                {
                                ?>
                                
                                    <tr>
                                        <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo $arrLead[0]['operativos_otro_transporte_libre1_texto']; ?>
                                        </td>
                                        <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_otro_transporte_libre1_frecuencia'], 'frecuencia_dias'); ?>
                                        </td>
                                        <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo number_format($arrLead[0]['operativos_otro_transporte_libre1_cantidad'], 2, ',', '.'); ?>
                                        </td>
                                        <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo number_format($arrLead[0]['operativos_otro_transporte_libre1_monto'], 2, ',', '.'); ?>
                                        </td>
                                        <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                            <?php echo number_format($calculo_lead->operativos_otro_transporte_libre1, 2, ',', '.'); ?>
                                        </td>
                                    </tr>
                                    
                                <?php
                                }
                                if($arrLead[0]['operativos_otro_transporte_libre2_monto'] != 0)
                                {
                                ?>
                                    
                                    <tr>
                                        <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo $arrLead[0]['operativos_otro_transporte_libre2_texto']; ?>
                                        </td>
                                        <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_otro_transporte_libre2_frecuencia'], 'frecuencia_dias'); ?>
                                        </td>
                                        <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo number_format($arrLead[0]['operativos_otro_transporte_libre2_cantidad'], 2, ',', '.'); ?>
                                        </td>
                                        <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo number_format($arrLead[0]['operativos_otro_transporte_libre2_monto'], 2, ',', '.'); ?>
                                        </td>
                                        <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                            <?php echo number_format($calculo_lead->operativos_otro_transporte_libre2, 2, ',', '.'); ?>
                                        </td>
                                    </tr>
                                    
                                <?php
                                }
                                if($arrLead[0]['operativos_otro_transporte_libre3_monto'] != 0)
                                {
                                ?>
                                    
                                    <tr>
                                        <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo $arrLead[0]['operativos_otro_transporte_libre3_texto']; ?>
                                        </td>
                                        <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_otro_transporte_libre3_frecuencia'], 'frecuencia_dias'); ?>
                                        </td>
                                        <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo number_format($arrLead[0]['operativos_otro_transporte_libre3_cantidad'], 2, ',', '.'); ?>
                                        </td>
                                        <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo number_format($arrLead[0]['operativos_otro_transporte_libre3_monto'], 2, ',', '.'); ?>
                                        </td>
                                        <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                            <?php echo number_format($calculo_lead->operativos_otro_transporte_libre3, 2, ',', '.'); ?>
                                        </td>
                                    </tr>
                                    
                                <?php
                                }
                                if($arrLead[0]['operativos_otro_transporte_libre4_monto'] != 0)
                                {
                                ?>
                                    
                                    <tr>
                                        <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo $arrLead[0]['operativos_otro_transporte_libre4_texto']; ?>
                                        </td>
                                        <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_otro_transporte_libre4_frecuencia'], 'frecuencia_dias'); ?>
                                        </td>
                                        <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo number_format($arrLead[0]['operativos_otro_transporte_libre4_cantidad'], 2, ',', '.'); ?>
                                        </td>
                                        <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo number_format($arrLead[0]['operativos_otro_transporte_libre4_monto'], 2, ',', '.'); ?>
                                        </td>
                                        <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                            <?php echo number_format($calculo_lead->operativos_otro_transporte_libre4, 2, ',', '.'); ?>
                                        </td>
                                    </tr>
                                    
                                <?php
                                }
                                if($arrLead[0]['operativos_otro_transporte_libre5_monto'] != 0)
                                {
                                ?>
                                    
                                    <tr>
                                        <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo $arrLead[0]['operativos_otro_transporte_libre5_texto']; ?>
                                        </td>
                                        <td style="text-align: center; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['operativos_otro_transporte_libre5_frecuencia'], 'frecuencia_dias'); ?>
                                        </td>
                                        <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo number_format($arrLead[0]['operativos_otro_transporte_libre5_cantidad'], 2, ',', '.'); ?>
                                        </td>
                                        <td style="text-align: right; font-size: 9px; height: 30px;" class="borde">
                                            <?php echo number_format($arrLead[0]['operativos_otro_transporte_libre5_monto'], 2, ',', '.'); ?>
                                        </td>
                                        <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                            <?php echo number_format($calculo_lead->operativos_otro_transporte_libre5, 2, ',', '.'); ?>
                                        </td>
                                    </tr>
                                
                                <?php
                                }
                                ?>
                                    
                                <tr>
                                    <td colspan="4" style="text-align: center; font-size: 9px; height: 30px;" class="borde_titulo">
                                        TOTAL GASTOS OPERATIVOS
                                    </td>
                                    <td style="text-align: right; font-size: 9px; height: 30px;" class="borde_caqui">
                                        <?php echo number_format($calculo_lead->operativos_otro_transporte_total, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                    
                            </table>
                        
                        <?php
                        }
                        else
                        {
                        ?>
                        
                            <table align="left" style="width: 100%;" cellspacing="0">
                                <tr>
                                    <td colspan="2" style="width: 100%; text-align: center;" class="borde_caqui">
                                        GASTOS OPERATIVOS
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: center;" class="borde_caqui">
                                        CONCEPTO
                                    </td>
                                    <td style="width: 30%; text-align: center;" class="borde_caqui">
                                        Bs.
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2" style="width: 100%; text-align: center;" class="borde_caqui">
                                        Alquileres
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        <?php echo $this->lang->line('operativos_alq_energia_monto'); ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_alq_energia_monto'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        <?php echo $this->lang->line('operativos_alq_agua_monto'); ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_alq_agua_monto'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        <?php echo $this->lang->line('operativos_alq_internet_monto'); ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_alq_internet_monto'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        <?php echo $this->lang->line('operativos_alq_combustible_monto'); ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_alq_combustible_monto'], 2, ',', '.'); ?>
                                    </td>
                                </tr>

                                <?php

                                if($arrLead[0]['operativos_alq_libre1_monto'] != 0)
                                {
                                    echo '
                                    <tr>
                                        <td style="width: 70%; text-align: right;" class="borde">
                                            ' . $arrLead[0]['operativos_alq_libre1_texto'] . '
                                        </td>
                                        <td style="width: 30%; text-align: right;" class="borde">
                                            ' . number_format($arrLead[0]['operativos_alq_libre1_monto'], 2, ',', '.') . '
                                        </td>
                                    </tr>
                                    ';
                                }

                                if($arrLead[0]['operativos_alq_libre2_monto'] != 0)
                                {
                                    echo '
                                    <tr>
                                        <td style="width: 70%; text-align: right;" class="borde">
                                            ' . $arrLead[0]['operativos_alq_libre2_texto'] . '
                                        </td>
                                        <td style="width: 30%; text-align: right;" class="borde">
                                            ' . number_format($arrLead[0]['operativos_alq_libre2_monto'], 2, ',', '.') . '
                                        </td>
                                    </tr>
                                    ';
                                }

                                ?>

                                <tr>
                                    <td colspan="2" style="width: 100%; text-align: center;" class="borde_caqui">
                                        Salarios
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        <?php echo $this->lang->line('operativos_sal_aguinaldos_monto'); ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_sal_aguinaldos_monto'], 2, ',', '.'); ?>
                                    </td>
                                </tr>

                                <?php

                                if($arrLead[0]['operativos_sal_libre1_monto'] != 0)
                                {
                                    echo '
                                    <tr>
                                        <td style="width: 70%; text-align: right;" class="borde">
                                            ' . $arrLead[0]['operativos_sal_libre1_texto'] . '
                                        </td>
                                        <td style="width: 30%; text-align: right;" class="borde">
                                            ' . number_format($arrLead[0]['operativos_sal_libre1_monto'], 2, ',', '.') . '
                                        </td>
                                    </tr>
                                    ';
                                }

                                if($arrLead[0]['operativos_sal_libre2_monto'] != 0)
                                {
                                    echo '
                                    <tr>
                                        <td style="width: 70%; text-align: right;" class="borde">
                                            ' . $arrLead[0]['operativos_sal_libre2_texto'] . '
                                        </td>
                                        <td style="width: 30%; text-align: right;" class="borde">
                                            ' . number_format($arrLead[0]['operativos_sal_libre2_monto'], 2, ',', '.') . '
                                        </td>
                                    </tr>
                                    ';
                                }

                                if($arrLead[0]['operativos_sal_libre3_monto'] != 0)
                                {
                                    echo '
                                    <tr>
                                        <td style="width: 70%; text-align: right;" class="borde">
                                            ' . $arrLead[0]['operativos_sal_libre3_texto'] . '
                                        </td>
                                        <td style="width: 30%; text-align: right;" class="borde">
                                            ' . number_format($arrLead[0]['operativos_sal_libre3_monto'], 2, ',', '.') . '
                                        </td>
                                    </tr>
                                    ';
                                }

                                if($arrLead[0]['operativos_sal_libre4_monto'] != 0)
                                {
                                    echo '
                                    <tr>
                                        <td style="width: 70%; text-align: right;" class="borde">
                                            ' . $arrLead[0]['operativos_sal_libre4_texto'] . '
                                        </td>
                                        <td style="width: 30%; text-align: right;" class="borde">
                                            ' . number_format($arrLead[0]['operativos_sal_libre4_monto'], 2, ',', '.') . '
                                        </td>
                                    </tr>
                                    ';
                                }

                                ?>

                                <tr>
                                    <td colspan="2" style="width: 100%; text-align: center;" class="borde_caqui">
                                        Otros
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        <?php echo $this->lang->line('operativos_otro_transporte_monto'); ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_otro_transporte_monto'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        <?php echo $this->lang->line('operativos_otro_licencias_monto'); ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_otro_licencias_monto'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        <?php echo $this->lang->line('operativos_otro_alimentacion_monto'); ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_otro_alimentacion_monto'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        <?php echo $this->lang->line('operativos_otro_mant_vehiculo_monto'); ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_otro_mant_vehiculo_monto'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        <?php echo $this->lang->line('operativos_otro_mant_maquina_monto'); ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_otro_mant_maquina_monto'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        <?php echo $this->lang->line('operativos_otro_imprevistos_monto'); ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_otro_imprevistos_monto'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        <?php echo $this->lang->line('operativos_otro_otros_monto'); ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['operativos_otro_otros_monto'], 2, ',', '.'); ?>
                                    </td>
                                </tr>

                                <?php

                                if($arrLead[0]['operativos_otro_libre1_monto'] != 0)
                                {
                                    echo '
                                    <tr>
                                        <td style="width: 70%; text-align: right;" class="borde">
                                            ' . $arrLead[0]['operativos_otro_libre1_texto'] . '
                                        </td>
                                        <td style="width: 30%; text-align: right;" class="borde">
                                            ' . number_format($arrLead[0]['operativos_otro_libre1_monto'], 2, ',', '.') . '
                                        </td>
                                    </tr>
                                    ';
                                }

                                if($arrLead[0]['operativos_otro_libre2_monto'] != 0)
                                {
                                    echo '
                                    <tr>
                                        <td style="width: 70%; text-align: right;" class="borde">
                                            ' . $arrLead[0]['operativos_otro_libre2_texto'] . '
                                        </td>
                                        <td style="width: 30%; text-align: right;" class="borde">
                                            ' . number_format($arrLead[0]['operativos_otro_libre2_monto'], 2, ',', '.') . '
                                        </td>
                                    </tr>
                                    ';
                                }

                                if($arrLead[0]['operativos_otro_libre3_monto'] != 0)
                                {
                                    echo '
                                    <tr>
                                        <td style="width: 70%; text-align: right;" class="borde">
                                            ' . $arrLead[0]['operativos_otro_libre3_texto'] . '
                                        </td>
                                        <td style="width: 30%; text-align: right;" class="borde">
                                            ' . number_format($arrLead[0]['operativos_otro_libre3_monto'], 2, ',', '.') . '
                                        </td>
                                    </tr>
                                    ';
                                }

                                if($arrLead[0]['operativos_otro_libre4_monto'] != 0)
                                {
                                    echo '
                                    <tr>
                                        <td style="width: 70%; text-align: right;" class="borde">
                                            ' . $arrLead[0]['operativos_otro_libre4_texto'] . '
                                        </td>
                                        <td style="width: 30%; text-align: right;" class="borde">
                                            ' . number_format($arrLead[0]['operativos_otro_libre4_monto'], 2, ',', '.') . '
                                        </td>
                                    </tr>
                                    ';
                                }

                                if($arrLead[0]['operativos_otro_libre5_monto'] != 0)
                                {
                                    echo '
                                    <tr>
                                        <td style="width: 70%; text-align: right;" class="borde">
                                            ' . $arrLead[0]['operativos_otro_libre5_texto'] . '
                                        </td>
                                        <td style="width: 30%; text-align: right;" class="borde">
                                            ' . number_format($arrLead[0]['operativos_otro_libre5_monto'], 2, ',', '.') . '
                                        </td>
                                    </tr>
                                    ';
                                }

                                ?>

                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde_titulo">
                                        TOTAL GASTO OPERATIVO MENSUAL Bs.
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde_titulo">
                                        <?php 

                                        $suma_operativo =   $arrLead[0]['operativos_alq_energia_monto'] + 
                                                            $arrLead[0]['operativos_alq_agua_monto'] + 
                                                            $arrLead[0]['operativos_alq_internet_monto'] + 
                                                            $arrLead[0]['operativos_alq_combustible_monto'] + 
                                                            $arrLead[0]['operativos_alq_libre1_monto'] + 
                                                            $arrLead[0]['operativos_alq_libre2_monto'] + 
                                                            $arrLead[0]['operativos_sal_aguinaldos_monto'] + 
                                                            $arrLead[0]['operativos_sal_libre1_monto'] + 
                                                            $arrLead[0]['operativos_sal_libre2_monto'] + 
                                                            $arrLead[0]['operativos_sal_libre3_monto'] + 
                                                            $arrLead[0]['operativos_sal_libre4_monto'] + 
                                                            $arrLead[0]['operativos_otro_transporte_monto'] + 
                                                            $arrLead[0]['operativos_otro_licencias_monto'] + 
                                                            $arrLead[0]['operativos_otro_alimentacion_monto'] + 
                                                            $arrLead[0]['operativos_otro_mant_vehiculo_monto'] + 
                                                            $arrLead[0]['operativos_otro_mant_maquina_monto'] + 
                                                            $arrLead[0]['operativos_otro_imprevistos_monto'] + 
                                                            $arrLead[0]['operativos_otro_otros_monto'] + 
                                                            $arrLead[0]['operativos_otro_libre1_monto'] + 
                                                            $arrLead[0]['operativos_otro_libre2_monto'] + 
                                                            $arrLead[0]['operativos_otro_libre3_monto'] + 
                                                            $arrLead[0]['operativos_otro_libre4_monto'] + 
                                                            $arrLead[0]['operativos_otro_libre5_monto'];

                                        echo number_format($suma_operativo, 2, ',', '.'); 

                                        ?>
                                    </td>
                                </tr>

                            </table>
                            
                        <?php
                        }
                        ?>
                        
                    </td>
                    <td valign="top" style="width: 50%; text-align: right;">
                        <table align="left" style="width: 95%;" cellspacing="0">
                            <tr>
                                <td colspan="2" style="width: 100%; text-align: center;" class="borde_caqui">
                                    <?php echo ($rubro_unidad==4 ? 'V. ESTIMACIÓN DE LOS GASTOS FAMILIARES' : 'GASTOS FAMILIARES'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="width: 100%; text-align: center; padding: 0px" class="">
                                    <table align="center" style="width: 100%;" cellspacing="0">
                                        <tr>
                                            <td style="width: 35%; text-align: center;" class="borde_caqui">
                                                DEPENDIENTES DEL INGRESO FAMILIAR
                                            </td>
                                            <td style="width: 10%; text-align: center;" class="borde">
                                                <?php echo $arrLead[0]['familiar_dependientes_ingreso']; ?>
                                            </td>
                                            <td style="width: 25%; text-align: center;" class="borde_caqui">
                                                EDAD DE HIJOS
                                            </td>
                                            <td style="width: 30%; text-align: center;" class="borde">
                                                <?php echo $arrLead[0]['familiar_edad_hijos']; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: center;" class="borde_caqui">
                                    CONCEPTO
                                </td>
                                <td style="width: 30%; text-align: center;" class="borde_caqui">
                                    Bs.
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde">
                                    <?php echo $this->lang->line('familiar_alimentacion_monto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['familiar_alimentacion_monto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde">
                                    <?php echo $this->lang->line('familiar_energia_monto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['familiar_energia_monto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde">
                                    <?php echo $this->lang->line('familiar_agua_monto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['familiar_agua_monto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde">
                                    <?php echo $this->lang->line('familiar_gas_monto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['familiar_gas_monto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde">
                                    <?php echo $this->lang->line('familiar_telefono_monto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['familiar_telefono_monto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde">
                                    <?php echo $this->lang->line('familiar_celular_monto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['familiar_celular_monto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde">
                                    <?php echo $this->lang->line('familiar_internet_monto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['familiar_internet_monto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde">
                                    <?php echo $this->lang->line('familiar_tv_monto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['familiar_tv_monto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde">
                                    <?php echo $this->lang->line('familiar_impuestos_monto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['familiar_impuestos_monto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde">
                                    <?php echo $this->lang->line('familiar_alquileres_monto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['familiar_alquileres_monto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde">
                                    <?php echo $this->lang->line('familiar_educacion_monto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['familiar_educacion_monto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde">
                                    <?php echo $this->lang->line('familiar_transporte_monto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['familiar_transporte_monto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde">
                                    <?php echo $this->lang->line('familiar_salud_monto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['familiar_salud_monto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde">
                                    <?php echo $this->lang->line('familiar_empleada_monto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['familiar_empleada_monto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde">
                                    <?php echo $this->lang->line('familiar_diversion_monto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['familiar_diversion_monto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde">
                                    <?php echo $this->lang->line('familiar_vestimenta_monto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['familiar_vestimenta_monto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde">
                                    <?php echo $this->lang->line('familiar_otros_monto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['familiar_otros_monto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            
                            <?php
                            
                            if($arrLead[0]['familiar_libre1_monto'] != 0)
                            {
                                echo '
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        ' . $arrLead[0]['familiar_libre1_texto'] . '
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        ' . number_format($arrLead[0]['familiar_libre1_monto'], 2, ',', '.') . '
                                    </td>
                                </tr>
                                ';
                            }
                            
                            if($arrLead[0]['familiar_libre2_monto'] != 0)
                            {
                                echo '
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        ' . $arrLead[0]['familiar_libre2_texto'] . '
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        ' . number_format($arrLead[0]['familiar_libre2_monto'], 2, ',', '.') . '
                                    </td>
                                </tr>
                                ';
                            }
                            
                            if($arrLead[0]['familiar_libre3_monto'] != 0)
                            {
                                echo '
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        ' . $arrLead[0]['familiar_libre3_texto'] . '
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        ' . number_format($arrLead[0]['familiar_libre3_monto'], 2, ',', '.') . '
                                    </td>
                                </tr>
                                ';
                            }
                            
                            if($arrLead[0]['familiar_libre4_monto'] != 0)
                            {
                                echo '
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        ' . $arrLead[0]['familiar_libre4_texto'] . '
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        ' . number_format($arrLead[0]['familiar_libre4_monto'], 2, ',', '.') . '
                                    </td>
                                </tr>
                                ';
                            }
                            
                            if($arrLead[0]['familiar_libre5_monto'] != 0)
                            {
                                echo '
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        ' . $arrLead[0]['familiar_libre5_texto'] . '
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        ' . number_format($arrLead[0]['familiar_libre5_monto'], 2, ',', '.') . '
                                    </td>
                                </tr>
                                ';
                            }
                            
                            ?>
                            
                            <tr>
                                <td style="width: 70%; text-align: right;" class="borde_titulo">
                                    TOTAL GASTO FAMILIAR MENSUAL Bs.
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde_titulo">
                                    <?php 
                                    
                                    $suma_familiar =    $arrLead[0]['familiar_alimentacion_monto'] + 
                                                        $arrLead[0]['familiar_energia_monto'] + 
                                                        $arrLead[0]['familiar_agua_monto'] + 
                                                        $arrLead[0]['familiar_gas_monto'] + 
                                                        $arrLead[0]['familiar_telefono_monto'] + 
                                                        $arrLead[0]['familiar_celular_monto'] + 
                                                        $arrLead[0]['familiar_internet_monto'] + 
                                                        $arrLead[0]['familiar_tv_monto'] + 
                                                        $arrLead[0]['familiar_impuestos_monto'] + 
                                                        $arrLead[0]['familiar_alquileres_monto'] + 
                                                        $arrLead[0]['familiar_educacion_monto'] + 
                                                        $arrLead[0]['familiar_transporte_monto'] + 
                                                        $arrLead[0]['familiar_salud_monto'] + 
                                                        $arrLead[0]['familiar_empleada_monto'] + 
                                                        $arrLead[0]['familiar_diversion_monto'] + 
                                                        $arrLead[0]['familiar_vestimenta_monto'] + 
                                                        $arrLead[0]['familiar_otros_monto'] + 
                                                        $arrLead[0]['familiar_libre1_monto'] + 
                                                        $arrLead[0]['familiar_libre2_monto'] + 
                                                        $arrLead[0]['familiar_libre3_monto'] + 
                                                        $arrLead[0]['familiar_libre4_monto'] + 
                                                        $arrLead[0]['familiar_libre5_monto'];
                                    
                                    echo number_format($suma_familiar, 2, ',', '.'); 
                                    
                                    ?>
                                </td>
                            </tr>
                            
                        </table>
                    </td>
                </tr>
            </table>
                
        <?php
        }
        
        // SECCIÓN: OTROS INGRESOS
        
        if(in_array("transporte_otros_ingresos", $this->mfunciones_generales->getVistasRubro($rubro_unidad)))
        {
        ?>
            <br />
            <div class="titulo_seccion" style="width: 100%; text-justify: inter-word; font-weight: bold;">
                <?php echo ($rubro_unidad==4 ? 'VI. OTROS INGRESOS' : 'OTROS INGRESOS'); ?>
            </div>
            <br />
            
            <?php
            
            $suma_otros_ingresos = 0;
            
            $arrOtros_Ingresos = $this->mfunciones_logica->ObtenerListaOtrosIngresos($arrLead[0]['prospecto_id'], -1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrOtros_Ingresos);
            
            // Sólo se aumenta el listado para la Actividad Principal
            if($arrLead[0]['prospecto_principal'] == 1)
            {
                $arrOtros_Ingresos = array_merge($arrOtros_Ingresos, $arrAuxiliarIngresos);
            }
            
            if (!isset($arrOtros_Ingresos[0])) 
            {
                echo 'No se Registró Información de Otros Ingresos <br />';
            }
            else
            {
            ?>
            
                <table align="center" style="width: 100%;" cellspacing="0">
                    <tr>
                        <th style="width: 40%; text-align: center; font-weight: bold;" class="borde">
                            <?php echo $this->lang->line('otros_descripcion_fuente'); ?>
                        </th>
                        <th style="width: 40%; text-align: center; font-weight: bold;" class="borde">
                            <?php echo $this->lang->line('otros_descripcion_respaldo'); ?>
                        </th>
                        <th style="width: 20%; text-align: center; font-weight: bold;" class="borde">
                            <?php echo $this->lang->line('otros_monto'); ?>
                        </th>
                    </tr>

                    <?php
                    
                    foreach ($arrOtros_Ingresos as $key => $value) 
                    {
                        $suma_otros_ingresos += $value["otros_monto"];
                    ?>
                        <tr>
                            <td style="width: 40%; text-align: center;" class="borde">
                                <?php echo $value["otros_descripcion_fuente"]; ?>
                            </td>
                            <td style="width: 40%; text-align: center;" class="borde">
                                <?php echo $value["otros_descripcion_respaldo"]; ?>
                            </td>
                            <td style="width: 20%; text-align: right;" class="borde">
                                <?php echo number_format($value["otros_monto"], 2, ',', '.'); ?>
                            </td>
                        </tr>

                    <?php
                    }
                    ?>

                    <tr>
                        <td colspan="2" style="text-align: right; font-weight: bold;" class="borde_titulo">
                            TOTAL OTROS INGRESOS
                        </td>
                        <td style="text-align: right;" class="borde_titulo">
                            <?php echo number_format($suma_otros_ingresos, 2, ',', '.'); ?>
                        </td>
                    </tr>
                        
                </table>
            
                <br />
            
            <?php
            }
        }
        
        // SECCIÓN: COMPRA MATERIA PRIMA

        if(in_array("pasivos", $this->mfunciones_generales->getVistasRubro($rubro_unidad)))
        {
        ?>
            <br />
            
            <div class="titulo_seccion" style="width: 100%; text-justify: inter-word; font-weight: bold;">
                <?php echo ($rubro_unidad==4 ? 'VII. PASIVOS' : 'DEUDAS EN EL SISTEMA FINANCIERO') ?>
            </div>
            <br />
            
            <?php
            
            // Datos de Pasivos
            
            $arrPasivos= $this->mfunciones_logica->ObtenerListaPasivos($arrLead[0]['prospecto_id'], -1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrPasivos);
            
            if(count($arrPasivos)>0)
            {
            
            ?>
            
                <table align="center" style="width: 100%;" cellspacing="0">
                    <tr>

                        <th class="borde" style="width: 15%; font-weight: bold; text-align: center;">
                            <?php echo $this->lang->line('pasivo_acreedor'); ?>
                        </th>

                        <th class="borde" style="width: 10%; font-weight: bold; text-align: center;">
                            <?php echo $this->lang->line('pasivo_tipo'); ?>
                        </th>

                        <th class="borde" style="width: 10%; font-weight: bold; text-align: center;">
                            <?php echo $this->lang->line('pasivo_saldo'); ?>
                        </th>

                        <th class="borde" style="width: 15%; font-weight: bold; text-align: center;">
                            <?php echo $this->lang->line('pasivo_periodo'); ?>
                        </th>

                        <th class="borde" style="width: 10%; font-weight: bold; text-align: center;">
                            <?php echo $this->lang->line('pasivo_cuota_periodica'); ?>
                        </th>

                        <th class="borde" style="width: 10%; font-weight: bold; text-align: center;">
                            <?php echo $this->lang->line('pasivo_cuota_mensual'); ?>
                        </th>

                        <th class="borde" style="width: 10%; font-weight: bold; text-align: center;">
                            <?php echo $this->lang->line('pasivo_rfto'); ?>
                        </th>

                        <th class="borde" style="width: 20%; font-weight: bold; text-align: center;">
                            <?php echo $this->lang->line('pasivo_destino'); ?>
                        </th>

                    </tr>
                    
                    <?php
                    
                    $suma_saldo = 0;
                    $suma_cuota = 0;
                    
                    foreach ($arrPasivos as $key => $value) 
                    {
                        $suma_saldo += $value["pasivo_saldo"];
                        $suma_cuota += ($value['pasivo_rfto']==0 ? $value["pasivo_cuota_mensual"] : 0);
                    ?>
                        <tr>

                            <td class="borde" style="width: 15%; font-weight: normal; text-align: center;">
                                <?php echo $value["pasivo_acreedor"]; ?>
                            </td>

                            <td class="borde" style="width: 10%; font-weight: normal; text-align: center;">
                                <?php echo $value["pasivo_tipo"]; ?>
                            </td>

                            <td class="borde" style="width: 10%; font-weight: normal; text-align: center;">
                                <?php echo number_format($value["pasivo_saldo"], 2, ',', '.'); ?>
                            </td>

                            <td class="borde" style="width: 15%; font-weight: normal; text-align: center;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($value['pasivo_periodo'], 'frecuencia_dias'); ?>
                            </td>

                            <td class="borde" style="width: 10%; font-weight: normal; text-align: center;">
                                <?php echo number_format($value["pasivo_cuota_periodica"], 2, ',', '.'); ?>
                            </td>

                            <td class="borde" style="width: 10%; font-weight: normal; text-align: center; ">
                                <?php echo number_format($value["pasivo_cuota_mensual"], 2, ',', '.'); ?>
                            </td>

                            <td class="borde" style="width: 10%; font-weight: normal; text-align: center;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($value['pasivo_rfto'], 'si_no'); ?>
                            </td>

                            <td class="borde" style="width: 20%; font-weight: normal; text-align: center;">
                                <?php echo $value["pasivo_destino"]; ?>
                            </td>

                        </tr>
                    <?php
                    }
                    ?>
                        
                    <tr>

                        <td colspan="2" class="" style="font-weight: bold; text-align: center;">
                            Total Deuda Directa
                        </td>
                        <td class="borde_titulo" style="font-weight: bold; text-align: center;">
                            <?php echo number_format($suma_saldo, 2, ',', '.'); ?>
                        </td>
                        <td colspan="2" class="" style="font-weight: bold; text-align: center;">
                            Total Cuotas al mes
                        </td>
                        <td class="borde_titulo" style="font-weight: bold; text-align: center;">
                            <?php echo number_format($suma_cuota, 2, ',', '.'); ?>
                        </td>
                        <td colspan="2" class="" style="font-weight: bold; text-align: center;">

                        </td>

                    </tr>
                        
                </table>
            
            <?php
            }
            else
            {
                echo $this->lang->line('TablaNoRegistros');
            }
            ?>
                
        <?php
        }
        
        // SECCIÓN: OTROS INGRESOS Y BALANCE GENERAL

        if(in_array("otros_ingresos", $this->mfunciones_generales->getVistasRubro($rubro_unidad)))
        {
            $ingresoBalanceLead = $this->mfunciones_generales->IngresoBalanceLead($arrLead[0]['prospecto_id'], $arrLead[0]['prospecto_principal']);
            
            $ingresoBalanceSecundarias = $this->mfunciones_generales->IngresoBalanceSecundarias($arrLead[0]['prospecto_id'], $arrLead[0]['prospecto_principal']);
        ?>
            <br />
            
            <div class="titulo_seccion" style="width: 100%; text-justify: inter-word; font-weight: bold;">
                INDICADORES CAPACIDAD DE PAGO
            </div>
            
            <br />
            
            <table align="center" style="width: 100%;" cellspacing="0">
                
                <tr>
                    
                    <td valign="top" style="width: 50%; text-align: right;">
                        
                        <table align="left" style="width: 100%;" cellspacing="0">
                            
                            <tr>
                                <td style="width: 70%; text-align: center; font-weight: bold;" class="">
                                    <?php echo ($rubro_unidad==4 ? 'VIII. BALANCE GENERAL': 'BALANCE GENERAL');?>
                                </td>
                                <td style="width: 30%; text-align: center;" class="borde_titulo">
                                    <?php echo $fecha_actual_corta; ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: center; font-weight: bold;" class="">
                                    &nbsp;
                                </td>
                                <td style="width: 30%; text-align: center;" class="borde_caqui">
                                    Bs.
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="">
                                    <?php echo $this->lang->line('extra_efectivo_caja'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['extra_efectivo_caja'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="">
                                    <?php echo $this->lang->line('extra_ahorro_dpf'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['extra_ahorro_dpf'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="">
                                    <?php echo $this->lang->line('extra_cuentas_cobrar'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['extra_cuentas_cobrar'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="">
                                    <?php echo $this->lang->line('extra_inventario'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($calculo_lead->total_inventario, 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="">
                                    <?php echo $this->lang->line('extra_otros_activos_corrientes'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['extra_otros_activos_corrientes'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right; font-weight: bold;" class="">
                                    TOTAL ACTIVO CORRIENTE
                                </td>
                                <td style="width: 30%; text-align: right; font-weight: bold;" class="borde">
                                    <?php  echo number_format($ingresoBalanceLead->total_activo_corriente, 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="">
                                    <?php echo $this->lang->line('extra_activo_fijo'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['extra_activo_fijo'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="">
                                    <?php echo $this->lang->line('extra_otros_activos_nocorrientes'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['extra_otros_activos_nocorrientes'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right; font-weight: bold;" class="">
                                    TOTAL ACTIVO NO CORRIENTE
                                </td>
                                <td style="width: 30%; text-align: right; font-weight: bold;" class="borde">
                                    <?php  echo number_format($ingresoBalanceLead->total_activo_no_corriente, 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right; font-weight: bold;" class="">
                                    TOTAL ACTIVO DE LA ACTIVIDAD
                                </td>
                                <td style="width: 30%; text-align: right; font-weight: bold;" class="borde">
                                    <?php  echo number_format($ingresoBalanceLead->total_activo, 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="">
                                    <?php echo $this->lang->line('extra_activos_actividades_secundarias'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php
                                    
                                    $total_activo_secundarias = $ingresoBalanceSecundarias->total_activo_secundarias;
                                    echo number_format($total_activo_secundarias, 2, ',', '.'); 
                                    
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="">
                                    <?php echo $this->lang->line('extra_inmuebles_terrenos'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['extra_inmuebles_terrenos'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="">
                                    <?php echo $this->lang->line('extra_bienes_hogar'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['extra_bienes_hogar'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="">
                                    <?php echo $this->lang->line('extra_otros_activos_familiares'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['extra_otros_activos_familiares'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right; font-weight: bold;" class="">
                                    TOTAL ACTIVO DEL CLIENTE
                                </td>
                                <td style="width: 30%; text-align: right; font-weight: bold;" class="borde">
                                    <?php
                                    $total_activo_cliente = $ingresoBalanceLead->total_activo + $total_activo_secundarias + $arrLead[0]['extra_inmuebles_terrenos'] + $arrLead[0]['extra_bienes_hogar'] + $arrLead[0]['extra_otros_activos_familiares'];
                                    echo number_format($total_activo_cliente, 2, ',', '.'); 
                                    ?>
                                </td>
                            </tr>
                            
                        </table>
                        
                    </td>
                    
                    <td valign="top" style="width: 50%; text-align: right;">
                        
                        <table align="left" style="width: 100%;" cellspacing="0">
                            
                            <tr>
                                <td style="width: 70%; text-align: center; font-weight: bold;" class="">
                                    &nbsp;
                                </td>
                                <td style="width: 30%; text-align: center;" class="">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: center; font-weight: bold;" class="">
                                    &nbsp;
                                </td>
                                <td style="width: 30%; text-align: center;" class="">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="">
                                    <?php echo $this->lang->line('extra_cuentas_pagar_proveedores'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['extra_cuentas_pagar_proveedores'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="">
                                    <?php echo $this->lang->line('extra_prestamos_financieras_corto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['extra_prestamos_financieras_corto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="">
                                    <?php echo $this->lang->line('extra_cuentas_pagar_corto'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['extra_cuentas_pagar_corto'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right; font-weight: bold;" class="">
                                    TOTAL PASIVO CORRIENTE
                                </td>
                                <td style="width: 30%; text-align: right; font-weight: bold;" class="borde">
                                    <?php
                                    
                                    $total_pasivo_corriente = $ingresoBalanceLead->total_pasivo_corriente;
                                    echo number_format($total_pasivo_corriente, 2, ',', '.'); 
                                    
                                    ?>
                                </td>
                            </tr>
                            
                            <tr>
                                <td style="width: 70%; text-align: right;" class="">
                                    <?php echo $this->lang->line('extra_prestamos_financieras_largo'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['extra_prestamos_financieras_largo'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="">
                                    <?php echo $this->lang->line('extra_cuentas_pagar_largo'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['extra_cuentas_pagar_largo'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right; font-weight: bold;" class="">
                                    TOTAL PASIVO DE LA ACTIVIDAD
                                </td>
                                <td style="width: 30%; text-align: right; font-weight: bold;" class="borde">
                                    <?php
                                    
                                    $total_pasivo = $ingresoBalanceLead->total_pasivo;
                                    echo number_format($total_pasivo, 2, ',', '.'); 
                                    
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="">
                                    <?php echo $this->lang->line('extra_pasivo_actividades_secundarias'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php
                                    
                                    $total_pasivo_secundarias = $ingresoBalanceSecundarias->total_pasivo_secundarias;
                                    echo number_format($total_pasivo_secundarias, 2, ',', '.'); 
                                    
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right;" class="">
                                    <?php echo $this->lang->line('extra_pasivo_familiar'); ?>
                                </td>
                                <td style="width: 30%; text-align: right;" class="borde">
                                    <?php echo number_format($arrLead[0]['extra_pasivo_familiar'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right; font-weight: bold;" class="">
                                    PASIVO DEL CLIENTE
                                </td>
                                <td style="width: 30%; text-align: right; font-weight: bold;" class="borde">
                                    <?php
                                    
                                    $total_pasivo_cliente = $total_pasivo + $total_pasivo_secundarias + $arrLead[0]['extra_pasivo_familiar'];
                                    echo number_format($total_pasivo_cliente, 2, ',', '.'); 
                                    
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right; font-weight: bold;" class="">
                                    PATRIMONIO DE LA ACTIVIDAD
                                </td>
                                <td style="width: 30%; text-align: right; font-weight: bold;" class="borde">
                                    <?php
                                    
                                    $total_patrimonio = $ingresoBalanceLead->total_patrimonio;
                                    echo number_format($total_patrimonio, 2, ',', '.'); 
                                    
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right; font-weight: bold;" class="">
                                    PATRIMONIO DEL CLIENTE
                                </td>
                                <td style="width: 30%; text-align: right; font-weight: bold;" class="borde">
                                    <?php
                                    
                                    $total_patrimonio_cliente = $total_activo_cliente - $total_pasivo_cliente;
                                    echo number_format($total_patrimonio_cliente, 2, ',', '.'); 
                                    
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 70%; text-align: right; font-weight: bold;" class="">
                                    PATRIMONIO + PASIVO
                                </td>
                                <td style="width: 30%; text-align: right; font-weight: bold;" class="borde">
                                    <?php
                                    
                                    echo number_format($total_pasivo_cliente + $total_patrimonio_cliente, 2, ',', '.'); 
                                    
                                    ?>
                                </td>
                            </tr>
                            
                        </table>
                        
                        
                    </td>
                    
                </tr>
                
                <?php
                if($rubro_unidad == 4)
                {
                ?>
                
                    <tr>
                        <td valign="top" style="width: 50%; text-align: left;">
                            
                            <br />
                            
                            <table align="left" style="width: 100%;" cellspacing="0">
                            
                                <tr>
                                    <td colspan="2" style="width: 100%; text-align: left; font-weight: bold;" class="">
                                        VIII. ESTIMACIÓN DE LA CAPACIDAD DE PAGO
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        VENTAS
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculo_lead->ingreso_mensual_promedio, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        COSTO DE VENTAS
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculo_lead->transporte_costo_ventas, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde_titulo">
                                        UTILIDAD BRUTA
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculo_lead->transporte_utilidad_bruta, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        GASTOS OPERATIVOS
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculo_lead->operativos_otro_transporte_total, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde_titulo">
                                        UTILIDAD OPERATIVA
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculo_lead->transporte_utilidad_operativa, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        OTROS INGRESOS
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculo_lead->transporte_otros_ingresos, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        GASTOS FAMILIARES
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($calculo_lead->transporte_gastos_familiares, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde_titulo">
                                        UTILIDAD NETA
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculo_lead->transporte_utilidad_neta, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        CUOTAS DE OTRAS DEUDAS
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($calculo_lead->transporte_otras_deudas, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        SALDO DISPONIBLE
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculo_lead->transporte_saldo_disponible, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde">
                                        CUOTA PRESTAMO SOLICITADO
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($calculo_lead->transporte_cuota_prestamo, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="borde_titulo">
                                        MARGEN DE AHORRO
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde_titulo">
                                        <?php echo number_format($calculo_lead->transporte_margen_ahorro, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                
                            </table>
                            
                        </td>
                        <td valign="top" style="width: 50%; text-align: right;">
            
                            <table align="center" style="width: 100%;" cellspacing="0">

                                <tr>
                                    <td style="width: 100%; text-align: left; font-weight: bold; text-justify: inter-word;" class="">
                                        <br />DESTINO DE CREDITO Y PLAN DE INVERSIÓN
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 100%; text-align: justify; padding: 5px;" class="borde">
                                        <?php echo nl2br($arrProspecto[0]['general_destino']); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 100%; text-align: left; font-weight: bold; text-justify: inter-word;" class="">
                                        <br />COMENTARIOS
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 100%; text-align: justify; padding: 5px;" class="borde">
                                        <?php echo nl2br($arrProspecto[0]['general_comentarios']); ?>
                                    </td>
                                </tr>

                            </table>
                            
                        </td>
                    </tr>
                
                <?php
                }
                else
                {
                ?>
                
                    <tr>
                        <td valign="top" style="width: 50%; text-align: left;">

                            <table align="left" style="width: 100%;" cellspacing="0">

                                <tr>
                                    <td colspan="2" style="width: 100%; text-align: left; font-weight: bold;" class="">
                                        ESTIMACION DE CAPACIDAD DE PAGO
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: center;" class="">
                                        &nbsp;
                                    </td>
                                    <td style="width: 30%; text-align: center;" class="borde_caqui">
                                        Bs.
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        INGRESOS O VENTAS DE LA ACTIVIDAD
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($calculo_lead->ingreso_mensual_promedio, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        COSTO DE VENTAS ACTIVIDAD
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php 

                                        $costo_venta = (1-$mub_total)*$calculo_lead->ingreso_mensual_promedio;

                                        echo number_format($costo_venta, 2, ',', '.'); 

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right; font-weight: bold;" class="">
                                        UTILIDAD BRUTA
                                    </td>
                                    <td style="width: 30%; text-align: right; font-weight: bold;" class="borde">
                                        <?php 

                                        $utilidad_bruta = $calculo_lead->ingreso_mensual_promedio-$costo_venta;
                                        echo number_format($utilidad_bruta, 2, ',', '.'); 

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right; font-weight: bold;" class="">
                                        MUB
                                    </td>
                                    <td style="width: 30%; text-align: right; font-weight: bold;" class="borde">
                                        <?php echo number_format($mub_total*100, 2, ',', '.'); ?>%
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        GASTOS OPERATIVOS
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php 

                                        $gastos_operativos =   $arrLead[0]['operativos_alq_energia_monto'] + 
                                                            $arrLead[0]['operativos_alq_agua_monto'] + 
                                                            $arrLead[0]['operativos_alq_internet_monto'] + 
                                                            $arrLead[0]['operativos_alq_combustible_monto'] + 
                                                            $arrLead[0]['operativos_alq_libre1_monto'] + 
                                                            $arrLead[0]['operativos_alq_libre2_monto'] + 
                                                            $arrLead[0]['operativos_sal_aguinaldos_monto'] + 
                                                            $arrLead[0]['operativos_sal_libre1_monto'] + 
                                                            $arrLead[0]['operativos_sal_libre2_monto'] + 
                                                            $arrLead[0]['operativos_sal_libre3_monto'] + 
                                                            $arrLead[0]['operativos_sal_libre4_monto'] + 
                                                            $arrLead[0]['operativos_otro_transporte_monto'] + 
                                                            $arrLead[0]['operativos_otro_licencias_monto'] + 
                                                            $arrLead[0]['operativos_otro_alimentacion_monto'] + 
                                                            $arrLead[0]['operativos_otro_mant_vehiculo_monto'] + 
                                                            $arrLead[0]['operativos_otro_mant_maquina_monto'] + 
                                                            $arrLead[0]['operativos_otro_imprevistos_monto'] + 
                                                            $arrLead[0]['operativos_otro_otros_monto'] + 
                                                            $arrLead[0]['operativos_otro_libre1_monto'] + 
                                                            $arrLead[0]['operativos_otro_libre2_monto'] + 
                                                            $arrLead[0]['operativos_otro_libre3_monto'] + 
                                                            $arrLead[0]['operativos_otro_libre4_monto'] + 
                                                            $arrLead[0]['operativos_otro_libre5_monto'];

                                        echo number_format($gastos_operativos, 2, ',', '.'); 

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right; font-weight: bold;" class="">
                                        UTILIDAD OPERATIVA
                                    </td>
                                    <td style="width: 30%; text-align: right; font-weight: bold;" class="borde">
                                        <?php 

                                        $utilidad_operativa = $utilidad_bruta - $gastos_operativos;
                                        echo number_format($utilidad_operativa, 2, ',', '.'); 

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        Ingreso neto de la actividades secundarias
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php

                                        $ingreso_actividades_secundarias = $ingresoBalanceSecundarias->ingreso_actividades_secundarias + $calculo_lead->suma_otros_ingresos;
                                        echo number_format($ingreso_actividades_secundarias, 2, ',', '.'); 

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        Gastos Familiares
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php 

                                        $gastos_familiares =    $arrLead[0]['familiar_alimentacion_monto'] + 
                                                            $arrLead[0]['familiar_energia_monto'] + 
                                                            $arrLead[0]['familiar_agua_monto'] + 
                                                            $arrLead[0]['familiar_gas_monto'] + 
                                                            $arrLead[0]['familiar_telefono_monto'] + 
                                                            $arrLead[0]['familiar_celular_monto'] + 
                                                            $arrLead[0]['familiar_internet_monto'] + 
                                                            $arrLead[0]['familiar_tv_monto'] + 
                                                            $arrLead[0]['familiar_impuestos_monto'] + 
                                                            $arrLead[0]['familiar_alquileres_monto'] + 
                                                            $arrLead[0]['familiar_educacion_monto'] + 
                                                            $arrLead[0]['familiar_transporte_monto'] + 
                                                            $arrLead[0]['familiar_salud_monto'] + 
                                                            $arrLead[0]['familiar_empleada_monto'] + 
                                                            $arrLead[0]['familiar_diversion_monto'] + 
                                                            $arrLead[0]['familiar_vestimenta_monto'] + 
                                                            $arrLead[0]['familiar_otros_monto'] + 
                                                            $arrLead[0]['familiar_libre1_monto'] + 
                                                            $arrLead[0]['familiar_libre2_monto'] + 
                                                            $arrLead[0]['familiar_libre3_monto'] + 
                                                            $arrLead[0]['familiar_libre4_monto'] + 
                                                            $arrLead[0]['familiar_libre5_monto'];

                                        echo number_format($gastos_familiares, 2, ',', '.'); 

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right; font-weight: bold;" class="">
                                        RESULTADO NETO
                                    </td>
                                    <td style="width: 30%; text-align: right; font-weight: bold;" class="borde">
                                        <?php

                                        $resultado_neto = $utilidad_operativa + $ingreso_actividades_secundarias - $gastos_familiares;
                                        echo number_format($resultado_neto, 2, ',', '.'); 

                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        <?php 

                                        $aux_pasivos = $this->mfunciones_generales->IngresoBalanceLead($arrLead[0]["prospecto_id"], '', $segmento='pasivos');

                                        echo $this->lang->line('extra_amortizacion_otras_deudas'); 

                                        ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($aux_pasivos->total_otros_pasivos, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right; font-weight: bold;" class="">
                                        SALDO DISPONIBLE
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php

                                        $saldo_disponible = ($arrLead[0]['extra_personal_ocupado']>0 ? $resultado_neto - $aux_pasivos->total_otros_pasivos : 0);
                                        echo number_format($saldo_disponible, 2, ',', '.'); 

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        <?php echo $this->lang->line('extra_cuota_maxima_credito'); ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['extra_cuota_maxima_credito'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        <?php echo $this->lang->line('extra_amortizacion_credito'); ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['extra_amortizacion_credito'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right; font-weight: bold;" class="">
                                        MARGEN DE AHORRO
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php

                                        $margen_ahorro = ($arrLead[0]['extra_personal_ocupado']>0 ? $saldo_disponible - $arrLead[0]['extra_amortizacion_credito'] - $arrLead[0]['extra_cuota_maxima_credito'] : 0);
                                        echo number_format($margen_ahorro, 2, ',', '.'); 

                                        ?>
                                    </td>
                                </tr>

                            </table>

                        </td>
                        <td valign="top" style="width: 50%; text-align: right;">

                            <table align="left" style="width: 100%;" cellspacing="0">

                                <tr>
                                    <td style="width: 70%; text-align: center; font-weight: bold;" class="">
                                        INDICADORES FINANCIEROS
                                    </td>
                                    <td style="width: 30%; text-align: center;" class="">

                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        ÍNDICE DE LIQUIDEZ
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php
                                        // "TOTAL ACTIVO CORRIENTE"/"TOTAL PASIVO CORRIENTE" (en caso de error dar valor 0)
                                        $indice_liquidez = ($ingresoBalanceLead->total_pasivo_corriente!=0 ? $ingresoBalanceLead->total_activo_corriente/$ingresoBalanceLead->total_pasivo_corriente : 0);
                                        echo number_format($indice_liquidez, 2, ',', '.'); 

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        PRUEBA ÁCIDA
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php
                                        // ("TOTAL ACTIVO CORRIENTE"-"Inventario")/"TOTAL PASIVO CORRIENTE" (en caso de error dar valor 0)
                                        $prueba_acida = ($ingresoBalanceLead->total_pasivo_corriente!=0 ? ($ingresoBalanceLead->total_activo_corriente - $calculo_lead->total_inventario)/$ingresoBalanceLead->total_pasivo_corriente : 0);
                                        echo number_format($prueba_acida, 2, ',', '.'); 

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        ENDEUDAMIENTO  DEL  NEGOCIO 
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php
                                        // "TOTAL PASIVO DE LA ACTIVIDAD PRINCIPAL"/"TOTAL ACTIVO DE LA ACTIVIDAD PRINCIPAL" (en caso de error dar valor 0)
                                        $endeudamiento_negocio = ($ingresoBalanceLead->total_activo != 0 ? $total_pasivo/$ingresoBalanceLead->total_activo : 0);
                                        echo number_format($endeudamiento_negocio*100, 2, ',', '.'); 

                                        ?>%
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        PROPIEDAD DEL NEGOCIO 
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php
                                        // Preguntar si "ENDEUDAMIENTO  DEL  NEGOCIO " es > a 0, entonces; 1-"ENDEUDAMIENTO  DEL  NEGOCIO " (en caso de error dar valor 0)
                                        $propiedad_negocio = ($endeudamiento_negocio>0 ? 1-$endeudamiento_negocio : 0);
                                        echo number_format($propiedad_negocio*100, 2, ',', '.'); 

                                        ?>%
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        ENDEUDAMIENTO  DEL  CLIENTE
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php
                                        // "PASIVO DEL CLIENTE"/" TOTAL ACTIVO DEL CLIENTE"
                                        $endeudamiento_cliente = ($total_activo_cliente!=0 ? $total_pasivo_cliente/$total_activo_cliente : 0);
                                        echo number_format($endeudamiento_cliente*100, 2, ',', '.'); 

                                        ?>%
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        RETORNO SOBRE EL ACTIVO
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php
                                        // "RESULTADO NETO"/"TOTAL ACTIVO DEL CLIENTE"  (en caso de error dar valor 0)
                                        $retorno_activo = ($total_activo_cliente!=0 ? $resultado_neto/$total_activo_cliente : 0);
                                        echo number_format($retorno_activo*100, 2, ',', '.'); 

                                        ?>%
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        RETORNO SOBRE PATRIMONIO
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php
                                        // "RESULTADO NETO" / "PATRIMONIO DEL CLIENTE" (en caso de error dar valor 0)
                                        $retorno_patrimonio = ($total_patrimonio_cliente!=0 ? $resultado_neto/$total_patrimonio_cliente : 0);
                                        echo number_format($retorno_patrimonio*100, 2, ',', '.'); 

                                        ?>%
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        ROTACIÓN DE INVENTARIOS (dias)
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php
                                        // 30/("COSTO DE VENTAS ACT. PRINC."/"Inventario")
                                        $rotacion_inventario = ($costo_venta!=0 ? (30*$calculo_lead->total_inventario)/$costo_venta : 0);
                                        echo number_format($rotacion_inventario, 2, ',', '.');

                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 70%; text-align: right; text-transform: uppercase;" class="">
                                        <?php echo $this->lang->line('extra_endeudamiento_credito'); ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['extra_endeudamiento_credito'], 2, ',', '.'); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        SECTOR
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['camp_id'], 'nombre_rubro'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        VENTAS ANUALES
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($calculo_lead->ingreso_mensual_promedio*12, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        PATRIMONIO
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($total_patrimonio, 2, ',', '.'); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 70%; text-align: right; text-transform: uppercase;" class="">
                                        <?php echo $this->lang->line('extra_personal_ocupado'); ?>
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php echo number_format($arrLead[0]['extra_personal_ocupado'], 2, ',', '.'); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        INDICE DE TAMAÑO DE ACTIVIDAD 
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php
                                        /*
                                        Si es servicio 
                                        (("VENTAS ANUALES"/28000000)*(PATRIMONIO/14000000)*(PERSONAL OCUPADO/50)) ^(1/3) (se redondeara a 4 decimales como maximo) (en caso de error dar valor 0)

                                        Si es cualquir otro  
                                        (("VENTAS ANUALES"/35000000)*(PATRIMONIO/21000000)*(PERSONAL OCUPADO/100))^(1/3));4) (se redondeara a 4 decimales como maximo) (en caso de error dar valor 0)
                                        */

                                        if($arrLead[0]['camp_id'] == 2)
                                        {
                                            $indice_tamano_actividad = pow(((($calculo_lead->ingreso_mensual_promedio*12)/28000000) * ($total_patrimonio/14000000) * ($arrLead[0]['extra_personal_ocupado']/50)), (1/3));
                                        }
                                        else
                                        {
                                            $indice_tamano_actividad = pow(((($calculo_lead->ingreso_mensual_promedio*12)/35000000) * ($total_patrimonio/21000000) * ($arrLead[0]['extra_personal_ocupado']/100)), (1/3));
                                        }
                                        echo number_format($indice_tamano_actividad, 4, ',', '.');
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 70%; text-align: right;" class="">
                                        CLASIFICACION
                                    </td>
                                    <td style="width: 30%; text-align: right;" class="borde">
                                        <?php

                                        $clasificacion;

                                        switch ($indice_tamano_actividad) {

                                            case ($indice_tamano_actividad>0 && $indice_tamano_actividad<=0.035): $clasificacion='MICRO'; break;

                                            case ($indice_tamano_actividad>0.035 && $indice_tamano_actividad<=0.115): $clasificacion='PEQUEÑA'; break;

                                            case ($indice_tamano_actividad>0.115 && $indice_tamano_actividad<=1): $clasificacion='MEDIANA'; break;

                                            case ($indice_tamano_actividad>1): $clasificacion='GRANDE'; break;

                                            default: $clasificacion='0'; break;
                                        }

                                        /*
                                        Es micro cuando 
                                        INDICE DE TAMAÑO DE ACTIVIDAD es > a 0  y <=a 0,035

                                        Es Pequeña cuando 
                                        INDICE DE TAMAÑO DE ACTIVIDAD es > 0,035  y <=0,115

                                        Es MEDIANA cuando 
                                        INDICE DE TAMAÑO DE ACTIVIDAD es > 0,115 y <=1

                                        Es GRAN cuando 
                                        INDICE DE TAMAÑO DE ACTIVIDAD es >1

                                        En caso de dar valor negativo o errror, colocar 0 
                                        */
                                        echo $clasificacion;

                                        ?>
                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top" style="width: 100%; text-align: left;">
                            
                            <br />
            
                            <table align="center" style="width: 100%;" cellspacing="0">

                                <tr>
                                    <td style="width: 100%; text-align: left; font-weight: bold; text-justify: inter-word;" class="">
                                        <br />EXPLICACION DEL DESTINO DE CREDITO Y PLAN DE INVERSIÓN
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 100%; text-align: justify; padding: 5px;" class="borde">
                                        <?php echo nl2br($arrProspecto[0]['general_destino']); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 100%; text-align: left; font-weight: bold; text-justify: inter-word;" class="">
                                        <br />COMENTARIOS
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 100%; text-align: justify; padding: 5px;" class="borde">
                                        <?php echo nl2br($arrProspecto[0]['general_comentarios']); ?>
                                    </td>
                                </tr>

                            </table>
                            
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
                
        <?php
        }
        
        /* ----- ANEXOS ----- */
        
        // Datos del margen
        
        $arrMargen = $this->mfunciones_logica->ObtenerListaProductos($arrLead[0]['prospecto_id'], -1, 0);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrMargen);
       
        $sw_anexo = 0;
        
        if(isset($arrMargen[0]) && $calculo_lead->metodo_inventario==0)
        {
            $sw_anexo = 1;
            
            /* NUEVA PÁGINA */ echo '<pagebreak />';
        ?>
            <table style="width: 100%;">
                <tr>
                    <td style="width: 40%; text-align: left;">
                        <img style="width: 200px;" src="<?php echo $this->config->base_url(); ?>html_public/imagenes/reportes/logo.jpg" />
                    </td>
                    <td style="width: 60%; text-align: right; font-size: 16px;">

                        <table style="width: 100%;" border="0" cellspacing="0">
                            <tr>
                                <td style="width: 35%; text-align: right; font-weight: bold;">
                                    NOMBRE DEL CLIENTE:
                                </td>
                                <td style="width: 65%; text-align: justify;" class="borde">
                                    <?php echo $titular_nombre; ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 35%; text-align: center; font-weight: bold; font-style: italic;">
                                    
                                </td>
                                <td style="width: 65%; text-align: right; font-style: italic;" class="">
                                    <?php echo $familia_nombre; ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <br />

            <div align="center" style="width: 100%; text-align: center; text-justify: inter-word; font-weight: bold;">
                ANEXO I - INVENTARIO
            </div>

            <table style="width: 100%;">
                <tr>
                    <td style="width: 30%; text-align: left;">

                    </td>
                    <td style="width: 40%; text-align: center; font-weight: bold;">
                        ACTIVIDAD <?php echo ($arrLead[0]['prospecto_principal'] == 1 ? 'PRINCIPAL' : 'SECUNDARIA') ?>
                    </td>
                    <td style="width: 15%; text-align: right; font-weight: bold;">
                        Moneda
                    </td>
                    <td style="width: 15%; text-align: center;" class="borde">
                        Bolivianos
                    </td>
                </tr>
            </table>

            <br />

            <table style="width: 100%;" cellspacing="0">
                <tr>
                    <td style="width: 25%; text-align: center;" class="borde_titulo">
                        Descripción de la Actividad
                    </td>
                    <td style="width: 50%; text-align: center;" class="borde">
                        <?php echo $arrLead[0]['general_actividad']; ?>
                    </td>
                    <td style="width: 10%; text-align: center;" class="borde_titulo">
                        Sector
                    </td>
                    <td style="width: 15%; text-align: center;" class="borde">
                        <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['camp_id'], 'nombre_rubro'); ?>
                    </td>
                </tr>
            </table>
            
            <br />
            
            <?php
            
            // Se obtienen los totales
                
            $total_mp = 0;
            $total_pp = 0;
            $total_pt = 0;
            $total_mercaderia = 0;

            foreach ($arrMargen as $key => $value_inventario) 
            {
                /*  1=MP
                    2=PP
                    3=PT
                    4=Mercadería
                */

                $inventario_total_costo = $value_inventario['producto_compra_cantidad'] * $value_inventario['producto_compra_precio'];

                switch ($value_inventario["producto_categoria_mercaderia"]) {

                    case 1: $total_mp += $inventario_total_costo; break;
                    case 2: $total_pp += $inventario_total_costo; break;
                    case 3: $total_pt += $inventario_total_costo; break;
                    case 4: $total_mercaderia += $inventario_total_costo; break;

                    default:
                        break;
                }
            }
            
            $inventario_total_costo_suma = $total_mp + $total_pp + $total_pt + $total_mercaderia;
            
            ?>
            
            <table style="width: 100%;" cellspacing="0">
                <tr>
                    <td style="width: 85%; text-align: right;" class="">
                        TOTAL
                    </td>
                    <td style="width: 15%; text-align: right;" class="borde_titulo">
                        <?php echo number_format($inventario_total_costo_suma, 2, ',', '.'); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 85%; text-align: right;" class="">
                        &nbsp;
                    </td>
                    <td style="width: 15%; text-align: right;" class="">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td style="width: 85%; text-align: right;" class="">
                        MERCADERIA
                    </td>
                    <td style="width: 15%; text-align: right;" class="borde_titulo">
                        <?php echo number_format($total_mercaderia, 2, ',', '.'); ?>
                    </td>
                </tr>
                
                <?php
                if($arrLead[0]['camp_id']==1 || $arrLead[0]['camp_id']==2)
                {
                ?>
                    <tr>
                        <td style="width: 85%; text-align: right;" class="">
                            MP E INSUMOS
                        </td>
                        <td style="width: 15%; text-align: right;" class="borde_titulo">
                            <?php echo number_format($total_mp, 2, ',', '.'); ?>
                        </td>
                    </tr>
                    
                <?php
                }
                if($arrLead[0]['camp_id']==1)
                {
                ?>
                    
                    <tr>
                        <td style="width: 85%; text-align: right;" class="">
                            PROD. TERM.
                        </td>
                        <td style="width: 15%; text-align: right;" class="borde_titulo">
                            <?php echo number_format($total_pt, 2, ',', '.'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 85%; text-align: right;" class="">
                            PROD. PROC.
                        </td>
                        <td style="width: 15%; text-align: right;" class="borde_titulo">
                            <?php echo number_format($total_pp, 2, ',', '.'); ?>
                        </td>
                    </tr>
                    
                <?php
                }
                ?>
            </table>
            
            <br />
            
            <table align="center" style="width: 100%;" cellspacing="0">
                <tr>
                    <th style="width: 20%; text-align: center; font-weight: bold;" class="borde">
                        Descripción del Producto
                    </th>
                    <th style="width: 10%; text-align: center; font-weight: bold;" class="borde">
                        Cantidad Comprada
                    </th>
                    <th style="width: 10%; text-align: center; font-weight: bold;" class="borde">
                        Unidad de Medida de Compra
                    </th>
                    <th style="width: 10%; text-align: center; font-weight: bold;" class="borde">
                        Categoría (<?php echo ($arrLead[0]['camp_id']==1||$arrLead[0]['camp_id']==2 ? 'Mercadería, MP' : 'Mercadería'); echo ($arrLead[0]['camp_id']==1 ? ', PP, PT' : ''); ?>)
                    </th>
                    <th style="width: 10%; text-align: center; font-weight: bold;" class="borde">
                        Precio de Compra Unitarios Bs (Costo unitario)
                    </th>
                    <th style="width: 10%; text-align: center; font-weight: bold;" class="borde">
                        COSTO TOTAL
                    </th>
                </tr>

                <?php
                
                foreach ($arrMargen as $key => $value) 
                {
                ?>
                    <tr>
                        <td style="width: 20%; text-align: center;" class="borde">
                            <?php echo $value['producto_nombre']; ?>
                        </td>
                        <td style="width: 10%; text-align: right;" class="borde">
                            <?php echo number_format($value['producto_compra_cantidad'], 2, ',', '.'); ?>
                        </td>
                        <td style="width: 10%; text-align: center;" class="borde">
                            <?php echo $value['producto_compra_medida']; ?>
                        </td>
                        <td style="width: 10%; text-align: center;" class="borde">
                            <?php echo $this->mfunciones_generales->GetValorCatalogo($value["producto_categoria_mercaderia"], 'categoria_mercaderia'); ?>
                        </td>
                        <td style="width: 10%; text-align: right;" class="borde">
                            <?php
                            
                            //$producto_venta_costo = ($value["producto_compra_precio"]!=0 ? $value["producto_unidad_venta_unidad_compra"]/$value["producto_compra_precio"] : 0);
                            $producto_venta_costo = $value["producto_compra_precio"];
                            
                            echo number_format($producto_venta_costo, 2, ',', '.'); 
                            ?>
                        </td>
                        <td style="width: 10%; text-align: right;" class="borde">
                            <?php
                            
                            $producto_venta_costo_total = ($value['producto_compra_cantidad'] * $value['producto_compra_precio']);
                            
                            echo number_format($producto_venta_costo_total, 2, ',', '.'); 
                            ?>
                        </td>
                        
                    </tr>

                <?php

                }
                ?>

            </table>
        
        <?php
        }
            
        /* ----- ANEXOS ----- */
        
        // Detalle de Costos
        
        $arrDetalleCostos = $this->mfunciones_logica->ObtenerListaProductosCostos($arrLead[0]['prospecto_id']);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDetalleCostos);
        
        if(isset($arrDetalleCostos[0]))
        {
            $h = 0;
            
            foreach ($arrDetalleCostos as $key => $value_costos) 
            {
                $sw_tabla_costos = 0;
                
                $h++;
                /* NUEVA PÁGINA */ echo '<pagebreak />';
        ?>
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 40%; text-align: left;">
                            <img style="width: 200px;" src="<?php echo $this->config->base_url(); ?>html_public/imagenes/reportes/logo.jpg" />
                        </td>
                        <td style="width: 60%; text-align: right; font-size: 16px;">

                            <table style="width: 100%;" border="0" cellspacing="0">
                                <tr>
                                    <td style="width: 35%; text-align: right; font-weight: bold;">
                                        NOMBRE DEL CLIENTE:
                                    </td>
                                    <td style="width: 65%; text-align: justify;" class="borde">
                                        <?php echo $titular_nombre; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 35%; text-align: center; font-weight: bold; font-style: italic;">

                                    </td>
                                    <td style="width: 65%; text-align: right; font-style: italic;" class="">
                                        <?php echo $familia_nombre; ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <br />

                <div align="center" style="width: 100%; text-align: center; text-justify: inter-word; font-weight: bold;">
                    <?php echo ($sw_anexo==1 ? 'ANEXO II' : 'ANEXO I'); ?> - HOJA DE COSTOS N° <?php echo $h; ?>
                </div>

                <table style="width: 100%;">
                    <tr>
                        <td style="width: 30%; text-align: left;">

                        </td>
                        <td style="width: 40%; text-align: center; font-weight: bold;">
                            ACTIVIDAD <?php echo ($arrLead[0]['prospecto_principal'] == 1 ? 'PRINCIPAL' : 'SECUNDARIA') ?>
                        </td>
                        <td style="width: 15%; text-align: right; font-weight: bold;">
                            Moneda
                        </td>
                        <td style="width: 15%; text-align: center;" class="borde">
                            Bolivianos
                        </td>
                    </tr>
                </table>

                <br />

                <table style="width: 100%;" cellspacing="0">
                    <tr>
                        <td style="width: 25%; text-align: center;" class="borde_titulo">
                            Descripción de la Actividad
                        </td>
                        <td style="width: 50%; text-align: center;" class="borde">
                            <?php echo $arrLead[0]['general_actividad']; ?>
                        </td>
                        <td style="width: 10%; text-align: center;" class="borde_titulo">
                            Sector
                        </td>
                        <td style="width: 15%; text-align: center;" class="borde">
                            <?php echo $this->mfunciones_generales->GetValorCatalogo($arrLead[0]['camp_id'], 'nombre_rubro'); ?>
                        </td>
                    </tr>
                </table>

                <br />

                <?php
                
                $producto_nombre = $value_costos['producto_nombre'];
                $producto_costo_medida_unidad = $value_costos['producto_costo_medida_unidad'];
                $producto_costo_medida_cantidad = $value_costos['producto_costo_medida_cantidad'];
                $producto_costo_medida_precio = $value_costos['producto_costo_medida_precio'];

                $total_costo_detalle_suma = 0;
                
                // Listado de los No Seleccionados
                $arrDetalle = $this->mfunciones_logica->ObtenerListaDetalleProductos($value_costos['producto_id'], -1);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDetalle);

                if(isset($arrDetalle[0]))
                {
                    foreach ($arrDetalle as $key => $value_aux_detalle) 
                    {
                        // COSTO PARA COSTOS DE PRODUCCION
                        if($value_aux_detalle['detalle_costo_medida_convertir'] == 0)
                        {
                            $detalle_costo_unitario_auxn = $value_aux_detalle["detalle_costo_unitario"];
                        }
                        else
                        {
                            $sw_tabla_costos = 1;
                            
                            $detalle_costo_unitario_auxn = ($value_aux_detalle["detalle_costo_unidad_medida_cantidad"]!=0 ? $value_aux_detalle["detalle_costo_medida_precio"]/$value_aux_detalle["detalle_costo_unidad_medida_cantidad"] : 0);
                        }

                        $total_costo_detalle_suma += $value_aux_detalle["detalle_cantidad"] * $detalle_costo_unitario_auxn;
                    }

                    ?>

                    <table style="width: 100%;" cellspacing="0">
                        <tr>
                            <td style="width: 60%; text-align: right;" class="">
                                Descripcion del Producto:
                            </td>
                            <td style="width: 40%; text-align: right; text-transform: uppercase;" class="borde_titulo">
                                <?php echo $producto_nombre; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 60%; text-align: right; text-transform: uppercase;" class="">
                                Unidad de Medida
                            </td>
                            <td style="width: 40%; text-align: right;" class="borde_titulo">
                                <?php echo $producto_costo_medida_unidad; ?>
                            </td>
                        </tr>
                    </table>

                    <table style="width: 100%;" cellspacing="0">
                        <tr>
                            <td style="width: 85%; text-align: right;" class="">
                                Cantidad según unidad de medida
                            </td>
                            <td style="width: 15%; text-align: right;" class="borde_titulo">
                                <?php echo number_format($producto_costo_medida_cantidad, 2, ',', '.'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 85%; text-align: right;" class="">
                                Precio de venta según unidad de medida
                            </td>
                            <td style="width: 15%; text-align: right;" class="borde_titulo">
                                <?php echo number_format($producto_costo_medida_precio, 2, ',', '.'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 85%; text-align: right;" class="">
                                Costo total
                            </td>
                            <td style="width: 15%; text-align: right;" class="borde_titulo">
                                <?php echo number_format($total_costo_detalle_suma, 2, ',', '.'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 85%; text-align: right;" class="">
                                Costo Unitario Bs.
                            </td>
                            <td style="width: 15%; text-align: right;" class="borde_titulo">
                                <?php echo number_format(($producto_costo_medida_cantidad!=0 ? $total_costo_detalle_suma/$producto_costo_medida_cantidad : 0), 2, ',', '.'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 85%; text-align: right;" class="">
                                Margen de Utilidad bruta
                            </td>
                            <td style="width: 15%; text-align: right;" class="borde_titulo">
                                <?php echo number_format(((int)$producto_costo_medida_cantidad!=0 && $producto_costo_medida_precio!=0 ? 1-(($total_costo_detalle_suma/$producto_costo_medida_cantidad)/$producto_costo_medida_precio) : 0)*100, 2, ',', '.'); ?>%
                            </td>
                        </tr>
                    </table>

                    <br />

                    <?php

                        if(count($arrDetalle) > 0)
                        {
                            // Si tiene al menos 1 registro que indica conversión, se muestra sólo 5 columnas
                            if($sw_tabla_costos == 1)
                            {

                    ?>

                                <table align="center" style="width: 100%;" cellspacing="0">
                                    <tr>
                                        <th class="borde" style="width: 9%; font-weight: bold; text-align: center;">
                                            <?php echo $this->lang->line('detalle_descripcion'); ?>
                                        </th>

                                        <th class="borde" style="width: 8%; font-weight: bold; text-align: center;">
                                            <?php echo $this->lang->line('detalle_cantidad'); ?>
                                        </th>

                                        <th class="borde" style="width: 9%; font-weight: bold; text-align: center;">
                                            <?php echo $this->lang->line('detalle_unidad'); ?>
                                        </th>

                                        <th class="borde" style="width: 8%; font-weight: bold; text-align: center;">
                                            <?php echo $this->lang->line('detalle_costo_unitario'); ?>
                                        </th>

                                        <th class="borde" style="width: 8%; font-weight: bold; text-align: center;">
                                            <?php echo $this->lang->line('detalle_costo_medida_convertir'); ?>
                                        </th>

                                        <th class="borde" style="width: 9%; font-weight: bold; text-align: center;">
                                            <?php echo $this->lang->line('detalle_costo_medida_unidad'); ?>
                                        </th>
                                        <th class="borde" style="width: 8%; font-weight: bold; text-align: center;">
                                            <?php echo $this->lang->line('detalle_costo_medida_precio'); ?>
                                        </th>

                                        <th class="borde" style="width: 9%; font-weight: bold; text-align: center;">
                                            <?php echo $this->lang->line('detalle_costo_unidad_medida_uso'); ?>
                                        </th>
                                        <th class="borde" style="width: 8%; font-weight: bold; text-align: center;">
                                            <?php echo $this->lang->line('detalle_costo_unidad_medida_cantidad'); ?>
                                        </th>

                                        <th class="borde" style="width: 8%; font-weight: bold; text-align: center;">
                                            COSTO PARA COSTOS DE PRODUCCION
                                        </th>
                                        <th class="borde" style="width: 8%; font-weight: bold; text-align: center;">
                                            UNIDAD DE MEDIDA PARA COSTOS DE PRODUCCION
                                        </th>

                                        <th class="borde" style="width: 8%; font-weight: bold; text-align: center;">
                                            TOTAL Bs.
                                        </th>
                                    </tr>

                                        <?php

                                        foreach ($arrDetalle as $key => $value_detalle) 
                                        {
                                        ?>
                                            <tr>

                                                <td class="borde" style="width: 9%; font-weight: normal; text-align: center;">
                                                    <?php echo $value_detalle["detalle_descripcion"]; ?>
                                                </td>

                                                <td class="borde" style="width: 8%; font-weight: normal; text-align: right; font-weight: bold;">
                                                    <?php echo number_format($value_detalle["detalle_cantidad"], 2, ',', '.'); ?>
                                                </td>

                                                <td class="borde" style="width: 9%; font-weight: normal; text-align: center; font-weight: bold;">
                                                    <?php echo $value_detalle["detalle_unidad"]; ?>
                                                </td>

                                                <td class="borde" style="width: 8%; font-weight: normal; text-align: right;">
                                                    <?php echo number_format($value_detalle["detalle_costo_unitario"], 2, ',', '.'); ?>
                                                </td>

                                                <td class="borde" style="width: 8%; font-weight: normal; text-align: center;">
                                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($value_detalle["detalle_costo_medida_convertir"], 'si_no'); ?>
                                                </td>

                                                <td class="borde" style="width: 9%; font-weight: normal; text-align: center;">
                                                    <?php echo $value_detalle["detalle_costo_medida_unidad"]; ?>
                                                </td>
                                                <td class="borde" style="width: 8%; font-weight: normal; text-align: right;">
                                                    <?php echo number_format($value_detalle["detalle_costo_medida_precio"], 2, ',', '.'); ?>
                                                </td>

                                                <td class="borde" style="width: 9%; font-weight: normal; text-align: center;">
                                                    <?php echo $value_detalle["detalle_costo_unidad_medida_uso"]; ?>
                                                </td>

                                                <td class="borde" style="width: 8%; font-weight: normal; text-align: right;">
                                                    <?php echo number_format($value_detalle["detalle_costo_unidad_medida_cantidad"], 2, ',', '.'); ?>
                                                </td>

                                                <td class="borde" style="width: 8%; font-weight: normal; text-align: right;">
                                                    <?php 
                                                    // COSTO PARA COSTOS DE PRODUCCION
                                                    if($value_detalle['detalle_costo_medida_convertir'] == 0)
                                                    {
                                                        $detalle_costo_produccion = $value_detalle["detalle_costo_unitario"];
                                                    }
                                                    else
                                                    {
                                                        $detalle_costo_produccion = ($value_detalle["detalle_costo_unidad_medida_cantidad"]!=0 ? $value_detalle["detalle_costo_medida_precio"]/$value_detalle["detalle_costo_unidad_medida_cantidad"] : 0);
                                                    }

                                                    echo ($value_detalle['detalle_costo_medida_convertir']==1 ? number_format($detalle_costo_produccion, 2, ',', '.') : 'No');

                                                    ?>
                                                </td>

                                                <td class="borde" style="width: 8%; font-weight: normal; text-align: center;">
                                                    <?php echo ($value_detalle['detalle_costo_medida_convertir']==1 ? $value_detalle['detalle_costo_medida_unidad'] : 'No'); ?>
                                                </td>

                                                <td class="borde" style="width: 8%; font-weight: normal; text-align: right;">
                                                    <?php

                                                    $total_costo_detalle = $value_detalle["detalle_cantidad"] * $detalle_costo_produccion; 

                                                    echo number_format($total_costo_detalle, 2, ',', '.'); 
                                                    ?>
                                                </td>

                                            </tr>

                                        <?php

                                        }
                                        ?>

                                </table>
                    
        <?php
                            }
                            
                            // Si no tiene ningún registro que indica conversión, se muestra todas las columnas
                            if($sw_tabla_costos == 0)
                            {

                    ?>

                                <table align="center" style="width: 100%;" cellspacing="0">
                                    <tr>
                                        <th class="borde" style="width: 20%; font-weight: bold; text-align: center;">
                                            <?php echo $this->lang->line('detalle_descripcion'); ?>
                                        </th>

                                        <th class="borde" style="width: 20%; font-weight: bold; text-align: center;">
                                            <?php echo $this->lang->line('detalle_cantidad'); ?>
                                        </th>

                                        <th class="borde" style="width: 20%; font-weight: bold; text-align: center;">
                                            <?php echo $this->lang->line('detalle_unidad'); ?>
                                        </th>

                                        <th class="borde" style="width: 20%; font-weight: bold; text-align: center;">
                                            <?php echo $this->lang->line('detalle_costo_unitario'); ?>
                                        </th>

                                        <th class="borde" style="width: 20%; font-weight: bold; text-align: center;">
                                            TOTAL Bs.
                                        </th>
                                    </tr>

                                        <?php

                                        foreach ($arrDetalle as $key => $value_detalle) 
                                        {
                                        ?>
                                            <tr>

                                                <td class="borde" style="width: 20%; font-weight: normal; text-align: center;">
                                                    <?php echo $value_detalle["detalle_descripcion"]; ?>
                                                </td>

                                                <td class="borde" style="width: 20%; font-weight: normal; text-align: right; font-weight: bold;">
                                                    <?php echo number_format($value_detalle["detalle_cantidad"], 2, ',', '.'); ?>
                                                </td>

                                                <td class="borde" style="width: 20%; font-weight: normal; text-align: center; font-weight: bold;">
                                                    <?php echo $value_detalle["detalle_unidad"]; ?>
                                                </td>

                                                <td class="borde" style="width: 20%; font-weight: normal; text-align: right;">
                                                    <?php echo number_format($value_detalle["detalle_costo_unitario"], 2, ',', '.'); ?>
                                                </td>

                                                <td class="borde" style="width: 20%; font-weight: normal; text-align: right;">
                                                    <?php

                                                    // COSTO PARA COSTOS DE PRODUCCION
                                                    if($value_detalle['detalle_costo_medida_convertir'] == 0)
                                                    {
                                                        $detalle_costo_produccion = $value_detalle["detalle_costo_unitario"];
                                                    }
                                                    else
                                                    {
                                                        $detalle_costo_produccion = ($value_detalle["detalle_costo_unidad_medida_cantidad"]!=0 ? $value_detalle["detalle_costo_medida_precio"]/$value_detalle["detalle_costo_unidad_medida_cantidad"] : 0);
                                                    }
                                                    
                                                    $total_costo_detalle = $value_detalle["detalle_cantidad"] * $detalle_costo_produccion; 

                                                    echo number_format($total_costo_detalle, 2, ',', '.');
                                                    
                                                    ?>
                                                </td>

                                            </tr>

                                        <?php

                                        }
                                        ?>

                                </table>
                    
        <?php
                            }
                            
                        }
        
                }
                else
                {
                    echo '
                    <div class="titulo_seccion" style="width: 100%; text-justify: inter-word; font-weight: bold;">
                        No registró detalle de Costeo de: ' . (isset($producto_nombre) ? $producto_nombre : '') . '
                    </div>';
                }
            }
        }
        ?>
        

    </div>
        
<?php
// Actualización 22-01, sólo se imprime el primer registro, luego sale

    if($contenido_completo!='completo')
    {
        break;
    }
}
?>
    
