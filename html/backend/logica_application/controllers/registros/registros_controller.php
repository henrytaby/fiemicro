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
class Registros_controller extends CI_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	//protected $codigo_menu_acceso = 42;

    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
    
    public function VistaMapa() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        
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
        
        $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
        
        $data['conf_general_key_google'] = $arrConf[0]['conf_general_key_google'];
        
        $arrResultado = $this->mfunciones_logica->ObtenerDetalleSolicitudTerceros($codigo_terceros);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        // Detalle del Prospecto
        $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($estructura_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
        
        // Si realizó Check-In y Domicilio sigue con los valores por defecto, se cambia a la GEO del Check-In
        if($arrResultado3[0]['prospecto_checkin'] == 1 && $arrResultado[0]['coordenadas_geo_dom'] == GEO_FIE)
        {
            if(!preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $arrResultado3[0]['prospecto_checkin_geo']))
            {
                $arrResultado[0]['coordenadas_geo_dom'] = GEO_FIE;
            }
            else
            {
                $arrResultado[0]['coordenadas_geo_dom'] = $arrResultado3[0]['prospecto_checkin_geo'];
            }
        }
        
        // Si realizó Check-In y Trabajo sigue con los valores por defecto, se cambia a la GEO del Check-In
        if($arrResultado3[0]['prospecto_checkin'] == 1 && $arrResultado[0]['coordenadas_geo_trab'] == GEO_FIE)
        {
            if(!preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $arrResultado3[0]['prospecto_checkin_geo']))
            {
                $arrResultado[0]['coordenadas_geo_trab'] = GEO_FIE;
            }
            else
            {
                $arrResultado[0]['coordenadas_geo_trab'] = $arrResultado3[0]['prospecto_checkin_geo'];
            }
        }
        
        $data['coordenadas_geo_dom'] = $arrResultado[0]['coordenadas_geo_dom'];
        $data['coordenadas_geo_trab'] = $arrResultado[0]['coordenadas_geo_trab'];
        
        $this->load->view('registros/' . $tipo_registro, $data);
    }
    
    public function VistaMapaNorm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        
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
        
        $geo_registro = $arrParametros[0]['tipo_registro'];
        
        $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
        
        $data['conf_general_key_google'] = $arrConf[0]['conf_general_key_google'];
        
        $coordenada_geo = $this->mfunciones_microcreditos->validateGEO($geo_registro);
        
        $data['coordenada_geo'] = $coordenada_geo;
        $data['geo_registro'] = 'rd_geolocalizacion';
        
        $this->load->view('registros/view_geo_solcred', $data);
    }
    
    public function VistaMapaSolCred() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        
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
        $geo_registro = $arrParametros[0]['tipo_registro'];
        
        $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
        
        $data['conf_general_key_google'] = $arrConf[0]['conf_general_key_google'];
        
        $arrResultado = $this->mfunciones_microcreditos->ListadoSolCred_GEOs($estructura_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        switch (str_replace(' ', '', (string)$geo_registro)) {
            case 'sol_geolocalizacion':
                $geo_registro_depto = 'sol_dir_departamento';
                break;

            case 'sol_geolocalizacion_dom':
                $geo_registro_depto = 'sol_dir_departamento_dom';
                break;
            
            case 'sol_con_geolocalizacion':
                $geo_registro_depto = 'sol_con_dir_departamento';
                break;
            
            case 'sol_con_geolocalizacion_dom':
                $geo_registro_depto = 'sol_con_dir_departamento_dom';
                break;
            
            // Actividad Secundaria
            case 'sol_geolocalizacion_sec':
                $geo_registro_depto = 'sol_dir_departamento_sec';
                break;
            
            default:
                $geo_registro_depto = 'sol_dir_departamento';
                break;
        }
        
        // Si realizo Check-In y no se registró aún la GEO, se asigna el valor del check-in
        if(preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $arrResultado[0]['sol_checkin_geo']))
        {
            $arrResultado[0][$geo_registro] = $this->mfunciones_microcreditos->setAuxGEO($arrResultado[0][$geo_registro], $arrResultado[0]['sol_checkin_geo']);
        }
        
        $coordenada_geo = $this->mfunciones_microcreditos->validateGEO($arrResultado[0][$geo_registro], $arrResultado[0][$geo_registro_depto]);
        
        $data['coordenada_geo'] = $coordenada_geo;
        $data['geo_registro'] = $geo_registro;
        
        $this->load->view('registros/view_geo_solcred', $data);
    }
    
    public function VistaPrincipal() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        
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
        $codigo_ejecutivo = $arrParametros[0]['codigo_ejecutivo'];
        $tipo_registro = $arrParametros[0]['tipo_registro'];
        
        switch ($tipo_registro) {
            case 'nuevo_lead':

                $data['estructura_id'] = $codigo_ejecutivo;
                $data['codigo_vista'] = 'lead_nuevo';

                break;
            
            case 'unidad_familiar':

                $data['estructura_id'] = $estructura_id;
                $data['codigo_vista'] = 'lead_registros';
                
                break;

            case 'nuevo_onboarding':

                $data['estructura_id'] = $codigo_ejecutivo;
                $data['codigo_vista'] = 'nuevo_onboarding';

                break;
            
            case 'nuevo_solcredito':
                
                $data['estructura_id'] = $codigo_ejecutivo;
                $data['codigo_vista'] = 'nuevo_solcredito';

                break;
            
            case 'solicitud_credito':
                
                $data['estructura_id'] = $estructura_id;
                $data['codigo_vista'] = 'sol_registros';

                break;
            
            case 'reporte_funnel':
                
                $data['estructura_id'] = $codigo_ejecutivo;
                $data['codigo_vista'] = 'reporte_funnel';

                break;
            
            // Normalizador/Cobrador
            case 'nuevo_norm':
                
                $data['estructura_id'] = $codigo_ejecutivo;
                $data['codigo_vista'] = 'nuevo_norm';

                break;
            
            case 'normalizador':
                
                $data['estructura_id'] = $estructura_id;
                $data['codigo_vista'] = 'norm_registros';

                break;
            
            default:
                
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            
                break;
        }
        
        $this->load->view('registros/view_principal_ver', $data);
    }
    
    public function ReporteFunnel() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_microcreditos');
        $this->load->model('mfunciones_dashboard');
        
        // Se captura los valores
        
        $sel_oficial = $this->input->post('estructura_id', TRUE);
        $funnel_tabla_app = $this->input->post('funnel_tabla_app', TRUE);
        
        $sel_departamento = '';
        $sel_agencia = '';
        $campoFiltroFechaDesde = '';
        $campoFiltroFechaHasta = '';
        
        $filtro = $this->mfunciones_dashboard->ArmarFiltro($sel_departamento, $sel_agencia, $sel_oficial, $campoFiltroFechaDesde, $campoFiltroFechaHasta, 1);
        
        if((int)$funnel_tabla_app == 0)
        {
            $resultado = $this->mfunciones_dashboard->GenerarFunnel($filtro);

            $data['sel_oficial'] = $sel_oficial;
            $data['funnel'] = $resultado->funnel;
            $data['chartFunnel'] = $resultado->chartFunnel;

            $this->load->view('dashboard/view_dashboard_app_resultado', $data);
        }
        else
        {
            $etapa = str_replace('&', '', $this->input->post('etapa', TRUE));
            
            $etapa_nombre = '';
            if((int)$etapa == -1)
            {
                $etapa = '2, 3, 4, 5';

                $etapa_nombre = 'EN PROCESO';
            }
            else
            {
                $arrEtapas = $this->mfunciones_logica->ObtenerDatosFlujo($etapa, 1);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEtapas);

                if (isset($arrEtapas[0])) 
                {
                    $etapa_nombre = $arrEtapas[0]['etapa_nombre'];
                }

                if((int)$etapa == 22 || (int)$etapa == 6)
                {
                    $etapa = '6, 22';
                }
            }

            $filtro = 'AND etapa_id IN(' . $etapa . ')' . $filtro;

            $lst_resultado = $this->mfunciones_dashboard->GenerarFunnelTabla($filtro);
            
            $data['arrRespuesta'] = $lst_resultado;
                
            $data['etapa_nombre'] = $etapa_nombre;

            $data['etapa_id'] = str_replace('&', '', $this->input->post('etapa', TRUE));

            $this->load->view('dashboard/view_dashboard_app_tabla', $data);
        }
    }
    
    /*** Normalizador/Cobrador - INICIO ***/
    
    public function Norm_VistaInicio() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        $this->load->model('mfunciones_cobranzas');
        
        // LLAMAR A LAS FUNCIONES PARA CARGAR LA DATA DE SOLICITUD DE CRÉDITO
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        $arrResultado = $this->mfunciones_cobranzas->DatosRegistroEmail($estructura_id);
        
        if (!isset($arrResultado[0])) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        // Visitas registradas
        $arrVisita = $this->mfunciones_cobranzas->getVisitasRegistro($estructura_id, -1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrVisita);
        
        if (isset($arrVisita[0]))
        {
            $i = 0;

            foreach ($arrVisita as $key => $value) {
                
                $check_geo = $this->mfunciones_microcreditos->validateGEO_simple($value['cv_checkin_geo']);
                
                $item_valor = array(
                    "cv_id" => $value["cv_id"],
                    "cv_fecha" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["cv_fecha"]),
                    "cv_resultado" => $this->mfunciones_generales->GetValorCatalogoDB($value["cv_resultado"], 'cobranzas_resultado_visita'),
                    "cv_fecha_compromiso" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y($value["cv_fecha_compromiso"]),
                    "cv_observaciones" => nl2br($value["cv_observaciones"]),
                    "cv_checkin" => ($value["cv_checkin"]==1 && $check_geo->lat!=0 ? 1 : 0),
                    "cv_checkin_fecha" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["cv_checkin_fecha"]),
                );
                $lst_resultado_visita[$i] = $item_valor;

                $i++;
            }
            
            $visitas_contador = $i;
        }
        else 
        {
            $lst_resultado_visita[0] = $arrVisita;
            
            $visitas_contador = 0;
        }
        
        $data["visitas_contador"] = $visitas_contador;
        $data["arrVisita"] = $lst_resultado_visita;
        $data["arrRespuesta"] = $arrResultado;
        
        $this->load->view('registros/view_norm_inicio', $data);
    }
    
    public function Norm_VistaNuevo() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_cobranzas');
        
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_cobranza');
        $this->formulario_logica_cobranza->DefinicionValidacionFormulario();

        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        $data["arrCajasHTML"] = $this->formulario_logica_cobranza->ConstruccionCajasFormulario(array());
        $data["strValidacionJqValidate"] = $this->formulario_logica_cobranza->GeneraValidacionJavaScript();
        
        $data['estructura_id'] = $estructura_id;
        
        $this->load->view('registros/view_nuevo_norm', $data);
    }
    
    public function NormNuevoGuardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        $this->load->model('mfunciones_cobranzas');
        
        // Es el código del Ejecutivo de Cuentas
        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        $accion_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'ejecutivo');
        $accion_fecha = date('Y-m-d H:i:s');

        $norm_primer_nombre = ucwords($this->input->post('norm_primer_nombre', TRUE));
        $norm_segundo_nombre = ucwords($this->input->post('norm_segundo_nombre', TRUE));
        $norm_primer_apellido = ucwords($this->input->post('norm_primer_apellido', TRUE));
        $norm_segundo_apellido = ucwords($this->input->post('norm_segundo_apellido', TRUE));
        $registro_num_proceso = ucwords($this->input->post('registro_num_proceso', TRUE));
        
        // Validación de campos

        $separador = '<br /> - ';
        $error_texto = '';
        
        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($norm_primer_nombre, 'string_corto', 1, 3, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('norm_primer_nombre') . '. ' . $aux_texto; }
        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($norm_segundo_nombre, 'string_corto', 1, 0, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('norm_segundo_nombre') . '. ' . $aux_texto; }
        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($norm_primer_apellido, 'string_corto', 1, 3, 30); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('norm_primer_apellido') . '. ' . $aux_texto; }
        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($norm_segundo_apellido, 'string_corto', 1, 0, 30); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('norm_segundo_apellido') . '. ' . $aux_texto; }
        
        if($error_texto != '')
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
            exit();
        }

        if($this->mfunciones_microcreditos->ValidarNumOperacion($registro_num_proceso))
        {
            js_error_div_javascript($this->lang->line('registro_num_proceso_error'));
            exit();
        }
        
        // Guardar en la DB

        $ejecutivo_id = $estructura_id;
        $afiliador_id = 1;
        $sol_asistencia = 1;
        
            // Obtener Agencia
            $arrUsuario = $this->mfunciones_logica->getUsuarioCriterio('ejecutivo', $ejecutivo_id);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrUsuario);
        
        $codigo_agencia_fie = $arrUsuario[0]['estructura_regional_id'];
        $codigo_usuario = $arrUsuario[0]['usuario_id'];
        
        $codigo_registro = $this->mfunciones_cobranzas->NuevoNorm(
                                            $ejecutivo_id,
                                            $codigo_agencia_fie,
                                            $registro_num_proceso,
                
                                            $norm_primer_nombre,
                                            $norm_segundo_nombre,
                                            $norm_primer_apellido,
                                            $norm_segundo_apellido,
                
                                            $accion_fecha,
                                            $accion_usuario
                                            );
        
        // Se crea la carpeta de la solicitud
        
        $path = $this->lang->line('ruta_cobranzas') . 'reg_' . $codigo_registro;

        if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
        {
            mkdir($path, 0755, TRUE);
            // Se crea el archivo html para evitar ataques de directorio
            $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
            write_file($path . '/index.html', $cuerpo_html);
        }
        
        // Notificar Proceso Onboarding Normalizador/Cobrador     0=No Regionalizado      1=Si Regionalizado
        $this->mfunciones_cobranzas->NotificacionEtapaRegistro($codigo_registro, 25, 1);
        
        js_invocacion_javascript('$("div.modal-backdrop").remove();');
        
        $data['nombre_rubro'] = mb_strtoupper($this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app_singular'));
        $data['general_solicitante'] = $norm_primer_nombre . ' ' . $norm_segundo_nombre . ' ' . $norm_primer_apellido . ' ' . $norm_segundo_apellido;
        $data['estructura_id'] = $estructura_id;
        $this->load->view('registros/view_nuevo_norm_completado', $data);
    }
    
    /*** Normalizador/Cobrador - FIN ***/
    
    public function Sol_VistaNuevo() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_credito');
        $this->formulario_logica_credito->DefinicionValidacionFormulario();

        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        // Paso 1: Se pregunta si el prospecto depende de otro
        
        $data["arrCajasHTML"] = $this->formulario_logica_credito->ConstruccionCajasFormulario(array());
        $data["strValidacionJqValidate"] = $this->formulario_logica_credito->GeneraValidacionJavaScript();
        
        //$data["arrRespuesta"] = $lst_resultado;
        
        $data['estructura_id'] = $estructura_id;
        
        $this->load->view('registros/view_nuevo_solcred', $data);
    }
    
    public function SolPrepForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        
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
        
        $codigo_registro = $arrParametros[0]['estructura_id'];
        $tipo = $arrParametros[0]['tipo_registro'];
        $aux_method = (int)$arrParametros[0]['codigo_ejecutivo'];
        
        if($aux_method != 1 && $aux_method != 2)
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $arrResultado = $this->mfunciones_microcreditos->ListadoSolCred_GEOs($codigo_registro);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (!isset($arrResultado[0])) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $data['ota_key'] = $this->mfunciones_generales->setEnlaceRegistro($codigo_registro, $aux_method, $tipo);
        
        $data['sol_geolocalizacion'] = $this->mfunciones_microcreditos->validateGEO_simple($arrResultado[0]['sol_geolocalizacion']);
        $data['sol_geolocalizacion_dom'] = $this->mfunciones_microcreditos->validateGEO_simple($arrResultado[0]['sol_geolocalizacion_dom']);
        $data['sol_con_geolocalizacion'] = $this->mfunciones_microcreditos->validateGEO_simple($arrResultado[0]['sol_con_geolocalizacion']);
        $data['sol_con_geolocalizacion_dom'] = $this->mfunciones_microcreditos->validateGEO_simple($arrResultado[0]['sol_con_geolocalizacion_dom']);
        
        $this->load->view('solicitud_credito/view_formulario_plain_prepara', $data);
    }
    
    public function SolCredAuxMap() {
        
        $this->load->model('mfunciones_microcreditos');
        $this->load->model('mfunciones_generales');
        
        $ota_key = $this->input->post('ota_key', TRUE);
        
        $arrParametros = $this->mfunciones_microcreditos->getEnlaceRegistroAux(str_replace('5ot4d_arutp4c=', '', $ota_key));
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrParametros);
        
        if(isset($arrParametros[0])) 
        {
            $codigo_registro = $arrParametros[0]['estructura_id'];
            
            $aux_campo = $this->input->post('aux_campo', TRUE);
            $base64 = $this->input->post('base64', TRUE);

            $this->mfunciones_microcreditos->update_sol_img_mapas_single($codigo_registro, $aux_campo . '_img', $this->mfunciones_microcreditos->ConvertToJPEG($base64));
        }
    }
    
    public function SolCredFormulario() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        
        if(!isset($_POST['ota_key']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $ota_key = $this->input->post('ota_key', TRUE);
        $sol_geolocalizacion = $this->input->post('sol_geolocalizacion', TRUE);
        $sol_geolocalizacion_dom = $this->input->post('sol_geolocalizacion_dom', TRUE);
        $sol_con_geolocalizacion = $this->input->post('sol_con_geolocalizacion', TRUE);
        $sol_con_geolocalizacion_dom = $this->input->post('sol_con_geolocalizacion_dom', TRUE);

        $arrParametros = $this->mfunciones_generales->getEnlaceRegistro(str_replace('5ot4d_arutp4c=', '', $ota_key));

        if(!isset($arrParametros[0])) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }

        // Borrado de registros (keys) antiguos.
        $this->mfunciones_microcreditos->ClearOldKeys($arrParametros[0]['key_id']);

        $codigo_registro = $arrParametros[0]['estructura_id'];
        $tipo = $arrParametros[0]['tipo_registro'];
        $aux_method = (int)$arrParametros[0]['codigo_ejecutivo'];
        
        // $aux_method      1=get       2=post
        if($aux_method != 1 && $aux_method != 2)
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $arrAuxResultado = $this->mfunciones_microcreditos->ListadoSolCred_GEOs($codigo_registro);
        if (!isset($arrAuxResultado[0])) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        if($this->mfunciones_microcreditos->validateIMG_simple($sol_geolocalizacion) != '')
        {
            $this->mfunciones_microcreditos->update_sol_img_mapas_single($codigo_registro, 'sol_geolocalizacion' . '_img', $this->mfunciones_microcreditos->ConvertToJPEG($sol_geolocalizacion));
        }
        
        if($this->mfunciones_microcreditos->validateIMG_simple($sol_geolocalizacion_dom) != '')
        {
            $this->mfunciones_microcreditos->update_sol_img_mapas_single($codigo_registro, 'sol_geolocalizacion_dom' . '_img', $this->mfunciones_microcreditos->ConvertToJPEG($sol_geolocalizacion_dom));
        }
        
        if($this->mfunciones_microcreditos->validateIMG_simple($sol_con_geolocalizacion) != '')
        {
            $this->mfunciones_microcreditos->update_sol_img_mapas_single($codigo_registro, 'sol_con_geolocalizacion' . '_img', $this->mfunciones_microcreditos->ConvertToJPEG($sol_con_geolocalizacion));
        }
        
        $arrResultado = $this->mfunciones_microcreditos->ObtenerDetalleSolicitudCredito($codigo_registro);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (!isset($arrResultado[0])) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // Revisar todos los campos, remplazar espacio en blanco por vacío
            foreach ($arrResultado[0] as $key_aux => $value_aux)
            {
                if((string)$arrResultado[0][$key_aux] == ' ')
                {
                    $arrResultado[0][$key_aux] = '';
                }
                $arrResultado[0][$key_aux] = htmlspecialchars($arrResultado[0][$key_aux]);
            }
            
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                $item_valor = array(
                    
                    "agente_nombre" => $value["agente_nombre"],
                    "agente_agencia" => $value["estructura_regional_nombre"],
                    "agente_codigo" => $value["agente_codigo"],
                    
                    "estructura_regional_departamento" => $this->mfunciones_generales->GetValorCatalogoDB($value['estructura_regional_departamento'], 'dir_departamento'),
                    "estructura_regional_provincia" => $this->mfunciones_generales->GetValorCatalogoDB($value['estructura_regional_provincia'], 'dir_provincia'),
                    "estructura_regional_ciudad" => $this->mfunciones_generales->GetValorCatalogoDB($value['estructura_regional_ciudad'], 'dir_localidad_ciudad'),
                    
                    'codigo_agencia_fie' => $value['codigo_agencia_fie'],
                    
                    "sol_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["sol_fecha"]),
                    'sol_asignado' => $value['sol_asignado'],
                    
                    // EMPIEZA DATOS FIE
                    
                    'sol_ci' => $value['sol_ci'],
                    'sol_extension_codigo' => $value['sol_extension'],
                    'sol_complemento' => $value['sol_complemento'],
                    'sol_primer_nombre' => $value['sol_primer_nombre'],
                    'sol_segundo_nombre' => $value['sol_segundo_nombre'],
                    'sol_primer_apellido' => $value['sol_primer_apellido'],
                    'sol_segundo_apellido' => $value['sol_segundo_apellido'],
                    'sol_correo' => $value['sol_correo'],
                    'sol_cel' => $value['sol_cel'],
                    'sol_telefono' => $value['sol_telefono'],
                    'sol_fec_nac' => $this->mfunciones_generales->getFormatoFechaD_M_Y($value['sol_fec_nac']),
                    'sol_est_civil_codigo' => $value['sol_est_civil'],
                    'sol_est_civil' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_est_civil'], 'di_estadocivil'),
                    'sol_nit' => $value['sol_nit'],
                    'sol_cliente_codigo' => $value['sol_cliente'],
                    'sol_cliente' => $this->mfunciones_microcreditos->GetValorCatalogo($value['sol_cliente'], 'sol_cliente'),
                    'sol_dependencia' => $value['sol_dependencia'],
                    'sol_indepen_actividad' => $value['sol_indepen_actividad'],
                    'sol_indepen_ant_ano' => $value['sol_indepen_ant_ano'],
                    'sol_indepen_ant_mes' => $value['sol_indepen_ant_mes'],
                    'sol_indepen_horario_desde' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_indepen_horario_desde']),
                    'sol_indepen_horario_hasta' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_indepen_horario_hasta']),
                    'sol_indepen_horario_dias' => $value['sol_indepen_horario_dias'],
                    'sol_indepen_telefono' => $value['sol_indepen_telefono'],
                    'sol_depen_empresa' => $value['sol_depen_empresa'],
                    'sol_depen_actividad' => $value['sol_depen_actividad'],
                    'sol_depen_cargo' => $value['sol_depen_cargo'],
                    'sol_depen_ant_ano' => $value['sol_depen_ant_ano'],
                    'sol_depen_ant_mes' => $value['sol_depen_ant_mes'],
                    'sol_depen_horario_desde' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_depen_horario_desde']),
                    'sol_depen_horario_hasta' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_depen_horario_hasta']),
                    'sol_depen_horario_dias' => $value['sol_depen_horario_dias'],
                    'sol_depen_telefono' => $value['sol_depen_telefono'],
                    'sol_depen_tipo_contrato' => $value['sol_depen_tipo_contrato'],
                    'sol_monto' => $value['sol_monto'],
                    'sol_plazo' => number_format($value['sol_plazo'], 0, ',', '.'),
                    'sol_moneda' => $this->mfunciones_microcreditos->GetValorCatalogo($value['sol_moneda'], 'sol_moneda'),
                    'sol_moneda_codigo' => $value['sol_moneda'],
                    'sol_detalle' => $value['sol_detalle'],
                    'sol_dir_departamento' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_dir_departamento'], 'dir_departamento'),
                    'sol_dir_provincia' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_dir_provincia'], 'dir_provincia'),
                    'sol_dir_localidad_ciudad' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_dir_localidad_ciudad'], 'dir_localidad_ciudad'),
                    'sol_cod_barrio_codigo' => $value['sol_cod_barrio'],
                    'sol_cod_barrio' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_cod_barrio'], 'dir_barrio_zona_uv'),
                    'sol_direccion_trabajo' => $value['sol_direccion_trabajo'],
                    'sol_edificio_trabajo' => $value['sol_edificio_trabajo'],
                    'sol_numero_trabajo' => $value['sol_numero_trabajo'],
                    'sol_dir_referencia' => $value['sol_dir_referencia'],
                    'sol_geolocalizacion' => $value['sol_geolocalizacion'],
                    'sol_croquis' => $value['sol_croquis'],
                    'sol_trabajo_lugar' => $value['sol_trabajo_lugar'],
                    'sol_trabajo_lugar_otro' => $value['sol_trabajo_lugar_otro'],
                    'sol_trabajo_realiza' => $value['sol_trabajo_realiza'],
                    'sol_trabajo_realiza_otro' => $value['sol_trabajo_realiza_otro'],
                    'sol_dir_departamento_dom' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_dir_departamento_dom'], 'dir_departamento'),
                    'sol_dir_provincia_dom' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_dir_provincia_dom'], 'dir_provincia'),
                    'sol_dir_localidad_ciudad_dom' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_dir_localidad_ciudad_dom'], 'dir_localidad_ciudad'),
                    'sol_cod_barrio_dom_codigo' => $value['sol_cod_barrio_dom'],
                    'sol_cod_barrio_dom' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_cod_barrio_dom'], 'dir_barrio_zona_uv'),
                    'sol_direccion_dom' => $value['sol_direccion_dom'],
                    'sol_edificio_dom' => $value['sol_edificio_dom'],
                    'sol_numero_dom' => $value['sol_numero_dom'],
                    'sol_dir_referencia_dom' => $value['sol_dir_referencia_dom'],
                    'sol_geolocalizacion_dom' => $value['sol_geolocalizacion_dom'],
                    'sol_croquis_dom' => $value['sol_croquis_dom'],
                    'sol_dom_tipo' => $value['sol_dom_tipo'],
                    'sol_dom_tipo_otro' => $value['sol_dom_tipo_otro'],
                    'sol_conyugue' => $value['sol_conyugue'],
                    'sol_con_ci' => $value['sol_con_ci'],
                    'sol_con_extension_codigo' => $value['sol_con_extension'],
                    'sol_con_complemento' => $value['sol_con_complemento'],
                    'sol_con_primer_nombre' => $value['sol_con_primer_nombre'],
                    'sol_con_segundo_nombre' => $value['sol_con_segundo_nombre'],
                    'sol_con_primer_apellido' => $value['sol_con_primer_apellido'],
                    'sol_con_segundo_apellido' => $value['sol_con_segundo_apellido'],
                    'sol_con_correo' => $value['sol_con_correo'],
                    'sol_con_cel' => $value['sol_con_cel'],
                    'sol_con_telefono' => $value['sol_con_telefono'],
                    'sol_con_fec_nac' => $this->mfunciones_generales->getFormatoFechaD_M_Y($value['sol_con_fec_nac']),
                    'sol_con_est_civil_codigo' => $value['sol_con_est_civil'],
                    'sol_con_est_civil' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_est_civil'], 'di_estadocivil'),
                    'sol_con_nit' => $value['sol_con_nit'],
                    'sol_con_cliente_codigo' => $value['sol_con_cliente'],
                    'sol_con_dependencia' => $value['sol_con_dependencia'],
                    'sol_con_indepen_actividad' => $value['sol_con_indepen_actividad'],
                    'sol_con_indepen_ant_ano' => $value['sol_con_indepen_ant_ano'],
                    'sol_con_indepen_ant_mes' => $value['sol_con_indepen_ant_mes'],
                    'sol_con_indepen_horario_desde' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_con_indepen_horario_desde']),
                    'sol_con_indepen_horario_hasta' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_con_indepen_horario_hasta']),
                    'sol_con_indepen_horario_dias' => $value['sol_con_indepen_horario_dias'],
                    'sol_con_indepen_telefono' => $value['sol_con_indepen_telefono'],
                    'sol_con_depen_empresa' => $value['sol_con_depen_empresa'],
                    'sol_con_depen_actividad' => $value['sol_con_depen_actividad'],
                    'sol_con_depen_cargo' => $value['sol_con_depen_cargo'],
                    'sol_con_depen_ant_ano' => $value['sol_con_depen_ant_ano'],
                    'sol_con_depen_ant_mes' => $value['sol_con_depen_ant_mes'],
                    'sol_con_depen_horario_desde' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_con_depen_horario_desde']),
                    'sol_con_depen_horario_hasta' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_con_depen_horario_hasta']),
                    'sol_con_depen_horario_dias' => $value['sol_con_depen_horario_dias'],
                    'sol_con_depen_telefono' => $value['sol_con_depen_telefono'],
                    'sol_con_depen_tipo_contrato' => $value['sol_con_depen_tipo_contrato'],
                    'sol_con_dir_departamento' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_dir_departamento'], 'dir_departamento'),
                    'sol_con_dir_provincia' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_dir_provincia'], 'dir_provincia'),
                    'sol_con_dir_localidad_ciudad' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_dir_localidad_ciudad'], 'dir_localidad_ciudad'),
                    'sol_con_cod_barrio_codigo' => $value['sol_con_cod_barrio'],
                    'sol_con_cod_barrio' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_cod_barrio'], 'dir_barrio_zona_uv'),
                    'sol_con_direccion_trabajo' => $value['sol_con_direccion_trabajo'],
                    'sol_con_edificio_trabajo' => $value['sol_con_edificio_trabajo'],
                    'sol_con_numero_trabajo' => $value['sol_con_numero_trabajo'],
                    'sol_con_dir_referencia' => $value['sol_con_dir_referencia'],
                    'sol_con_geolocalizacion' => $value['sol_con_geolocalizacion'],
                    'sol_con_croquis' => $value['sol_con_croquis'],
                    'sol_con_trabajo_lugar' => $value['sol_con_trabajo_lugar'],
                    'sol_con_trabajo_lugar_otro' => $value['sol_con_trabajo_lugar_otro'],
                    'sol_con_trabajo_realiza' => $value['sol_con_trabajo_realiza'],
                    'sol_con_trabajo_realiza_otro' => $value['sol_con_trabajo_realiza_otro'],
                    'sol_con_trabajo_actividad_pertenece' => $value['sol_con_trabajo_actividad_pertenece'],
                    'sol_con_dir_departamento_dom' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_dir_departamento_dom'], 'dir_departamento'),
                    'sol_con_dir_provincia_dom' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_dir_provincia_dom'], 'dir_provincia'),
                    'sol_con_dir_localidad_ciudad_dom' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_dir_localidad_ciudad_dom'], 'dir_localidad_ciudad'),
                    'sol_con_cod_barrio_dom_codigo' => $value['sol_con_cod_barrio_dom'],
                    'sol_con_cod_barrio_dom' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_cod_barrio_dom'], 'dir_barrio_zona_uv'),
                    'sol_con_direccion_dom' => $value['sol_con_direccion_dom'],
                    'sol_con_edificio_dom' => $value['sol_con_edificio_dom'],
                    'sol_con_numero_dom' => $value['sol_con_numero_dom'],
                    'sol_con_dir_referencia_dom' => $value['sol_con_dir_referencia_dom'],
                    'sol_con_geolocalizacion_dom' => $value['sol_con_geolocalizacion_dom'],
                    'sol_con_croquis_dom' => $value['sol_con_croquis_dom'],
                    'sol_con_dom_tipo' => $value['sol_con_dom_tipo'],
                    'sol_con_dom_tipo_otro' => $value['sol_con_dom_tipo_otro'],
                    
                    'sol_geolocalizacion_img' => $value['sol_geolocalizacion_img'],
                    'sol_geolocalizacion_dom_img' => $value['sol_geolocalizacion_dom_img'],
                    'sol_con_geolocalizacion_img' => $value['sol_con_geolocalizacion_img'],
                    'sol_con_geolocalizacion_dom_img' => $value['sol_con_geolocalizacion_dom_img'],
                    
                    
                    'sol_desembolso_monto' => $value['sol_desembolso_monto'],
                );
                $lst_resultado[$i] = $item_valor;
                
                $i++;
            }
        }
        
        $data["arrRespuesta"] = $lst_resultado;
        
        // Una vez obtenidos los valores, se procede a borrar las imagenes de los mapas, ya que se generan en cada ocasión
        $this->mfunciones_microcreditos->update_erase_img_mapas_all($codigo_registro);
        
        switch ($tipo) {
            
            case 'pdf':
                
                $this->mfunciones_microcreditos->GeneraPDF_SinHeader('solicitud_credito/view_formulario_plain',$data, ($aux_method == 2 ? 'I' : 'D'));
                return;
                
            /*
            case 'excel':
                
                $this->mfunciones_generales->GeneraExcel('solicitud_credito/view_auditoria_resultado_plain',$data);
                return;
            */
                
            default:
                
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            
                break;
        }
    }
    
    public function SolCredNuevoGuardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        
        // Es el código del Ejecutivo de Cuentas
        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        $accion_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'ejecutivo');
        $accion_fecha = date('Y-m-d H:i:s');

        $sol_ci = $this->input->post('sol_ci', TRUE);
        $sol_complemento = $this->input->post('sol_complemento', TRUE);
        $sol_extension = $this->input->post('sol_extension', TRUE);
        $sol_primer_nombre = ucwords($this->input->post('sol_primer_nombre', TRUE));
        $sol_segundo_nombre = ucwords($this->input->post('sol_segundo_nombre', TRUE));
        $sol_primer_apellido = ucwords($this->input->post('sol_primer_apellido', TRUE));
        $sol_segundo_apellido = ucwords($this->input->post('sol_segundo_apellido', TRUE));
        $sol_correo = $this->input->post('sol_correo', TRUE);
        $sol_cel = $this->input->post('sol_cel', TRUE);
        $sol_telefono = $this->input->post('sol_telefono', TRUE);
        
        // Validación de campos

        $separador = '<br /> - ';
        $error_texto = '';
        
        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_ci, '', 1, 3, 10); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_ci') . '. ' . $aux_texto; }
        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_complemento, 'ci_complemento'); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_complemento') . '. ' . $aux_texto; }
        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_extension); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_extension') . '. ' . $aux_texto; }
        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_primer_nombre, 'string_corto', 1, 3, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_primer_nombre') . '. ' . $aux_texto; }
        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_segundo_nombre, 'string_corto', 1, 0, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_segundo_nombre') . '. ' . $aux_texto; }
        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_primer_apellido, 'string_corto', 1, 3, 30); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_primer_apellido') . '. ' . $aux_texto; }
        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_segundo_apellido, 'string_corto', 1, 0, 30); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_segundo_apellido') . '. ' . $aux_texto; }
        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_correo, 'email', 0, 0, 100); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_correo') . '. ' . $aux_texto; }
        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_cel, 'celular', 2, 8, 0); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_cel') . '. ' . $aux_texto; }
        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_telefono, '', 1, 0, 15); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_telefono') . '. ' . $aux_texto; }
        
        if($error_texto != '')
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
            exit();
        }

        // Guardar en la DB

        $ejecutivo_id = $estructura_id;
        $afiliador_id = 1;
        $sol_asistencia = 1;
        
            // Obtener Agencia
            $arrUsuario = $this->mfunciones_logica->getUsuarioCriterio('ejecutivo', $ejecutivo_id);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrUsuario);
        
        $codigo_agencia_fie = $arrUsuario[0]['estructura_regional_id'];
        $codigo_usuario = $arrUsuario[0]['usuario_id'];
        
        $codigo_registro = $this->mfunciones_microcreditos->NuevoSolCred(
                                            $ejecutivo_id,
                                            $afiliador_id,
                                            $sol_asistencia,
                                            $codigo_agencia_fie,
                                            
                                            $sol_ci,
                                            $sol_complemento,
                                            $sol_extension,
                                            $sol_primer_nombre,
                                            $sol_segundo_nombre,
                                            $sol_primer_apellido,
                                            $sol_segundo_apellido,
                                            $sol_correo,
                                            $sol_cel,
                                            $sol_telefono,
                
                                            $codigo_usuario,
                                            $accion_usuario,
                                            $accion_fecha
                                            );
        
        // Se crea la carpeta de la solicitud
        
        $path = RUTA_SOLCREDITOS . 'sol_' . $codigo_registro;

        if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
        {
            mkdir($path, 0755, TRUE);
            // Se crea el archivo html para evitar ataques de directorio
            $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
            write_file($path . '/index.html', $cuerpo_html);
        }
        
        js_invocacion_javascript('$("div.modal-backdrop").remove();');
        
        $data['nombre_rubro'] = 'SOLICITUD DE CRÉDITO';
        $data['general_solicitante'] = $sol_primer_nombre . ' ' . $sol_primer_apellido . ' ' . $sol_segundo_apellido;
        $data['estructura_id'] = $estructura_id;
        $this->load->view('registros/view_nuevo_solcred_completado', $data);
    }
    
    public function VistaNuevoOnboarding() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        $this->formulario_logica_general->DefinicionValidacionFormulario();

        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        // Paso 1: Se pregunta si el prospecto depende de otro
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        //$data["arrRespuesta"] = $lst_resultado;
        
        $data['estructura_id'] = $estructura_id;
        
        $this->load->view('registros/view_nuevo_onboarding', $data);
    }
    
    public function OnboardingGuardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Es el código del Ejecutivo de Cuentas
        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        $accion_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'ejecutivo');
        $accion_fecha = date('Y-m-d H:i:s');

        $tipo_cuenta = $this->input->post('tipo_cuenta', TRUE);
        
        $cI_confirmacion_id = $cI_numeroraiz = $this->input->post('cI_numeroraiz', TRUE);
        $cI_complemento = strtoupper($this->input->post('cI_complemento', TRUE));
        $cI_lugar_emisionoextension = $this->input->post('cI_lugar_emisionoextension', TRUE);
        $di_primernombre = ucwords($this->input->post('di_primernombre', TRUE));
        $di_segundo_otrosnombres = ucwords($this->input->post('di_segundo_otrosnombres', TRUE));
        $di_primerapellido = ucwords($this->input->post('di_primerapellido', TRUE));
        $di_segundoapellido = ucwords($this->input->post('di_segundoapellido', TRUE));
        
        $direccion_email = $this->input->post('direccion_email', TRUE);
        $dir_notelefonico = $this->input->post('dir_notelefonico', TRUE);
        
        // Validación de campos

        $separador = '<br /> - ';
        $error_texto = '';
        
        if($tipo_cuenta == '') { $error_texto .= $separador . $this->lang->line('tipo_cuenta'); }
        if($cI_numeroraiz == '' || strlen((string)$cI_numeroraiz) < 3) { $error_texto .= $separador . $this->lang->line('cI_numeroraiz'); }
        
        // Validacion del Complemento
        if($cI_complemento != '')
        {
            if(strlen((string)$cI_complemento) != 2)
            {
                $error_texto .= $separador . $this->lang->line('cI_complemento');
            }
            elseif(!preg_match('/^(?!\d+$)(?![a-zA-Z]+$)[a-zA-Z\d]{2}$/', $cI_complemento))
            {
                $error_texto .= $separador . 'El complemento no puede contener 2 letras juntas o 2 numeros juntos';
            }
        }
        
        if($di_primernombre == '' || strlen((string)$di_primernombre) < 3) { $error_texto .= $separador . $this->lang->line('di_primernombre'); }
        if($di_segundo_otrosnombres != '' && strlen((string)$di_segundo_otrosnombres) < 3) { $error_texto .= $separador . $this->lang->line('di_segundo_otrosnombres'); }
        if($di_primerapellido == '' || strlen((string)$di_primerapellido) < 3) { $error_texto .= $separador . $this->lang->line('di_primerapellido'); }
        if($di_segundoapellido != '' && strlen((string)$di_segundoapellido) < 3) { $error_texto .= $separador . $this->lang->line('di_segundoapellido'); }
        if($cI_lugar_emisionoextension == '-1') { $cI_lugar_emisionoextension = ''; /*$error_texto .= $separador . $this->lang->line('cI_lugar_emisionoextension');*/ }
        if($this->mfunciones_generales->VerificaCorreo($direccion_email) == false) { $error_texto .= $separador . $this->lang->line('direccion_email'); }
        
        if($dir_notelefonico == '' || strlen((string)$dir_notelefonico) != 8) { $error_texto .= $separador . $this->lang->line('dir_notelefonico'); }
        if($dir_notelefonico != '') { if($dir_notelefonico != '') { if((string)$dir_notelefonico[0] != '6' && (string)$dir_notelefonico[0] != '7') { $error_texto .= $separador . $this->lang->line('dir_notelefonico') . ' debe empezar con 6 o 7'; } }}
        
        if($error_texto != '')
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
            exit();
        }

        // Guardar en la DB

        $ejecutivo_id = $estructura_id;
        $tipo_persona_id = 5;
        $afiliador_id = 1;
        $tercero_asistencia = 1;
        
            // Obtener Agencia
            $arrUsuario = $this->mfunciones_logica->getUsuarioCriterio('ejecutivo', $ejecutivo_id);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrUsuario);
        
        $codigo_agencia_fie = $arrUsuario[0]['estructura_regional_id'];
        
        $codigo_terceros = $this->mfunciones_logica->NuevoOnboarding(
                                            $ejecutivo_id,
                                            $tipo_persona_id,
                                            $afiliador_id,
                                            $tercero_asistencia,
                                            $codigo_agencia_fie,
                                            $tipo_cuenta,
                                            $cI_confirmacion_id,
                                            $cI_numeroraiz,
                                            $cI_complemento,
                                            $cI_lugar_emisionoextension,
                                            $di_primernombre,
                                            $di_segundo_otrosnombres,
                                            $di_primerapellido,
                                            $di_segundoapellido,
                                            $dir_notelefonico,
                                            $direccion_email,
                                            $accion_usuario,
                                            $accion_fecha
                                            );

        // PASO 4: Se registra el Codigo Tercero en Prospectos para la relación
        
        $geo_prospecto = GEO_FIE;
        
        $codigo_prospecto = $this->mfunciones_logica->InsertarRelacionTercerosProspecto($ejecutivo_id, $tipo_persona_id, $codigo_terceros, $cI_numeroraiz . ' ' . $cI_complemento, $di_primernombre . ' ' . $di_primerapellido . ' ' . $di_segundoapellido, $dir_notelefonico, $direccion_email, $geo_prospecto, $accion_usuario, $accion_fecha);
        
        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 1, -1, $accion_usuario, $accion_fecha, 0);
        // Dervia a Visita y Registro
        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 3, 1, $accion_usuario, $accion_fecha, 0);
        
        $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, 1, 0, 'Registro de Nuevo Onboarding Asistido', $accion_usuario, $accion_fecha);

            // PASO 4.1: Se registra la fecha en el calendario del Ejecutivo de Cuentas
                
            $cal_visita_ini = $accion_fecha;
                $cal_visita_fin = new DateTime($accion_fecha);
            $cal_visita_fin->add(new DateInterval('PT' . 30 . 'M'));
                $cal_visita_fin = $cal_visita_fin->format('Y-m-d H:i:s');

            $this->mfunciones_logica->InsertarFechaCaendario($ejecutivo_id, $codigo_prospecto, 1, $cal_visita_ini, $cal_visita_fin, $accion_usuario, $accion_fecha);
        
        
        
        js_invocacion_javascript('$("div.modal-backdrop").remove();');
        
        $data['nombre_rubro'] = 'ONBOARDING';
        $data['general_solicitante'] = $di_primernombre . ' ' . $di_primerapellido . ' ' . $di_segundoapellido;
        $data['estructura_id'] = $estructura_id;
        $this->load->view('registros/view_nuevo_onboarding_completado', $data);
    }
    
    public function VistaNuevo() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        $this->formulario_logica_general->DefinicionValidacionFormulario();

        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        // Paso 1: Se pregunta si el prospecto depende de otro
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        //$data["arrRespuesta"] = $lst_resultado;
        
        $data['estructura_id'] = $estructura_id;
        
        $this->load->view('registros/view_nuevo_lead', $data);
    }
    
    public function NuevoGuardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Es el código del Ejecutivo de Cuentas
        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        $accion_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'ejecutivo');
        $accion_fecha = date('Y-m-d H:i:s');

        $camp_id = $this->input->post('camp_id', TRUE);

        $general_solicitante = ucwords($this->input->post('general_solicitante', TRUE));
        $general_telefono = $this->input->post('general_telefono', TRUE);
        //$general_email = $this->input->post('general_email', TRUE);
        $general_email = 'sinregistro@mail.com';
        
        $general_direccion = $this->input->post('general_direccion', TRUE);
        
        $general_actividad = $this->input->post('general_actividad', TRUE);
        $general_ci = $this->input->post('general_ci', TRUE);
        $general_ci_extension = $this->input->post('general_ci_extension', TRUE);
        $general_destino = $this->input->post('general_destino', TRUE);
        $general_interes = $this->input->post('general_interes', TRUE);

        $arrProductos = $this->input->post('producto_list', TRUE);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrProductos);

        // Validación de campos

        $separador = '<br /> - ';
        $error_texto = '';

        if($camp_id == '' || $camp_id <= 0) { $error_texto .= $separador . 'Debe seleccionar el Rubro'; }
        if($general_solicitante == '') { $error_texto .= $separador . $this->lang->line('general_solicitante'); }
        if($general_telefono == '') { $error_texto .= $separador . $this->lang->line('general_telefono'); }
        if($this->mfunciones_generales->VerificaCorreo($general_email) == false) { $error_texto .= $separador . $this->lang->line('general_email'); }

        if($general_actividad == '') { $error_texto .= $separador . $this->lang->line('general_actividad'); }
        //if($general_destino == '') { $error_texto .= $separador . $this->lang->line('general_destino'); }
        if($general_ci == '') { $error_texto .= $separador . $this->lang->line('general_ci'); }
        if($general_ci_extension == '') { $error_texto .= $separador . $this->lang->line('general_ci') . ' extensión'; }

        if($error_texto != '')
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
            exit();
        }

        // Guardar en la DB

        $nuevo_lead = $this->mfunciones_logica->NuevoLead(
                                            $camp_id,
                                            $estructura_id,
                                            $general_solicitante,
                                            $general_telefono,
                                            $general_email,
                                            $general_direccion,
                                            $general_actividad,
                                            $general_destino,
                                            $general_ci,
                                            $general_ci_extension,
                                            $general_interes,
                                            $accion_usuario,
                                            $accion_fecha
                                            );

        $this->mfunciones_generales->SeguimientoHitoProspecto($nuevo_lead, 1, -1, $accion_usuario, $accion_fecha, 0);
        $this->mfunciones_generales->SeguimientoHitoProspecto($nuevo_lead, 2, -1, $accion_usuario, $accion_fecha, 0);
            $nombre_rubro = $this->mfunciones_generales->GetValorCatalogo($camp_id, 'nombre_rubro');
        $this->mfunciones_logica->InsertSeguimientoProspecto($nuevo_lead, 1, 0, 'Registro de Cliente con Rubro ' . $nombre_rubro, $accion_usuario, $accion_fecha);

        // INSERTAR LOS PRODUCTOS SELECCIONADOS

        // 1. Se eliminan los servicios de la solicitud
        $this->mfunciones_logica->EliminarActividadesProspecto($nuevo_lead);

        // 2. Se registran los servicios seleccionados

        if (isset($arrProductos[0]))
        {
            foreach ($arrProductos as $key => $value) 
            {
                $this->mfunciones_logica->InsertarActividadesProspecto($nuevo_lead, $value, $accion_usuario, $accion_fecha);
            }
        }
        
        // CREACIÓN DEL DIRECTORIO Y CALENDARIO
        
        $path = RUTA_PROSPECTOS . 'afn_' . $nuevo_lead;

        if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
        {
                mkdir($path, 0755, TRUE);
        }
        
            // Se crea el archivo html para evitar ataques de directorio
            $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
            write_file($path . '/index.html', $cuerpo_html);
		
        // PASO 7: Se registra la fecha en el calendario del Ejecutivo de Cuentas
                
        $cal_visita_ini = $accion_fecha;
            $cal_visita_fin = new DateTime($accion_fecha);
        $cal_visita_fin->add(new DateInterval('PT' . 30 . 'M'));
            $cal_visita_fin = $cal_visita_fin->format('Y-m-d H:i:s');
        
        $this->mfunciones_logica->InsertarFechaCaendario($estructura_id, $nuevo_lead, 1, $cal_visita_ini, $cal_visita_fin, $accion_usuario, $accion_fecha);
        
        
        js_invocacion_javascript('$("div.modal-backdrop").remove();');
        
        $data['nombre_rubro'] = $nombre_rubro;
        $data['general_solicitante'] = $general_solicitante;
        $data['estructura_id'] = $estructura_id;
        $this->load->view('registros/view_nuevo_lead_completado', $data);
    }
    
    public function VistaInicio() {

        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_microcreditos');

        $estructura_id = $this->input->post('estructura_id', TRUE);

        

        // Paso 1: Se pregunta si el prospecto depende de otro
        
        $arrConsulta = $this->mfunciones_logica->GetProspectoDepende($estructura_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConsulta);
        
        if (isset($arrConsulta[0])) 
        {
            $estructura_id = $arrConsulta[0]['general_depende'];
        }
        
        // Listado de información del Prospecto y sus dependencias
        $arrResultado = $this->mfunciones_logica->select_info_dependencia($estructura_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        // ACTUALIZAR desembolso COBIS 23/02/2023 ------------------------------------------------------
        if ($arrResultado[0]['prospecto_num_proceso'] !="" && $arrResultado[0]['prospecto_num_proceso']!='0') {
            $ci_ext = trim($arrResultado[0]['general_ci']).$this->mfunciones_generales->GetValorCatalogo($arrResultado[0]['general_ci_extension'], 'extension_ci');
            $ci_ext = str_replace(".","",$ci_ext); $ci_ext = str_replace(" ","",$ci_ext);

            // obtener valor de cobis
            $result = $this->JdaJsonOperacionMethod($ci_ext, $arrResultado[0]['prospecto_num_proceso']);
            $monto = floatval($result['respapi']['result']['disbursedAmount']);
            $monto = $monto/100;

            // actualizar
            if ($monto != null) {
                $resultado = $this->mfunciones_microcreditos->ActualizarDesembolsoCobis($estructura_id, $monto, 'prospecto');
            }
        }
        $arrResultado = $this->mfunciones_logica->select_info_dependencia($estructura_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;

            foreach ($arrResultado as $key => $value) 
            {
                $calculo_lead = $this->mfunciones_generales->CalculoLead($value["prospecto_id"], 'ingreso_ponderado');
                
                $item_valor = array(
                    "prospecto_id" => $value["prospecto_id"],
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "tipo_persona_id" => $value["tipo_persona_id"],
                    "camp_id" => $value["camp_id"],
                    "prospecto_consolidado" => $value["prospecto_consolidado"],
                    "prospecto_ultimo_paso" => $value["prospecto_ultimo_paso"],
                    "general_categoria" => $value["general_categoria"],
                    "general_depende" => $value["general_depende"],
                    "general_solicitante" => $value["general_solicitante"],
                    "general_ci" => $value["general_ci"],
                    "general_ci_extension" => $value["general_ci_extension"],
                    "general_telefono" => $value["general_telefono"],
                    "prospecto_principal" => $value["prospecto_principal"],
                    "prospecto_evaluacion" => $value["prospecto_evaluacion"],
                    "ingreso_mensual_promedio" => $calculo_lead->ingreso_mensual_promedio,
                    //"ingreso_mensual_promedio" => $calculo_lead->utilidad_operativa,
                    "capacidad_ingreso" => $calculo_lead->ingreso_mensual_promedio,
                    "capacidad_costo" => $calculo_lead->costo_ventas,
                    "capacidad_utilidad_bruta" => $calculo_lead->utilidad_bruta,
                    "capacidad_utilidad_operativa" => $calculo_lead->utilidad_operativa,
                    "capacidad_resultado_neto" => $calculo_lead->utilidad_neta,
                    "capacidad_saldo_disponible" => $calculo_lead->saldo_disponible,
                    "capacidad_margen_ahorro" => $calculo_lead->margen_ahorro,
                    "onboarding" => $value["onboarding"],
                    "onboarding_codigo" => $value["onboarding_codigo"],
                    "registro_num_proceso" => $value["prospecto_num_proceso"],
                    "prospecto_desembolso_monto" => $value["prospecto_desembolso_monto"]
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

        /*
        echo "<pre>";
        print_r($lst_resultado);
        echo "</pre>";
        */

        // Listado de Versiones
        $arrVersiones = $this->mfunciones_logica->ObtenerListaVersiones($estructura_id, -1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrVersiones);
        
        $data["arrRespuesta"] = $lst_resultado;
        $data["arrVersiones"] = $arrVersiones;
        
        $this->load->view('registros/view_inicio', $data);
    }
    
    public function Sol_VistaInicio() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        
        // LLAMAR A LAS FUNCIONES PARA CARGAR LA DATA DE SOLICITUD DE CRÉDITO
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        $arrResultado = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($estructura_id);

        // ACTUALIZAR desembolso COBIS 23/02/2023 ------------------------------------------------------
        if ($arrResultado[0]['registro_num_proceso'] !="" && $arrResultado[0]['registro_num_proceso']!='0') {
            $ci_ext = trim($arrResultado[0]['sol_ci']).$arrResultado[0]['sol_extension'];

            // obtener valor de cobis
            $result = $this->JdaJsonOperacionMethod($ci_ext, $arrResultado[0]['registro_num_proceso']);
            $monto = floatval($result['respapi']['result']['disbursedAmount']);
            $monto = $monto/100;
            
            // actualizar
            if ($monto != null ) {
                $resultado = $this->mfunciones_microcreditos->ActualizarDesembolsoCobis($estructura_id, $monto, 'sol_cre');
            }
        }
        $arrResultado = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($estructura_id);

        
        if (!isset($arrResultado[0])) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }

        // Validar que el ejecutvio ya TENGA ese perfil, si ya lo tiene asignado mostrar mensaje al usuario | 2=Categoría B
        if($this->mfunciones_microcreditos->CheckTipoPerfilEjecutivo($arrResultado[0]["ejecutivo_id"], 2))
        {
            // Seleccion de Agencias asociadas
            $aux_agencias = $this->mfunciones_generales->getUsuarioRegion($arrResultado[0]["agente_codigo"]);
            
            $arrAgencias = $this->mfunciones_microcreditos->ObtenerAgenciasFiltrado($aux_agencias->region_id);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrAgencias);
            
            if (isset($arrAgencias[0]))
            {
                $i = 0;

                foreach ($arrAgencias as $key => $value) {
                    $item_valor = array(
                        "estructura_regional_id" => $value["estructura_regional_id"],
                        "estructura_regional_nombre" => $value["estructura_regional_nombre"] . ((int)$value["estructura_regional_estado"]==1 ? '' : ' (Cerrada)')
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                $lst_resultado[0] = $arrAgencias;
            }

            $data["arrAgencias"] = $lst_resultado;
        }
        
        $data["arrRespuesta"] = $arrResultado;
        
        $this->load->view('registros/view_sol_inicio', $data);
    }
    
    public function PasosGuardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // PASO 1: Se captura los 4 parámetros principales
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $codigo_rubro = $this->input->post('codigo_rubro', TRUE);
        $vista_actual = $this->input->post('vista_actual', TRUE);
        $home_ant_sig = $this->input->post('home_ant_sig', TRUE);
        
            // Auxiliar
            $tipo_registro = $this->input->post('tipo_registro', TRUE);
        
        // Si se envía SIN GUARDAR, el valor es "1"
        $sin_guardar = $this->input->post('sin_guardar', TRUE);
        
        switch ((int)$codigo_rubro) {
            // Solicitud de Crédito
            case 6:
                $this->load->model('mfunciones_microcreditos');
                $DatosSolCredito = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($estructura_id);
                
                if(!isset($DatosSolCredito[0]))
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }
                
                $accion_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($DatosSolCredito[0]['ejecutivo_id'], 'ejecutivo');

                break;

            // Normalizador/Cobrador
            case 13:
                
                $this->load->model('mfunciones_cobranzas');
                
                $DatosNormalizador = $this->mfunciones_cobranzas->DatosRegistroEmail($estructura_id);
                
                if(!isset($DatosNormalizador[0]))
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }
                
                $accion_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($DatosNormalizador[0]['ejecutivo_id'], 'ejecutivo');

                break;
                
            default:
                
                $accion_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
                
                break;
        }
        
        $accion_fecha = date('Y-m-d H:i:s');
        
        // NORMALIZADOR/COBRADOR    INICIO
        
        // -- Finalización de la gestión
        
        if($tipo_registro == 'norm_finalizacion')
        {
            // Capturar datos
            $norm_finalizacion = (int)$home_ant_sig;

            if($norm_finalizacion <= 0)
            {
                js_error_div_javascript($this->lang->line('norm_finalizacion_error'));
                exit();
            }
            
            $arrRegistro = $this->mfunciones_cobranzas->VerificaNormConsolidado($estructura_id);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRegistro);

            if (!isset($arrRegistro[0])) 
            {
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            }

            if((int)$arrRegistro[0]['norm_rel_cred'] <= 0)
            {
                js_error_div_javascript($this->lang->line('norm_finalizacion_error_form'));
                exit();
            }
            
            $this->mfunciones_cobranzas->update_finalizacion_gestion(
                        $norm_finalizacion,
                        $estructura_id,
                        $accion_usuario,
                        $accion_fecha
                        );
            
            js_invocacion_javascript('$("div.modal-backdrop").remove(); Ajax_CargadoGeneralPagina("Norm_Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");');
            exit();
        }
        
        // NORMALIZADOR/COBRADOR    FIN
        
        // SOLICIUD DE CRÉDITO  INICIO
        
        // Aprobar
            if($tipo_registro == 'sol_aprobar')
            {
                // Capturar datos
                $check_sol_trabajo_actividad_pertenece = $this->input->post('check_sol_trabajo_actividad_pertenece', TRUE);
                $check_sol_con_trabajo_actividad_pertenece = $this->input->post('check_sol_con_trabajo_actividad_pertenece', TRUE);
                $sol_trabajo_actividad_pertenece = $this->input->post('sol_trabajo_actividad_pertenece', TRUE);
                $sol_con_trabajo_actividad_pertenece = $this->input->post('sol_con_trabajo_actividad_pertenece', TRUE);
                
                $flag_convertir_actividad = (int)$this->input->post('flag_convertir_actividad', TRUE);
                
                if($flag_convertir_actividad != 1 && $flag_convertir_actividad != 2)
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }
                
                if((int)$DatosSolCredito[0]['sol_codigo_rubro'] <= 0)
                {
                    js_error_div_javascript($this->lang->line('ejecutivo_perfil_tipo_rubro_error'));
                    exit();
                }
                
                if((int)$check_sol_trabajo_actividad_pertenece == 1)
                {
                    if(str_replace(' ', '', $DatosSolCredito[0]['sol_ultimo_paso']) != 'view_final')
                    {
                        js_error_div_javascript('Para "Convertir a estudio" debe completar el registro de información con los datos mandatorios.');
                        exit();
                    }
                    
                    // Marca la opción convertir a estudio
                    
                    if((int)$sol_trabajo_actividad_pertenece <= 0)
                    {
                        js_error_div_javascript('Marcó la opción para convertir el solicitante a estudio, debe seleccionar un rubro habilitado en el sistema.');
                        exit();
                    }
                    
                    if((int)$check_sol_con_trabajo_actividad_pertenece == 1)
                    {
                        if((int)$DatosSolCredito[0]['sol_conyugue'] == 1 && str_replace(' ', '', $DatosSolCredito[0]['sol_con_ci']) != '')
                        {
                            if((int)$sol_con_trabajo_actividad_pertenece <= 0)
                            {
                                js_error_div_javascript('Marcó la opción para convertir el cónyuge a unidad familiar, debe seleccionar un rubro habilitado en el sistema.');
                                exit();
                            }
                        }
                        else
                        {
                            js_error_div_javascript('Marcó la opción para convertir el cónyuge a unidad familiar, sin embargo, no está registrado la información del cónyuge. Revise el registro.');
                            exit();
                        }
                    }
                    else
                    {
                        // Si no seleccionó la opción, enviar como 0.
                        $sol_con_trabajo_actividad_pertenece = 0;
                    }
                }
                else
                {
                    // Si no seleccionó la opción, enviar como 0.
                    $sol_trabajo_actividad_pertenece = 0;
                    $sol_con_trabajo_actividad_pertenece = 0;
                }
                
                $this->mfunciones_microcreditos->Sol_Eval_Aprobar($flag_convertir_actividad, $sol_trabajo_actividad_pertenece, $sol_con_trabajo_actividad_pertenece, $accion_usuario, $accion_fecha, $estructura_id);
                
                js_invocacion_javascript('$("div.modal-backdrop").remove(); Ajax_CargadoGeneralPagina("Sol_Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");');
                exit();
            }
        
        // Rechazar
            if($tipo_registro == 'sol_rechazar')
            {
                // $home_ant_sig = Texto de justifiación
                if ($home_ant_sig == '') 
                {
                    js_error_div_javascript('Debe registrar la Justificación para marcar la evaluación como Rechazado.');
                    exit();
                }
                
                $this->mfunciones_microcreditos->Sol_Eval_Rechazar($home_ant_sig, $accion_usuario, $accion_fecha, $estructura_id);
                
                js_invocacion_javascript('$("div.modal-backdrop").remove(); Ajax_CargadoGeneralPagina("Sol_Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");');
                exit();
            }
        
        // SOLICIUD DE CRÉDITO  FIN
        
        // PASO BAJA FAMILIAR: Si se seleccionó la opción de baja, no es necsario continuar y sólo se cambie el estado activo del Prospecto
        
            if($tipo_registro == 'baja_familiar')
            {
                // Primero se pregunta si no es el principal
                
                $arrConsulta = $this->mfunciones_logica->GetProspectoPrincipal($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConsulta);

                if (isset($arrConsulta[0])) 
                {
                    js_error_div_javascript('NO PUEDE DAR DE BAJA EL REGISTRO PRINCIPAL.');
                    exit();
                }
                
                $this->mfunciones_logica->baja_unidad_familiar(
                                    $accion_usuario,
                                    $accion_fecha,
                                    $estructura_id
                                    );
                
                $arrDepende = $this->mfunciones_logica->GetProspectoDepende($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDepende);

                if (isset($arrDepende[0])) 
                {
                    $general_depende = $arrDepende[0]['general_depende'];
                }
                
                    // Auxiliar para Auditoría
                    $accion_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
                
                // Se registra el Seguimiento
                $this->mfunciones_logica->InsertSeguimientoProspecto($general_depende, 1, 11, 'Baja Unidad Familiar (Código interno ' . $estructura_id . ')', $accion_usuario, $accion_fecha);
                
                js_invocacion_javascript('$("div.modal-backdrop").remove(); Ajax_CargadoGeneralPagina("Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");');
                exit();
            }
        
        // PASO MARCAR COMO ACTIVIDAD PRINCIPAL
        
            if($tipo_registro == 'actividad_principal')
            {
                $arrConsulta = $this->mfunciones_logica->GetProspectoDepende($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConsulta);

                if (isset($arrConsulta[0])) 
                {
                    $principal_id = $arrConsulta[0]['general_depende'];
                    $texto_actividad_principal = 'Asignado a la Unidad Familiar (Código interno ' . $estructura_id . ')';
                    
                    $arrDepende = $this->mfunciones_logica->GetProspectoDepende($estructura_id);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDepende);

                    if (isset($arrDepende[0])) 
                    {
                        $general_depende = $arrDepende[0]['general_depende'];
                    }
                }
                else
                {
                    $principal_id = $estructura_id;
                    $texto_actividad_principal = 'Asignado al Titular';
                    
                    $general_depende = $estructura_id;
                }
                
                $this->mfunciones_logica->marcar_actividad_principal(
                                    $accion_usuario,
                                    $accion_fecha,
                                    $estructura_id,
                                    $principal_id
                                    );
                
                    // Auxiliar para Auditoría
                    $accion_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($general_depende, 'prospecto');
                
                // Se registra el Seguimiento
                $this->mfunciones_logica->InsertSeguimientoProspecto($general_depende, 1, 10, $texto_actividad_principal, $accion_usuario, $accion_fecha);
                
                js_invocacion_javascript('$("div.modal-backdrop").remove(); Ajax_CargadoGeneralPagina("Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");');
                exit();
            }
            
            if($tipo_registro == 'evaluacion')
            {
                // $codigo_evaluacion = $codigo_rubro
                $this->mfunciones_logica->marcar_evaluacion_lead(
                                    $codigo_rubro,
                                    $accion_usuario,
                                    $accion_fecha,
                                    $estructura_id
                                    );
                
                    // Auxiliar para Auditoría
                    $accion_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
                
                    $nombre_evaluacion = $this->mfunciones_generales->GetValorCatalogo($codigo_rubro, 'prospecto_evaluacion');
                
                // Detalle del Prospecto
                $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
                
                // Se registra el Seguimiento
                $this->mfunciones_generales->SeguimientoHitoProspecto($estructura_id, 4, $arrResultado3[0]['prospecto_etapa'], $accion_usuario, $accion_fecha, 0);
                $this->mfunciones_logica->InsertSeguimientoProspecto($estructura_id, 1, 13, 'Cliente evaluado como ' . $nombre_evaluacion, $accion_usuario, $accion_fecha);
                
                // Se registra la observación para Terceros $home_ant_sig
                
                $codigo_terceros = $this->mfunciones_generales->ObtenerCodigoTercerosProspecto($estructura_id);
                
                $this->mfunciones_logica->update_observacion_tercero(
                            $home_ant_sig,
                            $accion_usuario,
                            $accion_fecha,
                            $codigo_terceros
                            );
                
                js_invocacion_javascript('$("div.modal-backdrop").remove(); Ajax_CargadoGeneralPagina("Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");');
                exit();
            }
            
            if($tipo_registro == 'version_lead')
            {
                    // Auxiliar para Auditoría
                    $accion_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
                    
                // SE DEBE LLAMAR A LA FUNCION QUE GENERA EL REPORTE y GUARDA
                $this->mfunciones_generales->GeneraVersionLead($estructura_id, $accion_usuario, $accion_fecha);
                    
                // Detalle del Prospecto
                $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
                
                $this->mfunciones_logica->InsertSeguimientoProspecto($estructura_id, $arrResultado3[0]['prospecto_etapa'], 14, 'Se generó una versión del Informe Final', $accion_usuario, $accion_fecha);
                
                js_invocacion_javascript('$("div.modal-backdrop").remove(); Ajax_CargadoGeneralPagina("Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");');
                exit();
            }
            
            if($tipo_registro == 'monto_inicial')
            {
                    // Auxiliar para Auditoría
                    $accion_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
                    
                $arrTercero = $this->mfunciones_logica->ObtenerDetalleTercerosProspecto($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrTercero);

                if (!isset($arrTercero[0])) 
                {
                    js_error_div_javascript('Revisé el codigo del registro');
                    exit();
                }
                    
                $codigo_terceros = $arrTercero[0]['onboarding_codigo'];
                
                // $codigo_rubro es el Valor del Monto
                
                if($codigo_rubro < 0)
                {
                    js_error_div_javascript('El Monto de Apertura no puede ser negativo.');
                    exit();
                }
                
                if($codigo_rubro > $arrTercero[0]['estructura_regional_monto'])
                {
                    js_error_div_javascript('El Monto de Apertura es mayor al establecido para ' . $arrTercero[0]['estructura_regional_nombre'] . ' (límite establecido: ' . $arrTercero[0]['estructura_regional_monto'] . ').');
                    exit();
                }
                
                
                // Función para actualizar el monto inicial
                
                    // $codigo_rubro es el Valor del Monto
                
                $this->mfunciones_logica->UpdateTercerosMonto($codigo_rubro, $codigo_terceros, $accion_usuario, $accion_fecha);
                    
                // Detalle del Prospecto
                $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
                
                $this->mfunciones_logica->InsertSeguimientoProspecto($estructura_id, $arrResultado3[0]['prospecto_etapa'], 1, 'Se Actualizó el Monto Inicial a ' . $codigo_rubro, $accion_usuario, $accion_fecha);
                
                js_invocacion_javascript('$("div.modal-backdrop").remove(); Ajax_CargadoGeneralPagina("Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");');
                exit();
            }
            
            if($tipo_registro == 'numero_operacion' || $tipo_registro == 'sol_numero_operacion')
            {
                // Si no es Solicitud de Crédito se carga el modelo para utilizar la función de validación del número de operación
                $this->load->model('mfunciones_microcreditos');
                $prospecto_desembolso_monto = $this->input->post('prospecto_desembolso_monto', TRUE);

                //echo $prospecto_desembolso_monto;exit;

                if($tipo_registro == 'sol_numero_operacion')
                {
                    $numero_operacion = $home_ant_sig;
                }
                else
                {
                    $numero_operacion = $codigo_rubro;
                }
                
                if($this->mfunciones_microcreditos->ValidarNumOperacion($numero_operacion))
                {
                    js_error_div_javascript($this->lang->line('registro_num_proceso_error'));
                    exit();
                }
                
                $this->mfunciones_microcreditos->update_NroOperacion($tipo_registro, $estructura_id, $numero_operacion, $accion_usuario, $accion_fecha,$prospecto_desembolso_monto);
                
                js_invocacion_javascript('$("div.modal-backdrop").remove(); Ajax_CargadoGeneralPagina("' . ($tipo_registro=='sol_numero_operacion' ? 'Sol_' : '') . 'Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");');
                exit();
            }
            
            if($tipo_registro == 'asociar_agencia')
            {
                $codigo_agencia = (int)$this->input->post('estructura_regional', TRUE);
                
                // Validar código de agencia recibida
                if($codigo_agencia <= 0)
                {
                    js_error_div_javascript($this->lang->line('ejecutivo_perfil_tipo_errorAgencia'));
                    exit();
                }
                
                // Validar si tiene aisgnado el perfil Categoría "B"
                if(!$this->mfunciones_microcreditos->CheckTipoPerfilEjecutivo((int)$this->input->post('codigo_ejecutivo', TRUE), 2))
                {
                    js_error_div_javascript($this->lang->line('ejecutivo_perfil_tipo_nocatb'));
                    exit();
                }
                
                // Seleccion de Agencias asociadas
                $aux_agencias = $this->mfunciones_generales->getUsuarioRegion((int)$this->input->post('codigo_usuario', TRUE));
                
                // Validar que la agencia sea parte de las asignadas al usuario
                $arrAgencias = explode(',', str_replace(' ', '', $aux_agencias->region_id));
                
                if (!in_array($codigo_agencia, $arrAgencias))
                {
                    js_error_div_javascript($this->lang->line('ejecutivo_perfil_tipo_errorNoAgencia'));
                    exit();
                }
                
                // Actualizar Agencia
                $this->mfunciones_microcreditos->setSolAgenciaAsociada($estructura_id, $codigo_agencia, $accion_usuario, $accion_fecha);
                
                js_invocacion_javascript('$("div.modal-backdrop").remove(); Ajax_CargadoGeneralPagina("Sol_Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");');
                exit();
            }
            
        // PASO 2: Se obtiene las vistas y sus colindantes
        
        switch ((int)$codigo_rubro) {
            
            // Solicitud de Crédito
            case 6:
                
                if(isset($_POST['sol_conyugue']))
                {
                    $DatosSolCredito[0]['sol_conyugue'] = $_POST['sol_conyugue'];
                }
                
                $array_rubro = $this->mfunciones_microcreditos->getSolVistasRubro($DatosSolCredito[0]['sol_conyugue']==0 ? $codigo_rubro : 61);

                break;

            // Normalizador/Cobrador
            case 13:
                
                $array_rubro = $this->mfunciones_cobranzas->getRegVistasRubro($codigo_rubro);
                
                break;
                
            default:
                
                $array_rubro = $this->mfunciones_generales->getVistasRubro($codigo_rubro);
                
                break;
        }
        
        $vista_prospecto = $this->mfunciones_generales->paso_ant_sig($vista_actual, $array_rubro);
        
        // PASO 3: Se guarda la Vista Actual (siempre y cuando no se haya enviado "sin_guardar"
        
        if($sin_guardar == 0)
        {
            $vista_actual = str_replace("view_", "", $vista_actual);
            
                // Validación de campos
                    
                $separador = '<br /> - ';
                $error_texto = '';
            
            switch ($vista_actual) {
                
                case "norm_datos_personales":
                    
                    $this->load->model('mfunciones_microcreditos');
                    
                    $registro_num_proceso = $this->input->post('registro_num_proceso', TRUE);
                    $codigo_agencia_fie = (int)$this->input->post('estructura_regional', TRUE);
                    
                    $norm_primer_nombre = ucwords($this->input->post('norm_primer_nombre', TRUE));
                    $norm_segundo_nombre = ucwords($this->input->post('norm_segundo_nombre', TRUE));
                    $norm_primer_apellido = ucwords($this->input->post('norm_primer_apellido', TRUE));
                    $norm_segundo_apellido = ucwords($this->input->post('norm_segundo_apellido', TRUE));
                    
                    $norm_cel = $this->input->post('norm_cel', TRUE);
                    $norm_actividad = $this->input->post('norm_actividad', TRUE);
                    $norm_rel_cred = (int)$this->input->post('norm_rel_cred', TRUE);
                        $norm_rel_cred_otro = $this->input->post('norm_rel_cred_otro', TRUE);

                    // Validación de campos

                    $separador = '<br /> - ';
                    $error_texto = '';

                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($norm_primer_nombre, 'string_corto', 1, 3, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('norm_primer_nombre') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($norm_segundo_nombre, 'string_corto', 1, 0, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('norm_segundo_nombre') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($norm_primer_apellido, 'string_corto', 1, 3, 30); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('norm_primer_apellido') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($norm_segundo_apellido, 'string_corto', 1, 0, 30); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('norm_segundo_apellido') . '. ' . $aux_texto; }

                    if($norm_cel != '')
                    {
                        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($norm_cel, 'celular', 2, 8, 0); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('norm_cel') . '. ' . $aux_texto; }
                    }                    
                    
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($norm_actividad, 'string', 1, 0, 80); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('norm_actividad') . '. ' . $aux_texto; }
                    
                    if($norm_rel_cred <= 0)
                    {
                        $error_texto .= $separador . $this->lang->line('norm_rel_cred');
                    }
                    
                    if($norm_rel_cred == 99)
                    {
                        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($norm_rel_cred_otro, 'string', 1, 3, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('norm_rel_cred') . ' ' . $this->lang->line('norm_rel_cred_otro') . '. ' . $aux_texto; }
                    }
                    else
                    {
                        $norm_rel_cred_otro = '';
                    }
                    
                    if($codigo_agencia_fie <= 0)
                    {
                        $error_texto .= $separador . $this->lang->line('terceros_columna1');
                    }
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }

                    if($this->mfunciones_microcreditos->ValidarNumOperacion($registro_num_proceso))
                    {
                        js_error_div_javascript($this->lang->line('registro_num_proceso_error'));
                        exit();
                    }

                    // Seleccion de Agencias asociadas
                    $aux_agencias = $this->mfunciones_generales->getUsuarioRegion($DatosNormalizador[0]['agente_codigo']);

                        // Validar que la agencia sea parte de las asignadas al usuario
                        $arrAgencias = explode(',', str_replace(' ', '', $aux_agencias->region_id));

                        if (!in_array($codigo_agencia_fie, $arrAgencias))
                        {
                            js_error_div_javascript($this->lang->line('ejecutivo_perfil_tipo_errorNoAgencia'));
                            exit();
                        }
                        
                    // Guardar en la DB
                    $this->mfunciones_cobranzas->update_norm_datos_personales(
                            $codigo_agencia_fie,
                            $registro_num_proceso,

                            $norm_primer_nombre,
                            $norm_segundo_nombre,
                            $norm_primer_apellido,
                            $norm_segundo_apellido,
                            $norm_cel,
                            $norm_actividad,
                            $norm_rel_cred,
                            $norm_rel_cred_otro,
                            
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            );
                    
                    
                    break;
                
                case "norm_direccion":
                    
                    $arrDireccion = $this->mfunciones_cobranzas->checkDireccionesRegistro($estructura_id);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDireccion);

                    if(!isset($arrDireccion[0]))
                    {
                        js_error_div_javascript($this->lang->line('rd_sin_direcciones'));
                        exit();
                    }
                    
                    break;
                    
                case "sol_datos_personales":
                    
                    // Captura de Datos
                    
                    $sol_codigo_rubro = (int)$this->input->post('sol_codigo_rubro', TRUE);
                    $sol_ci = $this->input->post('sol_ci', TRUE);
                    $sol_complemento = $this->input->post('sol_complemento', TRUE);
                    $sol_extension = $this->input->post('sol_extension', TRUE);
                    $sol_primer_nombre = ucwords($this->input->post('sol_primer_nombre', TRUE));
                    $sol_segundo_nombre = ucwords($this->input->post('sol_segundo_nombre', TRUE));
                    $sol_primer_apellido = ucwords($this->input->post('sol_primer_apellido', TRUE));
                    $sol_segundo_apellido = ucwords($this->input->post('sol_segundo_apellido', TRUE));
                    $sol_correo = $this->input->post('sol_correo', TRUE);
                    $sol_cel = $this->input->post('sol_cel', TRUE);
                    $sol_telefono = $this->input->post('sol_telefono', TRUE);
                    $sol_fec_nac = $this->input->post('sol_fec_nac', TRUE);
                    $sol_est_civil = $this->input->post('sol_est_civil', TRUE);
                    $sol_nit = $this->input->post('sol_nit', TRUE);
                    $sol_cliente = (int)$this->input->post('sol_cliente', TRUE);
                    $sol_conyugue = $this->input->post('sol_conyugue', TRUE);
                    $sol_dependencia = (int)$this->input->post('sol_dependencia', TRUE);
                    $sol_indepen_actividad = $this->input->post('sol_indepen_actividad', TRUE);
                    $sol_indepen_telefono = $this->input->post('sol_indepen_telefono', TRUE);
                    $sol_indepen_ant_ano = (int)$this->input->post('sol_indepen_ant_ano', TRUE);
                    $sol_indepen_ant_mes = (int)$this->input->post('sol_indepen_ant_mes', TRUE);
                    $sol_indepen_horario_desde = $this->input->post('sol_indepen_horario_desde', TRUE);
                    $sol_indepen_horario_hasta = $this->input->post('sol_indepen_horario_hasta', TRUE);
                    $sol_indepen_horario_dias_list = $this->input->post('sol_indepen_horario_dias_list', TRUE);
                    $sol_depen_empresa = $this->input->post('sol_depen_empresa', TRUE);
                    $sol_depen_actividad = $this->input->post('sol_depen_actividad', TRUE);
                    $sol_depen_cargo = $this->input->post('sol_depen_cargo', TRUE);
                    $sol_depen_tipo_contrato = $this->input->post('sol_depen_tipo_contrato', TRUE);
                    $sol_depen_telefono = $this->input->post('sol_depen_telefono', TRUE);
                    $sol_depen_ant_ano = (int)$this->input->post('sol_depen_ant_ano', TRUE);
                    $sol_depen_ant_mes = (int)$this->input->post('sol_depen_ant_mes', TRUE);
                    $sol_depen_horario_desde = $this->input->post('sol_depen_horario_desde', TRUE);
                    $sol_depen_horario_hasta = $this->input->post('sol_depen_horario_hasta', TRUE);
                    $sol_depen_horario_dias_list = $this->input->post('sol_depen_horario_dias_list', TRUE);
                    
                    // Actividad Secundaria
                    
                    $sol_actividad_secundaria = (int)$this->input->post('sol_actividad_secundaria', TRUE);
                    $sol_codigo_rubro_sec = (int)$this->input->post('sol_codigo_rubro_sec', TRUE);
                    
                    $sol_dependencia_sec = (int)$this->input->post('sol_dependencia_sec', TRUE);
                    $sol_indepen_actividad_sec = $this->input->post('sol_indepen_actividad_sec', TRUE);
                    $sol_indepen_telefono_sec = $this->input->post('sol_indepen_telefono_sec', TRUE);
                    $sol_indepen_ant_ano_sec = (int)$this->input->post('sol_indepen_ant_ano_sec', TRUE);
                    $sol_indepen_ant_mes_sec = (int)$this->input->post('sol_indepen_ant_mes_sec', TRUE);
                    $sol_indepen_horario_desde_sec = $this->input->post('sol_indepen_horario_desde_sec', TRUE);
                    $sol_indepen_horario_hasta_sec = $this->input->post('sol_indepen_horario_hasta_sec', TRUE);
                    $sol_indepen_horario_dias_list_sec = $this->input->post('sol_indepen_horario_dias_list_sec', TRUE);
                    $sol_depen_empresa_sec = $this->input->post('sol_depen_empresa_sec', TRUE);
                    $sol_depen_actividad_sec = $this->input->post('sol_depen_actividad_sec', TRUE);
                    $sol_depen_cargo_sec = $this->input->post('sol_depen_cargo_sec', TRUE);
                    $sol_depen_tipo_contrato_sec = $this->input->post('sol_depen_tipo_contrato_sec', TRUE);
                    $sol_depen_telefono_sec = $this->input->post('sol_depen_telefono_sec', TRUE);
                    $sol_depen_ant_ano_sec = (int)$this->input->post('sol_depen_ant_ano_sec', TRUE);
                    $sol_depen_ant_mes_sec = (int)$this->input->post('sol_depen_ant_mes_sec', TRUE);
                    $sol_depen_horario_desde_sec = $this->input->post('sol_depen_horario_desde_sec', TRUE);
                    $sol_depen_horario_hasta_sec = $this->input->post('sol_depen_horario_hasta_sec', TRUE);
                    $sol_depen_horario_dias_list_sec = $this->input->post('sol_depen_horario_dias_list_sec', TRUE);
                    
                    
                    // Validación de campos
                    
                    if($sol_codigo_rubro <= 0)
                    {
                        $error_texto .= $separador . 'Debe seleccionar un Rubro.';
                    }
                    
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_ci, '', 1, 3, 10); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_ci') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_complemento, 'ci_complemento'); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_complemento') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_extension); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_extension') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_primer_nombre, 'string_corto', 1, 3, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_primer_nombre') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_segundo_nombre, 'string_corto', 1, 0, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_segundo_nombre') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_primer_apellido, 'string_corto', 1, 3, 30); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_primer_apellido') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_segundo_apellido, 'string_corto', 1, 0, 30); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_segundo_apellido') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_correo, 'email', 0, 0, 100); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_correo') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_cel, 'celular', 2, 8, 0); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_cel') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_telefono, '', 1, 0, 15); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_telefono') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_nit, '', 0, 0, 20); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_nit') . '. ' . $aux_texto; }
                    
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_fec_nac, 'fecha_Y_M_D', 0, 0, 20);
                    if($aux_texto != '')
                    {
                        $error_texto .= $separador . $this->lang->line('sol_fec_nac') . '. ' . $aux_texto;
                    }
                    else
                    {
                        if((string)$sol_fec_nac != '')
                        {
                            $today_dt = new DateTime(date("Y-m-d"));
                            if ($today_dt <  new DateTime($sol_fec_nac)) { $error_texto .= $separador . $this->lang->line('sol_fec_nac') . ' no puede ser posterior a la fecha actual.'; }
                        }
                        else
                        {
                            $sol_fec_nac = '1900-01-01';
                        }
                    }
                    
                    switch ((int)$sol_dependencia) {
                        case 1:

                            // Dependiente
                            
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_depen_empresa, 'string', 1, 3, 60); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_depen_empresa') . '. ' . $aux_texto; }
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_depen_actividad, 'string', 0, 0, 50); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_depen_actividad') . '. ' . $aux_texto; }
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_depen_cargo, 'string', 1, 3, 60); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_depen_cargo') . '. ' . $aux_texto; }
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_depen_tipo_contrato, 'string', 0, 0, 50); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_depen_tipo_contrato') . '. ' . $aux_texto; }
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_depen_telefono, '', 1, 3, 15); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_depen_telefono') . '. ' . $aux_texto; }
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_depen_ant_ano, 'string', 0, 0, 3); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_indepen_ant_ano') . '. ' . $aux_texto; }
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_depen_ant_mes, 'string', 0, 0, 2); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_indepen_ant_mes') . '. ' . $aux_texto; }

                            // Validar horarios
                            if($sol_depen_horario_desde != '00:00:00' && $sol_depen_horario_hasta != '00:00:00')
                            {
                                if(strtotime($sol_depen_horario_desde) == false || strtotime($sol_depen_horario_hasta) == false)
                                {
                                    $error_texto .= $separador . 'Actividad dependiente. Horario de trabajo inválido.';
                                }
                                elseif(strtotime($sol_depen_horario_desde) > strtotime($sol_depen_horario_hasta))
                                {
                                    $error_texto .= $separador . 'Actividad dependiente. Horario desde/hasta inválido.';
                                }
                            }
                            else
                            {
                                $sol_depen_horario_desde = '00:00:00';
                                $sol_depen_horario_hasta = '00:00:00';
                            }
                            
                            // Registro de días de atención
                            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($sol_depen_horario_dias_list);

                            if (isset($sol_depen_horario_dias_list[0])) 
                            {
                                foreach ($sol_depen_horario_dias_list as $key => $value) 
                                {
                                    $sol_depen_horario_dias .= $value . ',';
                                }
                                
                                $sol_depen_horario_dias = rtrim($sol_depen_horario_dias, ',');
                            }
                            else
                            {
                                $sol_depen_horario_dias = '';
                            }
                            
                            if($sol_depen_ant_mes > 12)
                            {
                                $error_texto .= $separador . 'Antigüedad ' . $this->lang->line('sol_depen_ant_mes') . '. Debe ser menor o igual a 12.';
                            }
                            
                            if((int)$sol_depen_ant_ano < 0 || (int)$sol_depen_ant_mes < 0)
                            {
                                $error_texto .= $separador . $this->lang->line('sol_error_anituedad');
                            }
                            
                            // Borrar campos
                            $sol_indepen_actividad = '';
                            $sol_indepen_telefono = '';
                            $sol_indepen_ant_ano = 0;
                            $sol_indepen_ant_mes = 0;
                            $sol_indepen_horario_desde = '00:00:00';
                            $sol_indepen_horario_hasta = '00:00:00';
                            $sol_indepen_horario_dias = '';
                            
                            break;

                        case 2:
                            
                            // Independiente
                            
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_indepen_actividad, 'string', 1, 3, 60); if($aux_texto != '') {$error_texto .= $separador . 'Actividad independiente - ' . $this->lang->line('sol_indepen_actividad') . '. ' . $aux_texto; }
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_indepen_telefono, '', 1, 3, 15); if($aux_texto != '') {$error_texto .= $separador . 'Actividad independiente - ' . $this->lang->line('sol_indepen_telefono') . '. ' . $aux_texto; }
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_indepen_ant_ano, 'string', 0, 0, 3); if($aux_texto != '') {$error_texto .= $separador . 'Actividad independiente - ' . $this->lang->line('sol_indepen_ant_ano') . '. ' . $aux_texto; }
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_indepen_ant_mes, 'string', 0, 0, 2); if($aux_texto != '') {$error_texto .= $separador . 'Actividad independiente - ' . $this->lang->line('sol_indepen_ant_mes') . '. ' . $aux_texto; }
                            
                            // Validar horarios
                            if($sol_indepen_horario_desde != '00:00:00' && $sol_indepen_horario_hasta != '00:00:00')
                            {
                                if(strtotime($sol_indepen_horario_desde) == false || strtotime($sol_indepen_horario_hasta) == false)
                                {
                                    $error_texto .= $separador . 'Actividad Independiente. Horario de atención inválido.';
                                }
                                elseif(strtotime($sol_indepen_horario_desde) > strtotime($sol_indepen_horario_hasta))
                                {
                                    $error_texto .= $separador . 'Actividad Independiente. Horario desde/hasta inválido.';
                                }
                            }
                            else
                            {
                                $sol_indepen_horario_desde = '00:00:00';
                                $sol_indepen_horario_hasta = '00:00:00';
                            }
                            
                            // Registro de días de atención
                            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($sol_indepen_horario_dias_list);

                            if (isset($sol_indepen_horario_dias_list[0])) 
                            {
                                foreach ($sol_indepen_horario_dias_list as $key => $value) 
                                {
                                    $sol_indepen_horario_dias .= $value . ',';
                                }
                                
                                $sol_indepen_horario_dias = rtrim($sol_indepen_horario_dias, ',');
                            }
                            else
                            {
                                $sol_indepen_horario_dias = '';
                            }
                            
                            if($sol_indepen_ant_mes > 12)
                            {
                                $error_texto .= $separador . 'Antigüedad ' . $this->lang->line('sol_indepen_ant_mes') . '. Debe ser menor o igual a 12.';
                            }
                            
                            if((int)$sol_indepen_ant_ano < 0 || (int)$sol_indepen_ant_mes < 0)
                            {
                                $error_texto .= $separador . $this->lang->line('sol_error_anituedad');
                            }
                            
                            // Borrar campos
                            $sol_depen_empresa = '';
                            $sol_depen_actividad = '';
                            $sol_depen_cargo = '';
                            $sol_depen_tipo_contrato = '';
                            $sol_depen_telefono = '';
                            $sol_depen_ant_ano = 0;
                            $sol_depen_ant_mes = 0;
                            $sol_depen_horario_desde = '00:00:00';
                            $sol_depen_horario_hasta = '00:00:00';
                            $sol_depen_horario_dias = '';
                            
                            break;
                            
                        default:
                            
                            $error_texto .= $separador . $this->lang->line('sol_dependencia') . '. Debe seleccionar una opción.';
                            
                            // Borrar campos
                            $sol_indepen_actividad = '';
                            $sol_indepen_telefono = '';
                            $sol_indepen_ant_ano = 0;
                            $sol_indepen_ant_mes = 0;
                            $sol_indepen_horario_desde = '00:00:00';
                            $sol_indepen_horario_hasta = '00:00:00';
                            $sol_indepen_horario_dias = '';
                            
                            $sol_depen_empresa = '';
                            $sol_depen_actividad = '';
                            $sol_depen_cargo = '';
                            $sol_depen_tipo_contrato = '';
                            $sol_depen_telefono = '';
                            $sol_depen_ant_ano = 0;
                            $sol_depen_ant_mes = 0;
                            $sol_depen_horario_desde = '00:00:00';
                            $sol_depen_horario_hasta = '00:00:00';
                            $sol_depen_horario_dias = '';
                            
                            break;
                    }
                    
                    // Validar sólo si marcó la Actividad Secundaria
                    if($sol_actividad_secundaria == 1)
                    {
                        // Se adecua el separador para incluir la referencia de Actividad Secundaria
                        $separador = '<br /> - ' . $this->lang->line('sol_secundaria_separador');
                        
                        if($sol_codigo_rubro_sec <= 0)
                        {
                            $error_texto .= $separador . 'Debe seleccionar un Rubro.';
                        }
                        
                        switch ((int)$sol_dependencia_sec) {
                            case 1:

                                // Dependiente

                                $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_depen_empresa_sec, 'string', 1, 3, 60); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_depen_empresa_sec') . '. ' . $aux_texto; }
                                $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_depen_actividad_sec, 'string', 0, 0, 50); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_depen_actividad_sec') . '. ' . $aux_texto; }
                                $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_depen_cargo_sec, 'string', 1, 3, 60); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_depen_cargo_sec') . '. ' . $aux_texto; }
                                $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_depen_tipo_contrato_sec, 'string', 0, 0, 50); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_depen_tipo_contrato_sec') . '. ' . $aux_texto; }
                                $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_depen_telefono_sec, '', 1, 3, 15); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_depen_telefono_sec') . '. ' . $aux_texto; }
                                $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_depen_ant_ano_sec, 'string', 0, 0, 3); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_indepen_ant_ano_sec') . '. ' . $aux_texto; }
                                $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_depen_ant_mes_sec, 'string', 0, 0, 2); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_indepen_ant_mes_sec') . '. ' . $aux_texto; }

                                // Validar horarios
                                if($sol_depen_horario_desde_sec != '00:00:00' && $sol_depen_horario_hasta_sec != '00:00:00')
                                {
                                    if(strtotime($sol_depen_horario_desde_sec) == false || strtotime($sol_depen_horario_hasta_sec) == false)
                                    {
                                        $error_texto .= $separador . 'Actividad dependiente. Horario de trabajo inválido.';
                                    }
                                    elseif(strtotime($sol_depen_horario_desde_sec) > strtotime($sol_depen_horario_hasta_sec))
                                    {
                                        $error_texto .= $separador . 'Actividad dependiente. Horario desde/hasta inválido.';
                                    }
                                }
                                else
                                {
                                    $sol_depen_horario_desde_sec = '00:00:00';
                                    $sol_depen_horario_hasta_sec = '00:00:00';
                                }

                                // Registro de días de atención
                                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($sol_depen_horario_dias_list_sec);

                                if (isset($sol_depen_horario_dias_list_sec[0])) 
                                {
                                    foreach ($sol_depen_horario_dias_list_sec as $key => $value) 
                                    {
                                        $sol_depen_horario_dias_sec .= $value . ',';
                                    }

                                    $sol_depen_horario_dias_sec = rtrim($sol_depen_horario_dias_sec, ',');
                                }
                                else
                                {
                                    $sol_depen_horario_dias_sec = '';
                                }

                                if($sol_depen_ant_mes_sec > 12)
                                {
                                    $error_texto .= $separador . 'Antigüedad ' . $this->lang->line('sol_depen_ant_mes_sec') . '. Debe ser menor o igual a 12.';
                                }

                                if((int)$sol_depen_ant_ano_sec < 0 || (int)$sol_depen_ant_mes_sec < 0)
                                {
                                    $error_texto .= $separador . $this->lang->line('sol_error_anituedad');
                                }

                                // Borrar campos
                                $sol_indepen_actividad_sec = '';
                                $sol_indepen_telefono_sec = '';
                                $sol_indepen_ant_ano_sec = 0;
                                $sol_indepen_ant_mes_sec = 0;
                                $sol_indepen_horario_desde_sec = '00:00:00';
                                $sol_indepen_horario_hasta_sec = '00:00:00';
                                $sol_indepen_horario_dias_sec = '';

                                break;

                            case 2:

                                // Independiente

                                $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_indepen_actividad_sec, 'string', 1, 3, 60); if($aux_texto != '') {$error_texto .= $separador . 'Actividad independiente - ' . $this->lang->line('sol_indepen_actividad_sec') . '. ' . $aux_texto; }
                                $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_indepen_telefono_sec, '', 1, 3, 15); if($aux_texto != '') {$error_texto .= $separador . 'Actividad independiente - ' . $this->lang->line('sol_indepen_telefono_sec') . '. ' . $aux_texto; }
                                $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_indepen_ant_ano_sec, 'string', 0, 0, 3); if($aux_texto != '') {$error_texto .= $separador . 'Actividad independiente - ' . $this->lang->line('sol_indepen_ant_ano_sec') . '. ' . $aux_texto; }
                                $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_indepen_ant_mes_sec, 'string', 0, 0, 2); if($aux_texto != '') {$error_texto .= $separador . 'Actividad independiente - ' . $this->lang->line('sol_indepen_ant_mes_sec') . '. ' . $aux_texto; }

                                // Validar horarios
                                if($sol_indepen_horario_desde_sec != '00:00:00' && $sol_indepen_horario_hasta_sec != '00:00:00')
                                {
                                    if(strtotime($sol_indepen_horario_desde_sec) == false || strtotime($sol_indepen_horario_hasta_sec) == false)
                                    {
                                        $error_texto .= $separador . 'Actividad Independiente. Horario de atención inválido.';
                                    }
                                    elseif(strtotime($sol_indepen_horario_desde_sec) > strtotime($sol_indepen_horario_hasta_sec))
                                    {
                                        $error_texto .= $separador . 'Actividad Independiente. Horario desde/hasta inválido.';
                                    }
                                }
                                else
                                {
                                    $sol_indepen_horario_desde_sec = '00:00:00';
                                    $sol_indepen_horario_hasta_sec = '00:00:00';
                                }

                                // Registro de días de atención
                                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($sol_indepen_horario_dias_list_sec);

                                if (isset($sol_indepen_horario_dias_list_sec[0])) 
                                {
                                    foreach ($sol_indepen_horario_dias_list_sec as $key => $value) 
                                    {
                                        $sol_indepen_horario_dias_sec .= $value . ',';
                                    }

                                    $sol_indepen_horario_dias_sec = rtrim($sol_indepen_horario_dias_sec, ',');
                                }
                                else
                                {
                                    $sol_indepen_horario_dias_sec = '';
                                }

                                if($sol_indepen_ant_mes_sec > 12)
                                {
                                    $error_texto .= $separador . 'Antigüedad ' . $this->lang->line('sol_indepen_ant_mes_sec') . '. Debe ser menor o igual a 12.';
                                }

                                if((int)$sol_indepen_ant_ano_sec < 0 || (int)$sol_indepen_ant_mes_sec < 0)
                                {
                                    $error_texto .= $separador . $this->lang->line('sol_error_anituedad');
                                }

                                // Borrar campos
                                $sol_depen_empresa_sec = '';
                                $sol_depen_actividad_sec = '';
                                $sol_depen_cargo_sec = '';
                                $sol_depen_tipo_contrato_sec = '';
                                $sol_depen_telefono_sec = '';
                                $sol_depen_ant_ano_sec = 0;
                                $sol_depen_ant_mes_sec = 0;
                                $sol_depen_horario_desde_sec = '00:00:00';
                                $sol_depen_horario_hasta_sec = '00:00:00';
                                $sol_depen_horario_dias_sec = '';

                                break;

                            default:

                                $error_texto .= $separador . $this->lang->line('sol_dependencia_sec') . '. Debe seleccionar una opción.';

                                // Borrar campos
                                $sol_indepen_actividad_sec = '';
                                $sol_indepen_telefono_sec = '';
                                $sol_indepen_ant_ano_sec = 0;
                                $sol_indepen_ant_mes_sec = 0;
                                $sol_indepen_horario_desde_sec = '00:00:00';
                                $sol_indepen_horario_hasta_sec = '00:00:00';
                                $sol_indepen_horario_dias_sec = '';

                                $sol_depen_empresa_sec = '';
                                $sol_depen_actividad_sec = '';
                                $sol_depen_cargo_sec = '';
                                $sol_depen_tipo_contrato_sec = '';
                                $sol_depen_telefono_sec = '';
                                $sol_depen_ant_ano_sec = 0;
                                $sol_depen_ant_mes_sec = 0;
                                $sol_depen_horario_desde_sec = '00:00:00';
                                $sol_depen_horario_hasta_sec = '00:00:00';
                                $sol_depen_horario_dias_sec = '';

                                break;
                        }
                    }
                    else
                    {
                        // Borrar campos
                        
                        $sol_codigo_rubro_sec = -1;
                        $sol_dependencia_sec = 0;
                        
                        $sol_indepen_actividad_sec = '';
                        $sol_indepen_telefono_sec = '';
                        $sol_indepen_ant_ano_sec = 0;
                        $sol_indepen_ant_mes_sec = 0;
                        $sol_indepen_horario_desde_sec = '00:00:00';
                        $sol_indepen_horario_hasta_sec = '00:00:00';
                        $sol_indepen_horario_dias_sec = '';

                        $sol_depen_empresa_sec = '';
                        $sol_depen_actividad_sec = '';
                        $sol_depen_cargo_sec = '';
                        $sol_depen_tipo_contrato_sec = '';
                        $sol_depen_telefono_sec = '';
                        $sol_depen_ant_ano_sec = 0;
                        $sol_depen_ant_mes_sec = 0;
                        $sol_depen_horario_desde_sec = '00:00:00';
                        $sol_depen_horario_hasta_sec = '00:00:00';
                        $sol_depen_horario_dias_sec = '';
                        
                        // Auxiliar. Sólo si seleccionó sin Actividad Secundaria, se debe borrar sus campos de direccion (setearlos como vacío).
                        $this->mfunciones_microcreditos->SolLimpiarDatosActSecundaria($estructura_id);
                    }
                    
                    if(str_replace(' ', '', $sol_conyugue) == '')
                    {
                        $error_texto .= $separador . $this->lang->line('sol_conyugue') . '. Debe seleccionar una opción.';
                    }
                    else
                    {
                        $sol_conyugue = (int)$sol_conyugue;
                    }
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    // Guardar en la DB
                    
                    // Auxiliar. Sólo si seleccionó sin Cónyuge, se debe borrar sus campos (setearlos como vacío).
                    
                    if($sol_conyugue == 0)
                    {
                        $this->mfunciones_microcreditos->SolLimpiarDatosConyuge($estructura_id);
                    }
                    
                    $this->mfunciones_microcreditos->update_sol_datos_personales(
                            
                            $sol_codigo_rubro,
                            $sol_ci,
                            $sol_complemento,
                            $sol_extension,
                            $sol_primer_nombre,
                            $sol_segundo_nombre,
                            $sol_primer_apellido,
                            $sol_segundo_apellido,
                            $sol_correo,
                            $sol_cel,
                            $sol_telefono,
                            $sol_fec_nac,
                            $sol_est_civil,
                            $sol_nit,
                            $sol_cliente,
                            $sol_conyugue,
                            $sol_dependencia,
                            $sol_indepen_actividad,
                            $sol_indepen_telefono,
                            $sol_indepen_ant_ano,
                            $sol_indepen_ant_mes,
                            $sol_indepen_horario_desde,
                            $sol_indepen_horario_hasta,
                            $sol_indepen_horario_dias,
                            $sol_depen_empresa,
                            $sol_depen_actividad,
                            $sol_depen_cargo,
                            $sol_depen_tipo_contrato,
                            $sol_depen_telefono,
                            $sol_depen_ant_ano,
                            $sol_depen_ant_mes,
                            $sol_depen_horario_desde,
                            $sol_depen_horario_hasta,
                            $sol_depen_horario_dias,
                            
                            // Actividad Secundaria
                            $sol_actividad_secundaria,
                            $sol_codigo_rubro_sec,
                            $sol_dependencia_sec,
                            $sol_indepen_actividad_sec,
                            $sol_indepen_telefono_sec,
                            $sol_indepen_ant_ano_sec,
                            $sol_indepen_ant_mes_sec,
                            $sol_indepen_horario_desde_sec,
                            $sol_indepen_horario_hasta_sec,
                            $sol_indepen_horario_dias_sec,
                            $sol_depen_empresa_sec,
                            $sol_depen_actividad_sec,
                            $sol_depen_cargo_sec,
                            $sol_depen_tipo_contrato_sec,
                            $sol_depen_telefono_sec,
                            $sol_depen_ant_ano_sec,
                            $sol_depen_ant_mes_sec,
                            $sol_depen_horario_desde_sec,
                            $sol_depen_horario_hasta_sec,
                            $sol_depen_horario_dias_sec,
                            
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            );
                    
                    break;
                
                case "sol_con_datos_personales":
                    
                    // Captura de Datos
                    
                    $sol_con_ci = $this->input->post('sol_con_ci', TRUE);
                    $sol_con_complemento = $this->input->post('sol_con_complemento', TRUE);
                    $sol_con_extension = $this->input->post('sol_con_extension', TRUE);
                    $sol_con_primer_nombre = ucwords($this->input->post('sol_con_primer_nombre', TRUE));
                    $sol_con_segundo_nombre = ucwords($this->input->post('sol_con_segundo_nombre', TRUE));
                    $sol_con_primer_apellido = ucwords($this->input->post('sol_con_primer_apellido', TRUE));
                    $sol_con_segundo_apellido = ucwords($this->input->post('sol_con_segundo_apellido', TRUE));
                    $sol_con_correo = $this->input->post('sol_con_correo', TRUE);
                    $sol_con_cel = $this->input->post('sol_con_cel', TRUE);
                    $sol_con_telefono = $this->input->post('sol_con_telefono', TRUE);
                    $sol_con_fec_nac = $this->input->post('sol_con_fec_nac', TRUE);
                    $sol_con_est_civil = $this->input->post('sol_con_est_civil', TRUE);
                    $sol_con_nit = $this->input->post('sol_con_nit', TRUE);
                    $sol_con_cliente = (int)$this->input->post('sol_con_cliente', TRUE);
                    $sol_con_dependencia = (int)$this->input->post('sol_con_dependencia', TRUE);
                    $sol_con_indepen_actividad = $this->input->post('sol_con_indepen_actividad', TRUE);
                    $sol_con_indepen_telefono = $this->input->post('sol_con_indepen_telefono', TRUE);
                    $sol_con_indepen_ant_ano = (int)$this->input->post('sol_con_indepen_ant_ano', TRUE);
                    $sol_con_indepen_ant_mes = (int)$this->input->post('sol_con_indepen_ant_mes', TRUE);
                    $sol_con_indepen_horario_desde = $this->input->post('sol_con_indepen_horario_desde', TRUE);
                    $sol_con_indepen_horario_hasta = $this->input->post('sol_con_indepen_horario_hasta', TRUE);
                    $sol_con_indepen_horario_dias_list = $this->input->post('sol_con_indepen_horario_dias_list', TRUE);
                    $sol_con_depen_empresa = $this->input->post('sol_con_depen_empresa', TRUE);
                    $sol_con_depen_actividad = $this->input->post('sol_con_depen_actividad', TRUE);
                    $sol_con_depen_cargo = $this->input->post('sol_con_depen_cargo', TRUE);
                    $sol_con_depen_tipo_contrato = $this->input->post('sol_con_depen_tipo_contrato', TRUE);
                    $sol_con_depen_telefono = $this->input->post('sol_con_depen_telefono', TRUE);
                    $sol_con_depen_ant_ano = (int)$this->input->post('sol_con_depen_ant_ano', TRUE);
                    $sol_con_depen_ant_mes = (int)$this->input->post('sol_con_depen_ant_mes', TRUE);
                    $sol_con_depen_horario_desde = $this->input->post('sol_con_depen_horario_desde', TRUE);
                    $sol_con_depen_horario_hasta = $this->input->post('sol_con_depen_horario_hasta', TRUE);
                    $sol_con_depen_horario_dias_list = $this->input->post('sol_con_depen_horario_dias_list', TRUE);
                    
                    // Validación de campos
                    
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_ci, '', 1, 3, 10); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_con_ci') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_complemento, 'ci_complemento'); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_con_complemento') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_extension); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_con_extension') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_primer_nombre, 'string_corto', 1, 3, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_con_primer_nombre') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_segundo_nombre, 'string_corto', 1, 0, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_con_segundo_nombre') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_primer_apellido, 'string_corto', 1, 3, 30); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_con_primer_apellido') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_segundo_apellido, 'string_corto', 1, 0, 30); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_con_segundo_apellido') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_correo, 'email', 0, 0, 100); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_con_correo') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_cel, 'celular', 2, 8, 0); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_con_cel') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_telefono, '', 1, 0, 15); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_con_telefono') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_nit, '', 0, 0, 20); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_con_nit') . '. ' . $aux_texto; }
                    
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_fec_nac, 'fecha_Y_M_D', 0, 0, 20);
                    if($aux_texto != '')
                    {
                        $error_texto .= $separador . $this->lang->line('sol_con_fec_nac') . '. ' . $aux_texto;
                    }
                    else
                    {
                        if((string)$sol_con_fec_nac != '')
                        {
                            $today_dt = new DateTime(date("Y-m-d"));
                            if ($today_dt <  new DateTime($sol_con_fec_nac)) { $error_texto .= $separador . $this->lang->line('sol_con_fec_nac') . ' no puede ser posterior a la fecha actual.'; }
                        }
                        else
                        {
                            $sol_con_fec_nac = '1900-01-01';
                        }
                    }
                    
                    switch ((int)$sol_con_dependencia) {
                        case 1:

                            // Dependiente
                            
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_depen_empresa, 'string', 1, 3, 60); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_con_depen_empresa') . '. ' . $aux_texto; }
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_depen_actividad, 'string', 0, 0, 50); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_con_depen_actividad') . '. ' . $aux_texto; }
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_depen_cargo, 'string', 1, 3, 60); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_con_depen_cargo') . '. ' . $aux_texto; }
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_depen_tipo_contrato, 'string', 0, 0, 50); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_con_depen_tipo_contrato') . '. ' . $aux_texto; }
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_depen_telefono, '', 1, 3, 15); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_con_depen_telefono') . '. ' . $aux_texto; }
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_depen_ant_ano, 'string', 0, 0, 3); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_con_indepen_ant_ano') . '. ' . $aux_texto; }
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_depen_ant_mes, 'string', 0, 0, 2); if($aux_texto != '') {$error_texto .= $separador . 'Actividad dependiente - ' . $this->lang->line('sol_con_indepen_ant_mes') . '. ' . $aux_texto; }

                            // Validar horarios
                            if($sol_con_depen_horario_desde != '00:00:00' && $sol_con_depen_horario_hasta != '00:00:00')
                            {
                                if(strtotime($sol_con_depen_horario_desde) == false || strtotime($sol_con_depen_horario_hasta) == false)
                                {
                                    $error_texto .= $separador . 'Actividad dependiente. Horario de trabajo inválido.';
                                }
                                elseif(strtotime($sol_con_depen_horario_desde) > strtotime($sol_con_depen_horario_hasta))
                                {
                                    $error_texto .= $separador . 'Actividad dependiente. Horario desde/hasta inválido.';
                                }
                            }
                            else
                            {
                                $sol_con_depen_horario_desde = '00:00:00';
                                $sol_con_depen_horario_hasta = '00:00:00';
                            }
                            
                            // Registro de días de atención
                            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($sol_con_depen_horario_dias_list);

                            if (isset($sol_con_depen_horario_dias_list[0])) 
                            {
                                foreach ($sol_con_depen_horario_dias_list as $key => $value) 
                                {
                                    $sol_con_depen_horario_dias .= $value . ',';
                                }
                                
                                $sol_con_depen_horario_dias = rtrim($sol_con_depen_horario_dias, ',');
                            }
                            else
                            {
                                $sol_con_depen_horario_dias = '';
                            }
                            
                            if($sol_con_depen_ant_mes > 12)
                            {
                                $error_texto .= $separador . 'Antigüedad ' . $this->lang->line('sol_con_depen_ant_mes') . '. Debe ser menor o igual a 12.';
                            }
                            
                            if((int)$sol_con_depen_ant_ano < 0 || (int)$sol_con_depen_ant_mes < 0)
                            {
                                $error_texto .= $separador . $this->lang->line('sol_error_anituedad');
                            }
                            
                            // Borrar campos
                            $sol_con_indepen_actividad = '';
                            $sol_con_indepen_telefono = '';
                            $sol_con_indepen_ant_ano = 0;
                            $sol_con_indepen_ant_mes = 0;
                            $sol_con_indepen_horario_desde = '00:00:00';
                            $sol_con_indepen_horario_hasta = '00:00:00';
                            $sol_con_indepen_horario_dias = '';
                            
                            break;

                        case 2:
                            
                            // Independiente
                            
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_indepen_actividad, 'string', 1, 3, 60); if($aux_texto != '') {$error_texto .= $separador . 'Actividad independiente - ' . $this->lang->line('sol_con_indepen_actividad') . '. ' . $aux_texto; }
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_indepen_telefono, '', 1, 3, 15); if($aux_texto != '') {$error_texto .= $separador . 'Actividad independiente - ' . $this->lang->line('sol_con_indepen_telefono') . '. ' . $aux_texto; }
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_indepen_ant_ano, 'string', 0, 0, 3); if($aux_texto != '') {$error_texto .= $separador . 'Actividad independiente - ' . $this->lang->line('sol_con_indepen_ant_ano') . '. ' . $aux_texto; }
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_indepen_ant_mes, 'string', 0, 0, 2); if($aux_texto != '') {$error_texto .= $separador . 'Actividad independiente - ' . $this->lang->line('sol_con_indepen_ant_mes') . '. ' . $aux_texto; }
                            
                            // Validar horarios
                            if($sol_con_indepen_horario_desde != '00:00:00' && $sol_con_indepen_horario_hasta != '00:00:00')
                            {
                                if(strtotime($sol_con_indepen_horario_desde) == false || strtotime($sol_con_indepen_horario_hasta) == false)
                                {
                                    $error_texto .= $separador . 'Actividad Independiente. Horario de atención inválido.';
                                }
                                elseif(strtotime($sol_con_indepen_horario_desde) > strtotime($sol_con_indepen_horario_hasta))
                                {
                                    $error_texto .= $separador . 'Actividad Independiente. Horario desde/hasta inválido.';
                                }
                            }
                            else
                            {
                                $sol_con_indepen_horario_desde = '00:00:00';
                                $sol_con_indepen_horario_hasta = '00:00:00';
                            }
                            
                            // Registro de días de atención
                            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($sol_con_indepen_horario_dias_list);

                            if (isset($sol_con_indepen_horario_dias_list[0])) 
                            {
                                foreach ($sol_con_indepen_horario_dias_list as $key => $value) 
                                {
                                    $sol_con_indepen_horario_dias .= $value . ',';
                                }
                                
                                $sol_con_indepen_horario_dias = rtrim($sol_con_indepen_horario_dias, ',');
                            }
                            else
                            {
                                $sol_con_indepen_horario_dias = '';
                            }
                            
                            if($sol_con_indepen_ant_mes > 12)
                            {
                                $error_texto .= $separador . 'Antigüedad ' . $this->lang->line('sol_con_indepen_ant_mes') . '. Debe ser menor o igual a 12.';
                            }
                            
                            if((int)$sol_con_indepen_ant_ano < 0 || (int)$sol_con_indepen_ant_mes < 0)
                            {
                                $error_texto .= $separador . $this->lang->line('sol_error_anituedad');
                            }
                            
                            // Borrar campos
                            $sol_con_depen_empresa = '';
                            $sol_con_depen_actividad = '';
                            $sol_con_depen_cargo = '';
                            $sol_con_depen_tipo_contrato = '';
                            $sol_con_depen_telefono = '';
                            $sol_con_depen_ant_ano = 0;
                            $sol_con_depen_ant_mes = 0;
                            $sol_con_depen_horario_desde = '00:00:00';
                            $sol_con_depen_horario_hasta = '00:00:00';
                            $sol_con_depen_horario_dias = '';
                            
                            break;
                            
                        default:
                            
                            // Borrar campos
                            $sol_con_indepen_actividad = '';
                            $sol_con_indepen_telefono = '';
                            $sol_con_indepen_ant_ano = 0;
                            $sol_con_indepen_ant_mes = 0;
                            $sol_con_indepen_horario_desde = '00:00:00';
                            $sol_con_indepen_horario_hasta = '00:00:00';
                            $sol_con_indepen_horario_dias = '';
                            
                            $sol_con_depen_empresa = '';
                            $sol_con_depen_actividad = '';
                            $sol_con_depen_cargo = '';
                            $sol_con_depen_tipo_contrato = '';
                            $sol_con_depen_telefono = '';
                            $sol_con_depen_ant_ano = 0;
                            $sol_con_depen_ant_mes = 0;
                            $sol_con_depen_horario_desde = '00:00:00';
                            $sol_con_depen_horario_hasta = '00:00:00';
                            $sol_con_depen_horario_dias = '';
                            
                            break;
                    }
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    // Guardar en la DB
                    
                    $this->mfunciones_microcreditos->update_sol_con_datos_personales(
                            
                            $sol_con_ci,
                            $sol_con_complemento,
                            $sol_con_extension,
                            $sol_con_primer_nombre,
                            $sol_con_segundo_nombre,
                            $sol_con_primer_apellido,
                            $sol_con_segundo_apellido,
                            $sol_con_correo,
                            $sol_con_cel,
                            $sol_con_telefono,
                            $sol_con_fec_nac,
                            $sol_con_est_civil,
                            $sol_con_nit,
                            $sol_con_cliente,
                            $sol_con_dependencia,
                            $sol_con_indepen_actividad,
                            $sol_con_indepen_telefono,
                            $sol_con_indepen_ant_ano,
                            $sol_con_indepen_ant_mes,
                            $sol_con_indepen_horario_desde,
                            $sol_con_indepen_horario_hasta,
                            $sol_con_indepen_horario_dias,
                            $sol_con_depen_empresa,
                            $sol_con_depen_actividad,
                            $sol_con_depen_cargo,
                            $sol_con_depen_tipo_contrato,
                            $sol_con_depen_telefono,
                            $sol_con_depen_ant_ano,
                            $sol_con_depen_ant_mes,
                            $sol_con_depen_horario_desde,
                            $sol_con_depen_horario_hasta,
                            $sol_con_depen_horario_dias,
                            
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            );
                    
                    break;
                
                case "sol_credito_solicitado":
                    
                    // Captura de Datos
                    
                    $sol_monto = $this->input->post('sol_monto', TRUE);
                    $sol_plazo = (int)$this->input->post('sol_plazo', TRUE);
                    $sol_moneda = $this->input->post('sol_moneda', TRUE);
                    $sol_detalle = $this->input->post('sol_detalle', TRUE);
                    
                    // Validación de campos
                    
                    if((int)$sol_monto <= 1)
                    {
                        $error_texto .= $separador . $this->lang->line('sol_monto') . '. Debe ser mayor a 1.';
                    }
                    
                    if((int)$sol_plazo <= 0)
                    {
                        $error_texto .= $separador . $this->lang->line('sol_plazo') . '. Debe ser mayor a 0.';
                    }
                    
                    if($sol_moneda == '')
                    {
                        $error_texto .= $separador . 'Debe seleccionar ' . $this->lang->line('sol_moneda');
                    }
                    
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_detalle, 'string', 1, 3, 2300); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_detalle') . '. ' . $aux_texto; }
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    // Guardar en la DB
                    
                    $this->mfunciones_microcreditos->update_sol_credito_solicitado(
                            
                            $sol_monto,
                            $sol_plazo,
                            $sol_moneda,
                            $sol_detalle,
                            
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            );
                    
                    break;
                    
                case "sol_direccion":
                    
                    // Captura de Datos
                    
                    $sol_dir_departamento = $this->input->post('sol_dir_departamento');
                    $sol_dir_provincia = $this->input->post('sol_dir_provincia');
                    $sol_dir_localidad_ciudad = $this->input->post('sol_dir_localidad_ciudad');
                    $sol_cod_barrio = $this->input->post('sol_cod_barrio');
                    $sol_direccion_trabajo = $this->input->post('sol_direccion_trabajo');
                    $sol_edificio_trabajo = $this->input->post('sol_edificio_trabajo');
                    $sol_numero_trabajo = $this->input->post('sol_numero_trabajo');
                    $sol_trabajo_lugar = (int)$this->input->post('sol_trabajo_lugar');
                        $sol_trabajo_lugar_otro = $this->input->post('sol_trabajo_lugar_otro');
                    $sol_trabajo_realiza = (int)$this->input->post('sol_trabajo_realiza');
                        $sol_trabajo_realiza_otro = $this->input->post('sol_trabajo_realiza_otro');
                    $sol_dir_referencia = $this->input->post('sol_dir_referencia');
                    $sol_geolocalizacion = $this->input->post('sol_geolocalizacion');
                    $sol_croquis = $this->input->post('croquis_base64__1');
                    $sol_dir_departamento_dom = $this->input->post('sol_dir_departamento_dom');
                    $sol_dir_provincia_dom = $this->input->post('sol_dir_provincia_dom');
                    $sol_dir_localidad_ciudad_dom = $this->input->post('sol_dir_localidad_ciudad_dom');
                    $sol_cod_barrio_dom = $this->input->post('sol_cod_barrio_dom');
                    $sol_direccion_dom = $this->input->post('sol_direccion_dom');
                    $sol_edificio_dom = $this->input->post('sol_edificio_dom');
                    $sol_numero_dom = $this->input->post('sol_numero_dom');
                    $sol_dom_tipo = (int)$this->input->post('sol_dom_tipo');
                        $sol_dom_tipo_otro = $this->input->post('sol_dom_tipo_otro');
                    $sol_dir_referencia_dom = $this->input->post('sol_dir_referencia_dom');
                    $sol_geolocalizacion_dom = $this->input->post('sol_geolocalizacion_dom');
                    $sol_croquis_dom = $this->input->post('croquis_base64__2');
                    
                    // Actividad Secundaria
                    $sol_dir_departamento_sec = $this->input->post('sol_dir_departamento_sec');
                    $sol_dir_provincia_sec = $this->input->post('sol_dir_provincia_sec');
                    $sol_dir_localidad_ciudad_sec = $this->input->post('sol_dir_localidad_ciudad_sec');
                    $sol_cod_barrio_sec = $this->input->post('sol_cod_barrio_sec');
                    $sol_direccion_trabajo_sec = $this->input->post('sol_direccion_trabajo_sec');
                    $sol_edificio_trabajo_sec = $this->input->post('sol_edificio_trabajo_sec');
                    $sol_numero_trabajo_sec = $this->input->post('sol_numero_trabajo_sec');
                    $sol_trabajo_lugar_sec = (int)$this->input->post('sol_trabajo_lugar_sec');
                        $sol_trabajo_lugar_otro_sec = $this->input->post('sol_trabajo_lugar_otro_sec');
                    $sol_trabajo_realiza_sec = (int)$this->input->post('sol_trabajo_realiza_sec');
                        $sol_trabajo_realiza_otro_sec = $this->input->post('sol_trabajo_realiza_otro_sec');
                    $sol_dir_referencia_sec = $this->input->post('sol_dir_referencia_sec');
                    $sol_geolocalizacion_sec = $this->input->post('sol_geolocalizacion_sec');
                    $sol_croquis_sec = $this->input->post('croquis_base64__3');
                    
                    // Validación de campos
                    
                    if((int)$sol_dir_localidad_ciudad <= 0)
                    {
                        $error_texto .= $separador . 'Dirección negocio/actividad - Mínimamente debe registrar ' . $this->lang->line('sol_dir_localidad_ciudad') . '.';
                    }
                    
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_direccion_trabajo, 'string', 1, 0, 0); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_direccion_trabajo') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_edificio_trabajo, 'string', 1, 0, 0); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_edificio_trabajo') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_direccion_dom, 'string', 1, 0, 0); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_direccion_dom') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_edificio_dom, 'string', 1, 0, 0); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_edificio_dom') . '. ' . $aux_texto; }
                    
                    if($sol_trabajo_lugar == 99)
                    {
                        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_trabajo_lugar_otro, 'string', 1, 3, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_trabajo_lugar') . ' ' . $this->lang->line('sol_trabajo_lugar_otro') . '. ' . $aux_texto; }
                    }
                    else
                    {
                        $sol_trabajo_lugar_otro = '';
                    }

                    if($sol_trabajo_realiza == 99)
                    {
                        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_trabajo_realiza_otro, 'string', 1, 3, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_trabajo_realiza') . ' ' . $this->lang->line('sol_trabajo_realiza_otro') . '. ' . $aux_texto; }
                    }
                    else
                    {
                        $sol_trabajo_realiza_otro = '';
                    }

                    if($sol_dom_tipo == 99)
                    {
                        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_dom_tipo_otro, 'string', 1, 3, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_dom_tipo') . ' ' . $this->lang->line('sol_dom_tipo_otro') . '. ' . $aux_texto; }
                    }
                    else
                    {
                        $sol_dom_tipo_otro = '';
                    }
                    
                    if(!$this->mfunciones_microcreditos->validateRegDir($sol_dir_departamento, $sol_dir_provincia, $sol_dir_localidad_ciudad, $sol_cod_barrio))
                    {
                        $error_texto .= $separador . 'Dirección negocio/actividad - ' . $this->lang->line('sol_direccion_error');
                    }
                    
                    if(!$this->mfunciones_microcreditos->validateRegDir($sol_dir_departamento_dom, $sol_dir_provincia_dom, $sol_dir_localidad_ciudad_dom, $sol_cod_barrio_dom))
                    {
                        $error_texto .= $separador . 'Dirección domicilio - ' . $this->lang->line('sol_direccion_error');
                    }
                    
                    switch ((int)$sol_dir_referencia) {
                        case 1:
                            // Geolocalización
                            $sol_croquis = '';
                            break;

                        case 2:
                            // Croquis
                            $sol_geolocalizacion = '';
                            break;
                        
                        default:
                            // Sin selección
                            $sol_croquis = '';
                            $sol_geolocalizacion = '';
                            break;
                    }
                    
                    switch ((int)$sol_dir_referencia_dom) {
                        case 1:
                            // Geolocalización
                            $sol_croquis_dom = '';
                            break;

                        case 2:
                            // Croquis
                            $sol_geolocalizacion_dom = '';
                            break;
                        
                        default:
                            // Sin selección
                            $sol_croquis_dom = '';
                            $sol_geolocalizacion_dom = '';
                            break;
                    }
                    
                    // Validar sólo si marcó la Actividad Secundaria
                    if($DatosSolCredito[0]['sol_actividad_secundaria'] == 1)
                    {
                        // Se adecua el separador para incluir la referencia de Actividad Secundaria
                        $separador = '<br /> - ' . $this->lang->line('sol_secundaria_separador');
                        
                        if((int)$sol_dir_localidad_ciudad_sec <= 0)
                        {
                            $error_texto .= $separador . 'Dirección negocio/actividad - Mínimamente debe registrar ' . $this->lang->line('sol_dir_localidad_ciudad_sec') . '.';
                        }

                        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_direccion_trabajo_sec, 'string', 1, 0, 0); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_direccion_trabajo_sec') . '. ' . $aux_texto; }
                        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_edificio_trabajo_sec, 'string', 1, 0, 0); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_edificio_trabajo_sec') . '. ' . $aux_texto; }

                        if($sol_trabajo_lugar_sec == 99)
                        {
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_trabajo_lugar_otro_sec, 'string', 1, 3, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_trabajo_lugar_sec') . ' ' . $this->lang->line('sol_trabajo_lugar_otro_sec') . '. ' . $aux_texto; }
                        }
                        else
                        {
                            $sol_trabajo_lugar_otro_sec = '';
                        }

                        if($sol_trabajo_realiza_sec == 99)
                        {
                            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_trabajo_realiza_otro_sec, 'string', 1, 3, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_trabajo_realiza_sec') . ' ' . $this->lang->line('sol_trabajo_realiza_otro_sec') . '. ' . $aux_texto; }
                        }
                        else
                        {
                            $sol_trabajo_realiza_otro_sec = '';
                        }

                        if(!$this->mfunciones_microcreditos->validateRegDir($sol_dir_departamento_sec, $sol_dir_provincia_sec, $sol_dir_localidad_ciudad_sec, $sol_cod_barrio_sec))
                        {
                            $error_texto .= $separador . 'Dirección negocio/actividad - ' . $this->lang->line('sol_direccion_error');
                        }

                        switch ((int)$sol_dir_referencia_sec) {
                            case 1:
                                // Geolocalización
                                $sol_croquis_sec = '';
                                break;

                            case 2:
                                // Croquis
                                $sol_geolocalizacion_sec = '';
                                break;

                            default:
                                // Sin selección
                                $sol_croquis_sec = '';
                                $sol_geolocalizacion_sec = '';
                                break;
                        }

                        // Validación de imagen del croquis
                        $sol_croquis_sec = $this->mfunciones_microcreditos->validateIMG_simple($sol_croquis_sec);
                    }
                    else
                    {
                        // Limpiar campos
                        $sol_dir_departamento_sec = '';
                        $sol_dir_provincia_sec = '';
                        $sol_dir_localidad_ciudad_sec = '';
                        $sol_cod_barrio_sec = '';
                        $sol_direccion_trabajo_sec = '';
                        $sol_edificio_trabajo_sec = '';
                        $sol_numero_trabajo_sec = '';
                        $sol_trabajo_lugar_sec = 0;
                        $sol_trabajo_lugar_otro_sec = '';
                        $sol_trabajo_realiza_sec = 0;
                        $sol_trabajo_realiza_otro_sec = '';
                        $sol_dir_referencia_sec = 0;
                        $sol_geolocalizacion_sec = '';
                        $sol_croquis_sec = '';
                    }
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    // Se procede a borrar las imagenes de los mapas, ya que se generan en cada ocasión
                    $this->mfunciones_microcreditos->update_erase_img_mapas_all($estructura_id);
                    
                    // Validación de imagen del croquis
                    $sol_croquis = $this->mfunciones_microcreditos->validateIMG_simple($sol_croquis);
                    $sol_croquis_dom = $this->mfunciones_microcreditos->validateIMG_simple($sol_croquis_dom);
                    
                    // Guardar en la DB
                    
                    $this->mfunciones_microcreditos->update_sol_direccion(
                            
                            $sol_dir_departamento,
                            $sol_dir_provincia,
                            $sol_dir_localidad_ciudad,
                            $sol_cod_barrio,
                            $sol_direccion_trabajo,
                            $sol_edificio_trabajo,
                            $sol_numero_trabajo,
                            $sol_trabajo_lugar,
                            $sol_trabajo_lugar_otro,
                            $sol_trabajo_realiza,
                            $sol_trabajo_realiza_otro,
                            $sol_dir_referencia,
                            $sol_geolocalizacion,
                            $sol_croquis,
                            $sol_dir_departamento_dom,
                            $sol_dir_provincia_dom,
                            $sol_dir_localidad_ciudad_dom,
                            $sol_cod_barrio_dom,
                            $sol_direccion_dom,
                            $sol_edificio_dom,
                            $sol_numero_dom,
                            $sol_dom_tipo,
                            $sol_dom_tipo_otro,
                            $sol_dir_referencia_dom,
                            $sol_geolocalizacion_dom,
                            $sol_croquis_dom,
                            
                            // Actividad Secundaria
                            $sol_dir_departamento_sec,
                            $sol_dir_provincia_sec,
                            $sol_dir_localidad_ciudad_sec,
                            $sol_cod_barrio_sec,
                            $sol_direccion_trabajo_sec,
                            $sol_edificio_trabajo_sec,
                            $sol_numero_trabajo_sec,
                            $sol_trabajo_lugar_sec,
                            $sol_trabajo_lugar_otro_sec,
                            $sol_trabajo_realiza_sec,
                            $sol_trabajo_realiza_otro_sec,
                            $sol_dir_referencia_sec,
                            $sol_geolocalizacion_sec,
                            $sol_croquis_sec,
                            
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            );
                    
                    break;
                   
                case "sol_con_direccion":
                    
                    // Captura de Datos
                    
                    $sol_con_dir_departamento = $this->input->post('sol_con_dir_departamento');
                    $sol_con_dir_provincia = $this->input->post('sol_con_dir_provincia');
                    $sol_con_dir_localidad_ciudad = $this->input->post('sol_con_dir_localidad_ciudad');
                    $sol_con_cod_barrio = $this->input->post('sol_con_cod_barrio');
                    $sol_con_direccion_trabajo = $this->input->post('sol_con_direccion_trabajo');
                    $sol_con_edificio_trabajo = $this->input->post('sol_con_edificio_trabajo');
                    $sol_con_numero_trabajo = $this->input->post('sol_con_numero_trabajo');
                    $sol_con_trabajo_lugar = (int)$this->input->post('sol_con_trabajo_lugar');
                        $sol_con_trabajo_lugar_otro = $this->input->post('sol_con_trabajo_lugar_otro');
                    $sol_con_trabajo_realiza = (int)$this->input->post('sol_con_trabajo_realiza');
                        $sol_con_trabajo_realiza_otro = $this->input->post('sol_con_trabajo_realiza_otro');
                    $sol_con_dir_referencia = $this->input->post('sol_con_dir_referencia');
                    $sol_con_geolocalizacion = $this->input->post('sol_con_geolocalizacion');
                    $sol_con_croquis = $this->input->post('croquis_base64__1');
                    $sol_con_dir_departamento_dom = $this->input->post('sol_con_dir_departamento_dom');
                    $sol_con_dir_provincia_dom = $this->input->post('sol_con_dir_provincia_dom');
                    $sol_con_dir_localidad_ciudad_dom = $this->input->post('sol_con_dir_localidad_ciudad_dom');
                    $sol_con_cod_barrio_dom = $this->input->post('sol_con_cod_barrio_dom');
                    $sol_con_direccion_dom = $this->input->post('sol_con_direccion_dom');
                    $sol_con_edificio_dom = $this->input->post('sol_con_edificio_dom');
                    $sol_con_numero_dom = $this->input->post('sol_con_numero_dom');
                    $sol_con_dom_tipo = (int)$this->input->post('sol_con_dom_tipo');
                        $sol_con_dom_tipo_otro = $this->input->post('sol_con_dom_tipo_otro');
                    $sol_con_dir_referencia_dom = $this->input->post('sol_con_dir_referencia_dom');
                    $sol_con_geolocalizacion_dom = $this->input->post('sol_con_geolocalizacion_dom');
                    $sol_con_croquis_dom = $this->input->post('croquis_base64__2');
                    
                    // Validación de campos
                    
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_direccion_trabajo, 'string', 1, 0, 0); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_con_direccion_trabajo') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_edificio_trabajo, 'string', 1, 0, 0); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_con_edificio_trabajo') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_direccion_dom, 'string', 1, 0, 0); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_con_direccion_dom') . '. ' . $aux_texto; }
                    $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_edificio_dom, 'string', 1, 0, 0); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_con_edificio_dom') . '. ' . $aux_texto; }
                    
                    if($sol_con_trabajo_lugar == 99)
                    {
                        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_trabajo_lugar_otro, 'string', 1, 3, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_con_trabajo_lugar') . ' ' . $this->lang->line('sol_con_trabajo_lugar_otro') . '. ' . $aux_texto; }
                    }
                    else
                    {
                        $sol_con_trabajo_lugar_otro = '';
                    }

                    if($sol_con_trabajo_realiza == 99)
                    {
                        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_trabajo_realiza_otro, 'string', 1, 3, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_con_trabajo_realiza') . ' ' . $this->lang->line('sol_con_trabajo_realiza_otro') . '. ' . $aux_texto; }
                    }
                    else
                    {
                        $sol_con_trabajo_realiza_otro = '';
                    }

                    if($sol_con_dom_tipo == 99)
                    {
                        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($sol_con_dom_tipo_otro, 'string', 1, 3, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('sol_con_dom_tipo') . ' ' . $this->lang->line('sol_con_dom_tipo_otro') . '. ' . $aux_texto; }
                    }
                    else
                    {
                        $sol_con_dom_tipo_otro = '';
                    }
                    
                    if(!$this->mfunciones_microcreditos->validateRegDir($sol_con_dir_departamento, $sol_con_dir_provincia, $sol_con_dir_localidad_ciudad, $sol_con_cod_barrio))
                    {
                        $error_texto .= $separador . 'Dirección negocio/actividad - ' . $this->lang->line('sol_direccion_error');
                    }
                    
                    if(!$this->mfunciones_microcreditos->validateRegDir($sol_con_dir_departamento_dom, $sol_con_dir_provincia_dom, $sol_con_dir_localidad_ciudad_dom, $sol_con_cod_barrio_dom))
                    {
                        $error_texto .= $separador . 'Dirección domicilio - ' . $this->lang->line('sol_direccion_error');
                    }
                    
                    switch ((int)$sol_con_dir_referencia) {
                        case 1:
                            // Geolocalización
                            $sol_con_croquis = '';
                            break;

                        case 2:
                            // Croquis
                            $sol_con_geolocalizacion = '';
                            break;
                        
                        default:
                            // Sin selección
                            $sol_con_croquis = '';
                            $sol_con_geolocalizacion = '';
                            break;
                    }
                    
                    switch ((int)$sol_con_dir_referencia_dom) {
                        case 1:
                            // Geolocalización
                            $sol_con_croquis_dom = '';
                            break;

                        case 2:
                            // Croquis
                            $sol_con_geolocalizacion_dom = '';
                            break;
                        
                        default:
                            // Sin selección
                            $sol_con_croquis_dom = '';
                            $sol_con_geolocalizacion_dom = '';
                            break;
                    }
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    // Se procede a borrar las imagenes de los mapas, ya que se generan en cada ocasión
                    $this->mfunciones_microcreditos->update_erase_img_mapas_all($estructura_id);
                    
                    // Validación de imagen del croquis
                    $sol_con_croquis = $this->mfunciones_microcreditos->validateIMG_simple($sol_con_croquis);
                    $sol_con_croquis_dom = $this->mfunciones_microcreditos->validateIMG_simple($sol_con_croquis_dom);
                    
                    // Guardar en la DB
                    
                    $this->mfunciones_microcreditos->update_sol_con_direccion(
                            
                            $sol_con_dir_departamento,
                            $sol_con_dir_provincia,
                            $sol_con_dir_localidad_ciudad,
                            $sol_con_cod_barrio,
                            $sol_con_direccion_trabajo,
                            $sol_con_edificio_trabajo,
                            $sol_con_numero_trabajo,
                            $sol_con_trabajo_lugar,
                            $sol_con_trabajo_lugar_otro,
                            $sol_con_trabajo_realiza,
                            $sol_con_trabajo_realiza_otro,
                            $sol_con_dir_referencia,
                            $sol_con_geolocalizacion,
                            $sol_con_croquis,
                            $sol_con_dir_departamento_dom,
                            $sol_con_dir_provincia_dom,
                            $sol_con_dir_localidad_ciudad_dom,
                            $sol_con_cod_barrio_dom,
                            $sol_con_direccion_dom,
                            $sol_con_edificio_dom,
                            $sol_con_numero_dom,
                            $sol_con_dom_tipo,
                            $sol_con_dom_tipo_otro,
                            $sol_con_dir_referencia_dom,
                            $sol_con_geolocalizacion_dom,
                            $sol_con_croquis_dom,
                            
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            );
                    
                    break;
                    
                case "referencias":
                    
                    // Captura de Datos
                    
                    $rp_nombres = ucwords($this->input->post('rp_nombres', TRUE));
                    $rp_primer_apellido = ucwords($this->input->post('rp_primer_apellido', TRUE));
                    $rp_segundo_apellido = ucwords($this->input->post('rp_segundo_apellido', TRUE));
                    $rp_direccion = $this->input->post('rp_direccion', TRUE);
                    $rp_notelefonicos = $this->input->post('rp_notelefonicos', TRUE);
                    $rp_nexo_cliente = $this->input->post('rp_nexo_cliente', TRUE);
                    $con_primer_nombre = ucwords($this->input->post('con_primer_nombre', TRUE));
                    $con_segundo_nombre = ucwords($this->input->post('con_segundo_nombre', TRUE));
                    $con_primera_pellido = ucwords($this->input->post('con_primera_pellido', TRUE));
                    $con_segundoa_pellido = ucwords($this->input->post('con_segundoa_pellido', TRUE));
                    $con_acteconomica_principal = '749900';//$this->input->post('con_acteconomica_principal', TRUE);
                    
                    $di_estadocivil = $this->input->post('di_estadocivil', TRUE);
                    
                    // Validación de campos
                    
                    if($rp_nombres == '' || strlen((string)$rp_nombres) < 3) { $error_texto .= $separador . 'Referencia - ' . $this->lang->line('rp_nombres'); }
                    if($rp_primer_apellido == '' || strlen((string)$rp_primer_apellido) < 3) { $error_texto .= $separador . 'Referencia - ' . $this->lang->line('rp_primer_apellido'); }
                    if($rp_segundo_apellido != '' && strlen((string)$rp_segundo_apellido) < 3) { $error_texto .= $separador . 'Referencia - ' . $this->lang->line('rp_segundo_apellido'); }
                    
                    if($rp_notelefonicos == '' || strlen((string)$rp_notelefonicos) != 8) { $error_texto .= $separador . 'Referencia - ' . $this->lang->line('rp_notelefonicos'); }
                    if($rp_notelefonicos != '') { if((string)$rp_notelefonicos[0] != '6' && (string)$rp_notelefonicos[0] != '7') { $error_texto .= $separador . 'Referencia - ' . $this->lang->line('rp_notelefonicos') . ' debe empezar con 6 o 7'; }}
                    
                    if($rp_nexo_cliente == '-1') { $error_texto .= $separador . $this->lang->line('rp_nexo_cliente'); }
                    
                    if($di_estadocivil == 'CA')
                    {
                        if($con_primer_nombre == '' || strlen((string)$con_primer_nombre) < 3) { $error_texto .= $separador . 'Cónyuge - ' . $this->lang->line('con_primer_nombre'); }
                        if($con_segundo_nombre != '' && strlen((string)$con_segundo_nombre) < 3) { $error_texto .= $separador . 'Cónyuge - ' . $this->lang->line('con_segundo_nombre'); }
                        if($con_primera_pellido == '' || strlen((string)$con_primera_pellido) < 3) { $error_texto .= $separador . 'Cónyuge - ' . $this->lang->line('con_primera_pellido'); }
                        if($con_segundoa_pellido != '' && strlen((string)$con_segundoa_pellido) < 3) { $error_texto .= $separador . 'Cónyuge - ' . $this->lang->line('con_segundoa_pellido'); }
                    }
                    else
                    {
                        $con_primer_nombre = '';
                        $con_segundo_nombre = '';
                        $con_primera_pellido = '';
                        $con_segundoa_pellido = '';
                    }
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    // Guardar en la DB
                    
                    // Paso 1: Se obtiene el Código Terceros relacionado
                    
                    $codigo_terceros = $this->mfunciones_generales->ObtenerCodigoTercerosProspecto($estructura_id);
                    
                    // Paso 2: Se guarda
                    
                    $this->mfunciones_logica->update_referencias(
                            $rp_nombres,
                            $rp_primer_apellido,
                            $rp_segundo_apellido,
                            $rp_direccion,
                            $rp_notelefonicos,
                            $rp_nexo_cliente,
                            $con_primer_nombre,
                            $con_segundo_nombre,
                            $con_primera_pellido,
                            $con_segundoa_pellido,
                            $con_acteconomica_principal,
                            $accion_usuario,
                            $accion_fecha,
                            $codigo_terceros
                            );
                    
                    break;
                
                case "actividad_economica":
                    
                    // Captura de Datos
                    
                    $ae_sector_economico = $this->input->post('ae_sector_economico', TRUE);
                    $ae_subsector_economico = $this->input->post('ae_subsector_economico', TRUE);
                    
                    $ae_actividad_ocupacion = $this->input->post('ae_actividad_ocupacion', TRUE);
                    
                    $ae_actividad_fie = $this->input->post('ae_actividad_fie', TRUE);
                    $ae_ambiente = $this->input->post('ae_ambiente', TRUE);
                    $emp_nombre_empresa = $this->input->post('emp_nombre_empresa', TRUE);
                    $emp_direccion_trabajo = $this->input->post('emp_direccion_trabajo', TRUE);
                    $emp_telefono_faxtrabaj = $this->input->post('emp_telefono_faxtrabaj', TRUE);
                    $emp_tipo_empresa = $this->input->post('emp_tipo_empresa', TRUE);
                    $emp_antiguedad_empresa = '0';//$this->input->post('emp_antiguedad_empresa', TRUE);
                    $emp_codigo_actividad = $this->input->post('emp_codigo_actividad', TRUE);
                    $emp_descripcion_cargo = $this->input->post('emp_descripcion_cargo', TRUE);
                    $emp_fecha_ingreso = $this->input->post('emp_fecha_ingreso', TRUE);
                    
                    $coordenadas_geo_trab = $this->input->post('coordenadas_geo_trab', TRUE);
                    
                    $dir_departamento_neg = $this->input->post('dir_departamento_neg', TRUE);
                    $dir_provincia_neg = $this->input->post('dir_provincia_neg', TRUE);
                    $dir_localidad_ciudad_neg = $this->input->post('dir_localidad_ciudad_neg', TRUE);
                    $dir_barrio_zona_uv_neg = $this->input->post('dir_barrio_zona_uv_neg', TRUE);
                    $dir_av_calle_pasaje_neg = $this->input->post('dir_av_calle_pasaje_neg', TRUE);
                    $dir_edif_cond_urb_neg = $this->input->post('dir_edif_cond_urb_neg', TRUE);
                    $dir_numero_neg = $this->input->post('dir_numero_neg', TRUE);
                    
                    // Validación de campos
                    
                    if($ae_sector_economico == '-1') { $error_texto .= $separador . $this->lang->line('ae_sector_economico'); }
                    if($ae_subsector_economico == '-1') { $error_texto .= $separador . $this->lang->line('ae_subsector_economico'); }
                    if($ae_actividad_ocupacion == '-1') { $error_texto .= $separador . $this->lang->line('ae_actividad_ocupacion'); }
                    if($ae_actividad_fie == '-1') { $error_texto .= $separador . $this->lang->line('ae_actividad_fie'); }
                    
                    if($ae_ambiente == '') { $error_texto .= $separador . $this->lang->line('ae_ambiente'); }
                    
                    if($coordenadas_geo_trab == '') { $error_texto .= $separador . $this->lang->line('coordenadas_geo_trab'); }
                    
                    // Si la Actividad Económica no es Ingreso Fijo, entonces los campos se borran
                    if($ae_sector_economico != 'V')
                    {
                        $emp_nombre_empresa = '';
                        $emp_direccion_trabajo = '';
                        $emp_telefono_faxtrabaj = '';
                        $emp_tipo_empresa = '';
                        $emp_antiguedad_empresa = '0';
                        $emp_codigo_actividad = '74990';
                        $emp_descripcion_cargo = '';
                        $emp_fecha_ingreso = '1990-01-01';
                    }
                    else
                    {
                        // Si seleccionó Jubilado o Rentista
                        if($ae_actividad_ocupacion == '99001')
                        {
                            $emp_nombre_empresa = 'Jubilado';
                            $emp_direccion_trabajo = 'Jubilado';
                            $emp_telefono_faxtrabaj = '11223344';
                            $emp_tipo_empresa = 'PRI';
                            $emp_antiguedad_empresa = '0';
                            $emp_codigo_actividad = '99001';
                            $emp_descripcion_cargo = 'Jubilado';
                            $emp_fecha_ingreso = '2020-01-01';
                        }
                        
                        if($emp_nombre_empresa == '') { $error_texto .= $separador . $this->lang->line('emp_nombre_empresa'); }
                        if($emp_direccion_trabajo == '') { $error_texto .= $separador . $this->lang->line('emp_direccion_trabajo'); }
                        if($emp_tipo_empresa == '-1') { $error_texto .= $separador . $this->lang->line('emp_tipo_empresa'); }
                        if($emp_antiguedad_empresa == '') { $error_texto .= $separador . $this->lang->line('emp_antiguedad_empresa'); }
                        if((int)$emp_codigo_actividad <= 0) { $error_texto .= $separador . $this->lang->line('emp_codigo_actividad'); }
                        
                        if($emp_descripcion_cargo == '' || strlen((string)$emp_descripcion_cargo) < 5 || strlen((string)$emp_descripcion_cargo) > 30) { $error_texto .= $separador . $this->lang->line('emp_descripcion_cargo') . '. Además debe tener entre 5 a 30 caracteres.'; }
                        if($this->mfunciones_generales->ValidaCadena($emp_descripcion_cargo) != '') { $error_texto .= $separador . $this->lang->line('emp_descripcion_cargo') . $this->mfunciones_generales->ValidaCadena($emp_descripcion_cargo); }
                        
                        if($emp_fecha_ingreso == '') { $error_texto .= $separador . $this->lang->line('emp_fecha_ingreso'); }
                        
                        if($emp_telefono_faxtrabaj == '' || strlen((string)$emp_telefono_faxtrabaj) < 7) { $error_texto .= $separador . $this->lang->line('emp_telefono_faxtrabaj') . '. Además debe tener entre 7 a 8 dígitos.'; }
                        
                        // Fechas
                    
                        $today_dt = new DateTime(date("Y-m-d"));

                        // Vencimiento CI       No puede ser fecha futura
                        if ($today_dt <  new DateTime($emp_fecha_ingreso)) { $error_texto .= $separador . $this->lang->line('emp_fecha_ingreso') . ' no puede ser posterior a la fecha actual.'; }
                        
                    }
                    
                    // Tratamiento Direccion del Negocio
                    if($ae_sector_economico != 'V' && $ae_sector_economico != 'VI' && $ae_sector_economico != '-1')
                    {
                        if((int)$dir_departamento_neg == -1) { $error_texto .= $separador . 'Actividad ' . $this->lang->line('dir_departamento'); }
                        if((int)$dir_provincia_neg == -1) { $error_texto .= $separador . 'Actividad ' . $this->lang->line('dir_provincia'); }
                        if((int)$dir_localidad_ciudad_neg == -1) { $error_texto .= $separador . 'Actividad ' . $this->lang->line('dir_localidad_ciudad'); }
                        if((int)$dir_barrio_zona_uv_neg == -1) { $error_texto .= $separador . 'Actividad ' . $this->lang->line('dir_barrio_zona_uv'); }
                        
                        if($dir_av_calle_pasaje_neg == '' || strlen((string)$dir_av_calle_pasaje_neg) < 5 || strlen((string)$dir_av_calle_pasaje_neg) > 70) { $error_texto .= $separador . $this->lang->line('dir_av_calle_pasaje') . '. Además debe tener entre 5 a 70 caracteres.'; }
                        if($this->mfunciones_generales->ValidaCadena($dir_av_calle_pasaje_neg) != '') { $error_texto .= $separador . $this->lang->line('dir_av_calle_pasaje') . $this->mfunciones_generales->ValidaCadena($dir_av_calle_pasaje_neg); }

                        if($dir_edif_cond_urb_neg != '')
                        {
                            if(strlen((string)$dir_edif_cond_urb_neg) < 5 || strlen((string)$dir_edif_cond_urb_neg) > 20) { $error_texto .= $separador . $this->lang->line('dir_edif_cond_urb') . '. Además debe tener entre 5 a 20 caracteres.'; }
                            if($this->mfunciones_generales->ValidaCadena($dir_edif_cond_urb_neg) != '') { $error_texto .= $separador . $this->lang->line('dir_edif_cond_urb') . $this->mfunciones_generales->ValidaCadena($dir_edif_cond_urb_neg); }
                        }
                            
                        //if($dir_numero_neg == '' || strlen((string)$dir_numero_neg) > 20) { $error_texto .= $separador . $this->lang->line('dir_numero'); }
                        
                    }
                    else
                    {
                        $dir_departamento_neg = '';
                        $dir_provincia_neg = '';
                        $dir_localidad_ciudad_neg = '';
                        $dir_barrio_zona_uv_neg = '';
                        $dir_av_calle_pasaje_neg = '';
                        $dir_edif_cond_urb_neg = '';
                        $dir_numero_neg = '';
                    }
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    // Guardar en la DB
                    
                    // Paso 1: Se obtiene el Código Terceros relacionado
                    
                    $codigo_terceros = $this->mfunciones_generales->ObtenerCodigoTercerosProspecto($estructura_id);
                    
                    // Paso 2: Se guarda
                    
                    $this->mfunciones_logica->update_actividad_economica(
                            $ae_sector_economico,
                            $ae_subsector_economico,
                            $ae_actividad_ocupacion,
                            $ae_actividad_fie,
                            $ae_ambiente,
                            $emp_nombre_empresa,
                            $emp_direccion_trabajo,
                            $emp_telefono_faxtrabaj,
                            $emp_tipo_empresa,
                            $emp_antiguedad_empresa,
                            $emp_codigo_actividad,
                            $emp_descripcion_cargo,
                            $emp_fecha_ingreso,
                            $coordenadas_geo_trab,
                            
                            $dir_departamento_neg,
                            $dir_provincia_neg,
                            $dir_localidad_ciudad_neg,
                            $dir_barrio_zona_uv_neg,
                            $dir_av_calle_pasaje_neg,
                            $dir_edif_cond_urb_neg,
                            $dir_numero_neg,
                            
                            $accion_usuario,
                            $accion_fecha,
                            $codigo_terceros
                            );
                    
                    break;
                
                case "ubicacion":
                    
                    // Captura de Datos
                    
                    $dir_tipo_direccion = $this->input->post('dir_tipo_direccion', TRUE);
                    $dir_departamento = $this->input->post('dir_departamento', TRUE);
                    $dir_provincia = $this->input->post('dir_provincia', TRUE);
                    $dir_localidad_ciudad = $this->input->post('dir_localidad_ciudad', TRUE);
                    $dir_barrio_zona_uv = $this->input->post('dir_barrio_zona_uv', TRUE);
                    $dir_ubicacionreferencial = $this->input->post('dir_ubicacionreferencial', TRUE);
                    $dir_av_calle_pasaje = $this->input->post('dir_av_calle_pasaje', TRUE);
                    $dir_edif_cond_urb = $this->input->post('dir_edif_cond_urb', TRUE);
                    $dir_numero = $this->input->post('dir_numero', TRUE);
                    
                    $coordenadas_geo_dom = $this->input->post('coordenadas_geo_dom', TRUE);
                    
                    // Validación de campos
                    
                    //if($this->mfunciones_generales->VerificaCorreo($direccion_email) == false) { $error_texto .= $separador . $this->lang->line('general_email'); }

                    if((int)$dir_tipo_direccion == -1) { $error_texto .= $separador . $this->lang->line('dir_tipo_direccion'); }
                    
                    if((int)$dir_departamento != 14)
                    {
                        if((int)$dir_departamento == -1) { $error_texto .= $separador . $this->lang->line('dir_departamento'); }
                        if((int)$dir_provincia == -1) { $error_texto .= $separador . $this->lang->line('dir_provincia'); }
                        if((int)$dir_localidad_ciudad == -1) { $error_texto .= $separador . $this->lang->line('dir_localidad_ciudad'); }
                        if((int)$dir_barrio_zona_uv == -1) { $error_texto .= $separador . $this->lang->line('dir_barrio_zona_uv'); }
                    }
                    
                    if($coordenadas_geo_dom == '') { $error_texto .= $separador . $this->lang->line('coordenadas_geo_dom'); }
                    
                    
                    if($dir_av_calle_pasaje == '' || strlen((string)$dir_av_calle_pasaje) < 5 || strlen((string)$dir_av_calle_pasaje) > 70) { $error_texto .= $separador . $this->lang->line('dir_av_calle_pasaje') . '. Además debe tener entre 5 a 70 caracteres.'; }
                    if($this->mfunciones_generales->ValidaCadena($dir_av_calle_pasaje) != '') { $error_texto .= $separador . $this->lang->line('dir_av_calle_pasaje') . $this->mfunciones_generales->ValidaCadena($dir_av_calle_pasaje); }
                    
                    if($dir_edif_cond_urb != '')
                    {
                    
                        if(strlen((string)$dir_edif_cond_urb) < 5 || strlen((string)$dir_edif_cond_urb) > 20) { $error_texto .= $separador . $this->lang->line('dir_edif_cond_urb') . '. Además debe tener entre 5 a 20 caracteres.'; }
                        if($this->mfunciones_generales->ValidaCadena($dir_edif_cond_urb) != '') { $error_texto .= $separador . $this->lang->line('dir_edif_cond_urb') . $this->mfunciones_generales->ValidaCadena($dir_edif_cond_urb); }
                    }
                    
                    //if($dir_numero == '' || strlen((string)$dir_numero) > 20) { $error_texto .= $separador . $this->lang->line('dir_numero'); }
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    // Guardar en la DB
                    
                    // Paso 1: Se obtiene el Código Terceros relacionado
                    
                    $codigo_terceros = $this->mfunciones_generales->ObtenerCodigoTercerosProspecto($estructura_id);
                    
                    // Paso 2: Se guarda
                    
                    $this->mfunciones_logica->update_ubicacion(
                            $dir_tipo_direccion,
                            $dir_departamento,
                            $dir_provincia,
                            $dir_localidad_ciudad,
                            $dir_barrio_zona_uv,
                            $dir_ubicacionreferencial,
                            $dir_av_calle_pasaje,
                            $dir_edif_cond_urb,
                            $dir_numero,
                            $coordenadas_geo_dom,
                            $accion_usuario,
                            $accion_fecha,
                            $codigo_terceros
                            );
                    
                    break;
                
                case "datos_personales":
                    
                    // Captura de Datos
                    
                    $tipo_cuenta = $this->input->post('tipo_cuenta', TRUE);
                    
                    $ddc_ciudadania_usa = $this->input->post('ddc_ciudadania_usa', TRUE);
                    $ddc_motivo_permanencia_usa = $this->input->post('ddc_motivo_permanencia_usa', TRUE);
                    
                    $cI_confirmacion_id = $cI_numeroraiz = $this->input->post('cI_numeroraiz', TRUE);
                    $cI_complemento = strtoupper($this->input->post('cI_complemento', TRUE));
                    $cI_lugar_emisionoextension = $this->input->post('cI_lugar_emisionoextension', TRUE);
                    $di_primernombre = ucwords($this->input->post('di_primernombre', TRUE));
                    $di_segundo_otrosnombres = ucwords($this->input->post('di_segundo_otrosnombres', TRUE));
                    $di_primerapellido = ucwords($this->input->post('di_primerapellido', TRUE));
                    $di_segundoapellido = ucwords($this->input->post('di_segundoapellido', TRUE));
                    $di_fecha_nacimiento = $this->input->post('di_fecha_nacimiento', TRUE);
                    $di_fecha_vencimiento = $this->input->post('di_fecha_vencimiento', TRUE);
                    $di_indefinido = $this->input->post('di_indefinido', TRUE);
                    $di_genero = $this->input->post('di_genero', TRUE);
                    $di_estadocivil = $this->input->post('di_estadocivil', TRUE);
                    $di_apellido_casada = $this->input->post('di_apellido_casada', TRUE);
                    $direccion_email = $this->input->post('direccion_email', TRUE);
                    $dir_notelefonico = $this->input->post('dir_notelefonico', TRUE);
                    $envio = $this->input->post('envio', TRUE);
                    $dd_profesion = $this->input->post('dd_profesion', TRUE);
                    $dd_nivel_estudios = $this->input->post('dd_nivel_estudios', TRUE);
                    $dd_dependientes = 1;//$this->input->post('dd_dependientes', TRUE);
                    $dd_proposito_rel_comercial = $this->input->post('dd_proposito_rel_comercial', TRUE);
                    $dec_ingresos_mensuales = $this->input->post('dec_ingresos_mensuales', TRUE);
                    $dec_nivel_egresos = $dec_ingresos_mensuales;//$this->input->post('dec_nivel_egresos', TRUE);
                    
                    // Validación de campos
                    if($di_estadocivil == '-1') { $error_texto .= $separador . $this->lang->line('di_estadocivil'); }

                    if($tipo_cuenta == '') { $error_texto .= $separador . $this->lang->line('tipo_cuenta'); }
                    if($cI_numeroraiz == '' || strlen((string)$cI_numeroraiz) < 3) { $error_texto .= $separador . $this->lang->line('cI_numeroraiz'); }
                    
                    // Validacion del Complemento
                    if($cI_complemento != '')
                    {
                        if(strlen((string)$cI_complemento) != 2)
                        {
                            $error_texto .= $separador . $this->lang->line('cI_complemento');
                        }
                        elseif(!preg_match('/^(?!\d+$)(?![a-zA-Z]+$)[a-zA-Z\d]{2}$/', $cI_complemento))
                        {
                            $error_texto .= $separador . 'El complemento no puede contener 2 letras juntas o 2 números juntos';
                        }
                    }
                    
                    if($di_primernombre == '' || strlen((string)$di_primernombre) < 3) { $error_texto .= $separador . $this->lang->line('di_primernombre'); }
                    if($di_segundo_otrosnombres != '' && strlen((string)$di_segundo_otrosnombres) < 3) { $error_texto .= $separador . $this->lang->line('di_segundo_otrosnombres'); }
                    if($di_primerapellido == '' || strlen((string)$di_primerapellido) < 3) { $error_texto .= $separador . $this->lang->line('di_primerapellido'); }
                    if($di_segundoapellido != '' && strlen((string)$di_segundoapellido) < 3) { $error_texto .= $separador . $this->lang->line('di_segundoapellido'); }
                    if($cI_lugar_emisionoextension == '-1') { $cI_lugar_emisionoextension = ''; /*$error_texto .= $separador . $this->lang->line('cI_lugar_emisionoextension');*/ }
                    if($this->mfunciones_generales->VerificaCorreo($direccion_email) == false) { $error_texto .= $separador . $this->lang->line('direccion_email'); }
                    
                    if($dir_notelefonico == '' || strlen((string)$dir_notelefonico) != 8) { $error_texto .= $separador . $this->lang->line('dir_notelefonico') . ' debe tener 8 digitos'; }
                    if((string)$dir_notelefonico[0] != '6' && (string)$dir_notelefonico[0] != '7') { $error_texto .= $separador . $this->lang->line('dir_notelefonico') . ' debe empezar con 6 o 7'; }
                    
                    
                    if($ddc_ciudadania_usa == '') { $error_texto .= $separador . 'Debe marcar una opcion para la Declaración de Ciudadanía'; }
                    
                    //if((int)$ddc_ciudadania_usa == 1 && $ddc_motivo_permanencia_usa == '') { $error_texto .= $separador . $this->lang->line('ddc_motivo_permanencia_usa'); }
                    
                    if($di_apellido_casada != '' && strlen((string)$di_apellido_casada) < 3) { $error_texto .= $separador . $this->lang->line('di_apellido_casada'); }
                    
                    if((int)$envio == 0) { $error_texto .= $separador . $this->lang->line('envio'); }
                    
                    if($di_genero == '-1') { $error_texto .= $separador . $this->lang->line('di_genero'); }
                    if($dd_profesion == '-1') { $error_texto .= $separador . $this->lang->line('dd_profesion'); }
                    if($dd_nivel_estudios == '-1') { $error_texto .= $separador . $this->lang->line('dd_nivel_estudios'); }
                    if($dec_ingresos_mensuales == '-1') { $error_texto .= $separador . $this->lang->line('dec_ingresos_mensuales'); }
                    
                    
                    if($di_fecha_vencimiento == '') { $error_texto .= $separador . $this->lang->line('di_fecha_vencimiento'); }
                    if($di_fecha_nacimiento == '') { $error_texto .= $separador . $this->lang->line('di_fecha_nacimiento'); }
                    
                    // Fechas
                    
                    $today_dt = new DateTime(date("Y-m-d"));
                    
                    if($di_indefinido == 0)
                    {
                        // Se establece los rangos SEGIP, rango entre 01/11/2019 y 31/08/2020
                        $rango_segip_start = new DateTime(SEGIP_VIGENCIA_FECHA_INI);
                        $rango_segip_end = new DateTime(SEGIP_VIGENCIA_FECHA_FIN);

                        // Se pregunta si la fecha de vencimiento está entre entre los rangos SEGIP
                        // True:  Asignar valor rango_segip_end
                        // False: Mantener valor registrado por el usuario

                        if(new DateTime($di_fecha_vencimiento) >= $rango_segip_start && new DateTime($di_fecha_vencimiento) <= $rango_segip_end)
                        {
                            $di_fecha_vencimiento = $rango_segip_end->format('Y-m-d');
                        }
                        
                        // Vencimiento CI       No puede ser fecha pasada
                        if ($today_dt >  new DateTime($di_fecha_vencimiento)) { $error_texto .= $separador . $this->lang->line('di_fecha_vencimiento') . ' no puede ser anterior a la fecha actual.'; }
                    }
                    
                    // Nacimiento       Debe tener más de 18 años
                    
                    $interval = $today_dt->diff(new DateTime($di_fecha_nacimiento));
                    
                    if ($interval->y<18) { $error_texto .= $separador . 'El cliente debe ser mayor de 18 años.'; }
                    
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    if((int)$ddc_ciudadania_usa == 0)
                    {
                        $ddc_motivo_permanencia_usa = '';
                    }
                    
                    // Guardar en la DB
                    
                    // Paso 1: Se obtiene el Código Terceros relacionado
                    
                    $codigo_terceros = $this->mfunciones_generales->ObtenerCodigoTercerosProspecto($estructura_id);
                    
                    // Paso 2: Se guarda
                    
                    $this->mfunciones_logica->update_datos_personales(
                            $ddc_ciudadania_usa,
                            $ddc_motivo_permanencia_usa,
                            $tipo_cuenta,
                            $cI_numeroraiz,
                            $cI_confirmacion_id, 
                            $cI_complemento,
                            $cI_lugar_emisionoextension,
                            $di_primernombre,
                            $di_segundo_otrosnombres,
                            $di_primerapellido,
                            $di_segundoapellido,
                            $di_fecha_nacimiento,
                            $di_fecha_vencimiento,
                            $di_indefinido,
                            $di_genero,
                            $di_estadocivil,
                            $di_apellido_casada,
                            $direccion_email,
                            $dir_notelefonico,
                            $envio,
                            $dd_profesion,
                            $dd_nivel_estudios,
                            $dd_dependientes,
                            $dd_proposito_rel_comercial,
                            $dec_ingresos_mensuales,
                            $dec_nivel_egresos,
                            $accion_usuario,
                            $accion_fecha,
                            $codigo_terceros
                            );
                    
                    // Paso Extra: Se actualiza el Prospecto asociado
                    
                    $general_solicitante = $di_primernombre . ' ' . $di_primerapellido . ' ' . $di_segundoapellido;
                    $general_telefono = $dir_notelefonico;
                    $general_email = $direccion_email;
                    $general_ci = $cI_numeroraiz . ' ' . $cI_complemento;
                    
                    $this->mfunciones_logica->update_referencia_prospectos(
                            $general_solicitante,
                            $general_telefono,
                            $general_email,
                            $general_ci,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            );
                    
                    break;
                
                case "datos_generales":

                    // Captura de Datos
                    
                    $ejecutivo_id = $this->input->post('ejecutivo_id', TRUE);
                    $camp_id = $this->input->post('camp_id', TRUE);
                    $general_categoria = $this->input->post('general_categoria', TRUE);
                    
                    
                    $general_solicitante = ucwords($this->input->post('general_solicitante', TRUE));
                    $general_telefono = $this->input->post('general_telefono', TRUE);
                    //$general_email = $this->input->post('general_email', TRUE);
                    $general_email = 'sinregistro@mail.com';
                    
                    $general_direccion = $this->input->post('general_direccion', TRUE);
                    
                    $general_actividad = $this->input->post('general_actividad', TRUE);
                    $general_ci = $this->input->post('general_ci', TRUE);
                    $general_ci_extension = $this->input->post('general_ci_extension', TRUE);
                    $general_destino = $this->input->post('general_destino', TRUE);
                    
                    $general_comentarios = $this->input->post('general_comentarios', TRUE);
                    
                    $general_interes = $this->input->post('general_interes', TRUE);
                    
                    $arrProductos = $this->input->post('producto_list', TRUE);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrProductos);
                    
                    $operacion_efectivo = $this->input->post('operacion_efectivo', TRUE);
                    $operacion_dias = $this->input->post('operacion_dias', TRUE);
                   
                    $operacion_antiguedad = $this->input->post('operacion_antiguedad', TRUE);
                    $operacion_tiempo = $this->input->post('operacion_tiempo', TRUE);
                    
                    $operacion_antiguedad_ano = $this->input->post('operacion_antiguedad_ano', TRUE);
                    $operacion_antiguedad_mes = $this->input->post('operacion_antiguedad_mes', TRUE);
                    $operacion_tiempo_ano = $this->input->post('operacion_tiempo_ano', TRUE);
                    $operacion_tiempo_mes = $this->input->post('operacion_tiempo_mes', TRUE);
                    
                    $aclarar_contado = $this->input->post('aclarar_contado', TRUE);
                    $aclarar_credito = $this->input->post('aclarar_credito', TRUE);
                    
                    // Validación de campos
                    
                    if($general_solicitante == '') { $error_texto .= $separador . $this->lang->line('general_solicitante'); }
                    if($general_telefono == '') { $error_texto .= $separador . $this->lang->line('general_telefono'); }
                    if($this->mfunciones_generales->VerificaCorreo($general_email) == false) { $error_texto .= $separador . $this->lang->line('general_email'); }
                    
                    if($general_actividad == '') { $error_texto .= $separador . $this->lang->line('general_actividad'); }
                    if($general_ci == '') { $error_texto .= $separador . $this->lang->line('general_ci'); }
                    if($general_ci_extension == '') { $error_texto .= $separador . $this->lang->line('general_ci') . ' extensión'; }
                    
                    if($tipo_registro != 'unidad_familiar')
                    {
                        if($general_categoria == 1)
                        {
                            if($general_destino == '') { $error_texto .= $separador . $this->lang->line('general_destino'); }
                            //if($general_comentarios == '') { $error_texto .= $separador . $this->lang->line('general_comentarios'); }
                        }
                        //if($general_interes == '') { $error_texto .= $separador . $this->lang->line('general_interes'); }
                        
                        if($codigo_rubro != 4)
                        {
                            if($operacion_efectivo == '') { $error_texto .= $separador . $this->lang->line('operacion_efectivo'); }
                            if($operacion_dias == '') { $error_texto .= $separador . $this->lang->line('operacion_dias'); }
                            if($aclarar_contado == '') { $error_texto .= $separador . $this->lang->line('aclarar_contado'); }
                            if($aclarar_credito == '') { $error_texto .= $separador . $this->lang->line('aclarar_credito'); }
                            
                            if(($aclarar_credito+$aclarar_contado) != 100) { $error_texto .= $separador . 'Los porcentajes del tipo de venta deben sumar 100%'; }
                        }
                        
                        if($operacion_antiguedad_ano+$operacion_antiguedad_mes <= 0) { $error_texto .= $separador . $this->lang->line('operacion_antiguedad'); }
                        if($operacion_tiempo_ano+$operacion_tiempo_mes <= 0) { $error_texto .= $separador . $this->lang->line('operacion_tiempo'); }
                        
                        if($operacion_antiguedad_mes > 12) { $error_texto .= $separador . 'Los meses de la antigüedad no pueden ser mayor a 12.'; }
                        if($operacion_tiempo_mes > 12) { $error_texto .= $separador . 'Los meses del tiempo de la actividad no pueden ser mayor a 12.'; }
                        
                    }
                    else
                    {
                        if($camp_id == '' || $camp_id <= 0) { $error_texto .= $separador . 'Debe seleccionar el Rubro'; }
                    }
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    // Guardar en la DB
                    
                    switch ($tipo_registro) {
                        case 'unidad_familiar':
                            
                            $nuevo_lead = $this->mfunciones_logica->insert_datos_generales_familiar(
                                    $camp_id,
                                    $ejecutivo_id,
                                    $general_solicitante,
                                    $general_telefono,
                                    $general_email,
                                    $general_direccion,
                                    $general_actividad,
                                    $general_ci,
                                    $general_ci_extension,
                                    $accion_usuario,
                                    $accion_fecha,
                                    $estructura_id
                                    );

                            $this->mfunciones_generales->SeguimientoHitoProspecto($nuevo_lead, 1, -1, $accion_usuario, $accion_fecha, 0);
                                    $nombre_rubro = $this->mfunciones_generales->GetValorCatalogo($camp_id, 'nombre_rubro');
                            
                            $arrDepende = $this->mfunciones_logica->GetProspectoDepende($nuevo_lead);
                            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDepende);

                            if (isset($arrDepende[0])) 
                            {
                                $general_depende = $arrDepende[0]['general_depende'];
                            }
                                    
                            $this->mfunciones_logica->InsertSeguimientoProspecto($general_depende, 1, 9, 'Registro Unidad Familiar Rubro ' . $nombre_rubro . ' (Código interno ' . $nuevo_lead . ')', $accion_usuario, $accion_fecha);
                            
                            js_invocacion_javascript('$("div.modal-backdrop").remove(); Ajax_CargadoGeneralPagina("Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");');
                            exit();
                            
                            break;

                        default:
                            
                            // INSERTAR LOS PRODUCTOS SELECCIONADOS

                                // 1. Se eliminan los servicios de la solicitud
                                $this->mfunciones_logica->EliminarActividadesProspecto($estructura_id);

                                // 2. Se registran los servicios seleccionados

                                if (isset($arrProductos[0]))
                                {
                                    foreach ($arrProductos as $key => $value) 
                                    {
                                        $this->mfunciones_logica->InsertarActividadesProspecto($estructura_id, $value, $accion_usuario, $accion_fecha);
                                    }
                                }

                            $this->mfunciones_logica->update_datos_generales(
                                    $general_solicitante,
                                    $general_telefono,
                                    $general_email,
                                    $general_direccion,
                                    $general_actividad,
                                    $general_ci,
                                    $general_ci_extension,
                                    $general_destino,
                                    $general_comentarios,
                                    $general_interes,
                                    $operacion_efectivo,
                                    $operacion_dias,
                                    $operacion_antiguedad_ano,
                                    $operacion_antiguedad_mes,
                                    $operacion_tiempo_ano,
                                    $operacion_tiempo_mes,
                                    $aclarar_contado,
                                    $aclarar_credito,
                                    $accion_usuario,
                                    $accion_fecha,
                                    $estructura_id
                                    );
                            
                            break;
                    }
                    
                    break;

                case "frecuencia_venta":
                    
                    // Captura de Datos
                    
                    $frec_seleccion = $this->input->post('frec_seleccion', TRUE);
                    $frec_dia_lunes = $this->input->post('frec_dia_lunes', TRUE);
                    $frec_dia_martes = $this->input->post('frec_dia_martes', TRUE);
                    $frec_dia_miercoles = $this->input->post('frec_dia_miercoles', TRUE);
                    $frec_dia_jueves = $this->input->post('frec_dia_jueves', TRUE);
                    $frec_dia_viernes = $this->input->post('frec_dia_viernes', TRUE);
                    $frec_dia_sabado = $this->input->post('frec_dia_sabado', TRUE);
                    $frec_dia_domingo = $this->input->post('frec_dia_domingo', TRUE);
                    $frec_dia_semana_sel_brm = $this->input->post('frec_dia_semana_sel_brm', TRUE);
                    
                    $frec_dia_semana_sel = $this->input->post('frec_dia_semana_sel', TRUE);
                    
                    $frec_dia_semana_monto2 = $this->input->post('frec_dia_semana_monto2', TRUE);
                    $frec_dia_semana_monto3 = $this->input->post('frec_dia_semana_monto3', TRUE);
                    $frec_dia_eval_semana1_brm = $this->input->post('frec_dia_eval_semana1_brm', TRUE);
                    $frec_dia_eval_semana2_brm = $this->input->post('frec_dia_eval_semana2_brm', TRUE);
                    $frec_dia_eval_semana3_brm = $this->input->post('frec_dia_eval_semana3_brm', TRUE);
                    $frec_dia_eval_semana4_brm = $this->input->post('frec_dia_eval_semana4_brm', TRUE);
                    
                    switch ($frec_dia_semana_sel) {
                        case 1: $frec_dia_eval_semana1_brm = $frec_dia_semana_sel_brm; break;
                        case 2: $frec_dia_eval_semana2_brm = $frec_dia_semana_sel_brm; break;
                        case 3: $frec_dia_eval_semana3_brm = $frec_dia_semana_sel_brm; break;
                        case 4: $frec_dia_eval_semana4_brm = $frec_dia_semana_sel_brm; break;

                        default: $frec_dia_eval_semana4_brm = $frec_dia_semana_sel_brm; break;
                    }
                    
                    $frec_sem_semana1_monto = $this->input->post('frec_sem_semana1_monto', TRUE);
                    $frec_sem_semana2_monto = $this->input->post('frec_sem_semana2_monto', TRUE);
                    $frec_sem_semana3_monto = $this->input->post('frec_sem_semana3_monto', TRUE);
                    $frec_sem_semana4_monto = $this->input->post('frec_sem_semana4_monto', TRUE);
                    $frec_mes_sel = $this->input->post('frec_mes_sel', TRUE);
                    $frec_mes_mes1_monto = $this->input->post('frec_mes_mes1_monto', TRUE);
                    $frec_mes_mes2_monto = $this->input->post('frec_mes_mes2_monto', TRUE);
                    $frec_mes_mes3_monto = $this->input->post('frec_mes_mes3_monto', TRUE);
                    
                    // Validación de campos
                    
                    switch ($frec_seleccion) {
                        case "1":
                            
                            // Día
                            
                            if($frec_dia_lunes == "" || $frec_dia_martes == "" || $frec_dia_miercoles == "" || $frec_dia_jueves == "" || $frec_dia_viernes == "" || $frec_dia_sabado == "" || $frec_dia_domingo == "")
                            {
                                $error_texto .= $separador . 'Debe registrar todas las frecuencias diarias.';
                            }
                            
                            if($frec_dia_semana_sel == '') { $error_texto .= $separador . 'Debe seleccionar la semana de referencia.'; }
                            
                            if($frec_dia_semana_sel_brm == '') { $error_texto .= $separador . 'Debe seleccionar el criterio de evaluación de la semana.'; }
                            
                            if($frec_dia_semana_monto2 == "" || $frec_dia_semana_monto3 == "") { $error_texto .= $separador . 'Debe completar los montos Buena Regular Mala.'; }
                            
                            if($frec_dia_eval_semana1_brm == "" || $frec_dia_eval_semana2_brm == "" || $frec_dia_eval_semana3_brm == "" || $frec_dia_eval_semana4_brm == "")
                            {
                                $error_texto .= $separador . 'Debe registrar todas las variaciones de ventas semanales.';
                            }
                            
                            // -- Se valida que los montos BRM tengan coherencia
                            
                            // 1=Bueno      2=Regular       3=Malo
                            
                            $suma_dias = 
                                    $frec_dia_lunes +
                                    $frec_dia_martes +
                                    $frec_dia_miercoles +
                                    $frec_dia_jueves +
                                    $frec_dia_viernes +
                                    $frec_dia_sabado +
                                    $frec_dia_domingo;
                            
                            switch ($frec_dia_semana_sel_brm) {
                                case 1: if($suma_dias<$frec_dia_semana_monto2) { $error_texto .= $separador . 'El monto REGULAR no puede ser Mayor a BUENO.'; } break;
                                case 2: if($suma_dias>$frec_dia_semana_monto2 || $suma_dias<$frec_dia_semana_monto3) { $error_texto .= $separador . 'El monto REGULAR debe estar entre BUENO y MALO.'; } break;
                                case 3: if($suma_dias>$frec_dia_semana_monto3) { $error_texto .= $separador . 'El monto MALO no puede ser Mayor a REGULAR.'; } break;
                                default: $error_texto .= $separador . 'No seleccionó opción'; break;
                            }
                            
                            if($frec_dia_semana_monto2<$frec_dia_semana_monto3)
                            {
                                $error_texto .= $separador . 'Revise los importes de los criterios tengan coherencia.';
                            }
                            
                            
                            
                            switch ($frec_dia_semana_sel) {
                                case 1: 
                                    
                                    //if((($frec_dia_semana_sel_brm + $frec_dia_eval_semana2_brm + $frec_dia_eval_semana3_brm + $frec_dia_eval_semana4_brm)/4) == $frec_dia_semana_sel_brm)
                                    if($frec_dia_semana_sel_brm  == $frec_dia_eval_semana2_brm && $frec_dia_semana_sel_brm  == $frec_dia_eval_semana3_brm && $frec_dia_semana_sel_brm  == $frec_dia_eval_semana4_brm)
                                    {
                                        $error_texto .= $separador . 'Las semanas no pueden tener todas la misma consideración (Ejemplo: No pueden ser todas "Regular" o "Bueno" o "Malo")';
                                    }
                                    
                                    break;
                                    
                                case 2: 
                                    
                                    //if((($frec_dia_semana_sel_brm + $frec_dia_eval_semana1_brm + $frec_dia_eval_semana3_brm + $frec_dia_eval_semana4_brm)/4) == $frec_dia_semana_sel_brm)
                                    if($frec_dia_semana_sel_brm  == $frec_dia_eval_semana1_brm && $frec_dia_semana_sel_brm  == $frec_dia_eval_semana3_brm && $frec_dia_semana_sel_brm  == $frec_dia_eval_semana4_brm)
                                    {
                                        $error_texto .= $separador . 'Las semanas no pueden tener todas la misma consideración (Ejemplo: No pueden ser todas "Regular" o "Bueno" o "Malo")';
                                    }
                                    
                                    break;
                                    
                                case 3: 
                                    
                                    //if((($frec_dia_semana_sel_brm + $frec_dia_eval_semana1_brm + $frec_dia_eval_semana2_brm + $frec_dia_eval_semana4_brm)/4) == $frec_dia_semana_sel_brm)
                                    if($frec_dia_semana_sel_brm  == $frec_dia_eval_semana1_brm && $frec_dia_semana_sel_brm  == $frec_dia_eval_semana2_brm && $frec_dia_semana_sel_brm  == $frec_dia_eval_semana4_brm)
                                    {
                                        $error_texto .= $separador . 'Las semanas no pueden tener todas la misma consideración (Ejemplo: No pueden ser todas "Regular" o "Bueno" o "Malo")';
                                    }
                                    
                                    break;
                                    
                                case 4: 
                                    
                                    //if((($frec_dia_semana_sel_brm + $frec_dia_eval_semana1_brm + $frec_dia_eval_semana2_brm + $frec_dia_eval_semana3_brm)/4) == $frec_dia_semana_sel_brm)
                                    if($frec_dia_semana_sel_brm  == $frec_dia_eval_semana1_brm && $frec_dia_semana_sel_brm  == $frec_dia_eval_semana2_brm && $frec_dia_semana_sel_brm  == $frec_dia_eval_semana3_brm)
                                    {
                                        $error_texto .= $separador . 'Las semanas no pueden tener todas la misma consideración (Ejemplo: No pueden ser todas "Regular" o "Bueno" o "Malo")';
                                    }
                                    
                                    break;
                                    
                                default: $error_texto .= $separador . 'No seleccionó opción'; break;
                            }
                            
                            break;

                        case "2":
                            
                            // Semana
                            
                            if($frec_sem_semana1_monto == "" || $frec_sem_semana2_monto == "" || $frec_sem_semana3_monto == "" || $frec_sem_semana4_monto == "")
                            {
                                $error_texto .= $separador . 'Debe registrar todas las frecuencias semanales.';
                            }
                            
                            break;
                        
                        case "3":
                            
                            if($frec_mes_sel == '') { $error_texto .= $separador . 'Debe seleccionar el Mes de referencia'; }
                            
                            if($frec_mes_mes1_monto == "" || $frec_mes_mes2_monto == "" || $frec_mes_mes3_monto == "")
                            {
                                $error_texto .= $separador . 'Debe registrar todas las frecuencias mensuales.';
                            }
                            
                            break;
                            
                        default:
                            
                            $error_texto .= $separador . 'Debe seleccionar la Frecuencia de Ventas .';
                            
                            break;
                    }
                    
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    // Guardar en la DB
                    
                    $this->mfunciones_logica->update_frecuencia_venta(
                            $frec_seleccion,
                            $frec_dia_lunes,
                            $frec_dia_martes,
                            $frec_dia_miercoles,
                            $frec_dia_jueves,
                            $frec_dia_viernes,
                            $frec_dia_sabado,
                            $frec_dia_domingo,
                            $frec_dia_semana_sel,
                            $frec_dia_semana_sel_brm,
                            $frec_dia_semana_monto2,
                            $frec_dia_semana_monto3,
                            $frec_dia_eval_semana1_brm,
                            $frec_dia_eval_semana2_brm,
                            $frec_dia_eval_semana3_brm,
                            $frec_dia_eval_semana4_brm,
                            $frec_sem_semana1_monto,
                            $frec_sem_semana2_monto,
                            $frec_sem_semana3_monto,
                            $frec_sem_semana4_monto,
                            $frec_mes_sel,
                            $frec_mes_mes1_monto,
                            $frec_mes_mes2_monto,
                            $frec_mes_mes3_monto,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            );
                    
                    break;
                
                case "margen_utilidad":

                    // Captura de Datos
                    
                    $margen_utilidad_productos = $this->input->post('margen_utilidad_productos', TRUE);
                    
                    // Validación de campos
                    
                    if($margen_utilidad_productos == '') { $error_texto .= $separador . 'Debe seleccionar el margen de utilidad.'; }
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    // Guardar en la DB
                    
                    $this->mfunciones_logica->update_margen_utilidad(
                            $margen_utilidad_productos,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            );
                    
                    // Guardado Método de Registro de Inventario
                    
                    $metodo = $this->input->post('inventario_registro', TRUE);
                    $monto = $this->input->post('inventario_registro_total', TRUE);
                    
                    if($metodo == '')
                    {
                        js_error_div_javascript('Debe seleccionar el método de registro del Inventario, Detallado o con Respaldo.');
                        exit();
                    }
                    
                    // Se guarda en DB
                    $this->mfunciones_logica->update_monto_inventario(
                                    $metodo,
                                    $monto,
                                    $accion_usuario,
                                    $accion_fecha,
                                    $estructura_id
                                    );
                    
                    break;
                
                case "proveedores":

                    // Captura de Datos
                    
                    $porcentaje_participacion_proveedores = $this->input->post('porcentaje_participacion_proveedores', TRUE);
                    
                    // Validación de campos
                    
                    if($porcentaje_participacion_proveedores == '') { $error_texto .= $separador . 'Debe seleccionar el porcentaje de los Proveedores.'; }
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    // Guardar en la DB
                    
                    $this->mfunciones_logica->update_proveedores(
                            $porcentaje_participacion_proveedores,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            );
                    
                    break;
                
                case "estacionalidad":

                    // Captura de Datos
                    
                    $capacidad_criterio = $this->input->post('capacidad_criterio', TRUE);
                    $capacidad_monto_manual = $this->input->post('capacidad_monto_manual', TRUE);
                    $estacion_sel = $this->input->post('estacion_sel', TRUE);
                    $estacion_sel_mes = $this->input->post('estacion_sel_mes', TRUE);
                    $estacion_sel_arb = $this->input->post('estacion_sel_arb', TRUE);
                    $estacion_monto2 = $this->input->post('estacion_monto2', TRUE);
                    $estacion_monto3 = $this->input->post('estacion_monto3', TRUE);
                    $estacion_ene_arb = $this->input->post('estacion_ene_arb', TRUE);
                    $estacion_feb_arb = $this->input->post('estacion_feb_arb', TRUE);
                    $estacion_mar_arb = $this->input->post('estacion_mar_arb', TRUE);
                    $estacion_abr_arb = $this->input->post('estacion_abr_arb', TRUE);
                    $estacion_may_arb = $this->input->post('estacion_may_arb', TRUE);
                    $estacion_jun_arb = $this->input->post('estacion_jun_arb', TRUE);
                    $estacion_jul_arb = $this->input->post('estacion_jul_arb', TRUE);
                    $estacion_ago_arb = $this->input->post('estacion_ago_arb', TRUE);
                    $estacion_sep_arb = $this->input->post('estacion_sep_arb', TRUE);
                    $estacion_oct_arb = $this->input->post('estacion_oct_arb', TRUE);
                    $estacion_nov_arb = $this->input->post('estacion_nov_arb', TRUE);
                    $estacion_dic_arb = $this->input->post('estacion_dic_arb', TRUE);
                    
                    // Validación de campos
                    
                    if($capacidad_criterio == '') { $error_texto .= $separador . 'Debe seleccionar el criterio.'; }
                    if($estacion_sel == '') { $error_texto .= $separador . 'Debe seleccionar la estacionalidad.'; }
                    
                    if($capacidad_criterio == "4" && $estacion_sel != '')
                    {
                        if($capacidad_monto_manual == '') { $error_texto .= $separador . 'Debe seleccionar el monto de Cruce Personalizado.'; }
                    }
                    
                    if($estacion_sel == "1")
                    {
                        if($estacion_sel_mes == '' || $estacion_sel_arb == '') { $error_texto .= $separador . 'Debe seleccionar el mes y su estacionalidad.'; }
                        
                        if($estacion_sel_arb == "1" || $estacion_sel_arb == "3")
                        {
                            if($estacion_monto2 == '' || $estacion_monto3 == '' || $estacion_ene_arb == '' || $estacion_feb_arb == '' || $estacion_mar_arb == '' || $estacion_abr_arb == '' || $estacion_may_arb == '' || $estacion_jun_arb == '' || $estacion_jul_arb == '' || $estacion_ago_arb == '' || $estacion_sep_arb == '' || $estacion_oct_arb == '' || $estacion_nov_arb == '' || $estacion_dic_arb == '')
                            {
                                $error_texto .= $separador . 'Debe seleccionar los importes y la categorización de todos los meses.';
                            }
                            
                            $monto_criterio_seleccionado = $this->input->post('monto_criterio_seleccionado', TRUE);
                            
                            switch ($estacion_sel_arb) {
                                case "1":
                                    $texto_validacion_montos = 'El monto Bajo no puede ser mayor al monto Regular';

                                    if($monto_criterio_seleccionado < $estacion_monto3)
                                    {
                                        $error_texto .= $separador . 'El criterio seleccionado debe ser mayor al importe Regular';
                                    }
                                    
                                    break;
                                
                                case "3":
                                    $texto_validacion_montos = 'El monto Regular no puede ser mayor al monto Alto';

                                    if($monto_criterio_seleccionado > $estacion_monto2)
                                    {
                                        $error_texto .= $separador . 'El criterio seleccionado debe ser menor al importe Regular';
                                    }
                                    
                                    break;

                                default:
                                    $texto_validacion_montos = '';
                                    break;
                            }
                            
                            if($estacion_monto3 > $estacion_monto2)
                            {
                                $error_texto .= $separador . $texto_validacion_montos;
                            }
                        }
                    }
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    // Guardar en la DB
                    
                    $this->mfunciones_logica->update_estacionalidad(
                            $capacidad_criterio,
                            $capacidad_monto_manual,
                            $estacion_sel,
                            $estacion_sel_mes,
                            $estacion_sel_arb,
                            $estacion_monto2,
                            $estacion_monto3,
                            $estacion_ene_arb,
                            $estacion_feb_arb,
                            $estacion_mar_arb,
                            $estacion_abr_arb,
                            $estacion_may_arb,
                            $estacion_jun_arb,
                            $estacion_jul_arb,
                            $estacion_ago_arb,
                            $estacion_sep_arb,
                            $estacion_oct_arb,
                            $estacion_nov_arb,
                            $estacion_dic_arb,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            );
                    
                    break;
                
                case "gastos_operativos":

                    // Captura de Datos
                    
                    $operativos_alq_energia_monto = $this->input->post('operativos_alq_energia_monto', TRUE);
                    $operativos_alq_agua_monto = $this->input->post('operativos_alq_agua_monto', TRUE);
                    $operativos_alq_internet_monto = $this->input->post('operativos_alq_internet_monto', TRUE);
                    $operativos_alq_combustible_monto = $this->input->post('operativos_alq_combustible_monto', TRUE);
                    $operativos_alq_libre1_texto = $this->input->post('operativos_alq_libre1_texto', TRUE);
                    $operativos_alq_libre1_monto = $this->input->post('operativos_alq_libre1_monto', TRUE);
                    $operativos_alq_libre2_texto = $this->input->post('operativos_alq_libre2_texto', TRUE);
                    $operativos_alq_libre2_monto = $this->input->post('operativos_alq_libre2_monto', TRUE);
                    
                    $operativos_sal_libre1_texto = $this->input->post('operativos_sal_libre1_texto', TRUE);
                    $operativos_sal_libre1_monto = $this->input->post('operativos_sal_libre1_monto', TRUE);
                    $operativos_sal_libre2_texto = $this->input->post('operativos_sal_libre2_texto', TRUE);
                    $operativos_sal_libre2_monto = $this->input->post('operativos_sal_libre2_monto', TRUE);
                    $operativos_sal_libre3_texto = $this->input->post('operativos_sal_libre3_texto', TRUE);
                    $operativos_sal_libre3_monto = $this->input->post('operativos_sal_libre3_monto', TRUE);
                    $operativos_sal_libre4_texto = $this->input->post('operativos_sal_libre4_texto', TRUE);
                    $operativos_sal_libre4_monto = $this->input->post('operativos_sal_libre4_monto', TRUE);
                    
                    //$operativos_sal_aguinaldos_monto = $this->input->post('operativos_sal_aguinaldos_monto', TRUE);
                    $operativos_sal_aguinaldos_monto = ($operativos_sal_libre1_monto + $operativos_sal_libre2_monto + $operativos_sal_libre3_monto + $operativos_sal_libre4_monto)/12;
                    
                    $operativos_otro_transporte_monto = $this->input->post('operativos_otro_transporte_monto', TRUE);
                    $operativos_otro_licencias_monto = $this->input->post('operativos_otro_licencias_monto', TRUE);
                    $operativos_otro_alimentacion_monto = $this->input->post('operativos_otro_alimentacion_monto', TRUE);
                    $operativos_otro_mant_vehiculo_monto = $this->input->post('operativos_otro_mant_vehiculo_monto', TRUE);
                    $operativos_otro_mant_maquina_monto = $this->input->post('operativos_otro_mant_maquina_monto', TRUE);
                    $operativos_otro_imprevistos_monto = $this->input->post('operativos_otro_imprevistos_monto', TRUE);
                    $operativos_otro_otros_monto = $this->input->post('operativos_otro_otros_monto', TRUE);
                    $operativos_otro_libre1_texto = $this->input->post('operativos_otro_libre1_texto', TRUE);
                    $operativos_otro_libre1_monto = $this->input->post('operativos_otro_libre1_monto', TRUE);
                    $operativos_otro_libre2_texto = $this->input->post('operativos_otro_libre2_texto', TRUE);
                    $operativos_otro_libre2_monto = $this->input->post('operativos_otro_libre2_monto', TRUE);
                    $operativos_otro_libre3_texto = $this->input->post('operativos_otro_libre3_texto', TRUE);
                    $operativos_otro_libre3_monto = $this->input->post('operativos_otro_libre3_monto', TRUE);
                    $operativos_otro_libre4_texto = $this->input->post('operativos_otro_libre4_texto', TRUE);
                    $operativos_otro_libre4_monto = $this->input->post('operativos_otro_libre4_monto', TRUE);
                    $operativos_otro_libre5_texto = $this->input->post('operativos_otro_libre5_texto', TRUE);
                    $operativos_otro_libre5_monto = $this->input->post('operativos_otro_libre5_monto', TRUE);

                    
                    // Validación de campos
                    
                    if($operativos_alq_energia_monto == '' || $operativos_alq_agua_monto == '' || $operativos_alq_internet_monto == '' || $operativos_alq_combustible_monto == '' || $operativos_otro_transporte_monto == '' || $operativos_otro_licencias_monto == '' || $operativos_otro_alimentacion_monto == '' || $operativos_otro_mant_vehiculo_monto == '' || $operativos_otro_mant_maquina_monto == '' || $operativos_otro_imprevistos_monto == '' || $operativos_otro_otros_monto == '' || $operativos_alq_libre1_monto == '' || $operativos_alq_libre2_monto == '' || $operativos_sal_libre1_monto == '' || $operativos_sal_libre2_monto == '' || $operativos_sal_libre3_monto == '' || $operativos_sal_libre4_monto == '' || $operativos_otro_libre1_monto == '' || $operativos_otro_libre2_monto == '' || $operativos_otro_libre3_monto == '' || $operativos_otro_libre4_monto == '' || $operativos_otro_libre5_monto == '')
                    {
                        $error_texto .= $separador . 'Debe registrar todos los importes o en su defecto colocar "0".';
                    }
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    // Guardar en la DB
                    
                    $this->mfunciones_logica->update_gastos_operativos(
                            $operativos_alq_energia_monto,
                            $operativos_alq_agua_monto,
                            $operativos_alq_internet_monto,
                            $operativos_alq_combustible_monto,
                            $operativos_alq_libre1_texto,
                            $operativos_alq_libre1_monto,
                            $operativos_alq_libre2_texto,
                            $operativos_alq_libre2_monto,
                            $operativos_sal_aguinaldos_monto,
                            $operativos_sal_libre1_texto,
                            $operativos_sal_libre1_monto,
                            $operativos_sal_libre2_texto,
                            $operativos_sal_libre2_monto,
                            $operativos_sal_libre3_texto,
                            $operativos_sal_libre3_monto,
                            $operativos_sal_libre4_texto,
                            $operativos_sal_libre4_monto,
                            $operativos_otro_transporte_monto,
                            $operativos_otro_licencias_monto,
                            $operativos_otro_alimentacion_monto,
                            $operativos_otro_mant_vehiculo_monto,
                            $operativos_otro_mant_maquina_monto,
                            $operativos_otro_imprevistos_monto,
                            $operativos_otro_otros_monto,
                            $operativos_otro_libre1_texto,
                            $operativos_otro_libre1_monto,
                            $operativos_otro_libre2_texto,
                            $operativos_otro_libre2_monto,
                            $operativos_otro_libre3_texto,
                            $operativos_otro_libre3_monto,
                            $operativos_otro_libre4_texto,
                            $operativos_otro_libre4_monto,
                            $operativos_otro_libre5_texto,
                            $operativos_otro_libre5_monto,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            );
                    
                    break;
                
                case "gastos_familiares":

                    // Captura de Datos
                    
                    $familiar_dependientes_ingreso = $this->input->post('familiar_dependientes_ingreso', TRUE);
                    $familiar_edad_hijos = $this->input->post('familiar_edad_hijos', TRUE);
                    $familiar_alimentacion_monto = $this->input->post('familiar_alimentacion_monto', TRUE);
                    $familiar_energia_monto = $this->input->post('familiar_energia_monto', TRUE);
                    $familiar_agua_monto = $this->input->post('familiar_agua_monto', TRUE);
                    $familiar_gas_monto = $this->input->post('familiar_gas_monto', TRUE);
                    $familiar_telefono_monto = $this->input->post('familiar_telefono_monto', TRUE);
                    $familiar_celular_monto = $this->input->post('familiar_celular_monto', TRUE);
                    $familiar_internet_monto = $this->input->post('familiar_internet_monto', TRUE);
                    $familiar_tv_monto = $this->input->post('familiar_tv_monto', TRUE);
                    $familiar_impuestos_monto = $this->input->post('familiar_impuestos_monto', TRUE);
                    $familiar_alquileres_monto = $this->input->post('familiar_alquileres_monto', TRUE);
                    $familiar_educacion_monto = $this->input->post('familiar_educacion_monto', TRUE);
                    $familiar_transporte_monto = $this->input->post('familiar_transporte_monto', TRUE);
                    $familiar_salud_monto = $this->input->post('familiar_salud_monto', TRUE);
                    $familiar_empleada_monto = $this->input->post('familiar_empleada_monto', TRUE);
                    $familiar_diversion_monto = $this->input->post('familiar_diversion_monto', TRUE);
                    $familiar_vestimenta_monto = $this->input->post('familiar_vestimenta_monto', TRUE);
                    $familiar_otros_monto = $this->input->post('familiar_otros_monto', TRUE);
                    $familiar_libre1_texto = $this->input->post('familiar_libre1_texto', TRUE);
                    $familiar_libre1_monto = $this->input->post('familiar_libre1_monto', TRUE);
                    $familiar_libre2_texto = $this->input->post('familiar_libre2_texto', TRUE);
                    $familiar_libre2_monto = $this->input->post('familiar_libre2_monto', TRUE);
                    $familiar_libre3_texto = $this->input->post('familiar_libre3_texto', TRUE);
                    $familiar_libre3_monto = $this->input->post('familiar_libre3_monto', TRUE);
                    $familiar_libre4_texto = $this->input->post('familiar_libre4_texto', TRUE);
                    $familiar_libre4_monto = $this->input->post('familiar_libre4_monto', TRUE);
                    $familiar_libre5_texto = $this->input->post('familiar_libre5_texto', TRUE);
                    $familiar_libre5_monto = $this->input->post('familiar_libre5_monto', TRUE);
                    
                    // Validación de campos
                    
                    if($familiar_dependientes_ingreso == '' || $familiar_edad_hijos == '')
                    {
                        //$error_texto .= $separador . 'Debe registrar el No. Dependientes del ingreso familiar y el Detalle Edad de hijos.';
                    }
                    
                    if($familiar_alimentacion_monto == '' || $familiar_energia_monto == '' || $familiar_agua_monto == '' || $familiar_gas_monto == '' || $familiar_telefono_monto == '' || $familiar_celular_monto == '' || $familiar_internet_monto == '' || $familiar_tv_monto == '' || $familiar_impuestos_monto == '' || $familiar_alquileres_monto == '' || $familiar_educacion_monto == '' || $familiar_transporte_monto == '' || $familiar_salud_monto == '' || $familiar_empleada_monto == '' || $familiar_diversion_monto == '' || $familiar_vestimenta_monto == '' || $familiar_otros_monto == '' || $familiar_libre1_monto == '' || $familiar_libre2_monto == '' || $familiar_libre3_monto == '' || $familiar_libre4_monto == '' || $familiar_libre5_monto == '')
                    {
                        $error_texto .= $separador . 'Debe registrar todos los importes o en su defecto colocar "0".';
                    }
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    // Guardar en la DB
                    
                    $this->mfunciones_logica->update_gastos_familiares(
                            $familiar_dependientes_ingreso,
                            $familiar_edad_hijos,
                            $familiar_alimentacion_monto,
                            $familiar_energia_monto,
                            $familiar_agua_monto,
                            $familiar_gas_monto,
                            $familiar_telefono_monto,
                            $familiar_celular_monto,
                            $familiar_internet_monto,
                            $familiar_tv_monto,
                            $familiar_impuestos_monto,
                            $familiar_alquileres_monto,
                            $familiar_educacion_monto,
                            $familiar_transporte_monto,
                            $familiar_salud_monto,
                            $familiar_empleada_monto,
                            $familiar_diversion_monto,
                            $familiar_vestimenta_monto,
                            $familiar_otros_monto,
                            $familiar_libre1_texto,
                            $familiar_libre1_monto,
                            $familiar_libre2_texto,
                            $familiar_libre2_monto,
                            $familiar_libre3_texto,
                            $familiar_libre3_monto,
                            $familiar_libre4_texto,
                            $familiar_libre4_monto,
                            $familiar_libre5_texto,
                            $familiar_libre5_monto,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            );
                    
                    break;
                
                case "otros_ingresos":

                    // Captura de Datos
                    
                    $extra_cuota_prestamo_solicitado = $this->input->post('extra_cuota_prestamo_solicitado', TRUE);
                    $extra_amortizacion_otras_deudas = 20;
                    $extra_cuota_maxima_credito = $this->input->post('extra_cuota_maxima_credito', TRUE);
                    $extra_amortizacion_credito = $this->input->post('extra_amortizacion_credito', TRUE);
                    $extra_endeudamiento_credito = $this->input->post('extra_endeudamiento_credito', TRUE);
                    $extra_personal_ocupado = $this->input->post('extra_personal_ocupado', TRUE);
                    $extra_efectivo_caja = $this->input->post('extra_efectivo_caja', TRUE);
                    $extra_ahorro_dpf = $this->input->post('extra_ahorro_dpf', TRUE);
                    $extra_cuentas_cobrar = $this->input->post('extra_cuentas_cobrar', TRUE);
                    //$extra_inventario = $this->input->post('extra_inventario', TRUE);
                    $extra_inventario = 20;
                    $extra_otros_activos_corrientes = $this->input->post('extra_otros_activos_corrientes', TRUE);
                    $extra_activo_fijo = $this->input->post('extra_activo_fijo', TRUE);
                    $extra_otros_activos_nocorrientes = $this->input->post('extra_otros_activos_nocorrientes', TRUE);
                    $extra_activos_actividades_secundarias = 20;
                    $extra_inmuebles_terrenos = $this->input->post('extra_inmuebles_terrenos', TRUE);
                    $extra_bienes_hogar = $this->input->post('extra_bienes_hogar', TRUE);
                    $extra_otros_activos_familiares = $this->input->post('extra_otros_activos_familiares', TRUE);
                    $extra_cuentas_pagar_proveedores = $this->input->post('extra_cuentas_pagar_proveedores', TRUE);
                    $extra_prestamos_financieras_corto = $this->input->post('extra_prestamos_financieras_corto', TRUE);
                    $extra_cuentas_pagar_corto = $this->input->post('extra_cuentas_pagar_corto', TRUE);
                    $extra_prestamos_financieras_largo = $this->input->post('extra_prestamos_financieras_largo', TRUE);
                    $extra_cuentas_pagar_largo = $this->input->post('extra_cuentas_pagar_largo', TRUE);
                    $extra_pasivo_actividades_secundarias = 20;
                    $extra_pasivo_familiar = $this->input->post('extra_pasivo_familiar', TRUE);
                    
                    // Validación de campos
                    
                    if($extra_efectivo_caja == '' || $extra_ahorro_dpf == '' || $extra_cuentas_cobrar == '' || $extra_inventario == '' || $extra_otros_activos_corrientes == '' || $extra_activo_fijo == '' || $extra_otros_activos_nocorrientes == '' || $extra_activos_actividades_secundarias == '' || $extra_inmuebles_terrenos == '' || $extra_bienes_hogar == '' || $extra_otros_activos_familiares == '' || $extra_cuentas_pagar_proveedores == '' || $extra_prestamos_financieras_corto == '' || $extra_cuentas_pagar_corto == '' || $extra_prestamos_financieras_largo == '' || $extra_cuentas_pagar_largo == '' || $extra_pasivo_actividades_secundarias == '' || $extra_pasivo_familiar == '')
                    {
                        $error_texto .= $separador . 'Debe registrar todos los importes de Información Adicional, Ativos y Pasivos; o en su defecto colocar "0".';
                    }
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    // Guardar en la DB
                    
                    $this->mfunciones_logica->update_otros_ingresos(
                            $extra_cuota_prestamo_solicitado,
                            $extra_amortizacion_otras_deudas,
                            $extra_cuota_maxima_credito,
                            $extra_amortizacion_credito,
                            $extra_endeudamiento_credito,
                            $extra_personal_ocupado,
                            $extra_efectivo_caja,
                            $extra_ahorro_dpf,
                            $extra_cuentas_cobrar,
                            $extra_inventario,
                            $extra_otros_activos_corrientes,
                            $extra_activo_fijo,
                            $extra_otros_activos_nocorrientes,
                            $extra_activos_actividades_secundarias,
                            $extra_inmuebles_terrenos,
                            $extra_bienes_hogar,
                            $extra_otros_activos_familiares,
                            $extra_cuentas_pagar_proveedores,
                            $extra_prestamos_financieras_corto,
                            $extra_cuentas_pagar_corto,
                            $extra_prestamos_financieras_largo,
                            $extra_cuentas_pagar_largo,
                            $extra_pasivo_actividades_secundarias,
                            $extra_pasivo_familiar,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            );
                    
                    break;
                    
                case "fuente_generadora":

                    // Captura de Datos
                    
                    $transporte_tipo_prestatario = $this->input->post('transporte_tipo_prestatario', TRUE);
                    $transporte_tipo_transporte = $this->input->post('transporte_tipo_transporte', TRUE);
                    $transporte_preg_sindicato = $this->input->post('transporte_preg_sindicato', TRUE);
                    $transporte_preg_sindicato_lineas = $this->input->post('transporte_preg_sindicato_lineas', TRUE);
                    $transporte_preg_sindicato_grupos = $this->input->post('transporte_preg_sindicato_grupos', TRUE);
                    $transporte_preg_unidades_grupo = $this->input->post('transporte_preg_unidades_grupo', TRUE);
                    $transporte_preg_grupo_rota = $this->input->post('transporte_preg_grupo_rota', TRUE);
                    $transporte_preg_lineas_buenas = $this->input->post('transporte_preg_lineas_buenas', TRUE);
                    $transporte_preg_lineas_regulares = $this->input->post('transporte_preg_lineas_regulares', TRUE);
                    $transporte_preg_lineas_malas = $this->input->post('transporte_preg_lineas_malas', TRUE);
                    $transporte_preg_trabaja_semana = $this->input->post('transporte_preg_trabaja_semana', TRUE);
                    $transporte_preg_trabaja_dia = $this->input->post('transporte_preg_trabaja_dia', TRUE);
                    $transporte_preg_jornada_inicia = $this->input->post('transporte_preg_jornada_inicia', TRUE);
                    $transporte_preg_jornada_concluye = $this->input->post('transporte_preg_jornada_concluye', TRUE);
                    $transporte_preg_tiempo_no_trabaja = $this->input->post('transporte_preg_tiempo_no_trabaja', TRUE);
                    $transporte_preg_tiempo_parada = $this->input->post('transporte_preg_tiempo_parada', TRUE);
                    $transporte_preg_tiempo_vuelta = $this->input->post('transporte_preg_tiempo_vuelta', TRUE);
                    $transporte_preg_vehiculo_ano = $this->input->post('transporte_preg_vehiculo_ano', TRUE);
                    $transporte_preg_vehiculo_combustible = $this->input->post('transporte_preg_vehiculo_combustible', TRUE);
                    
                    // Validación de campos
                    
                    if($transporte_tipo_prestatario == '')
                    {
                        $error_texto .= $separador . $this->lang->line('transporte_tipo_prestatario');
                    }
                    
                    if($transporte_tipo_transporte == '')
                    {
                        $error_texto .= $separador . $this->lang->line('transporte_tipo_transporte');
                    }
                    
                    if($transporte_preg_trabaja_semana == '')
                    {
                        $error_texto .= $separador . $this->lang->line('transporte_preg_trabaja_semana');
                    }
                    
                    if($transporte_preg_vehiculo_combustible == '')
                    {
                        $error_texto .= $separador . $this->lang->line('transporte_preg_vehiculo_combustible');
                    }
                    
                    if($transporte_tipo_prestatario != 2 && $this->mfunciones_generales->time_to_decimal($transporte_preg_tiempo_vuelta) == 0)
                    {
                        $error_texto .= $separador . 'Duración de la Vuelta/Carrera';
                    }
                    
                    if($transporte_tipo_transporte != 5 && $transporte_preg_sindicato == '')
                    {
                        $error_texto .= $separador . $this->lang->line('transporte_preg_sindicato');
                    }
                    
                    if($this->mfunciones_generales->time_to_decimal($transporte_preg_trabaja_dia) == 0)
                    {
                        $error_texto .= $separador . $this->lang->line('transporte_preg_trabaja_dia');
                    }
                    
                    if(strtotime($transporte_preg_jornada_inicia) > strtotime($transporte_preg_jornada_concluye))
                    {
                        //$error_texto .= $separador . 'El horario de la jornada laboral es incorrecto.';
                    }
                    
                        //$this->mfunciones_generales->time_to_decimal($transporte_preg_trabaja_dia);
                        //echo $transporte_preg_jornada_inicia; $this->mfunciones_generales->time_to_decimal($transporte_preg_jornada_inicia);
                        //$this->mfunciones_generales->time_to_decimal($transporte_preg_jornada_concluye);
                    
                    //if($this->mfunciones_generales->time_to_decimal($transporte_preg_trabaja_dia) != $this->mfunciones_generales->time_to_decimal(date('H:i:', strtotime($transporte_preg_jornada_concluye))) - $this->mfunciones_generales->time_to_decimal(date('H:i:s', strtotime($transporte_preg_jornada_inicia))))
                    //{
                    //    $error_texto .= $separador . 'Las horas trabajadas no coinciden con la cantidad de horas que indicó que se trabajan al día.';
                    //}
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    if($transporte_tipo_prestatario == 2)
                    {
                        $transporte_preg_tiempo_no_trabaja = '00:00:00';
                    }
                    
                    if($transporte_tipo_transporte >= 4)
                    {
                        $transporte_preg_tiempo_parada = '00:00:00';
                    }
                    
                    // Guardar en la DB
                    
                    $this->mfunciones_logica->update_fuente_generadora(
                            $transporte_tipo_prestatario,
                            $transporte_tipo_transporte,
                            $transporte_preg_sindicato,
                            $transporte_preg_sindicato_lineas,
                            $transporte_preg_sindicato_grupos,
                            $transporte_preg_unidades_grupo,
                            $transporte_preg_grupo_rota,
                            $transporte_preg_lineas_buenas,
                            $transporte_preg_lineas_regulares,
                            $transporte_preg_lineas_malas,
                            $transporte_preg_trabaja_semana,
                            $transporte_preg_trabaja_dia,
                            $transporte_preg_jornada_inicia,
                            $transporte_preg_jornada_concluye,
                            $transporte_preg_tiempo_no_trabaja,
                            $transporte_preg_tiempo_parada,
                            $transporte_preg_tiempo_vuelta,
                            $transporte_preg_vehiculo_ano,
                            $transporte_preg_vehiculo_combustible,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            );
                    
                    break;
                
                case "volumen_ingresos":

                    // Captura de Datos
                    
                    $transporte_cliente_frecuencia = $this->input->post('transporte_cliente_frecuencia', TRUE);
                    $transporte_cliente_dia_lunes = $this->input->post('transporte_cliente_dia_lunes', TRUE);
                    $transporte_cliente_dia_martes = $this->input->post('transporte_cliente_dia_martes', TRUE);
                    $transporte_cliente_dia_miercoles = $this->input->post('transporte_cliente_dia_miercoles', TRUE);
                    $transporte_cliente_dia_jueves = $this->input->post('transporte_cliente_dia_jueves', TRUE);
                    $transporte_cliente_dia_viernes = $this->input->post('transporte_cliente_dia_viernes', TRUE);
                    $transporte_cliente_dia_sabado = $this->input->post('transporte_cliente_dia_sabado', TRUE);
                    $transporte_cliente_dia_domingo = $this->input->post('transporte_cliente_dia_domingo', TRUE);
                    $transporte_cliente_linea1_texto = $this->input->post('transporte_cliente_linea1_texto', TRUE);
                    $transporte_cliente_linea2_texto = $this->input->post('transporte_cliente_linea2_texto', TRUE);
                    $transporte_cliente_linea3_texto = $this->input->post('transporte_cliente_linea3_texto', TRUE);
                    $transporte_cliente_linea4_texto = $this->input->post('transporte_cliente_linea4_texto', TRUE);
                    $transporte_cliente_linea5_texto = $this->input->post('transporte_cliente_linea5_texto', TRUE);
                    $transporte_cliente_linea6_texto = $this->input->post('transporte_cliente_linea6_texto', TRUE);
                    $transporte_cliente_linea7_texto = $this->input->post('transporte_cliente_linea7_texto', TRUE);
                    $transporte_cliente_linea1_min = $this->input->post('transporte_cliente_linea1_min', TRUE);
                    $transporte_cliente_linea2_min = $this->input->post('transporte_cliente_linea2_min', TRUE);
                    $transporte_cliente_linea3_min = $this->input->post('transporte_cliente_linea3_min', TRUE);
                    $transporte_cliente_linea4_min = $this->input->post('transporte_cliente_linea4_min', TRUE);
                    $transporte_cliente_linea5_min = $this->input->post('transporte_cliente_linea5_min', TRUE);
                    $transporte_cliente_linea6_min = $this->input->post('transporte_cliente_linea6_min', TRUE);
                    $transporte_cliente_linea7_min = $this->input->post('transporte_cliente_linea7_min', TRUE);
                    $transporte_cliente_linea1_max = $this->input->post('transporte_cliente_linea1_max', TRUE);
                    $transporte_cliente_linea2_max = $this->input->post('transporte_cliente_linea2_max', TRUE);
                    $transporte_cliente_linea3_max = $this->input->post('transporte_cliente_linea3_max', TRUE);
                    $transporte_cliente_linea4_max = $this->input->post('transporte_cliente_linea4_max', TRUE);
                    $transporte_cliente_linea5_max = $this->input->post('transporte_cliente_linea5_max', TRUE);
                    $transporte_cliente_linea6_max = $this->input->post('transporte_cliente_linea6_max', TRUE);
                    $transporte_cliente_linea7_max = $this->input->post('transporte_cliente_linea7_max', TRUE);
                    $transporte_cliente_vueta_buena_monto = $this->input->post('transporte_cliente_vueta_buena_monto', TRUE);
                    $transporte_cliente_vueta_buena_numero = $this->input->post('transporte_cliente_vueta_buena_numero', TRUE);
                    $transporte_cliente_vueta_regular_monto = $this->input->post('transporte_cliente_vueta_regular_monto', TRUE);
                    $transporte_cliente_vueta_regular_numero = $this->input->post('transporte_cliente_vueta_regular_numero', TRUE);
                    $transporte_cliente_vueta_mala_monto = $this->input->post('transporte_cliente_vueta_mala_monto', TRUE);
                    $transporte_cliente_vueta_mala_numero = $this->input->post('transporte_cliente_vueta_mala_numero', TRUE);
                    $transporte_capacidad_sin_rotacion = $this->input->post('transporte_capacidad_sin_rotacion', TRUE);
                    $transporte_capacidad_con_rotacion = $this->input->post('transporte_capacidad_con_rotacion', TRUE);
                    $transporte_capacidad_tramo_largo_pasajero = $this->input->post('transporte_capacidad_tramo_largo_pasajero', TRUE);
                    $transporte_capacidad_tramo_corto_pasajero = $this->input->post('transporte_capacidad_tramo_corto_pasajero', TRUE);
                    $transporte_capacidad_tramo_largo_precio = $this->input->post('transporte_capacidad_tramo_largo_precio', TRUE);
                    $transporte_capacidad_tramo_corto_precio = $this->input->post('transporte_capacidad_tramo_corto_precio', TRUE);
                    $transporte_vuelta_buena_ocupacion = $this->input->post('transporte_vuelta_buena_ocupacion', TRUE);
                    $transporte_vuelta_buena_veces = $this->input->post('transporte_vuelta_buena_veces', TRUE);
                    $transporte_vuelta_regular_ocupacion = $this->input->post('transporte_vuelta_regular_ocupacion', TRUE);
                    $transporte_vuelta_regular_veces = $this->input->post('transporte_vuelta_regular_veces', TRUE);
                    $transporte_vuelta_mala_ocupacion = $this->input->post('transporte_vuelta_mala_ocupacion', TRUE);
                    $transporte_vuelta_mala_veces = $this->input->post('transporte_vuelta_mala_veces', TRUE);
                    
                    $total_ingreso_bueno = $this->input->post('total_ingreso_bueno', TRUE);
                    $total_ingreso_regular = $this->input->post('total_ingreso_regular', TRUE);
                    $total_ingreso_malo = $this->input->post('total_ingreso_malo', TRUE);
                    
                    $transporte_tipo_prestatario = $this->input->post('transporte_tipo_prestatario', TRUE);
                    $transporte_tipo_transporte = $this->input->post('transporte_tipo_transporte', TRUE);
                    
                    // Validación de campos
                    
                    if($transporte_cliente_frecuencia == '')
                    {
                        $error_texto .= $separador . 'Seleccione la frecuencia de los días considerados';
                    }
                    
                    if($transporte_cliente_dia_lunes == '' || $transporte_cliente_dia_martes == '' || $transporte_cliente_dia_miercoles == '' || $transporte_cliente_dia_jueves == '' || $transporte_cliente_dia_viernes == '' || $transporte_cliente_dia_sabado == '' || $transporte_cliente_dia_domingo == '')
                    {
                        $error_texto .= $separador . 'Debe registrar todos los días o en su defecto colocar 0';
                    }
                    
                    if($transporte_tipo_transporte < 4 && $transporte_tipo_prestatario != 2)
                    {
                        if( $transporte_cliente_linea1_min>$transporte_cliente_linea1_max ||
                            $transporte_cliente_linea2_min>$transporte_cliente_linea2_max ||
                            $transporte_cliente_linea3_min>$transporte_cliente_linea3_max ||
                            $transporte_cliente_linea4_min>$transporte_cliente_linea4_max ||
                            $transporte_cliente_linea5_min>$transporte_cliente_linea5_max ||
                            $transporte_cliente_linea6_min>$transporte_cliente_linea6_max ||
                            $transporte_cliente_linea7_min>$transporte_cliente_linea7_max)
                        {
                            $error_texto .= $separador . 'Los Importes de las líneas deben tener coherencia entre los mínimos y máximos.';
                        }
                        
                        if($transporte_cliente_vueta_buena_monto < $transporte_cliente_vueta_regular_monto || $transporte_cliente_vueta_regular_monto < $transporte_cliente_vueta_mala_monto)
                        {
                            $error_texto .= $separador . 'Los Importes de las Vueltas/Carreras deben tener coherencia.';
                        }
                        
                        if($transporte_vuelta_buena_ocupacion>100 || $transporte_vuelta_regular_ocupacion>100 || $transporte_vuelta_mala_ocupacion>100)
                        {
                            $error_texto .= $separador . 'Cada % Ocupación no debe sobrepasar el 100%.';
                        }
                        
                        if($transporte_vuelta_buena_ocupacion<$transporte_vuelta_regular_ocupacion || $transporte_vuelta_regular_ocupacion<$transporte_vuelta_mala_ocupacion)
                        {
                            $error_texto .= $separador . 'Los % Ocupación deben tener coherencia.';
                        }
                    }
                    
                    if($error_texto != '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    if($transporte_tipo_transporte >= 4 || $transporte_tipo_prestatario == 2)
                    {
                        $transporte_capacidad_sin_rotacion = 0;
                        $transporte_capacidad_con_rotacion = 0;
                        $transporte_capacidad_tramo_largo_pasajero = 0;
                        $transporte_capacidad_tramo_corto_pasajero = 0;
                        $transporte_capacidad_tramo_largo_precio = 0;
                        $transporte_capacidad_tramo_corto_precio = 0;
                        
                        $transporte_cliente_linea1_texto = '';
                        $transporte_cliente_linea2_texto = '';
                        $transporte_cliente_linea3_texto = '';
                        $transporte_cliente_linea4_texto = '';
                        $transporte_cliente_linea5_texto = '';
                        $transporte_cliente_linea6_texto = '';
                        $transporte_cliente_linea7_texto = '';
                        $transporte_cliente_linea1_min = 0;
                        $transporte_cliente_linea2_min = 0;
                        $transporte_cliente_linea3_min = 0;
                        $transporte_cliente_linea4_min = 0;
                        $transporte_cliente_linea5_min = 0;
                        $transporte_cliente_linea6_min = 0;
                        $transporte_cliente_linea7_min = 0;
                        $transporte_cliente_linea1_max = 0;
                        $transporte_cliente_linea2_max = 0;
                        $transporte_cliente_linea3_max = 0;
                        $transporte_cliente_linea4_max = 0;
                        $transporte_cliente_linea5_max = 0;
                        $transporte_cliente_linea6_max = 0;
                        $transporte_cliente_linea7_max = 0;
                    }
                    
                    if($transporte_tipo_prestatario == 2)
                    {
                        $transporte_cliente_vueta_buena_monto = 0;
                        $transporte_cliente_vueta_buena_numero = 0;
                        $transporte_cliente_vueta_regular_monto = 0;
                        $transporte_cliente_vueta_regular_numero = 0;
                        $transporte_cliente_vueta_mala_monto = 0;
                        $transporte_cliente_vueta_mala_numero = 0;
                    }
                    
                    // Guardar en la DB
                    
                    $this->mfunciones_logica->update_volumen_ingresos(
                            $transporte_cliente_frecuencia, 
                            $transporte_cliente_dia_lunes, 
                            $transporte_cliente_dia_martes, 
                            $transporte_cliente_dia_miercoles, 
                            $transporte_cliente_dia_jueves, 
                            $transporte_cliente_dia_viernes, 
                            $transporte_cliente_dia_sabado, 
                            $transporte_cliente_dia_domingo, 
                            $transporte_cliente_linea1_texto, 
                            $transporte_cliente_linea2_texto, 
                            $transporte_cliente_linea3_texto, 
                            $transporte_cliente_linea4_texto, 
                            $transporte_cliente_linea5_texto, 
                            $transporte_cliente_linea6_texto, 
                            $transporte_cliente_linea7_texto, 
                            $transporte_cliente_linea1_min, 
                            $transporte_cliente_linea2_min, 
                            $transporte_cliente_linea3_min, 
                            $transporte_cliente_linea4_min, 
                            $transporte_cliente_linea5_min, 
                            $transporte_cliente_linea6_min, 
                            $transporte_cliente_linea7_min, 
                            $transporte_cliente_linea1_max, 
                            $transporte_cliente_linea2_max, 
                            $transporte_cliente_linea3_max, 
                            $transporte_cliente_linea4_max, 
                            $transporte_cliente_linea5_max, 
                            $transporte_cliente_linea6_max, 
                            $transporte_cliente_linea7_max, 
                            $transporte_cliente_vueta_buena_monto, 
                            $transporte_cliente_vueta_buena_numero, 
                            $transporte_cliente_vueta_regular_monto, 
                            $transporte_cliente_vueta_regular_numero, 
                            $transporte_cliente_vueta_mala_monto, 
                            $transporte_cliente_vueta_mala_numero, 
                            $transporte_capacidad_sin_rotacion, 
                            $transporte_capacidad_con_rotacion, 
                            $transporte_capacidad_tramo_largo_pasajero, 
                            $transporte_capacidad_tramo_corto_pasajero, 
                            $transporte_capacidad_tramo_largo_precio, 
                            $transporte_capacidad_tramo_corto_precio, 
                            $transporte_vuelta_buena_ocupacion, 
                            $transporte_vuelta_buena_veces, 
                            $transporte_vuelta_regular_ocupacion, 
                            $transporte_vuelta_regular_veces, 
                            $transporte_vuelta_mala_ocupacion, 
                            $transporte_vuelta_mala_veces, 
                            $total_ingreso_bueno, 
                            $total_ingreso_regular, 
                            $total_ingreso_malo, 
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            );
                    
                    break;
                
                case "transporte_gastos_operativos":

                    // Captura de Datos
                    
                    $operativos_cambio_aceite_motor_frecuencia = $this->input->post('operativos_cambio_aceite_motor_frecuencia', TRUE);
                    $operativos_cambio_aceite_motor_cantidad = $this->input->post('operativos_cambio_aceite_motor_cantidad', TRUE);
                    $operativos_cambio_aceite_motor_monto = $this->input->post('operativos_cambio_aceite_motor_monto', TRUE);
                    $operativos_cambio_aceite_caja_frecuencia = $this->input->post('operativos_cambio_aceite_caja_frecuencia', TRUE);
                    $operativos_cambio_aceite_caja_cantidad = $this->input->post('operativos_cambio_aceite_caja_cantidad', TRUE);
                    $operativos_cambio_aceite_caja_monto = $this->input->post('operativos_cambio_aceite_caja_monto', TRUE);
                    $operativos_cambio_llanta_delanteras_frecuencia = $this->input->post('operativos_cambio_llanta_delanteras_frecuencia', TRUE);
                    $operativos_cambio_llanta_delanteras_cantidad = $this->input->post('operativos_cambio_llanta_delanteras_cantidad', TRUE);
                    $operativos_cambio_llanta_delanteras_monto = $this->input->post('operativos_cambio_llanta_delanteras_monto', TRUE);
                    $operativos_cambio_llanta_traseras_frecuencia = $this->input->post('operativos_cambio_llanta_traseras_frecuencia', TRUE);
                    $operativos_cambio_llanta_traseras_cantidad = $this->input->post('operativos_cambio_llanta_traseras_cantidad', TRUE);
                    $operativos_cambio_llanta_traseras_monto = $this->input->post('operativos_cambio_llanta_traseras_monto', TRUE);
                    $operativos_cambio_bateria_frecuencia = $this->input->post('operativos_cambio_bateria_frecuencia', TRUE);
                    $operativos_cambio_bateria_cantidad = $this->input->post('operativos_cambio_bateria_cantidad', TRUE);
                    $operativos_cambio_bateria_monto = $this->input->post('operativos_cambio_bateria_monto', TRUE);
                    $operativos_cambio_balatas_frecuencia = $this->input->post('operativos_cambio_balatas_frecuencia', TRUE);
                    $operativos_cambio_balatas_cantidad = $this->input->post('operativos_cambio_balatas_cantidad', TRUE);
                    $operativos_cambio_balatas_monto = $this->input->post('operativos_cambio_balatas_monto', TRUE);
                    $operativos_revision_electrico_frecuencia = $this->input->post('operativos_revision_electrico_frecuencia', TRUE);
                    $operativos_revision_electrico_cantidad = $this->input->post('operativos_revision_electrico_cantidad', TRUE);
                    $operativos_revision_electrico_monto = $this->input->post('operativos_revision_electrico_monto', TRUE);
                    $operativos_remachado_embrague_frecuencia = $this->input->post('operativos_remachado_embrague_frecuencia', TRUE);
                    $operativos_remachado_embrague_cantidad = $this->input->post('operativos_remachado_embrague_cantidad', TRUE);
                    $operativos_remachado_embrague_monto = $this->input->post('operativos_remachado_embrague_monto', TRUE);
                    $operativos_rectificacion_motor_frecuencia = $this->input->post('operativos_rectificacion_motor_frecuencia', TRUE);
                    $operativos_rectificacion_motor_cantidad = $this->input->post('operativos_rectificacion_motor_cantidad', TRUE);
                    $operativos_rectificacion_motor_monto = $this->input->post('operativos_rectificacion_motor_monto', TRUE);
                    $operativos_cambio_rodamiento_frecuencia = $this->input->post('operativos_cambio_rodamiento_frecuencia', TRUE);
                    $operativos_cambio_rodamiento_cantidad = $this->input->post('operativos_cambio_rodamiento_cantidad', TRUE);
                    $operativos_cambio_rodamiento_monto = $this->input->post('operativos_cambio_rodamiento_monto', TRUE);
                    $operativos_reparaciones_menores_frecuencia = $this->input->post('operativos_reparaciones_menores_frecuencia', TRUE);
                    $operativos_reparaciones_menores_cantidad = $this->input->post('operativos_reparaciones_menores_cantidad', TRUE);
                    $operativos_reparaciones_menores_monto = $this->input->post('operativos_reparaciones_menores_monto', TRUE);
                    $operativos_imprevistos_frecuencia = $this->input->post('operativos_imprevistos_frecuencia', TRUE);
                    $operativos_imprevistos_cantidad = $this->input->post('operativos_imprevistos_cantidad', TRUE);
                    $operativos_imprevistos_monto = $this->input->post('operativos_imprevistos_monto', TRUE);
                    $operativos_impuesto_propiedad_frecuencia = $this->input->post('operativos_impuesto_propiedad_frecuencia', TRUE);
                    $operativos_impuesto_propiedad_cantidad = $this->input->post('operativos_impuesto_propiedad_cantidad', TRUE);
                    $operativos_impuesto_propiedad_monto = $this->input->post('operativos_impuesto_propiedad_monto', TRUE);
                    $operativos_soat_frecuencia = $this->input->post('operativos_soat_frecuencia', TRUE);
                    $operativos_soat_cantidad = $this->input->post('operativos_soat_cantidad', TRUE);
                    $operativos_soat_monto = $this->input->post('operativos_soat_monto', TRUE);
                    $operativos_roseta_inspeccion_frecuencia = $this->input->post('operativos_roseta_inspeccion_frecuencia', TRUE);
                    $operativos_roseta_inspeccion_cantidad = $this->input->post('operativos_roseta_inspeccion_cantidad', TRUE);
                    $operativos_roseta_inspeccion_monto = $this->input->post('operativos_roseta_inspeccion_monto', TRUE);
                    $operativos_otro_transporte_libre1_texto = $this->input->post('operativos_otro_transporte_libre1_texto', TRUE);
                    $operativos_otro_transporte_libre1_frecuencia = $this->input->post('operativos_otro_transporte_libre1_frecuencia', TRUE);
                    $operativos_otro_transporte_libre1_cantidad = $this->input->post('operativos_otro_transporte_libre1_cantidad', TRUE);
                    $operativos_otro_transporte_libre1_monto = $this->input->post('operativos_otro_transporte_libre1_monto', TRUE);
                    $operativos_otro_transporte_libre2_texto = $this->input->post('operativos_otro_transporte_libre2_texto', TRUE);
                    $operativos_otro_transporte_libre2_frecuencia = $this->input->post('operativos_otro_transporte_libre2_frecuencia', TRUE);
                    $operativos_otro_transporte_libre2_cantidad = $this->input->post('operativos_otro_transporte_libre2_cantidad', TRUE);
                    $operativos_otro_transporte_libre2_monto = $this->input->post('operativos_otro_transporte_libre2_monto', TRUE);
                    $operativos_otro_transporte_libre3_texto = $this->input->post('operativos_otro_transporte_libre3_texto', TRUE);
                    $operativos_otro_transporte_libre3_frecuencia = $this->input->post('operativos_otro_transporte_libre3_frecuencia', TRUE);
                    $operativos_otro_transporte_libre3_cantidad = $this->input->post('operativos_otro_transporte_libre3_cantidad', TRUE);
                    $operativos_otro_transporte_libre3_monto = $this->input->post('operativos_otro_transporte_libre3_monto', TRUE);
                    $operativos_otro_transporte_libre4_texto = $this->input->post('operativos_otro_transporte_libre4_texto', TRUE);
                    $operativos_otro_transporte_libre4_frecuencia = $this->input->post('operativos_otro_transporte_libre4_frecuencia', TRUE);
                    $operativos_otro_transporte_libre4_cantidad = $this->input->post('operativos_otro_transporte_libre4_cantidad', TRUE);
                    $operativos_otro_transporte_libre4_monto = $this->input->post('operativos_otro_transporte_libre4_monto', TRUE);
                    $operativos_otro_transporte_libre5_texto = $this->input->post('operativos_otro_transporte_libre5_texto', TRUE);
                    $operativos_otro_transporte_libre5_frecuencia = $this->input->post('operativos_otro_transporte_libre5_frecuencia', TRUE);
                    $operativos_otro_transporte_libre5_cantidad = $this->input->post('operativos_otro_transporte_libre5_cantidad', TRUE);
                    $operativos_otro_transporte_libre5_monto = $this->input->post('operativos_otro_transporte_libre5_monto', TRUE);
                    
                    if($operativos_otro_transporte_libre1_texto == '')
                    {
                        $operativos_otro_transporte_libre1_frecuencia = 1;
                        $operativos_otro_transporte_libre1_cantidad = 0;
                        $operativos_otro_transporte_libre1_monto = 0;
                    }
                    
                    if($operativos_otro_transporte_libre2_texto == '')
                    {
                        $operativos_otro_transporte_libre2_frecuencia = 1;
                        $operativos_otro_transporte_libre2_cantidad = 0;
                        $operativos_otro_transporte_libre2_monto = 0;
                    }
                    
                    if($operativos_otro_transporte_libre3_texto == '')
                    {
                        $operativos_otro_transporte_libre3_frecuencia = 1;
                        $operativos_otro_transporte_libre3_cantidad = 0;
                        $operativos_otro_transporte_libre3_monto = 0;
                    }
                    
                    if($operativos_otro_transporte_libre4_texto == '')
                    {
                        $operativos_otro_transporte_libre4_frecuencia = 1;
                        $operativos_otro_transporte_libre4_cantidad = 0;
                        $operativos_otro_transporte_libre4_monto = 0;
                    }
                    
                    if($operativos_otro_transporte_libre5_texto == '')
                    {
                        $operativos_otro_transporte_libre5_frecuencia = 1;
                        $operativos_otro_transporte_libre5_cantidad = 0;
                        $operativos_otro_transporte_libre5_monto = 0;
                    }
                    
                    // Validación de campos
                    
                    
                    // Guardar en la DB
                    
                    $this->mfunciones_logica->transporte_gastos_operativos(
                            $operativos_cambio_aceite_motor_frecuencia, 
                            $operativos_cambio_aceite_motor_cantidad, 
                            $operativos_cambio_aceite_motor_monto, 
                            $operativos_cambio_aceite_caja_frecuencia, 
                            $operativos_cambio_aceite_caja_cantidad, 
                            $operativos_cambio_aceite_caja_monto, 
                            $operativos_cambio_llanta_delanteras_frecuencia, 
                            $operativos_cambio_llanta_delanteras_cantidad, 
                            $operativos_cambio_llanta_delanteras_monto, 
                            $operativos_cambio_llanta_traseras_frecuencia, 
                            $operativos_cambio_llanta_traseras_cantidad, 
                            $operativos_cambio_llanta_traseras_monto, 
                            $operativos_cambio_bateria_frecuencia, 
                            $operativos_cambio_bateria_cantidad, 
                            $operativos_cambio_bateria_monto, 
                            $operativos_cambio_balatas_frecuencia, 
                            $operativos_cambio_balatas_cantidad, 
                            $operativos_cambio_balatas_monto, 
                            $operativos_revision_electrico_frecuencia, 
                            $operativos_revision_electrico_cantidad, 
                            $operativos_revision_electrico_monto, 
                            $operativos_remachado_embrague_frecuencia, 
                            $operativos_remachado_embrague_cantidad, 
                            $operativos_remachado_embrague_monto, 
                            $operativos_rectificacion_motor_frecuencia, 
                            $operativos_rectificacion_motor_cantidad, 
                            $operativos_rectificacion_motor_monto, 
                            $operativos_cambio_rodamiento_frecuencia, 
                            $operativos_cambio_rodamiento_cantidad, 
                            $operativos_cambio_rodamiento_monto, 
                            $operativos_reparaciones_menores_frecuencia, 
                            $operativos_reparaciones_menores_cantidad, 
                            $operativos_reparaciones_menores_monto, 
                            $operativos_imprevistos_frecuencia, 
                            $operativos_imprevistos_cantidad, 
                            $operativos_imprevistos_monto, 
                            $operativos_impuesto_propiedad_frecuencia, 
                            $operativos_impuesto_propiedad_cantidad, 
                            $operativos_impuesto_propiedad_monto, 
                            $operativos_soat_frecuencia, 
                            $operativos_soat_cantidad, 
                            $operativos_soat_monto, 
                            $operativos_roseta_inspeccion_frecuencia, 
                            $operativos_roseta_inspeccion_cantidad, 
                            $operativos_roseta_inspeccion_monto, 
                            $operativos_otro_transporte_libre1_texto, 
                            $operativos_otro_transporte_libre1_frecuencia, 
                            $operativos_otro_transporte_libre1_cantidad, 
                            $operativos_otro_transporte_libre1_monto, 
                            $operativos_otro_transporte_libre2_texto, 
                            $operativos_otro_transporte_libre2_frecuencia, 
                            $operativos_otro_transporte_libre2_cantidad, 
                            $operativos_otro_transporte_libre2_monto, 
                            $operativos_otro_transporte_libre3_texto, 
                            $operativos_otro_transporte_libre3_frecuencia, 
                            $operativos_otro_transporte_libre3_cantidad, 
                            $operativos_otro_transporte_libre3_monto, 
                            $operativos_otro_transporte_libre4_texto, 
                            $operativos_otro_transporte_libre4_frecuencia, 
                            $operativos_otro_transporte_libre4_cantidad, 
                            $operativos_otro_transporte_libre4_monto, 
                            $operativos_otro_transporte_libre5_texto, 
                            $operativos_otro_transporte_libre5_frecuencia, 
                            $operativos_otro_transporte_libre5_cantidad, 
                            $operativos_otro_transporte_libre5_monto, 
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            );
                    
                    break;
                    
                default:
                    break;
            }
        }
        
        // PASO 4: Establecer que vista es la siguiente en mostrarse dependiendo si es ANT, SIG, FINAL o HOME
        
        switch ($home_ant_sig) {
            // Volver a Home
            case 'home':
                $siguiente_vista = 'view_home';
                break;

            // Volver a Home
            case 'final':
                $siguiente_vista = 'view_final';
                break;
            
            // Volver a la vista anterior siempre y cuando no sea Home
            case 'ant':
                
                if($vista_prospecto->anterior == 'home')
                {
                    $siguiente_vista = 'view_home';
                }
                else
                {
                    $siguiente_vista = 'view_' . $vista_prospecto->anterior;
                }
                
                break;
            
            // Volver a la vista siguiente siempre y cuando no sea Final
            case 'sig':
                
                if($vista_prospecto->siguiente == 'final')
                {
                    $siguiente_vista = 'view_final';
                }
                else
                {
                    $siguiente_vista = 'view_' . $vista_prospecto->siguiente;
                }
                
                break;
                
            default:
                
                $siguiente_vista = 'view_' . $home_ant_sig;
                
                break;
        }
        
        // PASO 4.1: Guardar la siguiente vista como actual, siempre y cuando se haya seleccionado "Guardar"
        if($siguiente_vista != 'view_home')
        {
            switch ((int)$codigo_rubro) {
                
                // Soliciutd de Crédito
                case 6:
                    $this->mfunciones_microcreditos->Guardar_PasoActual($siguiente_vista, $accion_usuario, $accion_fecha, $estructura_id);

                    break;

                // Normalizador/Cobrador
                case 13:
                    $this->mfunciones_cobranzas->Guardar_PasoActual($siguiente_vista, $accion_usuario, $accion_fecha, $estructura_id);

                    break;
                
                default:
                    
                    $this->mfunciones_logica->Guardar_PasoActual($siguiente_vista, $accion_usuario, $accion_fecha, $estructura_id);
                    
                    break;
            }
        }
        elseif($siguiente_vista == 'view_home')
        {
            if($tipo_registro != 'unidad_familiar')
            {
                $this->mfunciones_logica->Guardar_PasoActual($vista_actual, $accion_usuario, $accion_fecha, $estructura_id);
            }
            
            switch ((int)$codigo_rubro) {
                
                // Solicitud de Crédito
                case 6:
                    js_invocacion_javascript('$("div.modal-backdrop").remove(); Ajax_CargadoGeneralPagina("Sol_Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");');

                    break;

                // Normalizador/Cobrador
                case 13:
                    js_invocacion_javascript('$("div.modal-backdrop").remove(); Ajax_CargadoGeneralPagina("Norm_Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");');

                    break;
                
                default:
                    
                    js_invocacion_javascript('$("div.modal-backdrop").remove(); Ajax_CargadoGeneralPagina("Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");');
                    
                    break;
            }
            
            exit();
        }
        
        // PASO 5: Cargar datos de la vista siguiente
        
        switch ($siguiente_vista) {
            
            case 'view_norm_datos_personales':
                
                $DatosNormalizador = $this->mfunciones_cobranzas->DatosRegistroEmail($estructura_id);
                
                $this->load->model('mfunciones_microcreditos');
                
                // Seleccion de Agencias asociadas
                $aux_agencias = $this->mfunciones_generales->getUsuarioRegion($DatosNormalizador[0]['agente_codigo']);

                $arrAgencias = $this->mfunciones_microcreditos->ObtenerAgenciasFiltrado($aux_agencias->region_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrAgencias);

                if (isset($arrAgencias[0]))
                {
                    $i = 0;

                    foreach ($arrAgencias as $key => $value) {
                        $item_valor = array(
                            "estructura_regional_id" => $value["estructura_regional_id"],
                            "estructura_regional_nombre" => $value["estructura_regional_nombre"] . ((int)$value["estructura_regional_estado"]==1 ? '' : ' (Cerrada)')
                        );
                        $lst_resultado_agencia[$i] = $item_valor;

                        $i++;
                    }
                }
                else 
                {
                    $lst_resultado_agencia[0] = $arrAgencias;
                }

                $data["arrAgencias"] = $lst_resultado_agencia;
                
                $lst_resultado[0] = $DatosNormalizador[0];
                
                break;
            
            case 'view_norm_direccion':
                
                $this->load->model('mfunciones_microcreditos');
                
                $arrDirecciones = $this->mfunciones_cobranzas->getDireccionesRegistro(-1, $estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDirecciones);
                
                if (isset($arrDirecciones[0]))
                {
                    $i = 0;

                    foreach ($arrDirecciones as $key => $value) {
                        $item_valor = array(
                            "rd_id" => $value["rd_id"],
                            "tipo_persona_id" => $value["tipo_persona_id"],
                            "codigo_registro" => $value["codigo_registro"],
                            "rd_tipo" => $this->mfunciones_cobranzas->GetValorCatalogo($value['rd_tipo'], 'rd_tipo_abrev'),
                            "rd_dir_departamento" => $this->mfunciones_generales->GetValorCatalogoDB($value['rd_dir_departamento'], 'dir_departamento'),
                            "rd_dir_provincia" => $this->mfunciones_generales->GetValorCatalogoDB($value['rd_dir_provincia'], 'dir_provincia'),
                            "rd_dir_localidad_ciudad" => $this->mfunciones_generales->GetValorCatalogoDB($value['rd_dir_localidad_ciudad'], 'dir_localidad_ciudad'),
                            "rd_cod_barrio" => $this->mfunciones_generales->GetValorCatalogoDB($value['rd_cod_barrio'], 'dir_barrio_zona_uv'),
                            "rd_cod_barrio_codigo" => $value["rd_cod_barrio"],
                            "rd_direccion" => $value["rd_direccion"],
                            "rd_edificio" => $value["rd_edificio"],
                            "rd_numero" => $value["rd_numero"],
                            "rd_referencia_texto" => $value["rd_referencia_texto"],
                            "rd_referencia" => $this->mfunciones_cobranzas->GetValorCatalogo($value['rd_referencia'], 'rd_referencia'),
                            "rd_referencia_codigo" => $value["rd_referencia"],
                            "rd_geolocalizacion" => $value["rd_geolocalizacion"],
                            "rd_geolocalizacion_img" => $value["rd_geolocalizacion_img"],
                            "rd_croquis" => $value['rd_croquis'],
                            "rd_trabajo_lugar" => $this->mfunciones_microcreditos->GetValorCatalogo($value['rd_trabajo_lugar'], 'sol_trabajo_lugar'),
                            "rd_trabajo_lugar_otro" => $value["rd_trabajo_lugar_otro"],
                            "rd_trabajo_realiza" => $this->mfunciones_microcreditos->GetValorCatalogo($value['rd_trabajo_realiza'], 'sol_trabajo_realiza'),
                            "rd_trabajo_realiza_otro" => $value["rd_trabajo_realiza_otro"],
                            "sol_dom_tipo" => $this->mfunciones_microcreditos->GetValorCatalogo($value['sol_dom_tipo'], 'sol_dom_tipo'),
                            "rd_dom_tipo_otro" => $value["rd_dom_tipo_otro"]
                        );
                        $lst_resultado_direcciones[$i] = $item_valor;

                        $i++;
                    }
                }
                else 
                {
                    $lst_resultado_direcciones[0] = $arrDirecciones;
                }
                
                $data["tipo_persona_id"] = $codigo_rubro;
                $data["codigo_registro"] = $estructura_id;
                
                $data["arrDirecciones"] = $lst_resultado_direcciones;
                
                break;
                
            case 'view_sol_datos_personales':
            case 'view_sol_con_datos_personales':
            case 'view_sol_credito_solicitado':
            case 'view_sol_direccion':
            case 'view_sol_con_direccion':
            case 'view_sol_direccion_auxcopy':
                
                $arrResultado = $this->mfunciones_microcreditos->ObtenerDetalleSolicitudCredito($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    // Revisar todos los campos, remplazar espacio en blanco por vacío
                    foreach ($arrResultado[0] as $key_aux => $value_aux)
                    {
                        if((string)$arrResultado[0][$key_aux] == ' ')
                        {
                            $arrResultado[0][$key_aux] = '';
                        }
                        $arrResultado[0][$key_aux] = htmlspecialchars($arrResultado[0][$key_aux]);
                    }
                    
                    if($siguiente_vista == 'view_sol_direccion' || $siguiente_vista == 'view_sol_con_direccion')
                    {
                        // Sólo para las vistas de dirección
                        
                        // Si realizo Check-In y no se registró aún la GEO, se asigna el valor del check-in
                        if(preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $arrResultado[0]['sol_checkin_geo']))
                        {
                            $arrResultado[0]['sol_geolocalizacion'] = $this->mfunciones_microcreditos->setAuxGEO($arrResultado[0]['sol_geolocalizacion'], $arrResultado[0]['sol_checkin_geo']);
                            $arrResultado[0]['sol_geolocalizacion_dom'] = $this->mfunciones_microcreditos->setAuxGEO($arrResultado[0]['sol_geolocalizacion_dom'], $arrResultado[0]['sol_checkin_geo']);
                            $arrResultado[0]['sol_con_geolocalizacion'] = $this->mfunciones_microcreditos->setAuxGEO($arrResultado[0]['sol_con_geolocalizacion'], $arrResultado[0]['sol_checkin_geo']);
                            $arrResultado[0]['sol_con_geolocalizacion_dom'] = $this->mfunciones_microcreditos->setAuxGEO($arrResultado[0]['sol_con_geolocalizacion_dom'], $arrResultado[0]['sol_checkin_geo']);
                            // Actividad Secundaria
                            $arrResultado[0]['sol_geolocalizacion_sec'] = $this->mfunciones_microcreditos->setAuxGEO($arrResultado[0]['sol_geolocalizacion_sec'], $arrResultado[0]['sol_checkin_geo']);
                        }
                        
                        $arrResultado[0]['sol_geolocalizacion'] = $this->mfunciones_microcreditos->validateGEO($arrResultado[0]['sol_geolocalizacion'], $arrResultado[0]['sol_dir_departamento']);
                        $arrResultado[0]['sol_geolocalizacion_dom'] = $this->mfunciones_microcreditos->validateGEO($arrResultado[0]['sol_geolocalizacion_dom'], $arrResultado[0]['sol_dir_departamento_dom']);
                        $arrResultado[0]['sol_con_geolocalizacion'] = $this->mfunciones_microcreditos->validateGEO($arrResultado[0]['sol_con_geolocalizacion'], $arrResultado[0]['sol_con_dir_departamento']);
                        $arrResultado[0]['sol_con_geolocalizacion_dom'] = $this->mfunciones_microcreditos->validateGEO($arrResultado[0]['sol_con_geolocalizacion_dom'], $arrResultado[0]['sol_con_dir_departamento_dom']);

                        if((int)$arrResultado[0]['sol_dir_departamento'] == -1) { $arrResultado[0]['sol_dir_departamento'] = ''; }
                        if((int)$arrResultado[0]['sol_dir_provincia'] == -1) { $arrResultado[0]['sol_dir_provincia'] = ''; }
                        if((int)$arrResultado[0]['sol_dir_localidad_ciudad'] == -1) { $arrResultado[0]['sol_dir_localidad_ciudad'] = ''; }
                        if((int)$arrResultado[0]['sol_cod_barrio'] == -1) { $arrResultado[0]['sol_cod_barrio'] = ''; }

                        if((int)$arrResultado[0]['sol_dir_departamento_dom'] == -1) { $arrResultado[0]['sol_dir_departamento_dom'] = ''; }
                        if((int)$arrResultado[0]['sol_dir_provincia_dom'] == -1) { $arrResultado[0]['sol_dir_provincia_dom'] = ''; }
                        if((int)$arrResultado[0]['sol_dir_localidad_ciudad_dom'] == -1) { $arrResultado[0]['sol_dir_localidad_ciudad_dom'] = ''; }
                        if((int)$arrResultado[0]['sol_cod_barrio_dom'] == -1) { $arrResultado[0]['sol_cod_barrio_dom'] = ''; }

                        if((int)$arrResultado[0]['sol_con_dir_departamento'] == -1) { $arrResultado[0]['sol_con_dir_departamento'] = ''; }
                        if((int)$arrResultado[0]['sol_con_dir_provincia'] == -1) { $arrResultado[0]['sol_con_dir_provincia'] = ''; }
                        if((int)$arrResultado[0]['sol_con_dir_localidad_ciudad'] == -1) { $arrResultado[0]['sol_con_dir_localidad_ciudad'] = ''; }
                        if((int)$arrResultado[0]['sol_con_cod_barrio'] == -1) { $arrResultado[0]['sol_con_cod_barrio'] = ''; }

                        if((int)$arrResultado[0]['sol_con_dir_departamento_dom'] == -1) { $arrResultado[0]['sol_con_dir_departamento_dom'] = ''; }
                        if((int)$arrResultado[0]['sol_con_dir_provincia_dom'] == -1) { $arrResultado[0]['sol_con_dir_provincia_dom'] = ''; }
                        if((int)$arrResultado[0]['sol_con_dir_localidad_ciudad_dom'] == -1) { $arrResultado[0]['sol_con_dir_localidad_ciudad_dom'] = ''; }
                        if((int)$arrResultado[0]['sol_con_cod_barrio_dom'] == -1) { $arrResultado[0]['sol_con_cod_barrio_dom'] = ''; }
                        
                        // Actividad Secundaria
                        if((int)$arrResultado[0]['sol_dir_departamento_sec'] == -1) { $arrResultado[0]['sol_dir_departamento_sec'] = ''; }
                        if((int)$arrResultado[0]['sol_dir_provincia_sec'] == -1) { $arrResultado[0]['sol_dir_provincia_sec'] = ''; }
                        if((int)$arrResultado[0]['sol_dir_localidad_ciudad_sec'] == -1) { $arrResultado[0]['sol_dir_localidad_ciudad_sec'] = ''; }
                        if((int)$arrResultado[0]['sol_cod_barrio_sec'] == -1) { $arrResultado[0]['sol_cod_barrio_sec'] = ''; }
                        
                    }
                    
                    // Vista auxiliar para copiar la dirección de los datos
                    if($siguiente_vista == 'view_sol_direccion_auxcopy')
                    {
                        $siguiente_vista = 'view_sol_con_direccion';
                        
                        $arrResultado[0]['sol_con_dir_departamento'] = $arrResultado[0]['sol_dir_departamento'];
                        $arrResultado[0]['sol_con_dir_provincia'] = $arrResultado[0]['sol_dir_provincia'];
                        $arrResultado[0]['sol_con_dir_localidad_ciudad'] = $arrResultado[0]['sol_dir_localidad_ciudad'];
                        $arrResultado[0]['sol_con_cod_barrio'] = $arrResultado[0]['sol_cod_barrio'];
                        $arrResultado[0]['sol_con_direccion_trabajo'] = $arrResultado[0]['sol_direccion_trabajo'];
                        $arrResultado[0]['sol_con_edificio_trabajo'] = $arrResultado[0]['sol_edificio_trabajo'];
                        $arrResultado[0]['sol_con_numero_trabajo'] = $arrResultado[0]['sol_numero_trabajo'];
                        $arrResultado[0]['sol_con_trabajo_lugar'] = $arrResultado[0]['sol_trabajo_lugar'];
                        $arrResultado[0]['sol_con_trabajo_lugar_otro'] = $arrResultado[0]['sol_trabajo_lugar_otro'];
                        $arrResultado[0]['sol_con_trabajo_realiza'] = $arrResultado[0]['sol_trabajo_realiza'];
                        $arrResultado[0]['sol_con_trabajo_realiza_otro'] = $arrResultado[0]['sol_trabajo_realiza_otro'];
                        $arrResultado[0]['sol_con_dir_referencia'] = $arrResultado[0]['sol_dir_referencia'];
                        $arrResultado[0]['sol_con_geolocalizacion'] = $arrResultado[0]['sol_geolocalizacion'];
                        $arrResultado[0]['sol_con_croquis'] = $arrResultado[0]['sol_croquis'];
                        $arrResultado[0]['sol_con_dir_departamento_dom'] = $arrResultado[0]['sol_dir_departamento_dom'];
                        $arrResultado[0]['sol_con_dir_provincia_dom'] = $arrResultado[0]['sol_dir_provincia_dom'];
                        $arrResultado[0]['sol_con_dir_localidad_ciudad_dom'] = $arrResultado[0]['sol_dir_localidad_ciudad_dom'];
                        $arrResultado[0]['sol_con_cod_barrio_dom'] = $arrResultado[0]['sol_cod_barrio_dom'];
                        $arrResultado[0]['sol_con_direccion_dom'] = $arrResultado[0]['sol_direccion_dom'];
                        $arrResultado[0]['sol_con_edificio_dom'] = $arrResultado[0]['sol_edificio_dom'];
                        $arrResultado[0]['sol_con_numero_dom'] = $arrResultado[0]['sol_numero_dom'];
                        $arrResultado[0]['sol_con_dom_tipo'] = $arrResultado[0]['sol_dom_tipo'];
                        $arrResultado[0]['sol_con_dom_tipo_otro'] = $arrResultado[0]['sol_dom_tipo_otro'];
                        $arrResultado[0]['sol_con_dir_referencia_dom'] = $arrResultado[0]['sol_dir_referencia_dom'];
                        $arrResultado[0]['sol_con_geolocalizacion_dom'] = $arrResultado[0]['sol_geolocalizacion_dom'];
                        $arrResultado[0]['sol_con_croquis_dom'] = $arrResultado[0]['sol_croquis_dom'];
                        
                        // Copiar la geolocalización en la DB
                        $this->mfunciones_microcreditos->SolCopyGeoSolCon($estructura_id);
                    }
                    
                    $lst_resultado[0] = $arrResultado[0];
                }
                
                break;
            
            // Requerimiento IM-27 !IMPORTANTE: NO DEBE GUARDARSE COMO ÚLTIMO PASO EL CALCULO DE LA CUOTA; DARÁ ERROR AL BÓTON "REGISTRO"
            case 'view_calculo_cuota':

                $arrResultado = $this->mfunciones_logica->select_calculadora_financiera($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "ejecutivo_id" => $value["ejecutivo_id"],
                            "cuota_tasa" => $value["cuota_tasa"],
                            "cuota_periodicidad" => $value["cuota_periodicidad"],
                            "cuota_plazo_meses" => $value["cuota_plazo_meses"],
                            "cuota_meses_gracia" => $value["cuota_meses_gracia"],
                            "cuota_tipo" => $value["cuota_tipo"],
                            "cuota_seguro" => $value["cuota_seguro"],
                            "cuota_seguro_nro_personas" => $value["cuota_seguro_nro_personas"],
                            "cuota_seguro_tasa" => $value["cuota_seguro_tasa"]
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }

                break;
            
            case 'view_datos_personales':
            case 'view_ubicacion':
            case 'view_actividad_economica':
            case 'view_referencias':
                
                $codigo_terceros = $this->mfunciones_generales->ObtenerCodigoTercerosProspecto($estructura_id);
                
                $arrResultado = $this->mfunciones_logica->ObtenerDetalleSolicitudTerceros($codigo_terceros);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                // Excepcionalidad para la GEO de domicilio y trabajo
                
                // Detalle del Prospecto
                $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
                
                // Si realizó Check-In y Domicilio sigue con los valores por defecto, se cambia a la GEO del Check-In
                if($arrResultado3[0]['prospecto_checkin'] == 1 && $arrResultado[0]['coordenadas_geo_dom'] == GEO_FIE)
                {
                    if(!preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $arrResultado3[0]['prospecto_checkin_geo']))
                    {
                        $arrResultado[0]['coordenadas_geo_dom'] = GEO_FIE;
                    }
                    else
                    {
                        $arrResultado[0]['coordenadas_geo_dom'] = $arrResultado3[0]['prospecto_checkin_geo'];
                    }
                }

                // Si realizó Check-In y Trabajo sigue con los valores por defecto, se cambia a la GEO del Check-In
                if($arrResultado3[0]['prospecto_checkin'] == 1 && $arrResultado[0]['coordenadas_geo_trab'] == GEO_FIE)
                {
                    if(!preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $arrResultado3[0]['prospecto_checkin_geo']))
                    {
                        $arrResultado[0]['coordenadas_geo_trab'] = GEO_FIE;
                    }
                    else
                    {
                        $arrResultado[0]['coordenadas_geo_trab'] = $arrResultado3[0]['prospecto_checkin_geo'];
                    }
                }
                
                
                $lst_resultado = $arrResultado;
                
                break;
            
            case 'view_datos_generales':

                switch ($tipo_registro) {
                    case 'unidad_familiar':
                        
                        $arrEjecutivoProsp = $this->mfunciones_logica->ObtenerEjecutivoProspecto($estructura_id);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEjecutivoProsp);
                        
                        $lst_resultado[0] = array(
                            "ejecutivo_id" => $arrEjecutivoProsp[0]['ejecutivo_id'],
                            "general_categoria" => 2
                            );
                        
                        break;

                    default:
                        
                        $arrResultado = $this->mfunciones_logica->select_datos_generales($estructura_id);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                        if (isset($arrResultado[0])) 
                        {
                            $i = 0;

                            foreach ($arrResultado as $key => $value) 
                            {
                                $item_valor = array(
                                    "ejecutivo_id" => $value["ejecutivo_id"],
                                    "general_categoria" => $value["general_categoria"],
                                    "general_solicitante" => $value["general_solicitante"],
                                    "general_telefono" => $value["general_telefono"],
                                    "general_email" => $value["general_email"],
                                    "general_direccion" => $value["general_direccion"],
                                    "general_actividad" => $value["general_actividad"],
                                    "general_ci" => $value["general_ci"],
                                    "general_ci_extension" => $value["general_ci_extension"],
                                    "general_destino" => $value["general_destino"],
                                    "general_comentarios" => $value["general_comentarios"],
                                    "general_interes" => $value["general_interes"],
                                    "operacion_efectivo" => $value["operacion_efectivo"],
                                    "operacion_dias" => $value["operacion_dias"],
                                    "operacion_antiguedad" => $value["operacion_antiguedad"],
                                    "operacion_tiempo" => $value["operacion_tiempo"],
                                    "aclarar_contado" => ($value["aclarar_contado"]==100 ? 100 : $value["aclarar_contado"]),
                                    "aclarar_credito" => $value["aclarar_credito"],
                                    "operacion_criterio" => $value["operacion_criterio"],
                                    "operacion_antiguedad_ano" => $value["operacion_antiguedad_ano"],
                                    "operacion_antiguedad_mes" => $value["operacion_antiguedad_mes"],
                                    "operacion_tiempo_ano" => $value["operacion_tiempo_ano"],
                                    "operacion_tiempo_mes" => $value["operacion_tiempo_mes"]
                                );
                                $lst_resultado[$i] = $item_valor;

                                $i++;
                            }
                        }
                        
                        break;
                }
                
                break;

            case 'view_frecuencia_venta':

                $arrResultado = $this->mfunciones_logica->select_frecuencia_venta($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "frec_seleccion" => $value["frec_seleccion"],
                            "frec_dia_lunes" => $value["frec_dia_lunes"],
                            "frec_dia_martes" => $value["frec_dia_martes"],
                            "frec_dia_miercoles" => $value["frec_dia_miercoles"],
                            "frec_dia_jueves" => $value["frec_dia_jueves"],
                            "frec_dia_viernes" => $value["frec_dia_viernes"],
                            "frec_dia_sabado" => $value["frec_dia_sabado"],
                            "frec_dia_domingo" => $value["frec_dia_domingo"],
                            "frec_dia_semana_sel" => $value["frec_dia_semana_sel"],
                            "frec_dia_semana_sel_brm" => $value["frec_dia_semana_sel_brm"],
                            "frec_dia_semana_monto2" => $value["frec_dia_semana_monto2"],
                            "frec_dia_semana_monto3" => $value["frec_dia_semana_monto3"],
                            "frec_dia_eval_semana1_brm" => $value["frec_dia_eval_semana1_brm"],
                            "frec_dia_eval_semana2_brm" => $value["frec_dia_eval_semana2_brm"],
                            "frec_dia_eval_semana3_brm" => $value["frec_dia_eval_semana3_brm"],
                            "frec_dia_eval_semana4_brm" => $value["frec_dia_eval_semana4_brm"],
                            "frec_sem_semana1_monto" => $value["frec_sem_semana1_monto"],
                            "frec_sem_semana2_monto" => $value["frec_sem_semana2_monto"],
                            "frec_sem_semana3_monto" => $value["frec_sem_semana3_monto"],
                            "frec_sem_semana4_monto" => $value["frec_sem_semana4_monto"],
                            "frec_mes_sel" => $value["frec_mes_sel"],
                            "frec_mes_mes1_monto" => $value["frec_mes_mes1_monto"],
                            "frec_mes_mes2_monto" => $value["frec_mes_mes2_monto"],
                            "frec_mes_mes3_monto" => $value["frec_mes_mes3_monto"]
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                } 

                break;
                
            case 'view_margen_utilidad':
            case 'view_proveedores':
                                                    // Función para margen_utilidad y proveedores 
                $arrResultado = $this->mfunciones_logica->select_margenes($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "margen_utilidad_productos" => $value["margen_utilidad_productos"],
                            "porcentaje_participacion_proveedores" => $value["porcentaje_participacion_proveedores"]
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }
                
                $data[''] = 2;

                break;
            
            case 'view_materia_prima':
                
                $lst_resultado[0] = array();
                
                break;
            
            case 'view_estacionalidad':
                
                $arrResultado = $this->mfunciones_logica->select_estacionalidad($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "capacidad_criterio" => $value["capacidad_criterio"],
                            "capacidad_monto_manual" => $value["capacidad_monto_manual"],
                            "estacion_sel" => $value["estacion_sel"],
                            "estacion_sel_mes" => $value["estacion_sel_mes"],
                            "estacion_sel_arb" => $value["estacion_sel_arb"],
                            "estacion_monto2" => $value["estacion_monto2"],
                            "estacion_monto3" => $value["estacion_monto3"],
                            "estacion_ene_arb" => $value["estacion_ene_arb"],
                            "estacion_feb_arb" => $value["estacion_feb_arb"],
                            "estacion_mar_arb" => $value["estacion_mar_arb"],
                            "estacion_abr_arb" => $value["estacion_abr_arb"],
                            "estacion_may_arb" => $value["estacion_may_arb"],
                            "estacion_jun_arb" => $value["estacion_jun_arb"],
                            "estacion_jul_arb" => $value["estacion_jul_arb"],
                            "estacion_ago_arb" => $value["estacion_ago_arb"],
                            "estacion_sep_arb" => $value["estacion_sep_arb"],
                            "estacion_oct_arb" => $value["estacion_oct_arb"],
                            "estacion_nov_arb" => $value["estacion_nov_arb"],
                            "estacion_dic_arb" => $value["estacion_dic_arb"]
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }

                break;
            
            case 'view_gastos_operativos':
                
                $arrResultado = $this->mfunciones_logica->select_gastos_operativos($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "operativos_alq_energia_monto" => $value["operativos_alq_energia_monto"],
                            "operativos_alq_energia_aclaracion" => $value["operativos_alq_energia_aclaracion"],
                            "operativos_alq_agua_monto" => $value["operativos_alq_agua_monto"],
                            "operativos_alq_agua_aclaracion" => $value["operativos_alq_agua_aclaracion"],
                            "operativos_alq_internet_monto" => $value["operativos_alq_internet_monto"],
                            "operativos_alq_internet_aclaracion" => $value["operativos_alq_internet_aclaracion"],
                            "operativos_alq_combustible_monto" => $value["operativos_alq_combustible_monto"],
                            "operativos_alq_combustible_aclaracion" => $value["operativos_alq_combustible_aclaracion"],
                            "operativos_alq_libre1_texto" => $value["operativos_alq_libre1_texto"],
                            "operativos_alq_libre1_monto" => $value["operativos_alq_libre1_monto"],
                            "operativos_alq_libre1_aclaracion" => $value["operativos_alq_libre1_aclaracion"],
                            "operativos_alq_libre2_texto" => $value["operativos_alq_libre2_texto"],
                            "operativos_alq_libre2_monto" => $value["operativos_alq_libre2_monto"],
                            "operativos_alq_libre2_aclaracion" => $value["operativos_alq_libre2_aclaracion"],
                            "operativos_sal_aguinaldos_monto" => $value["operativos_sal_aguinaldos_monto"],
                            "operativos_sal_aguinaldos_aclaracion" => $value["operativos_sal_aguinaldos_aclaracion"],
                            "operativos_sal_libre1_texto" => $value["operativos_sal_libre1_texto"],
                            "operativos_sal_libre1_monto" => $value["operativos_sal_libre1_monto"],
                            "operativos_sal_libre1_aclaracion" => $value["operativos_sal_libre1_aclaracion"],
                            "operativos_sal_libre2_texto" => $value["operativos_sal_libre2_texto"],
                            "operativos_sal_libre2_monto" => $value["operativos_sal_libre2_monto"],
                            "operativos_sal_libre2_aclaracion" => $value["operativos_sal_libre2_aclaracion"],
                            "operativos_sal_libre3_texto" => $value["operativos_sal_libre3_texto"],
                            "operativos_sal_libre3_monto" => $value["operativos_sal_libre3_monto"],
                            "operativos_sal_libre3_aclaracion" => $value["operativos_sal_libre3_aclaracion"],
                            "operativos_sal_libre4_texto" => $value["operativos_sal_libre4_texto"],
                            "operativos_sal_libre4_monto" => $value["operativos_sal_libre4_monto"],
                            "operativos_sal_libre4_aclaracion" => $value["operativos_sal_libre4_aclaracion"],
                            "operativos_otro_transporte_monto" => $value["operativos_otro_transporte_monto"],
                            "operativos_otro_transporte_aclaracion" => $value["operativos_otro_transporte_aclaracion"],
                            "operativos_otro_licencias_monto" => $value["operativos_otro_licencias_monto"],
                            "operativos_otro_licencias_aclaracion" => $value["operativos_otro_licencias_aclaracion"],
                            "operativos_otro_alimentacion_monto" => $value["operativos_otro_alimentacion_monto"],
                            "operativos_otro_alimentacion_aclaracion" => $value["operativos_otro_alimentacion_aclaracion"],
                            "operativos_otro_mant_vehiculo_monto" => $value["operativos_otro_mant_vehiculo_monto"],
                            "operativos_otro_mant_vehiculo_aclaracion" => $value["operativos_otro_mant_vehiculo_aclaracion"],
                            "operativos_otro_mant_maquina_monto" => $value["operativos_otro_mant_maquina_monto"],
                            "operativos_otro_mant_maquina_aclaracion" => $value["operativos_otro_mant_maquina_aclaracion"],
                            "operativos_otro_imprevistos_monto" => $value["operativos_otro_imprevistos_monto"],
                            "operativos_otro_imprevistos_aclaracion" => $value["operativos_otro_imprevistos_aclaracion"],
                            "operativos_otro_otros_monto" => $value["operativos_otro_otros_monto"],
                            "operativos_otro_otros_aclaracion" => $value["operativos_otro_otros_aclaracion"],
                            "operativos_otro_libre1_texto" => $value["operativos_otro_libre1_texto"],
                            "operativos_otro_libre1_monto" => $value["operativos_otro_libre1_monto"],
                            "operativos_otro_libre1_aclaracion" => $value["operativos_otro_libre1_aclaracion"],
                            "operativos_otro_libre2_texto" => $value["operativos_otro_libre2_texto"],
                            "operativos_otro_libre2_monto" => $value["operativos_otro_libre2_monto"],
                            "operativos_otro_libre2_aclaracion" => $value["operativos_otro_libre2_aclaracion"],
                            "operativos_otro_libre3_texto" => $value["operativos_otro_libre3_texto"],
                            "operativos_otro_libre3_monto" => $value["operativos_otro_libre3_monto"],
                            "operativos_otro_libre3_aclaracion" => $value["operativos_otro_libre3_aclaracion"],
                            "operativos_otro_libre4_texto" => $value["operativos_otro_libre4_texto"],
                            "operativos_otro_libre4_monto" => $value["operativos_otro_libre4_monto"],
                            "operativos_otro_libre4_aclaracion" => $value["operativos_otro_libre4_aclaracion"],
                            "operativos_otro_libre5_texto" => $value["operativos_otro_libre5_texto"],
                            "operativos_otro_libre5_monto" => $value["operativos_otro_libre5_monto"],
                            "operativos_otro_libre5_aclaracion" => $value["operativos_otro_libre5_aclaracion"]
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }

                break;
            
            case 'view_gastos_familiares':

                $arrResultado = $this->mfunciones_logica->select_gastos_familiares($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "familiar_dependientes_ingreso" => $value["familiar_dependientes_ingreso"],
                            "familiar_edad_hijos" => $value["familiar_edad_hijos"],
                            "familiar_alimentacion_monto" => $value["familiar_alimentacion_monto"],
                            "familiar_alimentacion_aclaracion" => $value["familiar_alimentacion_aclaracion"],
                            "familiar_energia_monto" => $value["familiar_energia_monto"],
                            "familiar_energia_aclaracion" => $value["familiar_energia_aclaracion"],
                            "familiar_agua_monto" => $value["familiar_agua_monto"],
                            "familiar_agua_aclaracion" => $value["familiar_agua_aclaracion"],
                            "familiar_gas_monto" => $value["familiar_gas_monto"],
                            "familiar_gas_aclaracion" => $value["familiar_gas_aclaracion"],
                            "familiar_telefono_monto" => $value["familiar_telefono_monto"],
                            "familiar_telefono_aclaracion" => $value["familiar_telefono_aclaracion"],
                            "familiar_celular_monto" => $value["familiar_celular_monto"],
                            "familiar_celular_aclaracion" => $value["familiar_celular_aclaracion"],
                            "familiar_internet_monto" => $value["familiar_internet_monto"],
                            "familiar_internet_aclaracion" => $value["familiar_internet_aclaracion"],
                            "familiar_tv_monto" => $value["familiar_tv_monto"],
                            "familiar_tv_aclaracion" => $value["familiar_tv_aclaracion"],
                            "familiar_impuestos_monto" => $value["familiar_impuestos_monto"],
                            "familiar_impuestos_aclaracion" => $value["familiar_impuestos_aclaracion"],
                            "familiar_alquileres_monto" => $value["familiar_alquileres_monto"],
                            "familiar_alquileres_aclaracion" => $value["familiar_alquileres_aclaracion"],
                            "familiar_educacion_monto" => $value["familiar_educacion_monto"],
                            "familiar_educacion_aclaracion" => $value["familiar_educacion_aclaracion"],
                            "familiar_transporte_monto" => $value["familiar_transporte_monto"],
                            "familiar_transporte_aclaracion" => $value["familiar_transporte_aclaracion"],
                            "familiar_salud_monto" => $value["familiar_salud_monto"],
                            "familiar_salud_aclaracion" => $value["familiar_salud_aclaracion"],
                            "familiar_empleada_monto" => $value["familiar_empleada_monto"],
                            "familiar_empleada_aclaracion" => $value["familiar_empleada_aclaracion"],
                            "familiar_diversion_monto" => $value["familiar_diversion_monto"],
                            "familiar_diversion_aclaracion" => $value["familiar_diversion_aclaracion"],
                            "familiar_vestimenta_monto" => $value["familiar_vestimenta_monto"],
                            "familiar_vestimenta_aclaracion" => $value["familiar_vestimenta_aclaracion"],
                            "familiar_otros_monto" => $value["familiar_otros_monto"],
                            "familiar_otros_aclaracion" => $value["familiar_otros_aclaracion"],
                            "familiar_libre1_texto" => $value["familiar_libre1_texto"],
                            "familiar_libre1_monto" => $value["familiar_libre1_monto"],
                            "familiar_libre1_aclaracion" => $value["familiar_libre1_aclaracion"],
                            "familiar_libre2_texto" => $value["familiar_libre2_texto"],
                            "familiar_libre2_monto" => $value["familiar_libre2_monto"],
                            "familiar_libre2_aclaracion" => $value["familiar_libre2_aclaracion"],
                            "familiar_libre3_texto" => $value["familiar_libre3_texto"],
                            "familiar_libre3_monto" => $value["familiar_libre3_monto"],
                            "familiar_libre3_aclaracion" => $value["familiar_libre3_aclaracion"],
                            "familiar_libre4_texto" => $value["familiar_libre4_texto"],
                            "familiar_libre4_monto" => $value["familiar_libre4_monto"],
                            "familiar_libre4_aclaracion" => $value["familiar_libre4_aclaracion"],
                            "familiar_libre5_texto" => $value["familiar_libre5_texto"],
                            "familiar_libre5_monto" => $value["familiar_libre5_monto"],
                            "familiar_libre5_aclaracion" => $value["familiar_libre5_aclaracion"]
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }

                break;
            
            case 'view_otros_ingresos':

                $arrResultado = $this->mfunciones_logica->select_otros_ingresos($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $aux_balance = $this->mfunciones_generales->IngresoBalanceSecundarias($value["prospecto_id"], $value["prospecto_principal"]);
                        
                        $aux_pasivos = $this->mfunciones_generales->IngresoBalanceLead($value["prospecto_id"], '', $segmento='pasivos');
                        
                        $item_valor = array(
                            "extra_cuota_prestamo_solicitado" => $value["extra_cuota_prestamo_solicitado"],
                            "extra_amortizacion_otras_deudas" => $aux_pasivos->total_otros_pasivos,
                            "extra_cuota_maxima_credito" => $value["extra_cuota_maxima_credito"],
                            "extra_amortizacion_credito" => $value["extra_amortizacion_credito"],
                            "extra_efectivo_caja" => $value["extra_efectivo_caja"],
                            "extra_ahorro_dpf" => $value["extra_ahorro_dpf"],
                            "extra_cuentas_cobrar" => $value["extra_cuentas_cobrar"],
                            //"extra_inventario" => $value["extra_inventario"],
                            "extra_inventario" => $aux_pasivos->total_inventario,
                            "extra_otros_activos_corrientes" => $value["extra_otros_activos_corrientes"],
                            "extra_activo_fijo" => $value["extra_activo_fijo"],
                            "extra_otros_activos_nocorrientes" => $value["extra_otros_activos_nocorrientes"],
                            "extra_activos_actividades_secundarias" => $aux_balance->total_activo_secundarias,
                            "extra_inmuebles_terrenos" => $value["extra_inmuebles_terrenos"],
                            "extra_bienes_hogar" => $value["extra_bienes_hogar"],
                            "extra_otros_activos_familiares" => $value["extra_otros_activos_familiares"],
                            "extra_endeudamiento_credito" => $value["extra_endeudamiento_credito"],
                            "extra_personal_ocupado" => $value["extra_personal_ocupado"],
                            "extra_cuentas_pagar_proveedores" => $value["extra_cuentas_pagar_proveedores"],
                            "extra_prestamos_financieras_corto" => $value["extra_prestamos_financieras_corto"],
                            "extra_cuentas_pagar_corto" => $value["extra_cuentas_pagar_corto"],
                            "extra_prestamos_financieras_largo" => $value["extra_prestamos_financieras_largo"],
                            "extra_cuentas_pagar_largo" => $value["extra_cuentas_pagar_largo"],
                            "extra_pasivo_actividades_secundarias" => $aux_balance->total_pasivo_secundarias,
                            "extra_pasivo_familiar" => $value["extra_pasivo_familiar"]
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                } 

                break;
            
            case 'view_final':
                
                $lst_resultado[0] = array();
                
                break;
            
            case 'view_fuente_generadora':
                
                $arrResultado = $this->mfunciones_logica->select_fuente_generadora($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                
                $lst_resultado = $arrResultado;
                
                break;
            
            case 'view_volumen_ingresos':
                
                $arrResultado = $this->mfunciones_logica->select_volumen_ingresos($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                
                $lst_resultado = $arrResultado;
                
                break;
            
            case 'view_transporte_margen_utilidad':
                
                $lst_resultado[0] = array();
                
                break;
            
            case 'view_transporte_gastos_operativos':
                
                $arrResultado = $this->mfunciones_logica->select_transporte_gastos_operativos($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                
                $lst_resultado = $arrResultado;
                
                break;
            
            case 'view_transporte_otros_ingresos':
                
                $lst_resultado[0] = array();
                
                break;
            
            
            case 'view_pasivos':
                
                $lst_resultado[0] = array();
                
                break;
            
            default:
                break;
        }
        
        // Si la siguiente vista es la Vista Final, se llama a la función que actualiza el Hito
        if($siguiente_vista == 'view_final' && $sin_guardar == 0)
        {   
            switch ((int)$codigo_rubro) {
                
                // Solicitud de Crédito
                case 6:

                    $arrResultado = $this->mfunciones_microcreditos->ObtenerDetalleSolicitudCredito($estructura_id);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                    if(isset($arrResultado[0]))
                    {
                        if((int)$arrResultado[0]['sol_codigo_rubro'] <= 0) { $error_texto .= $separador . 'Debe completar la Sección "Datos Personales" (Paso 1).'; }
                        
                        // Actividad Secundaria
                        if((int)$arrResultado[0]['sol_actividad_secundaria'] == 1)
                        {
                            if((int)$arrResultado[0]['sol_codigo_rubro_sec'] <= 0) { $error_texto .= $separador . 'Debe completar la Sección "Actividad Secundaria" (Paso 1).'; }
                        }
                        
                        if((int)$arrResultado[0]['sol_conyugue'] == 1)
                        {
                            if(str_replace(' ', '', $arrResultado[0]['sol_con_ci']) == '') { $error_texto .= $separador . 'Debe completar la Sección "Cónyuge - Datos Personales" (Paso 2).'; }
                            if((int)$arrResultado[0]['sol_monto'] <= 1) { $error_texto .= $separador . 'Debe completar la Sección "Datos del Crédito Solicitado" (Paso 3).'; }
                            if((int)$arrResultado[0]['sol_dir_localidad_ciudad'] <= 0) { $error_texto .= $separador . 'Debe completar la Sección "Dirección del Negocio/Actividad o lugar de trabajo" (Paso 4).'; }
                            
                            // Actividad Secundaria
                            if((int)$arrResultado[0]['sol_actividad_secundaria'] == 1)
                            {
                                if((int)$arrResultado[0]['sol_dir_localidad_ciudad_sec'] <= 0) { $error_texto .= $separador . 'Debe completar la Sección "' . $this->lang->line('sol_secundaria_separador') . 'Dirección Negocio/Actividad - Mínimamente debe registrar Ciudad" (Paso 4).'; }
                            }
                            
                        }
                        else
                        {
                            if((int)$arrResultado[0]['sol_monto'] <= 1) { $error_texto .= $separador . 'Debe completar la Sección "Datos del Crédito Solicitado" (Paso 2).'; }
                            if((int)$arrResultado[0]['sol_dir_localidad_ciudad'] <= 0) { $error_texto .= $separador . 'Debe completar la Sección "Dirección del Negocio/Actividad o lugar de trabajo" (Paso 3).'; }
                            
                            // Actividad Secundaria
                            if((int)$arrResultado[0]['sol_actividad_secundaria'] == 1)
                            {
                                if((int)$arrResultado[0]['sol_dir_localidad_ciudad_sec'] <= 0) { $error_texto .= $separador . 'Debe completar la Sección "' . $this->lang->line('sol_secundaria_separador') . 'Dirección del Negocio/Actividad o lugar de trabajo" (Paso 3).'; }
                            }
                        }
                    }

                    if($error_texto != '')
                    {
                        $this->mfunciones_microcreditos->Guardar_PasoActual($vista_actual, $accion_usuario, $accion_fecha, $estructura_id);
                        
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    $this->mfunciones_microcreditos->SolFinFormulario($estructura_id, $accion_usuario, $accion_fecha);
                    
                    break;

                // Normalizador/Cobrador
                case 13:
                    
                    $arrResultado = $this->mfunciones_cobranzas->DatosRegistroEmail($estructura_id);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                    if(isset($arrResultado[0]))
                    {
                        if((int)$arrResultado[0]['norm_rel_cred'] <= 0) { $error_texto .= $separador . 'Debe completar la Sección "Datos Personales" (Paso 1).'; }
                        
                        $arrDireccion = $this->mfunciones_cobranzas->checkDireccionesRegistro($estructura_id);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDireccion);
                        
                        if(!isset($arrDireccion[0])) { $error_texto .= $separador . 'Debe completar la Sección "Direcciones" (Paso 2).'; }
                        
                    }

                    if($error_texto != '')
                    {
                        $this->mfunciones_cobranzas->Guardar_PasoActual($vista_actual, $accion_usuario, $accion_fecha, $estructura_id);
                        
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                        exit();
                    }
                    
                    $this->mfunciones_cobranzas->RegFinFormulario($estructura_id, $accion_usuario, $accion_fecha);
                    
                    js_invocacion_javascript('$("div.modal-backdrop").remove(); Ajax_CargadoGeneralPagina("Norm_Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");');
                    exit();
                    
                    break;
                    
                default:
                    
                    $codigo_terceros = $this->mfunciones_generales->ObtenerCodigoTercerosProspecto($estructura_id);

                    if($codigo_terceros != 0)
                    {
                        $arrResultado = $this->mfunciones_logica->ObtenerDetalleSolicitudTerceros($codigo_terceros);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                        if(isset($arrResultado[0]))
                        {
                            if($arrResultado[0]['di_genero'] == '') { $error_texto .= $separador . 'Debe completar la Sección Datos de Identificación (Paso 1).'; }

                            if($arrResultado[0]['dir_barrio_zona_uv'] == '') { $error_texto .= $separador . 'Debe completar la Sección Dirección Domicilio (Paso 2).'; }

                            if($arrResultado[0]['ae_actividad_fie'] == '') { $error_texto .= $separador . 'Debe completar la Sección Actividad Económica (Paso 3).'; }
                        }

                        if($error_texto != '')
                        {
                            js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
                            exit();
                        }
                    }

                    $this->mfunciones_generales->GuardarHitoFormulario($estructura_id, $codigo_rubro, $accion_usuario, $accion_fecha);
                    
                    break;
            }
        }
        
        switch ((int)$codigo_rubro) {
            
            // Solicitud de Crédito
            case 6:
                
                $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_credito');
                $this->formulario_logica_credito->DefinicionValidacionFormulario();

                $data["arrCajasHTML"] = $this->formulario_logica_credito->ConstruccionCajasFormulario($lst_resultado[0]);

                $data["strValidacionJqValidate"] = $this->formulario_logica_credito->GeneraValidacionJavaScript();
                
                break;

            // Normalizador/Cobrador
            case 13:
                
                $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_cobranza');
                $this->formulario_logica_cobranza->DefinicionValidacionFormulario();

                $data["arrCajasHTML"] = $this->formulario_logica_cobranza->ConstruccionCajasFormulario($lst_resultado[0]);

                $data["strValidacionJqValidate"] = $this->formulario_logica_cobranza->GeneraValidacionJavaScript();
                
                break;
            
            default:
                
                $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
                
                $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
                
                break;
        }
        
        $data["arrRespuesta"] = $lst_resultado;
        
        // DATOS PRONCIPALES
        
        $data['estructura_id'] = $estructura_id;
        $data['codigo_rubro'] = $codigo_rubro;
        $data['vista_actual'] = $siguiente_vista;
        
            // Auxiliar de Registro
            $data['tipo_registro'] = $tipo_registro;
        
        $this->load->view('registros/' . $siguiente_vista, $data);
        
    }
    
    public function NormVisita_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        $this->load->model('mfunciones_cobranzas');
        
        // Captura de Datos

        $norm_id = (int)$this->input->post('norm_id');
        $codigo_visita = (int)$this->input->post('codigo_visita');
        
        $cv_resultado = $this->input->post('cv_resultado');
        $cv_fecha_compromiso = $this->input->post('cv_fecha_compromiso');
        $cv_observaciones = htmlspecialchars($this->input->post('cv_observaciones'));
        
        if($norm_id == 0 || $codigo_visita == 0)
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $arrRegistro = $this->mfunciones_cobranzas->VerificaNormConsolidado($norm_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRegistro);
        
        if(!isset($arrRegistro[0]))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        // Validación de campos
        $separador = '<br /> - ';
        $error_texto = '';
        
        if((int)$cv_resultado == -1) { $error_texto .= $separador . $this->lang->line('cv_resultado'); }
        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($cv_observaciones, 'string_corto', 1, 0, 280); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('cv_observaciones') . '. ' . $aux_texto; }
        
        if($cv_fecha_compromiso == '')
        {
            $error_texto .= $separador . $this->lang->line('cv_fecha_compromiso');
        }
        else
        {
            $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($cv_fecha_compromiso, 'fecha_Y_M_D', 0, 0, 20);
            if($aux_texto != '')
            {
                $error_texto .= $separador . $this->lang->line('cv_fecha_compromiso') . '. ' . $aux_texto;
            }
            else
            {
                $today_dt = new DateTime(date("Y-m-d"));
                if ($today_dt >  new DateTime($cv_fecha_compromiso)) { $error_texto .= $separador . $this->lang->line('cv_fecha_compromiso') . ' sólo se acepta la fecha actual o fechas futuras.'; }
            }
        }
        
        if($error_texto != '')
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
            exit();
        }
        
        $accion_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($arrRegistro[0]['ejecutivo_id'], 'ejecutivo');
        $accion_fecha = date('Y-m-d H:i:s');
        
        if($codigo_visita == -1)
        {
            // Si es nueva visita, validar que la regla de negocio se cumpla: Debe haber marcado el Check-In de la última visita registrada
            $check_visita = $this->mfunciones_cobranzas->checkVisitaRegistrada($norm_id, $codigo_visita, 'check_nueva_visita');
            
            if($check_visita->error)
            {
                js_error_div_javascript($check_visita->error_texto);
                exit();
            }
            
            // Nueva Visita
            $this->mfunciones_cobranzas->insert_visita_registro(
                $norm_id,
                $cv_resultado,
                $cv_fecha_compromiso,
                $cv_observaciones,
                $accion_usuario,
                $accion_fecha
                );
        }
        else
        {
            // Actualizar Visita
            $this->mfunciones_cobranzas->update_visita_registro(
                $cv_resultado,
                $cv_fecha_compromiso,
                $cv_observaciones,

                $accion_usuario,
                $accion_fecha,
                $norm_id,
                $codigo_visita
                );
        }
        
        js_invocacion_javascript('$("div.modal-backdrop").remove(); Ajax_CargadoGeneralPagina("Norm_Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $norm_id . '");');
        exit();
    }
    
    public function NormVisita_Form() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        $this->load->model('mfunciones_cobranzas');
        
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_cobranza');
        $this->formulario_logica_cobranza->DefinicionValidacionFormulario();
        
        $norm_id = (int)$this->input->post('norm_id', TRUE);
        $codigo_visita = (int)$this->input->post('codigo_visita', TRUE);
        $codigo_contador = (int)$this->input->post('codigo_contador', TRUE);
        
        if($norm_id == 0 || $codigo_visita == 0)
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        if($codigo_visita == -1)
        {
            // Nueva Visita
            // Si es nueva visita, validar que la regla de negocio se cumpla: Debe haber marcado el Check-In de la última visita registrada
            $check_visita = $this->mfunciones_cobranzas->checkVisitaRegistrada($norm_id, $codigo_visita, 'check_nueva_visita');
            
            if($check_visita->error)
            {
                js_error_div_javascript($check_visita->error_texto);
                exit();
            }
            
            $lst_resultado_visita[0] = array();
        }
        else
        {
            // Actualizar Visita
            $arrVisita = $this->mfunciones_cobranzas->getVisitasRegistro($norm_id, $codigo_visita);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrVisita);

            if (!isset($arrVisita[0]))
            {
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            }
            
            $lst_resultado_visita = $arrVisita;
        }
        
        $data["arrRespuesta"] = $lst_resultado_visita;
        
        $data["norm_id"] = $norm_id;
        $data["codigo_visita"] = $codigo_visita;
        $data["codigo_contador"] = $codigo_contador;
        
        $data["arrCajasHTML"] = $this->formulario_logica_cobranza->ConstruccionCajasFormulario($lst_resultado_visita[0]);
        $data["strValidacionJqValidate"] = $this->formulario_logica_cobranza->GeneraValidacionJavaScript();
        
        js_invocacion_javascript('MostrarPanelVisitas();');
        
        $this->load->view('registros/view_norm_visita_form', $data);
    }
    
    public function NormDir_Eliminar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        $this->load->model('mfunciones_cobranzas');
        
        // Captura de Datos

        $dir_codigo = (int)$this->input->post('dir_codigo');
        $registro_tipo = (int)$this->input->post('registro_tipo');
        $registro_codigo = (int)$this->input->post('registro_codigo');
        
        if($dir_codigo == 0 || $registro_tipo == 0 || $registro_codigo == 0)
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $arrRegistro = $this->mfunciones_cobranzas->VerificaNormConsolidado($registro_codigo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRegistro);
        
        if(!isset($arrRegistro[0]))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        // Borrar de la DB
            $this->mfunciones_cobranzas->delete_norm_direccion(
                        $dir_codigo,
                        $registro_tipo,
                        $registro_codigo
                    );
            
        js_invocacion_javascript('Cancelar_FormularioDireccion();');
    }
    
    public function NormDir_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        $this->load->model('mfunciones_cobranzas');
        
        // Captura de Datos

        $dir_contador = (int)$this->input->post('dir_contador');
        $dir_codigo = (int)$this->input->post('dir_codigo');
        $registro_tipo = (int)$this->input->post('registro_tipo');
        $registro_codigo = (int)$this->input->post('registro_codigo');
        
        if($dir_codigo == 0 || $registro_tipo == 0 || $registro_codigo == 0)
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $arrRegistro = $this->mfunciones_cobranzas->VerificaNormConsolidado($registro_codigo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRegistro);
        
        if(!isset($arrRegistro[0]))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $rd_tipo = (int)$this->input->post('rd_tipo');
        
        $rd_dir_departamento = $this->input->post('rd_dir_departamento');
        $rd_dir_provincia = $this->input->post('rd_dir_provincia');
        $rd_dir_localidad_ciudad = $this->input->post('rd_dir_localidad_ciudad');
        $rd_cod_barrio = $this->input->post('rd_cod_barrio');
        $rd_direccion = $this->input->post('rd_direccion');
        $rd_edificio = $this->input->post('rd_edificio');
        $rd_numero = $this->input->post('rd_numero');
        $rd_trabajo_lugar = (int)$this->input->post('rd_trabajo_lugar');
            $rd_trabajo_lugar_otro = $this->input->post('rd_trabajo_lugar_otro');
        $rd_trabajo_realiza = (int)$this->input->post('rd_trabajo_realiza');
            $rd_trabajo_realiza_otro = $this->input->post('rd_trabajo_realiza_otro');
        $rd_dom_tipo = (int)$this->input->post('rd_dom_tipo');
            $rd_dom_tipo_otro = $this->input->post('rd_dom_tipo_otro');
        
        $rd_referencia_texto = $this->input->post('rd_referencia_texto');

        $rd_referencia = (int)$this->input->post('rd_referencia');
        $rd_geolocalizacion = $this->input->post('rd_geolocalizacion');
        $rd_croquis = $this->input->post('croquis_base64__1');
        
        // Validación de campos
        $separador = '<br /> - ';
        $error_texto = '';
        
        
        if($rd_tipo <= 0)
        {
            $error_texto .= $separador . $this->lang->line('rd_tipo');
        }
        
        if((int)$rd_dir_localidad_ciudad <= 0)
        {
            $error_texto .= $separador . 'Mínimamente debe registrar ' . $this->lang->line('rd_dir_localidad_ciudad') . '.';
        }
        
        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($rd_direccion, 'string', 1, 0, 0); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('rd_direccion') . '. ' . $aux_texto; }
        $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($rd_edificio, 'string', 1, 0, 0); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('rd_edificio') . '. ' . $aux_texto; }
        
        
        if($rd_tipo == 1)
        {
            // Negocio
            if($rd_trabajo_lugar == 99)
            {
                $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($rd_trabajo_lugar_otro, 'string', 1, 3, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('rd_trabajo_lugar') . ' ' . $this->lang->line('rd_trabajo_lugar_otro') . '. ' . $aux_texto; }
            }
            else
            {
                $rd_trabajo_lugar_otro = '';
            }

            if($rd_trabajo_realiza == 99)
            {
                $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($rd_trabajo_realiza_otro, 'string', 1, 3, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('rd_trabajo_realiza') . ' ' . $this->lang->line('rd_trabajo_realiza_otro') . '. ' . $aux_texto; }
            }
            else
            {
                $rd_trabajo_realiza_otro = '';
            }
            
            $rd_dom_tipo = 0;
            $rd_dom_tipo_otro = '';
        }
        else
        {
            // Domicilio
            if($rd_dom_tipo == 99)
            {
                $aux_texto = ''; $aux_texto = $this->mfunciones_microcreditos->validarTxtCampo($rd_dom_tipo_otro, 'string', 1, 3, 50); if($aux_texto != '') {$error_texto .= $separador . $this->lang->line('rd_dom_tipo') . ' ' . $this->lang->line('rd_dom_tipo_otro') . '. ' . $aux_texto; }
            }
            else
            {
                $rd_dom_tipo_otro = '';
            }
            
            $rd_trabajo_lugar = 0;
            $rd_trabajo_lugar_otro = '';
            $rd_trabajo_realiza = 0;
            $rd_trabajo_realiza_otro = '';
        }
        
        if(!$this->mfunciones_microcreditos->validateRegDir($rd_dir_departamento, $rd_dir_provincia, $rd_dir_localidad_ciudad, $rd_cod_barrio))
        {
            $error_texto .= $separador . $this->lang->line('sol_direccion_error');
        }

        switch ((int)$rd_referencia) {
            case 1:
                // Geolocalización
                $rd_croquis = '';
                break;

            case 2:
                // Croquis
                $rd_geolocalizacion = '';
                
                if($rd_croquis == '')
                {
                    $error_texto .= $separador . $this->lang->line('rd_croquis');
                }
                
                break;

            default:
                // Sin selección
                $rd_croquis = '';
                $rd_geolocalizacion = '';
                break;
        }
        
        if($error_texto != '')
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
            exit();
        }
        
        $accion_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($arrRegistro[0]['ejecutivo_id'], 'ejecutivo');
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Guardar en la DB
        if($dir_codigo == -1)
        {
            // Nueva Dirección
            $this->mfunciones_cobranzas->insert_norm_direccion(
                    $rd_tipo,
                    $rd_dir_departamento,
                    $rd_dir_provincia,
                    $rd_dir_localidad_ciudad,
                    $rd_cod_barrio,
                    $rd_direccion,
                    $rd_edificio,
                    $rd_numero,
                    $rd_trabajo_lugar,
                    $rd_trabajo_lugar_otro,
                    $rd_trabajo_realiza,
                    $rd_trabajo_realiza_otro,
                    $rd_dom_tipo,
                    $rd_dom_tipo_otro,
                    $rd_referencia_texto,
                    $rd_referencia,
                    $rd_geolocalizacion,
                    $rd_croquis,
                    $accion_usuario,
                    $accion_fecha,
                    $registro_tipo,
                    $registro_codigo
                    );
        }
        else
        {
            // Actualizar Dirección
            $this->mfunciones_cobranzas->update_norm_direccion(
                    $rd_tipo,
                    $rd_dir_departamento,
                    $rd_dir_provincia,
                    $rd_dir_localidad_ciudad,
                    $rd_cod_barrio,
                    $rd_direccion,
                    $rd_edificio,
                    $rd_numero,
                    $rd_trabajo_lugar,
                    $rd_trabajo_lugar_otro,
                    $rd_trabajo_realiza,
                    $rd_trabajo_realiza_otro,
                    $rd_dom_tipo,
                    $rd_dom_tipo_otro,
                    $rd_referencia_texto,
                    $rd_referencia,
                    $rd_geolocalizacion,
                    $rd_croquis,

                    $accion_usuario,
                    $accion_fecha,
                    $dir_codigo,
                    $registro_tipo,
                    $registro_codigo
                    );
        }
        
        js_invocacion_javascript('Cancelar_FormularioDireccion();');
    }
    
    public function NormDir_Form() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        $this->load->model('mfunciones_cobranzas');
        
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_cobranza');
        $this->formulario_logica_cobranza->DefinicionValidacionFormulario();
        
        $dir_contador = (int)$this->input->post('dir_contador', TRUE);
        $dir_codigo = (int)$this->input->post('dir_codigo', TRUE);
        $registro_tipo = (int)$this->input->post('registro_tipo', TRUE);
        $registro_codigo = (int)$this->input->post('registro_codigo', TRUE);
        
        if($dir_contador == 0 || $dir_codigo == 0 || $registro_tipo == 0 || $registro_codigo == 0)
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        if($dir_codigo == -1)
        {
            // Nueva Dirección
            $lst_resultado_direcciones[0] = array();
        }
        else
        {
            // Actualizar Direción
            $arrDirecciones = $this->mfunciones_cobranzas->getDireccionesRegistro($dir_codigo, $registro_codigo, $registro_tipo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDirecciones);

            if (!isset($arrDirecciones[0]))
            {
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            }
            else
            {
                $i = 0;

                foreach ($arrDirecciones as $key => $value) {
                    $item_valor = array(
                        "rd_id" => $value["rd_id"],
                        "tipo_persona_id" => $value["tipo_persona_id"],
                        "codigo_registro" => $value["codigo_registro"],
                        "rd_tipo" => $value["rd_tipo"],
                        "rd_dir_departamento" => $value["rd_dir_departamento"],
                        "rd_dir_provincia" => $value["rd_dir_provincia"],
                        "rd_dir_localidad_ciudad" => $value["rd_dir_localidad_ciudad"],
                        "rd_cod_barrio" => $value["rd_cod_barrio"],
                        "rd_direccion" => $value["rd_direccion"],
                        "rd_edificio" => $value["rd_edificio"],
                        "rd_numero" => $value["rd_numero"],
                        "rd_referencia_texto" => $value["rd_referencia_texto"],
                        "rd_referencia" => $value["rd_referencia"],
                        "rd_geolocalizacion" => $value["rd_geolocalizacion"],
                        "rd_geolocalizacion_img" => $value["rd_geolocalizacion_img"],
                        "rd_croquis" => $value["rd_croquis"],
                        "rd_trabajo_lugar" => $value["rd_trabajo_lugar"],
                        "rd_trabajo_lugar_otro" => $value["rd_trabajo_lugar_otro"],
                        "rd_trabajo_realiza" => $value["rd_trabajo_realiza"],
                        "rd_trabajo_realiza_otro" => $value["rd_trabajo_realiza_otro"],
                        "rd_dom_tipo" => $value["rd_dom_tipo"],
                        "rd_dom_tipo_otro" => $value["rd_dom_tipo_otro"]
                    );
                    $lst_resultado_direcciones[$i] = $item_valor;

                    $i++;
                }
            }
        }
        
        $data["arrRespuesta"] = $lst_resultado_direcciones;
        
        $data["dir_contador"] = $dir_contador;
        $data["dir_codigo"] = $dir_codigo;
        $data["registro_tipo"] = $registro_tipo;
        $data["registro_codigo"] = $registro_codigo;
        
        $data["arrCajasHTML"] = $this->formulario_logica_cobranza->ConstruccionCajasFormulario($lst_resultado_direcciones[0]);
        $data["strValidacionJqValidate"] = $this->formulario_logica_cobranza->GeneraValidacionJavaScript();
        
        $this->load->view('registros/view_norm_direccion_form', $data);
    }
    
    public function Margen_Lista() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        $tab = $this->input->post('tab', TRUE);
        
        $producto_id = -1;
        
        $rubro = $this->input->post('rubro', TRUE);
        
        $arrResultado = $this->mfunciones_logica->ObtenerListaProductos($estructura_id, $producto_id, 1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;

            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "producto_id" => $value["producto_id"],
                    "prospecto_id" => $value["prospecto_id"],
                    "producto_nombre" => $value["producto_nombre"],
                    "producto_frecuencia_valor" => $value["producto_frecuencia"],
                    "producto_frecuencia" => $this->mfunciones_generales->GetValorCatalogo($value["producto_frecuencia"], 'frecuencia_dias'),
                    "producto_venta_cantidad" => $value["producto_venta_cantidad"],
                    "producto_venta_medida" => $value["producto_venta_medida"],
                    "producto_venta_precio" => $value["producto_venta_precio"],
                    "producto_aclaracion" => $value["producto_aclaracion"],
                    "producto_opcion" => $value["producto_opcion"],
                    "producto_venta_costo" => $this->mfunciones_generales->ValorCostoProducto($value["producto_id"], $value["producto_opcion"], $value["producto_venta_costo"], $value["producto_compra_precio"], $value["producto_unidad_venta_unidad_compra"], $value["producto_costo_medida_cantidad"])
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        }
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }
        
        $arrResultado2 = $this->mfunciones_logica->ObtenerListaProductos($estructura_id, $producto_id, 0);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

        if (isset($arrResultado2[0])) 
        {
            $i = 0;

            foreach ($arrResultado2 as $key => $value) 
            {
                $item_valor = array(
                    "producto_id" => $value["producto_id"],
                    "prospecto_id" => $value["prospecto_id"],
                    "producto_nombre" => $value["producto_nombre"],
                    "producto_venta_medida" => $value["producto_venta_medida"],
                    "producto_aclaracion" => $value["producto_aclaracion"],
                    "producto_compra_cantidad" => $value["producto_compra_cantidad"],
                    "producto_compra_medida" => $value["producto_compra_medida"],
                    "producto_compra_precio" => $value["producto_compra_precio"],
                    "producto_unidad_venta_unidad_compra" => $value["producto_unidad_venta_unidad_compra"],
                    "producto_categoria_mercaderia" => $value["producto_categoria_mercaderia"],
                    "producto_opcion" => $value["producto_opcion"],
                    "producto_seleccion" => $value["producto_seleccion"],
                    "producto_venta_costo" => $this->mfunciones_generales->ValorCostoProducto($value["producto_id"], $value["producto_opcion"], $value["producto_venta_costo"], $value["producto_compra_precio"], $value["producto_unidad_venta_unidad_compra"], $value["producto_costo_medida_cantidad"])
                );
                $lst_resultado2[$i] = $item_valor;

                $i++;
            }
        }
        else 
        {
            $lst_resultado2[0] = $arrResultado2;
        }
        
        $data["arrRespuesta"] = $lst_resultado;
        
        $data["arrRespuesta2"] = $lst_resultado2;
        
        $data["tab"] = $tab;
        
        $data["rubro"] = $rubro;
        
        $data["estructura_id"] = $estructura_id;
        
        // Se obtiene el valor del método de registro y el total del Inventario
        
        $arrMetodo = $this->mfunciones_logica->select_margenes($estructura_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrMetodo);
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($arrMetodo[0]);
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('registros/view_margen_utilidad_lista', $data);
        
    }
    
    public function Margen_Form() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $producto_id = $this->input->post('producto_id', TRUE);
        
        $rubro = $this->input->post('rubro', TRUE);
        
        $tab = $this->input->post('tab', TRUE);
        
        if($producto_id == -1)
        {
            $lst_resultado[0] = array();
            $producto_opcion = 1;
        }
        else
        {
            $arrResultado = $this->mfunciones_logica->ObtenerListaProductos($estructura_id, $producto_id, -1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "producto_id" => $value["producto_id"],
                        "prospecto_id" => $value["prospecto_id"],
                        "producto_nombre" => $value["producto_nombre"],
                        "producto_frecuencia" => $value["producto_frecuencia"],
                        "producto_venta_cantidad" => $value["producto_venta_cantidad"],
                        "producto_venta_medida" => $value["producto_venta_medida"],
                        "producto_venta_costo" => $value["producto_venta_costo"],
                        "producto_venta_precio" => $value["producto_venta_precio"],
                        "producto_aclaracion" => $value["producto_aclaracion"],
                        "producto_compra_cantidad" => $value["producto_compra_cantidad"],
                        "producto_compra_medida" => $value["producto_compra_medida"],
                        "producto_compra_precio" => $value["producto_compra_precio"],
                        "producto_unidad_venta_unidad_compra" => $value["producto_unidad_venta_unidad_compra"],
                        "producto_categoria_mercaderia" => $value["producto_categoria_mercaderia"],
                        "producto_seleccion" => $value["producto_seleccion"],
                        "producto_costo_medida_unidad" => $value["producto_costo_medida_unidad"],
                        "producto_costo_medida_cantidad" => $value["producto_costo_medida_cantidad"],
                        "producto_costo_medida_precio" => $value["producto_costo_medida_precio"],
                        "producto_opcion" => $value["producto_opcion"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
                
                $producto_opcion = $lst_resultado[0]['producto_opcion'];
            }
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
        
        }
        
        // Listado de los No Seleccionados
        $arrDetalleProductos = $this->mfunciones_logica->ObtenerListaDetalleProductos($producto_id, -1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDetalleProductos);
        
        if(isset($arrDetalleProductos[0]))
        {
            $detalle_producto = 1;
            $detalle_producto_cantidad = count($arrDetalleProductos);
        }
        else
        {
            $detalle_producto = 0;
            $detalle_producto_cantidad = 0;
        }
        
        $data["detalle_producto"] = $detalle_producto;
        $data["detalle_producto_cantidad"] = $detalle_producto_cantidad;
        
        $data["arrRespuesta"] = $lst_resultado;
        
        $data["producto_id"] = $producto_id;
        $data["producto_opcion"] = $producto_opcion;
        
        $data["estructura_id"] = $estructura_id;
        
        $data["tab"] = $tab;
        
        $data["rubro"] = $rubro;
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('registros/view_margen_utilidad_form', $data);
        
    }
    
    public function Margen_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $producto_id = $this->input->post('producto_id', TRUE);
        
        $tab = $this->input->post('tab', TRUE);
        
        if(!isset($_POST['estructura_id']) || !isset($_POST['producto_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $producto_frecuencia = $this->input->post('producto_frecuencia', TRUE);
        $producto_venta_cantidad = $this->input->post('producto_venta_cantidad', TRUE);
        $producto_venta_precio = $this->input->post('producto_venta_precio', TRUE);
        
        $producto_nombre = $this->input->post('producto_nombre', TRUE);
        $producto_venta_medida = $this->input->post('producto_venta_medida', TRUE);
        $producto_venta_costo = $this->input->post('producto_venta_costo', TRUE);
        $producto_aclaracion = $this->input->post('producto_aclaracion', TRUE);
        
        $producto_compra_cantidad = $this->input->post('producto_compra_cantidad', TRUE);
        $producto_compra_medida = $this->input->post('producto_compra_medida', TRUE);
        $producto_compra_precio = $this->input->post('producto_compra_precio', TRUE);
        $producto_unidad_venta_unidad_compra = $this->input->post('producto_unidad_venta_unidad_compra', TRUE);
        $producto_categoria_mercaderia = $this->input->post('producto_categoria_mercaderia', TRUE);
        //$producto_seleccion = $this->input->post('producto_seleccion', TRUE);
        
        $producto_opcion = $this->input->post('producto_opcion', TRUE);
        //$producto_costo_medida_unidad = $this->input->post('producto_costo_medida_unidad', TRUE);
        $producto_costo_medida_unidad = $this->input->post('producto_venta_medida', TRUE);
        $producto_costo_medida_cantidad = $this->input->post('producto_costo_medida_cantidad', TRUE);
        
        //$producto_costo_medida_precio = $this->input->post('producto_costo_medida_precio', TRUE);
        $producto_costo_medida_precio = $this->input->post('producto_venta_precio', TRUE);
        
        // Validación de campos
                    
        $separador = '<br /> - ';
        $error_texto = '';

        if($producto_nombre == '')
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios') . ' Debe registrar la Descripción del Producto');
            exit();
        }
        
        switch ($tab) {
            case "1":

                $producto_seleccion = 1;
                
                if($producto_frecuencia == '' || $producto_venta_cantidad == '' || $producto_venta_precio == '')
                {
                    js_error_div_javascript($this->lang->line('CamposObligatorios') . ' La frecuencia y todos los datos de venta debe registrarse, caso contrario coloque 0');
                    exit();
                }

                if((int)$producto_opcion <= 0)
                {
                    js_error_div_javascript($this->lang->line('CamposObligatorios') . ' Debe seleccionar la opción del Costo Unitario');
                    exit();
                }
                
                if($producto_opcion == 2)
                {
                    if($producto_costo_medida_unidad == '' || $producto_costo_medida_cantidad == '' || $producto_costo_medida_precio == '')
                    {
                        js_error_div_javascript($this->lang->line('CamposObligatorios') . ' Debe registrar los datos del Costeo');
                        exit();
                    }
                }
                elseif($producto_opcion == 3 && $producto_venta_costo == '')
                {
                    js_error_div_javascript($this->lang->line('CamposObligatorios') . ' Debe registrar el Importe personalizado');
                    exit();
                }

                break;
                
            case "2":
                
                if((int)$producto_categoria_mercaderia <= 0)
                {
                    js_error_div_javascript($this->lang->line('CamposObligatorios') . ' Debe seleccionar la Categoría Mercadería');
                    exit();
                }
                
                $producto_seleccion = 0;

                /*
                if($producto_nombre == '' || $producto_venta_medida == '' || $producto_compra_cantidad == '' || $producto_compra_medida == '' || $producto_compra_precio == '' || $producto_unidad_venta_unidad_compra == '' || $producto_categoria_mercaderia == '' || $producto_seleccion == '')
                {
                    js_error_div_javascript($this->lang->line('CamposObligatorios') . ' La descripción y todos los datos del item debe registrarse, caso contrario coloque 0');
                    exit();
                }
                */

                break;
            
            default:
                
                js_error_div_javascript($this->lang->line('CamposObligatorios') . '');
                    exit();
                
                break;
        }
        
        $nombre_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
        $fecha_actual = date('Y-m-d H:i:s');
        
            // Se guarda en DB
            $this->mfunciones_logica->Producto_Registro($estructura_id, $producto_id, $producto_nombre, $producto_venta_medida, $producto_venta_costo, $producto_aclaracion, $producto_compra_cantidad, $producto_compra_medida, $producto_compra_precio, $producto_unidad_venta_unidad_compra, $producto_categoria_mercaderia, $producto_seleccion, $producto_frecuencia, $producto_venta_cantidad, $producto_venta_precio, $producto_opcion, $producto_costo_medida_unidad, $producto_costo_medida_cantidad, $producto_costo_medida_precio, $nombre_usuario, $fecha_actual);
        
        echo "Actualizando tabla...";
            
        if($tab == "2")
        {
            $js_auxiliar = '$("#tab2").click();';
        }
        else
        {
            $js_auxiliar = '';
        }
        
        js_invocacion_javascript('Margen_Lista('. $estructura_id . ', "' . $tab . '"); Elementos_General_MostrarElementoFlotante(false);' . $js_auxiliar);
    }
    
    public function Margen_Eliminar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $producto_id = $this->input->post('producto_id', TRUE);
        
        $tab = $this->input->post('tab', TRUE);
        
        if(!isset($_POST['estructura_id']) || !isset($_POST['producto_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        // Listado de los No Seleccionados
        $arrResultado = $this->mfunciones_logica->ObtenerListaDetalleProductos($producto_id, -1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        
        if (isset($arrResultado[0])) 
        {
            foreach ($arrResultado as $key => $value) 
            {
                $this->mfunciones_logica->Costos_Eliminar($producto_id, $value['detalle_id']);
            }
        }
        
        //$nombre_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
        //$fecha_actual = date('Y-m-d H:i:s');
        
            // Se guarda en DB
            $this->mfunciones_logica->Producto_Eliminar($estructura_id, $producto_id);
        
        echo "Actualizando tabla...";
            
        if($tab == "2")
        {
            $js_auxiliar = '$("#tab2").click();';
        }
        else
        {
            $js_auxiliar = '';
        }
        
        js_invocacion_javascript('Margen_Lista('. $estructura_id . ', "' . $tab . '"); Elementos_General_MostrarElementoFlotante(false);' . $js_auxiliar);
    }
    
    public function Inventario_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        $metodo = $this->input->post('metodo', TRUE);
        $monto = $this->input->post('monto', TRUE);
        
        
        if(!isset($_POST['estructura_id']) || !isset($_POST['monto']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        if($metodo == '')
        {
            js_error_div_javascript('Debe seleccionar el método de registro del Inventario.');
            exit();
        }
        
        $nombre_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
        $fecha_actual = date('Y-m-d H:i:s');
        
            // Se guarda en DB
            $this->mfunciones_logica->update_monto_inventario(
                            $metodo,
                            $monto,
                            $nombre_usuario,
                            $fecha_actual,
                            $estructura_id
                            );
            
        echo "Actualizando monto...";
        
        //js_invocacion_javascript('Margen_Lista('. $estructura_id . ', "2"); Elementos_General_MostrarElementoFlotante(false); $("#tab2").click();');
    }
    
    // -- Margen Transporte
    
    public function MargenTransporte_Lista() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        $tab = $this->input->post('tab', TRUE);
        
        $margen_id = -1;
        
        $arrResultado = $this->mfunciones_logica->ObtenerListaMargen($estructura_id, $margen_id, 0);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;

            foreach ($arrResultado as $key => $value) 
            {
                
                if($value['margen_tipo'] == 0)
                {
                    $margen_monto_total = $value["margen_cantidad"]*$value["margen_monto_unitario"];
                }
                else
                {
                    $margen_monto_total = $value["margen_cantidad"]*$value["margen_pasajeros"]*$value["margen_monto_unitario"];
                }
                
                $item_valor = array(
                    "margen_id" => $value["margen_id"],
                    "prospecto_id" => $value["prospecto_id"],
                    "margen_nombre" => $value["margen_nombre"],
                    "margen_cantidad" => $value["margen_cantidad"],
                    "margen_unidad_medida" => $value["margen_unidad_medida"],
                    "margen_pasajeros" => $value["margen_pasajeros"],
                    "margen_monto_unitario" => $value["margen_monto_unitario"],
                    "margen_tipo" => $value["margen_tipo"],
                    "margen_monto_total" => $margen_monto_total
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        }
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }
        
        $arrResultado2 = $this->mfunciones_logica->ObtenerListaMargen($estructura_id, $margen_id, 1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

        if (isset($arrResultado2[0])) 
        {
            $i = 0;

            foreach ($arrResultado2 as $key => $value) 
            {
                
                if($value['margen_tipo'] == 0)
                {
                    $margen_monto_total = $value["margen_cantidad"]*$value["margen_monto_unitario"];
                }
                else
                {
                    $margen_monto_total = $value["margen_cantidad"]*$value["margen_pasajeros"]*$value["margen_monto_unitario"];
                }
                
                $item_valor = array(
                    "margen_id" => $value["margen_id"],
                    "prospecto_id" => $value["prospecto_id"],
                    "margen_nombre" => $value["margen_nombre"],
                    "margen_cantidad" => $value["margen_cantidad"],
                    "margen_unidad_medida" => $value["margen_unidad_medida"],
                    "margen_pasajeros" => $value["margen_pasajeros"],
                    "margen_monto_unitario" => $value["margen_monto_unitario"],
                    "margen_tipo" => $value["margen_tipo"],
                    "margen_monto_total" => $margen_monto_total
                );
                $lst_resultado2[$i] = $item_valor;

                $i++;
            }
        }
        else 
        {
            $lst_resultado2[0] = $arrResultado2;
        }
        
        $data["arrRespuesta"] = $lst_resultado;
        
        $data["arrRespuesta2"] = $lst_resultado2;
        
        $data["tab"] = $tab;
        
        $data["estructura_id"] = $estructura_id;
        
        $this->load->view('registros/view_transporte_margen_utilidad_lista', $data);
        
    }
    
    public function MargenTransporte_Form() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $margen_id = $this->input->post('margen_id', TRUE);
        
        $tab = $this->input->post('tab', TRUE);
        
        if($margen_id == -1)
        {
            $lst_resultado[0] = array();
        }
        else
        {
            $arrResultado = $this->mfunciones_logica->ObtenerListaMargen($estructura_id, $margen_id, -1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "margen_id" => $value["margen_id"],
                        "prospecto_id" => $value["prospecto_id"],
                        "margen_nombre" => $value["margen_nombre"],
                        "margen_cantidad" => $value["margen_cantidad"],
                        "margen_unidad_medida" => $value["margen_unidad_medida"],
                        "margen_pasajeros" => $value["margen_pasajeros"],
                        "margen_monto_unitario" => $value["margen_monto_unitario"],
                        "margen_tipo" => $value["margen_tipo"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
        
        }
            
        $data["arrRespuesta"] = $lst_resultado;
        
        $data["margen_id"] = $margen_id;
        
        $data["estructura_id"] = $estructura_id;
        
        $data["tab"] = $tab;
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('registros/view_transporte_margen_form', $data);
        
    }
    
    public function MargenTransporte_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $margen_id = $this->input->post('margen_id', TRUE);
        
        $tab = $this->input->post('tab', TRUE);
        
        if(!isset($_POST['estructura_id']) || !isset($_POST['margen_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $margen_nombre = $this->input->post('margen_nombre', TRUE);
        $margen_cantidad = $this->input->post('margen_cantidad', TRUE);
        $margen_unidad_medida = $this->input->post('margen_unidad_medida', TRUE);
        $margen_pasajeros = $this->input->post('margen_pasajeros', TRUE);
        $margen_monto_unitario = $this->input->post('margen_monto_unitario', TRUE);
        
        // Validación de campos
                    
        $separador = '<br /> - ';
        $error_texto = '';

        if($margen_nombre == '' || $margen_cantidad == '' || $margen_unidad_medida == '' || $margen_monto_unitario == '')
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios') . ' El item y todos los datos deben registrarse, caso contrario coloque 0');
            exit();
        }
        
        $nombre_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
        $fecha_actual = date('Y-m-d H:i:s');
        
            // Se guarda en DB
            $this->mfunciones_logica->TransporteMargen_Registro(
                    $estructura_id, 
                    $margen_id,
                    $tab,
                    $margen_nombre,
                    $margen_cantidad,
                    $margen_unidad_medida,
                    $margen_pasajeros,
                    $margen_monto_unitario,
                    $nombre_usuario, 
                    $fecha_actual);
        
        echo "Actualizando tabla...".$tab;
            
        if($tab == "1")
        {
            $js_auxiliar = '$("#tab2").click();';
        }
        else
        {
            $js_auxiliar = '';
        }
        
        js_invocacion_javascript('MargenTransporte_Lista('. $estructura_id . ', "' . $tab . '"); Elementos_General_MostrarElementoFlotante(false);' . $js_auxiliar);
    }
    
    public function MargenTransporte_Eliminar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $margen_id = $this->input->post('margen_id', TRUE);
        
        $tab = $this->input->post('tab', TRUE);
        
        if(!isset($_POST['estructura_id']) || !isset($_POST['margen_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $nombre_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
        $fecha_actual = date('Y-m-d H:i:s');
        
            // Se guarda en DB
            $this->mfunciones_logica->TransporteMargen_Eliminar($estructura_id, $margen_id);
        
        echo "Actualizando tabla...";
            
        if($tab == "1")
        {
            $js_auxiliar = '$("#tab2").click();';
        }
        else
        {
            $js_auxiliar = '';
        }
        
        js_invocacion_javascript('MargenTransporte_Lista('. $estructura_id . ', "' . $tab . '"); Elementos_General_MostrarElementoFlotante(false);' . $js_auxiliar);
    }
    
    // -- Proveedores
    
    public function Proveedor_Lista() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $proveedor_id = -1;
        
        $arrResultado = $this->mfunciones_logica->ObtenerListaProovedor($estructura_id, $proveedor_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;

            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "proveedor_id" => $value["proveedor_id"],
                    "prospecto_id" => $value["prospecto_id"],
                    "proveedor_nombre" => $value["proveedor_nombre"],
                    "proveedor_lugar_compra" => $value["proveedor_lugar_compra"],
                    "proveedor_frecuencia_dias" => $value["proveedor_frecuencia_dias"],
                    "proveedor_importe" => $value["proveedor_importe"],
                    "proveedor_fecha_ultima" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["proveedor_fecha_ultima"]),
                    "proveedor_aclaracion" => $value["proveedor_aclaracion"]
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
        
        $this->load->view('registros/view_proveedor_lista', $data);
        
    }
    
    public function Proveedor_Form() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $proveedor_id = $this->input->post('proveedor_id', TRUE);
        
        if($proveedor_id == -1)
        {
            $lst_resultado[0] = array();
        }
        else
        {
            $arrResultado = $this->mfunciones_logica->ObtenerListaProovedor($estructura_id, $proveedor_id);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "proveedor_id" => $value["proveedor_id"],
                        "prospecto_id" => $value["prospecto_id"],
                        "proveedor_nombre" => $value["proveedor_nombre"],
                        "proveedor_lugar_compra" => $value["proveedor_lugar_compra"],
                        "proveedor_frecuencia_dias" => $value["proveedor_frecuencia_dias"],
                        "proveedor_importe" => $value["proveedor_importe"],
                        "proveedor_fecha_ultima" => $value["proveedor_fecha_ultima"],
                        "proveedor_aclaracion" => $value["proveedor_aclaracion"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
        
        }
            
        $data["arrRespuesta"] = $lst_resultado;
        
        $data["proveedor_id"] = $proveedor_id;
        
        $data["estructura_id"] = $estructura_id;
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('registros/view_proveedor_form', $data);
        
    }
    
    public function Proveedor_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $proveedor_id = $this->input->post('proveedor_id', TRUE);
        
        if(!isset($_POST['estructura_id']) || !isset($_POST['proveedor_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $proveedor_nombre = $this->input->post('proveedor_nombre', TRUE);
        $proveedor_lugar_compra = $this->input->post('proveedor_lugar_compra', TRUE);
        $proveedor_frecuencia_dias = $this->input->post('proveedor_frecuencia_dias', TRUE);
        $proveedor_importe = $this->input->post('proveedor_importe', TRUE);
        $proveedor_fecha_ultima = $this->input->post('proveedor_fecha_ultima', TRUE);
        $proveedor_aclaracion = $this->input->post('proveedor_aclaracion', TRUE);
        
        // Validación de campos
                    
        $separador = '<br /> - ';
        $error_texto = '';

        if($proveedor_nombre == '' || $proveedor_lugar_compra == '' || $proveedor_frecuencia_dias == '' || $proveedor_importe == '' || $proveedor_fecha_ultima == '')
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios') . ' La descripción y frecuencia debe registrarse');
            exit();
        }
        
        $nombre_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
        $fecha_actual = date('Y-m-d H:i:s');
        
            // Se guarda en DB
            
            $this->mfunciones_logica->Proveedor_Registro($estructura_id, $proveedor_id, $proveedor_nombre, $proveedor_lugar_compra, $proveedor_frecuencia_dias, $proveedor_importe, $proveedor_fecha_ultima, $proveedor_aclaracion, $nombre_usuario, $fecha_actual);
        
        echo "Actualizando tabla...";
            
        js_invocacion_javascript('Proveedor_Lista('. $estructura_id . '); Elementos_General_MostrarElementoFlotante(false);');
    }
    
    public function Proveedor_Eliminar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $proveedor_id = $this->input->post('proveedor_id', TRUE);
        
        if(!isset($_POST['estructura_id']) || !isset($_POST['proveedor_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $nombre_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
        $fecha_actual = date('Y-m-d H:i:s');
        
            // Se guarda en DB
            $this->mfunciones_logica->Proveedor_Eliminar($estructura_id, $proveedor_id);
        
        echo "Actualizando tabla...";
            
        js_invocacion_javascript('Proveedor_Lista('. $estructura_id . '); Elementos_General_MostrarElementoFlotante(false);');
    }
    
    // -- Materia Prima
    
    public function Materia_Lista() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $materia_id = -1;
        
        // Listado de los No Seleccionados
        $arrResultado = $this->mfunciones_logica->ObtenerListaMateria($estructura_id, $materia_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "materia_id" => $value["materia_id"],
                    "prospecto_id" => $value["prospecto_id"],
                    "materia_nombre" => $value["materia_nombre"],
                    "materia_frecuencia" => $value["materia_frecuencia"],
                    "materia_unidad_compra" => $value["materia_unidad_compra"],
                    "materia_unidad_compra_cantidad" => $value["materia_unidad_compra_cantidad"],
                    "materia_unidad_uso" => $value["materia_unidad_uso"],
                    "materia_unidad_uso_cantidad" => $value["materia_unidad_uso_cantidad"],
                    "materia_unidad_proceso" => $value["materia_unidad_proceso"],
                    "materia_producto_medida" => $value["materia_producto_medida"],
                    "materia_producto_medida_cantidad" => $value["materia_producto_medida_cantidad"],
                    "materia_precio_unitario" => $value["materia_precio_unitario"],
                    "materia_ingreso_estimado" => $this->mfunciones_generales->CalculoFecuencia(($value['materia_unidad_compra_cantidad'] * $value['materia_producto_medida_cantidad'] * $value['materia_precio_unitario']), ($value['materia_unidad_proceso']!=0 ? $value['materia_unidad_uso_cantidad'] / $value['materia_unidad_proceso'] : 0), $value['materia_frecuencia'])
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
        
        $this->load->view('registros/view_materia_lista', $data);
        
    }
    
    public function Materia_Form() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $materia_id = $this->input->post('materia_id', TRUE);
        
        if($materia_id == -1)
        {
            $lst_resultado[0] = array();
        }
        else
        {
            $arrResultado = $this->mfunciones_logica->ObtenerListaMateria($estructura_id, $materia_id);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "materia_id" => $value["materia_id"],
                        "prospecto_id" => $value["prospecto_id"],
                        "materia_nombre" => $value["materia_nombre"],
                        "materia_frecuencia" => $value["materia_frecuencia"],
                        "materia_unidad_compra" => $value["materia_unidad_compra"],
                        "materia_unidad_compra_cantidad" => $value["materia_unidad_compra_cantidad"],
                        "materia_unidad_uso" => $value["materia_unidad_uso"],
                        "materia_unidad_uso_cantidad" => $value["materia_unidad_uso_cantidad"],
                        "materia_unidad_proceso" => $value["materia_unidad_proceso"],
                        "materia_producto_medida" => $value["materia_producto_medida"],
                        "materia_producto_medida_cantidad" => $value["materia_producto_medida_cantidad"],
                        "materia_precio_unitario" => $value["materia_precio_unitario"],
                        "materia_aclaracion" => $value["materia_aclaracion"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
        
        }
            
        $data["arrRespuesta"] = $lst_resultado;
        
        $data["materia_id"] = $materia_id;
        
        $data["estructura_id"] = $estructura_id;
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('registros/view_materia_form', $data);
        
    }
    
    public function Materia_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $materia_id = $this->input->post('materia_id', TRUE);
        
        if(!isset($_POST['estructura_id']) || !isset($_POST['materia_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $materia_nombre = $this->input->post('materia_nombre', TRUE);
        $materia_frecuencia = $this->input->post('materia_frecuencia', TRUE);
        $materia_unidad_compra = $this->input->post('materia_unidad_compra', TRUE);
        $materia_unidad_compra_cantidad = $this->input->post('materia_unidad_compra_cantidad', TRUE);
        $materia_unidad_uso = $this->input->post('materia_unidad_uso', TRUE);
        $materia_unidad_uso_cantidad = $this->input->post('materia_unidad_uso_cantidad', TRUE);
        $materia_unidad_proceso = $this->input->post('materia_unidad_proceso', TRUE);
        $materia_producto_medida = $this->input->post('materia_producto_medida', TRUE);
        $materia_producto_medida_cantidad = $this->input->post('materia_producto_medida_cantidad', TRUE);
        $materia_precio_unitario = $this->input->post('materia_precio_unitario', TRUE);
        $materia_aclaracion = $this->input->post('materia_aclaracion', TRUE);
        
        // Validación de campos
                    
        $separador = '<br /> - ';
        $error_texto = '';

        if($materia_nombre == '' || $materia_frecuencia == '' || $materia_unidad_compra == '' || $materia_unidad_compra_cantidad == '' || $materia_unidad_uso == '' || $materia_unidad_uso_cantidad == '' || $materia_unidad_proceso == '' || $materia_producto_medida == '' || $materia_producto_medida_cantidad == '' || $materia_precio_unitario == '')
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios') . ' La Materia Prima, frecuencia y valores deben registrarse');
            exit();
        }
        
        $nombre_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
        $fecha_actual = date('Y-m-d H:i:s');
        
            // Se guarda en DB
            
            $this->mfunciones_logica->Materia_Registro($estructura_id, $materia_id, $materia_nombre, $materia_frecuencia, $materia_unidad_compra, $materia_unidad_compra_cantidad, $materia_unidad_uso, $materia_unidad_uso_cantidad, $materia_unidad_proceso, $materia_producto_medida, $materia_producto_medida_cantidad, $materia_precio_unitario, $materia_aclaracion, $nombre_usuario, $fecha_actual);
        
        echo "Actualizando tabla...";
            
        js_invocacion_javascript('Materia_Lista('. $estructura_id . '); Elementos_General_MostrarElementoFlotante(false);');
    }
    
    public function Materia_Eliminar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $materia_id = $this->input->post('materia_id', TRUE);
        
        if(!isset($_POST['estructura_id']) || !isset($_POST['materia_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $nombre_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
        $fecha_actual = date('Y-m-d H:i:s');
        
            // Se guarda en DB
            $this->mfunciones_logica->Materia_Eliminar($estructura_id, $materia_id);
        
        echo "Actualizando tabla...";
            
        js_invocacion_javascript('Materia_Lista('. $estructura_id . '); Elementos_General_MostrarElementoFlotante(false);');
    }
    
    public function Costos_Lista() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_POST['estructura_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $codigo_detalle = -1;
        
        // Listado de los No Seleccionados
        $arrResultado = $this->mfunciones_logica->ObtenerListaDetalleProductos($estructura_id, $codigo_detalle);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) 
            {
                
                if($value['detalle_costo_medida_convertir'] == 0)
                {
                    $detalle_costo_unitario = $value["detalle_costo_unitario"];
                }
                else
                {
                    if($value["detalle_costo_unidad_medida_cantidad"] == 0)
                    {
                        $detalle_costo_unitario = 0;
                    }
                    else
                    {
                        $detalle_costo_unitario = ($value["detalle_costo_unidad_medida_cantidad"]!=0 ? $value["detalle_costo_medida_precio"]/$value["detalle_costo_unidad_medida_cantidad"] : 0);
                    }
                }
                
                $item_valor = array(
                    "detalle_id" => $value["detalle_id"],
                    "producto_id" => $value["producto_id"],
                    "detalle_descripcion" => $value["detalle_descripcion"],
                    "detalle_cantidad" => $value["detalle_cantidad"],
                    "detalle_unidad" => $value["detalle_unidad"],
                    "detalle_costo_unitario" => $detalle_costo_unitario,
                    "detalle_costo_medida_unidad" => $value["detalle_costo_medida_unidad"],
                    "detalle_costo_medida_precio" => $value["detalle_costo_medida_precio"],
                    "detalle_costo_medida_convertir" => $this->mfunciones_generales->GetValorCatalogo($value["detalle_costo_medida_convertir"], 'si_no'),
                    "detalle_costo_medida_convertir_codigo" => $value["detalle_costo_medida_convertir"],
                    "detalle_costo_unidad_medida_uso" => $value["detalle_costo_unidad_medida_uso"],
                    "detalle_costo_unidad_medida_cantidad" => $value["detalle_costo_unidad_medida_cantidad"],
                    "producto_nombre" => $value["producto_nombre"]
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        }
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }
        
        $arrProducto = $this->mfunciones_logica->ObtenerProducto($estructura_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrProducto);
        
        $data["estructura_id"] = $estructura_id;
        
        $data["producto_nombre"] = $arrProducto[0]['producto_nombre'];
        $data["producto_costo_medida_unidad"] = $arrProducto[0]['producto_costo_medida_unidad'];
        $data["producto_costo_medida_cantidad"] = $arrProducto[0]['producto_costo_medida_cantidad'];
        $data["producto_costo_medida_precio"] = $arrProducto[0]['producto_costo_medida_precio'];
        
        $data["arrRespuesta"] = $lst_resultado;
        
        $this->load->view('registros/view_costos_lista', $data);
        
    }
    
    public function Costos_Form() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $codigo_detalle = $this->input->post('codigo_detalle', TRUE);
        
        if($codigo_detalle == -1)
        {
            $lst_resultado[0] = array();
            
            $conversion_opcion = 0;
        }
        else
        {
            // Listado de los No Seleccionados
            $arrResultado = $this->mfunciones_logica->ObtenerListaDetalleProductos($estructura_id, $codigo_detalle);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "detalle_id" => $value["detalle_id"],
                        "producto_id" => $value["producto_id"],
                        "detalle_descripcion" => $value["detalle_descripcion"],
                        "detalle_cantidad" => $value["detalle_cantidad"],
                        "detalle_unidad" => $value["detalle_unidad"],
                        "detalle_costo_unitario" => $value["detalle_costo_unitario"],
                        "detalle_costo_medida_unidad" => $value["detalle_costo_medida_unidad"],
                        "detalle_costo_medida_precio" => $value["detalle_costo_medida_precio"],
                        "detalle_costo_medida_convertir" => $value["detalle_costo_medida_convertir"],
                        "detalle_costo_unidad_medida_uso" => $value["detalle_costo_unidad_medida_uso"],
                        "detalle_costo_unidad_medida_cantidad" => $value["detalle_costo_unidad_medida_cantidad"],
                        "producto_nombre" => $value["producto_nombre"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
                
                $conversion_opcion = $lst_resultado[0]['detalle_costo_medida_convertir'];
            }
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
        
        }
            
        $data["arrRespuesta"] = $lst_resultado;
        
        $data["codigo_detalle"] = $codigo_detalle;
        $data["conversion_opcion"] = $conversion_opcion;
        
        $data["estructura_id"] = $estructura_id;
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('registros/view_costos_form', $data);
    }
    
    public function Costos_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $codigo_detalle = $this->input->post('codigo_detalle', TRUE);
        
        if(!isset($_POST['estructura_id']) || !isset($_POST['codigo_detalle']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $detalle_descripcion = $this->input->post('detalle_descripcion', TRUE);
        $detalle_cantidad = $this->input->post('detalle_cantidad', TRUE);
        $detalle_unidad = $this->input->post('detalle_unidad', TRUE);
        $detalle_costo_unitario = $this->input->post('detalle_costo_unitario', TRUE);
        
        $detalle_costo_medida_convertir = $this->input->post('detalle_costo_medida_convertir', TRUE);
        
        $detalle_costo_medida_unidad = $this->input->post('detalle_costo_medida_unidad', TRUE);
        $detalle_costo_medida_precio = $this->input->post('detalle_costo_medida_precio', TRUE);
        $detalle_costo_unidad_medida_uso = $this->input->post('detalle_costo_unidad_medida_uso', TRUE);
        $detalle_costo_unidad_medida_cantidad = $this->input->post('detalle_costo_unidad_medida_cantidad', TRUE);
        
        // Validación de campos
                    
        $separador = '<br /> - ';
        $error_texto = '';

        if($detalle_descripcion == '' || $detalle_cantidad == '' || $detalle_unidad == '' || $detalle_costo_unitario == '')
        {
            $error_texto .= $separador . 'Debe registrar los datos del costo';
        }
        
        if($detalle_costo_medida_convertir == '')
        {
            $error_texto .= $separador . 'Debe seleccionar la opción de conversión';
        }
        
        if($detalle_costo_medida_convertir == 1)
        {
            if($detalle_costo_medida_convertir == '' || $detalle_costo_medida_unidad == '' || $detalle_costo_medida_precio == '' || $detalle_costo_unidad_medida_uso == '' || $detalle_costo_unidad_medida_cantidad == '')
            {
                $error_texto .= $separador . 'Debe registar los datos de la conversión';
            }
        }
        else
        {
            $detalle_costo_medida_unidad = '';
            $detalle_costo_medida_precio = 0;
            $detalle_costo_unidad_medida_uso = '';
            $detalle_costo_unidad_medida_cantidad = 0;
        }
        
        if($error_texto != '')
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
            exit();
        }
        
        $arrProducto = $this->mfunciones_logica->ObtenerProducto($estructura_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrProducto);
        
        $nombre_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($arrProducto[0]['prospecto_id'], 'prospecto');
        $fecha_actual = date('Y-m-d H:i:s');
        
            // Se guarda en DB
            
            $this->mfunciones_logica->Costos_Registro($estructura_id, $codigo_detalle, $detalle_descripcion, $detalle_cantidad, $detalle_unidad, $detalle_costo_unitario, $detalle_costo_medida_convertir, $detalle_costo_medida_unidad, $detalle_costo_medida_precio, $detalle_costo_unidad_medida_uso, $detalle_costo_unidad_medida_cantidad, $nombre_usuario, $fecha_actual);
        
        echo "Actualizando tabla...";
            
        js_invocacion_javascript('Margen_Lista('. $arrProducto[0]['prospecto_id'] . ', "' . 1 . '"); Elementos_General_MostrarElementoFlotante(false); $("#tab1").click(); $("#ResultadoDetalleProducto").html(""); $("#ResultadoDetalleProducto").hide(); $("#ResultadoMargenUtilidad").show(); DetalleProducto(' . $estructura_id . ');');
    }
    
    public function Costos_Eliminar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $codigo_detalle = $this->input->post('codigo_detalle', TRUE);
        
        if(!isset($_POST['estructura_id']) || !isset($_POST['codigo_detalle']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
            // Se guarda en DB
            $this->mfunciones_logica->Costos_Eliminar($estructura_id, $codigo_detalle);
        
        $arrProducto = $this->mfunciones_logica->ObtenerProducto($estructura_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrProducto);
        
        echo "Actualizando tabla...";
            
        js_invocacion_javascript('Margen_Lista('. $arrProducto[0]['prospecto_id'] . ', "' . 1 . '"); Elementos_General_MostrarElementoFlotante(false); $("#tab1").click(); $("#ResultadoDetalleProducto").html(""); $("#ResultadoDetalleProducto").hide(); $("#ResultadoMargenUtilidad").show(); DetalleProducto(' . $estructura_id . ');');
    }
    
    // -- Otros Ingresos
    
    public function Otros_Ingresos_Lista() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $otros_id = -1;
        
        $arrResultado = $this->mfunciones_logica->ObtenerListaOtrosIngresos($estructura_id, $otros_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;

            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "otros_id" => $value["otros_id"],
                    "prospecto_id" => $value["prospecto_id"],
                    "otros_descripcion_fuente" => $value["otros_descripcion_fuente"],
                    "otros_descripcion_respaldo" => $value["otros_descripcion_respaldo"],
                    "otros_monto" => $value["otros_monto"],
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
        
        $this->load->view('registros/view_otros_lista', $data);
        
    }
    
    public function Otros_Ingresos_Form() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $otros_id = $this->input->post('otros_id', TRUE);
        
        if($otros_id == -1)
        {
            $lst_resultado[0] = array();
        }
        else
        {
            $arrResultado = $this->mfunciones_logica->ObtenerListaOtrosIngresos($estructura_id, $otros_id);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "otros_id" => $value["otros_id"],
                        "prospecto_id" => $value["prospecto_id"],
                        "otros_descripcion_fuente" => $value["otros_descripcion_fuente"],
                        "otros_descripcion_respaldo" => $value["otros_descripcion_respaldo"],
                        "otros_monto" => $value["otros_monto"],
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
        
        }
            
        $data["arrRespuesta"] = $lst_resultado;
        
        $data["otros_id"] = $otros_id;
        
        $data["estructura_id"] = $estructura_id;
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('registros/view_otros_ingresos_form', $data);
        
    }
    
    public function Otros_Ingresos_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $otros_id = $this->input->post('otros_id', TRUE);
        
        if(!isset($_POST['estructura_id']) || !isset($_POST['otros_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $otros_descripcion_fuente = $this->input->post('otros_descripcion_fuente', TRUE);
        $otros_descripcion_respaldo = $this->input->post('otros_descripcion_respaldo', TRUE);
        $otros_monto = $this->input->post('otros_monto', TRUE);
        
        // Validación de campos
                    
        $separador = '<br /> - ';
        $error_texto = '';

        if($otros_descripcion_fuente == '' || $otros_descripcion_respaldo == '' || $otros_monto == '')
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios') . ' La descripción y monto debe registrarse');
            exit();
        }
        
        $nombre_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
        $fecha_actual = date('Y-m-d H:i:s');
        
            // Se guarda en DB
            
            $this->mfunciones_logica->Otros_Ingresos_Registro($estructura_id, $otros_id, $otros_descripcion_fuente, $otros_descripcion_respaldo, $otros_monto, $nombre_usuario, $fecha_actual);
            
        echo "Actualizando tabla...";
            
        js_invocacion_javascript('Otros_Ingresos_Lista('. $estructura_id . '); Elementos_General_MostrarElementoFlotante(false);');
    }
    
    public function Otros_Ingresos_Eliminar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $otros_id = $this->input->post('otros_id', TRUE);
        
        if(!isset($_POST['estructura_id']) || !isset($_POST['otros_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $nombre_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
        $fecha_actual = date('Y-m-d H:i:s');
        
            // Se guarda en DB
            $this->mfunciones_logica->Otros_Ingresos_Eliminar($estructura_id, $otros_id);
        
        echo "Actualizando tabla...";
            
        js_invocacion_javascript('Otros_Ingresos_Lista('. $estructura_id . '); Elementos_General_MostrarElementoFlotante(false);');
    }
    
    // -- Pasivos
    
    public function Pasivos_Lista() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $pasivos_id = -1;
        
        $arrResultado = $this->mfunciones_logica->ObtenerListaPasivos($estructura_id, $pasivos_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;

            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "pasivo_id" => $value["pasivos_id"],
                    "prospecto_id" => $value["prospecto_id"],
                    "pasivo_acreedor" => $value["pasivo_acreedor"],
                    "pasivo_tipo" => $value["pasivo_tipo"],
                    "pasivo_saldo" => $value["pasivo_saldo"],
                    "pasivo_periodo" => $value["pasivo_periodo"],
                    "pasivo_cuota_periodica" => $value["pasivo_cuota_periodica"],
                    "pasivo_cuota_mensual" => $value["pasivo_cuota_mensual"],
                    "pasivo_rfto" => $value["pasivo_rfto"],
                    "pasivo_destino" => $value["pasivo_destino"]
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
        
        $this->load->view('registros/view_pasivos_lista', $data);
        
    }
    
    public function Pasivos_Form() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $pasivos_id = $this->input->post('pasivos_id', TRUE);
        
        if($pasivos_id == -1)
        {
            $lst_resultado[0] = array();
        }
        else
        {
            $arrResultado = $this->mfunciones_logica->ObtenerListaPasivos($estructura_id, $pasivos_id);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "pasivo_id" => $value["pasivos_id"],
                        "prospecto_id" => $value["prospecto_id"],
                        "pasivo_acreedor" => $value["pasivo_acreedor"],
                        "pasivo_tipo" => $value["pasivo_tipo"],
                        "pasivo_saldo" => $value["pasivo_saldo"],
                        "pasivo_periodo" => $value["pasivo_periodo"],
                        "pasivo_cuota_periodica" => $value["pasivo_cuota_periodica"],
                        "pasivo_cuota_mensual" => $value["pasivo_cuota_mensual"],
                        "pasivo_rfto" => $value["pasivo_rfto"],
                        "pasivo_destino" => $value["pasivo_destino"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
        
        }
            
        $data["arrRespuesta"] = $lst_resultado;
        
        $data["pasivos_id"] = $pasivos_id;
        
        $data["estructura_id"] = $estructura_id;
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('registros/view_pasivos_form', $data);
        
    }
    
    public function Pasivos_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $pasivos_id = $this->input->post('pasivos_id', TRUE);
        
        if(!isset($_POST['estructura_id']) || !isset($_POST['pasivos_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $otros_descripcion_fuente = $this->input->post('otros_descripcion_fuente', TRUE);
        $otros_descripcion_respaldo = $this->input->post('otros_descripcion_respaldo', TRUE);
        $otros_monto = $this->input->post('otros_monto', TRUE);
        
        $pasivo_acreedor = $this->input->post('pasivo_acreedor', TRUE);
        $pasivo_tipo = $this->input->post('pasivo_tipo', TRUE);
        $pasivo_saldo = $this->input->post('pasivo_saldo', TRUE);
        $pasivo_periodo = $this->input->post('pasivo_periodo', TRUE);
        $pasivo_cuota_periodica = $this->input->post('pasivo_cuota_periodica', TRUE);
        $pasivo_cuota_mensual = $this->input->post('pasivo_cuota_mensual', TRUE);
        $pasivo_rfto = $this->input->post('pasivo_rfto', TRUE);
        $pasivo_destino = $this->input->post('pasivo_destino', TRUE);
        
        
        // Validación de campos
                    
        $separador = '<br /> - ';
        $error_texto = '';

        
        
        
        if($pasivo_acreedor == '' || $pasivo_tipo == '' || $pasivo_saldo == '' || $pasivo_periodo == '' || $pasivo_cuota_periodica == '' || $pasivo_cuota_mensual == '' || $pasivo_rfto == '' || $pasivo_destino == '')
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios') . ' Por favor debe registrar todos los campos o en su defecto colocar 0');
            exit();
        }
        
        $nombre_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
        $fecha_actual = date('Y-m-d H:i:s');
        
            // Se guarda en DB
            
            $this->mfunciones_logica->Pasivos_Registro($estructura_id, $pasivos_id, $pasivo_acreedor, $pasivo_tipo, $pasivo_saldo, $pasivo_periodo, $pasivo_cuota_periodica, $pasivo_cuota_mensual, $pasivo_rfto, $pasivo_destino, $nombre_usuario, $fecha_actual);
            
        echo "Actualizando tabla...";
            
        js_invocacion_javascript('Pasivos_Lista('. $estructura_id . '); Elementos_General_MostrarElementoFlotante(false);');
    }
    
    public function Pasivos_Eliminar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $pasivos_id = $this->input->post('pasivos_id', TRUE);
        
        if(!isset($_POST['estructura_id']) || !isset($_POST['pasivos_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $nombre_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
        $fecha_actual = date('Y-m-d H:i:s');
        
            // Se guarda en DB
            $this->mfunciones_logica->Pasivos_Eliminar($estructura_id, $pasivos_id);
        
        echo "Actualizando tabla...";
            
        js_invocacion_javascript('Pasivos_Lista('. $estructura_id . '); Elementos_General_MostrarElementoFlotante(false);');
    }
    
    public function PoblarListaCatalogo() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_POST['parent_codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        // Se captura el valor
        $parent_codigo = preg_replace('/[^a-zA-Z0-9_ .]/s', '', $this->input->post('parent_codigo', TRUE));
        $tipo = preg_replace('/[^a-zA-Z0-9_ .]/s', '', $this->input->post('tipo', TRUE));
        $parent_tipo = preg_replace('/[^a-zA-Z0-9_ .]/s', '', $this->input->post('parent_tipo', TRUE));
        
        if($parent_codigo == -1)
        {
            $parent_codigo = 0;
        }
        
        // Cargar el catálogo para establecer registros hijos
        $arrResultado1 = $this->mfunciones_logica->ObtenerCatalogo($tipo, $parent_codigo, $parent_tipo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado1 as $key => $value) 
            {
                $item_valor = array(
                    "lista_codigo" => $value["catalogo_codigo"],
                    "lista_valor" => ucwords(mb_strtolower($value["catalogo_descripcion"]))
                );
                $lst_resultado1[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado1[0] = array(
                "lista_codigo" => -1,
                "lista_valor" => 'No se encontró dependencias'
            );
        }
        
        echo json_encode($lst_resultado1);
    }
    
    public function SolCredLinkForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        
        if(!isset($_POST['estructura_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        // Se captura el valor
        $estructura_id = preg_replace('/[^a-zA-Z0-9_ .]/s', '', $this->input->post('estructura_id', TRUE));
        $codigo_ejecutivo = preg_replace('/[^a-zA-Z0-9_ .]/s', '', $this->input->post('codigo_ejecutivo', TRUE));
        $tipo_registro = preg_replace('/[^a-zA-Z0-9_ .]/s', '', $this->input->post('tipo_registro', TRUE));
        
        $arrResultado = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($estructura_id);
        if (!isset($arrResultado[0])) 
        {
            $resultado = array();
            echo json_encode($resultado);
            return;
        }
        
        $url_armado = $this->mfunciones_generales->setEnlaceRegistro($estructura_id, $codigo_ejecutivo, $tipo_registro);
        
        $resultado[0] = array(
            "armado" => $url_armado
        );

        echo json_encode($resultado);
    }

    // -- Verificar el número de operación
    public function JdaJsonOperacionMethod($customerDocumentNumber,$creditOperation){
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');

        /**
         * Conseguimos las configuraciones general
         */
        $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
        $arrConf = $arrConf[0];

        $api_credit = array();
        $api_credit["conf_credit_nro_uri"] = $arrConf["conf_credit_nro_uri"];

        /**
         * Variables recibidas para realizar las operaciones
         */
        $id = $this->input->get('id', TRUE);
        //$customerDocumentNumber = $this->input->get('customerDocumentNumber', TRUE);
        //$creditOperation = $this->input->get('creditOperation', TRUE);

        if($customerDocumentNumber!="" && $creditOperation!=""){
            $respapi = array();
            $res = array();
            /**
             * recuperar datos del usuario en curso, y activar la sessión
             */
            if (!isset($_SESSION)) {
                session_start();
            }

            $sql = "SELECT CONCAT(usuario_nombres, ' ', usuario_app, ' ', usuario_apm) AS nombre_completo,usuario_email, usuario_id, usuario_user FROM usuarios WHERE usuario_id = ? ";
            $consulta = $this->db->query($sql, array($_SESSION["session_informacion"]["codigo"]));
            $listaResultados = $consulta->result_array();

            $id = $listaResultados[0]["usuario_email"];
            $end_point = $api_credit["conf_credit_nro_uri"];
            $parametros = array(
                "creditOperation" => $creditOperation,
                "customerDocumentNumber" => $customerDocumentNumber,
                "id" => $id
            );
            /*
            $parametros=array();
            $parametros["creditOperation"] = "10008585117";
            $parametros["customerDocumentNumber"] = "6706213";
            $parametros["id"] = "6074862LP";
            */
            $accion_fecha = date('Y-m-d H:i:s');
            $request_get = 0;
            $generate_token = 0;
            $conf_f_cobis_header='';

            $resultado_soa_fie = $this->mfunciones_generales->Cliente_SOA_FIE_COBIS(
                ''
                , ''
                , $end_point
                , $parametros
                , 'testing...'
                , $accion_fecha
                , $request_get
                , 1
                , $generate_token
                , $conf_f_cobis_header
            );
            $res["ws_httpcode"]=$resultado_soa_fie->ws_httpcode;
            /**
             * Forzar el codigo, borrar para produccion
             */
            $resultado_soa_fie->ws_httpcode = 200;
            //$resultado_soa_fie->ws_httpcode = 404;

            if($resultado_soa_fie->ws_httpcode==200){
                $respuesta =  1;
                /**
                 * arreglo para pruebas
                 */


                $respapi["transactionId"] = "nostrud in";
                $respapi["result"] = array(
                    "disbursedAmount" => rand(100000,200000),

                    /**/
                    "message" => "null",
                    "typeMessage" => "null"
                    /**/
                    /*/
                    "message" => "La operación 10008585117 pertenece al cliente con CI 67062136 en la APP. En el CORE pertenece al cliente MARIA YESVI CASTRO HERNANDEZ con CI 6706213.",
                    "typeMessage" => "INFO"
                    /**/
                    /*/
                    "message" => "No existe un usuario con el Documento 60748624LP",
                    "typeMessage" => "BLOCK"
                    /**/

                );
                $respapi["timestamp"] = "1952-10-07T11:34:58.220Z";


                /**
                 * para produccion
                 */



                // comentada ------------------------
                //$respapi = $resultado_soa_fie->ws_result;
                /**
                 * Verificamos errores de datos
                 */
            }else if($resultado_soa_fie->ws_httpcode==500){
                $respuesta =  2;
            }else{
                $respuesta =  3;
            }
            //$respuesta = 3;
            $res["parametros"] = $parametros;
            switch ($respuesta){
                case 1:
                    $res["res"] = $respuesta;
                    $res["msg"] = "ok";
                    $res["respapi"]=$respapi;
                    break;
                case 2:
                    $res["res"] = 2;
                    $res["msg"] = "[500] - No se puede conectar al servicio API";
                    break;
                default:
                    $res["res"] = 3;
                    $res["msg"] = "[ERROR] - Ocurrio un problema :".$resultado_soa_fie->ws_httpcode;
                    break;
            }
        }else{
            $res["res"] = 1;
            $res["msg"] = "[Error] - Error desconocido";
        }
        /*
        $arr = json_encode($res);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($arr);
        */
        return $res;
    }

    public function JdaJsonOperacion(){
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');

        /**
         * Conseguimos las configuraciones general
         */
        $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
        $arrConf = $arrConf[0];

        $api_credit = array();
        $api_credit["conf_credit_nro_uri"] = $arrConf["conf_credit_nro_uri"];

        /**
         * Variables recibidas para realizar las operaciones
         */
        $id = $this->input->get('id', TRUE);
        $customerDocumentNumber = $this->input->get('customerDocumentNumber', TRUE);
        $creditOperation = $this->input->get('creditOperation', TRUE);

        if($customerDocumentNumber!="" && $creditOperation!=""){
            $respapi = array();
            $res = array();
            /**
             * recuperar datos del usuario en curso, y activar la sessión
             */
            if (!isset($_SESSION)) {
                session_start();
            }

            $sql = "SELECT CONCAT(usuario_nombres, ' ', usuario_app, ' ', usuario_apm) AS nombre_completo,usuario_email, usuario_id, usuario_user FROM usuarios WHERE usuario_id = ? ";
            $consulta = $this->db->query($sql, array($_SESSION["session_informacion"]["codigo"]));
            $listaResultados = $consulta->result_array();

            $id = $listaResultados[0]["usuario_email"];
            $end_point = $api_credit["conf_credit_nro_uri"];
            $parametros = array(
                "creditOperation" => $creditOperation,
                "customerDocumentNumber" => $customerDocumentNumber,
                "id" => $id
            );
            /*
            $parametros=array();
            $parametros["creditOperation"] = "10008585117";
            $parametros["customerDocumentNumber"] = "6706213";
            $parametros["id"] = "6074862LP";
            */
            $accion_fecha = date('Y-m-d H:i:s');
            $request_get = 0;
            $generate_token = 0;
            $conf_f_cobis_header='';

            $resultado_soa_fie = $this->mfunciones_generales->Cliente_SOA_FIE_COBIS(
                ''
                , ''
                , $end_point
                , $parametros
                , 'testing...'
                , $accion_fecha
                , $request_get
                , 1
                , $generate_token
                , $conf_f_cobis_header
            );
            $res["ws_httpcode"]=$resultado_soa_fie->ws_httpcode;
            /**
             * Forzar el codigo, borrar para produccion
             */
            $resultado_soa_fie->ws_httpcode = 200;
            //$resultado_soa_fie->ws_httpcode = 404;

            if($resultado_soa_fie->ws_httpcode==200){
                $respuesta =  1;
                /**
                 * arreglo para pruebas
                 */

                
                $respapi["transactionId"] = "nostrud in";
                $respapi["result"] = array(
                    "disbursedAmount" => rand(100000,200000),
                  
                    /**/
                    "message" => "null",
                    "typeMessage" => "null"
                    /**/
                    /*/
                    "message" => "La operación 10008585117 pertenece al cliente con CI 67062136 en la APP. En el CORE pertenece al cliente MARIA YESVI CASTRO HERNANDEZ con CI 6706213.",
                    "typeMessage" => "INFO"
                    /**/
                    /*/
                    "message" => "No existe un usuario con el Documento 60748624LP",
                    "typeMessage" => "BLOCK"
                    /**/
                
                );
                $respapi["timestamp"] = "1952-10-07T11:34:58.220Z";
                

                /**
                 * para produccion
                 */



                    // comentada ------------------------
                //$respapi = $resultado_soa_fie->ws_result;
                /**
                 * Verificamos errores de datos
                 */
            }else if($resultado_soa_fie->ws_httpcode==500){
                $respuesta =  2;
            }else{
                $respuesta =  3;
            }
            //$respuesta = 3;
            $res["parametros"] = $parametros;
            switch ($respuesta){
                case 1:
                    $res["res"] = $respuesta;
                    $res["msg"] = "ok";
                    $res["respapi"]=$respapi;
                    break;
                case 2:
                    $res["res"] = 2;
                    $res["msg"] = "[500] - No se puede conectar al servicio API";
                    break;
                default:
                    $res["res"] = 3;
                    $res["msg"] = "[ERROR] - Ocurrio un problema :".$resultado_soa_fie->ws_httpcode;
                    break;
            }
        }else{
            $res["res"] = 1;
            $res["msg"] = "[Error] - Error desconocido";
        }
        $arr = json_encode($res);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($arr);
    }
}
