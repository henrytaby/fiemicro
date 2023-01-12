<?php
/**
 * @file 
 * Codigo que implementa el controlador para ga gestión del catálogo del sistema
 * @brief  TAREAS DE MANTENIMIENTO DE CARTERA DEL SISTEMA
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para ga gestión del catálogo del sistema
 * @brief TAREAS DE MANTENIMIENTO DE CARTERA DEL SISTEMA
 * @class Conf_catalogo_controller 
 */
class Campana_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 37;

    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
	 
    public function Campana_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerCampana(-1, '');
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0]))
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
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
        
        $data["arrRespuesta"] = $lst_resultado;
        
        $this->load->view('campana/view_campana_ver', $data);
        
    }
    
    public function CampanaForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // 0=Insert    1=Update
        
        $tipo_accion = 0;
        
        if(isset($_POST['tipo_accion']))
        {
            $tipo_accion = $_POST['tipo_accion'];
            
            // UPDATE
            
            $estructura_codigo = $_POST['codigo'];
            
            $arrResultado = $this->mfunciones_logica->ObtenerCampana($estructura_codigo);
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
                        "camp_monto_oferta" => $value["camp_monto_oferta"],
                        "camp_tasa" => $value["camp_tasa"],
                        "camp_color" => $value["camp_color"],
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
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
            
            $data["arrRespuesta"] = $lst_resultado;
        }
        else
        {
            $tipo_accion = 0;
            
            // INSERT
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
            
            $estructura_codigo = -1;
        }
        
        $arrTipoCampana = $this->mfunciones_logica->ObtenerTipoCampana(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrTipoCampana);
        $data["arrTipoCampana"] = $arrTipoCampana;

        
        // Listado de Servicios
        $arrServicios = $this->mfunciones_logica->ObtenerServicio(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);

        // Lista los perfiles disponibles

        if (isset($arrServicios[0])) 
        {
            $i = 0;

            foreach ($arrServicios as $key => $value) 
            {
                $item_valor = array(
                    "servicio_id" => $value["servicio_id"],
                    "servicio_detalle" => $value["servicio_detalle"],
                    "servicio_asignado" => $this->mfunciones_generales->GetServicioCampana($estructura_codigo, $value["servicio_id"])
                );
                $lst_resultado2[$i] = $item_valor;

                $i++;
            }
        }
        else 
        {
            $lst_resultado2[0] = $arrServicios;
        }

        $data["arrServicios"] = $lst_resultado2;
        
        
        $data["tipo_accion"] = $tipo_accion;
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('campana/view_campana_form', $data);
        
    }
    
    public function Campana_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        
        $camtip_id = $this->input->post('camtip_id', TRUE);
        $camp_nombre = $this->input->post('camp_nombre', TRUE);
        $camp_desc = $this->input->post('camp_desc', TRUE);
        $camp_plazo = $this->input->post('camp_plazo', TRUE);
        $camp_monto_oferta = $this->input->post('camp_monto_oferta', TRUE);
        $camp_tasa = $this->input->post('camp_tasa', TRUE);
        
        $camp_color = $this->input->post('camp_color', TRUE);
        
        $camp_fecha_inicio = $this->input->post('camp_fecha_inicio', TRUE);
        
        $arrServicios = $this->input->post('servicio_list', TRUE);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        // 0=Insert    1=Update
        
        $tipo_accion = $this->input->post('tipo_accion', TRUE);
        
        
            // Validaciones
                
            $separador = '<br /> - ';
            $error_texto = '';

            if($camtip_id == '-1')
            {
                $error_texto .= $separador . $this->lang->line('campana_tipo');
            }
            
            if($camp_nombre == '')
            {
                $error_texto .= $separador . $this->lang->line('campana_nombre');
            }
            
            if($camp_desc == '')
            {
                $error_texto .= $separador . $this->lang->line('campana_desc');
            }
            
            if((int)$camp_plazo == 0)
            {
                $error_texto .= $separador . $this->lang->line('campana_plazo');
            }
            
            if((int)$camp_monto_oferta == 0)
            {
                $error_texto .= $separador . $this->lang->line('campana_monto_oferta');
            }
            
            if(!filter_var($camp_tasa, FILTER_VALIDATE_FLOAT) !== false)
            {
                $error_texto .= $separador . $this->lang->line('campana_tasa');
            }
            
            if($camp_color == '')
            {
                $error_texto .= $separador . 'Color Rubro';
            }
            
            if($this->mfunciones_generales->VerificaFechaD_M_Y($camp_fecha_inicio) == false)
            {
                $error_texto .= $separador . $this->lang->line('campana_fecha_inicio');
            }
            
            if (!isset($arrServicios[0]))
            {
                $error_texto .= $separador . $this->lang->line('campana_servicios');
            }
            
            if($error_texto != '')
            {
                js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                exit();
            }
        
            // Se verifica que el nombre de la campaña esta en uso
            
            $existe_nombre = "";
            
            $arrResultado4 = $this->mfunciones_logica->ObtenerCodigoCampanaNombre($camp_nombre);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado4);

            if (isset($arrResultado4[0])) 
            {
                $existe_nombre = $arrResultado4[0]['camp_nombre'];
            }
            
            $camp_fecha_inicio = $this->mfunciones_generales->getFormatoFechaDate($camp_fecha_inicio);
            
        if($tipo_accion == 1)
        {
            // UPDATE
            $estructura_id = $this->input->post('estructura_id', TRUE);
            
            // Se busca el nombre del registro
            
            $arrResultado3 = $this->mfunciones_logica->ObtenerCampana($estructura_id);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
            
            if (isset($arrResultado4[0]) && $arrResultado3[0]['camp_nombre'] != $camp_nombre) 
            {
                js_error_div_javascript($this->lang->line('campana_nombre_error'));
                exit();
            }
            
            $this->mfunciones_logica->UpdateCampana($camtip_id, $camp_nombre, $camp_desc, $camp_plazo, $camp_monto_oferta, $camp_tasa, $camp_fecha_inicio, $camp_color, $nombre_usuario, $fecha_actual, $estructura_id);
        }
        
        if($tipo_accion == 0)
        {
            // Insert, se pregunta si el nombre de la campaña no lo tiene otro registro
            if($existe_nombre != "")
            {
                js_error_div_javascript($this->lang->line('campana_nombre_error'));
                exit();
            }
            
            $estructura_id = $this->mfunciones_logica->InsertCampana($camtip_id, $camp_nombre, $camp_desc, $camp_plazo, $camp_monto_oferta, $camp_tasa, $camp_fecha_inicio, $camp_color, $nombre_usuario, $fecha_actual);
        }
        
        // INSERTAR LOS SERVICIOS SELECCIONADOS
        
            // 1. Se eliminan los servicios de la solicitud
            $this->mfunciones_logica->Eliminar_SolicitudCampana($estructura_id);
        
            // 2. Se registran los servicios seleccionados
            
            if (isset($arrServicios[0]))
            {
                foreach ($arrServicios as $key => $value) 
                {
                    $this->mfunciones_logica->InsertarCampanaServicio($estructura_id, $value, $nombre_usuario, $fecha_actual);
                }
            }
        
        
        $this->Campana_Ver();
    }
}
?>