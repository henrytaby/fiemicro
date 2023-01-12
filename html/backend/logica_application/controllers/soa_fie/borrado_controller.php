<?php
/**
 * @file 
 * Codigo que implementa el controlador para la auditoría
 * @brief  CONTROLADOR DE CONFIGURACIÓN
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
class Borrado_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 48;

    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el cargado de auditoría
     * @brief CARGAR AUDITORÍA
     * @author JRAD
     * @date 2017
     */
    public function Reporte_Ver() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();

        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());

        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $data["accion_gestion"] = 'borrado';
        
        $this->load->view('soa_fie/view_auditoria_ver', $data);
    }
    
    public function Reporte_Resultado() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_soa');
        
        // Se capturan los datos
	
        $accion_gestion = preg_replace('/[^a-z]/s', '', $this->input->post('accion_gestion', TRUE));
        
        if($accion_gestion != 'borrado')
        {
            js_invocacion_javascript('$("#divResultadoReporte").html("")');
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $tipo = preg_replace('/[^a-z]/s', '', $this->input->post('tipo', TRUE));
        
        $servicio = preg_replace('/[^0-9-]/s', '', $this->input->post('servicio', TRUE));
        $respuesta = preg_replace('/[^0-9-]/s', '', $this->input->post('respuesta', TRUE));
        $validacion = preg_replace('/[^0-9-]/s', '', $this->input->post('validacion', TRUE));
        
        $fecha1_capturada = $this->input->post('fecha_inicio', TRUE);
        $fecha2_capturada = $this->input->post('fecha_fin', TRUE);
        
        $this->mfunciones_soa->Obtener_Resumen_SOA_FIE($accion_gestion, $tipo, $servicio, $respuesta, $validacion, $fecha1_capturada, $fecha2_capturada);
    }
    
    public function Auditoria_Detalle() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
	
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $codigo = preg_replace('/[^0-9-]/s', '', $this->input->post('codigo', TRUE));
        
        // Filtro del Reporte
        $arrResultado = $this->mfunciones_logica->DetalleAuditoriaSOA((int)$codigo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (!isset($arrResultado[0])) 
        {
            js_error_div_javascript('Registro inválido.');
            exit();
        }
        
        if($this->mfunciones_generales->validateJSON($arrResultado[0]['audit_params']))
        {
            $arrResultado[0]['audit_params'] = json_decode($arrResultado[0]['audit_params'], true);
        }
        
        if($this->mfunciones_generales->validateJSON($arrResultado[0]['audit_result']))
        {
            $arrResultado[0]['audit_result'] = json_decode($arrResultado[0]['audit_result'], true);
        }
        
        $data["arrResultado"] = $arrResultado;

        $this->load->view('soa_fie/view_detalle_ver', $data);
    }
    
    public function Borrar_Logs() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_soa');
        
        // Se capturan los datos
	
        $seleccion= preg_replace('/[^0-9-]/s', '', $this->input->post('seleccion', TRUE));
        
        if((int)$seleccion != 1)
        {
            js_error_div_javascript('Sólo puede proceder si marcó la opción "Si" (confirmar acción).');
            exit();
        }
        
        $fecha1_capturada = $this->input->post('fecha_inicio', TRUE);
        $fecha2_capturada = $this->input->post('fecha_fin', TRUE);
        
        if(null !== $fecha1_capturada)
        {
            $fecha_inicio = $fecha1_capturada;
            $fecha_inicio1 = $this->mfunciones_generales->getFormatoFechaDateTime($fecha_inicio . ' 00:00:01');
        }

        if(null !== $fecha2_capturada)
        {
            $fecha_fin = $fecha2_capturada;
            $fecha_fin1 = $this->mfunciones_generales->getFormatoFechaDateTime($fecha_fin . ' 23:59:59');
        }
		
        if($fecha_inicio == "" && $fecha_fin == "")
        {   
            js_error_div_javascript('Debe establecer un rango de fechas.');
            exit();
        }
		
        if($fecha_inicio != "" && $fecha_fin == "")
        {
            js_error_div_javascript($this->lang->line('FormularioFechas'));
            exit();
        }
		
        if($fecha_inicio == "" && $fecha_fin != "")
        {
            js_error_div_javascript($this->lang->line('FormularioFechas'));
            exit();
        }
        
        $filas_afectadas = $this->mfunciones_logica->BorrarAuditoriaSOA($fecha_inicio1, $fecha_fin1);
        
        $data["filas_afectadas"] = number_format($filas_afectadas, 0, '.', ',');
        
        $data["ruta_redireccion"] = 'AuditoriaSOAclear/Ver';

        $this->load->view('soa_fie/view_msj_ok', $data);
    }
    
    public function ExportarLogsCSV() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
 
        $arrResultado = $this->mfunciones_logica->ListadoLogsBorrado_AuditoriaSOA();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"initium_logs_borrados_soa_" . date('YmdHis') .".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        # Generate CSV data from array
        $fh = fopen('php://temp', 'rw'); # don't create a file, attempt
        
        if (isset($arrResultado[0]))
        {
            # write out the headers
            fputcsv($fh, array_keys(current($arrResultado)));

            # write out the data
            foreach ( $arrResultado as $row ) {
                fputcsv($fh, $row);
            }
            rewind($fh);
            $csv = stream_get_contents($fh);
        }
        else
        {
            $csv = '';
        }
        
        fclose($fh);

        print ($csv);
	exit;
    }
}
?>