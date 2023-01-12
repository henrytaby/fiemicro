<?php
/**
 * @file 
 * Codigo que implementa el controlador para EL DETALLE DEL REGISTRO
 * @brief  EJECUTIVOS DE CUENTA
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para la gestión del DETALLE DEL REGISTRO
 * @brief EJECUTIVOS DE CUENTA
 * @class Conf_catalogo_controller 
 */
class Detalle_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 0;

    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */

    public function InformeLead_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
                
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // Se captura el valor
            $estructura_id = preg_replace('/[^a-zA-Z0-9_ .]/s', '', $this->input->post('codigo', TRUE));
            
            //if(!$this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,9))
            if(!1==1)
            {
                js_error_div_javascript($this->lang->line('NoAutorizadoPerfil'));
                exit();
            }
            else 
            {
                // Detalle del Prospecto
                $arrResultado3 = $this->mfunciones_logica->VerificaProspectoRechazo($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
                
                $arrResultado = $this->mfunciones_logica->ObtenerListaVersiones($estructura_id, -1);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                
                $data["arrRespuesta"] = $arrResultado;
                
                $data['general_solicitante'] = $arrResultado3[0]['general_solicitante'];
                
                $data['estructura_id'] = $estructura_id;
                
                $this->load->view('prospecto/view_historial_informe', $data);
            }
        }
    }
    
    public function Informe_Generar() {
        $this->lang->load('general', 'castellano'); // Archivo de Lenguaje
        $this->load->model('mfunciones_generales'); // Funciones Generales
        $this->load->model('mfunciones_logica');    // Capa de Datos
        
        //if(!$this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,9))
        if(!1==1)
        {
            js_error_div_javascript($this->lang->line('NoAutorizadoPerfil'));
            exit();
        }
        
        $parametros = json_decode(urldecode($this->input->get_post("parametros")));
        
        $codigo_prospecto = $parametros->codigo;
        $codigo_version = $parametros->version;
        $tipo = $parametros->tipo;
        $repote = $parametros->repote;
        $correlativo = $parametros->correlativo;
        
        /***** REGIONALIZACIÓN: Valida si el prospecto pertenece a la región ******/
        /*
        if(!$this->mfunciones_generales->VerificaProspectoRegion($codigo_prospecto))
        {
            js_error_div_javascript($this->lang->line('regionaliza_NoRegion'));
            exit();
        }
        */
        
        if($tipo == 'actual')
        {
            // Versión Actual
            $html_resultado = $this->mfunciones_generales->GeneraInformeLead($codigo_prospecto, 'completo');
        }
        elseif($tipo == 'version')
        {
            // Versión Histórica
            $arrVersion = $this->mfunciones_logica->ObtenerListaVersiones($codigo_prospecto, $codigo_version);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrVersion);
            
            if(isset($arrVersion[0])) 
            {
                $html_resultado = $arrVersion[0]['version_contenido'];
            }
            else
            {
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            }
            
            // Se remplaza el título por el número de versión
            $html_resultado = str_replace('<!--|version|-->', '<br />VERSIÓN N° ' . $correlativo, $html_resultado);
            
        }
        
        if($repote == 'pdf')
        {
            $this->mfunciones_generales->GeneraPDF_Informe($html_resultado);
            return;
        }
        elseif($repote == 'excel')
        {
            $html_resultado = str_replace('reportes/logo.jpg', 'reportes/logo_pequeno.jpg', $html_resultado);
            $this->mfunciones_generales->GeneraExcel_Informe($html_resultado);
            return;
        }
    }
    
    public function DocumentosProspectos_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        // Se captura el valor
        $estructura_id = $this->input->post('codigo', TRUE);

        $codigo_tipo_persona = $this->input->post('codigo_tipo_persona', TRUE);
        
        switch ((int)$codigo_tipo_persona) {
            
            // Solicitud de Crédito
            case 6:
                
                $this->load->model('mfunciones_microcreditos');
                
                $arrResultado = $this->mfunciones_microcreditos->SolObtenerDocumentosDigitalizarAppAux((int)$codigo_tipo_persona);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "documento_id" => $value["documento_id"],
                            "documento_detalle" => $value["documento_nombre"],
                            "documento_digitalizado" => $this->mfunciones_microcreditos->GetInfoSolicitudDigitalizadoPDF($estructura_id, $value["documento_id"], 'existe'),
                            "documento_observado" => $this->mfunciones_microcreditos->SolVerDocumentoObservado($estructura_id, $value["documento_id"], (int)$codigo_tipo_persona)
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }
                else 
                {
                    $lst_resultado[0] = $arrResultado;
                }

                $DatosProspecto = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($estructura_id);
                $data['general_solicitante'] = $DatosProspecto[0]['sol_nombre_completo'];
                
                break;

            // Normalizador/Cobrador
            case 13:
                
                $this->load->model('mfunciones_microcreditos');
                $this->load->model('mfunciones_cobranzas');
                
                $arrResultado = $this->mfunciones_microcreditos->SolObtenerDocumentosDigitalizarAppAux((int)$codigo_tipo_persona);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                
                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "documento_id" => $value["documento_id"],
                            "documento_detalle" => $value["documento_nombre"],
                            "documento_digitalizado" => $this->mfunciones_cobranzas->GetInfoRegistroDigitalizadoPDF($estructura_id, $value["documento_id"], 'existe'),
                            "documento_observado" => $this->mfunciones_cobranzas->RegistroVerificaDocumentoObservado($estructura_id, $value["documento_id"], (int)$codigo_tipo_persona)
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }
                else 
                {
                    $lst_resultado[0] = $arrResultado;
                }

                $DatosProspecto = $this->mfunciones_cobranzas->VerificaNormConsolidado($estructura_id);
                $data['general_solicitante'] = $DatosProspecto[0]['norm_primer_nombre'] . ' ' . $DatosProspecto[0]['norm_segundo_nombre'] . ' ' . $DatosProspecto[0]['norm_primer_apellido'] . ' ' . $DatosProspecto[0]['norm_segundo_apellido'];
                
                break;
                
            default:

                // Detalle del Prospecto
                $arrResultado3 = $this->mfunciones_logica->VerificaProspectoRechazo($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                $arrResultado = $this->mfunciones_logica->ObtenerDocumentosDigitalizar($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "documento_id" => $value["documento_id"],
                            "documento_detalle" => $value["documento_nombre"],
                            "documento_digitalizado" => $this->mfunciones_generales->GetInfoDigitalizado($estructura_id, $value["documento_id"], 'existe'),
                            "documento_observado" => $this->mfunciones_generales->VerificaDocumentoObservado($estructura_id, $value["documento_id"])
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }
                else 
                {
                    $lst_resultado[0] = $arrResultado;
                }
                $data['general_solicitante'] = $arrResultado3[0]['general_solicitante'];

                break;
        }

        $data['estructura_id'] = $estructura_id;
        
        $data["prospecto_rechazado"] = 0;
        $data["arrRespuesta"] = $lst_resultado;
        
        $data["codigo_tipo_persona"] = $codigo_tipo_persona;
        
        // Variable que indica donde volver
        $_SESSION['funcion_ver_documento'] = 'Ajax_CargarAccion_DocumentoProspecto';

        $this->load->view('prospecto/view_documento_ver', $data);
    }
    
    public function DocumentosProspectoHistorico_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');

        if(!isset($_POST['codigo_documento']) || !isset($_POST['codigo_prospecto']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // Se captura el valor
            $documento_codigo = $this->input->post('codigo_documento', TRUE);
            $prospecto_codigo = $this->input->post('codigo_prospecto', TRUE);
            
            $codigo_tipo_persona = $this->input->post('codigo_tipo_persona', TRUE);
            
            if(!$this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
            {
                js_error_div_javascript($this->lang->line('NoAutorizadoPerfil'));
                exit();
            }
            else
            {
                // Datos del Documento
                $arrResultado1 = $arrResultado = $this->mfunciones_logica->ObtenerDocumento($documento_codigo);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
                
                if (!isset($arrResultado1[0])) 
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }
                
                $documento_nombre = $arrResultado1[0]['documento_nombre'];
                
                switch ((int)$codigo_tipo_persona) {
                    
                    // Solicitud de Crédito
                    case 6:
                        $this->load->model('mfunciones_microcreditos');

                        $arrResultado2 = $this->mfunciones_microcreditos->SolListaDocumentosDigitalizar($prospecto_codigo, $documento_codigo);        
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                        if (isset($arrResultado2[0])) 
                        {
                            $i = 0;

                            foreach ($arrResultado2 as $key => $value) 
                            {

                                $ruta = RUTA_SOLCREDITOS;
                                $documento = $value['prospecto_carpeta'] . '/' . $value['prospecto_carpeta'] . '_' . $value['solicitud_credito_documento_pdf'];

                                $path = $ruta . $documento;

                                if(file_exists($path))
                                {
                                    $item_valor = array(
                                        "prospecto_documento_id" => $value["solicitud_credito_documento_id"],
                                        "documento_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["accion_fecha"]),
                                    );
                                    $lst_resultado[$i] = $item_valor;

                                    $i++;
                                }
                            }
                        }
                        else 
                        {
                            $lst_resultado[0] = $arrResultado2;
                        }
                        
                        $DatosProspecto = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($prospecto_codigo);
                        $data['general_solicitante'] = $DatosProspecto[0]['sol_nombre_completo'];
                        
                        break;

                    // Normalizador/Cobrador
                    case 13:
                        
                        $this->load->model('mfunciones_cobranzas');
                        
                        $arrResultado2 = $this->mfunciones_cobranzas->RegListaDocumentosDigitalizar($prospecto_codigo, $documento_codigo, (int)$codigo_tipo_persona);        
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                        if (isset($arrResultado2[0])) 
                        {
                            $i = 0;

                            foreach ($arrResultado2 as $key => $value) 
                            {

                                $ruta = $this->lang->line('ruta_cobranzas');
                                $documento = $value['prospecto_carpeta'] . '/' . $value['prospecto_carpeta'] . '_' . $value['registro_documento_pdf'];

                                $path = $ruta . $documento;

                                if(file_exists($path))
                                {
                                    $item_valor = array(
                                        "prospecto_documento_id" => $value["registro_documento_id"],
                                        "documento_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["accion_fecha"]),
                                    );
                                    $lst_resultado[$i] = $item_valor;

                                    $i++;
                                }
                            }
                        }
                        else 
                        {
                            $lst_resultado[0] = $arrResultado2;
                        }
                        
                        $DatosProspecto = $this->mfunciones_cobranzas->VerificaNormConsolidado($prospecto_codigo);
                        $data['general_solicitante'] = $DatosProspecto[0]['norm_primer_nombre'] . ' ' . $DatosProspecto[0]['norm_segundo_nombre'] . ' ' . $DatosProspecto[0]['norm_primer_apellido'] . ' ' . $DatosProspecto[0]['norm_segundo_apellido'];
                        
                        break;
                        
                    default:
                        
                        $arrResultado2 = $this->mfunciones_logica->ListaDocumentosDigitalizar($prospecto_codigo, $documento_codigo);        
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                        if (isset($arrResultado2[0])) 
                        {
                            $i = 0;

                            foreach ($arrResultado2 as $key => $value) 
                            {

                                $ruta = RUTA_PROSPECTOS;
                                $documento = $value['prospecto_carpeta'] . '/' . $value['prospecto_carpeta'] . '_' . $value['prospecto_documento_pdf'];

                                $path = $ruta . $documento;

                                if(file_exists($path))
                                {
                                    $item_valor = array(
                                        "prospecto_documento_id" => $value["prospecto_documento_id"],
                                        "documento_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["accion_fecha"]),
                                    );
                                    $lst_resultado[$i] = $item_valor;

                                    $i++;
                                }
                            }
                        }
                        else 
                        {
                            $lst_resultado[0] = $arrResultado2;
                        }
                        
                        // Detalle del Prospecto
                        $arrResultado3 = $this->mfunciones_logica->VerificaProspectoRechazo($prospecto_codigo);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                        $data['general_solicitante'] = $arrResultado3[0]['general_solicitante'];
                        
                        break;
                }
                
                $data["arrRespuesta"] = $lst_resultado;
                
                $data["documento_codigo"] = $documento_codigo;
                $data["documento_nombre"] = $documento_nombre;
                $data['prospecto_codigo'] = $prospecto_codigo;
                $data['codigo_tipo_persona'] = $codigo_tipo_persona;

                $this->load->view('prospecto/view_documento_historico_ver', $data);
            }
        }
    }
    
    public function DocumentosProspectos_Ver_Unico() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
                
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // Se captura el valor
            $estructura_id = $this->input->post('codigo', TRUE);
            
            $codigo_tipo_persona = $this->input->post('codigo_tipo_persona', TRUE);
            
            if(!$this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
            {
                js_error_div_javascript($this->lang->line('NoAutorizadoPerfil'));
                exit();
            }
            else 
            {
                switch ((int)$codigo_tipo_persona) {
                    
                    // Solicitud de Crédito
                    case 6:
                        $this->load->model('mfunciones_microcreditos');

                        $arrResultado = $this->mfunciones_microcreditos->SolObtenerDocumentosDigitalizarApp((int)$codigo_tipo_persona);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                        if (isset($arrResultado[0])) 
                        {
                            $i = 0;

                            foreach ($arrResultado as $key => $value) 
                            {
                                $item_valor = array(
                                    "documento_id" => $value["documento_id"],
                                    "documento_detalle" => $value["documento_nombre"],
                                    "documento_digitalizado" => $this->mfunciones_microcreditos->GetInfoSolicitudDigitalizadoPDF($estructura_id, $value["documento_id"], 'existe')
                                );
                                $lst_resultado[$i] = $item_valor;

                                $i++;
                            }
                        }
                        else 
                        {
                            $lst_resultado[0] = $arrResultado;
                        }

                        $DatosProspecto = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($estructura_id);
                        $data['general_solicitante'] = $DatosProspecto[0]['sol_nombre_completo'];
                        
                        break;

                    // Normalizador/Cobrador
                    case 13:

                        $this->load->model('mfunciones_microcreditos');
                        $this->load->model('mfunciones_cobranzas');

                        $arrResultado = $this->mfunciones_microcreditos->SolObtenerDocumentosDigitalizarApp((int)$codigo_tipo_persona);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                        if (isset($arrResultado[0])) 
                        {
                            $i = 0;

                            foreach ($arrResultado as $key => $value) 
                            {
                                $item_valor = array(
                                    "documento_id" => $value["documento_id"],
                                    "documento_detalle" => $value["documento_nombre"],
                                    "documento_digitalizado" => $this->mfunciones_cobranzas->GetInfoRegistroDigitalizadoPDF($estructura_id, $value["documento_id"], 'existe')
                                );
                                $lst_resultado[$i] = $item_valor;

                                $i++;
                            }
                        }
                        else 
                        {
                            $lst_resultado[0] = $arrResultado;
                        }

                        $DatosProspecto = $this->mfunciones_cobranzas->VerificaNormConsolidado($estructura_id);
                        $data['general_solicitante'] = $DatosProspecto[0]['norm_primer_nombre'] . ' ' . $DatosProspecto[0]['norm_segundo_nombre'] . ' ' . $DatosProspecto[0]['norm_primer_apellido'] . ' ' . $DatosProspecto[0]['norm_segundo_apellido'];
                        $data['norm_consolidado'] = (int)$DatosProspecto[0]['norm_consolidado'];
                        
                        break;
                        
                    default:
                        
                        // Detalle del Prospecto
                        $arrResultado3 = $this->mfunciones_logica->VerificaProspectoRechazo($estructura_id);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                        $arrResultado = $this->mfunciones_logica->ObtenerDocumentosDigitalizarApp($estructura_id);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                        if (isset($arrResultado[0])) 
                        {
                            $i = 0;

                            foreach ($arrResultado as $key => $value) 
                            {
                                $item_valor = array(
                                    "documento_id" => $value["documento_id"],
                                    "documento_detalle" => $value["documento_nombre"],
                                    "documento_digitalizado" => $this->mfunciones_generales->GetInfoDigitalizado($estructura_id, $value["documento_id"], 'existe')
                                );
                                $lst_resultado[$i] = $item_valor;

                                $i++;
                            }
                        } 
                        else 
                        {
                            $lst_resultado[0] = $arrResultado;
                        }
                        
                        $data['general_solicitante'] = $arrResultado3[0]['general_solicitante'];
                        
                        break;
                }
                
                $data["prospecto_rechazado"] = 0;
                
                $data["arrRespuesta"] = $lst_resultado;
                
                $data['estructura_id'] = $estructura_id;

                $data['codigo_tipo_persona'] = $codigo_tipo_persona;
                
                // Variable que indica donde volver
                $_SESSION['funcion_ver_documento'] = 'Ajax_CargarAccion_DocumentoProspectoReporte';
                
                $this->load->view('prospecto/view_documento_ver_unico', $data);
            }
        }
    }
    
    public function ObservaDoc_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
                
        if(!isset($_POST['codigo_documento']) || !isset($_POST['codigo_prospecto']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // Se captura el valor
            $documento_codigo = $this->input->post('codigo_documento', TRUE);
            $prospecto_codigo = $this->input->post('codigo_prospecto', TRUE);
            
            $codigo_tipo_persona = $this->input->post('codigo_tipo_persona', TRUE);
            
            if(!$this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_OBSERVAR_DOCUMENTOS))
            {
                js_error_div_javascript($this->lang->line('NoAutorizadoPerfil'));
                exit();
            }
            else
            {
                // Datos del Documento
                $arrResultado1 = $arrResultado = $this->mfunciones_logica->ObtenerDocumento($documento_codigo);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
                
                if (!isset($arrResultado1[0])) 
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }
                
                $documento_nombre = $arrResultado1[0]['documento_nombre'];
                
                $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
                $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
                
                $data["documento_codigo"] = $documento_codigo;
                $data["documento_nombre"] = $documento_nombre;                
                $data['prospecto_codigo'] = $prospecto_codigo;
                
                $data['codigo_tipo_persona'] = $codigo_tipo_persona;

                $this->load->view('prospecto/view_observacion_form', $data);
            }
        }
    }
    
    public function ObservaDoc_Guardar() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
                
        if(!isset($_POST['documento_codigo']) || !isset($_POST['prospecto_codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // PASO 1: Se capturan los valores
            
            $documento_codigo = $this->input->post('documento_codigo', TRUE);
            $prospecto_codigo = $this->input->post('prospecto_codigo', TRUE);
            $prospecto_justificar = $this->input->post('prospecto_justificar', TRUE);
            
            $codigo_tipo_persona = $this->input->post('codigo_tipo_persona', TRUE);
            
            // PASO 2: Se valida
            
            if($prospecto_justificar == "")
            {
                js_error_div_javascript($this->lang->line('FormularioIncompleto'));
                exit();
            }
            
            // Datos del Documento
            $arrResultado1 = $arrResultado = $this->mfunciones_logica->ObtenerDocumento($documento_codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
            
            $documento_nombre = $arrResultado1[0]['documento_nombre'];
            
            // PASO 3: SE REGISTRA LA OBSERVACIÓN
            
            $nombre_usuario = $_SESSION["session_informacion"]["login"];
            $fecha_actual = date('Y-m-d H:i:s');
            
            // Observación      1 = Cumplimiento        2 = Legal
            $tipo_observacion = 1;
            
            // Rol 6 = Analista Legal            
            if($_SESSION["session_informacion"]["rol_codigo"] == 6)
            {
                $tipo_observacion = 2;
            }
            
            // INSERTAR EN LA TABLA OBSERVACION_DOCUMENTO
            
            switch ((int)$codigo_tipo_persona) {
                
                // Solicitud de Crédito
                case 6:
                    $this->load->model('mfunciones_microcreditos');

                    $this->mfunciones_microcreditos->SolInsertarObservacionDoc($prospecto_codigo, (int)$codigo_tipo_persona, $_SESSION["session_informacion"]["codigo"], $documento_codigo, $tipo_observacion, $fecha_actual, $prospecto_justificar, $nombre_usuario, $fecha_actual);
                    
                    break;

                // Normalizador/Cobrador
                
                case 13:
                    
                    $this->load->model('mfunciones_cobranzas');

                    $this->mfunciones_cobranzas->RegInsertarObservacionDoc($prospecto_codigo, (int)$codigo_tipo_persona, $_SESSION["session_informacion"]["codigo"], $documento_codigo, $tipo_observacion, $fecha_actual, $prospecto_justificar, $nombre_usuario, $fecha_actual);
                    
                    break;
                
                default:
                    
                    $this->mfunciones_logica->InsertarObservacionDoc($prospecto_codigo, $_SESSION["session_informacion"]["codigo"], $documento_codigo, $tipo_observacion, $fecha_actual, $prospecto_justificar, $nombre_usuario, $fecha_actual);
                    
                    break;
            }
            
            if(isset($_SESSION['aux_flag_reporte_return']))
            {
                if($_SESSION['aux_flag_reporte_return'] == 1)
                {
                    $_SESSION['direccion_bandeja_actual'] = 'Consultas/Ver';
                }
            }
            
            js_invocacion_javascript("Ajax_CargarOpcionMenu(' " . $_SESSION['direccion_bandeja_actual'] . "')");
            
            exit();
        }
    }
    
    public function ObservaDoc_Remitir() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');

        if(!isset($_POST['prospecto_codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // PASO 1: Se capturan los valores
            $prospecto_codigo = $this->input->post('prospecto_codigo', TRUE);            
            
            $codigo_tipo_persona = $this->input->post('codigo_tipo_persona', TRUE);
            
            // PASO 2: Obtener el listado de los Documentos Observados
            
            $listado_documentos = 'Documentos observados: <br /><br />';
            
            $nombre_usuario = $_SESSION["session_informacion"]["login"];
            $fecha_actual = date('Y-m-d H:i:s');

            // Observación      1 = Cumplimiento        2 = Legal
            $tipo_observacion = 1;
            
            $codigo_perfil_app = 1;  // <-- 1=Oficial de Negocios   3=Normalizador/Cobrador
            
            switch ((int)$codigo_tipo_persona) {
                
                // Solicitud de Crédito
                case 6:
                    
                    $this->load->model('mfunciones_microcreditos');
                    
                    $arrResultado = $this->mfunciones_microcreditos->SolObtenerDocumentosDigitalizarApp((int)$codigo_tipo_persona);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                    if (isset($arrResultado[0])) 
                    {
                        foreach ($arrResultado as $key => $value) 
                        {
                            if($this->mfunciones_microcreditos->SolVerDocumentoObservado($prospecto_codigo, $value["documento_id"], (int)$codigo_tipo_persona))
                            {
                                $listado_documentos .= $value["documento_nombre"]. '<br />';
                            }
                        }
                    }

                    // CAMBIAR EL ESTADO DEL PROSPECTO PARA DESCONSOLIDAR Y OBSERVAR            
                    $this->mfunciones_microcreditos->ObservarDocSolicitud($nombre_usuario, $fecha_actual, $prospecto_codigo);

                    // REMITIR CORREO ELECTRÓNICO DE OBSERVACIÓN AL EJECUTIVO DE CUENTAS

                    // Listado Detalle Empresa por el código de Prospecto
                    $arrResultadoAux = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($prospecto_codigo);

                    $correo_ejecutivo = $arrResultadoAux[0]['agente_correo'];
                    $nombre_ejecutivo = $arrResultadoAux[0]['agente_nombre'];
                    
                    $arrResultado2[0] = array();
                    $arrResultado2[0]['prospecto_id'] = 'SOL_' . $prospecto_codigo . ' (Solicitud de Crédito)';
                    $arrResultado2[0]['empresa_nombre'] = $arrResultadoAux[0]['sol_nombre_completo'];
                    $arrResultado2[0]['empresa_categoria'] = 'Cliente';
                    $arrResultado2[0]['ejecutivo_asignado_nombre'] = $arrResultadoAux[0]['agente_nombre'];
                    $arrResultado2[0]['ejecutivo_asignado_contacto'] = $arrResultadoAux[0]['agente_telefono'];

                    $correo = $this->mfunciones_generales->EnviarCorreo('observar_documento_app', $correo_ejecutivo, $nombre_ejecutivo, $arrResultado2);

                    /* Se Envía a la APP la Notificación por Push   tipo_notificacion, $tipo_visita, $codigo_visita */
                    $this->mfunciones_generales->EnviarNotificacionPush(3, 1, $prospecto_codigo);
                    
                    break;

                // Normalizador/Cobrador
                case 13:
                    
                    $this->load->model('mfunciones_microcreditos');
                    $this->load->model('mfunciones_cobranzas');
                    
                    $codigo_perfil_app = 3;  // <-- 1=Oficial de Negocios   3=Normalizador/Cobrador
                    
                    $arrResultado = $this->mfunciones_microcreditos->SolObtenerDocumentosDigitalizarApp((int)$codigo_tipo_persona);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                    if (isset($arrResultado[0])) 
                    {
                        foreach ($arrResultado as $key => $value) 
                        {
                            if($this->mfunciones_cobranzas->RegistroVerDocumentoObservado($prospecto_codigo, $value["documento_id"], (int)$codigo_tipo_persona))
                            {
                                $listado_documentos .= $value["documento_nombre"]. '<br />';
                            }
                        }
                    }

                    // CAMBIAR EL ESTADO DEL PROSPECTO PARA DESCONSOLIDAR Y OBSERVAR            
                    $this->mfunciones_cobranzas->ObservarDocRegistro($nombre_usuario, $fecha_actual, $prospecto_codigo);

                    // REMITIR CORREO ELECTRÓNICO DE OBSERVACIÓN AL EJECUTIVO DE CUENTAS

                    // Listado Detalle Empresa por el código de Prospecto
                    $arrResultadoAux = $this->mfunciones_cobranzas->DatosRegistroEmail($prospecto_codigo);

                    $correo_ejecutivo = $arrResultadoAux[0]['agente_correo'];
                    $nombre_ejecutivo = $arrResultadoAux[0]['agente_nombre'];
                    
                    $arrResultado2[0] = array();
                    $arrResultado2[0]['prospecto_id'] = $this->lang->line('norm_prefijo') . $prospecto_codigo . ' (Normalizador/Cobrador)';
                    $arrResultado2[0]['empresa_nombre'] = $arrResultadoAux[0]['norm_nombre_completo'];
                    $arrResultado2[0]['empresa_categoria'] = 'Cliente';
                    $arrResultado2[0]['ejecutivo_asignado_nombre'] = $arrResultadoAux[0]['agente_nombre'];
                    $arrResultado2[0]['ejecutivo_asignado_contacto'] = $arrResultadoAux[0]['agente_telefono'];

                    $correo = $this->mfunciones_generales->EnviarCorreo('observar_documento_app', $correo_ejecutivo, $nombre_ejecutivo, $arrResultado2);

                    /* Se Envía a la APP la Notificación por Push   tipo_notificacion, $tipo_visita, $codigo_visita */
                    $this->mfunciones_generales->EnviarNotificacionPush(3, 1, $prospecto_codigo);
                    
                    break;
                    
                default:
                    
                    $arrResultado = $this->mfunciones_logica->ObtenerDocumentosDigitalizar($prospecto_codigo);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                    if (isset($arrResultado[0])) 
                    {
                        foreach ($arrResultado as $key => $value) 
                        {
                            if($this->mfunciones_generales->VerificaDocumentoObservado($prospecto_codigo, $value["documento_id"]))
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
                    $this->mfunciones_logica->ObservarDocProspecto($nombre_usuario, $fecha_actual, $prospecto_codigo);

                    // PASO 5: REMITIR CORREO ELECTRÓNICO DE OBSERVACIÓN AL EJECUTIVO DE CUENTAS

                    // Listado Detalle Empresa por el código de Prospecto
                    $arrResultado2 = $this->mfunciones_generales->GetDatosEmpresaCorreo($prospecto_codigo);

                    $correo_ejecutivo = $arrResultado2[0]['ejecutivo_asignado_correo'];
                    $nombre_ejecutivo = $arrResultado2[0]['ejecutivo_asignado_nombre'];
                    $arrResultado2[0]['empresa_nombre'] = 'Estudio de Crédito';
                    $arrResultado2[0]['empresa_categoria'] = 'flujo';

                    $correo = $this->mfunciones_generales->EnviarCorreo('observar_documento_app', $correo_ejecutivo, $nombre_ejecutivo, $arrResultado2, 0);

                    /* Se Envía a la APP la Notificación por Push   tipo_notificacion, $tipo_visita, $codigo_visita */
                    $this->mfunciones_generales->EnviarNotificacionPush(3, 1, $prospecto_codigo);

                    /***  REGISTRAR SEGUIMIENTO ***/
                    $this->mfunciones_logica->InsertSeguimientoProspecto($prospecto_codigo, 1, 2, $listado_documentos, $nombre_usuario, $fecha_actual);
                    
                    break;
            }
            
            $texto = sprintf($this->lang->line('prospecto_obs_doc_guardado'), $this->mfunciones_generales->GetValorCatalogo($codigo_perfil_app, 'tipo_perfil_app_singular'));

            if(!$correo)
            {
                $texto .= '<br />' . $this->lang->line('FormularioNoNotificacion') . ' (Instancia)';
            }

            $data['texto'] = $texto;
            
            $this->load->view('prospecto/view_observacion_guardado', $data);
        }
    }
    
    public function ProspectoDetalle() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // 0=Insert    1=Update
        
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            if(!$this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"], PERFIL_DETALLE_PROSPECTO))
            {
                js_error_div_javascript($this->lang->line('NoAutorizadoPerfil'));
                exit();
            }
            else 
            {
                $this->load->model('mfunciones_microcreditos');
                
                $codigo_prospecto = $this->input->post('codigo', TRUE);

                $arrResultado = $this->mfunciones_microcreditos->ListadoDetalleProspectoAux($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                
                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $nombre_depende = 'Titular';
                        
                        if($value["general_categoria"] == 2 && $value["general_depende"] != -1)
                        {
                            $arrPrincipal = $this->mfunciones_logica->GetProspectoNombreDepende($value["prospecto_id"]);
                            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrPrincipal);
                            
                            if (isset($arrResultado[0])) 
                            {
                                $nombre_depende = 'Unidad Familiar - Depende de ' . $arrResultado[0]['general_solicitante'] . ' (' . PREFIJO_PROSPECTO . $value["prospecto_id"] . ')';
                            }
                        }
                        
                        $item_valor = array(
                            "prospecto_id" => $value["prospecto_id"],
                            "camp_id" => $value["camp_id"],
                            "camp_nombre" => $value["camp_nombre"],
                            "ejecutivo_id" => $value["ejecutivo_id"],
                            "ejecutivo_nombre" => $value["ejecutivo_nombre"] . ((int)$value["ejecutivo_perfil_tipo"]==1 ? '' : ' (Perfil ' . $this->mfunciones_microcreditos->GetValorCatalogo($value["ejecutivo_perfil_tipo"], 'ejecutivo_perfil_tipo') . ')'),
                            "tipo_persona_codigo" => $value["tipo_persona_id"],
                            "tipo_persona_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["tipo_persona_id"], 'tipo_persona'),
                            "empresa_id" => $value["empresa_id"],
                            "fecha_derivada_etapa" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["fecha_derivada_etapa"]),
                            "tiempo_etapa" => $this->mfunciones_generales->TiempoEtapaProspecto($value["fecha_derivada_etapa"], $value["prospecto_etapa"]),
                            "prospecto_fecha_asignacion" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["prospecto_fecha_asignacion"]),
                            "prospecto_carpeta" => $value["prospecto_carpeta"],
                            "prospecto_etapa" => $value["prospecto_etapa"],
                            "etapa_nombre" => $value["etapa_nombre"],
                            "prospecto_checkin" => $value["prospecto_checkin"],
                            "prospecto_checkin_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["prospecto_checkin_fecha"]),
                            "prospecto_checkin_geo" => $value["prospecto_checkin_geo"],
                            "prospecto_consolidar_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["prospecto_consolidar_fecha"]),
                            "prospecto_consolidar_geo" => $value["prospecto_consolidar_geo"],
                            "prospecto_consolidado" => $value["prospecto_consolidado"],
                            "prospecto_observado_app" => $value["prospecto_observado_app"],
                            "prospecto_estado_actual" => $value["prospecto_estado_actual"],
                            "prospecto_observado" => $value["prospecto_observado"],
                            "cal_visita_ini" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["cal_visita_ini"]),
                            "cal_visita_fin" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["cal_visita_fin"]),
                            
                            "prospecto_evaluacion" => $value["prospecto_evaluacion"],
                            "general_categoria" => $value["general_categoria"],
                            "general_depende" => $value["general_depende"],
                            "general_depende_nombre" => $nombre_depende,
                            "general_solicitante" => $value["general_solicitante"],
                            "general_ci" => $value["general_ci"],
                            "general_ci_extension" => $value["general_ci_extension"],
                            "general_telefono" => $value["general_telefono"],
                            "general_email" => $value["general_email"],
                            "general_direccion" => $value["general_direccion"],
                            "general_actividad" => $value["general_actividad"],
                            "general_destino" => nl2br($value["general_destino"]),
                            "general_interes" => $this->mfunciones_generales->GetValorCatalogo($value["general_interes"], 'grado_interes'),
                            
                            "prospecto_jda_eval" => $value["prospecto_jda_eval"],
                            "prospecto_jda_eval_texto" => nl2br($value["prospecto_jda_eval_texto"]),
                            "prospecto_jda_eval_usuario" => $this->mfunciones_generales->getNombreUsuario($value["prospecto_jda_eval_usuario"]),
                            "prospecto_jda_eval_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["prospecto_jda_eval_fecha"]),
                            "prospecto_desembolso" => $value["prospecto_desembolso"],
                            "prospecto_desembolso_monto" => number_format($value["prospecto_desembolso_monto"], 2, ',', '.'),
                            "prospecto_desembolso_usuario" => $this->mfunciones_generales->getNombreUsuario($value["prospecto_desembolso_usuario"]),
                            "prospecto_desembolso_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["prospecto_desembolso_fecha"]),
                            "registro_num_proceso" => $value["prospecto_num_proceso"]
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }
                else 
                {
                    $lst_resultado[0] = $arrResultado;
                }
                
                $data["arrRespuesta"] = $lst_resultado;

                $this->load->view('prospecto/view_prospecto_detalle', $data);
            }
        }
    }
    
    public function Historial_Ver() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // 0=Insert    1=Update
        
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            if(!$this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"], PERFIL_HISTORIAL))
            {
                js_error_div_javascript($this->lang->line('NoAutorizadoPerfil'));
                exit();
            }
            else 
            {
                $codigo_prospecto = $this->input->post('codigo', TRUE);
                
                $codigo_tipo_persona = $this->input->post('codigo_tipo_persona', TRUE);
                
                switch ((int)$codigo_tipo_persona) {
                    
                    // Solicitud de Crédito
                    case 6:
                        
                        $this->load->model('mfunciones_microcreditos');

                        $arrResultado = $this->mfunciones_microcreditos->SolHistorialObservacionesDoc($codigo_prospecto, (int)$codigo_tipo_persona);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                        if (isset($arrResultado[0])) 
                        {
                            $i = 0;

                            foreach ($arrResultado as $key => $value) 
                            {
                                $item_valor = array(
                                    "prospecto_id" => $value["codigo_registro"],
                                    "usuario_id" => $value["usuario_id"],
                                    "usuario_nombre" => $value["usuario_nombre"],
                                    "documento_id" => $value["documento_id"],
                                    "documento_nombre" => $value["documento_nombre"],
                                    "documento_digitalizado" => $this->mfunciones_microcreditos->GetInfoSolicitudDigitalizadoPDF($value["codigo_registro"], $value["documento_id"], 'existe'),
                                    "obs_tipo_codigo" => $value["obs_tipo"],
                                    "obs_tipo_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["obs_tipo"], 'tipo_observacion'),
                                    "obs_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["obs_fecha"]),
                                    "obs_detalle" => nl2br($value["obs_detalle"]),
                                    "obs_estado_codigo" => $value["obs_estado"],
                                    "obs_estado_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["obs_estado"], 'estado_observacion_corto'),
                                );
                                $lst_resultado[$i] = $item_valor;

                                $i++;
                            }
                        }
                        else 
                        {
                            $lst_resultado[0] = $arrResultado;
                        }

                        $arrObsDocumentos = $lst_resultado;
                        
                        $arrObsProceso[0] = array();
                        
                        $DatosProspecto = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($codigo_prospecto);
                        $data['general_solicitante'] = $DatosProspecto[0]['sol_nombre_completo'];
                        
                        break;

                    // Normalizador/Cobrador
                    case 13:
                        
                        $this->load->model('mfunciones_cobranzas');
                        
                        $arrResultado = $this->mfunciones_cobranzas->RegHistorialObservacionesDoc($codigo_prospecto, (int)$codigo_tipo_persona);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                        
                        if (isset($arrResultado[0])) 
                        {
                            $i = 0;

                            foreach ($arrResultado as $key => $value) 
                            {
                                $item_valor = array(
                                    "prospecto_id" => $value["codigo_registro"],
                                    "usuario_id" => $value["usuario_id"],
                                    "usuario_nombre" => $value["usuario_nombre"],
                                    "documento_id" => $value["documento_id"],
                                    "documento_nombre" => $value["documento_nombre"],
                                    "documento_digitalizado" => $this->mfunciones_cobranzas->GetInfoRegistroDigitalizadoPDF($value["codigo_registro"], $value["documento_id"], 'existe'),
                                    "obs_tipo_codigo" => $value["obs_tipo"],
                                    "obs_tipo_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["obs_tipo"], 'tipo_observacion'),
                                    "obs_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["obs_fecha"]),
                                    "obs_detalle" => nl2br($value["obs_detalle"]),
                                    "obs_estado_codigo" => $value["obs_estado"],
                                    "obs_estado_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["obs_estado"], 'estado_observacion_corto'),
                                );
                                $lst_resultado[$i] = $item_valor;

                                $i++;
                            }
                        }
                        else 
                        {
                            $lst_resultado[0] = $arrResultado;
                        }

                        $arrObsDocumentos = $lst_resultado;
                        
                        $arrObsProceso[0] = array();
                        
                        $DatosProspecto = $this->mfunciones_cobranzas->VerificaNormConsolidado($codigo_prospecto);
                        $data['general_solicitante'] = $DatosProspecto[0]['norm_primer_nombre'] . ' ' . $DatosProspecto[0]['norm_segundo_nombre'] . ' ' . $DatosProspecto[0]['norm_primer_apellido'] . ' ' . $DatosProspecto[0]['norm_segundo_apellido'];
                        
                        break;
                        
                    default:
                        
                        $arrResultado = $this->mfunciones_logica->HistorialObservacionesDoc($codigo_prospecto);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                        if (isset($arrResultado[0])) 
                        {
                            $i = 0;

                            foreach ($arrResultado as $key => $value) 
                            {
                                $item_valor = array(
                                    "prospecto_id" => $value["prospecto_id"],
                                    "usuario_id" => $value["usuario_id"],
                                    "usuario_nombre" => $value["usuario_nombre"],
                                    "documento_id" => $value["documento_id"],
                                    "documento_nombre" => $value["documento_nombre"],
                                    "documento_digitalizado" => $this->mfunciones_generales->GetInfoDigitalizado($value["prospecto_id"], $value["documento_id"], 'existe'),
                                    "obs_tipo_codigo" => $value["obs_tipo"],
                                    "obs_tipo_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["obs_tipo"], 'tipo_observacion'),
                                    "obs_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["obs_fecha"]),
                                    "obs_detalle" => nl2br($value["obs_detalle"]),
                                    "obs_estado_codigo" => $value["obs_estado"],
                                    "obs_estado_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["obs_estado"], 'estado_observacion_corto'),
                                );
                                $lst_resultado[$i] = $item_valor;

                                $i++;
                            }
                        }
                        else 
                        {
                            $lst_resultado[0] = $arrResultado;
                        }

                        $arrObsDocumentos = $lst_resultado;

                        // Observaciones del Proceso

                        $arrResultado2 = $this->mfunciones_logica->HistorialObservacionesProc($codigo_prospecto);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                        if (isset($arrResultado2[0])) 
                        {
                            $i = 0;

                            foreach ($arrResultado2 as $key => $value) 
                            {
                                $item_valor2 = array(
                                    "prospecto_id" => $value["prospecto_id"],
                                    "usuario_id" => $value["usuario_id"],
                                    "usuario_nombre" => $value["usuario_nombre"],
                                    "etapa_id" => $value["etapa_id"],
                                    "etapa_nombre" => $value["etapa_nombre"],
                                    "obs_tipo_codigo" => $value["obs_tipo"],
                                    "obs_tipo_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["obs_tipo"], 'tipo_observacion'),
                                    "obs_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["obs_fecha"]),
                                    "obs_detalle" => nl2br($value["obs_detalle"]),
                                    "obs_estado_codigo" => $value["obs_estado"],
                                    "obs_estado_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["obs_estado"], 'estado_observacion_corto'),
                                );
                                $lst_resultado2[$i] = $item_valor2;

                                $i++;
                            }
                        }
                        else
                        {
                            $lst_resultado2[0] = $arrResultado2;
                        }

                        $arrObsProceso = $lst_resultado2;
                        
                        // Detalle del Prospecto
                        $arrResultado3 = $this->mfunciones_logica->VerificaProspectoRechazo($codigo_prospecto);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                        $data['general_solicitante'] = $arrResultado3[0]['general_solicitante'];
                        
                        break;
                }
                
                $data["arrObsDocumentos"] = $arrObsDocumentos;
                $data["arrObsProceso"] = $arrObsProceso;
                $data["codigo_prospecto"] = $codigo_prospecto;
                $data["codigo_tipo_persona"] = $codigo_tipo_persona;

                // Variable que indica donde volver
                $_SESSION['funcion_ver_documento'] = 'Ajax_CargarAccion_Historial';
                
                $this->load->view('prospecto/view_historial_observacion', $data);
            }
        }
    }
    
    public function HistorialExcepcion_Ver() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // 0=Insert    1=Update
        
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            if(!$this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"], PERFIL_HISTORIAL_EXCEPCION))
            {
                js_error_div_javascript($this->lang->line('NoAutorizadoPerfil'));
                exit();
            }
            else 
            {
                $codigo_prospecto = $this->input->post('codigo', TRUE);
                
                $arrResultado = $this->mfunciones_logica->HistorialExcepcion($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                
                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "prospecto_id" => $value["prospecto_id"],
                            "usuario_id" => $value["usuario_id"],
                            "usuario_nombre" => $value["usuario_nombre"],
                            "etapa_id" => $value["etapa_id"],
                            "etapa_nombre" => $value["etapa_nombre"],
                            "accion_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["accion_fecha"]),
                            "excepcion_detalle" => nl2br($value["excepcion_detalle"])
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }
                else 
                {
                    $lst_resultado[0] = $arrResultado;
                }
                
                $arrObsExcepcion = $lst_resultado;
                
                $data["arrObsExcepcion"] = $arrObsExcepcion;
                $data["codigo_prospecto"] = $codigo_prospecto;

                $this->load->view('prospecto/view_historial_excepcion', $data);
            }
        }
    }
    
    public function HistorialSeguimiento_Ver() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // 0=Insert    1=Update
        
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            if(!$this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"], PERFIL_HISTORIAL_EXCEPCION))
            {
                js_error_div_javascript($this->lang->line('NoAutorizadoPerfil'));
                exit();
            }
            else 
            {
                $codigo_prospecto = $this->input->post('codigo', TRUE);
                
                $arrResultado = $this->mfunciones_logica->HistorialSeguimiento($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                
                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "prospecto_id" => $value["prospecto_id"],
                            "usuario_nombre" => $value["usuario_nombre"],
                            "etapa_id" => $value["etapa_id"],
                            "etapa_nombre" => $value["etapa_nombre"],
                            "accion_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["accion_fecha"]),
                            "seguimiento_detalle" => nl2br($value["seguimiento_detalle"]),
                            "seguimiento_accion" => $this->mfunciones_generales->GetValorCatalogo($value["seguimiento_accion"], 'accion_catalogo')
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }
                else 
                {
                    $lst_resultado[0] = $arrResultado;
                }
                
                $arrObsExcepcion = $lst_resultado;
                
                $data["arrObsExcepcion"] = $arrObsExcepcion;
                $data["codigo_prospecto"] = $codigo_prospecto;

                $this->load->view('prospecto/view_prospecto_seguimiento', $data);
            }
        }
    }
    
    public function ObservarDevolver_Form() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
                
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            if(!$this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"], PERFIL_DEVOLVER_PROSPECTO))
            {
                js_error_div_javascript($this->lang->line('NoAutorizadoPerfil'));
                exit();
            }
            else 
            {
                // Se captura el valor
                $codigo_prospecto = $this->input->post('codigo', TRUE);

                // Datos del Prospecto
                $DatosProspecto = $this->mfunciones_generales->GetDatosEmpresaCorreo($codigo_prospecto);

                if (!isset($DatosProspecto[0])) 
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }

                $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
                $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

                $data["arrRespuesta"] = $DatosProspecto;

                $this->load->view('prospecto/view_observacion_proc_form', $data);
            }
        }
    }
    
    public function ObservarDevolver_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_POST['estructura_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // Se capturan los datos

            $codigo_prospecto = $this->input->post('estructura_id', TRUE);
            
            $observacion_detalle = $this->input->post('excepcion_detalle', TRUE);
            
            $nombre_usuario = $_SESSION["session_informacion"]["login"];
            $fecha_actual = date('Y-m-d H:i:s');

            if((int)$codigo_prospecto == 0 || $observacion_detalle == "")
            {
                js_error_div_javascript($this->lang->line('FormularioIncompleto'));
                exit();
            }
            
            // Observación      1 = Cumplimiento        2 = Legal
            $tipo_observacion = 1;
            
            // Rol 6 = Analista Legal
            if($_SESSION["session_informacion"]["rol_codigo"] == 6)
            {
                $tipo_observacion = 2;
            }
            
            $etapa_actual = $_SESSION['dato_etapa_actual'];

            // Se obtiene el parten de la etapa, Ó la etapa anterior
            $arrResultado = $this->mfunciones_logica->ObtenerParentEtapa($etapa_actual);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $etapa_nueva = $arrResultado[0]['etapa_depende'];
            }
            else 
            {
                $etapa_nueva = 2;
            }
            
            
            // Detalle del Prospecto
            $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($codigo_prospecto);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
            
            // Caso Extraordinario
            
