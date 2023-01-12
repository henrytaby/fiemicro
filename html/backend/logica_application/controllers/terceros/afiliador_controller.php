<?php
/**
 * @file 
 * Codigo que implementa el controlador para la autenticacion de usuario en el sistema
 * @brief  CONTROLADOR SOLICITUD DE PROSPECTO
 * @author JRAD
 * @date Mar 21, 2014
 * @copyright 2017 JRAD
 */
/**
 * Controlador para SOLICITUD DE AFILIACIÓN POR TERCEROS
 * @brief CONTROLADOR de AFILIACIÓN POR TERCEROS
 * @class Afiliador_controller 
 */
class Afiliador_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 42;

    function __construct() {
        parent::__construct();
    }
    /**
     * carga la vista para el formulario de login
     * @brief CARGAR LOGIN
     * @author JRAD
     * @date Mar 21, 2014     
     */
    
    public function Solicitud_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estado = 0;
        
        if(isset($_POST['estado']))
        {
            $estado = $this->input->post('estado', TRUE);
        }
        
        if($estado == 2 || $estado == 5)
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        if($estado == 1)
        {
            js_error_div_javascript('La bandeja "' . $this->lang->line('aprobado') . '" no está habilitada.');
            exit();
        }
        
        $codigo_etapa = 0;

        $filtro = '';
        
        if($estado == 0) { $filtro = ' AND t.tercero_asistencia=1 '; $codigo_etapa = 7; $fila_fecha = 'terceros_fecha'; }
        if($estado == 16) { $filtro = ' AND t.tercero_asistencia=0 '; $codigo_etapa = 7; $fila_fecha = 'terceros_fecha'; }
        if($estado == 1 || $estado == 15) { $codigo_etapa = 8; $fila_fecha = 'aprobado_fecha'; }
        if($estado == 3 || $estado == 4) { $filtro = ' OR t.terceros_estado=3 '; $codigo_etapa = 0; $fila_fecha = 'rechazado_fecha'; }
        
        $aux_fecha_fin = date("Y-m-d H:i:s");
        $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa($codigo_etapa);
        $tiempo_etapa = $arrEtapa[0]['etapa_tiempo'];
        
        $arrResultado = $this->mfunciones_logica->ObtenerSolicitudTerceros(-1, $estado, $filtro);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        $horas_laborales = 0;
        $resultado_bandera = 0;
        
        if (isset($arrResultado[0]))
        {
            $i = 0;
            
            $conf_intentos = $this->mfunciones_generales->getIntentosFlujoCOBIS();
            
            foreach ($arrResultado as $key => $value) {
                
                if($estado <= 1 || $estado == 16)
                {
                    $tiempo = $this->mfunciones_generales->ObtenerHorasLaboral($value[$fila_fecha], $aux_fecha_fin, $tiempo_etapa);
                    
                    $horas_laborales = $tiempo->horas_laborales;
                    $resultado_bandera = $tiempo->resultado_bandera;
                    
                }
                else
                {
                    $horas_laborales = 0;
                    $resultado_bandera = 0;
                }
                
                if($estado == 15)
                {   // Si fue registrado el flujo Cobis se muestra su fecha, caso contrario la fecha de aprobación.
                    if((int)$value['f_cobis_actual_etapa'] <= 0)
                    {
                        $fila_fecha = 'aprobado_fecha';
                    }
                    else
                    {
                        $fila_fecha = 'f_cobis_actual_fecha';
                    }
                }
                
                $item_valor = array(
                    "terceros_id" => $value["terceros_id"],
                    "tipo_persona_id_codigo" => $value["tipo_persona_id"],
                    "tipo_persona_id_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["tipo_persona_id"], 'tipo_persona'),
                    "afiliador_id" => $value["afiliador_id"],
                    "terceros_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value[$fila_fecha]),
                    "terceros_estado" => $value["terceros_estado"],
                    "tercero_asistencia" => $value["tercero_asistencia"],
                    "tiempo_horas" => $horas_laborales,
                    "tiempo_texto" => $resultado_bandera,
                    "terceros_columna1" => $this->mfunciones_generales->ObtenerNombreRegionCodigo($value["codigo_agencia_fie"]),
                    "terceros_columna2" => $this->mfunciones_generales->GetValorCatalogoDB($value['dir_localidad_ciudad'], 'dir_localidad_ciudad'),
                    "terceros_columna3" => $value["di_primernombre"] . ' ' . $value["di_primerapellido"] . ' ' . $value["di_segundoapellido"],
                    "terceros_columna4" => $value["cI_numeroraiz"] . ' ' . $value["cI_complemento"] . ' ' . $this->mfunciones_generales->GetValorCatalogoDB($value['cI_lugar_emisionoextension'], 'cI_lugar_emisionoextension'),
                    "terceros_columna5" => $value["direccion_email"] . '<br />' . $value["dir_notelefonico"],
                    "tipo_cuenta" => $this->mfunciones_generales->GetValorCatalogoDB($value['tipo_cuenta'], 'tipo_cuenta'),
                    "geo1" => $value["coordenadas_geo_dom"],
                    "geo2" => $value["coordenadas_geo_trab"],
                    'envio' => $this->mfunciones_generales->GetValorCatalogo($value['envio'], 'tercero_envio'),
                    "segip_operador_resultado" => $value["segip_operador_resultado"],
                    "f_cobis_actual_etapa_codigo" => $value["f_cobis_actual_etapa"],
                    "f_cobis_actual_etapa" => $this->mfunciones_generales->GetValorCatalogo($value['f_cobis_actual_etapa'], 'flujo_cobis_etapa'),
                    "f_cobis_excepcion" => $value["f_cobis_excepcion"],
                    "f_cobis_excepcion_motivo" => $this->mfunciones_generales->GetValorCatalogoDB($value['f_cobis_excepcion_motivo'], 'motivo_flujo_cobis'),
                    "f_cobis_excepcion_motivo_texto" => $value["f_cobis_excepcion_motivo_texto"],
                    "f_cobis_flag_rechazado" => $value["f_cobis_flag_rechazado"],
                    "max_intentos" => ($value["f_cobis_actual_intento"] >= $conf_intentos->conf_f_cobis_intentos ? 1 : 0)
                );
                $lst_resultado[$i] = $item_valor;
                
                $i++;
            }
        }
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }
        
        $estado_texto = $this->mfunciones_generales->GetValorCatalogo($estado, 'terceros_estado');
        
        $data["estado"] = $estado;
        $data["estado_texto"] = $estado_texto;
        
        $data["arrRespuesta"] = $lst_resultado;
        
        $data["tiempo_etapa"] = $tiempo_etapa;
        
        $_SESSION['direccion_bandeja_actual'] = 'Afiliador/Ver';
        
        $this->load->view('terceros/view_solicitud_ver', $data);
        
    }
    
    public function SolicitudCobis() {
        
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

            if($estado != 1)
            {
                if($estado != 15)
                {
                    $texto_error = 'La solicitud no es correcta, por favor verifique el codigo.';

                    js_invocacion_javascript('alert("' . $texto_error . '"); Ajax_CargarOpcionMenu("Menu/Principal");');
                    exit();
                }
            }
            
            $arrTercero = $this->mfunciones_generales->DatosTercerosEmail($codigo);
        
            if(!$arrTercero)
            {   
                $texto_error = 'El codigo de registro no existe, por favor verifique el codigo.';

                js_invocacion_javascript('alert("' . $texto_error . '"); Ajax_CargarOpcionMenu("Menu/Principal");');
                exit();
            }

            $texto_error = 'El registro se encuentra en el estado ' . $this->mfunciones_generales->GetValorCatalogo($arrTercero[0]["terceros_estado_codigo"], 'terceros_estado') . ' por lo que no puede ser marcado como Registrado en COBIS, por favor verifique el codigo.';

            if($arrTercero[0]["terceros_estado_codigo"] != 1)
            {
                if($arrTercero[0]["terceros_estado_codigo"] != 15)
                {
                    js_invocacion_javascript('alert("' . $texto_error . '"); Ajax_CargarOpcionMenu("Menu/Principal");');
                    exit();
                }
            }
            
            $arrResultado = $this->mfunciones_logica->ObtenerDetalleSolicitudTerceros($codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0]))
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) {
                    $item_valor = array(

                        "terceros_id" => $value["terceros_id"],
                        "tipo_persona_id_codigo" => $value["tipo_persona_id"],
                        "tipo_persona_id_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["tipo_persona_id"], 'tipo_persona'),
                        "nombre_persona" => $value["di_primernombre"] . ' ' . $value["di_primerapellido"] . ' ' . $value["di_segundoapellido"],
                        "email" => $value["direccion_email"],
                        "onboarding_numero_cuenta" => $value["onboarding_numero_cuenta"],
                        "terceros_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["terceros_fecha"]),
                        "terceros_estado" => $value["terceros_estado"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            }
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
            
            $data["arrRespuesta"] = $lst_resultado;

            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
            
            $this->load->view('terceros/view_cobis_ver', $data);
        }
    }
    
    public function SolicitudCobis_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        $onboarding_numero_cuenta = $this->input->post('onboarding_numero_cuenta', TRUE);
        
        $codigo_usuario = $_SESSION["session_informacion"]["codigo"];
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if((int)$estructura_id == 0 || $onboarding_numero_cuenta == '')
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        // Se valida antes que código COBIS no esté ya registrado
        
        $arrResultado = $this->mfunciones_logica->VerificaCuentaCOBIS($onboarding_numero_cuenta);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0]))
        {
            if((int)$arrResultado[0]['terceros_id'] != (int)$estructura_id)
            {
                js_error_div_javascript('El Número de Cuenta "' . $onboarding_numero_cuenta . '" ya fue registrado, por favor verifique el número.');
                exit();
            }
        }
        
        // Se guarda el registro
        
        $this->mfunciones_logica->CompletarCOBISTerceros($onboarding_numero_cuenta, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id);
        
        // Notificar Proceso Onboarding     0=No Regionalizado      1=Si Regionalizado
        $this->mfunciones_generales->NotificacionEtapaTerceros($estructura_id, 9, 1);
        
        // FUNCION PARA OBTENER EL CÓDIGO DE PROSPECTO EN BASE AL CODIGO TERCERO
        $codigo_prospecto = $this->mfunciones_generales->ObtenerProspectoTerceros($estructura_id);
        
        // Se registra para las Etapas Onboarding   Pasa a: Bandeja Onboarding Contrato   Etapa Nueva     Etapa Actual
        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 9, 8, $nombre_usuario, $fecha_actual, 0);
        
        js_invocacion_javascript('alert("El registro se marco como Registrado en COBIS y se notifico a la instancia respectiva.");');
        
        js_invocacion_javascript('Ajax_CargarAccion_BandejaSolicitud(0);');
        
    }
    
    
    public function AprobarForm() {
        
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
            $codigo_solicitud = $this->input->post('codigo_solicitud', TRUE);
            
            $arrTercero = $this->mfunciones_generales->DatosTercerosEmail($codigo_solicitud);
            
            if(!$arrTercero)
            {
                js_error_div_javascript('El codigo de registro no existe, por favor verifique el codigo.');
                exit();
            }

            $texto_error = 'El registro se encuentra en el estado ' . $this->mfunciones_generales->GetValorCatalogo($arrTercero[0]["terceros_estado_codigo"], 'terceros_estado') . ' por lo que no puede ser marcado como ' . $this->mfunciones_generales->GetValorCatalogo(1, 'terceros_estado') . ' desde esta opción, por favor verifique el codigo.';

            if($arrTercero[0]["terceros_estado_codigo"] != 15)
            {
                js_error_div_javascript($texto_error);
                exit();
            }
            
            if($arrTercero[0]["f_cobis_actual_etapa"] == 99)
            {
                js_error_div_javascript(sprintf($this->lang->line('f_cobis_error_flujo_completo'), $codigo_solicitud));
                exit();
            }
            
            if($arrTercero[0]["f_cobis_flujo"] == 1)
            {
                js_error_div_javascript(sprintf($this->lang->line('f_cobis_error_flujo_activo'), $codigo_solicitud));
                exit();
            }
            
            $conf_intentos = $this->mfunciones_generales->getIntentosFlujoCOBIS();
            
            if($arrTercero[0]["f_cobis_flag_rechazado"] == 1 && $conf_intentos->conf_f_cobis_intentos_operativo == 0)
            {
                js_error_div_javascript(sprintf($this->lang->line('f_cobis_error_flag_rechazo'), $codigo_solicitud));
                exit();
            }
            
            if($arrTercero[0]["f_cobis_actual_intento"] >= $conf_intentos->conf_f_cobis_intentos)
            {
                js_error_div_javascript(sprintf($this->lang->line('f_cobis_error_intentos'), $codigo_solicitud));
                exit();
            }
            
            $data['conf_intentos'] = $conf_intentos->conf_f_cobis_intentos;
            $data['codigo_solicitud'] = $codigo_solicitud;
            $data['estado'] = $this->input->post('estado', TRUE);
            $data['arrRespuesta'] = $arrTercero;

            $this->load->view('terceros/view_aprobar_pregunta', $data);
        }
    }
    
    public function Aprobar_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_POST['codigo_solicitud_conf']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            $codigo_usuario = $_SESSION["session_informacion"]["codigo"];
            $accion_usuario = $_SESSION["session_informacion"]["login"];
			
            $codigo_registro = $this->input->post('codigo_solicitud_conf', TRUE);
            $estado = (int)$this->input->post('estado', TRUE);
            
            $arrTercero = $this->mfunciones_generales->DatosTercerosEmail($codigo_registro);
            
            if(!$arrTercero)
            {
                js_error_div_javascript('El codigo de registro no existe, por favor verifique el codigo.');
                exit();
            }

            if($arrTercero[0]["terceros_estado_codigo"] == 1)
            {
                js_error_div_javascript('El registro se encuentra en el estado ' . $this->mfunciones_generales->GetValorCatalogo($arrTercero[0]["terceros_estado_codigo"], 'terceros_estado') . ' por lo que no puede ser marcado como ' . $this->mfunciones_generales->GetValorCatalogo(1, 'terceros_estado') . ', por favor verifique el codigo.');
                exit();
            }
            
            if($arrTercero[0]["f_cobis_actual_etapa"] == 99)
            {
                js_error_div_javascript(sprintf($this->lang->line('f_cobis_error_flujo_completo'), $codigo_registro));
                exit();
            }
            
            if($arrTercero[0]["f_cobis_flujo"] == 1)
            {
                js_error_div_javascript(sprintf($this->lang->line('f_cobis_error_flujo_activo'), $codigo_registro));
                exit();
            }
            
            $conf_intentos = $this->mfunciones_generales->getIntentosFlujoCOBIS();
            
            if($arrTercero[0]["f_cobis_flag_rechazado"] == 1 && $conf_intentos->conf_f_cobis_intentos_operativo == 0)
            {
                js_error_div_javascript(sprintf($this->lang->line('f_cobis_error_flag_rechazo'), $codigo_registro));
                exit();
            }
            
            if($arrTercero[0]["f_cobis_actual_intento"] >= $conf_intentos->conf_f_cobis_intentos)
            {
                js_error_div_javascript(sprintf($this->lang->line('f_cobis_error_intentos'), $codigo_registro));
                exit();
            }
            
            // *** Llamar a función para Aprobar y Flujo COBIS en 2do plano ***
            $this->mfunciones_generales->Aprobar_FlujoCobis_background($codigo_registro, $codigo_usuario, $accion_usuario);
            
            $data['terceros_id'] = $codigo_registro;
            $data['estado'] = $estado;
            
            $this->load->view('terceros/view_aprobar_flujo', $data);
        }
    }
    
    public function Reservar_Guardar() {
        
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
            $accion_usuario = $_SESSION["session_informacion"]["login"];
            $accion_fecha = date('Y-m-d H:i:s');
			
            $codigo_solicitud = $this->input->post('codigo_solicitud', TRUE);
            $estado = $this->input->post('estado', TRUE);
            
            if($estado > 4)
            {
                $texto_error = 'La solicitud no es correcta, por favor verifique el codigo.';

                js_error_div_javascript($texto_error);
                exit();
            }
            
            $arrTercero = $this->mfunciones_generales->DatosTercerosEmail($codigo_solicitud);
        
            if(!$arrTercero)
            {   
                $texto_error = 'El codigo de registro no existe, por favor verifique el codigo.';

                js_error_div_javascript($texto_error);
                exit();
            }

            $texto_error = 'El registro se encuentra en el estado ' . $this->mfunciones_generales->GetValorCatalogo($arrTercero[0]["terceros_estado_codigo"], 'terceros_estado') . ' por lo que no puede ser marcado como ' . $this->mfunciones_generales->GetValorCatalogo(15, 'terceros_estado') . ', por favor verifique el codigo.';

            if($arrTercero[0]["terceros_estado_codigo"] == 2 || $arrTercero[0]["terceros_estado_codigo"] > 4)
            {
                js_error_div_javascript($texto_error);
                exit();
            }
            
            // -- Fin validaciones
            
            $this->mfunciones_logica->ReservarSolicitudTerceros($accion_usuario, $accion_fecha, $codigo_solicitud);
            
            js_invocacion_javascript('alert("¡El Registro fue marcado como ' . $this->mfunciones_generales->GetValorCatalogo(15, 'terceros_estado') . ' Correctamente! Se procederá a actualizar la bandeja."); Ajax_CargarAccion_BandejaSolicitud(15);');
            
            //$this->Solicitud_Ver();
                        
        }
    }
    
    public function SolicitudForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_onboarding');
        
        $this->formulario_onboarding->DefinicionValidacionFormulario();

        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            $codigo = $this->input->post('codigo', TRUE);
            $estado = $this->input->post('estado', TRUE);

            $arrResultado = $this->mfunciones_logica->ObtenerSolicitudTerceros($codigo, $estado);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
            
            if (isset($arrResultado[0]))
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) {
                    $item_valor = array(

                        "terceros_id" => $value["terceros_id"],
                        "tipo_persona_id_codigo" => $value["tipo_persona_id"],
                        "tipo_persona_id_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["tipo_persona_id"], 'tipo_persona'),
                        "afiliador_id" => $value["afiliador_id"],
                        "terceros_nombre_persona" => $value["terceros_nombre_persona"],
                        "terceros_ci" => $value["terceros_ci"],
                        "terceros_estado_civil" => $value["terceros_estado_civil"],
                        "terceros_email" => $value["direccion_email"],
                        "terceros_telefono" => $value["terceros_telefono"],
                        "terceros_nit" => $value["terceros_nit"],
                        "terceros_pais" => $value["terceros_pais"],
                        "terceros_profesion" => $value["terceros_profesion"],
                        "terceros_ingreso" => $value["terceros_ingreso"],
                        "terceros_conyugue_nombre" => $value["terceros_conyugue_nombre"],
                        "terceros_conyugue_actividad" => $value["terceros_conyugue_actividad"],
                        "terceros_referencias" => $value["terceros_referencias"],
                        "terceros_actividad_principal" => $value["terceros_actividad_principal"],
                        "terceros_lugar_trabajo" => $value["terceros_lugar_trabajo"],
                        "terceros_cargo" => $value["terceros_cargo"],
                        "terceros_ano_ingreso" => $value["terceros_ano_ingreso"],
                        "terceros_mis_coordenadas_mapa" => $value["coordenadas_geo_dom"],
                        "terceros_mis_coordenadas_mapa2" => $value["coordenadas_geo_trab"],
                        "terceros_llevar" => $this->mfunciones_generales->GetValorCatalogo($value["envio"], 'terceros_llevar'),

                        "terceros_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["terceros_fecha"]),
                        "terceros_estado" => $value["terceros_estado"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
            
            $data["arrCajasHTML"] = $this->formulario_onboarding->ConstruccionCajasFormulario($lst_resultado[0]);
            $data["strValidacionJqValidate"] = $this->formulario_onboarding->GeneraValidacionJavaScript();
            
            $data["arrRespuesta"] = $lst_resultado;

                /***** REGIONALIZACIÓN INICIO ******/                
                    $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
                    $data['nombre_region'] = $lista_region->region_nombres_texto;
                /***** REGIONALIZACIÓN FIN ******/
            
            $data["tipo_accion"] = 1;
                    
            $this->load->view('terceros/view_solicitud_editar', $data);
        }
    }
    
    public function Solicitud_Guardar() {
        
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
            $accion_usuario = $_SESSION["session_informacion"]["login"];
            $accion_fecha = date('Y-m-d H:i:s');
	
            $estructura_id = $this->input->post('estructura_id', TRUE);
            
            $terceros_nombre_persona = $this->input->post('terceros_nombre_persona', TRUE);
            $terceros_ci = $this->input->post('terceros_ci', TRUE);
            $terceros_estado_civil = $this->input->post('terceros_estado_civil', TRUE);
            $terceros_email = $this->input->post('terceros_email', TRUE);
            $terceros_telefono = $this->input->post('terceros_telefono', TRUE);
            $terceros_nit = $this->input->post('terceros_nit', TRUE);
            $terceros_pais = $this->input->post('terceros_pais', TRUE);
            $terceros_profesion = $this->input->post('terceros_profesion', TRUE);
            $terceros_ingreso = $this->input->post('terceros_ingreso', TRUE);
            $terceros_conyugue_nombre = $this->input->post('terceros_conyugue_nombre', TRUE);
            $terceros_conyugue_actividad = $this->input->post('terceros_conyugue_actividad', TRUE);
            $terceros_referencias = $this->input->post('terceros_referencias', TRUE);
            $terceros_actividad_principal = $this->input->post('terceros_actividad_principal', TRUE);
            $terceros_lugar_trabajo = $this->input->post('terceros_lugar_trabajo', TRUE);
            $terceros_cargo = $this->input->post('terceros_cargo', TRUE);
            $terceros_ano_ingreso = $this->input->post('terceros_ano_ingreso', TRUE);

            $this->mfunciones_logica->ActualizarSolicitudTerceros($terceros_nombre_persona, $terceros_ci, $terceros_estado_civil, $terceros_email, $terceros_telefono, $terceros_nit, $terceros_pais, $terceros_profesion, $terceros_ingreso, $terceros_conyugue_nombre, $terceros_conyugue_actividad, $terceros_referencias, $terceros_actividad_principal, $terceros_lugar_trabajo, $terceros_cargo, $terceros_ano_ingreso, $accion_usuario, $accion_fecha, $estructura_id);

            $this->Solicitud_Ver();
                        
        }
    }
    
    public function ExportarCSV() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
 
        $arrResultado = $this->mfunciones_logica->ObtenerTercerosCSV();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0]))
        {
            
            $i = 0;

            foreach ($arrResultado as $key => $value) {

                // Revisar todos los campos, remplazar espacio en blanco por vacío
		foreach ($value as $key_aux => $value_aux)
		{
                    if((string)$arrResultado[$i][$key_aux] == ' ')
                    {
                        $arrResultado[$i][$key_aux] = '';
                    }
		}
                
                $arrResultado[$i]['rp_direccion'] = $this->mfunciones_generales->GetValorCatalogoDB($arrResultado[$i]['dir_departamento'], 'dir_departamento');

                $arrResultado[$i]['di_primernombre'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['di_primernombre']);
                $arrResultado[$i]['di_segundo_otrosnombres'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['di_segundo_otrosnombres']);
                $arrResultado[$i]['di_primerapellido'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['di_primerapellido']);
                $arrResultado[$i]['di_segundoapellido'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['di_segundoapellido']);
                $arrResultado[$i]['di_apellido_casada'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['di_apellido_casada']);
                $arrResultado[$i]['dir_ubicacionreferencial'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['dir_ubicacionreferencial']);
                $arrResultado[$i]['dir_av_calle_pasaje'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['dir_av_calle_pasaje']);
                $arrResultado[$i]['dir_edif_cond_urb'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['dir_edif_cond_urb']);
                $arrResultado[$i]['dir_numero'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['dir_numero']);
                $arrResultado[$i]['dir_av_calle_pasaje_neg'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['dir_av_calle_pasaje_neg']);
                $arrResultado[$i]['dir_edif_cond_urb_neg'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['dir_edif_cond_urb_neg']);
                $arrResultado[$i]['dir_numero_neg'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['dir_numero_neg']);
                $arrResultado[$i]['emp_nombre_empresa'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['emp_nombre_empresa']);
                $arrResultado[$i]['emp_direccion_trabajo'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['emp_direccion_trabajo']);
                $arrResultado[$i]['emp_descripcion_cargo'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['emp_descripcion_cargo']);
                $arrResultado[$i]['rp_nombres'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['rp_nombres']);
                $arrResultado[$i]['rp_primer_apellido'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['rp_primer_apellido']);
                $arrResultado[$i]['rp_segundo_apellido'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['rp_segundo_apellido']);
                $arrResultado[$i]['rp_direccion'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['rp_direccion']);
                $arrResultado[$i]['con_primer_nombre'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['con_primer_nombre']);
                $arrResultado[$i]['con_segundo_nombre'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['con_segundo_nombre']);
                $arrResultado[$i]['con_primera_pellido'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['con_primera_pellido']);
                $arrResultado[$i]['con_segundoa_pellido'] = $this->mfunciones_generales->TextoNoAcentoNoEspaciosAux($arrResultado[$i]['con_segundoa_pellido']);
                
                // Quitar espacio en blanco por vacio. Req. 25/08
                if($arrResultado[$i]['con_primer_nombre'] == ' ') { $arrResultado[$i]['con_primer_nombre'] = ''; }
                if($arrResultado[$i]['con_segundo_nombre'] == ' ') { $arrResultado[$i]['con_segundo_nombre'] = ''; }
                if($arrResultado[$i]['con_primera_pellido'] == ' ') { $arrResultado[$i]['con_primera_pellido'] = ''; }
                if($arrResultado[$i]['con_segundoa_pellido'] == ' ') { $arrResultado[$i]['con_segundoa_pellido'] = ''; }
                
                
                // Req. 29/10/2020
                
                $accion_usuario = $_SESSION["session_informacion"]["login"];
                $accion_fecha = date('Y-m-d H:i:s');
                
                //$this->mfunciones_logica->ReservarSolicitudTerceros($accion_usuario, $accion_fecha, $arrResultado[$i]['codigo_initium']);
                
                $i++;
            }
            
        }
        
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"initium_onboarding_rpa".".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        # Generate CSV data from array
        $fh = fopen('php://temp', 'rw'); # don't create a file, attempt
        
        if (isset($arrResultado[0]))
        {
            # write out the headers
            fputcsv($fh, array_keys(current($arrResultado)));

            # write out the data
            foreach ( $arrResultado as $row ) {
                            fputcsv($fh, $row);
            }
            rewind($fh);
            $csv = stream_get_contents($fh);
        }
        else
        {
            $csv = '';
        }
        
        fclose($fh);

        print ($csv);
	exit;
    }
    
    // -- Req. Consulta COBIS y SEGIP
    
    public function ValidacionOperativa() {
        
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

            // El registor debe venir de la bandeja de Validación Operativa
            if($estado != 16)
            {
                $texto_error = 'La solicitud no es correcta, por favor verifique el codigo.';

                js_invocacion_javascript('alert("' . $texto_error . '"); Ajax_CargarOpcionMenu("Menu/Principal");');
                exit();
            }
            
            $arrTercero = $this->mfunciones_generales->DatosTercerosEmail($codigo);
        
            if(!$arrTercero)
            {   
                $texto_error = 'El codigo de registro no existe, por favor verifique el codigo.';

                js_invocacion_javascript('alert("' . $texto_error . '"); Ajax_CargarOpcionMenu("Menu/Principal");');
                exit();
            }

            $texto_error = 'El registro se encuentra en el estado ' . $this->mfunciones_generales->GetValorCatalogo($arrTercero[0]["terceros_estado_codigo"], 'terceros_estado') . ' por lo que no puede realizarse la ' . $this->lang->line('ValOperOpcion') . ', por favor verifique el codigo.';

            if($arrTercero[0]["terceros_estado_codigo"] != 16)
            {
                js_invocacion_javascript('alert("' . $texto_error . '"); Ajax_CargarOpcionMenu("Menu/Principal");');
                exit();
            }
            
            $arrResultado = $this->mfunciones_logica->ObtenerDetalleSolicitudTerceros($codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0]))
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) {
                    $item_valor = array(

                        "terceros_id" => $value["terceros_id"],
                        "tipo_persona_id_codigo" => $value["tipo_persona_id"],
                        "tipo_persona_id_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["tipo_persona_id"], 'tipo_persona'),
                        "nombre_persona" => $value["di_primernombre"] . ' ' . $value["di_primerapellido"] . ' ' . $value["di_segundoapellido"],
                        "email" => $value["direccion_email"],
                        "onboarding_numero_cuenta" => $value["onboarding_numero_cuenta"],
                        "terceros_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["terceros_fecha"]),
                        "terceros_estado" => $value["terceros_estado"],
                        "tercero_asistencia" => $value["tercero_asistencia"],
                        "ws_segip_codigo_resultado_codigo" => $value['ws_segip_codigo_resultado'],
                        "ws_segip_codigo_resultado" => $this->mfunciones_generales->GetValorCatalogoDB($value['ws_segip_codigo_resultado'], 'segip_codigo_respuesta'),
                        
                        "ws_cobis_codigo_resultado_codigo" => $value['ws_cobis_codigo_resultado'],
                        "ws_cobis_codigo_resultado" => $this->mfunciones_generales->GetValorCatalogoDB($value['ws_cobis_codigo_resultado'], 'segip_codigo_respuesta'),
                        
                        "ws_selfie_codigo_resultado_codigo" => $value['ws_selfie_codigo_resultado'],
                        "ws_selfie_codigo_resultado" => $this->mfunciones_generales->GetValorCatalogoDB($value['ws_selfie_codigo_resultado'], 'segip_codigo_respuesta'),
                        
                        "ws_ocr_codigo_resultado_codigo" => $value['ws_ocr_codigo_resultado'],
                        "ws_ocr_codigo_resultado" => $this->mfunciones_generales->GetValorCatalogoDB($value['ws_ocr_codigo_resultado'], 'segip_codigo_respuesta'),
                        "ws_ocr_name_valor" => $value['ws_ocr_name_valor'],
                        "ws_ocr_name_similar" => $value['ws_ocr_name_similar'],
                        "segip_operador_resultado" => $value["segip_operador_resultado"],
                        "segip_operador_texto" => $value["segip_operador_texto"],
                        'cI_numeroraiz' => $value['cI_numeroraiz'],
                        'cI_complemento' => $value['cI_complemento'],
                        'cI_lugar_emisionoextension' => $this->mfunciones_generales->GetValorCatalogoDB($value['cI_lugar_emisionoextension'], 'cI_lugar_emisionoextension'),
                        'cI_confirmacion_id' => $value['cI_confirmacion_id'],
                        'di_primernombre' => $value['di_primernombre'],
                        'di_segundo_otrosnombres' => $value['di_segundo_otrosnombres'],
                        'di_primerapellido' => $value['di_primerapellido'],
                        'di_segundoapellido' => $value['di_segundoapellido'],
                        'di_fecha_nacimiento' => $this->mfunciones_generales->getFormatoFechaD_M_Y($value['di_fecha_nacimiento']),
                        'di_fecha_vencimiento' => $this->mfunciones_generales->getFormatoFechaD_M_Y($value['di_fecha_vencimiento']),
                        'di_indefinido' => $this->mfunciones_generales->GetValorCatalogo($value['di_indefinido'], 'si_no'),
                        'di_genero' => $this->mfunciones_generales->GetValorCatalogoDB($value['di_genero'], 'di_genero'),
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            }
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
            
            $data["arrRespuesta"] = $lst_resultado;

            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
            
            $this->load->view('terceros/view_valoper_ver', $data);
        }
    }
    
    public function ValidacionOperativa_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        $segip_operador_resultado = $this->input->post('segip_operador_resultado', TRUE);
        $segip_operador_texto = $this->input->post('segip_operador_texto', TRUE);
        
        $codigo_usuario = $_SESSION["session_informacion"]["codigo"];
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if((int)$estructura_id == 0)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        if((int)$segip_operador_resultado == -1)
        {
            js_error_div_javascript('Debe seleccionar el resultado de la ' . $this->lang->line('ValOperOpcion'));
            exit();
        }
        
        // Se guarda el registro
        
        $this->mfunciones_logica->CompletarValOper(($segip_operador_resultado==1 ? 1 : 16), $segip_operador_resultado, $segip_operador_texto, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id);
        
        // Dependiendo del resultado de la validación       0=No Satisfactorio, se rechaza      1=Satisfactorio, se marca como Aprobado
        
        if($segip_operador_resultado == 0)
        {
            js_invocacion_javascript('alert("La ' . $this->lang->line('ValOperOpcion') . ' fue correctamente registrada. Por favor debe completar el proceso de Rechazo del registro.");');
        
            js_invocacion_javascript('Ajax_CargarAccion_RechazarSolicitud(' . $estructura_id . ', 16);');
        }
        
        if($segip_operador_resultado == 1)
        {
            // *** Llamar a función para Aprobar y Flujo COBIS en 2do plano ***
            $this->mfunciones_generales->Aprobar_FlujoCobis_background($estructura_id, $codigo_usuario, $nombre_usuario);
            
            $data['terceros_id'] = $estructura_id;
            $data['estado'] = 16;
            
            $this->load->view('terceros/view_aprobar_flujo', $data);
        }
        
    }
    
}
?>