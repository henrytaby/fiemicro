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
class Tarea_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 10;

    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
	 
    public function Tarea_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerTarea(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0]))
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                $item_valor = array(
                    "estructura_codigo" => $value["tarea_id"],
                    "estructura_nombre" => $value["tarea_detalle"],
                    "tarea_activo" => $this->mfunciones_generales->GetValorCatalogo($value["tarea_activo"], 'activo'),
                    "perfil_app_id" => $value["perfil_app_id"],
                    "perfil_app_nombre" => $value["perfil_app_nombre"]
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
        
        $this->load->view('tarea/view_tarea_ver', $data);
        
    }
    
    public function TareaForm() {
        
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
            
            $arrResultado = $arrResultado = $this->mfunciones_logica->ObtenerTarea($estructura_codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                        
            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "estructura_codigo" => $value["tarea_id"],
                        "estructura_nombre" => $value["tarea_detalle"],
                        "tarea_activo" => $value["tarea_activo"],
                        "perfil_app_id" => $value["perfil_app_id"],
                        "perfil_app_nombre" => $value["perfil_app_nombre"]
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
            
            $estructura_codigo = 0;
        }
        
        // Listado de los Perfiles App Activos
        $arrParent = $this->mfunciones_logica->ObtenerListaPerfilAppActivo(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrParent);                
        $data["arrParent"] = $arrParent;
        
        $data["tipo_accion"] = $tipo_accion;
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('tarea/view_tarea_form', $data);
        
    }
    
    public function Tarea_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $estructura_nombre = $this->input->post('estructura_nombre', TRUE);
        $catalogo_parent = $this->input->post('catalogo_parent', TRUE);
        $tarea_activo = $this->input->post('tarea_activo', TRUE);
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if($estructura_nombre == "" || $catalogo_parent == -1 || $tarea_activo == -1)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        // 0=Insert    1=Update
        
        $tipo_accion = $this->input->post('tipo_accion', TRUE);
        
        if($tipo_accion == 1)
        {
            // UPDATE
            $estructura_id = $this->input->post('estructura_id', TRUE);
            
            if((int)$estructura_id == 0)
            {
                js_error_div_javascript($this->lang->line('FormularioIncompleto'));
                exit();
            }
            
            // Sólo para el registro ID=1
            if($estructura_id == 1)
            {
                $catalogo_parent = 0;
            }
            
            $this->mfunciones_logica->UpdateTarea($estructura_nombre, $catalogo_parent, $tarea_activo, $nombre_usuario, $fecha_actual, $estructura_id);
        }
        
        if($tipo_accion == 0)
        {
            $this->mfunciones_logica->InsertTarea($estructura_nombre, $catalogo_parent, $tarea_activo, $nombre_usuario, $fecha_actual);
        }
        
        $this->Tarea_Ver();
    }
}
?>