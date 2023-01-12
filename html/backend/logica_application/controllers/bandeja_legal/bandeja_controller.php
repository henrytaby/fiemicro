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
 * Controlador para la gestión de Prospectos - LEGAL
 * @brief BANDEJA DE LA INSTANCIA
 * @class Conf_catalogo_controller 
 */
class Bandeja_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 24;
        
        // Código de la Etapa Propio de la Bandeja
        protected $codigo_etapa = ETAPA_LEGAL;

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

        // Bandeja Evaluación Legal
        
        $filtro = 'p.prospecto_etapa=' . $this->codigo_etapa . ' AND p.prospecto_rechazado=0 AND p.prospecto_consolidado=1 AND p.prospecto_observado_app=0 AND p.prospecto_excepcion!=3';
        
        $arrResultado = $this->mfunciones_logica->ListadoBandejasProspecto($filtro);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;
                        
            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "prospecto_id" => $value["prospecto_id"],
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "tipo_persona_codigo" => $value["tipo_persona_id"],
                    "tipo_persona_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["tipo_persona_id"], 'tipo_persona'),
                    "empresa_id" => $value["empresa_id"],
                    "empresa_categoria_codigo" => $value["empresa_categoria_codigo"],
                    "empresa_categoria_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["empresa_categoria"], 'empresa_categoria'),
                    "fecha_derivada_etapa" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["fecha_derivada_etapa"]),
                    "empresa_nombre" => $value["empresa_nombre"],
                    "tiempo_etapa" => $this->mfunciones_generales->TiempoEtapaProspecto($value["fecha_derivada_etapa"], $this->codigo_etapa),
                    "prospecto_excepcion_codigo" => $value["prospecto_excepcion"],
                    "prospecto_excepcion_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["prospecto_excepcion"], 'excepcion_estado'),
                    "prospecto_observado" => $value["prospecto_observado"],
                    "evaluacion_legal_reporte" => $this->mfunciones_generales->VerificaReporteLegal($value["prospecto_id"])
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
            
            $arrResumen = $this->mfunciones_generales->TiempoEtapaResumen($lst_resultado);
            
            // Obtener el Tiempo asignado de la etapa
            $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa($this->codigo_etapa);
            $tiempo_etapa_asignado = $arrEtapa[0]['etapa_tiempo'];
        }
        else 
        {
            $lst_resultado[0] = $arrResultado;
            $arrResumen[0] = $arrResultado;
            $tiempo_etapa_asignado = 0;
        }
        
        // Se establece una variable de SESSION para determinar en que bandeja se encuentra el usuario y al Observar el documento, regresar allí
        
        $_SESSION['direccion_bandeja_actual'] = 'Bandeja/Legal/Ver';
        $_SESSION['dato_etapa_actual'] = $this->codigo_etapa;
        
        $data["arrRespuesta"] = $lst_resultado;
        $data["arrResumen"] = $arrResumen;
        $data["tiempo_etapa_asignado"] = $tiempo_etapa_asignado;
        
        $this->load->view('bandeja_legal/view_bandeja_ver', $data);
    }
    
    public function Legal_Form() {
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
            // Se captura el valor
            $codigo_prospecto = $this->input->post('codigo', TRUE);
            
            // Datos del Prospecto
            $DatosProspecto = $this->mfunciones_generales->GetDatosEmpresaCorreo($codigo_prospecto);

            if(!isset($DatosProspecto[0])) 
            {
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            }
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

            $data["arrRespuesta"] = $DatosProspecto;
            $data["evaluacion_legal_reporte"] = $this->mfunciones_generales->VerificaReporteLegal($codigo_prospecto);

            $this->load->view('bandeja_legal/view_legal_form', $data);
        }
    }
    
    public function Legal_Guardar() {
        
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
            
            $opcion_seleccion = $this->input->post('opcion_seleccion', TRUE);
            
            $nombre_usuario = $_SESSION["session_informacion"]["login"];
            $fecha_actual = date('Y-m-d H:i:s');

            if((int)$codigo_prospecto == 0 || $opcion_seleccion == "")
            {
                js_error_div_javascript($this->lang->line('FormularioIncompleto'));
                exit();
            }

            // Paso 1: Se obtiene el estado y etapa actual del prospecto
                
            // Detalle del Prospecto
            $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($codigo_prospecto);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            // Se continua siempre y cuando el prospecto no haya sido rechazado
            if($arrResultado3[0]['prospecto_etapa'] != 15)
            {
                // Si la etapa es 6 (Legal)
                // Dependiendo de la recomendación de CUMPLIMIENTO      1 = Aprueba     2 = Rechaza

                if($opcion_seleccion == 1)
                {
                    // Se procede a actualizar el prospecto respecto a este caso
                    $this->mfunciones_logica->RevisionLegalProspecto(1, 2, $nombre_usuario, $fecha_actual, $codigo_prospecto);
                    
                    // Se busca la etapa siguiente
                    $arrEtapa = $this->mfunciones_generales->ObteneRolHijoFlujo($this->codigo_etapa);
                    
                    // Caso Normal                    
                    $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, $arrEtapa[0]['etapa_id'], $this->codigo_etapa, $nombre_usuario, $fecha_actual, 2);

                    /***  REGISTRAR SEGUIMIENTO ***/
                    $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, $arrEtapa[0]['etapa_id'], 1, 'VoBo Cumplimento', $nombre_usuario, $fecha_actual);
                }

                if($opcion_seleccion == 2)
                {
                    // Se procede a actualizar el prospecto para registrar la revisión de Antecedentes                        
                    $this->mfunciones_logica->RechazarProspecto($nombre_usuario, $fecha_actual, $codigo_prospecto);

                    $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, ETAPA_RECHAZO, $this->codigo_etapa, $nombre_usuario, $fecha_actual, 0);

                    /***  REGISTRAR SEGUIMIENTO ***/
                    $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, ETAPA_RECHAZO, 7, 'Legal rechazó la Afiliación', $nombre_usuario, $fecha_actual);

                    /* Se Envía a la APP la Notificación por Push   tipo_notificacion, $tipo_visita, $codigo_visita */
                    $this->mfunciones_generales->EnviarNotificacionPush(5, 1, $codigo_prospecto);

                    // Enviar correo del rechazo del prospecto

                    $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa(ETAPA_RECHAZO);

                    if (isset($arrEtapa[0]))
                    {
                        foreach ($arrEtapa as $key1 => $value1) 
                        {
                            $rol = $value1['rol_codigo'];

                            $arrResultado4 = $this->mfunciones_logica->ObtenerDetalleDatosUsuario(2, $rol);
                            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado4);

                            if (isset($arrResultado4[0])) 
                            {

                                foreach ($arrResultado4 as $key => $value) 
                                {
                                        $destinatario_nombre = $value['usuario_nombres'] . ' ' . $value['usuario_app'] . ' ' . $value['usuario_apm'];
                                        $destinatario_correo = $value['usuario_email'];

                                        // SE PROCEDE CON EL ENVÍO DE CORREO ELECTRÓNICO
                                        $correo_enviado = $this->mfunciones_generales->EnviarCorreo('notificar_rechazo', $destinatario_correo, $destinatario_nombre, $codigo_prospecto, 0);
                                }
                            }
                        }
                    }
                }
            }
            
            $this->Bandeja_Ver();
        }
    }
    
    public function Evaluacion_Form() {
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
            // Se captura el valor
            $codigo_prospecto = $this->input->post('codigo', TRUE);
            
            // Datos del Prospecto
            $DatosProspecto = $this->mfunciones_generales->GetDatosEmpresaCorreo($codigo_prospecto);

            if (!isset($DatosProspecto[0])) 
            {
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            }
            
            // Paso 1: Se consulta los datos de la Evaluación Legal y en el caso que no existan se creará por única vez el registro
            
            $arrResultado = $this->mfunciones_logica->ListaDatosEvaluacion($codigo_prospecto);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "evaluacion_legal_id" => $value["evaluacion_legal_id"],
                        "prospecto_id" => $value["prospecto_id"],
                        "usuario_id" => $value["usuario_id"],
                        "evaluacion_denominacion_comercial" => $value["evaluacion_denominacion_comercial"],
                        "evaluacion_razon_social" => $value["evaluacion_razon_social"],
                        "evaluacion_doc_nit" => $value["evaluacion_legal_nit_doc"],
                        "evaluacion_nit" => $value["evaluacion_legal_nit_al_numero"],
                        "evaluacion_representante_legal" => $value["evaluacion_legal_nit_al_representante"],
                        "evaluacion_razon_idem" => $value["evaluacion_legal_idem"],
                        "evaluacion_doc_certificado" => $value["evaluacion_legal_cert_doc"],
                        "evaluacion_actividad_principal" => $value["evaluacion_legal_cert_al_principal"],
                        "evaluacion_actividad_secundaria" => $value["evaluacion_legal_cert_al_secundaria"],
                        "evaluacion_certificado_idem" => $value["evaluacion_legal_cert_al_idem"],
                        "evaluacion_doc_ci" => $value["evaluacion_legal_ci_doc"],
                        "evaluacion_ci_pertenece" => $value["evaluacion_legal_ci_al_pertenece"],
                        "evaluacion_ci_vigente" => $value["evaluacion_legal_ci_al_vigente"],
                        "evaluacion_ci_fecnac" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["evaluacion_legal_ci_al_fecnac"]),
                        "evaluacion_ci_titular" => $value["evaluacion_legal_ci_al_nombre"],
                        "evaluacion_doc_test" => $value["evaluacion_legal_test_doc"],
                        "evaluacion_numero_testimonio" => $value["evaluacion_legal_test_al_numero"],
                        "evaluacion_duracion_empresa" => $value["evaluacion_legal_test_al_duracion"],
                        "evaluacion_fecha_testimonio" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["evaluacion_legal_test_al_fecha"]),
                        "evaluacion_objeto_constitucion" => $value["evaluacion_legal_test_al_objeto"],
                        "evaluacion_doc_poder" => $value["evaluacion_legal_poder_doc"],
                        "evaluacion_fecha_testimonio_poder" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["evaluacion_legal_poder_al_fecha"]),                        
                        "evaluacion_numero_testimonio_poder" => $value["evaluacion_legal_poder_al_numero"],
                        "evaluacion_firma_conjunta" => $value["evaluacion_legal_poder_al_firma"],
                        "evaluacion_facultad_representacion" => $value["evaluacion_legal_poder_al_facultades"],
                        "evaluacion_doc_funde" => $value["evaluacion_legal_funde_doc"],
                        "evaluacion_fundaempresa_emision" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["evaluacion_legal_funde_al_fecemi"]),
                        "evaluacion_fundaempresa_vigencia" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["evaluacion_legal_funde_al_fecvig"]),
                        "evaluacion_idem_escritura" => $value["evaluacion_legal_funde_al_idem"],
                        "evaluacion_idem_poder" => $value["evaluacion_legal_funde_al_representante"],
                        "evaluacion_idem_general" => $value["evaluacion_legal_funde_al_denominacion"],
                        "evaluacion_resultado" => $value["evaluacion_legal_resultado"],
                        "opcion_conclusion" => $value["evaluacion_legal_conclusion"],
                        "evaluacion_pertenece_regional" => $value["evaluacion_pertenece_regional"],
                        "evaluacion_fecha_solicitud" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["evaluacion_legal_fecha_solicitud"]),
                        "evaluacion_fecha_evaluacion" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["evaluacion_legal_fecha_evaluacion"]),
                        "evaluacion_legal_revisadopor" => $value["evaluacion_legal_revisadopor"],
                        "evaluacion_legal_estado" => $value["evaluacion_legal_estado"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {                
                // Paso 2: Si no se contraron datos, se registra por única vez la evaluación
                $nombre_usuario = $_SESSION["session_informacion"]["login"];
                $fecha_actual = date('Y-m-d H:i:s');
                $this->mfunciones_logica->InsertarEvaluacionLegal($codigo_prospecto, $_SESSION["session_informacion"]["codigo"], $nombre_usuario, $fecha_actual);
                
                $lst_resultado[0] = $arrResultado;
            }
            
            // Listado de las Sucursales
            $arrRegional = $this->mfunciones_logica->ObtenerDatosRegional(-1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRegional);
            $data["arrRegional"] = $arrRegional;
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

            $data["arrRespuesta"] = $DatosProspecto;
            $data["arrResultado"] = $lst_resultado;

            $this->load->view('bandeja_legal/view_evaluacion_form', $data);
        }
    }
    
    public function Evaluacion_Guardar() {
        
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
            
            $nombre_usuario = $_SESSION["session_informacion"]["login"];
            $fecha_actual = date('Y-m-d H:i:s');

            // Paso 1: Se obtiene el estado y etapa actual del prospecto
                
            // Detalle del Prospecto
            $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($codigo_prospecto);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            // Se continua siempre y cuando el prospecto no haya sido rechazado
            if($arrResultado3[0]['prospecto_etapa'] != 15)
            {
                $evaluacion_denominacion_comercial = $this->input->post('evaluacion_denominacion_comercial', TRUE);
                $evaluacion_razon_social = $this->input->post('evaluacion_razon_social', TRUE);
                $evaluacion_doc_nit = $this->input->post('evaluacion_doc_nit', TRUE);
                $evaluacion_nit = (int)$this->input->post('evaluacion_nit', TRUE);
                $evaluacion_representante_legal = $this->input->post('evaluacion_representante_legal', TRUE);
                $evaluacion_razon_idem = $this->input->post('evaluacion_razon_idem', TRUE);
                $evaluacion_doc_certificado = $this->input->post('evaluacion_doc_certificado', TRUE);
                $evaluacion_actividad_principal = $this->input->post('evaluacion_actividad_principal', TRUE);
                $evaluacion_actividad_secundaria = $this->input->post('evaluacion_actividad_secundaria', TRUE);
                $evaluacion_certificado_idem = $this->input->post('evaluacion_certificado_idem', TRUE);
                $evaluacion_doc_ci = $this->input->post('evaluacion_doc_ci', TRUE);
                $evaluacion_ci_pertenece = $this->input->post('evaluacion_ci_pertenece', TRUE);
                $evaluacion_ci_vigente = $this->input->post('evaluacion_ci_vigente', TRUE);
                $evaluacion_ci_fecnac = $this->input->post('evaluacion_ci_fecnac', TRUE);
                    $evaluacion_ci_fecnac = $this->mfunciones_generales->getFormatoFechaDate($evaluacion_ci_fecnac);
                $evaluacion_ci_titular = $this->input->post('evaluacion_ci_titular', TRUE);
                $evaluacion_doc_test = $this->input->post('evaluacion_doc_test', TRUE);
                $evaluacion_numero_testimonio = $this->input->post('evaluacion_numero_testimonio', TRUE);
                $evaluacion_duracion_empresa = $this->input->post('evaluacion_duracion_empresa', TRUE);
                $evaluacion_fecha_testimonio = $this->input->post('evaluacion_fecha_testimonio', TRUE);
                    $evaluacion_fecha_testimonio = $this->mfunciones_generales->getFormatoFechaDate($evaluacion_fecha_testimonio);                
                $evaluacion_objeto_constitucion = $this->input->post('evaluacion_objeto_constitucion', TRUE);
                $evaluacion_doc_poder = $this->input->post('evaluacion_doc_poder', TRUE);
                $evaluacion_fecha_testimonio_poder = $this->input->post('evaluacion_fecha_testimonio_poder', TRUE);
                    $evaluacion_fecha_testimonio_poder = $this->mfunciones_generales->getFormatoFechaDate($evaluacion_fecha_testimonio_poder);                
                $evaluacion_numero_testimonio_poder = $this->input->post('evaluacion_numero_testimonio_poder', TRUE);
                $evaluacion_firma_conjunta = $this->input->post('evaluacion_firma_conjunta', TRUE);
                $evaluacion_facultad_representacion = $this->input->post('evaluacion_facultad_representacion', TRUE);
                $evaluacion_doc_funde = $this->input->post('evaluacion_doc_funde', TRUE);
                $evaluacion_fundaempresa_emision = $this->input->post('evaluacion_fundaempresa_emision', TRUE);
                    $evaluacion_fundaempresa_emision = $this->mfunciones_generales->getFormatoFechaDate($evaluacion_fundaempresa_emision);                
                $evaluacion_fundaempresa_vigencia = $this->input->post('evaluacion_fundaempresa_vigencia', TRUE);
                    $evaluacion_fundaempresa_vigencia = $this->mfunciones_generales->getFormatoFechaDate($evaluacion_fundaempresa_vigencia);
                    
                $evaluacion_idem_escritura = $this->input->post('evaluacion_idem_escritura', TRUE);
                $evaluacion_idem_poder = $this->input->post('evaluacion_idem_poder', TRUE);
                $evaluacion_idem_general = $this->input->post('evaluacion_idem_general', TRUE);
                $evaluacion_resultado = $this->input->post('evaluacion_resultado', TRUE);
                $opcion_conclusion = $this->input->post('opcion_conclusion', TRUE);
                $evaluacion_pertenece_regional = $this->input->post('evaluacion_pertenece_regional', TRUE);
                $evaluacion_fecha_solicitud = $this->input->post('evaluacion_fecha_solicitud', TRUE);
                    $evaluacion_fecha_solicitud = $this->mfunciones_generales->getFormatoFechaDate($evaluacion_fecha_solicitud);
                
                $evaluacion_fecha_evaluacion = $this->input->post('evaluacion_fecha_evaluacion', TRUE);
                    $evaluacion_fecha_evaluacion = $this->mfunciones_generales->getFormatoFechaDate($evaluacion_fecha_evaluacion);
                                
                
                // Validaciones
                
                $separador = '<br /> - ';
                $error_texto = '';
                    
                if((int)$codigo_prospecto == 0)
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }
                
                if($evaluacion_denominacion_comercial == '')
                {
                    $error_texto .= $separador . $this->lang->line('evaluacion_denominacion_comercial');
                }
                
                if($evaluacion_razon_social == '')
                {
                    $error_texto .= $separador . $this->lang->line('evaluacion_razon_social');
                }
                
                if($evaluacion_doc_nit == 2)
                {
                    if((int)$evaluacion_nit == 0)
                    {
                        $error_texto .= $separador . $this->lang->line('evaluacion_nit');
                    }
                    
                    if($evaluacion_representante_legal == '')
                    {
                        $error_texto .= $separador . $this->lang->line('evaluacion_representante_legal');
                    }
                    
                    if($evaluacion_razon_idem == '')
                    {
                        $error_texto .= $separador . $this->lang->line('evaluacion_razon_idem');
                    }
                }
                else
                {
                    $evaluacion_nit = 0;
                    $evaluacion_representante_legal = '';
                    $evaluacion_razon_idem = 0;                    
                }
                
                if($evaluacion_doc_certificado == 2)
                {
                    if($evaluacion_actividad_principal == '')
                    {
                        $error_texto .= $separador . $this->lang->line('evaluacion_actividad_principal');
                    }
                    
                    if($evaluacion_certificado_idem == '')
                    {
                        $error_texto .= $separador . $this->lang->line('evaluacion_certificado_idem');
                    }
                }
                else
                {
                    $evaluacion_actividad_principal = '';
                    $evaluacion_actividad_secundaria = '';
                    $evaluacion_certificado_idem = 0;
                }
                
                if($evaluacion_doc_ci == 2)
                {
                    if($this->mfunciones_generales->VerificaFechaY_M_D($evaluacion_ci_fecnac) == false)
                    {
                        $error_texto .= $separador . $this->lang->line('evaluacion_ci_fecnac');
                    }
                    
                    if($evaluacion_ci_titular == '')
                    {
                        $error_texto .= $separador . $this->lang->line('evaluacion_ci_titular');
                    }
                }
                else
                {
                    $evaluacion_ci_vigente = 0;
                    $evaluacion_ci_fecnac = $this->mfunciones_generales->getFormatoFechaDate('');
                    $evaluacion_ci_titular = '';
                }
                
                if($evaluacion_doc_test == 2)
                {
                    if($evaluacion_numero_testimonio == '')
                    {
                        $error_texto .= $separador . $this->lang->line('evaluacion_numero_testimonio');
                    }
                    
                    if($evaluacion_duracion_empresa == '')
                    {
                        $error_texto .= $separador . $this->lang->line('evaluacion_duracion_empresa');
                    }
                    
                    if($this->mfunciones_generales->VerificaFechaY_M_D($evaluacion_fecha_testimonio) == false)
                    {
                        $error_texto .= $separador . $this->lang->line('evaluacion_fecha_testimonio');
                    }
                    
                    if($evaluacion_objeto_constitucion == '')
                    {
                        $error_texto .= $separador . $this->lang->line('evaluacion_objeto_constitucion');
                    }
                }
                else
                {
                    $evaluacion_numero_testimonio = '';
                    $evaluacion_duracion_empresa = '';
                    $evaluacion_fecha_testimonio = $this->mfunciones_generales->getFormatoFechaDate('');
                    $evaluacion_objeto_constitucion = '';
                }
                
                if($evaluacion_doc_poder == 2)
                {
                    if($this->mfunciones_generales->VerificaFechaY_M_D($evaluacion_fecha_testimonio_poder) == false)
                    {
                        $error_texto .= $separador . $this->lang->line('evaluacion_fecha_testimonio_poder');
                    }
                    
                    if($evaluacion_numero_testimonio_poder == '')
                    {
                        $error_texto .= $separador . $this->lang->line('evaluacion_numero_testimonio_poder');
                    }
                }
                else
                {
                    $evaluacion_fecha_testimonio_poder = $this->mfunciones_generales->getFormatoFechaDate('');
                    $evaluacion_numero_testimonio_poder = '';
                    $evaluacion_firma_conjunta = 0;
                    $evaluacion_facultad_representacion = 0;
                    
                }
                
                if($evaluacion_doc_funde == 2)
                {
                    if($this->mfunciones_generales->VerificaFechaY_M_D($evaluacion_fundaempresa_emision) == false)
                    {
                        $error_texto .= $separador . $this->lang->line('evaluacion_fundaempresa_emision');
                    }
                    
                    if($this->mfunciones_generales->VerificaFechaY_M_D($evaluacion_fundaempresa_vigencia) == false)
                    {
                        $error_texto .= $separador . $this->lang->line('evaluacion_fundaempresa_vigencia');
                    }
                }
                else
                {
                    $evaluacion_fundaempresa_emision = $this->mfunciones_generales->getFormatoFechaDate('');
                    $evaluacion_fundaempresa_vigencia = $this->mfunciones_generales->getFormatoFechaDate('');
                    $evaluacion_idem_escritura = 0;
                    $evaluacion_idem_poder = 0;
                    $evaluacion_idem_general = 0;
                }
                
                if($evaluacion_resultado == '')
                {
                    $error_texto .= $separador . $this->lang->line('evaluacion_resultado');
                }
                
                if($opcion_conclusion == '')
                {
                    $error_texto .= $separador . $this->lang->line('opcion_conclusion');
                }
                
                if($evaluacion_pertenece_regional == '')
                {
                    $error_texto .= $separador . $this->lang->line('evaluacion_pertenece_regional');
                }
                
                if($this->mfunciones_generales->VerificaFechaY_M_D($evaluacion_fecha_solicitud) == false)
                {
                    $error_texto .= $separador . $this->lang->line('evaluacion_fecha_solicitud');
                }
                
                if($this->mfunciones_generales->VerificaFechaY_M_D($evaluacion_fecha_evaluacion) == false)
                {
                    $error_texto .= $separador . $this->lang->line('evaluacion_fecha_evaluacion');
                }
                
                if($error_texto != '')
                {
                    js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                    exit();
                }
                
                // Fin validación
                
                // Se procede a actualizar la evaluación
                $this->mfunciones_logica->UpdateEvaluacionLegal($evaluacion_denominacion_comercial, $evaluacion_razon_social, $evaluacion_doc_nit, $evaluacion_nit, $evaluacion_representante_legal, $evaluacion_razon_idem, $evaluacion_doc_certificado, $evaluacion_actividad_principal, $evaluacion_actividad_secundaria, $evaluacion_certificado_idem, $evaluacion_doc_ci, $evaluacion_ci_pertenece, $evaluacion_ci_vigente, $evaluacion_ci_fecnac, $evaluacion_ci_titular, $evaluacion_doc_test, $evaluacion_numero_testimonio, $evaluacion_duracion_empresa, $evaluacion_fecha_testimonio, $evaluacion_objeto_constitucion, $evaluacion_doc_poder, $evaluacion_fecha_testimonio_poder, $evaluacion_numero_testimonio_poder, $evaluacion_firma_conjunta, $evaluacion_facultad_representacion, $evaluacion_doc_funde, $evaluacion_fundaempresa_emision, $evaluacion_fundaempresa_vigencia, $evaluacion_idem_escritura, $evaluacion_idem_poder, $evaluacion_idem_general, $evaluacion_resultado, $opcion_conclusion, $evaluacion_pertenece_regional, $evaluacion_fecha_solicitud, $evaluacion_fecha_evaluacion, $nombre_usuario, $fecha_actual, $codigo_prospecto);
            }
            
            $this->Bandeja_Ver();
        }
    }
    
    public function Evaluacion_PDF() {
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
            $codigo_prospecto = $this->input->post('codigo', TRUE);            
            
            // Paso 1: Se consulta los datos de la Evaluación Legal y en el caso que no existan se creará por única vez el registro
            
            $arrResultado = $this->mfunciones_logica->ListaDatosEvaluacion($codigo_prospecto);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "evaluacion_legal_id" => $value["evaluacion_legal_id"],
                        "prospecto_id" => $value["prospecto_id"],
                        "usuario_id" => $value["usuario_id"],
                        "evaluacion_denominacion_comercial" => $value["evaluacion_denominacion_comercial"],
                        "evaluacion_razon_social" => $value["evaluacion_razon_social"],
                        "evaluacion_doc_nit_codigo" => $value["evaluacion_legal_nit_doc"],
                        "evaluacion_doc_nit" => $this->mfunciones_generales->GetValorCatalogo($value["evaluacion_legal_nit_doc"], 'evaluacion_doc'),
                        "evaluacion_nit" => $value["evaluacion_legal_nit_al_numero"],
                        "evaluacion_representante_legal" => $value["evaluacion_legal_nit_al_representante"],
                        "evaluacion_razon_idem" => $this->mfunciones_generales->GetValorCatalogo($value["evaluacion_legal_idem"], 'si_no'),
                        "evaluacion_doc_certificado_codigo" => $value["evaluacion_legal_cert_doc"],
                        "evaluacion_doc_certificado" => $this->mfunciones_generales->GetValorCatalogo($value["evaluacion_legal_cert_doc"], 'evaluacion_doc'),
                        "evaluacion_actividad_principal" => $value["evaluacion_legal_cert_al_principal"],
                        "evaluacion_actividad_secundaria" => $value["evaluacion_legal_cert_al_secundaria"],
                        "evaluacion_certificado_idem" => $this->mfunciones_generales->GetValorCatalogo($value["evaluacion_legal_cert_al_idem"], 'si_no'),
                        "evaluacion_doc_ci_codigo" => $value["evaluacion_legal_ci_doc"],
                        "evaluacion_doc_ci" => $this->mfunciones_generales->GetValorCatalogo($value["evaluacion_legal_ci_doc"], 'evaluacion_doc'),
                        "evaluacion_ci_pertenece" => $this->mfunciones_generales->GetValorCatalogo($value["evaluacion_legal_ci_al_pertenece"], 'ci_pertenece'),
                        "evaluacion_ci_vigente" => $this->mfunciones_generales->GetValorCatalogo($value["evaluacion_legal_ci_al_vigente"], 'si_no'),
                        "evaluacion_ci_fecnac" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["evaluacion_legal_ci_al_fecnac"]),
                        "evaluacion_ci_titular" => $value["evaluacion_legal_ci_al_nombre"],
                        "evaluacion_doc_test_codigo" => $value["evaluacion_legal_test_doc"],
                        "evaluacion_doc_test" => $this->mfunciones_generales->GetValorCatalogo($value["evaluacion_legal_test_doc"], 'evaluacion_doc'),
                        "evaluacion_numero_testimonio" => $value["evaluacion_legal_test_al_numero"],
                        "evaluacion_duracion_empresa" => $value["evaluacion_legal_test_al_duracion"],
                        "evaluacion_fecha_testimonio" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["evaluacion_legal_test_al_fecha"]),
                        "evaluacion_objeto_constitucion" => nl2br($value["evaluacion_legal_test_al_objeto"]),
                        "evaluacion_doc_poder_codigo" => $value["evaluacion_legal_poder_doc"],
                        "evaluacion_doc_poder" => $this->mfunciones_generales->GetValorCatalogo($value["evaluacion_legal_poder_doc"], 'evaluacion_doc'),
                        "evaluacion_fecha_testimonio_poder" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["evaluacion_legal_poder_al_fecha"]),                        
                        "evaluacion_numero_testimonio_poder" => $value["evaluacion_legal_poder_al_numero"],
                        "evaluacion_firma_conjunta" => $this->mfunciones_generales->GetValorCatalogo($value["evaluacion_legal_poder_al_firma"], 'si_no'),
                        "evaluacion_facultad_representacion" => $this->mfunciones_generales->GetValorCatalogo($value["evaluacion_legal_poder_al_facultades"], 'si_no'),
                        "evaluacion_doc_funde_codigo" => $value["evaluacion_legal_funde_doc"],
                        "evaluacion_doc_funde" => $this->mfunciones_generales->GetValorCatalogo($value["evaluacion_legal_funde_doc"], 'evaluacion_doc'),
                        "evaluacion_fundaempresa_emision" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["evaluacion_legal_funde_al_fecemi"]),
                        "evaluacion_fundaempresa_vigencia" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["evaluacion_legal_funde_al_fecvig"]),
                        "evaluacion_idem_escritura" => $this->mfunciones_generales->GetValorCatalogo($value["evaluacion_legal_funde_al_idem"], 'si_no'),
                        "evaluacion_idem_poder" => $this->mfunciones_generales->GetValorCatalogo($value["evaluacion_legal_funde_al_representante"], 'si_no'),
                        "evaluacion_idem_general" => $this->mfunciones_generales->GetValorCatalogo($value["evaluacion_legal_funde_al_denominacion"], 'si_no'),
                        "evaluacion_resultado" => nl2br($value["evaluacion_legal_resultado"]),
                        "opcion_conclusion" => $value["evaluacion_legal_conclusion"],
                        "evaluacion_pertenece_regional" => $value["estructura_regional_nombre"],
                        "evaluacion_fecha_solicitud" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["evaluacion_legal_fecha_solicitud"]),
                        "evaluacion_fecha_evaluacion" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["evaluacion_legal_fecha_evaluacion"]),
                        "evaluacion_legal_revisadopor" => $value["evaluacion_legal_revisadopor"],
                        "evaluacion_legal_estado" => $value["evaluacion_legal_estado"],
                        "evaluacion_legal_reporte" => $this->mfunciones_generales->VerificaReporteLegal($value["prospecto_id"])
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }

            // Se adecua el contenido de acuerdo a las opciones elegidas
            
            if($lst_resultado[0]['evaluacion_doc_nit_codigo'] != 2)
            {
                $lst_resultado[0]['evaluacion_nit'] = '';
                $lst_resultado[0]['evaluacion_representante_legal'] = '';
                $lst_resultado[0]['evaluacion_razon_idem'] = '';
            }

            if($lst_resultado[0]['evaluacion_doc_certificado_codigo'] != 2)
            {
                $lst_resultado[0]['evaluacion_actividad_principal'] = '';
                $lst_resultado[0]['evaluacion_actividad_secundaria'] = '';
                $lst_resultado[0]['evaluacion_certificado_idem'] = '';
            }

            if($lst_resultado[0]['evaluacion_doc_ci_codigo'] != 2)
            {
                $lst_resultado[0]['evaluacion_ci_vigente'] = '';
                $lst_resultado[0]['evaluacion_ci_fecnac'] = '';
                $lst_resultado[0]['evaluacion_ci_titular'] = '';
            }

            if($lst_resultado[0]['evaluacion_doc_test_codigo'] != 2)
            {
                $lst_resultado[0]['evaluacion_numero_testimonio'] = '';
                $lst_resultado[0]['evaluacion_duracion_empresa'] = '';
                $lst_resultado[0]['evaluacion_fecha_testimonio'] = '';
                $lst_resultado[0]['evaluacion_objeto_constitucion'] = '';
            }

            if($lst_resultado[0]['evaluacion_doc_poder_codigo'] != 2)
            {
                $lst_resultado[0]['evaluacion_fecha_testimonio_poder'] = '';
                $lst_resultado[0]['evaluacion_numero_testimonio_poder'] = '';
                $lst_resultado[0]['evaluacion_firma_conjunta'] = '';
                $lst_resultado[0]['evaluacion_facultad_representacion'] = '';

            }

            if($lst_resultado[0]['evaluacion_doc_funde_codigo'] != 2)
            {
                $lst_resultado[0]['evaluacion_fundaempresa_emision'] = '';
                $lst_resultado[0]['evaluacion_fundaempresa_vigencia'] = '';
                $lst_resultado[0]['evaluacion_idem_escritura'] = '';
                $lst_resultado[0]['evaluacion_idem_poder'] = '';
                $lst_resultado[0]['evaluacion_idem_general'] = '';
            }
            
            $data["arrResultado"] = $lst_resultado;

            $this->mfunciones_generales->GeneraPDF('bandeja_legal/view_evaluacion_plain',$data);
            return;
        }
    }
}
?>