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
    
    public function SolicitudEnviarDocumentos() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // 0=Insert    1=Update

        if(!isset($_POST['codigo_prospecto']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            $codigo_prospecto = $this->input->post('codigo_prospecto', TRUE);
            
            // Listado de los Documentos
            $arrResultado1 = $this->mfunciones_logica->ObtenerDocumentosEnviar($codigo_prospecto);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
				
            if (isset($arrResultado1[0])) 
            {
                $i = 0;

                foreach ($arrResultado1 as $key => $value) 
                {
                    $item_valor = array(
                        "documento_id" => $value["documento_id"],
                        "documento_detalle" => $value["documento_nombre"]
                    );
                    $lst_resultado1[$i] = $item_valor;

                    $i++;
                }
            } 
            else 
            {
                js_error_div_javascript($this->lang->line('TablaNoResultados'));
                exit();
            }
            
            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
            
            $data['arrRespuesta'] = $lst_resultado1;
            
            $data['codigo_prospecto'] = $codigo_prospecto;
                        
            $this->load->view('solicitud_prospecto/view_enviar_ver', $data);
        }
    }
    
    public function SolicitudEnviar_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_POST['documento_list']))
        {
            js_error_div_javascript($this->lang->line('FormularioSinOpciones'));
            exit();
        }
        else
        {
            $codigo_prospecto = $this->input->post('codigo_prospecto', TRUE);
            $arrDocumentos = $this->input->post('documento_list', TRUE);
            
            // Listado Detalle Empresa
            $arrResultado1 = $this->mfunciones_logica->ObtenerDetalleEmpresaCorreo($codigo_prospecto);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

            if (isset($arrResultado1[0])) 
            {
                $i = 0;

                foreach ($arrResultado1 as $key => $value) 
                {
                    $item_valor = array(
                        "empresa_id" => $value["empresa_id"],
                        "empresa_email" => $value["empresa_email"],
                        "empresa_nombre_referencia" => $value["empresa_nombre_referencia"],
                        "empresa_nombre" => $value["empresa_nombre"],
                        "empresa_categoria" => $value["empresa_categoria"],
                        "ejecutivo_asignado_nombre" => $value["ejecutivo_asignado_nombre"],
                        "ejecutivo_asignado_contacto" => $value["ejecutivo_asignado_contacto"]
                    );
                    $lst_resultado1[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                // En el caso que no se encuentre información con los parámetros indicados, se devuelve vacío            
                return $arrResultado1;
                exit();
            }
            
            // En el caso que el array este vacio se muestra el mensaje de error
            if (!isset($arrDocumentos[0])) 
            {
                js_error_div_javascript($this->lang->line('FormularioSinOpciones'));
                exit();
            }
            else
            {
                $i = 0;

                foreach ($arrDocumentos as $key => $value) 
                {
                    $item_valor = array(
                        "documento_id" => $value
                    );
                    $lst_Documentos[$i] = $item_valor;

                    $i++;
                }
            }
            
            $correo_enviado = $this->mfunciones_generales->EnviarCorreo('enviar_documentos_app', $lst_resultado1[0]['empresa_email'], $lst_resultado1[0]['empresa_nombre_referencia'], $lst_resultado1, $lst_Documentos);

            if($correo_enviado)
            {
                $this->load->view('solicitud_prospecto/view_enviar_guardar');
            }            
        }
    }
}
?>