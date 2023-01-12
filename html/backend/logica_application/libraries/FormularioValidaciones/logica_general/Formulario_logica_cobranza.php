<?php

/**
 * Description of Fomulario_Inicio
 *
 * @author Joel Aliaga
 * Agosto 2022
 */
class Formulario_logica_cobranza {

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
        
        // Formulario
        
        // Numero de operacion
        $this->formulario_campos->CargarOpcionesValidacion('registro_num_proceso', NUMEROS, 'MAX(11)|SINESPACIO|TIPO-NUMBER|REQUERIDO', '');
        $arr_validacion['registro_num_proceso'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('norm_primer_nombre', LETRAS, 'MAX(50)|REQUERIDO|', '\''); $arr_validacion['norm_primer_nombre'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('norm_segundo_nombre', LETRAS, 'MAX(50)|', '\''); $arr_validacion['norm_segundo_nombre'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('norm_primer_apellido', LETRAS, 'MAX(30)|REQUERIDO|', '\''); $arr_validacion['norm_primer_apellido'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('norm_segundo_apellido', LETRAS, 'MAX(30)|', '\''); $arr_validacion['norm_segundo_apellido'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('norm_correo', LETRAS_NUMEROS, 'MAX(100)|SINESPACIO|TIPO-EMAIL', '@|.|_|-'); $arr_validacion['norm_correo'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('norm_cel', NUMEROS, 'MAX(8)|SINESPACIO|TIPO-TEL', '.|-'); $arr_validacion['norm_cel'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('norm_telefono', NUMEROS, 'MAX(15)|SINESPACIO|TIPO-TEL', '.|-'); $arr_validacion['norm_telefono'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('norm_actividad', LETRAS_NUMEROS, 'MAX(80)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['norm_actividad'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('norm_rel_cred_otro', LETRAS_NUMEROS, 'MAX(50)|', '.|,|-'); $arr_validacion['norm_rel_cred_otro'] = clone $this->formulario_campos;

        // -- Direcciones
        $this->formulario_campos->CargarOpcionesValidacion('rd_direccion', LETRAS_NUMEROS, 'MAX(70)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['rd_direccion'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('rd_edificio', LETRAS_NUMEROS, 'MAX(20)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['rd_edificio'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('rd_numero', LETRAS_NUMEROS, 'MAX(20)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['rd_numero'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('rd_referencia_texto', LETRAS_NUMEROS, 'MAX(70)|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,'); $arr_validacion['rd_referencia_texto'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('rd_trabajo_lugar_otro', LETRAS_NUMEROS, 'MAX(50)|', '.|,|-'); $arr_validacion['rd_trabajo_lugar_otro'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('rd_trabajo_realiza_otro', LETRAS_NUMEROS, 'MAX(50)|', '.|,|-'); $arr_validacion['rd_trabajo_realiza_otro'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('rd_dom_tipo_otro', LETRAS_NUMEROS, 'MAX(50)|', '.|,|-'); $arr_validacion['rd_dom_tipo_otro'] = clone $this->formulario_campos;
        
        // -- Visita
        
        $this->formulario_campos->CargarOpcionesValidacion('cv_fecha_compromiso', LETRAS_NUMEROS, 'MAX(11)|SINESPACIO|TIPO-DATE', '.|-'); $arr_validacion['cv_fecha_compromiso'] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('cv_observaciones', LETRAS_NUMEROS, 'MAX(280)', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,',CAJATEXTAREA); $arr_validacion['cv_observaciones'] = clone $this->formulario_campos;
        
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
        
        $arr_norm_cliente = Array(
            array("id" => "0", "campoDescrip" => "Nuevo"),
            array("id" => "1", "campoDescrip" => "Antiguo")
        );
        
        $arr_norm_dependencia = Array(
            array("id" => "0", "campoDescrip" => " -- "),
            array("id" => "1", "campoDescrip" => "Dependiente"),
            array("id" => "2", "campoDescrip" => "Independiente")
        );
        
        $arr_norm_moneda = Array(
            array("id" => "bob", "campoDescrip" => "Bolivianos"),
            array("id" => "usd", "campoDescrip" => "Dólares")
        );
        
        $arr_norm_trabajo_lugar = Array(
            array("id" => "1", "campoDescrip" => "Propio"),
            array("id" => "2", "campoDescrip" => "Alquilado"),
            array("id" => "3", "campoDescrip" => "Anticrético"),
            array("id" => "99", "campoDescrip" => "Otro")
        );
        
        $arr_norm_trabajo_realiza = Array(
            array("id" => "1", "campoDescrip" => "Calle"),
            array("id" => "2", "campoDescrip" => "Tienda"),
            array("id" => "3", "campoDescrip" => "Puesto de Mercado"),
            array("id" => "4", "campoDescrip" => "Sindicato"),
            array("id" => "99", "campoDescrip" => "Otro")
        );

        $arr_norm_trabajo_actividad_pertenece = Array(
            array("id" => "0", "campoDescrip" => "Deudor"),
            array("id" => "1", "campoDescrip" => "Codeudor")
        );

        $arr_norm_dom_tipo = Array(
            array("id" => "1", "campoDescrip" => "Propia"),
            array("id" => "2", "campoDescrip" => "En Alquiler"),
            array("id" => "3", "campoDescrip" => "En Anticrético"),
            array("id" => "4", "campoDescrip" => "Familiar"),
            array("id" => "99", "campoDescrip" => "Otro")
        );
        
        $arr_norm_dir_referencia = Array(
            array("id" => "0", "campoDescrip" => " -- "),
            array("id" => "1", "campoDescrip" => "Geolocalización"),
            array("id" => "2", "campoDescrip" => "Croquis")
        );
        
        $arr_norm_rel_cred = Array(
            array("id" => "1", "campoDescrip" => "Deudor"),
            array("id" => "2", "campoDescrip" => "Codeudor"),
            array("id" => "3", "campoDescrip" => "Garante"),
            array("id" => "99", "campoDescrip" => "Otro")
        );
        
        $arr_rd_tipo = Array(
            array("id" => "1", "campoDescrip" => "Negocio"),
            array("id" => "2", "campoDescrip" => "Domicilio")
        );
        
        // COMBOS - FIN
        
        // Formulario
        
        $id_caja = 'norm_rel_cred';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_norm_rel_cred, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = 'rd_tipo';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_rd_tipo, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'rd_referencia';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_norm_dir_referencia, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'rd_trabajo_lugar';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_norm_trabajo_lugar, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = 'rd_trabajo_realiza';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_norm_trabajo_realiza, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = 'rd_trabajo_actividad_pertenece';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_norm_trabajo_actividad_pertenece, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'rd_dom_tipo';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        // Catalogo
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_norm_dom_tipo, 'id', 'campoDescrip', '', $strValorCaja);
        
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
