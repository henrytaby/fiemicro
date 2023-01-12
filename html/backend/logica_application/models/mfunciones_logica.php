<?php

class Mfunciones_logica extends CI_Model {

    // Verifiación de Roles para el Acceso
    function ObtenerRolesMenu($menu_codigo, $rol_codigo)
    {        
        try 
        {
            $sql = "SELECT rol_id FROM rol_menu WHERE menu_id IN (" . $menu_codigo . ") AND rol_id=? ";

            $consulta = $this->db->query($sql, array($rol_codigo));

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
    
    function ObtenerMenuPorRol($rol_codigo)
    {        
        try 
        {
            $sql = "SELECT m.menu_orden, m.menu_nombre, m.menu_enlace AS 'menu_ruta' FROM rol_menu rm INNER JOIN rol r ON r.rol_id=rm.rol_id INNER JOIN menu m ON m.menu_id=rm.menu_id WHERE r.rol_id=? ORDER BY m.menu_orden, m.menu_nombre "; 

            $consulta = $this->db->query($sql, array($rol_codigo));

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
    
    // Auditoria

    function InsertarAuditoriaAcceso($auditoria_usuario, $auditoria_accion, $auditoria_fecha, $auditoria_ip) 
    {
        try 
        {
            $sql = "INSERT INTO auditoria_acceso (auditoria_usuario, auditoria_tipo_acceso, auditoria_fecha, auditoria_ip) VALUES (?, ?, ?, ?) "; 

            $consulta = $this->db->query($sql, array($auditoria_usuario, $auditoria_accion, $auditoria_fecha, $auditoria_ip));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
	
    function InsertarAuditoria($auditoria_usuario, $auditoria_fecha, $auditoria_tabla, $auditoria_accion, $auditoria_ip) 
    {        
        try 
        {
            $sql = "INSERT INTO auditoria (auditoria_usuario, auditoria_fecha, auditoria_tabla, auditoria_accion, auditoria_ip) VALUES (?, ?, ?, ?, ?) "; 

            $consulta = $this->db->query($sql, array($auditoria_usuario, $auditoria_fecha, $auditoria_tabla, $auditoria_accion, $auditoria_ip));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UsuarioActualizarFechaLogin($fecha_login, $usuario_codigo) 
    {        
        try 
        {
            $sql = "UPDATE usuarios SET usuario_fecha_ultimo_acceso = ? WHERE usuario_id = ? "; 

            $consulta = $this->db->query($sql, array($fecha_login, $usuario_codigo));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerAuditoriaTablas() 
    {        
        try
        {
            $sql = "SELECT table_name FROM auditoria GROUP BY table_name ORDER BY table_name ASC "; 

            $consulta = $this->db->query($sql);

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
    
    function ObtenerAuditoriaAcceso() 
    {        
        try
        {
            $sql = "SELECT auditoria_usuario FROM auditoria_acceso GROUP BY auditoria_usuario ORDER BY auditoria_usuario ASC "; 

            $consulta = $this->db->query($sql);

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
    
    function ObtenerAuditoriaTipoAcceso() 
    {        
        try
        {
            $sql = "SELECT auditoria_tipo_acceso FROM auditoria_acceso GROUP BY auditoria_tipo_acceso ORDER BY auditoria_tipo_acceso ASC "; 

            $consulta = $this->db->query($sql);

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
	
    function ObtenerAuditoriaUsuarios() 
    {        
        try 
        {
            $sql = "SELECT usuario_id, usuario_user, usuario_nombres, usuario_app, usuario_apm FROM usuarios ORDER BY usuario_app ASC "; 
            
            $consulta = $this->db->query($sql);

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
	
    function ReporteAuditoria($tabla, $usuario, $fecha_inicio, $fecha_fin, $filtro_fechas)
    {        
        try 
        {
            $criterio = " WHERE audit_id > 0";

            if($tabla != -1)
            {
                    $criterio .= " AND table_name='" . $tabla . "'";
            }

            if($usuario != -1)
            {
                    $criterio .= " AND accion_usuario='" . $usuario . "'";
            }

            if($filtro_fechas == 1)
            {
                    $criterio .= " AND accion_fecha BETWEEN '" . $fecha_inicio . " 00:00:01' AND '" . $fecha_fin . " 23:59:59'";
            }			
			
            $sql = "SELECT audit_id, accion_usuario, table_name, pk1, pk2, action, accion_fecha FROM auditoria " . $criterio; 

            $consulta = $this->db->query($sql, array($criterio));

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
	
    function ReporteAuditoriaDetalle($codigo)
    {        
        try 
        {
            $sql = "SELECT audit_meta_id, audit_id, col_name, old_value, new_value FROM auditoria_meta WHERE NOT(old_value <=> new_value) AND audit_id=? "; 

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function ReporteAuditoriaAcceso($acceso, $accion, $fecha_inicio, $fecha_fin, $filtro_fechas)
    {        
        try 
        {
            $criterio = " WHERE auditoria_id > 0";

            if($acceso != -1)
            {
                $criterio .= " AND auditoria_usuario='" . $acceso . "'";
            }

            if($accion != -1)
            {
                $criterio .= " AND auditoria_tipo_acceso='" . $accion . "'";
            }

            if($filtro_fechas == 1)
            {
                $criterio .= " AND auditoria_fecha BETWEEN '" . $fecha_inicio . " 00:00:01' AND '" . $fecha_fin . " 23:59:59'";
            }			
			
            $sql = "SELECT auditoria_id, auditoria_usuario, auditoria_tipo_acceso, auditoria_fecha, auditoria_ip FROM auditoria_acceso " . $criterio; 

            $consulta = $this->db->query($sql, array($criterio));

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
    
    function InsertarAuditoriaMovil($auditoria_movil_servicio, $auditoria_movil_parametros, $auditoria_movil_geo, $accion_usuario, $accion_fecha)
    {
        try 
        {
            $sql = "INSERT INTO auditoria_movil(auditoria_movil_servicio, auditoria_movil_parametros, auditoria_movil_geo, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?) "; 

            $consulta = $this->db->query($sql, array($auditoria_movil_servicio, substr($auditoria_movil_parametros,0,5000), $auditoria_movil_geo, $accion_usuario, $accion_fecha));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // SERVICIOS REST APP
    
    function VerificaCredencialesAPP($usu_login, $usu_password) 
    {        
        try 
        {
            $sql = "SELECT r.perfil_app_id, pa.perfil_app_nombre, u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email, u.usuario_telefono, u.usuario_direccion, u.usuario_rol, u.usuario_id, u.usuario_user, u.usuario_fecha_creacion, u.usuario_fecha_ultimo_acceso, u.usuario_fecha_ultimo_password, u.usuario_activo, e.ejecutivo_id 
                    FROM usuarios u
                    INNER JOIN rol r ON r.rol_id=u.usuario_rol
                    INNER JOIN perfil_app pa ON pa.perfil_app_id=r.perfil_app_id
                    INNER JOIN ejecutivo e ON u.usuario_id = e.usuario_id 
                    WHERE u.usuario_user = ? AND u.usuario_pass = ? LIMIT 1"; 

            $consulta = $this->db->query($sql, array($usu_login, $usu_password));

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
    
    function ObtenerBandejaProspectos($codigo_ejecutivo, $consolidado) 
    {        
        try 
        {
            $sql = "SELECT p.onboarding, p.general_interes, p.general_ci, p.general_ci_extension, p.prospecto_id, p.ejecutivo_id, p.empresa_id, e.empresa_categoria, p.prospecto_fecha_asignacion, p.prospecto_consolidado, p.prospecto_observado_app, p.general_solicitante AS 'empresa_nombre_legal', p.general_direccion AS 'empresa_direccion', p.prospecto_checkin_geo, p.general_telefono AS 'contacto', c.camp_id, c.camp_nombre, et.etapa_nombre, et.etapa_color 
                    FROM prospecto p 
                    INNER JOIN empresa e ON p.empresa_id=e.empresa_id 
                    INNER JOIN campana c ON c.camp_id=p.camp_id 
                    INNER JOIN hito ON hito.etapa_id=p.prospecto_etapa AND hito.prospecto_id=p.prospecto_id 
                    INNER JOIN etapa et ON et.etapa_id=p.prospecto_etapa
                    WHERE p.prospecto_consolidado=0 AND p.ejecutivo_id=? AND c.camp_id=? AND p.general_categoria=1 ORDER BY p.prospecto_fecha_asignacion DESC "; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo, $consolidado));

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
    
    function ObtenerBandejaProspectos_Carga($codigo_ejecutivo, $onboarding) 
    {        
        try 
        {
            $sql = "SELECT p.onboarding, p.general_interes, p.general_ci, p.general_ci_extension, p.prospecto_id, p.ejecutivo_id, p.empresa_id, e.empresa_categoria, p.prospecto_fecha_asignacion, p.prospecto_consolidado, p.prospecto_observado_app, p.general_solicitante AS 'empresa_nombre_legal', p.general_direccion AS 'empresa_direccion', p.prospecto_checkin_geo, p.general_telefono AS 'contacto', c.camp_id, c.camp_nombre, et.etapa_nombre, et.etapa_color 
                    FROM prospecto p 
                    INNER JOIN empresa e ON p.empresa_id=e.empresa_id 
                    INNER JOIN campana c ON c.camp_id=p.camp_id 
                    INNER JOIN hito ON hito.etapa_id=p.prospecto_etapa AND hito.prospecto_id=p.prospecto_id 
                    INNER JOIN etapa et ON et.etapa_id=p.prospecto_etapa
                    WHERE p.prospecto_consolidado=0 AND p.ejecutivo_id=? AND p.onboarding=? AND p.general_categoria=1 ORDER BY p.prospecto_fecha_asignacion DESC "; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo, $onboarding));

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
    
    function ObtenerProspectosEjecutivoAll($codigo_ejecutivo) 
    {        
        try 
        {
            $sql = "SELECT p.prospecto_id, c.camp_id, c.camp_nombre, p.ejecutivo_id, p.tipo_persona_id, p.prospecto_fecha_asignacion, p.prospecto_fecha_conclusion, p.prospecto_checkin, p.prospecto_llamada, p.prospecto_consolidado, p.prospecto_observado_app, p.general_solicitante, p.general_ci, p.general_ci_extension, p.prospecto_etapa, p.prospecto_evaluacion
                    FROM prospecto p 
                    INNER JOIN campana c ON c.camp_id=p.camp_id
                    WHERE p.general_categoria=1 AND p.ejecutivo_id=? ORDER BY p.prospecto_fecha_asignacion DESC"; 

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
    
    function CalculoRubrosEjecutivo($codigo_ejecutivo) 
    {        
        try 
        {
            $sql = "SELECT COUNT(c.camp_id) as 'contador', c.camp_nombre, c.camp_color
                    FROM prospecto p 
                    INNER JOIN campana c ON c.camp_id=p.camp_id
                    WHERE p.general_categoria=1 AND p.ejecutivo_id=?
                    GROUP BY c.camp_id"; 

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
    
    function CalculoEtapasEjecutivo($codigo_ejecutivo) 
    {        
        try 
        {
            $sql = "SELECT COUNT(e.etapa_id) as 'contador', e.etapa_nombre, e.etapa_color
                    FROM prospecto p 
                    INNER JOIN etapa e ON e.etapa_id=p.prospecto_etapa
                    WHERE p.general_categoria=1 AND p.ejecutivo_id=?
                    GROUP BY e.etapa_id ORDER BY e.etapa_orden"; 

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
    
    function ObtenerProspectosEjecutivo($codigo_ejecutivo) 
    {        
        try 
        {
            $sql = "SELECT p.onboarding, p.prospecto_id, c.camp_id, c.camp_nombre, p.ejecutivo_id, p.tipo_persona_id, p.prospecto_fecha_asignacion, p.prospecto_fecha_conclusion, p.prospecto_checkin, p.prospecto_llamada, p.prospecto_consolidado, p.prospecto_observado_app, p.general_solicitante, p.general_ci, p.general_ci_extension
                    FROM prospecto p 
                    INNER JOIN campana c ON c.camp_id=p.camp_id
                    WHERE p.prospecto_consolidado=0 AND p.general_categoria=1 AND p.ejecutivo_id=? ORDER BY p.prospecto_fecha_asignacion DESC"; 

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
    
    function ObtenerObsProspectos($codigo_prospecto, $estado) 
    {        
        try 
        {
            $sql = "SELECT o.obs_id, o.prospecto_id, o.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app) as 'usuario_nombre', o.documento_id, d.documento_nombre, o.obs_tipo, o.obs_fecha, o.obs_detalle, o.obs_estado, o.accion_usuario, o.accion_fecha FROM observacion_documento o, usuarios u, documento d WHERE o.prospecto_id=? AND o.obs_estado=? AND o.usuario_id=u.usuario_id AND o.documento_id=d.documento_id ORDER BY obs_fecha ASC "; 

            $consulta = $this->db->query($sql, array($codigo_prospecto, $estado));

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
    
    function VerificaCheckOut($codigo_prospecto) 
    {        
        try 
        {
            $sql = "SELECT prospecto_id FROM prospecto WHERE prospecto_id=? AND prospecto_llamada=1 "; 

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function UpdateCheckOut($fechaCheckIn, $geoCheckIn, $codigo_prospecto, $usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_llamada=1, prospecto_llamada_fecha=?, prospecto_llamada_geo=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 

            $consulta = $this->db->query($sql, array($fechaCheckIn, $geoCheckIn, $usuario, $accion_fecha, $codigo_prospecto));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function VerificaCheckIn($codigo_prospecto) 
    {        
        try 
        {
            $sql = "SELECT prospecto_id FROM prospecto WHERE prospecto_id=? AND prospecto_checkin=1 "; 

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function UpdateCheckIn($fechaCheckIn, $geoCheckIn, $codigo_prospecto, $usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_checkin=1, prospecto_checkin_fecha=?, prospecto_checkin_geo=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 

            $consulta = $this->db->query($sql, array($fechaCheckIn, $geoCheckIn, $usuario, $accion_fecha, $codigo_prospecto));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
	
    function ObtenerDetalleProspecto_comercio($codigo_empresa, $codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT pros.general_telefono, pros.general_email, pros.general_direccion, pros.prospecto_checkin_geo, pros.prospecto_id, e.ejecutivo_id, pros.tipo_persona_id, e.empresa_id, e.empresa_consolidada, e.empresa_categoria, e.empresa_nit, e.empresa_adquiriente, e.empresa_tipo_sociedad, e.empresa_nombre_legal, e.empresa_nombre_fantasia, e.empresa_rubro, e.empresa_perfil_comercial, e.empresa_mcc, e.empresa_nombre_referencia, e.empresa_ha_desde, e.empresa_ha_hasta, e.empresa_dias_atencion, e.empresa_medio_contacto, e.empresa_email, e.empresa_dato_contacto, e.empresa_departamento, e.empresa_municipio, e.empresa_zona, e.empresa_tipo_calle, e.empresa_calle, e.empresa_numero, e.empresa_direccion_literal, e.empresa_direccion_geo, e.empresa_info_adicional, c.cal_visita_ini, c.cal_visita_fin FROM empresa e INNER JOIN prospecto pros ON pros.empresa_id=e.empresa_id INNER JOIN calendario c ON c.ejecutivo_id=pros.ejecutivo_id AND c.cal_id_visita=pros.prospecto_id INNER JOIN campana cam ON cam.camp_id=pros.camp_id WHERE e.empresa_categoria=1 AND c.cal_tipo_visita=1 AND pros.prospecto_consolidado = 0 AND e.empresa_id=? AND pros.prospecto_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_empresa, $codigo_prospecto));

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

    function ObtenerDetalleProspecto_establecimiento($codigo_empresa, $codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT pros.prospecto_id, e.ejecutivo_id, pros.tipo_persona_id, e.empresa_id, e.empresa_consolidada, e.empresa_categoria, e.empresa_depende, e.empresa_nit AS 'parent_nit', e.empresa_adquiriente AS 'parent_adquiriente', e.empresa_tipo_sociedad AS 'parent_tipo_sociedad', e.empresa_nombre_legal AS 'parent_nombre_legal', e.empresa_nombre_fantasia AS 'parent_nombre_fantasia', e.empresa_rubro AS 'parent_rubro', e.empresa_perfil_comercial AS 'parent_perfil_comercial', e.empresa_mcc AS 'parent_mcc', e.empresa_nombre_referencia, e.empresa_nombre_establecimiento, e.empresa_denominacion_corta, e.empresa_ha_desde, e.empresa_ha_hasta, e.empresa_dias_atencion, e.empresa_medio_contacto, e.empresa_email, e.empresa_dato_contacto, e.empresa_departamento, e.empresa_municipio, e.empresa_zona, e.empresa_tipo_calle, e.empresa_calle, e.empresa_numero, e.empresa_direccion_literal, e.empresa_direccion_geo, e.empresa_info_adicional, c.cal_visita_ini, c.cal_visita_fin FROM empresa e INNER JOIN prospecto pros ON pros.empresa_id=e.empresa_id INNER JOIN calendario c ON c.ejecutivo_id=e.ejecutivo_id AND c.cal_id_visita=pros.prospecto_id WHERE e.empresa_categoria=2 AND c.cal_tipo_visita = 1 AND pros.prospecto_consolidado = 0 AND e.empresa_id=? AND pros.prospecto_id=? ";

            $consulta = $this->db->query($sql, array($codigo_empresa, $codigo_prospecto));

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

    function ObtenerServicios($estado)
    {        
        try 
        {
            $sql = "SELECT servicio_id, servicio_detalle, servicio_activo, accion_usuario, accion_fecha FROM servicio WHERE servicio_activo=? ";

            $consulta = $this->db->query($sql, array($estado));

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

    function ObtenerDetalleProspecto_servicios($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT p.prospecto_servicio_id, p.servicio_id, s.servicio_detalle FROM prospecto_servicio p, servicio s WHERE prospecto_id=? AND p.servicio_id=s.servicio_id AND s.servicio_activo=1 ";

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function ObtenerComercioPorNIT($nit)
    {        
        try 
        {
            $sql = "SELECT empresa_id, empresa_nit, empresa_adquiriente, empresa_tipo_sociedad, empresa_nombre_legal, empresa_nombre_fantasia, empresa_rubro, empresa_perfil_comercial, empresa_mcc FROM empresa WHERE empresa_nit=? AND empresa_categoria=1 AND empresa_consolidada=1 LIMIT 1 ";

            $consulta = $this->db->query($sql, array($nit));

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
    
    function ObtenerComercioPorNITSolicitud($nit)
    {        
        try 
        {
            $sql = "SELECT empresa_id, empresa_nit, empresa_adquiriente, empresa_tipo_sociedad, empresa_nombre_legal, empresa_nombre_fantasia, empresa_rubro, empresa_perfil_comercial, empresa_mcc, empresa_consolidada FROM empresa WHERE empresa_nit=? AND empresa_categoria=1 LIMIT 1 ";

            $consulta = $this->db->query($sql, array($nit));

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
    
    function ObtenerEmpresaNIT($nit)
    {        
        try 
        {
            $sql = "SELECT e.empresa_id, e.ejecutivo_id, e.empresa_consolidada, e.empresa_categoria, 
                    CASE e.empresa_categoria
                      WHEN 1 then e.empresa_nombre_legal
                      WHEN 2 then e.empresa_nombre_establecimiento
                    END AS 'empresa_nombre', e.empresa_tipo_sociedad, e.empresa_rubro, e.empresa_perfil_comercial, e.empresa_mcc, CONCAT(u.usuario_app, ' ', u.usuario_apm, ' ', u.usuario_nombres) as 'ejecutivo_nombre', ej.usuario_id FROM empresa e
                    INNER JOIN ejecutivo ej ON ej.ejecutivo_id=e.ejecutivo_id
                    INNER JOIN usuarios u ON u.usuario_id=ej.usuario_id
                    WHERE empresa_consolidada=1 AND empresa_nit=? ";

            $consulta = $this->db->query($sql, array($nit));

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
    
    function ObtenerEmpresaNITEjecutivo($nit, $codigo_ejecutivo)
    {        
        try 
        {
            $sql = "SELECT empresa_id, ejecutivo_id, empresa_consolidada, empresa_categoria, empresa_nit, 
                    CASE empresa_categoria
                      WHEN 1 then IF(STRCMP(empresa_nombre_fantasia, '') = 0, empresa_nombre_legal, empresa_nombre_fantasia)
                      WHEN 2 then empresa_nombre_establecimiento
                    END AS 'empresa_nombre', empresa_tipo_sociedad, empresa_rubro, empresa_perfil_comercial, empresa_mcc FROM empresa WHERE CONCAT(empresa_nit, empresa_nombre_legal) LIKE CONCAT('%', ?, '%') ";

            $consulta = $this->db->query($sql, array($nit, $codigo_ejecutivo));

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
    
    function ObtenerHorariosEjecutivo($codigo_ejecutivo, $fecha_dia, $tipo_visita)
    {        
        try 
        {
            $criterio = "";
            if($fecha_dia != -1)
            {
                $criterio .= " AND cal_visita_ini BETWEEN '" . $fecha_dia . " 00:00:01' AND '" . $fecha_dia . " 23:59:59' ";
            }
            
            if($tipo_visita != -1)
            {
                $criterio .= " AND cal_tipo_visita=". $tipo_visita;
            }
            
            $sql = "SELECT ejecutivo_id, cal_id, cal_tipo_visita, cal_id_visita, cal_visita_ini, cal_visita_fin, empresa_id, empresa_categoria, empresa_nombre
                    FROM(
                            SELECT ej.ejecutivo_id, c.cal_id, c.cal_tipo_visita, c.cal_id_visita, c.cal_visita_ini, c.cal_visita_fin, e.empresa_id, e.empresa_categoria, 
                            CASE e.empresa_categoria
                                    WHEN 1 then e.empresa_nombre_legal
                                    WHEN 2 then e.empresa_nombre_establecimiento
                            END AS 'empresa_nombre'
                            FROM empresa e
                            INNER JOIN prospecto p ON p.empresa_id=e.empresa_id 
                            INNER JOIN ejecutivo ej ON ej.ejecutivo_id=p.ejecutivo_id
                            INNER JOIN calendario c ON c.ejecutivo_id=ej.ejecutivo_id AND c.cal_id_visita=p.prospecto_id AND c.cal_tipo_visita=1 AND p.prospecto_checkin=0 AND p.prospecto_consolidado=0

                            UNION ALL

                            SELECT ej.ejecutivo_id, c.cal_id, c.cal_tipo_visita, c.cal_id_visita, c.cal_visita_ini, c.cal_visita_fin, e.empresa_id, e.empresa_categoria, 
                            CASE e.empresa_categoria
                                    WHEN 1 then e.empresa_nombre_legal
                                    WHEN 2 then e.empresa_nombre_establecimiento
                            END AS 'empresa_nombre'
                            FROM empresa e
                            INNER JOIN mantenimiento m ON m.empresa_id=e.empresa_id 
                            INNER JOIN ejecutivo ej ON ej.ejecutivo_id=m.ejecutivo_id
                            INNER JOIN calendario c ON c.ejecutivo_id=ej.ejecutivo_id AND c.cal_id_visita=m.mant_id AND c.cal_tipo_visita=2 AND m.mant_checkin=0 AND m.mant_estado=0
                            ) a WHERE ejecutivo_id=? " . $criterio . " ORDER BY cal_visita_ini ASC ";

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
    
    function VerificaNitExistente($data)
    {        
        try 
        {
            $sql = "SELECT empresa_id FROM empresa WHERE empresa_nit=? ";

            $consulta = $this->db->query($sql, array($data));

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
    
    function InsertarProspecto_comercioAPP($ejecutivo_id, $empresa_categoria, $empresa_depende, $empresa_nit, $empresa_adquiriente, $empresa_tipo_sociedad, $empresa_nombre_referencia, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_direccion_geo, $empresa_info_adicional, $accion_usuario, $accion_fecha)
    {        
        try 
        {            
            $sql = "INSERT INTO empresa(ejecutivo_id, empresa_categoria, empresa_depende, empresa_nit, empresa_adquiriente, empresa_tipo_sociedad, empresa_nombre_referencia, empresa_nombre_legal, empresa_nombre_fantasia, empresa_rubro, empresa_perfil_comercial, empresa_mcc, empresa_ha_desde, empresa_ha_hasta, empresa_dias_atencion, empresa_medio_contacto, empresa_email, empresa_dato_contacto, empresa_departamento, empresa_municipio, empresa_zona, empresa_tipo_calle, empresa_calle, empresa_numero, empresa_direccion_literal, empresa_direccion_geo, empresa_info_adicional, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";

            $this->db->query($sql, array($ejecutivo_id, $empresa_categoria, $empresa_depende, $empresa_nit, $empresa_adquiriente, $empresa_tipo_sociedad, $empresa_nombre_referencia, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_direccion_geo, $empresa_info_adicional, $accion_usuario, $accion_fecha));

            $listaResultados = $this->db->insert_id();
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

            return $listaResultados;
    }
    
    function InsertarProspecto_establecimientoAPP($ejecutivo_id, $empresa_categoria, $empresa_depende, $empresa_nit, $empresa_adquiriente, $empresa_tipo_sociedad, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_nombre_referencia, $empresa_nombre_establecimiento, $empresa_denominacion_corta, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_direccion_geo, $empresa_info_adicional, $accion_usuario, $accion_fecha)
    {        
        try 
        {            
            $sql = "INSERT INTO empresa(ejecutivo_id, empresa_categoria, empresa_depende, empresa_nit, empresa_adquiriente, empresa_tipo_sociedad, empresa_rubro, empresa_perfil_comercial, empresa_mcc, empresa_nombre_legal, empresa_nombre_fantasia, empresa_nombre_referencia, empresa_nombre_establecimiento, empresa_denominacion_corta, empresa_ha_desde, empresa_ha_hasta, empresa_dias_atencion, empresa_medio_contacto, empresa_email, empresa_dato_contacto, empresa_departamento, empresa_municipio, empresa_zona, empresa_tipo_calle, empresa_calle, empresa_numero, empresa_direccion_literal, empresa_direccion_geo, empresa_info_adicional, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";

            $this->db->query($sql, array($ejecutivo_id, $empresa_categoria, $empresa_depende, $empresa_nit, $empresa_adquiriente, $empresa_tipo_sociedad, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_nombre_referencia, $empresa_nombre_establecimiento, $empresa_denominacion_corta, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_direccion_geo, $empresa_info_adicional, $accion_usuario, $accion_fecha));

            $listaResultados = $this->db->insert_id();
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

            return $listaResultados;
    }
    
    function InsertarProspecto_APP($ejecutivo_id, $tipo_persona_id, $empresa_id, $prospecto_fecha_asignacion, $accion_usuario, $accion_fecha)
    {        
        try 
        {            
            $sql = "INSERT INTO prospecto(ejecutivo_id, tipo_persona_id, empresa_id, prospecto_fecha_asignacion, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?) ";

            $this->db->query($sql, array($ejecutivo_id, $tipo_persona_id, $empresa_id, $prospecto_fecha_asignacion, $accion_usuario, $accion_fecha));

            $listaResultados = $this->db->insert_id();
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

            return $listaResultados;
    }
    
    function EliminarServiciosProspecto($codigo_prospecto)
    {        
        try 
        {
            $sql = "DELETE FROM prospecto_servicio WHERE prospecto_id=? ";

            $this->db->query($sql, array($codigo_prospecto));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarServiciosProspecto($prospecto_id, $servicio_id, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO prospecto_servicio(prospecto_id, servicio_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) ";

            $this->db->query($sql, array($prospecto_id, $servicio_id, $accion_usuario, $accion_fecha));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarFechaCaendario($ejecutivo_id, $cal_id_visita, $cal_tipo_visita, $cal_visita_ini, $cal_visita_fin, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO calendario(ejecutivo_id, cal_id_visita, cal_tipo_visita, cal_visita_ini, cal_visita_fin, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?) ";

            $this->db->query($sql, array($ejecutivo_id, $cal_id_visita, $cal_tipo_visita, $cal_visita_ini, $cal_visita_fin, $accion_usuario, $accion_fecha));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function VerificaProspectoConsolidado($codigo_prospecto) 
    {        
        try 
        {
            $sql = "SELECT prospecto_checkin, prospecto_checkin_geo, ejecutivo_id, onboarding, onboarding_codigo, prospecto_evaluacion, prospecto_consolidado, prospecto_estado_actual, prospecto_etapa, prospecto_observado_app, tipo_persona_id, prospecto_num_proceso FROM prospecto WHERE prospecto_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function VerificaProspectoRechazo($codigo_prospecto) 
    {        
        try 
        {
            $sql = "SELECT general_solicitante, prospecto_consolidado, prospecto_estado_actual, prospecto_etapa, prospecto_observado_app, tipo_persona_id FROM prospecto WHERE prospecto_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function RechazoProspecto($rechazo_detalle, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_rechazado=2, prospecto_rechazado_fecha=?, prospecto_rechazado_detalle=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($accion_fecha, $rechazo_detalle, $accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
	
    function ActualizarProspecto($empresa_tipo_sociedad, $empresa_nombre_referencia, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_nombre_establecimiento, $empresa_denominacion_corta, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_direccion_geo, $empresa_info_adicional, $accion_usuario, $accion_fecha, $prospecto_id, $empresa_id, $ejecutivo_id, $tipo_persona_id, $cal_visita_ini, $cal_visita_fin)
    {        
        try 
        {
            $sql = "UPDATE empresa SET empresa_tipo_sociedad=?, empresa_nombre_referencia=?, empresa_nombre_legal=?, empresa_nombre_fantasia=?, empresa_rubro=?, empresa_perfil_comercial=?, empresa_mcc=?, empresa_nombre_establecimiento=?, empresa_denominacion_corta=?, empresa_ha_desde=?, empresa_ha_hasta=?, empresa_dias_atencion=?, empresa_medio_contacto=?, empresa_email=?, empresa_dato_contacto=?, empresa_departamento=?, empresa_municipio=?, empresa_zona=?, empresa_tipo_calle=?, empresa_calle=?, empresa_numero=?, empresa_direccion_literal=?, empresa_direccion_geo=?, empresa_info_adicional=?, accion_usuario=?, accion_fecha=? WHERE empresa_id=? AND ejecutivo_id=? ";

            $this->db->query($sql, array($empresa_tipo_sociedad, $empresa_nombre_referencia, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_nombre_establecimiento, $empresa_denominacion_corta, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_direccion_geo, $empresa_info_adicional, $accion_usuario, $accion_fecha, $empresa_id, $ejecutivo_id));
        
            $sql2 = "UPDATE prospecto SET tipo_persona_id=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? ";

            $this->db->query($sql2, array($tipo_persona_id, $accion_usuario, $accion_fecha, $prospecto_id));
            
            $sql3 = "UPDATE calendario SET cal_visita_ini=?, cal_visita_fin=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? AND cal_id_visita=?";

            $this->db->query($sql3, array($cal_visita_ini, $cal_visita_fin, $accion_usuario, $accion_fecha, $ejecutivo_id, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerDocumentosEnviar($prospecto)
    {        
        try 
        {
            $sql = "SELECT d.documento_id, d.documento_nombre FROM tipo_persona_documento tpd INNER JOIN documento d ON d.documento_id=tpd.documento_id INNER JOIN tipo_persona tp ON tp.tipo_persona_id=tpd.tipo_persona_id INNER JOIN prospecto p ON p.tipo_persona_id=tp.tipo_persona_id WHERE d.documento_vigente=1 AND d.documento_enviar=1 AND d.documento_pdf IS NOT NULL AND p.prospecto_id=? ORDER BY d.documento_nombre ASC ";

            $consulta = $this->db->query($sql, array($prospecto));

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
    
    function ObtenerDocumentosEnviarApp($prospecto)
    {        
        try 
        {
            $sql = "SELECT d.documento_id, d.documento_nombre FROM tipo_persona_documento tpd INNER JOIN documento d ON d.documento_id=tpd.documento_id INNER JOIN tipo_persona tp ON tp.tipo_persona_id=tpd.tipo_persona_id INNER JOIN prospecto p ON p.tipo_persona_id=tp.tipo_persona_id WHERE d.documento_id>0 AND d.documento_vigente=1 AND d.documento_enviar=1 AND d.documento_pdf IS NOT NULL AND p.prospecto_id=? ORDER BY d.documento_nombre ASC ";

            $consulta = $this->db->query($sql, array($prospecto));

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
    
    function ObtenerDetalleEmpresaCorreo($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT camp.camp_nombre, p.general_email, p.general_solicitante, p.general_destino, p.prospecto_id, e.empresa_id, e.empresa_email, e.empresa_nombre_referencia, e.empresa_categoria as 'empresa_categoria_codigo', ej.ejecutivo_id, 
                        CASE e.empresa_categoria
                            WHEN 1 then e.empresa_nombre_legal
                            WHEN 2 then e.empresa_nombre_establecimiento
                        END AS 'empresa_nombre',
                        CASE e.empresa_categoria
                            WHEN 1 then 'Comercio'
                            WHEN 2 then 'Establecimiento'
                        END AS 'empresa_categoria',
                        CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) as 'ejecutivo_asignado_nombre', u.usuario_telefono as 'ejecutivo_asignado_contacto', u.usuario_email as 'ejecutivo_asignado_correo', u.usuario_id, e.empresa_nit, e.empresa_adquiriente, e.empresa_rubro, e.empresa_perfil_comercial, e.empresa_mcc, e.empresa_ha_desde, e.empresa_ha_hasta, e.empresa_dias_atencion, e.empresa_medio_contacto, e.empresa_email, e.empresa_dato_contacto, e.empresa_departamento, e.empresa_municipio, e.empresa_zona, e.empresa_tipo_calle, e.empresa_calle, e.empresa_numero, e.empresa_direccion_literal, e.empresa_direccion_geo, e.empresa_info_adicional, p.tipo_persona_id
                    FROM empresa e
                    INNER JOIN prospecto p ON p.empresa_id=e.empresa_id
                    INNER JOIN ejecutivo ej ON ej.ejecutivo_id=p.ejecutivo_id
                    INNER JOIN usuarios u ON u.usuario_id=ej.usuario_id
                    INNER JOIN campana camp ON camp.camp_id=p.camp_id
                    WHERE p.prospecto_id=? ";

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function ObtenerDetalleEmpresa($codigo_empresa)
    {        
        try 
        {
            $sql = "SELECT e.empresa_id, e.empresa_consolidada, e.empresa_tipo_sociedad, e.empresa_email, e.empresa_nombre_referencia, empresa_nombre_legal, empresa_nombre_fantasia, empresa_nombre_establecimiento, empresa_denominacion_corta, empresa_categoria,
                        CASE e.empresa_categoria
                            WHEN 1 then e.empresa_nombre_legal
                            WHEN 2 then e.empresa_nombre_establecimiento
                        END AS 'empresa_nombre',
                        CASE e.empresa_categoria
                            WHEN 1 then 'Comercio'
                            WHEN 2 then 'Establecimiento'
                        END AS 'empresa_categoria',
                        CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) as 'ejecutivo_asignado_nombre', u.usuario_telefono as 'ejecutivo_asignado_contacto', u.usuario_email as 'ejecutivo_asignado_correo', u.usuario_id, e.empresa_nit, e.empresa_adquiriente, e.empresa_rubro, e.empresa_perfil_comercial, e.empresa_mcc, e.empresa_ha_desde, e.empresa_ha_hasta, e.empresa_dias_atencion, e.empresa_medio_contacto, e.empresa_email, e.empresa_dato_contacto, e.empresa_departamento, e.empresa_municipio, e.empresa_zona, e.empresa_tipo_calle, e.empresa_calle, e.empresa_numero, e.empresa_direccion_literal, e.empresa_direccion_geo, e.empresa_info_adicional
                    FROM empresa e
                    INNER JOIN ejecutivo ej ON ej.ejecutivo_id=e.ejecutivo_id
                    INNER JOIN usuarios u ON u.usuario_id=ej.usuario_id
                    WHERE e.empresa_id=? ";

            $consulta = $this->db->query($sql, array($codigo_empresa));

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

    function ObtenerDocumentosDigitalizarApp($prospecto)
    {        
        try 
        {
            $sql = "SELECT d.documento_id, d.documento_nombre FROM tipo_persona_documento tpd INNER JOIN documento d ON d.documento_id=tpd.documento_id INNER JOIN tipo_persona tp ON tp.tipo_persona_id=tpd.tipo_persona_id INNER JOIN prospecto p ON p.tipo_persona_id=tp.tipo_persona_id WHERE d.documento_vigente=1 AND d.documento_enviar=0 AND d.documento_id>0 AND p.prospecto_id=? ORDER BY d.documento_id ASC ";

            $consulta = $this->db->query($sql, array($prospecto));

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
    
    function ObtenerDocumentosDigitalizar($prospecto)
    {        
        try 
        {
            $sql = "SELECT d.documento_id, d.documento_nombre FROM tipo_persona_documento tpd INNER JOIN documento d ON d.documento_id=tpd.documento_id INNER JOIN tipo_persona tp ON tp.tipo_persona_id=tpd.tipo_persona_id INNER JOIN prospecto p ON p.tipo_persona_id=tp.tipo_persona_id WHERE d.documento_vigente=1 AND p.prospecto_id=? ORDER BY d.documento_id ASC ";

            $consulta = $this->db->query($sql, array($prospecto));

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
    
    function ObtenerDocumentosDigitalizarOnb($prospecto)
    {        
        try 
        {
            $sql = "SELECT d.documento_id, d.documento_nombre FROM tipo_persona_documento tpd INNER JOIN documento d ON d.documento_id=tpd.documento_id INNER JOIN tipo_persona tp ON tp.tipo_persona_id=tpd.tipo_persona_id INNER JOIN prospecto p ON p.tipo_persona_id=tp.tipo_persona_id WHERE d.documento_vigente=1 AND d.documento_enviar=0 AND p.prospecto_id=? ORDER BY d.documento_id ASC ";

            $consulta = $this->db->query($sql, array($prospecto));

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
    
    function VerificaDocumentosDigitalizar($codigo_prospecto, $codigo_documento)
    {        
        try 
        {
            $sql = "SELECT pd.prospecto_documento_pdf, CONCAT(p.prospecto_carpeta, '_', p.prospecto_id) as 'prospecto_carpeta' FROM prospecto_documento pd, prospecto p WHERE p.prospecto_id=pd.prospecto_id AND pd.prospecto_id=? AND pd.documento_id=? ORDER BY pd.prospecto_documento_id DESC LIMIT 1 ";

            $consulta = $this->db->query($sql, array($codigo_prospecto, $codigo_documento));

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
    
    function ObtenerDocumentoDigitalizar($codigo_prospecto, $codigo_documento)
    {        
        try 
        {
            $sql = "SELECT pd.prospecto_documento_pdf, CONCAT(p.prospecto_carpeta, '_', p.prospecto_id) as 'prospecto_carpeta' FROM prospecto_documento pd, prospecto p WHERE p.prospecto_id=pd.prospecto_id AND pd.prospecto_id=? AND pd.prospecto_documento_id=? ORDER BY pd.prospecto_documento_id DESC LIMIT 1 ";

            $consulta = $this->db->query($sql, array($codigo_prospecto, $codigo_documento));

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
    
    function ListaDocumentosDigitalizar($codigo_prospecto, $codigo_documento)
    {        
        try 
        {
            $sql = "SELECT pd.prospecto_documento_id, pd.prospecto_documento_pdf, CONCAT(p.prospecto_carpeta, '_', p.prospecto_id) as 'prospecto_carpeta', pd.accion_fecha FROM prospecto_documento pd, prospecto p WHERE p.prospecto_id=pd.prospecto_id AND pd.prospecto_id=? AND pd.documento_id=? ORDER BY pd.prospecto_documento_id DESC ";

            $consulta = $this->db->query($sql, array($codigo_prospecto, $codigo_documento));

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
    
    function ObtenerNombreDocumento($codigo_documento)
    {        
        try 
        {
            $sql = "SELECT documento_nombre, documento_vigente, documento_enviar, documento_pdf, documento_mandatorio FROM documento WHERE documento_id=? ";

            $consulta = $this->db->query($sql, array($codigo_documento));

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
	
    function ObtenerNombreDocumentoEnviar($codigo_documento)
    {        
        try 
        {
            $sql = "SELECT documento_nombre, documento_vigente, documento_enviar, documento_pdf FROM documento WHERE documento_id=? AND documento_enviar=1 AND documento_pdf IS NOT NULL ";

            $consulta = $this->db->query($sql, array($codigo_documento));

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
	
    function InsertarDocumentoProspecto($prospecto_id, $documento_id, $prospecto_documento_pdf, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO prospecto_documento(prospecto_id, documento_id, prospecto_documento_pdf, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?) ";

            $consulta = $this->db->query($sql, array($prospecto_id, $documento_id, $prospecto_documento_pdf, $accion_usuario, $accion_fecha));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
	
    function ActualizarProspecto_EnviarCumplimiento($accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_estado_actual=1, prospecto_observado_app=0, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? ";

            $consulta = $this->db->query($sql, array($accion_usuario, $accion_fecha, $prospecto_id));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
	
    function ConsolidarProspecto($geolocalizacion, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_consolidado=1, prospecto_observado_app=0, prospecto_consolidar_geo=?, prospecto_consolidar_fecha=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? ";

            $consulta = $this->db->query($sql, array($geolocalizacion, $accion_fecha, $accion_usuario, $accion_fecha, $prospecto_id));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ForzarConsolidarProspecto($geolocalizacion, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_estado_actual=3, prospecto_consolidado=1, prospecto_observado_app=0, prospecto_consolidar_geo=?, prospecto_consolidar_fecha=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? ";

            $consulta = $this->db->query($sql, array($geolocalizacion, $accion_fecha, $accion_usuario, $accion_fecha, $prospecto_id));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ActualizarFlagAuxProspecto($prospecto_aux_cump, $prospecto_aux_legal, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_aux_cump=?, prospecto_aux_legal=? WHERE prospecto_id=? ";

            $consulta = $this->db->query($sql, array($prospecto_aux_cump, $prospecto_aux_legal, $prospecto_id));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Mantenimientos
    
    function ObtenerBandejaMantenimientos($codigo_ejecutivo, $estado)
    {        
        try 
        {
            $sql = "SELECT m.mant_id, m.ejecutivo_id, m.empresa_id, e.empresa_categoria, m.mant_fecha_asignacion, m.mant_estado, CASE e.empresa_categoria WHEN 1 then e.empresa_nombre_legal WHEN 2 then e.empresa_nombre_establecimiento END AS 'empresa_nombre_legal', e.empresa_direccion_literal AS 'empresa_direccion', e.empresa_dato_contacto AS 'contacto', c.cal_visita_ini, c.cal_visita_fin, e.empresa_direccion_geo FROM mantenimiento m INNER JOIN empresa e ON e.empresa_id=m.empresa_id INNER JOIN calendario c ON c.cal_id_visita=m.mant_id AND c.cal_tipo_visita=2 WHERE m.ejecutivo_id=? AND m.mant_estado=? ORDER BY m.mant_fecha_asignacion DESC "; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo, $estado));

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
    
    function ObtenerMantenimientosEjecutivos($codigo_ejecutivo)
    {        
        try 
        {
            $sql = "SELECT m.mant_id, m.ejecutivo_id, m.empresa_id, e.empresa_categoria, m.mant_fecha_asignacion, m.mant_estado, CASE e.empresa_categoria WHEN 1 then e.empresa_nombre_legal WHEN 2 then e.empresa_nombre_establecimiento END AS 'empresa_nombre_legal', e.empresa_direccion_literal AS 'empresa_direccion', e.empresa_dato_contacto AS 'contacto', c.cal_visita_ini, c.cal_visita_fin, e.empresa_direccion_geo FROM mantenimiento m INNER JOIN empresa e ON e.empresa_id=m.empresa_id INNER JOIN calendario c ON c.cal_id_visita=m.mant_id AND c.cal_tipo_visita=2 WHERE m.ejecutivo_id=? ORDER BY m.mant_fecha_asignacion DESC "; 

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
    
    function VerificaCheckInMantenimiento($codigo_mantenimiento)
    {        
        try 
        {
            $sql = "SELECT mant_id FROM mantenimiento WHERE mant_id=? AND mant_checkin=1 "; 

            $consulta = $this->db->query($sql, array($codigo_mantenimiento));

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
    
    function UpdateCheckInMantenimiento($fechaCheckIn, $geoCheckIn, $codigo_mantenimiento, $usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "UPDATE mantenimiento SET mant_checkin=1, mant_checkin_fecha=?, mant_checkin_geo=?, accion_usuario=?, accion_fecha=? WHERE mant_id=? "; 

            $consulta = $this->db->query($sql, array($fechaCheckIn, $geoCheckIn, $usuario, $accion_fecha, $codigo_mantenimiento));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerTareas($codigo_perfil=1)
    {        
        try 
        {
            $sql = "SELECT t.tarea_id, t.tarea_detalle, t.tarea_activo, t.accion_usuario, t.accion_fecha 
                    FROM tarea t
                    INNER JOIN perfil_app pa ON pa.perfil_app_id=t.perfil_app_id
                    WHERE t.perfil_app_id IN (0, " . $codigo_perfil . ") AND t.tarea_activo=1";
            
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
    
    function VerificaMantenimientoCompletado($codigo_mantenimiento) 
    {        
        try 
        {
            $sql = "SELECT mant_estado FROM mantenimiento WHERE mant_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_mantenimiento));

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
    
    function UpdateHorarioMantenimiento($fecha_visita_ini, $fecha_visita_fin, $codigo_ejecutivo, $codigo_mantenimiento, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "UPDATE calendario SET cal_visita_ini=?, cal_visita_fin=?, accion_usuario=?, accion_fecha=? WHERE cal_tipo_visita=2 AND ejecutivo_id=? AND cal_id_visita=? "; 

            $consulta = $this->db->query($sql, array($fecha_visita_ini, $fecha_visita_fin, $accion_usuario, $accion_fecha, $codigo_ejecutivo, $codigo_mantenimiento));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarMantenimiento($ejecutivo_id, $empresa_id, $mant_fecha_asignacion, $accion_usuario, $accion_fecha)
    {        
        try 
        {            
            $sql = "INSERT INTO mantenimiento(ejecutivo_id, empresa_id, mant_fecha_asignacion, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?) ";

            $this->db->query($sql, array($ejecutivo_id, $empresa_id, $mant_fecha_asignacion, $accion_usuario, $accion_fecha));

            $listaResultados = $this->db->insert_id();
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

            return $listaResultados;
    }
    
    function UpdateSubirDocumentoMantenimiento($mant_documento_adjunto, $accion_usuario, $accion_fecha, $codigo_mantenimiento)
    {        
        try 
        {
            $sql = "UPDATE mantenimiento SET mant_documento_adjunto=?, accion_usuario=?, accion_fecha=? WHERE mant_id=? "; 

            $consulta = $this->db->query($sql, array($mant_documento_adjunto, $accion_usuario, $accion_fecha, $codigo_mantenimiento));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function EliminarTareasMantenimiento($codigo_mantenimiento)
    {        
        try 
        {
            $sql = "DELETE FROM mantenimiento_tarea WHERE mant_id=? ";

            $this->db->query($sql, array($codigo_mantenimiento));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarTareasMantenimiento($codigo_mantenimiento, $tarea_id, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO mantenimiento_tarea(mant_id, tarea_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) ";

            $this->db->query($sql, array($codigo_mantenimiento, $tarea_id, $accion_usuario, $accion_fecha));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function CompletarMantenimiento($mant_completado_geo, $mant_otro, $mant_otro_detalle, $accion_usuario, $accion_fecha, $codigo_mantenimiento)
    {        
        try 
        {
            $sql = "UPDATE mantenimiento SET mant_estado=1, mant_completado_fecha=?, mant_completado_geo=?, mant_otro=?, mant_otro_detalle=?, accion_usuario=?, accion_fecha=? WHERE mant_id=? ";

            $this->db->query($sql, array($accion_fecha, $mant_completado_geo, $mant_otro, $mant_otro_detalle, $accion_usuario, $accion_fecha, $codigo_mantenimiento));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Estadísticas
    
    function ReporteVisitasEntreFechas($codigo_ejecutivo, $fecha_ini, $fecha_fin)
    {        
        try 
        {
            $sql = " SELECT fecha, SUM(prospecto) AS 'prospecto', SUM(mantenimiento) AS 'mantenimiento'
                    FROM(
                            SELECT DATE(prospecto_consolidar_fecha) AS 'fecha', COUNT(*) AS 'prospecto', 0 AS 'mantenimiento' FROM prospecto WHERE ejecutivo_id=? AND prospecto_consolidado=1 AND prospecto_consolidar_fecha BETWEEN ? AND ? GROUP BY DATE(prospecto_consolidar_fecha)

                            UNION ALL

                            SELECT DATE(mant_completado_fecha) AS 'fecha', 0 AS 'prospecto', COUNT(*) AS 'mantenimiento' FROM mantenimiento WHERE ejecutivo_id=? AND mant_estado=1 AND mant_completado_fecha BETWEEN ? AND ? GROUP BY DATE(mant_completado_fecha)
                        ) a  
                    GROUP BY fecha
                    ORDER BY fecha ASC "; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo, $fecha_ini, $fecha_fin, $codigo_ejecutivo, $fecha_ini, $fecha_fin));

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
    
    // Entrega del Servicio
    
    function ObtenerBandejaEntregaServicio($codigo_ejecutivo, $entrega_servicio) 
    {        
        try 
        {
            $sql = "SELECT p.prospecto_id, p.ejecutivo_id, p.empresa_id, e.empresa_categoria, p.prospecto_aceptado_afiliado_fecha, p.prospecto_entrega_servicio, CASE e.empresa_categoria WHEN 1 then e.empresa_nombre_legal WHEN 2 then e.empresa_nombre_establecimiento END AS 'empresa_nombre_legal', e.empresa_direccion_literal AS 'empresa_direccion', e.empresa_direccion_geo, e.empresa_dato_contacto AS 'contacto'  FROM prospecto p, empresa e WHERE p.prospecto_consolidado=1 AND p.prospecto_aceptado_afiliado=1 AND p.ejecutivo_id=? AND p.prospecto_entrega_servicio=? AND p.empresa_id=e.empresa_id ORDER BY p.prospecto_fecha_asignacion DESC "; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo, $entrega_servicio));

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
    
    function VerificaServicioEntregado($codigo_prospecto) 
    {        
        try 
        {
            $sql = "SELECT prospecto_entrega_servicio FROM prospecto WHERE prospecto_consolidado=1 AND prospecto_aceptado_afiliado=1 AND prospecto_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function CompletarEntregaServicio($geolocalizacion, $accion_usuario, $accion_fecha, $codigo_ejecutivo, $codigo_prospecto)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_entrega_servicio=1, prospecto_entrega_servicio_geo=?, prospecto_entrega_servicio_fecha=?, accion_usuario=?, accion_fecha=? WHERE prospecto_consolidado=1 AND prospecto_aceptado_afiliado=1 AND ejecutivo_id=? AND prospecto_id=? ";

            $this->db->query($sql, array($geolocalizacion, $accion_fecha, $accion_usuario, $accion_fecha, $codigo_ejecutivo, $codigo_prospecto));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // FIN SERVICIOS REST APP

    
    // Obtener Detalle del Catálogo
    function ObtenerDetalleCatalogo($data, $tipo)
    {        
        try 
        {
            $sql = "SELECT catalogo_id, catalogo_tipo_codigo, catalogo_codigo, catalogo_descripcion FROM catalogo WHERE catalogo_codigo=? AND catalogo_tipo_codigo=? LIMIT 1";

            $consulta = $this->db->query($sql, array($data, $tipo));

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
    
    // Obtener Detalle del Catálogo de Tipo de Persona
    function ObtenerDetalleCatalogoTipo($data)
    {        
        try
        {
            if($data == -1)
            {
                $sql = "SELECT tipo_persona_id, categoria_persona_id, tipo_persona_nombre, tipo_persona_vigente FROM tipo_persona WHERE tipo_persona_vigente=1 AND tipo_persona_id>0 ";
            }
            else
            {
                $sql = "SELECT tipo_persona_id, categoria_persona_id, tipo_persona_nombre, tipo_persona_vigente FROM tipo_persona WHERE tipo_persona_id=? AND tipo_persona_vigente=1 AND tipo_persona_id>0 ";
            }
            $consulta = $this->db->query($sql, array($data));

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
    
    // Obtener Datos Usuario
    
    function ObtenerDatosUsuario($usuario_codigo) 
    {        
        try 
        {
            $sql = "SELECT u.usuario_id, u.usuario_user, u.estructura_agencia_id, a.estructura_agencia_nombre, r.estructura_regional_nombre, u.usuario_pass, u.usuario_fecha_creacion, u.usuario_fecha_ultimo_acceso, u.usuario_fecha_ultimo_password, u.usuario_password_reset, CONCAT_WS(' ', u.usuario_nombres, u.usuario_app, u.usuario_apm) AS 'nombre_completo', u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email, u.usuario_telefono, u.usuario_direccion, u.usuario_rol, u.usuario_activo FROM usuarios u INNER JOIN estructura_agencia a ON a.estructura_agencia_id=u.estructura_agencia_id INNER JOIN estructura_regional r ON a.estructura_regional_id=r.estructura_regional_id WHERE usuario_id = ? "; 

            $consulta = $this->db->query($sql, array($usuario_codigo));

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
    
    function ObtenerCodigoRegionUsuario($usuario_codigo) 
    {        
        try 
        {
            $sql = "SELECT r.estructura_regional_id, r.estructura_regional_nombre, r.estructura_regional_departamento, r.estructura_regional_ciudad, r.estructura_regional_estado FROM usuarios u INNER JOIN estructura_agencia a ON a.estructura_agencia_id=u.estructura_agencia_id INNER JOIN estructura_regional r ON r.estructura_regional_id=a.estructura_regional_id WHERE u.usuario_id=? "; 

            $consulta = $this->db->query($sql, array($usuario_codigo));

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
    
    function ObtenerDetalleDatosUsuario($tipo_codigo, $codigo_usuario)
    {        
        try 
        {
            $criterio = 'usuario_id';
            
            if($tipo_codigo == 1)
            {
                $criterio = 'usuario_user';
            }
            
            if($tipo_codigo == 2)
            {
                $criterio = 'usuario_rol';
            }
            
            $sql = "SELECT pa.perfil_app_nombre, u.usuario_id, u.usuario_user, u.estructura_agencia_id, a.estructura_agencia_nombre, r.estructura_regional_nombre, u.usuario_pass, u.usuario_fecha_creacion, u.usuario_fecha_ultimo_acceso, u.usuario_fecha_ultimo_password, u.usuario_password_reset, u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email, u.usuario_telefono, u.usuario_direccion, u.usuario_rol, u.usuario_activo 
                    FROM usuarios u 
                    INNER JOIN rol ro ON ro.rol_id=u.usuario_rol
                    INNER JOIN perfil_app pa ON pa.perfil_app_id=ro.perfil_app_id
                    INNER JOIN estructura_agencia a ON a.estructura_agencia_id=u.estructura_agencia_id 
                    INNER JOIN estructura_regional r ON a.estructura_regional_id=r.estructura_regional_id WHERE $criterio = ? "; 

            $consulta = $this->db->query($sql, array($codigo_usuario));

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
    
    // Roles, Perfiles y Menus
    
    function ObtenerDatosRolesUsuario($estado)
    {        
        try 
        {
            $sql = "SELECT rol_id, rol_nombre, rol_descirpcion, rol_estado, accion_usuario, accion_fecha FROM rol WHERE rol_estado!=? AND rol_id!=1"; 

            $consulta = $this->db->query($sql, array($estado));

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
    
    function ObtenerDatosRolAdministrador()
    {        
        try 
        {
            $sql = "SELECT rol_id, rol_nombre, rol_descirpcion, rol_estado, accion_usuario, accion_fecha FROM rol WHERE rol_id=?"; 

            $consulta = $this->db->query($sql, 1);

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
    
    function ObtenerDatosRoles($estado)
    {        
        try 
        {
            $sql = "SELECT rol_id, rol_nombre, rol_descirpcion, rol_estado, accion_usuario, accion_fecha FROM rol WHERE rol_estado!=? "; 

            $consulta = $this->db->query($sql, array($estado));

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
    
    function ObtenerCategoriaPersonas($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT categoria_persona_id, categoria_persona_nombre, categoria_persona_vigente, accion_usuario, accion_fecha FROM categoria_persona WHERE categoria_persona_vigente=1 "; 
            }
            else
            {
                $sql = "SELECT categoria_persona_id, categoria_persona_nombre, categoria_persona_vigente, accion_usuario, accion_fecha FROM categoria_persona WHERE categoria_persona_vigente=1 AND categoria_persona_id=? "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function ObtenerPersonas($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT p.tipo_persona_id, p.categoria_persona_id, c.categoria_persona_nombre, p.tipo_persona_nombre, p.tipo_persona_vigente, p.accion_usuario, p.accion_fecha FROM tipo_persona p INNER JOIN categoria_persona c ON c.categoria_persona_id=p.categoria_persona_id WHERE p.tipo_persona_vigente=1 "; 
            }
            else
            {
                $sql = "SELECT p.tipo_persona_id, p.categoria_persona_id, c.categoria_persona_nombre, p.tipo_persona_nombre, p.tipo_persona_vigente, p.accion_usuario, p.accion_fecha FROM tipo_persona p INNER JOIN categoria_persona c ON c.categoria_persona_id=p.categoria_persona_id WHERE p.tipo_persona_vigente=1 AND p.tipo_persona_id=? "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function ObtenerRoles($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT r.rol_id, r.rol_nombre, r.rol_descirpcion, r.rol_estado, r.perfil_app_id, p.perfil_app_nombre, r.accion_usuario, r.accion_fecha 
                        FROM rol r
                        INNER JOIN perfil_app p ON r.perfil_app_id=p.perfil_app_id "; 
            }
            else
            {
                $sql = "SELECT r.rol_id, r.rol_nombre, r.rol_descirpcion, r.rol_estado, r.perfil_app_id, p.perfil_app_nombre, r.accion_usuario, r.accion_fecha 
                        FROM rol r
                        INNER JOIN perfil_app p ON r.perfil_app_id=p.perfil_app_id
                        WHERE rol_id=? "; 
            }

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function ObtenerDatosMenu()
    {        
        try 
        {
            $sql = "SELECT menu_id, menu_nombre, menu_descripcion, menu_enlace, menu_orden, accion_usuario, accion_fecha FROM menu ORDER BY menu_orden, menu_nombre"; 

            $consulta = $this->db->query($sql);

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
    
    function ObtenerDatosRolMenu($rol_codigo, $menu_codigo)
    {        
        try 
        {
            $sql = "SELECT rol_menu_id, rol_id, menu_id, accion_usuario, accion_fecha FROM rol_menu WHERE rol_id=? AND menu_id=? "; 

            $consulta = $this->db->query($sql, array($rol_codigo, $menu_codigo));

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
    
    function ObtenerDatosPersonaDocumento($persona_codigo, $documento_codigo)
    {        
        try 
        {
            $sql = "SELECT tipo_persona_documento_id, tipo_persona_id, documento_id, accion_usuario, accion_fecha FROM tipo_persona_documento WHERE tipo_persona_id=? AND documento_id=? "; 

            $consulta = $this->db->query($sql, array($persona_codigo, $documento_codigo));

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
    
    function ObtenerDatosPerfiles($estado)
    {        
        try 
        {
            $sql = "SELECT perfil_id, perfil_nombre, perfil_descripcion, perfil_estado, accion_usuario, accion_fecha FROM perfil WHERE perfil_estado!=? ORDER BY perfil_nombre "; 

            $consulta = $this->db->query($sql, array($estado));

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
    
    function ObtenerPerfil($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT perfil_id, perfil_nombre, perfil_descripcion, perfil_estado, accion_usuario, accion_fecha FROM perfil ORDER BY perfil_nombre ASC "; 
            }
            else
            {
                $sql = "SELECT perfil_id, perfil_nombre, perfil_descripcion, perfil_estado, accion_usuario, accion_fecha FROM perfil WHERE perfil_id=? ORDER BY perfil_nombre ASC "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function ObtenerDatosUsuarioPerfil($usuario_codigo, $perfil_codigo)
    {        
        try 
        {
            $sql = "SELECT usuario_perfil_id, usuario_id, perfil_id, accion_usuario, accion_fecha FROM usuario_perfil WHERE usuario_id=? AND perfil_id=? "; 

            $consulta = $this->db->query($sql, array($usuario_codigo, $perfil_codigo));

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
    
    function EliminarPerfilUsuario($usuario_codigo)
    {        
        try 
        {
            $sql = "DELETE FROM usuario_perfil WHERE usuario_id=? "; 

            $consulta = $this->db->query($sql, array($usuario_codigo));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarPerfilUsuario($usuario_codigo, $perfil_codigo, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO usuario_perfil(usuario_id, perfil_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) "; 

            $consulta = $this->db->query($sql, array($usuario_codigo, $perfil_codigo, $accion_usuario, $accion_fecha));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }

    // Agencias
    
    function ObtenerDatosAgencia($agencia_codigo)
    {        
        try 
        {
            if($agencia_codigo == -1)
            { 
                $sql = "SELECT a.estructura_agencia_id, a.estructura_regional_id AS 'parent_id', r.estructura_regional_nombre AS 'parent_detalle', a.estructura_agencia_nombre, a.accion_usuario, a.accion_fecha FROM estructura_agencia a INNER JOIN estructura_regional r ON r.estructura_regional_id=a.estructura_regional_id ORDER BY a.estructura_agencia_nombre "; 
            }
            else
            {
                $sql = "SELECT a.estructura_agencia_id, a.estructura_regional_id AS 'parent_id', r.estructura_regional_nombre AS 'parent_detalle', a.estructura_agencia_nombre, a.accion_usuario, a.accion_fecha FROM estructura_agencia a INNER JOIN estructura_regional r ON r.estructura_regional_id=a.estructura_regional_id WHERE estructura_agencia_id=? ORDER BY a.estructura_agencia_nombre "; 
            }

            $consulta = $this->db->query($sql, array($agencia_codigo));

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
    
    function ObtenerDatosRegionalExcluyente($codigo=1, $parent_codigo=-1, $parent_tipo=-1)
    {        
        try 
        {
            $filtro = '';
            
            if($parent_codigo != -1 && $parent_tipo != -1)
            {
                switch ($parent_tipo) {
                    case 'dir_departamento':
                        $filtro = " AND estructura_regional_departamento='$parent_codigo'";
                        break;
                    case 'dir_provincia':
                        $filtro = " AND estructura_regional_provincia='$parent_codigo'";
                        break;
                    case 'dir_localidad_ciudad':
                        $filtro = " AND estructura_regional_ciudad='$parent_codigo'";
                        break;

                    default:
                        break;
                }
            }
            
            $sql = "SELECT r.estructura_regional_id, r.estructura_entidad_id AS 'parent_id', e.estructura_entidad_nombre AS 'parent_detalle', r.estructura_regional_nombre, r.estructura_regional_direccion, r.estructura_regional_departamento, r.estructura_regional_provincia, r.estructura_regional_ciudad, r.estructura_regional_geo, r.estructura_regional_firma, r.estructura_regional_estado, r.accion_usuario, r.accion_fecha FROM estructura_regional r INNER JOIN estructura_entidad e ON e.estructura_entidad_id=r.estructura_entidad_id WHERE estructura_regional_estado=? $filtro "; 

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function ObtenerDatosRegional($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT r.estructura_regional_monto, r.estructura_regional_id, r.estructura_entidad_id AS 'parent_id', e.estructura_entidad_nombre AS 'parent_detalle', r.estructura_regional_nombre, r.estructura_regional_direccion, r.estructura_regional_departamento, r.estructura_regional_provincia, r.estructura_regional_ciudad, r.estructura_regional_geo, r.estructura_regional_responsable, r.estructura_regional_firma, r.estructura_regional_estado, r.accion_usuario, r.accion_fecha FROM estructura_regional r INNER JOIN estructura_entidad e ON e.estructura_entidad_id=r.estructura_entidad_id ORDER BY r.estructura_regional_ciudad "; 
            }
            else
            {
                $sql = "SELECT r.estructura_regional_monto, r.estructura_regional_id, r.estructura_entidad_id AS 'parent_id', e.estructura_entidad_nombre AS 'parent_detalle', r.estructura_regional_nombre, r.estructura_regional_direccion, r.estructura_regional_departamento, r.estructura_regional_provincia, r.estructura_regional_ciudad, r.estructura_regional_geo, r.estructura_regional_responsable, r.estructura_regional_firma, r.estructura_regional_estado, r.accion_usuario, r.accion_fecha FROM estructura_regional r INNER JOIN estructura_entidad e ON e.estructura_entidad_id=r.estructura_entidad_id WHERE estructura_regional_id=? "; 
            }

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function ObtenerDatosEntidad($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT estructura_entidad_id, estructura_entidad_nombre, accion_usuario, accion_fecha FROM estructura_entidad "; 
            }
            else
            {
                $sql = "SELECT estructura_entidad_id, estructura_entidad_nombre, accion_usuario, accion_fecha FROM estructura_entidad WHERE estructura_entidad_id=? "; 
            }

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function UpdateAgencia($codigo_parent, $estructura_agencia_nombre, $accion_usuario, $accion_fecha, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE estructura_agencia SET estructura_regional_id=?,estructura_agencia_nombre=?,accion_usuario=?,accion_fecha=? WHERE estructura_agencia_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_parent, $estructura_agencia_nombre, $accion_usuario, $accion_fecha, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertAgencia($codigo_parent, $estructura_agencia_nombre, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO estructura_agencia(estructura_regional_id, estructura_agencia_nombre, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($codigo_parent, $estructura_agencia_nombre, $accion_usuario, $accion_fecha));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateRegional($estructura_regional_monto, $codigo_parent, $estructura_regional_nombre, $estructura_regional_direccion, $estructura_regional_departamento, $estructura_regional_provincia, $estructura_regional_ciudad, $estructura_regional_geo, $estructura_regional_responsable, $estructura_regional_firma, $estructura_regional_estado, $accion_usuario, $accion_fecha, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE estructura_regional SET estructura_regional_monto=?, estructura_entidad_id=?, estructura_regional_nombre=?, estructura_regional_direccion=?, estructura_regional_departamento=?, estructura_regional_provincia=?, estructura_regional_ciudad=?, estructura_regional_geo=?, estructura_regional_responsable=?, estructura_regional_firma=?, estructura_regional_estado=?, accion_usuario=?, accion_fecha=? WHERE estructura_regional_id=? "; 
            
            $consulta = $this->db->query($sql, array($estructura_regional_monto, $codigo_parent, $estructura_regional_nombre, $estructura_regional_direccion, $estructura_regional_departamento, $estructura_regional_provincia, $estructura_regional_ciudad, $estructura_regional_geo, $estructura_regional_responsable, $estructura_regional_firma, $estructura_regional_estado, $accion_usuario, $accion_fecha, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertRegional($estructura_regional_monto, $codigo_parent, $estructura_regional_nombre, $estructura_regional_direccion, $estructura_regional_departamento, $estructura_regional_provincia, $estructura_regional_ciudad, $estructura_regional_geo, $estructura_regional_responsable, $estructura_regional_firma, $estructura_regional_estado, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO estructura_regional(estructura_regional_monto, estructura_entidad_id, estructura_regional_nombre, estructura_regional_direccion, estructura_regional_departamento, estructura_regional_provincia, estructura_regional_ciudad, estructura_regional_geo, estructura_regional_responsable, estructura_regional_firma, estructura_regional_estado, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($estructura_regional_monto, $codigo_parent, $estructura_regional_nombre, $estructura_regional_direccion, $estructura_regional_departamento, $estructura_regional_provincia, $estructura_regional_ciudad, $estructura_regional_geo, $estructura_regional_responsable, $estructura_regional_firma, $estructura_regional_estado, $accion_usuario, $accion_fecha));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Tipo de Persona
    
    function UpdatePersona($categoria_persona_id, $tipo_persona_nombre, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE tipo_persona SET categoria_persona_id=?, tipo_persona_nombre=?, accion_usuario=?, accion_fecha=? WHERE tipo_persona_id=? "; 
            
            $consulta = $this->db->query($sql, array($categoria_persona_id, $tipo_persona_nombre, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertPersona($catalogo_parent, $estructura_nombre, $nombre_usuario, $fecha_actual)
    {        
        try 
        {            
            $sql = "INSERT INTO tipo_persona(categoria_persona_id, tipo_persona_nombre, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) ";

            $this->db->query($sql, array($catalogo_parent, $estructura_nombre, $nombre_usuario, $fecha_actual));

            $listaResultados = $this->db->insert_id();
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

            return $listaResultados;
    }
    
    function EliminaDocumentoPersona($persona_codigo)
    {        
        try 
        {
            $sql = "DELETE FROM tipo_persona_documento WHERE tipo_persona_id=? "; 
            
            $consulta = $this->db->query($sql, array($persona_codigo));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarDocumentoPersona($persona_codigo, $documento_codigo, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            $sql = "INSERT INTO tipo_persona_documento(tipo_persona_id, documento_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($persona_codigo, $documento_codigo, $nombre_usuario, $fecha_actual));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateRol($perfil_app, $estructura_nombre, $estructura_detalle, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE rol SET perfil_app_id=?, rol_nombre=?, rol_descirpcion=?, accion_usuario=?, accion_fecha=? WHERE rol_id=? "; 
            
            $consulta = $this->db->query($sql, array($perfil_app, $estructura_nombre, $estructura_detalle, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertRol($perfil_app, $estructura_nombre, $estructura_detalle, $nombre_usuario, $fecha_actual)
    {        
        try 
        {            
            $sql = "INSERT INTO rol(perfil_app_id, rol_nombre, rol_descirpcion, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?) ";

            $this->db->query($sql, array($perfil_app, $estructura_nombre, $estructura_detalle, $nombre_usuario, $fecha_actual));

            $listaResultados = $this->db->insert_id();
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

            return $listaResultados;
    }
    
    function EliminaMenuRol($rol_codigo)
    {        
        try 
        {
            $sql = "DELETE FROM rol_menu WHERE rol_id=? "; 
            
            $consulta = $this->db->query($sql, array($rol_codigo));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarMenuRol($codigo_rol, $codigo_menu, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            $sql = "INSERT INTO rol_menu(rol_id, menu_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($codigo_rol, $codigo_menu, $nombre_usuario, $fecha_actual));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdatePerfil($estructura_nombre, $estructura_detalle, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE perfil SET perfil_nombre=?, perfil_descripcion=?, accion_usuario=?, accion_fecha=? WHERE perfil_id=? "; 
            
            $consulta = $this->db->query($sql, array($estructura_nombre, $estructura_detalle, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function VerificaPermisoPorPerfil($codigo_usuario, $codigo_perfil)
    {        
        try 
        {
            $sql = "SELECT usuario_perfil_id, usuario_id, perfil_id FROM usuario_perfil WHERE usuario_id=? AND perfil_id=? ";

            $consulta = $this->db->query($sql, array($codigo_usuario, $codigo_perfil));

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
	
    // Documentos
	
    function ObtenerDocumento($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT documento_id, documento_nombre, documento_vigente, documento_enviar, documento_pdf, documento_codigo, documento_mandatorio, accion_usuario, accion_fecha FROM documento WHERE documento_vigente=1 "; 
            }
            else
            {
                $sql = "SELECT documento_id, documento_nombre, documento_vigente, documento_enviar, documento_pdf, documento_codigo, documento_mandatorio, accion_usuario, accion_fecha FROM documento WHERE documento_vigente AND documento_id=? "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
	
    function UpdateDocumento($documento_nombre, $documento_enviar, $documento_pdf, $documento_codigo, $documento_mandatorio, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE documento SET documento_nombre=?, documento_enviar=?, documento_pdf=?, documento_codigo=?, documento_mandatorio=?, accion_usuario=?, accion_fecha=? WHERE documento_id=? "; 
            
            $consulta = $this->db->query($sql, array($documento_nombre, $documento_enviar, $documento_pdf, $documento_codigo, $documento_mandatorio, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateDocumentoSinUpload($documento_nombre, $documento_enviar, $documento_codigo, $documento_mandatorio, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE documento SET documento_nombre=?, documento_enviar=?, documento_codigo=?, documento_mandatorio=?, accion_usuario=?, accion_fecha=? WHERE documento_id=? "; 
            
            $consulta = $this->db->query($sql, array($documento_nombre, $documento_enviar, $documento_codigo, $documento_mandatorio, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertDocumento($documento_nombre, $documento_enviar, $documento_pdf, $documento_codigo, $documento_mandatorio, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            $sql = "INSERT INTO documento(documento_nombre, documento_enviar, documento_pdf, documento_codigo, documento_mandatorio, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($documento_nombre, $documento_enviar, $documento_pdf, $documento_codigo, $documento_mandatorio, $nombre_usuario, $fecha_actual));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }

    // Actividades
    
    function ObtenerActividadProspecto($prospecto_codigo, $actividad_codigo)
    {        
        try 
        {
            $sql = "SELECT prospecto_actividades_id, prospecto_id, act_id FROM prospecto_actividades WHERE prospecto_id=? AND act_id=? "; 

            $consulta = $this->db->query($sql, array($prospecto_codigo, $actividad_codigo));

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
    
    function ObtenerActividades($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT act_id, act_detalle, act_activo, accion_usuario, accion_fecha FROM actividades WHERE act_activo=1 "; 
            }
            else
            {
                $sql = "SELECT act_id, act_detalle, act_activo, accion_usuario, accion_fecha FROM actividades WHERE act_activo=1 AND act_id=? "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function UpdateActividades($estructura_nombre, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE actividades SET act_detalle=?, accion_usuario=?, accion_fecha=? WHERE act_id=? "; 
            
            $consulta = $this->db->query($sql, array($estructura_nombre, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertActividades($estructura_nombre, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            $sql = "INSERT INTO actividades(act_detalle, accion_usuario, accion_fecha) VALUES (?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($estructura_nombre, $nombre_usuario, $fecha_actual));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerDetalleProspecto_actividades($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT p.prospecto_actividades_id, p.act_id, a.act_detalle FROM prospecto_actividades p, actividades a WHERE prospecto_id=? AND p.act_id=a.act_id AND a.act_activo=1 ";

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function EliminarActividadesProspecto($codigo_prospecto)
    {        
        try 
        {
            $sql = "DELETE FROM prospecto_actividades WHERE prospecto_id=? ";

            $this->db->query($sql, array($codigo_prospecto));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarActividadesProspecto($prospecto_id, $act_id, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO prospecto_actividades(prospecto_id, act_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) ";

            $this->db->query($sql, array($prospecto_id, $act_id, $accion_usuario, $accion_fecha));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Servicios
    
    function ObtenerServicio($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT servicio_id, servicio_detalle, servicio_activo, accion_usuario, accion_fecha FROM servicio WHERE servicio_activo=1 "; 
            }
            else
            {
                $sql = "SELECT servicio_id, servicio_detalle, servicio_activo, accion_usuario, accion_fecha FROM servicio WHERE servicio_activo=1 AND servicio_id=? "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function UpdateServicio($estructura_nombre, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE servicio SET servicio_detalle=?, accion_usuario=?, accion_fecha=? WHERE servicio_id=? "; 
            
            $consulta = $this->db->query($sql, array($estructura_nombre, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertServicio($estructura_nombre, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            $sql = "INSERT INTO servicio(servicio_detalle, accion_usuario, accion_fecha) VALUES (?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($estructura_nombre, $nombre_usuario, $fecha_actual));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Tareas de Mantenimiento de Cartera
    
    function ObtenerTarea($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT pa.perfil_app_id, pa.perfil_app_nombre, t.tarea_id, t.tarea_detalle, t.tarea_activo, t.accion_usuario, t.accion_fecha 
                        FROM tarea t
                        INNER JOIN perfil_app pa ON pa.perfil_app_id=t.perfil_app_id "; 
            }
            else
            {
                $sql = "SELECT pa.perfil_app_id, pa.perfil_app_nombre, t.tarea_id, t.tarea_detalle, t.tarea_activo, t.accion_usuario, t.accion_fecha 
                        FROM tarea t
                        INNER JOIN perfil_app pa ON pa.perfil_app_id=t.perfil_app_id
                        WHERE t.tarea_id=?"; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function UpdateTarea($estructura_nombre, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE tarea SET tarea_detalle=?, accion_usuario=?, accion_fecha=? WHERE tarea_id=? "; 
            
            $consulta = $this->db->query($sql, array($estructura_nombre, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertTarea($estructura_nombre, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            $sql = "INSERT INTO tarea(tarea_detalle, accion_usuario, accion_fecha) VALUES (?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($estructura_nombre, $nombre_usuario, $fecha_actual));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Ejecutivos de Cuenta
    
    function ObtenerEjecutivo($codigo, $tipo_ejecutivo=1)
    {        
        try 
        {
            $this->load->model('mfunciones_generales');
            
            $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
            
            if($codigo == -1)
            {
                $sql = "SELECT r.perfil_app_id, pa.perfil_app_nombre, er.estructura_regional_nombre, '0' as afiliador_id, e.ejecutivo_id, e.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) AS 'usuario_nombre', e.ejecutivo_zona, e.ejecutivo_perfil_tipo, u.usuario_email, 
                        CASE 
                        WHEN e.ejecutivo_zona='' OR e.ejecutivo_zona IS NULL THEN 'No'
                        WHEN e.ejecutivo_zona!='' OR e.ejecutivo_zona IS NOT NULL THEN 'Si'
                        END AS 'zona_registrada',
                        e.accion_usuario, e.accion_fecha FROM ejecutivo e
                        INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                        INNER JOIN rol r ON r.rol_id=u.usuario_rol
                        INNER JOIN perfil_app pa ON pa.perfil_app_id=r.perfil_app_id AND r.perfil_app_id=" . $tipo_ejecutivo . "
                        INNER JOIN estructura_agencia ea ON ea.estructura_agencia_id=u.estructura_agencia_id
                        INNER JOIN estructura_regional er ON er.estructura_regional_id=ea.estructura_regional_id
                        WHERE er.estructura_regional_id IN (" . $lista_region->region_id . ") AND u.usuario_activo>0
                        ORDER BY u.usuario_nombres ASC ";
            }
            else
            {
                $sql = "SELECT r.perfil_app_id, pa.perfil_app_nombre, er.estructura_regional_nombre, '0' as afiliador_id, e.ejecutivo_id, e.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) AS 'usuario_nombre', e.ejecutivo_zona, e.ejecutivo_perfil_tipo, u.usuario_email, 
                        CASE 
                        WHEN e.ejecutivo_zona='' OR e.ejecutivo_zona IS NULL THEN 'No'
                        WHEN e.ejecutivo_zona!='' OR e.ejecutivo_zona IS NOT NULL THEN 'Si'
                        END AS 'zona_registrada',
                        e.accion_usuario, e.accion_fecha FROM ejecutivo e
                        INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                        INNER JOIN rol r ON r.rol_id=u.usuario_rol
                        INNER JOIN perfil_app pa ON pa.perfil_app_id=r.perfil_app_id
                        INNER JOIN estructura_agencia ea ON ea.estructura_agencia_id=u.estructura_agencia_id
                        INNER JOIN estructura_regional er ON er.estructura_regional_id=ea.estructura_regional_id
                        WHERE e.ejecutivo_id=? AND er.estructura_regional_id IN (" . $lista_region->region_id . ") ORDER BY u.usuario_nombres ASC ";
            }
            
            $consulta = $this->db->query($sql, array($codigo));

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
    
    function HabilitarUsuariosEjecutivosCuenta($codigo, $tipo_ejecutivo=1)
    {        
        try 
        {
            $this->load->model('mfunciones_generales');
            
            $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
            
            if($codigo == -1)
            {
                $sql = "SELECT er.estructura_regional_nombre, u.usuario_id, u.estructura_agencia_id, u.usuario_rol, CONCAT(usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) AS 'nombre_completo', u.usuario_user, u.usuario_pass, u.usuario_fecha_creacion, u.usuario_fecha_ultimo_acceso, u.usuario_fecha_ultimo_password, u.usuario_password_reset, u.usuario_recupera_token, u.usuario_recupera_solicitado, u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email, u.usuario_telefono, u.usuario_direccion, u.accion_fecha, u.accion_usuario, u.usuario_activo 
                        FROM usuarios u 

                        INNER JOIN rol r ON r.rol_id=u.usuario_rol
                        INNER JOIN perfil_app pa ON pa.perfil_app_id=r.perfil_app_id AND r.perfil_app_id=" . $tipo_ejecutivo . "

                        INNER JOIN estructura_agencia ea ON ea.estructura_agencia_id=u.estructura_agencia_id
                        INNER JOIN estructura_regional er ON er.estructura_regional_id=ea.estructura_regional_id
                        WHERE er.estructura_regional_id IN (" . $lista_region->region_id . ") AND u.usuario_id NOT IN (SELECT ejecutivo.usuario_id FROM ejecutivo) "; 
            }

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function ObtenerUsuariosEjecutivosCuenta($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT u.usuario_id, u.estructura_agencia_id, u.usuario_rol, CONCAT(usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) AS 'nombre_completo', u.usuario_user, u.usuario_pass, u.usuario_fecha_creacion, u.usuario_fecha_ultimo_acceso, u.usuario_fecha_ultimo_password, u.usuario_password_reset, u.usuario_recupera_token, u.usuario_recupera_solicitado, u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email, u.usuario_telefono, u.usuario_direccion, u.accion_fecha, u.accion_usuario, u.usuario_activo FROM usuarios u INNER JOIN ejecutivo e ON e.usuario_id=u.usuario_id WHERE u.usuario_rol=2 "; 
            }
            else
            {
                $sql = "SELECT u.usuario_id, u.estructura_agencia_id, u.usuario_rol, CONCAT(usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) AS 'nombre_completo', u.usuario_user, u.usuario_pass, u.usuario_fecha_creacion, u.usuario_fecha_ultimo_acceso, u.usuario_fecha_ultimo_password, u.usuario_password_reset, u.usuario_recupera_token, u.usuario_recupera_solicitado, u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email, u.usuario_telefono, u.usuario_direccion, u.accion_fecha, u.accion_usuario, u.usuario_activo FROM usuarios u INNER JOIN ejecutivo e ON e.usuario_id=u.usuario_id WHERE u.usuario_rol=2 AND usuario_id=? "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function UpdateEjecutivo($codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $this->load->model('mfunciones_generales');
            
            // Se verifica el ID de Ejecutivo del usuario
            $arrVerifica = $this->VerificarUsuarioEjecutivo($codigo_usuario);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrVerifica);
            
            $sql1 = "UPDATE prospecto SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? ";
            $consulta1 = $this->db->query($sql1, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id));
            
            $sql2 = "UPDATE calendario SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? ";
            $consulta2 = $this->db->query($sql2, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id));
            
            $sql3 = "UPDATE empresa SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? ";
            $consulta3 = $this->db->query($sql3, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id));
            
            $sql4 = "UPDATE mantenimiento SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? ";
            $consulta4 = $this->db->query($sql4, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id));
            
            $sql5 = "UPDATE terceros SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? ";
            $consulta5 = $this->db->query($sql5, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id));
            
            $sql6 = "UPDATE solicitud_credito SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? ";
            $consulta6 = $this->db->query($sql6, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id));
            
            $sql7 = "UPDATE cobranza SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? ";
            $consulta7 = $this->db->query($sql7, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateZonaEjecutivo($zona, $nombre_usuario, $fecha_actual, $codigo_ejecutivo)
    {        
        try 
        {
            $sql = "UPDATE ejecutivo SET ejecutivo_zona=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? "; 
            
            $consulta = $this->db->query($sql, array($zona, $nombre_usuario, $fecha_actual, $codigo_ejecutivo));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerEjecutivoProspecto($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT ejecutivo_id FROM prospecto WHERE prospecto_id=? ";         

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function VerificarUsuarioEjecutivo($usuario_codigo)
    {        
        try 
        {
            $sql = "SELECT ejecutivo_id FROM ejecutivo WHERE usuario_id=? ";         

            $consulta = $this->db->query($sql, array($usuario_codigo));

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
    
    function InsertEjecutivo($codigo_usuario, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            $sql = "INSERT INTO ejecutivo(usuario_id, accion_usuario, accion_fecha) VALUES (?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($codigo_usuario, $nombre_usuario, $fecha_actual));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Seguimiento a Ejecutivos de Cuenta
    
    function SeguimientoVisitasEjecutivo($filtro, $tipo_registro)
    {        
        try 
        {
            $sql_lead = "SELECT *
                        FROM(
                            SELECT 1 as 'tipo',
                            p1.prospecto_id as 'codigo',
                            p1.codigo_agencia_fie as 'agencia_id',
                            er1.estructura_regional_nombre as 'agencia_nombre',
                            er1.estructura_regional_departamento as 'agencia_departamento',
                            er1.estructura_regional_provincia as 'agencia_provincia',
                            er1.estructura_regional_ciudad as 'agencia_ciudad',
                            er1.estructura_regional_estado as 'agencia_estado',
                            p1.camp_id as 'codigo_rubro',
                            e1.ejecutivo_id,
                            CONCAT_WS(' ', u1.usuario_nombres, u1.usuario_app, u1.usuario_apm) as 'ejecutivo_nombre',
                            u1.usuario_activo,
                            p1.general_solicitante as 'solicitante',
                            p1.general_actividad as 'actividad',
                            p1.prospecto_fecha_asignacion as 'fecha_registro',
                            p1.prospecto_checkin_fecha as 'fecha_checkin',
                            p1.prospecto_checkin_geo as 'checkin_geo'
                            FROM prospecto p1
                            INNER JOIN ejecutivo e1 ON e1.ejecutivo_id=p1.ejecutivo_id
                            INNER JOIN usuarios u1 ON u1.usuario_id=e1.usuario_id
                            INNER JOIN estructura_regional er1 ON er1.estructura_regional_id=p1.codigo_agencia_fie
                            WHERE p1.prospecto_checkin=1 AND p1.onboarding=0 AND p1.general_categoria=1
                        ) aux2 WHERE 1 $filtro";
            
            
            $sql_sol = "SELECT *
                        FROM(
                            SELECT 2 as 'tipo', 
                            s1.sol_id as 'codigo',
                            s1.codigo_agencia_fie as 'agencia_id',
                            er2.estructura_regional_nombre as 'agencia_nombre',
                            er2.estructura_regional_departamento as 'agencia_departamento',
                            er2.estructura_regional_provincia as 'agencia_provincia',
                            er2.estructura_regional_ciudad as 'agencia_ciudad',
                            er2.estructura_regional_estado as 'agencia_estado',
                            s1.sol_codigo_rubro as 'codigo_rubro',
                            e2.ejecutivo_id,
                            CONCAT_WS(' ', u2.usuario_nombres, u2.usuario_app, u2.usuario_apm) as 'ejecutivo_nombre',
                            u2.usuario_activo,
                            CONCAT_WS(' ', s1.sol_primer_nombre, s1.sol_primer_apellido, s1.sol_segundo_apellido) as 'solicitante',
                            CASE s1.sol_dependencia
                                WHEN 1 THEN CONCAT_WS(' | ', s1.sol_depen_empresa, s1.sol_depen_cargo)
                                WHEN 2 THEN s1.sol_indepen_actividad
                            END as 'actividad',
                            s1.sol_fecha as 'fecha_registro',
                            s1.sol_checkin_fecha as 'fecha_checkin',
                            s1.sol_checkin_geo as 'checkin_geo'
                            FROM solicitud_credito s1
                            INNER JOIN ejecutivo e2 ON e2.ejecutivo_id=s1.ejecutivo_id
                            INNER JOIN usuarios u2 ON u2.usuario_id=e2.usuario_id
                            INNER JOIN estructura_regional er2 ON er2.estructura_regional_id=s1.codigo_agencia_fie
                            WHERE s1.sol_checkin=1
                        ) aux2 WHERE 1 $filtro";

            $array_sql = array();
            
            // Consulta Estudios de Crédito
            if($tipo_registro == 1 || $tipo_registro == 99)
            {
                array_push($array_sql, $sql_lead);
            }

            // Consulta Solicitudes de Crédito
            if($tipo_registro == 2 || $tipo_registro == 99)
            {
                    array_push($array_sql, $sql_sol);
            }

            $sql_resultado = implode(" UNION ALL ",$array_sql);
            
            $consulta = $this->db->query($sql_resultado, array());

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
    
    function BandejaVisitasEjecutivo($codigo_usuario)
    {        
        try 
        {
            $sql = "SELECT tipo_visita_codigo, tipo_visita, visita_id, ejecutivo_id, empresa_id, empresa_categoria, empresa_nombre, checkin, checkin_fecha, checkin_geo, cal_visita_ini, usuario_id
                    FROM(
                        SELECT '1' AS 'tipo_visita_codigo', 'Prospecto' AS 'tipo_visita', p.prospecto_id AS 'visita_id', p.ejecutivo_id, p.empresa_id, e.empresa_categoria,

                        CASE e.empresa_categoria
                           WHEN 1 then e.empresa_nombre_legal
                           WHEN 2 then e.empresa_nombre_establecimiento
                        END AS 'empresa_nombre',

                        p.prospecto_checkin AS 'checkin', p.prospecto_checkin_fecha AS 'checkin_fecha', p.prospecto_checkin_geo AS 'checkin_geo', c.cal_visita_ini, u.usuario_id FROM prospecto p 
                        INNER JOIN empresa e ON e.empresa_id=p.empresa_id
                        INNER JOIN calendario c ON c.cal_visita_ini AND c.cal_id_visita=p.prospecto_id AND c.cal_tipo_visita=1
                        INNER JOIN ejecutivo ej ON ej.ejecutivo_id=p.ejecutivo_id
                            INNER JOIN usuarios u ON u.usuario_id=ej.usuario_id

                        WHERE prospecto_checkin=1

                        UNION ALL

                        SELECT '2' AS 'tipo_visita_codigo', 'Mantenimiento' AS 'tipo_visita', m.mant_id AS 'visita_id', m.ejecutivo_id, m.empresa_id, e.empresa_categoria,
                        CASE e.empresa_categoria
                           WHEN 1 then e.empresa_nombre_legal
                           WHEN 2 then e.empresa_nombre_establecimiento
                        END AS 'empresa_nombre',
                        m.mant_checkin AS 'checkin', m.mant_checkin_fecha AS 'checkin_fecha', m.mant_checkin_geo AS 'checkin_geo', c.cal_visita_ini, u.usuario_id FROM mantenimiento m
                        INNER JOIN empresa e ON e.empresa_id=m.empresa_id
                        INNER JOIN calendario c ON c.cal_visita_ini AND c.cal_id_visita=m.mant_id AND c.cal_tipo_visita=2
                            INNER JOIN ejecutivo ej ON ej.ejecutivo_id=m.ejecutivo_id
                            INNER JOIN usuarios u ON u.usuario_id=ej.usuario_id
                        WHERE mant_checkin=1
                        ) a WHERE usuario_id=?";

            $consulta = $this->db->query($sql, array($codigo_usuario));

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
    
    // Horario Ejecutivos de Cuenta
    
    function HorarioVisitasEjecutivo($codigo_ejecutivo)
    {        
        try 
        {            
            $sql = "SELECT ejecutivo_id, cal_id, cal_tipo_visita, cal_id_visita, cal_visita_ini, cal_visita_fin, empresa_id, empresa_categoria, empresa_nombre
                    FROM(
                            SELECT ej.ejecutivo_id, c.cal_id, c.cal_tipo_visita, c.cal_id_visita, c.cal_visita_ini, c.cal_visita_fin, e.empresa_id, e.empresa_categoria, p.general_solicitante as 'empresa_nombre'
                            FROM empresa e
                            INNER JOIN prospecto p ON p.empresa_id=e.empresa_id 
                            INNER JOIN ejecutivo ej ON ej.ejecutivo_id=p.ejecutivo_id
                            INNER JOIN calendario c ON c.ejecutivo_id=ej.ejecutivo_id AND c.cal_id_visita=p.prospecto_id AND c.cal_tipo_visita=1 AND p.prospecto_checkin=0 AND p.prospecto_consolidado=0

                            UNION ALL

                            SELECT ej.ejecutivo_id, c.cal_id, c.cal_tipo_visita, c.cal_id_visita, c.cal_visita_ini, c.cal_visita_fin, e.empresa_id, e.empresa_categoria, 
                            CASE e.empresa_categoria
                                    WHEN 1 then e.empresa_nombre_legal
                                    WHEN 2 then e.empresa_nombre_establecimiento
                            END AS 'empresa_nombre'
                            FROM empresa e
                            INNER JOIN mantenimiento m ON m.empresa_id=e.empresa_id 
                            INNER JOIN ejecutivo ej ON ej.ejecutivo_id=m.ejecutivo_id
                            INNER JOIN calendario c ON c.ejecutivo_id=ej.ejecutivo_id AND c.cal_id_visita=m.mant_id AND c.cal_tipo_visita=2 AND m.mant_checkin=0 AND m.mant_estado=0
                            ) a WHERE ejecutivo_id=? ORDER BY cal_visita_ini ASC ";

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
    
    function UpdateHorarioEjecutivo($fecha_inicio, $fecha_fin, $nombre_usuario, $fecha_actual, $codigo_horario)
    {        
        try 
        {
            $sql = "UPDATE calendario SET cal_visita_ini=?, cal_visita_fin=?, accion_usuario=?, accion_fecha=? WHERE cal_id=? "; 
            
            $consulta = $this->db->query($sql, array($fecha_inicio, $fecha_fin, $nombre_usuario, $fecha_actual, $codigo_horario));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateHorarioReVisita($fecha_inicio, $fecha_fin, $nombre_usuario, $fecha_actual, $codigo_prospecto)
    {        
        try 
        {
            $sql = "UPDATE calendario SET cal_visita_ini=?, cal_visita_fin=?, accion_usuario=?, accion_fecha=? WHERE cal_tipo_visita=1 AND cal_id_visita=? "; 
            
            $consulta = $this->db->query($sql, array($fecha_inicio, $fecha_fin, $nombre_usuario, $fecha_actual, $codigo_prospecto));
            
            $sql2 = "UPDATE prospecto SET prospecto_consolidado=0, prospecto_fecha_asignacion=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=?"; 
            
            $consulta2 = $this->db->query($sql2, array($fecha_inicio, $nombre_usuario, $fecha_actual, $codigo_prospecto));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Solicitudes de Prospectos
    
    function ObtenerSolicitudProspecto($codigo, $estado)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT solicitud_id, solicitud_nombre_persona, solicitud_nombre_empresa, solicitud_departamento, solicitud_ciudad, solicitud_zona, solicitud_telefono, solicitud_email, solicitud_direccion_literal, solicitud_direccion_geo, solicitud_rubro, solicitud_fecha, solicitud_confirmado, solicitud_token, solicitud_ip, solicitud_estado, solicitud_observacion FROM solicitud_afiliacion WHERE solicitud_confirmado<=1 AND solicitud_estado<=? AND solicitud_confirmado<=1 ORDER BY solicitud_fecha DESC "; 
            }
            else
            {
                $sql = "SELECT solicitud_id, solicitud_nombre_persona, solicitud_nombre_empresa, solicitud_departamento, solicitud_ciudad, solicitud_zona, solicitud_telefono, solicitud_email, solicitud_direccion_literal, solicitud_direccion_geo, solicitud_rubro, solicitud_fecha, solicitud_confirmado, solicitud_token, solicitud_ip, solicitud_estado, solicitud_observacion FROM solicitud_afiliacion WHERE solicitud_confirmado<=1 AND solicitud_estado<=? AND solicitud_id=? AND solicitud_confirmado<=1 ORDER BY solicitud_fecha DESC "; 
            }

            $consulta = $this->db->query($sql, array($estado, $codigo));

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
    
    function ObtenerServiciosSolicitud($codigo_solicitud)
    {        
        try 
        {
            $sql = "SELECT s.solicitud_servicio_id, s.solicitud_id, s.servicio_id, se.servicio_detalle FROM solicitud_servicio s INNER JOIN servicio se ON se.servicio_id=s.servicio_id WHERE s.solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_solicitud));

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
    
    function RechazarSolicitudProspecto($solicitud_observacion, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE solicitud_mantenimiento SET solicitud_estado=2, solicitud_observacion=?, accion_usuario=?, accion_fecha=? WHERE solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($solicitud_observacion, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function AprobarSolicitudProspecto($nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE solicitud_afiliacion SET solicitud_estado=1, accion_usuario=?, accion_fecha=? WHERE solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Servicios Campaña
    
    function ObtenerServiciosCampana($codigo_solicitud)
    {        
        try 
        {
            $sql = "SELECT c.campana_servicio_id, c.camp_id, c.servicio_id, se.servicio_detalle, se.servicio_tab FROM campana_servicio c INNER JOIN servicio se ON se.servicio_id=c.servicio_id WHERE c.camp_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_solicitud));

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
    
    function ObtenerServicioCampana($camp_id, $servicio_codigo)
    {        
        try 
        {
            $sql = "SELECT campana_servicio_id, camp_id, servicio_id, accion_usuario, accion_fecha FROM campana_servicio WHERE camp_id=? AND servicio_id=? "; 

            $consulta = $this->db->query($sql, array($camp_id, $servicio_codigo));

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
    
    function Eliminar_SolicitudCampana($codigo_campana)
    {        
        try 
        {
            $sql = "DELETE FROM campana_servicio WHERE camp_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_campana));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarCampanaServicio($codigo_solicitud, $codigo_servicio, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO campana_servicio(camp_id, servicio_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($codigo_solicitud, $codigo_servicio, $accion_usuario, $accion_fecha));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Servicios Prospecto
    
    function ObtenerServicioSolicitud($solicitud_codigo, $servicio_codigo)
    {        
        try 
        {
            $sql = "SELECT solicitud_servicio_id, solicitud_id, servicio_id, accion_usuario, accion_fecha FROM solicitud_servicio WHERE solicitud_id=? AND servicio_id=? "; 

            $consulta = $this->db->query($sql, array($solicitud_codigo, $servicio_codigo));

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
    
    function UpdateSolicitudProspecto($solicitud_nombre_persona, $solicitud_nombre_empresa, $empresa_departamento, $empresa_municipio, $empresa_zona, $solicitud_telefono, $solicitud_email, $solicitud_direccion_literal, $coordenadas_solicitud, $catalogo_rubro, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE solicitud_afiliacion SET solicitud_nombre_persona=?, solicitud_nombre_empresa=?, solicitud_departamento=?, solicitud_ciudad=?, solicitud_zona=?, solicitud_telefono=?, solicitud_email=?, solicitud_direccion_literal=?, solicitud_direccion_geo=?, solicitud_rubro=?, accion_usuario=?, accion_fecha=? WHERE solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($solicitud_nombre_persona, $solicitud_nombre_empresa, $empresa_departamento, $empresa_municipio, $empresa_zona, $solicitud_telefono, $solicitud_email, $solicitud_direccion_literal, $coordenadas_solicitud, $catalogo_rubro, $nombre_usuario, $fecha_actual, $estructura_id));
            
            $listaResultados = $this->db->insert_id();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function InsertSolicitudProspecto($solicitud_nombre_persona, $solicitud_nombre_empresa, $empresa_departamento, $empresa_municipio, $empresa_zona, $solicitud_telefono, $solicitud_email, $solicitud_direccion_literal, $solicitud_direccion_geo, $solicitud_rubro, $solicitud_fecha, $accion_usuario, $accion_fecha, $ip, $token)
    {        
        try 
        {
            $sql = "INSERT INTO solicitud_afiliacion(solicitud_nombre_persona, solicitud_nombre_empresa, solicitud_departamento, solicitud_ciudad, solicitud_zona, solicitud_telefono, solicitud_email, solicitud_direccion_literal, solicitud_direccion_geo, solicitud_rubro, solicitud_fecha, accion_usuario, accion_fecha, solicitud_ip, solicitud_token, solicitud_confirmado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($solicitud_nombre_persona, $solicitud_nombre_empresa, $empresa_departamento, $empresa_municipio, $empresa_zona, $solicitud_telefono, $solicitud_email, $solicitud_direccion_literal, $solicitud_direccion_geo, $solicitud_rubro, $solicitud_fecha, $accion_usuario, $accion_fecha, $ip, $token, 1));
            
            $listaResultados = $this->db->insert_id();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function Eliminar_SolicitudProspecto($codigo_solicitud)
    {        
        try 
        {
            $sql = "DELETE FROM solicitud_servicio WHERE solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_solicitud));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarSolicitudServicio($codigo_solicitud, $codigo_servicio, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO solicitud_servicio(solicitud_id, servicio_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($codigo_solicitud, $codigo_servicio, $accion_usuario, $accion_fecha));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Solicitudes de Mantenimiento
    
    function ObtenerSolicitudMantenimiento($codigo, $estado)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT solicitud_id, solicitud_nit, solicitud_nombre, solicitud_email, solicitud_otro, solicitud_otro_detalle, solicitud_fecha, solicitud_confirmado, solicitud_token, solicitud_ip, solicitud_estado, solicitud_observacion, accion_usuario, accion_fecha FROM solicitud_mantenimiento WHERE solicitud_estado=? AND solicitud_confirmado=1 ORDER BY solicitud_fecha DESC "; 
            }
            else
            {
                $sql = "SELECT solicitud_id, solicitud_nit, solicitud_nombre, solicitud_email, solicitud_otro, solicitud_otro_detalle, solicitud_fecha, solicitud_confirmado, solicitud_token, solicitud_ip, solicitud_estado, solicitud_observacion, accion_usuario, accion_fecha FROM solicitud_mantenimiento WHERE solicitud_estado=? AND solicitud_id=? AND solicitud_confirmado=1 ORDER BY solicitud_fecha DESC "; 
            }

            $consulta = $this->db->query($sql, array($estado, $codigo));

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
    
    function ObtenerTareasSolicitud($codigo_solicitud)
    {        
        try 
        {
            $sql = "SELECT s.solicitud_tarea_id, s.solicitud_id, s.tarea_id, t.tarea_detalle FROM solicitud_tarea s INNER JOIN tarea t ON t.tarea_id=s.tarea_id WHERE s.solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_solicitud));

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
    
    function RechazarSolicitudMantenimiento($solicitud_observacion, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE solicitud_afiliacion SET solicitud_estado=2, solicitud_observacion=?, accion_usuario=?, accion_fecha=? WHERE solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($solicitud_observacion, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }

    function InsertSolicitudMantenimiento($solicitud_nit, $solicitud_nombre, $solicitud_otro, $solicitud_otro_detalle, $solicitud_fecha, $solicitud_email, $solicitud_ip, $accion_usuario, $accion_fecha, $token)
    {        
        try 
        {
            $sql = "INSERT INTO solicitud_mantenimiento(solicitud_nit, solicitud_nombre, solicitud_otro, solicitud_otro_detalle, solicitud_fecha, solicitud_email, solicitud_ip, accion_usuario, accion_fecha, solicitud_token) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($solicitud_nit, $solicitud_nombre, $solicitud_otro, $solicitud_otro_detalle, $solicitud_fecha, $solicitud_email, $solicitud_ip, $accion_usuario, $accion_fecha, $token));
            
            $listaResultados = $this->db->insert_id();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }

    function Eliminar_SolicitudTarea($codigo_solicitud)
    {        
        try 
        {
            $sql = "DELETE FROM solicitud_tarea WHERE solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_solicitud));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarSolicitudTarea($codigo_solicitud, $codigo_tarea, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO solicitud_tarea(solicitud_id, tarea_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($codigo_solicitud, $codigo_tarea, $accion_usuario, $accion_fecha));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function AprobarSolicitudMantenimiento($nombre_usuario, $fecha_actual, $estructura_id)
    {
        try 
        {
            $sql = "UPDATE solicitud_mantenimiento SET solicitud_estado=1, accion_usuario=?, accion_fecha=? WHERE solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function VerificaSolicitudVisita($tabla, $token, $codigo)
    {        
        try 
        {
            $sql = "SELECT solicitud_id, solicitud_confirmado, solicitud_fecha FROM $tabla WHERE solicitud_token=? AND solicitud_id=? LIMIT 1 "; 
            
            $consulta = $this->db->query($sql, array($token, $codigo));

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
    
    function UpdateSolicitudVisita($tabla, $accion_usuario, $accion_fecha, $codigo)
    {        
        try 
        {
            $sql = "UPDATE $tabla SET solicitud_confirmado=1, solicitud_token='', accion_usuario=?, accion_fecha=? WHERE solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($accion_usuario, $accion_fecha, $codigo));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarEmpresaPayStudio($codigo_ejecutivo, $empresa_nit, $empresa_adquiriente, $empresa_tipo_sociedad, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO empresa (empresa_depende, empresa_consolidada, empresa_categoria, ejecutivo_id, empresa_nit, empresa_adquiriente, empresa_tipo_sociedad, empresa_nombre_legal, empresa_nombre_fantasia, empresa_rubro, empresa_perfil_comercial, empresa_mcc, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array(-1, 1, 1, $codigo_ejecutivo, $empresa_nit, $empresa_adquiriente, $empresa_tipo_sociedad, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $accion_usuario, $accion_fecha));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Flujo de Trabajo
    
    // Nueva Incorporación, seleccionar multipes Roles y/o Usuarios para la notificación de la Etapa
    
    function ObtenerListaEtapaRolAll($codigo_etapa)
    {        
        try 
        {
            $sql = "SELECT e.codigo_id AS 'codigo', 'Rol Usuario' AS 'nombre', e.tipo_id
                    FROM etapa_rol_usuario e WHERE e.etapa_id=? ";
            
            $consulta = $this->db->query($sql, array($codigo_etapa));

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
    
    function ObtenerListaEtapaRol($codigo_etapa, $tipo_id)
    {        
        try 
        {
            $sql = "SELECT e.codigo_id AS 'codigo', r.rol_nombre AS 'nombre'
                    FROM etapa_rol_usuario e
                    INNER JOIN rol r ON r.rol_id=e.codigo_id
                    WHERE e.etapa_id=? AND e.tipo_id=? ";
            
            $consulta = $this->db->query($sql, array($codigo_etapa, $tipo_id));

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
    
    function ObtenerListaEtapaUsuario($codigo_etapa, $tipo_id)
    {        
        try 
        {
            $sql = "SELECT e.codigo_id AS 'codigo', CONCAT_WS(' ', u.usuario_nombres, u.usuario_app, u.usuario_apm) AS 'nombre'
                    FROM etapa_rol_usuario e
                    INNER JOIN usuarios u ON u.usuario_id=e.codigo_id
                    WHERE e.etapa_id=? AND e.tipo_id=? AND u.usuario_activo>0 ";
            
            $consulta = $this->db->query($sql, array($codigo_etapa, $tipo_id));

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
    
    function DeleteRolUsuarioEtapa($tipo_id, $codigo_etapa)
    {        
        try 
        {
            $sql = "DELETE FROM etapa_rol_usuario WHERE tipo_id=? AND etapa_id=? "; 
            
            $consulta = $this->db->query($sql, array($tipo_id, $codigo_etapa));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertRolUsuarioEtapa($codigo_etapa, $tipo_id, $codigo_id, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO etapa_rol_usuario(etapa_id, tipo_id, codigo_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($codigo_etapa, $tipo_id, $codigo_id, $accion_usuario, $accion_fecha));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerDatosFlujo($codigo_etapa, $codigo_flujo)
    {        
        try 
        {
            if($codigo_etapa == -1)
            {
                $sql = "SELECT e.etapa_color, e.etapa_id, e.etapa_nombre, e.etapa_detalle, e.etapa_depende, e.etapa_tiempo, e.etapa_notificar_correo, e.etapa_rol as 'rol_codigo', r.rol_nombre, e.etapa_categoria, e.etapa_alerta_correo, e.etapa_alerta_dias, e.etapa_alerta_hora, e.accion_usuario, e.accion_fecha FROM etapa e INNER JOIN rol r ON r.rol_id=e.etapa_rol WHERE e.etapa_categoria=? AND e.etapa_id>-1 ORDER BY e.etapa_id ASC ";
                $consulta = $this->db->query($sql, array($codigo_flujo));
            }
            else
            {
                $sql = "SELECT  e.etapa_color, e.etapa_id, e.etapa_nombre, e.etapa_detalle, e.etapa_depende, e.etapa_tiempo, e.etapa_notificar_correo, e.etapa_rol as 'rol_codigo', r.rol_nombre, e.etapa_categoria, e.etapa_alerta_correo, e.etapa_alerta_dias, e.etapa_alerta_hora, e.accion_usuario, e.accion_fecha FROM etapa e INNER JOIN rol r ON r.rol_id=e.etapa_rol WHERE e.etapa_id=? AND e.etapa_categoria=? AND e.etapa_id>-1 ORDER BY e.etapa_id ASC ";
                $consulta = $this->db->query($sql, array($codigo_etapa, $codigo_flujo));
            }

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
    
    function ObtenerLista_UsuariosActivos() 
    {        
        try 
        {
            $sql = "SELECT u.usuario_id, u.usuario_user, u.estructura_agencia_id, a.estructura_agencia_nombre, r.estructura_regional_nombre, u.usuario_pass, u.usuario_fecha_creacion, u.usuario_fecha_ultimo_acceso, u.usuario_fecha_ultimo_password, u.usuario_password_reset, CONCAT_WS(' ', u.usuario_nombres, u.usuario_app, u.usuario_apm) as nombre_completo, u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email, u.usuario_telefono, u.usuario_direccion, u.usuario_rol, u.usuario_activo FROM usuarios u INNER JOIN estructura_agencia a ON a.estructura_agencia_id=u.estructura_agencia_id INNER JOIN estructura_regional r ON a.estructura_regional_id=r.estructura_regional_id WHERE u.usuario_activo>0 ORDER BY usuario_user "; 

            $consulta = $this->db->query($sql);

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
    
    function ObtenerDatosFlujoEspec($codigo_etapa)
    {        
        try 
        {
            $sql = "SELECT e.etapa_id, e.etapa_nombre, e.etapa_detalle, e.etapa_depende, e.etapa_tiempo, e.etapa_notificar_correo, e.etapa_categoria, e.accion_usuario, e.accion_fecha FROM etapa e WHERE e.etapa_id=? ORDER BY e.etapa_id ASC ";
            $consulta = $this->db->query($sql, array($codigo_etapa));

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
    
    function ObteneParentFlujo($codigo_etapa, $codigo_flujo)
    {        
        try 
        {
            if($codigo_flujo == -1)
            {
                $sql = "SELECT e.etapa_id, e.etapa_nombre, e.etapa_detalle, e.etapa_depende, e.etapa_tiempo, e.etapa_notificar_correo, e.etapa_rol as 'rol_codigo', r.rol_nombre, e.accion_usuario, e.accion_fecha FROM etapa e INNER JOIN rol r ON r.rol_id=e.etapa_rol WHERE e.etapa_id!=? ORDER BY e.etapa_id ASC "; 
            }
            else
            {
                $sql = "SELECT e.etapa_id, e.etapa_nombre, e.etapa_detalle, e.etapa_depende, e.etapa_tiempo, e.etapa_notificar_correo, e.etapa_rol as 'rol_codigo', r.rol_nombre, e.accion_usuario, e.accion_fecha FROM etapa e INNER JOIN rol r ON r.rol_id=e.etapa_rol WHERE e.etapa_id!=? AND e.etapa_id>0 AND e.etapa_categoria=? ORDER BY e.etapa_id ASC "; 
            }
            
            $consulta = $this->db->query($sql, array($codigo_etapa, $codigo_flujo));

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
    
    function UpdateFlujo($codigo_parent, $etapa_nombre, $etapa_detalle, $etapa_tiempo, $notificar, $codigo_rol, $nombre_usuario, $fecha_actual, $estructura_id, $alertar, $etapa_alerta_hora, $dias_list, $etapa_color)
    {        
        try 
        {
            $sql = "UPDATE etapa SET etapa_depende=?, etapa_nombre=?, etapa_detalle=?, etapa_tiempo=?, etapa_notificar_correo=?, etapa_rol=?, accion_usuario=?, accion_fecha=?, etapa_alerta_correo=?, etapa_alerta_hora=?, etapa_alerta_dias=?, etapa_color=? WHERE etapa_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_parent, $etapa_nombre, $etapa_detalle, $etapa_tiempo, $notificar, $codigo_rol, $nombre_usuario, $fecha_actual, $alertar, $etapa_alerta_hora, $dias_list, $etapa_color, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObteneRolHijoFlujo($codigo_etapa)
    {        
        try 
        {
            $sql = "SELECT e.etapa_id, e.etapa_rol, r.rol_nombre, e.etapa_notificar_correo FROM etapa e INNER JOIN rol r ON r.rol_id=e.etapa_rol WHERE e.etapa_depende=? "; 

            $consulta = $this->db->query($sql, array($codigo_etapa));

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
    
    function VerificaEnvioEtapa($codigo_etapa)
    {        
        try 
        {
            $sql = "SELECT e.etapa_id, e.etapa_id, e.etapa_nombre, e.etapa_detalle, e.etapa_depende, e.etapa_tiempo, e.etapa_notificar_correo, e.etapa_rol as 'rol_codigo', r.rol_nombre, e.etapa_categoria, e.accion_usuario, e.accion_fecha FROM etapa e INNER JOIN rol r ON r.rol_id=e.etapa_rol WHERE e.etapa_id=? ORDER BY e.etapa_id ASC "; 

            $consulta = $this->db->query($sql, array($codigo_etapa));

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
    
    function ObtenerParentEtapa($codigo_etapa)
    {        
        try 
        {
            $sql = "SELECT etapa_depende FROM etapa WHERE etapa_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_etapa));

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
    
    function HitosProspecto($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT p.prospecto_id, e.etapa_id, e.etapa_nombre, h.hito_fecha_ini FROM hito h INNER JOIN etapa e ON e.etapa_id=h.etapa_id INNER JOIN prospecto p ON p.prospecto_id=h.prospecto_id WHERE p.prospecto_id=? ORDER BY e.etapa_id ASC ";
            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    // PROSPECTOS
    
    function RechazarProspecto($accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_rechazado=1, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarObservacionDoc($prospecto_id, $usuario_id, $documento_id, $obs_tipo, $obs_fecha, $obs_detalle, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO observacion_documento(prospecto_id, usuario_id, documento_id, obs_tipo, obs_fecha, obs_detalle, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($prospecto_id, $usuario_id, $documento_id, $obs_tipo, $obs_fecha, $obs_detalle, $accion_usuario, $accion_fecha));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObservarDocProspecto($accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_consolidado=0, prospecto_observado_app=1, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
        
    function UpdateObservacionDoc($obs_estado, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE observacion_documento SET obs_estado=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($obs_estado, $accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertSeguimientoProspecto($prospecto_id, $etapa_id, $seguimiento_accion, $seguimiento_detalle, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO seguimiento(prospecto_id, etapa_id, seguimiento_accion, seguimiento_detalle, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($prospecto_id, $etapa_id, $seguimiento_accion, $seguimiento_detalle, $accion_usuario, $accion_fecha));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateEtapaProspecto($prospecto_etapa, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_observado=0, prospecto_etapa=?, prospecto_etapa_fecha=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($prospecto_etapa, $accion_fecha, $accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ListadoDetalleProspecto($codigo_prospecto)
    {
        try 
        {
            $sql = "SELECT camp.camp_id, camp.camp_nombre, camp.camp_plazo, camp.camp_monto_oferta, camp.camp_tasa, p.prospecto_evaluacion, p.prospecto_id, p.ejecutivo_id, u.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) as 'ejecutivo_nombre', p.tipo_persona_id, p.empresa_id, p.prospecto_fecha_asignacion, p.prospecto_carpeta, p.prospecto_etapa, et.etapa_nombre, p.prospecto_etapa_fecha, p.prospecto_checkin, p.prospecto_checkin_fecha, p.prospecto_checkin_geo, p.prospecto_consolidar_fecha, p.prospecto_consolidar_geo, p.prospecto_consolidado, p.prospecto_observado_app, p.prospecto_estado_actual, c.cal_visita_ini, c.cal_visita_fin, h.hito_fecha_ini as 'fecha_derivada_etapa', p.prospecto_observado, 
                    p.general_categoria, p.general_depende, p.general_solicitante, p.general_ci, p.general_ci_extension, p.general_telefono, p.general_email, p.general_direccion, p.general_actividad, p.general_destino, p.general_interes, p.prospecto_jda_eval, p.prospecto_jda_eval_texto, p.prospecto_jda_eval_usuario, p.prospecto_jda_eval_fecha, p.prospecto_desembolso, p.prospecto_desembolso_monto, p.prospecto_desembolso_usuario, p.prospecto_desembolso_fecha
                    FROM prospecto p
                    INNER JOIN campana camp ON camp.camp_id=p.camp_id
                    INNER JOIN ejecutivo e ON e.ejecutivo_id=p.ejecutivo_id
                    INNER JOIN usuarios u ON u.usuario_id= e.usuario_id
                    INNER JOIN empresa emp ON emp.empresa_id=p.empresa_id
                    INNER JOIN calendario c ON c.cal_id_visita=p.prospecto_id AND c.cal_tipo_visita=1
                    INNER JOIN etapa et ON et.etapa_id=prospecto_etapa
                    INNER JOIN hito h ON h.etapa_id=p.prospecto_etapa AND h.prospecto_id=p.prospecto_id
                    WHERE p.prospecto_id=? ";
            
            $consulta = $this->db->query($sql, array($codigo_prospecto));
            
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
    
    function ListadoBandejasOnboarding($filtro)
    {        
        try 
        {
            $sql = "SELECT t.notificar_cierre_fecha, t.terceros_id, t.tipo_persona_id, t.terceros_estado, t.tercero_asistencia, t.codigo_agencia_fie, t.dir_localidad_ciudad, t.di_primernombre, t.di_primerapellido, t.di_segundoapellido, t.cI_numeroraiz, t.cI_complemento, t.cI_lugar_emisionoextension, t.direccion_email, t.dir_notelefonico, t.tipo_cuenta, t.envio,
                    p.prospecto_fecha_conclusion, etapa.etapa_nombre, p.prospecto_id, p.prospecto_llamada, p.prospecto_llamada_fecha, p.prospecto_llamada_geo, p.general_solicitante, p.ejecutivo_id, u.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) as 'ejecutivo_nombre', p.tipo_persona_id, 
                    p.prospecto_fecha_asignacion, p.prospecto_carpeta, p.prospecto_etapa, p.prospecto_etapa_fecha, p.prospecto_checkin, p.prospecto_checkin_fecha, p.prospecto_checkin_geo, p.prospecto_consolidar_fecha, p.prospecto_consolidar_geo, p.prospecto_consolidado, h.hito_fecha_ini as 'fecha_derivada_etapa' FROM prospecto p
                    INNER JOIN terceros t ON t.terceros_id=p.onboarding_codigo
                    INNER JOIN ejecutivo e ON e.ejecutivo_id=t.ejecutivo_id
                    INNER JOIN hito h ON h.etapa_id=p.prospecto_etapa AND h.prospecto_id=p.prospecto_id
                    INNER JOIN etapa ON etapa.etapa_id=p.prospecto_etapa
                    INNER JOIN usuarios u ON u.usuario_id= e.usuario_id
                    WHERE " . $filtro;
            
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
    
    function ListadoBandejasProspecto($filtro)
    {        
        try 
        {
            $sql = "SELECT p.*, p.prospecto_fecha_conclusion, p.prospecto_evaluacion, etapa.etapa_nombre, cam.camp_id, cam.camp_nombre, cam.camp_desc, cam.camp_plazo, cam.camp_monto_oferta, cam.camp_tasa, cam.camp_fecha_inicio, p.prospecto_id, p.prospecto_llamada, p.prospecto_llamada_fecha, p.prospecto_llamada_geo, p.general_solicitante, p.general_ci, p.general_ci_extension, p.general_telefono, p.general_email, p.general_direccion, p.general_actividad, p.general_destino, p.general_interes, p.ejecutivo_id, u.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) as 'ejecutivo_nombre', p.tipo_persona_id, 
                    p.empresa_id, emp.empresa_categoria as 'empresa_categoria_codigo',
                    p.prospecto_fecha_asignacion, p.prospecto_carpeta, p.prospecto_etapa, p.prospecto_etapa_fecha, p.prospecto_checkin, p.prospecto_checkin_fecha, p.prospecto_checkin_geo, p.prospecto_consolidar_fecha, p.prospecto_consolidar_geo, p.prospecto_consolidado, p. prospecto_observado, p.prospecto_observado_app, p.prospecto_estado_actual, c.cal_visita_ini, c.cal_visita_fin, h.hito_fecha_ini as 'fecha_derivada_etapa' FROM prospecto p
                    INNER JOIN ejecutivo e ON e.ejecutivo_id=p.ejecutivo_id
                    INNER JOIN usuarios u ON u.usuario_id= e.usuario_id
                    INNER JOIN empresa emp ON emp.empresa_id=p.empresa_id
                    INNER JOIN calendario c ON c.cal_id_visita=p.prospecto_id AND c.cal_tipo_visita=1
                    INNER JOIN hito h ON h.etapa_id=p.prospecto_etapa AND h.prospecto_id=p.prospecto_id
                    INNER JOIN campana cam ON cam.camp_id=p.camp_id
                    INNER JOIN etapa ON etapa.etapa_id=p.prospecto_etapa
                    WHERE " . $filtro;
            
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
    
    function HistorialObservacionesDoc($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT o.obs_id, o.prospecto_id, o.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) AS 'usuario_nombre', o.documento_id, d.documento_nombre, o.obs_tipo, o.obs_fecha, o.obs_detalle, o.obs_estado, o.accion_usuario, o.accion_fecha 
                    FROM observacion_documento o
                    INNER JOIN usuarios u ON u.usuario_id=o.usuario_id
                    INNER JOIN documento d ON d.documento_id=o.documento_id
                    WHERE o.prospecto_id=? ORDER BY o.obs_fecha ASC ";
            
            $consulta = $this->db->query($sql, array($codigo_prospecto));
            
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
    
    function HistorialObservacionesProc($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT o.obs_id, o.prospecto_id, o.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) AS 'usuario_nombre', o.etapa_id, e.etapa_nombre, o.obs_tipo, o.obs_fecha, o.obs_detalle, o.obs_estado, o.accion_usuario, o.accion_fecha FROM observacion o
                    INNER JOIN usuarios u ON u.usuario_id=o.usuario_id
                    INNER JOIN etapa e ON e.etapa_id=o.etapa_id
                    WHERE o.prospecto_id=? ORDER BY o.obs_fecha ASC ";
            
            $consulta = $this->db->query($sql, array($codigo_prospecto));
            
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
    
    function HistorialExcepcion($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT ex.excepcion_detalle_id, ex.prospecto_id, ex.etapa_id, e.etapa_nombre, ex.excepcion_detalle, ex.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) AS 'usuario_nombre', ex.accion_usuario, ex.accion_fecha FROM excepcion_detalle ex
                    INNER JOIN usuarios u ON u.usuario_id=ex.usuario_id
                    INNER JOIN etapa e ON e.etapa_id=ex.etapa_id
                    WHERE ex.prospecto_id=? ORDER BY ex.excepcion_detalle_id ASC ";
            
            $consulta = $this->db->query($sql, array($codigo_prospecto));
            
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
    
    function HistorialSeguimiento($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT seguimiento_id, s.prospecto_id, s.etapa_id, e.etapa_nombre, s.seguimiento_accion, s.seguimiento_detalle, s.accion_usuario, CONCAT_WS(' ', u.usuario_nombres, u.usuario_app, u.usuario_apm) as usuario_nombre, s.accion_fecha 
                    FROM seguimiento s
                    INNER JOIN etapa e ON e.etapa_id=s.etapa_id
                    INNER JOIN usuarios u ON u.usuario_user=s.accion_usuario
                    WHERE s.prospecto_id=? ORDER BY s.accion_fecha ASC ";
            
            $consulta = $this->db->query($sql, array($codigo_prospecto));
            
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
    
    function HitoProspecto($prospecto_id, $etapa_nueva, $etapa_actual, $accion_usuario, $accion_fecha)
    {        
        try 
        {   
            // Paso 1: Se consulta si existen registros con los criterios
            $sql1 = "SELECT hito_id FROM hito WHERE prospecto_id=? AND etapa_id=?";
            $consulta1 = $this->db->query($sql1, array($prospecto_id, $etapa_nueva));
            
            if ($consulta1->num_rows() == 0)
            {
                // Paso 2: Se inserta la etapa siguiente siempre y cuando no este registrada
                $sql = "INSERT INTO hito(prospecto_id, etapa_id, hito_fecha_ini, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?)";
                $consulta = $this->db->query($sql, array($prospecto_id, $etapa_nueva, $accion_fecha, $accion_usuario, $accion_fecha));
            }
            
            // Paso 3: Se actualiza la fecha de finalziación de la etapa actual
            $sql2 = "UPDATE hito SET hito_fecha_fin=?, hito_finalizo=1, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? AND etapa_id=? ";
            $consulta2 = $this->db->query($sql2, array($accion_fecha, $accion_usuario, $accion_fecha, $prospecto_id, $etapa_actual));
            
            // Paso 4: Se actualiza la tabla Observación para que todas las observaciones del prospecto se marquen como "Solucionadas"
            $sql3 = "UPDATE observacion SET obs_estado=0, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? AND obs_estado=1 ";
            $consulta3 = $this->db->query($sql3, array($accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function EvaluacionAntecedentesProspecto($prospecto_rev, $prospecto_rev_informe, $prospecto_rev_pep, $prospecto_rev_match, $prospecto_rev_infocred, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_estado_actual=3, prospecto_rev=?, prospecto_rev_fecha=?, prospecto_rev_informe=?, prospecto_rev_pep=?, prospecto_rev_match=?, prospecto_rev_infocred=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($prospecto_rev, $accion_fecha, $prospecto_rev_informe, $prospecto_rev_pep, $prospecto_rev_match, $prospecto_rev_infocred, $accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function VerificaequisitosProspecto($misma_info, $cambio_poder, $cambio_banco, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_misma_inf=?, prospecto_cambia_poder=?, prospecto_reporte_bancario=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($misma_info, $cambio_poder, $cambio_banco, $accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function RevisionCumplimientoProspecto($prospecto_vobo_cumplimiento, $prospecto_aux_cump, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_vobo_cumplimiento=?, prospecto_vobo_cumplimiento_fecha=?, prospecto_aux_cump=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($prospecto_vobo_cumplimiento, $accion_fecha, $prospecto_aux_cump, $accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // -- Formulario MATCH
    
    function ListaDatosForm_Match($prospecto_id)
    {        
        try 
        {
            $sql = "SELECT form_id, prospecto_id, form_razon_social, form_direccion, form_departamento, form_nit, form_representante_legal, form_ci, form_rubro, form_observacion, accion_usuario, accion_fecha FROM form_cumplimiento_match WHERE prospecto_id=? LIMIT 1 "; 

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    function InsertarForm_Match($prospecto_id, $accion_fecha, $accion_usuario)
    {        
        try 
        {
            $sql = "INSERT INTO form_cumplimiento_match(prospecto_id, accion_usuario, accion_fecha) VALUES (?, ?, ?)"; 
            
            $consulta = $this->db->query($sql, array($prospecto_id, $accion_fecha, $accion_usuario));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateForm_Match($form_razon_social, $form_direccion, $form_departamento, $form_nit, $form_representante_legal, $form_ci, $form_rubro, $form_observacion, $accion_usuario, $accion_fecha, $codigo_prospecto)
    {        
        try 
        {
            $sql = "UPDATE form_cumplimiento_match SET
                    form_razon_social=?, 
                    form_direccion=?, 
                    form_departamento=?, 
                    form_nit=?, 
                    form_representante_legal=?, 
                    form_ci=?, 
                    form_rubro=?, 
                    form_observacion=?, 
                    accion_usuario=?, 
                    accion_fecha=? 
                    WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($form_razon_social, $form_direccion, $form_departamento, $form_nit, $form_representante_legal, $form_ci, $form_rubro, $form_observacion, $accion_usuario, $accion_fecha, $codigo_prospecto));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // -- Formulario Sociedad
    
    function ListaDatosForm_Sociedad($prospecto_id)
    {        
        try 
        {
            $sql = "SELECT form_id, prospecto_id, form_razon_social, form_nit, form_matricula_comercio, form_direccion, form_departamento, form_departamento_deseable, form_mcc, form_mcc_deseable, form_rubro, form_flujo_estimado, form_cuenta_bob, form_cuenta_usd, form_titular_cuenta, form_ci, form_representante_legal, form_lista_accionistas, form_requisitos_afiliacion, form_requisitos_afiliacion_deseable, form_infocred_cuenta_endeuda_est, form_infocred_cuenta_endeuda_rep, form_infocred_calificacion_riesgos_est, form_infocred_calificacion_riesgos_rep, form_infocred_deseable, form_pep_categorizado_est, form_pep_categorizado_rep, form_pep_codigo_est, form_pep_codigo_rep, form_pep_cargo_est, form_pep_cargo_rep, form_pep_institucion_est, form_pep_institucion_rep, form_pep_gestion_est, form_pep_gestion_rep, form_pep_deseable, form_lista_confidenciales_est, form_lista_confidenciales_rep, form_lista_deseable, form_match_observado_est, form_match_observado_rep, form_match_observado_deseable, form_lista_negativa_est, form_lista_negativa_rep, form_lista_negativa_deseable, form_comentarios, form_comentarios_deseable, form_firma_entrega_nombre, form_firma_entrega_cargo, form_firma_entrega_fecha, accion_usuario, accion_fecha FROM form_cumplimiento_sociedad WHERE prospecto_id=? LIMIT 1 "; 

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    function InsertarForm_Sociedad($prospecto_id, $accion_fecha, $accion_usuario)
    {        
        try 
        {
            $sql = "INSERT INTO form_cumplimiento_sociedad(prospecto_id, accion_usuario, accion_fecha) VALUES (?, ?, ?)"; 
            
            $consulta = $this->db->query($sql, array($prospecto_id, $accion_fecha, $accion_usuario));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateForm_Sociedad($form_razon_social, $form_nit, $form_matricula_comercio, $form_direccion, $form_departamento, $form_departamento_deseable, $form_mcc, $form_mcc_deseable, $form_rubro, $form_flujo_estimado, $form_cuenta_bob, $form_cuenta_usd, $form_titular_cuenta, $form_ci, $form_representante_legal, $form_lista_accionistas, $form_requisitos_afiliacion, $form_requisitos_afiliacion_deseable, $form_infocred_cuenta_endeuda_est, $form_infocred_cuenta_endeuda_rep, $form_infocred_calificacion_riesgos_est, $form_infocred_calificacion_riesgos_rep, $form_infocred_deseable, $form_pep_categorizado_est, $form_pep_categorizado_rep, $form_pep_codigo_est, $form_pep_codigo_rep, $form_pep_cargo_est, $form_pep_cargo_rep, $form_pep_institucion_est, $form_pep_institucion_rep, $form_pep_gestion_est, $form_pep_gestion_rep, $form_pep_deseable, $form_lista_confidenciales_est, $form_lista_confidenciales_rep, $form_lista_deseable, $form_match_observado_est, $form_match_observado_rep, $form_match_observado_deseable, $form_lista_negativa_est, $form_lista_negativa_rep, $form_lista_negativa_deseable, $form_comentarios, $form_comentarios_deseable, $form_firma_entrega_nombre, $form_firma_entrega_cargo, $form_firma_entrega_fecha, $accion_usuario, $accion_fecha, $codigo_prospecto)
    {        
        try 
        {
            $sql = "UPDATE form_cumplimiento_sociedad SET
                    form_razon_social=?, 
                    form_nit=?, 
                    form_matricula_comercio=?, 
                    form_direccion=?, 
                    form_departamento=?, 
                    form_departamento_deseable=?, 
                    form_mcc=?, 
                    form_mcc_deseable=?, 
                    form_rubro=?, 
                    form_flujo_estimado=?, 
                    form_cuenta_bob=?, 
                    form_cuenta_usd=?, 
                    form_titular_cuenta=?, 
                    form_ci=?, 
                    form_representante_legal=?, 
                    form_lista_accionistas=?, 
                    form_requisitos_afiliacion=?, 
                    form_requisitos_afiliacion_deseable=?, 
                    form_infocred_cuenta_endeuda_est=?, 
                    form_infocred_cuenta_endeuda_rep=?, 
                    form_infocred_calificacion_riesgos_est=?, 
                    form_infocred_calificacion_riesgos_rep=?, 
                    form_infocred_deseable=?, 
                    form_pep_categorizado_est=?, 
                    form_pep_categorizado_rep=?, 
                    form_pep_codigo_est=?, 
                    form_pep_codigo_rep=?, 
                    form_pep_cargo_est=?, 
                    form_pep_cargo_rep=?, 
                    form_pep_institucion_est=?, 
                    form_pep_institucion_rep=?, 
                    form_pep_gestion_est=?, 
                    form_pep_gestion_rep=?, 
                    form_pep_deseable=?, 
                    form_lista_confidenciales_est=?, 
                    form_lista_confidenciales_rep=?, 
                    form_lista_deseable=?, 
                    form_match_observado_est=?, 
                    form_match_observado_rep=?, 
                    form_match_observado_deseable=?, 
                    form_lista_negativa_est=?, 
                    form_lista_negativa_rep=?, 
                    form_lista_negativa_deseable=?, 
                    form_comentarios=?, 
                    form_comentarios_deseable=?, 
                    form_firma_entrega_nombre=?, 
                    form_firma_entrega_cargo=?, 
                    form_firma_entrega_fecha=?, 
                    accion_usuario=?, 
                    accion_fecha=?
                    WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($form_razon_social, $form_nit, $form_matricula_comercio, $form_direccion, $form_departamento, $form_departamento_deseable, $form_mcc, $form_mcc_deseable, $form_rubro, $form_flujo_estimado, $form_cuenta_bob, $form_cuenta_usd, $form_titular_cuenta, $form_ci, $form_representante_legal, $form_lista_accionistas, $form_requisitos_afiliacion, $form_requisitos_afiliacion_deseable, $form_infocred_cuenta_endeuda_est, $form_infocred_cuenta_endeuda_rep, $form_infocred_calificacion_riesgos_est, $form_infocred_calificacion_riesgos_rep, $form_infocred_deseable, $form_pep_categorizado_est, $form_pep_categorizado_rep, $form_pep_codigo_est, $form_pep_codigo_rep, $form_pep_cargo_est, $form_pep_cargo_rep, $form_pep_institucion_est, $form_pep_institucion_rep, $form_pep_gestion_est, $form_pep_gestion_rep, $form_pep_deseable, $form_lista_confidenciales_est, $form_lista_confidenciales_rep, $form_lista_deseable, $form_match_observado_est, $form_match_observado_rep, $form_match_observado_deseable, $form_lista_negativa_est, $form_lista_negativa_rep, $form_lista_negativa_deseable, $form_comentarios, $form_comentarios_deseable, $form_firma_entrega_nombre, $form_firma_entrega_cargo, $form_firma_entrega_fecha, $accion_usuario, $accion_fecha, $codigo_prospecto));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // -- Evaluación Legal
    
    function ListaDatosEvaluacion($prospecto_id)
    {        
        try 
        {
            $sql = "SELECT e.evaluacion_legal_id, e.prospecto_id, e.usuario_id, e.evaluacion_denominacion_comercial, e.evaluacion_razon_social, e.evaluacion_legal_nit_doc, e.evaluacion_legal_nit_al_numero, e.evaluacion_legal_nit_al_representante, e.evaluacion_legal_idem, e.evaluacion_legal_cert_doc, e.evaluacion_legal_cert_al_principal, e.evaluacion_legal_cert_al_secundaria, e.evaluacion_legal_cert_al_idem, e.evaluacion_legal_ci_doc, e.evaluacion_legal_ci_al_pertenece, e.evaluacion_legal_ci_al_vigente, e.evaluacion_legal_ci_al_fecnac, e.evaluacion_legal_ci_al_nombre, e.evaluacion_legal_test_doc, e.evaluacion_legal_test_al_numero, e.evaluacion_legal_test_al_duracion, e.evaluacion_legal_test_al_fecha, e.evaluacion_legal_test_al_objeto, e.evaluacion_legal_poder_doc, e.evaluacion_legal_poder_al_fecha, e.evaluacion_legal_poder_al_numero, e.evaluacion_legal_poder_al_firma, e.evaluacion_legal_poder_al_facultades, e.evaluacion_legal_funde_doc, e.evaluacion_legal_funde_al_fecemi, e.evaluacion_legal_funde_al_fecvig, e.evaluacion_legal_funde_al_idem, e.evaluacion_legal_funde_al_representante, e.evaluacion_legal_funde_al_denominacion, e.evaluacion_legal_resultado, e.evaluacion_legal_conclusion, e.evaluacion_pertenece_regional, r.estructura_regional_nombre, e.evaluacion_legal_fecha_solicitud, e.evaluacion_legal_fecha_evaluacion, e.evaluacion_legal_revisadopor, e.evaluacion_legal_estado, e.accion_usuario, e.accion_fecha FROM evaluacion_legal e INNER JOIN estructura_regional r ON r.estructura_regional_id=e.evaluacion_pertenece_regional WHERE prospecto_id=? LIMIT 1 "; 

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    function VerificaEvaluacionLegal($prospecto_id)
    {        
        try 
        {
            $sql = "SELECT prospecto_id FROM evaluacion_legal WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($prospecto_id));
            
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
    
    function InsertarEvaluacionLegal($prospecto_id, $usuario_id, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO evaluacion_legal(prospecto_id, usuario_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?)"; 
            
            $consulta = $this->db->query($sql, array($prospecto_id, $usuario_id, $accion_usuario, $accion_fecha));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateEvaluacionLegal($evaluacion_denominacion_comercial, $evaluacion_razon_social, $evaluacion_doc_nit, $evaluacion_nit, $evaluacion_representante_legal, $evaluacion_razon_idem, $evaluacion_doc_certificado, $evaluacion_actividad_principal, $evaluacion_actividad_secundaria, $evaluacion_certificado_idem, $evaluacion_doc_ci, $evaluacion_ci_pertenece, $evaluacion_ci_vigente, $evaluacion_ci_fecnac, $evaluacion_ci_titular, $evaluacion_doc_test, $evaluacion_numero_testimonio, $evaluacion_duracion_empresa, $evaluacion_fecha_testimonio, $evaluacion_objeto_constitucion, $evaluacion_doc_poder, $evaluacion_fecha_testimonio_poder, $evaluacion_numero_testimonio_poder, $evaluacion_firma_conjunta, $evaluacion_facultad_representacion, $evaluacion_doc_funde, $evaluacion_fundaempresa_emision, $evaluacion_fundaempresa_vigencia, $evaluacion_idem_escritura, $evaluacion_idem_poder, $evaluacion_idem_general, $evaluacion_resultado, $opcion_conclusion, $evaluacion_pertenece_regional, $evaluacion_fecha_solicitud, $evaluacion_fecha_evaluacion, $nombre_usuario, $fecha_actual, $codigo_prospecto)
    {        
        try 
        {
            $sql = "UPDATE evaluacion_legal SET 
                    evaluacion_denominacion_comercial=?, 
                    evaluacion_razon_social=?, 
                    evaluacion_legal_nit_doc=?, 
                    evaluacion_legal_nit_al_numero=?, 
                    evaluacion_legal_nit_al_representante=?, 
                    evaluacion_legal_idem=?, 
                    evaluacion_legal_cert_doc=?, 
                    evaluacion_legal_cert_al_principal=?, 
                    evaluacion_legal_cert_al_secundaria=?, 
                    evaluacion_legal_cert_al_idem=?, 
                    evaluacion_legal_ci_doc=?, 
                    evaluacion_legal_ci_al_pertenece=?, 
                    evaluacion_legal_ci_al_vigente=?, 
                    evaluacion_legal_ci_al_fecnac=?, 
                    evaluacion_legal_ci_al_nombre=?, 
                    evaluacion_legal_test_doc=?, 
                    evaluacion_legal_test_al_numero=?, 
                    evaluacion_legal_test_al_duracion=?, 
                    evaluacion_legal_test_al_fecha=?, 
                    evaluacion_legal_test_al_objeto=?, 
                    evaluacion_legal_poder_doc=?, 
                    evaluacion_legal_poder_al_fecha=?, 
                    evaluacion_legal_poder_al_numero=?, 
                    evaluacion_legal_poder_al_firma=?, 
                    evaluacion_legal_poder_al_facultades=?, 
                    evaluacion_legal_funde_doc=?, 
                    evaluacion_legal_funde_al_fecemi=?, 
                    evaluacion_legal_funde_al_fecvig=?, 
                    evaluacion_legal_funde_al_idem=?, 
                    evaluacion_legal_funde_al_representante=?, 
                    evaluacion_legal_funde_al_denominacion=?, 
                    evaluacion_legal_resultado=?, 
                    evaluacion_legal_conclusion=?, 
                    evaluacion_pertenece_regional=?, 
                    evaluacion_legal_fecha_solicitud=?, 
                    evaluacion_legal_fecha_evaluacion=?, 
                    accion_usuario=?, 
                    accion_fecha=?
                    WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($evaluacion_denominacion_comercial, $evaluacion_razon_social, $evaluacion_doc_nit, $evaluacion_nit, $evaluacion_representante_legal, $evaluacion_razon_idem, $evaluacion_doc_certificado, $evaluacion_actividad_principal, $evaluacion_actividad_secundaria, $evaluacion_certificado_idem, $evaluacion_doc_ci, $evaluacion_ci_pertenece, $evaluacion_ci_vigente, $evaluacion_ci_fecnac, $evaluacion_ci_titular, $evaluacion_doc_test, $evaluacion_numero_testimonio, $evaluacion_duracion_empresa, $evaluacion_fecha_testimonio, $evaluacion_objeto_constitucion, $evaluacion_doc_poder, $evaluacion_fecha_testimonio_poder, $evaluacion_numero_testimonio_poder, $evaluacion_firma_conjunta, $evaluacion_facultad_representacion, $evaluacion_doc_funde, $evaluacion_fundaempresa_emision, $evaluacion_fundaempresa_vigencia, $evaluacion_idem_escritura, $evaluacion_idem_poder, $evaluacion_idem_general, $evaluacion_resultado, $opcion_conclusion, $evaluacion_pertenece_regional, $evaluacion_fecha_solicitud, $evaluacion_fecha_evaluacion, $nombre_usuario, $fecha_actual, $codigo_prospecto));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function RevisionLegalProspecto($prospecto_vobo_legal, $prospecto_aux_legal, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_vobo_legal=?, prospecto_vobo_legal_fecha=?, prospecto_aux_legal=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($prospecto_vobo_legal, $accion_fecha, $prospecto_aux_legal, $accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function GenerarExcepcionProspecto($accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_excepcion=1, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function AccionExcepcionProspecto($accion_excepcion, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_excepcion=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($accion_excepcion, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function AccionExcepcionProspectoPDF($accion_excepcion, $documento_pdf, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_excepcion=?, prospecto_excepcion_acta=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($accion_excepcion, $documento_pdf, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function AuxiliarCumpLegal($prospecto_aux_cump, $prospecto_aux_legal, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_aux_cump=?, prospecto_aux_legal=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($prospecto_aux_cump, $prospecto_aux_legal, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function GenerarObservacionProspecto($accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_observado=1, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarExcepcionProspecto($prospecto_id, $etapa_id, $excepcion_detalle, $usuario_id, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO excepcion_detalle(prospecto_id, etapa_id, excepcion_detalle, usuario_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($prospecto_id, $etapa_id, $excepcion_detalle, $usuario_id, $accion_usuario, $accion_fecha));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarObservacionProspecto($prospecto_id, $usuario_id, $etapa_id, $obs_tipo, $obs_fecha, $obs_detalle, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO observacion(prospecto_id, usuario_id, etapa_id, obs_tipo, obs_fecha, obs_detalle, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($prospecto_id, $usuario_id, $etapa_id, $obs_tipo, $obs_fecha, $obs_detalle, $accion_usuario, $accion_fecha));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Para Aprobar el Prospecto
    
    function AprobarProspecto($accion_usuario, $accion_fecha, $prospecto_id, $empresa_id)
    {        
        try 
        {
            // Paso 1: Se actualiza la tabla "prospecto"
            
            $sql1 = "UPDATE prospecto SET prospecto_estado_actual=4, prospecto_aceptado_afiliado=1, prospecto_aceptado_afiliado_fecha=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta1 = $this->db->query($sql1, array($accion_fecha, $accion_usuario, $accion_fecha, $prospecto_id));
            
            // Paso 2: Se actualiza la tabla "empresa" para marcar el registro como "Empresa Consolidada"
            
            $sql2 = "UPDATE empresa SET empresa_consolidada=1, accion_usuario=?, accion_fecha=? WHERE empresa_id=? "; 
            
            $consulta2 = $this->db->query($sql2, array($accion_usuario, $accion_fecha, $empresa_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ResultadoVerificacion($prospecto_resultado_verificacion, $accion_usuario, $accion_fecha, $prospecto_id, $empresa_id)
    {        
        try 
        {
            // Paso 1: Se actualiza la tabla "prospecto"
            
            $sql1 = "UPDATE prospecto SET prospecto_estado_actual=4, prospecto_resultado_verificacion=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta1 = $this->db->query($sql1, array((int)$prospecto_resultado_verificacion, $accion_usuario, $accion_fecha, $prospecto_id));
            
            // Paso 2: Se actualiza la tabla "empresa" para marcar el registro como "Empresa Consolidada"
            
            $sql2 = "UPDATE empresa SET empresa_consolidada=1, accion_usuario=?, accion_fecha=? WHERE empresa_id=? "; 
            
            $consulta2 = $this->db->query($sql2, array($accion_usuario, $accion_fecha, $empresa_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // MANTENIMIENTOS
    
    function ListadoDetalleMantenimientos($codigo_mantenimiento)
    {        
        try 
        {
            $sql = "SELECT m.mant_id, m.ejecutivo_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) as 'ejecutivo_nombre', m.empresa_id, emp.empresa_categoria as 'empresa_categoria_codigo',

                    CASE emp.empresa_categoria
                        WHEN 1 then emp.empresa_nombre_legal
                        WHEN 2 then emp.empresa_nombre_establecimiento
                    END AS 'empresa_nombre',
                    CASE emp.empresa_categoria
                        WHEN 1 then 'Comercio'
                        WHEN 2 then 'Establecimiento'
                    END AS 'empresa_categoria',

                    m.mant_fecha_asignacion, m.mant_estado, m.mant_checkin, m.mant_checkin_fecha, m.mant_checkin_geo, m.mant_completado_fecha, m.mant_completado_geo, m.mant_documento_adjunto, m.mant_otro, m.mant_otro_detalle, m.accion_usuario, m.accion_fecha, c.cal_visita_ini, c.cal_visita_fin FROM mantenimiento m 
                    INNER JOIN ejecutivo e ON e.ejecutivo_id=m.ejecutivo_id
                    INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                    INNER JOIN empresa emp ON emp.empresa_id=m.empresa_id
                    INNER JOIN calendario c ON c.cal_id_visita=m.mant_id AND c.cal_tipo_visita=2
                    WHERE m.mant_id=? ";
            
            $consulta = $this->db->query($sql, array($codigo_mantenimiento));
            
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
    
    function ObtenerDetalleMantenimiento_tareas($codigo_mantenimiento)
    {        
        try 
        {
            $sql = "SELECT mt.mantenimiento_tarea_id, mt.mant_id, mt.tarea_id, t.tarea_detalle FROM mantenimiento_tarea mt
                    INNER JOIN tarea t ON t.tarea_id=mt.tarea_id
                    WHERE mt.mant_id=? AND t.tarea_activo=1 ";

            $consulta = $this->db->query($sql, array($codigo_mantenimiento));

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
    
    // Credenciales
    
    function ObtenerDatosConf_General()
    {        
        try 
        {
            $sql = "SELECT * FROM conf_general WHERE conf_general_id=? "; 

            $consulta = $this->db->query($sql, array('conf-001'));

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
    
    function UpdateDatosConf_General($conf_general_key_google, $conf_horario_feriado, $conf_horario_laboral, $conf_atencion_desde1, $conf_atencion_hasta1, $conf_atencion_desde2, $conf_atencion_hasta2, $conf_atencion_dias, $conf_rekognition, $conf_rekognition_key, $conf_rekognition_secret, $conf_rekognition_region, $conf_rekognition_similarity, $conf_rekognition_texto_fallo, $conf_img_width_max, $conf_img_width_min, $conf_img_height_max, $conf_img_height_min, $conf_img_peso, $conf_doc_validacion1, $conf_doc_validacion2, $conf_texto_respuesta, $conf_onboarding_correo, $conf_onboarding_docs, 
            
            // Token
            $conf_token_dimension,
            $conf_token_otp,
            $conf_token_validez,
            $conf_token_texto,

            // WS JWT
            $conf_jwt_ws_uri,
            $conf_jwt_client_secret,
            $conf_jwt_username,
            $conf_jwt_password,
            $conf_captura_intentos,
            $conf_captura_intentos_texto,
            
            // WS COBIS
            $conf_cobis_ws_uri,
            $conf_cobis_texto,
            $conf_cobis_mandatorio,
            $conf_cobis_tipo_error,

            // WS SEGIP
            $conf_segip_ws_uri,
            $conf_segip_texto,
            $conf_segip_mandatorio,
            $conf_segip_intentos,
            
            // WS Prueba de Vida
            $conf_life_ws_uri,
            $conf_life_tipo_error,
            $conf_life_texto,

            // WS OCR
            $conf_ocr_ws_uri,
            $conf_ocr_porcentaje,
            $conf_ocr_tipo_error,
            $conf_ocr_texto,
            
            // WS FLUJO COBIS
            $conf_f_cobis_procesa_activo,
            $conf_f_cobis_header,
            $conf_f_cobis_uri_cliente_alta_params,
            $conf_f_cobis_uri_apertura_params,
            $conf_f_cobis_intentos,
            $conf_f_cobis_intentos_tiempo,
            $conf_f_cobis_intentos_operativo,
            $conf_f_cobis_uri_cliente_ci,
            $conf_f_cobis_uri_cliente_cobis,
            $conf_f_cobis_uri_cliente_alta,
            $conf_f_cobis_uri_apertura,
            
            // Solicitud de Crédito
            $conf_credito_texto,
            $conf_credito_notificar_email,
            $conf_credito_notificar_sms,
            $conf_credito_notificar_texto,
            $conf_credito_tipo_cambio,
            $conf_cuota_tasa_seguro,
            
            $conf_credito_notificar_sms_uri,
            $conf_credito_notificar_sms_bearer,
            
            // Integración con AD
            $conf_ad_activo,
            $conf_ad_host,
            $conf_ad_dominio,
            $conf_ad_dn,
            
            // Integración Envío SMS
            $conf_sms_name_plantilla,
            $conf_sms_channelid,
            $conf_sms_tiempo_validez,
            $conf_sms_permitido_cantidad,
            $conf_sms_permitido_tiempo,
            $conf_sms_onb_ambiente,
            $conf_sms_permitido_txt_error,
            
            $fecha_actual, $nombre_usuario, $conf_credenciales_id)
    {        
        try 
        {
            $sql = "UPDATE conf_general SET conf_general_key_google=?, conf_horario_feriado=?, conf_horario_laboral=?, conf_atencion_desde1=?, conf_atencion_hasta1=?, conf_atencion_desde2=?, conf_atencion_hasta2=?, conf_atencion_dias=?, conf_rekognition=?, conf_rekognition_key=?, conf_rekognition_secret=?, conf_rekognition_region=?, conf_rekognition_similarity=?, conf_rekognition_texto_fallo=?, conf_img_width_max=?, conf_img_width_min=?, conf_img_height_max=?, conf_img_height_min=?, conf_img_peso=?, conf_doc_validacion1=?, conf_doc_validacion2=?, conf_texto_respuesta=?, conf_onboarding_correo=?, conf_onboarding_docs=?, 
                
                conf_token_dimension=?,
                conf_token_otp=?,
                conf_token_validez=?,
                conf_token_texto=?,
                
                conf_jwt_ws_uri=?,
                conf_jwt_client_secret=?,
                conf_jwt_username=?,
                conf_jwt_password=?,
                conf_captura_intentos=?,
                conf_captura_intentos_texto=?,

                conf_cobis_ws_uri=?,
                conf_cobis_texto=?,
                conf_cobis_mandatorio=?,
                conf_cobis_tipo_error=?,
                    
                conf_segip_ws_uri=?,
                conf_segip_texto=?,
                conf_segip_mandatorio=?,
                conf_segip_intentos=?,

                conf_life_ws_uri=?,
                conf_life_tipo_error=?,
                conf_life_texto=?,

                conf_ocr_ws_uri=?,
                conf_ocr_porcentaje=?,
                conf_ocr_tipo_error=?,
                conf_ocr_texto=?,

                conf_f_cobis_procesa_activo=?,
                conf_f_cobis_header=?,
                conf_f_cobis_uri_cliente_alta_params=?,
                conf_f_cobis_uri_apertura_params=?,
                conf_f_cobis_intentos=?,
                conf_f_cobis_intentos_tiempo=?,
                conf_f_cobis_intentos_operativo=?,
                conf_f_cobis_uri_cliente_ci=?,
                conf_f_cobis_uri_cliente_cobis=?,
                conf_f_cobis_uri_cliente_alta=?,
                conf_f_cobis_uri_apertura=?,

                conf_credito_texto=?,
                conf_credito_notificar_email=?,
                conf_credito_notificar_sms=?,
                conf_credito_notificar_texto=?,
                conf_credito_tipo_cambio=?,
                conf_cuota_tasa_seguro=?,

                conf_credito_notificar_sms_uri=?,
                conf_credito_notificar_sms_bearer=?,

                conf_ad_activo=?,
                conf_ad_host=?,
                conf_ad_dominio=?,
                conf_ad_dn=?,

                conf_sms_name_plantilla=?,
                conf_sms_channelid=?,
                conf_sms_tiempo_validez=?,
                conf_sms_permitido_cantidad=?,
                conf_sms_permitido_tiempo=?,
                conf_sms_onb_ambiente=?,
                conf_sms_permitido_txt_error=?,

                accion_usuario=?, accion_fecha=? WHERE conf_general_id=? "; 

            $consulta = $this->db->query($sql, array($conf_general_key_google, $conf_horario_feriado, $conf_horario_laboral, $conf_atencion_desde1, $conf_atencion_hasta1, $conf_atencion_desde2, $conf_atencion_hasta2, $conf_atencion_dias, $conf_rekognition, $conf_rekognition_key, $conf_rekognition_secret, $conf_rekognition_region, $conf_rekognition_similarity, $conf_rekognition_texto_fallo, $conf_img_width_max, $conf_img_width_min, $conf_img_height_max, $conf_img_height_min, $conf_img_peso, $conf_doc_validacion1, $conf_doc_validacion2, $conf_texto_respuesta, $conf_onboarding_correo, $conf_onboarding_docs, 
                
                // Token
                $conf_token_dimension,
                $conf_token_otp,
                $conf_token_validez,
                $conf_token_texto,
                
                // WS JWT
                $conf_jwt_ws_uri,
                $conf_jwt_client_secret,
                $conf_jwt_username,
                $conf_jwt_password,
                $conf_captura_intentos,
                $conf_captura_intentos_texto,
                
                // WS COBIS
                $conf_cobis_ws_uri,
                $conf_cobis_texto,
                $conf_cobis_mandatorio,
                $conf_cobis_tipo_error,
                
                // WS SEGIP
                $conf_segip_ws_uri,
                $conf_segip_texto,
                $conf_segip_mandatorio,
                $conf_segip_intentos,

                // WS Prueba de Vida
                $conf_life_ws_uri,
                $conf_life_tipo_error,
                $conf_life_texto,
                
                // WS OCR
                $conf_ocr_ws_uri,
                $conf_ocr_porcentaje,
                $conf_ocr_tipo_error,
                $conf_ocr_texto,
                
                // WS FLUJO COBIS
                $conf_f_cobis_procesa_activo,
                $conf_f_cobis_header,
                $conf_f_cobis_uri_cliente_alta_params,
                $conf_f_cobis_uri_apertura_params,
                $conf_f_cobis_intentos,
                $conf_f_cobis_intentos_tiempo,
                $conf_f_cobis_intentos_operativo,
                $conf_f_cobis_uri_cliente_ci,
                $conf_f_cobis_uri_cliente_cobis,
                $conf_f_cobis_uri_cliente_alta,
                $conf_f_cobis_uri_apertura,
                
                // Solicitud de Crédito
                $conf_credito_texto,
                $conf_credito_notificar_email,
                $conf_credito_notificar_sms,
                $conf_credito_notificar_texto,
                $conf_credito_tipo_cambio,
                $conf_cuota_tasa_seguro,
                
                $conf_credito_notificar_sms_uri,
                $conf_credito_notificar_sms_bearer,
                
                // Integración con AD
                $conf_ad_activo,
                $conf_ad_host,
                $conf_ad_dominio,
                $conf_ad_dn,
                
                // Integración Envío SMS
                $conf_sms_name_plantilla,
                $conf_sms_channelid,
                $conf_sms_tiempo_validez,
                $conf_sms_permitido_cantidad,
                $conf_sms_permitido_tiempo,
                $conf_sms_onb_ambiente,
                $conf_sms_permitido_txt_error,
                
                $nombre_usuario, $fecha_actual, $conf_credenciales_id));
                $_SESSION["session_informacion"]["ad_active"] = $conf_ad_activo;

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerDatosConf_Credenciales() 
    {        
        try 
        {
            $sql = "SELECT conf_id, conf_long_min, conf_long_max, conf_req_upper, conf_req_num, conf_req_esp, conf_duracion_min, conf_duracion_max, conf_tiempo_bloqueo, conf_defecto, conf_ejecutivo_ic, accion_fecha, accion_usuario FROM conf_credenciales WHERE conf_id = ? "; 

            $consulta = $this->db->query($sql, array('conf-001'));

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
    
    function UpdateDatosConf_Credenciales($conf_credenciales_long_min, $conf_credenciales_long_max, $conf_credenciales_req_upper, $conf_credenciales_req_num, $conf_credenciales_req_esp, $conf_credenciales_duracion_min, $conf_credenciales_duracion_max, $conf_credenciales_tiempo_bloqueo, $conf_credenciales_defecto, $fecha_actual, $nombre_usuario, $conf_credenciales_id)
    {        
        try 
        {
            $sql = "UPDATE conf_credenciales  SET conf_long_min = ?, conf_long_max = ?, conf_req_upper = ?, conf_req_num = ?, conf_req_esp = ?, conf_duracion_min = ?, conf_duracion_max = ?, conf_tiempo_bloqueo = ?, conf_defecto = ?, accion_fecha = ?, accion_usuario = ? WHERE conf_id = ? "; 

            $consulta = $this->db->query($sql, array($conf_credenciales_long_min, $conf_credenciales_long_max, $conf_credenciales_req_upper, $conf_credenciales_req_num, $conf_credenciales_req_esp, $conf_credenciales_duracion_min, $conf_credenciales_duracion_max, $conf_credenciales_tiempo_bloqueo, $conf_credenciales_defecto, $fecha_actual, $nombre_usuario, $conf_credenciales_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateDatosConf_IC($conf_ejecutivo_ic, $fecha_actual, $nombre_usuario, $conf_credenciales_id)
    {        
        try 
        {
            $sql = "UPDATE conf_credenciales  SET conf_ejecutivo_ic = ?, accion_fecha = ?, accion_usuario = ? WHERE conf_id = ? "; 

            $consulta = $this->db->query($sql, array($conf_ejecutivo_ic, $fecha_actual, $nombre_usuario, $conf_credenciales_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerDatosConf_Correo() 
    {        
        try 
        {
            $sql = "SELECT conf_id, conf_protocol, conf_smtp_host, conf_smtp_port, conf_smtp_user, conf_smtp_pass, conf_mailtype, conf_charset, accion_usuario, accion_fecha FROM conf_correo WHERE conf_id = ? "; 

            $consulta = $this->db->query($sql, array('conf-001'));

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
    
    function UpdateDatosConf_Correo($conf_protocol, $conf_smtp_host, $conf_smtp_port, $conf_smtp_user, $conf_smtp_pass, $conf_mailtype, $conf_charset, $nombre_usuario, $fecha_actual, $conf_credenciales_id)
    {        
        try 
        {
            $sql = "UPDATE conf_correo SET conf_protocol = ?,conf_smtp_host = ?,conf_smtp_port = ?,conf_smtp_user = ?,conf_smtp_pass = ?,conf_mailtype = ?,conf_charset = ?,accion_usuario = ?,accion_fecha = ? WHERE conf_id = ? "; 

            $consulta = $this->db->query($sql, array($conf_protocol, $conf_smtp_host, $conf_smtp_port, $conf_smtp_user, $conf_smtp_pass, $conf_mailtype, $conf_charset, $nombre_usuario, $fecha_actual, $conf_credenciales_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerDatosConf_PlantillaCorreo($codigo_plantilla) 
    {        
        try 
        {
            if($codigo_plantilla != -1)
            {
                $sql = "SELECT conf_plantilla_id, conf_plantilla_nombre, conf_plantilla_titulo_correo, conf_plantilla_contenido, accion_usuario, accion_fecha FROM conf_correo_plantillas WHERE conf_plantilla_id = ? ORDER BY conf_plantilla_nombre "; 
            }
            else 
            {
                $sql = "SELECT conf_plantilla_id, conf_plantilla_nombre, conf_plantilla_titulo_correo, conf_plantilla_contenido, accion_usuario, accion_fecha FROM conf_correo_plantillas ORDER BY conf_plantilla_nombre "; 
            }            

            $consulta = $this->db->query($sql, array($codigo_plantilla));

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
    
    function UpdateDatosConf_PlantillaCorreo($conf_plantilla_nombre, $conf_plantilla_titulo_correo, $conf_plantilla_contenido, $accion_usuario, $accion_fecha, $codigo_plantilla)
    {        
        try 
        {
            $sql = "UPDATE conf_correo_plantillas SET conf_plantilla_nombre = ?,conf_plantilla_titulo_correo = ?,conf_plantilla_contenido = ?,accion_usuario = ?,accion_fecha = ? WHERE conf_plantilla_id = ? "; 

            $consulta = $this->db->query($sql, array($conf_plantilla_nombre, $conf_plantilla_titulo_correo, $conf_plantilla_contenido, $accion_usuario, $accion_fecha, $codigo_plantilla));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // CATÁLOGO

    function ObtenerTodoCatalogo($catalogo)
    {        
        try
        {
            $sql = "SELECT catalogo_id, catalogo_parent, catalogo_tipo_codigo, catalogo_codigo, catalogo_descripcion, catalogo_estado, accion_usuario, accion_fecha FROM catalogo WHERE catalogo_id>0 AND catalogo_tipo_codigo='" . $catalogo . "' ORDER BY catalogo_id "; 

            $consulta = $this->db->query($sql, array($catalogo));

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
    
    function ObtenerCatalogo($tipo, $parent_codigo, $parent_tipo, $filtro=-1)
    {        
        try
        {
            if($filtro == -1)
            {
                $filtro = '';
            }
            else
            {
                $filtro = " AND catalogo_codigo IN ($filtro) ";
            }
            
            $criterio = '';
            if($parent_codigo != '-1' && $parent_tipo != '-1')
            {
                $criterio = " AND catalogo_parent=(SELECT catalogo_codigo FROM catalogo WHERE catalogo_tipo_codigo='" . $parent_tipo . "' AND catalogo_codigo='" . $parent_codigo . "')";
            }
            
            $criterio .= $filtro;
            
            if($tipo == "-1")
            {
                    $sql = "SELECT catalogo_id, catalogo_parent, catalogo_tipo_codigo, catalogo_codigo, catalogo_descripcion, accion_usuario, accion_fecha FROM catalogo WHERE catalogo_estado=1 AND catalogo_id>0 " . $criterio . " AND catalogo_tipo_codigo NOT IN ('RUB') ORDER BY catalogo_id "; 
            }
            else
            {
                    $sql = "SELECT catalogo_id, catalogo_parent, catalogo_tipo_codigo, catalogo_codigo, catalogo_descripcion, accion_usuario, accion_fecha FROM catalogo WHERE catalogo_estado=1 AND catalogo_tipo_codigo = ? " . $criterio . "  ORDER BY catalogo_id "; 
            }

            $consulta = $this->db->query($sql, array($tipo, $parent_codigo));

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

    function ObtenerCatalogos()
    {        
        try
        {
            $sql = "SELECT catalogo_tipo_codigo FROM catalogo GROUP BY catalogo_tipo_codigo ORDER BY catalogo_id "; 

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
    
    function ObtenerDatosCatalogo($catalogo_codigo)
    {        
        try
        {
            $sql = "SELECT catalogo_id, catalogo_parent, catalogo_tipo_codigo, catalogo_codigo, catalogo_descripcion, accion_usuario, accion_fecha, catalogo_estado FROM catalogo WHERE catalogo_id = ? "; 

            $consulta = $this->db->query($sql, array($catalogo_codigo));

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
    
    function ObtenerDatosCatalogoParent($catalogo_codigo, $catalogo_tipo_codigo)
    {        
        try
        {
            $sql = "SELECT catalogo_id, catalogo_parent, catalogo_tipo_codigo, catalogo_codigo, catalogo_descripcion, accion_usuario, accion_fecha FROM catalogo WHERE catalogo_codigo = ? AND catalogo_tipo_codigo = ?"; 

            $consulta = $this->db->query($sql, array($catalogo_codigo, $catalogo_tipo_codigo));

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
    
    function VeriricaDatosCatalogo($catalogo_tipo_codigo, $catalogo_codigo)
    {        
        try
        {
            $sql = "SELECT catalogo_id FROM catalogo WHERE catalogo_tipo_codigo=? AND catalogo_codigo=? "; 

            $consulta = $this->db->query($sql, array($catalogo_tipo_codigo, $catalogo_codigo));

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
	
    function InsertarCatalogo($catalogo_tipo_codigo, $catalogo_parent, $catalogo_codigo, $catalogo_descripcion, $catalogo_estado, $accion_usuario, $accion_fecha) 
    {
        try 
        {
            $sql = "INSERT INTO catalogo(catalogo_tipo_codigo, catalogo_parent, catalogo_codigo, catalogo_descripcion, catalogo_estado, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?) "; 

            $consulta = $this->db->query($sql, array($catalogo_tipo_codigo, $catalogo_parent, $catalogo_codigo, $catalogo_descripcion, $catalogo_estado, $accion_usuario, $accion_fecha));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateCatalogo($catalogo_tipo_codigo, $catalogo_parent, $catalogo_codigo, $catalogo_descripcion, $catalogo_estado, $accion_usuario, $accion_fecha, $catalogo_id)
    {        
        try 
        {
            $sql = "UPDATE catalogo SET catalogo_tipo_codigo = ?, catalogo_parent = ?, catalogo_codigo = ?, catalogo_descripcion = ?, catalogo_estado = ?, accion_usuario = ?, accion_fecha = ? WHERE catalogo_id = ? "; 
			
            $consulta = $this->db->query($sql, array($catalogo_tipo_codigo, $catalogo_parent, $catalogo_codigo, $catalogo_descripcion, $catalogo_estado, $accion_usuario, $accion_fecha, $catalogo_id));
			
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function VerificaUsuario($usuario_user) 
    {        
        try 
        {
            $sql = "SELECT usuario_id, usuario_user, usuario_pass FROM usuarios WHERE usuario_user=?"; 

            $consulta = $this->db->query($sql, array($usuario_user));

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
	
    function VerificaPass($usuario_codigo, $usuario_pass) 
    {        
        try 
        {
            $sql = "SELECT usuario_id, usuario_user, usuario_pass FROM usuarios WHERE usuario_id=? AND usuario_pass=?"; 

            $consulta = $this->db->query($sql, array($usuario_codigo, $usuario_pass));

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
    
    function InsertarUsuario($usuario_user, $usuario_pass, $usuario_fecha_creacion, $usuario_nombres, $usuario_app, $usuario_apm, $usuario_email, $usuario_telefono, $usuario_direccion, $accion_fecha, $accion_usuario, $usuario_parent, $usuario_rol, $usuario_activo) 
    {        
        try 
        {
            $sql = "INSERT INTO usuarios (usuario_user, usuario_pass, usuario_fecha_creacion, usuario_nombres, usuario_app, usuario_apm, usuario_email, usuario_telefono, usuario_direccion, accion_fecha, accion_usuario, usuario_fecha_ultimo_acceso, usuario_fecha_ultimo_password, estructura_agencia_id, usuario_rol, usuario_password_reset, usuario_activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) "; 

            $consulta = $this->db->query($sql, array($usuario_user, $usuario_pass, $usuario_fecha_creacion, $usuario_nombres, $usuario_app, $usuario_apm, $usuario_email, $usuario_telefono, $usuario_direccion, $accion_fecha, $accion_usuario, '1500-01-01', $accion_fecha, $usuario_parent, $usuario_rol, 1, $usuario_activo));

            $listaResultados = $this->db->insert_id();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function UpdateUsuario($usuario_nombres, $usuario_app, $usuario_apm, $usuario_email, $usuario_telefono, $usuario_direccion, $fecha_actual, $nombre_usuario, $usuario_parent, $usuario_rol, $usuario_activo, $usuario_codigo) 
    {        
        try 
        {
            $sql = "UPDATE usuarios SET usuario_nombres = ?, usuario_app = ?, usuario_apm = ?, usuario_email = ?, usuario_telefono = ?, usuario_direccion = ?, accion_fecha = ?, accion_usuario = ?, estructura_agencia_id = ?, usuario_rol = ?, usuario_activo = ? WHERE usuarios.usuario_id = ? "; 

            $consulta = $this->db->query($sql, array($usuario_nombres, $usuario_app, $usuario_apm, $usuario_email, $usuario_telefono, $usuario_direccion, $fecha_actual, $nombre_usuario, $usuario_parent, $usuario_rol, $usuario_activo, $usuario_codigo));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function RenovarPass($usuario_pass, $fecha_actual, $nombre_usuario, $usuario_codigo) 
    {        
        try 
        {
            $sql = "UPDATE usuarios SET usuario_pass = ?, usuario_fecha_ultimo_password = ?, usuario_password_reset = 0, accion_fecha = ?, accion_usuario = ? WHERE usuarios.usuario_id = ? "; 

            $consulta = $this->db->query($sql, array($usuario_pass, $fecha_actual, $fecha_actual, $nombre_usuario, $usuario_codigo));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function RestablecerPass($usuario_pass, $fecha_actual, $nombre_usuario, $usuario_codigo) 
    {        
        try 
        {
            $sql = "UPDATE usuarios SET usuario_pass = ?, usuario_password_reset = 1, accion_fecha = ?, accion_usuario = ? WHERE usuarios.usuario_id = ? "; 

            $consulta = $this->db->query($sql, array($usuario_pass, $fecha_actual, $nombre_usuario, $usuario_codigo));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerLista_Usuarios() 
    {        
        try 
        {
            $sql = "SELECT u.usuario_id, u.usuario_user, u.estructura_agencia_id, a.estructura_agencia_nombre, r.estructura_regional_nombre, u.usuario_pass, u.usuario_fecha_creacion, u.usuario_fecha_ultimo_acceso, u.usuario_fecha_ultimo_password, u.usuario_password_reset, u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email, u.usuario_telefono, u.usuario_direccion, u.usuario_rol, u.usuario_activo FROM usuarios u INNER JOIN estructura_agencia a ON a.estructura_agencia_id=u.estructura_agencia_id INNER JOIN estructura_regional r ON a.estructura_regional_id=r.estructura_regional_id ORDER BY usuario_user "; 

            $consulta = $this->db->query($sql);

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
    
    function VerDocumentoObservado($codigo_prospecto, $codigo_documento)
    {        
        try 
        {
            $sql = "SELECT obs_id FROM observacion_documento WHERE prospecto_id=? AND documento_id=? AND obs_estado=1 "; 

            $consulta = $this->db->query($sql, array($codigo_prospecto, $codigo_documento));

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
    
    /*************** LECTOR QR - INICIO ****************************/
    
    function ObtenerThemeQR($codigo_agencia)
    {
        try 
        {
            $sql = "SELECT c.comercio_theme_id, c.estructura_agencia_id, c.background_color, c.color_primary, c.color_secundary, c.button_background_color, c.button_text_color, c.url_web_view, c.titulo, c.comprobante_diseno, a.estructura_agencia_nombre 
                    FROM comercio_theme c
                    INNER JOIN estructura_agencia a ON a.estructura_agencia_id=c.estructura_agencia_id
                    WHERE c.estructura_agencia_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_agencia));

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
    
    function ListaCategoriaQR($codigo_agencia, $codigo_categoria)
    {        
        try 
        {
            $condicion = '';
            
            if($codigo_agencia != -1)
            {
                $condicion = 'AND estructura_agencia_id=' . $codigo_agencia;
            }
            
            if($codigo_categoria == -1)
            {
                $sql = "SELECT categoria_qr_id, estructura_agencia_id, categoria_qr_nombre, categoria_qr_tipo, categoria_qr_plantilla_ok, categoria_qr_plantilla_error, categoria_qr_plantilla_notfound FROM categoria_qr WHERE 1 $condicion "; 
            }
            else
            {
                $sql = "SELECT categoria_qr_id, estructura_agencia_id, categoria_qr_nombre, categoria_qr_tipo, categoria_qr_plantilla_ok, categoria_qr_plantilla_error, categoria_qr_plantilla_notfound FROM categoria_qr WHERE categoria_qr_id=? $condicion "; 
            }
            $consulta = $this->db->query($sql, array($codigo_categoria));

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
    
    function ListaRegistroQR($codigo_registro, $codigo_categoria)
    {        
        try 
        {
            
            $condicion = '';
            
            if($codigo_categoria != -1)
            {
                $condicion = 'AND categoria_qr_id=' . $codigo_categoria;
            }
            
            if($codigo_registro == -1)
            {
                $sql = "SELECT registro_qr_id, categoria_qr_id, registro_qr_codigo, registro_qr_detalle, registro_qr_checkin FROM registro_qr WHERE 1 $condicion "; 
            }
            else
            {
                $sql = "SELECT registro_qr_id, categoria_qr_id, registro_qr_codigo, registro_qr_detalle, registro_qr_checkin FROM registro_qr WHERE registro_qr_codigo=? $condicion "; 
            }
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
    
    function ActualizarRegistroQR($codigo_registro) 
    {        
        try 
        {
            $sql = "UPDATE registro_qr SET registro_qr_checkin = 1 WHERE registro_qr_codigo = ? "; 

            $consulta = $this->db->query($sql, array($codigo_registro));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarRegistroQR($categoria_qr_id, $registro_qr_codigo, $registro_qr_detalle, $registro_qr_checkin) 
    {        
        try 
        {
            $sql = "INSERT INTO registro_qr(categoria_qr_id, registro_qr_codigo, registro_qr_detalle, registro_qr_checkin) VALUES (?, ?, ?, ?) "; 

            $consulta = $this->db->query($sql, array($categoria_qr_id, $registro_qr_codigo, $registro_qr_detalle, $registro_qr_checkin));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    
    /*************** LECTOR QR - FIN ****************************/
    
    // Control de Cambio 04/07/2018 - Nuevo Módulo GESTIÓN DE PROSPECTOS (DOCUMENTO) Y EMPRESA
    
    function ObtenerDatosEmpresa($codigo_empresa) 
    {        
        $criterio = '';
        
        if($codigo_empresa != -1)
        {
            $criterio = 'AND empresa_id= ' . $codigo_empresa;
        }
        
        try 
        {
            $sql = "SELECT empresa_id, ejecutivo_id,
                    CASE empresa_categoria
                            WHEN 1 then IF(STRCMP(empresa_nombre_fantasia, '') = 0, empresa_nombre_legal, empresa_nombre_fantasia) 
                            WHEN 2 then empresa_nombre_establecimiento
                    END AS 'empresa_nombre',
                    CASE empresa_categoria
                            WHEN 1 then 'Comercio'
                            WHEN 2 then 'Establecimiento'
                    END AS 'empresa_categoria',
                    empresa_consolidada, empresa_categoria AS 'empresa_categoria_codigo', empresa_depende, empresa_nit, empresa_adquiriente, empresa_tipo_sociedad, empresa_nombre_referencia, empresa_nombre_legal, empresa_nombre_fantasia, empresa_rubro, empresa_perfil_comercial, empresa_mcc, empresa_nombre_establecimiento, empresa_denominacion_corta, empresa_ha_desde, empresa_ha_hasta, empresa_dias_atencion, empresa_medio_contacto, empresa_email, empresa_dato_contacto, empresa_departamento, empresa_municipio, empresa_zona, empresa_tipo_calle, empresa_calle, empresa_numero, empresa_direccion_literal, empresa_direccion_geo, empresa_info_adicional, accion_usuario, accion_fecha FROM empresa WHERE empresa_id>0 AND empresa_consolidada>=0 $criterio "; 

            $consulta = $this->db->query($sql, array($codigo_empresa));

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
    
    function UpdateEmpresaComercio($ejecutivo_id, $empresa_nit, $empresa_tipo_sociedad, $empresa_nombre_referencia, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_info_adicional, $accion_usuario, $accion_fecha, $empresa_id)
    {        
        try 
        {
            $sql = "UPDATE empresa SET ejecutivo_id=?, empresa_nit=?, empresa_tipo_sociedad=?, empresa_nombre_referencia=?, empresa_nombre_legal=?, empresa_nombre_fantasia=?, empresa_rubro=?, empresa_perfil_comercial=?, empresa_mcc=?, empresa_ha_desde=?, empresa_ha_hasta=?, empresa_dias_atencion=?, empresa_medio_contacto=?, empresa_email=?, empresa_dato_contacto=?, empresa_departamento=?, empresa_municipio=?, empresa_zona=?, empresa_tipo_calle=?, empresa_calle=?, empresa_numero=?, empresa_direccion_literal=?, empresa_info_adicional=?, accion_usuario=?, accion_fecha=? WHERE empresa_id=? "; 
			
            $consulta = $this->db->query($sql, array($ejecutivo_id, $empresa_nit, $empresa_tipo_sociedad, $empresa_nombre_referencia, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_info_adicional, $accion_usuario, $accion_fecha, $empresa_id));
			
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateEmpresaEstablecimiento($ejecutivo_id, $empresa_nombre_establecimiento, $empresa_denominacion_corta, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_info_adicional, $accion_usuario, $accion_fecha, $empresa_id)
    {        
        try 
        {
            $sql = "UPDATE empresa SET ejecutivo_id=?, empresa_nombre_establecimiento=?, empresa_denominacion_corta=?, empresa_ha_desde=?, empresa_ha_hasta=?, empresa_dias_atencion=?, empresa_medio_contacto=?, empresa_email=?, empresa_dato_contacto=?, empresa_departamento=?, empresa_municipio=?, empresa_zona=?, empresa_tipo_calle=?, empresa_calle=?, empresa_numero=?, empresa_direccion_literal=?, empresa_info_adicional=?, accion_usuario=?, accion_fecha=? WHERE empresa_id=? "; 
			
            $consulta = $this->db->query($sql, array($ejecutivo_id, $empresa_nombre_establecimiento, $empresa_denominacion_corta, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_info_adicional, $accion_usuario, $accion_fecha, $empresa_id));
			
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertEmpresa($empresa_nit, $ejecutivo_id, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO empresa (empresa_nit, ejecutivo_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) ";
			
            $consulta = $this->db->query($sql, array($empresa_nit, $ejecutivo_id, $accion_usuario, $accion_fecha));
			
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateEmpresaGeo($empresa_direccion_geo, $accion_usuario, $accion_fecha, $empresa_id)
    {        
        try 
        {
            $sql = "UPDATE empresa SET empresa_direccion_geo=?, accion_usuario=?, accion_fecha=? WHERE empresa_id=? "; 
			
            $consulta = $this->db->query($sql, array($empresa_direccion_geo, $accion_usuario, $accion_fecha, $empresa_id));
			
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerDatosGeoEmpresa($lista) 
    {
        try 
        {
            $sql = "SELECT empresa_id,
                    CASE empresa_categoria
                            WHEN 1 then IF(STRCMP(empresa_nombre_fantasia, '') = 0, empresa_nombre_legal, empresa_nombre_fantasia) 
                            WHEN 2 then empresa_nombre_establecimiento
                    END AS 'empresa_nombre',
                    CASE empresa_categoria
                            WHEN 1 then 'Comercio'
                            WHEN 2 then 'Establecimiento'
                    END AS 'empresa_categoria',
                    empresa_direccion_geo
                    FROM empresa WHERE empresa_id IN ($lista) "; 

            $consulta = $this->db->query($sql, array($lista));

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
    
    function ObtenerNombreEmpresaProspecto($prospecto_id) 
    {
        try 
        {
            $sql = "SELECT e.empresa_id,
                    CASE e.empresa_categoria
                                    WHEN 1 then IF(STRCMP(e.empresa_nombre_fantasia, '') = 0, e.empresa_nombre_legal, e.empresa_nombre_fantasia) 
                                    WHEN 2 then e.empresa_nombre_establecimiento
                    END AS 'empresa_nombre',
                    CASE e.empresa_categoria
                                    WHEN 1 then 'Comercio'
                                    WHEN 2 then 'Establecimiento'
                    END AS 'empresa_categoria'
                    FROM prospecto p 
                    INNER JOIN empresa e ON e.empresa_id=p.empresa_id
                    WHERE p.prospecto_id=? "; 

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    /* ********************************** */

    // FORMULARIOS DINÁMICOS
    
    function UpdateTipoPersonaProspecto($empresa_id, $empresa_geo, $tipo_persona_id, $prospecto_acciones, $prospecto_observaciones, $prospecto_reverificacion, $prospecto_refecha, $prospecto_firma, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET tipo_persona_id=?, prospecto_acciones=?, prospecto_observaciones=?, prospecto_reverificacion=?, prospecto_refecha=?, prospecto_firma=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
			
            $consulta = $this->db->query($sql, array($tipo_persona_id, $prospecto_acciones, $prospecto_observaciones, $prospecto_reverificacion, $prospecto_refecha, $prospecto_firma, $accion_usuario, $accion_fecha, $prospecto_id));

            $sql2 = "UPDATE empresa SET empresa_direccion_geo=? WHERE empresa_id=? "; 
			
            $consulta2 = $this->db->query($sql2, array($empresa_geo, $empresa_id));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    /* ********************************** */
    
    // Tipos de Campana
    
    function ObtenerTipoCampana($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT camtip_id, camtip_nombre, camtip_desc, accion_usuario, accion_fecha FROM campana_tipo "; 
            }
            else
            {
                $sql = "SELECT camtip_id, camtip_nombre, camtip_desc, accion_usuario, accion_fecha FROM campana_tipo WHERE camtip_id=? "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    // Campañas
    
    function ObtenerCampana($codigo, $filto=' WHERE c.camp_id IN (1,2,3,4)')
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT c.camp_color, c.camp_fecha_inicio, c.camp_id, c.camtip_id, ct.camtip_nombre, c.camp_nombre, c.camp_desc, c.camp_plazo, c.camp_monto_oferta, c.camp_tasa, c.accion_usuario, c.accion_fecha FROM campana c INNER JOIN campana_tipo ct ON ct.camtip_id=c.camtip_id $filto "; 
            }
            else
            {
                $sql = "SELECT c.camp_color, c.camp_fecha_inicio, c.camp_id, c.camtip_id, ct.camtip_nombre, c.camp_nombre, c.camp_desc, c.camp_plazo, c.camp_monto_oferta, c.camp_tasa, c.accion_usuario, c.accion_fecha FROM campana c INNER JOIN campana_tipo ct ON ct.camtip_id=c.camtip_id WHERE c.camp_id=? "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function UpdateCampana($camtip_id, $camp_nombre, $camp_desc, $camp_plazo, $camp_monto_oferta, $camp_tasa, $camp_fecha_inicio, $camp_color, $accion_usuario, $accion_fecha, $camp_id)
    {        
        try 
        {
            $sql = "UPDATE campana SET camtip_id=?, camp_nombre=?, camp_desc=?, camp_plazo=?, camp_monto_oferta=?, camp_tasa=?, camp_fecha_inicio=?, camp_color=?, accion_usuario=?, accion_fecha=? WHERE camp_id=? "; 
            
            $consulta = $this->db->query($sql, array($camtip_id, $camp_nombre, $camp_desc, $camp_plazo, $camp_monto_oferta, $camp_tasa, $camp_fecha_inicio, $camp_color, $accion_usuario, $accion_fecha, $camp_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertCampana($camtip_id, $camp_nombre, $camp_desc, $camp_plazo, $camp_monto_oferta, $camp_tasa, $camp_fecha_inicio, $camp_color, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO campana(camtip_id, camp_nombre, camp_desc, camp_plazo, camp_monto_oferta, camp_tasa, camp_fecha_inicio, camp_color, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($camtip_id, $camp_nombre, $camp_desc, $camp_plazo, $camp_monto_oferta, $camp_tasa, $camp_fecha_inicio, $camp_color, $accion_usuario, $accion_fecha));
            
            $listaResultados = $this->db->insert_id();
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    // -- Campañas de un Agente
    
    function ObtenerCampanaAgente($codigo_agente)
    {        
        try 
        {
            $sql = "SELECT camp_id FROM prospecto WHERE ejecutivo_id=? GROUP BY camp_id "; 

            $consulta = $this->db->query($sql, array($codigo_agente));

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
    
    // Verificar si existe un valor dado en una tabla dada
    
    function VerificaTablaCampo($tabla, $campo, $valor)
    {        
        try 
        {
            $sql = "SELECT $campo FROM $tabla WHERE $campo=? ";
            
            $consulta = $this->db->query($sql, array($valor));

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
    
    function ObtenerCodigoEtapaNombre($nombre_etapa)
    {        
        try 
        {
            $sql = "SELECT etapa_id, etapa_nombre FROM etapa WHERE UPPER(REPLACE(etapa_nombre, ' ', ''))=UPPER(REPLACE(?, ' ', '')) ";
            
            $consulta = $this->db->query($sql, array($nombre_etapa));

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
    
    function ObtenerCodigoCampanaNombre($nombre_campana)
    {        
        try 
        {
            $sql = "SELECT camp_id, camp_nombre FROM campana WHERE UPPER(REPLACE(camp_nombre, ' ', ''))=UPPER(REPLACE(?, ' ', '')) ";
            
            $consulta = $this->db->query($sql, array($nombre_campana));

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
    
    function ObtenerCodigoEjecutivoUsuario($matricula)
    {        
        try 
        {
            $sql = "SELECT e.ejecutivo_id, u.usuario_id FROM ejecutivo e INNER JOIN usuarios u ON u.usuario_id=e.usuario_id WHERE u.usuario_user=? ";
            
            $consulta = $this->db->query($sql, array($matricula));

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
    
    function InsertarLead_APP($prospecto_codigo_cliente, $prospecto_mensaje, $ejecutivo_id, $tipo_persona_id, $empresa_id, $camp_id, $prospecto_fecha_asignacion, $prospecto_idc, $prospecto_nombre_cliente, $prospecto_empresa, $prospecto_ingreso, $prospecto_direccion, $prospecto_direccion_geo, $prospecto_telefono, $prospecto_celular, $prospecto_email, $prospecto_tipo_lead, $prospecto_matricula, $prospecto_comentario, $nombre_usuario, $fecha_actual)
    {        
        try 
        {            
            $sql = "INSERT INTO prospecto(prospecto_codigo_cliente, prospecto_mensaje, ejecutivo_id, tipo_persona_id, empresa_id, camp_id, prospecto_fecha_asignacion, prospecto_idc, prospecto_nombre_cliente, prospecto_empresa, prospecto_ingreso, prospecto_direccion, prospecto_direccion_geo, prospecto_telefono, prospecto_celular, prospecto_email, prospecto_tipo_lead, prospecto_matricula, prospecto_comentario, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";

            $this->db->query($sql, array($prospecto_codigo_cliente, $prospecto_mensaje, $ejecutivo_id, $tipo_persona_id, $empresa_id, $camp_id, $prospecto_fecha_asignacion, $prospecto_idc, $prospecto_nombre_cliente, $prospecto_empresa, $prospecto_ingreso, $prospecto_direccion, $prospecto_direccion_geo, $prospecto_telefono, $prospecto_celular, $prospecto_email, $prospecto_tipo_lead, $prospecto_matricula, $prospecto_comentario, $nombre_usuario, $fecha_actual));

            $listaResultados = $this->db->insert_id();
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

            return $listaResultados;
    }
    
    function UpdateLead_APP($prospecto_idc, $prospecto_nombre_cliente, $prospecto_empresa, $prospecto_ingreso, $prospecto_direccion, $prospecto_direccion_geo, $prospecto_telefono, $prospecto_celular, $prospecto_email, $prospecto_fecha_contacto1, $prospecto_comentario, $accion_usuario, $accion_fecha, $codigo_registro)
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET prospecto_idc = ?, prospecto_nombre_cliente = ?, prospecto_empresa = ?, prospecto_ingreso = ?, prospecto_direccion = ?, prospecto_direccion_geo = ?, prospecto_telefono = ?, prospecto_celular = ?, prospecto_email = ?, prospecto_fecha_contacto1 = ?, prospecto_comentario = ?, accion_usuario = ?, accion_fecha = ? WHERE prospecto_id = ?";

            $this->db->query($sql, array($prospecto_idc, $prospecto_nombre_cliente, $prospecto_empresa, $prospecto_ingreso, $prospecto_direccion, $prospecto_direccion_geo, $prospecto_telefono, $prospecto_celular, $prospecto_email, $prospecto_fecha_contacto1, $prospecto_comentario, $accion_usuario, $accion_fecha, $codigo_registro));

            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

    }
    
    function UpdateEstadoLead_APP($prospecto_etapa, $prospecto_monto_aprobacion, $prospecto_monto_desembolso, $prospecto_fecha_desembolso, $accion_usuario, $accion_fecha, $codigo_registro)
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET prospecto_etapa = ?, prospecto_monto_aprobacion = ?, prospecto_monto_desembolso = ?, prospecto_fecha_desembolso = ?, accion_usuario = ?, accion_fecha = ? WHERE prospecto_id = ?";

            $this->db->query($sql, array($prospecto_etapa, $prospecto_monto_aprobacion, $prospecto_monto_desembolso, $prospecto_fecha_desembolso, $accion_usuario, $accion_fecha, $codigo_registro));

            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

    }
    
    /*************** REGIONALIZACIÓN - INICIO ****************************/
    
    function ObtenerDatosUsuarioRegion($usuario_codigo, $region_codigo)
    {        
        try 
        {
            $sql = "SELECT region_asignado_id, usuario_id, estructura_regional_id, accion_usuario, accion_fecha FROM region_asignado WHERE usuario_id=? AND estructura_regional_id=? "; 

            $consulta = $this->db->query($sql, array($usuario_codigo, $region_codigo));

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
    
    function EliminarRegionUsuario($usuario_codigo)
    {        
        try 
        {
            $sql = "DELETE FROM region_asignado WHERE usuario_id=? "; 

            $consulta = $this->db->query($sql, array($usuario_codigo));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarRegionUsuario($usuario_codigo, $region_codigo, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO region_asignado(usuario_id, estructura_regional_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) "; 

            $consulta = $this->db->query($sql, array($usuario_codigo, $region_codigo, $accion_usuario, $accion_fecha));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function VerificaProspectoRegion($prospecto_codigo, $region_codigo)
    {        
        try 
        {
            $sql = "SELECT p.prospecto_id
                    FROM prospecto p
                    WHERE p.prospecto_id=? AND p.codigo_agencia_fie IN (" . $region_codigo . ")"; 

            $consulta = $this->db->query($sql, array($prospecto_codigo, $region_codigo));

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
    
    function VerificaEjecutivoRegion($codigo, $region_codigo)
    {        
        try 
        {
            $sql = "SELECT e.ejecutivo_id
                    FROM ejecutivo e
                    INNER JOIN usuarios u ON e.usuario_id=u.usuario_id
                    INNER JOIN estructura_agencia ea ON u.estructura_agencia_id=ea.estructura_agencia_id
                    INNER JOIN estructura_regional er ON ea.estructura_regional_id=er.estructura_regional_id
                    WHERE e.ejecutivo_id=? AND er.estructura_regional_id IN (" . $region_codigo . ")"; 

            $consulta = $this->db->query($sql, array($codigo, $region_codigo));

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
    
    function VerificaUsuarioRegion($codigo, $region_codigo)
    {        
        try 
        {
            $sql = "SELECT u.usuario_id
                    FROM usuarios u
                    INNER JOIN estructura_agencia ea ON u.estructura_agencia_id=ea.estructura_agencia_id
                    INNER JOIN estructura_regional er ON ea.estructura_regional_id=er.estructura_regional_id
                    WHERE u.usuario_id=? AND er.estructura_regional_id IN (" . $region_codigo . ")"; 

            $consulta = $this->db->query($sql, array($codigo, $region_codigo));

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
    
    function ObtenerTercerosRegion($terceros_codigo)
    {        
        try 
        {
            $sql = "SELECT codigo_agencia_fie FROM terceros WHERE terceros_id=? "; 

            $consulta = $this->db->query($sql, array($terceros_codigo));

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
    
    function ObtenerProspectoRegion($prospecto_codigo)
    {        
        try 
        {
            $sql = "SELECT er.estructura_regional_nombre, er.estructura_regional_id, er.estructura_regional_estado
                    FROM prospecto p
                    INNER JOIN estructura_regional er ON er.estructura_regional_id=p.codigo_agencia_fie
                    WHERE p.prospecto_id=?"; 

            $consulta = $this->db->query($sql, array($prospecto_codigo));

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
    
    function ObtenerMantenimientoRegion($mantenimiento_codigo)
    {        
        try 
        {
            $sql = "SELECT er.estructura_regional_nombre
                    FROM mantenimiento m
                    INNER JOIN ejecutivo e ON m.ejecutivo_id=e.ejecutivo_id
                    INNER JOIN usuarios u ON e.usuario_id=u.usuario_id
                    INNER JOIN estructura_agencia ea ON u.estructura_agencia_id=ea.estructura_agencia_id
                    INNER JOIN estructura_regional er ON ea.estructura_regional_id=er.estructura_regional_id
                    WHERE m.mant_id=?"; 

            $consulta = $this->db->query($sql, array($mantenimiento_codigo));

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
    
    function ObtenerEjecutivoRegion($ejecutivo_codigo)
    {        
        try 
        {
            $sql = "SELECT er.estructura_regional_nombre
                    FROM ejecutivo e
                    INNER JOIN usuarios u ON e.usuario_id=u.usuario_id
                    INNER JOIN estructura_agencia ea ON u.estructura_agencia_id=ea.estructura_agencia_id
                    INNER JOIN estructura_regional er ON ea.estructura_regional_id=er.estructura_regional_id
                    WHERE e.ejecutivo_id=?"; 

            $consulta = $this->db->query($sql, array($ejecutivo_codigo));

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
    
    function ObtenerListaPerfilApp($perfil_app_id)
    {        
        try 
        {   
            if($perfil_app_id == -1)
            {
                $sql = "SELECT perfil_app_id, perfil_app_nombre, accion_usuario, accion_fecha FROM perfil_app ORDER BY perfil_app_id ";
            }
            else
            {
                $sql = "SELECT perfil_app_id, perfil_app_nombre, accion_usuario, accion_fecha FROM perfil_app WHERE perfil_app_id=? ORDER BY perfil_app_id ";
            }
            
            $consulta = $this->db->query($sql, array($perfil_app_id));

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
    
    function ObtenerListaPerfilAppActivo($perfil_app_id)
    {        
        try 
        {   
            if($perfil_app_id == -1)
            {
                $sql = "SELECT perfil_app_id, perfil_app_nombre, accion_usuario, accion_fecha FROM perfil_app WHERE perfil_app_id>0 ORDER BY perfil_app_id ";
            }
            else
            {
                $sql = "SELECT perfil_app_id, perfil_app_nombre, accion_usuario, accion_fecha FROM perfil_app WHERE perfil_app_id AND perfil_app_id=? ORDER BY perfil_app_id ";
            }
            
            $consulta = $this->db->query($sql, array($perfil_app_id));

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
    
    function ObtenerListaPerfilAppProspecto($perfil_app_id)
    {        
        try 
        {   
            if($perfil_app_id == -1)
            {
                $sql = "SELECT perfil_app_id, perfil_app_nombre, accion_usuario, accion_fecha FROM perfil_app WHERE perfil_app_id>0 AND perfil_app_prospecto=1 ORDER BY perfil_app_id ";
            }
            else
            {
                $sql = "SELECT perfil_app_id, perfil_app_nombre, accion_usuario, accion_fecha FROM perfil_app WHERE perfil_app_id AND perfil_app_id=? AND perfil_app_prospecto=1 ORDER BY perfil_app_id ";
            }
            
            $consulta = $this->db->query($sql, array($perfil_app_id));

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
    
    function ObtenerUsuariosRegionNotificar($lista_rol, $lista_usuario, $prospecto_region)
    {        
        try 
        {   
            $sql = "SELECT u.usuario_id, ro.rol_id, r.estructura_regional_id, r.estructura_regional_nombre, u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email 
                    FROM usuarios u 
                    INNER JOIN rol ro ON ro.rol_id=u.usuario_rol
                    INNER JOIN estructura_agencia a ON a.estructura_agencia_id=u.estructura_agencia_id 
                    INNER JOIN estructura_regional r ON a.estructura_regional_id=r.estructura_regional_id 
                    WHERE (ro.rol_id IN (" . $lista_rol . ") OR u.usuario_id IN (" . $lista_usuario . ")) AND u.usuario_activo>0"; 

            $consulta = $this->db->query($sql, array($prospecto_region));

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
    
    function ObtenerDatosRegionalFiltrado($codigo)
    {        
        try 
        {
            $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
            
            if($codigo == -1)
            {
                $sql = "SELECT r.estructura_regional_id, r.estructura_entidad_id AS 'parent_id', e.estructura_entidad_nombre AS 'parent_detalle', r.estructura_regional_nombre, r.accion_usuario, r.accion_fecha FROM estructura_regional r INNER JOIN estructura_entidad e ON e.estructura_entidad_id=r.estructura_entidad_id WHERE r.estructura_regional_id IN (" . $lista_region->region_id . ") "; 
            }
            else
            {
                $sql = "SELECT r.estructura_regional_id, r.estructura_entidad_id AS 'parent_id', e.estructura_entidad_nombre AS 'parent_detalle', r.estructura_regional_nombre, r.accion_usuario, r.accion_fecha FROM estructura_regional r INNER JOIN estructura_entidad e ON e.estructura_entidad_id=r.estructura_entidad_id WHERE estructura_regional_id=? "; 
            }

            $consulta = $this->db->query($sql, array($codigo));

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
    
    /*************** REGIONALIZACIÓN - FIN ****************************/
    
    /*************** UPDATE FORMULARIOS FIE - INICIO ****************************/
    
    function baja_unidad_familiar(
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            )
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET
                
                        prospecto_activo = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE prospecto_id = ?";

            $this->db->query($sql, array(
                            0,
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
    
    function marcar_actividad_principal(
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id,
                            $principal_id
                            )
    {        
        try 
        {
            // 1. Colocar a todos los leads asociados como no principales
            
            $sql1 = "UPDATE prospecto SET
                
                        prospecto_principal = 0
                    
                    WHERE prospecto_id=? OR general_depende=?";

            $this->db->query($sql1, array(
                            $principal_id,
                            $principal_id
                            ));
            
            // 2. Marcar el registro específico como principal
            
            $sql2 = "UPDATE prospecto SET
                
                        prospecto_principal = 1,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE prospecto_id = ?";

            $this->db->query($sql2, array(
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
    
    function marcar_evaluacion_lead(
                            $codigo_evaluacion,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            )
    {        
        try 
        {
            $sql1 = "UPDATE prospecto SET
                
                        prospecto_evaluacion = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE prospecto_id=? ";

            $this->db->query($sql1, array(
                            $codigo_evaluacion,
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

    function ObtenerListaVersiones($codigo_prospecto, $codigo_version)
    {        
        try 
        {
            if($codigo_version == -1)
            {
                $sql = "SELECT v.version_id, v.prospecto_id, v.version_contenido, v.version_hash, v.accion_usuario, v.accion_fecha, CONCAT_WS(' ', u.usuario_nombres, u.usuario_app, u.usuario_apm) as 'usuario_nombre', u.usuario_id
                        FROM version v
                        INNER JOIN usuarios u ON u.usuario_user=v.accion_usuario
                        WHERE v.prospecto_id=? ORDER BY v.version_id ASC ";
            }
            else
            {
                $sql = "SELECT v.version_id, v.prospecto_id, v.version_contenido, v.version_hash, v.accion_usuario, v.accion_fecha, CONCAT_WS(' ', u.usuario_nombres, u.usuario_app, u.usuario_apm) as 'usuario_nombre', u.usuario_id
                        FROM version v
                        INNER JOIN usuarios u ON u.usuario_user=v.accion_usuario
                        WHERE v.prospecto_id=? AND v.version_id=? ORDER BY v.version_id ASC ";
            }

            $consulta = $this->db->query($sql, array($codigo_prospecto, $codigo_version));

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
    
    function crear_version_lead(
                            $estructura_id,
                            $version_contenido,
                            $version_hash,
                            $accion_usuario,
                            $accion_fecha
                            )
    {        
        try 
        {
            $sql1 = "INSERT INTO version
                
                        (prospecto_id, 
                        version_contenido, 
                        version_hash, 
                        accion_usuario,
                        accion_fecha)
                        
                    VALUES
                    
                        (?,
                        ?,
                        ?,
                        ?,
                        ?
                        )";

            $this->db->query($sql1, array(
                            $estructura_id,
                            $version_contenido,
                            $version_hash,
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
    
    function crear_key_registro(
                            $key_key, 
                            $estructura_id, 
                            $codigo_ejecutivo, 
                            $tipo_registro
                            )
    {        
        try 
        {
            $sql1 = "INSERT INTO registro_keys
                
                        (key_key, 
                        estructura_id, 
                        codigo_ejecutivo, 
                        tipo_registro)
                        
                    VALUES
                    
                        (?,
                        ?,
                        ?,
                        ?
                        )";

            $this->db->query($sql1, array(
                            $key_key, 
                            $estructura_id, 
                            $codigo_ejecutivo, 
                            $tipo_registro
                            ));

            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

    }
    
    function obtener_key_registro($key_key)
    {        
        try 
        {
            // Paso 1: Se busca el registro por la Llave y se captura el valor
            
            $sql1 = "SELECT key_id, key_key, estructura_id, codigo_ejecutivo, tipo_registro FROM registro_keys WHERE key_key=? ";
            $consulta1 = $this->db->query($sql1, array($key_key));
            
            $listaResultados = $consulta1->result_array();
            
            // Paso 2: Se elimina el registro por la Llave
            
            $sql2 = "DELETE FROM registro_keys WHERE key_key=? ";
            $consulta2 = $this->db->query($sql2, array($key_key));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function insert_datos_generales_familiar(
                            $codigo_rubro,
                            $ejecutivo_id,
                            $general_solicitante,
                            $general_telefono,
                            $general_email,
                            $general_direccion,
                            $general_actividad,
                            $general_ci,
                            $general_ci_extension,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            )
    {        
        try 
        {            
            $sql = "INSERT INTO prospecto
                
                        (camp_id, 
                        tipo_persona_id, 
                        empresa_id,
                        general_categoria,
                        ejecutivo_id,
                        general_depende, 
                        general_solicitante,
                        general_telefono,
                        general_email,
                        general_direccion,
                        general_actividad,
                        general_ci,
                        general_ci_extension,
                        accion_usuario,
                        accion_fecha,
                        prospecto_fecha_asignacion)
                        
                    VALUES
                    
                        (?,
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
                            $codigo_rubro,
                            0,
                            -1,
                            2,
                            $ejecutivo_id,
                            $estructura_id,
                            $general_solicitante,
                            $general_telefono,
                            $general_email,
                            $general_direccion,
                            $general_actividad,
                            $general_ci,
                            $general_ci_extension,
                            $accion_usuario,
                            $accion_fecha,
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
    
    function update_datos_generales(
                            $general_solicitante,
                            $general_telefono,
                            $general_email,
                            $general_direccion,
                            $general_actividad,
                            $general_ci,
                            $general_ci_extension,
                            $general_destino,
                            $general_comentarios,
                            $general_interes,
                            $operacion_efectivo,
                            $operacion_dias,
                            $operacion_antiguedad_ano,
                            $operacion_antiguedad_mes,
                            $operacion_tiempo_ano,
                            $operacion_tiempo_mes,
                            $aclarar_contado,
                            $aclarar_credito,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            )
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET
                
                        general_solicitante = ?,
                        general_telefono = ?,
                        general_email = ?,
                        general_direccion =?,
                        general_actividad = ?,
                        general_ci = ?,
                        general_ci_extension = ?,
                        general_destino = ?,
                        general_comentarios = ?,
                        general_interes = ?,
                        operacion_efectivo = ?,
                        operacion_dias = ?,
                        operacion_antiguedad_ano = ?,
                        operacion_antiguedad_mes = ?,
                        operacion_tiempo_ano = ?,
                        operacion_tiempo_mes = ?,
                        aclarar_contado = ?,
                        aclarar_credito = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE prospecto_id = ?";

            $this->db->query($sql, array(
                            $general_solicitante,
                            $general_telefono,
                            $general_email,
                            $general_direccion,
                            $general_actividad,
                            $general_ci,
                            $general_ci_extension,
                            $general_destino,
                            $general_comentarios,
                            $general_interes,
                            $operacion_efectivo,
                            $operacion_dias,
                            $operacion_antiguedad_ano,
                            $operacion_antiguedad_mes,
                            $operacion_tiempo_ano,
                            $operacion_tiempo_mes,
                            $aclarar_contado,
                            $aclarar_credito,
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
    
    // Calculadora Financiera INICIO
    
    function select_calculadora_financiera($prospecto_id)
    {        
        try 
        {            
            $sql = "SELECT 
                        ejecutivo_id,
                        general_categoria,
                        cuota_tasa, 
                        cuota_periodicidad, 
                        cuota_plazo_meses, 
                        cuota_meses_gracia, 
                        cuota_tipo, 
                        cuota_seguro, 
                        cuota_seguro_nro_personas, 
                        cuota_seguro_tasa
                    
                    FROM prospecto WHERE prospecto_id = ?";

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    // Calculadora Financiera FIN
    
    function select_datos_generales($prospecto_id)
    {        
        try 
        {            
            $sql = "SELECT 
                        ejecutivo_id,
                        general_categoria,
                        general_solicitante,
                        general_telefono,
                        general_email,
                        general_direccion,
                        general_actividad,
                        general_ci,
                        general_ci_extension,
                        general_destino,
                        general_comentarios,
                        general_interes,
                        operacion_efectivo,
                        operacion_dias,
                        operacion_antiguedad,
                        operacion_tiempo,
                        aclarar_contado,
                        aclarar_credito,
                        operacion_criterio,
                        operacion_antiguedad_ano,
                        operacion_antiguedad_mes,
                        operacion_tiempo_ano,
                        operacion_tiempo_mes,
                        onboarding
                    
                    FROM prospecto WHERE prospecto_id = ?";

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    function update_frecuencia_venta(
                            $frec_seleccion,
                            $frec_dia_lunes,
                            $frec_dia_martes,
                            $frec_dia_miercoles,
                            $frec_dia_jueves,
                            $frec_dia_viernes,
                            $frec_dia_sabado,
                            $frec_dia_domingo,
                            $frec_dia_semana_sel,
                            $frec_dia_semana_sel_brm,
                            $frec_dia_semana_monto2,
                            $frec_dia_semana_monto3,
                            $frec_dia_eval_semana1_brm,
                            $frec_dia_eval_semana2_brm,
                            $frec_dia_eval_semana3_brm,
                            $frec_dia_eval_semana4_brm,
                            $frec_sem_semana1_monto,
                            $frec_sem_semana2_monto,
                            $frec_sem_semana3_monto,
                            $frec_sem_semana4_monto,
                            $frec_mes_sel,
                            $frec_mes_mes1_monto,
                            $frec_mes_mes2_monto,
                            $frec_mes_mes3_monto,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            )
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET
                
                        frec_seleccion = ?,
                        frec_dia_lunes = ?,
                        frec_dia_martes = ?,
                        frec_dia_miercoles = ?,
                        frec_dia_jueves = ?,
                        frec_dia_viernes = ?,
                        frec_dia_sabado = ?,
                        frec_dia_domingo = ?,
                        frec_dia_semana_sel = ?,
                        frec_dia_semana_sel_brm = ?,
                        frec_dia_semana_monto2 = ?,
                        frec_dia_semana_monto3 = ?,
                        frec_dia_eval_semana1_brm = ?,
                        frec_dia_eval_semana2_brm = ?,
                        frec_dia_eval_semana3_brm = ?,
                        frec_dia_eval_semana4_brm = ?,
                        frec_sem_semana1_monto = ?,
                        frec_sem_semana2_monto = ?,
                        frec_sem_semana3_monto = ?,
                        frec_sem_semana4_monto = ?,
                        frec_mes_sel = ?,
                        frec_mes_mes1_monto = ?,
                        frec_mes_mes2_monto = ?,
                        frec_mes_mes3_monto = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE prospecto_id = ?";

            $this->db->query($sql, array(
                            $frec_seleccion,
                            $frec_dia_lunes,
                            $frec_dia_martes,
                            $frec_dia_miercoles,
                            $frec_dia_jueves,
                            $frec_dia_viernes,
                            $frec_dia_sabado,
                            $frec_dia_domingo,
                            $frec_dia_semana_sel,
                            $frec_dia_semana_sel_brm,
                            $frec_dia_semana_monto2,
                            $frec_dia_semana_monto3,
                            $frec_dia_eval_semana1_brm,
                            $frec_dia_eval_semana2_brm,
                            $frec_dia_eval_semana3_brm,
                            $frec_dia_eval_semana4_brm,
                            $frec_sem_semana1_monto,
                            $frec_sem_semana2_monto,
                            $frec_sem_semana3_monto,
                            $frec_sem_semana4_monto,
                            $frec_mes_sel,
                            $frec_mes_mes1_monto,
                            $frec_mes_mes2_monto,
                            $frec_mes_mes3_monto,
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
    
    function select_frecuencia_venta($prospecto_id)
    {        
        try 
        {            
            $sql = "SELECT 
                        prospecto_id,
                        prospecto_principal,
                        camp_id, 
                        porcentaje_participacion_proveedores, 
                        frec_seleccion, 
                        frec_dia_lunes, 
                        frec_dia_martes, 
                        frec_dia_miercoles, 
                        frec_dia_jueves, 
                        frec_dia_viernes, 
                        frec_dia_sabado, 
                        frec_dia_domingo, 
                        frec_dia_semana_sel,
                        frec_dia_semana_sel_brm, 
                        frec_dia_semana_monto2, 
                        frec_dia_semana_monto3, 
                        frec_dia_eval_semana1_brm, 
                        frec_dia_eval_semana2_brm, 
                        frec_dia_eval_semana3_brm, 
                        frec_dia_eval_semana4_brm, 
                        frec_sem_semana1_monto, 
                        frec_sem_semana2_monto, 
                        frec_sem_semana3_monto, 
                        frec_sem_semana4_monto, 
                        frec_mes_sel, 
                        frec_mes_mes1_monto, 
                        frec_mes_mes2_monto, 
                        frec_mes_mes3_monto,
                        margen_utilidad_productos,
                        capacidad_criterio,
                        capacidad_monto_manual,
                        estacion_sel, 
                        estacion_sel_mes, 
                        estacion_sel_arb, 
                        estacion_monto2, 
                        estacion_monto3, 
                        estacion_ene_arb, 
                        estacion_feb_arb, 
                        estacion_mar_arb, 
                        estacion_abr_arb, 
                        estacion_may_arb, 
                        estacion_jun_arb, 
                        estacion_jul_arb, 
                        estacion_ago_arb, 
                        estacion_sep_arb, 
                        estacion_oct_arb, 
                        estacion_nov_arb, 
                        estacion_dic_arb,
                        operativos_alq_energia_monto,
                        operativos_alq_agua_monto,
                        operativos_alq_internet_monto,
                        operativos_alq_combustible_monto,
                        operativos_alq_libre1_monto,
                        operativos_alq_libre2_monto,
                        operativos_sal_aguinaldos_monto,
                        operativos_sal_libre1_monto,
                        operativos_sal_libre2_monto,
                        operativos_sal_libre3_monto,
                        operativos_sal_libre4_monto,
                        operativos_otro_transporte_monto,
                        operativos_otro_licencias_monto,
                        operativos_otro_alimentacion_monto,
                        operativos_otro_mant_vehiculo_monto,
                        operativos_otro_mant_maquina_monto,
                        operativos_otro_imprevistos_monto,
                        operativos_otro_otros_monto,
                        operativos_otro_libre1_monto,
                        operativos_otro_libre2_monto,
                        operativos_otro_libre3_monto,
                        operativos_otro_libre4_monto,
                        operativos_otro_libre5_monto,
                        familiar_alimentacion_monto,
                        familiar_energia_monto,
                        familiar_agua_monto,
                        familiar_gas_monto,
                        familiar_telefono_monto,
                        familiar_celular_monto,
                        familiar_internet_monto,
                        familiar_tv_monto,
                        familiar_impuestos_monto,
                        familiar_alquileres_monto,
                        familiar_educacion_monto,
                        familiar_transporte_monto,
                        familiar_salud_monto,
                        familiar_empleada_monto,
                        familiar_diversion_monto,
                        familiar_vestimenta_monto,
                        familiar_otros_monto,
                        familiar_libre1_monto,
                        familiar_libre2_monto,
                        familiar_libre3_monto,
                        familiar_libre4_monto,
                        familiar_libre5_monto,
                        extra_personal_ocupado,
                        extra_amortizacion_credito,
                        extra_cuota_maxima_credito
                    
                    FROM prospecto WHERE prospecto_id = ?";

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    // -- Margen Transporte
    
    function ObtenerListaMargen($codigo_prospecto, $codigo_margen, $seleccion)
    {        
        try 
        {
            if($seleccion == -1)
            {
                $criterio = '';
            }
            else
            {
                $criterio = 'margen_tipo=' . $seleccion . ' AND ';
            }
            
            if($codigo_margen == -1)
            {
                $sql = "SELECT margen_id, prospecto_id, margen_nombre, margen_cantidad, margen_unidad_medida, margen_pasajeros, margen_monto_unitario, margen_tipo FROM margen_utilidad WHERE $criterio prospecto_id=? ORDER BY margen_id ASC "; 
            }
            else
            {
                $sql = "SELECT margen_id, prospecto_id, margen_nombre, margen_cantidad, margen_unidad_medida, margen_pasajeros, margen_monto_unitario, margen_tipo FROM margen_utilidad WHERE $criterio prospecto_id=? AND margen_id=? ORDER BY margen_id ASC "; 
            }
            
            $consulta = $this->db->query($sql, array($codigo_prospecto, $codigo_margen));

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
    
    function TransporteMargen_Registro(
                    $estructura_id, 
                    $margen_id,
                    $tab,
                    $margen_nombre,
                    $margen_cantidad,
                    $margen_unidad_medida,
                    $margen_pasajeros,
                    $margen_monto_unitario,
                    $nombre_usuario, 
                    $fecha_actual)
    {        
        try 
        {
            if($margen_id == -1)
            {
                // Nuevo
                $sql = " INSERT INTO margen_utilidad(prospecto_id, margen_nombre, margen_cantidad, margen_unidad_medida, margen_pasajeros, margen_monto_unitario, margen_tipo, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $consulta = $this->db->query($sql, array($estructura_id, $margen_nombre, $margen_cantidad, $margen_unidad_medida, $margen_pasajeros, $margen_monto_unitario, $tab, $nombre_usuario, $fecha_actual));
            }
            else
            {
                // Actualizar
                $sql = " UPDATE margen_utilidad SET margen_nombre=?, margen_cantidad=?, margen_unidad_medida=?, margen_pasajeros=?, margen_monto_unitario=?, margen_tipo=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? AND margen_id=?";
                $consulta = $this->db->query($sql, array($margen_nombre, $margen_cantidad, $margen_unidad_medida, $margen_pasajeros, $margen_monto_unitario, $tab, $nombre_usuario, $fecha_actual, $estructura_id, $margen_id));
            }
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function TransporteMargen_Eliminar($estructura_id, $margen_id)
    {        
        try 
        {
            $sql = " DELETE FROM margen_utilidad WHERE prospecto_id=? AND margen_id=?";
            $consulta = $this->db->query($sql, array($estructura_id, $margen_id));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // -- Prodcuto
    
    function ObtenerProducto($codigo_producto)
    {        
        try 
        {
            $sql = "SELECT producto_id, prospecto_id, producto_nombre, producto_frecuencia, producto_venta_cantidad, producto_venta_medida, producto_venta_costo, producto_venta_precio, producto_aclaracion, producto_compra_cantidad, producto_compra_medida, producto_compra_precio, producto_unidad_venta_unidad_compra, producto_categoria_mercaderia, producto_costo_medida_cantidad, producto_costo_medida_precio, producto_costo_medida_unidad, producto_opcion, producto_seleccion FROM producto WHERE producto_id=? ORDER BY producto_id ASC "; 
            
            $consulta = $this->db->query($sql, array($codigo_producto));

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
    
    function ObtenerListaProductos($codigo_prospecto, $codigo_producto, $seleccion)
    {        
        try 
        {
            if($seleccion == -1)
            {
                $criterio = '';
            }
            else
            {
                $criterio = 'producto_seleccion=' . $seleccion . ' AND ';
            }
            
            if($codigo_producto == -1)
            {
                $sql = "SELECT producto_id, prospecto_id, producto_nombre, producto_frecuencia, producto_venta_cantidad, producto_venta_medida, producto_venta_costo, producto_venta_precio, producto_aclaracion, producto_compra_cantidad, producto_compra_medida, producto_compra_precio, producto_unidad_venta_unidad_compra, producto_categoria_mercaderia, producto_costo_medida_cantidad, producto_costo_medida_precio, producto_costo_medida_unidad, producto_opcion, producto_seleccion FROM producto WHERE $criterio prospecto_id=? ORDER BY producto_id ASC "; 
            }
            else
            {
                $sql = "SELECT producto_id, prospecto_id, producto_nombre, producto_frecuencia, producto_venta_cantidad, producto_venta_medida, producto_venta_costo, producto_venta_precio, producto_aclaracion, producto_compra_cantidad, producto_compra_medida, producto_compra_precio, producto_unidad_venta_unidad_compra, producto_categoria_mercaderia, producto_costo_medida_cantidad, producto_costo_medida_precio, producto_costo_medida_unidad, producto_opcion, producto_seleccion FROM producto WHERE $criterio prospecto_id=? AND producto_id=? ORDER BY producto_id ASC "; 
            }
            
            $consulta = $this->db->query($sql, array($codigo_prospecto, $codigo_producto));

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
    
    function ObtenerListaProductosCostos($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT producto_id, prospecto_id, producto_nombre, producto_frecuencia, producto_venta_cantidad, producto_venta_medida, producto_venta_costo, producto_venta_precio, producto_aclaracion, producto_compra_cantidad, producto_compra_medida, producto_compra_precio, producto_unidad_venta_unidad_compra, producto_categoria_mercaderia, producto_costo_medida_cantidad, producto_costo_medida_precio, producto_costo_medida_unidad, producto_opcion, producto_seleccion FROM producto WHERE prospecto_id=? AND producto_opcion=2 AND producto_seleccion=1 ORDER BY producto_id ASC ";
            
            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function Producto_Registro($estructura_id, $producto_id, $producto_nombre, $producto_venta_medida, $producto_venta_costo, $producto_aclaracion, $producto_compra_cantidad, $producto_compra_medida, $producto_compra_precio, $producto_unidad_venta_unidad_compra, $producto_categoria_mercaderia, $producto_seleccion, $producto_frecuencia, $producto_venta_cantidad, $producto_venta_precio, $producto_opcion, $producto_costo_medida_unidad, $producto_costo_medida_cantidad, $producto_costo_medida_precio, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            if($producto_id == -1)
            {
                // Nuevo
                $sql = " INSERT INTO producto (prospecto_id, producto_nombre, producto_venta_medida, producto_venta_costo, producto_aclaracion, producto_compra_cantidad, producto_compra_medida, producto_compra_precio, producto_unidad_venta_unidad_compra, producto_categoria_mercaderia, producto_seleccion, producto_frecuencia, producto_venta_cantidad, producto_venta_precio, producto_opcion, producto_costo_medida_unidad, producto_costo_medida_cantidad, producto_costo_medida_precio, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $consulta = $this->db->query($sql, array($estructura_id, $producto_nombre, $producto_venta_medida, $producto_venta_costo, $producto_aclaracion, $producto_compra_cantidad, $producto_compra_medida, $producto_compra_precio, $producto_unidad_venta_unidad_compra, $producto_categoria_mercaderia, $producto_seleccion, $producto_frecuencia, $producto_venta_cantidad, $producto_venta_precio, $producto_opcion, $producto_costo_medida_unidad, $producto_costo_medida_cantidad, $producto_costo_medida_precio, $nombre_usuario, $fecha_actual));
            }
            else
            {
                // Actualizar
                $sql = " UPDATE producto SET producto_nombre=?, producto_venta_medida=?, producto_venta_costo=?, producto_aclaracion=?, producto_compra_cantidad=?, producto_compra_medida=?, producto_compra_precio=?, producto_unidad_venta_unidad_compra=?, producto_categoria_mercaderia=?, producto_seleccion=?, producto_frecuencia=?, producto_venta_cantidad=?, producto_venta_precio=?, producto_opcion=?, producto_costo_medida_unidad=?, producto_costo_medida_cantidad=?, producto_costo_medida_precio=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? AND producto_id=?";
                $consulta = $this->db->query($sql, array($producto_nombre, $producto_venta_medida, $producto_venta_costo, $producto_aclaracion, $producto_compra_cantidad, $producto_compra_medida, $producto_compra_precio, $producto_unidad_venta_unidad_compra, $producto_categoria_mercaderia, $producto_seleccion, $producto_frecuencia, $producto_venta_cantidad, $producto_venta_precio, $producto_opcion, $producto_costo_medida_unidad, $producto_costo_medida_cantidad, $producto_costo_medida_precio, $nombre_usuario, $fecha_actual, $estructura_id, $producto_id));
            }
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function Producto_Eliminar($estructura_id, $producto_id)
    {        
        try 
        {
            $sql = " DELETE FROM producto WHERE prospecto_id=? AND producto_id=?";
            $consulta = $this->db->query($sql, array($estructura_id, $producto_id));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Detalle de los Productos
    
    function ObtenerListaDetalleProductos($codigo_producto, $codigo_detalle)
    {        
        try 
        {
            if($codigo_detalle == -1)
            {
                $sql = "SELECT d.detalle_id, d.producto_id, d.detalle_descripcion, d.detalle_cantidad, d.detalle_unidad, d.detalle_costo_unitario, d.detalle_costo_medida_unidad, d.detalle_costo_medida_precio, d.detalle_costo_medida_convertir, d.detalle_costo_unidad_medida_uso, d.detalle_costo_unidad_medida_cantidad, p.producto_nombre FROM producto_detalle d INNER JOIN producto p ON p.producto_id=d.producto_id WHERE d.producto_id=? ORDER BY d.producto_id ASC "; 
            }
            else
            {
                $sql = "SELECT d.detalle_id, d.producto_id, d.detalle_descripcion, d.detalle_cantidad, d.detalle_unidad, d.detalle_costo_unitario, d.detalle_costo_medida_unidad, d.detalle_costo_medida_precio, d.detalle_costo_medida_convertir, d.detalle_costo_unidad_medida_uso, d.detalle_costo_unidad_medida_cantidad, p.producto_nombre FROM producto_detalle d INNER JOIN producto p ON p.producto_id=d.producto_id WHERE d.producto_id=? AND d.detalle_id=? ORDER BY d.producto_id ASC "; 
            }
            
            $consulta = $this->db->query($sql, array($codigo_producto, $codigo_detalle));

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
    
    
    function select_margenes($prospecto_id)
    {        
        try 
        {            
            $sql = "SELECT 
                
                        margen_utilidad_productos,
                        porcentaje_participacion_proveedores,
                        inventario_registro,
                        inventario_registro_total
                    
                    FROM prospecto WHERE prospecto_id = ?";

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    function update_margen_utilidad(
                            $margen_utilidad_productos,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            )
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET
                
                        margen_utilidad_productos = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE prospecto_id = ?";

            $this->db->query($sql, array(
                            $margen_utilidad_productos,
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
    
    function update_monto_inventario(
                            $inventario_registro,
                            $inventario_registro_total,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            )
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET
                        inventario_registro = ?,
                        inventario_registro_total = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE prospecto_id = ?";

            $this->db->query($sql, array(
                            $inventario_registro,
                            $inventario_registro_total,
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
    
    //-- Proveedores
    
    function ObtenerListaProovedor($codigo_prospecto, $codigo_proveedor)
    {        
        try 
        {
            if($codigo_proveedor == -1)
            {
                $sql = "SELECT proveedor_id, prospecto_id, proveedor_nombre, proveedor_lugar_compra, proveedor_frecuencia_dias, proveedor_importe, proveedor_fecha_ultima, proveedor_aclaracion FROM proveedor WHERE prospecto_id=? ORDER BY proveedor_id ASC "; 
            }
            else
            {
                $sql = "SELECT proveedor_id, prospecto_id, proveedor_nombre, proveedor_lugar_compra, proveedor_frecuencia_dias, proveedor_importe, proveedor_fecha_ultima, proveedor_aclaracion FROM proveedor WHERE prospecto_id=? AND proveedor_id=? ORDER BY proveedor_id ASC "; 
            }
            
            $consulta = $this->db->query($sql, array($codigo_prospecto, $codigo_proveedor));

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
    
    function Proveedor_Registro($estructura_id, $proveedor_id, $proveedor_nombre, $proveedor_lugar_compra, $proveedor_frecuencia_dias, $proveedor_importe, $proveedor_fecha_ultima, $proveedor_aclaracion, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            if($proveedor_id == -1)
            {
                // Nuevo
                $sql = " INSERT INTO proveedor (prospecto_id, proveedor_nombre, proveedor_lugar_compra, proveedor_frecuencia_dias, proveedor_importe, proveedor_fecha_ultima, proveedor_aclaracion, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $consulta = $this->db->query($sql, array($estructura_id, $proveedor_nombre, $proveedor_lugar_compra, $proveedor_frecuencia_dias, $proveedor_importe, $proveedor_fecha_ultima, $proveedor_aclaracion, $nombre_usuario, $fecha_actual));
            }
            else
            {
                // Actualizar
                $sql = " UPDATE proveedor SET proveedor_nombre=?, proveedor_lugar_compra=?, proveedor_frecuencia_dias=?, proveedor_importe=?, proveedor_fecha_ultima=?, proveedor_aclaracion=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? AND proveedor_id=?";
                $consulta = $this->db->query($sql, array($proveedor_nombre, $proveedor_lugar_compra, $proveedor_frecuencia_dias, $proveedor_importe, $proveedor_fecha_ultima, $proveedor_aclaracion, $nombre_usuario, $fecha_actual, $estructura_id, $proveedor_id));
            }
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function Proveedor_Eliminar($estructura_id, $proveedor_id)
    {        
        try 
        {
            $sql = " DELETE FROM proveedor WHERE prospecto_id=? AND proveedor_id=?";
            $consulta = $this->db->query($sql, array($estructura_id, $proveedor_id));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function update_proveedores(
                            $porcentaje_participacion_proveedores,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            )
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET
                
                        porcentaje_participacion_proveedores = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE prospecto_id = ?";

            $this->db->query($sql, array(
                            $porcentaje_participacion_proveedores,
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
    
    //-- Materia Prima
    
    function ObtenerListaMateria($codigo_prospecto, $codigo_materia)
    {        
        try 
        {
            if($codigo_materia == -1)
            {
                $sql = "SELECT materia_id, prospecto_id, materia_nombre, materia_frecuencia, materia_unidad_compra, materia_unidad_compra_cantidad, materia_unidad_uso, materia_unidad_uso_cantidad, materia_unidad_proceso, materia_producto_medida, materia_producto_medida_cantidad, materia_precio_unitario, materia_aclaracion FROM materia_prima WHERE prospecto_id=? ORDER BY materia_id ASC "; 
            }
            else
            {
                $sql = "SELECT materia_id, prospecto_id, materia_nombre, materia_frecuencia, materia_unidad_compra, materia_unidad_compra_cantidad, materia_unidad_uso, materia_unidad_uso_cantidad, materia_unidad_proceso, materia_producto_medida, materia_producto_medida_cantidad, materia_precio_unitario, materia_aclaracion FROM materia_prima WHERE prospecto_id=? AND materia_id=? ORDER BY materia_id ASC "; 
            }
            
            $consulta = $this->db->query($sql, array($codigo_prospecto, $codigo_materia));

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
    
    function Materia_Registro($estructura_id, $materia_id, $materia_nombre, $materia_frecuencia, $materia_unidad_compra, $materia_unidad_compra_cantidad, $materia_unidad_uso, $materia_unidad_uso_cantidad, $materia_unidad_proceso, $materia_producto_medida, $materia_producto_medida_cantidad, $materia_precio_unitario, $materia_aclaracion, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            if($materia_id == -1)
            {
                // Nuevo
                $sql = " INSERT INTO materia_prima(prospecto_id, materia_nombre, materia_frecuencia, materia_unidad_compra, materia_unidad_compra_cantidad, materia_unidad_uso, materia_unidad_uso_cantidad, materia_unidad_proceso, materia_producto_medida, materia_producto_medida_cantidad, materia_precio_unitario, materia_aclaracion, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $consulta = $this->db->query($sql, array($estructura_id, $materia_nombre, $materia_frecuencia, $materia_unidad_compra, $materia_unidad_compra_cantidad, $materia_unidad_uso, $materia_unidad_uso_cantidad, $materia_unidad_proceso, $materia_producto_medida, $materia_producto_medida_cantidad, $materia_precio_unitario, $materia_aclaracion, $nombre_usuario, $fecha_actual));
            }
            else
            {
                // Actualizar
                $sql = " UPDATE materia_prima SET materia_nombre=?, materia_frecuencia=?, materia_unidad_compra=?, materia_unidad_compra_cantidad=?, materia_unidad_uso=?, materia_unidad_uso_cantidad=?, materia_unidad_proceso=?, materia_producto_medida=?, materia_producto_medida_cantidad=?, materia_precio_unitario=?, materia_aclaracion=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? AND materia_id=?";
                $consulta = $this->db->query($sql, array($materia_nombre, $materia_frecuencia, $materia_unidad_compra, $materia_unidad_compra_cantidad, $materia_unidad_uso, $materia_unidad_uso_cantidad, $materia_unidad_proceso, $materia_producto_medida, $materia_producto_medida_cantidad, $materia_precio_unitario, $materia_aclaracion, $nombre_usuario, $fecha_actual, $estructura_id, $materia_id));
            }
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function Materia_Eliminar($estructura_id, $materia_id)
    {        
        try 
        {
            $sql = " DELETE FROM materia_prima WHERE prospecto_id=? AND materia_id=?";
            $consulta = $this->db->query($sql, array($estructura_id, $materia_id));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Estacionalidad
    
    function select_estacionalidad($prospecto_id)
    {        
        try 
        {            
            $sql = "SELECT 
                
                        capacidad_criterio, 
                        capacidad_monto_manual, 
                        estacion_sel, 
                        estacion_sel_mes, 
                        estacion_sel_arb, 
                        estacion_monto2, 
                        estacion_monto3, 
                        estacion_ene_arb, 
                        estacion_feb_arb, 
                        estacion_mar_arb, 
                        estacion_abr_arb, 
                        estacion_may_arb, 
                        estacion_jun_arb, 
                        estacion_jul_arb, 
                        estacion_ago_arb, 
                        estacion_sep_arb, 
                        estacion_oct_arb, 
                        estacion_nov_arb, 
                        estacion_dic_arb
                    
                    FROM prospecto WHERE prospecto_id = ?";

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    function update_estacionalidad(
                            $capacidad_criterio,
                            $capacidad_monto_manual,
                            $estacion_sel,
                            $estacion_sel_mes,
                            $estacion_sel_arb,
                            $estacion_monto2,
                            $estacion_monto3,
                            $estacion_ene_arb,
                            $estacion_feb_arb,
                            $estacion_mar_arb,
                            $estacion_abr_arb,
                            $estacion_may_arb,
                            $estacion_jun_arb,
                            $estacion_jul_arb,
                            $estacion_ago_arb,
                            $estacion_sep_arb,
                            $estacion_oct_arb,
                            $estacion_nov_arb,
                            $estacion_dic_arb,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            )
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET
                
                        capacidad_criterio = ?,
                        capacidad_monto_manual = ?,
                        estacion_sel = ?,
                        estacion_sel_mes = ?,
                        estacion_sel_arb = ?,
                        estacion_monto2 = ?,
                        estacion_monto3 = ?,
                        estacion_ene_arb = ?,
                        estacion_feb_arb = ?,
                        estacion_mar_arb = ?,
                        estacion_abr_arb = ?,
                        estacion_may_arb = ?,
                        estacion_jun_arb = ?,
                        estacion_jul_arb = ?,
                        estacion_ago_arb = ?,
                        estacion_sep_arb = ?,
                        estacion_oct_arb = ?,
                        estacion_nov_arb = ?,
                        estacion_dic_arb = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE prospecto_id = ?";

            $this->db->query($sql, array(
                            $capacidad_criterio,
                            $capacidad_monto_manual,
                            $estacion_sel,
                            $estacion_sel_mes,
                            $estacion_sel_arb,
                            $estacion_monto2,
                            $estacion_monto3,
                            $estacion_ene_arb,
                            $estacion_feb_arb,
                            $estacion_mar_arb,
                            $estacion_abr_arb,
                            $estacion_may_arb,
                            $estacion_jun_arb,
                            $estacion_jul_arb,
                            $estacion_ago_arb,
                            $estacion_sep_arb,
                            $estacion_oct_arb,
                            $estacion_nov_arb,
                            $estacion_dic_arb,
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
    
    // Gastos Operativos
    
    function select_gastos_operativos($prospecto_id)
    {        
        try 
        {            
            $sql = "SELECT 
                
                        operativos_alq_energia_monto, 
                        operativos_alq_energia_aclaracion, 
                        operativos_alq_agua_monto, 
                        operativos_alq_agua_aclaracion, 
                        operativos_alq_internet_monto, 
                        operativos_alq_internet_aclaracion, 
                        operativos_alq_combustible_monto, 
                        operativos_alq_combustible_aclaracion, 
                        operativos_alq_libre1_texto, 
                        operativos_alq_libre1_monto, 
                        operativos_alq_libre1_aclaracion, 
                        operativos_alq_libre2_texto, 
                        operativos_alq_libre2_monto, 
                        operativos_alq_libre2_aclaracion, 
                        operativos_sal_aguinaldos_monto, 
                        operativos_sal_aguinaldos_aclaracion, 
                        operativos_sal_libre1_texto, 
                        operativos_sal_libre1_monto, 
                        operativos_sal_libre1_aclaracion, 
                        operativos_sal_libre2_texto, 
                        operativos_sal_libre2_monto, 
                        operativos_sal_libre2_aclaracion, 
                        operativos_sal_libre3_texto, 
                        operativos_sal_libre3_monto, 
                        operativos_sal_libre3_aclaracion, 
                        operativos_sal_libre4_texto, 
                        operativos_sal_libre4_monto, 
                        operativos_sal_libre4_aclaracion, 
                        operativos_otro_transporte_monto, 
                        operativos_otro_transporte_aclaracion, 
                        operativos_otro_licencias_monto, 
                        operativos_otro_licencias_aclaracion, 
                        operativos_otro_alimentacion_monto, 
                        operativos_otro_alimentacion_aclaracion, 
                        operativos_otro_mant_vehiculo_monto, 
                        operativos_otro_mant_vehiculo_aclaracion, 
                        operativos_otro_mant_maquina_monto, 
                        operativos_otro_mant_maquina_aclaracion, 
                        operativos_otro_imprevistos_monto, 
                        operativos_otro_imprevistos_aclaracion, 
                        operativos_otro_otros_monto, 
                        operativos_otro_otros_aclaracion, 
                        operativos_otro_libre1_texto, 
                        operativos_otro_libre1_monto, 
                        operativos_otro_libre1_aclaracion, 
                        operativos_otro_libre2_texto, 
                        operativos_otro_libre2_monto, 
                        operativos_otro_libre2_aclaracion, 
                        operativos_otro_libre3_texto, 
                        operativos_otro_libre3_monto, 
                        operativos_otro_libre3_aclaracion, 
                        operativos_otro_libre4_texto, 
                        operativos_otro_libre4_monto, 
                        operativos_otro_libre4_aclaracion, 
                        operativos_otro_libre5_texto, 
                        operativos_otro_libre5_monto, 
                        operativos_otro_libre5_aclaracion
                    
                    FROM prospecto WHERE prospecto_id = ?";

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    function update_gastos_operativos(
                            $operativos_alq_energia_monto,
                            $operativos_alq_agua_monto,
                            $operativos_alq_internet_monto,
                            $operativos_alq_combustible_monto,
                            $operativos_alq_libre1_texto,
                            $operativos_alq_libre1_monto,
                            $operativos_alq_libre2_texto,
                            $operativos_alq_libre2_monto,
                            $operativos_sal_aguinaldos_monto,
                            $operativos_sal_libre1_texto,
                            $operativos_sal_libre1_monto,
                            $operativos_sal_libre2_texto,
                            $operativos_sal_libre2_monto,
                            $operativos_sal_libre3_texto,
                            $operativos_sal_libre3_monto,
                            $operativos_sal_libre4_texto,
                            $operativos_sal_libre4_monto,
                            $operativos_otro_transporte_monto,
                            $operativos_otro_licencias_monto,
                            $operativos_otro_alimentacion_monto,
                            $operativos_otro_mant_vehiculo_monto,
                            $operativos_otro_mant_maquina_monto,
                            $operativos_otro_imprevistos_monto,
                            $operativos_otro_otros_monto,
                            $operativos_otro_libre1_texto,
                            $operativos_otro_libre1_monto,
                            $operativos_otro_libre2_texto,
                            $operativos_otro_libre2_monto,
                            $operativos_otro_libre3_texto,
                            $operativos_otro_libre3_monto,
                            $operativos_otro_libre4_texto,
                            $operativos_otro_libre4_monto,
                            $operativos_otro_libre5_texto,
                            $operativos_otro_libre5_monto,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            )
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET
                
                        operativos_alq_energia_monto = ?,
                        operativos_alq_agua_monto = ?,
                        operativos_alq_internet_monto = ?,
                        operativos_alq_combustible_monto = ?,
                        operativos_alq_libre1_texto = ?,
                        operativos_alq_libre1_monto = ?,
                        operativos_alq_libre2_texto = ?,
                        operativos_alq_libre2_monto = ?,
                        operativos_sal_aguinaldos_monto = ?,
                        operativos_sal_libre1_texto = ?,
                        operativos_sal_libre1_monto = ?,
                        operativos_sal_libre2_texto = ?,
                        operativos_sal_libre2_monto = ?,
                        operativos_sal_libre3_texto = ?,
                        operativos_sal_libre3_monto = ?,
                        operativos_sal_libre4_texto = ?,
                        operativos_sal_libre4_monto = ?,
                        operativos_otro_transporte_monto = ?,
                        operativos_otro_licencias_monto = ?,
                        operativos_otro_alimentacion_monto = ?,
                        operativos_otro_mant_vehiculo_monto = ?,
                        operativos_otro_mant_maquina_monto = ?,
                        operativos_otro_imprevistos_monto = ?,
                        operativos_otro_otros_monto = ?,
                        operativos_otro_libre1_texto = ?,
                        operativos_otro_libre1_monto = ?,
                        operativos_otro_libre2_texto = ?,
                        operativos_otro_libre2_monto = ?,
                        operativos_otro_libre3_texto = ?,
                        operativos_otro_libre3_monto = ?,
                        operativos_otro_libre4_texto = ?,
                        operativos_otro_libre4_monto = ?,
                        operativos_otro_libre5_texto = ?,
                        operativos_otro_libre5_monto = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE prospecto_id = ?";

            $this->db->query($sql, array(
                            $operativos_alq_energia_monto,
                            $operativos_alq_agua_monto,
                            $operativos_alq_internet_monto,
                            $operativos_alq_combustible_monto,
                            $operativos_alq_libre1_texto,
                            $operativos_alq_libre1_monto,
                            $operativos_alq_libre2_texto,
                            $operativos_alq_libre2_monto,
                            $operativos_sal_aguinaldos_monto,
                            $operativos_sal_libre1_texto,
                            $operativos_sal_libre1_monto,
                            $operativos_sal_libre2_texto,
                            $operativos_sal_libre2_monto,
                            $operativos_sal_libre3_texto,
                            $operativos_sal_libre3_monto,
                            $operativos_sal_libre4_texto,
                            $operativos_sal_libre4_monto,
                            $operativos_otro_transporte_monto,
                            $operativos_otro_licencias_monto,
                            $operativos_otro_alimentacion_monto,
                            $operativos_otro_mant_vehiculo_monto,
                            $operativos_otro_mant_maquina_monto,
                            $operativos_otro_imprevistos_monto,
                            $operativos_otro_otros_monto,
                            $operativos_otro_libre1_texto,
                            $operativos_otro_libre1_monto,
                            $operativos_otro_libre2_texto,
                            $operativos_otro_libre2_monto,
                            $operativos_otro_libre3_texto,
                            $operativos_otro_libre3_monto,
                            $operativos_otro_libre4_texto,
                            $operativos_otro_libre4_monto,
                            $operativos_otro_libre5_texto,
                            $operativos_otro_libre5_monto,
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
    
    // Gastos Familiares
    
    function select_gastos_familiares($prospecto_id)
    {        
        try 
        {            
            $sql = "SELECT 
                
                        familiar_dependientes_ingreso, 
                        familiar_edad_hijos, 
                        familiar_alimentacion_monto, 
                        familiar_alimentacion_aclaracion, 
                        familiar_energia_monto, 
                        familiar_energia_aclaracion, 
                        familiar_agua_monto, 
                        familiar_agua_aclaracion, 
                        familiar_gas_monto, 
                        familiar_gas_aclaracion, 
                        familiar_telefono_monto, 
                        familiar_telefono_aclaracion, 
                        familiar_celular_monto, 
                        familiar_celular_aclaracion, 
                        familiar_internet_monto, 
                        familiar_internet_aclaracion, 
                        familiar_tv_monto, 
                        familiar_tv_aclaracion, 
                        familiar_impuestos_monto, 
                        familiar_impuestos_aclaracion, 
                        familiar_alquileres_monto, 
                        familiar_alquileres_aclaracion, 
                        familiar_educacion_monto, 
                        familiar_educacion_aclaracion, 
                        familiar_transporte_monto, 
                        familiar_transporte_aclaracion, 
                        familiar_salud_monto, 
                        familiar_salud_aclaracion, 
                        familiar_empleada_monto, 
                        familiar_empleada_aclaracion, 
                        familiar_diversion_monto, 
                        familiar_diversion_aclaracion, 
                        familiar_vestimenta_monto, 
                        familiar_vestimenta_aclaracion, 
                        familiar_otros_monto, 
                        familiar_otros_aclaracion, 
                        familiar_libre1_texto, 
                        familiar_libre1_monto, 
                        familiar_libre1_aclaracion, 
                        familiar_libre2_texto, 
                        familiar_libre2_monto, 
                        familiar_libre2_aclaracion, 
                        familiar_libre3_texto, 
                        familiar_libre3_monto, 
                        familiar_libre3_aclaracion, 
                        familiar_libre4_texto, 
                        familiar_libre4_monto, 
                        familiar_libre4_aclaracion, 
                        familiar_libre5_texto, 
                        familiar_libre5_monto, 
                        familiar_libre5_aclaracion
                    
                    FROM prospecto WHERE prospecto_id = ?";

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    function update_gastos_familiares(
                            $familiar_dependientes_ingreso,
                            $familiar_edad_hijos,
                            $familiar_alimentacion_monto,
                            $familiar_energia_monto,
                            $familiar_agua_monto,
                            $familiar_gas_monto,
                            $familiar_telefono_monto,
                            $familiar_celular_monto,
                            $familiar_internet_monto,
                            $familiar_tv_monto,
                            $familiar_impuestos_monto,
                            $familiar_alquileres_monto,
                            $familiar_educacion_monto,
                            $familiar_transporte_monto,
                            $familiar_salud_monto,
                            $familiar_empleada_monto,
                            $familiar_diversion_monto,
                            $familiar_vestimenta_monto,
                            $familiar_otros_monto,
                            $familiar_libre1_texto,
                            $familiar_libre1_monto,
                            $familiar_libre2_texto,
                            $familiar_libre2_monto,
                            $familiar_libre3_texto,
                            $familiar_libre3_monto,
                            $familiar_libre4_texto,
                            $familiar_libre4_monto,
                            $familiar_libre5_texto,
                            $familiar_libre5_monto,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            )
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET
                
                        familiar_dependientes_ingreso = ?,
                        familiar_edad_hijos = ?,
                        familiar_alimentacion_monto = ?,
                        familiar_energia_monto = ?,
                        familiar_agua_monto = ?,
                        familiar_gas_monto = ?,
                        familiar_telefono_monto = ?,
                        familiar_celular_monto = ?,
                        familiar_internet_monto = ?,
                        familiar_tv_monto = ?,
                        familiar_impuestos_monto = ?,
                        familiar_alquileres_monto = ?,
                        familiar_educacion_monto = ?,
                        familiar_transporte_monto = ?,
                        familiar_salud_monto = ?,
                        familiar_empleada_monto = ?,
                        familiar_diversion_monto = ?,
                        familiar_vestimenta_monto = ?,
                        familiar_otros_monto = ?,
                        familiar_libre1_texto = ?,
                        familiar_libre1_monto = ?,
                        familiar_libre2_texto = ?,
                        familiar_libre2_monto = ?,
                        familiar_libre3_texto = ?,
                        familiar_libre3_monto = ?,
                        familiar_libre4_texto = ?,
                        familiar_libre4_monto = ?,
                        familiar_libre5_texto = ?,
                        familiar_libre5_monto = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE prospecto_id = ?";

            $this->db->query($sql, array(
                            $familiar_dependientes_ingreso,
                            $familiar_edad_hijos,
                            $familiar_alimentacion_monto,
                            $familiar_energia_monto,
                            $familiar_agua_monto,
                            $familiar_gas_monto,
                            $familiar_telefono_monto,
                            $familiar_celular_monto,
                            $familiar_internet_monto,
                            $familiar_tv_monto,
                            $familiar_impuestos_monto,
                            $familiar_alquileres_monto,
                            $familiar_educacion_monto,
                            $familiar_transporte_monto,
                            $familiar_salud_monto,
                            $familiar_empleada_monto,
                            $familiar_diversion_monto,
                            $familiar_vestimenta_monto,
                            $familiar_otros_monto,
                            $familiar_libre1_texto,
                            $familiar_libre1_monto,
                            $familiar_libre2_texto,
                            $familiar_libre2_monto,
                            $familiar_libre3_texto,
                            $familiar_libre3_monto,
                            $familiar_libre4_texto,
                            $familiar_libre4_monto,
                            $familiar_libre5_texto,
                            $familiar_libre5_monto,
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
    
    // Otros Ingresos
    
    function select_otros_ingresos($prospecto_id)
    {        
        try 
        {            
            $sql = "SELECT 
                
                        prospecto_id, 
                        prospecto_principal, 
                        extra_cuota_prestamo_solicitado, 
                        extra_amortizacion_otras_deudas, 
                        extra_cuota_maxima_credito, 
                        extra_amortizacion_credito, 
                        extra_efectivo_caja, 
                        extra_ahorro_dpf, 
                        extra_cuentas_cobrar, 
                        extra_inventario, 
                        extra_otros_activos_corrientes, 
                        extra_activo_fijo, 
                        extra_otros_activos_nocorrientes, 
                        extra_activos_actividades_secundarias, 
                        extra_inmuebles_terrenos, 
                        extra_bienes_hogar, 
                        extra_otros_activos_familiares, 
                        extra_endeudamiento_credito, 
                        extra_personal_ocupado, 
                        extra_cuentas_pagar_proveedores, 
                        extra_prestamos_financieras_corto, 
                        extra_cuentas_pagar_corto, 
                        extra_prestamos_financieras_largo, 
                        extra_cuentas_pagar_largo, 
                        extra_pasivo_actividades_secundarias, 
                        extra_pasivo_familiar
                    
                    FROM prospecto WHERE prospecto_id = ?";

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    function update_otros_ingresos(
                            $extra_cuota_prestamo_solicitado,
                            $extra_amortizacion_otras_deudas,
                            $extra_cuota_maxima_credito,
                            $extra_amortizacion_credito,
                            $extra_endeudamiento_credito,
                            $extra_personal_ocupado,
                            $extra_efectivo_caja,
                            $extra_ahorro_dpf,
                            $extra_cuentas_cobrar,
                            $extra_inventario,
                            $extra_otros_activos_corrientes,
                            $extra_activo_fijo,
                            $extra_otros_activos_nocorrientes,
                            $extra_activos_actividades_secundarias,
                            $extra_inmuebles_terrenos,
                            $extra_bienes_hogar,
                            $extra_otros_activos_familiares,
                            $extra_cuentas_pagar_proveedores,
                            $extra_prestamos_financieras_corto,
                            $extra_cuentas_pagar_corto,
                            $extra_prestamos_financieras_largo,
                            $extra_cuentas_pagar_largo,
                            $extra_pasivo_actividades_secundarias,
                            $extra_pasivo_familiar,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            )
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET
                
                        extra_cuota_prestamo_solicitado = ?,
                        extra_amortizacion_otras_deudas = ?,
                        extra_cuota_maxima_credito = ?,
                        extra_amortizacion_credito = ?,
                        extra_endeudamiento_credito = ?,
                        extra_personal_ocupado = ?,
                        extra_efectivo_caja = ?,
                        extra_ahorro_dpf = ?,
                        extra_cuentas_cobrar = ?,
                        extra_inventario = ?,
                        extra_otros_activos_corrientes = ?,
                        extra_activo_fijo = ?,
                        extra_otros_activos_nocorrientes = ?,
                        extra_activos_actividades_secundarias = ?,
                        extra_inmuebles_terrenos = ?,
                        extra_bienes_hogar = ?,
                        extra_otros_activos_familiares = ?,
                        extra_cuentas_pagar_proveedores = ?,
                        extra_prestamos_financieras_corto = ?,
                        extra_cuentas_pagar_corto = ?,
                        extra_prestamos_financieras_largo = ?,
                        extra_cuentas_pagar_largo = ?,
                        extra_pasivo_actividades_secundarias = ?,
                        extra_pasivo_familiar = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE prospecto_id = ?";

            $this->db->query($sql, array(
                            $extra_cuota_prestamo_solicitado,
                            $extra_amortizacion_otras_deudas,
                            $extra_cuota_maxima_credito,
                            $extra_amortizacion_credito,
                            $extra_endeudamiento_credito,
                            $extra_personal_ocupado,
                            $extra_efectivo_caja,
                            $extra_ahorro_dpf,
                            $extra_cuentas_cobrar,
                            $extra_inventario,
                            $extra_otros_activos_corrientes,
                            $extra_activo_fijo,
                            $extra_otros_activos_nocorrientes,
                            $extra_activos_actividades_secundarias,
                            $extra_inmuebles_terrenos,
                            $extra_bienes_hogar,
                            $extra_otros_activos_familiares,
                            $extra_cuentas_pagar_proveedores,
                            $extra_prestamos_financieras_corto,
                            $extra_cuentas_pagar_corto,
                            $extra_prestamos_financieras_largo,
                            $extra_cuentas_pagar_largo,
                            $extra_pasivo_actividades_secundarias,
                            $extra_pasivo_familiar,
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
    
    // Transporte Fuente Generadora de Ingresos
    
    function select_fuente_generadora($prospecto_id)
    {        
        try 
        {            
            $sql = "SELECT 
                
                        transporte_tipo_prestatario, 
                        transporte_tipo_transporte, 
                        transporte_preg_sindicato, 
                        transporte_preg_sindicato_lineas, 
                        transporte_preg_sindicato_grupos, 
                        transporte_preg_unidades_grupo, 
                        transporte_preg_grupo_rota, 
                        transporte_preg_lineas_buenas, 
                        transporte_preg_lineas_regulares, 
                        transporte_preg_lineas_malas, 
                        transporte_preg_trabaja_semana, 
                        transporte_preg_trabaja_dia, 
                        transporte_preg_jornada_inicia, 
                        transporte_preg_jornada_concluye, 
                        transporte_preg_tiempo_no_trabaja, 
                        transporte_preg_tiempo_parada, 
                        transporte_preg_tiempo_vuelta, 
                        transporte_preg_vehiculo_ano, 
                        transporte_preg_vehiculo_combustible,
                        total_ingreso_bueno,
                        total_ingreso_regular,
                        total_ingreso_malo
                    
                    FROM prospecto WHERE prospecto_id = ?";

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    function update_fuente_generadora(
                            $transporte_tipo_prestatario,
                            $transporte_tipo_transporte,
                            $transporte_preg_sindicato,
                            $transporte_preg_sindicato_lineas,
                            $transporte_preg_sindicato_grupos,
                            $transporte_preg_unidades_grupo,
                            $transporte_preg_grupo_rota,
                            $transporte_preg_lineas_buenas,
                            $transporte_preg_lineas_regulares,
                            $transporte_preg_lineas_malas,
                            $transporte_preg_trabaja_semana,
                            $transporte_preg_trabaja_dia,
                            $transporte_preg_jornada_inicia,
                            $transporte_preg_jornada_concluye,
                            $transporte_preg_tiempo_no_trabaja,
                            $transporte_preg_tiempo_parada,
                            $transporte_preg_tiempo_vuelta,
                            $transporte_preg_vehiculo_ano,
                            $transporte_preg_vehiculo_combustible,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            )
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET
                
                        transporte_tipo_prestatario= ?,
                        transporte_tipo_transporte= ?,
                        transporte_preg_sindicato= ?,
                        transporte_preg_sindicato_lineas= ?,
                        transporte_preg_sindicato_grupos= ?,
                        transporte_preg_unidades_grupo= ?,
                        transporte_preg_grupo_rota= ?,
                        transporte_preg_lineas_buenas= ?,
                        transporte_preg_lineas_regulares= ?,
                        transporte_preg_lineas_malas= ?,
                        transporte_preg_trabaja_semana= ?,
                        transporte_preg_trabaja_dia= ?,
                        transporte_preg_jornada_inicia= ?,
                        transporte_preg_jornada_concluye= ?,
                        transporte_preg_tiempo_no_trabaja= ?,
                        transporte_preg_tiempo_parada= ?,
                        transporte_preg_tiempo_vuelta= ?,
                        transporte_preg_vehiculo_ano= ?,
                        transporte_preg_vehiculo_combustible= ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE prospecto_id = ?";

            $this->db->query($sql, array(
                            $transporte_tipo_prestatario,
                            $transporte_tipo_transporte,
                            $transporte_preg_sindicato,
                            $transporte_preg_sindicato_lineas,
                            $transporte_preg_sindicato_grupos,
                            $transporte_preg_unidades_grupo,
                            $transporte_preg_grupo_rota,
                            $transporte_preg_lineas_buenas,
                            $transporte_preg_lineas_regulares,
                            $transporte_preg_lineas_malas,
                            $transporte_preg_trabaja_semana,
                            $transporte_preg_trabaja_dia,
                            $transporte_preg_jornada_inicia,
                            $transporte_preg_jornada_concluye,
                            $transporte_preg_tiempo_no_trabaja,
                            $transporte_preg_tiempo_parada,
                            $transporte_preg_tiempo_vuelta,
                            $transporte_preg_vehiculo_ano,
                            $transporte_preg_vehiculo_combustible,
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
    
    // Transporte Fuente Generadora de Ingresos
    
    function select_volumen_ingresos($prospecto_id)
    {        
        try 
        {            
            $sql = "SELECT 
                        prospecto_principal, 
                        transporte_tipo_prestatario, 
                        transporte_tipo_transporte, 
                        transporte_preg_jornada_concluye, 
                        transporte_preg_jornada_inicia, 
                        transporte_preg_tiempo_no_trabaja, 
                        transporte_preg_tiempo_parada, 
                        transporte_preg_tiempo_vuelta, 
                        transporte_preg_trabaja_semana, 
                        transporte_cliente_frecuencia, 
                        transporte_cliente_dia_lunes, 
                        transporte_cliente_dia_martes, 
                        transporte_cliente_dia_miercoles, 
                        transporte_cliente_dia_jueves, 
                        transporte_cliente_dia_viernes, 
                        transporte_cliente_dia_sabado, 
                        transporte_cliente_dia_domingo, 
                        transporte_cliente_linea1_texto, 
                        transporte_cliente_linea2_texto, 
                        transporte_cliente_linea3_texto, 
                        transporte_cliente_linea4_texto, 
                        transporte_cliente_linea5_texto, 
                        transporte_cliente_linea6_texto, 
                        transporte_cliente_linea7_texto, 
                        transporte_cliente_linea1_min, 
                        transporte_cliente_linea2_min, 
                        transporte_cliente_linea3_min, 
                        transporte_cliente_linea4_min, 
                        transporte_cliente_linea5_min, 
                        transporte_cliente_linea6_min, 
                        transporte_cliente_linea7_min, 
                        transporte_cliente_linea1_max, 
                        transporte_cliente_linea2_max, 
                        transporte_cliente_linea3_max, 
                        transporte_cliente_linea4_max, 
                        transporte_cliente_linea5_max, 
                        transporte_cliente_linea6_max, 
                        transporte_cliente_linea7_max, 
                        transporte_cliente_vueta_buena_monto, 
                        transporte_cliente_vueta_buena_numero, 
                        transporte_cliente_vueta_regular_monto, 
                        transporte_cliente_vueta_regular_numero, 
                        transporte_cliente_vueta_mala_monto, 
                        transporte_cliente_vueta_mala_numero, 
                        transporte_capacidad_sin_rotacion, 
                        transporte_capacidad_con_rotacion, 
                        transporte_capacidad_tramo_largo_pasajero, 
                        transporte_capacidad_tramo_corto_pasajero, 
                        transporte_capacidad_tramo_largo_precio, 
                        transporte_capacidad_tramo_corto_precio, 
                        transporte_vuelta_buena_ocupacion, 
                        transporte_vuelta_buena_veces, 
                        transporte_vuelta_regular_ocupacion, 
                        transporte_vuelta_regular_veces, 
                        transporte_vuelta_mala_ocupacion, 
                        transporte_vuelta_mala_veces,
                        total_ingreso_bueno,
                        total_ingreso_regular,
                        total_ingreso_malo,
                        operativos_cambio_aceite_motor_frecuencia, 
                        operativos_cambio_aceite_motor_cantidad, 
                        operativos_cambio_aceite_motor_monto, 
                        operativos_cambio_aceite_caja_frecuencia, 
                        operativos_cambio_aceite_caja_cantidad, 
                        operativos_cambio_aceite_caja_monto, 
                        operativos_cambio_llanta_delanteras_frecuencia, 
                        operativos_cambio_llanta_delanteras_cantidad, 
                        operativos_cambio_llanta_delanteras_monto, 
                        operativos_cambio_llanta_traseras_frecuencia, 
                        operativos_cambio_llanta_traseras_cantidad, 
                        operativos_cambio_llanta_traseras_monto, 
                        operativos_cambio_bateria_frecuencia, 
                        operativos_cambio_bateria_cantidad, 
                        operativos_cambio_bateria_monto, 
                        operativos_cambio_balatas_frecuencia, 
                        operativos_cambio_balatas_cantidad, 
                        operativos_cambio_balatas_monto, 
                        operativos_revision_electrico_frecuencia, 
                        operativos_revision_electrico_cantidad, 
                        operativos_revision_electrico_monto, 
                        operativos_remachado_embrague_frecuencia, 
                        operativos_remachado_embrague_cantidad, 
                        operativos_remachado_embrague_monto, 
                        operativos_rectificacion_motor_frecuencia, 
                        operativos_rectificacion_motor_cantidad, 
                        operativos_rectificacion_motor_monto, 
                        operativos_cambio_rodamiento_frecuencia, 
                        operativos_cambio_rodamiento_cantidad, 
                        operativos_cambio_rodamiento_monto, 
                        operativos_reparaciones_menores_frecuencia, 
                        operativos_reparaciones_menores_cantidad, 
                        operativos_reparaciones_menores_monto, 
                        operativos_imprevistos_frecuencia, 
                        operativos_imprevistos_cantidad, 
                        operativos_imprevistos_monto, 
                        operativos_impuesto_propiedad_frecuencia, 
                        operativos_impuesto_propiedad_cantidad, 
                        operativos_impuesto_propiedad_monto, 
                        operativos_soat_frecuencia, 
                        operativos_soat_cantidad, 
                        operativos_soat_monto, 
                        operativos_roseta_inspeccion_frecuencia, 
                        operativos_roseta_inspeccion_cantidad, 
                        operativos_roseta_inspeccion_monto, 
                        operativos_otro_transporte_libre1_texto, 
                        operativos_otro_transporte_libre1_frecuencia, 
                        operativos_otro_transporte_libre1_cantidad, 
                        operativos_otro_transporte_libre1_monto, 
                        operativos_otro_transporte_libre2_texto, 
                        operativos_otro_transporte_libre2_frecuencia, 
                        operativos_otro_transporte_libre2_cantidad, 
                        operativos_otro_transporte_libre2_monto, 
                        operativos_otro_transporte_libre3_texto, 
                        operativos_otro_transporte_libre3_frecuencia, 
                        operativos_otro_transporte_libre3_cantidad, 
                        operativos_otro_transporte_libre3_monto, 
                        operativos_otro_transporte_libre4_texto, 
                        operativos_otro_transporte_libre4_frecuencia, 
                        operativos_otro_transporte_libre4_cantidad, 
                        operativos_otro_transporte_libre4_monto, 
                        operativos_otro_transporte_libre5_texto, 
                        operativos_otro_transporte_libre5_frecuencia, 
                        operativos_otro_transporte_libre5_cantidad, 
                        operativos_otro_transporte_libre5_monto,
                        familiar_alimentacion_monto,
                        familiar_energia_monto,
                        familiar_agua_monto,
                        familiar_gas_monto,
                        familiar_telefono_monto,
                        familiar_celular_monto,
                        familiar_internet_monto,
                        familiar_tv_monto,
                        familiar_impuestos_monto,
                        familiar_alquileres_monto,
                        familiar_educacion_monto,
                        familiar_transporte_monto,
                        familiar_salud_monto,
                        familiar_empleada_monto,
                        familiar_diversion_monto,
                        familiar_vestimenta_monto,
                        familiar_otros_monto,
                        familiar_libre1_monto,
                        familiar_libre2_monto,
                        familiar_libre3_monto,
                        familiar_libre4_monto,
                        familiar_libre5_monto,
                        extra_cuota_prestamo_solicitado
                    
                    FROM prospecto WHERE prospecto_id = ?";

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    function update_volumen_ingresos(
                            $transporte_cliente_frecuencia, 
                            $transporte_cliente_dia_lunes, 
                            $transporte_cliente_dia_martes, 
                            $transporte_cliente_dia_miercoles, 
                            $transporte_cliente_dia_jueves, 
                            $transporte_cliente_dia_viernes, 
                            $transporte_cliente_dia_sabado, 
                            $transporte_cliente_dia_domingo, 
                            $transporte_cliente_linea1_texto, 
                            $transporte_cliente_linea2_texto, 
                            $transporte_cliente_linea3_texto, 
                            $transporte_cliente_linea4_texto, 
                            $transporte_cliente_linea5_texto, 
                            $transporte_cliente_linea6_texto, 
                            $transporte_cliente_linea7_texto, 
                            $transporte_cliente_linea1_min, 
                            $transporte_cliente_linea2_min, 
                            $transporte_cliente_linea3_min, 
                            $transporte_cliente_linea4_min, 
                            $transporte_cliente_linea5_min, 
                            $transporte_cliente_linea6_min, 
                            $transporte_cliente_linea7_min, 
                            $transporte_cliente_linea1_max, 
                            $transporte_cliente_linea2_max, 
                            $transporte_cliente_linea3_max, 
                            $transporte_cliente_linea4_max, 
                            $transporte_cliente_linea5_max, 
                            $transporte_cliente_linea6_max, 
                            $transporte_cliente_linea7_max, 
                            $transporte_cliente_vueta_buena_monto, 
                            $transporte_cliente_vueta_buena_numero, 
                            $transporte_cliente_vueta_regular_monto, 
                            $transporte_cliente_vueta_regular_numero, 
                            $transporte_cliente_vueta_mala_monto, 
                            $transporte_cliente_vueta_mala_numero, 
                            $transporte_capacidad_sin_rotacion, 
                            $transporte_capacidad_con_rotacion, 
                            $transporte_capacidad_tramo_largo_pasajero, 
                            $transporte_capacidad_tramo_corto_pasajero, 
                            $transporte_capacidad_tramo_largo_precio, 
                            $transporte_capacidad_tramo_corto_precio, 
                            $transporte_vuelta_buena_ocupacion, 
                            $transporte_vuelta_buena_veces, 
                            $transporte_vuelta_regular_ocupacion, 
                            $transporte_vuelta_regular_veces, 
                            $transporte_vuelta_mala_ocupacion, 
                            $transporte_vuelta_mala_veces, 
                            $total_ingreso_bueno, 
                            $total_ingreso_regular, 
                            $total_ingreso_malo, 
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            )
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET
                
                        transporte_cliente_frecuencia = ?,
                        transporte_cliente_dia_lunes = ?,
                        transporte_cliente_dia_martes = ?,
                        transporte_cliente_dia_miercoles = ?,
                        transporte_cliente_dia_jueves = ?,
                        transporte_cliente_dia_viernes = ?,
                        transporte_cliente_dia_sabado = ?,
                        transporte_cliente_dia_domingo = ?,
                        transporte_cliente_linea1_texto = ?,
                        transporte_cliente_linea2_texto = ?,
                        transporte_cliente_linea3_texto = ?,
                        transporte_cliente_linea4_texto = ?,
                        transporte_cliente_linea5_texto = ?,
                        transporte_cliente_linea6_texto = ?,
                        transporte_cliente_linea7_texto = ?,
                        transporte_cliente_linea1_min = ?,
                        transporte_cliente_linea2_min = ?,
                        transporte_cliente_linea3_min = ?,
                        transporte_cliente_linea4_min = ?,
                        transporte_cliente_linea5_min = ?,
                        transporte_cliente_linea6_min = ?,
                        transporte_cliente_linea7_min = ?,
                        transporte_cliente_linea1_max = ?,
                        transporte_cliente_linea2_max = ?,
                        transporte_cliente_linea3_max = ?,
                        transporte_cliente_linea4_max = ?,
                        transporte_cliente_linea5_max = ?,
                        transporte_cliente_linea6_max = ?,
                        transporte_cliente_linea7_max = ?,
                        transporte_cliente_vueta_buena_monto = ?,
                        transporte_cliente_vueta_buena_numero = ?,
                        transporte_cliente_vueta_regular_monto = ?,
                        transporte_cliente_vueta_regular_numero = ?,
                        transporte_cliente_vueta_mala_monto = ?,
                        transporte_cliente_vueta_mala_numero = ?,
                        transporte_capacidad_sin_rotacion = ?,
                        transporte_capacidad_con_rotacion = ?,
                        transporte_capacidad_tramo_largo_pasajero = ?,
                        transporte_capacidad_tramo_corto_pasajero = ?,
                        transporte_capacidad_tramo_largo_precio = ?,
                        transporte_capacidad_tramo_corto_precio = ?,
                        transporte_vuelta_buena_ocupacion = ?,
                        transporte_vuelta_buena_veces = ?,
                        transporte_vuelta_regular_ocupacion = ?,
                        transporte_vuelta_regular_veces = ?,
                        transporte_vuelta_mala_ocupacion = ?,
                        transporte_vuelta_mala_veces = ?,
                        total_ingreso_bueno = ?,
                        total_ingreso_regular = ?,
                        total_ingreso_malo = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE prospecto_id = ?";

            $this->db->query($sql, array(
                            $transporte_cliente_frecuencia, 
                            $transporte_cliente_dia_lunes, 
                            $transporte_cliente_dia_martes, 
                            $transporte_cliente_dia_miercoles, 
                            $transporte_cliente_dia_jueves, 
                            $transporte_cliente_dia_viernes, 
                            $transporte_cliente_dia_sabado, 
                            $transporte_cliente_dia_domingo, 
                            $transporte_cliente_linea1_texto, 
                            $transporte_cliente_linea2_texto, 
                            $transporte_cliente_linea3_texto, 
                            $transporte_cliente_linea4_texto, 
                            $transporte_cliente_linea5_texto, 
                            $transporte_cliente_linea6_texto, 
                            $transporte_cliente_linea7_texto, 
                            $transporte_cliente_linea1_min, 
                            $transporte_cliente_linea2_min, 
                            $transporte_cliente_linea3_min, 
                            $transporte_cliente_linea4_min, 
                            $transporte_cliente_linea5_min, 
                            $transporte_cliente_linea6_min, 
                            $transporte_cliente_linea7_min, 
                            $transporte_cliente_linea1_max, 
                            $transporte_cliente_linea2_max, 
                            $transporte_cliente_linea3_max, 
                            $transporte_cliente_linea4_max, 
                            $transporte_cliente_linea5_max, 
                            $transporte_cliente_linea6_max, 
                            $transporte_cliente_linea7_max, 
                            $transporte_cliente_vueta_buena_monto, 
                            $transporte_cliente_vueta_buena_numero, 
                            $transporte_cliente_vueta_regular_monto, 
                            $transporte_cliente_vueta_regular_numero, 
                            $transporte_cliente_vueta_mala_monto, 
                            $transporte_cliente_vueta_mala_numero, 
                            $transporte_capacidad_sin_rotacion, 
                            $transporte_capacidad_con_rotacion, 
                            $transporte_capacidad_tramo_largo_pasajero, 
                            $transporte_capacidad_tramo_corto_pasajero, 
                            $transporte_capacidad_tramo_largo_precio, 
                            $transporte_capacidad_tramo_corto_precio, 
                            $transporte_vuelta_buena_ocupacion, 
                            $transporte_vuelta_buena_veces, 
                            $transporte_vuelta_regular_ocupacion, 
                            $transporte_vuelta_regular_veces, 
                            $transporte_vuelta_mala_ocupacion, 
                            $transporte_vuelta_mala_veces, 
                            $total_ingreso_bueno, 
                            $total_ingreso_regular, 
                            $total_ingreso_malo, 
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
    
    // Transporte Fuente Generadora de Ingresos
    
    function select_transporte_gastos_operativos($prospecto_id)
    {        
        try 
        {            
            $sql = "SELECT 
                
                        operativos_cambio_aceite_motor_frecuencia, 
                        operativos_cambio_aceite_motor_cantidad, 
                        operativos_cambio_aceite_motor_monto, 
                        operativos_cambio_aceite_caja_frecuencia, 
                        operativos_cambio_aceite_caja_cantidad, 
                        operativos_cambio_aceite_caja_monto, 
                        operativos_cambio_llanta_delanteras_frecuencia, 
                        operativos_cambio_llanta_delanteras_cantidad, 
                        operativos_cambio_llanta_delanteras_monto, 
                        operativos_cambio_llanta_traseras_frecuencia, 
                        operativos_cambio_llanta_traseras_cantidad, 
                        operativos_cambio_llanta_traseras_monto, 
                        operativos_cambio_bateria_frecuencia, 
                        operativos_cambio_bateria_cantidad, 
                        operativos_cambio_bateria_monto, 
                        operativos_cambio_balatas_frecuencia, 
                        operativos_cambio_balatas_cantidad, 
                        operativos_cambio_balatas_monto, 
                        operativos_revision_electrico_frecuencia, 
                        operativos_revision_electrico_cantidad, 
                        operativos_revision_electrico_monto, 
                        operativos_remachado_embrague_frecuencia, 
                        operativos_remachado_embrague_cantidad, 
                        operativos_remachado_embrague_monto, 
                        operativos_rectificacion_motor_frecuencia, 
                        operativos_rectificacion_motor_cantidad, 
                        operativos_rectificacion_motor_monto, 
                        operativos_cambio_rodamiento_frecuencia, 
                        operativos_cambio_rodamiento_cantidad, 
                        operativos_cambio_rodamiento_monto, 
                        operativos_reparaciones_menores_frecuencia, 
                        operativos_reparaciones_menores_cantidad, 
                        operativos_reparaciones_menores_monto, 
                        operativos_imprevistos_frecuencia, 
                        operativos_imprevistos_cantidad, 
                        operativos_imprevistos_monto, 
                        operativos_impuesto_propiedad_frecuencia, 
                        operativos_impuesto_propiedad_cantidad, 
                        operativos_impuesto_propiedad_monto, 
                        operativos_soat_frecuencia, 
                        operativos_soat_cantidad, 
                        operativos_soat_monto, 
                        operativos_roseta_inspeccion_frecuencia, 
                        operativos_roseta_inspeccion_cantidad, 
                        operativos_roseta_inspeccion_monto, 
                        operativos_otro_transporte_libre1_texto, 
                        operativos_otro_transporte_libre1_frecuencia, 
                        operativos_otro_transporte_libre1_cantidad, 
                        operativos_otro_transporte_libre1_monto, 
                        operativos_otro_transporte_libre2_texto, 
                        operativos_otro_transporte_libre2_frecuencia, 
                        operativos_otro_transporte_libre2_cantidad, 
                        operativos_otro_transporte_libre2_monto, 
                        operativos_otro_transporte_libre3_texto, 
                        operativos_otro_transporte_libre3_frecuencia, 
                        operativos_otro_transporte_libre3_cantidad, 
                        operativos_otro_transporte_libre3_monto, 
                        operativos_otro_transporte_libre4_texto, 
                        operativos_otro_transporte_libre4_frecuencia, 
                        operativos_otro_transporte_libre4_cantidad, 
                        operativos_otro_transporte_libre4_monto, 
                        operativos_otro_transporte_libre5_texto, 
                        operativos_otro_transporte_libre5_frecuencia, 
                        operativos_otro_transporte_libre5_cantidad, 
                        operativos_otro_transporte_libre5_monto
                    
                    FROM prospecto WHERE prospecto_id = ?";

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    function transporte_gastos_operativos(
                            $operativos_cambio_aceite_motor_frecuencia, 
                            $operativos_cambio_aceite_motor_cantidad, 
                            $operativos_cambio_aceite_motor_monto, 
                            $operativos_cambio_aceite_caja_frecuencia, 
                            $operativos_cambio_aceite_caja_cantidad, 
                            $operativos_cambio_aceite_caja_monto, 
                            $operativos_cambio_llanta_delanteras_frecuencia, 
                            $operativos_cambio_llanta_delanteras_cantidad, 
                            $operativos_cambio_llanta_delanteras_monto, 
                            $operativos_cambio_llanta_traseras_frecuencia, 
                            $operativos_cambio_llanta_traseras_cantidad, 
                            $operativos_cambio_llanta_traseras_monto, 
                            $operativos_cambio_bateria_frecuencia, 
                            $operativos_cambio_bateria_cantidad, 
                            $operativos_cambio_bateria_monto, 
                            $operativos_cambio_balatas_frecuencia, 
                            $operativos_cambio_balatas_cantidad, 
                            $operativos_cambio_balatas_monto, 
                            $operativos_revision_electrico_frecuencia, 
                            $operativos_revision_electrico_cantidad, 
                            $operativos_revision_electrico_monto, 
                            $operativos_remachado_embrague_frecuencia, 
                            $operativos_remachado_embrague_cantidad, 
                            $operativos_remachado_embrague_monto, 
                            $operativos_rectificacion_motor_frecuencia, 
                            $operativos_rectificacion_motor_cantidad, 
                            $operativos_rectificacion_motor_monto, 
                            $operativos_cambio_rodamiento_frecuencia, 
                            $operativos_cambio_rodamiento_cantidad, 
                            $operativos_cambio_rodamiento_monto, 
                            $operativos_reparaciones_menores_frecuencia, 
                            $operativos_reparaciones_menores_cantidad, 
                            $operativos_reparaciones_menores_monto, 
                            $operativos_imprevistos_frecuencia, 
                            $operativos_imprevistos_cantidad, 
                            $operativos_imprevistos_monto, 
                            $operativos_impuesto_propiedad_frecuencia, 
                            $operativos_impuesto_propiedad_cantidad, 
                            $operativos_impuesto_propiedad_monto, 
                            $operativos_soat_frecuencia, 
                            $operativos_soat_cantidad, 
                            $operativos_soat_monto, 
                            $operativos_roseta_inspeccion_frecuencia, 
                            $operativos_roseta_inspeccion_cantidad, 
                            $operativos_roseta_inspeccion_monto, 
                            $operativos_otro_transporte_libre1_texto, 
                            $operativos_otro_transporte_libre1_frecuencia, 
                            $operativos_otro_transporte_libre1_cantidad, 
                            $operativos_otro_transporte_libre1_monto, 
                            $operativos_otro_transporte_libre2_texto, 
                            $operativos_otro_transporte_libre2_frecuencia, 
                            $operativos_otro_transporte_libre2_cantidad, 
                            $operativos_otro_transporte_libre2_monto, 
                            $operativos_otro_transporte_libre3_texto, 
                            $operativos_otro_transporte_libre3_frecuencia, 
                            $operativos_otro_transporte_libre3_cantidad, 
                            $operativos_otro_transporte_libre3_monto, 
                            $operativos_otro_transporte_libre4_texto, 
                            $operativos_otro_transporte_libre4_frecuencia, 
                            $operativos_otro_transporte_libre4_cantidad, 
                            $operativos_otro_transporte_libre4_monto, 
                            $operativos_otro_transporte_libre5_texto, 
                            $operativos_otro_transporte_libre5_frecuencia, 
                            $operativos_otro_transporte_libre5_cantidad, 
                            $operativos_otro_transporte_libre5_monto, 
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            )
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET
                
                        operativos_cambio_aceite_motor_frecuencia = ?, 
                        operativos_cambio_aceite_motor_cantidad = ?, 
                        operativos_cambio_aceite_motor_monto = ?, 
                        operativos_cambio_aceite_caja_frecuencia = ?, 
                        operativos_cambio_aceite_caja_cantidad = ?, 
                        operativos_cambio_aceite_caja_monto = ?, 
                        operativos_cambio_llanta_delanteras_frecuencia = ?, 
                        operativos_cambio_llanta_delanteras_cantidad = ?, 
                        operativos_cambio_llanta_delanteras_monto = ?, 
                        operativos_cambio_llanta_traseras_frecuencia = ?, 
                        operativos_cambio_llanta_traseras_cantidad = ?, 
                        operativos_cambio_llanta_traseras_monto = ?, 
                        operativos_cambio_bateria_frecuencia = ?, 
                        operativos_cambio_bateria_cantidad = ?, 
                        operativos_cambio_bateria_monto = ?, 
                        operativos_cambio_balatas_frecuencia = ?, 
                        operativos_cambio_balatas_cantidad = ?, 
                        operativos_cambio_balatas_monto = ?, 
                        operativos_revision_electrico_frecuencia = ?, 
                        operativos_revision_electrico_cantidad = ?, 
                        operativos_revision_electrico_monto = ?, 
                        operativos_remachado_embrague_frecuencia = ?, 
                        operativos_remachado_embrague_cantidad = ?, 
                        operativos_remachado_embrague_monto = ?, 
                        operativos_rectificacion_motor_frecuencia = ?, 
                        operativos_rectificacion_motor_cantidad = ?, 
                        operativos_rectificacion_motor_monto = ?, 
                        operativos_cambio_rodamiento_frecuencia = ?, 
                        operativos_cambio_rodamiento_cantidad = ?, 
                        operativos_cambio_rodamiento_monto = ?, 
                        operativos_reparaciones_menores_frecuencia = ?, 
                        operativos_reparaciones_menores_cantidad = ?, 
                        operativos_reparaciones_menores_monto = ?, 
                        operativos_imprevistos_frecuencia = ?, 
                        operativos_imprevistos_cantidad = ?, 
                        operativos_imprevistos_monto = ?, 
                        operativos_impuesto_propiedad_frecuencia = ?, 
                        operativos_impuesto_propiedad_cantidad = ?, 
                        operativos_impuesto_propiedad_monto = ?, 
                        operativos_soat_frecuencia = ?, 
                        operativos_soat_cantidad = ?, 
                        operativos_soat_monto = ?, 
                        operativos_roseta_inspeccion_frecuencia = ?, 
                        operativos_roseta_inspeccion_cantidad = ?, 
                        operativos_roseta_inspeccion_monto = ?, 
                        operativos_otro_transporte_libre1_texto = ?, 
                        operativos_otro_transporte_libre1_frecuencia = ?, 
                        operativos_otro_transporte_libre1_cantidad = ?, 
                        operativos_otro_transporte_libre1_monto = ?, 
                        operativos_otro_transporte_libre2_texto = ?, 
                        operativos_otro_transporte_libre2_frecuencia = ?, 
                        operativos_otro_transporte_libre2_cantidad = ?, 
                        operativos_otro_transporte_libre2_monto = ?, 
                        operativos_otro_transporte_libre3_texto = ?, 
                        operativos_otro_transporte_libre3_frecuencia = ?, 
                        operativos_otro_transporte_libre3_cantidad = ?, 
                        operativos_otro_transporte_libre3_monto = ?, 
                        operativos_otro_transporte_libre4_texto = ?, 
                        operativos_otro_transporte_libre4_frecuencia = ?, 
                        operativos_otro_transporte_libre4_cantidad = ?, 
                        operativos_otro_transporte_libre4_monto = ?, 
                        operativos_otro_transporte_libre5_texto = ?, 
                        operativos_otro_transporte_libre5_frecuencia = ?, 
                        operativos_otro_transporte_libre5_cantidad = ?, 
                        operativos_otro_transporte_libre5_monto = ?, 
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE prospecto_id = ?";

            $this->db->query($sql, array(
                            $operativos_cambio_aceite_motor_frecuencia, 
                            $operativos_cambio_aceite_motor_cantidad, 
                            $operativos_cambio_aceite_motor_monto, 
                            $operativos_cambio_aceite_caja_frecuencia, 
                            $operativos_cambio_aceite_caja_cantidad, 
                            $operativos_cambio_aceite_caja_monto, 
                            $operativos_cambio_llanta_delanteras_frecuencia, 
                            $operativos_cambio_llanta_delanteras_cantidad, 
                            $operativos_cambio_llanta_delanteras_monto, 
                            $operativos_cambio_llanta_traseras_frecuencia, 
                            $operativos_cambio_llanta_traseras_cantidad, 
                            $operativos_cambio_llanta_traseras_monto, 
                            $operativos_cambio_bateria_frecuencia, 
                            $operativos_cambio_bateria_cantidad, 
                            $operativos_cambio_bateria_monto, 
                            $operativos_cambio_balatas_frecuencia, 
                            $operativos_cambio_balatas_cantidad, 
                            $operativos_cambio_balatas_monto, 
                            $operativos_revision_electrico_frecuencia, 
                            $operativos_revision_electrico_cantidad, 
                            $operativos_revision_electrico_monto, 
                            $operativos_remachado_embrague_frecuencia, 
                            $operativos_remachado_embrague_cantidad, 
                            $operativos_remachado_embrague_monto, 
                            $operativos_rectificacion_motor_frecuencia, 
                            $operativos_rectificacion_motor_cantidad, 
                            $operativos_rectificacion_motor_monto, 
                            $operativos_cambio_rodamiento_frecuencia, 
                            $operativos_cambio_rodamiento_cantidad, 
                            $operativos_cambio_rodamiento_monto, 
                            $operativos_reparaciones_menores_frecuencia, 
                            $operativos_reparaciones_menores_cantidad, 
                            $operativos_reparaciones_menores_monto, 
                            $operativos_imprevistos_frecuencia, 
                            $operativos_imprevistos_cantidad, 
                            $operativos_imprevistos_monto, 
                            $operativos_impuesto_propiedad_frecuencia, 
                            $operativos_impuesto_propiedad_cantidad, 
                            $operativos_impuesto_propiedad_monto, 
                            $operativos_soat_frecuencia, 
                            $operativos_soat_cantidad, 
                            $operativos_soat_monto, 
                            $operativos_roseta_inspeccion_frecuencia, 
                            $operativos_roseta_inspeccion_cantidad, 
                            $operativos_roseta_inspeccion_monto, 
                            $operativos_otro_transporte_libre1_texto, 
                            $operativos_otro_transporte_libre1_frecuencia, 
                            $operativos_otro_transporte_libre1_cantidad, 
                            $operativos_otro_transporte_libre1_monto, 
                            $operativos_otro_transporte_libre2_texto, 
                            $operativos_otro_transporte_libre2_frecuencia, 
                            $operativos_otro_transporte_libre2_cantidad, 
                            $operativos_otro_transporte_libre2_monto, 
                            $operativos_otro_transporte_libre3_texto, 
                            $operativos_otro_transporte_libre3_frecuencia, 
                            $operativos_otro_transporte_libre3_cantidad, 
                            $operativos_otro_transporte_libre3_monto, 
                            $operativos_otro_transporte_libre4_texto, 
                            $operativos_otro_transporte_libre4_frecuencia, 
                            $operativos_otro_transporte_libre4_cantidad, 
                            $operativos_otro_transporte_libre4_monto, 
                            $operativos_otro_transporte_libre5_texto, 
                            $operativos_otro_transporte_libre5_frecuencia, 
                            $operativos_otro_transporte_libre5_cantidad, 
                            $operativos_otro_transporte_libre5_monto, 
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
    
    // Final del Formulario
    
    function update_final_formulario(
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            )
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET
                
                        prospecto_fecha_conclusion = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE prospecto_id = ?";

            $this->db->query($sql, array(
                            $accion_fecha,
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
    
    // Guardar Paso Siguiente del formulario
    
    function Guardar_PasoActual($siguiente_vista, $accion_usuario, $accion_fecha, $estructura_id)
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET
                
                        prospecto_ultimo_paso = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE prospecto_id = ?";

            $this->db->query($sql, array($siguiente_vista, $accion_usuario, $accion_fecha, $estructura_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

    }
    
    // Auxiliares
    
    function ObtenerDatosHito($etapa, $codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT hito_id, etapa_id, prospecto_id, hito_fecha_ini, hito_fecha_fin, hito_finalizo FROM hito WHERE etapa_id=? AND prospecto_id=? ";           

            $consulta = $this->db->query($sql, array($etapa, $codigo_prospecto));

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
    
    // Página Principal
    
    function GetProspectoPrincipal($prospecto_id)
    {        
        try 
        {            
            $sql = "SELECT prospecto_id, general_depende FROM prospecto WHERE general_categoria='1' AND prospecto_id=? ";

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    function GetProspectoNombreDepende($prospecto_id)
    {        
        try 
        {            
            $sql = "SELECT prospecto_id, general_solicitante FROM prospecto WHERE prospecto_id=(SELECT general_depende FROM prospecto WHERE general_categoria=2 AND prospecto_id=?) ";

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    function GetProspectoDepende($prospecto_id)
    {        
        try 
        {            
            $sql = "SELECT prospecto_id, general_depende FROM prospecto WHERE general_categoria='2' AND prospecto_id=? ";

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    function select_info_secundarias_ordenado($prospecto_id, $activo=1)
    {        
        try 
        {
            if($prospecto_id == -1)
            {
                $sql = "SELECT prospecto_evaluacion, prospecto_id, ejecutivo_id, tipo_persona_id, camp_id, prospecto_consolidado, prospecto_ultimo_paso, general_categoria, general_depende, general_solicitante, general_ci, general_ci_extension, general_telefono, prospecto_principal, general_actividad, general_destino, operacion_antiguedad, operacion_tiempo FROM prospecto WHERE prospecto_activo=$activo ORDER BY prospecto_principal DESC, general_categoria ASC ";
            }
            else
            {
                $sql = "SELECT prospecto_evaluacion, prospecto_id, ejecutivo_id, tipo_persona_id, camp_id, prospecto_consolidado, prospecto_ultimo_paso, general_categoria, general_depende, general_solicitante, general_ci, general_ci_extension, general_telefono, prospecto_principal, general_actividad, general_destino, operacion_antiguedad, operacion_tiempo FROM prospecto WHERE prospecto_activo=$activo AND prospecto_principal=0 AND (prospecto_id=? OR general_depende=?) ORDER BY prospecto_principal DESC, general_categoria ASC";
            }
            
            $consulta = $this->db->query($sql, array($prospecto_id, $prospecto_id));

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
    
    function select_info_dependencia_ordenado($prospecto_id, $activo=1)
    {        
        try 
        {
            if($prospecto_id == -1)
            {
                $sql = "SELECT prospecto_principal, prospecto_evaluacion, prospecto_id, ejecutivo_id, tipo_persona_id, camp_id, prospecto_consolidado, prospecto_ultimo_paso, general_categoria, general_depende, general_solicitante, general_ci, general_ci_extension, general_telefono, prospecto_principal, general_actividad, general_destino, operacion_antiguedad, operacion_tiempo FROM prospecto WHERE prospecto_activo=$activo ORDER BY prospecto_principal DESC, general_categoria ASC ";
            }
            else
            {
                $sql = "SELECT prospecto_principal, prospecto_evaluacion, prospecto_id, ejecutivo_id, tipo_persona_id, camp_id, prospecto_consolidado, prospecto_ultimo_paso, general_categoria, general_depende, general_solicitante, general_ci, general_ci_extension, general_telefono, prospecto_principal, general_actividad, general_destino, operacion_antiguedad, operacion_tiempo FROM prospecto WHERE prospecto_activo=$activo AND (prospecto_id=? OR general_depende=?) ORDER BY prospecto_principal DESC, general_categoria ASC ";
            }
            
            $consulta = $this->db->query($sql, array($prospecto_id, $prospecto_id));

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
    
    function select_info_dependencia($prospecto_id, $activo=1)
    {        
        try 
        {
            if($prospecto_id == -1)
            {
                $sql = "SELECT onboarding, onboarding_codigo, prospecto_evaluacion, prospecto_id, ejecutivo_id, tipo_persona_id, camp_id, prospecto_consolidado, prospecto_ultimo_paso, general_categoria, general_depende, general_solicitante, general_ci, general_ci_extension, general_telefono, prospecto_principal, prospecto_num_proceso FROM prospecto WHERE prospecto_activo=$activo ORDER BY prospecto_id ASC ";
            }
            else
            {
                $sql = "SELECT onboarding, onboarding_codigo, prospecto_evaluacion, prospecto_id, ejecutivo_id, tipo_persona_id, camp_id, prospecto_consolidado, prospecto_ultimo_paso, general_categoria, general_depende, general_solicitante, general_ci, general_ci_extension, general_telefono, prospecto_principal, prospecto_num_proceso FROM prospecto WHERE prospecto_activo=$activo AND (prospecto_id=? OR general_depende=?) ORDER BY prospecto_id ASC ";
            }
            
            $consulta = $this->db->query($sql, array($prospecto_id, $prospecto_id));

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
    
    function Costos_Registro($estructura_id, $codigo_detalle, $detalle_descripcion, $detalle_cantidad, $detalle_unidad, $detalle_costo_unitario, $detalle_costo_medida_convertir, $detalle_costo_medida_unidad, $detalle_costo_medida_precio, $detalle_costo_unidad_medida_uso, $detalle_costo_unidad_medida_cantidad, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            if($codigo_detalle == -1)
            {
                // Nuevo
                $sql = " INSERT INTO producto_detalle(producto_id, detalle_descripcion, detalle_cantidad, detalle_unidad, detalle_costo_unitario, detalle_costo_medida_convertir, detalle_costo_medida_unidad, detalle_costo_medida_precio, detalle_costo_unidad_medida_uso, detalle_costo_unidad_medida_cantidad, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $consulta = $this->db->query($sql, array($estructura_id, $detalle_descripcion, $detalle_cantidad, $detalle_unidad, $detalle_costo_unitario, $detalle_costo_medida_convertir, $detalle_costo_medida_unidad, $detalle_costo_medida_precio, $detalle_costo_unidad_medida_uso, $detalle_costo_unidad_medida_cantidad, $nombre_usuario, $fecha_actual));
            }
            else
            {
                // Actualizar
                $sql = " UPDATE producto_detalle SET detalle_descripcion=?, detalle_cantidad=?, detalle_unidad=?, detalle_costo_unitario=?, detalle_costo_medida_convertir=?, detalle_costo_medida_unidad=?, detalle_costo_medida_precio=?, detalle_costo_unidad_medida_uso=?, detalle_costo_unidad_medida_cantidad=?, accion_usuario=?, accion_fecha=? WHERE producto_id=? AND detalle_id=?";
                $consulta = $this->db->query($sql, array($detalle_descripcion, $detalle_cantidad, $detalle_unidad, $detalle_costo_unitario, $detalle_costo_medida_convertir, $detalle_costo_medida_unidad, $detalle_costo_medida_precio, $detalle_costo_unidad_medida_uso, $detalle_costo_unidad_medida_cantidad, $nombre_usuario, $fecha_actual, $estructura_id, $codigo_detalle));
            }
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function Costos_Eliminar($estructura_id, $codigo_detalle)
    {        
        try 
        {
            $sql = " DELETE FROM producto_detalle WHERE producto_id=? AND detalle_id=?";
            $consulta = $this->db->query($sql, array($estructura_id, $codigo_detalle));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Nuevo Lead
    
    function NuevoLead(
                            $codigo_rubro,
                            $ejecutivo_id,
                            $general_solicitante,
                            $general_telefono,
                            $general_email,
                            $general_direccion,
                            $general_actividad,
                            $general_destino,
                            $general_ci,
                            $general_ci_extension,
                            $general_interes,
                            $accion_usuario,
                            $accion_fecha
                            )
    {        
        try 
        {            
            $sql = "INSERT INTO prospecto
                
                        (camp_id, 
                        tipo_persona_id, 
                        empresa_id,
                        general_categoria,
                        ejecutivo_id,
                        general_depende, 
                        prospecto_principal,
                        general_solicitante,
                        general_telefono,
                        general_email,
                        general_direccion,
                        general_actividad,
                        general_destino,
                        general_ci,
                        general_ci_extension,
                        general_interes,
                        accion_usuario,
                        accion_fecha,
                        prospecto_fecha_asignacion)
                        
                    VALUES
                    
                        (?,
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
                            $codigo_rubro,
                            $codigo_rubro,
                            -1,
                            1,
                            $ejecutivo_id,
                            -1,
                            1,
                            $general_solicitante,
                            $general_telefono,
                            $general_email,
                            $general_direccion,
                            $general_actividad,
                            $general_destino,
                            $general_ci,
                            $general_ci_extension,
                            $general_interes,
                            $accion_usuario,
                            $accion_fecha,
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
    
    function getUsuarioCriterio($criterio, $codigo)
    {        
        try 
        {
            if($criterio == 'prospecto')
            {
                $sql = "SELECT p.prospecto_id, u.usuario_user, u.usuario_id
                        FROM prospecto p
                        INNER JOIN ejecutivo e ON e.ejecutivo_id=p.ejecutivo_id
                        INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                        WHERE p.prospecto_id=? ";
            }
            if($criterio == 'ejecutivo')
            {
                $sql = "SELECT e.ejecutivo_id, u.usuario_user, u.usuario_id, er.estructura_regional_id
                        FROM ejecutivo e 
                        INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                        INNER JOIN estructura_agencia ea ON ea.estructura_agencia_id=u.estructura_agencia_id
                        INNER JOIN estructura_regional er ON er.estructura_regional_id=ea.estructura_regional_id
                        WHERE e.ejecutivo_id=? ";
            }
            
            $consulta = $this->db->query($sql, array($codigo));

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
    
    function UpdateTransfProspecto($codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id, $visita_codigo)
    {        
        try 
        {
            $this->load->model('mfunciones_generales');
            
            // Se verifica el ID de Ejecutivo del usuario
            $arrVerifica = $this->VerificarUsuarioEjecutivo($codigo_usuario);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrVerifica);
            
            $sql1 = "UPDATE prospecto SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? AND prospecto_id=?";
            $consulta1 = $this->db->query($sql1, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id, $visita_codigo));
            
            $sql2 = "UPDATE calendario SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? AND cal_tipo_visita=1 AND cal_id_visita=?";
            $consulta2 = $this->db->query($sql2, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id, $visita_codigo));
            
            $sql3 = "UPDATE empresa SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? ";
            $consulta3 = $this->db->query($sql3, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id));

            // Se consulta si es un registro Tercero para actualizarlo
            $codigo_terceros = $this->mfunciones_generales->ObtenerCodigoTercerosProspecto($visita_codigo);
            
            if($codigo_terceros != 0)
            {
                $sql4 = "UPDATE terceros SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? AND terceros_id=?";
                $consulta4 = $this->db->query($sql4, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id, $codigo_terceros));
            }
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateTransfMantenimiento($codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id, $visita_codigo)
    {        
        try 
        {
            $this->load->model('mfunciones_generales');
            
            // Se verifica el ID de Ejecutivo del usuario
            $arrVerifica = $this->VerificarUsuarioEjecutivo($codigo_usuario);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrVerifica);
            
            $sql1 = "UPDATE mantenimiento SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? AND mant_id=? ";
            $consulta1 = $this->db->query($sql1, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id, $visita_codigo));
            
            $sql2 = "UPDATE calendario SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? AND cal_tipo_visita=2 AND cal_id_visita=?";
            $consulta2 = $this->db->query($sql2, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id, $visita_codigo));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function GetSolicitanteData($codigo_prospecto, $campo)
    {
        try 
        {
            $sql = "SELECT $campo FROM prospecto WHERE prospecto_id=?"; 

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    
    function ObtenerInfoProspecto($codigo_prospecto) 
    {        
        try 
        {
            $sql = "SELECT c.camp_nombre, CONCAT_WS(' ', u.usuario_nombres, u.usuario_app, u.usuario_apm) as 'usuario_nombre', er.estructura_regional_nombre, p.*
                    FROM prospecto p
                    INNER JOIN campana c ON c.camp_id=p.camp_id
                    INNER JOIN ejecutivo e ON e.ejecutivo_id=p.ejecutivo_id
                    INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                    INNER JOIN estructura_regional er ON er.estructura_regional_id=p.codigo_agencia_fie
                    WHERE p.prospecto_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    //-- Otros Ingresos
    
    function ObtenerListaOtrosIngresos($codigo_prospecto, $otros_id)
    {        
        try 
        {
            if($otros_id == -1)
            {
                $sql = "SELECT otros_id, prospecto_id, otros_descripcion_fuente, otros_descripcion_respaldo, otros_monto FROM transporte_otros_ingresos WHERE prospecto_id=? ORDER BY otros_id ASC "; 
            }
            else
            {
                $sql = "SELECT otros_id, prospecto_id, otros_descripcion_fuente, otros_descripcion_respaldo, otros_monto FROM transporte_otros_ingresos WHERE prospecto_id=? AND otros_id=? ORDER BY otros_id ASC "; 
            }
            
            $consulta = $this->db->query($sql, array($codigo_prospecto, $otros_id));

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
    
    function Otros_Ingresos_Registro($estructura_id, $otros_id, $otros_descripcion_fuente, $otros_descripcion_respaldo, $otros_monto, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            if($otros_id == -1)
            {
                // Nuevo
                $sql = " INSERT INTO transporte_otros_ingresos(prospecto_id, otros_descripcion_fuente, otros_descripcion_respaldo, otros_monto, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?)";
                $consulta = $this->db->query($sql, array($estructura_id, $otros_descripcion_fuente, $otros_descripcion_respaldo, $otros_monto, $nombre_usuario, $fecha_actual));
            }
            else
            {
                // Actualizar
                $sql = " UPDATE transporte_otros_ingresos SET otros_descripcion_fuente=?, otros_descripcion_respaldo=?, otros_monto=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? AND otros_id=?";
                $consulta = $this->db->query($sql, array($otros_descripcion_fuente, $otros_descripcion_respaldo, $otros_monto, $nombre_usuario, $fecha_actual, $estructura_id, $otros_id));
            }
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function Otros_Ingresos_Eliminar($estructura_id, $otros_id)
    {        
        try 
        {
            $sql = " DELETE FROM transporte_otros_ingresos WHERE prospecto_id=? AND otros_id=?";
            $consulta = $this->db->query($sql, array($estructura_id, $otros_id));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    //-- Pasivos
    
    function ObtenerListaPasivos($codigo_prospecto, $pasivos_id)
    {        
        try 
        {
            if($pasivos_id == -1)
            {
                $sql = "SELECT pasivos_id, prospecto_id, pasivo_acreedor, pasivo_tipo, pasivo_saldo, pasivo_periodo, pasivo_cuota_periodica, pasivo_cuota_mensual, pasivo_rfto, pasivo_destino, accion_usuario, accion_fecha FROM pasivos WHERE prospecto_id=? ORDER BY pasivos_id ASC "; 
            }
            else
            {
                $sql = "SELECT pasivos_id, prospecto_id, pasivo_acreedor, pasivo_tipo, pasivo_saldo, pasivo_periodo, pasivo_cuota_periodica, pasivo_cuota_mensual, pasivo_rfto, pasivo_destino, accion_usuario, accion_fecha FROM pasivos WHERE prospecto_id=? AND pasivos_id=? ORDER BY pasivos_id ASC "; 
            }
            
            $consulta = $this->db->query($sql, array($codigo_prospecto, $pasivos_id));

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
    
    function Pasivos_Registro($estructura_id, $pasivos_id, $pasivo_acreedor, $pasivo_tipo, $pasivo_saldo, $pasivo_periodo, $pasivo_cuota_periodica, $pasivo_cuota_mensual, $pasivo_rfto, $pasivo_destino, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            if($pasivos_id == -1)
            {
                // Nuevo
                $sql = " INSERT INTO pasivos(prospecto_id, pasivo_acreedor, pasivo_tipo, pasivo_saldo, pasivo_periodo, pasivo_cuota_periodica, pasivo_cuota_mensual, pasivo_rfto, pasivo_destino, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $consulta = $this->db->query($sql, array($estructura_id, $pasivo_acreedor, $pasivo_tipo, $pasivo_saldo, $pasivo_periodo, $pasivo_cuota_periodica, $pasivo_cuota_mensual, $pasivo_rfto, $pasivo_destino, $nombre_usuario, $fecha_actual));
            }
            else
            {
                // Actualizar
                $sql = " UPDATE pasivos SET pasivo_acreedor=?, pasivo_tipo=?, pasivo_saldo=?, pasivo_periodo=?, pasivo_cuota_periodica=?, pasivo_cuota_mensual=?, pasivo_rfto=?, pasivo_destino=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? AND pasivos_id=?";
                $consulta = $this->db->query($sql, array($pasivo_acreedor, $pasivo_tipo, $pasivo_saldo, $pasivo_periodo, $pasivo_cuota_periodica, $pasivo_cuota_mensual, $pasivo_rfto, $pasivo_destino, $nombre_usuario, $fecha_actual, $estructura_id, $pasivos_id));
            }
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function Pasivos_Eliminar($estructura_id, $pasivos_id)
    {        
        try 
        {
            $sql = " DELETE FROM pasivos WHERE prospecto_id=? AND pasivos_id=?";
            $consulta = $this->db->query($sql, array($estructura_id, $pasivos_id));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    /*************** UPDATE FORMULARIOS FIE - FIN ****************************/
    
    /*************** AFILIACIÓN POR TERCEROS - INICIO ****************************/
    
    function InsertarRelacionTercerosProspecto($ejecutivo_id, $tipo_persona_id, $onboarding_codigo, $general_ci, $general_solicitante, $general_telefono, $direccion_email, $geo_prospecto, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO prospecto (ejecutivo_id, tipo_persona_id, camp_id, onboarding, onboarding_codigo, prospecto_fecha_asignacion, prospecto_etapa, prospecto_etapa_fecha, prospecto_ultimo_paso, prospecto_principal, general_ci, general_solicitante, general_telefono, general_email, prospecto_checkin_geo, accion_usuario, accion_fecha) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";

            $consulta = $this->db->query($sql, array($ejecutivo_id, $tipo_persona_id, $tipo_persona_id, 1, $onboarding_codigo, $accion_fecha, 1, $accion_fecha, 'view_datos_personales', 1, $general_ci, $general_solicitante, $general_telefono, $direccion_email, $geo_prospecto, $accion_usuario, $accion_fecha));
            
            $listaResultados = $this->db->insert_id();
            
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
        
    }
    
    function EliminarRegistroTercero($tercero_id)
    {        
        try 
        {
            $sql1 = "DELETE FROM terceros_documento WHERE terceros_id=? ";

            $consulta1 = $this->db->query($sql1, array($tercero_id));
            
            $sql2 = "DELETE FROM terceros WHERE terceros_id=? ";

            $consulta2 = $this->db->query($sql2, array($tercero_id));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarDocumentoTercero($prospecto_id, $documento_id, $prospecto_documento_pdf, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO terceros_documento(terceros_id, documento_id, terceros_documento_pdf, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?) ";

            $consulta = $this->db->query($sql, array($prospecto_id, $documento_id, $prospecto_documento_pdf, $accion_usuario, $accion_fecha));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarProspecto_Terceros($identificador_ejecutivo, $identificador_afiliador, $tipo_persona_id, $codigo_agencia_fie, $rekognition, $similaridad_obtenida, 
                
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
                $di_fecha_nacimiento, 
                $di_fecha_vencimiento, 
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
                $emp_fecha_ingreso, 
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
            
                $accion_usuario, $accion_fecha, $accion_fecha2)
    {        
        try 
        {            
            $sql = "INSERT INTO terceros

                    (
                    ejecutivo_id, afiliador_id, tipo_persona_id, codigo_agencia_fie, terceros_rekognition, terceros_rekognition_similarity,
                    
                    tipo_cuenta,
                    direccion_email, 
                    coordenadas_geo_dom, 
                    coordenadas_geo_trab, 
                    envio, 
                    cI_numeroraiz, 
                    cI_complemento, 
                    cI_lugar_emisionoextension, 
                    cI_confirmacion_id, 
                    di_primernombre, 
                    di_segundo_otrosnombres, 
                    di_primerapellido, 
                    di_segundoapellido, 
                    di_fecha_nacimiento, 
                    di_fecha_vencimiento, 
                    di_indefinido, 
                    di_genero, 
                    di_estadocivil, 
                    di_apellido_casada, 
                    dd_profesion, 
                    dd_nivel_estudios, 
                    dd_dependientes, 
                    dd_proposito_rel_comercial, 
                    dec_ingresos_mensuales, 
                    dec_nivel_egresos, 
                    dir_tipo_direccion, 
                    dir_departamento, 
                    dir_provincia, 
                    dir_localidad_ciudad, 
                    dir_barrio_zona_uv, 
                    dir_ubicacionreferencial, 
                    dir_av_calle_pasaje, 
                    dir_edif_cond_urb, 
                    dir_numero, 
                    dir_departamento_neg, 
                    dir_provincia_neg, 
                    dir_localidad_ciudad_neg, 
                    dir_barrio_zona_uv_neg, 
                    dir_ubicacionreferencial_neg, 
                    dir_av_calle_pasaje_neg, 
                    dir_edif_cond_urb_neg, 
                    dir_numero_neg, 
                    dir_tipo_telefono, 
                    dir_notelefonico, 
                    ae_sector_economico, 
                    ae_actividad_fie, 
                    ae_ambiente, 
                    emp_nombre_empresa, 
                    emp_direccion_trabajo, 
                    emp_telefono_faxtrabaj, 
                    emp_tipo_empresa, 
                    emp_antiguedad_empresa, 
                    emp_codigo_actividad, 
                    emp_descripcion_cargo, 
                    emp_fecha_ingreso, 
                    rp_nombres, 
                    rp_primer_apellido, 
                    rp_segundo_apellido, 
                    rp_direccion, 
                    rp_notelefonicos, 
                    rp_nexo_cliente, 
                    con_primer_nombre, 
                    con_segundo_nombre, 
                    con_primera_pellido, 
                    con_segundoa_pellido, 
                    con_acteconomica_principal, 
                    ddc_ciudadania_usa, 
                    ddc_motivo_permanencia_usa, 
                    
                    ws_cobis_codigo_resultado,
                    ws_segip_codigo_resultado,
                    ws_segip_flag_verificacion,

                    ws_selfie_codigo_resultado,
                    ws_ocr_codigo_resultado,
                    
                    ws_ocr_name_valor,
                    ws_ocr_name_similar,

                    accion_usuario, accion_fecha, terceros_fecha
                    ) 

                    VALUES (
                    ?, ?, ?, ?, ?, ?, 
                    
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
                    
                    ?, ?, ?
                    ) ";
            

            $this->db->query($sql, array($identificador_ejecutivo, $identificador_afiliador, $tipo_persona_id, $codigo_agencia_fie, $rekognition, $similaridad_obtenida, 
                
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
                $di_fecha_nacimiento, 
                $di_fecha_vencimiento, 
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
                $emp_fecha_ingreso, 
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
                
                $accion_usuario, $accion_fecha, $accion_fecha2));

            $listaResultados = $this->db->insert_id();
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

            return $listaResultados;
    }
    
    function ObtenerSolicitudTerceros_bk($codigo, $estado)
    {        
        try 
        {
            $lista_region = $this->mfunciones_generales->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
            
            if($codigo == -1)
            {
                $sql = "SELECT t.*
                        FROM terceros t 
                        INNER JOIN ejecutivo e ON e.ejecutivo_id=t.ejecutivo_id
                        INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                        INNER JOIN estructura_agencia ea ON ea.estructura_agencia_id=u.estructura_agencia_id
                        INNER JOIN estructura_regional er ON er.estructura_regional_id=ea.estructura_regional_id
                        WHERE er.estructura_regional_id IN (" . $lista_region->region_id . ") AND t.terceros_estado=? ORDER BY t.terceros_fecha DESC "; 
            }
            else
            {
                $sql = "SELECT t.*
                        FROM terceros t 
                        INNER JOIN ejecutivo e ON e.ejecutivo_id=t.ejecutivo_id
                        INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                        INNER JOIN estructura_agencia ea ON ea.estructura_agencia_id=u.estructura_agencia_id
                        INNER JOIN estructura_regional er ON er.estructura_regional_id=ea.estructura_regional_id
                        WHERE er.estructura_regional_id IN (" . $lista_region->region_id . ") AND t.terceros_estado=? AND terceros_id=? ORDER BY t.terceros_fecha DESC "; 
            }

            $consulta = $this->db->query($sql, array($estado, $codigo));

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
    
    function ObtenerEstadoTerceros($codigo, $estado=-1)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT terceros_id, terceros_estado
                        FROM terceros
                        WHERE terceros_id=? ";
            }
            else
            {
                $sql = "SELECT terceros_id, terceros_estado
                        FROM terceros
                        WHERE terceros_id=? AND terceros_estado=? "; 
            }

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
    
    function ObtenerSolicitudTercerosRegion($codigo, $estado, $lista_region)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT t.*
                        FROM terceros t 
                        INNER JOIN ejecutivo e ON e.ejecutivo_id=t.ejecutivo_id
                        INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                        INNER JOIN estructura_agencia ea ON ea.estructura_agencia_id=u.estructura_agencia_id
                        INNER JOIN estructura_regional er ON er.estructura_regional_id=ea.estructura_regional_id
                        WHERE t.terceros_estado=? AND t.codigo_agencia_fie IN ($lista_region) ORDER BY t.terceros_fecha DESC "; 
            }
            else
            {
                $sql = "SELECT t.*
                        FROM terceros t 
                        INNER JOIN ejecutivo e ON e.ejecutivo_id=t.ejecutivo_id
                        INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                        INNER JOIN estructura_agencia ea ON ea.estructura_agencia_id=u.estructura_agencia_id
                        INNER JOIN estructura_regional er ON er.estructura_regional_id=ea.estructura_regional_id
                        WHERE t.terceros_estado=? AND t.codigo_agencia_fie IN ($lista_region) AND terceros_id=? ORDER BY t.terceros_fecha DESC "; 
            }

            $consulta = $this->db->query($sql, array($estado, $codigo));

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
    
    function ObtenerBandejaAgenciaRegion($codigo, $estado, $lista_region)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT t.*
                        FROM terceros t 
                        INNER JOIN ejecutivo e ON e.ejecutivo_id=t.ejecutivo_id
                        INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                        INNER JOIN estructura_agencia ea ON ea.estructura_agencia_id=u.estructura_agencia_id
                        INNER JOIN estructura_regional er ON er.estructura_regional_id=ea.estructura_regional_id
                        WHERE t.terceros_estado IN ($estado) AND t.codigo_agencia_fie IN ($lista_region) ORDER BY t.terceros_fecha DESC "; 
            }
            else
            {
                $sql = "SELECT t.*
                        FROM terceros t 
                        INNER JOIN ejecutivo e ON e.ejecutivo_id=t.ejecutivo_id
                        INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                        INNER JOIN estructura_agencia ea ON ea.estructura_agencia_id=u.estructura_agencia_id
                        INNER JOIN estructura_regional er ON er.estructura_regional_id=ea.estructura_regional_id
                        WHERE t.terceros_estado=? AND t.codigo_agencia_fie IN ($lista_region) AND terceros_id=? ORDER BY t.terceros_fecha DESC "; 
            }

            $consulta = $this->db->query($sql, array($estado, $codigo));

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
    
    function ObtenerTercerosCSV()
    {        
        try 
        {
            $sql = "SELECT 
            terceros_id AS codigo_initium, 
            tipo_id_tipos_identificacion, 
            cI_numeroraiz, 
            cI_complemento, 
            cI_lugar_emisionoextension, 
            cI_confirmacion_id, 
            cI_sufijo, 
            cI_idduplicado, 
            cI_duplicada_numeroraiz, 
            cI_duplicada_lugar_emisionoextension, 
            cI_duplicada_confirmacion_id, 
            cI_duplicada_sufijo, 
            cI_duplicada_idduplicado, 
            ci_extransjero_numeroraiz, 
            ci_extransjero_complemento, 
            ci_extransjero_confirmacion_id, 
            ci_extransjero_idduplicado, 
            ci_consular_prefijoa, 
            ci_consular_codigo_id_obligado_rree, 
            ci_consular_confirmacion_id, 
            ci_consular_idduplicado, 
            c_diplomatico_prefijoa, 
            c_diplomatico_codigo_id_obligado_rree, 
            c_diplomatico_confirmacion_id, 
            c_diplomatico_prefijo, 
            c_diplomatico_idduplicado, 
            c_consular_prefijoa, 
            c_consular_codigo_id_obligado_rree, 
            c_consular_confirmacion_id, 
            c_consular_idduplicado, 
            c_prefijoa, 
            c_codigo_ide_obligado_rree, 
            c_confirmacion_id, 
            c_idduplicado, 
            ca_prefijoa, 
            ca_codigo_id_obligado_rree, 
            ca_confirmacion_id, 
            ca_idduplicado, 
            cb_codigo_id_obligador_ree, 
            cb_confirmacion_id, 
            cb_idduplicado, 
            cc_prefijoa, 
            cc_codigo_ide_obligado_rree, 
            cc_confirmacion_id, 
            cc_idduplicado, 
            cd_prefijoa, 
            cd_codigo_id_obligado_rree, 
            cd_confirmacion_id, 
            cd_idduplicado, 
            doc_extranjero_cedula, 
            doc_extranjero_confirmacion_id, 
            doc_extranjero_idduplicado, 
            pas_cedula, 
            pas_confirmacion_id, 
            di_tipo_id, 
            di_numero_id, 
            di_estado_cliente, 
            di_primernombre, 
            di_segundo_otrosnombres, 
            di_primerapellido, 
            di_segundoapellido, 
            di_nacionalidad, 
            DATE_FORMAT(di_fecha_nacimiento,'%d/%m/%Y') AS di_fecha_nacimiento, 
            DATE_FORMAT(di_fecha_vencimiento,'%d/%m/%Y') AS di_fecha_vencimiento, 
            di_indefinido, 
            di_genero, 
            di_estadocivil, 
            di_apellido_casada, 
            di_conocidocomo, 
            di_sucursal_gestion, 
            di_conyuge, 
            dd_pais_residencia, 
            dd_profesion, 
            dd_nivel_estudios, 
            dd_tipo_vivienda, 
            dd_dependientes, 
            dd_tipo_persona, 
            dd_planilla, 
            dd_tienediscapacidad, 
            dd_tipo_discapacidad, 
            dd_cedula_discapacidad, 
            dd_proposito_rel_comercial, 
            dec_numero_nit, 
            DATE_FORMAT(dec_fecha_venc_nit,'%d/%m/%Y') AS dec_fecha_venc_nit, 
            dec_retencion_impuesto, 
            dec_oportuno_pago_asfi, 
            'CL' AS dec_relacion_banco, 
            dec_comentarios, 
            dec_ingresos_mensuales, 
            dec_nivel_egresos, 
            dec_promotor, 
            dec_noidtutor, 
            dec_tutor, 
            dec_calif_cliente_interna, 
            dec_tiene_ref_economicas, 
            dec_categoria_aml, 
            dej_oficial_negocio, 
            dir_tipo_direccion, 
            dir_nro_direccion, 
            dir_tipo_propiedad, 
            dir_rural_urbano, 
            dir_pais, 
            dir_departamento, 
            dir_provincia, 
            dir_localidad_ciudad, 
            dir_barrio_zona_uv, 
            dir_ubicacionreferencial, 
            dir_av_calle_pasaje, 
            dir_edif_cond_urb, 
            dir_numero, 
            dir_presento_ssbb, 
            dir_principal, 
            dir_correspondencia, 
            dir_telefonos, 
            dir_notelefono, 
            dir_tipo_telefono, 
            dir_codigo_area, 
            dir_notelefonico, 
            ae_estado, 
            ae_cliente, 
            ae_sector_economico, 
            ae_subsector_economico, 
            ae_actividad_ocupacion, 
            ae_actividad_fie, 
            ae_aclaracion_fie, 
            ae_ambiente, 
            'OT' AS ae_tipo_propiedad, 
            ae_fuente_ingreso, 
            ae_aclaracion_caedec, 
            ae_descactividad, 
            ae_consecutivo, 
            ae_principal, 
            '' AS ae_fecha_inicio_act, 
            ae_antiguedad_actividad, 
            ae_nro_empleados, 
            ae_autorizado, 
            'Falso' AS ae_afiliado, 
            ae_lugar_afiliacion, 
            ae_horarioinicio, 
            ae_horariofinal, 
            ae_dia_atencion_trabajo, 
            re_noreferencia, 
            ban_entidad_financiera, 
            ban_nrocuenta, 
            ban_tipo_cuenta, 
            DATE_FORMAT(ban_fecha_apertura,'%d/%m/%Y') AS ban_fecha_apertura, 
            ban_observaciones, 
            com_nroreferencia, 
            com_establecimiento, 
            com_nacional_extranjera, 
            com_pais, 
            com_localidad_ciudad, 
            DATE_FORMAT(com_negociosdesde,'%d/%m/%Y') AS com_negociosdesde, 
            com_funcionario_inst, 
            DATE_FORMAT(com_fechareferencia,'%d/%m/%Y') AS com_fechareferencia, 
            com_codigo_area, 
            com_telefono, 
            com_moneda, 
            com_monto, 
            com_estado, 
            com_observaciones, 
            emp_nroempleo, 
            emp_empresa, 
            emp_nombre_empresa, 
            emp_descactivid_empresa, 
            emp_direccion_trabajo, 
            emp_telefono_faxtrabaj, 
            emp_tipo_empresa, 
            emp_antiguedad_empresa, 
            emp_codigo_actividad, 
            emp_funcionario_publico, 
            emp_noplanilla, 
            emp_cargo, 
            emp_descripcion_cargo, 
            DATE_FORMAT(emp_fecha_ingreso,'%d/%m/%Y') AS emp_fecha_ingreso, 
            '' AS emp_fecha_salida, 
            emp_actividad_economica, 
            rp_consecutivo, 
            rp_nombres, 
            rp_primer_apellido, 
            rp_segundo_apellido, 
            'Sin dirección registrada' AS rp_direccion, 
            rp_notelefonicos, 
            rp_domicilio, 
            rp_trabajo, 
            rp_celular, 
            rp_nexo_cliente, 
            idb_motivo_nocaptura_huella, 
            idb_huellas, 
            rec_relacion, 
            rec_izquierda, 
            rec_derecha, 
            rec_mensaje_completo, 
            rec_nro_atributo, 
            '' AS rec_descripcion_atributo, 
            con_tipo_id, 
            con_nro_id, 
            '' AS con_fecha_nacimiento, 
            con_primer_nombre, 
            con_segundo_nombre, 
            con_primera_pellido, 
            con_segundoa_pellido, 
            con_genero, 
            con_acteconomica_principal, 
            ddc_ciudadania_usa, 
            ddc_residio_usa_tiempo, 
            ddc_motivo_permanencia_usa, 
            ddc_nit_nss_usa, 
            pep_ocupo_funciones, 
            pep_cargo_enentidad_estatal, 
            pep_entidad, 
            pep_fuente_origen_losfondos, 
            DATE_FORMAT(pep_fecha_desde,'%d/%m/%Y') AS pep_fecha_desde, 
            DATE_FORMAT(pep_fecha_hasta,'%d/%m/%Y') AS pep_fecha_hasta, 
            pep_tipo_vinculo, 
            pep_allegado_pep, 
            pep_primer_nombre, 
            pep_segundo_nombre, 
            pep_primer_apellido, 
            pep_segundo_apellido, 
            pep_apellido_casada, 
            pep_nro_id, 
            pea_cargo_entidad_estatal, 
            pea_tipo_persona, 
            pea_persona, 
            pea_tipo_vinculo, 
            pea_primer_nombre_razonsocial, 
            pea_segundo_nombre, 
            pea_primer_apellido, 
            pea_segundoapellido, 
            pea_apellido_casada, 
            pea_nro_id,
            tipo_cuenta,
            direccion_email,
            monto_inicial,
            dir_pais_neg,
            dir_departamento_neg,
            dir_provincia_neg,
            dir_localidad_ciudad_neg,
            dir_barrio_zona_uv_neg,
            dir_av_calle_pasaje_neg,
            dir_edif_cond_urb_neg,
            dir_numero_neg

            FROM terceros
            WHERE terceros_estado=1
            ORDER BY terceros_fecha ASC LIMIT " . CANTIDAD_REGISTROS_CSV;

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
    
    function ObtenerSolicitudTerceros($codigo, $estado, $filtro='')
    {        
        try 
        {
            if($estado == 0)
            {
                $filtro_aux = ' LIMIT 5';
            }
            else
            {
                $filtro_aux = '';
            }
            
            if($codigo == -1)
            {
                $sql = "SELECT t.*
                        FROM terceros t 
                        INNER JOIN ejecutivo e ON e.ejecutivo_id=t.ejecutivo_id
                        INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                        INNER JOIN estructura_agencia ea ON ea.estructura_agencia_id=u.estructura_agencia_id
                        INNER JOIN estructura_regional er ON er.estructura_regional_id=ea.estructura_regional_id
                        WHERE t.terceros_estado=? $filtro ORDER BY t.terceros_fecha DESC $filtro_aux"; 
            }
            else
            {
                $sql = "SELECT t.*
                        FROM terceros t 
                        INNER JOIN ejecutivo e ON e.ejecutivo_id=t.ejecutivo_id
                        INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                        INNER JOIN estructura_agencia ea ON ea.estructura_agencia_id=u.estructura_agencia_id
                        INNER JOIN estructura_regional er ON er.estructura_regional_id=ea.estructura_regional_id
                        WHERE t.terceros_estado=? AND terceros_id=? ORDER BY t.terceros_fecha DESC "; 
            }
            
            $consulta = $this->db->query($sql, array($estado, $codigo));

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
    
    function ObtenerDetalleSolicitudTerceros($codigo)
    {        
        try 
        {
            $sql = "SELECT usuarios.usuario_id AS agente_codigo, CONCAT_WS(' ', usuarios.usuario_nombres, usuarios.usuario_app, usuarios.usuario_apm) AS agente_nombre, estructura_regional_nombre, t.* FROM terceros t 
                    INNER JOIN ejecutivo ON ejecutivo.ejecutivo_id=t.ejecutivo_id INNER JOIN usuarios ON usuarios.usuario_id=ejecutivo.usuario_id
                    INNER JOIN estructura_agencia ON estructura_agencia.estructura_agencia_id=usuarios.estructura_agencia_id INNER JOIN estructura_regional ON estructura_regional.estructura_regional_id=estructura_agencia.estructura_regional_id
                    WHERE t.terceros_id=? ORDER BY t.terceros_fecha DESC "; 

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function ObtenerDocumentosTercerosDigitalizar($codigo_terceros)
    {        
        try 
        {
            $sql = "SELECT d.documento_id, d.documento_nombre FROM tipo_persona_documento tpd INNER JOIN documento d ON d.documento_id=tpd.documento_id INNER JOIN tipo_persona tp ON tp.tipo_persona_id=tpd.tipo_persona_id INNER JOIN terceros t ON t.tipo_persona_id=tp.tipo_persona_id WHERE d.documento_vigente=1 AND t.terceros_id=? ORDER BY d.documento_nombre ASC ";

            $consulta = $this->db->query($sql, array($codigo_terceros));

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
    
    function ObtenerDocumentosTercerosCriterio($enviar, $mandatorio=-1)
    {        
        try 
        {
            if($mandatorio != -1)
            {
                $criterio = ' AND d.documento_mandatorio=' . $mandatorio;
            }
            else
            {
                $criterio = '';
            }
            $sql = "SELECT d.documento_codigo, d.documento_mandatorio, d.documento_id, d.documento_nombre FROM tipo_persona_documento tpd INNER JOIN documento d ON d.documento_id=tpd.documento_id INNER JOIN tipo_persona tp ON tp.tipo_persona_id=tpd.tipo_persona_id WHERE d.documento_vigente=1 AND tp.tipo_persona_id=5 AND d.documento_enviar=? $criterio ORDER BY d.documento_nombre ASC ";

            $consulta = $this->db->query($sql, array($enviar));

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
    
    function VerificaDocumentosTercerosDigitalizar($codigo_terceros, $codigo_documento)
    {        
        try 
        {
            $sql = "SELECT td.terceros_documento_pdf, CONCAT('ter_', t.terceros_id) as 'terceros_carpeta' FROM terceros_documento td, terceros t WHERE t.terceros_id=td.terceros_id AND td.terceros_id=? AND td.documento_id=? ORDER BY td.terceros_documento_id DESC LIMIT 1 ";

            $consulta = $this->db->query($sql, array($codigo_terceros, $codigo_documento));

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
    
    function CambiarAgenciaTerceros($codigo_agencia_fie, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE terceros SET codigo_agencia_fie=?, accion_usuario=?, accion_fecha=? WHERE terceros_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_agencia_fie, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function RechazarSolicitudTerceros($solicitud_observacion, $tipo_rechazo, $rechazado_envia, $rechazado_texto, $lista_documentos, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE terceros SET terceros_estado=?, terceros_observacion=?, rechazado=?, rechazado_envia=?, rechazado_texto=?, rechazado_docs_enviados=?, rechazado_fecha=?, rechazado_usuario=?, accion_usuario=?, accion_fecha=? WHERE terceros_id=? "; 
            
            $consulta = $this->db->query($sql, array($tipo_rechazo, $solicitud_observacion, 1, $rechazado_envia, $rechazado_texto, $lista_documentos, $fecha_actual, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function RechazarSolicitudTercerosApp($codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE terceros SET rechazado=1, terceros_estado=4, rechazado_fecha=?, rechazado_usuario=?, accion_usuario=?, accion_fecha=? WHERE terceros_id=? "; 
            
            $consulta = $this->db->query($sql, array($fecha_actual, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function CompletarSolicitudTerceros($completado_envia, $completado_texto, $lista_documentos, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE terceros SET terceros_estado=?, completado=?, completado_envia=?, completado_texto=?, completado_docs_enviados=?, completado_fecha=?, completado_usuario=?, accion_usuario=?, accion_fecha=? WHERE terceros_id=? "; 
            
            $consulta = $this->db->query($sql, array(5, 1, $completado_envia, $completado_texto, $lista_documentos, $fecha_actual, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function EntregarSolicitudTerceros($monto_inicial, $completado_texto, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE terceros SET terceros_estado=?, entregado=?, monto_inicial=?, entregado_texto=?, entregado_fecha=?, entregado_usuario=?, accion_usuario=?, accion_fecha=? WHERE terceros_id=? "; 
            
            $consulta = $this->db->query($sql, array(6, 1, $monto_inicial, $completado_texto, $fecha_actual, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ActualizarSolicitudTerceros($terceros_nombre_persona, $terceros_ci, $terceros_estado_civil, $terceros_email, $terceros_telefono, $terceros_nit, $terceros_pais, $terceros_profesion, $terceros_ingreso, $terceros_conyugue_nombre, $terceros_conyugue_actividad, $terceros_referencias, $terceros_actividad_principal, $terceros_lugar_trabajo, $terceros_cargo, $terceros_ano_ingreso, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE terceros SET 
                    terceros_nombre_persona=?, 
                    terceros_ci=?, 
                    terceros_estado_civil=?, 
                    terceros_email=?, 
                    terceros_telefono=?, 
                    terceros_nit=?, 
                    terceros_pais=?, 
                    terceros_profesion=?, 
                    terceros_ingreso=?, 
                    terceros_conyugue_nombre=?, 
                    terceros_conyugue_actividad=?, 
                    terceros_referencias=?, 
                    terceros_actividad_principal=?, 
                    terceros_lugar_trabajo=?, 
                    terceros_cargo=?, 
                    terceros_ano_ingreso=?, 
                    accion_usuario=?, 
                    accion_fecha=? 
                    WHERE terceros_id=? "; 
            
            
            
            
            $consulta = $this->db->query($sql, array($terceros_nombre_persona, $terceros_ci, $terceros_estado_civil, $terceros_email, $terceros_telefono, $terceros_nit, $terceros_pais, $terceros_profesion, $terceros_ingreso, $terceros_conyugue_nombre, $terceros_conyugue_actividad, $terceros_referencias, $terceros_actividad_principal, $terceros_lugar_trabajo, $terceros_cargo, $terceros_ano_ingreso, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function VerificaCuentaCOBIS($codigo)
    {        
        try 
        {
            $sql = "SELECT terceros_id, onboarding_numero_cuenta
                    FROM terceros
                    WHERE LOWER(REPLACE(onboarding_numero_cuenta, ' ', '')) = LOWER(REPLACE('$codigo', ' ', '')) "; 

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function CompletarCOBISTerceros($onboarding_numero_cuenta, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE terceros SET terceros_estado=2, onboarding_numero_cuenta=?, cobis=?, cobis_fecha=?, cobis_usuario=?, accion_usuario=?, accion_fecha=? WHERE terceros_id=? "; 
            
            $consulta = $this->db->query($sql, array($onboarding_numero_cuenta, 1, $fecha_actual, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function CompletarCOBISTerceros_fCOBIS($codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE terceros SET terceros_estado=2, cobis=?, cobis_fecha=?, cobis_usuario=?, accion_usuario=?, accion_fecha=? WHERE terceros_id=? "; 
            
            $consulta = $this->db->query($sql, array(1, $fecha_actual, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function AprobarSolicitudTerceros($codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE terceros SET terceros_estado=1, aprobado=?, aprobado_fecha=?, aprobado_usuario=?, accion_usuario=?, accion_fecha=? WHERE terceros_id=? "; 
            
            $consulta = $this->db->query($sql, array(1, $fecha_actual, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Control de Cambios 28/10/2020
    
    function ReservarSolicitudTerceros($nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE terceros SET terceros_estado=15, accion_usuario=?, accion_fecha=? WHERE terceros_id=? "; 
            
            $consulta = $this->db->query($sql, array($nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function AsignarProspecto_Tercero($afiliador_id, $accion_usuario, $accion_fecha, $codigo_prospecto)
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET prospecto_afiliador_id=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? ";

            $this->db->query($sql, array($afiliador_id, $accion_usuario, $accion_fecha, $codigo_prospecto));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerTerceros($afiliador_id)
    {        
        try 
        {
            if($afiliador_id == -1)
            {
                $sql = "SELECT afiliador_id, afiliador_nombre, afiliador_key, afiliador_responsable_nombre, afiliador_responsable_email, afiliador_responsable_contacto, afiliador_referencia_documento, afiliador_fecha_registro, accion_usuario, accion_fecha FROM afiliador WHERE afiliador_id>0 ORDER BY afiliador_id ASC "; 
            }
            else
            {
                $sql = "SELECT afiliador_id, afiliador_nombre, afiliador_key, afiliador_responsable_nombre, afiliador_responsable_email, afiliador_responsable_contacto, afiliador_referencia_documento, afiliador_fecha_registro, accion_usuario, accion_fecha FROM afiliador WHERE afiliador_id=? ORDER BY afiliador_id ASC "; 
            }

            $consulta = $this->db->query($sql, array($afiliador_id));

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
    
    function ObtenerTercerosKey($afiliador_id)
    {        
        try 
        {
            $sql = "SELECT afiliador_id, afiliador_nombre, afiliador_key, afiliador_responsable_nombre, afiliador_responsable_email, afiliador_responsable_contacto, afiliador_referencia_documento, afiliador_fecha_registro, accion_usuario, accion_fecha FROM afiliador WHERE afiliador_key=? ORDER BY afiliador_id ASC "; 

            $consulta = $this->db->query($sql, array($afiliador_id));

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
    
    function ObtenerTercerosUsuarios($afiliador_id)
    {        
        try 
        {
            $sql = "SELECT CONCAT_WS(' ', u.usuario_nombres, u.usuario_app, u.usuario_apm) as usuario_nombre, u.usuario_email
                    FROM ejecutivo e
                    INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                    WHERE e.afiliador_id=? AND u.usuario_activo=1 "; 

            $consulta = $this->db->query($sql, array($afiliador_id));

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
    
    function ObtenerTercerosAll($afiliador_id)
    {        
        try 
        {
            if($afiliador_id == -1)
            {
                $sql = "SELECT afiliador_id, afiliador_nombre, afiliador_key, afiliador_responsable_nombre, afiliador_responsable_email, afiliador_responsable_contacto, afiliador_referencia_documento, afiliador_fecha_registro, accion_usuario, accion_fecha FROM afiliador ORDER BY afiliador_id ASC "; 
            }
            else
            {
                $sql = "SELECT afiliador_id, afiliador_nombre, afiliador_key, afiliador_responsable_nombre, afiliador_responsable_email, afiliador_responsable_contacto, afiliador_referencia_documento, afiliador_fecha_registro, accion_usuario, accion_fecha FROM afiliador WHERE afiliador_id=? ORDER BY afiliador_id ASC "; 
            }

            $consulta = $this->db->query($sql, array($afiliador_id));

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
    
    function InsertTerceros($afiliador_nombre, $afiliador_responsable_nombre, $afiliador_responsable_email, $afiliador_responsable_contacto, $afiliador_referencia_documento, $afiliador_fecha_registro, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO afiliador(afiliador_nombre, afiliador_responsable_nombre, afiliador_responsable_email, afiliador_responsable_contacto, afiliador_referencia_documento, afiliador_fecha_registro, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($afiliador_nombre, $afiliador_responsable_nombre, $afiliador_responsable_email, $afiliador_responsable_contacto, $afiliador_referencia_documento, $afiliador_fecha_registro, $accion_usuario, $accion_fecha));

            $listaResultados = $this->db->insert_id();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function UpdateTerceros($afiliador_nombre, $afiliador_responsable_nombre, $afiliador_responsable_email, $afiliador_responsable_contacto, $afiliador_referencia_documento, $accion_usuario, $accion_fecha, $afiliador_id)
    {        
        try 
        {
            $sql = "UPDATE afiliador SET afiliador_nombre=?, afiliador_responsable_nombre=?, afiliador_responsable_email=?, afiliador_responsable_contacto=?, afiliador_referencia_documento=?, accion_usuario=?, accion_fecha=? WHERE afiliador_id=? "; 
            
            $consulta = $this->db->query($sql, array($afiliador_nombre, $afiliador_responsable_nombre, $afiliador_responsable_email, $afiliador_responsable_contacto, $afiliador_referencia_documento, $accion_usuario, $accion_fecha, $afiliador_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateTercerosKey($afiliador_key, $accion_usuario, $accion_fecha, $afiliador_id)
    {        
        try 
        {
            $sql = "UPDATE afiliador SET afiliador_key=?, accion_usuario=?, accion_fecha=? WHERE afiliador_id=? "; 
            
            $consulta = $this->db->query($sql, array($afiliador_key, $accion_usuario, $accion_fecha, $afiliador_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function VerificaAfiliadorTercero($usuario_id, $afiliador_key)
    {        
        try 
        {
            $sql = "SELECT e.ejecutivo_id, u.usuario_id, a.afiliador_id, CONCAT_WS(' ', u.usuario_nombres, u.usuario_app, u.usuario_apm) as usuario_nombre, u.usuario_email, a.afiliador_nombre
                    FROM usuarios u
                    INNER JOIN rol r ON r.rol_id=u.usuario_rol
                    INNER JOIN perfil_app pa ON pa.perfil_app_id=r.perfil_app_id
                    INNER JOIN ejecutivo e ON e.usuario_id=u.usuario_id
                    INNER JOIN afiliador a ON a.afiliador_id=e.afiliador_id
                    WHERE u.usuario_id=? AND a.afiliador_key=?"; 

            $consulta = $this->db->query($sql, array($usuario_id, $afiliador_key));

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
    
    function ListaDocumentosDigitalizarTerceros($codigo_terceros, $codigo_documento)
    {        
        try 
        {
            $sql = "SELECT td.terceros_documento_id, td.terceros_documento_pdf, CONCAT('ter', '_', t.terceros_id) as 'terceros_carpeta', td.accion_fecha 
                    FROM terceros_documento td, terceros t
                    WHERE t.terceros_id=td.terceros_id AND td.terceros_id=? AND td.documento_id=? ORDER BY td.terceros_documento_id DESC ";

            $consulta = $this->db->query($sql, array($codigo_terceros, $codigo_documento));

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
    
    function ObtenerDocumentoDigitalizarTerceros($codigo_terceros, $codigo_documento)
    {        
        try 
        {
            $sql = "SELECT td.terceros_documento_pdf, CONCAT('ter', '_', t.terceros_id) as 'terceros_carpeta' FROM terceros_documento td, terceros t WHERE t.terceros_id=td.terceros_id AND td.terceros_id=? AND td.terceros_documento_id=? ORDER BY td.terceros_documento_id DESC LIMIT 1 ";

            $consulta = $this->db->query($sql, array($codigo_terceros, $codigo_documento));

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
    
    function ObtenerDetalleTercerosProspecto($codigo)
    {        
        try 
        {
            $sql = "SELECT p.prospecto_etapa, p.onboarding_codigo, er.estructura_regional_monto, er.estructura_regional_id, er.estructura_regional_nombre
                    FROM prospecto p
                    INNER JOIN terceros t ON t.terceros_id=p.onboarding_codigo
                    INNER JOIN estructura_regional er ON er.estructura_regional_id=t.codigo_agencia_fie
                    WHERE p.onboarding=1 AND p.prospecto_id=? LIMIT 1 "; 

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function ObtenerTercerosProspecto($codigo)
    {        
        try 
        {
            $sql = "SELECT onboarding_codigo FROM prospecto WHERE onboarding=1 AND prospecto_id=? LIMIT 1 "; 

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function ObtenerProspectoTerceros($codigo)
    {        
        try 
        {
            $sql = "SELECT prospecto_id FROM prospecto WHERE onboarding=1 AND onboarding_codigo=? LIMIT 1 "; 

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function UpdateTercerosMonto($monto, $codigo_terceros, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "UPDATE terceros SET monto_inicial=?, accion_usuario=?, accion_fecha=? WHERE terceros_id=? "; 
            
            $consulta = $this->db->query($sql, array($monto, $accion_usuario, $accion_fecha, $codigo_terceros));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Onboarding
    
    function NuevoOnboarding(
                            $ejecutivo_id,
                            $tipo_persona_id,
                            $afiliador_id,
                            $tercero_asistencia,
                            $codigo_agencia_fie,
                            $tipo_cuenta,
                            $cI_confirmacion_id,
                            $cI_numeroraiz,
                            $cI_complemento,
                            $cI_lugar_emisionoextension,
                            $di_primernombre,
                            $di_segundo_otrosnombres,
                            $di_primerapellido,
                            $di_segundoapellido,
                            $dir_notelefonico,
                            $direccion_email,
                            $accion_usuario,
                            $accion_fecha
                            )
    {        
        try 
        {            
            $sql = "INSERT INTO terceros
                
                        (ejecutivo_id,
                        tipo_persona_id,
                        afiliador_id,
                        tercero_asistencia,
                        codigo_agencia_fie,
                        tipo_cuenta,
                        cI_confirmacion_id,
                        cI_numeroraiz,
                        cI_complemento,
                        cI_lugar_emisionoextension,
                        di_primernombre,
                        di_segundo_otrosnombres,
                        di_primerapellido,
                        di_segundoapellido,
                        dir_notelefonico,
                        direccion_email,
                        accion_usuario,
                        accion_fecha,
                        terceros_fecha)
                        
                    VALUES
                    
                        (?,
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
                            $ejecutivo_id,
                            $tipo_persona_id,
                            $afiliador_id,
                            $tercero_asistencia,
                            $codigo_agencia_fie,
                            $tipo_cuenta,
                            $cI_confirmacion_id,
                            $cI_numeroraiz,
                            $cI_complemento,
                            $cI_lugar_emisionoextension,
                            $di_primernombre,
                            $di_segundo_otrosnombres,
                            $di_primerapellido,
                            $di_segundoapellido,
                            $dir_notelefonico,
                            $direccion_email,
                            $accion_usuario,
                            $accion_fecha,
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
    
    function update_actividad_economica(
                            $ae_sector_economico,
                            $ae_subsector_economico,
                            $ae_actividad_ocupacion,
                            $ae_actividad_fie,
                            $ae_ambiente,
                            $emp_nombre_empresa,
                            $emp_direccion_trabajo,
                            $emp_telefono_faxtrabaj,
                            $emp_tipo_empresa,
                            $emp_antiguedad_empresa,
                            $emp_codigo_actividad,
                            $emp_descripcion_cargo,
                            $emp_fecha_ingreso,
                            $coordenadas_geo_trab,
                            $dir_departamento_neg,
                            $dir_provincia_neg,
                            $dir_localidad_ciudad_neg,
                            $dir_barrio_zona_uv_neg,
                            $dir_av_calle_pasaje_neg,
                            $dir_edif_cond_urb_neg,
                            $dir_numero_neg,
                            $accion_usuario,
                            $accion_fecha,
                            $codigo_terceros
                            )
    {        
        try 
        {            
            $sql = "UPDATE terceros SET
                
                        ae_sector_economico = ?,
                        ae_subsector_economico = ?,
                        ae_actividad_ocupacion = ?,
                        ae_actividad_fie = ?,
                        ae_ambiente = ?,
                        emp_nombre_empresa = ?,
                        emp_direccion_trabajo = ?,
                        emp_telefono_faxtrabaj = ?,
                        emp_tipo_empresa = ?,
                        emp_antiguedad_empresa = ?,
                        emp_codigo_actividad = ?,
                        emp_descripcion_cargo = ?,
                        emp_fecha_ingreso = ?,
                        coordenadas_geo_trab = ?,
                        dir_departamento_neg = ?,
                        dir_provincia_neg = ?,
                        dir_localidad_ciudad_neg = ?,
                        dir_barrio_zona_uv_neg = ?,
                        dir_av_calle_pasaje_neg = ?,
                        dir_edif_cond_urb_neg = ?,
                        dir_numero_neg = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE terceros_id = ?";
            
            $this->db->query($sql, array(
                            $ae_sector_economico,
                            $ae_subsector_economico,
                            $ae_actividad_ocupacion,
                            $ae_actividad_fie,
                            $ae_ambiente,
                            $emp_nombre_empresa,
                            $emp_direccion_trabajo,
                            $emp_telefono_faxtrabaj,
                            $emp_tipo_empresa,
                            $emp_antiguedad_empresa,
                            $emp_codigo_actividad,
                            $emp_descripcion_cargo,
                            $emp_fecha_ingreso,
                            $coordenadas_geo_trab,
                            $dir_departamento_neg,
                            $dir_provincia_neg,
                            $dir_localidad_ciudad_neg,
                            $dir_barrio_zona_uv_neg,
                            $dir_av_calle_pasaje_neg,
                            $dir_edif_cond_urb_neg,
                            $dir_numero_neg,
                            $accion_usuario,
                            $accion_fecha,
                            $codigo_terceros
                            ));

            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

    }
    
    function update_observacion_tercero(
                            $observacion,
                            $accion_usuario,
                            $accion_fecha,
                            $codigo_terceros
                            )
    {        
        try 
        {            
            $sql = "UPDATE terceros SET
                
                        terceros_observacion = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE terceros_id = ?";
            
            $this->db->query($sql, array(
                            $observacion,
                            $accion_usuario,
                            $accion_fecha,
                            $codigo_terceros
                            ));

            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

    }
    
    function update_referencias(
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
                            $accion_usuario,
                            $accion_fecha,
                            $codigo_terceros
                            )
    {        
        try 
        {            
            $sql = "UPDATE terceros SET
                
                        rp_nombres = ?,
                        rp_primer_apellido = ?,
                        rp_segundo_apellido = ?,
                        rp_direccion = ?,
                        rp_notelefonicos = ?,
                        rp_nexo_cliente = ?,
                        con_primer_nombre = ?,
                        con_segundo_nombre = ?,
                        con_primera_pellido = ?,
                        con_segundoa_pellido = ?,
                        con_acteconomica_principal = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE terceros_id = ?";
            
            $this->db->query($sql, array(
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
                            $accion_usuario,
                            $accion_fecha,
                            $codigo_terceros
                            ));

            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

    }
    
    function update_ubicacion(
                            $dir_tipo_direccion,
                            $dir_departamento,
                            $dir_provincia,
                            $dir_localidad_ciudad,
                            $dir_barrio_zona_uv,
                            $dir_ubicacionreferencial,
                            $dir_av_calle_pasaje,
                            $dir_edif_cond_urb,
                            $dir_numero,
                            $coordenadas_geo_dom,
                            $accion_usuario,
                            $accion_fecha,
                            $codigo_terceros
                            )
    {        
        try 
        {            
            $sql = "UPDATE terceros SET
                
                        dir_tipo_direccion = ?,
                        dir_departamento = ?,
                        dir_provincia = ?,
                        dir_localidad_ciudad = ?,
                        dir_barrio_zona_uv = ?,
                        dir_ubicacionreferencial = ?,
                        dir_av_calle_pasaje = ?,
                        dir_edif_cond_urb = ?,
                        dir_numero = ?,
                        coordenadas_geo_dom = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE terceros_id = ?";
            
            $this->db->query($sql, array(
                            $dir_tipo_direccion,
                            $dir_departamento,
                            $dir_provincia,
                            $dir_localidad_ciudad,
                            $dir_barrio_zona_uv,
                            $dir_ubicacionreferencial,
                            $dir_av_calle_pasaje,
                            $dir_edif_cond_urb,
                            $dir_numero,
                            $coordenadas_geo_dom,
                            $accion_usuario,
                            $accion_fecha,
                            $codigo_terceros
                            ));

            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

    }
    
    function update_datos_personales(
                            $ddc_ciudadania_usa,
                            $ddc_motivo_permanencia_usa,
                            $tipo_cuenta,
                            $cI_numeroraiz,
                            $cI_confirmacion_id, 
                            $cI_complemento,
                            $cI_lugar_emisionoextension,
                            $di_primernombre,
                            $di_segundo_otrosnombres,
                            $di_primerapellido,
                            $di_segundoapellido,
                            $di_fecha_nacimiento,
                            $di_fecha_vencimiento,
                            $di_indefinido,
                            $di_genero,
                            $di_estadocivil,
                            $di_apellido_casada,
                            $direccion_email,
                            $dir_notelefonico,
                            $envio,
                            $dd_profesion,
                            $dd_nivel_estudios,
                            $dd_dependientes,
                            $dd_proposito_rel_comercial,
                            $dec_ingresos_mensuales,
                            $dec_nivel_egresos,
                            $accion_usuario,
                            $accion_fecha,
                            $codigo_terceros
                            )
    {        
        try 
        {            
            $sql = "UPDATE terceros SET
                
                        ddc_ciudadania_usa = ?,
                        ddc_motivo_permanencia_usa = ?,
                        tipo_cuenta = ?,
                        cI_numeroraiz = ?,
                        cI_confirmacion_id = ?,
                        cI_complemento = ?,
                        cI_lugar_emisionoextension = ?,
                        di_primernombre = ?,
                        di_segundo_otrosnombres = ?,
                        di_primerapellido = ?,
                        di_segundoapellido = ?,
                        di_fecha_nacimiento = ?,
                        di_fecha_vencimiento = ?,
                        di_indefinido = ?,
                        di_genero = ?,
                        di_estadocivil = ?,
                        di_apellido_casada = ?,
                        direccion_email = ?,
                        dir_notelefonico = ?,
                        envio = ?,
                        dd_profesion = ?,
                        dd_nivel_estudios = ?,
                        dd_dependientes = ?,
                        dd_proposito_rel_comercial = ?,
                        dec_ingresos_mensuales = ?,
                        dec_nivel_egresos = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE terceros_id = ?";
            
            $this->db->query($sql, array(
                            $ddc_ciudadania_usa,
                            $ddc_motivo_permanencia_usa,
                            $tipo_cuenta,
                            $cI_numeroraiz,
                            $cI_confirmacion_id, 
                            $cI_complemento,
                            $cI_lugar_emisionoextension,
                            $di_primernombre,
                            $di_segundo_otrosnombres,
                            $di_primerapellido,
                            $di_segundoapellido,
                            $di_fecha_nacimiento,
                            $di_fecha_vencimiento,
                            $di_indefinido,
                            $di_genero,
                            $di_estadocivil,
                            $di_apellido_casada,
                            $direccion_email,
                            $dir_notelefonico,
                            $envio,
                            $dd_profesion,
                            $dd_nivel_estudios,
                            $dd_dependientes,
                            $dd_proposito_rel_comercial,
                            $dec_ingresos_mensuales,
                            $dec_nivel_egresos,
                            $accion_usuario,
                            $accion_fecha,
                            $codigo_terceros
                            ));

            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

    }
    
    function update_referencia_prospectos(
                            $general_solicitante,
                            $general_telefono,
                            $general_email,
                            $general_ci,
                            $accion_usuario,
                            $accion_fecha,
                            $estructura_id
                            )
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET
                
                        general_solicitante = ?,
                        general_telefono = ?,
                        general_email = ?,
                        general_ci = ?,
                        accion_usuario = ?,
                        accion_fecha = ?
                    
                    WHERE prospecto_id = ?";

            $this->db->query($sql, array(
                            $general_solicitante,
                            $general_telefono,
                            $general_email,
                            $general_ci,
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
    
    function ObtenerTotalHorasFlujo($flujos)
    {        
        try 
        {
            $sql = "SELECT SUM(etapa_tiempo) as 'total_horas' FROM etapa WHERE etapa_categoria IN(" . $flujos . ") ";           

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
    
    function ObtenerListaFlujo($flujos, $hora, $dia)
    {        
        try 
        {
            $sql = "SELECT etapa_id, etapa_depende, etapa_nombre, etapa_detalle, etapa_tiempo, etapa_notificar_correo, etapa_rol, etapa_categoria, etapa_alerta_correo, etapa_alerta_dias, etapa_alerta_hora FROM etapa WHERE etapa_alerta_correo=1 AND etapa_categoria IN(" . $flujos . ") AND etapa_alerta_hora=" . $hora . " AND etapa_alerta_dias LIKE '%" . $dia . "%' ";           

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
    
    function ForzarVoboInstancias($campo, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET " . $campo . "=?, " . $campo . "_fecha=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array("1", $accion_fecha, $accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Se obtiene los datos actuales del catálogo por su ID interno
    function ObtenerCatalogoPorId($codigo_catalogo)
    {
        try 
        {
            $sql = "SELECT catalogo_id, catalogo_parent, catalogo_tipo_codigo, catalogo_codigo FROM catalogo WHERE catalogo_id=? LIMIT 1"; 

            $consulta = $this->db->query($sql, array($codigo_catalogo));

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
    
    // Actualiza la data de la tabla, campos y valores indicados
    function UpdateCatalogoTablas($tabla, $campo, $valor_nuevo, $valor_actual)
    {        
        try 
        {
            $sql = "UPDATE $tabla SET $campo=? WHERE $campo=? "; 
            
            $consulta = $this->db->query($sql, array($valor_nuevo, $valor_actual));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function actividad_fie_from_sector($codigo_sector)
    {        
        try
        {
            $sql = "SELECT DISTINCT fie.catalogo_tipo_codigo, fie.catalogo_codigo, fie.catalogo_descripcion, act.catalogo_codigo as 'ae_actividad_economica', sub.catalogo_codigo as 'ae_subsector_economico', sec.catalogo_codigo as 'ae_sector_economico'
                    FROM catalogo sec
                    INNER JOIN catalogo sub ON sub.catalogo_parent=sec.catalogo_codigo
                    INNER JOIN catalogo act ON act.catalogo_parent=sub.catalogo_codigo
                    INNER JOIN catalogo fie on fie.catalogo_parent=act.catalogo_codigo
                    WHERE sec.catalogo_codigo=? AND fie.catalogo_estado=1 ORDER BY fie.catalogo_descripcion "; 

            $consulta = $this->db->query($sql, $codigo_sector);

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
    
    // -- Req. Nuevos Estados
    
    function NotificarCierre_Guardar($notificar_cierre_causa, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE terceros SET terceros_estado=?, notificar_cierre=?, notificar_cierre_causa=?, notificar_cierre_fecha=?, notificar_cierre_usuario=?, accion_usuario=?, accion_fecha=? WHERE terceros_id=? "; 
            
            $consulta = $this->db->query($sql, array(7, 1, $notificar_cierre_causa, $fecha_actual, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function CuentaCerrada_Guardar($cuenta_cerrada_causa, $cuenta_cerrada_envia, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE terceros SET terceros_estado=?, cuenta_cerrada=?, cuenta_cerrada_causa=?, cuenta_cerrada_envia=?, cuenta_cerrada_fecha=?, cuenta_cerrada_usuario=?, accion_usuario=?, accion_fecha=? WHERE terceros_id=? "; 
            
            $consulta = $this->db->query($sql, array(8, 1, $cuenta_cerrada_causa, $cuenta_cerrada_envia, $fecha_actual, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // -- Req. Consulta COBIS y SEGIP
    
    function MarcarValOper($estructura_id)
    {        
        try 
        {
            $sql = "UPDATE terceros SET terceros_estado=? WHERE terceros_id=? "; 
            
            $consulta = $this->db->query($sql, array(16, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function CompletarValOper($terceros_estado, $segip_operador_resultado, $segip_operador_texto, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE terceros SET terceros_estado=?, segip_operador_resultado=?, segip_operador_texto=?, segip_operador_fecha=?, segip_operador_usuario=?, accion_usuario=?, accion_fecha=? WHERE terceros_id=? "; 
            
            $consulta = $this->db->query($sql, array($terceros_estado, $segip_operador_resultado, $segip_operador_texto, $fecha_actual, $codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function token_existe($token)
    {        
        try
        {
            $sql = "SELECT token_id FROM token_onboarding WHERE token_token=? ORDER BY token_id DESC LIMIT 1 "; 

            $consulta = $this->db->query($sql, array($token));
            
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
    
    function token_obtener($token, $opcion_otp)
    {        
        try
        {
            if($opcion_otp == 1)
            {
                $aux = 'AND token_activo=1';
            }
            else
            {
                $aux = '';
            }
            
            $sql = "SELECT * FROM token_onboarding WHERE token_token=? $aux ORDER BY token_id DESC LIMIT 1 "; 

            $consulta = $this->db->query($sql, array($token));
            
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
    
    function token_update_aux($ws_token, $parametros, $certificado_segip, $token)
    {        
        try
        {
            $sql = "UPDATE token_onboarding SET token_jwt=?, token_params=?, token_pdf_base64=? WHERE token_token=? "; 

            $consulta = $this->db->query($sql, array($ws_token, $parametros, $certificado_segip, $token));
        }
        catch (Exception $e)
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function token_update_parametro($parametros, $token, $accion_usuario, $accion_fecha)
    {        
        try
        {
            $sql = "UPDATE token_onboarding SET token_params=?, accion_usuario=?, accion_fecha=? WHERE token_token=? "; 

            $consulta = $this->db->query($sql, array($parametros, $accion_usuario, $accion_fecha, $token));
        }
        catch (Exception $e)
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function token_insert($token_generado, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO token_onboarding (token_token, token_fecha_creacion, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?)"; 
            
            $consulta = $this->db->query($sql, array($token_generado, $accion_fecha, $accion_usuario, $accion_fecha));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function token_delete_expired()
    {        
        try 
        {
            $sql = "DELETE FROM token_onboarding WHERE (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(token_onboarding.token_fecha_creacion)) > ((SELECT conf_token_validez FROM conf_general WHERE conf_general_id='conf-001')*60) "; 
            
            $consulta = $this->db->query($sql, array());

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function token_update_activo($token, $accion_usuario, $accion_fecha)
    {        
        try
        {
            $sql = "UPDATE token_onboarding SET token_activo=0, accion_usuario=?, accion_fecha=? WHERE token_token=? "; 

            $consulta = $this->db->query($sql, array($accion_usuario, $accion_fecha, $token));
        }
        catch (Exception $e)
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function token_delete_id($token)
    {        
        try 
        {
            $sql = "DELETE FROM token_onboarding WHERE token_token=? "; 
            
            $consulta = $this->db->query($sql, array($token));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function token_update_jwt($ws_jwt, $token, $accion_usuario, $accion_fecha)
    {        
        try
        {
            $sql = "UPDATE token_onboarding SET token_jwt=?, accion_usuario=?, accion_fecha=? WHERE token_token=? "; 

            $consulta = $this->db->query($sql, array($ws_jwt, $accion_usuario, $accion_fecha, $token));
        }
        catch (Exception $e)
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarAuditoria_WS_SOA_FIE($audit_action, $audit_service, $audit_params, $audit_result, $audit_token_front, $audit_token_ci, $audit_user, $audit_date)
    {
        try 
        {
            $sql = "INSERT INTO auditoria_soa_fie(audit_action, audit_service, audit_params, audit_result, audit_token_front, audit_token_ci, audit_user, audit_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ";
            $consulta = $this->db->query($sql, array($audit_action, $audit_service, $audit_params, $audit_result, $audit_token_front, $audit_token_ci, $audit_user, $audit_date));
            $listaResultados = $this->db->insert_id();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    
    function UpdateToken_WS_SOA_FIE($audit_token_front, $audit_token_ci, $audit_id)
    {        
        try
        {
            $sql = "UPDATE auditoria_soa_fie SET audit_token_front=?, audit_token_ci=? WHERE audit_id=? "; 

            $consulta = $this->db->query($sql, array($audit_token_front, $audit_token_ci, $audit_id));
        }
        catch (Exception $e)
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateSimilaridad_WS_SOA_FIE($audit_other, $audit_id)
    {        
        try
        {
            $sql = "UPDATE auditoria_soa_fie SET audit_other=? WHERE audit_id=? "; 

            $consulta = $this->db->query($sql, array($audit_other, $audit_id));
        }
        catch (Exception $e)
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateAttempt_WS_SOA_FIE($audit_attempt, $audit_id)
    {        
        try
        {
            $sql = "UPDATE auditoria_soa_fie SET audit_attempt=? WHERE audit_id=? "; 

            $consulta = $this->db->query($sql, array($audit_attempt, $audit_id));
        }
        catch (Exception $e)
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    /*************** AFILIACIÓN POR TERCEROS - FIN ****************************/
    
    /*************** REPORTE SOA FIE - INICIO ****************************/
    
    function ReporteAuditoriaSOA($filtro_personalizado)
    {        
        try 
        {
            $sql = "SELECT audit_id, audit_action, audit_service, audit_result, audit_token_front, audit_token_ci, audit_other, audit_user, audit_date,
                    
                    CASE WHEN audit_service LIKE '%validate-client' THEN 'validate-client' WHEN audit_service LIKE '%liveness' THEN 'liveness' WHEN audit_service LIKE '%ocrci' THEN 'ocrci'
                    END AS servicio,
                    CASE WHEN audit_action LIKE 'SUCCESS%' THEN 'Exitoso' WHEN audit_action LIKE 'WITH_ERROR%' THEN 'Error'
                    END AS resultado,
                    CASE WHEN audit_attempt=1 THEN 'Primero' WHEN audit_attempt<>1 THEN 'Reintento'
                    END AS validacion
                    
                    FROM auditoria_soa_fie " . $filtro_personalizado . " ORDER BY audit_date ASC, audit_token_front "; 

            $consulta = $this->db->query($sql, array($filtro_personalizado));

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
    
    function DetalleAuditoriaSOA($audit_id)
    {        
        try
        {
            $sql = "SELECT audit_id, audit_action, audit_service, audit_token_front, audit_token_ci, audit_attempt, audit_other, audit_user, audit_date, audit_params, audit_result FROM auditoria_soa_fie WHERE audit_id=? LIMIT 1 "; 

            $consulta = $this->db->query($sql, array($audit_id));
            
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
    
    function BorrarAuditoriaSOA($fecha_ini, $fecha_fin)
    {        
        try
        {
            $nombre_usuario = $_SESSION["session_informacion"]["login"];
            $fecha_actual = date('Y-m-d H:i:s');
            
            $sql = "DELETE FROM auditoria_soa_fie WHERE audit_date BETWEEN ? AND ? ";
            $consulta = $this->db->query($sql, array($fecha_ini, $fecha_fin));
            
            $filas_afectadas = $this->db->affected_rows();
            
            // Guardar el log de borrado SOA-FI
            
            $sql2 = "INSERT INTO auditoria_borrado_soa_fie(audit_date_from, audit_date_to, audit_affected_rows, audit_user, audit_date) VALUES (?, ?, ?, ?, ?) ";
            $consulta2 = $this->db->query($sql2, array($this->mfunciones_generales->getFormatoFecha_datetime_date($fecha_ini), $this->mfunciones_generales->getFormatoFecha_datetime_date($fecha_fin), (int)$filas_afectadas, $nombre_usuario, $fecha_actual));
            
            return $filas_afectadas;
        }
        catch (Exception $e)
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ListadoLogsBorrado_AuditoriaSOA()
    {        
        try
        {
            $sql = "SELECT * FROM auditoria_borrado_soa_fie "; 

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
    
    /*************** REPORTE SOA FIE - FIN ****************************/
    
    /*************** INTEGRACIÓN WS COBIS - INICIO ****************************/
    
    function obtenerIntentosFlujoCOBIS()
    {        
        try
        {
            $sql = "SELECT conf_f_cobis_intentos, conf_f_cobis_intentos_operativo FROM conf_general WHERE conf_general_id='conf-001' "; 

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
    
    function ObtenerListaRegistrosFlujoCOBIS($tiempo)
    {
        try 
        {
            $sql = "SELECT terceros_id, f_cobis_actual_usuario, accion_usuario FROM terceros WHERE terceros_estado=1 AND f_cobis_flujo=0 AND (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(terceros.f_cobis_actual_fecha)) >= (60*?) ";

            $consulta = $this->db->query($sql, array((int)$tiempo));

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
    
    function ObtenerListaRegistrosFlujoCOBIS_stuck($tiempo)
    {
        try 
        {
            $sql = "SELECT terceros_id, codigo_agencia_fie, f_cobis_actual_usuario, accion_usuario FROM terceros WHERE terceros_estado=1 AND f_cobis_flujo=1 AND (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(terceros.f_cobis_actual_fecha)) >= (60*?) ";

            $consulta = $this->db->query($sql, array((int)$tiempo));

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
    
    function getFlujoCOBISActivo($codigo_solicitud)
    {
        try 
        {
            $sql = "SELECT f_cobis_flujo FROM terceros WHERE terceros_id=? ";

            $consulta = $this->db->query($sql, array((int)$codigo_solicitud));

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
    
    
    function PrepararFlujoCOBIS($codigo_solicitud)
    {
        try 
        {
            $sql = "UPDATE terceros SET f_cobis_flujo=1, f_cobis_actual_intento=f_cobis_actual_intento+1 WHERE terceros_id=? ";

            $consulta = $this->db->query($sql, array((int)$codigo_solicitud));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function setFlujoCOBIS_excepcion($codigo_solicitud, $codigo_usuario, $f_cobis_excepcion_motivo, $f_cobis_excepcion_motivo_texto, $f_cobis_flag_rechazado, $accion_fecha)
    {
        try 
        {
            $sql = "UPDATE terceros SET f_cobis_flujo=0, f_cobis_excepcion=1, f_cobis_actual_usuario=?, f_cobis_actual_fecha=?, f_cobis_excepcion_motivo=?, f_cobis_excepcion_motivo_texto=?, f_cobis_flag_rechazado=? WHERE terceros_id=? ";

            $consulta = $this->db->query($sql, array($codigo_usuario, $accion_fecha, $f_cobis_excepcion_motivo, $f_cobis_excepcion_motivo_texto, $f_cobis_flag_rechazado, $codigo_solicitud));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function setFlujoCOBIS_tracking($codigo_solicitud, $texto_armado)
    {
        try 
        {
            $sql = "UPDATE terceros SET f_cobis_tracking=concat(f_cobis_tracking, ?) WHERE terceros_id=? ";

            $consulta = $this->db->query($sql, array($texto_armado, $codigo_solicitud));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function setFlujoCOBIS_codigoCOBIS($codigo_solicitud, $cobis_codigo)
    {
        try 
        {
            $sql = "UPDATE terceros SET f_cobis_codigo=?, accion_fecha=? WHERE terceros_id=? ";

            $consulta = $this->db->query($sql, array($cobis_codigo, date('Y-m-d H:i:s'), $codigo_solicitud));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function setFlujoCOBIS_nroCuenta($codigo_solicitud, $onboarding_numero_cuenta)
    {
        try 
        {
            $sql = "UPDATE terceros SET onboarding_numero_cuenta=?, accion_fecha=? WHERE terceros_id=? ";

            $consulta = $this->db->query($sql, array($onboarding_numero_cuenta, date('Y-m-d H:i:s'), $codigo_solicitud));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function setFlujoCOBIS_marcar_etapa($codigo_solicitud, $etapa_flujo, $codigo_usuario, $fecha_actual)
    {
        try 
        {
            $sql = "UPDATE terceros SET f_cobis_excepcion=0, f_cobis_excepcion_motivo='', f_cobis_excepcion_motivo_texto='', f_cobis_flag_rechazado=0, f_cobis_actual_etapa=?, f_cobis_actual_usuario=?, f_cobis_actual_fecha=? WHERE terceros_id=? ";

            $consulta = $this->db->query($sql, array($etapa_flujo, $codigo_usuario, $fecha_actual, $codigo_solicitud));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function setFlujoCOBIS_finalizar($codigo_solicitud, $codigo_usuario, $fecha_actual)
    {
        try 
        {
            $sql = "UPDATE terceros SET f_cobis_flujo=0, f_cobis_excepcion=0, f_cobis_excepcion_motivo='', f_cobis_excepcion_motivo_texto='', f_cobis_flag_rechazado=0, f_cobis_actual_etapa=99, f_cobis_actual_usuario=?, f_cobis_actual_fecha=? WHERE terceros_id=? ";

            $consulta = $this->db->query($sql, array($codigo_usuario, $fecha_actual, $codigo_solicitud));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerTrackingFlujo($codigo)
    {
        try 
        {
            $sql = "SELECT f_cobis_tracking, f_cobis_flujo FROM terceros WHERE terceros_id=? ";

            $consulta = $this->db->query($sql, array((int)$codigo));

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
    
    function get_Sec_Sub_Act_from_fie($codigo)
    {
        try
        {
            $sql = "SELECT DISTINCT sec.catalogo_codigo as 'ae_sector_economico', sub.catalogo_codigo as 'ae_subsector_economico', act.catalogo_codigo as 'ae_actividad_economica', fie.catalogo_codigo as 'ae_actividad_fie'
                    FROM catalogo sec
                    INNER JOIN catalogo sub ON sub.catalogo_parent=sec.catalogo_codigo
                    INNER JOIN catalogo act ON act.catalogo_parent=sub.catalogo_codigo
                    INNER JOIN catalogo fie on fie.catalogo_parent=act.catalogo_codigo
                    WHERE fie.catalogo_tipo_codigo='ae_actividad_fie' 
                    AND act.catalogo_tipo_codigo='ae_actividad_economica' 
                    AND sub.catalogo_tipo_codigo='ae_subsector_economico' 
                    AND sec.catalogo_tipo_codigo='ae_sector_economico'
                    AND fie.catalogo_codigo=? ";

            $consulta = $this->db->query($sql, array((int)$codigo));

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
    
    /*************** INTEGRACIÓN WS COBIS - FIN ****************************/
    
    /*************** INTEGRACIÓN WS SMS - INICIO ****************************/
    
    function set_SMS_clean($tiempo_bloqueo)
    {
        try 
        {
            $tiempo_limpiado = 2880; // <-- 2 días | Tiempo de limpiado de los SMS ya que se verifica sólo los del día
            
            // Limpiar celulares PIN
            
            // -- Celulares que hayan cumplido el tiempo de limpiado
            $sql1 = "DELETE FROM onboarding_sms_pin WHERE (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(sms_fecha)) >= (60*?) ";
            $this->db->query($sql1, array($tiempo_limpiado));
            
            // -- Celulares que estén registrados en la tabla de bloqueo, que hayan cumplido el tiempo de bloqueo
            $sql2 = "   DELETE FROM onboarding_sms_pin 
                        WHERE sms_celular IN
                        (SELECT b.bloq_celular FROM onboarding_sms_bloq b WHERE (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(b.bloq_fecha)) >= (60*?)) ";
            $this->db->query($sql2, array($tiempo_bloqueo));
            
            // Limpiar celulares bloqueados que hayan cumplido el tiempo de bloqueo
            $sql3 = "DELETE FROM onboarding_sms_bloq WHERE (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(bloq_fecha)) >= (60*?) ";
            $this->db->query($sql3, array($tiempo_bloqueo));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ListarCelular_SMS_bloqueados($numero_celular)
    {
        try 
        {
            $sql = "SELECT bloq_id FROM onboarding_sms_bloq WHERE bloq_celular=? LIMIT 1 ";

            $consulta = $this->db->query($sql, array($numero_celular));

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
    
    function ListarCelular_SMS_MismaFecha($numero_celular)
    {
        try 
        {
            $sql = "SELECT sms_id FROM onboarding_sms_pin WHERE sms_celular=? AND DATE(sms_fecha) = CURDATE() ";

            $consulta = $this->db->query($sql, array($numero_celular));

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
    
    function set_Celular_Bloqueado($numero_celular, $accion_fecha)
    {
        try 
        {
            $sql = "INSERT INTO onboarding_sms_bloq(bloq_celular, bloq_fecha) VALUES (?, ?) ";

            $consulta = $this->db->query($sql, array($numero_celular, $accion_fecha));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function pin_existe($numero_celular, $pin, $activo='')
    {        
        try
        {
            $sql = "SELECT sms_id FROM onboarding_sms_pin WHERE sms_celular=? AND sms_pin=? " . ($activo=='' ? '' : 'AND sms_activo=?') . " LIMIT 1 "; 

            $consulta = $this->db->query($sql, array($numero_celular, $pin, $activo));
            
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
    
    function set_Celular_Enviado($numero_celular, $pin_generado, $accion_fecha)
    {
        try 
        {
            $sql = "INSERT INTO onboarding_sms_pin(sms_celular, sms_pin, sms_fecha) VALUES (?, ?, ?) ";

            $consulta = $this->db->query($sql, array($numero_celular, $pin_generado, $accion_fecha));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function set_Celular_UpdateActivo($numero_celular)
    {
        try 
        {
            $sql = "UPDATE onboarding_sms_pin SET sms_activo=0 WHERE sms_celular=? ";

            $consulta = $this->db->query($sql, array($numero_celular));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function set_CelularCaducado_UpdateActivo($numero_celular, $tiempo_vigencia)
    {
        try 
        {
            $sql = "UPDATE onboarding_sms_pin SET sms_activo=0 WHERE sms_celular=? AND (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(sms_fecha)) >= (60*?) ";

            $consulta = $this->db->query($sql, array($numero_celular, $tiempo_vigencia));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    /*************** INTEGRACIÓN WS SMS - FIN ****************************/
}

?>