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
 * Controlador para la gestión de Prospectos - APROBACIÓN
 * @brief BANDEJA DE LA INSTANCIA
 * @class Conf_catalogo_controller 
 */
class Bandeja_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 25;
        
        // Código de la Etapa Propio de la Bandeja
        protected $codigo_etapa = 14;

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
        
        $_SESSION['direccion_bandeja_actual'] = 'Bandeja/Aprobacion/Ver';
        $_SESSION['dato_etapa_actual'] = $this->codigo_etapa;
        
        $data["arrRespuesta"] = $lst_resultado;
        $data["arrResumen"] = $arrResumen;
        $data["tiempo_etapa_asignado"] = $tiempo_etapa_asignado;
        
        $this->load->view('bandeja_aprobacion/view_bandeja_ver', $data);
    }
    
    public function Aprobacion_Form() {
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
            
            // Obtener Datos de la Empresa
            
            $arrResultado = $this->mfunciones_generales->GetDatosEmpresa($DatosProspecto[0]['empresa_id']);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                            "empresa_id" => $value["empresa_id"],
                            "empresa_consolidada_codigo" => $value["empresa_consolidada"],
                            "empresa_consolidada_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["empresa_consolidada"], 'consolidado'),
                            "empresa_categoria_detalle" => $value["empresa_categoria"],
                            "empresa_nit" => $value["empresa_nit"],
                            "empresa_adquiriente_codigo" => $value["empresa_adquiriente"],
                            "empresa_adquiriente_detalle" => 'ATC SA',
                            "empresa_tipo_sociedad_codigo" => $value["empresa_tipo_sociedad"],
                            "empresa_tipo_sociedad_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_tipo_sociedad"], 'TPS'),
                            "empresa_nombre_legal" => $value["empresa_nombre_legal"],
                            "empresa_nombre_fantasia" => $value["empresa_nombre_fantasia"],
                            "empresa_nombre_establecimiento" => $value["empresa_nombre_establecimiento"],
                            "empresa_denominacion_corta" => $value["empresa_denominacion_corta"],
                            "empresa_rubro_codigo" => $value["empresa_rubro"],
                            "empresa_rubro_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_rubro"], 'RUB'),
                            "empresa_perfil_comercial_codigo" => $value["empresa_perfil_comercial"],
                            "empresa_perfil_comercial_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_rubro"], 'PEC'),
                            "empresa_mcc_codigo" => $value["empresa_mcc"],
                            "empresa_mcc_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_mcc"], 'MCC'),
                            "empresa_nombre_referencia" => $value["empresa_nombre_referencia"],
                            "empresa_ha_desde" => $value["empresa_ha_desde"],
                            "empresa_ha_hasta" => $value["empresa_ha_hasta"],
                            "empresa_dias_atencion" => $value["empresa_dias_atencion"],
                            "empresa_medio_contacto_codigo" => $value["empresa_medio_contacto"],
                            "empresa_medio_contacto_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_medio_contacto"], 'MCO'),
                            "empresa_dato_contacto" => $value["empresa_dato_contacto"],
                            "empresa_email" => $value["empresa_email"],
                            "empresa_departamento_codigo" => $value["empresa_departamento"],
                            "empresa_departamento_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_departamento"], 'DEP'),
                            "empresa_municipio_codigo" => $value["empresa_municipio"],
                            "empresa_municipio_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_municipio"], 'CIU'),
                            "empresa_zona_codigo" => $value["empresa_zona"],
                            "empresa_zona_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_zona"], 'ZON'),
                            "empresa_tipo_calle_codigo" => $value["empresa_tipo_calle"],
                            "empresa_tipo_calle_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_tipo_calle"], 'TPC'),
                            "empresa_calle" => $value["empresa_calle"],
                            "empresa_numero" => $value["empresa_numero"],
                            "empresa_direccion_literal" => $value["empresa_direccion_literal"],
                            "empresa_info_adicional" => $value["empresa_info_adicional"],
                            "ejecutivo_asignado_nombre" => $value["ejecutivo_asignado_nombre"],
                            "ejecutivo_asignado_contacto" => $value["ejecutivo_asignado_contacto"]
                            );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }

                $data["arrDias"] = explode(",", $lst_resultado[0]['empresa_dias_atencion']);

            } 
            else 
            {
                $lst_resultado[0] = $arrResultado;
                $data["arrDias"] = $arrResultado;
            }
                
                $tipo_empresa = $DatosProspecto[0]['empresa_categoria_codigo'];
            
                $falta_info_texto = $this->VerificaRequisitosPayStudio($tipo_empresa, $lst_resultado);
            
                $falta_info = 0;
            
                // Si el texto esta vacío, no hay observaciones
                if($falta_info_texto != '')
                {
                    $falta_info = 1;
                }
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

            $data["arrRespuesta"] = $DatosProspecto;
            $data["arrResultado"] = $lst_resultado;

            $data["tipo_empresa"] = $tipo_empresa;
            $data["falta_info"] = $falta_info;
            $data["falta_info_texto"] = $falta_info_texto;
            
            $this->load->view('bandeja_aprobacion/view_aprobacion_form', $data);
        }
    }
    
    public function VerificaRequisitosPayStudio($tipo_empresa, $lst_resultado) {
        
        // Se establecen los flags de verificación

        $falta_info_texto = '';

        $separador = '<br /> - ';

        // Se verifica los campos requeridos por PayStudio

        if((int)$lst_resultado[0]['empresa_nit'] == 0)
        {
            $falta_info_texto .= $separador . $this->lang->line('empresa_nit');
        }

        if($tipo_empresa == 1)
        {
            // Comercio

            /***

            Tipo de Documento (No es necesario, siempre es NIT)
            NIT
            Adquirente
            Tipo de Sociedad
            Nombre Legal
            Nombre Fantasía
            MCC
            Rubro
            Perfil Comercial

            ***/

            if((int)$lst_resultado[0]['empresa_adquiriente_codigo'] == 0)
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_adquiriente_detalle');
            }

            if((int)$lst_resultado[0]['empresa_tipo_sociedad_codigo'] == 0)
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_tipo_sociedad_detalle');
            }

            if($lst_resultado[0]['empresa_nombre_legal'] == '')
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_nombre_legal');
            }

            if($lst_resultado[0]['empresa_nombre_fantasia'] == '')
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_nombre_fantasia');
            }

            if((int)$lst_resultado[0]['empresa_mcc_codigo'] == 0)
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_mcc_detalle');
            }

            if((int)$lst_resultado[0]['empresa_rubro_codigo'] == 0)
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_rubro_detalle');
            }

            if((int)$lst_resultado[0]['empresa_perfil_comercial_codigo'] == 0)
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_perfil_comercial_detalle');
            }

            /***  COMPLETAR ***/

        }

        if($tipo_empresa == 2)
        {
            // Establecimiento

            /***

            Nombre Establecimiento
            Denominación Corta
            Horario de Atención Desde
            Horario de Atención Hasta
            Días de Atención
            Medio de Contacto
            Dato de Contacto:
            Departamento
            Municipio/Ciudad
            Zona/Localidad
            Tipo de Calle
            Calle
            Numero
            Info. Adicional (podría estar vacío)

            ***/

            if($lst_resultado[0]['empresa_nombre_establecimiento'] == '')
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_nombre_establecimiento');
            }

            if($lst_resultado[0]['empresa_denominacion_corta'] == '')
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_denominacion_corta');
            }

            if($this->mfunciones_generales->VerificaFechaH_M($lst_resultado[0]['empresa_ha_desde']) == false)
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_ha_desde');
            }

            if($this->mfunciones_generales->VerificaFechaH_M($lst_resultado[0]['empresa_ha_hasta']) == false)
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_ha_hasta');
            }

            if($lst_resultado[0]['empresa_dias_atencion'] == '')
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_dias_atencion');
            }

            if((int)$lst_resultado[0]['empresa_medio_contacto_codigo'] == 0)
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_medio_contacto_detalle');
            }

            if($lst_resultado[0]['empresa_dato_contacto'] == '')
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_dato_contacto');
            }

            if((int)$lst_resultado[0]['empresa_departamento_codigo'] == 0)
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_departamento_detalle');
            }

            if((int)$lst_resultado[0]['empresa_municipio_codigo'] == 0)
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_municipio_detalle');
            }

            if((int)$lst_resultado[0]['empresa_zona_codigo'] == 0)
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_zona_detalle');
            }

            if((int)$lst_resultado[0]['empresa_tipo_calle_codigo'] == 0)
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_tipo_calle_detalle');
            }

            if($lst_resultado[0]['empresa_calle'] == '')
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_calle');
            }

            if((int)$lst_resultado[0]['empresa_numero'] == 0)
            {
                $falta_info_texto .= $separador . $this->lang->line('empresa_numero');
            }                    
        }
        
        return ($falta_info_texto);
    }
    
    public function Aprobacion_Guardar() {
        
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
            
            $opcion_seleccion1 = $this->input->post('opcion_seleccion1', TRUE);
            $opcion_seleccion2 = $this->input->post('opcion_seleccion2', TRUE);
            
            $nombre_usuario = $_SESSION["session_informacion"]["login"];
            $fecha_actual = date('Y-m-d H:i:s');

            if((int)$codigo_prospecto == 0 || $opcion_seleccion1 == "" || $opcion_seleccion2 == "")
            {
                js_error_div_javascript($this->lang->line('FormularioIncompleto'));
                exit();
            }

            if((int)$opcion_seleccion1 == 0 || (int)$opcion_seleccion2 == 0)
            {
                js_error_div_javascript($this->lang->line('FormularioNoPreguntas'));
                exit();
            }
            
            // Paso 1: Se obtiene el estado y etapa actual del prospecto
            
            // Datos del Prospecto
            $DatosProspecto = $this->mfunciones_generales->GetDatosEmpresaCorreo($codigo_prospecto);

            if (!isset($DatosProspecto[0])) 
            {
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            }
            
            // Obtener Datos de la Empresa
            
            $arrResultado = $this->mfunciones_generales->GetDatosEmpresa($DatosProspecto[0]['empresa_id']);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                            "empresa_id" => $value["empresa_id"],
                            "empresa_consolidada_codigo" => $value["empresa_consolidada"],
                            "empresa_consolidada_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["empresa_consolidada"], 'consolidado'),
                            "empresa_categoria_detalle" => $value["empresa_categoria"],
                            "empresa_nit" => $value["empresa_nit"],
                            "empresa_adquiriente_codigo" => $value["empresa_adquiriente"],
                            "empresa_adquiriente_detalle" => 'ATC SA',
                            "empresa_tipo_sociedad_codigo" => $value["empresa_tipo_sociedad"],
                            "empresa_tipo_sociedad_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_tipo_sociedad"], 'TPS'),
                            "empresa_nombre_legal" => $value["empresa_nombre_legal"],
                            "empresa_nombre_fantasia" => $value["empresa_nombre_fantasia"],
                            "empresa_nombre_establecimiento" => $value["empresa_nombre_establecimiento"],
                            "empresa_denominacion_corta" => $value["empresa_denominacion_corta"],
                            "empresa_rubro_codigo" => $value["empresa_rubro"],
                            "empresa_rubro_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_rubro"], 'RUB'),
                            "empresa_perfil_comercial_codigo" => $value["empresa_perfil_comercial"],
                            "empresa_perfil_comercial_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_rubro"], 'PEC'),
                            "empresa_mcc_codigo" => $value["empresa_mcc"],
                            "empresa_mcc_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_mcc"], 'MCC'),
                            "empresa_nombre_referencia" => $value["empresa_nombre_referencia"],
                            "empresa_ha_desde" => $value["empresa_ha_desde"],
                            "empresa_ha_hasta" => $value["empresa_ha_hasta"],
                            "empresa_dias_atencion" => $value["empresa_dias_atencion"],
                            "empresa_medio_contacto_codigo" => $value["empresa_medio_contacto"],
                            "empresa_medio_contacto_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_medio_contacto"], 'MCO'),
                            "empresa_dato_contacto" => $value["empresa_dato_contacto"],
                            "empresa_email" => $value["empresa_email"],
                            "empresa_departamento_codigo" => $value["empresa_departamento"],
                            "empresa_departamento_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_departamento"], 'DEP'),
                            "empresa_municipio_codigo" => $value["empresa_municipio"],
                            "empresa_municipio_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_municipio"], 'CIU'),
                            "empresa_zona_codigo" => $value["empresa_zona"],
                            "empresa_zona_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_zona"], 'ZON'),
                            "empresa_tipo_calle_codigo" => $value["empresa_tipo_calle"],
                            "empresa_tipo_calle_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_tipo_calle"], 'TPC'),
                            "empresa_calle" => $value["empresa_calle"],
                            "empresa_numero" => $value["empresa_numero"],
                            "empresa_direccion_literal" => $value["empresa_direccion_literal"],
                            "empresa_info_adicional" => $value["empresa_info_adicional"],
                            "ejecutivo_asignado_nombre" => $value["ejecutivo_asignado_nombre"],
                            "ejecutivo_asignado_contacto" => $value["ejecutivo_asignado_contacto"]
                            );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }

                $data["arrDias"] = explode(",", $lst_resultado[0]['empresa_dias_atencion']);

            } 
            else 
            {
                $lst_resultado[0] = $arrResultado;
                $data["arrDias"] = $arrResultado;
            }
                
            $tipo_empresa = $DatosProspecto[0]['empresa_categoria_codigo'];

            $falta_info_texto = $this->VerificaRequisitosPayStudio($tipo_empresa, $lst_resultado);

            $falta_info = 0;

            // Si el texto esta vacío, no hay observaciones
            if($falta_info_texto != '')
            {
                $falta_info = 1;

                js_error_div_javascript($this->lang->line('aprobacion_advertencia') . $falta_info_texto);
                exit();
            }

            if($falta_info == 0)
            {
                $respuesta_ws = $this->mfunciones_generales->WS_InsertarPayStudio($lst_resultado);

                if($respuesta_ws != '')
                {
                    js_error_div_javascript($this->lang->line('error_WS_PayStudio') . '<br /><br />Respuesta: ' . $respuesta_ws);
                    exit();
                }
                else
                {
                    $this->mfunciones_logica->AprobarProspecto($nombre_usuario, $fecha_actual, $codigo_prospecto, $DatosProspecto[0]['empresa_id']);

                    // Si todo va bien, se procede a registrar la Etapa y el Hito
                    $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 16, $this->codigo_etapa, $nombre_usuario, $fecha_actual, 1);

                    /***  REGISTRAR SEGUIMIENTO ***/
                    $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, 16, 5, 'Prospecto Aprobado e Insertado en PayStudio', $nombre_usuario, $fecha_actual);

                    /* Se Envía a la APP la Notificación por Push   tipo_notificacion, $tipo_visita, $codigo_visita */
                    $this->mfunciones_generales->EnviarNotificacionPush(6, 1, $codigo_prospecto);

                    $this->load->view('prospecto/view_aprobado_guardado');
                }
            }
        }
    }
}
?>