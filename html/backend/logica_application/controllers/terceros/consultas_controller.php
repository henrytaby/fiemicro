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
 * @brief REPORTES GERENCIALES DEL SISTEMA
 * @class Conf_catalogo_controller 
 */
class Consultas_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 45;
        
    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */

    public function Reportes_Ver() {
        $this->lang->load('general', 'castellano'); // Archivo de Lenguaje
        $this->load->model('mfunciones_generales'); // Funciones Generales
        $this->load->model('mfunciones_logica');    // Capa de Datos
        $this->load->model('mfunciones_reporte');   // Capa de Datos Reporte
        
        $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        
        $data['nombre_region'] = $lista_region->region_nombres_texto;
        
        $this->load->view('terceros/view_consultas_ver', $data);
    }

    public function Reportes_Generar() {
        $this->lang->load('general', 'castellano'); // Archivo de Lenguaje
        $this->load->model('mfunciones_generales'); // Funciones Generales
        $this->load->model('mfunciones_logica');    // Capa de Datos
        $this->load->model('mfunciones_reporte');   // Capa de Datos Reporte
        $parametros = json_decode(urldecode($this->input->get_post("parametros")));
        
        if (isset($parametros->tipo) && $parametros->tipo=="registros") {
            $resultado = $this->mfunciones_reporte->Generar_Consulta_Onboarding($parametros);
            
            if ($resultado->error) die("<div class='mensajeBD'> <br /> <i class='fa fa-meh-o' aria-hidden='true'></i> " . $resultado->mensaje_error . "<br /><br /></div>");
            
            if ($resultado->sin_registros) die($this->lang->line('TablaNoResultadosReporte'));
            if (property_exists($parametros,"exportar") && $parametros->exportar == "pdf") {
                $this->mfunciones_generales->GeneraPDF('terceros/view_consultas_tabla_registros_plain',array("resultado"=>$resultado, "parametros"=>$parametros));
                return;
            } else if (property_exists($parametros,"exportar") && $parametros->exportar == "excel") {
                $this->mfunciones_generales->GeneraExcel('terceros/view_consultas_tabla_registros_plain',array("resultado"=>$resultado, "parametros"=>$parametros));
                return;
            }
            $this->load->view('terceros/view_consultas_tabla_registros',array("resultado"=>$resultado, "parametros"=>$parametros));
            return;
        }
        
        if (isset($parametros->tipo) && $parametros->tipo=="pivot") {
            $resultado = $this->mfunciones_reporte->Generar_Consulta_Onboarding($parametros, 'pivot');
            
            if ($resultado->error) die("<div class='mensajeBD'> <br /> <i class='fa fa-meh-o' aria-hidden='true'></i> " . $resultado->mensaje_error . "<br /><br /></div>");
            
            if ($resultado->sin_registros) die($this->lang->line('TablaNoResultadosReporte'));
            if (property_exists($parametros,"exportar") && $parametros->exportar == "pdf") {
                $this->mfunciones_generales->GeneraPDF('terceros/view_reportes_tabla_pivot_plain',array("resultado"=>$resultado, "parametros"=>$parametros), 'L');
                return;
            } else if (property_exists($parametros,"exportar") && $parametros->exportar == "excel") {
                $this->mfunciones_generales->GeneraExcel('terceros/view_reportes_tabla_pivot_plain',array("resultado"=>$resultado, "parametros"=>$parametros));
                return;
            }
            $this->load->view('terceros/view_reportes_tabla_pivot',array("resultado"=>$resultado, "parametros"=>$parametros));
            return;
        }
    }

    public function Reportes_Agregar_Filtro_Onboarding() {
        $this->lang->load('general', 'castellano'); // Archivo de Lenguaje
        $this->load->model('mfunciones_generales'); // Funciones Generales
        $this->load->model('mfunciones_logica');    // Capa de Datos
        $this->load->model('mfunciones_reporte');   // Capa de Datos Reporte
        $campos = $this->mfunciones_reporte->Obtener_Campos_Filtro_Onboarding();
        $this->load->view('terceros/view_consultas_agregar_filtro_registro',array("campos"=>$campos));
    }

    public function Reportes_Valores_Filtro() {
        $this->lang->load('general', 'castellano'); // Archivo de Lenguaje
        $this->load->model('mfunciones_generales'); // Funciones Generales
        $this->load->model('mfunciones_logica');    // Capa de Datos
        $this->load->model('mfunciones_reporte');   // Capa de Datos Reporte
        die(json_encode($this->mfunciones_reporte->Obtener_Valores_Filtro($this->input->get_post("campo"), $this->input->get_post("perfil_app"))));
    }
}
?>