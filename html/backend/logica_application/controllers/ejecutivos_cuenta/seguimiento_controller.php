<?php
/**
 * @file 
 * Codigo que implementa el controlador para ga gestión del catálogo del sistema
 * @brief  TAREAS DE MANTENIMIENTO DE CARTERA DEL SISTEMA
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para ga gestión del catálogo del sistema
 * @brief TAREAS DE MANTENIMIENTO DE CARTERA DEL SISTEMA
 * @class Conf_catalogo_controller 
 */
class Seguimiento_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = "12, 47, 48, 49, 50";

    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
	 
    public function Seguimiento_Ver($identificador_app) {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // Se establece el codigo del perfil_app para Ejecutivos de Cuenta -> Para ver el código revise la tabla "perfil_app"
        $_SESSION["identificador_tipo_perfil_app_tracking"] = $identificador_app;
        
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
        
        $this->load->view('ejecutivos_cuenta/view_seguimiento_ver', $data);        
    }
    
    private function ArmarValoresFiltro($sel_departamento, $sel_agencia, $sel_oficial, $campoFiltroFechaDesde, $campoFiltroFechaHasta, $tipo_registro, $estado)
    {
        $nombres_filtro = '';
        
        $separador = '<b> ó </b> ';
        
        // Tipo de Registro
        $nombres_filtro .= '<b>*Tipo de Registro: </b> ';

        switch ((int)$tipo_registro) {
            case 99:

                $nombres_filtro .= $separador . 'Estudio y Solicitud de Crédito';

                break;

            case 1:

                $nombres_filtro .= $separador . 'Estudio de Crédito';

                break;

            case 2:

                $nombres_filtro .= $separador . 'Solicitud de Crédito';

                break;

            default:
                break;
        }

        $nombres_filtro .= '<br />';
        
        // Estado
        $nombres_filtro .= '<b>*Estado Agencia/Oficial de Negocio: </b> ';

        switch ((int)$estado) {
            case 0:

                $nombres_filtro .= $separador . 'Cerrada/Inactivo';

                break;

            case 1:

                $nombres_filtro .= $separador . 'Abierta/Activo';

                break;

            case 2:

                $nombres_filtro .= $separador . 'Todos';

                break;

            default:
                break;
        }

        $nombres_filtro .= '<br />';
        
        // Departamento
        if ($sel_departamento != '')
        {
            $nombres_filtro .= '<b>*Departamento: </b> ';
            
            $sel_departamento = explode (",", $sel_departamento);
            
            foreach ($sel_departamento as $key => $value) 
            {
                switch ((int)$value) {

                    case 115:
                        $nombres_filtro .= $separador . 'LA PAZ - EL ALTO';
                        break;

                    default:
                        $nombres_filtro .= $separador . $this->mfunciones_generales->GetValorCatalogoDB($value, 'dir_departamento');
                        break;
                }
            }
            
            $nombres_filtro .= '<br />';
        }
        
        $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        
        // Agencias
        if($sel_agencia != '')
        {
            $filtro_agencia = ' AND estructura_regional_id IN(' . $lista_region->region_id . ')';
            
            $nombres_filtro .= '<b>*Agencia: </b> ';
            
            $filtro_agencia .= " AND estructura_regional_id IN($sel_agencia)";
            
            $arrAgencia = $this->mfunciones_dashboard->ObtenerListaAgencia_generico($filtro_agencia);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrAgencia);
            
            if (isset($arrAgencia[0])) 
            {
                foreach ($arrAgencia as $key => $value) 
                {
                    $nombres_filtro .= $separador . $value["estructura_regional_nombre"] . ((int)$value["estructura_regional_estado"]==1 ? '' : ' (Cerrada)');
                }
            }
            
            $nombres_filtro .= '<br />';
        }
        
        // Oficiales de Negocios
        if($sel_oficial != '')
        {   
            $nombres_filtro .= '<b>*Oficial de Negocios: </b> ';
            
            $filtro_oficial .= ' AND er.estructura_regional_id IN(' . $lista_region->region_id . ')';
            
            $filtro_oficial .= " AND e.ejecutivo_id IN($sel_oficial)";
            
            $arrOficial = $this->mfunciones_dashboard->ObtenerListaOficial_generico($filtro_oficial);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrOficial);
            
            if (isset($arrOficial[0])) 
            {
                foreach ($arrOficial as $key => $value) 
                {
                    $nombres_filtro .= $separador . $value["ejecutivo_nombre"] . ((int)$value["usuario_activo"]==1 ? '' : ' (Inactivo)');
                }
            }
            
            $nombres_filtro .= '<br />';
        }
        
        if($campoFiltroFechaDesde != '' || $campoFiltroFechaHasta != '')
        {
            $nombres_filtro .= '<b>*Fecha de Check Visita: </b> ';
            
            if($this->mfunciones_generales->VerificaFechaD_M_Y($campoFiltroFechaDesde))
            {
                $nombres_filtro .= ' <i>Desde el:</i> ' . $campoFiltroFechaDesde;
            }

            if($this->mfunciones_generales->VerificaFechaD_M_Y($campoFiltroFechaHasta))
            {
                $nombres_filtro .= ' <i>Hasta el:</i> ' . $campoFiltroFechaHasta;
            }

            $nombres_filtro .= '<br />';
        }
        
        $nombres_filtro = str_replace(': </b> ' . $separador, ': </b> ', $nombres_filtro);
        
        return $nombres_filtro;
    }
    
    public function SeguimientoForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_dashboard');
                
        if(!isset($_POST['sel_oficial']))
        {
            js_error_div_javascript($this->lang->line('TablaNoResultadosBusqueda'));
            exit();
        }
        // Se capturan los valores

        // Se captura los valores
        $sel_departamento = $this->input->post('sel_departamento', TRUE);
        $sel_agencia = $this->input->post('sel_agencia', TRUE);
        $sel_oficial = $this->input->post('sel_oficial', TRUE);
        $campoFiltroFechaDesde = $this->input->post('campoFiltroFechaDesde', TRUE);
        $campoFiltroFechaHasta = $this->input->post('campoFiltroFechaHasta', TRUE);

        $estado = preg_replace('/[^a-zA-Z0-9_ .]/s', '', $this->input->post('estado', TRUE));

        $tipo_registro = (int)$this->input->post('tipo_registro', TRUE);
        $formato_reporte = (int)$this->input->post('formato_reporte', TRUE);

        // Validaciones
        
        if($campoFiltroFechaDesde == '' || $campoFiltroFechaHasta == '')
        {
            js_error_div_javascript($this->lang->line('ejecutivo_seguimiento_fecha_error'));
            exit();
        }
        
        if($tipo_registro != 99 && $tipo_registro != 1 && $tipo_registro != 2)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        if($formato_reporte<1 || $formato_reporte>4)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        if($estado<0 || $estado>2)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        if($sel_agencia == '' && $sel_oficial == '')
        {
            js_error_div_javascript($this->lang->line('ejecutivo_seguimiento_filtro_error'));
            exit();
        }
        
        $filtro = $this->mfunciones_dashboard->ArmarFiltro_tracking($sel_departamento, $sel_agencia, $sel_oficial, $campoFiltroFechaDesde, $campoFiltroFechaHasta, $estado);

        $valoresFiltro = $this->ArmarValoresFiltro($sel_departamento, $sel_agencia, $sel_oficial, $campoFiltroFechaDesde, $campoFiltroFechaHasta, $tipo_registro, $estado);
        
        $arrResultado = $this->mfunciones_logica->SeguimientoVisitasEjecutivo($filtro, $tipo_registro);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $this->load->model('mfunciones_microcreditos');
            
            $i = 0;

            foreach ($arrResultado as $key => $value) 
            {
                if(!preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $value["checkin_geo"]))
                {
                    continue;
                }
                
                $item_valor = array(
                    "tipo_visita_codigo" => $value["tipo"],
                    "codigo" => $value["codigo"],
                    "agencia_id" => $value["agencia_id"],
                    "agencia_nombre" => $value["agencia_nombre"],
                    "agencia_estado" => $value["agencia_estado"],
                    "codigo_rubro" => $this->mfunciones_microcreditos->GetValorCatalogo($value["codigo_rubro"], 'aux_rubro'),
                    "codigo_rubro_codigo" => $value["codigo_rubro"],
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "ejecutivo_nombre" => $value["ejecutivo_nombre"],
                    "usuario_activo" => $value["usuario_activo"],
                    "solicitante" => $value["solicitante"],
                    "actividad" => (str_replace(' ', '', $value["actividad"])=='' ? 'Actividad no registrada' : $value["actividad"]),
                    "fecha_registro" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["fecha_registro"]),
                    "fecha_checkin" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["fecha_checkin"]),
                    "checkin_geo" => $value["checkin_geo"]
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            js_error_div_javascript($this->lang->line('TablaNoResultadosBusqueda'));
            exit();
        }

        $valoresFiltro .= '<b>*Registros encontrados: </b> ' . number_format(count($lst_resultado), 0, '.', ',');

        $_SESSION['arrResultadoSeguimiento'] = $lst_resultado;
        $_SESSION['arrResultadoSeguimiento_filtro'] = $valoresFiltro;
        $_SESSION['arrResultadoSeguimiento_formato_reporte'] = $formato_reporte;

        $data['valoresFiltro'] = '<b>Registros encontrados con los filtros seleccionados: </b> ' . number_format(count($lst_resultado), 0, '.', ',');
        
        // Formato Reporte      1=Tabla     2=Tabla con Paginación      3=Mapa
        if($formato_reporte == 3 || $formato_reporte == 4)
        {
            $this->load->view('ejecutivos_cuenta/view_seguimiento_resultado_mapa', $data);
        }
        else
        {
            $data['formato_reporte'] = $formato_reporte;
            $this->load->view('ejecutivos_cuenta/view_seguimiento_resultado_tabla', $data);
        }
    }
    
    public function SeguimientoForm_Excel() {
		
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');

        $this->load->view('ejecutivos_cuenta/view_seguimiento_excel');
    }    
    
    public function Seguimiento_Mapa() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('googlemaps');
        
        if (!isset($_SESSION['arrResultadoSeguimiento'][0])) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            foreach ($_SESSION['arrResultadoSeguimiento'] as $key => $value) 
            {
                switch ((int)$value['tipo_visita_codigo']) {
                    case 1:

                        // Estudio de Crédito
                        $tipo_visita = MARCADOR_PROSPECTO;
                        $codigo_visita = PREFIJO_PROSPECTO . $value['codigo'];
                        
                        break;
                    
                    case 2:

                        // Solicitud de Crédito
                        $tipo_visita = MARCADOR_MANTENIMIENTO;
                        $codigo_visita = 'SOL_' . $value['codigo'];
                        
                        break;

                    default:
                        
                        $tipo_visita = MARCADOR_PROSPECTO;
                        
                        break;
                }
                
                $coordenadas = $value['checkin_geo'];
                
                //Marcadores 
                if(preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $coordenadas))
                {
                    $marker = array();
                    $marker['position'] = $coordenadas;
                    $marker['infowindow_content'] = '<div style="text-align: center; max-width: 350px;"> <img src="' . MARCADOR_LOGO . '" style="width: 30px;" align="top" /> <span style="font-size: 20px; font-weight: bold;"> ' . $value['solicitante'] . ' </span> <br /> <div style="text-align: center;"> ' . $value['actividad'] . ' </div> <ul style="text-align: justify;"><li><b>Cliente:</b> ' . $codigo_visita . '</li><li><b>Rubro:</b> ' . $value['codigo_rubro'] . '</li><li><b>Agencia Asociada:</b> ' . $value['agencia_nombre'] . ((int)$value["agencia_estado"]==1 ? '' : ' (Cerrada)') . '</li><li><b>Oficial de Negocios:</b> ' . $value['ejecutivo_nombre'] . ((int)$value["usuario_activo"]==1 ? '' : ' (Inactivo)') . '</li><li><b>Fecha Registro:</b> ' . $value['fecha_registro'] . '</li><li><b>Fecha Check Visita:</b> ' . $value['fecha_checkin'] . '</li></ul> </div>';
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