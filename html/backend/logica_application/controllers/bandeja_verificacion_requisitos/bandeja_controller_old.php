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
 * Controlador para la gestión de Prospectos
 * @brief BANDEJA DE LA INSTANCIA
 * @class Conf_catalogo_controller 
 */
class Bandeja_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 20;
        
        // Código de la Etapa Propio de la Bandeja
        protected $codigo_etapa = 5;

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
        $this->load->model('mfunciones_microcreditos');
        
        // Bandeja Supervisión Leads Consolidados
        
        //$filtro = 'p.prospecto_etapa>=' . $this->codigo_etapa . ' AND p.prospecto_consolidado=1';
        $filtro = 'p.prospecto_etapa IN (5,22) AND p.prospecto_consolidado=1 AND p.general_categoria=1';
        
        /***** REGIONALIZACIÓN INICIO ******/                
            // Se captura el filtro Regionalizado
            $regionalizado = $this->mfunciones_generales->getProspectosRegion();
            if($regionalizado->error)
            {
                js_error_div_javascript($this->lang->line('TablaNoResultados'));
                exit();
            }
            $data["region_nombres"] = $regionalizado->region_nombres_texto;
            $filtro .= $regionalizado->region_consulta;
        /***** REGIONALIZACIÓN FIN ******/
        
        $arrResultado = $this->mfunciones_logica->ListadoBandejasProspecto($filtro);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        $i = 0;
        
        if (isset($arrResultado[0]))
        {       
            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                        "prospecto_id" => $value["prospecto_id"],
                        "usuario_id" => $value["usuario_id"],
                        "ejecutivo_nombre" => $value["ejecutivo_nombre"],
                        "tiempo_etapa" => $this->mfunciones_generales->TiempoEtapaProspecto($value["fecha_derivada_etapa"], $value["prospecto_etapa"]),
                        "general_solicitante" => $value["general_solicitante"],
                        "general_ci" => $value["general_ci"] . $this->mfunciones_generales->GetValorCatalogo($value["general_ci_extension"], 'extension_ci'),
                        "general_actividad" => $value["general_actividad"],
                        "general_destino" => nl2br($value["general_destino"]),
                        "general_interes" => $value["general_interes"],
                        "camp_id" => $value["camp_id"],
                        "camp_nombre" => $value["camp_nombre"],
                        "prospecto_evaluacion" => $value["prospecto_evaluacion"],
                        "prospecto_fecha_conclusion" => $value["prospecto_fecha_conclusion"],
                        "prospecto_etapa" => $value["prospecto_etapa"],
                        "prospecto_jda_eval" => $value["prospecto_jda_eval"]
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        }
        
        $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        $arrSolCred = $this->mfunciones_microcreditos->BandejaSupervisionLeads($lista_region->region_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrSolCred);

        if (isset($arrSolCred[0]))
        {
            foreach ($arrSolCred as $key => $value) 
            {
                $item_valor = array(
                        "prospecto_id" => $value["sol_id"],
                        "usuario_id" => $value["usuario_id"],
                        "ejecutivo_nombre" => $value["ejecutivo_nombre"],
                        "tiempo_etapa" => $this->mfunciones_generales->TiempoEtapaProspecto($value["sol_consolidado_fecha"], ($value["sol_jda_eval"]==0 ? 5 : 22)),
                        "general_solicitante" => $value["sol_primer_nombre"] . ' ' . $value["sol_primer_apellido"] . ' ' . $value["sol_segundo_apellido"],
                        "general_ci" => $value["sol_ci"] . ' ' . $value["sol_complemento"] . ' ' . ((int)$value['sol_extension']==-1 ? '' : $this->mfunciones_generales->GetValorCatalogoDB($value['sol_extension'], 'cI_lugar_emisionoextension')),
                        "general_actividad" => ($value['sol_dependencia']==1 ? 'Dependiente, ' . $value['sol_depen_empresa'] : 'Independiente, ' . $value['sol_indepen_actividad']),
                        "general_destino" => nl2br($value["sol_detalle"]),
                        "general_interes" => '',
                        "camp_id" => $value["tipo_persona_id"],
                        "camp_nombre" => $value["tipo_persona_nombre"],
                        "prospecto_evaluacion" => $value["sol_evaluacion"],
                        "prospecto_fecha_conclusion" => $value["sol_registro_completado_fecha"],
                        "prospecto_etapa" => ($value["sol_jda_eval"]==0 ? 5 : 22),
                        "prospecto_jda_eval" => $value["sol_jda_eval"]
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        }
        
        if (isset($lst_resultado[0]))
        {
            $arrResumen = $this->mfunciones_generales->TiempoEtapaResumen($lst_resultado);
            
            // Obtener el Tiempo asignado de la etapa
            $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa($this->codigo_etapa);
            $tiempo_etapa_asignado = $arrEtapa[0]['etapa_tiempo'];
        }
        else
        {
            $lst_resultado[0] = array();
            $arrResumen[0] = array();
            $tiempo_etapa_asignado = 0;
        }
        
        // Se establece una variable de SESSION para determinar en que bandeja se encuentra el usuario y al Observar el documento, regresar allí
        
        $_SESSION['aux_flag_reporte_return'] = 0; // <-- Se indica que no se está en reportes
        
        $_SESSION['direccion_bandeja_actual'] = 'Bandeja/Verificacion/Ver';
        $_SESSION['dato_etapa_actual'] = $this->codigo_etapa;
        
        $data["arrRespuesta"] = $lst_resultado;
        $data["arrResumen"] = $arrResumen;
        $data["tiempo_etapa_asignado"] = $tiempo_etapa_asignado;
        
        $this->load->view('bandeja_verificacion_requisitos/view_bandeja_ver', $data);
    }
    
    public function JDA_Evaluacion() {
        
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
        
        $codigo = $this->input->post('codigo', TRUE);
        $codigo_tipo_persona = $this->input->post('codigo_tipo_persona', TRUE);

        // 1. Datos del Lead
        switch ((int)$codigo_tipo_persona) {
            case 6:

                // Solicitud de Crédito
                $this->load->model('mfunciones_microcreditos');

                $arrProspecto = $this->mfunciones_microcreditos->BandejaSupervisionLeads(-1, ' AND sc.sol_id=' . $codigo);
                $arrProspecto[0]['prospecto_id'] = $arrProspecto[0]['sol_id'];
                $arrProspecto[0]['camp_id'] = $arrProspecto[0]['tipo_persona_id'];
                $arrProspecto[0]['camp_nombre'] = $arrProspecto[0]['tipo_persona_nombre'];
                $arrProspecto[0]['usuario_nombre'] = $arrProspecto[0]['ejecutivo_nombre'];
                $arrProspecto[0]['prospecto_jda_eval'] = $arrProspecto[0]['sol_jda_eval'];
                $arrProspecto[0]['prospecto_jda_eval_texto'] = $arrProspecto[0]['sol_jda_eval_texto'];
                $arrProspecto[0]['general_solicitante'] = $arrProspecto[0]["sol_primer_nombre"] . ' ' . $arrProspecto[0]["sol_primer_apellido"] . ' ' . $arrProspecto[0]["sol_segundo_apellido"];
                $arrProspecto[0]['general_ci'] = $arrProspecto[0]["sol_ci"] . ' ' . $arrProspecto[0]["sol_complemento"] . ' ' . ((int)$arrProspecto[0]['sol_extension']==-1 ? '' : $this->mfunciones_generales->GetValorCatalogoDB($arrProspecto[0]['sol_extension'], 'cI_lugar_emisionoextension'));
                $arrProspecto[0]['prospecto_consolidar_fecha'] = $arrProspecto[0]['sol_consolidado_fecha'];

                break;

            default:

                // Datos del Prospecto
                $arrProspecto = $this->mfunciones_logica->ObtenerInfoProspecto($codigo);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrProspecto);

                $arrProspecto[0]['general_ci'] = $arrProspecto[0]["general_ci"] . $this->mfunciones_generales->GetValorCatalogo($DatosProspecto[0]["general_ci_extension"], 'extension_ci');
                
                break;
        }
        
        if(!isset($arrProspecto[0])) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array('prospecto_jda_eval' => $arrProspecto[0]['prospecto_jda_eval'], 'prospecto_jda_eval_texto' => $arrProspecto[0]['prospecto_jda_eval_texto']));

        $data["arrRespuesta"] = $arrProspecto;
        
        $data["codigo_tipo_persona"] = $codigo_tipo_persona;

        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

        $this->load->view('bandeja_verificacion_requisitos/view_jda_evaluacion', $data);
    }
    
    public function JDA_Evaluacion_Guardar() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_microcreditos');
        
        if(!isset($_POST['estructura_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $codigo_prospecto = $this->input->post('estructura_id', TRUE);
        $prospecto_jda_eval = $this->input->post('prospecto_jda_eval', TRUE);
        $prospecto_jda_eval_texto = htmlspecialchars($this->input->post('prospecto_jda_eval_texto', TRUE));
        
        $codigo_tipo_persona = $this->input->post('codigo_tipo_persona', TRUE);

        if((int)$prospecto_jda_eval != 1 && (int)$prospecto_jda_eval != 2)
        {
            js_error_div_javascript('Debe seleccionar una opción para la evaluación.');
            exit();
        }
        
        $accion_usuario = $_SESSION["session_informacion"]["login"];
        $codigo_usuario = $_SESSION["session_informacion"]["codigo"];
        $accion_fecha = date('Y-m-d H:i:s');
        
        switch ((int)$codigo_tipo_persona) {
            case 6:
                
                $arrResultado3 = $this->mfunciones_microcreditos->VerificaSolicitudConsolidado($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                if (!isset($arrResultado3[0])) 
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }
                
                $this->mfunciones_microcreditos->update_JDA_Eval_Sol($codigo_prospecto, $prospecto_jda_eval, $prospecto_jda_eval_texto, $codigo_usuario, $accion_usuario, $accion_fecha);
                
                break;
            
            default:
                
                /***** REGIONALIZACIÓN: Valida si el prospecto pertenece a la región ******/
                if(!$this->mfunciones_generales->VerificaProspectoRegion($codigo_prospecto))
                {
                    js_error_div_javascript($this->lang->line('regionaliza_NoRegion'));
                    exit();
                }
                
                $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                if (!isset($arrResultado3[0])) 
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }
                
                // PASO 2: Se actualiza la evaluación del JDA
                
                $this->mfunciones_microcreditos->update_JDA_Eval($codigo_prospecto, $prospecto_jda_eval, $prospecto_jda_eval_texto, $codigo_usuario, $accion_usuario, $accion_fecha);

                $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, ((int)$prospecto_jda_eval==1 ? 22 : 23), $arrResultado3[0]['prospecto_etapa'], $accion_usuario, $accion_fecha, 2);

                /***  REGISTRAR SEGUIMIENTO ***/
                $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, ((int)$prospecto_jda_eval==1 ? 22 : 23), 13, 'JDA Evaluación: ' . $this->mfunciones_generales->GetValorCatalogo($prospecto_jda_eval, 'prospecto_evaluacion') . '.' . ((int)$prospecto_jda_eval==2 ? ' Fin del Flujo.' : ''), $accion_usuario, $accion_fecha);
                
                break;
        }
        
        $this->Bandeja_Ver();
    }
    
    public function DesembCOBISForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_microcreditos');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // 0=Insert    1=Update

        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $codigo = $this->input->post('codigo', TRUE);
        $codigo_tipo_persona = $this->input->post('codigo_tipo_persona', TRUE);

        switch ((int)$codigo_tipo_persona) {
            case 6:

                // Solicitud de Crédito
                $this->load->model('mfunciones_microcreditos');

                $arrProspecto = $this->mfunciones_microcreditos->BandejaSupervisionLeads(-1, ' AND sc.sol_id=' . $codigo);
                $arrProspecto[0]['prospecto_id'] = $arrProspecto[0]['sol_id'];
                $arrProspecto[0]['camp_id'] = $arrProspecto[0]['tipo_persona_id'];
                $arrProspecto[0]['camp_nombre'] = $arrProspecto[0]['tipo_persona_nombre'];
                $arrProspecto[0]['usuario_nombre'] = $arrProspecto[0]['ejecutivo_nombre'];
                $arrProspecto[0]['prospecto_jda_eval'] = $arrProspecto[0]['sol_jda_eval'];
                $arrProspecto[0]['prospecto_jda_eval_texto'] = $arrProspecto[0]['sol_jda_eval_texto'];
                $arrProspecto[0]['general_solicitante'] = $arrProspecto[0]["sol_primer_nombre"] . ' ' . $arrProspecto[0]["sol_primer_apellido"] . ' ' . $arrProspecto[0]["sol_segundo_apellido"];
                $arrProspecto[0]['general_ci'] = $arrProspecto[0]["sol_ci"] . ' ' . $arrProspecto[0]["sol_complemento"] . ' ' . ((int)$arrProspecto[0]['sol_extension']==-1 ? '' : $this->mfunciones_generales->GetValorCatalogoDB($arrProspecto[0]['sol_extension'], 'cI_lugar_emisionoextension'));
                $arrProspecto[0]['prospecto_consolidar_fecha'] = $arrProspecto[0]['sol_consolidado_fecha'];
                
                $arrProspecto[0]['prospecto_jda_eval_usuario'] = $arrProspecto[0]['sol_jda_eval_usuario'];
                $arrProspecto[0]['prospecto_jda_eval_fecha'] = $arrProspecto[0]['sol_jda_eval_fecha'];

                $arrProspecto[0]['prospecto_etapa'] = ((int)$arrProspecto[0]['sol_jda_eval']==1 ? 22 : 5);
                
                $sol_cred = new stdClass();
                
                $sol_cred->sol_id = $arrProspecto[0]['sol_id'];
                $sol_cred->sol_moneda = $arrProspecto[0]['sol_moneda'];
                $sol_cred->sol_monto = $arrProspecto[0]['sol_monto'];
                
                break;

            default:

                // Datos del Prospecto
                $arrProspecto = $this->mfunciones_logica->ObtenerInfoProspecto($codigo);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrProspecto);
                
                // Check si el prospecto fue creado a través de una solicitud de crédito
                $sol_cred = $this->mfunciones_microcreditos->ObtenerSol_from_Pros($codigo);
                
                break;
        }
        
        if(!isset($arrProspecto[0])) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        // Validar si la etapa esta como "JDA Aprobado" para permitir el registro de Desembolso en COBIS
        if((int)$arrProspecto[0]['prospecto_etapa'] != 22) 
        {
            js_error_div_javascript('El registro debe tener la evaluación del JDA como "Aprobado" para poder registrar el ' . $this->lang->line('prospecto_desembolso'));
            exit();
        }

        $data["sol_cred"] = $sol_cred;
        
        $data["codigo_tipo_persona"] = $codigo_tipo_persona;
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());

        $data["arrRespuesta"] = $arrProspecto;

        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

        $this->load->view('bandeja_verificacion_requisitos/view_desembolso_cobis', $data);
    }
    
    public function DesembCOBIS_Guardar() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_microcreditos');
        
        if(!isset($_POST['estructura_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $codigo_prospecto = $this->input->post('estructura_id', TRUE);
        $prospecto_desembolso_monto = $this->input->post('prospecto_desembolso_monto', TRUE);
        
        $codigo_tipo_persona = $this->input->post('codigo_tipo_persona', TRUE);

        if((int)$prospecto_desembolso_monto < 1)
        {
            js_error_div_javascript('Debe registrar el ' . $this->lang->line('prospecto_desembolso_monto'));
            exit();
        }
        
        $accion_usuario = $_SESSION["session_informacion"]["login"];
        $codigo_usuario = $_SESSION["session_informacion"]["codigo"];
        $accion_fecha = date('Y-m-d H:i:s');
        
        switch ((int)$codigo_tipo_persona) {
            case 6:
                
                $arrResultado3 = $this->mfunciones_microcreditos->VerificaSolicitudConsolidado($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                if (!isset($arrResultado3[0])) 
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }
                
                $this->mfunciones_microcreditos->update_DesembCOBIS_Sol($codigo_prospecto, $prospecto_desembolso_monto, $codigo_usuario, $accion_usuario, $accion_fecha);
                
                break;
            
            default:
                
                /***** REGIONALIZACIÓN: Valida si el prospecto pertenece a la región ******/
                if(!$this->mfunciones_generales->VerificaProspectoRegion($codigo_prospecto))
                {
                    js_error_div_javascript($this->lang->line('regionaliza_NoRegion'));
                    exit();
                }

                $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                if (!isset($arrResultado3[0])) 
                {
                    js_error_div_javascript($this->lang->line('NoAutorizado'));
                    exit();
                }

                $this->mfunciones_microcreditos->update_DesembCOBIS($codigo_prospecto, $prospecto_desembolso_monto, $codigo_usuario, $accion_usuario, $accion_fecha);

                $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 24, $arrResultado3[0]['prospecto_etapa'], $accion_usuario, $accion_fecha, 2);
                /***  REGISTRAR SEGUIMIENTO ***/
                $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, 24, 12, $this->lang->line('prospecto_desembolso') . '. Fin del flujo.', $accion_usuario, $accion_fecha);
                
                break;
        }
        
        $this->Bandeja_Ver();
    }
}
?>