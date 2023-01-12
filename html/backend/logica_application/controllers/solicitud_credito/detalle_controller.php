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
    }
    /**
     * carga la vista para el formulario de login
     * @brief CARGAR LOGIN
     * @author JRAD
     * @date Mar 21, 2014     
     */
    
    public function SolicitudDetalle() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
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
        
        $vista = $this->input->post('vista', TRUE);
        
        $arrResultado = $this->mfunciones_microcreditos->ObtenerDetalleSolicitudCredito($codigo);
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
                    
                    "agente_nombre" => $value["agente_nombre"] . ((int)$value["ejecutivo_perfil_tipo"]==1 ? '' : ' (Perfil ' . $this->mfunciones_microcreditos->GetValorCatalogo($value["ejecutivo_perfil_tipo"], 'ejecutivo_perfil_tipo') . ')'),
                    "agente_agencia" => $value["estructura_regional_nombre"],
                    "agente_codigo" => $value["agente_codigo"],
                    
                    "sol_id" => $value["sol_id"],
                    "afiliador_id" => $value["afiliador_id"],
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "sol_codigo_rubro_codigo" => $value["sol_codigo_rubro"],
                    "sol_codigo_rubro" => $this->mfunciones_microcreditos->GetValorCatalogo($value["sol_codigo_rubro"], 'aux_rubro'),
                    "sol_estado_codigo" => $value["sol_estado"],
                    "sol_estado" => $this->mfunciones_microcreditos->GetValorCatalogo($value["sol_estado"], 'solicitud_estado'),
                    "sol_asistencia" => $value["sol_asistencia"],
                    "codigo_agencia_fie" => $value["codigo_agencia_fie"],
                    "sol_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["sol_fecha"]),
                    "sol_fecha_plano" => $value["sol_fecha"],
                    "sol_asignado" => $value["sol_asignado"],
                    "sol_asignado_usuario" => $value["sol_asignado_usuario"],
                    "sol_asignado_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["sol_asignado_fecha"]),
                    "sol_checkin" => $value["sol_checkin"],
                    "sol_checkin_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["sol_checkin_fecha"]),
                    "sol_checkin_geo" => $value["sol_checkin_geo"],
                    "sol_registro_completado_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["sol_registro_completado_fecha"]),
                    "sol_consolidado" => $value["sol_consolidado"],
                    "sol_consolidado_usuario" => $value["sol_consolidado_usuario"],
                    "sol_consolidado_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["sol_consolidado_fecha"]),
                    "sol_consolidado_fecha_plano" => $value["sol_consolidado_fecha"],
                    "sol_rechazado" => $value["sol_rechazado"],
                    "sol_rechazado_usuario" => $value["sol_rechazado_usuario"],
                    "sol_rechazado_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["sol_rechazado_fecha"]),
                    "sol_rechazado_texto" => $value["sol_rechazado_texto"],
                    
                    "sol_evaluacion" => $this->mfunciones_generales->GetValorCatalogo((int)$value["sol_evaluacion"], 'prospecto_evaluacion'),
                    "sol_evaluacion_codigo" => $value["sol_evaluacion"],
                    "sol_estudio" => $value["sol_estudio"],
                    "sol_estudio_codigo" => $value["sol_estudio_codigo"],
                    
                    // EMPIEZA DATOS FIE
                    
                    'sol_ci' => $value['sol_ci'],
                    'sol_extension' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_extension'], 'cI_lugar_emisionoextension'),
                    'sol_complemento' => $value['sol_complemento'],
                    'sol_primer_nombre' => $value['sol_primer_nombre'],
                    'sol_segundo_nombre' => $value['sol_segundo_nombre'],
                    'sol_primer_apellido' => $value['sol_primer_apellido'],
                    'sol_segundo_apellido' => $value['sol_segundo_apellido'],
                    'sol_correo' => $value['sol_correo'],
                    'sol_cel' => $value['sol_cel'],
                    'sol_telefono' => $value['sol_telefono'],
                    'sol_fec_nac' => $this->mfunciones_generales->getFormatoFechaD_M_Y($value['sol_fec_nac']),
                    'sol_est_civil' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_est_civil'], 'di_estadocivil'),
                    'sol_nit' => $value['sol_nit'],
                    'sol_cliente' => $this->mfunciones_microcreditos->GetValorCatalogo($value['sol_cliente'], 'sol_cliente'),
                    'sol_dependencia' => $value['sol_dependencia'],
                    'sol_indepen_actividad' => $value['sol_indepen_actividad'],
                    'sol_indepen_ant_ano' => $value['sol_indepen_ant_ano'],
                    'sol_indepen_ant_mes' => $value['sol_indepen_ant_mes'],
                    'sol_indepen_horario_desde' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_indepen_horario_desde']),
                    'sol_indepen_horario_hasta' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_indepen_horario_hasta']),
                    'sol_indepen_horario_dias' => $this->mfunciones_microcreditos->GetDiasAtencion($value['sol_indepen_horario_dias']),
                    'sol_indepen_telefono' => $value['sol_indepen_telefono'],
                    'sol_depen_empresa' => $value['sol_depen_empresa'],
                    'sol_depen_actividad' => $value['sol_depen_actividad'],
                    'sol_depen_cargo' => $value['sol_depen_cargo'],
                    'sol_depen_ant_ano' => $value['sol_depen_ant_ano'],
                    'sol_depen_ant_mes' => $value['sol_depen_ant_mes'],
                    'sol_depen_horario_desde' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_depen_horario_desde']),
                    'sol_depen_horario_hasta' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_depen_horario_hasta']),
                    'sol_depen_horario_dias' => $this->mfunciones_microcreditos->GetDiasAtencion($value['sol_depen_horario_dias']),
                    'sol_depen_telefono' => $value['sol_depen_telefono'],
                    'sol_depen_tipo_contrato' => $value['sol_depen_tipo_contrato'],
                    'sol_monto' => number_format($value['sol_monto'], 2, '.', ','),
                    'sol_plazo' => number_format($value['sol_plazo'], 0, '.', ','),
                    'sol_moneda' => $this->mfunciones_microcreditos->GetValorCatalogo($value['sol_moneda'], 'sol_moneda'),
                    'sol_detalle' => $value['sol_detalle'],
                    'sol_dir_departamento' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_dir_departamento'], 'dir_departamento'),
                    'sol_dir_provincia' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_dir_provincia'], 'dir_provincia'),
                    'sol_dir_localidad_ciudad' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_dir_localidad_ciudad'], 'dir_localidad_ciudad'),
                    'sol_cod_barrio' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_cod_barrio'], 'dir_barrio_zona_uv'),
                    'sol_direccion_trabajo' => $value['sol_direccion_trabajo'],
                    'sol_edificio_trabajo' => $value['sol_edificio_trabajo'],
                    'sol_numero_trabajo' => $value['sol_numero_trabajo'],
                    'sol_dir_referencia' => $value['sol_dir_referencia'],
                    'sol_geolocalizacion' => $value['sol_geolocalizacion'],
                    'sol_croquis' => $this->mfunciones_microcreditos->validateIMG_simple($value['sol_croquis']),
                    'sol_trabajo_lugar_codigo' => $value['sol_trabajo_lugar'],
                    'sol_trabajo_lugar' => $this->mfunciones_microcreditos->GetValorCatalogo($value['sol_trabajo_lugar'], 'sol_trabajo_lugar'),
                    'sol_trabajo_lugar_otro' => $value['sol_trabajo_lugar_otro'],
                    'sol_trabajo_realiza_codigo' => $value['sol_trabajo_realiza'],
                    'sol_trabajo_realiza' => $this->mfunciones_microcreditos->GetValorCatalogo($value['sol_trabajo_realiza'], 'sol_trabajo_realiza'),
                    'sol_trabajo_realiza_otro' => $value['sol_trabajo_realiza_otro'],
                    'sol_trabajo_actividad_pertenece' => $value['sol_trabajo_actividad_pertenece'],
                    'sol_dir_departamento_dom' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_dir_departamento_dom'], 'dir_departamento'),
                    'sol_dir_provincia_dom' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_dir_provincia_dom'], 'dir_provincia'),
                    'sol_dir_localidad_ciudad_dom' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_dir_localidad_ciudad_dom'], 'dir_localidad_ciudad'),
                    'sol_cod_barrio_dom' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_cod_barrio_dom'], 'dir_barrio_zona_uv'),
                    'sol_direccion_dom' => $value['sol_direccion_dom'],
                    'sol_edificio_dom' => $value['sol_edificio_dom'],
                    'sol_numero_dom' => $value['sol_numero_dom'],
                    'sol_dir_referencia_dom' => $value['sol_dir_referencia_dom'],
                    'sol_geolocalizacion_dom' => $value['sol_geolocalizacion_dom'],
                    'sol_croquis_dom' => $this->mfunciones_microcreditos->validateIMG_simple($value['sol_croquis_dom']),
                    'sol_dom_tipo_codigo' => $value['sol_dom_tipo'],
                    'sol_dom_tipo' => $this->mfunciones_microcreditos->GetValorCatalogo($value['sol_dom_tipo'], 'sol_dom_tipo'),
                    'sol_dom_tipo_otro' => $value['sol_dom_tipo_otro'],
                    'sol_conyugue' => $value['sol_conyugue'],
                    'sol_con_ci' => $value['sol_con_ci'],
                    'sol_con_extension' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_extension'], 'cI_lugar_emisionoextension'),
                    'sol_con_complemento' => $value['sol_con_complemento'],
                    'sol_con_primer_nombre' => $value['sol_con_primer_nombre'],
                    'sol_con_segundo_nombre' => $value['sol_con_segundo_nombre'],
                    'sol_con_primer_apellido' => $value['sol_con_primer_apellido'],
                    'sol_con_segundo_apellido' => $value['sol_con_segundo_apellido'],
                    'sol_con_correo' => $value['sol_con_correo'],
                    'sol_con_cel' => $value['sol_con_cel'],
                    'sol_con_telefono' => $value['sol_con_telefono'],
                    'sol_con_fec_nac' => $this->mfunciones_generales->getFormatoFechaD_M_Y($value['sol_con_fec_nac']),
                    'sol_con_est_civil' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_est_civil'], 'di_estadocivil'),
                    'sol_con_nit' => $value['sol_con_nit'],
                    'sol_con_cliente' => $this->mfunciones_microcreditos->GetValorCatalogo($value['sol_con_cliente'], 'sol_cliente'),
                    'sol_con_dependencia' => $value['sol_con_dependencia'],
                    'sol_con_indepen_actividad' => $value['sol_con_indepen_actividad'],
                    'sol_con_indepen_ant_ano' => $value['sol_con_indepen_ant_ano'],
                    'sol_con_indepen_ant_mes' => $value['sol_con_indepen_ant_mes'],
                    'sol_con_indepen_horario_desde' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_con_indepen_horario_desde']),
                    'sol_con_indepen_horario_hasta' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_con_indepen_horario_hasta']),
                    'sol_con_indepen_horario_dias' => $this->mfunciones_microcreditos->GetDiasAtencion($value['sol_con_indepen_horario_dias']),
                    'sol_con_indepen_telefono' => $value['sol_con_indepen_telefono'],
                    'sol_con_depen_empresa' => $value['sol_con_depen_empresa'],
                    'sol_con_depen_actividad' => $value['sol_con_depen_actividad'],
                    'sol_con_depen_cargo' => $value['sol_con_depen_cargo'],
                    'sol_con_depen_ant_ano' => $value['sol_con_depen_ant_ano'],
                    'sol_con_depen_ant_mes' => $value['sol_con_depen_ant_mes'],
                    'sol_con_depen_horario_desde' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_con_depen_horario_desde']),
                    'sol_con_depen_horario_hasta' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_con_depen_horario_hasta']),
                    'sol_con_depen_horario_dias' => $this->mfunciones_microcreditos->GetDiasAtencion($value['sol_con_depen_horario_dias']),
                    'sol_con_depen_telefono' => $value['sol_con_depen_telefono'],
                    'sol_con_depen_tipo_contrato' => $value['sol_con_depen_tipo_contrato'],
                    'sol_con_dir_departamento' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_dir_departamento'], 'dir_departamento'),
                    'sol_con_dir_provincia' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_dir_provincia'], 'dir_provincia'),
                    'sol_con_dir_localidad_ciudad' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_dir_localidad_ciudad'], 'dir_localidad_ciudad'),
                    'sol_con_cod_barrio' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_cod_barrio'], 'dir_barrio_zona_uv'),
                    'sol_con_direccion_trabajo' => $value['sol_con_direccion_trabajo'],
                    'sol_con_edificio_trabajo' => $value['sol_con_edificio_trabajo'],
                    'sol_con_numero_trabajo' => $value['sol_con_numero_trabajo'],
                    'sol_con_dir_referencia' => $value['sol_con_dir_referencia'],
                    'sol_con_geolocalizacion' => $value['sol_con_geolocalizacion'],
                    'sol_con_croquis' => $this->mfunciones_microcreditos->validateIMG_simple($value['sol_con_croquis']),
                    'sol_con_trabajo_lugar_codigo' => $value['sol_con_trabajo_lugar'],
                    'sol_con_trabajo_lugar' => $this->mfunciones_microcreditos->GetValorCatalogo($value['sol_con_trabajo_lugar'], 'sol_trabajo_lugar'),
                    'sol_con_trabajo_lugar_otro' => $value['sol_con_trabajo_lugar_otro'],
                    'sol_con_trabajo_realiza_codigo' => $value['sol_con_trabajo_realiza'],
                    'sol_con_trabajo_realiza' => $this->mfunciones_microcreditos->GetValorCatalogo($value['sol_con_trabajo_realiza'], 'sol_trabajo_realiza'),
                    'sol_con_trabajo_realiza_otro' => $value['sol_con_trabajo_realiza_otro'],
                    'sol_con_trabajo_actividad_pertenece' => $value['sol_con_trabajo_actividad_pertenece'],
                    'sol_con_dir_departamento_dom' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_dir_departamento_dom'], 'dir_departamento'),
                    'sol_con_dir_provincia_dom' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_dir_provincia_dom'], 'dir_provincia'),
                    'sol_con_dir_localidad_ciudad_dom' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_dir_localidad_ciudad_dom'], 'dir_localidad_ciudad'),
                    'sol_con_cod_barrio_dom' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_con_cod_barrio_dom'], 'dir_barrio_zona_uv'),
                    'sol_con_direccion_dom' => $value['sol_con_direccion_dom'],
                    'sol_con_edificio_dom' => $value['sol_con_edificio_dom'],
                    'sol_con_numero_dom' => $value['sol_con_numero_dom'],
                    'sol_con_dir_referencia_dom' => $value['sol_con_dir_referencia_dom'],
                    'sol_con_geolocalizacion_dom' => $value['sol_con_geolocalizacion_dom'],
                    'sol_con_croquis_dom' => $this->mfunciones_microcreditos->validateIMG_simple($value['sol_con_croquis_dom']),
                    'sol_con_dom_tipo_codigo' => $value['sol_con_dom_tipo'],
                    'sol_con_dom_tipo' => $this->mfunciones_microcreditos->GetValorCatalogo($value['sol_con_dom_tipo'], 'sol_dom_tipo'),
                    'sol_con_dom_tipo_otro' => $value['sol_con_dom_tipo_otro'],
                    
                    // Actividad Secundaria
                    
                    "sol_estudio_actividad" => $value["sol_estudio_actividad"],
                    "sol_actividad_secundaria" => $value["sol_actividad_secundaria"],
                    "sol_codigo_rubro_codigo_sec" => $value["sol_codigo_rubro_sec"],
                    "sol_codigo_rubro_sec" => $this->mfunciones_microcreditos->GetValorCatalogo($value["sol_codigo_rubro_sec"], 'aux_rubro'),
                    
                    'sol_dependencia_sec' => $value['sol_dependencia_sec'],
                    'sol_indepen_actividad_sec' => $value['sol_indepen_actividad_sec'],
                    'sol_indepen_ant_ano_sec' => $value['sol_indepen_ant_ano_sec'],
                    'sol_indepen_ant_mes_sec' => $value['sol_indepen_ant_mes_sec'],
                    'sol_indepen_horario_desde_sec' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_indepen_horario_desde_sec']),
                    'sol_indepen_horario_hasta_sec' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_indepen_horario_hasta_sec']),
                    'sol_indepen_horario_dias_sec' => $this->mfunciones_microcreditos->GetDiasAtencion($value['sol_indepen_horario_dias_sec']),
                    'sol_indepen_telefono_sec' => $value['sol_indepen_telefono_sec'],
                    'sol_depen_empresa_sec' => $value['sol_depen_empresa_sec'],
                    'sol_depen_actividad_sec' => $value['sol_depen_actividad_sec'],
                    'sol_depen_cargo_sec' => $value['sol_depen_cargo_sec'],
                    'sol_depen_ant_ano_sec' => $value['sol_depen_ant_ano_sec'],
                    'sol_depen_ant_mes_sec' => $value['sol_depen_ant_mes_sec'],
                    'sol_depen_horario_desde_sec' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_depen_horario_desde_sec']),
                    'sol_depen_horario_hasta_sec' => $this->mfunciones_generales->getFormatoFechaH_M($value['sol_depen_horario_hasta_sec']),
                    'sol_depen_horario_dias_sec' => $this->mfunciones_microcreditos->GetDiasAtencion($value['sol_depen_horario_dias_sec']),
                    'sol_depen_telefono_sec' => $value['sol_depen_telefono_sec'],
                    'sol_depen_tipo_contrato_sec' => $value['sol_depen_tipo_contrato_sec'],
                    'sol_dir_departamento_sec' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_dir_departamento_sec'], 'dir_departamento'),
                    'sol_dir_provincia_sec' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_dir_provincia_sec'], 'dir_provincia'),
                    'sol_dir_localidad_ciudad_sec' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_dir_localidad_ciudad_sec'], 'dir_localidad_ciudad'),
                    'sol_cod_barrio_sec' => $this->mfunciones_generales->GetValorCatalogoDB($value['sol_cod_barrio_sec'], 'dir_barrio_zona_uv'),
                    'sol_direccion_trabajo_sec' => $value['sol_direccion_trabajo_sec'],
                    'sol_edificio_trabajo_sec' => $value['sol_edificio_trabajo_sec'],
                    'sol_numero_trabajo_sec' => $value['sol_numero_trabajo_sec'],
                    'sol_dir_referencia_sec' => $value['sol_dir_referencia_sec'],
                    'sol_geolocalizacion_sec' => $value['sol_geolocalizacion_sec'],
                    'sol_croquis_sec' => $this->mfunciones_microcreditos->validateIMG_simple($value['sol_croquis_sec']),
                    'sol_trabajo_lugar_codigo_sec' => $value['sol_trabajo_lugar_sec'],
                    'sol_trabajo_lugar_sec' => $this->mfunciones_microcreditos->GetValorCatalogo($value['sol_trabajo_lugar_sec'], 'sol_trabajo_lugar'),
                    'sol_trabajo_lugar_otro_sec' => $value['sol_trabajo_lugar_otro_sec'],
                    'sol_trabajo_realiza_codigo_sec' => $value['sol_trabajo_realiza_sec'],
                    'sol_trabajo_realiza_sec' => $this->mfunciones_microcreditos->GetValorCatalogo($value['sol_trabajo_realiza_sec'], 'sol_trabajo_realiza'),
                    'sol_trabajo_realiza_otro_sec' => $value['sol_trabajo_realiza_otro_sec'],
                    'sol_trabajo_actividad_pertenece_sec' => $value['sol_trabajo_actividad_pertenece_sec'],
                    
                    'sol_jda_eval' => $value['sol_jda_eval'],
                    'sol_jda_eval_usuario' => $this->mfunciones_generales->getNombreUsuario($value['sol_jda_eval_usuario']),
                    'sol_jda_eval_fecha' => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value['sol_jda_eval_fecha']),
                    'sol_jda_eval_texto' => nl2br($value["sol_jda_eval_texto"]),
                    'sol_desembolso' => $value['sol_desembolso'],
                    'sol_desembolso_monto' => number_format($value["sol_desembolso_monto"], 2, '.', ','),
                    'sol_desembolso_usuario' => $this->mfunciones_generales->getNombreUsuario($value['sol_desembolso_usuario']),
                    'sol_desembolso_fecha' => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value['sol_desembolso_fecha']),
                    "registro_num_proceso" => $value["sol_num_proceso"]
                    
                );
                $lst_resultado[$i] = $item_valor;
                
                $i++;
            }
        }
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }
        
        $estado_texto = $this->mfunciones_generales->GetValorCatalogo($estado, 'estado_solicitud');
        
        $data["estado"] = $estado;
        $data["estado_texto"] = $estado_texto;
        
        $data["arrRespuesta"] = $lst_resultado;
        
        $this->load->view('solicitud_credito/view_solicitud_detalle_' . ($vista == 0 ? 'web' : 'full'), $data);
        
    }
}
?>