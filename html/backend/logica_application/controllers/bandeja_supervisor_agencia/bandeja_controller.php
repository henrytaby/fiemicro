<?php
/**
 * @file 
 * Codigo que implementa el controlador para la gestión de Ejecutivos de Cuentas
 * @brief BANDEJA DE LA INSTANCIA
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para ga gestión del catálogo del sistema
 * @brief BANDEJA DE LA INSTANCIA
 * @class Conf_catalogo_controller 
 */
class Bandeja_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 17;

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
        
        // Se captura el valor
        $estructura_id = $this->input->post('codigo', TRUE);
        // Listado de los prospectos
        
        // ***************** ARREGLAR *****************
        
        $arrResultado = $this->mfunciones_logica->ObtenerProspectosEjecutivo(3);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
		                
        if (isset($arrResultado[0])) 
        {
            $i = 0;
                        
            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "prospecto_id" => $value["prospecto_id"],
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "tipo_persona_codigo" => $value["tipo_persona_id"],
                    "tipo_persona_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["tipo_persona_id"], 'tipo_persona'),
                    "empresa_id" => $value["empresa_id"],
                    "empresa_categoria_codigo" => $value["empresa_categoria"],
                    "empresa_categoria_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["empresa_categoria"], 'empresa_categoria'),
                    "prospecto_fecha_asignacion" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["prospecto_fecha_asignacion"]),
                    "prospecto_consolidado_codigo" => $value["prospecto_consolidado"],
                    "prospecto_consolidado_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["prospecto_consolidado"], 'consolidado'),
                    "prospecto_observado_codigo" => $value["prospecto_observado_app"],
                    "prospecto_observado_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["prospecto_observado_app"], 'tipo_observacion'),
                    "empresa_nombre_legal" => $value["empresa_nombre_legal"],
                    "empresa_direccion" => $value["empresa_direccion"],
                    "contacto" => $value["contacto"]
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
        
        $this->load->view('bandeja_supervisor_agencia/view_bandeja_ver', $data);        
    }    
}
?>