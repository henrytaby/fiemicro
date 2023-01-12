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
class Interno_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 31;

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
     
        $this->load->view('solicitud/web_menu');
        
    }
    
    public function SolicitudFormProspecto() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // 0=Insert    1=Update

        $tipo_accion = 0;

        $_SESSION['coordenadas_solicitud'] = '';
        
        $codigo_departamento = "-1";
        $codigo_ciudad = "-1";
        $codigo_zona = "-1";
        
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
        $data["arrCiudad"] = $this->ObtenerListaCatalogo('CIU', -1, -1);
        $data["arrZona"] = $this->ObtenerListaCatalogo('ZON', -1, -1);

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

        $this->load->view('solicitud/view_prospecto_editar', $data);
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
    
    public function SolicitudFormMantenimiento() {
        
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

        $this->load->view('solicitud/view_mantenimiento_editar', $data);
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
            
            $data['texto'] = $this->lang->line('solicitud_prospecto_guardado');
            
            $this->load->view('solicitud/view_solicitud_guardado', $data);
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
            
            $data['texto'] = $this->lang->line('solicitud_mantenimiento_guardado');
            
            $this->load->view('solicitud/view_solicitud_guardado', $data);
        }
    }
}
?>