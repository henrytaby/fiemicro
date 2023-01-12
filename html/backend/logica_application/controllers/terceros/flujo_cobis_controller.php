<?php
/**
 * @file 
 * Codigo que implementa el controlador para la gestión de Prospectos
 * @brief BANDEJA DE LA INSTANCIA
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para la gestión de Prospectos - BACKOFFICE
 * @brief BANDEJA DE LA INSTANCIA
 * @class Conf_catalogo_controller 
 */
class Flujo_cobis_controller extends CI_Controller {
        
    function __construct() {
        parent::__construct();
    }
    
    /**
     * @brief FLUJO COBIS
     * @author JRAD
     * @date 2021
     */
    
    public function Aprobar_FlujoCobis() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('encrypt');
        
        $logger = new CI_Log();
        
        if(!isset($_POST['ortsiger_ogidoc']))
        {
            $logger->write_log('error', 'Error flujo COBIS - ' . $this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            $codigo_solicitud = $this->encrypt->decode($this->input->post('ortsiger_ogidoc', TRUE));
            $codigo_solicitud = (int)preg_replace('/[^0-9]/s', '', $codigo_solicitud);

            $codigo_usuario = $this->input->post('codigo_usuario');
            $accion_usuario = $this->input->post('accion_usuario');
            
            $arrTercero = $this->mfunciones_logica->ObtenerDetalleSolicitudTerceros($codigo_solicitud);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrTercero);

            if(!$arrTercero)
            {
                $logger->write_log('error', 'Error flujo COBIS - El código de registro ' . $codigo_solicitud . ' no existe.');
                exit();
            }
            
            if((int)$arrTercero[0]["f_cobis_actual_etapa"] == 99)
            {
                // Fin del flujo. No continuar.
                $logger->write_log('error', 'Error flujo COBIS - El usuario ' . $accion_usuario . ' reingresó a un flujo completado el registro ' . $codigo_solicitud . '.');
                exit();
            }
            
            try {

                // Revisar todos los campos, remplazar espacio en blanco por vacío
                foreach ($arrTercero[0] as $key_aux => $value_aux)
                {
                    if((string)$arrTercero[0][$key_aux] == ' ')
                    {
                        $arrTercero[0][$key_aux] = '';
                    }
                    $arrTercero[0][$key_aux] = htmlspecialchars($arrTercero[0][$key_aux]);
                }
                
                // Registrar el ingreso al flujo        -1=Inicio Flujo     -2=Reingreso
                $this->mfunciones_generales->FlujoCOBIS_marcar_etapa($codigo_solicitud, ($arrTercero[0]["f_cobis_tracking"]=='' ? -1 : -2), $codigo_usuario, 1);
                
                // Obtener parámetros de configuración
                $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
                
                // VALIDACIÓN INICIAL: SÍ EL NÚMERO DE INTENTO ES "MAYOR O IGUAL" AL CONFIGURADO, SE DEBE MARCAR COMO RECHAZADO
                if((int)$arrTercero[0]["f_cobis_actual_intento"] >= $arrConf[0]['conf_f_cobis_intentos'])
                {
                    if((int)$arrTercero[0]["f_cobis_actual_etapa"]==5 || (int)$arrTercero[0]["f_cobis_actual_etapa"]==6)
                    {
                        // Si se encuentra en estado 5(Generación contrato PDF) o 6(Envío correo a cliente), se deriva a la bandeja de Onboarding Contrato
                        $aux_etapa = 23;
                        $aux_tipo_ex = 'com';
                    }
                    else
                    {
                        // Caso normal
                        $aux_etapa = 21;
                        $aux_tipo_ex = 'pen';
                    }
                    
                    // ++ Registra excepción ++
                    $this->mfunciones_generales->FlujoCOBIS_excepcion(
                        $aux_etapa, // <- Código etapa       20=Reintento   21=Derivado a Pendiente      22=Derivado a Rechazado
                        $arrTercero[0]["codigo_agencia_fie"], // <- Código agencia del registro
                        $aux_tipo_ex, // <- Tipo de excepción  rei=Reintento  pen=Pendiente rec=Rechazado
                        $codigo_solicitud, // <- Cod Sólicitud
                        $codigo_usuario, // <- Cod Usuario
                        'rev_operativa', // <- Motivo excepción del catálogo
                        'Límite intentos alcanzado.', // <- Motivo Texto personalizado
                        1, // <- Marcar Flag fin de flujo 0-1
                        $accion_usuario // <- User Usuario
                    );
                    exit();
                }
                
                $accion_fecha = date('Y-m-d H:i:s');
                
                if((int)$arrTercero[0]["terceros_estado"] != 1)
                {
                    // Sólo si el estado es diferencia a Aprobado (1) se procede a aprobar
                    $this->mfunciones_logica->AprobarSolicitudTerceros($codigo_usuario, $accion_usuario, $accion_fecha, $codigo_solicitud);

                    // FUNCION PARA OBTENER EL CÓDIGO DE PROSPECTO EN BASE AL CODIGO TERCERO
                    $codigo_prospecto = $this->mfunciones_generales->ObtenerProspectoTerceros($codigo_solicitud);

                    // Se registra para las Etapas Onboarding   Pasa a: Bandeja Onboarding Contrato   Etapa Nueva     Etapa Actual
                    $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 8, 7, $accion_usuario, $accion_fecha, 0);
                }

                // *** FLUJO COBIS - I N I C I O ***
                
                // Establecer el limite de timeout a 0 por precaución
                set_time_limit(0);
                
                // Inmediatamente que se ingresa al flujo, debe sumarse "1" a los intentos, de esa forma se registra el intento actual.
                $this->mfunciones_logica->PrepararFlujoCOBIS($codigo_solicitud);
                
                // Crear el objeto info_cliente
                $info_cliente = new stdClass();
                $info_cliente->codigo_solicitud = $codigo_solicitud;
                $info_cliente->codigo_agencia_fie = $arrTercero[0]["codigo_agencia_fie"];
                $info_cliente->f_cobis_codigo = $arrTercero[0]["f_cobis_codigo"];
                $info_cliente->crear_o_actualizar = ($arrTercero[0]["f_cobis_codigo"]=='' ? 1 : 2); // 1=Crear  2=Actualizar
                $info_cliente->onboarding_numero_cuenta = (string)$arrTercero[0]['onboarding_numero_cuenta'];
                
                $info_cliente->cI_numeroraiz = (string)$arrTercero[0]['cI_numeroraiz'];
                $info_cliente->cI_complemento = (string)$arrTercero[0]['cI_complemento'];
                $info_cliente->cI_lugar_emisionoextension = (string)$arrTercero[0]['cI_lugar_emisionoextension'];
                $info_cliente->di_fecha_nacimiento = $arrTercero[0]['di_fecha_nacimiento'];
                
                $info_cliente->di_indefinido = $arrTercero[0]['di_indefinido'];
                $info_cliente->di_fecha_vencimiento = $arrTercero[0]['di_fecha_vencimiento'];
                    if((int)$arrTercero[0]['di_indefinido'] == 1)
                    {   // Si el valor indefinidio es "1" se setea el valor por defecto
                        $info_cliente->di_fecha_vencimiento = '2100-12-31';
                    }
                
                $info_cliente->token = '';
                $info_cliente->codigo_usuario = $codigo_usuario;
                $info_cliente->accion_usuario = $accion_usuario;
                
                // Se captura la etapa actual
                $info_cliente->puntero_etapa_flujo = (int)$arrTercero[0]["f_cobis_actual_etapa"];
                
                // ++ ETAPA 1: Consulta de clientes (CI)
                if($info_cliente->puntero_etapa_flujo <= 1)
                {
                    // Aux. Registrar la etapa
                    $info_cliente->puntero_etapa_flujo = 1;
                    
                    $resultado = ''; // <- Inicializar vacío
                    $resultado = $this->Servicio_searchNaturalCustomers($arrTercero, $arrConf, $info_cliente);
                    if($resultado->error == true){ $logger->write_log('error', 'Error flujo COBIS Etapa1 -  ' . 'Registro: ' . $info_cliente->codigo_solicitud . ' ' . $resultado->ws_error_text); exit(); }
                    
                    $info_cliente->token = $resultado->token;
                    
                    $info_cliente->puntero_etapa_flujo = $resultado->puntero_etapa_flujo;
                    $info_cliente->f_cobis_codigo = $resultado->f_cobis_codigo;
                    $info_cliente->crear_o_actualizar = $resultado->crear_o_actualizar;
                }
                
                // ++ ETAPA 2: Consulta de clientes (Cod COBIS)
                if($info_cliente->puntero_etapa_flujo == 2)
                {
                    $resultado = ''; // <- Inicializar vacío
                    $resultado = $this->Servicio_searchNatural($arrTercero, $arrConf, $info_cliente);
                    if($resultado->error == true){ $logger->write_log('error', 'Error flujo COBIS Etapa2 -  ' . 'Registro: ' . $info_cliente->codigo_solicitud . ' ' . $resultado->ws_error_text); exit(); }
                    
                    $info_cliente->token = $resultado->token;
                    
                    $info_cliente->puntero_etapa_flujo = $resultado->puntero_etapa_flujo;
                }
                
                // ++ ETAPA 3: Creación / Actualización de clientes PN
                if($info_cliente->puntero_etapa_flujo == 3)
                {
                    $resultado = ''; // <- Inicializar vacío
                    $resultado = $this->Servicio_client($arrTercero, $arrConf, $info_cliente);
                    if($resultado->error == true){ $logger->write_log('error', 'Error flujo COBIS Etapa3 -  ' . 'Registro: ' . $info_cliente->codigo_solicitud . ' ' . $resultado->ws_error_text); exit(); }
                    
                    $info_cliente->token = $resultado->token;
                    $info_cliente->f_cobis_codigo = $resultado->f_cobis_codigo;
                    $info_cliente->puntero_etapa_flujo = $resultado->puntero_etapa_flujo;
                }
                
                // ++ ETAPA 4: Apertura de cuenta y Afiliación a Fienet
                if($info_cliente->puntero_etapa_flujo == 4)
                {
                    $resultado = ''; // <- Inicializar vacío
                    $resultado = $this->Servicio_accountAffiliation($arrTercero, $arrConf, $info_cliente);
                    if($resultado->error == true){ $logger->write_log('error', 'Error flujo COBIS Etapa4 -  ' . 'Registro: ' . $info_cliente->codigo_solicitud . ' ' . $resultado->ws_error_text); exit(); }
                    
                    $info_cliente->token = $resultado->token;
                    
                    $info_cliente->puntero_etapa_flujo = $resultado->puntero_etapa_flujo;
                }
                
                // ++ ETAPA 5: Generación contrato PDF
                if($info_cliente->puntero_etapa_flujo == 5)
                {
                    $this->mfunciones_generales->FlujoCOBIS_marcar_etapa($codigo_solicitud, $info_cliente->puntero_etapa_flujo, $codigo_usuario);
                    
                    // Se obtiene los datos trabajados del registro
                    $arrDatos = $this->mfunciones_generales->DatosTercerosEmail($codigo_solicitud);
                    
                    $contrato_generado = $this->mfunciones_generales->GeneraContrato_terceros($arrDatos, $info_cliente->accion_usuario, date('Y-m-d H:i:s'));
        
                    if($contrato_generado == FALSE)
                    {
                        // Excepcion, reintentar
                        $aux_error_text = 'PDF del contrato no generado, verifique datos y firma digitalizada (Proceso ' . $arrDatos[0]['tercero_asistencia_detalle'] . ').';
                        $logger->write_log('error', 'Error flujo COBIS Etapa5 -  ' . 'Registro: ' . $info_cliente->codigo_solicitud . ' ' . $aux_error_text);
                        $this->MarcarExcepcion($info_cliente, 'rei', $aux_error_text, '', 0);
                    }
                    
                    // Marcar tracking
                    $this->MarcarTracking($info_cliente, 'PDF del contrato generado y asociado al registro.');
                    
                    // Derivar a Envío correo a cliente
                    $info_cliente->puntero_etapa_flujo = 6;
                }
                
                // ++ ETAPA 6: Envío correo a cliente
                if($info_cliente->puntero_etapa_flujo == 6)
                {
                    // Registrar etapa
                    $this->mfunciones_generales->FlujoCOBIS_marcar_etapa($codigo_solicitud, $info_cliente->puntero_etapa_flujo, $codigo_usuario);
                    
                    try
                    {
                        // Se obtiene los datos trabajados del registro
                        $arrDatos = $this->mfunciones_generales->DatosTercerosEmail($codigo_solicitud);

                        // Armar listado de documentos a adjuntar

                        $lista_documentos = '';

                        $arrDocumentos = $this->mfunciones_logica->ObtenerDocumentosTercerosCriterio(1);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDocumentos);

                        if (!isset($arrDocumentos[0])) 
                        {
                            $lst_Documentos[0] = array();
                        }
                        else
                        {
                            $i = 0;
                            foreach ($arrDocumentos as $key_doc => $value_doc) 
                            {
                                $item_valor_doc = array(
                                    "documento_id" => $value_doc['documento_id']
                                );
                                $lst_Documentos[$i] = $item_valor_doc;

                                $i++;

                                $lista_documentos .= $value_doc['documento_id'] . ',';
                            }
                        }

                        $fecha_actual = date('Y-m-d H:i:s');

                        // Se registra la "etapa" COBIS
                        $this->mfunciones_logica->CompletarCOBISTerceros_fCOBIS($info_cliente->codigo_usuario, $info_cliente->accion_usuario, $fecha_actual, $codigo_solicitud);
                        
                        // Se marca el estado completado para derivar a la Bandeja Agencia
                        $this->mfunciones_logica->CompletarSolicitudTerceros(1, '', $lista_documentos, $info_cliente->codigo_usuario, $info_cliente->accion_usuario, $fecha_actual, $codigo_solicitud);

                        // Se envía el correo
                        $this->mfunciones_generales->EnviarCorreo('terceros_completado', $arrDatos[0]['terceros_email'], $arrDatos[0]['terceros_nombre'], $arrDatos, $lst_Documentos);

                        // Notificar Proceso Onboarding     0=No Regionalizado      1=Si Regionalizado
                        $this->mfunciones_generales->NotificacionEtapaTerceros($codigo_solicitud, 11, 1);

                        // FUNCION PARA OBTENER EL CÓDIGO DE PROSPECTO EN BASE AL CODIGO TERCERO
                        $codigo_prospecto = $this->mfunciones_generales->ObtenerProspectoTerceros($codigo_solicitud);

                        // Se registra para las Etapas Onboarding   Pasa a: Bandeja Onboarding Agencia   Etapa Nueva     Etapa Actual
                        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 9, 8, $info_cliente->accion_usuario, $fecha_actual, 0);
                        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 11, 9, $info_cliente->accion_usuario, $fecha_actual, 0);

