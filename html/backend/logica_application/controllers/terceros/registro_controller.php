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
 * Controlador para SOLICITUD DE AFILIACIÓN POR TERCEROS
 * @brief CONTROLADOR de AFILIACIÓN POR TERCEROS
 * @class Afiliador_controller 
 */
class Registro_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 43;

    function __construct() {
        parent::__construct();
    }
    /**
     * carga la vista para el formulario de login
     * @brief CARGAR LOGIN
     * @author JRAD
     * @date Mar 21, 2014     
     */
    
    public function Afiliador_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerTerceros(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0]))
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                $item_valor = array(
                    
                    "afiliador_id" => $value["afiliador_id"],
                    "afiliador_nombre" => $value["afiliador_nombre"],
                    "afiliador_key" => $value["afiliador_key"],
                    "afiliador_responsable_nombre" => $value["afiliador_responsable_nombre"],
                    "afiliador_responsable_email" => $value["afiliador_responsable_email"],
                    "afiliador_responsable_contacto" => $value["afiliador_responsable_contacto"],
                    "afiliador_referencia_documento" => $value["afiliador_referencia_documento"],
                    "afiliador_fecha_registro" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["afiliador_fecha_registro"])
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
        
        $this->load->view('terceros/view_afiliador_ver', $data);
        
    }
    
    public function AfiliadorForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();

        // 0=Insert    1=Update
        
        $tipo_accion = 0;
        
        if(isset($_POST['tipo_accion']))
        {   
            // UPDATE
            
            $tipo_accion = $this->input->post('tipo_accion', TRUE);
            $codigo = $this->input->post('codigo', TRUE);
            $arrResultado = $this->mfunciones_logica->ObtenerTerceros($codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0]))
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) {
                    $item_valor = array(

                        "afiliador_id" => $value["afiliador_id"],
                        "afiliador_nombre" => $value["afiliador_nombre"],
                        "afiliador_key" => $value["afiliador_key"],
                        "afiliador_responsable_nombre" => $value["afiliador_responsable_nombre"],
                        "afiliador_responsable_email" => $value["afiliador_responsable_email"],
                        "afiliador_responsable_contacto" => $value["afiliador_responsable_contacto"],
                        "afiliador_referencia_documento" => $value["afiliador_referencia_documento"],
                        "afiliador_fecha_registro" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["afiliador_fecha_registro"])
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
        }
        
        $data["tipo_accion"] = $tipo_accion;

        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

        $this->load->view('terceros/view_afiliador_form', $data);
        
    }
    
    public function Afiliador_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $afiliador_nombre = $this->input->post('afiliador_nombre', TRUE);
        $afiliador_responsable_nombre = $this->input->post('afiliador_responsable_nombre', TRUE);
        $afiliador_responsable_email = $this->input->post('afiliador_responsable_email', TRUE);
        $afiliador_responsable_contacto = $this->input->post('afiliador_responsable_contacto', TRUE);
        $afiliador_referencia_documento = $this->input->post('afiliador_referencia_documento', TRUE);
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        // Validaciones
                
        $separador = '<br /> - ';
        $error_texto = '';
        
        if($afiliador_nombre == '')
        {
            $error_texto .= $separador . $this->lang->line('afiliador_nombre');
        }
        
        if($afiliador_responsable_nombre == '')
        {
            $error_texto .= $separador . $this->lang->line('afiliador_responsable_nombre');
        }
        
        if($this->mfunciones_generales->VerificaCorreo($afiliador_responsable_email) == false)
        {
            $error_texto .= $separador . $this->lang->line('afiliador_responsable_email');
        }
        
        if($afiliador_responsable_contacto == '')
        {
            $error_texto .= $separador . $this->lang->line('afiliador_responsable_contacto');
        }
        
        if($afiliador_referencia_documento == '')
        {
            $error_texto .= $separador . $this->lang->line('afiliador_referencia_documento');
        }
        
        if($error_texto != '')
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
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
            
            $this->mfunciones_logica->UpdateTerceros($afiliador_nombre, $afiliador_responsable_nombre, $afiliador_responsable_email, $afiliador_responsable_contacto, $afiliador_referencia_documento, $nombre_usuario, $fecha_actual, $estructura_id);
        }
        
        if($tipo_accion == 0)
        {
            $codigo_afiliador = $this->mfunciones_logica->InsertTerceros($afiliador_nombre, $afiliador_responsable_nombre, $afiliador_responsable_email, $afiliador_responsable_contacto, $afiliador_referencia_documento, $fecha_actual, $nombre_usuario, $fecha_actual);
            
            $afiliador_key = hash('sha256', $codigo_afiliador);
            
            $this->mfunciones_logica->UpdateTercerosKey($afiliador_key, $nombre_usuario, $fecha_actual, $codigo_afiliador);
        }
        
        $this->Afiliador_Ver();
    }
    
    public function EnvioForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();

        // 0=Insert    1=Update
        
        $tipo_accion = 0;
        
        if(isset($_POST['tipo_accion']) && isset($_POST['codigo']))
        {   
            // UPDATE
            
            $tipo_accion = $this->input->post('tipo_accion', TRUE);
            $codigo = $this->input->post('codigo', TRUE);
            $arrResultado = $this->mfunciones_logica->ObtenerTerceros($codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0]))
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) {
                    $item_valor = array(

                        "afiliador_id" => $value["afiliador_id"],
                        "afiliador_nombre" => $value["afiliador_nombre"],
                        "afiliador_key" => $value["afiliador_key"],
                        "afiliador_responsable_nombre" => $value["afiliador_responsable_nombre"],
                        "afiliador_responsable_email" => $value["afiliador_responsable_email"],
                        "afiliador_responsable_contacto" => $value["afiliador_responsable_contacto"],
                        "afiliador_referencia_documento" => $value["afiliador_referencia_documento"],
                        "afiliador_fecha_registro" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["afiliador_fecha_registro"])
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            }
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
            
            $data["arrRespuesta"] = $lst_resultado;
            
            $data["tipo_accion"] = $tipo_accion;

            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

            // Se obtiene el contenido del Correo como vista previa
            
                $listado_usuarios = '';
            
                $arrUsuariosApp = $this->mfunciones_logica->ObtenerTercerosUsuarios($codigo);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrUsuariosApp);

                if (isset($arrUsuariosApp[0]))
                {
                    $i = 0;

                    foreach ($arrUsuariosApp as $key => $value) {
                        
                        $listado_usuarios .= ' → ' . $value["usuario_nombre"] . ' - ' . $value["usuario_email"] . '<br />';
                    }
                }
                else 
                {
                    $listado_usuarios = '<i> Aún no se tiene usuarios habilitados con el Perfil App "' . $this->mfunciones_generales->GetValorCatalogo(5, 'tipo_perfil_app') . '". </i>';
                }
            
                $arrayParametros[0] = array(
                    "nombre_afiliador" => $lst_resultado[0]["afiliador_nombre"],
                    "nombre_afiliador_responsable" => $lst_resultado[0]["afiliador_responsable_nombre"],
                    "empresa_afiliador_correo" => $lst_resultado[0]["afiliador_responsable_email"],
                    "detalle_custom" => "<i>Texto Personalizado</i>",
                    "empresa_afiliador_link" => "#",
                    "nombre_corto" => $this->lang->line('NombreSistema_corto'),
                    "destinatario_nombre" => $lst_resultado[0]["afiliador_responsable_nombre"],
                    "empresa_afiliador_usuarios" => $listado_usuarios
                );
            
                $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo('templ-16');
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                $contenido_mensaje = str_replace(
                        array('{empresa_afiliador_nombre}', '{empresa_afiliador_correo}', '{detalle_custom}', '{empresa_afiliador_link}', '{nombre_corto}', '{destinatario_nombre}', '{empresa_afiliador_usuarios}'),
                        array($arrayParametros[0]['nombre_afiliador'], $arrayParametros[0]['empresa_afiliador_correo'], $arrayParametros[0]['detalle_custom'], $arrayParametros[0]['empresa_afiliador_link'], $arrayParametros[0]['nombre_corto'], $arrayParametros[0]['destinatario_nombre'], $arrayParametros[0]['empresa_afiliador_usuarios']),
                        $contenido_mensaje
                );
            
                $contenido_mensaje = str_replace("<p>", '<br /><p>', $contenido_mensaje);
                
            $data["contenido_mensaje"] = $contenido_mensaje;
            
            $this->load->view('terceros/view_afiliador_envio_form', $data);
        }
        else
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
    }
    
    public function EnvioForm_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');

        // 0=Insert    1=Update
        
        $tipo_accion = 0;
        
        if(isset($_POST['codigo']))
        {   
            // UPDATE
            
            $codigo = $this->input->post('codigo', TRUE);
            
            $afiliador_texto_custom = $this->input->post('afiliador_texto_custom', TRUE);
            
            $arrResultado = $this->mfunciones_logica->ObtenerTerceros($codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0]))
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) {
                    $item_valor = array(

                        "afiliador_id" => $value["afiliador_id"],
                        "afiliador_nombre" => $value["afiliador_nombre"],
                        "afiliador_key" => $value["afiliador_key"],
                        "afiliador_responsable_nombre" => $value["afiliador_responsable_nombre"],
                        "afiliador_responsable_email" => $value["afiliador_responsable_email"],
                        "afiliador_responsable_contacto" => $value["afiliador_responsable_contacto"],
                        "afiliador_referencia_documento" => $value["afiliador_referencia_documento"],
                        "afiliador_fecha_registro" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["afiliador_fecha_registro"])
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            }

                $aux_route = hash('sha256', 'Afiliador/Info');
                $aux_post = hash('sha256', 'codigo_afiliador');
                $url_key = site_url($aux_route . '?' . $aux_post . '=' . $lst_resultado[0]["afiliador_key"] . '&' . hash('sha256', 'random'));
            
                $listado_usuarios = '';
            
                $arrUsuariosApp = $this->mfunciones_logica->ObtenerTercerosUsuarios($codigo);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrUsuariosApp);

                if (isset($arrUsuariosApp[0]))
                {
                    $i = 0;

                    foreach ($arrUsuariosApp as $key => $value) {
                        
                        $listado_usuarios .= ' → ' . $value["usuario_nombre"] . ' - ' . $value["usuario_email"] . '<br />';
                    }
                }
                else 
                {
                    $listado_usuarios = '<i> Aún no se tiene usuarios habilitados con el Perfil App "' . $this->mfunciones_generales->GetValorCatalogo(5, 'tipo_perfil_app') . '". </i>';
                }
                
                $arrayParametros[0] = array(
                    "nombre_afiliador" => $lst_resultado[0]["afiliador_nombre"],
                    "nombre_afiliador_responsable" => $lst_resultado[0]["afiliador_responsable_nombre"],
                    "empresa_afiliador_correo" => $lst_resultado[0]["afiliador_responsable_email"],
                    "detalle_custom" => $afiliador_texto_custom,
                    "empresa_afiliador_link" => str_replace(';', '', $url_key),
                    "empresa_afiliador_usuarios" => $listado_usuarios
                );
                
            $this->mfunciones_generales->EnviarCorreo('terceros_integracion', $lst_resultado[0]["afiliador_responsable_email"], $lst_resultado[0]["afiliador_responsable_nombre"], $arrayParametros);
                
            $this->load->view('terceros/view_afiliador_envio_form_guardar');
        }
        else
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
    }
    
}
?>