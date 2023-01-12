<?php
/**
 * @file 
 * Codigo que implementa el controlador para EL DETALLE DEL REGISTRO
 * @brief  EJECUTIVOS DE CUENTA
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para la gestión del DETALLE DEL REGISTRO
 * @brief EJECUTIVOS DE CUENTA
 * @class Conf_catalogo_controller 
 */
class Detalle_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 0;

    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */

    public function MantenimientoDetalle() {
        
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
            if(!$this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"], PERFIL_DETALLE_MANTENIMIENTO))
            {
                js_error_div_javascript($this->lang->line('NoAutorizadoPerfil'));
                exit();
            }
            else 
            {
                $codigo_mantenimiento = $this->input->post('codigo', TRUE);

                $arrResultado = $this->mfunciones_logica->ListadoDetalleMantenimientos($codigo_mantenimiento);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                
                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {   
                        $item_valor = array(
                            "mant_id" => $value["mant_id"],
                            "ejecutivo_id" => $value["ejecutivo_id"],
                            "ejecutivo_nombre" => $value["ejecutivo_nombre"],
                            "empresa_id" => $value["empresa_id"],
                            "empresa_categoria_codigo" => $value["empresa_categoria_codigo"],
                            "empresa_nombre" => $value["empresa_nombre"],
                            "empresa_categoria" => $value["empresa_categoria"],
                            "mant_fecha_asignacion" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["mant_fecha_asignacion"]),
                            "mant_estado" => $value["mant_estado"],
                            "mant_checkin" => $value["mant_checkin"],
                            "mant_checkin_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["mant_checkin_fecha"]),
                            "mant_checkin_geo" => $value["mant_checkin_geo"],
                            "mant_completado_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["mant_completado_fecha"]),
                            "mant_completado_geo" => $value["mant_completado_geo"],
                            "mant_documento_adjunto" => $value["mant_documento_adjunto"],
                            "mant_otro" => $value["mant_otro"],
                            "mant_otro_detalle" => $value["mant_otro_detalle"],                            
                            "cal_visita_ini" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["cal_visita_ini"]),
                            "cal_visita_fin" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["cal_visita_fin"])
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }
                else 
                {
                    $lst_resultado[0] = $arrResultado;
                }
                
                // Listado de Servicios asignados a la Solicitud
                $arrTareas = $this->mfunciones_logica->ObtenerDetalleMantenimiento_tareas($codigo_mantenimiento);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrTareas);
                
                $listadoTareas = '';
                
                if(isset($arrTareas[0]))
                {
                    foreach ($arrTareas as $key => $value) 
                    {
                        $listadoTareas .= ' <i class="fa fa-wrench" aria-hidden="true"></i> ' . $value["tarea_detalle"];
                        $listadoTareas .= "<br />";
                    }                                
                }
                
                // Si se eligió otra tarea =/
            
                if($arrResultado[0]['mant_otro'] == 1)
                {
                    $listadoTareas .= ' <i class="fa fa-wrench" aria-hidden="true"></i> Otro: ' . $arrResultado[0]["mant_otro_detalle"];
                    $listadoTareas .= "<br />";
                }
                
                $data["listadoTareas"] = $listadoTareas;                
                $data["arrRespuesta"] = $lst_resultado;

                $this->load->view('mantenimiento/view_mantenimiento_detalle', $data);
            }
        }
    }
}
?>