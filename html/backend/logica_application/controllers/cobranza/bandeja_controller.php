<?php
/**
 * @file 
 * Codigo que implementa el controlador para la gestión de Casos Normalizador/Cobrador
 * @brief BANDEJA DE LA INSTANCIA
 * @author JRAD
 * @date 2022
 * @copyright 2022 JRAD
 */
/**
 * Controlador para la gestión de Casos Normalizador/Cobrador
 * @brief BANDEJA DE LA INSTANCIA
 * @class Bandeja_controller 
 */
class Bandeja_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 53;

    function __construct() {
        parent::__construct();
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model("mfunciones_cobranzas");
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
    
    public function Bandeja_Ver() {
        
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
            /***** REGIONALIZACIÓN INICIO ******/                
                $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
                $data['nombre_region'] = $lista_region->region_nombres_texto;
            /***** REGIONALIZACIÓN FIN ******/
        
        $data['tipo_bandeja'] = 'supervision';
                
        $this->load->view('cobranza/view_bandeja_ver', $data);
    }
    
    public function Reportes_Generar() {
        $parametros = json_decode(urldecode($this->input->get_post("parametros")));
        
        $resultado = $this->mfunciones_cobranzas->Generar_Reporte_Norm($parametros);
        if ($resultado->cuenta <= 0) die($this->lang->line('TablaNoResultadosReporte'));
        if (property_exists($parametros,"exportar") && $parametros->exportar == "pdf") {
            $this->mfunciones_generales->GeneraPDF('cobranza/view_reportes_tabla_plain',array("resultado"=>$resultado, "parametros"=>$parametros), 'L');
            return;
        } else if (property_exists($parametros,"exportar") && $parametros->exportar == "excel") {
            $this->mfunciones_generales->GeneraExcel('cobranza/view_reportes_tabla_plain',array("resultado"=>$resultado, "parametros"=>$parametros));
            return;
        }
        
        $_SESSION['aux_flag_reporte_return'] = 0;
        $_SESSION['direccion_bandeja_actual'] = 'Bandeja/SupNorm/Ver';
        
        $this->load->view('cobranza/view_reportes_tabla',array("resultado"=>$resultado, "parametros"=>$parametros));
        return;
    }
    
    public function NormModCampo_Form() {
        
        if(!isset($_POST['codigo']) || !isset($_POST['tipo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $codigo = (int)$this->input->post('codigo', TRUE);
        $tipo = (int)$this->input->post('tipo', TRUE);
        
        $arraDatos = $this->mfunciones_cobranzas->VerificaNormConsolidado($codigo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arraDatos);
        
        if(!isset($arraDatos[0]))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        switch ($tipo) {
            
            // Modificar Número de Operación
            case 1:

                $titulo_ventana = 'Número de Operación';
                $valor_actual = $arraDatos[0]['registro_num_proceso'];

                break;
            
            // Modificar Agencia Asociada
            case 2:

                $titulo_ventana = 'Agencia Asociada';
                $valor_actual = $this->mfunciones_generales->ObtenerNombreRegionCodigo($arraDatos[0]['codigo_agencia_fie']);
                
                $this->load->model("mfunciones_microcreditos");
                
                // Seleccion de Agencias asociadas
                $aux_agencias = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);

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

                break;

            default:
                
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
                
                break;
        }
        
        $data["codigo"] = $codigo;
        $data["tipo"] = $tipo;
        $data["arrRespuesta"] = $arraDatos;
        $data["valor_actual"] = $valor_actual;
        
        $data["titulo_ventana"] = '<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Modificar ' . $titulo_ventana;
        $data["general_solicitante"] = $arraDatos[0]['norm_primer_nombre'] . ' ' . $arraDatos[0]['norm_segundo_nombre'] . ' ' . $arraDatos[0]['norm_primer_apellido'] . ' ' . $arraDatos[0]['norm_segundo_apellido'];
        
        $this->load->view('cobranza/view_modificar_campo', $data);
    }
    
    public function NormModCampo_Guardar() {
        
        if(!isset($_POST['codigo']) || !isset($_POST['tipo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $codigo = (int)$this->input->post('codigo', TRUE);
        $tipo = (int)$this->input->post('tipo', TRUE);
        $registro_custom_valor = $this->input->post('registro_custom_valor', TRUE);
        
        $arraDatos = $this->mfunciones_cobranzas->VerificaNormConsolidado($codigo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arraDatos);
        
        if(!isset($arraDatos[0]))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        $accion_usuario = $_SESSION["session_informacion"]["login"];
        $accion_fecha = date('Y-m-d H:i:s');
        
        switch ($tipo) {
            
            // Modificar Número de Operación
            case 1:

                $this->load->model("mfunciones_microcreditos");
                if($this->mfunciones_microcreditos->ValidarNumOperacion($registro_custom_valor))
                {
                    js_invocacion_javascript('OcultarAccion();');
                    js_error_div_javascript($this->lang->line('registro_num_proceso_error'));
                    exit();
                }

                $this->mfunciones_cobranzas->update_NornNroOperacion($codigo, $registro_custom_valor, $accion_usuario, $accion_fecha);
                
                $nuevo_valor = $registro_custom_valor;
                $id_celda = 'tdnumproceso_';
                
                break;
            
            // Modificar Agencia Asociada
            case 2:

                // Validar código de agencia recibida
                if((int)$registro_custom_valor <= 0)
                {
                    js_invocacion_javascript('OcultarAccion();');
                    js_error_div_javascript($this->lang->line('ejecutivo_perfil_tipo_errorAgencia'));
                    exit();
                }
                
                // Validar que la agencia sea parte de las asignadas al usuario
                $aux_agencias = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
                
                $arrAgencias = explode(',', str_replace(' ', '', $aux_agencias->region_id));
                
                if (!in_array($registro_custom_valor, $arrAgencias))
                {
                    js_invocacion_javascript('OcultarAccion();');
                    js_error_div_javascript($this->lang->line('ejecutivo_perfil_tipo_errorNoAgencia'));
                    exit();
                }

                $this->mfunciones_cobranzas->setNormAgenciaAsociada($codigo, $registro_custom_valor, $accion_usuario, $accion_fecha);
                
                $nuevo_valor = $this->mfunciones_generales->ObtenerNombreRegionCodigo($registro_custom_valor);;
                $id_celda = 'tdagencia_';
                
                break;

            default:
                
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
                
                break;
        }
        
        $nuevo_valor = '<span style="color: #eea236;">' . $nuevo_valor . ' <br /> <i class="fa fa-check" aria-hidden="true"></i> Modificado</span>';
        
        $nuevo_valor = str_replace('"', '\"', $nuevo_valor);
        
        js_invocacion_javascript('document.getElementById("' . $id_celda . $codigo . '").innerHTML="' . $nuevo_valor . '"; Elementos_General_MostrarElementoFlotante(false);');
    }
}
?>