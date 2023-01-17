<?php
/**
 * Libreria de Lenguaje para los módulos del sistema
 * @brief LIBRERIA LENGUAJE
 * @author Joel Aliaga Duran
 * @date Nov 2016
 */

$lang["NombreSistema"] = "Initium :: FIE MicroApp <br /> <span style='font-size: 0.6em;'>Seguimiento Efectivo de Información de Clientes </span> ";
$lang["NombreSistema_corto"] = "Initium :: FIE MicroApp";
$lang["NombreSistemaCliente"] = "BANCO FIE S.A.";

// ----------- FORMULARIOS ONBOARDING INICIO -----------

$lang["ConsultaTerceroTitulo"] = "Consultas y Reportes Onboarding";
$lang["ConsultaTerceroSubtitulo"] = "En este apartado podrá realizar las consultas respecto a los registros del proceso Onboarding. Los resultados se mostrarán regionalizados (por Agencia) de acuerdo a los permisos con los que cuente. <br /><br />Puede generar las consultas utilizando múltiples filtros de acuerdo a lo que requiera y para ver el detalle haga clic en “Más Opciones”.";

$lang["consulta_terceros_registros"] = "Consulta Registros";
$lang["consulta_terceros_piviot"] = "Constructor Reporte Pivot";

$lang["terceros_columna1"] = "Agencia Asociada";
$lang["terceros_columna2"] = "Ciudad";
$lang["terceros_columna3"] = "Nombre Solicitante";
$lang["terceros_columna4"] = "Número de Cédula de Identidad";
$lang["terceros_columna5"] = "Correo / Celular";
$lang["terceros_columna6"] = "Solicitó envío";
$lang["terceros_columna7"] = "Estado Actual";
$lang["terceros_columna8"] = "Fecha Registro";

$lang["fin_flujo_cobis"] = "Fin flujo COBIS";
$lang["f_cobis_error_intentos"] = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' . PREFIJO_TERCEROS . '%d: La cantidad de intentos de ingreso al flujo de registro COBIS (Aprobar Solicitud) alcanzó el máximo configurado. Por favor procese el registro de forma manual.';
$lang["f_cobis_error_flag_rechazo"] = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' . PREFIJO_TERCEROS . '%d: El <i>flag</i> "' . $lang["fin_flujo_cobis"] . '" fue marcado. Por favor procese el registro de forma manual.';
$lang["f_cobis_error_flujo_activo"] = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' . PREFIJO_TERCEROS . '%d: El registro aún se encuentra en el Flujo COBIS, por favor espere a que concluya. Puede revisar el <i>tracking</i> del flujo en el Detalle del Registro.';
$lang["f_cobis_error_flujo_completo"] = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' . PREFIJO_TERCEROS . '%d: El registro ya completó el Flujo COBIS y no puede reingresar al mismo. Puede revisar el <i>tracking</i> del flujo en el Detalle del Registro.';

$lang["terceros_excepcion_cobis"] = "Excepción Flujo COBIS";

// -- Listado INICIO

$lang['monto_inicial'] = 'Monto de Apertura';
$lang['tipo_cuenta'] = 'Tipo de Cuenta';
$lang['direccion_email'] = 'Dirección de Correo';
$lang['coordenadas_geo_dom'] = 'Geolocalización Domicilio';
$lang['coordenadas_geo_trab'] = 'Geolocalización Trabajo';
$lang['envio'] = 'Cliente solicitó envío';
$lang['numero_celular'] = 'Número Celular';
$lang['tipo_id_tipos_identificacion'] = 'Tipos de identificación';
$lang['cI_numeroraiz'] = 'Número de Cédula de Identidad';
$lang['cI_complemento'] = 'Complemento (sólo CI duplicados)';
$lang['cI_lugar_emisionoextension'] = 'Lugar de Expedición (si corresponde)';
$lang['cI_confirmacion_id'] = 'Confirmación de ID';
$lang['cI_sufijo'] = 'Sufijo';
$lang['cI_idduplicado'] = 'I.D. Duplicado';
$lang['cI_duplicada_numeroraiz'] = 'Número Raiz';
$lang['cI_duplicada_lugar_emisionoextension'] = 'Lugar de emisión o extensión';
$lang['cI_duplicada_confirmacion_id'] = 'Confirmación de ID';
$lang['cI_duplicada_sufijo'] = 'Sufijo';
$lang['cI_duplicada_idduplicado'] = 'I.D. Duplicado';
$lang['ci_extransjero_numeroraiz'] = 'Número Raiz';
$lang['ci_extransjero_complemento'] = 'Complemento';
$lang['ci_extransjero_confirmacion_id'] = 'Confirmación de ID';
$lang['ci_extransjero_idduplicado'] = 'I.D. Duplicado';
$lang['ci_consular_prefijoa'] = 'Prefijo (A)';
$lang['ci_consular_codigo_id_obligado_rree'] = 'Código de identificación del obligado RREE';
$lang['ci_consular_confirmacion_id'] = 'Confirmación de ID';
$lang['ci_consular_idduplicado'] = 'I.D. Duplicado';
$lang['c_diplomatico_prefijoa'] = 'Prefijo (A)';
$lang['c_diplomatico_codigo_id_obligado_rree'] = 'Código de identificación del obligado RREE';
$lang['c_diplomatico_confirmacion_id'] = 'Confirmación de ID';
$lang['c_diplomatico_prefijo'] = 'Prefijo';
$lang['c_diplomatico_idduplicado'] = 'I.D. Duplicado';
$lang['c_consular_prefijoa'] = 'Prefijo (A)';
$lang['c_consular_codigo_id_obligado_rree'] = 'Código de identificación del obligado RREE';
$lang['c_consular_confirmacion_id'] = 'Confirmación de ID';
$lang['c_consular_idduplicado'] = 'I.D. Duplicado';
$lang['c_prefijoa'] = 'Prefijo (A)';
$lang['c_codigo_ide_obligado_rree'] = 'Código de identificación del obligado RREE';
$lang['c_confirmacion_id'] = 'Confirmación de ID';
$lang['c_idduplicado'] = 'I.D. Duplicado';
$lang['ca_prefijoa'] = 'Prefijo (A)';
$lang['ca_codigo_id_obligado_rree'] = 'Código de identificación del obligado RREE';
$lang['ca_confirmacion_id'] = 'Confirmación de ID';
$lang['ca_idduplicado'] = 'I.D. Duplicado';
$lang['cb_codigo_id_obligador_ree'] = 'Código de identificación del obligado RREE';
$lang['cb_confirmacion_id'] = 'Confirmación de ID';
$lang['cb_idduplicado'] = 'I.D. Duplicado';
$lang['cc_prefijoa'] = 'Prefijo (A)';
$lang['cc_codigo_ide_obligado_rree'] = 'Código de identificación del obligado RREE';
$lang['cc_confirmacion_id'] = 'Confirmación de ID';
$lang['cc_idduplicado'] = 'I.D. Duplicado';
$lang['cd_prefijoa'] = 'Prefijo (A)';
$lang['cd_codigo_id_obligado_rree'] = 'Código de identificación del obligado RREE';
$lang['cd_confirmacion_id'] = 'Confirmación de ID';
$lang['cd_idduplicado'] = 'I.D. Duplicado';
$lang['doc_extranjero_cedula'] = 'Cédula';
$lang['doc_extranjero_confirmacion_id'] = 'Confirmación de ID';
$lang['doc_extranjero_idduplicado'] = 'I.D. Duplicado';
$lang['pas_cedula'] = 'Cédula';
$lang['pas_confirmacion_id'] = 'Confirmación de ID';
$lang['di_tipo_id'] = 'Tipo de ID';
$lang['di_numero_id'] = 'Número de ID';
$lang['di_estado_cliente'] = 'Estado de cliente';
$lang['di_primernombre'] = 'Primer Nombre';
$lang['di_segundo_otrosnombres'] = 'Segundo nombre';
$lang['di_primerapellido'] = 'Apellido paterno';
$lang['di_segundoapellido'] = 'Apellido materno';
$lang['di_nacionalidad'] = 'Nacionalidad';
$lang['di_fecha_nacimiento'] = 'Fecha de nacimiento';
$lang['di_fecha_vencimiento'] = 'Fecha de vencimiento';
$lang['di_indefinido'] = 'Indefinido';
$lang['di_genero'] = 'Género';
$lang['di_estadocivil'] = 'Estado civil';
$lang['di_apellido_casada'] = 'Apellido de casada';
$lang['di_conocidocomo'] = 'Conocido como';
$lang['di_sucursal_gestion'] = 'Sucursal de gestión';
$lang['di_conyuge'] = 'Conyuge';
$lang['dd_pais_residencia'] = 'País de residencia';
$lang['dd_profesion'] = 'Profesión';
$lang['dd_nivel_estudios'] = 'Nivel de estudios';
$lang['dd_tipo_vivienda'] = 'Tipo de vivienda';
$lang['dd_dependientes'] = 'Dependientes';
$lang['dd_tipo_persona'] = 'Tipo de persona';
$lang['dd_planilla'] = 'Planilla';
$lang['dd_tienediscapacidad'] = 'Tiene discapacidad';
$lang['dd_tipo_discapacidad'] = '     Tipo de discapacidad';
$lang['dd_cedula_discapacidad'] = '     Cédula de discapacidad';
$lang['dd_proposito_rel_comercial'] = 'Propósito de relación comercial';
$lang['dec_numero_nit'] = 'Número de NIT';
$lang['dec_fecha_venc_nit'] = 'Fecha de venc. de NIT';
$lang['dec_retencion_impuesto'] = 'Retención por impuesto';
$lang['dec_oportuno_pago_asfi'] = 'Oportuno pago ASFI';
$lang['dec_relacion_banco'] = 'Relación con el Banco';
$lang['dec_comentarios'] = 'Comentarios';
$lang['dec_ingresos_mensuales'] = 'Ingresos Mensuales';
$lang['dec_nivel_egresos'] = 'Nivel de Egresos';
$lang['dec_promotor'] = 'Promotor';
$lang['dec_noidtutor'] = 'No. ID (Tutor)';
$lang['dec_tutor'] = 'Tutor';
$lang['dec_calif_cliente_interna'] = 'Calif. Cliente Interna ';
$lang['dec_tiene_ref_economicas'] = 'Tiene Ref económicas';
$lang['dec_categoria_aml'] = 'Categoria AML';
$lang['dej_oficial_negocio'] = 'Oficial de negocio';
$lang['dir_tipo_direccion'] = 'Tipo de Dirección';
$lang['dir_nro_direccion'] = 'Nro. De dirección';
$lang['dir_tipo_propiedad'] = 'Tipo de propiedad';
$lang['dir_rural_urbano'] = 'Rural/urbano';
$lang['dir_pais'] = 'Pais';
$lang['dir_departamento'] = 'Departamento';
$lang['dir_provincia'] = 'Provincia';
$lang['dir_localidad_ciudad'] = 'Localidad/ciudad';
$lang['dir_barrio_zona_uv'] = 'Barrio/zona/uv';
$lang['dir_ubicacionreferencial'] = 'Ubicación referencial';
$lang['dir_av_calle_pasaje'] = 'Av./calle/pasaje';
$lang['dir_edif_cond_urb'] = 'Edif/cond/urb';
$lang['dir_numero'] = 'Número ';

$lang['dir_departamento_neg'] = 'Actividad Departamento';
$lang['dir_provincia_neg'] = 'Actividad Provincia';
$lang['dir_localidad_ciudad_neg'] = 'Actividad Localidad/ciudad';
$lang['dir_barrio_zona_uv_neg'] = 'Actividad Barrio/zona/uv';
$lang['dir_av_calle_pasaje_neg'] = 'Actividad Av./calle/pasaje';
$lang['dir_edif_cond_urb_neg'] = 'Actividad Edif/cond/urb';
$lang['dir_numero_neg'] = 'Actividad Número ';

$lang['dir_presento_ssbb'] = 'Presentó facturas servicios básicos';
$lang['dir_principal'] = 'Principal';
$lang['dir_correspondencia'] = 'correspondencia';
$lang['dir_telefonos'] = 'Telefonos:';
$lang['dir_notelefono'] = 'No teléfono';
$lang['dir_tipo_telefono'] = 'Tipo de teléfono';
$lang['dir_codigo_area'] = 'Código de área';
$lang['dir_notelefonico'] = 'Teléfono celular';
$lang['ae_estado'] = 'Estado';
$lang['ae_cliente'] = 'Cliente';
$lang['ae_sector_economico'] = 'Sector económico';
$lang['ae_subsector_economico'] = 'Sub sector económico';
//$lang['ae_actividad_ocupacion'] = 'Actividad/ocupación';
$lang['ae_actividad_ocupacion'] = 'Actividad Económica';
$lang['ae_actividad_fie'] = 'Actividad FIE';
$lang['ae_aclaracion_fie'] = 'Aclaración FIE';
$lang['ae_ambiente'] = 'Ambiente';
$lang['ae_tipo_propiedad'] = 'Tipo de propiedad';
$lang['ae_fuente_ingreso'] = 'Fuente de ingreso';
$lang['ae_aclaracion_caedec'] = 'Aclaración CAEDEC';
$lang['ae_descactividad'] = 'Desc. Actividad ';
$lang['ae_consecutivo'] = 'Consecutivo';
$lang['ae_principal'] = 'Principal';
$lang['ae_fecha_inicio_act'] = 'Fecha inicio act.';
$lang['ae_antiguedad_actividad'] = 'Antigüedad actividad';
$lang['ae_nro_empleados'] = 'Nro empleados';
$lang['ae_autorizado'] = 'Autorizado';
$lang['ae_afiliado'] = 'Afiliado';
$lang['ae_lugar_afiliacion'] = 'Lugar de afiliación';
$lang['ae_horarioinicio'] = 'Horario inicio';
$lang['ae_horariofinal'] = 'Horario final';
$lang['ae_dia_atencion_trabajo'] = 'Dia de atención/trabajo';
$lang['re_noreferencia'] = 'No Referencia';
$lang['ban_entidad_financiera'] = '  Entidad Financiera';
$lang['ban_nrocuenta'] = '  Nro cuenta';
$lang['ban_tipo_cuenta'] = '  Tipo de cuenta';
$lang['ban_fecha_apertura'] = '  Fecha de apertura';
$lang['ban_observaciones'] = '  Observaciones';
$lang['com_nroreferencia'] = '  Nro referencia';
$lang['com_establecimiento'] = '  Establecimiento';
$lang['com_nacional_extranjera'] = '  Nacional/extranjera';
$lang['com_pais'] = '  Pais';
$lang['com_localidad_ciudad'] = '  Localidad/ciudad';
$lang['com_negociosdesde'] = '  Negocios desde';
$lang['com_funcionario_inst'] = '  Funcionario-inst';
$lang['com_fechareferencia'] = '  Fecha referencia';
$lang['com_codigo_area'] = '  Código de área';
$lang['com_telefono'] = '  Teléfono';
$lang['com_moneda'] = '  Moneda';
$lang['com_monto'] = '  Monto';
$lang['com_estado'] = '  Estado';
$lang['com_observaciones'] = '  Observaciones';
$lang['emp_nroempleo'] = 'Nro empleo';
$lang['emp_empresa'] = 'Empresa';
$lang['emp_nombre_empresa'] = 'Nombre de la empresa';
$lang['emp_descactivid_empresa'] = 'Desc. Activid. Empresa';
$lang['emp_direccion_trabajo'] = 'Dirección del trabajo';
$lang['emp_telefono_faxtrabaj'] = 'Teléfono del Trabajo';
$lang['emp_tipo_empresa'] = 'Tipo de empresa';
$lang['emp_antiguedad_empresa'] = 'Antigüedad de la empresa';
$lang['emp_codigo_actividad'] = 'Código actividad';
$lang['emp_funcionario_publico'] = 'Funcionario público';
$lang['emp_noplanilla'] = 'No planilla';
$lang['emp_cargo'] = 'Cargo';
$lang['emp_descripcion_cargo'] = 'Cargo Actual';
$lang['emp_fecha_ingreso'] = 'Fecha de ingreso';
$lang['emp_fecha_salida'] = 'Fecha de salida';
$lang['emp_actividad_economica'] = 'Actividad económica';
$lang['rp_consecutivo'] = 'Consecutivo';
$lang['rp_nombres'] = 'Nombres';
$lang['rp_primer_apellido'] = 'Apellido paterno';
$lang['rp_segundo_apellido'] = 'Apellido materno';
$lang['rp_direccion'] = 'Dirección  ';
$lang['rp_notelefonicos'] = 'Teléfono celular';
$lang['rp_domicilio'] = '    Domicilio';
$lang['rp_trabajo'] = '    Trabajo';
$lang['rp_celular'] = '    Celular';
$lang['rp_nexo_cliente'] = 'Tipo de Referencia';
$lang['idb_foto_firma'] = 'Foto firma';
$lang['idb_motivo_nocaptura_huella'] = 'Motivo de no captura de huella';
$lang['idb_huellas'] = 'Huellas';
$lang['rec_relacion'] = 'Relación';
$lang['rec_izquierda'] = 'Izquierda';
$lang['rec_derecha'] = 'Derecha';
$lang['rec_mensaje_completo'] = 'Mensaje completo';
$lang['rec_atributos'] = 'Atributos';
$lang['rec_nro_atributo'] = 'Nro de atributo';
$lang['con_tipo_id'] = 'Tipo de Id';
$lang['con_nro_id'] = 'Nro de Id';
$lang['con_fecha_nacimiento'] = 'Fecha de nacimiento';
$lang['con_primer_nombre'] = 'Primer nombre';
$lang['con_segundo_nombre'] = 'Segundo Nombre';
$lang['con_primera_pellido'] = 'Apellido paterno';
$lang['con_segundoa_pellido'] = 'Apellido materno';
$lang['ddc_motivo_permanencia_usa'] = 'Motivo de permanencia en Estados Unidos';

$lang['con_genero'] = 'Género';
$lang['con_acteconomica_principal'] = 'Act. Económica principal';
$lang['ddc_ciudadania_usa'] = '¿Tiene o ha tenido ciudadanía estadounidense o pasaporte estadounidense o tarjeta de residencia estadounidense?';
$lang['ddc_residio_usa_tiempo'] = 'Ha residido en EEUU o alguno de sus territorios por mas de 182 dias en el ultimo año o un promedio de mas de 122 dias en los ultimos 3 años?.';
$lang['ddc_motivo_permanencia_usa'] = 'Motivo de permanencia en Estados Unidos';
$lang['ddc_nit_nss_usa'] = 'Numero de identificacion tributaria o numero de seguro social de los Estados Unidos';
$lang['pep_clientepep'] = 'Cliente PEP';
$lang['pep_ocupo_funciones'] = 'Ocupa u ocupò funciones en alguno  de los siguientes cargos pùblicos?';
$lang['pep_cargo_enentidad_estatal'] = 'Cargo en Entidad Estatal';
$lang['pep_entidad'] = 'Entidad';
$lang['pep_fuente_origen_losfondos'] = 'Fuente u origen de los fondos';
$lang['pep_fecha_desde'] = 'Fecha desde';
$lang['pep_fecha_hasta'] = 'Fecha hasta';
$lang['pep_tipo_vinculo'] = 'Tipo de vinculo';
$lang['pep_allegado_pep'] = 'Allegado PEP';
$lang['pep_primer_nombre'] = 'Primer Nombre';
$lang['pep_segundo_nombre'] = 'Segundo Nombre';
$lang['pep_primer_apellido'] = 'Primer apellido';
$lang['pep_segundo_apellido'] = 'Segundo Apellido';
$lang['pep_apellido_casada'] = 'Apellido de Casada';
$lang['pep_nro_id'] = 'Nro de ID';
$lang['pea_cargo_entidad_estatal'] = 'Cargo en Entidad Estatal';
$lang['pea_tipo_persona'] = 'Tipo de persona';
$lang['pea_persona'] = 'Persona  ';
$lang['pea_tipo_vinculo'] = 'Tipo de vinculo';
$lang['pea_primer_nombre_razonsocial'] = 'Primer nombre/razon social';
$lang['pea_segundo_nombre'] = 'Segundo Nombre';
$lang['pea_primer_apellido'] = 'Primer apellido';
$lang['pea_segundoapellido'] = 'Segundo apellido';
$lang['pea_apellido_casada'] = 'Apellido de Casada';
$lang['pea_nro_id'] = 'Nro. De ID';

// -- Listado FIN


$lang["terceros_geo1"] = "Geo Domicilio";
$lang["terceros_geo2"] = "Geo Trabajo";

$lang["terceros_agente_nombre"] = "Nombre Agente";
$lang["terceros_agente_agencia"] = "Agencia Agente";

$lang["terceros_nombre_persona"] = "Nombre";
$lang["terceros_ci"] = "CI";
$lang["terceros_estado_civil"] = "Estado Civil";
$lang["terceros_email"] = "Email";
$lang["terceros_telefono"] = "Teléfono";
$lang["terceros_nit"] = "NIT";
$lang["terceros_pais"] = "País";
$lang["terceros_profesion"] = "Profesión";
$lang["terceros_ingreso"] = "Ingreso";
$lang["terceros_conyugue_nombre"] = "Nombre del Cónyuge";
$lang["terceros_conyugue_actividad"] = "Actividad del Cónyuge";
$lang["terceros_referencias"] = "Referencias";
$lang["terceros_actividad_principal"] = "Actividad Principal";
$lang["terceros_lugar_trabajo"] = "Lugar de Trabajo";
$lang["terceros_cargo"] = "Cargo Actual";
$lang["terceros_ano_ingreso"] = "Año de Ingreso";


