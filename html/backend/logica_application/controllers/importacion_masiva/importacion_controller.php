<?php
/**
 * @file 
 * Codigo que implementa el controlador para la gestión de Prospectos
 * @brief CONRTOLADOR PARA LA GESTIÓN DE PROSPECTOS (DOCUMENTOS Y CONSOLIDAR) Y EMPRESAS (ACTUALIZACIÓN DE INFORMACIÓN)
 * @author JRAD
 * @date 2018
 * @copyright 2018 JRAD
 */
/**
 * Controlador para la gestión de Prospectos y Empresas
 * @brief GESTIÓN DE PROSPECTOS Y EMPRESAS
 * @class Gestion_controller
 */
class Importacion_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 36;

    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
    
    public function Importacion_Form() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');

        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        // Se realizar el unset de la variable de session
        
        if(isset($_SESSION['arrResultadoImportacion']))
        {
            unset($_SESSION['arrResultadoImportacion']);
            unset($_SESSION['arrResultadoImportacion_aux']);
        }
        
        $this->load->view('importacion_masiva/view_form_ver', $data);
    }
    
    // Se procede a subir y guardar temporalmente los datos del excel, aún no se guarda
    
    public function Importacion_Subir() {
        
        $this->lang->load('general', 'castellano');
        $this->load->library('excel');
        
        if(empty($_FILES['documento_pdf']['tmp_name']))
        {
            echo $this->lang->line('FormularioNoFile');
            exit();
        }
        
        if (isset($_FILES["documento_pdf"]["name"])) 
        {
            $path = $_FILES["documento_pdf"]["tmp_name"];
            
            $inputFileType = PHPExcel_IOFactory::identify($path);
            
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($path);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            //$objReader->setLoadSheetsOnly('jrad');
            
            //print_r($objPHPExcel->setActiveSheetIndexByName('jrad'));
            
            
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
            {
                $hoja_id = $objPHPExcel->getIndex($worksheet);
                
                $hoja_nombre = $worksheet->getTitle();
                
                switch ($hoja_id) {
                    // Cargado de Leads
                    case 0:

                        $highestRow = $worksheet->getHighestRow();
                        $highestColumn = $worksheet->getHighestColumn();

                        $aux_error = 0;

                        // Se asume que los datos empiezan en la fila 2, la fila 1 son cabeceras
                        for ($row = 3; $row <= $highestRow; $row++) {
                            
                            // Se capturan los valores

                            $captura_id_campana = $worksheet->getCellByColumnAndRow(0, $row)->getValue(); // Nuevo 10-07-2019
                            $nombre_campana = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                            
                            $captura_fecha_inicio = $worksheet->getCellByColumnAndRow(2, $row)->getFormattedValue(); // Nuevo 10-07-2019
                                $captura_fecha_inicio = PHPExcel_Shared_Date::ExcelToPHP($captura_fecha_inicio);
                                $captura_fecha_inicio = new DateTime(date('Y-m-d', $captura_fecha_inicio));
                                $captura_fecha_inicio->modify('+1 day');
                                
                            $captura_fecha_fin = $worksheet->getCellByColumnAndRow(3, $row)->getFormattedValue(); // Nuevo 10-07-2019
                                $captura_fecha_fin = PHPExcel_Shared_Date::ExcelToPHP($captura_fecha_fin);
                                $captura_fecha_fin = new DateTime(date('Y-m-d', $captura_fecha_fin));
                                $captura_fecha_fin->modify('+1 day');
                                
                            $captura_oferta = $worksheet->getCellByColumnAndRow(4, $row)->getValue(); // Nuevo 10-07-2019
                            
                            $idc = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                            $nombre_cliente = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                            
                            $codigo_cliente = $worksheet->getCellByColumnAndRow(7, $row)->getValue(); // Nuevo 10-07-2019
                            
                            $empresa = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                            $ingreso = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                            $direccion = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                            $telefono = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                            $celular = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                            $correo = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                            
                            $captura_mensaje = $worksheet->getCellByColumnAndRow(14, $row)->getValue(); // Nuevo 10-07-2019
                            
                            $matricula = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                            
                            $captura_estado = $worksheet->getCellByColumnAndRow(16, $row)->getValue(); // Nuevo 10-07-2019

                            // Se realiza las validaciones de los campos, y si existe error capturando el número de la línea

                            /* VALIDACIONES INICIO */

                                // ID. DE CAMPAÑA
                                $resultado_verifica = '';
                                    $valor_verifica = $nombre_campana;   // <-- Variable del Campo
                                    $campo_verifica = 'nombre_campana';  // <-- Nombre del Campo
                                    $resultado_verifica = $this->mfunciones_generales->VerificaCampoLead($campo_verifica, $valor_verifica);

                                $codigo_campana = $resultado_verifica;
                                    
                                if($resultado_verifica != '')
                                {
                                    // Si no existe la campaña debe ser creada
                                    
                                        $earlier = new DateTime($captura_fecha_inicio->format('Y-m-d'));
                                        $later = new DateTime($captura_fecha_fin->format('Y-m-d'));
                                        
                                        $camp_plazo = $later->diff($earlier)->format("%a");
                                    
                                    $accion_usuario = $_SESSION["session_informacion"]["login"];
                                    $accion_fecha = date('Y-m-d H:i:s');
                                        
                                    $codigo_campana = $this->mfunciones_logica->InsertCampana(1, $nombre_campana, $nombre_campana, $camp_plazo, $captura_oferta, 0, $captura_fecha_inicio->format('Y-m-d'), $accion_usuario, $accion_fecha);
                                }
                                else
                                {
                                    // Sólo para el caso del nombre de la Campaña
                                    
                                    $arrConsultaCampana = $this->mfunciones_logica->ObtenerCodigoCampanaNombre($nombre_campana);
                                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConsultaCampana);
                                    
                                    if(isset($arrConsultaCampana[0]))
                                    {
                                        $codigo_campana = $arrConsultaCampana[0]['camp_id'];
                                    }
                                    
                                }
                                
                                // IDC
                                $resultado_verifica = '';
                                    $valor_verifica = $idc;   // <-- Variable del Campo
                                    $campo_verifica = 'idc';  // <-- Nombre del Campo
                                    $resultado_verifica = $this->mfunciones_generales->VerificaCampoLead($campo_verifica, $valor_verifica);

                                if($resultado_verifica != '')
                                {
                                    $aux_error = 1;

                                    $arrError[] = array(
                                        'hoja' => $hoja_nombre, 'linea' => $row, 'campo' => $campo_verifica, 'valor' => $valor_verifica, 'mensaje' => $resultado_verifica
                                    );
                                }

                                // Nombre Cliente
                                $resultado_verifica = '';
                                    $valor_verifica = $nombre_cliente;   // <-- Variable del Campo
                                    $campo_verifica = 'nombre_cliente';  // <-- Nombre del Campo
                                    $resultado_verifica = $this->mfunciones_generales->VerificaCampoLead($campo_verifica, $valor_verifica);

                                if($resultado_verifica != '')
                                {
                                    $aux_error = 1;

                                    $arrError[] = array(
                                        'hoja' => $hoja_nombre, 'linea' => $row, 'campo' => $campo_verifica, 'valor' => $valor_verifica, 'mensaje' => $resultado_verifica
                                    );
                                }

                                // empresa
                                $resultado_verifica = '';
                                    $valor_verifica = $empresa;   // <-- Variable del Campo
                                    $campo_verifica = 'empresa';  // <-- Nombre del Campo
                                    $resultado_verifica = $this->mfunciones_generales->VerificaCampoLead($campo_verifica, $valor_verifica);

                                if($resultado_verifica != '')
                                {
                                    $aux_error = 1;

                                    $arrError[] = array(
                                        'hoja' => $hoja_nombre, 'linea' => $row, 'campo' => $campo_verifica, 'valor' => $valor_verifica, 'mensaje' => $resultado_verifica
                                    );
                                }

                                // ingreso
                                $resultado_verifica = '';
                                    $valor_verifica = $ingreso;   // <-- Variable del Campo
                                    $campo_verifica = 'ingreso';  // <-- Nombre del Campo
                                    $resultado_verifica = $this->mfunciones_generales->VerificaCampoLead($campo_verifica, $valor_verifica);

                                if($resultado_verifica != '')
                                {
                                    $aux_error = 1;

                                    $arrError[] = array(
                                        'hoja' => $hoja_nombre, 'linea' => $row, 'campo' => $campo_verifica, 'valor' => $valor_verifica, 'mensaje' => $resultado_verifica
                                    );
                                }

                                // direccion
                                $resultado_verifica = '';
                                    $valor_verifica = $direccion;   // <-- Variable del Campo
                                    $campo_verifica = 'direccion';  // <-- Nombre del Campo
                                    $resultado_verifica = $this->mfunciones_generales->VerificaCampoLead($campo_verifica, $valor_verifica);

                                if($resultado_verifica != '')
                                {
                                    $aux_error = 1;

                                    $arrError[] = array(
                                        'hoja' => $hoja_nombre, 'linea' => $row, 'campo' => $campo_verifica, 'valor' => $valor_verifica, 'mensaje' => $resultado_verifica
                                    );
                                }

                                // telefono
                                $resultado_verifica = '';
                                    $valor_verifica = $telefono;   // <-- Variable del Campo
                                    $campo_verifica = 'telefono';  // <-- Nombre del Campo
                                    $resultado_verifica = $this->mfunciones_generales->VerificaCampoLead($campo_verifica, $valor_verifica);

                                if($resultado_verifica != '')
                                {
                                    $aux_error = 1;

                                    $arrError[] = array(
                                        'hoja' => $hoja_nombre, 'linea' => $row, 'campo' => $campo_verifica, 'valor' => $valor_verifica, 'mensaje' => $resultado_verifica
                                    );
                                }

                                // celular
                                $resultado_verifica = '';
                                    $valor_verifica = $celular;   // <-- Variable del Campo
                                    $campo_verifica = 'celular';  // <-- Nombre del Campo
                                    $resultado_verifica = $this->mfunciones_generales->VerificaCampoLead($campo_verifica, $valor_verifica);

                                if($resultado_verifica != '')
                                {
                                    $aux_error = 1;

                                    $arrError[] = array(
                                        'hoja' => $hoja_nombre, 'linea' => $row, 'campo' => $campo_verifica, 'valor' => $valor_verifica, 'mensaje' => $resultado_verifica
                                    );
                                }

                                // correo
                                $resultado_verifica = '';
                                    $valor_verifica = $correo;   // <-- Variable del Campo
                                    $campo_verifica = 'correo';  // <-- Nombre del Campo
                                    $resultado_verifica = $this->mfunciones_generales->VerificaCampoLead($campo_verifica, $valor_verifica);

                                if($resultado_verifica != '')
                                {
                                    $aux_error = 1;

                                    $arrError[] = array(
                                        'hoja' => $hoja_nombre, 'linea' => $row, 'campo' => $campo_verifica, 'valor' => $valor_verifica, 'mensaje' => $resultado_verifica
                                    );
                                }
                                
                                // mensaje
                                $resultado_verifica = '';
                                    $valor_verifica = $captura_mensaje;   // <-- Variable del Campo
                                    $campo_verifica = 'string_vacio';  // <-- Nombre del Campo
                                    $resultado_verifica = $this->mfunciones_generales->VerificaCampoLead($campo_verifica, $valor_verifica);

                                if($resultado_verifica != '')
                                {
                                    $aux_error = 1;

                                    $arrError[] = array(
                                        'hoja' => $hoja_nombre, 'linea' => $row, 'campo' => $campo_verifica, 'valor' => $valor_verifica, 'mensaje' => $resultado_verifica
                                    );
                                }
                                
                                // codigo cliente
                                $resultado_verifica = '';
                                    $valor_verifica = $codigo_cliente;   // <-- Variable del Campo
                                    $campo_verifica = 'string_vacio';  // <-- Nombre del Campo
                                    $resultado_verifica = $this->mfunciones_generales->VerificaCampoLead($campo_verifica, $valor_verifica);

                                if($resultado_verifica != '')
                                {
                                    $aux_error = 1;

                                    $arrError[] = array(
                                        'hoja' => $hoja_nombre, 'linea' => $row, 'campo' => $campo_verifica, 'valor' => $valor_verifica, 'mensaje' => $resultado_verifica
                                    );
                                }

                                // matricula
                                $resultado_verifica = '';
                                    $valor_verifica = $matricula;   // <-- Variable del Campo
                                    $campo_verifica = 'matricula';  // <-- Nombre del Campo
                                    $resultado_verifica = $this->mfunciones_generales->VerificaCampoLead($campo_verifica, $valor_verifica);

                                $codigo_matricula = 0;
                                $codigo_usuario = 0;
                                    
                                if($resultado_verifica != '')
                                {
                                    $aux_error = 1;

                                    $arrError[] = array(
                                        'hoja' => $hoja_nombre, 'linea' => $row, 'campo' => $campo_verifica, 'valor' => $valor_verifica, 'mensaje' => $resultado_verifica
                                    );
                                }
                                else
                                {
                                    // Sólo para el caso de la matricula
                                    
                                    $arrConsultaMatricula = $this->mfunciones_logica->ObtenerCodigoEjecutivoUsuario($matricula);
                                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConsultaMatricula);
                                    
                                    if(isset($arrConsultaMatricula[0]))
                                    {
                                        $codigo_matricula = $arrConsultaMatricula[0]['ejecutivo_id'];
                                        $codigo_usuario = $arrConsultaMatricula[0]['usuario_id'];
                                    }
                                    
                                }
                                
                                // etapa
                                $resultado_verifica = '';
                                    $valor_verifica = $captura_estado;   // <-- Variable del Campo
                                    $campo_verifica = 'nombre_etapa';  // <-- Nombre del Campo
                                    $resultado_verifica = $this->mfunciones_generales->VerificaCampoLead($campo_verifica, $valor_verifica);

                                $estado_codigo = -1;
                                    
                                if($resultado_verifica != '')
                                {
                                    $aux_error = 1;

                                    $arrError[] = array(
                                        'hoja' => $hoja_nombre, 'linea' => $row, 'campo' => $campo_verifica, 'valor' => $valor_verifica, 'mensaje' => $resultado_verifica
                                    );
                                }
                                else
                                {
                                    // Sólo para el caso de la etapa
                                    
                                    $arrConsultaEtapa = $this->mfunciones_logica->ObtenerCodigoEtapaNombre($captura_estado);
                                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConsultaEtapa);
                                    
                                    if(isset($arrConsultaEtapa[0]))
                                    {
                                        $estado_codigo = $arrConsultaEtapa[0]['etapa_id'];
                                    }
                                    
                                }

                            /* VALIDACIONES FIN */

                            // Si no hay error, se guarda en el array

                            $data[] = array(
                                'campana_id' => $codigo_campana,
                                'campana_nombre' => $nombre_campana,
                                'idc' => $idc,
                                'codigo_cliente' => $codigo_cliente,
                                'nombre_cliente' => $nombre_cliente,
                                'empresa' => $empresa,
                                'ingreso' => $ingreso,
                                'direccion' => $direccion,
                                'telefono' => $telefono,
                                'celular' => $celular,
                                'correo' => $correo,
                                'matricula' => $matricula,
                                'mensaje' => $captura_mensaje,
                                'matricula_codigo' => $codigo_matricula,
                                'codigo_usuario' => $codigo_usuario,
                                'etapa' => $captura_estado,
                                'codigo_etapa' => $estado_codigo,
                            );
                        }

                        break;

                    // Cargado de Campañas - Productos
                    case 1:


                        break;
                    
                    default:
                        break;
                }
            }
            
            if(!isset($data))
            {
                echo 'No hay filas registrables en el archivo';
                exit();
            }
            
            if($aux_error == 0)
            {
                $_SESSION['arrResultadoImportacion_aux'] = $aux_error;
                $_SESSION['arrResultadoImportacion'] = $data;
            }
            else
            {
                $_SESSION['arrResultadoImportacion_aux'] = $aux_error;
                $_SESSION['arrResultadoImportacion'] = $arrError;
            }
            
            
        }
        else
        {
            echo $this->lang->line('FormularioNoFile');
            exit();
        }
        
    }
    
    public function Importacion_Resultado() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_SESSION['arrResultadoImportacion']))
        {
            js_error_div_javascript($this->lang->line('FormularioSinOpciones'));
            exit();
        }
        
        $data["aux"] = $_SESSION['arrResultadoImportacion_aux'];
        
        if($_SESSION['arrResultadoImportacion_aux'] == 0)
        {
            $data["aux_text"] = "Sin errores <i class='fa fa-check-square-o' aria-hidden='true'></i>";
        }
        else
        {
            $data["aux_text"] = "Errores en el archivo <i class='fa fa-times-circle' aria-hidden='true'></i>";
        }
        
        $data["arrRespuesta"] = $_SESSION['arrResultadoImportacion'];
        
        $this->load->view('importacion_masiva/view_form_resultado', $data);
    }
    
    public function Importacion_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        if(!isset($_SESSION['arrResultadoImportacion']) || $_SESSION['arrResultadoImportacion_aux'] != 0)
        {
            js_error_div_javascript($this->lang->line('FormularioSinOpciones'));
            exit();
        }
        
        $accion_usuario = $_SESSION["session_informacion"]["login"];
        $accion_fecha = date('Y-m-d H:i:s');
        
        $arrBase1 = $this->mfunciones_generales->ListadoCamapanaServicios();
        
        foreach ($_SESSION['arrResultadoImportacion'] as $key => $value) 
        {
            // Incluir el registro en la base de datos, no registrar en la tabla EMPRESA, solo en PROSPECTO, una vez que se consolide el LEAD o se desembolse se puede generar recien el registro de la empresa
        
            $ejecutivo_id = $value['matricula_codigo'];
            $tipo_persona_id = 1; // Por ahora sólo es un valor constante
            $empresa_id = -1; // Por ahora sólo es un valor constante
            $camp_id = $value['campana_id'];
            $prospecto_fecha_asignacion = $accion_fecha;
            $prospecto_idc = $value['idc'];
            $prospecto_codigo_cliente = $value['codigo_cliente'];
            $prospecto_nombre_cliente = $value['nombre_cliente'];
            $prospecto_empresa = $value['empresa'];
            $prospecto_ingreso = $value['ingreso'];
            $prospecto_direccion = $value['direccion'];
            $prospecto_direccion_geo = GEO_FIE;
            $prospecto_telefono = $value['telefono'];
            $prospecto_celular = $value['celular'];
            $prospecto_email = $value['correo'];
            $prospecto_mensaje = $value['mensaje'];
            $prospecto_tipo_lead = 1; // 1 = Asignado por Marketing (cargado masivo) 2 = Registrado por el agente
            $prospecto_matricula = $value['matricula'];
            
            $etapa_nueva = $value['codigo_etapa'];
            
            // PASO 1: Insertar en la tabla "prospecto"
            $arrResultado2 = $this->mfunciones_logica->InsertarLead_APP($prospecto_codigo_cliente, $prospecto_mensaje, $ejecutivo_id, $tipo_persona_id, $empresa_id, $camp_id, $prospecto_fecha_asignacion, $prospecto_idc, $prospecto_nombre_cliente, $prospecto_empresa, $prospecto_ingreso, $prospecto_direccion, $prospecto_direccion_geo, $prospecto_telefono, $prospecto_celular, $prospecto_email, $prospecto_tipo_lead, $prospecto_matricula, '', $accion_usuario, $accion_fecha);

            // PASO 2: Se captura el ID del registro recíen insertado en la tabla "prospecto"
            $codigo_prospecto = $arrResultado2;

                // PASO 3: Se crea la carpeta del prospecto

                $path = RUTA_PROSPECTOS . 'afn_' . $codigo_prospecto;

                if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
                {
                    mkdir($path, 0755, TRUE);
                }

                    // Se crea el archivo html para evitar ataques de directorio
                    $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                    write_file($path . '/index.html', $cuerpo_html);

                // PASO 4: Se registra la fecha en el calendario del Ejecutivo de Cuentas

                $cal_visita_ini = date('Y-m-d 08:00:00');
                $cal_visita_fin = date('Y-m-d 15:00:00');

                $this->mfunciones_logica->InsertarFechaCaendario($ejecutivo_id, $codigo_prospecto, 1, $cal_visita_ini, $cal_visita_fin, $accion_usuario, $accion_fecha);

                // PASO 5: Insertar los servicios seleccionados del prospecto
                    // (se elimina todos los servicios del prospecto para volver a insertarlos)
                    $this->mfunciones_logica->EliminarServiciosProspecto($codigo_prospecto);

                if (isset($arrBase1[$camp_id]['servicios'])) 
                {
                    foreach ($arrBase1[$camp_id]['servicios'] as $key => $value) 
                    {
                        $this->mfunciones_logica->InsertarServiciosProspecto($codigo_prospecto, $value, $accion_usuario, $accion_fecha);
                    }
                }
                
                // PASO 6: Se registra el estado e Hito
                
                $etapa_actual = 0;
                //$etapa_nueva = 1; // Lead Asignado
                
                /**** REGISTRAR SEGUIMIENTO, ETAPA E HITO ****/        
                $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, $etapa_nueva, $etapa_actual, $accion_usuario, $accion_fecha, 2);
                /***  REGISTRAR SEGUIMIENTO ***/
                $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, $etapa_nueva, 0, 'Asignación Lead al Agente', $accion_usuario, $accion_fecha);
        }
        
        // PASO 7: Se notifica por PUSH a los agentes
                
        $arrAgentes = $this->mfunciones_generales->ArrGroupBy($_SESSION['arrResultadoImportacion'], 'codigo_usuario');

        foreach ($arrAgentes as $key2 => $value) 
        {
            /* Se Envía a la APP la Notificación por Push   tipo_notificacion, $tipo_visita, $codigo_visita */
            $this->mfunciones_generales->EnviarNotificacionPush(1, 3, $key2);
        }
        
        $data['texto'] = 'Se importaron ' . count($_SESSION['arrResultadoImportacion']) . ' Leads de las campañas indicadas y fueron asignados a los agentes respecto a su matrícula.';

        // Se realizar el unset de la variable de session
        
        if(isset($_SESSION['arrResultadoImportacion']))
        {
            unset($_SESSION['arrResultadoImportacion']);
            unset($_SESSION['arrResultadoImportacion_aux']);
        }
        
        $this->load->view('importacion_masiva/view_importacion_guardado', $data);
    }

}
?>