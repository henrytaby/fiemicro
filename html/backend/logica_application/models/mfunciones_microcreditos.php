<?php

class Mfunciones_microcreditos extends CI_Model {

/*************** FUNCTIONS - INICIO ****************************/
    
    /**** REGISTRAR SEGUIMIENTO, ETAPA E HITO Y ENVIAR CORREO (último parámetro 1=Envio a etapas hijas    2=Envio a etapa específica ****/
    function NotificacionEtapaCredito($solicitud_id, $codigo_etapa, $regionalizado) {
        
        $this->load->model('mfunciones_logica');
        
        $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa($codigo_etapa);

        if (isset($arrEtapa[0]))
        {
            foreach ($arrEtapa as $key1 => $value1) 
            {
                // 0 = No Envía Correo      1 = Sí Envía Correo
                if($value1['etapa_notificar_correo'] == 1)
                {
                    $arrTerceros = $this->DatosSolicitudCreditoEmail($solicitud_id);
                    
                    $arrUsuariosNotificar = $this->mfunciones_generales->getListadoUsuariosEtapa($codigo_etapa, $arrTerceros[0]['codigo_agencia_fie'], $regionalizado);

                    if (isset($arrUsuariosNotificar[0])) 
                    {
                        foreach ($arrUsuariosNotificar as $key => $value) 
                        {
                            $destinatario_nombre = $value['usuario_nombres'] . ' ' . $value['usuario_app'] . ' ' . $value['usuario_apm'];
                            $destinatario_correo = $value['usuario_email'];
                            
                            // SE PROCEDE CON EL ENVÍO DE CORREO ELECTRÓNICO
                            $correo_enviado = $this->mfunciones_generales->EnviarCorreo('notificar_instancia_credito', $destinatario_correo, $destinatario_nombre, $arrTerceros);
                        }
                    }
                }
            }
        }
    }
    
    function DatosSolicitudCreditoEmail($codigo_solicitud)
    {
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->ObtenerDetalleSolicitudCredito($codigo_solicitud);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            $arrRegion = $this->mfunciones_logica->ObtenerDatosRegional($arrResultado[0]["codigo_agencia_fie"]);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRegion);
            
            if (isset($arrRegion[0])) 
            {
                $nombre_region = $arrRegion[0]['estructura_regional_nombre'];
                $monto_region = $arrRegion[0]['estructura_regional_monto'];
                $estado_region = $arrRegion[0]['estructura_regional_estado'];
            }
            else
            {
                $nombre_region = 'Sin Selección';
                $monto_region = '';
                $estado_region = '';
            }
            
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                $item_valor = array(

                    "codigo_agencia_fie" => $value["codigo_agencia_fie"],
                    "nombre_agencia" => $nombre_region,
                    "monto_agencia" => $monto_region,
                    "estado_region" => $estado_region,
                    
                    "agente_codigo" => $value["agente_codigo"],
                    "agente_nombre" => $value["agente_nombre"],
                    "agente_correo" => $value["agente_correo"],
                    "agente_telefono" => $value["usuario_telefono"],
                    
                    "sol_estudio" => $value["sol_estudio"],
                    "sol_estudio_codigo" => $value["sol_estudio_codigo"],
                    
                    "registro_num_proceso" => $value["sol_num_proceso"],
                    
                    "sol_id" => $value["sol_id"],
                    "camp_id" => 6, // <-- Valor constante
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "sol_codigo_rubro" => $value["sol_codigo_rubro"],
                    "sol_ultimo_paso" => $value["sol_ultimo_paso"],
                    "sol_consolidado" => $value["sol_consolidado"],
                    "sol_evaluacion" => $value["sol_evaluacion"],
                    "sol_rechazado_texto" => $value["sol_rechazado_texto"],
                    "sol_estado_codigo" => $value["sol_estado"],
                    "sol_estado" => $this->GetValorCatalogo($value["sol_estado"], 'solicitud_estado'),
                    "sol_asistencia" => $this->mfunciones_generales->GetValorCatalogo($value["sol_asistencia"], 'tercero_asistencia'),
                    "sol_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["sol_fecha"]),
                    
                    "sol_ci" => $value["sol_ci"],
                    "sol_extension" => $value["sol_extension"],
                    "sol_complemento" => $value["sol_complemento"],
                    "sol_ci_completo" => $value["sol_ci"] . ' ' . $value["sol_complemento"] . ' ' . ((int)$value['sol_extension']==-1 ? '' : $this->mfunciones_generales->GetValorCatalogoDB($value['sol_extension'], 'cI_lugar_emisionoextension')),
                    "sol_primer_nombre" => $value["sol_primer_nombre"],
                    "sol_segundo_nombre" => $value["sol_segundo_nombre"],
                    "sol_primer_apellido" => $value["sol_primer_apellido"],
                    "sol_segundo_apellido" => $value["sol_segundo_apellido"],
                    "sol_nombre_completo" => $value["sol_primer_nombre"] . ' ' . $value["sol_primer_apellido"] . ' ' . $value["sol_segundo_apellido"],
                    "sol_correo" => $value["sol_correo"],
                    "sol_cel" => $value["sol_cel"],
                    "sol_dependencia" => $value["sol_dependencia"],
                    "sol_indepen_actividad" => $value["sol_indepen_actividad"],
                    "sol_depen_empresa" => $value["sol_depen_empresa"],
                    "sol_depen_cargo" => $value["sol_depen_cargo"],
                    "sol_dir_departamento" => $value["sol_dir_departamento"],
                    "sol_dir_provincia" => $value["sol_dir_provincia"],
                    "sol_dir_localidad_ciudad" => $value["sol_dir_localidad_ciudad"],
                    "sol_cod_barrio" => $value["sol_cod_barrio"],
                    "sol_direccion_trabajo" => $value["sol_direccion_trabajo"],
                    "sol_edificio_trabajo" => $value["sol_edificio_trabajo"],
                    "sol_numero_trabajo" => $value["sol_numero_trabajo"],
                    
                    "sol_monto" => $this->GetValorCatalogo($value["sol_moneda"], 'sol_moneda') . ' ' . number_format($value["sol_monto"], 2, ',', '.'),
                    "sol_monto_valor" => $value["sol_monto"],
                    "sol_moneda" => $value["sol_moneda"],
                    "sol_plazo" => $value["sol_plazo"],
                    "sol_detalle" => $value["sol_detalle"],
                    "sol_conyugue" => $value["sol_conyugue"],
                    
                    "sol_con_ci" => $value["sol_con_ci"],
                    "sol_con_extension" => $value["sol_con_extension"],
                    "sol_con_complemento" => $value["sol_con_complemento"],
                    "sol_con_ci_completo" => $value["sol_con_ci"] . ' ' . $value["sol_con_complemento"] . ' ' . ((int)$value['sol_con_extension']==-1 ? '' : $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_extension'], 'cI_lugar_emisionoextension')),
                    "sol_con_primer_nombre" => $value["sol_con_primer_nombre"],
                    "sol_con_segundo_nombre" => $value["sol_con_segundo_nombre"],
                    "sol_con_primer_apellido" => $value["sol_con_primer_apellido"],
                    "sol_con_segundo_apellido" => $value["sol_con_segundo_apellido"],
                    "sol_con_nombre_completo" => $value["sol_con_primer_nombre"] . ' ' . $value["sol_con_primer_apellido"] . ' ' . $value["sol_con_segundo_apellido"],
                    "sol_con_correo" => $value["sol_con_correo"],
                    "sol_con_cel" => $value["sol_con_cel"],
                    "sol_con_dependencia" => $value["sol_con_dependencia"],
                    "sol_con_indepen_actividad" => $value["sol_con_indepen_actividad"],
                    "sol_con_depen_empresa" => $value["sol_con_depen_empresa"],
                    "sol_con_depen_cargo" => $value["sol_con_depen_cargo"],
                    "sol_con_dir_departamento" => $value["sol_con_dir_departamento"],
                    "sol_con_dir_provincia" => $value["sol_con_dir_provincia"],
                    "sol_con_dir_localidad_ciudad" => $value["sol_con_dir_localidad_ciudad"],
                    "sol_con_cod_barrio" => $value["sol_con_cod_barrio"],
                    "sol_con_direccion_trabajo" => $value["sol_con_direccion_trabajo"],
                    "sol_con_edificio_trabajo" => $value["sol_con_edificio_trabajo"],
                    "sol_con_numero_trabajo" => $value["sol_con_numero_trabajo"],
                    
                    "sol_trabajo_actividad_pertenece" => $value["sol_trabajo_actividad_pertenece"],
                    "sol_con_trabajo_actividad_pertenece" => $value["sol_con_trabajo_actividad_pertenece"],
                    
                    "sol_depen_ant_ano" => $value["sol_depen_ant_ano"],
                    "sol_depen_ant_mes" => $value["sol_depen_ant_mes"],
                    "sol_indepen_ant_ano" => $value["sol_indepen_ant_ano"],
                    "sol_indepen_ant_mes" => $value["sol_indepen_ant_mes"],
                    "sol_con_depen_ant_ano" => $value["sol_con_depen_ant_ano"],
                    "sol_con_depen_ant_mes" => $value["sol_con_depen_ant_mes"],
                    "sol_con_indepen_ant_ano" => $value["sol_con_indepen_ant_ano"],
                    "sol_con_indepen_ant_mes" => $value["sol_con_indepen_ant_mes"],
                    
                    // Actividad Secundaria
                    "sol_actividad_secundaria" => $value["sol_actividad_secundaria"],
                    "sol_codigo_rubro_sec" => $value["sol_codigo_rubro_sec"],
                    "sol_dir_departamento_sec" => $value["sol_dir_departamento_sec"],
                    "sol_dir_localidad_ciudad_sec" => $value["sol_dir_localidad_ciudad_sec"],
                    "sol_cod_barrio_sec" => $value["sol_cod_barrio_sec"],
                    "sol_direccion_trabajo_sec" => $value["sol_direccion_trabajo_sec"],
                    "sol_dependencia_sec" => $value["sol_dependencia_sec"],
                    "sol_depen_empresa_sec" => $value["sol_depen_empresa_sec"],
                    "sol_depen_cargo_sec" => $value["sol_depen_cargo_sec"],
                    "sol_depen_ant_ano_sec" => $value["sol_depen_ant_ano_sec"],
                    "sol_depen_ant_mes_sec" => $value["sol_depen_ant_mes_sec"],
                    "sol_indepen_actividad_sec" => $value["sol_indepen_actividad_sec"],
                    "sol_indepen_ant_ano_sec" => $value["sol_indepen_ant_ano_sec"],
                    "sol_indepen_ant_mes_sec" => $value["sol_indepen_ant_mes_sec"],
                    
                );
                $lst_resultado[$i] = $item_valor;
                
                $i++;
            }
            
