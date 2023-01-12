<?php
class Mfunciones_soa extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    /*************** REPORTE SOA FIE - INICIO ****************************/
    
    public function Obtener_Resumen_SOA_FIE($accion_gestion, $tipo, $servicio, $respuesta, $validacion, $fecha1_capturada, $fecha2_capturada)
    {
        if($accion_gestion != 'seguimiento' && $accion_gestion != 'borrado')
        {
            js_invocacion_javascript('$("#divResultadoReporte").html("")');
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        if(null !== $fecha1_capturada)
        {
            $fecha_inicio = $fecha1_capturada;
            $fecha_inicio1 = $this->mfunciones_generales->getFormatoFechaDateTime($fecha_inicio . ' 00:00:01');
        }

        if(null !== $fecha2_capturada)
        {
            $fecha_fin = $fecha2_capturada;
            $fecha_fin1 = $this->mfunciones_generales->getFormatoFechaDateTime($fecha_fin . ' 23:59:59');
        }
		
        if($fecha_inicio == "" && $fecha_fin == "")
        {   
            js_invocacion_javascript('$("#divResultadoReporte").html("")');
            js_error_div_javascript('Debe establecer un rango de fechas.');
            exit();
        }
		
        if($fecha_inicio != "" && $fecha_fin == "")
        {
            js_invocacion_javascript('$("#divResultadoReporte").html("")');
            js_error_div_javascript($this->lang->line('FormularioFechas'));
            exit();
        }
		
        if($fecha_inicio == "" && $fecha_fin != "")
        {
            js_invocacion_javascript('$("#divResultadoReporte").html("")');
            js_error_div_javascript($this->lang->line('FormularioFechas'));
            exit();
        }
		
        $filtro_texto = "<strong>Filtros seleccionados</strong><br />";
        $filtro_personalizado = ' WHERE audit_service NOT LIKE \'%token\' ';
        
        $filtro_texto .= " • Rango de Fechas: Del " . $fecha_inicio . " Al " . $fecha_fin;
        $filtro_personalizado .= ' AND (audit_date BETWEEN "' . $fecha_inicio1 . '" AND "' . $fecha_fin1 . '")';
        
        if($accion_gestion == 'seguimiento')
        {
            $filtro_texto .= " <br /> • Servicio: ";
            switch ($servicio) {

                case -1:
                    $filtro_texto .= 'Todos';
                    break;

                case 1:
                    $filtro_texto .= 'Validación COBIS-SEGIP';
                    $filtro_personalizado .= ' AND audit_service LIKE \'%validate-client\'';
                    break;

                case 2:
                    $filtro_texto .= 'Prueba de vida';
                    $filtro_personalizado .= ' AND audit_service LIKE \'%liveness\'';
                    break;

                case 3:
                    $filtro_texto .= 'OCR';
                    $filtro_personalizado .= ' AND audit_service LIKE \'%ocrci\'';
                    break;

                default:

                    js_invocacion_javascript('$("#divResultadoReporte").html("")');
                    js_error_div_javascript('Servicio Inválido.');
                    exit();

                    break;
            }

            $filtro_texto .= " <br /> • Tipo de Respuesta: ";
            switch ($respuesta) {

                case -1:
                    $filtro_texto .= 'Todos';
                    break;

                case 1:
                    $filtro_texto .= 'Exitoso';
                    $filtro_personalizado .= ' AND audit_action LIKE \'SUCCESS%\'';
                    break;

                case 2:
                    $filtro_texto .= 'Error';
                    $filtro_personalizado .= ' AND audit_action LIKE \'WITH_ERROR%\'';
                    break;

                default:

                    js_invocacion_javascript('$("#divResultadoReporte").html("")');
                    js_error_div_javascript('Tipo de Respuesta Inválida.');
                    exit();

                    break;
            }

            $filtro_texto .= " <br /> • Tipo de Validación: ";
            switch ($validacion) {

                case -1:
                    $filtro_texto .= 'Todos';
                    break;

                case 1:
                    $filtro_texto .= 'Primer intento';
                    $filtro_personalizado .= ' AND audit_attempt=1';
                    break;

                case 2:
                    $filtro_texto .= 'Reintento';
                    $filtro_personalizado .= ' AND audit_attempt<>1';
                    break;

                default:

                    js_invocacion_javascript('$("#divResultadoReporte").html("")');
                    js_error_div_javascript('Tipo de Respuesta Inválida.');
                    exit();

                    break;
            }
        }
        
        // Filtro del Reporte
        $array = $this->mfunciones_logica->ReporteAuditoriaSOA($filtro_personalizado);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($array);

        if (!isset($array[0])) 
        {
            js_invocacion_javascript('$("#divResultadoReporte").html("")');
            js_error_div_javascript('No se encontraron registros con los filtros indicados.');
            exit();
        }
        
        // -------
        
        $resultado = new stdClass();
        $resultado->total = 0;
        $resultado->servicio_segip = 0;
        $resultado->servicio_liveness = 0;
        $resultado->servicio_ocr = 0;
        
        $resultado->servicio_otro = 0;
        
        $resultado->respuesta_success = 0;
        $resultado->respuesta_error = 0;
        
        $resultado->validacion_primero = 0;
        $resultado->validacion_reintento = 0;
        
        $resultado->array_listado = array();
        
        if (isset($array[0])) 
        {
            $resultado->total = count($array);
            
            $i = 0;
            
            foreach ($array as $key => $value) 
            {
                $cliente_cobis = '';
                $respuesta_segip = '';
                $respuesta_prueba_vida = '';
                $comparacion_selfie_segip = '';
                $comparacion_selfie_ci = '';
                
                $aux_similaridad = '';
                
                $array_respuesta = json_decode($value['audit_result'], true);
                if(json_last_error() != JSON_ERROR_NONE)
                {
                    $array_respuesta = '';
                }
                
                $nombre_servicio = '';
                
                switch ($value['servicio']) {
                    case 'validate-client':
                        
                        $nombre_servicio = 'Validación COBIS-SEGIP';
                        
                        $resultado->servicio_segip++;
                        
                        // Respuesta del servicio
                        if($value['audit_action'] == 'SUCCESS' && is_array($array_respuesta) && isset($array_respuesta['result']))
                        {
                            if(isset($array_respuesta['result']['fieClient']))
                            {
                                $cliente_cobis = $array_respuesta['result']['fieClient'] ? 'True' : 'False';
                            }
                            
                            if($array_respuesta['result']['fieClient'] == true && !isset($array_respuesta['result']['segipResponse']))
                            {
                                $respuesta_segip = 0;
                            }
                            elseif(isset($array_respuesta['result']['segipResponse']['code']))
                            {
                                $respuesta_segip = (int)$array_respuesta['result']['segipResponse']['code'];
                            }
                        }
                        
                        break;

                    case 'liveness':
                        
                        $nombre_servicio = 'Prueba de vida';
                        
                        $resultado->servicio_liveness++;
                        
                        // Respuesta del servicio
                        if($value['audit_action'] == 'SUCCESS' && is_array($array_respuesta) && isset($array_respuesta['result']))
                        {
                            if(isset($array_respuesta['result']['livenessDiagnostic']))
                            {
                                $respuesta_prueba_vida = $array_respuesta['result']['livenessDiagnostic'];
                            }
                            
                            if(isset($array_respuesta['result']['compareSelfieSegip']))
                            {
                                $comparacion_selfie_segip = $array_respuesta['result']['compareSelfieSegip'] ? 'True' : 'False';
                            }
                        }
                        
                        break;
                    
                    case 'ocrci':
                        
                        $nombre_servicio = 'OCR';
                        
                        $resultado->servicio_ocr++;
                        
                        // Respuesta del servicio
                        if($value['audit_action'] == 'SUCCESS' && is_array($array_respuesta) && isset($array_respuesta['result']))
                        {
                            if(isset($array_respuesta['result']['success']))
                            {
                                if($array_respuesta['result']['success'])
                                {
                                    $comparacion_selfie_ci = 'EXITOSA';
                                }
                                else
                                {
                                    // Verificar si viene con los parámetros mínimos necesarios
                                    if(isset($array_respuesta['result']['data']['BACKSIDE']['FIELD_DATA']['NAME']) && isset($array_respuesta['result']['data']['FRONTSIDE']['FIELD_DATA']['DOCUMENT_NUMBER']))
                                    {
                                        $comparacion_selfie_ci = 'FALLO';
                                    }
                                    else
                                    {
                                        $comparacion_selfie_ci = 'NO APLICA';
                                    }
                                }
                                
                                // Verificar si CI es igual al registrado por el usuario
                                if(isset($array_respuesta['result']['data']['FRONTSIDE']['FIELD_DATA']['DOCUMENT_NUMBER']))
                                {
                                    if(str_replace(" ", "", (string)$array_respuesta['result']['data']['FRONTSIDE']['FIELD_DATA']['DOCUMENT_NUMBER']) != str_replace(" ", "", (string)$value["audit_token_ci"]))
                                    {
                                        $aux_similaridad = 'OCR CI NO COINCIDE';
                                    }
                                }
                            }
                        }
                        break;
                    
                    default:
                        
                        $nombre_servicio = "Otro: " . substr($value['audit_service'], strrpos($value['audit_service'], '/') + 1);;
                        $resultado->servicio_otro++;
                        
                        break;
                }
                
                switch ($value['resultado']) {
                    case 'Exitoso':
                        $resultado->respuesta_success++;
                        break;

                    case 'Error':
                        $resultado->respuesta_error++;
                        break;
                    
                    default:
                        break;
                }
                
                switch ($value['validacion']) {
                    case 'Primero':
                        $resultado->validacion_primero++;
                        break;

                    case 'Reintento':
                        $resultado->validacion_reintento++;
                        break;
                    
                    default:
                        break;
                }
                
                $item_valor = array(
                    "audit_id" => $value["audit_id"],
                    "token" => $value["audit_token_front"],
                    "nro_ci" => $value["audit_token_ci"],
                    "servicio_nombre" => $nombre_servicio,
                    "servicio_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["audit_date"]),
                    "tipo_validacion" => $value["validacion"],
                    "tipo_respuesta" => $value["resultado"],
                    "cliente_cobis" => $cliente_cobis,
                    "respuesta_segip" => ((string)$respuesta_segip=='' ? '' : $respuesta_segip . ' - ' . $this->mfunciones_generales->GetValorCatalogoDB($respuesta_segip, 'segip_codigo_respuesta')),
                    "respuesta_prueba_vida" => $respuesta_prueba_vida,
                    "comparacion_selfie_segip" => $comparacion_selfie_segip,
                    "comparacion_selfie_ci" => $comparacion_selfie_ci,
                    "porcentaje_similaridad" => ($value["audit_other"]=='' ? ($aux_similaridad == '' ? '' : $aux_similaridad) : $value["audit_other"].'%')
                    
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        }
        else 
        {
            $lst_resultado[0] = $array;
        }
        
        $resultado->total = number_format($resultado->total, 0, '.', ',');
        $resultado->servicio_segip = number_format($resultado->servicio_segip, 0, '.', ',');
        $resultado->servicio_liveness = number_format($resultado->servicio_liveness, 0, '.', ',');
        $resultado->servicio_ocr = number_format($resultado->servicio_ocr, 0, '.', ',');
        
        $resultado->servicio_otro = number_format($resultado->servicio_otro, 0, '.', ',');
        
        $resultado->respuesta_success = number_format($resultado->respuesta_success, 0, '.', ',');
        $resultado->respuesta_error = number_format($resultado->respuesta_error, 0, '.', ',');
        $resultado->validacion_primero = number_format($resultado->validacion_primero, 0, '.', ',');
        $resultado->validacion_reintento = number_format($resultado->validacion_reintento, 0, '.', ',');
                
        $resultado->array_listado = $lst_resultado;
        
        // -------
        
        $data["reporte_soa"] = $resultado;
        $data["filtro_texto"] = $filtro_texto;

        $data["fecha_inicio"] = $fecha_inicio;
        $data["fecha_fin"] = $fecha_fin;
        $data["servicio"] = $servicio;
        $data["respuesta"] = $respuesta;
        $data["validacion"] = $validacion;
        
        $data["accion_gestion"] = $accion_gestion;
        
        switch ($tipo) {
            case 'tabla':
                
                $this->load->view('soa_fie/view_auditoria_resultado', $data);

                break;
            
            case 'pdf':
                
                $this->mfunciones_generales->GeneraPDF('soa_fie/view_auditoria_resultado_plain',$data, 'L');
                return;
                
            case 'excel':
                
                $this->mfunciones_generales->GeneraExcel('soa_fie/view_auditoria_resultado_plain',$data);
                return;

            default:
                
                js_invocacion_javascript('$("#divResultadoReporte").html("")');
                js_error_div_javascript('Opción de generación de reporte inválida.');
                exit();
            
                break;
        }
    }
    
    /*************** REPORTE SOA FIE - FIN ****************************/
    
    // ObtenerListaRegistrosFlujoCOBIS_stuck_aprobado
    function ObtenerListaRegistrosFlujoCOBIS_stuck_aprobado($tiempo)
    {
        try 
        {
            $sql = "SELECT t.terceros_id, t.ejecutivo_id, t.accion_usuario
                    FROM prospecto p
                    INNER JOIN terceros t ON t.terceros_id=p.onboarding_codigo
                    WHERE p.onboarding=1 AND p.prospecto_consolidado=1 AND t.tercero_asistencia=1 AND t.terceros_estado=0 AND (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(t.terceros_fecha)) >= (60*?) ";

            $consulta = $this->db->query($sql, array((int)$tiempo));

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
    
}
?>