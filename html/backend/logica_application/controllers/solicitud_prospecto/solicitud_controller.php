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
 * Controlador para SOLICITUD DE PROSPECTO
 * @brief CONTROLADOR DEL LOGUEO
 * @class Login_controller 
 */
class Solicitud_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 0;
        
        // Código de la Etapa Propio de la Bandeja
        protected $codigo_etapa = ETAPA_BACKOFFICE;

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
        
        $arrResultado = $this->mfunciones_logica->ObtenerSolicitudProspecto(-1, $estado);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0]))
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                $item_valor = array(
                    "solicitud_id" => $value["solicitud_id"],
                    "solicitud_nombre_persona" => $value["solicitud_nombre_persona"],
                    "solicitud_nombre_empresa" => $value["solicitud_nombre_empresa"],
                    "solicitud_departamento_codigo" => $value["solicitud_departamento"],
                    "solicitud_departamento_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["solicitud_departamento"], 'DEP'),
                    "solicitud_ciudad_codigo" => $value["solicitud_ciudad"],
                    "solicitud_ciudad_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["solicitud_ciudad"], 'CIU'),
                    "solicitud_zona_codigo" => $value["solicitud_zona"],
                    "solicitud_zona_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["solicitud_zona"], 'ZON'),
                    "solicitud_telefono" => $value["solicitud_telefono"],
                    "solicitud_email" => $value["solicitud_email"],
                    "solicitud_direccion_literal" => $value["solicitud_direccion_literal"],
                    "solicitud_direccion_geo" => $value["solicitud_direccion_geo"],
                    "solicitud_rubro_codigo" => $value["solicitud_rubro"],
                    "solicitud_rubro_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["solicitud_rubro"], 'RUB'),
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
        
        $this->load->view('solicitud_prospecto/view_solicitud_ver', $data);
        
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

            $arrResultado = $this->mfunciones_logica->ObtenerSolicitudProspecto($codigo, $estado);

            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "solicitud_id" => $value["solicitud_id"],
                        "solicitud_nombre_persona" => $value["solicitud_nombre_persona"],
                        "solicitud_nombre_empresa" => $value["solicitud_nombre_empresa"],
                        "solicitud_ciudad_codigo" => $value["solicitud_ciudad"],
                        "solicitud_ciudad_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["solicitud_ciudad"], 'DEP'),
                        "solicitud_telefono" => $value["solicitud_telefono"],
                        "solicitud_email" => $value["solicitud_email"],
                        "solicitud_direccion_literal" => $value["solicitud_direccion_literal"],
                        "solicitud_direccion_geo" => $value["solicitud_direccion_geo"],
                        "solicitud_rubro_codigo" => $value["solicitud_rubro"],
                        "solicitud_rubro_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["solicitud_rubro"], 'RUB'),
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

            // Listado de Servicios
            $arrServicios = $this->mfunciones_logica->ObtenerServiciosSolicitud($codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);
            $data["arrServicios"] = $arrServicios;
            
            $data["arrRespuesta"] = $lst_resultado;

            $this->load->view('solicitud_prospecto/view_solicitud_detalle', $data);
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

            $arrResultado = $this->mfunciones_logica->ObtenerSolicitudProspecto($codigo, $estado);

            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "solicitud_id" => $value["solicitud_id"],
                        "solicitud_nombre_persona" => $value["solicitud_nombre_persona"],
                        "solicitud_nombre_empresa" => $value["solicitud_nombre_empresa"],
                        "solicitud_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["solicitud_fecha"]),
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
            
            $this->load->view('solicitud_prospecto/view_solicitud_rechazar', $data);
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

        $_SESSION['coordenadas_solicitud'] = '';
        
        $codigo_departamento = "";
        $codigo_ciudad = "";
        $codigo_zona = "";
        
        if(isset($_POST['tipo_accion']))
        {
            // UPDATE

            $tipo_accion = $this->input->post('tipo_accion', TRUE);
            $codigo = $this->input->post('codigo', TRUE);
            $estado = $this->input->post('estado', TRUE);

            $arrResultado = $this->mfunciones_logica->ObtenerSolicitudProspecto($codigo, $estado);

            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $codigo_departamento = $value["solicitud_departamento"];
                    $codigo_ciudad = $value["solicitud_ciudad"];
                    $codigo_zona = $value["solicitud_zona"];
                    
                    $item_valor = array(
                        "solicitud_id" => $value["solicitud_id"],
                        "solicitud_nombre_persona" => $value["solicitud_nombre_persona"],
                        "solicitud_nombre_empresa" => $value["solicitud_nombre_empresa"],
                        "solicitud_departamento_codigo" => $value["solicitud_departamento"],
                        "solicitud_departamento_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["solicitud_departamento"], 'DEP'),
                        "solicitud_ciudad_codigo" => $value["solicitud_ciudad"],
                        "solicitud_ciudad_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["solicitud_ciudad"], 'CIU'),
                        "solicitud_zona_codigo" => $value["solicitud_zona"],
                        "solicitud_zona_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["solicitud_zona"], 'ZON'),
                        "solicitud_telefono" => $value["solicitud_telefono"],
                        "solicitud_email" => $value["solicitud_email"],
                        "solicitud_direccion_literal" => $value["solicitud_direccion_literal"],
                        "solicitud_direccion_geo" => $value["solicitud_direccion_geo"],
                        "solicitud_rubro_codigo" => $value["solicitud_rubro"],
                        "solicitud_rubro_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["solicitud_rubro"], 'RUB'),
                        "solicitud_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["solicitud_fecha"]),
                        "solicitud_confirmado" => $value["solicitud_confirmado"],
                        "solicitud_token" => $value["solicitud_token"],
                        "solicitud_ip" => $value["solicitud_ip"],
                        "solicitud_estado" => $this->mfunciones_generales->GetValorCatalogo($value["solicitud_estado"], 'estado_solicitud'),
                        "solicitud_observacion" => $value["solicitud_observacion"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;

                    $_SESSION['coordenadas_solicitud'] = $value["solicitud_direccion_geo"];
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
        
        // Se listan los SELECTS
            
            $data["arrVacio"][0] = array(
                "lista_codigo" => -1,
                "lista_valor" => 'Parámetro Invalido'
            );
        
        $data["arrDepartamento"] = $this->ObtenerListaCatalogo('DEP', -1, -1);
        $data["arrCiudad"] = $this->ObtenerListaCatalogo('CIU', $codigo_departamento, 'DEP');
        $data["arrZona"] = $this->ObtenerListaCatalogo('ZON', $codigo_ciudad, 'CIU');
        

        // Listado de tablas del catálogo RUBRO
        $arrRubro = $this->mfunciones_logica->ObtenerCatalogo('RUB', -1, -1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRubro);
        $data["arrRubro"] = $arrRubro;

        // Listado de Servicios
        $arrServicios = $this->mfunciones_logica->ObtenerServicio(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);

        // Lista los perfiles disponibles

        if (isset($arrServicios[0])) 
        {
            $i = 0;

            foreach ($arrServicios as $key => $value) 
            {
                $item_valor = array(
                    "servicio_id" => $value["servicio_id"],
                    "servicio_detalle" => $value["servicio_detalle"],
                    "servicio_asignado" => $this->mfunciones_generales->GetServicioSolicitud($codigo, $value["servicio_id"])
                );
                $lst_resultado2[$i] = $item_valor;

                $i++;
            }
        }
        else 
        {
            $lst_resultado2[0] = $arrServicios;
        }

        $data["arrServicios"] = $lst_resultado2;

        $data["tipo_accion"] = $tipo_accion;

        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

        $this->load->view('solicitud_prospecto/view_solicitud_editar', $data);
    }
    
    public function Solicitud_Zona_Ver() {
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
            
            $data["estructura_id"] = $estructura_id;

            $this->load->view('solicitud_prospecto/view_solicitud_zona_ver', $data);
        }
    }
    
    public function Solicitud_Zona_Mapa() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('googlemaps');

        if(!isset($_GET['estructura_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {        
            // Se captura el valor
            $estructura_id = $_SESSION['coordenadas_solicitud'];

            if($estructura_id == '')
            {
                $estructura_id = 'auto'; // Ubicación Actual
            }        

            //Marcadores

            $config['center'] = $estructura_id;
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
            $marker['position'] = $estructura_id;
            $marker['infowindow_content'] = '<div style="text-align: center;"> <img src="' . MARCADOR_LOGO . '" style="width: 30px;" align="top" /> <span style="font-size: 20px; font-weight: bold;"> ' . $this->lang->line('SolicitudTitulo_zona') . ' </span> <br /> <span> Ubique este pin donde lo requiera </span> </div>';
            $marker['icon'] = MARCADOR_SOLICITUD;
            $marker['animation'] = 'DROP';        
            $marker['draggable'] = true;
            $marker['ondragend'] = 'ActualizarGeoSolicitud(event.latLng.lat(), event.latLng.lng());';
            $this->googlemaps->add_marker($marker);
            $data['map'] = $this->googlemaps->create_map();

            $this->load->view('solicitud_prospecto/view_solicitud_mapa_zona', $data);
        }
    }
    
    public function Solicitud_Zona_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se captura el valor
        $newLat = $this->input->post('newLat', TRUE);
        $newLng = $this->input->post('newLng', TRUE);
                 
        $_SESSION['coordenadas_solicitud'] = $newLat . ', ' . $newLng;
        
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
            
            $solicitud_nombre_persona = $this->input->post('solicitud_nombre_persona', TRUE);
            $solicitud_nombre_empresa = $this->input->post('solicitud_nombre_empresa', TRUE);
            
            $empresa_departamento = $this->input->post('empresa_departamento', TRUE);
            $empresa_municipio = $this->input->post('empresa_municipio', TRUE);
            $empresa_zona = $this->input->post('empresa_zona', TRUE);
            
            $solicitud_telefono = $this->input->post('solicitud_telefono', TRUE);
            $solicitud_email = $this->input->post('solicitud_email', TRUE);
            $solicitud_direccion_literal = $this->input->post('solicitud_direccion_literal', TRUE);
            $catalogo_rubro = $this->input->post('catalogo_rubro', TRUE);
            
            $arrServicios = $this->input->post('servicio_list', TRUE);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);
            
            $coordenadas_solicitud = '';
            
            if(isset($_SESSION['coordenadas_solicitud']))
            {
                $coordenadas_solicitud = $_SESSION['coordenadas_solicitud'];
            }
            
            $nombre_usuario = $_SESSION["session_informacion"]["login"];
            $fecha_actual = date('Y-m-d H:i:s');

            // Validaciones
                
            $separador = '<br /> - ';
            $error_texto = '';
            
            if($this->mfunciones_generales->VerificaCorreo($solicitud_email) == false)
            {
                $error_texto .= $separador . $this->lang->line('CamposCorreo');
            }
            
            if($solicitud_nombre_persona == "")
            {
                $error_texto .= $separador . $this->lang->line('solicitud_nombre_persona');
            }
            
            if($solicitud_nombre_empresa == "")
            {
                $error_texto .= $separador . $this->lang->line('solicitud_nombre_empresa');
            }
            
            if($empresa_departamento == -1)
            {
                $error_texto .= $separador . $this->lang->line('empresa_departamento');
            }
            
            if($empresa_municipio == -1)
            {
                $error_texto .= $separador . $this->lang->line('empresa_municipio');
            }
            
            if($empresa_zona == -1)
            {
                $error_texto .= $separador . $this->lang->line('empresa_zona');
            }
            
            if(!filter_var($solicitud_telefono, FILTER_VALIDATE_FLOAT) !== false)
            {
                $error_texto .= $separador . $this->lang->line('solicitud_telefono');
            }
            
            if($solicitud_direccion_literal == "")
            {
                $error_texto .= $separador . $this->lang->line('solicitud_direccion_literal');
            }
            
            if($catalogo_rubro == -1)
            {
                $error_texto .= $separador . $this->lang->line('solicitud_rubro');
            }
            
            if($coordenadas_solicitud == '')
            {
                $error_texto .= $separador . $this->lang->line('FormularioNoGeo');
            }
            
            if (!isset($arrServicios[0]))
            {
                $error_texto .= $separador . $this->lang->line('solicitud_servicios');
            }
            
            if($error_texto != '')
            {
                js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                exit();
            }
            
            // Fin Validación
            
            // 0=Insert    1=Update
        
            $tipo_accion = $this->input->post('tipo_accion', TRUE);
            
            if($tipo_accion == 1)
            {
                // UPDATE
                $estructura_id = $this->input->post('estructura_id', TRUE);

                $this->mfunciones_logica->UpdateSolicitudProspecto($solicitud_nombre_persona, $solicitud_nombre_empresa, $empresa_departamento, $empresa_municipio, $empresa_zona, $solicitud_telefono, $solicitud_email, $solicitud_direccion_literal, $coordenadas_solicitud, $catalogo_rubro, $nombre_usuario, $fecha_actual, $estructura_id);

            }
            
            if($tipo_accion == 0)
            {
                $ip = $this->input->ip_address();
                $token = $this->mfunciones_generales->GeneraToken();
                // INSERT
                $estructura_id = $this->mfunciones_logica->InsertSolicitudProspecto($solicitud_nombre_persona, $solicitud_nombre_empresa, $empresa_departamento, $empresa_municipio, $empresa_zona, $solicitud_telefono, $solicitud_email, $solicitud_direccion_literal, $coordenadas_solicitud, $catalogo_rubro, $fecha_actual, $nombre_usuario, $fecha_actual, $ip, $token);
            
                // Enviar Correo de verificación de la Solicitud
                //$this->mfunciones_generales->EnviaCorreoVerificacion('afiliacion', $solicitud_nombre_persona, $solicitud_email, $estructura_id, $token);
            }
            
            // INSERTAR LOS SERVICIOS SELECCIONADOS
        
            // 1. Se eliminan los servicios de la solicitud
            $this->mfunciones_logica->Eliminar_SolicitudProspecto($estructura_id);
        
            // 2. Se registran los servicios seleccionados
            
            if (isset($arrServicios[0]))
            {
                foreach ($arrServicios as $key => $value) 
                {
                    $this->mfunciones_logica->InsertarSolicitudServicio($estructura_id, $value, $nombre_usuario, $fecha_actual);
                }
            }
            
            $_SESSION['coordenadas_solicitud'] = '';
            
            $this->Solicitud_Ver();
        }
    }
    
    public function AprobarForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();

        if(!isset($_POST['solicitud_list']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            $_SESSION["solicitud_list"] = "";
            
            $solicitud_list = json_decode($this->input->post('solicitud_list', TRUE), true);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($solicitud_list);

            if (!isset($solicitud_list[0])) 
            {
                js_error_div_javascript('No seleccionó ninguna empresa. Debe seleccionar almenos una.');
                exit();
            }
            
            $_SESSION["solicitud_list"] = $solicitud_list;
            
            
            $arrEjecutivo = $this->mfunciones_logica->ObtenerEjecutivo(-1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEjecutivo);
            $data["arrEjecutivo"] = $arrEjecutivo;
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

            $this->load->view('solicitud_prospecto/view_solicitud_aprobar_ver', $data);
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
            
            // Se usa esta variable para verificar si se contró el NIT en PayStudio o en el Sistema
            $swNIT = 0;

                // A. SE PREGUNTA A PAYSTUDIO (NAZIR) A TRAVÉS DE SU WEB SERVICE SI EL NIT EXISTE Y POBLAR LOS CAMPOS

                $RespuestaWS = $this->mfunciones_generales->ConsultaWebServiceNIT($nit);
                if (isset($RespuestaWS[0])) 
                {
                    $swNIT = 1;
                    $lst_resultado1 = $RespuestaWS;
                }

                // Detalle del Comercio
                $arrResultado1 = $this->mfunciones_logica->ObtenerComercioPorNIT($nit);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                if (isset($arrResultado1[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado1 as $key => $value) 
                    {
                        $item_valor = array(
                            "parent_id" => $value["empresa_id"],
                            "parent_nit" => $value["empresa_nit"],
                            "parent_adquiriente_codigo" => $value["empresa_adquiriente"],
                            "parent_adquiriente_detalle" => 'ATC SA',
                            "parent_tipo_sociedad_codigo" => $value["empresa_tipo_sociedad"],
                            "parent_tipo_sociedad_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_tipo_sociedad"], 'TPS'),
                            "parent_nombre_legal" => $value["empresa_nombre_legal"],
                            "parent_nombre_fantasia" => $value["empresa_nombre_fantasia"],
                            "parent_rubro_codigo" => $value["empresa_rubro"],
                            "parent_rubro_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_rubro"], 'RUB'),
                            "parent_perfil_comercial_codigo" => $value["empresa_perfil_comercial"],
                            "parent_perfil_comercial_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_rubro"], 'PEC'),
                            "parent_mcc_codigo" => $value["empresa_mcc"],
                            "parent_mcc_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_mcc"], 'MCC')
                        );
                        $lst_resultado1[$i] = $item_valor;

                        $i++;
                    }

                    $swNIT = 1;
                }

            if($swNIT == 0)
            {
                $lst_resultado1 = array();
            }
            
            $data["nit"] = $nit;
            $data["arrRespuesta"] = $lst_resultado1;

            $this->load->view('solicitud_prospecto/view_solicitud_nit', $data);
        }
    }
    
    public function Solicitud_Mapa_Ver() {
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
            
            $data["estructura_id"] = $estructura_id;

            $this->load->view('solicitud_prospecto/view_solicitud_mapa_ver', $data);
        }
    }
    
    public function Solicitud_Mapa_Mapa() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('googlemaps');

        if(!isset($_GET['estructura_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {        
            // Se captura el valor
            $estructura_id = $this->input->get('estructura_id', TRUE);
            
            if($estructura_id == '')
            {
                $estructura_id = GEO_FIE; // ATC
            }        

            //Marcadores

            $config['center'] = $estructura_id;
            $config['zoom'] = 'auto';

            // Parámetros de la Key de Google Maps
            $arrResultado3 = $this->mfunciones_logica->ObtenerDatosConf_General();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
            if (isset($arrResultado3[0])) 
            {                
                $config['apiKey'] = $arrResultado3[0]['conf_general_key_google'];
            }

            $this->googlemaps->initialize($config);

            $marker = array();
            $marker['position'] = $estructura_id;
            $marker['infowindow_content'] = '<div style="text-align: center;"> <img src="' . MARCADOR_LOGO . '" style="width: 30px;" align="top" /> <span style="font-size: 20px; font-weight: bold;"> ' . $this->lang->line('SolicitudTitulo_zona') . ' </span> <br /> <span> Ubicación del Cliente </span> </div>';
            $marker['icon'] = MARCADOR_SOLICITUD;
            $marker['animation'] = 'DROP';        
            $marker['draggable'] = false;
            $this->googlemaps->add_marker($marker);
            
            // Marcadores de los Ejecutivos de Cuentas
            
            $arrResultado = $arrResultado = $this->mfunciones_logica->ObtenerEjecutivo(-1);
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
                        $marker['infowindow_content'] = '<div style="text-align: center;"> <img src="' . MARCADOR_LOGO . '" style="width: 30px;" align="top" /> <span style="font-size: 20px; font-weight: bold;"> ' . $value['usuario_nombre'] . ' </span> <br /> <span> Ejecutivo de Cuenta </span> </div>';
                        $marker['icon'] = MARCADOR_ZONA;
                        $marker['animation'] = 'DROP';        
                        $marker['draggable'] = false;
                        $this->googlemaps->add_marker($marker);
                    }
                }
            }
            
            $data['map'] = $this->googlemaps->create_map();

            $this->load->view('solicitud_prospecto/view_solicitud_mapa_mapa', $data);
        }
    }
    
    public function Aprobar_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_SESSION["solicitud_list"]))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // 1. SE CAPTURAN LOS DATOS DEL PASO 1 Y 2
            
            
            $solicitud_nit = 123456;
            $categoria_empresa = 1;
            $tipo_persona = 1;
            $codigo_ejecutivo = $this->input->post('codigo_ejecutivo', TRUE);
            $solicitud_fecha_visita = $this->input->post('solicitud_fecha_visita', TRUE);
            $tiempo_visita = $this->input->post('tiempo_visita', TRUE);
            
            // INICIO VALIDACIÓN
            
            // FIN VALIDACIÓN
            
            foreach ($_SESSION["solicitud_list"] as $key => $value) 
            {
                $codigo_solicitud = $value;
                
                if($codigo_solicitud == 'on')
                {
                    continue;
                }
                
                // 3. SE OBTIENEN LOS CAMPOS DE LA EMPRESA

                $arrResultado = $this->mfunciones_logica->ObtenerSolicitudProspecto($codigo_solicitud, 0);

                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "solicitud_id" => $value["solicitud_id"],
                            "solicitud_nombre_persona" => $value["solicitud_nombre_persona"],
                            "solicitud_nombre_empresa" => $value["solicitud_nombre_empresa"],
                            "solicitud_departamento_codigo" => $value["solicitud_departamento"],
                            "solicitud_departamento_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["solicitud_departamento"], 'DEP'),
                            "solicitud_ciudad_codigo" => $value["solicitud_ciudad"],
                            "solicitud_ciudad_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["solicitud_ciudad"], 'CIU'),
                            "solicitud_zona_codigo" => $value["solicitud_zona"],
                            "solicitud_zona_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["solicitud_zona"], 'ZON'),
                            "solicitud_telefono" => $value["solicitud_telefono"],
                            "solicitud_email" => $value["solicitud_email"],
                            "solicitud_direccion_literal" => $value["solicitud_direccion_literal"],
                            "solicitud_direccion_geo" => $value["solicitud_direccion_geo"],
                            "solicitud_rubro_codigo" => $value["solicitud_rubro"],
                            "solicitud_rubro_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["solicitud_rubro"], 'RUB'),
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

                    // Listado de Servicios Sólo si existe los datos de la Empresa Solicitante
                    $arrServicios = $this->mfunciones_logica->ObtenerServiciosSolicitud($codigo_solicitud);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);
                } 
                else 
                {
                    js_error_div_javascript($this->lang->line('FormularioSinOpciones'));
                    exit();
                }

                // Datos Generales

                $ejecutivo_id = $codigo_ejecutivo;
                $empresa_categoria = $categoria_empresa;
                $tipo_persona = $tipo_persona;
                $empresa_nit = $solicitud_nit;
                $empresa_adquiriente = 1;
                $cal_visita_ini = $solicitud_fecha_visita;            
                    $cal_visita_fin = new DateTime($this->mfunciones_generales->getFormatoFechaDateTime($cal_visita_ini));
                $cal_visita_fin->add(new DateInterval('PT' . $tiempo_visita . 'M'));
                    $cal_visita_fin = $cal_visita_fin->format('d/m/Y H:i');
                // CAMBIAR SI ES NECESARIO EL MEDIO DE CONTACTO
                $empresa_medio_contacto = 1;

                $empresa_nombre_referencia = $lst_resultado[0]['solicitud_nombre_persona'];
                $empresa_dato_contacto = $lst_resultado[0]['solicitud_telefono'];
                $empresa_email = $lst_resultado[0]['solicitud_email'];
                
                $empresa_departamento = $lst_resultado[0]['solicitud_departamento_codigo'];
                $empresa_municipio = $lst_resultado[0]['solicitud_ciudad_codigo'];
                $empresa_zona = $lst_resultado[0]['solicitud_zona_codigo'];
                
                $empresa_direccion_literal = $lst_resultado[0]['solicitud_direccion_literal'];
                $empresa_direccion_geo = $lst_resultado[0]['solicitud_direccion_geo'];

                $arrServicios = $arrServicios;

                // Comercio
                if($categoria_empresa == 1)
                {
                    // Los datos se obtienen de la Solicitud

                    $empresa_tipo_sociedad = 0;
                    $empresa_nombre_legal = $lst_resultado[0]['solicitud_nombre_empresa'];
                    $empresa_nombre_fantasia = '';
                    $empresa_rubro = $lst_resultado[0]['solicitud_rubro_codigo'];
                    $empresa_perfil_comercial = 0;
                    $empresa_mcc = 0;
                }

                $empresa_denominacion_corta = '';
                $empresa_ha_desde = '';
                $empresa_ha_hasta = '';
                $empresa_dias_atencion = '';
                
                $empresa_tipo_calle = 0;
                $empresa_calle = '';
                $empresa_numero = 0;

                $empresa_info_adicional = '';

                // 4. SE PROCEDE A INSERTAR LA DATA EN LA DB

                $accion_usuario = $_SESSION["session_informacion"]["login"];
                $accion_fecha = date('Y-m-d H:i:s');

                // Comercio
                if($categoria_empresa == 1)
                {
                    // PASO 2: Insertar en la tabla "empresa" (dependiendo si es comercio o establecimiento/sucursal
                    $arrResultado1 = $this->mfunciones_logica->InsertarProspecto_comercioAPP($ejecutivo_id, $empresa_categoria, 0, $empresa_nit, $empresa_adquiriente, $empresa_tipo_sociedad, $empresa_nombre_referencia, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_direccion_geo, $empresa_info_adicional, $accion_usuario, $accion_fecha);
                }

                // PASO 5: Se captura el ID del registro recíen insertado en la tabla "empresa"
                $codigo_empresa = $arrResultado1;

                // PASO 6: Insertar en la tabla "prospecto"
                    $arrResultado2 = $this->mfunciones_logica->InsertarProspecto_APP($ejecutivo_id, $tipo_persona, $codigo_empresa, $accion_fecha, $accion_usuario, $accion_fecha);

                // PASO 7: Se captura el ID del registro recíen insertado en la tabla "prospecto"
                $codigo_prospecto = $arrResultado2;

                // PASO 8: Se crea la carpeta del prospecto

                $path = RUTA_PROSPECTOS . 'afn_' . $codigo_prospecto;

                if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
                {
                    mkdir($path, 0755, TRUE);
                }

                    // Se crea el archivo html para evitar ataques de directorio
                    $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                    write_file($path . '/index.html', $cuerpo_html);

                // PASO 9: Se registra la fecha en el calendario del Ejecutivo de Cuentas

                $cal_visita_ini = $this->mfunciones_generales->getFormatoFechaDateTime($cal_visita_ini);
                $cal_visita_fin = $this->mfunciones_generales->getFormatoFechaDateTime($cal_visita_fin);

                $this->mfunciones_logica->InsertarFechaCaendario($ejecutivo_id, $codigo_prospecto, 1, $cal_visita_ini, $cal_visita_fin, $accion_usuario, $accion_fecha);

                // PASO 10: Insertar los servicios seleccionados del prospecto
                    // (se elimina todos los servicios del prospecto para volver a insertarlos)
                    $this->mfunciones_logica->EliminarServiciosProspecto($codigo_prospecto);

                if (isset($arrServicios[0])) 
                {
                    foreach ($arrServicios as $key => $value) 
                    {
                        $this->mfunciones_logica->InsertarServiciosProspecto($codigo_prospecto, $value["servicio_id"], $accion_usuario, $accion_fecha);
                    }
                }

                // PASO 11; Se cambia el Estado de la Solicitud a "Aprobado"

                    $this->mfunciones_logica->AprobarSolicitudProspecto($accion_usuario, $accion_fecha, $codigo_solicitud);

                $texto = "";

                if($empresa_categoria == 1)
                {
                    $texto = "Titular";
                }

                $texto = "El bloque de empresas fue asignado correctamente al Ejecutivo seleccionado";

                // PASO 12: SE ENVÍA UN CORREO ELECTRÓNICO DE NOTIFICACIÓN A LA EMPRESA ACEPTANTE

                    $arrayNotificacionEA[0] = array(
                        "codigo_prospecto" => $codigo_prospecto,
                        "fecha_visita" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($cal_visita_ini),
                        "fecha_fin" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($cal_visita_fin),
                        "direccion_visita" => $empresa_direccion_literal
                    );

                    //$correo_ea = $this->mfunciones_generales->EnviarCorreo('notificar_EA', $empresa_email, $empresa_nombre_referencia, $arrayNotificacionEA, 0);

