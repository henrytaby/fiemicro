<?php

class Mfunciones_cobranzas extends CI_Model {

/*************** FUNCTIONS - INICIO ****************************/
    
    // ******** FILTROS REPORTE ********
    
    public function Obtener_Campos_Filtro_Norm($tipo_bandeja='') {
        
        switch ($tipo_bandeja) {
            case 'supervision':

                $text_aux1 = ' (Última registrada)';
                $text_aux2 = '';
                
                break;

            default:
                
                $text_aux1 = '';
                $text_aux2 = ' (Caso registrado)';
                
                break;
        }
        
        $arrayFiltros = array(
            
            //ID
            (object) array("campo"=>"norm_id","titulo"=>$this->lang->line('norm_id') . $text_aux2,"tipo"=>"id"),
            
            // Listas
            (object) array("campo"=>"norm_rel_cred","titulo"=>$this->lang->line('norm_rel_cred') . $text_aux2,"tipo"=>"lista"),
            (object) array("campo"=>"norm_finalizacion","titulo"=>$this->lang->line('norm_finalizacion') . $text_aux2,"tipo"=>"lista"),
            
            // Fechas
            (object) array("campo"=>"cv_fecha","titulo"=>$this->lang->line('cv_fecha') . $text_aux1,"tipo"=>"fecha"),
            (object) array("campo"=>"cv_checkin_fecha","titulo"=>$this->lang->line('cv_checkin_fecha') . $text_aux1,"tipo"=>"fecha"),
            (object) array("campo"=>"cv_fecha_compromiso","titulo"=>$this->lang->line('cv_fecha_compromiso') . $text_aux1,"tipo"=>"fecha_simple"),
            
            // Listas
            (object) array("campo"=>"cv_resultado","titulo"=>$this->lang->line('cv_resultado') . $text_aux1,"tipo"=>"lista"),
        );
        
        return $arrayFiltros;
    }
    
    public function Obtener_Valores_Filtro_Norm($campo) {

        switch ($campo) {
            
            case "norm_rel_cred":
                return array(
                    (object) array("id"=>"1","descripcion"=>$this->GetValorCatalogo(1, 'norm_rel_cred')),
                    (object) array("id"=>"2","descripcion"=>$this->GetValorCatalogo(2, 'norm_rel_cred')),
                    (object) array("id"=>"3","descripcion"=>$this->GetValorCatalogo(3, 'norm_rel_cred')),
                    (object) array("id"=>"99","descripcion"=>$this->GetValorCatalogo(99, 'norm_rel_cred')),
                );
            
            case "norm_finalizacion":
                return array(
                    (object) array("id"=>"1","descripcion"=>$this->GetValorCatalogo(1, 'norm_finalizacion')),
                    (object) array("id"=>"2","descripcion"=>$this->GetValorCatalogo(2, 'norm_finalizacion')),
                    (object) array("id"=>"3","descripcion"=>$this->GetValorCatalogo(3, 'norm_finalizacion')),
                    (object) array("id"=>"4","descripcion"=>$this->GetValorCatalogo(4, 'norm_finalizacion')),
                );
            
            case "cv_resultado":
                return $this->Obtener_Lista_Catalogo("cobranzas_resultado_visita", "catalogo_codigo");
                
        }
        return array();
    }
    
    private function Obtener_Lista_Catalogo($tipo, $orden='catalogo_descripcion') {
        try {
            $consulta = $this->db->query("select catalogo_codigo as id, catalogo_descripcion as descripcion from catalogo where catalogo_tipo_codigo = '$tipo' AND catalogo_estado=1 order by $orden");
            $filas_consultadas = $consulta->result();
            return $filas_consultadas;
        } catch (Exception $ex) {
            return array();
        }
    }
    
    public function Obtener_Tabla_Campo_Filtro($campo) {
        switch ($campo) {
            case "norm_id": return (object) array("tabla"=>"tabla_auxiliar","campo"=>"norm_id"); break;
            case "norm_rel_cred": return (object) array("tabla"=>"tabla_auxiliar","campo"=>"norm_rel_cred"); break;
            case "norm_finalizacion": return (object) array("tabla"=>"tabla_auxiliar","campo"=>"norm_finalizacion"); break;
            case "cv_checkin_fecha": return (object) array("tabla"=>"tabla_auxiliar","campo"=>"cv_checkin_fecha"); break;
            case "cv_fecha": return (object) array("tabla"=>"tabla_auxiliar","campo"=>"cv_fecha"); break;
            case "cv_fecha_compromiso": return (object) array("tabla"=>"tabla_auxiliar","campo"=>"cv_fecha_compromiso"); break;
            case "cv_resultado": return (object) array("tabla"=>"tabla_auxiliar","campo"=>"cv_resultado"); break;
        }
        return null;
    }
    
    // ******** FILTROS REPORTE ********
    
    function ArmarFiltro_Norm_tracking($sel_departamento, $sel_agencia, $sel_oficial, $campoFiltroFechaDesde, $campoFiltroFechaHasta, $estado, $consolidado)
    {
        $estado = (int)$estado;
        $consolidado = (int)$consolidado;
        
        $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);

        $filtro = ' AND agencia_id IN(' . $lista_region->region_id . ')';
        
        // Departamento
        if (!empty($sel_departamento))
        {
            $flag_elalto = 0;
            
            foreach ($sel_departamento as $key => $value) 
            {
                switch ((int)$value) {

                    case 115:
                        $flag_elalto = 1;
                        $aux_criterio = "";
                        break;

                    default:
                        $aux_criterio = "'" . $value . "'";
                        break;
                }

                $value = $aux_criterio;

                if($aux_criterio != '')
                {
                    $criterio .= $value . ',';
                }
            }
            
            $criterio = rtrim($criterio, ',');
            
            if($criterio != '')
            {
                $filtro .= " AND (agencia_departamento IN($criterio)";
				
                if($flag_elalto == 1)
                {
                    $filtro .= " OR agencia_ciudad IN('115'))";
                }
                else
                {
                    $filtro .= " AND agencia_ciudad<>115) ";
                }
            }
            else
            {
                $filtro .= " AND agencia_ciudad IN('115')";
            }
        }
        
        // Agencias
        if(!empty($sel_agencia))
        {
            $filtro .= " AND agencia_id IN(" . implode(', ', $sel_agencia) . ")";
        }
        
        // Normalizadores/Cobradores
        if(!empty($sel_oficial))
        {
            $filtro .= " AND ejecutivo_id IN(" . implode(', ', $sel_oficial) . ")";
        }
        
        // Estado
        if($estado != 2)
        {
            $filtro .= " AND agencia_estado=$estado AND usuario_activo=$estado";
        }
        
        // Consolidado
        if($consolidado != 99)
        {
            $filtro .= " AND registro_consolidado=$consolidado";
        }
        
        if($this->mfunciones_generales->VerificaFechaD_M_Y($campoFiltroFechaDesde))
        {
            $filtro .= " AND fecha_registro >= '" . $this->mfunciones_generales->getFormatoFechaDateTime($campoFiltroFechaDesde . ' 00:00:01') . "'";
        }
        
        if($this->mfunciones_generales->VerificaFechaD_M_Y($campoFiltroFechaHasta))
        {
            $filtro .= " AND fecha_registro <= '" . $this->mfunciones_generales->getFormatoFechaDateTime($campoFiltroFechaHasta . ' 23:59:59') . "'";
        }
        
        $filtro = str_replace('&', '', $filtro);
        
