<?php
/**
 * @file 
 * Codigo que implementa el controlador para los formularios dinámicos
 * @brief  CONTROLADOR DE FORMULARIOS DINÁMICOS
 * @author JRAD
 * @date 2019
 * @copyright 2019 JRAD
 */
/**
 * Controlador para la gestión de los componentes de los formularios dinámicos
 * @brief CONTROLADOR DE FORMULARIOS DINÁMICOS
 * @class Login_controller 
 */
class form_controller extends MY_Controller {        
        
	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 39;
        
    function __construct() {
      parent::__construct();
      $this->load->model('mfunciones_generales');
      $this->load->model('mfunciones_logica');
      $this->lang->load('general', 'castellano');
      $this->load->library('encrypt');
      $this->load->model('form_dinamico');    // Capa de Datos
      $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2019
     */
    public function Formulario_Ver() {
        $data["formularios"] = $this->form_dinamico->listadoFormularios();
        $this->load->view('form_dinamico/view_form_main', $data);
    }

    /*
    * Cambia el estado del Formulario de despublicado a publicado
    */
    public function Formulario_publicar () {
      $idFormulario = $this->input->post('idFormulario');
      $this->form_dinamico->cambiarPublicado($idFormulario, array('publicado' => true));
      $data["formularios"] = $this->form_dinamico->listadoFormularios();
      $this->load->view('form_dinamico/view_form_main', $data);
    }

    /*
    * Cambia el estado del Formulario de publicado a despublicado
    */
    public function Formulario_despublicar () {
      $idFormulario = $this->input->post('idFormulario');
      $this->form_dinamico->cambiarPublicado($idFormulario, array('publicado' => false));
      $data["formularios"] = $this->form_dinamico->listadoFormularios();
      $this->load->view('form_dinamico/view_form_main', $data);
    }

    public function configurarFormulario () {
      $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
      $this->load->view('form_dinamico/view_form_settings', $data);
    }

    /*
    * Para la creacion de Formularios
    */
    public function guardarFormulario () {
      $formatted = $this->input->post('json_stringify_formatted');
      $datosGuardar = json_decode($formatted);
      $formulario = $datosGuardar->formulario;
      $idFormulario = $this->form_dinamico->guardarFormulario($formulario, $datosGuardar->lista_elementos);
      $data["formularios"] = $this->form_dinamico->listadoFormularios();
      $this->load->view('form_dinamico/view_form_main', $data);
    }


    public function mostrarFormulario () {
      $idFormulario = $this->input->post('idFormulario');
      $data['idFormulario'] = $idFormulario;
      $data["formulario"] = $this->form_dinamico->listadoFormularios($idFormulario)[0];
      $data["componentes"] = json_encode($this->form_dinamico->listadoComponentesFormulario($idFormulario));
      $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
      $this->load->view('form_dinamico/view_form_edit', $data);
    }


    public function nuevoCasoFormulario () {
      $idFormulario = $this->input->post('idFormulario');
      $data['idFormulario'] = $idFormulario;
      $data["formulario"] = $this->form_dinamico->listadoFormularios($idFormulario)[0];
      $data["componentes"] = json_encode($this->form_dinamico->listadoComponentesFormulario($idFormulario));
      $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
      $this->load->view('form_dinamico/view_form_new_case', $data);
    }

    /*
    * Borra el formulario
    */
    public function Formulario_Borrar () {
      $idFormulario = $this->input->post('idFormulario');
      $this->form_dinamico->borrarFormulario($idFormulario);
      $data["formularios"] = $this->form_dinamico->listadoFormularios();
      $this->load->view('form_dinamico/view_form_main', $data);
    }



    /* PARA LAS INSTANCIAS DE LOS FLUJOS */
    public function Formulario_listar_instancias () {
      $idFormulario = $this->input->post('idFormulario');
      $instancias= $this->form_dinamico->listadoInstancia($idFormulario);
      $componentes = $this->form_dinamico->listadoComponentesFormulario($idFormulario);
      $cabeceras = array();
      foreach ($componentes as $componente) {
        if ($componente->name == '') {
          array_push($cabeceras, 'DEFAULT');
        } else {
          array_push($cabeceras, $componente->name);
        }
      }
      foreach ($instancias as $instancia) {
        $valores = $this->form_dinamico->valoresInstancia($instancia->id);
        $instancia->values = array();
        foreach ($componentes as $componente) {
          $val = new stdClass();
          $val->name = $componente->name;
          $val->value = $this->form_dinamico->getValue($componente, $instancia->id, true, false);
          array_push($instancia->values, $val);
        }
      }
      $data["instancias"] = $instancias;
      $data['cabeceras'] = $cabeceras;
      $data['idFormulario'] = $idFormulario;
      $this->load->view('form_dinamico/view_form_instancias', $data); 
    }


    public function Formulario_mostrar_instancia () {
      $idInstancia = $this->input->post('idInstancia');
      $instancia = $this->form_dinamico->mostrarInstancia($idInstancia);
      $valores = $this->form_dinamico->valoresInstancia($idInstancia);
      $formulario = $this->form_dinamico->listadoFormularios($instancia->fid_formulario)[0];
      $componentes = $this->form_dinamico->listadoComponentesFormulario($instancia->fid_formulario);

      $data['instancia'] = $instancia;
      $data['valores'] = $valores;
      $data['formulario'] = $formulario;
      foreach ($componentes as $componente) {
        $valor = $this->form_dinamico->getValue($componente, $idInstancia, false, false);
        $componente->defaultValue = $valor ? $valor : '';
      }
      $data['componentes'] = json_encode($componentes);
      $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
      $this->load->view('form_dinamico/view_form_instancia', $data); 
    }

    public function Formulario_guardar_instancia () {
      $formatted = $this->input->post('json_stringify_formatted');
      $datosGuardar = json_decode($formatted);
      if ($datosGuardar->crear) {
        $codigo_registro = $datosGuardar->parametros->codigo_registro;
        $tipo_bandeja = 1;
        $form_id = $datosGuardar->parametros->form_id;
        $idFormulario = $datosGuardar->parametros->form_id;
        $formulario = $this->form_dinamico->listadoFormularios($form_id)[0];
        $arrElementos = $datosGuardar->parametros->arrElementos;
        $this->form_dinamico->crearInstancia($form_id, $codigo_registro, $arrElementos);
      } else {
        $instancia = $datosGuardar->instancia;
        $idFormulario = $instancia->fid_formulario;
        $arrElementos = $datosGuardar->lista_elementos;
        $this->form_dinamico->actualizarInstancia($instancia->fid_formulario, $instancia->id, $arrElementos);
      }
      $instancias= $this->form_dinamico->listadoInstancia($idFormulario);
      $componentes = $this->form_dinamico->listadoComponentesFormulario($idFormulario);
      $cabeceras = array();
      foreach ($componentes as $componente) {
        if ($componente->name == '') {
          array_push($cabeceras, 'DEFAULT');
        } else {
          array_push($cabeceras, $componente->name);
        }
      }
      foreach ($instancias as $instancia) {
        $valores = $this->form_dinamico->valoresInstancia($instancia->id);
        $instancia->values = array();
        foreach ($componentes as $componente) {
          $val = new stdClass();
          $val->name = $componente->name;
          $val->value = $this->form_dinamico->getValue($componente, $instancia->id, true, false);
          array_push($instancia->values, $val);
        }
      }
      $data["instancias"] = $instancias;
      $data['cabeceras'] = $cabeceras;
      $data['idFormulario'] = $idFormulario;
      $this->load->view('form_dinamico/view_form_instancias', $data);
    }

    public function Formulario_eliminar_instancia () {
      $idInstancia = $this->input->post('idInstancia');
      $this->form_dinamico->eliminarInstancia($idInstancia);
      $idFormulario = $this->input->post('idFormulario');
      $instancias= $this->form_dinamico->listadoInstancia($idFormulario);
      $componentes = $this->form_dinamico->listadoComponentesFormulario($idFormulario);
      $cabeceras = array();
      foreach ($componentes as $componente) {
        if ($componente->name == '') {
          array_push($cabeceras, 'DEFAULT');
        } else {
          array_push($cabeceras, $componente->name);
        }
      }
      foreach ($instancias as $instancia) {
        $valores = $this->form_dinamico->valoresInstancia($instancia->id);
        $instancia->values = array();
        foreach ($componentes as $componente) {
          $val = new stdClass();
          $val->name = $componente->name;
          $val->value = $this->form_dinamico->getValue($componente, $instancia->id, true, false);
          array_push($instancia->values, $val);
        }
      }
      $data["instancias"] = $instancias;
      $data['cabeceras'] = $cabeceras;
      $data['idFormulario'] = $idFormulario;
      $this->load->view('form_dinamico/view_form_instancias', $data);  
    }
    /* PARA LAS INSTANCIAS DE LOS FLUJOS */
}
?>