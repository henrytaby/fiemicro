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
	protected $codigo_menu_acceso = 30;
        
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
        $data['opciones_grupo'] = $this->mfunciones_reporte->Obtener_Opciones_Grupo();
        $data['opciones_funcion_mostrar'] = $this->mfunciones_reporte->Obtener_Opciones_Funcion_Mostrar();
        
        $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        
        $data['nombre_region'] = $lista_region->region_nombres_texto;
        
        // Listado de los Perfiles App
        $arrPerfilApp = $this->mfunciones_logica->ObtenerListaPerfilAppActivo(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrPerfilApp);
        $data["arrPerfilApp"] = $arrPerfilApp;
        
        $_SESSION['aux_flag_reporte_return'] = 1;
        
        $this->load->view('consultas/view_consultas_ver', $data);
    }

    public function Reportes_Generar() {
        $this->lang->load('general', 'castellano'); // Archivo de Lenguaje
        $this->load->model('mfunciones_generales'); // Funciones Generales
        $this->load->model('mfunciones_logica');    // Capa de Datos
        $this->load->model('mfunciones_reporte');   // Capa de Datos Reporte
        $parametros = json_decode(urldecode($this->input->get_post("parametros")));
        
        if (isset($parametros->tipo) && $parametros->tipo=="prospecto") {
            $resultado = $this->mfunciones_reporte->Generar_Consulta_Prospectos($parametros, $parametros->opcionResultado);
            
            if ($resultado->error) die("<div class='mensajeBD'> <br /> <i class='fa fa-meh-o' aria-hidden='true'></i> " . $resultado->mensaje_error . "<br /><br /></div>");
            
            $vista = ($parametros->opcionResultado == 'pivot' ? 'view_reportes_tabla_pivot' : 'view_consultas_tabla_prospecto');
            $orientacion = 'L';//($parametros->opcionResultado == 'pivot' ? 'L' : 'P');
            
            if ($resultado->sin_registros) die($this->lang->line('TablaNoResultadosReporte'));
            if (property_exists($parametros,"exportar") && $parametros->exportar == "pdf") {
                $this->mfunciones_generales->GeneraPDF('consultas/' . $vista . '_plain',array("resultado"=>$resultado, "parametros"=>$parametros), $orientacion);
                return;
            } else if (property_exists($parametros,"exportar") && $parametros->exportar == "excel") {
                $this->mfunciones_generales->GeneraExcel('consultas/' . $vista . '_plain',array("resultado"=>$resultado, "parametros"=>$parametros));
                return;
            }
            $this->load->view('consultas/' . $vista,array("resultado"=>$resultado, "parametros"=>$parametros));
            return;
        }
        
        if (isset($parametros->tipo) && $parametros->tipo=="mantenimiento") {
            $resultado = $this->mfunciones_reporte->Generar_Consulta_Mantenimiento($parametros);
            if ($resultado->sin_registros) die($this->lang->line('TablaNoResultadosReporte'));
            if (property_exists($parametros,"exportar") && $parametros->exportar == "pdf") {
                $this->mfunciones_generales->GeneraPDF('consultas/view_consultas_tabla_mantenimiento_plain',array("resultado"=>$resultado, "parametros"=>$parametros));
                return;
            } else if (property_exists($parametros,"exportar") && $parametros->exportar == "excel") {
                $this->mfunciones_generales->GeneraExcel('consultas/view_consultas_tabla_mantenimiento_plain',array("resultado"=>$resultado, "parametros"=>$parametros));
                return;
            }
            $this->load->view('consultas/view_consultas_tabla_mantenimiento',array("resultado"=>$resultado, "parametros"=>$parametros));
            return;
        }
        
        if (isset($parametros->tipo) && $parametros->tipo=="solcred") {
            $resultado = $this->mfunciones_reporte->Generar_Consulta_SolCred($parametros, $parametros->opcionResultado);
            
            if ($resultado->error) die("<div class='mensajeBD'> <br /> <i class='fa fa-meh-o' aria-hidden='true'></i> " . $resultado->mensaje_error . "<br /><br /></div>");
            
            $vista = ($parametros->opcionResultado == 'pivot' ? 'view_reportes_tabla_pivot' : 'view_consultas_tabla_solcred');
            $orientacion = 'L';//($parametros->opcionResultado == 'pivot' ? 'L' : 'P');
            
            if ($resultado->sin_registros) die($this->lang->line('TablaNoResultadosReporte'));
            if (property_exists($parametros,"exportar") && $parametros->exportar == "pdf") {
                $this->mfunciones_generales->GeneraPDF('solicitud_credito/' . $vista . '_plain',array("resultado"=>$resultado, "parametros"=>$parametros), $orientacion);
                return;
            } else if (property_exists($parametros,"exportar") && $parametros->exportar == "excel") {
                $this->mfunciones_generales->GeneraExcel('solicitud_credito/' . $vista . '_plain',array("resultado"=>$resultado, "parametros"=>$parametros));
                return;
            }
            
            $this->load->view('solicitud_credito/' . $vista,array("resultado"=>$resultado, "parametros"=>$parametros));
            return;
        }
        
    }

    public function Reportes_Agregar_Filtro_Prospecto() {
        $this->lang->load('general', 'castellano'); // Archivo de Lenguaje
        $this->load->model('mfunciones_generales'); // Funciones Generales
        $this->load->model('mfunciones_logica');    // Capa de Datos
        $this->load->model('mfunciones_reporte');   // Capa de Datos Reporte
        $campos = $this->mfunciones_reporte->Obtener_Campos_Filtro_Prospecto();
        $this->load->view('consultas/view_consultas_agregar_filtro_prospecto',array("campos"=>$campos));
    }
    
    public function Reportes_Agregar_Filtro_Mantenimiento() {
        $this->lang->load('general', 'castellano'); // Archivo de Lenguaje
        $this->load->model('mfunciones_generales'); // Funciones Generales
        $this->load->model('mfunciones_logica');    // Capa de Datos
        $this->load->model('mfunciones_reporte');   // Capa de Datos Reporte
        $campos = $this->mfunciones_reporte->Obtener_Campos_Filtro_Mantenimiento();
        $this->load->view('consultas/view_consultas_agregar_filtro_mantenimiento',array("campos"=>$campos));
    }
    
    public function Reportes_Agregar_Filtro_SolCred() {
        $this->lang->load('general', 'castellano'); // Archivo de Lenguaje
        $this->load->model('mfunciones_generales'); // Funciones Generales
        $this->load->model('mfunciones_logica');    // Capa de Datos
        $this->load->model('mfunciones_reporte');   // Capa de Datos Reporte
        $campos = $this->mfunciones_reporte->Obtener_Campos_Filtro_SolCred();
        $this->load->view('consultas/view_consultas_agregar_filtro_prospecto',array("campos"=>$campos));
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