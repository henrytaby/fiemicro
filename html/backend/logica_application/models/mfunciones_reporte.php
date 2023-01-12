<?php
class Mfunciones_reporte extends CI_Model {

    const MAXIMA_RECURSION = 50;
    const CANTIDAD_TOP_DOCUMENTOS = 100;

    function __construct() {
        parent::__construct();
        $CI = & get_instance();
    }


    function Generar_Consulta($parametros) {
        $this->load->model('mfunciones_generales');
        // Se crea el objeto de resultado a devolver
        $resultado = new stdClass();
        $resultado->sin_registros = true;
        $resultado->error = false;
        $resultado->filas = array();
        $resultado->mensaje_error = "";
        $resultado->agrupar_filas = false;
        $resultado->tipo_dato = "decimal";
        $filtros = array();

        if (is_null($parametros) || !is_object($parametros)) return $resultado;

        // Parametros de la consulta
        $p_hito_finalizado = 0;
        $p_campos_grupo = property_exists($parametros,"campos_grupo")?$parametros->campos_grupo:"prospecto_id";
        $p_funcion_mostrar = property_exists($parametros,"funcion_mostrar")?$parametros->funcion_mostrar:"total";
        if (empty($p_campos_grupo) || $p_campos_grupo == "-1") {
            $p_campos_grupo = "prospecto_id";
        }
        if (empty($p_funcion_mostrar) || $p_funcion_mostrar == "-1") {
            $resultado->error = true;
            $resultado->mensaje_error = "Debe seleccionar una opción del dato a mostrar";
            return $resultado;
        }
        foreach ($parametros->filtros as $f) {
            $datos_filtro = $this->Obtener_Tabla_Campo_Filtro($f->campo);
            if ($datos_filtro == null) continue;
            $f->tabla = $datos_filtro->tabla;
            $f->campo_sql = $datos_filtro->campo;
            $filtros[] = $f;
        }


        $p_mostrar_etapas_sin_datos = 1;

        if ($p_campos_grupo != "prospecto_id") {
            $resultado->agrupar_filas = true;
        }
        // se crea una variable temporal para almacenar el total de las etapas
        $array_etapas = array();

        $campos_grupos = array();

        $tablas_adicionales = array();
        // Se buscan las definiciones de los campos a agrupar, y las tablas utilizadas en dicha agrupación
        $this->Obtener_Campos_Grupo($p_campos_grupo,$campos_grupos,$tablas_adicionales);

        // Se agregan las tablas relacionadas con los campos de filtro
        foreach ($filtros as $filtro) {
            $tablas_adicionales[] = $filtro->tabla;
        }

        // Se unifican las tablas adicionales (eliminando los duplicados)
        $tablas_adicionales = array_unique($tablas_adicionales);

        // Se definen los campos a devolver por la consulta. Considerando los predeterminados y los usados en los grupos
        $lista_campos = explode("|","hito_fecha_ini as hito_fecha_inicio|IFNULL(hito_fecha_fin, CURRENT_TIMESTAMP) as hito_fecha_fin|hito.prospecto_id as prospecto_id|hito.etapa_id as etapa_id");
        foreach ($campos_grupos as $campo_grupo) {
            $campo = $campo_grupo->campo_sql." as ".$campo_grupo->alias_sql;
            if (array_search($campo, $lista_campos)=== false) $lista_campos[] = $campo;
            if (empty($campo_grupo->campo_sql_descripcion)) continue;
            $campo = $campo_grupo->campo_sql_descripcion." as ".$campo_grupo->alias_sql_descripcion;
            if (array_search($campo, $lista_campos)=== false) $lista_campos[] = $campo;
        }

        // Se construye la clausula where según los filtros
        $where = array("(hito.hito_finalizo >= $p_hito_finalizado)");

        $sw_pivot = 0;
        
        foreach ($filtros as $filtro) {
            
            if($filtro->campo_sql == 'sol_estudio') { $sw_solcred = 1; $sw_solcred_valor = implode(",",array_map(function ($i) {return $this->db->escape($i);},$filtro->valores));continue; }
            
            switch ($filtro->tipo) {
                case "texto":
                    $where[] = (strpos($filtro->campo_sql, "concat_ws") === false)?"(".$filtro->tabla.".".$filtro->campo_sql." like '%".$this->db->escape_str($filtro->valor)."%')"
                                                                                          :"(".$filtro->campo_sql." like '%".$this->db->escape_str($filtro->valor)."%')";
                    break;
                case "id":
                    $where[] = "(".$filtro->tabla.".".$filtro->campo_sql." = ".$this->db->escape($filtro->valor).")";
                    break;
                case "booleano":
                case "lista":
                    $where[] = "(".$filtro->tabla.".".$filtro->campo_sql." in (".implode(",",array_map(function ($i) {return $this->db->escape($i);},$filtro->valores))."))";
                    break;
                case "fecha":
                    if ($filtro->tabla == "calendario") {
                        $sql_where_fecha ="";
                        if (!empty($filtro->desde)) {
                            $fecha_desde = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->desde . ' 00:00:01');
                            $sql_where_fecha = " and (".$filtro->tabla.".".$filtro->campo_sql." >= '$fecha_desde')";
                        }
                        if (!empty($filtro->hasta)) {
                            $fecha_hasta = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->hasta . ' 23:59:59');
                            $sql_where_fecha = " and (".$filtro->tabla.".".$filtro->campo_sql." <= '$fecha_hasta')";
                        }
                        $where[] = "((select count(*) from calendario where calendario.cal_id_visita = prospecto.prospecto_id and calendario.cal_tipo_visita = 1$sql_where_fecha) > 0)";
                    } else {
                        if (!empty($filtro->desde)) {
                            $fecha_desde = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->desde . ' 00:00:01');
                            $where[] = "(".$filtro->tabla.".".$filtro->campo_sql." >= '$fecha_desde')";
                        }
                        if (!empty($filtro->hasta)) {
                            $fecha_hasta = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->hasta . ' 23:59:59');
                            $where[] = "(".$filtro->tabla.".".$filtro->campo_sql." <= '$fecha_hasta')";
                        }
                    }
                    break;
            }
            
            if($filtro->campo_sql == 'prospecto_fecha_asignacion') { $sw_pivot = 1; }
        }
        
        if(empty($parametros->filtros))
        {
            $resultado->error = true;
            $resultado->mensaje_error = "El Reporte debe contar con filtros agregados, por favor debe Agregar mínimamente el Filtro 'Fecha de Asignación' con un rango de tiempo más certero.";
            return $resultado;
        }
        else
        {
            if($sw_pivot == 0)
            {
                $resultado->error = true;
                $resultado->mensaje_error = "Por favor debe Agregar el Filtro 'Fecha de Asignación' y un rango de tiempo más certero.";
                return $resultado;
            }
        }
        

//        .$this->db->escape($title).

        // Nuevo Requerimiento - Quitar Etapa Pre-Afiliación y Excepciones
        
        $where[] = "e.etapa_categoria IN (1, 2, 3, 5)";
        
        if($sw_solcred == 1)
        {
            $where[] = "prospecto.prospecto_id " . ($sw_solcred_valor == "'0'" ? "NOT" : "") . " IN (select sol_estudio_codigo from solicitud_credito WHERE sol_estudio=1)";
        }
        
        $sql_where = join(" AND ",$where);

        // Se crea el string sql de los campos de la consulta
        $sql_campos = implode(", ",$lista_campos);

        // Se obtiene el string de joins para la consulta sql
        $sql_uniones = $this->Obtener_Uniones($tablas_adicionales,"hito");

        // Se define el filtro sql
        $sql_filtro = "where p.general_categoria=1 AND $sql_where";

            // Verifica si tiene el permiso para ver todo, caso contrario sólo la región del usuario        
            $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
            $filtro_region = "INNER JOIN prospecto p ON p.prospecto_id = hito.prospecto_id INNER JOIN estructura_regional er ON er.estructura_regional_id = p.codigo_agencia_fie AND(er.estructura_regional_id IN($lista_region->region_id)) INNER JOIN estructura_agencia ea ON ea.estructura_regional_id=er.estructura_regional_id ";
        
        // Se define la porción FROM del sql
        $sql_tablas = "hito$sql_uniones $filtro_region inner join etapa e ON e.etapa_id=hito.etapa_id";

        // Se genera el SQL resultante
        $sql = "select $sql_campos from $sql_tablas $sql_filtro";
        
        // Se ejecuta la consulta SQL
        $filas = array(); // Ordenadas por id_prospecto
        try {
            $consulta = $this->db->query($sql);
            $filas_consultadas = $consulta->result();
            if (count($filas_consultadas)>0) $resultado->sin_registros = false;
        } catch (Exception $ex) {
            $resultado->error = true;
            $resultado->mensaje_error = "Ocurrio un evento inesperado, intentelo mas tarde.";
        }

        if ($resultado->sin_registros) return $resultado;

        // Se obtiene el listado de etapas de la BD para generar el arbol y ordenarlas
        $etapas_consultadas = array();
        try {
            $sql_etapas = "SELECT etapa_id, etapa_depende, etapa_nombre, etapa_detalle, etapa_categoria, etapa_tiempo FROM etapa where etapa_categoria BETWEEN 0 and 5 ORDER BY etapa_categoria, etapa_id";
            $consulta = $this->db->query($sql_etapas);
            $etapas_consultadas = $consulta->result();
            if (count($etapas_consultadas)>0) {
                foreach ($etapas_consultadas as &$etapa_db) {
                    $etapa_db->nivel = $this->Buscar_Nivel_Etapa($etapa_db,$etapas_consultadas,1);
                }
            } else {
                $resultado->error = true;
                $resultado->mensaje_error = "No hay etapas registradas.";
            }

            // Se ordenan las etapsa por categoria, nivel y luego nombre
            uasort($etapas_consultadas, function ($a, $b) {
                if ($a->etapa_categoria == $b->etapa_categoria) {
                    if ($a->nivel == $b->nivel) {
                        return strcmp($a->etapa_nombre,$b->etapa_nombre);
                    }
                    return ($a->nivel < $b->nivel)?-1:1;
                }
                return ($a->etapa_categoria < $b->etapa_categoria)?-1:1;
            });

            // Se generan las columnas de las etapas a partir del resultado
            foreach ($etapas_consultadas as $etapa ) {
                $total_etapa = new stdClass();
                $total_etapa->titulo = $etapa->etapa_nombre;
                $total_etapa->grupo = $this->Obtener_Nombre_Categoria($etapa->etapa_categoria);
                $total_etapa->descripcion = $etapa->etapa_detalle;
                $total_etapa->tiempo_limite = $etapa->etapa_tiempo;
                $total_etapa->registros = 0;
                $total_etapa->horas = 0;
                $array_etapas[$etapa->etapa_id] = $total_etapa;
            }
        } catch (Exception $ex) {
            $resultado->error = true;
            $resultado->mensaje_error = "Ocurrio un evento inesperado, intentelo mas tarde.";
        }

        // Si hubo un error o no hubo resultado salir inmediatamente
        if ($resultado->error || $resultado->sin_registros) return $resultado;

        // se guarda en el resultado el total de filas consultadas
        $resultado->cuenta = count($filas_consultadas);

        // Se genera el resumen y agrupado con las filas obtenidas
        foreach ($filas_consultadas as $fila_db) {
            // Se calcula el hash de grupo para el hito leido
            $id_grupo = "k".$this->Calcula_Id_Grupo($fila_db, $campos_grupos);
            // Se calculan las horas para el hito leido
            $horas_calculadas = $this->Calcula_Horas_Laborales($fila_db->hito_fecha_inicio,$fila_db->hito_fecha_fin);
            if (!array_key_exists($id_grupo, $filas)) {
                $fila = new stdClass();
                $fila->id_grupo = $id_grupo;
                $fila->registros = 1;
                foreach ($campos_grupos as $campo_grupo) {
                    $fila->{"campo_grupo_".$campo_grupo->alias_sql} = (empty($campo_grupo->alias_sql_descripcion))?$fila_db->{$campo_grupo->alias_sql}:$fila_db->{$campo_grupo->alias_sql_descripcion};
                }
                $datos_etapa = new stdClass();
                $datos_etapa->horas = $horas_calculadas;
                $datos_etapa->registros = 1;
                $fila->etapas = array($fila_db->etapa_id=>$datos_etapa);
            } else {
                $fila = $filas[$id_grupo];
                $fila->registros += 1;
                if (!array_key_exists($fila_db->etapa_id,$fila->etapas)) {
                    $datos_etapa = new stdClass();
                    $datos_etapa->horas = $horas_calculadas;
                    $datos_etapa->registros = 1;
                }
                else {
                    $datos_etapa = $fila->etapas[$fila_db->etapa_id];
                    $datos_etapa->horas += $horas_calculadas;
                    $datos_etapa->registros += 1;
                }
                $fila->etapas[$fila_db->etapa_id] = $datos_etapa;
            }
            $filas[$id_grupo] = $fila;

            if (!array_key_exists($fila_db->etapa_id,$array_etapas)) {
                $total_etapa = new stdClass();
                $total_etapa->titulo = "#".$fila_db->etapa_id;
                $total_etapa->grupo = "";
                $total_etapa->descripcion = "No encontrada en tabla de etapas";
                $total_etapa->registros = 1;
                $total_etapa->tiempo_limite = 0;
                $total_etapa->horas = $horas_calculadas;
            } else {
                $total_etapa = $array_etapas[$fila_db->etapa_id];
                $total_etapa->registros += 1;
                $total_etapa->horas += $horas_calculadas;
            }
            $array_etapas[$fila_db->etapa_id] = $total_etapa;
        }

        // TODO: obtener titulos de etapas y ordenarlas
        // Se define la función para mostrar la información en la tabla