        return $filtro;
    }
    
    private function ArmarValores_Norm_Filtro($sel_departamento, $sel_agencia, $sel_oficial, $campoFiltroFechaDesde, $campoFiltroFechaHasta, $estado, $consolidado)
    {
        $this->load->model("mfunciones_dashboard");
        
        $nombres_filtro = '';
        
        $separador = '<b> ó </b> ';
        
        // Estado
        $nombres_filtro .= '<b>*Estado Agencia/Normalizador: </b> ';

        switch ((int)$estado) {
            case 0:

                $nombres_filtro .= $separador . 'Cerrada/Inactivo';

                break;

            case 1:

                $nombres_filtro .= $separador . 'Abierta/Activo';

                break;

            case 2:

                $nombres_filtro .= $separador . 'Todos';

                break;

            default:
                break;
        }

        $nombres_filtro .= '<br />';
        
        // Consolidado
        $nombres_filtro .= '<b>*Estado Registro: </b> ';

        switch ((int)$consolidado) {
            case 0:

                $nombres_filtro .= $separador . 'No Consolidado';

                break;

            case 1:

                $nombres_filtro .= $separador . 'Consolidado';

                break;

            case 99:

                $nombres_filtro .= $separador . 'Todos';

                break;

            default:
                break;
        }

        $nombres_filtro .= '<br />';
        
        // Departamento
        if (!empty($sel_departamento))
        {
            $nombres_filtro .= '<b>*Departamento: </b> ';
            
            foreach ($sel_departamento as $key => $value) 
            {
                switch ((int)$value) {

                    case 115:
                        $nombres_filtro .= $separador . 'LA PAZ - EL ALTO';
                        break;

                    default:
                        $nombres_filtro .= $separador . $this->mfunciones_generales->GetValorCatalogoDB($value, 'dir_departamento');
                        break;
                }
            }
            
            $nombres_filtro .= '<br />';
        }
        
        $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        
        // Agencias
        if(!empty($sel_agencia))
        {
            $filtro_agencia = ' AND estructura_regional_id IN(' . $lista_region->region_id . ')';
            
            $nombres_filtro .= '<b>*Agencia: </b> ';
            
            $filtro_agencia .= " AND estructura_regional_id IN(" . implode(', ', $sel_agencia) . ")";
            
            $arrAgencia = $this->mfunciones_dashboard->ObtenerListaAgencia_generico($filtro_agencia);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrAgencia);
            
            if (isset($arrAgencia[0])) 
            {
                foreach ($arrAgencia as $key => $value) 
                {
                    $nombres_filtro .= $separador . $value["estructura_regional_nombre"] . ((int)$value["estructura_regional_estado"]==1 ? '' : ' (Cerrada)');
                }
            }
            
            $nombres_filtro .= '<br />';
        }
        
        // Oficiales de Negocios
        if(!empty($sel_oficial))
        {   
            $nombres_filtro .= '<b>*' . $this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app_singular') . ': </b> ';
            
            $filtro_oficial .= ' AND er.estructura_regional_id IN(' . $lista_region->region_id . ')';
            
            $filtro_oficial .= " AND e.ejecutivo_id IN(" . implode(', ', $sel_oficial) . ")";
            
            $arrOficial = $this->ObtenerListaNorm_generico($filtro_oficial);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrOficial);
            
            if (isset($arrOficial[0])) 
            {
                foreach ($arrOficial as $key => $value) 
                {
                    $nombres_filtro .= $separador . $value["ejecutivo_nombre"] . ((int)$value["usuario_activo"]==1 ? '' : ' (Inactivo)');
                }
            }
            
            $nombres_filtro .= '<br />';
        }
        
        if($campoFiltroFechaDesde != '' || $campoFiltroFechaHasta != '')
        {
            $nombres_filtro .= '<b>*Fecha de Registro del Caso: </b> ';
            
            if($this->mfunciones_generales->VerificaFechaD_M_Y($campoFiltroFechaDesde))
            {
                $nombres_filtro .= ' <i>Desde el:</i> ' . $campoFiltroFechaDesde;
            }

            if($this->mfunciones_generales->VerificaFechaD_M_Y($campoFiltroFechaHasta))
            {
                $nombres_filtro .= ' <i>Hasta el:</i> ' . $campoFiltroFechaHasta;
            }

            $nombres_filtro .= '<br />';
        }
        
        $nombres_filtro = str_replace(': </b> ' . $separador, ': </b> ', $nombres_filtro);
        
        return $nombres_filtro;
    }
    
    function Generar_Reporte_Norm($parametros) {
        // Se crea el objeto de resultado a devolver
        $resultado = new stdClass();
        $resultado->sin_registros = true;
        $resultado->error = false;
        $resultado->filas = array();
        $resultado->mensaje_error = "";
        $filtros = array();
        
        if (is_null($parametros) || !is_object($parametros)) return $resultado;

        // Se captura los valores
        
        $tipo_bandeja = $parametros->tipo_bandeja;
        
        $sel_departamento = $parametros->sel_departamento;
        $sel_agencia = $parametros->sel_agencia;
        $sel_oficial = $parametros->sel_normalizador;
        $campoFiltroFechaDesde = $parametros->campoFiltroFechaDesde;
        $campoFiltroFechaHasta = $parametros->campoFiltroFechaHasta;

        $estado = preg_replace('/[^a-zA-Z0-9_ .]/s', '', $parametros->estado);

        $consolidado = (int)$parametros->consolidado;
        $formato_reporte = (int)$parametros->formato_reporte;

        // Validaciones
        
        if($tipo_bandeja != 'supervision' && $campoFiltroFechaHasta == 'tracking')
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        if($tipo_bandeja == 'tracking')
        {
            if($campoFiltroFechaDesde == '' || $campoFiltroFechaHasta == '')
            {
                js_error_div_javascript($this->lang->line('norm_reporte_fecha_error'));
                exit();
            }
            
            if($formato_reporte<1 || $formato_reporte>4)
            {
                js_error_div_javascript($this->lang->line('FormularioIncompleto'));
                exit();
            }
            
            if(empty($sel_agencia) && empty($sel_oficial))
            {
                js_error_div_javascript(sprintf($this->lang->line('norm_reporte_filtro_error'), $this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app_singular')));
                exit();
            }
        }
        
        if($estado<0 || $estado>2)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        if($consolidado != 99 && $consolidado != 0 && $consolidado != 1)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        $filtro_armado = $this->ArmarFiltro_Norm_tracking($sel_departamento, $sel_agencia, $sel_oficial, $campoFiltroFechaDesde, $campoFiltroFechaHasta, $estado, $consolidado);
        
        $valoresFiltro = $this->ArmarValores_Norm_Filtro($sel_departamento, $sel_agencia, $sel_oficial, $campoFiltroFechaDesde, $campoFiltroFechaHasta, $estado, $consolidado);
        
        
        // Parametros de la consulta
        foreach ($parametros->filtros as $f) {
            $datos_filtro = $this->Obtener_Tabla_Campo_Filtro($f->campo);
            if ($datos_filtro == null) continue;
            $f->tabla = $datos_filtro->tabla;
            $f->campo_sql = $datos_filtro->campo;
            $filtros[] = $f;
        }
        
        // Se construye la clausula where según los filtros (JRAD CONSULTA)
        $where = array("");
        
        foreach ($filtros as $filtro) {
            
            switch ($filtro->tipo) {
                case "texto":
                    $where[] = (strpos($filtro->campo_sql, "concat_ws") === false)?"(".$filtro->campo_sql." like '%".$this->db->escape_str($filtro->valor)."%')"
                        :"(".$filtro->campo_sql." like '%".$this->db->escape_str($filtro->valor)."%')";
                    break;
                case "id":
                    $where[] = "(".$filtro->campo_sql." = ".$this->db->escape($filtro->valor).")";
                    break;
                case "booleano":
                case "lista":
                    $where[] = "(".$filtro->campo_sql." in (".implode(",",array_map(function ($i) {return $this->db->escape($i);},$filtro->valores))."))";
                    break;
                case "fecha":
                    if (!empty($filtro->desde)) {
                        $fecha_desde = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->desde . ' 00:00:01');
                        $where[] = "(" . $filtro->campo_sql . " >= '$fecha_desde')";
                    }
                    if (!empty($filtro->hasta)) {
                        $fecha_hasta = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->hasta . ' 23:59:59');
                        $where[] = "(" . $filtro->campo_sql . " <= '$fecha_hasta')";
                    }
                    break;
                case "fecha_simple":
                    if (!empty($filtro->desde)) {
                        $fecha_desde = $this->mfunciones_generales->getFormatoFechaDate($filtro->desde);
                        $where[] = "(" . $filtro->campo_sql . " >= '$fecha_desde')";
                    }
                    if (!empty($filtro->hasta)) {
                        $fecha_hasta = $this->mfunciones_generales->getFormatoFechaDate($filtro->hasta);
                        $where[] = "(" . $filtro->campo_sql . " <= '$fecha_hasta')";
                    }
                    break;
            }
        }
        
        $sql_where = join(" AND ", $where);
        
        // Si se seleccionó otros filtros, concatenar con el criterio ya armado
        if($sql_where != '')
        {
            $filtro_armado .= $sql_where;
            
            foreach ($parametros->filtros as $key_filtro => $value_filtro) 
            {
                $valoresFiltro .= '<b>*' . $value_filtro->titulo . '</b>: ' . $value_filtro->descripcion . '<br />';
            }
        }
        
        switch ($tipo_bandeja) {
            
            case 'supervision':

                $arrResultado = $this->ObtenerReporte_Norm($filtro_armado);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if(isset($arrResultado[0]))
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "norm_id" => $value["norm_id"],
                            "ejecutivo_id" => $value["ejecutivo_id"],
                            "ejecutivo_nombre" => $value["ejecutivo_nombre"],
                            "agencia_id" => $value["agencia_id"],
                            "agencia_nombre" => $value["agencia_nombre"],
                            "registro_consolidado_codigo" => $value["registro_consolidado"],
                            "registro_consolidado" => $this->mfunciones_generales->GetValorCatalogo($value["registro_consolidado"], 'consolidado'),
                            "fecha_registro" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["fecha_registro"]),

                            "registro_num_proceso" => ((int)$value["registro_num_proceso"] <=0 ? $this->lang->line('prospecto_no_evaluacion') : $value["registro_num_proceso"]),
                            "cliente_nombre" => $value["norm_primer_nombre"] . ' ' . $value["norm_segundo_nombre"] . ' ' . $value["norm_primer_apellido"] . ' ' . $value["norm_segundo_apellido"],
                            "norm_rel_cred" => ((int)$value['norm_rel_cred']==99 ? 'Otro: ' . $value['norm_rel_cred_otro'] : $this->mfunciones_cobranzas->GetValorCatalogo($value["norm_rel_cred"], 'norm_rel_cred')),
                            "norm_finalizacion" => $this->mfunciones_cobranzas->GetValorCatalogo($value["norm_finalizacion"], 'norm_finalizacion'),

                            "cv_fecha" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["cv_fecha"]),
                            "cv_fecha_compromiso" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y($value["cv_fecha_compromiso"]),
                            "cv_fecha_compromiso_check" => $this->checkFechaComPago_vencido($value["cv_fecha_compromiso"], $value["registro_consolidado"]),

                            "norm_ultimo_paso_check" => (str_replace(' ', '', $value['norm_ultimo_paso']) != 'view_final' && (int)$value["registro_consolidado"] == 1)
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }
                else
                {
                    $lst_resultado = $arrResultado;
                }
                
                break;
                
            case 'tracking':
                
                $arrResultado = $this->ObtenerReporte_VisitaNorm($filtro_armado);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if(isset($arrResultado[0]))
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        if(!preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $value["cv_checkin_geo"]))
                        {
                            continue;
                        }
                        
                        $item_valor = array(
                            "norm_id" => $value["norm_id"],
                            "ejecutivo_id" => $value["ejecutivo_id"],
                            "ejecutivo_nombre" => $value["ejecutivo_nombre"],
                            "usuario_activo" => $value["usuario_activo"],
                            "agencia_id" => $value["agencia_id"],
                            "agencia_nombre" => $value["agencia_nombre"],
                            "agencia_estado" => $value["agencia_estado"],
                            "registro_consolidado_codigo" => $value["registro_consolidado"],
                            "registro_consolidado" => $this->mfunciones_generales->GetValorCatalogo($value["registro_consolidado"], 'consolidado'),
                            "fecha_registro" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["fecha_registro"]),

                            "registro_num_proceso" => ((int)$value["registro_num_proceso"] <=0 ? $this->lang->line('prospecto_no_evaluacion') : $value["registro_num_proceso"]),
                            "cliente_nombre" => $value["norm_primer_nombre"] . ' ' . $value["norm_segundo_nombre"] . ' ' . $value["norm_primer_apellido"] . ' ' . $value["norm_segundo_apellido"],
                            "norm_rel_cred" => ((int)$value['norm_rel_cred']==99 ? 'Otro: ' . $value['norm_rel_cred_otro'] : $this->mfunciones_cobranzas->GetValorCatalogo($value["norm_rel_cred"], 'norm_rel_cred')),
                            "norm_finalizacion" => $this->mfunciones_cobranzas->GetValorCatalogo($value["norm_finalizacion"], 'norm_finalizacion'),

                            "cv_fecha" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["cv_fecha"]),
                            "cv_fecha_compromiso" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y($value["cv_fecha_compromiso"]),
                            "cv_fecha_compromiso_check" => $this->checkFechaComPago_vencido($value["cv_fecha_compromiso"], $value["registro_consolidado"]),
                            "cv_resultado" => $this->mfunciones_generales->GetValorCatalogoDB($value["cv_resultado"], 'cobranzas_resultado_visita'),

                            "norm_ultimo_paso_check" => (str_replace(' ', '', $value['norm_ultimo_paso']) != 'view_final' && (int)$value["registro_consolidado"] == 1),
                            
                            "cv_checkin" => $value["cv_checkin"],
                            "cv_checkin_geo" => $value["cv_checkin_geo"],
                            "cv_checkin_fecha" => $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($value["cv_checkin_fecha"]),
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                }
                else
                {
                    $lst_resultado = $arrResultado;
                }
                
                break;
                
            default:
                
                js_error_div_javascript($this->lang->line('NoAutorizado'));
                exit();
                
                break;
        }
        
        $resultado->valoresFiltro = $valoresFiltro;
        
        // Se guarda en el resultado el total de filas consultadas
        $resultado->cuenta = count($lst_resultado);
        $resultado->filas = $lst_resultado;
        
        return $resultado;
    }
    
    function getReporteHomeApp($codigo_ejecutivo, $perfil_app_nombre)
    {
        $html_body = '';
        
        // 1. Se obtiene el listado de todos los Casos Asignados al Agente
        $arrLeads = $this->ObtenerBandejaCobranzasEjecutivoAll($codigo_ejecutivo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrLeads);

        $contador_compromiso_vencido = 0;
        $contador_sin_visitas = 0;
        $contador_consolidado = 0;
        $contador_no_consolidado = 0;

        $contador_fin_sin = 0;
        $contador_fin_cancelado = 0;
        $contador_fin_pago = 0;
        $contador_fin_reprogramo = 0;
        $contador_fin_normalizado = 0;

        $contador_total_leads = 0;

        if (isset($arrLeads[0])) 
        {
            foreach ($arrLeads as $key => $value) 
            {
                // Contador Consolidado
                if((int)$value["norm_consolidado"] == 1) { $contador_consolidado++; } else { $contador_no_consolidado++; }

                // Contador Finalización
                switch ((int)$value["norm_finalizacion"])
                {
                    case 1: $contador_fin_cancelado++; break;
                    case 2: $contador_fin_pago++; break;
                    case 3: $contador_fin_reprogramo++; break;
                    case 4: $contador_fin_normalizado++; break;

                    default:

                        $contador_fin_sin++;

                        break;
                }

                // Contador Fecha de compromiso de pago vencido
                if((int)$value["norm_consolidado"] == 0 && $this->checkFechaComPago_vencido($value["cv_fecha_compromiso"])) { $contador_compromiso_vencido++; }

                // Contador sin Visitas
                if($this->mfunciones_generales->VerificaFechaY_M_D_H_M_S($value["cv_fecha"]) == FALSE) { $contador_sin_visitas++; }
            }

            $contador_total_leads = count($arrLeads);
        }
        
        if($contador_total_leads > 0)
        {
            $html_body .= '

                <div class="col-sm-4">

                    <table>
                        <tr>
                            <th style="width: 75%; font-weight: bold; text-align: center;">CASOS REGISTRADOS</th>
                            <th style="width: 25%; font-weight: bold; text-align: right;">' . number_format($contador_total_leads, 0, '.', ',') . ' &nbsp;&nbsp;</th>
                        </tr>

                        <tr>
                            <td style="width: 75%; text-align: left;"><h4 style="text-align: left;">Consolidados (Completado)</h4></td>
                            <td style="width: 25%; text-align: right;"><h4 style="text-align: right;">' . number_format($contador_consolidado, 0, '.', ',') . '</h4> </td>
                        </tr>

                        <tr>
                            <td style="width: 75%; text-align: left;"><h4 style="text-align: left;">No Consolidados (Registro)</h4></td>
                            <td style="width: 25%; text-align: right;"><h4 style="text-align: right;">' . number_format($contador_no_consolidado, 0, '.', ',') . '</h4> </td>
                        </tr>
                    </table>
                    
                    <table>
                        <tr style="background-color: #f5f5f5;">
                            <td style="width: 75%; text-align: left;"><h4 style="text-align: left;">Compromisos Vencidos</h4></td>
                            <td style="width: 25%; text-align: right;"><h4 style="text-align: right;">' . number_format($contador_compromiso_vencido, 0, '.', ',') . '</h4> </td>
                        </tr>
                        
                        <tr style="background-color: #f5f5f5;">
                            <td style="width: 75%; text-align: left;"><h4 style="text-align: left;">Sin visitas registradas</h4></td>
                            <td style="width: 25%; text-align: right;"><h4 style="text-align: right;">' . number_format($contador_sin_visitas, 0, '.', ',') . '</h4> </td>
                        </tr>
                    </table>

                </div>
                ';
            
            // 2. Se obtiene el cálculo de Finalización

            $html_body .= '

                <br />

                <div class="col-sm-3">

                <fieldset>
                    <legend> .: Casos - Finalización de la Gestión :. </legend>';

            $total_fin = $contador_total_leads;

            $arrayContadorFin = array();
            $arrayContadorFin[] = array(
                "nombre" => $this->GetValorCatalogo(0, 'finaliza_gestion'),
                "contador" => $contador_fin_sin,
                "color" => '#A4A4A5'
            );

            $arrayContadorFin[] = array(
                "nombre" => $this->GetValorCatalogo(1, 'finaliza_gestion'),
                "contador" => $contador_fin_cancelado,
                "color" => '#c7ab21'
            );

            $arrayContadorFin[] = array(
                "nombre" => $this->GetValorCatalogo(2, 'finaliza_gestion'),
                "contador" => $contador_fin_pago,
                "color" => '#6EA141'
            );

            $arrayContadorFin[] = array(
                "nombre" => $this->GetValorCatalogo(3, 'finaliza_gestion'),
                "contador" => $contador_fin_reprogramo,
                "color" => '#F88400'
            );

            $arrayContadorFin[] = array(
                "nombre" => $this->GetValorCatalogo(4, 'finaliza_gestion'),
                "contador" => $contador_fin_normalizado,
                "color" => '#006699'
            );


            if (isset($arrayContadorFin[0])) 
            {
                $total_fin = 0;

                foreach ($arrayContadorFin as $key => $value) 
                {
                    $total_fin += $value["contador"];
                }

                foreach ($arrayContadorFin as $key => $value) 
                {
                    $contador_porcentaje = ($total_fin!=0 ? ($value["contador"]/$total_fin)*100 : 0);

                    $html_body .= '

                            <div class="barra_contenedor">
                                <div class="barra_titulo"> ' . $value["nombre"] . ' - ' . number_format($value["contador"], 0, '.', ',') . ' Casos</div>
                                <div class="barra_color" style="background-color: ' . $value["color"] . '; width: ' . $contador_porcentaje . '%;"> </div>
                                <div class="barra_texto"> ' . number_format($contador_porcentaje, 2, '.', '.') . '% </div>
                            </div>';
                }
            }

            $html_body .= '

                </fieldset>
                
                </div>
                ';


            // 3. Se obtiene el cálculo de Agencias
            $CalculoAgencias = $this->CalculoAgenciasCobrador($codigo_ejecutivo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($CalculoAgencias);

            $html_body .= '

                <br />

                <div class="col-sm-4">

                <fieldset>
                    <legend> .: Casos - Por Agencia :. </legend>';

            if (isset($CalculoAgencias[0])) 
            {
                $total_agencia = 0;

                foreach ($CalculoAgencias as $key => $value) 
                {
                    $total_agencia += $value["contador"];
                }

                foreach ($CalculoAgencias as $key => $value) 
                {
                    $contador_porcentaje = ($total_agencia!=0 ? ($value["contador"]/$total_agencia)*100 : 0);

                    $html_body .= '

                            <div class="barra_contenedor">
                                <div class="barra_titulo"> ' . $this->mfunciones_microcreditos->TextoTitulo($value["estructura_regional_nombre"]) . ((int)$value["estructura_regional_estado"]==1 ? '' : ' (Cerrada)') . ' - ' . number_format($value["contador"], 0, '.', ',') . ' Casos</div>
                                <div class="barra_color" style="background-color: ' . '#006699' . '; width: ' . $contador_porcentaje . '%;"> </div>
                                <div class="barra_texto"> ' . number_format($contador_porcentaje, 2, '.', ',') . '% </div>
                            </div>';
                }
            }

            $html_body .= '

                </fieldset>
                
                </div>

                ';
        }
        else
        {
            $html_body .= ' <div align="center" style="text-align: center;"> 
                                <img style="width: 50px;" src=\'' . $this->config->base_url() . 'html_public/imagenes/info.png\' /> 
                                <br />
                                <h2 style="text-align: center;"> ' .$perfil_app_nombre . ' <br />[Sin registros]</h2>
                                <i>Para comenzar [indicaciones].</i>
                            </div> ';
        }
        
        return $html_body;
    }
    
    function checkFechaComPago_vencido($fecha, $consolidado=0)
    {
        // Si está consolidado ya no se cuenta como vencido
        if((int)$consolidado == 1)
        {
            return FALSE;
        }
        
        $today_dt = new DateTime(date("Y-m-d"));
        
        // Validar fecha de compromiso de pago de la última visita registrada
        if($this->mfunciones_generales->VerificaFechaY_M_D($fecha) != FALSE)
        {
            if ($today_dt >  new DateTime($fecha))
            {
                return TRUE;
            }
        }
        
        return FALSE;
    }
    
    function checkVisitaRegistrada($norm_id, $codigo_visita, $opcion_filtro, $opcion_query='ORDER BY cv_id DESC LIMIT 1')
    {
        $this->lang->load('general', 'castellano');
        
        $resultado = new stdClass();
        $resultado->error = FALSE;
        $resultado->error_texto = '';
        
        $arrVisita = $this->getVisitasRegistroJoin($norm_id, $codigo_visita, $opcion_query);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrVisita);
        
        switch ($opcion_filtro) {
            case 'check_nueva_visita':
                // Se valida si tiene registros de visitas y que la última tenga marcado el Check-In
                if(isset($arrVisita[0]))
                {
                    $check_geo = $this->mfunciones_microcreditos->validateGEO_simple($arrVisita[0]['cv_checkin_geo']);
                    
                    if((int)$arrVisita[0]['cv_checkin'] == 0 || $check_geo->lat == 0)
                    {
                        $resultado->error = TRUE;
                        $resultado->error_texto = $this->lang->line('cv_error_nuevo_nocheckin') . (!$this->mfunciones_microcreditos->CheckIsMobile() ? ' También puede "Forzar Check Visita" desde las opciones del registro.' : '');
                    }
                }

                break;
                
            case 'check_checkin':
                
                if(!isset($arrVisita[0]))
                {
                    $resultado->error = TRUE;
                    $resultado->error_texto = $this->lang->line('cv_error_noregistros');
                    
                    return $resultado;
                }
                
                $check_geo = $this->mfunciones_microcreditos->validateGEO_simple($arrVisita[0]['cv_checkin_geo']);

                if((int)$arrVisita[0]['cv_checkin'] == 1 && $check_geo->lat != 0)
                {
                    $resultado->error = TRUE;
                    $resultado->error_texto = $this->lang->line('cv_error_sicheckin');
                }
                else
                {
                    $resultado->codigo_visita = $arrVisita[0]['cv_id'];
                }
                
                if((int)$arrVisita[0]['norm_consolidado'] == 1)
                {
                    $resultado->error = TRUE;
                    $resultado->error_texto = $this->lang->line('norm_error_consolidado');
                    
                    return $resultado;
                }
                
                break;
                
            case 'check_consolidar':
                
                if(!isset($arrVisita[0]))
                {
                    $resultado->error = TRUE;
                    $resultado->error_texto = $this->lang->line('cv_error_noregistros');
                    
                    return $resultado;
                }
                
                foreach ($arrVisita as $key => $value) 
                {
                    $check_geo = $this->mfunciones_microcreditos->validateGEO_simple($value['cv_checkin_geo']);
                    
                    if((int)$value['cv_checkin'] == 0 || $check_geo->lat == 0)
                    {
                        $resultado->error = TRUE;
                        $resultado->error_texto = $this->lang->line('norm_error_sincheckin');

                        return $resultado;
                    }
                }
                
                if((int)$arrVisita[0]['norm_consolidado'] == 1)
                {
                    $resultado->error = TRUE;
                    $resultado->error_texto = $this->lang->line('norm_error_consolidado');
                    
                    return $resultado;
                }
                
                // Validar que tenga marcado la Finalización de la Gestión
                if((int)$arrVisita[0]['norm_finalizacion'] <= 0)
                {
                    $resultado->error = TRUE;
                    $resultado->error_texto = $this->lang->line('norm_error_nofinalizacion');
                }
                
                $resultado->usuario_id = $arrVisita[0]['usuario_id'];
                
                break;

            default:
                break;
        }
        
        return $resultado;
    }
    
    function ObtenerCatalogoSelectNorm($campo, $valor, $codigo_catalogo, $parent_codigo=-1, $parent_tipo=-1, $filtro=-1, $sin_seleccion='')
    {
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        $arrResultado = $this->mfunciones_logica->ObtenerCatalogo($codigo_catalogo, $parent_codigo, $parent_tipo, $filtro);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            return html_select($campo, $arrResultado, 'catalogo_codigo', 'catalogo_descripcion', $sin_seleccion, $valor);
        }
        else
        {
            $arrResultado[0] = array(
                "catalogo_codigo" => '-1',
                "catalogo_descripcion" => 'No se encontró dependencias',
            );
        }
        
        return html_select($campo, $arrResultado, 'catalogo_codigo', 'catalogo_descripcion', '', $valor);
    }
    
    // Obtener estadistica de Tiempo de registro de Rubro
    function RegTiempoRegistroRubro($aux_fecha_ini, $aux_fecha_fin)
    {
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        $calculo_tiempo = $this->mfunciones_generales->getDiasLaborales($aux_fecha_ini, $aux_fecha_fin);

        $calculo_estado = "Atrasado";

        $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa(1);
        $tiempo_etapa = $arrEtapa[0]['etapa_tiempo'];

        if($tiempo_etapa > 0)
        {
            $total_porcentaje = 100 - round(($calculo_tiempo*100)/$tiempo_etapa);

            if($total_porcentaje > 50)
            {
                $calculo_estado = "A tiempo";
            }        
            elseif($total_porcentaje >= 0)
            {
                $calculo_estado = "A tiempo";
            }
            elseif($total_porcentaje < 0)
            {
                $calculo_estado = "Atrasado";
            }
        }

        $resultado = new stdClass();
        
        $resultado->aux_fecha_ini = $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($aux_fecha_ini);
        $resultado->aux_fecha_fin = $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($aux_fecha_fin);
        $resultado->calculo_tiempo = number_format($calculo_tiempo, 0, '.', ',') . ' Horas';
        $resultado->calculo_estado = $calculo_estado;
        $resultado->tiempo_etapa = number_format($tiempo_etapa, 0, '.', ',') . ' Horas';

        return $resultado;
    }
    
    /**** REGISTRAR SEGUIMIENTO, ETAPA E HITO Y ENVIAR CORREO (último parámetro 1=Envio a etapas hijas    2=Envio a etapa específica ****/
    function NotificacionEtapaRegistro($solicitud_id, $codigo_etapa, $regionalizado) {
        
        $this->load->model('mfunciones_logica');
        
        $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa($codigo_etapa);

        if (isset($arrEtapa[0]))
        {
            foreach ($arrEtapa as $key1 => $value1) 
            {
                // 0 = No Envía Correo      1 = Sí Envía Correo
                if($value1['etapa_notificar_correo'] == 1)
                {
                    $arrTerceros = $this->DatosRegistroEmail($solicitud_id);
                    
                    $arrUsuariosNotificar = $this->mfunciones_generales->getListadoUsuariosEtapa($codigo_etapa, $arrTerceros[0]['codigo_agencia_fie'], $regionalizado);

                    if (isset($arrUsuariosNotificar[0])) 
                    {
                        foreach ($arrUsuariosNotificar as $key => $value) 
                        {
                            $destinatario_nombre = $value['usuario_nombres'] . ' ' . $value['usuario_app'] . ' ' . $value['usuario_apm'];
                            $destinatario_correo = $value['usuario_email'];
                            
                            // SE PROCEDE CON EL ENVÍO DE CORREO ELECTRÓNICO
                            $correo_enviado = $this->mfunciones_generales->EnviarCorreo('notificar_instancia_norm', $destinatario_correo, $destinatario_nombre, $arrTerceros);
                        }
                    }
                }
            }
        }
    }
    
    function getRegVistasRubro($codigo_rubro)
    {
        $vistas_rubro = new stdClass();
        
        $vistas_rubro->norm_credito = array(
            "norm_datos_personales",
            "norm_direccion",
        );
        
        switch ($codigo_rubro) {
            case 13:
                
                $array_rubro = $vistas_rubro->norm_credito;

                break;
            
            default:
                
                $array_rubro = $vistas_rubro->norm_credito;
                
                break;
        }
        
        return $array_rubro;
    }
    
    function getContenidoNavApp($codigo_registro, $paso_actual='0', $codigo_tipo_persona='0')
    {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->DatosRegistroEmail($codigo_registro);
        
        if (!isset($arrResultado[0])) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        // Paso 1: En base al Código de Prospecto, se obtiene la información
        
        $titular_nombre = $arrResultado[0]['norm_nombre_completo'];
        
        $familia_nombre = 'REGISTRO DEL TITULAR';
            
        $titular_rubro = $this->mfunciones_generales->GetValorCatalogo($arrResultado[0]['camp_id'], 'nombre_rubro');
        $registro_num_proceso = ((int)$arrResultado[0]['registro_num_proceso'] <=0 ? $this->lang->line('prospecto_no_evaluacion') : $arrResultado[0]['registro_num_proceso']);
        
        $codigo_rubro = $arrResultado[0]['camp_id'];
        
        $color_rubro = $this->mfunciones_generales->GetValorCatalogo($arrResultado[0]['camp_id'], 'color_rubro');
        
        // Paso 2: Se establece el array de las vista según el rubro seleccionado
        
        $array_rubro = $this->getRegVistasRubro($codigo_rubro);
        
        // Se establece el máximo número de pasos
        $maximo_pasos = count($array_rubro) + 1;
        
        // Paso 3: Se establece las vistas (anterior | actual | siguiente)
        
        $vista_actual = $paso_actual;
            $vista_prospecto = $this->mfunciones_generales->paso_ant_sig($vista_actual, $array_rubro);
        $vista_anterior = $vista_prospecto->anterior;
        $vista_siguiente = $vista_prospecto->siguiente;
        
        $vista_actual_numero = $vista_prospecto->index+1;
        
        $contenido = '';
        
        if($vista_actual != '0')
        {
            $contenido .= '
            
                <div class="main_nav_bloque" style="position: fixed; top: 0;">
                    <span style="color: ' . $color_rubro . ' !important;" class="nav-home" onclick="ElementoSubmit(\'home\');"><i class="fa fa-home" aria-hidden="true"></i></span>
                </div>
                ';
        }elseif($codigo_tipo_persona=='unidad_familiar')
        {
            $contenido .= '
            
                <div class="main_nav_bloque" style="position: fixed; top: 0;">
                    <span class="nav-home" onclick="ElementoSubmit(\'home\');"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                </div>
                ';
        }
        
        $contenido .= '

            <div style="text-align: right;">
                <span class="nav-titulo">' . $titular_nombre . ' ' . $this->mfunciones_generales->GetValorCatalogo(1, 'icono_categoria') . '</span>
                <br />
                <span class="nav-subtitulo"> ' . $titular_rubro . ' </span>
                <br />
                <span class="nav-subtitulo"> Nro. Operación: ' . $registro_num_proceso . ' </span>
            </div>

            <div style="clear: both"></div>
            
            <div class="diraux_nav_bloque" style="display: none;">
                <div class="container panel-heading">
                    <label class="label-campo panel-heading" for="rd_actividad_principal" style="padding: 0px; margin-left: 0px;"> <i class="fa fa-map-marker" aria-hidden="true"></i> REGISTRO DIRECCIÓN <span id="diraux_nav_bloque_titulo">#3</span> </label>
                </div>
                <div class="row">
                    <div class="col" style="text-align: left;">
                        <span class="nav-avanza btn-danger" style="padding: 5px 18%;" onclick="Cancelar_FormularioDireccion();"><i class="fa fa-ban" aria-hidden="true"></i> Cancelar </span> 
                    </div>

                    <div class="col" style="text-align: right;">
                        <span class="nav-avanza" style="padding: 5px 18%;" onclick="pregunta_dir_acciones();"> <i class="fa fa-cogs" aria-hidden="true"></i> ACCIONES </span> 
                    </div>
                </div>
            </div>

            ';
        
            if($vista_actual != '0')
            {
                $contenido .= '
        
                <br />

                <div class="main_nav_bloque container">
                    <ul class="progress-indicator">
                ';

                    // Bucle para el Stepper, en base al array seleccionado
                
                    $contador_pasos = 0;

                    foreach ($array_rubro as $key => $value) 
                    {
                        $contador_pasos++;
                        
                        if($vista_actual_numero >= $contador_pasos)
                        {
                            $stepper_clase = 'class="completed"';
                        }
                        else
                        {
                            $stepper_clase = '';
                        }
                        
                        if($vista_actual_numero == $contador_pasos)
                        {
                            $stepper_actual = '<i class="fa fa-pencil" aria-hidden="true"></i>';
                        }
                        else
                        {
                            $stepper_actual = $contador_pasos;
                        }

                        $contenido .= '

                                <li ' . $stepper_clase . '>
                                    <span class="bubble" onclick="ElementoSubmit(\'' . $value . '\');">' . $stepper_actual . '</span>
                                </li>
                        ';
                    }

                $aux_botones = '';

                if($vista_actual_numero > 1)
                {
                    $aux_botones .= '

                        <div class="col" style="text-align: left;">
                            <span class="nav-avanza" onclick="ElementoSubmit(\'ant\');"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Anterior</span> 
                        </div>

                        ';
                }

                if($vista_actual_numero+1 < $maximo_pasos)
                {
                    $aux_botones .= '

                        <div class="col" style="text-align: right;">
                            <span class="nav-avanza" onclick="ElementoSubmit(\'sig\');">Siguiente <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span> 
                        </div>

                        ';
                }
                
                if($vista_actual_numero+1 >= $maximo_pasos)
                {
                    $aux_botones .= '

                        <div class="col" style="text-align: right;">
                            <span class="nav-avanza" onclick="ElementoSubmit(\'final\');">Finalizar <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span> 
                        </div>

                        ';
                }

                if($vista_actual != 'view_final')
                {
                    $contenido .= '

                        </ul>

                    </div>

                    <div style="clear: both"></div>

                    <div class="main_nav_bloque container" style="margin-top: 5px;">
                        <div class="row"> ' . $aux_botones . ' </div>
                    </div>

                    ';
                }
            }
        
        return $contenido;
    }
    
    function rand_color() {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
    
    function GetValorCatalogo($data, $tipo) {
        
        $resultado = 'No Registrado';
        
        if($tipo == 'norm_rel_cred')
        {
            switch ($data) {              
                case 1:
                    $resultado = 'Deudor';
                    break;
                case 2:
                    $resultado = 'Codeudor';
                    break;
                case 3:
                    $resultado = 'Garante';
                    break;
                case 99:
                    $resultado = 'Otro';
                    break;
                default:
                    $resultado = 'No Registrado';
                    break;
            }
        }
        
        if($tipo == 'norm_finalizacion')
        {
            switch ($data) {              
                case 1:
                    $resultado = 'Crédito Cancelado';
                    break;
                case 2:
                    $resultado = 'Cliente Pagó';
                    break;
                case 3:
                    $resultado = 'Reprogramó';
                    break;
                case 4:
                    $resultado = 'Crédito Normalizado';
                    break;
                default:
                    $resultado = 'No Registrado';
                    break;
            }
        }
        
        if($tipo == 'rd_referencia')
        {
            switch ($data) {              
                case 0:
                    $resultado = 'Sin Selección';
                    break;
                case 1:
                    $resultado = 'GEO';
                    break;
                case 2:
                    $resultado = 'Croquis';
                    break;
                default:
                    $resultado = 'No Registrado';
                    break;
            }
        }
        
        if($tipo == 'rd_tipo_abrev')
        {
            switch ($data) {              
                case 1:
                    $resultado = 'Neg.';
                    break;
                case 2:
                    $resultado = 'Dom.';
                    break;
                default:
                    $resultado = 'No Registrado';
                    break;
            }
        }
        
        if($tipo == 'rd_tipo')
        {
            switch ($data) {              
                case 1:
                    $resultado = 'Negocio';
                    break;
                case 2:
                    $resultado = 'Domicilio';
                    break;
                default:
                    $resultado = 'No Registrado';
                    break;
            }
        }
        
        if($tipo == 'norm_estado')
        {
            switch ($data) {              
                case 0:
                    $resultado = 'Registro';
                    break;
                case 1:
                    $resultado = 'Completado';
                    break;
                default:
                    $resultado = 'No Registrado';
                    break;
            }
        }
        
        if($tipo == 'finaliza_gestion')
        {
            switch ($data) {              
                case 1:
                    $resultado = 'Crédito Cancelado';
                    break;
                case 2:
                    $resultado = 'Cliente Pago';
                    break;
                case 3:
                    $resultado = 'Reprogramó';
                    break;
                case 4:
                    $resultado = 'Crédito Normalizado';
                    break;
                default:
                    $resultado = 'No Registrado';
                    break;
            }
        }
        
        return($resultado);
    }
    
    function GuardarDocumentoRegistroBase64PDF($codigo_registro, $codigo_documento, $documento_pdf_base64) {
		
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerNombreDocumento($codigo_documento);        
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
			
        // 1. Obtener el nombre del documento

        if (isset($arrResultado1[0])) 
        {
            $nombre_documento = $this->mfunciones_generales->TextoNoAcentoNoEspacios($arrResultado1[0]['documento_nombre']);

            //Se añade la fecha y hora al final
            $nombre_documento .= '__' . date('Y-m-d_H_i_s') . '.pdf';
            $path = $this->lang->line('ruta_cobranzas') . 'reg_' . $codigo_registro . '/reg_' . $codigo_registro . '_' . $nombre_documento;
            $pdf = $documento_pdf_base64;
            $decoded = base64_decode($pdf);

            if(!file_put_contents($path, $decoded)) { if(file_exists ($path)){ unlink($path); } return FALSE; }
            else { return $nombre_documento; }
        }
        else 
        {
            return FALSE;
        }
    }
    
    function DatosRegistroEmail($codigo_solicitud)
    {
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->ObtenerDetalleRegistro($codigo_solicitud);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            $arrRegion = $this->mfunciones_logica->ObtenerDatosRegional($arrResultado[0]["codigo_agencia_fie"]);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRegion);
            
            if (isset($arrRegion[0])) 
            {
                $nombre_region = $arrRegion[0]['estructura_regional_nombre'];
                $monto_region = $arrRegion[0]['estructura_regional_monto'];
                $estado_region = $arrRegion[0]['estructura_regional_estado'];
            }
            else
            {
                $nombre_region = 'Sin Selección';
                $monto_region = '';
                $estado_region = '';
            }
            
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                $item_valor = array(

                    "codigo_agencia_fie" => $value["codigo_agencia_fie"],
                    "nombre_agencia" => $nombre_region,
                    "monto_agencia" => $monto_region,
                    "estado_region" => $estado_region,
                    
                    "agente_codigo" => $value["agente_codigo"],
                    "agente_nombre" => $value["agente_nombre"],
                    "agente_correo" => $value["agente_correo"],
                    "agente_telefono" => $value["usuario_telefono"],
                    
                    "registro_num_proceso" => $value["registro_num_proceso"],
                    
                    "cv_fecha_compromiso_check" => $this->checkFechaComPago_vencido($value["cv_fecha_compromiso"], $value["norm_consolidado"]),
                    "cv_fecha_compromiso" => $value["cv_fecha_compromiso"],
                    
                    "camp_id" => 13, // <-- Valor constante
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "norm_ultimo_paso" => $value["norm_ultimo_paso"],
                    "norm_consolidado" => $value["norm_consolidado"],
                    
                    "norm_id" => $value["norm_id"],
                    "norm_estado" => $this->GetValorCatalogo($arrResultado[0]['norm_estado'], 'norm_estado'),
                    "norm_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["norm_fecha"]),
                    "norm_consolidado" => $value["norm_consolidado"],
                    "norm_consolidado_usuario" => $value["norm_consolidado_usuario"],
                    "norm_consolidado_fecha" => $value["norm_consolidado_fecha"],
                    "norm_consolidado_geo" => $value["norm_consolidado_geo"],
                    "norm_registro_completado_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["norm_registro_completado_fecha"]),
                    "norm_observado_app" => $value["norm_observado_app"],
                    
                    "norm_nombre_completo" => $value["norm_primer_nombre"] . ' ' . $value["norm_segundo_nombre"] . ' ' .$value["norm_primer_apellido"] . ' ' . $value["norm_segundo_apellido"],
                    "norm_primer_nombre" => $value["norm_primer_nombre"],
                    "norm_segundo_nombre" => $value["norm_segundo_nombre"],
                    "norm_primer_apellido" => $value["norm_primer_apellido"],
                    "norm_segundo_apellido" => $value["norm_segundo_apellido"],
                    "norm_cel" => $value["norm_cel"],
                    "norm_actividad" => $value["norm_actividad"],
                    "norm_rel_cred" => $value["norm_rel_cred"],
                    "norm_rel_cred_otro" => $value["norm_rel_cred_otro"],
                    "norm_finalizacion" => $value["norm_finalizacion"],
                    
                );
                $lst_resultado[$i] = $item_valor;
                
                $i++;
            }
            
            return $lst_resultado;
        }
        
        return FALSE;
    }
    
    function RegGetDocDigitalizado($codigo_registro, $codigo_documento_registro, $filtro) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->RegObtenerDocumentoDigitalizar($codigo_registro, $codigo_documento_registro);        
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
        
        if (isset($arrResultado1[0])) 
        {
            $ruta = $this->lang->line('ruta_cobranzas');
            $documento = $arrResultado1[0]['prospecto_carpeta'] . '/' . $arrResultado1[0]['prospecto_carpeta'] . '_' .$arrResultado1[0]['registro_documento_pdf'];

            $path = $ruta . $documento;
            
            if(file_exists($path))
            {
                if($filtro == 'existe')
                {
                    return TRUE;
                }

                if($filtro == 'documento')
                {
                    // Obtener la cadena base64 de un documento especifico de un prospecto específico
                    $data = file_get_contents($path);
                    $base64 = base64_encode($data);

                    return $base64;
                }
                
                if($filtro == 'ruta')
                {
                    return $documento;
                }
                
                if($filtro == 'path')
                {
                    return $path;
                }
            }
            else
            {
                return FALSE;
            }
        }
        else 
        {
            return FALSE;
        }
    }
    
    function GetInfoRegistroDigitalizadoPDF($codigo_solicitud, $codigo_documento, $filtro) {

        $arrResultado1 = $this->VerificaDocumentosRegistroDigitalizar($codigo_solicitud, $codigo_documento);        
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
        
        if (isset($arrResultado1[0])) 
        {
            $ruta = $this->lang->line('ruta_cobranzas');
            
            $documento = $arrResultado1[0]['solicitud_carpeta'] . '/' . $arrResultado1[0]['solicitud_carpeta'] . '_' .$arrResultado1[0]['registro_documento_pdf'];

            $path = $ruta . $documento;

            if(file_exists($path))
            {
                if($filtro == 'existe')
                {
                    return TRUE;
                }

                if($filtro == 'documento')
                {
                    // Obtener la cadena base64 de un documento especifico de un prospecto específico
                    
                    // Obtener la cadena base64 de un documento especifico de un prospecto específico
                    $data = file_get_contents($path);
                    $base64 = base64_encode($data);

                    return $base64;
                }
                
                if($filtro == 'ruta')
                {
                    return $documento;
                }
                
                if($filtro == 'path')
                {
                    return $path;
                }
                
            }
            else
            {
                return FALSE;
            }
        }
        else 
        {
            return FALSE;
        }
    }
    
    // Función para verificar si un documento de un prospecto esta observado
    function RegistroVerificaDocumentoObservado($codigo_registro, $codigo_documento, $codigo_tipo_registro)
    {
        $arrResultado = $this->RegistroVerDocumentoObservado($codigo_registro, $codigo_documento, $codigo_tipo_registro);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    
    /*************** FUNCTIONS - FIN ****************************/
    
/*************** DATABASE - INICIO ****************************/
    
    function update_NornNroOperacion($codigo_registro, $numero_operacion, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "UPDATE cobranza SET registro_num_proceso=?, accion_usuario=?, accion_fecha=? WHERE norm_id=? ";
            
            $this->db->query($sql, array((int)$numero_operacion, $accion_usuario, $accion_fecha, $codigo_registro));
            
            return TRUE;
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function setNormAgenciaAsociada($codigo_registro, $codigo_agencia, $accion_usuario, $accion_fecha)
    {
        try {
            
            $sql = "UPDATE cobranza SET
                
                        codigo_agencia_fie=?,
                        accion_usuario=?,
                        accion_fecha=?
                        
                    WHERE norm_id = ?";

            $this->db->query($sql, array($codigo_agencia, $accion_usuario, $accion_fecha, $codigo_registro));

            return TRUE;
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerReporte_VisitaNorm($filtro)
    {
        try 
        {       
            $sql = "SELECT *
                    FROM(
                        SELECT c.*, 
                        er.estructura_regional_id as 'agencia_id',
                        er.estructura_regional_nombre as 'agencia_nombre',
                        er.estructura_regional_departamento as 'agencia_departamento',
                        er.estructura_regional_provincia as 'agencia_provincia',
                        er.estructura_regional_ciudad as 'agencia_ciudad',
                        er.estructura_regional_estado as 'agencia_estado',
                        CONCAT_WS(' ', u.usuario_nombres, u.usuario_app, u.usuario_apm) as 'ejecutivo_nombre',
                        u.usuario_activo,
                        c.norm_consolidado as 'registro_consolidado',
                        c.norm_fecha as 'fecha_registro',
                        cv.cv_fecha, 
                        cv.cv_fecha_compromiso,
                        cv.cv_checkin,
                        cv.cv_checkin_geo,
                        cv.cv_checkin_fecha,
                        cv.cv_resultado
                        FROM cobranza_visita cv
                        INNER JOIN cobranza c ON c.norm_id=cv.norm_id
                        INNER JOIN ejecutivo e ON e.ejecutivo_id=c.ejecutivo_id
                        INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                        INNER JOIN rol r ON r.rol_id=u.usuario_rol
                        INNER JOIN perfil_app pa ON pa.perfil_app_id=r.perfil_app_id AND pa.perfil_app_id=3
                        INNER JOIN estructura_regional er ON er.estructura_regional_id=c.codigo_agencia_fie
                        WHERE cv.cv_checkin=1
                        ORDER BY c.norm_id ASC, cv.cv_id ASC
                    ) tabla_auxiliar WHERE 1" . $filtro;
            
            $consulta = $this->db->query($sql, array());

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function ObtenerReporte_Norm($filtro)
    {
        try 
        {       
            $sql = "SELECT *
                    FROM(
                        SELECT c.*, 
                        er.estructura_regional_id as 'agencia_id',
                        er.estructura_regional_nombre as 'agencia_nombre',
                        er.estructura_regional_departamento as 'agencia_departamento',
                        er.estructura_regional_provincia as 'agencia_provincia',
                        er.estructura_regional_ciudad as 'agencia_ciudad',
                        er.estructura_regional_estado as 'agencia_estado',
                        CONCAT_WS(' ', u.usuario_nombres, u.usuario_app, u.usuario_apm) as 'ejecutivo_nombre',
                        u.usuario_activo,
                        c.norm_consolidado as 'registro_consolidado',
                        c.norm_fecha as 'fecha_registro', 
                        cv.cv_fecha, 
                        cv.cv_fecha_compromiso,
                        cv.cv_checkin_fecha,
                        cv.cv_resultado
                        FROM cobranza c
                        LEFT JOIN (
                            SELECT max(cv_id) max_id, norm_id
                            FROM cobranza_visita
                            GROUP BY norm_id
                            ) aux ON aux.norm_id=c.norm_id
                        LEFT JOIN cobranza_visita cv ON cv.cv_id=aux.max_id
                        INNER JOIN ejecutivo e ON e.ejecutivo_id=c.ejecutivo_id
                        INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                        INNER JOIN rol r ON r.rol_id=u.usuario_rol
                        INNER JOIN perfil_app pa ON pa.perfil_app_id=r.perfil_app_id AND pa.perfil_app_id=3
                        INNER JOIN estructura_regional er ON er.estructura_regional_id=c.codigo_agencia_fie
                        ORDER BY c.norm_id ASC
                    ) tabla_auxiliar WHERE 1" . $filtro;
            
            $consulta = $this->db->query($sql, array());

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function ObtenerListaNorm_generico($filtro, $perfil_app=3)
    {        
        try 
        {
            $sql = "SELECT DISTINCT e.ejecutivo_id, CONCAT_WS(' ', u.usuario_nombres, u.usuario_app, u.usuario_apm) as 'ejecutivo_nombre', u.usuario_activo
                    FROM ejecutivo e
                    INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                    INNER JOIN estructura_agencia ea ON ea.estructura_agencia_id=u.estructura_agencia_id
                    INNER JOIN estructura_regional er ON er.estructura_regional_id=ea.estructura_regional_id
                    INNER JOIN rol r ON r.rol_id=u.usuario_rol
                    INNER JOIN perfil_app pa ON pa.perfil_app_id=r.perfil_app_id AND pa.perfil_app_id IN ($perfil_app)
                    WHERE 1 $filtro"; 
            
            $consulta = $this->db->query($sql, array());

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function ObtenerDatosNorm_dashboard_generico($parent_codigo=-1, $criterio='', $aux, $aux_estado, $perfil_app=3)
    {
        $parent_codigo = htmlspecialchars($parent_codigo);
        $criterio = htmlspecialchars($criterio);
        
        if($aux == 0)
        {
            $filtro = ' AND er.estructura_regional_id IN (' . $parent_codigo . ')';
        }
        
        // Filtro Estado
        if($aux_estado != 2)
        {
            $aux_estado = 'u.usuario_activo=' . $aux_estado . ' AND er.estructura_regional_estado=' . $aux_estado;
        }
        else
        {
            $aux_estado = '1';
        }
        
        try 
        {   
            $sql = "SELECT u.usuario_activo, er.estructura_regional_id, er.estructura_regional_nombre, e.ejecutivo_id, CONCAT_WS(' ', u.usuario_app, u.usuario_apm, u.usuario_nombres) as 'ejecutivo_nombre'
                    FROM estructura_regional er
                    INNER JOIN estructura_agencia ea ON ea.estructura_regional_id=er.estructura_regional_id
                    INNER JOIN usuarios u ON u.estructura_agencia_id=ea.estructura_agencia_id
                    INNER JOIN ejecutivo e ON e.usuario_id=u.usuario_id
                    INNER JOIN rol r ON r.rol_id=u.usuario_rol
                    INNER JOIN perfil_app pa ON pa.perfil_app_id=r.perfil_app_id AND pa.perfil_app_id IN ($perfil_app)
                    WHERE $aux_estado $filtro AND er.estructura_regional_id IN (" . $criterio . ") ORDER BY u.usuario_app ASC";

            $consulta = $this->db->query($sql, array());

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function RegUpdateObservacionDoc($obs_estado, $accion_usuario, $accion_fecha, $codigo_registro, $codigo_tipo_registro)
    {        
        try 
        {
            $sql = "UPDATE registro_observacion_documento SET obs_estado=?, accion_usuario=?, accion_fecha=? WHERE codigo_registro=? AND tipo_persona_id=? "; 
            
            $consulta = $this->db->query($sql, array($obs_estado, $accion_usuario, $accion_fecha, $codigo_registro, $codigo_tipo_registro));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function setConsolidarNorm($geolocalizacion, $codigo_usuario, $accion_usuario, $accion_fecha, $codigo_registro)
    {        
        try 
        {
            $sql = "UPDATE cobranza SET norm_observado_app=0, norm_estado=1, norm_consolidado=1, norm_consolidado_geo=?, norm_consolidado_fecha=?, norm_consolidado_usuario=?, accion_usuario=?, accion_fecha=? WHERE norm_id=? ";

            $consulta = $this->db->query($sql, array($geolocalizacion, $accion_fecha, $codigo_usuario, $accion_usuario, $accion_fecha, $codigo_registro));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function NormVisitaUpdateCheckInForzar($geoCheckIn, $codigo_visita, $norm_id, $usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "UPDATE cobranza_visita SET cv_checkin=1, cv_checkin_fecha=?, cv_checkin_geo=?, accion_usuario=?, accion_fecha=? WHERE norm_id=? AND (cv_checkin=0 OR cv_checkin_geo IS NULL) "; 

            $consulta = $this->db->query($sql, array($accion_fecha, $geoCheckIn, $usuario, $accion_fecha, $norm_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function NormVisitaUpdateCheckIn($geoCheckIn, $codigo_visita, $norm_id, $usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "UPDATE cobranza_visita SET cv_checkin=1, cv_checkin_fecha=?, cv_checkin_geo=?, accion_usuario=?, accion_fecha=? WHERE cv_id=? AND norm_id=? "; 

            $consulta = $this->db->query($sql, array($accion_fecha, $geoCheckIn, $usuario, $accion_fecha, $codigo_visita, $norm_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function update_visita_registro(
                        $cv_resultado,
                        $cv_fecha_compromiso,
                        $cv_observaciones,
                
                        $accion_usuario,
                        $accion_fecha,
                        $norm_id,
                        $codigo_registro
                        )
    {
        try 
        {            
            $sql = "UPDATE cobranza_visita SET
                
                        cv_resultado = ?,
                        cv_fecha_compromiso = ?,
                        cv_observaciones = ?,
                        
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE   norm_id = ? AND
                            cv_id = ? ";

            $this->db->query($sql, array(
                        
                        $cv_resultado,
                        $cv_fecha_compromiso,
                        $cv_observaciones,
                
                        $accion_usuario,
                        $accion_fecha,
                        $norm_id,
                        $codigo_registro
                        ));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function insert_visita_registro(
                        $norm_id,
                        $cv_resultado,
                        $cv_fecha_compromiso,
                        $cv_observaciones,
                        $accion_usuario,
                        $accion_fecha
                        )
    {
        try 
        {            
            $sql = "INSERT INTO cobranza_visita (
                
                        norm_id,
                        cv_resultado,
                        cv_fecha_compromiso,
                        cv_observaciones,
                        cv_fecha,
                        accion_usuario,
                        accion_fecha)
                        
                        VALUES (
                        
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?)";

            $this->db->query($sql, array(
                        $norm_id,
                        $cv_resultado,
                        $cv_fecha_compromiso,
                        $cv_observaciones,
                        $accion_fecha,
                        $accion_usuario,
                        $accion_fecha
                        ));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function getVisitasRegistroJoin($codigo_registro, $codigo_visita, $orden_aux='ORDER BY cv_id ASC') 
    {        
        try 
        {
            $sql = "SELECT cv.cv_id, cv.norm_id, cv.cv_fecha, cv.cv_resultado, cv.cv_fecha_compromiso, cv.cv_observaciones, cv.cv_checkin, cv.cv_checkin_geo, cv.cv_checkin_fecha, c.ejecutivo_id, c.codigo_agencia_fie, c.registro_num_proceso, c.norm_estado, c.norm_fecha, c.norm_consolidado, c.norm_finalizacion, u.usuario_id, u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email
                    FROM cobranza_visita cv
                    INNER JOIN cobranza c ON c.norm_id=cv.norm_id
                    INNER JOIN ejecutivo e ON e.ejecutivo_id=c.ejecutivo_id
                    INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                    WHERE cv.norm_id=? " . ($codigo_visita!=-1 ? 'AND cv.cv_id=?' : '') . " $orden_aux ";
            
            $consulta = $this->db->query($sql, array($codigo_registro, $codigo_visita));

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function getVisitasRegistro($codigo_registro, $codigo_visita, $orden_aux='ORDER BY cv_id ASC') 
    {        
        try 
        {
            if($codigo_visita == -1)
            {
                $sql = "SELECT cv_id, norm_id, cv_fecha, cv_resultado, cv_fecha_compromiso, cv_observaciones, cv_checkin, cv_checkin_geo, cv_checkin_fecha FROM cobranza_visita WHERE norm_id=? $orden_aux "; 
            }
            else
            {
                $sql = "SELECT cv_id, norm_id, cv_fecha, cv_resultado, cv_fecha_compromiso, cv_observaciones, cv_checkin, cv_checkin_geo, cv_checkin_fecha FROM cobranza_visita WHERE norm_id=? AND cv_id=? $orden_aux "; 
            }
            
            $consulta = $this->db->query($sql, array($codigo_registro, $codigo_visita));

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function update_finalizacion_gestion(
                        $norm_finalizacion,
                        $codigo_registro,
                        $accion_usuario,
                        $accion_fecha
                        )
    {
        try 
        {            
            $sql = "UPDATE cobranza SET
                
                        norm_finalizacion = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE norm_id = ?";

            $this->db->query($sql, array(
                        $norm_finalizacion,
                        $accion_usuario,
                        $accion_fecha,
                        $codigo_registro
                        ));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function delete_norm_direccion(
                        $dir_codigo,
                        $registro_tipo,
                        $registro_codigo
                        )
    {
        try 
        {            
            $sql = "DELETE FROM registro_direccion 
                    WHERE   rd_id = ? AND
                            tipo_persona_id = ? AND 
                            codigo_registro = ? ";

            $this->db->query($sql, array(
                        $dir_codigo,
                        $registro_tipo,
                        $registro_codigo
                        ));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function insert_norm_direccion(
                        
                        $rd_tipo,
                        $rd_dir_departamento,
                        $rd_dir_provincia,
                        $rd_dir_localidad_ciudad,
                        $rd_cod_barrio,
                        $rd_direccion,
                        $rd_edificio,
                        $rd_numero,
                        $rd_trabajo_lugar,
                        $rd_trabajo_lugar_otro,
                        $rd_trabajo_realiza,
                        $rd_trabajo_realiza_otro,
                        $rd_dom_tipo,
                        $rd_dom_tipo_otro,
                        $rd_referencia_texto,
                        $rd_referencia,
                        $rd_geolocalizacion,
                        $rd_croquis,
                        $accion_usuario,
                        $accion_fecha,
                        $registro_tipo,
                        $registro_codigo
                        )
    {
        try 
        {            
            $sql = "INSERT INTO registro_direccion(
                
                        rd_tipo,
                        rd_dir_departamento,
                        rd_dir_provincia,
                        rd_dir_localidad_ciudad,
                        rd_cod_barrio,
                        rd_direccion,
                        rd_edificio,
                        rd_numero,
                        rd_trabajo_lugar,
                        rd_trabajo_lugar_otro,
                        rd_trabajo_realiza,
                        rd_trabajo_realiza_otro,
                        rd_dom_tipo,
                        rd_dom_tipo_otro,
                        rd_referencia_texto,
                        rd_referencia,
                        rd_geolocalizacion,
                        rd_croquis,
                        accion_usuario,
                        accion_fecha,
                        tipo_persona_id,
                        codigo_registro)
                        
                        VALUES(
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?
                        )";

            $this->db->query($sql, array(
                        
                        $rd_tipo,
                        $rd_dir_departamento,
                        $rd_dir_provincia,
                        $rd_dir_localidad_ciudad,
                        $rd_cod_barrio,
                        $rd_direccion,
                        $rd_edificio,
                        $rd_numero,
                        $rd_trabajo_lugar,
                        $rd_trabajo_lugar_otro,
                        $rd_trabajo_realiza,
                        $rd_trabajo_realiza_otro,
                        $rd_dom_tipo,
                        $rd_dom_tipo_otro,
                        $rd_referencia_texto,
                        $rd_referencia,
                        $rd_geolocalizacion,
                        $rd_croquis,
                        $accion_usuario,
                        $accion_fecha,
                        $registro_tipo,
                        $registro_codigo
                        ));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function update_norm_direccion(
                        
                        $rd_tipo,
                        $rd_dir_departamento,
                        $rd_dir_provincia,
                        $rd_dir_localidad_ciudad,
                        $rd_cod_barrio,
                        $rd_direccion,
                        $rd_edificio,
                        $rd_numero,
                        $rd_trabajo_lugar,
                        $rd_trabajo_lugar_otro,
                        $rd_trabajo_realiza,
                        $rd_trabajo_realiza_otro,
                        $rd_dom_tipo,
                        $rd_dom_tipo_otro,
                        $rd_referencia_texto,
                        $rd_referencia,
                        $rd_geolocalizacion,
                        $rd_croquis,

                        $accion_usuario,
                        $accion_fecha,
                        $dir_codigo,
                        $registro_tipo,
                        $registro_codigo
                        )
    {
        try 
        {            
            $sql = "UPDATE registro_direccion SET
                
                        rd_tipo = ?,
                        rd_dir_departamento = ?,
                        rd_dir_provincia = ?,
                        rd_dir_localidad_ciudad = ?,
                        rd_cod_barrio = ?,
                        rd_direccion = ?,
                        rd_edificio = ?,
                        rd_numero = ?,
                        rd_trabajo_lugar = ?,
                        rd_trabajo_lugar_otro = ?,
                        rd_trabajo_realiza = ?,
                        rd_trabajo_realiza_otro = ?,
                        rd_dom_tipo = ?,
                        rd_dom_tipo_otro = ?,
                        rd_referencia_texto = ?,
                        rd_referencia = ?,
                        rd_geolocalizacion = ?,
                        rd_croquis = ?,
                        
                        accion_usuario = ?,
                        accion_fecha = ?
                    WHERE   rd_id = ? AND
                            tipo_persona_id = ? AND
                            codigo_registro = ?";

            $this->db->query($sql, array(
                        
                        $rd_tipo,
                        $rd_dir_departamento,
                        $rd_dir_provincia,
                        $rd_dir_localidad_ciudad,
                        $rd_cod_barrio,
                        $rd_direccion,
                        $rd_edificio,
                        $rd_numero,
                        $rd_trabajo_lugar,
                        $rd_trabajo_lugar_otro,
                        $rd_trabajo_realiza,
                        $rd_trabajo_realiza_otro,
                        $rd_dom_tipo,
                        $rd_dom_tipo_otro,
                        $rd_referencia_texto,
                        $rd_referencia,
                        $rd_geolocalizacion,
                        $rd_croquis,

                        $accion_usuario,
                        $accion_fecha,
                        $dir_codigo,
                        $registro_tipo,
                        $registro_codigo,
                        ));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function checkDireccionesRegistro($codigo_registro, $codigo_tipo_registro=13) 
    {        
        try 
        {
            $sql = "SELECT rd_id FROM registro_direccion WHERE tipo_persona_id=? AND codigo_registro=? LIMIT 1 "; 

            $consulta = $this->db->query($sql, array($codigo_tipo_registro, $codigo_registro));

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function RegFinFormulario(
                        $codigo_registro,
                        $accion_usuario,
                        $accion_fecha
                        )
    {
        try 
        {            
            $sql = "UPDATE cobranza SET
                
                        norm_registro_completado_fecha = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE norm_id = ?";

            $this->db->query($sql, array(
                        $accion_fecha,
                        $accion_usuario,
                        $accion_fecha,
                        $codigo_registro
                        ));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Guardar Paso Siguiente del formulario
    function Guardar_PasoActual($siguiente_vista, $accion_usuario, $accion_fecha, $codigo_registro)
    {        
        try 
        {            
            $sql = "UPDATE cobranza SET
                
                        norm_ultimo_paso = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE norm_id = ?";

            $this->db->query($sql, array($siguiente_vista, $accion_usuario, $accion_fecha, $codigo_registro));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function getDireccionesRegistroAll($codigo_registro, $codigo_tipo_registro=13) 
    {        
        try 
        {
            $sql = "SELECT rd_id, tipo_persona_id, codigo_registro, rd_tipo, rd_dir_departamento, rd_dir_provincia, rd_dir_localidad_ciudad, rd_cod_barrio, rd_direccion, rd_edificio, rd_numero, rd_referencia_texto, rd_referencia, rd_geolocalizacion, rd_trabajo_lugar, rd_trabajo_lugar_otro, rd_trabajo_realiza, rd_trabajo_realiza_otro, rd_trabajo_actividad_pertenece, rd_dom_tipo, rd_dom_tipo_otro, rd_croquis FROM registro_direccion WHERE tipo_persona_id=? AND codigo_registro=? ORDER BY rd_id ASC "; 

            $consulta = $this->db->query($sql, array($codigo_tipo_registro, $codigo_registro));

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function getDireccionesRegistro($codigo_direccion, $codigo_registro, $codigo_tipo_registro=13) 
    {        
        try 
        {
            if($codigo_direccion == -1)
            {
                $sql = "SELECT rd_id, tipo_persona_id, codigo_registro, rd_tipo, rd_dir_departamento, rd_dir_provincia, rd_dir_localidad_ciudad, rd_cod_barrio, rd_direccion, rd_edificio, rd_numero, rd_referencia_texto, rd_referencia, rd_geolocalizacion, rd_trabajo_lugar, rd_trabajo_lugar_otro, rd_trabajo_realiza, rd_trabajo_realiza_otro, rd_trabajo_actividad_pertenece, rd_dom_tipo, rd_dom_tipo_otro, SUBSTRING(rd_croquis, 1, 5) AS rd_croquis FROM registro_direccion WHERE tipo_persona_id=? AND codigo_registro=? ORDER BY rd_id ASC "; 
            }
            else
            {
                $sql = "SELECT rd_id, tipo_persona_id, codigo_registro, rd_tipo, rd_dir_departamento, rd_dir_provincia, rd_dir_localidad_ciudad, rd_cod_barrio, rd_direccion, rd_edificio, rd_numero, rd_referencia_texto, rd_referencia, rd_geolocalizacion, rd_geolocalizacion_img, rd_croquis, rd_trabajo_lugar, rd_trabajo_lugar_otro, rd_trabajo_realiza, rd_trabajo_realiza_otro, rd_trabajo_actividad_pertenece, rd_dom_tipo, rd_dom_tipo_otro FROM registro_direccion WHERE tipo_persona_id=? AND codigo_registro=? AND rd_id=? ORDER BY rd_id ASC "; 
            }

            $consulta = $this->db->query($sql, array($codigo_tipo_registro, $codigo_registro, $codigo_direccion));

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function update_norm_datos_personales(
                        
                        $codigo_agencia_fie,
                        $registro_num_proceso,

                        $norm_primer_nombre,
                        $norm_segundo_nombre,
                        $norm_primer_apellido,
                        $norm_segundo_apellido,
                        $norm_cel,
                        $norm_actividad,
                        $norm_rel_cred,
                        $norm_rel_cred_otro,

                        $accion_usuario,
                        $accion_fecha,
                        $estructura_id
                        )
    {
        try 
        {            
            $sql = "UPDATE cobranza SET
                
                        codigo_agencia_fie = ?,
                        registro_num_proceso = ?,

                        norm_primer_nombre = ?,
                        norm_segundo_nombre = ?,
                        norm_primer_apellido = ?,
                        norm_segundo_apellido = ?,
                        norm_cel = ?,
                        norm_actividad = ?,
                        norm_rel_cred = ?,
                        norm_rel_cred_otro = ?,
                        
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE norm_id = ?";

            $this->db->query($sql, array(
                        
                        $codigo_agencia_fie,
                        $registro_num_proceso,

                        $norm_primer_nombre,
                        $norm_segundo_nombre,
                        $norm_primer_apellido,
                        $norm_segundo_apellido,
                        $norm_cel,
                        $norm_actividad,
                        $norm_rel_cred,
                        $norm_rel_cred_otro,

                        $accion_usuario,
                        $accion_fecha,
                        $estructura_id
                        ));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function NuevoNorm(
                        $ejecutivo_id,
                        $codigo_agencia_fie,
                        $registro_num_proceso,

                        $norm_primer_nombre,
                        $norm_segundo_nombre,
                        $norm_primer_apellido,
                        $norm_segundo_apellido,

                        $accion_fecha,
                        $accion_usuario
                        )
    {
        try 
        {            
            $sql = "INSERT INTO cobranza(

                        ejecutivo_id,
                        codigo_agencia_fie,
                        registro_num_proceso,

                        norm_primer_nombre,
                        norm_segundo_nombre,
                        norm_primer_apellido,
                        norm_segundo_apellido,
                        
                        norm_fecha,
                        accion_usuario,
                        accion_fecha
                        ) VALUES(

                        ?,
                        ?,
                        ?,

                        ?,
                        ?,
                        ?,
                        ?,

                        ?,
                        ?,
                        ?
                        )";

            $this->db->query($sql, array(
                        $ejecutivo_id,
                        $codigo_agencia_fie,
                        $registro_num_proceso,

                        $norm_primer_nombre,
                        $norm_segundo_nombre,
                        $norm_primer_apellido,
                        $norm_segundo_apellido,

                        $accion_fecha,
                        $accion_usuario,
                        $accion_fecha
                        ));
            
            $listaResultados = $this->db->insert_id();
            return $listaResultados;
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerTotalRegistros($codigo_ejecutivo) 
    {        
        try 
        {
            $sql = "SELECT count(norm_id) as 'total_norm' FROM cobranza WHERE norm_estado=0 AND ejecutivo_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo));

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function UpdateTransfRegistro($codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id, $codigo_registro)
    {        
        try 
        {
            $this->load->model('mfunciones_generales');
            $this->load->model('mfunciones_logica');
            
            // Se verifica el ID de Ejecutivo del usuario
            $arrVerifica = $this->mfunciones_logica->VerificarUsuarioEjecutivo($codigo_usuario);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrVerifica);
            
            $sql1 = "UPDATE cobranza SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? AND norm_id=?";
            $consulta1 = $this->db->query($sql1, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id, $codigo_registro));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function CalculoAgenciasCobrador($codigo_ejecutivo) 
    {        
        try 
        {
            $sql = "SELECT COUNT(er.estructura_regional_id) as 'contador', er.estructura_regional_nombre, er.estructura_regional_estado
                    FROM cobranza c
                    INNER JOIN estructura_regional er ON er.estructura_regional_id=c.codigo_agencia_fie
                    WHERE c.ejecutivo_id=?
                    GROUP BY er.estructura_regional_id"; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo));

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function ObtenerDetalleRegistro($codigo, $estado=-1, $lista_region=-1)
    {        
        try 
        {
            $condicion = '';
            
            if($codigo != -1)
            {
                $condicion .= ' AND c.norm_id=' . $codigo;
            }
            
            if($estado != -1)
            {
                $condicion .= ' AND c.norm_estado=' . $estado;
            }
            
            if($lista_region != -1)
            {
                $condicion .= " AND c.codigo_agencia_fie IN ($lista_region)";
            }
            
            $sql = "SELECT cv.cv_fecha, cv.cv_fecha_compromiso, cv.cv_resultado, ejecutivo.ejecutivo_perfil_tipo, usuario_email AS agente_correo, usuarios.usuario_id AS agente_codigo, CONCAT_WS(' ', usuarios.usuario_nombres, usuarios.usuario_app, usuarios.usuario_apm) AS agente_nombre, usuarios.usuario_telefono, estructura_regional_nombre, estructura_regional_departamento, estructura_regional_provincia, estructura_regional_ciudad, c.* 
                    FROM cobranza c 
                    LEFT JOIN (
                            SELECT max(cv_id) max_id, norm_id
                            FROM cobranza_visita
                            GROUP BY norm_id
                            ) aux ON aux.norm_id=c.norm_id
                    LEFT JOIN cobranza_visita cv ON cv.cv_id=aux.max_id
                    INNER JOIN ejecutivo ON ejecutivo.ejecutivo_id=c.ejecutivo_id
                    INNER JOIN usuarios ON usuarios.usuario_id=ejecutivo.usuario_id
                    INNER JOIN estructura_agencia ON estructura_agencia.estructura_agencia_id=usuarios.estructura_agencia_id INNER JOIN estructura_regional ON estructura_regional.estructura_regional_id=estructura_agencia.estructura_regional_id
                    WHERE 1 " . $condicion . " ORDER BY c.norm_fecha DESC "; 

            $consulta = $this->db->query($sql, array($codigo, $estado));

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function ObservarDocRegistro($accion_usuario, $accion_fecha, $codigo_registro)
    {        
        try 
        {
            $sql = "UPDATE cobranza SET norm_estado=0, norm_consolidado=0, norm_observado_app=1, accion_usuario=?, accion_fecha=? WHERE norm_id=? "; 
            
            $consulta = $this->db->query($sql, array($accion_usuario, $accion_fecha, $codigo_registro));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function RegInsertarObservacionDoc($registro_id, $codigo_tipo_registro, $usuario_id, $documento_id, $obs_tipo, $obs_fecha, $obs_detalle, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO registro_observacion_documento(codigo_registro, tipo_persona_id, usuario_id, documento_id, obs_tipo, obs_fecha, obs_detalle, accion_usuario, accion_fecha, obs_estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($registro_id, $codigo_tipo_registro, $usuario_id, $documento_id, $obs_tipo, $obs_fecha, $obs_detalle, $accion_usuario, $accion_fecha, 1));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerObsRegistros($codigo_registro, $estado, $codigo_tipo_registro)
    {        
        try 
        {
            $sql = "SELECT rod.obs_id, rod.codigo_registro, rod.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) AS 'usuario_nombre', rod.documento_id, d.documento_nombre, rod.obs_tipo, rod.obs_fecha, rod.obs_detalle, rod.obs_estado, rod.accion_usuario, rod.accion_fecha
                    FROM registro_observacion_documento rod
                    INNER JOIN usuarios u ON u.usuario_id=rod.usuario_id
                    INNER JOIN documento d ON d.documento_id=rod.documento_id
                    WHERE rod.codigo_registro=? AND rod.obs_estado=? AND rod.tipo_persona_id=? ORDER BY rod.obs_fecha ASC ";
            
            $consulta = $this->db->query($sql, array($codigo_registro, $estado, $codigo_tipo_registro));
            
            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function RegHistorialObservacionesDoc($codigo_registro, $codigo_tipo_registro)
    {        
        try 
        {
            $sql = "SELECT rod.obs_id, rod.codigo_registro, rod.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) AS 'usuario_nombre', rod.documento_id, d.documento_nombre, rod.obs_tipo, rod.obs_fecha, rod.obs_detalle, rod.obs_estado, rod.accion_usuario, rod.accion_fecha
                    FROM registro_observacion_documento rod
                    INNER JOIN usuarios u ON u.usuario_id=rod.usuario_id
                    INNER JOIN documento d ON d.documento_id=rod.documento_id
                    WHERE rod.codigo_registro=? AND rod.tipo_persona_id=? ORDER BY rod.obs_fecha ASC ";
            
            $consulta = $this->db->query($sql, array($codigo_registro, $codigo_tipo_registro));
            
            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function RegObtenerDocumentoDigitalizar($codigo_registro, $codigo_documento, $codigo_tipo_registro=13)
    {        
        try 
        {
            $sql = "SELECT rd.registro_documento_id, rd.registro_documento_pdf, CONCAT('reg_', c.norm_id) as 'prospecto_carpeta', rd.accion_fecha
                    FROM registro_documento rd
                    INNER JOIN cobranza c ON c.norm_id=rd.codigo_registro
                    WHERE c.norm_id=? AND rd.registro_documento_id=? AND rd.tipo_persona_id=? ORDER BY rd.registro_documento_id DESC LIMIT 1 ";
            
            $consulta = $this->db->query($sql, array($codigo_registro, $codigo_documento, $codigo_tipo_registro));

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

                return $listaResultados;
    }
    
    function RegListaDocumentosDigitalizar($codigo_registro, $codigo_documento, $codigo_tipo_registro=6)
    {        
        try 
        {
            $sql = "SELECT rd.registro_documento_id, rd.registro_documento_pdf, CONCAT('reg_', c.norm_id) as 'prospecto_carpeta', rd.accion_fecha
                    FROM registro_documento rd
                    INNER JOIN cobranza c ON c.norm_id=rd.codigo_registro
                    WHERE c.norm_id=? AND rd.documento_id=? AND rd.tipo_persona_id=? ORDER BY rd.registro_documento_id DESC ";

            $consulta = $this->db->query($sql, array($codigo_registro, $codigo_documento, $codigo_tipo_registro));

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

                return $listaResultados;
    }
    
    function VerificaDocumentosRegistroDigitalizar($codigo_registro, $codigo_documento, $codigo_tipo_registro=13)
    {        
        try 
        {
            $sql = "SELECT rd.registro_documento_pdf, CONCAT('reg_', c.norm_id) as 'solicitud_carpeta' 
                    FROM registro_documento rd
                    INNER JOIN cobranza c ON c.norm_id=rd.codigo_registro
                    WHERE rd.codigo_registro=? AND rd.documento_id=? AND rd.tipo_persona_id=? ORDER BY rd.registro_documento_id DESC LIMIT 1";

            $consulta = $this->db->query($sql, array($codigo_registro, $codigo_documento, $codigo_tipo_registro));

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

        return $listaResultados;
    }
    
    function InsertarDocumentoRegistro($codigo_registro, $documento_id, $prospecto_documento_pdf, $accion_usuario, $accion_fecha, $codigo_tipo_registro)
    {        
        try 
        {
            $sql = "INSERT INTO registro_documento(codigo_registro, documento_id, registro_documento_pdf, accion_usuario, accion_fecha, tipo_persona_id) VALUES (?, ?, ?, ?, ?, ?) ";

            $consulta = $this->db->query($sql, array($codigo_registro, $documento_id, $prospecto_documento_pdf, $accion_usuario, $accion_fecha, $codigo_tipo_registro));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function RegistroVerDocumentoObservado($codigo_registro, $codigo_documento, $codigo_tipo_registro)
    {        
        try 
        {
            $sql = "SELECT obs_id FROM registro_observacion_documento WHERE codigo_registro=? AND documento_id=? AND tipo_persona_id=? AND obs_estado=1 "; 

            $consulta = $this->db->query($sql, array($codigo_registro, $codigo_documento, $codigo_tipo_registro));

            $listaResultados = $consulta->result_array();            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function ObtenerBandejaCobranzasEjecutivoAll($codigo_ejecutivo)
    {        
        try
        {
            $sql = "SELECT c.norm_id, ejecutivo_id, c.norm_estado, c.norm_fecha, c.norm_consolidado, c.norm_consolidado_fecha, c.norm_registro_completado_fecha, c.norm_observado_app, c.norm_primer_nombre, c.norm_segundo_nombre, c.norm_primer_apellido, c.norm_segundo_apellido, c.norm_cel, c.registro_num_proceso, c.norm_finalizacion, camp.camp_id, camp.camp_nombre, et.etapa_nombre, et.etapa_color, cv.cv_fecha, cv.cv_fecha_compromiso
                    FROM cobranza c
                    LEFT JOIN (
                            SELECT max(cv_id) max_id, norm_id
                            FROM cobranza_visita
                            GROUP BY norm_id
                            ) aux ON aux.norm_id=c.norm_id
                    LEFT JOIN cobranza_visita cv ON cv.cv_id=aux.max_id
                    INNER JOIN campana camp ON camp.camp_id=13
                    INNER JOIN etapa et ON et.etapa_id = CASE c.norm_estado WHEN 0 THEN 25 WHEN 1 THEN 26 END
                    WHERE c.ejecutivo_id=? ORDER BY c.norm_fecha DESC "; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo));

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function ObtenerBandejaCobranzasEjecutivo($codigo_ejecutivo, $estado=0)
    {        
        try
        {
            $sql = "SELECT c.norm_id, ejecutivo_id, c.norm_estado, c.norm_fecha, c.norm_consolidado, c.norm_consolidado_fecha, c.norm_registro_completado_fecha, c.norm_observado_app, c.norm_primer_nombre, c.norm_segundo_nombre, c.norm_primer_apellido, c.norm_segundo_apellido, c.norm_cel, c.registro_num_proceso, camp.camp_id, camp.camp_nombre, et.etapa_nombre, et.etapa_color, cv.cv_fecha, cv.cv_fecha_compromiso
                    FROM cobranza c
					LEFT JOIN (
                            SELECT max(cv_id) max_id, norm_id
                            FROM cobranza_visita
                            GROUP BY norm_id
                            ) aux ON aux.norm_id=c.norm_id
                    LEFT JOIN cobranza_visita cv ON cv.cv_id=aux.max_id
                    INNER JOIN campana camp ON camp.camp_id=13
                    INNER JOIN etapa et ON et.etapa_id = CASE c.norm_estado WHEN 0 THEN 25 WHEN 1 THEN 26 END
                    WHERE c.norm_consolidado=0 AND c.norm_estado=? AND c.ejecutivo_id=? ORDER BY c.norm_fecha DESC "; 

            $consulta = $this->db->query($sql, array($estado, $codigo_ejecutivo));

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function VerificaNormConsolidado($codigo_registro) 
    {        
        try 
        {
            $sql = "SELECT norm_id, ejecutivo_id, codigo_agencia_fie, registro_num_proceso, norm_estado, norm_consolidado, norm_observado_app, norm_primer_nombre, norm_segundo_nombre, norm_primer_apellido, norm_segundo_apellido, norm_rel_cred FROM cobranza WHERE norm_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_registro));

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
/*************** DATABASE - FIN ****************************/

}
    
?>