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
 * Controlador para la gestión de Prospectos - BACKOFFICE
 * @brief BANDEJA DE LA INSTANCIA
 * @class Conf_catalogo_controller 
 */
class Envio_controller extends CI_Controller {
        
        // Código de la Etapa Propio de la Bandeja
        protected $codigo_etapa = 18;

    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
    
    public function BandejaEnvio_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');

        if(php_sapi_name() === 'cli')
        {
            $flujos = "2,5";
            
            // Se obtiene la hora y día actual
            $fecha_actual = new DateTime(date("Y-m-d H:i:s"));
            $hora = $fecha_actual->format("G");
            $dia = $fecha_actual->format("N");
            
            // Se obtiene el listado de etapas que cumplan el envío de alertas, la hora y día actual
            $arrEtapas = $this->mfunciones_logica->ObtenerListaFlujo($flujos, $hora, $dia);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEtapas);
            
            if (isset($arrEtapas[0])) 
            {
                foreach ($arrEtapas as $key => $value) 
                {
                    $arrParametros[0] = array(
                        "etapa_verificar" => $value["etapa_id"],
                        "etapa_depende" => $value["etapa_depende"],
                        "etapa_nombre" => $value["etapa_nombre"],
                        "etapa_tiempo" => $value["etapa_tiempo"],
                        "etapa_categoria" => $value["etapa_categoria"]
                    );
                    
                    $this->mfunciones_generales->EnviarCorreo('notificar_alerta_prospecto', "change", "change", $arrParametros, 0);
                    //$this->EnviarAlertaProspecto($value["etapa_id"], $value["etapa_depende"], $value["etapa_nombre"], $value["etapa_tiempo"], $value["etapa_categoria"]);
                }
            }
        }
        else
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
    }
    
    public function EnviarAlertaProspecto($etapa_verificar, $etapa_depende, $etapa_nombre, $etapa_tiempo, $etapa_categoria)
    {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Paso 1. Verificar la etapa

        $arrUsuariosNotificar = $this->mfunciones_generales->getListadoUsuariosEtapaEnvio($etapa_verificar);

        if (isset($arrUsuariosNotificar[0])) 
        {   
            foreach ($arrUsuariosNotificar as $key => $value_usuario) 
            {
                $arrResultado = array();
                $lst_resultado = array();

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

                        echo $resultado_vista;

                        echo $destinatario_nombre = $value_usuario['usuario_nombres'] . ' ' . $value_usuario['usuario_app'] . ' ' . $value_usuario['usuario_apm'];
                        echo $destinatario_correo = $value_usuario['usuario_email'];

                        /*
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

                        */

                        print " sent...";
                    }
                }
            }
        }
    }
}
?>