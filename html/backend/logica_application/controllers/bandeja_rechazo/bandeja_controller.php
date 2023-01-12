<?php
/**
 * @file 
 * Codigo que implementa el controlador para la gestión de Prospectos
 * @brief BANDEJA DE LA INSTANCIA
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para la gestión de Prospectos - RECHAZO
 * @brief BANDEJA DE LA INSTANCIA
 * @class Conf_catalogo_controller 
 */
class Bandeja_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 29;
        
        // Código de la Etapa Propio de la Bandeja
        protected $codigo_etapa = 15;

    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
    
    public function Bandeja_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');

        // Bandeja Evaluación Legal
        
        $filtro = 'p.prospecto_etapa=' . $this->codigo_etapa . ' AND p.prospecto_rechazado=1 AND p.prospecto_consolidado=1 AND p.prospecto_observado_app=0';
        
        $arrResultado = $this->mfunciones_logica->ListadoBandejasProspecto($filtro);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;
                        
            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "prospecto_id" => $value["prospecto_id"],
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "tipo_persona_codigo" => $value["tipo_persona_id"],
                    "tipo_persona_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["tipo_persona_id"], 'tipo_persona'),
                    "empresa_id" => $value["empresa_id"],
                    "empresa_categoria_codigo" => $value["empresa_categoria_codigo"],
                    "empresa_categoria_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["empresa_categoria"], 'empresa_categoria'),
                    "fecha_derivada_etapa" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["fecha_derivada_etapa"]),
                    "empresa_nombre" => $value["empresa_nombre"],
                    "tiempo_etapa" => $this->mfunciones_generales->TiempoEtapaProspecto($value["fecha_derivada_etapa"], $this->codigo_etapa),
                    "prospecto_excepcion_codigo" => $value["prospecto_excepcion"],
                    "prospecto_excepcion_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["prospecto_excepcion"], 'excepcion_estado'),
                    "prospecto_observado" => $value["prospecto_observado"]
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
            
            $arrResumen = $this->mfunciones_generales->TiempoEtapaResumen($lst_resultado);
            
            // Obtener el Tiempo asignado de la etapa
            $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa($this->codigo_etapa);
            $tiempo_etapa_asignado = $arrEtapa[0]['etapa_tiempo'];
        }
        else 
        {
            $lst_resultado[0] = $arrResultado;
            $arrResumen[0] = $arrResultado;
            $tiempo_etapa_asignado = 0;
        }
        
        // Se establece una variable de SESSION para determinar en que bandeja se encuentra el usuario y al Observar el documento, regresar allí
        
        $_SESSION['direccion_bandeja_actual'] = 'Bandeja/Rechazo/Ver';
        $_SESSION['dato_etapa_actual'] = $this->codigo_etapa;
        
        $data["arrRespuesta"] = $lst_resultado;
        $data["arrResumen"] = $arrResumen;
        $data["tiempo_etapa_asignado"] = $tiempo_etapa_asignado;
        
        $this->load->view('bandeja_rechazo/view_bandeja_ver', $data);
    }
    
    public function Rechazo_Form() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
                
        if(!isset($_POST['codigo']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // Se captura el valor
            $codigo_prospecto = $this->input->post('codigo', TRUE);
            
            // Datos del Prospecto
            $DatosProspecto = $this->mfunciones_generales->GetDatosEmpresaCorreo($codigo_prospecto);

            if (!isset($DatosProspecto[0])) 
            {
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            }
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

            $data["arrRespuesta"] = $DatosProspecto;

            $this->load->view('bandeja_rechazo/view_rechazo_form', $data);
        }
    }
    
    public function Rechazo_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_POST['estructura_id']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // Se capturan los datos

            $codigo_prospecto = $this->input->post('estructura_id', TRUE);            
            $rechazo_detalle = $this->input->post('excepcion_detalle', TRUE);
            
            $nombre_usuario = $_SESSION["session_informacion"]["login"];
            $fecha_actual = date('Y-m-d H:i:s');

            if((int)$codigo_prospecto == 0 || $rechazo_detalle == "")
            {
                js_error_div_javascript($this->lang->line('FormularioIncompleto'));
                exit();
            }
            
            // Paso 1: Se obtiene el estado y etapa actual del prospecto
                
            // Detalle del Prospecto
            $arrResultado3 = $this->mfunciones_logica->VerificaProspectoRechazo($codigo_prospecto);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            // Se continua siempre el prospecto este marcado como rechazado
            if($arrResultado3[0]['prospecto_rechazado'] == 1)
            {                
                // Se registra el detalle/justificación del rechazo
                $this->mfunciones_logica->RechazoProspecto($rechazo_detalle, $nombre_usuario, $fecha_actual, $codigo_prospecto);                
                
                $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, $this->codigo_etapa, $this->codigo_etapa, $nombre_usuario, $fecha_actual, 0);
                /***  REGISTRAR SEGUIMIENTO ***/
                $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, $this->codigo_etapa, 7, 'Notificar Rechazo', $nombre_usuario, $fecha_actual);
            }
            
            $this->Bandeja_Ver();
        }
    }
}
?>