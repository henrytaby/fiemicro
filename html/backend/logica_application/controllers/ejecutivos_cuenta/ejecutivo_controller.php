<?php
/**
 * @file 
 * Codigo que implementa el controlador para la gestión de Ejecutivos de Cuentas
 * @brief  EJECUTIVOS DE CUENTA
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para ga gestión del catálogo del sistema
 * @brief EJECUTIVOS DE CUENTA
 * @class Conf_catalogo_controller 
 */
class Ejecutivo_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = "11, 52";

    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
	 
    public function Ejecutivo_Ver($identificador_app) {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se establece el codigo del perfil_app para Ejecutivos de Cuenta -> Para ver el código revise la tabla "perfil_app"
        $_SESSION["identificador_tipo_perfil_app"] = $identificador_app;
        
        $arrResultado = $this->mfunciones_logica->ObtenerEjecutivo(-1, $_SESSION["identificador_tipo_perfil_app"]);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0]))
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                $item_valor = array(
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "usuario_id" => $value["usuario_id"],
                    "usuario_nombre" => $value["usuario_nombre"],
                    "ejecutivo_zona" => $value["ejecutivo_zona"],
                    "zona_registrada" => $value["zona_registrada"],
                    "ejecutivo_perfil_tipo" => (int)$value["ejecutivo_perfil_tipo"]
                );
                $lst_resultado[$i] = $item_valor;
                
                $i++;
            }
        }
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }
        
        // Cargar adicionalmente el modelo de microcreditos
        $this->load->model('mfunciones_microcreditos');
        
        $data["arrRespuesta"] = $lst_resultado;
        
            /***** REGIONALIZACIÓN INICIO ******/                
                $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
                $data['nombre_region'] = $lista_region->region_nombres_texto;
            /***** REGIONALIZACIÓN FIN ******/
        
        $this->load->view('ejecutivos_cuenta/view_ejecutivo_ver', $data);
    }
    
    public function EjecutivoForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // 0=Insert    1=Update
        
        $tipo_accion = 0;
        
        if(isset($_POST['tipo_accion']))
        {
            $tipo_accion = $_POST['tipo_accion'];
            
            // UPDATE
            
            $estructura_codigo = $_POST['codigo'];
            
            $arrResultado = $this->mfunciones_logica->ObtenerEjecutivo($estructura_codigo, $_SESSION["identificador_tipo_perfil_app"]);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                        
            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "ejecutivo_id" => $value["ejecutivo_id"],
                        "usuario_id" => $value["usuario_id"],
                        "usuario_nombre" => $value["usuario_nombre"],
                        "ejecutivo_zona" => $value["ejecutivo_zona"],
                        "zona_registrada" => $value["zona_registrada"]
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
            
            // INSERT
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
            
            $estructura_codigo = 0;
        }
        
        // Listado de los Usuarios App
        if($tipo_accion == 0)
        {
            $arrParent = $this->mfunciones_generales->ListaHabilitarUsuariosRegion($_SESSION["identificador_tipo_perfil_app"]);
        }
        else
        {
            $arrParent = $this->mfunciones_generales->ListaEjecutivosUsuariosRegion($_SESSION["identificador_tipo_perfil_app"]);
        }
        
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrParent);
        $data["arrParent"] = $arrParent;
        
        $data["tipo_accion"] = $tipo_accion;
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
            /***** REGIONALIZACIÓN INICIO ******/                
                $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
                $data['nombre_region'] = $lista_region->region_nombres_texto;
            /***** REGIONALIZACIÓN FIN ******/
        
        $this->load->view('ejecutivos_cuenta/view_ejecutivo_form', $data);        
    }
    
    public function Ejecutivo_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $catalogo_parent = $this->input->post('catalogo_parent', TRUE);
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if((int)$catalogo_parent <= 0)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        // 0=Insert    1=Update
        
        $tipo_accion = $this->input->post('tipo_accion', TRUE);
        
        /***** REGIONALIZACIÓN: Valida si el registro pertenece a la región ******/
        if(!$this->mfunciones_generales->VerificaUsuarioRegion($catalogo_parent))
        {
            js_error_div_javascript($this->lang->line('regionaliza_NoRegion'));
            exit();
        }
        
        if($tipo_accion == 1)
        {
            // UPDATE
            $estructura_id = $this->input->post('estructura_id', TRUE);
            
            if((int)$estructura_id == 0)
            {
                js_error_div_javascript($this->lang->line('FormularioIncompleto'));
                exit();
            }
            
            $this->mfunciones_logica->UpdateEjecutivo($catalogo_parent, $nombre_usuario, $fecha_actual, $estructura_id);
        }
        
        if($tipo_accion == 0)
        {
            // Se verifica que no se selccione un Usuario ya Asociado como Ejecutivo de Cuenta
            $arrVerifica = $this->mfunciones_logica->VerificarUsuarioEjecutivo($catalogo_parent);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrVerifica);
            
            if (isset($arrVerifica[0]))
            {
                js_error_div_javascript($this->lang->line('FormularioNoEjecutivo'));
                exit();
            }
            
            $this->mfunciones_logica->InsertEjecutivo($catalogo_parent, $nombre_usuario, $fecha_actual);
        }
        
        $this->Ejecutivo_Ver($_SESSION["identificador_tipo_perfil_app"]);
    }
    
    public function Ejecutivo_Zona_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se captura el valor
        $estructura_id = $this->input->post('codigo', TRUE);
        
        $arrResultado = $arrResultado = $this->mfunciones_logica->ObtenerEjecutivo($estructura_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {            
            $data["estructura_id"] = $estructura_id;
            $data["zona_registrada"] = $arrResultado[0]['zona_registrada'];
        
            $this->load->view('ejecutivos_cuenta/view_ejecutivo_zona_ver', $data);  
        }
    }
    
    public function Ejecutivo_Zona_Mapa() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('googlemaps');

        if (!isset($_GET['estructura_id'])) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        // Se captura el valor
        $estructura_id = preg_replace('/[^a-zA-Z0-9_ .]/s', '', $this->input->get('estructura_id', TRUE));
        
        $arrResultado = $arrResultado = $this->mfunciones_logica->ObtenerEjecutivo($estructura_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {            
            if($arrResultado[0]['zona_registrada'] == 'Si')
            {
                $coordenadas = $arrResultado[0]['ejecutivo_zona'];
            }
            else
            {
                $coordenadas = 'auto'; // Ubicación Actual
            }
        }
        
        //Marcadores
        
        $config['center'] = $coordenadas;
        $config['zoom'] = '15';
        $config['disableDoubleClickZoom'] = 'true';
            
        $config['ondblclick'] = 'ActualizarZonaEjecutivoAuxliar(event.latLng.lat(), event.latLng.lng());';
        
        // Parámetros de la Key de Google Maps
        $arrResultado3 = $this->mfunciones_logica->ObtenerDatosConf_General();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
        if (isset($arrResultado3[0])) 
        {                
            $config['apiKey'] = $arrResultado3[0]['conf_general_key_google'];
        }
        
        $this->googlemaps->initialize($config);
        
        $marker = array();
        $marker['position'] = $coordenadas;
        $marker['infowindow_content'] = '<div style="text-align: center;"> <img src="' . MARCADOR_LOGO . '" style="width: 30px;" align="top" /> <span style="font-size: 20px; font-weight: bold;"> ' . $this->lang->line('ejecutivo_ejecutivo_zona') . $arrResultado[0]['perfil_app_nombre'] . ' </span> <br /> <span> Ubique este pin donde lo requiera </span> </div>';
        $marker['icon'] = MARCADOR_ZONA;
        $marker['animation'] = 'DROP';        
        $marker['draggable'] = true;
        
        $marker['ondragend'] = 'ActualizarZonaEjecutivo(event.latLng.lat(), event.latLng.lng());';
        $this->googlemaps->add_marker($marker);
        $data['map'] = $this->googlemaps->create_map();
        
        $data['codigo_ejecutivo'] = $estructura_id;
        
        $this->load->view('ejecutivos_cuenta/view_ejecutivo_mapa_zona', $data);
    }
    
    public function Ejecutivo_Zona_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        // Se captura el valor
        $newLat = $this->input->post('newLat', TRUE);
        $newLng = $this->input->post('newLng', TRUE);
        
        $codigo_ejecutivo = $this->input->post('codigo_ejecutivo', TRUE);
         
        $zona = $newLat . ', ' . $newLng;
        
        // Se actualiza le ubicación
        $this->mfunciones_logica->UpdateZonaEjecutivo($zona, $nombre_usuario, $fecha_actual, $codigo_ejecutivo);
    }
    
    public function Ejecutivo_Mapa_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $this->load->view('ejecutivos_cuenta/view_ejecutivo_mapa_ver');
    }
    
    public function Ejecutivo_Mapa_Mapa() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('googlemaps');

        //$config['center'] = 'auto';
        $config['zoom'] = 'auto';
        
        // Parámetros de la Key de Google Maps
        $arrResultado3 = $this->mfunciones_logica->ObtenerDatosConf_General();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
        if (isset($arrResultado3[0])) 
        {                
            $config['apiKey'] = $arrResultado3[0]['conf_general_key_google'];
        }
        
        $this->googlemaps->initialize($config);
        
        $arrResultado = $arrResultado = $this->mfunciones_logica->ObtenerEjecutivo(-1, $_SESSION["identificador_tipo_perfil_app"]);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            foreach ($arrResultado as $key => $value) 
            {
                if($value['zona_registrada'] == 'Si')
                {
                    //Marcadores
                    $marker = array();
                    $marker['position'] = $value['ejecutivo_zona'];
                    $marker['infowindow_content'] = '<div style="text-align: center;"> <img src="' . MARCADOR_LOGO . '" style="width: 30px;" align="top" /> <span style="font-size: 20px; font-weight: bold;"> ' . $value['usuario_nombre'] . ' </span> <br /> <span> ' . $value['perfil_app_nombre'] . ' </span> </div>';
                    $marker['icon'] = MARCADOR_ZONA;
                    $marker['animation'] = 'DROP';        
                    $marker['draggable'] = false;
                    $this->googlemaps->add_marker($marker);
                }
            }
        }
        
        $data['map'] = $this->googlemaps->create_map();
        
        $this->load->view('ejecutivos_cuenta/view_ejecutivo_mapa_mapa', $data);
    }
    
    public function EjecutivoHorario_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se captura el valor
        $estructura_id = $this->input->post('codigo', TRUE);
        
        if (!isset($estructura_id)) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {            
            $data['estructura_id'] = $estructura_id;
            $this->load->view('ejecutivos_cuenta/view_horario_ver', $data);
        }
    }
    
    public function EjecutivoHorario_Horario() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se captura el valor
        $estructura_id = $this->input->get('estructura_id', TRUE);
        
        if (!isset($estructura_id)) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            $arrResultado = $this->mfunciones_logica->HorarioVisitasEjecutivo($estructura_id);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0]))
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) {

                    $evento_color = '#006699';
                    if($value["cal_tipo_visita"] == 2)
                    {
                        $evento_color = '#009dd9';
                    }

                    $item_valor = array(
                        "ejecutivo_id" => $value["ejecutivo_id"],
                        "cal_id" => $value["cal_id"],
                        "cal_tipo_visita" => $value["cal_tipo_visita"],
                        "cal_id_visita" => $value["cal_id_visita"],
                        "cal_visita_ini" => $value["cal_visita_ini"],
                        "cal_visita_fin" => $value["cal_visita_fin"],
                        "empresa_id" => $value["empresa_id"],
                        "empresa_categoria" => $value["empresa_categoria"],
                        "empresa_nombre" => $value["empresa_nombre"],
                        "evento_color" => $evento_color
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

            // Parámetros del Calendario

            $arrResultado3 = $this->mfunciones_logica->ObtenerDatosConf_General();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            if (isset($arrResultado3[0])) 
            {                
                $data["conf_calendario"] = $arrResultado3[0]['conf_horario_feriado'];
                $data["conf_key_google"] = $arrResultado3[0]['conf_general_key_google'];
                $data["conf_horario_laboral"] = $arrResultado3[0]['conf_horario_laboral'];
                $data["conf_atencion_desde"] = $arrResultado3[0]['conf_atencion_desde'];
                $data["conf_atencion_hasta"] = $arrResultado3[0]['conf_atencion_hasta'];
                $data["conf_atencion_dias"] = $arrResultado3[0]['conf_atencion_dias'];
            }

            $this->load->view('ejecutivos_cuenta/view_horario_calendario', $data);
        }
    }
    
    public function EjecutivoHorario_Guardar() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los valores

        $estructura_id = $this->input->post('id', TRUE);
        $fecha_inicio = $this->input->post('start', TRUE);
        $fecha_fin = $this->input->post('end', TRUE);

        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');

        if($estructura_id == '' || $fecha_inicio == '' || $fecha_fin == '')
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }

        // Se actualiza el Horario del Ejecutivo de Cuentas
        $this->mfunciones_logica->UpdateHorarioEjecutivo($fecha_inicio, $fecha_fin, $nombre_usuario, $fecha_actual, $estructura_id);        
    }
    
    public function ConfForm_metrica_Ver() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        $arrResultado = $this->mfunciones_logica->ObtenerDatosConf_Credenciales();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "conf_credenciales_id" => $value["conf_id"],
                    "conf_ejecutivo_ic" => $value["conf_ejecutivo_ic"]
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
        
        $this->load->view('ejecutivos_cuenta/view_metrica_form', $data);        
    }
    
    public function ConfForm_metrica_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $conf_credenciales_id = $this->input->post('conf_credenciales_id', TRUE);
        $conf_credenciales_defecto = $this->input->post('conf_credenciales_defecto', TRUE);
        
        $conf_ejecutivo_ic = $this->input->post('conf_ejecutivo_ic', TRUE);
                        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if($conf_credenciales_id == "" || (int)$conf_ejecutivo_ic == 0)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        $this->mfunciones_logica->UpdateDatosConf_IC($conf_ejecutivo_ic, $fecha_actual, $nombre_usuario, $conf_credenciales_id);

        $this->Ejecutivo_Ver();
    }
    
    public function TransferirProsForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // 0=Insert    1=Update
        
        $tipo_accion = 1;
        
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
            
        // UPDATE

        $estructura_codigo = $this->input->post('codigo', TRUE);

        $visita_codigo = $this->input->post('visita', TRUE);

        $codigo_tipo_persona = $this->input->post('tipo_registro', TRUE);
        
        $data["codigo_tipo_persona"] = $codigo_tipo_persona;
        
        switch ((int)$codigo_tipo_persona) {
            
            // Solicitud de Crédito
            case 6:
                
                $this->load->model('mfunciones_microcreditos');
                
                $DatosProspecto = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($visita_codigo);
                $DatosProspecto[0]['general_solicitante'] = $DatosProspecto[0]['sol_nombre_completo'];
                
                break;
        
            // Normalizador/Cobrador
            case 13:
                
                $this->load->model('mfunciones_cobranzas');
                
                $DatosProspecto = $this->mfunciones_cobranzas->ObtenerDetalleRegistro($visita_codigo);
                $DatosProspecto[0]['general_solicitante'] = $DatosProspecto[0]['norm_primer_nombre'] . ' ' . $DatosProspecto[0]['norm_segundo_nombre'] . ' ' . $DatosProspecto[0]['norm_primer_apellido'] . ' ' . $DatosProspecto[0]['norm_segundo_apellido'];
                
                break;
            
            default:
        
                // Datos del Prospecto
                $DatosProspecto = $this->mfunciones_generales->GetDatosEmpresaCorreo($visita_codigo);
                
                break;
        }
        
        $data["arrProspecto"] = $DatosProspecto;

        $arrResultado = $this->mfunciones_logica->ObtenerEjecutivo($estructura_codigo, $_SESSION["identificador_tipo_perfil_app"]);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;

            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "usuario_id" => $value["usuario_id"],
                    "usuario_nombre" => $value["usuario_nombre"],
                    "ejecutivo_zona" => $value["ejecutivo_zona"],
                    "zona_registrada" => $value["zona_registrada"]
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
        
        // Listado de los Usuarios App
        $arrParent = $this->mfunciones_generales->ListaEjecutivosUsuariosRegion($_SESSION["identificador_tipo_perfil_app"]);
        
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrParent);
        $data["arrParent"] = $arrParent;
        
        $data["visita_codigo"] = $visita_codigo;
        
        $data["tipo_accion"] = $tipo_accion;
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
            /***** REGIONALIZACIÓN INICIO ******/                
                $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
                $data['nombre_region'] = $lista_region->region_nombres_texto;
            /***** REGIONALIZACIÓN FIN ******/
        
        $this->load->view('ejecutivos_cuenta/view_transferir_pros_form', $data);        
    }
    
    public function TransferirPros_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $catalogo_parent = $this->input->post('catalogo_parent', TRUE);
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if((int)$catalogo_parent <= 0)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        // 0=Insert    1=Update
        
        $tipo_accion = $this->input->post('tipo_accion', TRUE);
        
        $visita_codigo = $this->input->post('visita_codigo', TRUE);
        
        $codigo_tipo_persona = $this->input->post('codigo_tipo_persona', TRUE);
        
        /***** REGIONALIZACIÓN: Valida si el registro pertenece a la región ******/
        if(!$this->mfunciones_generales->VerificaUsuarioRegion($catalogo_parent))
        {
            js_error_div_javascript($this->lang->line('regionaliza_NoRegion'));
            exit();
        }
        
        // UPDATE
        $estructura_id = $this->input->post('estructura_id', TRUE);

        if((int)$estructura_id == 0)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }

        switch ((int)$codigo_tipo_persona) {
            
            // Solicitud de Crédito
            case 6:
                
                $this->load->model('mfunciones_microcreditos');
                $this->mfunciones_microcreditos->UpdateTransfSolCred($catalogo_parent, $nombre_usuario, $fecha_actual, $estructura_id, $visita_codigo);
                
                break;
            
            // Normalizador/Cobrador
            case 13:
                
                $this->load->model('mfunciones_cobranzas');
                $this->mfunciones_cobranzas->UpdateTransfRegistro($catalogo_parent, $nombre_usuario, $fecha_actual, $estructura_id, $visita_codigo);
                
                break;
            
            default:
        
                $this->mfunciones_logica->UpdateTransfProspecto($catalogo_parent, $nombre_usuario, $fecha_actual, $estructura_id, $visita_codigo);
                
            break;
        }
        
        js_invocacion_javascript("Ajax_CargarAccion_Prospecto('". $estructura_id . "');");
    }
    
    public function TransferirMantForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // 0=Insert    1=Update
        
        $tipo_accion = 1;
        
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
            
        // UPDATE

        $estructura_codigo = $this->input->post('codigo', TRUE);

        $visita_codigo = $this->input->post('visita', TRUE);

        // Datos del Mantenimiento
        $DatosMantenimiento = $this->mfunciones_logica->ListadoDetalleMantenimientos($visita_codigo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($DatosMantenimiento);
        $data["arrMantenimiento"] = $DatosMantenimiento;
        
        $arrResultado = $this->mfunciones_logica->ObtenerEjecutivo($estructura_codigo, $_SESSION["identificador_tipo_perfil_app"]);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;

            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "usuario_id" => $value["usuario_id"],
                    "usuario_nombre" => $value["usuario_nombre"],
                    "ejecutivo_zona" => $value["ejecutivo_zona"],
                    "zona_registrada" => $value["zona_registrada"]
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
        
        // Listado de los Usuarios App
        $arrParent = $this->mfunciones_generales->ListaEjecutivosUsuariosRegion($_SESSION["identificador_tipo_perfil_app"]);
        
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrParent);
        $data["arrParent"] = $arrParent;
        
        $data["visita_codigo"] = $visita_codigo;
        
        $data["tipo_accion"] = $tipo_accion;
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
            /***** REGIONALIZACIÓN INICIO ******/                
                $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
                $data['nombre_region'] = $lista_region->region_nombres_texto;
            /***** REGIONALIZACIÓN FIN ******/
        
        $this->load->view('ejecutivos_cuenta/view_transferir_mant_form', $data);        
    }
    
    public function TransferirMant_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $catalogo_parent = $this->input->post('catalogo_parent', TRUE);
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if((int)$catalogo_parent <= 0)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        // 0=Insert    1=Update
        
        $tipo_accion = $this->input->post('tipo_accion', TRUE);
        
        $visita_codigo = $this->input->post('visita_codigo', TRUE);
        
        /***** REGIONALIZACIÓN: Valida si el registro pertenece a la región ******/
        if(!$this->mfunciones_generales->VerificaUsuarioRegion($catalogo_parent))
        {
            js_error_div_javascript($this->lang->line('regionaliza_NoRegion'));
            exit();
        }
        
        // UPDATE
        $estructura_id = $this->input->post('estructura_id', TRUE);

        if((int)$estructura_id == 0)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }

        $this->mfunciones_logica->UpdateTransfMantenimiento($catalogo_parent, $nombre_usuario, $fecha_actual, $estructura_id, $visita_codigo);
        
        $this->Ejecutivo_Ver($_SESSION["identificador_tipo_perfil_app"]);
    }
    
    public function Bandeja_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $filtro = 'p.prospecto_etapa IN (3,5,6,7,8,9,11)';
        
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
        
        if (isset($arrResultado[0])) 
        {
            $i = 0;
                        
            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "prospecto_id" => $value["prospecto_id"],
                    "camp_id" => $value["camp_id"],
                    "camp_nombre" => $value["camp_nombre"],
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "tipo_persona_id" => $value["tipo_persona_id"],
                    "prospecto_fecha_asignacion" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["prospecto_fecha_asignacion"]),
                    "prospecto_checkin" => $value["prospecto_checkin"],
                    "prospecto_llamada" => $value["prospecto_llamada"],
                    "prospecto_consolidado" => $this->mfunciones_generales->GetValorCatalogo($value["prospecto_consolidado"], 'si_no'),
                    "prospecto_observado_app" => $value["prospecto_observado_app"],
                    "general_solicitante" => $value["general_solicitante"],
                    "general_ci" => $value["general_ci"],
                    "general_ci_extension" => $value["general_ci_extension"],
                    "onboarding" => $value["onboarding"]
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        }
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }
        
        // Se establece una variable de SESSION para determinar en que bandeja se encuentra el usuario y al Observar el documento, regresar allí
        
        $_SESSION['direccion_bandeja_actual'] = 'Ejecutivo/Prospecto/ForGestion';
        
        $_SESSION['flag_bandeja_agente'] = 5;
        
        $data["arrRespuesta"] = $lst_resultado;
        
        $this->load->view('ejecutivos_cuenta/view_ejecutivo_prospectos_gestion', $data);
    }
    
    // Perfil Tipo Categoría "B"
    public function ActualizarTipoPerfil() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        
        $codigo_ejecutivo = (int)$this->input->post('codigo_ejecutivo', TRUE);
        $codigo_perfil = (int)$this->input->post('codigo_perfil', TRUE);
        
        if($codigo_perfil <= 0 || $codigo_ejecutivo <= 0)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        // Validar que el ejecutvio ya TENGA ese perfil, si ya lo tiene asignado mostrar mensaje al usuario
        if($this->mfunciones_microcreditos->CheckTipoPerfilEjecutivo($codigo_ejecutivo, $codigo_perfil))
        {
            js_error_div_javascript(sprintf($this->lang->line('ejecutivo_perfil_tipo_asignado'), $this->mfunciones_microcreditos->GetValorCatalogo($codigo_perfil, 'ejecutivo_perfil_tipo')));
            exit();
        }
        
        // Actualizar el perfil tipo, con el valor capturado
        $this->mfunciones_microcreditos->setPerfilEjecutivo($codigo_ejecutivo, $codigo_perfil, $nombre_usuario, $fecha_actual);
        
        $this->Ejecutivo_Ver($_SESSION["identificador_tipo_perfil_app"]);
    }
}
?>