//        $resultado->funcion_mostrar = function ($etapas,$clave) { return (isset($etapas[$clave]))? $etapas[$clave]->registros:"-";};
        switch ($p_funcion_mostrar) {
            case "promedio":
                $resultado->funcion_mostrar = function ($etapas,$clave) { return (isset($etapas[$clave]))? number_format ($etapas[$clave]->horas / ($etapas[$clave]->registros),2):"";};
                break;
            case "registros":
                $resultado->funcion_mostrar = function ($etapas,$clave) { return (isset($etapas[$clave]))? $etapas[$clave]->registros:"";};
                $resultado->tipo_dato = "entero";
                break;
            default:
                $resultado->funcion_mostrar = function ($etapas,$clave) { return (isset($etapas[$clave]))? $etapas[$clave]->horas:"";};
                break;
        }
        $resultado->columnas = ($p_mostrar_etapas_sin_datos==0)?array_filter($array_etapas,function ($e) {return $e->registros>0;}):$array_etapas;
        $resultado->filas = $filas;
        $resultado->columnas_grupo = $campos_grupos;

        // Se calculan los tiempos utilizados para cada fila según la etapa
        $resultado->tiene_detalles = ($p_campos_grupo == "prospecto_id");
        if ($resultado->tiene_detalles) {
            $resultado->prospectos_a_tiempo=0;
            $resultado->prospectos_pendientes=0;
            $resultado->prospectos_atrasados=0;
            foreach ($resultado->filas as &$fila) {
                foreach ($fila->etapas as $id_etapa=>&$datos) {
                    $limite = $resultado->columnas[$id_etapa]->tiempo_limite;
                    $datos->bandera_a_tiempo = ($limite>0 && (1-($datos->horas/$limite)) > .5);
                    $datos->bandera_pendiente = ($limite>0 && (1-($datos->horas/$limite)) >= 0) && !$datos->bandera_a_tiempo;
                    $datos->bandera_atrasado = ($limite>0 && (1-($datos->horas/$limite)) < 0);
                    $resultado->prospectos_a_tiempo+=$datos->bandera_a_tiempo?1:0;
                    $resultado->prospectos_pendientes+=$datos->bandera_pendiente?1:0;
                    $resultado->prospectos_atrasados+=$datos->bandera_atrasado?1:0;
                }
            }
        }
        return $resultado;
    }

    function getFecha_valor($valor)
    {
        $resultado = 0;
        
        switch ($valor) {
            case 1:

                $resultado = "prospecto_fecha_asignacion";
                
                break;

            case 2:

                $resultado = "prospecto_checkin_fecha";
                
                break;
            
            case 3:

                $resultado = "prospecto_consolidar_fecha";
                
                break;
            
            case 4:

                $resultado = "prospecto_rechazado_fecha";
                
                break;
            
            case 5:

                $resultado = "prospecto_aceptado_afiliado_fecha";
                
                break;
            
            case 6:

                $resultado = "prospecto_entrega_servicio_fecha";
                
                break;
            
            default:
                
                $resultado = "prospecto_fecha_asignacion";
                
                break;
        }
        
        return $resultado;
        
    }
    
    function getFecha_valor_des($valor)
    {
        $resultado = 0;
        
        switch ($valor) {
            case 1:

                $resultado = "P1. Fecha de Asignación";
                
                break;

            case 2:

                $resultado = "P2. Fecha de Check-In";
                
                break;
            
            case 3:

                $resultado = "P3. Fecha de Consolidación Prospecto";
                
                break;
            
            case 4:

                $resultado = "P4. Fecha de Rechazo del Prospecto";
                
                break;
            
            case 5:

                $resultado = "P5. Fecha de Aceptación del Prospecto";
                
                break;
            
            case 6:

                $resultado = "P6. Fecha de Entrega de Servicio";
                
                break;
            
            default:
                
                $resultado = "P1. Fecha de Asignación";
                
                break;
        }
        
        return $resultado;
        
    }
    
    function Generar_Consulta_Documentos($parametros) {
        // Se crea el objeto de resultado a devolver
        $resultado = new stdClass();
        $resultado->sin_registros = true;
        $resultado->error = false;
        $resultado->filas = array();
        $resultado->mensaje_error = "";
        $filtros = array();

        if (is_null($parametros) || !is_object($parametros)) return $resultado;

        // Parametros de la consulta
        foreach ($parametros->filtros as $f) {
            $datos_filtro = $this->Obtener_Tabla_Campo_Filtro($f->campo);
            if ($datos_filtro == null) continue;
            $f->tabla = $datos_filtro->tabla;
            $f->campo_sql = $datos_filtro->campo;
            $filtros[] = $f;
        }

        // se crea una variable temporal para almacenar el total de las etapas
        // Se buscan las definiciones de los campos a agrupar, y las tablas utilizadas en dicha agrupación

        $tablas_adicionales = array("");

        // Se agregan las tablas relacionadas con los campos de filtro
        foreach ($filtros as $filtro) {
            $tablas_adicionales[] = $filtro->tabla;
        }

        // Se unifican las tablas adicionales (eliminando los duplicados)
        $tablas_adicionales = array_unique($tablas_adicionales);

        // Se definen los campos a devolver por la consulta. Considerando los predeterminados y los usados en los grupos (JRAD CONSULTA)
        $lista_campos = explode("|","count(observacion_documento.obs_id) as observacion_cantidad|MAX(observacion_documento.obs_fecha) as observacion_fecha|max(observacion_documento.obs_id) as observacion_id|documento.documento_id as documento_id|MAX(observacion_documento.prospecto_id) as prospecto_id");

        // Se construye la clausula where según los filtros (JRAD CONSULTA)
        $where = array("(observacion_documento.obs_estado != -1)");

        foreach ($filtros as $filtro) {
            switch ($filtro->tipo) {
                case "texto":
                    $where[] = (strpos($filtro->campo_sql, "concat_ws") === false)?"(".$filtro->tabla.".".$filtro->campo_sql." like '%".$this->db->escape_str($filtro->valor)."%')"
                        :"(".$filtro->campo_sql." like '%".$this->db->escape_str($filtro->valor)."%')";
                    break;
                case "id":
                    $where[] = "(".$filtro->tabla.".".$filtro->campo_sql." = ".$this->db->escape($filtro->valor).")";
                    break;
                case "booleano":
                case "lista":
                    $where[] = "(".$filtro->tabla.".".$filtro->campo_sql." in (".implode(",",array_map(function ($i) {return $this->db->escape($i);},$filtro->valores))."))";
                    break;
                case "fecha":
                    if ($filtro->tabla == "calendario") {
                        $sql_where_fecha ="";
                        if (!empty($filtro->desde)) {
                            $fecha_desde = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->desde . ' 00:00:01');
                            $sql_where_fecha = " and (".$filtro->tabla.".".$filtro->campo_sql." >= '$fecha_desde')";
                        }
                        if (!empty($filtro->hasta)) {
                            $fecha_hasta = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->hasta . ' 23:59:59');
                            $sql_where_fecha = " and (".$filtro->tabla.".".$filtro->campo_sql." <= '$fecha_hasta')";
                        }
                        $where[] = "((select count(*) from calendario where calendario.cal_id_visita = prospecto.prospecto_id and calendario.cal_tipo_visita = 1$sql_where_fecha) > 0)";
                    } else {
                        if (!empty($filtro->desde)) {
                            $fecha_desde = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->desde . ' 00:00:01');
                            $where[] = "(" . $filtro->tabla . "." . $filtro->campo_sql . " >= '$fecha_desde')";
                        }
                        if (!empty($filtro->hasta)) {
                            $fecha_hasta = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->hasta . ' 23:59:59');
                            $where[] = "(" . $filtro->tabla . "." . $filtro->campo_sql . " <= '$fecha_hasta')";
                        }
                    }
                    break;
            }
        }

        $sql_where = join(" AND ",$where);

        // Se crea el string sql de los campos de la consulta
        $sql_campos = implode(", ",$lista_campos);

        // Se obtiene el string de joins para la consulta sql
        $sql_uniones = $this->Obtener_Uniones_Documento($tablas_adicionales,"observacion_documento");

        // Se define el filtro sql
        $sql_filtro = "where p.onboarding=0 AND $sql_where";

            // Verifica si tiene el permiso para ver todo, caso contrario sólo la región del usuario        
            $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
            $filtro_region = "inner join prospecto p ON p.prospecto_id=observacion_documento.prospecto_id inner join ejecutivo ej on ej.ejecutivo_id = p.ejecutivo_id inner join usuarios u on u.usuario_id = ej.usuario_id inner join estructura_agencia ea on ea.estructura_agencia_id = u.estructura_agencia_id inner join estructura_regional er on er.estructura_regional_id = ea.estructura_regional_id AND (er.estructura_regional_id in ($lista_region->region_id)) ";
        
        // Se define la porción FROM del sql (JRAD CONSULTA)
        $sql_tablas = "observacion_documento inner join documento on observacion_documento.documento_id = documento.documento_id$sql_uniones $filtro_region";

        // Se genera el SQL resultante							(JRAD CONSULTA)
        $sql = "select $sql_campos from $sql_tablas $sql_filtro group by documento.documento_id order by observacion_cantidad desc limit ".Mfunciones_reporte::CANTIDAD_TOP_DOCUMENTOS;

        // Se ejecuta la consulta SQL
        try {
            $consulta = $this->db->query($sql);
            $filas_consultadas = $consulta->result();
            if (count($filas_consultadas)>0) $resultado->sin_registros = false;
        } catch (Exception $ex) {
            $resultado->error = true;
            $resultado->mensaje_error = "Ocurrio un evento inesperado, intentelo mas tarde.";
        }

        if ($resultado->sin_registros) return $resultado;

        // Se buscan los datos adicionales de los documentos (JRAD CONSULTA)
        try {
            $id_documentos = implode(",",array_map(function ($i) {return $i->documento_id;},$filas_consultadas));
            
            $consulta = $this->db->query("select documento.documento_id as documento_id,documento.documento_nombre as documento_nombre,concat_ws(' ',usuarios.usuario_nombres,usuarios.usuario_app,usuarios.usuario_apm) as ejecutivo_nombre, observacion_documento.prospecto_id, estructura_regional.estructura_regional_nombre from documento inner join observacion_documento on observacion_documento.documento_id = documento.documento_id inner join prospecto on prospecto.prospecto_id = observacion_documento.prospecto_id inner join ejecutivo on ejecutivo.ejecutivo_id = prospecto.ejecutivo_id inner join usuarios on usuarios.usuario_id = ejecutivo.usuario_id inner join estructura_agencia on estructura_agencia.estructura_agencia_id=usuarios.estructura_agencia_id inner join estructura_regional on estructura_regional.estructura_regional_id=estructura_agencia.estructura_regional_id where documento.documento_id in ($id_documentos)");
            $filas_datos_adicionales =$consulta->result();
            // Se unen los datos adicionales
            foreach ($filas_consultadas as &$fila) {
                $fila->documento_nombre = "";
                $fila->ejecutivo_nombre = "";
                $fila->ciudad = "";
                foreach ($filas_datos_adicionales as $adicional) {
					
                            // Siempre se verifica que tenga coincidencia (JRAD CONSULTA)
                    if ($adicional->documento_id != $fila->documento_id) continue;
                    if ($adicional->prospecto_id != $fila->prospecto_id) continue;
                    $fila->documento_nombre = $adicional->documento_nombre;
                    $fila->ejecutivo_nombre = $adicional->ejecutivo_nombre;
                    $fila->estructura_regional_nombre = $adicional->estructura_regional_nombre;
                }
            }
        } catch (Exception $ex) {
            $resultado->error = true;
            $resultado->mensaje_error = "Ocurrio un evento inesperado, intentelo mas tarde.";
        }

        // se guarda en el resultado el total de filas consultadas
        $resultado->cuenta = count($filas_consultadas);
        $resultado->filas = $filas_consultadas;

        return $resultado;
    }
    
    function Generar_Consulta_Agencia($parametros, $tipo_reporte='') {
        // Se crea el objeto de resultado a devolver
        $resultado = new stdClass();
        $resultado->sin_registros = true;
        $resultado->error = false;
        $resultado->filas = array();
        $resultado->mensaje_error = "";
        $filtros = array();
        
        if (is_null($parametros) || !is_object($parametros)) return $resultado;
        
        // Parametros de la consulta
        foreach ($parametros->filtros as $f) {
            $datos_filtro = $this->Obtener_Tabla_Campo_Filtro($f->campo);
            if ($datos_filtro == null) continue;
            $f->tabla = $datos_filtro->tabla;
            $f->campo_sql = $datos_filtro->campo;
            $filtros[] = $f;
        }

        // se crea una variable temporal para almacenar el total de las etapas
        // Se buscan las definiciones de los campos a agrupar, y las tablas utilizadas en dicha agrupación

        $tablas_adicionales = array("");

        // Se agregan las tablas relacionadas con los campos de filtro
        foreach ($filtros as $filtro) {
            $tablas_adicionales[] = $filtro->tabla;
        }

        // Se unifican las tablas adicionales (eliminando los duplicados)
        $tablas_adicionales = array_unique($tablas_adicionales);

        // Se definen los campos a devolver por la consulta. Considerando los predeterminados y los usados en los grupos (JRAD CONSULTA)
        $lista_campos = explode("|","terceros.terceros_id as terceros_id");

        // Se construye la clausula where según los filtros (JRAD CONSULTA)
        $where = array("1");
        $where = array("(terceros_estado IN (6, 7, 8))");

        $sw_pivot = 0;
        
        foreach ($filtros as $filtro) {
            switch ($filtro->tipo) {
                case "texto":
                    $where[] = (strpos($filtro->campo_sql, "concat_ws") === false)?"(".$filtro->tabla.".".$filtro->campo_sql." like '%".$this->db->escape_str($filtro->valor)."%')"
                        :"(".$filtro->campo_sql." like '%".$this->db->escape_str($filtro->valor)."%')";
                    break;
                case "id":
                    $where[] = "(".$filtro->tabla.".".$filtro->campo_sql." = ".$this->db->escape($filtro->valor).")";
                    break;
                case "booleano":
                case "lista":
                    $where[] = "(".$filtro->tabla.".".$filtro->campo_sql." in (".implode(",",array_map(function ($i) {return $this->db->escape($i);},$filtro->valores))."))";
                    break;
                case "fecha":
                    if ($filtro->tabla == "calendario") {
                        $sql_where_fecha ="";
                        if (!empty($filtro->desde)) {
                            $fecha_desde = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->desde . ' 00:00:01');
                            $sql_where_fecha = " and (".$filtro->tabla.".".$filtro->campo_sql." >= '$fecha_desde')";
                        }
                        if (!empty($filtro->hasta)) {
                            $fecha_hasta = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->hasta . ' 23:59:59');
                            $sql_where_fecha = " and (".$filtro->tabla.".".$filtro->campo_sql." <= '$fecha_hasta')";
                        }
                        $where[] = "((select count(*) from calendario where calendario.cal_id_visita = prospecto.prospecto_id and calendario.cal_tipo_visita = 1$sql_where_fecha) > 0)";
                    } else {
                        if (!empty($filtro->desde)) {
                            $fecha_desde = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->desde . ' 00:00:01');
                            $where[] = "(" . $filtro->tabla . "." . $filtro->campo_sql . " >= '$fecha_desde')";
                        }
                        if (!empty($filtro->hasta)) {
                            $fecha_hasta = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->hasta . ' 23:59:59');
                            $where[] = "(" . $filtro->tabla . "." . $filtro->campo_sql . " <= '$fecha_hasta')";
                        }
                    }
                    break;
            }
            
            if($filtro->campo_sql == 'completado_fecha') { $sw_pivot = 1; }
            
        }

        if($sw_pivot == 0)
        {
            $resultado->error = true;
            $resultado->mensaje_error = "Por favor Agregar el Filtro 'Fecha de proceso (" . $this->mfunciones_generales->GetValorCatalogo(5, 'terceros_estado') . ")'.";
            return $resultado;
        }
        
        $sql_where = join(" AND ",$where);

        // Se crea el string sql de los campos de la consulta
        $sql_campos = implode(", ",$lista_campos);

        // Se obtiene el string de joins para la consulta sql
        $sql_uniones = $this->Obtener_Uniones_Prospecto($tablas_adicionales,"terceros");

            // Verifica si tiene el permiso para ver todo, caso contrario sólo la región del usuario        
            $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        
        // Se define el filtro sql
        $sql_filtro = "where codigo_agencia_fie IN ($lista_region->region_id) AND $sql_where";
        
        // Se define la porción FROM del sql (JRAD CONSULTA)
        $sql_tablas = "terceros $sql_uniones";

        // Se genera el SQL resultante							(JRAD CONSULTA)
        $sql = "select $sql_campos from $sql_tablas $sql_filtro order by terceros_id ASC ";

        // Se ejecuta la consulta SQL
        try {
            $consulta = $this->db->query($sql);
            $filas_consultadas = $consulta->result();
            if (count($filas_consultadas)>0) $resultado->sin_registros = false;
        } catch (Exception $ex) {
            $resultado->error = true;
            $resultado->mensaje_error = "Ocurrio un evento inesperado, intentelo mas tarde.";
        }

        if ($resultado->sin_registros) return $resultado;

        // Se buscan los datos adicionales de los documentos (JRAD CONSULTA)
        try {
            $id_prospecto = implode(",",array_map(function ($i) {return $i->terceros_id;},$filas_consultadas));
            
            $consulta = $this->db->query("SELECT usuarios.usuario_id AS agente_codigo, CONCAT_WS(' ', usuarios.usuario_nombres, usuarios.usuario_app, usuarios.usuario_apm) AS agente_nombre, estructura_regional.estructura_regional_nombre, terceros.* FROM terceros INNER JOIN ejecutivo ON ejecutivo.ejecutivo_id=terceros.ejecutivo_id INNER JOIN usuarios ON usuarios.usuario_id=ejecutivo.usuario_id INNER JOIN estructura_agencia ON estructura_agencia.estructura_agencia_id=usuarios.estructura_agencia_id INNER JOIN estructura_regional ON estructura_regional.estructura_regional_id=estructura_agencia.estructura_regional_id WHERE terceros.terceros_id IN ($id_prospecto)");
            $filas_datos_adicionales =$consulta->result();
            
            // Se unen los datos adicionales
            foreach ($filas_consultadas as &$fila) {
                $fila->documento_nombre = "";
                $fila->ejecutivo_nombre = "";
                $fila->ciudad = "";
                foreach ($filas_datos_adicionales as $adicional) {

                            // Siempre se verifica que tenga coincidencia (JRAD CONSULTA)
                    if ($adicional->terceros_id != $fila->terceros_id) continue;
                    
                    $fila->terceros_id = $adicional->terceros_id;
                    
                    $fila->agencia_r_fecha_proceso = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->completado_fecha);
                    
                    $fila->agencia_r_cuenta_cobis = $adicional->onboarding_numero_cuenta;
                    $fila->agencia_r_codigo = $adicional->terceros_id;
                    $fila->agencia_r_ci = $adicional->cI_numeroraiz . ' ' . $adicional->cI_complemento . ' ' . $this->mfunciones_generales->GetValorCatalogoDB($adicional->cI_lugar_emisionoextension, 'cI_lugar_emisionoextension');
                    $fila->agencia_r_solicitante = $adicional->di_primernombre . ' ' . $adicional->di_primerapellido . ' ' . $adicional->di_segundoapellido;
                    $fila->agencia_r_regional = $this->mfunciones_generales->ObtenerNombreRegionCodigo($adicional->codigo_agencia_fie);
                    $fila->agencia_r_agencia = $this->mfunciones_generales->ObtenerNombreRegionCodigo($adicional->codigo_agencia_fie);
                    
                    $fila->agencia_r_estado = $this->mfunciones_generales->GetValorCatalogo($adicional->terceros_estado, 'terceros_estado');
                    
                    switch ($adicional->terceros_estado) {
                        case 6:
                            // Entregado Cliente
                            $fila->agencia_r_fecha_actualizacion = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->entregado_fecha);
                            $fila->agencia_r_usuario = $this->mfunciones_generales->getNombreUsuario($adicional->entregado_usuario);
                            
                            break;

                        case 7:
                            // Notificar Cierre
                            $fila->agencia_r_fecha_actualizacion = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->notificar_cierre_fecha);
                            $fila->agencia_r_usuario = $this->mfunciones_generales->getNombreUsuario($adicional->notificar_cierre_usuario);
                            
                            break;
                        
                        case 8:
                            // Cuenta Cerrada
                            $fila->agencia_r_fecha_actualizacion = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->cuenta_cerrada_fecha);
                            $fila->agencia_r_usuario = $this->mfunciones_generales->getNombreUsuario($adicional->cuenta_cerrada_usuario);
                            
                            break;
                        
                            $fila->agencia_r_fecha_actualizacion = 'No registrado';
                            $fila->agencia_r_usuario = 'No registrado';
                        
                        default:
                            break;
                    }
                    
                    if($adicional->terceros_estado == 7)
                    {
                        $fila->agencia_r_dias_notificacion = $this->mfunciones_generales->getDiasCalendario($adicional->notificar_cierre_fecha, date("Y-m-d H:i:s"));
                    }
                    else
                    {
                        $fila->agencia_r_dias_notificacion = '--';
                    }
                    
                }
            }
        } catch (Exception $ex) {
            $resultado->error = true;
            $resultado->mensaje_error = "Ocurrio un evento inesperado, intentelo mas tarde.";
        }
        

        // se guarda en el resultado el total de filas consultadas
        $resultado->cuenta = count($filas_consultadas);
        $resultado->filas = $filas_consultadas;

        return $resultado;
    }
    
    function Generar_Consulta_Onboarding($parametros, $tipo_reporte='') {
        // Se crea el objeto de resultado a devolver
        $resultado = new stdClass();
        $resultado->sin_registros = true;
        $resultado->error = false;
        $resultado->filas = array();
        $resultado->mensaje_error = "";
        $filtros = array();
        
        if (is_null($parametros) || !is_object($parametros)) return $resultado;
        
        // Parametros de la consulta
        foreach ($parametros->filtros as $f) {
            $datos_filtro = $this->Obtener_Tabla_Campo_Filtro($f->campo);
            if ($datos_filtro == null) continue;
            $f->tabla = $datos_filtro->tabla;
            $f->campo_sql = $datos_filtro->campo;
            $filtros[] = $f;
        }

        // se crea una variable temporal para almacenar el total de las etapas
        // Se buscan las definiciones de los campos a agrupar, y las tablas utilizadas en dicha agrupación

        $tablas_adicionales = array("");

        // Se agregan las tablas relacionadas con los campos de filtro
        foreach ($filtros as $filtro) {
            $tablas_adicionales[] = $filtro->tabla;
        }

        // Se unifican las tablas adicionales (eliminando los duplicados)
        $tablas_adicionales = array_unique($tablas_adicionales);

        // Se definen los campos a devolver por la consulta. Considerando los predeterminados y los usados en los grupos (JRAD CONSULTA)
        $lista_campos = explode("|","terceros.terceros_id as terceros_id");

        // Se construye la clausula where según los filtros (JRAD CONSULTA)
        $where = array("1");

        $sw_pivot = 0;
        
        foreach ($filtros as $filtro) {
            switch ($filtro->tipo) {
                case "texto":
                    $where[] = (strpos($filtro->campo_sql, "concat_ws") === false)?"(".$filtro->tabla.".".$filtro->campo_sql." like '%".$this->db->escape_str($filtro->valor)."%')"
                        :"(".$filtro->campo_sql." like '%".$this->db->escape_str($filtro->valor)."%')";
                    break;
                case "id":
                    $where[] = "(".$filtro->tabla.".".$filtro->campo_sql." = ".$this->db->escape($filtro->valor).")";
                    break;
                case "booleano":
                case "lista":
                    $where[] = "(".$filtro->tabla.".".$filtro->campo_sql." in (".implode(",",array_map(function ($i) {return $this->db->escape($i);},$filtro->valores))."))";
                    break;
                case "fecha":
                    if ($filtro->tabla == "calendario") {
                        $sql_where_fecha ="";
                        if (!empty($filtro->desde)) {
                            $fecha_desde = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->desde . ' 00:00:01');
                            $sql_where_fecha = " and (".$filtro->tabla.".".$filtro->campo_sql." >= '$fecha_desde')";
                        }
                        if (!empty($filtro->hasta)) {
                            $fecha_hasta = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->hasta . ' 23:59:59');
                            $sql_where_fecha = " and (".$filtro->tabla.".".$filtro->campo_sql." <= '$fecha_hasta')";
                        }
                        $where[] = "((select count(*) from calendario where calendario.cal_id_visita = prospecto.prospecto_id and calendario.cal_tipo_visita = 1$sql_where_fecha) > 0)";
                    } else {
                        if (!empty($filtro->desde)) {
                            $fecha_desde = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->desde . ' 00:00:01');
                            $where[] = "(" . $filtro->tabla . "." . $filtro->campo_sql . " >= '$fecha_desde')";
                        }
                        if (!empty($filtro->hasta)) {
                            $fecha_hasta = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->hasta . ' 23:59:59');
                            $where[] = "(" . $filtro->tabla . "." . $filtro->campo_sql . " <= '$fecha_hasta')";
                        }
                    }
                    break;
            }
            
            if($filtro->campo_sql == 'terceros_fecha') { $sw_pivot = 1; }
            
        }

        $sql_where = join(" AND ",$where);

        // Se crea el string sql de los campos de la consulta
        $sql_campos = implode(", ",$lista_campos);

        // Se obtiene el string de joins para la consulta sql
        $sql_uniones = $this->Obtener_Uniones_Prospecto($tablas_adicionales,"terceros");

            // Verifica si tiene el permiso para ver todo, caso contrario sólo la región del usuario        
            $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        
        // Se define el filtro sql
        $sql_filtro = "where codigo_agencia_fie IN ($lista_region->region_id) AND $sql_where";
        
        // Se define la porción FROM del sql (JRAD CONSULTA)
        $sql_tablas = "terceros $sql_uniones";

        // Se genera el SQL resultante							(JRAD CONSULTA)
        $sql = "select $sql_campos from $sql_tablas $sql_filtro order by terceros_id ASC ";

        // Se ejecuta la consulta SQL
        try {
            $consulta = $this->db->query($sql);
            $filas_consultadas = $consulta->result();
            if (count($filas_consultadas)>0) $resultado->sin_registros = false;
        } catch (Exception $ex) {
            $resultado->error = true;
            $resultado->mensaje_error = "Ocurrio un evento inesperado, intentelo mas tarde.";
        }

        if ($resultado->sin_registros) return $resultado;

        // Se buscan los datos adicionales de los documentos (JRAD CONSULTA)
        try {
            $id_prospecto = implode(",",array_map(function ($i) {return $i->terceros_id;},$filas_consultadas));
            
            $consulta = $this->db->query("SELECT usuarios.usuario_id AS agente_codigo, CONCAT_WS(' ', usuarios.usuario_nombres, usuarios.usuario_app, usuarios.usuario_apm) AS agente_nombre, estructura_regional.estructura_regional_nombre, terceros.* FROM terceros INNER JOIN ejecutivo ON ejecutivo.ejecutivo_id=terceros.ejecutivo_id INNER JOIN usuarios ON usuarios.usuario_id=ejecutivo.usuario_id INNER JOIN estructura_agencia ON estructura_agencia.estructura_agencia_id=usuarios.estructura_agencia_id INNER JOIN estructura_regional ON estructura_regional.estructura_regional_id=estructura_agencia.estructura_regional_id WHERE terceros.terceros_id IN ($id_prospecto)");
            $filas_datos_adicionales =$consulta->result();
            
                // Verifica si es Pivot, debe contar con almenos un filtro de la Fecha Registros
            
                if(empty($parametros->filtros))
                {
                    $resultado->error = true;
                    $resultado->mensaje_error = "El Reporte debe contar con filtros agregados, por favor debe Agregar mínimamente el Filtro 'Fecha Registro' con un rango de tiempo más certero.";
                    return $resultado;
                }
                else
                {
                    if($sw_pivot == 0)
                    {
                        $resultado->error = true;
                        $resultado->mensaje_error = "Por favor Agregar el Filtro 'Fecha Registro' y un rango de tiempo más certero.";
                        return $resultado;
                    }
                }
            
            // Se unen los datos adicionales
            foreach ($filas_consultadas as &$fila) {
                $fila->documento_nombre = "";
                $fila->ejecutivo_nombre = "";
                $fila->ciudad = "";
                foreach ($filas_datos_adicionales as $adicional) {

                            // Siempre se verifica que tenga coincidencia (JRAD CONSULTA)
                    if ($adicional->terceros_id != $fila->terceros_id) continue;
                    
                    $fila->terceros_id = $adicional->terceros_id;
                    $fila->ejecutivo_id = $adicional->ejecutivo_id;
                    $fila->codigo_usuario = $adicional->agente_codigo;
                    
                    $fila->agente_nombre = $adicional->agente_nombre;
                    $fila->estructura_regional_nombre = $adicional->estructura_regional_nombre;
                    $fila->tercero_asistencia = $this->mfunciones_generales->GetValorCatalogo($adicional->tercero_asistencia, 'tercero_asistencia');
                    
                    if($adicional->tercero_asistencia == 0)
                    {
                        $fila->agente_nombre = 'No corresponde';
                        $fila->estructura_regional_nombre = 'No corresponde';
                    }
                    
                    $fila->terceros_columna1 = $this->mfunciones_generales->ObtenerNombreRegionCodigo($adicional->codigo_agencia_fie);
                    $fila->terceros_columna2 = $this->mfunciones_generales->GetValorCatalogoDB($adicional->dir_localidad_ciudad, 'dir_localidad_ciudad');
                    $fila->terceros_columna3 = $adicional->di_primernombre . ' ' . $adicional->di_primerapellido . ' ' . $adicional->di_segundoapellido;
                    $fila->terceros_columna4 = $adicional->cI_numeroraiz . ' ' . $adicional->cI_complemento . ' ' . $this->mfunciones_generales->GetValorCatalogoDB($adicional->cI_lugar_emisionoextension, 'cI_lugar_emisionoextension');
                    $fila->terceros_columna5 = $adicional->direccion_email . '<br />' . $adicional->dir_notelefonico;
                    $fila->terceros_columna7 = $this->mfunciones_generales->GetValorCatalogo($adicional->terceros_estado, 'terceros_estado');
                    $fila->terceros_columna8 = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->terceros_fecha);
                    $fila->geo1 = $adicional->coordenadas_geo_dom;
                    $fila->geo2 = $adicional->coordenadas_geo_trab;
                    $fila->envio = $this->mfunciones_generales->GetValorCatalogo($adicional->envio, 'tercero_envio');
                    
                    $fila->tipo_cuenta = $this->mfunciones_generales->GetValorCatalogoDB($adicional->tipo_cuenta, 'tipo_cuenta');
                    
                    if($tipo_reporte == 'pivot')
                    {   
                        $fila->terceros_rekognition = $this->mfunciones_generales->GetValorCatalogo($adicional->terceros_rekognition, 'si_no');
                        
                        if($fila->terceros_rekognition == 0)
                        {
                            $fila->terceros_rekognition_similarity = 'No Corresponde';
                        }
                        else
                        {
                            $fila->terceros_rekognition_similarity = number_format($adicional->terceros_rekognition_similarity, 2, ',', '.');
                        }
                        
                        $fila->aprobado = $this->mfunciones_generales->GetValorCatalogo($adicional->aprobado, 'si_no');
                        $fila->aprobado_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->aprobado_fecha);
                        $fila->aprobado_usuario = $this->mfunciones_generales->getNombreUsuario($adicional->aprobado_usuario);
                        $fila->cobis = $this->mfunciones_generales->GetValorCatalogo($adicional->cobis, 'si_no');
                        $fila->cobis_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->cobis_fecha);
                        $fila->cobis_usuario = $this->mfunciones_generales->getNombreUsuario($adicional->cobis_usuario);
                        $fila->completado = $this->mfunciones_generales->GetValorCatalogo($adicional->completado, 'si_no');
                        $fila->completado_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->completado_fecha);
                        $fila->completado_usuario = $this->mfunciones_generales->getNombreUsuario($adicional->completado_usuario);
                        $fila->completado_envia = $this->mfunciones_generales->GetValorCatalogo($adicional->completado_envia, 'si_no');
                        $fila->completado_texto = $adicional->completado_texto;
                        $fila->completado_docs_enviados = $this->mfunciones_generales->DocsComa2Lista($adicional->completado_docs_enviados);
                        
                        $fila->entregado = $this->mfunciones_generales->GetValorCatalogo($adicional->entregado, 'si_no');
                        $fila->entregado_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->entregado_fecha);
                        $fila->entregado_usuario = $this->mfunciones_generales->getNombreUsuario($adicional->entregado_usuario);
                        $fila->entregado_texto = $adicional->entregado_texto;                       
                        
                        $fila->rechazado = $this->mfunciones_generales->GetValorCatalogo($adicional->rechazado, 'si_no');
                        $fila->terceros_observacion = $adicional->terceros_observacion;
                        $fila->rechazado_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->rechazado_fecha);
                        $fila->rechazado_usuario = $this->mfunciones_generales->getNombreUsuario($adicional->rechazado_usuario);
                        $fila->rechazado_envia = $this->mfunciones_generales->GetValorCatalogo($adicional->rechazado_envia, 'si_no');
                        $fila->rechazado_texto = $adicional->rechazado_texto;
                        $fila->rechazado_docs_enviados = $this->mfunciones_generales->DocsComa2Lista($adicional->rechazado_docs_enviados);
                        
                        // Colocar los demás campos para el Pivot
                        
                        $fila->onboarding_numero_cuenta = $adicional->onboarding_numero_cuenta;
                        
                        $fila->monto_inicial = $adicional->monto_inicial;
                        $fila->direccion_email = $adicional->direccion_email;
                        $fila->coordenadas_geo_dom = $adicional->coordenadas_geo_dom;
                        $fila->coordenadas_geo_trab = $adicional->coordenadas_geo_trab;
                        $fila->envio = $this->mfunciones_generales->GetValorCatalogo($adicional->envio, 'tercero_envio');
                        $fila->cI_numeroraiz = $adicional->cI_numeroraiz;
                        $fila->cI_complemento = $adicional->cI_complemento;
                        $fila->cI_lugar_emisionoextension = $this->mfunciones_generales->GetValorCatalogoDB($adicional->cI_lugar_emisionoextension, 'cI_lugar_emisionoextension');
                        $fila->cI_confirmacion_id = $adicional->cI_confirmacion_id;
                        $fila->di_primernombre = $adicional->di_primernombre;
                        $fila->di_segundo_otrosnombres = $adicional->di_segundo_otrosnombres;
                        $fila->di_primerapellido = $adicional->di_primerapellido;
                        $fila->di_segundoapellido = $adicional->di_segundoapellido;
                        $fila->di_fecha_nacimiento = $this->mfunciones_generales->pivotFormatoFechaD_M_Y($adicional->di_fecha_nacimiento);
                        $fila->di_fecha_vencimiento = $this->mfunciones_generales->pivotFormatoFechaD_M_Y($adicional->di_fecha_vencimiento);
                        $fila->di_indefinido = $this->mfunciones_generales->GetValorCatalogo($adicional->di_indefinido, 'si_no');
                        $fila->di_genero = $this->mfunciones_generales->GetValorCatalogoDB($adicional->di_genero, 'di_genero');
                        $fila->di_estadocivil = $this->mfunciones_generales->GetValorCatalogoDB($adicional->di_estadocivil, 'di_estadocivil');
                        $fila->di_apellido_casada = $adicional->di_apellido_casada;
                        $fila->dd_profesion = $this->mfunciones_generales->GetValorCatalogoDB($adicional->dd_profesion, 'dd_profesion');
                        $fila->dd_nivel_estudios = $this->mfunciones_generales->GetValorCatalogoDB($adicional->dd_nivel_estudios, 'dd_nivel_estudios');
                        $fila->dd_dependientes = $adicional->dd_dependientes;
                        $fila->dd_proposito_rel_comercial = $this->mfunciones_generales->GetValorCatalogoDB($adicional->dd_proposito_rel_comercial, 'dd_proposito_rel_comercial');
                        $fila->dec_ingresos_mensuales = $this->mfunciones_generales->GetValorCatalogoDB($adicional->dec_ingresos_mensuales, 'dec_ingresos_mensuales');
                        $fila->dec_nivel_egresos = $this->mfunciones_generales->GetValorCatalogoDB($adicional->dec_nivel_egresos, 'dec_nivel_egresos');
                        $fila->dir_tipo_direccion = $this->mfunciones_generales->GetValorCatalogoDB($adicional->dir_tipo_direccion, 'dir_tipo_direccion');
                        $fila->dir_departamento = $this->mfunciones_generales->GetValorCatalogoDB($adicional->dir_departamento, 'dir_departamento');
                        $fila->dir_provincia = $this->mfunciones_generales->GetValorCatalogoDB($adicional->dir_provincia, 'dir_provincia');
                        $fila->dir_localidad_ciudad = $this->mfunciones_generales->GetValorCatalogoDB($adicional->dir_localidad_ciudad, 'dir_localidad_ciudad');
                        $fila->dir_barrio_zona_uv = $this->mfunciones_generales->GetValorCatalogoDB($adicional->dir_barrio_zona_uv, 'dir_barrio_zona_uv');
                        $fila->dir_ubicacionreferencial = $adicional->dir_ubicacionreferencial;
                        $fila->dir_av_calle_pasaje = $adicional->dir_av_calle_pasaje;
                        $fila->dir_edif_cond_urb = $adicional->dir_edif_cond_urb;
                        $fila->dir_numero = $adicional->dir_numero;
                        
                        $fila->dir_departamento_neg = $this->mfunciones_generales->GetValorCatalogoDB($adicional->dir_departamento_neg, 'dir_departamento');
                        $fila->dir_provincia_neg = $this->mfunciones_generales->GetValorCatalogoDB($adicional->dir_provincia_neg, 'dir_provincia');
                        $fila->dir_localidad_ciudad_neg = $this->mfunciones_generales->GetValorCatalogoDB($adicional->dir_localidad_ciudad_neg, 'dir_localidad_ciudad');
                        $fila->dir_barrio_zona_uv_neg = $this->mfunciones_generales->GetValorCatalogoDB($adicional->dir_barrio_zona_uv_neg, 'dir_barrio_zona_uv');
                        $fila->dir_av_calle_pasaje_neg = $adicional->dir_av_calle_pasaje_neg;
                        $fila->dir_edif_cond_urb_neg = $adicional->dir_edif_cond_urb_neg;
                        $fila->dir_numero_neg = $adicional->dir_numero_neg;
                        
                        $fila->dir_tipo_telefono = $adicional->dir_tipo_telefono;
                        $fila->dir_notelefonico = $adicional->dir_notelefonico;
                        $fila->ae_sector_economico = $this->mfunciones_generales->GetValorCatalogoDB($adicional->ae_sector_economico, 'ae_sector_economico');
                        
                        $fila->ae_subsector_economico = $this->mfunciones_generales->GetValorCatalogoDB($adicional->ae_subsector_economico, 'ae_subsector_economico');
                        $fila->ae_actividad_ocupacion = $this->mfunciones_generales->GetValorCatalogoDB($adicional->ae_actividad_fie, 'ae_actividad_economica');
                        
                        $fila->ae_actividad_fie = $this->mfunciones_generales->GetValorCatalogoDB($adicional->ae_actividad_fie, 'ae_actividad_fie');
                        $fila->ae_ambiente = $this->mfunciones_generales->GetValorCatalogoDB($adicional->ae_ambiente, 'ae_ambiente');
                        $fila->emp_nombre_empresa = $adicional->emp_nombre_empresa;
                        $fila->emp_direccion_trabajo = $adicional->emp_direccion_trabajo;
                        $fila->emp_telefono_faxtrabaj = $adicional->emp_telefono_faxtrabaj;
                        $fila->emp_tipo_empresa = $this->mfunciones_generales->GetValorCatalogoDB($adicional->emp_tipo_empresa, 'emp_tipo_empresa');
                        $fila->emp_antiguedad_empresa = $adicional->emp_antiguedad_empresa;
                        $fila->emp_codigo_actividad = $this->mfunciones_generales->GetValorCatalogoDB($adicional->emp_codigo_actividad, 'emp_codigo_actividad');
                        $fila->emp_descripcion_cargo = $adicional->emp_descripcion_cargo;
                        $fila->emp_fecha_ingreso = $this->mfunciones_generales->pivotFormatoFechaD_M_Y($adicional->emp_fecha_ingreso);
                        $fila->rp_nombres = $adicional->rp_nombres;
                        $fila->rp_primer_apellido = $adicional->rp_primer_apellido;
                        $fila->rp_segundo_apellido = $adicional->rp_segundo_apellido;
                        $fila->rp_direccion = $adicional->rp_direccion;
                        $fila->rp_notelefonicos = $adicional->rp_notelefonicos;
                        $fila->rp_nexo_cliente = $this->mfunciones_generales->GetValorCatalogoDB($adicional->rp_nexo_cliente, 'rp_nexo_cliente');
                        $fila->con_primer_nombre = $adicional->con_primer_nombre;
                        $fila->con_segundo_nombre = $adicional->con_segundo_nombre;
                        $fila->con_primera_pellido = $adicional->con_primera_pellido;
                        $fila->con_segundoa_pellido = $adicional->con_segundoa_pellido;
                        $fila->con_acteconomica_principal = $this->mfunciones_generales->GetValorCatalogoDB($adicional->con_acteconomica_principal, 'ae_actividad_economica');
                        $fila->ddc_ciudadania_usa = $this->mfunciones_generales->GetValorCatalogo($adicional->ddc_ciudadania_usa, 'si_no');
                        
                        // -- Req. Consulta COBIS y SEGIP
                        
                        $fila->ws_segip_flag_verificacion = $this->mfunciones_generales->GetValorCatalogo($adicional->ws_segip_flag_verificacion, 'si_no');
                        $fila->ws_segip_codigo_resultado = $this->mfunciones_generales->GetValorCatalogoDB($adicional->ws_segip_codigo_resultado, 'segip_codigo_respuesta');
                        $fila->ws_cobis_codigo_resultado = $this->mfunciones_generales->GetValorCatalogoDB($adicional->ws_cobis_codigo_resultado, 'segip_codigo_respuesta');
                        
                        $fila->ws_selfie_codigo_resultado = $this->mfunciones_generales->GetValorCatalogoDB($adicional->ws_selfie_codigo_resultado, 'segip_codigo_respuesta');
                        
                        $fila->ws_ocr_codigo_resultado = $this->mfunciones_generales->GetValorCatalogoDB($adicional->ws_ocr_codigo_resultado, 'segip_codigo_respuesta');
                        $fila->ws_ocr_name_valor = ((int)$adicional->ws_ocr_name_similar == -1 ? 'No Registrado' : $adicional->ws_ocr_name_valor);
                        $fila->ws_ocr_name_similar = ((int)$adicional->ws_ocr_name_similar == -1 ? 'No Registrado' : $adicional->ws_ocr_name_similar . '%');
                        
                        $fila->segip_operador_resultado = $this->mfunciones_generales->GetValorCatalogo($adicional->segip_operador_resultado, 'segip_operador_resultado');
                        $fila->segip_operador_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->segip_operador_fecha);
                        $fila->segip_operador_usuario = $this->mfunciones_generales->getNombreUsuario($adicional->segip_operador_usuario);
                        $fila->segip_operador_texto = $adicional->segip_operador_texto;
                        
                        // Req. Nuevos estados
                        
                        $fila->notificar_cierre = $this->mfunciones_generales->GetValorCatalogo($adicional->notificar_cierre, 'si_no');
                        $fila->notificar_cierre_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->notificar_cierre_fecha);
                        $fila->notificar_cierre_usuario = $this->mfunciones_generales->getNombreUsuario($adicional->notificar_cierre_usuario);
                        $fila->notificar_cierre_causa = $this->mfunciones_generales->GetValorCatalogoDB($adicional->notificar_cierre_causa, 'causa_notificar_cierre');
                        $fila->cuenta_cerrada = $this->mfunciones_generales->GetValorCatalogo($adicional->cuenta_cerrada, 'si_no');
                        $fila->cuenta_cerrada_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->cuenta_cerrada_fecha);
                        $fila->cuenta_cerrada_usuario = $this->mfunciones_generales->getNombreUsuario($adicional->cuenta_cerrada_usuario);
                        $fila->cuenta_cerrada_causa = $this->mfunciones_generales->GetValorCatalogoDB($adicional->cuenta_cerrada_causa, 'causa_cuenta_cerrada');
                        $fila->cuenta_cerrada_envia = $this->mfunciones_generales->GetValorCatalogo($adicional->cuenta_cerrada_envia, 'si_no');
                        
                        // -- Req. Flujo COBIS
                        
                        $fila->f_cobis_actual_etapa = $this->mfunciones_generales->GetValorCatalogo($adicional->f_cobis_actual_etapa, 'flujo_cobis_etapa');
                        $fila->f_cobis_actual_intento = ($adicional->f_cobis_actual_intento==0 ? 'Ninguno' : $adicional->f_cobis_actual_intento);
                        $fila->f_cobis_actual_usuario = $this->mfunciones_generales->getNombreUsuario($adicional->f_cobis_actual_usuario);
                        $fila->f_cobis_actual_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->f_cobis_actual_fecha);
                        $fila->f_cobis_codigo = ($adicional->f_cobis_codigo=='' ? 'No registrado' : $adicional->f_cobis_codigo);
                        $fila->f_cobis_excepcion = $this->mfunciones_generales->GetValorCatalogo($adicional->f_cobis_excepcion, 'si_no');
                        $fila->f_cobis_excepcion_motivo = $this->mfunciones_generales->GetValorCatalogoDB($adicional->f_cobis_excepcion_motivo, 'motivo_flujo_cobis');
                        $fila->f_cobis_excepcion_motivo_texto = $adicional->f_cobis_excepcion_motivo_texto;
                        $fila->f_cobis_flag_rechazado = $this->mfunciones_generales->GetValorCatalogo($adicional->f_cobis_flag_rechazado, 'si_no');
                    }
                    
                }
            }
        } catch (Exception $ex) {
            $resultado->error = true;
            $resultado->mensaje_error = "Ocurrio un evento inesperado, intentelo mas tarde.";
        }
        

        // se guarda en el resultado el total de filas consultadas
        $resultado->cuenta = count($filas_consultadas);
        $resultado->filas = $filas_consultadas;

        return $resultado;
    }
    
    function Generar_Consulta_Prospectos($parametros, $tipo_reporte='') {
        // Se crea el objeto de resultado a devolver
        $resultado = new stdClass();
        $resultado->sin_registros = true;
        $resultado->error = false;
        $resultado->filas = array();
        $resultado->mensaje_error = "";
        $filtros = array();
        
        if (is_null($parametros) || !is_object($parametros)) return $resultado;

        // Parametros de la consulta
        foreach ($parametros->filtros as $f) {
            $datos_filtro = $this->Obtener_Tabla_Campo_Filtro($f->campo);
            if ($datos_filtro == null) continue;
            $f->tabla = $datos_filtro->tabla;
            $f->campo_sql = $datos_filtro->campo;
            $filtros[] = $f;
        }

        // se crea una variable temporal para almacenar el total de las etapas
        // Se buscan las definiciones de los campos a agrupar, y las tablas utilizadas en dicha agrupación

        $tablas_adicionales = array("");

        // Se agregan las tablas relacionadas con los campos de filtro
        foreach ($filtros as $filtro) {
            $tablas_adicionales[] = $filtro->tabla;
        }

        // Se unifican las tablas adicionales (eliminando los duplicados)
        $tablas_adicionales = array_unique($tablas_adicionales);

        // Se definen los campos a devolver por la consulta. Considerando los predeterminados y los usados en los grupos (JRAD CONSULTA)
        $lista_campos = explode("|","prospecto.prospecto_id as prospecto_id");

        // Se construye la clausula where según los filtros (JRAD CONSULTA)
        $where = array("1");

        $sw_pivot = 0;
        $sw_solcred = 0;
        
        foreach ($filtros as $filtro) {
            
            if($filtro->campo_sql == 'sol_estudio') { $sw_solcred = 1; $sw_solcred_valor = implode(",",array_map(function ($i) {return $this->db->escape($i);},$filtro->valores));continue; }
            
            switch ($filtro->tipo) {
                case "texto":
                    $where[] = (strpos($filtro->campo_sql, "concat_ws") === false)?"(".$filtro->tabla.".".$filtro->campo_sql." like '%".$this->db->escape_str($filtro->valor)."%')"
                        :"(".$filtro->campo_sql." like '%".$this->db->escape_str($filtro->valor)."%')";
                    break;
                case "id":
                    $where[] = "(".$filtro->tabla.".".$filtro->campo_sql." = ".$this->db->escape($filtro->valor).")";
                    break;
                case "booleano":
                case "lista":
                    $where[] = "(".$filtro->tabla.".".$filtro->campo_sql." in (".implode(",",array_map(function ($i) {return $this->db->escape($i);},$filtro->valores))."))";
                    break;
                case "fecha":
                    if ($filtro->tabla == "calendario") {
                        $sql_where_fecha ="";
                        if (!empty($filtro->desde)) {
                            $fecha_desde = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->desde . ' 00:00:01');
                            $sql_where_fecha = " and (".$filtro->tabla.".".$filtro->campo_sql." >= '$fecha_desde')";
                        }
                        if (!empty($filtro->hasta)) {
                            $fecha_hasta = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->hasta . ' 23:59:59');
                            $sql_where_fecha = " and (".$filtro->tabla.".".$filtro->campo_sql." <= '$fecha_hasta')";
                        }
                        $where[] = "((select count(*) from calendario where calendario.cal_id_visita = prospecto.prospecto_id and calendario.cal_tipo_visita = 1$sql_where_fecha) > 0)";
                    } else {
                        if (!empty($filtro->desde)) {
                            $fecha_desde = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->desde . ' 00:00:01');
                            $where[] = "(" . $filtro->tabla . "." . $filtro->campo_sql . " >= '$fecha_desde')";
                        }
                        if (!empty($filtro->hasta)) {
                            $fecha_hasta = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->hasta . ' 23:59:59');
                            $where[] = "(" . $filtro->tabla . "." . $filtro->campo_sql . " <= '$fecha_hasta')";
                        }
                    }
                    break;
            }
            
            if($filtro->campo_sql == 'prospecto_fecha_asignacion') { $sw_pivot = 1; }
        }
        
        if(empty($parametros->filtros))
        {
            $resultado->error = true;
            $resultado->mensaje_error = "El Reporte debe contar con filtros agregados, por favor debe Agregar mínimamente el Filtro 'Fecha de Asignación' con un rango de tiempo más certero.";
            return $resultado;
        }
        else
        {
            if($sw_pivot == 0)
            {
                $resultado->error = true;
                $resultado->mensaje_error = "Por favor debe Agregar el Filtro 'Fecha de Asignación' y un rango de tiempo más certero.";
                return $resultado;
            }
        }
        
        if($sw_solcred == 1)
        {
            $where[] = "prospecto.prospecto_id " . ($sw_solcred_valor == "'0'" ? "NOT" : "") . " IN (select sol_estudio_codigo from solicitud_credito WHERE sol_estudio=1)";
        }
        
        $sql_where = join(" AND ",$where);

        // Se crea el string sql de los campos de la consulta
        $sql_campos = implode(", ",$lista_campos);

        // Se obtiene el string de joins para la consulta sql
        $sql_uniones = $this->Obtener_Uniones_Prospecto($tablas_adicionales,"prospecto");

        // Se define el filtro sql
        $sql_filtro = "where prospecto.onboarding=0 AND prospecto.general_categoria=1 AND $sql_where";
        
            // Verifica si tiene el permiso para ver todo, caso contrario sólo la región del usuario        
            $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
            $sql_filtro .= " AND prospecto.codigo_agencia_fie IN ($lista_region->region_id) ";

        // Se define la porción FROM del sql (JRAD CONSULTA)
        $sql_tablas = "prospecto $filtro_region $sql_uniones";

        // Se genera el SQL resultante							(JRAD CONSULTA)
        $sql = "select $sql_campos from $sql_tablas $sql_filtro order by prospecto_id ASC ";

        // Se ejecuta la consulta SQL
        try {
            
            $consulta = $this->db->query($sql);
            $filas_consultadas = $consulta->result();
            if (count($filas_consultadas)>0) $resultado->sin_registros = false;
        } catch (Exception $ex) {
            $resultado->error = true;
            $resultado->mensaje_error = "Ocurrio un evento inesperado, intentelo mas tarde.";
        }

        if ($resultado->sin_registros) return $resultado;

        // Se buscan los datos adicionales de los documentos (JRAD CONSULTA)
        try {
            $id_prospecto = implode(",",array_map(function ($i) {return $i->prospecto_id;},$filas_consultadas));
            
            $consulta = $this->db->query("SELECT estructura_regional.estructura_regional_id, camp.camp_id, camp.camp_nombre, ejecutivo.ejecutivo_id, prospecto.prospecto_jda_eval, prospecto.prospecto_jda_eval_texto, prospecto.prospecto_jda_eval_usuario, prospecto.prospecto_jda_eval_fecha, prospecto.prospecto_desembolso, prospecto.prospecto_desembolso_monto, prospecto.prospecto_desembolso_usuario, prospecto.prospecto_desembolso_fecha, prospecto.prospecto_observado_app, prospecto.prospecto_evaluacion, prospecto.prospecto_id as prospecto_id, prospecto.general_solicitante, prospecto.general_ci, prospecto.general_ci_extension, prospecto.general_actividad, prospecto.general_destino, prospecto.prospecto_estado_actual, prospecto.prospecto_fecha_asignacion, prospecto.prospecto_fecha_conclusion, prospecto.prospecto_checkin, prospecto.prospecto_checkin_fecha, prospecto.prospecto_checkin_geo, prospecto.prospecto_llamada, prospecto.prospecto_llamada_fecha, prospecto.prospecto_llamada_geo, prospecto.prospecto_consolidado, prospecto.prospecto_consolidar_fecha, prospecto.prospecto_consolidar_geo, prospecto.prospecto_principal, prospecto.general_telefono, prospecto.general_email, prospecto.general_direccion, prospecto.general_comentarios, prospecto.general_interes, prospecto.operacion_antiguedad_ano, prospecto.operacion_antiguedad_mes, prospecto.operacion_tiempo_ano, prospecto.operacion_tiempo_mes, prospecto.operacion_dias, prospecto.frec_seleccion, prospecto.capacidad_criterio, prospecto.estacion_sel_arb, concat_ws(' ',usuarios.usuario_nombres,usuarios.usuario_app,usuarios.usuario_apm) as ejecutivo_nombre, usuarios.usuario_id, etapa.etapa_nombre FROM prospecto INNER JOIN ejecutivo ON ejecutivo.ejecutivo_id=prospecto.ejecutivo_id INNER JOIN usuarios ON usuarios.usuario_id = ejecutivo.usuario_id INNER JOIN estructura_regional ON estructura_regional.estructura_regional_id = prospecto.codigo_agencia_fie INNER JOIN etapa ON etapa.etapa_id=prospecto.prospecto_etapa INNER JOIN campana camp ON camp.camp_id=prospecto.camp_id WHERE prospecto.prospecto_id IN ($id_prospecto)");
            
            // Se obtiene los parámetros de configuración general para el tipo de cambio 
            $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
            
            $filas_datos_adicionales =$consulta->result();
            // Se unen los datos adicionales
            foreach ($filas_consultadas as &$fila) {
                $fila->documento_nombre = "";
                $fila->ejecutivo_nombre = "";
                $fila->ciudad = "";
                foreach ($filas_datos_adicionales as $adicional) {
					
                            // Siempre se verifica que tenga coincidencia (JRAD CONSULTA)
                    if ($adicional->prospecto_id != $fila->prospecto_id) continue;
                    
                    $fila->general_solicitante = $adicional->general_solicitante;
                    $fila->general_ci = $adicional->general_ci;
                    $fila->general_ci_extension = $adicional->general_ci_extension;
                    $fila->general_actividad = $adicional->general_actividad;
                    $fila->general_destino = $adicional->general_destino;
                    $fila->prospecto_jda_eval = $adicional->prospecto_jda_eval;
                    
                    $fila->camp_id = $adicional->camp_id;
                    $fila->camp_nombre = $adicional->camp_nombre;
                    $fila->prospecto_estado_actual = $adicional->prospecto_estado_actual;
                    $fila->ejecutivo_nombre = $adicional->ejecutivo_nombre;
                    $fila->ejecutivo_id = $adicional->ejecutivo_id;
                    $fila->etapa_nombre = $adicional->etapa_nombre;
                    $fila->usuario_id = $adicional->usuario_id;
                    
                    $fila->prospecto_consolidado_codigo = $adicional->prospecto_consolidado;
                    
                    $this->load->model('mfunciones_microcreditos');
                    
                    $sol_cred = $this->mfunciones_microcreditos->ObtenerSol_from_Pros($fila->prospecto_id);
                    
                    if((int)$adicional->prospecto_desembolso == 1)
                    {
                        $fila->sol_monto_bs = $fila->prospecto_desembolso_monto = number_format($adicional->prospecto_desembolso_monto, 2, ',', '.');
                    }
                    else
                    {
                        if((int)$sol_cred->sol_id > 0)
                        {
                            $fila->sol_monto_bs = number_format(($sol_cred->sol_moneda=='bob' ? $sol_cred->sol_monto : $sol_cred->sol_monto*$arrConf[0]['conf_credito_tipo_cambio']), 2, ',', '.');
                        }
                        else
                        {
                            $fila->sol_monto_bs = '0,00';
                        }
                    }
                    
                    $aux_datos_agencia = $this->mfunciones_microcreditos->ObtenerDatosRegionCodigo($adicional->estructura_regional_id);
                    $fila->estudio_agencia_nombre = $aux_datos_agencia->nombre;
                    
                    if($parametros->opcionResultado == 'pivot')
                    {
                        $fila->estudio_agencia_departamento = $aux_datos_agencia->departamento;
                        $fila->estudio_agencia_provincia = $aux_datos_agencia->provincia;
                        $fila->estudio_agencia_ciudad = $aux_datos_agencia->ciudad;
                        
                        if((int)$sol_cred->sol_id > 0)
                        {
                            $fila->sol_estudio = 'Si';
                            $fila->sol_id = 'SOL_' . $sol_cred->sol_id;
                            $fila->sol_moneda = $this->mfunciones_microcreditos->GetValorCatalogo($sol_cred->sol_moneda, 'sol_moneda');
                            $fila->sol_monto = number_format($sol_cred->sol_monto, 2, ',', '.');
                            $fila->sol_monto_bs = number_format(($sol_cred->sol_moneda=='bob' ? $sol_cred->sol_monto : $sol_cred->sol_monto*$arrConf[0]['conf_credito_tipo_cambio']), 2, ',', '.');
                            $fila->sol_detalle = $sol_cred->sol_detalle;
                            $fila->sol_plazo = $sol_cred->sol_plazo . ' mes(es)';
                            $fila->sol_consolidado_usuario = $this->mfunciones_generales->getNombreUsuario($sol_cred->sol_consolidado_usuario);
                            $fila->sol_consolidado_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($sol_cred->sol_consolidado_fecha);
                            $fila->sol_consolidado_geo = $sol_cred->sol_consolidado_geo;

                            $fila->sol_asistencia = $this->mfunciones_generales->GetValorCatalogo($sol_cred->sol_asistencia, 'tercero_asistencia');
                            $fila->sol_codigo_rubro = $this->mfunciones_microcreditos->GetValorCatalogo($sol_cred->sol_codigo_rubro, 'aux_rubro');
                            
                            $aux_datos_agencia = $this->mfunciones_microcreditos->ObtenerDatosRegionCodigo($sol_cred->codigo_agencia_fie);
                            $fila->sol_agencia_nombre = $aux_datos_agencia->nombre;
                            $fila->sol_agencia_departamento = $aux_datos_agencia->departamento;
                            $fila->sol_agencia_provincia = $aux_datos_agencia->provincia;
                            $fila->sol_agencia_ciudad = $aux_datos_agencia->ciudad;
                        }
                        else
                        {
                            $fila->sol_estudio = 'No';
                            $fila->sol_id = '';
                            $fila->sol_moneda = 'No Definido';
                            $fila->sol_monto = '0,00';
                            $fila->sol_monto_bs = '0,00';
                            $fila->sol_detalle = 'No Definido';
                            $fila->sol_plazo = 'No Definido';
                            $fila->sol_consolidado_usuario = 'No Definido';
                            $fila->sol_consolidado_fecha = 'No Definido';
                            $fila->sol_consolidado_geo = 'No Definido';
                            
                            $fila->sol_asistencia = 'No Definido';
                            $fila->sol_codigo_rubro = 'No Definido';
                            $fila->sol_agencia_nombre = 'No Definido';
                            $fila->sol_agencia_departamento = 'No Definido';
                            $fila->sol_agencia_provincia = 'No Definido';
                            $fila->sol_agencia_ciudad = 'No Definido';
                        }
                        
                        $fila->prospecto_fecha_asignacion = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->prospecto_fecha_asignacion);
                        $fila->prospecto_fecha_conclusion = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->prospecto_fecha_conclusion);

                        $fila->prospecto_checkin = $this->mfunciones_generales->GetValorCatalogo($adicional->prospecto_checkin, 'si_no');
                        $fila->prospecto_checkin_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->prospecto_checkin_fecha);
                        $fila->prospecto_checkin_geo = $adicional->prospecto_checkin_geo;
                        $fila->prospecto_llamada = $this->mfunciones_generales->GetValorCatalogo($adicional->prospecto_llamada, 'si_no');
                        $fila->prospecto_llamada_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->prospecto_llamada_fecha);
                        $fila->prospecto_llamada_geo = $adicional->prospecto_llamada_geo;

                        $fila->prospecto_observado_app = $this->mfunciones_generales->GetValorCatalogo($adicional->prospecto_observado_app, 'si_no');
                        
                        $fila->prospecto_consolidado = $this->mfunciones_generales->GetValorCatalogo($adicional->prospecto_consolidado, 'si_no');
                        $fila->prospecto_consolidar_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->prospecto_consolidar_fecha);
                        $fila->prospecto_consolidar_geo = $adicional->prospecto_consolidar_geo;
                        
                        $fila->prospecto_evaluacion = $this->mfunciones_generales->GetValorCatalogo($adicional->prospecto_evaluacion, 'prospecto_evaluacion');
                        
                        $fila->prospecto_jda_eval_texto = $adicional->prospecto_jda_eval_texto;
                        $fila->prospecto_jda_eval_usuario = $this->mfunciones_generales->getNombreUsuario($adicional->prospecto_jda_eval_usuario);
                        $fila->prospecto_jda_eval_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->prospecto_jda_eval_fecha);
                        $fila->prospecto_desembolso = $this->mfunciones_generales->GetValorCatalogo($adicional->prospecto_desembolso, 'si_no');
                        $fila->prospecto_desembolso_monto = number_format($adicional->prospecto_desembolso_monto, 2, ',', '.');
                        $fila->prospecto_desembolso_usuario = $this->mfunciones_generales->getNombreUsuario($adicional->prospecto_desembolso_usuario);
                        $fila->prospecto_desembolso_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->prospecto_desembolso_fecha);
                        
                        $fila->prospecto_principal = $this->mfunciones_generales->GetValorCatalogo($adicional->prospecto_principal, 'si_no');
                        $fila->general_telefono = $adicional->general_telefono;
                        $fila->general_email = $adicional->general_email;
                        $fila->general_direccion = $adicional->general_direccion;
                        $fila->general_actividad = $adicional->general_actividad;
                        $fila->general_comentarios = $adicional->general_comentarios;
                        $fila->general_interes = $this->mfunciones_generales->GetValorCatalogo($adicional->general_interes, 'grado_interes');
                        
                        $fila->operacion_antiguedad = $adicional->operacion_antiguedad_ano . ' año(s) y ' . $adicional->operacion_antiguedad_mes . ' mes(es)';
                        $fila->operacion_tiempo = $adicional->operacion_tiempo_ano . ' año(s) y ' . $adicional->operacion_tiempo_mes . ' mes(es)';
                        $fila->operacion_dias = $adicional->operacion_dias;
                        
                        switch ((int)$adicional->frec_seleccion) {
                            case 1: $fila->frec_seleccion = 'Diaria'; break;
                            case 2: $fila->frec_seleccion = 'Semanal'; break;
                            case 3: $fila->frec_seleccion = 'Mensual'; break;

                            default:
                                break;
                        }
                        
                        switch ((int)$adicional->capacidad_criterio) {
                            case 1: $fila->capacidad_criterio = 'Ventas Declaradas Según Frecuencia del Ingreso'; break;
                            case 2: $fila->capacidad_criterio = 'Ventas por Principales Productos Comercializados'; break;
                            case 3: $fila->capacidad_criterio = 'Ventas Según Compras de Materia Prima'; break;
                            case 4: $fila->capacidad_criterio = 'Cruce Personalizado'; break;

                            default:
                                
                                break;
                        }
                        
                        switch ((int)$adicional->estacion_sel_arb) {
                            case 1: $fila->estacion_sel_arb = 'Alta'; break;
                            case 2: $fila->estacion_sel_arb = 'Regular'; break;
                            case 3: $fila->estacion_sel_arb = 'Baja'; break;

                            default:
                                break;
                        }
                        
                        $calculo_lead = $this->mfunciones_generales->CalculoLead($fila->prospecto_id, 'ingreso_ponderado');
                        
                        $fila->ingreso_ventas = number_format($calculo_lead->ingreso_mensual_promedio, 2, ',', '.');
                        $fila->costo_ventas = number_format($calculo_lead->costo_ventas, 2, ',', '.');
                        $fila->utilidad_bruta = number_format($calculo_lead->utilidad_bruta, 2, ',', '.');
                        $fila->utilidad_operativa = number_format($calculo_lead->utilidad_operativa, 2, ',', '.');
                        $fila->utilidad_neta = number_format($calculo_lead->utilidad_neta, 2, ',', '.');
                        $fila->saldo_disponible = number_format($calculo_lead->saldo_disponible, 2, ',', '.');
                        $fila->margen_ahorro = number_format($calculo_lead->margen_ahorro, 2, ',', '.');
                    }
                    
                }
            }
        } catch (Exception $ex) {
            $resultado->error = true;
            $resultado->mensaje_error = "Ocurrio un evento inesperado, intentelo mas tarde.";
        }
        
        // se guarda en el resultado el total de filas consultadas
        $resultado->cuenta = count($filas_consultadas);
        $resultado->filas = $filas_consultadas;

        return $resultado;
    }
    
    function Generar_Consulta_SolCred($parametros, $tipo_reporte='') {
        // Se crea el objeto de resultado a devolver
        $resultado = new stdClass();
        $resultado->sin_registros = true;
        $resultado->error = false;
        $resultado->filas = array();
        $resultado->mensaje_error = "";
        $filtros = array();
        
        if (is_null($parametros) || !is_object($parametros)) return $resultado;

        // Parametros de la consulta
        foreach ($parametros->filtros as $f) {
            $datos_filtro = $this->Obtener_Tabla_Campo_Filtro($f->campo);
            if ($datos_filtro == null) continue;
            $f->tabla = $datos_filtro->tabla;
            $f->campo_sql = $datos_filtro->campo;
            $filtros[] = $f;
        }

        // se crea una variable temporal para almacenar el total de las etapas
        // Se buscan las definiciones de los campos a agrupar, y las tablas utilizadas en dicha agrupación

        $tablas_adicionales = array("");

        // Se agregan las tablas relacionadas con los campos de filtro
        foreach ($filtros as $filtro) {
            $tablas_adicionales[] = $filtro->tabla;
        }

        // Se unifican las tablas adicionales (eliminando los duplicados)
        $tablas_adicionales = array_unique($tablas_adicionales);

        // Se definen los campos a devolver por la consulta. Considerando los predeterminados y los usados en los grupos (JRAD CONSULTA)
        $lista_campos = explode("|","solicitud_credito.sol_id as sol_id");

        // Se construye la clausula where según los filtros (JRAD CONSULTA)
        $where = array("1");

        $sw_pivot = 0;
        
        foreach ($filtros as $filtro) {
            
            switch ($filtro->tipo) {
                case "texto":
                    $where[] = (strpos($filtro->campo_sql, "concat_ws") === false)?"(".$filtro->tabla.".".$filtro->campo_sql." like '%".$this->db->escape_str($filtro->valor)."%')"
                        :"(".$filtro->campo_sql." like '%".$this->db->escape_str($filtro->valor)."%')";
                    break;
                case "id":
                    $where[] = "(".$filtro->tabla.".".$filtro->campo_sql." = ".$this->db->escape($filtro->valor).")";
                    break;
                case "booleano":
                case "lista":
                    $where[] = "(".$filtro->tabla.".".$filtro->campo_sql." in (".implode(",",array_map(function ($i) {return $this->db->escape($i);},$filtro->valores))."))";
                    break;
                case "fecha":
                    if ($filtro->tabla == "calendario") {
                        $sql_where_fecha ="";
                        if (!empty($filtro->desde)) {
                            $fecha_desde = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->desde . ' 00:00:01');
                            $sql_where_fecha = " and (".$filtro->tabla.".".$filtro->campo_sql." >= '$fecha_desde')";
                        }
                        if (!empty($filtro->hasta)) {
                            $fecha_hasta = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->hasta . ' 23:59:59');
                            $sql_where_fecha = " and (".$filtro->tabla.".".$filtro->campo_sql." <= '$fecha_hasta')";
                        }
                        $where[] = "((select count(*) from calendario where calendario.cal_id_visita = prospecto.prospecto_id and calendario.cal_tipo_visita = 1$sql_where_fecha) > 0)";
                    } else {
                        if (!empty($filtro->desde)) {
                            $fecha_desde = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->desde . ' 00:00:01');
                            $where[] = "(" . $filtro->tabla . "." . $filtro->campo_sql . " >= '$fecha_desde')";
                        }
                        if (!empty($filtro->hasta)) {
                            $fecha_hasta = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->hasta . ' 23:59:59');
                            $where[] = "(" . $filtro->tabla . "." . $filtro->campo_sql . " <= '$fecha_hasta')";
                        }
                    }
                    break;
            }
            
            if($filtro->campo_sql == 'sol_fecha') { $sw_pivot = 1; }
        }
        
        if(empty($parametros->filtros))
        {
            $resultado->error = true;
            $resultado->mensaje_error = "El Reporte debe contar con filtros agregados, por favor debe Agregar mínimamente el Filtro 'Fecha de Registro' con un rango de tiempo más certero.";
            return $resultado;
        }
        else
        {
            if($sw_pivot == 0)
            {
                $resultado->error = true;
                $resultado->mensaje_error = "Por favor debe Agregar el Filtro 'Fecha de Registro' y un rango de tiempo más certero.";
                return $resultado;
            }
        }
        
        $sql_where = join(" AND ",$where);

        // Se crea el string sql de los campos de la consulta
        $sql_campos = implode(", ",$lista_campos);

        // Se obtiene el string de joins para la consulta sql
        $sql_uniones = $this->Obtener_Uniones_SolCred($tablas_adicionales,"solicitud_credito");

            // Verifica si tiene el permiso para ver todo, caso contrario sólo la región del usuario        
            $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        
        // Se define el filtro sql
        $sql_filtro = "where $sql_where AND codigo_agencia_fie in ($lista_region->region_id)";
        
        // Se define la porción FROM del sql (JRAD CONSULTA)
        $sql_tablas = "solicitud_credito $filtro_region $sql_uniones";

        // Se genera el SQL resultante							(JRAD CONSULTA)
        $sql = "select $sql_campos from $sql_tablas $sql_filtro order by sol_id ASC ";

        // Se ejecuta la consulta SQL
        try {
            
            $consulta = $this->db->query($sql);
            $filas_consultadas = $consulta->result();
            if (count($filas_consultadas)>0) $resultado->sin_registros = false;
        } catch (Exception $ex) {
            $resultado->error = true;
            $resultado->mensaje_error = "Ocurrio un evento inesperado, intentelo mas tarde.";
        }

        if ($resultado->sin_registros) return $resultado;

        // Se buscan los datos adicionales de los documentos (JRAD CONSULTA)
        try {
            $id_solicitud = implode(",",array_map(function ($i) {return $i->sol_id;},$filas_consultadas));
            
            $consulta = $this->db->query("SELECT solicitud_credito.sol_jda_eval, solicitud_credito.sol_desembolso, solicitud_credito.sol_desembolso_monto, ejecutivo.ejecutivo_id, concat_ws(' ',usuarios.usuario_nombres,usuarios.usuario_app,usuarios.usuario_apm) as ejecutivo_nombre, usuarios.usuario_id, solicitud_credito.sol_id, solicitud_credito.sol_codigo_rubro, solicitud_credito.sol_estado, solicitud_credito.sol_asistencia, codigo_agencia_fie, solicitud_credito.sol_fecha, solicitud_credito.sol_asignado, solicitud_credito.sol_asignado_usuario, solicitud_credito.sol_asignado_fecha, solicitud_credito.sol_registro_completado_fecha, solicitud_credito.sol_consolidado, solicitud_credito.sol_consolidado_usuario, solicitud_credito.sol_consolidado_fecha, solicitud_credito.sol_consolidado_geo, solicitud_credito.sol_checkin, solicitud_credito.sol_checkin_fecha, solicitud_credito.sol_checkin_geo, solicitud_credito.sol_llamada, solicitud_credito.sol_llamada_fecha, solicitud_credito.sol_llamada_geo, solicitud_credito.sol_fecha_conclusion, solicitud_credito.sol_evaluacion, solicitud_credito.sol_observado_app, solicitud_credito.sol_ultimo_paso, solicitud_credito.sol_estudio, solicitud_credito.sol_estudio_codigo, solicitud_credito.sol_rechazado, solicitud_credito.sol_rechazado_usuario, solicitud_credito.sol_rechazado_fecha, solicitud_credito.sol_rechazado_texto, solicitud_credito.sol_ci, solicitud_credito.sol_extension, solicitud_credito.sol_complemento, solicitud_credito.sol_primer_nombre, solicitud_credito.sol_segundo_nombre, solicitud_credito.sol_primer_apellido, solicitud_credito.sol_segundo_apellido, solicitud_credito.sol_correo, solicitud_credito.sol_cel, solicitud_credito.sol_telefono, solicitud_credito.sol_fec_nac, solicitud_credito.sol_est_civil, solicitud_credito.sol_nit, solicitud_credito.sol_cliente, solicitud_credito.sol_dependencia, solicitud_credito.sol_indepen_actividad, solicitud_credito.sol_indepen_ant_ano, solicitud_credito.sol_indepen_ant_mes, solicitud_credito.sol_indepen_horario_desde, solicitud_credito.sol_indepen_horario_hasta, solicitud_credito.sol_indepen_horario_dias, solicitud_credito.sol_indepen_telefono, solicitud_credito.sol_depen_empresa, solicitud_credito.sol_depen_actividad, solicitud_credito.sol_depen_cargo, solicitud_credito.sol_depen_ant_ano, solicitud_credito.sol_depen_ant_mes, solicitud_credito.sol_depen_horario_desde, solicitud_credito.sol_depen_horario_hasta, solicitud_credito.sol_depen_horario_dias, solicitud_credito.sol_depen_telefono, solicitud_credito.sol_depen_tipo_contrato, solicitud_credito.sol_monto, solicitud_credito.sol_plazo, solicitud_credito.sol_moneda, solicitud_credito.sol_detalle, solicitud_credito.sol_dir_departamento, solicitud_credito.sol_dir_provincia, solicitud_credito.sol_dir_localidad_ciudad, solicitud_credito.sol_cod_barrio, solicitud_credito.sol_direccion_trabajo, solicitud_credito.sol_edificio_trabajo, solicitud_credito.sol_numero_trabajo, solicitud_credito.sol_dir_referencia, solicitud_credito.sol_geolocalizacion, solicitud_credito.sol_trabajo_lugar, solicitud_credito.sol_trabajo_lugar_otro, solicitud_credito.sol_trabajo_realiza, solicitud_credito.sol_trabajo_realiza_otro, solicitud_credito.sol_trabajo_actividad_pertenece, solicitud_credito.sol_dir_departamento_dom, solicitud_credito.sol_dir_provincia_dom, solicitud_credito.sol_dir_localidad_ciudad_dom, solicitud_credito.sol_cod_barrio_dom, solicitud_credito.sol_direccion_dom, solicitud_credito.sol_edificio_dom, solicitud_credito.sol_numero_dom, solicitud_credito.sol_dir_referencia_dom, solicitud_credito.sol_geolocalizacion_dom, solicitud_credito.sol_dom_tipo, solicitud_credito.sol_dom_tipo_otro, solicitud_credito.sol_conyugue, solicitud_credito.sol_con_ci, solicitud_credito.sol_con_extension, solicitud_credito.sol_con_complemento, solicitud_credito.sol_con_primer_nombre, solicitud_credito.sol_con_segundo_nombre, solicitud_credito.sol_con_primer_apellido, solicitud_credito.sol_con_segundo_apellido, solicitud_credito.sol_con_correo, solicitud_credito.sol_con_cel, solicitud_credito.sol_con_telefono, solicitud_credito.sol_con_fec_nac, solicitud_credito.sol_con_est_civil, solicitud_credito.sol_con_nit, solicitud_credito.sol_con_cliente, solicitud_credito.sol_con_dependencia, solicitud_credito.sol_con_indepen_actividad, solicitud_credito.sol_con_indepen_ant_ano, solicitud_credito.sol_con_indepen_ant_mes, solicitud_credito.sol_con_indepen_horario_desde, solicitud_credito.sol_con_indepen_horario_hasta, solicitud_credito.sol_con_indepen_horario_dias, solicitud_credito.sol_con_indepen_telefono, solicitud_credito.sol_con_depen_empresa, solicitud_credito.sol_con_depen_actividad, solicitud_credito.sol_con_depen_cargo, solicitud_credito.sol_con_depen_ant_ano, solicitud_credito.sol_con_depen_ant_mes, solicitud_credito.sol_con_depen_horario_desde, solicitud_credito.sol_con_depen_horario_hasta, solicitud_credito.sol_con_depen_horario_dias, solicitud_credito.sol_con_depen_telefono, solicitud_credito.sol_con_depen_tipo_contrato, solicitud_credito.sol_con_dir_departamento, solicitud_credito.sol_con_dir_provincia, solicitud_credito.sol_con_dir_localidad_ciudad, solicitud_credito.sol_con_cod_barrio, solicitud_credito.sol_con_direccion_trabajo, solicitud_credito.sol_con_edificio_trabajo, solicitud_credito.sol_con_numero_trabajo, solicitud_credito.sol_con_dir_referencia, solicitud_credito.sol_con_geolocalizacion, solicitud_credito.sol_con_trabajo_lugar, solicitud_credito.sol_con_trabajo_lugar_otro, solicitud_credito.sol_con_trabajo_realiza, solicitud_credito.sol_con_trabajo_realiza_otro, solicitud_credito.sol_con_trabajo_actividad_pertenece, solicitud_credito.sol_con_dir_departamento_dom, solicitud_credito.sol_con_dir_provincia_dom, solicitud_credito.sol_con_dir_localidad_ciudad_dom, solicitud_credito.sol_con_cod_barrio_dom, solicitud_credito.sol_con_direccion_dom, solicitud_credito.sol_con_edificio_dom, solicitud_credito.sol_con_numero_dom, solicitud_credito.sol_con_dir_referencia_dom, solicitud_credito.sol_con_geolocalizacion_dom, solicitud_credito.sol_con_dom_tipo, solicitud_credito.sol_con_dom_tipo_otro FROM solicitud_credito INNER JOIN ejecutivo ON ejecutivo.ejecutivo_id=solicitud_credito.ejecutivo_id INNER JOIN usuarios ON usuarios.usuario_id=ejecutivo.usuario_id WHERE solicitud_credito.sol_id IN ($id_solicitud)");
            
            $this->load->model('mfunciones_microcreditos');
            
            $filas_datos_adicionales =$consulta->result();
            // Se unen los datos adicionales
            foreach ($filas_consultadas as &$fila) {
                
                foreach ($filas_datos_adicionales as $adicional) {
					
                            // Siempre se verifica que tenga coincidencia (JRAD CONSULTA)
                    if ($adicional->sol_id != $fila->sol_id) continue;
                    
                    $aux_datos_agencia = $this->mfunciones_microcreditos->ObtenerDatosRegionCodigo($adicional->codigo_agencia_fie);
                    
                    $fila->codigo_agencia_fie = $aux_datos_agencia->nombre;
                    $fila->ejecutivo_nombre = ($adicional->sol_asignado==0 ? 'No Asignado' : $adicional->ejecutivo_nombre);
                    $fila->sol_ci = $adicional->sol_ci . ' ' . $adicional->sol_complemento . ' ' . ((int)$adicional->sol_extension==-1 ? '' : $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_extension, 'cI_lugar_emisionoextension'));
                    $fila->sol_nombre_completo = $adicional->sol_primer_nombre . ' ' . $adicional->sol_segundo_nombre . ' ' . $adicional->sol_primer_apellido . ' ' . $adicional->sol_segundo_apellido;
                    $fila->sol_correo = $adicional->sol_correo;
                    $fila->sol_cel = $adicional->sol_cel;
                    
                    $fila->sol_detalle = $adicional->sol_detalle;
                    $fila->sol_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->sol_fecha);
                    $fila->sol_estado = $this->mfunciones_microcreditos->GetValorCatalogo($adicional->sol_estado, 'solicitud_estado');
                    
                    $fila->sol_consolidado_codigo = $adicional->sol_consolidado;
                    
                    $fila->sol_codigo_rubro = $this->mfunciones_microcreditos->GetValorCatalogo($adicional->sol_codigo_rubro, 'aux_rubro');
                    
                    $fila->sol_monto = ($adicional->sol_moneda=='' ? '' : $this->mfunciones_microcreditos->GetValorCatalogo($adicional->sol_moneda, 'sol_moneda')) . ' ' . number_format($adicional->sol_monto, 2, ',', '.');
                    
                    if((int)$adicional->sol_desembolso == 1)
                    {
                        $fila->sol_estado_aux = 'Desembolsado COBIS';
                    }
                    else
                    {
                        $fila->sol_estado_aux = ((int)$adicional->sol_jda_eval<=0 ? '' : 'JDA ' . $this->mfunciones_generales->GetValorCatalogo($adicional->sol_jda_eval, 'prospecto_evaluacion'));
                    }
                    
                    if($fila->sol_estado_aux != '')
                    {
                        $fila->sol_estado_aux = '<br /><i>' . $fila->sol_estado_aux . '</i>';
                    }
                    
                    if($parametros->opcionResultado == 'pivot')
                    {
                        $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
                        
                        $fila->agencia_departamento = $aux_datos_agencia->departamento;
                        $fila->agencia_provincia = $aux_datos_agencia->provincia;
                        $fila->agencia_ciudad = $aux_datos_agencia->ciudad;
                        
                        $fila->sol_asistencia = $this->mfunciones_generales->GetValorCatalogo($adicional->sol_asistencia, 'tercero_asistencia');
                        
                        $fila->sol_plazo = $adicional->sol_plazo;
                        $fila->sol_moneda = $this->mfunciones_microcreditos->GetValorCatalogo($adicional->sol_moneda, 'sol_moneda');
                        $fila->sol_monto_valor = number_format($adicional->sol_monto, 2, ',', '.');
                        $fila->sol_monto_bs = number_format(($adicional->sol_moneda=='bob' ? $adicional->sol_monto : $adicional->sol_monto*$arrConf[0]['conf_credito_tipo_cambio']), 2, ',', '.');
                        
                        $fila->sol_asignado = $this->mfunciones_generales->GetValorCatalogo($adicional->sol_asignado, 'si_no');
                        $fila->sol_asignado_usuario = $this->mfunciones_generales->getNombreUsuario($adicional->sol_asignado_usuario);
                        $fila->sol_asignado_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->sol_asignado_fecha);
                        $fila->sol_registro_completado_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->sol_registro_completado_fecha);
                        $fila->sol_consolidado = $this->mfunciones_generales->GetValorCatalogo($adicional->sol_consolidado, 'si_no');
                        $fila->sol_consolidado_usuario = $this->mfunciones_generales->getNombreUsuario($adicional->sol_consolidado_usuario);
                        $fila->sol_consolidado_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->sol_consolidado_fecha);
                        $fila->sol_consolidado_geo = $adicional->sol_consolidado_geo;
                        $fila->sol_rechazado = $this->mfunciones_generales->GetValorCatalogo($adicional->sol_rechazado, 'si_no');
                        $fila->sol_rechazado_usuario = $this->mfunciones_generales->getNombreUsuario($adicional->sol_rechazado_usuario);
                        $fila->sol_rechazado_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->sol_rechazado_fecha);
                        $fila->sol_rechazado_texto = $adicional->sol_rechazado_texto;
                        $fila->sol_checkin = $this->mfunciones_generales->GetValorCatalogo($adicional->sol_checkin, 'si_no');
                        $fila->sol_checkin_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->sol_checkin_fecha);
                        $fila->sol_checkin_geo = $adicional->sol_checkin_geo;
                        $fila->sol_llamada = $this->mfunciones_generales->GetValorCatalogo($adicional->sol_llamada, 'si_no');
                        $fila->sol_llamada_fecha = $this->mfunciones_generales->pivotFormatoFechaD_M_Y_H_M($adicional->sol_llamada_fecha);
                        $fila->sol_llamada_geo = $adicional->sol_llamada_geo;
                        $fila->sol_evaluacion = $this->mfunciones_generales->GetValorCatalogo((int)$adicional->sol_evaluacion, 'prospecto_evaluacion');
                        
                        $fila->sol_estudio = $this->mfunciones_generales->GetValorCatalogo($adicional->sol_estudio, 'si_no');
                        $fila->sol_estudio_codigo = ($adicional->sol_estudio==0 ? '' : PREFIJO_PROSPECTO . $adicional->sol_estudio_codigo);
                        $fila->sol_estudio_rubro = ($adicional->sol_estudio==0 ? '' : $this->mfunciones_generales->GetValorCatalogo($adicional->sol_con_trabajo_actividad_pertenece, 'nombre_rubro'));
                        
                        $fila->sol_ci = $adicional->sol_ci;
                        $fila->sol_extension = ((int)$adicional->sol_extension==-1 ? '' : $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_extension, 'cI_lugar_emisionoextension'));
                        $fila->sol_complemento = $adicional->sol_complemento;
                        $fila->sol_primer_nombre = $adicional->sol_primer_nombre;
                        $fila->sol_segundo_nombre = $adicional->sol_segundo_nombre;
                        $fila->sol_primer_apellido = $adicional->sol_primer_apellido;
                        $fila->sol_segundo_apellido = $adicional->sol_segundo_apellido;
                        $fila->sol_correo = $adicional->sol_correo;
                        $fila->sol_cel = $adicional->sol_cel;
                        $fila->sol_telefono = $adicional->sol_telefono;
                        $fila->sol_fec_nac = $this->mfunciones_generales->pivotFormatoFechaD_M_Y($adicional->sol_fec_nac);
                        $fila->sol_est_civil = $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_est_civil, 'di_estadocivil');
                        $fila->sol_nit = $adicional->sol_nit;
                        $fila->sol_cliente = $this->mfunciones_microcreditos->GetValorCatalogo($adicional->sol_cliente, 'sol_cliente');
                        $fila->sol_dependencia = $this->mfunciones_microcreditos->GetValorCatalogo($adicional->sol_dependencia, 'sol_dependencia');
                        $fila->sol_indepen_actividad = $adicional->sol_indepen_actividad;
                        $fila->sol_indepen_antiguedad = $adicional->sol_indepen_ant_ano . ' año(s) y ' . $adicional->sol_indepen_ant_mes . ' mes(es)';
                        $fila->sol_indepen_atencion = 'De ' . $adicional->sol_indepen_horario_desde . ' a ' . $adicional->sol_indepen_horario_hasta;
                        $fila->sol_indepen_horario_dias = $this->mfunciones_microcreditos->GetDiasAtencionCorto($adicional->sol_indepen_horario_dias);
                        $fila->sol_indepen_telefono = $adicional->sol_indepen_telefono;
                        
                        $fila->sol_depen_empresa = $adicional->sol_depen_empresa;
                        $fila->sol_depen_actividad = $adicional->sol_depen_actividad;
                        $fila->sol_depen_cargo = $adicional->sol_depen_cargo;
                        $fila->sol_depen_antiguedad = $adicional->sol_depen_ant_ano . ' año(s) y ' . $adicional->sol_depen_ant_mes . ' mes(es)';
                        $fila->sol_depen_atencion = 'De ' . $adicional->sol_depen_horario_desde . ' a ' . $adicional->sol_depen_horario_hasta;
                        $fila->sol_depen_horario_dias = $this->mfunciones_microcreditos->GetDiasAtencionCorto($adicional->sol_depen_horario_dias);
                        $fila->sol_depen_telefono = $adicional->sol_depen_telefono;
                        $fila->sol_depen_tipo_contrato = $adicional->sol_depen_tipo_contrato;
                        
                        $fila->sol_dir_departamento = $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_dir_departamento, 'dir_departamento');
                        $fila->sol_dir_provincia = $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_dir_provincia, 'dir_provincia');
                        $fila->sol_dir_localidad_ciudad = $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_dir_localidad_ciudad, 'dir_localidad_ciudad');
                        $fila->sol_cod_barrio = $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_cod_barrio, 'dir_barrio_zona_uv');
                        $fila->sol_direccion_trabajo = $adicional->sol_direccion_trabajo;
                        $fila->sol_edificio_trabajo = $adicional->sol_edificio_trabajo;
                        $fila->sol_numero_trabajo = $adicional->sol_numero_trabajo;
                        $fila->sol_trabajo_lugar = ($adicional->sol_trabajo_lugar==99 ? 'Otro: ' . $adicional->sol_trabajo_lugar_otro : $this->mfunciones_microcreditos->GetValorCatalogo($adicional->sol_trabajo_lugar, 'sol_trabajo_lugar'));
                        $fila->sol_trabajo_realiza = ($adicional->sol_trabajo_realiza==99 ? 'Otro: ' . $adicional->sol_trabajo_realiza_otro : $this->mfunciones_microcreditos->GetValorCatalogo($adicional->sol_trabajo_realiza, 'sol_trabajo_realiza'));
                        $fila->sol_dir_referencia = $this->mfunciones_microcreditos->GetValorCatalogo($adicional->sol_dir_referencia, 'sol_referencia');
                        
                        $fila->sol_dir_departamento_dom = $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_dir_departamento_dom, 'dir_departamento');
                        $fila->sol_dir_provincia_dom = $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_dir_provincia_dom, 'dir_provincia');
                        $fila->sol_dir_localidad_ciudad_dom = $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_dir_localidad_ciudad_dom, 'dir_localidad_ciudad');
                        $fila->sol_cod_barrio_dom = $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_cod_barrio_dom, 'dir_barrio_zona_uv');
                        $fila->sol_direccion_dom = $adicional->sol_direccion_dom;
                        $fila->sol_edificio_dom = $adicional->sol_edificio_dom;
                        $fila->sol_numero_dom = $adicional->sol_numero_dom;
                        $fila->sol_dom_tipo = ($adicional->sol_dom_tipo==99 ? 'Otro: ' . $adicional->sol_dom_tipo_otro : $this->mfunciones_microcreditos->GetValorCatalogo($adicional->sol_dom_tipo, 'sol_dom_tipo'));
                        $fila->sol_dir_referencia_dom = $this->mfunciones_microcreditos->GetValorCatalogo($adicional->sol_dir_referencia_dom, 'sol_referencia');
                        
                        // -------------
                        
                        $fila->sol_conyugue = $this->mfunciones_generales->GetValorCatalogo($adicional->sol_conyugue, 'si_no');
                        
                        // -------------
                        
                        $fila->sol_con_ci = $adicional->sol_con_ci;
                        $fila->sol_con_extension = ((int)$adicional->sol_con_extension==-1 ? '' : $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_con_extension, 'cI_lugar_emisionoextension'));
                        $fila->sol_con_complemento = $adicional->sol_con_complemento;
                        $fila->sol_con_primer_nombre = $adicional->sol_con_primer_nombre;
                        $fila->sol_con_segundo_nombre = $adicional->sol_con_segundo_nombre;
                        $fila->sol_con_primer_apellido = $adicional->sol_con_primer_apellido;
                        $fila->sol_con_segundo_apellido = $adicional->sol_con_segundo_apellido;
                        $fila->sol_con_correo = $adicional->sol_con_correo;
                        $fila->sol_con_cel = $adicional->sol_con_cel;
                        $fila->sol_con_telefono = $adicional->sol_con_telefono;
                        $fila->sol_con_fec_nac = $this->mfunciones_generales->pivotFormatoFechaD_M_Y($adicional->sol_con_fec_nac);
                        $fila->sol_con_est_civil = $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_con_est_civil, 'di_estadocivil');
                        $fila->sol_con_nit = $adicional->sol_con_nit;
                        $fila->sol_con_cliente = $this->mfunciones_microcreditos->GetValorCatalogo($adicional->sol_con_cliente, 'sol_con_cliente');
                        $fila->sol_con_dependencia = $this->mfunciones_microcreditos->GetValorCatalogo($adicional->sol_con_dependencia, 'sol_con_dependencia');
                        $fila->sol_con_indepen_actividad = $adicional->sol_con_indepen_actividad;
                        $fila->sol_con_indepen_antiguedad = $adicional->sol_con_indepen_ant_ano . ' año(s) y ' . $adicional->sol_con_indepen_ant_mes . ' mes(es)';
                        $fila->sol_con_indepen_atencion = 'De ' . $adicional->sol_con_indepen_horario_desde . ' a ' . $adicional->sol_con_indepen_horario_hasta;
                        $fila->sol_con_indepen_horario_dias = $this->mfunciones_microcreditos->GetDiasAtencionCorto($adicional->sol_con_indepen_horario_dias);
                        $fila->sol_con_indepen_telefono = $adicional->sol_con_indepen_telefono;
                        
                        $fila->sol_con_depen_empresa = $adicional->sol_con_depen_empresa;
                        $fila->sol_con_depen_actividad = $adicional->sol_con_depen_actividad;
                        $fila->sol_con_depen_cargo = $adicional->sol_con_depen_cargo;
                        $fila->sol_con_depen_antiguedad = $adicional->sol_con_depen_ant_ano . ' año(s) y ' . $adicional->sol_con_depen_ant_mes . ' mes(es)';
                        $fila->sol_con_depen_atencion = 'De ' . $adicional->sol_con_depen_horario_desde . ' a ' . $adicional->sol_con_depen_horario_hasta;
                        $fila->sol_con_depen_horario_dias = $this->mfunciones_microcreditos->GetDiasAtencionCorto($adicional->sol_con_depen_horario_dias);
                        $fila->sol_con_depen_telefono = $adicional->sol_con_depen_telefono;
                        $fila->sol_con_depen_tipo_contrato = $adicional->sol_con_depen_tipo_contrato;
                        
                        $fila->sol_con_dir_departamento = $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_con_dir_departamento, 'dir_departamento');
                        $fila->sol_con_dir_provincia = $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_con_dir_provincia, 'dir_provincia');
                        $fila->sol_con_dir_localidad_ciudad = $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_con_dir_localidad_ciudad, 'dir_localidad_ciudad');
                        $fila->sol_con_cod_barrio = $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_con_cod_barrio, 'dir_barrio_zona_uv');
                        $fila->sol_con_direccion_trabajo = $adicional->sol_con_direccion_trabajo;
                        $fila->sol_con_edificio_trabajo = $adicional->sol_con_edificio_trabajo;
                        $fila->sol_con_numero_trabajo = $adicional->sol_con_numero_trabajo;
                        $fila->sol_con_trabajo_lugar = ($adicional->sol_con_trabajo_lugar==99 ? 'Otro: ' . $adicional->sol_con_trabajo_lugar_otro : $this->mfunciones_microcreditos->GetValorCatalogo($adicional->sol_con_trabajo_lugar, 'sol_con_trabajo_lugar'));
                        $fila->sol_con_trabajo_realiza = ($adicional->sol_con_trabajo_realiza==99 ? 'Otro: ' . $adicional->sol_con_trabajo_realiza_otro : $this->mfunciones_microcreditos->GetValorCatalogo($adicional->sol_con_trabajo_realiza, 'sol_con_trabajo_realiza'));
                        $fila->sol_con_dir_referencia = $this->mfunciones_microcreditos->GetValorCatalogo($adicional->sol_con_dir_referencia, 'sol_referencia');
                        
                        $fila->sol_con_dir_departamento_dom = $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_con_dir_departamento_dom, 'dir_departamento');
                        $fila->sol_con_dir_provincia_dom = $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_con_dir_provincia_dom, 'dir_provincia');
                        $fila->sol_con_dir_localidad_ciudad_dom = $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_con_dir_localidad_ciudad_dom, 'dir_localidad_ciudad');
                        $fila->sol_con_cod_barrio_dom = $this->mfunciones_generales->GetValorCatalogoDB($adicional->sol_con_cod_barrio_dom, 'dir_barrio_zona_uv');
                        $fila->sol_con_direccion_dom = $adicional->sol_con_direccion_dom;
                        $fila->sol_con_edificio_dom = $adicional->sol_con_edificio_dom;
                        $fila->sol_con_numero_dom = $adicional->sol_con_numero_dom;
                        $fila->sol_con_dom_tipo = ($adicional->sol_con_dom_tipo==99 ? 'Otro: ' . $adicional->sol_con_dom_tipo_otro : $this->mfunciones_microcreditos->GetValorCatalogo($adicional->sol_con_dom_tipo, 'sol_con_dom_tipo'));
                        $fila->sol_con_dir_referencia_dom = $this->mfunciones_microcreditos->GetValorCatalogo($adicional->sol_con_dir_referencia_dom, 'sol_referencia');
                        
                    }
                    
                }
            }
        } catch (Exception $ex) {
            $resultado->error = true;
            $resultado->mensaje_error = "Ocurrio un evento inesperado, intentelo mas tarde.";
        }
        

        // se guarda en el resultado el total de filas consultadas
        $resultado->cuenta = count($filas_consultadas);
        $resultado->filas = $filas_consultadas;

        return $resultado;
    }
    
    function Generar_Consulta_Mantenimiento($parametros) {
        // Se crea el objeto de resultado a devolver
        $resultado = new stdClass();
        $resultado->sin_registros = true;
        $resultado->error = false;
        $resultado->filas = array();
        $resultado->mensaje_error = "";
        $filtros = array();

        if (is_null($parametros) || !is_object($parametros)) return $resultado;

        // Parametros de la consulta
        foreach ($parametros->filtros as $f) {
            $datos_filtro = $this->Obtener_Tabla_Campo_Filtro($f->campo);
            if ($datos_filtro == null) continue;
            $f->tabla = $datos_filtro->tabla;
            $f->campo_sql = $datos_filtro->campo;
            $filtros[] = $f;
        }

        // se crea una variable temporal para almacenar el total de las etapas
        // Se buscan las definiciones de los campos a agrupar, y las tablas utilizadas en dicha agrupación
        
        $tablas_adicionales = array("");

        // Se agregan las tablas relacionadas con los campos de filtro
        foreach ($filtros as $filtro) {
            $tablas_adicionales[] = $filtro->tabla;
        }

        // Se unifican las tablas adicionales (eliminando los duplicados)
        $tablas_adicionales = array_unique($tablas_adicionales);

        // Se definen los campos a devolver por la consulta. Considerando los predeterminados y los usados en los grupos (JRAD CONSULTA)
        $lista_campos = explode("|","mantenimiento.mant_id as mant_id|mantenimiento.mant_fecha_asignacion|mantenimiento.mant_estado");

        // Se construye la clausula where según los filtros (JRAD CONSULTA)
        $where = array("1");

        foreach ($filtros as $filtro) {
            switch ($filtro->tipo) {
                case "texto":
                    $where[] = (strpos($filtro->campo_sql, "concat_ws") === false)?"(".$filtro->tabla.".".$filtro->campo_sql." like '%".$this->db->escape_str($filtro->valor)."%')"
                        :"(".$filtro->campo_sql." like '%".$this->db->escape_str($filtro->valor)."%')";
                    break;
                case "id":
                    $where[] = "(".$filtro->tabla.".".$filtro->campo_sql." = ".$this->db->escape($filtro->valor).")";
                    break;
                case "booleano":
                case "lista":
                    $where[] = "(".$filtro->tabla.".".$filtro->campo_sql." in (".implode(",",array_map(function ($i) {return $this->db->escape($i);},$filtro->valores))."))";
                    break;
                case "fecha":
                    if ($filtro->tabla == "calendario") {
                        $sql_where_fecha ="";
                        if (!empty($filtro->desde)) {
                            $fecha_desde = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->desde . ' 00:00:01');
                            $sql_where_fecha = " and (".$filtro->tabla.".".$filtro->campo_sql." >= '$fecha_desde')";
                        }
                        if (!empty($filtro->hasta)) {
                            $fecha_hasta = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->hasta . ' 23:59:59');
                            $sql_where_fecha = " and (".$filtro->tabla.".".$filtro->campo_sql." <= '$fecha_hasta')";
                        }
                        $where[] = "((select count(*) from calendario where calendario.cal_id_visita = mantenimiento.mant_id and calendario.cal_tipo_visita = 2$sql_where_fecha) > 0)";
                    } else {
                        if (!empty($filtro->desde)) {
                            $fecha_desde = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->desde . ' 00:00:01');
                            $where[] = "(" . $filtro->tabla . "." . $filtro->campo_sql . " >= '$fecha_desde')";
                        }
                        if (!empty($filtro->hasta)) {
                            $fecha_hasta = $this->mfunciones_generales->getFormatoFechaDateTime($filtro->hasta . ' 23:59:59');
                            $where[] = "(" . $filtro->tabla . "." . $filtro->campo_sql . " <= '$fecha_hasta')";
                        }
                    }
                    break;
            }
        }

        $sql_where = join(" AND ",$where);

        // Se crea el string sql de los campos de la consulta
        $sql_campos = implode(", ",$lista_campos);

        // Se obtiene el string de joins para la consulta sql
        $sql_uniones = $this->Obtener_Uniones_Mantenimiento($tablas_adicionales,"mantenimiento");

        // Se define el filtro sql
        $sql_filtro = "where $sql_where";

            // Verifica si tiene el permiso para ver todo, caso contrario sólo la región del usuario        
            $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
            $filtro_region = "inner join ejecutivo ej on ej.ejecutivo_id = mantenimiento.ejecutivo_id inner join usuarios u on u.usuario_id = ej.usuario_id inner join estructura_agencia ea on ea.estructura_agencia_id = u.estructura_agencia_id inner join estructura_regional er on er.estructura_regional_id = ea.estructura_regional_id AND (er.estructura_regional_id in ($lista_region->region_id)) INNER JOIN rol r ON r.rol_id=u.usuario_rol INNER JOIN perfil_app pa ON pa.perfil_app_id=r.perfil_app_id AND pa.perfil_app_id=" . $parametros->perfil_app . " ";
        
        // Se define la porción FROM del sql (JRAD CONSULTA)
        $sql_tablas = "mantenimiento $filtro_region $sql_uniones";

        // Se genera el SQL resultante							(JRAD CONSULTA)
        $sql = "select $sql_campos from $sql_tablas $sql_filtro order by mant_id ASC ";

        // Se ejecuta la consulta SQL
        try {
            $consulta = $this->db->query($sql);
            $filas_consultadas = $consulta->result();
            if (count($filas_consultadas)>0) $resultado->sin_registros = false;
        } catch (Exception $ex) {
            $resultado->error = true;
            $resultado->mensaje_error = "Ocurrio un evento inesperado, intentelo mas tarde.";
        }

        if ($resultado->sin_registros) return $resultado;

        // Se buscan los datos adicionales de los documentos (JRAD CONSULTA)
        try {
            $id_mantenimiento = implode(",",array_map(function ($i) {return $i->mant_id;},$filas_consultadas));
            
            $consulta = $this->db->query("SELECT mantenimiento.mant_id as mant_id, mantenimiento.empresa_id, empresa.empresa_categoria as 'empresa_codigo', CASE empresa.empresa_categoria WHEN 1 then IF(STRCMP(empresa.empresa_nombre_fantasia, '') = 0, empresa.empresa_nombre_legal, empresa.empresa_nombre_fantasia) WHEN 2 then empresa.empresa_nombre_establecimiento END AS 'empresa_nombre', CASE empresa.empresa_categoria WHEN 1 then 'Comercio' WHEN 2 then 'Establecimiento' END AS 'empresa_categoria', concat_ws(' ',usuarios.usuario_nombres,usuarios.usuario_app,usuarios.usuario_apm) as ejecutivo_nombre FROM mantenimiento INNER JOIN empresa ON empresa.empresa_id=mantenimiento.empresa_id INNER JOIN ejecutivo ON ejecutivo.ejecutivo_id=mantenimiento.ejecutivo_id INNER JOIN usuarios ON usuarios.usuario_id=ejecutivo.usuario_id WHERE mantenimiento.mant_id IN ($id_mantenimiento)");
            $filas_datos_adicionales =$consulta->result();
            // Se unen los datos adicionales
            foreach ($filas_consultadas as &$fila) {
                $fila->documento_nombre = "";
                $fila->ejecutivo_nombre = "";
                $fila->ciudad = "";
                foreach ($filas_datos_adicionales as $adicional) {
					
                            // Siempre se verifica que tenga coincidencia (JRAD CONSULTA)
                    if ($adicional->mant_id != $fila->mant_id) continue;
                    $fila->empresa_nombre = $adicional->empresa_nombre;
                    $fila->empresa_categoria = $adicional->empresa_categoria;
                    $fila->ejecutivo_nombre = $adicional->ejecutivo_nombre;
                }
            }
        } catch (Exception $ex) {
            $resultado->error = true;
            $resultado->mensaje_error = "Ocurrio un evento inesperado, intentelo mas tarde.";
        }
        

        // se guarda en el resultado el total de filas consultadas
        $resultado->cuenta = count($filas_consultadas);
        $resultado->filas = $filas_consultadas;

        return $resultado;
    }

    private function Obtener_Nombre_Categoria($categoria_id) {
        switch ($categoria_id) {
            case "0": return "Pre - Afiliación EA";
            case "1": return "Estado de Operación del Lead";
            case "2": return "Acciones Específicas";
            case "3": return "Notificación Rechazo";
            case "5": return "Onboarding";
        }
        return "";
    }

    /**
     * Busca el nivel en el arbol de una etapa especifica
     * @param $etapa_actual
     * @param $etapas_consultadas
     * @param $nivel
     * @return mixed
     */
    private function Buscar_Nivel_Etapa(&$etapa_actual,&$etapas_consultadas, $nivel) {
        if ($etapa_actual->etapa_id == $etapa_actual->etapa_depende || $nivel > Mfunciones_reporte::MAXIMA_RECURSION) return $nivel;
        foreach ($etapas_consultadas as $etapa_iteracion) {
            if ($etapa_iteracion->etapa_id == $etapa_actual->etapa_depende) {
                if (property_exists($etapa_iteracion, 'nivel')) return $etapa_iteracion->nivel+1;
                return $this->Buscar_Nivel_Etapa($etapa_iteracion, $etapas_consultadas, $nivel + 1);
            }
        }
        return $nivel;
    }

    /**
     * Calcula el numero de horas laborales en el periodo especificado
     * @param $fecha_inicio
     * @param $fecha_fin
     * @return int
     */
    private function Calcula_Horas_Laborales($fecha_inicio, $fecha_fin) {
        $inicio = date('Y-m-d H:i:s', strtotime($fecha_inicio));
        $fin = date('Y-m-d H:i:s', strtotime($fecha_fin));
        return $this->mfunciones_generales->getDiasLaboralesCache($inicio,$fin);
    }

    /**
     * Devuelve un string vacío o una string con el listado de joins empezando y separados por espacios
     * @param $tablas_adicionales
     * @return string
     */
    private function Obtener_Uniones($tablas_adicionales,$tabla_union_prospecto) {
        if (count($tablas_adicionales)<=0) return "";
        $sql= array();
        if ($this->Match_Tablas(array("prospecto","ejecutivo","usuarios","estructura_agencia","estructura_regional","estructura_entidad","empresa","calendario","servicio","actividades"),$tablas_adicionales)) $sql[] = "inner join prospecto on prospecto.prospecto_id = $tabla_union_prospecto.prospecto_id";
        if ($this->Match_Tablas(array("ejecutivo","usuarios","estructura_agencia","estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join ejecutivo on ejecutivo.ejecutivo_id = prospecto.ejecutivo_id";
        if ($this->Match_Tablas(array("usuarios","estructura_agencia","estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join usuarios on usuarios.usuario_id = ejecutivo.usuario_id";
        if ($this->Match_Tablas(array("estructura_agencia","estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join estructura_agencia on estructura_agencia.estructura_agencia_id = usuarios.estructura_agencia_id";
        if ($this->Match_Tablas(array("estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join estructura_regional on estructura_regional.estructura_regional_id = estructura_agencia.estructura_regional_id";
        if ($this->Match_Tablas(array("estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join estructura_entidad on estructura_entidad.estructura_entidad_id = estructura_regional.estructura_entidad_id";
        if ($this->Match_Tablas(array("empresa"),$tablas_adicionales)) $sql[] = "inner join empresa on empresa.empresa_id = prospecto.empresa_id";
        if ($this->Match_Tablas(array("servicio"),$tablas_adicionales)) $sql[] = "inner join prospecto_servicio ON prospecto_servicio.prospecto_id=prospecto.prospecto_id inner join servicio ON servicio.servicio_id=prospecto_servicio.servicio_id";
        if ($this->Match_Tablas(array("actividades"),$tablas_adicionales)) $sql[] = "inner join prospecto_actividades ON prospecto_actividades.prospecto_id=prospecto.prospecto_id inner join actividades ON actividades.act_id=prospecto_actividades.act_id";
        if (count($sql)==0) return "";
        return " ".implode(" ", $sql);
    }
    
    private function Obtener_Uniones_Documento($tablas_adicionales,$tabla_union_prospecto) {
        if (count($tablas_adicionales)<=0) return "";
        $sql= array();
        if ($this->Match_Tablas(array("prospecto","ejecutivo","usuarios","estructura_agencia","estructura_regional","estructura_entidad","empresa","calendario","servicio","actividades"),$tablas_adicionales)) $sql[] = "inner join prospecto on prospecto.prospecto_id = $tabla_union_prospecto.prospecto_id";
        if ($this->Match_Tablas(array("ejecutivo","usuarios","estructura_agencia","estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join ejecutivo on ejecutivo.ejecutivo_id = prospecto.ejecutivo_id";
        if ($this->Match_Tablas(array("usuarios","estructura_agencia","estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join usuarios on usuarios.usuario_id = ejecutivo.usuario_id";
        if ($this->Match_Tablas(array("estructura_agencia","estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join estructura_agencia on estructura_agencia.estructura_agencia_id = usuarios.estructura_agencia_id";
        if ($this->Match_Tablas(array("estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join estructura_regional on estructura_regional.estructura_regional_id = estructura_agencia.estructura_regional_id";
        if ($this->Match_Tablas(array("estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join estructura_entidad on estructura_entidad.estructura_entidad_id = estructura_regional.estructura_entidad_id";
        if ($this->Match_Tablas(array("empresa"),$tablas_adicionales)) $sql[] = "inner join empresa on empresa.empresa_id = prospecto.empresa_id";
        if ($this->Match_Tablas(array("hito"),$tablas_adicionales)) $sql[] = "inner join hito on hito.prospecto_id = prospecto.prospecto_id";
        if ($this->Match_Tablas(array("servicio"),$tablas_adicionales)) $sql[] = "inner join prospecto_servicio ON prospecto_servicio.prospecto_id=prospecto.prospecto_id inner join servicio ON servicio.servicio_id=prospecto_servicio.servicio_id";
        if ($this->Match_Tablas(array("actividades"),$tablas_adicionales)) $sql[] = "inner join prospecto_actividades ON prospecto_actividades.prospecto_id=prospecto.prospecto_id inner join actividades ON actividades.act_id=prospecto_actividades.act_id";
        if (count($sql)==0) return "";
        return " ".implode(" ", $sql);
    }
    
    private function Obtener_Uniones_Prospecto($tablas_adicionales,$tabla_union_prospecto) {
        if (count($tablas_adicionales)<=0) return "";
        $sql= array();
        if ($this->Match_Tablas(array("prospecto","ejecutivo","usuarios","estructura_agencia","estructura_regional","estructura_entidad", "empresa", "calendario","actividades"),$tablas_adicionales)) $sql[] = "inner join empresa on empresa.empresa_id = $tabla_union_prospecto.empresa_id";
        if ($this->Match_Tablas(array("ejecutivo","usuarios","estructura_agencia","estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join ejecutivo on ejecutivo.ejecutivo_id = prospecto.ejecutivo_id";
        if ($this->Match_Tablas(array("usuarios","estructura_agencia","estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join usuarios on usuarios.usuario_id = ejecutivo.usuario_id";
        if ($this->Match_Tablas(array("estructura_agencia","estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join estructura_agencia on estructura_agencia.estructura_agencia_id = usuarios.estructura_agencia_id";
        if ($this->Match_Tablas(array("estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join estructura_regional on estructura_regional.estructura_regional_id = estructura_agencia.estructura_regional_id";
        if ($this->Match_Tablas(array("estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join estructura_entidad on estructura_entidad.estructura_entidad_id = estructura_regional.estructura_entidad_id";
        if ($this->Match_Tablas(array("hito"),$tablas_adicionales)) $sql[] = "inner join hito on hito.prospecto_id = prospecto.prospecto_id";
        if ($this->Match_Tablas(array("servicio"),$tablas_adicionales)) $sql[] = "inner join prospecto_servicio ON prospecto_servicio.prospecto_id=prospecto.prospecto_id inner join servicio ON servicio.servicio_id=prospecto_servicio.servicio_id";
        if ($this->Match_Tablas(array("actividades"),$tablas_adicionales)) $sql[] = "inner join prospecto_actividades ON prospecto_actividades.prospecto_id=prospecto.prospecto_id inner join actividades ON actividades.act_id=prospecto_actividades.act_id";
        if (count($sql)==0) return "";
        return " ".implode(" ", $sql);
    }
    
    private function Obtener_Uniones_SolCred($tablas_adicionales,$tabla_union_prospecto) {
        if (count($tablas_adicionales)<=0) return "";
        $sql= array();
        if ($this->Match_Tablas(array("ejecutivo","usuarios","estructura_agencia","estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join ejecutivo on ejecutivo.ejecutivo_id = solicitud_credito.ejecutivo_id";
        if ($this->Match_Tablas(array("usuarios","estructura_agencia","estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join usuarios on usuarios.usuario_id = ejecutivo.usuario_id";
        if ($this->Match_Tablas(array("estructura_agencia","estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join estructura_agencia on estructura_agencia.estructura_agencia_id = usuarios.estructura_agencia_id";
        if ($this->Match_Tablas(array("estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join estructura_regional on estructura_regional.estructura_regional_id = estructura_agencia.estructura_regional_id";
        if ($this->Match_Tablas(array("estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join estructura_entidad on estructura_entidad.estructura_entidad_id = estructura_regional.estructura_entidad_id";
        if (count($sql)==0) return "";
        return " ".implode(" ", $sql);
    }
    
    private function Obtener_Uniones_Mantenimiento($tablas_adicionales,$tabla_union_prospecto) {
        if (count($tablas_adicionales)<=0) return "";
        $sql= array();
        if ($this->Match_Tablas(array("mantenimiento","ejecutivo","usuarios","estructura_agencia","estructura_regional","estructura_entidad", "empresa", "calendario"),$tablas_adicionales)) $sql[] = "inner join empresa on empresa.empresa_id = $tabla_union_prospecto.empresa_id";
        if ($this->Match_Tablas(array("ejecutivo","usuarios","estructura_agencia","estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join ejecutivo on ejecutivo.ejecutivo_id = mantenimiento.ejecutivo_id";
        if ($this->Match_Tablas(array("usuarios","estructura_agencia","estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join usuarios on usuarios.usuario_id = ejecutivo.usuario_id";
        if ($this->Match_Tablas(array("estructura_agencia","estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join estructura_agencia on estructura_agencia.estructura_agencia_id = usuarios.estructura_agencia_id";
        if ($this->Match_Tablas(array("estructura_regional","estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join estructura_regional on estructura_regional.estructura_regional_id = estructura_agencia.estructura_regional_id";
        if ($this->Match_Tablas(array("estructura_entidad"),$tablas_adicionales)) $sql[] = "inner join estructura_entidad on estructura_entidad.estructura_entidad_id = estructura_regional.estructura_entidad_id";
        if ($this->Match_Tablas(array("tarea"),$tablas_adicionales)) $sql[] = "inner join mantenimiento_tarea ON mantenimiento_tarea.mant_id=mantenimiento.mant_id inner join tarea ON tarea.tarea_id=mantenimiento_tarea.tarea_id";
        if (count($sql)==0) return "";
        return " ".implode(" ", $sql);
    }

    /***
     * Busca si alguno de los elementos existe en la lista
     * @param $elementos
     * @param $lista
     * @return bool
     */
    private function Match_Tablas($elementos, $lista) {
        foreach ($elementos as $elemento) {
            if (in_array($elemento, $lista)) return true;
        }
        return false;
    }

    /***
     * Computa el hash identificador del grupo
     * @param $fila_db
     * @param $grupos
     * @return string
     */
    private function Calcula_Id_Grupo($fila_db, $grupos) {
        $lista = array();
        foreach ($grupos as $grupo) {
            $alias = $grupo->alias_sql;
            $lista[] = $fila_db->$alias;
        }
        return implode("|",$lista);
    }

    /**
     * Obtiene los datos de cada campo de agrupación (campo de select, campo de filtro, campo,
     * @param $grupos
     * @param $array_grupos
     * @param $tablas
     */
    private function Obtener_Campos_Grupo($cadena_grupos, &$array_grupos, &$tablas) {
        if (!is_string($cadena_grupos)) return;
        foreach (explode(",",$cadena_grupos) as $grupo_textual) {
            $grupo = new stdClass();
            $grupo->alias_sql = trim(strtolower($grupo_textual));
            switch ($grupo->alias_sql) {
                case "prospecto_id":
                    $grupo->campo_sql = "hito.prospecto_id";
                    $grupo->campo_sql_descripcion = "";
                    $grupo->alias_sql_descripcion = "prospecto_id";
                    $grupo->titulo = "Solicitante";
                    $grupo->tipo = "entero";
                    $array_grupos[] = $grupo;
                    break;
                case "agencia_id":
                    $grupo->campo_sql = "ea.estructura_agencia_id";
                    $grupo->campo_sql_descripcion = "ea.estructura_agencia_nombre";
                    $grupo->alias_sql_descripcion = "agencia_descripcion";
                    $grupo->titulo = "Oficina";
                    $grupo->tipo = "cadena";
                    $tablas[] = "estructura_agencia";
                    $array_grupos[] = $grupo;
                    break;
                case "region_id":
                    $grupo->campo_sql = "er.estructura_regional_id";
                    $grupo->campo_sql_descripcion = "er.estructura_regional_nombre";
                    $grupo->alias_sql_descripcion = "region_descripcion";
                    $grupo->titulo = "Agencia";
                    $grupo->tipo = "cadena";
                    $tablas[] = "estructura_regional";
                    $array_grupos[] = $grupo;
                    break;
                case "entidad_id":
                    $grupo->campo_sql = "estructura_entidad.estructura_entidad_id";
                    $grupo->campo_sql_descripcion = "estructura_entidad.estructura_entidad_nombre";
                    $grupo->alias_sql_descripcion = "entidad_descripcion";
                    $grupo->titulo = "Entidad";
                    $grupo->tipo = "cadena";
                    $tablas[] = "estructura_entidad";
                    $array_grupos[] = $grupo;
                    break;
                case "ejecutivo_nombre":
                    $grupo->campo_sql = "concat_ws(' ',usuarios.usuario_nombres,usuarios.usuario_app,usuarios.usuario_apm)";
                    $grupo->campo_sql_descripcion = "";
                    $grupo->alias_sql_descripcion = "ejecutivo_nombre";
                    $grupo->titulo = "Usuario App";
                    $grupo->tipo = "cadena";
                    $tablas[] = "usuarios";
                    $array_grupos[] = $grupo;
                    break;
                case "ejecutivo_zona":
                    $grupo->campo_sql = "ejecutivo.ejecutivo_zona";
                    $grupo->campo_sql_descripcion = "";
                    $grupo->alias_sql_descripcion = "ejecutivo_zona";
                    $grupo->titulo = "Zona";
                    $grupo->tipo = "cadena";
                    $tablas[] = "ejecutivo";
                    $array_grupos[] = $grupo;
                    break;
                case "empresa_id":
                    $grupo->campo_sql = "empresa.empresa_id";
                    $grupo->campo_sql_descripcion = "empresa.empresa_nombre_legal";
                    $grupo->alias_sql_descripcion = "empresa_denominacion";
                    $grupo->titulo = "Empresa";
                    $grupo->tipo = "cadena";
                    $tablas[] = "empresa";
                    $array_grupos[] = $grupo;
                    break;
            }
        }
    }

    public function Obtener_Opciones_Grupo() {
        return array(
            array("codigo"=>"prospecto_id","valor"=>"Sin agrupar"),
            array("codigo"=>"agencia_id","valor"=>"Oficina"),
            array("codigo"=>"region_id","valor"=>"Agencia"),
            array("codigo"=>"ejecutivo_nombre","valor"=>"Usuario App"),
            array("codigo"=>"empresa_id","valor"=>"Empresa Aceptante"),
            array("codigo"=>"entidad_id,region_id,agencia_id","valor"=>"Entidad/Agencia/Oficina"),
        );
    }

    public function Obtener_Opciones_Funcion_Mostrar() {
        return array(
            array("codigo"=>"total","valor"=>"Total Horas"),
            array("codigo"=>"promedio","valor"=>"Promedio Horas"),
            array("codigo"=>"registros","valor"=>"Clientes Registrados"),
        );
    }
    
    public function Obtener_Campos_Filtro_Ejecutivo() {
        
        $aux = array(
            
            (object) array("campo"=>"prospecto_fecha_visita_ea","titulo"=>"Cliente - Fecha de Visita con la EA","tipo"=>"fecha"),
            
            // Estructura

            (object) array("campo"=>"ejecutivo_regional_id","titulo"=>"Oficial de Negocios - Estructura - Agencia","tipo"=>"lista"),
            (object) array("campo"=>"ejecutivo_agencia_id","titulo"=>"Oficial de Negocios - Estructura - Oficina","tipo"=>"lista"),
            (object) array("campo"=>"ejecutivo_entidad_id","titulo"=>"Oficial de Negocios - Estructura - Entidad","tipo"=>"lista"),
        );
        
        $arrayFiltros = array(
            // Ejecutivo de Cuentas
            (object) array("campo"=>"ejecutivo_nombre","titulo"=>"Oficial de Negocios - Nombre Literal","tipo"=>"texto"),
            (object) array("campo"=>"ejecutivo_username","titulo"=>"Oficial de Negocios - Nombre de Usuario","tipo"=>"texto"),

            // Afiliador Tercero
            
            (object) array("campo"=>"prospecto_afiliador_id","titulo"=>"Cliente - Fuente de Afiliación","tipo"=>"lista"),
            
            // Prospecto
            (object) array("campo"=>"prospecto_id","titulo"=>"Cliente - Código (LEAD)","tipo"=>"id"),
            (object) array("campo"=>"prospecto_tipo_persona_id","titulo"=>"Cliente - Tipo de persona","tipo"=>"lista"),
            (object) array("campo"=>"prospecto_etapa_id","titulo"=>"Cliente - Etapa","tipo"=>"lista"),
            (object) array("campo"=>"prospecto_rechazado","titulo"=>"Cliente - Rechazado","tipo"=>"booleano"), // Si o no
            (object) array("campo"=>"prospecto_afiliado_paystudio","titulo"=>"Cliente - Afiliado en PayStudio","tipo"=>"booleano"), // Si o no
            (object) array("campo"=>"servicios_prospecto","titulo"=>"Cliente - Servicios","tipo"=>"lista"),
            (object) array("campo"=>"prospecto_provisioning","titulo"=>"Cliente - Provisioning","tipo"=>"lista"),
            
            // Fechas
            (object) array("campo"=>"prospecto_fecha_asignacion","titulo"=>"Cliente - Fecha de asignación","tipo"=>"fecha"),
            (object) array("campo"=>"prospecto_fecha_revision_antecedentes","titulo"=>"Cliente - Fecha de Revisión Antecedentes","tipo"=>"fecha"),
            (object) array("campo"=>"prospecto_fecha_check_in_ea","titulo"=>"Cliente - Fecha de Check-in con la EA","tipo"=>"fecha"),
            (object) array("campo"=>"prospecto_fecha_consolidacion","titulo"=>"Cliente - Fecha de Consolidación","tipo"=>"fecha"),
            (object) array("campo"=>"prospecto_fecha_afiliacion_paystudio","titulo"=>"Cliente - Fecha de Afiliación en PayStudio","tipo"=>"fecha"),
            (object) array("campo"=>"prospecto_fecha_derivacion_etapa","titulo"=>"Cliente - Fecha de Derivación de la Etapa","tipo"=>"fecha"),
            (object) array("campo"=>"prospecto_fecha_visita_ea","titulo"=>"Cliente - Fecha de Visita con la EA","tipo"=>"fecha"),
            
            // Empresa
            (object) array("campo"=>"empresa_consolidada","titulo"=>"Empresa - Es consolidada","tipo"=>"booleano"), // si o no
            (object) array("campo"=>"empresa_categoria","titulo"=>"Empresa - Categoría","tipo"=>"lista"),
            (object) array("campo"=>"empresa_nit","titulo"=>"Empresa - NIT","tipo"=>"texto"),
            (object) array("campo"=>"empresa_nombre_legal","titulo"=>"Empresa - Nombre Legal","tipo"=>"texto"),
            (object) array("campo"=>"empresa_nombre_establecimiento","titulo"=>"Empresa - Nombre del Establecimiento","tipo"=>"texto"),

            // Catálogo
            (object) array("campo"=>"empresa_tipo_sociedad","titulo"=>"Empresa - Tipo de Sociedad","tipo"=>"lista"),
            (object) array("campo"=>"empresa_perfil_comercial","titulo"=>"Empresa- Perfil Comercial","tipo"=>"lista"),
            (object) array("campo"=>"empresa_mcc","titulo"=>"Empresa - MCC","tipo"=>"lista"),
            (object) array("campo"=>"empresa_rubro","titulo"=>"Empresa - Rubro","tipo"=>"lista"),
            (object) array("campo"=>"empresa_departamento","titulo"=>"Empresa - Departamento","tipo"=>"lista"),
            (object) array("campo"=>"empresa_municipio","titulo"=>"Empresa - Municipio/Ciudad","tipo"=>"lista"),
            
        );
        
        return array_merge($aux, $arrayFiltros);
        
    }
    
    public function Obtener_Campos_Filtro_Agencia() {
        
        $arrayFiltros = array(
            
            // Fechas
            (object) array("campo"=>"completado_fecha","titulo"=>"Fecha de proceso (" . $this->mfunciones_generales->GetValorCatalogo(5, 'terceros_estado') . ")","tipo"=>"fecha"),
            (object) array("campo"=>"entregado_fecha","titulo"=>"Fecha " . $this->mfunciones_generales->GetValorCatalogo(6, 'terceros_estado'),"tipo"=>"fecha"),
            (object) array("campo"=>"notificar_cierre_fecha","titulo"=>"Fecha " . $this->lang->line('notificar_cierre_texto'),"tipo"=>"fecha"),
            (object) array("campo"=>"cuenta_cerrada_fecha","titulo"=>"Fecha " . $this->lang->line('cuenta_cerrada_texto'),"tipo"=>"fecha"),
            
            (object) array("campo"=>"terceros_estado_agencia_aux","titulo"=>"Estado Actual","tipo"=>"lista"),
            
            (object) array("campo"=>"notificar_cierre","titulo"=>$this->lang->line('notificar_cierre_texto'),"tipo"=>"booleano"),
        );
        
        return $arrayFiltros;
        
    }
    
    public function Obtener_Campos_Filtro_Onboarding() {
        
        $aux = array(
            
            (object) array("campo"=>"tipo_cuenta","titulo"=>"Tipo de Cuenta","tipo"=>"lista"),
            // Estructura
            (object) array("campo"=>"codigo_agencia_fie","titulo"=>"Estructura - Agencia","tipo"=>"lista"),
        );
        
        $arrayFiltros = array(
            
            (object) array("campo"=>"tercero_asistencia","titulo"=>"Tipo de Proceso","tipo"=>"lista"),
            (object) array("campo"=>"terceros_estado","titulo"=>"Estado Actual","tipo"=>"lista"),
            (object) array("campo"=>"rechazado","titulo"=>"Rechazado","tipo"=>"booleano"),
            (object) array("campo"=>"aprobado","titulo"=>"Aprobado","tipo"=>"booleano"),
            (object) array("campo"=>"completado","titulo"=>"Completado","tipo"=>"booleano"),
            
            // Req. Nuevos estados
            (object) array("campo"=>"notificar_cierre","titulo"=>$this->lang->line('notificar_cierre_texto'),"tipo"=>"booleano"),
            (object) array("campo"=>"cuenta_cerrada","titulo"=>$this->lang->line('cuenta_cerrada_texto'),"tipo"=>"booleano"),
            
            // -- Req. Consulta COBIS y SEGIP
            
            (object) array("campo"=>"ws_segip_codigo_resultado","titulo"=>"No Asistido - " . $this->lang->line('ws_segip_codigo_resultado'),"tipo"=>"lista"),
            (object) array("campo"=>"ws_segip_flag_verificacion","titulo"=>"No Asistido - " . $this->lang->line('ws_segip_flag_verificacion'),"tipo"=>"booleano"),
            (object) array("campo"=>"segip_operador_resultado","titulo"=>"No Asistido - " . $this->lang->line('segip_operador_resultado'),"tipo"=>"lista"),
            (object) array("campo"=>"segip_operador_fecha","titulo"=>"No Asistido - " . $this->lang->line('segip_operador_fecha'),"tipo"=>"fecha"),
            
            // -- Req. Flujo COBIS
            
            (object) array("campo"=>"f_cobis_actual_etapa","titulo"=>$this->lang->line('f_cobis_actual_etapa'),"tipo"=>"lista"),
            (object) array("campo"=>"f_cobis_actual_fecha","titulo"=>$this->lang->line('f_cobis_actual_fecha'),"tipo"=>"fecha"),
            (object) array("campo"=>"f_cobis_excepcion","titulo"=>$this->lang->line('f_cobis_excepcion'),"tipo"=>"booleano"),
            (object) array("campo"=>"f_cobis_excepcion_motivo","titulo"=>$this->lang->line('f_cobis_excepcion_motivo'),"tipo"=>"lista"),
            (object) array("campo"=>"f_cobis_flag_rechazado","titulo"=>$this->lang->line('f_cobis_flag_rechazado'),"tipo"=>"booleano"),
            
            // Fechas
            (object) array("campo"=>"terceros_fecha","titulo"=>"Fecha Registro","tipo"=>"fecha"),
            (object) array("campo"=>"rechazado_fecha","titulo"=>"Fecha Rechazo","tipo"=>"fecha"),
            (object) array("campo"=>"aprobado_fecha","titulo"=>"Fecha Aprobación","tipo"=>"fecha"),
            (object) array("campo"=>"completado_fecha","titulo"=>"Fecha Completado","tipo"=>"fecha"),
            
                // Req. Nuevos estados
                (object) array("campo"=>"notificar_cierre_fecha","titulo"=>"Fecha " . $this->lang->line('notificar_cierre_texto'),"tipo"=>"fecha"),
                (object) array("campo"=>"cuenta_cerrada_fecha","titulo"=>"Fecha " . $this->lang->line('cuenta_cerrada_texto'),"tipo"=>"fecha"),
                
        );
        
        return array_merge($aux, $arrayFiltros);
        
    }
    
    public function Obtener_Campos_Filtro_Prospecto() {
        
        $aux = array(
            
            // Estructura
            (object) array("campo"=>"prospecto_fecha_asignacion","titulo"=>"Cliente - Fecha de asignación","tipo"=>"fecha"),
            
            (object) array("campo"=>"ejecutivo_regional_id","titulo"=>"Oficial de Negocios - Estructura - Agencia","tipo"=>"lista"),
            (object) array("campo"=>"ejecutivo_agencia_id","titulo"=>"Oficial de Negocios - Estructura - Oficina","tipo"=>"lista"),
            (object) array("campo"=>"ejecutivo_entidad_id","titulo"=>"Oficial de Negocios - Estructura - Entidad","tipo"=>"lista"),
        );
        
        $arrayFiltros = array(
            // Ejecutivo de Cuentas
            (object) array("campo"=>"ejecutivo_nombre","titulo"=>"Oficial de Negocios - Nombre Literal","tipo"=>"texto"),
            
            // Prospecto
            (object) array("campo"=>"prospecto_id","titulo"=>"Cliente - Código (LEAD)","tipo"=>"id"),
            (object) array("campo"=>"general_solicitante","titulo"=>"Cliente - Solicitante","tipo"=>"texto"),
            (object) array("campo"=>"general_ci","titulo"=>"Cliente - C.I.","tipo"=>"texto"),
            (object) array("campo"=>"prospecto_tipo_persona_id","titulo"=>"Cliente - Rubro","tipo"=>"lista"),
            (object) array("campo"=>"prospecto_etapa_id","titulo"=>"Cliente - Etapa","tipo"=>"lista"),
            (object) array("campo"=>"prospecto_etapa_id_aux","titulo"=>"Cliente - Etapa (Todas)","tipo"=>"lista"),
            (object) array("campo"=>"general_interes","titulo"=>"Cliente - Nivel de Interés","tipo"=>"lista"),
            (object) array("campo"=>"productos_solicitados","titulo"=>"Cliente - Productos Solicitados","tipo"=>"lista"),
            (object) array("campo"=>"frec_seleccion","titulo"=>"Cliente - Frecuencia de Ventas","tipo"=>"lista"),
            (object) array("campo"=>"capacidad_criterio","titulo"=>"Cliente - Capacidad de Pago Seleccionada","tipo"=>"lista"),
            (object) array("campo"=>"estacion_sel_arb","titulo"=>"Cliente - Estacionalidad","tipo"=>"lista"),
            (object) array("campo"=>"prospecto_evaluacion","titulo"=>"Cliente - " . $this->lang->line('prospecto_evaluacion'),"tipo"=>"lista"),
            
            (object) array("campo"=>"prospecto_jda_eval","titulo"=>"Cliente - " . $this->lang->line('prospecto_jda_eval'),"tipo"=>"lista"),
            (object) array("campo"=>"prospecto_desembolso","titulo"=>"Cliente - " . $this->lang->line('prospecto_desembolso'),"tipo"=>"booleano"),
            
            // Fechas
            (object) array("campo"=>"prospecto_fecha_check_in_ea","titulo"=>"Cliente - Fecha de Check-in","tipo"=>"fecha"),
            (object) array("campo"=>"prospecto_fecha_consolidacion","titulo"=>"Cliente - Fecha de Consolidación","tipo"=>"fecha"),
            (object) array("campo"=>"prospecto_jda_eval_fecha","titulo"=>"Cliente - Fecha " . $this->lang->line('prospecto_jda_eval'),"tipo"=>"fecha"),
            (object) array("campo"=>"prospecto_desembolso_fecha","titulo"=>"Cliente - Fecha " . $this->lang->line('prospecto_desembolso'),"tipo"=>"fecha"),
            
            // Relación Solicitud de Crédito
            (object) array("campo"=>"sol_estudio","titulo"=>"Solicitud Crédito - Asociado","tipo"=>"booleano"),
            
        );
        
        return array_merge($aux, $arrayFiltros);
        
    }
    
    public function Obtener_Campos_Filtro_Mantenimiento() {
                
        $aux = array(
            
            // Estructura

            (object) array("campo"=>"ejecutivo_regional_id","titulo"=>"Oficial de Negocios - Estructura - Agencia","tipo"=>"lista"),
            (object) array("campo"=>"ejecutivo_agencia_id","titulo"=>"Oficial de Negocios - Estructura - Oficina","tipo"=>"lista"),
            (object) array("campo"=>"ejecutivo_entidad_id","titulo"=>"Oficial de Negocios - Estructura - Entidad","tipo"=>"lista"),
        );
        
        $arrayFiltros = array(
            // Ejecutivo de Cuenta
            (object) array("campo"=>"ejecutivo_nombre","titulo"=>"Oficial de Negocios - Nombre Literal","tipo"=>"texto"),
            (object) array("campo"=>"ejecutivo_username","titulo"=>"Oficial de Negocios - Nombre de Usuario","tipo"=>"texto"),

            // Mantenimiento
            (object) array("campo"=>"mant_id","titulo"=>"Mantenimiento - Código (MAN)","tipo"=>"id"),
            (object) array("campo"=>"mant_estado","titulo"=>"Mantenimiento - Estado","tipo"=>"lista"),
            (object) array("campo"=>"tareas_mantenimiento","titulo"=>"Mantenimiento - Tareas (Por Perfil App Seleccionado)","tipo"=>"lista"),
            
            // Fechas
            (object) array("campo"=>"mant_fecha_asignacion","titulo"=>"Mantenimiento - Fecha de asignación","tipo"=>"fecha"),
            (object) array("campo"=>"mant_checkin_fecha","titulo"=>"Mantenimiento - Fecha de Check-in con la EA","tipo"=>"fecha"),
            (object) array("campo"=>"mant_completado_fecha","titulo"=>"Mantenimiento - Fecha de Conclusión","tipo"=>"fecha"),
            (object) array("campo"=>"mant_fecha_visita_ea","titulo"=>"Mantenimiento - Fecha de Visita con la EA","tipo"=>"fecha"),
            
//            // Empresa
//            (object) array("campo"=>"empresa_consolidada","titulo"=>"Empresa - Es consolidada","tipo"=>"booleano"), // si o no
//            (object) array("campo"=>"empresa_categoria","titulo"=>"Empresa - Categoría","tipo"=>"lista"),
//            (object) array("campo"=>"empresa_nit","titulo"=>"Empresa - NIT","tipo"=>"texto"),
//            (object) array("campo"=>"empresa_nombre_legal","titulo"=>"Empresa - Nombre Legal","tipo"=>"texto"),
//            (object) array("campo"=>"empresa_nombre_establecimiento","titulo"=>"Empresa - Nombre del Establecimiento","tipo"=>"texto"),
//            
//            // Catálogo
//            (object) array("campo"=>"empresa_tipo_sociedad","titulo"=>"Empresa - Tipo de Sociedad","tipo"=>"lista"),
//            (object) array("campo"=>"empresa_perfil_comercial","titulo"=>"Empresa- Perfil Comercial","tipo"=>"lista"),
//            (object) array("campo"=>"empresa_mcc","titulo"=>"Empresa - MCC","tipo"=>"lista"),
//            (object) array("campo"=>"empresa_rubro","titulo"=>"Empresa - Rubro","tipo"=>"lista"),            
//            (object) array("campo"=>"empresa_departamento","titulo"=>"Empresa - Departamento","tipo"=>"lista"),
//            (object) array("campo"=>"empresa_municipio","titulo"=>"Empresa - Municipio/Ciudad","tipo"=>"lista"),
            
        );
        
        return array_merge($aux, $arrayFiltros);
    }

    public function Obtener_Campos_Filtro_SolCred() {
                
        $aux = array(
            
            // Estructura
            
            (object) array("campo"=>"sol_fecha","titulo"=>"Fecha de Registro","tipo"=>"fecha"),
            (object) array("campo"=>"sol_codigo_rubro","titulo"=>"Rubro de la Solicitud","tipo"=>"lista"),
            (object) array("campo"=>"sol_codigo_agencia_fie","titulo"=>"Agencia Asociada","tipo"=>"lista"),
        );
        
        $arrayFiltros = array(
            // Ejecutivo de Cuenta
            (object) array("campo"=>"ejecutivo_nombre","titulo"=>"Oficial de Negocios - Nombre Literal","tipo"=>"texto"),

            // Solicitud de Crédito
            (object) array("campo"=>"sol_id","titulo"=>"Código Interno (SOL)","tipo"=>"id"),
            (object) array("campo"=>"sol_asistencia","titulo"=>"Tipo Registro","tipo"=>"lista"),
            (object) array("campo"=>"sol_estado","titulo"=>"Solicitud Estado","tipo"=>"lista"),
            (object) array("campo"=>"sol_asignado","titulo"=>"Solicitud Asignada","tipo"=>"booleano"),
            (object) array("campo"=>"sol_consolidado","titulo"=>"Solicitud Consolidada","tipo"=>"booleano"),
            (object) array("campo"=>"sol_rechazado","titulo"=>"Solicitud Rechazada","tipo"=>"booleano"),
            (object) array("campo"=>"sol_evaluacion","titulo"=>"Evalución Registrada","tipo"=>"lista"),
            
            (object) array("campo"=>"sol_estudio","titulo"=>"Conversión a Estudio","tipo"=>"booleano"),
            (object) array("campo"=>"sol_estudio_codigo","titulo"=>"Conversión a Estudio - Código","tipo"=>"id"),
            (object) array("campo"=>"sol_trabajo_actividad_pertenece","titulo"=>"Conversión a Estudio - Rubro","tipo"=>"lista"),
            
            (object) array("campo"=>"sol_ci","titulo"=>"Solicitante CI (raíz)","tipo"=>"texto"),
            (object) array("campo"=>"sol_dependencia","titulo"=>"Solicitante Actividad Económica","tipo"=>"lista"),
            (object) array("campo"=>"sol_conyugue","titulo"=>"Solicitante con Cónyuge","tipo"=>"booleano"),
            
            (object) array("campo"=>"sol_jda_eval","titulo"=>"Aux - " . $this->lang->line('prospecto_jda_eval'),"tipo"=>"lista"),
            (object) array("campo"=>"sol_desembolso","titulo"=>"Aux - " . $this->lang->line('prospecto_desembolso'),"tipo"=>"booleano"),
            
            // Fechas
            (object) array("campo"=>"sol_asignado_fecha","titulo"=>"Fecha de Asignación","tipo"=>"fecha"),
            (object) array("campo"=>"sol_consolidado_fecha","titulo"=>"Fecha de Consolidación","tipo"=>"fecha"),
            (object) array("campo"=>"sol_rechazado_fecha","titulo"=>"Fecha de Rechazo","tipo"=>"fecha"),
            (object) array("campo"=>"sol_jda_eval_fecha","titulo"=>"Aux - Fecha " . $this->lang->line('prospecto_jda_eval'),"tipo"=>"fecha"),
            (object) array("campo"=>"sol_desembolso_fecha","titulo"=>"Aux - Fecha " . $this->lang->line('prospecto_desembolso'),"tipo"=>"fecha"),
        );
        
        return array_merge($aux, $arrayFiltros);
    }
    
    public function Obtener_Tabla_Campo_Filtro($campo) {
        switch ($campo) {
            // Prospecto
            case "ejecutivo_regional_id": return (object) array("tabla"=>"estructura_regional","campo"=>"estructura_regional_id"); break;
            case "ejecutivo_agencia_id": return (object) array("tabla"=>"estructura_agencia","campo"=>"estructura_agencia_id"); break;
            case "ejecutivo_entidad_id": return (object) array("tabla"=>"estructura_entidad","campo"=>"estructura_entidad_id"); break;
            case "ejecutivo_nombre": return (object) array("tabla"=>"usuarios","campo"=>"concat_ws(' ',usuarios.usuario_nombres,usuarios.usuario_app,usuarios.usuario_apm)"); break;
            case "general_solicitante": return (object) array("tabla"=>"prospecto","campo"=>"general_solicitante"); break;
            case "general_ci": return (object) array("tabla"=>"prospecto","campo"=>"general_ci"); break;
            case "prospecto_id": return (object) array("tabla"=>"prospecto","campo"=>"prospecto_id"); break;
            
            case "prospecto_tipo_persona_id": return (object) array("tabla"=>"prospecto","campo"=>"tipo_persona_id"); break;
            case "prospecto_etapa_id": return (object) array("tabla"=>"prospecto","campo"=>"prospecto_etapa"); break;
            case "prospecto_etapa_id_aux": return (object) array("tabla"=>"prospecto","campo"=>"prospecto_etapa"); break;
            case "prospecto_rechazado": return (object) array("tabla"=>"prospecto","campo"=>"prospecto_rechazado"); break;
            case "prospecto_afiliado_paystudio": return (object) array("tabla"=>"prospecto","campo"=>"prospecto_aceptado_afiliado"); break;
            case "productos_solicitados": return (object) array("tabla"=>"actividades","campo"=>"act_id"); break;
            
            case "frec_seleccion": return (object) array("tabla"=>"prospecto","campo"=>"frec_seleccion"); break;
            case "capacidad_criterio": return (object) array("tabla"=>"prospecto","campo"=>"capacidad_criterio"); break;
            case "estacion_sel_arb": return (object) array("tabla"=>"prospecto","campo"=>"estacion_sel_arb"); break;
            case "prospecto_evaluacion": return (object) array("tabla"=>"prospecto","campo"=>"prospecto_evaluacion"); break;
            case "general_interes": return (object) array("tabla"=>"prospecto","campo"=>"general_interes"); break;
        
            case "sol_estudio": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_estudio"); break;
        
            // Nuevos estados Estudio de Crédito
        
            case "prospecto_jda_eval": return (object) array("tabla"=>"prospecto","campo"=>"prospecto_jda_eval"); break;
            case "prospecto_jda_eval_fecha": return (object) array("tabla"=>"prospecto","campo"=>"prospecto_jda_eval_fecha"); break;
            case "prospecto_desembolso": return (object) array("tabla"=>"prospecto","campo"=>"prospecto_desembolso"); break;
            case "prospecto_desembolso_fecha": return (object) array("tabla"=>"prospecto","campo"=>"prospecto_desembolso_fecha"); break;
        
            case "empresa_consolidada": return (object) array("tabla"=>"empresa","campo"=>"empresa_consolidada"); break;
            case "empresa_categoria": return (object) array("tabla"=>"empresa","campo"=>"empresa_categoria"); break;
            case "empresa_nit": return (object) array("tabla"=>"empresa","campo"=>"empresa_nit"); break;
            case "empresa_nombre_legal": return (object) array("tabla"=>"empresa","campo"=>"empresa_nombre_legal"); break;
            case "empresa_nombre_establecimiento": return (object) array("tabla"=>"empresa","campo"=>"empresa_nombre_establecimiento"); break;
            case "empresa_tipo_sociedad": return (object) array("tabla"=>"empresa","campo"=>"empresa_tipo_sociedad"); break;
            case "empresa_perfil_comercial": return (object) array("tabla"=>"empresa","campo"=>"empresa_perfil_comercial"); break;
            case "empresa_rubro": return (object) array("tabla"=>"empresa","campo"=>"empresa_rubro"); break;
            case "empresa_mcc": return (object) array("tabla"=>"empresa","campo"=>"empresa_mcc"); break;
            case "empresa_departamento": return (object) array("tabla"=>"empresa","campo"=>"empresa_departamento"); break;
            case "empresa_municipio": return (object) array("tabla"=>"empresa","campo"=>"empresa_municipio"); break;
            case "prospecto_fecha_asignacion": return (object) array("tabla"=>"prospecto","campo"=>"prospecto_fecha_asignacion"); break;
            case "prospecto_fecha_revision_antecedentes": return (object) array("tabla"=>"prospecto","campo"=>"prospecto_rev_fecha"); break;
            case "prospecto_fecha_check_in_ea": return (object) array("tabla"=>"prospecto","campo"=>"prospecto_checkin_fecha"); break;
            case "prospecto_fecha_consolidacion": return (object) array("tabla"=>"prospecto","campo"=>"prospecto_consolidar_fecha"); break;
            case "prospecto_fecha_afiliacion_paystudio": return (object) array("tabla"=>"prospecto","campo"=>"prospecto_aceptado_afiliado_fecha"); break;
            case "prospecto_fecha_derivacion_etapa": return (object) array("tabla"=>"hito","campo"=>"hito_fecha_ini"); break;
            case "prospecto_fecha_visita_ea": return (object) array("tabla"=>"calendario","campo"=>"cal_visita_ini"); break;
            // Mantenimiento
            case "mant_id": return (object) array("tabla"=>"mantenimiento","campo"=>"mant_id"); break;
            case "mant_estado": return (object) array("tabla"=>"mantenimiento","campo"=>"mant_estado"); break;
            case "tareas_mantenimiento": return (object) array("tabla"=>"tarea","campo"=>"tarea_id"); break;
            case "mant_fecha_asignacion": return (object) array("tabla"=>"mantenimiento","campo"=>"mant_fecha_asignacion"); break;
            case "mant_checkin_fecha": return (object) array("tabla"=>"mantenimiento","campo"=>"mant_checkin_fecha"); break;
            case "mant_completado_fecha": return (object) array("tabla"=>"mantenimiento","campo"=>"mant_completado_fecha"); break;
            case "mant_fecha_visita_ea": return (object) array("tabla"=>"calendario","campo"=>"cal_visita_ini"); break;
            
            /*************** SPRINT 1.7 - INICIO ****************************/
        
            // Solicitud
        
            case "fuente_id": return (object) array("tabla"=>"solicitud_afiliacion","campo"=>"fuente_id"); break;
            case "solicitud_estado": return (object) array("tabla"=>"solicitud_afiliacion","campo"=>"solicitud_estado"); break;
            case "solicitud_fecha": return (object) array("tabla"=>"solicitud_afiliacion","campo"=>"solicitud_fecha"); break;
            case "solicitud_fecha_completo": return (object) array("tabla"=>"solicitud_afiliacion","campo"=>"solicitud_fecha_completo"); break;
            case "solicitud_rubro": return (object) array("tabla"=>"solicitud_afiliacion","campo"=>"solicitud_rubro"); break;
            case "solicitud_ciudad": return (object) array("tabla"=>"solicitud_afiliacion","campo"=>"solicitud_ciudad"); break;
            case "solicitud_masivo": return (object) array("tabla"=>"solicitud_afiliacion","campo"=>"solicitud_masivo"); break;
        
            /*************** SPRINT 1.7 - FIN ****************************/
        
            // Onboarding
        
            case "tipo_cuenta": return (object) array("tabla"=>"terceros","campo"=>"tipo_cuenta"); break;
        
            case "codigo_agencia_fie": return (object) array("tabla"=>"terceros","campo"=>"codigo_agencia_fie"); break;
            case "tercero_asistencia": return (object) array("tabla"=>"terceros","campo"=>"tercero_asistencia"); break;
            case "terceros_estado": return (object) array("tabla"=>"terceros","campo"=>"terceros_estado"); break;
            
            case "rechazado": return (object) array("tabla"=>"terceros","campo"=>"rechazado"); break;
            case "aprobado": return (object) array("tabla"=>"terceros","campo"=>"aprobado"); break;
            case "completado": return (object) array("tabla"=>"terceros","campo"=>"completado"); break;
        
            case "terceros_fecha": return (object) array("tabla"=>"terceros","campo"=>"terceros_fecha"); break;
            case "rechazado_fecha": return (object) array("tabla"=>"terceros","campo"=>"rechazado_fecha"); break;
            case "aprobado_fecha": return (object) array("tabla"=>"terceros","campo"=>"aprobado_fecha"); break;
            case "completado_fecha": return (object) array("tabla"=>"terceros","campo"=>"completado_fecha"); break;
            
            // -- Req. Consulta COBIS y SEGIP
        
            case "ws_segip_flag_verificacion": return (object) array("tabla"=>"terceros","campo"=>"ws_segip_flag_verificacion"); break;
            case "ws_segip_codigo_resultado": return (object) array("tabla"=>"terceros","campo"=>"ws_segip_codigo_resultado"); break;
            case "segip_operador_resultado": return (object) array("tabla"=>"terceros","campo"=>"segip_operador_resultado"); break;
            case "segip_operador_fecha": return (object) array("tabla"=>"terceros","campo"=>"segip_operador_fecha"); break;
        
            // -- Req. Flujo COBIS
        
            case "f_cobis_actual_etapa": return (object) array("tabla"=>"terceros","campo"=>"f_cobis_actual_etapa"); break;
            case "f_cobis_actual_fecha": return (object) array("tabla"=>"terceros","campo"=>"f_cobis_actual_fecha"); break;
            case "f_cobis_excepcion": return (object) array("tabla"=>"terceros","campo"=>"f_cobis_excepcion"); break;
            case "f_cobis_flag_rechazado": return (object) array("tabla"=>"terceros","campo"=>"f_cobis_flag_rechazado"); break;
            case "f_cobis_excepcion_motivo": return (object) array("tabla"=>"terceros","campo"=>"f_cobis_excepcion_motivo"); break;
        
            // Req. Nuevos estados
        
            case "notificar_cierre": return (object) array("tabla"=>"terceros","campo"=>"notificar_cierre"); break;
            case "cuenta_cerrada": return (object) array("tabla"=>"terceros","campo"=>"cuenta_cerrada"); break;
            case "notificar_cierre_fecha": return (object) array("tabla"=>"terceros","campo"=>"notificar_cierre_fecha"); break;
            case "cuenta_cerrada_fecha": return (object) array("tabla"=>"terceros","campo"=>"cuenta_cerrada_fecha"); break;
            case "terceros_estado_agencia_aux": return (object) array("tabla"=>"terceros","campo"=>"terceros_estado"); break;
        
            // Solicitud de Crédito
        
            case "sol_codigo_agencia_fie": return (object) array("tabla"=>"solicitud_credito","campo"=>"codigo_agencia_fie"); break;
            case "sol_codigo_rubro": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_codigo_rubro"); break;
        
        
            case "sol_id": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_id"); break;
            case "sol_asistencia": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_asistencia"); break;
            case "sol_estado": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_estado"); break;
            case "sol_asignado": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_asignado"); break;
            case "sol_consolidado": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_consolidado"); break;
            case "sol_rechazado": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_rechazado"); break;
            case "sol_evaluacion": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_evaluacion"); break;
            case "sol_estudio": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_estudio"); break;
            case "sol_estudio_codigo": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_estudio_codigo"); break;
            case "sol_trabajo_actividad_pertenece": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_trabajo_actividad_pertenece"); break;
            case "sol_ci": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_ci"); break;
            case "sol_dependencia": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_dependencia"); break;
            case "sol_conyugue": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_conyugue"); break;
            case "sol_fecha": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_fecha"); break;
            case "sol_asignado_fecha": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_asignado_fecha"); break;
            case "sol_consolidado_fecha": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_consolidado_fecha"); break;
            case "sol_rechazado_fecha": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_rechazado_fecha"); break;
            
            case "sol_jda_eval": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_jda_eval"); break;
            case "sol_jda_eval_fecha": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_jda_eval_fecha"); break;
            case "sol_desembolso": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_desembolso"); break;
            case "sol_desembolso_fecha": return (object) array("tabla"=>"solicitud_credito","campo"=>"sol_desembolso_fecha"); break;
        
        }
        return null;
    }

    public function Obtener_Valores_Filtro($campo, $perfil_app=1) {
        
        $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);

        switch ($campo) {
            case "ejecutivo_regional_id":
            case "sol_codigo_agencia_fie":
                return $this->Obtener_Lista("estructura_regional WHERE estructura_regional.estructura_regional_id IN (" . $lista_region->region_id . ")","estructura_regional_id","CONCAT_WS(' ', estructura_regional_nombre, IF(estructura_regional_estado=0, '(Cerrada)', ''))");
            case "ejecutivo_agencia_id":
                return $this->Obtener_Lista("estructura_agencia INNER JOIN estructura_regional ON estructura_regional.estructura_regional_id=estructura_agencia.estructura_regional_id WHERE estructura_regional.estructura_regional_id IN (" . $lista_region->region_id . ")","estructura_agencia_id","estructura_agencia_nombre");
            case "ejecutivo_entidad_id":
                return $this->Obtener_Lista("estructura_entidad","estructura_entidad_id","estructura_entidad_nombre");
            
            case "prospecto_afiliador_id":
                return $this->Obtener_Lista("afiliador","afiliador_id","afiliador_nombre");
                
            case "prospecto_tipo_persona_id":
            case "sol_trabajo_actividad_pertenece":
                return $this->Obtener_Lista("tipo_persona WHERE tipo_persona_id IN(1,2,3,4)","tipo_persona_id","tipo_persona_nombre");
            case "prospecto_etapa_id":
                return $this->Obtener_Lista("etapa WHERE etapa_categoria IN(1)","etapa_id","etapa_nombre");
            case "prospecto_etapa_id_aux":
                return $this->Obtener_Lista("etapa WHERE etapa_categoria IN(1,5,2)","etapa_id","etapa_nombre");
            case "prospecto_rechazado":
            case "productos_solicitados":
                return $this->Obtener_Lista("actividades WHERE act_activo > 0","act_id","act_detalle");
                
            case "frec_seleccion":
                return array(
                    (object) array("id"=>"1","descripcion"=>"Diaria"),
                    (object) array("id"=>"2","descripcion"=>"Semanal"),
                    (object) array("id"=>"3","descripcion"=>"Mensual")
                );
                
            case "capacidad_criterio":
                return array(
                    (object) array("id"=>"1","descripcion"=>"Ventas Declaradas Según Frecuencia del Ingreso"),
                    (object) array("id"=>"2","descripcion"=>"Ventas por Principales Productos Comercializados"),
                    (object) array("id"=>"3","descripcion"=>"Ventas Según Compras de Materia Prima"),
                    (object) array("id"=>"4","descripcion"=>"Cruce Personalizado")
                );
                
            case "estacion_sel_arb":
                return array(
                    (object) array("id"=>"1","descripcion"=>"Alta"),
                    (object) array("id"=>"2","descripcion"=>"Regular"),
                    (object) array("id"=>"3","descripcion"=>"Baja")
                );
                
            case "general_interes":
                return array(
                    (object) array("id"=>"1","descripcion"=>"Bajo"),
                    (object) array("id"=>"2","descripcion"=>"Medio"),
                    (object) array("id"=>"3","descripcion"=>"Alto")
                );
                
            case "estacion_sel_arb":
                return array(
                    (object) array("id"=>"1","descripcion"=>"Alta"),
                    (object) array("id"=>"2","descripcion"=>"Regular"),
                    (object) array("id"=>"3","descripcion"=>"Baja")
                );
                
            case "prospecto_evaluacion":
            case "sol_jda_eval":
                return array(
                    (object) array("id"=>"0","descripcion"=>"No Evaluado"),
                    (object) array("id"=>"1","descripcion"=>"Aprobado"),
                    (object) array("id"=>"2","descripcion"=>"Rechazado")
                );
                
            case "prospecto_jda_eval":
                return array(
                    (object) array("id"=>"0","descripcion"=>"No Evaluado"),
                    (object) array("id"=>"1","descripcion"=>"Aprobado"),
                    (object) array("id"=>"2","descripcion"=>"Rechazado"),
                    (object) array("id"=>"99","descripcion"=>"Devolver al Oficial de Negocios")
                );
            case "prospecto_provisioning":
                return array(
                    (object) array("id"=>"0","descripcion"=>"No Asignado"),
                    (object) array("id"=>"1","descripcion"=>"Asignado"),
                    (object) array("id"=>"2","descripcion"=>"Completado"),
                    (object) array("id"=>"3","descripcion"=>"Rechazado")
                );
            case "empresa_consolidada":
                return array(
                    (object) array("id"=>"0","descripcion"=>"No"),
                    (object) array("id"=>"1","descripcion"=>"Sí")
                );
            case "empresa_categoria":
                //return $this->Obtener_Lista("categoria_persona","categoria_persona_id","categoria_persona_nombre");
                return array(
                    (object) array("id"=>"1","descripcion"=>"Comercio"),
                    (object) array("id"=>"2","descripcion"=>"Establecimiento")
                );
            case "empresa_tipo_sociedad":
                return $this->Obtener_Lista_Catalogo("TPS"); // Tipo de Sociedad
            case "empresa_departamento":
                return $this->Obtener_Lista_Catalogo("DEP"); // Departamento
            case "empresa_rubro":
                return $this->Obtener_Lista_Catalogo("RUB"); // Rubro
            case "empresa_perfil_comercial":
                return $this->Obtener_Lista_Catalogo("PEC"); // Perfil Comercial
            case "empresa_mcc":
                return $this->Obtener_Lista_Catalogo("MCC"); // MCC
            case "empresa_municipio":
                return $this->Obtener_Lista_Catalogo("CIU"); // Municipio/Ciudad
            
            // Mantenimiento
            case "mant_estado":
                return array(
                    (object) array("id"=>"0","descripcion"=>"Pendiente"),
                    (object) array("id"=>"1","descripcion"=>"Completado")
                );
            case "tareas_mantenimiento":
                return $this->Obtener_Lista("tarea WHERE tarea_activo=1 AND perfil_app_id IN (0, '$perfil_app')","tarea_id","tarea_detalle");
                
                
            /*************** SPRINT 1.7 - INICIO ****************************/
            
            // Solicitud
            
            case "fuente_id":
                return $this->Obtener_Lista("fuente_lead WHERE fuente_activo=1","fuente_id","fuente_nombre");
            
            case "solicitud_estado":
                return array(
                    (object) array("id"=>"0","descripcion"=>"Pendiente"),
                    (object) array("id"=>"1","descripcion"=>"Aprobado"),
                    (object) array("id"=>"2","descripcion"=>"Rechazado")
                );
            
            case "solicitud_ciudad":
                return $this->Obtener_Lista_Catalogo("DEP"); // Departamento
            case "solicitud_rubro":
                return $this->Obtener_Lista_Catalogo("RUB"); // Rubro
                
            /*************** SPRINT 1.7 - FIN ****************************/
                
            // Onboarding
                
            case "codigo_agencia_fie":
                return $this->Obtener_Lista("estructura_regional WHERE estructura_regional.estructura_regional_id IN (" . $lista_region->region_id . ")","estructura_regional_id","CONCAT_WS(' ', estructura_regional_nombre, IF(estructura_regional_estado=0, '(Cerrada)', ''))");
                
            case "tercero_asistencia":
            case "sol_asistencia":
                return array(
                    (object) array("id"=>"0","descripcion"=>"No Asistido"),
                    (object) array("id"=>"1","descripcion"=>"Asistido")
                );
                
            case "terceros_estado":
                return array(
                    (object) array("id"=>"0","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(0, 'terceros_estado')),
                    (object) array("id"=>"1","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(1, 'terceros_estado')),
                    (object) array("id"=>"2","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(2, 'terceros_estado')),
                    (object) array("id"=>"3","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(3, 'terceros_estado')),
                    (object) array("id"=>"4","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(4, 'terceros_estado')),
                    (object) array("id"=>"5","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(5, 'terceros_estado')),
                    (object) array("id"=>"6","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(6, 'terceros_estado')),
                    // Req. Nuevos estados
                    (object) array("id"=>"7","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(7, 'terceros_estado')),
                    (object) array("id"=>"8","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(8, 'terceros_estado')),
                    
                    // Control de Cambios 28/10/2020
                    (object) array("id"=>"15","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(15, 'terceros_estado')),
                    // Req. Validación SEGIP
                    (object) array("id"=>"16","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(16, 'terceros_estado')),
                );
            
            // -- Req. Nuevos Estados
                
            case "terceros_estado_agencia_aux":
                return array(
                    (object) array("id"=>"6","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(6, 'terceros_estado')),
                    // Req. Nuevos estados
                    (object) array("id"=>"7","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(7, 'terceros_estado')),
                    (object) array("id"=>"8","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(8, 'terceros_estado')),
                );
                
            case "tipo_cuenta":
                return $this->Obtener_Lista_Catalogo("tipo_cuenta"); // Rubro
                
            // -- Req. Consulta COBIS y SEGIP

            case "ws_segip_codigo_resultado":
                return $this->Obtener_Lista_Catalogo("segip_codigo_respuesta"); // Rubro
                
            case "segip_operador_resultado":
                return array(
                    (object) array("id"=>"0","descripcion"=>"No Satisfactorio"),
                    (object) array("id"=>"1","descripcion"=>"Satisfactorio"),
                    (object) array("id"=>"2","descripcion"=>"No Realizado")
                );
                
            // -- Req. Flujo COBIS
                
            case "f_cobis_actual_etapa":
                return array(
                    (object) array("id"=>"0","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(0, 'flujo_cobis_etapa')),
                    (object) array("id"=>"1","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(1, 'flujo_cobis_etapa')),
                    (object) array("id"=>"2","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(2, 'flujo_cobis_etapa')),
                    (object) array("id"=>"3","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(3, 'flujo_cobis_etapa')),
                    (object) array("id"=>"4","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(4, 'flujo_cobis_etapa')),
                    (object) array("id"=>"5","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(5, 'flujo_cobis_etapa')),
                    (object) array("id"=>"6","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(6, 'flujo_cobis_etapa')),
                    (object) array("id"=>"99","descripcion"=>$this->mfunciones_generales->GetValorCatalogo(99, 'flujo_cobis_etapa'))
                );
                
            case "f_cobis_excepcion_motivo":
                return $this->Obtener_Lista_Catalogo("motivo_flujo_cobis"); // Rubro
                
            // Solicitud de Crédito
            
            case "sol_codigo_rubro":
                return $this->Obtener_Lista("tipo_persona WHERE categoria_persona_id=1 AND tipo_persona_vigente=1 AND tipo_persona_id IN (1,2,3,4,7,8,9,10,11,12)","tipo_persona_id","tipo_persona_nombre");
                
            case "sol_estado":
                return array(
                    (object) array("id" => "0", "descripcion" => "Registrado"),
                    (object) array("id" => "1", "descripcion" => "Asignado"),
                    (object) array("id" => "2", "descripcion" => "Consolidado"),
                    (object) array("id" => "3", "descripcion" => "Rechazado")
                );
                
            case "sol_evaluacion":
                return array(
                    (object) array("id" => "0", "descripcion" => "No Evaluado"),
                    (object) array("id" => "1", "descripcion" => "Aprobado"),
                    (object) array("id" => "2", "descripcion" => "Rechazado"),
                );
                
            case "sol_dependencia":
                return array(
                    (object) array("id" => "1", "descripcion" => "Dependiente"),
                    (object) array("id" => "2", "descripcion" => "Independiente"),
                );
        }
        return array();
    }

    /**
     * Busca las opciones, para los filtros, de uan tabla especifica
     * @param $tabla
     * @param $campo_id
     * @param $campo_descripcion
     * @return array
     */
    private function Obtener_Lista($tabla, $campo_id, $campo_descripcion) {
        try {
            $consulta = $this->db->query("select $campo_id as id, $campo_descripcion as descripcion from $tabla order by $campo_descripcion");
            $filas_consultadas = $consulta->result();
            return $filas_consultadas;
        } catch (Exception $ex) {
            return array();
        }
    }

    /**
     * Busca las opciones en la tabla catalogo para los filtros
     * @param $tipo
     * @return array
     */
    private function Obtener_Lista_Catalogo($tipo) {
        try {
            $consulta = $this->db->query("select catalogo_codigo as id, catalogo_descripcion as descripcion from catalogo where catalogo_tipo_codigo = '$tipo' AND catalogo_estado=1 order by catalogo_descripcion");
            $filas_consultadas = $consulta->result();
            return $filas_consultadas;
        } catch (Exception $ex) {
            return array();
        }
    }

}