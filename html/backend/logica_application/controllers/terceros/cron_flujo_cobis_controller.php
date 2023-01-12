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
 * Controlador para la gestión de Prospectos - BACKOFFICE
 * @brief BANDEJA DE LA INSTANCIA
 * @class Conf_catalogo_controller 
 */
class Cron_flujo_cobis_controller extends CI_Controller {
        
    function __construct() {
        parent::__construct();
    }
    
    /**
     * @brief FLUJO COBIS
     * @author JRAD
     * @date 2021
     */
    
    public function CronFlujo()
    {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(php_sapi_name() !== 'cli')
        {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        else
        {
            try {
                
                $logger = new CI_Log();

                // Obtener parámetros de configuración
                $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);

                $arrResultado = $this->mfunciones_logica->ObtenerListaRegistrosFlujoCOBIS(((int)$arrConf[0]['conf_f_cobis_intentos_tiempo']<=0 ? 1 : $arrConf[0]['conf_f_cobis_intentos_tiempo']));
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0]))
                {
                    foreach ($arrResultado as $key => $value) {
                        // *** Llamar a función para Aprobar y Flujo COBIS en 2do plano ***
                        $this->mfunciones_generales->Aprobar_FlujoCobis_background($value['terceros_id'], $value['f_cobis_actual_usuario'], $value['accion_usuario']);
                    }
                }
                
                sleep(1);
                
                // Buscar los registros que, por algna razón, se quedaron estancados en el flujo por 10 minutos
                $arrResultado2 = $this->mfunciones_logica->ObtenerListaRegistrosFlujoCOBIS_stuck(10);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);
                
                if (isset($arrResultado2[0]))
                {
                    foreach ($arrResultado2 as $key2 => $value2) {
                        $info_cliente = new stdClass();
                        $info_cliente->codigo_solicitud = $value2['terceros_id'];
                        $info_cliente->codigo_agencia_fie = $value2['codigo_agencia_fie'];
                        $info_cliente->codigo_usuario = $value2['f_cobis_actual_usuario'];
                        $info_cliente->accion_usuario = $value2['accion_usuario'];
                        
                        $this->mfunciones_generales->ExcepcionGenerica($info_cliente, 0);
                    }
                }
                
                sleep(1);
                
                // stuck/release para los registros de proceso "Asistido" que hayan sido consolidados y que no realizan el cambio de estado a "Aprobado", manteniéndose como "Solicitud Registrada" a fin de reingresarlos al flujo COBIS.
                $this->load->model('mfunciones_soa');
                $arrResultado3 = $this->mfunciones_soa->ObtenerListaRegistrosFlujoCOBIS_stuck_aprobado(((int)$arrConf[0]['conf_f_cobis_intentos_tiempo']<=0 ? 1 : $arrConf[0]['conf_f_cobis_intentos_tiempo']));
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
                
                if (isset($arrResultado3[0]))
                {
                    $arrUsuario = $this->mfunciones_logica->getUsuarioCriterio('ejecutivo', $arrResultado3[0]['ejecutivo_id']);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrUsuario);
                    
                    foreach ($arrResultado3 as $key => $value) {
                        
                        // *** Llamar a función para Aprobar y Flujo COBIS en 2do plano ***
                        $this->mfunciones_generales->Aprobar_FlujoCobis_background($value['terceros_id'], $arrUsuario[0]['usuario_id'], $value['accion_usuario']);
                    }
                }
                
                
            } catch (Exception $exc) {
                $logger->write_log('error', 'Error flujo COBIS (cron) - ' . $exc->getTraceAsString());
            }
        }
    }
}
?>