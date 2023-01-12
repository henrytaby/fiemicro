<?php
/**
 * @file 
 * Codigo que implementa el controlador para la autenticacion de usuario en el sistema
 * @brief  CONTROLADOR SOLICITUD DE MANTENIMIENTO
 * @author JRAD
 * @date Mar 21, 2014
 * @copyright 2017 JRAD
 */
/**
 * Controlador para SOLICITUD DE MANTENIMIENTO
 * @brief CONTROLADOR DEL LOGUEO
 * @class Login_controller 
 */
class Solicitud_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 0;

    function __construct() {
        parent::__construct();
    }
    /**
     * carga la vista para el formulario de login
     * @brief CARGAR LOGIN
     * @author JRAD
     * @date Mar 21, 2014     
     */
    
    public function menu() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
     
        $this->load->view('solicitud_mantenimiento/web_menu');
        
    }
    
    public function Solicitud_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estado = 0;
        
        if(isset($_POST['estado']))
        {
            $estado = preg_replace('/[^a-zA-Z0-9_ .]/s', '', $this->input->post('estado', TRUE));
        }
        
        $arrResultado = $this->mfunciones_logica->ObtenerSolicitudMantenimiento(-1, $estado);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0]))
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                $item_valor = array(
                    "solicitud_id" => $value["solicitud_id"],
                    "solicitud_nit" => $value["solicitud_nit"],
                    "solicitud_nombre_empresa" => $value["solicitud_nombre"],
                    "solicitud_otro" => $value["solicitud_otro"],
                    "solicitud_otro_detalle" => $value["solicitud_otro_detalle"],
                    "solicitud_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["solicitud_fecha"]),
                    "solicitud_confirmado" => $value["solicitud_confirmado"],
                    "solicitud_token" => $value["solicitud_token"],
                    "solicitud_ip" => $value["solicitud_ip"],
                    "solicitud_estado" => $this->mfunciones_generales->GetValorCatalogo($value["solicitud_estado"], 'estado_solicitud'),
                    "solicitud_observacion" => $value["solicitud_observacion"]
                );
                $lst_resultado[$i] = $item_valor;
                
                $i++;
            }
        }
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }
        
        $estado_texto = $this->mfunciones_generales->GetValorCatalogo($estado, 'estado_solicitud');
        
        $data["estado"] = $estado;
        $data["estado_texto"] = $estado_texto;
        
        $data["arrRespuesta"] = $lst_resultado;
        
        $this->load->view('solicitud_mantenimiento/view_solicitud_ver', $data);
        
    }
    
    public function SolicitudDetalle() {
        
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
            $codigo = $this->input->post('codigo', TRUE);
            $estado = $this->input->post('estado', TRUE);

            $arrResultado = $this->mfunciones_logica->ObtenerSolicitudMantenimiento($codigo, $estado);

            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "solicitud_id" => $value["solicitud_id"],
                        "solicitud_nit" => $value["solicitud_nit"],
                        "solicitud_nombre_empresa" => $value["solicitud_nombre"],
                        "solicitud_otro" => $value["solicitud_otro"],
                        "solicitud_otro_detalle" => $value["solicitud_otro_detalle"],
                        "solicitud_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["solicitud_fecha"]),
                        "solicitud_confirmado" => $value["solicitud_confirmado"],
                        "solicitud_token" => $value["solicitud_token"],
                        "solicitud_ip" => $value["solicitud_ip"],
                        "solicitud_estado" => $this->mfunciones_generales->GetValorCatalogo($value["solicitud_estado"], 'estado_solicitud'),
                        "solicitud_observacion" => $value["solicitud_observacion"],
                        "solicitud_email" => $value["solicitud_email"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            } 
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }

            // Listado de Servicios
            $arrTareas = $this->mfunciones_logica->ObtenerTareasSolicitud($codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrTareas);
            $data["arrTareas"] = $arrTareas;
            
            $data["arrRespuesta"] = $lst_resultado;

            $this->load->view('solicitud_mantenimiento/view_solicitud_detalle', $data);
        }
    }
    
    public function SolicitudRechazar() {
        
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
        else
        {
            $codigo = $this->input->post('codigo', TRUE);
            $estado = $this->input->post('estado', TRUE);

            $arrResultado = $this->mfunciones_logica->ObtenerSolicitudMantenimiento($codigo, $estado);

            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "solicitud_id" => $value["solicitud_id"],
                        "solicitud_nit" => $value["solicitud_nit"],
                        "solicitud_nombre_empresa" => $value["solicitud_nombre"],
                        "solicitud_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["solicitud_fecha"]),
                        "solicitud_email" => $value["solicitud_email"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            } 
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }

            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
            
            $data["arrRespuesta"] = $lst_resultado;

            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
            
            $this->load->view('solicitud_mantenimiento/view_solicitud_rechazar', $data);
        }
    }    
    
    public function SolicitudRechazar_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $solicitud_observacion = $this->input->post('solicitud_observacion', TRUE);
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if((int)$estructura_id == 0 || $solicitud_observacion == "")
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        $this->mfunciones_logica->RechazarSolicitudProspecto($solicitud_observacion, $nombre_usuario, $fecha_actual, $estructura_id);
        
        $this->Solicitud_Ver();        
    }
    
    public function SolicitudForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // 0=Insert    1=Update

        $tipo_accion = 0;
        
        if(isset($_POST['tipo_accion']))
        {
            // UPDATE

            $tipo_accion = $this->input->post('tipo_accion', TRUE);
            $codigo = $this->input->post('codigo', TRUE);
            $estado = $this->input->post('estado', TRUE);

            $arrResultado = $this->mfunciones_logica->ObtenerSolicitudMantenimiento($codigo, $estado);

            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "solicitud_id" => $value["solicitud_id"],
                        "solicitud_nit" => $value["solicitud_nit"],
                        "solicitud_nombre_empresa" => $value["solicitud_nombre"],
                        "solicitud_otro" => $value["solicitud_otro"],
                        "solicitud_otro_detalle" => $value["solicitud_otro_detalle"],
                        "solicitud_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["solicitud_fecha"]),
                        "solicitud_confirmado" => $value["solicitud_confirmado"],
                        "solicitud_token" => $value["solicitud_token"],
                        "solicitud_ip" => $value["solicitud_ip"],
                        "solicitud_estado" => $this->mfunciones_generales->GetValorCatalogo($value["solicitud_estado"], 'estado_solicitud'),
                        "solicitud_observacion" => $value["solicitud_observacion"],
                        "solicitud_email" => $value["solicitud_email"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            } 
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }

            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);                    
            $data["arrRespuesta"] = $lst_resultado;

        }
        else
        {
            $tipo_accion = 0;
            $codigo = 0;

            // INSERT

            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
        }

        // Listado de Servicios
        $arrTareas = $this->mfunciones_logica->ObtenerTareas();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrTareas);

        $data["arrTareas"] = $arrTareas;

        $data["tipo_accion"] = $tipo_accion;

        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

        $this->load->view('solicitud_mantenimiento/view_solicitud_editar', $data);
    }
    
    public function Solicitud_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_POST['tipo_accion']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {            
            // Se capturan los datos
            
            $solicitud_nit = $this->input->post('solicitud_nit', TRUE);
            $solicitud_nombre_empresa = $this->input->post('solicitud_nombre_empresa', TRUE);
            $solicitud_email = $this->input->post('solicitud_email', TRUE);
            
            $arrTareas = $this->input->post('tarea_list', TRUE);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrTareas);
            
            $otro = $this->input->post('otro', TRUE);
            $solicitud_otro_detalle = $this->input->post('solicitud_otro_detalle', TRUE);
            
            $nombre_usuario = '';
            if(isset($_SESSION["session_informacion"]["login"]))
            {
                $nombre_usuario = $_SESSION["session_informacion"]["login"];
            }
            
            $fecha_actual = date('Y-m-d H:i:s');

            // Validaciones
                
            $separador = '<br /> - ';
            $error_texto = '';
            
            if(!filter_var($solicitud_nit, FILTER_VALIDATE_FLOAT) !== false)
            {
                $error_texto .= $separador . $this->lang->line('empresa_nit');
            }
            
            if($solicitud_nombre_empresa == "")
            {
                $error_texto .= $separador . $this->lang->line('solicitud_nombre_empresa');
            }
            
            if($this->mfunciones_generales->VerificaCorreo($solicitud_email) == false)
            {
                $error_texto .= $separador . $this->lang->line('CamposCorreo');
            }
            
            if($otro == "")
            {
                $error_texto .= $separador . $this->lang->line('mantenimiento_otro_elegir');
            }
            
            if($otro == 1 && $solicitud_otro_detalle == "")
            {
                $error_texto .= $separador . $this->lang->line('mantenimiento_otro');
            }
            
            if (!isset($arrTareas[0]))
            {
                $error_texto .= $separador . $this->lang->line('mantenimiento_tareas');
            }
            
            if($error_texto != '')
            {
                js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                exit();
            }
            
            // Fin Validación
            
            // 0=Insert    1=Update
        
            $tipo_accion = $this->input->post('tipo_accion', TRUE);
            