//                if($etapa_actual == 14)
//                {                    
//                    if($arrResultado3[0]['prospecto_vobo_cumplimiento'] == 1 || $arrResultado3[0]['prospecto_vobo_legal'] == 1)
//                    {                        
//                        $aux_cump = $arrResultado3[0]['prospecto_aux_cump'];
//                        $aux_legal = $arrResultado3[0]['prospecto_aux_legal'];
//
//                        if($aux_cump == 2)
//                        {
//                            $aux_cump = 1;
//                        }
//
//                        if($aux_legal == 2)
//                        {
//                            $aux_legal = 1;
//                        }
//
//                        $this->mfunciones_logica->AuxiliarCumpLegal($aux_cump, $aux_legal, $codigo_prospecto);
//                    }
//                    else
//                    {
//                        $etapa_nueva = 2;
//                    }
//                }
            
            // Para las excepciones
                
            if($arrResultado3[0]['prospecto_excepcion'] > 0)
            {
                $this->mfunciones_logica->AccionExcepcionProspecto(0, $codigo_prospecto);
            }
                
            $this->mfunciones_generales->ObservarDevolverProspecto($codigo_prospecto, $etapa_nueva, $etapa_actual, $nombre_usuario, $fecha_actual, $tipo_observacion, $observacion_detalle);
                        
            $this->load->view('prospecto/view_devolver_guardado');
        }
    }
    
    public function SeguimientoAgente() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_POST['agente']) || !isset($_POST['campana']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // Se capturan los datos

            $codigo_ejecutivo = $this->input->post('agente', TRUE);
            $codigo_campana = $this->input->post('campana', TRUE);
            
            // Lista de campañas asignadas al Agente
            
            $arrResultado = $this->mfunciones_logica->ObtenerCampanaAgente($codigo_ejecutivo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0]))
            {
                foreach ($arrResultado as $key => $value)
                {
                    $arrSeguimiento[] = $this->mfunciones_generales->CalculoLeadAgenteCampana($codigo_ejecutivo, $value["camp_id"]);
                }
            }
            else 
            {
                js_error_div_javascript($this->lang->line('TablaNoResultados'));
                exit();
            }
            
            // Listado de las Etapas
            $arrEtapas = $this->mfunciones_logica->ObtenerDatosFlujo(-1, 1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEtapas);
            $data['arrEtapas'] = $arrEtapas;
            
            $data['arrSeguimiento'] = $arrSeguimiento;
            
            $this->load->view('prospecto/view_seguimiento_agente', $data);
        }
    }
}
?>