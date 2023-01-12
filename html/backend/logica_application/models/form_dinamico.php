<?php
/**
 * Created by PhpStorm.
 * User: Itancara
 * Date: 15/08/2019
 * Time: 8:58
 */

class Form_dinamico extends CI_Model
{
  //private $Consulta_soapx;
  private $cache_dias_laborales = null;
  
  function __construct()
  {
    parent::__construct();
    $CI = &get_instance();
    $CI->load->library('soap/Consulta_soap');
    $this->consulta_soap = $CI->consulta_soap;
  }
  
  public function listadoFormularios($idformulario = null)
  {
      try {
        if (isset($idformulario)) {
          $sql = "
          SELECT * 
          FROM formulario
          WHERE id = $idformulario";
          
        } else {
          $sql = "
          SELECT *
          FROM formulario";
        }
      
        $consulta = $this->db->query($sql);
        $listaResultados = $consulta->result();
      } catch (Exception $e) {
        js_error_div_javascript("<span style='font-size:3.5mm;'>".$e->getMessage()."</span>");
        exit();
      }
    
      return $listaResultados;
  }

  public function listadoComponentesFormulario ($idformulario) {
      try {
        $sql = "
          SELECT c.*
          FROM formulario f
          INNER JOIN componente c ON c.fid_formulario = f.id
          WHERE f.id = $idformulario";
        $consulta = $this->db->query($sql)->result();
        foreach ($consulta as $componente) {
          $componente->value = [];
          $componente->values = $this->db->query('SELECT * FROM opciones WHERE fid_componente = '.$componente->id)->result();
          if ($componente->parent_codigo && $componente->parent_tipo && $componente->catalogo) {
            $componente->values = array();
            $catalogo = $this->mfunciones_logica->ObtenerCatalogo($componente->catalogo, $componente->parent_codigo, $componente->parent_tipo);
            foreach ($catalogo as $value) {
              array_push($componente->values, array('label' => $value['catalogo_descripcion'], 'value' => $value['catalogo_codigo'], 'selected' => 0 ));
            }
          }
        }
      } catch (Exception $e) {
        js_error_div_javascript("<span style='font-size:3.5mm;'>".$e->getMessage()."</span>");
        exit();
      }
      return $consulta;
  }

