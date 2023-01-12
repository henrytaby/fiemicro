<?php
/**
 * @file 
 * Codigo que implementa el controlador para la gestión de Consultas
 * @brief CONSULTAS Y SEGUIMIENTO DEL SISTEMA
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para la gestión de Reportes
 * @brief DASHBOARD REPORTES GERENCIALES DEL SISTEMA
 * @class Conf_catalogo_controller 
 */
class Dashboard_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 50;
        
    public function __construct() {
        parent::__construct();
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        $this->load->model('mfunciones_dashboard');
    }

    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2022
     */

    public function Reportes_Ver() {
        
        $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        
        $data['nombre_region'] = $lista_region->region_nombres_texto;
        
        $this->load->view('dashboard/view_dashboard_ver', $data);
    }
    
    public function PoblarLista()  {
        
        // Se captura los valores
        $tipo = filter_var($this->input->post('tipo', TRUE), FILTER_SANITIZE_STRING);
        $opciones = $this->input->post('opciones', TRUE);
        $aux = filter_var($this->input->post('aux', TRUE), FILTER_SANITIZE_STRING);
        
        if($tipo == 'departamento')
        {
            $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
            
            $arrResultado = $this->mfunciones_dashboard->ObtenerDatosDepartamento_dashboard($lista_region->region_id);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;
                
                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "value" => ($value["dep"] == 'LPALTO' ? '115' : $value["dep"]),
                        "label" => ($value["dep"] == 'LPALTO' ? 'LA PAZ - EL ALTO' : $this->mfunciones_generales->GetValorCatalogoDB($value["dep"], 'dir_departamento'))
                    );
                    $lst_resultado[$i] = $item_valor;
                    
                    $i++;
                }
                
                echo json_encode($lst_resultado);
                return;
            }
            else
            {
                echo json_encode(array());
                return;
            }
        }
        
        // Registro de opciones
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($opciones);

        if (!isset($opciones[0])) 
        {
            echo json_encode(array());
            return;
        }
        
        if($tipo == 'agencia')
        {
            $flag_elalto = 0;
            
            foreach ($opciones as $key => $value) 
            {
                switch ((int)$value) {

                    case 115:
                        $flag_elalto = 1;
                        $aux_criterio = "";
                        break;

                    default:
                        $aux_criterio = "'" . $value . "'";
                        break;
                }

                $value = $aux_criterio;

                if($aux_criterio != '')
                {
                    $criterio .= $value . ',';
                }
            }

            $criterio = rtrim($criterio, ',');
        }
        
        $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        
        $lst_resultado = array();
        
        $i = 0;
        
        switch ($tipo) {
            case 'agencia':
                
                // Se valida que existan criterios para realizar la búsqueda
                if($criterio != '')
                {
                    // Listado de REGIONES (Agencias)
                    $arrResultado = $this->mfunciones_dashboard->ObtenerDatosRegional_dashboard($criterio, 'dir_departamento', $lista_region->region_id, $aux);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                    
                    if (isset($arrResultado[0])) 
                    {
                        foreach ($arrResultado as $key => $value) 
                        {
                            $item_valor = array(
                                "catalogo_padre" => ((int)$value["estructura_regional_ciudad"]==115 ? 'LA PAZ - EL ALTO' : $this->mfunciones_generales->GetValorCatalogoDB($value["estructura_regional_departamento"], 'dir_departamento')),
                                "catalogo_codigo" => $value["estructura_regional_id"],
                                "catalogo_descripcion" => $value["estructura_regional_nombre"],
                            );
                            $lst_resultado[$i] = $item_valor;

                            $i++;
                        }
                    }
                }
                
                // Se valida si el flag de ciudad El Alto está activado para realizar la búsquedas sólo de esta ciudad
                if($flag_elalto == 1)
                {
                    // Listado de REGIONES (Agencias)
                    $arrResultado = $this->mfunciones_dashboard->ObtenerDatosRegional_dashboard('115', 'dir_localidad_ciudad', $lista_region->region_id, $aux);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                    
                    if (isset($arrResultado[0])) 
                    {
                        foreach ($arrResultado as $key => $value) 
                        {
                            $item_valor = array(
                                "catalogo_padre" => 'LA PAZ - EL ALTO',
                                "catalogo_codigo" => $value["estructura_regional_id"],
                                "catalogo_descripcion" => $value["estructura_regional_nombre"],
                            );
                            $lst_resultado[$i] = $item_valor;

                            $i++;
                        }
                    }
                }

                break;

            case 'oficial':
                
                $aux_array = implode (", ", $opciones);
                
                // Listado de Oficiales de Negocio por Agencias
                $arrResultado = $this->mfunciones_dashboard->ObtenerDatosOficial_dashboard($aux_array, $lista_region->region_id, $aux);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "catalogo_padre" => $value["estructura_regional_nombre"],
                            "catalogo_codigo" => $value["ejecutivo_id"],
                            "catalogo_descripcion" => $value["ejecutivo_nombre"],
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }
                
                break;
            
            default:
                
                echo json_encode(array());
                return;
                
                break;
        }
        
        // Si no se encontraron valores, se devuelve vacío
        if (!isset($lst_resultado[0]))
        {
            echo json_encode(array());
            return;
        }
        
        $orden_padre = $this->mfunciones_generales->ArrGroupBy($lst_resultado, 'catalogo_padre');
        
        $j = 0;
        
        foreach ($orden_padre as $key => $orden)
        {
            $k = 0;
            $aux_opciones = array();
            foreach ($orden_padre[$key] as $key2 => $orden2)
            {
                $aux_opciones[$k] = array(
                    'label' => $orden2['catalogo_descripcion'],
                    'value' => $orden2['catalogo_codigo']
                );
                
                $k++;
            }
            
            $lista[$j] = array(
                'label' => $key,
                'options' => $aux_opciones
            );
            
            $j++;
        }
        
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($lista);
        
        echo json_encode($lista);
    }
    
    private function ArmarValoresFiltro($sel_departamento, $sel_agencia, $sel_oficial, $campoFiltroFechaDesde, $campoFiltroFechaHasta)
    {
        $nombres_filtro = '';
        
        $separador = '<b> ó </b> ';
        
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
            
            $arrAgencia = $this->mfunciones_dashboard->ObtenerListaAgencia($filtro_agencia);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrAgencia);
            
            if (isset($arrAgencia[0])) 
            {
                foreach ($arrAgencia as $key => $value) 
                {
                    $nombres_filtro .= $separador . $value["estructura_regional_nombre"];
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
            
            $arrOficial = $this->mfunciones_dashboard->ObtenerListaOficial($filtro_oficial);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrOficial);
            
            if (isset($arrOficial[0])) 
            {
                foreach ($arrOficial as $key => $value) 
                {
                    $nombres_filtro .= $separador . $value["ejecutivo_nombre"];
                }
            }
            
            $nombres_filtro .= '<br />';
        }
        
        if($campoFiltroFechaDesde != '' || $campoFiltroFechaHasta != '')
        {
            $nombres_filtro .= '<b>*Fecha de Asignación: </b> ';
            
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
    
    public function ReporteFunnel()  {
        
        // Se captura los valores
        $sel_departamento = $this->input->post('sel_departamento', TRUE);
        $sel_agencia = $this->input->post('sel_agencia', TRUE);
        $sel_oficial = $this->input->post('sel_oficial', TRUE);
        $campoFiltroFechaDesde = $this->input->post('campoFiltroFechaDesde', TRUE);
        $campoFiltroFechaHasta = $this->input->post('campoFiltroFechaHasta', TRUE);
        
        $filtro = $this->mfunciones_dashboard->ArmarFiltro($sel_departamento, $sel_agencia, $sel_oficial, $campoFiltroFechaDesde, $campoFiltroFechaHasta);
        
        $resultado = $this->mfunciones_dashboard->GenerarFunnel($filtro);
        
        $data['funnel'] = $resultado->funnel;
        $data['chartFunnel'] = $resultado->chartFunnel;
        
        $this->load->view('dashboard/view_dashboard_resultado', $data);
    }
    
    public function ReporteFunnel_Tabla()  {
        
        // Se captura los valores
        $sel_departamento = $this->input->post('sel_departamento', TRUE);
        $sel_agencia = $this->input->post('sel_agencia', TRUE);
        $sel_oficial = $this->input->post('sel_oficial', TRUE);
        $campoFiltroFechaDesde = $this->input->post('campoFiltroFechaDesde', TRUE);
        $campoFiltroFechaHasta = $this->input->post('campoFiltroFechaHasta', TRUE);
        
        $ValoresFiltro = $this->ArmarValoresFiltro($sel_departamento, $sel_agencia, $sel_oficial, $campoFiltroFechaDesde, $campoFiltroFechaHasta);
        
        $etapa = str_replace('&', '', $this->input->post('etapa', TRUE));
        
        $tipo = str_replace('&', '', $this->input->post('tipo', TRUE));
        
        $etapa_nombre = '';
        
        switch ((int)$etapa) {
            case -1:

                $etapa = '2, 3, 4, 5';
                $etapa_nombre = 'EN PROCESO';

                break;

            case 23:

                $etapa_nombre = 'RECHAZADAS';

                break;
            
            default:
                
                $arrEtapas = $this->mfunciones_logica->ObtenerDatosFlujo($etapa, 1);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEtapas);

                if (isset($arrEtapas[0])) 
                {
                    $etapa_nombre = $arrEtapas[0]['etapa_nombre'];
                }

                if((int)$etapa == 22 || (int)$etapa == 6)
                {
                    $etapa = '6, 22';
                }
                
                break;
        }
        
        $filtro = 'AND etapa_id IN(' . $etapa . ')' . $this->mfunciones_dashboard->ArmarFiltro($sel_departamento, $sel_agencia, $sel_oficial, $campoFiltroFechaDesde, $campoFiltroFechaHasta);
        
        $lst_resultado = $this->mfunciones_dashboard->GenerarFunnelTabla($filtro);
        
        $vista = 'dashboard/view_dashboard_tabla';
        
        switch ($tipo) {
            case 'tabla':
                
                $data['arrRespuesta'] = $lst_resultado;
                
                $data['etapa_nombre'] = $etapa_nombre;
                
                $data['etapa_id'] = str_replace('&', '', $this->input->post('etapa', TRUE));
                
                $this->load->view($vista, $data);

                break;
            
            case 'pdf':

                $this->mfunciones_generales->GeneraPDF($vista . '_plain',array("arrRespuesta"=>$lst_resultado, "ValoresFiltro"=>$ValoresFiltro, "etapa_nombre"=>$etapa_nombre), 'L');
                return;

                break;

            case 'excel':

                $this->mfunciones_generales->GeneraExcel($vista . '_plain',array("arrRespuesta"=>$lst_resultado, "ValoresFiltro"=>$ValoresFiltro, "etapa_nombre"=>$etapa_nombre));
                return;

                break;
            
            default:
                
                js_error_div_javascript($this->lang->line('TablaNoResultadosDashboard'));
                
                break;
        }
    }
}
?>