<?php

/**
 * Description of Fomulario_Inicio
 *
 * @author Joel Aliaga
 */
class Formulario_logica_credito {

    private $arr_validacion;
    private $arr_title_tooltip;

    public function __construct() {
        $CI = & get_instance();
        $CI->load->library('FormularioValidaciones/Formulario_campos');        
        $this->arr_validacion = array();
        $this->arr_title_tooltip = array();
        $this->formulario_campos = $CI->formulario_campos;
    }

    public function DefinicionValidacionFormulario() {

        // Solicitud de Crédito
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_credito_texto', LETRAS_NUMEROS, 'MAX(280)|', '!|¡|@|#|$|%|&|/|¿|?|+|<|>|=|\'|;|:|.|-|(|)|,');
        $arr_validacion["conf_credito_texto"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('conf_credito_notificar_texto', LETRAS_NUMEROS, 'MAX(140)|', '!|¡|%:|.|-|(|)|,');
        $arr_validacion["conf_credito_notificar_texto"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('conf_credito_tipo_cambio', NUMEROS, 'MAX(10)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.');
        $arr_validacion["conf_credito_tipo_cambio"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('conf_cuota_tasa_seguro', LETRAS_NUMEROS, 'MAX(40)|SINACENTO|SINESPACIO|REQUERIDO', '.|;');
        $arr_validacion["conf_cuota_tasa_seguro"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_credito_notificar_sms_uri', LETRAS_NUMEROS, 'MAX(145)|SINACENTO|SINESPACIO|REQUERIDO', '?|#|.|:|_|-|/|\\');
        $arr_validacion["conf_credito_notificar_sms_uri"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('conf_credito_notificar_sms_bearer', LETRAS_NUMEROS, 'MAX(1450)|SINACENTO|SINESPACIO|REQUERIDO', '?|#|.|:|_|-|/|\\');
        $arr_validacion["conf_credito_notificar_sms_bearer"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('sms_cel_test', NUMEROS, 'MAX(8)|SINESPACIO|TIPO-NUMBER|REQUERIDO', '.|-');
        $arr_validacion['sms_cel_test'] = clone $this->formulario_campos;
        
        // Formulario
        
        $this->formulario_campos->CargarOpcionesValidacion('sol_ci', LETRAS_NUMEROS, 'MAX(10)|SINESPACIO|REQUERIDO', '-'); $arr_validacion['sol_ci'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_complemento', LETRAS_NUMEROS, 'MAX(2)|SINESPACIO', ''); $arr_validacion['sol_complemento'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_primer_nombre', LETRAS, 'MAX(50)|REQUERIDO|', '\''); $arr_validacion['sol_primer_nombre'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_segundo_nombre', LETRAS, 'MAX(50)|', '\''); $arr_validacion['sol_segundo_nombre'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_primer_apellido', LETRAS, 'MAX(30)|REQUERIDO|', '\''); $arr_validacion['sol_primer_apellido'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_segundo_apellido', LETRAS, 'MAX(30)|', '\''); $arr_validacion['sol_segundo_apellido'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_correo', LETRAS_NUMEROS, 'MAX(100)|SINESPACIO|TIPO-EMAIL', '@|.|_|-'); $arr_validacion['sol_correo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_cel', NUMEROS, 'MAX(15)|SINESPACIO|REQUERIDO|TIPO-TEL', '.|-'); $arr_validacion['sol_cel'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_telefono', NUMEROS, 'MAX(15)|SINESPACIO|TIPO-TEL', '.|-'); $arr_validacion['sol_telefono'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_fec_nac', LETRAS_NUMEROS, 'MAX(11)|SINESPACIO|TIPO-DATE', '.|-'); $arr_validacion['sol_fec_nac'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_nit', NUMEROS, 'MAX(20)|SINESPACIO|TIPO-NUMBER', '.|-'); $arr_validacion['sol_nit'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_indepen_actividad', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_indepen_actividad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_indepen_ant_ano', NUMEROS, 'MAX(3)|SINESPACIO|TIPO-NUMBER', '.|-'); $arr_validacion['sol_indepen_ant_ano'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_indepen_ant_mes', NUMEROS, 'MAX(2)|SINESPACIO|TIPO-NUMBER', '.|-'); $arr_validacion['sol_indepen_ant_mes'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_indepen_horario_desde', NUMEROS, 'MAX(8)|SINESPACIO|TIPO-TIME', '.|-|:'); $arr_validacion['sol_indepen_horario_desde'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_indepen_horario_hasta', NUMEROS, 'MAX(8)|SINESPACIO|TIPO-TIME', '.|-|:'); $arr_validacion['sol_indepen_horario_hasta'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_indepen_telefono', NUMEROS, 'MAX(15)|SINESPACIO|TIPO-TEL', '.|-'); $arr_validacion['sol_indepen_telefono'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_depen_empresa', LETRAS_NUMEROS, 'MAX(50)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_depen_empresa'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_depen_actividad', LETRAS_NUMEROS, 'MAX(50)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_depen_actividad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_depen_cargo', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_depen_cargo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_depen_ant_ano', NUMEROS, 'MAX(3)|SINESPACIO|TIPO-NUMBER', '.|-'); $arr_validacion['sol_depen_ant_ano'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_depen_ant_mes', NUMEROS, 'MAX(2)|SINESPACIO|TIPO-NUMBER', '.|-'); $arr_validacion['sol_depen_ant_mes'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_depen_horario_desde', NUMEROS, 'MAX(8)|SINESPACIO|TIPO-TIME', '.|-|:'); $arr_validacion['sol_depen_horario_desde'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_depen_horario_hasta', NUMEROS, 'MAX(8)|SINESPACIO|TIPO-TIME', '.|-|:'); $arr_validacion['sol_depen_horario_hasta'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_depen_telefono', NUMEROS, 'MAX(15)|SINESPACIO|TIPO-TEL', '.|-'); $arr_validacion['sol_depen_telefono'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_depen_tipo_contrato', LETRAS_NUMEROS, 'MAX(50)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_depen_tipo_contrato'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_monto', NUMEROS, 'MAX(15)|SINESPACIO|TIPO-NUMBER|REQUERIDO', '.|-'); $arr_validacion['sol_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_plazo', NUMEROS, 'MAX(5)|SINESPACIO|TIPO-NUMBER|REQUERIDO', '.|-'); $arr_validacion['sol_plazo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_detalle', LETRAS_NUMEROS, 'MAX(2300)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,',CAJATEXTAREA); $arr_validacion['sol_detalle'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_direccion_trabajo', LETRAS_NUMEROS, 'MAX(70)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_direccion_trabajo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_edificio_trabajo', LETRAS_NUMEROS, 'MAX(20)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_edificio_trabajo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_numero_trabajo', LETRAS_NUMEROS, 'MAX(20)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_numero_trabajo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_trabajo_lugar_otro', LETRAS_NUMEROS, 'MAX(50)|', '.|,|-'); $arr_validacion['sol_trabajo_lugar_otro'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_trabajo_realiza_otro', LETRAS_NUMEROS, 'MAX(50)|', '.|,|-'); $arr_validacion['sol_trabajo_realiza_otro'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_direccion_dom', LETRAS_NUMEROS, 'MAX(70)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_direccion_dom'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_edificio_dom', LETRAS_NUMEROS, 'MAX(20)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_edificio_dom'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_numero_dom', LETRAS_NUMEROS, 'MAX(20)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_numero_dom'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_dom_tipo_otro', LETRAS_NUMEROS, 'MAX(50)|', '.|,|-'); $arr_validacion['sol_dom_tipo_otro'] = clone $this->formulario_campos;
        
        // -- Conyuge
        
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_ci', LETRAS_NUMEROS, 'MAX(10)|SINESPACIO|REQUERIDO', '.|-'); $arr_validacion['sol_con_ci'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_complemento', LETRAS_NUMEROS, 'MAX(2)|SINESPACIO', ''); $arr_validacion['sol_con_complemento'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_primer_nombre', LETRAS, 'MAX(50)|REQUERIDO', '\''); $arr_validacion['sol_con_primer_nombre'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_segundo_nombre', LETRAS, 'MAX(50)|', '\''); $arr_validacion['sol_con_segundo_nombre'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_primer_apellido', LETRAS, 'MAX(30)|REQUERIDO', '\''); $arr_validacion['sol_con_primer_apellido'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_segundo_apellido', LETRAS, 'MAX(30)|', '\''); $arr_validacion['sol_con_segundo_apellido'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_correo', LETRAS_NUMEROS, 'MAX(100)|SINESPACIO|TIPO-EMAIL', '@|.|_|-'); $arr_validacion['sol_con_correo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_cel', NUMEROS, 'MAX(15)|SINESPACIO|REQUERIDO|TIPO-TEL', '.|-'); $arr_validacion['sol_con_cel'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_telefono', NUMEROS, 'MAX(15)|SINESPACIO|TIPO-TEL', '.|-'); $arr_validacion['sol_con_telefono'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_fec_nac', LETRAS_NUMEROS, 'MAX(11)|SINESPACIO|TIPO-DATE', '.|-'); $arr_validacion['sol_con_fec_nac'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_nit', NUMEROS, 'MAX(20)|SINESPACIO|TIPO-NUMBER', '.|-'); $arr_validacion['sol_con_nit'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_indepen_actividad', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_con_indepen_actividad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_indepen_ant_ano', NUMEROS, 'MAX(3)|SINESPACIO|TIPO-NUMBER', '.|-'); $arr_validacion['sol_con_indepen_ant_ano'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_indepen_ant_mes', NUMEROS, 'MAX(2)|SINESPACIO|TIPO-NUMBER', '.|-'); $arr_validacion['sol_con_indepen_ant_mes'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_indepen_horario_desde', NUMEROS, 'MAX(8)|SINESPACIO|TIPO-TIME', '.|-|:'); $arr_validacion['sol_con_indepen_horario_desde'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_indepen_horario_hasta', NUMEROS, 'MAX(8)|SINESPACIO|TIPO-TIME', '.|-|:'); $arr_validacion['sol_con_indepen_horario_hasta'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_indepen_telefono', NUMEROS, 'MAX(15)|SINESPACIO|TIPO-TEL', '.|-'); $arr_validacion['sol_con_indepen_telefono'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_depen_empresa', LETRAS_NUMEROS, 'MAX(50)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_con_depen_empresa'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_depen_actividad', LETRAS_NUMEROS, 'MAX(50)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_con_depen_actividad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_depen_cargo', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_con_depen_cargo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_depen_ant_ano', NUMEROS, 'MAX(3)|SINESPACIO|TIPO-NUMBER', '.|-'); $arr_validacion['sol_con_depen_ant_ano'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_depen_ant_mes', NUMEROS, 'MAX(2)|SINESPACIO|TIPO-NUMBER', '.|-'); $arr_validacion['sol_con_depen_ant_mes'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_depen_horario_desde', NUMEROS, 'MAX(8)|SINESPACIO|TIPO-TIME', '.|-|:'); $arr_validacion['sol_con_depen_horario_desde'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_depen_horario_hasta', NUMEROS, 'MAX(8)|SINESPACIO|TIPO-TIME', '.|-|:'); $arr_validacion['sol_con_depen_horario_hasta'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_depen_telefono', NUMEROS, 'MAX(15)|SINESPACIO|TIPO-TEL', '.|-'); $arr_validacion['sol_con_depen_telefono'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_depen_tipo_contrato', LETRAS_NUMEROS, 'MAX(50)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_con_depen_tipo_contrato'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_monto', NUMEROS, 'MAX(15)|SINESPACIO|TIPO-NUMBER', '.|-'); $arr_validacion['sol_con_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_plazo', NUMEROS, 'MAX(5)|SINESPACIO|TIPO-NUMBER', '.|-'); $arr_validacion['sol_con_plazo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_detalle', LETRAS_NUMEROS, 'MAX(2300)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_con_detalle'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_direccion_trabajo', LETRAS_NUMEROS, 'MAX(70)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_con_direccion_trabajo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_edificio_trabajo', LETRAS_NUMEROS, 'MAX(20)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_con_edificio_trabajo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_numero_trabajo', LETRAS_NUMEROS, 'MAX(20)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_con_numero_trabajo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_trabajo_lugar_otro', LETRAS_NUMEROS, 'MAX(50)|', '.|,|-'); $arr_validacion['sol_con_trabajo_lugar_otro'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_trabajo_realiza_otro', LETRAS_NUMEROS, 'MAX(50)|', '.|,|-'); $arr_validacion['sol_con_trabajo_realiza_otro'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_direccion_dom', LETRAS_NUMEROS, 'MAX(70)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_con_direccion_dom'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_edificio_dom', LETRAS_NUMEROS, 'MAX(20)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_con_edificio_dom'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_numero_dom', LETRAS_NUMEROS, 'MAX(20)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_con_numero_dom'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_con_dom_tipo_otro', LETRAS_NUMEROS, 'MAX(50)|', '.|,|-'); $arr_validacion['sol_con_dom_tipo_otro'] = clone $this->formulario_campos;
        
        // -- Actividad Secundaria
        $this->formulario_campos->CargarOpcionesValidacion('sol_indepen_actividad_sec', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_indepen_actividad_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_indepen_ant_ano_sec', NUMEROS, 'MAX(3)|SINESPACIO|TIPO-NUMBER', '.|-'); $arr_validacion['sol_indepen_ant_ano_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_indepen_ant_mes_sec', NUMEROS, 'MAX(2)|SINESPACIO|TIPO-NUMBER', '.|-'); $arr_validacion['sol_indepen_ant_mes_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_indepen_horario_desde_sec', NUMEROS, 'MAX(8)|SINESPACIO|TIPO-TIME', '.|-|:'); $arr_validacion['sol_indepen_horario_desde_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_indepen_horario_hasta_sec', NUMEROS, 'MAX(8)|SINESPACIO|TIPO-TIME', '.|-|:'); $arr_validacion['sol_indepen_horario_hasta_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_indepen_telefono_sec', NUMEROS, 'MAX(15)|SINESPACIO|TIPO-TEL', '.|-'); $arr_validacion['sol_indepen_telefono_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_depen_empresa_sec', LETRAS_NUMEROS, 'MAX(50)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_depen_empresa_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_depen_actividad_sec', LETRAS_NUMEROS, 'MAX(50)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_depen_actividad_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_depen_cargo_sec', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_depen_cargo_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_depen_ant_ano_sec', NUMEROS, 'MAX(3)|SINESPACIO|TIPO-NUMBER', '.|-'); $arr_validacion['sol_depen_ant_ano_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_depen_ant_mes_sec', NUMEROS, 'MAX(2)|SINESPACIO|TIPO-NUMBER', '.|-'); $arr_validacion['sol_depen_ant_mes_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_depen_horario_desde_sec', NUMEROS, 'MAX(8)|SINESPACIO|TIPO-TIME', '.|-|:'); $arr_validacion['sol_depen_horario_desde_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_depen_horario_hasta_sec', NUMEROS, 'MAX(8)|SINESPACIO|TIPO-TIME', '.|-|:'); $arr_validacion['sol_depen_horario_hasta_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_depen_telefono_sec', NUMEROS, 'MAX(15)|SINESPACIO|TIPO-TEL', '.|-'); $arr_validacion['sol_depen_telefono_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_depen_tipo_contrato_sec', LETRAS_NUMEROS, 'MAX(50)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_depen_tipo_contrato_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_direccion_trabajo_sec', LETRAS_NUMEROS, 'MAX(70)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_direccion_trabajo_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_edificio_trabajo_sec', LETRAS_NUMEROS, 'MAX(20)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_edificio_trabajo_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_numero_trabajo_sec', LETRAS_NUMEROS, 'MAX(20)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['sol_numero_trabajo_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_trabajo_lugar_otro_sec', LETRAS_NUMEROS, 'MAX(50)|', '.|,|-'); $arr_validacion['sol_trabajo_lugar_otro_sec'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('sol_trabajo_realiza_otro_sec', LETRAS_NUMEROS, 'MAX(50)|', '.|,|-'); $arr_validacion['sol_trabajo_realiza_otro_sec'] = clone $this->formulario_campos;
        
        // FORMULARIOS FIE - FIN
        
        $this->arr_validacion = $arr_validacion;
    }

    public function DefinicionTitleToolTip() {
        $arr_title_tooltip["hoja_ruta"] = "Indique el N° de Hoja de Ruta";

        $this->arr_title_tooltip = $arr_title_tooltip;
    }

    public function ConstruccionCajasFormulario($arrValoresPorDefecto) {
        $i = 0;
        $arrCajasFormulario = array();
        
        foreach ($this->arr_validacion as $campo => $objvalidacion) {

            if ($objvalidacion->TIPO_CAJA_TEXTO === CAJATEXTO) {
                $arrCajasFormulario[$i++] = $campo;
            }
        }
        //var_dump($this->arr_validacion);
        foreach ($arrCajasFormulario as $id_caja) {
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            if (isset($this->arr_validacion[$id_caja])) {
                $validacion = $this->arr_validacion[$id_caja];

                if (isset($this->arr_title_tooltip[$id_caja])) {
                    $titleTooltip = $this->arr_title_tooltip[$id_caja];
                } else {
                    $titleTooltip = '';
                }
                $arr_formulario_cajas[$id_caja] = LibreriaUsir_GeneracionCajaHtml($id_caja, 'MAX(' . $validacion->CARACTERES_MAXIMO . ')', $strValorCaja, $validacion->CLASES_CSS, $titleTooltip, $validacion->tipo_caja_especifica);
            }
        }
        
        // COMBOS - INCIO
        
        $combo_sino = Array(
            array("id" => "0", "campoDescrip" => "No"),
            array("id" => "1", "campoDescrip" => "Si")
        );
        
        $arr_sol_cliente = Array(
            array("id" => "0", "campoDescrip" => "Nuevo"),
            array("id" => "1", "campoDescrip" => "Antiguo")
        );
        
        $arr_sol_dependencia = Array(
            array("id" => "0", "campoDescrip" => " -- "),
            array("id" => "1", "campoDescrip" => "Dependiente"),
            array("id" => "2", "campoDescrip" => "Independiente")
        );
        
        $arr_sol_moneda = Array(
            array("id" => "bob", "campoDescrip" => "Bolivianos"),
            array("id" => "usd", "campoDescrip" => "Dólares")
        );
        
        $arr_sol_trabajo_lugar = Array(
            array("id" => "1", "campoDescrip" => "Propio"),
            array("id" => "2", "campoDescrip" => "Alquilado"),
            array("id" => "3", "campoDescrip" => "Anticrético"),
            array("id" => "99", "campoDescrip" => "Otro")
        );
        
        $arr_sol_trabajo_realiza = Array(
            array("id" => "1", "campoDescrip" => "Calle"),
            array("id" => "2", "campoDescrip" => "Tienda"),
            array("id" => "3", "campoDescrip" => "Puesto de Mercado"),
            array("id" => "4", "campoDescrip" => "Sindicato"),
            array("id" => "99", "campoDescrip" => "Otro")
        );

        $arr_sol_trabajo_actividad_pertenece = Array(
            array("id" => "0", "campoDescrip" => "Deudor"),
            array("id" => "1", "campoDescrip" => "Codeudor")
        );

        $arr_sol_dom_tipo = Array(
            array("id" => "1", "campoDescrip" => "Propia"),
            array("id" => "2", "campoDescrip" => "En Alquiler"),
            array("id" => "3", "campoDescrip" => "En Anticrético"),
            array("id" => "4", "campoDescrip" => "Familiar"),
            array("id" => "99", "campoDescrip" => "Otro")
        );
        
        $arr_sol_dir_referencia = Array(
            array("id" => "0", "campoDescrip" => " -- "),
            array("id" => "1", "campoDescrip" => "Geolocalización"),
            array("id" => "2", "campoDescrip" => "Croquis")
        );
        
        
        // COMBOS - FIN
        
        $id_caja = 'conf_credito_notificar_email';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'conf_credito_notificar_sms';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        // Formulario
        
        $id_caja = 'sol_cliente';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_cliente, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'sol_conyugue';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'sol_dependencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_dependencia, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'sol_moneda';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_moneda, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'sol_dir_referencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_dir_referencia, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'sol_dir_referencia_dom';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_dir_referencia, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'sol_trabajo_lugar';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_trabajo_lugar, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = 'sol_trabajo_realiza';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_trabajo_realiza, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = 'sol_trabajo_actividad_pertenece';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_trabajo_actividad_pertenece, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'sol_dom_tipo';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_dom_tipo, 'id', 'campoDescrip', '', $strValorCaja);
        
        // Conyuge
        
        $id_caja = 'sol_con_cliente';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_cliente, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'sol_con_dependencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_dependencia, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'sol_con_moneda';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_moneda, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'sol_con_dir_referencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_dir_referencia, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'sol_con_dir_referencia_dom';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_dir_referencia, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'sol_con_trabajo_lugar';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_trabajo_lugar, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = 'sol_con_trabajo_realiza';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_trabajo_realiza, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = 'sol_con_trabajo_actividad_pertenece';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_trabajo_actividad_pertenece, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'sol_con_dom_tipo';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_dom_tipo, 'id', 'campoDescrip', '', $strValorCaja);
        
        // -- Actividad Secundaria
        
        $id_caja = 'sol_actividad_secundaria';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'sol_dependencia_sec';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_dependencia, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'sol_dir_referencia_sec';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_dir_referencia, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'sol_trabajo_lugar_sec';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_trabajo_lugar, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = 'sol_trabajo_realiza_sec';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_sol_trabajo_realiza, 'id', 'campoDescrip', '', $strValorCaja);
        
        /////////////////////////////
        
        $i = 0;
        $arrCajasFormulario = array();
        foreach ($this->arr_validacion as $campo => $objvalidacion) {
            if ($objvalidacion->TIPO_CAJA_TEXTO == CAJAFECHA) {
                $arrCajasFormulario[$i++] = $campo;
            }
        }

        //$arrCajasFormulario = array("djbr_fecha","djbr_fecha_incom","djbr_fecha_indep","persona_fecha_nacimiento","mov_fecha_inicio","mov_fecha_final","mov_fecha_ingreso");
        //$id_caja = "persona_fecha_nacimiento";
        foreach ($arrCajasFormulario as $id_caja) {
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            if (isset($this->arr_validacion[$id_caja])) {
                $validacion = $this->arr_validacion[$id_caja];
                $arr_formulario_cajas[$id_caja] = html_caja_fecha($id_caja, 'DISABLED', $strValorCaja, $validacion->CLASES_CSS);
            }
        }


        $i = 0;
        $arrCajasFormulario = array();
        foreach ($this->arr_validacion as $campo => $objvalidacion) {
            if ($objvalidacion->TIPO_CAJA_TEXTO == CAJATEXTAREA) {
                $arrCajasFormulario[$i++] = $campo;
            }
        }
        //$arrCajasFormulario = array("cuentaDescribePercibePension","cuentaDescribeSalarioDocencia");
        //$id_caja = "base_legal_descripcion";
        foreach ($arrCajasFormulario as $id_caja) {
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            if (isset($this->arr_validacion[$id_caja])) {
                $validacion = $this->arr_validacion[$id_caja];
                $arr_formulario_cajas[$id_caja] = html_textarea($id_caja, '', $strValorCaja, $validacion->CLASES_CSS, '', $validacion->CARACTERES_MAXIMO);
            }
        }

        return $arr_formulario_cajas;
    }

    public function GeneraValidacionJavaScript() {
        return LibreriaUsir_GeneracionJqueryValidate_ParaCajasFormulario($this->arr_validacion);
    }

    public function ValidarValoresLadoServidor($arrValoresPost, $strNombreDivError, &$arrValoresRetorno) {
        $arr_validacion = $this->arr_validacion;
        LibreriaUsir_ValidarValoresLadoServidor($arr_validacion, $arrValoresPost, $strNombreDivError, $arrValoresRetorno);
    }

}

?>