  // PARA EL LISTADO DE COMPONENTES
  public function listadoComponentesInstancia ($idformulario, $tipoBandeja, $codigo_registro) {
    try {
      $existeInstancia = $this->db->query("SELECT * FROM instancia WHERE codigo_registro = $codigo_registro AND fid_formulario = $idformulario")->result();
      if (isset($existeInstancia) && sizeof($existeInstancia) == 1) {
        $existe = true;
      } else {
        $existe = false;
      }
      
      $listaElementos = array();
      $consulta = $this->db->query("SELECT c.*
        FROM formulario f
        INNER JOIN componente c ON c.fid_formulario = f.id
        WHERE f.id = $idformulario AND tipo_bandeja = $tipoBandeja")->result();
      foreach ($consulta as $componente) {
        $valorFinal = null;
        if ($existe) {
          $idInstancia = $existeInstancia[0]->id;
          $valores = $this->db->query("SELECT * FROM valor WHERE fid_instancia = $idInstancia AND nombre_componente = '".$componente->name."';")->result();
        }
        $opciones = $this->db->query('SELECT * FROM opciones WHERE fid_componente = '.$componente->id)->result();
        foreach ($opciones as $opcion) {
          unset($opcion->id);
          unset($opcion->fid_componente);
          unset($opcion->selected);
        }
        $valoresGenerales = array(
          "form_id" => $idformulario,
          'ele_id' => $componente->id,
          "type" => $componente->type,
          "label" => $componente->label
        );
        switch ($componente->type) {
          case 'header':
            $valoresGenerales['align'] = $componente->align ? $componente->align : "";
            break;
          case 'text':
           if (isset($valores[0]) && $existeInstancia) {
              $valoresGenerales['defaultValue'] = $valores[0]->value;
            } else {
              $valoresGenerales['defaultValue'] = $componente->defaultValue ? $componente->defaultValue : "";
            }
            $valoresGenerales['subtype'] = $componente->subtype ? $componente->subtype : "";
            $valoresGenerales['maxlength'] = $componente->maxlength ? $componente->maxlength : "";
            $valoresGenerales['name'] = $componente->name ? $componente->name : "";
            $valoresGenerales['description'] = $componente->description ? $componente->description : "";
            $valoresGenerales['placeholder'] = $componente->placeholder ? $componente->placeholder : "";
            $valoresGenerales['className'] = $componente->className ? $componente->className : "";
            $valoresGenerales['readonly'] = $componente->readonly == 1;
            $valoresGenerales['required'] = $componente->required == 1;
            break;
          case 'textarea':
            if (isset($valores[0]) && $existeInstancia) {
              $valoresGenerales['defaultValue'] = $valores[0]->value;
            } else {
              $valoresGenerales['defaultValue'] = $componente->defaultValue ? $componente->defaultValue : "";
            }
            $valoresGenerales['maxlength'] = $componente->maxlength ? $componente->maxlength : "";
            $valoresGenerales['rows'] = $componente->rows;
            $valoresGenerales['name'] = $componente->name ? $componente->name : "";
            $valoresGenerales['description'] = $componente->description ? $componente->description : "";
            $valoresGenerales['placeholder'] = $componente->placeholder ? $componente->placeholder : "";
            $valoresGenerales['className'] = $componente->className ? $componente->className : "";
            $valoresGenerales['readonly'] = $componente->readonly == 1;
            $valoresGenerales['required'] = $componente->required == 1;
            break;
          case 'number':
            if (isset($valores[0]) && $existeInstancia) {
              $valoresGenerales['defaultValue'] = $valores[0]->value;
            } else {
              $valoresGenerales['defaultValue'] = $componente->defaultValue ? $componente->defaultValue : "";
            }
            $valoresGenerales['name'] = $componente->name ? $componente->name : "";
            $valoresGenerales['description'] = $componente->description ? $componente->description : "";
            $valoresGenerales['placeholder'] = $componente->placeholder ? $componente->placeholder : "";
            $valoresGenerales['className'] = $componente->className ? $componente->className : "";
            $valoresGenerales['readonly'] = $componente->readonly == 1;
            $valoresGenerales['required'] = $componente->required == 1;
            $valoresGenerales['maxlength'] = $componente->maxlength ? $componente->maxlength : "";
            $valoresGenerales['min'] = $componente->min ? $componente->min : "";
            $valoresGenerales['max'] = $componente->max ? $componente->max : "";
            $valoresGenerales['step'] = $componente->step ? $componente->step : "";
            break;
          case 'date':
            if (isset($valores[0]) && $existeInstancia) {
              $valoresGenerales['defaultValue'] = $valores[0]->value;
            } else {
              $valoresGenerales['defaultValue'] = $componente->defaultValue ? $componente->defaultValue : "";
            }
            $valoresGenerales['name'] = $componente->name ? $componente->name : "";
            $valoresGenerales['description'] = $componente->description ? $componente->description : "";
            $valoresGenerales['placeholder'] = $componente->placeholder ? $componente->placeholder : "";
            $valoresGenerales['className'] = $componente->className ? $componente->className : "";
            $valoresGenerales['readonly'] = $componente->readonly == 1;
            $valoresGenerales['required'] = $componente->required == 1;
            $valoresGenerales['subtype'] = $componente->subtype ? $componente->subtype : "";
            break;
          case 'select':
            if (isset($valores)) {
              foreach ($valores as $val) {
                foreach ($opciones as $opcion) {
                  if ($opcion->label == $val->label) {
                    $opcion->selected = true;
                  }
                }
              }
            }
            $valoresGenerales['name'] = $componente->name ? $componente->name : "";
            $valoresGenerales['description'] = $componente->description ? $componente->description : "";
            $valoresGenerales['placeholder'] = $componente->placeholder || "";
            $valoresGenerales['className'] = $componente->className ? $componente->className : "";
            $valoresGenerales['readonly'] = $componente->readonly == 1;
            $valoresGenerales['required'] = $componente->required == 1;
            $valoresGenerales['values'] = $opciones;
            if ($componente->parent_codigo && $componente->parent_tipo && $componente->catalogo) {
              $valoresGenerales['values'] = array();
              $catalogo = $this->mfunciones_logica->ObtenerCatalogo($componente->catalogo, $componente->parent_codigo, $componente->parent_tipo);
              if (isset($valores)) {
                if (is_array($valores) && sizeof($valores) > 0 ) {
                  foreach ($valores as $val) {
                    foreach ($catalogo as $opcionCatalogo) {
                      if ($opcionCatalogo['catalogo_descripcion'] == $val->label && $opcionCatalogo['catalogo_codigo'] == $val->value) {

                        $array_aux = array('label' => $opcionCatalogo['catalogo_descripcion'], 'value' => $opcionCatalogo['catalogo_codigo'], 'selected' => true);

                        array_push($valoresGenerales['values'], $array_aux);
                      } else {
                        array_push($valoresGenerales['values'], array('label' => $opcionCatalogo['catalogo_descripcion'], 'value' => $opcionCatalogo['catalogo_codigo']));
                      }
                    }

                    // Aux

                    array_unshift($valoresGenerales['values'], $array_aux);


                  }
                } else {
                  foreach ($catalogo as $opcionCatalogo) {
                    array_push($valoresGenerales['values'], array('label' => $opcionCatalogo['catalogo_descripcion'], 'value' => $opcionCatalogo['catalogo_codigo']));
                  }
                }
              } else {
                foreach ($catalogo as $opcionCatalogo) {
                  array_push($valoresGenerales['values'], array('label' => $opcionCatalogo['catalogo_descripcion'], 'value' => $opcionCatalogo['catalogo_codigo']));
                }
              }
            }
            break;
          case 'radio-group':
            if (isset($valores)) {
              foreach ($valores as $val) {
                foreach ($opciones as $opcion) {
                  if ($opcion->label == $val->label) {
                    $opcion->selected = true;
                  }
                }
              }
            }
            $valoresGenerales['name'] = $componente->name ? $componente->name : "";
            $valoresGenerales['description'] = $componente->description ? $componente->description : "";
            $valoresGenerales['placeholder'] = $componente->placeholder ? $componente->placeholder : "";
            $valoresGenerales['className'] = $componente->className ? $componente->className : "";
            $valoresGenerales['readonly'] = $componente->readonly == 1;
            $valoresGenerales['required'] = $componente->required == 1;
            $valoresGenerales['values'] = $opciones;
            
            if ($componente->parent_codigo && $componente->parent_tipo && $componente->catalogo) {
              $valoresGenerales['values'] = array();
              $catalogo = $this->mfunciones_logica->ObtenerCatalogo($componente->catalogo, $componente->parent_codigo, $componente->parent_tipo);
              foreach ($catalogo as $value) {
                array_push($valoresGenerales['values'], array('label' => $value['catalogo_descripcion'], 'value' => $value['catalogo_codigo'], 'selected' => 0 ));
              }
            }
            break;
          case 'checkbox-group':
            if (isset($valores)) {
              foreach ($valores as $val) {
                foreach ($opciones as $opcion) {
                  if ($opcion->label == $val->label && $val->value == 1) {
                    $opcion->selected = true;
                  }
                }
              }
            }
            $valoresGenerales['name'] = $componente->name ? $componente->name : "";
            $valoresGenerales['description'] = $componente->description ? $componente->description : "";
            $valoresGenerales['placeholder'] = $componente->placeholder ? $componente->placeholder : "";
            $valoresGenerales['className'] = $componente->className ? $componente->className : "";
            $valoresGenerales['readonly'] = $componente->readonly == 1;
            $valoresGenerales['required'] = $componente->required == 1;
            $valoresGenerales['values'] = $opciones;
            if ($componente->parent_codigo && $componente->parent_tipo && $componente->catalogo) {
              $valoresGenerales['values'] = array();
              $catalogo = $this->mfunciones_logica->ObtenerCatalogo($componente->catalogo, $componente->parent_codigo, $componente->parent_tipo);
              foreach ($catalogo as $value) {
                array_push($valoresGenerales['values'], array('label' => $value['catalogo_descripcion'], 'value' => $value['catalogo_codigo'], 'selected' => 0 ));
              }
            }
            break;
          case 'drawable':
            if (isset($valores[0]) && $existeInstancia) {
              $valoresGenerales['defaultValue'] = $valores[0]->value;
            } else {
              $valoresGenerales['defaultValue'] = $componente->defaultValue ? $componente->defaultValue : "";
            }
            $valoresGenerales['name'] = $componente->name ? $componente->name : "";
            $valoresGenerales['description'] = $componente->description ? $componente->description : "";
            $valoresGenerales['placeholder'] = $componente->placeholder ? $componente->placeholder : "";
            $valoresGenerales['className'] = $componente->className ? $componente->className : "";
            $valoresGenerales['readonly'] = $componente->readonly == 1;
            $valoresGenerales['required'] = $componente->required == 1;
            break;
          case 'map':
            if (isset($valores[0]) && $existeInstancia) {
              $valoresGenerales['defaultValue'] = $valores[0]->value;
            } else {
              $valoresGenerales['defaultValue'] = $componente->defaultValue ? $componente->defaultValue : "";
            }
            $valoresGenerales['name'] = $componente->name ? $componente->name : "";
            $valoresGenerales['description'] = $componente->description ? $componente->description : "";
            $valoresGenerales['placeholder'] = $componente->placeholder ? $componente->placeholder : "";
            $valoresGenerales['className'] = $componente->className ? $componente->className : "";
            $valoresGenerales['readonly'] = $componente->readonly == 1;
            $valoresGenerales['required'] = $componente->required == 1;
            break;
          default:
            break;
        }
        array_push($listaElementos, $valoresGenerales);
      }
      return $listaElementos;
    } catch (Exception $e) {
      js_error_div_javascript("<span style='font-size:3.5mm;'>".$e->getMessage()."</span>");
      exit();
    }
    return $consulta;
  }
  // PARA EL LISTADO DE COMPONENTES

  public function guardarFormulario ($formulario, $componentes) {
    try {
      $this->db->trans_begin();
      if (strlen($formulario->nombre) < 1) {
        throw new Exception('El Formulario no puede ir sin nombre.');
      }
      if (isset($formulario->id)) {
        $yaExiste = $this->db->query("SELECT * FROM formulario WHERE upper(nombre) = upper('$formulario->nombre') AND id <> $formulario->id;")->result();
        if (sizeof($yaExiste) > 0 ) {
          throw new Exception('El Formulario con el nombre '.$formulario->nombre.' Ya existe');
        }
        $this->db->where('id', $formulario->id);
        $this->db->update('formulario', $formulario);
        $idFormulario = $formulario->id;
      } else {
        $yaExiste = $this->db->query("SELECT * FROM formulario WHERE upper(nombre) = upper('$formulario->nombre');")->result();
        if (sizeof($yaExiste) > 0 ) {
          throw new Exception('El Formulario con el nombre '.$formulario->nombre.' Ya existe');
        }
        $this->db->insert('formulario', $formulario);
        $idFormulario =  $insert_id = $this->db->insert_id();
      }
      $componentesValores = $this->db->query("SELECT * FROM componente WHERE fid_formulario = $idFormulario")->result();
      foreach ($componentesValores as $comp) {
        $this->db->where('fid_componente', $comp->id);
        $this->db->delete('opciones');
        $this->db->where('id', $comp->id);
        $this->db->delete('componente');
      }
      $nombresComponentes = array();
      foreach ($componentes as $componente) {
        if (strpos($componente->name, ' ') !== false) {
          throw new Exception("El campo '".$componente->name."' no debe contener espacios y tampoco mayusculas y debe seguir la notacion snake case.");
        }
        $nombre = strtoupper(trim($componente->name));
        if (in_array($nombre, $nombresComponentes)) {
          $this->db->trans_rollback();
          throw new Exception('No se puede guardar el formulario, debido a que existe mas de un componente con el nombre '.$componente->name);
        }
        array_push($nombresComponentes, $nombre);
        $yaExisteEnDB = $this->db->query("SELECT * FROM componente WHERE name = '$componente->name';")->result();
        if (sizeof($yaExisteEnDB) > 0) {
          throw new Exception('No se puede guardar el formulario, debido a que existe mas de un componente con el nombre '.$componente->name);
        }
      }
      foreach ($componentes as $componente) {
        if (isset($componente->values)) {
          $opciones = $componente->values;
        }
        unset($componente->values);
        unset($componente->idComponente);
        $componente->fid_formulario = $idFormulario;
        $this->db->insert('componente', $componente);
        $idComponente = $this->db->insert_id();
        if (isset($opciones) && is_array($opciones)) {
          // $valoresInstancias = $this->db->query("SELECT *
          // FROM instancia i
          // INNER JOIN formulario f
          // INNER JOIN valor v ON v.fid_instancia = i.id
          // WHERE f.id = $idFormulario;")->result();
          foreach ($opciones as $opcion) {
            // foreach ($valoresInstancias as $valorInstancia) {
            //   if ($valorInstancia->nombre_componente == $componente->name) {
            //     if ($componente->type == 'checkbox-group') {
            //       if ($opcion->value == $valorInstancia->label) {
            //         $this->db->where('id', $valorInstancia->id);
            //         $this->db->update('valor', array('label' => $opcion->label));
            //       } 
            //     } else {
            //       if ($opcion->label == $valorInstancia->label) {
            //         $this->db->where('id', $valorInstancia->id);
            //         $this->db->update('valor', array('value' => $opcion->value));
            //       } else {
            //         if ($opcion->value == $valorInstancia->value) {
            //           $this->db->where('id', $valorInstancia->id);
            //           $this->db->update('valor', array('label' => $opcion->label));
            //         }
            //       }
            //     }
            //   }
            // }
            unset($opcion->id);
            $opcion->fid_componente = $idComponente;
            $this->db->insert('opciones', $opcion);
          }
        }
        $opciones = null;
      }
      $this->db->trans_commit();
    } catch (Exception $e) {
      js_error_div_javascript("<span style='font-size:3.5mm;'>".$e->getMessage()."</span>");
      exit();
    } 
  }

  public function borrarFormulario ($idFormulario) {
    try {
      $instancias = $this->db->query("SELECT * FROM instancia WHERE fid_formulario = $idFormulario")->result();
      foreach ($instancias as $instancia) {
        $this->db->where('fid_instancia', $instancia->id);
        $this->db->delete('valor');
        $this->db->where('id', $instancia->id);
        $this->db->delete('instancia');
      }
      $componentesValores = $this->db->query("SELECT * FROM componente WHERE fid_formulario = $idFormulario")->result();
      foreach ($componentesValores as $comp) {
        $this->db->where('fid_componente', $comp->id);
        $this->db->delete('opciones');
        $this->db->where('id', $comp->id);
        $this->db->delete('componente');
      }
      $this->db->where('id', $idFormulario);
      $this->db->delete('formulario');
    } catch (Exception $e) {
      js_error_div_javascript("<span style='font-size:3.5mm;'>".$e->getMessage()."</span>");
      exit();
    }
  }

  public function cambiarPublicado ($idFormulario, $formulario) {
    try {
      $this->db->where('id', $idFormulario);
      $this->db->update('formulario', $formulario);
    } catch (Exception $e) {
      js_error_div_javascript("<span style='font-size:3.5mm;'>".$e->getMessage()."</span>");
      exit();
    }
  }

  /* PARA LAS INSTANCIAS DE LOS FLUJOS */
  public function listadoInstancia ($idFormulario) {
    try {
      $consulta = $this->db->query("SELECT * FROM instancia WHERE fid_formulario = $idFormulario")->result();
      return $consulta;
    } catch (Exception $e) {
      js_error_div_javascript("<span style='font-size:3.5mm;'>".$e->getMessage()."</span>");
      exit();
    } 
  }

  public function mostrarInstancia ($idInstancia) {
    try {
      $instancia = $this->db->query("SELECT * FROM instancia WHERE id = $idInstancia")->result();
      if (sizeof($instancia) === 1) {
        $instancia = $instancia[0];
      }
      return $instancia;
    } catch (Exception $e) {
      js_error_div_javascript("<span style='font-size:3.5mm;'>".$e->getMessage()."</span>");
      exit();
    } 
  }

  public function valoresInstancia ($idInstancia) {
    try {
      $valores = $this->db->query("SELECT * FROM valor WHERE fid_instancia = $idInstancia")->result();
      return $valores;
    } catch (Exception $e) {
      js_error_div_javascript("<span style='font-size:3.5mm;'>".$e->getMessage()."</span>");
      exit();
    } 
  }

  public function eliminarInstancia ($idInstancia) {
    try {
      $this->db->where('fid_instancia', $idInstancia);
      $this->db->delete('valor');
      $this->db->where('id', $idInstancia);
      $this->db->delete('instancia');
    } catch (Exception $e) {
      js_error_div_javascript("<span style='font-size:3.5mm;'>".$e->getMessage()."</span>");
      exit();
    } 
  }

  /* PARA LAS INSTANCIAS DE LOS FLUJOS */


  /* PARA LOS SERVICIOS */
  public function formularios_publicados ($tipo_bandeja) {
    try {
        $formulariosPublicados = $this->db->query("SELECT id AS form_id, nombre AS form_detalle
        FROM formulario 
        WHERE tipo_bandeja = $tipo_bandeja AND publicado = true;")->result();
        return $formulariosPublicados;
    } catch (Exception $e) {
      return false;
      js_error_div_javascript("<span style='font-size:3.5mm;'>".$e->getMessage()."</span>");
      exit();
    } 
  }

  // public function guardarInstancia ($formulario, $arrElementos) {
  //   try {
  //     $this->db->insert('instancia', array('fid_formulario' => $formulario->id));
  //     $instanciaId = $this->db->insert_id();
  //     foreach ($arrElementos as $elem) {
  //       $this->db->insert('instancia', array('fid_formulario' => $formulario->id));
  //     }
  //   } catch (Exception $e) {
  //     js_error_div_javascript("<span style='font-size:3.5mm;'>".$e->getMessage()."</span>");
  //     exit();
  //   } 
  // }
  /* PARA LOS SERVICIOS */

  public function getFormulario($tipo_bandeja, $formulario_id)
  {
      try {
        $consulta = $this->db->query(" SELECT *
        FROM formulario
        WHERE id = $formulario_id AND tipo_bandeja = $tipo_bandeja")->result();
        if (sizeof($consulta) == 1) {
          return $consulta[0];
        } else {
          return false;
        }
      } catch (Exception $e) {
        js_error_div_javascript("<span style='font-size:3.5mm;'>".$e->getMessage()."</span>");
        exit();
      }
      return $listaResultados;
  }

  // public function actualizar_valores ($idInstancia, $valores) {
  //   try {
  //     foreach ($valores as $valor) {
  //       $val = new stdClass();
  //       if (is_object($valor->value)) {
  //         $i = 0;
  //         $this->db->where('fid_instancia', $idInstancia);
  //         $this->db->where('nombre_componente', $valor->name);
  //         $this->db->delete('valor');
  //         foreach ($valor->value as $key => $value) {
  //           $this->db->where('fid_instancia', $idInstancia);
  //           $this->db->where('nombre_componente', $valor->name);
  //           $consulta = $this->db->query("SELECT o.* 
  //             from formulario f
  //             inner join instancia i on i.fid_formulario = f.id
  //             inner join componente c on c.fid_formulario = f.id
  //             inner join opciones o on o.fid_componente = c.id
  //             where i.id = $idInstancia AND c.name = '$valor->name' AND value = '$key'")->result();
  //           $val->label = $consulta[0]->label;
  //           $val->fid_instancia = $idInstancia;
  //           $val->nombre_componente = $valor->name;
  //           $val->value = $value;
  //           $this->db->insert('valor', $val);
  //         }
  //       } else {
  //         $this->db->where('fid_instancia', $idInstancia);
  //         $this->db->where('nombre_componente', $valor->name);
  //         $val->value = $valor->value;
  //         $val->label = isset($valor->label) ? $valor->label : '';
  //         $this->db->update('valor', $val);
  //       }
  //     }
  //   } catch (Exception $e) {
  //     js_error_div_javascript("<span style='font-size:3.5mm;'>".$e->getMessage()."</span>");
  //     exit();
  //   }
  // }

  public function guadarValoresInstancia($codigo_registro, $tipo_bandeja, $formulario_id, $arrElementos)
  {
    try {
        if (!is_array($arrElementos)) {
          throw new Exception('No envio la lista de elementos a guardar en el formato correcto.');
        }
        $arrElementos = json_decode(json_encode($arrElementos), true);
        $this->db->trans_begin();
        $existeForm = $this->db->query(" SELECT *
        FROM formulario
        WHERE id = $formulario_id")->result();
        if (sizeof($existeForm) != 1) {
          $this->db->trans_rollback();
          throw new Exception('El Formulario no existe');
        }
        if ($existeForm[0]->publicado != 1) {
          $this->db->trans_rollback();
          throw new Exception('El Formulario no se encuentra publicado.');
        }
        $existeInstancia = $this->db->query("SELECT * FROM instancia WHERE fid_formulario = $formulario_id AND codigo_registro = $codigo_registro")->result();
        if (isset($existeInstancia) && sizeof($existeInstancia) == 1) {
          $instanciaId = $existeInstancia[0]->id;
        } else {
          $this->db->insert('instancia', array('fid_formulario' => $formulario_id, 'codigo_registro' => $codigo_registro));
          $instanciaId = $this->db->insert_id();
        }
        $arrElementos = json_decode(json_encode($arrElementos), true);
        foreach ($arrElementos as $componente) {
          if (isset($componente['value']) && isset($componente['name'])) {
            $existeComponente = $this->db->query("SELECT * FROM componente WHERE fid_formulario = $formulario_id AND name = '".$componente['name']."';")->result();
            if (sizeof($existeComponente) != 1) {
              $this->db->trans_rollback();
              throw new Exception('El nombre del componente '.$componente['name'].' no es valido');
            }
            $validacion = $this->validarCampos($existeComponente[0], $componente['value'], true);
            if (strlen($validacion) > 0) {
              $this->db->trans_rollback();
              throw new Exception($validacion);
            }
            $this->db->where('fid_instancia', $instanciaId);
            $this->db->where('nombre_componente', $existeComponente[0]->name);
            $this->db->delete('valor');
            switch ($componente['type']) {
              case 'checkbox-group':
                $opciones = $this->db->query("SELECT * FROM opciones WHERE fid_componente = ".$existeComponente[0]->id.";")->result();
                if (!is_array($componente['value'])) {
                  $componente['value'] = explode(',', $componente['value']);
                }
                foreach ($componente['value'] as $value) {
                  $label = 'SIN_LABEL';
                  foreach ($opciones as $opcion) {
                    if ($opcion->value == $value) {
                      $label = $opcion->label;
                    }
                  }
                  $this->db->insert('valor', array(
                    'fid_instancia' => $instanciaId,
                    'nombre_componente' => $existeComponente[0]->name,
                    'label' => $label,
                    'value' => true
                  ));
                }
                break;
              case 'select':
                $opciones = $this->db->query("SELECT * FROM opciones WHERE fid_componente = ".$existeComponente[0]->id.";")->result();
                $label = 'SIN_LABEL';
                foreach ($opciones as $opcion) {
                  if ($opcion->value == $componente['value']) {
                    $label = $opcion->label;
                  }
                }
                if ($existeComponente[0]->parent_codigo && $existeComponente[0]->parent_tipo && $existeComponente[0]->catalogo) {
                  $catalogo = $this->mfunciones_logica->ObtenerCatalogo($existeComponente[0]->catalogo, $existeComponente[0]->parent_codigo, $existeComponente[0]->parent_tipo);
                  foreach ($catalogo as $elemento) {
                    if ($elemento['catalogo_codigo'] == $componente['value']) {
                      $label = $elemento['catalogo_descripcion'];
                    }
                  }
                }
                $this->db->insert('valor', array(
                  'fid_instancia' => $instanciaId,
                  'nombre_componente' => $existeComponente[0]->name,
                  'label' => $label,
                  'value' => $componente['value']
                ));
                break;
              case 'radio-group':
                $opciones = $this->db->query("SELECT * FROM opciones WHERE fid_componente = ".$existeComponente[0]->id.";")->result();
                $label = 'SIN_LABEL';
                foreach ($opciones as $opcion) {
                  if ($opcion->value == $componente['value']) {
                    $label = $opcion->label;
                  }
                }
                $this->db->insert('valor', array(
                  'fid_instancia' => $instanciaId,
                  'nombre_componente' => $existeComponente[0]->name,
                  'label' => $label,
                  'value' => $componente['value']
                ));
                break;
              default:
                $this->db->insert('valor', array(
                  'fid_instancia' => $instanciaId,
                  'nombre_componente' => $existeComponente[0]->name,
                  'value' => $componente['value']
                ));
                break;
            }
          }
        }
        $this->db->trans_commit();
      } catch (Exception $e) {
        $this->db->trans_rollback();
        $error = new stdClass();
        $error->error = true;
        $error->mensaje = $e->getMessage();
        return $error;
        exit();
      }
  }

  /* PARA OBTENER VALUES DE UN COMPONENTE COMO STRING */
  
  /* PARA OBTENER VALUES DE UN COMPONENTE COMO STRING */


  /* FUNCIONES GENERALES */

  public function actualizarInstancia ($formulario_id, $instanciaId, $arrElementos) {
    try {
      $arrElementos = json_decode(json_encode($arrElementos), true);
      $this->db->trans_begin();
      foreach ($arrElementos as $componente) {
        if (isset($componente['value']) && isset($componente['name'])) {
          $existeComponente = $this->db->query("SELECT * FROM componente WHERE fid_formulario = $formulario_id AND name = '".$componente['name']."';")->result();
          if (sizeof($existeComponente) != 1) {
            $this->db->trans_rollback();
            throw new Exception('El nombre del componente '.$componente['name'].' no es valido');
          }
          $validacion = $this->validarCampos($existeComponente[0], $componente['value']);
          if (strlen($validacion) > 0) {
            throw new Exception($validacion);
          }
          $this->db->where('fid_instancia', $instanciaId);
          $this->db->where('nombre_componente', $existeComponente[0]->name);
          $this->db->delete('valor');
          switch ($componente['type']) {
            case 'checkbox-group':
              foreach ($componente['value'] as $value) {
                $this->db->insert('valor', array(
                  'fid_instancia' => $instanciaId,
                  'nombre_componente' => $existeComponente[0]->name,
                  'label' => $value['label'],
                  'value' => true
                ));
              }
              break;
            case 'select':
              foreach ($componente['value'] as $value) {
                $actualizar = array(
                  'fid_instancia' => $instanciaId,
                  'nombre_componente' => $existeComponente[0]->name,
                  'value' => $value['value']
                );
                if (isset($value['label'])) {
                  $actualizar['label'] = $value['label'];
                }
                $this->db->insert('valor', $actualizar);
              }
              break;
            case 'radio-group':
              foreach ($componente['value'] as $value) {
                $this->db->insert('valor', array(
                  'fid_instancia' => $instanciaId,
                  'nombre_componente' => $existeComponente[0]->name,
                  'label' => $value['label'],
                  'value' => $value['value']
                ));
              }
              break;
            default:
              $this->db->insert('valor', array(
                'fid_instancia' => $instanciaId,
                'nombre_componente' => $existeComponente[0]->name,
                'value' => $componente['value']
              ));
              break;
          }
        }
      }
      $this->db->trans_commit();
    } catch (Exception $e) {
      js_error_div_javascript("<span style='font-size:3.5mm;'>".$e->getMessage()."</span>");
      exit();
    }
  }

  public function crearInstancia ($formulario_id, $codigo_registro, $arrElementos) {
    try {
      $arrElementos = json_decode(json_encode($arrElementos), true);
      $this->db->trans_begin();
      $existeForm = $this->db->query(" SELECT * FROM formulario WHERE id = $formulario_id")->result();
      if (sizeof($existeForm) != 1) {
        $this->db->trans_rollback();
        throw new Exception('El Formulario no existe');
      }
      $this->db->insert('instancia', array('fid_formulario' => $formulario_id, 'codigo_registro' => $codigo_registro));
      $instanciaId = $this->db->insert_id();
      foreach ($arrElementos as $componente) {
        $existeComponente = $this->db->query("SELECT * FROM componente WHERE fid_formulario = $formulario_id AND name = '".$componente['name']."';")->result();
        if (sizeof($existeComponente) != 1) {
          $this->db->trans_rollback();
          throw new Exception('El nombre del componente '.$componente['name'].' no es valido');
        }
        $validacion = $this->validarCampos($existeComponente[0], $componente['value']);
        if (strlen($validacion) > 0) {
          throw new Exception($validacion);
        }
        switch ($componente['type']) {
          case 'checkbox-group':
            foreach ($componente['value'] as $value) {
              $this->db->insert('valor', array(
                'fid_instancia' => $instanciaId,
                'nombre_componente' => $existeComponente[0]->name,
                'label' => $value['label'],
                'value' => true
              ));
            }
            break;
          case 'select':
            foreach ($componente['value'] as $value) {
              $this->db->insert('valor', array(
                'fid_instancia' => $instanciaId,
                'nombre_componente' => $existeComponente[0]->name,
                'label' => $value['label'],
                'value' => $value['value']
              ));
            }
            break;
          case 'radio-group':
            foreach ($componente['value'] as $value) {
              $this->db->insert('valor', array(
                'fid_instancia' => $instanciaId,
                'nombre_componente' => $existeComponente[0]->name,
                'label' => $value['label'],
                'value' => $value['value']
              ));
            }
            break;
          default:
            $this->db->insert('valor', array(
              'fid_instancia' => $instanciaId,
              'nombre_componente' => $existeComponente[0]->name,
              'value' => $componente['value']
            ));
            break;
        }
      }
      $this->db->trans_commit();
    } catch (Exception $e) {
      js_error_div_javascript("<span style='font-size:3.5mm;'>".$e->getMessage()."</span>");
      exit();
    }
  }

  public function validarCampos($componente, $valor, $servicio = false) {
    $errores = '';
    if ($servicio) {
      switch ($componente->type) {
        case 'select':
          if (is_array($valor) || is_object($valor)) {
            $errores = "El valor del campo ".$componente->name." debe ser de tipo string.";
          }
          break;
        case 'radio-group':
          if (is_array($valor) || is_object($valor)) {
            $errores = "El valor del campo ".$componente->name." debe ser de tipo string.";
          }
          break;
        case 'checkbox-group':
          if (!is_array($valor)) {
            if ($servicio) {
              $valor = explode(',', $valor);
              foreach ($valor as $val) {
                if (is_array($val) || is_object($val)) {
                  $errores = "El valor de los elemnetos del componente ".$componente->name." debe ser de tipo string.";
                }
              }
            } else {
              $errores = "El valor del campo ".$componente->name." debe ser de tipo array.";
            }
          } else {
            foreach ($valor as $val) {
              if (is_array($val) || is_object($val)) {
                $errores = "El valor de los elemnetos del componente ".$componente->name." debe ser de tipo string.";
              }
            }
          }
          break;
        default:
          break;
      }
    }
    $opciones = $this->db->query('SELECT * FROM opciones WHERE fid_componente = '.$componente->id)->result();
    if ($valor != '') {
      if (isset($componente->type) && $componente->type == 'number') {
        if (!is_numeric($valor)) {
          $errores = "El valor del campo ".$componente->name." debe ser un numero";
        }
      }
      if (isset($componente->subtype) && $componente->subtype == 'email') {
        if (!preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $valor)) {
          $errores = "El valor del campo ".$componente->name." debe ser un correo valido.";
        }
      }
      if (isset($componente->subtype) && $componente->subtype == 'date') {
        if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(Z|(\+|-)\d{2}(:?\d{2})?)$/', $valor) && !preg_match('/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/', $valor)) {
          $errores = "El valor del campo ".$componente->name." debe tener el formato de fecha.";
        }
      }
      if (isset($componente->subtype) && $componente->subtype == 'time') {
        if (!preg_match('/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/', $valor)) {
          $errores = "El valor del campo ".$componente->name." debe tener el formato de tiempo.";
        }
      }
    }
    if (isset($componente->required) && $componente->required) {
      if (is_array($valor)) {
        if (sizeof($valor) == 0) {
          $errores = "El valor del campo ".$componente->name." es requerido.";
        }
        foreach ($valor as $parteValor) {
          if ($componente->parent_codigo && $componente->parent_tipo && $componente->catalogo) {
            $catalogo = $this->mfunciones_logica->ObtenerCatalogo($componente->catalogo, $componente->parent_codigo, $componente->parent_tipo);
            $valorValido = false;
            foreach ($catalogo as $opcion) {
              if ($opcion['catalogo_codigo'] == $parteValor['value']) {
                $valorValido = true;
              }
            }
            if (!$valorValido) {
              $errores = "El valor '".$parteValor['value']."' para el componente ".$componente->name." no es valido.";
            }
          } else {
            if (sizeof($opciones) > 0) {
              $valorValido = false;
              foreach ($opciones as $opcion) {
                if ($opcion->value == $parteValor['value'] || $opcion->label == $parteValor['value']) {
                  $valorValido = true;
                }
              }
              if (!$valorValido) {
                $errores = "El valor '".$parteValor['value']."' para el componente ".$componente->name." no es valido.";
              }
            }
          }
        }
      } else {
        if (strlen($valor) == 0) {
          $errores = "El valor del campo ".$componente->name." es requerido.";
        }
      }
      if (isset($componente->min) && $componente->min) {
        if ($valor < $componente->min ) {
          $errores = "El valor del campo ".$componente->name." no debe ser menor a ".$componente->min;
        }
      }
      if (isset($componente->max) && $componente->max) {
        if ($valor > $componente->max ) {
          $errores = "El valor del campo ".$componente->name." no debe ser mayor a ".$componente->max;
        }
      }
    }
    return $errores;
  }


  // public function getComponentes ($id_formulario, $tipo_bandeja) {
  //   $formulario = $this->db->query("SELECT * FROM formulario WHERE id = $id_formulario AND tipo_bandeja = $tipo_bandeja;")->result();     
  //   if (sizeof($formulario) != 1) {
  //     throw new Exception('Error al consultar el formulario');
  //   }
  //   $formulario = $formulario[0];
  //   return  $this->db->query("SELECT * FROM componente WHERE fid_formulario = $formulario->id;")->result();    
  // }

  public function getLabelOptions ($componente, $value) {
    $labelFinal = '';
    $opciones = $this->db->query("SELECT * FROM opciones WHERE fid_componente = $componente->id;")->result();
    foreach ($opciones as $opcion) {
      if ($value == $opcion->value) {
        $labelFinal = $opcion->label;
      }
    }
    if ($componente->parent_codigo && $componente->parent_tipo && $componente->catalogo) {
      $catalogo = $this->mfunciones_logica->ObtenerCatalogo($componente->catalogo, $componente->parent_codigo, $componente->parent_tipo);
      foreach ($catalogo as $elemento) {
        if ($value == $value['catalogo_codigo']) {
          $labelFinal = $value['catalogo_descripcion'];
        }
      }
    }
    return $labelFinal;
  }

  public function estaEnOpciones ($componente, $tipo, $value) {
    $existe = false;
    foreach ($componente->values as $opcion) {
      switch ($tipo) {
        case 'checkbox-group':
          if ($opcion->label == $value->label) {
            $existe = true;
          }
          break;
        case 'select':
          if (is_array($opcion)) {
            if ($opcion['label'] == $value->label && $opcion['value'] == $value->value) {
              $existe = true;
            }  
          } else {
            if ($opcion->label == $value->label && $opcion->value == $value->value) {
              $existe = true;
            }
          } 
          break;
        case 'radio-group':
          if ($opcion->label == $value->label) {
            $existe = true;
          }
          break;
        default:
          break;
      }
    }
    return $existe;
  }

  public function getValue ($componente, $idInstancia, $string = false, $value = true) {
    $values = array();
    $tipo = $componente->type;
    $subtype = $componente->subtype;
    $valores = $this->db->query("SELECT * FROM valor WHERE  fid_instancia = $idInstancia AND nombre_componente = '$componente->name';")->result();
    foreach ($valores as $valor) {
      switch ($tipo) {
        case 'checkbox-group':
          if ($this->estaEnOpciones($componente, $tipo, $valor)) {
            $val = new stdClass();
            $val->label = $valor->label;
            $val->value = $valor->value;
            $values[] = $val;
          }
          break;
        case 'select':
          if ($this->estaEnOpciones($componente, $tipo, $valor)) {
            $val = new stdClass();
            $val->label = $valor->label;
            $val->value = $valor->value;
            $values[] = $val;
          }
          break;
        case 'radio-group':
          if ($this->estaEnOpciones($componente, $tipo, $valor)) {
            $val = new stdClass();
            $val->label = $valor->label;
            $val->value = $valor->value;
            $values[] = $val;
          }
          break;
        default:
          if ($value) {
            array_push($values, $valor->value);
          } else {
            if (strlen($valor->label) > 0) {
              array_push($values, $valor->label);
            } else {
              array_push($values, $valor->value);
            }
          }
          break;
      }
    }
    if ($string) {
      switch ($tipo) {
        case 'checkbox-group':
            $result = array();
            foreach ($values as $val) {
              $result[] = $val->label;
            }
            $values = implode(",", $result);
          break;
        case 'select':
            $result = array();
            foreach ($values as $val) {
              $result[] = $val->label;
            }
            $values = implode(",", $result);
          break;
        case 'radio-group':
          $result = array();
            foreach ($values as $val) {
              $result[] = $val->label;
            }
            $values = implode(",", $result);
          break;
        default:
            $values = implode(",", $values);
          break;
      }
    } else {
      if (sizeof($values) == 1) {
        // if (!is_object($values[0])) {
          $values = $values[0];
        // }
      }
    }
    return $values;
  }
  /* FUNCIONES GENERALES */
}