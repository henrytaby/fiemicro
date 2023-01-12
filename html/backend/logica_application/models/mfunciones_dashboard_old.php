<?php
class Mfunciones_dashboard extends CI_Model {

    /*************** FUNCTIONS - INICIO ****************************/
    
    function ArmarFiltro($sel_departamento, $sel_agencia, $sel_oficial, $campoFiltroFechaDesde, $campoFiltroFechaHasta, $app=0, $campoFiltroFechaDCDesde='', $campoFiltroFechaDCHasta='')
    {
        if($app == 0)
        {
            $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);

            $filtro = ' AND agencia_id IN(' . $lista_region->region_id . ')';
        }
        else
        {
            $filtro = '';
        }
        
        // Departamento
        if ($sel_departamento != '')
        {
            $sel_departamento = explode (",", $sel_departamento);
            
            $flag_elalto = 0;
            
            foreach ($sel_departamento as $key => $value) 
            {
                switch ((int)$value) {

                    case 115:
                        $flag_elalto = 1;
                        $aux_criterio = "";
                        break;

                    default:
                        $aux_criterio = "'" . $value . "'";
                        break;
                }

                $value = $aux_criterio;

                if($aux_criterio != '')
                {
                    $criterio .= $value . ',';
                }
            }

            $criterio = rtrim($criterio, ',');
            
            if($criterio != '')
            {
                $filtro .= " AND agencia_departamento IN($criterio)";
            }
            
            if($flag_elalto == 1 && $criterio != '')
            {
                $filtro .= "";
            }
            else
            {
                     if($flag_elalto == 1)
                {
                    $filtro .= " AND agencia_ciudad IN('115')";
                }
                else
                {
                    $filtro .= " AND agencia_ciudad<>115 ";
                }
            }
        }
        
        // Agencias
        if($sel_agencia != '')
        {
            $filtro .= " AND agencia_id IN($sel_agencia)";
        }
        
        // Oficiales de Negocios
        if($sel_oficial != '')
        {
            $filtro .= " AND ejecutivo_id IN($sel_oficial)";
        }
        
        if($this->mfunciones_generales->VerificaFechaD_M_Y($campoFiltroFechaDesde))
        {
            $filtro .= " AND fecha_registro >= '" . $this->mfunciones_generales->getFormatoFechaDateTime($campoFiltroFechaDesde . ' 00:00:01') . "'";
        }
        
        if($this->mfunciones_generales->VerificaFechaD_M_Y($campoFiltroFechaHasta))
        {
            $filtro .= " AND fecha_registro <= '" . $this->mfunciones_generales->getFormatoFechaDateTime($campoFiltroFechaHasta . ' 23:59:59') . "'";
        }
        
        if($this->mfunciones_generales->VerificaFechaD_M_Y($campoFiltroFechaDCDesde))
        {
            $filtro .= " AND desembolso_fecha >= '" . $this->mfunciones_generales->getFormatoFechaDateTime($campoFiltroFechaDCDesde . ' 00:00:01') . "'";
        }
        
        if($this->mfunciones_generales->VerificaFechaD_M_Y($campoFiltroFechaDCHasta))
        {
            $filtro .= " AND desembolso_fecha <= '" . $this->mfunciones_generales->getFormatoFechaDateTime($campoFiltroFechaDCHasta . ' 23:59:59') . "'";
        }
        
        $filtro = str_replace('&', '', $filtro);
        
