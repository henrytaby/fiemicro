<?php
/**
 * @file 
 * Codigo que implementa el controlador para ga gestión del catálogo del sistema
 * @brief  DOCUMENTOS DEL SISTEMA
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para ga gestión del catálogo del sistema
 * @brief DOCUMENTOS DEL SISTEMA
 * @class Conf_catalogo_controller 
 */
class Documento_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 7;

    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
	 
	public function Documento_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerDocumento(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0]))
        {			
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
				
                $item_valor = array(
                    "documento_id" => $value["documento_id"],
                    "documento_nombre" => $value["documento_nombre"],
                    "documento_vigente" => $value["documento_vigente"],
                    "documento_enviar_codigo" => $value["documento_enviar"],
                    "documento_enviar_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["documento_enviar"], 'se_envia'),
                    "documento_pdf" => $value["documento_pdf"],
                    "documento_enlace" => $this->mfunciones_generales->GetDocumentoEnviar($value["documento_id"], 'file')
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
        
        $this->load->view('documento/view_documento_ver', $data);
        
    }
    
    public function DocumentoForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // 0=Insert    1=Update
        
        $tipo_accion = 0;
        
        if(isset($_POST['tipo_accion']))
        {
            /*
            echo "<pre>";
            print_r($_POST);
            echo "</pre>";
            */
            
            $tipo_accion = $_POST['tipo_accion'];
            
            // UPDATE
            
            $estructura_codigo = $_POST['codigo'];
            
            $arrResultado = $arrResultado = $this->mfunciones_logica->ObtenerDocumento($estructura_codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                        
            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "documento_id" => $value["documento_id"],
                        "documento_nombre" => $value["documento_nombre"],
                        "documento_vigente" => $value["documento_vigente"],
                        "documento_enviar_codigo" => $value["documento_enviar"],
                        "documento_enviar_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["documento_enviar"], 'se_envia'),
                        "documento_pdf" => $value["documento_pdf"],
                        "documento_enlace" => $this->mfunciones_generales->GetDocumentoEnviar($value["documento_id"], 'file'),
                        "documento_codigo" => $value["documento_codigo"],
                        "documento_mandatorio" => $value["documento_mandatorio"]
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
        
        $data["tipo_accion"] = $tipo_accion;
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('documento/view_documento_form', $data);        
    }
    
    public function Documento_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $documento_nombre = $this->input->post('documento_nombre', TRUE);
        $documento_enviar = $this->input->post('documento_enviar', TRUE);
        
        $documento_codigo = $this->input->post('documento_codigo', TRUE);
        $documento_mandatorio = $this->input->post('documento_mandatorio', TRUE);
        
        $existe_adjunto = $this->input->post('existe_adjunto', TRUE);

        if($documento_nombre == "" || $documento_enviar == "")
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        // Sólo se sube el archivo si se seleccionó "Si se Envía"
        
        $documento_pdf = '';
        
        // Se establece que por defecto, no se subirá un Documento
        $sube_adjunto = 0;
        
        if($documento_enviar == 1)
        {
            $_SESSION['auxiliar_bandera_upload'] = 2;
            $_SESSION['auxiliar_bandera_upload_url'] = 'Documento/Ver';
            
            if(empty($_FILES['documento_pdf']['tmp_name']))
            {
                redirect($this->config->base_url());
            }
            
            if (isset($_FILES['documento_pdf']['tmp_name'])) 
            {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $_FILES['documento_pdf']['tmp_name']);
                if ($mime != 'application/pdf') 
                {
                    finfo_close($finfo);

                    redirect($this->config->base_url());
                }
                finfo_close($finfo);
            }
            
            // Nombre del Arhivo
            
            // Descomentar para que el nombre del PDF sea mas rigido y unico
            //$documento_pdf = $this->mfunciones_generales->TextoNoAcentoNoEspacios($documento_nombre) . "_" . date('Y-m-d_H_i_s') . '.pdf';
            $documento_pdf = $this->mfunciones_generales->TextoNoAcentoNoEspacios($documento_nombre) . '_' . time() . '.pdf';

            $mi_archivo = 'documento_pdf';
            $config['upload_path'] = RUTA_DOCUMENTOS;
            $config['file_name'] = $documento_pdf;
            $config['allowed_types'] = "*";
            $config['max_size'] = "50000";

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload($mi_archivo)) {
                //*** ocurrio un error
                $data['uploadError'] = $this->upload->display_errors();
                echo $this->upload->display_errors();
                return;
            }

            $sube_adjunto = 1;
            
            $data['uploadSuccess'] = $this->upload->data();
        }

        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
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
            
            if($sube_adjunto == 1)
            {   // Se actualiza el Documento Subido
                $this->mfunciones_logica->UpdateDocumento($documento_nombre, $documento_enviar, $documento_pdf, $documento_codigo, $documento_mandatorio, $nombre_usuario, $fecha_actual, $estructura_id);
            }
            
            if($sube_adjunto == 0)
            {   // NO se actualiza el Documento Subido
                $this->mfunciones_logica->UpdateDocumentoSinUpload($documento_nombre, $documento_enviar, $documento_codigo, $documento_mandatorio, $nombre_usuario, $fecha_actual, $estructura_id);
            }
         
        }
		
        if($tipo_accion == 0)
        {
            // INSERT
            
            $this->mfunciones_logica->InsertDocumento($documento_nombre, $documento_enviar, $documento_pdf, $documento_codigo, $documento_mandatorio, $nombre_usuario, $fecha_actual);
         
        }
        
        // AUXILIARMENTE SE ENVIA UN FLAG
        
        $_SESSION['auxiliar_bandera_upload'] = 1;
        $_SESSION['auxiliar_bandera_upload_url'] = 'Documento/Ver';
        
        redirect($this->config->base_url());
    }
}
?>