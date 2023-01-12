<?php
/**
 * @file
 * Codigo que implementa el MODELO para los procedimientos relacionados
 * con ACTIVE DIRECTORY
 * @brief  MODELO DE LOGUEO
 * @author JRAD
 * @date Mar 21, 2014
 * @copyright 2017 JRAD
 */
/**
 * Codigo que implementa el MODELO para la autenticacion de usuario en el sistema
 * mediante ACTIVE DIRECTORY
 * @brief MODELO DEL LOGUEO
 * @class mfunciones_activeDirectory
 */
class mfunciones_activeDirectory extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
    }

    public function getDataUserInitium($usu_login)
    {
        try {
            $sql = "SELECT usuario_rol, usuario_id, usuario_user, usuario_fecha_creacion, usuario_fecha_ultimo_acceso, usuario_fecha_ultimo_password, usuario_activo, usuario_password_reset FROM usuarios WHERE usuario_user = ? GROUP BY usuario_user";
            $consulta = $this->db->query($sql, array($usu_login));
            $listaResultados = $consulta->result_array();
            return $listaResultados;
        } catch (Exception $e) {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }

    public function getDataUserApp($usu_login)
    {
        try {
            $sql = "SELECT r.perfil_app_id, pa.perfil_app_nombre, u.usuario_app, u.usuario_apm, u.usuario_email, u.usuario_telefono, u.usuario_direccion, u.usuario_rol, u.usuario_id, u.usuario_user, u.usuario_fecha_creacion, u.usuario_fecha_ultimo_acceso, u.usuario_fecha_ultimo_password, u.usuario_activo, e.ejecutivo_id
            FROM usuarios u
            INNER JOIN rol r ON r.rol_id=u.usuario_rol
            INNER JOIN perfil_app pa ON pa.perfil_app_id=r.perfil_app_id
            INNER JOIN ejecutivo e ON u.usuario_id = e.usuario_id
            WHERE u.usuario_user = ? ORDER BY u.usuario_id ASC LIMIT 1";
            $consulta = $this->db->query($sql, array($usu_login));
            $listaResultados = $consulta->result_array();
            return $listaResultados;
        } catch (Exception $e) {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }

    public function VerificarUserOnboarding($user_login)
    {
        try {
            $sql = "SELECT r.perfil_app_id, pa.perfil_app_nombre
            FROM usuarios u
            INNER JOIN rol r ON r.rol_id=u.usuario_rol
            INNER JOIN perfil_app pa ON pa.perfil_app_id=r.perfil_app_id
            WHERE u.usuario_user = ? ORDER BY u.usuario_id ASC LIMIT 1";
            $consulta = $this->db->query($sql, array($user_login));
            $listaResultados = $consulta->result_array();
            return $listaResultados;
        } catch (Exception $e) {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }

    public function VerificaCredencialesAPP2($usu_login)
    {
        try
        {
            $sql = "SELECT r.perfil_app_id, pa.perfil_app_nombre, u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email, u.usuario_telefono, u.usuario_direccion, u.usuario_rol, u.usuario_id, u.usuario_user, u.usuario_fecha_creacion, u.usuario_fecha_ultimo_acceso, u.usuario_fecha_ultimo_password, u.usuario_activo, e.ejecutivo_id
                    FROM usuarios u
                    INNER JOIN rol r ON r.rol_id=u.usuario_rol
                    INNER JOIN perfil_app pa ON pa.perfil_app_id=r.perfil_app_id
                    INNER JOIN ejecutivo e ON u.usuario_id = e.usuario_id
                    WHERE u.usuario_user = ? ORDER BY u.usuario_id ASC LIMIT 1";

            $consulta = $this->db->query($sql, array($usu_login));

            $listaResultados = $consulta->result_array();
        } catch (Exception $e) {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

        return $listaResultados;
    }

    public function getConfigAD()
    {
        try {
            $sql = "SELECT conf_general.conf_ad_activo, conf_general.conf_ad_host, conf_general.conf_ad_dominio, conf_general.conf_ad_dn FROM conf_general WHERE conf_general_id = ?";
            $consulta = $this->db->query($sql, array('conf-001'));
            $listaResultados = $consulta->result_array();
        } catch (Exception $e) {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

        return $listaResultados;
    }

    /**
     * Realiza la autenticacion mediante AD
     * Construye la parametria necesaria para la conexion
     *
     * @Params
     * usu_login : nombre de usuario
     * usu_password : password
     * from : quien realiza la peticion
     * test : verifica el modo hot test
     */
    public function ObtenerLista_AutenticacionPrincipalAD($usu_login, $usu_password, $from = "backend", $test = 0, $test_params = "")
    {
        try {
            // Conversión de usuario a minusculas
            $usu_login = strtolower($usu_login);

            $paramsAD = new stdClass();

            $paramsAD->ldap_fullname = "";
            $paramsAD->ldap_result = 0;
            $paramsAD->ldap_errno = 0;
            $paramsAD->ldap_message = "";

            if ($test == 0) {

                $userInitium = $this -> getDataUserInitium($usu_login);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($userInitium);

                if(!isset($userInitium[0])){
                    $listaResultados['error'] = $this->lang->line('not_found_user_ini');
                    return $listaResultados;
                }

                $ad_conf = $this->getConfigAD();
                $paramsAD->ldap_dn = $ad_conf[0]["conf_ad_dn"];
                $paramsAD->ldap_user = $usu_login . "@" . $ad_conf[0]["conf_ad_dominio"];
                $paramsAD->ldap_hostname = $ad_conf[0]["conf_ad_host"];
            } else {
                $paramsAD->ldap_dn = $test_params->conf_ad_dn;
                $paramsAD->ldap_user = $usu_login . "@" . $test_params->conf_ad_dominio;
                $paramsAD->ldap_hostname = $test_params->conf_ad_host;
            }

            $ldapport = 389;
            $paramsAD->ldap_hostname = strtolower($paramsAD->ldap_hostname);
            $ldap_hostname_sections = explode(":", $paramsAD->ldap_hostname);
            $section = $ldap_hostname_sections[count($ldap_hostname_sections) - 1];

            if (is_numeric($section)) {
                $ldapport = $section;

                if ($ldapport === 636) {
                    if ($ldap_hostname_sections[0] != "ldaps") {
                        $paramsAD->ldap_hostname = "ldaps://" . $paramsAD->ldap_hostname;
                    }
                } else {
                    if ($ldap_hostname_sections[0] != "ldap") {
                        $paramsAD->ldap_hostname = "ldap://" . $paramsAD->ldap_hostname;
                    }
                }
            }

            $ldap_con = @ldap_connect($paramsAD->ldap_hostname, $ldapport);

            if (!is_resource($ldap_con)) {
                $listaResultados['error'] = $this->lang->line('ad_error_general');

                $paramsAD->ldap_message = "Unable to connect to $paramsAD->ldap_hostname";

                if ($test != 1) {
                    $this->AuditoriaAD($paramsAD);
                }
            } else {
                @ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
                @ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0);

                $paramsAD->ldap_result = @ldap_bind($ldap_con, $paramsAD->ldap_user, $usu_password);
                $paramsAD->ldap_errno = @ldap_errno($ldap_con);
                $paramsAD->ldap_message = @ldap_error($ldap_con);

                if ($paramsAD->ldap_errno == -1) {
                    // Error de conexión
                    $listaResultados['error'] = $this->lang->line('ad_error_conexion');
                    if ($test != 1) {
                        $this->AuditoriaAD($paramsAD);
                    }
                } else {
                    $diagnostic = $this->getExtendedDiagnostic($ldap_con);

                    $paramsAD->ldap_errno = $diagnostic["code"];
                    $paramsAD->ldap_message = $diagnostic["message"];

                    if ($paramsAD->ldap_errno != 0) {
                        if ($paramsAD->ldap_errno == 82) {
                            $listaResultados['error'] = $this->lang->line('ad_error_credenciales');
                        } else {

                            // Cualquier otro error, solo se registra siempre que el modo test no esta activo
                            if ($test != 1) {
                                $this->AuditoriaAD($paramsAD);
                            }
                            $listaResultados['error'] = $this->lang->line('ad_error_general');
                        }
                    } else {
                        if ($paramsAD->ldap_result) {

                            // Nombre registrado en AD
                            $attributes = ['displayname'];
                            $filter = "(&(objectClass=user)(objectCategory=person)(userPrincipalName=" . ldap_escape($paramsAD->ldap_user, null, LDAP_ESCAPE_FILTER) . "))";
                            $results = ldap_search($ldap_con, $paramsAD->ldap_dn, $filter, $attributes);
                            if (is_resource($results)) {
                                $info = ldap_get_entries($ldap_con, $results);
                                if($info["count"] == 0){
                                    $paramsAD->ldap_message = $this -> lang -> line("ad_no_result");
                                    $paramsAD->ldap_errno = 94;
                                    if ($test != 1) {
                                        $this->AuditoriaAD($paramsAD);
                                    }
                                }else{
                                    $paramsAD->ldap_fullname = $info[0]['displayname'][0];
                                    if ($from == "backend") {
                                        $listaResultados = $this->getDataUserInitium($usu_login);
                                        $listaResultados[0]['nombre_completo'] = $paramsAD->ldap_fullname;
                                    } else if ($from == "app") {
                                        $listaResultados = $this->getDataUserApp($usu_login);
                                        $listaResultados[0]['usuario_nombres'] = $paramsAD->ldap_fullname;
                                    }
                                }
                            } else {
                                $paramsAD->ldap_message = $this -> lang -> line("ad_dn_error_sintax");
                                $paramsAD->ldap_errno = 34;
                                if ($test != 1) {
                                    $this->AuditoriaAD($paramsAD);
                                }
                            }

                        } else {
                            $listaResultados['error'] = $this->lang->line('ad_error_credenciales');
                        }
                    }
                }
            }

            if ($test == 1) {
                return $paramsAD;
            } else {
                return $listaResultados;
            }

        } catch (Exception $e) {
            js_error_div_javascript($e->getTraceAsString() . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }

    /**
     * Prepara la informacion para insertar los logs en la base de datos
     */
    public function AuditoriaAD($paramsAD)
    {
        // Fecha e IP
        $auditoria_fecha = date('Y-m-d H:i:s');
        $auditoria_ip = $this->input->ip_address();
        // Construccion de parametros
        $ldap_params["username"] = $paramsAD->ldap_user;
        $ldap_params["dn"] = $paramsAD->ldap_dn;
        $ldap_params["hostname"] = $paramsAD->ldap_hostname;
        $auditoria_params = json_encode($ldap_params);

        $this->InsertarAuditoriaAD($auditoria_params, $paramsAD->ldap_errno, $paramsAD->ldap_message, $auditoria_fecha, $auditoria_ip);
    }

    /**
     * Registro de logs en base de datos
     */
    public function InsertarAuditoriaAD($auditoria_params, $auditoria_cod_error, $auditoria_mensaje, $auditoria_fecha, $auditoria_ip)
    {
        try {
            $sql = "INSERT INTO auditoria_ad (auditoria_params, auditoria_cod_error, auditoria_mensaje, auditoria_fecha, auditoria_ip) VALUES (?, ?, ?, ?, ?) ";
            $consulta = $this->db->query($sql, array($auditoria_params, $auditoria_cod_error, $auditoria_mensaje, $auditoria_fecha, $auditoria_ip));
        } catch (Exception $e) {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }

    /**
     * Diagnostico de los codigos generados en AD
     * y los retorna con su respectivo mensaje
     */
    public function getExtendedDiagnostic($adLdap)
    {
        define(LDAP_OPT_DIAGNOSTIC_MESSAGE, 0x0032);
        $errorNumber = -999;

        ldap_get_option($adLdap, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extendedError);
        if (!empty($extendedError) && preg_match('/, data (\d+),?/', $extendedError, $matches)) {
            $errorNumber = hexdec(intval($matches[1]));
        } else {
            $errorNumber = 0;
        }
        $message = "";
        switch ($errorNumber) {
            case 0:
                $message = "SUCCESS";
                break;
            case 82:
                $message = "ERROR_INVALID_CREDENTIALS";
                break;
            case 1328:
                $message = "ERROR_INVALID_LOGON_HOURS";
                break;
            case 1329:
                $message = "ERROR_INVALID_WORKSTATION";
                break;
            case 1330:
                $message = "ERROR_PASSWORD_EXPIRED";
                break;
            case 1331:
                $message = "ERROR_ACCOUNT_DISABLED";
                break;
            case 1793:
                $message = "ERROR_ACCOUNT_EXPIRED";
                break;
            case 1907:
                $message = "ERROR_PASSWORD_MUST_CHANGE";
                break;
            case 1909:
                $message = "ERROR_ACCOUNT_LOCKED_OUT";
                break;
            case 1317:
                $message = "LDAP_NO_SUCH_OBJECT";
                break;
            case 1326:
                $message = "ERROR_LOGON_FAILURE";
                break;
            case 1327:
                $message = "ERROR_ACCOUNT_RESTRICTION";
                break;
            case 1384:
                $message = "ERROR_TOO_MANY_CONTEXT_IDS";
                break;
            default:
                $message = "UNKNOW_ERROR";
                break;
        }
        return array("code" => $errorNumber, "message" => $message);
    }

    public function Obtener_Resumen_AD($accion_gestion, $tipo, $fecha1_capturada, $fecha2_capturada)
    {
        if ($accion_gestion != 'seguimiento' && $accion_gestion != 'borrado') {
            js_invocacion_javascript('$("#divResultadoReporte").html("")');
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }

        if (null !== $fecha1_capturada) {
            $fecha_inicio = $fecha1_capturada;
            $fecha_inicio1 = $this->mfunciones_generales->getFormatoFechaDateTime($fecha_inicio . ' 00:00:01');
        }

        if (null !== $fecha2_capturada) {
            $fecha_fin = $fecha2_capturada;
            $fecha_fin1 = $this->mfunciones_generales->getFormatoFechaDateTime($fecha_fin . ' 23:59:59');
        }

        if ($fecha_inicio == "" && $fecha_fin == "") {
            js_invocacion_javascript('$("#divResultadoReporte").html("")');
            js_error_div_javascript('Debe establecer un rango de fechas.');
            exit();
        }

        if ($fecha_inicio != "" && $fecha_fin == "") {
            js_invocacion_javascript('$("#divResultadoReporte").html("")');
            js_error_div_javascript($this->lang->line('FormularioFechas'));
            exit();
        }

        if ($fecha_inicio == "" && $fecha_fin != "") {
            js_invocacion_javascript('$("#divResultadoReporte").html("")');
            js_error_div_javascript($this->lang->line('FormularioFechas'));
            exit();
        }

        $filtro_texto = "<strong>Filtros seleccionados</strong><br />";

        $filtro_texto .= " • Rango de Fechas: Del " . $fecha_inicio . " Al " . $fecha_fin;

        $array = $this->ReporteAuditoriaAD($fecha_inicio1, $fecha_fin1);

        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($array);

        if (!isset($array[0])) {
            js_invocacion_javascript('$("#divResultadoReporte").html("")');
            js_error_div_javascript('No se encontraron registros con los filtros indicados.');
            exit();
        }

        // -------

        $resultado = new stdClass();
        $resultado->total = 0;

        $resultado->array_listado = array();

        if (isset($array[0])) {
            $resultado->total = count($array);

            $i = 0;

            foreach ($array as $key => $value) {
                $item_valor = array(
                    "auditoria_id" => $value["id"],
                    "auditoria_params" => str_replace(",", ", ", json_encode($value["auditoria_params"])),
                    "auditoria_cod_error" => $value["auditoria_cod_error"],
                    "auditoria_mensaje" => $value["auditoria_mensaje"],
                    "auditoria_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["auditoria_fecha"]),
                    "auditoria_ip" => $value["auditoria_ip"],
                );
                $lst_resultado[$key] = $item_valor;
            }
        } else {
            $lst_resultado[0] = $array;
        }

        $resultado->array_listado = $lst_resultado;

        // -------

        $data["reporte_ad"] = $resultado;
        $data["filtro_texto"] = $filtro_texto;

        $data["fecha_inicio"] = $fecha_inicio;
        $data["fecha_fin"] = $fecha_fin;

        $data["accion_gestion"] = $accion_gestion;

        switch ($tipo) {
            case 'tabla':

                $this->load->view('auditoria_ad/view_auditoria_resultado', $data);

                break;

            case 'pdf':

                $this->mfunciones_generales->GeneraPDF('auditoria_ad/view_auditoria_resultado_plain', $data, 'L');
                return;

            case 'excel':

                $this->mfunciones_generales->GeneraExcel('auditoria_ad/view_auditoria_resultado_plain', $data);
                return;

            default:

                js_invocacion_javascript('$("#divResultadoReporte").html("")');
                js_error_div_javascript('Opción de generación de reporte inválida.');
                exit();

                break;
        }
    }

    public function ReporteAuditoriaAD($fecha_inicio, $fecha_fin)
    {
        try
        {
            $sql = "SELECT * FROM auditoria_ad WHERE auditoria_fecha BETWEEN ? AND ?";

            $consulta = $this->db->query($sql, array($fecha_inicio, $fecha_fin));

            $listaResultados = $consulta->result_array();

        } catch (Exception $e) {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

        return $listaResultados;
    }

    public function DetalleAuditoriaAD($audit_id)
    {
        try
        {
            $sql = "SELECT id, auditoria_params, auditoria_cod_error, auditoria_mensaje, auditoria_fecha, auditoria_ip FROM auditoria_ad WHERE id=? LIMIT 1 ";

            $consulta = $this->db->query($sql, array($audit_id));

            $listaResultados = $consulta->result_array();
        } catch (Exception $e) {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

        return $listaResultados;
    }
}
