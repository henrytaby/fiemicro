<?php
/**
 * @file 
 * Codigo que implementa el controlador para FLUJO DE TRABAJO
 * @brief  CONTROLADOR FLUJO DE TRABAJO
 * @author JRAD
 * @date Mar 21, 2014
 * @copyright 2017 JRAD
 */
/**
 * Controlador para CONTROLADOR FLUJO DE TRABAJO
 * @brief CONTROLADOR FLUJO DE TRABAJO
 * @class Flujo_controller
 */
class Flujo_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 18;

    function __construct() {
        parent::__construct();
    }
    /**
     * carga la vista para el formulario de login
     * @brief CARGAR LOGIN
     * @author JRAD
     * @date Mar 21, 2014     
     */
    
    public function Flujo_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
                
        $this->load->view('flujo/view_flujo_ver');
        
    }
    
    public function Flujo_VerDetalle() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');

        if(!isset($_POST['codigo_flujo']) || !isset($_POST['editar']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            $codigo_flujo = $this->input->post('codigo_flujo', TRUE);
            $editar = $this->input->post('editar', TRUE);
            
            $arrResultado = $this->mfunciones_logica->ObtenerDatosFlujo(-1, $codigo_flujo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0]))
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) {
                    $item_valor = array(
                        "etapa_id" => $value["etapa_id"],
                        "etapa_nombre" => $value["etapa_nombre"],
                        "etapa_detalle" => $value["etapa_detalle"],
                        "etapa_depende" => $value["etapa_depende"],
                        "etapa_tiempo" => $value["etapa_tiempo"],
                        "etapa_notificar_correo" => $value["etapa_notificar_correo"],
                        "rol_codigo" => $value["rol_codigo"],
                        "rol_nombre" => $value["rol_nombre"],
                        "etapa_categoria" => $value["etapa_categoria"]

                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
                
                $new = array();
                foreach ($lst_resultado as $a){
                    $new[$a['etapa_depende']][] = $a;
                }

                $tree = $this->mfunciones_generales->createTree($new, array($lst_resultado[0]));

                $data["tree"] = $tree;
            }
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }            

            $data["arrRespuesta"] = $lst_resultado;
            $data["editar"] = $editar;

            $this->load->view('flujo/view_flujo_ver_detalle', $data);
        }
    }
    
    public function FlujoForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        if(!isset($_POST['codigo_etapa']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // UPDATE

            $codigo_etapa = $this->input->post('codigo_etapa', TRUE);
            $codigo_flujo = $this->input->post('codigo_flujo', TRUE);
            
            $arrResultado = $this->mfunciones_logica->ObtenerDatosFlujo($codigo_etapa, $codigo_flujo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0]))
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) {
                    $item_valor = array(
                        "etapa_id" => $value["etapa_id"],
                        "etapa_nombre" => $value["etapa_nombre"],
                        "etapa_detalle" => $value["etapa_detalle"],
                        "etapa_depende" => $value["etapa_depende"],
                        "etapa_tiempo" => $value["etapa_tiempo"],
                        "etapa_notificar_correo" => $value["etapa_notificar_correo"],
                        "rol_codigo" => $value["rol_codigo"],
                        "rol_nombre" => $value["rol_nombre"],
                        "etapa_categoria" => $value["etapa_categoria"],
                        "etapa_alerta_correo" => $value["etapa_alerta_correo"],
                        "etapa_alerta_dias" => $value["etapa_alerta_dias"],
                        "etapa_alerta_hora" => $value["etapa_alerta_hora"],
                        "etapa_color" => $value["etapa_color"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            }

            $data["arrDias"] = explode(",", $lst_resultado[0]['etapa_alerta_dias']);
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
            $data["arrRespuesta"] = $lst_resultado;

            // Listado de los registros parent
            $arrParent = $this->mfunciones_logica->ObteneParentFlujo($codigo_etapa, 1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrParent);                
            $data["arrParent"] = $arrParent;
            
            // Listado de los roles
            $estado_rol = 0; // Inversa 0=Activos 1=Inactivos
            $arrRoles = $this->mfunciones_logica->ObtenerDatosRoles($estado_rol);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRoles);                
            $data["arrRoles"] = $arrRoles;
            
            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

            $this->load->view('flujo/view_flujo_form', $data);
        }
    }
    
    public function Flujo_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
        $codigo_parent = $this->input->post('codigo_parent', TRUE);
        $etapa_nombre = $this->input->post('etapa_nombre', TRUE);
        $etapa_detalle = $this->input->post('etapa_detalle', TRUE);
        $etapa_tiempo = $this->input->post('etapa_tiempo', TRUE);
        $notificar = $this->input->post('notificar', TRUE);
        $codigo_rol = 1;
        
        $etapa_color = $this->input->post('etapa_color', TRUE);
        
        $alertar = $this->input->post('alertar', TRUE);
        $etapa_alerta_hora = $this->input->post('etapa_alerta_hora', TRUE);
        $arrDias = $this->input->post('dias_list', TRUE);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDias);
        
        $dias_list = '';
        
        if (isset($arrDias[0])) 
        {
            foreach ($arrDias as $key => $value) 
            {
                $dias_list .= $value . ',';
            }
        }
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if((int)$estructura_id <= -1 || $etapa_nombre == "" || $etapa_detalle == "" || (int)$etapa_tiempo == 0 || $notificar == "" || $alertar == "" || (int)$codigo_rol == 0)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        if($alertar == 1)
        {
            if($dias_list == "" || (int)$etapa_alerta_hora < 0 || (int)$etapa_alerta_hora > 23)
            {
                js_error_div_javascript($this->lang->line('FormularioIncompleto') . " Complete la configuración del envío de la alerta.");
            exit();
            }
        }
        
        $this->mfunciones_logica->UpdateFlujo($codigo_parent, $etapa_nombre, $etapa_detalle, $etapa_tiempo, $notificar, $codigo_rol, $nombre_usuario, $fecha_actual, $estructura_id, $alertar, $etapa_alerta_hora, $dias_list, $etapa_color);
        
        $this->Flujo_Ver();        
    }
    
    public function FlujoAsignarForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        if(!isset($_POST['codigo_etapa']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            // UPDATE

            $codigo_etapa = $this->input->post('codigo_etapa', TRUE);
            $codigo_flujo = $this->input->post('codigo_flujo', TRUE);
            
            $arrResultado = $this->mfunciones_logica->ObtenerDatosFlujoEspec($codigo_etapa);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0]))
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) {
                    $item_valor = array(
                        "etapa_id" => $value["etapa_id"],
                        "etapa_nombre" => $value["etapa_nombre"],
                        "etapa_detalle" => $value["etapa_detalle"],
                        "etapa_depende" => $value["etapa_depende"],
                        "etapa_tiempo" => $value["etapa_tiempo"],
                        "etapa_notificar_correo" => $value["etapa_notificar_correo"],
                        "etapa_categoria" => $value["etapa_categoria"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            }
            else 
            {
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
            }
            
            // Listado de Roles de la Etapa     Tipo = 1
            
                if(isset($_SESSION["arrListaRol"]))
                {
                    unset($_SESSION["arrListaRol"]);
                }

                $arrListaRol = $this->mfunciones_logica->ObtenerListaEtapaRol($codigo_etapa, 1);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrListaRol);

                if (isset($arrListaRol[0]))
                {
                    $_SESSION["arrListaRol"] = $arrListaRol;
                }
                
            // Listado de Usuarios de la Etapa     Tipo = 2
            
                if(isset($_SESSION["arrListaUsuario"]))
                {
                    unset($_SESSION["arrListaUsuario"]);
                }

                $arrListaUsuario = $this->mfunciones_logica->ObtenerListaEtapaUsuario($codigo_etapa, 2);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrListaUsuario);

                if (isset($arrListaUsuario[0]))
                {
                    $_SESSION["arrListaUsuario"] = $arrListaUsuario;
                }

            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
            $data["arrRespuesta"] = $lst_resultado;
            
            // Listado de los roles
            $arrRoles = $this->mfunciones_logica->ObtenerDatosRoles(0);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRoles);                
            $data["arrRoles"] = $arrRoles;
            
            // Listado de los roles
            $arrUsuarios = $this->mfunciones_logica->ObtenerLista_UsuariosActivos();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrUsuarios);                
            $data["arrUsuarios"] = $arrUsuarios;
            
            $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();

            $this->load->view('flujo/view_flujo_responsable_form', $data);
        }
    }
    
    public function FlujoAsignarForm_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $estructura_id = $this->input->post('estructura_id', TRUE);
                
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if((int)$estructura_id <= -1)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        // Se verifica si los arrays en session existen y tienen valores
        
        $contador_registros = 0;
        
        if(isset($_SESSION['arrListaRol']) && count($_SESSION['arrListaRol'])>0)
        {
            $contador_registros += count($_SESSION['arrListaRol']);
        }
        
        if(isset($_SESSION['arrListaUsuario']) && count($_SESSION['arrListaUsuario'])>0)
        {
            $contador_registros += count($_SESSION['arrListaUsuario']);
        }
        
        if($contador_registros == 0)
        {
            js_error_div_javascript('Debe seleecionar por lo menos 1 Rol o 1 Usuario.');
            exit();
        }
        else
        {
            // GUARDADO DE ROLES
            
                // Primero se elimina todos los roles de la etapa       Tipo Id = 1
                $tipo_id = 1;
                $this->mfunciones_logica->DeleteRolUsuarioEtapa($tipo_id, $estructura_id);
            
            if(isset($_SESSION['arrListaRol']) && count($_SESSION['arrListaRol'])>0)
            {
                foreach ($_SESSION['arrListaRol'] as $key1 => $value1) {
                    
                    $this->mfunciones_logica->InsertRolUsuarioEtapa($estructura_id, $tipo_id, $value1['codigo'], $nombre_usuario, $fecha_actual);
                }
            }
            
            // GUARDADO DE USUARIOS
            
                // Primero se elimina todos los usuarios de la etapa       Tipo Id = 2
                $tipo_id = 2;
                $this->mfunciones_logica->DeleteRolUsuarioEtapa($tipo_id, $estructura_id);
            
            if(isset($_SESSION['arrListaUsuario']) && count($_SESSION['arrListaUsuario'])>0)
            {
                foreach ($_SESSION['arrListaUsuario'] as $key2 => $value2) {
                    
                    $this->mfunciones_logica->InsertRolUsuarioEtapa($estructura_id, $tipo_id, $value2['codigo'], $nombre_usuario, $fecha_actual);
                }
            }
        }
        
        $this->load->view('flujo/view_flujo_responsable_form_guardar');
    }
    
    private function VerificaKey($array, $key, $val) {
        foreach ($array as $item)
            if (isset($item[$key]) && $item[$key] == $val)
                return true;
        return false;
    }
    
    public function Adicionar_Rol_Array() {
        
        $this->lang->load('general', 'castellano');
        
        $arrListaAccionistas = array();
        
        $catalogo_id = $this->input->post('catalogo_rol', TRUE);
        
        if ((int)$catalogo_id <= 0) 
        {            
            js_error_div_javascript($this->lang->line('CamposObligatorios'));
            exit();
        }
        
        if (isset($_SESSION["arrListaRol"])) 
        { 
            $arrListaAccionistas = $_SESSION["arrListaRol"];
        }
        
        if (!$this->VerificaKey($arrListaAccionistas, 'codigo', $catalogo_id))
        {
            $itemLista = array(
                "codigo" => $catalogo_id,
                "nombre" => $this->mfunciones_generales->getRolUsuario($catalogo_id)
                );
            
            array_push($arrListaAccionistas, $itemLista);
        }
        else
        {
            js_error_div_javascript('El Rol ya está seleccionado, por favor seleccione otro.');
            exit();
        }
        
        $_SESSION["arrListaRol"] = $arrListaAccionistas;
        
        $this->load->view('flujo/view_lista_rol');
    }
    
    public function Quitar_Rol_Array() {
        
        $this->lang->load('general', 'castellano');
        
        $arrListaAccionistas = array();
        
        $posi = $this->input->post('posicion', TRUE);
        
        foreach ($_SESSION["arrListaRol"] as $key => $value) 
        {
            if ($key != $posi) 
            {
                array_push($arrListaAccionistas, $value);
            }
        }
        
        $_SESSION["arrListaRol"] = $arrListaAccionistas;
        
        $this->load->view('flujo/view_lista_rol');
    }
    
    public function Ver_Rol_Array() {
        
        $this->lang->load('general', 'castellano');
        
        $this->load->view('flujo/view_lista_rol');
    }
    
    public function Adicionar_Usuario_Array() {
        
        $this->lang->load('general', 'castellano');
        
        $arrListaAccionistas = array();
        
        $catalogo_id = $this->input->post('catalogo_usuario', TRUE);
        
        if ((int)$catalogo_id <= 0) 
        {            
            js_error_div_javascript($this->lang->line('CamposObligatorios'));
            exit();
        }
        
        if (isset($_SESSION["arrListaUsuario"])) 
        { 
            $arrListaAccionistas = $_SESSION["arrListaUsuario"];
        }
        
        if (!$this->VerificaKey($arrListaAccionistas, 'codigo', $catalogo_id))
        {
            $itemLista = array(
                "codigo" => $catalogo_id,
                "nombre" => $this->mfunciones_generales->getNombreUsuario($catalogo_id)
                );
            
            array_push($arrListaAccionistas, $itemLista);
        }
        else
        {
            js_error_div_javascript('El Usuario ya está seleccionado, por favor seleccione otro.');
            exit();
        }
        
        $_SESSION["arrListaUsuario"] = $arrListaAccionistas;
        
        $this->load->view('flujo/view_lista_usuario');
    }
    
    public function Quitar_Usuario_Array() {
        
        $this->lang->load('general', 'castellano');
        
        $arrListaAccionistas = array();
        
        $posi = $this->input->post('posicion', TRUE);
        
        foreach ($_SESSION["arrListaUsuario"] as $key => $value) 
        {
            if ($key != $posi) 
            {
                array_push($arrListaAccionistas, $value);
            }
        }
        
        $_SESSION["arrListaUsuario"] = $arrListaAccionistas;
        
        $this->load->view('flujo/view_lista_usuario');
    }
    
    public function Ver_Usuario_Array() {
        
        $this->lang->load('general', 'castellano');
        
        $this->load->view('flujo/view_lista_usuario');
    }
}
?>