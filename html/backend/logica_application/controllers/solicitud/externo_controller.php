<?php
/**
 * @file 
 * Codigo que implementa el controlador para la autenticacion de usuario en el sistema
 * @brief  CONTROLADOR SOLICITUDES
 * @author JRAD
 * @date Mar 21, 2014
 * @copyright 2017 JRAD
 */
/**
 * Controlador para SOLICITUDES
 * @brief CONTROLADOR DEL LOGUEO
 * @class Login_controller 
 */
class Externo_controller extends CI_Controller {


    function __construct() {
        parent::__construct();
        session_start();
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
     
        $this->load->view('solicitud/web_menu');
        
    }
    
    public function Recargar_captcha() {
        $this->load->model('mfunciones_generales');
        $this->load->library('LibreriasPersonalizadas/Construccion_captcha');

        $strTextoCaptcha = "";
        $imgTagHtml = $this->construccion_captcha->GetCaptchaExterno($strTextoCaptcha);
        //$data["imgTagHtml"] = $imgTagHtml;
        $_SESSION["session_captcha"] = array("Login" => $strTextoCaptcha);
        print_r($imgTagHtml);
        //$this->load->view('login/view_login', $data);
    }
    
    public function SolicitudFormProspecto() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        // Captcha
        $this->load->library('LibreriasPersonalizadas/Construccion_captcha');
        $strTextoCaptcha = "";
        $imgTagHtml = $this->construccion_captcha->GetCaptchaExterno($strTextoCaptcha);
        
        $data["imgTagHtml"] = $imgTagHtml;
        $_SESSION["session_captcha"] = array("Login" => $strTextoCaptcha);
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // 0=Insert    1=Update

        $tipo_accion = 0;

        $_SESSION['coordenadas_solicitud'] = '';
        
        $tipo_accion = 0;
        $codigo = 0;

        // INSERT

        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
        
        // Listado de tablas del catálogo DEPARTAMENTOS
        $arrCiudad = $this->mfunciones_logica->ObtenerCatalogo('DEP', -1, -1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrCiudad);
        $data["arrCiudad"] = $arrCiudad;

        // Listado de tablas del catálogo RUBRO
        $arrRubro = $this->mfunciones_logica->ObtenerCatalogo('RUB', -1, -1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRubro);
        
        if (isset($arrRubro[0])) 
        {
            $lst_resultado_rub[0] = array(
                "catalogo_codigo" => 4722,
                "catalogo_descripcion" => 'AGENCIA DE VIAJES'
            );
            
            $lst_resultado_rub[1] = array(
                "catalogo_codigo" => 5813,
                "catalogo_descripcion" => 'BARES/DISCOTECAS'
            );
            
            $lst_resultado_rub[2] = array(
                "catalogo_codigo" => 5651,
                "catalogo_descripcion" => 'BOUTIQUES'
            );
            
            $lst_resultado_rub[3] = array(
                "catalogo_codigo" => 5541,
                "catalogo_descripcion" => 'ESTACIONES DE SERVICIO VENTA GASOLINA/GNV'
            );
            
            $lst_resultado_rub[4] = array(
                "catalogo_codigo" => 5912,
                "catalogo_descripcion" => 'FARMACIAS'
            );
            
            $lst_resultado_rub[5] = array(
                "catalogo_codigo" => 7011,
                "catalogo_descripcion" => 'HOTELES'
            );
            
            $lst_resultado_rub[6] = array(
                "catalogo_codigo" => 5942,
                "catalogo_descripcion" => 'LIBRERIAS'
            );
            
            $lst_resultado_rub[7] = array(
                "catalogo_codigo" => 5812,
                "catalogo_descripcion" => 'RESTAURANTES'
            );
            
            $lst_resultado_rub[8] = array(
                "catalogo_codigo" => 5499,
                "catalogo_descripcion" => 'MICROMERCADOS'
            );
            
            $lst_resultado_rub[9] = array(
                "catalogo_codigo" => 5977,
                "catalogo_descripcion" => 'TIENDAS DE COSMÉTICOS'
            );
            
            $lst_resultado_rub[10] = array(
                "catalogo_codigo" => 5712,
                "catalogo_descripcion" => 'TIENDAS DE MUEBLES'
            );
            
            $lst_resultado_rub[11] = array(
                "catalogo_codigo" => 5533,
                "catalogo_descripcion" => 'TALLERES/TIENDAS DE SERVICIO DE AUTOMÓVILES'
            );
            
            $lst_resultado_rub[12] = array(
                "catalogo_codigo" => 5655,
                "catalogo_descripcion" => 'TIENDAS DE ROPA DEPORTIVA'
            );
            
            $lst_resultado_rub[13] = array(
                "catalogo_codigo" => 5661,
                "catalogo_descripcion" => 'TIENDAS DE ZAPATOS'
            );
            
            $arrAux[0] = array(
                "catalogo_codigo" => 0,
                "catalogo_descripcion" => ' -- No conozco mi rubro -- '
            );
            
            $lst_resultado_rub = array_merge($arrAux, $lst_resultado_rub);
        }
        else
        {
            $lst_resultado_rub[0] = $arrRubro;
        }
        
        $data["arrRubro"] = $lst_resultado_rub;

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

