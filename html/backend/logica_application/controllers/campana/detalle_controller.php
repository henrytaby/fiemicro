<?php
/**
 * @file 
 * Codigo que implementa el controlador para EL DETALLE DEL REGISTRO
 * @brief  CONTROLADOR DE LOGUEO
 * @author JRAD
 * @date Mar 21, 2014
 * @copyright 2017 JRAD
 */
/**
 * Controlador para la administracion del DETALLE DEL REGISTRO
 * @brief CONTROLADOR DEL LOGUEO
 * @class Login_controller 
 */
class Detalle_controller extends MY_Controller {

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
    
    public function CampanaDetalle() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // 0=Insert    1=Update
        
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            $codigo_campana = $this->input->post('codigo', TRUE);
            
            $arrResultado = $this->mfunciones_logica->ObtenerCampana($codigo_campana);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                        
            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "camp_id" => $value["camp_id"],
                        "camtip_id" => $value["camtip_id"],
                        "camtip_nombre" => $value["camtip_nombre"],
                        "camp_nombre" => $value["camp_nombre"],
                        "camp_desc" => $value["camp_desc"],
                        "camp_plazo" => $value["camp_plazo"],
                        "camp_monto_oferta" => number_format($value["camp_monto_oferta"], 2, ',', '.'),
                        "camp_tasa" => number_format($value["camp_tasa"], 2, ',', '.'),
                        "camp_fecha_inicio" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["camp_fecha_inicio"])
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            } 
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
            
            $arrServicios = $this->mfunciones_logica->ObtenerServiciosCampana($codigo_campana);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);
            $data["arrServicios"] = $arrServicios;
            
            $data["arrRespuesta"] = $lst_resultado;
            
            $this->load->view('campana/view_campana_detalle', $data);
            
        }
    }
}
?>