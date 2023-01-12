<?php
/**
 * @file
 * Codigo que implementa el controlador para la auditoría
 * @brief  CONTROLADOR DE CONFIGURACIÓN
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
class Reporte_controller extends MY_Controller
{

    // Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
    protected $codigo_menu_acceso = 51;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * carga la vista para el cargado de auditoría
     * @brief CARGAR AUDITORÍA
     * @author JRAD
     * @date 2017
     */
    public function Reporte_Ver()
    {

        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');

        $this->formulario_logica_general->DefinicionValidacionFormulario();

        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());

        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

        $data["accion_gestion"] = 'seguimiento';

        $this->load->view('auditoria_ad/view_auditoria_ver', $data);
    }

    public function Reporte_Resultado()
    {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_activeDirectory');

        // Se capturan los datos

        $accion_gestion = preg_replace('/[^a-z]/s', '', $this->input->post('accion_gestion', true));

        if ($accion_gestion != 'seguimiento') {
            js_invocacion_javascript('$("#divResultadoReporte").html("")');
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }

        $tipo = preg_replace('/[^a-z]/s', '', $this->input->post('tipo', TRUE));
        
        $fecha1_capturada = $this->input->post('fecha_inicio', true);
        $fecha2_capturada = $this->input->post('fecha_fin', true);

        $this->mfunciones_activeDirectory->Obtener_Resumen_AD($accion_gestion, $tipo, $fecha1_capturada, $fecha2_capturada);

        
    }

    public function Auditoria_Detalle()
    {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_activeDirectory');

        // Se capturan los datos

        if (!isset($_POST['codigo'])) {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }

        $codigo = preg_replace('/[^0-9-]/s', '', $this->input->post('codigo', true));
        
        // Filtro del Reporte
        $arrResultado = $this->mfunciones_activeDirectory->DetalleAuditoriaAD((int) $codigo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);


        if (!isset($arrResultado[0])) {
            js_error_div_javascript('Registro inválido.');
            exit();
        }

        if ($this->mfunciones_generales->validateJSON($arrResultado[0]['audit_params'])) {
            $arrResultado[0]['audit_params'] = json_decode($arrResultado[0]['audit_params'], true);
        }

        if ($this->mfunciones_generales->validateJSON($arrResultado[0]['audit_result'])) {
            $arrResultado[0]['audit_result'] = json_decode($arrResultado[0]['audit_result'], true);
        }

        $data["arrResultado"] = $arrResultado;

        $this->load->view('auditoria_ad/view_detalle_ver', $data);
    }
}
