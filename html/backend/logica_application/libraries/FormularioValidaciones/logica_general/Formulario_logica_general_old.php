<?php

/**
 * Description of Fomulario_Inicio
 *
 * @author Joel Aliaga
 */
class Formulario_logica_general {

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

        // AFILIACIÓN POR TERCEROS INICIO
        
        $this->formulario_campos->CargarOpcionesValidacion('afiliador_nombre', LETRAS_NUMEROS, 'MAX(140)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["afiliador_nombre"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('afiliador_responsable_nombre', LETRAS_NUMEROS, 'MAX(140)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["afiliador_responsable_nombre"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('afiliador_responsable_email', LETRAS_NUMEROS, 'MAX(140)|REQUERIDO|SINESPACIO', '.|@|-|_');
        $arr_validacion["afiliador_responsable_email"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('afiliador_responsable_contacto', LETRAS_NUMEROS, 'MAX(55)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["afiliador_responsable_contacto"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('afiliador_referencia_documento', LETRAS_NUMEROS, 'MAX(190)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["afiliador_referencia_documento"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('afiliador_texto_custom', LETRAS_NUMEROS, 'MAX(100)', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["afiliador_texto_custom"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('rechazado_texto', LETRAS_NUMEROS, 'MAX(140)', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(:)|,');
        $arr_validacion["rechazado_texto"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('onboarding_numero_cuenta', LETRAS_NUMEROS, 'MAX(120)|REQUERIDO', '+|.|*|_|-|,');
        $arr_validacion["onboarding_numero_cuenta"] = clone $this->formulario_campos;
        
        
        // AFILIACIÓN POR TERCEROS FIN
        
        // EMPRESA
        
        $this->formulario_campos->CargarOpcionesValidacion('empresa_nit', NUMEROS, 'MAX(20)|REQUERIDO|SINESPACIO', '');
        $arr_validacion["empresa_nit"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_nombre_legal', LETRAS_NUMEROS, 'MAX(55)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_nombre_legal"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_nombre_fantasia', LETRAS_NUMEROS, 'MAX(55)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_nombre_fantasia"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_nombre_establecimiento', LETRAS_NUMEROS, 'MAX(55)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_nombre_establecimiento"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_denominacion_corta', LETRAS_NUMEROS, 'MAX(20)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_denominacion_corta"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_nombre_referencia', LETRAS_NUMEROS, 'MAX(55)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_nombre_referencia"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_ha_desde', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["empresa_ha_desde"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_ha_hasta', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["empresa_ha_hasta"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_dato_contacto', LETRAS_NUMEROS, 'MAX(200)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_dato_contacto"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_email', LETRAS_NUMEROS, 'MAX(55)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_email"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_calle', LETRAS_NUMEROS, 'MAX(55)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_calle"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_numero', NUMEROS, 'MAX(6)|REQUERIDO|SINESPACIO', '');
        $arr_validacion["empresa_numero"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_direccion_literal', LETRAS_NUMEROS, 'MAX(95)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_direccion_literal"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_info_adicional', LETRAS_NUMEROS, 'MAX(55)', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_info_adicional"] = clone $this->formulario_campos;
        
        // CAMPAÑAS
        
        $this->formulario_campos->CargarOpcionesValidacion('camp_nombre', LETRAS_NUMEROS, 'MAX(45)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["camp_nombre"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('campana_desc', LETRAS_NUMEROS, 'MAX(255)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["camp_desc"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('campana_plazo', NUMEROS, 'MAX(11)|REQUERIDO', '');
        $arr_validacion["camp_plazo"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('camp_fecha_inicio', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["camp_fecha_inicio"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('campana_monto_oferta', NUMEROS, 'MAX(11)|REQUERIDO', '');
        $arr_validacion["camp_monto_oferta"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('campana_tasa', NUMEROS, 'MAX(19)|REQUERIDO', '.');
        $arr_validacion["camp_tasa"] = clone $this->formulario_campos;
        

        // QR EXTERNO
        
        $this->formulario_campos->CargarOpcionesValidacion('qr_nombre', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', '°|&|,|-|/|:|_|.|(|)|@|#|$|%|&|/|¿|?|¡|+|.|*');
        $arr_validacion["qr_nombre"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('qr_empresa', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', '°|&|,|-|/|:|_|.|(|)|@|#|$|%|&|/|¿|?|¡|+|.|*');
        $arr_validacion["qr_empresa"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('usuario_email', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|SINESPACIO', '.|@|-|_');
        $arr_validacion["qr_correo"] = clone $this->formulario_campos;

        // EXTERNO
        
        $this->formulario_campos->CargarOpcionesValidacion('contraseña de usuario', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|=|>|<|(|)');
        $arr_validacion["imagen"] = clone $this->formulario_campos;
                
        // USUARIOS
        
        $this->formulario_campos->CargarOpcionesValidacion('usuario_user', LETRAS_NUMEROS, 'SINACENTO|SINESPACIO|REQUERIDO|MAX(100)', '.|_|-');
        $arr_validacion["usuario_user"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('usuario_nombres', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', '-|.|:|\'');
        $arr_validacion["usuario_nombres"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('usuario_app', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', '-|.|:|\'');
        $arr_validacion["usuario_app"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('usuario_apm', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', '-|.|:|\'');
        $arr_validacion["usuario_apm"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('usuario_email', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|SINESPACIO', '.|@|-|_');
        $arr_validacion["usuario_email"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('usuario_telefono', NUMEROS, 'MAX(50)|REQUERIDO|SINESPACIO', '');
        $arr_validacion["usuario_telefono"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('usuario_direccion', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)');
        $arr_validacion["usuario_direccion"] = clone $this->formulario_campos;

        // pass
        $this->formulario_campos->CargarOpcionesValidacion('password_anterior', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|=|>|<|(|)');
        $arr_validacion["password_anterior"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('password_nuevo', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|=|>|<|(|)');
        $arr_validacion["password_nuevo"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('password_repetir', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|=|>|<|(|)');
        $arr_validacion["password_repetir"] = clone $this->formulario_campos;
        
        // CONFIGURACIÓN - CREDENCIALES
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_credenciales_long_min', NUMEROS, 'MAX(5)|REQUERIDO');
        $arr_validacion["conf_credenciales_long_min"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_credenciales_long_max', NUMEROS, 'MAX(5)|REQUERIDO');
        $arr_validacion["conf_credenciales_long_max"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_credenciales_duracion_min', NUMEROS, 'MAX(5)|REQUERIDO');
        $arr_validacion["conf_credenciales_duracion_min"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_credenciales_duracion_max', NUMEROS, 'MAX(5)|REQUERIDO');
        $arr_validacion["conf_credenciales_duracion_max"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_credenciales_tiempo_bloqueo', NUMEROS, 'MAX(5)|REQUERIDO');
        $arr_validacion["conf_credenciales_tiempo_bloqueo"] = clone $this->formulario_campos;
                
        $this->formulario_campos->CargarOpcionesValidacion('conf_credenciales_defecto', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*');
        $arr_validacion["conf_credenciales_defecto"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_ejecutivo_ic', NUMEROS, 'MAX(5)|REQUERIDO');
        $arr_validacion["conf_ejecutivo_ic"] = clone $this->formulario_campos;
        
        // CONFIGURACIÓN - ENVÍO DE CORREOS
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_smtp_host', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|SINESPACIO', '-|/|:|_|.');
        $arr_validacion["conf_correo_smtp_host"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_smtp_port', NUMEROS, 'MAX(10)|REQUERIDO|SINESPACIO');
        $arr_validacion["conf_correo_smtp_port"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_smtp_user', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-');
        $arr_validacion["conf_correo_smtp_user"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_smtp_pass', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|=|>|<|(|)');
        $arr_validacion["conf_correo_smtp_pass"] = clone $this->formulario_campos;
        
        // CONFIGURACIÓN - PLANTILLA DE CORREOS
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_plantilla_nombre', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', '.|:|_');
        $arr_validacion["conf_plantilla_nombre"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_plantilla_titulo_correo', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', '.|:|_|-');
        $arr_validacion["conf_plantilla_titulo_correo"] = clone $this->formulario_campos;
        
        // CONFIGURACIÓN - GENERAL
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_general_key_google', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|SINESPACIO', '.|:|_|-');
        $arr_validacion["conf_general_key_google"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_atencion_desde1', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["conf_atencion_desde1"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_atencion_hasta1', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["conf_atencion_hasta1"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_atencion_desde2', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["conf_atencion_desde2"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_atencion_hasta2', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["conf_atencion_hasta2"] = clone $this->formulario_campos;
        
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_rekognition_key', LETRAS_NUMEROS, 'MAX(45)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|=|>|<|(|)');
        $arr_validacion["conf_rekognition_key"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_rekognition_secret', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|=|>|<|(|)');
        $arr_validacion["conf_rekognition_secret"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_rekognition_region', LETRAS_NUMEROS, 'MAX(15)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|=|>|<|(|)');
        $arr_validacion["conf_rekognition_region"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_rekognition_similarity', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.');
        $arr_validacion["conf_rekognition_similarity"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_rekognition_texto_fallo', LETRAS_NUMEROS, 'MAX(115)', '!|@|%|¿|?|¡|.|,|*|_|-|=|>|<|(|)');
        $arr_validacion["conf_rekognition_texto_fallo"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_img_width_max', NUMEROS, 'MAX(4)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '');
        $arr_validacion["conf_img_width_max"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_img_width_min', NUMEROS, 'MAX(4)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '');
        $arr_validacion["conf_img_width_min"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_img_height_max', NUMEROS, 'MAX(4)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '');
        $arr_validacion["conf_img_height_max"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_img_height_min', NUMEROS, 'MAX(4)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '');
        $arr_validacion["conf_img_height_min"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_img_peso', NUMEROS, 'MAX(2)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '');
        $arr_validacion["conf_img_peso"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_texto_respuesta', LETRAS_NUMEROS, 'MAX(280)|REQUERIDO', '!|¡|@|#|$|%|&|/|¿|?|+|<|>|=|\'|;|:|.|-|(|)|,');
        $arr_validacion["conf_texto_respuesta"] = clone $this->formulario_campos;
        
        
        // AUDITORÍA

        $this->formulario_campos->CargarOpcionesValidacion('fecha_inicio', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["fecha_inicio"] = clone $this->formulario_campos;

        $this->formulario_campos->CargarOpcionesValidacion('fecha_fin', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["fecha_fin"] = clone $this->formulario_campos;
        
        // CATÁLOGO
		
        $this->formulario_campos->CargarOpcionesValidacion('catalogo_codigo', LETRAS_NUMEROS, 'MAX(20)|REQUERIDO|SINESPACIO', '_');
        $arr_validacion["catalogo_codigo"] = clone $this->formulario_campos;
		
        $this->formulario_campos->CargarOpcionesValidacion('catalogo_descripcion', LETRAS_NUMEROS, 'MAX(230)|REQUERIDO', '.|:|_');
        $arr_validacion["catalogo_descripcion"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('catalogo_tipo_extra', LETRAS_NUMEROS, 'SINACENTO|SINESPACIO|MAX(48)', '.|_|-');
        $arr_validacion["catalogo_tipo_extra"] = clone $this->formulario_campos;
        
        // ESTRUCTURA
		
        $this->formulario_campos->CargarOpcionesValidacion('estructura_nombre', LETRAS_NUMEROS, 'MAX(40)|REQUERIDO', ',|-|/|:|_|.|(|)');
        $arr_validacion["estructura_nombre"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('estructura_detalle', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', ',|-|/|:|_|.|(|)');
        $arr_validacion["estructura_detalle"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('estructura_regional_geo', NUMEROS, 'MAX(50)', ',|-|.');
        $arr_validacion["estructura_regional_geo"] = clone $this->formulario_campos;

        $this->formulario_campos->CargarOpcionesValidacion('estructura_regional_responsable', LETRAS_NUMEROS, 'MAX(140)', ',|-|/|:|_|.|(|)');
        $arr_validacion["estructura_regional_responsable"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('estructura_regional_direccion', LETRAS_NUMEROS, 'MAX(190)', '!|@|#|$|%|&|/|¿|?|¡|+|.|,|*|_|-|=|>|<|(|)');
        $arr_validacion["estructura_regional_direccion"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('estructura_regional_monto', NUMEROS, 'MAX(16)|SINESPACIO|REQUERIDO', '.');
        $arr_validacion["estructura_regional_monto"] = clone $this->formulario_campos;
        
        // DOCUMENTOS
		
        $this->formulario_campos->CargarOpcionesValidacion('documento_nombre', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', ',|-|/|:|_|.');
        $arr_validacion["documento_nombre"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('documento_codigo', LETRAS_NUMEROS, 'MAX(15)|SINESPACIO', '_');
        $arr_validacion["documento_codigo"] = clone $this->formulario_campos;
        
        // SOLICITUD DE AFILIACIÓN
                
        $this->formulario_campos->CargarOpcionesValidacion('solicitud_nombre_persona', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', ',|-|/|:|_|.|(|)');
        $arr_validacion["solicitud_nombre_persona"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('solicitud_nombre_empresa', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', '°|&|,|-|/|:|_|.|(|)|@|#|$|%|&|/|¿|?|¡|+|.|*');
        $arr_validacion["solicitud_nombre_empresa"] = clone $this->formulario_campos;
                
        $this->formulario_campos->CargarOpcionesValidacion('solicitud_telefono', LETRAS_NUMEROS, 'MAX(40)|REQUERIDO', ',|-|/|:|_|.|(|)');
        $arr_validacion["solicitud_telefono"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('solicitud_email', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|SINESPACIO', '-|/|:|_|.|(|)|@');
        $arr_validacion["solicitud_email"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('solicitud_direccion_literal', LETRAS_NUMEROS, 'MAX(250)|REQUERIDO', ',|-|/|:|_|.|(|)|°');
        $arr_validacion["solicitud_direccion_literal"] = clone $this->formulario_campos;

        $this->formulario_campos->CargarOpcionesValidacion('solicitud_observacion', LETRAS_NUMEROS, 'MAX(280)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,', CAJATEXTAREA);
        $arr_validacion["solicitud_observacion"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('solicitud_nit', NUMEROS, 'MAX(20)|REQUERIDO|SINESPACIO', '');
        $arr_validacion["solicitud_nit"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('solicitud_fecha_visita', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["solicitud_fecha_visita"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('solicitud_otro_detalle', LETRAS_NUMEROS, 'MAX(90)|REQUERIDO', '-|/|:|_|.|(|)');
        $arr_validacion["solicitud_otro_detalle"] = clone $this->formulario_campos;
        
        // FLUJO DE TRABAJO
        
        $this->formulario_campos->CargarOpcionesValidacion('etapa_nombre', LETRAS_NUMEROS, 'MAX(40)|REQUERIDO', '-|/|:|_|.|(|)');
        $arr_validacion["etapa_nombre"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('etapa_detalle', LETRAS_NUMEROS, 'MAX(290)|REQUERIDO', '-|/|:|_|.|(|)|,', CAJATEXTAREA);
        $arr_validacion["etapa_detalle"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('etapa_tiempo', NUMEROS, 'MAX(20)|REQUERIDO|SINESPACIO', '');
        $arr_validacion["etapa_tiempo"] = clone $this->formulario_campos;
        
        // PROSPECTO
        
        $this->formulario_campos->CargarOpcionesValidacion('prospecto_justificar', LETRAS_NUMEROS, 'MAX(190)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,', CAJATEXTAREA);
        $arr_validacion["prospecto_justificar"] = clone $this->formulario_campos;
        
        // BANDEJAS
        
        $this->formulario_campos->CargarOpcionesValidacion('antecedentes_detalle', LETRAS_NUMEROS, 'MAX(290)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,', CAJATEXTAREA);
        $arr_validacion["antecedentes_detalle"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('excepcion_detalle', LETRAS_NUMEROS, 'MAX(290)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,', CAJATEXTAREA);
        $arr_validacion["excepcion_detalle"] = clone $this->formulario_campos;
        
        // -- Evaluación Legal
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_denominacion_comercial', LETRAS_NUMEROS, 'MAX(90)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["evaluacion_denominacion_comercial"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_razon_social', LETRAS_NUMEROS, 'MAX(90)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["evaluacion_razon_social"] = clone $this->formulario_campos;
        
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_nit', NUMEROS, 'MAX(20)|REQUERIDO|SINESPACIO', '');
        $arr_validacion["evaluacion_nit"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_representante_legal', LETRAS_NUMEROS, 'MAX(90)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["evaluacion_representante_legal"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_actividad_principal', LETRAS_NUMEROS, 'MAX(90)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["evaluacion_actividad_principal"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_actividad_secundaria', LETRAS_NUMEROS, 'MAX(90)', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["evaluacion_actividad_secundaria"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_ci_fecnac', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["evaluacion_ci_fecnac"] = clone $this->formulario_campos;

        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_ci_titular', LETRAS_NUMEROS, 'MAX(90)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["evaluacion_ci_titular"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_numero_testimonio', LETRAS_NUMEROS, 'MAX(20)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["evaluacion_numero_testimonio"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_duracion_empresa', LETRAS_NUMEROS, 'MAX(20)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["evaluacion_duracion_empresa"] = clone $this->formulario_campos;

        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_fecha_testimonio', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["evaluacion_fecha_testimonio"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_objeto_constitucion', LETRAS_NUMEROS, 'MAX(90)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,', CAJATEXTAREA);
        $arr_validacion["evaluacion_objeto_constitucion"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_fecha_testimonio_poder', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["evaluacion_fecha_testimonio_poder"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_numero_testimonio_poder', LETRAS_NUMEROS, 'MAX(40)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["evaluacion_numero_testimonio_poder"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_fundaempresa_emision', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["evaluacion_fundaempresa_emision"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_fundaempresa_vigencia', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["evaluacion_fundaempresa_vigencia"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_resultado', LETRAS_NUMEROS, 'MAX(490)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,', CAJATEXTAREA);
        $arr_validacion["evaluacion_resultado"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_fecha_solicitud', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["evaluacion_fecha_solicitud"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_fecha_evaluacion', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["evaluacion_fecha_evaluacion"] = clone $this->formulario_campos;
        
        // FORMULARIOS FIE - INICIO
        
        $this->formulario_campos->CargarOpcionesValidacion('monto_manual', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['monto_manual'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('general_solicitante', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['general_solicitante'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('general_ci', LETRAS_NUMEROS, 'MAX(15)', '.|-');
        $arr_validacion['general_ci'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('general_telefono', LETRAS_NUMEROS, 'MAX(20)|REQUERIDO|TIPO-NUMBER', '_|-');
        $arr_validacion['general_telefono'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('general_email', LETRAS_NUMEROS, 'MAX(145)|REQUERIDO|TIPO-EMAIL', '!|@|#|$|%|&|?|.|_|-|');
        $arr_validacion["general_email"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('general_direccion', LETRAS_NUMEROS, 'MAX(290)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["general_direccion"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('general_actividad', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['general_actividad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('general_destino', LETRAS_NUMEROS, 'MAX(2000)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,', CAJATEXTAREA);
        $arr_validacion['general_destino'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('general_comentarios', LETRAS_NUMEROS, 'MAX(3000)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,', CAJATEXTAREA);
        $arr_validacion['general_comentarios'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operacion_antiguedad', NUMEROS, 'MAX(5)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operacion_antiguedad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operacion_tiempo', NUMEROS, 'MAX(5)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operacion_tiempo'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operacion_antiguedad_ano', NUMEROS, 'MAX(3)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operacion_antiguedad_ano'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operacion_antiguedad_mes', NUMEROS, 'MAX(2)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operacion_antiguedad_mes'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operacion_tiempo_ano', NUMEROS, 'MAX(3)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operacion_tiempo_ano'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operacion_tiempo_mes', NUMEROS, 'MAX(2)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operacion_tiempo_mes'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operacion_efectivo', NUMEROS, 'MAX(8)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operacion_efectivo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operacion_dias', NUMEROS, 'MAX(3)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operacion_dias'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('aclarar_contado', NUMEROS, 'MAX(5)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['aclarar_contado'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('aclarar_credito', NUMEROS, 'MAX(5)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['aclarar_credito'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_dia_lunes', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_dia_lunes'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_dia_martes', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_dia_martes'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_dia_miercoles', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_dia_miercoles'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_dia_jueves', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_dia_jueves'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_dia_viernes', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_dia_viernes'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_dia_sabado', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_dia_sabado'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_dia_domingo', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_dia_domingo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_dia_monto_bueno', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        
        $arr_validacion['frec_dia_semana_monto2'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_dia_semana_monto2', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_dia_semana_monto3'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_dia_semana_monto3', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        
        $arr_validacion['frec_dia_monto_bueno'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_dia_monto_regular', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_dia_monto_regular'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_dia_monto_malo', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_dia_monto_malo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_dia_eval_semana1_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_dia_eval_semana1_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_dia_eval_semana2_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_dia_eval_semana2_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_dia_eval_semana3_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_dia_eval_semana3_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_dia_eval_semana4_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_dia_eval_semana4_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_sem_semana1_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_sem_semana1_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_sem_semana2_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_sem_semana2_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_sem_semana3_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_sem_semana3_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_sem_semana4_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_sem_semana4_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_mes_mes1_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_mes_mes1_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_mes_mes2_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_mes_mes2_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('frec_mes_mes3_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['frec_mes_mes3_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('capacidad_monto_manual', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['capacidad_monto_manual'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('estacion_monto2', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['estacion_monto2'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('estacion_monto3', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['estacion_monto3'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_alq_energia_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_alq_energia_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_alq_energia_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_alq_energia_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_alq_agua_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_alq_agua_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_alq_agua_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_alq_agua_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_alq_internet_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_alq_internet_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_alq_internet_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_alq_internet_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_alq_combustible_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_alq_combustible_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_alq_combustible_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_alq_combustible_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_alq_libre1_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_alq_libre1_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_alq_libre1_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_alq_libre1_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_alq_libre1_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_alq_libre1_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_alq_libre2_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_alq_libre2_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_alq_libre2_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_alq_libre2_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_alq_libre2_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_alq_libre2_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_sal_aguinaldos_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_sal_aguinaldos_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_sal_aguinaldos_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_sal_aguinaldos_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_sal_libre1_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_sal_libre1_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_sal_libre1_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_sal_libre1_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_sal_libre1_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_sal_libre1_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_sal_libre2_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_sal_libre2_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_sal_libre2_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_sal_libre2_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_sal_libre2_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_sal_libre2_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_sal_libre3_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_sal_libre3_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_sal_libre3_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_sal_libre3_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_sal_libre3_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_sal_libre3_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_sal_libre4_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_sal_libre4_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_sal_libre4_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_sal_libre4_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_sal_libre4_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_sal_libre4_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_transporte_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_transporte_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_transporte_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_transporte_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_licencias_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_licencias_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_licencias_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_licencias_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_alimentacion_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_alimentacion_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_alimentacion_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_alimentacion_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_mant_vehiculo_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_mant_vehiculo_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_mant_vehiculo_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_mant_vehiculo_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_mant_maquina_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_mant_maquina_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_mant_maquina_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_mant_maquina_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_imprevistos_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_imprevistos_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_imprevistos_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_imprevistos_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_otros_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_otros_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_otros_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_otros_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_libre1_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_libre1_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_libre1_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_libre1_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_libre1_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_libre1_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_libre2_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_libre2_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_libre2_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_libre2_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_libre2_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_libre2_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_libre3_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_libre3_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_libre3_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_libre3_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_libre3_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_libre3_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_libre4_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_libre4_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_libre4_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_libre4_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_libre4_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_libre4_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_libre5_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_libre5_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_libre5_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_libre5_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_libre5_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_libre5_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_dependientes_ingreso', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_dependientes_ingreso'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_edad_hijos', LETRAS_NUMEROS, 'MAX(140)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_edad_hijos'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_alimentacion_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_alimentacion_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_alimentacion_aclaracion', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_alimentacion_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_energia_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_energia_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_energia_aclaracion', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_energia_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_agua_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_agua_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_agua_aclaracion', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_agua_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_gas_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_gas_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_gas_aclaracion', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_gas_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_telefono_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_telefono_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_telefono_aclaracion', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_telefono_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_celular_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_celular_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_celular_aclaracion', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_celular_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_internet_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_internet_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_internet_aclaracion', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_internet_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_tv_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_tv_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_tv_aclaracion', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_tv_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_impuestos_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_impuestos_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_impuestos_aclaracion', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_impuestos_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_alquileres_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_alquileres_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_alquileres_aclaracion', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_alquileres_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_educacion_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_educacion_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_educacion_aclaracion', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_educacion_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_transporte_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_transporte_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_transporte_aclaracion', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_transporte_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_salud_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_salud_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_salud_aclaracion', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_salud_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_empleada_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_empleada_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_empleada_aclaracion', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_empleada_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_diversion_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_diversion_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_diversion_aclaracion', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_diversion_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_vestimenta_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_vestimenta_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_vestimenta_aclaracion', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_vestimenta_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_otros_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_otros_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_otros_aclaracion', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_otros_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_libre1_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_libre1_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_libre1_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_libre1_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_libre1_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_libre1_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_libre2_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_libre2_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_libre2_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_libre2_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_libre2_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_libre2_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_libre3_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_libre3_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_libre3_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_libre3_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_libre3_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_libre3_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_libre4_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_libre4_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_libre4_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_libre4_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_libre4_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_libre4_aclaracion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_libre5_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_libre5_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_libre5_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['familiar_libre5_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('familiar_libre5_aclaracion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['familiar_libre5_aclaracion'] = clone $this->formulario_campos;

        $this->formulario_campos->CargarOpcionesValidacion('extra_cuota_prestamo_solicitado', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_cuota_prestamo_solicitado'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_amortizacion_otras_deudas', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_amortizacion_otras_deudas'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_cuota_maxima_credito', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_cuota_maxima_credito'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_amortizacion_credito', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_amortizacion_credito'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_efectivo_caja', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_efectivo_caja'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_ahorro_dpf', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_ahorro_dpf'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_cuentas_cobrar', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_cuentas_cobrar'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_inventario', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_inventario'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_otros_activos_corrientes', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_otros_activos_corrientes'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_activo_fijo', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_activo_fijo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_otros_activos_nocorrientes', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_otros_activos_nocorrientes'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_activos_actividades_secundarias', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_activos_actividades_secundarias'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_inmuebles_terrenos', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_inmuebles_terrenos'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_bienes_hogar', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_bienes_hogar'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_otros_activos_familiares', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_otros_activos_familiares'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_endeudamiento_credito', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_endeudamiento_credito'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_personal_ocupado', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_personal_ocupado'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_cuentas_pagar_proveedores', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_cuentas_pagar_proveedores'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_prestamos_financieras_corto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_prestamos_financieras_corto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_cuentas_pagar_corto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_cuentas_pagar_corto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_prestamos_financieras_largo', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_prestamos_financieras_largo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_cuentas_pagar_largo', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_cuentas_pagar_largo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_pasivo_actividades_secundarias', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_pasivo_actividades_secundarias'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('extra_pasivo_familiar', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['extra_pasivo_familiar'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('materia_nombre', LETRAS_NUMEROS, 'MAX(500)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['materia_nombre'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('materia_unidad_compra', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['materia_unidad_compra'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('materia_unidad_compra_cantidad', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['materia_unidad_compra_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('materia_unidad_uso', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['materia_unidad_uso'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('materia_unidad_uso_cantidad', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['materia_unidad_uso_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('materia_unidad_proceso', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['materia_unidad_proceso'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('materia_producto_medida', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['materia_producto_medida'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('materia_producto_medida_cantidad', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['materia_producto_medida_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('materia_precio_unitario', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['materia_precio_unitario'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('materia_aclaracion', LETRAS_NUMEROS, 'MAX(500)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['materia_aclaracion'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('proveedor_nombre', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['proveedor_nombre'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('proveedor_lugar_compra', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['proveedor_lugar_compra'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('proveedor_importe', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['proveedor_importe'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('proveedor_fecha_ultima', NUMEROS, 'MAX(11)|REQUERIDO|SINESPACIO|TIPO-DATE', '-|/');
        $arr_validacion['proveedor_fecha_ultima'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('proveedor_aclaracion', LETRAS_NUMEROS, 'MAX(500)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['proveedor_aclaracion'] = clone $this->formulario_campos;

        $this->formulario_campos->CargarOpcionesValidacion('inventario_descripcion', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['inventario_descripcion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('inventario_compra_cantidad', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['inventario_compra_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('inventario_compra_medida', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['inventario_compra_medida'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('inventario_venta_cantidad', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['inventario_venta_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('inventario_venta_medida', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['inventario_venta_medida'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('inventario_compra_precio', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['inventario_compra_precio'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('inventario_venta_precio', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['inventario_venta_precio'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('inventario_unidad_venta_compra', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['inventario_unidad_venta_compra'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('inventario_categoria', NUMEROS, 'MAX(0)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['inventario_categoria'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('intentario_aclaracion', LETRAS_NUMEROS, 'MAX()|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['intentario_aclaracion'] = clone $this->formulario_campos;

        $this->formulario_campos->CargarOpcionesValidacion('producto_nombre', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['producto_nombre'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('producto_venta_cantidad', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['producto_venta_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('producto_venta_medida', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['producto_venta_medida'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('producto_venta_costo', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['producto_venta_costo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('producto_venta_precio', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['producto_venta_precio'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('producto_costo_medida_unidad', LETRAS_NUMEROS, 'MAX(50)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['producto_costo_medida_unidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('producto_costo_medida_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['producto_costo_medida_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('producto_costo_medida_precio', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['producto_costo_medida_precio'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('producto_aclaracion', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['producto_aclaracion'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('producto_compra_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['producto_compra_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('producto_compra_medida', LETRAS_NUMEROS, 'MAX(50)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['producto_compra_medida'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('producto_compra_precio', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['producto_compra_precio'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('producto_unidad_venta_unidad_compra', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['producto_unidad_venta_unidad_compra'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('producto_unidad', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['producto_unidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('producto_medida_cantidad', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['producto_medida_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('producto_medida_precio', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['producto_medida_precio'] = clone $this->formulario_campos;

        $this->formulario_campos->CargarOpcionesValidacion('detalle_descripcion', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['detalle_descripcion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('detalle_cantidad', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['detalle_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('detalle_unidad', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['detalle_unidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('detalle_costo_unitario', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['detalle_costo_unitario'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('detalle_costo_medida_unidad', LETRAS_NUMEROS, 'MAX(50)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['detalle_costo_medida_unidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('detalle_costo_medida_precio', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['detalle_costo_medida_precio'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('detalle_costo_unidad_medida_uso', LETRAS_NUMEROS, 'MAX(50)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['detalle_costo_unidad_medida_uso'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('detalle_costo_unidad_medida_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['detalle_costo_unidad_medida_cantidad'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('inventario_registro_total', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['inventario_registro_total'] = clone $this->formulario_campos;
        
        // -- Formulario de Transporte
        
        $this->formulario_campos->CargarOpcionesValidacion('transporte_preg_sindicato', LETRAS_NUMEROS, 'MAX(100)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['transporte_preg_sindicato'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('transporte_preg_sindicato_lineas',  NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_preg_sindicato_lineas'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_preg_sindicato_grupos',  NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_preg_sindicato_grupos'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_preg_unidades_grupo',  NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_preg_unidades_grupo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_preg_grupo_rota',  LETRAS_NUMEROS, 'MAX(55)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['transporte_preg_grupo_rota'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_preg_lineas_buenas',  NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_preg_lineas_buenas'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_preg_lineas_regulares',  NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_preg_lineas_regulares'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_preg_lineas_malas',  NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_preg_lineas_malas'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('transporte_preg_jornada_inicia', TODO, 'MAX(20)|REQUERIDO|TIPO-TIME', '.');
        $arr_validacion["transporte_preg_jornada_inicia"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_preg_jornada_concluye', TODO, 'MAX(20)|REQUERIDO|TIPO-TIME', '.');
        $arr_validacion["transporte_preg_jornada_concluye"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_dia_lunes', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_dia_lunes'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_dia_martes', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_dia_martes'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_dia_miercoles', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_dia_miercoles'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_dia_jueves', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_dia_jueves'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_dia_viernes', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_dia_viernes'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_dia_sabado', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_dia_sabado'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_dia_domingo', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_dia_domingo'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea1_texto', LETRAS_NUMEROS, 'MAX(55)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['transporte_cliente_linea1_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea2_texto', LETRAS_NUMEROS, 'MAX(55)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['transporte_cliente_linea2_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea3_texto', LETRAS_NUMEROS, 'MAX(55)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['transporte_cliente_linea3_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea4_texto', LETRAS_NUMEROS, 'MAX(55)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['transporte_cliente_linea4_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea5_texto', LETRAS_NUMEROS, 'MAX(55)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['transporte_cliente_linea5_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea6_texto', LETRAS_NUMEROS, 'MAX(55)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['transporte_cliente_linea6_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea7_texto', LETRAS_NUMEROS, 'MAX(55)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['transporte_cliente_linea7_texto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea1_min', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_linea1_min'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea2_min', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_linea2_min'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea3_min', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_linea3_min'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea4_min', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_linea4_min'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea5_min', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_linea5_min'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea6_min', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_linea6_min'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea7_min', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_linea7_min'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea1_max', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_linea1_max'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea2_max', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_linea2_max'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea3_max', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_linea3_max'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea4_max', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_linea4_max'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea5_max', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_linea5_max'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea6_max', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_linea6_max'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_linea7_max', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_linea7_max'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_vueta_buena_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_vueta_buena_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_vueta_regular_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_vueta_regular_monto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_vueta_mala_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_vueta_mala_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_vueta_buena_numero',  NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_vueta_buena_numero'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_vueta_regular_numero',  NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_vueta_regular_numero'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_cliente_vueta_mala_numero',  NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_cliente_vueta_mala_numero'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('transporte_capacidad_sin_rotacion',  NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_capacidad_sin_rotacion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_capacidad_con_rotacion',  NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_capacidad_con_rotacion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_capacidad_tramo_largo_pasajero',  NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_capacidad_tramo_largo_pasajero'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_capacidad_tramo_corto_pasajero',  NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_capacidad_tramo_corto_pasajero'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('transporte_vuelta_buena_veces',  NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_vuelta_buena_veces'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_vuelta_regular_veces',  NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_vuelta_regular_veces'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_vuelta_mala_veces',  NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_vuelta_mala_veces'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('transporte_capacidad_tramo_largo_precio', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_capacidad_tramo_largo_precio'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_capacidad_tramo_corto_precio', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_capacidad_tramo_corto_precio'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_vuelta_buena_ocupacion', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_vuelta_buena_ocupacion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_vuelta_regular_ocupacion', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_vuelta_regular_ocupacion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('transporte_vuelta_mala_ocupacion', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['transporte_vuelta_mala_ocupacion'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('total_ingreso_bueno', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['total_ingreso_bueno'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('total_ingreso_regular', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['total_ingreso_regular'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('total_ingreso_malo', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['total_ingreso_malo'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('margen_nombre', LETRAS_NUMEROS, 'MAX(55)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['margen_nombre'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('margen_cantidad',  NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['margen_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('margen_unidad_medida', LETRAS_NUMEROS, 'MAX(55)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['margen_unidad_medida'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('margen_pasajeros',  NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['margen_pasajeros'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('margen_monto_unitario', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['margen_monto_unitario'] = clone $this->formulario_campos;
        
        
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_cambio_aceite_motor_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_cambio_aceite_motor_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_cambio_aceite_motor_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_cambio_aceite_motor_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_cambio_aceite_caja_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_cambio_aceite_caja_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_cambio_aceite_caja_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_cambio_aceite_caja_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_cambio_llanta_delanteras_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_cambio_llanta_delanteras_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_cambio_llanta_delanteras_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_cambio_llanta_delanteras_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_cambio_llanta_traseras_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_cambio_llanta_traseras_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_cambio_llanta_traseras_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_cambio_llanta_traseras_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_cambio_bateria_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_cambio_bateria_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_cambio_bateria_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_cambio_bateria_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_cambio_balatas_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_cambio_balatas_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_cambio_balatas_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_cambio_balatas_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_revision_electrico_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_revision_electrico_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_revision_electrico_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_revision_electrico_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_remachado_embrague_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_remachado_embrague_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_remachado_embrague_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_remachado_embrague_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_rectificacion_motor_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_rectificacion_motor_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_rectificacion_motor_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_rectificacion_motor_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_cambio_rodamiento_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_cambio_rodamiento_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_cambio_rodamiento_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_cambio_rodamiento_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_reparaciones_menores_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_reparaciones_menores_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_reparaciones_menores_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_reparaciones_menores_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_imprevistos_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_imprevistos_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_imprevistos_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_imprevistos_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_impuesto_propiedad_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_impuesto_propiedad_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_impuesto_propiedad_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_impuesto_propiedad_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_soat_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_soat_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_soat_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_soat_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_roseta_inspeccion_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_roseta_inspeccion_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_roseta_inspeccion_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_roseta_inspeccion_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_transporte_libre1_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_transporte_libre1_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_transporte_libre1_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_transporte_libre1_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_transporte_libre1_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_transporte_libre1_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_transporte_libre2_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_transporte_libre2_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_transporte_libre2_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_transporte_libre2_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_transporte_libre2_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_transporte_libre2_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_transporte_libre3_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_transporte_libre3_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_transporte_libre3_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_transporte_libre3_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_transporte_libre3_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_transporte_libre3_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_transporte_libre4_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_transporte_libre4_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_transporte_libre4_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_transporte_libre4_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_transporte_libre4_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_transporte_libre4_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_transporte_libre5_texto', LETRAS_NUMEROS, 'MAX(60)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['operativos_otro_transporte_libre5_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_transporte_libre5_cantidad', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_transporte_libre5_cantidad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('operativos_otro_transporte_libre5_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['operativos_otro_transporte_libre5_monto'] = clone $this->formulario_campos;
        
        
        $this->formulario_campos->CargarOpcionesValidacion('otros_descripcion_fuente', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['otros_descripcion_fuente'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('otros_descripcion_respaldo', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['otros_descripcion_respaldo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('otros_monto', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['otros_monto'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('pasivo_acreedor', LETRAS_NUMEROS, 'MAX(60)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['pasivo_acreedor'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('pasivo_saldo', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['pasivo_saldo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('pasivo_cuota_periodica', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['pasivo_cuota_periodica'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('pasivo_cuota_mensual', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['pasivo_cuota_mensual'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('pasivo_destino', LETRAS_NUMEROS, 'MAX(120)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['pasivo_destino'] = clone $this->formulario_campos;
        
        // -- Formularios Onboarding
        
        $this->formulario_campos->CargarOpcionesValidacion('monto_inicial', NUMEROS, 'MAX(16)|SINESPACIO|REQUERIDO|TIPO-NUMBER', '.|-');
        $arr_validacion['monto_inicial'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('direccion_email', LETRAS_NUMEROS, 'MAX(99)|TIPO-EMAIL|REQUERIDO', '@|.|_|-');
        $arr_validacion['direccion_email'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('cI_numeroraiz', NUMEROS, 'MAX(10)|SINESPACIO|TIPO-NUMBER|REQUERIDO', '.|-');
        $arr_validacion['cI_numeroraiz'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('cI_complemento', LETRAS_NUMEROS, 'MAX(2)|SINESPACIO', '');
        $arr_validacion['cI_complemento'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('cI_confirmacion_id', LETRAS_NUMEROS, 'MAX(25)|', '.|(|)|,');
        $arr_validacion['cI_confirmacion_id'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('di_primernombre', LETRAS, 'MAX(50)|REQUERIDO', ' ');
        $arr_validacion['di_primernombre'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('di_segundo_otrosnombres', LETRAS, 'MAX(50)|', ' ');
        $arr_validacion['di_segundo_otrosnombres'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('di_primerapellido', LETRAS, 'MAX(30)|REQUERIDO', ' ');
        $arr_validacion['di_primerapellido'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('di_segundoapellido', LETRAS, 'MAX(30)|', ' ');
        $arr_validacion['di_segundoapellido'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('di_fecha_nacimiento', NUMEROS, 'MAX(11)|SINESPACIO|TIPO-DATE', '.|-');
        $arr_validacion['di_fecha_nacimiento'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('di_fecha_vencimiento', NUMEROS, 'MAX(11)|SINESPACIO|TIPO-DATE', '.|-');
        $arr_validacion['di_fecha_vencimiento'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('di_apellido_casada', LETRAS, 'MAX(30)|', ' ');
        $arr_validacion['di_apellido_casada'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('dd_dependientes', NUMEROS, 'MAX(25)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['dd_dependientes'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('dir_ubicacionreferencial', LETRAS_NUMEROS, 'MAX(70)|', '.|(|)|,');
        $arr_validacion['dir_ubicacionreferencial'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('dir_av_calle_pasaje', LETRAS_NUMEROS, 'MAX(70)|REQUERIDO', '.|(|)|,');
        $arr_validacion['dir_av_calle_pasaje'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('dir_edif_cond_urb', LETRAS_NUMEROS, 'MAX(20)', '.|(|)|,');
        $arr_validacion['dir_edif_cond_urb'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('dir_numero', LETRAS_NUMEROS, 'MAX(20)', '.|-');
        $arr_validacion['dir_numero'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('dir_ubicacionreferencial_neg', LETRAS_NUMEROS, 'MAX(70)|', '.|(|)|,');
        $arr_validacion['dir_ubicacionreferencial_neg'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('dir_av_calle_pasaje_neg', LETRAS_NUMEROS, 'MAX(70)|REQUERIDO', '.|(|)|,');
        $arr_validacion['dir_av_calle_pasaje_neg'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('dir_edif_cond_urb_neg', LETRAS_NUMEROS, 'MAX(20)', '.|(|)|,');
        $arr_validacion['dir_edif_cond_urb_neg'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('dir_numero_neg', LETRAS_NUMEROS, 'MAX(20)', '.|-');
        $arr_validacion['dir_numero_neg'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('dir_notelefonico', NUMEROS, 'MAX(8)|SINESPACIO|TIPO-NUMBER|REQUERIDO', '.|-');
        $arr_validacion['dir_notelefonico'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('emp_nombre_empresa', LETRAS_NUMEROS, 'MAX(64)|', '.|(|)|,');
        $arr_validacion['emp_nombre_empresa'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('emp_direccion_trabajo', LETRAS_NUMEROS, 'MAX(64)|', '.|(|)|,');
        $arr_validacion['emp_direccion_trabajo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('emp_telefono_faxtrabaj', NUMEROS, 'MAX(8)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['emp_telefono_faxtrabaj'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('emp_antiguedad_empresa', NUMEROS, 'MAX(3)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['emp_antiguedad_empresa'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('emp_descripcion_cargo', LETRAS_NUMEROS, 'MAX(30)|', '.|(|)|,');
        $arr_validacion['emp_descripcion_cargo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('emp_fecha_ingreso', NUMEROS, 'MAX(11)|SINESPACIO|TIPO-DATE', '.|-');
        $arr_validacion['emp_fecha_ingreso'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('rp_nombres', LETRAS, 'MAX(20)|', ' ');
        $arr_validacion['rp_nombres'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('rp_primer_apellido', LETRAS, 'MAX(20)|', ' ');
        $arr_validacion['rp_primer_apellido'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('rp_segundo_apellido', LETRAS, 'MAX(20)|', ' ');
        $arr_validacion['rp_segundo_apellido'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('rp_direccion', LETRAS_NUMEROS, 'MAX(54)|', '.|(|)|,');
        $arr_validacion['rp_direccion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('rp_notelefonicos', NUMEROS, 'MAX(8)|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['rp_notelefonicos'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('con_primer_nombre', LETRAS, 'MAX(40)|', ' ');
        $arr_validacion['con_primer_nombre'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('con_segundo_nombre', LETRAS, 'MAX(15)|', ' ');
        $arr_validacion['con_segundo_nombre'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('con_primera_pellido', LETRAS, 'MAX(15)|', ' ');
        $arr_validacion['con_primera_pellido'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('con_segundoa_pellido', LETRAS, 'MAX(15)|', ' ');
        $arr_validacion['con_segundoa_pellido'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('ddc_motivo_permanencia_usa', LETRAS_NUMEROS, 'MAX(150)|', '.|(|)|,');
        $arr_validacion['ddc_motivo_permanencia_usa'] = clone $this->formulario_campos;
        
        // -- Req. Consulta COBIS y SEGIP
        
        $this->formulario_campos->CargarOpcionesValidacion('segip_operador_texto', LETRAS_NUMEROS, 'MAX(250)', '!|@|#|$|%|.|-|,', CAJATEXTAREA);
        $arr_validacion["segip_operador_texto"] = clone $this->formulario_campos;
        
        // Token
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_token_dimension', NUMEROS, 'MAX(2)|SINESPACIO|TIPO-NUMBER', '');
        $arr_validacion['conf_token_dimension'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('conf_token_validez', NUMEROS, 'MAX(4)|SINESPACIO|TIPO-NUMBER', '');
        $arr_validacion['conf_token_validez'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_token_texto', LETRAS_NUMEROS, 'MAX(280)|', '!|¡|@|#|$|%|&|/|¿|?|+|<|>|=|\'|;|:|.|-|(|)|,');
        $arr_validacion["conf_token_texto"] = clone $this->formulario_campos;
        
        // WS JWT
        $this->formulario_campos->CargarOpcionesValidacion('conf_jwt_ws_uri', LETRAS_NUMEROS, 'MAX(145)|SINACENTO|SINESPACIO|REQUERIDO', '?|#|.|:|_|-|/|\\');
        $arr_validacion["conf_jwt_ws_uri"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_jwt_client_secret', LETRAS_NUMEROS, 'MAX(60)|SINESPACIO|REQUERIDO', '!|¡|@|#|$|%|&|/|¿|?|+|<|>|=|\'|;|:|.|-|(|)|,');
        $arr_validacion["conf_jwt_client_secret"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('conf_jwt_username', LETRAS_NUMEROS, 'MAX(40)|SINESPACIO|REQUERIDO', '!|¡|@|#|$|%|&|/|¿|?|+|<|>|=|\'|;|:|.|-|(|)|,');
        $arr_validacion["conf_jwt_username"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('conf_jwt_password', LETRAS_NUMEROS, 'MAX(40)|SINESPACIO|REQUERIDO', '!|¡|@|#|$|%|&|/|¿|?|+|<|>|=|\'|;|:|.|-|(|)|,');
        $arr_validacion["conf_jwt_password"] = clone $this->formulario_campos;
        
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_captura_intentos_texto', LETRAS_NUMEROS, 'MAX(80)|', '!|¡|@|#|$|%|&|/|¿|?|+|<|>|=|\'|;|:|.|-|(|)|,');
        $arr_validacion["conf_captura_intentos_texto"] = clone $this->formulario_campos;
        
        // WS COBIS
        $this->formulario_campos->CargarOpcionesValidacion('conf_cobis_ws_uri', LETRAS_NUMEROS, 'MAX(145)|SINACENTO|SINESPACIO|REQUERIDO', '?|#|.|:|_|-|/|\\');
        $arr_validacion["conf_cobis_ws_uri"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('conf_cobis_texto', LETRAS_NUMEROS, 'MAX(280)|', '!|¡|@|#|$|%|&|/|¿|?|+|<|>|=|\'|;|:|.|-|(|)|,');
        $arr_validacion["conf_cobis_texto"] = clone $this->formulario_campos;
        
        // WS SEGIP        
        $this->formulario_campos->CargarOpcionesValidacion('conf_segip_ws_uri', LETRAS_NUMEROS, 'MAX(145)|SINACENTO|SINESPACIO|REQUERIDO', '?|#|.|:|_|-|/|\\');
        $arr_validacion["conf_segip_ws_uri"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('conf_segip_texto', LETRAS_NUMEROS, 'MAX(280)|', '!|¡|@|#|$|%|&|/|¿|?|+|<|>|=|\'|;|:|.|-|(|)|,');
        $arr_validacion["conf_segip_texto"] = clone $this->formulario_campos;

        // WS PRUEBA DE VIDA
        $this->formulario_campos->CargarOpcionesValidacion('conf_life_ws_uri', LETRAS_NUMEROS, 'MAX(145)|SINACENTO|SINESPACIO|REQUERIDO', '?|#|.|:|_|-|/|\\');
        $arr_validacion["conf_life_ws_uri"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('conf_life_texto', LETRAS_NUMEROS, 'MAX(280)|', '!|¡|@|#|$|%|&|/|¿|?|+|<|>|=|\'|;|:|.|-|(|)|,');
        $arr_validacion["conf_life_texto"] = clone $this->formulario_campos;
        
        // WS OCR
        $this->formulario_campos->CargarOpcionesValidacion('conf_ocr_ws_uri', LETRAS_NUMEROS, 'MAX(145)|SINACENTO|SINESPACIO|REQUERIDO', '?|#|.|:|_|-|/|\\');
        $arr_validacion["conf_ocr_ws_uri"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('conf_ocr_texto', LETRAS_NUMEROS, 'MAX(280)|', '!|¡|@|#|$|%|&|/|¿|?|+|<|>|=|\'|;|:|.|-|(|)|,');
        $arr_validacion["conf_ocr_texto"] = clone $this->formulario_campos;
        
        // WS FLUJO COBIS 
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_f_cobis_header', LETRAS_NUMEROS, 'MAX(1450)|SINACENTO|SINESPACIO|REQUERIDO', '=|#|.|:|_|-|/|\\');
        $arr_validacion["conf_f_cobis_header"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_f_cobis_uri_cliente_ci', LETRAS_NUMEROS, 'MAX(195)|SINACENTO|SINESPACIO|REQUERIDO', '?|#|.|:|_|-|/|\\');
        $arr_validacion["conf_f_cobis_uri_cliente_ci"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('conf_f_cobis_uri_cliente_cobis', LETRAS_NUMEROS, 'MAX(195)|SINACENTO|SINESPACIO|REQUERIDO', '?|#|.|:|_|-|/|\\');
        $arr_validacion["conf_f_cobis_uri_cliente_cobis"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_f_cobis_uri_cliente_alta', LETRAS_NUMEROS, 'MAX(195)|SINACENTO|SINESPACIO|REQUERIDO', '?|#|.|:|_|-|/|\\');
        $arr_validacion["conf_f_cobis_uri_cliente_alta"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('conf_f_cobis_uri_cliente_alta_params', TODO, 'MAX(1450)|REQUERIDO', '.', CAJATEXTAREA);
        $arr_validacion["conf_f_cobis_uri_cliente_alta_params"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_f_cobis_uri_apertura', LETRAS_NUMEROS, 'MAX(195)|SINACENTO|SINESPACIO|REQUERIDO', '?|#|.|:|_|-|/|\\');
        $arr_validacion["conf_f_cobis_uri_apertura"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('conf_f_cobis_uri_apertura_params', TODO, 'MAX(1450)|REQUERIDO', '.', CAJATEXTAREA);
        $arr_validacion["conf_f_cobis_uri_apertura_params"] = clone $this->formulario_campos;
        
        // -- Nuevos estados Microcréditos
        $this->formulario_campos->CargarOpcionesValidacion('prospecto_jda_eval_texto', LETRAS_NUMEROS, 'MAX(280)', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(:)|,', CAJATEXTAREA);
        $arr_validacion["prospecto_jda_eval_texto"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('prospecto_desembolso_monto', NUMEROS, 'MAX(16)|SINESPACIO|TIPO-NUMBER|REQUERIDO', '.|-');
        $arr_validacion['prospecto_desembolso_monto'] = clone $this->formulario_campos;
        
        // Numero de operacion
        $this->formulario_campos->CargarOpcionesValidacion('registro_num_proceso', NUMEROS, 'MAX(11)|SINESPACIO|TIPO-NUMBER|REQUERIDO', '');
        $arr_validacion['registro_num_proceso'] = clone $this->formulario_campos;
        
        // -- Integración con AD
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_ad_host', LETRAS_NUMEROS, 'MAX(195)|SINACENTO|SINESPACIO|REQUERIDO', '?|#|.|:|_|-|/|\\');
        $arr_validacion["conf_ad_host"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('conf_ad_dominio', LETRAS_NUMEROS, 'MAX(195)|SINACENTO|SINESPACIO|REQUERIDO', '?|#|.|:|_|-|/|\\');
        $arr_validacion["conf_ad_dominio"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('conf_ad_dn', TODO, 'MAX(1450)|REQUERIDO', '.');
        $arr_validacion["conf_ad_dn"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_ad_test_user', LETRAS_NUMEROS, 'SINACENTO|SINESPACIO|MAX(100)', '.|_|-');
        $arr_validacion["conf_ad_test_user"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('conf_ad_test_pass', LETRAS_NUMEROS, 'MAX(100)|SINESPACIO', '!|¡|@|#|$|%|&|/|¿|?|+|<|>|=|\'|;|:|.|-|(|)|,');
        $arr_validacion["conf_ad_test_pass"] = clone $this->formulario_campos;
        
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
        
        $id_caja = "password_anterior";
        $validacion = $this->arr_validacion[$id_caja];
        $arr_formulario_cajas[$id_caja] = LibreriaUsir_GeneracionCajaPasswordHtml($id_caja, 'SI_ENTER|MAX(' . $validacion->CARACTERES_MAXIMO . ')');
        
        $id_caja = "password_nuevo";
        $validacion = $this->arr_validacion[$id_caja];
        $arr_formulario_cajas[$id_caja] = LibreriaUsir_GeneracionCajaPasswordHtml($id_caja, 'SI_ENTER|MAX(' . $validacion->CARACTERES_MAXIMO . ')');
        
        $id_caja = "password_repetir";
        $validacion = $this->arr_validacion[$id_caja];
        $arr_formulario_cajas[$id_caja] = LibreriaUsir_GeneracionCajaPasswordHtml($id_caja, 'SI_ENTER|MAX(' . $validacion->CARACTERES_MAXIMO . ')');
        
        // FORMULARIOS FIE - INICIO
        
            $combo_brm = Array(
                array("id" => "1", "campoDescrip" => "Bueno"),
                array("id" => "2", "campoDescrip" => "Regular"),
                array("id" => "3", "campoDescrip" => "Malo")
            );

            $combo_arb = Array(
                array("id" => "1", "campoDescrip" => "Alta"),
                array("id" => "2", "campoDescrip" => "Regular"),
                array("id" => "3", "campoDescrip" => "Baja")
            );
            
            $combo_arb_aux = Array(
                array("id" => "1", "campoDescrip" => "Alta"),
                array("id" => "2", "campoDescrip" => "Regular"),
                array("id" => "3", "campoDescrip" => "Baja"),
                array("id" => "4", "campoDescrip" => "Sin Actividad")
            );
        
            $combo_sino = Array(
                array("id" => "0", "campoDescrip" => "No"),
                array("id" => "1", "campoDescrip" => "Si")
            );
            
            $combo_meses = Array(
                array("id" => "1", "campoDescrip" => "Enero"),
                array("id" => "2", "campoDescrip" => "Febrero"),
                array("id" => "3", "campoDescrip" => "Marzo"),
                array("id" => "4", "campoDescrip" => "Abril"),
                array("id" => "5", "campoDescrip" => "Mayo"),
                array("id" => "6", "campoDescrip" => "Junio"),
                array("id" => "7", "campoDescrip" => "Julio"),
                array("id" => "8", "campoDescrip" => "Agosto"),
                array("id" => "9", "campoDescrip" => "Septiembre"),
                array("id" => "10", "campoDescrip" => "Octubre"),
                array("id" => "11", "campoDescrip" => "Noviembre"),
                array("id" => "12", "campoDescrip" => "Diciembre")
            );
            
            $combo_frecuencia_pasivo = Array(
                array("id" => "1", "campoDescrip" => "Diario"),
                array("id" => "7", "campoDescrip" => "Semanal"),
                array("id" => "15", "campoDescrip" => "Quincenal"),
                array("id" => "30", "campoDescrip" => "Mensual"),
                array("id" => "60", "campoDescrip" => "Bimensual"),
                array("id" => "90", "campoDescrip" => "Trimestral"),
                array("id" => "120", "campoDescrip" => "Cuatrimestral"),
                array("id" => "150", "campoDescrip" => "Quinquemestre"),
                array("id" => "180", "campoDescrip" => "Semestral"),
                array("id" => "360", "campoDescrip" => "Anual"),
                array("id" => "0", "campoDescrip" => "Irregular")
            );
            
            $combo_frecuencia = Array(
                array("id" => "1", "campoDescrip" => "Diario"),
                array("id" => "7", "campoDescrip" => "Semanal"),
                array("id" => "15", "campoDescrip" => "Quincenal"),
                array("id" => "30", "campoDescrip" => "Mensual"),
                array("id" => "60", "campoDescrip" => "Bimensual"),
                array("id" => "90", "campoDescrip" => "Trimestral"),
                array("id" => "120", "campoDescrip" => "Cuatrimestral"),
                array("id" => "150", "campoDescrip" => "Quinquemestre"),
                array("id" => "180", "campoDescrip" => "Semestral"),
                array("id" => "360", "campoDescrip" => "Anual")
            );
            
            $combo_frecuencia_extendido = Array(
                array("id" => "1", "campoDescrip" => "Diario"),
                array("id" => "7", "campoDescrip" => "Semanal"),
                array("id" => "15", "campoDescrip" => "Quincenal"),
                array("id" => "30", "campoDescrip" => "Mensual"),
                array("id" => "60", "campoDescrip" => "Bimensual"),
                array("id" => "90", "campoDescrip" => "Trimestral"),
                array("id" => "120", "campoDescrip" => "Cuatrimestral"),
                array("id" => "150", "campoDescrip" => "Quinquemestre"),
                array("id" => "180", "campoDescrip" => "Semestral"),
                array("id" => "360", "campoDescrip" => "Anual"),
                array("id" => "720", "campoDescrip" => "2 Años"),
                array("id" => "1080", "campoDescrip" => "3 Años"),
                array("id" => "1440", "campoDescrip" => "4 Años"),
                array("id" => "1800", "campoDescrip" => "5 Años")
            );
            
            $combo_extension = Array(
                array("id" => "1", "campoDescrip" => "CH."),
                array("id" => "2", "campoDescrip" => "LP."),
                array("id" => "3", "campoDescrip" => "CB."),
                array("id" => "4", "campoDescrip" => "OR."),
                array("id" => "5", "campoDescrip" => "PT."),
                array("id" => "6", "campoDescrip" => "TJ."),
                array("id" => "7", "campoDescrip" => "SC."),
                array("id" => "8", "campoDescrip" => "BE."),
                array("id" => "9", "campoDescrip" => "PD."),
                array("id" => "10", "campoDescrip" => "EXT")
            );
        
            $combo_margen = Array(
                array("id" => "1", "campoDescrip" => "Poco menos del 50%"),
                array("id" => "2", "campoDescrip" => "El 50%"),
                array("id" => "3", "campoDescrip" => "Poco más del 50%"),
                array("id" => "4", "campoDescrip" => "Mucho más del 50%"),
                array("id" => "5", "campoDescrip" => "Casi el 100%"),
                array("id" => "6", "campoDescrip" => "El 100%")
            );
            
            $combo_mercaderia = Array(
                array("id" => "1", "campoDescrip" => "MP"),
                array("id" => "2", "campoDescrip" => "PP"),
                array("id" => "3", "campoDescrip" => "PT"),
                array("id" => "4", "campoDescrip" => "Mercadería")
            );
            
            $combo_interes = Array(
                array("id" => "1", "campoDescrip" => "Bajo"),
                array("id" => "2", "campoDescrip" => "Medio"),
                array("id" => "3", "campoDescrip" => "Alto")
            );
            
            $combo_costos = Array(
                //array("id" => "1", "campoDescrip" => "Inventario"),
                array("id" => "2", "campoDescrip" => "Costeo"),
                array("id" => "3", "campoDescrip" => "Valor Personalizado")
            );
            
            $combo_evaluacion = Array(
                array("id" => "0", "campoDescrip" => "No Evaluado"),
                array("id" => "1", "campoDescrip" => "Aprobado"),
                array("id" => "2", "campoDescrip" => "Rechazado")
            );
        
        $id_caja = 'prospecto_evaluacion';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_evaluacion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
            
        $id_caja = 'general_ci_extension';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_extension, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = 'general_interes';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_interes, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'detalle_costo_medida_convertir';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'producto_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'materia_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'proveedor_frecuencia_dias';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'proveedor_frecuencia_dias';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'inventario_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'frec_seleccion';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        $arr_frec_seleccion = Array(
                array("id" => "1", "campoDescrip" => "Diario"),
                array("id" => "2", "campoDescrip" => "Semanal"),
                array("id" => "3", "campoDescrip" => "Mensual")
            );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_frec_seleccion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'frec_dia_semana_sel_brm';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_brm, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'frec_dia_semana_brm2';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_brm, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'frec_dia_semana_brm3';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_brm, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'frec_dia_semana_sel';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        $arr_frec_dia_semana_sel = Array(
                array("id" => "1", "campoDescrip" => "1ra"),
                array("id" => "2", "campoDescrip" => "2da"),
                array("id" => "3", "campoDescrip" => "3ra"),
                array("id" => "4", "campoDescrip" => "4ta")
            );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_frec_dia_semana_sel, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'estacion_sel';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'estacion_sel_arb';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_arb, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'estacion_sel_mes';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_meses, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);


        $id_caja = 'estacion_ene_arb';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_arb_aux, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'estacion_feb_arb';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_arb_aux, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'estacion_mar_arb';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_arb_aux, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'estacion_abr_arb';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_arb_aux, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'estacion_may_arb';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_arb_aux, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'estacion_jun_arb';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_arb_aux, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'estacion_jul_arb';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_arb_aux, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'estacion_ago_arb';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_arb_aux, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'estacion_sep_arb';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_arb_aux, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'estacion_oct_arb';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_arb_aux, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'estacion_nov_arb';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_arb_aux, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'estacion_dic_arb';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_arb_aux, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);


        $id_caja = 'frec_dia_eval_semana1_brm';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_brm, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'frec_dia_eval_semana2_brm';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_brm, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'frec_dia_eval_semana3_brm';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_brm, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'frec_dia_eval_semana4_brm';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_brm, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);


        $id_caja = 'frec_mes_sel';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_meses, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

        $id_caja = 'margen_utilidad_productos';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_margen, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'porcentaje_participacion_proveedores';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_margen, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'producto_seleccion';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'producto_categoria_mercaderia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_mercaderia, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = 'producto_opcion';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_costos, 'id', 'campoDescrip', '', $strValorCaja);
        
        // -- Transporte
        
        $id_caja = "transporte_preg_trabaja_semana";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $transporte_preg_trabaja_semana = Array(
            array("id" => "1", "campoDescrip" => "1"),
            array("id" => "2", "campoDescrip" => "2"),
            array("id" => "3", "campoDescrip" => "3"),
            array("id" => "4", "campoDescrip" => "4"),
            array("id" => "5", "campoDescrip" => "5"),
            array("id" => "6", "campoDescrip" => "6"),
            array("id" => "7", "campoDescrip" => "7")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $transporte_preg_trabaja_semana, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = "transporte_preg_vehiculo_ano";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        
            $transporte_preg_vehiculo_ano = Array();
            $transporte_preg_vehiculo_ano[] = array("id" => '1', "campoDescrip" => 'Anterior a 1970');

            for ($x = 1970; $x <= (int)date("Y"); $x++)
            {
                $transporte_preg_vehiculo_ano[] = array("id" => $x, "campoDescrip" => $x);
            }
        
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $transporte_preg_vehiculo_ano, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = "transporte_preg_vehiculo_combustible";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $transporte_preg_vehiculo_combustible = Array(
            array("id" => "1", "campoDescrip" => "GASOLINA"),
            array("id" => "2", "campoDescrip" => "DIESEL"),
            array("id" => "3", "campoDescrip" => "GNV"),
            array("id" => "4", "campoDescrip" => "GLP"),
            array("id" => "5", "campoDescrip" => "GASOLINA-GNV"),
            array("id" => "6", "campoDescrip" => "DIESEL-GNV"),
            array("id" => "7", "campoDescrip" => "GASOLINA-GLP"),
            array("id" => "8", "campoDescrip" => "DIESEL-GLP")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $transporte_preg_vehiculo_combustible, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = "transporte_tipo_prestatario";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $transporte_tipo_prestatario = Array(
            array("id" => "1", "campoDescrip" => "PROPIETARIO"),
            array("id" => "2", "campoDescrip" => "PROPIETARIO QUE PERCIBE RENTA"),
            array("id" => "3", "campoDescrip" => "CHOFER")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $transporte_tipo_prestatario, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = "transporte_tipo_transporte";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $transporte_tipo_transporte = Array(
            array("id" => "1", "campoDescrip" => "MICROBUS"),
            array("id" => "2", "campoDescrip" => "MINIBUS"),
            array("id" => "3", "campoDescrip" => "TRUFI"),
            array("id" => "4", "campoDescrip" => "RADIO TAXI"),
            array("id" => "5", "campoDescrip" => "TAXI"),
            array("id" => "6", "campoDescrip" => "MOTO TAXI")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $transporte_tipo_transporte, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_cambio_aceite_motor_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_cambio_aceite_caja_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_cambio_llanta_delanteras_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_cambio_llanta_traseras_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        
        $id_caja = 'operativos_cambio_bateria_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_cambio_balatas_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_revision_electrico_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_remachado_embrague_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_rectificacion_motor_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_cambio_rodamiento_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_reparaciones_menores_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_imprevistos_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_impuesto_propiedad_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_soat_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_roseta_inspeccion_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_otro_transporte_libre1_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_otro_transporte_libre2_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_otro_transporte_libre3_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_otro_transporte_libre4_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'operativos_otro_transporte_libre5_frecuencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_extendido, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'pasivo_periodo';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_frecuencia_pasivo, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'pasivo_rfto';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = "pasivo_tipo";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $pasivo_tipo = Array(
            array("id" => "SN", "campoDescrip" => "SN"),
            array("id" => "M0", "campoDescrip" => "M0"),
            array("id" => "M1", "campoDescrip" => "M1"),
            array("id" => "M2", "campoDescrip" => "M2"),
            array("id" => "N0", "campoDescrip" => "N0"),
            array("id" => "N1", "campoDescrip" => "N1"),
            array("id" => "N2", "campoDescrip" => "N2"),
            array("id" => "P1", "campoDescrip" => "P1"),
            array("id" => "P0", "campoDescrip" => "P0"),
            array("id" => "P2", "campoDescrip" => "P2"),
            array("id" => "H0", "campoDescrip" => "H0"),
            array("id" => "H1", "campoDescrip" => "H1"),
            array("id" => "H2", "campoDescrip" => "H2"),
            array("id" => "H3", "campoDescrip" => "H3"),
            array("id" => "H4", "campoDescrip" => "H4"),
            array("id" => "M3", "campoDescrip" => "M3"),
            array("id" => "M4", "campoDescrip" => "M4"),
            array("id" => "M5", "campoDescrip" => "M5"),
            array("id" => "M6", "campoDescrip" => "M6"),
            array("id" => "M7", "campoDescrip" => "M7"),
            array("id" => "M8", "campoDescrip" => "M8"),
            array("id" => "M9", "campoDescrip" => "M9"),
            array("id" => "P3", "campoDescrip" => "P3"),
            array("id" => "P4", "campoDescrip" => "P4"),
            array("id" => "P5", "campoDescrip" => "P5"),
            array("id" => "P6", "campoDescrip" => "P6"),
            array("id" => "C0", "campoDescrip" => "C0"),
            array("id" => "C1", "campoDescrip" => "C1"),
            array("id" => "C2", "campoDescrip" => "C2"),
            array("id" => "C3", "campoDescrip" => "C3")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $pasivo_tipo, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        
        $id_caja = 'operacion_criterio';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        $arr_operacion_criterio = Array(
                array("id" => "0", "campoDescrip" => "Mes"),
                array("id" => "1", "campoDescrip" => "Año")
            );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_operacion_criterio, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $combo_tiempo_detalle = array();
        for($hours=0; $hours<15; $hours++) // the interval for hours is '1'
        {
            for($mins=0; $mins<60; $mins+=5) // the interval for mins is '30'
            {
            $aux_hora = str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT);
                    $combo_tiempo_detalle[] = array("id" => $aux_hora.':00', "campoDescrip" => $aux_hora);
            }
        }
        
        $combo_tiempo_corto = array();
        for($hours=0; $hours<24; $hours++) // the interval for hours is '1'
        {
            for($mins=0; $mins<60; $mins+=30) // the interval for mins is '30'
            {
            $aux_hora = str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT);
                    $combo_tiempo_corto[] = array("id" => $aux_hora.':00', "campoDescrip" => $aux_hora);
            }
        }
        
        $id_caja = 'transporte_preg_trabaja_dia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_tiempo_corto, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'transporte_preg_tiempo_no_trabaja';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_tiempo_detalle, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'transporte_preg_tiempo_parada';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_tiempo_detalle, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'transporte_preg_tiempo_vuelta';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_tiempo_detalle, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = "transporte_cliente_frecuencia";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $transporte_tipo_prestatario = Array(
            array("id" => "1", "campoDescrip" => "Semana"),
            array("id" => "2", "campoDescrip" => "Quincena"),
            array("id" => "4", "campoDescrip" => "Mes")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $transporte_tipo_prestatario, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'inventario_registro';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        $arr_frec_seleccion = Array(
                array("id" => "0", "campoDescrip" => "Registro Detallado"),
                array("id" => "1", "campoDescrip" => "Total con Respaldo")
            );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_frec_seleccion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        // FORMULARIOS FIE - FIN
        
        $id_caja = "tarea_activo";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "1", "campoDescrip" => "Activo"),
            array("id" => "0", "campoDescrip" => "No Activo")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = "etapa_alerta_hora";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "0", "campoDescrip" => "00:00"),
            array("id" => "1", "campoDescrip" => "01:00"),
            array("id" => "2", "campoDescrip" => "02:00"),
            array("id" => "3", "campoDescrip" => "03:00"),
            array("id" => "4", "campoDescrip" => "04:00"),
            array("id" => "5", "campoDescrip" => "05:00"),
            array("id" => "6", "campoDescrip" => "06:00"),
            array("id" => "7", "campoDescrip" => "07:00"),
            array("id" => "8", "campoDescrip" => "08:00"),
            array("id" => "9", "campoDescrip" => "09:00"),
            array("id" => "10", "campoDescrip" => "10:00"),
            array("id" => "11", "campoDescrip" => "11:00"),
            array("id" => "12", "campoDescrip" => "12:00"),
            array("id" => "13", "campoDescrip" => "13:00"),
            array("id" => "14", "campoDescrip" => "14:00"),
            array("id" => "15", "campoDescrip" => "15:00"),
            array("id" => "16", "campoDescrip" => "16:00"),
            array("id" => "17", "campoDescrip" => "17:00"),
            array("id" => "18", "campoDescrip" => "18:00"),
            array("id" => "19", "campoDescrip" => "19:00"),
            array("id" => "20", "campoDescrip" => "20:00"),
            array("id" => "21", "campoDescrip" => "21:00"),
            array("id" => "22", "campoDescrip" => "22:00"),
            array("id" => "23", "campoDescrip" => "23:00"),
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = "conf_credenciales_req_upper";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "0", "campoDescrip" => "No"),
            array("id" => "1", "campoDescrip" => "Si")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = "conf_credenciales_req_num";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "0", "campoDescrip" => "No"),
            array("id" => "1", "campoDescrip" => "Si")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = "conf_credenciales_req_esp";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "0", "campoDescrip" => "No"),
            array("id" => "1", "campoDescrip" => "Si")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = "conf_correo_protocol";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "mail", "campoDescrip" => "MAIL"),
            array("id" => "sendmail", "campoDescrip" => "SENDMAIL"),
            array("id" => "smtp", "campoDescrip" => "SMTP")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = "conf_correo_mailtype";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "text", "campoDescrip" => "TEXT"),
            array("id" => "html", "campoDescrip" => "HTML")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = "conf_correo_charset";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "utf-8", "campoDescrip" => "UTF-8"),
            array("id" => "iso-8859-1", "campoDescrip" => "ISO-8859-1"),
            array("id" => "us-ascii", "campoDescrip" => "US-ASCII")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = "catalogo_tipo_codigo";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "cI_lugar_emisionoextension", "campoDescrip" => "cI_lugar_emisionoextension"),
            array("id" => "di_genero", "campoDescrip" => "di_genero"),
            array("id" => "di_estadocivil", "campoDescrip" => "di_estadocivil"),
            array("id" => "dd_profesion", "campoDescrip" => "dd_profesion"),
            array("id" => "dd_nivel_estudios", "campoDescrip" => "dd_nivel_estudios"),
            array("id" => "dd_proposito_rel_comercial", "campoDescrip" => "dd_proposito_rel_comercial"),
            array("id" => "dec_ingresos_mensuales", "campoDescrip" => "dec_ingresos_mensuales"),
            array("id" => "dec_nivel_egresos", "campoDescrip" => "dec_nivel_egresos"),
            array("id" => "dir_tipo_direccion", "campoDescrip" => "dir_tipo_direccion"),
            array("id" => "ae_ambiente", "campoDescrip" => "ae_ambiente"),
            array("id" => "emp_tipo_empresa", "campoDescrip" => "emp_tipo_empresa"),
            array("id" => "emp_codigo_actividad", "campoDescrip" => "emp_codigo_actividad"),
            array("id" => "rp_nexo_cliente", "campoDescrip" => "rp_nexo_cliente"),
            array("id" => "dir_departamento", "campoDescrip" => "dir_departamento"),
            array("id" => "dir_provincia", "campoDescrip" => "dir_provincia"),
            array("id" => "dir_localidad_ciudad", "campoDescrip" => "dir_localidad_ciudad"),
            array("id" => "dir_barrio_zona_uv", "campoDescrip" => "dir_barrio_zona_uv"),
            array("id" => "ae_sector_economico", "campoDescrip" => "ae_sector_economico"),
            array("id" => "ae_subsector_economico", "campoDescrip" => "ae_subsector_economico"),
            array("id" => "ae_actividad_economica", "campoDescrip" => "ae_actividad_economica"),
            array("id" => "ae_actividad_fie", "campoDescrip" => "ae_actividad_fie"),
            array("id" => "tipo_cuenta", "campoDescrip" => "tipo_cuenta")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = "antecedentes_resultado";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "1", "campoDescrip" => "Aprobar Pre-Afiliación"),
            array("id" => "2", "campoDescrip" => "Rechazar Pre-Afiliación")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', '', $strValorCaja);

            // -- Evaluación Legal
            
            $id_caja = "evaluacion_doc_nit";
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            $arrayTipoContratacion = Array(
                array("id" => "1", "campoDescrip" => "No Aplica"),
                array("id" => "2", "campoDescrip" => "Adjunto en File"),
                array("id" => "3", "campoDescrip" => "Requisito con Excepción")
            );
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja, 'onchange="VerTablaEvaluacion(this, \'1\')"');
            
            $id_caja = "evaluacion_doc_certificado";
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            $arrayTipoContratacion = Array(
                array("id" => "1", "campoDescrip" => "No Aplica"),
                array("id" => "2", "campoDescrip" => "Adjunto en File"),
                array("id" => "3", "campoDescrip" => "Requisito con Excepción")
            );
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja, 'onchange="VerTablaEvaluacion(this, \'2\')"');
            
            $id_caja = "evaluacion_doc_ci";
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            $arrayTipoContratacion = Array(
                array("id" => "1", "campoDescrip" => "No Aplica"),
                array("id" => "2", "campoDescrip" => "Adjunto en File"),
                array("id" => "3", "campoDescrip" => "Requisito con Excepción")
            );
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja, 'onchange="VerTablaEvaluacion(this, \'3\')"');
            
            $id_caja = "evaluacion_doc_test";
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            $arrayTipoContratacion = Array(
                array("id" => "1", "campoDescrip" => "No Aplica"),
                array("id" => "2", "campoDescrip" => "Adjunto en File"),
                array("id" => "3", "campoDescrip" => "Requisito con Excepción")
            );
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja, 'onchange="VerTablaEvaluacion(this, \'4\')"');
            
            $id_caja = "evaluacion_doc_poder";
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            $arrayTipoContratacion = Array(
                array("id" => "1", "campoDescrip" => "No Aplica"),
                array("id" => "2", "campoDescrip" => "Adjunto en File"),
                array("id" => "3", "campoDescrip" => "Requisito con Excepción")
            );
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja, 'onchange="VerTablaEvaluacion(this, \'5\')"');
            
            $id_caja = "evaluacion_doc_funde";
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            $arrayTipoContratacion = Array(
                array("id" => "1", "campoDescrip" => "No Aplica"),
                array("id" => "2", "campoDescrip" => "Adjunto en File"),
                array("id" => "3", "campoDescrip" => "Requisito con Excepción")
            );
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja, 'onchange="VerTablaEvaluacion(this, \'6\')"');
            
            $id_caja = "evaluacion_ci_pertenece";
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            $arrayTipoContratacion = Array(
                array("id" => "1", "campoDescrip" => "Propietario"),
                array("id" => "2", "campoDescrip" => "Representante Legal")
            );
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

            $id_caja = 'documento_mandatorio';
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
            // Catalogo ;
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
            
            $id_caja = 'conf_rekognition';
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
            // Catalogo ;
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
            
            $id_caja = 'conf_onboarding_correo';
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
            // Catalogo ;
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
            
            $id_caja = 'rechazado_envia';
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
            // Catalogo ;
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
            
            $id_caja = 'estructura_regional_estado';
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
            // Catalogo ;
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
            
            
            $id_caja = "envio";
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            $arrayTipoContratacion = Array(
                array("id" => "0", "campoDescrip" => "No Seleccionado"),
                array("id" => "1", "campoDescrip" => "Domicilio"),
                array("id" => "2", "campoDescrip" => "Trabajo")
            );
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
            
            $id_caja = 'di_indefinido';
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
            // Catalogo ;
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
            
            $id_caja = 'ddc_ciudadania_usa';
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
            // Catalogo ;
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
            
            $id_caja = 'catalogo_estado';
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
            // Catalogo ;
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
            
            $id_caja = 'catalogo_nuevo';
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
            // Catalogo ;
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
            
            
            $id_caja = 'cuenta_cerrada_envia';
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '1';
            // Catalogo ;
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
            
        // -- Req. Consulta COBIS y SEGIP
        
        $id_caja = 'segip_operador_resultado';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        $arr_frec_seleccion = Array(
                array("id" => "0", "campoDescrip" => "No Satisfactorio"),
                array("id" => "1", "campoDescrip" => "Satisfactorio")
            );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_frec_seleccion, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = 'conf_token_otp';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'conf_segip_mandatorio';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'conf_cobis_mandatorio';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $combo_intentos_segip = array();
        for($intentos=1; $intentos<9; $intentos++) // the interval for hours is '1'
        {
            $combo_intentos_segip[] = array("id" => $intentos, "campoDescrip" => $intentos);
        }
        
        $id_caja = 'conf_segip_intentos';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_intentos_segip, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'conf_captura_intentos';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_intentos_segip, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $combo_ocr_porcentaje = array();
        for($intentos=0; $intentos<100; $intentos++) // the interval for hours is '1'
        {
            $combo_ocr_porcentaje[] = array("id" => $intentos, "campoDescrip" => $intentos . '%');
        }
        
        $id_caja = 'conf_ocr_porcentaje';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_ocr_porcentaje, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $combo_conf_f_cobis_intentos = array();
        for($intentos=1; $intentos<21; $intentos++) // the interval for hours is '1'
        {
            $combo_conf_f_cobis_intentos[] = array("id" => $intentos, "campoDescrip" => $intentos);
        }
        
        $id_caja = 'conf_f_cobis_intentos';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_conf_f_cobis_intentos, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $combo_conf_f_cobis_intentos_tiempo = array();
        for($intentos=1; $intentos<10; $intentos++) // the interval for hours is '1'
        {
            $combo_conf_f_cobis_intentos_tiempo[] = array("id" => $intentos, "campoDescrip" => $intentos . ($intentos==1 ? ' minuto' : ' minutos'));
        }
        for($intentos=2; $intentos<12; $intentos++) // the interval for hours is '1'
        {
            $combo_conf_f_cobis_intentos_tiempo[] = array("id" => $intentos*5, "campoDescrip" => $intentos*5 . ' minutos');
        }
        for($intentos=1; $intentos<=24; $intentos++) // the interval for hours is '1'
        {
            $combo_conf_f_cobis_intentos_tiempo[] = array("id" => $intentos*60, "campoDescrip" => $intentos . ($intentos==1 ? ' hora' : ' horas'));
        }
        
        $id_caja = 'conf_f_cobis_intentos_tiempo';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_conf_f_cobis_intentos_tiempo, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'conf_f_cobis_procesa_activo';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'conf_f_cobis_intentos_operativo';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        // -- Nuevos estados Microcréditos
        
        $id_caja = 'prospecto_jda_eval';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        $arr_frec_seleccion = Array(
                array("id" => "1", "campoDescrip" => "Aprobado"),
                array("id" => "2", "campoDescrip" => "Rechazado")
            );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_frec_seleccion, 'id', 'campoDescrip', '', $strValorCaja);
        
        // -- Integración AD
        
        $id_caja = 'conf_ad_activo';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : '';
        // Catalogo ;
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $combo_sino, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
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
