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
class Agencia_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 46;

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
        
        $estado = '5, 7';
        
        $aux_fecha_fin = date("Y-m-d H:i:s");
        
        $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa(11);
        $tiempo_etapa_completado = $arrEtapa[0]['etapa_tiempo'];
        
        // Bandeja Notificar Cierre, no el de las alertas
        $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa(14);
        $tiempo_etapa_notificar = $arrEtapa[0]['etapa_tiempo'];
        
        $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        $data['nombre_region'] = $lista_region->region_nombres_texto;
        
        $arrResultado = $this->mfunciones_logica->ObtenerBandejaAgenciaRegion(-1, $estado, $lista_region->region_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0]))
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                
                if($value["terceros_estado"] == '5')
                {
                    $tiempo = $this->mfunciones_generales->ObtenerHorasLaboral($value["completado_fecha"], $aux_fecha_fin, $tiempo_etapa_completado);
                }
                
                if($value["terceros_estado"] == '7')
                {
                    $tiempo = $this->mfunciones_generales->ObtenerHorasLaboral($value["notificar_cierre_fecha"], $aux_fecha_fin, $tiempo_etapa_notificar);
                }
                
                $item_valor = array(
                    
                    "terceros_id" => $value["terceros_id"],
                    "tipo_persona_id_codigo" => $value["tipo_persona_id"],
                    "tipo_persona_id_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["tipo_persona_id"], 'tipo_persona'),
                    "afiliador_id" => $value["afiliador_id"],
                    "terceros_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["completado_fecha"]),
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
                    "notificar_cierre" => $value["notificar_cierre"],
                    "notificar_cierre_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["notificar_cierre_fecha"])
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
        
        $data["estado"] = 5;//$estado;
        $data["estado_texto"] = $estado_texto;
        
        $data["arrRespuesta"] = $lst_resultado;
        
        $data["tiempo_etapa"] = $tiempo_etapa;
        
        $_SESSION['direccion_bandeja_actual'] = 'Afiliador/Agencia/Ver';
        
        $this->load->view('terceros/view_agencia_ver', $data);
        
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
                        "onboarding_numero_cuenta" => $value["onboarding_numero_cuenta"],
                        "terceros_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["terceros_fecha"]),
                        "terceros_estado" => $value["terceros_estado"],
                        "tipo_cuenta" => $this->mfunciones_generales->GetValorCatalogoDB($value['tipo_cuenta'], 'tipo_cuenta'),
                        "monto_inicial" => $value["monto_inicial"],
                        "rechazado_envia" => 1
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                $lst_resultado[0] = $arrResultado;
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
            
            $this->load->view('terceros/view_agencia_completar', $data);
        }
    }
    
    public function SolicitudCompletar_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        $completado_texto = $this->input->post('rechazado_texto', TRUE);
        
        $monto_inicial = $this->input->post('monto_inicial', TRUE);
        
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
            $texto_error = 'El codigo de registro no es correcto, por favor verifique el codigo.';
            
            js_error_div_javascript($texto_error);
            exit();
        }
        
        $texto_error = 'El registro se encuentra en el estado ' . $this->mfunciones_generales->GetValorCatalogo($arrResultado[0]["terceros_estado_codigo"], 'terceros_estado') . ' por lo que no puede ser Marcado como Entregado, por favor verifique el código.';

        if($arrResultado[0]["terceros_estado_codigo"] != 5 && $arrResultado[0]["terceros_estado_codigo"] != 7)
        {
            js_error_div_javascript($texto_error);
            exit();
        }
        
        if($monto_inicial == '')
        {
            js_error_div_javascript('El Monto Inicial debe tener algún valor.');
            exit();
        }
        
        // FUNCION PARA OBTENER EL CÓDIGO DE PROSPECTO EN BASE AL CODIGO TERCERO
        $codigo_prospecto = $this->mfunciones_generales->ObtenerProspectoTerceros($estructura_id);
        
        $arrTercero = $this->mfunciones_logica->ObtenerDetalleTercerosProspecto($codigo_prospecto);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrTercero);
        
        // $codigo_rubro es el Valor del Monto
        if($monto_inicial > $arrTercero[0]['estructura_regional_monto'])
        {
            js_error_div_javascript('El Monto de Apertura es mayor al establecido para ' . $arrTercero[0]['estructura_regional_nombre'] . ' (límite establecido: ' . number_format($arrTercero[0]['estructura_regional_monto'], 2, ',', '.') . ').');
            exit();
        }
        
        // Se guarda el registro
        
        $this->mfunciones_logica->EntregarSolicitudTerceros($monto_inicial, $completado_texto, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id);
        
        // Notificar Proceso Onboarding     0=No Regionalizado      1=Si Regionalizado
        $this->mfunciones_generales->NotificacionEtapaTerceros($estructura_id, 12, 1);
        
        // Se registra para las Etapas Onboarding   Pasa a: Notificar Flujo Onboarding Completo   Etapa Nueva     Etapa Actual
        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 12, 11, $nombre_usuario, $fecha_actual, 0);
        
        // Se registra para las Etapas Onboarding   Pasa a: Notificar Flujo Onboarding Completo   Etapa Nueva     Etapa Actual
        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 12, 12, $nombre_usuario, $fecha_actual, 0);
        
        $this->Solicitud_Ver();
    }
    
    public function NotificarCierre() {
        
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
                        "onboarding_numero_cuenta" => $value["onboarding_numero_cuenta"],
                        "terceros_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["terceros_fecha"]),
                        "terceros_estado" => $value["terceros_estado"],
                        "tipo_cuenta" => $this->mfunciones_generales->GetValorCatalogoDB($value['tipo_cuenta'], 'tipo_cuenta'),
                        "monto_inicial" => $value["monto_inicial"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
            
            $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
            $data['nombre_region'] = $lista_region->region_nombres_texto;
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
            
            $data["arrRespuesta"] = $lst_resultado;

            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
            
            $this->load->view('terceros/view_agencia_notificar_cierre', $data);
        }
    }
    
    public function NotificarCierre_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        $notificar_cierre_causa = $this->input->post('notificar_cierre_causa', TRUE);
        
        $codigo_usuario = $_SESSION["session_informacion"]["codigo"];
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if((int)$estructura_id == 0)
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        if((int)$notificar_cierre_causa == -1)
        {
            js_error_div_javascript('Debe seleccionar una causal.');
            exit();
        }
        
        $arrResultado = $this->mfunciones_generales->DatosTercerosEmail($estructura_id);
        
        if(!$arrResultado)
        {   
            $texto_error = 'El codigo de registro no es correcto, por favor verifique el codigo.';
            
            js_error_div_javascript($texto_error);
            exit();
        }
        
        if($arrResultado[0]["terceros_estado_codigo"] != 5)
        {
            $texto_error = 'El registro se encuentra en el estado ' . $this->mfunciones_generales->GetValorCatalogo($arrResultado[0]["terceros_estado_codigo"], 'terceros_estado') . ' por lo que no puede ser marcado como ' . $this->mfunciones_generales->GetValorCatalogo(7, 'terceros_estado') . ', por favor verifique el código.';

            js_error_div_javascript($texto_error);
            exit();
        }
        
        // FUNCION PARA OBTENER EL CÓDIGO DE PROSPECTO EN BASE AL CODIGO TERCERO
        $codigo_prospecto = $this->mfunciones_generales->ObtenerProspectoTerceros($estructura_id);
        
        $arrTercero = $this->mfunciones_logica->ObtenerDetalleTercerosProspecto($codigo_prospecto);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrTercero);
        
        // Se guarda el registro
        
        $this->mfunciones_logica->NotificarCierre_Guardar($notificar_cierre_causa, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id);
        
        // Notificar Proceso Onboarding Notificar Cierre=14     0=No Regionalizado      1=Si Regionalizado
        $this->mfunciones_generales->NotificacionEtapaTerceros($estructura_id, 14, 1);
        
        // Se registra para las Etapas Onboarding   Pasa a: Notificar Flujo Onboarding Completo   Etapa Nueva     Etapa Actual
        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 14, $arrTercero[0]['prospecto_etapa'], $nombre_usuario, $fecha_actual, 0);
        
        // Se registra para las Etapas Onboarding   Pasa a: Notificar Flujo Onboarding Completo   Etapa Nueva     Etapa Actual
        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 14, $arrTercero[0]['prospecto_etapa'], $nombre_usuario, $fecha_actual, 0);
        
        // Se envía el correo electrónico al cliente y con copia a los roles y/o usuarios de la etapa Bandeja Agencia
        
            // Obtiene el listado de usuarios a notificar de la etapa "Agencia" => 11
            $arr_usuarios = $this->mfunciones_generales->getListadoUsuariosEtapa(11, $arrResultado[0]['codigo_agencia_fie']);
            
            $lista_usuario_cc = array();
            
            if (isset($arr_usuarios[0]))
            {
                foreach ($arr_usuarios as $key => $value) {
                    array_push($lista_usuario_cc, $value['usuario_email']);
                }
            }
            
            // Se actualiza el array de los datos
            $arrResultado = $this->mfunciones_generales->DatosTercerosEmail($estructura_id);
            
        $this->mfunciones_generales->EnviarCorreo('terceros_notificar_cierre', $arrResultado[0]['terceros_email'], $arrResultado[0]['terceros_nombre'], $arrResultado, "", "", $lista_usuario_cc);
        
        js_invocacion_javascript('alert("Se marcó el registro como ' . $this->lang->line('notificar_cierre_texto') . '.");');
        
        $this->Solicitud_Ver();
    }
    
    public function CuentaCerrada() {
        
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
                        "onboarding_numero_cuenta" => $value["onboarding_numero_cuenta"],
                        "terceros_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["terceros_fecha"]),
                        "terceros_estado" => $value["terceros_estado"],
                        "tipo_cuenta" => $this->mfunciones_generales->GetValorCatalogoDB($value['tipo_cuenta'], 'tipo_cuenta'),
                        "monto_inicial" => $value["monto_inicial"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
            
            $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
            $data['nombre_region'] = $lista_region->region_nombres_texto;
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
            
            $data["arrRespuesta"] = $lst_resultado;

            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
            
            $this->load->view('terceros/view_agencia_cuenta_cerrada', $data);
        }
    }
    
    public function CuentaCerrada_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        $cuenta_cerrada_causa = $this->input->post('cuenta_cerrada_causa', TRUE);
        $cuenta_cerrada_envia = $this->input->post('cuenta_cerrada_envia', TRUE);
        
        $codigo_usuario = $_SESSION["session_informacion"]["codigo"];
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if((int)$estructura_id == 0)
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        if((int)$cuenta_cerrada_causa == -1)
        {
            js_error_div_javascript('Debe seleccionar una causal.');
            exit();
        }
        
        $arrResultado = $this->mfunciones_generales->DatosTercerosEmail($estructura_id);
        
        if(!$arrResultado)
        {   
            $texto_error = 'El codigo de registro no es correcto, por favor verifique el codigo.';
            
            js_error_div_javascript($texto_error);
            exit();
        }
        
        if($arrResultado[0]["terceros_estado_codigo"] != 5 && $arrResultado[0]["terceros_estado_codigo"] != 7)
        {
            $texto_error = 'El registro se encuentra en el estado ' . $this->mfunciones_generales->GetValorCatalogo($arrResultado[0]["terceros_estado_codigo"], 'terceros_estado') . ' por lo que no puede ser marcado como ' . $this->mfunciones_generales->GetValorCatalogo(8, 'terceros_estado') . ', por favor verifique el código.';

            js_error_div_javascript($texto_error);
            exit();
        }
        
        // FUNCION PARA OBTENER EL CÓDIGO DE PROSPECTO EN BASE AL CODIGO TERCERO
        $codigo_prospecto = $this->mfunciones_generales->ObtenerProspectoTerceros($estructura_id);
        
        $arrTercero = $this->mfunciones_logica->ObtenerDetalleTercerosProspecto($codigo_prospecto);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrTercero);
        
        // Se guarda el registro
        
        $this->mfunciones_logica->CuentaCerrada_Guardar($cuenta_cerrada_causa, $cuenta_cerrada_envia, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id);
        
        // Notificar Proceso Onboarding Cuenta Cerrada=15     0=No Regionalizado      1=Si Regionalizado
        $this->mfunciones_generales->NotificacionEtapaTerceros($estructura_id, 15, 1);
        
        // Se registra para las Etapas Onboarding   Pasa a: Notificar Flujo Onboarding Completo   Etapa Nueva     Etapa Actual
        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 15, $arrTercero[0]['prospecto_etapa'], $nombre_usuario, $fecha_actual, 0);
        
        // Se registra para las Etapas Onboarding   Pasa a: Notificar Flujo Onboarding Completo   Etapa Nueva     Etapa Actual
        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 15, $arrTercero[0]['prospecto_etapa'], $nombre_usuario, $fecha_actual, 0);
        
        // Se envía el correo electrónico al cliente y con copia a los roles y/o usuarios de la etapa Bandeja Agencia
        
            // Obtiene el listado de usuarios a notificar de la etapa "Agencia" => 11
            $arr_usuarios = $this->mfunciones_generales->getListadoUsuariosEtapa(11, $arrResultado[0]['codigo_agencia_fie']);
            
            $lista_usuario_cc = array();
            
            if (isset($arr_usuarios[0]))
            {
                foreach ($arr_usuarios as $key => $value) {
                    array_push($lista_usuario_cc, $value['usuario_email']);
                }
            }
            
            
        // Se envía correo sólo si selecionó "Si" (1)
        if($cuenta_cerrada_envia == 1)
        {
            // Se actualiza el array de los datos
            $arrResultado = $this->mfunciones_generales->DatosTercerosEmail($estructura_id);
            
            $this->mfunciones_generales->EnviarCorreo('terceros_cuenta_cerrada', $arrResultado[0]['terceros_email'], $arrResultado[0]['terceros_nombre'], $arrResultado, "", "", $lista_usuario_cc);
        }
        
        js_invocacion_javascript('alert("Se marcó el registro como ' . $this->lang->line('cuenta_cerrada_texto') . '.");');
        
        $this->Solicitud_Ver();
    }
    
    // INICIO Reportes de la Bandeja Agencia
    
    public function Reportes_Ver() {
        $this->lang->load('general', 'castellano'); // Archivo de Lenguaje
        $this->load->model('mfunciones_generales'); // Funciones Generales
        $this->load->model('mfunciones_logica');    // Capa de Datos
        $this->load->model('mfunciones_reporte');   // Capa de Datos Reporte
        
        $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        
        $data['nombre_region'] = $lista_region->region_nombres_texto;
        
        $this->load->view('terceros/view_agencia_consultas_ver', $data);
    }

    public function Reportes_Generar() {
        $this->lang->load('general', 'castellano'); // Archivo de Lenguaje
        $this->load->model('mfunciones_generales'); // Funciones Generales
        $this->load->model('mfunciones_logica');    // Capa de Datos
        $this->load->model('mfunciones_reporte');   // Capa de Datos Reporte
        $parametros = json_decode(urldecode($this->input->get_post("parametros")));
        
        $resultado = $this->mfunciones_reporte->Generar_Consulta_Agencia($parametros);

        if ($resultado->error) die("<div class='mensajeBD'> <br /> <i class='fa fa-meh-o' aria-hidden='true'></i> " . $resultado->mensaje_error . "<br /><br /></div>");

        if ($resultado->sin_registros) die($this->lang->line('TablaNoResultadosReporte'));
        if (property_exists($parametros,"exportar") && $parametros->exportar == "pdf") {
            $this->mfunciones_generales->GeneraPDF('terceros/view_agencia_consultas_tabla_registros_plain',array("resultado"=>$resultado, "parametros"=>$parametros), 'L');
            return;
        } else if (property_exists($parametros,"exportar") && $parametros->exportar == "excel") {
            $this->mfunciones_generales->GeneraExcel('terceros/view_agencia_consultas_tabla_registros_plain',array("resultado"=>$resultado, "parametros"=>$parametros));
            return;
        }
        $this->load->view('terceros/view_agencia_consultas_tabla_registros',array("resultado"=>$resultado, "parametros"=>$parametros));
        
        return;
    }

    public function Reportes_Agregar_Filtro_Agencia() {
        $this->lang->load('general', 'castellano'); // Archivo de Lenguaje
        $this->load->model('mfunciones_generales'); // Funciones Generales
        $this->load->model('mfunciones_logica');    // Capa de Datos
        $this->load->model('mfunciones_reporte');   // Capa de Datos Reporte
        $campos = $this->mfunciones_reporte->Obtener_Campos_Filtro_Agencia();
        $this->load->view('terceros/view_consultas_agregar_filtro_registro',array("campos"=>$campos));
    }

    public function Reportes_Valores_Filtro() {
        $this->lang->load('general', 'castellano'); // Archivo de Lenguaje
        $this->load->model('mfunciones_generales'); // Funciones Generales
        $this->load->model('mfunciones_logica');    // Capa de Datos
        $this->load->model('mfunciones_reporte');   // Capa de Datos Reporte
        die(json_encode($this->mfunciones_reporte->Obtener_Valores_Filtro($this->input->get_post("campo"), $this->input->get_post("perfil_app"))));
    }
    
    // FIN Reportes de la Bandeja Agencia
}
?>