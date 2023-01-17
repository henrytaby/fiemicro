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
 * Controlador para la gestión de Prospectos
 * @brief BANDEJA DE LA INSTANCIA
 * @class Conf_catalogo_controller
 */
class Bandeja_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 20;

        // Código de la Etapa Propio de la Bandeja
        protected $codigo_etapa = 5;

    function __construct() {
        parent::__construct();
    }

    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */

    public function Bandeja_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_microcreditos');

        // Bandeja Supervisión Leads Consolidados

        //$filtro = 'p.prospecto_etapa>=' . $this->codigo_etapa . ' AND p.prospecto_consolidado=1';
        $filtro = 'p.prospecto_etapa IN (5,22) AND p.prospecto_consolidado=1 AND p.general_categoria=1';

        /***** REGIONALIZACIÓN INICIO ******/
            // Se captura el filtro Regionalizado
            $regionalizado = $this->mfunciones_generales->getProspectosRegion();
            if($regionalizado->error)
            {
                js_error_div_javascript($this->lang->line('TablaNoResultados'));
                exit();
            }
            $data["region_nombres"] = $regionalizado->region_nombres_texto;
            $filtro .= $regionalizado->region_consulta;
        /***** REGIONALIZACIÓN FIN ******/

        $arrResultado = $this->mfunciones_logica->ListadoBandejasProspecto($filtro);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        $lst_resultado[0] = array();

        $i = 0;

        if (isset($arrResultado[0]))
        {
            foreach ($arrResultado as $key => $value)
            {
                $item_valor = array(
                        "prospecto_id" => $value["prospecto_id"],
                        "usuario_id" => $value["usuario_id"],
                        "ejecutivo_nombre" => $value["ejecutivo_nombre"],
                        "tiempo_etapa" => 0,
                        "general_solicitante" => $value["general_solicitante"],
                        "general_ci" => $value["general_ci"] . $this->mfunciones_generales->GetValorCatalogo($value["general_ci_extension"], 'extension_ci'),
                        "general_actividad" => $value["general_actividad"],
                        "general_destino" => nl2br($value["general_destino"]),
                        "general_interes" => $value["general_interes"],
                        "camp_id" => $value["camp_id"],
                        "camp_nombre" => $value["camp_nombre"],
                        "prospecto_evaluacion" => $value["prospecto_evaluacion"],
                        "prospecto_fecha_conclusion" => $value["prospecto_fecha_conclusion"],
                        "prospecto_etapa" => $value["prospecto_etapa"],
                        "prospecto_jda_eval" => $value["prospecto_jda_eval"],
                        "registro_rechazado_texto" => "",
                        "registro_num_proceso" => $value["prospecto_num_proceso"]
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        }

        $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        $arrSolCred = $this->mfunciones_microcreditos->BandejaSupervisionLeads($lista_region->region_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrSolCred);

        if (isset($arrSolCred[0]))
        {
            foreach ($arrSolCred as $key => $value)
            {
                $item_valor = array(
                        "prospecto_id" => $value["sol_id"],
                        "usuario_id" => $value["usuario_id"],
                        "ejecutivo_nombre" => $value["ejecutivo_nombre"],
                        "tiempo_etapa" => 0,
                        "general_solicitante" => $value["sol_primer_nombre"] . ' ' . $value["sol_primer_apellido"] . ' ' . $value["sol_segundo_apellido"],
                        "general_ci" => $value["sol_ci"] . ' ' . $value["sol_complemento"] . ' ' . ((int)$value['sol_extension']==-1 ? '' : $this->mfunciones_generales->GetValorCatalogoDB($value['sol_extension'], 'cI_lugar_emisionoextension')),
                        "general_actividad" => ($value['sol_dependencia']==1 ? 'Dependiente, ' . $value['sol_depen_empresa'] : 'Independiente, ' . $value['sol_indepen_actividad']),
                        "general_destino" => nl2br($value["sol_detalle"]),
                        "general_interes" => '',
                        "camp_id" => $value["tipo_persona_id"],
                        "camp_nombre" => $value["tipo_persona_nombre"],
                        "prospecto_evaluacion" => $value["sol_evaluacion"],
                        "prospecto_fecha_conclusion" => $value["sol_registro_completado_fecha"],
                        "prospecto_etapa" => ($value["sol_jda_eval"]==0 ? 5 : 22),
                        "prospecto_jda_eval" => $value["sol_jda_eval"],
                        "registro_rechazado_texto" => ((int)$value["sol_evaluacion"] == 2 ? $value["sol_rechazado_texto"] : ''),
                        "registro_num_proceso" => $value["sol_num_proceso"]
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        }

        $arrResumen[0] = array();
        $tiempo_etapa_asignado = 0;

        // Se establece una variable de SESSION para determinar en que bandeja se encuentra el usuario y al Observar el documento, regresar allí

        $_SESSION['aux_flag_reporte_return'] = 0; // <-- Se indica que no se está en reportes

        $_SESSION['direccion_bandeja_actual'] = 'Bandeja/Verificacion/Ver';
        $_SESSION['dato_etapa_actual'] = $this->codigo_etapa;

        $data["arrRespuesta"] = $lst_resultado;
        $data["arrResumen"] = $arrResumen;
        $data["tiempo_etapa_asignado"] = $tiempo_etapa_asignado;

        $this->load->view('bandeja_verificacion_requisitos/view_bandeja_ver', $data);
    }

    public function JDA_Evaluacion() {

        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');

        $this->formulario_logica_general->DefinicionValidacionFormulario();

        // 0=Insert    1=Update

        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }

        $codigo = $this->input->post('codigo', TRUE);
        $codigo_tipo_persona = $this->input->post('codigo_tipo_persona', TRUE);

        // 1. Datos del Lead
        switch ((int)$codigo_tipo_persona) {
            case 6:

                // Solicitud de Crédito
                $this->load->model('mfunciones_microcreditos');

                $arrProspecto = $this->mfunciones_microcreditos->BandejaSupervisionLeads(-1, ' AND sc.sol_id=' . $codigo);

                if (!isset($arrProspecto[0]))
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }

                $arrProspecto[0]['prospecto_id'] = $arrProspecto[0]['sol_id'];
                $arrProspecto[0]['camp_id'] = $arrProspecto[0]['tipo_persona_id'];
                $arrProspecto[0]['camp_nombre'] = $arrProspecto[0]['tipo_persona_nombre'];
                $arrProspecto[0]['usuario_nombre'] = $arrProspecto[0]['ejecutivo_nombre'];
                $arrProspecto[0]['prospecto_jda_eval'] = $arrProspecto[0]['sol_jda_eval'];
                $arrProspecto[0]['prospecto_jda_eval_texto'] = $arrProspecto[0]['sol_jda_eval_texto'];
                $arrProspecto[0]['general_solicitante'] = $arrProspecto[0]["sol_primer_nombre"] . ' ' . $arrProspecto[0]["sol_primer_apellido"] . ' ' . $arrProspecto[0]["sol_segundo_apellido"];
                $arrProspecto[0]['general_ci'] = $arrProspecto[0]["sol_ci"] . ' ' . $arrProspecto[0]["sol_complemento"] . ' ' . ((int)$arrProspecto[0]['sol_extension']==-1 ? '' : $this->mfunciones_generales->GetValorCatalogoDB($arrProspecto[0]['sol_extension'], 'cI_lugar_emisionoextension'));
                $arrProspecto[0]['prospecto_consolidar_fecha'] = $arrProspecto[0]['sol_consolidado_fecha'];
                $arrProspecto[0]['registro_num_proceso'] = $arrProspecto[0]['sol_num_proceso'];
                $arrProspecto[0]['prospecto_desembolso_monto'] = $arrProspecto[0]['prospecto_desembolso_monto'];

                break;

            default:

                // Datos del Prospecto
                $arrProspecto = $this->mfunciones_logica->ObtenerInfoProspecto($codigo);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrProspecto);

                $arrProspecto[0]['registro_num_proceso'] = $arrProspecto[0]['prospecto_num_proceso'];

                $arrProspecto[0]['general_ci'] = $arrProspecto[0]["general_ci"] . $this->mfunciones_generales->GetValorCatalogo($DatosProspecto[0]["general_ci_extension"], 'extension_ci');

                break;
        }

        if(!isset($arrProspecto[0]))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }

        // Auxiliar: Si la evaluación es Devolver al Oficial de Negocios (99) el texto debe estar vacío.
        if((int)$arrProspecto[0]['prospecto_jda_eval'] == 99)
        {
            $arrProspecto[0]['prospecto_jda_eval_texto'] = '';
        }

        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array('prospecto_jda_eval' => $arrProspecto[0]['prospecto_jda_eval'], 'prospecto_jda_eval_texto' => $arrProspecto[0]['prospecto_jda_eval_texto'], 'registro_num_proceso' => $arrProspecto[0]['registro_num_proceso']));

        $data["arrRespuesta"] = $arrProspecto;

        $data["codigo_tipo_persona"] = $codigo_tipo_persona;

        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

        $this->load->view('bandeja_verificacion_requisitos/view_jda_evaluacion', $data);
    }

    public function JDA_Evaluacion_Guardar() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_microcreditos');

        if(!isset($_POST['estructura_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }

        $codigo_prospecto = $this->input->post('estructura_id', TRUE);
        //$registro_num_proceso = (int)$this->input->post('registro_num_proceso', TRUE);
        $prospecto_jda_eval = $this->input->post('prospecto_jda_eval', TRUE);
        $prospecto_jda_eval_texto = htmlspecialchars($this->input->post('prospecto_jda_eval_texto', TRUE));

        $codigo_tipo_persona = $this->input->post('codigo_tipo_persona', TRUE);

        if((int)$prospecto_jda_eval != 1 && (int)$prospecto_jda_eval != 2 && (int)$prospecto_jda_eval != 99)
        {
            js_error_div_javascript('Debe seleccionar una opción para la evaluación.');
            exit();
        }

        if((int)$prospecto_jda_eval == 99 && $prospecto_jda_eval_texto == '')
        {
            js_error_div_javascript('Marcó la opción para Devolver al Oficial de Negocios, debe registrar la observación en el apartado de comentario.');
            exit();
        }

        /*if($this->mfunciones_microcreditos->ValidarNumOperacion($registro_num_proceso))
        {
            js_error_div_javascript($this->lang->line('registro_num_proceso_error'));
            exit();
        }*/

        $accion_usuario = $_SESSION["session_informacion"]["login"];
        $codigo_usuario = $_SESSION["session_informacion"]["codigo"];
        $accion_fecha = date('Y-m-d H:i:s');

        switch ((int)$codigo_tipo_persona) {
            case 6:

                $arrResultado3 = $this->mfunciones_microcreditos->VerificaSolicitudConsolidado($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                if (!isset($arrResultado3[0]))
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }

                $this->mfunciones_microcreditos->update_JDA_Eval_Sol($codigo_prospecto, $registro_num_proceso, $prospecto_jda_eval, $prospecto_jda_eval_texto, $codigo_usuario, $accion_usuario, $accion_fecha);

                break;

            default:

                /***** REGIONALIZACIÓN: Valida si el prospecto pertenece a la región ******/
                if(!$this->mfunciones_generales->VerificaProspectoRegion($codigo_prospecto))
                {
                    js_error_div_javascript($this->lang->line('regionaliza_NoRegion'));
                    exit();
                }

                $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                if (!isset($arrResultado3[0]))
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }

                // PASO 2: Se actualiza la evaluación del JDA

                $this->mfunciones_microcreditos->update_JDA_Eval($codigo_prospecto, $prospecto_jda_eval, $prospecto_jda_eval_texto, $codigo_usuario, $accion_usuario, $accion_fecha);

                if((int)$prospecto_jda_eval != 99)
                {
                    // Aprobar o Rechazar

                    $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, ((int)$prospecto_jda_eval==1 ? 22 : 23), $arrResultado3[0]['prospecto_etapa'], $accion_usuario, $accion_fecha, 2);

                    /***  REGISTRAR SEGUIMIENTO ***/
                    $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, ((int)$prospecto_jda_eval==1 ? 22 : 23), 13, 'JDA Evaluación: ' . $this->mfunciones_generales->GetValorCatalogo($prospecto_jda_eval, 'prospecto_evaluacion') . '.' . ((int)$prospecto_jda_eval==2 ? ' Fin del Flujo.' : ''), $accion_usuario, $accion_fecha);
                }
                else
                {
                    // Observar y Devolver al Oficial de Negocios

                    // INSERTAR EN LA TABLA OBSERVACION_DOCUMENTO

                    switch ((int)$codigo_tipo_persona) {
                        case 6:

                            // Solicitud de Crédito
                            $this->load->model('mfunciones_microcreditos');

                            $this->mfunciones_microcreditos->SolInsertarObservacionDoc($codigo_prospecto, (int)$codigo_tipo_persona, $_SESSION["session_informacion"]["codigo"], 0, 1, $accion_fecha, $prospecto_jda_eval_texto, $accion_usuario, $accion_fecha);

                            break;

                        default:

                            $this->mfunciones_logica->InsertarObservacionDoc($codigo_prospecto, $_SESSION["session_informacion"]["codigo"], 0, 1, $accion_fecha, $prospecto_jda_eval_texto, $accion_usuario, $accion_fecha);

                            break;
                    }

                    // REMITIR OBSERVACIÓN

                    switch ((int)$codigo_tipo_persona) {
                        case 6:

                            // Solicitud de Crédito
                            $this->load->model('mfunciones_microcreditos');

                            $arrResultado = $this->mfunciones_microcreditos->SolObtenerDocumentosDigitalizarApp((int)$codigo_tipo_persona);
                            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                            if (isset($arrResultado[0]))
                            {
                                foreach ($arrResultado as $key => $value)
                                {
                                    if($this->mfunciones_microcreditos->SolVerDocumentoObservado($codigo_prospecto, $value["documento_id"], (int)$codigo_tipo_persona))
                                    {
                                        $listado_documentos .= $value["documento_nombre"]. '<br />';
                                    }
                                }
                            }

                            // CAMBIAR EL ESTADO DEL PROSPECTO PARA DESCONSOLIDAR Y OBSERVAR            
                            $this->mfunciones_microcreditos->ObservarDocSolicitud($accion_usuario, $accion_fecha, $codigo_prospecto);

                            // REMITIR CORREO ELECTRÓNICO DE OBSERVACIÓN AL EJECUTIVO DE CUENTAS

                            // Listado Detalle Empresa por el código de Prospecto
                            $arrResultadoAux = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($codigo_prospecto);

                            $correo_ejecutivo = $arrResultadoAux[0]['agente_correo'];
                            $nombre_ejecutivo = $arrResultadoAux[0]['agente_nombre'];

                            $arrResultado2[0] = array();
                            $arrResultado2[0]['prospecto_id'] = 'SOL_' . $codigo_prospecto . ' (Solicitud de Crédito)';
                            $arrResultado2[0]['empresa_nombre'] = $arrResultadoAux[0]['sol_nombre_completo'];
                            $arrResultado2[0]['empresa_categoria'] = 'Cliente';
                            $arrResultado2[0]['ejecutivo_asignado_nombre'] = $arrResultadoAux[0]['agente_nombre'];
                            $arrResultado2[0]['ejecutivo_asignado_contacto'] = $arrResultadoAux[0]['agente_telefono'];

                            $correo = $this->mfunciones_generales->EnviarCorreo('observar_documento_app', $correo_ejecutivo, $nombre_ejecutivo, $arrResultado2);

                            /* Se Envía a la APP la Notificación por Push   tipo_notificacion, $tipo_visita, $codigo_visita */
                            $this->mfunciones_generales->EnviarNotificacionPush(3, 1, $codigo_prospecto);

                            break;

                        default:

                            $arrResultado = $this->mfunciones_logica->ObtenerDocumentosDigitalizar($codigo_prospecto);
                            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                            if (isset($arrResultado[0]))
                            {
                                foreach ($arrResultado as $key => $value)
                                {
                                    if($this->mfunciones_generales->VerificaDocumentoObservado($codigo_prospecto, $value["documento_id"]))
                                    {
                                        $listado_documentos .= $value["documento_nombre"]. '<br />';
                                    }
                                }
                            }

                            // PASO 3: SE REGISTRA LA OBSERVACIÓN

                            // Rol 6 = Analista Legal            
                            if($_SESSION["session_informacion"]["rol_codigo"] == 6)
                            {
                                $tipo_observacion = 2;
                            }

                            // PASO 4: CAMBIAR EL ESTADO DEL PROSPECTO PARA DESCONSOLIDAR Y OBSERVAR            
                            $this->mfunciones_logica->ObservarDocProspecto($accion_usuario, $accion_fecha, $codigo_prospecto);

                            // PASO 5: REMITIR CORREO ELECTRÓNICO DE OBSERVACIÓN AL EJECUTIVO DE CUENTAS

                            // Listado Detalle Empresa por el código de Prospecto
                            $arrResultado2 = $this->mfunciones_generales->GetDatosEmpresaCorreo($codigo_prospecto);

                            $correo_ejecutivo = $arrResultado2[0]['ejecutivo_asignado_correo'];
                            $nombre_ejecutivo = $arrResultado2[0]['ejecutivo_asignado_nombre'];

                            $correo = $this->mfunciones_generales->EnviarCorreo('observar_documento_app', $correo_ejecutivo, $nombre_ejecutivo, $arrResultado2, 0);

                            /* Se Envía a la APP la Notificación por Push   tipo_notificacion, $tipo_visita, $codigo_visita */
                            $this->mfunciones_generales->EnviarNotificacionPush(3, 1, $codigo_prospecto);

                            /***  REGISTRAR SEGUIMIENTO ***/
                            $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, 1, 2, $listado_documentos, $accion_usuario, $accion_fecha);

                            // Devolver a etapa Evaluado y Preparación Consolidar
                            $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 4, $arrResultado3[0]['prospecto_etapa'], $accion_usuario, $accion_fecha, 0);

                            break;
                    }

                    js_invocacion_javascript('alert("' . $this->lang->line('prospecto_obs_doc_guardado') . ' Se redirigirá a la bandeja.");');
                }

                break;
        }

        $this->Bandeja_Ver();
    }

    public function JdaJsonOperacion(){
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');

        /**
         * Conseguimos las configuraciones general
         */
        $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
        $arrConf = $arrConf[0];

        $api_credit = array();
        $api_credit["conf_credit_nro_uri"] = $arrConf["conf_credit_nro_uri"];
        //$api_credit["conf_credit_autentication_uri"] = $arrConf["conf_credit_autentication_uri"];

        $api_credit["conf_credit_client_id"] = $arrConf["conf_credit_client_id"];
        $api_credit["conf_credit_type"] = $arrConf["conf_credit_type"];
        $api_credit["conf_credit_scope"] = $arrConf["conf_credit_scope"];
        $api_credit["conf_credit_user"] = $arrConf["conf_credit_user"];
        $api_credit["conf_credit_password"] = $arrConf["conf_credit_password"];

        /**
         * Variables recibidas para realizar las operaciones
         */
        $id = $this->input->get('id', TRUE);
        $customerDocumentNumber = $this->input->get('customerDocumentNumber', TRUE);
        $creditOperation = $this->input->get('creditOperation', TRUE);

        if($id!="" && $customerDocumentNumber!="" && $creditOperation!=""){
            $parametros = array(
                "creditOperation" => (int)$creditOperation,
                "customerDocumentNumber" => (int)$customerDocumentNumber,
                "id" => (int)$id
            );

            $respapi = array();
            $res = array();
            /**
             *
             * Añadir código para autentificación y get de token
             */

            /**
             * recuperar token
             */

            $token = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICIwb1Q2Nm5kaUlKZklLNy1Nbk50cFlJQmpPYnVhdWlweExGbDJHdUhIeHFzIn0.eyJleHAiOjE2NzM2MzQ5MTQsImlhdCI6MTY3MzYzNDYxNCwianRpIjoiY2JlNzJkNWMtMTZkNy00YWQxLThmZTItY2MxZWQyNWMyNzhiIiwiaXNzIjoiaHR0cDovL2tleWNsb2FrLWtleWNsb2FrLWRldi5hcHBzLmRlc2FjbHVzdGVyLmJhbmNvZmllbGFiLmNvbS5iby9hdXRoL3JlYWxtcy9maWVkaWdpdGFsY3JlZGl0IiwiYXVkIjoiYWNjb3VudCIsInN1YiI6ImIzNjNiMTIxLWZlNmYtNGQ3ZC05NTQwLTYzNjA2ODU0NTI1ZiIsInR5cCI6IkJlYXJlciIsImF6cCI6ImRjLXVzZXItY2xpZW50Iiwic2Vzc2lvbl9zdGF0ZSI6ImZlMDc1ODhjLTc3Y2EtNDYxMy1iMmYwLWY2MThhZjk0ZTY0MyIsImFjciI6IjEiLCJhbGxvd2VkLW9yaWdpbnMiOlsiKiJdLCJyZWFsbV9hY2Nlc3MiOnsicm9sZXMiOlsib2ZmbGluZV9hY2Nlc3MiLCJkZWZhdWx0LXJvbGVzLWZpZWRpZ2l0YWxjcmVkaXQiLCJ1bWFfYXV0aG9yaXphdGlvbiJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoib3BlbmlkIHByb2ZpbGUgZW1haWwiLCJzaWQiOiJmZTA3NTg4Yy03N2NhLTQ2MTMtYjJmMC1mNjE4YWY5NGU2NDMiLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwicHJlZmVycmVkX3VzZXJuYW1lIjoiZGN1c2VyIn0.EViexk-6kFUUzwaQU09zJ8CImcLCv8Yp2TddIjT14fGUcAnIC73aDZBMBzsxdmR79e3tU5JrDwTPdGZC67cae90joq7iA7rifOI_oixPRDcBo4Bcg4dipNYFYN--mWHqxpk6PG5bpzP4CU6bys0ywDmroE-4VsYdB08m0_AhPQCWFuJhlnf92XV7wcD7-aTI3KimXUK7RFPNT2z_bpWcknm0ZG9EhtSAbv7fH8inM6_Ydfywfaonx20uNvZIfM3S8NTNmWBK1Yb4KPycimOL2aSFmkFhNDx_YmHIPxek6Y8j2nHydm-FxcZr43BelLVHSdcMQTQY55ASX20QbEOJYw";
            $accion_usuario = $_SESSION["session_informacion"]["login"];
            $codigo_usuario = $_SESSION["session_informacion"]["codigo"];
            $accion_fecha = date('Y-m-d H:i:s');

            $resultado_soa_fie = $this->mfunciones_generales->Cliente_SOA_FIE_Generico(
                $token
                , $api_credit["conf_credit_nro_uri"]
                , $parametros
                , $accion_usuario
                , $accion_fecha);

            $res["ws_httpcode"]=$resultado_soa_fie->ws_httpcode;

            /**
             * Forzar el codigo, borrar para produccion
             */
            $resultado_soa_fie->ws_httpcode = 200;
            //$resultado_soa_fie->ws_httpcode = 404;

            if($resultado_soa_fie->ws_httpcode==200){
                $respuesta =  1;
                /**
                 * arreglo para pruebas
                 */
                $respapi["transactionId"] = "nostrud in";
                $respapi["result"] = array(
                    "disbursedAmount" => rand(10000,20000),
                    "message" => "La operación N pertenece al cliente con CI X en la APP. En el CORE pertenece al cliente Y con CI Z.",
                    "typeMessage" => "INFO"
                );
                $respapi["timestamp"] = "1952-10-07T11:34:58.220Z";
                /**
                 * para produccion
                 */
                //$respapi = $resultado_soa_fie->ws_result;

            }else if($resultado_soa_fie->ws_httpcode==500){
                $respuesta =  3;
            }else{
                $respuesta =  0;
            }
            /*
            echo "<pre>";
            print_r($resultado_soa_fie->ws_httpcode);
            print_r($resultado_soa_fie);
            echo "</pre>";
            exit;
            */
            //$respuesta = 3;

            $res["customerDocumentNumber"] = $customerDocumentNumber;
            $res["id"] = $id;
            $res["creditOperation"] = $creditOperation;

            switch ($respuesta){
                case 1:
                    $res["res"] = $respuesta;
                    $res["msg"] = "ok";
                    $res["respapi"]=$respapi;
                    break;
                case 2:
                    $res["res"] = 2;
                    $res["msg"] = "[Error] - No se encuentra datos para el número de operación";
                    break;
                case 3:
                    $res["res"] = 3;
                    $res["msg"] = "[Error] - Ocurrió un error la momento de realizar la consulta";
                    break;
                default:
                    $res["res"] = 0;
                    $res["msg"] = "[Error] - Error desconocido";
                    break;
            }

        }else{
            $res["res"] = 1;
            $res["msg"] = "[Error] - Error desconocido";
        }

        $arr = json_encode($res);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($arr);
    }

    public function DesembCOBISForm() {

        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_microcreditos');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');

        $this->formulario_logica_general->DefinicionValidacionFormulario();

        // 0=Insert    1=Update

        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }

        $codigo = $this->input->post('codigo', TRUE);
        $codigo_tipo_persona = $this->input->post('codigo_tipo_persona', TRUE);

        switch ((int)$codigo_tipo_persona) {
            case 6:

                // Solicitud de Crédito
                $this->load->model('mfunciones_microcreditos');

                $arrProspecto = $this->mfunciones_microcreditos->BandejaSupervisionLeads(-1, ' AND sc.sol_id=' . $codigo);
                $arrProspecto[0]['prospecto_id'] = $arrProspecto[0]['sol_id'];
                $arrProspecto[0]['camp_id'] = $arrProspecto[0]['tipo_persona_id'];
                $arrProspecto[0]['camp_nombre'] = $arrProspecto[0]['tipo_persona_nombre'];
                $arrProspecto[0]['usuario_nombre'] = $arrProspecto[0]['ejecutivo_nombre'];
                $arrProspecto[0]['prospecto_jda_eval'] = $arrProspecto[0]['sol_jda_eval'];
                $arrProspecto[0]['prospecto_jda_eval_texto'] = $arrProspecto[0]['sol_jda_eval_texto'];
                $arrProspecto[0]['general_solicitante'] = $arrProspecto[0]["sol_primer_nombre"] . ' ' . $arrProspecto[0]["sol_primer_apellido"] . ' ' . $arrProspecto[0]["sol_segundo_apellido"];
                $arrProspecto[0]['general_ci'] = $arrProspecto[0]["sol_ci"] . ' ' . $arrProspecto[0]["sol_complemento"] . ' ' . ((int)$arrProspecto[0]['sol_extension']==-1 ? '' : $this->mfunciones_generales->GetValorCatalogoDB($arrProspecto[0]['sol_extension'], 'cI_lugar_emisionoextension'));
                $arrProspecto[0]['prospecto_consolidar_fecha'] = $arrProspecto[0]['sol_consolidado_fecha'];

                $arrProspecto[0]['registro_num_proceso'] = $arrProspecto[0]['sol_num_proceso'];
                $arrProspecto[0]['prospecto_jda_eval_usuario'] = $arrProspecto[0]['sol_jda_eval_usuario'];
                $arrProspecto[0]['prospecto_jda_eval_fecha'] = $arrProspecto[0]['sol_jda_eval_fecha'];

                $arrProspecto[0]['prospecto_etapa'] = ((int)$arrProspecto[0]['sol_jda_eval']==1 ? 22 : 5);

                $sol_cred = new stdClass();

                $sol_cred->sol_id = $arrProspecto[0]['sol_id'];
                $sol_cred->sol_moneda = $arrProspecto[0]['sol_moneda'];
                $sol_cred->sol_monto = $arrProspecto[0]['sol_monto'];

                break;

            default:

                // Datos del Prospecto
                $arrProspecto = $this->mfunciones_logica->ObtenerInfoProspecto($codigo);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrProspecto);

                $arrProspecto[0]['registro_num_proceso'] = $arrProspecto[0]['prospecto_num_proceso'];

                // Check si el prospecto fue creado a través de una solicitud de crédito
                $sol_cred = $this->mfunciones_microcreditos->ObtenerSol_from_Pros($codigo);

                break;
        }

        if(!isset($arrProspecto[0]))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }

        // Validar si la etapa esta como "JDA Aprobado" para permitir el registro de Desembolso en COBIS
        if((int)$arrProspecto[0]['prospecto_etapa'] != 22)
        {
            js_error_div_javascript('El registro debe tener la evaluación del JDA como "Aprobado" para poder registrar el ' . $this->lang->line('prospecto_desembolso'));
            exit();
        }

        $data["sol_cred"] = $sol_cred;

        $data["codigo_tipo_persona"] = $codigo_tipo_persona;

        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());

        $data["arrRespuesta"] = $arrProspecto;

        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

        $this->load->view('bandeja_verificacion_requisitos/view_desembolso_cobis', $data);
    }

    public function DesembCOBIS_Guardar() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_microcreditos');

        if(!isset($_POST['estructura_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }

        $codigo_prospecto = $this->input->post('estructura_id', TRUE);
        $prospecto_desembolso_monto = $this->input->post('prospecto_desembolso_monto', TRUE);

        $codigo_tipo_persona = $this->input->post('codigo_tipo_persona', TRUE);

        if((int)$prospecto_desembolso_monto < 1)
        {
            js_error_div_javascript('Debe registrar el ' . $this->lang->line('prospecto_desembolso_monto'));
            exit();
        }

        $accion_usuario = $_SESSION["session_informacion"]["login"];
        $codigo_usuario = $_SESSION["session_informacion"]["codigo"];
        $accion_fecha = date('Y-m-d H:i:s');

        switch ((int)$codigo_tipo_persona) {
            case 6:

                $arrResultado3 = $this->mfunciones_microcreditos->VerificaSolicitudConsolidado($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                if (!isset($arrResultado3[0]))
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }

                $registro_num_proceso = (int)$arrResultado3[0]['sol_num_proceso'];
                if($this->mfunciones_microcreditos->ValidarNumOperacion($registro_num_proceso))
                {
                    js_error_div_javascript($this->lang->line('registro_num_proceso_error_desemb'));
                    exit();
                }

                $this->mfunciones_microcreditos->update_DesembCOBIS_Sol($codigo_prospecto, $prospecto_desembolso_monto, $codigo_usuario, $accion_usuario, $accion_fecha);

                break;

            default:

                /***** REGIONALIZACIÓN: Valida si el prospecto pertenece a la región ******/
                if(!$this->mfunciones_generales->VerificaProspectoRegion($codigo_prospecto))
                {
                    js_error_div_javascript($this->lang->line('regionaliza_NoRegion'));
                    exit();
                }

                $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                if (!isset($arrResultado3[0]))
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }

                $registro_num_proceso = (int)$arrResultado3[0]['prospecto_num_proceso'];
                if($this->mfunciones_microcreditos->ValidarNumOperacion($registro_num_proceso))
                {
                    js_error_div_javascript($this->lang->line('registro_num_proceso_error_desemb'));
                    exit();
                }

                $this->mfunciones_microcreditos->update_DesembCOBIS($codigo_prospecto, $codigo_usuario, $accion_usuario, $accion_fecha);

                $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 24, $arrResultado3[0]['prospecto_etapa'], $accion_usuario, $accion_fecha, 2);
                /***  REGISTRAR SEGUIMIENTO ***/
                $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, 24, 12, $this->lang->line('prospecto_desembolso') . '. Fin del flujo.', $accion_usuario, $accion_fecha);

                break;
        }

        $this->Bandeja_Ver();
    }
}