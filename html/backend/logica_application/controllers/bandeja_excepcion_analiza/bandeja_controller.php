<?php
/**
 * @file 
 * Codigo que implementa el controlador para la gestión de Prospectos
 * @brief BANDEJA DE LA INSTANCIA
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para la gestión de Prospectos - EXCEPCIÓN - ANALIZA Y APRUEBA O RECHAZA
 * @brief BANDEJA DE LA INSTANCIA
 * @class Conf_catalogo_controller 
 */
class Bandeja_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 28;
        
        // Código de la Etapa Propio de la Bandeja
        protected $codigo_etapa = 10;

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

        // Bandeja Evaluación Legal
        
        $filtro = 'p.prospecto_etapa=' . $this->codigo_etapa . ' AND p.prospecto_rechazado=0 AND p.prospecto_consolidado=1 AND p.prospecto_observado_app=0 AND p.prospecto_excepcion!=3';
        
        $arrResultado = $this->mfunciones_logica->ListadoBandejasProspecto($filtro);
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
                    "empresa_categoria_codigo" => $value["empresa_categoria_codigo"],
                    "empresa_categoria_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["empresa_categoria"], 'empresa_categoria'),
                    "fecha_derivada_etapa" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["fecha_derivada_etapa"]),
                    "empresa_nombre" => $value["empresa_nombre"],
                    "tiempo_etapa" => $this->mfunciones_generales->TiempoEtapaProspecto($value["fecha_derivada_etapa"], $this->codigo_etapa),
                    "prospecto_excepcion_codigo" => $value["prospecto_excepcion"],
                    "prospecto_excepcion_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["prospecto_excepcion"], 'excepcion_estado'),
                    "prospecto_observado" => $value["prospecto_observado"]
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
            
            $arrResumen = $this->mfunciones_generales->TiempoEtapaResumen($lst_resultado);
            
            // Obtener el Tiempo asignado de la etapa
            $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa($this->codigo_etapa);
            $tiempo_etapa_asignado = $arrEtapa[0]['etapa_tiempo'];
        }
        else 
        {
            $lst_resultado[0] = $arrResultado;
            $arrResumen[0] = $arrResultado;
            $tiempo_etapa_asignado = 0;
        }
        
        // Se establece una variable de SESSION para determinar en que bandeja se encuentra el usuario y al Observar el documento, regresar allí
        
        $_SESSION['direccion_bandeja_actual'] = 'Excepcion/Analisis/Ver';
        $_SESSION['dato_etapa_actual'] = $this->codigo_etapa;
        
        $data["arrRespuesta"] = $lst_resultado;
        $data["arrResumen"] = $arrResumen;
        $data["tiempo_etapa_asignado"] = $tiempo_etapa_asignado;
        
        $this->load->view('bandeja_excepcion_analiza/view_bandeja_ver', $data);
    }
    
    public function Analisis_Form() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
                
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // Se captura el valor
            $codigo_prospecto = $this->input->post('codigo', TRUE);
            
            // Datos del Prospecto
            $DatosProspecto = $this->mfunciones_generales->GetDatosEmpresaCorreo($codigo_prospecto);

            if (!isset($DatosProspecto[0])) 
            {
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            }
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

            $data["arrRespuesta"] = $DatosProspecto;

            $this->load->view('bandeja_excepcion_analiza/view_analiza_form', $data);
        }
    }
    
    public function Analisis_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_POST['estructura_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // Se capturan los datos

            $codigo_prospecto = $this->input->post('estructura_id', TRUE);
            
            $opcion_seleccion = $this->input->post('opcion_seleccion', TRUE);
            
            $excepcion_detalle = $this->input->post('excepcion_detalle', TRUE);
            
            $nombre_usuario = $_SESSION["session_informacion"]["login"];
            $fecha_actual = date('Y-m-d H:i:s');

            $error = 0;
            
            if((int)$codigo_prospecto == 0 || $opcion_seleccion == "")
            {
                $error = 1;
            }

            if((int) $opcion_seleccion == 1 && $excepcion_detalle == '')
            {
                $error = 1;
            }
            
            if($error != 0)
            {
                $_SESSION['auxiliar_bandera_upload'] = 2;
                $_SESSION['auxiliar_bandera_upload_url'] = 'Excepcion/Analisis/Ver';

                redirect($this->config->base_url());
            }
            
            // Paso 1: Se obtiene el estado y etapa actual del prospecto
                
            // Detalle del Prospecto
            $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($codigo_prospecto);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            // Se continua siempre y cuando el prospecto no haya sido rechazado
            if($arrResultado3[0]['prospecto_etapa'] != 15)
            {
                // Dependiendo de la recomendación      1 = Sigue     2 = Rechaza

                if($opcion_seleccion == 1)
                {
                    // SE APRUEBA LA EXCEPCIÓN
                    
                    $_SESSION['auxiliar_bandera_upload'] = 2;
                    $_SESSION['auxiliar_bandera_upload_url'] = 'Excepcion/Analisis/Ver';
                    
                    // Se sube el Acta en PDF
                    
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

                    $documento_nombre = 'Acta Reunión Excepción';
                    
                    // Nombre del Arhivo

                    $documento_pdf = 'afn_' . $codigo_prospecto . '_' . $this->mfunciones_generales->TextoNoAcentoNoEspacios($documento_nombre) . "_" . date('Y-m-d_H_i_s') . '.pdf';

                    $mi_archivo = 'documento_pdf';
                    $config['upload_path'] = $path = RUTA_PROSPECTOS . 'afn_' . $codigo_prospecto;
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
                    
                    
                    // Se registra la acción de la Excepción    1=Excepción Generada        2=Excepción Aprobada        3=Excepción Rechazada
                    $accion_excepcion = 2;
                    $this->mfunciones_logica->AccionExcepcionProspectoPDF($accion_excepcion, $documento_pdf, $codigo_prospecto);
                    
                    // Se registra el detalle/justificación de la Excepción en su tabla
                    $this->mfunciones_logica->InsertarExcepcionProspecto($codigo_prospecto, $this->codigo_etapa, $excepcion_detalle, $_SESSION["session_informacion"]["codigo"], $nombre_usuario, $fecha_actual);
                    
                    $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 2, $this->codigo_etapa, $nombre_usuario, $fecha_actual, 2);
                    /***  REGISTRAR SEGUIMIENTO ***/
                    $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, 2, 1, 'Excepción - Aprobar Excepción (reingresa al flujo)', $nombre_usuario, $fecha_actual);
                    
                    // Enviar correo del Aprobacion de la Excepción

                        $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa(2);

                        if (isset($arrEtapa[0]))
                        {
                            foreach ($arrEtapa as $key1 => $value1) 
                            {
                                $rol = $value1['rol_codigo'];

                                $arrResultado4 = $this->mfunciones_logica->ObtenerDetalleDatosUsuario(2, $rol);
                                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado4);

                                if (isset($arrResultado4[0])) 
                                {

                                    foreach ($arrResultado4 as $key => $value) 
                                    {
                                        $destinatario_nombre = $value['usuario_nombres'] . ' ' . $value['usuario_app'] . ' ' . $value['usuario_apm'];
                                        $destinatario_correo = $value['usuario_email'];

                                        // SE PROCEDE CON EL ENVÍO DE CORREO ELECTRÓNICO
                                        $correo_enviado = $this->mfunciones_generales->EnviarCorreo('aprobar_excepcion', $destinatario_correo, $destinatario_nombre, $codigo_prospecto, 0);
                                    }
                                }                            
                            }
                        }                  
                }

                if($opcion_seleccion == 2)
                {
                    // Se registra la acción de la Excepción    1=Excepción Generada        2=Excepción Aprobada        3=Excepción Rechazada
                    $accion_excepcion = 3;
                    $this->mfunciones_logica->AccionExcepcionProspecto($accion_excepcion, $codigo_prospecto);
                    
                    // Se procede a actualizar el prospecto para registrar la revisión de Antecedentes                        
                    $this->mfunciones_logica->RechazarProspecto($nombre_usuario, $fecha_actual, $codigo_prospecto);

                    $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 15, $this->codigo_etapa, $nombre_usuario, $fecha_actual, 2);

                    /***  REGISTRAR SEGUIMIENTO ***/
                    $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, 15, 7, 'Excepción - Rechazó la Afiliación', $nombre_usuario, $fecha_actual);

                    /* Se Envía a la APP la Notificación por Push   tipo_notificacion, $tipo_visita, $codigo_visita */
                    $this->mfunciones_generales->EnviarNotificacionPush(5, 1, $codigo_prospecto);

                    // Enviar correo del rechazo del prospecto

                        $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa(15);

                        if (isset($arrEtapa[0]))
                        {
                            foreach ($arrEtapa as $key1 => $value1) 
                            {
                                $rol = $value1['rol_codigo'];

                                $arrResultado4 = $this->mfunciones_logica->ObtenerDetalleDatosUsuario(2, $rol);
                                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado4);

                                if (isset($arrResultado4[0])) 
                                {

                                    foreach ($arrResultado4 as $key => $value) 
                                    {
                                        $destinatario_nombre = $value['usuario_nombres'] . ' ' . $value['usuario_app'] . ' ' . $value['usuario_apm'];
                                        $destinatario_correo = $value['usuario_email'];

                                        // SE PROCEDE CON EL ENVÍO DE CORREO ELECTRÓNICO
                                        $correo_enviado = $this->mfunciones_generales->EnviarCorreo('notificar_rechazo', $destinatario_correo, $destinatario_nombre, $codigo_prospecto, 0);
                                    }
                                }                            
                            }
                        }
                }
                
                // AUXILIARMENTE SE ENVIA UN FLAG
        
                $_SESSION['auxiliar_bandera_upload'] = 1;
                $_SESSION['auxiliar_bandera_upload_url'] = 'Excepcion/Analisis/Ver';

                redirect($this->config->base_url());
                
            }
        }
    }
}
?>