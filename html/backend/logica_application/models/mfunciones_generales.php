<?php
class Mfunciones_generales extends CI_Model {
    //private $Consulta_soapx;
    private $cache_dias_laborales=null;

    function __construct() {
        parent::__construct();
        $CI = & get_instance();
        $CI->load->library('soap/Consulta_soap');
        $this->consulta_soap = $CI->consulta_soap;
    }
    
    
    // AUDITORIA
    
	function AuditoriaAcceso($tipo_acceso) {
            
            $this->load->model('mfunciones_logica');

            if(isset($_SESSION["session_informacion"]["login"]))
            {
                $auditoria_usuario = $_SESSION["session_informacion"]["login"];
            }
            else
            {
                $auditoria_usuario = "no_autenticado";
            }

            $auditoria_fecha = date('Y-m-d H:i:s');
            $auditoria_accion = $tipo_acceso;

            $auditoria_ip = $this->input->ip_address();

            $this->mfunciones_logica->InsertarAuditoriaAcceso($auditoria_usuario, $auditoria_accion, $auditoria_fecha, $auditoria_ip);
	}
	
	function Auditoria($accion_detalle, $tabla) {
		
		$this->load->model('mfunciones_logica');

		$auditoria_usuario = $_SESSION["session_informacion"]["login"];
		$auditoria_fecha = date('Y-m-d H:i:s');
		$auditoria_tabla = $tabla;
		$auditoria_accion = $accion_detalle;

		$auditoria_ip = $this->input->ip_address();

		//$this->mfunciones_logica->InsertarAuditoria($auditoria_usuario, $auditoria_fecha, $auditoria_tabla, $auditoria_accion, $auditoria_ip);
	}

    // FUNCIONALES PROPIOS DEL SISTEMA
    
    function ObtieneMeses($valor)
    {
        $resultado = new stdClass();
        $resultado->anos = 0;
        $resultado->meses = 0;

        $i = $valor;

        if ($i >= 12) {
            $resultado->anos = ($i - $i % 12) / 12;
        }

        if ($i % 12 > 0) {
            $resultado->meses = $i % 12;
        }

        return $resultado;
    }

    function IngresoBalanceLead($codigo_prospecto, $categoria_principal, $segmento='ingreso')
    {
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        $resultado = new stdClass();
        
        $resultado->ingreso_total = 0;
        
        $resultado->total_activo = 0;
        $resultado->total_activo_corriente = 0;
        $resultado->total_activo_no_corriente = 0;
        
        $resultado->total_pasivo = 0;
        $resultado->total_pasivo_corriente = 0;
        $resultado->total_patrimonio = 0;
        
        $resultado->total_otros_pasivos = 0;
        
        $resultado->metodo_inventario = 0;
        $resultado->total_inventario = 0;
        
        // Si no es la actividad principal, se retorna 0
        //if($categoria_principal == 0){return $resultado; }
        
        // Se obtien también el total de inventarios
            
        $arrMetodo = $this->mfunciones_logica->select_margenes($codigo_prospecto);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrMetodo);

        $resultado->metodo_inventario = $arrMetodo[0]['inventario_registro'];

        if($arrMetodo[0]['inventario_registro'] == 1)
        {
            $resultado->total_inventario = $arrMetodo[0]['inventario_registro_total'];
        }
        else
        {
            $arrMargen = $this->mfunciones_logica->ObtenerListaProductos($codigo_prospecto, -1, 0);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrMargen);

            $inventario_total_costo = 0;

            if(isset($arrMargen[0]))
            {
                foreach ($arrMargen as $key => $value_inventario) 
                {
                    $inventario_total_costo += $value_inventario['producto_compra_cantidad'] * $value_inventario['producto_compra_precio'];
                }
            }

