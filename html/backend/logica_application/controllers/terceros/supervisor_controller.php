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
class Supervisor_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 44;

    function __construct() {
        parent::__construct();
    }
    /**
     * carga la vista para el formulario de login
     * @brief CARGAR LOGIN
     * @author JRAD
     * @date Mar 21, 2014     
     */
    
    public function Solicitud_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estado = 2;
        
        $aux_fecha_fin = date("Y-m-d H:i:s");
        $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa(9);
        $tiempo_etapa = $arrEtapa[0]['etapa_tiempo'];
        
        $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        $data['nombre_region'] = $lista_region->region_nombres_texto;
        
        $arrResultado = $this->mfunciones_logica->ObtenerSolicitudTercerosRegion(-1, $estado, $lista_region->region_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0]))
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                
                $tiempo = $this->mfunciones_generales->ObtenerHorasLaboral($value["cobis_fecha"], $aux_fecha_fin, $tiempo_etapa);
                
                $item_valor = array(
                    
                    "terceros_id" => $value["terceros_id"],
                    "tipo_persona_id_codigo" => $value["tipo_persona_id"],
                    "tipo_persona_id_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["tipo_persona_id"], 'tipo_persona'),
                    "afiliador_id" => $value["afiliador_id"],
                    "terceros_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["cobis_fecha"]),
                    "terceros_estado" => $value["terceros_estado"],
                    "tercero_asistencia" => $value["tercero_asistencia"],
                    "tiempo_horas" => $tiempo->horas_laborales,
                    "tiempo_texto" => $tiempo->resultado_bandera,
                    "terceros_columna1" => $this->mfunciones_generales->ObtenerNombreRegionCodigo($value["codigo_agencia_fie"]),
                    "terceros_columna2" => $this->mfunciones_generales->GetValorCatalogoDB($value['dir_localidad_ciudad'], 'dir_localidad_ciudad'),
                    "terceros_columna3" => $value["di_primernombre"] . ' ' . $value["di_primerapellido"] . ' ' . $value["di_segundoapellido"],
                    "terceros_columna4" => $value["cI_numeroraiz"] . ' ' . $value["cI_complemento"] . ' ' . $this->mfunciones_generales->GetValorCatalogoDB($value['cI_lugar_emisionoextension'], 'cI_lugar_emisionoextension'),
                    "terceros_columna5" => $value["direccion_email"] . '<br />' . $value["dir_notelefonico"],
                    "tipo_cuenta" => $this->mfunciones_generales->GetValorCatalogoDB($value['tipo_cuenta'], 'tipo_cuenta'),
                    "geo1" => $value["coordenadas_geo_dom"],
                    "geo2" => $value["coordenadas_geo_trab"],
                    'envio' => $this->mfunciones_generales->GetValorCatalogo($value['envio'], 'tercero_envio'),
                );
                $lst_resultado[$i] = $item_valor;
                
                $i++;
            }
        }
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }
        
        $estado_texto = $this->mfunciones_generales->GetValorCatalogo($estado, 'terceros_estado');
        
        $data["estado"] = $estado;
        $data["estado_texto"] = $estado_texto;
        
        $data["arrRespuesta"] = $lst_resultado;
        
        $data["tiempo_etapa"] = $tiempo_etapa;
        
        $_SESSION['direccion_bandeja_actual'] = 'Afiliador/Supervisor/Ver';
        
        $this->load->view('terceros/view_supervisor_ver', $data);
        
    }
    
    public function SolicitudCompletar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // 0=Insert    1=Update

        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            $codigo = $this->input->post('codigo', TRUE);
            $estado = $this->input->post('estado', TRUE);

            $arrResultado = $this->mfunciones_logica->ObtenerSolicitudTerceros($codigo, $estado);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0]))
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) {
                    $item_valor = array(

                        "terceros_id" => $value["terceros_id"],
                        "tipo_persona_id_codigo" => $value["tipo_persona_id"],
                        "tipo_persona_id_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["tipo_persona_id"], 'tipo_persona'),
                        "nombre_persona" => $value["di_primernombre"] . ' ' . $value["di_primerapellido"] . ' ' . $value["di_segundoapellido"],
                        "email" => $value["direccion_email"],
                        "onboarding_numero_cuenta" => $value["onboarding_numero_cuenta"],
                        "terceros_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["terceros_fecha"]),
                        "terceros_estado" => $value["terceros_estado"],
                        "tipo_cuenta" => $this->mfunciones_generales->GetValorCatalogoDB($value['tipo_cuenta'], 'tipo_cuenta'),
                        "rechazado_envia" => 1
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

            $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
            $data['nombre_region'] = $lista_region->region_nombres_texto;
            
            // Listado de los Documentos
            $arrDocs = $this->mfunciones_logica->ObtenerDocumentosTercerosCriterio(1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDocs);
            
            $data["arrDocs"] = $arrDocs;
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
            
            $data["arrRespuesta"] = $lst_resultado;

            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
            
            $this->load->view('terceros/view_supervisor_completar', $data);
        }
    }
    
    public function SolicitudCompletar_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        $completado_envia = $this->input->post('rechazado_envia', TRUE);
        $completado_texto = $this->input->post('rechazado_texto', TRUE);
        
        $codigo_usuario = $_SESSION["session_informacion"]["codigo"];
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if((int)$estructura_id == 0)
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $arrResultado = $this->mfunciones_generales->DatosTercerosEmail($estructura_id);
        
        if(!$arrResultado)
        {   
            $texto_error = 'El codigo de registro no existe o ya no se encuentra en el estado para ser observado, por favor verifique el codigo.';
            
            js_error_div_javascript($texto_error);
            exit();
        }
        
        $texto_error = 'El registro se encuentra en el estado ' . $this->mfunciones_generales->GetValorCatalogo($arrResultado[0]["terceros_estado_codigo"], 'terceros_estado') . ' por lo que no puede ser Completado, por favor verifique el codigo.';

        if((int)$arrResultado[0]["terceros_estado_codigo"] != 2)
        {
            js_error_div_javascript($texto_error);
            exit();
        }
        
        // Se envía el correo electrónico sólo si la opción fue seleccionada
        
        $lista_documentos = '';
        
        if($completado_envia == 1)
        {
            $arrDocumentos = $this->input->post('documento_list', TRUE);

            // En el caso que el array este vacio se muestra el mensaje de error
            if (!isset($arrDocumentos[0])) 
            {
                $lst_Documentos[0] = array();
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
                    
                    $lista_documentos .= $value . ',';
                }
            }
        }
            // +++++ Se guarda el PDF del Contrato
            
            $contrato_generado = $this->mfunciones_generales->GeneraContrato_terceros($arrResultado, $nombre_usuario, $fecha_actual);
        
            if($contrato_generado == FALSE)
            {
                js_error_div_javascript('No pudo generarse el PDF del contrato. Por favor verifique que los datos y firma digitalizada sean correctos y vuelva a intentarlo.');
                exit();
            }
            
            // +++++ Se guarda el PDF del Contrato
            
        // Se guarda el registro
        
        $this->mfunciones_logica->CompletarSolicitudTerceros($completado_envia, $completado_texto, $lista_documentos, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id);
        
        if($completado_envia == 1)
        {
            $arrDatos = $this->mfunciones_generales->DatosTercerosEmail($estructura_id);
            $this->mfunciones_generales->EnviarCorreo('terceros_completado', $arrDatos[0]['terceros_email'], $arrDatos[0]['terceros_nombre'], $arrDatos, $lst_Documentos);
        }
        
        // Notificar Proceso Onboarding     0=No Regionalizado      1=Si Regionalizado
        $this->mfunciones_generales->NotificacionEtapaTerceros($estructura_id, 11, 1);
        
        // FUNCION PARA OBTENER EL CÓDIGO DE PROSPECTO EN BASE AL CODIGO TERCERO
        $codigo_prospecto = $this->mfunciones_generales->ObtenerProspectoTerceros($estructura_id);
        
        // Se registra para las Etapas Onboarding   Pasa a: Bandeja Onboarding Agencia   Etapa Nueva     Etapa Actual
        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 11, 9, $nombre_usuario, $fecha_actual, 0);
        
        js_invocacion_javascript('alert("Se realizó la acción correctamente. Se redireccionará a la bandeja.");');
        
        $this->Solicitud_Ver();
    }
    
    public function AuxSolicitudCompletar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // 0=Insert    1=Update

        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            $codigo = $this->input->post('codigo', TRUE);
            $estado = $this->input->post('estado', TRUE);

            $arrResultado = $this->mfunciones_logica->ObtenerSolicitudTerceros($codigo, $estado);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0]))
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) {
                    $item_valor = array(

                        "terceros_id" => $value["terceros_id"],
                        "tipo_persona_id_codigo" => $value["tipo_persona_id"],
                        "tipo_persona_id_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["tipo_persona_id"], 'tipo_persona'),
                        "nombre_persona" => $value["di_primernombre"] . ' ' . $value["di_primerapellido"] . ' ' . $value["di_segundoapellido"],
                        "email" => $value["direccion_email"],
                        "onboarding_numero_cuenta" => $value["onboarding_numero_cuenta"],
                        "terceros_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["terceros_fecha"]),
                        "terceros_estado" => $value["terceros_estado"],
                        "tipo_cuenta" => $this->mfunciones_generales->GetValorCatalogoDB($value['tipo_cuenta'], 'tipo_cuenta'),
                        "rechazado_envia" => 1
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

            $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
            $data['nombre_region'] = $lista_region->region_nombres_texto;
            
            // Listado de los Documentos
            $arrDocs = $this->mfunciones_logica->ObtenerDocumentosTercerosCriterio(1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDocs);
            
            $data["arrDocs"] = $arrDocs;
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
            
            $data["arrRespuesta"] = $lst_resultado;

            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
            
            $this->load->view('terceros/view_aux_supervisor_completar', $data);
        }
    }
    
    public function AuxSolicitudCompletar_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        $completado_envia = $this->input->post('rechazado_envia', TRUE);
        $completado_texto = $this->input->post('rechazado_texto', TRUE);
        
        $nombre_contrato = $this->input->post('nombre_contrato', TRUE);
        
        $codigo_usuario = $_SESSION["session_informacion"]["codigo"];
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        $_SESSION['auxiliar_bandera_upload'] = 2;
        $_SESSION['auxiliar_bandera_upload_url'] = 'Afiliador/Supervisor/Ver';
        
        if((int)$estructura_id == 0)
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $arrResultado = $this->mfunciones_generales->DatosTercerosEmail($estructura_id);
        
        if(!$arrResultado)
        {   
            $texto_error = 'El codigo de registro no existe o ya no se encuentra en el estado para ser observado, por favor verifique el codigo.';
            
            //js_error_div_javascript($texto_error);
            redirect($this->config->base_url());
            exit();
        }
        
        $texto_error = 'El registro se encuentra en el estado ' . $this->mfunciones_generales->GetValorCatalogo($arrResultado[0]["terceros_estado_codigo"], 'terceros_estado') . ' por lo que no puede ser Completado, por favor verifique el codigo.';

        if($arrResultado[0]["terceros_estado_codigo"] != 2)
        {
            //js_error_div_javascript($texto_error);
            redirect($this->config->base_url());
            exit();
        }
        
        if($nombre_contrato == '')
        {
            //js_invocacion_javascript('alert("Por favor debe Adjuntar el PDF del Contrato para poder continuar.");');
            redirect($this->config->base_url());
            exit();
        }
        
        // Se envía el correo electrónico sólo si la opción fue seleccionada
        
        $lista_documentos = '';
        
        if($completado_envia == 1)
        {
            $arrDocumentos = $this->input->post('documento_list', TRUE);

            // En el caso que el array este vacio se muestra el mensaje de error
            if (!isset($arrDocumentos[0])) 
            {
                $lst_Documentos[0] = array();
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
                    
                    $lista_documentos .= $value . ',';
                }
            }
        }
        
            // +++++ Se guarda el PDF del Contrato
            
            // Paso 1.1: Se verifica si existe el directorio del Prospecto, caso contrario crear el directorio
            $path = RUTA_TERCEROS . 'ter_' . $estructura_id;
            if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
            {
                mkdir($path, 0755, TRUE);
                // Se crea el archivo html para evitar ataques de directorio
                $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($path . '/index.html', $cuerpo_html);
            }
            
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
            
            $documento_pdf = str_replace(".pdf", "", strtolower($nombre_contrato));
            $documento_pdf = $this->mfunciones_generales->TextoNoAcentoNoEspacios($documento_pdf) . '.pdf';

            $mi_archivo = 'documento_pdf';
            
            $config['upload_path'] = $path;
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
            
            $data['uploadSuccess'] = $this->upload->data();
            
            $nombre_documento = $documento_pdf;

            $codigo_documento = 13; // Es el Contrato Onboarding
            
            $this->mfunciones_logica->InsertarDocumentoTercero($estructura_id, $codigo_documento, $nombre_documento, $nombre_usuario, $fecha_actual);
        
            // +++++ Se guarda el PDF del Contrato
            
        // Se guarda el registro
        
        $this->mfunciones_logica->CompletarSolicitudTerceros($completado_envia, $completado_texto, $lista_documentos, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id);
        
        if($completado_envia == 1)
        {
            // Se obtiene los datos del registro
            $arrDatos = $this->mfunciones_generales->DatosTercerosEmail($estructura_id);
            
            $this->mfunciones_generales->EnviarCorreo('terceros_completado', $arrDatos[0]['terceros_email'], $arrDatos[0]['terceros_nombre'], $arrDatos, $lst_Documentos);
        }
        
        // Notificar Proceso Onboarding     0=No Regionalizado      1=Si Regionalizado
        $this->mfunciones_generales->NotificacionEtapaTerceros($estructura_id, 11, 1);
        
        // FUNCION PARA OBTENER EL CÓDIGO DE PROSPECTO EN BASE AL CODIGO TERCERO
        $codigo_prospecto = $this->mfunciones_generales->ObtenerProspectoTerceros($estructura_id);
        
        // Se registra para las Etapas Onboarding   Pasa a: Bandeja Onboarding Agencia   Etapa Nueva     Etapa Actual
        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 11, 9, $nombre_usuario, $fecha_actual, 0);
        
        // AUXILIARMENTE SE ENVIA UN FLAG
        
        $_SESSION['auxiliar_bandera_upload'] = 1;
        $_SESSION['auxiliar_bandera_upload_url'] = 'Afiliador/Supervisor/Ver';
        
        redirect($this->config->base_url());
    }
}
?>