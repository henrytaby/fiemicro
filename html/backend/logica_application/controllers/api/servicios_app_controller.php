<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
require(APPPATH.'/libraries/REST_Controller.php');
class servicios_app_controller extends REST_Controller
{ 
    public function __construct() {
            parent::__construct();
            $this->load->model('mfunciones_generales');
            $this->load->model('mfunciones_logica');
            $this->load->model('mfunciones_microcreditos');
            $this->lang->load('general', 'castellano');
            $this->load->library('encrypt');
            $this->load->model('form_dinamico');
            $this->load->model("mfunciones_activeDirectory");
            $this->load->model("mfunciones_cobranzas");
    }
    
    private function getCodigo($codigo){
	$resultado = new stdClass();
	$resultado->codigo = 0;
	$resultado->tipo_persona = 0;
	
	if($codigo == '')
	{
            return $resultado;
	}
	
	$codigo_aux = explode('_', $codigo);
	
	if(count($codigo_aux) == 2)
	{
            $resultado->codigo = $codigo_aux[0];
            $resultado->tipo_persona = $codigo_aux[1];
	}
	else
	{
            $resultado->codigo = $codigo_aux[0];
	}
	
	return $resultado;
    }
    
    public function ServiciosAPP_post(){

        // 1. SE VERIFICA QUE SE ESTE ENVIANDO EL PARÁMETRO
        if(!isset($_POST['array_servicio']))
        {
            // Si no existe el parámetro se devuelve el error
            $arrError =  array(
                            "error" => true,
                            "errorMessage" => "Servicio Desconocido.",
                            "errorCode" => 101,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
                    );
            
            $this->response($arrError, 403);
        }

        // 2. SE CAPTURA EL ARRAY ENVIADO POR LA APP
            $arrRecibida = $this->input->post('array_servicio');
        
        // 3. SE CONVIERTE A ARRAY (SI ES QUE ES JSON)
        
            if($this->isJSON($arrRecibida))
            {
                    $arrRecibida = json_decode($arrRecibida, true);
            }
			
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRecibida);
            
			
        // 4. SE VERIFICA QUE EL FORMATO ESTE CORRECTO
        
            if(!empty($this->mfunciones_generales->VerificaEstructuraREST($arrRecibida)))
            {
                // Si no cumple con la estructura establecida, se devuelve el error 100 (Estructura Inválida del Array)            
                $arrError =  array(		
                                "error" => true,
                                "errorMessage" => "Estructura Invalida de la Solicitud.",
                                "errorCode" => 100,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                        );

                $this->response($arrError, 400);
            }
        
        // 5. SE VERIFICA QUE LAS CREDENCIALES (USUARIO Y CONTRASEÑA) CORRESPONDAN A UN USUARIO CON ACCESO
            
            
            $usuarioAPP = $arrRecibida[0]['identificador'];
            $passwordAPP = $arrRecibida[0]['password'];
            
            $ad_active = $this->mfunciones_activeDirectory->getConfigAD();
            $user = $this->mfunciones_activeDirectory->VerificarUserOnboarding($usuarioAPP);

            /**
             * 1ra Condicion : Verificacion si autenticacion por AD esta habilitado
             * 2da Condicion : Verificacion si el usuario es de tipo onboarding
             */
            if((int)$ad_active[0]["conf_ad_activo"] == 1 && (int)$user[0]['perfil_app_id'] != 2){
                // Flujo con AD
                $arrResultadoLogin = $this -> mfunciones_activeDirectory->ObtenerLista_AutenticacionPrincipalAD($usuarioAPP, $passwordAPP, "app");

                if(isset($arrResultadoLogin['error'])){
                    $arrError =  array(
                        "error" => true,
                        "errorMessage" => $arrResultadoLogin['error'],
                        "errorCode" => 90,
                        "result" => array(
                            "mensaje" => $this->lang->line('IncompletoApp')
                        )
                    );
    
                    $this->response($arrError, 401);
                }
                
            }else{
                // Flujo normal
                if($user[0]['perfil_app_id'] != 2){
                    $passwordAPP = sha1(sha1($passwordAPP));
                }
                $arrResultadoLogin = $this->mfunciones_logica->VerificaCredencialesAPP($usuarioAPP, $passwordAPP);
            }

            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultadoLogin);

            if (!isset($arrResultadoLogin[0]))
            {
                // Si las credenciales son incorrectas o el usaurio no existe, se muestra un mensaje de error     
                $arrError =  array(
                                "error" => true,
                                "errorMessage" => "Credenciales Incorrectas.",
                                "errorCode" => 90,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                        );

                $this->response($arrError, 401);
            }

            $usuario_codigo = $arrResultadoLogin[0]['usuario_activo'];

            if ($usuario_codigo == 0)
            {
                // Si las credenciales son incorrectas o el usaurio no existe, se muestra un mensaje de error     
                $arrError =  array(
                                "error" => true,
                                "errorMessage" => "La cuenta no esta activa, comuniquese con el Administrador del Sistema.",
                                "errorCode" => 91,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                        );
                $this->response($arrError, 401);
            }

        // 6. SE OBTIENE EL NOMBRE DEL SERVICIO
        $nombre_servicio = $arrRecibida[0]['servicio'];

        // 7. SE OBTIENE EL ARRAY DE LOS PARÁMETROS

        $arrParametros = $arrRecibida[0]['parametros'];
		
        // 8. SE LLAMA A LAS FUNCIONES DE ACUERDO AL NOMBRE DEL SERVICIO SOLICITADO
        
        switch ($nombre_servicio) 
        {
            
            /*************** APP FASE 2 - INICIO ****************************/
            
            case 'DatallePantallaPrincipal':

                    $arrResultado = $this->DatallePantallaPrincipal($arrParametros, $usuarioAPP, $nombre_servicio, $arrResultadoLogin[0]['ejecutivo_id'], $arrResultadoLogin[0]['usuario_nombres'], $user[0]['perfil_app_id'], $user[0]['perfil_app_nombre']);                    
                
                break;
            
            case 'MenuUsuarioRol':

                    $arrResultado = $this->MenuUsuarioRol($arrParametros, $user[0]['perfil_app_id'], $user[0]['perfil_app_nombre']);                    
                
                break;
            
            case 'ObtenerEstiloContenido':

                    $arrResultado = $this->ObtenerEstiloContenido($arrParametros, $usuarioAPP, $nombre_servicio);                    
                
                break;
            
            /*************** APP FASE 2 - FIN ****************************/
            
            /*************** APP FASE 3 - INICIO ****************************/
            
            case 'DatallePantallaWebRegistro':

                    $arrResultado = $this->DatallePantallaWebRegistro($arrParametros, $usuarioAPP, $nombre_servicio, $arrResultadoLogin[0]['ejecutivo_id'], $arrResultadoLogin[0]['usuario_nombres']);                    
                
                break;
            
            case 'VerTareasAsignadas':

                    $arrResultado = $this->VerTareasAsignadas($arrParametros, $usuarioAPP, $nombre_servicio);
            
                break;
            
            /*************** APP FASE 3 - FIN ****************************/
            
            /*************** FORMULARIOS DINÁMICOS - INICIO ****************************/
            
            case 'ListadoFormPublicados':

                    $arrResultado = $this->ListadoFormPublicados($arrParametros, $usuarioAPP, $nombre_servicio);                    
                
                break;
            
            
            case 'ListaElementosForm':

                    $arrResultado = $this->ListaElementosForm($arrParametros, $usuarioAPP, $nombre_servicio);                    
                
                break;
            
            case 'GuardarRegistroForm':

                    $arrResultado = $this->GuardarRegistroForm($arrParametros, $usuarioAPP, $nombre_servicio, $arrResultadoLogin[0]['ejecutivo_id']);                    
                
                break;
           
            
            /*************** FORMULARIOS DINÁMICOS - FIN ****************************/
            
            
            /*************** LECTOR QR - INICIO ****************************/
            
            case 'AutenticacionUsuarioQR':

                    $arrResultado = $this->AutenticacionUsuarioQR($arrParametros, $usuarioAPP, $nombre_servicio);                    
                
                break;
            
            case 'ListadoCategoriaQR':

                    $arrResultado = $this->ListadoCategoriaQR($arrParametros);                    
                
                break;
            
            case 'VerificaQR':

                    $arrResultado = $this->VerificaQR($arrParametros);                    
                
                break;
            
            /*************** LECTOR QR - FIN ****************************/
            
            /*************** ROCKETBOT - INICIO ****************************/
            
            case 'RB_Autenticacion':

                    $arrResultado = $this->RB_Autenticacion($arrParametros, $usuarioAPP, $nombre_servicio);                    
                
                break;
            
            /*************** ROCKETBOT - FIN ****************************/
            
            case 'AutenticacionUsuario':

                $arrResultado = $this->AutenticacionUsuario($arrParametros, $usuarioAPP, $nombre_servicio, $ad_active[0]["conf_ad_activo"], $arrResultadoLogin[0]['usuario_nombres']);
                    
                break;

            case 'RenovarPassUsuario':
                    $arrResultado = $this->RenovarPassUsuario($arrParametros, $usuarioAPP, $nombre_servicio, $ad_active[0]["conf_ad_activo"]);                    
                break;
            
            case 'ListadoBandejaProspectos':

                    $arrResultado = $this->ListadoBandejaProspectos($arrParametros);

                break;
            
            case 'ListadoCatalogo':

                    $arrResultado = $this->ListadoCatalogo($arrParametros);

                break;
            
            case 'ListadoCatalogoTipoPersona':

                    $arrResultado = $this->ListadoCatalogoTipoPersona($arrParametros);

                break;

            case 'ListadoObsProspectos':

                    $arrResultado = $this->ListadoObsProspectos($arrParametros);

                break;
            
            case 'ActualizaCheckIn':

                    $arrResultado = $this->ActualizaCheckIn($arrParametros, $usuarioAPP, $nombre_servicio);
                
                break;
                
            case 'ActualizaCheckOut':

                    $arrResultado = $this->ActualizaCheckOut($arrParametros, $usuarioAPP, $nombre_servicio, $user[0]['perfil_app_nombre']);
                
                break;
            
            case 'ListadoServicios':

                    $arrResultado = $this->ListadoServicios($arrParametros);

                break;
            
            case 'ListadoDetalleProspecto':

                    $arrResultado = $this->ListadoDetalleProspecto($arrParametros);

                break;
            
             case 'DetalleComercioPorNIT':

                    $arrResultado = $this->DetalleComercioPorNIT($arrParametros);

                break;

            case 'ListadoHorariosEjecutivo':

                    $arrResultado = $this->ListadoHorariosEjecutivo($arrParametros);

                break;
            
            case 'InsertarProspectoAPP':

                    $arrResultado = $this->InsertarProspectoAPP($arrParametros, $usuarioAPP, $nombre_servicio);

                break;
            
            case 'ActualizarProspectoAPP':

                    $arrResultado = $this->ActualizarProspectoAPP($arrParametros, $usuarioAPP, $nombre_servicio);

                break;
            
            case 'ListadoDocumentosEnviar':

                    $arrResultado = $this->ListadoDocumentosEnviar($arrParametros);
                
                 break;
             
            case 'EnviarDocumentos':

                    $arrResultado = $this->EnviarDocumentos($arrParametros);
                
                break;
            
            case 'ListadoDocumentosDigitalizar':

                    $arrResultado = $this->ListadoDocumentosDigitalizar($arrParametros);
                
                break;
				
            case 'DocumentoProspectoPDF':

                    $arrResultado = $this->DocumentoProspectoPDF($arrParametros);
                
                break;
			
            case 'InsertarProspectoPDF':

                    $arrResultado = $this->InsertarProspectoPDF($arrParametros, $usuarioAPP, $nombre_servicio);
                
                break;
				
            case 'RemitirProspectoCumplimiento':

                    $arrResultado = $this->RemitirProspectoCumplimiento($arrParametros, $usuarioAPP, $nombre_servicio);
                
                break;			
			
            case 'ConsolidarProspecto':

                    $arrResultado = $this->ConsolidarProspecto($arrParametros, $usuarioAPP, $nombre_servicio, $arrResultadoLogin[0]['usuario_id']);
            
                break;
            
            case 'ListadoBandejaMantenimientos':

                    $arrResultado = $this->ListadoBandejaMantenimientos($arrParametros);
            
                break;
            
            case 'ActualizaCheckInMantenimiento':

                    $arrResultado = $this->ActualizaCheckInMantenimiento($arrParametros, $usuarioAPP, $nombre_servicio);
            
                break;            

            case 'ListadoTareas':

                    $arrResultado = $this->ListadoTareas($arrParametros);
            
                break;
            
            case 'ActualizarHorarioMantenimiento':

                    $arrResultado = $this->ActualizarHorarioMantenimiento($arrParametros, $usuarioAPP, $nombre_servicio);
            
                break;
            
            case 'BusquedaNITEmpresaEjecutivo':

                    $arrResultado = $this->BusquedaNITEmpresaEjecutivo($arrParametros);
            
                break;
            
            case 'InsertarNuevoMantenimiento':

                    $arrResultado = $this->InsertarNuevoMantenimiento($arrParametros, $usuarioAPP, $nombre_servicio);
            
                break;
            
            case 'InsertarMantCapacitacionPDF':

                    $arrResultado = $this->InsertarMantCapacitacionPDF($arrParametros, $usuarioAPP, $nombre_servicio);
                
                break;
            
            case 'CompletarMantenimiento':

                    $arrResultado = $this->CompletarMantenimiento($arrParametros, $usuarioAPP, $nombre_servicio);
                
                break;
            
            case 'ReporteVisitasEntreFechas':

                    $arrResultado = $this->ReporteVisitasEntreFechas($arrParametros);
            
                break;
            
            case 'ListadoBandejaEntregaServicios':

                    $arrResultado = $this->ListadoBandejaEntregaServicios($arrParametros);
            
                break;
            
            
            case 'ConfirmarEntregaServicio':

                    $arrResultado = $this->ConfirmarEntregaServicio($arrParametros, $usuarioAPP, $nombre_servicio);
                
                break;
            
            /*************** AFILIACIÓN POR TERCEROS - INICIO ****************************/
            
            case 'External__Register_Prospect':

                    $arrResultado = $this->External__Register_Prospect($arrParametros, $usuarioAPP, $nombre_servicio, $arrResultadoLogin[0]['usuario_id'], $arrResultadoLogin[0]['perfil_app_id']);                    
                
                break;
            
            case 'External__Validate_Faces':

                    $arrResultado = $this->External__Validate_Faces($arrParametros, $usuarioAPP, $nombre_servicio, $arrResultadoLogin[0]['usuario_id'], $arrResultadoLogin[0]['perfil_app_id']);                    
                
                break;
            
            case 'External__Register_Steap':

                    $arrResultado = $this->External__Register_Steap($arrParametros, $usuarioAPP, $nombre_servicio, $arrResultadoLogin[0]['usuario_id'], $arrResultadoLogin[0]['perfil_app_id']);                    
                
                break;
            
            case 'External__Segip_Vigencia':

                    $arrResultado = $this->External__Segip_Vigencia($arrParametros, $usuarioAPP, $nombre_servicio, $arrResultadoLogin[0]['usuario_id'], $arrResultadoLogin[0]['perfil_app_id']);                    
                
                break;
            
            // -- Req. Validaciòn COBIS, SEGIP, FACIAL y OCR
            
            case 'External__Consulta_Cliente':

                    $arrResultado = $this->External__Consulta_Cliente($arrParametros, $usuarioAPP, $nombre_servicio, $arrResultadoLogin[0]['usuario_id'], $arrResultadoLogin[0]['perfil_app_id']);                    
                
                break;
            
            case 'External__Token_Update':

                    $arrResultado = $this->External__Token_Update($arrParametros, $usuarioAPP, $nombre_servicio, $arrResultadoLogin[0]['usuario_id'], $arrResultadoLogin[0]['perfil_app_id']);                    
                
                break;
            
            case 'External__Token_Validate':

                    $arrResultado = $this->External__Token_Validate($arrParametros, $usuarioAPP, $nombre_servicio, $arrResultadoLogin[0]['usuario_id'], $arrResultadoLogin[0]['perfil_app_id']);                    
                
                break;
            
            case 'External__SMS_PIN':

                    $arrResultado = $this->External__SMS_PIN($arrParametros, $usuarioAPP, $nombre_servicio, $arrResultadoLogin[0]['usuario_id'], $arrResultadoLogin[0]['perfil_app_id']);                    
                
                break;
            
            case 'External__Widget_Selfie':

                    $arrResultado = $this->External__Widget_Selfie($arrParametros, $usuarioAPP, $nombre_servicio, $arrResultadoLogin[0]['usuario_id'], $arrResultadoLogin[0]['perfil_app_id']);                    
                
                break;
            
            case 'External__Widget_OCR':

                    $arrResultado = $this->External__Widget_OCR($arrParametros, $usuarioAPP, $nombre_servicio, $arrResultadoLogin[0]['usuario_id'], $arrResultadoLogin[0]['perfil_app_id']);                    
                
                break;
            
            case 'External__Verificar_Similaridad':

                    $arrResultado = $this->External__Verificar_Similaridad($arrParametros, $usuarioAPP, $nombre_servicio, $arrResultadoLogin[0]['usuario_id'], $arrResultadoLogin[0]['perfil_app_id']);                    

                break;
            
            /*************** AFILIACIÓN POR TERCEROS - FIN ****************************/
            
            case 'External__Register_Credito':

                    $arrResultado = $this->External__Register_Credito($arrParametros, $usuarioAPP, $nombre_servicio, $arrResultadoLogin[0]['usuario_id'], $arrResultadoLogin[0]['perfil_app_id']);                    

                break;
            
            /*************** SOLICITUD DE CREDITOS - INICIO ****************************/
            
            
             /*************** SOLICITUD DE CREDITOS - FIN ****************************/
            
            default:
                // Si no es ningún servicio disponible, se muestra un mensaje de error
                $arrError =  array(		
                                "error" => "true",
                                "errorMessage" => "Servicio Desconocido.",
                                "errorCode" => 101,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                        );

                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrError);
                
                $this->response($arrError, 403);

                break;
        }

        // 9. CON EL RESULTADO OBTENIDO DEL SERVICIO SOLICITADO, SE ENVÍA LA RESPUESTA
        $arrResultado = $this->mfunciones_generales->RespuestaREST($arrResultado);

        $this->response($arrResultado, 200);
    }    

    // Función Auxiliar
	
	function isJSON($string){
	   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
	
    function array_keys_exist($array, $keys) {
            $count = 0;
            if ( ! is_array( $keys ) ) {
                    $keys = func_get_args();
                    array_shift( $keys );
            }
            foreach ( $keys as $key ) {
                    if ( array_key_exists( $key, $array ) ) {
                            $count ++;
                    }
            }

            return count( $keys ) === $count;
    }
    
    function array_keys_exist_value($array, $keys) {
            $count = '';
            if ( ! is_array( $keys ) ) {
                    $keys = func_get_args();
                    array_shift( $keys );
            }
            foreach ( $keys as $key ) {
                    if ( !array_key_exists( $key, $array ) ) {
                            $count .= $key . ', ';
                    }
            }

            return $count;
    }
    
    /*************** APP FASE 2 - INICIO ****************************/
    
    function MenuUsuarioRol($arrDatos, $perfil_app_id, $perfil_app_nombre){

            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "ejecutivo_id"
                    );
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                    $arrResultado = array();
                    return $arrResultado;
            }

            // Si Todo bien... se captura los datos y se procesa la información

            $ejecutivo_id = $arrDatos['ejecutivo_id'];
       
            // Se obtiene el perfil App del usuario
            
            /*******************************************/
            
            switch ((int)$perfil_app_id) {

                // Normalizador Cobrador
                case 3:

                    $lst_campana = array();

                    $lst_campana[] = array(
                        "opcion_titulo" => '① ' . $perfil_app_nombre,
                        "opcion_tipo" => 'module_prospect',
                        "opcion_id" => array(
                            "codigo_ejecutivo" => $ejecutivo_id,
                            "consolidado" => "13"
                        )
                    );
                    
                    $lst_onboarding[] = array(
                        "opcion_titulo" => '① Nuevo Registro',
                        "opcion_tipo" => 'info',
                        "opcion_id" => array(
                            "codigo_usuario" => -1,
                            "tipo_info" => 'nuevo_norm'
                        ),
                    );

                    $lst_resultado[] = array(
                            "opcion_titulo" => 'Nuevo Onboarding',
                            "opcion_icono" => 'calendario',
                            "opcion_tipo" => '',
                            "opcion_id" => 'Titulo 0',
                            "sub_menu" => $lst_onboarding
                        );

                    $lst_resultado[] = array(
                        "opcion_titulo" => 'Mis Clientes',
                        "opcion_icono" => 'calendario',
                        "opcion_tipo" => '',
                        "opcion_id" => 'Titulo 1',
                        "sub_menu" => $lst_campana
                    );
                    
                    break;

                default:
                    
                    $lst_campana = array();

                    $arrCampana = $this->mfunciones_logica->ObtenerCampana(-1, ' WHERE c.camp_id IN (1,2,3,4,5,6)');
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrCampana);

                    if (isset($arrCampana[0]))
                    {
                        foreach ($arrCampana as $key => $value) {

                            switch ($value["camp_id"]) {
                                case 1:

                                    $arreglo = '①';

                                    break;

                                case 2:

                                    $arreglo = '②';

                                    break;

                                case 3:

                                    $arreglo = '③';

                                    break;

                                case 4:

                                    $arreglo = '④';

                                    break;

                                case 5:

                                    $arreglo = '⑤';

                                    break;

                                case 6:

                                    $arreglo = '⑥';

                                    break;

                                default:

                                    $arreglo = '';

                                    break;
                            }

                            $lst_campana[] = array(
                                "opcion_titulo" => $arreglo . ' ' . $this->mfunciones_microcreditos->TextoTitulo($value["camp_nombre"]),
                                "opcion_tipo" => 'module_prospect',
                                "opcion_id" => array(
                                    "codigo_ejecutivo" => $ejecutivo_id,
                                    "consolidado" => $value["camp_id"]
                                )
                            );
                        }
                    }

                    /*
                    $lst_resultado[] = array(
                        "opcion_titulo" => 'Nuevo Cliente (Estudio)',
                        "opcion_icono" => 'tarea',
                        "opcion_tipo" => 'info',
                        "opcion_id" => array(
                            "codigo_usuario" => -1,
                            "tipo_info" => 'nuevo_lead'
                            ),
                        "sub_menu" => '',
                    );
                    */

                    $lst_onboarding[] = array(
                        "opcion_titulo" => '① Nueva Apertura de Cuenta',
                        "opcion_tipo" => 'info',
                        "opcion_id" => array(
                            "codigo_usuario" => -1,
                            "tipo_info" => 'nuevo_onboarding'
                        ),
                    );

                    $lst_onboarding[] = array(
                        "opcion_titulo" => '② Nueva Solicitud de Crédito',
                        "opcion_tipo" => 'info',
                        "opcion_id" => array(
                            "codigo_usuario" => -1,
                            "tipo_info" => 'nuevo_solcredito'
                        ),
                    );

                    $lst_resultado[] = array(
                            "opcion_titulo" => 'Nuevo Onboarding',
                            "opcion_icono" => 'calendario',
                            "opcion_tipo" => '',
                            "opcion_id" => 'Titulo 0',
                            "sub_menu" => $lst_onboarding
                        );

                    $lst_resultado[] = array(
                        "opcion_titulo" => 'Mis Clientes',
                        "opcion_icono" => 'calendario',
                        "opcion_tipo" => '',
                        "opcion_id" => 'Titulo 1',
                        "sub_menu" => $lst_campana
                    );

                    $lst_resultado[] = array(
                        "opcion_titulo" => 'Mis Mantenimientos',
                        "opcion_icono" => 'calendario',
                        "opcion_tipo" => 'module_maintenance',
                        "opcion_id" => 'Titulo 2',
                        "sub_menu" => '',
                    );

                    $lst_resultado[] = array(
                        "opcion_titulo" => 'Mis Estadísticas',
                        "opcion_icono" => 'reporte',
                        "opcion_tipo" => 'module_statistics',
                        "opcion_id" => 'Titulo 1',
                        "sub_menu" => '',
                    );
                    
                    break;
            }

            return $lst_resultado;
    }
    
    function DatallePantallaPrincipal($arrDatos, $usuario, $nombre_servicio, $codigo_ejecutivo, $nombre_completo, $perfil_app_id, $perfil_app_nombre){

            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "codigo_usuario",
                    "tipo_info"
                    );
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                    $arrResultado = array();
                    return $arrResultado;
            }

            // Si Todo bien... se captura los datos y se procesa la información

            $codigo_usuario = $arrDatos['codigo_usuario'];
            $tipo_info = $arrDatos['tipo_info'];
        
            $body = 'Sin contenido';
            
            if(MODO_MANTENIMIENTO == 1)
            {
                $lst_resultado[0] = array(
                    "codigo_usuario" => $codigo_usuario,
                    "body" => '<h1>EL SISTEMA SE ENCUENTRA EN "MODO MANTENIMIENTO" <br /> Generalmente este modo se aplica bajo cronograma establecido. <br /><br /> Si requiere más información, por favor puede comunicarse con su representante de INITIUM.</h1><br /><img class=\'img_derecha\' src=\'' . $this->config->base_url() . 'html_public/imagenes/portada/dev2.gif\' />'
                );
                
                return $lst_resultado;
            }
            
            if($tipo_info == 'home')
            {
                $html_body = '';
                
                switch ((int)$perfil_app_id) {
                    
                    // Normalizador/Cobrador
                    case 3:
                        
                        $html_body .= $this->mfunciones_cobranzas->getReporteHomeApp($codigo_ejecutivo, $perfil_app_nombre);

                        $html_body = str_replace('[indicaciones]', 'seleccione el menú, "Nuevo Onboarding" y "Nuevo Registro"', $html_body);
                        
                        break;

                    default:
                        
                        // 1. Se obtiene el listado de todos los Clientes Asignados al Agente
                        $arrLeads = $this->mfunciones_logica->ObtenerProspectosEjecutivoAll($codigo_ejecutivo);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrLeads);

                        $contador_trabajo = 0;
                        $contador_cerrados = 0;
                        $contador_no_evaluados = 0;
                        $contador_aprobados = 0;
                        $contador_rechazados = 0;

                        $contador_total_leads = 0;

                        if (isset($arrLeads[0])) 
                        {
                            foreach ($arrLeads as $key => $value) 
                            {
                                if($value["prospecto_etapa"] != 6 && $value["prospecto_etapa"] != 23 && $value["prospecto_etapa"] != 24) { $contador_trabajo++; } else { $contador_cerrados++; }

                                switch ($value["prospecto_evaluacion"])
                                {
                                   case 0: $contador_no_evaluados++; break;
                                   case 1: $contador_aprobados++; break;
                                   case 2: $contador_rechazados++; break;

                                    default:
                                        break;
                                }
                            }

                            $contador_total_leads = count($arrLeads);
                        }

                        // Solicitudes de Crédito
                        $arrSolicitudes = $this->mfunciones_microcreditos->ObtenerSolicitudesEjecutivoAll($codigo_ejecutivo);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrSolicitudes);

                        if (isset($arrSolicitudes[0])) 
                        {
                            foreach ($arrSolicitudes as $key => $value) 
                            {
                                if($value["sol_consolidado"] == 0) { $contador_trabajo++; } else { $contador_cerrados++; }

                                switch ($value["sol_estado"])
                                {
                                   case 1: $contador_no_evaluados++; break;
                                   case 2: $contador_aprobados++; break;
                                   case 3: $contador_rechazados++; break;

                                    default:
                                        break;
                                }
                            }

                            $contador_total_leads += count($arrSolicitudes);
                        }
                        
                        // SE ARMA EL IFRAME DEL FUNNEL

                        $estructura_id = -1;
                        //$codigo_ejecutivo = $codigo_ejecutivo;
                        $tipo_registro = 'reporte_funnel';

                        $url_armado = $this->mfunciones_generales->setEnlaceRegistro($estructura_id, $codigo_ejecutivo, $tipo_registro);
                        $reporte_funnel = '<iframe frameborder="0" scrolling="yes" style="overflow-y: auto; background-color: #fafafa; width: 100%; margin-top: 0px; height: 388px; padding-top: 0px;" src="' . (site_url('Registros/Principal/?' . $url_armado)) . '"> </iframe>';

                        $html_body .= '

                            <div style="text-align: center;">
                                <div style="width: 330px; display: inline-block; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; padding: 10px 0px 0px 0px;">
                                    ' . $reporte_funnel . '
                                </div>
                            </div>';

                        if($contador_total_leads > 0)
                        {
                            // 2. Se obtiene el cálculo del Rubro
                            $CalculoRubros = $this->mfunciones_logica->CalculoRubrosEjecutivo($codigo_ejecutivo);
                            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($CalculoRubros);

                            // Solicitud de Crédito - Sólo si se tiene registrado solicitudes de crédito
                            $CalculoSolRubros = $this->mfunciones_microcreditos->CalculoRubrosSolEjecutivo_Aux($codigo_ejecutivo);
                            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($CalculoSolRubros);

                            if (isset($CalculoRubros[0]))
                            {
                                if (isset($CalculoSolRubros[0]))
                                {
                                    $CalculoRubros = array_merge($CalculoRubros, $CalculoSolRubros);
                                }
                            }
                            else
                            {
                                if (isset($CalculoSolRubros[0]))
                                {
                                    $CalculoRubros = $CalculoSolRubros;
                                }
                            }

                            $html_body .= '

                                <br />

                                <fieldset>
                                    <legend> .: Clientes Ponderados por Registro :. </legend>';

                            if (isset($CalculoRubros[0])) 
                            {
                                $total_rubro = 0;

                                foreach ($CalculoRubros as $key => $value) 
                                {
                                    $total_rubro += $value["contador"];
                                }

                                foreach ($CalculoRubros as $key => $value) 
                                {
                                    $contador_porcentaje = ($total_rubro!=0 ? ($value["contador"]/$total_rubro)*100 : 0);

                                    $html_body .= '

                                            <div class="barra_contenedor">
                                                <div class="barra_titulo"> ' . $this->mfunciones_microcreditos->TextoTitulo($value["camp_nombre"]) . ' - ' . number_format($value["contador"], 0, '.', ',') . ' Clientes</div>
                                                <div class="barra_color" style="background-color: ' . $value["camp_color"] . '; width: ' . $contador_porcentaje . '%;"> </div>
                                                <div class="barra_texto"> ' . number_format($contador_porcentaje, 2, '.', ',') . '% </div>
                                            </div>';
                                }
                            }

                            $html_body .= '

                                </fieldset>';

                            // 3. Se obtiene el cálculo de la Etapa
                            $CalculoEtapas = $this->mfunciones_logica->CalculoEtapasEjecutivo($codigo_ejecutivo);
                            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($CalculoEtapas);

                            $CalculoSolEtapas = $this->mfunciones_microcreditos->CalculoSolEtapasEjecutivo($codigo_ejecutivo);
                            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($CalculoSolEtapas);

                            if (isset($CalculoEtapas[0]))
                            {
                                if (isset($CalculoSolEtapas[0]))
                                {
                                    $CalculoEtapas = array_merge($CalculoEtapas, $CalculoSolEtapas);
                                }
                            }
                            else
                            {
                                if (isset($CalculoSolEtapas[0]))
                                {
                                    $CalculoEtapas = $CalculoSolEtapas;
                                }
                            }

                            $html_body .= '

                                <br />

                                <fieldset>
                                    <legend> .: Clientes Ponderados por Etapa :. </legend>';

                            if (isset($CalculoEtapas[0])) 
                            {
                                $total_etapa = 0;

                                foreach ($CalculoEtapas as $key => $value) 
                                {
                                    $total_etapa += $value["contador"];
                                }

                                foreach ($CalculoEtapas as $key => $value) 
                                {
                                    $contador_porcentaje = ($total_etapa!=0 ? ($value["contador"]/$total_etapa)*100 : 0);

                                    $html_body .= '

                                            <div class="barra_contenedor">
                                                <div class="barra_titulo"> ' . $value["etapa_nombre"] . ' - ' . number_format($value["contador"], 0, '.', ',') . ' Clientes</div>
                                                <div class="barra_color" style="background-color: ' . $value["etapa_color"] . '; width: ' . $contador_porcentaje . '%;"> </div>
                                                <div class="barra_texto"> ' . number_format($contador_porcentaje, 2, '.', ',') . '% </div>
                                            </div>';
                                }
                            }


                            $html_body .= '

                                </fieldset>';

                            $html_body .= '

                                <br /><br /><br /><br />

                                ';

                        }
                        
                        break;
                }
                    
                $body = '<h1>Bienvenid@ ' . $nombre_completo . '</h1><fieldset><legend> Un Producto :: FIE MicroApp :: </legend> <img class=\'img_derecha\' src=\'' . $this->config->base_url() . 'html_public/imagenes/portada/logo-fie.svg\' /><h2 style=\'text-align: center;\'> <i> "Micro y pequeña empresa... priorizando la inclusión financiera..." </i> </h2></fieldset><br />' . str_replace("\r\n","",$html_body);
            }
            
            if($tipo_info == 'nuevo_lead')
            {
                // SE ARMA EL IFRAME
            
                $estructura_id = -1;
                //$codigo_ejecutivo = $codigo_ejecutivo;
                $tipo_registro = 'nuevo_lead';

                $url_armado = $this->mfunciones_generales->setEnlaceRegistro($estructura_id, $codigo_ejecutivo, $tipo_registro);
                $body = '<iframe frameborder="0" scrolling="yes" style="overflow-y: auto; background-color: #fafafa; width: 100%; margin-top: 0px; height: 1500px; padding-top: 0px;" src="' . (site_url('Registros/Principal/?' . $url_armado)) . '"> </iframe>';

            }
            
            if($tipo_info == 'nuevo_onboarding')
            {
                // SE ARMA EL IFRAME
            
                $estructura_id = -1;
                //$codigo_ejecutivo = $codigo_ejecutivo;
                $tipo_registro = 'nuevo_onboarding';

                $url_armado = $this->mfunciones_generales->setEnlaceRegistro($estructura_id, $codigo_ejecutivo, $tipo_registro);
                $body = '<iframe frameborder="0" scrolling="yes" style="overflow-y: auto; background-color: #fafafa; width: 100%; margin-top: 0px; height: 1300px; padding-top: 0px;" src="' . (site_url('Registros/Principal/?' . $url_armado)) . '"> </iframe>';

            }
            
            if($tipo_info == 'nuevo_solcredito')
            {
                // SE ARMA EL IFRAME
            
                $estructura_id = -1;
                //$codigo_ejecutivo = $codigo_ejecutivo;
                $tipo_registro = 'nuevo_solcredito';

                $url_armado = $this->mfunciones_generales->setEnlaceRegistro($estructura_id, $codigo_ejecutivo, $tipo_registro);
                $body = '<iframe frameborder="0" scrolling="yes" style="overflow-y: auto; background-color: #fafafa; width: 100%; margin-top: 0px; height: 1300px; padding-top: 0px;" src="' . (site_url('Registros/Principal/?' . $url_armado)) . '"> </iframe>';

            }
            
            if($tipo_info == 'nuevo_norm')
            {
                // SE ARMA EL IFRAME
            
                $estructura_id = -1;
                //$codigo_ejecutivo = $codigo_ejecutivo;
                $tipo_registro = 'nuevo_norm';

                $url_armado = $this->mfunciones_generales->setEnlaceRegistro($estructura_id, $codigo_ejecutivo, $tipo_registro);
                $body = '<iframe frameborder="0" scrolling="yes" style="overflow-y: auto; background-color: #fafafa; width: 100%; margin-top: 0px; height: 1300px; padding-top: 0px;" src="' . (site_url('Registros/Principal/?' . $url_armado)) . '"> </iframe>';

            }
            
            $lst_resultado[0] = array(
                "codigo_usuario" => $codigo_usuario,
                "body" => $body
            );
            

            return $lst_resultado;
    }
    
    function DatallePantallaWebRegistro($arrDatos, $usuario, $nombre_servicio, $codigo_ejecutivo, $nombre_completo){

            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "codigo_usuario",
                    "codigo_registro"
                    );
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                    $arrResultado = array();
                    return $arrResultado;
            }

            // Si Todo bien... se captura los datos y se procesa la información

            $codigo_usuario = $arrDatos['codigo_usuario'];
            $codigo_registro = $arrDatos['codigo_registro'];
        
            // *** Obtener Código y Tipo Persona ***
                $aux_get_codigo = $this->getCodigo($codigo_registro);

                if($aux_get_codigo->codigo == 0)
                {
                    $arrError = array(
                        "error" => true,
                        "errorMessage" => 'Ocurrió un error al captura el código identificador (' . $codigo_registro . ')',
                        "errorCode" => 96,
                        "result" => array(
                            "mensaje" => 'Ocurrió un error al captura el código identificador (' . $codigo_registro . ')'
                        )
                    );

                    $this->response($arrError, 200);
                    exit();
                }

                $codigo_registro = $aux_get_codigo->codigo;
                $codigo_tipo_persona = $aux_get_codigo->tipo_persona;
            // *** Obtener Código y Tipo Persona ***
            
            if(MODO_MANTENIMIENTO == 1)
            {
                $lst_resultado[0] = array(
                    "codigo_usuario" => $codigo_usuario,
                    "body" => '<h1>EL SISTEMA SE ENCUENTRA EN "MODO MANTENIMIENTO" <br /> Generalmente este modo se aplica bajo cronograma establecido. <br /><br /> Si requiere más información, por favor puede comunicarse con su representante de INITIUM.</h1><br /><img class=\'img_derecha\' src=\'' . $this->config->base_url() . 'html_public/imagenes/portada/dev2.gif\' />'
                );
                
                return $lst_resultado;
            }
            
            // SE ARMA EL IFRAME
            
            $estructura_id = $codigo_registro;
            
            switch ((int)$codigo_tipo_persona) {
                
                // Solicitud de Crédito
                case 6:
                    $tipo_registro = 'solicitud_credito';

                    break;

                // Normalizador/Cobrador
                
                case 13:
                    $tipo_registro = 'normalizador';

                    break;
                
                default:
                    
                    $tipo_registro = 'unidad_familiar';
                    
                    break;
            }
            
            $url_armado = $this->mfunciones_generales->setEnlaceRegistro($estructura_id, $codigo_ejecutivo, $tipo_registro);
            
            $body = '<iframe frameborder="0" scrolling="yes" style="overflow-y: auto; background-color: #ffffff; height: 90vh; width: 100%; margin-top: 0px; height: 100vh; padding-top: 0px;" src="' . (site_url('Registros/Principal/?' . $url_armado)) . '"> </iframe>';
            
            $lst_resultado[0] = array(
                "codigo_usuario" => $codigo_usuario,
                "body" => $body
            );
            

            return $lst_resultado;
    }
    
    function ObtenerEstiloContenido($arrDatos){

            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "ejecutivo_id"
                    );
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                    $arrResultado = array();
                    return $arrResultado;
            }

            // Si Todo bien... se captura los datos y se procesa la información

            $ejecutivo_id = $arrDatos['ejecutivo_id'];
            
            $style = '
                
                body {
                font-family: \'Roboto\', sans-serif;
                background-color: #fafafa;
                }
                table 
                {
                        width: 90%;
                        margin-left: 5%;
                        margin-right: 5%;
                        margin-bottom: 10px;
                        border-collapse: collapse;
                        padding: 0px;
                        box-sizing: border-box;
                        box-shadow: 1px 2px 5px 0px #ccc;
                }
                /* top-left border-radius */
                table tr:first-child th:first-child,
                table.Info tr:first-child td:first-child {
                        border-top-left-radius: 10px;
                }
                /* top-right border-radius */
                table tr:first-child th:last-child,
                table.Info tr:first-child td:last-child {
                        border-top-right-radius: 10px;
                }
                /* bottom-left border-radius */
                table tr:last-child td:first-child {
                        border-bottom-left-radius: 10px;
                }
                /* bottom-right border-radius */
                table tr:last-child td:last-child {
                        border-bottom-right-radius: 10px;
                }
                th {
                        padding-top: 8px;
                        padding-bottom: 8px;
                        line-height: 1.3em;
                        vertical-align: middle;
                        background-color: #006699;
                        color: #ffffff;
                }
                td {
                        border: 1px solid #CCC;
                        border-top: 1px solid #f8f8f8;
                        line-height: 1.3em;
                        padding: 8px 12px;
                        vertical-align: middle;
                        text-align: center;
                }
                h1 {
                        font-size: 24px;
                        color: #006699;
                        letter-spacing: 0.5px;
                        font-weight: bold;
                        text-align: left;
                        text-shadow: #004162 0px 1px 1px;
                }
                h2 {
                        font-size: 18px;
                        color: #006699;
                        letter-spacing: 0.5px;
                        font-weight: bold;
                        text-align: left;
                        text-shadow: #004162 0px 1px 1px;
                }
                h4 {
                        font-size: 14px;
                        color: #666666;
                        font-weight: normal;
                        text-align: left;
                        text-shadow: #222222 0px 1px 1px;
                        margin: 0px;
                        text-align: center;
                }
                h5 {
                        font-size: 28px;
                        color: #006699;
                        letter-spacing: 0.5px;
                        font-weight: bold;
                        text-align: center;
                        text-shadow: #004162 0px 1px 1px;
                        margin: 0px;
                }
                h6 {
                        font-size: 18px;
                        color: #006699;
                        letter-spacing: 0.5px;
                        font-weight: bold;
                        text-align: center;
                        text-shadow: #004162 0px 1px 1px;
                        margin: 0px;
                }
                p {
                        font-weight: 500;
                        line-height: 20px;
                        margin: 0 0 10px;
                }
                fieldset {
                        border: 2px solid #006699;
                        border-radius: 10px;
                        padding: 15px 15px 5px 15px;
                        width: 90%;
                }
                fieldset legend {
                        background: #006699;
                        color: #fff;
                        padding: 8px 10px;
                        border-radius: 5px;
                        border: 2px solid #ffffff;
                        text-shadow: #004162 0px 1px 1px;
                        font-weight: bold;
                        font-size: 16px;
                        width: 90%;
                }
                img {
                        width: 100%;
                        height: auto;
                }
                .img_derecha {
                        width: 50%;
                        float: right;
                }

                .barra_contenedor
                {
                        width: 100%;
                        margin: 0px 0px 15px 0px;
                        border-left: 1px dashed #ccc;
                }

                .barra_color
                {
                        height: 30px;
                        border-radius: 20px 5px 20px 5px;
                        box-shadow: 0px 3px 3px 0 rgba(0, 0, 0, 0.2), -1px 3px 3px 0 rgba(0, 0, 0, 0.19);
                }

                .barra_texto
                {
                        font-size: 14px;
                        text-align: left;
                        margin-top: -23px;
                        padding-left: 15px;
                        color: #eeeeee;
                        font-weight: bold;
                        text-shadow: #333333 0px 1px 1px;
                }

                .barra_titulo
                {
                        font-size: 14px;
                        padding-left: 15px;
                        font-weight: bold;
                        color: #aaaaaa;
                        text-shadow: #222222 0px 1px 1px;
                        margin: 0px;
                        padding: 0px 0px 0px 15px;
                }

                .texto_contenido
                {
                        width: 30px;
                }

                .texto_bloque
                {
                        width: 50%;
                        float: left;
                        margin-bottom: 10px;
                }
                
                .texto_bloque3
                {
                        width: 33%;
                        float: left;
                        margin-bottom: 10px;
                }
                
                ';
            
            $lst_resultado[0] = array(
                "ejecutivo_id" => $ejecutivo_id,
                "style" => str_replace("\r\n", "", $style)
            );
            

            return $lst_resultado;
    }
    
    /*************** APP FASE 2 - FIN ****************************/
    
    /*************** FORMULARIOS DINÁMICOS - INICIO ****************************/
    
    function ListadoFormPublicados($arrDatos){

        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
            "codigo_registro",
            "tipo_bandeja"
        );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            $arrResultado = array();
            return $arrResultado;
        }
        
        // Si Todo bien... se captura los datos y se procesa la información
        
        $codigo_registro = $arrDatos['codigo_registro'];
        $tipo_bandeja = $arrDatos['tipo_bandeja'];
        
        // *** Obtener Código y Tipo Persona ***
            $aux_get_codigo = $this->getCodigo($codigo_registro);

            if($aux_get_codigo->codigo == 0)
            {
                $arrError = array(
                    "error" => true,
                    "errorMessage" => 'Ocurrió un error al captura el código identificador (' . $codigo_registro . ')',
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => 'Ocurrió un error al captura el código identificador (' . $codigo_registro . ')'
                    )
                );

                $this->response($arrError, 200);
                exit();
            }

            $codigo_registro = $aux_get_codigo->codigo;
            $codigo_tipo_persona = $aux_get_codigo->tipo_persona;
        // *** Obtener Código y Tipo Persona ***
        
        $formulariosPublicados = $this->form_dinamico->formularios_publicados($tipo_bandeja);
        if (is_array($formulariosPublicados)) {
            $lst_resultado[0] = array(
                "codigo_registro" => $codigo_registro,
                "tipo_bandeja_codigo" => $tipo_bandeja,
                "tipo_bandeja_detalle" => $tipo_bandeja == 1 ? 'PROSPECTOS' : 'MANTENIMIENTOS' ,
                "lista_formularios" => $formulariosPublicados
            );
            return $lst_resultado;
        } else {
             $arrError =  array(        
                "error" => "true",
                "errorMessage" => "No existe el registro solicitado.",
                "errorCode" => 101,
                "result" => array(
                    "mensaje" => $this->lang->line('errorNoEncontrado')
                )
            );
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrError);
            $this->response($arrError, 403);
        }
    }
    
    function ListaElementosForm($arrDatos){
            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "codigo_registro",
                    "tipo_bandeja",
                    "form_id"
                    );
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                    $arrResultado = array();
                    return $arrResultado;
            }

            // Si Todo bien... se captura los datos y se procesa la información

            $codigo_registro = $arrDatos['codigo_registro'];
            $tipo_bandeja = $arrDatos['tipo_bandeja'];
            $form_id = $arrDatos['form_id'];
            
            // *** Obtener Código y Tipo Persona ***
                $aux_get_codigo = $this->getCodigo($codigo_registro);

                if($aux_get_codigo->codigo == 0)
                {
                    $arrError = array(
                        "error" => true,
                        "errorMessage" => 'Ocurrió un error al captura el código identificador (' . $codigo_registro . ')',
                        "errorCode" => 96,
                        "result" => array(
                            "mensaje" => 'Ocurrió un error al captura el código identificador (' . $codigo_registro . ')'
                        )
                    );

                    $this->response($arrError, 200);
                    exit();
                }

                $codigo_registro = $aux_get_codigo->codigo;
                $codigo_tipo_persona = $aux_get_codigo->tipo_persona;
            // *** Obtener Código y Tipo Persona ***
            
            // var_dump($codigo_registro, $tipo_bandeja, $form_id);
            
            // if($tipo_bandeja != 1)
            // {
            //     $arrResultado = array();
            //     return $arrResultado;
            // }
            
            // Se define que formularios requieren el listado de campos del prospecto
            if($form_id == 1 || $form_id == 2)
            {
                $arrResultado1 = $this->mfunciones_logica->ListadoDetalleProspecto($codigo_registro);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
                if (isset($arrResultado1[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado1 as $key => $value) 
                    {
                        $dir_geo = $value["prospecto_direccion_geo"];

                        if($value["prospecto_direccion_geo"] == 'null' || $value["prospecto_direccion_geo"] == 'null, null')
                        {
                            $dir_geo = GEO_FIE; 
                        }

                        $item_valor = array(
                            "tipo_persona_id" => $value["tipo_persona_id"],
                            "prospecto_idc" => $value["prospecto_idc"],
                            "prospecto_nombre_cliente" => $value["prospecto_nombre_cliente"],
                            "prospecto_empresa" => $value["prospecto_empresa"],
                            "prospecto_ingreso" => $value["prospecto_ingreso"],
                            "prospecto_direccion" => $value["prospecto_direccion"],
                            "prospecto_direccion_geo" => $dir_geo,
                            "prospecto_telefono" => $value["prospecto_telefono"],
                            "prospecto_celular" => $value["prospecto_celular"],
                            "prospecto_email" => $value["prospecto_email"],
                            "prospecto_tipo_lead" => $value["prospecto_tipo_lead"],
                            "prospecto_matricula" => $value["prospecto_matricula"],
                            "prospecto_fecha_contacto1" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["prospecto_fecha_contacto1"]),
                            "prospecto_monto_aprobacion" => $value["prospecto_monto_aprobacion"],
                            "prospecto_monto_desembolso" => $value["prospecto_monto_desembolso"],
                            "prospecto_fecha_desembolso" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["prospecto_fecha_desembolso"]),
                            "camp_nombre" => $value["camp_nombre"],
                            "etapa_nombre" => $value["etapa_nombre"],
                            "prospecto_etapa" => $value["prospecto_etapa"],
                            "prospecto_comentario" => $value["prospecto_comentario"]
                        );

                        $lst_resultado[$i] = $item_valor;
                        $i++;
                    }
                }
                else
                {
                    $arrResultado = array();
                    return $arrResultado;
                }
            }
            
            $contador_id = 0;
            // var_dump($codigo_registro, $tipo_bandeja, $form_id);
            $formulario = $this->form_dinamico->getFormulario($tipo_bandeja, $form_id);
            if ($formulario) {
                $componentes = $this->form_dinamico->listadoComponentesInstancia($form_id, $tipo_bandeja, $codigo_registro);
                $lst_resultado = array(
                    'codigo_registro' => $codigo_registro,
                    'form_id' => $form_id,
                    'form_detalle' => $formulario->nombre,
                    'tipo_bandeja' => $tipo_bandeja,
                    'lista_elementos' => $componentes
                );
            } else {
                 // Si no es ningún servicio disponible, se muestra un mensaje de error
                 $arrError =  array(        
                    "error" => "true",
                    "errorMessage" => "No existe el registro solicitado.",
                    "errorCode" => 404,
                    "result" => array(
                        "mensaje" => $this->lang->line('errorNoEncontrado')
                    )
                );

                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrError);
                
                $this->response($arrError, 403);
            }
           
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($lst_resultado);
            
        return $lst_resultado;
    }
    
    function GuardarRegistroForm($arrDatos, $usuario, $nombre_servicio, $codigo_ejecutivo){
            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "codigo_registro",
                    "tipo_bandeja",
                    "form_id",
                    "arrElementos"
                    );
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                $arrError =  array(     
                    "error" => true,
                    "errorMessage" => $this->lang->line('CamposRequeridos'),
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => $this->lang->line('IncompletoApp')
                    )
                );

                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrError);
                
                $this->response($arrError, 403);
            }

            // Si Todo bien... se captura los datos y se procesa la información

            $codigo_registro = $arrDatos['codigo_registro'];
            $tipo_bandeja = $arrDatos['tipo_bandeja'];
            $form_id = $arrDatos['form_id'];
            
            // *** Obtener Código y Tipo Persona ***
                $aux_get_codigo = $this->getCodigo($codigo_registro);

                if($aux_get_codigo->codigo == 0)
                {
                    $arrError = array(
                        "error" => true,
                        "errorMessage" => 'Ocurrió un error al captura el código identificador (' . $codigo_registro . ')',
                        "errorCode" => 96,
                        "result" => array(
                            "mensaje" => 'Ocurrió un error al captura el código identificador (' . $codigo_registro . ')'
                        )
                    );

                    $this->response($arrError, 200);
                    exit();
                }

                $codigo_registro = $aux_get_codigo->codigo;
                $codigo_tipo_persona = $aux_get_codigo->tipo_persona;
            // *** Obtener Código y Tipo Persona ***
            
            $arrElementos = $arrDatos['arrElementos'];
            
            $accion_fecha = date('Y-m-d H:i:s');
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrElementos);
            $guardado = $this->form_dinamico->guadarValoresInstancia($codigo_registro, $tipo_bandeja, $form_id, $arrElementos, true);
            if (isset($guardado->error)) {
                $arrError =  array(     
                    "error" => true,
                    "errorMessage" => $guardado->mensaje,
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => $guardado->mensaje
                    )
                );
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrError);
                $this->response($arrError, 403);
            }
        
            $lst_resultado[0] = array(
                "codigo_registro" => $codigo_registro,
                "mensaje" => "El registro se guardó correctamente. Puede gestionar el Cliente ingresando a la Campaña."
            );
            
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), '0,0', $usuario, $accion_fecha);
            
            return $lst_resultado;
    }
    
    /*************** FORMULARIOS DINÁMICOS - FIN ****************************/
    
    /*************** LECTOR QR - INICIO ****************************/
    
    function AutenticacionUsuarioQR($arrDatos, $usuario, $nombre_servicio){
        
        // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "usuario", "password", "ubicacion_geo"
                    );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            $arrResultado = array();
            return $arrResultado;
        }

        // Si Todo bien... se captura los datos y se procesa la información

        $usu_login = $arrDatos['usuario'];
        $usu_password = $arrDatos['password'];
        
        //Auditoría
        $geolocalizacion = $arrDatos['ubicacion_geo'];
        
        $arrResultado = $this->mfunciones_logica->VerificaCredencialesAPP($usu_login, $usu_password);

        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        // Se obtiene los datos
        
        if (isset($arrResultado[0])) 
        {
            $i = 0;
            
            $dias_cambio_password =$this->mfunciones_generales->getDiasPassword($arrResultado[0]['usuario_fecha_ultimo_password'], 'max');

            // Se obtiene los datos del Theme
            $arrTheme = $this->mfunciones_logica->ObtenerThemeQR($arrResultado[0]["agencia_codigo"]);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrTheme);
            
            if (isset($arrTheme[0])) 
            {
                $j = 0;
            
                foreach ($arrTheme as $key => $value) {                
                    $item_valor = array(
                        "background-color" => $value["background_color"],
                        "color-primary" => $value["color_primary"],
                        "color-secundary" => $value["color_secundary"],
                        "button-background-color" => $value["button_background_color"],
                        "button-text-color" => $value["button_text_color"],
                        "url_web_view" => $value["url_web_view"],
                        "titulo" => $value["titulo"]
                    );
                    $lst_Theme[$j] = $item_valor;

                    $j++;
                }
            }
            else
            {
                $lst_Theme[0] = array(
                            "background-color" => '#ffffff',
                            "color-primary" => '#003366',
                            "color-secundary" => '#006699',
                            "button-background-color" => '#006699',
                            "button-text-color" => '#ffffff',
                            "url_web_view" => 'https://www.google.com/',
                            "titulo" => 'Sin Theme Registrado'
                        );
            }
            
            foreach ($arrResultado as $key => $value) {                
                $item_valor = array(
                    "usuario_codigo" => $value["usuario_id"],
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "usuario_user" => $value["usuario_user"],
                    "usuario_nombre" => $value["usuario_nombres"],
                    "usuario_app" => $value["usuario_app"],
                    "usuario_apm" => $value["usuario_apm"],              
                    "usuario_email" => $value["usuario_email"],
                    "usuario_telefono" => $value["usuario_telefono"],
                    "usuario_direccion" => $value["usuario_direccion"],
                    "usuario_rol_codigo" => $value["usuario_rol"],
                    "usuario_rol_detalle" => $this->mfunciones_generales->getRolUsuario($value["usuario_rol"]),
                    "usuario_fecha_ultimo_acceso" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["usuario_fecha_ultimo_acceso"]),
                    "usuario_fecha_ultimo_password" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["usuario_fecha_ultimo_password"]),
                    "usuario_dias_validez_pass" => $dias_cambio_password,
                    "usuario_activo" => $this->mfunciones_generales->GetValorCatalogo($value["usuario_activo"], 'activo'),
                    "agencia_codigo" => $this->encrypt->encode($value["agencia_codigo"]),
                    "agencia_detalle" => $value["agencia_detalle"],
                    "theme" => $lst_Theme
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
            
            // Se registra la fecha del Login        
            $this->mfunciones_generales->UsuarioActualizarFechaLogin($arrResultado[0]['usuario_id']);
        } 
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }
        
        // Se guarda el Log para la auditoría        
        $accion_fecha = date('Y-m-d H:i:s');
        $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), $geolocalizacion, $usuario, $accion_fecha);
                
        return $lst_resultado;
    }
    
    function ListadoCategoriaQR($arrDatos){

            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "agencia_codigo"
                    );
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                    $arrResultado = array();
                    return $arrResultado;
            }

            // Si Todo bien... se captura los datos y se procesa la información

            $agencia_codigo = $this->encrypt->decode($arrDatos['agencia_codigo']);
        
        // Se obtiene los datos de la Categoría
        $arrCategoria = $this->mfunciones_logica->ListaCategoriaQR($agencia_codigo, -1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrCategoria);
        
        if (isset($arrCategoria[0])) 
        {
            $i = 0;
            
            foreach ($arrCategoria as $key => $value) 
            {
                $item_valor = array(
                    "categoria_codigo" => $this->encrypt->encode($value["categoria_qr_id"]),
                    "categoria_descripcion" => $value["categoria_qr_nombre"]
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        }
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }

            return $lst_resultado;
    }
    
    function VerificaQR($arrDatos){

            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "categoria_codigo", "qr_codigo"
                    );
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                    $arrResultado = array();
                    return $arrResultado;
            }

            // Si Todo bien... se captura los datos y se procesa la información

            $categoria_codigo = $this->encrypt->decode($arrDatos['categoria_codigo']);
            $qr_codigo = $arrDatos['qr_codigo'];
            
            // Se obtiene los datos de la Categoría
            $arrCategoria = $this->mfunciones_logica->ListaCategoriaQR(-1, $categoria_codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrCategoria);
            
            if (isset($arrCategoria[0])) 
            {
                $body_ok = $arrCategoria[0]['categoria_qr_plantilla_ok'];
                $body_error = $arrCategoria[0]['categoria_qr_plantilla_error'];
                $body_notfound = $arrCategoria[0]['categoria_qr_plantilla_notfound'];
            }
            else
            {
                $arrResultado = array();
                return $arrResultado;
            }
            
            
            // Se obtiene los datos del registro QR
            $arrRegistro = $this->mfunciones_logica->ListaRegistroQR($qr_codigo, $categoria_codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRegistro);
            
            
            
            switch ($arrCategoria[0]['categoria_qr_tipo'])
            {
                // CASO: REGISTRO ASISTENCIA
                case 1:
                    
                    // Se encuentra el registro
                    if (isset($arrRegistro[0])) 
                    {
                        if($arrRegistro[0]['registro_qr_checkin'] == 0)
                        {
                            // CASO 1: Se encuentra el registro y está vigente

                            $this->mfunciones_logica->ActualizarRegistroQR($qr_codigo);

                            $lst_resultado[0] = array(
                                "tk_respuesta" => 'success',
                                "tk_body" => $body_ok . '<br /><br />' . $arrRegistro[0]['registro_qr_detalle']
                            );

                            return $lst_resultado;
                        }
                        else
                        {
                            // CASO 2: Se encuentra el registro pero no esta vigente
                            $lst_resultado[0] = array(
                                "tk_respuesta" => 'error',
                                "tk_body" => $body_error . '<br /><br />' . $arrRegistro[0]['registro_qr_detalle']
                            );

                            return $lst_resultado;
                        }
                    }
                    else
                    {
                        // CASO 3: No se encuentra el registro
                        $lst_resultado[0] = array(
                            "tk_respuesta" => 'error',
                            "tk_body" => $body_notfound
                        );

                        return $lst_resultado;
                    }
                
                break;
                
                // CASO: INVENTARIO
                case 2:
                    
                    // Se encuentra el registro
                    if (isset($arrRegistro[0])) 
                    {
                        // CASO 1: Se encuentra el registro y está vigente

                        $lst_resultado[0] = array(
                            "tk_respuesta" => 'success',
                            "tk_body" => $body_ok . '<br /><br />' . $arrRegistro[0]['registro_qr_detalle']
                        );

                        return $lst_resultado;
                    }
                    else
                    {
                        // CASO 3: No se encuentra el registro
                        $lst_resultado[0] = array(
                            "tk_respuesta" => 'error',
                            "tk_body" => $body_notfound
                        );

                        return $lst_resultado;
                    }
                
                break;
                
                // CASO: REGISTRO LOTES
                case 3:
                    
                    // Se encuentra el registro
                    if (isset($arrRegistro[0])) 
                    {
                        // CASO 1: Se encuentra el registro y está vigente

                        $lst_resultado[0] = array(
                            "tk_respuesta" => 'error',
                            "tk_body" => $body_error . '<br /><br />' . $arrRegistro[0]['registro_qr_detalle']
                        );

                        return $lst_resultado;
                    }
                    else
                    {
                        // CASO 3: No se encuentra el registro y se guarda en la DB
                        
                        $this->mfunciones_logica->InsertarRegistroQR($categoria_codigo, $qr_codigo, '<strong>Las carateristicas técnicas son las mismas que el lote</strong>', 0);
                        
                        $lst_resultado[0] = array(
                            "tk_respuesta" => 'success',
                            "tk_body" => $body_ok
                        );

                        return $lst_resultado;
                    }
                
                break;
            }
            
            return $lst_resultado;
    }
    
    /*************** LECTOR QR - FIN ****************************/
    
    /*************** ROCKETBOT - INICIO ****************************/
    
    function RB_Autenticacion($arrDatos, $usuario, $nombre_servicio, $usuario_nombre){
        
        // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "codigo_proyecto"
                    );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            $arrResultado = array();
            return $arrResultado;
        }

        // Si Todo bien... se captura los datos y se procesa la información

        
        $lst_resultado = array(
            
            "usuario_codigo" => "123",
            "ejecutivo_id" => "codigo ejecutivo"
            
        );
        
        // Se guarda el Log para la auditoría        
        $accion_fecha = date('Y-m-d H:i:s');
        $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), '', $usuario, $accion_fecha);
                
        return $lst_resultado;
    }
    
    /*************** ROCKETBOT - FIN ****************************/
    
    function AutenticacionUsuario($arrDatos, $usuario, $nombre_servicio, $ad_active = 0){
        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array("usuario", "password", "ubicacion_geo");
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            $arrResultado = array();
            return $arrResultado;
        }

        // Si Todo bien... se captura los datos y se procesa la información

        $usu_login = $arrDatos['usuario'];
        $usu_password = $arrDatos['password'];
        
        //Auditoría
        $geolocalizacion = $arrDatos['ubicacion_geo'];

        $arrResultado = $this -> mfunciones_activeDirectory -> VerificaCredencialesAPP2($usu_login);

        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;
            
            if($ad_active == 1){
                $dias_cambio_password = 90;
                $arrResultado[0]['usuario_nombre'] = "";
                $arrResultado[0]['usuario_app'] = "";
                $arrResultado[0]['usuario_apm'] = "";
            }else{
                $dias_cambio_password =$this->mfunciones_generales->getDiasPassword($arrResultado[0]['usuario_fecha_ultimo_password'], 'max');
            }

            foreach ($arrResultado as $key => $value) {                
                $item_valor = array(
                    "usuario_codigo" => $value["usuario_id"],
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "usuario_user" => $value["usuario_user"],
                    "usuario_nombre" => $value["usuario_nombres"],
                    "usuario_app" => $value["usuario_app"],
                    "usuario_apm" => $value["usuario_apm"],              
                    "usuario_email" => $value["usuario_email"],
                    "usuario_telefono" => $value["usuario_telefono"],
                    "usuario_direccion" => $value["usuario_direccion"],
                    "usuario_rol_codigo" => $value["usuario_rol"],
                    "usuario_rol_detalle" => $this->mfunciones_generales->getRolUsuario($value["usuario_rol"]),
                    "usuario_fecha_ultimo_acceso" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["usuario_fecha_ultimo_acceso"]),
                    "usuario_fecha_ultimo_password" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["usuario_fecha_ultimo_password"]),
                    "usuario_dias_validez_pass" => $dias_cambio_password,
                    "usuario_activo" => $this->mfunciones_generales->GetValorCatalogo($value["usuario_activo"], 'activo')
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
            
            // Se registra la fecha del Login        
            $this->mfunciones_generales->UsuarioActualizarFechaLogin($arrResultado[0]['usuario_id']);
        } 
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }
        
        // Se guarda el Log para la auditoría        
        $accion_fecha = date('Y-m-d H:i:s');
        $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), $geolocalizacion, $usuario, $accion_fecha);
                
        return $lst_resultado;
    }
    
    function RenovarPassUsuario($arrDatos, $usuario, $nombre_servicio,$ad_active){
        if($ad_active == 1){
            $arrError =  array(		
                "error" => true,
                "errorMessage" => $this->lang->line('ad_reestablecer_pass'),
                "errorCode" => 90,
                "result" => array(
                    "mensaje" => $this->lang->line('IncompletoApp')
                )
            );

            $this->response($arrError, 200);
            exit();
        }else{
            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array("ubicacion_geo", "usuario_codigo", "password_actual","password_nuevo");
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                $arrResultado = array();
                return $arrResultado;
            }

            // Si Todo bien... se captura los datos y se procesa la información

            //Auditoría
            $geolocalizacion = $arrDatos['ubicacion_geo'];

            $usuario_codigo = $arrDatos['usuario_codigo'];
            $password_anterior = $arrDatos['password_actual'];
            $password_anterior = sha1(sha1($password_anterior));
            $password_nuevo = $arrDatos['password_nuevo'];
            $password_nuevo = sha1(sha1($password_nuevo));

            if ($password_anterior == $password_nuevo)
            {
                $arrError =  array(		
                                "error" => true,
                                "errorMessage" => $this->lang->line('PasswordRepetido'),
                                "errorCode" => 92,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                        );

                $this->response($arrError, 200);
                exit();
            }

            // PRIMERO SE VERIFICA SI LA CONTRASEÑA ANTERIOR ES CORRECTA

                $password_anterior = sha1(sha1($password_anterior));

                $arrResultado = $this->mfunciones_logica->VerificaPass($usuario_codigo, $password_anterior);

                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                
                if (!isset($arrResultado[0]))
                {
                    $arrError =  array(		
                                "error" => true,
                                "errorMessage" => $this->lang->line('PasswordAnterior'),
                                "errorCode" => 93,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                        );

                    $this->response($arrError, 200);
                    exit();
                }

            // Se pregunta si el tiempo mínimo de cambio de contraseña permite la renovación
                
                $arrResultado2 = $this->mfunciones_logica->ObtenerDatosUsuario($usuario_codigo);        
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);
                
                // Si la contraseña fue restablecida, puede ser cambiada    0 = No fue restablecida     1 = Si fue restablecida
                if($arrResultado2[0]['usuario_password_reset'] == 0)
                {
                    if($this->mfunciones_generales->getDiasPassword($arrResultado2[0]['usuario_fecha_ultimo_password'], 'min') == 0)
                    {                    
                        // Obtener la duración mínima de la contraseña
                        $arrResultado3 = $this->mfunciones_logica->ObtenerDatosConf_Credenciales();        
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
                        
                        $arrError =  array(		
                                    "error" => true,
                                    "errorMessage" => $this->lang->line('PasswordNoRenueva') . $arrResultado3[0]['conf_duracion_min'] . ' día(s).',
                                    "errorCode" => 94,
                                    "result" => array(
                                        "mensaje" => $this->lang->line('IncompletoApp')
                                    )
                            );

                        $this->response($arrError, 200);
                        exit();
                    }
                }
                
            // Se verifica que la contraseña cumpla con los requisitos mínimos
            if($this->mfunciones_generales->VerificaFortalezaPassword($password_nuevo) != 'ok')
            {
                $arrError =  array(		
                            "error" => true,
                            "errorMessage" => str_replace("<br />"," ",$this->mfunciones_generales->VerificaFortalezaPassword($password_nuevo)),
                            "errorCode" => 95,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
                    );

                $this->response($arrError, 200);
                exit();
            }
                
            $usuario_pass = sha1(sha1($password_nuevo));

            $accion_fecha = date('Y-m-d H:i:s');

            $this->mfunciones_logica->RenovarPass($usuario_pass, $accion_fecha, $usuario, $usuario_codigo);

            // Se guarda el Log para la auditoría        

            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), $geolocalizacion, $usuario, $accion_fecha);

            $lst_resultado[0] = array(
                "mensaje" => "La contraseña se renovó correctamente. Por Seguridad puede salir y volver a ingresar a la App."
            );
        }
                    
            
            return $lst_resultado;
        
    }
    
    function ListadoCatalogo($arrDatos){

        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array("catalogo", "parent_codigo", "parent_tipo");
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrResultado = array();
                return $arrResultado;
        }

        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_catalogo = $arrDatos['catalogo'];
        $parent_codigo = $arrDatos['parent_codigo'];
        $parent_tipo = $arrDatos['parent_tipo'];

        if($codigo_catalogo == '-1')
        {
            $arrResultado = array();
            return $arrResultado;
        }

        if($codigo_catalogo == 'ae_actividad_fie_from_sector')
        {
            // Listado de tablas del catálogo	
            $arrResultado = $this->mfunciones_logica->actividad_fie_from_sector($parent_codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "catalogo_tipo_codigo" => $value["catalogo_tipo_codigo"],
                        "catalogo_codigo" => $value["catalogo_codigo"],
                        "catalogo_descripcion" => ucwords(mb_strtolower($value["catalogo_descripcion"])),
                        "ae_actividad_economica" => $value["ae_actividad_economica"],
                        "ae_subsector_economico" => $value["ae_subsector_economico"],
                        "ae_sector_economico" => $value["ae_sector_economico"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            } 
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
            
            return $lst_resultado;
        }
        
        if($codigo_catalogo == 'agencias_solcred')
        {
            switch ((int)$parent_codigo) {
                case 1468:
                    $aux_criterio = "PA";
                    break;
                    
                case 545:
                    $aux_criterio = "CB";
                    break;
                    
                case 115:
                    $aux_criterio = "115";
                    break;
                    
                case 723:
                    $aux_criterio = "OR";
                    break;
                    
                case 102:
                    $aux_criterio = "LP";
                    break;
                    
                case 884:
                    $aux_criterio = "PO";
                    break;
                    
                case 1225:
                    $aux_criterio = "SC";
                    break;
                    
                case 1:
                    $aux_criterio = "CH";
                    break;
                    
                case 1134:
                    $aux_criterio = "TJ";
                    break;
                    
                case 1419:
                    $aux_criterio = "BE";
                    break;

                default:
                    $aux_criterio = "LP";
                    break;
            }
            
            if((int)$parent_codigo == 115)
            {
                $parent_tipo = 'dir_localidad_ciudad';
            }
            else
            {
                $parent_tipo = 'dir_departamento';
            }
            
            // Listado de REGIONES (Agencias)
            $arrResultado = $this->mfunciones_microcreditos->ObtenerDatosRegional_solcred(1, $aux_criterio, $parent_tipo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "catalogo_tipo_codigo" => $codigo_catalogo,
                        "catalogo_codigo" => $value["estructura_regional_id"],
                        "catalogo_descripcion" => ucwords(mb_strtolower($value["estructura_regional_nombre"])),
                        "catalogo_departamento" => $value["estructura_regional_departamento"],
                        "catalogo_provincia" => $value["estructura_regional_provincia"],
                        "catalogo_ciudad" => $value["estructura_regional_ciudad"],
                        "catalogo_direccion" => $value["estructura_regional_direccion"],
                        "catalogo_geo" => $value["estructura_regional_geo"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            } 
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
            
            return $lst_resultado;
        }
        
        if($codigo_catalogo == 'dir_barrio_zona_uv_solcred')
        {
            switch ((int)$parent_codigo) {
                case 1468:
                    $aux_criterio = "PA";
                    break;
                    
                case 545:
                    $aux_criterio = "CB";
                    break;
                    
                case 115:
                    $aux_criterio = "115";
                    break;
                    
                case 723:
                    $aux_criterio = "OR";
                    break;
                    
                case 102:
                    $aux_criterio = "LP";
                    break;
                    
                case 884:
                    $aux_criterio = "PO";
                    break;
                    
                case 1225:
                    $aux_criterio = "SC";
                    break;
                    
                case 1:
                    $aux_criterio = "CH";
                    break;
                    
                case 1134:
                    $aux_criterio = "TJ";
                    break;
                    
                case 1419:
                    $aux_criterio = "BE";
                    break;

                default:
                    $aux_criterio = "LP";
                    break;
            }
            
            if((int)$parent_codigo == 115)
            {
                $aux_criterio = sprintf("AND ciu.catalogo_codigo='%s'", $aux_criterio);
                $aux_flag = 0;
            }
            else
            {
                $aux_criterio = sprintf("AND dep.catalogo_codigo='%s' AND ciu.catalogo_codigo!='115'", $aux_criterio);
                $aux_flag = 1;
            }
            
            // Listado de zonas por ciudad o departamento
            $this->load->model('mfunciones_microcreditos');
            
            $arrResultado = $this->mfunciones_microcreditos->ObtenerZonas_solcred($aux_criterio, $aux_flag);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "catalogo_tipo_codigo" => $value["catalogo_tipo_codigo"],
                        "catalogo_codigo" => $value["catalogo_codigo"],
                        "catalogo_descripcion" => ucwords(mb_strtolower($value["catalogo_descripcion"]))
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            } 
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
            
            return $lst_resultado;
        }
        
        if($codigo_catalogo == 'AGENCIAS')
        {
            // Listado de REGIONES (Agencias)
            $arrResultado = $this->mfunciones_logica->ObtenerDatosRegionalExcluyente(1, $parent_codigo, $parent_tipo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "catalogo_tipo_codigo" => $codigo_catalogo,
                        "catalogo_codigo" => $value["estructura_regional_id"],
                        "catalogo_descripcion" => ucwords(mb_strtolower($value["estructura_regional_nombre"])),
                        "catalogo_departamento" => $value["estructura_regional_departamento"],
                        "catalogo_provincia" => $value["estructura_regional_provincia"],
                        "catalogo_ciudad" => $value["estructura_regional_ciudad"],
                        "catalogo_direccion" => $value["estructura_regional_direccion"],
                        "catalogo_geo" => $value["estructura_regional_geo"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            } 
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
        }
        elseif($codigo_catalogo == 'ae_actividad_fie_filtrado' && $parent_tipo == 'ae_sector_economico')
        {
            switch ($parent_codigo) {
                case 'I':

                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '011211', 'catalogo_descripcion' => ucwords(mb_strtolower('CULTIVO DE PAPA')), 'ae_actividad_economica' => '01121', 'ae_subsector_economico' => 'A', 'ae_sector_economico' => 'I');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '011192', 'catalogo_descripcion' => ucwords(mb_strtolower('OTROS CULTIVOS')), 'ae_actividad_economica' => '01119', 'ae_subsector_economico' => 'A', 'ae_sector_economico' => 'I');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '012110', 'catalogo_descripcion' => ucwords(mb_strtolower('CRÍA DE GANADO VACUNO')), 'ae_actividad_economica' => '01211', 'ae_subsector_economico' => 'A', 'ae_sector_economico' => 'I');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '012290', 'catalogo_descripcion' => ucwords(mb_strtolower('CRÍA DE ANIMALES Y OBTENCIÓN DE PRODUCTOS DE ORIGEN ANIMAL')), 'ae_actividad_economica' => '01229', 'ae_subsector_economico' => 'A', 'ae_sector_economico' => 'I');

                    break;

                case 'III':
                    
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '523990', 'catalogo_descripcion' => ucwords(mb_strtolower('VENTA AL POR MENOR DE OTROS PRODUCTOS')), 'ae_actividad_economica' => '52399', 'ae_subsector_economico' => 'S', 'ae_sector_economico' => 'III');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '519000', 'catalogo_descripcion' => ucwords(mb_strtolower('VENTA AL POR MAYOR DE OTROS PRODUCTOS')), 'ae_actividad_economica' => '51900', 'ae_subsector_economico' => 'H', 'ae_sector_economico' => 'III');
            
                    break;
                
                case 'V':
                
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '912008', 'catalogo_descripcion' => ucwords(mb_strtolower('INSTITUCIONES PRIVADAS DE OTROS SERVICIOS')), 'ae_actividad_economica' => '912008', 'ae_subsector_economico' => 'W', 'ae_sector_economico' => 'V');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '751109', 'catalogo_descripcion' => ucwords(mb_strtolower('INSTITUCIONES DE LA ADMINISTRACIÓN PÚBLICA')), 'ae_actividad_economica' => '751109', 'ae_subsector_economico' => 'W', 'ae_sector_economico' => 'V');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '801018', 'catalogo_descripcion' => ucwords(mb_strtolower('INSTITUCIONES EDUCATIVAS PÚBLICAS')), 'ae_actividad_economica' => '801018', 'ae_subsector_economico' => 'W', 'ae_sector_economico' => 'V');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '651919', 'catalogo_descripcion' => ucwords(mb_strtolower('ENTIDADES PRIVADAS DE SERVICIOS FINANCIEROS')), 'ae_actividad_economica' => '651919', 'ae_subsector_economico' => 'W', 'ae_sector_economico' => 'V');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '990019', 'catalogo_descripcion' => ucwords(mb_strtolower('JUBILADOS O RENTISTAS')), 'ae_actividad_economica' => '99001', 'ae_subsector_economico' => 'W', 'ae_sector_economico' => 'V');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '912009', 'catalogo_descripcion' => ucwords(mb_strtolower('INSTITUCIONES PRIVADAS INDUSTRIALES')), 'ae_actividad_economica' => '912009', 'ae_subsector_economico' => 'W', 'ae_sector_economico' => 'V');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '752329', 'catalogo_descripcion' => ucwords(mb_strtolower('INSTITUCIONES PÚBLICAS RELACIONADAS CON EL ORDEN Y LA SEGURIDAD')), 'ae_actividad_economica' => '752329', 'ae_subsector_economico' => 'W', 'ae_sector_economico' => 'V');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '950009', 'catalogo_descripcion' => ucwords(mb_strtolower('SERVICIO DOMÉSTICO')), 'ae_actividad_economica' => '95000', 'ae_subsector_economico' => 'W', 'ae_sector_economico' => 'V');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '801019', 'catalogo_descripcion' => ucwords(mb_strtolower('INSTITUCIONES EDUCATIVAS PRIVADAS')), 'ae_actividad_economica' => '801019', 'ae_subsector_economico' => 'W', 'ae_sector_economico' => 'V');

                    break;
                    
                case 'VI':
                    
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '990020', 'catalogo_descripcion' => ucwords(mb_strtolower('ESTUDIANTES')), 'ae_actividad_economica' => '99002', 'ae_subsector_economico' => 'Z', 'ae_sector_economico' => 'VI');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '990030', 'catalogo_descripcion' => ucwords(mb_strtolower('AMAS DE CASA')), 'ae_actividad_economica' => '99003', 'ae_subsector_economico' => 'Z', 'ae_sector_economico' => 'VI');
            
                    break;
                
                case 'II':
                    
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '452090', 'catalogo_descripcion' => ucwords(mb_strtolower('CONSTRUCCIÓN DE OBRAS DE INGENIERÍA CIVIL')), 'ae_actividad_economica' => '45209', 'ae_subsector_economico' => 'G', 'ae_sector_economico' => 'II');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '369900', 'catalogo_descripcion' => ucwords(mb_strtolower('OTRAS INDUSTRIAS MANUFACTURERAS')), 'ae_actividad_economica' => '36990', 'ae_subsector_economico' => 'E', 'ae_sector_economico' => 'II');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '154990', 'catalogo_descripcion' => ucwords(mb_strtolower('ELABORACIÓN DE PRODUCTOS ALIMENTICIOS')), 'ae_actividad_economica' => '15499', 'ae_subsector_economico' => 'E', 'ae_sector_economico' => 'II');

                    break;
                
                case 'IV':
                    
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '742191', 'catalogo_descripcion' => ucwords(mb_strtolower('ALBAÑILES')), 'ae_actividad_economica' => '74219', 'ae_subsector_economico' => 'L', 'ae_sector_economico' => 'IV');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '742192', 'catalogo_descripcion' => ucwords(mb_strtolower('OTROS SERVICIOS DE ACTIVIDADES TÉCNICAS')), 'ae_actividad_economica' => '74219', 'ae_subsector_economico' => 'L', 'ae_sector_economico' => 'IV');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '602114', 'catalogo_descripcion' => ucwords(mb_strtolower('SERVICIO DE TRANSPORTE URBANO DE MINIBUSES')), 'ae_actividad_economica' => '60211', 'ae_subsector_economico' => 'J', 'ae_sector_economico' => 'IV');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '552010', 'catalogo_descripcion' => ucwords(mb_strtolower('RESTAURANTES')), 'ae_actividad_economica' => '55201', 'ae_subsector_economico' => 'I', 'ae_sector_economico' => 'IV');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '749900', 'catalogo_descripcion' => ucwords(mb_strtolower('OTROS SERVICIOS EMPRESARIALES')), 'ae_actividad_economica' => '74990', 'ae_subsector_economico' => 'L', 'ae_sector_economico' => 'IV');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '801010', 'catalogo_descripcion' => ucwords(mb_strtolower('SERVICIOS PRESTADOS POR INSTITUCIONES EDUCATIVAS PRIVADAS Y PÚBL')), 'ae_actividad_economica' => '80101', 'ae_subsector_economico' => 'N', 'ae_sector_economico' => 'IV');
                    $lst_resultado[] = array('catalogo_tipo_codigo' => $codigo_catalogo, 'catalogo_codigo' => '659900', 'catalogo_descripcion' => ucwords(mb_strtolower('OTRO TIPO DE SERVICIOS DE INTERMEDIACIÓN FINANCIERA')), 'ae_actividad_economica' => '65990', 'ae_subsector_economico' => 'K', 'ae_sector_economico' => 'IV');

                    break;
                
                default:
                    
                    $lst_resultado = array();
                    
                    break;
            }
            
        }
        else
        {
            switch ($codigo_catalogo) {
                case 'dir_provincia_filtrado':

                    $codigo_catalogo = 'dir_provincia';

                    $filtro = '108,31,11,47,63,85,1,79,100';
                    
                    break;

                case 'dir_localidad_ciudad_filtrado':

                    $codigo_catalogo = 'dir_localidad_ciudad';

                    $filtro = '1468,545,115,723,102,884,1225,1,1134,1419';
                    
                    break;
                
                case 'ae_subsector_economico_filtrado':

                    $codigo_catalogo = 'ae_subsector_economico';

                    $filtro = '"A","E","G","H","S","I","J","K","L","N","Z","W"';
                    
                    break;

                /*
                case 'ae_actividad_fie_filtrado':

                    $codigo_catalogo = 'ae_actividad_fie';

                    $filtro = '011211,011192,012110,012290,523990,519000,912008,751109,801018,651919,990019,912009,752329,950009,801019,990020,990030,452090,369900,154990,742191,742192,602114,552010,749900,801010,659900';
                    
                    break;
                */
                
                case 'dir_barrio_zona_uv_filtrado':

                    $codigo_catalogo = 'dir_barrio_zona_uv';

                    $filtro = '298,299,300,301,302,303,304,305,306,308,309,310,311,312,313,314,315,316,317,318,319,320,321,322,323,324,325,326,327,328,329,330,331,332,333,334,335,336,337,338,339,340,341,342,343,344,345,346,347,348,349,350,351,353,354,355,356,357,358,359,360,361,362,363,364,365,366,367,368,369,370,371,372,374,375,376,377,378,379,380,381,382,383,384,385,386,387,388,390,391,392,393,394,395,396,397,399,400,401,402,403,404,405,406,408,409,410,411,412,413,414,415,417,418,419,420,421,422,423,424,425,426,427,428,430,431,432,433,434,435,437,440,441,442,443,444,445,446,447,448,449,450,520,521,522,523,524,525,526,527,528,529,530,531,532,533,534,535,536,537,538,539,540,541,542,544,545,546,547,548,549,550,551,552,553,554,555,557,558,559,560,561,562,563,564,565,566,568,569,570,571,572,575,576,577,578,579,580,581,582,583,585,586,587,588,589,590,591,592,593,594,595,597,598,599,603,604,605,606,607,608,610,612,614,615,616,617,618,619,620,621,622,623,624,625,626,627,628,629,630,631,632,633,634,635,636,637,638,639,640,642,643,644,645,646,647,648,649,650,651,652,653,654,655,656,657,658,660,661,662,663,664,665,666,668,669,670,671,672,673,675,676,677,678,679,680,681,682,683,684,685,686,687,688,689,692,693,694,695,696,697,698,699,700,701,703,704,705,706,707,708,709,710,711,712,713,714,716,717,718,719,720,721,722,723,724,725,726,727,728,729,730,731,732,733,734,735,736,737,738,739,740,741,742,743,744,745,746,747,748,749,750,751,752,753,754,755,756,757,758,759,760,761,762,763,764,765,767,768,769,770,771,772,773,774,776,779,784,785,952,953,954,955,956,957,958,959,960,961,962,963,964,965,966,967,968,969,970,971,972,973,974,975,976,977,978,979,980,981,982,983,984,986,987,988,989,990,991,992,993,994,995,996,997,998,999,1000,1001,1002,1004,1006,1007,1010,1011,1012,1013,1015,1016,1017,1018,1019,1020,1021,1022,1023,1024,1026,1027,1028,1030,1032,1034,1035,1036,1037,1038,1039,1041,1042,1043,1044,1045,1046,1047,1048,1049,1050,1052,1053,1054,1055,1056,1057,1058,1059,1060,1061,1062,1063,1064,1066,1067,1068,1069,1070,1071,1072,1075,1078,1080,1081,1082,1083,1084,1085,1086,1087,1088,1089,1090,1091,1092,1093,1094,1095,1096,1097,1098,1101,1102,1105,1106,1107,1108,1111,1112,1113,1114,1115,1116,1117,1118,1120,1121,1122,1124,1125,1126,1127,1128,1129,1131,1132,1134,1135,1137,1138,1139,1140,1141,1144,1145,1146,1148,1149,1434,1435,1436,1437,1438,1439,1440,1441,1442,1443,1444,1445,1446,1447,1448,1449,1450,1451,1452,1453,1454,1455,1456,1457,1552,1553,1554,1555,1556,1557,1558,1559,1560,1561,1562,1563,1564,1565,1566,1567,1568,1569,1570,1571,1572,1573,1574,1576,1577,1579,1580,1581,1582,1583,1584,1585,1586,1587,1588,1589,1590,1591,1592,1593,1594,1595,1596,1597,1598,1599,1600,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018,2019,2020,2021,2022,2023,2024,2025,2026,2027,2028,2029,2031,2033,2036,2037,2039,2040,2041,2042,2043,2044,2045,2046,2047,2048,2049,2050,2051,2052,2053,2054,2055,2056,2057,2058,2059,2060,2061,2062,2063,2064,2065,2066,2067,2068,2069,2070,2071,2072,2073,2074,2075,2077,2078,2080,2081,2082,2083,2084,2085,2087,2089,2090,2091,2092,2093,2094,2095,2096,2097,2098,2099,2100,2101,2102,2103,2104,2105,2106,2107,2108,2109,2110,2112,2113,2114,2115,2116,2117,2118,2119,2120,2121,2122,2123,2124,2125,2126,2127,2128,2129,2130,2131,2132,2133,2134,2135,2136,2137,2138,2139,2140,2141,2142,2143,2144,2145,2146,2147,2148,2149,2150,2151,2152,2153,2154,2155,2156,2157,2158,2159,2160,2161,2162,2163,2164,2165,2166,2167,2168,2169,2170,2171,2172,2173,2174,2175,2176,2177,2178,2179,2180,2181,2182,2183,2184,2185,2186,2187,2188,2189,2190,2191,2192,2193,2194,2195,2196,2197,2198,2199,2200,2201,2202,2203,2204,2205,2206,2207,2208,2209,2210,2211,2212,2213,2214,2215,2216,2217,2218,2219,2220,2221,2222,2223,2224,2225,2226,2227,2228,2229,2230,2231,2232,2233,2234,2235,2236,2237,2238,2239,2240,2241,2242,2243,2244,2245,2246,2247,2248,2249,2250,2251,2252,2253,2254,2255,2256,2257,2258,2259,2260,2261,2262,2263,2264,2265,2266,2267,2268,2269,2270,2271,2272,2273,2274,2275,2276,2277,2278,2279,2280,2281,2282,2283,2284,2285,2286,2287,2288,2289,2290,2291,2292,2293,2294,2295,2296,2297,2298,2299,2300,2301,2302,2303,2304,2305,2306,2307,2308,2309,2310,2311,2312,2313,2314,2315,2317,2318,2319,2320,2321,2322,2323,2324,2325,2326,2327,2877,2878,2879,2880,2881,2882,2883,2884,2885,2886,2887,2888,2889,2890,2891,2892,2893,2894,2895,2896,2897,2898,2899,2900,2901,2902,2903,2904,2906,2907,2908,2910,2911,2913,2915,2916,2917,2919,2920,2922,2923,2924,2925,2926,2927,2928,2929,2930,2931,2932,2933,2934,2937,2938,2939,2940,2941,2942,2943,2944,2945,2946,2947,2948,2949,2950,2951,2952,2953,2954,2955,2956,2957,2958,2959,2960,2961,2962,2964,2965,2966,2967,2968,2969,2970,2971,2972,2973,2974,2976,2977,2978,2979,2980,4088,4357,4436,4438,4439,4440,4441,4442,4444,4445,4446,4447,4448,4449,4450,4453,4702,4703,4704,4705,4707,4708,4793,4910,4911,4912,4913,4914,4915,4916,4917,4918,4919,4920,4921,4922,4923,4949,4953,4954,4955,4956,4957,4958,4960,4963,4964,4965,4966,4967,4968,4969,4970,4971,4972,4973,4974,4975,4976,4977,4978,4979,4980,4981,4982,4983,4984,4985,4986,4987,4988,4989,4990,4991,4992,4993,4994,4995,4996,4997,4998,4999,5000,5001,5002,5003,5004,5005,5006,5007,5008,5009,5010,5011,5012,5013,5014,5015,5016,5017,5018,5019,5020,5021,5024,5026,5027,5082,5085,5086,5087,5090,5091,5092,5093,5094,5095,5096,5097,5098,5099,5100,5101,5103,5104,5105,5106,5107,5108,5109,5110,5111,5112,5113,5115,5116,5117,5118,5119,5120,5121,5122,5123,5124,5127,5128,5190,5192,5195,5196,5197,5198,5202,5203,5208,5209,5214,5265,5362,5363,5371,5415,5421,5530';
                    
                    break;
                
                case 'dd_profesion_filtrado':

                    $codigo_catalogo = 'dd_profesion';

                    $filtro = '"CAJE","ABOG","ADME","AGRI","ALBA","AMAC","ARQI","AUDI","BIOQ","CARP","COCI","COME","CONS","CONT","ECON","ELEC","EMPL","ESTU","GANA","INGS","JUBI","MEAU","MEDI","ODON","OTRO","PANA","POLI","PROF","SAST","TECN","TRAN"';
                    
                    break;
                
                default:
                    
                    $filtro = -1;
                    
                    break;
            }
            
            // Listado de tablas del catálogo	
            $arrResultado = $this->mfunciones_logica->ObtenerCatalogo($codigo_catalogo, $parent_codigo, $parent_tipo, $filtro);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "catalogo_tipo_codigo" => $value["catalogo_tipo_codigo"],
                        "catalogo_codigo" => $value["catalogo_codigo"],
                        "catalogo_descripcion" => ucwords(mb_strtolower($value["catalogo_descripcion"]))
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            } 
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
        }

        return $lst_resultado;
    }
    
    function ListadoCatalogoTipoPersona($arrDatos){

            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "valor"
                    );
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                    $arrResultado = array();
                    return $arrResultado;
            }

            // Si Todo bien... se captura los datos y se procesa la información

            $valor = $arrDatos['valor'];
	
		// Listado de tablas del catálogo	
        $arrResultado = $this->mfunciones_logica->ObtenerDetalleCatalogoTipo($valor);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
		
        if (isset($arrResultado[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "catalogo_codigo" => $value["tipo_persona_id"],
                    "catalogo_descripcion" => $value["tipo_persona_nombre"]
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }

            return $lst_resultado;
    }
    
    /* Descipción: Listado de empresas para la bandeja de prospectos */
    function ListadoBandejaProspectos($arrDatos){

        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
            "codigo_ejecutivo", "consolidado"
        );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            $arrResultado = array();
            return $arrResultado;
        }

        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_ejecutivo = $arrDatos['codigo_ejecutivo'];
        $consolidado = $arrDatos['consolidado'];

        switch ((int)$consolidado) {
            
            // Solicitud de Crédito
            case 6:
                
                $arrResultado = $this->mfunciones_microcreditos->ObtenerBandejaSolicitudCreditoEjecutivo($codigo_ejecutivo);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                
                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $dir_geo = $value["sol_checkin_geo"];

                        if($value["sol_checkin_geo"] == 'null' || $value["sol_checkin_geo"] == null || $value["sol_checkin_geo"] == 'null, null')
                        {
                            $dir_geo = GEO_FIE; 
                        }

                        $etapa_color = $value["etapa_color"];

                        if($value["etapa_color"] == 'null' || $value["etapa_color"] == null)
                        {
                            $etapa_color = "#006699";
                        }

                        $tiempo_horas = number_format($this->mfunciones_generales->getDiasLaborales($value["sol_asignado_fecha"], date('Y-m-d H:i:s')), 0, '.', ',');

                        $tiempo_horas = ($tiempo_horas<=1 ? 'Reciente' : $tiempo_horas . ' horas');

                        $item_valor = array(
                            "prospecto_id" => $value["sol_id"] . '_' . $consolidado,
                            "ejecutivo_id" => $value["ejecutivo_id"],
                            "empresa_id" => -1,
                            "empresa_categoria_codigo" => 1,
                            "empresa_categoria_detalle" => $this->mfunciones_generales->GetValorCatalogo(1, 'empresa_categoria'),
                            "prospecto_fecha_asignacion" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["sol_asignado_fecha"]),
                            "prospecto_consolidado_codigo" => $value["sol_consolidado"],
                            "prospecto_consolidado_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["sol_consolidado"], 'consolidado'),
                            "prospecto_observado_codigo" => $value["sol_observado_app"],
                            "prospecto_observado_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["sol_observado_app"], 'tipo_observacion'),
                            "empresa_nombre_legal" => $value["sol_primer_nombre"] . ' ' . $value["sol_primer_apellido"] . ' ' . $value["sol_segundo_apellido"],
                            "empresa_direccion" => $this->mfunciones_microcreditos->TextoTitulo($value["camp_nombre"]) . ' | ' . $tiempo_horas . 
' 
C.I.: ' . $value["sol_ci"] . ' ' . $value["sol_complemento"] . ' ' . ((int)$value['sol_extension']==-1 ? '' : $this->mfunciones_generales->GetValorCatalogoDB($value['sol_extension'], 'cI_lugar_emisionoextension')),
                            "empresa_direccion_geo" => $dir_geo,
                            "contacto" => 'Teléfono celular: ' . $value["sol_cel"],
                            "etapa_nombre" => $value["etapa_nombre"],
                            "etapa_color" => $etapa_color,
                            "tabs" => $this->mfunciones_generales->GetValorCatalogo($value["camp_id"], 'campana_productos_tabs')
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                } 
                else 
                {
                    $lst_resultado[0] = $arrResultado;
                }

                break;

            // Normalizador/Cobrador
            case 13:
                
                $arrResultado = $this->mfunciones_cobranzas->ObtenerBandejaCobranzasEjecutivo($codigo_ejecutivo);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                
                if (isset($arrResultado[0])) 
                {
                    $i = 0;
                    
                    foreach ($arrResultado as $key => $value) 
                    {
                        
                        $etapa_nombre = $value["etapa_nombre"];
                        
                        $dir_geo = GEO_FIE;

                        $etapa_color = $value["etapa_color"];

                        if($value["etapa_color"] == 'null' || $value["etapa_color"] == null)
                        {
                            $etapa_color = "#006699";
                        }

                        // Validar fecha de compromiso de pago de la última visita registrada
                        if($this->mfunciones_cobranzas->checkFechaComPago_vencido($value["cv_fecha_compromiso"]))
                        {
                            $etapa_nombre = $this->lang->line('cv_fecha_compromiso') . ' vencida';
                            $etapa_color = "#db1b1c";
                        }
                        
                        $tiempo_horas = number_format($this->mfunciones_generales->getDiasLaborales($value["norm_fecha"], date('Y-m-d H:i:s')), 0, '.', ',');

                        $tiempo_horas = ($tiempo_horas<=1 ? 'Reciente' : $tiempo_horas . ' horas');

                        $item_valor = array(
                            "prospecto_id" => $value["norm_id"] . '_' . $consolidado,
                            "ejecutivo_id" => $value["ejecutivo_id"],
                            "empresa_id" => -1,
                            "empresa_categoria_codigo" => 1,
                            "empresa_categoria_detalle" => $this->mfunciones_generales->GetValorCatalogo(1, 'empresa_categoria'),
                            "prospecto_fecha_asignacion" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["norm_fecha"]),
                            "prospecto_consolidado_codigo" => $value["norm_consolidado"],
                            "prospecto_consolidado_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["norm_consolidado"], 'consolidado'),
                            "prospecto_observado_codigo" => $value["norm_observado_app"],
                            "prospecto_observado_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["norm_observado_app"], 'tipo_observacion'),
                            "empresa_nombre_legal" => $value["norm_primer_nombre"] . ' ' . $value["norm_primer_apellido"] . ' ' . $value["norm_segundo_apellido"],
                            "empresa_direccion" => $tiempo_horas . 
' 
Num. Op.: ' . ((int)$value["registro_num_proceso"] <=0 ? $this->lang->line('prospecto_no_evaluacion') : $value["registro_num_proceso"]) . '
' . $this->lang->line('cv_fecha_compromiso_abrev') . ': ' . $this->mfunciones_generales->pivotFormatoFechaD_M_Y($value["cv_fecha_compromiso"]),
                            "empresa_direccion_geo" => $dir_geo,
                            "contacto" => 'Teléfono celular: ' . ((int)$value["norm_cel"] <=0 ? $this->lang->line('prospecto_no_evaluacion') : $value["norm_cel"]),
                            "etapa_nombre" => $etapa_nombre,
                            "etapa_color" => $etapa_color,
                            "tabs" => $this->mfunciones_generales->GetValorCatalogo($value["camp_id"], 'campana_productos_tabs')
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                } 
                else 
                {
                    $lst_resultado[0] = $arrResultado;
                }
                
                break;
                
            default:
                
                // Listado de los prospectos
                $arrResultado = $this->mfunciones_logica->ObtenerBandejaProspectos($codigo_ejecutivo, $consolidado);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $dir_geo = $value["prospecto_checkin_geo"];

                        if($value["prospecto_checkin_geo"] == 'null' || $value["prospecto_checkin_geo"] == null || $value["prospecto_checkin_geo"] == 'null, null')
                        {
                            $dir_geo = GEO_FIE; 
                        }

                        $etapa_color = $value["etapa_color"];

                        if($value["etapa_color"] == 'null' || $value["etapa_color"] == null)
                        {
                            $etapa_color = "#006699";
                        }

                        $tiempo_horas = number_format($this->mfunciones_generales->getDiasLaborales($value["prospecto_fecha_asignacion"], date('Y-m-d H:i:s')), 0, '.', ',');

                        $tiempo_horas = ($tiempo_horas<=1 ? 'Reciente' : $tiempo_horas . ' horas');

                        $item_valor = array(
                            "prospecto_id" => $value["prospecto_id"] . '_' . $consolidado,
                            "ejecutivo_id" => $value["ejecutivo_id"],
                            "empresa_id" => $value["empresa_id"],
                            "empresa_categoria_codigo" => $value["empresa_categoria"],
                            "empresa_categoria_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["empresa_categoria"], 'empresa_categoria'),
                            "prospecto_fecha_asignacion" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["prospecto_fecha_asignacion"]),
                            "prospecto_consolidado_codigo" => $value["prospecto_consolidado"],
                            "prospecto_consolidado_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["prospecto_consolidado"], 'consolidado'),
                            "prospecto_observado_codigo" => $value["prospecto_observado_app"],
                            "prospecto_observado_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["prospecto_observado_app"], 'tipo_observacion'),
                            "empresa_nombre_legal" => $value["empresa_nombre_legal"],
                            "empresa_direccion" => $this->mfunciones_microcreditos->TextoTitulo($value["camp_nombre"]) . ' | ' . $tiempo_horas . ($value["onboarding"]==1 ? '' : '
C.I.: ' . $value["general_ci"] . ' ' . $this->mfunciones_generales->GetValorCatalogo($value["general_ci_extension"], 'extension_ci') . '
Interés: ' . $this->mfunciones_generales->GetValorCatalogo($value["general_interes"], 'grado_interes')),
                            "empresa_direccion_geo" => $dir_geo,
                            "contacto" => 'Telefóno: ' . $value["contacto"],
                            "etapa_nombre" => $value["etapa_nombre"],
                            "etapa_color" => $etapa_color,
                            "tabs" => $this->mfunciones_generales->GetValorCatalogo($value["camp_id"], 'campana_productos_tabs')
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                } 
                else 
                {
                    $lst_resultado[0] = $arrResultado;
                }
                
                break;
        }

        return $lst_resultado;
    }
    
    /* Descipción: Listado de las observaciones del prospecto */
    function ListadoObsProspectos($arrDatos){

            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "codigo_prospecto", "estado"
                    );
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                    $arrResultado = array();
                    return $arrResultado;
            }

            // Si Todo bien... se captura los datos y se procesa la información

            $codigo_prospecto = $arrDatos['codigo_prospecto'];
            $estado = $arrDatos['estado'];
	
        // *** Obtener Código y Tipo Persona ***
            $aux_get_codigo = $this->getCodigo($codigo_prospecto);

            if($aux_get_codigo->codigo == 0)
            {
                $arrError = array(
                    "error" => true,
                    "errorMessage" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')',
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')'
                    )
                );

                $this->response($arrError, 200);
                exit();
            }

            $codigo_prospecto = $aux_get_codigo->codigo;
            $codigo_tipo_persona = $aux_get_codigo->tipo_persona;
        // *** Obtener Código y Tipo Persona ***
        
        switch ((int)$codigo_tipo_persona) {
            
            // Solicitud de Crédito
            case 6:
                
                $arrResultado = $this->mfunciones_microcreditos->SolObtenerObsProspectos($codigo_prospecto, $estado, (int)$codigo_tipo_persona);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "prospecto_id" => $value["codigo_registro"],
                            "usuario_id" => $value["usuario_id"],
                            "usuario_nombre" => $value["usuario_nombre"],
                            "documento_id_codigo" => $value["documento_id"],
                            "documento_id_detalle" => $value["documento_nombre"],
                            "obs_tipo_codigo" => $value["obs_tipo"],
                            "obs_tipo_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["obs_tipo"], 'tipo_observacion'),
                            "obs_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["obs_fecha"]),
                            "obs_detalle" => $value["obs_detalle"],
                            "obs_estado_codigo" => $value["obs_estado"],
                            "obs_estado_codigo_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["obs_estado"], 'estado_observacion')
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                } 
                else 
                {
                    $lst_resultado[0] = $arrResultado;
                }

                break;

            // Normalizador/Cobrador
            case 13:
                
                $arrResultado = $this->mfunciones_cobranzas->ObtenerObsRegistros($codigo_prospecto, $estado, (int)$codigo_tipo_persona);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "prospecto_id" => $value["codigo_registro"],
                            "usuario_id" => $value["usuario_id"],
                            "usuario_nombre" => $value["usuario_nombre"],
                            "documento_id_codigo" => $value["documento_id"],
                            "documento_id_detalle" => $value["documento_nombre"],
                            "obs_tipo_codigo" => $value["obs_tipo"],
                            "obs_tipo_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["obs_tipo"], 'tipo_observacion'),
                            "obs_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["obs_fecha"]),
                            "obs_detalle" => $value["obs_detalle"],
                            "obs_estado_codigo" => $value["obs_estado"],
                            "obs_estado_codigo_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["obs_estado"], 'estado_observacion')
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                } 
                else 
                {
                    $lst_resultado[0] = $arrResultado;
                }
                
                break;
                
            default:
                
                $arrResultado = $this->mfunciones_logica->ObtenerObsProspectos($codigo_prospecto, $estado);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "prospecto_id" => $value["prospecto_id"],
                            "usuario_id" => $value["usuario_id"],
                            "usuario_nombre" => $value["usuario_nombre"],
                            "documento_id_codigo" => $value["documento_id"],
                            "documento_id_detalle" => $value["documento_nombre"],
                            "obs_tipo_codigo" => $value["obs_tipo"],
                            "obs_tipo_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["obs_tipo"], 'tipo_observacion'),
                            "obs_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["obs_fecha"]),
                            "obs_detalle" => $value["obs_detalle"],
                            "obs_estado_codigo" => $value["obs_estado"],
                            "obs_estado_codigo_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["obs_estado"], 'estado_observacion')
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                } 
                else 
                {
                    $lst_resultado[0] = $arrResultado;
                }
                
                break;
        }
        
        return $lst_resultado;
    }
    
    /* Descipción: Se marca el CheckOut del prospecto */
    function ActualizaCheckOut($arrDatos, $usuario, $nombre_servicio, $perfil_app_nombre){

            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "codigo_prospecto", "ubicacion_geo"
                    );
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                    $arrResultado = array();
                    return $arrResultado;
            }

            // Si Todo bien... se captura los datos y se procesa la información

            $codigo_prospecto = $arrDatos['codigo_prospecto'];
            $geolocalizacion = $arrDatos['ubicacion_geo'];
            
        // *** Obtener Código y Tipo Persona ***
            $aux_get_codigo = $this->getCodigo($codigo_prospecto);

            if($aux_get_codigo->codigo == 0)
            {
                $arrError = array(
                    "error" => true,
                    "errorMessage" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')',
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')'
                    )
                );

                $this->response($arrError, 200);
                exit();
            }

            $codigo_prospecto = $aux_get_codigo->codigo;
            $codigo_tipo_persona = $aux_get_codigo->tipo_persona;
        // *** Obtener Código y Tipo Persona ***
        
        $accion_fecha = date('Y-m-d H:i:s');

        $arrError =  array(		
            "error" => true,
            "errorMessage" => "¡Ya realizó el Check de la Llamada del Cliente!",
            "errorCode" => 103,
            "result" => array(
                "mensaje" => $this->lang->line('IncompletoApp')
            )
        );
        
        switch ((int)$codigo_tipo_persona) {
            
            // Solicitud de Crédito
            case 6:
                
                // Primero se verifica que no haya hecho CheckIn con anterioridad
                $arrResultado_checkin = $this->mfunciones_microcreditos->SolVerificaCheckOut($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado_checkin);

                if (isset($arrResultado_checkin[0])) 
                {
                    // Si ya ha hecho un CheckIn, se muestra un mesaje de error
                    $this->response($arrError, 200);
                    exit();
                }

                // Si no hizo el ChekIn antes, se procede a registrarlo        
                // Update CheckIn
                $this->mfunciones_microcreditos->SolUpdateCheckOut($accion_fecha, $geolocalizacion, $codigo_prospecto, $usuario, $accion_fecha);

                break;

            // Normalizador/Cobrador
            case 13:
                
                // No habilitado para el perfil
                
                $arrError =  array(		
                    "error" => true,
                    "errorMessage" => sprintf($this->lang->line('norm_no_checkout'), $perfil_app_nombre),
                    "errorCode" => 103,
                    "result" => array(
                        "mensaje" => $this->lang->line('IncompletoApp')
                    )
                );
                
                $this->response($arrError, 200);
                exit();
                
                break;
                
            default:
                
                // Primero se verifica que no haya hecho CheckIn con anterioridad
                $arrResultado_checkin = $this->mfunciones_logica->VerificaCheckOut($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado_checkin);

                if (isset($arrResultado_checkin[0])) 
                {
                    // Si ya ha hecho un CheckIn, se muestra un mesaje de error
                    $this->response($arrError, 200);
                    exit();
                }

                // Si no hizo el ChekIn antes, se procede a registrarlo        
                // Update CheckIn

                $this->mfunciones_logica->UpdateCheckOut($accion_fecha, $geolocalizacion, $codigo_prospecto, $usuario, $accion_fecha);
                
                break;
        }
        
        $lst_resultado[0] = array(
             "mensaje" => "Realizó el Check la Llamada del Cliente Correctamente."
        );

        // Se guarda el Log para la auditoría        
        $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), $geolocalizacion, $usuario, $accion_fecha);
        
        return $lst_resultado;
    }
    
    /* Descipción: Se marca el CheckIn del prospecto */
    function ActualizaCheckIn($arrDatos, $usuario, $nombre_servicio){

            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "codigo_prospecto", "ubicacion_geo"
                    );
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                    $arrResultado = array();
                    return $arrResultado;
            }

            // Si Todo bien... se captura los datos y se procesa la información

            $codigo_prospecto = $arrDatos['codigo_prospecto'];
            $geolocalizacion = $arrDatos['ubicacion_geo'];
	
        // *** Obtener Código y Tipo Persona ***
            $aux_get_codigo = $this->getCodigo($codigo_prospecto);

            if($aux_get_codigo->codigo == 0)
            {
                $arrError = array(
                    "error" => true,
                    "errorMessage" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')',
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')'
                    )
                );

                $this->response($arrError, 200);
                exit();
            }

            $codigo_prospecto = $aux_get_codigo->codigo;
            $codigo_tipo_persona = $aux_get_codigo->tipo_persona;
        // *** Obtener Código y Tipo Persona ***
            
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Error genérico
        $arrError =  array(		
            "error" => true,
            "errorMessage" => "¡Ya realizó el Check de la visita del Cliente!",
            "errorCode" => 103,
            "result" => array(
                "mensaje" => $this->lang->line('IncompletoApp')
            )
        );
        
        switch ((int)$codigo_tipo_persona) {
            
            // Solicitud de Crédito
            case 6:
                // Primero se verifica que no haya hecho CheckIn con anterioridad
                $arrResultado_checkin = $this->mfunciones_microcreditos->SolVerificaCheckIn($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado_checkin);

                if (isset($arrResultado_checkin[0])) 
                {
                    // Si ya ha hecho un CheckIn, se muestra un mesaje de error
                    $this->response($arrError, 200);
                    exit();
                }
                
                // Si no hizo el ChekIn antes, se procede a registrarlo        
                // Update CheckIn

                $this->mfunciones_microcreditos->SolUpdateCheckIn($accion_fecha, $geolocalizacion, $codigo_prospecto, $usuario, $accion_fecha);

                break;

            // Normalizador/Cobrador
            case 13:
                
                // Validaciones de las reglas de negocio: Sólo se asigna el CheckIn a la última visita registrada (sólo 1 vez)
                $check_visita = $this->mfunciones_cobranzas->checkVisitaRegistrada($codigo_prospecto, -1, 'check_checkin');
                
                if($check_visita->error)
                {
                    $arrError =  array(		
                        "error" => true,
                        "errorMessage" => $check_visita->error_texto,
                        "errorCode" => 103,
                        "result" => array(
                            "mensaje" => $this->lang->line('IncompletoApp')
                        )
                    );
                    
                    $this->response($arrError, 200);
                    exit();
                }
                
                // Registrar CheckIn
                $this->mfunciones_cobranzas->NormVisitaUpdateCheckIn($geolocalizacion, $check_visita->codigo_visita, $codigo_prospecto, $usuario, $accion_fecha);
                
                break;
                
            default:
                
                // Primero se verifica que no haya hecho CheckIn con anterioridad
                $arrResultado_checkin = $this->mfunciones_logica->VerificaCheckIn($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado_checkin);

                if (isset($arrResultado_checkin[0])) 
                {
                    // Si ya ha hecho un CheckIn, se muestra un mesaje de error
                    $this->response($arrError, 200);
                    exit();
                }

                // Si no hizo el ChekIn antes, se procede a registrarlo        
                // Update CheckIn

                $this->mfunciones_logica->UpdateCheckIn($accion_fecha, $geolocalizacion, $codigo_prospecto, $usuario, $accion_fecha);

                // Detalle del Prospecto
                $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 3, $arrResultado3[0]['prospecto_etapa'], $usuario, $accion_fecha, 2);
                /***  REGISTRAR SEGUIMIENTO ***/
                $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, 3, 1, 'Efectua Check-In', $usuario, $accion_fecha);

                break;
        }

        $lst_resultado[0] = array(
            "mensaje" => "Realizó el Check de la Visita con el Cliente Correctamente."
        );
        
        // Se guarda el Log para la auditoría        
        $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), $geolocalizacion, $usuario, $accion_fecha);
        
        return $lst_resultado;
    }
	
    /* Descipción: Listado de los servicios */
    function ListadoServicios($arrDatos){

        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "estado"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrResultado = array();
                return $arrResultado;
        }

        // Si Todo bien... se captura los datos y se procesa la información

        $estado = $arrDatos['estado'];

            // Listado de los Servicios
            $arrResultado1 = $this->mfunciones_logica->ObtenerServicios($estado);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
				
	if (isset($arrResultado1[0])) 
        {
            $i = 0;
                        
            foreach ($arrResultado1 as $key => $value) 
            {
                $item_valor = array(
                    "servicio_id" => $value["servicio_id"],
                    "servicio_detalle" => $value["servicio_detalle"]
                );
                $lst_resultado1[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado1[0] = $arrResultado1;
        }

        return $lst_resultado1;
    }
	
	/* Descipción: Listado del detalle del prospecto */
    function ListadoDetalleProspecto($arrDatos){

            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "codigo_prospecto", "codigo_empresa", "tipo_prospecto"
                    );
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                    $arrResultado = array();
                    return $arrResultado;
            }
            
            // Si Todo bien... se captura los datos y se procesa la información

            $codigo_prospecto = $arrDatos['codigo_prospecto'];
            $codigo_empresa = $arrDatos['codigo_empresa'];
            $tipo_prospecto = $arrDatos['tipo_prospecto'];
            
            // *** Obtener Código y Tipo Persona ***
                $aux_get_codigo = $this->getCodigo($codigo_prospecto);

                if($aux_get_codigo->codigo == 0)
                {
                    $arrError = array(
                        "error" => true,
                        "errorMessage" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')',
                        "errorCode" => 96,
                        "result" => array(
                            "mensaje" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')'
                        )
                    );

                    $this->response($arrError, 200);
                    exit();
                }

                $codigo_prospecto = $aux_get_codigo->codigo;
                $codigo_tipo_persona = $aux_get_codigo->tipo_persona;
            // *** Obtener Código y Tipo Persona ***
            
            // Se pregunta si el tipo de prospecto es COMERCIO o ESTABLECIMIENTO (sucursal)		
            switch ((int)$codigo_tipo_persona) 
            {
                // Solicitud de Crédito
                case 6:
                    
                    $arrResultado = $this->mfunciones_microcreditos->ObtenerDetalleSolicitudCredito($codigo_prospecto);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                    
                    if (isset($arrResultado[0])) 
                    {
                        $i = 0;

                        foreach ($arrResultado as $key => $value) 
                        {
                            $dir_geo = $value["sol_checkin_geo"];
                
                            if($value["sol_checkin_geo"] == 'null' || $value["sol_checkin_geo"] == null || $value["sol_checkin_geo"] == 'null, null')
                            {
                                $dir_geo = GEO_FIE; 
                            }
                            
                            $item_valor = array(
                                    "prospecto_id" => $value["sol_id"] . '_' . $codigo_tipo_persona,
                                    "ejecutivo_id" => $value["ejecutivo_id"],
                                    "tipo_persona_codigo" => $codigo_tipo_persona,
                                    "tipo_persona_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($codigo_tipo_persona, 'tipo_persona'),
                                    "empresa_id" => -1,
                                    "empresa_consolidada_codigo" => 1,
                                    "empresa_consolidada_detalle" => $this->mfunciones_generales->GetValorCatalogo(1, 'consolidado'),
                                    "empresa_categoria_codigo" => 1,
                                    "empresa_categoria_detalle" => $this->mfunciones_generales->GetValorCatalogo(1, 'empresa_categoria'),
                                    "empresa_nit" => '',
                                    "empresa_adquiriente_codigo" => '',
                                    "empresa_adquiriente_detalle" => 'ATC SA',
                                    "empresa_tipo_sociedad_codigo" => '',
                                    "empresa_tipo_sociedad_detalle" => $this->mfunciones_generales->GetValorCatalogoDB('', 'TPS'),
                                    "empresa_nombre_legal" => '',
                                    "empresa_nombre_fantasia" => '',
                                    "empresa_rubro_codigo" => '',
                                    "empresa_rubro_detalle" => '',
                                    "empresa_perfil_comercial_codigo" => '',
                                    "empresa_perfil_comercial_detalle" => '',
                                    "empresa_mcc_codigo" => '',
                                    "empresa_mcc_detalle" => '',
                                    "empresa_nombre_referencia" => '',
                                    "empresa_ha_desde" => '',
                                    "empresa_ha_hasta" => '',
                                    "empresa_dias_atencion" => '',
                                    "empresa_medio_contacto_codigo" => '',
                                    "empresa_medio_contacto_detalle" => '',
                                    "empresa_dato_contacto" => $value["sol_cel"],
                                    "empresa_email" => $value["sol_correo"],
                                    "empresa_departamento_codigo" => '',
                                    "empresa_departamento_detalle" => '',
                                    "empresa_municipio_codigo" => '',
                                    "empresa_municipio_detalle" => '',
                                    "empresa_zona_codigo" => '',
                                    "empresa_zona_detalle" => '',
                                    "empresa_tipo_calle_codigo" => '',
                                    "empresa_tipo_calle_detalle" => '',
                                    "empresa_calle" => '',
                                    "empresa_numero" => '',
                                    "empresa_direccion_literal" => 'Ubicación del Solicitante',
                                    "empresa_direccion_geo" => $dir_geo,
                                    "empresa_info_adicional" => '',
                                    "fecha_visita_ini" => '',
                                    "fecha_visita_fin" => '',
                                    "servicios_solicitados" => array()
                                
                                    );
                            
                            $lst_resultado[$i] = $item_valor;

                            $i++;
                        }
                    } 
                    else 
                    {
                        $lst_resultado[0] = $arrResultado;
                    }
                    
                    break;
                    
                // Normalizador/Cobrador
                    
                case 13:
                    
                    $arrResultado = $this->mfunciones_cobranzas->ObtenerDetalleRegistro($codigo_prospecto);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                    
                    if (isset($arrResultado[0])) 
                    {
                        $i = 0;

                        foreach ($arrResultado as $key => $value) 
                        {
                            $dir_geo = GEO_FIE;
                            
                            $item_valor = array(
                                    "prospecto_id" => $value["norm_id"] . '_' . $codigo_tipo_persona,
                                    "ejecutivo_id" => $value["ejecutivo_id"],
                                    "tipo_persona_codigo" => $codigo_tipo_persona,
                                    "tipo_persona_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($codigo_tipo_persona, 'tipo_persona'),
                                    "empresa_id" => -1,
                                    "empresa_consolidada_codigo" => 1,
                                    "empresa_consolidada_detalle" => $this->mfunciones_generales->GetValorCatalogo(1, 'consolidado'),
                                    "empresa_categoria_codigo" => 1,
                                    "empresa_categoria_detalle" => $this->mfunciones_generales->GetValorCatalogo(1, 'empresa_categoria'),
                                    "empresa_nit" => '',
                                    "empresa_adquiriente_codigo" => '',
                                    "empresa_adquiriente_detalle" => 'ATC SA',
                                    "empresa_tipo_sociedad_codigo" => '',
                                    "empresa_tipo_sociedad_detalle" => $this->mfunciones_generales->GetValorCatalogoDB('', 'TPS'),
                                    "empresa_nombre_legal" => '',
                                    "empresa_nombre_fantasia" => '',
                                    "empresa_rubro_codigo" => '',
                                    "empresa_rubro_detalle" => '',
                                    "empresa_perfil_comercial_codigo" => '',
                                    "empresa_perfil_comercial_detalle" => '',
                                    "empresa_mcc_codigo" => '',
                                    "empresa_mcc_detalle" => '',
                                    "empresa_nombre_referencia" => '',
                                    "empresa_ha_desde" => '',
                                    "empresa_ha_hasta" => '',
                                    "empresa_dias_atencion" => '',
                                    "empresa_medio_contacto_codigo" => '',
                                    "empresa_medio_contacto_detalle" => '',
                                    "empresa_dato_contacto" => ((int)$value["norm_cel"] <=0 ? '800101112' : $value["norm_cel"]),
                                    "empresa_email" => $value["agente_correo"],
                                    "empresa_departamento_codigo" => '',
                                    "empresa_departamento_detalle" => '',
                                    "empresa_municipio_codigo" => '',
                                    "empresa_municipio_detalle" => '',
                                    "empresa_zona_codigo" => '',
                                    "empresa_zona_detalle" => '',
                                    "empresa_tipo_calle_codigo" => '',
                                    "empresa_tipo_calle_detalle" => '',
                                    "empresa_calle" => '',
                                    "empresa_numero" => '',
                                    "empresa_direccion_literal" => 'Ubicación del Solicitante',
                                    "empresa_direccion_geo" => $dir_geo,
                                    "empresa_info_adicional" => '',
                                    "fecha_visita_ini" => '',
                                    "fecha_visita_fin" => '',
                                    "servicios_solicitados" => array()
                                
                                    );
                            
                            $lst_resultado[$i] = $item_valor;

                            $i++;
                        }
                    } 
                    else 
                    {
                        $lst_resultado[0] = $arrResultado;
                    }
                    
                    break;
                    
                default:
                    
                    // Detalle del COMERCIO
                    $arrResultado = $this->mfunciones_logica->ObtenerDetalleProspecto_comercio($codigo_empresa, $codigo_prospecto);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                    if (isset($arrResultado[0])) 
                    {
                        $i = 0;

                        foreach ($arrResultado as $key => $value) 
                        {
                            $dir_geo = $value["prospecto_checkin_geo"];
                
                            if($value["prospecto_checkin_geo"] == 'null' || $value["prospecto_checkin_geo"] == null || $value["prospecto_checkin_geo"] == 'null, null')
                            {
                                $dir_geo = GEO_FIE; 
                            }
                            
                            $item_valor = array(
                                    "prospecto_id" => $value["prospecto_id"] . '_' . $codigo_tipo_persona,
                                    "ejecutivo_id" => $value["ejecutivo_id"],
                                    "tipo_persona_codigo" => $value["tipo_persona_id"],
                                    "tipo_persona_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["tipo_persona_id"], 'tipo_persona'),
                                    "empresa_id" => $value["empresa_id"],
                                    "empresa_consolidada_codigo" => $value["empresa_consolidada"],
                                    "empresa_consolidada_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["empresa_consolidada"], 'consolidado'),
                                    "empresa_categoria_codigo" => $value["empresa_categoria"],
                                    "empresa_categoria_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["empresa_categoria"], 'empresa_categoria'),
                                    "empresa_nit" => $value["empresa_nit"],
                                    "empresa_adquiriente_codigo" => $value["empresa_adquiriente"],
                                    "empresa_adquiriente_detalle" => 'ATC SA',
                                    "empresa_tipo_sociedad_codigo" => $value["empresa_tipo_sociedad"],
                                    "empresa_tipo_sociedad_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_tipo_sociedad"], 'TPS'),
                                    "empresa_nombre_legal" => $value["empresa_nombre_legal"],
                                    "empresa_nombre_fantasia" => $value["empresa_nombre_fantasia"],
                                    "empresa_rubro_codigo" => $value["empresa_rubro"],
                                    "empresa_rubro_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_rubro"], 'RUB'),
                                    "empresa_perfil_comercial_codigo" => $value["empresa_perfil_comercial"],
                                    "empresa_perfil_comercial_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_perfil_comercial"], 'PEC'),
                                    "empresa_mcc_codigo" => $value["empresa_mcc"],
                                    "empresa_mcc_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_mcc"], 'MCC'),
                                    "empresa_nombre_referencia" => $value["empresa_nombre_referencia"],
                                    "empresa_ha_desde" => $value["empresa_ha_desde"],
                                    "empresa_ha_hasta" => $value["empresa_ha_hasta"],
                                    "empresa_dias_atencion" => $value["empresa_dias_atencion"],
                                    "empresa_medio_contacto_codigo" => $value["empresa_medio_contacto"],
                                    "empresa_medio_contacto_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_medio_contacto"], 'MCO'),
                                    "empresa_dato_contacto" => $value["general_telefono"],
                                    "empresa_email" => $value["general_email"],
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
                                    "empresa_direccion_literal" => ($value["general_direccion"] == null ? 'Ubicación del Solicitante' : $value["general_direccion"]),
                                    "empresa_direccion_geo" => $dir_geo,
                                    "empresa_info_adicional" => $value["empresa_info_adicional"],
                                    "fecha_visita_ini" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["cal_visita_ini"]),
                                    "fecha_visita_fin" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["cal_visita_fin"]),
                                    "servicios_solicitados" => array()
                                
                                    );
                            
                            $lst_resultado[$i] = $item_valor;

                            $i++;
                        }
                    } 
                    else 
                    {
                            $lst_resultado[0] = $arrResultado;
                    }

                    break;
            }

        return $lst_resultado;
    }
    
    /* Descipción: Listado de los servicios */
    function DetalleComercioPorNIT($arrDatos){

        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "nit"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrResultado = array();
                return $arrResultado;
        }

        // Si Todo bien... se captura los datos y se procesa la información

        $nit = (int)$arrDatos['nit'];

        // Se usa esta variable para verificar si se contró el NIT en PayStudio o en el Sistema
        $swNIT = 0;
        
            // A. SE PREGUNTA A PAYSTUDIO (NAZIR) A TRAVÉS DE SU WEB SERVICE SI EL NIT EXISTE Y POBLAR LOS CAMPOS
        
            $RespuestaWS = $this->mfunciones_generales->ConsultaWebServiceNIT($nit);
            if (isset($RespuestaWS[0])) 
            {
                $swNIT = 1;
                $lst_resultado1 = $RespuestaWS;
            }
            
            
            // Detalle del Comercio
            $arrResultado1 = $this->mfunciones_logica->ObtenerComercioPorNIT($nit);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
				
            if (isset($arrResultado1[0])) 
            {
                $i = 0;

                foreach ($arrResultado1 as $key => $value) 
                {
                    $item_valor = array(
                        "parent_id" => $value["empresa_id"],
                        "parent_nit" => $value["empresa_nit"],
                        "parent_adquiriente_codigo" => $value["empresa_adquiriente"],
                        "parent_adquiriente_detalle" => 'ATC SA',
                        "parent_tipo_sociedad_codigo" => $value["empresa_tipo_sociedad"],
                        "parent_tipo_sociedad_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_tipo_sociedad"], 'TPS'),
                        "parent_nombre_legal" => $value["empresa_nombre_legal"],
                        "parent_nombre_fantasia" => $value["empresa_nombre_fantasia"],
                        "parent_rubro_codigo" => $value["empresa_rubro"],
                        "parent_rubro_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_rubro"], 'RUB'),
                        "parent_perfil_comercial_codigo" => $value["empresa_perfil_comercial"],
                        "parent_perfil_comercial_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_rubro"], 'PEC'),
                        "parent_mcc_codigo" => $value["empresa_mcc"],
                        "parent_mcc_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["empresa_mcc"], 'MCC')
                    );
                    $lst_resultado1[$i] = $item_valor;

                    $i++;
                }
                
                $swNIT = 1;
            }

        if($swNIT == 0)
        {
            $lst_resultado1 = array();
        }
            
        return $lst_resultado1;
    }
    
    /* Descipción: Listado de horarios registrados del Ejecutivo de Cuentas */
    function ListadoHorariosEjecutivo($arrDatos){

        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_ejecutivo", "fecha_dia", "tipo_visita"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrResultado = array();
                return $arrResultado;
        }

        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_ejecutivo = $arrDatos['codigo_ejecutivo'];
        
        $fecha_dia = $arrDatos['fecha_dia'];        
        if($arrDatos['fecha_dia'] != "-1")
        {
            $fecha_dia = $this->mfunciones_generales->getFormatoFechaDate($arrDatos['fecha_dia']);
        }
        
        $tipo_visita = $arrDatos['tipo_visita'];

            // Listado de los Servicios
            $arrResultado1 = $this->mfunciones_logica->ObtenerHorariosEjecutivo($codigo_ejecutivo, $fecha_dia, $tipo_visita);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
				
	if (isset($arrResultado1[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado1 as $key => $value) 
            {
                $item_valor = array(
                    "cal_id" => $value["cal_id"],
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "cal_id_visita" => $value["cal_id_visita"],
                    "cal_tipo_visita_codigo" => $value["cal_tipo_visita"],
                    "cal_tipo_visita_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["cal_tipo_visita"], 'tipo_visita'),
                    "cal_visita_ini" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["cal_visita_ini"]),
                    "cal_visita_fin" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["cal_visita_fin"])
                );
                $lst_resultado1[$i] = $item_valor;

                $i++;
            }
        }
        else 
        {
            $lst_resultado1[0] = $arrResultado1;
        }

        return $lst_resultado1;
    }
    
    /* Descipción: Insertar un Nuevo Prospecto */
    function InsertarProspectoAPP($arrDatos, $usuarioAPP, $nombre_servicio){

        $parametros = array(
                "ubicacion_geo", "codigo_ejecutivo", "empresa_categoria", 
                "empresa_nombre_referencia", "tipo_persona", 
                "empresa_nit", "empresa_adquiriente", "empresa_tipo_sociedad", 
                "empresa_rubro", "empresa_perfil_comercial", "empresa_mcc", 
                "empresa_nombre_legal", "empresa_nombre_fantasia", 
                "empresa_ha_desde", "empresa_ha_hasta", "empresa_dias_atencion", 
                "empresa_medio_contacto", "empresa_dato_contacto", "empresa_email", 
                "empresa_departamento", "empresa_municipio", "empresa_zona", 
                "empresa_tipo_calle", "empresa_calle", "empresa_numero", 
                "empresa_direccion_literal", "empresa_direccion_geo", "empresa_info_adicional",
                "fecha_visita_ini", "fecha_visita_fin", "arrServicios"
                );
        
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            $arrResultado = array();
            return $arrResultado;
        }
        
        $geolocalizacion = $arrDatos['ubicacion_geo'];
        
        $ejecutivo_id = $arrDatos['codigo_ejecutivo'];
        $empresa_categoria = $arrDatos['empresa_categoria'];
        $empresa_nombre_referencia = $arrDatos['empresa_nombre_referencia'];
        $tipo_persona = $arrDatos['tipo_persona'];
        $empresa_nit = $arrDatos['empresa_nit'];
        $empresa_adquiriente = $arrDatos['empresa_adquiriente'];
        $empresa_tipo_sociedad = $arrDatos['empresa_tipo_sociedad'];
        $empresa_rubro = $arrDatos['empresa_rubro'];
        $empresa_perfil_comercial = $arrDatos['empresa_perfil_comercial'];
        $empresa_mcc = $arrDatos['empresa_mcc'];
        $empresa_nombre_legal = $arrDatos['empresa_nombre_legal'];
        $empresa_nombre_fantasia = $arrDatos['empresa_nombre_fantasia'];
                
        // Establecimiento/Sucursal
        if($empresa_categoria == 2)
        {
            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "empresa_parent", "empresa_nombre_establecimiento", "empresa_denominacion_corta"
                    );
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                    $arrResultado = array();
                    return $arrResultado;
            }
            
            $empresa_depende = $arrDatos['empresa_parent'];            
            $empresa_nombre_establecimiento = $arrDatos['empresa_nombre_establecimiento'];
            $empresa_denominacion_corta = $arrDatos['empresa_denominacion_corta'];
        }              

        // Si Todo bien... se captura los datos y se procesa la información

        $accion_usuario = $usuarioAPP;
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Datos
        
        $empresa_ha_desde = $arrDatos['empresa_ha_desde'];
        $empresa_ha_hasta = $arrDatos['empresa_ha_hasta'];
        $empresa_dias_atencion = $arrDatos['empresa_dias_atencion'];
        $empresa_medio_contacto = $arrDatos['empresa_medio_contacto'];        
        $empresa_dato_contacto = $arrDatos['empresa_dato_contacto'];
        $empresa_email = $arrDatos['empresa_email'];
        $empresa_departamento = $arrDatos['empresa_departamento'];
        $empresa_municipio = $arrDatos['empresa_municipio'];
        $empresa_zona = $arrDatos['empresa_zona'];
        $empresa_tipo_calle = $arrDatos['empresa_tipo_calle'];
        $empresa_calle = $arrDatos['empresa_calle'];
        $empresa_numero = $arrDatos['empresa_numero'];
        $empresa_direccion_literal = $arrDatos['empresa_direccion_literal'];
        $empresa_direccion_geo = $arrDatos['empresa_direccion_geo'];
        $empresa_info_adicional = $arrDatos['empresa_info_adicional'];
        
        $cal_visita_ini = $arrDatos['fecha_visita_ini'];
        $cal_visita_fin = $arrDatos['fecha_visita_fin'];
        
        $arrServicios = $arrDatos['arrServicios'];
        
        // PASO 1: Se realiza las validaciones pertinenetes (LA VALIDACIÓN PARA LA APP ES PARA TODOS LOS CAMPOS, EN LA WEB PUEDE VARIAR)
        
        if((int)($ejecutivo_id) == 0 ||
                $this->mfunciones_generales->VerificaFechaH_M($empresa_ha_desde) == false || $this->mfunciones_generales->VerificaFechaH_M($empresa_ha_hasta) == false || $empresa_dias_atencion == '' ||
                (int)($empresa_medio_contacto) == 0 || $empresa_dato_contacto == '' || $this->mfunciones_generales->VerificaCorreo($empresa_email) == false || 
                (int)($empresa_departamento) == 0 || (int)($empresa_municipio) == 0 || (int)($empresa_zona) == 0 ||
                (int)($empresa_tipo_calle) == 0 || $empresa_calle == '' || (int)($empresa_numero) == 0 || 
                $empresa_direccion_literal == '' || $empresa_direccion_geo == '' || 
                (int)($ejecutivo_id) == 0 || (int)($empresa_categoria) == 0 || (int)($tipo_persona) == 0 || 
                (int)($empresa_nit) == 0 || (int)($empresa_adquiriente) == 0 || (int)($empresa_tipo_sociedad) == 0 || 
                (int)($empresa_rubro) == 0 || 
                (int)($empresa_perfil_comercial) == 0 || (int)($empresa_mcc) == 0 || $empresa_nombre_legal == '' || $empresa_nombre_fantasia == '' || $this->mfunciones_generales->VerificaFechaD_M_Y_H_M($cal_visita_ini) == false || $this->mfunciones_generales->VerificaFechaD_M_Y_H_M($cal_visita_fin) == false)
        {
            $arrError =  array(
                            "error" => true,
                            "errorMessage" => $this->lang->line('CamposObligatorios'),
                            "errorCode" => 96,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
                    );

            $this->response($arrError, 200);
            exit();
        }
        
        // Se verifica la estructura del Array de Servicios
        if (isset($arrServicios[0])) 
        {
            $parametros_servicios = "servicio_id";
            
            foreach ($arrServicios as $key => $value) 
            {
                if(!isset($value[$parametros_servicios]))
                {
                    $arrError =  array(
                                    "error" => true,
                                    "errorMessage" => $this->lang->line('CamposObligatorios'),
                                    "errorCode" => 96,
                                    "result" => array(
                                        "mensaje" => $this->lang->line('IncompletoApp')
                                    )
                            );

                    $this->response($arrError, 200);
                    exit();
                }
            }
        }
        
        // - FIN VALIDACIÓN
        
        // Comercio
        if($empresa_categoria == 1)
        {
            // PASO 1: Se realiza las validaciones pertinenetes
            
            // A. SE PREGUNTA A PAYSTUDIO (NAZIR) A TRAVÉS DE SU WEB SERVICE SI EL NIT EXISTE Y POBLAR LOS CAMPOS        
            $RespuestaWS = $this->mfunciones_generales->ConsultaWebServiceNIT($empresa_nit);
            if (isset($RespuestaWS[0])) 
            {
                $arrError =  array(
                                "error" => true,
                                "errorMessage" => $this->lang->line('FormularioNIT') . ' (Registrado en PayStudio)',
                                "errorCode" => 97,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                        );

                $this->response($arrError, 200);
                exit();
            }
            
            // B. SE PREGUNTA EN LA BASE DE DATOS DEL SISTEMA
            $arrResultadoNit = $this->mfunciones_logica->VerificaNitExistente($empresa_nit);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultadoNit);

            if (isset($arrResultadoNit[0]))
            {
                $arrError =  array(
                                "error" => true,
                                "errorMessage" => $this->lang->line('FormularioNIT'),
                                "errorCode" => 97,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                        );

                $this->response($arrError, 200);
                exit();
            }
            
            // C. SE PREGUNTA EN LA BASE DE DATOS DEL SISTEMA EMPRESAS EN EVALUACIÓN O EN PROSPECTO
            $arrResultadoNit2 = $this->mfunciones_logica->ObtenerComercioPorNITSolicitud($empresa_nit);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultadoNit2);

            if (isset($arrResultadoNit2[0]))
            {
                $arrError =  array(
                                "error" => true,
                                "errorMessage" => $this->lang->line('FormularioNIT_revision'),
                                "errorCode" => 97,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                        );

                $this->response($arrError, 200);
                exit();
            }
            
            // PASO 2: Insertar en la tabla "empresa" (dependiendo si es comercio o establecimiento/sucursal
            $arrResultado1 = $this->mfunciones_logica->InsertarProspecto_comercioAPP($ejecutivo_id, $empresa_categoria, 0, $empresa_nit, $empresa_adquiriente, $empresa_tipo_sociedad, $empresa_nombre_referencia, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_direccion_geo, $empresa_info_adicional, $accion_usuario, $accion_fecha);
        }
        
        // Establecimiento/Sucursal
        if($empresa_categoria == 2)
        {
            // PASO 1: Se realiza las validaciones pertinenetes
            
            // Verifica la integridad de los datos, que no esten vacios y del tipo correcto
            if((int)($empresa_depende) == 0 || $empresa_nombre_establecimiento == '' || $empresa_denominacion_corta == '')
            {
                $arrError =  array(
                                "error" => true,
                                "errorMessage" => $this->lang->line('CamposObligatorios'),
                                "errorCode" => 96,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                        );

                $this->response($arrError, 200);
                exit();
            }
            
            // PASO 2: Insertar en la tabla "empresa" (dependiendo si es comercio o establecimiento/sucursal
            $arrResultado1 = $this->mfunciones_logica->InsertarProspecto_establecimientoAPP($ejecutivo_id, $empresa_categoria, $empresa_depende, $empresa_nit, $empresa_adquiriente, $empresa_tipo_sociedad, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_nombre_referencia, $empresa_nombre_establecimiento, $empresa_denominacion_corta, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_direccion_geo, $empresa_info_adicional, $accion_usuario, $accion_fecha);
        
            // Si es un establecimiento/sucursal el tipo de persona debería heredarlo del comercio, por defecto se colocará 0 
            $tipo_persona = 0;
            
        }
        
        // PASO 3: Se captura el ID del registro recíen insertado en la tabla "empresa"
        $codigo_empresa = $arrResultado1;
				
	// PASO 4: Insertar en la tabla "prospecto"
            $arrResultado2 = $this->mfunciones_logica->InsertarProspecto_APP($ejecutivo_id, $tipo_persona, $codigo_empresa, $accion_fecha, $accion_usuario, $accion_fecha);
        
        // PASO 5: Se captura el ID del registro recíen insertado en la tabla "prospecto"
        $codigo_prospecto = $arrResultado2;
        
        // PASO 6: Se crea la carpeta del prospecto
        
        $path = RUTA_PROSPECTOS . 'afn_' . $codigo_prospecto;

        if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
        {
                mkdir($path, 0755, TRUE);
        }
        
            // Se crea el archivo html para evitar ataques de directorio
            $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
            write_file($path . '/index.html', $cuerpo_html);
		
        // PASO 7: Se registra la fecha en el calendario del Ejecutivo de Cuentas
                
        $cal_visita_ini = $this->mfunciones_generales->getFormatoFechaDateTime($cal_visita_ini);
        $cal_visita_fin = $this->mfunciones_generales->getFormatoFechaDateTime($cal_visita_fin);
        
        $this->mfunciones_logica->InsertarFechaCaendario($ejecutivo_id, $codigo_prospecto, 1, $cal_visita_ini, $cal_visita_fin, $accion_usuario, $accion_fecha);
        
        // PASO 8: Insertar los servicios seleccionados del prospecto
            // (se elimina todos los servicios del prospecto para volver a insertarlos)
            $this->mfunciones_logica->EliminarServiciosProspecto($codigo_prospecto);
        
        if (isset($arrServicios[0])) 
        {
            foreach ($arrServicios as $key => $value) 
            {
                $this->mfunciones_logica->InsertarServiciosProspecto($codigo_prospecto, $value["servicio_id"], $accion_usuario, $accion_fecha);
            }
        }
        
        // Insertar Seguimiento
        
        if($empresa_categoria == 2)
        {
            /**** REGISTRAR SEGUIMIENTO, ETAPA E HITO ****/        
            $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 1, 0, $accion_usuario, $accion_fecha);
        }
        else
        {
            /**** REGISTRAR SEGUIMIENTO, ETAPA E HITO ****/        
            $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 11, 0, $accion_usuario, $accion_fecha);
        }
        
        
        $texto = "";
        
        if($empresa_categoria == 1)
        {
            $texto = "Comercio";
        }
        
        if($empresa_categoria == 2)
        {
            $texto = "Establecimiento/Sucursal";
        }
        
        $lst_resultado[0] = array(
			"mensaje" => "El Prospecto (" . $texto .") se registró correctamente.",
			"codigo_prospecto" => $codigo_prospecto,
			"codigo_empresa" => $codigo_empresa,
			"tipo_prospecto" => $empresa_categoria
        );
        
        // Se guarda el Log para la auditoría        
        $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), $geolocalizacion, $accion_usuario, $accion_fecha);
        
        return $lst_resultado;

    }
    
    /* Descipción: Actualizar un Prospecto */
    function ActualizarProspectoAPP($arrDatos, $usuarioAPP, $nombre_servicio){

        $parametros = array(
                "ubicacion_geo", "codigo_prospecto", "codigo_ejecutivo", "empresa_categoria", "codigo_empresa",
                "empresa_nombre_referencia", "tipo_persona", 
                "empresa_tipo_sociedad", 
                "empresa_rubro", "empresa_perfil_comercial", "empresa_mcc", 
                "empresa_nombre_legal", "empresa_nombre_fantasia", "empresa_nombre_establecimiento", "empresa_denominacion_corta",
                "empresa_ha_desde", "empresa_ha_hasta", "empresa_dias_atencion", 
                "empresa_medio_contacto", "empresa_dato_contacto", "empresa_email", 
                "empresa_departamento", "empresa_municipio", "empresa_zona", 
                "empresa_tipo_calle", "empresa_calle", "empresa_numero", 
                "empresa_direccion_literal", "empresa_direccion_geo", "empresa_info_adicional",
                "fecha_visita_ini", "fecha_visita_fin", "arrServicios"
                );
        
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            $arrResultado = array();
            return $arrResultado;
        }
        
        $geolocalizacion = $arrDatos['ubicacion_geo'];
        
        $prospecto_id = $arrDatos['codigo_prospecto'];
        
        // *** Obtener Código y Tipo Persona ***
            $aux_get_codigo = $this->getCodigo($prospecto_id);

            if($aux_get_codigo->codigo == 0)
            {
                $arrError = array(
                    "error" => true,
                    "errorMessage" => 'Ocurrió un error al captura el código identificador (' . $prospecto_id . ')',
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => 'Ocurrió un error al captura el código identificador (' . $prospecto_id . ')'
                    )
                );

                $this->response($arrError, 200);
                exit();
            }

            $prospecto_id = $aux_get_codigo->codigo;
            $codigo_tipo_persona = $aux_get_codigo->tipo_persona;
        // *** Obtener Código y Tipo Persona ***
        
        $empresa_id = $arrDatos['codigo_empresa'];
        
        $ejecutivo_id = $arrDatos['codigo_ejecutivo'];
        $empresa_categoria = $arrDatos['empresa_categoria'];
        $empresa_nombre_referencia = $arrDatos['empresa_nombre_referencia'];
        $tipo_persona = $arrDatos['tipo_persona'];
        $empresa_tipo_sociedad = $arrDatos['empresa_tipo_sociedad'];
        $empresa_rubro = $arrDatos['empresa_rubro'];
        $empresa_perfil_comercial = $arrDatos['empresa_perfil_comercial'];
        $empresa_mcc = $arrDatos['empresa_mcc'];
        $empresa_nombre_legal = $arrDatos['empresa_nombre_legal'];
        $empresa_nombre_fantasia = $arrDatos['empresa_nombre_fantasia'];
        $empresa_nombre_establecimiento = $arrDatos['empresa_nombre_establecimiento'];
        $empresa_denominacion_corta = $arrDatos['empresa_denominacion_corta'];  
        
        // Si Todo bien... se captura los datos y se procesa la información

        $accion_usuario = $usuarioAPP;
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Datos
        
        $empresa_ha_desde = $arrDatos['empresa_ha_desde'];
        $empresa_ha_hasta = $arrDatos['empresa_ha_hasta'];
        $empresa_dias_atencion = $arrDatos['empresa_dias_atencion'];
        $empresa_medio_contacto = $arrDatos['empresa_medio_contacto'];        
        $empresa_dato_contacto = $arrDatos['empresa_dato_contacto'];
        $empresa_email = $arrDatos['empresa_email'];
        $empresa_departamento = $arrDatos['empresa_departamento'];
        $empresa_municipio = $arrDatos['empresa_municipio'];
        $empresa_zona = $arrDatos['empresa_zona'];
        $empresa_tipo_calle = $arrDatos['empresa_tipo_calle'];
        $empresa_calle = $arrDatos['empresa_calle'];
        $empresa_numero = $arrDatos['empresa_numero'];
        $empresa_direccion_literal = $arrDatos['empresa_direccion_literal'];
        $empresa_direccion_geo = $arrDatos['empresa_direccion_geo'];
        $empresa_info_adicional = $arrDatos['empresa_info_adicional'];
        
        $cal_visita_ini = $arrDatos['fecha_visita_ini'];
        $cal_visita_fin = $arrDatos['fecha_visita_fin'];
        
        $arrServicios = $arrDatos['arrServicios'];
        
        // PASO 1: Se realiza las validaciones pertinenetes (LA VALIDACIÓN PARA LA APP ES PARA TODOS LOS CAMPOS, EN LA WEB PUEDE VARIAR)

        if($empresa_categoria == 1)
        {
            if((int)($tipo_persona) == 0)
            {
                $arrError =  array(
                                "error" => true,
                                "errorMessage" => $this->lang->line('CamposObligatorios'),
                                "errorCode" => 96,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                        );

                $this->response($arrError, 200);
                exit();
            }
        }
        
        if($empresa_categoria == 2)
        {
            if($empresa_nombre_establecimiento == '' || $empresa_denominacion_corta == '')
            {
                $arrError =  array(
                                "error" => true,
                                "errorMessage" => $this->lang->line('CamposObligatorios'),
                                "errorCode" => 96,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                        );

                $this->response($arrError, 200);
                exit();
            }
            
            // Si es un establecimiento/sucursal el tipo de persona debería heredarlo del comercio, por defecto se colocará 0 
            $tipo_persona = 0;
        }
        
        if((int)($prospecto_id) == 0 || (int)($empresa_id) == 0 || (int)($ejecutivo_id) == 0 || (int)($empresa_categoria) == 0 ||
                $this->mfunciones_generales->VerificaFechaH_M($empresa_ha_desde) == false || $this->mfunciones_generales->VerificaFechaH_M($empresa_ha_hasta) == false || $empresa_dias_atencion == '' ||
                (int)($empresa_medio_contacto) == 0 || $empresa_dato_contacto == '' || $this->mfunciones_generales->VerificaCorreo($empresa_email) == false || 
                (int)($empresa_departamento) == 0 || (int)($empresa_municipio) == 0 || (int)($empresa_zona) == 0 ||
                (int)($empresa_tipo_calle) == 0 || $empresa_calle == '' || (int)($empresa_numero) == 0 || 
                $empresa_direccion_literal == '' || $empresa_direccion_geo == '' || 
                (int)($ejecutivo_id) == 0 || (int)($empresa_tipo_sociedad) == 0 || 
                (int)($empresa_rubro) == 0 || (int)($empresa_perfil_comercial) == 0 || (int)($empresa_mcc) == 0 || 
                $empresa_nombre_legal == '' || $empresa_nombre_fantasia == '' || $this->mfunciones_generales->VerificaFechaD_M_Y_H_M($cal_visita_ini) == false || $this->mfunciones_generales->VerificaFechaD_M_Y_H_M($cal_visita_fin) == false)
        {
            $arrError =  array(
                            "error" => true,
                            "errorMessage" => $this->lang->line('CamposObligatorios'),
                            "errorCode" => 96,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
                    );

            $this->response($arrError, 200);
            exit();
        }
        
        // Se verifica la estructura del Array de Servicios
        if (isset($arrServicios[0])) 
        {
            $parametros_servicios = "servicio_id";
            
            foreach ($arrServicios as $key => $value) 
            {
                if(!isset($value[$parametros_servicios]))
                {
                    $arrError =  array(
                                    "error" => true,
                                    "errorMessage" => $this->lang->line('CamposObligatorios'),
                                    "errorCode" => 96,
                                    "result" => array(
                                        "mensaje" => $this->lang->line('IncompletoApp')
                                    )
                            );

                    $this->response($arrError, 200);
                    exit();
                }
            }
        }
        
        // Se verifica que el prospecto No es este Consolidado. No puede actualizarse si esta consolidado

        // Detalle del Prospecto
        $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($prospecto_id);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
		
        if (isset($arrResultado3[0])) 
        {
            if($arrResultado3[0]['prospecto_consolidado'] == 1)
            {
                $arrError =  array(
                                "error" => true,
                                "errorMessage" => $this->lang->line('FormularioYaConsolidado'),
                                "errorCode" => 89,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                );

                $this->response($arrError, 200);
                exit();
            }			
        } 
        else 
        {
            $arrResultado = array();
			return $arrResultado;
        }
		
        // - FIN VALIDACIÓN
        
        $cal_visita_ini = $this->mfunciones_generales->getFormatoFechaDateTime($cal_visita_ini);
        $cal_visita_fin = $this->mfunciones_generales->getFormatoFechaDateTime($cal_visita_fin);        
        
        // PASO 2: Se procede a Actualizar el Prospecto y los servicios
        $this->mfunciones_logica->ActualizarProspecto($empresa_tipo_sociedad, $empresa_nombre_referencia, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_nombre_establecimiento, $empresa_denominacion_corta, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_direccion_geo, $empresa_info_adicional, $accion_usuario, $accion_fecha, $prospecto_id, $empresa_id, $ejecutivo_id, $tipo_persona, $cal_visita_ini, $cal_visita_fin);
                
        // Insertar los servicios seleccionados del prospecto
            // (se elimina todos los servicios del prospecto para volver a insertarlos)
            $this->mfunciones_logica->EliminarServiciosProspecto($prospecto_id);

        if (isset($arrServicios[0])) 
        {            
            foreach ($arrServicios as $key => $value) 
            {
                $this->mfunciones_logica->InsertarServiciosProspecto($prospecto_id, $value["servicio_id"], $accion_usuario, $accion_fecha);
            }
        }
        
        $texto = "";
        
        if($empresa_categoria == 1)
        {
            $texto = "Comercio";
        }
        
        if($empresa_categoria == 2)
        {
            $texto = "Establecimiento/Sucursal";
        }
        
        $lst_resultado[0] = array(
			"mensaje" => "El Prospecto (" . $texto .") se actualizó correctamente.",
			"codigo_prospecto" => $prospecto_id,
			"codigo_empresa" => $empresa_id,
			"tipo_prospecto" => $empresa_categoria
        );
        
        // Se guarda el Log para la auditoría        
        $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), $geolocalizacion, $accion_usuario, $accion_fecha);
        
        return $lst_resultado;

    }
    
    /* Descipción: Listado Documentos que pueden ser enviados por correo al cliente dependiendo de el Tipo de Persona al que pertenece */
    function ListadoDocumentosEnviar($arrDatos){
        
        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_prospecto"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrResultado = array();
                return $arrResultado;
        }

        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_prospecto = $arrDatos['codigo_prospecto'];
        
        // *** Obtener Código y Tipo Persona ***
            $aux_get_codigo = $this->getCodigo($codigo_prospecto);

            if($aux_get_codigo->codigo == 0)
            {
                $arrError = array(
                    "error" => true,
                    "errorMessage" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')',
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')'
                    )
                );

                $this->response($arrError, 200);
                exit();
            }

            $codigo_prospecto = $aux_get_codigo->codigo;
            $codigo_tipo_persona = $aux_get_codigo->tipo_persona;
        // *** Obtener Código y Tipo Persona ***
            
        switch ((int)$codigo_tipo_persona) {
            
            // Solicitud de Crédito y  Normalizador/Cobrador
            case 6:
            case 13:

                // Listado de los Documentos
                $arrResultado1 = $this->mfunciones_microcreditos->SolObtenerDocumentosEnviarApp($codigo_tipo_persona);
                
                break;

            default:
                
                // Listado de los Documentos
                $arrResultado1 = $this->mfunciones_logica->ObtenerDocumentosEnviarApp($codigo_prospecto);
                
                break;
        }
        
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
				
	if (isset($arrResultado1[0])) 
        {
            $i = 0;
                        
            foreach ($arrResultado1 as $key => $value) 
            {
                $item_valor = array(
                    "documento_id" => $value["documento_id"],
                    "documento_detalle" => $value["documento_nombre"]
                );
                $lst_resultado1[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado1[0] = $arrResultado1;
        }

        return $lst_resultado1;

    }
    
    /* Descipción: Envío de los documentos indicados, al correo al cliente */
    function EnviarDocumentos($arrDatos){

        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_prospecto", "codigo_empresa", "arrDocumentos"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrResultado = array();
                return $arrResultado;
        }

        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_prospecto = $arrDatos['codigo_prospecto'];
        $codigo_empresa = $arrDatos['codigo_empresa'];
        $arrDocumentos = $arrDatos['arrDocumentos'];

        // *** Obtener Código y Tipo Persona ***
            $aux_get_codigo = $this->getCodigo($codigo_prospecto);

            if($aux_get_codigo->codigo == 0)
            {
                $arrError = array(
                    "error" => true,
                    "errorMessage" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')',
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')'
                    )
                );

                $this->response($arrError, 200);
                exit();
            }

            $codigo_prospecto = $aux_get_codigo->codigo;
            $codigo_tipo_persona = $aux_get_codigo->tipo_persona;
        // *** Obtener Código y Tipo Persona ***
        
        // 1. BUSCAR LA INFORMACIÓN DE LA EMPRESA
        
        switch ((int)$codigo_tipo_persona) {
            
            // Solicitud de Crédito
            case 6:
                
                $arrResultado1 = $this->mfunciones_microcreditos->ObtenerDetalleSolicitudCredito($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                if (isset($arrResultado1[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado1 as $key => $value) 
                    {
                        $item_valor = array(
                            "empresa_id" => $value["sol_id"],
                            "empresa_email" => $value["sol_correo"],
                            "empresa_nombre_referencia" => $value["sol_primer_nombre"] . ' ' . $value["sol_primer_apellido"],
                            "empresa_nombre" => $value["sol_primer_nombre"] . ' ' . $value["sol_primer_apellido"],
                            "empresa_categoria" => 1,
                            "ejecutivo_asignado_nombre" => $value["ejecutivo_asignado_nombre"],
                            "ejecutivo_asignado_contacto" => $value["ejecutivo_asignado_contacto"]
                        );
                        $lst_resultado1[$i] = $item_valor;

                        $i++;
                    }
                }
                else 
                {
                    // En el caso que no se encuentre información con los parámetros indicados, se devuelve vacío            
                    return $arrResultado1;
                    exit();
                }

                break;

            // Normalizador/Cobrador
                
            case 13:
                
                $arrResultado1 = $this->mfunciones_cobranzas->ObtenerDetalleRegistro($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                if (isset($arrResultado1[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado1 as $key => $value) 
                    {
                        $item_valor = array(
                            "empresa_id" => $value["norm_id"],
                            "empresa_email" => $value["agente_correo"],
                            "empresa_nombre_referencia" => $value["agente_nombre"],
                            "empresa_nombre" => $value["agente_nombre"],
                            "empresa_categoria" => 1,
                            "ejecutivo_asignado_nombre" => $value["agente_nombre"],
                            "ejecutivo_asignado_contacto" => $value["agente_correo"]
                        );
                        $lst_resultado1[$i] = $item_valor;

                        $i++;
                    }
                }
                else 
                {
                    // En el caso que no se encuentre información con los parámetros indicados, se devuelve vacío            
                    return $arrResultado1;
                    exit();
                }
                
                break;
                
            default:
                
                // Listado Detalle Empresa
                $arrResultado1 = $this->mfunciones_logica->ObtenerDetalleEmpresaCorreo($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                if (isset($arrResultado1[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado1 as $key => $value) 
                    {
                        $item_valor = array(
                            "empresa_id" => $value["empresa_id"],
                            "empresa_email" => $value["general_email"],
                            "empresa_nombre_referencia" => $value["general_solicitante"],
                            "empresa_nombre" => $value["empresa_nombre"],
                            "empresa_categoria" => $value["empresa_categoria"],
                            "ejecutivo_asignado_nombre" => $value["ejecutivo_asignado_nombre"],
                            "ejecutivo_asignado_contacto" => $value["ejecutivo_asignado_contacto"]
                        );
                        $lst_resultado1[$i] = $item_valor;

                        $i++;
                    }
                }
                else 
                {
                    // En el caso que no se encuentre información con los parámetros indicados, se devuelve vacío            
                    return $arrResultado1;
                    exit();
                }
                
                break;
        }
            
                

        // 2. SI SE ENCONTRÓ LA INFORMACIÓN DE LA EMPRESA, SE PREGUNTA SI EL ARRAY DE DOCUMENTOS CONTIENE DOCUMENTOS SELECCIONADOS 

        // En el caso que el array este vacio se muestra el mensaje de error
        if (!isset($arrDocumentos[0])) 
        {
            $arrError =  array(
                            "error" => true,
                            "errorMessage" => $this->lang->line('FormularioSinOpciones'),
                            "errorCode" => 98,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
                    );

            $this->response($arrError, 200);
            exit();
        }
        
        // Se verifica la estructura del Array de Servicios
        if (isset($arrDocumentos[0])) 
        {
            $parametros_buscar = "documento_id";
            
            foreach ($arrDocumentos as $key => $value) 
            {
                if(!isset($value[$parametros_buscar]))
                {
                    $arrError =  array(
                                    "error" => true,
                                    "errorMessage" => $this->lang->line('CamposObligatorios'),
                                    "errorCode" => 96,
                                    "result" => array(
                                        "mensaje" => $this->lang->line('IncompletoApp')
                                    )
                            );

                    $this->response($arrError, 200);
                    exit();
                }
            }
        }
        
        // - FIN VALIDACIÓN        
        
        // 3. SE PROCEDE CON EL ENVÍO DE DOCUMENTOS AL CORREO ELECTRÓNICO

        $correo_enviado = $this->mfunciones_generales->EnviarCorreo('enviar_documentos_app', $lst_resultado1[0]['empresa_email'], $lst_resultado1[0]['empresa_nombre_referencia'], $lst_resultado1, $arrDocumentos);

        if($correo_enviado)
        {
            $lst_resultado[0] = array(
                            "mensaje" => "Se enviaron los documentos seleccionados al cliente correctamente."
            );
        }
        else
        {
            $arrError =  array(
                            "error" => true,
                            "errorMessage" => $this->lang->line('FormularioNoEnvio'),
                            "errorCode" => 88,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        return $lst_resultado;
    }
    
    /* Descipción: Listado Documentos que pueden ser digitalizados por el Ejecutivo de Cuentas, dependiendo de el Tipo de Persona al que pertenece. Y cuales ya estan digitalizados */
    function ListadoDocumentosDigitalizar($arrDatos){
        
        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_prospecto"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            $arrResultado = array();
            return $arrResultado;
        }

        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_prospecto = $arrDatos['codigo_prospecto'];
        
        // *** Obtener Código y Tipo Persona ***
            $aux_get_codigo = $this->getCodigo($codigo_prospecto);

            if($aux_get_codigo->codigo == 0)
            {
                $arrError = array(
                    "error" => true,
                    "errorMessage" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')',
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')'
                    )
                );

                $this->response($arrError, 200);
                exit();
            }

            $codigo_prospecto = $aux_get_codigo->codigo;
            $codigo_tipo_persona = $aux_get_codigo->tipo_persona;
        // *** Obtener Código y Tipo Persona ***
        
        switch ((int)$codigo_tipo_persona) {
            
            // Solicitud de Crédito
            case 6:
                
                // Listado de los Documentos
                
                // << ============
                
                // Configuration Aux    0=Off       1=On
                $config_allow_upload_croquis = 0;
                
                // ============ >>
                
                if($config_allow_upload_croquis == 1)
                {
                    $arrayCroquis[] = array(
                        "documento_id" => "9991",
                        "documento_detalle" => "(Opcional) Cargar Croquis Actividad",
                        "documento_digitalizado" => false
                        );
                    $arrayCroquis[] = array(
                        "documento_id" => "9992",
                        "documento_detalle" => "(Opcional) Cargar Croquis Domicilio",
                        "documento_digitalizado" => false
                    );
                    $arrayCroquis[] = array(
                        "documento_id" => "9993",
                        "documento_detalle" => "(Opcional) Cargar Croquis Actividad Cónyuge",
                        "documento_digitalizado" => false
                    );
                    $arrayCroquis[] = array(
                        "documento_id" => "9994",
                        "documento_detalle" => "(Opcional) Cargar Croquis Domicilio Cónyuge",
                        "documento_digitalizado" => false
                    );
                }
                
                $arrResultado1 = $this->mfunciones_microcreditos->SolObtenerDocumentosDigitalizarApp($codigo_tipo_persona);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                if (isset($arrResultado1[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado1 as $key => $value) 
                    {
                        $item_valor = array(
                            "documento_id" => $value["documento_id"],
                            "documento_detalle" => $value["documento_nombre"],
                            "documento_digitalizado" => $this->mfunciones_microcreditos->GetInfoSolicitudDigitalizadoPDF($codigo_prospecto, $value["documento_id"], 'existe')
                        );
                        $lst_resultado1[$i] = $item_valor;

                        $i++;
                    }
                    
                    if($config_allow_upload_croquis == 1)
                    {
                        $lst_resultado1 = array_merge_recursive($lst_resultado1, $arrayCroquis);
                    }
                } 
                else 
                {
                    if($config_allow_upload_croquis == 1)
                    {
                        $lst_resultado1 = $arrayCroquis;
                    }
                    else
                    {
                        $lst_resultado1[0] = array();
                    }
                }
                
                break;

            // Normalizador/Cobrador
            case 13:
                
                $arrResultado1 = $this->mfunciones_microcreditos->SolObtenerDocumentosDigitalizarApp($codigo_tipo_persona);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                if (isset($arrResultado1[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado1 as $key => $value) 
                    {
                        $item_valor = array(
                            "documento_id" => $value["documento_id"],
                            "documento_detalle" => $value["documento_nombre"],
                            "documento_digitalizado" => $this->mfunciones_cobranzas->GetInfoRegistroDigitalizadoPDF($codigo_prospecto, $value["documento_id"], 'existe')
                        );
                        $lst_resultado1[$i] = $item_valor;

                        $i++;
                    }
                } 
                else 
                {
                    $lst_resultado1[0] = $arrResultado1;
                }
                
                break;
                
            default:
                
                // Listado de los Documentos
                $arrResultado1 = $this->mfunciones_logica->ObtenerDocumentosDigitalizarApp($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                if (isset($arrResultado1[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado1 as $key => $value) 
                    {
                        $item_valor = array(
                            "documento_id" => $value["documento_id"],
                            "documento_detalle" => $value["documento_nombre"],
                            "documento_digitalizado" => $this->mfunciones_generales->GetInfoDigitalizado($codigo_prospecto, $value["documento_id"], 'existe')
                        );
                        $lst_resultado1[$i] = $item_valor;

                        $i++;
                    }
                } 
                else 
                {
                    $lst_resultado1[0] = $arrResultado1;
                }
                
                break;
        }
        
        return $lst_resultado1;
    }
    
	/* Descipción: Obtiene el Documento PDF digitalizado previamente codificado en base64 */
    function DocumentoProspectoPDF($arrDatos){
        
        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_prospecto", "codigo_documento"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrResultado = array();
                return $arrResultado;
        }
		
        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_prospecto = $arrDatos['codigo_prospecto'];
        $codigo_documento = $arrDatos['codigo_documento'];

        // *** Obtener Código y Tipo Persona ***
            $aux_get_codigo = $this->getCodigo($codigo_prospecto);

            if($aux_get_codigo->codigo == 0)
            {
                $arrError = array(
                    "error" => true,
                    "errorMessage" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')',
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')'
                    )
                );

                $this->response($arrError, 200);
                exit();
            }

            $codigo_prospecto = $aux_get_codigo->codigo;
            $codigo_tipo_persona = $aux_get_codigo->tipo_persona;
        // *** Obtener Código y Tipo Persona ***
        
        switch ((int)$codigo_tipo_persona) {
            
            // Solicitud de Crédito
            case 6:
                
                $documento_base64 = $this->mfunciones_microcreditos->GetInfoSolicitudDigitalizadoPDF($codigo_prospecto, $codigo_documento, 'documento');
                
                break;

            // Normalizador/Cobrador
            
            case 13:
                
                $documento_base64 = $this->mfunciones_cobranzas->GetInfoRegistroDigitalizadoPDF($codigo_prospecto, $codigo_documento, 'documento');
                
                break;
            
            default:
                
                $documento_base64 = $this->mfunciones_generales->GetInfoDigitalizado($codigo_prospecto, $codigo_documento, 'documento');
                
                break;
        }

        if($documento_base64)
        {
            $lst_resultado[0] = array(
                    "documento_pdf_base64" => $documento_base64
            );
        }
        else
        {
            $arrError =  array(
                            "error" => true,
                            "errorMessage" => $this->lang->line('FormularioDocumentoError'),
                            "errorCode" => 98,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
            );

            $this->response($arrError, 200);
            exit();
        }

        return $lst_resultado;
    }
	
    /* Descipción: Se recibe el PDF codificado en base64 para guardarlo en la DB y en el directorio correspondiente */
    function InsertarProspectoPDF($arrDatos, $usuarioAPP, $nombre_servicio){
        		
        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "ubicacion_geo", "codigo_prospecto", "codigo_documento", "documento_pdf_base64"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrError =  array(
                    "error" => true,
                    "errorMessage" => $this->lang->line('CamposObligatorios'),
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => $this->lang->line('IncompletoApp')
                    )
                );

                $this->response($arrError, 200);
                exit();
        }
		
        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_prospecto = $arrDatos['codigo_prospecto'];
        $codigo_documento = $arrDatos['codigo_documento'];
        $documento_pdf_base64 = $arrDatos['documento_pdf_base64'];
	
        // *** Obtener Código y Tipo Persona ***
            $aux_get_codigo = $this->getCodigo($codigo_prospecto);

            if($aux_get_codigo->codigo == 0)
            {
                $arrError = array(
                    "error" => true,
                    "errorMessage" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')',
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')'
                    )
                );

                $this->response($arrError, 200);
                exit();
            }

            $codigo_prospecto = $aux_get_codigo->codigo;
            $codigo_tipo_persona = $aux_get_codigo->tipo_persona;
        // *** Obtener Código y Tipo Persona ***
        
        //Auditoría
        $geolocalizacion = $arrDatos['ubicacion_geo'];
        
        // PASO 1: Se realiza las validaciones pertinenetes (LA VALIDACIÓN PARA LA APP ES PARA TODOS LOS CAMPOS, EN LA WEB PUEDE VARIAR)                
        if((int)($codigo_prospecto) == 0 || (int)($codigo_documento) == 0 || $documento_pdf_base64 == '' || $geolocalizacion == '')
        {
            $arrError =  array(
                "error" => true,
                "errorMessage" => $this->lang->line('CamposObligatorios'),
                "errorCode" => 96,
                "result" => array(
                    "mensaje" => $this->lang->line('IncompletoApp')
                )
            );

            $this->response($arrError, 200);
            exit();
        }

        $accion_usuario = $usuarioAPP;
        $accion_fecha = date('Y-m-d H:i:s');
        
        $arrError_consolidado =  array(
            "error" => true,
            "errorMessage" => $this->lang->line('FormularioYaConsolidado'),
            "errorCode" => 89,
            "result" => array(
                "mensaje" => $this->lang->line('IncompletoApp')
            )
        );
        
        $arrError_doc_error =  array(
            "error" => true,
            "errorMessage" => $this->lang->line('FormularioDocumentoError'),
            "errorCode" => 98,
            "result" => array(
                "mensaje" => $this->lang->line('IncompletoApp')
            )
        );
        
        // Se identifica el Tipo de Persona al que está asociado el codigo_documento
        
        switch ((int)$codigo_tipo_persona) {
            
            // Solicitud de Crédito
            case 6:

                if((int)$codigo_documento > 9990)
                {
                    switch ((int)$codigo_documento) {
                        case 9991:
                            $aux_campo = 'sol_croquis';
                            $aux_ref = 'sol_dir_referencia';
                            $aux_texto = 'Croquis Actividad del Solicitante';
                            break;
                        
                        case 9992:
                            $aux_campo = 'sol_croquis_dom';
                            $aux_ref = 'sol_dir_referencia_dom';
                            $aux_texto = 'Croquis Domicilio del Solicitante';
                            break;
                        
                        case 9993:
                            $aux_campo = 'sol_con_croquis';
                            $aux_ref = 'sol_con_dir_referencia';
                            $aux_texto = 'Croquis Actividad del Cónyuge';
                            break;
                        
                        case 9994:
                            $aux_campo = 'sol_con_croquis_dom';
                            $aux_ref = 'sol_con_dir_referencia_dom';
                            $aux_texto = 'Croquis Domicilio del Cónyuge';
                            break;

                        default:
                            
                            // Muestra un mesaje de error
                            $arrError =  array(		
                                    "error" => true,
                                    "errorMessage" => "Documento Inválido",
                                    "errorCode" => 85,
                                    "result" => array(
                                        "mensaje" => $this->lang->line('IncompletoApp')
                                    )
                            );

                            $this->response($arrError, 200);
                            exit();
                            
                            break;
                    }
                    
                    $resultado_croquis = $this->mfunciones_microcreditos->AuxPDF_croquis($codigo_prospecto, $aux_campo, $aux_ref, $documento_pdf_base64);
                    
                    if($resultado_croquis)
                    {
                        $arrRespAux =  array(
                                "error" => true,
                                "errorMessage" => "¡Guardado correcto! \"" . $aux_texto . "\" registrado y asociado automáticamente al registro. Puede verificar el mismo en el registro.",
                                "errorCode" => 98,
                                "result" => array(
                                    "mensaje" => "Evento controlado"
                                )
                        );
                        $this->response($arrRespAux, 200);
                        exit();
                    }
                    else
                    {
                        $this->response($arrError_doc_error, 200);
                        exit();
                    }
                    
                        
                }

                // Detalle del Prospecto
                $arrResultado3 = $this->mfunciones_microcreditos->VerificaSolicitudConsolidado($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                if (isset($arrResultado3[0])) 
                {
                    if($arrResultado3[0]['sol_consolidado'] == 1)
                    {
                        $this->response($arrError_consolidado, 200);
                        exit();
                    }
                } 
                else 
                {
                    $arrResultado = array();
                    return $arrResultado;
                }
                
                // Paso 1: Se verifica si existe el directorio del Prospecto, caso contrario crear el directorio
                $path = RUTA_SOLCREDITOS . 'sol_' . $arrResultado3[0]['sol_id'];
                if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
                {
                    mkdir($path, 0755, TRUE);
                    // Se crea el archivo html para evitar ataques de directorio
                    $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                    write_file($path . '/index.html', $cuerpo_html);
                }

                // PASO 2: Se guarda el archivo PDF en el directorio correspondiente
                // Se verifica que el PDF se haya guardado correctamente, caso contrario indicar el error

                $sol_documento_pdf = $this->mfunciones_microcreditos->GuardarDocumentoSolicitudBase64PDF($arrResultado3[0]['sol_id'], $codigo_documento, $documento_pdf_base64);

                if($sol_documento_pdf == false)
                {
                    $this->response($arrError_doc_error, 200);
                    exit();
                }

                // PASO 3: Guardar en la DB

                $this->mfunciones_microcreditos->InsertarDocumentoSolicitud($arrResultado3[0]['sol_id'], $codigo_documento, $sol_documento_pdf, $accion_usuario, $accion_fecha);
                
                break;

            // Normalizador/Cobrador
            case 13:
                
                // Detalle del Registro
                $arrResultado3 = $this->mfunciones_cobranzas->VerificaNormConsolidado($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                if (isset($arrResultado3[0])) 
                {
                    if($arrResultado3[0]['norm_consolidado'] == 1)
                    {
                        $this->response($arrError_consolidado, 200);
                        exit();
                    }
                } 
                else 
                {
                    $arrResultado = array();
                    return $arrResultado;
                }
                
                // Paso 1: Se verifica si existe el directorio del Prospecto, caso contrario crear el directorio
                $path = $this->lang->line('ruta_cobranzas') . 'reg_' . $arrResultado3[0]['norm_id'];
                if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
                {
                    mkdir($path, 0755, TRUE);
                    // Se crea el archivo html para evitar ataques de directorio
                    $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                    write_file($path . '/index.html', $cuerpo_html);
                }

                // PASO 2: Se guarda el archivo PDF en el directorio correspondiente
                // Se verifica que el PDF se haya guardado correctamente, caso contrario indicar el error

                $sol_documento_pdf = $this->mfunciones_cobranzas->GuardarDocumentoRegistroBase64PDF($arrResultado3[0]['norm_id'], $codigo_documento, $documento_pdf_base64);

                if($sol_documento_pdf == false)
                {
                    $this->response($arrError_doc_error, 200);
                    exit();
                }

                // PASO 3: Guardar en la DB

                $this->mfunciones_cobranzas->InsertarDocumentoRegistro($arrResultado3[0]['norm_id'], $codigo_documento, $sol_documento_pdf, $accion_usuario, $accion_fecha, $codigo_tipo_persona);
                
                break;
                
            default:
                
                // Se verifica que el prospecto No es este Consolidado. No puede actualizarse si esta consolidado

                // Detalle del Prospecto
                $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                if (isset($arrResultado3[0])) 
                {
                    if($arrResultado3[0]['prospecto_consolidado'] == 1)
                    {
                        $this->response($arrError_consolidado, 200);
                        exit();
                    }
                } 
                else 
                {
                    $arrResultado = array();
                    return $arrResultado;
                }

                if($arrResultado3[0]['onboarding'] == 1)
                {
                    // Paso 1.1: Se verifica si existe el directorio del Prospecto, caso contrario crear el directorio
                    $path = RUTA_TERCEROS . 'ter_' . $arrResultado3[0]['onboarding_codigo'];
                    if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
                    {
                        mkdir($path, 0755, TRUE);
                        // Se crea el archivo html para evitar ataques de directorio
                        $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                        write_file($path . '/index.html', $cuerpo_html);
                    }

                    // PASO 2: Se guarda el archivo PDF en el directorio correspondiente
                    // Se verifica que el PDF se haya guardado correctamente, caso contrario indicar el error

                    $terceros_documento_pdf = $this->mfunciones_generales->GuardarDocumentoTercerosBase64PDF($arrResultado3[0]['onboarding_codigo'], $codigo_documento, $documento_pdf_base64);

                    if($terceros_documento_pdf == false)
                    {
                        $this->response($arrError_doc_error, 200);
                        exit();
                    }

                    // PASO 3: Guardar en la DB

                    $this->mfunciones_logica->InsertarDocumentoTercero($arrResultado3[0]['onboarding_codigo'], $codigo_documento, $terceros_documento_pdf, $accion_usuario, $accion_fecha);
                }

                // Paso 1.1: Se verifica si existe el directorio del Prospecto, caso contrario crear el directorio
                $path = RUTA_PROSPECTOS . 'afn_' . $codigo_prospecto;
                if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
                {
                    mkdir($path, 0755, TRUE);
                    // Se crea el archivo html para evitar ataques de directorio
                    $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                    write_file($path . '/index.html', $cuerpo_html);
                }

                // PASO 2: Se guarda el archivo PDF en el directorio correspondiente
                // Se verifica que el PDF se haya guardado correctamente, caso contrario indicar el error

                $prospecto_documento_pdf = $this->mfunciones_generales->GuardarDocumentoBase64($codigo_prospecto, $codigo_documento, $documento_pdf_base64);

                if($prospecto_documento_pdf == false)
                {
                    $this->response($arrError_doc_error, 200);
                    exit();
                }

                // PASO 3: Guardar en la DB

                $this->mfunciones_logica->InsertarDocumentoProspecto($codigo_prospecto, $codigo_documento, $prospecto_documento_pdf, $accion_usuario, $accion_fecha);

                break;
        }
        
        // Se guarda el Log para la auditoría        
        $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "{'codigo_prospecto': '$codigo_prospecto', 'codigo_documento': '$codigo_documento', 'prospecto_documento_pdf': '$prospecto_documento_pdf'}", $geolocalizacion, $accion_usuario, $accion_fecha);

        $lst_resultado[0] = array(
            "mensaje" => "El documento se guardo correctamente."
        );
		
        return $lst_resultado;
    }
	
    /* Descipción: Se cambia el estado del prospecto para remitirlo a Cumplimiento */
    function RemitirProspectoCumplimiento($arrDatos, $usuarioAPP, $nombre_servicio){
        		
        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "ubicacion_geo", "codigo_prospecto"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrResultado = array();
                return $arrResultado;
        }
        
        
        $arrError =  array(
                        "error" => true,
                        "errorMessage" => 'Funcionalidad no disponible en esta versión',
                        "errorCode" => 89,
                        "result" => array(
                            "mensaje" => $this->lang->line('IncompletoApp')
                        )
        );

        $this->response($arrError, 200);
        exit();
        
        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_prospecto = $arrDatos['codigo_prospecto'];

        // *** Obtener Código y Tipo Persona ***
            $aux_get_codigo = $this->getCodigo($codigo_prospecto);

            if($aux_get_codigo->codigo == 0)
            {
                $arrError = array(
                    "error" => true,
                    "errorMessage" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')',
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')'
                    )
                );

                $this->response($arrError, 200);
                exit();
            }

            $codigo_prospecto = $aux_get_codigo->codigo;
            $codigo_tipo_persona = $aux_get_codigo->tipo_persona;
        // *** Obtener Código y Tipo Persona ***
        
        //Auditoría
        $geolocalizacion = $arrDatos['ubicacion_geo'];
        
        // PASO 1: Se realiza las validaciones pertinenetes (LA VALIDACIÓN PARA LA APP ES PARA TODOS LOS CAMPOS, EN LA WEB PUEDE VARIAR)                
        if((int)($codigo_prospecto) == 0 || $geolocalizacion == '')
        {
            $arrError =  array(
                "error" => true,
                "errorMessage" => $this->lang->line('CamposObligatorios'),
                "errorCode" => 96,
                "result" => array(
                    "mensaje" => $this->lang->line('IncompletoApp')
                )
            );

            $this->response($arrError, 200);
            exit();
        }
		
        // Se verifica que el prospecto No es este Consolidado. No puede actualizarse si esta consolidado

        // Detalle del Prospecto
        $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($codigo_prospecto);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

        if (isset($arrResultado3[0])) 
        {
            if($arrResultado3[0]['prospecto_consolidado'] == 1)
            {
                    $arrError =  array(
                                    "error" => true,
                                    "errorMessage" => $this->lang->line('FormularioYaConsolidado'),
                                    "errorCode" => 89,
                                    "result" => array(
                                        "mensaje" => $this->lang->line('IncompletoApp')
                                    )
                    );

                    $this->response($arrError, 200);
                    exit();
            }
            
            if($arrResultado3[0]['tipo_persona_id'] == 0)
            {
                    $arrError =  array(
                                    "error" => true,
                                    "errorMessage" => $this->lang->line('FormularioNoRequiereCumplimiento'),
                                    "errorCode" => 84,
                                    "result" => array(
                                        "mensaje" => $this->lang->line('IncompletoApp')
                                    )
                    );

                    $this->response($arrError, 200);
                    exit();
            }
            
            if($arrResultado3[0]['prospecto_estado_actual'] > 0 && $arrResultado3[0]['prospecto_observado_app'] == 0)
            {
                $arrError =  array(
                                "error" => true,
                                "errorMessage" => $this->lang->line('FormularioYaEnviadoPre'),
                                "errorCode" => 85,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
            elseif($arrResultado3[0]['prospecto_etapa'] != 12 && $arrResultado3[0]['prospecto_etapa'] != 11)
            {
                $arrError =  array(
                                "error" => true,
                                "errorMessage" => $this->lang->line('FormularioYaEnviadoPre'),
                                "errorCode" => 85,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
        } 
        else 
        {
            $arrResultado = array();
            return $arrResultado;
        }
		
        // PASO 2: Se remite un correo electrónico de notificación al (los) usuario(s) de "Cumplimiento" Rol: 5
                
        if($this->mfunciones_generales->VerificaEtapaEnvio(12))
        {        
            $rol = ROL_CUMPLIMIENTO;

            $arrResultado4 = $this->mfunciones_logica->ObtenerDetalleDatosUsuario(2, $rol);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado4);

            if (isset($arrResultado4[0]))
            {
                foreach ($arrResultado4 as $key => $value) 
                {                        
                    $destinatario_nombre = $value['usuario_nombres'] . ' ' . $value['usuario_app'] . ' ' . $value['usuario_apm'];
                    $destinatario_correo = $value['usuario_email'];

                    // SE PROCEDE CON EL ENVÍO DE DOCUMENTOS AL CORREO ELECTRÓNICO				
                    $correo_enviado = $this->mfunciones_generales->EnviarCorreo('remitir_cumplimiento', $destinatario_correo, $destinatario_nombre, $codigo_prospecto, 0);

                    if(!$correo_enviado)
                    {
                        $arrError =  array(
                                        "error" => true,
                                        "errorMessage" => $this->lang->line('FormularioNoEnvio'),
                                        "errorCode" => 88,
                                        "result" => array(
                                            "mensaje" => $this->lang->line('IncompletoApp')
                                        )
                        );

                        $this->response($arrError, 200);
                        exit();
                    }                        
                }
            }
        }
        
        // PASO 3: Se cambia el estado del Prospecto para que sea visible por "Cumplimiento" Estado=2

        $accion_usuario = $usuarioAPP;
        $accion_fecha = date('Y-m-d H:i:s');

        $this->mfunciones_logica->ActualizarProspecto_EnviarCumplimiento($accion_usuario, $accion_fecha, $codigo_prospecto);

        // PASO 4: Se cambia el estado a las observaciones (si existiesen) del Prospecto => Estado=0                
        $this->mfunciones_logica->UpdateObservacionDoc(0, $accion_usuario, $accion_fecha, $codigo_prospecto);
        
        /**** REGISTRAR SEGUIMIENTO, ETAPA E HITO Y ENVIAR CORREO (último parámetro 1=Envio a etapas hijas    2=Envio a etapa específica ****/        
        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 12, 11, $accion_usuario, $accion_fecha, 1);

        /***  REGISTRAR SEGUIMIENTO ***/
        $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, 12, 1, 'Remitir Prospecto a Cumplimiento para su pre-revisión', $accion_usuario, $accion_fecha);

        // Se guarda el Log para la auditoría        
        $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), $geolocalizacion, $accion_usuario, $accion_fecha);

        $lst_resultado[0] = array(
                                "mensaje" => "El Prospecto fue remitido a cumplimiento para su revisión. Puede continuar con la visita."
        );
		
        return $lst_resultado;

    }
	
    /* Descipción: Una vez que este registrada toda la información del Prospecto así como digitalizada su documentación, el Ejecutivo de Cuentas puede "Consolidar" el Prospecto para que continúe con el flujo. No se podrá editar el prospecto una vez consolidado. */
    function ConsolidarProspecto($arrDatos, $usuarioAPP, $nombre_servicio, $codigo_usuario){
        		
        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "ubicacion_geo", "codigo_prospecto"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            $arrResultado = array();
            return $arrResultado;
        }
		
        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_prospecto = $arrDatos['codigo_prospecto'];

        // *** Obtener Código y Tipo Persona ***
            $aux_get_codigo = $this->getCodigo($codigo_prospecto);

            if($aux_get_codigo->codigo == 0)
            {
                $arrError = array(
                    "error" => true,
                    "errorMessage" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')',
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')'
                    )
                );

                $this->response($arrError, 200);
                exit();
            }

            $codigo_prospecto = $aux_get_codigo->codigo;
            $codigo_tipo_persona = $aux_get_codigo->tipo_persona;
        // *** Obtener Código y Tipo Persona ***
        
		//Auditoría
        $geolocalizacion = $arrDatos['ubicacion_geo'];
        
        // PASO 1: Se realiza las validaciones pertinenetes (LA VALIDACIÓN PARA LA APP ES PARA TODOS LOS CAMPOS, EN LA WEB PUEDE VARIAR)                
        if((int)($codigo_prospecto) == 0 || $geolocalizacion == '')
        {
                $arrError =  array(
                    "error" => true,
                    "errorMessage" => $this->lang->line('CamposObligatorios'),
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => $this->lang->line('IncompletoApp')
                    )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        $arrError_yaconsolidado =  array(
            "error" => true,
            "errorMessage" => $this->lang->line('FormularioYaConsolidado'),
            "errorCode" => 89,
            "result" => array(
                "mensaje" => $this->lang->line('IncompletoApp')
            )
        );
        
        $arrError_sinevaluacion =  array(
            "error" => true,
            "errorMessage" => $this->lang->line('ConsolidarSinEvaluacion'),
            "errorCode" => 89,
            "result" => array(
                "mensaje" => $this->lang->line('IncompletoApp')
            )
        );
        
        $arrError_sin_nro_operacion =  array(
            "error" => true,
            "errorMessage" => $this->lang->line('registro_num_proceso_error_app'),
            "errorCode" => 89,
            "result" => array(
                "mensaje" => $this->lang->line('IncompletoApp')
            )
        );
        
        $accion_usuario = $usuarioAPP;
        $accion_fecha = date('Y-m-d H:i:s');
        
        switch ((int)$codigo_tipo_persona) {
            
            // Solicitud de Crédito
            case 6:
                // Detalle del Prospecto
                $arrResultado3 = $this->mfunciones_microcreditos->VerificaSolicitudConsolidado($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                if (isset($arrResultado3[0])) 
                {
                    if($arrResultado3[0]['sol_consolidado'] == 1)
                    {
                        $this->response($arrError_yaconsolidado, 200);
                        exit();
                    }
                } 
                else 
                {
                    $arrResultado = array();
                    return $arrResultado;
                }
                
                if($arrResultado3[0]['sol_evaluacion'] == 0)
                {
                    $this->response($arrError_sinevaluacion, 200);
                    exit();
                }
                
                // Evaluación 1=Aprobado    2=Rechazado
                if($arrResultado3[0]['sol_evaluacion'] == 1)
                {
                    // SÓLO CUANDO LA EVALUACIÓN ES "APROBADO". Validar que el Número de Operación esté correctamente registrado
                    // SÓLO PARA LOS RUBROS QUE NO SE CONVIERTEN A ESTUDIO
                    if((int)$arrResultado3[0]['sol_codigo_rubro'] >= 7)
                    {
                        if($this->mfunciones_microcreditos->ValidarNumOperacion($arrResultado3[0]['sol_num_proceso']))
                        {
                            $this->response($arrError_sin_nro_operacion, 200);
                            exit();
                        }
                    }
                    
                    // Aprobado
                    $sol_consolidado = $this->mfunciones_microcreditos->ConsolidarSolicitud($geolocalizacion, $codigo_usuario, $accion_usuario, $accion_fecha, $codigo_prospecto);
                    
                    if($sol_consolidado == FALSE)
                    {
                        $arrError = array(
                            "error" => true,
                            "errorMessage" => $this->lang->line('sol_no_consolidado'),
                            "errorCode" => 96,
                            "result" => array(
                                "mensaje" => $this->lang->line('sol_no_consolidado')
                            )
                        );

                        $this->response($arrError, 200);
                        exit();
                    }
                }
                else
                {
                    // Rechazado
                    $this->mfunciones_microcreditos->RechazarSolicitudCreditoApp($codigo_usuario, $accion_usuario, $accion_fecha, $codigo_prospecto);
                }
                
                // Se cambia el estado a las observaciones (si existiesen) del registro => Estado=0
                $this->mfunciones_microcreditos->SolUpdateObservacionDoc(0, $accion_usuario, $accion_fecha, $codigo_prospecto, (int)$codigo_tipo_persona);
                
                // Notificar Proceso Onboarding Solicitud Crédito     0=No Regionalizado      1=Si Regionalizado
                $this->mfunciones_microcreditos->NotificacionEtapaCredito($codigo_prospecto, ($arrResultado3[0]['sol_evaluacion']==1 ? 20 : 21), 1);
                
                break;

            // Normalizador/Cobrador
            case 13:
                
                // Validaciones de las reglas de negocio: Sólo se asigna el CheckIn a la última visita registrada (sólo 1 vez)
                $check_visita = $this->mfunciones_cobranzas->checkVisitaRegistrada($codigo_prospecto, -1, 'check_consolidar', 'ORDER BY cv_id DESC');
                
                if($check_visita->error)
                {
                    $arrError = array(
                        "error" => true,
                        "errorMessage" => 'No se realizó la consolidación. ' . $check_visita->error_texto,
                        "errorCode" => 89,
                        "result" => array(
                            "mensaje" => $this->lang->line('IncompletoApp')
                        )
                    );

                    $this->response($arrError, 200);
                    exit();
                }
                
                // Consolidar
                $this->mfunciones_cobranzas->setConsolidarNorm($geolocalizacion, $codigo_usuario, $accion_usuario, $accion_fecha, $codigo_prospecto);
                
                // Se cambia el estado a las observaciones (si existiesen) del registro => Estado=0
                $this->mfunciones_cobranzas->RegUpdateObservacionDoc(0, $accion_usuario, $accion_fecha, $codigo_prospecto, (int)$codigo_tipo_persona);
                
                // Notificar Proceso Onboarding Normalizador/Cobrador     0=No Regionalizado      1=Si Regionalizado
                $this->mfunciones_cobranzas->NotificacionEtapaRegistro($codigo_prospecto, 26, 1);
                
                break;
                
            default:
                
                // Se verifica que el prospecto No es este Consolidado. No puede actualizarse si esta consolidado

                // Detalle del Prospecto
                $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($codigo_prospecto);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

                if (isset($arrResultado3[0])) 
                {
                    // Se capturan variables auxiliares
                    $existe_obs = $arrResultado3[0]['prospecto_observado_app'];
                    $etapa_actual = $arrResultado3[0]['prospecto_etapa'];

                    if($arrResultado3[0]['prospecto_evaluacion'] == 0)
                    {
                        $this->response($arrError_sinevaluacion, 200);
                        exit();
                    }

                    if($arrResultado3[0]['prospecto_consolidado'] == 1)
                    {
                        $this->response($arrError_yaconsolidado, 200);
                        exit();
                    }

                    if($arrResultado3[0]['prospecto_estado_actual'] == 0 && $arrResultado3[0]['tipo_persona_id'] != 0)
                    {
                        $arrError =  array(
                                        "error" => true,
                                        "errorMessage" => $this->lang->line('FormularioNoEnviadoPre'),
                                        "errorCode" => 86,
                                        "result" => array(
                                            "mensaje" => $this->lang->line('IncompletoApp')
                                        )
                        );

                        //$this->response($arrError, 200);
                        //exit();
                    }

                    if($arrResultado3[0]['prospecto_estado_actual'] != 3 && $arrResultado3[0]['tipo_persona_id'] != 0)
                    {
                        $arrError =  array(
                                        "error" => true,
                                        "errorMessage" => $this->lang->line('FormularioNoAprobadoPre'),
                                        "errorCode" => 87,
                                        "result" => array(
                                            "mensaje" => $this->lang->line('IncompletoApp')
                                        )
                        );

                        //$this->response($arrError, 200);
                        //exit();
                    }

                    if($arrResultado3[0]['onboarding'] == 1 && $arrResultado3[0]['prospecto_evaluacion'] == 1)
                    {
                        // Listado de los Documentos
                        $arrResultado1 = $this->mfunciones_logica->ObtenerDocumentosDigitalizarOnb($codigo_prospecto);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                        if (isset($arrResultado1[0]))
                        {
                            foreach ($arrResultado1 as $key => $value) 
                            {
                                if($this->mfunciones_generales->GetInfoDigitalizado($codigo_prospecto, $value["documento_id"], 'existe') == FALSE)
                                {
                                    $arrError =  array(
                                                    "error" => true,
                                                    "errorMessage" => 'No Consolidado: Debe digitalizar todos los elementos antes de Consolidar.',
                                                    "errorCode" => 98,
                                                    "result" => array(
                                                        "mensaje" => $this->lang->line('IncompletoApp')
                                                    )
                                    );

                                    $this->response($arrError, 200);
                                    exit();
                                }
                            }
                        }
                        else 
                        {
                            $arrResultado = array();
                            return $arrResultado;
                        }
                    }

                }
                else 
                {
                    $arrResultado = array();
                    return $arrResultado;
                }

                // SÓLO CUANDO ES ESTUDIO DE CRÉDITO Y NO ES ONBOARDING
                // SÓLO PARA LOS RUBROS QUE NO SE CONVIERTEN A ESTUDIO
                if($arrResultado3[0]['onboarding'] == 0)
                {
                    if($this->mfunciones_microcreditos->ValidarNumOperacion($arrResultado3[0]['prospecto_num_proceso']))
                    {
                        $this->response($arrError_sin_nro_operacion, 200);
                        exit();
                    }
                }
                
                // PASO 2: Se cambia el estado del Prospecto para que sea visible por "Cumplimiento" Estado=2
                
                $this->mfunciones_logica->ConsolidarProspecto($geolocalizacion, $accion_usuario, $accion_fecha, $codigo_prospecto);

                // PASO 3: Se cambia el estado a las observaciones (si existiesen) del Prospecto => Estado=0

                $this->mfunciones_logica->UpdateObservacionDoc(0, $accion_usuario, $accion_fecha, $codigo_prospecto);

                // PASO 4: Enviar Correo a la instancia de Supervisor de Agencia (O la que corresponda)

                    $texto_auxiliar = '';

                // SI SÓLO ESTA SUBSANANDO OBSERVACIONES, SE DERIVA (DEVUELVE) A LA ETAPA QUE OBSERVÓ

                if($existe_obs > 0)
                {            
                    // Caso Normal
                    $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 5, 3, $accion_usuario, $accion_fecha, 2);
                    /***  REGISTRAR SEGUIMIENTO ***/
                    $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, 5, 3, 'Subsanar Observación', $accion_usuario, $accion_fecha);
                }
                else
                {
                    // FLUJO NORMAL

                    // Verifica primero si es Onboarding

                    if($arrResultado3[0]['onboarding'] == 0)
                    {
                        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 5, $etapa_actual, $accion_usuario, $accion_fecha, 2);
                    }
                    else
                    {
                        $arrUsuario = $this->mfunciones_logica->getUsuarioCriterio('ejecutivo', $arrResultado3[0]['ejecutivo_id']);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrUsuario);

                        // Evaluación 1=Aprobado    2=Rechazado

                        if($arrResultado3[0]['prospecto_evaluacion'] == 1)
                        {
                            // Aprobado
                            // *** Llamar a función para Aprobar y Flujo COBIS en 2do plano ***
                            $this->mfunciones_generales->Aprobar_FlujoCobis_background($arrResultado3[0]['onboarding_codigo'], $arrUsuario[0]['usuario_id'], $accion_usuario);
                        }
                        else
                        {
                            // Rechazado

                            $this->mfunciones_logica->RechazarSolicitudTercerosApp($arrUsuario[0]['usuario_id'], $accion_usuario, $accion_fecha, $arrResultado3[0]['onboarding_codigo']);

                            // Notificar Proceso Onboarding     0=No Regionalizado      1=Si Regionalizado
                            $this->mfunciones_generales->NotificacionEtapaTerceros($arrResultado3[0]['onboarding_codigo'], 10, 1);

                            // Se registra para las Etapas Onboarding   Pasa a: Notificar Rechazo Onboarding   Etapa Nueva     Etapa Actual
                            $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 10, $etapa_actual, $accion_usuario, $accion_fecha, 0);
                            $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 10, $etapa_actual, $accion_usuario, $accion_fecha, 0);
                        }

                    }

                    /***  REGISTRAR SEGUIMIENTO ***/
                    $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, 5, 1, 'Consolidar Prospecto', $accion_usuario, $accion_fecha);
                }
                
                break;
        }
        
        // Se guarda el Log para la auditoría        
        $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), $geolocalizacion, $accion_usuario, $accion_fecha);

        $lst_resultado[0] = array(
                        "mensaje" => "El Cliente fue consolidado correctamente." . $texto_auxiliar
        );
		
        return $lst_resultado;
    }
    
    // Mantenimientos
    
    /* Descipción: Listado de empresas para la bandeja de mantenimientos */
    function ListadoBandejaMantenimientos($arrDatos){

        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_ejecutivo", "estado"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrResultado = array();
                return $arrResultado;
        }

        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_ejecutivo = $arrDatos['codigo_ejecutivo'];
        $estado = $arrDatos['estado'];
	
		// Listado de los prospectos
        $arrResultado = $this->mfunciones_logica->ObtenerBandejaMantenimientos($codigo_ejecutivo, $estado);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
		
        if (isset($arrResultado[0])) 
        {
            $i = 0;
                        
            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "mantenimiento_id" => $value["mant_id"],
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "empresa_id" => $value["empresa_id"],
                    "empresa_categoria_codigo" => $value["empresa_categoria"],
                    "empresa_categoria_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["empresa_categoria"], 'empresa_categoria'),
                    "mantenimiento_fecha_asignacion" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["mant_fecha_asignacion"]),
                    "mantenimiento_estado_codigo" => $value["mant_estado"],
                    "mantenimiento_estado_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["mant_estado"], 'estado_mantenimiento'),
                    "empresa_nombre_legal" => $value["empresa_nombre_legal"],
                    "empresa_direccion" => $value["empresa_direccion"],
                    "empresa_direccion_geo" => $value["empresa_direccion_geo"],
                    "contacto" => $value["contacto"],
                    "fecha_visita_ini" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["cal_visita_ini"]),
                    "fecha_visita_fin" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["cal_visita_fin"])
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }

        return $lst_resultado;
    }
    
    /* Descipción: Se marca el CheckIn del Mantenimiento */
    function ActualizaCheckInMantenimiento($arrDatos, $usuario, $nombre_servicio){

        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_mantenimiento", "ubicacion_geo"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrResultado = array();
                return $arrResultado;
        }

        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_mantenimiento = $arrDatos['codigo_mantenimiento'];
        $geolocalizacion = $arrDatos['ubicacion_geo'];
	
            
        // Primero se verifica que no haya hecho CheckIn con anterioridad
        $arrResultado_checkin = $this->mfunciones_logica->VerificaCheckInMantenimiento($codigo_mantenimiento);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado_checkin);
		
        if (isset($arrResultado_checkin[0])) 
        {
            // Si ya ha hecho un CheckIn, se muestra un mesaje de error
            $arrError =  array(		
                            "error" => true,
                            "errorMessage" => "¡Ya realizó el CheckIn del mantenimiento!",
                            "errorCode" => 103,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
                    );

            $this->response($arrError, 200);
            exit();
        }
            
        // Si no hizo el ChekIn antes, se procede a registrarlo        
	// Update CheckIn
        $accion_fecha = date('Y-m-d H:i:s');
        
        $this->mfunciones_logica->UpdateCheckInMantenimiento($accion_fecha, $geolocalizacion, $codigo_mantenimiento, $usuario, $accion_fecha);
        
        $lst_resultado[0] = array(
             "mensaje" => "Realizó el Check-In con el Cliente Correctamente."
        );

        // Se guarda el Log para la auditoría        
        $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), $geolocalizacion, $usuario, $accion_fecha);
        
        return $lst_resultado;
    }    
    
    /* Descipción: Listado de las tareas que puedene hacerse en los mantenimientos */
    function ListadoTareas($arrDatos){

        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "estado"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrResultado = array();
                return $arrResultado;
        }

        // Si Todo bien... se captura los datos y se procesa la información

        $estado = $arrDatos['estado'];

            // Listado de los Servicios
            $arrResultado1 = $this->mfunciones_logica->ObtenerTareas();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
				
	if (isset($arrResultado1[0])) 
        {
            $i = 0;
                        
            foreach ($arrResultado1 as $key => $value) 
            {
                $item_valor = array(
                    "tarea_id" => $value["tarea_id"],
                    "tarea_detalle" => $value["tarea_detalle"]
                );
                $lst_resultado1[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado1[0] = $arrResultado1;
        }

        return $lst_resultado1;
    }
    
    /* Descipción: Se actualiza el horario del mantenimiento */
    function ActualizarHorarioMantenimiento($arrDatos, $usuarioAPP, $nombre_servicio){
        		
        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "ubicacion_geo", "codigo_ejecutivo", "codigo_mantenimiento", "fecha_visita_ini", "fecha_visita_fin"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrResultado = array();
                return $arrResultado;
        }
		
        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_ejecutivo = $arrDatos['codigo_ejecutivo'];
        $codigo_mantenimiento = $arrDatos['codigo_mantenimiento'];
        
        $fecha_visita_ini = $arrDatos['fecha_visita_ini'];
        $fecha_visita_fin = $arrDatos['fecha_visita_fin'];
		
        //Auditoría
        $geolocalizacion = $arrDatos['ubicacion_geo'];
        
            // PASO 1: Se realiza las validaciones pertinenetes (LA VALIDACIÓN PARA LA APP ES PARA TODOS LOS CAMPOS, EN LA WEB PUEDE VARIAR)                
            if((int)($codigo_ejecutivo) == 0 || (int)($codigo_mantenimiento) == 0 || $geolocalizacion == '' || $this->mfunciones_generales->VerificaFechaD_M_Y_H_M($fecha_visita_ini) == false || $this->mfunciones_generales->VerificaFechaD_M_Y_H_M($fecha_visita_fin) == false)
            {
                $arrError =  array(
                        "error" => true,
                        "errorMessage" => $this->lang->line('CamposObligatorios'),
                        "errorCode" => 96,
                        "result" => array(
                            "mensaje" => $this->lang->line('IncompletoApp')
                        )
                );

                $this->response($arrError, 200);
                exit();
            }

            // Se verifica que el mantenimiento No es este Completado. No puede actualizarse si esta completado
            // Detalle del Mantenimiento
            $arrResultado3 = $this->mfunciones_logica->VerificaMantenimientoCompletado($codigo_mantenimiento);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            if (isset($arrResultado3[0])) 
            {
                if($arrResultado3[0]['mant_estado'] == 1)
                {
                    $arrError =  array(
                                    "error" => true,
                                    "errorMessage" => $this->lang->line('FormularioYaCompletado'),
                                    "errorCode" => 70,
                                    "result" => array(
                                        "mensaje" => $this->lang->line('IncompletoApp')
                                    )
                    );

                    $this->response($arrError, 200);
                    exit();
                }
            } 
            else 
            {
                $arrResultado = array();
                return $arrResultado;
            }

            // PASO 2: Se actualiza el horario de la visita de mantenimiento

            $accion_usuario = $usuarioAPP;
            $accion_fecha = date('Y-m-d H:i:s');

            $fecha_visita_ini = $this->mfunciones_generales->getFormatoFechaDateTime($fecha_visita_ini);
            $fecha_visita_fin = $this->mfunciones_generales->getFormatoFechaDateTime($fecha_visita_fin);

            $this->mfunciones_logica->UpdateHorarioMantenimiento($fecha_visita_ini, $fecha_visita_fin, $codigo_ejecutivo, $codigo_mantenimiento, $accion_usuario, $accion_fecha);

            // Se guarda el Log para la auditoría        
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), $geolocalizacion, $accion_usuario, $accion_fecha);

            $lst_resultado[0] = array(
                            "mensaje" => "La visita de mantenimiento se actualizó correctamente."
        );

        return $lst_resultado;
    }
    
    /* Descipción: Búsqueda de empresas (comercio o establecimiento/sucrusal) por NIT que estén asociados asignados al Ejecutivo de Cuentas */
    function BusquedaNITEmpresaEjecutivo($arrDatos){

        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "nit", "codigo_ejecutivo"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrResultado = array();
                return $arrResultado;
        }

        // Si Todo bien... se captura los datos y se procesa la información

        $nit = $arrDatos['nit'];
        $codigo_ejecutivo = $arrDatos['codigo_ejecutivo'];

        // No se realiza la búsqueda en NAZIR porque la empresa debe estar registrada en el sistema y asignada a un Ejecutivo de Cuentas
        
            // Detalle del Comercio
            $arrResultado1 = $this->mfunciones_logica->ObtenerEmpresaNITEjecutivo($nit, $codigo_ejecutivo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
				
            if (isset($arrResultado1[0])) 
            {
                $i = 0;

                foreach ($arrResultado1 as $key => $value) 
                {
                    $item_valor = array(
                        "empresa_id" => $value["empresa_id"],
                        "ejecutivo_id" => $value["ejecutivo_id"],
                        "empresa_categoria_codigo" => $value["empresa_categoria"],
                        "empresa_categoria_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["empresa_categoria"], 'empresa_categoria'),
                        "empresa_nombre" => $value["empresa_nombre"] . ' | ' . $value["empresa_nit"]
                    );
                    $lst_resultado1[$i] = $item_valor;

                    $i++;
                }
            }
            else                
            {
                $arrError =  array(
                                "error" => true,
                                "errorMessage" => $this->lang->line('FormularioNoEncontroNIT'),
                                "errorCode" => 71,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
            
        return $lst_resultado1;
    }
    
    /* Descipción: Se crea un nuevo mantenimiento con el código de la empresa y la hora de la vísita */
    function InsertarNuevoMantenimiento($arrDatos, $usuarioAPP, $nombre_servicio){
        		
        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "ubicacion_geo", "codigo_ejecutivo", "codigo_empresa", "fecha_visita_ini", "fecha_visita_fin"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            $arrResultado = array();
            return $arrResultado;
        }
		
        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_ejecutivo = $arrDatos['codigo_ejecutivo'];
        $codigo_empresa = $arrDatos['codigo_empresa'];
        
        $fecha_visita_ini = $arrDatos['fecha_visita_ini'];
        $fecha_visita_fin = $arrDatos['fecha_visita_fin'];
		
        //Auditoría
        $geolocalizacion = $arrDatos['ubicacion_geo'];
        
            // PASO 1: Se realiza las validaciones pertinenetes (LA VALIDACIÓN PARA LA APP ES PARA TODOS LOS CAMPOS, EN LA WEB PUEDE VARIAR)                
            if((int)($codigo_ejecutivo) == 0 || (int)($codigo_empresa) == 0 || $geolocalizacion == '' || $this->mfunciones_generales->VerificaFechaD_M_Y_H_M($fecha_visita_ini) == false || $this->mfunciones_generales->VerificaFechaD_M_Y_H_M($fecha_visita_fin) == false)
            {
                $arrError =  array(
                        "error" => true,
                        "errorMessage" => $this->lang->line('CamposObligatorios'),
                        "errorCode" => 96,
                        "result" => array(
                            "mensaje" => $this->lang->line('IncompletoApp')
                        )
                );

                $this->response($arrError, 200);
                exit();
            }
            
            // Listado Detalle Empresa
            $arrEmpresa = $this->mfunciones_generales->GetDatosEmpresa($codigo_empresa);
            if (!isset($arrEmpresa[0]))
            {
                $arrResultado = array();
                return $arrResultado;
            }
            
            // FIN VALIDACIÓN

            $accion_usuario = $usuarioAPP;
            $accion_fecha = date('Y-m-d H:i:s');
                        
            // PASO 2: Insertar en la tabla "mantenimiento" el nuevo registro
            $arrResultado1 = $this->mfunciones_logica->InsertarMantenimiento($codigo_ejecutivo, $codigo_empresa, $accion_fecha, $accion_usuario, $accion_fecha);
            
            $codigo_mantenimiento = $arrResultado1;

            // PASO 3: Se registra la fecha en el calendario del Ejecutivo de Cuentas
                
            $fecha_visita_ini = $this->mfunciones_generales->getFormatoFechaDateTime($fecha_visita_ini);
            $fecha_visita_fin = $this->mfunciones_generales->getFormatoFechaDateTime($fecha_visita_fin);

            $this->mfunciones_logica->InsertarFechaCaendario($codigo_ejecutivo, $codigo_mantenimiento, 2, $fecha_visita_ini, $fecha_visita_fin, $accion_usuario, $accion_fecha);
            
            // PASO 4: Se crea la carpeta del mantenimiento
        
            $path = RUTA_MANTENIMIENTOS . 'man_' . $codigo_mantenimiento;

            if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
            {
                mkdir($path, 0755, TRUE);
            }

                // Se crea el archivo html para evitar ataques de directorio
                $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($path . '/index.html', $cuerpo_html);
            
            // Se guarda el Log para la auditoría        
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), $geolocalizacion, $accion_usuario, $accion_fecha);

            $lst_resultado[0] = array(
                                "mensaje" => "La visita de mantenimiento se registró correctamente.",
                                "codigo_mantenimiento" => $codigo_mantenimiento
            );

        return $lst_resultado;
    }
    
    /* Descipción: Se recibe el PDF de la Capacitación codificado en base64 para guardarlo en la DB y en el directorio correspondiente del mantenimiento */
    function InsertarMantCapacitacionPDF($arrDatos, $usuarioAPP, $nombre_servicio){
        		
        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "ubicacion_geo", "codigo_mantenimiento", "documento_pdf_base64"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrResultado = array();
                return $arrResultado;
        }
		
        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_mantenimiento = $arrDatos['codigo_mantenimiento'];
        $documento_pdf_base64 = $arrDatos['documento_pdf_base64'];
		
        //Auditoría
        $geolocalizacion = $arrDatos['ubicacion_geo'];
        
        // PASO 1: Se realiza las validaciones pertinenetes (LA VALIDACIÓN PARA LA APP ES PARA TODOS LOS CAMPOS, EN LA WEB PUEDE VARIAR)                
        if((int)($codigo_mantenimiento) == 0 || $documento_pdf_base64 == '' || $geolocalizacion == '')
        {
            $arrError =  array(
                    "error" => true,
                    "errorMessage" => $this->lang->line('CamposObligatorios'),
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => $this->lang->line('IncompletoApp')
                    )
            );

            $this->response($arrError, 200);
            exit();
        }
		
            // Se verifica que el mantenimiento No es este Completado. No puede actualizarse si esta completado
            // Detalle del Mantenimiento
            $arrResultado3 = $this->mfunciones_logica->VerificaMantenimientoCompletado($codigo_mantenimiento);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            if (isset($arrResultado3[0])) 
            {
                if($arrResultado3[0]['mant_estado'] == 1)
                {
                    $arrError =  array(
                                    "error" => true,
                                    "errorMessage" => $this->lang->line('FormularioYaCompletado'),
                                    "errorCode" => 70,
                                    "result" => array(
                                        "mensaje" => $this->lang->line('IncompletoApp')
                                    )
                    );

                    $this->response($arrError, 200);
                    exit();
                }
            } 
            else 
            {
                $arrResultado = array();
                return $arrResultado;
            }
		
            // PASO 2: Se guarda el archivo PDF en el directorio correspondiente
            // Se verifica que el PDF se haya guardado correctamente, caso contrario indicar el error

            $mantenimiento_documento_pdf = $this->mfunciones_generales->GuardarDocumentoMantenimientoBase64($codigo_mantenimiento, $documento_pdf_base64);

            if($mantenimiento_documento_pdf == false)
            {
                    $arrError =  array(
                                    "error" => true,
                                    "errorMessage" => $this->lang->line('FormularioDocumentoError'),
                                    "errorCode" => 98,
                                    "result" => array(
                                        "mensaje" => $this->lang->line('IncompletoApp')
                                    )
                    );

                    $this->response($arrError, 200);
                    exit();
            }

            // PASO 3: Guardar en la DB

            $accion_usuario = $usuarioAPP;
            $accion_fecha = date('Y-m-d H:i:s');

            $this->mfunciones_logica->UpdateSubirDocumentoMantenimiento($mantenimiento_documento_pdf, $accion_usuario, $accion_fecha, $codigo_mantenimiento);

            // Se guarda el Log para la auditoría        
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "{'codigo_mantenimiento': '$codigo_mantenimiento', 'mantenimiento_documento_pdf': '$mantenimiento_documento_pdf'}", $geolocalizacion, $accion_usuario, $accion_fecha);

            $lst_resultado[0] = array(
                                    "mensaje" => "El documento del mantenimiento se guardó correctamente."
            );
		
        return $lst_resultado;
    }
    
    /* Descipción: Se marca el mantenimiento como Completado y se indica las tareas realizadas, 
       por defecto se manda los parámetros "mant_otro" como "0" y su detalle se deja en blanco, 
       pero en el caso de que se realice algún otro mantenimiento diferente del listado, se puede 
       marcar esta opción como "1" y el texto que detalle el mantenimiento extraordinario realizado  */
    function CompletarMantenimiento($arrDatos, $usuarioAPP, $nombre_servicio){
        		
        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "ubicacion_geo", "codigo_mantenimiento", "mant_otro", "mant_otro_detalle", "arrTareas"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrResultado = array();
                return $arrResultado;
        }
		
        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_mantenimiento = $arrDatos['codigo_mantenimiento'];
        $mant_otro = $arrDatos['mant_otro'];
        $mant_otro_detalle = $arrDatos['mant_otro_detalle'];
        
        $arrTareas = $arrDatos['arrTareas'];
		
        //Auditoría
        $geolocalizacion = $arrDatos['ubicacion_geo'];
        
        // PASO 1: Se realiza las validaciones pertinenetes (LA VALIDACIÓN PARA LA APP ES PARA TODOS LOS CAMPOS, EN LA WEB PUEDE VARIAR)                
        if((int)($codigo_mantenimiento) == 0 || $geolocalizacion == '')
        {
            $arrError =  array(
                    "error" => true,
                    "errorMessage" => $this->lang->line('CamposObligatorios'),
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => $this->lang->line('IncompletoApp')
                    )
            );

            $this->response($arrError, 200);
            exit();
        }

        // Se verifica la estructura del Array de las Tareas
        if (isset($arrTareas[0])) 
        {            
            $parametros_tareas = "tarea_id";
            
            foreach ($arrTareas as $key => $value) 
            {
                if(!isset($value[$parametros_tareas]))
                {
                    $arrError =  array(
                                    "error" => true,
                                    "errorMessage" => $this->lang->line('CamposObligatorios'),
                                    "errorCode" => 96,
                                    "result" => array(
                                        "mensaje" => $this->lang->line('IncompletoApp')
                                    )
                            );

                    $this->response($arrError, 200);
                    exit();
                }
            }
        }
        
            // Se verifica que el mantenimiento No es este Completado. No puede actualizarse si esta completado
            // Detalle del Mantenimiento
            $arrResultado3 = $this->mfunciones_logica->VerificaMantenimientoCompletado($codigo_mantenimiento);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            if (isset($arrResultado3[0])) 
            {
                if($arrResultado3[0]['mant_estado'] == 1)
                {
                    $arrError =  array(
                                    "error" => true,
                                    "errorMessage" => $this->lang->line('FormularioYaCompletado'),
                                    "errorCode" => 70,
                                    "result" => array(
                                        "mensaje" => $this->lang->line('IncompletoApp')
                                    )
                    );

                    $this->response($arrError, 200);
                    exit();
                }
            } 
            else 
            {
                $arrResultado = array();
                return $arrResultado;
            }
	
        // FIN VALIDACIÓN
            
            $accion_usuario = $usuarioAPP;
            $accion_fecha = date('Y-m-d H:i:s');
            
            // Insertar las tareas seleccionados del mantenimiento
            // (se elimina todas las tareas del mantenimiento para volver a insertarlos)
            $this->mfunciones_logica->EliminarTareasMantenimiento($codigo_mantenimiento);

            if (isset($arrTareas[0])) 
            {            
                foreach ($arrTareas as $key => $value) 
                {
                    $this->mfunciones_logica->InsertarTareasMantenimiento($codigo_mantenimiento, $value["tarea_id"], $accion_usuario, $accion_fecha);
                }
            }
            elseif($mant_otro == 0)
            {
                $arrError =  array(
                                "error" => true,
                                "errorMessage" => $this->lang->line('FormularioSinOpciones'),
                                "errorCode" => 98,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
            
            // PASO 3: Guardar en la DB
            
            $this->mfunciones_logica->CompletarMantenimiento($geolocalizacion, $mant_otro, $mant_otro_detalle, $accion_usuario, $accion_fecha, $codigo_mantenimiento);

            // Se guarda el Log para la auditoría        
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), $geolocalizacion, $accion_usuario, $accion_fecha);

            $lst_resultado[0] = array(
                                    "mensaje" => "El mantenimiento se marcó como Completado correctamente con las tareas indicadas."
            );
		
        return $lst_resultado;
    }
    
    // Estadísticas
    
    /* Descipción: Reporte para obtener la cantidad de prospectos o mantenimientos realizados por fecha, en un rango de fechas */
    function ReporteVisitasEntreFechas($arrDatos){

        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_ejecutivo", "fecha_ini", "fecha_fin"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrResultado = array();
                return $arrResultado;
        }

        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_ejecutivo = $arrDatos['codigo_ejecutivo'];
        $fecha_ini = $arrDatos['fecha_ini'];
        $fecha_fin = $arrDatos['fecha_fin'];

        // PASO 1: Se realiza las validaciones pertinenetes (LA VALIDACIÓN PARA LA APP ES PARA TODOS LOS CAMPOS, EN LA WEB PUEDE VARIAR)                
        if((int)($codigo_ejecutivo) == 0 || $this->mfunciones_generales->VerificaFechaD_M_Y($fecha_ini) == false || $this->mfunciones_generales->VerificaFechaD_M_Y($fecha_fin) == false)
        {
            $arrError =  array(
                    "error" => true,
                    "errorMessage" => $this->lang->line('CamposObligatorios'),
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => $this->lang->line('IncompletoApp')
                    )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        // Se añade las horas para brindar mayor presición a la búsqueda
        
        $fecha_ini = $this->mfunciones_generales->getFormatoFechaDateTime($fecha_ini . '00:00:00');
        $fecha_fin = $this->mfunciones_generales->getFormatoFechaDateTime($fecha_fin . '23:59:59');
        
        // Resultado del Reporte
        $arrResultado1 = $this->mfunciones_logica->ReporteVisitasEntreFechas($codigo_ejecutivo, $fecha_ini, $fecha_fin);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
			
        /* Se busca en la base de datos si fuera necesario el valor de $meta_visitas */
            // Parámetro del Índice de cumplimiento de los Ejecutivos de Cuenta
            $arrResultado5 = $this->mfunciones_logica->ObtenerDatosConf_Credenciales();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado5);
        
            $meta_visitas = $arrResultado5[0]['conf_ejecutivo_ic'];
        
	if (isset($arrResultado1[0])) 
        {
            $i = 0;
                        
            foreach ($arrResultado1 as $key => $value) 
            {
                $item_valor = array(
                    "fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["fecha"]),
                    "prospecto" => $value["prospecto"],
                    "mantenimiento" => $value["mantenimiento"],
                    "meta" => $meta_visitas
                );
                $lst_resultado1[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado1[0] = $arrResultado1;
        }

        return $lst_resultado1;
    }
    
    // Entrega del Servicio
    
    /* Descipción: Listado de los prospectos que ya fueron Insertados en PayStudio */
    function ListadoBandejaEntregaServicios($arrDatos){

        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_ejecutivo", "entrega_servicio"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrResultado = array();
                return $arrResultado;
        }

        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_ejecutivo = $arrDatos['codigo_ejecutivo'];
        $entrega_servicio = $arrDatos['entrega_servicio'];
	
		// Listado de los prospectos
        $arrResultado = $this->mfunciones_logica->ObtenerBandejaEntregaServicio($codigo_ejecutivo, $entrega_servicio);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
		
        if (isset($arrResultado[0])) 
        {
            $i = 0;
                        
            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "prospecto_id" => $value["prospecto_id"],
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "empresa_id" => $value["empresa_id"],
                    "empresa_categoria_codigo" => $value["empresa_categoria"],
                    "empresa_categoria_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["empresa_categoria"], 'empresa_categoria'),
                    "prospecto_aceptado_afiliado_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["prospecto_aceptado_afiliado_fecha"]),
                    "prospecto_entrega_servicio_codigo" => $value["prospecto_entrega_servicio"],
                    "prospecto_entrega_servicio_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["prospecto_entrega_servicio"], 'entregado'),
                    "empresa_nombre_legal" => $value["empresa_nombre_legal"],
                    "empresa_direccion" => $value["empresa_direccion"],
                    "empresa_direccion_geo" => $value["empresa_direccion_geo"],
                    "contacto" => $value["contacto"]
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }

        return $lst_resultado;
    }
    
    /* Descipción: Se confirma la entrega del servicio para cerrar el ciclo del flujo de la afiliación y posteriormente realizar el seguimiento a los tiempos */
    function ConfirmarEntregaServicio($arrDatos, $usuarioAPP, $nombre_servicio){
        		
        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "ubicacion_geo", "codigo_ejecutivo", "codigo_prospecto"
                );
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
                $arrResultado = array();
                return $arrResultado;
        }
		
        // Si Todo bien... se captura los datos y se procesa la información

        $codigo_ejecutivo = $arrDatos['codigo_ejecutivo'];
        $codigo_prospecto = $arrDatos['codigo_prospecto'];
        
        // *** Obtener Código y Tipo Persona ***
            $aux_get_codigo = $this->getCodigo($codigo_prospecto);

            if($aux_get_codigo->codigo == 0)
            {
                $arrError = array(
                    "error" => true,
                    "errorMessage" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')',
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => 'Ocurrió un error al captura el código identificador (' . $codigo_prospecto . ')'
                    )
                );

                $this->response($arrError, 200);
                exit();
            }

            $codigo_prospecto = $aux_get_codigo->codigo;
            $codigo_tipo_persona = $aux_get_codigo->tipo_persona;
        // *** Obtener Código y Tipo Persona ***
        
        //Auditoría
        $geolocalizacion = $arrDatos['ubicacion_geo'];
        
        // PASO 1: Se realiza las validaciones pertinenetes (LA VALIDACIÓN PARA LA APP ES PARA TODOS LOS CAMPOS, EN LA WEB PUEDE VARIAR)                
        if((int)($codigo_ejecutivo) == 0 || (int)($codigo_prospecto) == 0 || $geolocalizacion == '')
        {
            $arrError =  array(
                    "error" => true,
                    "errorMessage" => $this->lang->line('CamposObligatorios'),
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => $this->lang->line('IncompletoApp')
                    )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        // Se verifica que el prospecto No es este Consolidado. No puede actualizarse si esta consolidado

        // Detalle del Prospecto
        $arrResultado3 = $this->mfunciones_logica->VerificaServicioEntregado($codigo_prospecto);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
		
        if (isset($arrResultado3[0])) 
        {
            if($arrResultado3[0]['prospecto_entrega_servicio'] == 1)
            {
                $arrError =  array(
                                "error" => true,
                                "errorMessage" => $this->lang->line('FormularioYaEntregado'),
                                "errorCode" => 60,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                );

                $this->response($arrError, 200);
                exit();
            }			
        } 
        else 
        {
            $arrResultado = array();
            return $arrResultado;
        }
        
        // FIN VALIDACIÓN
            
            $accion_usuario = $usuarioAPP;
            $accion_fecha = date('Y-m-d H:i:s');
            
            // PASO 3: Guardar en la DB
            
            $this->mfunciones_logica->CompletarEntregaServicio($geolocalizacion, $accion_usuario, $accion_fecha, $codigo_ejecutivo, $codigo_prospecto);

            $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 17, 16, $accion_usuario, $accion_fecha);
            /***  REGISTRAR SEGUIMIENTO ***/
            $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, 17, 8, 'Se marcó la entrega del Servicio', $accion_usuario, $accion_fecha);
            
            // Se guarda el Log para la auditoría        
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), $geolocalizacion, $accion_usuario, $accion_fecha);

            $lst_resultado[0] = array(
                                    "mensaje" => "Se confirmó la entrega del servicio correctamente."
            );
		
        return $lst_resultado;
    }
    
    /*************** AFILIACIÓN POR TERCEROS - INICIO ****************************/
    
    function External__Validate_Faces($arrDatos, $usuarioAPP, $nombre_servicio, $codigo_usuario, $perfil_app){
        
        require APPPATH.'libraries/rekognition/vendor/autoload.php';
        
        // Primero se verifica si el Perfil App es Afiliador Tercero "5"
        
        if ($perfil_app != 2) 
        {
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError =  array(
                            "error" => true,
                            "errorMessage" => 'No cuenta con el Perfil App Afiliador Tercero, por favor comuníquese con el Administrador del sistema.',
                            "errorCode" => 91,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
            );

            $this->response($arrError, 200);
            exit();			
        }
        
        // Obtener Listado de Documentos
        $arrDataDocumento = $this->mfunciones_logica->ObtenerDocumentosTercerosCriterio(0);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDataDocumento);
        
        if (!isset($arrDataDocumento[0])) 
        {
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError =  array(
                            "error" => true,
                            "errorMessage" => 'No se tienen Documentos Habilitados.',
                            "errorCode" => 91,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
            );

            $this->response($arrError, 200);
            exit();			
        }
        
        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_afiliador"
            );
        
        $accion_usuario = $usuarioAPP;
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Existen parámetros de entrada faltantes, revise el listado de campos del servicio.", '0,0', $accion_usuario, $accion_fecha);
            
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError =  array(
                            "error" => true,
                            "errorMessage" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio.',
                            "errorCode" => 99,
                            "result" => array(
                                "mensaje" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio.'
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        $codigo_afiliador = $arrDatos['codigo_afiliador'];
        
        // VALIDACIONES DEL AFILIADOR
        
            $arrResultado3 = $this->mfunciones_logica->VerificaAfiliadorTercero($codigo_usuario, $codigo_afiliador);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            if (!isset($arrResultado3[0])) 
            {
                // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
                $arrError =  array(
                                "error" => true,
                                "errorMessage" => 'Credenciales incorrectas. Key del Afiliador.',
                                "errorCode" => 91,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
            else
            {
                $identificador_afiliador = $arrResultado3[0]["afiliador_id"];
                $identificador_ejecutivo = $arrResultado3[0]["ejecutivo_id"];
            }
        
        // Validaciones INICIO
            
        $arrError[0] =  array(
                "error" => true,
                "errorMessage" => $this->lang->line('CamposObligatorios'),
                "errorCode" => 96,
                "result" => array(
                    "mensaje" => $this->lang->line('IncompletoApp')
                )
        );
        
        $separador = '<br /> - ';
        $error_texto = '';
        
        if($error_texto != '')
        {
            $arrError[0]['errorMessage'] = $this->lang->line('CamposObligatorios') . $error_texto;
            $this->response($arrError, 200); exit();
        }
        
        // Recorre y guarda los documentos
        
            $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
        
            $conf_doc_validacion1 = '';
            $conf_doc_validacion2 = '';
            
            foreach ($arrDataDocumento as $key => $value) 
            {
                // Se valida que el código del documento exista
                if(isset($arrDatos[$value['documento_codigo']]))
                {
                    $arrDocumentos[] = array("doc"=>$arrDatos[$value['documento_codigo']], "id"=>$value['documento_id']);
                
                    // Se pregunta por las imagenes que serán validadas

                    if($arrConf[0]['conf_doc_validacion1'] == $value['documento_id'])
                    {
                        $conf_doc_validacion1 = $arrDatos[$value['documento_codigo']];
                    }

                    if($arrConf[0]['conf_doc_validacion2'] == $value['documento_id'])
                    {
                        $conf_doc_validacion2 = $arrDatos[$value['documento_codigo']];
                    }
                }
            }
            
            $rekognition = 0;
            $similaridad_obtenida = 0;
            
            // Sólo si esta marcada la opción, se realizará la validación facial
            
            if($arrConf[0]['conf_rekognition'] == 1)
            {
                $rekognition = 1;
                
                // Validación Rekognition

                try
                {
                    if($conf_doc_validacion1 == '' || $conf_doc_validacion2 == '')
                    {
                        // Se guarda el Log para la auditoría
                        $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Se envió imagenes vacías para la validación de reconocimiento facial", '0,0', $accion_usuario, $accion_fecha);

                        $arrError =  array(
                                            "error" => true,
                                            "errorMessage" => $arrConf[0]['conf_rekognition_texto_fallo'],
                                            "errorCode" => 98,
                                            "result" => array(
                                                "mensaje" => $this->lang->line('IncompletoApp')
                                            )
                                        );

                        $this->response($arrError, 200);
                        exit();
                    }

                    $args = [
                        'credentials' => [
                            'key' => $arrConf[0]['conf_rekognition_key'],
                            'secret' => $arrConf[0]['conf_rekognition_secret'],
                        ],
                        'region' => $arrConf[0]['conf_rekognition_region'],
                        'version' => 'latest'
                    ];

                    $client = new Aws\Rekognition\RekognitionClient($args);

                    $result_rekognition = $client->compareFaces([
                        'SimilarityThreshold' => (int)$arrConf[0]['conf_rekognition_similarity'],
                        'SourceImage' => [
                            'Bytes' => @file_get_contents($conf_doc_validacion1),

                        ],
                        'TargetImage' => [
                            'Bytes' => @file_get_contents($conf_doc_validacion2),
                        ],
                    ]);

                    if(isset($result_rekognition['FaceMatches'][0]))
                    {
                        $similaridad_obtenida = number_format($result_rekognition['FaceMatches'][0]['Similarity'], 2, '.', '.');
                    }
                    else
                    {
                        // Se guarda el Log para la auditoría
                        $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: No pasó la validación de reconocimiento facial", '0,0', $accion_usuario, $accion_fecha);

                        $arrError =  array(
                                            "error" => true,
                                            "errorMessage" => $arrConf[0]['conf_rekognition_texto_fallo'],
                                            "errorCode" => 98,
                                            "result" => array(
                                                "mensaje" => $this->lang->line('IncompletoApp')
                                            )
                                        );

                        $this->response($arrError, 200);
                        exit();
                    }
                }
                catch(Exception $e)
                {
                    // Se guarda el Log para la auditoría
                    $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: " . $e, '0,0', $accion_usuario, $accion_fecha);

                    $arrError =  array(
                                        "error" => true,
                                        "errorMessage" => $arrConf[0]['conf_rekognition_texto_fallo'],
                                        "errorCode" => 98,
                                        "result" => array(
                                            "mensaje" => $this->lang->line('IncompletoApp')
                                        )
                                    );

                    $this->response($arrError, 200);
                    exit();

                }
            }
        
        // Validaciones FIN
        
        $lst_resultado[0] = array(
            "similaridad_obtenida" => $similaridad_obtenida,
            "mensaje" => "Validación Facial Correcta"
        );

        return $lst_resultado;
        
    }
    
    function External__Register_Prospect($arrDatos, $usuarioAPP, $nombre_servicio, $codigo_usuario, $perfil_app){
        
        require APPPATH.'libraries/rekognition/vendor/autoload.php';
        
        // Primero se verifica si el Perfil App es Afiliador Tercero "5"
        
        if ($perfil_app != 2) 
        {
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'No cuenta con el Perfil App Afiliador Tercero, por favor comuníquese con el Administrador del sistema.',
                            "errorCode" => 91,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
            );

            $this->response($arrError, 200);
            exit();			
        }
        
        // Obtener Listado de Documentos
        $arrDataDocumento = $this->mfunciones_logica->ObtenerDocumentosTercerosCriterio(0);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDataDocumento);
        
        if (!isset($arrDataDocumento[0])) 
        {
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'No se tienen Documentos Habilitados.',
                            "errorCode" => 91,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
            );

            $this->response($arrError, 200);
            exit();			
        }
        
        // Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_afiliador", 
                "codigo_agencia_fie", 
                "tipo_cuenta", 
                "direccion_email", 
                "coordenadas_geo_dom", 
                "coordenadas_geo_trab", 
                "envio", 
                "cI_numeroraiz", 
                "cI_complemento", 
                "cI_lugar_emisionoextension", 
                "cI_confirmacion_id", 
                "di_primernombre", 
                "di_segundo_otrosnombres", 
                "di_primerapellido", 
                "di_segundoapellido", 
                "di_fecha_nacimiento", 
                "di_fecha_vencimiento", 
                "di_indefinido", 
                "di_genero", 
                "di_estadocivil", 
                "di_apellido_casada", 
                "dd_profesion", 
                "dd_nivel_estudios", 
                "dd_dependientes", 
                "dd_proposito_rel_comercial", 
                "dec_ingresos_mensuales", 
                "dec_nivel_egresos", 
                "dir_tipo_direccion", 
                "dir_departamento", 
                "dir_provincia", 
                "dir_localidad_ciudad", 
                "dir_barrio_zona_uv", 
                "dir_ubicacionreferencial", 
                "dir_av_calle_pasaje", 
                "dir_edif_cond_urb", 
                "dir_numero", 
            
                "dir_departamento_neg", 
                "dir_provincia_neg", 
                "dir_localidad_ciudad_neg", 
                "dir_barrio_zona_uv_neg", 
                "dir_ubicacionreferencial_neg", 
                "dir_av_calle_pasaje_neg", 
                "dir_edif_cond_urb_neg", 
                "dir_numero_neg", 
            
                "dir_tipo_telefono", 
                "dir_notelefonico", 
                "ae_sector_economico", 
                "ae_actividad_fie", 
                "ae_ambiente", 
                "emp_nombre_empresa", 
                "emp_direccion_trabajo", 
                "emp_telefono_faxtrabaj", 
                "emp_tipo_empresa", 
                "emp_antiguedad_empresa", 
                "emp_codigo_actividad", 
                "emp_descripcion_cargo", 
                "emp_fecha_ingreso", 
                "rp_nombres", 
                "rp_primer_apellido", 
                "rp_segundo_apellido", 
                "rp_direccion", 
                "rp_notelefonicos", 
                "rp_nexo_cliente", 
                "con_primer_nombre", 
                "con_segundo_nombre", 
                "con_primera_pellido", 
                "con_segundoa_pellido", 
                "con_acteconomica_principal", 
                "ddc_ciudadania_usa", 
                "ddc_motivo_permanencia_usa", 
                "dig_firma", 
            
                "token"
            );
        
        $accion_usuario = $usuarioAPP;
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Existen parámetros de entrada faltantes, revise el listado de campos del servicio.", '0,0', $accion_usuario, $accion_fecha);
            
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', '),
                            "errorCode" => 99,
                            "result" => array(
                                "mensaje" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', ')
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        $codigo_afiliador = $arrDatos['codigo_afiliador'];
        
        // VALIDACIONES DEL AFILIADOR
        
            $arrResultado3 = $this->mfunciones_logica->VerificaAfiliadorTercero($codigo_usuario, $codigo_afiliador);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            if (!isset($arrResultado3[0])) 
            {
                // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
                $arrError[0] =  array(
                                "error" => true,
                                "errorMessage" => 'Credenciales incorrectas. Key del Afiliador.',
                                "errorCode" => 91,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
            else
            {
                $identificador_afiliador = $arrResultado3[0]["afiliador_id"];
                $identificador_ejecutivo = $arrResultado3[0]["ejecutivo_id"];
            }
        
        // Validaciones INICIO

        // PASO 1. VALIDAR EL TOKEN Y OBTENER LA DATA Y CERTIFICADO SEGIP
            
            $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
            
            $parametros_token = $this->mfunciones_generales->TokenOnboarding_parametros($arrDatos['token'], 'documento');
            
            if ($parametros_token == FALSE) 
            {
                // Se guarda el Log para la auditoría
                $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Sesión expirada o Token inválido/expirado al guardar la data.", '0,0', $accion_usuario, $accion_fecha);

                // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
                $arrError[0] =  array(
                                "error" => true,
                                "errorMessage" => $arrConf[0]['conf_token_texto'],
                                "errorCode" => 300,
                                "result" => array(
                                    "mensaje" => $arrConf[0]['conf_token_texto']
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
       
            // Se capturan los valores recibidos, del token
            
            $arrDatos['dig_cert_segip'] = $parametros_token['dig_cert_segip'];
            
            $arrDatos['dig_ci_anv'] = $parametros_token['dig_ci_anv'];
            $arrDatos['dig_ci_rev'] = $parametros_token['dig_ci_rev'];
            $arrDatos['dig_selfie'] = $parametros_token['dig_selfie'];
            $arrDatos['ws_cobis_codigo_resultado'] = $parametros_token['ws_cobis_codigo_resultado'];
            $arrDatos['ws_segip_codigo_resultado'] = $parametros_token['ws_segip_codigo_resultado'];
            $arrDatos['ws_segip_flag_verificacion'] = $parametros_token['ws_segip_flag_verificacion'];
            $arrDatos['ws_selfie_codigo_resultado'] = $parametros_token['ws_selfie_codigo_resultado'];
            $arrDatos['ws_ocr_codigo_resultado'] = $parametros_token['ws_ocr_codigo_resultado'];
            $arrDatos['ws_ocr_name_valor'] = $parametros_token['ws_ocr_name_valor'];
            $arrDatos['ws_ocr_name_similar'] = $parametros_token['ws_ocr_name_similar'];
            $arrDatos['cI_lugar_emisionoextension'] = ($parametros_token['cI_lugar_emisionoextension'] == '' ? ' ' : $parametros_token['cI_lugar_emisionoextension']);
            
        // Validación de campos
        $arrError[0] =  array(
                "error" => true,
                "errorMessage" => $this->lang->line('CamposObligatorios'),
                "errorCode" => 96,
                "result" => array(
                    "mensaje" => $this->lang->line('IncompletoApp')
                )
        );
        
        $separador = '<br /> - ';
        $error_texto = '';
        
        $direccion_email = $arrDatos['direccion_email'];
        
        if($this->mfunciones_generales->VerificaCorreo($direccion_email) == false)
        {
            $error_texto .= $separador . 'Email en formato correcto';
        }
        
        $tipo_cuenta = $this->mfunciones_generales->SanitizarData($arrDatos['tipo_cuenta']); if($tipo_cuenta == '') { $error_texto .= $separador . $this->lang->line('tipo_cuenta'); }
        $coordenadas_geo_dom = $this->mfunciones_generales->SanitizarData($arrDatos['coordenadas_geo_dom']); if($coordenadas_geo_dom == '') { $error_texto .= $separador . $this->lang->line('coordenadas_geo_dom'); }
        $coordenadas_geo_trab = $this->mfunciones_generales->SanitizarData($arrDatos['coordenadas_geo_trab']); if($coordenadas_geo_trab == '') { $error_texto .= $separador . $this->lang->line('coordenadas_geo_trab'); }
        $envio = $this->mfunciones_generales->SanitizarData($arrDatos['envio']); if($envio == '') { $error_texto .= $separador . $this->lang->line('envio'); }
        $cI_numeroraiz = $this->mfunciones_generales->SanitizarData($arrDatos['cI_numeroraiz']); if($cI_numeroraiz == '') { $error_texto .= $separador . $this->lang->line('cI_numeroraiz'); }
        $cI_complemento = $this->mfunciones_generales->SanitizarData($arrDatos['cI_complemento']);
        $cI_lugar_emisionoextension = $this->mfunciones_generales->SanitizarData($arrDatos['cI_lugar_emisionoextension']); if($cI_lugar_emisionoextension == '') { $error_texto .= $separador . $this->lang->line('cI_lugar_emisionoextension'); }
        $cI_confirmacion_id = $this->mfunciones_generales->SanitizarData($arrDatos['cI_confirmacion_id']); if($cI_confirmacion_id == '') { $error_texto .= $separador . $this->lang->line('cI_confirmacion_id'); }
        $di_primernombre = $this->mfunciones_generales->SanitizarData($arrDatos['di_primernombre']); if($di_primernombre == '') { $error_texto .= $separador . $this->lang->line('di_primernombre'); }
        $di_segundo_otrosnombres = $this->mfunciones_generales->SanitizarData($arrDatos['di_segundo_otrosnombres']);
        $di_primerapellido = $this->mfunciones_generales->SanitizarData($arrDatos['di_primerapellido']); if($di_primerapellido == '') { $error_texto .= $separador . $this->lang->line('di_primerapellido'); }
        $di_segundoapellido = $this->mfunciones_generales->SanitizarData($arrDatos['di_segundoapellido']);
        $di_fecha_nacimiento = ($arrDatos['di_fecha_nacimiento']); if($di_fecha_nacimiento == '') { $error_texto .= $separador . $this->lang->line('di_fecha_nacimiento'); }
        $di_fecha_vencimiento = ($arrDatos['di_fecha_vencimiento']); if($di_fecha_vencimiento == '') { $error_texto .= $separador . $this->lang->line('di_fecha_vencimiento'); }
        $di_indefinido = $this->mfunciones_generales->SanitizarData($arrDatos['di_indefinido']); if($di_indefinido == '') { $error_texto .= $separador . $this->lang->line('di_indefinido'); }
        $di_genero = $this->mfunciones_generales->SanitizarData($arrDatos['di_genero']); if($di_genero == '') { $error_texto .= $separador . $this->lang->line('di_genero'); }
        $di_estadocivil = $this->mfunciones_generales->SanitizarData($arrDatos['di_estadocivil']); if($di_estadocivil == '') { $error_texto .= $separador . $this->lang->line('di_estadocivil'); }
        $di_apellido_casada = $this->mfunciones_generales->SanitizarData($arrDatos['di_apellido_casada']);
        $dd_profesion = $this->mfunciones_generales->SanitizarData($arrDatos['dd_profesion']); if($dd_profesion == '') { $error_texto .= $separador . $this->lang->line('dd_profesion'); }
        $dd_nivel_estudios = $this->mfunciones_generales->SanitizarData($arrDatos['dd_nivel_estudios']); if($dd_nivel_estudios == '') { $error_texto .= $separador . $this->lang->line('dd_nivel_estudios'); }
        $dd_dependientes = $this->mfunciones_generales->SanitizarData($arrDatos['dd_dependientes']); if($dd_dependientes == '') { $error_texto .= $separador . $this->lang->line('dd_dependientes'); }
        $dd_proposito_rel_comercial = $this->mfunciones_generales->SanitizarData($arrDatos['dd_proposito_rel_comercial']); if($dd_proposito_rel_comercial == '') { $error_texto .= $separador . $this->lang->line('dd_proposito_rel_comercial'); }
        $dec_ingresos_mensuales = $this->mfunciones_generales->SanitizarData($arrDatos['dec_ingresos_mensuales']); if($dec_ingresos_mensuales == '') { $error_texto .= $separador . $this->lang->line('dec_ingresos_mensuales'); }
        $dec_nivel_egresos = $this->mfunciones_generales->SanitizarData($arrDatos['dec_nivel_egresos']); if($dec_nivel_egresos == '') { $error_texto .= $separador . $this->lang->line('dec_nivel_egresos'); }
        $dir_tipo_direccion = $this->mfunciones_generales->SanitizarData($arrDatos['dir_tipo_direccion']); if($dir_tipo_direccion == '') { $error_texto .= $separador . $this->lang->line('dir_tipo_direccion'); }
        
        $dir_departamento = $this->mfunciones_generales->SanitizarData($arrDatos['dir_departamento']); if($dir_departamento == '') { $error_texto .= $separador . $this->lang->line('dir_departamento'); }
        $dir_provincia = $this->mfunciones_generales->SanitizarData($arrDatos['dir_provincia']); if($dir_provincia == '') { $error_texto .= $separador . $this->lang->line('dir_provincia'); }
        $dir_localidad_ciudad = $this->mfunciones_generales->SanitizarData($arrDatos['dir_localidad_ciudad']); if($dir_localidad_ciudad == '') { $error_texto .= $separador . $this->lang->line('dir_localidad_ciudad'); }
        $dir_barrio_zona_uv = $this->mfunciones_generales->SanitizarData($arrDatos['dir_barrio_zona_uv']); if($dir_barrio_zona_uv == '') { $error_texto .= $separador . $this->lang->line('dir_barrio_zona_uv'); }
        $dir_ubicacionreferencial = $this->mfunciones_generales->SanitizarData($arrDatos['dir_ubicacionreferencial']);
        $dir_av_calle_pasaje = $this->mfunciones_generales->SanitizarData($arrDatos['dir_av_calle_pasaje']); if($dir_av_calle_pasaje == '') { $error_texto .= $separador . $this->lang->line('dir_av_calle_pasaje'); }
        $dir_edif_cond_urb = $this->mfunciones_generales->SanitizarData($arrDatos['dir_edif_cond_urb']); if($dir_edif_cond_urb == '') { $error_texto .= $separador . $this->lang->line('dir_edif_cond_urb'); }
        $dir_numero = $this->mfunciones_generales->SanitizarData($arrDatos['dir_numero']); if($dir_numero == '') { $error_texto .= $separador . $this->lang->line('dir_numero'); }
        
        $dir_departamento_neg = $this->mfunciones_generales->SanitizarData($arrDatos['dir_departamento_neg']);
        $dir_provincia_neg = $this->mfunciones_generales->SanitizarData($arrDatos['dir_provincia_neg']);
        $dir_localidad_ciudad_neg = $this->mfunciones_generales->SanitizarData($arrDatos['dir_localidad_ciudad_neg']);
        $dir_barrio_zona_uv_neg = $this->mfunciones_generales->SanitizarData($arrDatos['dir_barrio_zona_uv_neg']);
        $dir_ubicacionreferencial_neg = $this->mfunciones_generales->SanitizarData($arrDatos['dir_ubicacionreferencial_neg']);
        $dir_av_calle_pasaje_neg = $this->mfunciones_generales->SanitizarData($arrDatos['dir_av_calle_pasaje_neg']);
        $dir_edif_cond_urb_neg = $this->mfunciones_generales->SanitizarData($arrDatos['dir_edif_cond_urb_neg']);
        $dir_numero_neg = $this->mfunciones_generales->SanitizarData($arrDatos['dir_numero_neg']);
        
        $dir_tipo_telefono = $this->mfunciones_generales->SanitizarData($arrDatos['dir_tipo_telefono']); if($dir_tipo_telefono == '') { $error_texto .= $separador . $this->lang->line('dir_tipo_telefono'); }
        $dir_notelefonico = $this->mfunciones_generales->SanitizarData($arrDatos['dir_notelefonico']); if($dir_notelefonico == '') { $error_texto .= $separador . $this->lang->line('dir_notelefonico'); }
        $ae_sector_economico = $this->mfunciones_generales->SanitizarData($arrDatos['ae_sector_economico']); if($ae_sector_economico == '') { $error_texto .= $separador . $this->lang->line('ae_sector_economico'); }
        $ae_actividad_fie = $this->mfunciones_generales->SanitizarData($arrDatos['ae_actividad_fie']); if($ae_actividad_fie == '') { $error_texto .= $separador . $this->lang->line('ae_actividad_fie'); }
        $ae_ambiente = $this->mfunciones_generales->SanitizarData($arrDatos['ae_ambiente']); if($ae_ambiente == '') { $error_texto .= $separador . $this->lang->line('ae_ambiente'); }
        $emp_nombre_empresa = $this->mfunciones_generales->SanitizarData($arrDatos['emp_nombre_empresa']); if($emp_nombre_empresa == '') { $error_texto .= $separador . $this->lang->line('emp_nombre_empresa'); }
        $emp_direccion_trabajo = $this->mfunciones_generales->SanitizarData($arrDatos['emp_direccion_trabajo']); if($emp_direccion_trabajo == '') { $error_texto .= $separador . $this->lang->line('emp_direccion_trabajo'); }
        $emp_telefono_faxtrabaj = $this->mfunciones_generales->SanitizarData($arrDatos['emp_telefono_faxtrabaj']); if($emp_telefono_faxtrabaj == '') { $error_texto .= $separador . $this->lang->line('emp_telefono_faxtrabaj'); }
        $emp_tipo_empresa = $this->mfunciones_generales->SanitizarData($arrDatos['emp_tipo_empresa']); if($emp_tipo_empresa == '') { $error_texto .= $separador . $this->lang->line('emp_tipo_empresa'); }
        $emp_antiguedad_empresa = $this->mfunciones_generales->SanitizarData($arrDatos['emp_antiguedad_empresa']); if($emp_antiguedad_empresa == '') { $error_texto .= $separador . $this->lang->line('emp_antiguedad_empresa'); }
        $emp_codigo_actividad = $this->mfunciones_generales->SanitizarData($arrDatos['emp_codigo_actividad']); if($emp_codigo_actividad == '') { $error_texto .= $separador . $this->lang->line('emp_codigo_actividad'); }
        $emp_descripcion_cargo = $this->mfunciones_generales->SanitizarData($arrDatos['emp_descripcion_cargo']); if($emp_descripcion_cargo == '') { $error_texto .= $separador . $this->lang->line('emp_descripcion_cargo'); }
        $emp_fecha_ingreso = ($arrDatos['emp_fecha_ingreso']); if($emp_fecha_ingreso == '') { $error_texto .= $separador . $this->lang->line('emp_fecha_ingreso'); }
        $rp_nombres = $this->mfunciones_generales->SanitizarData($arrDatos['rp_nombres']); if($rp_nombres == '') { $error_texto .= $separador . $this->lang->line('rp_nombres'); }
        $rp_primer_apellido = $this->mfunciones_generales->SanitizarData($arrDatos['rp_primer_apellido']); if($rp_primer_apellido == '') { $error_texto .= $separador . $this->lang->line('rp_primer_apellido'); }
        $rp_segundo_apellido = $this->mfunciones_generales->SanitizarData($arrDatos['rp_segundo_apellido']); if($rp_segundo_apellido == '') { $error_texto .= $separador . $this->lang->line('rp_segundo_apellido'); }
        $rp_direccion = $this->mfunciones_generales->SanitizarData($arrDatos['rp_direccion']); if($rp_direccion == '') { $error_texto .= $separador . $this->lang->line('rp_direccion'); }
        $rp_notelefonicos = $this->mfunciones_generales->SanitizarData($arrDatos['rp_notelefonicos']); if($rp_notelefonicos == '') { $error_texto .= $separador . $this->lang->line('rp_notelefonicos'); }
        $rp_nexo_cliente = $this->mfunciones_generales->SanitizarData($arrDatos['rp_nexo_cliente']); if($rp_nexo_cliente == '') { $error_texto .= $separador . $this->lang->line('rp_nexo_cliente'); }
        $con_primer_nombre = $this->mfunciones_generales->SanitizarData($arrDatos['con_primer_nombre']); 
        $con_segundo_nombre = $this->mfunciones_generales->SanitizarData($arrDatos['con_segundo_nombre']); 
        $con_primera_pellido = $this->mfunciones_generales->SanitizarData($arrDatos['con_primera_pellido']); 
        $con_segundoa_pellido = $this->mfunciones_generales->SanitizarData($arrDatos['con_segundoa_pellido']); 
        $con_acteconomica_principal = $this->mfunciones_generales->SanitizarData($arrDatos['con_acteconomica_principal']); 
        $ddc_ciudadania_usa = $this->mfunciones_generales->SanitizarData($arrDatos['ddc_ciudadania_usa']); if($ddc_ciudadania_usa == '') { $error_texto .= $separador . $this->lang->line('ddc_ciudadania_usa'); }
        $ddc_motivo_permanencia_usa = $this->mfunciones_generales->SanitizarData($arrDatos['ddc_motivo_permanencia_usa']);
        
        $ws_cobis_codigo_resultado = $this->mfunciones_generales->SanitizarData($arrDatos['ws_cobis_codigo_resultado']); if($ws_cobis_codigo_resultado == '') { $error_texto .= $separador . 'Error flujo: ' . 'ws_cobis_codigo_resultado'; }
        $ws_segip_codigo_resultado = $this->mfunciones_generales->SanitizarData($arrDatos['ws_segip_codigo_resultado']); if($ws_segip_codigo_resultado == '') { $error_texto .= $separador . 'Error flujo: ' . 'ws_segip_codigo_resultado'; }
        $ws_segip_flag_verificacion = $this->mfunciones_generales->SanitizarData($arrDatos['ws_segip_flag_verificacion']); if($ws_segip_flag_verificacion == '') { $error_texto .= $separador . 'Error flujo: ' . 'ws_segip_flag_verificacion'; }
        $ws_selfie_codigo_resultado = $this->mfunciones_generales->SanitizarData($arrDatos['ws_selfie_codigo_resultado']); if($ws_selfie_codigo_resultado == '') { $error_texto .= $separador . 'Error flujo: ' . 'ws_selfie_codigo_resultado'; }
        $ws_ocr_codigo_resultado = $this->mfunciones_generales->SanitizarData($arrDatos['ws_ocr_codigo_resultado']); if($ws_ocr_codigo_resultado == '') { $error_texto .= $separador . 'Error flujo: ' . 'ws_ocr_codigo_resultado'; }
        $ws_ocr_name_valor = $this->mfunciones_generales->SanitizarData($arrDatos['ws_ocr_name_valor']); 
        $ws_ocr_name_similar = $this->mfunciones_generales->SanitizarData($arrDatos['ws_ocr_name_similar']); if($ws_ocr_name_similar == '') { $error_texto .= $separador . 'Error flujo: ' . 'ws_ocr_name_similar: ' . $ws_ocr_name_similar; }
        
        if($error_texto != '')
        {
            $arrError[0]['errorMessage'] = $this->lang->line('CamposObligatorios') . $error_texto;
            $this->response($arrError, 200); exit();
        }
        
        // Validar la Agencia Asociada
        
        $codigo_agencia_fie = $arrDatos['codigo_agencia_fie'];
        
        if($this->mfunciones_generales->ObtenerNombreRegionCodigo($codigo_agencia_fie) == '0')
        {
            $arrError[0]['errorMessage'] = 'La Agencia seleccionada no es correcta';
            $this->response($arrError, 200); exit();
        }
        
        // Recorre y guarda los documentos
        
            $conf_doc_validacion1 = '';
            $conf_doc_validacion2 = '';
            
            foreach ($arrDataDocumento as $key => $value) 
            {
                // Se valida que el código del documento exista
                if(isset($arrDatos[$value['documento_codigo']]))
                {
                    $arrDocumentos[] = array("doc"=>$arrDatos[$value['documento_codigo']], "id"=>$value['documento_id']);
                
                    // Se pregunta por las imagenes que serán validadas

                    if($arrConf[0]['conf_doc_validacion1'] == $value['documento_id'])
                    {
                        $conf_doc_validacion1 = $arrDatos[$value['documento_codigo']];
                    }

                    if($arrConf[0]['conf_doc_validacion2'] == $value['documento_id'])
                    {
                        $conf_doc_validacion2 = $arrDatos[$value['documento_codigo']];
                    }
                }
            }
                // Adicional manual auxiliar del cerificado SEGIP
                $arrDocumentos[] = array("doc"=>$arrDatos['dig_cert_segip'], "id"=>20);
            
            // ** REQ. 28/05 Ya no realizar la validación facial en el guardado
            
            //$rekognition = 0;
            //$similaridad_obtenida = 0;
            
            // Sólo si esta marcada la opción, se realizará la validación facial
            
            $rekognition = 1;
            $similaridad_obtenida = (int)$arrConf[0]['conf_rekognition_similarity'];
            
            //if($arrConf[0]['conf_rekognition'] == 1)
            if(1 == 2)
            {
                $rekognition = 1;
                
                // Validación Rekognition

                try
                {
                    if($conf_doc_validacion1 == '' || $conf_doc_validacion2 == '')
                    {
                        // Se guarda el Log para la auditoría
                        $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Se envió imagenes vacías para la validación de reconocimiento facial", '0,0', $accion_usuario, $accion_fecha);

                        $arrError[0] =  array(
                                            "error" => true,
                                            "errorMessage" => $arrConf[0]['conf_rekognition_texto_fallo'],
                                            "errorCode" => 98,
                                            "result" => array(
                                                "mensaje" => $this->lang->line('IncompletoApp')
                                            )
                                        );

                        $this->response($arrError, 200);
                        exit();
                    }

                    $args = [
                        'credentials' => [
                            'key' => $arrConf[0]['conf_rekognition_key'],
                            'secret' => $arrConf[0]['conf_rekognition_secret'],
                        ],
                        'region' => $arrConf[0]['conf_rekognition_region'],
                        'version' => 'latest'
                    ];

                    $client = new Aws\Rekognition\RekognitionClient($args);

                    $result_rekognition = $client->compareFaces([
                        'SimilarityThreshold' => (int)$arrConf[0]['conf_rekognition_similarity'],
                        'SourceImage' => [
                            'Bytes' => @file_get_contents($conf_doc_validacion1),

                        ],
                        'TargetImage' => [
                            'Bytes' => @file_get_contents($conf_doc_validacion2),
                        ],
                    ]);

                    if(isset($result_rekognition['FaceMatches'][0]))
                    {
                        $similaridad_obtenida = number_format($result_rekognition['FaceMatches'][0]['Similarity'], 2, '.', '.');
                    }
                    else
                    {
                        // Se guarda el Log para la auditoría
                        $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: No pasó la validación de reconocimiento facial", '0,0', $accion_usuario, $accion_fecha);

                        $arrError[0] =  array(
                                            "error" => true,
                                            "errorMessage" => $arrConf[0]['conf_rekognition_texto_fallo'],
                                            "errorCode" => 98,
                                            "result" => array(
                                                "mensaje" => $this->lang->line('IncompletoApp')
                                            )
                                        );

                        $this->response($arrError, 200);
                        exit();
                    }
                }
                catch(Exception $e)
                {
                    // Se guarda el Log para la auditoría
                    $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: " . $e, '0,0', $accion_usuario, $accion_fecha);

                    $arrError[0] =  array(
                                        "error" => true,
                                        "errorMessage" => $arrConf[0]['conf_rekognition_texto_fallo'],
                                        "errorCode" => 98,
                                        "result" => array(
                                            "mensaje" => $this->lang->line('IncompletoApp')
                                        )
                                    );

                    $this->response($arrError, 200);
                    exit();

                }
            }
        
        // Validaciones FIN
            
        // PASO 1: Guardar el registro
        
        $tipo_persona_id = 5; // <- Constante
        
        $arrResultado2 = $this->mfunciones_logica->InsertarProspecto_Terceros($identificador_ejecutivo, $identificador_afiliador, $tipo_persona_id, $codigo_agencia_fie, $rekognition, $similaridad_obtenida, 
                
                $tipo_cuenta,
                $direccion_email, 
                $coordenadas_geo_dom, 
                $coordenadas_geo_trab, 
                $envio, 
                $cI_numeroraiz, 
                $cI_complemento, 
                $cI_lugar_emisionoextension, 
                $cI_confirmacion_id, 
                $di_primernombre, 
                $di_segundo_otrosnombres, 
                $di_primerapellido, 
                $di_segundoapellido, 
                $this->mfunciones_generales->getFormatoFechaDateTime($di_fecha_nacimiento), 
                $this->mfunciones_generales->getFormatoFechaDateTime($di_fecha_vencimiento), 
                $di_indefinido, 
                $di_genero, 
                $di_estadocivil, 
                $di_apellido_casada, 
                $dd_profesion, 
                $dd_nivel_estudios, 
                $dd_dependientes, 
                $dd_proposito_rel_comercial, 
                $dec_ingresos_mensuales, 
                $dec_nivel_egresos, 
                $dir_tipo_direccion, 
                $dir_departamento, 
                $dir_provincia, 
                $dir_localidad_ciudad, 
                $dir_barrio_zona_uv, 
                $dir_ubicacionreferencial, 
                $dir_av_calle_pasaje, 
                $dir_edif_cond_urb, 
                $dir_numero, 
                
                $dir_departamento_neg, 
                $dir_provincia_neg, 
                $dir_localidad_ciudad_neg, 
                $dir_barrio_zona_uv_neg, 
                $dir_ubicacionreferencial_neg, 
                $dir_av_calle_pasaje_neg, 
                $dir_edif_cond_urb_neg, 
                $dir_numero_neg, 
                
                $dir_tipo_telefono, 
                $dir_notelefonico, 
                $ae_sector_economico, 
                $ae_actividad_fie, 
                $ae_ambiente, 
                $emp_nombre_empresa, 
                $emp_direccion_trabajo, 
                $emp_telefono_faxtrabaj, 
                $emp_tipo_empresa, 
                $emp_antiguedad_empresa, 
                $emp_codigo_actividad, 
                $emp_descripcion_cargo, 
                $this->mfunciones_generales->getFormatoFechaDateTime($emp_fecha_ingreso), 
                $rp_nombres, 
                $rp_primer_apellido, 
                $rp_segundo_apellido, 
                $rp_direccion, 
                $rp_notelefonicos, 
                $rp_nexo_cliente, 
                $con_primer_nombre, 
                $con_segundo_nombre, 
                $con_primera_pellido, 
                $con_segundoa_pellido, 
                $con_acteconomica_principal, 
                $ddc_ciudadania_usa, 
                $ddc_motivo_permanencia_usa, 
                $ws_cobis_codigo_resultado,
                $ws_segip_codigo_resultado,
                $ws_segip_flag_verificacion,
                
                $ws_selfie_codigo_resultado,
                $ws_ocr_codigo_resultado,
                
                $ws_ocr_name_valor,
                $ws_ocr_name_similar,
                
                $accion_usuario, $accion_fecha, $accion_fecha);
        
        // PASO 2: Crear la Carpeta donde se guardarán los PDF
        
        $codigo_terceros = $arrResultado2;
        
        $path = RUTA_TERCEROS . 'ter_' . $codigo_terceros;

        if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
        {
                mkdir($path, 0755, TRUE);
        }
        
            // Se crea el archivo html para evitar ataques de directorio
            $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
            write_file($path . '/index.html', $cuerpo_html);
        
        // PASO 3: Guardar las Imagenes
            
            foreach ($arrDocumentos as $key => $value) 
            {
                $prospecto_documento_pdf = $this->mfunciones_generales->GuardarDocumentoTercerosBase64($codigo_terceros, $value["id"], $value["doc"]);

                if($prospecto_documento_pdf->ok == FALSE)
                {
                    // Eliminar su directorio
                    
                    $this->mfunciones_generales->rrmdir(RUTA_TERCEROS . 'ter_' . $codigo_terceros);
                    $this->mfunciones_logica->EliminarRegistroTercero($codigo_terceros);
                    
                    $arrError[0] =  array(
                                        "error" => true,
                                        "errorMessage" => $prospecto_documento_pdf->texto,
                                        "errorCode" => 98,
                                        "result" => array(
                                            "mensaje" => $this->lang->line('IncompletoApp')
                                        )
                                    );

                    // Se guarda el Log para la auditoría
                    $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Imagen inválida de " . $prospecto_documento_pdf->texto, '0,0', $accion_usuario, $accion_fecha);
                    
                    $this->response($arrError, 200);
                    exit();
                }

                // Guardar en la DB

                $this->mfunciones_logica->InsertarDocumentoTercero($codigo_terceros, $value["id"], $prospecto_documento_pdf->nombre_documento, $accion_usuario, $accion_fecha);
            }
        
        // PASO 4: Se registra el Codigo Tercero en Prospectos para la relación
        
        $geo_prospecto = ($envio==1 ? $coordenadas_geo_dom : $coordenadas_geo_trab);
        
        $codigo_prospecto = $this->mfunciones_logica->InsertarRelacionTercerosProspecto($identificador_ejecutivo, $tipo_persona_id, $codigo_terceros, $cI_numeroraiz . ' ' . $cI_complemento, $di_primernombre . ' ' . $di_primerapellido . ' ' . $di_segundoapellido, $dir_notelefonico, $direccion_email, $geo_prospecto, $accion_usuario, $accion_fecha);
        
        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 1, -1, $accion_usuario, $accion_fecha, 0);
        // Dervia a Visita y Registro
        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 3, 1, $accion_usuario, $accion_fecha, 0);
        
        $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, 1, 0, 'Registro de Nuevo Onboarding No Asistido', $accion_usuario, $accion_fecha);

        
        // Se registra para las Etapas Onboarding   Pasa a: Onboarding   Etapa Nueva     Etapa Actual
        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 7, 3, $accion_usuario, $accion_fecha, 0);
        
            // PASO 4.1: Se registra la fecha en el calendario del Ejecutivo de Cuentas
                
            $cal_visita_ini = $accion_fecha;
                $cal_visita_fin = new DateTime($accion_fecha);
            $cal_visita_fin->add(new DateInterval('PT' . 30 . 'M'));
                $cal_visita_fin = $cal_visita_fin->format('Y-m-d H:i:s');

            $this->mfunciones_logica->InsertarFechaCaendario($identificador_ejecutivo, $codigo_prospecto, 1, $cal_visita_ini, $cal_visita_fin, $accion_usuario, $accion_fecha);
        
        
        // PASO 5: Se envía correo electrónico al registrado y notifica instancia
        
        $arrTerceros = $this->mfunciones_generales->DatosTercerosEmail($codigo_terceros);
        
        // Si se marcó la opción de envío de correo, se envía
        if($arrConf[0]['conf_onboarding_correo'] == 1)
        {
            $arrDocumentos = explode(",", $arrConf[0]['conf_onboarding_docs']);
            
            // En el caso que el array este vacio se muestra el mensaje de error
            if (!isset($arrDocumentos[0])) 
            {
                $lst_Documentos[0] = array();
            }
            else
            {
                $i = 0;

                foreach ($arrDocumentos as $key => $value) 
                {
                    $item_valor = array(
                        "documento_id" => $value
                    );
                    $lst_Documentos[$i] = $item_valor;

                    $i++;
                }
            }
            
            $correo_enviado = $this->mfunciones_generales->EnviarCorreo('terceros_recepcion', $arrTerceros[0]['terceros_email'], $arrTerceros[0]['terceros_nombre'], $arrTerceros, $lst_Documentos);
        }
        
        // DE ACUERDO A REQUERIMIENTO 21/05/2020 AHORA LA SOLICITUD PASA AUTOMÁTICAMENTE A APROBADO
        // Sólo se Aprueba el registro si no tiene el flag 1, caso contrario debe marcarse como Validación Operativa        $ws_segip_flag_verificacion 0=No  1=Si
        
        if($ws_segip_flag_verificacion == 1)
        {
            $this->mfunciones_logica->MarcarValOper($codigo_terceros);
        }
        else
        {
           // *** Llamar a función para Aprobar y Flujo COBIS en 2do plano ***
           $this->mfunciones_generales->Aprobar_FlujoCobis_background($codigo_terceros, $codigo_usuario, $accion_usuario);
        }
        
            // Se guarda el Log para la auditoría
            $arrDatos['dig_firma'] = 'imagen-guardada';
            $arrDatos['dig_ci_anv'] = 'imagen-guardada';
            $arrDatos['dig_ci_rev'] = 'imagen-guardada';
            $arrDatos['dig_selfie'] = 'imagen-guardada';
            $arrDatos['dig_cert_segip'] = 'codigo_segip ' . $ws_segip_codigo_resultado;
            $arrDatos['cod_initium'] = (string)$codigo_terceros;

            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), '0,0', $accion_usuario, $accion_fecha);
        
        // Notificar Proceso Onboarding     0=No Regionalizado      1=Si Regionalizado
        $this->mfunciones_generales->NotificacionEtapaTerceros($codigo_terceros, 7, 0);
        
        // AL CONCLUIR EL REGISTRO SE PROCEDE A ELIMINAR EL TOKEN TEMPORAL
            $this->mfunciones_logica->token_delete_id($arrDatos['token']);
        
        $lst_resultado[0] = array(
            //"codigo_registro" => $codigo_terceros, //Just for development
            "mensaje" => ($arrConf[0]['conf_texto_respuesta']!='' ? $arrConf[0]['conf_texto_respuesta']: '')
        );

        return $lst_resultado;
        
    }
    
    function External__Register_Steap($arrDatos, $usuarioAPP, $nombre_servicio, $codigo_usuario, $perfil_app){

		// Primero se verifica si el Perfil App es Afiliador Tercero "5"
        
        if ($perfil_app != 2) 
        {
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'No cuenta con el Perfil App Afiliador Tercero, por favor comuníquese con el Administrador del sistema.',
                            "errorCode" => 91,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
            );

            $this->response($arrError, 200);
            exit();			
        }
		
		// Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_afiliador", 
                "ci", 
                "paso_actual",
                "ip"
            );
        
        $accion_usuario = $usuarioAPP;
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Existen parámetros de entrada faltantes, revise el listado de campos del servicio.", '0,0', $accion_usuario, $accion_fecha);
            
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', '),
                            "errorCode" => 99,
                            "result" => array(
                                "mensaje" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', ')
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        $codigo_afiliador = $arrDatos['codigo_afiliador'];
        
        $ci = $arrDatos['ci'];
        $paso_actual = $arrDatos['paso_actual'];
        $ip = $arrDatos['ip'];
        
        // Se guarda el Log para la auditoría
        $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "{'codigo_afiliador': '" . $codigo_afiliador . "', 'ci': '" . $ci . "', 'paso_actual': '" . $paso_actual . "' , 'ip': '" . $ip . "' }", '0,0', $accion_usuario, $accion_fecha);
        	
	$lst_resultado[0] = array(
            "mensaje" => "Registrado."
        );

        return $lst_resultado;
		
    }
    
    function External__Segip_Vigencia($arrDatos, $usuarioAPP, $nombre_servicio, $codigo_usuario, $perfil_app){

		// Primero se verifica si el Perfil App es Afiliador Tercero "5"
        
        if ($perfil_app != 2) 
        {
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'No cuenta con el Perfil App Afiliador Tercero, por favor comuníquese con el Administrador del sistema.',
                            "errorCode" => 91,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
            );

            $this->response($arrError, 200);
            exit();			
        }
		
		// Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_afiliador"
            );
        
        $accion_usuario = $usuarioAPP;
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Existen parámetros de entrada faltantes, revise el listado de campos del servicio.", '0,0', $accion_usuario, $accion_fecha);
            
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', '),
                            "errorCode" => 99,
                            "result" => array(
                                "mensaje" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', ')
                            )
            );

            $this->response($arrError, 200);
            exit();
        }

	$lst_resultado[0] = array(
            "vigencia_fecha_ini" => SEGIP_VIGENCIA_FECHA_INI,
            "vigencia_fecha_fin" => SEGIP_VIGENCIA_FECHA_FIN,
        );

        return $lst_resultado;
		
    }
    
    //-- Req. Validacion COBIS-SEGIP
    
    function External__Consulta_Cliente($arrDatos, $usuarioAPP, $nombre_servicio, $codigo_usuario, $perfil_app){

		// Primero se verifica si el Perfil App es Afiliador Tercero "5"
        
        if ($perfil_app != 2) 
        {
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'No cuenta con el Perfil App Afiliador Tercero, por favor comuníquese con el Administrador del sistema.',
                            "errorCode" => 91,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
            );

            $this->response($arrError, 200);
            exit();			
        }
		
		// Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_afiliador",
                "dir_localidad_ciudad",
                "ddc_ciudadania_usa",
                "ddc_motivo_permanencia_usa",
                "tipo_cuenta",
                "cI_numeroraiz",
                "cI_complemento",
                "cI_lugar_emisionoextension",
                "di_fecha_nacimiento",
                "di_fecha_vencimiento",
                "di_indefinido"
            );
        
        $accion_usuario = $usuarioAPP;
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Existen parámetros de entrada faltantes, revise el listado de campos del servicio.", '0,0', $accion_usuario, $accion_fecha);
            
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', '),
                            "errorCode" => 99,
                            "result" => array(
                                "mensaje" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', ')
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        $codigo_afiliador = $arrDatos['codigo_afiliador'];
        
        // VALIDACIONES DEL AFILIADOR
        
            $arrResultado3 = $this->mfunciones_logica->VerificaAfiliadorTercero($codigo_usuario, $codigo_afiliador);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            if (!isset($arrResultado3[0])) 
            {
                // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
                $arrError[0] =  array(
                                "error" => true,
                                "errorMessage" => 'Credenciales incorrectas. Key del Afiliador.',
                                "errorCode" => 91,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
            else
            {
                $identificador_afiliador = $arrResultado3[0]["afiliador_id"];
                $identificador_ejecutivo = $arrResultado3[0]["ejecutivo_id"];
            }
        
        // Validaciones INICIO

        $arrError[0] =  array(
                "error" => true,
                "errorMessage" => $this->lang->line('CamposObligatorios'),
                "errorCode" => 96,
                "result" => array(
                    "mensaje" => $this->lang->line('IncompletoApp')
                )
        );
        
        $separador = '<br /> - ';
        $error_texto = '';

        $ciNumber = (int)$this->mfunciones_generales->SanitizarData($arrDatos['cI_numeroraiz']); if($ciNumber == '') { $error_texto .= $separador . $this->lang->line('cI_numeroraiz'); }
        $complement = $this->mfunciones_generales->SanitizarData($arrDatos['cI_complemento']);
        $extension = $this->mfunciones_generales->SanitizarData($arrDatos['cI_lugar_emisionoextension']);
        $birthDate = $arrDatos['di_fecha_nacimiento']; if($this->mfunciones_generales->VerificaFechaD_M_Y($birthDate) == false) { $error_texto .= $separador . $this->lang->line('di_fecha_nacimiento'); }

        if($error_texto != '')
        {
            $arrError[0]['errorMessage'] = $this->lang->line('CamposObligatorios') . $error_texto;
            $this->response($arrError, 200); exit();
        }
        
        // Quitar espacios
            $complement = str_replace(' ', '', $complement);
            $extension = str_replace(' ', '', $extension);
            $birthDate = str_replace(' ', '', $birthDate);
        
        $segipValidation = true; // <- Valor constante
            
        // Validaciones FIN
            
            // Obtener parámetros de configuración
            $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
            
        // PASO 0: Se setea los valores por defecto
        
        $aux_error_cobis = 0;
        $aux_error_segip = 0;
            
        $ws_cobis_codigo_resultado = 2;
        $ws_segip_codigo_resultado = 2;
        $ws_segip_flag_verificacion = 0; // <-- 0 = Sin flag
        
        // Llamar a la función para generar el token
        $token_generado = $this->mfunciones_generales->TokenOnboarding_generar($accion_usuario, $accion_fecha);
        
        if($token_generado == FALSE)
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Generación del Token", '0,0', $accion_usuario, $accion_fecha);
            
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'Error al generar el token.',
                            "errorCode" => 99,
                            "result" => array(
                                "mensaje" => 'Error al generar el token.'
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        // PASO 1: CONSULTA A COBIS y SEGIP
        
        // ** LÓGICA CONSULTA WS COBIS INICIO

        $contador_cobis_segip = 0;
        
        // Bucle de reintentos parametrizado <-- Se solicitó que se quite los intentos
        while ($contador_cobis_segip < $arrConf[0]['conf_segip_intentos'])
        {
            $contador_cobis_segip++;
            
            // Armar parámetros y consumir WS de COBIS y SEGIP
            $param__end_point = $arrConf[0]['conf_cobis_ws_uri'];
            $parametros = array(
                'birthDate' => $birthDate,
                'ciNumber' => $ciNumber,
                'complement' => $complement,
                'extension' => $extension,
                'segipValidation' => $segipValidation
            );
            
            $resultado_soa_fie = $this->mfunciones_generales->Cliente_SOA_FIE('', $param__end_point, $parametros, $accion_usuario, $accion_fecha);
        
                // Auxiliar - Actualizar el token onboarding en el registro de auditoria
                $aux_token = $this->mfunciones_generales->TokenOnboarding_parametros($token_generado, 'token_id');
                if ($aux_token == FALSE) 
                {
                    // Se guarda el Log para la auditoría
                    $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Sesión expirada o Token inválido/expirado al actualizar el token.", '0,0', $accion_usuario, $accion_fecha);

                    // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
                    $arrError[0] =  array(
                                    "error" => true,
                                    "errorMessage" => $arrConf[0]['conf_token_texto'],
                                    "errorCode" => 300,
                                    "result" => array(
                                        "mensaje" => $arrConf[0]['conf_token_texto']
                                    )
                    );

                    $this->response($arrError, 200);
                    exit();
                }
                $this->mfunciones_logica->UpdateToken_WS_SOA_FIE($token_generado . '_' . $aux_token['token_id'], $ciNumber, $resultado_soa_fie->ws_code_audit);
            
            if($resultado_soa_fie->ws_error == TRUE)
            {
                $arrError[0] =  array(
                                "error" => true,
                                "errorMessage" => $this->lang->line('ws_soa_fie_error'),
                                "errorCode" => 99,
                                "result" => array(
                                    "mensaje" => $this->lang->line('ws_soa_fie_error')
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
            
            // Se captura los parámetros
        
            // ----- Inicio
            
                // ***** Caso especial, se obtiene el JWT para guardarlo
                    $ws_token = $resultado_soa_fie->ws_token;
                // ***** Caso especial, se obtiene el JWT para guardarlo
                
            $soa_fie_capturado = $resultado_soa_fie->ws_result['result'];

            $fieClient = $soa_fie_capturado['fieClient'] ? true : false;

            // Se valida que el parámetro "segipResponse" exista
            if($fieClient == true && !isset( $soa_fie_capturado['segipResponse']))
            {
                $valid = false;
                $ws_segip_codigo_resultado = $code = 0;
                $document = '';
                $validationRequired = true;
            }
            else
            {
                $valid = $soa_fie_capturado['segipResponse']['valid'] ? true : false;
                $ws_segip_codigo_resultado = $code = (int)$soa_fie_capturado['segipResponse']['code'];
                $document = $soa_fie_capturado['segipResponse']['document'];
                $validationRequired = $soa_fie_capturado['validationRequired'] ? true : false;
            }
            
            // ----- Fin
            
            // ** NO SATISFACTORIO
            // ERROR COBIS
            if ($fieClient == true) // <-- si fieClient es TRUE significa que está activo
            {
                // Si la validación COBIS es bloqueante se responde el error
                if($arrConf[0]['conf_cobis_mandatorio'] == 1)
                {
                    // Se guarda el Log para la auditoría
                    $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Error controlado del WS validate-client COBIS mandatorio", '0,0', $accion_usuario, $accion_fecha);

                    $arrError[0] =  array(
                                    "error" => true,
                                    "errorMessage" => $arrConf[0]['conf_cobis_texto'],
                                    "errorCode" => 300,
                                    "result" => array(
                                        "mensaje" => $arrConf[0]['conf_cobis_texto']
                                    )
                    );

                    $this->response($arrError, 200);
                    exit();
                }

                // Se asigna el código de error parametrizado (si no viene con un código en la respuesta) y activar flag
                $ws_cobis_codigo_resultado = $arrConf[0]['conf_cobis_tipo_error'];
                $aux_error_cobis = 1;
            }
            
            // ** NO SATISFACTORIO
            // ERROR SEGIP
            if ($code != 2) // <-- Si es diferente a código 2 es error
            {
                // Si la validación SEGIP es mandatorio se responde el error
                if($arrConf[0]['conf_segip_mandatorio'] == 1)
                {
                    // Se guarda el Log para la auditoría
                    $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Error controlado del WS validate-client SEGIP mandatorio", '0,0', $accion_usuario, $accion_fecha);

                    // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
                    $arrError[0] =  array(
                                    "error" => true,
                                    "errorMessage" => $arrConf[0]['conf_segip_texto'],
                                    "errorCode" => 300,
                                    "result" => array(
                                        "mensaje" => $arrConf[0]['conf_segip_texto']
                                    )
                    );

                    $this->response($arrError, 200);
                    exit();
                }

                // Se asigna el código de error parametrizado y activar flag
                $aux_error_segip = 1;
            }
            
            // !! FIE indicó que sólo se intente 1 vez !!
            break;
        }
        
        // ** LÓGICA CONSULTA WS COBIS FIN
        
        // PASO 2: ASIGNAR FLAG Y GUARDAR PARÁMETROS Y CERTIFICADO SEGIP

        $ws_segip_flag_verificacion = $validationRequired == true ? 1 : 0;
        
        // AQUI se recibe el parámetro del Certificado SEGIP PDF base64
        $certificado_segip = $document;
        
        // PASO 3: SE COMPLETA CONSUMO Y VALIDACIÓN WS COBIS Y SEGIP        SE GENERA EL TOKEN
        
        // Se quita el codigo afiliador para guardar la data
        unset($arrDatos['codigo_afiliador']);
        
            // Se actualiza el token con los parámetros obtenidos
            $arrDatos = array_merge($arrDatos, array(
                'ws_cobis_codigo_resultado' => (string)$ws_cobis_codigo_resultado,
                'ws_segip_codigo_resultado' => (string)$ws_segip_codigo_resultado,
                'ws_segip_flag_verificacion' => (string)$ws_segip_flag_verificacion,
                'selfie_captura_intentos' => '1', // <- Se setea los intentos como '1'
                'ocr_captura_intentos' => '1' // <- Se setea los intentos como '1'
                )
            );
            
        // Actualizar JWT, array con los valores y certificado SEGIP
        $this->mfunciones_logica->token_update_aux($ws_token, json_encode($arrDatos), $certificado_segip, $token_generado);
        
            
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), '0,0', $accion_usuario, $accion_fecha);
            
	$lst_resultado[0] = array(
            "token" => $token_generado
        );

        return $lst_resultado;
		
    }
    
    function External__Token_Update($arrDatos, $usuarioAPP, $nombre_servicio, $codigo_usuario, $perfil_app){

		// Primero se verifica si el Perfil App es Afiliador Tercero "5"
        
        if ($perfil_app != 2) 
        {
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'No cuenta con el Perfil App Afiliador Tercero, por favor comuníquese con el Administrador del sistema.',
                            "errorCode" => 91,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
            );

            $this->response($arrError, 200);
            exit();			
        }
		
		// Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_afiliador",
                "token",
                "dir_notelefonico",
                "host_text"
            );
        
        $accion_usuario = $usuarioAPP;
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Existen parámetros de entrada faltantes, revise el listado de campos del servicio.", '0,0', $accion_usuario, $accion_fecha);
            
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', '),
                            "errorCode" => 99,
                            "result" => array(
                                "mensaje" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', ')
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        $codigo_afiliador = $arrDatos['codigo_afiliador'];
        
        // VALIDACIONES DEL AFILIADOR
        
            $arrResultado3 = $this->mfunciones_logica->VerificaAfiliadorTercero($codigo_usuario, $codigo_afiliador);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            if (!isset($arrResultado3[0])) 
            {
                // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
                $arrError[0] =  array(
                                "error" => true,
                                "errorMessage" => 'Credenciales incorrectas. Key del Afiliador.',
                                "errorCode" => 91,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
            else
            {
                $identificador_afiliador = $arrResultado3[0]["afiliador_id"];
                $identificador_ejecutivo = $arrResultado3[0]["ejecutivo_id"];
            }
        
        // Validaciones INICIO
            
        // LOGICA PARA VALIDAR EL TOKEN
        
            // Obtener parámetros de configuración
            $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
            
        // Crear funcion general para 1=Limpiar los token expirados     2=Buscar el token       3=Si existe obtener la data y borrar el token   | No existe devolver mensaje de error
        
        $parametros_token = $this->mfunciones_generales->TokenOnboarding_parametros($arrDatos['token']);
        
        if ($parametros_token == FALSE) 
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Sesión expirada o Token inválido/expirado al actualizar el token.", '0,0', $accion_usuario, $accion_fecha);

            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => $arrConf[0]['conf_token_texto'],
                            "errorCode" => 300,
                            "result" => array(
                                "mensaje" => $arrConf[0]['conf_token_texto']
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
            // Intregación WS SMS
            $numero_celular = str_replace(" ", "", $this->mfunciones_generales->SanitizarData($arrDatos['dir_notelefonico']));
            $host_text = htmlspecialchars($arrDatos['host_text']);
        
        // Se actualiza el token con los parámetros obtenidos
        $array_resultado = array_merge($parametros_token, array(
            'dir_notelefonico' => $arrDatos['dir_notelefonico']
            )
        );
        
        // Actualizar los parámetros con el nuevo valor en JSON
        
        $this->mfunciones_logica->token_update_parametro(json_encode($array_resultado), $arrDatos['token'], $accion_usuario, $accion_fecha);
        
        
            // Se quitan los códigos de respuesta, quedando sólo los valores
            unset(
                $arrDatos['ws_cobis_codigo_resultado'],
                $arrDatos['ws_segip_codigo_resultado'],
                $arrDatos['ws_segip_flag_verificacion'],
                $arrDatos['token_jwt'],
                $arrDatos['dig_cert_segip'],
                $arrDatos['dig_selfie'],
                $arrDatos['dig_ci_anv'],
                $arrDatos['dig_ci_rev'],
                $arrDatos['dig_firma'],
                $arrDatos['selfie_captura_intentos'],
                $arrDatos['ocr_captura_intentos'],
                $arrDatos['dir_notelefonico'],
                $arrDatos['ws_selfie_codigo_resultado'],
                $arrDatos['ws_ocr_codigo_resultado'],
                $arrDatos['ws_ocr_name_valor'],
                $arrDatos['host_text']
            );
            
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), '0,0', $accion_usuario, $accion_fecha);
        
        // #######################################

        // Req. Cambio de Servicio Envío SMS
        
        $texto_respuesta = '';
            
        if($arrConf[0]['conf_sms_onb_ambiente'] == 1)
        {
            // Ambiente de desarrollo habilitado
            
            $lst_resultado[0] = array(
                "mensaje" => $this->lang->line('sms_onb_ambiente_devel_sms'),
                "development" => $arrConf[0]['conf_sms_onb_ambiente']
            );

            return $lst_resultado;
        }
        
        // Consumo del servicio SMS
        
        // PASO 0
        // -- Limpieza de números celulares y bloqueados que haya cumplido sus tiempos
        $this->mfunciones_logica->set_SMS_clean((int)$arrConf[0]['conf_sms_permitido_tiempo']);
        
            // -- Seteo de errores
        
            $arrError_bloqueado[0] =  array(
                "error" => true,
                "errorMessage" => $arrConf[0]['conf_sms_permitido_txt_error'],
                "errorCode" => 300,
                "result" => array(
                    "mensaje" => $arrConf[0]['conf_sms_permitido_txt_error']
                )
            );
            
            $arrError_noenviado[0] =  array(
                "error" => true,
                "errorMessage" => $this->lang->line('sms_onb_ambiente_prod_error_noenviado'),
                "errorCode" => 400,
                "result" => array(
                    "mensaje" => $this->lang->line('sms_onb_ambiente_prod_error_noenviado')
                )
            );
        
        // -- Consultar si el número de celular está en tabla de bloqueo
        $ListarBloqueados = $this->mfunciones_logica->ListarCelular_SMS_bloqueados($numero_celular);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($ListarBloqueados);

        // -- Si el número de celular está registrado en la tabla de bloqueo, se termina el flujo y muestra el error
        if (isset($ListarBloqueados[0])) 
        {
            $this->response($arrError_bloqueado, 200);
            exit();
        }
        
        // PASO 1: Solicitar el SMS
        
        // -- Listar los casos registrados con el número de celular en la misma fecha
        $ListarCelRegistrados = $this->mfunciones_logica->ListarCelular_SMS_MismaFecha($numero_celular);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($ListarCelRegistrados);

        // -- Se cuenta la cantidad de registros encontrados, si es >= a la cantidad permitida
        if (isset($ListarCelRegistrados[0])) 
        {
            if(count($ListarCelRegistrados) >= (int)$arrConf[0]['conf_sms_permitido_cantidad'])
            {
                // Si es >= a la cantidad permitida, se marca el número de celular como bloqueado y se responde al usuario terminando el flujo
                $this->mfunciones_logica->set_Celular_Bloqueado($numero_celular, $accion_fecha);
                
                $this->response($arrError_bloqueado, 200);
                exit();
            }
        }
        
        // PASO 2: Generar el PIN
        
        // -- Llamar a la función para generar el token
        $pin_generado = $this->mfunciones_generales->PIN_Onboarding_generar($numero_celular);
        
        // Si no se pudo generar el PIN, se muestra el error y se detiene el flujo
        if($pin_generado == FALSE)
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Generación del PIN de SMS", '0,0', $accion_usuario, $accion_fecha);
            
            $this->response($arrError_noenviado, 200);
            exit();
        }
        
        // PASO 3: CONSUMIR EL WS DE SMS
        
        // -- Armar los parámetros de envío
        
        $parametros_sms = array(
            "name" => $arrConf[0]['conf_sms_name_plantilla'],
            "message" => $pin_generado . " es tu PIN para el registro en BANCO FIE.",
            "to" => array(
                $numero_celular
            ),
            "channelId" => (int)$arrConf[0]['conf_sms_channelid']
        );
        
        $resultado_soa_fie = $this->mfunciones_generales->Cliente_SOA_FIE_Generico($arrDatos['token'], $arrConf[0]['conf_credito_notificar_sms_uri'], $parametros_sms, $accion_usuario, $accion_fecha);
        
        if($resultado_soa_fie->ws_error == TRUE)
        {
            $this->response($arrError_noenviado, 200);
            exit();
        }
        
        switch ((int)$resultado_soa_fie->ws_httpcode) {
            
            case 200:
                
                // Enviado correcto, se procede a
                // -- Inactivar todos los registros del número de celular
                $this->mfunciones_logica->set_Celular_UpdateActivo($numero_celular);
                
                // -- Registrar nueva fila con el número de celular y PIN generado en la tabla
                $this->mfunciones_logica->set_Celular_Enviado($numero_celular, $pin_generado, $accion_fecha);
                
                $texto_respuesta = $this->lang->line('sms_onb_ambiente_prod_enviado');
                
                break;
            
            default:
                
                // Cualquier otra respuesta HTTP, se marca como no enviado.
                
                $this->response($arrError_noenviado, 200);
                exit();
                
                break;
        }
        
	$lst_resultado[0] = array(
                "mensaje" => $texto_respuesta,
                "development" => $arrConf[0]['conf_sms_onb_ambiente']
            );

        return $lst_resultado;
    }
    
    function External__SMS_PIN($arrDatos, $usuarioAPP, $nombre_servicio, $codigo_usuario, $perfil_app){

		// Primero se verifica si el Perfil App es Afiliador Tercero "5"
        
        if ($perfil_app != 2) 
        {
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'No cuenta con el Perfil App Afiliador Tercero, por favor comuníquese con el Administrador del sistema.',
                            "errorCode" => 91,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
            );

            $this->response($arrError, 200);
            exit();			
        }
		
		// Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_afiliador",
                "dir_notelefonico",
                "pin"
            );
        
        $accion_usuario = $usuarioAPP;
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Existen parámetros de entrada faltantes, revise el listado de campos del servicio.", '0,0', $accion_usuario, $accion_fecha);
            
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', '),
                            "errorCode" => 99,
                            "result" => array(
                                "mensaje" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', ')
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        $codigo_afiliador = $arrDatos['codigo_afiliador'];
        
        // VALIDACIONES DEL AFILIADOR
        
            $arrResultado3 = $this->mfunciones_logica->VerificaAfiliadorTercero($codigo_usuario, $codigo_afiliador);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            if (!isset($arrResultado3[0])) 
            {
                // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
                $arrError[0] =  array(
                                "error" => true,
                                "errorMessage" => 'Credenciales incorrectas. Key del Afiliador.',
                                "errorCode" => 91,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
            else
            {
                $identificador_afiliador = $arrResultado3[0]["afiliador_id"];
                $identificador_ejecutivo = $arrResultado3[0]["ejecutivo_id"];
            }
        
            // Obtener parámetros de configuración
            $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);

        // Req. Cambio de Servicio Envío SMS
        
        $numero_celular = str_replace(" ", "", $this->mfunciones_generales->SanitizarData($arrDatos['dir_notelefonico']));
        $pin = str_replace(" ", "", $arrDatos['pin']);
            
        if($arrConf[0]['conf_sms_onb_ambiente'] == 1)
        {
            // Ambiente de desarrollo habilitado
            
            $lst_resultado[0] = array(
                "mensaje" => $this->lang->line('sms_onb_ambiente_devel_sms')
            );

            return $lst_resultado;
        }
        
        // PASO 0
        // -- Cambiar de estado a Inactivo a todos los PIN que hayan superado el tiempo de vigencia
        $this->mfunciones_logica->set_CelularCaducado_UpdateActivo($numero_celular, (int)$arrConf[0]['conf_sms_tiempo_validez']);
            
        // PASO 1: Validar el PIN, se pregunta la combinación de número celular, PIN y que esté activo (vigente)
        $ListarMatch = $this->mfunciones_logica->pin_existe($numero_celular, $pin, 1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($ListarMatch);

        // -- Si el número de celular u PIN (activo) no hacen match, se termina el flujo y muestra el error
        if (!isset($ListarMatch[0])) 
        {
            $arrError_PIN_incorrecto[0] =  array(
                "error" => true,
                "errorMessage" => $this->lang->line('sms_onb_ambiente_prod_error_pin'),
                "errorCode" => 400,
                "result" => array(
                    "mensaje" => $this->lang->line('sms_onb_ambiente_prod_error_pin')
                )
            );
            
            $this->response($arrError_PIN_incorrecto, 200);
            exit();
        }

        // -- Si se hace match, se marca como inactivo todos los PIN del número de celular
        $this->mfunciones_logica->set_Celular_UpdateActivo($numero_celular);
        
        $lst_resultado[0] = array(
                "mensaje" => $this->lang->line('sms_onb_ambiente_prod_pin')
            );

        return $lst_resultado;
		
    }
    
    function External__Token_Validate($arrDatos, $usuarioAPP, $nombre_servicio, $codigo_usuario, $perfil_app){

		// Primero se verifica si el Perfil App es Afiliador Tercero "5"
        
        if ($perfil_app != 2) 
        {
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'No cuenta con el Perfil App Afiliador Tercero, por favor comuníquese con el Administrador del sistema.',
                            "errorCode" => 91,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
            );

            $this->response($arrError, 200);
            exit();			
        }
		
		// Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_afiliador",
                "token"
            );
        
        $accion_usuario = $usuarioAPP;
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Existen parámetros de entrada faltantes, revise el listado de campos del servicio.", '0,0', $accion_usuario, $accion_fecha);
            
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', '),
                            "errorCode" => 99,
                            "result" => array(
                                "mensaje" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', ')
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        $codigo_afiliador = $arrDatos['codigo_afiliador'];
        
        // VALIDACIONES DEL AFILIADOR
        
            $arrResultado3 = $this->mfunciones_logica->VerificaAfiliadorTercero($codigo_usuario, $codigo_afiliador);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            if (!isset($arrResultado3[0])) 
            {
                // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
                $arrError[0] =  array(
                                "error" => true,
                                "errorMessage" => 'Credenciales incorrectas. Key del Afiliador.',
                                "errorCode" => 91,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
            else
            {
                $identificador_afiliador = $arrResultado3[0]["afiliador_id"];
                $identificador_ejecutivo = $arrResultado3[0]["ejecutivo_id"];
            }
        
        // Validaciones INICIO
        
        // LOGICA PARA VALIDAR EL TOKEN
        
            // Obtener parámetros de configuración
            $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
                
        // Para la validación siempre se exige que el token esté activo
        $parametros_token = $this->mfunciones_generales->TokenOnboarding_parametros($arrDatos['token'], '', 1);
        
        if ($parametros_token == FALSE) 
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Sesión expirada o Token inválido/expirado al actualizar el token.", '0,0', $accion_usuario, $accion_fecha);

            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => $arrConf[0]['conf_token_texto'],
                            "errorCode" => 300,
                            "result" => array(
                                "mensaje" => $arrConf[0]['conf_token_texto']
                            )
            );

            $this->response($arrError, 200);
            exit();
        }

            // Se captura el parámetro de configuración, si el token es One Time Password       0=No        1=Si
            
            $opcion_otp = $arrConf[0]['conf_token_otp'];
            
        // Si el parámetro de configuración, si el token es One Time Password
        
        if($opcion_otp == 1)
        {
            $this->mfunciones_logica->token_update_activo($arrDatos['token'], $accion_usuario, $accion_fecha);
        }
        
            // Se quitan los códigos de respuesta, quedando sólo los valores
            unset(
                $parametros_token['ws_cobis_codigo_resultado'],
                $parametros_token['ws_segip_codigo_resultado'],
                $parametros_token['ws_segip_flag_verificacion'],
                $parametros_token['token_jwt'],
                $parametros_token['dig_cert_segip'],
                $parametros_token['dig_selfie'],
                $parametros_token['dig_ci_anv'],
                $parametros_token['dig_ci_rev'],
                $parametros_token['dig_firma'],
                $parametros_token['selfie_captura_intentos'],
                $parametros_token['ocr_captura_intentos'],
                $parametros_token['ws_selfie_codigo_resultado'],
                $parametros_token['ws_ocr_codigo_resultado'],
                $parametros_token['ws_ocr_name_valor'],
                $parametros_token['ws_ocr_name_similar'],
                $parametros_token['ws_code_audit']
            );
            
        // Se envian los valores
        
	$lst_resultado[0] = $parametros_token;

        return $lst_resultado;
		
    }
    
    function External__Widget_Selfie($arrDatos, $usuarioAPP, $nombre_servicio, $codigo_usuario, $perfil_app){

		// Primero se verifica si el Perfil App es Afiliador Tercero "5"
        
        if ($perfil_app != 2) 
        {
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'No cuenta con el Perfil App Afiliador Tercero, por favor comuníquese con el Administrador del sistema.',
                            "errorCode" => 91,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
            );

            $this->response($arrError, 200);
            exit();			
        }
		
		// Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_afiliador",
                "token",
                "dig_selfie",
                "template"
            );
        
        $accion_usuario = $usuarioAPP;
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Existen parámetros de entrada faltantes, revise el listado de campos del servicio.", '0,0', $accion_usuario, $accion_fecha);
            
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', '),
                            "errorCode" => 99,
                            "result" => array(
                                "mensaje" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', ')
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        $codigo_afiliador = $arrDatos['codigo_afiliador'];
        
        // VALIDACIONES DEL AFILIADOR
        
            $arrResultado3 = $this->mfunciones_logica->VerificaAfiliadorTercero($codigo_usuario, $codigo_afiliador);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            if (!isset($arrResultado3[0])) 
            {
                // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
                $arrError[0] =  array(
                                "error" => true,
                                "errorMessage" => 'Credenciales incorrectas. Key del Afiliador.',
                                "errorCode" => 91,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
            else
            {
                $identificador_afiliador = $arrResultado3[0]["afiliador_id"];
                $identificador_ejecutivo = $arrResultado3[0]["ejecutivo_id"];
            }
        
        // Validaciones INICIO
        
            // Obtener parámetros de configuración
            $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
            
        $parametros_token = $this->mfunciones_generales->TokenOnboarding_parametros($arrDatos['token'], 'documento');

        if ($parametros_token == FALSE) 
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Sesión expirada o Token inválido/expirado al actualizar el token.", '0,0', $accion_usuario, $accion_fecha);

            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => $arrConf[0]['conf_token_texto'],
                            "errorCode" => 300,
                            "result" => array(
                                "mensaje" => $arrConf[0]['conf_token_texto']
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        // Validar que la imagen base64 sea correcta
        if ($this->mfunciones_generales->validar_imagen64_size($arrDatos['dig_selfie'], $arrConf[0]['conf_img_width_min'], $arrConf[0]['conf_img_height_min'])){
            
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Imagen Base64", '0,0', $accion_usuario, $accion_fecha);
            
            // Si no es base64 correcto, se muestra el error 400
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'La imagen no fue procesada, por favor vuelva a intentar.',
                            "errorCode" => 400,
                            "result" => array(
                                "mensaje" => 'La imagen no fue procesada, por favor vuelva a intentar.'
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        
            // Para empezar, se captura el valor actual del Flag SEGIP
            // Sólo si se marca el flag como "1" se asigna nuevamente
            $ws_segip_flag_verificacion = $parametros_token['ws_segip_flag_verificacion'];
            
        // LÓGICA CONSUMIR SERVICIO Y REALIZAR ACCIONES INICIO
            
            // Armar parámetros y consumir WS de Prueba de VIda
            $param__end_point = $arrConf[0]['conf_life_ws_uri'];
            $parametros = array(
                'document' => ((int)$parametros_token['ws_segip_codigo_resultado'] == 2 ? $parametros_token['dig_cert_segip'] : ''),
                'image' => preg_replace('#^data:image/[^;]+;base64,#', '', $arrDatos['dig_selfie']), // <-- Strip the data:image part
                'template' => $arrDatos['template'],
                'tokenClient' => $arrDatos['token']
            );
            
            $resultado_soa_fie = $this->mfunciones_generales->Cliente_SOA_FIE($arrDatos['token'], $param__end_point, $parametros, $accion_usuario, $accion_fecha);
            
            if($resultado_soa_fie->ws_error == TRUE)
            {
                $arrError[0] =  array(
                                "error" => true,
                                "errorMessage" => $this->lang->line('ws_soa_fie_error'),
                                "errorCode" => 99,
                                "result" => array(
                                    "mensaje" => $this->lang->line('ws_soa_fie_error')
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
            // Se captura los parámetros
        
            // ----- Inicio
            
            $soa_fie_capturado = $resultado_soa_fie->ws_result['result'];

            $livenessDiagnostic = $soa_fie_capturado['livenessDiagnostic'];
            $compareSelfieSegip = $soa_fie_capturado['compareSelfieSegip'] ? true : false;
            $validationRequired = $soa_fie_capturado['validationRequired'] ? true : false;
            
            // ----- Fin
            
            // Lógica de Reintentos cuando el servicio indica re-intentar | Prueba de Vida      Se controla los intentos guardados en el token, debe ser <= al configurado     Por cada error, se incrementa el valor de los intentos
            
            if(strtolower($livenessDiagnostic) != 'live') // <-- Si la prueba de vida devuelve cualquier valor que no se "Live" (por seguridad se transforma en minúsculas) se pide reintentar
            {
                $selfie_captura_intentos = $parametros_token['selfie_captura_intentos'];
                    // Se actualiza el número de intentos de consumo del servicio
                    $this->mfunciones_logica->UpdateAttempt_WS_SOA_FIE((int)$selfie_captura_intentos, $resultado_soa_fie->ws_code_audit);
                
                if($selfie_captura_intentos < $arrConf[0]['conf_captura_intentos'])
                {
                    // Se autoincrementa los intentos y Actualiza el token
                    $selfie_captura_intentos++;

                    $array_resultado = array_merge($parametros_token, array(
                        'selfie_captura_intentos' => $selfie_captura_intentos
                        )
                    );

                    // Se quita el certificado SEGIP antes de actualizar el Token
                    unset(
                        $array_resultado['dig_cert_segip']
                    );
                    
                    // Actualizar los parámetros con el nuevo valor en JSON
                    $this->mfunciones_logica->token_update_parametro(json_encode($array_resultado), $arrDatos['token'], $accion_usuario, $accion_fecha);

                    $arrError[0] =  array(
                                    "error" => true,
                                    "errorMessage" => $arrConf[0]['conf_captura_intentos_texto'],
                                    "errorCode" => 400,
                                    "result" => array(
                                        "mensaje" => $arrConf[0]['conf_captura_intentos_texto']
                                    )
                    );

                    $this->response($arrError, 200);
                    exit();
                }
                else
                {
                    // Se guarda el Log para la auditoría
                    $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Error controlado del WS liveness not Live", '0,0', $accion_usuario, $accion_fecha);
                    
                    // Si se agotaron los intentos se termina el flujo mostrando el mensaje personalizado
                    $arrError[0] =  array(
                                    "error" => true,
                                    "errorMessage" => $arrConf[0]['conf_life_texto'],
                                    "errorCode" => 300,
                                    "result" => array(
                                        "mensaje" => $arrConf[0]['conf_life_texto']
                                    )
                    );

                    $this->response($arrError, 200);
                    exit();
                }
            }
            
            if($compareSelfieSegip == false) // <-- Si la comparación Selfie y SEGIP es falso se permite continuar pero se marca la Validación Operativa
            {
                // Se asigna el código de error parametrizado (si no viene con un código en la respuesta) y activar flag
                $ws_selfie_codigo_resultado = $arrConf[0]['conf_life_tipo_error'];
            }
            else
            {
                $ws_selfie_codigo_resultado = 2; //<-- Se coloca el valor "2", satisfactorio
            }
            
            // Sólo se marca el flag como "1" cuando el servicio indique true
            if($validationRequired == true)
            {
                $ws_segip_flag_verificacion = 1;
            }
            
        // LOGICA CONSUMIR SERVICIO Y REALIZAR ACCIONES FIN
                
            // Se actualiza el token con los parámetros obtenidos
            $array_resultado = array_merge($parametros_token, array(
                'ws_segip_flag_verificacion' => $ws_segip_flag_verificacion,
                'ws_selfie_codigo_resultado' => $ws_selfie_codigo_resultado,
                'dig_selfie' => $arrDatos['dig_selfie']
                )
            );

            // Actualizar los parámetros con el nuevo valor en JSON

            $this->mfunciones_logica->token_update_parametro(json_encode($array_resultado), $arrDatos['token'], $accion_usuario, $accion_fecha);
        
        $lst_resultado[0] = array(
            "mensaje" => "Correcto"
        );

        return $lst_resultado;	
    }
    
    function External__Widget_OCR($arrDatos, $usuarioAPP, $nombre_servicio, $codigo_usuario, $perfil_app){

		// Primero se verifica si el Perfil App es Afiliador Tercero "5"
        
        if ($perfil_app != 2) 
        {
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'No cuenta con el Perfil App Afiliador Tercero, por favor comuníquese con el Administrador del sistema.',
                            "errorCode" => 91,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
            );

            $this->response($arrError, 200);
            exit();			
        }
		
		// Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_afiliador",
                "token",
                "dig_ci_anv",
                "dig_ci_rev"
            );
        
        $accion_usuario = $usuarioAPP;
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Existen parámetros de entrada faltantes, revise el listado de campos del servicio.", '0,0', $accion_usuario, $accion_fecha);
            
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', '),
                            "errorCode" => 99,
                            "result" => array(
                                "mensaje" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', ')
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        $codigo_afiliador = $arrDatos['codigo_afiliador'];
        
        // VALIDACIONES DEL AFILIADOR
        
            $arrResultado3 = $this->mfunciones_logica->VerificaAfiliadorTercero($codigo_usuario, $codigo_afiliador);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            if (!isset($arrResultado3[0])) 
            {
                // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
                $arrError[0] =  array(
                                "error" => true,
                                "errorMessage" => 'Credenciales incorrectas. Key del Afiliador.',
                                "errorCode" => 91,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
            else
            {
                $identificador_afiliador = $arrResultado3[0]["afiliador_id"];
                $identificador_ejecutivo = $arrResultado3[0]["ejecutivo_id"];
            }
        
        // Validaciones INICIO
        
            // Obtener parámetros de configuración
            $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
            
        $parametros_token = $this->mfunciones_generales->TokenOnboarding_parametros($arrDatos['token']);

        if ($parametros_token == FALSE) 
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Sesión expirada o Token inválido/expirado al actualizar el token.", '0,0', $accion_usuario, $accion_fecha);

            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => $arrConf[0]['conf_token_texto'],
                            "errorCode" => 300,
                            "result" => array(
                                "mensaje" => $arrConf[0]['conf_token_texto']
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        // Validar que la imagen base64 sea correcta
        if (
                $this->mfunciones_generales->validar_imagen64_size($arrDatos['dig_ci_anv'], $arrConf[0]['conf_img_width_min'], $arrConf[0]['conf_img_height_min']) ||
                $this->mfunciones_generales->validar_imagen64_size($arrDatos['dig_ci_rev'], $arrConf[0]['conf_img_width_min'], $arrConf[0]['conf_img_height_min'])
            ){
            
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Imágenes Base64", '0,0', $accion_usuario, $accion_fecha);
            
            // Si no es base64 correcto, se muestra el error 400
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'Las imágenes no fueron procesadas, por favor vuelva a intentar.',
                            "errorCode" => 400,
                            "result" => array(
                                "mensaje" => 'Las imágenes no fueron procesadas, por favor vuelva a intentar.'
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
            // Para empezar, se captura el valor actual del Flag SEGIP
            // Sólo si se marca el flag como "1" se asigna nuevamente
            $ws_segip_flag_verificacion = $parametros_token['ws_segip_flag_verificacion'];
            
        // LÓGICA CONSUMIR SERVICIO Y REALIZAR ACCIONES INICIO
            
            // Armar parámetros y consumir WS de OCR
            $param__end_point = $arrConf[0]['conf_ocr_ws_uri'];
            $parametros = array(
                'tokenBackDocument' => preg_replace('#^data:image/[^;]+;base64,#', '', $arrDatos['dig_ci_rev']), // <-- Strip the data:image part
                'tokenFrontDocument' => preg_replace('#^data:image/[^;]+;base64,#', '', $arrDatos['dig_ci_anv']), // <-- Strip the data:image part
                'image' => preg_replace('#^data:image/[^;]+;base64,#', '', $parametros_token['dig_selfie']) // <-- Strip the data:image part
            );
            
            $resultado_soa_fie = $this->mfunciones_generales->Cliente_SOA_FIE($arrDatos['token'], $param__end_point, $parametros, $accion_usuario, $accion_fecha);
        
            $soa_fie_capturado = $resultado_soa_fie->ws_result['result']['data'];
        
            $validDocument = $resultado_soa_fie->ws_result['result']['validDocument'] ? true : false;
            
            // Validar que el documento sea válido (bloqueante)
            if($validDocument == false) // <-- Si es falso el documento es inválido o no-boliviano
            {
                // Se guarda el Log para la auditoría
                $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Error controlado del WS ocrci documento inválido", '0,0', $accion_usuario, $accion_fecha);

                // Se termina el flujo mostrando el mensaje personalizado
                $arrError[0] =  array(
                                "error" => true,
                                "errorMessage" => $arrConf[0]['conf_ocr_texto'],
                                "errorCode" => 300,
                                "result" => array(
                                    "mensaje" => $arrConf[0]['conf_ocr_texto']
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
            
            // Si el documento es válido continúa las validaciones
            
            $ocr_success = $resultado_soa_fie->ws_result['result']['success'] ? true : false;
            
            // Lógica de Reintentos cuando el servicio indica re-intentar | Prueba de Vida      Se controla los intentos guardados en el token, debe ser <= al configurado     Por cada error, se incrementa el valor de los intentos
            
                $ws_ocr_codigo_resultado = 2; // <-- Se asigna el valor 2 (satisfactorio) por defecto
            
            if($ocr_success == false) // <-- Si el parámetro "success" es falso se pide reintentar
            {
                $ocr_captura_intentos = $parametros_token['ocr_captura_intentos'];

                    // Se actualiza el número de intentos de consumo del servicio
                    $this->mfunciones_logica->UpdateAttempt_WS_SOA_FIE((int)$ocr_captura_intentos, $resultado_soa_fie->ws_code_audit);
                
                if($ocr_captura_intentos < $arrConf[0]['conf_captura_intentos'])
                {
                    // Se autoincrementa los intentos y Actualiza el token
                    $ocr_captura_intentos++;

                    $array_resultado = array_merge($parametros_token, array(
                        'ocr_captura_intentos' => $ocr_captura_intentos
                        )
                    );

                    // Actualizar los parámetros con el nuevo valor en JSON
                    $this->mfunciones_logica->token_update_parametro(json_encode($array_resultado), $arrDatos['token'], $accion_usuario, $accion_fecha);

                    $arrError[0] =  array(
                                    "error" => true,
                                    "errorMessage" => $arrConf[0]['conf_captura_intentos_texto'],
                                    "errorCode" => 400,
                                    "result" => array(
                                        "mensaje" => $arrConf[0]['conf_captura_intentos_texto']
                                    )
                    );

                    $this->response($arrError, 200);
                    exit();
                }
                else
                {
                    // Se asigna el código de error parametrizado (si no viene con un código en la respuesta) y activar flag
                    $ws_ocr_codigo_resultado = $arrConf[0]['conf_ocr_tipo_error'];
                    $ws_segip_flag_verificacion = 1;
                }
            }
            
            // Continua con la validación
            if(!isset($soa_fie_capturado['BACKSIDE']['FIELD_DATA']) || !isset($soa_fie_capturado['FRONTSIDE']['FIELD_DATA']))
            {
                // Se guarda el Log para la auditoría
                $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Error controlado del WS ocrci no BACKSIDE - FRONTSIDE", '0,0', $accion_usuario, $accion_fecha);
                    
                $arrError[0] =  array(
                                "error" => true,
                                "errorMessage" => $arrConf[0]['conf_ocr_texto'],
                                "errorCode" => 99,
                                "result" => array(
                                    "mensaje" => $arrConf[0]['conf_ocr_texto']
                                )
                );

                $this->response($arrError, 200);
                exit();
            }

            $name = $soa_fie_capturado['BACKSIDE']['FIELD_DATA']['NAME'];
            $document_number = (int)$soa_fie_capturado['FRONTSIDE']['FIELD_DATA']['DOCUMENT_NUMBER'];
            
            // Si logró capturar los valores del OCR

            // VALIDACIÓN DEL CI-OCR vs CI-REGISTRADO

            // Se adiciona el valor del CI del OCR

                $cadena_ci_ocr = $document_number; // <-- Resultado obtenido del OCR

                    $array_resultado_auditoria = array_merge($parametros_token, array(
                        'cadena_ci_ocr' => $cadena_ci_ocr
                        )
                    );

            // Se valida que el número CI sea igual al valor del OCR
            if(str_replace(" ", "", (string)$parametros_token['cI_numeroraiz']) != str_replace(" ", "", (string)$cadena_ci_ocr))
            {
                // Se quita las imagenes para guardar la data
                unset(
                    $array_resultado_auditoria['dig_selfie'],
                    $array_resultado_auditoria['dig_ci_anv'],
                    $array_resultado_auditoria['dig_ci_rev'],
                    $array_resultado_auditoria['token_jwt'],
                    $array_resultado_auditoria['dig_cert_segip']
                );

                // Si no coinciden, se guarda el Log para la auditoría
                $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio . ' - OCR_CI_NO_COINCIDE', json_encode($array_resultado_auditoria), '0,0', $accion_usuario, $accion_fecha);

                // Si no hay match del CI registrado y del OCR se termina el flujo
                $arrError[0] =  array(
                                "error" => true,
                                "errorMessage" => $arrConf[0]['conf_ocr_texto'],
                                "errorCode" => 300,
                                "result" => array(
                                    "mensaje" => $arrConf[0]['conf_ocr_texto']
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
            
            
        // LOGICA CONSUMIR SERVICIO Y REALIZAR ACCIONES FIN    
        
                $ws_ocr_name_valor = $name;
                
            // Se actualiza el token con los parámetros obtenidos
            $array_resultado = array_merge($parametros_token, array(
                'ws_segip_flag_verificacion' => $ws_segip_flag_verificacion,
                'ws_ocr_codigo_resultado' => $ws_ocr_codigo_resultado,
                'ws_ocr_name_valor' => $ws_ocr_name_valor,
                'dig_ci_anv' => $arrDatos['dig_ci_anv'],
		'dig_ci_rev' => $arrDatos['dig_ci_rev'],
                'ws_code_audit' => $resultado_soa_fie->ws_code_audit // <- Se aumenta temporalmente el código del registro de auditoría
                )
            );

            // Actualizar los parámetros con el nuevo valor en JSON

            $this->mfunciones_logica->token_update_parametro(json_encode($array_resultado), $arrDatos['token'], $accion_usuario, $accion_fecha);
        
            // Se separa el nombre compuesto para autocompletar en el formulario
            
            $ws_ocr_name_valor_array = $this->mfunciones_generales->get_nombre_compuesto($name);
            
        $lst_resultado[0] = array(
            "primer_nombre" => $ws_ocr_name_valor_array->primer_nombre,
            "segundo_nombre" => $ws_ocr_name_valor_array->segundo_nombre,
            "primer_apellido" => $ws_ocr_name_valor_array->primer_apellido,
            "segundo_apellido" => $ws_ocr_name_valor_array->segundo_apellido,
        );

        return $lst_resultado;	
    }
    
    function External__Verificar_Similaridad($arrDatos, $usuarioAPP, $nombre_servicio, $codigo_usuario, $perfil_app){
		// Primero se verifica si el Perfil App es Afiliador Tercero "5"
        
        if ($perfil_app != 2) 
        {
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'No cuenta con el Perfil App Afiliador Tercero, por favor comuníquese con el Administrador del sistema.',
                            "errorCode" => 91,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
            );

            $this->response($arrError, 200);
            exit();			
        }
		
		// Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_afiliador",
                "token",
                "cadena_validar_nombre"
            );
        
        $accion_usuario = $usuarioAPP;
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Existen parámetros de entrada faltantes, revise el listado de campos del servicio.", '0,0', $accion_usuario, $accion_fecha);
            
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', '),
                            "errorCode" => 99,
                            "result" => array(
                                "mensaje" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', ')
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        $codigo_afiliador = $arrDatos['codigo_afiliador'];
        
        // VALIDACIONES DEL AFILIADOR
        
            $arrResultado3 = $this->mfunciones_logica->VerificaAfiliadorTercero($codigo_usuario, $codigo_afiliador);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            if (!isset($arrResultado3[0])) 
            {
                // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
                $arrError[0] =  array(
                                "error" => true,
                                "errorMessage" => 'Credenciales incorrectas. Key del Afiliador.',
                                "errorCode" => 91,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
            else
            {
                $identificador_afiliador = $arrResultado3[0]["afiliador_id"];
                $identificador_ejecutivo = $arrResultado3[0]["ejecutivo_id"];
            }
        
        // Validaciones INICIO
        
        $parametros_token = $this->mfunciones_generales->TokenOnboarding_parametros($arrDatos['token']);

            // Obtener parámetros de configuración
            $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
        
        if ($parametros_token == FALSE) 
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Sesión expirada o Token inválido/expirado al actualizar el token.", '0,0', $accion_usuario, $accion_fecha);

            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => $arrConf[0]['conf_token_texto'],
                            "errorCode" => 300,
                            "result" => array(
                                "mensaje" => $arrConf[0]['conf_token_texto']
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
            // Para empezar, se captura el valor actual del Flag SEGIP
            // Sólo si se marca el flag como "1" se asigna nuevamente
            $ws_segip_flag_verificacion = $parametros_token['ws_segip_flag_verificacion'];
        
        if(strlen((string)$parametros_token['ws_ocr_name_valor']) < 5)
        {
            // Si el NAME está vacío es porque el OCR no se procesó
            $resultado_similaridad = 0;
            $ws_segip_flag_verificacion = 1;
        }
        else
        {
            $cadena_validar_1 = $this->mfunciones_generales->SanitizarData($arrDatos['cadena_validar_nombre']);
            $cadena_validar_2 = $this->mfunciones_generales->SanitizarData($parametros_token['ws_ocr_name_valor']);

            if (strlen((string)$cadena_validar_1) < 5) 
            {
                // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
                $arrError[0] =  array(
                                "error" => true,
                                "errorMessage" => 'Dimensión cadena inválida',
                                "errorCode" => 99,
                                "result" => array(
                                    "mensaje" => 'Dimensión cadena inválida'
                                )
                );

                $this->response($arrError, 200);
                exit();
            }

            // LÓGICA PARA APLICAR ALGORITMO "EDIT DISTANCE"

            // Obtener porcentaje de Edit Distance entre las 2 cadenas
                $resultado_similaridad = 0;
            similar_text(str_replace(" ", "", mb_strtolower($cadena_validar_1, 'UTF-8')), str_replace(" ", "", mb_strtolower($cadena_validar_2, 'UTF-8')), $resultado_similaridad);

            // Consultar si el resultado obtenido es maoyr o igual al parametrizado

            $resultado_similaridad = (int)round($resultado_similaridad);

            if($resultado_similaridad < (int)$arrConf[0]['conf_ocr_porcentaje'])
            {
                // Se asigna flag "1"
                $ws_segip_flag_verificacion = 1;
            }
            
        }
            // Se actualiza el registro de log con el resultado de la similaridad
            $this->mfunciones_logica->UpdateSimilaridad_WS_SOA_FIE((string)$resultado_similaridad, $parametros_token['ws_code_audit']);
             
            // Se actualiza el token con los parámetros obtenidos
            $array_resultado = array_merge($parametros_token, array(
                "ws_ocr_name_similar" => (string)$resultado_similaridad,
                "ws_segip_flag_verificacion" => $ws_segip_flag_verificacion
                )
            );

            // Actualizar los parámetros con el nuevo valor en JSON

            $this->mfunciones_logica->token_update_parametro(json_encode($array_resultado), $arrDatos['token'], $accion_usuario, $accion_fecha);

        
        $lst_resultado[0] = array(
            //"resultado_similaridad" => $resultado_similaridad, // <-- Sólo para desarrollo
            "mensaje" => "Correcto"
        );

        return $lst_resultado;	
    }
    
    /*************** AFILIACIÓN POR TERCEROS - FIN ****************************/
    
    /*************** SOLICITUD DE CREDITOS - INICIO ****************************/
    
    function External__Register_Credito($arrDatos, $usuarioAPP, $nombre_servicio, $codigo_usuario, $perfil_app){
		// Primero se verifica si el Perfil App es Afiliador Tercero "5"
        
        if ($perfil_app != 2) 
        {
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'No cuenta con el Perfil App Afiliador Tercero, por favor comuníquese con el Administrador del sistema.',
                            "errorCode" => 91,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
            );

            $this->response($arrError, 200);
            exit();			
        }
		
		// Se verifica si cuenta con los parámetros necesarios y correctos
        $parametros = array(
                "codigo_afiliador",
                "codigo_agencia_fie",
                "sol_dir_localidad_ciudad",
                "sol_dir_provincia",
                "sol_dir_departamento",
                "sol_ci",
                "sol_extension",
                "sol_complemento",
                "sol_primer_nombre",
                "sol_segundo_nombre",
                "sol_primer_apellido",
                "sol_segundo_apellido",
                "sol_correo",
                "sol_cel",
                "sol_telefono",
                "sol_dependencia",
                "sol_indepen_actividad",
                "sol_indepen_telefono",
                "sol_depen_empresa",
                "sol_depen_cargo",
                "sol_depen_telefono",
                "sol_conyugue",
                "sol_con_ci",
                "sol_con_extension",
                "sol_con_complemento",
                "sol_con_primer_nombre",
                "sol_con_segundo_nombre",
                "sol_con_primer_apellido",
                "sol_con_segundo_apellido",
                "sol_con_correo",
                "sol_con_cel",
                "sol_con_telefono",
                "sol_con_dependencia",
                "sol_con_indepen_actividad",
                "sol_con_indepen_telefono",
                "sol_con_depen_empresa",
                "sol_con_depen_cargo",
                "sol_con_depen_telefono",
                "sol_monto",
                "sol_moneda",
                "sol_detalle",
                "sol_direccion_trabajo",
                "sol_edificio_trabajo",
                "sol_numero_trabajo",
                "sol_cod_barrio",
                "sol_dir_referencia",
                "sol_geolocalizacion",
                "sol_croquis",
                "sol_con_direccion_trabajo",
                "sol_con_edificio_trabajo",
                "sol_con_numero_trabajo",
                "sol_con_cod_barrio",
                "sol_con_dir_referencia",
                "sol_con_geolocalizacion",
                "sol_con_croquis"
            );
        
        $accion_usuario = $usuarioAPP;
        $accion_fecha = date('Y-m-d H:i:s');
        
        // Si no son los parámetros que se requiere, se devuelve vacio
        if(!($this->array_keys_exist($arrDatos, $parametros)))
        {
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, "Error: Existen parámetros de entrada faltantes, revise el listado de campos del servicio.", '0,0', $accion_usuario, $accion_fecha);
            
            // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
            $arrError[0] =  array(
                            "error" => true,
                            "errorMessage" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', '),
                            "errorCode" => 99,
                            "result" => array(
                                "mensaje" => 'Existen parámetros de entrada faltantes, revise el listado de campos del servicio. Faltan: ' . rtrim($this->array_keys_exist_value($arrDatos, $parametros), ', ')
                            )
            );

            $this->response($arrError, 200);
            exit();
        }
        
        $codigo_afiliador = $arrDatos['codigo_afiliador'];
        
        // VALIDACIONES DEL AFILIADOR
        
            $arrResultado3 = $this->mfunciones_logica->VerificaAfiliadorTercero($codigo_usuario, $codigo_afiliador);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            if (!isset($arrResultado3[0])) 
            {
                // Si no hay match del usuario y la Key del afiliador asociado, se muestra el mensaje
                $arrError[0] =  array(
                                "error" => true,
                                "errorMessage" => 'Credenciales incorrectas. Key del Afiliador.',
                                "errorCode" => 91,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                );

                $this->response($arrError, 200);
                exit();
            }
            else
            {
                $identificador_afiliador = $arrResultado3[0]["afiliador_id"];
                $identificador_ejecutivo = $arrResultado3[0]["ejecutivo_id"];
            }
        
        // Validaciones INICIO
        
        $arrError[0] =  array(
                "error" => true,
                "errorMessage" => $this->lang->line('CamposObligatorios'),
                "errorCode" => 96,
                "result" => array(
                    "mensaje" => $this->lang->line('IncompletoApp')
                )
        );
        
        $separador = '<br /> - ';
        $error_texto = '';
        
        $sol_dir_localidad_ciudad = $this->mfunciones_generales->SanitizarData($arrDatos['sol_dir_localidad_ciudad']); if($sol_dir_localidad_ciudad == '') { $error_texto .= $separador . $this->lang->line('sol_dir_localidad_ciudad'); }
        $sol_dir_provincia = $this->mfunciones_generales->SanitizarData($arrDatos['sol_dir_provincia']); if($sol_dir_provincia == '') { $error_texto .= $separador . $this->lang->line('sol_dir_provincia'); }
        $sol_dir_departamento = $this->mfunciones_generales->SanitizarData($arrDatos['sol_dir_departamento']); if($sol_dir_departamento == '') { $error_texto .= $separador . $this->lang->line('sol_dir_departamento'); }
        $sol_ci = $this->mfunciones_generales->SanitizarData($arrDatos['sol_ci']); if($sol_ci == '') { $error_texto .= $separador . $this->lang->line('sol_ci'); }
        $sol_extension = $this->mfunciones_generales->SanitizarData($arrDatos['sol_extension']); if($sol_extension == '') { $error_texto .= $separador . $this->lang->line('sol_extension'); }
        $sol_complemento = $this->mfunciones_generales->SanitizarData($arrDatos['sol_complemento']); if($sol_complemento == '') { $error_texto .= $separador . $this->lang->line('sol_complemento'); }
        $sol_primer_nombre = $this->mfunciones_generales->SanitizarData($arrDatos['sol_primer_nombre']); if($sol_primer_nombre == '') { $error_texto .= $separador . $this->lang->line('sol_primer_nombre'); }
        $sol_segundo_nombre = $this->mfunciones_generales->SanitizarData($arrDatos['sol_segundo_nombre']); if($sol_segundo_nombre == '') { $error_texto .= $separador . $this->lang->line('sol_segundo_nombre'); }
        $sol_primer_apellido = $this->mfunciones_generales->SanitizarData($arrDatos['sol_primer_apellido']); if($sol_primer_apellido == '') { $error_texto .= $separador . $this->lang->line('sol_primer_apellido'); }
        $sol_segundo_apellido = $this->mfunciones_generales->SanitizarData($arrDatos['sol_segundo_apellido']); if($sol_segundo_apellido == '') { $error_texto .= $separador . $this->lang->line('sol_segundo_apellido'); }
        
        $sol_correo = $arrDatos['sol_correo'];
        if(strlen((string)$sol_correo) > 3 && $this->mfunciones_generales->VerificaCorreo($sol_correo) == false)
        {
            $error_texto .= $separador . 'Email en formato incorrecto';
        }
        
        $sol_cel = $this->mfunciones_generales->SanitizarData($arrDatos['sol_cel']); if(strlen((string)$sol_cel) != 8) { $error_texto .= $separador . $this->lang->line('sol_cel'); }
        if((string)$sol_cel[0] != '6' && (string)$sol_cel[0] != '7') { $error_texto .= $separador . $this->lang->line('sol_cel') . ' debe empezar con 6 o 7'; }
        
        $sol_telefono = $this->mfunciones_generales->SanitizarData($arrDatos['sol_telefono']); if($sol_telefono == '') { $error_texto .= $separador . $this->lang->line('sol_telefono'); }
        $sol_dependencia = $this->mfunciones_generales->SanitizarData($arrDatos['sol_dependencia']); if($sol_dependencia == '') { $error_texto .= $separador . $this->lang->line('sol_dependencia'); }
        $sol_indepen_actividad = $this->mfunciones_generales->SanitizarData($arrDatos['sol_indepen_actividad']); if($sol_indepen_actividad == '') { $error_texto .= $separador . $this->lang->line('sol_indepen_actividad'); }
        $sol_indepen_telefono = $this->mfunciones_generales->SanitizarData($arrDatos['sol_indepen_telefono']); if($sol_indepen_telefono == '') { $error_texto .= $separador . $this->lang->line('sol_indepen_telefono'); }
        $sol_depen_empresa = $this->mfunciones_generales->SanitizarData($arrDatos['sol_depen_empresa']); if($sol_depen_empresa == '') { $error_texto .= $separador . $this->lang->line('sol_depen_empresa'); }
        $sol_depen_cargo = $this->mfunciones_generales->SanitizarData($arrDatos['sol_depen_cargo']); if($sol_depen_cargo == '') { $error_texto .= $separador . $this->lang->line('sol_depen_cargo'); }
        $sol_depen_telefono = $this->mfunciones_generales->SanitizarData($arrDatos['sol_depen_telefono']); if($sol_depen_telefono == '') { $error_texto .= $separador . $this->lang->line('sol_depen_telefono'); }
        
        $sol_conyugue = $this->mfunciones_generales->SanitizarData($arrDatos['sol_conyugue']); if($sol_conyugue == '') { $error_texto .= $separador . $this->lang->line('sol_conyugue'); }
        
        $sol_con_ci = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_ci']); if($sol_con_ci == '') { $error_texto .= $separador . $this->lang->line('sol_con_ci'); }
        $sol_con_extension = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_extension']); if($sol_con_extension == '') { $error_texto .= $separador . $this->lang->line('sol_con_extension'); }
        $sol_con_complemento = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_complemento']); if($sol_con_complemento == '') { $error_texto .= $separador . $this->lang->line('sol_con_complemento'); }
        $sol_con_primer_nombre = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_primer_nombre']); if($sol_con_primer_nombre == '') { $error_texto .= $separador . $this->lang->line('sol_con_primer_nombre'); }
        $sol_con_segundo_nombre = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_segundo_nombre']); if($sol_con_segundo_nombre == '') { $error_texto .= $separador . $this->lang->line('sol_con_segundo_nombre'); }
        $sol_con_primer_apellido = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_primer_apellido']); if($sol_con_primer_apellido == '') { $error_texto .= $separador . $this->lang->line('sol_con_primer_apellido'); }
        $sol_con_segundo_apellido = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_segundo_apellido']); if($sol_con_segundo_apellido == '') { $error_texto .= $separador . $this->lang->line('sol_con_segundo_apellido'); }
        
        $sol_con_correo = $arrDatos['sol_con_correo'];
        if(strlen((string)$sol_con_correo) > 3 && $this->mfunciones_generales->VerificaCorreo($sol_con_correo) == false)
        {
            $error_texto .= $separador . 'Email cónyuge en formato incorrecto';
        }
        
        $sol_con_cel = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_cel']); if($sol_con_cel == '') { $error_texto .= $separador . $this->lang->line('sol_con_cel'); }
        $sol_con_telefono = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_telefono']); if($sol_con_telefono == '') { $error_texto .= $separador . $this->lang->line('sol_con_telefono'); }
        $sol_con_dependencia = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_dependencia']); if($sol_con_dependencia == '') { $error_texto .= $separador . $this->lang->line('sol_con_dependencia'); }
        $sol_con_indepen_actividad = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_indepen_actividad']); if($sol_con_indepen_actividad == '') { $error_texto .= $separador . $this->lang->line('sol_con_indepen_actividad'); }
        $sol_con_indepen_telefono = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_indepen_telefono']); if($sol_con_indepen_telefono == '') { $error_texto .= $separador . $this->lang->line('sol_con_indepen_telefono'); }
        $sol_con_depen_empresa = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_depen_empresa']); if($sol_con_depen_empresa == '') { $error_texto .= $separador . $this->lang->line('sol_con_depen_empresa'); }
        $sol_con_depen_cargo = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_depen_cargo']); if($sol_con_depen_cargo == '') { $error_texto .= $separador . $this->lang->line('sol_con_depen_cargo'); }
        $sol_con_depen_telefono = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_depen_telefono']); if($sol_con_depen_telefono == '') { $error_texto .= $separador . $this->lang->line('sol_con_depen_telefono'); }
        $sol_monto = $this->mfunciones_generales->SanitizarData($arrDatos['sol_monto']); if($sol_monto == '') { $error_texto .= $separador . $this->lang->line('sol_monto'); }
        $sol_moneda = $this->mfunciones_generales->SanitizarData($arrDatos['sol_moneda']); if($sol_moneda == '') { $error_texto .= $separador . $this->lang->line('sol_moneda'); }
        $sol_detalle = $this->mfunciones_generales->SanitizarData($arrDatos['sol_detalle']); if($sol_detalle == '') { $error_texto .= $separador . $this->lang->line('sol_detalle'); }
        $sol_direccion_trabajo = $this->mfunciones_generales->SanitizarData($arrDatos['sol_direccion_trabajo']); if($sol_direccion_trabajo == '') { $error_texto .= $separador . $this->lang->line('sol_direccion_trabajo'); }
        $sol_edificio_trabajo = $this->mfunciones_generales->SanitizarData($arrDatos['sol_edificio_trabajo']); if($sol_edificio_trabajo == '') { $error_texto .= $separador . $this->lang->line('sol_edificio_trabajo'); }
        $sol_numero_trabajo = $this->mfunciones_generales->SanitizarData($arrDatos['sol_numero_trabajo']); if($sol_numero_trabajo == '') { $error_texto .= $separador . $this->lang->line('sol_numero_trabajo'); }
        $sol_cod_barrio = $this->mfunciones_generales->SanitizarData($arrDatos['sol_cod_barrio']); if($sol_cod_barrio == '') { $error_texto .= $separador . $this->lang->line('sol_cod_barrio'); }
        $sol_dir_referencia = $this->mfunciones_generales->SanitizarData($arrDatos['sol_dir_referencia']); if($sol_dir_referencia == '') { $error_texto .= $separador . $this->lang->line('sol_dir_referencia'); }
        $sol_geolocalizacion = $this->mfunciones_generales->SanitizarData($arrDatos['sol_geolocalizacion']); if($sol_geolocalizacion == '') { $error_texto .= $separador . $this->lang->line('sol_geolocalizacion'); }
        $sol_croquis = $arrDatos['sol_croquis']; if($sol_croquis == '') { $error_texto .= $separador . $this->lang->line('sol_croquis'); }
        $sol_con_direccion_trabajo = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_direccion_trabajo']); if($sol_con_direccion_trabajo == '') { $error_texto .= $separador . $this->lang->line('sol_con_direccion_trabajo'); }
        $sol_con_edificio_trabajo = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_edificio_trabajo']); if($sol_con_edificio_trabajo == '') { $error_texto .= $separador . $this->lang->line('sol_con_edificio_trabajo'); }
        $sol_con_numero_trabajo = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_numero_trabajo']); if($sol_con_numero_trabajo == '') { $error_texto .= $separador . $this->lang->line('sol_con_numero_trabajo'); }
        $sol_con_cod_barrio = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_cod_barrio']); if($sol_con_cod_barrio == '') { $error_texto .= $separador . $this->lang->line('sol_con_cod_barrio'); }
        $sol_con_dir_referencia = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_dir_referencia']); if($sol_con_dir_referencia == '') { $error_texto .= $separador . $this->lang->line('sol_con_dir_referencia'); }
        $sol_con_geolocalizacion = $this->mfunciones_generales->SanitizarData($arrDatos['sol_con_geolocalizacion']); if($sol_con_geolocalizacion == '') { $error_texto .= $separador . $this->lang->line('sol_con_geolocalizacion'); }
        $sol_con_croquis = $arrDatos['sol_con_croquis']; if($sol_con_croquis == '') { $error_texto .= $separador . $this->lang->line('sol_con_croquis'); }

        // Validar la Agencia Asociada
        $codigo_agencia_fie = $arrDatos['codigo_agencia_fie'];
        
        if($this->mfunciones_generales->ObtenerNombreRegionCodigo($codigo_agencia_fie) == '0')
        {
            $arrError[0]['errorMessage'] = 'La Agencia seleccionada no es correcta';
            $this->response($arrError, 200); exit();
        }
        
        if($error_texto != '')
        {
            $arrError[0]['errorMessage'] = $this->lang->line('CamposObligatorios') . $error_texto;
            $this->response($arrError, 200); exit();
        }

        // Obtener parámetros de configuración
        $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
        
        if (strlen((string)$sol_croquis) > 1 && $this->mfunciones_generales->validar_imagen64_size($sol_croquis, $arrConf[0]['conf_img_width_min'], $arrConf[0]['conf_img_height_min']))
        {
            $arrError[0]['errorMessage'] = 'La imagen del croquis no es correcta.';
            $this->response($arrError, 200); exit();
        }
        
        if (strlen((string)$sol_con_croquis) > 1 && $this->mfunciones_generales->validar_imagen64_size($sol_con_croquis, $arrConf[0]['conf_img_width_min'], $arrConf[0]['conf_img_height_min']))
        {
            $arrError[0]['errorMessage'] = 'La imagen del croquis del cónyuge no es correcta.';
            $this->response($arrError, 200); exit();
        }
        
        // SOLICITANTE Obtener los códigos de ciudad, provincia y municipio si viene con valor
        if(str_replace(" ", "", $sol_cod_barrio) != "")
        {
            $aux_sol_cod_barrio = $this->mfunciones_microcreditos->obtener_Dep_Pro_Ciu_from_zon($sol_cod_barrio);
            
            $sol_dir_departamento = $aux_sol_cod_barrio->dir_departamento;
            $sol_dir_provincia = $aux_sol_cod_barrio->dir_provincia;
            $sol_dir_localidad_ciudad = $aux_sol_cod_barrio->dir_localidad_ciudad;
            
            if($aux_sol_cod_barrio->dir_departamento == '')
            {
                $arrError[0] = array(
                    "error" => true,
                    "errorMessage" => $this->lang->line('dir_barrio_zona_uv') . ' del solicitante es incorrecto. Por favor revise el formulario.',
                    "errorCode" => 300,
                    "result" => array(
                        "mensaje" => $this->lang->line('dir_barrio_zona_uv') . ' del solicitante es incorrecto. Por favor revise el formulario.'
                    )
                );
                
                $this->response($arrError, 200);
                exit();
            }
        }
        
        // CONYUGE Obtener los códigos de ciudad, provincia y municipio si viene con valor
        
        $sol_con_dir_departamento = $sol_dir_departamento;
        $sol_con_dir_provincia = $sol_dir_provincia;
        $sol_con_dir_localidad_ciudad = $sol_dir_localidad_ciudad;
        
        if(str_replace(" ", "", $sol_con_cod_barrio) != "")
        {
            $aux_sol_cod_barrio = $this->mfunciones_microcreditos->obtener_Dep_Pro_Ciu_from_zon($sol_con_cod_barrio);
            
            $sol_con_dir_departamento = $aux_sol_cod_barrio->dir_departamento;
            $sol_con_dir_provincia = $aux_sol_cod_barrio->dir_provincia;
            $sol_con_dir_localidad_ciudad = $aux_sol_cod_barrio->dir_localidad_ciudad;
            
            if($aux_sol_cod_barrio->dir_departamento == '')
            {
                $arrError[0] = array(
                    "error" => true,
                    "errorMessage" => $this->lang->line('dir_barrio_zona_uv') . ' del cónyuge es incorrecto. Por favor revise el formulario.',
                    "errorCode" => 300,
                    "result" => array(
                        "mensaje" => $this->lang->line('dir_barrio_zona_uv') . ' del cónyuge es incorrecto. Por favor revise el formulario.'
                    )
                );
                
                $this->response($arrError, 200);
                exit();
            }
        }
        
        // Validaciones FIN
        
        $codigo_solicitud = $this->mfunciones_microcreditos->Insertar_SolicitudCredito($identificador_ejecutivo, $identificador_afiliador, $codigo_agencia_fie,
            
                    $sol_dir_localidad_ciudad,
                    $sol_dir_provincia,
                    $sol_dir_departamento,
                    $sol_ci,
                    $sol_extension,
                    $sol_complemento,
                    $sol_primer_nombre,
                    $sol_segundo_nombre,
                    $sol_primer_apellido,
                    $sol_segundo_apellido,
                    substr($sol_correo,0,100),
                    $sol_cel,
                    $sol_telefono,
                    (int)$sol_dependencia,
                    substr($sol_indepen_actividad,0,60),
                    substr($sol_indepen_telefono,0,14),
                    substr($sol_depen_empresa,0,60),
                    substr($sol_depen_cargo,0,50),
                    substr($sol_depen_telefono,0,14),
                    $sol_conyugue,
                    $sol_con_ci,
                    $sol_con_extension,
                    $sol_con_complemento,
                    $sol_con_primer_nombre,
                    $sol_con_segundo_nombre,
                    $sol_con_primer_apellido,
                    $sol_con_segundo_apellido,
                    substr($sol_con_correo,0,100),
                    $sol_con_cel,
                    $sol_con_telefono,
                    (int)$sol_con_dependencia,
                    substr($sol_con_indepen_actividad,0,60),
                    substr($sol_con_indepen_telefono,0,14),
                    substr($sol_con_depen_empresa,0,60),
                    substr($sol_con_depen_cargo,0,50),
                    substr($sol_con_depen_telefono,0,14),
                    $sol_monto,
                    $sol_moneda,
                    $sol_detalle,
                    $sol_direccion_trabajo,
                    $sol_edificio_trabajo,
                    $sol_numero_trabajo,
                    $sol_cod_barrio,
                    (int)$sol_dir_referencia,
                    $sol_geolocalizacion,
                    $sol_croquis,
                    $sol_con_direccion_trabajo,
                    $sol_con_edificio_trabajo,
                    $sol_con_numero_trabajo,
                    $sol_con_cod_barrio,
                    (int)$sol_con_dir_referencia,
                    $sol_con_geolocalizacion,
                    $sol_con_croquis,
                    $sol_con_dir_departamento,
                    $sol_con_dir_provincia,
                    $sol_con_dir_localidad_ciudad,
                    $accion_usuario,
                    $accion_fecha);
        
        // Notificar Proceso Onboarding Solicitud Crédito     0=No Regionalizado      1=Si Regionalizado
        $this->mfunciones_microcreditos->NotificacionEtapaCredito($codigo_solicitud, 18, 1);
        
        // Notificar al Solicitante
        
        $notificacion_texto_personalizado = $arrConf[0]['conf_credito_notificar_texto'];
        
        // -- Correo
        if(strlen((string)$sol_correo) > 3 && $arrConf[0]['conf_credito_notificar_email'] == 1)
        {
            $arrCorreo[0] = array(
                "sol_credito_mensaje_registro" => $notificacion_texto_personalizado
            );
            
            $this->mfunciones_generales->EnviarCorreo('notificar_solicitante_credito', $sol_correo, $sol_primer_nombre . ' ' . $sol_primer_apellido . ' ' . $sol_segundo_apellido, $arrCorreo);
        }
        
        // -- SMS
        if($arrConf[0]['conf_credito_notificar_sms'] == 1)
        {
            $parametros_sms = array(
                "name" => $arrConf[0]['conf_sms_name_plantilla'],
                "message" => str_replace(
                                array('{destinatario_nombre}'),
                                array(mb_strtoupper(implode(' ', array($sol_primer_nombre)))),
                                $arrConf[0]['conf_credito_notificar_texto']),
                "to" => array(
                    $sol_cel
                ),
                "channelId" => $arrConf[0]['conf_sms_channelid']
            );
            
            $this->mfunciones_microcreditos->DoBackgroundEnviarSMS($arrConf[0]['conf_credito_notificar_sms_uri'], $parametros_sms, $accion_usuario, $accion_fecha);
        }
        
        // Se guarda el Log para la auditoría
            // Se quita el codigo afiliador para guardar la data
            unset($arrDatos['sol_croquis']);
            unset($arrDatos['sol_con_croquis']);
        $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), '0,0', $accion_usuario, $accion_fecha);
            
        $lst_resultado[0] = array(
            "mensaje" => $arrConf[0]['conf_credito_texto']
        );

        return $lst_resultado;	
    }
    
    /*************** SOLICITUD DE CREDITOS - FIN ****************************/
    
}