            $resultado->total_inventario = $inventario_total_costo;
        }
        
        if($segmento == 'pasivos')
        {
            $arrResultado = $this->mfunciones_logica->ObtenerListaPasivos($codigo_prospecto, -1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            $suma_cuota = 0;
            
            if (isset($arrResultado[0])) 
            {
                foreach ($arrResultado as $key => $value) 
                {
                    $suma_cuota += ($value['pasivo_rfto']==0 ? $value["pasivo_cuota_mensual"] : 0);
                }
            }
            
            $resultado->total_otros_pasivos = $suma_cuota;
            
            return $resultado;
        }
        
        $arrResultado = $this->mfunciones_logica->select_otros_ingresos($codigo_prospecto);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            // "Efectivo en caja y bancos" + "Cuentas de ahorro DPF,S" + "Cuentas por cobrar" + "Inventario" + "Otros activos corrientes"
            $total_activo_corriente = 
                                    $arrResultado[0]['extra_efectivo_caja'] + 
                                    $arrResultado[0]['extra_ahorro_dpf'] + 
                                    $arrResultado[0]['extra_cuentas_cobrar'] + 
                                    //$arrResultado[0]['extra_inventario'] + 
                                    $resultado->total_inventario + 
                                    $arrResultado[0]['extra_otros_activos_corrientes'];
            
            // Activo fijo + Otros activos no corrientes
            
            $total_activo_no_corriente = 
                                    $arrResultado[0]['extra_activo_fijo'] + 
                                    $arrResultado[0]['extra_otros_activos_nocorrientes'];
            
            // "TOTAL ACTIVO CORRIENTE" + "TOTAL ACTIVO NO CORRIENTE"
            
            $total_activo = $total_activo_corriente + $total_activo_no_corriente;
            
            // "Cuentas por pagar a proveedores"+"Prestamos con entidades financieras corto plazo"+"Otras cuentas por pagar corto plazo"
            
            $total_pasivo_corriente = 
                                    $arrResultado[0]['extra_cuentas_pagar_proveedores'] + 
                                    $arrResultado[0]['extra_prestamos_financieras_corto'] + 
                                    $arrResultado[0]['extra_cuentas_pagar_corto'];
            
            // "TOTAL PASIVO CORRIENTE"+" Prestamos con entidades financieras largo plazo"+"Otras cuentas por pagar largo plazo"
            
            $total_pasivo = 
                                    $total_pasivo_corriente +
                                    $arrResultado[0]['extra_prestamos_financieras_largo'] + 
                                    $arrResultado[0]['extra_cuentas_pagar_largo'];
            
            $total_patrimonio = $total_activo - $total_pasivo;
            
            $resultado->total_activo = $total_activo;
            $resultado->total_activo_corriente = $total_activo_corriente;
            $resultado->total_activo_no_corriente = $total_activo_no_corriente;
            $resultado->total_pasivo = $total_pasivo;
            $resultado->total_pasivo_corriente = $total_pasivo_corriente;
            $resultado->total_patrimonio = $total_patrimonio;
            
            if($segmento == 'ingreso')
            {
                $aux = $this->CalculoLead($codigo_prospecto, 'ingreso_ponderado');
                $resultado->ingreso_total = $aux->utilidad_operativa;
                $resultado->suma_otros_ingresos = $aux->suma_otros_ingresos;
            }
        }
        
        return $resultado;
    }
    
    function IngresoBalanceSecundarias($codigo_prospecto, $categoria_principal)
    {
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        $resultado = new stdClass();
        
        $resultado->ingreso_actividades_secundarias = 0;
        $resultado->total_activo_secundarias = 0;
        $resultado->total_pasivo_secundarias = 0;
        
        // Si no es la actividad principal, se retorna 0
        if($categoria_principal == 0){return $resultado; }
        
        // 1. Primero identificar si el Lead es el Titular o el Familiar
        
        $arrConsulta = $this->mfunciones_logica->GetProspectoDepende($codigo_prospecto);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConsulta);
        
        if(isset($arrConsulta[0])) 
        {
            $codigo_prospecto = $arrConsulta[0]['general_depende'];
        }
        
        // 2. Se obtiene el listado de registros del Lead que sean Secundarias (No la principal)
        
        $arrSecundarias = $this->mfunciones_logica->select_info_secundarias_ordenado($codigo_prospecto, 1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrSecundarias);
        
        if(!isset($arrSecundarias[0])) 
        {
            // Si no hay resultados, se devuelve 0
            return $resultado;
        }
        else
        {
            $ingreso_actividades_secundarias = 0;
            $total_activo_secundarias = 0;
            $total_pasivo_secundarias = 0;
            
            foreach ($arrSecundarias as $key => $value)
            {
                $aux_total_secundarias = $this->IngresoBalanceLead($value['prospecto_id'], 1);
                
                $ingreso_actividades_secundarias += $aux_total_secundarias->ingreso_total;
                $total_activo_secundarias += $aux_total_secundarias->total_activo;
                $total_pasivo_secundarias += $aux_total_secundarias->total_pasivo;
            }
            
            $resultado->ingreso_actividades_secundarias = $ingreso_actividades_secundarias;
            $resultado->total_activo_secundarias = $total_activo_secundarias;
            $resultado->total_pasivo_secundarias = $total_pasivo_secundarias;
        }
        
        return $resultado;
        
    }
    
    function GeneraInformeLead($codigo_prospecto, $contenido_completo='completo')
    {
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        // 1. Datos del Lead
        $arrProspecto = $this->mfunciones_logica->ObtenerInfoProspecto($codigo_prospecto);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrProspecto);

        if(!isset($arrProspecto[0])) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        // 2. Datos de las dependencias
        $arrFamiliar = $this->mfunciones_logica->select_info_dependencia_ordenado($codigo_prospecto);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrFamiliar);
        
        // ESTA ES LA FUNCION ENTRE LAS FUNCIONES
        
        $data['arrProspecto'] = $arrProspecto;
        $data['arrFamiliar'] = $arrFamiliar;
        
        $data['contenido_completo'] = $contenido_completo;
        
        //$data['fecha_actual'] = $this->getFechaLiteral(date('Y-m-d'));
        //$data['fecha_actual_corta'] = $this->getFormatoFechaD_M_Y(date('Y-m-d'));
        // Req IM-23
        $data['fecha_actual'] = $this->getFechaLiteral($arrProspecto[0]['prospecto_fecha_asignacion']);
        
        $data['fecha_actual_corta'] = $this->getFormatoFechaD_M_Y($arrProspecto[0]['prospecto_fecha_asignacion']);
        
        $vista_final = $this->load->view('reportes/view_informe_plain', $data, true);
        
        return $vista_final;
    }
        
    function GeneraVersionLead($codigo_prospecto, $accion_usuario, $accion_fecha)
    {
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        // Se obtiene el HTML Final
        $version_contenido = $this->GeneraInformeLead($codigo_prospecto);
        
        // Se obtinene el Hash de seguridad
        $version_hash = hash('sha256', $version_contenido);
        
        $this->mfunciones_logica->crear_version_lead(
                            $codigo_prospecto,
                            $version_contenido,
                            $version_hash,
                            $accion_usuario,
                            $accion_fecha
                            );
    }
    
    function setEnlaceRegistro($estructura_id, $codigo_ejecutivo, $tipo_registro)
    {
        $this->load->model('mfunciones_logica');
        $this->load->library('encrypt');
        
        $identificador_url = '5ot4d_arutp4c';
        // Se obtinene el Hash de seguridad de la Llave
        $key_key = hash('sha256', $this->encrypt->encode(date('Y-m-d H:i:s')));
        $url_armado = $identificador_url . '=' . $key_key;
        
        $this->mfunciones_logica->crear_key_registro(
                            $key_key, 
                            $estructura_id, 
                            $codigo_ejecutivo, 
                            $tipo_registro
                            );
        
        return $url_armado;
    }
    
    function getEnlaceRegistro($key_key)
    {
        $this->load->model('mfunciones_logica');
        
        $arrParametros = $this->mfunciones_logica->obtener_key_registro($key_key);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrParametros);
        
        return $arrParametros;
    }
        
    function ValorCostoProducto($codigo_producto, $opcion, $venta_costo, $producto_compra_precio, $producto_unidad_venta_unidad_compra, $producto_costo_medida_cantidad)
    {
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        $producto_venta_costo = 0;
        
        switch ($opcion)
        {   // Inventario
            case 1: 
                
                if($producto_unidad_venta_unidad_compra == 0)
                {
                    $producto_venta_costo = 0; 
                }
                else
                {
                    $producto_venta_costo = $producto_compra_precio/$producto_unidad_venta_unidad_compra; 
                }
                
                break;
            
            // Personalizado
            case 3: $producto_venta_costo = $venta_costo; break;
            
            // Costeo
            case 2:
                
                $arrResultado = $this->mfunciones_logica->ObtenerListaDetalleProductos($codigo_producto, -1);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                
                if (isset($arrResultado[0])) 
                {
                    $suma_costo = 0;
                    
                    foreach ($arrResultado as $key => $value)
                    {
                        if($value["detalle_costo_medida_convertir"] == 1)
                        {
                            // Seleccionó la opción de convertir la unidad de medida
                            if($value["detalle_costo_unidad_medida_cantidad"] == 0)
                            {
                                $suma_costo += 0;
                            }
                            else
                            {
                                $suma_costo += ($value["detalle_costo_medida_precio"] / $value["detalle_costo_unidad_medida_cantidad"]) * $value["detalle_cantidad"];
                            }
                            
                        }
                        else
                        {
                            // Se calcula directamente
                            $suma_costo += $value["detalle_cantidad"] * $value["detalle_costo_unitario"];
                        }
                    }
                    
                    if($producto_costo_medida_cantidad == 0)
                    {
                        $producto_venta_costo = 0;
                    }
                    else
                    {
                        $producto_venta_costo = $suma_costo / $producto_costo_medida_cantidad;
                    }
                }
                
                break;

            default: $producto_venta_costo = 0; break;
        }
        
        return $producto_venta_costo;
    }
        
    // Obtener estadistica de Tiempo de registro de Rubro
    function TiempoRegistroRubro($codigo_prospecto)
    {
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        $arrHito = $this->mfunciones_logica->ObtenerDatosHito(1, $codigo_prospecto);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrHito);

        if (isset($arrHito[0]))
        {
            $aux_fecha_ini = $arrHito[0]['hito_fecha_ini'];

            if($arrHito[0]['hito_finalizo'] == 0)
            {
                $aux_fecha_fin = date("Y-m-d H:i:s");
            }
            else
            {
                $aux_fecha_fin = $arrHito[0]['hito_fecha_fin'];
            }

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
        }

        $resultado = new stdClass();
        
        $resultado->aux_fecha_ini = $this->getFormatoFechaD_M_Y_H_M($aux_fecha_ini);
        $resultado->aux_fecha_fin = $this->getFormatoFechaD_M_Y_H_M($aux_fecha_fin);
        $resultado->calculo_tiempo = number_format($calculo_tiempo, 0, ',', '.') . ' Horas';
        $resultado->calculo_estado = $calculo_estado;
        $resultado->tiempo_etapa = number_format($tiempo_etapa, 0, ',', '.') . ' Horas';

        return $resultado;
    }
        
    // Guarda el Hito REGISTRAR SEGUIMIENTO (ESTA MISMA ETAPA DEBE REGISTRARSE AL PRINCIPIO)
    function GuardarHitoFormulario($codigo_prospecto, $codigo_rubro, $accion_usuario, $accion_fecha)
    {
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        $this->mfunciones_logica->update_final_formulario(
                            $accion_usuario,
                            $accion_fecha,
                            $codigo_prospecto
                            );
        
        // Detalle del Prospecto
        $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($codigo_prospecto);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
        
        $this->SeguimientoHitoProspecto($codigo_prospecto, $arrResultado3[0]['prospecto_etapa'], 1, $accion_usuario, $accion_fecha, 0);
            $nombre_rubro = $this->GetValorCatalogo($codigo_rubro, 'nombre_rubro');
        $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, 1, 9, 'Completó Registro del Rubro ' . $nombre_rubro, $accion_usuario, $accion_fecha);
    }

    // Cálculos del Lead
    function CalculoFecuencia($venta_cantidad, $venta_precio, $frecuencia)
    {
        $venta = $venta_cantidad*$venta_precio;
        
        switch ($frecuencia) {
            case "1": $resultado = $venta * 30; break;
            case "7": $resultado = $venta * 4; break;
            case "15": $resultado = $venta * 2; break;
            case "30": $resultado = $venta * 1; break;
            case "60": $resultado = $venta / 2; break;
            case "90": $resultado = $venta / 3; break;
            case "120": $resultado = $venta / 4; break;
            case "150": $resultado = $venta / 5; break;
            case "180": $resultado = $venta / 6; break;
            case "360": $resultado = $venta / 12; break;

            default: $resultado = 0; break;
        }
        
        return $resultado;
    }
    
    // Cálculos del Lead
    function CalculoLead($codigo_prospecto, $segmento)
    {
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');

        $resultado = new stdClass();
        $resultado->error = false;
        
        // Obtener el total de Otros Ingresos que será sumado al Ingreso de las actividades Secundarias. Esto para todos los rubros
        
        $suma_otros_ingresos = 0;
        $arrOtros_Ingresos = $this->mfunciones_logica->ObtenerListaOtrosIngresos($codigo_prospecto, -1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrOtros_Ingresos);

        if(isset($arrOtros_Ingresos[0]))
        {
            foreach ($arrOtros_Ingresos as $key => $value_otros_ingresos) 
            {
                $suma_otros_ingresos += $value_otros_ingresos["otros_monto"];
            }
        }
        
        $resultado->suma_otros_ingresos = $suma_otros_ingresos;
        
        // Obtener el total de FRECUENCIA
        
        $suma_frecuencia = 0;
        
        $arrFrecuencia = $this->mfunciones_logica->select_frecuencia_venta($codigo_prospecto);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrFrecuencia);
        
        // Se pregunta el Rubro para independizar el cálculo de Transporte
        
        if($arrFrecuencia[0]['camp_id'] == 4)
        {
            // RUBRO TRANSPORTE
            
            $arrCliente = $this->mfunciones_logica->select_volumen_ingresos($codigo_prospecto);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrCliente);
            
            $resultado->ingreso_mensual_promedio = 0;
            $resultado->utilidad_operativa = 0;
            
            $resultado->dias_considerados = $arrCliente[0]['transporte_preg_trabaja_semana']*$arrCliente[0]['transporte_cliente_frecuencia'];
            $resultado->cliente_frecuencia = $arrCliente[0]['transporte_cliente_frecuencia'];
            $resultado->cliente_frecuencia_texto = $this->GetValorCatalogo($arrCliente[0]['transporte_cliente_frecuencia'], 'transporte_cliente_frecuencia');
            $resultado->cliente_dia_total = 0;
            $resultado->cliente_dia_total_total = 0;
            
            $resultado->transporte_cliente_linea1_promedio = 0;
            $resultado->transporte_cliente_linea2_promedio = 0;
            $resultado->transporte_cliente_linea3_promedio = 0;
            $resultado->transporte_cliente_linea4_promedio = 0;
            $resultado->transporte_cliente_linea5_promedio = 0;
            $resultado->transporte_cliente_linea6_promedio = 0;
            $resultado->transporte_cliente_linea7_promedio = 0;
            
            $resultado->transporte_cliente_linea_suma_minimo = 0;
            $resultado->transporte_cliente_linea_suma_maximo = 0;
            $resultado->transporte_cliente_linea_suma_promedio = 0;
            
            $resultado->transporte_cliente_linea_total = 0;
            
            $resultado->transporte_cliente_vueta_buena_promedio = 0;
            $resultado->transporte_cliente_vueta_regular_promedio = 0;
            $resultado->transporte_cliente_vueta_mala_promedio = 0;
            
            $resultado->transporte_cliente_vueta_suma_importe = 0;
            $resultado->transporte_cliente_vueta_suma_vuelta = 0;
            $resultado->transporte_cliente_vueta_suma_promedio = 0;
            
            $resultado->transporte_cliente_vueta_total = 0;
            
            // DÍAS SUMA
            
            $cliente_dia_suma = 0;
            $cliente_dia_contador = 0;
            
            $cliente_dia_suma += ($arrCliente[0]['transporte_cliente_dia_lunes']!=0 ? $arrCliente[0]['transporte_cliente_dia_lunes'] : 0);
            $cliente_dia_suma += ($arrCliente[0]['transporte_cliente_dia_martes']!=0 ? $arrCliente[0]['transporte_cliente_dia_martes'] : 0);
            $cliente_dia_suma += ($arrCliente[0]['transporte_cliente_dia_miercoles']!=0 ? $arrCliente[0]['transporte_cliente_dia_miercoles'] : 0);
            $cliente_dia_suma += ($arrCliente[0]['transporte_cliente_dia_jueves']!=0 ? $arrCliente[0]['transporte_cliente_dia_jueves'] : 0);
            $cliente_dia_suma += ($arrCliente[0]['transporte_cliente_dia_viernes']!=0 ? $arrCliente[0]['transporte_cliente_dia_viernes'] : 0);
            $cliente_dia_suma += ($arrCliente[0]['transporte_cliente_dia_sabado']!=0 ? $arrCliente[0]['transporte_cliente_dia_sabado'] : 0);
            $cliente_dia_suma += ($arrCliente[0]['transporte_cliente_dia_domingo']!=0 ? $arrCliente[0]['transporte_cliente_dia_domingo'] : 0);
            
            ($arrCliente[0]['transporte_cliente_dia_lunes']!=0 ? $cliente_dia_contador++ : 0);
            ($arrCliente[0]['transporte_cliente_dia_martes']!=0 ? $cliente_dia_contador++ : 0);
            ($arrCliente[0]['transporte_cliente_dia_miercoles']!=0 ? $cliente_dia_contador++ : 0);
            ($arrCliente[0]['transporte_cliente_dia_jueves']!=0 ? $cliente_dia_contador++ : 0);
            ($arrCliente[0]['transporte_cliente_dia_viernes']!=0 ? $cliente_dia_contador++ : 0);
            ($arrCliente[0]['transporte_cliente_dia_sabado']!=0 ? $cliente_dia_contador++ : 0);
            ($arrCliente[0]['transporte_cliente_dia_domingo']!=0 ? $cliente_dia_contador++ : 0);
            
            $cliente_dia_total = ($cliente_dia_contador!=0 ? ($cliente_dia_suma/$cliente_dia_contador)*$resultado->dias_considerados : 0);
            
            $resultado->cliente_dia_total = $cliente_dia_suma;
            $resultado->cliente_dia_total_total = $cliente_dia_total;
            
            // LÍNEA SUMA
            
            $cliente_linea_suma_minimo = 0;
            $cliente_linea_suma_maximo = 0;
            $cliente_linea_suma_promedio = 0;
            $cliente_linea_contador = 0;
            
            $cliente_linea_suma_minimo += $arrCliente[0]['transporte_cliente_linea1_min'];
            $cliente_linea_suma_minimo += $arrCliente[0]['transporte_cliente_linea2_min'];
            $cliente_linea_suma_minimo += $arrCliente[0]['transporte_cliente_linea3_min'];
            $cliente_linea_suma_minimo += $arrCliente[0]['transporte_cliente_linea4_min'];
            $cliente_linea_suma_minimo += $arrCliente[0]['transporte_cliente_linea5_min'];
            $cliente_linea_suma_minimo += $arrCliente[0]['transporte_cliente_linea6_min'];
            $cliente_linea_suma_minimo += $arrCliente[0]['transporte_cliente_linea7_min'];
            
            $cliente_linea_suma_maximo += $arrCliente[0]['transporte_cliente_linea1_max'];
            $cliente_linea_suma_maximo += $arrCliente[0]['transporte_cliente_linea2_max'];
            $cliente_linea_suma_maximo += $arrCliente[0]['transporte_cliente_linea3_max'];
            $cliente_linea_suma_maximo += $arrCliente[0]['transporte_cliente_linea4_max'];
            $cliente_linea_suma_maximo += $arrCliente[0]['transporte_cliente_linea5_max'];
            $cliente_linea_suma_maximo += $arrCliente[0]['transporte_cliente_linea6_max'];
            $cliente_linea_suma_maximo += $arrCliente[0]['transporte_cliente_linea7_max'];
            
            $cliente_linea_suma_promedio += $resultado->transporte_cliente_linea1_promedio = ($arrCliente[0]['transporte_cliente_linea1_min']+$arrCliente[0]['transporte_cliente_linea1_max'])/2;
            $cliente_linea_suma_promedio += $resultado->transporte_cliente_linea2_promedio = ($arrCliente[0]['transporte_cliente_linea2_min']+$arrCliente[0]['transporte_cliente_linea2_max'])/2;
            $cliente_linea_suma_promedio += $resultado->transporte_cliente_linea3_promedio = ($arrCliente[0]['transporte_cliente_linea3_min']+$arrCliente[0]['transporte_cliente_linea3_max'])/2;
            $cliente_linea_suma_promedio += $resultado->transporte_cliente_linea4_promedio = ($arrCliente[0]['transporte_cliente_linea4_min']+$arrCliente[0]['transporte_cliente_linea4_max'])/2;
            $cliente_linea_suma_promedio += $resultado->transporte_cliente_linea5_promedio = ($arrCliente[0]['transporte_cliente_linea5_min']+$arrCliente[0]['transporte_cliente_linea5_max'])/2;
            $cliente_linea_suma_promedio += $resultado->transporte_cliente_linea6_promedio = ($arrCliente[0]['transporte_cliente_linea6_min']+$arrCliente[0]['transporte_cliente_linea6_max'])/2;
            $cliente_linea_suma_promedio += $resultado->transporte_cliente_linea7_promedio = ($arrCliente[0]['transporte_cliente_linea7_min']+$arrCliente[0]['transporte_cliente_linea7_max'])/2;
            
            ($resultado->transporte_cliente_linea1_promedio!=0 ? $cliente_linea_contador++ : 0);
            ($resultado->transporte_cliente_linea2_promedio!=0 ? $cliente_linea_contador++ : 0);
            ($resultado->transporte_cliente_linea3_promedio!=0 ? $cliente_linea_contador++ : 0);
            ($resultado->transporte_cliente_linea4_promedio!=0 ? $cliente_linea_contador++ : 0);
            ($resultado->transporte_cliente_linea5_promedio!=0 ? $cliente_linea_contador++ : 0);
            ($resultado->transporte_cliente_linea6_promedio!=0 ? $cliente_linea_contador++ : 0);
            ($resultado->transporte_cliente_linea7_promedio!=0 ? $cliente_linea_contador++ : 0);
            
            $resultado->transporte_cliente_linea_suma_minimo = $cliente_linea_suma_minimo;
            $resultado->transporte_cliente_linea_suma_maximo = $cliente_linea_suma_maximo;
            $resultado->transporte_cliente_linea_suma_promedio = $cliente_linea_suma_promedio;
            
            $resultado->transporte_cliente_linea_total = ($cliente_linea_contador!=0 ? ($cliente_linea_suma_promedio/$cliente_linea_contador)*$resultado->dias_considerados : 0);
            
            // VUELTAS/CARRERAS SUMA
            
            $resultado->transporte_cliente_vueta_buena_promedio = $arrCliente[0]['transporte_cliente_vueta_buena_monto']*$arrCliente[0]['transporte_cliente_vueta_buena_numero'];
            $resultado->transporte_cliente_vueta_regular_promedio = $arrCliente[0]['transporte_cliente_vueta_regular_monto']*$arrCliente[0]['transporte_cliente_vueta_regular_numero'];
            $resultado->transporte_cliente_vueta_mala_promedio = $arrCliente[0]['transporte_cliente_vueta_mala_monto']*$arrCliente[0]['transporte_cliente_vueta_mala_numero'];
            
            $resultado->transporte_cliente_vueta_suma_importe = $arrCliente[0]['transporte_cliente_vueta_buena_monto'] + $arrCliente[0]['transporte_cliente_vueta_regular_monto'] + $arrCliente[0]['transporte_cliente_vueta_mala_monto'];
            $resultado->transporte_cliente_vueta_suma_vuelta = $arrCliente[0]['transporte_cliente_vueta_buena_numero'] + $arrCliente[0]['transporte_cliente_vueta_regular_numero'] + $arrCliente[0]['transporte_cliente_vueta_mala_numero'];
            $resultado->transporte_cliente_vueta_suma_promedio = $resultado->transporte_cliente_vueta_buena_promedio + $resultado->transporte_cliente_vueta_regular_promedio + $resultado->transporte_cliente_vueta_mala_promedio;
            
            $resultado->transporte_cliente_vueta_total = $resultado->dias_considerados * $resultado->transporte_cliente_vueta_suma_promedio;
            
            // CÁLCULO CAPACIDAD INSTALADA
            
            $resultado->rotacion = ($arrCliente[0]['transporte_capacidad_sin_rotacion']!=0 ? $arrCliente[0]['transporte_capacidad_con_rotacion']/$arrCliente[0]['transporte_capacidad_sin_rotacion'] : 0);
            
            $resultado->precio_promedio = (($arrCliente[0]['transporte_capacidad_tramo_largo_pasajero']+$arrCliente[0]['transporte_capacidad_tramo_corto_pasajero'])==0 ? 0 : ($arrCliente[0]['transporte_capacidad_tramo_largo_precio']*$arrCliente[0]['transporte_capacidad_tramo_largo_pasajero'])/($arrCliente[0]['transporte_capacidad_tramo_largo_pasajero']+$arrCliente[0]['transporte_capacidad_tramo_corto_pasajero'])+($arrCliente[0]['transporte_capacidad_tramo_corto_precio']*$arrCliente[0]['transporte_capacidad_tramo_corto_pasajero'])/($arrCliente[0]['transporte_capacidad_tramo_largo_pasajero']+$arrCliente[0]['transporte_capacidad_tramo_corto_pasajero']));
            
            $resultado->cliente_capacidad_buena_pasajeros = 0;
            $resultado->cliente_capacidad_buena_ingreso = 0;
            $resultado->cliente_capacidad_buena_total = 0;
            
            $resultado->cliente_capacidad_regular_pasajeros = 0;
            $resultado->cliente_capacidad_regular_ingreso = 0;
            $resultado->cliente_capacidad_regular_total = 0;
            
            $resultado->cliente_capacidad_mala_pasajeros = 0;
            $resultado->cliente_capacidad_mala_ingreso = 0;
            $resultado->cliente_capacidad_mala_total = 0;
            
            $resultado->cliente_capacidad_ingreso_total = 0;
            $resultado->cliente_capacidad_total = 0;
            $resultado->cliente_capacidad_ingreso_mensual = 0;
            
            $resultado->cliente_capacidad_buena_pasajeros = $arrCliente[0]['transporte_capacidad_con_rotacion']*2*($arrCliente[0]['transporte_vuelta_buena_ocupacion']/100);
            $resultado->cliente_capacidad_regular_pasajeros = $arrCliente[0]['transporte_capacidad_con_rotacion']*2*($arrCliente[0]['transporte_vuelta_regular_ocupacion']/100);
            $resultado->cliente_capacidad_mala_pasajeros = $arrCliente[0]['transporte_capacidad_con_rotacion']*2*($arrCliente[0]['transporte_vuelta_mala_ocupacion']/100);
            
            $resultado->cliente_capacidad_buena_ingreso = $arrCliente[0]['total_ingreso_bueno'];
            $resultado->cliente_capacidad_regular_ingreso = $arrCliente[0]['total_ingreso_regular'];
            $resultado->cliente_capacidad_mala_ingreso = $arrCliente[0]['total_ingreso_malo'];
            
            $resultado->cliente_capacidad_buena_total = $arrCliente[0]['total_ingreso_bueno']*$arrCliente[0]['transporte_vuelta_buena_veces'];
            $resultado->cliente_capacidad_regular_total = $arrCliente[0]['total_ingreso_regular']*$arrCliente[0]['transporte_vuelta_regular_veces'];
            $resultado->cliente_capacidad_mala_total = $arrCliente[0]['total_ingreso_malo']*$arrCliente[0]['transporte_vuelta_mala_veces'];
            
            $resultado->cliente_capacidad_ingreso_total = ($arrCliente[0]['total_ingreso_bueno']+$arrCliente[0]['total_ingreso_regular']+$arrCliente[0]['total_ingreso_malo'])/3;
            $resultado->cliente_capacidad_total = (($arrCliente[0]['transporte_vuelta_buena_veces']+$arrCliente[0]['transporte_vuelta_regular_veces']+$arrCliente[0]['transporte_vuelta_mala_veces'])==0 ? 0 : ($resultado->cliente_capacidad_buena_total+$resultado->cliente_capacidad_regular_total+$resultado->cliente_capacidad_mala_total)/($arrCliente[0]['transporte_vuelta_buena_veces']+$arrCliente[0]['transporte_vuelta_regular_veces']+$arrCliente[0]['transporte_vuelta_mala_veces']));
            $resultado->cliente_capacidad_ingreso_mensual = $resultado->dias_considerados*$resultado->cliente_capacidad_total*($arrCliente[0]['transporte_vuelta_buena_veces']+$arrCliente[0]['transporte_vuelta_regular_veces']+$arrCliente[0]['transporte_vuelta_mala_veces']);
            
            // HORAS y VUELTAS TOTALES
            
            $resultado->horas_netas = 0;
            $resultado->calculo_vueltas = 0;
            
            $aux_horas_netas = ($this->time_to_decimal($arrCliente[0]['transporte_preg_jornada_concluye']) - $this->time_to_decimal($arrCliente[0]['transporte_preg_jornada_inicia']) - $this->time_to_decimal($arrCliente[0]['transporte_preg_tiempo_no_trabaja']));
            
            $resultado->horas_netas = date('H:i', mktime(0, ($aux_horas_netas==0 ? 0 : $aux_horas_netas)));
            
            if($arrCliente[0]['transporte_tipo_prestatario'] != 2)
            {
                $aux_calculo_vueltas = $this->time_to_decimal($arrCliente[0]['transporte_preg_tiempo_parada']) + $this->time_to_decimal($arrCliente[0]['transporte_preg_tiempo_vuelta']);
                $resultado->calculo_vueltas = number_format(($aux_calculo_vueltas!=0 ? $aux_horas_netas/$aux_calculo_vueltas : 0), 2, ',', '.');
            }
            else
            {
                $resultado->calculo_vueltas = '--';
            }
            
            // CRITERIO SELECCIONADO
            
            $resultado->ingreso_mensual_promedio = 0;
            
            $resultado->criterio_declaracion_cliente = 0;
            $resultado->criterio_capacidad_instalada = 0;
            
            $arrAuxCriterio_declaracion = array();
            $arrAuxCriterio_total = array();
            
            if($resultado->cliente_dia_total_total != 0) { array_push($arrAuxCriterio_declaracion, $resultado->cliente_dia_total_total); }
            
            if($arrCliente[0]['transporte_tipo_prestatario'] != 2)
            {
                $resultado->criterio_capacidad_instalada = $resultado->cliente_capacidad_ingreso_mensual;
                
                if($resultado->transporte_cliente_vueta_total != 0) { array_push($arrAuxCriterio_declaracion, $resultado->transporte_cliente_vueta_total); }
                
                if($resultado->criterio_capacidad_instalada != 0) { array_push($arrAuxCriterio_total, $resultado->criterio_capacidad_instalada); }
                
                if($arrCliente[0]['transporte_tipo_transporte'] < 4)
                {
                    if($resultado->transporte_cliente_linea_total != 0) { array_push($arrAuxCriterio_declaracion, $resultado->transporte_cliente_linea_total); }
                }
            }
            
            $resultado->criterio_declaracion_cliente = (count($arrAuxCriterio_declaracion)==0 ? 0 : min($arrAuxCriterio_declaracion));
            
            if($resultado->criterio_declaracion_cliente != 0) { array_push($arrAuxCriterio_total, $resultado->criterio_declaracion_cliente); }
            
            $resultado->ingreso_mensual_promedio = (count($arrAuxCriterio_total)==0 ? 0 : min($arrAuxCriterio_total));
            
            // COSTOS OPERATIVOS
            
            $resultado->operativos_otro_transporte_total = 0;
            
            $resultado->operativos_cambio_aceite_motor = ($arrCliente[0]['operativos_cambio_aceite_motor_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_cambio_aceite_motor_cantidad'] * $arrCliente[0]['operativos_cambio_aceite_motor_monto'] * (30/$arrCliente[0]['operativos_cambio_aceite_motor_frecuencia']));
            $resultado->operativos_cambio_aceite_caja = ($arrCliente[0]['operativos_cambio_aceite_caja_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_cambio_aceite_caja_cantidad'] * $arrCliente[0]['operativos_cambio_aceite_caja_monto'] * (30/$arrCliente[0]['operativos_cambio_aceite_caja_frecuencia']));
            $resultado->operativos_cambio_llanta_delanteras = ($arrCliente[0]['operativos_cambio_llanta_delanteras_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_cambio_llanta_delanteras_cantidad'] * $arrCliente[0]['operativos_cambio_llanta_delanteras_monto'] * (30/$arrCliente[0]['operativos_cambio_llanta_delanteras_frecuencia']));
            $resultado->operativos_cambio_llanta_traseras = ($arrCliente[0]['operativos_cambio_llanta_traseras_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_cambio_llanta_traseras_cantidad'] * $arrCliente[0]['operativos_cambio_llanta_traseras_monto'] * (30/$arrCliente[0]['operativos_cambio_llanta_traseras_frecuencia']));
            $resultado->operativos_cambio_bateria = ($arrCliente[0]['operativos_cambio_bateria_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_cambio_bateria_cantidad'] * $arrCliente[0]['operativos_cambio_bateria_monto'] * (30/$arrCliente[0]['operativos_cambio_bateria_frecuencia']));
            $resultado->operativos_cambio_balatas = ($arrCliente[0]['operativos_cambio_balatas_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_cambio_balatas_cantidad'] * $arrCliente[0]['operativos_cambio_balatas_monto'] * (30/$arrCliente[0]['operativos_cambio_balatas_frecuencia']));
            $resultado->operativos_revision_electrico = ($arrCliente[0]['operativos_revision_electrico_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_revision_electrico_cantidad'] * $arrCliente[0]['operativos_revision_electrico_monto'] * (30/$arrCliente[0]['operativos_revision_electrico_frecuencia']));
            $resultado->operativos_remachado_embrague = ($arrCliente[0]['operativos_remachado_embrague_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_remachado_embrague_cantidad'] * $arrCliente[0]['operativos_remachado_embrague_monto'] * (30/$arrCliente[0]['operativos_remachado_embrague_frecuencia']));
            $resultado->operativos_rectificacion_motor = ($arrCliente[0]['operativos_rectificacion_motor_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_rectificacion_motor_cantidad'] * $arrCliente[0]['operativos_rectificacion_motor_monto'] * (30/$arrCliente[0]['operativos_rectificacion_motor_frecuencia']));
            $resultado->operativos_cambio_rodamiento = ($arrCliente[0]['operativos_cambio_rodamiento_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_cambio_rodamiento_cantidad'] * $arrCliente[0]['operativos_cambio_rodamiento_monto'] * (30/$arrCliente[0]['operativos_cambio_rodamiento_frecuencia']));
            $resultado->operativos_reparaciones_menores = ($arrCliente[0]['operativos_reparaciones_menores_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_reparaciones_menores_cantidad'] * $arrCliente[0]['operativos_reparaciones_menores_monto'] * (30/$arrCliente[0]['operativos_reparaciones_menores_frecuencia']));
            $resultado->operativos_imprevistos = ($arrCliente[0]['operativos_imprevistos_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_imprevistos_cantidad'] * $arrCliente[0]['operativos_imprevistos_monto'] * (30/$arrCliente[0]['operativos_imprevistos_frecuencia']));
            $resultado->operativos_impuesto_propiedad = ($arrCliente[0]['operativos_impuesto_propiedad_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_impuesto_propiedad_cantidad'] * $arrCliente[0]['operativos_impuesto_propiedad_monto'] * (30/$arrCliente[0]['operativos_impuesto_propiedad_frecuencia']));
            $resultado->operativos_soat = ($arrCliente[0]['operativos_soat_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_soat_cantidad'] * $arrCliente[0]['operativos_soat_monto'] * (30/$arrCliente[0]['operativos_soat_frecuencia']));
            $resultado->operativos_roseta_inspeccion = ($arrCliente[0]['operativos_roseta_inspeccion_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_roseta_inspeccion_cantidad'] * $arrCliente[0]['operativos_roseta_inspeccion_monto'] * (30/$arrCliente[0]['operativos_roseta_inspeccion_frecuencia']));
            $resultado->operativos_otro_transporte_libre1 = ($arrCliente[0]['operativos_otro_transporte_libre1_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_otro_transporte_libre1_cantidad'] * $arrCliente[0]['operativos_otro_transporte_libre1_monto'] * (30/$arrCliente[0]['operativos_otro_transporte_libre1_frecuencia']));
            $resultado->operativos_otro_transporte_libre2 = ($arrCliente[0]['operativos_otro_transporte_libre2_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_otro_transporte_libre2_cantidad'] * $arrCliente[0]['operativos_otro_transporte_libre2_monto'] * (30/$arrCliente[0]['operativos_otro_transporte_libre2_frecuencia']));
            $resultado->operativos_otro_transporte_libre3 = ($arrCliente[0]['operativos_otro_transporte_libre3_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_otro_transporte_libre3_cantidad'] * $arrCliente[0]['operativos_otro_transporte_libre3_monto'] * (30/$arrCliente[0]['operativos_otro_transporte_libre3_frecuencia']));
            $resultado->operativos_otro_transporte_libre4 = ($arrCliente[0]['operativos_otro_transporte_libre4_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_otro_transporte_libre4_cantidad'] * $arrCliente[0]['operativos_otro_transporte_libre4_monto'] * (30/$arrCliente[0]['operativos_otro_transporte_libre4_frecuencia']));
            $resultado->operativos_otro_transporte_libre5 = ($arrCliente[0]['operativos_otro_transporte_libre5_frecuencia']==0 ? 0 : $arrCliente[0]['operativos_otro_transporte_libre5_cantidad'] * $arrCliente[0]['operativos_otro_transporte_libre5_monto'] * (30/$arrCliente[0]['operativos_otro_transporte_libre5_frecuencia']));

            $resultado->operativos_otro_transporte_total =  $resultado->operativos_cambio_aceite_motor + 
                                                            $resultado->operativos_cambio_aceite_caja + 
                                                            $resultado->operativos_cambio_llanta_delanteras + 
                                                            $resultado->operativos_cambio_llanta_traseras + 
                                                            $resultado->operativos_cambio_bateria + 
                                                            $resultado->operativos_cambio_balatas + 
                                                            $resultado->operativos_revision_electrico + 
                                                            $resultado->operativos_remachado_embrague + 
                                                            $resultado->operativos_rectificacion_motor + 
                                                            $resultado->operativos_cambio_rodamiento + 
                                                            $resultado->operativos_reparaciones_menores + 
                                                            $resultado->operativos_imprevistos + 
                                                            $resultado->operativos_impuesto_propiedad + 
                                                            $resultado->operativos_soat + 
                                                            $resultado->operativos_roseta_inspeccion + 
                                                            $resultado->operativos_otro_transporte_libre1 + 
                                                            $resultado->operativos_otro_transporte_libre2 + 
                                                            $resultado->operativos_otro_transporte_libre3 + 
                                                            $resultado->operativos_otro_transporte_libre4 + 
                                                            $resultado->operativos_otro_transporte_libre5;
            
            // INDICADORES
            
            // Sumatoria Costo Ventas
            $resultado->margen_utilidad_bruta = 0;
            $resultado->transporte_costo_ventas = 0;
            
            $arrCostoVentas = $this->mfunciones_logica->ObtenerListaMargen($codigo_prospecto, -1, -1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrCostoVentas);
            
            $suma_total_ingreso = 0;
            $suma_total_egreso = 0;
            
            if(isset($arrCostoVentas[0]))
            {
                foreach ($arrCostoVentas as $key => $value_costos) 
                {
                    if($value_costos['margen_tipo'] == 0)
                    {
                        $suma_total_egreso += $value_costos["margen_cantidad"]*$value_costos["margen_monto_unitario"];
                    }
                    else
                    {
                        $suma_total_ingreso += $value_costos["margen_cantidad"]*$value_costos["margen_pasajeros"]*$value_costos["margen_monto_unitario"];
                    }
                }
                
                $resultado->margen_utilidad_bruta = ($suma_total_ingreso==0 ? 0 : (($suma_total_ingreso - $suma_total_egreso)/$suma_total_ingreso));
                $resultado->transporte_costo_ventas = $resultado->ingreso_mensual_promedio*(1-$resultado->margen_utilidad_bruta);
            }
            
            $resultado->transporte_utilidad_bruta = $resultado->ingreso_mensual_promedio - $resultado->transporte_costo_ventas;
            $resultado->transporte_utilidad_operativa = $resultado->transporte_utilidad_bruta - $resultado->operativos_otro_transporte_total;
            
            // Sumatoria de otros ingresos
                $ingresoBalanceSecundarias = $this->IngresoBalanceSecundarias($codigo_prospecto, $arrCliente[0]['prospecto_principal']);
            
            $resultado->transporte_otros_ingresos = $suma_otros_ingresos + $ingresoBalanceSecundarias->ingreso_actividades_secundarias;
            
            // Gastos Familiares
            
            $resultado->transporte_gastos_familiares =  $arrCliente[0]['familiar_alimentacion_monto'] + 
                                                        $arrCliente[0]['familiar_energia_monto'] + 
                                                        $arrCliente[0]['familiar_agua_monto'] + 
                                                        $arrCliente[0]['familiar_gas_monto'] + 
                                                        $arrCliente[0]['familiar_telefono_monto'] + 
                                                        $arrCliente[0]['familiar_celular_monto'] + 
                                                        $arrCliente[0]['familiar_internet_monto'] + 
                                                        $arrCliente[0]['familiar_tv_monto'] + 
                                                        $arrCliente[0]['familiar_impuestos_monto'] + 
                                                        $arrCliente[0]['familiar_alquileres_monto'] + 
                                                        $arrCliente[0]['familiar_educacion_monto'] + 
                                                        $arrCliente[0]['familiar_transporte_monto'] + 
                                                        $arrCliente[0]['familiar_salud_monto'] + 
                                                        $arrCliente[0]['familiar_empleada_monto'] + 
                                                        $arrCliente[0]['familiar_diversion_monto'] + 
                                                        $arrCliente[0]['familiar_vestimenta_monto'] + 
                                                        $arrCliente[0]['familiar_otros_monto'] + 
                                                        $arrCliente[0]['familiar_libre1_monto'] + 
                                                        $arrCliente[0]['familiar_libre2_monto'] + 
                                                        $arrCliente[0]['familiar_libre3_monto'] + 
                                                        $arrCliente[0]['familiar_libre4_monto'] + 
                                                        $arrCliente[0]['familiar_libre5_monto'];
            
            
            $resultado->transporte_utilidad_neta = $resultado->transporte_utilidad_operativa+$resultado->transporte_otros_ingresos-$resultado->transporte_gastos_familiares;
            
            $resultado->utilidad_operativa = $resultado->transporte_utilidad_operativa;
            
                $transporte_otras_deudas = $this->IngresoBalanceLead($codigo_prospecto, '', $segmento='pasivos');
            $resultado->transporte_otras_deudas = $transporte_otras_deudas->total_otros_pasivos;
            
            $resultado->transporte_saldo_disponible = $resultado->transporte_utilidad_neta - $resultado->transporte_otras_deudas;
            
            $resultado->transporte_cuota_prestamo = $arrCliente[0]['extra_cuota_prestamo_solicitado'];
            
            $resultado->transporte_margen_ahorro = $resultado->transporte_saldo_disponible - $resultado->transporte_cuota_prestamo;
            
            
            $resultado->costo_ventas = $resultado->transporte_costo_ventas;
            $resultado->utilidad_bruta = $resultado->transporte_utilidad_bruta;
            $resultado->utilidad_operativa = $resultado->transporte_utilidad_operativa;
            $resultado->utilidad_neta = $resultado->transporte_utilidad_neta;
            $resultado->saldo_disponible = $resultado->transporte_saldo_disponible;
            $resultado->margen_ahorro = $resultado->transporte_margen_ahorro;
            
            
            $resultado->total_inventario = $transporte_otras_deudas->total_inventario;
            $resultado->metodo_inventario = $transporte_otras_deudas->metodo_inventario;
        }
        else
        {
            // OTROS RUBROS
            
            // Obtener el total de PRINCIPALES PRODUCTOS

            $suma_productos = 0;
            $suma_costo_total = 0;

            $arrProductos = $this->mfunciones_logica->ObtenerListaProductos($codigo_prospecto, -1, 1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrProductos);

            if(isset($arrProductos[0]))
            {
                foreach ($arrProductos as $key => $value) 
                {
                    $suma_productos += $this->CalculoFecuencia($value["producto_venta_cantidad"], $value["producto_venta_precio"], $value["producto_frecuencia"]);
                    
                    // --Adecuación
                        $costo_unitario = $this->ValorCostoProducto($value["producto_id"], $value["producto_opcion"], $value["producto_venta_costo"], $value["producto_compra_precio"], $value["producto_unidad_venta_unidad_compra"], $value["producto_costo_medida_cantidad"]); 
                    $suma_costo_total += $this->CalculoFecuencia($value["producto_venta_cantidad"], $costo_unitario, $value["producto_frecuencia"]);
                    // --Adecuación
                }
            }
            
            // --Adecuación
            $mub_total = ($suma_productos!=0 ? (1-($suma_costo_total/$suma_productos)) : 0);
            
            if($segmento == 'extra_mub')
            {
                return $mub_total;
            }
            
            // --Adecuación

                switch ($arrFrecuencia[0]['margen_utilidad_productos']) {
                    case 1: $margen_utilidad = 40; break;
                    case 2: $margen_utilidad = 50; break;
                    case 3: $margen_utilidad = 60; break;
                    case 4: $margen_utilidad = 70; break;
                    case 5: $margen_utilidad = 80; break;
                    case 6: $margen_utilidad = 100; break;

                    default: $margen_utilidad = 0; break;
                }

            $resultado->criterio_principales_productos = ($margen_utilidad==0 ? 0 : $suma_productos/($margen_utilidad/100));
            
            if(isset($arrFrecuencia[0]))
            {
                switch ($arrFrecuencia[0]['frec_seleccion'])
                {
                    // Diario
                    case "1":

                        $suma_frecuencia += $arrFrecuencia[0]['frec_dia_lunes'];
                        $suma_frecuencia += $arrFrecuencia[0]['frec_dia_martes'];
                        $suma_frecuencia += $arrFrecuencia[0]['frec_dia_miercoles'];
                        $suma_frecuencia += $arrFrecuencia[0]['frec_dia_jueves'];
                        $suma_frecuencia += $arrFrecuencia[0]['frec_dia_viernes'];
                        $suma_frecuencia += $arrFrecuencia[0]['frec_dia_sabado'];
                        $suma_frecuencia += $arrFrecuencia[0]['frec_dia_domingo'];

                        // 1. Se establece los montos BRM

                        switch ($arrFrecuencia[0]['frec_dia_semana_sel_brm'])
                        {
                            case 1:

                                $frecuencia_dia_monto_bueno = $suma_frecuencia;
                                $frecuencia_dia_monto_regular = $arrFrecuencia[0]['frec_dia_semana_monto2'];
                                $frecuencia_dia_monto_malo = $arrFrecuencia[0]['frec_dia_semana_monto3'];

                                break;

                            case 2:

                                $frecuencia_dia_monto_bueno = $arrFrecuencia[0]['frec_dia_semana_monto2'];
                                $frecuencia_dia_monto_regular = $suma_frecuencia;
                                $frecuencia_dia_monto_malo = $arrFrecuencia[0]['frec_dia_semana_monto3'];

                                break;

                            case 3:

                                $frecuencia_dia_monto_bueno = $arrFrecuencia[0]['frec_dia_semana_monto2'];
                                $frecuencia_dia_monto_regular = $arrFrecuencia[0]['frec_dia_semana_monto3'];
                                $frecuencia_dia_monto_malo = $suma_frecuencia;

                                break;

                            default:

                                $frecuencia_dia_monto_bueno = $arrFrecuencia[0]['frec_dia_semana_monto2'];
                                $frecuencia_dia_monto_regular = $arrFrecuencia[0]['frec_dia_semana_monto3'];
                                $frecuencia_dia_monto_malo = $suma_frecuencia;

                                break;
                        }

                        // 2. Se calcula las semanas por el monto

                        switch ($arrFrecuencia[0]['frec_dia_eval_semana1_brm']) {
                            case 1:
                                $frecuencia_primera_monto = $frecuencia_dia_monto_bueno;
                                break;

                            case 2:
                                $frecuencia_primera_monto = $frecuencia_dia_monto_regular;
                                break;

                            case 3:
                                $frecuencia_primera_monto = $frecuencia_dia_monto_malo;
                                break;

                            default:

                                $frecuencia_primera_monto = 0;

                                break;
                        }

                        switch ($arrFrecuencia[0]['frec_dia_eval_semana2_brm']) {
                            case 1:
                                $frecuencia_segunda_monto = $frecuencia_dia_monto_bueno;
                                break;

                            case 2:
                                $frecuencia_segunda_monto = $frecuencia_dia_monto_regular;
                                break;

                            case 3:
                                $frecuencia_segunda_monto = $frecuencia_dia_monto_malo;
                                break;

                            default:

                                $frecuencia_segunda_monto = 0;

                                break;
                        }

                        switch ($arrFrecuencia[0]['frec_dia_eval_semana3_brm']) {
                            case 1:
                                $frecuencia_tercera_monto = $frecuencia_dia_monto_bueno;
                                break;

                            case 2:
                                $frecuencia_tercera_monto = $frecuencia_dia_monto_regular;
                                break;

                            case 3:
                                $frecuencia_tercera_monto = $frecuencia_dia_monto_malo;
                                break;

                            default:
                                $frecuencia_tercera_monto = 0;
                                break;
                        }

                        switch ($arrFrecuencia[0]['frec_dia_eval_semana4_brm']) {
                            case 1:
                                $frecuencia_cuarta_monto = $frecuencia_dia_monto_bueno;
                                break;

                            case 2:
                                $frecuencia_cuarta_monto = $frecuencia_dia_monto_regular;
                                break;

                            case 3:
                                $frecuencia_cuarta_monto = $frecuencia_dia_monto_malo;
                                break;

                            default:
                                $frecuencia_cuarta_monto = 0;
                                break;
                        }

                        $suma_frecuencia = $frecuencia_primera_monto + $frecuencia_segunda_monto + $frecuencia_tercera_monto + $frecuencia_cuarta_monto;

                        break;

                    // Semanal
                    case "2":

                        $suma_frecuencia += $arrFrecuencia[0]['frec_sem_semana1_monto'];
                        $suma_frecuencia += $arrFrecuencia[0]['frec_sem_semana2_monto'];
                        $suma_frecuencia += $arrFrecuencia[0]['frec_sem_semana3_monto'];
                        $suma_frecuencia += $arrFrecuencia[0]['frec_sem_semana4_monto'];

                        break;

                    // Mensual
                    case "3":

                        $suma_frecuencia += $arrFrecuencia[0]['frec_mes_mes1_monto'];
                        $suma_frecuencia += $arrFrecuencia[0]['frec_mes_mes2_monto'];
                        $suma_frecuencia += $arrFrecuencia[0]['frec_mes_mes3_monto'];

                        $suma_frecuencia = $suma_frecuencia/3;

                        break;

                    default:
                        break;
                }
            }

            $resultado->criterio_frecuencia_ingreso = $suma_frecuencia;

            // Obtener el total de MATERIA PRIMA

            $suma_materia = 0;

            // Se pregunta el Rubro del Lead, para Comercio (3) se debe obtener la sumatoria de Proveedores, para los demás es sumatoria de Materia Prima
            if($arrFrecuencia[0]['camp_id'] != 3)
            {
                $arrMateria = $this->mfunciones_logica->ObtenerListaMateria($codigo_prospecto, -1);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrMateria);

                if(isset($arrMateria[0]))
                {
                    foreach ($arrMateria as $key => $value) 
                    {
                        $suma_materia += $this->CalculoFecuencia(($value['materia_unidad_compra_cantidad'] * $value['materia_producto_medida_cantidad'] * $value['materia_precio_unitario']), ($value['materia_unidad_proceso']!=0 ? $value['materia_unidad_uso_cantidad'] / $value['materia_unidad_proceso'] : 0), $value['materia_frecuencia']);
                    }
                }
            }
            else
            {
                $arrProveedor = $this->mfunciones_logica->ObtenerListaProovedor($codigo_prospecto, -1);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrProveedor);
            
                $promedio_mensual_total = 0;
                
                if(isset($arrProveedor[0]))
                {
                    foreach ($arrProveedor as $key => $value) 
                    {
                        $promedio_mensual_total += ($value['proveedor_frecuencia_dias']!=0 ? (30/$value['proveedor_frecuencia_dias'])*$value['proveedor_importe'] : 0);
                    }
                    
                    $porcentaje_participacion_proveedores = $this->GetValorCatalogo($arrFrecuencia[0]['porcentaje_participacion_proveedores'], 'combo_margen_valor');
                    
                    $totales_inferidas = ($porcentaje_participacion_proveedores!=0 ? $promedio_mensual_total/($porcentaje_participacion_proveedores/100) : 0);
                    
                    $suma_materia = ((1-$mub_total)!=0 ? $totales_inferidas/(1-$mub_total) : 0);
                }
            }
            
            $resultado->criterio_materia_prima = $suma_materia;

            // Identificar cual es el criterio seleccionado

                switch ($arrFrecuencia[0]['capacidad_criterio']) {
                    case 1: $monto_criterio_seleccionado = $resultado->criterio_frecuencia_ingreso; break;
                    case 2: $monto_criterio_seleccionado = $resultado->criterio_principales_productos; break;
                    case 3: $monto_criterio_seleccionado = $resultado->criterio_materia_prima; break;
                    case 4: $monto_criterio_seleccionado = $arrFrecuencia[0]['capacidad_monto_manual']; break;

                    default: $monto_criterio_seleccionado = 0; break;
                }

                $resultado->monto_criterio_seleccionado = $monto_criterio_seleccionado;


            if($segmento == 'ingreso_ponderado')
            {
                // 1. Se pregunta si no tiene estacionalidad marcada o si es Regular

                if($arrFrecuencia[0]['estacion_sel'] == 0 || $arrFrecuencia[0]['estacion_sel_arb'] == 2)
                {
                    $ingreso_mensual_promedio = $resultado->monto_criterio_seleccionado;
                }
                else
                {
                    // 2. Guardar el ARB de todos los meses
                    $estacion_ene_arb = $arrFrecuencia[0]['estacion_ene_arb'];
                    $estacion_feb_arb = $arrFrecuencia[0]['estacion_feb_arb'];
                    $estacion_mar_arb = $arrFrecuencia[0]['estacion_mar_arb'];
                    $estacion_abr_arb = $arrFrecuencia[0]['estacion_abr_arb'];
                    $estacion_may_arb = $arrFrecuencia[0]['estacion_may_arb'];
                    $estacion_jun_arb = $arrFrecuencia[0]['estacion_jun_arb'];
                    $estacion_jul_arb = $arrFrecuencia[0]['estacion_jul_arb'];
                    $estacion_ago_arb = $arrFrecuencia[0]['estacion_ago_arb'];
                    $estacion_sep_arb = $arrFrecuencia[0]['estacion_sep_arb'];
                    $estacion_oct_arb = $arrFrecuencia[0]['estacion_oct_arb'];
                    $estacion_nov_arb = $arrFrecuencia[0]['estacion_nov_arb'];
                    $estacion_dic_arb = $arrFrecuencia[0]['estacion_dic_arb'];

                    // 3. Se identifica cual es el mes seleccionado para establecer el ARB

                    switch ($arrFrecuencia[0]['estacion_sel_mes'])
                    {
                        case 1: $estacion_ene_arb = $arrFrecuencia[0]['estacion_sel_arb']; break;
                        case 2: $estacion_feb_arb = $arrFrecuencia[0]['estacion_sel_arb']; break;
                        case 3: $estacion_mar_arb = $arrFrecuencia[0]['estacion_sel_arb']; break;
                        case 4: $estacion_abr_arb = $arrFrecuencia[0]['estacion_sel_arb']; break;
                        case 5: $estacion_may_arb = $arrFrecuencia[0]['estacion_sel_arb']; break;
                        case 6: $estacion_jun_arb = $arrFrecuencia[0]['estacion_sel_arb']; break;
                        case 7: $estacion_jul_arb = $arrFrecuencia[0]['estacion_sel_arb']; break;
                        case 8: $estacion_ago_arb = $arrFrecuencia[0]['estacion_sel_arb']; break;
                        case 9: $estacion_sep_arb = $arrFrecuencia[0]['estacion_sel_arb']; break;
                        case 10: $estacion_oct_arb = $arrFrecuencia[0]['estacion_sel_arb']; break;
                        case 11: $estacion_nov_arb = $arrFrecuencia[0]['estacion_sel_arb']; break;
                        case 12: $estacion_dic_arb = $arrFrecuencia[0]['estacion_sel_arb']; break;

                        default: $estacion_ene_arb = $arrFrecuencia[0]['estacion_sel_arb']; break;
                    }

                    // 3. Establecer los montos Alto, Regular y Bajo

                    $mes_bajo_contador = 0;
                    $mes_bajo_suma = 0;

                    $mes_regular_contador = 0;
                    $mes_regular_suma = 0;

                    switch ($arrFrecuencia[0]['estacion_sel_arb']) {
                        case 1:

                            $monto_alto = $resultado->monto_criterio_seleccionado;
                            $monto_regular = $arrFrecuencia[0]['estacion_monto2'];
                            $monto_bajo = $arrFrecuencia[0]['estacion_monto3'];

                            break;

                        case 3:

                            $monto_alto = $arrFrecuencia[0]['estacion_monto2'];
                            $monto_regular = $arrFrecuencia[0]['estacion_monto3'];
                            $monto_bajo = $resultado->monto_criterio_seleccionado;

                            break;

                        default:
                            break;
                    }

                    switch ($arrFrecuencia[0]['estacion_sel_arb']) {

                        case 2:
                            $mes_regular_contador++; $mes_regular_suma+= $monto_regular;
                            break;

                        case 3:
                            $mes_bajo_contador++; $mes_bajo_suma+= $monto_bajo;
                            break;

                        default:
                            break;
                    }

                    // 4. Dependiendo del ARB del mes se marca los contadores y suma (2=Regular     3=Baja)

                    if($arrFrecuencia[0]['estacion_sel_mes']!=1){if($estacion_ene_arb == 2) { $mes_regular_contador++; $mes_regular_suma+= $monto_regular; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=2){if($estacion_feb_arb == 2) { $mes_regular_contador++; $mes_regular_suma+= $monto_regular; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=3){if($estacion_mar_arb == 2) { $mes_regular_contador++; $mes_regular_suma+= $monto_regular; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=4){if($estacion_abr_arb == 2) { $mes_regular_contador++; $mes_regular_suma+= $monto_regular; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=5){if($estacion_may_arb == 2) { $mes_regular_contador++; $mes_regular_suma+= $monto_regular; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=6){if($estacion_jun_arb == 2) { $mes_regular_contador++; $mes_regular_suma+= $monto_regular; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=7){if($estacion_jul_arb == 2) { $mes_regular_contador++; $mes_regular_suma+= $monto_regular; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=8){if($estacion_ago_arb == 2) { $mes_regular_contador++; $mes_regular_suma+= $monto_regular; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=9){if($estacion_sep_arb == 2) { $mes_regular_contador++; $mes_regular_suma+= $monto_regular; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=10){if($estacion_oct_arb == 2) { $mes_regular_contador++; $mes_regular_suma+= $monto_regular; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=11){if($estacion_nov_arb == 2) { $mes_regular_contador++; $mes_regular_suma+= $monto_regular; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=12){if($estacion_dic_arb == 2) { $mes_regular_contador++; $mes_regular_suma+= $monto_regular; } }

                    if($arrFrecuencia[0]['estacion_sel_mes']!=1){if($estacion_ene_arb == 3) { $mes_bajo_contador++; $mes_bajo_suma+= $monto_bajo; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=2){if($estacion_feb_arb == 3) { $mes_bajo_contador++; $mes_bajo_suma+= $monto_bajo; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=3){if($estacion_mar_arb == 3) { $mes_bajo_contador++; $mes_bajo_suma+= $monto_bajo; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=4){if($estacion_abr_arb == 3) { $mes_bajo_contador++; $mes_bajo_suma+= $monto_bajo; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=5){if($estacion_may_arb == 3) { $mes_bajo_contador++; $mes_bajo_suma+= $monto_bajo; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=6){if($estacion_jun_arb == 3) { $mes_bajo_contador++; $mes_bajo_suma+= $monto_bajo; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=7){if($estacion_jul_arb == 3) { $mes_bajo_contador++; $mes_bajo_suma+= $monto_bajo; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=8){if($estacion_ago_arb == 3) { $mes_bajo_contador++; $mes_bajo_suma+= $monto_bajo; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=9){if($estacion_sep_arb == 3) { $mes_bajo_contador++; $mes_bajo_suma+= $monto_bajo; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=10){if($estacion_oct_arb == 3) { $mes_bajo_contador++; $mes_bajo_suma+= $monto_bajo; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=11){if($estacion_nov_arb == 3) { $mes_bajo_contador++; $mes_bajo_suma+= $monto_bajo; } }
                    if($arrFrecuencia[0]['estacion_sel_mes']!=12){if($estacion_dic_arb == 3) { $mes_bajo_contador++; $mes_bajo_suma+= $monto_bajo; } }

                    // 5. Se calcula el ingreso mensual promedio

                    $ingreso_mensual_promedio = ($mes_bajo_contador+$mes_regular_contador!=0 ? ($mes_bajo_suma + $mes_regular_suma) / ($mes_bajo_contador + $mes_regular_contador) : 0);

                    $resultado->mes_bajo_contador = $mes_bajo_contador;
                    $resultado->mes_bajo_suma = $mes_bajo_suma;
                    $resultado->mes_regular_contador = $mes_regular_contador;
                    $resultado->mes_regular_suma = $mes_regular_suma;
                }

                $resultado->ingreso_mensual_promedio = $ingreso_mensual_promedio;
                
                // --Adecuación
                $costo_venta = (1-$mub_total)*$ingreso_mensual_promedio;
                $utilidad_bruta = $ingreso_mensual_promedio - $costo_venta;
                
                $gastos_operativos =    $arrFrecuencia[0]['operativos_alq_energia_monto'] + 
                                        $arrFrecuencia[0]['operativos_alq_agua_monto'] + 
                                        $arrFrecuencia[0]['operativos_alq_internet_monto'] + 
                                        $arrFrecuencia[0]['operativos_alq_combustible_monto'] + 
                                        $arrFrecuencia[0]['operativos_alq_libre1_monto'] + 
                                        $arrFrecuencia[0]['operativos_alq_libre2_monto'] + 
                                        $arrFrecuencia[0]['operativos_sal_aguinaldos_monto'] + 
                                        $arrFrecuencia[0]['operativos_sal_libre1_monto'] + 
                                        $arrFrecuencia[0]['operativos_sal_libre2_monto'] + 
                                        $arrFrecuencia[0]['operativos_sal_libre3_monto'] + 
                                        $arrFrecuencia[0]['operativos_sal_libre4_monto'] + 
                                        $arrFrecuencia[0]['operativos_otro_transporte_monto'] + 
                                        $arrFrecuencia[0]['operativos_otro_licencias_monto'] + 
                                        $arrFrecuencia[0]['operativos_otro_alimentacion_monto'] + 
                                        $arrFrecuencia[0]['operativos_otro_mant_vehiculo_monto'] + 
                                        $arrFrecuencia[0]['operativos_otro_mant_maquina_monto'] + 
                                        $arrFrecuencia[0]['operativos_otro_imprevistos_monto'] + 
                                        $arrFrecuencia[0]['operativos_otro_otros_monto'] + 
                                        $arrFrecuencia[0]['operativos_otro_libre1_monto'] + 
                                        $arrFrecuencia[0]['operativos_otro_libre2_monto'] + 
                                        $arrFrecuencia[0]['operativos_otro_libre3_monto'] + 
                                        $arrFrecuencia[0]['operativos_otro_libre4_monto'] + 
                                        $arrFrecuencia[0]['operativos_otro_libre5_monto'];
                
                $utilidad_operativa = $utilidad_bruta - $gastos_operativos;
                
                $resultado->utilidad_operativa = $utilidad_operativa;
                // --Adecuación
                
                $resultado->costo_ventas = $costo_venta;
                $resultado->utilidad_bruta = $utilidad_bruta;
                
                $gastos_familiares =    $arrFrecuencia[0]['familiar_alimentacion_monto'] + 
                                        $arrFrecuencia[0]['familiar_energia_monto'] + 
                                        $arrFrecuencia[0]['familiar_agua_monto'] + 
                                        $arrFrecuencia[0]['familiar_gas_monto'] + 
                                        $arrFrecuencia[0]['familiar_telefono_monto'] + 
                                        $arrFrecuencia[0]['familiar_celular_monto'] + 
                                        $arrFrecuencia[0]['familiar_internet_monto'] + 
                                        $arrFrecuencia[0]['familiar_tv_monto'] + 
                                        $arrFrecuencia[0]['familiar_impuestos_monto'] + 
                                        $arrFrecuencia[0]['familiar_alquileres_monto'] + 
                                        $arrFrecuencia[0]['familiar_educacion_monto'] + 
                                        $arrFrecuencia[0]['familiar_transporte_monto'] + 
                                        $arrFrecuencia[0]['familiar_salud_monto'] + 
                                        $arrFrecuencia[0]['familiar_empleada_monto'] + 
                                        $arrFrecuencia[0]['familiar_diversion_monto'] + 
                                        $arrFrecuencia[0]['familiar_vestimenta_monto'] + 
                                        $arrFrecuencia[0]['familiar_otros_monto'] + 
                                        $arrFrecuencia[0]['familiar_libre1_monto'] + 
                                        $arrFrecuencia[0]['familiar_libre2_monto'] + 
                                        $arrFrecuencia[0]['familiar_libre3_monto'] + 
                                        $arrFrecuencia[0]['familiar_libre4_monto'] + 
                                        $arrFrecuencia[0]['familiar_libre5_monto'];
                
                $ingresoBalanceSecundarias = $this->IngresoBalanceSecundarias($arrFrecuencia[0]['prospecto_id'], $arrFrecuencia[0]['prospecto_principal']);
                $ingreso_actividades_secundarias = $ingresoBalanceSecundarias->ingreso_actividades_secundarias + $resultado->suma_otros_ingresos;
                $resultado->utilidad_neta = $utilidad_operativa + $ingreso_actividades_secundarias - $gastos_familiares;
                
                    $aux_pasivos = $this->IngresoBalanceLead($arrFrecuencia[0]["prospecto_id"], '', $segmento='pasivos');
                $resultado->saldo_disponible = ($arrFrecuencia[0]['extra_personal_ocupado']>0 ? $resultado->utilidad_neta - $aux_pasivos->total_otros_pasivos : 0);
                
                $resultado->total_inventario = $aux_pasivos->total_inventario;
                $resultado->metodo_inventario = $aux_pasivos->metodo_inventario;
                
                $resultado->margen_ahorro = ($arrFrecuencia[0]['extra_personal_ocupado']>0 ? $resultado->saldo_disponible - $arrFrecuencia[0]['extra_amortizacion_credito'] - $arrFrecuencia[0]['extra_cuota_maxima_credito'] : 0);
                
            }
        }
            
        return $resultado;
    }
        
    // Contenido JS de los formualarios
    function JSformularios($filtro='todo')
    {
        $contenido_js = '
            Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
            Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", "../Pasos/Guardar",
                    "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS");
                    
            $(document).ready(function() {
                $("input[type=\'number\']").on("click", function () {
                    if($(this).val() == 0)
                    {
                        $(this).select();
                    }
                });
            });';
                
        if($filtro == 'todo')
        {
            $contenido_js .= '

                $(document).ready(function() {
                    $("select").togglebutton();
                    $("div.modal-backdrop").remove();
                });';
        }

        $contenido_js .= '

            function ElementoSubmit(home_ant_sig="0")
            {
                $("#home_ant_sig").val(home_ant_sig);

                if(home_ant_sig=="ant" || home_ant_sig=="sig" || home_ant_sig=="final")
                {
                    EnviarSubmit();
                }
                else
                {
                    if($("#vista_actual").val()=="view_final" && home_ant_sig!="home")
                    { 
                        $("#pregunta_confirmacion_final").modal();
                    }
                    else
                    {
                        $("#pregunta_confirmacion").modal();
                    }
                }
            }
            
            function EnviarSubmit()
            {
                $("#FormularioRegistroLista").submit();
            }
            
            function EnviarSinGuardar()
            {
                var estructura_id = $("#estructura_id").val();
                var codigo_rubro = $("#codigo_rubro").val();
                var vista_actual = $("#vista_actual").val();
                var home_ant_sig = $("#home_ant_sig").val();
                var sin_guardar = 1;
                var tipo_registro = "";

                if ($("#tipo_registro").length > 0)
                {
                    tipo_registro = $("#tipo_registro").val();
                }

                var strParametros = "&estructura_id=" + estructura_id + "&codigo_rubro=" + codigo_rubro + "&vista_actual=" + vista_actual + "&home_ant_sig=" + home_ant_sig + "&sin_guardar=" + sin_guardar + "&tipo_registro=" + tipo_registro;
                Ajax_CargadoGeneralPagina("../Pasos/Guardar", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", strParametros);
            }

                ';

        return $contenido_js;
    }
    
    function ContenidoModalConfirma()
    {
        $contenido_modal = '
            
            <div class="modal fade" id="pregunta_confirmacion" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Seleccione para Continuar</h4>
                    </div>
                    <div class="modal-body">
                        <div style="text-align: center;">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 130px !important;" onclick="EnviarSinGuardar()">Sin Guardar</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 8px 0px !important; width: 150px !important;" onclick="EnviarSubmit()">Guardar y Continuar</button>
                        </div>
                    </div>
              </div>
            </div>
            </div>
            
            <div class="modal fade" id="pregunta_confirmacion_final" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Finalizó el Registro, al editar un paso se actualizará las estadísticas y tiempos</h4>
                    </div>
                    <div class="modal-body">
                        <div style="text-align: center;">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 130px !important;">Cancelar</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 8px 0px !important; width: 150px !important;" onclick="EnviarSinGuardar()">Si, Confirmar</button>
                        </div>
                    </div>
              </div>
            </div>
            </div>
            
            ';
        
        return $contenido_modal;
    }
        
    // De acuerdo a un Array, se envía el Key y se devuelve cuál es el anterior y el siguiente
    function paso_ant_sig($id, $array)
    {
        $paso = new stdClass();

        $cantidad_elementos = count($array);

        $id = str_replace("view_", "", $id);
        
        $index = array_search($id, $array);

        if($index !== FALSE)
        {
            if($index-1 < 0)
            {
                $paso->anterior = 'home';
            }
            else
            {
                $paso->anterior = $array[$index - 1];
            }

            if($index+2 > $cantidad_elementos)
            {
                $paso->siguiente = 'final';
            }
            else
            {
                $paso->siguiente = $array[$index + 1];
            }
            
            $paso->index = $index;
        }
        else
        {
            $paso->anterior = 'home';
            $paso->siguiente = 'final';
            
            $paso->index = $cantidad_elementos+2;
        }

        return $paso;
    }
    
    function getVistasRubro($codigo_rubro)
    {
        $vistas_rubro = new stdClass();

        /*

        1 = Producción
        2 = Servicios
        3 = Comercio
        4 = Transporte
        5 = Onboarding
        
        */

        $vistas_rubro->produccion = array(
            "datos_generales",
            "frecuencia_venta",
            "margen_utilidad",
            "proveedores",
            "materia_prima",
            "estacionalidad",
            "gastos_operativos",
            "gastos_familiares",
            "pasivos",
            "transporte_otros_ingresos",
            "otros_ingresos"
        );

        $vistas_rubro->servicios = array(
            "datos_generales",
            "frecuencia_venta",
            "margen_utilidad",
            "proveedores",
            "materia_prima",
            "estacionalidad",
            "gastos_operativos",
            "gastos_familiares",
            "pasivos",
            "transporte_otros_ingresos",
            "otros_ingresos"
        );

        $vistas_rubro->comercio = array(
            "datos_generales",
            "frecuencia_venta",
            "margen_utilidad",
            "proveedores",
            "estacionalidad",
            "gastos_operativos",
            "gastos_familiares",
            "pasivos",
            "transporte_otros_ingresos",
            "otros_ingresos"
        );

        $vistas_rubro->transporte = array(
            "datos_generales",
            "fuente_generadora",
            "volumen_ingresos",
            "transporte_margen_utilidad",
            "transporte_gastos_operativos",
            "gastos_familiares",
            "pasivos",
            "transporte_otros_ingresos",
            "otros_ingresos"
        );
        
        $vistas_rubro->transporte = array(
            "datos_generales",
            "fuente_generadora",
            "volumen_ingresos",
            "transporte_margen_utilidad",
            "transporte_gastos_operativos",
            "gastos_familiares",
            "pasivos",
            "transporte_otros_ingresos",
            "otros_ingresos"
        );
        
        $vistas_rubro->onboarding = array(
            "datos_personales",
            "ubicacion",
            "actividad_economica",
            "referencias"
        );
        
        switch ($codigo_rubro) {
            case 1:
                
                $array_rubro = $vistas_rubro->produccion;

                break;

            case 2:
                
                $array_rubro = $vistas_rubro->servicios;

                break;
            
            case 3:
                
                $array_rubro = $vistas_rubro->comercio;

                break;
            
            case 4:
                
                $array_rubro = $vistas_rubro->transporte;

                break;
            
            case 5:
                
                $array_rubro = $vistas_rubro->onboarding;

                break;
            
            default:
                
                $array_rubro = $vistas_rubro->servicios;
                
                break;
        }
        
        return $array_rubro;
    }
    
    function getContenidoNavApp($codigo_prospecto, $paso_actual='0', $tipo_registro='0')
    {   
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->select_info_dependencia($codigo_prospecto);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (!isset($arrResultado[0])) 
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        // Paso 1: En base al Código de Prospecto, se obtiene la información
        
        $titular_nombre = $arrResultado[0]['general_solicitante'];
        
            $arrConsulta = $this->mfunciones_logica->GetProspectoDepende($codigo_prospecto);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConsulta);

            if (isset($arrConsulta[0])) 
            {
                $arrResultado2 = $this->mfunciones_logica->select_info_dependencia($arrConsulta[0]['general_depende']);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);
                
                $familia_nombre = 'FAMILIAR | ' . $arrResultado2[0]['general_solicitante'];
            }
            else
            {
                $familia_nombre = 'REGISTRO DEL TITULAR';
            }
            
        $titular_rubro = $this->GetValorCatalogo($arrResultado[0]['camp_id'], 'nombre_rubro');
        $titular_ci = $arrResultado[0]['general_ci'] . ' ' . $this->GetValorCatalogo($arrResultado[0]['general_ci_extension'], 'extension_ci');
        $titular_telefono = $arrResultado[0]['general_telefono'];
        
        $codigo_rubro = $arrResultado[0]['camp_id'];
        
        $color_rubro = $this->GetValorCatalogo($arrResultado[0]['camp_id'], 'color_rubro');
        
        // Paso 2: Se establece el array de las vista según el rubro seleccionado
        
        $array_rubro = $this->getVistasRubro($codigo_rubro);
        
        // Se establece el máximo número de pasos
        $maximo_pasos = count($array_rubro) + 1;
        
        // Paso 3: Se establece las vistas (anterior | actual | siguiente)
        
        $vista_actual = $paso_actual;
            $vista_prospecto = $this->paso_ant_sig($vista_actual, $array_rubro);
        $vista_anterior = $vista_prospecto->anterior;
        $vista_siguiente = $vista_prospecto->siguiente;
        
        $vista_actual_numero = $vista_prospecto->index+1;
        
        $contenido = '';
        
        if($vista_actual != '0')
        {
            $contenido .= '
            
                <div style="position: fixed; top: 0;">
                    <span style="color: ' . $color_rubro . ' !important;" class="nav-home" onclick="ElementoSubmit(\'home\');"><i class="fa fa-home" aria-hidden="true"></i></span>
                </div>
                ';
        }elseif($tipo_registro=='unidad_familiar')
        {
            $contenido .= '
            
                <div style="position: fixed; top: 0;">
                    <span class="nav-home" onclick="ElementoSubmit(\'home\');"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                </div>
                ';
        }
        
        if($arrResultado[0]['onboarding'] == 0)
        {
            $data_auxiliar = ' | C.I. ' . $titular_ci;
        }
        else
        {
            $data_auxiliar = ' | C.I. ' . $arrResultado[0]['general_ci'];
        }
        
        
        
        $contenido .= '

            <div style="text-align: right;">
                <span class="nav-titulo">' . $titular_nombre . ' ' . $this->GetValorCatalogo($arrResultado[0]['general_categoria'], 'icono_categoria') . '</span>
                <br />
                <span class="nav-subtitulo"> ' . $familia_nombre . ' </span>
                <br />
                <span class="nav-subtitulo"> ' . $titular_rubro . $data_auxiliar . ' | Teléfono: ' . $titular_telefono . ' </span>
            </div>

            <div style="clear: both"></div>
            ';
        
            if($vista_actual != '0')
            {
                $contenido .= '
        
                <br />

                <div class="container">
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

                    <div class="container" style="margin-top: 5px;">
                        <div class="row"> ' . $aux_botones . ' </div>
                    </div>

                    ';
                }
            }
        
        return $contenido;
    }
    
    function ListaAgenciasRegion($codigo_agencia)
    {
        $this->load->model('mfunciones_logica');
        
        $arr_aux = $this->mfunciones_logica->ObtenerDatosAgencia(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arr_aux);

        if (isset($arr_aux[0]))
        {
            // Se agrupa el listado y construye el SELECT

            $arrRespuesta = $this->mfunciones_generales->ArrGroupBy($arr_aux, 'parent_detalle');

            $arrEjecutivo_html = '<select id="usuario_parent" name="usuario_parent">';
            $arrEjecutivo_html .= '<option value="-1"> --Seleccionar-- </option>';


            foreach ($arrRespuesta as $key => $values) {

                $arrEjecutivo_html .= '<optgroup style="background-color: #f3f3f3; color: #000000;" label="Agencia: ' . $key . '">';

                foreach ($values as $value) {

                    if($codigo_agencia == $value['estructura_agencia_id'])
                    {
                        $arrEjecutivo_html .= '<option selected="selected" value="'.$value['estructura_agencia_id'].'" style="background-color: #ffffff; color: #000000;"> → ' . $value['estructura_agencia_nombre'] . ' </option>';
                    }
                    else
                    {
                        $arrEjecutivo_html .= '<option value="'.$value['estructura_agencia_id'].'" style="background-color: #ffffff; color: #000000;"> → ' . $value['estructura_agencia_nombre'] . ' </option>';
                    }
                }

                $arrEjecutivo_html .= '</optgroup>';
            }

            $arrEjecutivo_html .= '</select>';

        }
        else 
        {
            $arrRespuesta[0] = $arr_aux;

            $arrEjecutivo_html = $this->lang->line('regionaliza_TablaNoRegistros');
        }
        
        return $arrEjecutivo_html;
    }
    
    function getRolUsuario($data) {

            $this->load->model('mfunciones_logica');
            $this->lang->load('general', 'castellano');

            $arrResultado = $arrResultado = $this->mfunciones_logica->ObtenerRoles($data);
            $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                    return $arrResultado[0]['rol_nombre'];
            }
            else
            {
                    return 'Parámetro Invalido';
            }		
    }
    
    function VerificaUsuarioRegion($codigo) {
        
        $this->load->model('mfunciones_logica');
        
        $lista_region = $this->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        
        $arrResultado = $this->mfunciones_logica->VerificaUsuarioRegion($codigo, $lista_region->region_id);
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    function VerificaEjecutivoRegion($codigo) {
        
        $this->load->model('mfunciones_logica');
        
        $lista_region = $this->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        
        $arrResultado = $this->mfunciones_logica->VerificaEjecutivoRegion($codigo, $lista_region->region_id);
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    function VerificaProspectoRegion($prospecto_codigo) {
        
        $this->load->model('mfunciones_logica');
        
        $lista_region = $this->getUsuarioRegion($_SESSION["session_informacion"]["codigo"]);
        
        $arrResultado = $this->mfunciones_logica->VerificaProspectoRegion($prospecto_codigo, $lista_region->region_id);
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    function getUsuarioRegion($usuario_codigo) {
        
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');

        $resultado = new stdClass();
        $resultado->error = false;
        
        // 1. Se obtiene el ID del usuario actual y se obtiene las regiones asignadas
        // 
        // Se ejecuta la consulta SQL 1
        try 
        {
            $consulta1 = $this->db->query(" SELECT ra.estructura_regional_id, r.estructura_regional_nombre , r.estructura_regional_departamento, r.estructura_regional_ciudad, r.estructura_regional_estado
                                            FROM region_asignado ra 
                                            INNER JOIN estructura_regional r ON r.estructura_regional_id=ra.estructura_regional_id
                                            WHERE usuario_id=" . $usuario_codigo);
            $filas_consultadas1 = $consulta1->result();
            $resultado->region = $filas_consultadas1;
            
            // Se captura la región asignada al usuario
            
            $aux = $this->ObtenerDatosRegionUsuario($usuario_codigo);
            
            // Se sanitiza y quita duplicados
            
            $aux = (array) $aux;
            $obj_merged = (object) array_merge((array) $resultado->region, (array) $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($aux));
            $aux2 = json_decode(json_encode($obj_merged), True);
            $aux2 = array_map("unserialize", array_unique(array_map("serialize", $aux2)));
            $aux2 = json_decode(json_encode($aux2));
            
            $resultado->region = $aux2;
            
            if (count($resultado->region)>0)
            {
                $aux_cant_corte = 4;
                
                $resultado->sin_registros = false;
                $resultado->region_nombres = implode(", ",array_map(function ($i) {return $i->estructura_regional_nombre;},$resultado->region));
                $resultado->region_nombres_corte = implode(", ",array_map(function ($i) {return $i->estructura_regional_nombre;},array_slice($resultado->region, 0, $aux_cant_corte)));
                $resultado->region_id = implode(", ",array_map(function ($i) {return $i->estructura_regional_id;},$resultado->region));
                
                if(count($resultado->region) > $aux_cant_corte)
                {
                    // Estilo adicional para 
                    $ua = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
                    if (preg_match('~MSIE|Internet Explorer~i', $ua) || (strpos($ua, 'Trident/7.0') !== false && strpos($ua, 'rv:11.0') !== false))
                    {
                        $aux_iexplorer = 'float: right; ';
                    }
                    else
                    {
                        $aux_iexplorer = ';';
                    }
                    
                    
                    $aux_texto_more = "<br /> <a class='MenuSeccionMinimalista' style='" . $aux_iexplorer . "font-size: 0.70em; font-weight: 600 !important; padding: 1px 10px 3px 10px; margin: 4px; box-shadow: 0 2px 5px 0 rgb(0 0 0 / 20%), 0 2px 5px 0 rgb(0 0 0 / 20%);' onclick=\"Ajax_CargadoGeneralPagina('Afiliador/Detalle/Regiones', 'divElementoFlotante', 'divErrorBusqueda')\";><i class='fa fa-commenting-o' aria-hidden='true'></i> Ver " . (count($resultado->region) - $aux_cant_corte) . " más</a>";
                }
                else
                {
                    $aux_texto_more = "";
                }
                
                $resultado->region_nombres_texto = "<br /> <span style='float: left; text-align: justify;' class='AyudaTooltip' data-balloon-length='large' data-balloon='". $this->lang->line('regionaliza_ayuda') . "' data-balloon-pos='right'> </span><span style='font-size: 0.70em; font-style: italic;'> " . $this->lang->line('titulo_regiones_supervisadas') . ": " . $resultado->region_nombres_corte . $aux_texto_more . "</span>";
                $resultado->region_nombres_plano = str_replace(', ', '<br /> - ', $resultado->region_nombres_corte) . $aux_texto_more . "</span>";
            }
            else
            {
                $resultado->region_prospectos = -1;
                $resultado->region_nombres = 'No asignado';
                $resultado->region_id = 0;
                $resultado->region_consulta = " AND p.prospecto_id IN (" . $resultado->region_prospectos . ")";
                
                $resultado->region_nombres_texto = 'No asignado';
                $resultado->region_nombres_plano = 'No asignado';
                
                return $resultado;
            }
        } catch (Exception $ex) {
            $resultado->error = true;
            $resultado->mensaje_error = "Ocurrio un evento inesperado, intentelo mas tarde.";
        }
        
        return $resultado;
        
    }
    
    function GetRegionUsuario($usuario_codigo, $region_codigo) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerDatosUsuarioRegion($usuario_codigo, $region_codigo);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }
    
    function getProspectosRegion($codigo_usuario=-1) {

        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');

        $resultado = new stdClass();
        $resultado->error = false;
        
        // 1. Se obtiene el ID del usuario actual y se obtiene las regiones asignadas
        
        if($codigo_usuario == -1)
        {
            $codigo_usuario = $_SESSION["session_informacion"]["codigo"];
        }
        
        $resultado = $this->getUsuarioRegion($codigo_usuario);
        
        $resultado->sin_registros = true;
        
        // #######
        
        $logica_accion = 'jerarquia'; // jerarquia: Se toma la relaión jerárquica del ejecutivo de cuentas      departamento: Simplemente se toma el departamento de la empresa
        
        // #######
        
        
        // 2. Se obtiene el listado de Prospectos que corresponden a la(s) Región(es) asignadas al usuario
        
        // Se ejecuta la consulta SQL 2
        try 
        {
            switch ($logica_accion) {
                case 'jerarquia':

                    $id_region = implode(",",array_map(function ($i) {return $i->estructura_regional_id;},$resultado->region));
                    
                    // Se toma la relaión jerárquica del ejecutivo de cuentas
                    
                    $consulta2 = $this->db->query(" SELECT p.prospecto_id
                                                    FROM prospecto p
                                                    WHERE p.codigo_agencia_fie IN (" . $id_region . ")");
                    $filas_consultadas2 = $consulta2->result();
                    
                    break;

                case 'departamento':
                    
                    // Se debe convertir las regiones en códigos Departamento
                    
                    // 1: CHUQUISACA
                    // 2: LA PAZ
                    // 3: COCHABAMBA
                    // 4: ORURO 
                    // 5: POTOSI
                    // 6: TARIJA
                    // 7: SANTA CRUZ
                    // 8: BENI
                    // 9: PANDO 
                    
                    // Regiones 1: La Paz   2: Santa Cruz   3: Cochabamba
                    
                    $id_departamento = "";
                    
                    foreach ($resultado->region as $key1 => $value1) 
                    {
                        switch ($value1->estructura_regional_id) {
                            case 1: $id_departamento .= "2, 8, 9, "; break;

                            case 2: $id_departamento .= "7, 1, 6, "; break;
                                
                            case 3: $id_departamento .= "3, 4, 5, "; break;
                            
                            default:
                                break;
                        }
                    }
                    
                    $id_departamento .= "-1";
                    
                    $consulta2 = $this->db->query(" SELECT p.prospecto_id 
                                                    FROM prospecto p
                                                    INNER JOIN empresa e ON e.empresa_id=p.empresa_id
                                                    WHERE e.empresa_departamento IN (" . $id_departamento . ")");
                    $filas_consultadas2 = $consulta2->result();
                    
                    break;
                
                default:
                    break;
            }
            
            $resultado->prospectos = $filas_consultadas2;
            if (count($filas_consultadas2)>0) $resultado->sin_registros = false;
        } catch (Exception $ex) {
            $resultado->error = true;
            $resultado->mensaje_error = "Ocurrio un evento inesperado, intentelo mas tarde.";
        }
        
        if(!$resultado->error)
        {
            $resultado->region_prospectos = implode(",",array_map(function ($i) {return $i->prospecto_id;},$resultado->prospectos));
            $resultado->region_consulta = " AND p.prospecto_id IN (" . $resultado->region_prospectos . ")";
        }
        
        if($resultado->sin_registros)
        {
            $resultado->region_consulta = " AND p.prospecto_id IN (-1)";
        }
        
        return $resultado;
    }
        
        function GeneraPDF($vista, $datos, $orientacion='P') {
            $this->lang->load('general', 'castellano');
            // Ajusta el limite de memoria, puede no ser requerido según instalación del servidor
                if((int)(ini_get('memory_limit')) < 128)
                {
                    ini_set("memory_limit",'128M');
                }
            
            $html = $this->load->view($vista,$datos,true);
            $this->load->library('pdf');
            $pdf = $this->pdf->load();

            $reporte_generado = 'Reporte Generado ' . date('d/m/Y H:i') . ' (' . $_SESSION["session_informacion"]["login"] . ')';

            $header = array (
                'odd' => array (
                    'L' => array (
                        'content' => $this->lang->line('ReporteTituloIzquierda'),
                        'font-size' => 8,
                        'font-style' => '',
                        'font-family' => 'Arial',
                        'color'=>'#000000'
                    ),
                    'C' => array (
                        'content' => $this->lang->line('ReporteTituloCentro'),
                        'font-size' => 8,
                        'font-style' => '',
                        'font-family' => 'Arial',
                        'color'=>'#000000'
                    ),
                    'R' => array (
                        'content' => $this->lang->line('ReporteTituloDerecha'),
                        'font-size' => 8,
                        'font-style' => '',
                        'font-family' => 'Arial',
                        'color'=>'#000000'
                    ),
                    'line' => 1,
                ),
                'even' => array ()
            );

            $pdf->SetHeader($header);

            $pdf->SetHTMLFooter('<table border="0" style="text-align: right; font-family: \'Open Sans\', Arial, sans-serif; font-size: 11px; width: 100%"><tr><td align="left"> ' . $reporte_generado . ' </td><td>Página {PAGENO} de {nb}</td></td></table>');
            
            if($orientacion == 'L')
            {
                $pdf->AddPage('L');
            }
            
            $pdf->WriteHTML($html);

            /*        
            I: send the file inline to the browser. The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
            D: send to the browser and force a file download with the name given by name.
            F: save to a local file with the name given by name (may include a path).
            S: return the document as a string. name is ignored.
            */
            $pdf->Output('reporte_initium_' . date('Ymd_His') . '.pdf', 'I');
        }
        
        function GeneraPDF_Informe($html) {
            $this->lang->load('general', 'castellano');
            // Ajusta el limite de memoria, puede no ser requerido según instalación del servidor
                if((int)(ini_get('memory_limit')) < 128)
                {
                    ini_set("memory_limit",'128M');
                }
            
            $this->load->library('pdf');
            $pdf = $this->pdf->load();

            $pdf->AddPage('P', // L - landscape, P - portrait
                    '', '', '', '',
                    20, // margin_left
                    20, // margin right
                    20, // margin top
                    20, // margin bottom
                    20, // margin header
                    10); // margin footer
            
            $pdf->SetHTMLFooter('<div style="text-align: right; font-family: chelvetica; font-size: 10px; width: 100%">Página {PAGENO} de {nb}</div>');
            
            $pdf->WriteHTML($html);
            
            /*        
            I: send the file inline to the browser. The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
            D: send to the browser and force a file download with the name given by name.
            F: save to a local file with the name given by name (may include a path).
            S: return the document as a string. name is ignored.
            */
            $pdf->Output('ReporteConsolidado_' . date('Ymd_His') . '.pdf', 'I');
        }
        
        function GeneraExcel_Informe($html) {
    //        ini_set('display_errors', 1);
    //        ini_set('display_startup_errors', 1);
    //        error_reporting(E_ALL);
    //        ini_set("memory_limit",'32M');
    //        $tmpFile = tempnam("/tmp","tmp".microtime()."xls");
    //        file_put_contents($tmpFile,$this->load->view($vista,$datos,true));
    //        $this->load->library("Excel");
    //        $reader = PHPExcel_IOFactory::createReader("HTML");
    //        $objPHPExcel = $reader->load($tmpFile);
    //        $objPHPExcel->getActiveSheet()->setTitle("Reporte_INITIUM");
    //        unlink($tmpFile);
    //        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //        header('Content-Disposition: attachment;filename=reporte_initium_' . date('Ymd_His') . '.xlsx');
    //        header('Cache-Control: max-age=0');
    //                
    //        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
    //        $writer->save("php://output");

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename=reporte_initium_' . date('Ymd_His') . '.xls');
            header('Cache-Control: max-age=0');

            echo $html;
        }
        
        function GeneraPDF_SinHeader($vista, $datos) {
            $this->lang->load('general', 'castellano');
            // Ajusta el limite de memoria, puede no ser requerido según instalación del servidor
                if((int)(ini_get('memory_limit')) < 128)
                {
                    ini_set("memory_limit",'128M');
                }
            
            $html = $this->load->view($vista,$datos,true);
            $this->load->library('pdf');
            $pdf = $this->pdf->load();

            $pdf->WriteHTML($html);

            /*        
            I: send the file inline to the browser. The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
            D: send to the browser and force a file download with the name given by name.
            F: save to a local file with the name given by name (may include a path).
            S: return the document as a string. name is ignored.
            */
            $pdf->Output('reporte_initium_' . date('Ymd_His') . '.pdf', 'I');
        }
        
        function GeneraExcel($vista, $datos) {
    //        ini_set('display_errors', 1);
    //        ini_set('display_startup_errors', 1);
    //        error_reporting(E_ALL);
    //        ini_set("memory_limit",'32M');
    //        $tmpFile = tempnam("/tmp","tmp".microtime()."xls");
    //        file_put_contents($tmpFile,$this->load->view($vista,$datos,true));
    //        $this->load->library("Excel");
    //        $reader = PHPExcel_IOFactory::createReader("HTML");
    //        $objPHPExcel = $reader->load($tmpFile);
    //        $objPHPExcel->getActiveSheet()->setTitle("Reporte_INITIUM");
    //        unlink($tmpFile);
    //        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //        header('Content-Disposition: attachment;filename=reporte_initium_' . date('Ymd_His') . '.xlsx');
    //        header('Cache-Control: max-age=0');
    //                
    //        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
    //        $writer->save("php://output");

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename=reporte_initium_' . date('Ymd_His') . '.xls');
            header('Cache-Control: max-age=0');

            echo $this->load->view($vista,$datos,true);

        }
        
        function ArrGroupBy($array, $clave)
        {
            $result = array();
            foreach ($array as $element)
            {
                $result[$element[$clave]][] = $element;
            }
            
            return $result;
        }
        
        function EtapaHitosProspecto($array, $codigo_etapa)
        {
            
            //$this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($fechasEtapas[array_search(128, array_column($fechasEtapas,"etapa_id"))]["hito_fecha_ini"]); 
            
            $posicion = array_search($codigo_etapa, array_column($array,"etapa_id"));
            
            if(is_int($posicion))
            {
                return $this->getFormatoFechaD_M_Y_H_M($array[$posicion]["hito_fecha_ini"]);
            }
            else
            {
                return "--";
            }
        }
        
        function ObtenerColorAvance($total, $avance)
        {
            
            $rojo = 'background-color: #db1b1c; color: #ffffff;';
            $amarillo = 'background-color: #fec506; color: #ffffff;';
            $verde = 'background-color: #389317; color: #ffffff;';
            
            $total = (int)str_replace(",",".",$total);
            $avance = (int)str_replace(",",".",$avance);
            
            if($avance>=$total)
            {
                return $verde;
            }
            
            $resultado = ($avance*100)/$total;
            
            switch (1) {
                case ($resultado<=89):

                    return $rojo;
                    
                    break;
                
                case ($resultado>=90 && $resultado<=99):

                    return $amarillo;
                    
                    break;
                
                case ($resultado>=100):
                    
                    return $verde;
                    
                    break;

                default:
                    break;
            }
        }
        
        function ObtenerTimmingCampana($codigo_campana)
        {
            $arrResultado = $this->mfunciones_logica->ObtenerCampana($codigo_campana);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
            
            // Días
                    
            // 1: Se obtiene la fecha de conclusión de la campaña en base a su fecha de inicio y el plazo

            $aux_fecha_inicio = new DateTime($arrResultado[0]["camp_fecha_inicio"]);
            $aux_fecha_inicio->add(new DateInterval('P' . $arrResultado[0]["camp_plazo"] . 'D'));
            $aux_fecha_final = $aux_fecha_inicio->format('Y-m-d');

            // 2: Se calcula la cantidad de días entre la fecha actual y la fecha de finalización de la campaña

            $fecha_actual = new DateTime(date("Y-m-d"));

            $aux_fecha_final = new DateTime($aux_fecha_final);

            if($fecha_actual > $aux_fecha_final)
            {
                $avance_campana_dias_porcentaje = "(finalizó) 100,00";
            }
            else
            {
                $aux_diferencia_dias = $aux_fecha_final->diff($fecha_actual)->format("%a");
            
                // 3: Se calcula los días avanzados en Número y Porcentaje

                if($aux_diferencia_dias >= $arrResultado[0]["camp_plazo"])
                {
                    $avance_campana_dias_numero = 0;
                }
                else
                {
                    $avance_campana_dias_numero = $arrResultado[0]["camp_plazo"] - $aux_diferencia_dias;
                }

                $avance_campana_dias_porcentaje = number_format((($avance_campana_dias_numero*100)/$arrResultado[0]["camp_plazo"]), 2, ',', '.');
            }
            
            return $avance_campana_dias_porcentaje;
        }
        
        function CalculoLeadAgenteCampana($agente, $campana)
        {
            $this->load->model('mfunciones_logica');
            $this->lang->load('general', 'castellano');
            
            $arrReporte = array();
            
            $filtro = 'p.ejecutivo_id=' . $agente . ' AND p.camp_id=' . $campana;
        
            $arrResultado = $this->mfunciones_logica->ListadoBandejasProspecto($filtro);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
            
            if (!isset($arrResultado[0]))
            {
                js_error_div_javascript($this->lang->line('TablaNoResultados'));
                exit();
            }
            else
            {
                // Se inicializan las variables
            
                $campana_id = $arrResultado[0]["camp_id"];
                $campana_nombre = $arrResultado[0]["camp_nombre"];
                $campana_desc = $arrResultado[0]["camp_desc"];
                $campana_plazo = $arrResultado[0]["camp_plazo"];
                $campana_monto_oferta = $arrResultado[0]["camp_monto_oferta"];
                $campana_tasa = $arrResultado[0]["camp_tasa"];
                $campana_fecha_inicio = $arrResultado[0]["camp_fecha_inicio"];
                
                $agente_id = $arrResultado[0]["ejecutivo_id"];
                $agente_nombre = $arrResultado[0]["ejecutivo_nombre"];
                
                $contador_total = 0;

                $contador_asignado = 0;
                $contador_interes = 0;
                $contador_cierre = 0;
                $contador_entrega = 0;
                $contador_carpeta = 0;
                $contador_aprobacion = 0;
                $contador_rechazo = 0;
                $contador_desembolso = 0;
                
                $contador_nointeres = 0;

                $suma_aprobacion = 0;
                $suma_desembolso = 0;
                
                $porcentaje_asignado = 0;
                $porcentaje_interes = 0;
                $porcentaje_cierre = 0;
                $porcentaje_entrega = 0;
                $porcentaje_carpeta = 0;
                $porcentaje_aprobacion = 0;
                $porcentaje_rechazo = 0;
                $porcentaje_desembolso = 0;
                
                $avance_desembolso_numero = 0;
                $avance_desembolso_porcentaje = 0;
                
                $avance_campana_dias_numero = 0;
                $avance_campana_dias_porcentaje = 0;
                
                foreach ($arrResultado as $key => $value) 
                {
                    switch ($value["prospecto_etapa"]) {

                        case 1: $contador_asignado++; break;
                        case 2: $contador_interes++; break;
                        case 3: $contador_cierre++; break;
                        case 4: $contador_entrega++; break;
                        case 5: $contador_carpeta++; break;
                        case 6: $contador_aprobacion++; $suma_desembolso+=$value["prospecto_monto_desembolso"]; break;
                        case 7: $contador_rechazo++; break;
                        case 8: $contador_desembolso++; $suma_desembolso+=$value["prospecto_monto_desembolso"]; break;

                        case 10: $contador_nointeres++; break;
                    
                        default:
                            break;
                    }
                    
                    $contador_total++;
                }
                
                // REFERENCIA
                $arrReporte[0]["campana_id"] = $campana_id;
                $arrReporte[0]["campana_nombre"] = $campana_nombre;
                $arrReporte[0]["campana_desc"] = $campana_desc;
                $arrReporte[0]["campana_plazo"] = $campana_plazo;
                $arrReporte[0]["campana_monto_oferta"] = $campana_monto_oferta;
                $arrReporte[0]["campana_tasa"] = $campana_tasa;
                $arrReporte[0]["campana_fecha_inicio"] = $this->mfunciones_generales->getFormatoFechaD_M_Y($campana_fecha_inicio);
                $arrReporte[0]["agente_id"] = $agente_id;
                $arrReporte[0]["agente_nombre"] = $agente_nombre;
                // CONTADORES
                $arrReporte[0]["contador_total"] = $contador_total;
                $arrReporte[0]["contador_asignado"] = $contador_asignado;
                $arrReporte[0]["contador_interes"] = $contador_interes;
                $arrReporte[0]["contador_cierre"] = $contador_cierre;
                $arrReporte[0]["contador_entrega"] = $contador_entrega;
                $arrReporte[0]["contador_carpeta"] = $contador_carpeta;
                $arrReporte[0]["contador_aprobacion"] = $contador_aprobacion;
                $arrReporte[0]["contador_rechazo"] = $contador_rechazo;
                $arrReporte[0]["contador_desembolso"] = $contador_desembolso;
                
                $arrReporte[0]["contador_nointeres"] = $contador_nointeres;
                
                $arrReporte[0]["suma_aprobacion"] = $suma_aprobacion;
                $arrReporte[0]["suma_desembolso"] = $suma_desembolso;
                // PORCENTAJE
                $arrReporte[0]["porcentaje_asignado"] = number_format((($contador_asignado*100)/$contador_total), 2, ',', '.');
                $arrReporte[0]["porcentaje_interes"] = number_format((($contador_interes*100)/$contador_total), 2, ',', '.');
                $arrReporte[0]["porcentaje_cierre"] = number_format((($contador_cierre*100)/$contador_total), 2, ',', '.');
                $arrReporte[0]["porcentaje_entrega"] = number_format((($contador_entrega*100)/$contador_total), 2, ',', '.');
                $arrReporte[0]["porcentaje_carpeta"] = number_format((($contador_carpeta*100)/$contador_total), 2, ',', '.');
                $arrReporte[0]["porcentaje_aprobacion"] = number_format((($contador_aprobacion*100)/$contador_total), 2, ',', '.');
                $arrReporte[0]["porcentaje_rechazo"] = number_format((($contador_rechazo*100)/$contador_total), 2, ',', '.');
                $arrReporte[0]["porcentaje_desembolso"] = number_format((($contador_desembolso*100)/$contador_total), 2, ',', '.');
                
                $arrReporte[0]["porcentaje_nointeres"] = number_format((($contador_nointeres*100)/$contador_total), 2, ',', '.');
                
                // NIVEL AVANCE
                $arrReporte[0]["avance_desembolso_numero"] = number_format((($contador_desembolso*$campana_monto_oferta)/$contador_total), 2, ',', '.');
                $arrReporte[0]["avance_desembolso_porcentaje"] = number_format((($contador_desembolso*100)/$contador_total), 2, ',', '.');
                $arrReporte[0]["avance_desembolso_porcentaje_numero"] = number_format((($contador_desembolso*100)/$contador_total), 2, '.', '.');
                
                    // Días
                    
                    // 1: Se obtiene la fecha de conclusión de la campaña en base a su fecha de inicio y el plazo
                    
                    $aux_fecha_inicio = new DateTime($campana_fecha_inicio);
                    $aux_fecha_inicio->add(new DateInterval('P' . $campana_plazo . 'D'));
                    $aux_fecha_final = $aux_fecha_inicio->format('Y-m-d');
                    
                    $arrReporte[0]["campana_fecha_final"] = $this->mfunciones_generales->getFormatoFechaD_M_Y($aux_fecha_final);
                    
                    // 2: Se calcula la cantidad de días entre la fecha actual y la fecha de finalización de la campaña
                    
                    $fecha_actual = new DateTime(date("Y-m-d"));
                    
                    $aux_fecha_final = new DateTime($aux_fecha_final);
                    
                    if($fecha_actual > $aux_fecha_final)
                    {
                        $avance_campana_dias_porcentaje = "100,00";
                    }
                    else
                    {
                        $aux_diferencia_dias = $aux_fecha_final->diff($fecha_actual)->format("%a");

                        // 3: Se calcula los días avanzados en Número y Porcentaje

                        if($aux_diferencia_dias >= $arrResultado[0]["camp_plazo"])
                        {
                            $avance_campana_dias_numero = 0;
                        }
                        else
                        {
                            $avance_campana_dias_numero = $arrResultado[0]["camp_plazo"] - $aux_diferencia_dias;
                        }

                        $avance_campana_dias_porcentaje = number_format((($avance_campana_dias_numero*100)/$arrResultado[0]["camp_plazo"]), 2, ',', '.');
                    }
                    
                $arrReporte[0]["avance_campana_dias_numero"] = $avance_campana_dias_numero;
                $arrReporte[0]["avance_campana_dias_porcentaje"] = $avance_campana_dias_porcentaje;
                
                return $arrReporte;
            }
        }
        
        function ListadoCamapanaServicios()
        {
            $this->load->model('mfunciones_logica');
            
            // Se realiza la consulta 1 sóla vez a las campañas y servicios para guardarlo en una variable y no tener que consultar en la DB cada vez
            $arrCampana= $this->mfunciones_logica->ObtenerCampana(-1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrCampana);

            $arrBase1 = array();

            $contador1 = 0;

            foreach ($arrCampana as $key => $value1) 
            {
                $clave_campana = $value1["camp_id"];

                $arrServicios = $this->mfunciones_logica->ObtenerServiciosCampana($clave_campana);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);

                $arrBase2 = array();

                $contador2 = 0;

                foreach ($arrServicios as $key => $value2) 
                {
                    $arrBase2[$contador2] = $value2["servicio_id"];

                    $contador2++;
                }

                $arrBase1[$clave_campana] = array(
                    "servicios" => $arrBase2,
                );

                $contador1++;
            }
            
            return $arrBase1;
        }
        
        function VerificaReporteLegal($codigo_prospecto)
        {
            $arrResultado = $this->mfunciones_logica->ListaDatosEvaluacion($codigo_prospecto);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        
        function VerificaFormSociedad($codigo_prospecto)
        {
            $arrResultado = $this->mfunciones_logica->ListaDatosForm_Sociedad($codigo_prospecto);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        
        function VerificaFormMatch($codigo_prospecto)
        {
            $arrResultado = $this->mfunciones_logica->ListaDatosForm_Match($codigo_prospecto);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        
        function VerificaCampoLead($campo, $valor)
        {
            $this->lang->load('general', 'castellano');
            $this->load->model('mfunciones_generales');
            $this->load->model('mfunciones_logica');

            $error1 = 'Estructura y/o dimensión no válida';
            $error2 = 'La Campaña no esta registrada en la BD';
            $error3 = 'El Agente no esta registrado en la BD o no está habilitado';
            $error4 = 'El Estado no esta registrado en la BD o no está habilitado';

            $respuesta = '';

            switch ($campo) {

                case 'nombre_etapa':

                    $arrConsulta = $this->mfunciones_logica->ObtenerCodigoEtapaNombre($valor);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConsulta);

                    if(!isset($arrConsulta[0]))
                    {
                        $respuesta = $error4;
                    }

                    break;
                
                case 'nombre_campana':

                    $arrConsulta = $this->mfunciones_logica->ObtenerCodigoCampanaNombre($valor);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConsulta);

                    if(!isset($arrConsulta[0]))
                    {
                        $respuesta = $error2;
                    }

                    break;

                case 'string_vacio':

                    if($valor == '' || strlen((string)$valor) > 100)
                    {
                        $respuesta = $error1;
                    }

                    break;
                    
                 case 'idc':

                    if($valor == '' || strlen((string)$valor) > 15)
                    {
                        $respuesta = $error1;
                    }

                    break;

                case 'nombre_cliente':

                    if($valor == '' || strlen((string)$valor) > 150)
                    {
                        $respuesta = $error1;
                    }

                    break;

                case 'empresa':

                    if($valor == '' || strlen((string)$valor) > 150)
                    {
                        $respuesta = $error1;
                    }

                    break;

                case 'ingreso':

                    if($valor == '' || !is_numeric($valor) || $valor <= 0)
                    {
                        $respuesta = $error1;
                    }

                    break;

                case 'direccion':

                    if($valor == '' || strlen((string)$valor) > 255)
                    {
                        $respuesta = $error1;
                    }

                    break;

                case 'telefono':

                    if($valor == '' || strlen((string)$valor) > 10)
                    {
                        $respuesta = $error1;
                    }

                    break;

                case 'celular':

                    if($valor == '' || strlen((string)$valor) > 10)
                    {
                        $respuesta = $error1;
                    }

                    break;

                case 'correo':

                    if($this->mfunciones_generales->VerificaCorreo($valor) == false || strlen((string)$valor) > 150)
                    {
                        $respuesta = $error1;
                    }

                    break;

                case 'matricula':

                    if($valor == '' || strlen((string)$valor) > 16)
                    {
                        $respuesta = $error1;

                        break;
                    }

                    $arrConsulta = $this->mfunciones_logica->ObtenerCodigoEjecutivoUsuario($valor);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConsulta);

                    if(!isset($arrConsulta[0]))
                    {
                        $respuesta = $error3;
                    }

                    break;

                default:
                    $respuesta = 'Sin valor';
            }

            return $respuesta;
        }
        
        function GetValorCatalogoParent($data, $tipo_codigo) {
	
            $this->load->model('mfunciones_logica');
            $this->lang->load('general', 'castellano');

            if($data == '-1')
            {
                    $resultado = '<i>Ninguno</i>';
            }
            else
            {
                    switch ($tipo_codigo) 
                    {            
                        case 'dir_provincia': $valor_parent = 'dir_departamento'; break;				
                        case 'dir_localidad_ciudad': $valor_parent = 'dir_provincia'; break;
                        case 'dir_barrio_zona_uv': $valor_parent = 'dir_localidad_ciudad'; break;

                        case 'ae_subsector_economico': $valor_parent = 'ae_sector_economico'; break;
                        case 'ae_actividad_economica': $valor_parent = 'ae_subsector_economico'; break;
                        case 'ae_actividad_fie': $valor_parent = 'ae_actividad_economica'; break;
                        
                        default:  return('<i>Relación a código "' . $data . '"</i>'); break;
                    }

                    $arrResultado1 = $this->mfunciones_logica->ObtenerDatosCatalogoParent($data, $valor_parent);
                    $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                    if (isset($arrResultado1[0])) 
                    {
                            $resultado = $arrResultado1[0]['catalogo_tipo_codigo'] . ' | ' . $arrResultado1[0]['catalogo_descripcion'];                   
                    }
                    else 
                    {
                            $resultado = 'Parámetro Invalido';
                    }

            }

            return($resultado);
        }
        
	function GetValorCatalogo($data, $tipo) {
		
		$this->load->model('mfunciones_logica');
		$this->lang->load('general', 'castellano');
		
		$resultado = "No Definido";
                
                if($tipo == 'segip_operador_resultado')
		{
                    switch ($data) {              
                        case 0:
                            $resultado = 'No Satisfactorio';
                            break;
                        case 1:
                            $resultado = 'Satisfactorio';
                            break;
                        case 2:
                            $resultado = 'No Realizado';
                            break;
                        default:
                            $resultado = 'No Registrado';
                            break;
                    }
                }
                
                if($tipo == 'agencia_estado')
		{
                    switch ($data) {              
                        case 0:
                            $resultado = 'Cerrada';
                            break;
                        case 1:
                            $resultado = 'Disponible';
                            break;
                        default:
                            $resultado = 'No Registrado';
                            break;
                    }
                }
                
                if($tipo == 'tercero_envio')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'Al Domicilio';
                            break;
                        case 2:
                            $resultado = 'Al Trabajo';
                            break;
                        default:
                            $resultado = 'No Registrado';
                            break;
                    }
                }
                
                
                if($tipo == 'tercero_asistencia')
		{
                    switch ($data) {              
                        case 0:
                            $resultado = 'No Asistido';
                            break;
                        case 1:
                            $resultado = 'Asistido';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'terceros_estado')
		{
                    switch ($data) {              
                        case 0:
                            $resultado = 'Solicitud Registrada';
                            break;
                        case 1:
                            $resultado = 'Aprobado';
                            break;
                        case 2:
                            $resultado = 'Registrado en COBIS (etapa)';
                            break;
                        case 3:
                            $resultado = 'Rechazado RPA';
                            break;
                        case 4:
                            $resultado = 'Rechazado Bandeja';
                            break;
                        case 5:
                            $resultado = 'Completado';
                            break;
                        case 6:
                            $resultado = 'Entregado al Cliente';
                            break;
                        case 7:
                            $resultado = $this->lang->line('notificar_cierre_texto');
                            break;
                        case 8:
                            $resultado = $this->lang->line('cuenta_cerrada_texto');
                            break;
                        case 15:
                            $resultado = $this->lang->line('bandeja_procesando');
                            break;
                        case 16:
                            $resultado = $this->lang->line('ValOperOpcion');
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'flujo_cobis_etapa')
		{
                    switch ($data) {              
                        case -2:
                            $resultado = 'Reingreso Flujo';
                            break;
                        case -1:
                            $resultado = 'Inicio Flujo';
                            break;
                        case 0:
                            $resultado = 'Previo a ' . $this->lang->line('f_registro_cobis');
                            break;
                        case 1:
                            $resultado = 'Consulta de clientes (CI)';
                            break;
                        case 2:
                            $resultado = 'Consulta de clientes (Cód. COBIS)';
                            break;
                        case 3:
                            $resultado = 'Creación / Actualización de clientes PN';
                            break;
                        case 4:
                            $resultado = 'Apertura de cuenta y Afiliación a Fienet';
                            break;
                        case 5:
                            $resultado = 'Generación contrato PDF';
                            break;
                        case 6:
                            $resultado = 'Envío correo a cliente';
                            break;
                        
                        case 20:
                            $resultado = 'Reintento';
                            break;
                        case 21:
                            $resultado = 'Derivado a ' . $this->GetValorCatalogo(15, 'terceros_estado');
                            break;
                        case 22:
                            $resultado = 'Derivado a ' . $this->GetValorCatalogo(4, 'terceros_estado');
                            break;
                        case 23:
                            $resultado = 'Derivado a ' . $this->lang->line('SupervisorTercerosTitulo');
                            break;
                        
                        case 99:
                            $resultado = 'Flujo terminado. Derivado a ' . $this->lang->line('AgenciaTercerosTitulo');
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'estado_civil')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'Soltero(a)';
                            break;
                        case 2:
                            $resultado = 'Casado(a)';
                            break;
                        case 3:
                            $resultado = 'Divorciado(a)';
                            break;
                        case 4:
                            $resultado = 'Viudo(a)';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'terceros_llevar')
		{
                    switch ($data) {              
                        case 0:
                            $resultado = 'Recoger de Agencia';
                            break;
                        case 1:
                            $resultado = 'Domicio Particular';
                            break;
                        case 2:
                            $resultado = 'Domicilio Comercial';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'transporte_cliente_frecuencia')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'Semana';
                            break;
                        case 2:
                            $resultado = 'Quincena';
                            break;
                        case 4:
                            $resultado = 'Mes';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'transporte_preg_vehiculo_combustible')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'GASOLINA';
                            break;
                        case 2:
                            $resultado = 'DIESEL';
                            break;
                        case 3:
                            $resultado = 'GNV';
                            break;
                        case 4:
                            $resultado = 'GLP';
                            break;
                        case 5:
                            $resultado = 'GASOLINA-GNV';
                            break;
                        case 6:
                            $resultado = 'DIESEL-GNV';
                            break;
                        case 7:
                            $resultado = 'GASOLINA-GLP';
                            break;
                        case 8:
                            $resultado = 'DIESEL-GLP';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'transporte_tipo_prestatario')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'PROPIETARIO';
                            break;
                        case 2:
                            $resultado = 'PROPIETARIO QUE PERCIBE RENTA';
                            break;
                        case 3:
                            $resultado = 'CHOFER';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'transporte_tipo_transporte')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'MICROBUS';
                            break;
                        case 2:
                            $resultado = 'MINIBUS';
                            break;
                        case 3:
                            $resultado = 'TRUFI';
                            break;
                        case 4:
                            $resultado = 'RADIO TAXI';
                            break;
                        case 5:
                            $resultado = 'TAXI';
                            break;
                        case 6:
                            $resultado = 'MOTO TAXI';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'frec_dia_semana_sel')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'Primera';
                            break;
                        case 2:
                            $resultado = 'Segunda';
                            break;
                        case 3:
                            $resultado = 'Tercera';
                            break;
                        case 4:
                            $resultado = 'Cuarta';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'arb')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'Alto';
                            break;
                        case 2:
                            $resultado = 'Regular';
                            break;
                        case 3:
                            $resultado = 'Bajo';
                            break;
                        case 4:
                            $resultado = 'S. Actividad';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'mes_literal')
		{   
                    switch ($data) {              
                        case 1:
                            $resultado = 'Enero';
                            break;
                        case 2:
                            $resultado = 'Febrero';
                            break;
                        case 3:
                            $resultado = 'Marzo';
                            break;
                        case 4:
                            $resultado = 'Abril';
                            break;
                        case 5:
                            $resultado = 'Mayo';
                            break;
                        case 6:
                            $resultado = 'Junio';
                            break;
                        case 7:
                            $resultado = 'Julio';
                            break;
                        case 8:
                            $resultado = 'Agosto';
                            break;
                        case 9:
                            $resultado = 'Septiembre';
                            break;
                        case 10:
                            $resultado = 'Octubre';
                            break;
                        case 11:
                            $resultado = 'Noviembre';
                            break;
                        case 12:
                            $resultado = 'Diciembre';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'criterio_inciso')
		{   
                    switch ($data) {              
                        case 1:
                            $resultado = 'a)';
                            break;
                        case 2:
                            $resultado = 'b)';
                            break;
                        case 3:
                            $resultado = 'c)';
                            break;
                        case 4:
                            $resultado = 'd)';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'combo_margen_valor')
		{   
                    switch ($data) {              
                        case 1:
                            $resultado = 40;
                            break;
                        case 2:
                            $resultado = 50;
                            break;
                        case 3:
                            $resultado = 60;
                            break;
                        case 4:
                            $resultado = 70;
                            break;
                        case 5:
                            $resultado = 80;
                            break;
                        case 6:
                            $resultado = 100;
                            break;
                        default:
                            $resultado = 0;
                            break;
                    }
                }
                
                if($tipo == 'combo_margen')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'Poco menos del 50%';
                            break;
                        case 2:
                            $resultado = 'El 50%';
                            break;
                        case 3:
                            $resultado = 'Poco más del 50%';
                            break;
                        case 4:
                            $resultado = 'Mucho más del 50%';
                            break;
                        case 5:
                            $resultado = 'Casi el 100%';
                            break;
                        case 6:
                            $resultado = 'El 100%';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'brm')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'Bueno';
                            break;
                        case 2:
                            $resultado = 'Regular';
                            break;
                        case 3:
                            $resultado = 'Malo';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'prospecto_evaluacion')
		{
                    switch ($data) {              
                        case 0:
                            $resultado = 'No Evaluado';
                            break;
                        case 1:
                            $resultado = 'Aprobado';
                            break;
                        case 2:
                            $resultado = 'Rechazado';
                            break;
                        case 99:
                            $resultado = 'Devolver al Oficial de Negocios';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'grado_interes')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'Bajo';
                            break;
                        case 2:
                            $resultado = 'Medio';
                            break;
                        case 3:
                            $resultado = 'Alto';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'producto_opcion')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'I';
                            break;
                        case 2:
                            $resultado = 'C';
                            break;
                        case 3:
                            $resultado = 'P';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'categoria_mercaderia')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'MP';
                            break;
                        case 2:
                            $resultado = 'PP';
                            break;
                        case 3:
                            $resultado = 'PT';
                            break;
                        case 4:
                            $resultado = 'Mercadería';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'extension_ci')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'CH.';
                            break;
                        case 2:
                            $resultado = 'LP.';
                            break;
                        case 3:
                            $resultado = 'CB.';
                            break;
                        case 4:
                            $resultado = 'OR.';
                            break;
                        case 5:
                            $resultado = 'PT.';
                            break;
                        case 6:
                            $resultado = 'TJ.';
                            break;
                        case 7:
                            $resultado = 'SC.';
                            break;
                        case 8:
                            $resultado = 'BE.';
                            break;
                        case 9:
                            $resultado = 'PD.';
                            break;
                        case 10:
                            $resultado = 'EXT';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'icono_categoria')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = ' <i class="fa fa-user-circle-o" aria-hidden="true"></i> ';
                            break;
                        case 2:
                            $resultado = ' <i class="fa fa-users" aria-hidden="true"></i> ';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'icono_rubro')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = ' <i class="fa fa-industry" aria-hidden="true"></i> ';
                            break;
                        case 2:
                            $resultado = ' <i class="fa fa-suitcase" aria-hidden="true"></i> ';
                            break;
                        case 3:
                            $resultado = ' <i class="fa fa-shopping-basket" aria-hidden="true"></i> ';
                            break;
                        case 4:
                            $resultado = ' <i class="fa fa-truck" aria-hidden="true"></i> ';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'frecuencia_dias')
		{
                    switch ($data) {              
                        case 0:
                            $resultado = 'Irregular';
                            break;
                        case 1:
                            $resultado = 'Diario';
                            break;
                        case 7:
                            $resultado = 'Semanal';
                            break;
                        case 15:
                            $resultado = 'Quincenal';
                            break;
                        case 30:
                            $resultado = 'Mensual';
                            break;
                        case 60:
                            $resultado = 'Bimensual';
                            break;
                        case 90:
                            $resultado = 'Trimestral';
                            break;
                        case 120:
                            $resultado = 'Cuatrimestral';
                            break;
                        case 150:
                            $resultado = 'Quinquemestre';
                            break;
                        case 180:
                            $resultado = 'Semestral';
                            break;
                        case 360:
                            $resultado = 'Anual';
                            break;
                        case 720:
                            $resultado = '2 Años';
                            break;
                        case 1080:
                            $resultado = '3 Años';
                            break;
                        case 1440:
                            $resultado = '4 Años';
                            break;
                        case 1800:
                            $resultado = '5 Años';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'nombre_rubro')
		{
                    // Paso 1: Se guarda en un array el listado completo de actividaes
                    
                    // Listado de Servicios
                    $arrCampana = $this->mfunciones_logica->ObtenerCampana($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrCampana);
                    
                    $arrListado = array();
                    
                    if(isset($arrCampana[0]))
                    {
                        $resultado = $arrCampana[0]['camp_nombre'];
                    }
                    else
                    {
                        $resultado = $this->lang->line('TablaNoRegistrosMinimo');
                    }
                }
                
                if($tipo == 'color_rubro')
		{
                    // Paso 1: Se guarda en un array el listado completo de actividaes
                    
                    // Listado de Servicios
                    $arrCampana = $this->mfunciones_logica->ObtenerCampana($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrCampana);
                    
                    $arrListado = array();
                    
                    if(isset($arrCampana[0]))
                    {
                        $resultado = $arrCampana[0]['camp_color'];
                    }
                    else
                    {
                        $resultado = $this->lang->line('TablaNoRegistrosMinimo');
                    }
                }
                
                if($tipo == 'form_campanas')
		{
                    // Paso 1: Se guarda en un array el listado completo de actividaes
                    
                    // Listado de Servicios
                    $arrCampana = $this->mfunciones_logica->ObtenerCampana(-1);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrCampana);
                    
                    $arrListado = array();
                    
                    if(isset($arrCampana[0]))
                    {
                        foreach ($arrCampana as $key => $value) 
                        {
                            $arrListado[] = array(
                                'label' => $value["camp_nombre"],
                                'value' => $value["camp_id"]
                            );
                        }
                    }
                    
                    $resultado = $arrListado;
                }
                
                if($tipo == 'form_actividades')
		{
                    // Paso 1: Se guarda en un array el listado completo de actividaes
                    
                    // Listado de Servicios
                    $arrActividades = $this->mfunciones_logica->ObtenerActividades(-1);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrActividades);
                    
                    // Paso 2: Se guarda en un array las actiidades seleccionados por el registro
                    
                    // Listado de Servicios/Productos Seleccionados
                    $arrActividadesLead = $this->mfunciones_logica->ObtenerDetalleProspecto_actividades($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrActividadesLead);
                    
                    $arrListado = array();
                    
                    if(isset($arrActividades[0]))
                    {
                        foreach ($arrActividades as $key => $value) 
                        {
                            if(is_int(array_search($value["act_id"], array_column($arrActividadesLead, 'act_id'))))
                            {
                                $existe_producto = 'true';
                            }
                            else
                            {
                               $existe_producto = 'false';
                            }
                            
                            $arrListado[] = array(
                                'label' => $value["act_detalle"],
                                'value' => $value["act_id"],
                                'selected' => $existe_producto
                            );
                        }
                    }
                    
                    $resultado = $arrListado;
                }
                
                if($tipo == 'form_productos')
		{
                    // Paso 1: Se guarda en un array el listado completo de productos
                    
                    // Listado de Servicios
                    $arrServicios = $this->mfunciones_logica->ObtenerServicio(-1);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);
                    
                    // Paso 2: Se guarda en un array los productos seleccionados por el registro
                    
                    // Listado de Servicios/Productos Seleccionados
                    $arrServiciosLead = $this->mfunciones_logica->ObtenerDetalleProspecto_servicios($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServiciosLead);
                    
                    $arrListado = array();
                    
                    if(isset($arrServicios[0]))
                    {
                        foreach ($arrServicios as $key => $value) 
                        {
                            if(is_int(array_search($value["servicio_id"], array_column($arrServiciosLead, 'servicio_id'))))
                            {
                                $existe_producto = 'true';
                            }
                            else
                            {
                               $existe_producto = 'false';
                            }
                            
                            $arrListado[] = array(
                                'label' => $value["servicio_detalle"],
                                'value' => $value["servicio_id"],
                                'selected' => $existe_producto
                            );
                        }
                    }
                    
                    $resultado = $arrListado;
                }
                
                if($tipo == 'lead_actividades')
		{
                    $aux = '';
                    
                    // Listado de Servicios
                    $arrServicios = $this->mfunciones_logica->ObtenerDetalleProspecto_actividades($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);

                    if(isset($arrServicios[0]))
                    {
                        foreach ($arrServicios as $key => $value) 
                        {
                            $aux .= ' <i class="fa fa-dot-circle-o" aria-hidden="true"></i> ' . $value["act_detalle"];
                            $aux .= "<br />";
                        }                                
                    }
                    else
                    {
                        $aux = $this->lang->line('TablaNoRegistrosMinimo');
                    }
                    
                    $resultado = $aux;
                }
                
                if($tipo == 'lead_actividades_plain')
		{
                    $aux = '';
                    
                    // Listado de Servicios
                    $arrServicios = $this->mfunciones_logica->ObtenerDetalleProspecto_actividades($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);

                    if(isset($arrServicios[0]))
                    {
                        foreach ($arrServicios as $key => $value) 
                        {
                            $aux .= ' - ' . $value["act_detalle"];
                            $aux .= "<br />";
                        }                                
                    }
                    else
                    {
                        $aux = $this->lang->line('TablaNoRegistrosMinimo');
                    }
                    
                    $resultado = $aux;
                }
                
                if($tipo == 'lead_productos_plain')
		{
                    $aux = '';
                    
                    // Listado de Servicios
                    $arrServicios = $this->mfunciones_logica->ObtenerDetalleProspecto_servicios($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);

                    if(isset($arrServicios[0]))
                    {
                        foreach ($arrServicios as $key => $value) 
                        {
                            $aux .= ' - ' . $value["servicio_detalle"];
                            $aux .= "<br />";
                        }                                
                    }
                    else
                    {
                        $aux = $this->lang->line('TablaNoRegistrosMinimo');
                    }
                    
                    $resultado = $aux;
                }
                
                if($tipo == 'lead_productos')
		{
                    $aux = '';
                    
                    // Listado de Servicios
                    $arrServicios = $this->mfunciones_logica->ObtenerDetalleProspecto_servicios($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);

                    if(isset($arrServicios[0]))
                    {
                        foreach ($arrServicios as $key => $value) 
                        {
                            $aux .= ' <i class="fa fa-dot-circle-o" aria-hidden="true"></i> ' . $value["servicio_detalle"];
                            $aux .= "<br />";
                        }                                
                    }
                    else
                    {
                        $aux = $this->lang->line('TablaNoRegistrosMinimo');
                    }
                    
                    $resultado = $aux;
                }
                
                if($tipo == 'campana_productos_tabs')
		{
                    $aux = array();
                    
                    // Listado de Servicios
                    $arrServicios = $this->mfunciones_logica->ObtenerServiciosCampana($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);

                    if(isset($arrServicios[0]))
                    {
                        foreach ($arrServicios as $key => $value) 
                        {
                            array_push($aux, $value["servicio_tab"]);
                        }                                
                    }
                    else
                    {
                        $aux = $this->lang->line('TablaNoRegistrosMinimo');
                    }
                    
                    $resultado = $aux;
                }
                
                if($tipo == 'campana_productos_plain')
		{
                    $aux = '';
                    
                    // Listado de Servicios
                    $arrServicios = $this->mfunciones_logica->ObtenerServiciosCampana($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);

                    if(isset($arrServicios[0]))
                    {
                        foreach ($arrServicios as $key => $value) 
                        {
                            $aux .= ' - ' . $value["servicio_detalle"];
                            $aux .= "<br />";
                        }                                
                    }
                    else
                    {
                        $aux = $this->lang->line('TablaNoRegistrosMinimo');
                    }
                    
                    $resultado = $aux;
                }
                
                if($tipo == 'campana_productos')
		{
                    $aux = '';
                    
                    // Listado de Servicios
                    $arrServicios = $this->mfunciones_logica->ObtenerServiciosCampana($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);

                    if(isset($arrServicios[0]))
                    {
                        foreach ($arrServicios as $key => $value) 
                        {
                            $aux .= ' <i class="fa fa-dot-circle-o" aria-hidden="true"></i> ' . $value["servicio_detalle"];
                            $aux .= "<br />";
                        }                                
                    }
                    else
                    {
                        $aux = $this->lang->line('TablaNoRegistrosMinimo');
                    }
                    
                    $resultado = $aux;
                }
                
                if($tipo == 'empresa_acciones')
		{
                    if($data == "")
                    {
                        $resultado = 'No se registraron acciones';
                    }
                    else
                    {
                        $aux = '';
                        
                        $separador = '<br /> - ';
                        
                        $arrData = explode(',', $data);
                        
                        foreach ($arrData as $key => $value) 
                        {
                            switch ($value) {              
                                case 1:
                                    $aux .= $separador . 'Llamada a la empresa para buscar referencia';
                                    break;
                                case 2:
                                    $aux .= $separador . 'Dejar nota de contacto';
                                    break;
                                case 3:
                                    $aux .= $separador . 'Formulario de constancia de visita llenado';
                                    break;
                                case 4:
                                    $aux .= $separador . 'Planilla de constancia de verificación y georrefenciación';
                                    break;
                                default:
                                    $aux .= '';
                                    break;
                            }
                        }
                        
                        $resultado = $aux;
                    }
                    
                }
                
                if($tipo == 'tipo_perfil_app_singular')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'Oficial de Negocio';
                            break;
                        case 2:
                            $resultado = 'Registro Externo';
                            break;
                        case 3:
                            $resultado = 'Normalizador/Cobrador';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'tipo_perfil_app')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'Oficiales de Negocio';
                            break;
                        case 2:
                            $resultado = 'Registro Externo';
                            break;
                        case 3:
                            $resultado = 'Normalizadores/Cobradores';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'form_deseable')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'Deseable';
                            break;
                        case 2:
                            $resultado = 'De Precaución';
                            break;
                        case 3:
                            $resultado = 'No Deseable';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'accion_catalogo')
		{
                    switch ($data) {              
                        case 0:
                            $resultado = 'Creación del Registro';
                            break;
                        case 1:
                            $resultado = 'Derivar Instancia';
                            break;
                        case 2:
                            $resultado = 'Observar';
                            break;
                        case 3:
                            $resultado = 'VoBo Cumplimiento';
                            break;
                        case 4:
                            $resultado = 'VoBo Legal';
                            break;
                        case 5:
                            $resultado = 'Aprobar Insertar en Core';
                            break;
                        case 6:
                            $resultado = 'Generar Excepción';
                            break;
                        case 7:
                            $resultado = 'Rechazar';
                            break;
                        case 8:
                            $resultado = 'Entrega de Servicio';
                            break;
                        case 9:
                            $resultado = 'Registrar Información';
                            break;
                        case 10:
                            $resultado = 'Actualizar Actividad Principal';
                            break;
                        case 11:
                            $resultado = 'Baja de Registro';
                            break;
                        case 12:
                            $resultado = 'Cliente Cerrado';
                            break;
                        case 13:
                            $resultado = 'Cliente Evaluado';
                            break;
                        case 14:
                            $resultado = 'Versión Generada';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'ci_pertenece')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'Propietario';
                            break;
                        case 2:
                            $resultado = 'Representante Legal';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'evaluacion_doc')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'No Aplica';
                            break;
                        case 2:
                            $resultado = 'Adjunto en File';
                            break;
                        case 3:
                            $resultado = 'Requisito con Excepción';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
                }
                
                if($tipo == 'estado_actual')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'Creado';
                            break;                
                        case 1:
                            $resultado = 'En Pre-Revisión Cumplimiento';
                            break;
                        case 2:
                            $resultado = 'Completado Pre-Revisión Cumplimiento';
                            break;
                        case 3:
                            $resultado = 'Aprobado (en el flujo)';
                            break;
                        case 4:
                            $resultado = 'Afiliado';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
                
                if($tipo == 'excepcion')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'Sin excepción';
                            break;                
                        case 1:
                            $resultado = 'Excepción Generada';
                            break;
                        case 2:
                            $resultado = 'Excepción Aprobadaa';
                            break;
                        case 3:
                            $resultado = 'Excepción Rechazada';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
                
                if($tipo == 'excepcion_estado')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'Normal';
                            break;                
                        case 1:
                            $resultado = 'Excepción Generada';
                            break;
                        case 2:
                            $resultado = 'Excepción Aprobadaa';
                            break;
                        case 3:
                            $resultado = 'Excepción Rechazada';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
                
		if($tipo == 'parent')
		{
                    if($data == '-1')
                    {
                            $resultado = '<i>Ninguno</i>';
                    }
                    else
                    {                 
                        $arrResultado1 = $this->mfunciones_logica->ObtenerDatosCatalogo($data);
                        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                        if (isset($arrResultado1[0])) 
                        {
                            $resultado = $arrResultado1[0]['catalogo_tipo_codigo'] . ' | ' . $arrResultado1[0]['catalogo_descripcion'];                   
                        }
                        else 
                        {
                            $resultado = '--';
                        }

                    }
		}
		
		if($tipo == 'activo')
		{
                    switch ($data) {
                        case 0:
                            $resultado = $this->lang->line('Catalogo_activo1');                   
                            break;                
                        case 1:
                            $resultado = $this->lang->line('Catalogo_activo2');
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
                
                if($tipo == 'si_no')
		{
                    switch ($data) {
                        case 0:
                            $resultado = $this->lang->line('Catalogo_no');                   
                            break;                
                        case 1:
                            $resultado = $this->lang->line('Catalogo_si');
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
		
		if($tipo == 'empresa_categoria')
		{
                    switch ($data) {
                        case 1:
                            $resultado = 'Comercio';                   
                            break;                
                        case 2:
                            $resultado = 'Establecimiento/Sucursal';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
		
		if($tipo == 'tipo_observacion')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'Sin Observación';                   
                            break;                
                        case 1:
                            $resultado = 'Observación Cumplimiento';
                            break;
                        case 2:
                            $resultado = 'Observación Legal';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
		
		if($tipo == 'consolidado')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'No Consolidado';                   
                            break;                
                        case 1:
                            $resultado = 'Consolidado';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
		
		if($tipo == 'estado_observacion')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'Solucionado/Inactivo';                   
                            break;                
                        case 1:
                            $resultado = 'Activo';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
                
                if($tipo == 'estado_observacion_corto')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'Solucionado';                   
                            break;                
                        case 1:
                            $resultado = 'Activo';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
		
		if($tipo == 'estado_mantenimiento')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'Pendiente';                   
                            break;                
                        case 1:
                            $resultado = 'Completado';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
		
		if($tipo == 'tipo_visita')
		{
                    switch ($data) {
                        case 1:
                            $resultado = 'Lead';                   
                            break;                
                        case 2:
                            $resultado = 'Mantenimiento';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
		
		if($tipo == 'entregado')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'Confirmación Pendiente';                   
                            break;                
                        case 1:
                            $resultado = 'Entrega del Servicio Confirmada';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
		
		if($tipo == 'se_envia')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'No se Envía';                   
                            break;                
                        case 1:
                            $resultado = 'Se Envía';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
                
                if($tipo == 'estado_solicitud')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'Pendiente';                   
                            break;                
                        case 1:
                            $resultado = 'Aprobado';
                            break;
                        default:
                            $resultado = 'Rechazado';
                            break;
                    }
		}
		
		return($resultado);
	}
	
	function GetValorCatalogoDB($data, $tipo) {
		
		$this->load->model('mfunciones_logica');
	
		if($tipo == 'tipo_persona')
		{
                    $arrResultado1 = $this->mfunciones_logica->ObtenerDetalleCatalogoTipo($data);
                    $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                    if (isset($arrResultado1[0])) 
                    {                
                        $resultado = $arrResultado1[0]['tipo_persona_nombre'];
                    } 
                    else 
                    {
                        $resultado = 'No Corresponde';
                    }
		}
		else
		{
                    if($tipo == 'cI_lugar_emisionoextension' && str_replace(' ', '', $data) == '')
                    {
                        return('');
                    }
                    
                    $arrResultado1 = $this->mfunciones_logica->ObtenerDetalleCatalogo($data, $tipo);
                    $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                    if (isset($arrResultado1[0])) 
                    {
                            $resultado = $arrResultado1[0]['catalogo_descripcion'];
                    } 
                    else 
                    {
                            $resultado = 'No Registrado';
                    }
		}
		
		return($resultado);
	}
    
    function ListaEjecutivosUsuariosRegion($tipo_ejecutivo=1)
    {
        $this->load->model('mfunciones_logica');
        
        $arrEjecutivo_aux = $this->mfunciones_logica->ObtenerEjecutivo(-1, $tipo_ejecutivo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEjecutivo_aux);

        if (isset($arrEjecutivo_aux[0]))
        {
            $i = 0;

            foreach ($arrEjecutivo_aux as $key => $value) {

                $carga_laboral = $this->mfunciones_generales->GetCargaLaboralEjecutivo($value["ejecutivo_id"], $tipo_ejecutivo);

                $item_valor = array(
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "usuario_id" => $value["usuario_id"],
                    "usuario_nombre" => $value["usuario_nombre"],
                    "estructura_regional_nombre" => $value["estructura_regional_nombre"],
                    "carga_laboral" => $carga_laboral->cantidad_texto
                );
                $arrEjecutivo[$i] = $item_valor;

                $i++;
            }

            // Se agrupa el listado y construye el SELECT

            $arrEjecutivo = $this->mfunciones_generales->ArrGroupBy($arrEjecutivo, 'estructura_regional_nombre');

            $arrEjecutivo_html = '<select id="catalogo_parent" name="catalogo_parent">';
            $arrEjecutivo_html .= '<option value="-1"> --Seleccionar-- </option>';


            foreach ($arrEjecutivo as $key => $values) {

                $arrEjecutivo_html .= '<optgroup style="background-color: #f3f3f3; color: #000000;" label="Agencia: ' . $key . '">';

                foreach ($values as $value) {

                    $arrEjecutivo_html .= '<option value="'.$value['usuario_id'].'" style="background-color: #ffffff; color: #000000;"> → ' . $value['usuario_nombre'] . ' </option>';
                    $arrEjecutivo_html .= '<option value="'.$value['usuario_id'].'" style="font-style: italic; background-color: #ffffff; color: #888888; font-size: 0.80em;" disabled>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $value['carga_laboral'] . ' </option>';

                }

                $arrEjecutivo_html .= '</optgroup>';
            }

            $arrEjecutivo_html .= '</select>';

        }
        else 
        {
            $arrEjecutivo[0] = $arrEjecutivo_aux;

            $arrEjecutivo_html = $this->lang->line('regionaliza_TablaNoRegistros');
        }
        
        return $arrEjecutivo_html;
    }
    
    function GetCargaLaboralEjecutivo($codigo_ejecutivo, $tipo_ejecutivo=1) {
        
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_microcreditos');
        
        $resultado = new stdClass();
        
        $resultado->cantidad_prospectos = 0;
        $resultado->cantidad_onboarding = 0;
        $resultado->cantidad_mantenimientos = 0;
        $resultado->cantidad_solcred = 0;
        $resultado->cantidad_texto = 'No válido';
        
        switch ((int)$tipo_ejecutivo) {
            
            // Normalizador/Cobrador
            case 3:

                $this->load->model("mfunciones_cobranzas");
                
                // Listado de los onboarding solicitud de crédito
                $arrResultado4 = $this->mfunciones_cobranzas->ObtenerTotalRegistros($codigo_ejecutivo);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado4);
                $cantidad_norm = number_format($arrResultado4[0]['total_norm'], 0, ',', '.');

                $resultado->cantidad_texto = 'Pendientes: ' . $cantidad_norm . ' Caso(s)';
                
                break;

            default:
                
                // Listado de los prospectos
                $arrResultado1 = $this->mfunciones_logica->ObtenerBandejaProspectos_Carga($codigo_ejecutivo, 0);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
                $cantidad_prospectos = number_format(count($arrResultado1), 0, ',', '.');

                // Listado de los onboarding cuenta digital
                $arrResultado2 = $this->mfunciones_logica->ObtenerBandejaProspectos_Carga($codigo_ejecutivo, 1);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);
                $cantidad_onboarding = number_format(count($arrResultado2), 0, ',', '.');

                // Listado de los mantenimientos
                $arrResultado3 = $this->mfunciones_logica->ObtenerBandejaMantenimientos($codigo_ejecutivo, 0);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
                $cantidad_mantenimientos = number_format(count($arrResultado3), 0, ',', '.');

                // Listado de los onboarding solicitud de crédito
                $arrResultado4 = $this->mfunciones_microcreditos->ObtenerTotalSolCred($codigo_ejecutivo);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado4);
                $cantidad_solcred = number_format($arrResultado4[0]['total_solcred'], 0, ',', '.');

                $resultado->cantidad_prospectos = $cantidad_prospectos;
                $resultado->cantidad_onboarding = $cantidad_onboarding;
                $resultado->cantidad_mantenimientos = $cantidad_mantenimientos;
                $resultado->cantidad_solcred = $cantidad_solcred;
                $resultado->cantidad_texto = 'Pendientes: ' . $cantidad_prospectos . ' Lead(s) | ' . $cantidad_mantenimientos . ' Mant. | ' . $cantidad_solcred . ' Solicitud(es) Créd. | ' .  $cantidad_onboarding . ' Solicitud(es) Onb.';
                
                break;
        }
        
                
        return $resultado;
    }
    
    function UsuarioActualizarFechaLogin($data) {
        
        $this->load->model('mfunciones_logica');
        
        // Se registra la fecha del Login
	$fecha_login = date('Y-m-d H:i:s');
        
        $this->mfunciones_logica->UsuarioActualizarFechaLogin($fecha_login, $data);  
    }
        
    function getUltimoAcceso($data) {
        
        $fecha = $this->getFormatoFechaD_M_Y_H_M($data);
        
        if($fecha == '30/11/-0001 00:00' || $fecha == '01/01/1500 00:00')
        {
            $resultado = "DÍA DE HOY";
        }
        else
        {
            $resultado = $fecha;
        }
        
        return($resultado);
    }
    
    function getDiasPassword($data, $tipo) {
        
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerDatosConf_Credenciales();
        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if($tipo == 'max'){ $dias_cambio_pass = $arrResultado[0]['conf_duracion_max']; }
        
        if($tipo == 'min'){ $dias_cambio_pass = $arrResultado[0]['conf_duracion_min']; }
        
        $fecha_pass = strtotime($this->getFormatoFechaDateTime($data));
        
        $fecha_actual = strtotime(date('Y-m-d H:i:s'));
		
        $diferencia_dias = floor(($fecha_actual - $fecha_pass) / (60 * 60 * 24));
        
        if((int)$diferencia_dias < 0)
        { 
            $resultado = 0;
        }
        elseif ($tipo == 'max')
        {
            if (($dias_cambio_pass - $diferencia_dias) < 0)
            {
                $resultado = 0;
            }
            else
            {
                $resultado = $dias_cambio_pass - $diferencia_dias;
            }
        }
        else
        {
            if (($diferencia_dias - $dias_cambio_pass) < 0)
            {
                $resultado = 0;
            }
            else
            {
                $resultado = ($diferencia_dias - $dias_cambio_pass) + 1;
            }
        }
		
        return($resultado);
    }
    
    function ListadoMenu($codigo_rol) {
        
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_generales');

        // Se busca en la base de datos el listado de menus que pueden acceder al sistema de acuerdo al rol del usuario
        $arrMenu= $this->mfunciones_logica->ObtenerMenuPorRol($codigo_rol);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrMenu);
                
        return $arrMenu;        
    }
    
    function RequisitosFortalezaPassword() {
        
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerDatosConf_Credenciales();        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);                
        
        $conf_credenciales_long_min = $arrResultado[0]["conf_long_min"];
        $conf_credenciales_long_max = $arrResultado[0]["conf_long_max"];
        $conf_credenciales_req_upper = $arrResultado[0]["conf_req_upper"];
        $conf_credenciales_req_num = $arrResultado[0]["conf_req_num"];
        $conf_credenciales_req_esp = $arrResultado[0]["conf_req_esp"];
        
        $mensaje_error = "La contraseña debe cumplir: <br /><br /> - $conf_credenciales_long_min Caractéres Mínimo <br /> - $conf_credenciales_long_max Caractéres Máximo";
        
        if ($conf_credenciales_req_upper == 1) { $mensaje_error .= "<br /> - Almenos 1 Mayúscula"; }
        if ($conf_credenciales_req_num == 1) { $mensaje_error .= "<br /> - Almenos 1 Número"; }
        if ($conf_credenciales_req_esp == 1) { $mensaje_error .= "<br /> - Almenos 1 Caractér Especial (!@#$%&/¿?¡+)"; }
                
        $resultado = $mensaje_error;
        
        return($resultado);
    }
    
    function VerificaFortalezaPassword($data) {
        
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerDatosConf_Credenciales();        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);                
        
        $conf_credenciales_long_min = $arrResultado[0]["conf_long_min"];
        $conf_credenciales_long_max = $arrResultado[0]["conf_long_max"];
        $conf_credenciales_req_upper = $arrResultado[0]["conf_req_upper"];
        $conf_credenciales_req_num = $arrResultado[0]["conf_req_num"];
        $conf_credenciales_req_esp = $arrResultado[0]["conf_req_esp"];
        
        $regex = '/^';
        if ($conf_credenciales_req_upper == 1) { $regex .= '(?=.*[A-Z])'; }                         // Almenos 1 Mayúscula
        if ($conf_credenciales_req_num == 1) { $regex .= '(?=.*\d)'; }                              // Almenos 1 Número
        if ($conf_credenciales_req_esp == 1) { $regex .= '(?=.*[!@#$%^&+=])'; }                     // Almenos 1 Caractér Especial
        $regex .= '.{' . $conf_credenciales_long_min . ',' . $conf_credenciales_long_max . '}$/';   // Debe cumplir el mínimo y máximo de caractéres
                
        if(preg_match($regex, $data)) 
        {
            $resultado = "ok";
        } 
        else 
        {
            $resultado = $this->RequisitosFortalezaPassword();
        }
        
        return($resultado);
    }
    
    // Funciones de modelado de jerarquía TREE
    
    function createTree(&$list, $parent){
        $tree = array();
        foreach ($parent as $k=>$l){
            if(isset($list[$l['etapa_id']])){
                $l['children'] = $this->createTree($list, $list[$l['etapa_id']]);
            }
            $tree[] = $l;
        } 
        return $tree;
    }
    
    function menu($arr) {
        echo "<ul>";
        foreach ($arr as $val) {

            if (!empty($val['children'])) {
                echo "<li> <span data-balloon-length='medium' data-balloon='" . $val['etapa_detalle'] . "' data-balloon-pos='right'><a onclick=\"Ajax_CargarAccion_EditarEtapa(" . $val['etapa_id'] . ", " . $val['etapa_categoria'] . ");\"> " . $val['etapa_nombre'] . ' <br /> (' . $val['rol_nombre'] . ') </a></span>';
                $this->menu($val['children']);
                echo "</li>";
            } else {
                echo "<li> <span data-balloon-length='medium' data-balloon='" . $val['etapa_detalle'] . "' data-balloon-pos='right'><a onclick=\"Ajax_CargarAccion_EditarEtapa(" . $val['etapa_id'] . ", " . $val['etapa_categoria'] . ");\"> " . $val['etapa_nombre'] . ' <br /> (' . $val['rol_nombre'] . ') </a></span> </li>';
            }
        }
        echo "</ul>";
    }
    
    
    // Funciones de modelado de jerarquía TREE FIN
    
    function GetDatosEmpresaCorreo($codigo_prospecto)
    {        
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        // BUSCAR LA INFORMACIÓN DE LA EMPRESA        
        // Listado Detalle Empresa
        $arrResultado1 = $this->mfunciones_logica->ObtenerDetalleEmpresaCorreo($codigo_prospecto);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
        
        return $arrResultado1;        
    }
    
    function ObtenerUsuarioAuditoria($estructura_id, $tipo_identificador)
    {
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        $nombre_usuario = '';
        
        if(isset($_SESSION["session_informacion"]["login"]))
        {
            $nombre_usuario = $_SESSION["session_informacion"]["login"];
        }
        else
        {
            if($tipo_identificador == 'prospecto' || $tipo_identificador == 'ejecutivo')
            {
                $arrUsuario = $this->mfunciones_logica->getUsuarioCriterio($tipo_identificador, $estructura_id);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrUsuario);
                
                if(isset($arrUsuario[0]))
                {
                    $nombre_usuario = $arrUsuario[0]['usuario_user'];
                }
            }
        }
        
        if($nombre_usuario == '')
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        
        return $nombre_usuario;
    }
    
    function ListaEjecutivosRegion($codigo_ejecutivo=-1, $tipo_ejecutivo=1)
    {
        $this->load->model('mfunciones_logica');
        
        $arrEjecutivo_aux = $this->mfunciones_logica->ObtenerEjecutivo(-1, $tipo_ejecutivo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEjecutivo_aux);

        if (isset($arrEjecutivo_aux[0]))
        {
            $i = 0;

            foreach ($arrEjecutivo_aux as $key => $value) {

                $carga_laboral = $this->mfunciones_generales->GetCargaLaboralEjecutivo($value["ejecutivo_id"]);

                $item_valor = array(
                    "ejecutivo_id" => $value["ejecutivo_id"],
                    "usuario_id" => $value["usuario_id"],
                    "usuario_nombre" => $value["usuario_nombre"],
                    "estructura_regional_nombre" => $value["estructura_regional_nombre"],
                    "carga_laboral" => $carga_laboral->cantidad_texto
                );
                $arrEjecutivo[$i] = $item_valor;

                $i++;
            }

            // Se agrupa el listado y construye el SELECT

            $arrEjecutivo = $this->mfunciones_generales->ArrGroupBy($arrEjecutivo, 'estructura_regional_nombre');

            $arrEjecutivo_html = '<select id="codigo_ejecutivo" name="codigo_ejecutivo">';
            $arrEjecutivo_html .= '<option value="-1"> --Seleccionar-- </option>';


            foreach ($arrEjecutivo as $key => $values) {

                $arrEjecutivo_html .= '<optgroup style="background-color: #f3f3f3; color: #000000;" label="Agencia: ' . $key . '">';

                foreach ($values as $value) {

                    if($codigo_ejecutivo == $value['ejecutivo_id'])
                    {
                        $arrEjecutivo_html .= '<option selected="selected" value="'.$value['ejecutivo_id'].'" style="background-color: #ffffff; color: #000000;"> → ' . $value['usuario_nombre'] . ' </option>';
                    }
                    else
                    {
                        $arrEjecutivo_html .= '<option value="'.$value['ejecutivo_id'].'" style="background-color: #ffffff; color: #000000;"> → ' . $value['usuario_nombre'] . ' </option>';
                    }
                    
                    
                    $arrEjecutivo_html .= '<option value="'.$value['ejecutivo_id'].'" style="font-style: italic; background-color: #ffffff; color: #888888; font-size: 0.80em;" disabled>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $value['carga_laboral'] . ' </option>';

                }

                $arrEjecutivo_html .= '</optgroup>';
            }

            $arrEjecutivo_html .= '</select>';

        }
        else 
        {
            $arrEjecutivo[0] = $arrEjecutivo_aux;

            $arrEjecutivo_html = $this->lang->line('regionaliza_TablaNoRegistros');
        }
        
        return $arrEjecutivo_html;
    }
    
    function getNombreUsuario($data) {

        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');

        $arrResultado = $arrResultado = $this->mfunciones_logica->ObtenerDatosUsuario($data);
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            return $arrResultado[0]['nombre_completo'];
        }
        else
        {
            return 'No Definido';
        }
    }
    
    function ObtenerSolicitanteData($codigo_prospecto, $campo)
    {        
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        // BUSCAR LA INFORMACIÓN DE LA EMPRESA        
        // Listado Detalle Empresa
        $arrResultado1 = $this->mfunciones_logica->GetSolicitanteData($codigo_prospecto, $campo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
        
        if (isset($arrResultado1[0])) 
        {
            return htmlspecialchars($arrResultado1[0][$campo]);
        }
        else
        {
            return 'No válido';
        } 
    }
    
    function ListaHabilitarUsuariosRegion($tipo_ejecutivo=1)
    {
        $this->load->model('mfunciones_logica');
        
        $arrEjecutivo_aux = $this->mfunciones_logica->HabilitarUsuariosEjecutivosCuenta(-1, $tipo_ejecutivo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrEjecutivo_aux);

        if (isset($arrEjecutivo_aux[0]))
        {
            $i = 0;

            foreach ($arrEjecutivo_aux as $key => $value) {
                
                $item_valor = array(
                    "usuario_id" => $value["usuario_id"],
                    "nombre_completo" => $value["nombre_completo"],
                    "estructura_regional_nombre" => $value["estructura_regional_nombre"]
                );
                $arrEjecutivo[$i] = $item_valor;

                $i++;
            }

            // Se agrupa el listado y construye el SELECT

            $arrEjecutivo = $this->mfunciones_generales->ArrGroupBy($arrEjecutivo, 'estructura_regional_nombre');

            $arrEjecutivo_html = '<select id="catalogo_parent" name="catalogo_parent">';
            $arrEjecutivo_html .= '<option value="-1"> --Seleccionar-- </option>';


            foreach ($arrEjecutivo as $key => $values) {

                $arrEjecutivo_html .= '<optgroup style="background-color: #f3f3f3; color: #000000;" label="Agencia: ' . $key . '">';

                foreach ($values as $value) {

                    $arrEjecutivo_html .= '<option value="'.$value['usuario_id'].'" style="background-color: #ffffff; color: #000000;"> → ' . $value['nombre_completo'] . ' </option>';
                }

                $arrEjecutivo_html .= '</optgroup>';
            }

            $arrEjecutivo_html .= '</select>';

        }
        else 
        {
            $arrEjecutivo[0] = $arrEjecutivo_aux;

            $arrEjecutivo_html = $this->lang->line('regionaliza_TablaNoRegistros_usuarioApp');
        }
        
        return $arrEjecutivo_html;
    }
    
    function HabilitarUsuariosEjecutivosCuenta($codigo)
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
    
    function GetDatosEmpresa($codigo_empresa)
    {        
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        // BUSCAR LA INFORMACIÓN DE LA EMPRESA        
        // Listado Detalle Empresa
        $arrResultado1 = $this->mfunciones_logica->ObtenerDetalleEmpresa($codigo_empresa);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
        
        return $arrResultado1;        
    }
    
    function ObtenerRegionUsuario($usuario_codigo)
    {
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerCodigoRegionUsuario($usuario_codigo) ;
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            return $arrResultado[0]['estructura_regional_id'];
        }
        else
        {
            return 0;
        }
    }
    
    function ObtenerNombreRegionUsuario($usuario_codigo)
    {
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerCodigoRegionUsuario($usuario_codigo) ;
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            return $arrResultado[0]['estructura_regional_nombre'];
        }
        else
        {
            return 0;
        }
    }
    
    function ObtenerDatosRegionUsuario($usuario_codigo)
    {
        $resultado = new stdClass();
        
        $resultado->estructura_regional_id = 0;
        $resultado->estructura_regional_nombre = 0;
        $resultado->estructura_regional_departamento = 0;
        $resultado->estructura_regional_ciudad = 0;
        $resultado->estructura_regional_estado = 0;
        
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerCodigoRegionUsuario($usuario_codigo) ;
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            $resultado->estructura_regional_id = $arrResultado[0]['estructura_regional_id'];
            $resultado->estructura_regional_nombre = $arrResultado[0]['estructura_regional_nombre'];
            $resultado->estructura_regional_departamento = $arrResultado[0]['estructura_regional_departamento'];
            $resultado->estructura_regional_ciudad = $arrResultado[0]['estructura_regional_ciudad'];
            $resultado->estructura_regional_estado = $arrResultado[0]['estructura_regional_estado'];
        }
        
        return $resultado;
    }
    
    function EnviarCorreo($plantilla, $destinatario_correo, $destinatario_nombre, $arrayParametros = "", $arrayDocumentos = "", $arrayConCopia = "", $arrayConCopiaOculta = "") {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $this->load->helper(array('form', 'url'));
        $this->load->library('mylibrary');
        
        ignore_user_abort(1); // Que continue aun cuando el usuario se haya ido
        
        // Dirección del Controlador LOGICA DE NEGOCIO
        $url = base_url() . "Correo/Enviar";
        //$url = "https://atc.redcetus.com/Correo/Enviar";
        
        // Se capturan los valores
        
        $param = array(
            'plantilla' => $plantilla,
            'destinatario_correo' => $destinatario_correo,
            'destinatario_nombre' => $destinatario_nombre,
            'arrayParametros' => $arrayParametros,
            'arrayDocumentos' => $arrayDocumentos,
            'arrayConCopia' => $arrayConCopia,
            'arrayConCopiaOculta' => $arrayConCopiaOculta,
            );
        
        $this->mylibrary->do_in_background($url, $param);
        
        return TRUE;
    }
    
    /*************** LECTOR QR - INICIO ****************************/
    
    function generate_uid($l = 8) {
        
        $unico = FALSE;
        
        while ($unico == FALSE) 
        {
            $str = "";
            for ($x = 0; $x < $l; $x++)
            {
                $str .= substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1); 
            }
            
            // Preguntar en la tabla si existe el ID, caso contrario vuelve a generarlo
            
            $unico = TRUE;
        }
        
        
        return $str;
    }
    
    function GeneratorQR($data){
        
        
        $img_path = FCPATH . "html_public/qr_image/";
        
        // -----------------------------------
        // Remove old QR
        // -----------------------------------

        list($usec, $sec) = explode(" ", microtime());
        $now = ((float)$usec + (float)$sec);

        $current_dir = @opendir($img_path);

        while ($filename = @readdir($current_dir))
        {
            if ($filename != "." and $filename != ".." and $filename != "index.html")
            {
                $name = str_replace(".png", "", $filename);

                if (($name + 57600) < $now)
                {
                    @unlink($img_path.$filename);
                }
            }
        }

        @closedir($current_dir);
        
        // Create QR Code
        
        $this->load->library('ciqrcode');
        $qr_image= $now . '.png';
        $params['data'] = $data;
        $params['level'] = 'H';
        $params['size'] = 8;
        $params['savename'] = $img_path . $qr_image;
        
        if($this->ciqrcode->generate($params))
        {
            return $qr_image;	
        }
        else
        {
            return FALSE;
        }
        
    }