//            if($tipo_accion == 1)
//            {
//                // UPDATE
//                $estructura_id = $this->input->post('estructura_id', TRUE);
//
//                $this->mfunciones_logica->UpdateSolicitudProspecto();
//            }
            
            if($tipo_accion == 0)
            {
                $ip = $this->input->ip_address();
                // INSERT
                
                $token = $this->mfunciones_generales->GeneraToken();
                
                $estructura_id = $this->mfunciones_logica->InsertSolicitudMantenimiento($solicitud_nit, $solicitud_nombre_empresa, $otro, $solicitud_otro_detalle, $fecha_actual, $solicitud_email, $ip, $nombre_usuario, $fecha_actual, $token);

                // Enviar Correo de verificación de la Solicitud
                $this->mfunciones_generales->EnviaCorreoVerificacion('mantenimiento', $solicitud_nombre_empresa, $solicitud_email, $estructura_id, $token);
                
            }
            
            // INSERTAR LOS SERVICIOS SELECCIONADOS
        
            // 1. Se eliminan las tareas de la solicitud
            $this->mfunciones_logica->Eliminar_SolicitudTarea($estructura_id);
        
            // 2. Se registran los servicios seleccionados
            
            if (isset($arrTareas[0]))
            {
                foreach ($arrTareas as $key => $value) 
                {
                    $this->mfunciones_logica->InsertarSolicitudTarea($estructura_id, $value, $nombre_usuario, $fecha_actual);
                }
            }
            
            $this->Solicitud_Ver();
        }
    }
    
    public function AprobarForm() {
        
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
            $codigo = $this->input->post('codigo', TRUE);
            $estado = $this->input->post('estado', TRUE);

            $arrResultado = $this->mfunciones_logica->ObtenerSolicitudMantenimiento($codigo, $estado);

            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "solicitud_id" => $value["solicitud_id"],
                        "solicitud_nit" => $value["solicitud_nit"],
                        "solicitud_nombre_empresa" => $value["solicitud_nombre"],
                        "solicitud_otro" => $value["solicitud_otro"],
                        "solicitud_otro_detalle" => $value["solicitud_otro_detalle"],
                        "solicitud_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["solicitud_fecha"]),
                        "solicitud_confirmado" => $value["solicitud_confirmado"],
                        "solicitud_token" => $value["solicitud_token"],
                        "solicitud_ip" => $value["solicitud_ip"],
                        "solicitud_estado" => $this->mfunciones_generales->GetValorCatalogo($value["solicitud_estado"], 'estado_solicitud'),
                        "solicitud_observacion" => $value["solicitud_observacion"],
                        "solicitud_email" => $value["solicitud_email"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            } 
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }

            // Listado de Servicios
            $arrTareas = $this->mfunciones_logica->ObtenerTareasSolicitud($codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrTareas);
            $data["arrTareas"] = $arrTareas;
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
            
            $data["arrRespuesta"] = $lst_resultado;

            $this->load->view('solicitud_mantenimiento/view_solicitud_aprobar_ver', $data);
        }
    }
    
    public function VerificarNIT() {
        
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
            $codigo = floatval($this->input->post('codigo', TRUE));
            
            $nit = $codigo;
            
            // Variable que indica si esta registrado sólo en PayStudio
            $registrado_sistema = 0;
            
            // Se usa esta variable para verificar si se contró el NIT en PayStudio o en el Sistema
            $swNIT = 0;

                // A. SE PREGUNTA A PAYSTUDIO (NAZIR) A TRAVÉS DE SU WEB SERVICE SI EL NIT EXISTE Y POBLAR LOS CAMPOS

                $RespuestaWS = $this->mfunciones_generales->ConsultaWebServiceNIT($nit);
                if (isset($RespuestaWS[0])) 
                {
                    $swNIT = 1;
                    $lst_resultado1 = $RespuestaWS;
                    $registrado_sistema = 1;
                }
                
                // Detalle del Comercio
                $arrResultado1 = $this->mfunciones_logica->ObtenerEmpresaNIT($nit);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                if (isset($arrResultado1[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado1 as $key => $value) 
                    {
                        $item_valor = array(
                            "empresa_id" => $value["empresa_id"],
                            "ejecutivo_id" => $value["ejecutivo_id"],
                            "empresa_consolidada" => $value["empresa_consolidada"],
                            "empresa_categoria_codigo" => $value["empresa_categoria"],
                            "empresa_categoria_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["empresa_categoria"], 'empresa_categoria'),
                            "empresa_nombre" => $value["empresa_nombre"],
                            "empresa_tipo_sociedad_codigo" => $value["empresa_tipo_sociedad"],
                            "empresa_tipo_sociedad_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_tipo_sociedad"], 'TPS'),
                            "empresa_rubro_codigo" => $value["empresa_rubro"],
                            "empresa_rubro_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_rubro"], 'RUB'),
                            "empresa_perfil_comercial_codigo" => $value["empresa_perfil_comercial"],
                            "empresa_perfil_comercial_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_rubro"], 'PEC'),
                            "empresa_mcc_codigo" => $value["empresa_mcc"],
                            "empresa_mcc_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_mcc"], 'MCC'),
                            "ejecutivo_nombre" => $value["ejecutivo_nombre"],
                            "usuario_id" => $value["usuario_id"]
                        );
                        $lst_resultado1[$i] = $item_valor;

                        $i++;
                    }

                    $swNIT = 1;
                    
                    $registrado_sistema = 0;
                    
                }

            if($swNIT == 0)
            {
                $lst_resultado1 = array();
            }
            
            $data['registrado_sistema'] = $registrado_sistema;
            $data["nit"] = $nit;
            $data["arrRespuesta"] = $lst_resultado1;

            $this->load->view('solicitud_mantenimiento/view_solicitud_aprobar_nit', $data);
        }
    }
        
    public function Aprobar_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_POST['codigo_solicitud']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // 1. SE CAPTURAN LOS DATOS DEL PASO 1 Y 2
            
            $codigo_solicitud = $this->input->post('codigo_solicitud', TRUE);
            $solicitud_nit = $this->input->post('solicitud_nit', TRUE);
            
            $valor_recibido = $this->input->post('codigo_empresa', TRUE);
                        
            $solicitud_fecha_visita = $this->input->post('solicitud_fecha_visita', TRUE);
            $tiempo_visita = $this->input->post('tiempo_visita', TRUE);
            
            // 2. SE VALIDA LOS CAMPOS
            
            if((int)$codigo_solicitud == 0 || (int)$solicitud_nit == 0 || $this->mfunciones_generales->VerificaFechaD_M_Y_H_M($solicitud_fecha_visita) == false || (int)$tiempo_visita == 0)
            {
                js_error_div_javascript($this->lang->line('CamposObligatorios'));
                exit();
            }
            
            if($valor_recibido == '')
            {
                js_error_div_javascript($this->lang->line('FormularioSinOpciones') . ' ' . $this->lang->line('verifique_nit'));
                exit();
            }
            
            $array_valores = explode("|", $valor_recibido);
            
            $codigo_empresa = $array_valores[0];
            $codigo_ejecutivo = $array_valores[1];
            
            $sw = 0;
            $errorNIT = '';
            
            // A. SE PREGUNTA A PAYSTUDIO (NAZIR) A TRAVÉS DE SU WEB SERVICE SI EL NIT EXISTE Y POBLAR LOS CAMPOS        
            $RespuestaWS = $this->mfunciones_generales->ConsultaWebServiceNIT($solicitud_nit);
            if (isset($RespuestaWS[0])) 
            {
                $sw = 1;
                js_error_div_javascript($this->lang->line('verifique_nit_solo_paystudio'));
                exit();
            }

            // B. SE PREGUNTA EN LA BASE DE DATOS DEL SISTEMA EMPRESAS CONSOLIDADAS
            $arrResultadoNit = $this->mfunciones_logica->ObtenerComercioPorNIT($solicitud_nit);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultadoNit);
				
            if (!isset($arrResultadoNit[0])) 
            {
                $sw = 1;
                js_error_div_javascript($this->lang->line('verifique_nit_mantenimiento'));
                exit();
            }
            
            // Listado Detalle Empresa
            $arrEmpresa = $this->mfunciones_generales->GetDatosEmpresa($codigo_empresa);
            if (!isset($arrEmpresa[0]))
            {
                js_error_div_javascript($this->lang->line('verifique_nit_mantenimiento'));
                exit();
            }
            
            // FIN VALIDACIÓN
            
            $accion_usuario = $_SESSION["session_informacion"]["login"];
            $accion_fecha = date('Y-m-d H:i:s');
                        
            // PASO 2: Insertar en la tabla "mantenimiento" el nuevo registro
            $arrResultado1 = $this->mfunciones_logica->InsertarMantenimiento($codigo_ejecutivo, $codigo_empresa, $accion_fecha, $accion_usuario, $accion_fecha);
            
            $codigo_mantenimiento = $arrResultado1;

            // PASO 3: Se registra la fecha en el calendario del Ejecutivo de Cuentas

            $cal_visita_ini = $solicitud_fecha_visita;            
                $cal_visita_fin = new DateTime($this->mfunciones_generales->getFormatoFechaDateTime($cal_visita_ini));
            $cal_visita_fin->add(new DateInterval('PT' . $tiempo_visita . 'M'));
                $cal_visita_fin = $cal_visita_fin->format('d/m/Y H:i');
            
            $fecha_visita_ini = $this->mfunciones_generales->getFormatoFechaDateTime($cal_visita_ini);
            $fecha_visita_fin = $this->mfunciones_generales->getFormatoFechaDateTime($cal_visita_fin);

            $this->mfunciones_logica->InsertarFechaCaendario($codigo_ejecutivo, $codigo_mantenimiento, 2, $fecha_visita_ini, $fecha_visita_fin, $accion_usuario, $accion_fecha);
            
            // PASO 4: Se crea la carpeta del mantenimiento
        
            $path = RUTA_MANTENIMIENTOS . 'man_' . $codigo_mantenimiento;

            if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
            {
                mkdir($path, 0755, TRUE);
            }

                // Se crea el archivo html para evitar ataques de directorio
                $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($path . '/index.html', $cuerpo_html);
                
            // PASO 5; Se cambia el Estado de la Solicitud a "Aprobado"
            
                $this->mfunciones_logica->AprobarSolicitudMantenimiento($accion_usuario, $accion_fecha, $codigo_solicitud);
            
            $texto = "¡El Mantenimiento se registró correctamente!" . $this->lang->line('aprobar_solicitud_guardado');
            
            // PASO 6: SE ENVÍA UN CORREO ELECTRÓNICO DE NOTIFICACIÓN A LA EMPRESA ACEPTANTE
                
                // Listado de Servicios
                $arrTareas = $this->mfunciones_logica->ObtenerTareasSolicitud($codigo_solicitud);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrTareas);
                
                $listadoTareas = '';
                
                if(isset($arrTareas[0]))
                {
                    foreach ($arrTareas as $key => $value) 
                    {
                        $listadoTareas .= ' - ' . $value["tarea_detalle"];
                        $listadoTareas .= "<br />";
                    }                                
                }
                
                // Si se eligió otra tarea =/
            
                $arrDatoMant = $this->mfunciones_logica->ObtenerSolicitudMantenimiento($codigo_solicitud, 1);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDatoMant);
            
                if($arrDatoMant[0]['solicitud_otro'] == 1)
                {
                    $listadoTareas .= ' - Otro: ' . $arrDatoMant[0]["solicitud_otro_detalle"];
                    $listadoTareas .= "<br />";
                }
                
                $arrayNotificacionEA[0] = array(
                    "codigo_mantenimiento" => $codigo_mantenimiento,
                    "codigo_empresa" => $codigo_empresa,
                    "fecha_visita" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($fecha_visita_ini),
                    "fecha_fin" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($fecha_visita_fin),
                    "direccion_visita" => $arrEmpresa[0]['empresa_direccion_literal'],
                    "listadoTareas" => $listadoTareas
                );
            
                $correo_ea = $this->mfunciones_generales->EnviarCorreo('notificar_mantenimiento_EA', $arrEmpresa[0]['empresa_email'], $arrEmpresa[0]['empresa_nombre_referencia'], $arrayNotificacionEA, 0);

                if(!$correo_ea)
                {
                    $texto .= '<br />' . $this->lang->line('FormularioNoNotificacion') . ' (EA)';
                }
            
            // PASO 7: SE ENVÍA UN CORREO ELECTRÓNICO DE NOTIFICACIÓN AL EJECUTIVO DE CUENTAS

            /* Notificar Instancia INICIO */
            
                // a) Se busca la información de a quién se notificará
            
                $destinatario_nombre = $arrEmpresa[0]['ejecutivo_asignado_nombre'];
                $destinatario_correo = $arrEmpresa[0]['ejecutivo_asignado_correo'];
                
                $correo_enviado = $this->mfunciones_generales->EnviarCorreo('notificar_instancia_mantenimiento', $destinatario_correo, $destinatario_nombre, $arrayNotificacionEA, 0);

                if(!$correo_enviado)
                {
                    $texto .= '<br />' . $this->lang->line('FormularioNoNotificacion');
                }
            
            /* Notificar Instancia FIN */
            
            /* Se Envía a la APP la Notificación por Push   tipo_notificacion, $tipo_visita, $codigo_visita */
            $this->mfunciones_generales->EnviarNotificacionPush(4, 2, $codigo_empresa);
                
            $data['texto'] = $texto;
            $data['codigo_mantenimiento'] = $codigo_mantenimiento;
            
            $this->load->view('solicitud_mantenimiento/view_solicitud_aprobar_guardado', $data);
                        
        }
    }
}
?>