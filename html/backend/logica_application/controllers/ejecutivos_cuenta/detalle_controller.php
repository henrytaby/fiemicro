<?php
/**
 * @file 
 * Codigo que implementa el controlador para ga gestión del catálogo del sistema
 * @brief  EJECUTIVOS DE CUENTA
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para ga gestión del catálogo del sistema
 * @brief EJECUTIVOS DE CUENTA
 * @class Conf_catalogo_controller 
 */
class Detalle_controller extends MY_Controller {

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

    public function CargarGestionLead() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('encrypt');
        
        // Se captura el valor
        $estructura_id = preg_replace('/[^a-zA-Z0-9_ .]/s', '', $this->input->post('codigo', TRUE));
        $codigo_ejecutivo = preg_replace('/[^a-zA-Z0-9_ .]/s', '', $this->input->post('codigo_ejecutivo', TRUE));
        $tipo_registro = $this->input->post('tipo_registro', TRUE);
        
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            $data['estructura_id'] = $estructura_id;
            $data['codigo_ejecutivo'] = $codigo_ejecutivo;
            $data['tipo_registro'] = $tipo_registro;
            $this->load->view('ejecutivos_cuenta/view_gestion_lead', $data);
        }
    }
    
    public function EjecutivoHorario_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se captura el valor
        $estructura_id = $this->input->post('codigo', TRUE);
        
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            if(!$this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_CALENDARIO_EJECUTIVO))
            {
                js_error_div_javascript($this->lang->line('NoAutorizadoPerfil'));
                exit();
            }
            else 
            {
                $arrResultado = $this->mfunciones_logica->ObtenerEjecutivo($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    $data['usuario_nombre'] = $arrResultado[0]['usuario_nombre'];
                    $data['estructura_id'] = $estructura_id;

                    $this->load->view('ejecutivos_cuenta/view_horario_lectura_ver', $data);
                }
                else
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }
            }
        }
    }
    
    public function EjecutivoHorario_Horario() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se captura el valor
        $estructura_id = $this->input->get('estructura_id', TRUE);
        
        if (!isset($estructura_id)) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            if(!$this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_CALENDARIO_EJECUTIVO))
            {
                js_error_div_javascript($this->lang->line('NoAutorizadoPerfil'));
                exit();
            }
            else 
            {
                $arrResultado = $this->mfunciones_logica->HorarioVisitasEjecutivo($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0]))
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) {

                        $evento_color = '#f58220';
                        if($value["cal_tipo_visita"] == 2)
                        {
                            $evento_color = '#009dd9';
                        }

                        $item_valor = array(
                            "ejecutivo_id" => $value["ejecutivo_id"],
                            "cal_id" => $value["cal_id"],
                            "cal_tipo_visita" => $value["cal_tipo_visita"],
                            "cal_id_visita" => $value["cal_id_visita"],
                            "cal_visita_ini" => $value["cal_visita_ini"],
                            "cal_visita_fin" => $value["cal_visita_fin"],
                            "empresa_id" => $value["empresa_id"],
                            "empresa_categoria" => $value["empresa_categoria"],
                            "empresa_nombre" => $value["empresa_nombre"],
                            "evento_color" => $evento_color
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

                // Parámetros del Calendario

                $arrResultado3 = $this->mfunciones_logica->ObtenerDatosConf_General();
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                if (isset($arrResultado3[0])) 
                {                
                    $data["conf_calendario"] = $arrResultado3[0]['conf_horario_feriado'];
                    $data["conf_key_google"] = $arrResultado3[0]['conf_general_key_google'];
                    $data["conf_horario_laboral"] = $arrResultado3[0]['conf_horario_laboral'];
                    $data["conf_atencion_desde"] = $arrResultado3[0]['conf_atencion_desde'];
                    $data["conf_atencion_hasta"] = $arrResultado3[0]['conf_atencion_hasta'];
                    $data["conf_atencion_dias"] = $arrResultado3[0]['conf_atencion_dias'];
                }

                $this->load->view('ejecutivos_cuenta/view_horario_lectura_calendario', $data);
            }
        }
    }
    
    public function EjecutivoProspectos_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_microcreditos');
        
        $texto_noejecutivo = '';
        
        if(isset($_POST['codigo']))
        { 
            $estructura_id = $this->input->post('codigo', TRUE);
            $menu_directo = 0;
            
            $_SESSION['direccion_bandeja_actual'] = 'Ajax_CargarAccion_Prospecto(' . $estructura_id . ');';
            
            $_SESSION['flag_bandeja_agente'] = 0;
        }
        else
        {
            $arrAgente = $this->mfunciones_logica->ObtenerCodigoEjecutivoUsuario($_SESSION["session_informacion"]["login"]);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrAgente);
            
            if (isset($arrAgente[0])) 
            {
                $estructura_id = $arrAgente[0]['ejecutivo_id'];
            }
            else
            {
                $estructura_id = -2;
                $texto_noejecutivo = ' (No tienes el Perfil para la App Móvil)';
            }
            
            $menu_directo = 1;
            
            $_SESSION['direccion_bandeja_actual'] = "Ajax_CargarOpcionMenu('Bandeja/Ejecutivo/Ver');";
            
            $_SESSION['flag_bandeja_agente'] = 1;
        }
        
        // Validar tipo de Perfil App
        $arrDatosEjecutivo = $this->mfunciones_microcreditos->getDatosEjecutivo($estructura_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDatosEjecutivo);
        
        if (!isset($arrDatosEjecutivo[0])) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        // Se muestra el listado de registros en base al Perfil del Ejecutivo   1=Oficial de Negocios   3=Normalizador/Cobrador
        switch ((int)$arrDatosEjecutivo[0]['perfil_app_id']) {
            case 3:
                // Normalizador/Cobrador
                $this->load->model('mfunciones_cobranzas');
                
                // Casos Registrados
                $arrResultado = $this->mfunciones_cobranzas->ObtenerBandejaCobranzasEjecutivo($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                $i = 0;

                if (isset($arrResultado[0])) 
                {
                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "prospecto_id" => $value["norm_id"],
                            "camp_id" => $value["camp_id"],
                            "camp_nombre" => $value["camp_nombre"],
                            "ejecutivo_id" => $value["ejecutivo_id"],
                            "tipo_persona_id" => $value["tipo_persona_id"],
                            "prospecto_fecha_asignacion" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["norm_fecha"]),
                            "prospecto_fecha_conclusion" => $value["norm_registro_completado_fecha"],
                            "prospecto_checkin" => 1,
                            "prospecto_llamada" => 1,
                            "prospecto_consolidado" => $value["norm_consolidado"],
                            "prospecto_observado_app" => $value["norm_observado_app"],
                            "general_solicitante" => $value["norm_primer_nombre"] . ' ' . $value["norm_segundo_nombre"] . ' ' . $value["norm_primer_apellido"] . ' ' . $value["norm_segundo_apellido"],
                            "general_ci" => ((int)$value["registro_num_proceso"] <=0 ? $this->lang->line('prospecto_no_evaluacion') : $value["registro_num_proceso"]),
                            "general_ci_extension" => '',
                            "tiempo_etapa" => $this->mfunciones_generales->TiempoEtapaProspecto($value["norm_fecha"], 25),
                            "horas_laborales" => $this->mfunciones_generales->getDiasLaborales($value["norm_fecha"], date("Y-m-d H:i:s")),
                            "onboarding" => 1,
                            "cv_fecha_compromiso_check" => $this->mfunciones_cobranzas->checkFechaComPago_vencido($value["cv_fecha_compromiso"]),
                            "cv_fecha_compromiso" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y($value["cv_fecha_compromiso"]),
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }
                
                break;

            default:
                
                // Listado de los prospectos
                $arrResultado = $this->mfunciones_logica->ObtenerProspectosEjecutivo($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                $i = 0;

                if (isset($arrResultado[0])) 
                {
                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "prospecto_id" => $value["prospecto_id"],
                            "camp_id" => $value["camp_id"],
                            "camp_nombre" => $this->mfunciones_microcreditos->TextoTitulo($value["camp_nombre"]),
                            "ejecutivo_id" => $value["ejecutivo_id"],
                            "tipo_persona_id" => $value["tipo_persona_id"],
                            "prospecto_fecha_asignacion" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["prospecto_fecha_asignacion"]),
                            "prospecto_fecha_conclusion" => $value["prospecto_fecha_conclusion"],
                            "prospecto_checkin" => $value["prospecto_checkin"],
                            "prospecto_llamada" => $value["prospecto_llamada"],
                            "prospecto_consolidado" => $value["prospecto_consolidado"],
                            "prospecto_observado_app" => $value["prospecto_observado_app"],
                            "general_solicitante" => $value["general_solicitante"],
                            "general_ci" => $value["general_ci"],
                            "general_ci_extension" => $value["general_ci_extension"],
                            "tiempo_etapa" => $this->mfunciones_generales->TiempoEtapaProspecto($value["prospecto_fecha_asignacion"], 1),
                            "horas_laborales" => $this->mfunciones_generales->getDiasLaborales($value["prospecto_fecha_asignacion"], date("Y-m-d H:i:s")),
                            "onboarding" => $value["onboarding"]
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }

                // Solicitudes de Crédito
                $arrSolicitudes = $this->mfunciones_microcreditos->ObtenerBandejaSolicitudCreditoEjecutivo($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrSolicitudes);

                if (isset($arrSolicitudes[0])) 
                {
                    foreach ($arrSolicitudes as $key => $value) 
                    {
                        if($value["sol_asignado_fecha"] == '' || $value["sol_asignado_fecha"] == NULL || $value["sol_asignado_fecha"] == 'NULL')
                        {
                            $sol_asignado_fecha = date("Y-m-d H:i:s");
                        }
                        else
                        {
                            $sol_asignado_fecha = $value["sol_asignado_fecha"];
                        }

                        $item_valor = array(
                            "prospecto_id" => $value["sol_id"],
                            "camp_id" => $value["camp_id"],
                            "camp_nombre" => $this->mfunciones_microcreditos->TextoTitulo($value["camp_nombre"]),
                            "ejecutivo_id" => $value["ejecutivo_id"],
                            "tipo_persona_id" => $value["camp_id"],
                            "prospecto_fecha_asignacion" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["sol_asignado_fecha"]),
                            "prospecto_fecha_conclusion" => $value["sol_fecha_conclusion"],
                            "prospecto_checkin" => $value["sol_checkin"],
                            "prospecto_llamada" => $value["sol_llamada"],
                            "prospecto_consolidado" => $value["sol_consolidado"],
                            "prospecto_observado_app" => $value["sol_observado_app"],
                            "general_solicitante" => $value["sol_primer_nombre"] . ' ' . $value["sol_primer_apellido"] . ' ' . $value["sol_segundo_apellido"],
                            "general_ci" => $value["sol_ci"] . ' ' . $value["sol_complemento"],
                            "general_ci_extension" => ((int)$value['sol_extension']==-1 ? '' : $this->mfunciones_generales->GetValorCatalogoDB($value['sol_extension'], 'cI_lugar_emisionoextension')),
                            "tiempo_etapa" => $this->mfunciones_generales->TiempoEtapaProspecto($sol_asignado_fecha, 19),
                            "horas_laborales" => $this->mfunciones_generales->getDiasLaborales($sol_asignado_fecha, date("Y-m-d H:i:s")),
                            "onboarding" => -1
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }

                    // Proceso de verificación de Arrays para realizar el Merge
                    if (isset($arrResultado[0]))
                    {
                        if (isset($arrSolicitudes[0]))
                        {
                            $arrResultado = array_merge($arrResultado, $arrSolicitudes);
                        }
                    }
                    else
                    {
                        if (isset($arrSolicitudes[0]))
                        {
                            $arrResultado = $arrSolicitudes;
                        }
                    }
                
                break;
        }
        
        if (isset($arrResultado[0])) 
        {
            $arrResumen = $this->mfunciones_generales->TiempoEtapaResumen($lst_resultado);
            
            // Obtener el Tiempo asignado de la etapa
            $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa(1);
            $tiempo_etapa_asignado = $arrEtapa[0]['etapa_tiempo'];
        }
        else 
        {
            $lst_resultado[0] = $arrResultado;
            
            $arrResumen[0] = $arrResultado;
            $tiempo_etapa_asignado = 0;
        }
        
        $data["arrEjectutivo"] = $arrDatosEjecutivo;
        
        $data["arrRespuesta"] = $lst_resultado;
        
        $data["arrResumen"] = $arrResumen;
        $data["tiempo_etapa_asignado"] = $tiempo_etapa_asignado;
        
        $data["estructura_id"] = $estructura_id;
        $data["menu_directo"] = $menu_directo;
        $data["texto_noejecutivo"] = $texto_noejecutivo;
        
        $this->load->view('ejecutivos_cuenta/view_ejecutivo_prospectos_ver', $data);        
    }
    
    public function EjecutivoMantenimientos_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se captura el valor
        $estructura_id = $this->input->post('codigo', TRUE);
        // Listado de los prospectos
                $arrResultado = $this->mfunciones_logica->ObtenerMantenimientosEjecutivos($estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
		                
        if (isset($arrResultado[0])) 
        {
            $i = 0;
                        
            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "mantenimiento_id" => $value["mant_id"],
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "empresa_id" => $value["empresa_id"],
                    "empresa_categoria_codigo" => $value["empresa_categoria"],
                    "empresa_categoria_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["empresa_categoria"], 'empresa_categoria'),
                    "mantenimiento_fecha_asignacion" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["mant_fecha_asignacion"]),
                    "mantenimiento_estado_codigo" => $value["mant_estado"],
                    "mantenimiento_estado_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["mant_estado"], 'estado_mantenimiento'),
                    "empresa_nombre_legal" => $value["empresa_nombre_legal"],
                    "empresa_direccion" => $value["empresa_direccion"],
                    "empresa_direccion_geo" => $value["empresa_direccion_geo"],
                    "contacto" => $value["contacto"],
                    "fecha_visita_ini" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["cal_visita_ini"]),
                    "fecha_visita_fin" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["cal_visita_fin"])
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
        
        $this->load->view('ejecutivos_cuenta/view_ejecutivo_mantenimientos_ver', $data);
    }
    
    // Normalizador/Cobrador
    public function ReporteEvolucionNorm()  {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        $this->load->model('mfunciones_cobranzas');
        
        // Se captura los valores
        
        $sel_oficial = (int)$this->input->post('estructura_id', TRUE);
        
        if($sel_oficial <= 0)
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
         $arrEjecutivo = $this->mfunciones_microcreditos->getDatosEjecutivo($sel_oficial);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEjecutivo);
        if (!isset($arrEjecutivo[0]))
        {
            $data['NombreEjecutivo'] = '--';
        }
        else
        {
            $data['NombreEjecutivo'] = $arrEjecutivo[0]['usuario_nombres'] . ' ' . $arrEjecutivo[0]['usuario_app'] . ' ' . $arrEjecutivo[0]['usuario_apm'];
        }
        
        $html_body = $this->mfunciones_cobranzas->getReporteHomeApp($sel_oficial, $this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app_singular'));
        
        $html_body = str_replace('[indicaciones]', 'registre un Nuevo Caso', $html_body);
        
        $data['html_body'] = $html_body;
        
        $this->load->view('cobranza/view_dashboard_ejecutivo', $data);
        
    }
    
    public function ReporteFunnel()  {
        
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

            $arrEjecutivo = $this->mfunciones_microcreditos->getDatosEjecutivo($sel_oficial);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEjecutivo);
            if (!isset($arrEjecutivo[0]))
            {
                $data['NombreEjecutivo'] = '--';
            }
            else
            {
                $data['NombreEjecutivo'] = $arrEjecutivo[0]['usuario_nombres'] . ' ' . $arrEjecutivo[0]['usuario_app'] . ' ' . $arrEjecutivo[0]['usuario_apm'];
            }
            
            $this->load->view('dashboard/view_dashboard_ejecutivo', $data);
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
    
    // ************* FILTROS *************
    
    public function PoblarLista()  {
        
        $this->load->model('mfunciones_dashboard');
        
        // Se captura los valores
        $tipo = filter_var($this->input->post('tipo', TRUE), FILTER_SANITIZE_STRING);
        $opciones = $this->input->post('opciones', TRUE);
        $aux = filter_var($this->input->post('aux', TRUE), FILTER_SANITIZE_STRING);
        
        $aux_estado = (int)(filter_var($this->input->post('estado', TRUE), FILTER_SANITIZE_STRING));
        
        if($tipo == 'departamento')
        {
            $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
            
            $arrResultado = $this->mfunciones_dashboard->ObtenerDatosDepartamento_dashboard_generico($lista_region->region_id, $aux_estado);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;
                
                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "value" => ($value["dep"] == 'LPALTO' ? '115' : $value["dep"]),
                        "label" => ($value["dep"] == 'LPALTO' ? 'LA PAZ - EL ALTO' : $this->mfunciones_generales->GetValorCatalogoDB($value["dep"], 'dir_departamento'))
                    );
                    $lst_resultado[$i] = $item_valor;
                    
                    $i++;
                }
                
                echo json_encode($lst_resultado);
                return;
            }
            else
            {
                echo json_encode(array());
                return;
            }
        }
        
        // Registro de opciones
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($opciones);

        if (!isset($opciones[0])) 
        {
            echo json_encode(array());
            return;
        }
        
        if($tipo == 'agencia')
        {
            $flag_elalto = 0;
            
            foreach ($opciones as $key => $value) 
            {
                switch ((int)$value) {

                    case 115:
                        $flag_elalto = 1;
                        $aux_criterio = "";
                        break;

                    default:
                        $aux_criterio = "'" . $value . "'";
                        break;
                }

                $value = $aux_criterio;

                if($aux_criterio != '')
                {
                    $criterio .= $value . ',';
                }
            }

            $criterio = rtrim($criterio, ',');
        }
        
        $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        
        $lst_resultado = array();
        
        $i = 0;
        
        switch ($tipo) {
            case 'agencia':
                
                // Se valida que existan criterios para realizar la búsqueda
                if($criterio != '')
                {
                    // Listado de REGIONES (Agencias)
                    $arrResultado = $this->mfunciones_dashboard->ObtenerDatosRegional_dashboard_generico($criterio, 'dir_departamento', $lista_region->region_id, $aux, $aux_estado);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                    
                    if (isset($arrResultado[0])) 
                    {
                        foreach ($arrResultado as $key => $value) 
                        {
                            $item_valor = array(
                                "catalogo_padre" => ((int)$value["estructura_regional_ciudad"]==115 ? 'LA PAZ - EL ALTO' : $this->mfunciones_generales->GetValorCatalogoDB($value["estructura_regional_departamento"], 'dir_departamento')),
                                "catalogo_codigo" => $value["estructura_regional_id"],
                                "catalogo_descripcion" => $value["estructura_regional_nombre"] . ((int)$value["estructura_regional_estado"]==1 ? '' : ' (Cerrada)'),
                            );
                            $lst_resultado[$i] = $item_valor;

                            $i++;
                        }
                    }
                }
                
                // Se valida si el flag de ciudad El Alto está activado para realizar la búsquedas sólo de esta ciudad
                if($flag_elalto == 1)
                {
                    // Listado de REGIONES (Agencias)
                    $arrResultado = $this->mfunciones_dashboard->ObtenerDatosRegional_dashboard_generico('115', 'dir_localidad_ciudad', $lista_region->region_id, $aux, $aux_estado);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                    
                    if (isset($arrResultado[0])) 
                    {
                        foreach ($arrResultado as $key => $value) 
                        {
                            $item_valor = array(
                                "catalogo_padre" => 'LA PAZ - EL ALTO',
                                "catalogo_codigo" => $value["estructura_regional_id"],
                                "catalogo_descripcion" => $value["estructura_regional_nombre"] . ((int)$value["estructura_regional_estado"]==1 ? '' : ' (Cerrada)'),
                            );
                            $lst_resultado[$i] = $item_valor;

                            $i++;
                        }
                    }
                }

                break;

            case 'oficial':
                
                $aux_array = implode (", ", $opciones);
                
                // Listado de Oficiales de Negocio por Agencias
                $arrResultado = $this->mfunciones_dashboard->ObtenerDatosOficial_dashboard_generico($aux_array, $lista_region->region_id, $aux, $aux_estado);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "catalogo_padre" => $value["estructura_regional_nombre"],
                            "catalogo_codigo" => $value["ejecutivo_id"],
                            "catalogo_descripcion" => $value["ejecutivo_nombre"] . ((int)$value["usuario_activo"]==1 ? '' : ' (Inactivo)'),
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }
                
                break;
                
            case 'normalizador':
                
                $this->load->model("mfunciones_cobranzas");
                
                $aux_array = implode (", ", $opciones);
                
                // Listado de Oficiales de Negocio por Agencias
                $arrResultado = $this->mfunciones_cobranzas->ObtenerDatosNorm_dashboard_generico($aux_array, $lista_region->region_id, $aux, $aux_estado);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "catalogo_padre" => $value["estructura_regional_nombre"],
                            "catalogo_codigo" => $value["ejecutivo_id"],
                            "catalogo_descripcion" => $value["ejecutivo_nombre"] . ((int)$value["usuario_activo"]==1 ? '' : ' (Inactivo)'),
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }
                
                break;
            
            default:
                
                echo json_encode(array());
                return;
                
                break;
        }
        
        // Si no se encontraron valores, se devuelve vacío
        if (!isset($lst_resultado[0]))
        {
            echo json_encode(array());
            return;
        }
        
        $orden_padre = $this->mfunciones_generales->ArrGroupBy($lst_resultado, 'catalogo_padre');
        
        $j = 0;
        
        foreach ($orden_padre as $key => $orden)
        {
            $k = 0;
            $aux_opciones = array();
            foreach ($orden_padre[$key] as $key2 => $orden2)
            {
                $aux_opciones[$k] = array(
                    'label' => $orden2['catalogo_descripcion'],
                    'value' => $orden2['catalogo_codigo']
                );
                
                $k++;
            }
            
            $lista[$j] = array(
                'label' => $key,
                'options' => $aux_opciones
            );
            
            $j++;
        }
        
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($lista);
        
        echo json_encode($lista);
    }
}
?>