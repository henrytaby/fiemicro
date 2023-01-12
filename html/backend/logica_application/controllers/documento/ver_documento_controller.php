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
class Ver_documento_controller extends MY_Controller {

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
	 
	public function Documento_Visor() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $documento_base64 = $this->input->get('documento_base64', TRUE);
        $type = $this->input->get('type', TRUE);

        // Se verifica si tiene el permiso correcto para ver Documento

        $nombre_download = 'Documento_' . $this->mfunciones_generales->TextoNoAcentoNoEspacios($this->lang->line('NombreSistema_corto')) . '_' .date('YmdHi') . '.pdf';
        
        if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
        {
            switch ($type)
            {
                case 'dto':

                        $path = RUTA_DOCUMENTOS . '/' . $documento_base64; 
                    
                        $documento_resultado = $this->mfunciones_generales->GetDocumentoBase64_Ruta($path);
                    
                break;

                case 'pto':

                    // Buscar Nombre del Documento del Prospecto de acuerdo al ID del Documento
                    
                    $codigo_prospecto = $documento_base64;
                    $codigo_documento = $this->input->get('documento', TRUE);
                    
                    $documento_resultado = $this->mfunciones_generales->GetInfoDigitalizado($codigo_prospecto, $codigo_documento, 'documento');

                break;
            
                case 'pto_h':

                    // Buscar Nombre del Documento del Prospecto de acuerdo al ID del Documento
                    
                    $codigo_prospecto = $documento_base64;
                    $codigo_documento_prospecto = $this->input->get('documento', TRUE);
                    
                    $documento_resultado = $this->mfunciones_generales->GetDocDigitalizado($codigo_prospecto, $codigo_documento_prospecto, 'documento');

                break;

                case 'mto':

                    $codigo_mantenimiento = $this->input->get('documento', TRUE);
                    $path = RUTA_MANTENIMIENTOS . 'man_' . $codigo_mantenimiento . '/man_' . $codigo_mantenimiento . '_' . $documento_base64;
                    
                    $documento_resultado = $this->mfunciones_generales->GetDocumentoBase64_Ruta($path);
                        
                break;
            
                case 'act':

                    $codigo_prospecto = $this->input->get('documento', TRUE);
                    $path = RUTA_PROSPECTOS . 'afn_' . $codigo_prospecto . '/' . $documento_base64;
                    
                    $documento_resultado = $this->mfunciones_generales->GetDocumentoBase64_Ruta($path);
                        
                break;
            
                case 'ter':

                    // Buscar Nombre del Documento del Tercero de acuerdo al ID del Documento
                    
                    $codigo_prospecto = $documento_base64;
                    $codigo_documento = $this->input->get('documento', TRUE);
                    
                    $tipo_aux = $this->input->get('tipo_aux', TRUE);

                    if($tipo_aux == 0)
                    {
                        $documento_resultado = $this->mfunciones_generales->GetInfoTercerosDigitalizado($codigo_prospecto, $codigo_documento, 'documento');
                    }
                    else
                    {
                        $documento_resultado = $this->mfunciones_generales->GetInfoTercerosDigitalizadoPDF($codigo_prospecto, $codigo_documento, 'documento');
                    }
                    
                    if(!$documento_resultado)
                    {
                        js_error_div_javascript($this->lang->line('NoAutorizado'));
                        exit();
                    }
                    
                break;

                case 'ter_h':

                    // Buscar Nombre del Documento del Prospecto de acuerdo al ID del Documento
                    
                    $codigo_prospecto = $documento_base64;
                    $codigo_documento_prospecto = $this->input->get('documento', TRUE);
                    
                    $documento_resultado = $this->mfunciones_generales->GetDocDigitalizadoTercero($codigo_prospecto, $codigo_documento_prospecto, 'documento');

                break;
            
                case 'ter_contrato':

                    // Buscar Nombre del Documento del Prospecto de acuerdo al ID del Documento
                    
                    $codigo_prospecto = $documento_base64;
                    $codigo_documento_prospecto = $this->input->get('documento', TRUE);
                    
                    $documento_resultado = $this->mfunciones_generales->GetInfoTercerosDigitalizadoPDFaux($codigo_prospecto, $codigo_documento_prospecto, 'documento');

                break;
                
                case 'sol':

                    $this->load->model('mfunciones_microcreditos');
                    
                    // Buscar Nombre del Documento del Prospecto de acuerdo al ID del Documento
                    
                    $codigo_solicitud = $documento_base64;
                    $codigo_documento = $this->input->get('documento', TRUE);
                    
                    $documento_resultado = $this->mfunciones_microcreditos->GetInfoSolicitudDigitalizadoPDF($codigo_solicitud, $codigo_documento, 'documento');

                break;
            
                case 'sol_h':

                    $this->load->model('mfunciones_microcreditos');
                    
                    // Buscar Nombre del Documento del Prospecto de acuerdo al ID del Documento
                    
                    $codigo_solicitud = $documento_base64;
                    $codigo_documento_solicitud = $this->input->get('documento', TRUE);
                    
                    $documento_resultado = $this->mfunciones_microcreditos->SolGetDocDigitalizado($codigo_solicitud, $codigo_documento_solicitud, 'documento');

                break;
            
                case 'norm';
                    
                    $this->load->model('mfunciones_cobranzas');
                    
                    // Buscar Nombre del Documento del Prospecto de acuerdo al ID del Documento
                    
                    $codigo_solicitud = $documento_base64;
                    $codigo_documento = $this->input->get('documento', TRUE);
                    
                    $documento_resultado = $this->mfunciones_cobranzas->GetInfoRegistroDigitalizadoPDF($codigo_solicitud, $codigo_documento, 'documento');
                    
                break;
            
                case 'norm_h':

                    $this->load->model('mfunciones_cobranzas');
                    
                    // Buscar Nombre del Documento del Prospecto de acuerdo al ID del Documento
                    
                    $codigo_solicitud = $documento_base64;
                    $codigo_documento = $this->input->get('documento', TRUE);
                    
                    $documento_resultado = $this->mfunciones_cobranzas->RegGetDocDigitalizado($codigo_solicitud, $codigo_documento, 'documento');

                break;
            
                default:
                    exit();
                break;

            }

            $data['documento_base64'] = $documento_resultado;

            $data['nombre_download'] = $nombre_download;
            
            if($type == 'ter')
            {
                if($tipo_aux == 0)
                {
                    $this->load->view('documento/view_imagen_visor', $data);
                }
                else
                {
                    $this->load->view('documento/view_documento_visor', $data);
                }
            }
            else
            {
                $this->load->view('documento/view_documento_visor', $data);
            }
        }
        else
        {
            js_error_div_javascript($this->lang->line('NoAutorizadoPerfil'));
            exit();
        }
    }
    
    public function Documento_VisorValidacion() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $documento_base64 = $this->input->get('documento_base64', TRUE);
        $type = $this->input->get('type', TRUE);

        // Se verifica si tiene el permiso correcto para ver Documento

        $nombre_download = 'Documento_' . $this->mfunciones_generales->TextoNoAcentoNoEspacios($this->lang->line('NombreSistema_corto')) . '_' .date('YmdHi') . '.pdf';
        
        switch ($type)
        {
            case 'ter':

                // Buscar Nombre del Documento del Tercero de acuerdo al ID del Documento

                $codigo_prospecto = $documento_base64;
                $codigo_documento = $this->input->get('documento', TRUE);

                $tipo_aux = $this->input->get('tipo_aux', TRUE);

                if($tipo_aux == 0)
                {
                    $documento_resultado = $this->mfunciones_generales->GetInfoTercerosDigitalizado($codigo_prospecto, $codigo_documento, 'documento');
                }
                else
                {
                    $documento_resultado = $this->mfunciones_generales->GetInfoTercerosDigitalizadoPDF($codigo_prospecto, $codigo_documento, 'documento');
                }

                if(!$documento_resultado)
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }

            break;

            default:
                    exit();
            break;

        }

        $data['documento_base64'] = $documento_resultado;

        $data['nombre_download'] = $nombre_download;

        if($type == 'ter')
        {
            if($tipo_aux == 0)
            {
                $this->load->view('documento/view_imagen_visor', $data);
            }
            else
            {
                $this->load->view('documento/view_documento_visor', $data);
            }
        }
        else
        {
            $this->load->view('documento/view_documento_visor', $data);
        }
    }
}
?>