$lang["terceros_fecha"] = "Fecha Registro";
$lang["terceros_estado"] = "Estado";
$lang["terceros_rekognition"] = "Reconocimiento Facial";
$lang["terceros_rekognition_similarity"] = "% Similaridad";
$lang["rechazado"] = "Rechazado";
$lang["terceros_observacion"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> Rechazo - Justificación";
$lang["rechazado_fecha"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> Rechazo - Fecha";
$lang["rechazado_usuario"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> Rechazo - Usuario";
$lang["rechazado_envia"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> Rechazo - Se notificó al cliente";
$lang["rechazado_texto"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> <i class='fa fa-angle-double-right' aria-hidden='true'></i> Rechazo - Texto Personalizado";

$lang["adjuntos_enviados"] = "Adjuntos Enviados";

$lang["completado_docs_enviados"] = "Completado - Adj. Env.";
$lang["rechazado_docs_enviados"] = "Rechazado - Adj. Env.";
$lang["codigo_agencia_fie"] = "Agencia Asociada";

// -- Req. Consulta COBIS y SEGIP
$lang["ValOperTitulo"] = "Validación Operativa";
$lang["ValOperSubtitulo"] = "Validación de la información registrada del solicitante, Certificado SEGIP y elementos digitalizados. Al seleccionar el resultado de la validación como 'Satisfactorio' se actualizará el estado a 'Aprobado' y podrá continuar con el flujo de trabajo normal, caso contrario se marcará como 'Rechazado'.";
$lang["segip_operador_resultado_form"] = "Resultado de la Validación";
$lang["segip_operador_texto_form"] = "Texto (Opcional. 250car.)";
$lang["segip_operador_pregunta"] = "Marcar la Validación Operativa de la información del solicitante con el resultado seleccionado.";
$lang["ValOperOpcion"] = "Validación Operativa";
$lang["ws_segip_codigo_resultado"] = "Código Obtenido SEGIP";
$lang["ws_cobis_codigo_resultado"] = "Código Obtenido COBIS";

$lang["ws_selfie_codigo_resultado"] = "Código Obtenido Prueba de Vida";

$lang["ws_ocr_codigo_resultado"] = "Código Obtenido OCR";
$lang["ws_ocr_name_valor"] = "Valor Obtenido OCR";
$lang["ws_ocr_name_similar"] = "Similaridad Obtenida OCR";

$lang["ws_segip_flag_verificacion"] = $lang["ValOperOpcion"] . " - Solicitado";
$lang["segip_operador_resultado"] = $lang["ValOperOpcion"] . " - Resultado";
$lang["segip_operador_fecha"] = $lang["ValOperOpcion"] . " - Resultado Fecha";
$lang["segip_operador_usuario"] = $lang["ValOperOpcion"] . " - Resultado Usuario";
$lang["segip_operador_texto"] = $lang["ValOperOpcion"] . " - Resultado Texto";

$lang["f_registro_cobis"] = "Flujo registro COBIS";
$lang["f_cobis_actual_etapa"] = $lang["f_registro_cobis"] . " - Etapa Actual";
$lang["f_cobis_actual_intento"] = $lang["f_registro_cobis"] . " - Intento Registrado";
$lang["f_cobis_actual_usuario"] = $lang["f_registro_cobis"] . " - Usuario";
$lang["f_cobis_actual_fecha"] = $lang["f_registro_cobis"] . " - Fecha";
$lang["f_cobis_codigo"] = $lang["f_registro_cobis"] . " - Código Cliente";
$lang["f_cobis_excepcion"] = $lang["f_registro_cobis"] . " - Excepción";
$lang["f_cobis_excepcion_motivo"] = $lang["f_registro_cobis"] . " - Motivo";
$lang["f_cobis_excepcion_motivo_texto"] = $lang["f_registro_cobis"] . " - Motivo (detalle)";
$lang["f_cobis_flag_rechazado"] = $lang["f_registro_cobis"] . " - Flag Fin Flujo";
$lang["f_cobis_tracking"] = $lang["f_registro_cobis"] . " - Tracking";

$lang["flujo_registro_cobis"] = "<i>Flujo de registro COBIS</i>";
$lang["f_cobis_tracking_pnl_resultado"] = "<div style='text-align: justify;'>El proceso " . $lang["flujo_registro_cobis"] . " está sujeto a validaciones y lógica de reintentos establecidas, por lo que el resultado no necesariamente será inmediato.<br /><br /><i class='fa fa-info-circle' aria-hidden='true'></i> Puede volver a la bandeja de registros o revisar el tracking del " . $lang["flujo_registro_cobis"] . " en la siguiente tabla (misma opción puede revisarlo en el Detalle del Registro " . PREFIJO_TERCEROS . "%d).</div>";

// Panel de configuración

$lang["ws_soa_fie_error"] = "Error comunicación SOA-FIE. Revise logs.";

// Token
$lang["conf_token_dimension"] = "Dimensión";
$lang["conf_token_dimension_ayuda"] = "Especifique la cantidad de caracteres para la generación del token. Entre 2 y 14.";
$lang["conf_token_otp"] = "One Time Access";
$lang["conf_token_otp_ayuda"] = "(Más seguro, menos flexible) 'Si': El token podrá ser validado (al continuar el registro a través del link) sólo 1 vez, por lo que el usuario al actualizar la página o ingresar nuevamente con el token, estará marcado como expirado. 'No': El usuario podrá utilizar el link las veces que requiera hasta que sea eliminado por el tiempo de vigencia.";
$lang["conf_token_validez"] = "Tiempo de Vigencia/Validez (en minutos)";
$lang["conf_token_validez_ayuda"] = "Es el tiempo (en minutos) permitido para que el usuario complete su registro en el front-end. Establezca un tiempo adecuado para que el usuario pueda completar su registro; una vez que el token expire el registro no podrá ser guardado y el usuario deberá volver a comenzar desde el inicio.";
$lang["conf_token_texto"] = "Texto de token expirado/inválido (Máx 280 car.)";

// WS JWT
$lang["conf_jwt_ws_uri"] = "URI WS JWT";
$lang["conf_jwt_client_secret"] = "Client Secret";
$lang["conf_jwt_username"] = "Username";
$lang["conf_jwt_password"] = "Password";
$lang["conf_jwt_ws_uri_ayuda"] = "Los parámetros " . $lang["conf_jwt_client_secret"] . ", " . $lang["conf_jwt_username"] . " y " . $lang["conf_jwt_password"] . " deben estár registrados. Debe realizar primeramente el test exitoso de éste servicio (ya que obtendrá el token de autenticación) para poder realizar pruebas en los demás servicios. Puede Mostrar/Ocultar los parámetros";

$lang["conf_soa_fie_ayuda"] = "Los parámetros " . $lang["conf_jwt_client_secret"] . ", " . $lang["conf_jwt_username"] . " y " . $lang["conf_jwt_password"] . " deben estár registrados (guardados). Para realizar el test de este servicio, previamente debe ejecutar el test del servicio JWT para así obtener un token válido y vigente.";

// INTENTOS WIDGET
$lang["conf_captura_intentos"] = "Número de Intentos capturas y/o lecturas para los Widgets";
$lang["conf_captura_intentos_ayuda"] = "Es la cantidad máxima de veces que el usuario podrá interactuar con cada Widget incluyendo si el servicio indica que se vuelva a intentar. Al llegar al número de intentos, de acuerdo al flujo establecido, se permitirá continuar marcando " . $lang["ValOperTitulo"] . " o terminando el flujo dependiendo el caso.";
$lang["conf_captura_intentos_texto"] = "Texto de reintento capturas y/o lecturas (Máx 80 car.)";

// WS COBIS
$lang["conf_cobis_ws_uri"] = "URI WS COBIS y SEGIP";
$lang["conf_cobis_texto"] = "COBIS - Texto respuesta error, fin del flujo (Máx 280 car.)";

$lang["conf_cobis_mandatorio"] = "COBIS - Validación Bloqueante";
$lang["conf_cobis_mandatorio_ayuda"] = "'Si': Si es cliente activo se finaliza el proceso y se muestra el mensaje parametrizado. 'No': Se podrá continuar con " . $lang["ValOperTitulo"] . ".";

$lang["conf_cobis_tipo_error"] = "COBIS - Tipo de error seleccionado";
$lang["conf_cobis_tipo_error_ayuda"] = "Cuando se marque la " . $lang["ValOperTitulo"] . ", al ser Cliente Activo, se registrará la observación con el tipo de error del catálogo SEGIP seleccionado. Si lo requiere puede aumentar un nuevo valor en el módulo de Catálogos del Sistema (cuando no se reciba algún código en la respuesta).";

//WS SEGIP
$lang["conf_segip_ws_uri"] = "URI WS SEGIP";
$lang["conf_segip_texto"] = "SEGIP - Texto respuesta error, fin del flujo (Máx 280 car.)";
$lang["conf_segip_mandatorio"] = "SEGIP - Validación Mandatoria";
$lang["conf_segip_mandatorio_ayuda"] = "'Si': Al no recibir resultado satisfactorio de SEGIP se rechazará el registro. 'No': Se podrá continuar con " . $lang["ValOperTitulo"] . ".";
$lang["conf_segip_intentos"] = "SEGIP - Número de Intentos";
$lang["conf_segip_intentos_ayuda"] = "Cada intento considera toda la secuencia de consulta configurada.";

// WS PRUEBA DE VIDA
$lang["conf_life_ws_uri"] = "URI WS Prueba de Vida";
$lang["conf_life_tipo_error"] = "Tipo de error seleccionado";
$lang["conf_life_tipo_error_ayuda"] = "Cuando se marque la " . $lang["ValOperTitulo"] . ", se registrará la observación con el tipo de error del catálogo SEGIP seleccionado. Si lo requiere puede aumentar un nuevo valor en el módulo de Catálogos del Sistema (cuando no se reciba algún código en la respuesta).";
$lang["conf_life_texto"] = "Texto respuesta error, fin del flujo Prueba de Vida (Máx 280 car.)";

// WS OCR
$lang["conf_ocr_ws_uri"] = "URI WS OCR";
$lang["conf_ocr_porcentaje"] = "Porcentaje de coincidencia del nombre concatenado (OCR)";
$lang["conf_ocr_porcentaje_ayuda"] = "Es el porcentaje de coincidencia del nombre concatenado en el registro de datos personales y el valor obtenido en el OCR.";

$lang["conf_ocr_tipo_error"] = "Tipo de error seleccionado";
$lang["conf_ocr_tipo_error_ayuda"] = "Cuando se marque la " . $lang["ValOperTitulo"] . ", al no tener el porcentaje de similaridad establecido con el nombre concatenado, se registrará la observación con el tipo de error del catálogo SEGIP seleccionado. Si lo requiere puede aumentar un nuevo valor en el módulo de Catálogos del Sistema (cuando no se reciba algún código en la respuesta).";
$lang["conf_ocr_texto"] = "Texto respuesta error, fin del flujo (OCR-CI no coincide | Documento no boliviano) (Máx 280 car.)";

$lang["conf_test_ws_titulo"] = "<i class='fa fa-plug' aria-hidden='true'></i> Test Web Service";

// WS FLUJO COBIS

$lang["bandeja_procesando"] = "Pendiente";

$lang["conf_f_cobis_procesa_activo"] = "Procesar clientes activos";
$lang["conf_f_cobis_procesa_activo_ayuda"] = "Si se parametriza que SI, se puede proceder con el registro de apertura de la cuenta para clientes activos con el flujo establecido. Si se parametriza que NO, se registrará la solicitud en estado 'Pendiente' en la bandeja de Onboarding para su respectiva verificación y según corresponda procesamiento en Agencia.";

$lang["conf_f_cobis_header"] = "Parámetro serviceHeader";
$lang["conf_f_cobis_header_ayuda"] = "Este parámetro 'serviceHeader' es requerido para el consumo de los servicios del flujo COBIS. Similar al JWT, primeramente, debe registrar este parámetro antes de realizar testing de los servicios.";

$lang["conf_f_cobis_intentos"] = "Intentos permitidos";
$lang["conf_f_cobis_intentos_ayuda"] = "Es la cantidad de intentos permitidos del flujo de registro COBIS (mediante web services, generación PDF del contrato y envío de correo al cliente). Al alcanzar el límite de intentos deberá procesarse el registro operativamente (manual).";
$lang["conf_f_cobis_intentos_tiempo"] = "Tiempo entre reintentos";
$lang["conf_f_cobis_intentos_tiempo_ayuda"] = "Defina el tiempo entre reintentos. La tarea programada revisa cada minuto los registros marcados como 'reintento' y procesará aquellos que aún no hubieran alcanzado la cantidad máxima de reintentos desde la última etapa registrada.";

$lang["conf_f_cobis_intentos_operativo"] = "Permitir reintento operativo";
$lang["conf_f_cobis_intentos_operativo_ayuda"] = "Este parámetro aplica a la opción 'Aprobar Solicitud' de la bandeja '" . $lang["bandeja_procesando"]  . "'. Si selecciona la opción 'No' (por defecto) los registros que tengan marcado el flag '" . $lang["fin_flujo_cobis"] . "' no podrán efectuar un reintento de forma operativa, siendo necesario que sean procesados manualmente. Si selecciona la opción 'Si', permitirá el reintento operativo de los registros al flujo COBIS desde la última etapa registrada. Nota: indistintamente de la opción seleccionada, las demás validaciones, como la cantidad máxima de intentos, permanecen activas.";

$lang["conf_f_cobis_uri_cliente_ci"] = "URI WS searchNatural";
$lang["conf_f_cobis_uri_cliente_cobis"] = "URI WS searchNaturalCustomers";
$lang["conf_f_cobis_uri_cliente_alta"] = "URI WS Client";
$lang["conf_f_cobis_uri_apertura"] = "URI WS AccountAffiliation";

$lang["conf_f_cobis_uri_cliente_alta_params"] = "Parámetros de autorización (o prioritarios por defecto) " . $lang["conf_f_cobis_uri_cliente_alta"] ;
$lang["conf_f_cobis_uri_cliente_alta_params_ayuda"] = "Según el ambiente del sistema, registre los parámetros y valores de autorización (o prioritarios por defecto) que se envían al servicio, como ser 'user', 'officeId', etc. Los parámetros deben estar registrados en formato JSON. IMPORTANTE mencionar que, si se registran parámetros que ya son enviados por el servicio, los registrados en este apartado tendrán mayor prioridad y remplazarán de forma recursiva a los enviados por el servicio.";

$lang["conf_f_cobis_uri_apertura_params"] = "Parámetros de autorización (o prioritarios por defecto) " . $lang["conf_f_cobis_uri_apertura"] ;
$lang["conf_f_cobis_uri_apertura_params_ayuda"] = $lang["conf_f_cobis_uri_cliente_alta_params_ayuda"];

// SOLICITUD DE CRÉDITO

$lang["conf_credito_texto"] = "Texto personalizado al completar el registro (Web)";
$lang["conf_credito_texto_ayuda"] = "Es el texto personalizado que se muestra en la web al completar el registro";
$lang["conf_credito_notificar_email"] = "Notificar por Correo Electrónico";
$lang["conf_credito_notificar_email_ayuda"] = "Aún cuando la opción sea 'Si', sólo se notificará si el Correo Electrónico fue registrado.";

$lang["conf_credito_notificar_sms_bearer"] = "Bearer Authentication WS SMS";
$lang["conf_credito_notificar_sms_bearer_ayuda"] = "Es la autenticación por cabecera 'bearer' utilizada para el servicio SMS";
$lang["conf_credito_notificar_sms"] = "Notificar por SMS";
$lang["conf_credito_notificar_sms_ayuda"] = "La notificación por SMS tiene un costo adicional que no es regulado ni gestionado por el sistema. Si requiere hacer pruebas en un ambiente controlado o interno, puede marcar la opción 'No' y así no consumir el servicio.";
$lang["conf_credito_notificar_texto"] = "Texto personalizado para la notificación (Máx 140 car.)";
$lang["conf_credito_notificar_texto_ayuda"] = "Es el texto personalizado para la notificación por SMS (y también en el correo si fue configurado en la plantilla de notificación). Puede registrar hasta 140 caracteres; procure no utilizar caracteres especiales o aquellos que no se visualicen correctamente en el SMS. Tip: Puede utilizar la variable {destinatario_nombre} para indicar el primer nombre del solicitante.";
$lang["conf_credito_tipo_cambio"] = "Tipo de Cambio";

// CÁLCULO DE LA CUOTA

$lang["conf_cuota_tasa_seguro"] = "Tasa del Seguro";
$lang["conf_cuota_tasa_seguro_ayuda"] = "Puede registrar la Tasa del Seguro. Si requiere indicar más de una tasa puede hacerlo separado por punto-y-coma (ej: '0.50;0.65'). Por defecto para el cálculo de la cuota se registrará el primer valor de la lista y el usuario podrá seleccionar otra de la lista si corresponde. ";



$lang["cobis"] = "Registrado en COBIS";
$lang["cobis_fecha"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> Registrado en COBIS - Fecha";
$lang["cobis_usuario"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> Registrado en COBIS - Usuario";

$lang["aprobado"] = "Aprobado";
$lang["aprobado_fecha"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> Aprobar - Fecha";
$lang["aprobado_usuario"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> Aprobar - Usuario";

$lang["tercero_asistencia"] = "Proceso";

$lang["completado"] = "Completado";
$lang["completado_fecha"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> Completado - Fecha";
$lang["completado_usuario"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> Completado - Usuario";
$lang["completado_envia"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> Completado - Se notificó al cliente";
$lang["completado_texto"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i>  <i class='fa fa-angle-double-right' aria-hidden='true'></i> Completado - Texto Personalizado";

$lang["entregado"] = "Entregado al Cliente";
$lang["entregado_fecha"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> Entregado - Fecha";
$lang["entregado_usuario"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> Entregado - Usuario";
$lang["entregado_texto"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i>  Entregado - Texto Personalizado";

$lang["onboarding_numero_cuenta"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> Número de Cuenta (COBIS)";

$lang["conf_rekognition"] = "Habilitar Validación de Reconocimiento Facial";
$lang["conf_rekognition_key"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> AWS Rekognition Key";
$lang["conf_rekognition_secret"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> AWS Rekognition Secret";
$lang["conf_rekognition_region"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> AWS Rekognition Region";
$lang["conf_rekognition_similarity"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> AWS Rekognition % Similaridad";
$lang["conf_img_width_max"] = "Imagen - Ancho Máximo pixeles";
$lang["conf_img_width_min"] = "Imagen - Ancho Mínimo pixeles";
$lang["conf_img_height_max"] = "Imagen - Alto Máximo pixeles";
$lang["conf_img_height_min"] = "Imagen - Alto Mínimo pixeles";
$lang["conf_img_peso"] = "Imagen - Peso Máximo en MB";



// ----------- FORMULARIOS ONBOARDING FIN -----------

// ----------- FORMULARIOS DINÁMICOS INICIO -----------

$lang["AccionesTitulo"] = "Acciones";
$lang["errorGuardarInstancia"] = "Error al guardar el formulario.";
$lang["errorNoEncontrado"] = "No existe el registro solicitado.";

$lang["FormularioDinamicoTitulo"] = "Formularios Dinámicos";
$lang["FormularioDinamicoSubtitulo"] = "Explicación detallada pero puntual de las acciones que el usuario puede realizar. Este texto esta en archivo de lenguaje";

$lang["FormularioDinamicoNuevo"] = "Nuevo Formulario";
$lang["FormularioDinamicoEdicion"] = "Editar Formulario";
$lang["FormularioDinamicoNuevoCaso"] = "Nuevo caso";
$lang["FormularioDinamicoInstancia"] = "Ver todas las instancias de cada formulario";
$lang["FormularioDinamicoNombre"] = "Nombre";
$lang["FormularioDinamicoDescripcion"] = "Descripcion";
$lang["FormularioDinamicoPublicado"] = "Estado";

$lang["conf_formulario_insertar"] = "Guardar el nuevo formulario creado";

$lang["conf_formulario_eliminar"] = "Eliminar el formulario ";

$lang["conf_instancia_eliminar"] = "Eliminar la instancia seleccionada ";

// ----------- FORMULARIOS DINÁMICOS FIN -----------

// MENSAJES AL USUARIO

$lang["Self-XSS"] = "Este apartado es sólo para desarrolladores. Por favor no ingrese o haga correr ningún código aquí, cualquier intento de Self-XSS en el '" . $lang["NombreSistema"] . "' será pasible a las acciones legales pertinentes.";

$lang["NoAutorizado"] = "<br />ACCESO DENEGADO - El acceso no autorizado será pasible a las acciones legales pertinentes.<br /><br />";

$lang["NoAutorizadoPerfil"] = "No tiene los permisos requeridos para ver la información. Si lo requiere comuníquese con el Administrador.";

$lang["RequiereRenovarPass"] = "<i> HAS SUPERADO EL TIEMPO MÁXIMO DE VALIDEZ DE TU CONTRASEÑA. DEBES RENOVARLA PARA PODER CONTINUAR </i>";

$lang["UsuarioIncorrecto"] = "El nombre de usuario elegido ya está en uso. Por favor elija otro.";
$lang["UsuarioError"] = "No puede utilizar el nombre de usuario elegido, por favor defina uno válido.";
$lang["UsuarioError_corto"] = "El nombre de usuario elegido es muy corto, por favor defina uno válido.";

$lang["UsuarioErrorCantidad"] = "Ha superado la cantidad de usuarios permitida para su instancia. Si requiere mayor cantidad o cree que esto es un error, por favor comuníquese con su representante Initium.";

$lang["PasswordAnterior"] = "La contraseña actual no es correcta.";

$lang["PasswordNoCoincide"] = "La contraseña repetida no coincide.";

$lang["PasswordRepetido"] = "La nueva contraseña no puede ser igual a la actual.";

$lang["PasswordNoRenueva"] = "No puede renovar su contraseña ahora. La duración mínima de la contraseña es de ";

$lang["PasswordNoAceptado"] = "El nombre de usuario no es correcto, por favor elija otro.";

$lang["FormularioIncompleto"] = "Por favor debe completar todos los campos.";

$lang["FormularioNoDetalle"] = "Debe registrar la Observación o Justificación.";

$lang["FormularioNoPreguntas"] = "Para continuar debe responder afirmativamente a los requisitos indicados.";

$lang["FormularioRegistroExiste"] = "Ya existe un registro con la información proporcionada, por favor revise el formulario.";

$lang["FormularioNoEjecutivo"] = "El usuario seleccionado, ya está asignado como Oficial de Negocio, seleccione otro, o si requiere asignar Clientes/Mantenimientos, puede \"Transferir Cuentas\"";

$lang["CamposObligatorios"] = "Favor revise:";

$lang["CamposRequeridos"] = "Porfavor complete la info con el tipo de dato correcto.";

$lang["CamposCorreo"] = "El Correo Electrónico no es correcto.";

$lang["FormularioNoGeo"] = "Aún no definió la geolocalización.";

$lang["FormularioNIT"] = "No se puede registrar el Comercio, el NIT ya se encuentra registrado.";

$lang["FormularioNo_NIT"] = "No existe un Comercio Afiliado con el NIT indicado.";

$lang["FormularioNIT_revision"] = "La empresa con el NIT indicado no está afiliada, se encuentra en revisión o como Cliente.";

$lang["FormularioSinOpciones"] = "No seleccionó ninguna opción para procesar la solicitud.";

$lang["FormularioDocumentoError"] = "El documento no fue digitalizado o es incorrecto.";

$lang["FormularioNoEnviadoPre"] = "Antes de consolidar, debe enviar la Documentación del Cliente a Cumplimiento.";

$lang["FormularioYaEnviadoPre"] = "El Cliente ya fue remitido a Cumplimiento.";

$lang["FormularioNoAprobadoPre"] = "El Cliente aún se encuentra en evaluación de pre- Verificación.";

$lang["FormularioNoRequiereCumplimiento"] = "El tipo de empresa (Establecimiento) no requiere ser remitido a cumplimiento.";

$lang["FormularioYaConsolidado"] = "El Cliente está consolidado, no se puede realizar modificaciones.";

$lang["FormularioForzarConsolidado"] = "Al continuar con esta opción, se procederá con la consolidación del Cliente, se remitirá a la instancia respectiva (dentro del flujo de trabajo) y se marcarán todas las observaciones activas como subsanadas. ¿Confirma que quiere continuar?";
$lang["ConsolidarSinEvaluacion"] = "No Consolidado: Aún no realizó la evaluación del Cliente, por favor debe realizar la evaluación para poder Consolidar.!!";

$lang["FormularioNoNotificacion"] = "No se pudo notificar a la instancia respectiva, porfavor vuelva a intentarlo. Si este mensaje persiste comuníquese con el administrador.";

$lang["FormularioNoEnvio"] = "No se envió los documentos a la Empresa Aceptante, por favor verifique que la dirección de correo de la Empresa Aceptante y los documentos solicitados sean correctos y vuelva a intentarlo.";

$lang["FormularioYaCompletado"] = "El mantenimiento ya fue completado, no se puede realizar modificaciones.";

$lang["FormularioNoEncontroNIT"] = "No se encontraron resultados, verifique que el NIT sea correcto. Si la empresa está registrada en el CORE, solicite al administrador que la registre en el sistema y que se le asigne a usted.";

$lang["FormularioYaEntregado"] = "El servicio ya se marcó como entregado, no puede realizar la acción solicitada.";

$lang["FormularioFechas"] = "Las fechas están incorrectas.";

$lang["FormularioFiltros"] = "Debe seleccionar al menos un criterio.";

$lang["FormularioLongInvalido"] = "Por favor debe establecer una longitud mayor a 0.";

$lang["FormularioLongInvalido2"] = "La longitud mínima no puede ser mayor a la longitud máxima.";

$lang["FormularioTiempoInvalido"] = "Por favor debe establecer un tiempo de bloqueo mayor a 0.";

$lang["CorreoFallo"] = "Paso algo al enviar el correo electrónico. Por favor revise su configuración.";

$lang["FormularioNoFile"] = "No seleccionó ningún documento para subir al sistema o no es correcto. Por favor vuelva a intentarlo.";

$lang["MenuPrincipal"] = "Menú Principal";

$lang["exportar"] = "EXPORTAR";

$lang["titulo_regiones_supervisadas"] = "Agencia(s) Supervisada(s)";

$lang["IncompletoApp"] = "No se realizó la operación.";

// AYUDA EN PANTALLA

$lang["Ayuda_password"] = "Debe tener mínimamente";

$lang["Ayuda_usuario_activo"] = "Si el usuario no está activado, no podrá acceder al sistema.";

$lang["Ayuda_variables_correo"] = "Puede usar las siguientes variables en la plantilla de correo. Sólo copie y pegue la variable donde requiera que aparezca. Considere que no todas las variables aplican para todas plantillas.";

$lang["Ayuda_categoria_catalogo"] = "TPS: Tipo de Sociedad<br />RUB: Rubro<br />PEC: Perfil Comercial<br />MCC: MCC<br />MCO: Medio de Contacto<br />DEP: Departamento<br />CIU: Municipio/Ciudad<br />ZON: Zona/Localidad<br />TPC: Tipo de Calle";

$lang["MostrarOcultar"] = " <i class='fa fa-eye' aria-hidden='true'></i> Mostrar/Ocultar";

// PREGUNTA CONFIRMACIÓN

$lang["PreguntaTitulo"] = "VAMOS A HACER ESTO:";
$lang["PreguntaContinuar"] = "¿ESTAS SEGURO? ";
$lang["BotonAceptar"] = "PROCEDER";
$lang["BotonAceptar_enviar"] = " <i class='fa fa-handshake-o' aria-hidden='true'></i> Enviar mi Solicitud Ahora!";
$lang["BotonCancelar"] = "VOLVER";
$lang["BotonSalir"] = "CANCELAR";

$lang["BotonContinuar"] = "CONTINUAR";

$lang["BotonVolver1"] = "<== VOLVER AL PASO 1";
$lang["BotonVolver2"] = "<== VOLVER AL PASO 2";

$lang["BotonContinuar1"] = "CONTINUAR AL PASO 2 ==>";
$lang["BotonContinuar2"] = "CONTINUAR AL PASO 3 ==>";

$lang["Correcto"] = " <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> La Acción Solicitada se Efectuó Correctamente";

// MENU PRINCIPAL

$lang["CreditoMoneda"] = "Mensaje(s)";

$lang["CambiarPass"] = "Renovar mi Contraseña";

// TEXTO DE LOS BOTÓNES / OPCIONES

$lang["TablaOpciones"] = "Opciones";
$lang["TablaOpciones_asignar_perfil"] = "Asignar <br /> Perfil";
$lang["TablaOpciones_Editar"] = "Editar <br />Registro";
$lang["TablaOpciones_Detalle"] = "Ver <br />Detalle";
$lang["TablaOpciones_NuevoUsuario"] = "Registrar <br /> Usuario";
$lang["TablaOpciones_Seleccionar"] = "Seleccionar";
$lang["TablaOpciones_CargarPlantilla"] = "Cargar Plantilla";

$lang["TablaOpciones_TransferirAgencia"] = "Transferir<br />Agencia";

$lang["TablaOpciones_enviar_correo"] = "Enviar <br />Correo";

$lang["TablaOpciones_Restablecer"] = "Restablecer Contraseña";

$lang["TablaOpciones_CampanaNumerosNuevo"] = "(+) Nuevo";
$lang["TablaOpciones_CampanaNumerosQuitar"] = "Quitar";
$lang["TablaOpciones_ExportaExcel"] = "EXPORTAR A EXCEL";
$lang["TablaOpciones_ExportaPDF"] = "EXPORTAR A PDF";

$lang["TablaOpciones_VerDocumento"] = "Ver <br /> Documento";
$lang["TablaOpciones_SubirDocumento"] = "Cargar Documento PDF";

$lang["TablaOpciones_Rechazar"] = "Rechazar<br />Solicitud";
$lang["TablaOpciones_actualizar_data"] = "Actualizar<br />Información";

$lang["TablaOpciones_aceptar_solicitud"] = "Aprobar<br />Solicitud";

$lang["TablaNoRegistrosDetalle"] = "<div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> Aún No se Registró Información <br /> Puede crear desde NUEVO REGISTRO </div>";

$lang["TablaNoRegistros"] = "<div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> No se Registró Información </div>";

$lang["TablaNoRegistrosMinimo"] = "<i class='fa fa-meh-o' aria-hidden='true'></i> Sin Registros";

$lang["TablaNoResultados"] = "NO SE ENCONTRARON REGISTROS";

$lang["TablaNoObservaciones"] = "<div class='PreguntaConfirmar'> <i class='fa fa-smile-o' aria-hidden='true'></i> ¡Hooray! No hay Observaciones Registradas </div>";

$lang["TablaNoPendientes"] = "<div class='PreguntaConfirmar'> <i class='fa fa-smile-o' aria-hidden='true'></i> ¡Hooray! Todo al Día por Aquí </div>";

$lang["TablaNoResultadosBusqueda"] = "No se encontraron resultados con los criterios enviados. Por favor intente con otros criterios.";

$lang["TablaNoResultadosReporte"] = "<div class='mensajeBD'> <br /> <i class='fa fa-meh-o' aria-hidden='true'></i> No se encontraron resultados con los criterios indicados <br /><br /></div>";

$lang["TablaOpciones_CampanaEstado1"] = "En Espera";
$lang["TablaOpciones_CampanaEstado2"] = "Pendiente";
$lang["TablaOpciones_CampanaEstado3"] = "Completado";


// IMPORTACIÓN MASIVA DE LEADS

$lang["ImportacionFormTitulo"] = "Cargado masivo de Clientes";
$lang["ImportacionFormSubtitulo"] = "En este apartado podrá seleccionar un archivo en formato .XLS (Excel) con el formato ya establecido, para proceder con la subida masiva de los registros y asignación a los agentes respectivos.";
$lang["ImportacionFormDoc"] = "Proceda a cargar el archivo";
$lang["ImportacionFormDocAyuda"] = "Evite que el archivo contenga pestañas adicionales en el archivo que causen mayor tamaño de subida. Recuerde mantener el formato del archivo de subida";
$lang["TablaOpciones_SubirExcel"] = "Cargar Documento Excel";

$lang["import_agente"] = "Oficial de Negocios";
$lang["import_campana"] = "Rubro";
$lang["import_idc"] = "IDC";
$lang["import_nombre_cliente"] = "Nombre Cliente";
$lang["import_empresa"] = "Empresa";
$lang["import_ingreso"] = "Ingreso";
$lang["import_direccion"] = "Dirección";
$lang["import_direccion_geo"] = "Geolocalización del Cliente";
$lang["import_telefono"] = "Teléfono";
$lang["import_celular"] = "Celular";
$lang["import_correo"] = "Email";
$lang["import_matricula"] = "Matrícula Asignada";
$lang["import_tipo_lead"] = "Tipo Registro";
$lang["import_matricula_corto"] = "Matrícula";

$lang["lead_primer_contacto"] = "Fecha 1° Contacto";

$lang["lead_monto_aprobacion"] = "Monto Aprobación";
$lang["lead_monto_desembolso"] = "Monto Desembolso";
$lang["prospecto_fecha_desembolso"] = "Fecha Desembolso";

$lang["lead_seguimiento_agente"] = "<i class='fa fa-signal' aria-hidden='true'></i> Detalle Avance Oficial de Negocio";

$lang["ImportacionResultadoTitulo"] = "Resultado de la Importación: ";
$lang["ImportacionResultadoSubtitulo"] = "En este apartado podrá verificar el resultado del cargado masivo, y si corresponde la correción del formato. Estos registros, en este paso, sólo están guardados temporalmente, sólo se guardarán en la base de datos una vez que se complete el proceso.";

$lang["import_verificar"] = "IMPORTAR Y VERIFICAR";
$lang["import_guardar"] = "GUARDAR REGISTROS";

$lang["import_titulo_verificar"] = "<i class='fa fa-binoculars' aria-hidden='true'></i> Puede verificar los registros a ser guardados en la siguiente matriz:";

$lang["import_titulo_error"] = " Revise, subsane el archivo de cargado y  vuelva a intentarlo";

$lang["aprobar_importar_Pregunta"] = "Proceder a guardar los registros importados y asignarlos a los agentes indicados de acuerdo a la matrícula.<br /><br />Esta acción podría demorar dependiendo de la cantidad de registros.";

$lang["lead_seg_estado_operacion_titulo"] = "<strong>A continuación se muestra el Resumen de las Operaciones según el Estado Registrado de las Campañas del Oficial de Negocio:</strong>";



// GESTIÓN DE CAMPAÑAS

$lang["CampanaTitulo"] = "Gestión de Rubros";
$lang["CampanaSubtitulo"] = "En este apartado podrá gestionar los Rubros permitidos para el Registro en el Sistema.";

$lang["CampanaFormTitulo"] = "Gestión de Rubros";
$lang["CampanaFormSubtitulo"] = "En este apartado podrá gestionar los Rubros permitidos para el Registro en el Sistema.";

$lang["campana_tipo"] = "Tipo";
$lang["campana_nombre"] = "Nombre";
$lang["campana_desc"] = "Descripción";
$lang["campana_plazo"] = "Plazo";
$lang["campana_monto_oferta"] = "Monto Oferta";
$lang["campana_tasa"] = "Tasa";
$lang["campana_fecha_inicio"] = "Fecha Inicio";
$lang["campana_servicios"] = "Tabs Permitidos";

$lang["campana_pregunta"] = "Actualizar la información de la Estructura. Los datos modificados del Rubro podrían modificar los resultados y reportes generados.";

$lang["campana_nombre_error"] = "El nombre de la campaña ya está en uso, por favor elija otro.";

// REGISTRO WEB

$lang["RegistroWebTitulo"] = "Registro de Solicitudes de Visita";
$lang["RegistroWebSubtitulo"] = "En este apartado podrá registrar las solicitudes de Nuevos Registros o Tareas";

$lang["RegistroWeb_afiliacion"] = "Cliente Nuevo<br /> Registro";
$lang["RegistroWeb_mantenimiento"] = "Cliente Registrado <br /> Contácta a tu agente";

$lang["RegistroWeb_menu"] = "Registro de Solicitudes de Visita <i class='fa fa-handshake-o' aria-hidden='true'></i>";

// VALORES CATÁLOGOS

$lang["Catalogo_activo1"] = "No Activo";
$lang["Catalogo_activo2"] = "Activo";

$lang["Catalogo_si"] = "Si";
$lang["Catalogo_no"] = "No";

$lang["Catalogo_no_corresponde"] = "No Corresponde";

// USUARIOS

$lang["DetalleRegistroTitulo"] = " <i class='fa fa-commenting-o' aria-hidden='true'></i> Detalle del Registro";

$lang["UsuarioTitulo"] = "Administrar Usuarios";
$lang["UsuarioSubtitulo"] = "En este apartado podrá gestionar a los usuarios del sistema, así como la estructura organizacional. Por favor complete los campos del formulario, todos los campos son obligatorios.";

$lang["Usuario_user"] = "Matrícula";
$lang["Usuario_nombre"] = "Nombre";
$lang["Usuario_app"] = "Paterno";
$lang["Usuario_apm"] = "Materno";
$lang["Usuario_email"] = "Email";
$lang["Usuario_telefono"] = "Teléfono";
$lang["Usuario_direccion"] = "Dirección";
$lang["Usuario_rol"] = "Rol";
$lang["Usuario_perfil"] = "Perfil";
$lang["Usuario_activo"] = "¿Activo?";
$lang["Usuario_fecha_creacion"] = "Fecha Registro";
$lang["Usuario_fecha_acceso"] = "Fecha Último Ingreso";
$lang["Usuario_fecha_password"] = "Fecha Última Contraseña";


$lang["Usuario_pass1"] = "Contraseña Actual";
$lang["Usuario_pass2"] = "Ingrese su Nueva Contraseña";
$lang["Usuario_pass3"] = "Repita su Contraseña";

$lang["UsuarioPregunta"] = "Registrar Información del Usuario";

// CAMBIAR CONTRASEÑA

$lang["PassTitulo"] = "Renovar mi Contraseña";
$lang["PassSubtitulo"] = "Ingrese su contraseña anterior y su nueva contraseña.";
$lang["PassPregunta"] = "Restablecer la contraseña del usuario seleccionado a la definida por defecto.";

// CONFIGRUACIÓN - CREDENCIALES

$lang["conf_credmenu_Titulo"] = "Configuración de Credenciales y Gestión de Roles";
$lang["conf_credmenu_Subtitulo"] = "Por favor seleccione la opción requerida continuar.";

$lang["conf_credenciales_Titulo"] = "Configuración de Credenciales";
$lang["conf_credenciales_Subtitulo"] = "Para configurar los parámetros de las credenciales de usuario, por favor complete todos los campos del formulario.";

$lang["conf_credenciales_long_min"] = "Longitud Mínima";
$lang["conf_credenciales_long_max"] = "Longitud Máxima";
$lang["conf_credenciales_req_upper"] = "Requiere al menos una Mayúscula";
$lang["conf_credenciales_req_num"] = "Requiere al menos un Número";
$lang["conf_credenciales_req_esp"] = "Requiere al menos un Caractér Especial";
$lang["conf_credenciales_duracion_min"] = "Duración Mínima de la Contraseña (Días)";
$lang["conf_credenciales_duracion_max"] = "Duración Máxima de la Contraseña (Días)";
$lang["conf_credenciales_tiempo_bloqueo"] = "Tiempo de Bloqueo (Minutos)";
$lang["conf_credenciales_defecto"] = "Contraseña para Restablecimiento (requiere cambio obligatorio por el usuario al primer ingreso)";
$lang["conf_ejecutivo_ic"] = "Índice de Cumplimiento para los Oficiales de Negocio ";

$lang["conf_credenciales_Pregunta"] = "Actualizar la configuración de las credenciales.";
$lang["conf_general_Pregunta"] = "Actualizar la configuración general del sistema.";

// CONFIGRUACIÓN - ENVÍO DE CORREO

$lang["conf_correo_Titulo"] = "Configuración de Envío de Correo";
$lang["conf_correo_Subtitulo"] = "Por favor complete todos los campos del formulario. Debe verificar que la configuración proporcionada sea aceptada por la configuración de su Firewall y/o políticas de seguridad de su red.";

$lang["conf_correo_protocol"] = "Protocolo";
$lang["conf_correo_smtp_host"] = "Nombre Host";
$lang["conf_correo_smtp_port"] = "Puerto";
$lang["conf_correo_smtp_user"] = "Usuario Correo";
$lang["conf_correo_smtp_pass"] = "Contraseña Correo";
$lang["conf_correo_mailtype"] = "Tipo Correo";
$lang["conf_correo_charset"] = "Codificación de Caractéres";

$lang["conf_correo_Pregunta"] = "Actualizar la configuración del envío de correo.";

// CONFIGRUACIÓN - PLANTILLA DE CORREOS

$lang["conf_plantilla_correo_Titulo"] = "Plantillas de Correo";
$lang["conf_plantilla_correo_Subtitulo"] = "En este apartado se listarán todas las plantillas disponibles del sistema, para editar el título y contenido de cada plantilla, haga clic en la plantilla que requiera editar. Puede utilizar el editor HTML incorporado  para los colores estilos de la plantilla, y al estar conforme con el resultado haga clic en \"Aceptar\"";

$lang["conf_plantilla_nombre"] = "Nombre corto de la Plantilla";
$lang["conf_plantilla_titulo_correo"] = "Título del Correo a Enviar";
$lang["conf_plantilla_variables_correo"] = "Variables disponibles";
$lang["conf_plantilla_variables_correo_def"] = "{nombre_sistema} {nombre_corto} {destinatario_nombre} {destinatario_correo} {titulo_correo} {emisor_nombre} {fecha_actual} {fecha_visita} {fecha_evento_googlecalendar} {fecha_accion} {lead_codigo_registro} {lead_solicitante} {lead_ci} {lead_email} {lead_etapa_actual} {lead_rubro} {lead_oficial_negocios} {lead_agencia} {tabla_reporte_atrasos_onboarding} {tabla_reporte_notificar_cierre} {onboarding_codigo_registro} {onboarding_rechazo_texto} {onboarding_nombre_solicitante} {onboarding_email_solicitante} {onboarding_ci_solicitante} {onboarding_fecha_registro} {onboarding_estado_actual} {onboarding_listado_adjuntos} {onboarding_completar_texto} {onboarding_numero_cuenta} {onboarding_nombre_agencia} {onboarding_tipo_cuenta} {notificar_cierre_causa} {cuenta_cerrada_causa} {f_cobis_actual_etapa} {f_cobis_actual_intento} {f_cobis_excepcion} {f_cobis_excepcion_motivo} {f_cobis_flag_rechazado} {sol_credito_codigo_registro} {sol_credito_nombre_solicitante} {sol_credito_celular} {sol_credito_email} {sol_credito_fecha_registro} {sol_credito_estado} {sol_credito_asistencia} {sol_credito_nombre_agencia} {sol_credito_nombre_solicitante} {sol_credito_mensaje_registro} {norm_codigo_registro} {norm_agente} {norm_estado} {norm_fecha_registro} {norm_nombre_agencia} {norm_nombre_caso}";

$lang["correo_calendario_titulo"] = "Visita de mi Oficial de Negocio de ATC";
$lang["correo_calendario_afiliacion"] = " - Verificación";
$lang["correo_calendario_mantenimiento"] = " - Tareas de Mantenimiento de Cartera";


// CONFIGURACIÓN GENERALES

$lang["conf_general_Titulo"] = "Configuración General del Sistema";
$lang["conf_general_Subtitulo"] = "En este apartado podrá gestionar los parámetros generales del sistema, así como el manejo del calendario y el horario y días de atención. Es <u>muy importante</u> que establezca estos parámetros con la información real de la entidad, debido a que la lógica del negocio se basará en los tiempos indicados.<br /><br />Para mayor comodidad puede expandir u ocultar las secciones.";

$lang["conf_general_key_google"] = "Key de Google para Mapas y Calendario";
$lang["conf_horario_feriado"] = "¿Mostrar Días Festivos en Calendario?";
$lang["conf_horario_laboral"] = "¿Restringir Horario de Trabajo en Calendario?";

$lang["conf_atencion_desde1"] = "Turno Mañana desde";
$lang["conf_atencion_hasta1"] = "Turno Mañana hasta";
$lang["conf_atencion_desde2"] = "Turno Tarde desde";
$lang["conf_atencion_hasta2"] = "Turno Tarde hasta";
$lang["conf_atencion_dias"] = "Días de Atención";

// AUDITORÍA

$lang["AuditoriaTitulo"] = "Auditoría del Sistema - Acciones";
$lang["AuditoriaSubtitulo"] = "En este apartado podrá ver la auditoría de las acciones realizadas en el sistema, para visualizar la auditoría seleccione la tabla, usuario o en un rango de fechas.";

$lang["auditoria_tabla"] = "Tabla del Sistema";
$lang["auditoria_fechas"] = "Fechas";

$lang["auditoria_tabla_corta"] = "Tabla";
$lang["auditoria_accion"] = "Acción";
$lang["auditoria_pk"] = "PK";
$lang["auditoria_usuario"] = "Usuario";
$lang["auditoria_fecha"] = "Fecha";
$lang["auditoria_columna"] = "Columna";
$lang["auditoria_valor_anterior"] = "Valor Anterior";
$lang["auditoria_valor_nuevo"] = "Valor Nuevo";
$lang["auditoria_Reporte"] = "Reporte de Auditoría";

$lang["AuditoriaAccesoTitulo"] = "Auditoría del Sistema - Acceso";
$lang["AuditoriaAccesoSubtitulo"] = "En este apartado podrá ver la auditoría de los accesos realizadas en el sistema, para visualizar la auditoría seleccione el filtro o en un rango de fechas.";

$lang["auditoria_usuario_detectado"] = "Usuario Detectado";
$lang["auditoria_tipo_acceso"] = "Acceso Detectado";
$lang["auditoria_tipo_ip"] = "IP";

// CONFIGRUACIÓN - CATÁLOGOS

$lang["CatalogoTitulo"] = "Catálogo del Sistema";
$lang["CatalogoSubtitulo"] = "En este apartado podrá gestionar el catálogo utilizado para el registro de información del sistema y en la APP.";

$lang["catalogo_tipo_codigo"] = "Categoría";
$lang["catalogo_codigo"] = "Código";
$lang["catalogo_parent"] = "Depende de (opcional)";
$lang["catalogo_descripcion"] = "Valor";

$lang["catalogo_estado"] = "Estado";

$lang["TablaOpciones_NuevoCatalogo"] = "Nuevo Registro";
$lang["conf_catalogo_Pregunta"] = "Actualizar el Catálogo del Sistema.";

// ESTRCUTRA AGENCIA, SUCURSAL, ROLES Y PERFILES

$lang["AgenciaTitulo"] = "Estructura de Oficinas";
$lang["AgenciaSubtitulo"] = "En este apartado podrá gestionar las Oficinas.";

$lang["RegionalTitulo"] = "Estructura de Agencias";
$lang["RegionalSubtitulo"] = "En este apartado podrá gestionar las Oficinas.";

$lang["estructura_agencia"] = "Oficina";
$lang["estructura_regional"] = "Agencia";
$lang["estructura_entidad"] = "Entidads Principal";

$lang["estructura_nombre"] = "Nombre";
$lang["estructura_parent"] = "Depende de";
$lang["estructura_detalle"] = "Descripción";

$lang["estructura_regional_geo"] = "Coordenadas GEO (DD)";
$lang["estructura_regional_firma"] = "(Opcional) Imagen de Firma del Responsable (fondo blanco)";
$lang["estructura_regional_estado"] = "Está abierta/disponible";
$lang["estructura_regional_responsable"] = "Info del Responsable";
$lang["estructura_regional_direccion"] = "Dirección";
$lang["estructura_regional_monto"] = "Límite Monto de Apertura";

$lang["estructura_regional_departamento"] = "Departamento";
$lang["estructura_regional_provincia"] = "Provincia";
$lang["estructura_regional_ciudad"] = "Ciudad";

$lang["estructura_Pregunta"] = "Registrar la información de la estructura.";

$lang["estructura_menu"] = "Seleccione el Menú/Módulo al que puede ingresar el Rol";

$lang["RolTitulo"] = "Roles y Asignación de Módulos";
$lang["RolSubtitulo"] = "En este apartado podrá gestionar los Roles del Sistema, referido a su nombre, descripción y el acceso a los módulos del sistema.";

// PERFILES

$lang["PerfilUsuarioTitulo"] = "Asignación de Perfiles a Usuarios";
$lang["PerfilUsuarioSubtitulo"] = "Puede entenderse el perfil como el conjunto de permisos específicos otorgados a usuarios determinados para el acceso a la información en los diferentes módulos del sistema como visualizar el detalle de los registros y otros. <br /> Para continuar, seleccione al usuario requerido de la tabla.";
$lang["PerfilUsuarioFormSubtitulo"] = "Seleccione los perfiles del listado y seleccione \"Aceptar\".";

$lang["PerfilTitulo"] = "Perfiles de Usuario";
$lang["PerfilSubtitulo"] = "En este apartado podrá gestionar los Perfiles del Sistema, referido a su nombre y descripción.";

// DOCUMENTO

$lang["DocumentoTitulo"] = "Gestión de Documentos (Formularios y/o Cartas)";
$lang["DocumentoSubtitulo"] = "En este apartado podrá gestionar los documentos utilizados en el Sistema. Establezca qué documentos puede enviarse a la Empresa Aceptante por correo electrónico.";

$lang["documento_nombre"] = "Nombre";
$lang["documento_enviar"] = "¿Se envía a la Empresa Aceptante?";
$lang["documento_pdf"] = "PDF del Documento";
$lang["documento_tiene_adjunto"] = " <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> Ya Existe un Documento";

$lang["documento_codigo"] = "API - Código Interno";
$lang["documento_mandatorio"] = "API - Mandatorio";

$lang["documento_Pregunta"] = "Actualizar la información del Documento";

// TIPO DE PERSONA

$lang["PersonaTitulo"] = "Gestión de Tipos de Registro";
$lang["PersonaSubtitulo"] = "En este apartado podrá gestionar los tipos de registro utilizados en el Sistema y su relación con los documentos que son requeridos para su Verificación.";

$lang["estructura_documento"] = "Seleccione el Documento requerido para el Tipo de Persona";

// SERVICIOS

$lang["ServicioTitulo"] = "Gestión de Tabs para App";
$lang["ServicioSubtitulo"] = "En este apartado podrá gestionar los Tabs para la App utilizados en el Sistema.";

// ACTIVIDADES

$lang["ActividadesTitulo"] = "Gestión de Productos de Interés";
$lang["ActividadesSubtitulo"] = "En este apartado podrá gestionar los Productos de Interés utilizados en el Sistema.";

$lang["prospecto_actividades"] = "Productos de Interés";

// TAREAS DE MANTENIMIENTO DE CARTERA

$lang["TareaTitulo"] = "Gestión de Tareas de Mantenimiento de Cartera";
$lang["TareaSubtitulo"] = "En este apartado podrá gestionar las tareas de mantenimiento de cartera que efectúan los Oficiales de Negocio, utilizados en el Sistema.";

// VERIFICADORES

$lang["EjecutivoTitulo"] = "Gestión de ";
$lang["EjecutivoSubtitulo"] = "En este apartado podrá gestionar los %s del Sistema que son los usuarios que utilizarán la APP. Como requisito indispensable para asignar a un usuario App, tiene que ser un usuario registrado y con el Rol correspondiente a \"%s\". <br /><br /> Podrá realizar la transferencia de cuentas y el seguimiento de Clientes/Casos.";

$lang["EjecutivoTitulo_nuevo"] = "Habilitar un Usuario como ";
$lang["EjecutivoSubtitulo_nuevo"] = "Asigne un Usuario con Perfil App tipo \"%s\" para que se le asigne Clientes/Casos.";

$lang["EjecutivoTitulo_editar"] = "Transferir Cuenta ";
$lang["EjecutivoSubtitulo_editar"] = "En este apartado podrá realizar el cambio y traspaso de las cuentas (Clientes/Casos) del Usuario App, a fin de asignarlo a otro usuario.";


$lang["ejecutivo_nombre"] = "Nombre del Usuario App";
$lang["ejecutivo_ejecutivo"] = "Oficial";
$lang["ejecutivo_zona"] = "Zona Asignada";

$lang["ejecutivo_Pregunta1"] = "Habilitar usuario seleccionado con perfil App \"%s\".";
$lang["ejecutivo_Pregunta2"] = "Transferir la cuenta (Clientes/Casos) al Usuario App seleccionado.";

$lang["TablaOpciones_habilitar_ejecutivo"] = "Habilitar Usuario <br /> App";

$lang["TablaOpciones_prospectos_transferir"] = "Transferir <br /> Cliente";
$lang["TablaOpciones_mantenimientos_transferir"] = "Transferir <br /> Mantenimiento";

$lang["EjecutivoTitulo_prospecto_editar"] = "Transferir Cliente - ";
$lang["EjecutivoSubtitulo_prospecto_editar"] = "En este apartado podrá realizar el cambio y traspaso del Cliente del Usuario App, a fin de asignarlo a otro usuario.";

$lang["ejecutivo_Pregunta4"] = "Transferir el Cliente indicado al usuario seleccionado.";
$lang["ejecutivo_Pregunta5"] = "Transferir el Mantenimiento indicado al usuario seleccionado.";

$lang["usuario_app_nombre"] = "Nombre del usuario con el Rol y Perfil App correspondiente";

$lang["TablaOpciones_transferir"] = "Transferir <br /> Cuenta";

$lang["TablaOpciones_horario"] = "Gestionar <br /> Horario";

$lang["TablaOpciones_ver_horario"] = "Ver <br /> Horario";

$lang["TablaOpciones_asignar_zona"] = "Asignar <br /> Zona";

$lang["TablaOpciones_gestion_lead"] = "Gestionar <br /> Cliente";

$lang["TablaOpciones_prospectos_asignados"] = "Clientes <br /> Asignados";
$lang["TablaOpciones_mantenimientos_asignados"] = "Mantenimientos <br /> Asignados";

$lang["TablaOpciones_prospectos_asignados_titulo"] = "Clientes Asignados al ";
$lang["TablaOpciones_prospectos_asignados_subtitulo"] = "En este apartado podrá visualizar los Clientes/Casos asignados y gestionar los mismos, considere que sólo se visualizan los Clientes/Casos que se encuentren en Bandeja y aún no hayan sido Consolidados. Si requiere gestionar registros ya Consolidados, debe solicitarlo a Supervisor/Administrador.";

$lang["TablaOpciones_mantenimientos_asignados_titulo"] = "Mantenimientos Asignados al Oficial de Negocio";

$lang["TablaOpciones_visitas_asignados"] = "Visitas <br /> Asignados";

$lang["ejecutivo_advertencia"] = "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Al transferir la cuenta, los leads y registros serán reasignados al Usuario App seleccionado.";

$lang["ejecutivo_sin_zona"] = "El Oficial de Negocio no tiene asignada una zona, ubique el marcador en la zona requerida.";

$lang["ejecutivo_ubicacion_actual"] = "<i class='fa fa-globe' aria-hidden='true'></i> Ver Ubicación Actual.";

$lang["EjecutivoTitulo_zona"] = "Asignar Zona al Usuario App";
$lang["EjecutivoSubitulo_zona"] = "Para Guardar la ubicación de la Zona del Usuario App, solamente ubique el marcador y se guardará automáticamente. Si no ve el marcador o requiere registrar la posición manualmente puede hacer 'doble-clic' sobre el mapa.";

$lang["ejecutivo_ejecutivo_zona"] = "Zona del ";

$lang["EjecutivoTitulo_Mapa"] = "Mapa de Zonas ";

$lang["TablaOpciones_mapa_ejecutivo"] = "Zonas <br />";

$lang["TablaOpciones_ejecutivo_indice"] = "Índice de <br /> Cumplimiento";

$lang["SeguimientoTitulo"] = "Tracking de ";
$lang["SeguimientoSubtitulo"] = "En este apartado podrá realizar el seguimiento de las ubicaciones de las visitas realizadas por los Oficiales de Negocio respecto al \"Check de la Visita\" efectuado en las Solicitudes y Estudios de Crédito.<br />En las opciones de formato de reporte podrá seleccionar entre listado (tabla) y mapa.<br />Para generar el reporte es mandatorio seleccionar los filtros de rango de fechas Check Visita y al menos una agencia u oficial de negocios.<br /><br />Tomar en consideración que, al generar el reporte en formato Mapa, dependiendo de la cantidad de marcadores (Check Visita) resultantes, el rendimiento de carga dependerá de los recursos de su equipo o dispositivo. Procure generar reportes con la mayor precisión posible seleccionando los filtros disponibles.";

$lang["ejecutivo_seguimiento_visita"] = "Tipo de Visita";
$lang["ejecutivo_seguimiento_fecha_visita"] = "Fecha Agendada";
$lang["ejecutivo_seguimiento_fecha"] = "Fecha Check Visita";
$lang["ejecutivo_seguimiento_prospecto"] = "Estudio de Crédito";
$lang["ejecutivo_seguimiento_mantenimiento"] = "Solicitud de Crédito";
$lang["ejecutivo_seguimiento_formato_reporte"] = "Formato del Reporte";

$lang["ejecutivo_seguimiento_fecha_error"] = "Por favor debe seleccionar un rango de fechas (Check Visita).";
$lang["ejecutivo_seguimiento_filtro_error"] = "Por favor debe seleccionar al menos una Agencia u Oficial de Negocios.";

$lang["ejecutivo_seguimiento_col_cliente"] = "Cliente";
$lang["ejecutivo_seguimiento_col_agencia"] = "Agencia Asociada";
$lang["ejecutivo_seguimiento_col_oficial"] = "Oficial Negocios";
$lang["ejecutivo_seguimiento_col_rubro"] = "Rubro";
$lang["ejecutivo_seguimiento_col_soliciante"] = "Solicitante";
$lang["ejecutivo_seguimiento_col_actividad"] = "Actividad";
$lang["ejecutivo_seguimiento_col_fecreg"] = "Fecha Registro";
$lang["ejecutivo_seguimiento_col_feccheck"] = "Fecha Check Visita";

$lang["seguimiento_Reporte_refresh_estado"] = "Actualizar y volver a cargar los filtros con el estado seleccionado, se limpiarán las opciones seleccionadas ¿Desea Continuar?";

$lang["seguimiento_Reporte"] = "Reporte de Seguimiento de Visitas ";

$lang["HorarioTitulo"] = "Horario de Visitas ";
$lang["HorarioSubtitulo"] = "En este apartado podrá gestionar los horarios de las visitas que <u> aún no realizaron el Check-In o aún no estén Consolidadas</u>.";

$lang["EjecutivoTitulo_metrica"] = "Índice de Cumplimiento";
$lang["EjecutivoSubtitulo_metrica"] = "<br /> En este apartado podrá establecer la Meta o Índice de Cumplimiento de Vísitas por Día de los Oficiales de Negocio. <br /><br /> Esta Información se verá reflejada en las estadísticas de la APP del Oficial de Negocio.";

$lang["ejecutivo_indice_Pregunta"] = "Actualizar el Índice de Cumplimiento, también se actualizará el parámetro de las estadísticas de la APP.";

$lang["BandejaEjecutivoTitulo"] = "Operaciones - Visitas Realizadas";
$lang["BandejaEjecutivoSubtitulo"] = "En este apartado podrá visualizar las visitas (Clientes o Mantenimientos) que le hayan sido asignadas.";


// Prospectos

$lang["prospecto_codigo"] = "Cliente";
$lang["prospecto_tipo_persona"] = "Tipo Persona";
$lang["prospecto_tipo_empresa"] = "Actividad / Detalle";
$lang["prospecto_nombre_empresa"] = "Nombre";
$lang["prospecto_fecha_asignaccion"] = "Fecha Asignación";
$lang["prospecto_fecha_consolidado"] = "Fecha Consolidado";
$lang["prospecto_estado_consolidado"] = "Estado Consolidado";

// EMPRESA

$lang["empresa_consolidada_detalle"] = "¿Consolidado?";
$lang["empresa_categoria_detalle"] = "Categoría";
$lang["empresa_nit"] = "NIT o Identificador";
$lang["empresa_adquiriente_detalle"] = "Adquiriente";
$lang["empresa_tipo_sociedad_detalle"] = "Tipo de Sociedad";
$lang["empresa_nombre_legal"] = "Nombre Legal";
$lang["empresa_nombre_fantasia"] = "Nombre Fantasía";
$lang["empresa_nombre_establecimiento"] = "Establecimiento";
$lang["empresa_denominacion_corta"] = "Denominación Corta";
$lang["empresa_rubro_detalle"] = "¿A qué te dedicas?";
$lang["empresa_perfil_comercial_detalle"] = "Perfil Comercial";
$lang["empresa_mcc_detalle"] = "MCC";
$lang["empresa_nombre_referencia"] = "Nombre Referencia";
$lang["empresa_ha_desde"] = "Atiende desde";
$lang["empresa_ha_hasta"] = "Atiende hasta";
$lang["empresa_dias_atencion"] = "Días Atención";
$lang["empresa_medio_contacto_detalle"] = "Medio Contacto";
$lang["empresa_dato_contacto"] = "Dato Contacto";
$lang["empresa_email"] = "Correo Electrónico";
$lang["empresa_departamento_detalle"] = "Departamento";
$lang["empresa_municipio_detalle"] = "Municipio/Ciudad";
$lang["empresa_zona_detalle"] = "Zona";
$lang["empresa_tipo_calle_detalle"] = "Tipo Calle";
$lang["empresa_calle"] = "Calle";
$lang["empresa_numero"] = "Número";
$lang["empresa_direccion_literal"] = "Dirección Literal";
$lang["empresa_info_adicional"] = "Info Adicional";
$lang["ejecutivo_asignado_nombre"] = "Oficial de Negocio Asignado";
$lang["ejecutivo_asignado_contacto"] = "Contacto Oficial de Negocio ";

$lang["EmpresaTitulo"] = "Registro de Comercio de el CORE y Asignación";
$lang["EmpresaSubtitulo"] = "En este apartado podrá registrar un Comercio que se encuentre en el CORE y asignarlo a un Oficial de Negocio, a fin de poder registrar Mantenimientos de Cartera.";

$lang["empresa_paso1"] = "Paso 1/2 - Identifique el Comercio registrado en el CORE a través del NIT";

$lang["empresa_paso2"] = "Paso 2/2 - Asigne la Empresa a un Oficial de Negocio";

$lang["empresa_parametro"] = "Campo Registrado";
$lang["empresa_valor"] = "Valor";

$lang["aprobar_empresa_Pregunta"] = "Registrar el Comercio de el CORE en el Sistema y asignarlo al Oficial de Negocio seleccionado.";

$lang["empresa_guardado"] = " <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> El Comercio de el CORE fue registrado correctamente en el sistema y asociado al Oficial de Negocio seleccionado.";

// SOLICITUD

$lang["solicitud_prospecto_guardado"] = " <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> La Solicitud de Verificación se registró correctamente, la Empresa Aceptante recibió un correo electrónico con el enlace de confirmación de la solicitud.";
$lang["solicitud_mantenimiento_guardado"] = " <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> La Solicitud de Mantenimiento se registró correctamente, la Empresa Aceptante recibió un correo electrónico con el enlace de confirmación de la solicitud.";

$lang["externo_prospecto_guardado"] = " <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> ¡Excelente! Tu solicitud se guardo correctamente. <br /> Te hemos enviado un correo electrónico para que puedas confirmar tu solicitud. Una vez que la hayas confirmado, podremos continuar con el registro. <br /><br /> Gracias...";
$lang["externo_mantenimiento_guardado"] = " <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> Excelente! Tu solicitud se guardo correctamente. <br /> Te hemos enviado un correo electrónico para que puedas confirmar tu solicitud. Una vez que la hayas confirmado, podremos continuar con el registro. <br /><br /> Gracias...";

$lang["externo_captcha"] = "Ingrese el código que aparecen en la imágen";

// -- Externo

$lang["externo_prospecto_titulo"] = " <i class='fa fa-id-badge' aria-hidden='true'></i> Contactar un Oficial de Negocio";
$lang["externo_prospecto_subtitulo"] = "Para poder registrarse, por favor complete el siguiente formulario y con gusto nos comunicaremos con usted a la brevedad posible.";

$lang["SolicitudTitulo_externomapa"] = " <i class='fa fa-home' aria-hidden='true'></i> Donde te encuentras";
$lang["SolicitudTitulo_externomapa_sub"] = "Mueva el marcador para guardar su posición, si no lo ve o requiere registrar la posición manulmente puede hacer 'doble-clic' sobre el mapa.";

$lang["externo_mantenimiento_titulo"] = "Contácta a tu Oficial de Negocio";
$lang["externo_mantenimiento_subtitulo"] = "Bienvenido, para contactar con tu Oficial de Negocio por favor complete los siguientes campos.";

// SOLICITUD DE AFILIACIÓN POR TERCEROS

$lang["AfiliacionTercerosTitulo"] = "Bandeja BackOffice Onboarding";
$lang["AfiliacionTercerosSubtitulo"] = "En este apartado podrá visualizar las solicitudes de Onboarding realizadas desde el formulario de registro. Puede visualizar las imagenes digitalizadas y proceder a Aprobar o Rechar la Solicitud.";

$lang["SupervisorTercerosTitulo"] = "Bandeja Onboarding Contrato";
$lang["SupervisorTercerosSubtitulo"] = "En este apartado podrá visualizar las solicitudes de Onboarding que ya fueron registrados y aprobados en COBIS, ya sea que fueron registrados operativamente o derivados por una excepción específica del " . $lang["flujo_registro_cobis"] . ". Puede proceder a marcar los registros como \"Completado\" para el envío al cliente.";

$lang["AgenciaTercerosTitulo"] = "Bandeja Onboarding Agencia";
$lang["AgenciaTercerosSubtitulo"] = "En este apartado podrá visualizar las solicitudes de Onboarding que ya fueron marcadas como Completadas y cargaron el PDF del Contrato del cliente. Una vez que se haya concluido la entrega de los elementos pertinentes al cliente puede proceder a marcar los registros como \"Entregado\".";
$lang["AgenciaTercerosReporteSubtitulo"] = "En este apartado podrá generar reportes que contienen información de todas las solicitudes atendidas, en los 3 estados vigentes de la presente bandeja. Los resultados se mostrarán regionalizados (por Agencia) pudiendo utilizar múltiples filtros de acuerdo a lo que requiera.";

$lang["AprobarPregunta"] = "Marcar el registro seleccionado como '" . $lang["aprobado"] . "' e iniciar el flujo de registro de apertura de cuenta en COBIS. Este proceso está sujeto a validaciones y lógica de reintentos formalmente establecidas, por lo que el resultado no necesariamente será inmediato";

// -- Req. Nuevos estados

// -- Notificar Cierre

$lang["AgenciaNotificarCierre"] = "<i class='fa fa-lightbulb-o' aria-hidden='true'></i> Notificar<br />Cierre";
$lang["TituloNotificarCierre"] = "Notificar Cierre de la Cuenta";
$lang["SubNotificarCierre"] = "Proceda a actualizar el registro para Notificar Cierre seleccionando la causa específica. El solicitante será notificado al correo electrónico registrado.";
$lang["causal_notificar_cierre"] = "Seleccione el causal para Notificar el Cierre de la Cuenta";
$lang["notificar_cierre_texto"] = "Notificar Cierre";

$lang["notificar_cierre_Pregunta"] = "Marcar el registro como Notificar Cierre con la causal indicada. <br /><br />El solicitante será notificado por correo y se contabilizarán los días para la notificación de alertas.";

// -- Cuenta Cerrada

$lang["cuenta_cerrada_texto"] = "Cuenta Cerrada";

$lang["AgenciaCuentaCerrada"] = "<i class='fa fa-lightbulb-o' aria-hidden='true'></i> Cuenta<br />Cerrada";
$lang["TituloCuentaCerrada"] = "Cerrar la Cuenta";
$lang["SubCuentaCerrada"] = "Proceda a Cerrar la Cuenta seleccionando la causa específica, e indique si se notificará por correo al cliente.";

$lang["cuenta_cerrada_Pregunta"] = "Marcar el registro como " . $lang["cuenta_cerrada_texto"] . " con la causal indicada. <br /><br /> Si fue seleccionado el envío, el solicitante será notificado por correo.";

$lang["causal_cuenta_cerrada"] = "Seleccione el causal para el Cierre de la Cuenta";

$lang["notificar_cuenta_cerrada"] = "¿Notificar al solicitante por Correo Electrónico?";

$lang["notificar_cierre"] = $lang["notificar_cierre_texto"];
$lang["notificar_cierre_fecha"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> " . $lang["notificar_cierre_texto"] . " - Fecha";
$lang["notificar_cierre_usuario"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> " . $lang["notificar_cierre_texto"] . " - Usuario";
$lang["notificar_cierre_causa"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> " . $lang["notificar_cierre_texto"] . " - Causal";
$lang["cuenta_cerrada"] = $lang["cuenta_cerrada_texto"];
$lang["cuenta_cerrada_fecha"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> " . $lang["cuenta_cerrada_texto"] . " - Fecha";
$lang["cuenta_cerrada_usuario"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> " . $lang["cuenta_cerrada_texto"] . " - Usuario";
$lang["cuenta_cerrada_causa"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> " . $lang["cuenta_cerrada_texto"] . " - Causal";
$lang["cuenta_cerrada_envia"] = "<i class='fa fa-angle-double-right' aria-hidden='true'></i> " . $lang["cuenta_cerrada_texto"] . " - Se notificó al cliente";

$lang["AfiliadorTitulo"] = "Empresa Afiliadora (Afiliación por Terceros)";
$lang["AfiliadorSubtitulo"] = "En este apartado podrá visualizar, editar y dar de alta las empresas encargadas de la Afiliación por Terceros. <br /><br /> Recuerde que para registrar prospectos con la empresa afiliadora, debe ser asociada con el Usuario App respectivo en el módulo \"Gestión de Afiliador Tercero\".";
$lang["AfiliadorSubtitulo2"] = "<br /> <br /> La opción \"Enviar Correo\" remitirá al correo electrónico registrado del afiliador la información necesaria para poder hacer uso del Landing Page Demo y la documentación del Web Service para implementar el registro de solicitudes de afiliación por terceros.";

$lang["AfiliadorTitulo_seleccionar"] = "Seleccione a la Empresa Afiliadora (Afiliación por Terceros)";
$lang["AfiliadorSubtitulo_seleccionar"] = "Puede asociar la Fuente de Afiliación o la Empresa Afiliadora con el Usuario App a fin de que al registrar un nuevo Prospecto se marque como fuente de afiliación la empresa seleccionada. <br /> Esta configuración también es requerida para habilitar al Usuario App la integración de los Web Services de Afiliación en el Landing Page o integración en sus propios sistemas.";

$lang["AfiliadorTitulo_enviar"] = "<i class='fa fa-envelope-o' aria-hidden='true'></i> Envíe la Información a la Empresa Afiliadora";
$lang["AfiliadorSubtitulo_enviar"] = "Se enviará un correo electrónico al correo registrado, en el correo se proporcionará un Link para que puedan generar y descargar los archivos necesarios para la integración. Revise la vista previa del correo, los archivos compomentes y si lo requiere puede indicar un texto adicional. Si requiere cambiar la plantilla puede hacerlo en el módulo respectivo.";

$lang["afiliador_nombre"] = "Nombre Afiliador";
$lang["afiliador_key"] = "Llave Asignada";
$lang["afiliador_responsable_nombre"] = "Nombre Responsable";
$lang["afiliador_responsable_email"] = "Correo";
$lang["afiliador_responsable_contacto"] = "Tel. Contacto";
$lang["afiliador_referencia_documento"] = "Ref. Documento";
$lang["afiliador_fecha_registro"] = "Fecha de Registro";

$lang["afiliador_texto_custom"] = "Texto Personalizado (máx 100 carac.)";

$lang["afiliador_fuente"] = "Empresa Afiliadora";

$lang["afiliador_envio_guardado"] = "<div class='PreguntaConfirmar'> <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> ¡El correo se envío correctamente! <br /><br /> Puede cerrar esta ventana. </div>";

$lang["TablaOpciones_fuente_afiliador"] = "Asociar Empresa <br /> Afiliadora";

$lang["AfiliadorSubtituloPlus"] = "<br /><br />Por Defecto los Afiliadores Terceros tendrán como fuente de afiliación a ATC, si requiere asociar la <u>Fuente de Afiliación</u> con una empresa Afiliadora registrada, puede utilizar la opción \"Asociar Empresa Afiliadora\".";


// -- Reporte de la bandeja Agencia

$lang["bandeja_agencia_atendidas"] = "Atendidas";
$lang["bandeja_agencia_pendientes"] = "Pendientes de Atención";

$lang["agencia_pregunta_reporte"] = "Al cambiar de bandeja se borrarán los filtros seleccionados ¿Desea Continuar?";

$lang["agencia_r_fecha_proceso"] = "Fecha de proceso";
$lang["agencia_r_fecha_actualizacion"] = "Fecha de actualización";
$lang["agencia_r_cuenta_cobis"] = "N° cuenta Cobis";
$lang["agencia_r_codigo"] = "Código Initium";
$lang["agencia_r_ci"] = "N° de CI de cliente";
$lang["agencia_r_solicitante"] = "Nombre Cliente";
$lang["agencia_r_regional"] = "Regional";
$lang["agencia_r_agencia"] = "Agencia";
$lang["agencia_r_usuario"] = "Usuario";
$lang["agencia_r_estado"] = "Estado del registro";
$lang["agencia_r_dias_notificacion"] = "Días de notificación";

// SOLICITUD DE VERIFICACION

$lang["AfiliacionTitulo"] = "Solicitudes de revisión de empresas ";
$lang["AfiliacionSubtitulo"] = "En este apartado podrá realizar el último filtro para la revisión documental e información requerida, suficiente y necesaria, para la  verificación de empresas.";

$lang["solicitud_nombre_persona"] = "Tu nombre Completo";
$lang["solicitud_nombre_empresa"] = "¿Cuál es tu empresa?";
$lang["solicitud_ciudad"] = "¿En que Departamento te encuentras?";
$lang["solicitud_telefono"] = "Tu Teléfono o Móvil";
$lang["solicitud_email"] = "Email de Contácto";
$lang["solicitud_direccion_literal"] = "¿Dónde te ubicamos?";
$lang["solicitud_direccion_geo"] = "Geolocalización";
$lang["solicitud_direccion_geo_des"] = "Quiero ver mi dirección en mapa";
$lang["solicitud_rubro"] = "¿A qué te dedicas?";
$lang["solicitud_fecha"] = "Fecha Solicitud";
$lang["solicitud_ip"] = "IP";
$lang["solicitud_estado"] = "Estado";
$lang["solicitud_servicios"] = "¿Qué Servicios Necesitas?";
$lang["solicitud_observacion"] = "Observación";

$lang["solicitud_estado_pendiente"] = "Pendientes";
$lang["solicitud_estado_aprobado"] = "Aprobados";
$lang["solicitud_estado_cancelado"] = "Rechazados";

$lang["RechazarTitulo"] = "Rechazar Solicitud";
$lang["CompletarTitulo"] = "Completar Solicitud";

$lang["EntregarTitulo"] = "Marcar Entregado a Cliente";

$lang["completar_Pregunta"] = "Completar el registro con los datos indicados y generar el PDF del contrato. Verifique que los datos y firma digitalizada sean correctos. <br /><br />Si seleccionó la notificación al solicitante, se enviará el Correo Electróncio respectivo incluido el texto personalizado registrado.";

$lang["entregado_Pregunta"] = "Marcar el registro como Entregado al Cliente. <br /><br />Confirma que realizó todas acciones respectivas y entregó los elementos pertinentes al cliente.";

$lang["cambiar_agencia_Pregunta"] = "Se procederá a Transferir el Registro a la Agencia seleccionada. <br /><br />Confirma que la acción a realizar obedece a un desición debidamente justificada.";


$lang["rechazar_Pregunta"] = "Rechazar la Solicitud seleccionada con la justificación indicada. <br /><br />Si seleccionó la notificación al solicitante, se enviará el Correo Electróncio respectivo incluido el texto personalizado registrado.";
$lang["rechazar_Pregunta2"] = "Rechazar la Solicitud de Mantenimiento";

$lang["NuevoSolicitudTitulo"] = "Registrar Solicitudes de Verificación";
$lang["EditarSolicitudTitulo"] = "Editar Solicitudes de Verificación";
$lang["EditarSolicitudSubtitulo"] = "Complete el formulario con la información recopilada con la Empresa Aceptante. Esta información será utilizada para la creación del Cliente.";

$lang["editar_solicitud_Pregunta"] = "Registrar la Solicitud de Verificación";
$lang["editar_solicitud_Pregunta2"] = "Registrar la Solicitud de Mantenimiento";

$lang["SolicitudTitulo_zona"] = " <i class='fa fa-flag-o' aria-hidden='true'></i> Geolocalización del Solicitante";
$lang["SolicitudSubitulo_zona"] = "Para Guardar la ubicación del Solicitante, solamente ubique el marcador y se guardará automáticamente. Si no ve el marcador o requiere registrar la posición manulmente puede hacer 'doble-clic' sobre el mapa.";

$lang["aprobar_solicitud_paso1"] = "Primero - Seleccione el tipo de registro ";
$lang["aprobar_solicitud_paso2"] = "Etapa de Selección del Oficial de Negocio ";
$lang["aprobar_solicitud_paso3"] = "Por último - Verifique el resumen del registro";

$lang["solicitud_tipo_persona"] = "Tipo de Persona";
$lang["solicitud_categoria_empresa"] = "Categoría Empresa";
$lang["categoria_empresa_comercio"] = "Titular";
$lang["categoria_empresa_sucrusal"] = "Beneficiario";
$lang["solicitud_verificar_nit"] = "¿Es correcto?";
$lang["solicitud_ver_mapa"] = "Visualizar Mapa";
$lang["solicitud_ver_calendario"] = "Visualizar Calendario";

$lang["SolicitudTitulo_aprobar"] = "¡Excelente! Vamos a continuar";
$lang["SolicitudSubitulo_aprobar"] = "Para aceptar la Solicitud, complete el formulario 
(todos los campos son obligatorios):";

$lang["Ayuda_solicitud_nit"] = "Verifique primeramente el NIT para asegurarse que el Cliente ser trata de un Comercio o un Establecimiento/Sucursal. La verificación será respecto a empresas afiliadas en el CORE o registradas en el Sistema.";
$lang["Ayuda_solicitud_catergoria"] = "¿Verificó el NIT? Si la empresa está afiliada correspondería a \"Nuevo ". $lang["categoria_empresa_sucrusal"] . "\"";
$lang["Ayuda_solicitud_tipo"] = "El Tipo de Persona definirá que tipo de documentación será solicitada";

$lang["DetalleNITTitulo"] = "Verificación del NIT";

$lang["nit_advertencia"] = "La Empresa se encuentra afiliada sólo en el CORE.";
$lang["no_nit_advertencia"] = "No se encontró la empresa afiliada en el CORE o el Sistema con el NIT indicado. <br /><br /> Correspondería a \"Nuevo ". $lang["categoria_empresa_comercio"] . "\"";
$lang["no_nit_encontrado"] = "No se encontró la empresa afiliada en el CORE o el Sistema con el NIT indicado";
$lang["verifique_nit"] = "Verifique el NIT.";

$lang["verifique_nit_mantenimiento"] = "<div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> No se encontró ninguna empresa con el NIT indicado, verifique el NIT. </div>";
$lang["verifique_nit_solo_paystudio"] = "<div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> La empresa se encuentra registrada sólo en el CORE, por favor solicite a la instancia correspondiente que registre esta empresa en el sistema.</div>";
$lang["verifique_nit_ya_registrado"] = "<div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> El comercio ya se encuentra registrado en el sistema, no es necesario volver a registrarlo. </div>";

$lang["verifique_nit_registrado"] = "El comercio ya se encuentra registrado en el sistema, verifique el NIT. </div>";

$lang["solicitud_mapa_ejecutivos"] = "Identifique y Seleccione al Oficial de Negocio más cercano al Cliente";
$lang["solicitud_asignar_fecha"] = "Asignar Fecha y Hora de la Visita";
$lang["solicitud_tiempo_visita"] = "Tiempo de Visita";

$lang["Ayuda_tiempo_visita"] = "Si requiere otros tiempos, puede solicitar el cambio de la fecha y hora de la Visita al administrador";
$lang["Ayuda_asignar_fecha"] = "Para asignar una fecha y hora disponible, puede visualizar el calendario del Oficial de Negocio seleccionado";

$lang["SolicitudTitulo_mapa"] = " <i class='fa fa-search' aria-hidden='true'></i> ¿Cuál Oficial de Negocio se encuentra más cerca?";

$lang["aprobar_solicitud_Pregunta"] = "Aprobar la Solicitud y Crear un Nuevo Cliente con el NIT indicado, el Oficial de Negocio seleccionado y la fecha y hora de la visita asignada";

$lang["aprobar_solicitud_guardado"] = "<br /><br /> El cliente recibió un correo de Notificación de la Visita y el Oficial de Negocio Asignado fue Notificado <br /><br /> ¿Qué requiere hacer ahora?";

$lang["ProspectoEnviarDocumentos"] = "Envíar Cartas y/o Formularios";

$lang["DetalleEnvioTitulo"] = " <i class='fa fa-envelope-o' aria-hidden='true'></i> Seleccione las cartas y/o formularios";
$lang["DetalleEnvioSubTitulo"] = "El listado de cartas y/o formularios se cargan de acuerdo al Tipo de Persona del Cliente (puede tardar unos segundos)";

$lang["EnvioGuardado"] = "Se enviaron las cartas y/o formularios seleccionados a la EA";

// SOLICITUD DE MANTENIMIENTO

$lang["NuevoMantenimientoTitulo"] = "Registrar Solicitudes de Mantenimiento";
$lang["MantenimientoTitulo"] = "Solicitudes de Mantenimiento";
$lang["MantenimientoSubtitulo"] = "En este apartado podrá visualizar las solicitudes de Verificacion de empresas";

$lang["mantenimiento_otro"] = "Detalle otro Mantenimiento";
$lang["mantenimiento_otro_elegir"] = "¿Requiere otro mantenimiento?";
$lang["mantenimiento_tareas"] = "Tareas Solicitadas";

$lang["aprobar_mantenimiento_paso1"] = "Paso 1/3 - Identifique el comercio o establecimiento que requiere el mantenimiento";
$lang["aprobar_mantenimiento_paso2"] = "Paso 2/3 - Asigne la fecha y hora de la Visita al Oficial de Negocio Seleccionado";
$lang["aprobar_mantenimiento_paso3"] = "Paso 3/3 - Para concluir, Verifique  que la Información de la Solicitud este Correcta";

$lang["Ayuda_mantenimiento_nit"] = "Con el NIT indicado por el cliente, seleccione la empresa que requiere el Mantenimiento.";

$lang["aprobar_mantenimiento_Pregunta"] = "Aprobar la Solicitud y Crear un Nuevo Mantenimiento con el NIT indicado, el Oficial de Negocio seleccionado y la fecha y hora de la visita asignada";

// PROSPECTO

$lang["DocumentoProspectoTitulo"] = " <i class='fa fa-camera' aria-hidden='true'></i> Listado Documentos y/o formularios ";
$lang["DocumentoProspectoTituloHistorial"] = " <i class='fa fa-history' aria-hidden='true'></i> Historial ";
$lang["documento_no_digitalizado"] = "No Digitalizado";
$lang["documento_si_digitalizado"] = "Visualizar Documento";
$lang["documento_si_digitalizado_historial"] = "Ver Historial";
$lang["documento_observar"] = "Observar";

$lang["documento_remitir_observación"] = "Remitir Observaciones";
$lang["documento_remitir_consulta"] = "¿Esta seguro de remitir las observaciones del(los) documento(s) al Usuario App?";


$lang["ObservarDocTitulo"] = "Se procederá a Observar el Item Seleccionado";
$lang["ObservarDocSubtitulo"] = "Para efectivizar la observación, indique la acción solicitada al Usuario App lo más claro posible. Recuerde que después de registrar el detalle debe Remitir las Observaciones.";

$lang["prospecto_justificar"] = "Detalle la Observación/Justificación";
$lang["ObsDoc_Pregunta"] = "Observar Documento. Al realizar esta acción se marcará el documento como observado y deberá ser devuelto al Usuario App a través de la opción ubicada en \"Revisar Documentación\" o \"Elementos Digitalizados\".";

$lang["prospecto_obs_doc_guardado"] = " Se remitió las observaciones del Cliente al %s.";
$lang["prospecto_obs_proc_guardado"] = " Se realizó la observación del Cliente indicado correctamente.";
$lang["prospecto_obs_doc_volver"] = "VOLVER A LA BANDEJA";


$lang["prospecto_id"] = "Código Asignado (interno)";
$lang["tipo_persona_detalle"] = "Tipo de Persona";
$lang["prospecto_misma_inf"] = "Misma Información";
$lang["prospecto_cambia_poder"] = "Cambio de Poder";
$lang["prospecto_reporte_bancario"] = "Cambio Reporte Bancario";
$lang["empresa_categoria"] = "Categoría Empresa";
$lang["prospecto_etapa_fecha"] = "Fecha Etapa";
$lang["empresa_nombre"] = "Nombre Empresa";
$lang["empresa_ejecutivo"] = "Oficial de Negocios";
$lang["prospecto_etapa_actual"] = "Etapa Actual";
$lang["prospecto_fecha_asignacion"] = "Fecha Asignación";
$lang["prospecto_etapa_nombre"] = "Nombre Etapa";
$lang["prospecto_excepcion"] = "Excepción";
$lang["prospecto_excepcion_acta"] = "Acta Excepción";
$lang["prospecto_rev"] = "Revisión Antecedentes";
$lang["prospecto_rev_fecha"] = "Fecha Revisión Antecedentes";
$lang["prospecto_rev_informe"] = "Resultado Revisión Antecedentes";
$lang["prospecto_rev_pep"] = "Antecedentes PEP";
$lang["prospecto_rev_match"] = "Antecedentes MATCH";
$lang["prospecto_rev_infocred"] = "Antecedentes INFOCRED";
$lang["prospecto_checkin"] = "Check Visita del Cliente";
$lang["prospecto_checkin_fecha"] = "Fecha Check Visita";
$lang["prospecto_checkin_geo"] = "Geolocalización Visita del Cliente";

$lang["prospecto_llamada"] = "Check Llamada del Cliente";
$lang["prospecto_llamada_fecha"] = "Fecha Check Llamada";
$lang["prospecto_llamada_geo"] = "Geolocalización Llamada del Cliente";

$lang["prospecto_consolidar_fecha"] = "Fecha Consolidado";
$lang["prospecto_consolidado"] = "Consolidado";
$lang["prospecto_consolidado_geo"] = "Geolocalización Consolidado";

$lang["prospecto_vobo_cumplimiento"] = "VoBo Cumplimiento";
$lang["prospecto_vobo_cumplimiento_fecha"] = "Fecha VoBo Cumplimiento";
$lang["prospecto_vobo_legal"] = "VoBo Legal";
$lang["prospecto_vobo_legal_fecha"] = "Fecha VoBo Legal";
$lang["prospecto_estado_actual"] = "Estado Actual";
$lang["prospecto_rechazado"] = "Rechazado";
$lang["prospecto_rechazado_fecha"] = "Rechazo Fecha Notificación";
$lang["prospecto_rechazado_detalle"] = "Rechazo Detalle";
$lang["prospecto_aceptado_afiliado"] = "Afiliado en Nazir";
$lang["prospecto_aceptado_afiliado_fecha"] = "Fecha Verificación en Nazir";
$lang["prospecto_entrega_servicio"] = "Servicio Entregado";
$lang["prospecto_entrega_servicio_fecha"] = "Fecha Servicio Entregado";
$lang["cal_visita_ini"] = "Visita Agendada Empezó";
$lang["cal_visita_fin"] = "Visita Agendada Terminó";

$lang["prospecto_no_evaluacion"] = "No Registrado";
$lang["prospecto_evaluacion"] = "Evaluación Legal para EA";
$lang["prospecto_excepcion_no_acta"] = "No se adjuntó ningún documento";

$lang["prospecto_estado"] = "Flujo";

$lang["DetalleProspectoTitulo"] = " <i class='fa fa-search' aria-hidden='true'></i> Detalle del Cliente";

$lang["DetalleHistorialInforme"] = " <i class='fa fa-history' aria-hidden='true'></i> Historial de Versiones Informe Consolidado";

$lang["DetalleHistorialObservacion"] = " <i class='fa fa-comments-o' aria-hidden='true'></i> Historial Observaciones";
$lang["DetalleComentariosExcepcion"] = " <i class='fa fa-comments-o' aria-hidden='true'></i> Notas Excepción";
$lang["DetalleHistorialSeguimiento"] = " <i class='fa fa-road' aria-hidden='true'></i> Seguimiento del Cliente";

$lang["observacion_fecha"] = "Fecha";
$lang["observacion_fecha_digitalizacion"] = "Fecha Digitalización";
$lang["observacion_usuario_deriva"] = "Derivado Por";
$lang["observacion_usuario_realizado"] = "Realizado Por";
$lang["observacion_usuario_accion"] = "Acción Realizada";
$lang["observacion_tipo"] = "Tipo";
$lang["observacion_documento"] = "Documento";
$lang["observacion_etapa"] = "Etapa";
$lang["observacion_derivado_etapa"] = "Derivado a Etapa";
$lang["observacion_detalle"] = "Detalle";
$lang["observacion_excepcion_detalle"] = "Comentario/Justificación";
$lang["observacion_estado"] = "Estado";

$lang["ObsProcTitulo"] = "Observar y Devolver Cliente";
$lang["ObsProcSubtitulo"] = "Procederá a observar y devolver el Cliente a la instancia anterior. Para ello debe registrar un comentario o justificación. <br /> Al devolver un Cliente, <u> el tiempo asignado a su etapa seguirá corriendo. </u>";

// MANTENIMIENTO

$lang["DetalleMantenimientoTitulo"] = " <i class='fa fa-commenting-o' aria-hidden='true'></i> Detalle del Mantenimiento";

$lang["mant_id"] = "Código Asignado (interno)";
$lang["mant_fecha_asignacion"] = "Fecha Asignación";
$lang["mant_estado"] = "Estado del Mantenimiento";
$lang["mant_checkin"] = "Check-In con la EA";
$lang["mant_checkin_fecha"] = "Fecha del Check-In";
$lang["mant_completado_fecha"] = "Fecha Completado";
$lang["mant_documento_adjunto"] = "Existe Documento Adjunto";
$lang["mant_documento_adjunto_detalle"] = "Ver Documento";
$lang["mant_tareas_realizadas"] = "Tareas Realizadas";

// BANDEJA SUPERVISOR DE AGENCIA

$lang["SupervisorAgenciaTitulo"] = "Operaciones - Solicitudes de Pre-Verificación";
$lang["SupervisorAgenciaSubtitulo"] = "En este apartado podrá efectuar acciones sobre lo siguiente: <br /><br />- Solicitudes de pre- Verificación derivados por el Oficial de Negocio.<br />- Observar Documentación.<br />- Autorizar Documentación.";

$lang["TablaOpciones_revisar_documentacion"] = "<i class='fa fa-lightbulb-o' aria-hidden='true'></i> Revisar <br /> Documentación";
$lang["TablaOpciones_observar_devolver"] = "<i class='fa fa-lightbulb-o' aria-hidden='true'></i> Observar y <br /> Devolver";
$lang["TablaOpciones_autorizar_documentacion"] = "Autorizar<br /> Documentación";

// BANDEJA CUMPLIMIENTO

$lang["TablaOpciones_mostrar_resumen"] = "<i class='fa fa-eye' aria-hidden='true'></i> Mostrar/Ocultar Resumen de Mi Bandeja";

$lang["TablaOpciones_revisar_antecedentes"] = "Antecedentes <br /> y Remitir";

$lang["AntecedentesTitulo"] = "Revisión de Antecedentes y Remitir Cliente";
$lang["AntecedentesSubtitulo"] = "En este apartado podrá registrar el resultado de la Evaluación de Antecedentes y remitir el Cliente a la instancia siguiente.";

$lang["antecedentes_pep"] = "Cuenta con antecedentes PEP";
$lang["antecedentes_match"] = "Cuenta con antecedentes MATCH";
$lang["antecedentes_infocred"] = "Cuenta con antecedentes INFOCRED";

$lang["antecedentes_resultado"] = "Recomendación de la Revisión";

$lang["Ayuda_antecedentes_recomendacion"] = "Al aprobar el Cliente, ingresará al flujo de Verificación, caso contrario se derivará a la instancia respectiva para notificar a la EA";

$lang["antecedentes_detalle"] = "Registre el resultado de la Revisión";

$lang["antecedentes_Pregunta"] = "Completar la Revisión de Antecedentes y remitir el Cliente a la siguiente instancia. ¿Son correctos los resultados para PEP, MATCH e INFOCRED?";

// BANDEJA VERIFICACIÓN DE REQUISITOS

$lang["VerificacionTitulo"] = "Supervisión de Clientes Consolidados";
$lang["VerificacionSubtitulo"] = "En este apartado podrá efectuar la revisión de los Clientes que hayan sido Consolidados por el Oficial de Negocio, así mismo, puede observar el registro y/o documentos digitalizados para devolver el Cliente al Oficial de Negocio. <br /><br /> Puede Ver el detalle de los registros que tengan enlaces habilitados haciendo clic sobre los mismos.";

$lang["TablaOpciones_revisar_requisitos"] = "Requisitos <br /> y Remitir";

$lang["TablaOpciones_solicitar_excepcion"] = "Solicitar <br /> Excepción";

$lang["RequisitosTitulo"] = "Verificación de Requisitos y Remitir Cliente";
$lang["RequisitosSubtitulo"] = "En este apartado podrá registrar el resultado de la Verificación de Requisitos a fin de establecer si el Establecimiento/Sucursal cuenta con la misma información del Comercio o requiere otra información, y remitir el Cliente a la instancia siguiente para continuar con la Verificación.";

$lang["requisitos_titulo_opcion"] = "Opción";
$lang["requisitos_titulo_requisito"] = "Requisito";

$lang["requisitos_misma_info"] = "¿Cuenta con todos los requisitos solicitado?";
$lang["requisitos_misma_info_des"] = "Si el cliente cuenta con todos los requisitos que le fueron solicitados, puede continuar con la Verificación";

$lang["requisitos_cambio_poder"] = "Otras opciones";
$lang["requisitos_cambio_poder_des"] = "Puede registrar otras opciones de acuerdo al flujo";

$lang["requisitos_cambio_bancario"] = "¿Reporte Bancario con fotocopia de C.I.?";
$lang["requisitos_cambio_bancario_des"] = "Puede observar los documentos requeridos, solicitando que el Oficial de Negocio digitalice el mismo para continuar con el proceso. Se derivará el Cliente a Cumplimiento.";

$lang["requisitos_Pregunta"] = "Completar la Verificación de Requisitos de la Empresa Aceptante con la opción seleccionada. ¿Todos los documentos e información son suficientes y necesarios?";

$lang["GenerarExcTitulo"] = "Está a punto de Generar una Excepción para el Cliente";
$lang["GenerarExcSubtitulo"] = "Procederá a Generar una Excepción para el Cliente, para ello, registre la observación/justificación.";

$lang["excepcion_advertencia"] = "<i class='fa fa-envelope' aria-hidden='true'></i> Se remitirá también un Correo Electrónico a la instancia correspondiente para solicitar la Excepción";
$lang["excepcion_detalle"] = "Registre la Observación/Justificación (máx 300 carac.)";

$lang["excepcion_Pregunta"] = "Solicitar la Generación de Excepción del Cliente indicado";

$lang["TablaOpciones_ver_historial"] = "<i class='fa fa-lightbulb-o' aria-hidden='true'></i> Historial <br /> Observaciones";
$lang["TablaOpciones_ver_historial_excepcion"] = "<i class='fa fa-lightbulb-o' aria-hidden='true'></i> Notas <br /> Excepción";

$lang["TablaOpciones_ver_informe"] = "<i class='fa fa-lightbulb-o' aria-hidden='true'></i> Informe <br /> Consolidado";

$lang["TablaOpciones_observar_devolver"] = "<i class='fa fa-lightbulb-o' aria-hidden='true'></i> Observar y <br /> Devolver";

$lang["observar_devolver_Pregunta"] = "Observar y Devolver el Cliente a la instancia anterior. Considere que el tiempo de su etapa seguirá corriendo, deberá hacer seguimiento del Cliente.";

// BANDEJA CUMPLIMIENTO

$lang["CumplimientoTitulo"] = "Revisión Cumplimiento";
$lang["CumplimientoSubtitulo"] = "En este apartado podrá realizar la revisión de los leads respectiva de esta instancia y brindar su Visto Bueno.";

$lang["TablaOpciones_revisar_cumplimiento"] = "Revisar <br /> y Remitir";

$lang["TablaOpciones_cumplimiento_f1"] = "Formulario <br /> Sociedad";

$lang["TablaOpciones_cumplimiento_f2"] = "Formulario <br /> Match";

$lang["cumplimiento_Pregunta"] = "Completar la Revisión de la Empresa Aceptante con la Opción Seleccionada. <br /><br /> ¿Procedió con la revisión pertinente y suficiente de su instancia que establece la recomendación seleccionada?";

$lang["cumplimiento_opcion_vobo"] = "Marcar Visto Bueno (Vo.Bo.)";
$lang["cumplimiento_opcion_vobo_des"] = "Se marcará la revisión de Cumplimiento con el Visto Bueno de la instancia y se derivará a la instancia respectiva del flujo.";

$lang["cumplimiento_opcion_rechazar"] = "Recomendar Rechazar el Cliente";
$lang["cumplimiento_opcion_rechazar_des"] = "De acuerdo a la revisión realizada, se rechazará el Cliente y se derivará a la instancia respectiva del flujo.";

// -- Formulario Sociedad

$lang["CumplimientoSociedadTitulo"] = "Formulario de Información Confidencial de la Persona Jurídica";
$lang["CumplimientoSociedadSubtitulo"] = "En este apartado podrá registrar la información del formulario, por favor complete todos los campos para continuar. <br /><br /> La primera vez que ingrese al formulario, los campos se autocompletarán con la información registrada en el sistema.";

$lang["form_sociedad_sector1"] = "DATOS BASICOS CONOCIMIENTO DEL CLIENTE";
$lang["form_sociedad_sector2"] = "CONSULTAS ADICIONALES";
$lang["form_sociedad_sector3"] = "FIRMAS FUNCIONARIOS Vo.Bo.";

$lang["form_razon_social"] = "Razón Social / Nombre Comercial";
$lang["form_nit"] = "NIT o Identificador";
$lang["form_matricula_comercio"] = "Matrícula Comercio";
$lang["form_direccion"] = "Dirección";

$lang["form_mcc"] = "Giro - Actividad económica";
$lang["form_rubro"] = "Actividad Específica";

$lang["form_criterios_aceptacion"] = "Criterios de aceptación";

$lang["form_flujo_estimado"] = "Flujo estimado de ventas (US$/mes)";
$lang["form_cuenta_bob"] = "Cuenta Bancaria Bs./Banco";
$lang["form_cuenta_usd"] = "Cuenta Bancaria US$/Banco";
$lang["form_titular_cuenta"] = "Nombre de Titular de Cta.";
$lang["form_ci"] = "C.I.";
$lang["form_representante_legal"] = "Representante Legal";

$lang["form_lista_accionistas"] = "Listado Accionistas/Propietarios";

$lang["form_requisitos_afiliacion"] = "REQUISITOS DE VERIFICACION ";

$lang["form_consultas_titulo1"] = "Establecimiento";
$lang["form_consultas_titulo2"] = "Representante Legal";

$lang["form_infocred_cuenta_endeuda"] = "Cuenta con endeudamiento en el SFN";
$lang["form_infocred_calificacion_riesgos"] = "Calificación de Riesgo asignada";
$lang["form_pep_categorizado"] = "Se encuentra categorizado como PEP";
$lang["form_pep_codigo"] = "Código PEP";
$lang["form_pep_cargo"] = "Cargo Desempeñado (más elevado)";
$lang["form_pep_institucion"] = "Institución en la que se desempeñó";
$lang["form_pep_gestion"] = "Gestión en la que se desempeñó";
$lang["form_lista_confidenciales"] = "Se encuentra registrado en Lista OFAC ";
$lang["form_match_observado"] = "Se encuentra observado en el Match";
$lang["form_lista_negativa"] = "Se encuentra registrado en Listas";
$lang["form_comentarios"] = "COMENTARIOS RELACIONADOS CON SU APRECIACIÓN";
$lang["form_firma_entrega1_nombre"] = "Nombre Elabora";
$lang["form_firma_entrega1_cargo"] = "Cargo Elabora";
$lang["form_firma_entrega1_fecha"] = "Fecha Elabora";
$lang["form_firma_entrega2_nombre"] = "Nombre Vo.Bo.";
$lang["form_firma_entrega2_cargo"] = "Cargo Vo.Bo.";
$lang["form_firma_entrega2_fecha"] = "Fecha Vo.Bo.";
$lang["form_firma_comercial_nombre"] = "Nombre Recepciona";
$lang["form_firma_comercial_cargo"] = "Cargo Recepciona";
$lang["form_firma_comercial_fecha"] = "Fecha Recepciona";

$lang["form_accionista_nombre"] = "Propietario / Principales Accionistas / Directorio";
$lang["form_accionista_documento"] = "Nro. Documento";
$lang["form_accionista_participacion"] = "% Participación";

$lang["cumplimiento_form1_Pregunta"] = "Completar la Revisión de la Empresa Aceptante con la Información Registrada. <br /><br /> ¿Procedió con la revisión pertinente y suficiente de su instancia que establece los criterios registrados?";

// -- Formulario Match

$lang["CumplimientoMatchTitulo"] = "Formulario Diario de Consultas MATCH";
$lang["CumplimientoMatchSubtitulo"] = "En este apartado podrá registrar la información del formulario, por favor complete todos los campos para continuar. <br /><br /> La primera vez que ingrese al formulario, los campos se autocompletarán con la información registrada en el sistema, para los Comercios se buscará primeramente la información registrada en el Formulario Sociedad.";

$lang["form_observacion"] = "Observaciones";

// BANDEJA LEGAL

$lang["LegalTitulo"] = "Revisión Legal Cliente";
$lang["LegalSubtitulo"] = "En este apartado podrá realizar la revisión de los leads respectiva de esta instancia y brindar su Visto Bueno y para el caso de los Comercios generar la Evaluación Legal para Empresas Aceptantes.";

$lang["legal_Pregunta"] = "Completar la Revisión de la Empresa Aceptante con la Opción Seleccionada. <br /><br /> ¿Procedió con la revisión pertinente y suficiente de su instancia que establece la recomendación seleccionada de acuerdo a la categoría de la empresa?";

$lang["legal_opcion_vobo"] = "Marcar Visto Bueno (Vo.Bo.)";
$lang["legal_opcion_vobo_des"] = "De acuerdo al resultado del análisis legal, se marcará la revisión de Cumplimiento con el Visto Bueno de la instancia y se derivará a la instancia respectiva del flujo.";

$lang["legal_opcion_rechazar"] = "Recomendar Rechazar el Cliente";
$lang["legal_opcion_rechazar_des"] = "De acuerdo al resultado del análisis legal se podrá rechazar el Cliente y se derivará a la instancia respectiva del flujo.";

$lang["legal_evaluacion_opcion"] = "Reporte Adjunto";
$lang["legal_evaluacion"] = "Evaluación Legal para Empresas Aceptantes";
$lang["legal_generar_reporte"] = "<i class='fa fa-file-pdf-o' aria-hidden='true'></i> Ver Reporte";
$lang["legal_editar_reporte"] = "<i class='fa fa-pencil-square-o' aria-hidden='true'></i> Editar Reporte";

$lang["legal_advertencia"] = "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Debe Registrar la " . $lang["legal_evaluacion"] . " antes de continuar.";

$lang["TablaOpciones_evaluacion_legal"] = "Evaluación <br /> Legal";

$lang["EvalLegalTitulo"] = "Formulario de Evaluación Legal para Empresas Aceptantes";
$lang["EvalLegalSubtitulo"] = "En este apartado podrá registrar la información de la Evaluación Legal. Es necesario que complete correctamente este formulario antes de Remitir el Cliente.";

// -- Formulario Evaluación

$lang["evaluacion_denominacion_comercial"] = "Denominación Comercial";
$lang["evaluacion_razon_social"] = "Razón Social";

$lang["evaluacion_revision_documental"] = "Evaluación Documental";

$lang["evaluacion_razon_idem"] = "Razon Social idem a datos generales";

$lang["evaluacion_fotocopia_nit"] = "Fotocopia de NIT";

$lang["evaluacion_nit"] = "Número NIT";
$lang["evaluacion_representante_legal"] = "Nombre del Representante Legal";

$lang["evaluacion_fotocopia_certificado"] = "Fotocopia de Certificado de Inscripción";
$lang["evaluacion_actividad_principal"] = "Actividad Principal";
$lang["evaluacion_actividad_secundaria"] = "Actividad Secundaria";

$lang["evaluacion_fotocopia_ci"] = "Fotocopia de Cédula de Identidad";
$lang["evaluacion_ci_pertenece"] = "Cédula de Identidad pertenece a";
$lang["evaluacion_ci_vigente"] = "Vigente";
$lang["evaluacion_ci_fecnac"] = "Fecha de nacimiento";
$lang["evaluacion_ci_titular"] = "Nombre del Titular de la CI";

$lang["evaluacion_fotocopia_testimonio"] = "Fotocopia de Testimonio de Constitución";
$lang["evaluacion_numero_testimonio"] = "Número de Testimonio";
$lang["evaluacion_duracion_empresa"] = "Duración de la Empresa";
$lang["evaluacion_fecha_testimonio"] = "Fecha del testimonio";
$lang["evaluacion_objeto_constitucion"] = "Objeto de la constitución";

$lang["evaluacion_fotocopia_poder"] = "Fotocopia de Poder de Representante Legal";
$lang["evaluacion_fecha_testimonio_poder"] = "Fecha del Testimonio";
$lang["evaluacion_numero_testimonio_poder"] = "Número de Testimonio";
$lang["evaluacion_firma_conjunta"] = "Firma Conjunta";
$lang["evaluacion_facultad_representacion"] = "Presenta Facultades de Representacion";

$lang["evaluacion_fotocopia_fundaempresa"] = "Fotocopia de Registro FUNDEMPRESA";
$lang["evaluacion_fundaempresa_emision"] = "Fecha de emision";
$lang["evaluacion_fundaempresa_vigencia"] = "Fecha de vigencia";
$lang["evaluacion_idem_escritura"] = "Objeto idem a escritura de constitucion";
$lang["evaluacion_idem_poder"] = "Representante Legal Idem a poder";
$lang["evaluacion_idem_general"] = "Denominacion Comercial idem a datos generales";

$lang["evaluacion_resultado"] = "RESULTADO DE ANALISIS LEGAL";


$lang["evaluacion_titulo_opcion"] = "CONCLUSIONES";
$lang["opcion_conclusion"] = "CONCLUSIONES";

$lang["evaluacion_conclusion1"] = "PROCEDER CON LA AFILIACION";
$lang["evaluacion_conclusion2"] = "PROCEDER CON LA AFILIACION BAJO RESPONSABILIDAD DEL AREA SOLICITANTE";
$lang["evaluacion_conclusion3"] = "NO PROCEDER CON LA AFILIACION";

$lang["evaluacion_pertenece_regional"] = "PERTENECIENTE A LA REGIÓN DE";

$lang["evaluacion_fecha_solicitud"] = "FECHA DE SOLICITUD";
$lang["evaluacion_fecha_evaluacion"] = "FECHA DE EVALUACIÓN";

$lang["evaluacion_regional_ayuda"] = "Los datos mostrados son los registrados en el catálogo del sistema";

$lang["evaluacion_Pregunta"] = "¿Completó correctamente los datos generales, la evaluación documental y el análisis legal respectivo?";


// BANDEJA APROBACIÓN PAYSTUDIO

$lang["AprobacionTitulo"] = "Verificación de empresas visitadas";
$lang["AprobacionSubtitulo"] = " En este apartado podrá visualizar las solicitudes de verificación de empresas.";

$lang["aprobacion_Pregunta"] = "Está a punto de aprobar el Cliente indicado y se procederá con la Inserción de la Información al Core del Sistema. <br /><br /> ¡ESTA ACCIÓN NO SE PUEDE DESHACER!";

$lang["TablaOpciones_aprobar"] = "APROBAR PARA <br /> PAYSTUDIO";

$lang["AprobacionFormTitulo"] = "¡Está a punto de Aprobar el Cliente!";
$lang["AprobacionFormSubtitulo"] = "En este apartado podrá realizar el último filtro para la revisión documental e información requerida, suficiente y necesaria, para la inserción en el CORE (Nazir). Por favor complete los siguientes pasos:";

$lang["aprobar_paso1"] = "Paso 1/3 - Revise y Verifique la Información del Cliente";
$lang["aprobar_paso2"] = "Paso 2/3 - Revise y Verifique la Información que será insertada en el CORE";
$lang["aprobar_paso3"] = "Paso 3/3 - Marque los Requisitos de Verificación";

$lang["aprobacion_1_des"] = "¿Toda la información del Cliente así como su documentación es correcta, suficiente y se encuentra verificada por las instancias correspondientes?";
$lang["aprobacion_2_des"] = "Confirmar que la información requerida del Cliente se aprobará";

$lang["aprobacion_advertencia"] = "<i class='fa fa-lock' aria-hidden='true'></i> No puede continuar hasta que complete la información requerida por el CORE respecto a: <br />";

$lang["error_WS_el CORE"] = "<i class='fa fa-plug' aria-hidden='true'></i> Pasó algo con el Web Service de Inserción (el CORE). No se completó el proceso, por favor comuníquese con el Administrador del Sistema.";

$lang["prospecto_aprobado_guardado"] = "¡Hooray! El Cliente fue correctamente Aprobado";

// BANDEJA EXCEPCIÓN - JUSTIFICAR

$lang["JustificaTitulo"] = "Excepciones (Justificar e Informar)";
$lang["JustificaSubtitulo"] = "En este apartado podrá realizar la revisión de los leads respectiva de esta instancia y determinar la acción correspondiente.";

$lang["excepcion_generada_Pregunta"] = "Completar la Revisión de la Empresa Aceptante con la Opción Seleccionada. <br /><br />";

$lang["justificar_opcion_vobo"] = "Continuar con la Excepción - Justificar e Informar";
$lang["justificar_opcion_vobo_des"] = "De acuerdo al resultado del análisis realizado, se derivará a la instancia correspondiente para continuar con la Excepción.";

$lang["excepcion_opcion_rechazar"] = "Recomendar Rechazar el Cliente";
$lang["excepcion_opcion_rechazar_des"] = "De acuerdo al resultado del análisis efectuado se podrá rechazar el Cliente y se derivará a la instancia respectiva del flujo.";

$lang["acta_excepcion_pdf"] = "Adjuntar el acta de reunión (PDF)";
$lang["acta_excepcion_pdf_ok"] = " <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> Ok, listo para subir";

// BANDEJA EXCEPCIÓN - GESTIONAR

$lang["GestionTitulo"] = "Excepciones (Gestionar Excepción)";
$lang["GestionSubtitulo"] = "En este apartado podrá realizar la revisión de los leads respectiva de esta instancia y determinar la acción correspondiente.";

$lang["gestion_opcion_vobo"] = "Continuar con la Excepción - Gestionar Excepción";
$lang["gestion_opcion_vobo_des"] = "De acuerdo al resultado del análisis realizado, se derivará a la instancia correspondiente para continuar con la Excepción.";

// BANDEJA EXCEPCIÓN - ANALIZAR

$lang["AnalisisTitulo"] = "Excepciones (Analizar Excepción)";
$lang["AnalisisSubtitulo"] = "En este apartado podrá realizar la revisión de los leads respectiva de esta instancia y determinar la acción correspondiente.";

$lang["analisis_opcion_vobo"] = "Autorizar Excepción";
$lang["analisis_opcion_vobo_des"] = "De acuerdo al resultado del análisis realizado, se deberá justificar brevemente la aprobación y adjuntar el acta de reunión para que posteriormente se derive a la instancia correspondiente para continuar con el flujo correspondiente.";

$lang["analisis_opcion_rechazar"] = "Instruir Cancelar Verificación";

// BANDEJA RECHAZO

$lang["RechazoTitulo"] = "Notificar Rechazo";
$lang["RechazoSubtitulo"] = "En este apartado podrá realizar la Notificación de Rechazo de los leads que hayan sido rechazados.";

$lang["RechazoFormTitulo"] = "¡Confirmar el Rechazo del Cliente!";
$lang["RechazoFormSubtitulo"] = "En este apartado podrá realizar la Notificación de Rechazo, registre el detalle de la razón del rechazo y proceda a notificar a la Empresa Aceptante de manera cordial, clara y oportuna a la Empresa Aceptante.";

$lang["TablaOpciones_notificar_rechazar"] = "Notificar <br /> Rechazo";

$lang["rechazo_advertencia"] = "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Recuerde Notificar el rechazo a la Empresa Aceptante. Esta acción no se puede deshacer.";

$lang["excepcion_generada_Pregunta"] = "Rechazar la Verificación del Cliente con la observación/justificación registrada.";

// FLUJO DE TRABAJO

$lang["FlujoTitulo"] = "Etapas del BPM y Tiempos";
$lang["FlujoSubtitulo"] = "En este apartado puede visualizar las etapas del flujo seleccionado y administrar los <u>tiempos</u> de cada una de ellas y las opciones de notificación.";

$lang["etapa_seleccion"] = "Seleccione el Flujo";

$lang["etapa_nombre"] = "Nombre Corto";
$lang["etapa_detalle"] = "Descripción";
$lang["etapa_parent"] = "Consecutivo de";
$lang["etapa_tiempo"] = "Tiempo máximo de la etapa (en horas)";
$lang["etapa_notificar_correo"] = "¿Notificar Instancia por Correo?";

$lang["Ayuda_etapa_rol"] = "Los usuarios con el Rol establecido serán los actores directos de esta etapa";
$lang["Ayuda_etapa_tiempo"] = "Se respetará los días laborales configurados en el sistema y los feriados";
$lang["Ayuda_etapa_envio"] = "Puede configurar si esta etapa recibirá la notificación por correo electrónico cuando le deriven un Cliente";

$lang["estructura_Pregunta"] = "Actualizar la información de la Estructura";

$lang["flujo_advertencia"] = "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Considere que la modificación de la etapa del flujo podría ocasionar incongruencia de datos en los leads en proceso.";

$lang["etapa_alerta_correo"] = "¿Enviar Alerta de Registros Atrasados por Correo?";
$lang["etapa_alerta_ayuda"] = "<strong><i class='fa fa-lightbulb-o' aria-hidden='true'></i> Seleccione la <u>HORA</u> y los <u>DÍAS</u> en los que requiere enviar las alertas de registros Atrasados para ésta etapa. Se enviará a los Roles y/o Usuarios establecidos, manteniendo la regionalización (segmentación de agencias).<br /><br /><i><ul style=\"padding: 0px 20px;\"><li>Los correos se enviarán en la hora específica de los días seleccionados. </li><li>Puede existir tiempo de retraso en el envío del correo electrónico (minutos), esto puede deberse al tiempo de procesamiento y elaboración del reporte, o aspectos referidos al servidor de correo electrónico.</li><li>El cálculo de la cantidad de registros y las horas mostradas en el correo de alerta, así como el estado establecido (Atrasado) se obtiene desde la fecha de derivación (o arribo) a la etapa y la fecha de envío del correo electrónico. </li><li>Sólo se enviará las alertas cuando existan Registros Atrasados. En concordancia al punto anterior.</li><li>Si la hora y/o días seleccionadas ya pasaron al día y hora en la que se guardó la configuración, se enviará el correo en la siguiente ocasión. </ul></i> </strong>";
$lang["etapa_actores"] = "Roles y/o Usuarios notificados";
$lang["Ayuda_etapa_actores"] = "Seleccione los Roles y/o Usuarios que serán los actores directos de la Etapa. A estos usuarios se les enviará la notificación; la notificación obedecerá a la regionalización, por lo que si el Cliente es de una Agencia no asignada a los usuarios seleccionados, no serán notificados.";
$lang["FlujoActoresTitulo"] = "<i class='fa fa-users' aria-hidden='true'></i> Seleccione los Roles y/o Usuarios";
$lang["FlujoActoresSubtitulo"] = "Seleccione los Roles y/o Usuarios que recibirán las notificaciones al llegar los Registros a ésta etapa. Los listados se guardarán temporalmente hasta que proceda con el guardado. <br /><br /> Recuerde que, para que los usuarios puedan ingresar a la bandeja correspondiente a la etapa, tiene que tener asignado la bandeja como parte de su Rol. <br / ><br /> Si selecciona un usuario que ya es parte de un Rol registrado, sólo será notificado 1 vez. A estos usuarios se les enviará la notificación; ";
$lang["FlujoActores_guardado"] = "<div class='PreguntaConfirmar'> <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> ¡Se guardó el listado de Roles y/o Usuarios correctamente! <br /><br /> Puede cerrar esta ventana. </div>";

// REPORTES

$lang["ReporteTitulo"] = "Reportes - Toma de Decisiones";
$lang["ReporteSubtitulo"] = "En este apartado podrá generar, a través de multifiltros interdependientes, los reportes del sistema respecto a: <br /><br /> - Graficación de las Etapas del Flujo y Seguimiento de Clientes. <br /> - Top Documentación Digitalizada Observada.";

$lang["reportes_ayuda"] = "<i class='fa fa-lightbulb-o' aria-hidden='true'></i> Puede Marcar/Desmarcar las etapas y puede ver el gráfico en pantalla completa haciendo doble clic en el.";

$lang["reportes_ayuda_pivot"] = "<i class='fa fa-lightbulb-o' aria-hidden='true'></i> Seleccione los campos para definirlos como filas y/o columnas y construir el reporte que requiera.";

$lang["reportes_tipo_reporte"] = "Tipo de Reporte";
$lang["reportes_opciones_agrupamiento"] = "Agrupar por";
$lang["reportes_opciones_dato_mostrar"] = "Dato a mostrar";
$lang["reportes_generar_tabla"] = "<i class='fa fa-table' aria-hidden='true'></i> Generar Reporte";
$lang["reportes_generar_grafica"] = "<i class='fa fa-bar-chart' aria-hidden='true'></i> Generar Gráfica";
$lang["reportes_toggle_resumen"] = "Mostrar/Ocultar Resumen";
$lang["reportes_boton_agregar_filtro"] = " <i class='fa fa-cog' aria-hidden='true'></i> AGREGAR FILTRO";
$lang["reportes_opciones_filtro"] = "Filtrar por";
$lang["reportes_exportar_pdf"] = " <i class='fa fa-file-pdf-o' aria-hidden='true'></i> Exportar a PDF";
$lang["reportes_exportar_excel"] = " <i class='fa fa-file-excel-o' aria-hidden='true'></i> Exportar a Excel";

$lang["reportes_opciones_agrupamiento_ayuda"] = "Refiere al criterio principal para mostrar los resultados, que pueden ser utilizado para un seguimiento específico o para efectuar comparaciones";
$lang["reportes_opciones_dato_mostrar_ayuda"] = "Refiere al dato que será sujeto de seguimiento, por ejemplo en horas trabajadas o empresas registradas";

$lang["ReporteTituloIzquierda"] = ":: " . $lang["NombreSistema_corto"] . " ::";
$lang["ReporteTituloCentro"] = " ";
$lang["ReporteTituloDerecha"] = "Reportes";

// CONSULTAS Y SEGUIMIENTO

$lang['sol_monto_bs'] = 'Monto en Bs.';

$lang['sol_monto_aux'] = "<span id='sol_titulo_monto_aux'><br /><br />Respecto a la columna \"" . $lang['sol_monto_bs'] . "\" se debe considerar lo siguiente:<br />- Muestra el valor registrado de la solicitud de crédito asociado al Lead (si en la solicitud de crédito se registró el monto en dólares, se procederá a realizar la conversión a bolivianos con el tipo de cambio registrado en la configuración del sistema).<br />- Si el Lead no proviene de una solicitud de crédito se mostrará 0.<br />- Si el Lead registró el monto de Desembolso COBIS, será ese valor el que se mostrará en dicha columna.</span>";

$lang["ConsultaTitulo"] = "Consultas y Seguimiento";
$lang["ConsultaSubtitulo"] = "En este apartado podrá realizar las consultas respecto a los leads y mantenimientos registrados en el sistema. Los resultados se mostrarán regionalizados de acuerdo a los permisos con los que cuente. <br /><br />Puede generar las consultas utilizando múltiples filtros de acuerdo a lo que requiera y para ver el detalle haga clic en “Más Opciones”." . $lang['sol_monto_aux'];

$lang["consulta_prospectos"] = "Consultas Clientes";
$lang["consulta_mantenimientos"] = "Consultas Mantenimientos";
$lang["consulta_solcred"] = "Consultas Solicitudes de Crédito";

$lang["consulta_pregunta_reporte"] = "Al cambiar el Tipo de Reporte se borrarán los filtros seleccionados ¿Desea Continuar?";
$lang["consulta_listado_pendiente"] = "<p style='text-align: center;'><i> Pendiente </i></p>";

$lang["consulta_opciones"] = "Más <br /> Opciones";
$lang["consulta_volver"] = "Volver al Reporte";

$lang["consulta_opciones_detalle"] = " <i class='fa fa-search' aria-hidden='true'></i> Detalle <br /> Cliente";
$lang["consulta_opciones_documentos"] = " <i class='fa fa-camera' aria-hidden='true'></i> Ver Elementos<br />Digitalizados";
$lang["consulta_opciones_empresa"] = "<i class='fa fa-id-card-o' aria-hidden='true'></i> Informe <br /> Consolidado";
$lang["consulta_opciones_ejecutivo"] = "<i class='fa fa-user-o' aria-hidden='true'></i> Detalle <br /> Oficial de Negocio";
$lang["consulta_opciones_observaciones"] = "<i class='fa fa-comment-o' aria-hidden='true'></i> Observaciones <br /> al Cliente";
$lang["consulta_opciones_comentarios_excepcion"] = "<i class='fa fa-signal' aria-hidden='true'></i> Detalle <br /> Avance Oficial de Negocio";
$lang["consulta_opciones_seguimiento"] = "<i class='fa fa-road' aria-hidden='true'></i> Detalle <br /> Seguimiento";

$lang["consulta_opciones_devolver"] = "<i class='fa fa-exclamation-circle' aria-hidden='true'></i> Observar y <br /> Devolver";

$lang["consulta_perfil_app"] = "Perfil App";

// REGIONALIZACIÓN

$lang["RegionalizaTitulo"] = "Asignar Agencia a Usuario";
$lang["RegionalizaSubtitulo"] = "En este apartado podrá asignar una o más Agencias para Supervisar. Por defecto el usuario tiene asignada la Agencia de la que depende, si selecciona/quita la misma Agencia de la que ya tiene asignada por defecto simplemente no tendrá ningún efecto.";

$lang["regionaliza_ayuda"] = "Podrá interactuar únicamente con registros de las agencias (Estructura Jerárquica) asignadas para su Supervisión. Si requiere otras/más estructuras puede solicitarlo al administrador del sistema.";
$lang["regionaliza_seleccion"] = "Seleccione la Agencia a la que requiere supervisar";
$lang["regionaliza_NoRegion"] = "Este registro no es parte de las regiones que supervisa. Si requiere la asignación, comuníquese con el Administrador del sistema.";
$lang["regionaliza_TablaNoRegistros"] = "<div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> No se tiene asignado registros en la Estructura Jerárquica que supervisa </div>";
$lang["regionaliza_TablaNoRegistros_filtro"] = "<div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> No se tiene asignado registros en la Estructura Jerárquica que supervisa. Intente cambiar el criterio del filtro. </div>";
$lang["regionaliza_TablaNoRegistros_plain"] = "No se tiene asignado registros en la Estructura Jerárquica que supervisa";

$lang["regionaliza_TablaNoRegistros_usuarioApp"] = "<div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> No hay usuarios pendientes de habilitación o No se tiene asignado registros en la Estructura Jerárquica que supervisa </div>";

$lang["regionaliza_nombre"] = "Agencia Asociada";

$lang["FuenteTitulo"] = "Gestión de Fuentes de Captación de Clientes";
$lang["FuenteSubtitulo"] = "En este apartado podrá gestionar las fuentes de captación de Clientes que se pueden ver en el formulario de registro de solicitudes de visita interno y externo.";

// REGIONALIZACIÓN

$lang["Region_asignar"] = "Asignar Agencias a Usuarios Seleccioandos";
$lang["RegionUsuarioTitulo"] = "Asignación de Agencias a Supervisar";
$lang["RegionUsuarioSubtitulo"] = "En este apartado podrá asignar las Agencias a usuarios específicos a fin de que puedan ver los Clientes sólo de los que corresponden a su(s) Agencia(s). <br /><br /> Si requiere adicionar más Agencias, o cambiar la Agencia asignada por defecto puede hacerlo a través del módulo de Gestión de Usuarios y Estructura.";
$lang["Region_asignados"] = "Agencias Asignadas";

// FORMULARIOS FIE - INICIO

// PRINCIPAL

$lang['prospecto_evaluacion'] = 'Evaluación Oficial Negocios';

$lang['monto_manual'] = 'Monto capacidad de pago (Manual)';
$lang['general_solicitante'] = 'Nombre Completo';

$lang['general_solicitante_corto'] = 'Solicitante';

$lang['general_registro'] = 'Registro';

$lang['general_unidad_familiar'] = 'Unidad Familiar';

$lang['general_ci'] = 'Carnet de Identidad';
$lang['general_ci_extension'] = 'Extensión';
$lang['general_telefono'] = 'Teléfono';
$lang['general_email'] = 'Correo Electrónico';
$lang['general_direccion'] = 'Dirección (Opcional)';

$lang['general_depende'] = 'Depende de';

$lang['general_categoria'] = 'Categoría del Registro';

$lang['general_actividad'] = 'Actividad';
$lang['general_destino'] = 'Destino del crédito';
$lang['general_comentarios'] = 'Observaciones';
$lang['general_interes'] = 'Grado de Interés (Opcional)';
$lang['general_productos'] = 'Productos de Interés (Opcional)';
$lang['operacion_antiguedad'] = 'Antigüedad de la actividad desde su inicio';
$lang['operacion_tiempo'] = 'Tiempo que desarrolla actividad en el mismo lugar';
$lang['operacion_efectivo'] = 'Efectivo';
$lang['operacion_dias'] = 'De cuantos días';
$lang['aclarar_contado'] = 'Contado';
$lang['aclarar_credito'] = 'Crédito';
$lang['frec_seleccionar'] = 'Frecuencia de ventas';
$lang['frec_dia_lunes'] = 'Lunes';
$lang['frec_dia_martes'] = 'Martes';
$lang['frec_dia_miercoles'] = 'Miercoles';
$lang['frec_dia_jueves'] = 'Jueves';
$lang['frec_dia_viernes'] = 'Viernes';
$lang['frec_dia_sabado'] = 'Sábado';
$lang['frec_dia_domingo'] = 'Domingo';
$lang['frec_dia_semana_sel'] = 'Seleccione el numero de semana de la evaluación. Califique la semana bueno, malo, regular (Selección)';
$lang['frec_dia_semana_sel_brm'] = 'Seleccione el numero de semana de la evaluación. Califique la semana bueno, malo, regular (brm)';
$lang['frec_dia_monto_bueno'] = 'Completar que monto considera venta Buena Regular y Mal (Bueno)';
$lang['frec_dia_monto_regular'] = 'Completar que monto considera venta Buena Regular y Mal (Regular)';
$lang['frec_dia_monto_malo'] = 'Completar que monto considera venta Buena Regular y Mal (Malo)';
$lang['frec_dia_eval_semana1_brm'] = 'Primera (brm)';
$lang['frec_dia_eval_semana1_monto'] = 'Primera (monto)';
$lang['frec_dia_eval_semana2_brm'] = 'Segunda (brm)';
$lang['frec_dia_eval_semana2_monto'] = 'Segunda (monto)';
$lang['frec_dia_eval_semana3_brm'] = 'Tercera (brm)';
$lang['frec_dia_eval_semana3_monto'] = 'Tercera (monto)';
$lang['frec_dia_eval_semana4_brm'] = 'Cuarta (brm)';
$lang['frec_dia_eval_semana4_monto'] = 'Cuarta (monto)';
$lang['frec_sem_semana1_monto'] = 'Primera';
$lang['frec_sem_semana2_monto'] = 'Segunda';
$lang['frec_sem_semana3_monto'] = 'Tercera';
$lang['frec_sem_semana4_monto'] = 'Cuarta';
$lang['frec_mes_mes1'] = 'Mes Primero ';
$lang['frec_mes_mes1_monto'] = 'Mes Primero (monto)';
$lang['frec_mes_mes2'] = 'Mes Segundo';
$lang['frec_mes_mes2_monto'] = 'Mes Segundo (monto)';
$lang['frec_mes_mes3'] = 'Mes Tercero';
$lang['frec_mes_mes3_monto'] = 'Mes Tercero (monto)';
$lang['margen_utilidad_productos'] = '% DE PARTICIPACION DE PRINCIPALES PRODUCTOS EN LAS VENTAS';
$lang['porcentaje_participacion_proveedores'] = '% DE CONCENTRACION DE COMPRAS EN PRINCIPALES PROVEEDORES';
$lang['estacion_sel'] = 'La   actividad ¿tiene  estacionalidad  marcada?';
$lang['estacion_sel_arb'] = 'Identificar  la estacionalidad  del mes  de  evaluacion   y  categorizar   con  los demas  meses ';
$lang['estacion_sel_mes'] = 'Identificar  la estacionalidad  del mes  de  evaluacion   y  categorizar   con  los demas  meses ';
$lang['estacion_alta_monto'] = 'Alta (monto)';
$lang['estacion_regular_monto'] = 'Regular (monto)';
$lang['estacion_bajo_monto'] = 'Bajo (monto)';
$lang['estacion_ene_arb'] = 'Enero:';
$lang['estacion_feb_arb'] = 'Febrero:';
$lang['estacion_mar_arb'] = 'Marzo:';
$lang['estacion_abr_arb'] = 'Abril:';
$lang['estacion_may_arb'] = 'Mayo:';
$lang['estacion_jun_arb'] = 'Junio:';
$lang['estacion_jul_arb'] = 'Julio:';
$lang['estacion_ago_arb'] = 'Agosto:';
$lang['estacion_sep_arb'] = 'Septiembre:';
$lang['estacion_oct_arb'] = 'Octubre:';
$lang['estacion_nov_arb'] = 'Noviembre:';
$lang['estacion_dic_arb'] = 'Diciembre:';

$lang['estacion_no_accion'] = '<i class="fa fa-check-circle" aria-hidden="true"></i> No es requerido más acciones.';

$lang['operativos_alq_energia_monto'] = 'Energía eléctrica';
$lang['operativos_alq_energia_aclaracion'] = 'Energía eléctrica (aclaracion)';
$lang['operativos_alq_agua_monto'] = 'Agua Potable';
$lang['operativos_alq_agua_aclaracion'] = 'Agua Potable (aclaracion)';
$lang['operativos_alq_internet_monto'] = 'Telefonía fija, celular e internet';
$lang['operativos_alq_internet_aclaracion'] = 'Telefonía fija, celular e internet (aclaracion)';
$lang['operativos_alq_combustible_monto'] = 'Combustible';
$lang['operativos_alq_combustible_aclaracion'] = 'Combustible (aclaración)';
$lang['operativos_alq_libre1_texto'] = 'Campo libre Alquiler 1 (Texto)';
$lang['operativos_alq_libre1_monto'] = 'Campo libre Alquiler 1';
$lang['operativos_alq_libre1_aclaracion'] = 'Campo libre Alquiler 1 (aclaración)';
$lang['operativos_alq_libre2_texto'] = 'Campo libre Alquiler 2 (texto)';
$lang['operativos_alq_libre2_monto'] = 'Campo libre Alquiler 2';
$lang['operativos_alq_libre2_aclaracion'] = 'Campo libre Alquiler 2 (aclaración)';
$lang['operativos_sal_aguinaldos_monto'] = 'Aguinaldos';
$lang['operativos_sal_aguinaldos_aclaracion'] = 'Aguinaldos (aclaración)';
$lang['operativos_sal_libre1_texto'] = 'Campo libre salario 1 (texto)';
$lang['operativos_sal_libre1_monto'] = 'Campo libre salario 1';
$lang['operativos_sal_libre1_aclaracion'] = 'Campo libre salario 1 (aclaración)';
$lang['operativos_sal_libre2_texto'] = 'Campo libre salario 2 (texto)';
$lang['operativos_sal_libre2_monto'] = 'Campo libre salario 2';
$lang['operativos_sal_libre2_aclaracion'] = 'Campo libre salario 2 (aclaración)';
$lang['operativos_sal_libre3_texto'] = 'Campo libre salario 3 (texto)';
$lang['operativos_sal_libre3_monto'] = 'Campo libre salario 3';
$lang['operativos_sal_libre3_aclaracion'] = 'Campo libre salario 3 (aclaración)';
$lang['operativos_sal_libre4_texto'] = 'Campo libre salario 4 (texto)';
$lang['operativos_sal_libre4_monto'] = 'Campo libre salario 4';
$lang['operativos_sal_libre4_aclaracion'] = 'Campo libre salario 4 (aclaración)';
$lang['operativos_otro_transporte_monto'] = 'Transporte';
$lang['operativos_otro_transporte_aclaracion'] = 'Transporte (aclaración)';
$lang['operativos_otro_licencias_monto'] = 'Licencias, patentes, e impuestos';
$lang['operativos_otro_licencias_aclaracion'] = 'Licencias, patentes, e impuestos (aclaración)';
$lang['operativos_otro_alimentacion_monto'] = 'Alimentación /Refrigerio';
$lang['operativos_otro_alimentacion_aclaracion'] = 'Alimentación /Refrigerio (aclaración)';
$lang['operativos_otro_mant_vehiculo_monto'] = 'Mantenimiento Vehículo';
$lang['operativos_otro_mant_vehiculo_aclaracion'] = 'Mantenimiento Vehículo (aclaración)';
$lang['operativos_otro_mant_maquina_monto'] = 'Mantenimiento Maquinaria';
$lang['operativos_otro_mant_maquina_aclaracion'] = 'Mantenimiento Maquinaria (aclaración)';
$lang['operativos_otro_imprevistos_monto'] = 'Imprevistos';
$lang['operativos_otro_imprevistos_aclaracion'] = 'Imprevistos (aclaración)';
$lang['operativos_otro_otros_monto'] = 'Otros gastos';
$lang['operativos_otro_otros_aclaracion'] = 'Otros gastos (aclaración)';
$lang['operativos_otro_libre1_texto'] = 'Campo libre otro 1 (Texto)';
$lang['operativos_otro_libre1_monto'] = 'Campo libre otro 1';
$lang['operativos_otro_libre1_aclaracion'] = 'Campo libre otr 1 (aclaración)';
$lang['operativos_otro_libre2_texto'] = 'Campo libre otro 2 (Texto)';
$lang['operativos_otro_libre2_monto'] = 'Campo libre otro 2';
$lang['operativos_otro_libre2_aclaracion'] = 'Campo libre otro 2 (aclaración)';
$lang['operativos_otro_libre3_texto'] = 'Campo libre otro 3 (Texto)';
$lang['operativos_otro_libre3_monto'] = 'Campo libre otro 3';
$lang['operativos_otro_libre3_aclaracion'] = 'Campo libre otro 3 (aclaración)';
$lang['operativos_otro_libre4_texto'] = 'Campo libre otro 4 (Texto)';
$lang['operativos_otro_libre4_monto'] = 'Campo libre otro 4';
$lang['operativos_otro_libre4_aclaracion'] = 'Campo libre otro 4 (aclaración)';
$lang['operativos_otro_libre5_texto'] = 'Campo libre otro 5 (Texto)';
$lang['operativos_otro_libre5_monto'] = 'Campo libre otro 5';
$lang['operativos_otro_libre5_aclaracion'] = 'Campo libre otro 5 (aclaración)';
$lang['familiar_dependientes_ingreso'] = 'No. Dependientes del ingreso familiar';
$lang['familiar_edad_hijos'] = 'Detalle Edad de hijos';
$lang['familiar_alimentacion_monto'] = 'Alimentación Mensual';
$lang['familiar_alimentacion_aclaracion'] = 'Alimentación Mensual (aclaración)';
$lang['familiar_energia_monto'] = 'Energía eléctrica';
$lang['familiar_energia_aclaracion'] = 'Energía eléctrica (aclaración)';
$lang['familiar_agua_monto'] = 'Agua';
$lang['familiar_agua_aclaracion'] = 'Agua (aclaración)';
$lang['familiar_gas_monto'] = 'Gas';
$lang['familiar_gas_aclaracion'] = 'Gas (aclaración)';
$lang['familiar_telefono_monto'] = 'Teléfono';
$lang['familiar_telefono_aclaracion'] = 'Teléfono (aclaración)';
$lang['familiar_celular_monto'] = 'Celular(es)';
$lang['familiar_celular_aclaracion'] = 'Celular(es) (aclaración)';
$lang['familiar_internet_monto'] = 'Internet';
$lang['familiar_internet_aclaracion'] = 'Internet (aclaración)';
$lang['familiar_tv_monto'] = 'Tv Cable';
$lang['familiar_tv_aclaracion'] = 'Tv Cable (aclaración)';
$lang['familiar_impuestos_monto'] = 'Impuestos a la propiedad';
$lang['familiar_impuestos_aclaracion'] = 'Impuestos a la propiedad (aclaración)';
$lang['familiar_alquileres_monto'] = 'Alquileres';
$lang['familiar_alquileres_aclaracion'] = 'Alquileres (aclaración)';
$lang['familiar_educacion_monto'] = 'Educación';
$lang['familiar_educacion_aclaracion'] = 'Educación (aclaración)';
$lang['familiar_transporte_monto'] = 'Transporte';
$lang['familiar_transporte_aclaracion'] = 'Transporte (aclaración)';
$lang['familiar_salud_monto'] = 'Salud';
$lang['familiar_salud_aclaracion'] = 'Salud (aclaración)';
$lang['familiar_empleada_monto'] = 'Empleada';
$lang['familiar_empleada_aclaracion'] = 'Empleada (aclaración)';
$lang['familiar_diversion_monto'] = 'Diversión';
$lang['familiar_diversion_aclaracion'] = 'Diversión (aclaración)';
$lang['familiar_vestimenta_monto'] = 'Vestimenta';
$lang['familiar_vestimenta_aclaracion'] = 'Vestimenta (aclaración)';
$lang['familiar_otros_monto'] = 'Otros';
$lang['familiar_otros_aclaracion'] = 'Otros (aclaración)';
$lang['familiar_libre1_texto'] = 'Campo libre familiar  1 (texto)';
$lang['familiar_libre1_monto'] = 'Campo libre familiar  1';
$lang['familiar_libre1_aclaracion'] = 'Campo libre familiar  1 (aclaración)';
$lang['familiar_libre2_texto'] = 'Campo libre familiar  2 (texto)';
$lang['familiar_libre2_monto'] = 'Campo libre familiar  2';
$lang['familiar_libre2_aclaracion'] = 'Campo libre familiar  2 (aclaración)';
$lang['familiar_libre3_texto'] = 'Campo libre familiar  3 (texto)';
$lang['familiar_libre3_monto'] = 'Campo libre familiar  3';
$lang['familiar_libre3_aclaracion'] = 'Campo libre familiar  3 (aclaración)';
$lang['familiar_libre4_texto'] = 'Campo libre familiar  4 (texto)';
$lang['familiar_libre4_monto'] = 'Campo libre familiar  4';
$lang['familiar_libre4_aclaracion'] = 'Campo libre familiar  4 (aclaración)';
$lang['familiar_libre5_texto'] = 'Campo libre familiar  5 (texto)';
$lang['familiar_libre5_monto'] = 'Campo libre familiar  5';
$lang['familiar_libre5_aclaracion'] = 'Campo libre familiar  5 (aclaración)';

$lang['extra_amortizacion_otras_deudas'] = 'Amortización de otras deudas';
$lang['extra_amortizacion_otras_deudas_transporte'] = 'Cuotas de Otras Deudas';
$lang['extra_cuota_prestamo_solicitado'] = 'Cuota Prestamo Solicitado';
$lang['extra_cuota_maxima_credito'] = 'Cuota máxima de línea de crédito';
$lang['extra_amortizacion_credito'] = 'Amortización crédito que solicita';
$lang['extra_efectivo_caja'] = 'Efectivo en caja y bancos';
$lang['extra_ahorro_dpf'] = 'Cuenta de ahorro DPF';
$lang['extra_cuentas_cobrar'] = 'Cuentas por cobrar';
$lang['extra_inventario'] = 'Inventario';
$lang['extra_otros_activos_corrientes'] = 'Otros activos corrientes';
$lang['extra_activo_fijo'] = 'Activo fijo';
$lang['extra_otros_activos_nocorrientes'] = 'Otros activos no corrientes';
$lang['extra_activos_actividades_secundarias'] = 'Activos de actividades secundarias';
$lang['extra_inmuebles_terrenos'] = 'Inmuebles y terrenos';
$lang['extra_bienes_hogar'] = 'Bienes del hogar';
$lang['extra_otros_activos_familiares'] = 'Otros activo familiares';
$lang['extra_endeudamiento_credito'] = 'Endeudamiento con el crédito propuesto';
$lang['extra_personal_ocupado'] = 'Personal ocupado';
$lang['extra_cuentas_pagar_proveedores'] = 'Cuentas por pagar a proveedores';
$lang['extra_prestamos_financieras_corto'] = 'Prestamos con Ent. Fin. a corto plazo';
$lang['extra_cuentas_pagar_corto'] = 'Otras cuentas por pagar a corto plazo';
$lang['extra_prestamos_financieras_largo'] = 'Prestamos con Ent. Fin. a largo plazo';
$lang['extra_cuentas_pagar_largo'] = 'Otras cuentas por pagar a largo plazo';
$lang['extra_pasivo_actividades_secundarias'] = 'Pasivo de actividades secundarias';
$lang['extra_pasivo_familiar'] = 'Pasivo familiar';

$lang['ingreso_mensual_promedio'] = 'Ingreso Mensual Promedio';


// MATERIA PRIMA
$lang['materia_nombre'] = 'Materia Prima';
$lang['materia_frecuencia'] = 'Frecuencia de compra';
$lang['materia_unidad_compra'] = 'Unidad de compra';
$lang['materia_unidad_compra_cantidad'] = 'Cantidad que compra segun unidad de compra';
$lang['materia_unidad_uso'] = 'Unidad de uso ';
$lang['materia_unidad_uso_cantidad'] = 'Cantidades de unidades de uso por unidad de compra';
$lang['materia_unidad_proceso'] = 'Unidades de uso que entra por proceso';
$lang['materia_producto_medida'] = 'Unidad de medida del producto terminado';
$lang['materia_producto_medida_cantidad'] = 'Cantidad de producto terminado por proceso segun unidades de uso por proceso';
$lang['materia_precio_unitario'] = 'Precio de venta unitario Bs.(Producto terminado)';
$lang['materia_ingreso_estimado'] = 'Ingreso Estimado Mensual Bs.';
$lang['materia_aclaracion'] = 'Aclaración';


// PROVEEDORES
$lang['proveedor_nombre'] = 'Datos del proveedor';
$lang['proveedor_lugar_compra'] = 'Lugar de compra';
$lang['proveedor_frecuencia_dias'] = 'Frecuencia en dias';
$lang['proveedor_importe'] = 'Importe Bs.';
$lang['proveedor_fecha_ultima'] = 'Fecha de última compra';
$lang['proveedor_aclaracion'] = 'Aclaración';

// INVENTARIO
$lang['inventario_descripcion'] = 'Descripción';
$lang['inventario_frecuencia'] = 'Frecuencia en Dias';
$lang['inventario_compra_cantidad'] = 'Cantidad de Compra ';
$lang['inventario_compra_medida'] = 'Unidad de medida de compra';
$lang['inventario_venta_cantidad'] = 'Cantidad vendida';
$lang['inventario_venta_medida'] = 'Unidad de medida de venta';
$lang['inventario_compra_precio'] = 'Precio de Compra (De la unidad de Compra)';
$lang['inventario_venta_precio'] = 'Precio de Venta (De la unidad de Venta)';
$lang['inventario_unidad_venta_compra'] = 'Cuantas unidades de venta nos da una unidad de compra';
$lang['inventario_categoria'] = 'Categoria (Mercadería, MP,PP,PT)';
$lang['intentario_aclaracion'] = 'Aclaración';
$lang['inventario_seleccion'] = 'Registro Seleccionado';

// PRODUCTO
$lang['producto_nombre'] = 'Nombre del Producto';
$lang['producto_unidad'] = 'Unidad de medida';
$lang['producto_medida_cantidad'] = 'Cantidad segun unidad de medida';
$lang['producto_medida_precio'] = 'Precio de venta  segun unidad de medida Bs.';
$lang['detalle_descripcion'] = 'Descripción de la materia prima,  insumo o mano de obra directa';
$lang['detalle_cantidad'] = 'Cantidad';
$lang['detalle_unidad'] = 'Unidad de medida ';
$lang['detalle_costo_unitario'] = 'Costo unitario Bs.';
$lang['detalle_costo_medida_unidad'] = 'Unidad de medida de compra ';
$lang['detalle_costo_medida_precio'] = 'Precio de compra según unidad de medida Bs.';
$lang['detalle_costo_medida_convertir'] = '¿Convertir a unidad de medida de uso?';
$lang['detalle_costo_unidad_medida_uso'] = 'Unidad de medida de uso ';
$lang['detalle_costo_unidad_medida_cantidad'] = 'Cantidad de unidades de uso que contiene la unidad de compra';

$lang['detalle_costo_total'] = 'Costo Total Bs.';
$lang['detalle_costo_mub'] = 'Margen de Utilidad Bruta';

$lang['producto_costo_medida_unidad'] = 'Unidad de Medida';
$lang['producto_costo_medida_cantidad'] = 'Cantidad según Unidad de Medida';
$lang['producto_costo_medida_precio'] = 'Precio de Venta según Unidad de Medida Bs.';

$lang['producto_compra_cantidad'] = 'Cantidad en Inventario';
$lang['producto_compra_medida'] = 'Unidad de Medida de Compra';
$lang['producto_compra_precio'] = 'Precio de Compra Unitario';
$lang['producto_unidad_venta_unidad_compra'] = 'Cuantas unidades de Venta nos da una unidad de Compra';
$lang['producto_categoria_mercaderia'] = 'Categoría Mercadería';
$lang['producto_opcion'] = 'OPCIÓN DEL COSTO UNITARIO';
$lang['producto_seleccion'] = '¿SELECCIONADO PARA MARGEN?';

$lang["Usuario_perfil_app"] = "Acceso a FIE MicroApp";
$lang["Rol_perfil_app"] = "Seleccione si éste Rol, además de acceder al BackEnd, podrá también acceder a la App, si selecciona 'Ninguno' sólo podrá acceder al BackEnd. Recuerde que para que el usuario pueda acceder a la App, además de asignar el rol al usuario, debe habilitarlo en el módulo correspondiente para asignarle una cartera de clientes";

// -- Transporte

$lang['transporte_tipo_prestatario'] = 'TIPO DE PRESTATARIO';
$lang['transporte_tipo_transporte'] = 'TIPO DE TRANSPORTE';

$lang['transporte_preg_sindicato'] = '¿En qué sindicato o cooperativa trabaja?';
$lang['transporte_preg_sindicato_lineas'] = '¿Cuántas líneas tiene el sindicato?';
$lang['transporte_preg_sindicato_grupos'] = '¿Cuántos grupos tiene el sindicato?';
$lang['transporte_preg_unidades_grupo'] = '¿Cuántas unidades tiene su grupo?';
$lang['transporte_preg_grupo_rota'] = '¿Cada cuánto rota un grupo de línea?';
$lang['transporte_preg_lineas_buenas'] = '¿Cuántas líneas son buenas?';
$lang['transporte_preg_lineas_regulares'] = '¿Cuántas líneas son regulares?';
$lang['transporte_preg_lineas_malas'] = '¿Cuántas líneas son malas?';
$lang['transporte_preg_trabaja_semana'] = '¿Cuántos días a la semana trabaja?';

$lang['transporte_preg_trabaja_dia'] = '¿Cuántas horas trabaja al día?';
$lang['transporte_preg_jornada_inicia'] = '¿A qué hora inicia su jornada laboral?';
$lang['transporte_preg_jornada_concluye'] = '¿A qué hora concluye su jornada laboral?';
$lang['transporte_preg_tiempo_no_trabaja'] = '¿Durante su jornada laboral deja de trabajar algún periodo de tiempo?';

$lang['transporte_preg_tiempo_parada'] = '¿Cuánto tiempo permanece en parada?';
$lang['transporte_preg_tiempo_vuelta'] = '¿Cuánto tiempo dura una vuelta?';
$lang['transporte_preg_tiempo_carrera'] = '¿Cuánto tiempo dura una carrera?';
$lang['transporte_preg_vehiculo_ano'] = '¿Cuál es el año de fabricación de su vehículo?';
$lang['transporte_preg_vehiculo_combustible'] = '¿Qué combustible utiliza su vehículo?';

$lang['transporte_cliente_dia_lunes'] = 'Lunes';
$lang['transporte_cliente_dia_martes'] = 'Martes';
$lang['transporte_cliente_dia_miercoles'] = 'Miercoles';
$lang['transporte_cliente_dia_jueves'] = 'Jueves';
$lang['transporte_cliente_dia_viernes'] = 'Viernes';
$lang['transporte_cliente_dia_sabado'] = 'Sábado';
$lang['transporte_cliente_dia_domingo'] = 'Domingo';

$lang['transporte_capacidad_sin_rotacion'] = 'Capacidad Instalada sin Rotación';
$lang['transporte_capacidad_con_rotacion'] = 'Capacidad Instalada con Rotación';

$lang['transporte_capacidad_tramo_largo_pasajero'] = 'Pasajeros Tramo Largo';
$lang['transporte_capacidad_tramo_corto_pasajero'] = 'Pasajeros Tramo Corto';
$lang['transporte_capacidad_tramo_largo_precio'] = 'Precio Tramo Largo';
$lang['transporte_capacidad_tramo_corto_precio'] = 'Precio Tramo Corto';

$lang['margen_nombre'] = 'Principales Servicios / Insumos';
$lang['margen_cantidad'] = 'Cantidad';
$lang['margen_unidad_medida'] = 'Unidad de Medida';
$lang['margen_pasajeros'] = 'Pasajeros';


$lang['operativos_cambio_aceite_motor'] = ' CAMBIO DE ACEITE MOTOR ';
$lang['operativos_cambio_aceite_caja'] = ' CAMBIO DE ACEITE CAJA ';
$lang['operativos_cambio_llanta_delanteras'] = ' CAMBIO DE LLANTAS DELANTERAS ';
$lang['operativos_cambio_llanta_traseras'] = ' CAMBIO DE LLANTAS TRASERAS ';
$lang['operativos_cambio_bateria'] = ' CAMBIO DE BATERIA ';
$lang['operativos_cambio_balatas'] = ' PASTILLAS Y BALATAS ';
$lang['operativos_revision_electrico'] = ' REVISION SISTEMA ELECTRICO ';
$lang['operativos_remachado_embrague'] = ' REMACHADO DISCO DE EMBRAGUE ';
$lang['operativos_rectificacion_motor'] = ' RECTIFICACIÓN DE MOTOR ';
$lang['operativos_cambio_rodamiento'] = ' CAMBIO DE RODAMIENTOS Y MUÑONES ';
$lang['operativos_reparaciones_menores'] = ' REPARACIONES MENORES ';
$lang['operativos_imprevistos'] = ' IMPREVISTOS ';
$lang['operativos_impuesto_propiedad'] = ' IMPUESTO A LA PROPIEDAD ';
$lang['operativos_soat'] = ' SOAT ';
$lang['operativos_roseta_inspeccion'] = ' ROSETA DE INSPECCION ';

$lang['operativo_frecuencia'] = 'Frecuencia';
$lang['operativo_cantidad'] = 'Cantidad';
$lang['operativo_monto'] = 'C. Unitario';

$lang['otros_descripcion_fuente'] = 'Descripción de la fuente de ingreso';
$lang['otros_descripcion_respaldo'] = 'Descripción del respaldo';
$lang['otros_monto'] = 'Monto';

$lang['pasivo_acreedor'] = 'Acreedor';
$lang['pasivo_tipo'] = 'Tipo';
$lang['pasivo_saldo'] = 'Saldo Bs.';
$lang['pasivo_periodo'] = 'Periodo Cuota';
$lang['pasivo_cuota_periodica'] = 'Cuota Periodica';
$lang['pasivo_cuota_mensual'] = 'Cuota Mensual';
$lang['pasivo_rfto'] = 'Rfto.';
$lang['pasivo_destino'] = 'Destino de Crédito';


// FORMULARIOS FIE - FIN

// SOLICITUD DE CRÉDITOS - INICIO

$lang['sol_codigo'] = 'Código Solicitud';
$lang['sol_ci'] = 'Carnet de Identidad';
$lang['sol_extension'] = 'Extensión (opcional)';
$lang['sol_complemento'] = 'Complemento (opcional)';
$lang['sol_nombre_completo'] = 'Nombre Solicitante';
$lang['sol_primer_nombre'] = 'Primer Nombre';
$lang['sol_segundo_nombre'] = 'Segundo Nombre (opcional)';
$lang['sol_primer_apellido'] = 'Primer Apellido';
$lang['sol_segundo_apellido'] = 'Segundo Apellido (opcional)';
$lang['sol_correo'] = 'Correo Electrónico (opcional)';
$lang['sol_cel'] = 'Celular';
$lang['sol_telefono'] = 'Teléfono (opcional)';
$lang['sol_fec_nac'] = 'Fecha de Nacimiento (opcional)';
$lang['sol_est_civil'] = 'Estado Civil (opcional)';
$lang['sol_nit'] = 'NIT (si corresponde)';
$lang['sol_cliente'] = 'Tipo Cliente';
$lang['sol_dependencia'] = 'Selección Actividad Económica';
$lang['sol_indepen_actividad'] = 'Actividad que realiza';
$lang['sol_indepen_antiguedad'] = 'Antigüedad en la actividad (meses)';
$lang['sol_indepen_ant_ano'] = 'Años(s)';
$lang['sol_indepen_ant_mes'] = 'Mes(es)';
$lang['sol_indepen_atencion'] = 'Horario y días de atención';
$lang['sol_indepen_horario_desde'] = 'Desde';
$lang['sol_indepen_horario_hasta'] = 'Hasta';
$lang['sol_indepen_horario_dias'] = 'Días de trabajo';
$lang['sol_indepen_telefono'] = 'Teléfono/Celular';
$lang['sol_depen_empresa'] = 'Nombre de la empresa';
$lang['sol_depen_actividad'] = 'Actividad de la empresa';
$lang['sol_depen_cargo'] = 'Cargo actual';
$lang['sol_depen_antiguedad'] = 'Antigüedad';
$lang['sol_depen_ant_ano'] = 'Años(s)';
$lang['sol_depen_ant_mes'] = 'Mes(es)';
$lang['sol_depen_atencion'] = 'Horario y días de atención';
$lang['sol_depen_horario_desde'] = 'Desde';
$lang['sol_depen_horario_hasta'] = 'Hasta';
$lang['sol_depen_horario_dias'] = 'Días de trabajo';
$lang['sol_depen_telefono'] = 'Teléfono/Celular';
$lang['sol_depen_tipo_contrato'] = 'Tipo Contrato';
$lang['sol_monto'] = 'Monto Solicitado';
$lang['sol_plazo'] = 'Plazo (en meses)';
$lang['sol_moneda'] = 'Moneda';
$lang['sol_detalle'] = 'Detalle del destino del Crédito Solicitado';
$lang['sol_dir_departamento'] = 'Departamento';
$lang['sol_dir_provincia'] = 'Provincia';
$lang['sol_dir_localidad_ciudad'] = 'Ciudad';
$lang['sol_cod_barrio'] = 'Barrio/ Zona';
$lang['sol_direccion_trabajo'] = 'Avenida /Calle/ Pasaje';
$lang['sol_edificio_trabajo'] = 'Edificio/ Condominio/Urbanización';
$lang['sol_numero_trabajo'] = 'Nro';
$lang['sol_dir_referencia'] = 'Selección Referencia';
$lang['sol_geolocalizacion'] = 'Geolocalización';
$lang['sol_croquis'] = 'Imagen Croquis';
$lang['sol_trabajo_lugar'] = 'El lugar es';
$lang['sol_trabajo_lugar_otro'] = 'Otro';
$lang['sol_trabajo_realiza'] = 'Lo realiza en';
$lang['sol_trabajo_realiza_otro'] = 'Otro';
$lang['sol_trabajo_actividad_pertenece'] = 'Actividad del';
$lang['sol_dir_departamento_dom'] = 'Departamento';
$lang['sol_dir_provincia_dom'] = 'Provincia';
$lang['sol_dir_localidad_ciudad_dom'] = 'Ciudad';
$lang['sol_cod_barrio_dom'] = 'Barrio/ Zona';
$lang['sol_direccion_dom'] = 'Avenida /Calle/ Pasaje';
$lang['sol_edificio_dom'] = 'Edificio/ Condominio/Urbanización';
$lang['sol_numero_dom'] = 'Nro';
$lang['sol_dir_referencia_dom'] = 'Selección Referencia';
$lang['sol_geolocalizacion_dom'] = 'Geolocalización';
$lang['sol_croquis_dom'] = 'Imagen Croquis';
$lang['sol_dom_tipo'] = 'La casa donde vive es';
$lang['sol_dom_tipo_otro'] = 'Otro';
$lang['sol_conyugue'] = 'Registrar actividad del cónyuge (Opcional)';
$lang['sol_con_ci'] = 'Carnet de Identidad';
$lang['sol_con_extension'] = 'Extensión (opcional)';
$lang['sol_con_complemento'] = 'Complemento (opcional)';
$lang['sol_con_primer_nombre'] = 'Primer Nombre';
$lang['sol_con_segundo_nombre'] = 'Segundo Nombre (opcional)';
$lang['sol_con_primer_apellido'] = 'Primer Apellido';
$lang['sol_con_segundo_apellido'] = 'Segundo Apellido (opcional)';
$lang['sol_con_correo'] = 'Correo Electrónico (opcional)';
$lang['sol_con_cel'] = 'Celular';
$lang['sol_con_telefono'] = 'Teléfono (opcional)';
$lang['sol_con_fec_nac'] = 'Fecha de Nacimiento (opcional)';
$lang['sol_con_est_civil'] = 'Estado Civil';
$lang['sol_con_nit'] = 'NIT (si corresponde)';
$lang['sol_con_cliente'] = 'Tipo Cliente';
$lang['sol_con_dependencia'] = 'Selección Actividad Económica';
$lang['sol_con_indepen_actividad'] = 'Actividad que realiza';
$lang['sol_con_indepen_antiguedad'] = 'Antigüedad en la actividad (meses)';
$lang['sol_con_indepen_ant_ano'] = 'Años(s)';
$lang['sol_con_indepen_ant_mes'] = 'Mes(es)';
$lang['sol_con_indepen_atencion'] = 'Horario y días de atención';
$lang['sol_con_indepen_horario_desde'] = 'Desde';
$lang['sol_con_indepen_horario_hasta'] = 'Hasta';
$lang['sol_con_indepen_horario_dias'] = 'Días de trabajo';
$lang['sol_con_indepen_telefono'] = 'Teléfono/Celular';
$lang['sol_con_depen_empresa'] = 'Nombre de la empresa';
$lang['sol_con_depen_actividad'] = 'Actividad que realiza';
$lang['sol_con_depen_cargo'] = 'Cargo actual';
$lang['sol_con_depen_ant_ano'] = 'Años(s)';
$lang['sol_con_depen_ant_mes'] = 'Mes(es)';
$lang['sol_con_depen_horario_desde'] = 'Desde';
$lang['sol_con_depen_horario_hasta'] = 'Hasta';
$lang['sol_con_depen_horario_dias'] = 'Días de trabajo';
$lang['sol_con_depen_telefono'] = 'Teléfono/Celular';
$lang['sol_con_depen_tipo_contrato'] = 'Tipo Contrato';
$lang['sol_con_dir_departamento'] = 'Departamento';
$lang['sol_con_dir_provincia'] = 'Provincia';
$lang['sol_con_dir_localidad_ciudad'] = 'Ciudad';
$lang['sol_con_cod_barrio'] = 'Barrio/ Zona';
$lang['sol_con_direccion_trabajo'] = 'Avenida /Calle/ Pasaje';
$lang['sol_con_edificio_trabajo'] = 'Edificio/ Condominio/Urbanización';
$lang['sol_con_numero_trabajo'] = 'Nro';
$lang['sol_con_dir_referencia'] = 'Selección Referencia';
$lang['sol_con_geolocalizacion'] = 'Geolocalización';
$lang['sol_con_croquis'] = 'Imagen Croquis';
$lang['sol_con_trabajo_lugar'] = 'El lugar es';
$lang['sol_con_trabajo_lugar_otro'] = 'Otro';
$lang['sol_con_trabajo_realiza'] = 'Lo realiza en';
$lang['sol_con_trabajo_realiza_otro'] = 'Otro';
$lang['sol_con_trabajo_actividad_pertenece'] = 'Actividad del';
$lang['sol_con_dir_departamento_dom'] = 'Departamento';
$lang['sol_con_dir_provincia_dom'] = 'Provincia';
$lang['sol_con_dir_localidad_ciudad_dom'] = 'Ciudad';
$lang['sol_con_cod_barrio_dom'] = 'Barrio/ Zona';
$lang['sol_con_direccion_dom'] = 'Avenida /Calle/ Pasaje';
$lang['sol_con_edificio_dom'] = 'Edificio/ Condominio/Urbanización';
$lang['sol_con_numero_dom'] = 'Nro';
$lang['sol_con_dir_referencia_dom'] = 'Selección Referencia';
$lang['sol_con_geolocalizacion_dom'] = 'Geolocalización';
$lang['sol_con_croquis_dom'] = 'Imagen Croquis';
$lang['sol_con_dom_tipo'] = 'La casa donde vive es';
$lang['sol_con_dom_tipo_otro'] = 'Otro';

$lang['sol_form_generar'] = 'Formulario Solicitud';

$lang['sol_con_sin_seleccion'] = '<br /><br /><div class="col-sm-12"style="text-align: center;"> <label class="label-campo texto-centrado" for=""> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Seleccionó <u>NO</u> registrar la actividad del cónyuge. Puede continuar con el registro. </label><br /></div>';

$lang['sol_direccion_error'] = 'Seleccionó la estructura de dirección, debe registrar correctamente el ' . $lang['sol_dir_departamento'] . ', ' . $lang['sol_dir_provincia'] . ', ' . $lang['sol_dir_localidad_ciudad'] . ' y ' . $lang['sol_cod_barrio'] . '.';

$lang['sol_no_consolidado'] = 'No se realizó la consolidación, por favor revise el registro.';
$lang['sol_error_anituedad'] = 'Antigüedad en la actividad no acepta valores negativos.';

$lang['sol_estudio'] = 'Solicitud de Crédito';

$lang['sol_info_formulario'] = 'Al generar el reporte se construirán elementos dependientes de geolocalización y conversión de formatos del croquis; esta acción es compatible sólo con exploradores web modernos y requiere una conexión estable a internet. Si el PDF o alguna georreferenciación no se visualiza, por favor verifique que no tenga ningún bloqueo a nivel de red y vuelva a intentar la generación del PDF.';

$lang['agencia_departamento'] = 'Agencia - Departamento';
$lang['agencia_provincia'] = 'Agencia - Provincia';
$lang['agencia_ciudad'] = 'Agencia - Ciudad';

$lang['sol_id'] = 'Solicitud de Crédito - Código';
$lang['sol_consolidado_usuario'] = 'Solicitud de Crédito Consolidado - Usuario';
$lang['sol_consolidado_fecha'] = 'Solicitud de Crédito Consolidado - Fecha';
$lang['sol_consolidado_geo'] = 'Solicitud de Crédito Consolidado - Geolocalización';
$lang['prospecto_fecha_conclusion'] = 'Fecha de conclusión del registro';
$lang['prospecto_observado_app'] = 'Registro Observado';
$lang['prospecto_consolidar_geo'] = 'Consolidado - Geolocalización';
$lang['prospecto_principal'] = 'Actividad Principal';
$lang['frec_seleccion'] = 'Frecuencia de Ventas';
$lang['capacidad_criterio'] = 'Capacidad de Pago Seleccionada';
$lang['ingreso_ventas'] = 'Capacidad de Pago - Ingreso/Ventas';
$lang['costo_ventas'] = 'Capacidad de Pago - Costo Ventas';
$lang['utilidad_bruta'] = 'Capacidad de Pago - Utilidad Bruta';
$lang['utilidad_operativa'] = 'Capacidad de Pago - Utilidad Operativa';
$lang['utilidad_neta'] = 'Capacidad de Pago - Utilidad Neta';
$lang['saldo_disponible'] = 'Capacidad de Pago - Saldo Disponible';
$lang['margen_ahorro'] = 'Capacidad de Pago - Margen de Ahorro';

$lang['estudio_agencia_nombre'] = 'Estudio Crédito - Agencia Nombre';
$lang['estudio_agencia_departamento'] = 'Estudio Crédito - Agencia Departamento';
$lang['estudio_agencia_provincia'] = 'Estudio Crédito - Agencia Provincia';
$lang['estudio_agencia_ciudad'] = 'Estudio Crédito - Agencia Ciudad';
$lang['sol_asistencia'] = 'Solicitud de Crédito - Proceso';
$lang['sol_codigo_rubro'] = 'Solicitud de Crédito - Rubro';
$lang['sol_agencia_nombre'] = 'Solicitud Crédito - Agencia ';
$lang['sol_agencia_departamento'] = 'Solicitud Crédito - Agencia Departamento';
$lang['sol_agencia_provincia'] = 'Solicitud Crédito - Agencia Provincia';
$lang['sol_agencia_ciudad'] = 'Solicitud Crédito - Agencia Ciudad';

$lang['sol_proviene'] = 'Proviene de Solicitud de Crédito';

// Actividad Secundaria

$lang['sol_secundaria_separador'] = '(A. Secundaria) ';

$lang['sol_actividad_secundaria'] = 'ACTIVIDAD SECUNDARIA (Opcional)';
$lang['sol_codigo_rubro_sec'] = 'Actividad Secundaria - Rubro';

$lang['sol_dependencia_sec'] = 'Selección Actividad Económica';
$lang['sol_indepen_actividad_sec'] = 'Actividad que realiza';
$lang['sol_indepen_antiguedad_sec'] = 'Antigüedad en la actividad (meses)';
$lang['sol_indepen_ant_ano_sec'] = 'Años(s)';
$lang['sol_indepen_ant_mes_sec'] = 'Mes(es)';
$lang['sol_indepen_atencion_sec'] = 'Horario y días de atención';
$lang['sol_indepen_horario_desde_sec'] = 'Desde';
$lang['sol_indepen_horario_hasta_sec'] = 'Hasta';
$lang['sol_indepen_horario_dias_sec'] = 'Días de trabajo';
$lang['sol_indepen_telefono_sec'] = 'Teléfono/Celular';
$lang['sol_depen_empresa_sec'] = 'Nombre de la empresa';
$lang['sol_depen_actividad_sec'] = 'Actividad de la empresa';
$lang['sol_depen_cargo_sec'] = 'Cargo actual';
$lang['sol_depen_antiguedad_sec'] = 'Antigüedad';
$lang['sol_depen_ant_ano_sec'] = 'Años(s)';
$lang['sol_depen_ant_mes_sec'] = 'Mes(es)';
$lang['sol_depen_atencion_sec'] = 'Horario y días de atención';
$lang['sol_depen_horario_desde_sec'] = 'Desde';
$lang['sol_depen_horario_hasta_sec'] = 'Hasta';
$lang['sol_depen_horario_dias_sec'] = 'Días de trabajo';
$lang['sol_depen_telefono_sec'] = 'Teléfono/Celular';
$lang['sol_depen_tipo_contrato_sec'] = 'Tipo Contrato';
$lang['sol_monto_sec'] = 'Monto Solicitado';
$lang['sol_plazo_sec'] = 'Plazo (en meses)';
$lang['sol_moneda_sec'] = 'Moneda';
$lang['sol_detalle_sec'] = 'Detalle del destino del Crédito Solicitado';
$lang['sol_dir_departamento_sec'] = 'Departamento';
$lang['sol_dir_provincia_sec'] = 'Provincia';
$lang['sol_dir_localidad_ciudad_sec'] = 'Ciudad';
$lang['sol_cod_barrio_sec'] = 'Barrio/ Zona';
$lang['sol_direccion_trabajo_sec'] = 'Avenida /Calle/ Pasaje';
$lang['sol_edificio_trabajo_sec'] = 'Edificio/ Condominio/Urbanización';
$lang['sol_numero_trabajo_sec'] = 'Nro';
$lang['sol_dir_referencia_sec'] = 'Selección Referencia';
$lang['sol_geolocalizacion_sec'] = 'Geolocalización';
$lang['sol_croquis_sec'] = 'Imagen Croquis';
$lang['sol_trabajo_lugar_sec'] = 'El lugar es';
$lang['sol_trabajo_lugar_otro_sec'] = 'Otro';
$lang['sol_trabajo_realiza_sec'] = 'Lo realiza en';
$lang['sol_trabajo_realiza_otro_sec'] = 'Otro';
$lang['sol_trabajo_actividad_pertenece_sec'] = 'Actividad del';

// SOLICITUD DE CRÉDITOS - FIN

// -- Nuevos Estados

$lang['prospecto_jda_eval'] = 'Evaluación JDA';
$lang['prospecto_jda_eval_texto'] = 'Observaciones';
$lang['prospecto_jda_eval_usuario'] = $lang['prospecto_jda_eval'] . ' - Usuario';
$lang['prospecto_jda_eval_fecha'] = $lang['prospecto_jda_eval'] . ' - Fecha';

$lang['prospecto_jda_eval_msgapi_off'] = "Ingrese el número de Operación para poder realizar la verificación, la verificaciòn se la realizará de forma automática al momento de ingresar un número de verificación válida.<br> Recuerda que el número de operación debe contener 11 dígitos. ";
$lang['prospecto_jda_eval_msgapi_on'] = $lang['prospecto_jda_eval'] . ' - Fecha';

$lang['registro_num_proceso'] = 'Número de Operación';
$lang['registro_num_proceso_button'] = '<i class="fa fa-pencil-square-o" aria-hidden="true"></i> REGISTRAR ' . mb_strtoupper($lang['registro_num_proceso']);
$lang['registro_num_proceso_titulo'] = 'Por favor registre el ' . $lang['registro_num_proceso'] . '.';
$lang['registro_num_proceso_cantidad'] = '11';
$lang['registro_num_proceso_validate'] = 'debe ser ' . $lang['registro_num_proceso_cantidad'] . ' dígitos (mandatorio, numérico y sin caracteres especiales)';
$lang['registro_num_proceso_error'] = 'El ' . $lang['registro_num_proceso'] . ' ' . $lang['registro_num_proceso_validate'] . '.';
$lang['registro_num_proceso_label'] = '<span id="registro_num_proceso_label_ok" style="display: none;color: #006699;font-size: 1.7em;" title="¡Valor Correcto!"><i class="fa fa-check-circle" aria-hidden="true"></i></span><span id="registro_num_proceso_label_error" style="display: none;color: #ae0404;font-size: 1.7em;" title="¡Valor Incorrecto!"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></span>';
$lang['registro_num_proceso_error_desemb'] = $lang['registro_num_proceso_error'] . ' Por favor debe registrarlo previamente en "' . $lang['prospecto_jda_eval'] . '".';

$lang['registro_num_proceso_error_app'] = 'No Consolidado: Debe registrar correctamente el ' . mb_strtoupper($lang['registro_num_proceso']) . ' para poder consolidar.';

$lang['TituloJDA_Evaluacion'] = 'Evaluación JDA del Lead';
$lang['SubJDA_Evaluacion'] = 'En este apartado podrá validar y/o actualizar el ' . $lang['registro_num_proceso'] . ' que ' . $lang['registro_num_proceso_validate'] . ' y marcar la evaluación del JDA al registro. Proceda a marcar la opción de la evaluación como “Aprobado” o “Rechazado” y opcionalmente un comentario.<br /><br />Si su usuario tiene asignado el perfil "Detalle Cliente", podrá visualizar el detalle del registro seleccionando la opción <i class="fa fa-external-link" aria-hidden="true"></i>.';

$lang['prospecto_desembolso'] = 'Desembolso COBIS';
$lang['prospecto_desembolso_monto'] = $lang['prospecto_desembolso'] . ' - Monto Bs.';
$lang['prospecto_desembolso_usuario'] = $lang['prospecto_desembolso'] . ' - Usuario';
$lang['prospecto_desembolso_fecha'] = $lang['prospecto_desembolso'] . ' - Fecha';

$lang['jda_eval_Pregunta'] = 'Marcar la evaluación del JDA como <b><span id="jda_eval_valor"></span></b><br /><br />Esta acción no se puede deshacer.';

$lang['TituloDesembCOBIS'] = 'Registrar Desembolso COBIS';
$lang['SubDesembCOBIS'] = 'En este apartado podrá registrar el monto de Desembolso COBIS (sin separador de miles), y si el Lead ' . $lang['sol_proviene'] . ' se mostrará el monto registrado para que pueda seleccionarlo. Al registrar el desembolso en COBIS se terminará el flujo del registro.<br /><br />Si su usuario tiene asignado el perfil "Detalle Cliente", podrá visualizar el detalle del registro seleccionando la opción <i class="fa fa-external-link" aria-hidden="true"></i>.<br /><br />Nota: Si el Lead ' . $lang['sol_proviene'] . ' y la moneda seleccionada fue dólares se mostrarán 2 montos: el registrado y aprobado en la solicitud de crédito y el valor convertido en bolivianos con el tipo de cambio registrado en la configuración general del sistema.';

$lang['DesembCOBIS_Pregunta'] = 'Registrar el ' . $lang['prospecto_desembolso'] . ' con <u>Bs. <span id="DesembCOBIS_valor" style="font-size: 1.2em;"></span></u> y cerrar el Lead terminando el flujo.<br /><br />Esta acción no se puede deshacer.';


// DASHBOARD REPORTES - INICIO


$lang['dashboard_filtros'] = 'APLICAR FILTROS AL REPORTE';

$lang['TituloDashboard'] = 'Dashboard Reportes Gerenciales';
$lang['SubDashboard'] = 'En este apartado podrá ver el Dahsboard de reportes gerenciales de los registros de solicitud y estudio de crédito. <br /><br />Puede seleccionar una etapa/estado del reporte para cargar la tabla detalle con los registros respectivos. IMPORTANTE considerar que, al generar la tabla detalle se obtendrá la información actualizada en tiempo real y los registros listados, al ser dinámicos, pueden haber cambiado de etapa/estado.<br /><br />Puede aplicar uno o múltiples filtros para generar el reporte, para ello seleccione la opción "' . $lang['dashboard_filtros'] . '" y se mostrará un panel con los filtros disponibles. Así mismo puede filtrar los Oficiales de Negocios en base a las Agencias seleccionadas, y las mismas en base al Departamento.' . str_replace('columna', 'información', $lang['sol_monto_aux']);

$lang["TablaNoResultadosDashboard"] = "No se encontraron resultados con los filtros indicados. Por favor intente con otros filtros.";

$lang["infoDashboard1"] = "<i>Cuando una operación es desembolsada o rechazada sale de toda la tubería, por lo tanto, no es considerada en este reporte.</i>";

// -- Fecha de Corte
$lang["dashboard_fecha_corte"] = '2022-09-01'; // <-- Formato Y-m-d
// -- Fecha de Corte

// DASHBOARD REPORTES - FIN

// -- Integración con AD

$lang["conf_ad_activo"] = 'Activar Integración Active Directory';
$lang["conf_ad_activo_ayuda"] = 'Esta configuración establece la forma de autenticación de los usuarios. “Si” se realizará a través de los parámetros configurados para la integración con Active Directory.  Selección “No” la autenticación será gestionada por el sistema. Importante: Al aplicar autenticación por Active Directory ya no será controlado por el sistema las políticas y procedimiento de caducidad, renovación o restablecimiento de las contraseñas de usuarios.';
$lang["conf_ad_host"] = 'Endpoint (Host / IP)';
$lang["conf_ad_host_ayuda"] = 'Indique el Host o dirección IP para la conexión con su Active Directory; puede también utilizar aquí el nombre de dominio. Puede también indicar, junto con el host, el puerto, caso contrario se tomará el por defecto.';
$lang["conf_ad_dominio"] = 'Dominio';
$lang["conf_ad_dominio_ayuda"] = 'Es el nombre de Dominio con el que se concatenará al user en la autenticación, no es requerido colocar la arroba (@). Ej: midominio.com.bo';
$lang["conf_ad_dn"] = 'Cadena de conexión / DistinguishedName';
$lang["conf_ad_dn_ayuda"] = 'En este apartado podrá colocar la cadena de conexión, DistinguishedName así como los CN, OU, DC, etc. Importante: Es requerido que este apartado esté registrado con la sintaxis y valores correctos.';
$lang["conf_ad_test_user_ayuda"] = 'Credenciales de prueba <br /> <i>Sólo para testing, no se guardarán como parametría. Para el usuario sólo el valor sin el dominio. <br /> (Ej.: juan.perez)</i>';
$lang["conf_ad_test_user"] = 'Usuario AD';
$lang["conf_ad_test_pass"] = 'Contraseña AD';

$lang["ad_error_conexion"] = "Ocurrió un error al establecer conexión (AD). Para mayor detalle revise los logs del sistema.";
$lang["ad_error_credenciales"] = "Cuenta de Usuario o Contraseña incorrecto (AD).";
$lang["ad_error_general"] ="No se realizó la autenticación con éxito (AD). Para mayor detalle revise los logs del sistema.";
$lang["ad_activo"] = "Contraseña controlada por Active Directory";
$lang["ad_reestablecer_pass"] = "Operación no realizada, Active Directory habilitado.";
$lang["ad_dn_error_sintax"] = "LDAP_INVALID_DN_SYNTAX";
$lang["ad_no_result"] = "LDAP_NO_RESULTS_RETURNED";
$lang["not_found_user_ini"] = "La cuenta no está registrada, por favor comuníquese con el administrador del sistema.";


// Auditoria AD
// Cabeceras de tabla
$lang["id_table_ad"] = "ID";
$lang["params_table_ad"] = "Parámetros";
$lang["cod_err_table_ad"] = "Codigo de error";
$lang["message_table_ad"] = "Mensaje";
$lang["fec_sol_table_ad"] = "Fecha de registro";
$lang["ip_table_ad"] = "IP";

$lang["text_total_ad"] = "TOTAL CANTIDAD DE REGISTROS OBTENIDOS:";
$lang["text_res_ad"] = "RESUMEN DEL REPORTE";
$lang["text_notfound_ad"] = "No se encontraron registros con los filtros indicados. ";

$lang["title_result_ad"] = "REPORTE REGISTROS AUDITORÍA ACTIVE DIRECTORY";
$lang["subtitle_result_ad"] = "TOTAL CANTIDAD DE REGISTROS OBTENIDOS:";

$lang["title_report_ad"] = "Auditoría Active Directory Reporte";

$lang["head_subtitle_comment"] = "En este apartado podrá generar reportes de los logs de auditoría de la integración con Active Directory. Los logs corresponden a errores de conexión u otras respuestas obtenidas de validación de las credenciales por el Active Directory, sin embargo, no se considera los referidos a credenciales inválidas.<br /> Podrá filtrar por fechas para generar el reporte y exportar los resultados.<br /> <br /> Puede ver el detalle de un registro log específico con la opción<i class='fa fa-eye' aria-hidden='true'></i>.";

$lang["date_range_ad"] = "Rango de Fechas:";

$lang["detail_register_title_ad"] = "Detalle del Registro (Log)";
$lang["detail_register_subtitle_ad"] = "auditoria_params: Parámetros enviados | auditoria_mensaje: Respuesta recibida";

// CONSULTA NUMERO DE CRÉDITO
$lang["conf_credit_nro_uri"] = "URI WS CREDIT";
$lang["conf_credit_nro_uri_ayuda"] = "Registre la URI del Web Service. Puede testear la conexión, previamente debe registrar los parámetros por defecto e indicar un número celular para la prueba. ";
$lang["conf_credit_client_id"] = "ID DEL CLIENTE ";
$lang["conf_credit_type"] = "TIPO ";
$lang["conf_credit_scope"] = "ALCANCE ";
$lang["conf_credit_user"] = "NOMBRE DE USUARIO ";
$lang["conf_credit_password"] = "PASSWORD ";

// -- Integración Envío SMS

$lang["conf_credito_notificar_sms_uri"] = "URI WS SMS";
$lang["conf_credito_notificar_sms_uri_ayuda"] = "Registre la URI del Web Service. Puede testear la conexión, previamente debe registrar los parámetros por defecto e indicar un número celular para la prueba. EL TEST DEL WS-SMS CONSUMIRÁ UN SMS REAL (TIENE COSTO).";

$lang["conf_sms_name_plantilla"] = "Nombre de la Plantilla";
$lang["conf_sms_channelid"] = "Channel ID";
$lang["conf_sms_tiempo_validez"] = "Tiempo Validez del SMS (PIN)";
$lang["conf_sms_tiempo_validez_ayuda"] = "Es el tiempo de validez del PIN enviado en el SMS establecido para el proceso de Onboarding Apertura de Cuenta, donde, al sobrepasar el tiempo, este PIN ya no será válido. Nota: Este tiempo no puede ser mayor al '" . $lang["conf_token_validez"] . "' del Token de Registro.";
$lang["conf_sms_permitido_cantidad"] = "Bloqueo: Máximo de SMS permitidos por número celular";
$lang["conf_sms_permitido_cantidad_ayuda"] = "Este valor corresponde al control de cantidad de envíos SMS permitidos por celular, al superarlos bloqueará el envío. Puede seleccionar 'Sin límite'.";
$lang["conf_sms_permitido_tiempo"] = "Bloqueo: Tiempo de espera al superar la cantidad de SMS permitidos";
$lang["conf_sms_permitido_tiempo_ayuda"] = "Este valor corresponde al tiempo de espera que el número de celular debe cumplir al marcarse como bloqueado cuando se alcanza la cantidad máxima establecida. Este tiempo es independiente al de '" . $lang["conf_sms_tiempo_validez"] . "'.";
$lang["conf_sms_permitido_txt_error"] = "Texto Número celular bloqueado (Máx 280 car.)";

$lang["conf_sms_onb_ambiente"] = "Habilitar Solicitud SMS y Validación PIN";
$lang["conf_sms_onb_ambiente_ayuda"] = "Establezca si en el proceso onboarding, se habilitará la validación del PIN enviado por SMS; Si: Ambiente en producción; No: Ambiente 'development' o pruebas, no será requerido solicitar SMS ni validar el PIN (utilizado para pruebas en ambiente laboratorio).";

$lang["sms_onb_ambiente_devel_sms"] = "Ambiente desarrollo habilitado";
$lang["sms_onb_ambiente_prod_error_noenviado"] = "Error al solicitar código para su celular, por favor vuelva a intentarlo";
$lang["sms_onb_ambiente_prod_enviado"] = "El código ha sido enviado a su celular";
$lang["sms_onb_ambiente_prod_error_pin"] = "El código introducido no es correcto";
$lang["sms_onb_ambiente_prod_pin"] = "Número de celular verificado exitosamente";

// Perfil Tipo Categoría "B"
$lang["ejecutivo_perfil_tipo_rubro_error"] = "Debe seleccionar mínimamente un rubro para poder realizar esta acción.";
$lang["ejecutivo_perfil_tipo_accion"] = "Gestionar Agencia Asociada";
$lang["ejecutivo_perfil_tipo_generico"] = "Genérico";
$lang["ejecutivo_perfil_tipo_catb"] = "Categoría \"B\"";
$lang["ejecutivo_perfil_tipo_nocatb"] = "No cuenta con el Perfil <i>" . $lang["ejecutivo_perfil_tipo_catb"] . "</i> para realizar la acción <i>" . $lang["ejecutivo_perfil_tipo_accion"] . "</i>";
$lang["ejecutivo_perfil_tipo_asignado"] = "El Oficial de Negocios seleccionado ya tiene asignado el Perfil Tipo <i>'%s'</i>. Por favor actualice la bandeja.";
$lang["ejecutivo_perfil_tipo_errorAgencia"] = "Debe seleccionar una Agencia válida.";
$lang["ejecutivo_perfil_tipo_errorNoAgencia"] = "La Agencia seleccionada no es parte de las que supervisa, por favor seleccione una Agencia válida.";

// Módulo de Cobranzas

$lang["norm_prefijo"] = "REG_";
$lang["norm_num_proceso"] = "Número Operación";
$lang["ruta_cobranzas"] = "logica_application/document_n_files/cobranzas/";

$lang["norm_no_checkout"] = "Acción no habilitada para el Perfil App %s.";

// -- Formulario

$lang['norm_id'] = 'Cliente/Caso - Código';
$lang['norm_primer_nombre'] = 'Primer Nombre';
$lang['norm_segundo_nombre'] = 'Segundo Nombre (opcional)';
$lang['norm_primer_apellido'] = 'Primer Apellido';
$lang['norm_segundo_apellido'] = 'Segundo Apellido (opcional)';
$lang['norm_correo'] = 'Correo Electrónico (opcional)';
$lang['norm_cel'] = 'Celular (opcional)';

$lang['norm_actividad'] = 'Actividad (opcional)';
$lang['norm_rel_cred'] = 'Relación con el crédito';
$lang['norm_rel_cred_otro'] = 'Otro';
$lang['norm_finalizacion'] = 'Finalización de la Gestión';
$lang['norm_finalizacion_error'] = 'Debe seleccionar al menos un criterio para marcar la ' . $lang['norm_finalizacion'] . '.';
$lang['norm_finalizacion_error_form'] = 'Debe completar el registro de información del caso para poder marcar la ' . $lang['norm_finalizacion'] . ' (mínimamente los Datos Personales).';
$lang['norm_observado_app'] = 'Registro Observado';
$lang['norm_observado_app_ayuda'] = '<i class="fa fa-info-circle" aria-hidden="true"></i> Para ver el detalle de la observación puede utilizar la opción “Historial Observaciones” (debe contar con el perfil respectivo).';

$lang['cv_panel_ayuda'] = 'En la sección de abajo se listan las visitas registradas para el presente caso. Puede actualizar sus datos en la opción de la primera columna; en la última columna podrá ver si marcó el Check de la Visita con su respectiva fecha. ';
$lang['cv_resultado'] = 'Resultado de la visita';
$lang['cv_fecha'] = 'Fecha Registro Visita';
$lang['cv_fecha_compromiso'] = 'Fecha de compromiso de pago';
$lang['cv_fecha_compromiso_abrev'] = 'Fec. Comp. Pago';
$lang['cv_fecha_compromiso_ayuda'] = 'Las fechas permitidas son la actual o posterior (a futuro).';
$lang['cv_observaciones'] = 'Observaciones';
$lang['cv_checkin'] = 'Check Visita';
$lang['cv_checkin_fecha'] = 'Fecha Check Visita';
$lang['cv_fecha_ultima'] = 'Última Visita Registrada';

$lang["TablaNoResultados_seccion"] = '<div style="text-align: center; font-weight: bold; font-size: 1.1em;"> <span style="font-size: 1.6em;"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span> <br /> No se registró información para esta sección </div>';
$lang["TablaOpciones_norm_forzarcheckin"] = '<i class="fa fa-lightbulb-o"></i> Forzar<br />Check Visita';
$lang['norm_mje_forzarcheckin'] = 'Al continuar con esta opción, se procederá a forzar el "Check Visita" de la última visita registrada del caso seleccionado con la geolocalización de la oficina central. Se recomienda que esta acción se realice desde el App Móvil a fin de que se registre la ubicación del GPS del dispositivo. ¿Confirma que quiere continuar?';
$lang['norm_error_consolidado'] = 'El registro se encuentra Consolidado, no puede realizar la acción.';
$lang['norm_error_nofinalizacion'] = 'Debe marcar la ' . $lang['norm_finalizacion'] . ' para poder consolidar el registro.';
$lang['norm_error_sincheckin'] = 'Tiene pendiente de marcar CHECK VISITA, por favor revise y complete las visitas registradas antes de consolidar.';
$lang['cv_error_nuevo_nocheckin'] = 'No marcó el <i>Check Visita</i> para la última visita registrada. Por favor debe realizar previamente esta acción en la pestaña "RESUMEN", opción "CHECK VISITA" para poder registrar una nueva visita.';
$lang['cv_error_noregistros'] = 'No se tiene visitas registradas. Por favor debe registrar al menos una visita para utilizar esta acción.';
$lang['cv_error_sicheckin'] = 'Ya marcó el Check Visita para la última visita registrada.';

$lang['rd_tipo'] = 'Tipo Dirección';
$lang['rd_dir_departamento'] = 'Departamento';
$lang['rd_dir_provincia'] = 'Provincia';
$lang['rd_dir_localidad_ciudad'] = 'Ciudad';
$lang['rd_cod_barrio'] = 'Barrio/ Zona';
$lang['rd_direccion'] = 'Avenida /Calle/ Pasaje';
$lang['rd_edificio'] = 'Edificio/ Condominio/Urbanización';
$lang['rd_numero'] = 'Nro';
$lang['rd_referencia_texto'] = 'Referencia literal';
$lang['rd_referencia'] = 'Selección Referencia';
$lang['rd_geolocalizacion'] = 'Geolocalización';
$lang['rd_croquis'] = 'Imagen Croquis';
$lang['rd_trabajo_lugar'] = 'El lugar es';
$lang['rd_trabajo_lugar_otro'] = 'Otro';
$lang['rd_trabajo_realiza'] = 'Lo realiza en';
$lang['rd_trabajo_realiza_otro'] = 'Otro';
$lang['rd_trabajo_actividad_pertenece'] = 'Actividad del';
$lang['rd_dom_tipo'] = 'La casa donde vive es';
$lang['rd_dom_tipo_otro'] = 'Otro';

$lang['norm_agencia_ayuda'] = 'Se muestra el listado de agencias asignadas a su usuario para supervisar. Si precisa que se le asigne otras agencias puede solicitarlo al administrador.';

$lang['rd_sin_direcciones'] = 'Debe registrar al menos una dirección para poder continuar.';
$lang['norm_texto_final'] = 'Importante: Verifique que tenga todas las visitas registradas y haber marcado la ' . $lang['norm_finalizacion'] . '.';

// -- Supervisión Normalizador/Cobrador

$lang['NormSupervision_filtros'] = 'MOSTRAR/OCULTAR MÁS FILTROS';

$lang["norm_reporte_fecha_error"] = "Por favor debe seleccionar un rango de fechas (Registro del Caso).";
$lang["norm_reporte_filtro_error"] = "Por favor debe seleccionar al menos una Agencia o %s.";
$lang["norm_reporte_vencido_error"] = '<span style="color: #db1b1c; font-weight: bold;"> <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Vencido</span>';

$lang["norm_col_codigo"] = "Código";
$lang["norm_col_regional"] = "Regional";
$lang["norm_col_agencia"] = "Agencia de la operación";
$lang["norm_col_cliente"] = "Cliente";
$lang["norm_col_dias_mora"] = "Días mora";
$lang["norm_col_num_proceso"] = $lang['registro_num_proceso'];
$lang["norm_col_estado"] = "Estado de la operación";
$lang["norm_col_agencia_registrada"] = "Agencia registrada";
$lang["norm_col_agente"] = "Nombre Normalizador/ Cobrador";
$lang["norm_col_persona_contactada"] = "Persona contactada";
$lang["norm_col_rel_cred"] = $lang['norm_rel_cred'];
$lang["norm_col_estado_solicitud"] = "Estado de la solicitud";
$lang["norm_col_resultado_visita"] = "Resultado de la visita";
$lang["norm_col_finalizacion"] = $lang['norm_finalizacion'];
$lang["norm_col_fec_registro"] = "Fecha de registro";
$lang["norm_col_hora_registro"] = "Hora de registro";
$lang["norm_col_fecha_visita"] = "Fecha ultima visita";
$lang["norm_col_hora_visita"] = "Hora ultima visita";
$lang["norm_col_coor_ultima_visita"] = "Coordenada última visita";
$lang["norm_col_fecha_consolidado"] = "Fecha consolidado";
$lang["norm_col_hora_consolidado"] = "Hora consolidado";
$lang["norm_col_fec_comp_pago"] = "Fecha Compromiso de Pago";
$lang["norm_col_observaciones"] = "Observaciones";
$lang["norm_col_fec_visita"] = "Fecha Visita";
$lang["norm_sin_registrar"] = "Sin registrar";

$lang['NormSupervisionTitulo'] = 'Supervisión de %s';
$lang["NormSupervisionSubtitulo"] = 'En este apartado podrá generar reportes para efectuar la revisión de los casos Consolidados y/o No Consolidados por los %s de la(s) agencia(s) que supervisa. Puede aplicar uno o múltiples filtros para generar el reporte, así mismo puede filtrar los %s en base a las Agencias seleccionadas, y las mismas en base al Departamento.<br /><br />Nota: Las columnas "' . $lang["norm_col_fec_visita"] . '" y "' . $lang["norm_col_fec_comp_pago"] . '" son referidas a la <u>última visita registrada</u> por el %s.<br /><br />Las opciones disponibles, al contar con los perfiles respectivos, son: <br /> - Ver el detalle de los registros que tengan enlaces habilitados haciendo clic sobre los mismos. <br /> - Visualizar los documentos digitalizados. <br /> - Modificar ' . $lang['registro_num_proceso'] . ' (cuando el registro esté consolidado). <br /> - Modificar Agencia Asociada (cuando el registro esté consolidado).';

$lang["TablaOpciones_norm_nro_operacion"] = '<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Número Operación';
$lang["TablaOpciones_norm_agencia"] = '<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Agencia Asociada';

$lang["campo_NormModCampo_ayuda"] = 'Al proceder con el guardado se actualizará el valor y se cerrará la ventana actual, marcando la celda respectiva como <i>"Modificado"</i> con el nuevo valor.<br />Al exportar el reporte, se obtendrán los datos actualizados con los filtros que hayan sido establecidos, por lo que es recomendado que, una vez realizado el cambio genere nuevamente el reporte para visualizar la información actualizada.';
$lang["campo_NormModCampo_actual"] = '<i class="fa fa-check-square-o" aria-hidden="true"></i> Valor actual: ';
$lang["campo_NormModCampo_nuevo"] = '<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Establezca el nuevo valor: ';

$lang["PreguntaNormModCampoContinuar"] = "Se procederá a actualizar el dato seleccionado con el valor establecido ¿ESTÁ SEGURO DE PROCEDER? ";

$lang['NormSeguimientoTitulo'] = 'Tracking de %s';
$lang["NormSeguimientoSubtitulo"] = 'En este apartado podrá realizar el seguimiento de las visitas registradas por los %s respecto al "Check Visita" efectuado en las mismas. Considere que, para un mismo cliente/caso el %s puede registrar múltiples visitas con sus respectivos <i>Checks</i>, por lo que, el reporte mostrará la relación asociativa entre los clientes/casos y sus visitas, siendo las columnas específicas del seguimiento las que se encuentran entre "' . $lang["norm_col_fec_visita"] . '" y "' . $lang['cv_checkin'] . '".<br /><br />En las opciones de formato de reporte podrá seleccionar entre listado (tabla) y mapa. Para generar el reporte es mandatorio seleccionar los filtros de rango de fechas de registro del caso y al menos una Agencia o %s, asimismo, puede aplicar múltiples filtros para generar el reporte.<br /><br />Tomar en consideración que, al generar el reporte en formato Mapa, dependiendo de la cantidad de marcadores (Check Visita) resultantes, el rendimiento de carga dependerá de los recursos de su equipo o dispositivo. Procure generar reportes con la mayor precisión posible seleccionando los filtros disponibles.';

$lang["norm_marcador_mapa"] = "Visita del Normalizador/Cobrador";

?>