<?php
/**
 * @file 
 * Codigo que implementa el controlador para ga gestión del catálogo del sistema
 * @brief  TIPOS DE PERSONA DEL SISTEMA
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para ga gestión del catálogo del sistema
 * @brief TIPOS DE PERSONA DEL SISTEMA
 * @class Conf_catalogo_controller 
 */
class Persona_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 8;

    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
	 
    public function Persona_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerPersonas(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0]))
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                $item_valor = array(
                    "estructura_codigo" => $value["tipo_persona_id"],
                    "estructura_parent_codigo" => $value["categoria_persona_id"],
                    "estructura_parent_detalle" => $value["categoria_persona_nombre"],
                    "estructura_nombre" => $value["tipo_persona_nombre"]
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
        
        $this->load->view('persona/view_persona_ver', $data);
    }
    
    public function PersonaForm() {
        
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
            
            $arrResultado = $arrResultado = $this->mfunciones_logica->ObtenerPersonas($estructura_codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                        
            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "estructura_codigo" => $value["tipo_persona_id"],
                        "estructura_parent_codigo" => $value["categoria_persona_id"],
                        "estructura_parent_detalle" => $value["categoria_persona_nombre"],
                        "estructura_nombre" => $value["tipo_persona_nombre"]
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
        
        
        // Listado de las Categorías de Personas
        $arrParent = $this->mfunciones_logica->ObtenerCategoriaPersonas(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrParent);                
        $data["arrParent"] = $arrParent;
        
        // Listado de los Documentos
        $arrDocumento = $this->mfunciones_logica->ObtenerDocumento(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDocumento);                
                
        // Lista los perfiles disponibles

        if (isset($arrDocumento[0])) 
        {
            $i = 0;

            foreach ($arrDocumento as $key => $value) 
            {
                $item_valor = array(
                    "documento_id" => $value["documento_id"],
                    "documento_nombre" => $value["documento_nombre"],
                    "documento_asignado" => $this->mfunciones_generales->GetDocumentoPersona($estructura_codigo, $value["documento_id"])
                );
                $lst_resultado2[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado2[0] = $arrMenu;
        }

        $data["arrDocumento"] = $lst_resultado2;
        
        $data["tipo_accion"] = $tipo_accion;
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('persona/view_persona_form', $data);        
    }
    
    public function Persona_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $estructura_nombre = $this->input->post('estructura_nombre', TRUE);
        $catalogo_parent = $this->input->post('catalogo_parent', TRUE);
        
        $arrDocumento = $this->input->post('documento_list', TRUE);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDocumento);
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if($estructura_nombre == "" || $catalogo_parent == -1)
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
            
            $this->mfunciones_logica->UpdatePersona($catalogo_parent, $estructura_nombre, $nombre_usuario, $fecha_actual, $estructura_id);
         
        }
        
        if($tipo_accion == 0)
        {
            // INSERT
            
            $estructura_id = $this->mfunciones_logica->InsertPersona($catalogo_parent, $estructura_nombre, $nombre_usuario, $fecha_actual);
        }
        
        // INSERTAR LOS MENÚS SELECCIONADOS
        
            // 1. Se eliminan los menús del rol
            $this->mfunciones_logica->EliminaDocumentoPersona($estructura_id);
        
            // 2. Se registran los perfiles seleccionados
            
            if (isset($arrDocumento[0])) 
            {
                foreach ($arrDocumento as $key => $value) 
                {
                    $this->mfunciones_logica->InsertarDocumentoPersona($estructura_id, $value, $nombre_usuario, $fecha_actual);
                }
            }
        
        $this->Persona_Ver();
    }
}
?>