            return $lst_resultado;
        }
        
        return FALSE;
    }
    
    function GetValorCatalogo($data, $tipo) {
        
        $resultado = 'No Registrado';
        
        if($tipo == 'estudio_actividad')
        {
            switch ($data) {              
                case 1:
                    $resultado = $this->lang->line('evaluacion_actividad_principal');
                    break;
                case 2:
                    $resultado = $this->lang->line('evaluacion_actividad_secundaria');
                    break;
                default:
                    $resultado = 'No Registrado';
                    break;
            }
        }
        
        if($tipo == 'ejecutivo_perfil_tipo')
        {
            switch ($data) {              
                case 1:
                    $resultado = $this->lang->line('ejecutivo_perfil_tipo_generico');
                    break;
                case 2:
                    $resultado = $this->lang->line('ejecutivo_perfil_tipo_catb');
                    break;
                default:
                    $resultado = 'No Registrado';
                    break;
            }
        }
        
        if($tipo == 'sol_referencia')
        {
            switch ($data) {              
                case 0:
                    $resultado = 'Sin Selección';
                    break;
                case 1:
                    $resultado = 'Geolocalización';
                    break;
                case 2:
                    $resultado = 'Croquis';
                    break;
                default:
                    $resultado = 'No Registrado';
                    break;
            }
        }
        
        if($tipo == 'solicitud_estado')
        {
            switch ($data) {              
                case 0:
                    $resultado = 'Registrado';
                    break;
                case 1:
                    $resultado = 'Asignado';
                    break;
                case 2:
                    $resultado = 'Consolidado';
                    break;
                case 3:
                    $resultado = 'Rechazado';
                    break;
                default:
                    $resultado = 'No Registrado';
                    break;
            }
        }
        
        if($tipo == 'sol_moneda')
        {
            switch ($data) {              
                case 'bob':
                    $resultado = 'Bs.';
                    break;
                case 'usd':
                    $resultado = '$us.';
                    break;
                default:
                    $resultado = 'Moneda no registrada';
                    break;
            }
        }
        
        if($tipo == 'sol_cliente')
        {
            switch ($data) {              
                case 0:
                    $resultado = 'Nuevo';
                    break;
                case 1:
                    $resultado = 'Antiguo';
                    break;
                default:
                    $resultado = 'No Registrado';
                    break;
            }
        }
        
        if($tipo == 'sol_trabajo_lugar')
        {
            switch ($data) {              
                case 99:
                    $resultado = 'Otro';
                    break;
                case 1:
                    $resultado = 'Propio';
                    break;
                case 2:
                    $resultado = 'Alquilado';
                    break;
                case 3:
                    $resultado = 'Anticrético';
                    break;
                default:
                    $resultado = 'No Registrado';
                    break;
            }
        }
        
        if($tipo == 'sol_trabajo_realiza')
        {
            switch ($data) {              
                case 99:
                    $resultado = 'Otro';
                    break;
                case 1:
                    $resultado = 'Calle';
                    break;
                case 2:
                    $resultado = 'Tienda';
                    break;
                case 3:
                    $resultado = 'Puesto de Mercado';
                    break;
                case 4:
                    $resultado = 'Sindicato';
                    break;
                default:
                    $resultado = 'No Registrado';
                    break;
            }
        }
        
        if($tipo == 'sol_dom_tipo')
        {
            switch ($data) {              
                case 99:
                    $resultado = 'Otro';
                    break;
                case 1:
                    $resultado = 'Propia';
                    break;
                case 2:
                    $resultado = 'En alquiler';
                    break;
                case 3:
                    $resultado = 'En anticrético';
                    break;
                case 4:
                    $resultado = 'Familiar';
                    break;
                default:
                    $resultado = 'No Registrado';
                    break;
            }
        }
        
        if($tipo == 'sol_trabajo_actividad_pertenece')
        {
            switch ($data) {              
                case 0:
                    $resultado = 'Deudor';
                    break;
                case 1:
                    $resultado = 'Codeudor';
                    break;
                default:
                    $resultado = 'No Registrado';
                    break;
            }
        }
        
        if($tipo == 'sol_dependencia')
        {
            switch ($data) {              
                case 1:
                    $resultado = 'ACTIVIDAD DEPENDIENTE';
                    break;
                case 2:
                    $resultado = 'ACTIVIDAD INDEPENDIENTE';
                    break;
                default:
                    $resultado = 'ACTIVIDAD NO REGISTRADA';
                    break;
            }
        }
        
        if($tipo == 'aux_rubro')
        {
            switch ($data) {              
                case 1:
                    $resultado = 'PRODUCCIÓN';
                    break;
                case 2:
                    $resultado = 'SERVICIOS';
                    break;
                case 3:
                    $resultado = 'COMERCIO';
                    break;
                case 4:
                    $resultado = 'TRANSPORTE';
                    break;
                case 7:
                    $resultado = 'AGROPECUARIO';
                    break;
                case 8:
                    $resultado = 'INGRESO FIJO';
                    break;
                case 9:
                    $resultado = 'BAJO LÍNEA';
                    break;
                case 10:
                    $resultado = 'MICRO B';
                    break;
                case 11:
                    $resultado = 'PYME';
                    break;
                case 12:
                    $resultado = 'CORPORATIVO';
                    break;
                default:
                    $resultado = 'NO REGISTRADO';
                    break;
            }
        }
        
        return($resultado);
    }
    
    function GetDiasAtencion($data) {
        
        $resultado = '';
        
        if($data == '')
        {
            return ' - No registró días de atención';
        }
        
        $arrDias = explode(",", $data);
        
        if (isset($arrDias[0])) 
        {
            foreach ($arrDias as $key => $value) 
            {
                switch ($value) {
                    case 1:

                        $resultado .= '<br /> - Lunes';

                        break;

                    case 2:

                        $resultado .= '<br /> - Martes';

                        break;

                    case 3:

                        $resultado .= '<br /> - Miércoles';

                        break;

                    case 4:

                        $resultado .= '<br /> - Jueves';

                        break;

                    case 5:

                        $resultado .= '<br /> - Viernes';

                        break;

                    case 6:

                        $resultado .= '<br /> - Sábado';

                        break;

                    case 7:

                        $resultado .= '<br /> - Domingo';

                        break;

                    default:
                        break;
                }
            }
        }
        
        return($resultado);
    }
    
    function GetDiasAtencionCorto($data) {
        
        $resultado = '';
        
        if($data == '')
        {
            return $resultado;
        }
        
        $arrDias = explode(",", $data);
        
        if (isset($arrDias[0])) 
        {
            foreach ($arrDias as $key => $value) 
            {
                switch ($value) {
                    case 1:

                        $resultado .= 'Lun, ';

                        break;

                    case 2:

                        $resultado .= 'Mar, ';

                        break;

                    case 3:

                        $resultado .= 'Mié, ';

                        break;

                    case 4:

                        $resultado .= 'Jue, ';

                        break;

                    case 5:

                        $resultado .= 'Vie, ';

                        break;

                    case 6:

                        $resultado .= 'Sáb, ';

                        break;

                    case 7:

                        $resultado .= 'Dom, ';

                        break;

                    default:
                        break;
                }
            }
        }
        
        if($resultado != '')
        {
            $resultado = '<br />' . $resultado;
        }
        
        return(rtrim($resultado, ', '));
    }
    
    function ListaOficialesUsuariosRegion($tipo_ejecutivo=1)
    {
        $this->load->model('mfunciones_logica');
        
        $arrEjecutivo_aux = $this->mfunciones_logica->ObtenerEjecutivo(-1, $tipo_ejecutivo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEjecutivo_aux);

        if (isset($arrEjecutivo_aux[0]))
        {
            $i = 0;

            foreach ($arrEjecutivo_aux as $key => $value) {

                $carga_laboral = $this->GetCargaLaboralOficialNeg($value["ejecutivo_id"]);

                $item_valor = array(
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "usuario_id" => $value["usuario_id"],
                    "usuario_nombre" => $value["usuario_nombre"],
                    "estructura_regional_nombre" => $value["estructura_regional_nombre"],
                    "carga_laboral" => $carga_laboral->cantidad_texto
                );
                $arrEjecutivo[$i] = $item_valor;

                $i++;
            }

            // Se agrupa el listado y construye el SELECT

            $arrEjecutivo = $this->mfunciones_generales->ArrGroupBy($arrEjecutivo, 'estructura_regional_nombre');

            $arrEjecutivo_html = '<select id="catalogo_parent" name="catalogo_parent">';
            $arrEjecutivo_html .= '<option value="-1"> --Seleccionar-- </option>';


            foreach ($arrEjecutivo as $key => $values) {

                $arrEjecutivo_html .= '<optgroup style="background-color: #f3f3f3; color: #000000;" label="Agencia: ' . $key . '">';

                foreach ($values as $value) {

                    $arrEjecutivo_html .= '<option value="'.$value['ejecutivo_id'].'" style="background-color: #ffffff; color: #000000;"> → ' . $value['usuario_nombre'] . ' </option>';
                    $arrEjecutivo_html .= '<option value="'.$value['ejecutivo_id'].'" style="font-style: italic; background-color: #ffffff; color: #888888; font-size: 0.80em;" disabled>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $value['carga_laboral'] . ' </option>';

                }

                $arrEjecutivo_html .= '</optgroup>';
            }

            $arrEjecutivo_html .= '</select>';

        }
        else 
        {
            $arrEjecutivo[0] = $arrEjecutivo_aux;

            $arrEjecutivo_html = $this->lang->line('regionaliza_TablaNoRegistros');
        }
        
        return $arrEjecutivo_html;
    }
    
    function GetCargaLaboralOficialNeg($codigo_ejecutivo) {
        
        $resultado = new stdClass();
        
        $resultado->cantidad_prospectos = 0;
        $resultado->cantidad_onboarding = 0;
        $resultado->cantidad_mantenimientos = 0;
        $resultado->cantidad_solcred = 0;
        $resultado->cantidad_texto = 'No válido';
        
        // Listado de los prospectos
        $arrResultado1 = $this->mfunciones_logica->ObtenerBandejaProspectos_Carga($codigo_ejecutivo, 0);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
        $cantidad_prospectos = number_format(count($arrResultado1), 0, ',', '.');
        
        // Listado de los onboarding cuenta digital
        $arrResultado2 = $this->mfunciones_logica->ObtenerBandejaProspectos_Carga($codigo_ejecutivo, 1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);
        $cantidad_onboarding = number_format(count($arrResultado2), 0, ',', '.');
        
        // Listado de los mantenimientos
        $arrResultado3 = $this->mfunciones_logica->ObtenerBandejaMantenimientos($codigo_ejecutivo, 0);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
        $cantidad_mantenimientos = number_format(count($arrResultado3), 0, ',', '.');
        
        // Listado de los onboarding solicitud de crédito
        $arrResultado4 = $this->mfunciones_microcreditos->ObtenerTotalSolCred($codigo_ejecutivo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado4);
        $cantidad_solcred = number_format($arrResultado4[0]['total_solcred'], 0, ',', '.');
        
        $resultado->cantidad_prospectos = $cantidad_prospectos;
        $resultado->cantidad_onboarding = $cantidad_onboarding;
        $resultado->cantidad_mantenimientos = $cantidad_mantenimientos;
        $resultado->cantidad_solcred = $cantidad_solcred;
        $resultado->cantidad_texto = 'Pendientes: ' . $cantidad_prospectos . ' Lead(s) | ' . $cantidad_mantenimientos . ' Mant. | ' . $cantidad_solcred . ' Solicitud(es) Créd. | ' .  $cantidad_onboarding . ' Solicitud(es) Onb.';
        
        return $resultado;
    }
    
    function Cliente_SMS($param__end_point, $bearer, $parametros, $accion_usuario, $accion_fecha, $testing=0)
    {
        $this->load->model('mfunciones_logica');
        
        $resultado = new stdClass();
        $resultado->ws_end_point = '';
        $resultado->ws_token = '';
        $resultado->ws_params = '';
        $resultado->ws_httpcode = 500;
        $resultado->ws_result = FALSE;
        $resultado->ws_error = TRUE;
        $resultado->ws_error_text = '';
        $resultado->ws_action = '';
        
        try 
        {
            $process = curl_init($param__end_point);

            $additionalHeaders = "Authorization: Bearer " . $bearer;
            curl_setopt($process, CURLOPT_HTTPHEADER, array('content-type: application/json', $additionalHeaders));
            curl_setopt($process, CURLOPT_POSTFIELDS, json_encode($parametros, true));

            curl_setopt($process, CURLOPT_HEADER, false);
            curl_setopt($process, CURLOPT_TIMEOUT, 120);
            curl_setopt($process, CURLOPT_POST, true);
            curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($process, CURLOPT_VERBOSE, true);

            $return = curl_exec($process);

            if($return === FALSE)
            {
                $resultado->ws_action = 'WITH_ERROR_' . (string)$resultado->ws_httpcode;
                $resultado->ws_result = 'cURL: ' . curl_error($process);
            }
            else
            {
                $resultado->ws_httpcode = curl_getinfo($process, CURLINFO_HTTP_CODE);

                $resultado->ws_result = json_decode($return, true);

                if(json_last_error() !== JSON_ERROR_NONE)
                {
                    $resultado->ws_error = TRUE;
                    $resultado->ws_error_text = "Ocurrio un evento inesperado. ";

                    switch (json_last_error()) {
                    case JSON_ERROR_DEPTH:
                        $resultado->ws_error_text .=  "Maximum stack depth exceeded";
                        break;
                    case JSON_ERROR_STATE_MISMATCH:
                        $resultado->ws_error_text .=  "Invalid or malformed JSON";
                        break;
                    case JSON_ERROR_CTRL_CHAR:
                        $resultado->ws_error_text .=  "Control character error";
                        break;
                    case JSON_ERROR_SYNTAX:
                        $resultado->ws_error_text .=  "Syntax error";
                        break;
                    case JSON_ERROR_UTF8:
                        $resultado->ws_error_text .=  "Malformed UTF-8 characters";
                        break;
                    default:
                        $resultado->ws_error_text .=  "Unknown error";
                        break;
                    }

                    $resultado->ws_action = 'WITH_ERROR_' . (string)$resultado->ws_httpcode;
                    $resultado->ws_result = $resultado->ws_error_text;
                }
                elseif((int)$resultado->ws_httpcode >= 200 && (int)$resultado->ws_httpcode <300)
                {
                    $resultado->ws_error = FALSE;
                    $resultado->ws_action = 'SUCCESS';
                }
            }

            curl_close($process);
            
            // Resultado del proceso
            if($testing == 0)
            {   // Si no es test y genera error, Guardar el log de auditoría móvil
                if($resultado->ws_error)
                {
                    if(is_array($resultado->ws_result))
                    {
                        $arrayLog = array_merge($resultado->ws_result,array('status_code'=>(int)$resultado->ws_httpcode));
                    }
                    else
                    {
                        $arrayLog = array('status_code'=>(int)$resultado->ws_httpcode, 'result'=>$resultado->ws_result);
                    }
                    
                    $this->mfunciones_logica->InsertarAuditoriaMovil('External__sms_solicitud_credito', json_encode($arrayLog), '0,0', $accion_usuario, $accion_fecha);
                }
            }
            else
            {
                $resultado->ws_end_point = $param__end_point;
                $resultado->ws_params = $parametros;
            }
                    
            return $resultado;
        
        }
        catch (Exception $ex) {
            $resultado = new stdClass();
            $resultado->ws_end_point = '';
            $resultado->ws_token = '';
            $resultado->ws_params = '';
            $resultado->ws_result = "Ocurrio un evento inesperado. " . $ex;
            $resultado->ws_httpcode = '500';
            $resultado->ws_error = TRUE;
            $resultado->ws_error_text = $resultado->ws_result;
            $resultado->ws_action = 'WITH_ERROR_' . (string)$resultado->ws_httpcode;
            
            if($testing == 0)
            {
                // Si no es test y genera error, Guardar el log de auditoría móvil
                if(is_array($resultado->ws_result))
                {
                    $arrayLog = array_merge($resultado->ws_result,array('status_code'=>(int)$resultado->ws_httpcode));
                }
                else
                {
                    $arrayLog = array('status_code'=>(int)$resultado->ws_httpcode, 'result'=>$resultado->ws_result);
                }

                $this->mfunciones_logica->InsertarAuditoriaMovil('External__sms_solicitud_credito', json_encode($arrayLog), '0,0', $accion_usuario, $accion_fecha);
            }
            else
            {
                $resultado->ws_end_point = $param__end_point;
                $resultado->ws_params = $parametros;
            }
            
            return $resultado;
        }
    }
    
    function DoBackgroundEnviarSMS($param__end_point, $parametros, $accion_usuario, $accion_fecha) {
        
        $this->lang->load('general', 'castellano');
        
        $this->load->helper(array('form', 'url'));
        $this->load->library('mylibrary');
        
        ignore_user_abort(1); // Que continue aun cuando el usuario se haya ido
        
        // Dirección del Controlador LOGICA DE NEGOCIO
        $url = base_url() . "SolWeb/Envio/SMS";
        
        // Se capturan los valores
        
        $param = array(
            'param__end_point' => $param__end_point,
            'parametros' => $parametros,
            'accion_usuario' => $accion_usuario,
            'accion_fecha' => $accion_fecha
            );
        
        $this->mylibrary->do_in_background($url, $param);
        
        return TRUE;
    }
    
    function GetInfoSolicitudDigitalizadoPDF($codigo_solicitud, $codigo_documento, $filtro) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->VerificaDocumentosSolicitudDigitalizar($codigo_solicitud, $codigo_documento);        
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            $ruta = RUTA_SOLCREDITOS;
            $documento = $arrResultado1[0]['solicitud_carpeta'] . '/' . $arrResultado1[0]['solicitud_carpeta'] . '_' .$arrResultado1[0]['solicitud_credito_documento_pdf'];

            $path = $ruta . $documento;

            if(file_exists($path))
            {
                if($filtro == 'existe')
                {
                    return TRUE;
                }

                if($filtro == 'documento')
                {
                    // Obtener la cadena base64 de un documento especifico de un prospecto específico
                    
                    // Obtener la cadena base64 de un documento especifico de un prospecto específico
                    $data = file_get_contents($path);
                    $base64 = base64_encode($data);

                    return $base64;
                }
                
                if($filtro == 'ruta')
                {
                    return $documento;
                }
                
                if($filtro == 'path')
                {
                    return $path;
                }
                
            }
            else
            {
                return FALSE;
            }
        }
        else 
        {
            return FALSE;
        }
    }
    
    function GuardarDocumentoSolicitudBase64PDF($codigo_registro, $codigo_documento, $documento_pdf_base64) {
		
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerNombreDocumento($codigo_documento);        
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
			
        // 1. Obtener el nombre del documento

        if (isset($arrResultado1[0])) 
        {
            $nombre_documento = $this->mfunciones_generales->TextoNoAcentoNoEspacios($arrResultado1[0]['documento_nombre']);

            //Se añade la fecha y hora al final
            $nombre_documento .= '__' . date('Y-m-d_H_i_s') . '.pdf';
            $path = RUTA_SOLCREDITOS . 'sol_' . $codigo_registro . '/sol_' . $codigo_registro . '_' . $nombre_documento;
            $pdf = $documento_pdf_base64;
            $decoded = base64_decode($pdf);

            if(!file_put_contents($path, $decoded)) { if(file_exists ($path)){ unlink($path); } return FALSE; }
            else { return $nombre_documento; }
        }
        else 
        {
            return FALSE;
        }
    }
    
    function TextoTitulo($texto) {
        
        $texto = str_replace(array(' Y ', ' Del ', ' De ', ' En ', ' Con ', ' Más ', ' Se ', ' La ', ' A ', ' El ', ' Un ', ' O ', '[p'), array(' y ', ' del ', ' de ', ' en ', ' con ', ' más ', ' se ', ' la ', ' a ', ' el ', ' un ', ' o ', '[P'), ucwords(mb_strtolower($texto)));
        
        return $texto;
    }
    
    function SolGetDocDigitalizado($codigo_registro, $codigo_documento_registro, $filtro) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->SolObtenerDocumentoDigitalizar($codigo_registro, $codigo_documento_registro);        
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
        
        if (isset($arrResultado1[0])) 
        {
            $ruta = RUTA_SOLCREDITOS;
            $documento = $arrResultado1[0]['prospecto_carpeta'] . '/' . $arrResultado1[0]['prospecto_carpeta'] . '_' .$arrResultado1[0]['solicitud_credito_documento_pdf'];

            $path = $ruta . $documento;

            if(file_exists($path))
            {
                if($filtro == 'existe')
                {
                    return TRUE;
                }

                if($filtro == 'documento')
                {
                    // Obtener la cadena base64 de un documento especifico de un prospecto específico
                    $data = file_get_contents($path);
                    $base64 = base64_encode($data);

                    return $base64;
                }
            }
            else
            {
                return FALSE;
            }
        }
        else 
        {
            return FALSE;
        }
    }
    
    // Función para verificar si un documento de un prospecto esta observado
    function SolVerificaDocumentoObservado($codigo_registro, $codigo_documento)
    {
        $arrResultado = $this->SolVerDocumentoObservado($codigo_registro, $codigo_documento);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    function getSolVistasRubro($codigo_rubro)
    {
        $vistas_rubro = new stdClass();

        /*

        6  = Solicitud de Crédito
        61 = Solicitud de Crédito con Cónyuge
        
        */

        $vistas_rubro->sol_credito = array(
            "sol_datos_personales",
            "sol_credito_solicitado",
            "sol_direccion"
        );
        
        $vistas_rubro->sol_credito_con = array(
            "sol_datos_personales",
            "sol_con_datos_personales",
            "sol_credito_solicitado",
            "sol_direccion",
            "sol_con_direccion"
        );
        
        switch ($codigo_rubro) {
            case 6:
                
                $array_rubro = $vistas_rubro->sol_credito;

                break;
            
             case 61:
                
                $array_rubro = $vistas_rubro->sol_credito_con;

                break;
            
            default:
                
                $array_rubro = $vistas_rubro->sol_credito;
                
                break;
        }
        
        return $array_rubro;
    }
    
    function getContenidoNavApp($codigo_registro, $paso_actual='0', $codigo_tipo_persona='0')
    {   
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($codigo_registro);
        
        if (!isset($arrResultado[0])) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        // Paso 1: En base al Código de Prospecto, se obtiene la información
        
        $titular_nombre = $arrResultado[0]['sol_nombre_completo'];
        
        $familia_nombre = 'REGISTRO DEL TITULAR';
            
        $titular_rubro = $this->mfunciones_generales->GetValorCatalogo($arrResultado[0]['camp_id'], 'nombre_rubro');
        $titular_ci = $arrResultado[0]['sol_ci_completo'];
        $titular_telefono = $arrResultado[0]['sol_cel'];
        
        $codigo_rubro = ((int)$arrResultado[0]['sol_conyugue']==0 ? $arrResultado[0]['camp_id'] : 61);
        
        $color_rubro = $this->mfunciones_generales->GetValorCatalogo($arrResultado[0]['camp_id'], 'color_rubro');
        
        // Paso 2: Se establece el array de las vista según el rubro seleccionado
        
        $array_rubro = $this->getSolVistasRubro($codigo_rubro);
        
        // Se establece el máximo número de pasos
        $maximo_pasos = count($array_rubro) + 1;
        
        // Paso 3: Se establece las vistas (anterior | actual | siguiente)
        
        $vista_actual = $paso_actual;
            $vista_prospecto = $this->mfunciones_generales->paso_ant_sig($vista_actual, $array_rubro);
        $vista_anterior = $vista_prospecto->anterior;
        $vista_siguiente = $vista_prospecto->siguiente;
        
        $vista_actual_numero = $vista_prospecto->index+1;
        
        $contenido = '';
        
        if($vista_actual != '0')
        {
            $contenido .= '
            
                <div style="position: fixed; top: 0;">
                    <span style="color: ' . $color_rubro . ' !important;" class="nav-home" onclick="ElementoSubmit(\'home\');"><i class="fa fa-home" aria-hidden="true"></i></span>
                </div>
                ';
        }elseif($codigo_tipo_persona=='unidad_familiar')
        {
            $contenido .= '
            
                <div style="position: fixed; top: 0;">
                    <span class="nav-home" onclick="ElementoSubmit(\'home\');"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                </div>
                ';
        }
        
        $data_auxiliar = 'C.I. ' . $titular_ci;
        
        $contenido .= '

            <div style="text-align: right;">
                <span class="nav-titulo">' . $titular_nombre . ' ' . $this->mfunciones_generales->GetValorCatalogo(1, 'icono_categoria') . '</span>
                <br />
                <span class="nav-subtitulo"> ' . $titular_rubro . ' </span>
                <br />
                <span class="nav-subtitulo"> ' . $data_auxiliar . ' | Teléfono: ' . $titular_telefono . ' </span>
            </div>

            <div style="clear: both"></div>
            ';
        
            if($vista_actual != '0')
            {
                $contenido .= '
        
                <br />

                <div class="container">
                    <ul class="progress-indicator">
                ';

                    // Bucle para el Stepper, en base al array seleccionado
                
                    $contador_pasos = 0;

                    foreach ($array_rubro as $key => $value) 
                    {
                        $contador_pasos++;
                        
                        if($vista_actual_numero >= $contador_pasos)
                        {
                            $stepper_clase = 'class="completed"';
                        }
                        else
                        {
                            $stepper_clase = '';
                        }
                        
                        if($vista_actual_numero == $contador_pasos)
                        {
                            $stepper_actual = '<i class="fa fa-pencil" aria-hidden="true"></i>';
                        }
                        else
                        {
                            $stepper_actual = $contador_pasos;
                        }

                        $contenido .= '

                                <li ' . $stepper_clase . '>
                                    <span class="bubble" onclick="ElementoSubmit(\'' . $value . '\');">' . $stepper_actual . '</span>
                                </li>
                        ';
                    }

                $aux_botones = '';

                if($vista_actual_numero > 1)
                {
                    $aux_botones .= '

                        <div class="col" style="text-align: left;">
                            <span class="nav-avanza" onclick="ElementoSubmit(\'ant\');"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Anterior</span> 
                        </div>

                        ';
                }

                if($vista_actual_numero+1 < $maximo_pasos)
                {
                    $aux_botones .= '

                        <div class="col" style="text-align: right;">
                            <span class="nav-avanza" onclick="ElementoSubmit(\'sig\');">Siguiente <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span> 
                        </div>

                        ';
                }
                
                if($vista_actual_numero+1 >= $maximo_pasos)
                {
                    $aux_botones .= '

                        <div class="col" style="text-align: right;">
                            <span class="nav-avanza" onclick="ElementoSubmit(\'final\');">Finalizar <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span> 
                        </div>

                        ';
                }

                if($vista_actual != 'view_final')
                {
                    $contenido .= '

                        </ul>

                    </div>

                    <div style="clear: both"></div>

                    <div class="container" style="margin-top: 5px;">
                        <div class="row"> ' . $aux_botones . ' </div>
                    </div>

                    ';
                }
            }
        
        return $contenido;
    }
    
    // Obtener estadistica de Tiempo de registro de Rubro
    function SolTiempoRegistroRubro($aux_fecha_ini, $aux_fecha_fin)
    {
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        $calculo_tiempo = $this->mfunciones_generales->getDiasLaborales($aux_fecha_ini, $aux_fecha_fin);

        $calculo_estado = "Atrasado";

        $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa(1);
        $tiempo_etapa = $arrEtapa[0]['etapa_tiempo'];

        if($tiempo_etapa > 0)
        {
            $total_porcentaje = 100 - round(($calculo_tiempo*100)/$tiempo_etapa);

            if($total_porcentaje > 50)
            {
                $calculo_estado = "A tiempo";
            }        
            elseif($total_porcentaje >= 0)
            {
                $calculo_estado = "A tiempo";
            }
            elseif($total_porcentaje < 0)
            {
                $calculo_estado = "Atrasado";
            }
        }

        $resultado = new stdClass();
        
        $resultado->aux_fecha_ini = $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($aux_fecha_ini);
        $resultado->aux_fecha_fin = $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($aux_fecha_fin);
        $resultado->calculo_tiempo = number_format($calculo_tiempo, 0, ',', '.') . ' Horas';
        $resultado->calculo_estado = $calculo_estado;
        $resultado->tiempo_etapa = number_format($tiempo_etapa, 0, ',', '.') . ' Horas';

        return $resultado;
    }
    
    function obtener_Dep_Pro_Ciu_from_zon($codigo_zona)
    {
        $resultado = new stdClass();
        $resultado->dir_departamento = '';
        $resultado->dir_provincia = '';
        $resultado->dir_localidad_ciudad = '';
        
        $arrResultado1 = $this->get_Dep_Pro_Ciu_from_zon($codigo_zona);        
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            $resultado->dir_departamento = (string)$arrResultado1[0]['dir_departamento'];
            $resultado->dir_provincia = (string)$arrResultado1[0]['dir_provincia'];
            $resultado->dir_localidad_ciudad = (string)$arrResultado1[0]['dir_localidad_ciudad'];
        }
        
        return $resultado;
    }
    
    function validarTxtCampo($texto, $tipo = 'string', $len = 0, $min = 0, $max = 0) {
        // Para validar nombre  => validarTxtCampo('Juan', 'string', 1, 3, 50);
        // Para validar celular => validarTxtCampo('72018900', 'celular', 2, 8);
        // Para validar email   => validarTxtCampo('test@email.com', 'email', 1, 3, 100);
        // Para no validar      => validarTxtCampo('Hola');

        $this->lang->load('general', 'castellano');
        
        $len = (int) $len;
        $min = (int) $min;
        $max = (int) $max;

        $error = 0;
        $error_texto = '';

        // Validar caracteres
        $texto = htmlspecialchars($texto);

        if($tipo == 'string')
        {
            $texto = (string)$texto;
        }
        
        switch ($len) {
            case 1:

                // Validacion de rango

                if ($min != 0) {
                    if (strlen((string) str_replace(' ', '', $texto)) < $min) {
                        $error = 1;
                    }
                }

                if ($max != 0) {
                    if (strlen((string) str_replace(' ', '', $texto)) > $max) {
                        $error = 1;
                    }
                }

                if ($error == 1) {
                    $error_texto = 'El valor debe estar entre ' . $min . ' y ' . $max . ' caracteres.';
                }

                break;

            case 2:

                // Validación de caracteres especificos

                if ($min != 0) {
                    if (strlen((string) str_replace(' ', '', $texto)) != $min) {
                        $error_texto = 'El valor debe tener ' . $min . ($tipo == 'string' ? ' caracteres' : ' dígitos') . '.';
                    }
                }

                break;

            default:
                break;
        }

        switch ($tipo) {
            case 'string_corto':

                if (preg_match('/.*(.)\1\1.*/', mb_strtolower($texto))) {
                    $error_texto .= ' No debe tener más de 2 caracteres juntos iguales repetidos.';
                }

                break;

            case 'string':

                if (preg_match('/.*(.)\1\1\1\1.*/', mb_strtolower($texto))) {
                    $error_texto .= ' No debe tener más de 4 caracteres juntos iguales repetidos.';
                }

                break;
                
            case 'celular':

                if (!preg_match('/^\d+$/', $texto)) {
                    $error_texto .= ' Debe ser número.';
                }

                if ((string) $texto[0] != '6' && (string) $texto[0] != '7') {
                    $error_texto .= ' Debe empezar con 6 o 7.';
                }

                break;

            case 'email':

                $texto = str_replace(' ', '', $texto);
                
                if($texto != '' && $this->mfunciones_generales->VerificaCorreo($texto) == false)
                {
                    $error_texto .= ' Correo inválido.';
                }
                
                break;
                
            case 'fecha_Y_M_D':

                $texto = str_replace(' ', '', $texto);
                
                if($texto != '' && $texto != '1900-01-01' && $this->mfunciones_generales->VerificaFechaY_M_D($texto) == false)
                {
                    $error_texto .= ' Fecha inválida.';
                }
                
                break;
                
            case 'ci_complemento':

                if($texto != '')
                {
                    if(strlen((string)$texto) != 2)
                    {
                        $error_texto .= 'Debe contener 2 caracteres.';
                    }
                    elseif(!preg_match('/^(?!\d+$)(?![a-zA-Z]+$)[a-zA-Z\d]{2}$/', $texto))
                    {
                        $error_texto .= 'No puede contener 2 letras juntas o 2 números juntos.';
                    }
                }
                
                break;
                
            default:
                break;
        }
        
        return $error_texto;
    }

    function validateIMG_simple($img) {
        if ((preg_match("/data:([a-zA-Z0-9]+\/[a-zA-Z0-9-.+]+).base64,.*/", $img))) {
            return $img;
        } else {
            return '';
        }
    }
    
    function setAuxGEO($geo, $valor)
    {
        // Verificar si el valor inicial tiene coordenadas
        if(!preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $geo))
        {
            // Si geo no tiene coordenadas, se verifica que el nuevo valor si tenga coordenadas para asignarlas
            if(preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $valor))
            {
                // Si $valor es correcto, se devuelve ese valor
                return $valor;
            }
        }
        else
        {
            // Si $geo tiene coordenadas se devuelve el mismo valor
            return $geo;
        }
        
        return '';
    }
    
    function validateGEO_simple($geo)
    {
        $resultado = new stdClass();
        
        $resultado->lat = 0;
        $resultado->long = 0;
        $resultado->resultado = 0;
        
        $geo = str_replace(' ', '', $geo);
        
        if(preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $geo))
        {
            $geo = explode(',', $geo);
            
            if(count($geo) == 2)
            {
                $resultado->lat = $geo[0];
                $resultado->long = $geo[1];
                $resultado->resultado = '';
            }
        }
        
        return $resultado;
    }
    
    function validateGEO($geo, $depto='')
    {
        if(!preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $geo))
        {
            switch (str_replace(' ', '', (string)$depto)) {
                case 'PA':
                    $aux_geo = "-11.0186002, -68.7534241";
                    break;
                    
                case 'CB':
                    $aux_geo = "-17.3941514, -66.1570679";
                    break;
                    
                /* El Alto
                case 'LP':
                    $aux_geo = "-16.5042897, -68.1625667";
                    break;
                */
                
                case 'OR':
                    $aux_geo = "-17.9697723, -67.114637";
                    break;
                    
                case 'LP':
                    $aux_geo = "-16.4957204, -68.1330845";
                    break;
                    
                case 'PO':
                    $aux_geo = "-19.5812145, -65.7544123";
                    break;
                    
                case 'SC':
                    $aux_geo = "-17.7836896, -63.1821305";
                    break;
                    
                case 'CH':
                    $aux_geo = "-19.0479336, -65.2604586";
                    break;
                    
                case 'TJ':
                    $aux_geo = "-21.5338973, -64.7364835";
                    break;
                    
                case 'BE':
                    $aux_geo = "-14.8348845, -64.9052122";
                    break;

                default:
                    $aux_geo = GEO_FIE;
                    break;
            }
            
            return $aux_geo;
        }
        else
        {
            return $geo;
        }
    }
    
    function validateRegDir($departamento, $provincia, $localidad_ciudad, $barrio)
    {
        if((int)$localidad_ciudad != -1)
        {
            if((int)$departamento == -1 || (int)$provincia == -1)
            {
                return FALSE;
            }
        }
        
        if((int)$barrio != -1)
        {
            if((int)$departamento == -1 || (int)$provincia == -1 || (int)$localidad_ciudad == -1)
            {
                return FALSE;
            }
        }
        
        return TRUE;
    }
    
    function ObtenerCatalogoSelectSol($campo, $valor, $codigo_catalogo, $parent_codigo=-1, $parent_tipo=-1, $filtro=-1, $sin_seleccion='')
    {
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        $arrResultado = $this->mfunciones_logica->ObtenerCatalogo($codigo_catalogo, $parent_codigo, $parent_tipo, $filtro);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            $i = 0;

            $key_aux = -1;
            
            foreach ($arrResultado as $key => $value) {

                $arrResultado[$i]['catalogo_descripcion'] = str_replace(array(' Y ', ' Del ', ' De ', ' En ', ' Con ', ' Más ', ' Se ', ' La ', ' A ', ' El ', ' Un ', ' O ', '[p'), array(' y ', ' del ', ' de ', ' en ', ' con ', ' más ', ' se ', ' la ', ' a ', ' el ', ' un ', ' o ', '[P'), ucwords(mb_strtolower($arrResultado[$i]['catalogo_descripcion'])));

                if($parent_tipo == 'aux_ci_creditos')
                {
                    if(strtoupper($arrResultado[$i]['catalogo_codigo']) == 'EE')
                    {
                        $key_aux = $i;
                    }
                }
                
                $i++;
            }
            
            if($parent_tipo == 'aux_ci_creditos')
            {
                if($key_aux != -1)
                {
                    unset($arrResultado[$key_aux]);
                }
                
                $arrResultado = array_merge_recursive($arrResultado, array(0 => array('catalogo_codigo' => 'EE', 'catalogo_descripcion' => 'Ext')));
            }
            
            return html_select($campo, $arrResultado, 'catalogo_codigo', 'catalogo_descripcion', $sin_seleccion, $valor);
        }
        else
        {
            $arrResultado[0] = array(
                "catalogo_codigo" => '-1',
                "catalogo_descripcion" => 'No se encontró dependencias',
            );
        }
        
        return html_select($campo, $arrResultado, 'catalogo_codigo', 'catalogo_descripcion', '', $valor);
    }
    
    function checkConvertirEstudio($sol_codigo_rubro, $sol_actividad_secundaria, $sol_codigo_rubro_sec)
    {
        try {
            
            // [-- Sección ALPHA
                
            $icono_info = '<i class="fa fa-info-circle" aria-hidden="true"></i> ';

            $flag_convertir_sw = 0; // <-- Switch que indica si se mostrará las opciones para conversión a estudio
            $flag_convertir_actividad = 1; // <-- Por defecto se selecciona la Actividad Principal  1=Principal | 2=Secundaria

            $texto_aux_convertir = 'Opcionalmente puede marcar la opción "Convertir a estudio" para crear un nuevo estudio con el rubro seleccionado (de la Actividad %s) y el cónyuge como unidad familiar (si fue registrada su información).';

            if($sol_codigo_rubro == 7 || $sol_codigo_rubro == 8)
            {
                // Validar si es rubro Agropecuario|Ingreso Fijo y si la actividad secundaria está marcada (y seleccionó un rubro)
                // Los rubros que no se convierte: Bajo Línea, Micro B, PyME o Corporativo
                if((int)$sol_actividad_secundaria == 1 && (int)$sol_codigo_rubro_sec > 0 && (int)$sol_codigo_rubro_sec <= 4)
                {
                    $flag_convertir_sw = 1;
                    $flag_convertir_actividad = 2; // <-- Si cumple el criterio se selecciona la Actividad Secundaria  1=Principal | 2=Secundaria
                    $texto_aux_convertir = sprintf($texto_aux_convertir, 'Secundaria');
                }
                else
                {
                    $texto_aux_convertir = $icono_info . ' <i> Para los rubros ' . $this->GetValorCatalogo(7, 'aux_rubro') . ' | ' . $this->GetValorCatalogo(8, 'aux_rubro') . ' registrando la Actividad Secundaria, podrá seleccionar (opcionalmente) la conversión a Estudio de Crédito.</i>';
                }
            }
            elseif($sol_codigo_rubro > 0 && $sol_codigo_rubro <= 4)
            {
                $flag_convertir_sw = 1;
                $texto_aux_convertir = sprintf($texto_aux_convertir, 'Principal');
            }
            else
            {
                $texto_aux_convertir = '';
            }

            if((int)$sol_codigo_rubro <= 0)
            {
                $texto_aux_convertir = $icono_info . ' ' . $this->lang->line('ejecutivo_perfil_tipo_rubro_error');
            }

            // Sección ALPHA --]

            $resultado = new stdClass();

            $resultado->icono_info = $icono_info;
            $resultado->flag_convertir_sw = $flag_convertir_sw;
            $resultado->flag_convertir_actividad = $flag_convertir_actividad;
            $resultado->texto_aux_convertir = $texto_aux_convertir;

            return $resultado;
            
        } catch (Exception $exc) {
            return FALSE;
        }
        
        return TRUE;
    }
    
    function ConsolidarSolicitud($geolocalizacion, $codigo_usuario, $accion_usuario, $accion_fecha, $codigo_registro)
    {
        try {
            
            // Paso 1. Se obtiene los valores del registro
            $arrResultado = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($codigo_registro);
            
            if(!isset($arrResultado[0]) || (int)$arrResultado[0]['sol_evaluacion'] != 1 || (int)$arrResultado[0]['sol_codigo_rubro'] <=0)
            {
                return FALSE;
            }
            
            $this->load->model('mfunciones_logica');
            
            // Se setea en "0" el consolidar con estudio
            $estudio = 0;
            $estudio_codigo = 0;
            
            // Se consulta ya se ha registrado un estudio con esa solicitud
            if((int)$arrResultado[0]['sol_estudio'] == 1)
            {
                $estudio = $arrResultado[0]['sol_estudio'];
                $estudio_codigo = $arrResultado[0]['sol_estudio_codigo'];
            }
            else
            {
                $prospecto_num_proceso = $arrResultado[0]['registro_num_proceso'];
                
                // SÓLO si no se conviritó a estudio, se puede convertir
                
                // [-- Sección ALPHA --]
                $convertir = $this->checkConvertirEstudio($arrResultado[0]['sol_codigo_rubro'], $arrResultado[0]['sol_actividad_secundaria'], $arrResultado[0]['sol_codigo_rubro_sec']);
                
                
                    // 10/08/2022: Se aplica la lógica para determinar si se realiza la conversión.
                    if($convertir->flag_convertir_sw == 0)
                    {
                        $prospecto_num_proceso = 0;
                        $arrResultado[0]['sol_trabajo_actividad_pertenece'] = 0;
                    }
                
                // Paso 2. Validar si el Rubro seleccionado para conversión es > 0 para registrar Nuevo Estudio de Crédito
                if((int)$arrResultado[0]['sol_trabajo_actividad_pertenece'] > 0)
                {
                    // Se valida si se convertira con los datos de Actividad Secundaria
                    if($convertir->flag_convertir_actividad == 2)
                    {
                        // Se remplaza los parámetros con los de la actividad secundaria para continuar el flujo normal de conversión a estudio
                        $arrResultado[0]['sol_dir_departamento'] = $arrResultado[0]['sol_dir_departamento_sec'];
                        $arrResultado[0]['sol_dir_localidad_ciudad'] = $arrResultado[0]['sol_dir_localidad_ciudad_sec'];
                        $arrResultado[0]['sol_cod_barrio'] = $arrResultado[0]['sol_cod_barrio_sec'];
                        $arrResultado[0]['sol_direccion_trabajo'] = $arrResultado[0]['sol_direccion_trabajo_sec'];
                        $arrResultado[0]['sol_dependencia'] = $arrResultado[0]['sol_dependencia_sec'];
                        $arrResultado[0]['sol_depen_empresa'] = $arrResultado[0]['sol_depen_empresa_sec'];
                        $arrResultado[0]['sol_depen_cargo'] = $arrResultado[0]['sol_depen_cargo_sec'];
                        $arrResultado[0]['sol_depen_ant_ano'] = $arrResultado[0]['sol_depen_ant_ano_sec'];
                        $arrResultado[0]['sol_depen_ant_mes'] = $arrResultado[0]['sol_depen_ant_mes_sec'];
                        $arrResultado[0]['sol_indepen_actividad'] = $arrResultado[0]['sol_indepen_actividad_sec'];
                        $arrResultado[0]['sol_indepen_ant_ano'] = $arrResultado[0]['sol_indepen_ant_ano_sec'];
                        $arrResultado[0]['sol_indepen_ant_mes'] = $arrResultado[0]['sol_indepen_ant_mes_sec'];
                    }
                    
                    // Es el código del Ejecutivo de Cuentas
                    $estructura_id = $arrResultado[0]['ejecutivo_id'];

                    $camp_id = (int)$arrResultado[0]['sol_trabajo_actividad_pertenece'];

                    $general_solicitante = substr($arrResultado[0]['sol_primer_nombre'] . ' ' . $arrResultado[0]['sol_segundo_nombre'] . ' ' . $arrResultado[0]['sol_primer_apellido'] . ' ' . $arrResultado[0]['sol_segundo_apellido'],0,189);
                    $general_telefono = $arrResultado[0]['sol_cel'];
                    $general_email = (str_replace(' ', '', $arrResultado[0]['sol_correo']) != '' ? $arrResultado[0]['sol_correo'] : 'sinregistro@mail.com');
                        $general_email = substr($general_email,0,150);

                        // Dirección
                        $general_direccion = '';
                        $general_direccion .= ((int)$arrResultado[0]['sol_dir_departamento']>-1 && str_replace(' ', '', $arrResultado[0]['sol_dir_departamento'])!='' ? ' - ' . $this->lang->line('sol_dir_departamento') . ' ' . $this->mfunciones_generales->GetValorCatalogoDB($arrResultado[0]['sol_dir_departamento'], 'dir_departamento') : '');
                        $general_direccion .= ((int)$arrResultado[0]['sol_dir_localidad_ciudad']>0 ? ' - ' . $this->lang->line('sol_dir_localidad_ciudad') . ' ' . $this->mfunciones_generales->GetValorCatalogoDB($arrResultado[0]['sol_dir_localidad_ciudad'], 'dir_localidad_ciudad') : '');
                        $general_direccion .= ((int)$arrResultado[0]['sol_cod_barrio']>0 ? ' - ' . $this->lang->line('sol_cod_barrio') . ' ' . $this->mfunciones_generales->GetValorCatalogoDB($arrResultado[0]['sol_cod_barrio'], 'dir_barrio_zona_uv') : '');
                        $general_direccion .= ' - ' . $arrResultado[0]['sol_direccion_trabajo'] . ' ' . $arrResultado[0]['sol_edificio_trabajo'] . ' ' . $arrResultado[0]['sol_numero_trabajo'];
                        $general_direccion = substr(ltrim($general_direccion),0,295);

                        // Actividad - Colocar si es Dependiente (más el Cargo Actual) o Independiente (más la Actividad que realiza)

                        switch ((int)$arrResultado[0]['sol_dependencia']) {
                            case 1:
                                // Dependiente
                                $general_actividad = $arrResultado[0]['sol_depen_empresa'] . ' | ' . $arrResultado[0]['sol_depen_cargo'];

                                $operacion_antiguedad_ano = (int)$arrResultado[0]['sol_depen_ant_ano'];
                                $operacion_antiguedad_mes = (int)$arrResultado[0]['sol_depen_ant_mes'];

                                break;

                            case 2:
                                // Independiente
                                $general_actividad = $arrResultado[0]['sol_indepen_actividad'];

                                $operacion_antiguedad_ano = (int)$arrResultado[0]['sol_indepen_ant_ano'];
                                $operacion_antiguedad_mes = (int)$arrResultado[0]['sol_indepen_ant_mes'];

                                break;

                            default:
                                $general_actividad = '';

                                $operacion_antiguedad_ano = 0;
                                $operacion_antiguedad_mes = 0;

                                break;
                        }

                        $general_actividad = substr(ltrim($general_actividad),0,59);

                    $general_ci = rtrim($arrResultado[0]['sol_ci'] . ' ' . str_replace(' ', '', $arrResultado[0]['sol_complemento']));

                        // Extensión
                        switch (str_replace(' ', '', $arrResultado[0]['sol_extension'])) {
                            case 'CH':
                                $general_ci_extension = 1;
                                break;
                            case 'LP':
                                $general_ci_extension = 2;
                                break;
                            case 'CB':
                                $general_ci_extension = 3;
                                break;
                            case 'OR':
                                $general_ci_extension = 4;
                                break;
                            case 'PO':
                                $general_ci_extension = 5;
                                break;
                            case 'TJ':
                                $general_ci_extension = 6;
                                break;
                            case 'SC':
                                $general_ci_extension = 7;
                                break;
                            case 'BE':
                                $general_ci_extension = 8;
                                break;
                            case 'PA':
                                $general_ci_extension = 9;
                                break;
                            case 'EE':
                                $general_ci_extension = 10;
                                break;

                            default:
                                $general_ci_extension = '-1';
                                break;
                        }

                    $general_destino = $arrResultado[0]['sol_detalle']; // <-- Colocar Detalle del destino del Crédito Solicitado
                        $general_destino = substr($general_destino,0,2500);
                    $general_interes = 3; // <-- Grado de interés Alto (3)

                    switch (str_replace(' ', '', $arrResultado[0]['sol_moneda'])) {
                        case 'usd':

                            $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
                            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);

                            $operacion_efectivo = $arrResultado[0]['sol_monto_valor'] * ((int)$arrConf[0]['conf_credito_tipo_cambio']>0 ? $arrConf[0]['conf_credito_tipo_cambio'] : 1);

                            $texto_aux_efectivo = ' (tipo de cambio ' . ((int)$arrConf[0]['conf_credito_tipo_cambio']>0 ? number_format($arrConf[0]['conf_credito_tipo_cambio'], 2, ',', '.') : 1) . ')';

                            break;

                        default:

                            $operacion_efectivo = $arrResultado[0]['sol_monto_valor'];

                            $texto_aux_efectivo = '';

                            break;
                    }

                    // Convertir el plazo en meses en días
                    $operacion_dias = 0;//$arrResultado[0]['sol_plazo'] * 30;

                    $general_comentarios = 'Registrado a través de Solicitud de Crédito (SOL_' . $codigo_registro . ') | ' . $this->lang->line('sol_monto') . ' ' . $arrResultado[0]['sol_monto'] . $texto_aux_efectivo . ' | ' . $this->lang->line('sol_plazo') . ' ' . $arrResultado[0]['sol_plazo'] . '.';
                        $general_comentarios = str_replace(':', '', $general_comentarios);

                    $general_comentarios = '';

                    // Adecuación,  el efectivo siempre empezar en 0
                    $operacion_efectivo = 0;
                    
                        // Verificar previamente que, si el registro esta consolidado retornar y salir de la funcion
                        $arrCheck = $this->VerificaSolicitudConsolidado($codigo_registro);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrCheck);

                        if (isset($arrCheck[0])) 
                        {
                            if($arrCheck[0]['sol_consolidado'] == 1)
                            {
                                return TRUE;
                            }
                        }
                        else
                        {
                            return TRUE;
                        }
                        
                    // Guardar en la DB

                    $nuevo_lead = $this->NuevoLead_Sol(
                                                    (int)$arrResultado[0]['codigo_agencia_fie'],
                                                    $camp_id,
                                                    $estructura_id,
                                                    $general_solicitante,
                                                    $general_telefono,
                                                    $general_email,
                                                    $general_direccion,
                                                    $general_actividad,
                                                    $general_destino,
                                                    $general_ci,
                                                    $general_ci_extension,
                                                    $general_interes,
                                                    $operacion_efectivo,
                                                    $operacion_dias,
                                                    $accion_usuario,
                                                    $accion_fecha,
                                                    $general_comentarios,
                                                    $operacion_antiguedad_ano,
                                                    $operacion_antiguedad_mes,
                                                    $prospecto_num_proceso
                                                    );

                    $estudio = 1;
                    $estudio_codigo = $nuevo_lead;

                    $this->mfunciones_generales->SeguimientoHitoProspecto($nuevo_lead, 1, -1, $accion_usuario, $accion_fecha, 0);
                    $this->mfunciones_generales->SeguimientoHitoProspecto($nuevo_lead, 2, -1, $accion_usuario, $accion_fecha, 0);
                        $nombre_rubro = $this->mfunciones_generales->GetValorCatalogo($camp_id, 'nombre_rubro');
                    $this->mfunciones_logica->InsertSeguimientoProspecto($nuevo_lead, 1, 0, 'Registro de Cliente con Rubro ' . $nombre_rubro, $accion_usuario, $accion_fecha);

                    // CREACIÓN DEL DIRECTORIO Y CALENDARIO

                    $path = RUTA_PROSPECTOS . 'afn_' . $nuevo_lead;

                    if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
                    {
                        mkdir($path, 0755, TRUE);
                        // Se crea el archivo html para evitar ataques de directorio
                        $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                        write_file($path . '/index.html', $cuerpo_html);
                    }

                    // Se registra la fecha en el calendario del Ejecutivo de Cuentas

                    $cal_visita_ini = $accion_fecha;
                        $cal_visita_fin = new DateTime($accion_fecha);
                    $cal_visita_fin->add(new DateInterval('PT' . 30 . 'M'));
                        $cal_visita_fin = $cal_visita_fin->format('Y-m-d H:i:s');

                    $this->mfunciones_logica->InsertarFechaCaendario($estructura_id, $nuevo_lead, 1, $cal_visita_ini, $cal_visita_fin, $accion_usuario, $accion_fecha);

                    // Paso 3. Si tiene cónyuge y el CI es diferente de vacío, validar si el Rubro es > 0 para registrar Nueva Unidad Familiar
                    if((int)$arrResultado[0]['sol_conyugue']==1 && str_replace(' ', '', $arrResultado[0]['sol_con_ci'])!='' && (int)$arrResultado[0]['sol_con_trabajo_actividad_pertenece']>0)
                    {
                        $camp_id = (int)$arrResultado[0]['sol_con_trabajo_actividad_pertenece'];

                        $general_con_solicitante = substr($arrResultado[0]['sol_con_primer_nombre'] . ' ' . $arrResultado[0]['sol_con_segundo_nombre'] . ' ' . $arrResultado[0]['sol_con_primer_apellido'] . ' ' . $arrResultado[0]['sol_con_segundo_apellido'],0,189);
                        $general_con_telefono = $arrResultado[0]['sol_con_cel'];
                        $general_con_email = (str_replace(' ', '', $arrResultado[0]['sol_con_correo']) != '' ? $arrResultado[0]['sol_con_correo'] : 'sinregistro@mail.com');
                            $general_con_email = substr($general_con_email,0,150);

                        // Dirección
                        $general_con_direccion = '';
                        $general_con_direccion .= ((int)$arrResultado[0]['sol_con_dir_departamento']>-1 && str_replace(' ', '', $arrResultado[0]['sol_con_dir_departamento'])!='' ? ' - ' . $this->lang->line('sol_con_dir_departamento') . ' ' . $this->mfunciones_generales->GetValorCatalogoDB($arrResultado[0]['sol_con_dir_departamento'], 'dir_departamento') : '');
                        $general_con_direccion .= ((int)$arrResultado[0]['sol_con_dir_localidad_ciudad']>0 ? ' - ' . $this->lang->line('sol_con_dir_localidad_ciudad') . ' ' . $this->mfunciones_generales->GetValorCatalogoDB($arrResultado[0]['sol_con_dir_localidad_ciudad'], 'dir_localidad_ciudad') : '');
                        $general_con_direccion .= ((int)$arrResultado[0]['sol_con_cod_barrio']>0 ? ' - ' . $this->lang->line('sol_con_cod_barrio') . ' ' . $this->mfunciones_generales->GetValorCatalogoDB($arrResultado[0]['sol_con_cod_barrio'], 'dir_barrio_zona_uv') : '');
                        $general_con_direccion .= ' - ' . $arrResultado[0]['sol_con_direccion_trabajo'] . ' ' . $arrResultado[0]['sol_con_edificio_trabajo'] . ' ' . $arrResultado[0]['sol_con_numero_trabajo'];
                        $general_con_direccion = substr(ltrim($general_con_direccion),0,295);

                        // Actividad - Colocar si es Dependiente (más el Cargo Actual) o Independiente (más la Actividad que realiza)

                        switch ((int)$arrResultado[0]['sol_con_dependencia']) {
                            case 1:
                                // Dependiente
                                $general_con_actividad = $arrResultado[0]['sol_con_depen_empresa'] . ' | ' . $arrResultado[0]['sol_con_depen_cargo'];

                                $operacion_con_antiguedad_ano = (int)$arrResultado[0]['sol_con_depen_ant_ano'];
                                $operacion_con_antiguedad_mes = (int)$arrResultado[0]['sol_con_depen_ant_mes'];

                                break;

                            case 2:
                                // Independiente
                                $general_con_actividad = $arrResultado[0]['sol_con_indepen_actividad'];

                                $operacion_con_antiguedad_ano = (int)$arrResultado[0]['sol_con_indepen_ant_ano'];
                                $operacion_con_antiguedad_mes = (int)$arrResultado[0]['sol_con_indepen_ant_mes'];

                                break;

                            default:
                                $general_con_actividad = '';

                                $operacion_con_antiguedad_ano = 0;
                                $operacion_con_antiguedad_mes = 0;

                                break;
                        }

                        $general_con_actividad = substr(ltrim($general_con_actividad),0,59);

                        $general_con_ci = rtrim($arrResultado[0]['sol_con_ci'] . ' ' . str_replace(' ', '', $arrResultado[0]['sol_con_complemento']));

                        // Extensión
                        switch (str_replace(' ', '', $arrResultado[0]['sol_con_extension'])) {
                            case 'CH':
                                $general_con_ci_extension = 1;
                                break;
                            case 'LP':
                                $general_con_ci_extension = 2;
                                break;
                            case 'CB':
                                $general_con_ci_extension = 3;
                                break;
                            case 'OR':
                                $general_con_ci_extension = 4;
                                break;
                            case 'PO':
                                $general_con_ci_extension = 5;
                                break;
                            case 'TJ':
                                $general_con_ci_extension = 6;
                                break;
                            case 'SC':
                                $general_con_ci_extension = 7;
                                break;
                            case 'BE':
                                $general_con_ci_extension = 8;
                                break;
                            case 'PA':
                                $general_con_ci_extension = 9;
                                break;
                            case 'EE':
                                $general_con_ci_extension = 10;
                                break;

                            default:
                                $general_con_ci_extension = '-1';
                                break;
                        }

                        $nueva_unidad_familiar = $this->NuevoFamiliar_Sol(
                                $camp_id,
                                $arrResultado[0]['ejecutivo_id'],
                                $general_con_solicitante,
                                $general_con_telefono,
                                $general_con_email,
                                $general_con_direccion,
                                $general_con_actividad,
                                $general_con_ci,
                                $general_con_ci_extension,
                                $accion_usuario,
                                $accion_fecha,
                                $operacion_con_antiguedad_ano,
                                $operacion_con_antiguedad_mes,
                                $nuevo_lead
                                );

                        $this->mfunciones_generales->SeguimientoHitoProspecto($nueva_unidad_familiar, 1, -1, $accion_usuario, $accion_fecha, 0);
                                $nombre_rubro = $this->mfunciones_generales->GetValorCatalogo($camp_id, 'nombre_rubro');

                        $arrDepende = $this->mfunciones_logica->GetProspectoDepende($nueva_unidad_familiar);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDepende);

                        if (isset($arrDepende[0])) 
                        {
                            $general_con_depende = $arrDepende[0]['general_depende'];
                        }

                        $this->mfunciones_logica->InsertSeguimientoProspecto($general_con_depende, 1, 9, 'Registro Unidad Familiar Rubro ' . $nombre_rubro . ' (Código interno ' . $nueva_unidad_familiar . ')', $accion_usuario, $accion_fecha);
                    }
                }
            }
            
            // Paso 4. Consolidar el registro
            $this->setConsolidarSolicitud($estudio, $estudio_codigo, $geolocalizacion, $codigo_usuario, $accion_usuario, $accion_fecha, $codigo_registro);
            
            // Paso 5. Limpiar registros sin solicitud asociada
            $this->cleanProspectosNoSolicitud();
            
            
        } catch (Exception $exc) {
            return FALSE;
        }
        
        return TRUE;
    }
    
    function cleanProspectosNoSolicitud()
    {
        try 
        {
            // Paso 1: Se buscan los registros sin solicitud de credito asociado
            $sql1 = "UPDATE prospecto p SET p.prospecto_etapa=6, p.prospecto_consolidado=1 
                    WHERE p.prospecto_etapa<>6 AND p.onboarding=0 AND p.general_categoria=1 AND p.prospecto_fecha_asignacion>'2022-05-01 00:00:01' AND NOT EXISTS (SELECT s.sol_id FROM solicitud_credito s WHERE p.prospecto_id=s.sol_estudio_codigo); ";
            $consulta1 = $this->db->query($sql1, array());
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function CheckIsMobile()
    {
        $useragent=$_SERVER['HTTP_USER_AGENT'];

        return (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)));
    }
    
    function GeneraPDF_SinHeader($vista, $datos, $descarga='I', $orientacion='P') {
        $this->lang->load('general', 'castellano');
        // Ajusta el limite de memoria, puede no ser requerido según instalación del servidor
            if((int)(ini_get('memory_limit')) < 128)
            {
                ini_set("memory_limit",'128M');
            }

        $html = $this->load->view($vista,$datos,true);
        
        $this->load->library('pdf');
        $pdf = $this->pdf->load();

        if($orientacion == 'L')
        {
            $pdf->AddPage('L');
        }
        
        $pdf->WriteHTML($html);

        /*        
        I: send the file inline to the browser. The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
        D: send to the browser and force a file download with the name given by name.
        F: save to a local file with the name given by name (may include a path).
        S: return the document as a string. name is ignored.
        */
        $pdf->Output('initium_solcred_' . date('Ymd_His') . '.pdf', $descarga);
    }
    
    function getHoursDaysAttention($horario_desde, $horario_hasta, $horario_dias)
    {
        if(date('H:i:s', strtotime($horario_desde)) != '00:00:00' || date('H:i:s', strtotime($horario_hasta)) != '00:00:00')
        {
            $txt_result = 'De ' . $horario_desde . ' a ' . $horario_hasta . $this->mfunciones_microcreditos->GetDiasAtencionCorto($horario_dias);
        }
        else
        {
            $txt_result = '';
        }
        
        return $txt_result;
    }
    
    function ConvertToJPEG($img)
    {
        if($this->validateIMG_simple($img) == '')
        {
            return '';
        }
        
        try {
                                
            // Convertir a JPEG
            $img = preg_replace('#^data:image/[^;]+;base64,#', '', $img);

            $imagick = new Imagick();
            $imagick->readImageBlob(base64_decode($img));
            $imagick->setBackgroundColor('white');
            $imagick->setImageFormat('jpeg');
            $imagick->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
            $imagick->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
            $imageBlob = $imagick->getImageBlob();
            $imagick->clear();

            $result_imagen = base64_encode($imageBlob);

            $result_imagen = 'data:image/jpeg;base64,' . $result_imagen;
            
        } catch (ImagickException $ex) {
            $result_imagen = '';
        }
        
        return $result_imagen;
    }
    
    function resCroquis($cantidad=2)
    {
        $resultado = new stdClass();
        
        $resultado->croquis_css = '';
        $resultado->croquis_js = '';
        
        $resultado->croquis_css = ".croquis_dibujo {
            border: 1px solid #000000;
            cursor: crosshair;
            }
            img.responsive-img,
            video.responsive-video {
            max-width: 100%;
            max-height: 300px;
            height: auto;
            border:1px solid #ccc;
            vertical-align:middle;

            }";
        $resultado->croquis_js = '! function(t, i) {
            "object" == typeof exports && "object" == typeof module ? module.exports = i() : "function" == typeof define && define.amd ? define([], i) : "object" == typeof exports ? exports["responsive-sketchpad"] = i() : t.Sketchpad = i()
        }(self, (function() {
            return (() => {
                "use strict";
                var t = {};
                return (() => {
                    var i = t,
                        e = function() {
                            function t(t, i) {
                                if (this.sketching = !1, this._strokes = [], this.undoneStrokes = [], this.readOnly = !1, this.aspectRatio = 1, this.lineWidth = 5, this.lineColor = "#000", this.lineCap = "round", this.lineJoin = "round", this.lineMiterLimit = 10, null == t) throw new Error("Must pass in a container element");
                                null != i && this.setOptions(i), this.canvas = document.createElement("canvas"), this.ctx = this.canvas.getContext("2d");
                                var e = (null == i ? void 0 : i.width) || t.clientWidth,
                                    n = (null == i ? void 0 : i.height) || e * this.aspectRatio;

                                this.setCanvasSize(e, n), t.appendChild(this.canvas), this._strokes.length > 0 && this.redraw(), this.listen()
                            }
                            return Object.defineProperty(t.prototype, "strokes", {
                                get: function() {
                                    return this._strokes.map((function(t) {
                                        return t.toObj()
                                    }))
                                },
                                enumerable: !1,
                                configurable: !0
                            }), Object.defineProperty(t.prototype, "undos", {
                                get: function() {
                                    return this.undoneStrokes.map((function(t) {
                                        return t.toObj()
                                    }))
                                },
                                enumerable: !1,
                                configurable: !0
                            }), Object.defineProperty(t.prototype, "opts", {
                                get: function() {
                                    return {
                                        backgroundColor: this.backgroundColor,
                                        readOnly: this.readOnly,
                                        width: this.canvas.width,
                                        height: this.canvas.height,
                                        aspectRatio: this.canvas.width / this.canvas.height,
                                        line: {
                                            size: this.lineWidth,
                                            color: this.lineColor,
                                            cap: this.lineCap,
                                            join: this.lineJoin,
                                            miterLimit: this.lineMiterLimit
                                        }
                                    }
                                },
                                enumerable: !1,
                                configurable: !0
                            }), t.prototype.toJSON = function() {
                                return {
                                    aspectRatio: this.canvas.width / this.canvas.height,
                                    strokes: this.strokes
                                }
                            }, t.prototype.loadJSON = function(t) {
                                var i = t.strokes || [];
                                this._strokes = i.map((function(t) {
                                    return s.fromObj(t)
                                })), this.redraw()
                            }, t.prototype.toDataURL = function(t) {
                                return this.canvas.toDataURL(t)
                            }, t.prototype.setCanvasSize = function(t, i) {
                                if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB|PlayBook|IEMobile|Windows Phone|Kindle|Silk|Opera Mini/i.test(navigator.userAgent)){
                                    t=300;
                                    i=250;
                                }
                                else{
                                    t=700;
                                    i=400;
                                }
                                this.canvas.setAttribute("width", t.toString()), this.canvas.setAttribute("height", i.toString()), this.canvas.style.width = t + "px", this.canvas.style.height = i + "px"
                            }, t.prototype.getCanvasSize = function() {
                                return {
                                    width: this.canvas.width,
                                    height: this.canvas.height
                                }
                            }, t.prototype.setLineWidth = function(t) {
                                this.lineWidth = t
                            }, t.prototype.setLineSize = function(t) {
                                this.lineWidth = t
                            }, t.prototype.setLineColor = function(t) {
                                this.lineColor = t
                            }, t.prototype.setReadOnly = function(t) {
                                this.readOnly = t
                            }, t.prototype.undo = function() {
                                0 !== this._strokes.length && (this.undoneStrokes.push(this._strokes.pop()), this.redraw())
                            }, t.prototype.redo = function() {
                                0 !== this.undoneStrokes.length && (this._strokes.push(this.undoneStrokes.pop()), this.redraw())
                            }, t.prototype.clear = function() {
                                this.undoneStrokes = [], this._strokes = [], this.redraw()
                            }, t.prototype.drawLine = function(t, i, e) {
                                this.setOptions({
                                    line: e
                                }), t = this.getPointRelativeToCanvas(new o(t.x, t.y)), i = this.getPointRelativeToCanvas(new o(i.x, i.y)), this.pushStroke([t, i]), this.redraw()
                            }, t.prototype.resize = function(t) {
                                var i = 0;
                                if(t>600){
                                    i = 450;
                                }
                                else{
                                    i = 280;
                                }

                                this.lineWidth = this.lineWidth * (t / this.canvas.width), this.setCanvasSize(t, i), this.redraw()
                            }, t.prototype.getPointRelativeToCanvas = function(t) {
                                return {
                                    x: t.x / this.canvas.width,
                                    y: t.y / this.canvas.height
                                }
                            }, t.prototype.getLineSizeRelativeToCanvas = function(t) {
                                return t / this.canvas.width
                            }, t.prototype.setOptions = function(t) {
                                var i, e, n, o, r, a;
                                t.backgroundColor && (this.backgroundColor = t.backgroundColor), (null === (i = t.line) || void 0 === i ? void 0 : i.size) && (this.lineWidth = t.line.size), (null === (e = t.line) || void 0 === e ? void 0 : e.cap) && (this.lineCap = t.line.cap), (null === (n = t.line) || void 0 === n ? void 0 : n.join) && (this.lineJoin = t.line.join), (null === (o = t.line) || void 0 === o ? void 0 : o.miterLimit) && (this.lineMiterLimit = t.line.miterLimit), t.aspectRatio && (this.aspectRatio = t.aspectRatio), t.data && (this._strokes = null !== (a = null === (r = t.data.strokes) || void 0 === r ? void 0 : r.map((function(t) {
                                    return s.fromObj(t)
                                }))) && void 0 !== a ? a : []), t.onDrawEnd && (this.onDrawEnd = t.onDrawEnd)
                            }, t.prototype.getCursorRelativeToCanvas = function(t) {
                                var i, e = this.canvas.getBoundingClientRect();
                                if (n(t)) {
                                    var s = t;
                                    i = new o(s.touches[0].clientX - e.left, s.touches[0].clientY - e.top)
                                } else {
                                    var r = t;
                                    i = new o(r.clientX - e.left, r.clientY - e.top)
                                }
                                return new o(i.x / this.canvas.width, i.y / this.canvas.height)
                            }, t.prototype.normalizePoint = function(t) {
                                return new o(t.x * this.canvas.width, t.y * this.canvas.height)
                            }, t.prototype.getLineWidthRelativeToCanvas = function(t) {
                                return t / this.canvas.width
                            }, t.prototype.normalizeLineWidth = function(t) {
                                return t * this.canvas.width
                            }, t.prototype.clearCanvas = function() {
                                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height), this.backgroundColor && (this.ctx.fillStyle = rgb(200,0,0), this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height))
                            }, t.prototype.drawStroke = function(t) {
                                if (null != t.points) {
                                    this.ctx.beginPath();
                                    for (var i = 0; i < t.points.length - 1; i++) {
                                        var e = this.normalizePoint(t.points[i]),
                                            n = this.normalizePoint(t.points[i + 1]);
                                        this.ctx.moveTo(e.x, e.y), this.ctx.lineTo(n.x, n.y)
                                    }
                                    this.ctx.closePath(), t.color && (this.ctx.strokeStyle = t.color), t.width && (this.ctx.lineWidth = this.normalizeLineWidth(t.width)), t.join && (this.ctx.lineJoin = t.join), t.cap && (this.ctx.lineCap = t.cap), t.miterLimit && (this.ctx.miterLimit = t.miterLimit), this.ctx.stroke()
                                }
                            }, t.prototype.pushStroke = function(t) {
                                this._strokes.push(s.fromObj({
                                    points: t,
                                    size: this.getLineWidthRelativeToCanvas(this.lineWidth),
                                    color: this.lineColor,
                                    cap: this.lineCap,
                                    join: this.lineJoin,
                                    miterLimit: this.lineMiterLimit
                                }))
                            }, t.prototype.pushPoint = function(t) {
                                var i = this._strokes[this._strokes.length - 1];
                                i.points && i.points.push(t)
                            }, t.prototype.redraw = function() {
                                var t = this;
                                this.clearCanvas(), this._strokes.forEach((function(i) {
                                    return t.drawStroke(i)
                                }))
                            }, t.prototype.listen = function() {
                                var t = this;
                                ["mousedown", "touchstart"].forEach((function(i) {
                                    return t.canvas.addEventListener(i, (function(i) {
                                        return t.startStrokeHandler(i)
                                    }))
                                })), ["mousemove", "touchmove"].forEach((function(i) {
                                    return t.canvas.addEventListener(i, (function(i) {
                                        return t.drawStrokeHandler(i)
                                    }))
                                })), ["mouseup", "mouseleave", "touchend"].forEach((function(i) {
                                    return t.canvas.addEventListener(i, (function(i) {
                                        return t.endStrokeHandler(i)
                                    }))
                                }))
                            }, t.prototype.startStrokeHandler = function(t) {
                                if (t.preventDefault(), !this.readOnly) {
                                    this.sketching = !0;
                                    var i = this.getCursorRelativeToCanvas(t);
                                    this.pushStroke([i]), this.redraw()
                                }
                            }, t.prototype.drawStrokeHandler = function(t) {
                                if (t.preventDefault(), this.sketching) {
                                    var i = this.getCursorRelativeToCanvas(t);
                                    this.pushPoint(i), this.redraw()
                                }
                            }, t.prototype.endStrokeHandler = function(t) {
                                if (t.preventDefault(), this.sketching && (this.sketching = !1, !n(t))) {
                                    var i = this.getCursorRelativeToCanvas(t);
                                    this.pushPoint(i), this.redraw(), this.onDrawEnd && this.onDrawEnd()
                                }
                            }, t
                        }();

                    function n(t) {
                        return -1 !== t.type.indexOf("touch")
                    }
                    i.default = e;
                    var o = function(t, i) {
                            this.x = t, this.y = i
                        },
                        s = function() {
                            function t() {}
                            return t.fromObj = function(i) {
                                var e = new t;
                                return e.points = i.points, e.width = i.size, e.color = i.color, e.cap = i.cap, e.join = i.join, e.miterLimit = i.miterLimit, e
                            }, t.prototype.toObj = function() {
                                return {
                                    points: this.points,
                                    size: this.width,
                                    color: this.color,
                                    cap: this.cap,
                                    join: this.join,
                                    miterLimit: this.miterLimit
                                }
                            }, t
                        }()
                })(), t.default
            })()
        }));
        ';
        
        if((int)$cantidad <= 1) { $cantidad = 1; }
        
        // Se arma la cantidad de Croquis
        for($croquis_id=1; $croquis_id<=$cantidad; $croquis_id++)
        {
            $resultado->croquis_js .= '
                
                function clear__' . $croquis_id . '() {
                    $("#mensaje_lienzo__' . $croquis_id . '").fadeIn();
                    $("#confirmacion_lienzo__' . $croquis_id . '").fadeIn();
                    $("#confirmacion__' . $croquis_id . '").click( function() {
                        pad__' . $croquis_id . '.clear();
                        ocultar__' . $croquis_id . '();       
                    });
                    $("#negacion__' . $croquis_id . '").click( function() {
                        ocultar__' . $croquis_id . '();    
                    });
                }
                function ocultar__' . $croquis_id . '(){
                    $("#mensaje_lienzo__' . $croquis_id . '").fadeOut();
                    $("#confirmacion_lienzo__' . $croquis_id . '").fadeOut();
                }
                document.getElementById("clear__' . $croquis_id . '").onclick = clear__' . $croquis_id . ';

                function undo__' . $croquis_id . '() {
                    pad__' . $croquis_id . '.undo();
                }
                document.getElementById("undo__' . $croquis_id . '").onclick = undo__' . $croquis_id . ';

                function downloadPng__' . $croquis_id . '() {
                    currImage__' . $croquis_id . ' = $("#img_croquis__' . $croquis_id . '");
                    var data = pad__' . $croquis_id . '.canvas.toDataURL("image/png");
                    currImage__' . $croquis_id . '.attr( "src", data );
                    $("#croquis_base64__' . $croquis_id . '").val(data);
                    $( "#tab1__' . $croquis_id . '" ).trigger( "click" );
                    ocultar__' . $croquis_id . '();
                    $("#error-imagen__' . $croquis_id . '").hide();
                    $("#txt_no_registrado__' . $croquis_id . '").hide();
                    MostrarContenedorCroquis__' . $croquis_id . '("1");
                }

                document.getElementById("cargar__' . $croquis_id . '").onclick = downloadPng__' . $croquis_id . ';

                function limpiar__' . $croquis_id . '(){
                    input=document.getElementById("image-file__' . $croquis_id . '");
                    input.value = "";
                    input.type = "" ;input.type = "file";
                }

                var type__' . $croquis_id . ' = "unknown";
                function uploadFile__' . $croquis_id . '(file) {
                    $("#error-imagen__' . $croquis_id . '").hide();
                    $("#cargando-imagen__' . $croquis_id . '").show();
                    currImage__' . $croquis_id . ' = $( "#img_croquis__' . $croquis_id . '" );
                    $(file.id).attr("src","");
                    var files = document.getElementById( file.id ).files;
                    if (!files || !files[0]) return;
                    var blob = files[0]; // See step 1 above
                    var fileReader = new FileReader();
                    fileReader.onloadend = function(e) {
                    var arr = (new Uint8Array(e.target.result)).subarray(0, 4);
                    var header = "";
                    for(var i = 0; i < arr.length; i++) {
                        header += arr[i].toString(16);
                       }
                    switch (header) {
                        case "89504e47":
                            type__' . $croquis_id . ' = "image/png";
                            break;
                        case "47494638":
                            type__' . $croquis_id . ' = "image/gif";
                            break;
                        case "ffd8ffe0":
                        case "ffd8ffe1":
                        case "ffd8ffe2":
                        case "ffd8ffe3":
                        case "ffd8ffe8":
                            type__' . $croquis_id . ' = "image/jpeg";
                            break;
                        default:
                            type__' . $croquis_id . ' = "unknown"; // Or you can use the blob.type__' . $croquis_id . ' as fallback
                            break;
                    }
                    };
                    fileReader.readAsArrayBuffer(blob);
                    var ctx = snapshot__' . $croquis_id . '.getContext( "2d" );
                    var FR = new FileReader();
                    FR.addEventListener(
                        "load",
                        function( evt ) {
                        var img = new Image();
                        if(type__' . $croquis_id . '=="unknown"){
                            $(file.id).attr("src","");
                            $("#cargando-imagen__' . $croquis_id . '").hide();
                            $("#error-imagen__' . $croquis_id . '").show();
                            limpiar__' . $croquis_id . '();
                           return; 
                        }else if(files[0].size >= 5242880){
                            $(file.id).attor("src","");
                            $("#cargando-imagen__' . $croquis_id . '").hide();
                            $("#error-imagen__' . $croquis_id . '").show();
                           limpiar__' . $croquis_id . '();
                           return;
                          }                
                            img.addEventListener(
                                "load",
                                function( evt ) {
                                    $("#cargando-imagen__' . $croquis_id . '").hide();
                                    $("#error-imagen__' . $croquis_id . '").hide();
                                    $("#txt_no_registrado__' . $croquis_id . '").hide();
                                    ctx.clearRect( 0, 0, ctx.canvas.width, ctx.canvas.height );
                                    ctx.drawImage( img, 0, 0, ctx.canvas.width, ctx.canvas.height );
                                    var dataUrl = snapshot__' . $croquis_id . '.toDataURL();
                                    currImage__' . $croquis_id . '.attr( "src", dataUrl );
                                    $("#croquis_base64__' . $croquis_id . '").val(dataUrl);
                                    limpiar__' . $croquis_id . '();
                                    $( "#tab1__' . $croquis_id . '" ).trigger( "click" );
                                    ocultar__' . $croquis_id . '();
                                    $("#txt_no_registrado").hide();
                                    MostrarContenedorCroquis__' . $croquis_id . '("1");

                                });
                        img.src = evt.target.result;
                    });
                    FR.readAsDataURL( files[0] );
                }

                window.onresize = function (e) {
                    pad__' . $croquis_id . '.resize(el__' . $croquis_id . '.offsetWidth);
                }

                function MostrarContenedorCroquis__' . $croquis_id . '(tab){

                    switch (tab) {
                        case "1":
                            $("#resultado__' . $croquis_id . '").show();
                            $("#lienzo__' . $croquis_id . '").hide();
                            $("#imagen__' . $croquis_id . '").hide();
                            $("#error-imagen__' . $croquis_id . '").hide();
                            $("#tab1__' . $croquis_id . '").attr("class", "panel-heading active");
                            ocultar__' . $croquis_id . '();
                            break;

                        case "2":
                            $("#resultado__' . $croquis_id . '").hide();
                            $("#lienzo__' . $croquis_id . '").css("display", "inline-block")
                            $("#imagen__' . $croquis_id . '").hide();
                            $("#error-imagen__' . $croquis_id . '").hide();
                            $("#tab2__' . $croquis_id . '").attr("class", "panel-heading active");
                            ocultar__' . $croquis_id . '();
                            break;

                        case "3":
                            $("#resultado__' . $croquis_id . '").hide();
                            $("#lienzo__' . $croquis_id . '").hide();
                            $("#imagen__' . $croquis_id . '").show();    
                            $("#error-imagen__' . $croquis_id . '").hide();
                            $("#tab3__' . $croquis_id . '").attr("class", "panel-heading active");
                            ocultar__' . $croquis_id . '();
                            break;
                        default:

                            break;
                    }
                }';
        }
        
        return $resultado;
    }
    
    function ObtenerDatosRegionCodigo($codigo_region)
    {
        $this->load->model('mfunciones_logica');
        
        $resultado = new stdClass();
        
        $resultado->nombre = '';
        $resultado->departamento = '';
        $resultado->provincia = '';
        $resultado->ciudad = '';
        $resultado->ciudad_codigo = '';
        $resultado->geo = '';
        $resultado->responsable = '';
        $resultado->firma = '';
        
        $arrResultado = $this->mfunciones_logica->ObtenerDatosRegional($codigo_region);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            $resultado->nombre = $arrResultado[0]['estructura_regional_nombre'] . ((int)$arrResultado[0]["estructura_regional_estado"]==1 ? '' : ' (Cerrada)');
            $resultado->departamento = $this->mfunciones_generales->GetValorCatalogoDB($arrResultado[0]['estructura_regional_departamento'], 'dir_departamento');
            $resultado->provincia = $this->mfunciones_generales->GetValorCatalogoDB($arrResultado[0]['estructura_regional_provincia'], 'dir_provincia');
            $resultado->ciudad = $this->mfunciones_generales->GetValorCatalogoDB($arrResultado[0]['estructura_regional_ciudad'], 'dir_localidad_ciudad');
            $resultado->ciudad_codigo = $arrResultado[0]['estructura_regional_ciudad'];
            $resultado->direccion = $arrResultado[0]['estructura_regional_direccion'];
            $resultado->geo = $arrResultado[0]['estructura_regional_geo'];
            $resultado->responsable = $arrResultado[0]['estructura_regional_responsable'];
            $resultado->firma = $arrResultado[0]['estructura_regional_firma'];
        }
        
        return $resultado;
    }
    
    function ObtenerSol_from_Pros($codigo_prospecto)
    {
        $resultado = new stdClass();
        
        $resultado->sol_id = 0;
        $resultado->sol_monto = 0;
        $resultado->sol_moneda = 0;
        $resultado->sol_detalle = 0;
        $resultado->sol_plazo = 0;
        $resultado->sol_consolidado_usuario = 0;
        $resultado->sol_consolidado_fecha = 0;
        $resultado->sol_consolidado_geo = 0;
        
        $resultado->sol_codigo_rubro = 0;
        $resultado->sol_asistencia = 0;
        $resultado->codigo_agencia_fie = 0;
        
        
        $arrResultado = $this->getObtenerSol_from_Pros($codigo_prospecto);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            $resultado->sol_id = $arrResultado[0]['sol_id'];
            $resultado->sol_monto = $arrResultado[0]['sol_monto'];
            $resultado->sol_moneda = $arrResultado[0]['sol_moneda'];
            $resultado->sol_detalle = $arrResultado[0]['sol_detalle'];
            $resultado->sol_plazo = $arrResultado[0]['sol_plazo'];
            $resultado->sol_consolidado_usuario = $arrResultado[0]['sol_consolidado_usuario'];
            $resultado->sol_consolidado_fecha = $arrResultado[0]['sol_consolidado_fecha'];
            $resultado->sol_consolidado_geo = $arrResultado[0]['sol_consolidado_geo'];
            
            $resultado->sol_codigo_rubro = $arrResultado[0]['sol_codigo_rubro'];
            $resultado->sol_asistencia = $arrResultado[0]['sol_asistencia'];
            $resultado->codigo_agencia_fie = $arrResultado[0]['codigo_agencia_fie'];
        }
        
        return $resultado;
    }
    
    function AuxLimpiarCadena($data) {
        
        if((string)$data == ' ')
        {
            $data = '';
        }
        $data = htmlspecialchars($data);
        
        return $data;
    }
    
    function ValidarNumOperacion($registro_num_proceso)
    {
        return (!preg_match("/^[0-9]+$/", $registro_num_proceso) || $this->validarTxtCampo($registro_num_proceso, '', 2, (int)$this->lang->line('registro_num_proceso_cantidad')) != '');
    }
    
    // Perfil Tipo Categoría "B"
    function CheckTipoPerfilEjecutivo($codigo_ejecutivo, $codigo_perfil)
    {
        $arrResultado = $this->getTipoPerfilEjecutivo($codigo_ejecutivo, $codigo_perfil);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    /*************** FUNCTIONS - FIN ****************************/
    
/*************** DATABASE - INICIO ****************************/
    
    /*************** SOLICITUD DE CREDITOS - INICIO ****************************/

    function getObtenerSol_from_Pros($codigo_prospecto) 
    {        
        try 
        {
            $sql = "SELECT p.prospecto_id, s.sol_id, s.sol_codigo_rubro, s.sol_asistencia, s.codigo_agencia_fie, s.sol_monto, s.sol_moneda, s.sol_plazo, s.sol_detalle, s.sol_consolidado_usuario, s.sol_consolidado_fecha, s.sol_consolidado_geo
                    FROM prospecto p
                    INNER JOIN solicitud_credito s ON s.sol_estudio_codigo=p.prospecto_id
                    WHERE prospecto_id=? LIMIT 1"; 

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function ObtenerTotalProspectos($codigo_ejecutivo) 
    {        
        try 
        {
            $sql = "SELECT count(prospecto_id) as 'total_prospecto' FROM prospecto WHERE prospecto_consolidado=0 AND ejecutivo_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo));

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
    
    function ObtenerTotalSolCred($codigo_ejecutivo) 
    {        
        try 
        {
            $sql = "SELECT count(sol_id) as 'total_solcred' FROM solicitud_credito WHERE sol_estado=1 AND ejecutivo_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo));

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
    
    function Insertar_SolicitudCredito($identificador_ejecutivo, $identificador_afiliador, $codigo_agencia_fie,
            
                    $sol_dir_localidad_ciudad,
                    $sol_dir_provincia,
                    $sol_dir_departamento,
                    $sol_ci,
                    $sol_extension,
                    $sol_complemento,
                    $sol_primer_nombre,
                    $sol_segundo_nombre,
                    $sol_primer_apellido,
                    $sol_segundo_apellido,
                    $sol_correo,
                    $sol_cel,
                    $sol_telefono,
                    $sol_dependencia,
                    $sol_indepen_actividad,
                    $sol_indepen_telefono,
                    $sol_depen_empresa,
                    $sol_depen_cargo,
                    $sol_depen_telefono,
                    $sol_conyugue,
                    $sol_con_ci,
                    $sol_con_extension,
                    $sol_con_complemento,
                    $sol_con_primer_nombre,
                    $sol_con_segundo_nombre,
                    $sol_con_primer_apellido,
                    $sol_con_segundo_apellido,
                    $sol_con_correo,
                    $sol_con_cel,
                    $sol_con_telefono,
                    $sol_con_dependencia,
                    $sol_con_indepen_actividad,
                    $sol_con_indepen_telefono,
                    $sol_con_depen_empresa,
                    $sol_con_depen_cargo,
                    $sol_con_depen_telefono,
                    $sol_monto,
                    $sol_moneda,
                    $sol_detalle,
                    $sol_direccion_trabajo,
                    $sol_edificio_trabajo,
                    $sol_numero_trabajo,
                    $sol_cod_barrio,
                    $sol_dir_referencia,
                    $sol_geolocalizacion,
                    $sol_croquis,
                    $sol_con_direccion_trabajo,
                    $sol_con_edificio_trabajo,
                    $sol_con_numero_trabajo,
                    $sol_con_cod_barrio,
                    $sol_con_dir_referencia,
                    $sol_con_geolocalizacion,
                    $sol_con_croquis,
                    $sol_con_dir_departamento,
                    $sol_con_dir_provincia,
                    $sol_con_dir_localidad_ciudad,
                    $accion_usuario,
                    $accion_fecha)
    {        
        try
        {
            $sql = "INSERT INTO solicitud_credito

                    (
                    ejecutivo_id, afiliador_id, codigo_agencia_fie, sol_fecha, 
                    
                    sol_dir_localidad_ciudad,
                    sol_dir_provincia,
                    sol_dir_departamento,
                    sol_ci,
                    sol_extension,
                    sol_complemento,
                    sol_primer_nombre,
                    sol_segundo_nombre,
                    sol_primer_apellido,
                    sol_segundo_apellido,
                    sol_correo,
                    sol_cel,
                    sol_telefono,
                    sol_dependencia,
                    sol_indepen_actividad,
                    sol_indepen_telefono,
                    sol_depen_empresa,
                    sol_depen_cargo,
                    sol_depen_telefono,
                    sol_conyugue,
                    sol_con_ci,
                    sol_con_extension,
                    sol_con_complemento,
                    sol_con_primer_nombre,
                    sol_con_segundo_nombre,
                    sol_con_primer_apellido,
                    sol_con_segundo_apellido,
                    sol_con_correo,
                    sol_con_cel,
                    sol_con_telefono,
                    sol_con_dependencia,
                    sol_con_indepen_actividad,
                    sol_con_indepen_telefono,
                    sol_con_depen_empresa,
                    sol_con_depen_cargo,
                    sol_con_depen_telefono,
                    sol_monto,
                    sol_moneda,
                    sol_detalle,
                    sol_direccion_trabajo,
                    sol_edificio_trabajo,
                    sol_numero_trabajo,
                    sol_cod_barrio,
                    sol_dir_referencia,
                    sol_geolocalizacion,
                    sol_croquis,
                    sol_con_direccion_trabajo,
                    sol_con_edificio_trabajo,
                    sol_con_numero_trabajo,
                    sol_con_cod_barrio,
                    sol_con_dir_referencia,
                    sol_con_geolocalizacion,
                    sol_con_croquis,
                    sol_con_dir_departamento,
                    sol_con_dir_provincia,
                    sol_con_dir_localidad_ciudad,
                    accion_usuario,
                    accion_fecha,
                    sol_croquis_sec
                    )
                    VALUES
                    (
                    ?, ?, ?, ?, 
                    
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?,
                    ?, 
                    ?, 
                    ?,
                    ?
                    )
                    ";

            $consulta = $this->db->query($sql, array($identificador_ejecutivo, $identificador_afiliador, $codigo_agencia_fie, $accion_fecha, 
            
                    $sol_dir_localidad_ciudad,
                    $sol_dir_provincia,
                    $sol_dir_departamento,
                    $sol_ci,
                    $sol_extension,
                    $sol_complemento,
                    $sol_primer_nombre,
                    $sol_segundo_nombre,
                    $sol_primer_apellido,
                    $sol_segundo_apellido,
                    $sol_correo,
                    $sol_cel,
                    $sol_telefono,
                    $sol_dependencia,
                    $sol_indepen_actividad,
                    $sol_indepen_telefono,
                    $sol_depen_empresa,
                    $sol_depen_cargo,
                    $sol_depen_telefono,
                    $sol_conyugue,
                    $sol_con_ci,
                    $sol_con_extension,
                    $sol_con_complemento,
                    $sol_con_primer_nombre,
                    $sol_con_segundo_nombre,
                    $sol_con_primer_apellido,
                    $sol_con_segundo_apellido,
                    $sol_con_correo,
                    $sol_con_cel,
                    $sol_con_telefono,
                    $sol_con_dependencia,
                    $sol_con_indepen_actividad,
                    $sol_con_indepen_telefono,
                    $sol_con_depen_empresa,
                    $sol_con_depen_cargo,
                    $sol_con_depen_telefono,
                    $sol_monto,
                    $sol_moneda,
                    $sol_detalle,
                    $sol_direccion_trabajo,
                    $sol_edificio_trabajo,
                    $sol_numero_trabajo,
                    $sol_cod_barrio,
                    $sol_dir_referencia,
                    $sol_geolocalizacion,
                    $sol_croquis,
                    $sol_con_direccion_trabajo,
                    $sol_con_edificio_trabajo,
                    $sol_con_numero_trabajo,
                    $sol_con_cod_barrio,
                    $sol_con_dir_referencia,
                    $sol_con_geolocalizacion,
                    $sol_con_croquis,
                    $sol_con_dir_departamento,
                    $sol_con_dir_provincia,
                    $sol_con_dir_localidad_ciudad,
                    $accion_usuario,
                    $accion_fecha,
                    ''));
            
            $listaResultados = $this->db->insert_id();
        }
        catch (Exception $e)
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function ObtenerBandejaSolicitudCredito($codigo, $estado=-1, $lista_region=-1)
    {        
        try 
        {
            $condicion = '';
            
            if($codigo != -1)
            {
                $condicion .= ' AND s.sol_id=' . $codigo;
            }
            
            if($estado != -1)
            {
                $condicion .= ' AND s.sol_estado=' . $estado;
            }
            
            if($lista_region != -1)
            {
                $condicion .= " AND s.codigo_agencia_fie IN ($lista_region)";
            }
            
            $sql = "SELECT usuario_email AS agente_correo, usuarios.usuario_id AS agente_codigo, CONCAT_WS(' ', usuarios.usuario_nombres, usuarios.usuario_app, usuarios.usuario_apm) AS agente_nombre, estructura_regional_nombre, s.sol_id, s.codigo_agencia_fie, s.sol_fecha, s.sol_asignado_fecha, s.sol_rechazado_fecha, s.sol_estado, s.sol_asistencia, s.sol_ci, s.sol_complemento, s.sol_extension, s.sol_primer_nombre, s.sol_segundo_nombre, s.sol_primer_apellido, s.sol_segundo_apellido, s.sol_moneda, s.sol_monto, s.sol_detalle, s.sol_correo, s.sol_cel FROM solicitud_credito s 
                    INNER JOIN ejecutivo ON ejecutivo.ejecutivo_id=s.ejecutivo_id INNER JOIN usuarios ON usuarios.usuario_id=ejecutivo.usuario_id
                    INNER JOIN estructura_agencia ON estructura_agencia.estructura_agencia_id=usuarios.estructura_agencia_id INNER JOIN estructura_regional ON estructura_regional.estructura_regional_id=estructura_agencia.estructura_regional_id
                    WHERE sol_asistencia=0 " . $condicion . " ORDER BY s.sol_fecha DESC "; 

            $consulta = $this->db->query($sql, array($codigo, $estado));

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
    
    function ObtenerBandejaSolicitudCreditoEjecutivo($codigo_ejecutivo, $estado=1)
    {        
        try
        {
            $sql = "SELECT sc.sol_id, ejecutivo_id, sc.sol_codigo_rubro, sc.sol_estado, sc.sol_asignado_fecha, sc.sol_consolidado, sc.sol_consolidado_fecha, sc.sol_checkin, sc.sol_checkin_geo, sc.sol_llamada, sc.sol_fecha_conclusion, sc.sol_observado_app, sc.sol_ci, sc.sol_extension, sc.sol_complemento, sc.sol_primer_nombre, sc.sol_segundo_nombre, sc.sol_primer_apellido, sc.sol_segundo_apellido, sc.sol_cel, c.camp_id, c.camp_nombre, et.etapa_nombre, et.etapa_color 
                    FROM solicitud_credito sc
                    INNER JOIN campana c ON c.camp_id=6
                    INNER JOIN etapa et ON et.etapa_id = CASE sc.sol_estado WHEN 1 THEN 19 WHEN 2 THEN 20 WHEN 3 THEN 21 END
                    WHERE sc.sol_consolidado=0 AND sc.sol_estado=? AND sc.ejecutivo_id=? ORDER BY sc.sol_asignado_fecha DESC "; 

            $consulta = $this->db->query($sql, array($estado, $codigo_ejecutivo));

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
    
    function ObtenerDetalleSolicitudCredito($codigo, $estado=-1, $lista_region=-1)
    {        
        try 
        {
            $condicion = '';
            
            if($codigo != -1)
            {
                $condicion .= ' AND s.sol_id=' . $codigo;
            }
            
            if($estado != -1)
            {
                $condicion .= ' AND s.sol_estado=' . $estado;
            }
            
            if($lista_region != -1)
            {
                $condicion .= " AND s.codigo_agencia_fie IN ($lista_region)";
            }
            
            $sql = "SELECT ejecutivo.ejecutivo_perfil_tipo, usuario_email AS agente_correo, usuarios.usuario_id AS agente_codigo, CONCAT_WS(' ', usuarios.usuario_nombres, usuarios.usuario_app, usuarios.usuario_apm) AS agente_nombre, usuarios.usuario_telefono, estructura_regional_nombre, estructura_regional_departamento, estructura_regional_provincia, estructura_regional_ciudad, s.* FROM solicitud_credito s 
                    INNER JOIN ejecutivo ON ejecutivo.ejecutivo_id=s.ejecutivo_id INNER JOIN usuarios ON usuarios.usuario_id=ejecutivo.usuario_id
                    INNER JOIN estructura_agencia ON estructura_agencia.estructura_agencia_id=usuarios.estructura_agencia_id INNER JOIN estructura_regional ON estructura_regional.estructura_regional_id=estructura_agencia.estructura_regional_id
                    WHERE 1 " . $condicion . " ORDER BY s.sol_fecha DESC "; 

            $consulta = $this->db->query($sql, array($codigo, $estado));

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

    function RechazarSolicitudCredito($solicitud_observacion, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE solicitud_credito SET sol_estado=3, sol_rechazado=1, sol_rechazado_usuario=?, sol_rechazado_fecha=?, sol_rechazado_texto=?, accion_usuario=?, accion_fecha=? WHERE sol_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_usuario, $fecha_actual, $solicitud_observacion, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function RechazarSolicitudCreditoApp($codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE solicitud_credito SET sol_observado_app=0, sol_consolidado=1, sol_consolidado_usuario=?, sol_consolidado_fecha=?, sol_estado=3, sol_rechazado=1, sol_rechazado_usuario=?, sol_rechazado_fecha=?, accion_usuario=?, accion_fecha=? WHERE sol_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_usuario, $fecha_actual, $codigo_usuario, $fecha_actual, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function AsignarSolicitudCredito($ejecutivo_id, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE solicitud_credito SET ejecutivo_id=?, sol_estado=1, sol_asignado=1, sol_asignado_usuario=?, sol_asignado_fecha=?, accion_usuario=?, accion_fecha=? WHERE sol_id=? "; 
            
            $consulta = $this->db->query($sql, array($ejecutivo_id, $codigo_usuario, $fecha_actual, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ListadoLogsSMS_failed()
    {        
        try
        {
            $sql = "SELECT auditoria_movil_parametros as 'result', accion_fecha as 'date' FROM auditoria_movil WHERE auditoria_movil_servicio='External__sms_solicitud_credito' "; 

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
    
    function SolVerificaCheckIn($codigo_solicitud) 
    {        
        try 
        {
            $sql = "SELECT sol_checkin FROM solicitud_credito WHERE sol_id=? AND sol_checkin=1 "; 

            $consulta = $this->db->query($sql, array($codigo_solicitud));

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
    
    function SolUpdateCheckIn($fechaCheckIn, $geoCheckIn, $codigo_solicitud, $usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "UPDATE solicitud_credito SET sol_checkin=1, sol_checkin_fecha=?, sol_checkin_geo=?, accion_usuario=?, accion_fecha=? WHERE sol_id=? "; 

            $consulta = $this->db->query($sql, array($fechaCheckIn, $geoCheckIn, $usuario, $accion_fecha, $codigo_solicitud));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function SolVerificaCheckOut($codigo_solicitud) 
    {        
        try 
        {
            $sql = "SELECT sol_llamada FROM solicitud_credito WHERE sol_id=? AND sol_llamada=1 "; 

            $consulta = $this->db->query($sql, array($codigo_solicitud));

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
    
    function SolUpdateCheckOut($fechaCheckIn, $geoCheckIn, $codigo_solicitud, $usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "UPDATE solicitud_credito SET sol_llamada=1, sol_llamada_fecha=?, sol_llamada_geo=?, accion_usuario=?, accion_fecha=? WHERE sol_id=? "; 

            $consulta = $this->db->query($sql, array($fechaCheckIn, $geoCheckIn, $usuario, $accion_fecha, $codigo_solicitud));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function SolObtenerDocumentosEnviarApp($tipo_persona)
    {        
        try 
        {
            $sql = "SELECT d.documento_id, d.documento_nombre FROM tipo_persona_documento tpd INNER JOIN documento d ON d.documento_id=tpd.documento_id INNER JOIN tipo_persona tp ON tp.tipo_persona_id=tpd.tipo_persona_id WHERE d.documento_id>0 AND d.documento_vigente=1 AND d.documento_enviar=1 AND d.documento_pdf IS NOT NULL AND tpd.tipo_persona_id=? ORDER BY d.documento_nombre ASC ";

            $consulta = $this->db->query($sql, array($tipo_persona));

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
    
    function SolObtenerDocumentosDigitalizarApp($tipo_persona)
    {        
        try 
        {
            $sql = "SELECT d.documento_id, d.documento_nombre FROM tipo_persona_documento tpd INNER JOIN documento d ON d.documento_id=tpd.documento_id INNER JOIN tipo_persona tp ON tp.tipo_persona_id=tpd.tipo_persona_id WHERE d.documento_id>0 AND d.documento_vigente=1 AND d.documento_enviar=0 AND tpd.tipo_persona_id=? ORDER BY d.documento_nombre ASC ";

            $consulta = $this->db->query($sql, array($tipo_persona));

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
    
    function SolObtenerDocumentosDigitalizarAppAux($tipo_persona)
    {        
        try 
        {
            $sql = "SELECT d.documento_id, d.documento_nombre FROM tipo_persona_documento tpd INNER JOIN documento d ON d.documento_id=tpd.documento_id INNER JOIN tipo_persona tp ON tp.tipo_persona_id=tpd.tipo_persona_id WHERE d.documento_vigente=1 AND tpd.tipo_persona_id=? ORDER BY d.documento_nombre ASC ";

            $consulta = $this->db->query($sql, array($tipo_persona));

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
    
    function VerificaDocumentosSolicitudDigitalizar($codigo_registro, $codigo_documento, $codigo_tipo_registro=6)
    {        
        try 
        {
            $sql = "SELECT scd.solicitud_credito_documento_pdf, CONCAT('sol_', sc.sol_id) as 'solicitud_carpeta' FROM solicitud_credito_documento scd, solicitud_credito sc WHERE sc.sol_id=scd.sol_id AND scd.sol_id=? AND scd.documento_id=? AND scd.sol_tipo_registro=? ORDER BY scd.solicitud_credito_documento_id DESC LIMIT 1 ";

            $consulta = $this->db->query($sql, array($codigo_registro, $codigo_documento, $codigo_tipo_registro));

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
    
    function VerificaSolicitudConsolidado($codigo_registro) 
    {        
        try 
        {
            $sql = "SELECT sol_id, sol_checkin, sol_checkin_geo, ejecutivo_id, sol_consolidado, sol_evaluacion, sol_primer_nombre, sol_segundo_nombre, sol_primer_apellido, sol_segundo_apellido, sol_codigo_rubro, sol_num_proceso FROM solicitud_credito WHERE sol_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_registro));

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
    
    function InsertarDocumentoSolicitud($codigo_registro, $documento_id, $prospecto_documento_pdf, $accion_usuario, $accion_fecha, $codigo_tipo_registro=6)
    {        
        try 
        {
            $sql = "INSERT INTO solicitud_credito_documento(sol_id, documento_id, solicitud_credito_documento_pdf, accion_usuario, accion_fecha, sol_tipo_registro) VALUES (?, ?, ?, ?, ?, ?) ";

            $consulta = $this->db->query($sql, array($codigo_registro, $documento_id, $prospecto_documento_pdf, $accion_usuario, $accion_fecha, $codigo_tipo_registro));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function setConsolidarSolicitud($estudio, $estudio_codigo, $geolocalizacion, $codigo_usuario, $accion_usuario, $accion_fecha, $codigo_registro)
    {        
        try 
        {
            $sql = "UPDATE solicitud_credito SET sol_estudio=?, sol_estudio_codigo=?, sol_observado_app=0, sol_estado=2, sol_consolidado=1, sol_consolidado_geo=?, sol_consolidado_fecha=?, sol_consolidado_usuario=?, accion_usuario=?, accion_fecha=? WHERE sol_id=? ";

            $consulta = $this->db->query($sql, array($estudio, $estudio_codigo, $geolocalizacion, $accion_fecha, $codigo_usuario, $accion_usuario, $accion_fecha, $codigo_registro));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerSolicitudesEjecutivoAll($codigo_ejecutivo) 
    {        
        try 
        {
            $sql = "SELECT sol_id, ejecutivo_id, sol_consolidado, sol_codigo_rubro, sol_estado FROM solicitud_credito WHERE sol_estado>0 AND ejecutivo_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo));

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
    
    function CalculoRubrosSolEjecutivo($codigo_ejecutivo) 
    {        
        try 
        {
            $sql = "SELECT COUNT(c.camp_id) as 'contador', c.camp_nombre, c.camp_color
                    FROM solicitud_credito sc 
                    INNER JOIN campana c ON c.camp_id=sc.sol_codigo_rubro
                    WHERE sc.sol_estado>0 AND sc.sol_codigo_rubro<>-1 AND sc.ejecutivo_id=?
                    GROUP BY c.camp_id"; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo));

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
    
    function CalculoRubrosSolEjecutivo_Aux($codigo_ejecutivo) 
    {        
        try 
        {
            $sql = "SELECT (SELECT COUNT(sc.sol_id) FROM solicitud_credito sc WHERE sc.sol_estado>0 AND sc.ejecutivo_id=?) as 'contador', c.camp_nombre, c.camp_color
                    FROM campana c WHERE c.camp_id=6;"; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo));

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
    
    function CalculoSolEtapasEjecutivo($codigo_ejecutivo) 
    {        
        try 
        {
            $sql = "SELECT COUNT(e.etapa_id) AS 'contador', e.etapa_nombre, e.etapa_color
                    FROM solicitud_credito sc
                    INNER JOIN etapa e ON e.etapa_id = CASE sc.sol_estado WHEN 1 THEN 19 WHEN 2 THEN 20 WHEN 3 THEN 21 END
                    WHERE sc.sol_estado>0 AND sc.ejecutivo_id=? GROUP BY e.etapa_id ORDER BY e.etapa_orden"; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo));

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
    
    function getDatosEjecutivo($codigo_ejecutivo)
    {        
        try 
        {
            $sql = "SELECT CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) AS 'usuario_nombre', u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email, u.usuario_telefono, r.rol_id, r.rol_nombre, pa.perfil_app_id, pa.perfil_app_nombre, er.estructura_regional_id, er.estructura_regional_nombre, e.*
                    FROM ejecutivo e
                    INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                    INNER JOIN rol r ON r.rol_id=u.usuario_rol
                    INNER JOIN perfil_app pa ON pa.perfil_app_id=r.perfil_app_id
                    INNER JOIN estructura_agencia ea ON ea.estructura_agencia_id=u.estructura_agencia_id
                    INNER JOIN estructura_regional er ON er.estructura_regional_id=ea.estructura_agencia_id
                    WHERE e.ejecutivo_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo));

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
    
    function SolListaDocumentosDigitalizar($codigo_registro, $codigo_documento, $codigo_tipo_registro=6)
    {        
        try 
        {
            $sql = "SELECT scd.solicitud_credito_documento_id, scd.solicitud_credito_documento_pdf, CONCAT('sol_', sc.sol_id) as 'prospecto_carpeta', scd.accion_fecha
                    FROM solicitud_credito_documento scd
                    INNER JOIN solicitud_credito sc ON sc.sol_id=scd.sol_id
                    WHERE sc.sol_id=? AND scd.documento_id=? AND scd.sol_tipo_registro=? ORDER BY scd.solicitud_credito_documento_id DESC ";

            $consulta = $this->db->query($sql, array($codigo_registro, $codigo_documento, $codigo_tipo_registro));

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
    
    function SolObtenerDocumentoDigitalizar($codigo_registro, $codigo_documento, $codigo_tipo_registro=6)
    {        
        try 
        {
            $sql = "SELECT scd.solicitud_credito_documento_pdf, CONCAT('sol_', sc.sol_id) as 'prospecto_carpeta'
                    FROM solicitud_credito_documento scd
                    INNER JOIN solicitud_credito sc ON sc.sol_id=scd.sol_id
                    WHERE sc.sol_id=? AND scd.solicitud_credito_documento_id=? AND scd.sol_tipo_registro=? ORDER BY scd.solicitud_credito_documento_id DESC LIMIT 1 ";

            $consulta = $this->db->query($sql, array($codigo_registro, $codigo_documento, $codigo_tipo_registro));

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
    
    function SolUpdateObservacionDoc($obs_estado, $accion_usuario, $accion_fecha, $codigo_registro, $codigo_tipo_registro)
    {        
        try 
        {
            $sql = "UPDATE sol_observacion_documento SET obs_estado=?, accion_usuario=?, accion_fecha=? WHERE codigo_registro=? AND codigo_tipo_registro=? "; 
            
            $consulta = $this->db->query($sql, array($obs_estado, $accion_usuario, $accion_fecha, $codigo_registro, $codigo_tipo_registro));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function SolObtenerObsProspectos($codigo_registro, $estado, $codigo_tipo_registro) 
    {        
        try 
        {
            $sql = "SELECT so.obs_id, so.codigo_registro, so.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app) as 'usuario_nombre', so.documento_id, d.documento_nombre, so.obs_tipo, so.obs_fecha, so.obs_detalle, so.obs_estado, so.accion_usuario, so.accion_fecha
                    FROM sol_observacion_documento so
                    INNER JOIN usuarios u ON u.usuario_id=so.usuario_id
                    INNER JOIN documento d ON d.documento_id=so.documento_id
                    WHERE so.codigo_registro=? AND so.obs_estado=? AND so.codigo_tipo_registro=? "; 

            $consulta = $this->db->query($sql, array($codigo_registro, $estado, $codigo_tipo_registro));

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
    
    function SolHistorialObservacionesDoc($codigo_registro, $codigo_tipo_registro)
    {        
        try 
        {
            $sql = "SELECT so.obs_id, so.codigo_registro, so.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) AS 'usuario_nombre', so.documento_id, d.documento_nombre, so.obs_tipo, so.obs_fecha, so.obs_detalle, so.obs_estado, so.accion_usuario, so.accion_fecha
                    FROM sol_observacion_documento so
                    INNER JOIN usuarios u ON u.usuario_id=so.usuario_id
                    INNER JOIN documento d ON d.documento_id=so.documento_id
                    WHERE so.codigo_registro=? AND so.codigo_tipo_registro=? ORDER BY so.obs_fecha ASC ";
            
            $consulta = $this->db->query($sql, array($codigo_registro, $codigo_tipo_registro));
            
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
    
    function SolVerDocumentoObservado($codigo_registro, $codigo_documento, $codigo_tipo_registro)
    {        
        try 
        {
            $sql = "SELECT obs_id FROM sol_observacion_documento WHERE codigo_registro=? AND documento_id=? AND codigo_tipo_registro=? AND obs_estado=1 "; 

            $consulta = $this->db->query($sql, array($codigo_registro, $codigo_documento, $codigo_tipo_registro));

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
    
    function ObservarDocSolicitud($accion_usuario, $accion_fecha, $codigo_registro)
    {        
        try 
        {
            $sql = "UPDATE solicitud_credito SET sol_estado=1, sol_consolidado=0, sol_observado_app=1, accion_usuario=?, accion_fecha=? WHERE sol_id=? "; 
            
            $consulta = $this->db->query($sql, array($accion_usuario, $accion_fecha, $codigo_registro));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function SolInsertarObservacionDoc($registro_id, $codigo_tipo_registro, $usuario_id, $documento_id, $obs_tipo, $obs_fecha, $obs_detalle, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO sol_observacion_documento(codigo_registro, codigo_tipo_registro, usuario_id, documento_id, obs_tipo, obs_fecha, obs_detalle, accion_usuario, accion_fecha, obs_estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($registro_id, $codigo_tipo_registro, $usuario_id, $documento_id, $obs_tipo, $obs_fecha, $obs_detalle, $accion_usuario, $accion_fecha, 1));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerZonas_solcred($aux_criterio, $aux_flag)
    {        
        try 
        {
            if($aux_flag == 0)
            {
                $aux_flag = "zon.catalogo_descripcion";
            }
            else
            {
                $aux_flag = "CONCAT(ciu.catalogo_descripcion, ' - ', zon.catalogo_descripcion) as 'catalogo_descripcion'";
            }
            
            $sql = "SELECT DISTINCT 'dir_barrio_zona_uv' as 'catalogo_tipo_codigo', zon.catalogo_codigo, " . $aux_flag . "
                    FROM catalogo dep
                    INNER JOIN catalogo prov ON prov.catalogo_parent=dep.catalogo_codigo
                    INNER JOIN catalogo ciu ON ciu.catalogo_parent=prov.catalogo_codigo
                    INNER JOIN catalogo zon on zon.catalogo_parent=ciu.catalogo_codigo
                    WHERE ciu.catalogo_tipo_codigo='dir_localidad_ciudad' 
                    AND prov.catalogo_tipo_codigo='dir_provincia' 
                    AND dep.catalogo_tipo_codigo='dir_departamento'
                    " . $aux_criterio . " ORDER BY catalogo_descripcion ASC"; 

            $consulta = $this->db->query($sql, array($codigo_registro, $codigo_documento, $codigo_tipo_registro));

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
    
    function ObtenerDatosRegional_solcred($codigo=1, $parent_codigo=-1, $parent_tipo=-1)
    {        
        try 
        {
            $filtro = '';
            
            if($parent_codigo != -1 && $parent_tipo != -1)
            {
                switch ($parent_tipo) {
                    case 'dir_departamento':
                        $filtro = " AND estructura_regional_departamento='$parent_codigo' AND estructura_regional_ciudad<>115";
                        break;
                    case 'dir_provincia':
                        $filtro = " AND estructura_regional_provincia='$parent_codigo'";
                        break;
                    case 'dir_localidad_ciudad':
                        $filtro = " AND estructura_regional_ciudad='$parent_codigo'";
                        break;

                    default:
                        break;
                }
            }
            
            $sql = "SELECT r.estructura_regional_id, r.estructura_entidad_id AS 'parent_id', e.estructura_entidad_nombre AS 'parent_detalle', r.estructura_regional_nombre, r.estructura_regional_direccion, r.estructura_regional_departamento, r.estructura_regional_provincia, r.estructura_regional_ciudad, r.estructura_regional_geo, r.estructura_regional_firma, r.estructura_regional_estado, r.accion_usuario, r.accion_fecha FROM estructura_regional r INNER JOIN estructura_entidad e ON e.estructura_entidad_id=r.estructura_entidad_id WHERE estructura_regional_estado=? $filtro "; 

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function get_Dep_Pro_Ciu_from_zon($codigo_zona)
    {
        try
        {
            $sql = "SELECT DISTINCT dep.catalogo_codigo as 'dir_departamento', prov.catalogo_codigo as 'dir_provincia', ciu.catalogo_codigo as 'dir_localidad_ciudad', zon.catalogo_codigo as 'dir_barrio_zona_uv'
                    FROM catalogo dep
                    INNER JOIN catalogo prov ON prov.catalogo_parent=dep.catalogo_codigo
                    INNER JOIN catalogo ciu ON ciu.catalogo_parent=prov.catalogo_codigo
                    INNER JOIN catalogo zon on zon.catalogo_parent=ciu.catalogo_codigo
                    WHERE zon.catalogo_tipo_codigo='dir_barrio_zona_uv' 
                    AND ciu.catalogo_tipo_codigo='dir_localidad_ciudad' 
                    AND prov.catalogo_tipo_codigo='dir_provincia' 
                    AND dep.catalogo_tipo_codigo='dir_departamento'
                    AND zon.catalogo_codigo=? ";

            $consulta = $this->db->query($sql, array((int)$codigo_zona));

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
    
    function ObtenerTipoRegistro($filto=' AND tipo_persona_id IN (1,2,3,4,7,8,9,10,11,12)')
    {        
        try 
        {
            $sql = "SELECT tipo_persona_id, tipo_persona_nombre FROM tipo_persona WHERE categoria_persona_id=1 AND tipo_persona_vigente=1 $filto ";          

            $consulta = $this->db->query($sql, array(1));

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
    
    // Guardar Paso Siguiente del formulario
    function Guardar_PasoActual($siguiente_vista, $accion_usuario, $accion_fecha, $codigo_registro)
    {        
        try 
        {            
            $sql = "UPDATE solicitud_credito SET
                
                        sol_ultimo_paso = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE sol_id = ?";

            $this->db->query($sql, array($siguiente_vista, $accion_usuario, $accion_fecha, $codigo_registro));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function update_sol_datos_personales(
                        
                        $sol_codigo_rubro,
                        $sol_ci,
                        $sol_complemento,
                        $sol_extension,
                        $sol_primer_nombre,
                        $sol_segundo_nombre,
                        $sol_primer_apellido,
                        $sol_segundo_apellido,
                        $sol_correo,
                        $sol_cel,
                        $sol_telefono,
                        $sol_fec_nac,
                        $sol_est_civil,
                        $sol_nit,
                        $sol_cliente,
                        $sol_conyugue,
                        $sol_dependencia,
                        $sol_indepen_actividad,
                        $sol_indepen_telefono,
                        $sol_indepen_ant_ano,
                        $sol_indepen_ant_mes,
                        $sol_indepen_horario_desde,
                        $sol_indepen_horario_hasta,
                        $sol_indepen_horario_dias,
                        $sol_depen_empresa,
                        $sol_depen_actividad,
                        $sol_depen_cargo,
                        $sol_depen_tipo_contrato,
                        $sol_depen_telefono,
                        $sol_depen_ant_ano,
                        $sol_depen_ant_mes,
                        $sol_depen_horario_desde,
                        $sol_depen_horario_hasta,
                        $sol_depen_horario_dias,
            
                        // Actividad Secundaria
                        $sol_actividad_secundaria,
                        $sol_codigo_rubro_sec,
                        $sol_dependencia_sec,
                        $sol_indepen_actividad_sec,
                        $sol_indepen_telefono_sec,
                        $sol_indepen_ant_ano_sec,
                        $sol_indepen_ant_mes_sec,
                        $sol_indepen_horario_desde_sec,
                        $sol_indepen_horario_hasta_sec,
                        $sol_indepen_horario_dias_sec,
                        $sol_depen_empresa_sec,
                        $sol_depen_actividad_sec,
                        $sol_depen_cargo_sec,
                        $sol_depen_tipo_contrato_sec,
                        $sol_depen_telefono_sec,
                        $sol_depen_ant_ano_sec,
                        $sol_depen_ant_mes_sec,
                        $sol_depen_horario_desde_sec,
                        $sol_depen_horario_hasta_sec,
                        $sol_depen_horario_dias_sec,
            
                        $accion_usuario,
                        $accion_fecha,
                        $estructura_id
                        )
    {
        try 
        {            
            $sql = "UPDATE solicitud_credito SET
                
                        sol_codigo_rubro = ?,
                        sol_ci = ?,
                        sol_complemento = ?,
                        sol_extension = ?,
                        sol_primer_nombre = ?,
                        sol_segundo_nombre = ?,
                        sol_primer_apellido = ?,
                        sol_segundo_apellido = ?,
                        sol_correo = ?,
                        sol_cel = ?,
                        sol_telefono = ?,
                        sol_fec_nac = ?,
                        sol_est_civil = ?,
                        sol_nit = ?,
                        sol_cliente = ?,
                        sol_conyugue = ?,
                        sol_dependencia = ?,
                        sol_indepen_actividad = ?,
                        sol_indepen_telefono = ?,
                        sol_indepen_ant_ano = ?,
                        sol_indepen_ant_mes = ?,
                        sol_indepen_horario_desde = ?,
                        sol_indepen_horario_hasta = ?,
                        sol_indepen_horario_dias = ?,
                        sol_depen_empresa = ?,
                        sol_depen_actividad = ?,
                        sol_depen_cargo = ?,
                        sol_depen_tipo_contrato = ?,
                        sol_depen_telefono = ?,
                        sol_depen_ant_ano = ?,
                        sol_depen_ant_mes = ?,
                        sol_depen_horario_desde = ?,
                        sol_depen_horario_hasta = ?,
                        sol_depen_horario_dias = ?,
                        
                        sol_actividad_secundaria = ?,
                        sol_codigo_rubro_sec = ?,
                        sol_dependencia_sec = ?,
                        sol_indepen_actividad_sec = ?,
                        sol_indepen_telefono_sec = ?,
                        sol_indepen_ant_ano_sec = ?,
                        sol_indepen_ant_mes_sec = ?,
                        sol_indepen_horario_desde_sec = ?,
                        sol_indepen_horario_hasta_sec = ?,
                        sol_indepen_horario_dias_sec = ?,
                        sol_depen_empresa_sec = ?,
                        sol_depen_actividad_sec = ?,
                        sol_depen_cargo_sec = ?,
                        sol_depen_tipo_contrato_sec = ?,
                        sol_depen_telefono_sec = ?,
                        sol_depen_ant_ano_sec = ?,
                        sol_depen_ant_mes_sec = ?,
                        sol_depen_horario_desde_sec = ?,
                        sol_depen_horario_hasta_sec = ?,
                        sol_depen_horario_dias_sec = ?,

                        sol_estudio_actividad = ?,
                        sol_evaluacion = ?,
                        sol_trabajo_actividad_pertenece = ?,
                        sol_con_trabajo_actividad_pertenece = ?,

                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE sol_id = ?";

            $this->db->query($sql, array(
                        
                        $sol_codigo_rubro,
                        $sol_ci,
                        $sol_complemento,
                        $sol_extension,
                        $sol_primer_nombre,
                        $sol_segundo_nombre,
                        $sol_primer_apellido,
                        $sol_segundo_apellido,
                        $sol_correo,
                        $sol_cel,
                        $sol_telefono,
                        $sol_fec_nac,
                        $sol_est_civil,
                        $sol_nit,
                        $sol_cliente,
                        $sol_conyugue,
                        $sol_dependencia,
                        $sol_indepen_actividad,
                        $sol_indepen_telefono,
                        $sol_indepen_ant_ano,
                        $sol_indepen_ant_mes,
                        $sol_indepen_horario_desde,
                        $sol_indepen_horario_hasta,
                        $sol_indepen_horario_dias,
                        $sol_depen_empresa,
                        $sol_depen_actividad,
                        $sol_depen_cargo,
                        $sol_depen_tipo_contrato,
                        $sol_depen_telefono,
                        $sol_depen_ant_ano,
                        $sol_depen_ant_mes,
                        $sol_depen_horario_desde,
                        $sol_depen_horario_hasta,
                        $sol_depen_horario_dias,
                
                        // Actividad Secundaria
                        $sol_actividad_secundaria,
                        $sol_codigo_rubro_sec,
                        $sol_dependencia_sec,
                        $sol_indepen_actividad_sec,
                        $sol_indepen_telefono_sec,
                        $sol_indepen_ant_ano_sec,
                        $sol_indepen_ant_mes_sec,
                        $sol_indepen_horario_desde_sec,
                        $sol_indepen_horario_hasta_sec,
                        $sol_indepen_horario_dias_sec,
                        $sol_depen_empresa_sec,
                        $sol_depen_actividad_sec,
                        $sol_depen_cargo_sec,
                        $sol_depen_tipo_contrato_sec,
                        $sol_depen_telefono_sec,
                        $sol_depen_ant_ano_sec,
                        $sol_depen_ant_mes_sec,
                        $sol_depen_horario_desde_sec,
                        $sol_depen_horario_hasta_sec,
                        $sol_depen_horario_dias_sec,
                
                        1,
                        0,
                        0,
                        0,
                
                        $accion_usuario,
                        $accion_fecha,
                        $estructura_id
                        ));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function update_sol_con_datos_personales(
                        
                        $sol_con_ci,
                        $sol_con_complemento,
                        $sol_con_extension,
                        $sol_con_primer_nombre,
                        $sol_con_segundo_nombre,
                        $sol_con_primer_apellido,
                        $sol_con_segundo_apellido,
                        $sol_con_correo,
                        $sol_con_cel,
                        $sol_con_telefono,
                        $sol_con_fec_nac,
                        $sol_con_est_civil,
                        $sol_con_nit,
                        $sol_con_cliente,
                        $sol_con_dependencia,
                        $sol_con_indepen_actividad,
                        $sol_con_indepen_telefono,
                        $sol_con_indepen_ant_ano,
                        $sol_con_indepen_ant_mes,
                        $sol_con_indepen_horario_desde,
                        $sol_con_indepen_horario_hasta,
                        $sol_con_indepen_horario_dias,
                        $sol_con_depen_empresa,
                        $sol_con_depen_actividad,
                        $sol_con_depen_cargo,
                        $sol_con_depen_tipo_contrato,
                        $sol_con_depen_telefono,
                        $sol_con_depen_ant_ano,
                        $sol_con_depen_ant_mes,
                        $sol_con_depen_horario_desde,
                        $sol_con_depen_horario_hasta,
                        $sol_con_depen_horario_dias,
            
                        $accion_usuario,
                        $accion_fecha,
                        $estructura_id
                        )
    {
        try 
        {            
            $sql = "UPDATE solicitud_credito SET
                
                        sol_con_ci = ?,
                        sol_con_complemento = ?,
                        sol_con_extension = ?,
                        sol_con_primer_nombre = ?,
                        sol_con_segundo_nombre = ?,
                        sol_con_primer_apellido = ?,
                        sol_con_segundo_apellido = ?,
                        sol_con_correo = ?,
                        sol_con_cel = ?,
                        sol_con_telefono = ?,
                        sol_con_fec_nac = ?,
                        sol_con_est_civil = ?,
                        sol_con_nit = ?,
                        sol_con_cliente = ?,
                        sol_con_dependencia = ?,
                        sol_con_indepen_actividad = ?,
                        sol_con_indepen_telefono = ?,
                        sol_con_indepen_ant_ano = ?,
                        sol_con_indepen_ant_mes = ?,
                        sol_con_indepen_horario_desde = ?,
                        sol_con_indepen_horario_hasta = ?,
                        sol_con_indepen_horario_dias = ?,
                        sol_con_depen_empresa = ?,
                        sol_con_depen_actividad = ?,
                        sol_con_depen_cargo = ?,
                        sol_con_depen_tipo_contrato = ?,
                        sol_con_depen_telefono = ?,
                        sol_con_depen_ant_ano = ?,
                        sol_con_depen_ant_mes = ?,
                        sol_con_depen_horario_desde = ?,
                        sol_con_depen_horario_hasta = ?,
                        sol_con_depen_horario_dias = ?,
                        
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE sol_id = ?";

            $this->db->query($sql, array(
                        
                        $sol_con_ci,
                        $sol_con_complemento,
                        $sol_con_extension,
                        $sol_con_primer_nombre,
                        $sol_con_segundo_nombre,
                        $sol_con_primer_apellido,
                        $sol_con_segundo_apellido,
                        $sol_con_correo,
                        $sol_con_cel,
                        $sol_con_telefono,
                        $sol_con_fec_nac,
                        $sol_con_est_civil,
                        $sol_con_nit,
                        $sol_con_cliente,
                        $sol_con_dependencia,
                        $sol_con_indepen_actividad,
                        $sol_con_indepen_telefono,
                        $sol_con_indepen_ant_ano,
                        $sol_con_indepen_ant_mes,
                        $sol_con_indepen_horario_desde,
                        $sol_con_indepen_horario_hasta,
                        $sol_con_indepen_horario_dias,
                        $sol_con_depen_empresa,
                        $sol_con_depen_actividad,
                        $sol_con_depen_cargo,
                        $sol_con_depen_tipo_contrato,
                        $sol_con_depen_telefono,
                        $sol_con_depen_ant_ano,
                        $sol_con_depen_ant_mes,
                        $sol_con_depen_horario_desde,
                        $sol_con_depen_horario_hasta,
                        $sol_con_depen_horario_dias,
                
                        $accion_usuario,
                        $accion_fecha,
                        $estructura_id
                        ));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function update_sol_credito_solicitado(
                        
                        $sol_monto,
                        $sol_plazo,
                        $sol_moneda,
                        $sol_detalle,
            
                        $accion_usuario,
                        $accion_fecha,
                        $estructura_id
                        )
    {
        try 
        {            
            $sql = "UPDATE solicitud_credito SET
                
                        sol_monto = ?,
                        sol_plazo = ?,
                        sol_moneda = ?,
                        sol_detalle = ?,
                        
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE sol_id = ?";

            $this->db->query($sql, array(
                        
                        $sol_monto,
                        $sol_plazo,
                        $sol_moneda,
                        $sol_detalle,
                
                        $accion_usuario,
                        $accion_fecha,
                        $estructura_id
                        ));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function update_sol_direccion(
                        
                        $sol_dir_departamento,
                        $sol_dir_provincia,
                        $sol_dir_localidad_ciudad,
                        $sol_cod_barrio,
                        $sol_direccion_trabajo,
                        $sol_edificio_trabajo,
                        $sol_numero_trabajo,
                        $sol_trabajo_lugar,
                        $sol_trabajo_lugar_otro,
                        $sol_trabajo_realiza,
                        $sol_trabajo_realiza_otro,
                        $sol_dir_referencia,
                        $sol_geolocalizacion,
                        $sol_croquis,
                        $sol_dir_departamento_dom,
                        $sol_dir_provincia_dom,
                        $sol_dir_localidad_ciudad_dom,
                        $sol_cod_barrio_dom,
                        $sol_direccion_dom,
                        $sol_edificio_dom,
                        $sol_numero_dom,
                        $sol_dom_tipo,
                        $sol_dom_tipo_otro,
                        $sol_dir_referencia_dom,
                        $sol_geolocalizacion_dom,
                        $sol_croquis_dom,
            
                        // Actividad Secundaria
                        $sol_dir_departamento_sec,
                        $sol_dir_provincia_sec,
                        $sol_dir_localidad_ciudad_sec,
                        $sol_cod_barrio_sec,
                        $sol_direccion_trabajo_sec,
                        $sol_edificio_trabajo_sec,
                        $sol_numero_trabajo_sec,
                        $sol_trabajo_lugar_sec,
                        $sol_trabajo_lugar_otro_sec,
                        $sol_trabajo_realiza_sec,
                        $sol_trabajo_realiza_otro_sec,
                        $sol_dir_referencia_sec,
                        $sol_geolocalizacion_sec,
                        $sol_croquis_sec,
            
                        $accion_usuario,
                        $accion_fecha,
                        $estructura_id
                        )
    {
        try 
        {            
            $sql = "UPDATE solicitud_credito SET
                
                        sol_dir_departamento = ?,
                        sol_dir_provincia = ?,
                        sol_dir_localidad_ciudad = ?,
                        sol_cod_barrio = ?,
                        sol_direccion_trabajo = ?,
                        sol_edificio_trabajo = ?,
                        sol_numero_trabajo = ?,
                        sol_trabajo_lugar = ?,
                        sol_trabajo_lugar_otro = ?,
                        sol_trabajo_realiza = ?,
                        sol_trabajo_realiza_otro = ?,
                        sol_dir_referencia = ?,
                        sol_geolocalizacion = ?,
                        sol_croquis = ?,
                        sol_dir_departamento_dom = ?,
                        sol_dir_provincia_dom = ?,
                        sol_dir_localidad_ciudad_dom = ?,
                        sol_cod_barrio_dom = ?,
                        sol_direccion_dom = ?,
                        sol_edificio_dom = ?,
                        sol_numero_dom = ?,
                        sol_dom_tipo = ?,
                        sol_dom_tipo_otro = ?,
                        sol_dir_referencia_dom = ?,
                        sol_geolocalizacion_dom = ?,
                        sol_croquis_dom = ?,
                        
                        sol_dir_departamento_sec = ?,
                        sol_dir_provincia_sec = ?,
                        sol_dir_localidad_ciudad_sec = ?,
                        sol_cod_barrio_sec = ?,
                        sol_direccion_trabajo_sec = ?,
                        sol_edificio_trabajo_sec = ?,
                        sol_numero_trabajo_sec = ?,
                        sol_trabajo_lugar_sec = ?,
                        sol_trabajo_lugar_otro_sec = ?,
                        sol_trabajo_realiza_sec = ?,
                        sol_trabajo_realiza_otro_sec = ?,
                        sol_dir_referencia_sec = ?,
                        sol_geolocalizacion_sec = ?,
                        sol_croquis_sec = ?,

                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE sol_id = ?";

            $this->db->query($sql, array(
                        
                        $sol_dir_departamento,
                        $sol_dir_provincia,
                        $sol_dir_localidad_ciudad,
                        $sol_cod_barrio,
                        $sol_direccion_trabajo,
                        $sol_edificio_trabajo,
                        $sol_numero_trabajo,
                        $sol_trabajo_lugar,
                        $sol_trabajo_lugar_otro,
                        $sol_trabajo_realiza,
                        $sol_trabajo_realiza_otro,
                        $sol_dir_referencia,
                        $sol_geolocalizacion,
                        $sol_croquis,
                        $sol_dir_departamento_dom,
                        $sol_dir_provincia_dom,
                        $sol_dir_localidad_ciudad_dom,
                        $sol_cod_barrio_dom,
                        $sol_direccion_dom,
                        $sol_edificio_dom,
                        $sol_numero_dom,
                        $sol_dom_tipo,
                        $sol_dom_tipo_otro,
                        $sol_dir_referencia_dom,
                        $sol_geolocalizacion_dom,
                        $sol_croquis_dom,
                
                        // Actividad Secundaria
                        $sol_dir_departamento_sec,
                        $sol_dir_provincia_sec,
                        $sol_dir_localidad_ciudad_sec,
                        $sol_cod_barrio_sec,
                        $sol_direccion_trabajo_sec,
                        $sol_edificio_trabajo_sec,
                        $sol_numero_trabajo_sec,
                        $sol_trabajo_lugar_sec,
                        $sol_trabajo_lugar_otro_sec,
                        $sol_trabajo_realiza_sec,
                        $sol_trabajo_realiza_otro_sec,
                        $sol_dir_referencia_sec,
                        $sol_geolocalizacion_sec,
                        $sol_croquis_sec,
                
                        $accion_usuario,
                        $accion_fecha,
                        $estructura_id
                        ));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function update_sol_con_direccion(
                        
                        $sol_con_dir_departamento,
                        $sol_con_dir_provincia,
                        $sol_con_dir_localidad_ciudad,
                        $sol_con_cod_barrio,
                        $sol_con_direccion_trabajo,
                        $sol_con_edificio_trabajo,
                        $sol_con_numero_trabajo,
                        $sol_con_trabajo_lugar,
                        $sol_con_trabajo_lugar_otro,
                        $sol_con_trabajo_realiza,
                        $sol_con_trabajo_realiza_otro,
                        $sol_con_dir_referencia,
                        $sol_con_geolocalizacion,
                        $sol_con_croquis,
                        $sol_con_dir_departamento_dom,
                        $sol_con_dir_provincia_dom,
                        $sol_con_dir_localidad_ciudad_dom,
                        $sol_con_cod_barrio_dom,
                        $sol_con_direccion_dom,
                        $sol_con_edificio_dom,
                        $sol_con_numero_dom,
                        $sol_con_dom_tipo,
                        $sol_con_dom_tipo_otro,
                        $sol_con_dir_referencia_dom,
                        $sol_con_geolocalizacion_dom,
                        $sol_con_croquis_dom,
            
                        $accion_usuario,
                        $accion_fecha,
                        $estructura_id
                        )
    {
        try 
        {            
            $sql = "UPDATE solicitud_credito SET
                
                        sol_con_dir_departamento = ?,
                        sol_con_dir_provincia = ?,
                        sol_con_dir_localidad_ciudad = ?,
                        sol_con_cod_barrio = ?,
                        sol_con_direccion_trabajo = ?,
                        sol_con_edificio_trabajo = ?,
                        sol_con_numero_trabajo = ?,
                        sol_con_trabajo_lugar = ?,
                        sol_con_trabajo_lugar_otro = ?,
                        sol_con_trabajo_realiza = ?,
                        sol_con_trabajo_realiza_otro = ?,
                        sol_con_dir_referencia = ?,
                        sol_con_geolocalizacion = ?,
                        sol_con_croquis = ?,
                        sol_con_dir_departamento_dom = ?,
                        sol_con_dir_provincia_dom = ?,
                        sol_con_dir_localidad_ciudad_dom = ?,
                        sol_con_cod_barrio_dom = ?,
                        sol_con_direccion_dom = ?,
                        sol_con_edificio_dom = ?,
                        sol_con_numero_dom = ?,
                        sol_con_dom_tipo = ?,
                        sol_con_dom_tipo_otro = ?,
                        sol_con_dir_referencia_dom = ?,
                        sol_con_geolocalizacion_dom = ?,
                        sol_con_croquis_dom = ?,
                        
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE sol_id = ?";

            $this->db->query($sql, array(
                        
                        $sol_con_dir_departamento,
                        $sol_con_dir_provincia,
                        $sol_con_dir_localidad_ciudad,
                        $sol_con_cod_barrio,
                        $sol_con_direccion_trabajo,
                        $sol_con_edificio_trabajo,
                        $sol_con_numero_trabajo,
                        $sol_con_trabajo_lugar,
                        $sol_con_trabajo_lugar_otro,
                        $sol_con_trabajo_realiza,
                        $sol_con_trabajo_realiza_otro,
                        $sol_con_dir_referencia,
                        $sol_con_geolocalizacion,
                        $sol_con_croquis,
                        $sol_con_dir_departamento_dom,
                        $sol_con_dir_provincia_dom,
                        $sol_con_dir_localidad_ciudad_dom,
                        $sol_con_cod_barrio_dom,
                        $sol_con_direccion_dom,
                        $sol_con_edificio_dom,
                        $sol_con_numero_dom,
                        $sol_con_dom_tipo,
                        $sol_con_dom_tipo_otro,
                        $sol_con_dir_referencia_dom,
                        $sol_con_geolocalizacion_dom,
                        $sol_con_croquis_dom,
                
                        $accion_usuario,
                        $accion_fecha,
                        $estructura_id
                        ));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function SolLimpiarDatosConyuge($codigo_registro)
    {
        try 
        {            
            $sql = "UPDATE solicitud_credito SET
                
                        sol_con_ci = '',
                        sol_con_extension = '',
                        sol_con_complemento = '',
                        sol_con_primer_nombre = '',
                        sol_con_segundo_nombre = '',
                        sol_con_primer_apellido = '',
                        sol_con_segundo_apellido = '',
                        sol_con_correo = '',
                        sol_con_cel = '',
                        sol_con_telefono = '',
                        sol_con_fec_nac = '1900-01-01',
                        sol_con_est_civil = '',
                        sol_con_nit = '',
                        sol_con_cliente = 0,
                        sol_con_dependencia = 0,
                        sol_con_indepen_actividad = '',
                        sol_con_indepen_ant_ano = 0,
                        sol_con_indepen_ant_mes = 0,
                        sol_con_indepen_horario_desde = '00:00:00',
                        sol_con_indepen_horario_hasta = '00:00:00',
                        sol_con_indepen_horario_dias = '',
                        sol_con_indepen_telefono = '',
                        sol_con_depen_empresa = '',
                        sol_con_depen_actividad = '',
                        sol_con_depen_cargo = '',
                        sol_con_depen_ant_ano = 0,
                        sol_con_depen_ant_mes = 0,
                        sol_con_depen_horario_desde = '00:00:00',
                        sol_con_depen_horario_hasta = '00:00:00',
                        sol_con_depen_horario_dias = '',
                        sol_con_depen_telefono = '',
                        sol_con_depen_tipo_contrato = '',
                        sol_con_dir_departamento = '',
                        sol_con_dir_provincia = '',
                        sol_con_dir_localidad_ciudad = '',
                        sol_con_cod_barrio = '',
                        sol_con_direccion_trabajo = '',
                        sol_con_edificio_trabajo = '',
                        sol_con_numero_trabajo = '',
                        sol_con_dir_referencia = 0,
                        sol_con_geolocalizacion = '',
                        sol_con_geolocalizacion_img = '',
                        sol_con_croquis = '',
                        sol_con_trabajo_lugar = 0,
                        sol_con_trabajo_lugar_otro = '',
                        sol_con_trabajo_realiza = 0,
                        sol_con_trabajo_realiza_otro = '',
                        sol_con_dir_departamento_dom = '',
                        sol_con_dir_provincia_dom = '',
                        sol_con_dir_localidad_ciudad_dom = '',
                        sol_con_cod_barrio_dom = '',
                        sol_con_direccion_dom = '',
                        sol_con_edificio_dom = '',
                        sol_con_numero_dom = '',
                        sol_con_dir_referencia_dom = 0,
                        sol_con_geolocalizacion_dom = '',
                        sol_con_geolocalizacion_dom_img = '',
                        sol_con_croquis_dom = '',
                        sol_con_dom_tipo = 0,
                        sol_con_dom_tipo_otro = ''
                    
                    WHERE sol_id = ?";

            $this->db->query($sql, array($codigo_registro));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function SolLimpiarDatosActSecundaria($codigo_registro)
    {
        try 
        {            
            $sql = "UPDATE solicitud_credito SET
                
                        sol_dir_departamento_sec = '',
                        sol_dir_provincia_sec = '',
                        sol_dir_localidad_ciudad_sec = '',
                        sol_cod_barrio_sec = '',
                        sol_direccion_trabajo_sec = '',
                        sol_edificio_trabajo_sec = '',
                        sol_numero_trabajo_sec = '',
                        sol_trabajo_lugar_sec = 0,
                        sol_trabajo_lugar_otro_sec = '',
                        sol_trabajo_realiza_sec = 0,
                        sol_trabajo_realiza_otro_sec = '',
                        sol_dir_referencia_sec = 0,
                        sol_geolocalizacion_sec = '',
                        sol_croquis_sec = ''
                    
                    WHERE sol_id = ?";

            $this->db->query($sql, array($codigo_registro));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function SolCopyGeoSolCon($codigo_registro)
    {
        try 
        {            
            $sql = "UPDATE solicitud_credito SET
                
                        sol_con_geolocalizacion=sol_geolocalizacion,
                        sol_con_geolocalizacion_dom=sol_geolocalizacion_dom
                        
                    WHERE sol_id = ?";

            $this->db->query($sql, array($codigo_registro));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function SolFinFormulario(
                        $codigo_registro,
                        $accion_usuario,
                        $accion_fecha
                        )
    {
        try 
        {            
            $sql = "UPDATE solicitud_credito SET
                
                        sol_registro_completado_fecha = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE sol_id = ?";

            $this->db->query($sql, array(
                        $accion_fecha,
                        $accion_usuario,
                        $accion_fecha,
                        $codigo_registro
                        ));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function NuevoSolCred(
                        $ejecutivo_id,
                        $afiliador_id,
                        $sol_asistencia,
                        $codigo_agencia_fie,

                        $sol_ci,
                        $sol_complemento,
                        $sol_extension,
                        $sol_primer_nombre,
                        $sol_segundo_nombre,
                        $sol_primer_apellido,
                        $sol_segundo_apellido,
                        $sol_correo,
                        $sol_cel,
                        $sol_telefono,

                        $codigo_usuario,
                        $accion_usuario,
                        $accion_fecha
                        )
    {
        try 
        {            
            $sql = "INSERT INTO solicitud_credito(

                        ejecutivo_id,
                        afiliador_id,
                        sol_asistencia,
                        codigo_agencia_fie,

                        sol_ci,
                        sol_complemento,
                        sol_extension,
                        sol_primer_nombre,
                        sol_segundo_nombre,
                        sol_primer_apellido,
                        sol_segundo_apellido,
                        sol_correo,
                        sol_cel,
                        sol_telefono,

                        sol_croquis,
                        sol_con_croquis,
                        sol_croquis_sec,

                        sol_estado,
                        sol_fecha,
                        sol_asignado,
                        sol_asignado_usuario,
                        sol_asignado_fecha,
                        accion_usuario,
                        accion_fecha
                        ) VALUES(

                        ?,
                        ?,
                        ?,
                        ?,

                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,

                        ?,
                        ?,
                        ?,

                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?
                        )";

            $this->db->query($sql, array(
                        $ejecutivo_id,
                        $afiliador_id,
                        $sol_asistencia,
                        $codigo_agencia_fie,

                        $sol_ci,
                        $sol_complemento,
                        $sol_extension,
                        $sol_primer_nombre,
                        $sol_segundo_nombre,
                        $sol_primer_apellido,
                        $sol_segundo_apellido,
                        $sol_correo,
                        $sol_cel,
                        $sol_telefono,

                        '',
                        '',
                        '',
                
                        1,
                        $accion_fecha,
                        1,
                        $codigo_usuario,
                        $accion_fecha,
                        $accion_usuario,
                        $accion_fecha
                        ));
            
            $listaResultados = $this->db->insert_id();
            return $listaResultados;
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ListadoSolCred_GEOs($codigo_registro)
    {        
        try
        {
            $sql = "SELECT sol_id, sol_checkin_geo, sol_geolocalizacion_sec, sol_dir_departamento_sec, sol_geolocalizacion, sol_geolocalizacion_dom, sol_con_geolocalizacion, sol_con_geolocalizacion_dom, sol_dir_departamento, sol_dir_departamento_dom, sol_con_dir_departamento, sol_con_dir_departamento_dom FROM solicitud_credito WHERE sol_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_registro));
            
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
    
    function Sol_Eval_Rechazar($texto_justificacion, $accion_usuario, $accion_fecha, $codigo_registro)
    {
        try 
        {            
            $sql = "UPDATE solicitud_credito SET
                
                        sol_evaluacion=2, sol_trabajo_actividad_pertenece=0, sol_con_trabajo_actividad_pertenece=0, sol_rechazado_texto=?, accion_usuario=?, accion_fecha=?
                        
                    WHERE sol_id = ?";

            $this->db->query($sql, array($texto_justificacion, $accion_usuario, $accion_fecha, $codigo_registro));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function Sol_Eval_Aprobar($flag_convertir_actividad, $sol_trabajo_actividad_pertenece, $sol_con_trabajo_actividad_pertenece, $accion_usuario, $accion_fecha, $codigo_registro)
    {
        try 
        {            
            $sql = "UPDATE solicitud_credito SET
                
                        sol_evaluacion=1, sol_estudio_actividad=?, sol_trabajo_actividad_pertenece=?, sol_con_trabajo_actividad_pertenece=?, accion_usuario=?, accion_fecha=?
                        
                    WHERE sol_id = ?";

            $this->db->query($sql, array($flag_convertir_actividad, $sol_trabajo_actividad_pertenece, $sol_con_trabajo_actividad_pertenece, $accion_usuario, $accion_fecha, $codigo_registro));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Nuevo Lead
    
    function NuevoLead_Sol(
                            $codigo_agencia_fie,
                            $codigo_rubro,
                            $ejecutivo_id,
                            $general_solicitante,
                            $general_telefono,
                            $general_email,
                            $general_direccion,
                            $general_actividad,
                            $general_destino,
                            $general_ci,
                            $general_ci_extension,
                            $general_interes,
                            $operacion_efectivo,
                            $operacion_dias,
                            $accion_usuario,
                            $accion_fecha,
                            $general_comentarios,
                            $operacion_antiguedad_ano,
                            $operacion_antiguedad_mes,
                            $prospecto_num_proceso
                            )
    {        
        try 
        {            
            $sql = "INSERT INTO prospecto
                
                        (codigo_agencia_fie,
                        camp_id, 
                        tipo_persona_id, 
                        empresa_id,
                        general_categoria,
                        ejecutivo_id,
                        general_depende, 
                        prospecto_principal,
                        general_solicitante,
                        general_telefono,
                        general_email,
                        general_direccion,
                        general_actividad,
                        general_destino,
                        general_ci,
                        general_ci_extension,
                        general_interes,
                        operacion_efectivo,
                        operacion_dias,
                        accion_usuario,
                        accion_fecha,
                        prospecto_fecha_asignacion,
                        general_comentarios,
                        operacion_antiguedad_ano,
                        operacion_antiguedad_mes,
                        prospecto_num_proceso)
                        
                    VALUES
                    
                        (?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?
                        )";

            $this->db->query($sql, array(
                            $codigo_agencia_fie,
                            $codigo_rubro,
                            $codigo_rubro,
                            -1,
                            1,
                            $ejecutivo_id,
                            -1,
                            1,
                            $general_solicitante,
                            $general_telefono,
                            $general_email,
                            $general_direccion,
                            $general_actividad,
                            $general_destino,
                            $general_ci,
                            $general_ci_extension,
                            $general_interes,
                            $operacion_efectivo,
                            $operacion_dias,
                            $accion_usuario,
                            $accion_fecha,
                            $accion_fecha,
                            $general_comentarios,
                            $operacion_antiguedad_ano,
                            $operacion_antiguedad_mes,
                            $prospecto_num_proceso
                            ));
            
            $listaResultados = $this->db->insert_id();
            
            return $listaResultados;
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

    }
    
    function NuevoFamiliar_Sol(
                            $codigo_rubro,
                            $ejecutivo_id,
                            $general_solicitante,
                            $general_telefono,
                            $general_email,
                            $general_direccion,
                            $general_actividad,
                            $general_ci,
                            $general_ci_extension,
                            $accion_usuario,
                            $accion_fecha,
                            $operacion_con_antiguedad_ano,
                            $operacion_con_antiguedad_mes,
                            $estructura_id
                            )
    {        
        try 
        {            
            $sql = "INSERT INTO prospecto
                
                        (camp_id, 
                        tipo_persona_id, 
                        empresa_id,
                        general_categoria,
                        ejecutivo_id,
                        general_depende, 
                        general_solicitante,
                        general_telefono,
                        general_email,
                        general_direccion,
                        general_actividad,
                        general_ci,
                        general_ci_extension,
                        accion_usuario,
                        accion_fecha,
                        prospecto_fecha_asignacion,
                        operacion_antiguedad_ano,
                        operacion_antiguedad_mes)
                        
                    VALUES
                    
                        (?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?
                        )";

            $this->db->query($sql, array(
                            $codigo_rubro,
                            0,
                            -1,
                            2,
                            $ejecutivo_id,
                            $estructura_id,
                            $general_solicitante,
                            $general_telefono,
                            $general_email,
                            $general_direccion,
                            $general_actividad,
                            $general_ci,
                            $general_ci_extension,
                            $accion_usuario,
                            $accion_fecha,
                            $accion_fecha,
                            $operacion_con_antiguedad_ano,
                            $operacion_con_antiguedad_mes,
                            ));
            
            $listaResultados = $this->db->insert_id();
            
            return $listaResultados;
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

    }
    
    function ClearOldKeys($codigo_registro)
    {
        try 
        {
            // Eliminar registros antiguos, restar al último ID 100 y borrar los que sean menor o igual a ese valor
            $sql = "DELETE FROM registro_keys WHERE key_id<=? ";
            $this->db->query($sql, array((int)$codigo_registro - 500));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function update_sol_img_mapas($sol_geolocalizacion_img, $sol_geolocalizacion_dom_img, $sol_con_geolocalizacion_img, $sol_con_geolocalizacion_dom_img, $codigo_registro)
    {
        try 
        {            
            $sql = "UPDATE solicitud_credito SET
                
                        sol_geolocalizacion_img=?, sol_geolocalizacion_dom_img=?, sol_con_geolocalizacion_img=?, sol_con_geolocalizacion_dom_img=?
                        
                    WHERE sol_id = ?";

            $this->db->query($sql, array($sol_geolocalizacion_img, $sol_geolocalizacion_dom_img, $sol_con_geolocalizacion_img, $sol_con_geolocalizacion_dom_img, $codigo_registro));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function update_sol_img_mapas_single($codigo_registro, $aux_campo, $img_base64)
    {
        try {
            
            $sql = "UPDATE solicitud_credito SET
                
                        " . $aux_campo . "=?
                        
                    WHERE sol_id = ?";

            $this->db->query($sql, array($img_base64, $codigo_registro));

            return TRUE;
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function update_erase_img_mapas_all($codigo_registro)
    {
        try {
            
            $sql = "UPDATE solicitud_credito SET
                
                        sol_geolocalizacion_img='', 
                        sol_geolocalizacion_dom_img='', 
                        sol_con_geolocalizacion_img='', 
                        sol_con_geolocalizacion_dom_img='',
                        sol_geolocalizacion_img_sec=''
                        
                    WHERE sol_id = ?";

            $this->db->query($sql, array($codigo_registro));

            return TRUE;
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function AuxPDF_croquis($codigo_registro, $aux_campo, $aux_ref, $documento_pdf_base64)
    {
        try {
            
            // Convertir a JPG
            $imagick = new Imagick();
            $imagick->readImageBlob(base64_decode($documento_pdf_base64));
            $imagick->setImageFormat("jpeg");
            $imageBlob = $imagick->getImageBlob();
            $imagick->clear();

            $result_imagen = base64_encode($imageBlob);
            $result_imagen = 'data:image/jpeg;base64,' . $result_imagen;
            
            $sql = "UPDATE solicitud_credito SET
                
                        " . $aux_ref . "=?,
                        " . $aux_campo . "=?
                        
                    WHERE sol_id = ?";

            $this->db->query($sql, array(2, $result_imagen, $codigo_registro));

            return TRUE;
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function getEnlaceRegistroAux($key_key)
    {        
        try 
        {
            // Paso 1: Se busca el registro por la Llave y se captura el valor
            
            $sql1 = "SELECT key_id, key_key, estructura_id, codigo_ejecutivo, tipo_registro FROM registro_keys WHERE key_key=? ";
            $consulta1 = $this->db->query($sql1, array($key_key));
            
            $listaResultados = $consulta1->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function UpdateTransfSolCred($codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id, $codigo_registro)
    {        
        try 
        {
            $this->load->model('mfunciones_generales');
            $this->load->model('mfunciones_logica');
            
            // Se verifica el ID de Ejecutivo del usuario
            $arrVerifica = $this->mfunciones_logica->VerificarUsuarioEjecutivo($codigo_usuario);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrVerifica);
            
            $sql1 = "UPDATE solicitud_credito SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? AND sol_id=?";
            $consulta1 = $this->db->query($sql1, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id, $codigo_registro));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    /*************** SOLICITUD DE CREDITOS - FIN ****************************/
    
    /*************** ESTUDIO DE CREDITO - INICIO ****************************/
    
    function update_JDA_Eval($codigo_prospecto,  $prospecto_jda_eval, $prospecto_jda_eval_texto, $prospecto_jda_eval_usuario, $accion_usuario, $accion_fecha)
    {
        try {
            $sql = "UPDATE prospecto SET
                        prospecto_jda_eval=?,
                        prospecto_jda_eval_texto=?,
                        prospecto_jda_eval_usuario=?,
                        prospecto_jda_eval_fecha=?,
                        accion_usuario=?,
                        accion_fecha=?                        
                    WHERE prospecto_id = ?";
            $this->db->query($sql, array( $prospecto_jda_eval, $prospecto_jda_eval_texto, $prospecto_jda_eval_usuario, $accion_fecha, $accion_usuario, $accion_fecha, $codigo_prospecto));

            return TRUE;
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function update_JDA_Eval_Sol($codigo_sol, $registro_num_proceso, $sol_jda_eval, $sol_jda_eval_texto, $sol_jda_eval_usuario, $accion_usuario, $accion_fecha)
    {
        try {
            
            $sql = "UPDATE solicitud_credito SET
                
                        sol_num_proceso=?,
                        sol_jda_eval=?,
                        sol_jda_eval_texto=?,
                        sol_jda_eval_usuario=?,
                        sol_jda_eval_fecha=?,
                        accion_usuario=?,
                        accion_fecha=?
                        
                    WHERE sol_id = ?";

            $this->db->query($sql, array($registro_num_proceso, $sol_jda_eval, $sol_jda_eval_texto, $sol_jda_eval_usuario, $accion_fecha, $accion_usuario, $accion_fecha, $codigo_sol));

            return TRUE;
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function update_DesembCOBIS($codigo_prospecto,  $codigo_usuario, $accion_usuario, $accion_fecha)
    {
        try {
            
            $sql = "UPDATE prospecto SET
                        prospecto_desembolso=?,
                        prospecto_desembolso_usuario=?,
                        prospecto_desembolso_fecha=?,
                        accion_usuario=?,
                        accion_fecha=?
                        
                    WHERE prospecto_id = ?";

            $this->db->query(   $sql, array(1, $codigo_usuario, $accion_fecha, $accion_usuario, $accion_fecha, $codigo_prospecto));

            return TRUE;
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function update_DesembCOBIS_Sol($codigo_sol, $sol_desembolso_monto, $codigo_usuario, $accion_usuario, $accion_fecha)
    {
        try {
            
            $sql = "UPDATE solicitud_credito SET
                
                        sol_desembolso=?,
                        sol_desembolso_monto=?,
                        sol_desembolso_usuario=?,
                        sol_desembolso_fecha=?,
                        accion_usuario=?,
                        accion_fecha=?
                        
                    WHERE sol_id = ?";

            $this->db->query($sql, array(1, $sol_desembolso_monto, $codigo_usuario, $accion_fecha, $accion_usuario, $accion_fecha, $codigo_sol));

            return TRUE;
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function BandejaSupervisionLeads($lista_region=-1, $filtro='')
    {
        if($lista_region != -1)
        {
            $condicion .= " AND sc.codigo_agencia_fie IN ($lista_region)";
        }
        
        try 
        {
            // Paso 1: Se busca el registro por la Llave y se captura el valor
            
            $sql1 = "SELECT sc.sol_id, sc.sol_num_proceso, tp.tipo_persona_id, tp.tipo_persona_nombre, e.ejecutivo_id, e.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) as 'ejecutivo_nombre', sc.sol_registro_completado_fecha, sc.sol_primer_nombre, sc.sol_segundo_nombre, sc.sol_primer_apellido, sc.sol_segundo_apellido, sc.sol_ci, sc.sol_complemento, sc.sol_extension, sc.sol_dependencia, sc.sol_depen_empresa, sc.sol_indepen_actividad, sc.sol_evaluacion, sc.sol_rechazado_texto, sc.sol_moneda, sc.sol_monto, sc.sol_detalle, sc.sol_consolidado_fecha, sc.sol_jda_eval, sc.sol_jda_eval_usuario, sc.sol_jda_eval_fecha, sc.sol_jda_eval_texto
                    FROM solicitud_credito sc
                    INNER JOIN ejecutivo e ON e.ejecutivo_id=sc.ejecutivo_id
                    INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                    INNER JOIN tipo_persona tp ON tp.tipo_persona_id=sc.sol_codigo_rubro
                    WHERE sc.sol_consolidado=1 AND sc.sol_estudio=0 AND sc.sol_jda_eval IN(0,1) AND tp.tipo_persona_id IN(7,8,9,10,11,12) AND sc.sol_desembolso=0 " . $condicion . $filtro;
            $consulta1 = $this->db->query($sql1, array());
            
            $listaResultados = $consulta1->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function ListadoDetalleProspectoAux($codigo_prospecto)
    {
        try 
        {
            $sql = "SELECT camp.camp_id, camp.camp_nombre, camp.camp_plazo, camp.camp_monto_oferta, camp.camp_tasa, p.prospecto_evaluacion, p.prospecto_id, p.ejecutivo_id, u.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) as 'ejecutivo_nombre', e.ejecutivo_perfil_tipo, p.tipo_persona_id, p.empresa_id, p.prospecto_fecha_asignacion, p.prospecto_carpeta, p.prospecto_etapa, et.etapa_nombre, p.prospecto_etapa_fecha, p.prospecto_checkin, p.prospecto_checkin_fecha, p.prospecto_checkin_geo, p.prospecto_consolidar_fecha, p.prospecto_consolidar_geo, p.prospecto_consolidado, p.prospecto_observado_app, p.prospecto_estado_actual, c.cal_visita_ini, c.cal_visita_fin, p.prospecto_etapa_fecha as 'fecha_derivada_etapa', p.prospecto_observado, 
                    p.general_categoria, p.general_depende, p.general_solicitante, p.general_ci, p.general_ci_extension, p.general_telefono, p.general_email, p.general_direccion, p.general_actividad, p.general_destino, p.general_interes, p.prospecto_jda_eval, p.prospecto_jda_eval_texto, p.prospecto_jda_eval_usuario, p.prospecto_jda_eval_fecha, p.prospecto_desembolso, p.prospecto_desembolso_monto, p.prospecto_desembolso_usuario, p.prospecto_desembolso_fecha, p.prospecto_num_proceso
                    FROM prospecto p
                    INNER JOIN campana camp ON camp.camp_id=p.camp_id
                    INNER JOIN ejecutivo e ON e.ejecutivo_id=p.ejecutivo_id
                    INNER JOIN usuarios u ON u.usuario_id= e.usuario_id
                    INNER JOIN empresa emp ON emp.empresa_id=p.empresa_id
                    INNER JOIN calendario c ON c.cal_id_visita=p.prospecto_id AND c.cal_tipo_visita=1
                    INNER JOIN etapa et ON et.etapa_id=prospecto_etapa
                    WHERE p.prospecto_id=? LIMIT 1";
            
            $consulta = $this->db->query($sql, array($codigo_prospecto));
            
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
    
    function update_NroOperacion($tipo, $codigo_registro, $numero_operacion, $accion_usuario, $accion_fecha, $prospecto_desembolso_monto="")
    {        
        try 
        {
            if($tipo == 'numero_operacion')
            {
                $sql = "UPDATE prospecto SET prospecto_num_proceso=?, accion_usuario=?, accion_fecha=?, prospecto_desembolso_monto=? WHERE prospecto_id=? ";
                $consulta = $this->db->query($sql, array((int)$numero_operacion, $accion_usuario, $accion_fecha,$prospecto_desembolso_monto,$codigo_registro));
            }
            else
            {
                $sql = "UPDATE solicitud_credito SET sol_num_proceso=?, accion_usuario=?, accion_fecha=? WHERE sol_id=? ";
                $consulta = $this->db->query($sql, array((int)$numero_operacion, $accion_usuario, $accion_fecha, $codigo_registro));
            }

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    /*************** ESTUDIO DE CREDITOS - FIN ****************************/
    
    // Perfil Tipo Categoría "B"
    function getTipoPerfilEjecutivo($codigo_ejecutivo, $codigo_perfil)
    {
        try 
        {
            $sql = "SELECT ejecutivo_id FROM ejecutivo WHERE ejecutivo_id=? AND ejecutivo_perfil_tipo=?";
            
            $consulta = $this->db->query($sql, array($codigo_ejecutivo, $codigo_perfil));
            
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
    
    function setPerfilEjecutivo($codigo_ejecutivo, $codigo_perfil, $accion_usuario, $accion_fecha)
    {
        try {
            
            $sql = "UPDATE ejecutivo SET
                
                        ejecutivo_perfil_tipo=?,
                        accion_usuario=?,
                        accion_fecha=?
                        
                    WHERE ejecutivo_id = ?";

            $this->db->query($sql, array($codigo_perfil, $accion_usuario, $accion_fecha, $codigo_ejecutivo));

            return TRUE;
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerAgenciasFiltrado($region_id)
    {        
        try 
        {
            $sql = "SELECT r.estructura_regional_id, r.estructura_entidad_id AS 'parent_id', e.estructura_entidad_nombre AS 'parent_detalle', r.estructura_regional_nombre, r.estructura_regional_estado FROM estructura_regional r INNER JOIN estructura_entidad e ON e.estructura_entidad_id=r.estructura_entidad_id WHERE r.estructura_regional_id IN (" . $region_id . ") "; 

            $consulta = $this->db->query($sql, array(1));

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
    
    function setSolAgenciaAsociada($codigo_registro, $codigo_agencia, $accion_usuario, $accion_fecha)
    {
        try {
            
            $sql = "UPDATE solicitud_credito SET
                
                        codigo_agencia_fie=?,
                        accion_usuario=?,
                        accion_fecha=?
                        
                    WHERE sol_id = ?";

            $this->db->query($sql, array($codigo_agencia, $accion_usuario, $accion_fecha, $codigo_registro));

            return TRUE;
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
/*************** DATABASE - FIN ****************************/

}
    
?>