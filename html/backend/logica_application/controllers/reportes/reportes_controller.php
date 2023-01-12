<?php
/**
 * @file 
 * Codigo que implementa el controlador para la gestión de Reportes
 * @brief REPORTES GERENCIALES DEL SISTEMA
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para la gestión de Reportes
 * @brief REPORTES GERENCIALES DEL SISTEMA
 * @class Conf_catalogo_controller 
 */
class Reportes_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 23;
        
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
        $this->load->view('reportes/view_reportes_ver', $data);
    }

    private function GeneraPDF($vista, $datos) {
        $this->lang->load('general', 'castellano');
        // Ajusta el limite de memoria, puede no ser requerido según instalación del servidor
        ini_set("memory_limit",'128M');
        $html = $this->load->view($vista,$datos,true);
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        
        $reporte_generado = 'Reporte Generado ' . date('d/m/Y H:i') . ' (' . $_SESSION["session_informacion"]["login"] . ')';
        
        $header = array (
            'odd' => array (
                'L' => array (
                    'content' => $this->lang->line('ReporteTituloIzquierda'),
                    'font-size' => 8,
                    'font-style' => '',
                    'font-family' => 'Arial',
                    'color'=>'#000000'
                ),
                'C' => array (
                    'content' => $this->lang->line('ReporteTituloCentro'),
                    'font-size' => 8,
                    'font-style' => '',
                    'font-family' => 'Arial',
                    'color'=>'#000000'
                ),
                'R' => array (
                    'content' => $this->lang->line('ReporteTituloDerecha'),
                    'font-size' => 8,
                    'font-style' => '',
                    'font-family' => 'Arial',
                    'color'=>'#000000'
                ),
                'line' => 1,
            ),
            'even' => array ()
        );
        
        $pdf->SetHeader($header);
        
        $pdf->SetHTMLFooter('<table border="0" style="text-align: right; font-family: \'Open Sans\', Arial, sans-serif; font-size: 11px; width: 100%"><tr><td align="left"> ' . $reporte_generado . ' </td><td>Página {PAGENO} de {nb}</td></td></table>');
        $pdf->WriteHTML($html);
        
        /*        
        I: send the file inline to the browser. The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
        D: send to the browser and force a file download with the name given by name.
        F: save to a local file with the name given by name (may include a path).
        S: return the document as a string. name is ignored.
        */
        $pdf->Output('reporte_initium_' . date('Ymd_His') . '.pdf', 'I');
    }

    private function GeneraExcel($vista, $datos) {
//        ini_set('display_errors', 1);
//        ini_set('display_startup_errors', 1);
//        error_reporting(E_ALL);
//        ini_set("memory_limit",'32M');
//        $tmpFile = tempnam("/tmp","tmp".microtime()."xls");
//        file_put_contents($tmpFile,$this->load->view($vista,$datos,true));
//        $this->load->library("Excel");
//        $reader = PHPExcel_IOFactory::createReader("HTML");
//        $objPHPExcel = $reader->load($tmpFile);
//        $objPHPExcel->getActiveSheet()->setTitle("Reporte_INITIUM");
//        unlink($tmpFile);
//        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header('Content-Disposition: attachment;filename=reporte_senaf_' . date('Ymd_His') . '.xlsx');
//        header('Cache-Control: max-age=0');
//                
//        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
//        $writer->save("php://output");
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=reporte_initium_' . date('Ymd_His') . '.xls');
        header('Cache-Control: max-age=0');
        
        echo $this->load->view($vista,$datos,true);
        
    }

    public function Reportes_Generar() {
        $this->lang->load('general', 'castellano'); // Archivo de Lenguaje
        $this->load->model('mfunciones_generales'); // Funciones Generales
        $this->load->model('mfunciones_logica');    // Capa de Datos
        $this->load->model('mfunciones_reporte');   // Capa de Datos Reporte
        $parametros = json_decode(urldecode($this->input->get_post("parametros")));
        
        if (isset($parametros->tipo) && $parametros->tipo=="campana") {
            $resultado = $this->mfunciones_reporte->Generar_Consulta_Campana($parametros);
            if ($resultado == 'vacio') die($this->lang->line('TablaNoResultadosReporte'));
            
            // Listado de las Etapas
            $arrEtapas = $this->mfunciones_logica->ObtenerDatosFlujo(-1, 1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEtapas);
            
            if (property_exists($parametros,"exportar") && $parametros->exportar == "pdf") {
                $this->mfunciones_generales->GeneraPDF('reportes/view_reportes_campana_plain',array("resultado"=>$resultado, "parametros"=>$parametros, "arrEtapas"=>$arrEtapas), 'L');
                return;
            } else if (property_exists($parametros,"exportar") && $parametros->exportar == "excel") {
                $this->GeneraExcel('reportes/view_reportes_campana_plain',array("resultado"=>$resultado, "parametros"=>$parametros, "arrEtapas"=>$arrEtapas));
                return;
            }
            $this->load->view('reportes/view_reportes_campana',array("resultado"=>$resultado, "parametros"=>$parametros, "arrEtapas"=>$arrEtapas));
            return;
        }
        
        if (isset($parametros->tipo) && $parametros->tipo=="pivot") {
            $resultado = $this->mfunciones_reporte->Generar_Consulta_Pivot($parametros);
            if ($resultado->sin_registros) die($this->lang->line('TablaNoResultadosReporte'));
            if (property_exists($parametros,"exportar") && $parametros->exportar == "pdf") {
                $this->mfunciones_generales->GeneraPDF('reportes/view_reportes_tabla_pivot_plain',array("resultado"=>$resultado, "parametros"=>$parametros));
                return;
            } else if (property_exists($parametros,"exportar") && $parametros->exportar == "excel") {
                $this->GeneraExcel('reportes/view_reportes_tabla_pivot_plain',array("resultado"=>$resultado, "parametros"=>$parametros));
                return;
            }
            $this->load->view('reportes/view_reportes_tabla_pivot',array("resultado"=>$resultado, "parametros"=>$parametros));
            return;
        }
        
        if (isset($parametros->tipo) && $parametros->tipo=="documentos") {
            $resultado = $this->mfunciones_reporte->Generar_Consulta_Documentos($parametros);
            if ($resultado->sin_registros) die($this->lang->line('TablaNoResultadosReporte'));
            if (property_exists($parametros,"exportar") && $parametros->exportar == "pdf") {
                $this->mfunciones_generales->GeneraPDF('reportes/view_reportes_tabla_documentos_plain',array("resultado"=>$resultado, "parametros"=>$parametros));
                return;
            } else if (property_exists($parametros,"exportar") && $parametros->exportar == "excel") {
                $this->GeneraExcel('reportes/view_reportes_tabla_documentos_plain',array("resultado"=>$resultado, "parametros"=>$parametros));
                return;
            }
            $this->load->view('reportes/view_reportes_tabla_documentos',array("resultado"=>$resultado, "parametros"=>$parametros));
            return;
        }
        $resultado = $this->mfunciones_reporte->Generar_Consulta($parametros);
        
        if ($resultado->error) die("<div class='mensajeBD'> <br /> <i class='fa fa-meh-o' aria-hidden='true'></i> " . $resultado->mensaje_error . "<br /><br /></div>");
        
        if ($resultado->sin_registros) die($this->lang->line('TablaNoResultadosReporte'));
        if (isset($parametros->vista)&& $parametros->vista=="grafica") {
            $this->load->view('reportes/view_reportes_grafico',array("resultado"=>$resultado));
        } else {
            if (property_exists($parametros,"exportar") && $parametros->exportar == "pdf") {
                $this->mfunciones_generales->GeneraPDF('reportes/view_reportes_tabla_plain',array("resultado"=>$resultado, "parametros"=>$parametros));
                return;
            }else if (property_exists($parametros,"exportar") && $parametros->exportar == "excel") {
                $this->GeneraExcel('reportes/view_reportes_tabla_plain',array("resultado"=>$resultado, "parametros"=>$parametros));
                return;
            }
            $this->load->view('reportes/view_reportes_tabla',array("resultado"=>$resultado,"parametros"=>$parametros, "parametros"=>$parametros));
        }
    }

    public function Reportes_Agregar_Filtro() {
        $this->lang->load('general', 'castellano'); // Archivo de Lenguaje
        $this->load->model('mfunciones_generales'); // Funciones Generales
        $this->load->model('mfunciones_logica');    // Capa de Datos
        $this->load->model('mfunciones_reporte');   // Capa de Datos Reporte
        $campos = $this->mfunciones_reporte->Obtener_Campos_Filtro_Prospecto();
        $this->load->view('reportes/view_reportes_agregar_filtro',array("campos"=>$campos));
    }

    public function Reportes_Valores_Filtro() {
        $this->lang->load('general', 'castellano'); // Archivo de Lenguaje
        $this->load->model('mfunciones_generales'); // Funciones Generales
        $this->load->model('mfunciones_logica');    // Capa de Datos
        $this->load->model('mfunciones_reporte');   // Capa de Datos Reporte
        die(json_encode($this->mfunciones_reporte->Obtener_Valores_Filtro($this->input->get_post("campo"))));
    }
}
?>