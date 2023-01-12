<?php
/**
 * @file 
 * Codigo que implementa el controlador para LAS ACCIONES DE EXCEPCIÓN
 * @brief  CONTROLADOR DE LOGUEO
 * @author JRAD
 * @date Mar 21, 2014
 * @copyright 2017 JRAD
 */
/**
 * Controlador para la administracion del LAS ACCIONES DE EXCEPCIÓN
 * @brief CONTROLADOR DEL LOGUEO
 * @class Login_controller 
 */
class Detalle_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 0;

    function __construct() {
        parent::__construct();
    }
    /**
     * carga la vista para el formulario de login
     * @brief CARGAR LOGIN
     * @author JRAD
     * @date Mar 21, 2014     
     */
    
    public function EmpresaDetalle() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // 0=Insert    1=Update
        
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            if(!$this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_EMPRESA))
            {
                js_error_div_javascript($this->lang->line('NoAutorizadoPerfil'));
                exit();
            }
            else 
            {
                $codigo_empresa = $this->input->post('codigo', TRUE);

                $arrResultado = $this->mfunciones_generales->GetDatosEmpresa($codigo_empresa);

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
                
                $data["arrRespuesta"] = $lst_resultado;

                $this->load->view('empresa/view_empresa_detalle', $data);
            }
        }
    }
}
?>