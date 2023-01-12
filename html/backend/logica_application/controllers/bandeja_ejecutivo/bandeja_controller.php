<?php
/**
 * @file 
 * Codigo que implementa el controlador para la gestión de Visitas de los Ejecutivos de Cuentas
 * @brief BANDEJA DE LA INSTANCIA
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para ga gestión de Visitas de los Ejecutivos de Cuentas
 * @brief BANDEJA DE LA INSTANCIA
 * @class Conf_catalogo_controller 
 */
class Bandeja_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 21;

    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
    
    public function Bandeja_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $arrResultado = $this->mfunciones_logica->BandejaVisitasEjecutivo($_SESSION["session_informacion"]["codigo"]);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;

            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "tipo_visita" => $value["tipo_visita"],
                    "tipo_visita_codigo" => $value["tipo_visita_codigo"],
                    "visita_id" => $value["visita_id"],
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "empresa_id" => $value["empresa_id"],
                    "empresa_categoria_codigo" => $value["empresa_categoria"],
                    "empresa_categoria_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["empresa_categoria"], 'empresa_categoria'),
                    "empresa_nombre" => $value["empresa_nombre"],
                    "checkin" => $value["checkin"],
                    "checkin_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["checkin_fecha"]),
                    "checkin_geo" => $value["checkin_geo"],
                    "cal_visita_ini" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["cal_visita_ini"]),
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        }
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }
        
        $data["arrRespuesta"] = $lst_resultado;
        
        $this->load->view('bandeja_ejecutivo/view_bandeja_ver', $data);        
    }    
}
?>