        return $filtro;
    }
    
    function ObtenerChartFunnel($funnel, $movil=0)
    {
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $arrEtapas = $this->mfunciones_logica->ObtenerDatosFlujo(-1, 1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEtapas);
        
        if (isset($arrEtapas[0])) 
        {
            foreach ($arrEtapas as $key => $value) 
            {
                $lst_resultado[$value['etapa_id']] = array(
                    'etapa_color' => $value['etapa_color'],
                    'etapa_nombre' => $value['etapa_nombre']
                );
            }
        }
        else
        {
            js_error_div_javascript($this->lang->line('TablaNoResultadosDashboard'));
        }
        
        $arrTube[0]['etapa_id'] = 2;
        $arrTube[0]['etapa_color'] = $lst_resultado[2]['etapa_color'];
        $arrTube[0]['etapa_nombre'] = $lst_resultado[2]['etapa_nombre'];
        $arrTube[0]['etapa_monto'] = $funnel->efectivizar_monto;
        $arrTube[0]['etapa_registros'] = $funnel->efectivizar_contador;

        $arrTube[1]['etapa_id'] = 3;
        $arrTube[1]['etapa_color'] = $lst_resultado[3]['etapa_color'];
        $arrTube[1]['etapa_nombre'] = $lst_resultado[3]['etapa_nombre'];
        $arrTube[1]['etapa_monto'] = $funnel->visita_monto;
        $arrTube[1]['etapa_registros'] = $funnel->visita_contador;

        $arrTube[2]['etapa_id'] = 4;
        $arrTube[2]['etapa_color'] = $lst_resultado[4]['etapa_color'];
        $arrTube[2]['etapa_nombre'] = $lst_resultado[4]['etapa_nombre'];
        $arrTube[2]['etapa_monto'] = $funnel->evaluado_monto;
        $arrTube[2]['etapa_registros'] = $funnel->evaluado_contador;

        $arrTube[3]['etapa_id'] = 5;
        $arrTube[3]['etapa_color'] = $lst_resultado[5]['etapa_color'];
        $arrTube[3]['etapa_nombre'] = $lst_resultado[5]['etapa_nombre'];
        $arrTube[3]['etapa_monto'] = $funnel->supervision_monto;
        $arrTube[3]['etapa_registros'] = $funnel->supervision_contador;

        $arrTube[4]['etapa_id'] = 22;
        $arrTube[4]['etapa_color'] = $lst_resultado[22]['etapa_color'];
        $arrTube[4]['etapa_nombre'] = $lst_resultado[22]['etapa_nombre'];
        $arrTube[4]['etapa_monto'] = $funnel->aprobado_monto;
        $arrTube[4]['etapa_registros'] = $funnel->aprobado_contador;

        $ancho_tube = 95;
        $html_tube = '';
        $puntero = 0;
        
        foreach ($arrTube as $key => $valueTube) 
        {
            $puntero++;
	
            $ancho_tube = $ancho_tube - ($puntero==count($arrTube) ? 5 : 10);
            
            if($movil == 0)
            {
                $funcionTabla = 'ReporteFunnelTabla(' . $valueTube['etapa_id'] . ', \'tabla\')';
            
                if((int)$funnel->total_tabla_contador == 0)
                {
                    $percent_monto = 0;
                    $percent_registros = 0;
                }
                else
                {
                    $percent_monto = ($valueTube['etapa_monto']/($funnel->total_tabla_monto==0 ? 1 : $funnel->total_tabla_monto))*100;
                    $percent_registros = ($valueTube['etapa_registros']/$funnel->total_tabla_contador)*100;
                }

                $valueTube['etapa_monto'] = number_format($valueTube['etapa_monto'], 2, '.', ',');
                $valueTube['etapa_registros'] = number_format($valueTube['etapa_registros'], 0, '.', ',');

                if($this->mfunciones_microcreditos->CheckIsMobile())
                {
                    $html_ayuda = '';
                }
                else
                {
                    $html_ayuda = ' data-balloon-length="medium" data-balloon="
INFORMACIÓN DE LA ETAPA

 Monto Bs.: ' . $valueTube['etapa_monto'] . '
 [% del Total: '. number_format($percent_monto, 2, '.', ',') . '%]

 Registros: ' . $valueTube['etapa_registros'] . '
 [% del Total: '. number_format($percent_registros, 2, '.', ',') . '%]

Total -> Solicitudes Ingresadas

" data-balloon-pos="' . ($this->mfunciones_microcreditos->CheckIsMobile() ? 'down' : 'right') . '" data-balloon-break=""
';
                }
            }
            
            $html_tube .= '

                <div class="inv-piramide' . ($puntero==1 ? ' inv-corner-radius-top' : '') . ($puntero==count($arrTube) ? ' inv-piramide-bottom' : '') . '" style="width: ' . $ancho_tube . '%; border-top-color: ' . $valueTube['etapa_color'] . ' !important;">
                    <div class="inv-piramide-contenedor"> <div onclick="' . $funcionTabla . '" class="inv-piramide-contenedor-texto"' . $html_ayuda . '> <strong>' . $valueTube['etapa_nombre'] . ' - <span class="inv-piramide_monto">' . $valueTube['etapa_monto'] . '</span> <span class="inv-piramide_registros" style="display: none;">' . $valueTube['etapa_registros'] . '</span> </strong> </div> </div>
                </div>
                <br />

            ';
        }
        
        return $html_tube;
    }
    
    function ObtenerEstiloFunnel()
    {
        $estilo = '
            
            .inv-piramide
            {
                cursor: pointer;;
                border-top: 40px solid #ffffff !important;
                border-left: 25px solid transparent !important;
                border-right: 25px solid transparent !important;
                display:inline-block;
                margin-bottom: 2px;
            }

            .inv-piramide-contenedor
            {
                width: 100%;
                height: 40px;
                margin-top: -40px;
            }

            .inv-piramide-contenedor-texto
            {
                height: 40px;
                display: table-cell;
                vertical-align: middle;
                line-height: 1;
                width: 5%;
                color: #ffffff;
                text-shadow: #111111 0px 1px 1px;
                font-size: 13px;
            }
            
            .inv-piramide-titulo
            {
                color: #006699;
                font-size: 18px;
                letter-spacing: 0.5px;
                font-weight: bold;
                text-shadow: #004162 0px 1px 1px;
            }

            .inv-piramide-radio
            {
                font-size: 13px;
                padding-top: 5px;
            }
            
            .inv-corner-radius-top
            {
                border-radius: 20px 20px 0px 0px;
            }
            
            .inv-piramide-bottom
            {
                border-left: 12px solid transparent !important;
                border-right: 12px solid transparent !important;
            }
            
            @media screen and (max-width: 640px)
            {
                .inv-piramide
                {
                    border-left: 15px solid transparent !important;
                    border-right: 15px solid transparent !important;
                }
                
                .inv-piramide-bottom
                {
                    border-left: 5px solid transparent !important;
                    border-right: 5px solid transparent !important;
                }
                
                .inv-piramide-contenedor-texto
                {
                    font-size: 11px;
                }
            }

            ';
        
        return $estilo;
    }
    
    function ObtenerAyudaEtapa($etapa_id, $etapa_registros, $etapa_monto, $total_contador, $total_monto)
    {
        $funcionTabla = 'ReporteFunnelTabla(' . $etapa_id . ', \'tabla\')';
            
                if((int)$total_contador == 0)
                {
                    $percent_monto = 0;
                    $percent_registros = 0;
                }
                else
                {
                    $percent_monto = ($etapa_monto/($total_monto==0 ? 1 : $total_monto))*100;
                    $percent_registros = ($etapa_registros/$total_contador)*100;
                }

                $etapa_monto = number_format($etapa_monto, 2, '.', ',');
                $etapa_registros = number_format($etapa_registros, 0, '.', ',');

                if($this->mfunciones_microcreditos->CheckIsMobile())
                {
                    $html_ayuda = '';
                }
                else
                {
                    $html_ayuda = ' data-balloon-length="medium" data-balloon="
INFORMACIÓN DE LA ETAPA

 Monto Bs.: ' . $etapa_monto . '
 [% del Total: '. number_format($percent_monto, 2, '.', ',') . '%]

 Registros: ' . $etapa_registros . '
 [% del Total: '. number_format($percent_registros, 2, '.', ',') . '%]

Total -> Solicitudes Ingresadas

" data-balloon-pos="' . ($this->mfunciones_microcreditos->CheckIsMobile() ? 'down' : 'left') . '" data-balloon-break=""
';
                }

    $html = '<span class="EnlaceSimple" onclick="' . $funcionTabla . '" ' . $html_ayuda . '> <i class="fa fa-table" aria-hidden="true"></i> </span>';
    
    return $html;
    
    }
    
    function GenerarFunnel($filtro, $campoFiltroFechaDCDesde='', $campoFiltroFechaDCHasta='')
    {
        $resultado = new stdClass();
        
        $funnel = new stdClass();
            
        $funnel->efectivizar_contador = 0;
        $funnel->visita_contador = 0;
        $funnel->evaluado_contador = 0;
        $funnel->supervision_contador = 0;
        $funnel->aprobado_contador = 0;
        $funnel->rechazado_contador = 0;
        $funnel->desembolso_contador = 0;
        $funnel->proceso_contador = 0;

            // Fecha Desembolso COBIS
            $funnel->desembolso_fecha_contador = 0;
        
        $funnel->total_funnel_contador = 0;

        $funnel->efectivizar_monto = 0;
        $funnel->visita_monto = 0;
        $funnel->evaluado_monto = 0;
        $funnel->supervision_monto = 0;
        $funnel->aprobado_monto = 0;
        $funnel->rechazado_monto = 0;
        $funnel->desembolso_monto = 0;
        $funnel->proceso_monto = 0;

            // Fecha Desembolso COBIS
            $funnel->desembolso_fecha_monto = 0;
        
        $funnel->total_funnel_monto = 0;
        
            // Fecha Desembolso COBIS (Switch)  0=No    1=Si
            $funnel->desembolso_fecha_switch = 0;
        
        $resultado->funnel = '';
        $resultado->chartFunnel = '';
        
            // *** Auxiliar Fecha Desembolso COBIS
            $aux_FechaDC = 0; // <-- 0= Sin fecha desembolso COBIS  >0=Con fecha desembolso COBIS

            if(!$this->mfunciones_generales->VerificaFechaD_M_Y($campoFiltroFechaDCDesde))
            {
                $campoFiltroFechaDCDesde = '01/01/1900';
            }
            else
            {
                $aux_FechaDC++;
            }

            if(!$this->mfunciones_generales->VerificaFechaD_M_Y($campoFiltroFechaDCHasta))
            {
                $campoFiltroFechaDCHasta = '31/12/2100';
            }
            else
            {
                $aux_FechaDC++;
            }

            if($aux_FechaDC > 0)
            {
                $funnel->desembolso_fecha_switch = 1; // <-- 0=No    1=Si
                
                $campoFiltroFechaDCDesde = $this->mfunciones_generales->getFormatoFechaDateTime($campoFiltroFechaDCDesde . ' 00:00:01');
                $campoFiltroFechaDCHasta = $this->mfunciones_generales->getFormatoFechaDateTime($campoFiltroFechaDCHasta . ' 23:59:59');
                
                $campoFiltroFechaDCDesde = new DateTime($campoFiltroFechaDCDesde);
                $campoFiltroFechaDCHasta = new DateTime($campoFiltroFechaDCHasta);
            }
            // *** Auxiliar Fecha Desembolso COBIS
        
        $arrResultado = $this->ObtenerReporte_Funnel($filtro);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
            
            foreach ($arrResultado as $key => $value) 
            {
                // Establecer monto
                if($value['moneda'] == 'usd')
                {
                    $monto = $value['monto'] * $arrConf[0]['conf_credito_tipo_cambio'];
                }
                else
                {
                    $monto = $value['monto'] * 1;
                }
                
                switch ((int)$value['etapa_id']) {
                    // Efectivizar Solicitud
                    case 1:
                    case 2:

                        $funnel->efectivizar_contador++;
                        $funnel->efectivizar_monto += $monto;
                        
                        break;
                    
                    // Visita y Registro
                    case 3:

                        $funnel->visita_contador++;
                        $funnel->visita_monto += $monto;
                        
                        break;

                    // Evaluado y preparación Consolidar
                    case 4:

                        $funnel->evaluado_contador++;
                        $funnel->evaluado_monto += $monto;
                        
                        break;
                    
                    // Supervisión y Desición
                    case 5:

                        $funnel->supervision_contador++;
                        $funnel->supervision_monto += $monto;
                        
                        break;
                    
                    // JDA Aprobado
                    case 22:

                        $funnel->aprobado_contador++;
                        $funnel->aprobado_monto += $monto;
                        break;
                    
                    // JDA Rechazado
                    case 23:

                        $funnel->rechazado_contador++;
                        $funnel->rechazado_monto += $monto;
                        
                        break;
                    
                    // Desembolsado COBIS
                    case 24:

                        $funnel->desembolso_contador++;
                        $funnel->desembolso_monto += $value['desembolso_monto'];
                        
                        // Contador auxiliar de Fecha Desembolso COBIS, siempre y cuando el filtro se haya marcado y el registro tenga fecha desembolso
                        if($aux_FechaDC > 0 && $value['desembolso_fecha'] != '')
                        {
                            $fecha_cobis_captura = new DateTime($value['desembolso_fecha']);
                            
                            if($fecha_cobis_captura->getTimestamp() >= $campoFiltroFechaDCDesde->getTimestamp() && $fecha_cobis_captura->getTimestamp() <= $campoFiltroFechaDCHasta->getTimestamp())
                            {
                                $funnel->desembolso_fecha_contador++;
                                $funnel->desembolso_fecha_monto += $value['desembolso_monto'];
                            }
                        }
                        
                        break;
                    
                    default:
                        break;
                }
                
                $funnel->proceso_contador = 
                    $funnel->efectivizar_contador +
                    $funnel->visita_contador +
                    $funnel->evaluado_contador +
                    $funnel->supervision_contador;
                
                $funnel->proceso_monto = 
                    $funnel->efectivizar_monto +
                    $funnel->visita_monto +
                    $funnel->evaluado_monto +
                    $funnel->supervision_monto;
                
                
                $funnel->total_funnel_contador = $funnel->proceso_contador + $funnel->aprobado_contador;
                $funnel->total_funnel_monto = $funnel->proceso_monto + $funnel->aprobado_monto;
                
                $funnel->total_tabla_contador = $funnel->total_funnel_contador + $funnel->rechazado_contador + $funnel->desembolso_contador;
                $funnel->total_tabla_monto = $funnel->total_funnel_monto + $funnel->rechazado_monto + $funnel->desembolso_monto;
            }
        }
        
        $resultado->funnel = $funnel;
        $resultado->chartFunnel = $this->ObtenerChartFunnel($funnel);

        return $resultado;
    }
    
    function GenerarFunnelTabla($filtro)
    {
        $arrResultado = $this->ObtenerReporte_dashboard($filtro);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
            
            $i = 0;
            
            foreach ($arrResultado as $key => $value) 
            {
                // Armado de CI
                if((int)$value["tipo"] == 1)
                {
                    $extension = $this->mfunciones_generales->GetValorCatalogo($value["extension"], 'extension_ci');
                }
                else
                {
                    if($value["extension"] == 'EE')
                    {
                        $extension = 'EXT';
                    }
                    else
                    {
                        $extension = ((int)$value["extension"]==-1 ? '' : $value["extension"] . (str_replace(' ', '', $value["extension"])=='' ? '' : '.'));
                    }
                }
                
                // Armado de Monto
                
                $monto_final = 0;
                
                if((int)$value["desembolso"] == 1 && (int)$value["jda_eval"] == 1)
                {
                    $monto_final = $value["desembolso_monto"];
                }
                else
                {
                    if($value['moneda'] == 'usd')
                    {
                        $monto_final = $value['monto'] * $arrConf[0]['conf_credito_tipo_cambio'];
                    }
                    else
                    {
                        $monto_final = $value['monto'] * 1;
                    }
                }
                
                $item_valor = array(
                    "tipo" => (int)$value["tipo"],
                    "prospecto_id" => $value["codigo"],
                    "agencia_nombre" => $value["agencia_nombre"],
                    "usuario_id" => $value["usuario_id"],
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "camp_id" => $value["camp_id"],
                    "general_solicitante" => $value["solicitante"],
                    "general_ci" => $value["ci"],
                    "general_ci_extension" => $extension,
                    "camp_nombre" => $value["camp_nombre"],
                    "ejecutivo_nombre" => $value["ejecutivo_nombre"],
                    "etapa_id" => $value["etapa_id"],
                    "etapa_nombre" => $value["etapa_nombre"],
                    "general_actividad" => $value["actividad"],
                    "general_destino" => $value["destino"],
                    "prospecto_jda_eval" => $value["jda_eval"],
                    "sol_monto_bs" => number_format($monto_final, 2, '.', ','),
                    "fecha_registro" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["fecha_registro"]),
                    "dias_calendario" => (date('Y-m-d', strtotime($value["fecha_registro"]))=='1900-01-01' || $value["fecha_registro"]=='' ? '--' : number_format($this->mfunciones_generales->getDiasCalendario(date('Y-m-d', strtotime($value["fecha_registro"])), date('Y-m-d')), 0, '.', ',')),
                    "registro_num_proceso" => ((int)$value["registro_num_proceso"]<=0 ? $this->lang->line('prospecto_no_evaluacion') : $value["registro_num_proceso"]),
                    "desembolso_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["desembolso_fecha"])
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        }
        
        return $lst_resultado;
    }
    
    /*************** FUNCTIONS - FIN ****************************/
    
    /*************** DATABASE - INICIO ****************************/
    
    function ObtenerDatosDepartamento_dashboard($criterio)
    {        
        try 
        {
            $sql = "SELECT dep
                    FROM(
                        SELECT DISTINCT estructura_regional_departamento as 'dep'
                        FROM estructura_regional
                        WHERE estructura_regional_estado=1 AND length(estructura_regional_departamento) >= 2 AND estructura_regional_ciudad <> 115 AND estructura_regional_id IN(" . $criterio . ")
                        GROUP BY estructura_regional_departamento

                    UNION ALL

                        SELECT 'LPALTO' as 'dep'
                        FROM estructura_regional
                        WHERE estructura_regional_estado=1 AND estructura_regional_ciudad='115' AND estructura_regional_id IN(" . $criterio . ")
                    ) a GROUP BY dep ORDER BY dep ASC";

            $consulta = $this->db->query($sql, array());

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function ObtenerDatosRegional_dashboard($parent_codigo=-1, $parent_tipo=-1, $criterio='', $aux)
    {        
        try 
        {
            $parent_codigo = htmlspecialchars($parent_codigo);
            $parent_tipo = htmlspecialchars($parent_tipo);
            $criterio = htmlspecialchars($criterio);
            
            $filtro = '';
            
            if($aux == 0)
            {
                if($parent_codigo != -1 && $parent_tipo != -1)
                {
                    switch ($parent_tipo) {
                        case 'dir_departamento':
                            $filtro = " AND estructura_regional_departamento IN($parent_codigo) AND estructura_regional_ciudad<>115";
                            break;
                        case 'dir_provincia':
                            $filtro = " AND estructura_regional_provincia IN($parent_codigo)";
                            break;
                        case 'dir_localidad_ciudad':
                            $filtro = " AND estructura_regional_ciudad IN($parent_codigo)";
                            break;

                        default:
                            break;
                    }
                }
            }
            
            $sql = "SELECT DISTINCT r.estructura_regional_id, r.estructura_regional_nombre, r.estructura_regional_departamento, r.estructura_regional_ciudad
                        FROM estructura_regional r 
                        WHERE estructura_regional_estado=1 AND length(estructura_regional_nombre) >= 2 $filtro AND r.estructura_regional_id IN (" . $criterio . ") ORDER BY r.estructura_regional_nombre ASC";

            $consulta = $this->db->query($sql, array());

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function ObtenerDatosOficial_dashboard($parent_codigo=-1, $criterio='', $aux)
    {
        $parent_codigo = htmlspecialchars($parent_codigo);
        $criterio = htmlspecialchars($criterio);
        
        if($aux == 0)
        {
            $filtro = ' AND er.estructura_regional_id IN (' . $parent_codigo . ')';
        }
        
        try 
        {   
            $sql = "SELECT er.estructura_regional_id, er.estructura_regional_nombre, e.ejecutivo_id, CONCAT_WS(' ', u.usuario_app, u.usuario_apm, u.usuario_nombres) as 'ejecutivo_nombre'
                    FROM estructura_regional er
                    INNER JOIN estructura_agencia ea ON ea.estructura_regional_id=er.estructura_regional_id
                    INNER JOIN usuarios u ON u.estructura_agencia_id=ea.estructura_agencia_id
                    INNER JOIN ejecutivo e ON e.usuario_id=u.usuario_id
                    WHERE er.estructura_regional_estado=1 $filtro AND u.usuario_rol=2 AND er.estructura_regional_id IN (" . $criterio . ") ORDER BY u.usuario_app ASC";

            $consulta = $this->db->query($sql, array());

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function ObtenerReporte_Funnel($filtro)
    {
        $this->lang->load('general', 'castellano');
        
        try 
        {
            // ----
                // Setear fecha como variable
                $fecha_aux = $this->lang->line('dashboard_fecha_corte') . " 00:00:01' ";
                $filtro .= " AND fecha_registro > '" . $fecha_aux;
            // ----
                
            $sql = "SELECT tipo, codigo, agencia_id, agencia_departamento, agencia_ciudad, ejecutivo_id, etapa_id, desembolso_monto, desembolso_fecha, moneda, monto, fecha_registro
                    FROM(
                        
                        SELECT *
                        FROM(
                        
                            SELECT 1 as 'tipo',
                            p1.prospecto_id as 'codigo',
                            er1.estructura_regional_id as 'agencia_id',
                            er1.estructura_regional_departamento as 'agencia_departamento',
                            er1.estructura_regional_ciudad as 'agencia_ciudad',
                            e1.ejecutivo_id,
                            et1.etapa_id,
                            p1.prospecto_desembolso_monto as 'desembolso_monto',
                            p1.prospecto_desembolso_fecha as 'desembolso_fecha',
                            s0.sol_moneda as 'moneda',
                            s0.sol_monto as 'monto',
                            s0.sol_fecha as 'fecha_registro'

                            FROM prospecto p1
                            INNER JOIN solicitud_credito s0 ON s0.sol_estudio_codigo=p1.prospecto_id
                            INNER JOIN etapa et1 ON et1.etapa_id=p1.prospecto_etapa
                            INNER JOIN campana c1 ON c1.camp_id=p1.camp_id
                            INNER JOIN ejecutivo e1 ON e1.ejecutivo_id=p1.ejecutivo_id
                            INNER JOIN usuarios u1 ON u1.usuario_id=e1.usuario_id
                            INNER JOIN estructura_agencia ea1 ON ea1.estructura_agencia_id=u1.estructura_agencia_id
                            INNER JOIN estructura_regional er1 ON er1.estructura_regional_id=ea1.estructura_regional_id AND er1.estructura_regional_estado=1
                            WHERE p1.onboarding=0 AND p1.general_categoria=1 AND et1.etapa_id>0 AND et1.etapa_id<>6
                        ) aux1 WHERE 1 " . $filtro . "

                    UNION ALL

                        SELECT *
                        FROM(

                            SELECT 2 as 'tipo', 
                            s1.sol_id as 'codigo',
                            er2.estructura_regional_id as 'agencia_id',
                            er2.estructura_regional_departamento as 'agencia_departamento',
                            er2.estructura_regional_ciudad as 'agencia_ciudad',
                            e2.ejecutivo_id,
                            CASE
                                WHEN s1.sol_estudio=1 THEN 0
                                WHEN s1.sol_consolidado=0
                                THEN
                                    CASE
                                        WHEN s1.sol_estado=3 THEN 23
                                        WHEN s1.sol_estado<>3 THEN 2
                                    END
                                    ELSE
                                    CASE
                                        WHEN s1.sol_codigo_rubro NOT IN (7,8,9,10,11,12) AND s1.sol_evaluacion=1 AND s1.sol_estudio=0 THEN 2
                                        WHEN s1.sol_codigo_rubro NOT IN (7,8,9,10,11,12) AND s1.sol_evaluacion=1 THEN 0
                                        WHEN s1.sol_codigo_rubro NOT IN (7,8,9,10,11,12) AND s1.sol_evaluacion=2 THEN 23
                                        WHEN s1.sol_codigo_rubro IN (7,8,9,10,11,12)
                                        THEN
                                            CASE
                                                WHEN (s1.sol_desembolso=1) THEN 24
                                                WHEN (s1.sol_desembolso=0 AND s1.sol_jda_eval=0) THEN 5
                                                WHEN (s1.sol_desembolso=0 AND s1.sol_jda_eval=1) THEN 22
                                                WHEN (s1.sol_desembolso=0 AND s1.sol_jda_eval=2) THEN 23
                                            END
                                    END

                            END as 'etapa_id',
                            s1.sol_desembolso_monto as 'desembolso_monto',
                            s1.sol_desembolso_fecha as 'desembolso_fecha',
                            s1.sol_moneda as 'moneda',
                            s1.sol_monto as 'monto',
                            s1.sol_fecha as 'fecha_registro'
                            FROM solicitud_credito s1
                            INNER JOIN ejecutivo e2 ON e2.ejecutivo_id=s1.ejecutivo_id
                            INNER JOIN usuarios u2 ON u2.usuario_id=e2.usuario_id
                            INNER JOIN estructura_regional er2 ON er2.estructura_regional_id=s1.codigo_agencia_fie AND er2.estructura_regional_estado=1
                        ) aux2 WHERE 1 " . $filtro . "
                    ) a WHERE etapa_id>0 " . $filtro;
            
            $consulta = $this->db->query($sql, array());

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function ObtenerReporte_dashboard($filtro)
    {
        $this->lang->load('general', 'castellano');
        
        try 
        {
            // ----
                // Setear fecha como variable
                $fecha_aux = $this->lang->line('dashboard_fecha_corte') . " 00:00:01' ";
                $filtro .= " AND fecha_registro > '" . $fecha_aux;
            // ----
            
            $sql = "SELECT tipo, codigo, agencia_id, agencia_nombre, agencia_departamento, agencia_provincia, agencia_ciudad, ci, extension, solicitante, camp_id, camp_nombre, usuario_id, ejecutivo_id, ejecutivo_nombre, etapa_id, etapa_nombre, actividad, destino, jda_eval, jda_eval_fecha, registro_num_proceso, desembolso, desembolso_fecha, desembolso_monto, moneda, monto, fecha_registro
                    FROM(
                    
                        SELECT *
                        FROM(
                        
                            SELECT 1 as 'tipo',
                            p1.prospecto_id as 'codigo',
                            er1.estructura_regional_id as 'agencia_id',
                            er1.estructura_regional_nombre as 'agencia_nombre',
                            er1.estructura_regional_departamento as 'agencia_departamento',
                            er1.estructura_regional_provincia as 'agencia_provincia',
                            er1.estructura_regional_ciudad as 'agencia_ciudad',
                            p1.general_ci as 'ci',
                            p1.general_ci_extension as 'extension',
                            CONCAT_WS(' ', p1.general_solicitante) as 'solicitante', c1.camp_id, c1.camp_nombre, 
                            u1.usuario_id as 'usuario_id',
                            e1.ejecutivo_id,
                            CONCAT_WS(' ', u1.usuario_nombres, u1.usuario_app, u1.usuario_apm) as 'ejecutivo_nombre',
                            et1.etapa_id,
                            et1.etapa_nombre,
                            p1.general_actividad as 'actividad',
                            p1.general_destino as 'destino',
                            p1.prospecto_jda_eval as 'jda_eval',
                            p1.prospecto_jda_eval_fecha as 'jda_eval_fecha',
                            p1.prospecto_num_proceso as 'registro_num_proceso',
                            p1.prospecto_desembolso as 'desembolso',
                            p1.prospecto_desembolso_fecha as 'desembolso_fecha',
                            p1.prospecto_desembolso_monto as 'desembolso_monto',
                            s0.sol_moneda as 'moneda',
                            s0.sol_monto as 'monto',
                            s0.sol_fecha as 'fecha_registro'

                            FROM prospecto p1
                            INNER JOIN solicitud_credito s0 ON s0.sol_estudio_codigo=p1.prospecto_id
                            INNER JOIN etapa et1 ON et1.etapa_id=p1.prospecto_etapa
                            INNER JOIN campana c1 ON c1.camp_id=p1.camp_id
                            INNER JOIN ejecutivo e1 ON e1.ejecutivo_id=p1.ejecutivo_id
                            INNER JOIN usuarios u1 ON u1.usuario_id=e1.usuario_id
                            INNER JOIN estructura_agencia ea1 ON ea1.estructura_agencia_id=u1.estructura_agencia_id
                            INNER JOIN estructura_regional er1 ON er1.estructura_regional_id=ea1.estructura_regional_id AND er1.estructura_regional_estado=1
                            WHERE p1.onboarding=0 AND p1.general_categoria=1 AND et1.etapa_id>0 AND et1.etapa_id<>6 
                            
                        ) aux1 WHERE 1 " . $filtro . "

                    UNION ALL
                    
                        SELECT *
                        FROM (

                            SELECT 2 as 'tipo', s1.sol_id as 'codigo',
                            er2.estructura_regional_id as 'agencia_id',
                            er2.estructura_regional_nombre as 'agencia_nombre',
                            er2.estructura_regional_departamento as 'agencia_departamento',
                            er2.estructura_regional_provincia as 'agencia_provincia',
                            er2.estructura_regional_ciudad as 'agencia_ciudad',
                            CONCAT_WS(' ', s1.sol_ci, s1.sol_complemento) 'ci',
                            s1.sol_extension as 'extension',
                            CONCAT_WS(' ', s1.sol_primer_nombre, s1.sol_primer_apellido, s1.sol_segundo_apellido) as 'solicitante',
                            s1.sol_codigo_rubro as 'camp_id',
                            CASE s1.sol_codigo_rubro
                                    WHEN -1
                                    THEN 'NO REGISTRADO'
                                    ELSE (SELECT tp2.tipo_persona_nombre FROM tipo_persona tp2 WHERE tp2.tipo_persona_id=s1.sol_codigo_rubro)
                            END as 'camp_nombre',
                            u2.usuario_id as 'usuario_id',
                            e2.ejecutivo_id,
                            CONCAT_WS(' ', u2.usuario_nombres, u2.usuario_app, u2.usuario_apm) as 'ejecutivo_nombre',
                            CASE
                                WHEN s1.sol_estudio=1 THEN 0
                                WHEN s1.sol_consolidado=0
                                THEN
                                    CASE
                                        WHEN s1.sol_estado=3 THEN 23
                                        WHEN s1.sol_estado<>3 THEN 2
                                        END
                                    ELSE
                                    CASE
                                        WHEN s1.sol_codigo_rubro NOT IN (7,8,9,10,11,12) AND s1.sol_evaluacion=1 AND s1.sol_estudio=0 THEN 2
                                        WHEN s1.sol_codigo_rubro NOT IN (7,8,9,10,11,12) AND s1.sol_evaluacion=1 THEN 0
                                        WHEN s1.sol_codigo_rubro NOT IN (7,8,9,10,11,12) AND s1.sol_evaluacion=2 THEN 23
                                        WHEN s1.sol_codigo_rubro IN (7,8,9,10,11,12)
                                        THEN
                                        CASE
                                            WHEN (s1.sol_desembolso=1) THEN 24
                                            WHEN (s1.sol_desembolso=0 AND s1.sol_jda_eval=0) THEN 5
                                            WHEN (s1.sol_desembolso=0 AND s1.sol_jda_eval=1) THEN 22
                                            WHEN (s1.sol_desembolso=0 AND s1.sol_jda_eval=2) THEN 23
                                                        END
                                                END
                                        END as 'etapa_id',
                                                        CASE
                                    WHEN s1.sol_consolidado=0
                                THEN
                                    CASE
                                        WHEN s1.sol_estado=3 THEN 'Rechazado Bandeja'
                                        WHEN s1.sol_estado=0 THEN 'Registrado'
                                        WHEN s1.sol_estado=1 THEN 'Asignado'
                                        WHEN s1.sol_estado=2 THEN 'Consolidado'
                                        WHEN s1.sol_estado>3 THEN '--'
                                        END
                                    ELSE
                                    CASE
                                        WHEN s1.sol_codigo_rubro = -1 THEN 'CONSOLIDADO SIN RUBRO'
                                        WHEN s1.sol_codigo_rubro NOT IN (7,8,9,10,11,12) AND s1.sol_evaluacion=1 AND s1.sol_estudio=0 THEN 'CONSOLIDADO SIN ESTUDIO'
                                        WHEN s1.sol_codigo_rubro NOT IN (7,8,9,10,11,12) AND s1.sol_evaluacion=1 THEN 0
                                        WHEN s1.sol_codigo_rubro NOT IN (7,8,9,10,11,12) AND s1.sol_evaluacion=2 THEN 'Rechazado'
                                        WHEN s1.sol_codigo_rubro IN (7,8,9,10,11,12)
                                        THEN
                                            CASE
                                                WHEN (s1.sol_desembolso=1) THEN (SELECT e3.etapa_nombre FROM etapa e3 WHERE e3.etapa_id=24)
                                                WHEN (s1.sol_desembolso=0 AND s1.sol_jda_eval=0) THEN (SELECT e3.etapa_nombre FROM etapa e3 WHERE e3.etapa_id=5)
                                                WHEN (s1.sol_desembolso=0 AND s1.sol_jda_eval=1) THEN (SELECT e3.etapa_nombre FROM etapa e3 WHERE e3.etapa_id=22)
                                                WHEN (s1.sol_desembolso=0 AND s1.sol_jda_eval=2) THEN (SELECT e3.etapa_nombre FROM etapa e3 WHERE e3.etapa_id=23)
                                            END
                                            END
                                    END as 'etapa_nombre',
                            CASE s1.sol_dependencia
                                            WHEN 1 THEN CONCAT_WS(' | ', s1.sol_depen_empresa, s1.sol_depen_cargo)
                                            WHEN 2 THEN s1.sol_indepen_actividad
                            END as 'actividad',
                            s1.sol_detalle as 'destino',
                            s1.sol_jda_eval as 'jda_eval',
                            s1.sol_jda_eval_usuario as 'jda_eval_fecha',
                            s1.sol_num_proceso as 'registro_num_proceso',
                            s1.sol_desembolso as 'desembolso',
                            s1.sol_desembolso_fecha as 'desembolso_fecha',
                            s1.sol_desembolso_monto as 'desembolso_monto',
                            s1.sol_moneda as 'moneda',
                            s1.sol_monto as 'monto',
                            s1.sol_fecha as 'fecha_registro'
                            FROM solicitud_credito s1
                            INNER JOIN ejecutivo e2 ON e2.ejecutivo_id=s1.ejecutivo_id
                            INNER JOIN usuarios u2 ON u2.usuario_id=e2.usuario_id
                            INNER JOIN estructura_regional er2 ON er2.estructura_regional_id=s1.codigo_agencia_fie AND er2.estructura_regional_estado=1
                            
                        ) aux2 WHERE 1 " . $filtro . "
                    ) a WHERE etapa_id>0 " . $filtro;

            $consulta = $this->db->query($sql, array());

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function ObtenerListaAgencia($filtro)
    {        
        try 
        {
            $sql = "SELECT DISTINCT estructura_regional_id, estructura_regional_nombre
                    FROM estructura_regional
                    WHERE estructura_regional_estado=1 $filtro"; 
            
            $consulta = $this->db->query($sql, array());

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function ObtenerListaOficial($filtro)
    {        
        try 
        {
            $sql = "SELECT DISTINCT e.ejecutivo_id, CONCAT_WS(' ', u.usuario_nombres, u.usuario_app, u.usuario_apm) as 'ejecutivo_nombre'
                    FROM ejecutivo e
                    INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                    INNER JOIN estructura_agencia ea ON ea.estructura_agencia_id=u.estructura_agencia_id
                    INNER JOIN estructura_regional er ON er.estructura_regional_id=ea.estructura_regional_id
                    WHERE estructura_regional_estado=1 $filtro"; 
            
            $consulta = $this->db->query($sql, array());

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    /*************** DATABASE - FIN ****************************/

}