//                    if(!$correo_ea)
//                    {
//                        $texto .= '<br />' . $this->lang->line('FormularioNoNotificacion') . ' (EA)';
//                    }

                // PASO 13: SE ENVÍA UN CORREO ELECTRÓNICO DE NOTIFICACIÓN AL EJECUTIVO DE CUENTAS

                /* Notificar Instancia INICIO */

                    // a) Se busca la información de a quién se notificará
                /* Notificar Instancia FIN */

                if($empresa_categoria == 2)
                {
                    /**** REGISTRAR SEGUIMIENTO, ETAPA E HITO ****/        
                    $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 1, $this->codigo_etapa, $accion_usuario, $accion_fecha);
                }
                else
                {
                    /**** REGISTRAR SEGUIMIENTO, ETAPA E HITO ****/        
                    $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 1, $this->codigo_etapa, $accion_usuario, $accion_fecha);
                }

                /***  REGISTRAR SEGUIMIENTO ***/
                $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, 0, 0, 'Asignación Prospecto al Ejecutivo de Cuentas', $accion_usuario, $accion_fecha);

                $data['texto'] = $texto;
                $data['codigo_prospecto'] = $codigo_prospecto;
                
            }

            $arrDatosEjecutivo = $this->mfunciones_generales->GetDatosEmpresaCorreo($codigo_prospecto);
            $destinatario_nombre = $arrDatosEjecutivo[0]['ejecutivo_asignado_nombre'];
            $destinatario_correo = $arrDatosEjecutivo[0]['ejecutivo_asignado_correo'];
            
            $correo_enviado = $this->mfunciones_generales->EnviarCorreo('notificar_instancia', $destinatario_correo, $destinatario_nombre, $codigo_prospecto, 0);  
            
            /* Se Envía a la APP la Notificación por Push   tipo_notificacion, $tipo_visita, $codigo_visita */
            $this->mfunciones_generales->EnviarNotificacionPush(1, 1, $codigo_prospecto);
            
            $this->load->view('solicitud_prospecto/view_solicitud_aprobar_guardado', $data);
                        
        }
    }
    
    public function ObtenerListaCatalogo($tipo, $parent_codigo, $parent_tipo) {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if($parent_codigo == '' || $parent_codigo === NULL)
        {
            $parent_codigo = 0;
        }
        
        // Cargar el catálogo para establecer registros hijos
        $arrResultado1 = $this->mfunciones_logica->ObtenerCatalogo($tipo, $parent_codigo, $parent_tipo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
        
        if (isset($arrResultado1[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado1 as $key => $value) 
            {
                $item_valor = array(
                    "lista_codigo" => $value["catalogo_codigo"],
                    "lista_valor" => $value["catalogo_descripcion"],
                );
                $lst_resultado1[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado1[0] = $arrResultado1;
        }
        
        return $lst_resultado1;
    }
}
?>