        $this->load->view('solicitud_externo/view_prospecto_editar', $data);
    }
    
    public function SolicitudFormMantenimiento() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        // Captcha
        $this->load->library('LibreriasPersonalizadas/Construccion_captcha');
        $strTextoCaptcha = "";
        $imgTagHtml = $this->construccion_captcha->GetCaptchaExterno($strTextoCaptcha);
        
        $data["imgTagHtml"] = $imgTagHtml;
        $_SESSION["session_captcha"] = array("Login" => $strTextoCaptcha);
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        $tipo_accion = 0;
        $codigo = 0;

        // INSERT

        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());

        // Listado de Servicios
        $arrTareas = $this->mfunciones_logica->ObtenerTareas();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrTareas);

        $data["arrTareas"] = $arrTareas;

        $data["tipo_accion"] = $tipo_accion;

        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

        $this->load->view('solicitud_externo/view_mantenimiento_editar', $data);
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

            $this->load->view('solicitud_externo/view_solicitud_zona_ver', $data);
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
                $estructura_id = GEO_FIE; // Ubicación Actual
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

            $this->load->view('solicitud_externo/view_solicitud_mapa_zona', $data);
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
        
        echo "JRAD: " . $_SESSION['coordenadas_solicitud'];
        
    }
    
    public function Prospecto_Guardar() {
        
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
            if (!isset($_SESSION)) {
                session_start();
            }
            $arrCodigoCaptcha = $_SESSION["session_captcha"];

            if (!($_POST["imagen"] == $arrCodigoCaptcha["Login"])) {

                js_invocacion_javascript("recargar_captcha_externo('imgCaptchaLogin')");
                js_error_div_javascript("El Código de la Imágen no es correcto.");
                return;
            }
            
            // Se capturan los datos
            
            $solicitud_nombre_persona = $this->input->post('solicitud_nombre_persona', TRUE);
            $solicitud_nombre_empresa = $this->input->post('solicitud_nombre_empresa', TRUE);
            $catalogo_ciudad = $this->input->post('catalogo_ciudad', TRUE);
            $solicitud_telefono = $this->input->post('solicitud_telefono', TRUE);
            $solicitud_email = $this->input->post('solicitud_email', TRUE);
            $solicitud_direccion_literal = $this->input->post('solicitud_direccion_literal', TRUE);
            $catalogo_rubro = $this->input->post('catalogo_rubro', TRUE);
            
            $arrServicios = json_decode($this->input->post('servicio_list', TRUE), true);
            $coordenadas_solicitud = '-16.543858, -68.085931';
            
            if(isset($_SESSION['coordenadas_solicitud']) && $_SESSION['coordenadas_solicitud'] != '')
            {
                $coordenadas_solicitud = $_SESSION['coordenadas_solicitud'];
            }
            
            $nombre_usuario = 'externo';
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
            
            if($catalogo_ciudad == -1)
            {
                $error_texto .= $separador . $this->lang->line('solicitud_ciudad');
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

                $this->mfunciones_logica->UpdateSolicitudProspecto($solicitud_nombre_persona, $solicitud_nombre_empresa, $catalogo_ciudad, $solicitud_telefono, $solicitud_email, $solicitud_direccion_literal, $coordenadas_solicitud, $catalogo_rubro, $nombre_usuario, $fecha_actual, $estructura_id);

            }
            
            if($tipo_accion == 0)
            {
                $ip = $this->input->ip_address();
                $token = $this->mfunciones_generales->GeneraToken();
                // INSERT
                $estructura_id = $this->mfunciones_logica->InsertSolicitudProspecto($solicitud_nombre_persona, $solicitud_nombre_empresa, $catalogo_ciudad, $solicitud_telefono, $solicitud_email, $solicitud_direccion_literal, $coordenadas_solicitud, $catalogo_rubro, $fecha_actual, $nombre_usuario, $fecha_actual, $ip, $token);
            
                // Enviar Correo de verificación de la Solicitud
                $this->mfunciones_generales->EnviaCorreoVerificacion('afiliacion', $solicitud_nombre_persona, $solicitud_email, $estructura_id, $token);
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
            
            $data['texto'] = $this->lang->line('externo_prospecto_guardado');
            
            $this->load->view('solicitud_externo/view_solicitud_guardado', $data);
        }
    }
    
    public function Mantenimiento_Guardar() {
        
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
            if (!isset($_SESSION)) {
                session_start();
            }
            $arrCodigoCaptcha = $_SESSION["session_captcha"];

            if (!($_POST["imagen"] == $arrCodigoCaptcha["Login"])) {

                js_invocacion_javascript("recargar_captcha_externo('imgCaptchaLogin')");
                js_error_div_javascript("El Código de la Imágen no es correcto.");
                return;
            }
            
            // Se capturan los datos
            
            $solicitud_nit = $this->input->post('solicitud_nit', TRUE);
            $solicitud_nombre_empresa = $this->input->post('solicitud_nombre_empresa', TRUE);
            $solicitud_email = $this->input->post('solicitud_email', TRUE);
            
            $arrTareas = json_decode($this->input->post('tarea_list', TRUE), true);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrTareas);
            
            $otro = $this->input->post('otro', TRUE);
            $solicitud_otro_detalle = $this->input->post('solicitud_otro_detalle', TRUE);
            
            $nombre_usuario = 'externo';
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
            
            $data['texto'] = $this->lang->line('externo_mantenimiento_guardado');
            
            $this->load->view('solicitud_externo/view_solicitud_guardado', $data);
        }
    }
}
?>