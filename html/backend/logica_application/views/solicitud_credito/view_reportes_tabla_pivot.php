
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
                
                <th style="width: 2%;">Código Solicitud</th>
                
                <th style="width: 2%;"><?php echo $this->lang->line('terceros_columna1'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('import_agente'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_ci'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_nombre_completo'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('terceros_columna5'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_monto'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_detalle'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('terceros_columna8'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('terceros_columna7'); ?></th>
                
                <th style="width: 2%;"><?php echo $this->lang->line('agencia_departamento'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('agencia_provincia'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('agencia_ciudad'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('tercero_asistencia'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('import_campana'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_plazo'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_moneda'); ?></th>
                <th style="width: 2%;">Monto (Valor registrado)</th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_monto_bs'); ?></th>
                <th style="width: 2%;">Asignado</th>
                <th style="width: 2%;">Asignado - Usuario</th>
                <th style="width: 2%;">Asignado - Fecha</th>
                <th style="width: 2%;">Fecha Registro Completado</th>
                <th style="width: 2%;">Consolidado</th>
                <th style="width: 2%;">Consolidado - Usuario</th>
                <th style="width: 2%;">Consolidado - Fecha</th>
                <th style="width: 2%;">Consolidado - GEO</th>
                
                <th style="width: 2%;">Rechazado</th>
                <th style="width: 2%;">Rechazado - Usuario</th>
                <th style="width: 2%;">Rechazado - Fecha</th>
                <th style="width: 2%;">Rechazado - Texto</th>
                
                <th style="width: 2%;">Check-In Visita</th>
                <th style="width: 2%;">Check-In Visita - Fecha</th>
                <th style="width: 2%;">Check-In Visita - GEO</th>
                <th style="width: 2%;">Check-In Llamada</th>
                <th style="width: 2%;">Check-In Llamada - Fecha</th>
                <th style="width: 2%;">Check-In Llamada - GEO</th>
                <th style="width: 2%;">Evaluación</th>
                <th style="width: 2%;">Conversión a Estudio</th>
                <th style="width: 2%;">Conversión a Estudio - Código</th>
                <th style="width: 2%;">Conversión a Estudio - Rubro</th>
                
                <th style="width: 2%;"><?php echo $this->lang->line('sol_ci'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_extension'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_complemento'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_primer_nombre'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_segundo_nombre'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_primer_apellido'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_segundo_apellido'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_correo'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_cel'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_telefono'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_fec_nac'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_est_civil'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_nit'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_cliente'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('sol_dependencia'); ?></th>
                <th style="width: 2%;">Act. Independiente - <?php echo $this->lang->line('sol_indepen_actividad'); ?></th>
                <th style="width: 2%;">Act. Independiente - <?php echo $this->lang->line('sol_indepen_antiguedad'); ?></th>
                <th style="width: 2%;">Act. Independiente - <?php echo $this->lang->line('sol_indepen_atencion'); ?></th>
                <th style="width: 2%;">Act. Independiente - <?php echo $this->lang->line('sol_indepen_horario_dias'); ?></th>
                <th style="width: 2%;">Act. Independiente - <?php echo $this->lang->line('sol_indepen_telefono'); ?></th>
                <th style="width: 2%;">Act. Dependiente - <?php echo $this->lang->line('sol_depen_empresa'); ?></th>
                <th style="width: 2%;">Act. Dependiente - <?php echo $this->lang->line('sol_depen_actividad'); ?></th>
                <th style="width: 2%;">Act. Dependiente - <?php echo $this->lang->line('sol_depen_cargo'); ?></th>
                <th style="width: 2%;">Act. Dependiente - <?php echo $this->lang->line('sol_depen_antiguedad'); ?></th>
                <th style="width: 2%;">Act. Dependiente - <?php echo $this->lang->line('sol_depen_atencion'); ?></th>
                <th style="width: 2%;">Act. Dependiente - <?php echo $this->lang->line('sol_depen_horario_dias'); ?></th>
                <th style="width: 2%;">Act. Dependiente - <?php echo $this->lang->line('sol_depen_telefono'); ?></th>
                <th style="width: 2%;">Act. Dependiente - <?php echo $this->lang->line('sol_depen_tipo_contrato'); ?></th>
                <th style="width: 2%;">Dir. Negocio - <?php echo $this->lang->line('sol_dir_departamento'); ?></th>
                <th style="width: 2%;">Dir. Negocio - <?php echo $this->lang->line('sol_dir_provincia'); ?></th>
                <th style="width: 2%;">Dir. Negocio - <?php echo $this->lang->line('sol_dir_localidad_ciudad'); ?></th>
                <th style="width: 2%;">Dir. Negocio - <?php echo $this->lang->line('sol_cod_barrio'); ?></th>
                <th style="width: 2%;">Dir. Negocio - <?php echo $this->lang->line('sol_direccion_trabajo'); ?></th>
                <th style="width: 2%;">Dir. Negocio - <?php echo $this->lang->line('sol_edificio_trabajo'); ?></th>
                <th style="width: 2%;">Dir. Negocio - <?php echo $this->lang->line('sol_numero_trabajo'); ?></th>
                <th style="width: 2%;">Dir. Negocio - <?php echo $this->lang->line('sol_trabajo_lugar'); ?></th>
                <th style="width: 2%;">Dir. Negocio - <?php echo $this->lang->line('sol_trabajo_realiza'); ?></th>
                <th style="width: 2%;">Dir. Negocio - Referencia Dirección</th>
                <th style="width: 2%;">Dir. Domicilio - <?php echo $this->lang->line('sol_dir_departamento_dom'); ?></th>
                <th style="width: 2%;">Dir. Domicilio - <?php echo $this->lang->line('sol_dir_provincia_dom'); ?></th>
                <th style="width: 2%;">Dir. Domicilio - <?php echo $this->lang->line('sol_dir_localidad_ciudad_dom'); ?></th>
                <th style="width: 2%;">Dir. Domicilio - <?php echo $this->lang->line('sol_cod_barrio_dom'); ?></th>
                <th style="width: 2%;">Dir. Domicilio - <?php echo $this->lang->line('sol_direccion_dom'); ?></th>
                <th style="width: 2%;">Dir. Domicilio - <?php echo $this->lang->line('sol_edificio_dom'); ?></th>
                <th style="width: 2%;">Dir. Domicilio - <?php echo $this->lang->line('sol_numero_dom'); ?></th>
                <th style="width: 2%;">Dir. Domicilio - <?php echo $this->lang->line('sol_dom_tipo'); ?></th>
                <th style="width: 2%;">Dir. Domicilio - Referencia Dirección</th>
                
                <th style="width: 2%;">Cónyuge Registrado</th>
                
                <?php // ------------- ?>
                
                <th style="width: 2%;">Cónyuge - <?php echo $this->lang->line('sol_ci'); ?></th>
                <th style="width: 2%;">Cónyuge - <?php echo $this->lang->line('sol_extension'); ?></th>
                <th style="width: 2%;">Cónyuge - <?php echo $this->lang->line('sol_complemento'); ?></th>
                <th style="width: 2%;">Cónyuge - <?php echo $this->lang->line('sol_primer_nombre'); ?></th>
                <th style="width: 2%;">Cónyuge - <?php echo $this->lang->line('sol_segundo_nombre'); ?></th>
                <th style="width: 2%;">Cónyuge - <?php echo $this->lang->line('sol_primer_apellido'); ?></th>
                <th style="width: 2%;">Cónyuge - <?php echo $this->lang->line('sol_segundo_apellido'); ?></th>
                <th style="width: 2%;">Cónyuge - <?php echo $this->lang->line('sol_correo'); ?></th>
                <th style="width: 2%;">Cónyuge - <?php echo $this->lang->line('sol_cel'); ?></th>
                <th style="width: 2%;">Cónyuge - <?php echo $this->lang->line('sol_telefono'); ?></th>
                <th style="width: 2%;">Cónyuge - <?php echo $this->lang->line('sol_fec_nac'); ?></th>
                <th style="width: 2%;">Cónyuge - <?php echo $this->lang->line('sol_est_civil'); ?></th>
                <th style="width: 2%;">Cónyuge - <?php echo $this->lang->line('sol_nit'); ?></th>
                <th style="width: 2%;">Cónyuge - <?php echo $this->lang->line('sol_cliente'); ?></th>
                <th style="width: 2%;">Cónyuge - <?php echo $this->lang->line('sol_dependencia'); ?></th>
                <th style="width: 2%;">Cónyuge - Act. Independiente - <?php echo $this->lang->line('sol_indepen_actividad'); ?></th>
                <th style="width: 2%;">Cónyuge - Act. Independiente - <?php echo $this->lang->line('sol_indepen_antiguedad'); ?></th>
                <th style="width: 2%;">Cónyuge - Act. Independiente - <?php echo $this->lang->line('sol_indepen_atencion'); ?></th>
                <th style="width: 2%;">Cónyuge - Act. Independiente - <?php echo $this->lang->line('sol_indepen_horario_dias'); ?></th>
                <th style="width: 2%;">Cónyuge - Act. Independiente - <?php echo $this->lang->line('sol_indepen_telefono'); ?></th>
                <th style="width: 2%;">Cónyuge - Act. Dependiente - <?php echo $this->lang->line('sol_depen_empresa'); ?></th>
                <th style="width: 2%;">Cónyuge - Act. Dependiente - <?php echo $this->lang->line('sol_depen_actividad'); ?></th>
                <th style="width: 2%;">Cónyuge - Act. Dependiente - <?php echo $this->lang->line('sol_depen_cargo'); ?></th>
                <th style="width: 2%;">Cónyuge - Act. Dependiente - <?php echo $this->lang->line('sol_depen_antiguedad'); ?></th>
                <th style="width: 2%;">Cónyuge - Act. Dependiente - <?php echo $this->lang->line('sol_depen_atencion'); ?></th>
                <th style="width: 2%;">Cónyuge - Act. Dependiente - <?php echo $this->lang->line('sol_depen_horario_dias'); ?></th>
                <th style="width: 2%;">Cónyuge - Act. Dependiente - <?php echo $this->lang->line('sol_depen_telefono'); ?></th>
                <th style="width: 2%;">Cónyuge - Act. Dependiente - <?php echo $this->lang->line('sol_depen_tipo_contrato'); ?></th>
                <th style="width: 2%;">Cónyuge - Dir. Negocio - <?php echo $this->lang->line('sol_dir_departamento'); ?></th>
                <th style="width: 2%;">Cónyuge - Dir. Negocio - <?php echo $this->lang->line('sol_dir_provincia'); ?></th>
                <th style="width: 2%;">Cónyuge - Dir. Negocio - <?php echo $this->lang->line('sol_dir_localidad_ciudad'); ?></th>
                <th style="width: 2%;">Cónyuge - Dir. Negocio - <?php echo $this->lang->line('sol_cod_barrio'); ?></th>
                <th style="width: 2%;">Cónyuge - Dir. Negocio - <?php echo $this->lang->line('sol_direccion_trabajo'); ?></th>
                <th style="width: 2%;">Cónyuge - Dir. Negocio - <?php echo $this->lang->line('sol_edificio_trabajo'); ?></th>
                <th style="width: 2%;">Cónyuge - Dir. Negocio - <?php echo $this->lang->line('sol_numero_trabajo'); ?></th>
                <th style="width: 2%;">Cónyuge - Dir. Negocio - <?php echo $this->lang->line('sol_trabajo_lugar'); ?></th>
                <th style="width: 2%;">Cónyuge - Dir. Negocio - <?php echo $this->lang->line('sol_trabajo_realiza'); ?></th>
                <th style="width: 2%;">Cónyuge - Dir. Negocio - Referencia Dirección</th>
                <th style="width: 2%;">Cónyuge - Dir. Domicilio - <?php echo $this->lang->line('sol_dir_departamento_dom'); ?></th>
                <th style="width: 2%;">Cónyuge - Dir. Domicilio - <?php echo $this->lang->line('sol_dir_provincia_dom'); ?></th>
                <th style="width: 2%;">Cónyuge - Dir. Domicilio - <?php echo $this->lang->line('sol_dir_localidad_ciudad_dom'); ?></th>
                <th style="width: 2%;">Cónyuge - Dir. Domicilio - <?php echo $this->lang->line('sol_cod_barrio_dom'); ?></th>
                <th style="width: 2%;">Cónyuge - Dir. Domicilio - <?php echo $this->lang->line('sol_direccion_dom'); ?></th>
                <th style="width: 2%;">Cónyuge - Dir. Domicilio - <?php echo $this->lang->line('sol_edificio_dom'); ?></th>
                <th style="width: 2%;">Cónyuge - Dir. Domicilio - <?php echo $this->lang->line('sol_numero_dom'); ?></th>
                <th style="width: 2%;">Cónyuge - Dir. Domicilio - <?php echo $this->lang->line('sol_dom_tipo'); ?></th>
                <th style="width: 2%;">Cónyuge - Dir. Domicilio - Referencia Dirección</th>
                
            </tr>
            </thead>
            <tbody>
            <?php foreach ($resultado->filas as $fila):?>
                <tr class="FilaBlanca">
                    
                    <td align="center">SOL_<?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_id); ?></td>
                    
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->codigo_agencia_fie); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->ejecutivo_nombre); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_ci); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_nombre_completo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_correo . (str_replace(' ', '', $fila->sol_correo)=='' ? '' : '<br />') . $fila->sol_cel); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_monto); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_detalle); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_fecha); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_estado); ?></td>
                    
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->agencia_departamento); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->agencia_provincia); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->agencia_ciudad); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_asistencia); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_codigo_rubro); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_plazo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_moneda); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_monto_valor); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_monto_bs); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_asignado); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_asignado_usuario); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_asignado_fecha); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_registro_completado_fecha); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_consolidado); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_consolidado_usuario); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_consolidado_fecha); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_consolidado_geo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_rechazado); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_rechazado_usuario); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_rechazado_fecha); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_rechazado_texto); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_checkin); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_checkin_fecha); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_checkin_geo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_llamada); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_llamada_fecha); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_llamada_geo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_evaluacion); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_estudio); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_estudio_codigo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_estudio_rubro); ?></td>
                    
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_ci); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_extension); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_complemento); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_primer_nombre); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_segundo_nombre); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_primer_apellido); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_segundo_apellido); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_correo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_cel); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_telefono); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_fec_nac); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_est_civil); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_nit); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_cliente); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_dependencia); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_indepen_actividad); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_indepen_antiguedad); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_indepen_atencion); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_indepen_horario_dias); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_indepen_telefono); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_depen_empresa); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_depen_actividad); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_depen_cargo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_depen_antiguedad); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_depen_atencion); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_depen_horario_dias); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_depen_telefono); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_depen_tipo_contrato); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_dir_departamento); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_dir_provincia); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_dir_localidad_ciudad); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_cod_barrio); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_direccion_trabajo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_edificio_trabajo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_numero_trabajo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_trabajo_lugar); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_trabajo_realiza); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_dir_referencia); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_dir_departamento_dom); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_dir_provincia_dom); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_dir_localidad_ciudad_dom); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_cod_barrio_dom); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_direccion_dom); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_edificio_dom); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_numero_dom); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_dom_tipo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_dir_referencia_dom); ?></td>
                    
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_conyugue); ?></td>
                    
                    <?php // ------------- ?>
                    
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_ci); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_extension); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_complemento); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_primer_nombre); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_segundo_nombre); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_primer_apellido); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_segundo_apellido); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_correo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_cel); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_telefono); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_fec_nac); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_est_civil); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_nit); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_cliente); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_dependencia); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_indepen_actividad); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_indepen_antiguedad); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_indepen_atencion); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_indepen_horario_dias); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_indepen_telefono); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_depen_empresa); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_depen_actividad); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_depen_cargo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_depen_antiguedad); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_depen_atencion); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_depen_horario_dias); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_depen_telefono); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_depen_tipo_contrato); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_dir_departamento); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_dir_provincia); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_dir_localidad_ciudad); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_cod_barrio); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_direccion_trabajo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_edificio_trabajo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_numero_trabajo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_trabajo_lugar); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_trabajo_realiza); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_dir_referencia); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_dir_departamento_dom); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_dir_provincia_dom); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_dir_localidad_ciudad_dom); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_cod_barrio_dom); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_direccion_dom); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_edificio_dom); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_numero_dom); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_dom_tipo); ?></td>
                    <td align="center"><?php echo $this->mfunciones_microcreditos->AuxLimpiarCadena($fila->sol_con_dir_referencia_dom); ?></td>
                    
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        
    </div>
