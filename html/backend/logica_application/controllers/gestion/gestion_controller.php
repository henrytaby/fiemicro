<?php
/**
 * @file 
 * Codigo que implementa el controlador para la gestión de Prospectos
 * @brief CONRTOLADOR PARA LA GESTIÓN DE PROSPECTOS (DOCUMENTOS Y CONSOLIDAR) Y EMPRESAS (ACTUALIZACIÓN DE INFORMACIÓN)
 * @author JRAD
 * @date 2018
 * @copyright 2018 JRAD
 */
/**
 * Controlador para la gestión de Prospectos y Empresas
 * @brief GESTIÓN DE PROSPECTOS Y EMPRESAS
 * @class Gestion_controller
 */
class Gestion_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	//protected $codigo_menu_acceso = 39;
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
    
    public function EmpresaBandeja_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerDatosEmpresa(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;
                        
            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "empresa_id" => $value["empresa_id"],
                    "empresa_categoria_codigo" => $value["empresa_categoria_codigo"],
                    "empresa_categoria_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["empresa_categoria_codigo"], 'empresa_categoria'),
                    "empresa_nombre" => $value["empresa_nombre"],
                    "empresa_nit" => $value["empresa_nit"],
                    "empresa_departamento_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_departamento"], 'DEP'),
                    "empresa_direccion_geo" => $value["empresa_direccion_geo"]
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
        
        $_SESION['arrBandejaEmpresasGeo'] = $lst_resultado;
        
        $_SESSION['direccion_bandeja_actual'] = 'Registro/Empresa/Ver';
        
        $data["arrRespuesta"] = $lst_resultado;
        
        $this->load->view('gestion/view_bandeja_empresa_ver', $data);
    }
    
     public function DatosEmpresaNuevo_Form() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
      
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

        $arrEjecutivo = $this->mfunciones_logica->ObtenerEjecutivo(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEjecutivo);
        $data["arrEjecutivo"] = $arrEjecutivo;
        
        $this->load->view('gestion/view_empresa_form_new', $data);
     }
    
    
    public function DatosEmpresa_Form() {
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
            $estructura_id = $this->input->post('codigo', TRUE);
            
            $arrResultado = $this->mfunciones_logica->ObtenerDatosEmpresa($estructura_id);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "empresa_id" => $value["empresa_id"],
                        "empresa_consolidada_codigo" => $value["empresa_consolidada"],
                        "empresa_consolidada_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["empresa_consolidada"], 'consolidado'),
                        "empresa_categoria_codigo" => $value["empresa_categoria_codigo"],
                        "empresa_categoria_detalle" => $value["empresa_categoria"],
                        "empresa_nit" => $value["empresa_nit"],
                        "empresa_adquiriente_detalle" => 'BCP',
                        "empresa_tipo_sociedad_codigo" => $value["empresa_tipo_sociedad"],
                        "empresa_tipo_sociedad_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_tipo_sociedad"], 'TPS'),
                        "empresa_nombre_legal" => $value["empresa_nombre_legal"],
                        "empresa_nombre_fantasia" => $value["empresa_nombre_fantasia"],
                        "empresa_nombre_establecimiento" => htmlspecialchars($value["empresa_nombre_establecimiento"]),
                        "empresa_denominacion_corta" => $value["empresa_denominacion_corta"],
                        "empresa_rubro_codigo" => $value["empresa_rubro"],
                        "empresa_rubro_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_rubro"], 'RUB'),
                        "empresa_perfil_comercial_codigo" => $value["empresa_perfil_comercial"],
                        "empresa_perfil_comercial_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_perfil_comercial"], 'PEC'),
                        "empresa_mcc_codigo" => $value["empresa_mcc"],
                        "empresa_mcc_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_mcc"], 'MCC'),
                        "empresa_nombre_referencia" => $value["empresa_nombre_referencia"],
                        "empresa_ha_desde" => $this->mfunciones_generales->getFormatoFechaH_M($value["empresa_ha_desde"]),
                        "empresa_ha_hasta" => $this->mfunciones_generales->getFormatoFechaH_M($value["empresa_ha_hasta"]),
                        "empresa_dias_atencion" => $value["empresa_dias_atencion"],
                        "empresa_medio_contacto_codigo" => $value["empresa_medio_contacto"],
                        "empresa_medio_contacto_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_medio_contacto"], 'MCO'),
                        "empresa_dato_contacto" => $value["empresa_dato_contacto"],
                        "empresa_email" => $value["empresa_email"],
                        "empresa_departamento_codigo" => $value["empresa_departamento"],
                        "empresa_departamento_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_departamento"], 'DEP'),
                        "empresa_municipio_codigo" => $value["empresa_municipio"],
                        "empresa_municipio_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_municipio"], 'CIU'),
                        "empresa_zona_codigo" => $value["empresa_zona"],
                        "empresa_zona_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_zona"], 'ZON'),
                        "empresa_tipo_calle_codigo" => $value["empresa_tipo_calle"],
                        "empresa_tipo_calle_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_tipo_calle"], 'TPC'),
                        "empresa_calle" => $value["empresa_calle"],
                        "empresa_numero" => $value["empresa_numero"],
                        "empresa_direccion_geo" => $value["empresa_direccion_geo"],
                        "empresa_direccion_literal" => $value["empresa_direccion_literal"],
                        "empresa_info_adicional" => $value["empresa_info_adicional"],
                        "ejecutivo_asignado_codigo" => $value["ejecutivo_id"]
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
            
            $_SESSION['empresa_direccion_geo'] = $lst_resultado[0]["empresa_direccion_geo"];
            
            $data["arrRespuesta"] = $lst_resultado;

            // Se listan los SELECTS
            
            $data["arrVacio"][0] = array(
                "lista_codigo" => -1,
                "lista_valor" => 'Parámetro Invalido'
            );
            
            $data["arrTipoSociedad"] = $this->ObtenerListaCatalogo('TPS', -1, -1);
            
            $data["arrMCC"] = $this->ObtenerListaCatalogo('MCC', -1, -1);            
            $data["arrRubro"] = $this->ObtenerListaCatalogo('RUB', $lst_resultado[0]["empresa_rubro_codigo"], 'MCC');
            
            $data["arrPerfilComercial"] = $this->ObtenerListaCatalogo('PEC', -1, -1);
            
            $data["arrMedioContacto"] = $this->ObtenerListaCatalogo('MCO', -1, -1);
            
            $data["arrDepartamento"] = $this->ObtenerListaCatalogo('DEP', -1, -1);
            $data["arrCiudad"] = $this->ObtenerListaCatalogo('CIU', $lst_resultado[0]["empresa_departamento_codigo"], 'DEP');
            $data["arrZona"] = $this->ObtenerListaCatalogo('ZON', $lst_resultado[0]["empresa_municipio_codigo"], 'CIU');
            
            $data["arrTipoCalle"] = $this->ObtenerListaCatalogo('TPC', -1, -1);
            
            $arrEjecutivo = $this->mfunciones_logica->ObtenerEjecutivo(-1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEjecutivo);
            $data["arrEjecutivo"] = $arrEjecutivo;
            
            $data["arrDias"] = explode(",", $lst_resultado[0]['empresa_dias_atencion']);
            
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

            $this->load->view('gestion/view_empresa_form', $data);
        }
    }
    
    public function DatosEmpresaNuevo_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        if(!isset($_POST['empresa_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $empresa_nit = $this->input->post('empresa_nit', TRUE);
        
        $ejecutivo_id = $this->input->post('ejecutivo_id', TRUE);
        
        // B. SE PREGUNTA EN LA BASE DE DATOS DEL SISTEMA
        $arrResultadoNit = $this->mfunciones_logica->VerificaNitExistente($empresa_nit);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultadoNit);

        if (isset($arrResultadoNit[0]))
        {
            js_error_div_javascript($this->lang->line('FormularioNIT'));
            exit();
        }
        
        // Fin validación
        
        $accion_usuario = $_SESSION["session_informacion"]["login"];
        $accion_fecha = date('Y-m-d H:i:s');
        
        $this->mfunciones_logica->InsertEmpresa($empresa_nit, $ejecutivo_id, $accion_usuario, $accion_fecha);
        
        $this->EmpresaBandeja_Ver();
    }
    
    public function DatosEmpresa_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        if(!isset($_POST['empresa_id']) || !isset($_POST['empresa_categoria']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $empresa_id = $this->input->post('empresa_id', TRUE);
        $empresa_categoria = $this->input->post('empresa_categoria', TRUE);
        $empresa_nit = $this->input->post('empresa_nit', TRUE);
        $empresa_tipo_sociedad = $this->input->post('empresa_tipo_sociedad', TRUE);
        $empresa_nombre_legal = $this->input->post('empresa_nombre_legal', TRUE);
        $empresa_nombre_fantasia = $this->input->post('empresa_nombre_fantasia', TRUE);
        $empresa_mcc = $this->input->post('empresa_mcc', TRUE);
        $empresa_rubro = $this->input->post('empresa_rubro', TRUE);
        $empresa_perfil_comercial = $this->input->post('empresa_perfil_comercial', TRUE);
        $empresa_nombre_establecimiento = $this->input->post('empresa_nombre_establecimiento', TRUE);
        $empresa_denominacion_corta = $this->input->post('empresa_denominacion_corta', TRUE);
        $empresa_nombre_referencia = $this->input->post('empresa_nombre_referencia', TRUE);
        $empresa_ha_desde = $this->input->post('empresa_ha_desde', TRUE);
        $empresa_ha_hasta = $this->input->post('empresa_ha_hasta', TRUE);
        $dias_list = $this->input->post('dias_list', TRUE);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($dias_list);
        $empresa_medio_contacto = $this->input->post('empresa_medio_contacto', TRUE);
        $empresa_dato_contacto = $this->input->post('empresa_dato_contacto', TRUE);
        $empresa_email = $this->input->post('empresa_email', TRUE);
        $empresa_departamento = $this->input->post('empresa_departamento', TRUE);
        $empresa_municipio = $this->input->post('empresa_municipio', TRUE);
        $empresa_zona = $this->input->post('empresa_zona', TRUE);
        $empresa_tipo_calle = $this->input->post('empresa_tipo_calle', TRUE);
        $empresa_calle = $this->input->post('empresa_calle', TRUE);
        $empresa_numero = $this->input->post('empresa_numero', TRUE);
        $empresa_direccion_literal = $this->input->post('empresa_direccion_literal', TRUE);
        $empresa_info_adicional = $this->input->post('empresa_info_adicional', TRUE);
        $ejecutivo_id = $this->input->post('ejecutivo_id', TRUE);
        
        
        $separador = '<br /> - ';
        $error_texto = '';

        // Validaciones
        
        // 1 = Comercio     2 = Establecimiento/Sucursal
        
        if($empresa_categoria == 1)
        {
            if(!filter_var($empresa_nit, FILTER_VALIDATE_FLOAT) !== false){$error_texto .= $separador . $this->lang->line('empresa_nit');}
            if($empresa_tipo_sociedad == -1){$error_texto .= $separador . $this->lang->line('empresa_tipo_sociedad_detalle');}
            if($empresa_mcc == -1){$error_texto .= $separador . $this->lang->line('empresa_mcc_detalle');}
            if($empresa_rubro == -1){$error_texto .= $separador . $this->lang->line('empresa_rubro_detalle');}
            if($empresa_perfil_comercial == -1){$error_texto .= $separador . $this->lang->line('empresa_perfil_comercial_detalle');}
            if($empresa_nombre_legal == ''){$error_texto .= $separador . $this->lang->line('empresa_nombre_legal');}
            if($empresa_nombre_fantasia == ''){$error_texto .= $separador . $this->lang->line('empresa_nombre_fantasia');}
        }
        
        if($empresa_categoria == 2)
        {
            if($empresa_nombre_establecimiento == ''){$error_texto .= $separador . $this->lang->line('empresa_nombre_establecimiento');}
            if($empresa_denominacion_corta == ''){$error_texto .= $separador . $this->lang->line('empresa_denominacion_corta');}
        }
        
        if($empresa_medio_contacto == -1){$error_texto .= $separador . $this->lang->line('empresa_medio_contacto_detalle');}
        if($empresa_departamento == -1){$error_texto .= $separador . $this->lang->line('empresa_departamento_detalle');}
        if($empresa_municipio == -1){$error_texto .= $separador . $this->lang->line('empresa_municipio_detalle');}
        if($empresa_zona == -1){$error_texto .= $separador . $this->lang->line('empresa_zona_detalle');}
        if($empresa_tipo_calle == -1){$error_texto .= $separador . $this->lang->line('empresa_tipo_calle_detalle');}
        if($ejecutivo_id == -1){$error_texto .= $separador . $this->lang->line('ejecutivo_asignado_nombre');}

        if($empresa_nombre_referencia == ''){$error_texto .= $separador . $this->lang->line('empresa_nombre_referencia');}
        if($empresa_dato_contacto == ''){$error_texto .= $separador . $this->lang->line('empresa_dato_contacto');}
        if($empresa_calle == ''){$error_texto .= $separador . $this->lang->line('empresa_calle');}
        if($empresa_direccion_literal == ''){$error_texto .= $separador . $this->lang->line('empresa_direccion_literal');}
        
        
        $empresa_dias_atencion = '';
        
        if (isset($dias_list[0])) 
        {
            foreach ($dias_list as $key => $value) 
            {
                $empresa_dias_atencion .= $value . ',';
            }
        }
        
        if($empresa_dias_atencion == ''){$error_texto .= $separador . $this->lang->line('empresa_dias_atencion');}
        
        if($this->mfunciones_generales->VerificaFechaH_M($empresa_ha_desde) == false){$error_texto .= $separador . $this->lang->line('empresa_ha_desde');}
        if($this->mfunciones_generales->VerificaFechaH_M($empresa_ha_hasta) == false){$error_texto .= $separador . $this->lang->line('empresa_ha_hasta');}
        
        if($this->mfunciones_generales->VerificaCorreo($empresa_email) == false){$error_texto .= $separador . $this->lang->line('empresa_email');}
        
        if((int)$empresa_numero == 0){$error_texto .= $separador . $this->lang->line('empresa_numero');}
        
        if($error_texto != '')
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
            exit();
        }
        
        // Fin validación
        
        $accion_usuario = $_SESSION["session_informacion"]["login"];
        $accion_fecha = date('Y-m-d H:i:s');
            
        if($empresa_categoria == 1)
        {
            $this->mfunciones_logica->UpdateEmpresaComercio($ejecutivo_id, $empresa_nit, $empresa_tipo_sociedad, $empresa_nombre_referencia, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_info_adicional, $accion_usuario, $accion_fecha, $empresa_id);
        }
        
        if($empresa_categoria == 2)
        {
            $this->mfunciones_logica->UpdateEmpresaEstablecimiento($ejecutivo_id, $empresa_nombre_establecimiento, $empresa_denominacion_corta, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_info_adicional, $accion_usuario, $accion_fecha, $empresa_id);
        }
        
        $this->EmpresaBandeja_Ver();
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
    
    public function PoblarListaCatalogo() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_POST['parent_codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        // Se captura el valor
        $parent_codigo = $this->input->post('parent_codigo', TRUE);
        $tipo = $this->input->post('tipo', TRUE);
        $parent_tipo = $this->input->post('parent_tipo', TRUE);
        
        if($parent_codigo == -1)
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
            $lst_resultado1[0] = array(
                "lista_codigo" => -1,
                "lista_valor" => 'No se encontró dependencias'
            );
        }
        
        echo json_encode($lst_resultado1);
    }
    
    public function Empresa_Zona_Ver() {
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

            $this->load->view('gestion/view_empresa_mapa_ver', $data);
        }
    }
    
    public function Empresa_Zona_Mapa() {
        
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

            $empresa_direccion_geo = '';
            
            $arrResultado = $this->mfunciones_logica->ObtenerDatosEmpresa($estructura_id);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
            
            if (isset($arrResultado[0])) 
            {            
                $empresa_direccion_geo = $arrResultado[0]['empresa_direccion_geo'];
            }
            
            if($empresa_direccion_geo == '')
            {
                $empresa_direccion_geo = GEO_FIE; // Ubicación FIE
            }
            
            //Marcadores

            $config['center'] = $empresa_direccion_geo;
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
            $marker['position'] = $empresa_direccion_geo;
            $marker['infowindow_content'] = '<div style="text-align: center;"> <img src="' . MARCADOR_LOGO . '" style="width: 30px;" align="top" /> <span style="font-size: 20px; font-weight: bold;"> Ubicación de la Empresa </span> <br /> <span> Ubique este pin donde lo requiera </span> </div>';
            $marker['icon'] = MARCADOR_ZONA;
            $marker['animation'] = 'DROP';        
            $marker['draggable'] = true;
            $marker['ondragend'] = 'ActualizarGeoSolicitud(event.latLng.lat(), event.latLng.lng());';
            $this->googlemaps->add_marker($marker);
            $data['map'] = $this->googlemaps->create_map();

            $data['empresa_id'] = $estructura_id;

            $this->load->view('gestion/view_empresa_mapa_zona', $data);
        }
    }
    
    public function Empresa_Zona_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        // Se captura el valor
        $newLat = $this->input->post('newLat', TRUE);
        $newLng = $this->input->post('newLng', TRUE);
        
        $empresa_id = $this->input->post('empresa_id', TRUE);
         
        $empresa_direccion_geo = $newLat . ', ' . $newLng;
        
        // Se actualiza le ubicación
        $this->mfunciones_logica->UpdateEmpresaGeo($empresa_direccion_geo, $nombre_usuario, $fecha_actual, $empresa_id);
    }
    
    public function Empresa_Recibe_Geo() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_POST['geo_list']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $geo_list = json_decode($this->input->post('geo_list', TRUE), true);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($geo_list);
        
        $lista_empresas = '';
        
        if (isset($geo_list[0])) 
        {
            foreach ($geo_list as $key => $value) 
            {
                $lista_empresas .= $value . ',';
            }
        }
        
        if($lista_empresas == '')
        {
            js_error_div_javascript('No seleccionó ninguna empresa. Debe seleccionar almenos una.');
            exit();
        }
        
        $lista_empresas .= 0;
        
        $lista_empresas = str_replace("on,","", $lista_empresas);
        
        $_SESSION['lista_empresas_geo'] = $lista_empresas;
        
        $this->load->view('gestion/view_empresa_geo_ver');
    }
    
    public function Empresa_Geo_Mapa() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('leaflet');

        if(!isset($_SESSION['lista_empresas_geo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            $arrResultado = $this->mfunciones_logica->ObtenerDatosGeoEmpresa($_SESSION['lista_empresas_geo']);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
            
            //Marcadores
            
            if (isset($arrResultado[0])) 
            {
                foreach ($arrResultado as $key => $value) 
                {

                    $marker['latlng'] = $value["empresa_direccion_geo"];
                    $marker['popupContent'] = '<div style=\"text-align: center;\"> <img src=\"' . MARCADOR_LOGO . '\" style=\"width: 30px;\" align=\"top\" /> <span style=\"font-size: 20px; font-weight: bold;\"> ' . htmlspecialchars($value["empresa_nombre"]) . ' </span> <br /> <span> ' . $value["empresa_categoria"] . ' </span> </div>'; // Popup Content
                    $this->leaflet->add_marker($marker);
                }
            }
            $config = array(
                'center'         => '-16.278463, -63.649296', // Center of the map from Bolivia
                'zoom'           => 6, // Map zoom
            );
            
            $this->leaflet->initialize($config);
            $data['map'] = $this->leaflet->create_map();
            
            
            $this->load->view('gestion/view_empresa_mapa_geo', $data);
        }
    }
    
    public function DocumentosProspecto_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_microcreditos');
        
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
                // Detalle del Prospecto
                switch ((int)$codigo_tipo_persona) {
                    
                    // Solicitud de Crédito
                    case 6:

                        $arrResultado = $this->mfunciones_microcreditos->SolObtenerDocumentosDigitalizarApp(6);

                        $DatosProspecto = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($estructura_id);
                        $DatosProspecto[0]['general_solicitante'] = $DatosProspecto[0]['sol_nombre_completo'];

                        break;

                    // Normalizador/Cobrador
                    case 13:
                        
                        $this->load->model('mfunciones_cobranzas');
                        
                        $arrResultado = $this->mfunciones_microcreditos->SolObtenerDocumentosDigitalizarApp(13);
                        
                        $DatosProspecto = $this->mfunciones_cobranzas->VerificaNormConsolidado($estructura_id);
                        $DatosProspecto[0]['general_solicitante'] = $DatosProspecto[0]['norm_primer_nombre'] . ' ' . $DatosProspecto[0]['norm_segundo_nombre'] . ' ' . $DatosProspecto[0]['norm_primer_apellido'] . ' ' . $DatosProspecto[0]['norm_segundo_apellido'];
                        
                        break;
                    
                    default:
                        
                        $arrResultado = $this->mfunciones_logica->ObtenerDocumentosDigitalizarApp($estructura_id);
                    
                        // Datos del Prospecto
                        $DatosProspecto = $this->mfunciones_generales->GetDatosEmpresaCorreo($estructura_id);
                        
                        break;
                }
                
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        switch ((int)$codigo_tipo_persona) {
                            
                            // Solicitud de Crédito
                            case 6:

                                $documento_digitalizado = $this->mfunciones_microcreditos->GetInfoSolicitudDigitalizadoPDF($estructura_id, $value["documento_id"], 'existe');
                                $documento_observado = FALSE;
                                
                                break;

                            // Normalizador/Cobrador
                            case 13:
                                
                                $this->load->model('mfunciones_cobranzas');
                                
                                $documento_digitalizado = $this->mfunciones_cobranzas->GetInfoRegistroDigitalizadoPDF($estructura_id, $value["documento_id"], 'existe');
                                $documento_observado = $this->mfunciones_cobranzas->RegistroVerificaDocumentoObservado($estructura_id, $value["documento_id"], (int)$codigo_tipo_persona);
                                
                                break;
                            
                            default:
                                
                                $documento_digitalizado = $this->mfunciones_generales->GetInfoDigitalizado($estructura_id, $value["documento_id"], 'existe');
                                $documento_observado = $this->mfunciones_generales->VerificaDocumentoObservado($estructura_id, $value["documento_id"]);
                                
                                break;
                        }
                        
                        $item_valor = array(
                            "documento_id" => $value["documento_id"],
                            "documento_detalle" => $value["documento_nombre"],
                            "documento_digitalizado" => $documento_digitalizado,
                            "documento_observado" => $documento_observado
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }
                else 
                {
                    $lst_resultado[0] = $arrResultado;
                }
                
                $data["prospecto_rechazado"] = 0;
                
                $data["arrRespuesta"] = $lst_resultado;
                
                $data['estructura_id'] = $estructura_id;
                
                $data['codigo_tipo_persona'] = $codigo_tipo_persona;
                
                $data["arrProspecto"] = $DatosProspecto;
                
                // Variable que indica donde volver
                $_SESSION['funcion_ver_documento'] = 'Ajax_CargarAccion_AdministrarProspecto';
                
                $this->load->view('gestion/view_documento_administrar', $data);
            }
        }
    }
    
    public function DocumentosProspecto_Form() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();

        $documento_codigo = -2;
        $prospecto_codigo = -2;
        
        if(isset($_POST['codigo_documento']) && isset($_POST['codigo_prospecto']))
        {
            $documento_codigo = $this->input->post('codigo_documento', TRUE);
            $prospecto_codigo = $this->input->post('codigo_prospecto', TRUE);
            
            $codigo_tipo_persona = $this->input->post('codigo_tipo_persona', TRUE);
        }
        elseif(isset($_SESSION['aux_codigo_pro_upload']) && isset($_SESSION['aux_codigo_doc_upload']) && isset($_SESSION['aux_codigo_tipo_upload']))
        {
            $documento_codigo = $_SESSION['aux_codigo_doc_upload'];
            $prospecto_codigo = $_SESSION['aux_codigo_pro_upload'];
            
            $codigo_tipo_persona = $_SESSION['aux_codigo_tipo_upload'];
        }
        
        if($documento_codigo == -2 || $prospecto_codigo == -2)
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
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
            
            switch ((int)$codigo_tipo_persona) {
                
                // Solicitud de Crédito
                case 6:
                    $this->load->model('mfunciones_microcreditos');
                    
                    $DatosProspecto = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($prospecto_codigo);
                    $DatosProspecto[0]['general_solicitante'] = $DatosProspecto[0]['sol_nombre_completo'];

                    break;

                // Normalizador/Cobrador
                case 13:
                
                    $this->load->model('mfunciones_cobranzas');
                    
                    $DatosProspecto = $this->mfunciones_cobranzas->VerificaNormConsolidado($prospecto_codigo);
                    $DatosProspecto[0]['general_solicitante'] = $DatosProspecto[0]['norm_primer_nombre'] . ' ' . $DatosProspecto[0]['norm_segundo_nombre'] . ' ' . $DatosProspecto[0]['norm_primer_apellido'] . ' ' . $DatosProspecto[0]['norm_segundo_apellido'];
                    
                    break;
                
                default:
                    
                    // Datos del Prospecto
                    $DatosProspecto = $this->mfunciones_generales->GetDatosEmpresaCorreo($prospecto_codigo);
                    
                    break;
            }
            
            $data["arrProspecto"] = $DatosProspecto;

            $this->load->view('gestion/view_documento_form', $data);
        }
    }
    
    public function DocumentosProspecto_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $documento_codigo = $this->input->post('documento_codigo', TRUE);
        $prospecto_codigo = $this->input->post('prospecto_codigo', TRUE);
        
        $codigo_tipo_persona = $this->input->post('codigo_tipo_persona', TRUE);

        if($documento_codigo == "" || $prospecto_codigo == "")
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        $_SESSION['aux_codigo_pro_upload'] = $prospecto_codigo;
        $_SESSION['aux_codigo_doc_upload'] = $documento_codigo;
        $_SESSION['aux_codigo_tipo_upload'] = $codigo_tipo_persona;
        
        $documento_pdf = '';
        
        $_SESSION['auxiliar_bandera_upload'] = 2;
        $_SESSION['auxiliar_bandera_upload_url'] = 'Registro/Documento/Form';

        if(empty($_FILES['documento_pdf']['tmp_name']))
        {
            redirect($this->config->base_url());
        }

        if (!empty($_FILES['documento_pdf']['tmp_name'])) 
        {
            $nombre_usuario = $_SESSION["session_informacion"]["login"];
            $fecha_actual = date('Y-m-d H:i:s');
            
            // Nombre del Arhivo

            $arrResultado1 = $this->mfunciones_logica->ObtenerNombreDocumento($documento_codigo);        
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

            $nombre_documento = $this->mfunciones_generales->TextoNoAcentoNoEspacios($arrResultado1[0]['documento_nombre']);

            //Se añade la fecha y hora al final
            $nombre_documento .= '__' . date('Y-m-d_H_i_s') . '.pdf';
            
            // Paso 1: Obtener la data del Registro
            
            switch ((int)$codigo_tipo_persona) {
                
                // Solicitud de Crédito
                case 6:
                    
                    $this->load->model('mfunciones_microcreditos');
                    
                    $path = RUTA_SOLCREDITOS . 'sol_' . $prospecto_codigo;
                    if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
                    {
                        mkdir($path, 0755, TRUE);
                        // Se crea el archivo html para evitar ataques de directorio
                        $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                        write_file($path . '/index.html', $cuerpo_html);
                    }
                    
                    $path = RUTA_SOLCREDITOS . 'sol_' . $prospecto_codigo;
                    
                    $config['file_name'] = 'sol_' . $prospecto_codigo . '_' . $nombre_documento;
                    
                    break;

                // Normalizador/Cobrador
                case 13:
                    
                    $this->load->model('mfunciones_cobranzas');
                    
                    $path = $this->lang->line('ruta_cobranzas') . 'reg_' . $prospecto_codigo;
                    if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
                    {
                        mkdir($path, 0755, TRUE);
                        // Se crea el archivo html para evitar ataques de directorio
                        $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                        write_file($path . '/index.html', $cuerpo_html);
                    }
                    
                    $path = $this->lang->line('ruta_cobranzas') . 'reg_' . $prospecto_codigo;
                    
                    $config['file_name'] = 'reg_' . $prospecto_codigo . '_' . $nombre_documento;
                    
                    break;
                    
                default:
                    
                    // Detalle del Prospecto
                    $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($prospecto_codigo);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                    if($arrResultado3[0]['onboarding'] == 1)
                    {
                        // Paso 1.1: Se verifica si existe el directorio del Prospecto, caso contrario crear el directorio
                        $path = RUTA_TERCEROS . 'ter_' . $arrResultado3[0]['onboarding_codigo'];
                        if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
                        {
                            mkdir($path, 0755, TRUE);
                            // Se crea el archivo html para evitar ataques de directorio
                            $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                            write_file($path . '/index.html', $cuerpo_html);
                        }

                        $path = RUTA_TERCEROS . 'ter_' . $arrResultado3[0]['onboarding_codigo'];

                        $mi_archivo = 'documento_pdf';
                        $config['upload_path'] = $path;
                        $config['file_name'] = 'ter_' . $arrResultado3[0]['onboarding_codigo'] . '_' . $nombre_documento;
                        $config['allowed_types'] = "*";
                        $config['max_size'] = "70000";

                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);

                        if (!$this->upload->do_upload($mi_archivo)) {
                            //*** ocurrio un error
                            $data['uploadError'] = $this->upload->display_errors();
                            echo $this->upload->display_errors();
                            return;
                        }

                        $data['uploadSuccess'] = $this->upload->data();

                        $this->mfunciones_logica->InsertarDocumentoTercero($arrResultado3[0]['onboarding_codigo'], $documento_codigo, $nombre_documento, $nombre_usuario, $fecha_actual);
                    }

                    // Paso 1.1: Se verifica si existe el directorio del Prospecto, caso contrario crear el directorio
                    $path = RUTA_PROSPECTOS . 'afn_' . $prospecto_codigo;
                    if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
                    {
                        mkdir($path, 0755, TRUE);
                        // Se crea el archivo html para evitar ataques de directorio
                        $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                        write_file($path . '/index.html', $cuerpo_html);
                    }

                    $path = RUTA_PROSPECTOS . 'afn_' . $prospecto_codigo;
                    
                    $config['file_name'] = 'afn_' . $prospecto_codigo . '_' . $nombre_documento;
                    
                    break;
            }

            $mi_archivo = 'documento_pdf';
            $config['upload_path'] = $path;
            $config['allowed_types'] = "*";
            $config['max_size'] = "70000";

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload($mi_archivo)) {
                //*** ocurrio un error
                $data['uploadError'] = $this->upload->display_errors();
                echo $this->upload->display_errors();
                return;
            }

            $data['uploadSuccess'] = $this->upload->data();
            
            switch ((int)$codigo_tipo_persona) {
                
                // Solicitud de Crédito
                case 6:
                    
                    $this->mfunciones_microcreditos->InsertarDocumentoSolicitud($prospecto_codigo, $documento_codigo, $nombre_documento, $nombre_usuario, $fecha_actual);
                    
                    $DatosProspecto = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($prospecto_codigo);
                    $DatosProspecto[0]['ejecutivo_id'] = $DatosProspecto[0]['ejecutivo_id'];
                    
                    break;
                
                // Normalizador/Cobrador
                case 13:
                    
                    $this->mfunciones_cobranzas->InsertarDocumentoRegistro($prospecto_codigo, $documento_codigo, $nombre_documento, $nombre_usuario, $fecha_actual, (int)$codigo_tipo_persona);
                    
                    $DatosProspecto = $this->mfunciones_cobranzas->VerificaNormConsolidado($prospecto_codigo);
                    
                    break;
                
                default:
                    
                    $this->mfunciones_logica->InsertarDocumentoProspecto($prospecto_codigo, $documento_codigo, $nombre_documento, $nombre_usuario, $fecha_actual);
                    
                    $DatosProspecto = $this->mfunciones_generales->GetDatosEmpresaCorreo($prospecto_codigo);
                    
                    break;
            }
        }
        
        // AUXILIARMENTE SE ENVIA UN FLAG
        
        $_SESSION['auxiliar_bandera_upload'] = 1;
        $_SESSION['auxiliar_bandera_upload_url'] = 'aux_lead';
        $_SESSION['auxiliar_bandera_upload_url_aux'] = "Ajax_CargarAccion_ProspectoAux('" . $DatosProspecto[0]['ejecutivo_id'] . "');";
        
        redirect($this->config->base_url());
    }
    
    public function ForzarConsolidarProspecto() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $codigo_prospecto = $this->input->post('codigo', TRUE);
        
		// Se verifica que el prospecto No es este Consolidado. No puede actualizarse si esta consolidado
		
		// Detalle del Prospecto
                $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
        
        if (isset($arrResultado3[0])) 
        {
            // Se capturan variables auxiliares
            
            if($arrResultado3[0]['prospecto_consolidado'] == 1)
            {
                js_error_div_javascript($this->lang->line('FormularioYaConsolidado'));
                exit();
            }
            
            if($arrResultado3[0]['prospecto_evaluacion'] == 0)
            {
                js_error_div_javascript('No Consolidado: Aún no realizó la evaluación del Cliente, por favor debe realizar la evaluación para poder Consolidar.');
                exit();
            }
            
            if($arrResultado3[0]['onboarding'] == 1 && $arrResultado3[0]['prospecto_evaluacion'] == 1)
            {
                // Listado de los Documentos
                $arrResultado1 = $this->mfunciones_logica->ObtenerDocumentosDigitalizarOnb($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                if (isset($arrResultado1[0]))
                {
                    foreach ($arrResultado1 as $key => $value) 
                    {
                        if($this->mfunciones_generales->GetInfoDigitalizado($codigo_prospecto, $value["documento_id"], 'existe') == FALSE)
                        {
                            js_error_div_javascript('No Consolidado: Debe digitalizar todos los elementos antes de Consolidar.');
                            exit();
                        }
                    }
                }
                else 
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }
            }
        }
        else 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }

        // SÓLO CUANDO ES ESTUDIO DE CRÉDITO Y NO ES ONBOARDING
        // SÓLO PARA LOS RUBROS QUE NO SE CONVIERTEN A ESTUDIO
        if($arrResultado3[0]['onboarding'] == 0)
        {
            $this->load->model('mfunciones_microcreditos');
            
            if($this->mfunciones_microcreditos->ValidarNumOperacion($arrResultado3[0]['prospecto_num_proceso']))
            {
                js_error_div_javascript($this->lang->line('registro_num_proceso_error_app'));
                exit();
            }
        }
        
        // PASO 2: Se cambia el estado del Prospecto para que sea visible por "Cumplimiento" Estado=2
        
        $accion_usuario = $_SESSION["session_informacion"]["login"];
        $accion_fecha = date('Y-m-d H:i:s');

        $this->mfunciones_logica->ForzarConsolidarProspecto(GEO_FIE, $accion_usuario, $accion_fecha, $codigo_prospecto);

        // PASO 3: Se cambia el estado a las observaciones (si existiesen) del Prospecto => Estado=0

        $this->mfunciones_logica->UpdateObservacionDoc(0, $accion_usuario, $accion_fecha, $codigo_prospecto);
        
        
        // FLUJO NORMAL
            
        // Verifica primero si es Onboarding

        if($arrResultado3[0]['onboarding'] == 0)
        {
            $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 5, $arrResultado3[0]['prospecto_etapa'], $accion_usuario, $accion_fecha, 2);
            $codigo_onboarding = 0;
        }
        else
        {
            $arrUsuario = $this->mfunciones_logica->getUsuarioCriterio('ejecutivo', $arrResultado3[0]['ejecutivo_id']);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrUsuario);

            // Evaluación 1=Aprobado    2=Rechazado

            if($arrResultado3[0]['prospecto_evaluacion'] == 1)
            {
                // Aprobado

                // *** Llamar a función para Aprobar y Flujo COBIS en 2do plano ***
                $this->mfunciones_generales->Aprobar_FlujoCobis_background($arrResultado3[0]['onboarding_codigo'], $arrUsuario[0]['usuario_id'], $accion_usuario);
                $codigo_onboarding = $arrResultado3[0]['onboarding_codigo'];
            }
            else
            {
                // Rechazado

                $this->mfunciones_logica->RechazarSolicitudTercerosApp($arrUsuario[0]['usuario_id'], $accion_usuario, $accion_fecha, $arrResultado3[0]['onboarding_codigo']);
                
                // Se registra para las Etapas Onboarding   Pasa a: Notificar Rechazo Onboarding   Etapa Nueva     Etapa Actual
                $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 10, $arrResultado3[0]['prospecto_etapa'], $accion_usuario, $accion_fecha, 0);
                $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 10, $arrResultado3[0]['prospecto_etapa'], $accion_usuario, $accion_fecha, 0);
            }
        }
        
        /***  REGISTRAR SEGUIMIENTO ***/
        $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, 5, 1, 'Forzar Consolidar Lead', $accion_usuario, $accion_fecha);
        
        $data['codigo_onboarding'] = $codigo_onboarding;
        
        $this->load->view('gestion/view_consolidar_guardado', $data);
    }
    
    public function ForzarConsolidarProspectoSol() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $codigo_prospecto = $this->input->post('codigo', TRUE);
        $arrResultado3 = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($codigo_prospecto);
        
        if(!isset($arrResultado3[0])) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
            
        if($arrResultado3[0]['sol_consolidado'] == 1)
        {
            js_error_div_javascript($this->lang->line('FormularioYaConsolidado'));
            exit();
        }

        if($arrResultado3[0]['sol_evaluacion'] == 0)
        {
            js_error_div_javascript($this->lang->line('ConsolidarSinEvaluacion'));
            exit();
        }
        
        $accion_usuario = $_SESSION["session_informacion"]["login"];
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Evaluación 1=Aprobado    2=Rechazado
        if($arrResultado3[0]['sol_evaluacion'] == 1)
        {
            // SÓLO CUANDO LA EVALUACIÓN ES "APROBADO". Validar que el Número de Operación esté correctamente registrado
            // SÓLO PARA LOS RUBROS QUE NO SE CONVIERTEN A ESTUDIO
            if((int)$arrResultado3[0]['sol_codigo_rubro'] >= 7)
            {
                if($this->mfunciones_microcreditos->ValidarNumOperacion($arrResultado3[0]['registro_num_proceso']))
                {
                    js_error_div_javascript($this->lang->line('registro_num_proceso_error_app'));
                    exit();
                }
            }
            
            // Aprobado
            $sol_consolidado = $this->mfunciones_microcreditos->ConsolidarSolicitud(GEO_FIE, $arrResultado3[0]['agente_codigo'], $accion_usuario, $accion_fecha, $codigo_prospecto);
            
            if($sol_consolidado == FALSE)
            {
                js_error_div_javascript($this->lang->line('sol_no_consolidado'));
                exit();
            }
        }
        else
        {
            // Rechazado
            $this->mfunciones_microcreditos->RechazarSolicitudCreditoApp($arrResultado3[0]['agente_codigo'], $accion_usuario, $accion_fecha, $codigo_prospecto);
        }

        // Notificar Proceso Onboarding Solicitud Crédito     0=No Regionalizado      1=Si Regionalizado
        $this->mfunciones_microcreditos->NotificacionEtapaCredito($codigo_prospecto, ($arrResultado3[0]['sol_evaluacion']==1 ? 20 : 21), 1);
        
        $data['codigo_onboarding'] = 0;
        
        $this->load->view('gestion/view_consolidar_guardado', $data);
    }
    
    public function ForzarConsolidarProspectoNorm() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        $this->load->model('mfunciones_cobranzas');
        
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $codigo_prospecto = $this->input->post('codigo', TRUE);
        
        // Validaciones de las reglas de negocio: Sólo se asigna el CheckIn a la última visita registrada (sólo 1 vez)
        $check_visita = $this->mfunciones_cobranzas->checkVisitaRegistrada($codigo_prospecto, -1, 'check_consolidar', 'ORDER BY cv_id DESC');
        
        if($check_visita->error)
        {
            js_error_div_javascript($check_visita->error_texto);
            exit();
        }
        
        $accion_usuario = $_SESSION["session_informacion"]["login"];
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Consolidar
        $this->mfunciones_cobranzas->setConsolidarNorm(GEO_FIE, $check_visita->usuario_id, $accion_usuario, $accion_fecha, $codigo_prospecto);

        // Se cambia el estado a las observaciones (si existiesen) del registro => Estado=0
        $this->mfunciones_cobranzas->RegUpdateObservacionDoc(0, $accion_usuario, $accion_fecha, $codigo_prospecto, 13);

        // Notificar Proceso Onboarding Normalizador/Cobrador     0=No Regionalizado      1=Si Regionalizado
        $this->mfunciones_cobranzas->NotificacionEtapaRegistro($codigo_prospecto, 26, 1);
        
        $direccion_bandeja = 'Menu/Principal';
        if(isset($_SESSION['direccion_bandeja_actual']))
        {
            $direccion_bandeja = $_SESSION['direccion_bandeja_actual'];
        }
        
        js_invocacion_javascript('alert("¡Se consolidó el registro correctamente! Se redireccionará a la bandeja."); ' . $direccion_bandeja . ';');
    }
    
    public function ForzarNormCheckIn() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        $this->load->model('mfunciones_cobranzas');
        
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $codigo_prospecto = $this->input->post('codigo', TRUE);
        
        // Validaciones de las reglas de negocio: Sólo se asigna el CheckIn a la última visita registrada (sólo 1 vez)
        $check_visita = $this->mfunciones_cobranzas->checkVisitaRegistrada($codigo_prospecto, -1, 'check_checkin');
        
        if($check_visita->error)
        {
            js_error_div_javascript($check_visita->error_texto);
            exit();
        }
        
        $accion_usuario = $_SESSION["session_informacion"]["login"];
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Registrar CheckIn
        $this->mfunciones_cobranzas->NormVisitaUpdateCheckInForzar(GEO_FIE, $check_visita->codigo_visita, $codigo_prospecto, $accion_usuario, $accion_fecha);
        
        $direccion_bandeja = 'Menu/Principal';
        if(isset($_SESSION['direccion_bandeja_actual']))
        {
            $direccion_bandeja = $_SESSION['direccion_bandeja_actual'];
        }
        
        js_invocacion_javascript('alert("¡Check Visita registrado correctamente! Se redireccionará a la bandeja."); ' . $direccion_bandeja . ';');
    }
}
?>