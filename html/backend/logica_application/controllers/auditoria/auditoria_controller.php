<?php
/**
 * @file 
 * Codigo que implementa el controlador para la auditoría
 * @brief  CONTROLADOR DE CONFIGURACIÓN
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
class Auditoria_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 4;

    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el cargado de auditoría
     * @brief CARGAR AUDITORÍA
     * @author JRAD
     * @date 2017
     */
    public function Auditoria_Ver() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
	// Listado de tablas en la auditoría		
        $arrResultado = $this->mfunciones_logica->ObtenerAuditoriaTablas();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "lista_codigo" => $value["table_name"],
                    "lista_valor" => $value["table_name"],
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }

            $data["arrListadoTabla"] = $lst_resultado;
		
		// Listado de usuarios
        $arrResultado = $this->mfunciones_logica->ObtenerAuditoriaUsuarios();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) 
            {
				$valor_usuario = $value["usuario_app"] . ' ' . $value["usuario_nombres"] . ' - ' . $value["usuario_user"];
				
                $item_valor = array(
                    "lista_codigo" => $value["usuario_user"],
                    "lista_valor" => $valor_usuario,
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }

            $data["arrListadoUsuario"] = $lst_resultado;

            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);

        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('auditoria/view_auditoria_ver', $data);
    }
    
    public function Auditoria_Resultado() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
		
        $tabla = $this->input->post('tabla', TRUE);		
        $usuario = preg_replace('/[^a-zA-Z0-9_ .-]/s', '', $this->input->post('usuario', TRUE));
        
        if(null !== $this->input->post('fecha_inicio', TRUE))
        {
                $fecha_inicio = $this->input->post('fecha_inicio', TRUE);
                $fecha_inicio1 = $this->mfunciones_generales->getFormatoFechaDateTime($fecha_inicio . ' 00:00:01');
        }

        if(null !== $this->input->post('fecha_fin', TRUE))
        {
                $fecha_fin = $this->input->post('fecha_fin', TRUE);
                $fecha_fin1 = $this->mfunciones_generales->getFormatoFechaDateTime($fecha_fin . ' 23:59:59');
        }
		
        if($fecha_inicio == "" && $fecha_fin == "" && $tabla == -1 && $usuario == -1)
        {
            js_error_div_javascript($this->lang->line('FormularioFiltros'));
            exit();
        }
		
        if($fecha_inicio != "" && $fecha_fin == "")
        {
            js_error_div_javascript($this->lang->line('FormularioFechas'));
            exit();
        }
		
        if($fecha_inicio == "" && $fecha_fin != "")
        {
            js_error_div_javascript($this->lang->line('FormularioFechas'));
            exit();
        }
		
		$filtro_texto = "";
		
        if($tabla != -1)
        {
                $filtro_texto .= " <br /> Tabla: " . $tabla;
        }

        if($usuario != -1)
        {
                $filtro_texto .= " <br /> Usuario: " . $usuario;
        }

        $filtro_fechas = 0;

        if($fecha_inicio != "" && $fecha_fin != "")
        {
                $filtro_texto .= " <br /> Del: " . $fecha_inicio . " Al " . $fecha_fin;
                $filtro_fechas = 1;
        }
		
		// Filtro del Reporte
        $arrResultado = $this->mfunciones_logica->ReporteAuditoria($tabla, $usuario, $fecha_inicio1, $fecha_fin1, $filtro_fechas);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;
			
            foreach ($arrResultado as $key => $value) 
            {				
                $item_valor = array(
                    "audit_id" => $value["audit_id"],
                    "audit_accion" => $value["action"],
					"audit_tabla" => $value["table_name"],
                    "audit_pk1" => $value["pk1"],
					"audit_pk2" => $value["pk2"],
                    "audit_usuario" => $value["accion_usuario"],
					"audit_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["accion_fecha"])
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }

		$data["arrRespuesta"] = $lst_resultado;		
		$data["filtro_texto"] = $filtro_texto;
		
		$_SESSION["ResultadoReporteAuditoria"] = $lst_resultado;
		$_SESSION["ResultadoReporteAuditoriaFiltro"] = $filtro_texto;
		
		$this->load->view('auditoria/view_auditoria_resultado', $data);
    }
	
    public function Auditoria_Excel() {
		
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $lst_resultado = $_SESSION["ResultadoReporteAuditoria"];
        $filtro_texto = $_SESSION["ResultadoReporteAuditoriaFiltro"];

        $data["arrRespuesta"] = $lst_resultado;
        $data["filtro_texto"] = $filtro_texto;

        $this->load->view('auditoria/view_auditoria_excel', $data);
    }
	
    public function Auditoria_Detalle() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
		
		if(null == $this->input->post('codigo', TRUE))
		{
			js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
		}
		
		$codigo = $this->input->post('codigo', TRUE);
				
		// Filtro del Reporte
        $arrResultado = $this->mfunciones_logica->ReporteAuditoriaDetalle($codigo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;			
			
            foreach ($arrResultado as $key => $value) 
            {       
                $item_valor = array(
                    "audit_meta_id" => $value["audit_meta_id"],
                    "audit_id" => $value["audit_id"],
                    "audit_columna" => $value["col_name"],
                    "audit_anterior" => $value["old_value"],
                    "audit_nuevo" => $value["new_value"]
				);
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }

		$data["arrRespuesta"] = $lst_resultado;
		
		$this->load->view('auditoria/view_auditoria_detalle', $data);
    }
    
    public function Auditoria_Acceso_Ver() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
		// Listado de tablas en la auditoría		
        $arrResultado = $this->mfunciones_logica->ObtenerAuditoriaAcceso();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "lista_codigo" => $value["auditoria_usuario"],
                    "lista_valor" => $value["auditoria_usuario"],
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }

        $data["arrListadoAcceso"] = $lst_resultado;        
		
		// Listado de acciones de acceso
        $arrResultado2 = $this->mfunciones_logica->ObtenerAuditoriaTipoAcceso();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

        if (isset($arrResultado2[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado2 as $key => $value) 
            {                
                $item_valor = array(
                    "lista_codigo" => $value["auditoria_tipo_acceso"],
                    "lista_valor" => $value["auditoria_tipo_acceso"]
                );
                $lst_resultado2[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado2[0] = $arrResultado2;
        }

        $data["arrListadoAccion"] = $lst_resultado2;
		
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);

        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('auditoria/view_auditoria_acceso_ver', $data);
    }
    
    public function Auditoria_Acceso_Resultado() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
		
        $acceso= $this->input->post('acceso', TRUE);		
        $accion = $this->input->post('accion', TRUE);
		
        if(null !== $this->input->post('fecha_inicio', TRUE))
        {
            $fecha_inicio = $this->input->post('fecha_inicio', TRUE);
            $fecha_inicio1 = $this->mfunciones_generales->getFormatoFechaDateTime($fecha_inicio . ' 00:00:01');
        }

        if(null !== $this->input->post('fecha_fin', TRUE))
        {
            $fecha_fin = $this->input->post('fecha_fin', TRUE);
            $fecha_fin1 = $this->mfunciones_generales->getFormatoFechaDateTime($fecha_fin . ' 23:59:59');
        }
		
        if($fecha_inicio == "" && $fecha_fin == "" && $acceso == -1 && $accion == -1)
        {
            js_error_div_javascript($this->lang->line('FormularioFiltros'));
            exit();
        }
		
        if($fecha_inicio != "" && $fecha_fin == "")
        {
            js_error_div_javascript($this->lang->line('FormularioFechas'));
            exit();
        }
		
        if($fecha_inicio == "" && $fecha_fin != "")
        {
            js_error_div_javascript($this->lang->line('FormularioFechas'));
            exit();
        }
		
	$filtro_texto = "";
		
        if($acceso != -1)
        {
            $filtro_texto .= " <br /> Acceso: " . $acceso;
        }

        if($accion != -1)
        {
            $filtro_texto .= " <br /> Usuario: " . $accion;
        }

        $filtro_fechas = 0;

        if($fecha_inicio != "" && $fecha_fin != "")
        {
            $filtro_texto .= " <br /> Del: " . $fecha_inicio . " Al " . $fecha_fin;
            $filtro_fechas = 1;
        }
		
	// Filtro del Reporte
        $arrResultado = $this->mfunciones_logica->ReporteAuditoriaAcceso($acceso, $accion, $fecha_inicio1, $fecha_fin1, $filtro_fechas);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;
			
            foreach ($arrResultado as $key => $value) 
            {				
                $item_valor = array(
                    "audit_id" => $value["auditoria_id"],
                    "audit_usuario" => $value["auditoria_usuario"],
                    "audit_tipo_acceso" => $value["auditoria_tipo_acceso"],
                    "audit_ip" => $value["auditoria_ip"],
                    "audit_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["auditoria_fecha"])
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }

            $data["arrRespuesta"] = $lst_resultado;		
            $data["filtro_texto"] = $filtro_texto;

            $_SESSION["ResultadoReporteAuditoriaAcceso"] = $lst_resultado;
            $_SESSION["ResultadoReporteAuditoriaFiltroAcceso"] = $filtro_texto;

            $this->load->view('auditoria/view_auditoria_acceso_resultado', $data);
    }
    
    public function Auditoria_Acceso_Excel() {
		
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $lst_resultado = $_SESSION["ResultadoReporteAuditoriaAcceso"];
        $filtro_texto = $_SESSION["ResultadoReporteAuditoriaFiltroAcceso"];

        $data["arrRespuesta"] = $lst_resultado;
        $data["filtro_texto"] = $filtro_texto;

        $this->load->view('auditoria/view_auditoria_acceso_excel', $data);
    }
    
}
?>