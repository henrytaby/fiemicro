
    <div class="mensaje_advertencia"> <?php echo $this->lang->line('reportes_ayuda_pivot'); ?> </div>

    <!-- PivotTable.js libs from ../dist -->
    <link rel="stylesheet" type="text/css" href="html_public/css/pivot.css">
    <script type="text/javascript" src="html_public/js/lib/pivot/pivot.js"></script>

    <!-- optional: mobile support with jqueryui-touch-punch -->
    <script type="text/javascript" src="html_public/js/lib/pivot/jquery.ui.touch-punch.min.js"></script>


    <script type="text/javascript">
    // This example loads data from the HTML table below.

    $(function(){
        $("#output").pivotUI($("#input"),
        {
            rows: [""],
            cols: [""]
        });
     });
     
     function VerTablaResumen(id) {

        $("#"+id).slideToggle();
    }
    
    function Exportar(tipo) {
        var dataObj = <?php echo json_encode($parametros)?>;
        dataObj.exportar = tipo;
        dataObj.tabla_pivot = document.getElementById("tabla_resultado_pivot").innerHTML;
        
        var aux_pivot = "";
        
        element = document.getElementsByClassName("pvtAggregator")[0];
        if (typeof(element) != 'undefined' && element != null)
        {
            aux_pivot += " " + element.value;
        }
        
        element = document.getElementsByClassName("pvtAttrDropdown")[0];
        if (typeof(element) != 'undefined' && element != null)
        {
            if(element.value == "")
            {
                aux_pivot += " <i>Sin definir</i>";
            }
            else
            {
                aux_pivot += " " + element.value;
            }
        }
        
        dataObj.criterio_pivot = aux_pivot;
        var data = encodeURI(JSON.stringify(dataObj));
        var w = window.open('about:blank');
        w.document.write('<form method="post" id="formID" action="Consultas/Generar">Generando Reporte...<input type="hidden" value="' + data + '" name="parametros"/></form>');
        w.document.getElementById("formID").submit();
        return false;
    }
    
    </script>
    
    <div style="clear: both;"></div>
    
    <div style="text-align: right;">

        <br />
        
        <a id="btnExportarPDF" class="BotonMinimalistaPequeno" style="width:100px!important;" onclick="return Exportar('pdf');"> <?php echo $this->lang->line('reportes_exportar_pdf'); ?> </a>
        &nbsp;&nbsp;
        <a id="btnExportarExcel" class="BotonMinimalistaPequeno" style="width:100px!important;" onclick="return Exportar('excel');"> <?php echo $this->lang->line('reportes_exportar_excel'); ?> </a>
        
        <br /><br />
        
    </div>
    
    <div id="output" style="margin: 30px; text-align: left;"></div>

    <br />

    <div id="fuente_data" style="display: none;">
        
        <table id="input" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%; font-size: 8px;">
            <thead>
            <tr>
                
                <th style="width: 2%;"> Código Cliente </th>
                <th style="width: 2%;"><?php echo $this->lang->line('general_solicitante_corto'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('general_ci'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('import_campana'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('import_agente'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_etapa_actual'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('general_actividad'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('general_destino'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_actividades'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_jda_eval'); ?></th>
                
                <th style="width: 2%;"><?php echo $this->lang->line('estudio_agencia_nombre'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('estudio_agencia_departamento'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('estudio_agencia_provincia'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('estudio_agencia_ciudad'); ?></th>
                
                <th style="width: 2%;"><?php echo $this->lang->line('sol_proviene'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_id'); ?></th>
                <th style="width: 2%;">Solicitud de Crédito - <?php echo $this->lang->line('sol_moneda'); ?></th>
                <th style="width: 2%;">Solicitud de Crédito - <?php echo $this->lang->line('sol_monto'); ?></th>
                <th style="width: 2%;">Solicitud de Crédito - <?php echo $this->lang->line('sol_monto_bs'); ?></th>
                <th style="width: 2%;">Solicitud de Crédito - <?php echo $this->lang->line('sol_detalle'); ?></th>
                <th style="width: 2%;">Solicitud de Crédito - <?php echo $this->lang->line('sol_plazo'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_consolidado_usuario'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_consolidado_fecha'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_consolidado_geo'); ?></th>
                
                <th style="width: 2%;"><?php echo $this->lang->line('sol_asistencia'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_codigo_rubro'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_agencia_nombre'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_agencia_departamento'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_agencia_provincia'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_agencia_ciudad'); ?></th>
                
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_fecha_asignacion'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_fecha_conclusion'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_checkin'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_checkin_fecha'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_checkin_geo'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_llamada'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_llamada_fecha'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_llamada_geo'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_observado_app'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_consolidado'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_consolidar_fecha'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_consolidar_geo'); ?></th>
                
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_evaluacion'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_jda_eval') . ' - ' . $this->lang->line('prospecto_jda_eval_texto'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_jda_eval_usuario'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_jda_eval_fecha'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_desembolso'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_desembolso_monto'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_desembolso_usuario'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_desembolso_fecha'); ?></th>
                
                <th style="width: 2%;"><?php echo $this->lang->line('prospecto_principal'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('general_telefono'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('general_email'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('general_direccion'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('general_actividad'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('general_comentarios'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('general_interes'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('operacion_antiguedad'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('operacion_tiempo'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('operacion_dias'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('frec_seleccion'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('capacidad_criterio'); ?></th>
                <th style="width: 2%;">Estacionalidad Seleccionada</th>
                <th style="width: 2%;"><?php echo $this->lang->line('ingreso_ventas'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('costo_ventas'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('utilidad_bruta'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('utilidad_operativa'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('utilidad_neta'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('saldo_disponible'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('margen_ahorro'); ?></th>
                
            </tr>
            </thead>
            <tbody>
            <?php foreach ($resultado->filas as $fila):?>
                <tr class="FilaBlanca">
                    
                    <td align="center"><?php echo PREFIJO_PROSPECTO . $fila->prospecto_id?></td>
                    <td align="center"><?php echo $fila->general_solicitante; ?></td>
                    <td align="center"><?php echo $fila->general_ci . ' ' . $this->mfunciones_generales->GetValorCatalogo($fila->general_ci_extension, 'extension_ci'); ?></td>
                    <td align="center"><?php echo $fila->camp_nombre?></td>
                    <td align="center"><?php echo $fila->ejecutivo_nombre?></td>
                    <td align="center"><?php echo $fila->etapa_nombre?></td>
                    <td align="center"><?php echo $fila->general_actividad?></td>
                    <td align="center"><?php echo $fila->general_destino?></td>
                    <td align="center"><?php echo $this->mfunciones_generales->GetValorCatalogo($fila->prospecto_id, 'lead_actividades'); ?>
                    <td align="center"><?php echo $this->mfunciones_generales->GetValorCatalogo($fila->prospecto_jda_eval, 'prospecto_evaluacion'); ?></td>
                    
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->estudio_agencia_nombre); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->estudio_agencia_departamento); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->estudio_agencia_provincia); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->estudio_agencia_ciudad); ?></td>
                    
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_estudio); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_id); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_moneda); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_monto); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_monto_bs); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_detalle); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_plazo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_consolidado_usuario); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_consolidado_fecha); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_consolidado_geo); ?></td>
                    
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_asistencia); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_codigo_rubro); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_agencia_nombre); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_agencia_departamento); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_agencia_provincia); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_agencia_ciudad); ?></td>
                    
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_fecha_asignacion); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_fecha_conclusion); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_checkin); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_checkin_fecha); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_checkin_geo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_llamada); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_llamada_fecha); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_llamada_geo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_observado_app); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_consolidado); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_consolidar_fecha); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_consolidar_geo); ?></td>
                    
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_evaluacion); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_jda_eval_texto); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_jda_eval_usuario); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_jda_eval_fecha); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_desembolso); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_desembolso_monto); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_desembolso_usuario); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_desembolso_fecha); ?></td>
                    
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->prospecto_principal); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->general_telefono); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->general_email); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->general_direccion); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->general_actividad); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->general_comentarios); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->general_interes); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->operacion_antiguedad); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->operacion_tiempo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->operacion_dias); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->frec_seleccion); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->capacidad_criterio); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->estacion_sel_arb); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->ingreso_ventas); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->costo_ventas); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->utilidad_bruta); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->utilidad_operativa); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->utilidad_neta); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->saldo_disponible); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->margen_ahorro); ?></td>
                    
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        
    </div>
