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

            default:
                
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            
                break;
        }
        
        $this->load->view('registros/view_principal_ver', $data);
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
        
        // Listado de Versiones
        $arrVersiones = $this->mfunciones_logica->ObtenerListaVersiones($estructura_id, -1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrVersiones);
        
        $data["arrRespuesta"] = $lst_resultado;
        $data["arrVersiones"] = $arrVersiones;
        
        $this->load->view('registros/view_inicio', $data);
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
        
        $accion_usuario = $this->mfunciones_generales->ObtenerUsuarioAuditoria($estructura_id, 'prospecto');
        $accion_fecha = date('Y-m-d H:i:s');
        
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
            
        // PASO 2: Se obtiene las vistas y sus colindantes
        
        $array_rubro = $this->mfunciones_generales->getVistasRubro($codigo_rubro);
        $vista_prospecto = $this->mfunciones_generales->paso_ant_sig($vista_actual, $array_rubro);
        
        // PASO 3: Se guarda la Vista Actual (siempre y cuando no se haya enviado "sin_guardar"
        
        if($sin_guardar == 0)
        {
            $vista_actual = str_replace("view_", "", $vista_actual);
            
                // Validación de campos
                    
                $separador = '<br /> - ';
                $error_texto = '';
            
            switch ($vista_actual) {
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
                        
                        if($operacion_antiguedad == '') { $error_texto .= $separador . $this->lang->line('operacion_antiguedad'); }
                        if($operacion_tiempo == '') { $error_texto .= $separador . $this->lang->line('operacion_tiempo'); }
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
                                    $operacion_antiguedad,
                                    $operacion_tiempo,
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
                                case 2: if($suma_dias>$frec_dia_semana_monto2 || $suma_dias<$frec_dia_semana_monto3) { $error_texto .= $separador . 'El monto REGULAR deb estar entre BUENO y MALO.'; } break;
                                case 3: if($suma_dias>$frec_dia_semana_monto3) { $error_texto .= $separador . 'El monto MALO no puede ser Mayor a REGULAR.'; } break;
                                default: $error_texto .= $separador . 'No seleccionó opción'; break;
                            }
                            
                            if($frec_dia_semana_monto2<$frec_dia_semana_monto3)
                            {
                                $error_texto .= $separador . 'Revise los importes de los criterios tengan coherencia.';
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
                    $operativos_sal_aguinaldos_monto = $this->input->post('operativos_sal_aguinaldos_monto', TRUE);
                    $operativos_sal_libre1_texto = $this->input->post('operativos_sal_libre1_texto', TRUE);
                    $operativos_sal_libre1_monto = $this->input->post('operativos_sal_libre1_monto', TRUE);
                    $operativos_sal_libre2_texto = $this->input->post('operativos_sal_libre2_texto', TRUE);
                    $operativos_sal_libre2_monto = $this->input->post('operativos_sal_libre2_monto', TRUE);
                    $operativos_sal_libre3_texto = $this->input->post('operativos_sal_libre3_texto', TRUE);
                    $operativos_sal_libre3_monto = $this->input->post('operativos_sal_libre3_monto', TRUE);
                    $operativos_sal_libre4_texto = $this->input->post('operativos_sal_libre4_texto', TRUE);
                    $operativos_sal_libre4_monto = $this->input->post('operativos_sal_libre4_monto', TRUE);
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
                    
                    if($operativos_alq_energia_monto == '' || $operativos_alq_agua_monto == '' || $operativos_alq_internet_monto == '' || $operativos_alq_combustible_monto == '' || $operativos_sal_aguinaldos_monto == '' || $operativos_otro_transporte_monto == '' || $operativos_otro_licencias_monto == '' || $operativos_otro_alimentacion_monto == '' || $operativos_otro_mant_vehiculo_monto == '' || $operativos_otro_mant_maquina_monto == '' || $operativos_otro_imprevistos_monto == '' || $operativos_otro_otros_monto == '' || $operativos_alq_libre1_monto == '' || $operativos_alq_libre2_monto == '' || $operativos_sal_libre1_monto == '' || $operativos_sal_libre2_monto == '' || $operativos_sal_libre3_monto == '' || $operativos_sal_libre4_monto == '' || $operativos_otro_libre1_monto == '' || $operativos_otro_libre2_monto == '' || $operativos_otro_libre3_monto == '' || $operativos_otro_libre4_monto == '' || $operativos_otro_libre5_monto == '')
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
                    $extra_inventario = $this->input->post('extra_inventario', TRUE);
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
                        $error_texto .= $separador . 'El horario de la jornada laboral es incorrecto.';
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
            $this->mfunciones_logica->Guardar_PasoActual($siguiente_vista, $accion_usuario, $accion_fecha, $estructura_id);
        }
        elseif($siguiente_vista == 'view_home')
        {
            if($tipo_registro != 'unidad_familiar')
            {
                $this->mfunciones_logica->Guardar_PasoActual($vista_actual, $accion_usuario, $accion_fecha, $estructura_id);
            }
            
            js_invocacion_javascript('$("div.modal-backdrop").remove(); Ajax_CargadoGeneralPagina("Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");');
            exit();
        }
        
        // PASO 5: Cargar datos de la vista siguiente
        
        switch ($siguiente_vista) {
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
                                    "aclarar_credito" => $value["aclarar_credito"]
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
                            "extra_inventario" => $value["extra_inventario"],
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
            $this->mfunciones_generales->GuardarHitoFormulario($estructura_id, $codigo_rubro, $accion_usuario, $accion_fecha);
        }
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
        
        $data["arrRespuesta"] = $lst_resultado;
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        // DATOS PRONCIPALES
        
        $data['estructura_id'] = $estructura_id;
        $data['codigo_rubro'] = $codigo_rubro;
        $data['vista_actual'] = $siguiente_vista;
        
            // Auxiliar de Registro
            $data['tipo_registro'] = $tipo_registro;
        
        $this->load->view('registros/' . $siguiente_vista, $data);
        
    }
    
    public function Margen_Lista() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        
        $tab = $this->input->post('tab', TRUE);
        
        $producto_id = -1;
        
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
        
        $arrResultado2 = $this->mfunciones_logica->ObtenerListaProductos($estructura_id, $producto_id, -1);
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
            
        $data["arrRespuesta"] = $lst_resultado;
        
        $data["producto_id"] = $producto_id;
        $data["producto_opcion"] = $producto_opcion;
        
        $data["estructura_id"] = $estructura_id;
        
        $data["tab"] = $tab;
        
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
        $producto_seleccion = $this->input->post('producto_seleccion', TRUE);
        
        $producto_opcion = $this->input->post('producto_opcion', TRUE);
        $producto_costo_medida_unidad = $this->input->post('producto_costo_medida_unidad', TRUE);
        $producto_costo_medida_cantidad = $this->input->post('producto_costo_medida_cantidad', TRUE);
        $producto_costo_medida_precio = $this->input->post('producto_costo_medida_precio', TRUE);
        
        // Validación de campos
                    
        $separador = '<br /> - ';
        $error_texto = '';

        switch ($tab) {
            case "1":

                if($producto_frecuencia == '' || $producto_venta_cantidad == '' || $producto_venta_precio == '')
                {
                    js_error_div_javascript($this->lang->line('CamposObligatorios') . ' La frecuencia y todos los datos de venta debe registrarse, caso contrario coloque 0');
                    exit();
                }

                break;
                
            case "2":

                if($producto_nombre == '' || $producto_venta_medida == '' || $producto_compra_cantidad == '' || $producto_compra_medida == '' || $producto_compra_precio == '' || $producto_unidad_venta_unidad_compra == '' || $producto_categoria_mercaderia == '' || $producto_seleccion == '')
                {
                    js_error_div_javascript($this->lang->line('CamposObligatorios') . ' La descripción y todos los datos del item debe registrarse, caso contrario coloque 0');
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
            js_error_div_javascript('No puede eliminarlo aún. Este registro tienen asociado ' . count($arrResultado) . ' costo(s) dependiente(s), si requiere eliminarlo primero debe eliminar todos sus registros asociados.');
            exit();
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
            
        js_invocacion_javascript('Margen_Lista('. $arrProducto[0]['prospecto_id'] . ', "' . 2 . '"); Elementos_General_MostrarElementoFlotante(false); $("#tab2").click(); $("#ResultadoDetalleProducto").html(""); $("#ResultadoDetalleProducto").hide(); $("#ResultadoMargenUtilidad").show(); DetalleProducto(' . $estructura_id . ');');
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
            
        js_invocacion_javascript('Margen_Lista('. $arrProducto[0]['prospecto_id'] . ', "' . 2 . '"); Elementos_General_MostrarElementoFlotante(false); $("#tab2").click(); $("#ResultadoDetalleProducto").html(""); $("#ResultadoDetalleProducto").hide(); $("#ResultadoMargenUtilidad").show(); DetalleProducto(' . $estructura_id . ');');
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
}
?>