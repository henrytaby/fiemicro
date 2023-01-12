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
    
    public function SolicitudDetalle() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estado = 0;
        
        if(isset($_POST['codigo']))
        {
            $estado = $this->input->post('estado', TRUE);
            
            $codigo = $this->input->post('codigo', TRUE);
        }
        
        $arrResultado = $this->mfunciones_logica->ObtenerDetalleSolicitudTerceros($codigo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0]))
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                $item_valor = array(
                    
                    "terceros_id" => $value["terceros_id"],
                    "tipo_persona_id_codigo" => $value["tipo_persona_id"],
                    "tipo_persona_id_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["tipo_persona_id"], 'tipo_persona'),
                    "afiliador_id" => $value["afiliador_id"],
                    "codigo_agencia_fie" => $value["codigo_agencia_fie"],
                    "agente_codigo" => $value["agente_codigo"],
                    "agente_nombre" => $value["agente_nombre"],
                    "agente_agencia" => $value["estructura_regional_nombre"],
                    "onboarding_numero_cuenta" => $value["onboarding_numero_cuenta"],
                    "terceros_fecha" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["terceros_fecha"]),
                    "terceros_fecha_plano" => $value["terceros_fecha"],
                    "terceros_estado" => $value["terceros_estado"],
                    "terceros_observacion" => $value["terceros_observacion"],
                    "terceros_rekognition" => $value["terceros_rekognition"],
                    "terceros_rekognition_similarity" => number_format($value["terceros_rekognition_similarity"], 2, ',', '.'),
                    "rechazado" => $value["rechazado"],
                    "rechazado_fecha" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["rechazado_fecha"]),
                    "rechazado_usuario" => $value["rechazado_usuario"],
                    "rechazado_envia" => $value["rechazado_envia"],
                    "rechazado_texto" => $value["rechazado_texto"],
                    "rechazado_docs_enviados" => $value["rechazado_docs_enviados"],
                    "aprobado" => $value["aprobado"],
                    "aprobado_fecha" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["aprobado_fecha"]),
                    "aprobado_usuario" => $value["aprobado_usuario"],
                    "cobis" => $value["cobis"],
                    "cobis_fecha" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["cobis_fecha"]),
                    "cobis_usuario" => $value["cobis_usuario"],
                    "completado" => $value["completado"],
                    "completado_fecha" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["completado_fecha"]),
                    "completado_usuario" => $value["completado_usuario"],
                    "completado_envia" => $value["completado_envia"],
                    "completado_texto" => $value["completado_texto"],
                    "completado_docs_enviados" => $value["completado_docs_enviados"],
                    
                    "entregado" => $value["entregado"],
                    "entregado_fecha" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["entregado_fecha"]),
                    "entregado_fecha_plano" => $value["entregado_fecha"],
                    "entregado_usuario" => $value["entregado_usuario"],
                    "entregado_texto" => $value["entregado_texto"],
                    
                    "tercero_asistencia" => $value["tercero_asistencia"],
                    "tipo_cuenta" => $this->mfunciones_generales->GetValorCatalogoDB($value['tipo_cuenta'], 'tipo_cuenta'),
                    
                    // EMPIEZA DATOS FIE
                    
                    "terceros_geo1" => $value["coordenadas_geo_dom"],
                    "terceros_geo2" => $value["coordenadas_geo_trab"],
                    
                    "monto_inicial" => number_format($value["monto_inicial"], 2, ',', '.'),
                    'direccion_email' => $value['direccion_email'],
                    'coordenadas_geo_dom' => $value['coordenadas_geo_dom'],
                    'coordenadas_geo_trab' => $value['coordenadas_geo_trab'],
                    'envio' => $this->mfunciones_generales->GetValorCatalogo($value['envio'], 'tercero_envio'),
                    'cI_numeroraiz' => $value['cI_numeroraiz'],
                    'cI_complemento' => $value['cI_complemento'],
                    'cI_lugar_emisionoextension' => $this->mfunciones_generales->GetValorCatalogoDB($value['cI_lugar_emisionoextension'], 'cI_lugar_emisionoextension'),
                    'cI_confirmacion_id' => $value['cI_confirmacion_id'],
                    'di_primernombre' => $value['di_primernombre'],
                    'di_segundo_otrosnombres' => $value['di_segundo_otrosnombres'],
                    'di_primerapellido' => $value['di_primerapellido'],
                    'di_segundoapellido' => $value['di_segundoapellido'],
                    'di_fecha_nacimiento' => $this->mfunciones_generales->getFormatoFechaD_M_Y($value['di_fecha_nacimiento']),
                    'di_fecha_vencimiento' => $this->mfunciones_generales->getFormatoFechaD_M_Y($value['di_fecha_vencimiento']),
                    'di_indefinido' => $this->mfunciones_generales->GetValorCatalogo($value['di_indefinido'], 'si_no'),
                    'di_genero' => $this->mfunciones_generales->GetValorCatalogoDB($value['di_genero'], 'di_genero'),
                    
                    'di_estadocivil_codigo' => $value['di_estadocivil'],
                    
                    'di_estadocivil' => $this->mfunciones_generales->GetValorCatalogoDB($value['di_estadocivil'], 'di_estadocivil'),
                    'di_apellido_casada' => $value['di_apellido_casada'],
                    'dd_profesion' => $this->mfunciones_generales->GetValorCatalogoDB($value['dd_profesion'], 'dd_profesion'),
                    'dd_nivel_estudios' => $this->mfunciones_generales->GetValorCatalogoDB($value['dd_nivel_estudios'], 'dd_nivel_estudios'),
                    'dd_dependientes' => $value['dd_dependientes'],
                    'dd_proposito_rel_comercial' => $this->mfunciones_generales->GetValorCatalogoDB($value['dd_proposito_rel_comercial'], 'dd_proposito_rel_comercial'),
                    'dec_ingresos_mensuales' => $this->mfunciones_generales->GetValorCatalogoDB($value['dec_ingresos_mensuales'], 'dec_ingresos_mensuales'),
                    'dec_nivel_egresos' => $this->mfunciones_generales->GetValorCatalogoDB($value['dec_nivel_egresos'], 'dec_nivel_egresos'),
                    'dir_tipo_direccion' => $this->mfunciones_generales->GetValorCatalogoDB($value['dir_tipo_direccion'], 'dir_tipo_direccion'),
                    'dir_departamento' => $this->mfunciones_generales->GetValorCatalogoDB($value['dir_departamento'], 'dir_departamento'),
                    'dir_provincia' => $this->mfunciones_generales->GetValorCatalogoDB($value['dir_provincia'], 'dir_provincia'),
                    'dir_localidad_ciudad' => $this->mfunciones_generales->GetValorCatalogoDB($value['dir_localidad_ciudad'], 'dir_localidad_ciudad'),
                    'dir_barrio_zona_uv' => $this->mfunciones_generales->GetValorCatalogoDB($value['dir_barrio_zona_uv'], 'dir_barrio_zona_uv'),
                    'dir_ubicacionreferencial' => $value['dir_ubicacionreferencial'],
                    'dir_av_calle_pasaje' => $value['dir_av_calle_pasaje'],
                    'dir_edif_cond_urb' => $value['dir_edif_cond_urb'],
                    'dir_numero' => $value['dir_numero'],
                    
                    'dir_departamento_neg' => $this->mfunciones_generales->GetValorCatalogoDB($value['dir_departamento_neg'], 'dir_departamento'),
                    'dir_provincia_neg' => $this->mfunciones_generales->GetValorCatalogoDB($value['dir_provincia_neg'], 'dir_provincia'),
                    'dir_localidad_ciudad_neg' => $this->mfunciones_generales->GetValorCatalogoDB($value['dir_localidad_ciudad_neg'], 'dir_localidad_ciudad'),
                    'dir_barrio_zona_uv_neg' => $this->mfunciones_generales->GetValorCatalogoDB($value['dir_barrio_zona_uv_neg'], 'dir_barrio_zona_uv'),
                    'dir_av_calle_pasaje_neg' => $value['dir_av_calle_pasaje_neg'],
                    'dir_edif_cond_urb_neg' => $value['dir_edif_cond_urb_neg'],
                    'dir_numero_neg' => $value['dir_numero_neg'],
                    
                    'dir_tipo_telefono' => $value['dir_tipo_telefono'],
                    'dir_notelefonico' => $value['dir_notelefonico'],
                    'ae_sector_economico_codigo' => $value['ae_sector_economico'],
                    'ae_sector_economico' => $this->mfunciones_generales->GetValorCatalogoDB($value['ae_sector_economico'], 'ae_sector_economico'),
                    'ae_actividad_fie' => $this->mfunciones_generales->GetValorCatalogoDB($value['ae_actividad_fie'], 'ae_actividad_fie'),
                    'ae_ambiente' => $this->mfunciones_generales->GetValorCatalogoDB($value['ae_ambiente'], 'ae_ambiente'),
                    'emp_nombre_empresa' => $value['emp_nombre_empresa'],
                    'emp_direccion_trabajo' => $value['emp_direccion_trabajo'],
                    'emp_telefono_faxtrabaj' => $value['emp_telefono_faxtrabaj'],
                    'emp_tipo_empresa' => $this->mfunciones_generales->GetValorCatalogoDB($value['emp_tipo_empresa'], 'emp_tipo_empresa'),
                    'emp_antiguedad_empresa' => $value['emp_antiguedad_empresa'],
                    'emp_codigo_actividad' => $this->mfunciones_generales->GetValorCatalogoDB($value['emp_codigo_actividad'], 'emp_codigo_actividad'),
                    'emp_descripcion_cargo' => $value['emp_descripcion_cargo'],
                    'emp_fecha_ingreso' => $this->mfunciones_generales->getFormatoFechaD_M_Y($value['emp_fecha_ingreso']),
                    'rp_nombres' => $value['rp_nombres'],
                    'rp_primer_apellido' => $value['rp_primer_apellido'],
                    'rp_segundo_apellido' => $value['rp_segundo_apellido'],
                    'rp_direccion' => $value['rp_direccion'],
                    'rp_notelefonicos' => $value['rp_notelefonicos'],
                    'rp_nexo_cliente' => $this->mfunciones_generales->GetValorCatalogoDB($value['rp_nexo_cliente'], 'rp_nexo_cliente'),
                    'con_primer_nombre' => $value['con_primer_nombre'],
                    'con_segundo_nombre' => $value['con_segundo_nombre'],
                    'con_primera_pellido' => $value['con_primera_pellido'],
                    'con_segundoa_pellido' => $value['con_segundoa_pellido'],
                    'con_acteconomica_principal' => $this->mfunciones_generales->GetValorCatalogoDB($value['con_acteconomica_principal'], 'ae_actividad_fie'),
                    'ddc_ciudadania_usa' => $this->mfunciones_generales->GetValorCatalogo($value['ddc_ciudadania_usa'], 'si_no'),
                    'ddc_motivo_permanencia_usa' => $value['ddc_motivo_permanencia_usa'],
                    
                    'ws_segip_flag_verificacion' => $value['ws_segip_flag_verificacion'],
                    'ws_segip_codigo_resultado' => $this->mfunciones_generales->GetValorCatalogoDB($value['ws_segip_codigo_resultado'], 'segip_codigo_respuesta'),
                    'ws_cobis_codigo_resultado' => $this->mfunciones_generales->GetValorCatalogoDB($value['ws_cobis_codigo_resultado'], 'segip_codigo_respuesta'),
                    'ws_ocr_codigo_resultado' => $this->mfunciones_generales->GetValorCatalogoDB($value['ws_ocr_codigo_resultado'], 'segip_codigo_respuesta'),
                    'ws_selfie_codigo_resultado' => $this->mfunciones_generales->GetValorCatalogoDB($value['ws_selfie_codigo_resultado'], 'segip_codigo_respuesta'),
                    
                    'ws_ocr_name_valor' => $value['ws_ocr_name_valor'],
                    'ws_ocr_name_similar' => $value['ws_ocr_name_similar'],
                    
                    'segip_operador_resultado' => $this->mfunciones_generales->GetValorCatalogo($value['segip_operador_resultado'], 'segip_operador_resultado'),
                    'segip_operador_fecha' => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["segip_operador_fecha"]),
                    'segip_operador_usuario' => $this->mfunciones_generales->getNombreUsuario($value['segip_operador_usuario']),
                    'segip_operador_texto' => nl2br($value["segip_operador_texto"]),
                    
                    'notificar_cierre' => $value['notificar_cierre'],
                    'notificar_cierre_fecha' => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["notificar_cierre_fecha"]),
                    'notificar_cierre_usuario' => $value['notificar_cierre_usuario'],
                    'notificar_cierre_causa' => $value['notificar_cierre_causa'],
                    'cuenta_cerrada' => $value['cuenta_cerrada'],
                    'cuenta_cerrada_fecha' => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["cuenta_cerrada_fecha"]),
                    'cuenta_cerrada_usuario' => $value['cuenta_cerrada_usuario'],
                    'cuenta_cerrada_causa' => $value['cuenta_cerrada_causa'],
                    'cuenta_cerrada_envia' => $value['cuenta_cerrada_envia'],
                    
                    // Flujo COBIS
                    
                    "f_cobis_flujo" => $value["f_cobis_flujo"],
                    "f_cobis_actual_etapa" => $value["f_cobis_actual_etapa"],
                    "f_cobis_actual_etapa_detalle" => $this->mfunciones_generales->GetValorCatalogo($value['f_cobis_actual_etapa'], 'flujo_cobis_etapa'),
                    "f_cobis_actual_intento" => ($value["f_cobis_actual_intento"]==0 ? 'Ninguno' : $value["f_cobis_actual_intento"]),
                    "f_cobis_actual_usuario" => $this->mfunciones_generales->getNombreUsuario($value["f_cobis_actual_usuario"]),
                    "f_cobis_actual_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["f_cobis_actual_fecha"]),
                    "f_cobis_codigo" => $value["f_cobis_codigo"],
                    "f_cobis_excepcion" => $value["f_cobis_excepcion"],
                    "f_cobis_excepcion_detalle" =>  $this->mfunciones_generales->GetValorCatalogo($value['f_cobis_excepcion'], 'si_no'),
                    "f_cobis_excepcion_motivo" => $this->mfunciones_generales->GetValorCatalogoDB($value['f_cobis_excepcion_motivo'], 'motivo_flujo_cobis'),
                    "f_cobis_excepcion_motivo_texto" => $value["f_cobis_excepcion_motivo_texto"],
                    "f_cobis_flag_rechazado" => $value["f_cobis_flag_rechazado"],
                    "f_cobis_flag_rechazado_detalle" => $this->mfunciones_generales->GetValorCatalogo($value['f_cobis_flag_rechazado'], 'si_no'),
                    "f_cobis_tracking" => $value["f_cobis_tracking"],
                    
                );
                $lst_resultado[$i] = $item_valor;
                
                $i++;
            }
        }
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }
        
        $estado_texto = $this->mfunciones_generales->GetValorCatalogo($estado, 'estado_solicitud');
        
        $data["estado"] = $estado;
        $data["estado_texto"] = $estado_texto;
        
        $data["arrRespuesta"] = $lst_resultado;
        
        $this->load->view('terceros/view_solicitud_detalle', $data);
        
    }
    
    public function DocumentosTerceros_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // Se captura el valor
            $estructura_id = $this->input->post('codigo', TRUE);
            
            if(!$this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
            {
                js_error_div_javascript($this->lang->line('NoAutorizadoPerfil'));
                exit();
            }
            else 
            {   
                $arrResultado = $this->mfunciones_logica->ObtenerDocumentosTercerosCriterio(0);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "documento_id" => $value["documento_id"],
                            "documento_detalle" => $value["documento_nombre"],
                            "documento_digitalizado" => $this->mfunciones_generales->GetInfoTercerosDigitalizado($estructura_id, $value["documento_id"], 'existe')
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
                
                $data['estructura_id'] = $estructura_id;

                // Variable que indica donde volver
                $_SESSION['funcion_ver_documento'] = 'Ajax_CargarAccion_DocumentoProspectoReporte';
                
                
                // Datos Solicitud Terceros
                $data["arrTerceros"] = $this->mfunciones_generales->DatosTercerosEmail($estructura_id);
                
                $this->load->view('terceros/view_documento_ver_unico', $data);
            }
        }
    }
    
    public function DocumentosTercerosHistorico_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');

        if(!isset($_POST['codigo_documento']) || !isset($_POST['codigo_prospecto']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // Se captura el valor
            $documento_codigo = $this->input->post('codigo_documento', TRUE);
            $prospecto_codigo = $this->input->post('codigo_prospecto', TRUE);
            
            if(!$this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
            {
                js_error_div_javascript($this->lang->line('NoAutorizadoPerfil'));
                exit();
            }
            else
            {
                // Datos del Documento
                $arrResultado1 = $arrResultado = $this->mfunciones_logica->ObtenerDocumento($documento_codigo);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
                
                if (!isset($arrResultado1[0])) 
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }
                
                $documento_nombre = $arrResultado1[0]['documento_nombre'];
                
                $arrResultado2 = $this->mfunciones_logica->ListaDocumentosDigitalizarTerceros($prospecto_codigo, $documento_codigo);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);
                
                if (isset($arrResultado2[0])) 
                {
                    $i = 0;

                    $lst_resultado[0] = array();
                    
                    foreach ($arrResultado2 as $key => $value) 
                    {
                        
                        $ruta = RUTA_TERCEROS;
                        $documento = $value['terceros_carpeta'] . '/' . $value['terceros_carpeta'] . '_' . $value['terceros_documento_pdf'];
                        
                        $path = $ruta . $documento;
                        
                        if(file_exists($path))
                        {
                            $item_valor = array(
                                "prospecto_documento_id" => $value["terceros_documento_id"],
                                "documento_fecha" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["accion_fecha"]),
                            );
                            $lst_resultado[$i] = $item_valor;

                            $i++;
                        }
                    }
                }
                else 
                {
                    $lst_resultado[0] = $arrResultado2;
                }
                
                $data["arrRespuesta"] = $lst_resultado;
                
                // Datos Solicitud Terceros
                $data["arrTerceros"] = $this->mfunciones_generales->DatosTercerosEmail($prospecto_codigo);
                
                $data["documento_codigo"] = $documento_codigo;
                $data["documento_nombre"] = $documento_nombre;                
                $data['prospecto_codigo'] = $prospecto_codigo;

                $this->load->view('terceros/view_documento_historico_ver', $data);
            }
        }
        
    }
    
    public function SolicitudRechazar() {
        
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
                        "terceros_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["terceros_fecha"]),
                        "terceros_estado" => $value["terceros_estado"],
                        "tipo_cuenta" => $this->mfunciones_generales->GetValorCatalogoDB($value['tipo_cuenta'], 'tipo_cuenta'),
                        "rechazado_envia" => 1,
                        "ws_segip_flag_verificacion" => $value["ws_segip_flag_verificacion"],
                        "segip_operador_resultado" => $value["segip_operador_resultado"],
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }

            // Listado de los Documentos
            $arrDocs = $this->mfunciones_logica->ObtenerDocumentosTercerosCriterio(1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDocs);
            
            $data["arrDocs"] = $arrDocs;
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
            
            $data["arrRespuesta"] = $lst_resultado;

            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
            
            $this->load->view('terceros/view_solicitud_rechazar', $data);
        }
    }    
    
    public function SolicitudRechazar_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        if(isset($_POST['tipo_rechazo']))
        {
            $tipo_rechazo = $this->input->post('tipo_rechazo', TRUE);
        }
        else
        {
            $tipo_rechazo = 3;
        }
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $solicitud_observacion = $this->input->post('solicitud_observacion', TRUE);
        
        $rechazado_envia = $this->input->post('rechazado_envia', TRUE);
        $rechazado_texto = $this->input->post('rechazado_texto', TRUE);
        
        $codigo_usuario = $_SESSION["session_informacion"]["codigo"];
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if((int)$estructura_id == 0 || $solicitud_observacion == "")
        {
            js_error_div_javascript('Por favor debe registrar la Justificación');
            exit();
        }
        
        $arrResultado = $this->mfunciones_generales->DatosTercerosEmail($estructura_id);
        
        if(!$arrResultado)
        {   
            $texto_error = 'El codigo de registro no existe o ya no se encuentra en el estado para ser observado, por favor verifique el codigo.';
            
            if (!isset($_POST['tipo_rechazo']))
            {
                js_invocacion_javascript('alert("' . $texto_error . '"); Ajax_CargarOpcionMenu("Menu/Principal");');
                exit();
            }
            else
            {
                js_error_div_javascript($texto_error);
                exit();
            }
        }
        
        $texto_error = 'El registro se encuentra en el estado ' . $this->mfunciones_generales->GetValorCatalogo($arrResultado[0]["terceros_estado_codigo"], 'terceros_estado') . ' por lo que no puede ser Rechazado, por favor verifique el codigo.';

        if($arrResultado[0]["terceros_estado_codigo"] > 1)
        {
            if($arrResultado[0]["terceros_estado_codigo"] != 15)
            {
            
                if(!isset($_POST['tipo_rechazo']))
                {
                    js_invocacion_javascript('alert("' . $texto_error . '"); Ajax_CargarOpcionMenu("Menu/Principal");');
                    exit();
                }
                else
                {
                    //js_error_div_javascript($texto_error);
                    //exit();
                }
            }
        }
        
        // Se envía el correo electrónico sólo si la opción fue seleccionada
        
        $lista_documentos = '';
        
        if($rechazado_envia == 1)
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
        
        // Se guarda el registro
        
        $this->mfunciones_logica->RechazarSolicitudTerceros($solicitud_observacion, $tipo_rechazo, $rechazado_envia, $rechazado_texto, $lista_documentos, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id);
        
        if($rechazado_envia == 1)
        {
            // Se obtiene los datos del registro
            $arrDatos = $this->mfunciones_generales->DatosTercerosEmail($estructura_id);
            
            $this->mfunciones_generales->EnviarCorreo('terceros_rechazo', $arrDatos[0]['terceros_email'], $arrDatos[0]['terceros_nombre'], $arrDatos, $lst_Documentos);
        }
        
        if(!isset($_POST['tipo_rechazo']))
        {
            js_invocacion_javascript('alert("RPA: Se marco la observacion al registro y se actualizo el estado.");');
        }
        
        // Notificar Proceso Onboarding     0=No Regionalizado      1=Si Regionalizado
        $this->mfunciones_generales->NotificacionEtapaTerceros($estructura_id, 10, 1);
        
        // FUNCION PARA OBTENER EL CÓDIGO DE PROSPECTO EN BASE AL CODIGO TERCERO
        $codigo_prospecto = $this->mfunciones_generales->ObtenerProspectoTerceros($estructura_id);
        // Detalle del Prospecto
        $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($codigo_prospecto);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
        
        // Se registra para las Etapas Onboarding   Pasa a: Notificar Rechazo Onboarding   Etapa Nueva     Etapa Actual
        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 10, $arrResultado3[0]['prospecto_etapa'], $nombre_usuario, $fecha_actual, 0);
        
        $direccion_bandeja = 'Menu/Principal';

        if(isset($_SESSION['direccion_bandeja_actual']))
        {
            $direccion_bandeja = $_SESSION['direccion_bandeja_actual'];
        }
        
        js_invocacion_javascript('Ajax_CargarOpcionMenu("' . $direccion_bandeja . '");');
    }
    
    
    public function CambiarAgencia() {
        
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

            $arrResultado = $this->mfunciones_logica->ObtenerDetalleSolicitudTerceros($codigo);
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
                        "terceros_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["terceros_fecha"]),
                        "terceros_estado" => $value["terceros_estado"],
                        "tipo_cuenta" => $this->mfunciones_generales->GetValorCatalogoDB($value['tipo_cuenta'], 'tipo_cuenta'),
                        'envio' => $this->mfunciones_generales->GetValorCatalogo($value['envio'], 'tercero_envio'),
                        "codigo_agencia_fie_codigo" => $value["codigo_agencia_fie"],
                        "codigo_agencia_fie" => $this->mfunciones_generales->ObtenerNombreRegionCodigo($value["codigo_agencia_fie"])
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

            // Listado de Agencias (Regiones)
            $arrAgencias = $this->mfunciones_logica->ObtenerDatosRegional(-1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrAgencias);
            
            $data["arrAgencias"] = $arrAgencias;
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
            
            $data["arrRespuesta"] = $lst_resultado;

            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
            
            $this->load->view('terceros/view_agencia_cambiar', $data);
        }
    }
    
    public function CambiarAgencia_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        if(!isset($_POST['estructura_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $codigo_agencia_fie = $this->input->post('codigo_agencia_fie', TRUE);
        
        $codigo_usuario = $_SESSION["session_informacion"]["codigo"];
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if((int)$estructura_id == 0 || (int)$codigo_agencia_fie <= 0)
        {
            js_error_div_javascript('Por favor debe seleccionar una Agencia');
            exit();
        }
        
        $arrResultado = $this->mfunciones_generales->DatosTercerosEmail($estructura_id);
        
        if(!$arrResultado)
        {   
            $texto_error = 'El codigo de registro no existe, por favor verifique el codigo.';
            
             js_error_div_javascript($texto_error);
            exit();
        }
        
        // Se guarda el registro
        
        $this->mfunciones_logica->CambiarAgenciaTerceros($codigo_agencia_fie, $nombre_usuario, $fecha_actual, $estructura_id);
        
        // Notificar Proceso Onboarding     0=No Regionalizado      1=Si Regionalizado
        $this->mfunciones_generales->NotificacionEtapaTerceros($estructura_id, 11, 1);
        
        
        $direccion_bandeja = 'Menu/Principal';

        if(isset($_SESSION['direccion_bandeja_actual']))
        {
            $direccion_bandeja = $_SESSION['direccion_bandeja_actual'];
        }
        
        js_invocacion_javascript('Ajax_CargarOpcionMenu("' . $direccion_bandeja . '");');
    }
    
    public function Agencia_Mapa() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('googlemaps');

        
        if(!isset($_GET['5ot4d_arutp4c']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $capturado = $this->input->get('5ot4d_arutp4c', TRUE);
        
        $arrParametros = $this->mfunciones_generales->getEnlaceRegistro($capturado);
        
        if(!isset($arrParametros[0])) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $estructura_id = $arrParametros[0]['estructura_id'];
        $codigo_terceros = $arrParametros[0]['codigo_ejecutivo'];
        $tipo_registro = $arrParametros[0]['tipo_registro'];

        $arrTerceros = $this->mfunciones_generales->DatosTercerosEmail($estructura_id);
        
        //Marcadores
        
        if($arrTerceros[0]['envio'] == 1)
        {
            $centro = $arrTerceros[0]['coordenadas_geo_dom'];
        }
        else
        {
            $centro = $arrTerceros[0]['coordenadas_geo_trab'];
        }
        
        if(!preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $centro))
        {
            $centro = GEO_FIE;
        }
        
        $config['center'] = $centro;
        $config['zoom'] = '13';

        // Parámetros de la Key de Google Maps
        $arrResultado3 = $this->mfunciones_logica->ObtenerDatosConf_General();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
        if (isset($arrResultado3[0])) 
        {                
            $config['apiKey'] = $arrResultado3[0]['conf_general_key_google'];
        }
        
        $this->googlemaps->initialize($config);

        if(preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $arrTerceros[0]['coordenadas_geo_dom']))
        {
            $marker = array();
            $marker['position'] = $arrTerceros[0]['coordenadas_geo_dom'];
            $marker['infowindow_content'] = '<div style="text-align: center;"> <img src="' . MARCADOR_LOGO . '" style="width: 30px;" align="top" /> <span style="font-size: 20px; font-weight: bold;"> ' . $this->lang->line('SolicitudTitulo_zona') . ' </span> <br /> <span> Domicilio </span> </div>';
            $marker['icon'] = MARCADOR_SOLICITUD;
            $marker['animation'] = 'DROP';        
            $marker['draggable'] = false;
            $this->googlemaps->add_marker($marker);
        }
        
        if(preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $arrTerceros[0]['coordenadas_geo_trab']))
        {
            $marker = array();
            $marker['position'] = $arrTerceros[0]['coordenadas_geo_trab'];
            $marker['infowindow_content'] = '<div style="text-align: center;"> <img src="' . MARCADOR_LOGO . '" style="width: 30px;" align="top" /> <span style="font-size: 20px; font-weight: bold;"> ' . $this->lang->line('SolicitudTitulo_zona') . ' </span> <br /> <span> Trabajo </span> </div>';
            $marker['icon'] = MARCADOR_SOLICITUD;
            $marker['animation'] = 'DROP';        
            $marker['draggable'] = false;
            $this->googlemaps->add_marker($marker);
        }
        
        // Listado de Agencias (Regiones)
        $arrAgencias = $this->mfunciones_logica->ObtenerDatosRegional(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrAgencias);

        if (isset($arrAgencias[0])) 
        {
            foreach ($arrAgencias as $key => $value) 
            {
                if(preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $value['estructura_regional_geo']))
                {
                    //Marcadores        
                    $marker = array();
                    $marker['position'] = $value['estructura_regional_geo'];
                    $marker['infowindow_content'] = '<div style="text-align: center;"> <img src="' . MARCADOR_LOGO . '" style="width: 30px;" align="top" /> <span style="font-size: 20px; font-weight: bold;"> ' . $value['estructura_regional_nombre'] . ' </span> <br /> <span> Agencia FIE (' . $this->mfunciones_generales->GetValorCatalogo($value['estructura_regional_estado'], 'agencia_estado') . ') </span> <br /><br /> <span style="color: #006699; font-weight: bold; cursor: pointer;" onclick="seleccionar_agencia(' . $value['estructura_regional_id'] . ');"> Seleccionar Agencia </span></div>';
                    $marker['icon'] = MARCADOR_ZONA;
                    $marker['animation'] = 'DROP';        
                    $marker['draggable'] = false;
                    $this->googlemaps->add_marker($marker);
                }
            }
        }

        $data['map'] = $this->googlemaps->create_map();

        $this->load->view('terceros/view_agencia_mapa', $data);
    }
    
    public function AgenciasRegiones() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(isset($_POST['codigo']))
        {
            $estructura_id = $this->input->post('codigo', TRUE);
        }
        else
        {
            // Se captura el valor
            $estructura_id = $_SESSION["session_informacion"]["codigo"];
        }
        
        if($estructura_id == '')
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $lista_region = $this->mfunciones_generales->getUsuarioRegion($estructura_id);
        $data['region'] = $lista_region->region;
        
        $this->load->view('terceros/view_regiones_detalle', $data);
    }
    
    public function Tracking_flujo() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // 0=Insert    1=Update

        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            $codigo = $this->input->post('codigo', TRUE);

            $arrResultado = $this->mfunciones_logica->ObtenerTrackingFlujo($codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (!isset($arrResultado[0]))
            {
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            }

            $data['terceros_id'] = $codigo;
            $data['f_cobis_tracking'] = $arrResultado[0]['f_cobis_tracking'];
            $data['f_cobis_flujo'] = $arrResultado[0]['f_cobis_flujo'];
            
            $this->load->view('terceros/view_flujo_cobis_tracking', $data);
        }
    }
}
?>