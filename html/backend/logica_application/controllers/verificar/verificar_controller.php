<?php
/**
 * @file 
 * Codigo que implementa el controlador para la verificación de la solicitud de visitas
 * @brief  CONTROLADOR DE VERIFICACIÓN DE VISITAS
 * @author JRAD
 * @copyright 2017 JRAD
 */
/**
 * Controlador para la administracion de la autenticacion de usuarios 
 * @brief CONTROLADOR DE VERIFICACIÓN DE VISITAS
 * @class Login_controller 
 */
class Verificar_controller extends CI_Controller {
    /**
     * Constructor de Clases
     * @brief CONSTRUCTOR DE CLASE
     * @author JRAD
     */
    function __construct() {
        parent::__construct();
        session_start();		
    }
	
    /**
     * carga la vista para el formulario de login
     * @brief CARGAR LOGIN
     * @author JRAD
     */
    
    public function Verificar_Guardar() {
		
        $this->lang->load('general', 'castellano');        
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_generales');
        
        // VALIDACIÓN
        
        $texto = '0';
        
        if(!isset($_GET['visita']) || !isset($_GET['id']) || !isset($_GET['token']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $visita = filter_var($this->input->get('visita', TRUE), FILTER_SANITIZE_URL);
        $id = $this->input->get('id', TRUE);
        $token = filter_var($this->input->get('token', TRUE), FILTER_SANITIZE_URL);
        
        if($visita == '' || (int)$id == 0 || $token == '')
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $tabla = 'solicitud_' . $visita;
                
        $arrResultado = $this->mfunciones_logica->VerificaSolicitudVisita($tabla, $token, $id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            if($arrResultado[0]['solicitud_confirmado'])
            {
                $texto = '<i class="fa fa-meh-o" aria-hidden="true"></i> Solicitud ya confirmada, no necesita volver a procesar esta solicitud.';
            }

            $date1 = $arrResultado[0]['solicitud_fecha'];
            $date2 = date('Y-m-d H:m:s');
            $minutos_diferencia = round((strtotime($date2) - strtotime($date1)) /60);

            // El link sólo tiene validez de 24 horas
            if($minutos_diferencia > 1440)
            {
                $texto = '<i class="fa fa-meh-o" aria-hidden="true"></i> Este enlace de verificación ya caducó, si lo requiere puede volver a solicitar la visita.';
            }                    
        } 
        else 
        {
            $texto = '<i class="fa fa-meh-o" aria-hidden="true"></i> Este enlace de verificación no es correcto.';
        }
        
        // FIN VALIDACIÓN
        
        if($texto == '0')
        {
            $nombre_usuario = '';
            if(isset($_SESSION["session_informacion"]["login"]))
            {
                $nombre_usuario = $_SESSION["session_informacion"]["login"];
            }
            
            $fecha_actual = date('Y-m-d H:i:s');
            
            // Se procede a actualizar el estado "confirmado" y limpiar el token            
            $this->mfunciones_logica->UpdateSolicitudVisita($tabla, $nombre_usuario, $fecha_actual, $id);
            
            $texto = "<i class='fa fa-thumbs-o-up' aria-hidden='true'></i> ¡Excelente! Tu solicitud se confirmó correctamente y en breve nos contactaremos contigo.";
        }
        
        $data['texto'] = $texto;
        
        $this->load->view('verificar/view_verificar', $data);
    }    
}
?>