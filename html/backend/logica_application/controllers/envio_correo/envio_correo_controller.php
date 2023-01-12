<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador para el Envío de Correos en Segundo Plano
 * @brief CONTROLADOR DEL LOGUEO
 * @class Login_controller 
 */
class Envio_correo_controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        session_start();		
    }

    public function EnvioCorreo() {       
        
	$this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');

        // Se capturan los datos
        
        $plantilla = $this->input->post('plantilla', TRUE);
        $destinatario_correo = $this->input->post('destinatario_correo', TRUE);
        $destinatario_nombre = $this->input->post('destinatario_nombre', TRUE);
        $arrayParametros = $this->input->post('arrayParametros', TRUE);
        $arrayDocumentos = $this->input->post('arrayDocumentos', TRUE);
        $arrayConCopia = $this->input->post('arrayConCopia', TRUE);
        $arrayConCopiaOculta = $this->input->post('arrayConCopiaOculta', TRUE);
        
        if($plantilla != '' || $destinatario_correo != '' || $destinatario_nombre != '')
        {
            // 1. SE ESTABLECE TODAS LAS CONFIGURACIONES

            // Se obtiene la configuración de la Base de Datos

            $arrResultado = $this->mfunciones_logica->ObtenerDatosConf_Correo();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            $config = Array(
            'protocol' => $arrResultado[0]['conf_protocol'],
            'smtp_host' => $arrResultado[0]['conf_smtp_host'],
            'validation' => TRUE,
            'smtp_timeout' => 120,
            'smtp_port' => $arrResultado[0]['conf_smtp_port'],
            'smtp_user' => $arrResultado[0]['conf_smtp_user'],
            'smtp_pass' => $arrResultado[0]['conf_smtp_pass'],
            'mailtype'  => $arrResultado[0]['conf_mailtype'],
            'charset'   => $arrResultado[0]['conf_charset']                
            );

            $emisor_correo = $arrResultado[0]['conf_smtp_user'];
            $emisor_nombre = $this->lang->line('NombreSistemaCliente');

            $this->load->library('email', $config);
            $this->email->initialize($config);

            switch ($plantilla)
            {
                case 'notificar_instancia_norm':

                    $codigo_plantilla = 'templ-27';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    $contenido_mensaje = str_replace(
                            array('{norm_codigo_registro}', '{norm_agente}', '{norm_estado}', '{norm_fecha_registro}', '{norm_nombre_agencia}', '{norm_nombre_caso}'),
                            array($this->lang->line('norm_prefijo') . $arrayParametros[0]['norm_id'], $arrayParametros[0]['agente_nombre'], $arrayParametros[0]['norm_estado'], $arrayParametros[0]['norm_fecha'], $arrayParametros[0]['nombre_agencia'], $arrayParametros[0]['norm_nombre_completo']),
                            $contenido_mensaje
                    );
                    
                    break;
                
                case 'ws_cobis_incompleto':

                    $codigo_plantilla = 'templ-26';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    $arrTercero = $this->mfunciones_generales->DatosTercerosEmail($arrayParametros);
                    
                    if(!$arrTercero)
                    {
                        // En el caso que no se encuentre información con los parámetros indicados, se devuelve vacío            
                        return FALSE;
                        exit();
                    }
                    
                    $contenido_mensaje = str_replace(
                            array('{onboarding_codigo_registro}', '{onboarding_nombre_solicitante}', '{onboarding_email_solicitante}', '{onboarding_ci_solicitante}', '{onboarding_fecha_registro}', '{onboarding_estado_actual}', '{onboarding_numero_cuenta}', '{onboarding_nombre_agencia}', '{onboarding_tipo_cuenta}', '{f_cobis_actual_etapa}', '{f_cobis_actual_intento}', '{f_cobis_excepcion}', '{f_cobis_excepcion_motivo}', '{f_cobis_flag_rechazado}'),
                            array(PREFIJO_TERCEROS . $arrTercero[0]['terceros_id'], $arrTercero[0]['terceros_nombre'], $arrTercero[0]['terceros_email'], $arrTercero[0]['cedula_identidad'], $arrTercero[0]['terceros_fecha'], $arrTercero[0]['terceros_estado'], $arrTercero[0]['onboarding_numero_cuenta'], $arrTercero[0]['nombre_agencia'], $arrTercero[0]['tipo_cuenta'], $arrTercero[0]['f_cobis_actual_etapa_detalle'], $arrTercero[0]['f_cobis_actual_intento'], $arrTercero[0]['f_cobis_excepcion_detalle'], $arrTercero[0]['f_cobis_excepcion_motivo'], $arrTercero[0]['f_cobis_flag_rechazado_detalle']),
                            $contenido_mensaje
                    );

                    break;
                
                case 'notificar_instancia_credito':

                    $codigo_plantilla = 'templ-25';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    $contenido_mensaje = str_replace(
                            array('{sol_credito_codigo_registro}', '{sol_credito_nombre_solicitante}', '{sol_credito_email}', '{sol_credito_fecha_registro}', '{sol_credito_estado}', '{sol_credito_asistencia}', '{sol_credito_nombre_agencia}', '{sol_credito_monto_solicitado}', '{sol_credito_celular}'),
                            array('SOL_' . $arrayParametros[0]['sol_id'], $arrayParametros[0]['sol_nombre_completo'], $arrayParametros[0]['sol_correo'], $arrayParametros[0]['sol_fecha'], $arrayParametros[0]['sol_estado'], $arrayParametros[0]['sol_asistencia'], $arrayParametros[0]['nombre_agencia'], $arrayParametros[0]['sol_monto'], $arrayParametros[0]['sol_cel']),
                            $contenido_mensaje
                    );
                    
                    break;
                
                case 'notificar_solicitante_credito':

                    $codigo_plantilla = 'templ-24';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    $contenido_mensaje = str_replace(
                            array('{sol_credito_mensaje_registro}'),
                            array($arrayParametros[0]['sol_credito_mensaje_registro']),
                            $contenido_mensaje
                    );
                    
                    break;
                
                case 'terceros_cuenta_cerrada':

                    $codigo_plantilla = 'templ-22';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    $contenido_mensaje = str_replace(
                            array('{onboarding_codigo_registro}', '{onboarding_rechazo_texto}', '{onboarding_nombre_solicitante}', '{onboarding_email_solicitante}', '{onboarding_fecha_registro}', '{onboarding_estado_actual}', '{onboarding_completar_texto}', '{onboarding_numero_cuenta}', '{onboarding_nombre_agencia}', '{onboarding_tipo_cuenta}', '{notificar_cierre_causa}', '{cuenta_cerrada_causa}'),
                            array(PREFIJO_TERCEROS . $arrayParametros[0]['terceros_id'], $arrayParametros[0]['rechazado_texto'], $arrayParametros[0]['terceros_nombre'], $arrayParametros[0]['terceros_email'], $arrayParametros[0]['terceros_fecha'], $arrayParametros[0]['terceros_estado'], $arrayParametros[0]['completado_texto'], $arrayParametros[0]['onboarding_numero_cuenta'], $arrayParametros[0]['nombre_agencia'], $arrayParametros[0]['tipo_cuenta'], $arrayParametros[0]['notificar_cierre_causa'], $arrayParametros[0]['cuenta_cerrada_causa']),
                            $contenido_mensaje
                    );

                    break;
                
                case 'terceros_notificar_cierre':

                    $codigo_plantilla = 'templ-21';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    $contenido_mensaje = str_replace(
                            array('{onboarding_codigo_registro}', '{onboarding_rechazo_texto}', '{onboarding_nombre_solicitante}', '{onboarding_email_solicitante}', '{onboarding_fecha_registro}', '{onboarding_estado_actual}', '{onboarding_completar_texto}', '{onboarding_numero_cuenta}', '{onboarding_nombre_agencia}', '{onboarding_tipo_cuenta}', '{notificar_cierre_causa}', '{cuenta_cerrada_causa}'),
                            array(PREFIJO_TERCEROS . $arrayParametros[0]['terceros_id'], $arrayParametros[0]['rechazado_texto'], $arrayParametros[0]['terceros_nombre'], $arrayParametros[0]['terceros_email'], $arrayParametros[0]['terceros_fecha'], $arrayParametros[0]['terceros_estado'], $arrayParametros[0]['completado_texto'], $arrayParametros[0]['onboarding_numero_cuenta'], $arrayParametros[0]['nombre_agencia'], $arrayParametros[0]['tipo_cuenta'], $arrayParametros[0]['notificar_cierre_causa'], $arrayParametros[0]['cuenta_cerrada_causa']),
                            $contenido_mensaje
                    );

                    break;
                
                case 'notificar_alerta_prospecto':

                    // EXCEPCIÓN: Si la etapa es 16 es para la Alerta de Notificación de Cierre, debe cambiar a la plantilla de correo templ-23, caso contrario templ-20
                                    
                    if($arrayParametros[0]["etapa_verificar"] == 16)
                    {
                        $codigo_plantilla = 'templ-23';
                    }
                    else
                    {
                        $codigo_plantilla = 'templ-20';
                    }

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];
                    
                    // #############################################################
                    
                    $etapa_verificar = $arrayParametros[0]["etapa_verificar"];
                    $etapa_depende = $arrayParametros[0]["etapa_depende"];
                    $etapa_nombre = $arrayParametros[0]["etapa_nombre"];
                    $etapa_tiempo = $arrayParametros[0]["etapa_tiempo"];
                    $etapa_categoria = $arrayParametros[0]["etapa_categoria"];
                    
                    // Paso 1. Verificar la etapa

                    $arrUsuariosNotificar = $this->mfunciones_generales->getListadoUsuariosEtapaEnvio($etapa_verificar);

                    if (isset($arrUsuariosNotificar[0])) 
                    {   
                        foreach ($arrUsuariosNotificar as $key => $value_usuario) 
                        {
                            $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                            
                            $arrResultado = array();
                            $lst_resultado = array();

                            $resultado_vista = "";
                            
                            $destinatario_nombre = "";
                            $destinatario_correo = "";

                            // Paso 2. Obtener el listado de prospectos

                            /***** REGIONALIZACIÓN INICIO ******/                
                            // Se captura el filtro Regionalizado
                            $regionalizado = $this->mfunciones_generales->getProspectosRegion($value_usuario['usuario_id']);
                            if($regionalizado->error)
                            {
                                js_error_div_javascript($this->lang->line('TablaNoResultados'));
                                exit();
                            }

                            // Para el flujo de Prospectos

                            switch ($etapa_verificar)
                            {
                                // Etapas Auxiliares

                                case 1:
                                case 10:
                                case 12:

                                    $filtro = 'p.prospecto_id = -1';

                                    break;

                                // Bandeja Agencia que no considere los registros que hayan sido marcados como Notificar Cierre
                                case 11:

                                    $filtro = 'p.prospecto_etapa=11 AND t.notificar_cierre=0';

                                    break;

                                case 13:

                                    $filtro = 'p.prospecto_etapa IN (7, 8, 9, 11)';

                                    break;

                                // Alertas de Notificación de Cierre que superen los 15 días
                                case 16:

                                    $filtro = 'p.prospecto_etapa=14 AND t.notificar_cierre=1';

                                    break;

                                default:

                                    $filtro = 'p.prospecto_etapa =' . $etapa_verificar;

                                    break;
                            }

                            $filtro .= ' AND p.onboarding=1 AND t.codigo_agencia_fie IN(' . $regionalizado->region_id . ')';

                                // Se establece el filtro inicial en base a la etapa


                            $arrResultado = $this->mfunciones_logica->ListadoBandejasOnboarding($filtro);
                            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                            if (isset($arrResultado[0])) 
                            {
                                $i = 0;

                                foreach ($arrResultado as $key => $value) 
                                {
                                    if($etapa_verificar == 13)
                                    {   //Sólo si la etapa corresponde a la notificación se llama a otra función para el cálculo de tiempo
                                        $tiempo_etapa = $this->mfunciones_generales->TiempoEtapaProspectoAlerta($value["prospecto_fecha_asignacion"]);
                                        $suma_horas = $this->mfunciones_generales->getDiasLaborales($value["prospecto_fecha_asignacion"], date("Y-m-d H:i:s"));
                                        $fecha_asignacion = $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["prospecto_fecha_asignacion"]);
                                    }
                                    else
                                    {
                                        $tiempo_etapa = $this->mfunciones_generales->TiempoEtapaProspecto($value["fecha_derivada_etapa"], $etapa_verificar);
                                        $suma_horas = $this->mfunciones_generales->getDiasLaborales($value["fecha_derivada_etapa"], date("Y-m-d H:i:s"));
                                        $fecha_asignacion = $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["fecha_derivada_etapa"]);
                                    }

                                    if($etapa_verificar == 16)
                                    {   // Esta etapa es para los atrasados despues de 15 dias de Notificar Rechazo

                                        $suma_horas = $this->mfunciones_generales->getDiasCalendario($value["notificar_cierre_fecha"], date("Y-m-d H:i:s"));
                                        // Para el tiempo_etapa se obtiene la cantidad de horas de la etapa y se convierte en días
                                        $tiempo_etapa = ($etapa_tiempo/24) - $suma_horas;
                                        $fecha_asignacion = $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["notificar_cierre_fecha"]);
                                    }

                                    if($tiempo_etapa<0)
                                    {
                                        $item_valor = array(
                                            "prospecto_id" => $value["prospecto_id"],

                                            "tiempo_etapa" => $tiempo_etapa,
                                            "prospecto_fecha_asignacion" => $fecha_asignacion,
                                            "suma_horas" => $suma_horas,
                                            "etapa_nombre" => $value["etapa_nombre"],

                                            "tipo_persona_id_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["tipo_persona_id"], 'tipo_persona'),

                                            "ejecutivo_id" => $value["ejecutivo_id"],

                                            "codigo_agencia_fie" => $this->mfunciones_generales->ObtenerNombreRegionCodigo($value["codigo_agencia_fie"]),
                                            "terceros_id" => $value["terceros_id"],
                                            "tercero_asistencia" => $this->mfunciones_generales->GetValorCatalogo($value["tercero_asistencia"], 'tercero_asistencia'),
                                            "tipo_cuenta" => $this->mfunciones_generales->GetValorCatalogoDB($value['tipo_cuenta'], 'tipo_cuenta'),
                                            "dir_localidad_ciudad" => $this->mfunciones_generales->GetValorCatalogoDB($value['dir_localidad_ciudad'], 'dir_localidad_ciudad'),
                                            "nombre_completo" => $value["di_primernombre"] . ' ' . $value["di_primerapellido"] . ' ' . $value["di_segundoapellido"],
                                            //"carnet_identidad" => $value["cI_numeroraiz"] . ' ' . $value["cI_complemento"] . ' ' . $this->mfunciones_generales->GetValorCatalogoDB($value['cI_lugar_emisionoextension'], 'cI_lugar_emisionoextension'),
                                            "carnet_identidad" => $value["cI_numeroraiz"] . ' ' . $value["cI_complemento"] . ' ' . ($value['cI_lugar_emisionoextension']=='-1' ? '' : $value['cI_lugar_emisionoextension']),
                                            "contacto" => $value["direccion_email"] . '<br />' . $value["dir_notelefonico"],

                                            "tipo_persona_id_codigo" => $value["tipo_persona_id"],
                                            "tipo_persona_id_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["tipo_persona_id"], 'tipo_persona'),
                                            "terceros_estado" => $value["terceros_estado"],

                                            'envio' => $this->mfunciones_generales->GetValorCatalogo($value['envio'], 'tercero_envio'),

                                        );
                                        $lst_resultado[$i] = $item_valor;

                                        $i++;
                                    }
                                }

                                if (isset($lst_resultado[0]))
                                {
                                    $arrResumen = $this->mfunciones_generales->TiempoEtapaResumen($lst_resultado);

                                    if($etapa_verificar == 13)
                                    {
                                        $tiempo_etapa_asignado = $this->mfunciones_generales->GetTotalHorasFlujo(5);
                                    }
                                    else
                                    {
                                        $tiempo_etapa_asignado = $etapa_tiempo;
                                    }

                                    $data["arrRespuesta"] = $lst_resultado;
                                    $data["arrResumen"] = $arrResumen;
                                    $data["tiempo_etapa_asignado"] = $tiempo_etapa_asignado;

                                    $data["etapa_verificar"] = $etapa_verificar;
                                    $data["etapa_nombre"] = $etapa_nombre;
                                    $data["etapa_categoria"] = $etapa_categoria;

                                    // Paso 3. Guardar el resultado de la vista en una variable

                                    $resultado_vista = $this->load->view('bandeja_seg_backoffice/view_bandeja_plain', $data, true);

                                    /* 
                                    // PARA TEST
                                    echo $resultado_vista;
                                    */

                                    $destinatario_nombre = $value_usuario['usuario_nombres'] . ' ' . $value_usuario['usuario_app'] . ' ' . $value_usuario['usuario_apm'];
                                    $destinatario_correo = $value_usuario['usuario_email'];
                                    
                                    // SE PROCEDE CON EL ENVÍO DE CORREO ELECTRÓNICO

                                    // EXCEPCIONALMENTE SE HACE TODO EL PROCESO DE ENVIO PARA ESTA CASO

                                    $contenido_mensaje = str_replace(
                                            array('{tabla_reporte_atrasos_onboarding}', '{tabla_reporte_notificar_cierre}'),
                                            array($resultado_vista, $resultado_vista),
                                            $contenido_mensaje
                                    );

                                    $contenido_mensaje = str_replace(
                                            array('nbsp;', '{nombre_sistema}', '{nombre_corto}', '{destinatario_nombre}', '{destinatario_correo}', '{titulo_correo}', '{emisor_nombre}', '{fecha_actual}'),
                                            array(' ', $this->lang->line('NombreSistema'), $this->lang->line('NombreSistema_corto'), $destinatario_nombre, $destinatario_correo, $titulo_correo, $emisor_nombre, date("d/m/Y")),
                                            $contenido_mensaje
                                    );

                                    $body = $contenido_mensaje;

                                    // 2. SE PROCESA CON LA LIBRERÍA DE ENVÍO

                                    $this->email->set_crlf( "\r\n" );
                                    $this->email->set_newline("\r\n");    
                                    $this->email->from($emisor_correo, $emisor_nombre);        
                                    $this->email->to($destinatario_correo);
                                    $this->email->subject($titulo_correo);    
                                    $this->email->message($body);

                                    $this->email->send();
                                    
                                    print " sent...";
                                }
                            }
                        }
                    }
                    
                    
                    // Se termina el proceso
                    exit();
                    
                    // #############################################################
                    
                    break;
                
                case 'qr_notificar':

                    $codigo_plantilla = 'templ-qr';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    $contenido_mensaje = str_replace(
                            array('{registro_qr}', '{registro_qr_imagen}', '{registro_qr_detalle}', '{categoria_qr_detalle}'),
                            array($arrayParametros[0]['registro_qr_codigo'], '<img src="' . base_url('html_public/qr_image/' . $arrayParametros[0]['registro_qr_imagen']) . '" alt="QRCode Image">', $arrayParametros[0]['registro_qr_detalle'], $arrayParametros[0]['categoria_qr_detalle']),
                            $contenido_mensaje
                    );
                    
                    break;
                
                case 'qr_notificar_evento':

                    $codigo_plantilla = 'templ-20';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    $contenido_mensaje = str_replace(
                            array('{registro_qr}', '{registro_qr_imagen}', '{registro_qr_detalle}', '{categoria_qr_detalle}'),
                            array($arrayParametros[0]['registro_qr_codigo'], '<img src="' . base_url('html_public/qr_image/' . $arrayParametros[0]['registro_qr_imagen']) . '" alt="QRCode Image">', $arrayParametros[0]['registro_qr_detalle'], $arrayParametros[0]['categoria_qr_detalle']),
                            $contenido_mensaje
                    );
                    
                    break;
                
                case 'qr_evento_dell':

                    $codigo_plantilla = 'templ-21';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    $contenido_mensaje = str_replace(
                            array('{registro_qr}', '{registro_qr_imagen}', '{registro_qr_detalle}', '{categoria_qr_detalle}'),
                            array($arrayParametros[0]['registro_qr_codigo'], '<img src="' . base_url('html_public/qr_image/' . $arrayParametros[0]['registro_qr_imagen']) . '" alt="QRCode Image">', $arrayParametros[0]['registro_qr_detalle'], $arrayParametros[0]['categoria_qr_detalle']),
                            $contenido_mensaje
                    );
                    
                    break;
                
                case 'verificar_solicitud':

                    $codigo_plantilla = 'templ-01';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    $contenido_mensaje = str_replace(
                            array('{link_verificacion_solicitud}'),
                            array($arrayParametros),
                            $contenido_mensaje
                    );

                    break;

                case 'enviar_documentos_app':

                    $codigo_plantilla = 'templ-03';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                        // Remplazar con los parámetros específicos del envío					
                        $empresa_nombre = $arrayParametros[0]['empresa_nombre'];
                        $empresa_categoria = $arrayParametros[0]['empresa_categoria'];
                        $ejecutivo_asignado_nombre = $arrayParametros[0]['ejecutivo_asignado_nombre'];
                        $ejecutivo_asignado_contacto = $arrayParametros[0]['ejecutivo_asignado_contacto'];

                        $contenido_mensaje = str_replace(
                                array('{empresa_nombre}', '{empresa_categoria}', '{ejecutivo_asignado_nombre}', '{ejecutivo_asignado_contacto}'),
                                array($empresa_nombre, $empresa_categoria, $ejecutivo_asignado_nombre, $ejecutivo_asignado_contacto),
                                $contenido_mensaje
                        );

                    // Se adjunta los documentos

                    $lista_documentos = '';
                    
                    // Se adjunta documentos sólo si el array esta con valores
                    if(is_array($arrayDocumentos))
                    {
                        foreach ($arrayDocumentos as $key => $value)
                        {
                            if($this->mfunciones_generales->GetDocumentoEnviar($value["documento_id"], 'file'))
                            {
                                $lista_documentos .= '<br /> - ' . $this->mfunciones_generales->GetDocumentoEnviar($value["documento_id"], 'nombre_y_pdf');
                                
                                $this->email->attach($this->mfunciones_generales->GetDocumentoEnviar($value["documento_id"], 'file'));
                            }
                        }
                    }
                    
                    if($lista_documentos != '')
                    {
                        $contenido_mensaje = str_replace(
                                array('{onboarding_listado_adjuntos}'),
                                array('Se adjunta también a la presente: ' . $lista_documentos),
                                $contenido_mensaje
                        );
                    }
                    
                    break;

                case 'remitir_cumplimiento':

                    $codigo_plantilla = 'templ-04';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    // BUSCAR LA INFORMACIÓN DE LA EMPRESA        
                    // Listado Detalle Empresa
                    $arrResultado1 = $this->mfunciones_generales->GetDatosEmpresaCorreo($arrayParametros);

                    if (isset($arrResultado1[0])) 
                    {
                        $contenido_mensaje = str_replace(
                                array('{codigo_prospecto}', '{empresa_nombre}', '{empresa_categoria}', '{ejecutivo_asignado_nombre}', '{ejecutivo_asignado_contacto}'),
                                array('AFN_'.$arrayParametros, $arrResultado1[0]['empresa_nombre'], $arrResultado1[0]['empresa_categoria'], $arrResultado1[0]['ejecutivo_asignado_nombre'], $arrResultado1[0]['ejecutivo_asignado_contacto']),
                                $contenido_mensaje
                        );
                    }
                    else 
                    {
                        // En el caso que no se encuentre información con los parámetros indicados, se devuelve vacío            
                        return FALSE;
                        exit();
                    }

                    break;

                case 'notificar_EA':

                    $codigo_plantilla = 'templ-06';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    // BUSCAR LA INFORMACIÓN DE LA EMPRESA        
                    // Listado Detalle Empresa
                    $arrResultado1 = $this->mfunciones_generales->GetDatosEmpresaCorreo($arrayParametros[0]['codigo_prospecto']);

                    // Obtener enlace Evento Calendario Google
                    $visita_evento_calendario = $this->mfunciones_generales->VisitaEnlaceCalendario(1, $arrayParametros[0]['fecha_visita'], $arrayParametros[0]['fecha_fin'], $arrayParametros[0]['direccion_visita']);
                    
                    if (isset($arrResultado1[0])) 
                    {
                        $contenido_mensaje = str_replace(
                            array('{codigo_prospecto}', '{empresa_nombre}', '{empresa_categoria}', '{ejecutivo_asignado_nombre}', '{ejecutivo_asignado_contacto}', '{fecha_visita}', '{fecha_evento_googlecalendar}'),
                            array('AFN_'.$arrayParametros[0]['codigo_prospecto'], $arrResultado1[0]['empresa_nombre'], $arrResultado1[0]['empresa_categoria'], $arrResultado1[0]['ejecutivo_asignado_nombre'], $arrResultado1[0]['ejecutivo_asignado_contacto'], $arrayParametros[0]['fecha_visita'], $visita_evento_calendario),
                            $contenido_mensaje
                        );
                    }
                    else 
                    {
                        // En el caso que no se encuentre información con los parámetros indicados, se devuelve vacío            
                        return FALSE;
                        exit();
                    }

                    break;

                case 'notificar_mantenimiento_EA':

                    $codigo_plantilla = 'templ-07';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    // BUSCAR LA INFORMACIÓN DE LA EMPRESA        
                    // Listado Detalle Empresa
                    $arrResultado1 = $this->mfunciones_generales->GetDatosEmpresa($arrayParametros[0]['codigo_empresa']);

                    // Obtener enlace Evento Calendario Google
                    $visita_evento_calendario = $this->mfunciones_generales->VisitaEnlaceCalendario(2, $arrayParametros[0]['fecha_visita'], $arrayParametros[0]['fecha_fin'], $arrayParametros[0]['direccion_visita']);
                    
                    if (isset($arrResultado1[0])) 
                    {
                        $contenido_mensaje = str_replace(
                            array('{codigo_mantenimiento}', '{empresa_nombre}', '{empresa_categoria}', '{ejecutivo_asignado_nombre}', '{ejecutivo_asignado_contacto}', '{fecha_visita}', '{list_tareas_mant}', '{fecha_evento_googlecalendar}'),
                            array('MAN_'.$arrayParametros[0]['codigo_mantenimiento'], $arrResultado1[0]['empresa_nombre'], $arrResultado1[0]['empresa_categoria'], $arrResultado1[0]['ejecutivo_asignado_nombre'], $arrResultado1[0]['ejecutivo_asignado_contacto'], $arrayParametros[0]['fecha_visita'], $arrayParametros[0]['listadoTareas'], $visita_evento_calendario),
                            $contenido_mensaje
                        );
                    }
                    else 
                    {
                        // En el caso que no se encuentre información con los parámetros indicados, se devuelve vacío            
                        return FALSE;
                        exit();
                    }

                    break;

                case 'notificar_instancia':

                    $codigo_plantilla = 'templ-05';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    $arrProspecto = $this->mfunciones_logica->ObtenerInfoProspecto($arrayParametros);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrProspecto);
                    
                    $arrEtapa = $this->mfunciones_logica->ObtenerDatosFlujoEspec($arrProspecto[0]['prospecto_etapa']);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEtapa);
                    
                    $contenido_mensaje = str_replace(
                            array('{lead_codigo_registro}', '{lead_solicitante}', '{lead_ci}', '{lead_email}', '{lead_etapa_actual}', '{lead_rubro}', '{lead_oficial_negocios}', '{lead_agencia}', '{fecha_accion}'),
                            array(PREFIJO_PROSPECTO . $arrProspecto[0]['prospecto_id'], $arrProspecto[0]['general_solicitante'], $arrProspecto[0]["general_ci"] . ' ' . $this->mfunciones_generales->GetValorCatalogo($arrProspecto[0]["general_ci_extension"], 'extension_ci'), $arrProspecto[0]['general_email'], $arrEtapa[0]['etapa_nombre'], $arrProspecto[0]['camp_nombre'], $arrProspecto[0]['usuario_nombre'], $arrProspecto[0]['estructura_regional_nombre'], $arrProspecto[0]['accion_fecha']),
                            $contenido_mensaje
                    );
                    
                    break;

                case 'notificar_instancia_observacion':

                    $codigo_plantilla = 'templ-12';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    // BUSCAR LA INFORMACIÓN DE LA EMPRESA        
                    // Listado Detalle Empresa
                    $arrResultado1 = $this->mfunciones_generales->GetDatosEmpresaCorreo($arrayParametros);

                    if (isset($arrResultado1[0])) 
                    {
                        $contenido_mensaje = str_replace(
                                array('{codigo_prospecto}', '{empresa_nombre}', '{empresa_categoria}', '{ejecutivo_asignado_nombre}', '{ejecutivo_asignado_contacto}'),
                                array('AFN_'.$arrayParametros, $arrResultado1[0]['empresa_nombre'], $arrResultado1[0]['empresa_categoria'], $arrResultado1[0]['ejecutivo_asignado_nombre'], $arrResultado1[0]['ejecutivo_asignado_contacto']),
                                $contenido_mensaje
                        );
                    }
                    else 
                    {
                        // En el caso que no se encuentre información con los parámetros indicados, se devuelve vacío            
                        return FALSE;
                        exit();
                    }

                    break;
                    
                case 'notificar_rechazo':

                    $codigo_plantilla = 'templ-10';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    // BUSCAR LA INFORMACIÓN DE LA EMPRESA        
                    // Listado Detalle Empresa
                    $arrResultado1 = $this->mfunciones_generales->GetDatosEmpresaCorreo($arrayParametros);

                    if (isset($arrResultado1[0])) 
                    {
                        $contenido_mensaje = str_replace(
                                array('{codigo_prospecto}', '{empresa_nombre}', '{empresa_categoria}', '{ejecutivo_asignado_nombre}', '{ejecutivo_asignado_contacto}'),
                                array('AFN_'.$arrayParametros, $arrResultado1[0]['empresa_nombre'], $arrResultado1[0]['empresa_categoria'], $arrResultado1[0]['ejecutivo_asignado_nombre'], $arrResultado1[0]['ejecutivo_asignado_contacto']),
                                $contenido_mensaje
                        );
                    }
                    else 
                    {
                        // En el caso que no se encuentre información con los parámetros indicados, se devuelve vacío            
                        return FALSE;
                        exit();
                    }

                    break;

                case 'notificar_instancia_mantenimiento':

                    $codigo_plantilla = 'templ-08';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    // BUSCAR LA INFORMACIÓN DE LA EMPRESA        
                    // Listado Detalle Empresa
                    $arrResultado1 = $this->mfunciones_generales->GetDatosEmpresa($arrayParametros[0]['codigo_empresa']);

                    if (isset($arrResultado1[0])) 
                    {
                        $contenido_mensaje = str_replace(
                            array('{codigo_mantenimiento}', '{empresa_nombre}', '{empresa_categoria}', '{ejecutivo_asignado_nombre}', '{ejecutivo_asignado_contacto}', '{fecha_visita}', '{list_tareas_mant}'),
                            array('MAN_'.$arrayParametros[0]['codigo_mantenimiento'], $arrResultado1[0]['empresa_nombre'], $arrResultado1[0]['empresa_categoria'], $arrResultado1[0]['ejecutivo_asignado_nombre'], $arrResultado1[0]['ejecutivo_asignado_contacto'], $arrayParametros[0]['fecha_visita'], $arrayParametros[0]['listadoTareas']),
                            $contenido_mensaje
                        );
                    }
                    else 
                    {
                        // En el caso que no se encuentre información con los parámetros indicados, se devuelve vacío            
                        return FALSE;
                        exit();
                    }

                    break;

                case 'observar_documento_app':

                    $codigo_plantilla = 'templ-09';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    if (isset($arrayParametros[0])) 
                    {
                        $contenido_mensaje = str_replace(
                                array('{codigo_prospecto}', '{empresa_nombre}', '{empresa_categoria}', '{ejecutivo_asignado_nombre}', '{ejecutivo_asignado_contacto}'),
                                array($arrayParametros[0]['prospecto_id'], $arrayParametros[0]['empresa_nombre'], $arrayParametros[0]['empresa_categoria'], $arrayParametros[0]['ejecutivo_asignado_nombre'], $arrayParametros[0]['ejecutivo_asignado_contacto']),
                                $contenido_mensaje
                        );
                    }
                    else 
                    {
                        // En el caso que no se encuentre información con los parámetros indicados, se devuelve vacío            
                        return FALSE;
                        exit();
                    }

                    break;

                case 'solicitar_excepcion':

                    $codigo_plantilla = 'templ-11';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    // BUSCAR LA INFORMACIÓN DE LA EMPRESA        
                    // Listado Detalle Empresa
                    $arrResultado1 = $this->mfunciones_generales->GetDatosEmpresaCorreo($arrayParametros);

                    if (isset($arrResultado1[0])) 
                    {
                        $contenido_mensaje = str_replace(
                                array('{codigo_prospecto}', '{empresa_nombre}', '{empresa_categoria}', '{ejecutivo_asignado_nombre}', '{ejecutivo_asignado_contacto}'),
                                array('AFN_'.$arrayParametros, $arrResultado1[0]['empresa_nombre'], $arrResultado1[0]['empresa_categoria'], $arrResultado1[0]['ejecutivo_asignado_nombre'], $arrResultado1[0]['ejecutivo_asignado_contacto']),
                                $contenido_mensaje
                        );
                    }
                    else 
                    {
                        // En el caso que no se encuentre información con los parámetros indicados, se devuelve vacío            
                        return FALSE;
                        exit();
                    }

                    break;
                
                case 'aprobar_excepcion':

                    $codigo_plantilla = 'templ-13';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    // BUSCAR LA INFORMACIÓN DE LA EMPRESA        
                    // Listado Detalle Empresa
                    $arrResultado1 = $this->mfunciones_generales->GetDatosEmpresaCorreo($arrayParametros);

                    if (isset($arrResultado1[0])) 
                    {
                        $contenido_mensaje = str_replace(
                                array('{codigo_prospecto}', '{empresa_nombre}', '{empresa_categoria}', '{ejecutivo_asignado_nombre}', '{ejecutivo_asignado_contacto}'),
                                array('AFN_'.$arrayParametros, $arrResultado1[0]['empresa_nombre'], $arrResultado1[0]['empresa_categoria'], $arrResultado1[0]['ejecutivo_asignado_nombre'], $arrResultado1[0]['ejecutivo_asignado_contacto']),
                                $contenido_mensaje
                        );
                    }
                    else 
                    {
                        // En el caso que no se encuentre información con los parámetros indicados, se devuelve vacío            
                        return FALSE;
                        exit();
                    }

                    break;
                
                case 'terceros_integracion':

                    $codigo_plantilla = 'templ-16';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];
                    
                    $contenido_mensaje = str_replace(
                            array('{empresa_afiliador_nombre}', '{empresa_afiliador_correo}', '{detalle_custom}', '{empresa_afiliador_link}', '{empresa_afiliador_usuarios}'),
                            array($arrayParametros[0]['nombre_afiliador'], $arrayParametros[0]['empresa_afiliador_correo'], $arrayParametros[0]['detalle_custom'], str_replace(';', '', $arrayParametros[0]['empresa_afiliador_link']), $arrayParametros[0]['empresa_afiliador_usuarios']),
                            $contenido_mensaje
                    );
                    
                    break;
                
                case 'terceros_notificacion':

                    $codigo_plantilla = 'templ-15';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];
                    
                    $contenido_mensaje = str_replace(
                            array('{empresa_afiliador_nombre}','{nombre_persona}', '{nombre_empresa}', '{email}', '{telefono}', '{direccion}', '{catalogo_ciudad}', '{tipo_empresa}', '{geo}', '{empresa_nit}', '{accion_fecha}', '{codigo_terceros}'),
                            array($arrayParametros[0]['nombre_afiliador'], $arrayParametros[0]['nombre_persona'], $arrayParametros[0]['nombre_empresa'], $arrayParametros[0]['email'], $arrayParametros[0]['telefono'], $arrayParametros[0]['direccion'], $arrayParametros[0]['catalogo_ciudad'], $arrayParametros[0]['tipo_empresa'], $arrayParametros[0]['geo'], $arrayParametros[0]['empresa_nit'], $arrayParametros[0]['accion_fecha'], $arrayParametros[0]['codigo_terceros']),
                            $contenido_mensaje
                    );

                    // Se adjunta los documentos

                    try {
                        $arrayDocumentos = $this->mfunciones_logica->ObtenerDocumentosTercerosDigitalizar($arrayParametros[0]['codigo_terceros']);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrayDocumentos);

                        // Se adjunta documentos sólo si el array esta con valores
                        if (isset($arrayDocumentos[0]))
                        { 
                            foreach ($arrayDocumentos as $key => $value)
                            {
                                $ruta = $this->mfunciones_generales->GetInfoTercerosDigitalizado($arrayParametros[0]['codigo_terceros'], $value["documento_id"], 'path');

                                if($ruta)
                                {
                                    $this->email->attach($ruta);
                                }
                            }
                        }
                    }
                    catch (Exception $e) 
                    {
                        js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                            Ocurrio un evento inesperado, intentelo mas tarde.</span>");
                        exit();
                    }
                    
                    break;
                
                case 'terceros_recepcion':

                    $codigo_plantilla = 'templ-14';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    $contenido_mensaje = str_replace(
                            array('{onboarding_codigo_registro}', '{onboarding_rechazo_texto}', '{onboarding_nombre_solicitante}', '{onboarding_email_solicitante}', '{onboarding_fecha_registro}', '{onboarding_estado_actual}', '{onboarding_completar_texto}', '{onboarding_numero_cuenta}', '{onboarding_nombre_agencia}', '{onboarding_tipo_cuenta}'),
                            array(PREFIJO_TERCEROS . $arrayParametros[0]['terceros_id'], $arrayParametros[0]['rechazado_texto'], $arrayParametros[0]['terceros_nombre'], $arrayParametros[0]['terceros_email'], $arrayParametros[0]['terceros_fecha'], $arrayParametros[0]['terceros_estado'], $arrayParametros[0]['completado_texto'], $arrayParametros[0]['onboarding_numero_cuenta'], $arrayParametros[0]['nombre_agencia'], $arrayParametros[0]['tipo_cuenta']),
                            $contenido_mensaje
                    );

                    // Se adjunta los documentos

                    $lista_documentos = '';
                    
                    // Se adjunta documentos sólo si el array esta con valores
                    if(is_array($arrayDocumentos))
                    {
                        foreach ($arrayDocumentos as $key => $value)
                        {
                            if($this->mfunciones_generales->GetDocumentoEnviar($value["documento_id"], 'file'))
                            {
                                $lista_documentos .= '<br /> - ' . $this->mfunciones_generales->GetDocumentoEnviar($value["documento_id"], 'nombre_y_pdf');
                                
                                $this->email->attach($this->mfunciones_generales->GetDocumentoEnviar($value["documento_id"], 'file'));
                            }
                        }
                    }
                    
                    if($lista_documentos != '')
                    {
                        $contenido_mensaje = str_replace(
                                array('{onboarding_listado_adjuntos}'),
                                array('Se adjunta también a la presente: ' . $lista_documentos),
                                $contenido_mensaje
                        );
                    }
                    else
                    {
                        $contenido_mensaje = str_replace(
                                array('{onboarding_listado_adjuntos}'),
                                array(' '),
                                $contenido_mensaje
                        );
                    }
                    
                    break;
                
                case 'terceros_rechazo':

                    $codigo_plantilla = 'templ-17';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    $contenido_mensaje = str_replace(
                            array('{onboarding_codigo_registro}', '{onboarding_rechazo_texto}', '{onboarding_nombre_solicitante}', '{onboarding_email_solicitante}', '{onboarding_fecha_registro}', '{onboarding_estado_actual}', '{onboarding_completar_texto}', '{onboarding_numero_cuenta}', '{onboarding_nombre_agencia}', '{onboarding_tipo_cuenta}'),
                            array(PREFIJO_TERCEROS . $arrayParametros[0]['terceros_id'], $arrayParametros[0]['rechazado_texto'], $arrayParametros[0]['terceros_nombre'], $arrayParametros[0]['terceros_email'], $arrayParametros[0]['terceros_fecha'], $arrayParametros[0]['terceros_estado'], $arrayParametros[0]['completado_texto'], $arrayParametros[0]['onboarding_numero_cuenta'], $arrayParametros[0]['nombre_agencia'], $arrayParametros[0]['tipo_cuenta']),
                            $contenido_mensaje
                    );

                    // Se adjunta los documentos

                    $lista_documentos = '';
                    
                    // Se adjunta documentos sólo si el array esta con valores
                    if(is_array($arrayDocumentos))
                    {
                        foreach ($arrayDocumentos as $key => $value)
                        {
                            if($this->mfunciones_generales->GetDocumentoEnviar($value["documento_id"], 'file'))
                            {
                                $lista_documentos .= '<br /> - ' . $this->mfunciones_generales->GetDocumentoEnviar($value["documento_id"], 'nombre_y_pdf');
                                
                                $this->email->attach($this->mfunciones_generales->GetDocumentoEnviar($value["documento_id"], 'file'));
                            }
                        }
                    }
                    
                    if($lista_documentos != '')
                    {
                        $contenido_mensaje = str_replace(
                                array('{onboarding_listado_adjuntos}'),
                                array('Se adjunta también a la presente: ' . $lista_documentos),
                                $contenido_mensaje
                        );
                    }
                    else
                    {
                        $contenido_mensaje = str_replace(
                                array('{onboarding_listado_adjuntos}'),
                                array(' '),
                                $contenido_mensaje
                        );
                    }
                    
                    break;
                
                case 'terceros_completado':

                    $codigo_plantilla = 'templ-19';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    $contenido_mensaje = str_replace(
                            array('{onboarding_codigo_registro}', '{onboarding_rechazo_texto}', '{onboarding_nombre_solicitante}', '{onboarding_email_solicitante}', '{onboarding_fecha_registro}', '{onboarding_estado_actual}', '{onboarding_completar_texto}', '{onboarding_numero_cuenta}', '{onboarding_nombre_agencia}', '{onboarding_tipo_cuenta}'),
                            array(PREFIJO_TERCEROS . $arrayParametros[0]['terceros_id'], $arrayParametros[0]['rechazado_texto'], $arrayParametros[0]['terceros_nombre'], $arrayParametros[0]['terceros_email'], $arrayParametros[0]['terceros_fecha'], $arrayParametros[0]['terceros_estado'], $arrayParametros[0]['completado_texto'], $arrayParametros[0]['onboarding_numero_cuenta'], $arrayParametros[0]['nombre_agencia'], $arrayParametros[0]['tipo_cuenta']),
                            $contenido_mensaje
                    );

                    // Se adjunta los documentos

                    $lista_documentos = '';
                    
                    // Se adjunta documentos sólo si el array esta con valores
                    if(is_array($arrayDocumentos))
                    {
                        foreach ($arrayDocumentos as $key => $value)
                        {
                            if($this->mfunciones_generales->GetDocumentoEnviar($value["documento_id"], 'file'))
                            {
                                $lista_documentos .= '<br /> - ' . $this->mfunciones_generales->GetDocumentoEnviar($value["documento_id"], 'nombre_y_pdf');
                                
                                $this->email->attach($this->mfunciones_generales->GetDocumentoEnviar($value["documento_id"], 'file'));
                            }
                        }
                    }
                    
                    if($lista_documentos != '')
                    {
                        $contenido_mensaje = str_replace(
                                array('{onboarding_listado_adjuntos}'),
                                array('Se adjunta también a la presente: ' . $lista_documentos),
                                $contenido_mensaje
                        );
                    }
                    else
                    {
                        $contenido_mensaje = str_replace(
                                array('{onboarding_listado_adjuntos}'),
                                array(' '),
                                $contenido_mensaje
                        );
                    }
                    
                    // Se adjunta el Contrato
                    
                    if($this->mfunciones_generales->GetInfoTercerosDigitalizadoPDFaux($arrayParametros[0]['terceros_id'], 13, 'existe'))
                    {
                        $this->email->attach($this->mfunciones_generales->GetInfoTercerosDigitalizadoPDFaux($arrayParametros[0]['terceros_id'], 13, 'path'));
                    }
                    
                    break;
                
                case 'notificar_instancia_onboarding':

                    $codigo_plantilla = 'templ-18';

                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    $contenido_mensaje = $arrResultado2[0]['conf_plantilla_contenido'];
                    $titulo_correo = $arrResultado2[0]['conf_plantilla_titulo_correo'];

                    $contenido_mensaje = str_replace(
                            array('{onboarding_codigo_registro}', '{onboarding_rechazo_texto}', '{onboarding_nombre_solicitante}', '{onboarding_email_solicitante}', '{onboarding_fecha_registro}', '{onboarding_estado_actual}', '{onboarding_completar_texto}', '{onboarding_numero_cuenta}', '{onboarding_nombre_agencia}', '{onboarding_tipo_cuenta}'),
                            array(PREFIJO_TERCEROS . $arrayParametros[0]['terceros_id'], $arrayParametros[0]['rechazado_texto'], $arrayParametros[0]['terceros_nombre'], $arrayParametros[0]['terceros_email'], $arrayParametros[0]['terceros_fecha'], $arrayParametros[0]['terceros_estado'], $arrayParametros[0]['completado_texto'], $arrayParametros[0]['onboarding_numero_cuenta'], $arrayParametros[0]['nombre_agencia'], $arrayParametros[0]['tipo_cuenta']),
                            $contenido_mensaje
                    );
                    
                    break;
                    
                default:
                    return false;
                    break;			
            }

            $fecha_actual = date('d/m/Y H:i:s');
            
            $contenido_mensaje = str_replace(
                    array('nbsp;', '{nombre_sistema}', '{nombre_corto}', '{destinatario_nombre}', '{destinatario_correo}', '{titulo_correo}', '{emisor_nombre}', '{fecha_accion}'),
                    array(' ', $this->lang->line('NombreSistema'), $this->lang->line('NombreSistema_corto'), $destinatario_nombre, $destinatario_correo, $titulo_correo, $emisor_nombre, $fecha_actual),
                    $contenido_mensaje
            );

            $body = $contenido_mensaje;

            // 2. SE PROCESA CON LA LIBRERÍA DE ENVÍO

            $this->email->set_crlf( "\r\n" );
            $this->email->set_newline("\r\n");
            $this->email->from($emisor_correo, $emisor_nombre);        
            $this->email->to($destinatario_correo);
            $this->email->subject($titulo_correo);    
            $this->email->message($body);

            // Se envía CC sólo si así se ha definido
            if(is_array($arrayConCopia))
            {
                $this->email->cc($arrayConCopia);
            }

            // Se envía CCO sólo si así se ha definido
            if(is_array($arrayConCopiaOculta))
            {
                $this->email->bcc($arrayConCopiaOculta);
            }

            // SE ENVÍA
            
            if($this->email->send())
            {
                return true;
            }
            else
            {
                // echo $this->email->print_debugger(); // -> Sólo si se requiere hacer el debug
                return false;
            }
        }
    }
    
    public function TestCorreo() {       
        
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');

        // 1. SE ESTABLECE TODAS LAS CONFIGURACIONES

        // Se obtiene la configuración de la Base de Datos

        $arrResultado = $this->mfunciones_logica->ObtenerDatosConf_Correo();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        $config = Array(
            'protocol' => $arrResultado[0]['conf_protocol'],
            'smtp_host' => $arrResultado[0]['conf_smtp_host'],
            'validation' => TRUE,
            'smtp_timeout' => 120,
            'smtp_port' => $arrResultado[0]['conf_smtp_port'],
            'smtp_user' => $arrResultado[0]['conf_smtp_user'],
            'smtp_pass' => $arrResultado[0]['conf_smtp_pass'],
            'mailtype'  => $arrResultado[0]['conf_mailtype'],
            'charset'   => $arrResultado[0]['conf_charset']                
        );

        $emisor_correo = $arrResultado[0]['conf_smtp_user'];
        $emisor_nombre = $this->lang->line('NombreSistema_corto');

        $this->load->library('email', $config);
        $this->email->initialize($config);

        $body = 'Texto de Prueba. Si ves este texto, el correo se envió correctamente (Caracteres: á ä ñ)';

        // 2. SE PROCESA CON LA LIBRERÍA DE ENVÍO

        $this->email->set_crlf( "\r\n" );
        $this->email->set_newline("\r\n");    
        $this->email->from($emisor_correo, $emisor_nombre);        
        $this->email->to($arrResultado[0]['conf_smtp_user']);
        $this->email->subject("Prueba de Envío");    
        $this->email->message($body);

        // SE ENVÍA

        if($this->email->send())
        {
            echo 'Acción Realizada <br /><br />';
        }
        else
        {
            echo 'No se realizó la acción <br /><br />';
        }
        
        echo $this->email->print_debugger();
    }
    
}

?>