                        // Marcar fin del flujo
                        $info_cliente->puntero_etapa_flujo = 99;
                        $this->MarcarTracking($info_cliente, '', '', 1);
                        $this->mfunciones_logica->setFlujoCOBIS_finalizar($codigo_solicitud, $info_cliente->codigo_usuario, $fecha_actual);
                    }
                    catch (Exception $exc) {
                        $this->mfunciones_generales->ExcepcionGenerica($info_cliente);
                    }
                }
                
                // *** FLUJO COBIS - F I N ***
                
            } catch (Exception $exc) {
                $logger->write_log('error', 'Error flujo COBIS - ' . $exc->getTraceAsString());
                
                // ++ Registra excepción ++
                $this->mfunciones_generales->FlujoCOBIS_excepcion(
                    20, // <- Código etapa       20=Reintento   21=Derivado a Pendiente      22=Derivado a Rechazado
                    $arrTercero[0]["codigo_agencia_fie"], // <- Código agencia del registro
                    'rei', // <- Tipo de excepción  rei=Reintento  pen=Pendiente rec=Rechazado
                    $codigo_solicitud, // <- Cod Sólicitud
                    $codigo_usuario, // <- Cod Usuario
                    'ws_error', // <- Motivo excepción del catálogo
                    htmlspecialchars('Excepción Genérica'), // <- Motivo Texto personalizado
                    0, // <- Marcar Flag fin de flujo 0-1
                    $accion_usuario // <- User Usuario
                );
                exit();
            }
        }
    }
    
    private function MarcarTracking($info_cliente, $texto='', $incluye_cod_cobis=0, $f_cobis_flag_rechazado=0)
    {
        // Registrar tracking
        $this->mfunciones_generales->FlujoCOBIS_tracking($info_cliente->codigo_solicitud, date('Y-m-d H:i:s'), $info_cliente->puntero_etapa_flujo, 0, '', htmlspecialchars($texto) . ($incluye_cod_cobis==1 ? ' (clientCode: ' . (string)$info_cliente->f_cobis_codigo . ').' : ''), $f_cobis_flag_rechazado);
    }
    
    private function MarcarExcepcion($info_cliente, $tipo_excepcion='pen', $texto_error='', $motivo='', $flag_fin=1)
    {
        switch ($tipo_excepcion) {
            case 'pen':
                $codigo_etapa = 21;
                $motivo_excepcion = 'rev_operativa';
                break;
            case 'rec':
                $codigo_etapa = 22;
                $motivo_excepcion = 'rech_cli_bloq';
                break;
            default:
                $codigo_etapa = 20;
                $motivo_excepcion = 'ws_error';
                break;
        }
        
        if($motivo != '')
        {
            $motivo_excepcion = $motivo;
        }
        
        $this->mfunciones_generales->FlujoCOBIS_excepcion(
            $codigo_etapa, // <- Código etapa       20=Reintento   21=Derivado a Pendiente      22=Derivado a Rechazado
            $info_cliente->codigo_agencia_fie, // <- Código agencia del registro
            $tipo_excepcion, // <- Tipo de excepción  rei=Reintento  pen=Pendiente rec=Rechazado
            $info_cliente->codigo_solicitud, // <- Cod Sólicitud
            $info_cliente->codigo_usuario, // <- Cod Usuario
            $motivo_excepcion, // <- Motivo excepción del catálogo
            htmlspecialchars($texto_error), // <- Motivo Texto personalizado
            (int)$flag_fin, // <- Marcar Flag fin de flujo 0-1
            $info_cliente->accion_usuario // <- User Usuario
        );
        exit();
    }
    
    private function gestionEtapaToken($info_cliente)
    {
        // Verifica primeramente que el flujo siga activo antes de registrar la etapa
        $aux = $this->mfunciones_logica->getFlujoCOBISActivo($info_cliente->codigo_solicitud);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($aux);
        if(!$aux) { exit(); }
        if((int)$aux[0]['f_cobis_flujo'] == 0) { exit(); }
        
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Registrar etapa del flujo
        $this->mfunciones_generales->FlujoCOBIS_marcar_etapa($info_cliente->codigo_solicitud, $info_cliente->puntero_etapa_flujo, $info_cliente->codigo_usuario);
        
        // Verificar si el token no existe
        if ($this->mfunciones_generales->TokenOnboarding_parametros($info_cliente->token) == FALSE) 
        {
            // Llamar a la función para generar el token
            $info_cliente->token = $this->mfunciones_generales->TokenOnboarding_generar($info_cliente->accion_usuario, $accion_fecha);
            
            // Genera nuevo token y Actualizar parametros con el CI
            $ws_token = $this->mfunciones_generales->JWT_SOA_FIE('', $info_cliente->accion_usuario, $accion_fecha, 0);
            $this->mfunciones_logica->token_update_aux($ws_token, json_encode(array("cI_numeroraiz" => $info_cliente->cI_numeroraiz)), '', $info_cliente->token);
        }
        
        return $info_cliente->token;
    }
    
    private function Servicio_searchNaturalCustomers($arrTercero, $arrConf, $info_cliente)
    {
        $resultado_soa_fie = new stdClass();
        $resultado_soa_fie->error = TRUE;
        $resultado_soa_fie->result = '';
        
        try {
            
            $parametros = array(
                    'type' => '8',
                    'mode' => '0',
                    'cedRuc' => $arrTercero[0]['cI_numeroraiz']
                );
            
            $info_cliente->token = $this->gestionEtapaToken($info_cliente);
            
            $resultado_soa_fie = $this->mfunciones_generales->Cliente_SOA_FIE_COBIS($info_cliente, $info_cliente->token, $arrConf[0]['conf_f_cobis_uri_cliente_cobis'], $parametros, $info_cliente->accion_usuario, date('Y-m-d H:i:s'));
            
            if($resultado_soa_fie->ws_error == TRUE)
            {
                $this->mfunciones_generales->ExcepcionGenerica($info_cliente);
            }
            
            // -- Parametros principales
            $info_cliente->crear_o_actualizar = 1;
            // --
            
            switch ($resultado_soa_fie->ws_httpcode) {
                case 409:

                    switch (str_replace(' ', '', (string) $resultado_soa_fie->ws_result['detail'][0]['code'])) {
                        case '101001':
                            // No existe el registro. Se debe crear el registro.
                            
                            // Registrar tracking
                            $this->MarcarTracking($info_cliente, 'Cliente no registrado en COBIS, crear registro.');
                            
                            $info_cliente->puntero_etapa_flujo = 3;
                            $info_cliente->crear_o_actualizar = 1;
                            
                            return $info_cliente;

                            break;

                        default:

                            // Excepción derivar a Pendiente. Otro error.
                            $this->MarcarExcepcion($info_cliente, 'pen', ' => code ' . $resultado_soa_fie->ws_result['detail'][0]['code'] . ': ' . $resultado_soa_fie->ws_result['detail'][0]['message']);
                            exit();

                            break;
                    }

                    break;

                case 200:
                    
                    // Validación de estructura
                    if (!isset($resultado_soa_fie->ws_result['naturalCustomers'])) {
                        // Excepcion derivar a Pendiente. Objeto naturalCustomers no esta presente.
                        $this->MarcarExcepcion($info_cliente, 'rei', 'Objeto [naturalCustomers] no presente', '', 0);
                        exit();
                    }

                    // 1. Validar ¿Más de un resultado o resultado con Doc. de Ident. distinto de CI?
                    if (count($resultado_soa_fie->ws_result['naturalCustomers']) > 1) {
                        // Excepcion derivar a Pendiente. Más de 1 resultado
                        $this->MarcarExcepcion($info_cliente, 'pen', 'CI "' . (string)$arrTercero[0]['cI_numeroraiz'] . '" obtuvo más de 1 resultado (' . count($resultado_soa_fie->ws_result['naturalCustomers']) . ' registros).');
                        exit();
                    }
                    else
                    {
                        // Sólo si es un valor y existe el parametro, registrar Código COBIS
                        if(isset($resultado_soa_fie->ws_result['naturalCustomers'][0]['clientCode']))
                        {
                            $info_cliente->f_cobis_codigo = (int)$resultado_soa_fie->ws_result['naturalCustomers'][0]['clientCode'];
                            
                            // Registrar tracking
                            $this->MarcarTracking($info_cliente, 'Cliente ya registrado en COBIS.', 1);
                            
                            // Registrar código COBIS en DB
                            $this->mfunciones_logica->setFlujoCOBIS_codigoCOBIS($info_cliente->codigo_solicitud, $info_cliente->f_cobis_codigo);
                        }
                    }

                    if (str_replace(' ', '', strtoupper($resultado_soa_fie->ws_result['naturalCustomers'][0]['idType'])) != 'CI') {
                        // Excepcion derivar a Pendiente. Doc diferente a CI.
                        $this->MarcarExcepcion($info_cliente, 'pen', 'Doc. diferente a CI (' . str_replace(' ', '', strtoupper($resultado_soa_fie->ws_result['naturalCustomers'][0]['idType'])) . '). clientCode: ' . $info_cliente->f_cobis_codigo . '.');
                        exit();
                    }

                    // 2. Validación de estados

                    switch ((string) str_replace(' ', '', strtoupper($resultado_soa_fie->ws_result['naturalCustomers'][0]['status']))) {
                        case 'B':
                            // Bloqueado
                            
                            // Excepcion derivar a Rechazado. Bloqueado
                            $this->MarcarExcepcion($info_cliente, 'rec', 'Cliente estado "Bloqueado". (clientCode: ' . (string)$info_cliente->f_cobis_codigo . ').');
                            exit();

                            break;

                        case 'E':
                            // Eliminado. Se debe crear el registro.
                            
                            // Registrar tracking
                            $this->MarcarTracking($info_cliente, 'Cliente estado "Eliminado", crear registro.', 1);

                            $info_cliente->puntero_etapa_flujo = 3;
                            $info_cliente->crear_o_actualizar = 1;

                            // Excepcionalmente se setea el codigo COBIS a vacío para que no se considere como Actualización del cliente
                            $info_cliente->f_cobis_codigo = '';
                            $this->mfunciones_logica->setFlujoCOBIS_codigoCOBIS($info_cliente->codigo_solicitud, $info_cliente->f_cobis_codigo);

                            return $info_cliente;

                            break;

                        case 'P':
                            // Prospecto. Se debe actualizar el registro.
                            
                            // Registrar tracking
                            $this->MarcarTracking($info_cliente, 'Cliente estado "Prospecto", actualizar registro.', 1);

                            $info_cliente->puntero_etapa_flujo = 3;
                            $info_cliente->crear_o_actualizar = 2;
                            
                            return $info_cliente;

                            break;

                        case 'A':
                            // Activo.

                            if ((int) $arrConf[0]['conf_f_cobis_procesa_activo'] == 0) { // Si el parámetro para procesar Cliente Activo es "0" se marca excepción
                                $this->MarcarExcepcion($info_cliente, 'pen', 'Cliente estado "Activo" no permitido. (clientCode: ' . (string)$info_cliente->f_cobis_codigo . ').');
                                exit();
                            } else {
                                
                                // Registrar tracking
                                $this->MarcarTracking($info_cliente, 'Cliente estado "Activo" sí permitido.', 1);
                                
                                // Si el parámetro para procesar Cliente Activo es diferente a "0" invocar servicio de Consulta Clientes
                                $info_cliente->puntero_etapa_flujo = 2;
                                return $info_cliente;
                            }

                            break;

                        default:
                            break;
                    }

                    break;


                default:

                    // Excepción Reintento
                    $this->mfunciones_generales->ExcepcionGenerica($info_cliente);
                    exit();

                    break;
            }
            
            return $info_cliente;
        }
        catch (Exception $exc) {
            $this->mfunciones_generales->ExcepcionGenerica($info_cliente);
        }
        
        return $resultado_soa_fie;
    }
    
    private function Servicio_searchNatural($arrTercero, $arrConf, $info_cliente)
    {
        $resultado_soa_fie = new stdClass();
        $resultado_soa_fie->error = TRUE;
        $resultado_soa_fie->result = '';
        
        try {
            
            $parametros = array(
                    'clientCode' => (int)$info_cliente->f_cobis_codigo,
                    'queryType' => 'P'
                );
            
            $info_cliente->token = $this->gestionEtapaToken($info_cliente);
            
            $resultado_soa_fie = $this->mfunciones_generales->Cliente_SOA_FIE_COBIS($info_cliente, $info_cliente->token, $arrConf[0]['conf_f_cobis_uri_cliente_ci'], $parametros, $info_cliente->accion_usuario, date('Y-m-d H:i:s'), 1);
            
            if($resultado_soa_fie->ws_error == TRUE)
            {
                $this->mfunciones_generales->ExcepcionGenerica($info_cliente);
            }
            
            // -- Parametros principales
            $resultado_soa_fie->puntero_etapa_flujo = $info_cliente->puntero_etapa_flujo;
            // --
            
            switch ($resultado_soa_fie->ws_httpcode) {
                case 409:

                    // Excepción derivar a Pendiente. Otro error.
                    $this->MarcarExcepcion($info_cliente, 'pen', 'clientCode: ' . $info_cliente->f_cobis_codigo . ' => code ' . $resultado_soa_fie->ws_result['detail'][0]['code'] . ': ' . $resultado_soa_fie->ws_result['detail'][0]['message']);
                    exit();

                    break;

                case 200:

                    // Validación de estructura
                    if (!isset($resultado_soa_fie->ws_result['naturalCustomerP'])) {
                        // Excepcion derivar a Pendiente. Objeto naturalCustomerP no esta presente.
                        $this->MarcarExcepcion($info_cliente, 'rei', 'Objeto [naturalCustomerP] no presente', '', 0);
                        exit();
                    }

                    // Validar cliente Activo y parametro permite procesar clientes activos
                    if (isset($resultado_soa_fie->ws_result['naturalCustomerP']['identification']['clientStatusId'])) {
                        if (strtoupper($resultado_soa_fie->ws_result['naturalCustomerP']['identification']['clientStatusId']) == 'A' && (int) $arrConf[0]['conf_f_cobis_procesa_activo'] == 0) {
                            $this->MarcarExcepcion($info_cliente, 'pen', 'Cliente estado "Activo" no permitido. (clientCode: ' . (string)$info_cliente->f_cobis_codigo . ').');
                            exit();
                        }
                    }

                    // 1. Validar Fecha vigencia CI COBIS

                    $today_dt = new DateTime(date('Y-m-d'));
                    // Se establece los rangos SEGIP
                    $rango_segip_start = new DateTime(SEGIP_VIGENCIA_FECHA_INI);
                    $rango_segip_end = new DateTime(SEGIP_VIGENCIA_FECHA_FIN);

                    // Se pregunta si la fecha de vencimiento está entre entre los rangos SEGIP
                    // True:  Asignar valor rango_segip_end
                    // False: Mantener valor registrado

                    $expirationDate = new DateTime($resultado_soa_fie->ws_result['naturalCustomerP']['identification']['expirationDate']);

                    if ($expirationDate >= $rango_segip_start && $expirationDate <= $rango_segip_end) {
                        $expirationDate = new DateTime($rango_segip_end->format('Y-m-d'));
                    }

                    // Vencimiento CI No puede ser fecha pasada
                    if ($today_dt > $expirationDate) {
                        $this->MarcarExcepcion($info_cliente, 'pen', 'Validación Fecha vigencia CI COBIS (' . $resultado_soa_fie->ws_result['naturalCustomerP']['identification']['expirationDate'] . '). clientCode: ' . $info_cliente->f_cobis_codigo . '.');
                        exit();
                    }

                    // 2. Validar Fecha de nacimiento COBIS sea igual a la registrada en Initium

                    $dateOfBirth = new DateTime($resultado_soa_fie->ws_result['naturalCustomerP']['identification']['dateOfBirth']);
                    $di_fecha_nacimiento = new DateTime($info_cliente->di_fecha_nacimiento);

                    if ($dateOfBirth->format('Y-m-d') !== $di_fecha_nacimiento->format('Y-m-d')) {
                        $this->MarcarExcepcion($info_cliente, 'pen', 'Validación Fecha de nacimiento COBIS (' . $resultado_soa_fie->ws_result['naturalCustomerP']['identification']['dateOfBirth'] . '). clientCode: ' . $info_cliente->f_cobis_codigo . '.');
                        exit();
                    }

                    // 3. Validar Coincidencia CI [N°+Comp+Ext. ó N°+Compl.]

                    $sw_coincide = 0;

                    $idNumber = $resultado_soa_fie->ws_result['naturalCustomerP']['identification']['idNumber'];

                    // N°+Comp+Ext
                    $sw_concat = implode('', array($info_cliente->cI_numeroraiz, $info_cliente->cI_complemento, $info_cliente->cI_lugar_emisionoextension));
                    if (strtoupper((string) str_replace(' ', '', $sw_concat)) === strtoupper((string) str_replace(' ', '', $idNumber))) {
                        $sw_coincide = 1;
                    }

                    // N°+Compl
                    $sw_concat = implode('', array($info_cliente->cI_numeroraiz, $info_cliente->cI_complemento));
                    if (strtoupper((string) str_replace(' ', '', $sw_concat)) === strtoupper((string) str_replace(' ', '', $idNumber))) {
                        $sw_coincide = 1;
                    }

                    if ($sw_coincide == 0) {
                        $this->MarcarExcepcion($info_cliente, 'pen', 'Validación Coincidencia CI COBIS (' . $resultado_soa_fie->ws_result['naturalCustomerP']['identification']['idNumber'] . '). clientCode: ' . $info_cliente->f_cobis_codigo . '.');
                        exit();
                    } else {
                        // Registrar tracking
                        $this->MarcarTracking($info_cliente, 'CI COBIS coincide.');
                        
                        // Marca la etapa para Apertura de cuenta
                        $info_cliente->puntero_etapa_flujo = 4;
                    }

                    break;

                default:

                    // Excepción Reintento
                    $this->MarcarExcepcion($info_cliente, 'rei');
                    exit();

                    break;
            }
            
            return $info_cliente;
        }
        catch (Exception $exc) {
            $this->mfunciones_generales->ExcepcionGenerica($info_cliente);
        }
        
        return $resultado_soa_fie;
    }
    
    private function Servicio_client($arrTercero, $arrConf, $info_cliente)
    {
        $resultado_soa_fie = new stdClass();
        $resultado_soa_fie->error = TRUE;
        $resultado_soa_fie->result = '';
        
        try {

            // ARMADO DE PARÁMETROS

            $info_cliente->token = $this->gestionEtapaToken($info_cliente);
            
            // Completar valor de Actividad Económica para el proceso No-Asistido debido a que sólo se registra Sectot y Actividad FIE
            $info_cliente->ae_actividad_fie = $arrTercero[0]['ae_actividad_fie'];

            if ((int) $arrTercero[0]['tercero_asistencia'] == 0) {
                $sec_sub_act = $this->mfunciones_generales->obtener_Sec_Sub_Act_from_fie($arrTercero[0]['ae_actividad_fie'], $arrTercero[0]['ae_sector_economico']);

                $info_cliente->ae_sector_economico = $sec_sub_act->ae_sector_economico;
                $info_cliente->ae_subsector_economico = $sec_sub_act->ae_subsector_economico;
                $info_cliente->ae_actividad_ocupacion = $sec_sub_act->ae_actividad_economica;
            } else {
                // Para proceso Asistido, se obtiene de la DB
                $info_cliente->ae_sector_economico = $arrTercero[0]['ae_sector_economico'];
                $info_cliente->ae_subsector_economico = $arrTercero[0]['ae_subsector_economico'];
                $info_cliente->ae_actividad_ocupacion = $arrTercero[0]['ae_actividad_ocupacion'];
            }

            $parametros = array(
                'transactionDate' => date('Y-m-d H:i:s'),
                'identification' =>
                array(
                    'idRootNumber' => $arrTercero[0]['cI_numeroraiz'], //'1111317',
                    'idConfirmation' => implode('', array($arrTercero[0]['cI_numeroraiz'], $arrTercero[0]['cI_complemento'], $arrTercero[0]['cI_lugar_emisionoextension'])), //'1111317ACLP',
                    'firstName' => mb_strtoupper($arrTercero[0]['di_primernombre']), //'PEDRO',
                    'surname' => mb_strtoupper($arrTercero[0]['di_primerapellido']), //'GUTIERREZ',
                    'dateOfBirth' => $arrTercero[0]['di_fecha_nacimiento'], //'1993-09-04',
                    'expirationDate' => $info_cliente->di_fecha_vencimiento, //'1993-09-04',
                    'genderId' => $arrTercero[0]['di_genero'], //'M',
                    'maritalStatusId' => $arrTercero[0]['di_estadocivil'], //'CA',
                ),
                'demographic' =>
                array(
                    'occupationId' => $arrTercero[0]['dd_profesion'], //'OTRO',
                    'educationLevelId' => $arrTercero[0]['dd_nivel_estudios'], //'4',
                ),
                'economic' =>
                array(
                    'monthlyIncomeId' => $arrTercero[0]['dec_ingresos_mensuales'], //'2',
                    'expensesLevelId' => $arrTercero[0]['dec_nivel_egresos'], //'1',
                ),
                'addresses' =>
                array(
                    0 =>
                    array(
                        'mainAddress' => 'S',
                        'addressTypeId' => 'RE', //'RE',
                        'propertyTypeId' => 'OT', //'ALQ',
                        'ruralUrbanId' => 'U', //'U',
                        'countryId' => (int) $arrTercero[0]['dir_pais'], //68,
                        'departmentId' => $arrTercero[0]['dir_departamento'], //'LP',
                        'provinceId' => $arrTercero[0]['dir_provincia'], //11,
                        'localityId' => $arrTercero[0]['dir_localidad_ciudad'], //102,
                        'neighborhoodId' => $arrTercero[0]['dir_barrio_zona_uv'], //952,
                        'reference' => $arrTercero[0]['dir_ubicacionreferencial'], //'Alado Banco Fie',
                        'street' => $arrTercero[0]['dir_av_calle_pasaje'], //'Avenida 6 de Agosto',
                        'edifice' => $arrTercero[0]['dir_edif_cond_urb'], //'Edificio Unidos',
                        'addressNumber' => $arrTercero[0]['dir_numero'], //'Av111 23 45',
                        'phones' =>
                        array(
                            0 =>
                            array(
                                'phoneTypeId' => 'CE', //'DO',
                                'number' => $arrTercero[0]['dir_notelefonico'], //'2211677',
                            ),
                        ),
                    ),
                ),
                'economicActivities' =>
                array(
                    0 =>
                    array(
                        'economicSector' => $info_cliente->ae_sector_economico, //'III',
                        'economicSubSector' => $info_cliente->ae_subsector_economico, //'S',
                        'activityId' => $info_cliente->ae_actividad_ocupacion, //'50201',
                        'fieActivityId' => $info_cliente->ae_actividad_fie, //'502010',
                        'enviromentId' => '9', //'1',
                        'propertyTypeId' => 'OT', //'PRO',
                        'activityDesc' => 'Actividad Principal', //'Descripcion de actividad principal',
                        'authorized' => 'N', //'S',
                        'affiliated' => 'N', //'N',
                        'openTime' => '00:00:00', //'09:00:00',
                        'closeTime' => '00:00:00', //'18:30:00',
                        'laborDay' => '6', //'2',
                        'principal' => 'S', //'S',
                    )
                ),
                'personalReferences' =>
                array(
                    0 =>
                    array(
                        'names' => mb_strtoupper($arrTercero[0]['rp_nombres']), //'Carlos Esteban',
                        'surname' => mb_strtoupper($arrTercero[0]['rp_primer_apellido']), //'Calle',
                        'address' => $this->mfunciones_generales->GetValorCatalogoDB($arrTercero[0]['dir_departamento'], 'dir_departamento'), //' Es el nombre del departamento
                        'kinshipId' => $arrTercero[0]['rp_nexo_cliente'], //'2',
                        'telephone' =>
                        array(
                            'cellphone' => $arrTercero[0]['rp_notelefonicos'], //'099999999',
                        ),
                    )
                )
            );

            // ADICION CONDICIONAL 1: Para el objeto "identification" se adiciona extensión o complemento, otros. Sólo si estos vienen con valor

            if ($arrTercero[0]['cI_complemento'] != '') {
                $parametros = array_replace_recursive($parametros, array('identification' =>
                    array(
                        'idComplement' => $arrTercero[0]['cI_complemento']
                )));
            }

            if ($arrTercero[0]['cI_lugar_emisionoextension'] != '') {
                $parametros = array_replace_recursive($parametros, array('identification' =>
                    array(
                        'idIssuePlace' => $arrTercero[0]['cI_lugar_emisionoextension']
                )));
            }
            
            if ($arrTercero[0]['di_segundo_otrosnombres'] != '') {
                $parametros = array_replace_recursive($parametros, array('identification' =>
                    array(
                        'middleName' => mb_strtoupper($arrTercero[0]['di_segundo_otrosnombres']), //'JOSE',
                )));
            }
            
            if ($arrTercero[0]['di_segundoapellido'] != '') {
                $parametros = array_replace_recursive($parametros, array('identification' =>
                    array(
                        'secondSurname' => mb_strtoupper($arrTercero[0]['di_segundoapellido']), //'JACOME',
                )));
            }
            
            // ADICION CONDICIONAL 2: Sólo si el estado civil es casado, se adiciona "spouse".
            if ($arrTercero[0]['di_estadocivil'] == 'CA') {
                // Casado
                $parametros = array_replace_recursive($parametros, array('spouse' =>
                    array(
                        'firstName' => mb_strtoupper($arrTercero[0]['con_primer_nombre']), //'MARIA',
                        'surname' => mb_strtoupper($arrTercero[0]['con_primera_pellido']), //'HERRERA',
                        'mainEconomicActivityId' => '749900',
                )));
                
                if ($arrTercero[0]['con_segundo_nombre'] != '') {
                    $parametros = array_replace_recursive($parametros, array('spouse' =>
                        array(
                            'middleName' => mb_strtoupper($arrTercero[0]['con_segundo_nombre']), //'FERNANDA',
                    )));
                }
            }

            // Y se adiciona el apellido de casada, siempre y cuando tenga valor, cuando es género Femenino y estado civil: Casado CA, Divorciado DI o Viudo VI
            if (str_replace(' ', '', $arrTercero[0]['di_apellido_casada']) != '' && mb_strtoupper($arrTercero[0]['di_genero']) == 'F') {
                if ($arrTercero[0]['di_estadocivil'] == 'CA' || $arrTercero[0]['di_estadocivil'] == 'DI' || $arrTercero[0]['di_estadocivil'] == 'VI') {
                    $parametros = array_replace_recursive($parametros, array('identification' =>
                        array(
                            'marriedSurname' => mb_strtoupper($arrTercero[0]['di_apellido_casada'])
                    )));
                }
            }

            // ADICIÓN CONDICIONAL 3: Sólo si se registró Sector Económico "V" (Ingreso Fijo) se adiciona 'employments', caso contrario la segunda direccion
            if ($info_cliente->ae_sector_economico == 'V') {
                // Ingreso Fijo
                $parametros = array_replace_recursive($parametros, array('employments' =>
                    array(
                        0 =>
                        array(
                            'businessName' => $arrTercero[0]['emp_nombre_empresa'], //'Empresa de prueba 2 ',
                            'address' => $arrTercero[0]['emp_direccion_trabajo'], //'Calle principal A y secundaria B',
                            'phone' => $arrTercero[0]['emp_telefono_faxtrabaj'], //'023405678',
                            'businessType' => $arrTercero[0]['emp_tipo_empresa'], //'PUB',
                            'activityId' => (string) $arrTercero[0]['emp_codigo_actividad'], //'14220',
                            'publicEmployee' => 'N', //'S',
                            'description' => $arrTercero[0]['emp_descripcion_cargo'], //'Servicios',
                            'startDate' => $arrTercero[0]['emp_fecha_ingreso'], //'2016-06-12',
                            'economicActivityId' => 0, //0,
                        ),
                )));
            } else {
                // Diferente a Ingreso Fijo
                $parametros = array_replace_recursive($parametros, array('addresses' =>
                    array(
                        1 =>
                        array(
                            'addressTypeId' => 'AE', //'RE',
                            'propertyTypeId' => 'OT', //'ALQ',
                            'ruralUrbanId' => 'U', //'U',
                            'countryId' => (int) $arrTercero[0]['dir_pais_neg'], //68,
                            'departmentId' => $arrTercero[0]['dir_departamento_neg'], //'LP',
                            'provinceId' => $arrTercero[0]['dir_provincia_neg'], //11,
                            'localityId' => $arrTercero[0]['dir_localidad_ciudad_neg'], //102,
                            'neighborhoodId' => $arrTercero[0]['dir_barrio_zona_uv_neg'], //952,
                            'reference' => $arrTercero[0]['dir_ubicacionreferencial_neg'], //'Alado Banco Fie',
                            'street' => $arrTercero[0]['dir_av_calle_pasaje_neg'], //'Avenida 6 de Agosto',
                            'edifice' => $arrTercero[0]['dir_edif_cond_urb_neg'], //'Edificio Unidos',
                            'addressNumber' => $arrTercero[0]['dir_numero_neg'], //'Av111 23 45',
                            'phones' =>
                            array(
                                0 =>
                                array(
                                    'phoneTypeId' => 'CE', //'DO',
                                    'number' => $arrTercero[0]['dir_notelefonico'], //'2211677',
                                ),
                            ),
                        ),
                )));
            }
            
            // ADICION CONDICIONAL 4: Para el segundo apellido de referencias personales
            if ($arrTercero[0]['rp_segundo_apellido'] != '') {
                $parametros = array_replace_recursive($parametros, array('personalReferences' =>
                    array(
                        0 =>
                        array(
                            'secondSurname' => mb_strtoupper($arrTercero[0]['rp_segundo_apellido']), //'Baez',
                        )
                )));
            }

            // ADICION CONDICIONAL 5: Si la profesión seleccionada es OTRO aún no se registra, colocar 'OTRA OCUPACION'
            if ($arrTercero[0]['dd_profesion'] == 'OTRO') {
                $parametros = array_replace_recursive($parametros, array('demographic' =>
                    array(
                        'occupationOther' => 'OTRA OCUPACION', //'OTRA OCUPACION',
                )));
            }

            // ADICION CONDICIONAL 6: Dependiendo si es Creación o Actualización
            if ($info_cliente->crear_o_actualizar == 1) {
                // Si se indica "creación" validar con el código cobis, si existe, es "actualizar"
                $info_cliente->crear_o_actualizar = ($info_cliente->f_cobis_codigo == '' ? 1 : 2); // 1=Crear  2=Actualizar
            }

            $texto_aux_error_img = ''; // <-- Texto auxiliar si existe error al incluir la imagen selfie
            
            if ($info_cliente->crear_o_actualizar == 2 && str_replace(' ', '', $info_cliente->f_cobis_codigo) != '') {
                // Actualizar
                $parametros = array_merge($parametros, array(
                    'operation' => 'P',
                    'clientId' => (int) $info_cliente->f_cobis_codigo
                ));
            } else {
                // Crear
                $parametros = array_merge($parametros, array(
                    'operation' => 'I'
                ));
            }
            
            // Requerimiento Jun-2022: Ahora tanto "I" como "P" se envía la selfie
            
            // Obtener la imagen selfie del registro
            switch ((int)$arrTercero[0]['tercero_asistencia']) {
                case 0:

                    // No Asistido
                    $result_imagen = $this->mfunciones_generales->GetInfoTercerosDigitalizado($info_cliente->codigo_solicitud, 9, 'documento');

                    break;

                case 1:
                    // Asistido
                    $result_imagen = $this->mfunciones_generales->GetInfoTercerosDigitalizadoPDF($info_cliente->codigo_solicitud, 9, 'documento');

                    if($result_imagen != FALSE)
                    {
                        try {

                            // Convertir a JPG
                            $imagick = new Imagick();
                            $imagick->readImageBlob(base64_decode($result_imagen));
                            $imagick->setImageFormat("jpeg");
                            $imageBlob = $imagick->getImageBlob();
                            $imagick->clear();

                            $result_imagen = base64_encode($imageBlob);

                        } catch (ImagickException $ex) {
                            $result_imagen = FALSE;
                        }
                    }

                    break;

                default:
                    $result_imagen = FALSE;
                    break;
            }

            if($result_imagen != FALSE)
            {
                $result_imagen = preg_replace('#^data:image/[^;]+;base64,#', '', $result_imagen);

                $parametros = array_merge($parametros, array('biometricId' =>
                    array(
                        'photo' => $result_imagen
                )));
            }
            else
            {
                $texto_aux_error_img = ' [No se incluyó la imagen en [biometricId], revise el elemento digitalizado.]';

                $logger = new CI_Log();
                $logger->write_log('error', 'Error flujo COBIS Etapa4 -  ' . 'Registro: ' . $info_cliente->codigo_solicitud . ' ' . $texto_aux_error_img);
            }
            
            // Aumentar valores por defecto
            $conf_f_cobis_uri_cliente_alta_params = json_decode($arrConf[0]['conf_f_cobis_uri_cliente_alta_params'], true);
            if (json_last_error() == JSON_ERROR_NONE) {
                $parametros = array_replace_recursive($parametros, $conf_f_cobis_uri_cliente_alta_params);
            }
            
            // Requerimiento Jun-2022: Ahora tanto "I" como "P" se envía la selfie
            /*
            if ($info_cliente->crear_o_actualizar == 2 && str_replace(' ', '', $info_cliente->f_cobis_codigo) != '') {
                // Se quita "fingerprint" si es actualización
                unset($parametros['fingerprint']);
            }
            */
            
            $resultado_soa_fie = $this->mfunciones_generales->Cliente_SOA_FIE_COBIS($info_cliente, $info_cliente->token, $arrConf[0]['conf_f_cobis_uri_cliente_alta'], $parametros, $info_cliente->accion_usuario, date('Y-m-d H:i:s'));
            
            if($resultado_soa_fie->ws_error == TRUE)
            {
                $this->mfunciones_generales->ExcepcionGenerica($info_cliente);
            }
            
            // -- Parametros principales
            $resultado_soa_fie->puntero_etapa_flujo = $info_cliente->puntero_etapa_flujo;
            // --
            
            switch ($resultado_soa_fie->ws_httpcode) {
                case 409:

                    switch (true) {
                        // Si el httpcode es 409 y viene con el parámetro 'detail'
                        case (isset($resultado_soa_fie->ws_result['detail']) && isset($resultado_soa_fie->ws_result['detail'][0]['code'])):

                            $detalle_error = 'code ' . $resultado_soa_fie->ws_result['detail'][0]['code'] . ': ' . $resultado_soa_fie->ws_result['detail'][0]['message'];
                            
                            $detalle_aux = $resultado_soa_fie->ws_result['detail'][0]['message'];

                            break;
                        // Si el httpcode es 409 y viene con el parámetro 'detail' e 'Item'
                        case (isset($resultado_soa_fie->ws_result['detail']) && isset($resultado_soa_fie->ws_result['detail']['Item']['code'])):

                            $detalle_error = 'code ' . $resultado_soa_fie->ws_result['detail']['Item']['code'] . ': ' . $resultado_soa_fie->ws_result['detail']['Item']['message'];
                            
                            $detalle_aux = $resultado_soa_fie->ws_result['detail']['Item']['message'];

                            break;

                        default:
                            $detalle_error = 'code ' . $resultado_soa_fie->ws_result['code'] . ': ' . $resultado_soa_fie->ws_result['message'];
                            
                            $detalle_aux = $resultado_soa_fie->ws_result['message'];
                            
                            break;
                    }

                    // Verificar si llega con el dato de clientId para registrar
                    $detalle_aux = substr($detalle_aux, 0, 300);

                    if (strstr(strtoupper($detalle_aux), strtoupper('clientId:'))) {

                        $message = strtoupper($detalle_aux);
                        $message = explode('CLIENTID:', $message);

                        if (count($message) >= 2) {
                            // Guardado de número de cuenta
                            $info_cliente->f_cobis_codigo = preg_replace("/[^0-9]/", "", $message[1]);

                            // Registrar código COBIS en DB
                            $this->mfunciones_logica->setFlujoCOBIS_codigoCOBIS($info_cliente->codigo_solicitud, $info_cliente->f_cobis_codigo);
                        }
                    }
                    
                    $detalle_error .= $texto_aux_error_img;
                    
                    // Excepción derivar a Rechazado
                    $this->MarcarExcepcion($info_cliente, 'rec', 'Cliente no ' . ($info_cliente->crear_o_actualizar == 1 ? 'registrado' : 'actualizado. (clientCode: ' . (string) $info_cliente->f_cobis_codigo . ')') . '. ' . $detalle_error);
                    exit();

                    break;

                case 200:

                    if ($info_cliente->crear_o_actualizar == 1) {

                        // Validación de estructura
                        if (!isset($resultado_soa_fie->ws_result['clientAdmin']['enteCode'])) {
                            // Excepcion derivar a Pendiente. Objeto clientAdmin no esta presente.
                            $this->MarcarExcepcion($info_cliente, 'rei', 'Objeto [clientAdmin][enteCode] no presente', '', 0);
                            exit();
                        }

                        // Registrar código COBIS (enteCode)
                        $info_cliente->f_cobis_codigo = (int) $resultado_soa_fie->ws_result['clientAdmin']['enteCode'];

                        // Registrar código COBIS en DB
                        $this->mfunciones_logica->setFlujoCOBIS_codigoCOBIS($info_cliente->codigo_solicitud, $info_cliente->f_cobis_codigo);
                    }

                    // Registrar tracking
                    $this->MarcarTracking($info_cliente, 'Cliente ' . ($info_cliente->crear_o_actualizar==1 ? 'registrado' : 'actualizado') . ' en COBIS.' . $texto_aux_error_img, 1);
                    // Marca la etapa para Apertura de cuenta
                    $info_cliente->puntero_etapa_flujo = 4;

                    return $info_cliente;

                    break;

                default:

                    // Excepción Reintento
                    $this->MarcarExcepcion($info_cliente, 'rei');
                    exit();

                    break;
            }

            return $info_cliente;
        }
        catch (Exception $exc) {
            $this->mfunciones_generales->ExcepcionGenerica($info_cliente);
        }
        
        return $resultado_soa_fie;
    }
    
    private function Servicio_accountAffiliation($arrTercero, $arrConf, $info_cliente)
    {
        $resultado_soa_fie = new stdClass();
        $resultado_soa_fie->error = TRUE;
        $resultado_soa_fie->result = '';
        
        try {
            
            $parametros = array(
                'transactionDate' => date('Y-m-d H:i:s'),
                'clientId' => (int) $info_cliente->f_cobis_codigo,
                'bankingProduct' => (int)$arrTercero[0]['tipo_cuenta'],
                'clientEMail' => $arrTercero[0]['direccion_email'],
            );

            // Si se registró el número de cuenta se envía el parámetro
            if ((string) $info_cliente->onboarding_numero_cuenta != '') {
                $parametros = array_merge($parametros, array('accountNumber' => (string) $info_cliente->onboarding_numero_cuenta));
            }
            
            $conf_f_cobis_uri_apertura_params = json_decode($arrConf[0]['conf_f_cobis_uri_apertura_params'], true);
            if(json_last_error() == JSON_ERROR_NONE)
            {
                $parametros = array_replace_recursive($parametros, $conf_f_cobis_uri_apertura_params);
            }
            
            $info_cliente->token = $this->gestionEtapaToken($info_cliente);
            
            $resultado_soa_fie = $this->mfunciones_generales->Cliente_SOA_FIE_COBIS($info_cliente, $info_cliente->token, $arrConf[0]['conf_f_cobis_uri_apertura'], $parametros, $info_cliente->accion_usuario, date('Y-m-d H:i:s'));
            
            if($resultado_soa_fie->ws_error == TRUE)
            {
                $this->mfunciones_generales->ExcepcionGenerica($info_cliente);
            }
            
            // -- Parametros principales
            $resultado_soa_fie->puntero_etapa_flujo = $info_cliente->puntero_etapa_flujo;
            // --
            
            switch ($resultado_soa_fie->ws_httpcode) {
                case 409:

                    $detalle_error = ' => code ' . $resultado_soa_fie->ws_result['detail'][0]['code'] . ': ' . $resultado_soa_fie->ws_result['detail'][0]['message'];

                    // Validar listas negras
                    if (strstr(str_replace(' ', '', strtoupper($resultado_soa_fie->ws_result['detail'][0]['message'])), strtoupper('sp_control_listasnegras'))) {
                        // Excepción derivar a Rechazado. Listas negras.
                        $this->MarcarExcepcion($info_cliente, 'rec', 'Listas negras. (clientCode: ' . (string) $info_cliente->f_cobis_codigo . '). ' . $detalle_error, 'rech_lista_negra');
                        exit();
                    }

                    $detalle_excepcion = 'No apertura de cuenta ni afiliación a IB';

                    if (strstr(strtoupper($resultado_soa_fie->ws_result['detail'][0]['message']), strtoupper('accountNumber:'))) {
                        $message = strtoupper($resultado_soa_fie->ws_result['detail'][0]['message']);
                        $message = explode('ACCOUNTNUMBER:', $message);

                        if (count($message) >= 2) {
                            // Guardado de número de cuenta
                            $info_cliente->onboarding_numero_cuenta = preg_replace("/[^0-9]/", "", $message[1]);

                            // Registrar código COBIS en DB
                            $this->mfunciones_logica->setFlujoCOBIS_nroCuenta($info_cliente->codigo_solicitud, $info_cliente->onboarding_numero_cuenta);

                            $detalle_excepcion = 'Apertura de cuenta, sin afiliación a IB - accountNumber: ' . $info_cliente->onboarding_numero_cuenta;
                        }
                    }

                    // Excepción derivar a Pendiente. Con parámetro [accountNumber].
                    $this->MarcarExcepcion($info_cliente, 'pen', $detalle_excepcion . '. (clientCode: ' . (string) $info_cliente->f_cobis_codigo . '). ' . $detalle_error);
                    exit();

                    break;

                case 200:

                    // Validación de estructura
                    if (!isset($resultado_soa_fie->ws_result['affiliation'])) {
                        // Excepcion derivar a Pendiente. Objeto affiliation no esta presente.
                        $this->MarcarExcepcion($info_cliente, 'rei', 'Objeto [affiliation] no presente', '', 0);
                        exit();
                    }

                    if (!isset($resultado_soa_fie->ws_result['account']['accountNumber'])) {

                        // Excepcion derivar a Pendiente. Sin [accountNumber]
                        $this->MarcarExcepcion($info_cliente, 'pen', 'Parámetro [accountNumber] no presente, no apertura de cuenta ni afiliación a IB. (clientCode: ' . (string) $info_cliente->f_cobis_codigo . ').');
                    } else {
                        $accountNumber = (string) $resultado_soa_fie->ws_result['account']['accountNumber'];

                        if ($accountNumber == '' || $accountNumber == null || strtoupper($accountNumber) == 'NULL') {
                            // Excepcion derivar a Pendiente. Parámetro [accountNumber] sin valor o null
                            $this->MarcarExcepcion($info_cliente, 'pen', 'Parámetro [accountNumber] sin valor o null, no apertura de cuenta. (clientCode: ' . (string) $info_cliente->f_cobis_codigo . ').');
                        } else {
                            // Guardado de número de cuenta y marcar la etapa para Generación contrato PDF
                            
                            $info_cliente->onboarding_numero_cuenta = str_replace(' ', '', $accountNumber);

                            // Registrar tracking
                            $this->MarcarTracking($info_cliente, 'Apertura de cuenta y afiliación a IB - accountNumber: ' . $info_cliente->onboarding_numero_cuenta . '.', 1);

                            // Registrar código COBIS en DB
                            $this->mfunciones_logica->setFlujoCOBIS_nroCuenta($info_cliente->codigo_solicitud, $info_cliente->onboarding_numero_cuenta);
                            
                            $info_cliente->puntero_etapa_flujo = 5;
                        }
                    }

                    return $info_cliente;

                    break;

                default:

                    // Excepción Reintento
                    $this->MarcarExcepcion($info_cliente, 'rei');
                    exit();

                    break;
            }


            return $info_cliente;
        }
        catch (Exception $exc) {
            $this->mfunciones_generales->ExcepcionGenerica($info_cliente);
        }
        
        return $resultado_soa_fie;
    }
    
}
?>