/*************** LECTOR QR - FIN ****************************/
    
    function GeneraToken() {
        
        $token = sha1(uniqid(mt_rand(), false));
        return $token;
    }
    
    function EnviaCorreoVerificacion($tipo_visita, $solicitante_nombre, $solicitante_email, $identificador, $token) {
        
        // Se crea el URL para la confirmación de la Solicitud
        
        $url_confirmacion = site_url('Confirmar?visita=' . $tipo_visita . '&id=' . $identificador . '&token=' . $token);
                
        $correo_enviado = $this->EnviarCorreo('verificar_solicitud', $solicitante_email, $solicitante_nombre, $url_confirmacion, 0);

        if(!$correo_enviado)
        {
            return FALSE;
        }
        
        return TRUE;
        
    }
    
    function VisitaEnlaceCalendario($tipo_visita, $fecha_ini, $fecha_fin, $direccion)
    {
        $this->lang->load('general', 'castellano');
        
        // En el caso que no se haya definido la Zona Horaria
        if( ! ini_get('date.timezone') )
        {
            date_default_timezone_set("America/La_Paz");
        }

        $timezone  = +0; //(GMT -5:00) EST (U.S. & Canada)

        // Se convierten las fechas al formato del calendario

        $fecha_ini = $this->getFormatoFechaDateTime($fecha_ini);
        $fecha_fin = $this->getFormatoFechaDateTime($fecha_fin);
        
        $fecha_inicio = gmdate("Ymd\THis\Z", strtotime($fecha_ini) + 3600*($timezone+date("I")));
        $fecha_final = gmdate("Ymd\THis\Z", strtotime($fecha_fin) + 3600*($timezone+date("I")));

        $titulo = $this->lang->line('correo_calendario_titulo');

        // Dependiento del Tipo de Visita, se establece el Detalle del evento

        // 1 = Prospecto		2 = Mantenimiento
        if($tipo_visita == 1)
        {
            $detalle = $titulo . $this->lang->line('correo_calendario_afiliacion');
        }
        else
        {
            $detalle = $titulo . $this->lang->line('correo_calendario_mantenimiento');
        }

        // Se convierte los caractéres en el formato aceptado por la URL

        $titulo = rawurlencode($titulo);
        $detalle = rawurlencode($detalle);

        // No seteado
        $direccion_visita = rawurlencode($direccion);

        // Se construye el Enlace

        $enlace_calendario = '<a style="color: #f5811e; text-decoration: underline;" href="http://www.google.com/calendar/event?action=TEMPLATE&dates=' . $fecha_inicio . '%2F' . $fecha_final . '&text=' . $titulo . '&location=' . $direccion_visita . '&details=' . $detalle . '"> Agendar en Mi Calendario </a>';

        return $enlace_calendario;
    }
    
    function getDiasCalendario($start, $end)
    {
        $start = new DateTime($start);
        $end = new DateTime($end);

        return $end->diff($start)->format("%a");
    }
    
    function getDiasLaborales($start, $end) {
    
        // SE CALCULA EN HORAS
        
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $periodInterval = new DateInterval( "PT1H" );

        $period = new DatePeriod( $startDate, $periodInterval, $endDate );
        $count = 0;

        // Se obtienen los días te atención

        $this->load->model('mfunciones_logica');

        $arrResultado3 = $this->mfunciones_logica->ObtenerDatosConf_General();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

        $arrSemana = explode(",", $arrResultado3[0]['conf_atencion_dias']);

        $workingDays = $arrSemana; # formato = N (1 = Lunes, ...)
        $holidayDays = array(
                            '*-01-01',
                            '*-01-22',
                            '*-05-01',
                            '*-06-21',
                            '*-08-06',
                            '*-11-02',
                            '*-12-25',
                            '2018-02-12',
                            '2018-02-13',
                            '2018-03-30',
                            '2018-05-31',
                            '2019-03-04',
                            '2019-03-05',
                            '2019-04-19',
                            '2019-06-20',
                            '2020-02-24',
                            '2020-02-25',
                            '2020-04-10',
                            '2020-06-11',
                            '2021-02-15',
                            '2021-02-16',
                            '2021-04-02',
                            '2021-06-03',
                            '2022-02-28',
                            '2022-03-01',
                            '2022-04-15',
                            '2022-06-16',
                            '2023-02-20',
                            '2023-02-21',
                            '2023-04-07',
                            '2023-06-08',

            ); # Días festivos... es necesario configurar para mas adelante	

        // Se obtienen los Horarios de Atención

        $hora1_inicio_hora = (int)date('H', strtotime($arrResultado3[0]['conf_atencion_desde1']));
        $hora1_inicio_minuto = (int)date('i', strtotime($arrResultado3[0]['conf_atencion_desde1']));
        $hora1_fin_hora = (int)date('H', strtotime($arrResultado3[0]['conf_atencion_hasta1']));
        $hora1_fin_minuto = (int)date('i', strtotime($arrResultado3[0]['conf_atencion_hasta1']));

        $hora2_inicio_hora = (int)date('H', strtotime($arrResultado3[0]['conf_atencion_desde2']));
        $hora2_inicio_minuto = (int)date('i', strtotime($arrResultado3[0]['conf_atencion_desde2']));
        $hora2_fin_hora = (int)date('H', strtotime($arrResultado3[0]['conf_atencion_hasta2']));
        $hora2_fin_minuto = (int)date('i', strtotime($arrResultado3[0]['conf_atencion_hasta2']));

        foreach($period as $date)
        {

            $startofday1 = clone $date;
            $startofday1->setTime($hora1_inicio_hora, $hora1_inicio_minuto);
            $startofday2 = clone $date;
            $startofday2->setTime($hora1_fin_hora, $hora1_fin_minuto);

            $endofday1 = clone $date;
            $endofday1->setTime($hora2_inicio_hora, $hora2_inicio_minuto);
            $endofday2 = clone $date;
            $endofday2->setTime($hora2_fin_hora, $hora2_fin_minuto);

            if( ($date >= $startofday1 && $date < $startofday2) || ($date >= $endofday1 && $date < $endofday2))
            {
                if (!in_array($date->format('N'), $workingDays)) continue;
                if (in_array($date->format('Y-m-d'), $holidayDays)) continue;
                if (in_array($date->format('*-m-d'), $holidayDays)) continue;

                $count++;
            }
        }

        return $count;
    }

    public function getDiasLaboralesCache($start, $end) {

        // SE CALCULA EN HORAS

        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $periodInterval = new DateInterval( "PT1H" );

        $period = new DatePeriod( $startDate, $periodInterval, $endDate );
        $count = 0;

        // Se obtienen los días te atención

        if ($this->cache_dias_laborales=== null) {
            $this->load->model('mfunciones_logica');
            $this->cache_dias_laborales = new stdClass();
            $arrResultado3 = $this->mfunciones_logica->ObtenerDatosConf_General();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
            $arrSemana = explode(",", $arrResultado3[0]['conf_atencion_dias']);
            $this->cache_dias_laborales->workingDays = $arrSemana; # formato = N (1 = Lunes, ...)
            $this->cache_dias_laborales->holidayDays = array(
                '*-01-01',
                '*-01-22',
                '*-05-01',
                '*-06-21',
                '*-08-06',
                '*-11-02',
                '*-12-25',
                '2018-02-12',
                '2018-02-13',
                '2018-03-30',
                '2018-05-31',
                '2019-03-04',
                '2019-03-05',
                '2019-04-19',
                '2019-06-20',
                '2020-02-24',
                '2020-02-25',
                '2020-04-10',
                '2020-06-11',
                '2021-02-15',
                '2021-02-16',
                '2021-04-02',
                '2021-04-02',
                '2021-06-03',
                '2022-02-28',
                '2022-03-01',
                '2022-04-15',
                '2022-06-16',
                '2023-02-20',
                '2023-02-21',
                '2023-04-07',
                '2023-06-08',
            ); # Días festivos... es necesario configurar para mas adelante

            // Se obtienen los Horarios de Atención


            $this->cache_dias_laborales->hora1_inicio_hora = (int)date('H', strtotime($arrResultado3[0]['conf_atencion_desde1']));
            $this->cache_dias_laborales->hora1_inicio_minuto = (int)date('i', strtotime($arrResultado3[0]['conf_atencion_desde1']));
            $this->cache_dias_laborales->hora1_fin_hora = (int)date('H', strtotime($arrResultado3[0]['conf_atencion_hasta1']));
            $this->cache_dias_laborales->hora1_fin_minuto = (int)date('i', strtotime($arrResultado3[0]['conf_atencion_hasta1']));

            $this->cache_dias_laborales->hora2_inicio_hora = (int)date('H', strtotime($arrResultado3[0]['conf_atencion_desde2']));
            $this->cache_dias_laborales->hora2_inicio_minuto = (int)date('i', strtotime($arrResultado3[0]['conf_atencion_desde2']));
            $this->cache_dias_laborales->hora2_fin_hora = (int)date('H', strtotime($arrResultado3[0]['conf_atencion_hasta2']));
            $this->cache_dias_laborales->hora2_fin_minuto = (int)date('i', strtotime($arrResultado3[0]['conf_atencion_hasta2']));

        }


        foreach($period as $date)
        {
            $startofday1 = clone $date;
            $startofday1->setTime($this->cache_dias_laborales->hora1_inicio_hora, $this->cache_dias_laborales->hora1_inicio_minuto);
            $startofday2 = clone $date;
            $startofday2->setTime($this->cache_dias_laborales->hora1_fin_hora, $this->cache_dias_laborales->hora1_fin_minuto);
            $endofday1 = clone $date;
            $endofday1->setTime($this->cache_dias_laborales->hora2_inicio_hora, $this->cache_dias_laborales->hora2_inicio_minuto);
            $endofday2 = clone $date;
            $endofday2->setTime($this->cache_dias_laborales->hora2_fin_hora, $this->cache_dias_laborales->hora2_fin_minuto);
            if( ($date >= $startofday1 && $date < $startofday2) || ($date >= $endofday1 && $date < $endofday2))
            {
                if (!in_array($date->format('N'), $this->cache_dias_laborales->workingDays)) continue;
                if (in_array($date->format('Y-m-d'), $this->cache_dias_laborales->holidayDays)) continue;
                if (in_array($date->format('*-m-d'), $this->cache_dias_laborales->holidayDays)) continue;
                $count++;
            }
        }

        return $count;
    }

    function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
    
    function TiempoEtapaProspecto($fecha_asignacion_etapa, $codigo_etapa) {
        
        $this->load->model('mfunciones_logica');

        $resultado = 0;
        
        // Paso 1: Se obtiene los datos de la etapa
        
        $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa($codigo_etapa);
        
        $tiempo_etapa = $arrEtapa[0]['etapa_tiempo'];
        
        // Paso 2: Se utiliza la función para obtener la diferencia
        
        // -> Para Horas
        $fecha_asignacion_etapa = date('Y-m-d H:i:s', strtotime($fecha_asignacion_etapa));
        $fecha_actual = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')));
        
        // -> Para Días
        // $fecha_asignacion_etapa = date('Y-m-d', strtotime($fecha_asignacion_etapa));
        // $fecha_actual = date('Y-m-d', strtotime(date('Y-m-d H:i:s')));
        
        $diferencia_dias = $this->getDiasLaborales($fecha_asignacion_etapa, $fecha_actual);
        
        // Paso 4: Se obtiene el porcentaje 
        
        $total_porcentaje = 100 - round(($diferencia_dias*100)/$tiempo_etapa);
        
        return $total_porcentaje;
        
    }
    
    function TiempoEtapaColor($codigo) {
        
        $icono = '<span class="tiempo_0" title="Atrasado"><i class="fa fa-flag" aria-hidden="true"></i> </span>';
        
        if($codigo > 50)
        {
            $icono = '<span class="tiempo_100" title="A tiempo"><i class="fa fa-flag" aria-hidden="true"></i> </span>';
        }        
        elseif($codigo >= 0)
        {
            $icono = '<span class="tiempo_50" title="Pendiente"><i class="fa fa-flag" aria-hidden="true"></i> </span>';
        }
        elseif($codigo < 0)
        {
            $icono = '<span class="tiempo_0" title="Atrasado"><i class="fa fa-flag" aria-hidden="true"></i> </span>';
        }
        
        return $icono;        
    }
    
    function TiempoEtapaResumen($arrRecibido) {
        
        $contador_100 = 0;
        $contador_50 = 0;
        $contador_0 = 0;
        
        if (isset($arrRecibido[0])) 
        {                        
            foreach ($arrRecibido as $key => $value) 
            {
                if($value["tiempo_etapa"] > 50)
                {
                    $contador_100++;
                }
                elseif($value["tiempo_etapa"] >= 0)
                {
                    $contador_50++;
                }
                elseif($value["tiempo_etapa"] < 0)
                {
                    $contador_0++;
                }
            }
        }
        
        $arrResultado[0] = array(
                    "contador_100" => $contador_100,
                    "contador_50" => $contador_50,
                    "contador_0" => $contador_0
        );
        
        return $arrResultado;
    }
    
    function GetProspectoRegionCodigo($prospecto_codigo) {
        
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerProspectoRegion($prospecto_codigo);
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            return $arrResultado[0]['estructura_regional_id'];
        }
        else
        {
            return 'Parámetro Invalido';
        }
        
    }
    
    /**** OBTENER LISTADO DE USUARIOS HABILITADOS QUE ESTÁN ASOCIADOS A UNA ETAPA AL CAMBIAR DE ETAPA EL PROSPECTO ****/
    
    function getListadoUsuariosEtapa($codigo_etapa, $codigo_region, $regionalizado=1) {
        
        // Paso 1: Se obtiene la Región del Registro
        $prospecto_region = $codigo_region;

        // Paso 2: Se obtiene el listado de Roles y Usuarios asignados a la Etapa
        $arrRolUsuarioEtapa = $this->mfunciones_logica->ObtenerListaEtapaRolAll($codigo_etapa);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRolUsuarioEtapa);

        $lista_usuario = '-1';
        $lista_rol = '-1';

        if (isset($arrRolUsuarioEtapa[0])) 
        {

            foreach ($arrRolUsuarioEtapa as $key => $value) 
            {
                if($value['tipo_id'] == 1) // <- Roles
                {
                    $lista_rol .= ', ' . $value['codigo'];
                }

                if($value['tipo_id'] == 2) // <- Usuarios
                {
                    $lista_usuario .= ', ' . $value['codigo'];
                }
            }

        }

        // Paso 3: Se obtiene el listado de Usuarios que sean parte de lo asignado a la Etapa, y que seán de la región del Prospecto

        $arrUsuarios = $this->mfunciones_logica->ObtenerUsuariosRegionNotificar($lista_rol, $lista_usuario, $prospecto_region);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrUsuarios);
        
        // Paso 4: Se crea un array auxiliar que identifique si las regiones que supervisa el usuario es parte de la region del registro
        
        $lst_resultado = array();
        
        if (isset($arrUsuarios[0])) 
        {
            $i = 0;
            
            foreach ($arrUsuarios as $key => $value) 
            {
                if($regionalizado == 1)
                {
                    // Se verifica si el usuario tiene supervisada la región del registro
                
                    $lista_region = $this->mfunciones_generales->getUsuarioRegion($value["usuario_id"]);

                    if(!in_array($prospecto_region, explode(", ", $lista_region->region_id)))
                    {
                        continue;
                    }
                }
                
                $item_valor = array(
                    "usuario_id" => $value["usuario_id"],
                    "estructura_regional_id" => $value["estructura_regional_id"],
                    "usuario_nombres" => $value["usuario_nombres"],
                    "usuario_app" => $value["usuario_app"],
                    "usuario_apm" => $value["usuario_apm"],
                    "usuario_email" => $value["usuario_email"]
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        }
        
        return $lst_resultado;
    }
    
    /**** REGISTRAR SEGUIMIENTO, ETAPA E HITO Y ENVIAR CORREO (último parámetro 1=Envio a etapas hijas    2=Envio a etapa específica ****/
    function SeguimientoHitoProspecto($prospecto_id, $etapa_nueva, $etapa_actual, $accion_usuario, $accion_fecha, $enviar_correo = 0) {
        
        $this->load->model('mfunciones_logica');
        
        /*** Actualizar Etapa del Prospecto ***/
        $this->mfunciones_logica->UpdateEtapaProspecto($etapa_nueva, $accion_usuario, $accion_fecha, $prospecto_id);
        
        $this->mfunciones_logica->HitoProspecto($prospecto_id, $etapa_nueva, $etapa_actual, $accion_usuario, $accion_fecha);
        
        // Si se indicó, se pregunta si se enviará el comercio
        // 1 = Se envía a las etapas Hijas de la Etapa Actual       2 = Se envía a una etapa en específico
        if($enviar_correo == 1)
        {
            $arrEtapa = $this->mfunciones_generales->ObteneRolHijoFlujo($etapa_actual);
                
            if (isset($arrEtapa[0]))
            {
                foreach ($arrEtapa as $key1 => $value1) 
                {
                    // 0 = No Envía Correo      1 = Sí Envía Correo
                    if($value1['etapa_notificar_correo'] == 1)
                    {
                        
                        $arrUsuariosNotificar = $this->mfunciones_generales->getListadoUsuariosEtapa($prospecto_id, $etapa_nueva);
                        
                        if (isset($arrUsuariosNotificar[0])) 
                        {

                            foreach ($arrUsuariosNotificar as $key => $value) 
                            {
                                $destinatario_nombre = $value['usuario_nombres'] . ' ' . $value['usuario_app'] . ' ' . $value['usuario_apm'];
                                $destinatario_correo = $value['usuario_email'];

                                // SE PROCEDE CON EL ENVÍO DE CORREO ELECTRÓNICO
                                $correo_enviado = $this->mfunciones_generales->EnviarCorreo('notificar_instancia', $destinatario_correo, $destinatario_nombre, $prospecto_id, 0);
                            }
                        }
                    }
                }
            }
        }
        
        // 1 = Se envía a las etapas Hijas de la Etapa Actual       2 = Se envía a una etapa en específico
        if($enviar_correo == 2)
        {
            $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa($etapa_nueva);

            if (isset($arrEtapa[0]))
            {
                foreach ($arrEtapa as $key1 => $value1) 
                {
                    // 0 = No Envía Correo      1 = Sí Envía Correo
                    if($value1['etapa_notificar_correo'] == 1)
                    {
                        // Se obtiene la Región del Prospecto
                        $prospecto_region = $this->mfunciones_generales->GetProspectoRegionCodigo($prospecto_id);
                        
                        $arrUsuariosNotificar = $this->mfunciones_generales->getListadoUsuariosEtapa($etapa_nueva, $prospecto_region);
                        
                        if (isset($arrUsuariosNotificar[0])) 
                        {
                            foreach ($arrUsuariosNotificar as $key => $value) 
                            {
                                $destinatario_nombre = $value['usuario_nombres'] . ' ' . $value['usuario_app'] . ' ' . $value['usuario_apm'];
                                $destinatario_correo = $value['usuario_email'];

                                // SE PROCEDE CON EL ENVÍO DE CORREO ELECTRÓNICO
                                $correo_enviado = $this->mfunciones_generales->EnviarCorreo('notificar_instancia', $destinatario_correo, $destinatario_nombre, $prospecto_id, 0);
                            }
                        }
                    }
                }
            }
        }
    }
    
    function ObservarDevolverProspecto($codigo_prospecto, $etapa_nueva, $etapa_actual, $nombre_usuario, $fecha_actual, $tipo_observacion, $observacion) {

        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, $etapa_nueva, $etapa_actual, $nombre_usuario, $fecha_actual, 0);
        /***  REGISTRAR SEGUIMIENTO ***/
        $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, $etapa_nueva, 2, 'Observa y Devuelve el Prospecto: ' . $observacion, $nombre_usuario, $fecha_actual);

        // Se procede a actualizar el prospecto para registrar la Observación
        $this->mfunciones_logica->GenerarObservacionProspecto($nombre_usuario, $fecha_actual, $codigo_prospecto);
        
        // Se registra el detalle/justificación de la Observación/Justificación en su tabla y se marca el flag "observado" del prospecto como "1"
        $this->mfunciones_logica->InsertarObservacionProspecto($codigo_prospecto, $_SESSION["session_informacion"]["codigo"], $etapa_actual, $tipo_observacion, $fecha_actual, $observacion, $nombre_usuario, $fecha_actual);
        
        // Se envía el correo electrónico para notificar que se observó el prospecto
        $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa($etapa_nueva);

        if (isset($arrEtapa[0]))
        {
            foreach ($arrEtapa as $key1 => $value1) 
            {
                $rol = $value1['rol_codigo'];

                $arrResultado4 = $this->mfunciones_logica->ObtenerDetalleDatosUsuario(2, $rol);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado4);

                if (isset($arrResultado4[0])) 
                {

                    foreach ($arrResultado4 as $key => $value) 
                    {
                        $destinatario_nombre = $value['usuario_nombres'] . ' ' . $value['usuario_app'] . ' ' . $value['usuario_apm'];
                        $destinatario_correo = $value['usuario_email'];

                        // SE PROCEDE CON EL ENVÍO DE CORREO ELECTRÓNICO
                        $correo_enviado = $this->mfunciones_generales->EnviarCorreo('notificar_instancia_observacion', $destinatario_correo, $destinatario_nombre, $codigo_prospecto, 0);

                    }
                }
            }
        }        
    }
    
    function GetDocumentoNombre($codigo_documento) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerNombreDocumento($codigo_documento);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
		
        if (isset($arrResultado1[0])) 
        {
            return $arrResultado1[0]['documento_nombre'];
        }
        else 
        {
            return FALSE;
        }
    }
    
    function GetDocumentoEnviar($codigo_documento, $filtro) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerNombreDocumentoEnviar($codigo_documento);
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
		
        if (isset($arrResultado1[0])) 
        {
            if($filtro == 'file')
            {			
                $ruta = RUTA_DOCUMENTOS;
                $documento = $arrResultado1[0]['documento_pdf'];

                $path = $ruta . $documento;

                if(file_exists($path))
                {
                        return $path;
                }
                else
                {
                        return FALSE;
                }

            }

            if($filtro == 'nombre_y_pdf')
            {
                return $arrResultado1[0]['documento_nombre'] . ' (' . $arrResultado1[0]['documento_pdf'] . ')';
            }
            
            if($filtro == 'nombre')
            {
                return $arrResultado1[0]['documento_nombre'];
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
    
    function GetDocDigitalizadoTercero($codigo_prospecto, $codigo_documento_prospecto, $filtro) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerDocumentoDigitalizarTerceros($codigo_prospecto, $codigo_documento_prospecto);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
        
        if (isset($arrResultado1[0])) 
        {
            $ruta = RUTA_TERCEROS;
            $documento = $arrResultado1[0]['terceros_carpeta'] . '/' . $arrResultado1[0]['terceros_carpeta'] . '_' .$arrResultado1[0]['terceros_documento_pdf'];

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
    
    function GetDocDigitalizado($codigo_prospecto, $codigo_documento_prospecto, $filtro) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerDocumentoDigitalizar($codigo_prospecto, $codigo_documento_prospecto);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
        
        if (isset($arrResultado1[0])) 
        {
            $ruta = RUTA_PROSPECTOS;
            $documento = $arrResultado1[0]['prospecto_carpeta'] . '/' . $arrResultado1[0]['prospecto_carpeta'] . '_' .$arrResultado1[0]['prospecto_documento_pdf'];

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
    
    function GetInfoDigitalizado($codigo_prospecto, $codigo_documento, $filtro) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->VerificaDocumentosDigitalizar($codigo_prospecto, $codigo_documento);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            $ruta = RUTA_PROSPECTOS;
            $documento = $arrResultado1[0]['prospecto_carpeta'] . '/' . $arrResultado1[0]['prospecto_carpeta'] . '_' .$arrResultado1[0]['prospecto_documento_pdf'];

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

    function GetInfoTercerosDigitalizadoPDFaux($codigo_terceros, $codigo_documento, $filtro) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->VerificaDocumentosTercerosDigitalizar($codigo_terceros, $codigo_documento);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            $ruta = RUTA_TERCEROS;
            $documento = $arrResultado1[0]['terceros_carpeta'] . '/' . $arrResultado1[0]['terceros_documento_pdf'];

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
    
    function GetInfoTercerosDigitalizadoPDF($codigo_terceros, $codigo_documento, $filtro) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->VerificaDocumentosTercerosDigitalizar($codigo_terceros, $codigo_documento);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            $ruta = RUTA_TERCEROS;
            $documento = $arrResultado1[0]['terceros_carpeta'] . '/' . $arrResultado1[0]['terceros_carpeta'] . '_' .$arrResultado1[0]['terceros_documento_pdf'];

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
    
    function GetInfoTercerosDigitalizado($codigo_terceros, $codigo_documento, $filtro) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->VerificaDocumentosTercerosDigitalizar($codigo_terceros, $codigo_documento);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            $ruta = RUTA_TERCEROS;
            $documento = $arrResultado1[0]['terceros_carpeta'] . '/' . $arrResultado1[0]['terceros_carpeta'] . '_' .$arrResultado1[0]['terceros_documento_pdf'];

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
                    
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    
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
    
    function GetDocumentoBase64_Ruta($ruta_documento) {
            
        // Obtener la cadena base64 de un documento especifico de un prospecto específico
        $data = file_get_contents($ruta_documento);
        $base64 = base64_encode($data);

        return $base64;
    }
    
    function GetPerfilUsuario($usuario_codigo, $perfil_codigo) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerDatosUsuarioPerfil($usuario_codigo, $perfil_codigo);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }
    
    function GetPermisoPorPerfil($codigo_usuario, $codigo_perfil) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->VerificaPermisoPorPerfil($codigo_usuario, $codigo_perfil);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }
    
    function GetMenuRol($rol_codigo, $menu_codigo) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerDatosRolMenu($rol_codigo, $menu_codigo);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }
    
    function GetDocumentoPersona($persona_codigo, $documento_codigo) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerDatosPersonaDocumento($persona_codigo, $documento_codigo);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }
    
    function GetActividadProspecto($prospecto_codigo, $actividad_codigo) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerActividadProspecto($prospecto_codigo, $actividad_codigo);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }
    
    function GetServicioCampana($campana_codigo, $servicio_codigo) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerServicioCampana($campana_codigo, $servicio_codigo);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }
    
    function GetServicioSolicitud($solicitud_codigo, $servicio_codigo) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerServicioSolicitud($solicitud_codigo, $servicio_codigo);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }
    
    function GuardarDocumentoBase64($codigo_prospecto, $codigo_documento, $documento_pdf_base64) {
		
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerNombreDocumento($codigo_documento);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
			
            // 1. Obtener el nombre del documento ¿

            if (isset($arrResultado1[0])) 
            {
                $nombre_documento = $this->TextoNoAcentoNoEspacios($arrResultado1[0]['documento_nombre']);

                //Se añade la fecha y hora al final
                $nombre_documento .= '__' . date('Y-m-d_H_i_s') . '.pdf';

                $path = RUTA_PROSPECTOS . 'afn_' . $codigo_prospecto . '/afn_' . $codigo_prospecto . '_' . $nombre_documento;

                $pdf = $documento_pdf_base64;

                $decoded = base64_decode($pdf);			

                if(!file_put_contents($path, $decoded))
                {
                    if(file_exists ($path))
                    {
                        unlink($path);
                    }					

                    return FALSE;
                }
                else
                {
                    return $nombre_documento;
                }
            }
            else 
        {
            return FALSE;
        }
    }
    
    function GuardarDocumentoMantenimientoBase64($codigo_mantenimiento, $documento_pdf_base64) {
		
        $this->load->model('mfunciones_logica');

        $nombre_documento = 'Capacitacion';

        //Se añade la fecha y hora al final
        $nombre_documento .= '__' . date('Y-m-d_H_i_s') . '.pdf';

        $path = RUTA_MANTENIMIENTOS . 'man_' . $codigo_mantenimiento . '/man_' . $codigo_mantenimiento . '_' . $nombre_documento;

        $pdf = $documento_pdf_base64;

        $decoded = base64_decode($pdf);			

        if(!file_put_contents($path, $decoded))
        {
            if(file_exists ($path))
            {
                    unlink($path);
            }					

            return FALSE;
        }
        else
        {
            return $nombre_documento;
        }
    }
    
    function TextoNoAcentoNoEspacios($data)
    {		
        //Se quitan los puntos
        $data = str_replace(".", "", $data);
        //Se quitan las comas
        $data = str_replace(",", "", $data);
        //Se quitan los punto y coma
        $data = str_replace(";", "", $data);
        //Se quitan los slash
        $data = str_replace("/", "", $data);			
        //Se remplazan los espacios
        $data = str_replace(" ", "_", $data);


        $data = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
                array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
                $data
        );

        $data = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
                array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
                $data 
        );

        $data = str_replace(
                array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
                array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
                $data
        );

        $data = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
                array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
                $data
        );

        $data = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
                array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
                $data
        );

        $data = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'),
                array('n', 'N', 'c', 'C'),
                $data
        );

        $data = substr($data, 0, 175);

        return $data;
    }

    function TextoNoAcentoNoEspaciosAux($data)
    {		
        //Se quitan los puntos
        $data = str_replace(".", "", $data);
        //Se quitan las comas
        $data = str_replace(",", "", $data);
        //Se quitan los punto y coma
        $data = str_replace(";", "", $data);
        //Se quitan los slash
        $data = str_replace("/", "", $data);			
        //Se remplazan los espacios
        //$data = str_replace(" ", "_", $data);


        $data = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
                array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
                $data
        );

        $data = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
                array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
                $data 
        );

        $data = str_replace(
                array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
                array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
                $data
        );

        $data = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
                array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
                $data
        );

        $data = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
                array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
                $data
        );

        /*
        $data = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'),
                array('n', 'N', 'c', 'C'),
                $data
        );
        */
        
        $data = str_replace(
                array('ç', 'Ç'),
                array('c', 'C'),
                $data
        );
        
        $data = str_replace(
                array('!', '"', '#', '$', '%', '&', '/', '(', ')', '=', '?', '¡', '¿', '\''),
                array('', '', '', '', '', '', '', '', '', '', '', '', '', ''),
                $data
        );

        //$data = substr($data, 0, 175);

        return $data;
    }
    
    function SanitizarData($data)
    {	
        $data = str_replace(
            array('>', '<', '!', '"', '#', '$', '%', '&', '(', ')', '=', '?', '¡', '¿', '/'),
            array('', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
            $data
        );

        return $data;
    }
    
    // Función para la notificación por PUSH de la APP
    
    function EnviarNotificacionPush($tipo_notificacion, $tipo_visita, $codigo_visita)
    {
        
        // PASO 1: Dependiento del tipo de Visita, se obtienen sus datos
            // 1 = Prospecto        2 = Mantenimiento (Código de la empresa)        3 = Se recibe el ID del Agente
        if($tipo_visita == 1)
        {
            $arrDatosProspecto = $this->GetDatosEmpresaCorreo($codigo_visita);
            $empresa_nombre = $arrDatosProspecto[0]['camp_nombre'];
            $empresa_categoria = $arrDatosProspecto[0]['empresa_categoria'];
            $usuario_id = $arrDatosProspecto[0]['usuario_id'];
            
            $data_extra = 'prospect_to_home';
            $accion_click = 'OPEN_PROSPECT_ACTIVITY';
        }
        
        if($tipo_visita == 2)
        {
            $arrEmpresa = $this->GetDatosEmpresa($codigo_visita);
            
            $empresa_nombre = $arrEmpresa[0]['empresa_nombre'];
            $empresa_categoria = $arrEmpresa[0]['empresa_categoria'];
            $usuario_id = $arrEmpresa[0]['usuario_id'];
            
            $data_extra = 'maintenance_to_home';
            $accion_click = 'OPEN_MAINTENANCE_ACTIVITY';
            
        }
        
        if($tipo_visita == 3)
        {
            $usuario_id = $codigo_visita;
            
            $data_extra = 'prospect_to_home';
            $accion_click = 'OPEN_PROSPECT_ACTIVITY';
        }
        
        // Se setea en vacio (Cambiar cuando sea requerido)
        
        $data_extra = '';
        $accion_click = '';
        
        // PASO 2: Se obtienen los datos neceasarios del Prospecto/Mantenimiento
        
        $titulo = '';
        $mensaje = '';
        
        // 1 = Nuevo Prospecto CN-01        2 = Pre-Afiliación (revisión por cumplimiento) completada CN-02       3 = Prospecto Observado CN-03     4 = Asignar Visita CN-04
        
        switch ($tipo_notificacion) 
        {            
            case 1:
                    $titulo = 'Nuevos Leads Asignados';
                    $mensaje = 'Puede ver el detalle ingresando a la App';

                break;
            
            case 2:
                    $titulo = 'Pre-Afiliación Aprobada';
                    $mensaje = 'Aprobado pre-afiliación del ' . $empresa_categoria . ': ' . $empresa_nombre . ', puede continuar con su consolidación';

                break;
            
            case 3:
                    $titulo = 'Lead Observado';
                    $mensaje = 'Observado en: ' . $empresa_nombre;

                break;
            
            case 4:
                    $titulo = 'Nueva Visita Asignada';
                    $mensaje = $empresa_categoria . ': ' . $empresa_nombre;

                break;
            
            case 5:
                    $titulo = 'Se ha rechazado una Verificación';
                    $mensaje = $empresa_categoria . ': ' . $empresa_nombre;

                break;
            
            case 6:
                    $titulo = 'Se ha aprobado una Verificación';
                    $mensaje = $empresa_categoria . ': ' . $empresa_nombre;

                break;
            
            case 7:
                    $titulo = 'Bloque Re-Agendado Asignado';
                    $mensaje = 'Se le asignó un nuevo bloque';

                break;

            default:
                return FALSE;
                break;
        }
        
        // Key de la API de la consola de Google para Firebase
        $api_access_key = 'AAAAJmr4Mt8:APA91bEJBosYAJ53DWL0PGaUgutCjwEERBxuvHa4nB1Us5YXpwj82-TOCw5IDd8QvbZxg5RwdUObYtl6yCJDSF0qghNRCINg6I-cCyFCMpAebspzsgi_v8SxkcmkGJQ5y55HldmhJqeH';
        
        // Preparar el bundle
        $msg = array
                (
                    'title'         => $titulo,
                    'body'          => $mensaje,
                    'vibrate'       => 1,
                    'sound'         => 1
                );

        $fields = array
                (
                    'to'            => '/topics/senaf_' . $usuario_id,
                    'notification'  => $msg
                );

        $headers = array
                (
                    'Authorization: key=' . $api_access_key,
                    'Content-Type: application/json'
                );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        // echo $result;
    }
    
    function ObteneRolHijoFlujo($codigo_etapa)
    {
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObteneRolHijoFlujo($codigo_etapa);
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
        
        return $arrResultado1;
    }
    
    // Función para verificar si la etapa se configuró para el envío de correo o no
    function VerificaEtapaEnvio($codigo_etapa)
    {
        $this->load->model('mfunciones_logica');

        $arrResultado = $this->mfunciones_logica->VerificaEnvioEtapa($codigo_etapa);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            if($arrResultado[0]['etapa_notificar_correo'] == 1)
            {
                return TRUE;
            }
        }
        
        return FALSE;
    }
    
    function GetProspectoRegion($prospecto_codigo) {
        
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerProspectoRegion($prospecto_codigo);
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            return $arrResultado[0]['estructura_regional_nombre'] . ((int)$arrResultado[0]["estructura_regional_estado"]==1 ? '' : ' (Cerrada)');
        }
        else
        {
            return 'Parámetro Invalido';
        }
        
    }
    
    // Función para verificar si un documento de un prospecto esta observado
    function VerificaDocumentoObservado($codigo_prospecto, $codigo_documento)
    {
        $this->load->model('mfunciones_logica');

        $arrResultado = $this->mfunciones_logica->VerDocumentoObservado($codigo_prospecto, $codigo_documento);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
        
	/* FUNCIONES API REST */
	
        // Verifica que la estructura sea la correcta
	function VerificaEstructuraREST($arrayData)
	{            
		
		// Estructura Establecida
		
//            array(
//                "identificador" => "",    // Usuario
//                "password" => "",         // Contraseña
//                "servicio" => "",         // Nombre del Servicio a Ser utilizado
//                "parametros" => array()   // Listado de parámetros
//            );
		
		$a1 = array(
			"identificador" => "",
			"password" => "",
			"servicio" => "",
			"parametros" => array()
		);
		$this->Verificar_ConvertirArray_Hacia_Matriz($a1);
		
		$a2 =  $arrayData;
		
		$resultado = $this->array_diff_key_recursive($a1, $a2);

		return $resultado;
		
	}
    
	function array_diff_key_recursive($a1, $a2)
	{
		$r = array();

		foreach ($a1 as $k => $v)
		{
			if (is_array($v))
			{
				if (!isset($a2[$k]) || !is_array($a2[$k]))
				{
					$r[$k] = $a1[$k];
				}
				else
				{
					if ($diff = $this->array_diff_key_recursive($a1[$k], $a2[$k]))
					{
						$r[$k] = $diff;
					}
				}
			}
			else
			{
				if (!isset($a2[$k]) || is_array($a2[$k]))
				{
					$r[$k] = $v;
				}
			}
		}

		return $r;
	}
	
	function RespuestaREST($arrayData)
	{
		$error = "false";
		$errorMessage = "";
		$errorCode = 0;

		if(empty(array_filter($arrayData)))
		{
				$error = "true";
				$errorMessage = "O_o No se encontraron resultados con los criterios enviados. Puede intentar actualizar la página.";
				$errorCode = "99";
                                $arrayData = "No se realizó la operación.";
		}

		$aux = array(		
				"error" => $error,
				"errorMessage" => $errorMessage,
				"errorCode" => $errorCode,
				"result" => $arrayData		
		);

		$this->Verificar_ConvertirArray_Hacia_Matriz($aux);

		return $aux;
	}
        
        function ConsultaWebServiceNIT($nit)
	{
            
            $arrResultado = array();
            
            /* BORRAR DESPUES SOLO ES PARA PRUEBAS - REMPLAZAR CON LA BÚSQUEDA REAL EN EL WEB SERVICE DE PAYSTUDIO (NAZIR) */
            if($nit == '999')
            {
                
                // Estos valores deberían ser los de la respuesta del Web Service
                
                $parent_nit = "999";
                $parent_tipo_sociedad_codigo = "1";
                $parent_nombre_legal = "Empresa registrada en PayStudio";
                $parent_nombre_fantasia = "Existe en PayStudio";
                $parent_rubro_codigo = "1";
                $parent_perfil_comercial_codigo = "1";
                $parent_mcc_codigo = "5441";

                $arrResultado = array(		
                        "parent_id" => -1,
                        "parent_nit" => $parent_nit,
                        "parent_adquiriente_codigo" => 1,
                        "parent_adquiriente_detalle" => 'ATC SA',
                        "parent_tipo_sociedad_codigo" => $parent_tipo_sociedad_codigo,
                        "parent_tipo_sociedad_detalle" => $this->GetValorCatalogoDB($parent_tipo_sociedad_codigo, 'TPS'),
                        "parent_nombre_legal" => $parent_nombre_legal,
                        "parent_nombre_fantasia" => $parent_nombre_fantasia,
                        "parent_rubro_codigo" => $parent_rubro_codigo,
                        "parent_rubro_detalle" => $this->GetValorCatalogoDB($parent_rubro_codigo, 'RUB'),
                        "parent_perfil_comercial_codigo" => $parent_perfil_comercial_codigo,
                        "parent_perfil_comercial_detalle" => $this->GetValorCatalogoDB($parent_perfil_comercial_codigo, 'PEC'),
                        "parent_mcc_codigo" => $parent_mcc_codigo,
                        "parent_mcc_detalle" => $this->GetValorCatalogoDB($parent_mcc_codigo, 'MCC')
                );
            }

            $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            return $arrResultado;
	}
	
         /* Función para la incersión de la data a PayStudio a través de su Web Service*/
        function WS_InsertarPayStudio($arrayDatos)
	{
            
            // Si existe error, se muestra la respuesta del Web Service
            $mensaje_error = '';
            
            return $mensaje_error;
        }
        
    function Verificar_ConvertirArray_Hacia_Matriz(&$arrResultado) {
        if (!isset($arrResultado[0]) && $arrResultado != null) {
            $arrResultado = array($arrResultado);
        }
        return $arrResultado;
    }
    
    function getFechaLiteral($cadenaFecha)
    {
        $cadenaFecha = strtotime($cadenaFecha);

        $ano = date('Y',$cadenaFecha);
        $mes = date('n',$cadenaFecha);
        $dia = date('d',$cadenaFecha);
        $diasemana = date('w',$cadenaFecha);
        $diassemanaN= array("Domingo","Lunes","Martes","Miércoles",
                                  "Jueves","Viernes","Sábado");
        $mesesN=array(1=>"Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                         "Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        return $diassemanaN[$diasemana]." $dia de ". $mesesN[$mes] ." de $ano";
    }
    
    function getFechaLiteral_tiempo($cadenaFecha)
    {
        $cadenaFecha = strtotime($cadenaFecha);

        $ano = date('Y',$cadenaFecha);
        $mes = date('n',$cadenaFecha);
        $dia = date('d',$cadenaFecha);
        $hora = date('H',$cadenaFecha);
        $minuto = date('i',$cadenaFecha);
        $segundo = date('s',$cadenaFecha);
        $diasemana = date('w',$cadenaFecha);
        $diassemanaN= array("Domingo","Lunes","Martes","Miércoles",
                                  "Jueves","Viernes","Sábado");
        $mesesN=array(1=>"Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                         "Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        return $diassemanaN[$diasemana]." $dia de ". $mesesN[$mes] ." de $ano a las $hora:$minuto:$segundo";
    }
    
    function VerificaFechaY_M_D($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if($date == '1900-01-01'){ return FALSE; }
        return $d && $d->format('Y-m-d') == $date;
    }
    
    function VerificaFechaY_M_D_H_M_S($date) {
        $d = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        if($date == '1900-01-01 00:00:00'){ return FALSE; }
        return $d && $d->format('Y-m-d H:i:s') == $date;
    }
    
    function VerificaFechaD_M_Y($date) {
        $d = DateTime::createFromFormat('d/m/Y', $date);
        return $d && $d->format('d/m/Y') == $date;
    }
    
    function VerificaFechaD_M_Y_H_M($date) {
        $d = DateTime::createFromFormat('d/m/Y H:i', $date);
        return $d && $d->format('d/m/Y H:i') == $date;
    }
    
    function VerificaFechaH_M($date) {
        $d = DateTime::createFromFormat('H:i', $date);
        return $d && $d->format('H:i') == $date;
    }
    
    function VerificaCorreo($data) {
        
        return (filter_var($data, FILTER_VALIDATE_EMAIL));
    }
    
    function getFormatoFecha_datetime_date($dateTime) {
        $fecha = new DateTime($dateTime);
        $fecha = $fecha->format('Y-m-d');
        return($fecha);
    }
    
    function getFormatoFechaD_M_Y($dateTime) {
        $fecha = new DateTime($dateTime);
        $fecha = $fecha->format('d/m/Y');
        if ($fecha == "01/01/1900" || $fecha == "01/01/0001" || $dateTime == "0000-00-00") {
            $fecha = "";
        }
        return($fecha);
    }
    
    function getFormatoFechaH_M($dateTime) {
        $fecha = new DateTime($dateTime);
        $fecha = $fecha->format('H:i');
        return($fecha);
    }
    
    function getFormatoFechaD_M_Y_H_M($dateTime) {
        $fecha = new DateTime($dateTime);
        $fecha = $fecha->format('d/m/Y H:i');
        if ($fecha == "01/01/1900T00:00:00" || $fecha == "01/01/0001T00:00:00") {
            $fecha = "";
        }
        return($fecha);
    }
    
    function getFormatoFechaD_M_Y_H_M_S($dateTime) {
        $fecha = new DateTime($dateTime);
        $fecha = $fecha->format('d/m/Y H:i:s');
        if ($fecha == "01/01/1900T00:00:00" || $fecha == "01/01/0001T00:00:00") {
            $fecha = "";
        }
        return($fecha);
    }
    
    function pivotFormatoFechaD_M_Y($dateTime) {
        
        if($dateTime == "null" || $dateTime == "" || $dateTime == NULL)
        {
            return("Sin Registrar");
        }
        
        $fecha = new DateTime($dateTime);
        $fecha = $fecha->format('d/m/Y');
        if ($fecha == "01/01/1900" || $fecha == "01/01/0001" || $dateTime == "0000-00-00") {
            $fecha = "";
        }
        return($fecha);
    }
    
    function pivotFormatoFechaD_M_Y_H_M($dateTime) {
        
        if($dateTime == "null" || $dateTime == "" || $dateTime == NULL)
        {
            return("Sin Registrar");
        }
        
        $fecha = new DateTime($dateTime);
        $fecha = $fecha->format('d/m/Y H:i');
        if ($fecha == "01/01/1900T00:00:00" || $fecha == "01/01/0001T00:00:00") {
            $fecha = "";
        }
        return($fecha);
    }
    
    public function getMinutos($start, $end) {
        $start_date = new DateTime($start);
        $end_date = new DateTime($end);
        
        $interval = $start_date->diff($end_date);
        $hours   = $interval->format('%h'); 
        $minutes = $interval->format('%i');
        return  ($hours * 60 + $minutes);
    }
    
    function getFormatoFechaDate($cadenaFecha) {
        if ($cadenaFecha == "") {
            //$date ='1900-01-01';
            $cadenaFechaFormato = '1900-01-01';
        } else {
            $date = str_replace('/', '-', $cadenaFecha);
            $cadenaFechaFormato = date('Y-m-d', strtotime($date));
        }
        return($cadenaFechaFormato);
    }
    
    function getFormatoFechaDateTime($cadenaFecha) {
        if ($cadenaFecha == "") {
            //$date ='1900-01-01';
            $cadenaFechaFormato = '1900-01-01T00:00:00';
        } else {
            $date = str_replace('/', '-', $cadenaFecha);
            $cadenaFechaFormato = date('Y-m-d H:i:s', strtotime($date));
        }
        return($cadenaFechaFormato);
    }
    function getNumeroDecimal($valor_dinero) {
        if ($valor_dinero != "0,00") {
            $valor_dinero = str_replace(".", "", $valor_dinero);
            $numero_decimal = str_replace(",", ".", $valor_dinero);
        } else {
            $numero_decimal = str_replace("0,00", "0.00", $valor_dinero);
        }
        return $numero_decimal;
    }
    function getNumeroDecimalVacio($valor_dinero) {
        $numero_decimal = str_replace("0.00", "", $valor_dinero);
        return $numero_decimal;
    }
    function getNumeroEntero($valor_dinero) {
        $numero_decimal = str_replace(".00", "", $valor_dinero);
        return $numero_decimal;
    }
    function getNumeroEnteroVacio($valor_dinero) {
        $numero_decimal = str_replace("0.00", "", $valor_dinero);
        return $numero_decimal;
    }
    
    function time_to_decimal($time) {
        
        if($time == null || $time == 'null' || $time == '')
        {
            $time = '00:00:00';
        }
        
        $timeArr = explode(':', $time);
        $decTime = ($timeArr[0]*60) + ($timeArr[1]) + ($timeArr[2]/60);

        return $decTime;
    }
    
    public function getMensajeRespuesta($arrRespuesta) {
        if (isset($arrRespuesta['err_existente'])) {
            if ($arrRespuesta['err_existente'] == 0) {
                $respuesta = $arrRespuesta['err_mensaje'];
            } else {
                $err_mensaje = $arrRespuesta['err_mensaje'];
                $err_base = strpos($err_mensaje, "#");
                if ($err_base !== FALSE) {
                    js_error_div_javascript($arrRespuesta['err_mensaje']);
                } else {
                    js_error_div_javascript("Ocurrio un error inesperado, vuelva a intentarlo");
                }
                exit();
            }
        } else {
            $respuesta = 'No se puede realizar la transaccion';
        }
        return $respuesta;
    }
    
    public function htmlArraytoSelected($listaParametricas, $nombreLista, $nombreObj, $idCaja, $codItem, $descripcion, $strValorCaja) {
        $arrLista = $listaParametricas[$nombreLista];
        $arrListaObj = $arrLista[$nombreObj];

        if (!isset($arrListaObj[0])) {
            $arrListaObj = array($arrListaObj);
        }

        $i = 0;
        foreach ($arrListaObj as $key => $value) {
            $arrayObjetos[$i] = array("id" => $value[$codItem], "campoDescrip" => $value[$descripcion]);
            $i++;
        }
        $arr_formulario_cajas[$idCaja] = html_select($idCaja, $arrayObjetos, 'id', 'campoDescrip', '', $strValorCaja);
        return $arr_formulario_cajas;
    }
    
    
    
    function getCodigosDepartamento() {

        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');

        $resultado = new stdClass();
        $resultado->error = false;
        
            // Seteo inicial
        
            $resultado->lp = "2";
            $resultado->be = "8";
            $resultado->or = "4";
            $resultado->sc = "7";
            $resultado->ta = "6";
            $resultado->pa = "9";
            $resultado->co = "3";
            $resultado->ch = "1";
            $resultado->po = "5";
        
        $arrDepartamento = $this->mfunciones_logica->ObtenerCatalogo('DEP', -1, -1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDepartamento);
        
        if (!isset($arrDepartamento[0])) 
        {
            $resultado->error = true;
        }
        else
        {
            foreach ($arrDepartamento as $key => $value) 
            {
                $valor_buscar = str_replace(" ", "", strtolower($value["catalogo_descripcion"]));
                
                switch ($valor_buscar)
                {
                    case "lapaz":
                        $resultado->lp = $value["catalogo_codigo"];
                        break;
                    
                    case "beni":
                        $resultado->be = $value["catalogo_codigo"];
                        break;

                    case "oruro":
                        $resultado->or = $value["catalogo_codigo"];
                        break;
                    
                    case "santacruz":
                        $resultado->sc = $value["catalogo_codigo"];
                        break;
                    
                    case "tarija":
                        $resultado->ta = $value["catalogo_codigo"];
                        break;
                    
                    case "pando":
                        $resultado->pa = $value["catalogo_codigo"];
                        break;
                    
                    case "cochabamba":
                        $resultado->co = $value["catalogo_codigo"];
                        break;
                    
                    case "chuquisaca":
                        $resultado->ch = $value["catalogo_codigo"];
                        break;
                    
                    case "potosi":
                    case "potosí":
                        $resultado->po = $value["catalogo_codigo"];
                        break;
                    
                    default:
                        break;
                }
            }
        }
        
        return $resultado;
        
    }
    
    /*************** AFILIACIÓN POR TERCEROS - INICIO ****************************/
    
    // Funcion para eliminar recursivamente un directorio y sus archivos
    function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object))
                        rrmdir($dir . DIRECTORY_SEPARATOR . $object);
                    else
                        unlink($dir . DIRECTORY_SEPARATOR . $object);
                }
            }
            rmdir($dir);
        }
    }

    function GuardarDocumentoTercerosBase64PDF($codigo_prospecto, $codigo_documento, $documento_pdf_base64) {
		
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerNombreDocumento($codigo_documento);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
			
            // 1. Obtener el nombre del documento

            if (isset($arrResultado1[0])) 
            {
                $nombre_documento = $this->TextoNoAcentoNoEspacios($arrResultado1[0]['documento_nombre']);

                //Se añade la fecha y hora al final
                $nombre_documento .= '__' . date('Y-m-d_H_i_s') . '.pdf';
                $path = RUTA_TERCEROS . 'ter_' . $codigo_prospecto . '/ter_' . $codigo_prospecto . '_' . $nombre_documento;
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
    
    function GuardarDocumentoTercerosBase64($codigo_prospecto, $codigo_documento, $documento_pdf_base64) {
		
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        $resultado = new stdClass();
        $resultado->ok = TRUE;
        $resultado->texto = $this->lang->line('FormularioDocumentoError');
        $resultado->nombre_documento = '';
        
        // Para el caso específico del Certificado SEGIP
       
        if($codigo_documento == 20)
        {
            if(strlen((string)$documento_pdf_base64) < 10)
            {
                $resultado->nombre_documento = 'no-digitalizado';
                return $resultado;
            }
            
            if (!base64_decode($documento_pdf_base64))
            {
                $resultado->ok = FALSE;
                $resultado->texto = 'Documento: PDF no es correcto.';
                return $resultado;
            }
            else
            {
                // Remplazo auxiliar para el formato PDF quitando el content type
                $documento_pdf_base64 = str_replace('data:application/pdf;base64,', '', $documento_pdf_base64);
                $aux_pdf = $this->GuardarDocumentoTercerosBase64PDF($codigo_prospecto, $codigo_documento, $documento_pdf_base64);
                
                if($aux_pdf == FALSE)
                {
                    $resultado->ok = FALSE;
                    $resultado->texto = 'Documento: PDF no se guardó.';
                    return $resultado;
                }
                else
                {
                    $resultado->nombre_documento = $aux_pdf;
                    return $resultado;
                }
            }
        }
        
        $arrResultado1 = $this->mfunciones_logica->ObtenerNombreDocumento($codigo_documento);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
        
            // 1. Obtener el nombre del documento

            if (isset($arrResultado1[0])) 
            {
                $nombre_documento = $this->TextoNoAcentoNoEspacios($arrResultado1[0]['documento_nombre']);
                
                // Validacones

                if($arrResultado1[0]['documento_mandatorio'] == 1)
                {
                    if(strlen((string)$documento_pdf_base64) < 150)
                    {
                        $resultado->ok = FALSE;
                        $resultado->texto = 'Documento: ' . $arrResultado1[0]['documento_nombre'] . ' Es Mandatorio.';
                        return $resultado;
                    }
                }
                else
                {
                    if(strlen((string)$documento_pdf_base64) < 150)
                    {
                        $resultado->nombre_documento = 'no-digitalizado';
                        return $resultado;
                    }
                }
                
                $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
                
                // Tipo
                $imagen_tipo = substr($documento_pdf_base64, 5, strpos($documento_pdf_base64, ';')-5);
                if(preg_match('(image/jpg|image/jpeg|image/png)', $imagen_tipo) !== 1)
                {
                    $resultado->ok = FALSE;
                    $resultado->texto = 'Documento: ' . $arrResultado1[0]['documento_nombre'] . ' No es válido.';
                    return $resultado;
                }
                else
                {
                    // Peso
                    $size = (int) ((strlen(rtrim($documento_pdf_base64, '=')) * 3 / 4)/1024);
                    
                    if($size > $arrConf[0]['conf_img_peso']*1024)
                    {
                        $resultado->ok = FALSE;
                        $resultado->texto = 'Documento: ' . $arrResultado1[0]['documento_nombre'] . ' Debe pesar menos de ' . $arrConf[0]['conf_img_peso'] . 'MB.';
                        return $resultado;
                    }
                    
                    // Tamano
                    $data_tamano = getimagesize($documento_pdf_base64);

                    $imagen_height = $data_tamano[1];
                    $imagen_widht = $data_tamano[0];
                    
                    if($imagen_height < $arrConf[0]['conf_img_height_min'] || $imagen_widht < $arrConf[0]['conf_img_width_min'])
                    {
                        $resultado->ok = FALSE;
                        $resultado->texto = 'Documento: ' . $arrResultado1[0]['documento_nombre'] . ' Debe ser mayor a ' . $arrConf[0]['conf_img_width_min'] . 'x' . $arrConf[0]['conf_img_height_min'];
                        return $resultado;
                    }
                    
                    if($imagen_height > $arrConf[0]['conf_img_height_max'] || $imagen_widht > $arrConf[0]['conf_img_width_max'])
                    {
                        $resultado->ok = FALSE;
                        $resultado->texto = 'Documento: ' . $arrResultado1[0]['documento_nombre'] . ' Debe ser menor a ' . $arrConf[0]['conf_img_width_max'] . 'x' . $arrConf[0]['conf_img_height_max'];
                        return $resultado;
                    }
                }
                
                switch ($imagen_tipo) {
                    case (preg_match('(image/png)', $imagen_tipo) === 1):
                        
                        $extension_tipo = 'png';
                        break;
                    
                    case (preg_match('(image/jpg)', $imagen_tipo) === 1):
                        
                        $extension_tipo = 'jpg';
                        break;
                    
                    case (preg_match('(image/jpeg)', $imagen_tipo) === 1):
                        
                        $extension_tipo = 'jpeg';
                        break;

                    default:
                        $resultado->ok = FALSE;
                        $resultado->texto = 'Documento: ' . $arrResultado1[0]['documento_nombre'] . ' No es válido.';
                        return $resultado;
                }
                
                //Se añade la fecha y hora al final
                $nombre_documento .= '__' . date('Y-m-d_H_i_s') . '.' . $extension_tipo;
                    $resultado->nombre_documento = $nombre_documento;
                $path = RUTA_TERCEROS . 'ter_' . $codigo_prospecto . '/ter_' . $codigo_prospecto . '_' . $nombre_documento;
                
                $pdf = $documento_pdf_base64;
                
                $pdf = str_replace('data:image/png;base64,', '', $pdf);
                $pdf = str_replace('data:image/jpg;base64,', '', $pdf);
                $pdf = str_replace('data:image/jpeg;base64,', '', $pdf);
                
                $decoded = base64_decode($pdf);
                
                if(!file_put_contents($path, $decoded))
                {
                    if(file_exists ($path))
                    {
                        unlink($path);
                    }
                    
                    $resultado->ok = FALSE;
                    return $resultado;
                    
                }
                else
                {
                    return $resultado;
                }
            }
            else 
            {
                $resultado->ok = FALSE;
                return $resultado;
            }
    }
    
    function ObtenerHorasLaboral($fecha_ini, $fecha_fin, $tiempo_horas)
    {
        $resultado = new stdClass();
        $resultado->horas_laborales = 0;
        $resultado->horas_reloj = 0;
        $resultado->resultado_texto = 'Atrasado';
        $resultado->resultado_bandera = '';
        
        $resultado->horas_laborales = $this->mfunciones_generales->getDiasLaborales($fecha_ini, $fecha_fin);
        $resultado->horas_reloj = $this->mfunciones_generales->getMinutos($fecha_ini, $fecha_fin);
        
        if($tiempo_horas > 0)
        {
            $total_porcentaje = 100 - round(($resultado->horas_laborales*100)/$tiempo_horas);

            if($total_porcentaje > 50)
            {
                $resultado->resultado_texto = "A tiempo";
            }        
            elseif($total_porcentaje >= 0)
            {
                $resultado->resultado_texto = "Pendiente";
            }
            elseif($total_porcentaje < 0)
            {
                $resultado->resultado_texto = "Atrasado";
            }
            
            $resultado->resultado_bandera = $this->TiempoEtapaColor($total_porcentaje);
        }
        
        return $resultado;
        
    }
    
    function DatosTercerosEmail($codigo_tercero)
    {
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerDetalleSolicitudTerceros($codigo_tercero);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            $arrRegion = $this->mfunciones_logica->ObtenerDatosRegional($arrResultado[0]["codigo_agencia_fie"]);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRegion);
            
            if (isset($arrRegion[0])) 
            {
                $nombre_region = $arrRegion[0]['estructura_regional_nombre'];
                $monto_region = $arrRegion[0]['estructura_regional_monto'];
            }
            else
            {
                $nombre_region = 'Sin Selección';
            }
            
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                $item_valor = array(

                    "terceros_id" => $value["terceros_id"],
                    "tipo_persona_id_codigo" => $value["tipo_persona_id"],
                    "tipo_persona_id_detalle" => $this->mfunciones_generales->GetValorCatalogoDB($value["tipo_persona_id"], 'tipo_persona'),
                    "tercero_asistencia" => $value["tercero_asistencia"],
                    "tercero_asistencia_detalle" => $this->GetValorCatalogo($value["tercero_asistencia"], 'tercero_asistencia'),
                    "codigo_agencia_fie" => $value["codigo_agencia_fie"],
                    "nombre_agencia" => $nombre_region,
                    "monto_agencia" => $monto_region,
                    "onboarding_numero_cuenta" => ($value["onboarding_numero_cuenta"]!='' ? $value["onboarding_numero_cuenta"] : ' '),
                    "terceros_nombre" => $value["di_primernombre"] . ' ' . $value["di_primerapellido"] . ' ' . $value["di_segundoapellido"],
                    "terceros_nombre_completo" => $value["di_primernombre"] . ' ' . $value["di_segundo_otrosnombres"] . ' ' . $value["di_primerapellido"] . ' ' . $value["di_segundoapellido"],
                    "cedula_identidad" => $value["cI_numeroraiz"] . ' ' . $value["cI_complemento"] . ' ' . $this->mfunciones_generales->GetValorCatalogoDB($value['cI_lugar_emisionoextension'], 'cI_lugar_emisionoextension'),
                    "terceros_email" => $value["direccion_email"],
                    "rechazado_texto" => ($value["rechazado_texto"]!='' ? $value["rechazado_texto"] : ' '),
                    "completado_texto" => ($value["completado_texto"]!='' ? $value["completado_texto"] : ' '),
                    "terceros_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["terceros_fecha"]),
                    "terceros_estado_codigo" => $value["terceros_estado"],
                    "terceros_estado" => $this->GetValorCatalogo($value["terceros_estado"], 'terceros_estado'),
                    "monto_inicial" => $value["monto_inicial"],
                    "terceros_observacion" => ($value["terceros_observacion"]!='' ? $value["terceros_observacion"] : ' '),
                    "coordenadas_geo_dom" => ($value["coordenadas_geo_dom"]!='' ? $value["coordenadas_geo_dom"] : ' '),
                    "coordenadas_geo_trab" => ($value["coordenadas_geo_trab"]!='' ? $value["coordenadas_geo_trab"] : ' '),
                    "tipo_cuenta" => $this->mfunciones_generales->GetValorCatalogoDB($value['tipo_cuenta'], 'tipo_cuenta'),
                    
                    "notificar_cierre_causa" => $this->mfunciones_generales->GetValorCatalogoDB($value['notificar_cierre_causa'], 'causa_notificar_cierre'),
                    "cuenta_cerrada_causa" => $this->mfunciones_generales->GetValorCatalogoDB($value['cuenta_cerrada_causa'], 'causa_cuenta_cerrada'),
                    
                    "envio" => $value["envio"],
                    "dir_departamento" => $value["dir_departamento"],
                    
                    "f_cobis_flujo" => $value["f_cobis_flujo"],
                    "f_cobis_actual_etapa" => $value["f_cobis_actual_etapa"],
                    "f_cobis_actual_etapa_detalle" => $this->mfunciones_generales->GetValorCatalogo($value['f_cobis_actual_etapa'], 'flujo_cobis_etapa'),
                    "f_cobis_actual_intento" => ($value["f_cobis_actual_intento"]==0 ? 'Ninguno' : $value["f_cobis_actual_intento"]),
                    "f_cobis_actual_usuario" => $this->mfunciones_generales->getNombreUsuario($value["f_cobis_actual_usuario"]),
                    "f_cobis_actual_fecha" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["f_cobis_actual_fecha"]),
                    "f_cobis_excepcion" => $value["f_cobis_excepcion"],
                    "f_cobis_excepcion_detalle" =>  $this->mfunciones_generales->GetValorCatalogo($value['f_cobis_excepcion'], 'si_no'),
                    "f_cobis_excepcion_motivo" => $this->mfunciones_generales->GetValorCatalogoDB($value['f_cobis_excepcion_motivo'], 'motivo_flujo_cobis') . ((string)$value["f_cobis_excepcion_motivo_texto"]=='' ? '' : ' - ' . $value["f_cobis_excepcion_motivo_texto"]),
                    "f_cobis_flag_rechazado" => $value["f_cobis_flag_rechazado"],
                    "f_cobis_flag_rechazado_detalle" => $this->mfunciones_generales->GetValorCatalogo($value['f_cobis_flag_rechazado'], 'si_no'),
                );
                $lst_resultado[$i] = $item_valor;
                
                $i++;
            }
            
            return $lst_resultado;
        }
        
        return FALSE;
    }
    
    function DocsComa2Lista($lista)
    {
        $resultado = '';
        
        if($lista != '')
        {
            $arrDocs= explode(",", rtrim($lista, ','));
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDocs);
            
            if (isset($arrDocs[0])) 
            {
                foreach ($arrDocs as $key => $value) {
                    
                    $resultado .= ' - ' . $this->GetDocumentoEnviar($value, 'nombre') . '<br />';
                }
            }
        }
        else
        {
            $resultado = 'Ninguno';
        }
        
        return $resultado;
    }
    
    /**** REGISTRAR SEGUIMIENTO, ETAPA E HITO Y ENVIAR CORREO (último parámetro 1=Envio a etapas hijas    2=Envio a etapa específica ****/
    function NotificacionEtapaTerceros($terceros_id, $codigo_etapa, $regionalizado) {
        
        $this->load->model('mfunciones_logica');
        
        $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa($codigo_etapa);

        if (isset($arrEtapa[0]))
        {
            foreach ($arrEtapa as $key1 => $value1) 
            {
                // 0 = No Envía Correo      1 = Sí Envía Correo
                if($value1['etapa_notificar_correo'] == 1)
                {
                    $arrTerceros = $this->DatosTercerosEmail($terceros_id);
                    
                    $arrUsuariosNotificar = $this->getListadoUsuariosEtapa($codigo_etapa, $arrTerceros[0]['codigo_agencia_fie'], $regionalizado);

                    if (isset($arrUsuariosNotificar[0])) 
                    {

                        foreach ($arrUsuariosNotificar as $key => $value) 
                        {
                            $destinatario_nombre = $value['usuario_nombres'] . ' ' . $value['usuario_app'] . ' ' . $value['usuario_apm'];
                            $destinatario_correo = $value['usuario_email'];
                            
                            // SE PROCEDE CON EL ENVÍO DE CORREO ELECTRÓNICO
                            $correo_enviado = $this->mfunciones_generales->EnviarCorreo('notificar_instancia_onboarding', $destinatario_correo, $destinatario_nombre, $arrTerceros);
                        }
                    }
                }
            }
        }
    }
    
    function ObtenerNombreRegionCodigo($codigo_region)
    {
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerDatosRegional($codigo_region);
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            return $arrResultado[0]['estructura_regional_nombre'] . ((int)$arrResultado[0]["estructura_regional_estado"]==1 ? '' : ' (Cerrada)');
        }
        else
        {
            return 0;
        }
    }
    
    function ObtenerCodigoTercerosProspecto($codigo_prospecto)
    {
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerTercerosProspecto($codigo_prospecto);
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            return $arrResultado[0]['onboarding_codigo'];
        }
        else
        {
            return 0;
        }
    }
    
    function ObtenerProspectoTerceros($codigo_tercero)
    {
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerProspectoTerceros($codigo_tercero);
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            return $arrResultado[0]['prospecto_id'];
        }
        else
        {
            return 0;
        }
    }
    
    function ObtenerCatalogoSelect($campo, $valor, $codigo_catalogo, $parent_codigo=-1, $parent_tipo=-1, $filtro=-1, $sin_seleccion='')
    {
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        $arrResultado = $this->mfunciones_logica->ObtenerCatalogo($codigo_catalogo, $parent_codigo, $parent_tipo, $filtro);
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            $i = 0;

            foreach ($arrResultado as $key => $value) {

                $arrResultado[$i]['catalogo_descripcion'] = str_replace(array(' Y ', ' Del ', ' De ', ' En ', ' Con ', ' Más ', ' Se ', ' La ', ' A ', ' El ', ' Un ', ' O ', '[p'), array(' y ', ' del ', ' de ', ' en ', ' con ', ' más ', ' se ', ' la ', ' a ', ' el ', ' un ', ' o ', '[P'), ucwords(mb_strtolower($arrResultado[$i]['catalogo_descripcion'])));

                $i++;
            }
            
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
    
    function GetTotalHorasFlujo($flujos = "1")
    {
        $this->load->model('mfunciones_logica');
        
        $arrFlujo = $this->mfunciones_logica->ObtenerTotalHorasFlujo($flujos);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrFlujo);
        
        if (isset($arrFlujo[0]))
        {
            return $arrFlujo[0]["total_horas"];
        }
        else
        {
            return 0;
        }
    }
    
    function TiempoEtapaProspectoAlerta($fecha_asignacion_etapa) {
        
        $this->load->model('mfunciones_logica');

        $resultado = 0;
        
        // Paso 1: Se obtiene el tiempo total del flujo de afiliación
        
        $tiempo_etapa = $this->GetTotalHorasFlujo();
        
        // Paso 2: Se utiliza la función para obtener la diferencia
        
        $date1 = new DateTime($fecha_asignacion_etapa);
        $date2 = new DateTime(date('Y-m-d H:i:s'));

        //$diferencia_dias = $date2->diff($date1)->format("%a");
        
        // -> Para Horas
        $fecha_asignacion_etapa = date('Y-m-d H:i:s', strtotime($fecha_asignacion_etapa));
        $fecha_actual = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')));
        
        // -> Para Días
        // $fecha_asignacion_etapa = date('Y-m-d', strtotime($fecha_asignacion_etapa));
        // $fecha_actual = date('Y-m-d', strtotime(date('Y-m-d H:i:s')));
        
        $diferencia_dias = $this->getDiasLaborales($fecha_asignacion_etapa, $fecha_actual);
        
        // Paso 4: Se obtiene el porcentaje 
        
            if($tiempo_etapa == 0){ return 0; }
        
        $total_porcentaje = 100 - round(($diferencia_dias*100)/$tiempo_etapa);
        
        return $total_porcentaje;
        
    }
    
    function getListadoUsuariosEtapaEnvio($etapa) {
        
        // Paso 1: Se obtiene el listado de Roles y Usuarios asignados a la Etapa
        $arrRolUsuarioEtapa = $this->mfunciones_logica->ObtenerListaEtapaRolAll($etapa);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRolUsuarioEtapa);

        $lista_usuario = '-1';
        $lista_rol = '-1';

        if (isset($arrRolUsuarioEtapa[0])) 
        {

            foreach ($arrRolUsuarioEtapa as $key => $value) 
            {
                if($value['tipo_id'] == 1) // <- Roles
                {
                    $lista_rol .= ', ' . $value['codigo'];
                }

                if($value['tipo_id'] == 2) // <- Usuarios
                {
                    $lista_usuario .= ', ' . $value['codigo'];
                }
            }

        }

        // Paso 3: Se obtiene el listado de Usuarios que sean parte de lo asignado a la Etapa, y que seán de la región del Prospecto

        $arrUsuarios = $this->mfunciones_logica->ObtenerUsuariosRegionNotificar($lista_rol, $lista_usuario, -1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrUsuarios);
        
        // Paso 4: Se crea un array auxiliar que identifique si las regiones que supervisa el usuario es parte de la region del registro
        
        $lst_resultado = array();
        
        if (isset($arrUsuarios[0])) 
        {
            $i = 0;
            
            foreach ($arrUsuarios as $key => $value) 
            {
                $item_valor = array(
                    "usuario_id" => $value["usuario_id"],
                    "estructura_regional_id" => $value["estructura_regional_id"],
                    "usuario_nombres" => $value["usuario_nombres"],
                    "usuario_app" => $value["usuario_app"],
                    "usuario_apm" => $value["usuario_apm"],
                    "usuario_email" => $value["usuario_email"]
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        }
        
        return $lst_resultado;
    }
    
    function TiempoEtapaColor_plain($codigo) {
        
        $icono = 'Atrasado';
        
        if($codigo > 50)
        {
            $icono = 'A Tiempo';
        }        
        elseif($codigo >= 0)
        {
            $icono = 'Pendiente';
        }
        elseif($codigo < 0)
        {
            $icono = '<strong>Atrasado</strong>';
        }
        
        return $icono;        
    }
    
    function ValidaCadena($texto, $tipo_validacion=1) {
        
        $max_repetidos = 3;
        
        // 0=En toda la cadena          1=Solo los primeros caracteres
        
        if($tipo_validacion == 0)
        {
            if (preg_match('/(\w)\1{' . $max_repetidos . ',}/', $texto))
            {
                return ' no puede tener más de ' . $max_repetidos . ' caracteres juntos repetidos.';
            }
        }
        else
        {
            if(strlen((string)$texto) > $max_repetidos)
            {
                if (preg_match('/^(.)\1*$/', substr(strtolower($this->TextoNoAcentoNoEspacios($texto)),0,$max_repetidos )))
                {
                    return ' no puede tener más de ' . ($max_repetidos-1) . ' caracteres juntos repetidos al inicio.';
                }
            }
        }
        
        return '';
    }
    
    // -- Req. Consulta COBIS y SEGIP
    
    function TokenOnboarding_random_str(
    int $length = 64, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces [] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

    function TokenOnboarding_parametros($token, $criterio='', $opcion_otp=0) {
        
        $this->load->model('mfunciones_logica');
        
        // Primero, se invoca a la función par borrar los tokens que estén expirados
        
        $this->mfunciones_logica->token_delete_expired();
        
        // Segundo, se ejecuta la consulta para obtener la data del token
        
        $arrResultado = $this->mfunciones_logica->token_obtener($token, $opcion_otp);
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0]))
        {
            if($criterio == 'token_id')
            {
                return array('token_id' => $arrResultado[0]['token_id']);
            }
            
            $array_resultado = json_decode($arrResultado[0]['token_params'], true);
            if(json_last_error() == JSON_ERROR_NONE)
            {
                $array_resultado = array_merge($array_resultado, array('token_jwt' => $arrResultado[0]['token_jwt']));
                
                if($criterio == 'documento')
                {
                    // ADICIONAR EL ELEMENTO DEL CERTIFICADO SEGIP BASE64
                    $array_resultado = array_merge($array_resultado, array('dig_cert_segip' => $arrResultado[0]['token_pdf_base64']));
                }
                
                if($criterio == 'guardado_log')
                {
                    // ADICIONAR EL ID EN UN TOKEN AUXILIAR
                    $array_resultado_aux = array_merge($array_resultado, array('token_id' => $arrResultado[0]['token_id']));
                    
                    // Limpiar array y sólo colocar los parámetros específicos
                    unset($array_resultado);
                    $array_resultado['token_id'] = $array_resultado_aux['token_id'];
                    $array_resultado['cI_numeroraiz'] = $array_resultado_aux['cI_numeroraiz'];
                }
                
                return $array_resultado;
            }
            
        }
        return FALSE;
    }
    
    function TokenOnboarding_generar($accion_usuario, $accion_fecha) {
        
        $this->load->model('mfunciones_logica');
        
        // Primero, se invoca a la función par borrar los tokens que estén expirados
        
        $this->mfunciones_logica->token_delete_expired();
        
        // Segundo, se ejecuta la consulta para obtener el token único y guardar la data
        
        $arrDatos = json_encode($arrDatos);
        if(json_last_error() == JSON_ERROR_NONE)
        {
                // Obtener parámetros de configuración
                $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
            
            $token_generado = $this->TokenOnboarding_random_str($arrConf[0]['conf_token_dimension']);
        
            $sw = 0;
            // Bucle hasta obtener un token que no esté registrado en la tabla
            while ($sw == 0)
            {
                $arrResultado = $this->mfunciones_logica->token_existe($token_generado);
                $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (!isset($arrResultado[0]))
                {
                    $sw = 1;
                }
            }

            // Se procede a guardar los parámetros y certificado SEGIP

            $this->mfunciones_logica->token_insert($token_generado, $accion_usuario, $accion_fecha);

            return $token_generado;
        }
        
        return FALSE;
        
    }
    
    function validar_imagen64_size($img64, $width_min, $height_min)
    {
        return (preg_match("/data:([a-zA-Z0-9]+\/[a-zA-Z0-9-.+]+).base64,.*/", $img64) == FALSE) || getimagesize($img64)[0] < $width_min || getimagesize($img64)[1] < $height_min;
       
    }
    
    function JWT_SOA_FIE($token_onboarding, $accion_usuario, $accion_fecha, $testing, $renovar_token=0)
    {
        $this->load->model('mfunciones_logica');
        
        if($token_onboarding == '' || $renovar_token == 1)
        {
            // Es creación de JWT
            
            // No existe el token, por ende no exsite el JWT, o caducó, se procede a crearlo

            // Obtener parámetros de configuración
            $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);

            $parametros = array(
                'client_id' => 'msa-client',
                'grant_type' => 'password',
                'client_secret' => $arrConf[0]['conf_jwt_client_secret'],
                'scope' => 'openid',
                'username' => $arrConf[0]['conf_jwt_username'],
                'password' => $arrConf[0]['conf_jwt_password']
            );

            $resultado_soa_fie = $this->Cliente_SOA_FIE($token_onboarding, $arrConf[0]['conf_jwt_ws_uri'], $parametros, $accion_usuario, $accion_fecha, $testing, 1);

            if($resultado_soa_fie->ws_error == TRUE)
            {
                return FALSE;
            }
            
            if($token_onboarding == '')
            {
                return $resultado_soa_fie->ws_result['access_token'];
            }
        }
        
        // Continúa si no es Renovación ni Creación

        $parametros_token = $this->mfunciones_generales->TokenOnboarding_parametros($token_onboarding);

        if ($parametros_token == FALSE) 
        {
            return FALSE;
        }
        else
        {
            if($renovar_token == 1)
            {
                // Se requiere renovar el JWT, se actualiza el registro en base al token-onboarding
                $this->mfunciones_logica->token_update_jwt($resultado_soa_fie->ws_result['access_token'], $token_onboarding, $accion_usuario, $accion_fecha);
                return TRUE;
            }
            // El token existe, se procede a retornar el JWT asociado
            return $parametros_token['token_jwt'];
        }
            
    }
    
    /*************** INTEGRACIÓN WS SMS - INICIO ****************************/
    
    function Cliente_SOA_FIE_Generico($token_onboarding, $param__end_point, $parametros, $accion_usuario, $accion_fecha, $testing=0, $generate_token=0)
    {
        $this->load->model('mfunciones_logica');
        
        $resultado = new stdClass();
        $resultado->ws_end_point = '';
        $resultado->ws_token = '';
        $resultado->ws_params = '';
        $resultado->ws_httpcode = 500;
        $resultado->ws_result = FALSE;
        $resultado->ws_error = TRUE;
        $resultado->ws_error_text = '';
        $resultado->ws_action = '';
        
        try 
        {
            $contador_control_max = 3; // <-- Se establece la cantidad máxima de veces que puede reintentar la re-generacion del token (Sólo para errores 401: Token expired)
            $contador_control = 1; // <-- Se inicializa en 1
            
            // Bucle de reintentos sólo para error 401: Token expired
            while ($contador_control <= $contador_control_max)
            {
                $contador_control++;
                // CLIENTE REST

                $process = curl_init($param__end_point);

                if($generate_token == 1)
                {   // Para creación de JWT
                    $additionalHeaders = 'content-type: application/x-www-form-urlencoded';
                    curl_setopt($process, CURLOPT_HTTPHEADER, array('cache-control: no-cache', $additionalHeaders));
                    curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($parametros));
                }
                else
                {
                    // Se llama a la función para obtener el JWT y colocarlo por cabecera
                    $jwt_soa = $this->JWT_SOA_FIE($token_onboarding, $accion_usuario, $accion_fecha, $testing);

                    // Si da algún tipo de error, se responde con error
                    if($jwt_soa == FALSE)
                    {
                        $resultado->ws_error = TRUE;
                        return $resultado;
                    }

                    $resultado->ws_token = $jwt_soa;

                    $additionalHeaders = "Authorization: Bearer " . $jwt_soa; // <-- JWT
                    curl_setopt($process, CURLOPT_HTTPHEADER, array('content-type: application/json', $additionalHeaders));
                    curl_setopt($process, CURLOPT_POSTFIELDS, json_encode($parametros, true));
                }

                curl_setopt($process, CURLOPT_HEADER, false);
                curl_setopt($process, CURLOPT_TIMEOUT, 120);
                curl_setopt($process, CURLOPT_POST, true);
                curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($process, CURLOPT_VERBOSE, true);

                $return = curl_exec($process);

                if($return === FALSE)
                {
                    $resultado->ws_action = 'WITH_ERROR_' . (string)$resultado->ws_httpcode;
                    $resultado->ws_result = 'cURL: ' . curl_error($process);
                }
                else
                {
                    $resultado->ws_httpcode = curl_getinfo($process, CURLINFO_HTTP_CODE);

                    $resultado->ws_result = json_decode($return, true);

                    if(json_last_error() !== JSON_ERROR_NONE)
                    {
                        $resultado->ws_error = TRUE;
                        $resultado->ws_error_text = "Ocurrio un evento inesperado. ";

                        switch (json_last_error()) {
                        case JSON_ERROR_DEPTH:
                            $resultado->ws_error_text .=  "Maximum stack depth exceeded";
                            break;
                        case JSON_ERROR_STATE_MISMATCH:
                            $resultado->ws_error_text .=  "Invalid or malformed JSON";
                            break;
                        case JSON_ERROR_CTRL_CHAR:
                            $resultado->ws_error_text .=  "Control character error";
                            break;
                        case JSON_ERROR_SYNTAX:
                            $resultado->ws_error_text .=  "Syntax error";
                            break;
                        case JSON_ERROR_UTF8:
                            $resultado->ws_error_text .=  "Malformed UTF-8 characters";
                            break;
                        default:
                            $resultado->ws_error_text .=  "Unknown error";
                            break;
                        }

                        $resultado->ws_action = 'WITH_ERROR_' . (string)$resultado->ws_httpcode;
                        $resultado->ws_result = $resultado->ws_error_text;
                    }
                    else
                    {
                        switch ((int)$resultado->ws_httpcode) {
                            case 200:
                                // Exitoso
                                if($generate_token == 0)
                                { // Si no se está generando el token, se valida la estructura
                                    $resultado->ws_error = FALSE;
                                    $resultado->ws_action = 'SUCCESS_' . (string)$resultado->ws_httpcode;
                                }
                                else
                                {
                                    if(!isset($resultado->ws_result['access_token']))
                                    {
                                        $resultado->ws_error = TRUE;
                                        $resultado->ws_error_text = 'No access_token';
                                        $resultado->ws_result = $resultado->ws_error_text;
                                    }
                                    else
                                    {
                                        $resultado->ws_error = FALSE;
                                        $resultado->ws_action = 'SUCCESS_' . (string)$resultado->ws_httpcode;
                                    }
                                }

                                // Se asigna el valor al contador de control el máximo permitido +1 para que salga del flujo
                                $contador_control = $contador_control_max+1;

                                break;
                                
                            case 401:
                                
                                $resultado->ws_action = 'WITH_ERROR_' . (string)$resultado->ws_httpcode;
                                
                                // Token expired y No es generación de Token
                                if($generate_token == 0)
                                {
                                    // Llamar a la función para generar nuevo token
                                    $jwt_soa = $this->JWT_SOA_FIE($token_onboarding, $accion_usuario, $accion_fecha, $testing, 1);

                                    // Si da algún tipo de error, se responde con error
                                    if($jwt_soa == FALSE)
                                    {
                                        $resultado->ws_error = TRUE;
                                        return $resultado;
                                    }
                                }
                                else
                                {
                                    // Se asigna el valor al contador de control el máximo permitido +1 para que salga del flujo
                                    $contador_control = $contador_control_max+1;
                                }
                                
                                break;
                                
                            default:
                                
                                $resultado->ws_error = TRUE;
                                $resultado->ws_action = 'WITH_ERROR_' . (string)$resultado->ws_httpcode;
                                
                                // Se asigna el valor al contador de control el máximo permitido +1 para que salga del flujo
                                $contador_control = $contador_control_max+1;
                                
                                break;
                        }
                    }
                }

                curl_close($process);
            }
            
            // Resultado del proceso
            if($testing == 0)
            {   // Si no es test, Guardar el log del WS-SOA-FIE
                
                    $token_concatenado = '';
                    if($token_onboarding != '')
                    {
                        $parametros_token = $this->mfunciones_generales->TokenOnboarding_parametros($token_onboarding, 'guardado_log');
                        $token_concatenado = $token_onboarding . '_' . $parametros_token['token_id'];
                    }
                    
                $ws_code_audit = $this->mfunciones_logica->InsertarAuditoria_WS_SOA_FIE($resultado->ws_action, $param__end_point, json_encode($parametros), json_encode($resultado->ws_result), $token_concatenado, (isset($parametros_token['cI_numeroraiz']) ? $parametros_token['cI_numeroraiz'] : ''), $accion_usuario, $accion_fecha);
                $resultado->ws_code_audit = $ws_code_audit;
            }
            else
            {
                $resultado->ws_end_point = $param__end_point;
                $resultado->ws_params = $parametros;
            }
                    
            return $resultado;
        
        }
        catch (Exception $ex) {
            $resultado = new stdClass();
            $resultado->ws_end_point = '';
            $resultado->ws_token = '';
            $resultado->ws_params = '';
            $resultado->ws_result = "Ocurrio un evento inesperado. " . $ex;
            $resultado->ws_httpcode = '500';
            $resultado->ws_error = TRUE;
            $resultado->ws_error_text = $resultado->ws_result;
            $resultado->ws_action = 'WITH_ERROR_' . (string)$resultado->ws_httpcode;
            
            if($testing == 0)
            {
                // Si no es test, Guardar el log del WS-SOA-FIE
                    
                    $token_concatenado = '';
                    if($token_onboarding != '')
                    {
                        $parametros_token = $this->mfunciones_generales->TokenOnboarding_parametros($token_onboarding, 'guardado_log');
                        $token_concatenado = $token_onboarding . '_' . $parametros_token['token_id'];
                    }
                    
                $ws_code_audit = $this->mfunciones_logica->InsertarAuditoria_WS_SOA_FIE($resultado->ws_action, $param__end_point, json_encode($parametros), json_encode($resultado->ws_result), $token_concatenado, (isset($parametros_token['cI_numeroraiz']) ? $parametros_token['cI_numeroraiz'] : ''), $accion_usuario, $accion_fecha);
                $resultado->ws_code_audit = $ws_code_audit;
            }
            else
            {
                $resultado->ws_end_point = $param__end_point;
                $resultado->ws_params = $parametros;
            }
            
            return $resultado;
        }
    }
    
    function PIN_Onboarding_generar($numero_celular) {
        
        try {
            
            $this->load->model('mfunciones_logica');

            $pin_generado = $this->TokenOnboarding_random_str(4, '0123456789');

            $sw = 0;
            $i = 0;
            // Bucle hasta obtener un token que no esté registrado en la tabla
            while ($sw == 0)
            {
                    // Contador de escape, al superar retorna FALSE
                    $i++; if($i > 50) { return FALSE; }
                
                $arrResultado = $this->mfunciones_logica->pin_existe($numero_celular, $pin_generado);
                $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                
                if (!isset($arrResultado[0]))
                {
                    if((int)$pin_generado != 0)
                    {
                        $sw = 1;
                    }
                }
            }
            
            return $pin_generado;
            
        } catch (Exception $ex) {
            return FALSE;
        }
        
        return FALSE;
    }
    
    /*************** INTEGRACIÓN WS SMS - FIN ****************************/
    
    function Cliente_SOA_FIE($token_onboarding, $param__end_point, $parametros, $accion_usuario, $accion_fecha, $testing=0, $generate_token=0)
    {
        $this->load->model('mfunciones_logica');
        
        $resultado = new stdClass();
        $resultado->ws_end_point = '';
        $resultado->ws_token = '';
        $resultado->ws_params = '';
        $resultado->ws_httpcode = 500;
        $resultado->ws_result = FALSE;
        $resultado->ws_error = TRUE;
        $resultado->ws_error_text = '';
        $resultado->ws_action = '';
        
        try 
        {
            $contador_control_max = 3; // <-- Se establece la cantidad máxima de veces que puede reintentar la re-generacion del token (Sólo para errores 401: Token expired)
            $contador_control = 1; // <-- Se inicializa en 1
            
            // Bucle de reintentos sólo para error 401: Token expired
            while ($contador_control <= $contador_control_max)
            {
                $contador_control++;
                // CLIENTE REST

                $process = curl_init($param__end_point);

                if($generate_token == 1)
                {   // Para creación de JWT
                    $additionalHeaders = 'content-type: application/x-www-form-urlencoded';
                    curl_setopt($process, CURLOPT_HTTPHEADER, array('cache-control: no-cache', $additionalHeaders));
                    curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($parametros));
                }
                else
                {
                    // Se llama a la función para obtener el JWT y colocarlo por cabecera
                    $jwt_soa = $this->JWT_SOA_FIE($token_onboarding, $accion_usuario, $accion_fecha, $testing);

                    // Si da algún tipo de error, se responde con error
                    if($jwt_soa == FALSE)
                    {
                        $resultado->ws_error = TRUE;
                        return $resultado;
                    }

                    $resultado->ws_token = $jwt_soa;

                    $additionalHeaders = "Authorization: Bearer " . $jwt_soa; // <-- JWT
                    curl_setopt($process, CURLOPT_HTTPHEADER, array('content-type: application/json', $additionalHeaders));
                    curl_setopt($process, CURLOPT_POSTFIELDS, json_encode($parametros, true));
                }

                curl_setopt($process, CURLOPT_HEADER, false);
                curl_setopt($process, CURLOPT_TIMEOUT, 120);
                curl_setopt($process, CURLOPT_POST, true);
                curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($process, CURLOPT_VERBOSE, true);

                $return = curl_exec($process);

                if($return === FALSE)
                {
                    $resultado->ws_action = 'WITH_ERROR_' . (string)$resultado->ws_httpcode;
                    $resultado->ws_result = 'cURL: ' . curl_error($process);
                }
                else
                {
                    $resultado->ws_httpcode = curl_getinfo($process, CURLINFO_HTTP_CODE);

                    $resultado->ws_result = json_decode($return, true);

                    if(json_last_error() !== JSON_ERROR_NONE)
                    {
                        $resultado->ws_error = TRUE;
                        $resultado->ws_error_text = "Ocurrio un evento inesperado. ";

                        switch (json_last_error()) {
                        case JSON_ERROR_DEPTH:
                            $resultado->ws_error_text .=  "Maximum stack depth exceeded";
                            break;
                        case JSON_ERROR_STATE_MISMATCH:
                            $resultado->ws_error_text .=  "Invalid or malformed JSON";
                            break;
                        case JSON_ERROR_CTRL_CHAR:
                            $resultado->ws_error_text .=  "Control character error";
                            break;
                        case JSON_ERROR_SYNTAX:
                            $resultado->ws_error_text .=  "Syntax error";
                            break;
                        case JSON_ERROR_UTF8:
                            $resultado->ws_error_text .=  "Malformed UTF-8 characters";
                            break;
                        default:
                            $resultado->ws_error_text .=  "Unknown error";
                            break;
                        }

                        $resultado->ws_action = 'WITH_ERROR_' . (string)$resultado->ws_httpcode;
                        $resultado->ws_result = $resultado->ws_error_text;
                    }
                    else
                    {
                        switch ((int)$resultado->ws_httpcode) {
                            case 200:
                                // Exitoso
                                if($generate_token == 0)
                                { // Si no se está generando el token, se valida la estructura
                                    if(!isset($resultado->ws_result['result']))
                                    {
                                        $resultado->ws_error = TRUE;
                                        $resultado->ws_error_text = 'No result';
                                        $resultado->ws_result = $resultado->ws_error_text;
                                    }
                                    else
                                    {
                                        $resultado->ws_error = FALSE;
                                        $resultado->ws_action = 'SUCCESS';
                                    }
                                }
                                else
                                {
                                    if(!isset($resultado->ws_result['access_token']))
                                    {
                                        $resultado->ws_error = TRUE;
                                        $resultado->ws_error_text = 'No access_token';
                                        $resultado->ws_result = $resultado->ws_error_text;
                                    }
                                    else
                                    {
                                        $resultado->ws_error = FALSE;
                                        $resultado->ws_action = 'SUCCESS';
                                    }
                                }

                                // Se asigna el valor al contador de control el máximo permitido +1 para que salga del flujo
                                $contador_control = $contador_control_max+1;
                                
                                break;

                            case 401:
                                
                                $resultado->ws_action = 'WITH_ERROR_' . (string)$resultado->ws_httpcode;
                                
                                // Token expired y No es generación de Token
                                if($generate_token == 0)
                                {
                                    // Llamar a la función para generar nuevo token
                                    $jwt_soa = $this->JWT_SOA_FIE($token_onboarding, $accion_usuario, $accion_fecha, $testing, 1);

                                    // Si da algún tipo de error, se responde con error
                                    if($jwt_soa == FALSE)
                                    {
                                        $resultado->ws_error = TRUE;
                                        return $resultado;
                                    }
                                }
                                else
                                {
                                    // Se asigna el valor al contador de control el máximo permitido +1 para que salga del flujo
                                    $contador_control = $contador_control_max+1;
                                }
                                
                                break;
                                
                            default:
                                
                                $resultado->ws_error = TRUE;
                                $resultado->ws_action = 'WITH_ERROR_' . (string)$resultado->ws_httpcode;
                                
                                break;
                        }
                    }
                }

                curl_close($process);
            }
            
            // Resultado del proceso
            if($testing == 0)
            {   // Si no es test, Guardar el log del WS-SOA-FIE
                
                    $token_concatenado = '';
                    if($token_onboarding != '')
                    {
                        $parametros_token = $this->mfunciones_generales->TokenOnboarding_parametros($token_onboarding, 'guardado_log');
                        $token_concatenado = $token_onboarding . '_' . $parametros_token['token_id'];
                    }
                    
                $ws_code_audit = $this->mfunciones_logica->InsertarAuditoria_WS_SOA_FIE($resultado->ws_action, $param__end_point, json_encode($parametros), json_encode($resultado->ws_result), $token_concatenado, (isset($parametros_token['cI_numeroraiz']) ? $parametros_token['cI_numeroraiz'] : ''), $accion_usuario, $accion_fecha);
                $resultado->ws_code_audit = $ws_code_audit;
            }
            else
            {
                $resultado->ws_end_point = $param__end_point;
                $resultado->ws_params = $parametros;
            }
                    
            return $resultado;
        
        }
        catch (Exception $ex) {
            $resultado = new stdClass();
            $resultado->ws_end_point = '';
            $resultado->ws_token = '';
            $resultado->ws_params = '';
            $resultado->ws_result = "Ocurrio un evento inesperado. " . $ex;
            $resultado->ws_httpcode = '500';
            $resultado->ws_error = TRUE;
            $resultado->ws_error_text = $resultado->ws_result;
            $resultado->ws_action = 'WITH_ERROR_' . (string)$resultado->ws_httpcode;
            
            if($testing == 0)
            {
                // Si no es test, Guardar el log del WS-SOA-FIE
                    
                    $token_concatenado = '';
                    if($token_onboarding != '')
                    {
                        $parametros_token = $this->mfunciones_generales->TokenOnboarding_parametros($token_onboarding, 'guardado_log');
                        $token_concatenado = $token_onboarding . '_' . $parametros_token['token_id'];
                    }
                    
                $ws_code_audit = $this->mfunciones_logica->InsertarAuditoria_WS_SOA_FIE($resultado->ws_action, $param__end_point, json_encode($parametros), json_encode($resultado->ws_result), $token_concatenado, (isset($parametros_token['cI_numeroraiz']) ? $parametros_token['cI_numeroraiz'] : ''), $accion_usuario, $accion_fecha);
                $resultado->ws_code_audit = $ws_code_audit;
            }
            else
            {
                $resultado->ws_end_point = $param__end_point;
                $resultado->ws_params = $parametros;
            }
            
            return $resultado;
        }
    }
    
    function Cliente_SOA_FIE_COBIS($info_cliente, $token_onboarding, $param__end_point, $parametros, $accion_usuario, $accion_fecha, $request_get=0, $testing=0, $generate_token=0, $conf_f_cobis_header='')
    {
        $this->load->model('mfunciones_logica');
        
        $resultado = new stdClass();
        $resultado->ws_end_point = '';
        $resultado->ws_token = '';
        $resultado->ws_params = '';
        $resultado->transactionID = '';
        $resultado->ws_httpcode = 500;
        $resultado->ws_result = FALSE;
        $resultado->ws_error = TRUE;
        $resultado->ws_error_text = '';
        $resultado->ws_action = '';
        
        try 
        {
            $contador_control_max = 3; // <-- Se establece la cantidad máxima de veces que puede reintentar la re-generacion del token (Sólo para errores 401: Token expired)
            $contador_control = 1; // <-- Se inicializa en 1
            
            // Bucle de reintentos sólo para error 401: Token expired
            while ($contador_control <= $contador_control_max)
            {
                $contador_control++;
                // CLIENTE REST
            
                $process = curl_init($param__end_point . ($request_get==0 ? '' : '?' . http_build_query( $parametros, '', '&' )));

                if($generate_token == 1)
                {   // Para creación de JWT
                    $additionalHeaders = 'content-type: application/x-www-form-urlencoded';
                    curl_setopt($process, CURLOPT_HTTPHEADER, array('cache-control: no-cache', $additionalHeaders));
                    curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($parametros));
                }
                else
                {
                    // Se llama a la función para obtener el JWT y colocarlo por cabecera
                    $jwt_soa = $this->JWT_SOA_FIE($token_onboarding, $accion_usuario, $accion_fecha, $testing);

                    // Si da algún tipo de error, se responde con error
                    if($jwt_soa == FALSE)
                    {
                        $resultado->ws_error = TRUE;
                        $resultado->ws_error_text = 'Not found front-end token';
                        
                        if($testing == 0)
                        {
                            $this->ExcepcionGenerica($info_cliente);
                        }
                        
                        return $resultado;
                    }

                    $resultado->ws_token = $jwt_soa;

                        if($testing == 1)
                        {
                            $serviceHeader = $conf_f_cobis_header;
                        }
                        else
                        {
                            // Obtener parámetros de configuración
                            $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
                            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
                        
                            $serviceHeader = $arrConf[0]['conf_f_cobis_header'];
                        }

                    $additionalHeaders = "Authorization: Bearer " . $jwt_soa; // <-- JWT
                    $resultado->transactionID = ($testing==1 ? 'testing' : $token_onboarding) . '_' . time();
                    curl_setopt($process, CURLOPT_HTTPHEADER, array(
                                                                'content-type: application/json', 
                                                                $additionalHeaders, 
                                                                'transactionID: ' . $resultado->transactionID,
                                                                'serviceHeader: ' . $serviceHeader,
                                                            ));
                    if($request_get == 0)
                    {
                        curl_setopt($process, CURLOPT_POSTFIELDS, json_encode($parametros, true));
                        curl_setopt($process, CURLOPT_POST, (true));
                    }
                }

                curl_setopt($process, CURLOPT_HEADER, false);
                curl_setopt($process, CURLOPT_TIMEOUT, 120);
                curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($process, CURLOPT_VERBOSE, true);

                $return = curl_exec($process);
                
                if($return === FALSE)
                {
                    $resultado->ws_action = 'WITH_ERROR_' . (string)$resultado->ws_httpcode;
                    $resultado->ws_result = 'cURL: ' . curl_error($process);
                }
                else
                {
                    $resultado->ws_httpcode = curl_getinfo($process, CURLINFO_HTTP_CODE);

                    $resultado->ws_result = json_decode($return, true);

                    if(json_last_error() !== JSON_ERROR_NONE)
                    {
                        $resultado->ws_error = TRUE;
                        $resultado->ws_error_text = "Ocurrio un evento inesperado. ";

                        switch (json_last_error()) {
                        case JSON_ERROR_DEPTH:
                            $resultado->ws_error_text .=  "Maximum stack depth exceeded";
                            break;
                        case JSON_ERROR_STATE_MISMATCH:
                            $resultado->ws_error_text .=  "Invalid or malformed JSON";
                            break;
                        case JSON_ERROR_CTRL_CHAR:
                            $resultado->ws_error_text .=  "Control character error";
                            break;
                        case JSON_ERROR_SYNTAX:
                            $resultado->ws_error_text .=  "Syntax error";
                            break;
                        case JSON_ERROR_UTF8:
                            $resultado->ws_error_text .=  "Malformed UTF-8 characters";
                            break;
                        default:
                            $resultado->ws_error_text .=  "Unknown error";
                            break;
                        }

                        $resultado->ws_action = 'WITH_ERROR_' . (string)$resultado->ws_httpcode;
                        $resultado->ws_result = $resultado->ws_error_text;
                    }
                    else
                    {
                        switch ((int)$resultado->ws_httpcode) {
                            case 200:
                            case 409:
                                // Exitoso
                                if($generate_token == 0)
                                { // Si no se está generando el token, se valida la estructura
                                    $resultado->ws_error = FALSE;
                                    $resultado->ws_action = 'SUCCESS_' . (string)$resultado->ws_httpcode;
                                }
                                else
                                {
                                    if(!isset($resultado->ws_result['access_token']))
                                    {
                                        $resultado->ws_error = TRUE;
                                        $resultado->ws_error_text = 'No access_token';
                                        $resultado->ws_result = $resultado->ws_error_text;
                                    }
                                    else
                                    {
                                        $resultado->ws_error = FALSE;
                                        $resultado->ws_action = 'SUCCESS_' . (string)$resultado->ws_httpcode;
                                    }
                                }

                                // Se asigna el valor al contador de control el máximo permitido +1 para que salga del flujo
                                $contador_control = $contador_control_max+1;

                                break;

                            case 401:

                                $resultado->ws_action = 'WITH_ERROR_' . (string)$resultado->ws_httpcode;

                                // Token expired y No es generación de Token
                                if($generate_token == 0)
                                {
                                    // Llamar a la función para generar nuevo token
                                    $jwt_soa = $this->JWT_SOA_FIE($token_onboarding, $accion_usuario, $accion_fecha, $testing, 1);

                                    // Si da algún tipo de error, se responde con error
                                    if($jwt_soa == FALSE)
                                    {
                                        $resultado->ws_error = TRUE;
                                        return $resultado;
                                    }
                                }
                                else
                                {
                                    // Se asigna el valor al contador de control el máximo permitido +1 para que salga del flujo
                                    $contador_control = $contador_control_max+1;
                                }

                                break;

                            default:

                                $resultado->ws_error = TRUE;
                                $resultado->ws_action = 'WITH_ERROR_' . (string)$resultado->ws_httpcode;

                                // Se asigna el valor al contador de control el máximo permitido +1 para que salga del flujo
                                $contador_control = $contador_control_max+1;
                                
                                break;
                        }
                    }
                }

                curl_close($process);
            }
                
            // Resultado del proceso
            if($testing == 0)
            {   // Si no es test, Guardar el log del WS-SOA-FIE
                
                    $token_concatenado = '';
                    if($token_onboarding != '')
                    {
                        $parametros_token = $this->mfunciones_generales->TokenOnboarding_parametros($token_onboarding, 'guardado_log');
                        $token_concatenado = $token_onboarding . '_' . $parametros_token['token_id'];
                    }
                    
                $ws_code_audit = $this->mfunciones_logica->InsertarAuditoria_WS_SOA_FIE($resultado->ws_action, $param__end_point, json_encode(array_merge(array('transactionID' => $resultado->transactionID), $parametros)), json_encode($resultado->ws_result), $token_concatenado, (isset($parametros_token['cI_numeroraiz']) ? $parametros_token['cI_numeroraiz'] : ''), $accion_usuario, $accion_fecha);
                $resultado->ws_code_audit = $ws_code_audit;
            }
            else
            {
                $resultado->ws_end_point = $param__end_point;
                $resultado->ws_params = $parametros;
            }
        }
        catch (Exception $ex) {
            $resultado = new stdClass();
            $resultado->ws_end_point = '';
            $resultado->ws_token = '';
            $resultado->transactionID = '';
            $resultado->ws_params = '';
            $resultado->ws_result = "Ocurrio un evento inesperado. " . $ex;
            $resultado->ws_httpcode = '500';
            $resultado->ws_error = TRUE;
            $resultado->ws_error_text = $resultado->ws_result;
            $resultado->ws_action = 'WITH_ERROR_' . (string)$resultado->ws_httpcode;
            
            if($testing == 0)
            {
                // Si no es test, Guardar el log del WS-SOA-FIE
                    
                    $token_concatenado = '';
                    if($token_onboarding != '')
                    {
                        $parametros_token = $this->mfunciones_generales->TokenOnboarding_parametros($token_onboarding, 'guardado_log');
                        $token_concatenado = $token_onboarding . '_' . $parametros_token['token_id'];
                    }
                    
                $ws_code_audit = $this->mfunciones_logica->InsertarAuditoria_WS_SOA_FIE($resultado->ws_action, $param__end_point, json_encode($parametros), json_encode($resultado->ws_result), $token_concatenado, (isset($parametros_token['cI_numeroraiz']) ? $parametros_token['cI_numeroraiz'] : ''), $accion_usuario, $accion_fecha);
                $resultado->ws_code_audit = $ws_code_audit;
            }
            else
            {
                $resultado->ws_end_point = $param__end_point;
                $resultado->ws_params = $parametros;
            }
        }
        
        // Control de excepciones del flujo si no es testing y no se esta generando token
        if($testing == 0 && $generate_token == 0)
        {
            $resultado->ws_httpcode = (int)$resultado->ws_httpcode;
            
            // SW reintento
            $sw_reintento = 0;
            $texto_error = '';
            
            if($resultado->ws_httpcode != 200)
            {
                // Excepciones para re-intento del flujo, httpcode 409 y code <= 17000
                switch (true) {
                    // Si el httpcode es 409 y viene con el parámetro 'detail'
                    case ($resultado->ws_httpcode==409 && isset($resultado->ws_result['detail']) && isset($resultado->ws_result['detail'][0]['code'])):
                        if((int)$resultado->ws_result['detail'][0]['code'] <= 17000)
                        {
                            $texto_error = ' => code ' . $resultado->ws_result['detail'][0]['code'] . ': ';

                            if(isset($resultado->ws_result['detail'][0]['message']))
                            {
                                $texto_error .= $resultado->ws_result['detail'][0]['message'];
                            }
                            else
                            {
                                $texto_error .= 'No registrado.';
                            }
                            
                            $sw_reintento = 1;
                        }
                        
                        break;
                    // Si el httpcode es 409 y viene con el parámetro 'detail' e 'Item'
                    case ($resultado->ws_httpcode==409 && isset($resultado->ws_result['detail']) && isset($resultado->ws_result['detail']['Item']['code'])):
                        if((int)$resultado->ws_result['detail']['Item']['code'] <= 17000)
                        {
                            $texto_error = ' => code ' . $resultado->ws_result['detail']['Item']['code'] . ': ';

                            if(isset($resultado->ws_result['detail']['Item']['message']))
                            {
                                $texto_error .= $resultado->ws_result['detail']['Item']['message'];
                            }
                            else
                            {
                                $texto_error .= 'No registrado.';
                            }
                            
                            $sw_reintento = 1;
                        }
                        
                        break;
                    default:
                        $sw_reintento = 1;
                        break;
                }
            }
            elseif(!isset($resultado->ws_result['responseCode']))
            {
                $sw_reintento = 1;
            }
            
            // SW de reintento activado
            if($sw_reintento == 1)
            {
                if($texto_error == '')
                {
                    if(isset($resultado->ws_result['detail'][0]['code']))
                    {
                        $texto_error = ' => code ' . $resultado->ws_result['detail'][0]['code'] . ': ';
                    }
                    else
                    {
                        $texto_error = ' => code ' . $resultado->ws_httpcode . ': ';
                    }
                    
                    if(isset($resultado->ws_result['detail'][0]['message']))
                    {
                        $texto_error .= $resultado->ws_result['detail'][0]['message'];
                    }
                    elseif(isset($resultado->ws_result['message']))
                    {
                        $texto_error .= $resultado->ws_result['message'];
                    }
                    else
                    {
                        $texto_error .= $resultado->ws_result;
                    }
                }
                
                // ++ Registra excepción ++
                $this->FlujoCOBIS_excepcion(
                    20, // <- Código etapa       20=Reintento   21=Derivado a Pendiente      22=Derivado a Rechazado
                    $info_cliente->codigo_agencia_fie, // <- Código agencia del registro
                    'rei', // <- Tipo de excepción  rei=Reintento  pen=Pendiente rec=Rechazado
                    $info_cliente->codigo_solicitud, // <- Cod Sólicitud
                    $info_cliente->codigo_usuario, // <- Cod Usuario
                    'ws_error', // <- Motivo excepción del catálogo
                    htmlspecialchars($texto_error), // <- Motivo Texto personalizado
                    0, // <- Marcar Flag fin de flujo 0-1
                    $info_cliente->accion_usuario // <- User Usuario
                );
                exit();
            }
        }
        
        return $resultado;
    }
    
    function resolver_nombre_compuesto($full_name) {
        $resultado = new stdClass();
        $resultado->first = '';
        $resultado->second = '';

        /* separar el nombre completo en espacios */
        $tokens = explode(' ', trim($full_name));
        /* arreglo donde se guardan las "palabras" del nombre */
        $names = array();
        /* palabras de apellidos (y nombres) compuetos */
        $special_tokens = array('da', 'de', 'del', 'la', 'las', 'los', 'mac', 'mc', 'van', 'von', 'y', 'i', 'san', 'santa', 'a');

        $prev = "";
        foreach ($tokens as $token) {
            $_token = strtolower($token);
            if (in_array($_token, $special_tokens)) {
                $prev .= "$token ";
            } else {
                $names[] = $prev . $token;
                $prev = "";
            }
        }

        $num_nombres = count($names);
        $nombres = $apellidos = "";
        switch ($num_nombres) {
            case 0:
                $nombres = '';
                break;
            case 1:
                $nombres = $names[0];
                break;
            case 2:
                $nombres = $names[0];
                $apellidos = $names[1];
                break;
            case 3:
                $nombres = $names[0] . ' ' . $names[1];
                $apellidos = $names[2];
            default:
                $nombres = $names[0] . ' ' . $names[1];
                unset($names[0]);
                unset($names[1]);

                $apellidos = implode(' ', $names);
                break;
        }

        $resultado->first = $nombres;
        $resultado->second = $apellidos;

        return $resultado;
    }

    function get_nombre_compuesto($full_name) {
        $resultado = new stdClass();
        $resultado->primer_nombre = '';
        $resultado->segundo_nombre = '';
        $resultado->primer_apellido = '';
        $resultado->segundo_apellido = '';

        $base = $this->resolver_nombre_compuesto($full_name);

        $nombres = $this->resolver_nombre_compuesto($base->first);

        $apellidos = $this->resolver_nombre_compuesto($base->second);

        if ($nombres->second != '' && $apellidos->second == '') {
            // Caso especial sin segundo nombre, se mueve el orden
            $resultado->primer_nombre = $nombres->first;
            $resultado->segundo_nombre = '';
            $resultado->primer_apellido = $nombres->second;
            $resultado->segundo_apellido = $apellidos->first;
        } else {
            $resultado->primer_nombre = $nombres->first;
            $resultado->segundo_nombre = $nombres->second;
            $resultado->primer_apellido = $apellidos->first;
            $resultado->segundo_apellido = $apellidos->second;
        }

        return $resultado;
    }

    function validateJSON($string){
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
     }
    
    /*************** AFILIACIÓN POR TERCEROS - FIN ****************************/
     
    /*************** INTEGRACIÓN WS COBIS - INICIO ****************************/
    
    function getTextConfirm($disp=''){
        return "<div id='TextApproveConfirm' class='" . ($disp=='' ? 'PreguntaTexto' : 'color-azul') . "' style='" . ($disp=='' ? 'float: none; width: 90%; clear: both;' : 'font-style: italic; font-weight: bold;') . " text-align: justify;'>" . ($disp=='' ? 'Al proceder' : '<br />Importante: Al consolidar') . ", se marcará el registro como \"" . $this->GetValorCatalogo(1, 'terceros_estado') . "\" 
                e iniciar/continuar el flujo de apertura de cuenta en COBIS (vía Servicios Web) siendo preciso que la información esté completa. 
                Dicho proceso está sujeto a validaciones y lógica de reintentos establecidas, por lo que el resultado no necesariamente 
                será inmediato, considerando lo siguiente:
                <br /><br />
                <ul>
                    <li style='margin-left: 20px;'>Exitoso: Derivado a " . $this->lang->line('AgenciaTercerosTitulo') . ".</li>
                    <li style='margin-left: 20px;'>Casuística detectada: Derivado al estado \"" . $this->GetValorCatalogo(15, 'terceros_estado') . "\" o \"" . $this->GetValorCatalogo(4, 'terceros_estado') . "\" (según el caso).</li>
                </ul></div>";
    }
    
    function Aprobar_FlujoCobis_background($codigo_registro, $codigo_usuario, $accion_usuario) {
        
        $this->load->helper(array('form', 'url'));
        $this->load->library('mylibrary');
        $this->load->library('encrypt');
        
        ignore_user_abort(1); // Que continue aun cuando el usuario se haya ido
        
        // Dirección del Controlador LOGICA DE NEGOCIO
        $url = base_url() . "Afiliador/Aprobar/Guardar_BG";
        
        // Se capturan los valores
        
        $param = array(
            'ortsiger_ogidoc' => $this->encrypt->encode($codigo_registro),
            'codigo_usuario' => $codigo_usuario,
            'accion_usuario' => $accion_usuario
            );
        
        $this->mylibrary->do_in_background($url, $param);
        
        return TRUE;
    }
    
    function GeneraContrato_terceros($arrDatos, $accion_usuario, $accion_fecha)
    {   
        $this->lang->load('general', 'castellano');

        try
        {
            $codigo_prospecto = $arrDatos[0]['terceros_id'];
            
            $codigo_documento = 13;

            $arrResultado1 = $this->mfunciones_logica->ObtenerNombreDocumento($codigo_documento);        
            $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

            // 1. Obtener el nombre del documento

            if (isset($arrResultado1[0])) 
            {
                $nombre_documento = 'COD_' . $codigo_prospecto . '_' . $this->TextoNoAcentoNoEspacios($arrResultado1[0]['documento_nombre']);

                //Se añade la fecha y hora al final
                $nombre_documento .= '__' . date('Y-m-d_H_i_s') . '.pdf';
                $path = RUTA_TERCEROS . 'ter_' . $codigo_prospecto . '/' . $nombre_documento;
            }
            else 
            {
                return FALSE;
            }

            // Ajusta el limite de memoria, puede no ser requerido según instalación del servidor
            if((int)(ini_get('memory_limit')) < 128)
            {
                ini_set("memory_limit",'128M');
            }

            // Armar valores para la vista
            
            $datos['lugar'] = mb_strtoupper($this->GetValorCatalogoDB($arrDatos[0]['dir_departamento'], 'dir_departamento'));
            $datos['fecha_actual'] = date('d/m/Y');
            $datos['nombre_completo'] = mb_strtoupper($arrDatos[0]['terceros_nombre_completo']);
            $datos['cedula_identidad'] = mb_strtoupper($arrDatos[0]['cedula_identidad']);
            
            // Armar imagen de la firma
            
            switch ((int)$arrDatos[0]['tercero_asistencia']) {
                case 0:
                    // No Asistido
                    $result_imagen = $this->mfunciones_generales->GetInfoTercerosDigitalizado($codigo_prospecto, 12, 'documento');
                    $result_imagen = preg_replace('#^data:image/[^;]+;base64,#', '', $result_imagen);
                    
                    break;

                case 1:
                    // Asistido
                    $result_imagen = $this->mfunciones_generales->GetInfoTercerosDigitalizadoPDF($codigo_prospecto, 12, 'documento');

                    break;
                
                default:
                    return FALSE;
                    break;
            }
            
            if($result_imagen != FALSE)
            {
                // Convertir a JPG
                $imagick = new Imagick();
                $imagick->readImageBlob(base64_decode($result_imagen));
                $imagick->setImageFormat("jpeg");
                $imageBlob = $imagick->getImageBlob();
                $imagick->clear();

                $datos['imagen_firma'] = base64_encode($imageBlob);
            }
            else
            {
                $datos['imagen_firma'] = '';
            }
            
            $html = $this->load->view('terceros/view_plantilla_contrato', $datos, true);
            $this->load->library('pdf');
            $pdf = $this->pdf->load();

            $pdf->WriteHTML($html);

            $pdf->Output($path,'F');

            $this->mfunciones_logica->InsertarDocumentoTercero($codigo_prospecto, $codigo_documento, $nombre_documento, $accion_usuario, $accion_fecha);
            
            // Verificar si el PDF se genero en la ruta correcta
            if(!$this->mfunciones_generales->GetInfoTercerosDigitalizadoPDFaux($codigo_prospecto, 13, 'existe'))
            {
               return FALSE;
            }
            
            return TRUE;
        }
        catch (Exception $exc)
        {
            return FALSE;
        }
    }
    
    function getIntentosFlujoCOBIS()
    {
        $resultado = new stdClass();
        $resultado->conf_f_cobis_intentos = 0;
        $resultado->conf_f_cobis_intentos_operativo = 0;
        
        $arrResultado1 = $this->mfunciones_logica->obtenerIntentosFlujoCOBIS();        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            $resultado->conf_f_cobis_intentos_operativo = (int)$arrResultado1[0]['conf_f_cobis_intentos_operativo'];
            
            if((int)$arrResultado1[0]['conf_f_cobis_intentos'] > 0)
            {
                $resultado->conf_f_cobis_intentos = (int)$arrResultado1[0]['conf_f_cobis_intentos'];
            }
        }
        
        return $resultado;
    }
    
    function getTablaTrackingFlujoCOBIS($texto)
    {
        /*
            1. Fecha
            2. Etapa
            3. Excepción
            4. Motivo
            5. Rechazo
            6. Texto o Respuesta COBIS
        
            Separador de filas: ^^
            Separador de columnas: |^|

        */

        if(strlen((string)$texto) < 3)
        {
            return '<br /> Aún no registrado';
        }

        $filas = explode('^^', rtrim($texto, '^^'));

        if(count($filas) <= 0)
        {
            return 'No registrado';
        }

        $tabla = '<table class="tblListas Centrado" border="0" align="center" style="width: 95%;">
                    <tr class="FilaBlanca">
                        <th style="width: 20%;"> Fecha </td>
                        <th style="width: 30%;"> Etapa </td>
                        <th style="width: 40%;"> Excepción </td>
                        <th style="width: 10%;"> Finaliza </td>
                    </tr>';

        foreach ($filas as $key => $value) 
        {
                $col = explode('|^|', $value);

                if(count($col) != 6)
                {
                    return '<i>Sin formato: ' . $texto . '</i>';
                }
                
                $tabla .= ' <tr class="FilaBlanca">
                                <td style="text-align: center;">' . $this->getFormatoFechaD_M_Y_H_M_S($col[0]) . '</td>
                                <td style="text-align: center;">' . $this->GetValorCatalogo($col[1], 'flujo_cobis_etapa') . '</td>
                                <td style="text-align: center;">' . ((int)$col[2]==0 ? 'No' : 'Si: ' . $this->GetValorCatalogoDB($col[3], 'motivo_flujo_cobis')) . ((string)$col[5]=='' ? '' : '<br /><i>' . $col[5] . '</i>') . '</td>
                                <td style="text-align: center;">' . $this->GetValorCatalogo($col[4], 'si_no') . '</td>
                            </tr>
                ';
        }

        $tabla .= '</table>';

        return $tabla;
    }
    
    function ValidateIsJSON($string){
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
    
    function NotificarFlujoCOBIS_incompleto($codigo_solicitud, $codigo_agencia_fie)
    {
        // Obtiene el listado de usuarios a notificar de la etapa "Alerta Servicio Web Registro COBIS"  => 17
        $codigo_etapa = 17;

        $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa($codigo_etapa);

        if($arrEtapa[0]['etapa_notificar_correo'] == 1)
        {
            $this->Verificar_ConvertirArray_Hacia_Matriz($arrEtapa);
            $arr_usuarios = $this->getListadoUsuariosEtapa($codigo_etapa, $codigo_agencia_fie);
            if (isset($arr_usuarios[0]))
            {
                foreach ($arr_usuarios as $key => $value) 
                {
                    $this->EnviarCorreo('ws_cobis_incompleto', $value['usuario_email'], implode(' ', array($value['usuario_nombres'], $value['usuario_app'], $value['usuario_apm'])), $codigo_solicitud, "", "", "");
                }
            }
        }
    }
    
    function FlujoCOBIS_excepcion($etapa_flujo, $codigo_agencia_fie, $tipo_excepcion, $codigo_solicitud, $codigo_usuario, $f_cobis_excepcion_motivo, $f_cobis_excepcion_motivo_texto, $f_cobis_flag_rechazado, $nombre_usuario)
    {
        $fecha_actual = date('Y-m-d H:i:s');
        
        $this->mfunciones_logica->setFlujoCOBIS_excepcion(
            $codigo_solicitud, 
            $codigo_usuario, // <- Cod Usuario
            $f_cobis_excepcion_motivo, // <- Motivo excepción del catálogo
            substr($f_cobis_excepcion_motivo_texto,0,400), // <- Motivo Texto personalizado
            $f_cobis_flag_rechazado, // <- Marcar Flag fin de flujo 0-1
            $fecha_actual // <- Fecha actual
        );
        
        if($tipo_excepcion == 'pen')
        {
            // Derivar a Pendiente
            $this->mfunciones_logica->ReservarSolicitudTerceros($nombre_usuario, $fecha_actual, $codigo_solicitud);
        }
        if($tipo_excepcion == 'rec')
        {
            // Derivar a Rechazar
            $this->mfunciones_logica->RechazarSolicitudTerceros($this->GetValorCatalogoDB($f_cobis_excepcion_motivo, 'motivo_flujo_cobis') . ($f_cobis_excepcion_motivo_texto=='' ? '' : ' - ' . $f_cobis_excepcion_motivo_texto), 4, 0, '', '', $codigo_usuario, $nombre_usuario, $fecha_actual, $codigo_solicitud);

            // Notificar Proceso Onboarding     0=No Regionalizado      1=Si Regionalizado
            $this->mfunciones_generales->NotificacionEtapaTerceros($codigo_solicitud, 10, 1);

            // FUNCION PARA OBTENER EL CÓDIGO DE PROSPECTO EN BASE AL CODIGO TERCERO
            $codigo_prospecto = $this->mfunciones_generales->ObtenerProspectoTerceros($codigo_solicitud);
            // Detalle del Prospecto
            $arrResultado3 = $this->mfunciones_logica->VerificaProspectoConsolidado($codigo_prospecto);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

            // Se registra para las Etapas Onboarding   Pasa a: Notificar Rechazo Onboarding   Etapa Nueva     Etapa Actual
            $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 10, 7, $nombre_usuario, $fecha_actual, 0);
        }
        
        if($tipo_excepcion == 'com')
        {
            // Derivar a la bandeja de Onboarding Contrato
            $this->mfunciones_logica->CompletarCOBISTerceros_fCOBIS($codigo_usuario, $nombre_usuario, $fecha_actual, $codigo_solicitud);
        
            // Notificar Proceso Onboarding     0=No Regionalizado      1=Si Regionalizado
            $this->mfunciones_generales->NotificacionEtapaTerceros($codigo_solicitud, 9, 1);

            // FUNCION PARA OBTENER EL CÓDIGO DE PROSPECTO EN BASE AL CODIGO TERCERO
            $codigo_prospecto = $this->mfunciones_generales->ObtenerProspectoTerceros($codigo_solicitud);

            // Se registra para las Etapas Onboarding   Pasa a: Bandeja Onboarding Contrato   Etapa Nueva     Etapa Actual
            $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, 9, 8, $nombre_usuario, $fecha_actual, 0);
        }
        
        // Se notifica para todos los casos excepto para reintento; ya que el flujo aún no terminó y busca reintentar.
        if($tipo_excepcion != 'rei')
        {
            $this->NotificarFlujoCOBIS_incompleto($codigo_solicitud, $codigo_agencia_fie);
        }
        
        // Registro tracking                                                Excepción       0=No    1=Si
        $this->FlujoCOBIS_tracking($codigo_solicitud, $fecha_actual, $etapa_flujo, 1, $f_cobis_excepcion_motivo, $f_cobis_excepcion_motivo_texto, $f_cobis_flag_rechazado);
    }
    
    function FlujoCOBIS_tracking($codigo_solicitud, $fecha_actual, $etapa_flujo, $excepcion=0, $f_cobis_excepcion_motivo='', $f_cobis_excepcion_motivo_texto='', $f_cobis_flag_rechazado=0)
    {
        /*
            1. Fecha
            2. Etapa
            3. Excepción
            4. Motivo
            5. Rechazo
            6. Texto o Respuesta COBIS
        
            Separador de filas: ^^
            Separador de columnas: |^|

        */
        
        $texto_armado = implode('|^|', array($fecha_actual, $etapa_flujo, $excepcion, $f_cobis_excepcion_motivo, $f_cobis_flag_rechazado, $f_cobis_excepcion_motivo_texto));
        $texto_armado .= '^^';
        
        $this->mfunciones_logica->setFlujoCOBIS_tracking($codigo_solicitud, $texto_armado);
    }
    
    function FlujoCOBIS_marcar_etapa($codigo_solicitud, $etapa_flujo, $codigo_usuario, $referencia_flujo=0)
    {
        $fecha_actual = date('Y-m-d H:i:s');
        
        if($referencia_flujo == 0)
        {
            // Registro de etapa
            $this->mfunciones_logica->setFlujoCOBIS_marcar_etapa($codigo_solicitud, $etapa_flujo, $codigo_usuario, $fecha_actual);
        }
        
        // Registro tracking
        $this->FlujoCOBIS_tracking($codigo_solicitud, $fecha_actual, $etapa_flujo);
    }
    
    function ExcepcionGenerica($info_cliente, $salir=1)
    {
        // ++ Registra excepción ++
        $this->FlujoCOBIS_excepcion(
            20, // <- Código etapa       20=Reintento   21=Derivado a Pendiente      22=Derivado a Rechazado
            $info_cliente->codigo_agencia_fie, // <- Código agencia del registro
            'rei', // <- Tipo de excepción  rei=Reintento  pen=Pendiente rec=Rechazado
            $info_cliente->codigo_solicitud, // <- Cod Sólicitud
            $info_cliente->codigo_usuario, // <- Cod Usuario
            'ws_error', // <- Motivo excepción del catálogo
            htmlspecialchars('Excepción Genérica conexión WS.'), // <- Motivo Texto personalizado
            0, // <- Marcar Flag fin de flujo 0-1
            $info_cliente->accion_usuario // <- User Usuario
        );
        if($salir == 1){ exit(); }
    }
    
    function obtener_Sec_Sub_Act_from_fie($act_fie, $sector)
    {
        $resultado = new stdClass();
        $resultado->ae_sector_economico = $sector;
        $resultado->ae_subsector_economico = '';
        $resultado->ae_actividad_economica = '';
        
        $arrResultado1 = $this->mfunciones_logica->get_Sec_Sub_Act_from_fie($act_fie);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            $resultado->ae_sector_economico = (string)$arrResultado1[0]['ae_sector_economico'];
            $resultado->ae_subsector_economico = (string)$arrResultado1[0]['ae_subsector_economico'];
            $resultado->ae_actividad_economica = (string)$arrResultado1[0]['ae_actividad_economica'];
        }
        
        return $resultado;
    }
    
    /*************** INTEGRACIÓN WS COBIS - FIN ****************************/
    
}
?>