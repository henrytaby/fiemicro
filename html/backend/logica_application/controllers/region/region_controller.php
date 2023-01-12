<?php
/**
 * @file 
 * Codigo que implementa el controlador para la asingación de Regiones a usuarios
 * @brief  CONTROLADOR DE CONFIGURACIÓN
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para la administracion para la asingación de Regiones a usuarios
 * @brief CONTROLADOR DEL LOGUEO
 * @class Login_controller 
 */
class Region_controller extends MY_Controller {        
        
	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 41;
        
    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
    
    public function ListaUsuarios() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerLista_Usuarios();
        
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                $item_valor = array(
                    "usuario_codigo" => $value["usuario_id"],
                    "usuario_user" => $value["usuario_user"],
                    "usuario_nombre_completo" => $value["usuario_nombres"] . " " . $value["usuario_app"] . " " . $value["usuario_apm"],
                    "usuario_email" => $value["usuario_email"],
                    "usuario_telefono" => $value["usuario_telefono"],
                    "usuario_direccion" => $value["usuario_direccion"],
                    "usuario_rol" => $this->mfunciones_generales->getRolUsuario($value["usuario_rol"]),
                    "usuario_fecha_creacion" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["usuario_fecha_creacion"]),
                    "usuario_activo" => $this->mfunciones_generales->GetValorCatalogo($value["usuario_activo"], 'activo'),
                    "estructura_agencia_nombre" => $value["estructura_agencia_nombre"]
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
        
        $this->load->view('region/view_listado_usuarios', $data);        
    }
    
    public function RegionForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // 0=Insert    1=Update
        
        $tipo_accion = 0;
        
        if(isset($_POST['tipo_accion']))
        {
            $tipo_accion = $_POST['tipo_accion'];
            
            // UPDATE
            
            $usuario_codigo = $_POST['usuario_codigo'];
            
            $arrResultado = $this->mfunciones_logica->ObtenerDatosUsuario($usuario_codigo);

            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                        
            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $dato_utlimo_ingreso = 'Aún no Ingresó al Sistema';
                    
                    if($value["usuario_fecha_ultimo_acceso"] != '1500-01-01 00:00:00')
                    {
                        $dato_utlimo_ingreso = $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["usuario_fecha_ultimo_acceso"]);
                    }
                    
                    $item_valor = array(
                        "usuario_codigo" => $value["usuario_id"],
                        "usuario_user" => $value["usuario_user"],
                        "usuario_nombre_completo" => $value["usuario_nombres"] . " " . $value["usuario_app"] . " " . $value["usuario_apm"],
                        "usuario_email" => $value["usuario_email"],
                        "usuario_rol" => $this->mfunciones_generales->getRolUsuario($value["usuario_rol"]),
                        "usuario_region" => $this->mfunciones_generales->ObtenerNombreRegionUsuario($value["usuario_id"]),
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            } 
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
            
            $data["arrRespuesta"] = $lst_resultado;
        }
        
        // Listado de las Regionales
        $arrRegion = $this->mfunciones_logica->ObtenerDatosRegional(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRegion);
                
            // Lista los perfiles disponibles
        
            if (isset($arrRegion[0])) 
            {
                $i = 0;

                foreach ($arrRegion as $key => $value) 
                {
                    $item_valor = array(
                        "estructura_regional_id" => $value["estructura_regional_id"],
                        "estructura_regional_nombre" => $value["estructura_regional_nombre"],
                        "estructura_regional_departamento" => ((int)$value["estructura_regional_ciudad"]==115 ? 'LA PAZ - EL ALTO' : 'Departamento ' . $this->mfunciones_generales->GetValorCatalogoDB($value["estructura_regional_departamento"], 'dir_departamento')),
                        "region_asignado" => $this->mfunciones_generales->GetRegionUsuario($usuario_codigo, $value["estructura_regional_id"])
                    );
                    $lst_resultado2[$i] = $item_valor;

                    $i++;
                }
            } 
            else 
            {
                $lst_resultado2[0] = $arrRegion;
            }
            
            $data["arrRegion"] = $lst_resultado2;
        
        
        $data["tipo_accion"] = $tipo_accion;
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('region/view_region_form', $data);    
    }
    
    public function Region_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        $arrRegion = $this->input->post('region_list', TRUE);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRegion);

        // 0=Insert    1=Update
        
        $tipo_accion = $this->input->post('tipo_accion', TRUE);
        
        $usuario_codigo = $this->input->post('estructura_id', TRUE);
        
        // INSERTAR LOS PERFILES SELECCIONADOS
        
            // 1. Se eliminan los perfiles del usuario
            $this->mfunciones_logica->EliminarRegionUsuario($usuario_codigo);
        
            // 2. Se registran los perfiles seleccionados
            
            if (isset($arrRegion[0])) 
            {
                foreach ($arrRegion as $key => $value) 
                {
                    $this->mfunciones_logica->InsertarRegionUsuario($usuario_codigo, $value, $nombre_usuario, $fecha_actual);
                }
            }
        
        $this->ListaUsuarios();        
    }
}
?>