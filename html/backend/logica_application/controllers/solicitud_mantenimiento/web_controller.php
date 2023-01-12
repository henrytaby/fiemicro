<?php
/**
 * @file 
 * Codigo que implementa el controlador para la autenticacion de usuario en el sistema
 * @brief  CONTROLADOR SOLICITUD DE PROSPECTO
 * @author JRAD
 * @date Mar 21, 2014
 * @copyright 2017 JRAD
 */
/**
 * Controlador para SOLICITUD DE PROSPECTO
 * @brief CONTROLADOR DEL LOGUEO
 * @class Login_controller 
 */
class Web_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 0;

    function __construct() {
        parent::__construct();
    }
    /**
     * carga la vista para el formulario de login
     * @brief CARGAR LOGIN
     * @author JRAD
     * @date Mar 21, 2014     
     */
        
    public function SolicitudForm() {
        
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

        $this->load->view('solicitud_mantenimiento/view_web_solicitud_editar', $data);
    }
    
    public function Solicitud_Guardar() {
        
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
            
            $nombre_usuario = $_SESSION["session_informacion"]["login"];
            $fecha_actual = date('Y-m-d H:i:s');

            if((int)$solicitud_nit == 0 || $solicitud_nombre_empresa == "" || $this->mfunciones_generales->VerificaCorreo($solicitud_email) == false || $otro == "")
            {
                js_error_div_javascript($this->lang->line('CamposObligatorios'));
                exit();
            }
            
            if ($otro == 1 && $solicitud_otro_detalle == "")
            {
                js_error_div_javascript($this->lang->line('FormularioIncompleto'));
                exit();
            }
            
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
                
                $confirmado = 1; // --> Borrar cuando este en producción, esto es para que no requiera el correo de verificación
                
                $estructura_id = $this->mfunciones_logica->InsertSolicitudMantenimiento($solicitud_nit, $solicitud_nombre_empresa, $otro, $solicitud_otro_detalle, $fecha_actual, $solicitud_email, $ip, $nombre_usuario, $fecha_actual, $confirmado);

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
            
            $data['resultado'] = 1;
            
            $this->load->view('solicitud_mantenimiento/view_web_solicitud_editar', $data);
        }
    }    
}
?>