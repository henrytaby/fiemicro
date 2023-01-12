<?php
/**
 * @file 
 * Codigo que implementa el controlador para EL REGISTRO DE EMPRESAS
 * @brief  CONTROLADOR DE LOGUEO
 * @author JRAD
 * @date Mar 21, 2014
 * @copyright 2017 JRAD
 */
/**
 * Controlador para la administracion de EL REGISTRO DE EMPRESAS
 * @brief CONTROLADOR DEL LOGUEO
 * @class Login_controller 
 */
class Empresa_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 32;

    function __construct() {
        parent::__construct();
    }
    /**
     * carga la vista para el formulario de login
     * @brief CARGAR LOGIN
     * @author JRAD
     * @date Mar 21, 2014     
     */
    
    public function Empresa_Ver() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        // Lista los Ejecutivos de Cuenta
        $arrEjecutivo = $this->mfunciones_logica->ObtenerEjecutivo(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEjecutivo);
        $data["arrEjecutivo"] = $arrEjecutivo;
        
        $this->load->view('empresa/view_empresa_form', $data);
    }
    
    public function VerificarNIT() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            $codigo = (int)$this->input->post('codigo', TRUE);
            
            $nit = $codigo;
            
            // Variable que indica si esta registrado sólo en PayStudio
            $registrado_sistema = 0;
            
            // Se usa esta variable para verificar si se contró el NIT en PayStudio o en el Sistema
            $swNIT = 0;

                // A. SE PREGUNTA A PAYSTUDIO (NAZIR) A TRAVÉS DE SU WEB SERVICE SI EL NIT EXISTE Y POBLAR LOS CAMPOS

                $RespuestaWS = $this->mfunciones_generales->ConsultaWebServiceNIT($nit);
                if (isset($RespuestaWS[0])) 
                {
                    $swNIT = 1;
                    $lst_resultado1 = $RespuestaWS;
                    $registrado_sistema = 1;
                }
                
                // Detalle del Comercio
                $arrResultado1 = $this->mfunciones_logica->ObtenerEmpresaNIT($nit);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                if (isset($arrResultado1[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado1 as $key => $value) 
                    {
                        $item_valor = array(
                            "empresa_id" => $value["empresa_id"],
                            "ejecutivo_id" => $value["ejecutivo_id"],
                            "empresa_consolidada" => $value["empresa_consolidada"],
                            "empresa_categoria_codigo" => $value["empresa_categoria"],
                            "empresa_categoria_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["empresa_categoria"], 'empresa_categoria'),
                            "empresa_nombre" => $value["empresa_nombre"],
                            "empresa_tipo_sociedad_codigo" => $value["empresa_tipo_sociedad"],
                            "empresa_tipo_sociedad_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_tipo_sociedad"], 'TPS'),
                            "empresa_rubro_codigo" => $value["empresa_rubro"],
                            "empresa_rubro_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_rubro"], 'RUB'),
                            "empresa_perfil_comercial_codigo" => $value["empresa_perfil_comercial"],
                            "empresa_perfil_comercial_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_rubro"], 'PEC'),
                            "empresa_mcc_codigo" => $value["empresa_mcc"],
                            "empresa_mcc_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_mcc"], 'MCC'),
                            "ejecutivo_nombre" => $value["ejecutivo_nombre"],
                            "usuario_id" => $value["usuario_id"]
                        );
                        $lst_resultado1[$i] = $item_valor;

                        $i++;
                    }

                    $swNIT = 1;
                    
                    $registrado_sistema = 0;
                }

            if($swNIT == 0)
            {
                $lst_resultado1 = array();
            }
            
            $data['registrado_sistema'] = $registrado_sistema;
            $data["nit"] = $nit;
            $data["arrRespuesta"] = $lst_resultado1;

            $this->load->view('empresa/view_empresa_resultado', $data);
        }
    }
    
    public function Empresa_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_POST['solicitud_nit']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // 1. SE CAPTURAN LOS DATOS DEL PASO 1 Y 2
            
            $nit = $this->input->post('solicitud_nit', TRUE);
            $codigo_ejecutivo = $this->input->post('codigo_ejecutivo', TRUE);
            
            // 2. SE VALIDA LOS CAMPOS
            
            if((int)$nit == 0 || (int)$codigo_ejecutivo == -1)
            {
                js_error_div_javascript($this->lang->line('CamposObligatorios'));
                exit();
            }
            
            // A. PRIMERO SE PREGUNTA SI ESTA REGISTRADA LA EMPRESA EN EL SISTEMA, EN CASO QUE SÍ SE DEBE MOSTRAR UN MENSAJE DE ERROR
            $arrResultado1 = $this->mfunciones_logica->ObtenerEmpresaNIT($nit);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

            if (isset($arrResultado1[0])) 
            {
                js_error_div_javascript($this->lang->line('verifique_nit_registrado'));
                exit();
            }

            // B. SE PREGUNTA A PAYSTUDIO (NAZIR) A TRAVÉS DE SU WEB SERVICE SI EL NIT EXISTE Y POBLAR LOS CAMPOS

            $RespuestaWS = $this->mfunciones_generales->ConsultaWebServiceNIT($nit);
            if (isset($RespuestaWS[0])) 
            {   
                $lst_resultado1 = $RespuestaWS;
            }
            else
            {
                js_error_div_javascript($this->lang->line('no_nit_encontrado'));
                exit();
            }

            // 3. Se establecen las variables

            $accion_usuario = $_SESSION["session_informacion"]["login"];
            $accion_fecha = date('Y-m-d H:i:s');
            
            $empresa_nit = $lst_resultado1[0]['parent_nit'];
            $empresa_adquiriente = $lst_resultado1[0]['parent_adquiriente_codigo'];
            $empresa_tipo_sociedad = $lst_resultado1[0]['parent_tipo_sociedad_codigo'];
            $empresa_nombre_legal = $lst_resultado1[0]['parent_nombre_legal'];
            $empresa_nombre_fantasia = $lst_resultado1[0]['parent_nombre_fantasia'];
            $empresa_rubro = $lst_resultado1[0]['parent_rubro_codigo'];
            $empresa_perfil_comercial = $lst_resultado1[0]['parent_perfil_comercial_codigo'];
            $empresa_mcc = $lst_resultado1[0]['parent_mcc_codigo'];
            
            // 4. Se procede a Guardar en la tabla Empresa
            
            $this->mfunciones_logica->InsertarEmpresaPayStudio($codigo_ejecutivo, $empresa_nit, $empresa_adquiriente, $empresa_tipo_sociedad, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $accion_usuario, $accion_fecha);
            
            $this->load->view('empresa/view_empresa_guardado');
                        
        }
    }
}
?>