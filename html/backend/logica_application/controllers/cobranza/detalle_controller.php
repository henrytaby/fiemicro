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
 * Controlador para SOLICITUD DE PROSPECTO
 * @brief CONTROLADOR DEL LOGUEO
 * @class Login_controller 
 */
class Detalle_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 0;

    function __construct() {
        parent::__construct();
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model("mfunciones_cobranzas");
    }
    /**
     * carga la vista para el formulario de login
     * @brief CARGAR LOGIN
     * @author JRAD
     * @date Mar 21, 2014     
     */
    
    public function NormDetalle() {
        
        $this->load->model('mfunciones_microcreditos');
        
        $estado = 0;
        
        if(isset($_POST['codigo']))
        {
            $codigo = $this->input->post('codigo', TRUE);
        }
        else
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        $arrResultado = $this->mfunciones_cobranzas->ObtenerDetalleRegistro($codigo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
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
                $item_valor = array(
                    
                    "agente_nombre" => $value["agente_nombre"],
                    "agente_agencia" => $value["estructura_regional_nombre"],
                    "agente_codigo" => $value["agente_codigo"],
                    
                    "codigo_agencia_fie" => $value["codigo_agencia_fie"],
                    "registro_num_proceso" => $value["registro_num_proceso"],
                    
                    "camp_id" => 13, // <-- Valor constante
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "norm_ultimo_paso" => $value["norm_ultimo_paso"],
                    "norm_consolidado" => $value["norm_consolidado"],
                    
                    "cv_fecha" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["cv_fecha"]),
                    "cv_fecha_check" => ($this->mfunciones_generales->VerificaFechaY_M_D_H_M_S($value["cv_fecha"])),
                    "cv_fecha_compromiso" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y($value["cv_fecha_compromiso"]),
                    "cv_fecha_compromiso_check" => $this->mfunciones_cobranzas->checkFechaComPago_vencido($value["cv_fecha_compromiso"], $value["norm_consolidado"]),
                    "cv_resultado" => $this->mfunciones_generales->GetValorCatalogoDB($value["cv_resultado"], 'cobranzas_resultado_visita'),
                    
                    "norm_id" => $value["norm_id"],
                    "norm_estado" => $this->mfunciones_cobranzas->GetValorCatalogo($value['norm_estado'], 'norm_estado'),
                    "norm_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["norm_fecha"]),
                    "norm_fecha_plano" => $value["norm_fecha"],
                    "norm_consolidado" => $value["norm_consolidado"],
                    "norm_consolidado_usuario" => $value["norm_consolidado_usuario"],
                    "norm_consolidado_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["norm_consolidado_fecha"]),
                    "norm_consolidado_fecha_plano" => $value["norm_consolidado_fecha"],
                    "norm_consolidado_geo" => $value["norm_consolidado_geo"],
                    "norm_registro_completado_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["norm_registro_completado_fecha"]),
                    "norm_observado_app" => $value["norm_observado_app"],
                    
                    "norm_nombre_completo" => $value["norm_primer_nombre"] . ' ' . $value["norm_segundo_nombre"] . ' ' .$value["norm_primer_apellido"] . ' ' . $value["norm_segundo_apellido"],
                    "norm_primer_nombre" => $value["norm_primer_nombre"],
                    "norm_segundo_nombre" => $value["norm_segundo_nombre"],
                    "norm_primer_apellido" => $value["norm_primer_apellido"],
                    "norm_segundo_apellido" => $value["norm_segundo_apellido"],
                    "norm_cel" => $value["norm_cel"],
                    "norm_actividad" => $value["norm_actividad"],
                    "norm_rel_cred_codigo" => $value["norm_rel_cred"],
                    "norm_rel_cred" => $this->mfunciones_cobranzas->GetValorCatalogo($value["norm_rel_cred"], 'norm_rel_cred'),
                    "norm_rel_cred_otro" => $value["norm_rel_cred_otro"],
                    "norm_finalizacion" => $this->mfunciones_cobranzas->GetValorCatalogo($value["norm_finalizacion"], 'norm_finalizacion'),
                    
                );
                $lst_resultado[$i] = $item_valor;
                
                $i++;
            }
            
            // Direcciones
            $arrDirecciones = $this->mfunciones_cobranzas->getDireccionesRegistroAll($codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDirecciones);

            if (isset($arrDirecciones[0]))
            {
                $i = 0;

                foreach ($arrDirecciones as $key => $value) {
                    $item_valor = array(
                        "rd_id" => $value["rd_id"],
                        "tipo_persona_id" => $value["tipo_persona_id"],
                        "codigo_registro" => $value["codigo_registro"],
                        "rd_tipo_codigo" => $value["rd_tipo"],
                        "rd_tipo" => $this->mfunciones_cobranzas->GetValorCatalogo($value['rd_tipo'], 'rd_tipo'),
                        "rd_dir_departamento" => $this->mfunciones_generales->GetValorCatalogoDB($value['rd_dir_departamento'], 'dir_departamento'),
                        "rd_dir_provincia" => $this->mfunciones_generales->GetValorCatalogoDB($value['rd_dir_provincia'], 'dir_provincia'),
                        "rd_dir_localidad_ciudad" => $this->mfunciones_generales->GetValorCatalogoDB($value['rd_dir_localidad_ciudad'], 'dir_localidad_ciudad'),
                        "rd_cod_barrio" => $this->mfunciones_generales->GetValorCatalogoDB($value['rd_cod_barrio'], 'dir_barrio_zona_uv'),
                        "rd_cod_barrio_codigo" => $value["rd_cod_barrio"],
                        "rd_direccion" => $value["rd_direccion"],
                        "rd_edificio" => $value["rd_edificio"],
                        "rd_numero" => $value["rd_numero"],
                        "rd_referencia_texto" => $value["rd_referencia_texto"],
                        "rd_referencia" => $this->mfunciones_cobranzas->GetValorCatalogo($value['rd_referencia'], 'rd_referencia'),
                        "rd_referencia_codigo" => $value["rd_referencia"],
                        "rd_geolocalizacion" => $value["rd_geolocalizacion"],
                        "rd_geolocalizacion_img" => $value["rd_geolocalizacion_img"],
                        "rd_croquis" => $this->mfunciones_microcreditos->validateIMG_simple($value['rd_croquis']),
                        "rd_trabajo_lugar_codigo" => $value["rd_trabajo_lugar"],
                        "rd_trabajo_lugar" => $this->mfunciones_microcreditos->GetValorCatalogo($value['rd_trabajo_lugar'], 'sol_trabajo_lugar'),
                        "rd_trabajo_lugar_otro" => $value["rd_trabajo_lugar_otro"],
                        "rd_trabajo_realiza_codigo" => $value["rd_trabajo_realiza"],
                        "rd_trabajo_realiza" => $this->mfunciones_microcreditos->GetValorCatalogo($value['rd_trabajo_realiza'], 'sol_trabajo_realiza'),
                        "rd_trabajo_realiza_otro" => $value["rd_trabajo_realiza_otro"],
                        "rd_dom_tipo_codigo" => $value["rd_dom_tipo"],
                        "rd_dom_tipo" => $this->mfunciones_microcreditos->GetValorCatalogo($value['rd_dom_tipo'], 'sol_dom_tipo'),
                        "rd_dom_tipo_otro" => $value["rd_dom_tipo_otro"]
                    );
                    $lst_resultado_direcciones[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                $lst_resultado_direcciones[0] = $arrDirecciones;
            }

            // Visitas registradas
            $arrVisita = $this->mfunciones_cobranzas->getVisitasRegistro($codigo, -1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrVisita);

            if (isset($arrVisita[0]))
            {
                $i = 0;

                foreach ($arrVisita as $key => $value) {

                    $check_geo = $this->mfunciones_microcreditos->validateGEO_simple($value['cv_checkin_geo']);

                    $item_valor = array(
                        "cv_id" => $value["cv_id"],
                        "cv_fecha" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["cv_fecha"]),
                        "cv_resultado" => $this->mfunciones_generales->GetValorCatalogoDB($value["cv_resultado"], 'cobranzas_resultado_visita'),
                        "cv_fecha_compromiso" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y($value["cv_fecha_compromiso"]),
                        "cv_observaciones" => nl2br($value["cv_observaciones"]),
                        "cv_checkin" => ($value["cv_checkin"]==1 && $check_geo->lat!=0 ? 1 : 0),
                        "cv_checkin_fecha" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["cv_checkin_fecha"]),
                        "cv_checkin_geo" => $value["cv_checkin_geo"]
                    );
                    $lst_resultado_visita[$i] = $item_valor;

                    $i++;
                }

                $visitas_contador = $i;
            }
            else 
            {
                $lst_resultado_visita[0] = $arrVisita;

                $visitas_contador = 0;
            }

            $data["arrDirecciones"] = $lst_resultado_direcciones;

            $data["arrVisita"] = $lst_resultado_visita;
            
        }
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }
        
        $data["arrRespuesta"] = $lst_resultado;
        
        $this->load->view('cobranza/view_norm_detalle', $data);
    }
    
    public function Reportes_Agregar_Filtro() {
        
        $tipo_bandeja = $this->input->post('tipo_bandeja', TRUE);
        
        $campos = $this->mfunciones_cobranzas->Obtener_Campos_Filtro_Norm($tipo_bandeja);
        $this->load->view('cobranza/view_reportes_agregar_filtro',array("campos"=>$campos));
    }

    public function Reportes_Valores_Filtro() {
        die(json_encode($this->mfunciones_cobranzas->Obtener_Valores_Filtro_Norm($this->input->get_post("campo"))));
    }
}
?>