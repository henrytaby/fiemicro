    
<script type="text/javascript">

    function VerTablaResumen(id) {

        $("#"+id).slideToggle();
    }

</script>

<?php

$texto_agente = "";
        
if(count($arrSeguimiento[0]) > 0)
{
    $texto_agente = "<br />" . $arrSeguimiento[0][0]['agente_nombre'];
}

?>

<div style="overflow-y: auto; height: 400px;">

    <div class="FormularioSubtitulo"> <?php echo $this->lang->line('lead_seguimiento_agente') . $texto_agente; ?></div>

    <div style="clear: both"></div>

    <br />

    <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaResumen('1');">
        <i class='fa fa-eye' aria-hidden='true'></i> MOSTRAR/OCULTAR AVANCE POR CAMPAÑA
    </div>

    <br />

    <div style="display: none;" id="1">
        
    <?php

    // Se guardará todo el HTML en la variable $html_body
    
    $html_body = "";

    if(count($arrSeguimiento[0]) > 0)
    {
        foreach ($arrSeguimiento as $key => $value)
        {
            
            $termino = '<br /><br /><h1 style="text-align: center;">&nbsp;</h1>';
            
            if($arrSeguimiento[$key][0]["avance_campana_dias_porcentaje"] == '100,00')
            {
                $termino = '<br /><br /><h1 style="text-align: center;">La Campaña Cuncluyó!</h1>';
            }
            
            $html_body .= '
    
            <div style="float: left; width: 45%; padding: 5px 15px; box-sizing: border-box; min-height: 300px;">

                <fieldset>
                <legend> ' . $arrSeguimiento[$key][0]["campana_nombre"] . '</legend>

                    <h2>Campaña: <span style="font-size: 2em; color: #00577f; text-shadow: #5a5a5a 0px 1px 1px;">' . number_format($arrSeguimiento[$key][0]["campana_monto_oferta"], 2, ',', '.') . '</span></h2>

                    <h2 style="text-align: right; float: left; width: 95px; padding: 0px 10px;">Desembolso Campaña: </h2> <span style="font-size: 2.5em; font-weight: bold; color: #00577f; text-shadow: #5a5a5a 0px 1px 1px;">' . $arrSeguimiento[$key][0]["avance_desembolso_numero"] . '</span>

                    <br /><br /><br />

                    <div style="text-align: right;">

                        <span style="font-size: 1.2em; font-weight: bold; color: #006699;">Avance Desembolso Campaña</span>

                    </div>

                    <div class="outter">
                            <div class="inner" style="width: ' . $arrSeguimiento[$key][0]["avance_desembolso_porcentaje_numero"] . '%;">
                                    ' . $arrSeguimiento[$key][0]["avance_desembolso_porcentaje_numero"] . '%
                            </div>
                    </div>

                    <br />

                    <h2 style="text-align: center;">Tiempo Campaña: <span style="font-size: 2em; color: #00577f; text-shadow: #5a5a5a 0px 1px 1px;">' . $arrSeguimiento[$key][0]["campana_plazo"] . ' días</span></h2>

                    <h2 style="text-align: right; float: left; width: 125px; padding: 0px 10px;">¿Cuánto avanzó la Campaña? </h2> <span style="font-size: 2.5em; font-weight: bold; color: #00577f; text-shadow: #5a5a5a 0px 1px 1px;">' . $arrSeguimiento[$key][0]["avance_campana_dias_porcentaje"] . '%</span>

                    ' . $termino . '

                </fieldset> <br />
            </div>';

        }
        
        echo $html_body;
    }

    else
    {            
        echo $this->lang->line('TablaNoResultados');
    }

    ?>
        
    </div>
    
    <div style="clear: both;"></div>
    
    <br />
    
    <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaResumen('2');">
        <i class='fa fa-eye' aria-hidden='true'></i> MOSTRAR/OCULTAR ESTADO DE OPERACIONES
    </div>

    <br />

    <div style="display: none;" id="2">

    <?php

    // Se guardará todo el HTML en la variable $html_body
    
    $html_body = "";

    $html_body .= "<p>" . $this->lang->line('lead_seg_estado_operacion_titulo') . "</p> <br />";

    if(count($arrSeguimiento[0]) > 0)
    {
        foreach ($arrSeguimiento as $key => $value)
        {
            
            $html_body .= '

            <div style="float: left; width: 45%; padding: 5px 15px; box-sizing: border-box; min-height: 300px;">
            
                <fieldset>
                <legend> ' . $arrSeguimiento[$key][0]["campana_nombre"] . '<br /> La Campaña Concluye el ' . $arrSeguimiento[$key][0]["campana_fecha_final"] . '</legend>

                    <table style="width: 95% !important;" border="0" class="tblListas Centrado">

                        <tr class="FilaBlanca">

                            <th style="width: 50%; font-weight: bold; color: #ffffff; background-color: #006699 !important;">
                                Etapa / Estado
                            </th>

                            <th style="width: 25%; font-weight: bold; color: #ffffff; background-color: #006699 !important;">
                                Cantidad Clientes
                            </th>

                            <th style="width: 25%; font-weight: bold; color: #ffffff; background-color: #006699 !important;">
                                Porcentaje
                            </th>
                        </tr>

                        <tr class="FilaBlanca">

                            <td style="width: 50%;">
                                ' . $arrEtapas[array_search(1, array_column($arrEtapas,"etapa_id"))]["etapa_nombre"] . '
                            </td>

                            <td style="width: 25%; text-align: right;">
                                ' . $arrSeguimiento[$key][0]["contador_" . "asignado"] . '
                            </td>

                            <td style="width: 25%; text-align: right;">
                                ' . $arrSeguimiento[$key][0]["porcentaje_" . "asignado"] . '%
                            </td>
                        </tr>

                        <tr class="FilaBlanca">

                            <td>
                                ' . $arrEtapas[array_search(2, array_column($arrEtapas,"etapa_id"))]["etapa_nombre"] . '
                            </td>

                            <td style="text-align: right;">
                                ' . $arrSeguimiento[$key][0]["contador_" . "interes"] . '
                            </td>

                            <td style="text-align: right;">
                                ' . $arrSeguimiento[$key][0]["porcentaje_" . "interes"] . '%
                            </td>
                        </tr>
                        
                        <tr style="background-color: #ffeeee !important;" class="FilaBlanca">

                            <td>
                                ' . $arrEtapas[array_search(10, array_column($arrEtapas,"etapa_id"))]["etapa_nombre"] . '
                            </td>

                            <td style="text-align: right;">
                                ' . $arrSeguimiento[$key][0]["contador_" . "nointeres"] . '
                            </td>

                            <td style="text-align: right;">
                                ' . $arrSeguimiento[$key][0]["porcentaje_" . "nointeres"] . '%
                            </td>
                        </tr>

                        <tr class="FilaBlanca">

                            <td>
                                ' . $arrEtapas[array_search(3, array_column($arrEtapas,"etapa_id"))]["etapa_nombre"] . '
                            </td>

                            <td style="text-align: right;">
                                ' . $arrSeguimiento[$key][0]["contador_" . "cierre"] . '
                            </td>

                            <td style="text-align: right;">
                                ' . $arrSeguimiento[$key][0]["porcentaje_" . "cierre"] . '%
                            </td>
                        </tr>

                        <tr class="FilaBlanca">

                            <td>
                                ' . $arrEtapas[array_search(4, array_column($arrEtapas,"etapa_id"))]["etapa_nombre"] . '
                            </td>

                            <td style="text-align: right;">
                                ' . $arrSeguimiento[$key][0]["contador_" . "entrega"] . '
                            </td>

                            <td style="text-align: right;">
                                ' . $arrSeguimiento[$key][0]["porcentaje_" . "entrega"] . '%
                            </td>
                        </tr>

                        <tr class="FilaBlanca">

                            <td>
                                ' . $arrEtapas[array_search(5, array_column($arrEtapas,"etapa_id"))]["etapa_nombre"] . '
                            </td>

                            <td style="text-align: right;">
                                ' . $arrSeguimiento[$key][0]["contador_" . "carpeta"] . '
                            </td>

                            <td style="text-align: right;">
                                ' . $arrSeguimiento[$key][0]["porcentaje_" . "carpeta"] . '%
                            </td>
                        </tr>

                        <tr class="FilaBlanca">

                            <td>
                                ' . $arrEtapas[array_search(6, array_column($arrEtapas,"etapa_id"))]["etapa_nombre"] . '
                            </td>

                            <td style="text-align: right;">
                                ' . $arrSeguimiento[$key][0]["contador_" . "aprobacion"] . '
                            </td>

                            <td style="text-align: right;">
                                ' . $arrSeguimiento[$key][0]["porcentaje_" . "aprobacion"]. '%
                            </td>
                        </tr>

                        <tr style="background-color: #ffeeee !important;" class="FilaBlanca">

                            <td>
                                ' . $arrEtapas[array_search(7, array_column($arrEtapas,"etapa_id"))]["etapa_nombre"] . '
                            </td>

                            <td style="text-align: right;">
                                ' . $arrSeguimiento[$key][0]["contador_" . "rechazo"] . '
                            </td>

                            <td style="text-align: right;">
                                ' . $arrSeguimiento[$key][0]["porcentaje_" . "rechazo"] . '%
                            </td>
                        </tr>

                        <tr style="background-color: #eeffee !important;" class="FilaBlanca">

                            <td>
                                ' . $arrEtapas[array_search(8, array_column($arrEtapas,"etapa_id"))]["etapa_nombre"] . '
                            </td>

                            <td style="text-align: right;">
                                ' . $arrSeguimiento[$key][0]["contador_" . "desembolso"] . '
                            </td>

                            <td style="text-align: right;">
                                ' . $arrSeguimiento[$key][0]["porcentaje_" . "desembolso"] . '%
                            </td>
                        </tr>

                        <tr class="FilaBlanca">

                            <td style="text-align: center; font-weight: bold;">
                                TOTAL LEADS ASIGNADOS
                            </td>

                            <td style="text-align: right; font-weight: bold;">
                                ' . $arrSeguimiento[$key][0]["contador_total"] . '
                            </td>

                            <td style="text-align: right; font-weight: bold;">
                                100%
                            </td>
                        </tr>

                    </table>

                </fieldset>
                
            </div>';
            
    

        }
        
        echo $html_body;
    }

    else
    {            
        echo $this->lang->line('TablaNoResultados');
    }

    ?>

    </div>
            
    <br /><br />
</div>