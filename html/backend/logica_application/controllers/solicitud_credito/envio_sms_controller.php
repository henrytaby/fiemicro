<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador para el Envío de Correos en Segundo Plano
 * @brief CONTROLADOR DEL LOGUEO
 * @class Login_controller 
 */
class Envio_sms_controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        session_start();		
    }

    public function EnvioSMS() {       
        
        $logger = new CI_Log();
        
        $this->load->model('mfunciones_generales');
        $this->lang->load('general', 'castellano');
        
        if(!isset($_POST['param__end_point']) || !isset($_POST['parametros']) || !isset($_POST['accion_usuario']) || !isset($_POST['accion_fecha']))
        {
            $logger->write_log('error', 'JRAD: SMS Error. Missing POST parameters.');
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $param__end_point = $this->input->post('param__end_point', TRUE);
        $parametros = $this->input->post('parametros', TRUE);
        $accion_usuario = $this->input->post('accion_usuario', TRUE);
        $accion_fecha = $this->input->post('accion_fecha', TRUE);
        
        // Se envían los datos
        $this->mfunciones_generales->Cliente_SOA_FIE_Generico('', $param__end_point, $parametros, $accion_usuario, $accion_fecha);
    }
}

?>