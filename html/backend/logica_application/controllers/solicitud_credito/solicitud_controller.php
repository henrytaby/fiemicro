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
class Solicitud_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 49;

    function __construct() {
        parent::__construct();
    }
    /**
     * carga la vista para el formulario de login
     * @brief CARGAR LOGIN
     * @author JRAD
     * @date Mar 21, 2014     
     */
    
    public function Bandeja_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        
        $estado = 0;
        
        if(isset($_POST['estado']))
        {
            $estado = $this->input->post('estado', TRUE);
        }
        
        $codigo_etapa = 0;

        $tiempo_etapa = 0;
        
        if($estado == 0)
        {
            $codigo_etapa = 18; $fila_fecha = 'sol_fecha';
            $aux_fecha_fin = date("Y-m-d H:i:s");
            $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa($codigo_etapa);
            $tiempo_etapa = $arrEtapa[0]['etapa_tiempo'];
        }
        
        if($estado == 1)
        {
            $codigo_etapa = 19; $fila_fecha = 'sol_asignado_fecha';
            $aux_fecha_fin = date("Y-m-d H:i:s");
            $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa($codigo_etapa);
            $tiempo_etapa = $arrEtapa[0]['etapa_tiempo'];
        }
        
        if($estado == 3) { $codigo_etapa = 21; $fila_fecha = 'sol_rechazado_fecha'; }
        
        $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        $data['nombre_region'] = $lista_region->region_nombres_texto;
        
        $arrResultado = $this->mfunciones_microcreditos->ObtenerBandejaSolicitudCredito(-1, $estado, $lista_region->region_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        $horas_laborales = 0;
        $resultado_bandera = 0;
        
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
            
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                
                if($estado == 0 || $estado == 1)
                {
                    $tiempo = $this->mfunciones_generales->ObtenerHorasLaboral($value[$fila_fecha], $aux_fecha_fin, $tiempo_etapa);
                    
                    $horas_laborales = $tiempo->horas_laborales;
                    $resultado_bandera = $tiempo->resultado_bandera;
                    
                    $tiempo_calculado =  ($tiempo_etapa==0 ? 0 : 100 - round(($horas_laborales*100)/$tiempo_etapa));
                }
                
                $item_valor = array(
                    
                    "codigo_agencia_fie" => $value["codigo_agencia_fie"],
                    "terceros_columna1" => $this->mfunciones_generales->ObtenerNombreRegionCodigo($value["codigo_agencia_fie"]),
                    "agente_codigo" => $value["agente_codigo"],
                    "import_agente" => $value["agente_nombre"],
                    "tiempo_horas" => $horas_laborales,
                    "tiempo_texto" => $resultado_bandera,
                    "tiempo_etapa" => $tiempo_calculado,
                    "sol_id" => $value["sol_id"],
                    "sol_estado_codigo" => $value["sol_estado"],
                    "sol_estado" => $this->mfunciones_microcreditos->GetValorCatalogo($value["sol_estado"], 'solicitud_estado'),
                    "sol_asistencia" => $this->mfunciones_generales->GetValorCatalogo($value["sol_asistencia"], 'tercero_asistencia'),
                    "sol_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value[$fila_fecha]),
                    "sol_ci" => $value["sol_ci"] . ' ' . $value["sol_complemento"] . ' ' . ((int)$value['sol_extension']==-1 ? '' : $this->mfunciones_generales->GetValorCatalogoDB($value['sol_extension'], 'cI_lugar_emisionoextension')),
                    "sol_nombre_completo" => $value["sol_primer_nombre"] . ' ' . $value["sol_segundo_nombre"] . ' ' . $value["sol_primer_apellido"] . ' ' . $value["sol_segundo_apellido"],
                    "sol_monto" => $this->mfunciones_microcreditos->GetValorCatalogo($value["sol_moneda"], 'sol_moneda') . ' ' . number_format($value["sol_monto"], 2, ',', '.'),
                    "sol_detalle" => $value["sol_detalle"],
                    "terceros_columna5" => $value["sol_correo"] . '<br />' . $value["sol_cel"]
                );
                $lst_resultado[$i] = $item_valor;
                
                $i++;
            }
            
            if($estado == 0 || $estado == 1)
            {
                $arrResumen = $this->mfunciones_generales->TiempoEtapaResumen($lst_resultado);
                $data["arrResumen"] = $arrResumen;
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
        
        $_SESSION['direccion_bandeja_actual'] = 'SolWeb/Ver';
        
        $_SESSION['aux_flag_reporte_return'] = 0; // <-- Se indica que no se está en reportes
        
        $this->load->view('solicitud_credito/view_bandeja_ver', $data);
        
    }
    
    public function SolicitudRechazar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
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

            $arrResultado = $this->mfunciones_microcreditos->ObtenerDetalleSolicitudCredito($codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "sol_id" => $value["sol_id"],
                        "sol_estado" =>  $this->mfunciones_microcreditos->GetValorCatalogo($value["sol_estado"], 'solicitud_estado'),
                        "terceros_columna1" => $this->mfunciones_generales->ObtenerNombreRegionCodigo($value["codigo_agencia_fie"]),
                        "sol_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value['sol_fecha']),
                        "sol_ci" => $value["sol_ci"] . ' ' . $value["sol_complemento"] . ' ' . $this->mfunciones_generales->GetValorCatalogoDB($value['sol_extension'], 'cI_lugar_emisionoextension'),
                        "sol_nombre_completo" => $value["sol_primer_nombre"] . ' ' . $value["sol_segundo_nombre"] . ' ' . $value["sol_primer_apellido"] . ' ' . $value["sol_segundo_apellido"],
                        "sol_monto" => $this->mfunciones_microcreditos->GetValorCatalogo($value["sol_moneda"], 'sol_moneda') . ' ' . number_format($value["sol_monto"], 2, ',', '.'),
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

            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
            
            $this->load->view('solicitud_credito/view_solicitud_rechazar', $data);
        }
    }
    
    public function SolicitudRechazar_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        
        // Se capturan los datos
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $solicitud_observacion = $this->input->post('solicitud_observacion', TRUE);
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $codigo_usuario = $_SESSION["session_informacion"]["codigo"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if((int)$estructura_id == 0 || $solicitud_observacion == "")
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        $this->mfunciones_microcreditos->RechazarSolicitudCredito($solicitud_observacion, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id);
        
        // Notificar Proceso Onboarding Solicitud Crédito     0=No Regionalizado      1=Si Regionalizado
        $this->mfunciones_microcreditos->NotificacionEtapaCredito($estructura_id, 21, 1);
        
        $this->Bandeja_Ver();        
    }
    
    public function SolicitudAsignar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
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

            $arrResultado = $this->mfunciones_microcreditos->ObtenerDetalleSolicitudCredito($codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "sol_id" => $value["sol_id"],
                        "terceros_columna1" => $this->mfunciones_generales->ObtenerNombreRegionCodigo($value["codigo_agencia_fie"]),
                        "sol_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value['sol_fecha']),
                        "sol_ci" => $value["sol_ci"] . ' ' . $value["sol_complemento"] . ' ' . $this->mfunciones_generales->GetValorCatalogoDB($value['sol_extension'], 'cI_lugar_emisionoextension'),
                        "sol_nombre_completo" => $value["sol_primer_nombre"] . ' ' . $value["sol_segundo_nombre"] . ' ' . $value["sol_primer_apellido"] . ' ' . $value["sol_segundo_apellido"],
                        "sol_monto" => $this->mfunciones_microcreditos->GetValorCatalogo($value["sol_moneda"], 'sol_moneda') . ' ' . number_format($value["sol_monto"], 2, ',', '.'),
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
            
            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
            
            $data["arrRespuesta"] = $lst_resultado;
            
            $arrOficiales = $this->mfunciones_microcreditos->ListaOficialesUsuariosRegion();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrOficiales);
            $data["arrOficiales"] = $arrOficiales;
            
            $this->load->view('solicitud_credito/view_solicitud_asignar', $data);
        }
    }
    
    public function SolicitudAsignar_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_microcreditos');
        
        // Se capturan los datos
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $ejecutivo_id= $this->input->post('catalogo_parent', TRUE);
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $codigo_usuario = $_SESSION["session_informacion"]["codigo"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if((int)$estructura_id == 0 || $ejecutivo_id == "")
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        if((int)$ejecutivo_id == -1)
        {
            js_error_div_javascript('Debe seleccionar un Oficial de Negocio');
            exit();
        }
        
        $arrTerceros = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($estructura_id);
        
        if($arrTerceros[0]["sol_estado_codigo"] == 1)
        {
            $texto_error = 'El registro se encuentra en el estado ' . $this->mfunciones_microcreditos->GetValorCatalogo($arrTerceros[0]["sol_estado_codigo"], 'solicitud_estado') . ' por lo que no puede ser marcado como ' . $this->mfunciones_microcreditos->GetValorCatalogo(1, 'solicitud_estado') . ', por favor verifique el codigo.';
            js_error_div_javascript($texto_error);
            exit();
        }
        
        $arrEjecutivo = $this->mfunciones_microcreditos->getDatosEjecutivo($ejecutivo_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEjecutivo);
        if (!isset($arrEjecutivo[0]))
        {
            js_error_div_javascript('El ' . $this->lang->line('import_agente') . ' seleccionado no es válido, por favor vuelva a intentar.');
            exit();
        }
        
        // Se crea la carpeta de la solicitud
        
        $path = RUTA_SOLCREDITOS . 'sol_' . $estructura_id;

        if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
        {
                mkdir($path, 0755, TRUE);
        }
        
            // Se crea el archivo html para evitar ataques de directorio
            $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
            write_file($path . '/index.html', $cuerpo_html);
        
        $this->mfunciones_microcreditos->AsignarSolicitudCredito($ejecutivo_id, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id);
        
        // Actualizar registro con la última data actualizada
        $arrTerceros = $this->mfunciones_microcreditos->DatosSolicitudCreditoEmail($estructura_id);
        
        // Notificar al Oficial de Negocio
        $this->mfunciones_generales->EnviarCorreo('notificar_instancia_credito', $arrEjecutivo[0]['usuario_email'], $arrEjecutivo[0]['usuario_nombres'] . ' ' . $arrEjecutivo[0]['usuario_app'] . ' ' . $arrEjecutivo[0]['usuario_apm'], $arrTerceros);
        
            /* Se Envía a la APP la Notificación por Push   tipo_notificacion, $tipo_visita, $codigo_visita */
            $this->mfunciones_generales->EnviarNotificacionPush(1, 3, $arrTerceros[0]['agente_codigo']);
        
        // Notificar Proceso Onboarding Solicitud Crédito     0=No Regionalizado      1=Si Regionalizado
        $this->mfunciones_microcreditos->NotificacionEtapaCredito($estructura_id, 19, 1);

        js_invocacion_javascript('Ajax_CargarAccion_BandejaSolicitudCred(0); Elementos_General_MostrarElementoFlotante(false);');
    }
    
}
?>