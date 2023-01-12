<?php
/**
 * @file 
 * Codigo que implementa el controlador para el tracking de visitas Normalizador/Cobrador
 * @brief BANDEJA DE LA INSTANCIA
 * @author JRAD
 * @date 2022
 * @copyright 2022 JRAD
 */
/**
 * Controlador para el tracking de visitas Normalizador/Cobrador
 * @brief BANDEJA DE LA INSTANCIA
 * @class Seguimiento_controller 
 */
class Seguimiento_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 54;

    function __construct() {
        parent::__construct();
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model("mfunciones_cobranzas");
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
    
    public function Bandeja_Ver() {
        
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        // Borrar las variables de SESSION
        if(isset($_SESSION['arrResultadoSeguimiento']))
        {
            $_SESSION['arrResultadoSeguimiento'] = '';
            $_SESSION['arrResultadoSeguimiento_filtro'] = '';
        }
        
            /***** REGIONALIZACIÓN INICIO ******/                
                $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
                $data['nombre_region'] = $lista_region->region_nombres_texto;
            /***** REGIONALIZACIÓN FIN ******/
        
        $data['tipo_bandeja'] = 'tracking';
                
        $this->load->view('cobranza/view_bandeja_ver', $data);
    }
    
    public function Reportes_Generar() {
        $parametros = json_decode(urldecode($this->input->get_post("parametros")));
        
        $resultado = $this->mfunciones_cobranzas->Generar_Reporte_Norm($parametros);
        if ($resultado->cuenta <= 0) die($this->lang->line('TablaNoResultadosReporte'));
        
        $formato_reporte = (int)$parametros->formato_reporte;

        $_SESSION['arrResultadoSeguimiento'] = $resultado->filas;
        $_SESSION['arrResultadoSeguimiento_filtro'] = $resultado->valoresFiltro . '<b>*Registros encontrados: </b> ' . number_format($resultado->cuenta, 0, '.', ',');
        $_SESSION['arrResultadoSeguimiento_formato_reporte'] = $formato_reporte;

        $data['valoresFiltro'] = '<b>Registros encontrados con los filtros seleccionados: </b> ' . number_format($resultado->cuenta, 0, '.', ',');
        
        // Formato Reporte      1=Tabla     2=Tabla con Paginación      3=Mapa
        if($formato_reporte == 3 || $formato_reporte == 4)
        {
            $this->load->view('cobranza/view_tracking_mapa', $data);
        }
        else
        {
            $data['formato_reporte'] = $formato_reporte;
            $this->load->view('cobranza/view_tracking_tabla', $data);
        }
        
        return;
    }
    
    public function Seguimiento_Excel() {
        $this->load->view('cobranza/view_tracking_excel');
    }
    
    public function Seguimiento_Mapa() {
        
        $this->load->library('googlemaps');
        
        if (!isset($_SESSION['arrResultadoSeguimiento'][0])) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // Normalizador/Cobrador
            $tipo_visita = MARCADOR_MANTENIMIENTO;
            
            foreach ($_SESSION['arrResultadoSeguimiento'] as $key => $value) 
            {
                $codigo_visita = '<span style="color: #006699; cursor: pointer; font-weight: bold;" onclick="parent.Ajax_CargarAccion_DetalleNorm(\'' . $value["norm_id"] . '\')">' . $this->lang->line('norm_prefijo') . $value['norm_id'] . '</span>';
                $coordenadas = $value['cv_checkin_geo'];
                
                //Marcadores 
                if(preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $coordenadas))
                {
                    $html_marker = '
                        <div style="text-align: center; max-width: 350px;">
                            <img src="' . MARCADOR_LOGO . '" style="width: 30px;" align="top" />
                            <span style="font-size: 20px; font-weight: bold;"> ' . $value['cliente_nombre'] . ' </span>
                            <br />
                            <ul style="text-align: justify;">
                                <li style="font-size: 0.90em;">
                                    <b>Cliente/Caso:</b> ' . $codigo_visita . '
                                </li>
                                <li style="font-size: 0.90em;">
                                    <b>Agencia Asociada:</b> ' . $value['agencia_nombre'] . ((int) $value["agencia_estado"] == 1 ? '' : ' (Cerrada)') . '
                                </li>
                                <li style="font-size: 0.90em;">
                                    <b>' . $this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app_singular') . ':</b> ' . $value['ejecutivo_nombre'] . ((int) $value["usuario_activo"] == 1 ? '' : ' (Inactivo)') . '
                                </li>
                                <li style="font-size: 0.90em;">
                                    <b>' . $this->lang->line('norm_col_num_proceso') . ':</b> ' . $value['registro_num_proceso'] . '
                                </li>
                                <li style="font-size: 0.90em;">
                                    <b>' . $this->lang->line('norm_col_estado') . ':</b> ' . $value['registro_consolidado'] . ($value["norm_ultimo_paso_check"] ? '<i>(Registro Pendiente)</i>' : '') . '
                                </li>
                                <li style="font-size: 0.90em;">
                                    <b>' . $this->lang->line('norm_col_fec_registro') . ':</b> ' . $value['fecha_registro'] . '
                                </li>
                                <br />
                                <li>
                                    <b>' . $this->lang->line('norm_col_fec_visita') . ':</b> ' . $value['cv_fecha'] . '
                                </li>
                                <li>
                                    <b>' . $this->lang->line('cv_checkin_fecha') . ':</b> ' . $value['cv_checkin_fecha'] . '
                                </li>
                                <li>
                                    <b>' . $this->lang->line('cv_resultado') . ':</b> ' . $value['cv_resultado'] . '
                                </li>
                                <li>
                                    <b>' . $this->lang->line('cv_fecha_compromiso') . ':</b> ' . $value['cv_fecha_compromiso'] . '
                                </li>
                            </ul>
                        </div>
                        ';
                    
                    $html_marker = str_replace(array("\r", "\n"), '', $html_marker);
                    
                    $marker = array();
                    $marker['position'] = $coordenadas;
                    $marker['infowindow_content'] = $html_marker;
                    $marker['icon'] = $tipo_visita;
                    $marker['animation'] = 'DROP';        
                    $marker['draggable'] = false;
                    $this->googlemaps->add_marker($marker);
                }
            }

            $config['center'] = $coordenadas;
            $config['zoom'] = 'auto';
            
            // Si se marcó la opción de mapa con clusterer (agrupador) se muestra
            if((int)$_SESSION['arrResultadoSeguimiento_formato_reporte'] == 4)
            {
                $config['cluster'] = TRUE;
            }
            
            // Parámetros de la Key de Google Maps
            $arrResultado3 = $this->mfunciones_logica->ObtenerDatosConf_General();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
            if (isset($arrResultado3[0])) 
            {                
                $config['apiKey'] = $arrResultado3[0]['conf_general_key_google'];
            }
            
            $this->googlemaps->initialize($config);
            
            $data['map'] = $this->googlemaps->create_map();
            
            $this->load->view('ejecutivos_cuenta/view_seguimiento_mapa', $data);            
        }
    }
}
?>