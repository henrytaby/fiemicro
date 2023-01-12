
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
        w.document.write('<form method="post" id="formID" action="Afiliador/Consultas/Generar">Generando Reporte...<input type="hidden" value="' + data + '" name="parametros"/></form>');
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
                
                <th style="width: 2%;">Código Cliente</th>
                
                <th style="width: 2%;"><?php echo $this->lang->line('tipo_cuenta'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('tercero_asistencia'); ?></th>
                <th style="width: 2%;">Nombre Agente</th>
                <th style="width: 2%;">Agencia Agente</th>
                
                
                <th style="width: 2%;"><?php echo $this->lang->line('terceros_columna1'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('terceros_columna2'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('terceros_columna3'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('terceros_columna4'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('terceros_columna5'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('terceros_columna7'); ?></th>
                <th style="width: 2%;"><?php echo $this->lang->line('terceros_columna8'); ?></th>
                
                <th style='width: 2%;'> <?php echo $this->lang->line('aprobado'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('aprobado_fecha'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('aprobado_usuario'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('cobis'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('cobis_fecha'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('cobis_usuario'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('completado'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('completado_fecha'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('completado_usuario'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('completado_envia'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('completado_texto'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('completado_docs_enviados'); ?></th>
                
                <th style='width: 2%;'> <?php echo $this->lang->line('entregado'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('entregado_fecha'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('entregado_usuario'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('entregado_texto'); ?></th>
                
                <th style='width: 2%;'> <?php echo $this->lang->line('rechazado'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('terceros_observacion'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('rechazado_fecha'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('rechazado_usuario'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('rechazado_envia'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('rechazado_texto'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('rechazado_docs_enviados'); ?></th>
                
                <th style='width: 2%;'> <?php echo $this->lang->line('notificar_cierre'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('notificar_cierre_fecha'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('notificar_cierre_usuario'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('notificar_cierre_causa'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('cuenta_cerrada'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('cuenta_cerrada_fecha'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('cuenta_cerrada_usuario'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('cuenta_cerrada_causa'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('cuenta_cerrada_envia'); ?></th>
                
                <th style='width: 2%;'> <?php echo $this->lang->line('monto_inicial'); ?></th>
                <th style='width: 2%;'> Número de Cuenta (COBIS)</th>
                
                <th style='width: 2%;'> <?php echo $this->lang->line('direccion_email'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('coordenadas_geo_dom'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('coordenadas_geo_trab'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('envio'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('cI_numeroraiz'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('cI_complemento'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('cI_lugar_emisionoextension'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('cI_confirmacion_id'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('di_primernombre'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('di_segundo_otrosnombres'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('di_primerapellido'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('di_segundoapellido'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('di_fecha_nacimiento'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('di_fecha_vencimiento'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('di_indefinido'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('di_genero'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('di_estadocivil'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('di_apellido_casada'); ?></th>

                <th style='width: 2%;'> <?php echo $this->lang->line('dd_profesion'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dd_nivel_estudios'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dd_dependientes'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dd_proposito_rel_comercial'); ?></th>

                <th style='width: 2%;'> <?php echo $this->lang->line('dec_ingresos_mensuales'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dec_nivel_egresos'); ?></th>

                <th style='width: 2%;'> <?php echo $this->lang->line('dir_tipo_direccion'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dir_departamento'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dir_provincia'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dir_localidad_ciudad'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dir_barrio_zona_uv'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dir_ubicacionreferencial'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dir_av_calle_pasaje'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dir_edif_cond_urb'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dir_numero'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dir_tipo_telefono'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dir_notelefonico'); ?></th>

                <th style='width: 2%;'> <?php echo $this->lang->line('ae_sector_economico'); ?></th>
                
                <th style='width: 2%;'> <?php echo $this->lang->line('ae_subsector_economico'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('ae_actividad_ocupacion'); ?></th>
                
                <th style='width: 2%;'> <?php echo $this->lang->line('ae_actividad_fie'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('ae_ambiente'); ?></th>

                <th style='width: 2%;'> <?php echo $this->lang->line('emp_nombre_empresa'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('emp_direccion_trabajo'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('emp_telefono_faxtrabaj'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('emp_tipo_empresa'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('emp_antiguedad_empresa'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('emp_codigo_actividad'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('emp_descripcion_cargo'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('emp_fecha_ingreso'); ?></th>

                <th style='width: 2%;'> <?php echo $this->lang->line('dir_departamento_neg'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dir_provincia_neg'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dir_localidad_ciudad_neg'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dir_barrio_zona_uv_neg'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dir_av_calle_pasaje_neg'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dir_edif_cond_urb_neg'); ?></th>
                <th style='width: 2%;'> <?php echo $this->lang->line('dir_numero_neg'); ?></th>
                
                <th style='width: 2%;'>Referencia -  <?php echo $this->lang->line('rp_nombres'); ?></th>
                <th style='width: 2%;'>Referencia -  <?php echo $this->lang->line('rp_primer_apellido'); ?></th>
                <th style='width: 2%;'>Referencia -  <?php echo $this->lang->line('rp_segundo_apellido'); ?></th>
                <th style='width: 2%;'>Referencia -  <?php echo $this->lang->line('rp_direccion'); ?></th>
                <th style='width: 2%;'>Referencia -  <?php echo $this->lang->line('rp_notelefonicos'); ?></th>
                <th style='width: 2%;'>Referencia -  <?php echo $this->lang->line('rp_nexo_cliente'); ?></th>

                <th style='width: 2%;'>Cónyuge -  <?php echo $this->lang->line('con_primer_nombre'); ?></th>
                <th style='width: 2%;'>Cónyuge -  <?php echo $this->lang->line('con_segundo_nombre'); ?></th>
                <th style='width: 2%;'>Cónyuge -  <?php echo $this->lang->line('con_primera_pellido'); ?></th>
                <th style='width: 2%;'>Cónyuge -  <?php echo $this->lang->line('con_segundoa_pellido'); ?></th>
                <th style='width: 2%;'>Cónyuge -  <?php echo $this->lang->line('con_acteconomica_principal'); ?></th>

                <th style='width: 2%;'> Relación estadounidense</th>
                
                <th style='width: 2%;'> <?php echo $this->lang->line('ws_segip_flag_verificacion'); ?> </th>
                <th style='width: 2%;'> <?php echo $this->lang->line('ws_segip_codigo_resultado'); ?> </th>
                <th style='width: 2%;'> <?php echo $this->lang->line('ws_cobis_codigo_resultado'); ?> </th>
                
                <th style='width: 2%;'> <?php echo $this->lang->line('ws_selfie_codigo_resultado'); ?> </th>
                
                <th style='width: 2%;'> <?php echo $this->lang->line('ws_ocr_codigo_resultado'); ?> </th>
                <th style='width: 2%;'> <?php echo $this->lang->line('ws_ocr_name_valor'); ?> </th>
                <th style='width: 2%;'> <?php echo $this->lang->line('ws_ocr_name_similar'); ?> </th>
                
                <th style='width: 2%;'> <?php echo $this->lang->line('segip_operador_resultado'); ?> </th>
                <th style='width: 2%;'> <?php echo $this->lang->line('segip_operador_fecha'); ?> </th>
                <th style='width: 2%;'> <?php echo $this->lang->line('segip_operador_usuario'); ?> </th>
                <th style='width: 2%;'> <?php echo $this->lang->line('segip_operador_texto'); ?> </th>
                
                <th style='width: 2%;'> <?php echo $this->lang->line('f_cobis_actual_etapa'); ?> </th>
                <th style='width: 2%;'> <?php echo $this->lang->line('f_cobis_actual_intento'); ?> </th>
                <th style='width: 2%;'> <?php echo $this->lang->line('f_cobis_actual_usuario'); ?> </th>
                <th style='width: 2%;'> <?php echo $this->lang->line('f_cobis_actual_fecha'); ?> </th>
                <th style='width: 2%;'> <?php echo $this->lang->line('f_cobis_codigo'); ?> </th>
                <th style='width: 2%;'> <?php echo $this->lang->line('f_cobis_excepcion'); ?> </th>
                <th style='width: 2%;'> <?php echo $this->lang->line('f_cobis_excepcion_motivo'); ?> </th>
                <th style='width: 2%;'> <?php echo $this->lang->line('f_cobis_excepcion_motivo_texto'); ?> </th>
                <th style='width: 2%;'> <?php echo $this->lang->line('f_cobis_flag_rechazado'); ?> </th>
                
            </tr>
            </thead>
            <tbody>
            <?php foreach ($resultado->filas as $fila):?>
                <tr class="FilaBlanca">
                    
                    <td align="center"><?php echo PREFIJO_TERCEROS . $fila->terceros_id?></td>
                    
                    <td align="center"><?php echo $fila->tipo_cuenta; ?></td>
                    
                    <td align="center"><?php echo $fila->tercero_asistencia; ?></td>
                    <td align="center"><?php echo $fila->agente_nombre; ?></td>
                    <td align="center"><?php echo $fila->estructura_regional_nombre; ?></td>
                    
                    
                    <td align="center"><?php echo $fila->terceros_columna1; ?></td>
                    <td align="center"><?php echo $fila->terceros_columna2; ?></td>
                    <td align="center"><?php echo $fila->terceros_columna3; ?></td>
                    <td align="center"><?php echo $fila->terceros_columna4; ?></td>
                    <td align="center"><?php echo $fila->terceros_columna5; ?></td>
                    <td align="center"><?php echo $fila->terceros_columna7; ?></td>
                    <td align="center"><?php echo $fila->terceros_columna8; ?></td>
                    
                    <td align='center'><?php echo $fila->aprobado; ?></td>
                    <td align='center'><?php echo $fila->aprobado_fecha; ?></td>
                    <td align='center'><?php echo $fila->aprobado_usuario; ?></td>
                    <td align='center'><?php echo $fila->cobis; ?></td>
                    <td align='center'><?php echo $fila->cobis_fecha; ?></td>
                    <td align='center'><?php echo $fila->cobis_usuario; ?></td>
                    <td align='center'><?php echo $fila->completado; ?></td>
                    <td align='center'><?php echo $fila->completado_fecha; ?></td>
                    <td align='center'><?php echo $fila->completado_usuario; ?></td>
                    <td align='center'><?php echo $fila->completado_envia; ?></td>
                    <td align='center'><?php echo $fila->completado_texto; ?></td>
                    <td align='center'><?php echo $fila->completado_docs_enviados; ?></td>
                    
                    <td align='center'><?php echo $fila->entregado; ?></td>
                    <td align='center'><?php echo $fila->entregado_fecha; ?></td>
                    <td align='center'><?php echo $fila->entregado_usuario; ?></td>
                    <td align='center'><?php echo $fila->entregado_texto; ?></td>
                    
                    <td align='center'><?php echo $fila->rechazado; ?></td>
                    <td align='center'><?php echo $fila->terceros_observacion; ?></td>
                    <td align='center'><?php echo $fila->rechazado_fecha; ?></td>
                    <td align='center'><?php echo $fila->rechazado_usuario; ?></td>
                    <td align='center'><?php echo $fila->rechazado_envia; ?></td>
                    <td align='center'><?php echo $fila->rechazado_texto; ?></td>
                    <td align='center'><?php echo $fila->rechazado_docs_enviados; ?></td>

                    <td align='center'><?php echo $fila->notificar_cierre; ?></td>
                    <td align='center'><?php echo $fila->notificar_cierre_fecha; ?></td>
                    <td align='center'><?php echo $fila->notificar_cierre_usuario; ?></td>
                    <td align='center'><?php echo $fila->notificar_cierre_causa; ?></td>
                    <td align='center'><?php echo $fila->cuenta_cerrada; ?></td>
                    <td align='center'><?php echo $fila->cuenta_cerrada_fecha; ?></td>
                    <td align='center'><?php echo $fila->cuenta_cerrada_usuario; ?></td>
                    <td align='center'><?php echo $fila->cuenta_cerrada_causa; ?></td>
                    <td align='center'><?php echo $fila->cuenta_cerrada_envia; ?></td>
                    
                    <td align='center'><?php echo $fila->monto_inicial; ?></td>
                    <td align='center'><?php echo $fila->onboarding_numero_cuenta; ?></td>
                    
                    <td align='center'><?php echo $fila->direccion_email; ?></td>
                    <td align='center'><?php echo $fila->coordenadas_geo_dom; ?></td>
                    <td align='center'><?php echo $fila->coordenadas_geo_trab; ?></td>
                    <td align='center'><?php echo $fila->envio; ?></td>
                    <td align='center'><?php echo $fila->cI_numeroraiz; ?></td>
                    <td align='center'><?php echo $fila->cI_complemento; ?></td>
                    <td align='center'><?php echo $fila->cI_lugar_emisionoextension; ?></td>
                    <td align='center'><?php echo $fila->cI_confirmacion_id; ?></td>
                    <td align='center'><?php echo $fila->di_primernombre; ?></td>
                    <td align='center'><?php echo $fila->di_segundo_otrosnombres; ?></td>
                    <td align='center'><?php echo $fila->di_primerapellido; ?></td>
                    <td align='center'><?php echo $fila->di_segundoapellido; ?></td>
                    <td align='center'><?php echo $fila->di_fecha_nacimiento; ?></td>
                    <td align='center'><?php echo $fila->di_fecha_vencimiento; ?></td>
                    <td align='center'><?php echo $fila->di_indefinido; ?></td>
                    <td align='center'><?php echo $fila->di_genero; ?></td>
                    <td align='center'><?php echo $fila->di_estadocivil; ?></td>
                    <td align='center'><?php echo $fila->di_apellido_casada; ?></td>

                    <td align='center'><?php echo $fila->dd_profesion; ?></td>
                    <td align='center'><?php echo $fila->dd_nivel_estudios; ?></td>
                    <td align='center'><?php echo $fila->dd_dependientes; ?></td>
                    <td align='center'><?php echo $fila->dd_proposito_rel_comercial; ?></td>

                    <td align='center'><?php echo $fila->dec_ingresos_mensuales; ?></td>
                    <td align='center'><?php echo $fila->dec_nivel_egresos; ?></td>

                    <td align='center'><?php echo $fila->dir_tipo_direccion; ?></td>
                    <td align='center'><?php echo $fila->dir_departamento; ?></td>
                    <td align='center'><?php echo $fila->dir_provincia; ?></td>
                    <td align='center'><?php echo $fila->dir_localidad_ciudad; ?></td>
                    <td align='center'><?php echo $fila->dir_barrio_zona_uv; ?></td>
                    <td align='center'><?php echo $fila->dir_ubicacionreferencial; ?></td>
                    <td align='center'><?php echo $fila->dir_av_calle_pasaje; ?></td>
                    <td align='center'><?php echo $fila->dir_edif_cond_urb; ?></td>
                    <td align='center'><?php echo $fila->dir_numero; ?></td>
                    <td align='center'><?php echo $fila->dir_tipo_telefono; ?></td>
                    <td align='center'><?php echo $fila->dir_notelefonico; ?></td>

                    <td align='center'><?php echo $fila->ae_sector_economico; ?></td>
                    
                    <td align='center'><?php echo $fila->ae_subsector_economico; ?></td>
                    <td align='center'><?php echo $fila->ae_actividad_ocupacion; ?></td>
                    
                    <td align='center'><?php echo $fila->ae_actividad_fie; ?></td>
                    <td align='center'><?php echo $fila->ae_ambiente; ?></td>

                    <td align='center'><?php echo $fila->emp_nombre_empresa; ?></td>
                    <td align='center'><?php echo $fila->emp_direccion_trabajo; ?></td>
                    <td align='center'><?php echo $fila->emp_telefono_faxtrabaj; ?></td>
                    <td align='center'><?php echo $fila->emp_tipo_empresa; ?></td>
                    <td align='center'><?php echo $fila->emp_antiguedad_empresa; ?></td>
                    <td align='center'><?php echo $fila->emp_codigo_actividad; ?></td>
                    <td align='center'><?php echo $fila->emp_descripcion_cargo; ?></td>
                    <td align='center'><?php echo $fila->emp_fecha_ingreso; ?></td>

                    <td align='center'><?php echo $fila->dir_departamento_neg; ?></td>
                    <td align='center'><?php echo $fila->dir_provincia_neg; ?></td>
                    <td align='center'><?php echo $fila->dir_localidad_ciudad_neg; ?></td>
                    <td align='center'><?php echo $fila->dir_barrio_zona_uv_neg; ?></td>
                    <td align='center'><?php echo $fila->dir_av_calle_pasaje_neg; ?></td>
                    <td align='center'><?php echo $fila->dir_edif_cond_urb_neg; ?></td>
                    <td align='center'><?php echo $fila->dir_numero_neg; ?></td>
                    
                    <td align='center'><?php echo $fila->rp_nombres; ?></td>
                    <td align='center'><?php echo $fila->rp_primer_apellido; ?></td>
                    <td align='center'><?php echo $fila->rp_segundo_apellido; ?></td>
                    <td align='center'><?php echo $fila->rp_direccion; ?></td>
                    <td align='center'><?php echo $fila->rp_notelefonicos; ?></td>
                    <td align='center'><?php echo $fila->rp_nexo_cliente; ?></td>

                    <td align='center'><?php echo $fila->con_primer_nombre; ?></td>
                    <td align='center'><?php echo $fila->con_segundo_nombre; ?></td>
                    <td align='center'><?php echo $fila->con_primera_pellido; ?></td>
                    <td align='center'><?php echo $fila->con_segundoa_pellido; ?></td>
                    <td align='center'><?php echo $fila->con_acteconomica_principal; ?></td>

                    <td align='center'><?php echo $fila->ddc_ciudadania_usa; ?></td>
                    
                    <td align='center'><?php echo $fila->ws_segip_flag_verificacion; ?></td>
                    <td align='center'><?php echo $fila->ws_segip_codigo_resultado; ?></td>
                    <td align='center'><?php echo $fila->ws_cobis_codigo_resultado; ?></td>
                    
                    <td align='center'><?php echo $fila->ws_selfie_codigo_resultado; ?></td>
                    
                    <td align='center'><?php echo $fila->ws_ocr_codigo_resultado; ?></td>
                    <td align='center'><?php echo $fila->ws_ocr_name_valor; ?></td>
                    <td align='center'><?php echo $fila->ws_ocr_name_similar; ?></td>
                    
                    <td align='center'><?php echo $fila->segip_operador_resultado; ?></td>
                    <td align='center'><?php echo $fila->segip_operador_fecha; ?></td>
                    <td align='center'><?php echo $fila->segip_operador_usuario; ?></td>
                    <td align='center'><?php echo $fila->segip_operador_texto; ?></td>
                    
                    <td align='center'><?php echo $fila->f_cobis_actual_etapa; ?></td>
                    <td align='center'><?php echo $fila->f_cobis_actual_intento; ?></td>
                    <td align='center'><?php echo $fila->f_cobis_actual_usuario; ?></td>
                    <td align='center'><?php echo $fila->f_cobis_actual_fecha; ?></td>
                    <td align='center'><?php echo $fila->f_cobis_codigo; ?></td>
                    <td align='center'><?php echo $fila->f_cobis_excepcion; ?></td>
                    <td align='center'><?php echo $fila->f_cobis_excepcion_motivo; ?></td>
                    <td align='center'><?php echo $fila->f_cobis_excepcion_motivo_texto; ?></td>
                    <td align='center'><?php echo $fila->f_cobis_flag_rechazado; ?></td>
                    
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        
    </div>
