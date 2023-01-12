<?php

/**
 * Description of Fomulario_Inicio
 *
 * @author Joel Aliaga
 */
class Formulario_onboarding {

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
        
        $this->formulario_campos->CargarOpcionesValidacion('terceros_nombre_persona', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['terceros_nombre_persona'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('terceros_ci', LETRAS_NUMEROS, 'MAX(45)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['terceros_ci'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('terceros_email', LETRAS_NUMEROS, 'MAX(150)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['terceros_email'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('terceros_telefono', LETRAS_NUMEROS, 'MAX(45)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['terceros_telefono'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('terceros_nit', LETRAS_NUMEROS, 'MAX(45)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['terceros_nit'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('terceros_profesion', LETRAS_NUMEROS, 'MAX(150)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['terceros_profesion'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('terceros_conyugue_nombre', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['terceros_conyugue_nombre'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('terceros_conyugue_actividad', LETRAS_NUMEROS, 'MAX(150)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['terceros_conyugue_actividad'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('terceros_referencias', LETRAS_NUMEROS, 'MAX(300)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['terceros_referencias'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('terceros_actividad_principal', LETRAS_NUMEROS, 'MAX(150)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['terceros_actividad_principal'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('terceros_lugar_trabajo', LETRAS_NUMEROS, 'MAX(200)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['terceros_lugar_trabajo'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('terceros_cargo', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion['terceros_cargo'] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('terceros_ingreso', NUMEROS, 'MAX(16)|REQUERIDO|SINESPACIO|TIPO-NUMBER', '.|-');
        $arr_validacion['terceros_ingreso'] = clone $this->formulario_campos;
        
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
        
        $id_caja = 'terceros_ano_ingreso';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
            
            $terceros_ano_ingreso = Array();

            for ($x = 1970; $x <= (int)date("Y"); $x++)
            {
                $terceros_ano_ingreso[] = array("id" => $x, "campoDescrip" => $x);
            }
        
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $terceros_ano_ingreso, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'terceros_estado_civil';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        $arr_frec_dia_semana_sel = Array(
                array("id" => "1", "campoDescrip" => "Soltero(a)"),
                array("id" => "2", "campoDescrip" => "Casado(a)"),
                array("id" => "3", "campoDescrip" => "Divorciado(a)"),
                array("id" => "4", "campoDescrip" => "Viudo(a)")
            );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_frec_dia_semana_sel, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
        $id_caja = 'terceros_pais';
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : 'SINSELECCIONAR';
        $arr_frec_dia_semana_sel = Array(
                array("id" => "1", "campoDescrip" => "1ra"),
                array("id" => "2", "campoDescrip" => "2da"),
                array("id" => "3", "campoDescrip" => "3ra"),
                array("id" => "4", "campoDescrip" => "4ta")
            );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arr_frec_dia_semana_sel, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);
        
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
