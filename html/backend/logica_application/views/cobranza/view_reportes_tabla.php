<script type="text/javascript">
    function Exportar(tipo) {
        var dataObj = <?php echo json_encode($parametros)?>;
        dataObj.exportar = tipo;
        var data = encodeURI(JSON.stringify(dataObj));
        var w = window.open('about:blank');
        w.document.write('<form method="post" id="formID" action="Bandeja/SupNorm/Generar"><span style="font-family: tahoma;">Generando Reporte... ' + (tipo=='excel' ? 'Al completar el proceso puede cerrar esta pestaña.' : '') + '</span><input type="hidden" value="' + data + '" name="parametros"/></form>');
        w.document.getElementById("formID").submit();
        return false;
    }
    
    $('#divDetalle').dataTable({
        "searching": true,
        "iDisplayLength": <?php echo PAGINADO_TABLA; ?>,
        "bAutoWidth": false,
        "bLengthChange": false,
        "aaSorting": [[8, "asc"]], // Sort by first column descending,
        "oLanguage": idioma_table
    });
    
    function Ajax_CargarAccion_DetalleNorm(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Norm/Detalle', 'divElementoFlotante', "divErrorListaResultado", '', strParametros);
    }
    
    function Ajax_CargarAccion_NormModCampo(codigo, tipo) {
        var strParametros = "&codigo=" + codigo + "&tipo=" + tipo;
        Ajax_CargadoGeneralPagina('Norm/NormModCampo', 'divElementoFlotante', "divErrorListaResultado", 'SIN_FOCUS', strParametros);
    }
    
    <?php
    // Sólo muestra la opción si tiene el Perfil
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
    {
        echo '
                function Ajax_CargarAccion_DocumentoProspectoReporte(codigo, codigo_tipo_persona) {
                    var strParametros = "&codigo=" + codigo + "&codigo_tipo_persona=" + codigo_tipo_persona;
                    Ajax_CargadoGeneralPagina("Prospecto/Documento/Unico", "divElementoFlotante", "divErrorListaResultado", "SLASH", strParametros);
                }
            ';
        
        if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"], PERFIL_OBSERVAR_DOCUMENTOS) && $this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"], PERFIL_DEVOLVER_PROSPECTO))
        {
            echo '
                    function Ajax_CargarAccion_DocumentoProspecto(codigo, codigo_tipo_persona) {
                        var strParametros = "&codigo=" + codigo + "&codigo_tipo_persona=" + codigo_tipo_persona;
                        Ajax_CargadoGeneralPagina("Prospecto/Documento/Ver", "divElementoFlotante", "divErrorListaResultado", "SLASH", strParametros);
                    }
            ';
        }
    }
    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
    {
        echo '
                function Ajax_CargarAccion_DocumentoProspecto(codigo, codigo_tipo_persona) {
                    var strParametros = "&codigo=" + codigo + "&codigo_tipo_persona=" + codigo_tipo_persona;
                    Ajax_CargadoGeneralPagina("Prospecto/Documento/Ver", "divElementoFlotante", "divErrorListaResultado", "SLASH", strParametros);
                }
            ';
    }
    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_HISTORIAL))
    {
        
        echo '
                function Ajax_CargarAccion_Historial(codigo, codigo_tipo_persona) {
                    var strParametros = "&codigo=" + codigo + "&codigo_tipo_persona=" + codigo_tipo_persona;
                    Ajax_CargadoGeneralPagina("Prospecto/Historial", "divElementoFlotante", "divErrorListaResultado", "SLASH", strParametros);
                }
            ';
    }
    ?>
    
</script>

<?php $cantidad_columnas = 12;?>

<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 97%;">
    <thead>

        <tr class="FilaCabecera">

            <!-- Similar al EXCEL -->

            <th style="width:5%;">
               <?php echo $this->lang->line('norm_col_codigo'); ?> </th>
            <th style="width:8%;">
               <?php echo $this->lang->line('norm_col_agencia'); ?> </th>
            <th style="width:8%;">
               <?php echo $this->lang->line('norm_col_agente'); ?> </th>
            <th style="width:8%;">
               <?php echo $this->lang->line('norm_col_cliente'); ?> </th>
            <th style="width:8%;">
               <?php echo $this->lang->line('norm_col_num_proceso'); ?> </th>
            <th style="width:8%;">
               <?php echo $this->lang->line('norm_col_estado'); ?> </th>
            <th style="width:8%;">
               <?php echo $this->lang->line('norm_col_rel_cred'); ?> </th>
            <th style="width:8%;">
               <?php echo $this->lang->line('norm_col_finalizacion'); ?> </th>
            <th style="width:7%;">
               <?php echo $this->lang->line('norm_col_fec_registro'); ?> </th>
            <th style="width:7%;">
               <?php echo $this->lang->line('norm_col_fec_visita'); ?> </th>
            <th style="width:7%;">
               <?php echo $this->lang->line('norm_col_fec_comp_pago'); ?> </th>

            <!-- Similar al EXCEL -->

            <th style="width:18%;"> <?php echo $this->lang->line('TablaOpciones'); ?> </th>

        </tr>
    </thead>
    <tbody>
        <?php
        $strClase = "FilaBlanca";
        foreach ($resultado->filas as $key => $value) 
        {
            ?> 
            <tr class="<?php echo $strClase; ?>">

                <!-- Similar al EXCEL -->

                <td style="text-align: center;">
                    <?php
                    echo '<span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleNorm(\'' . $value["norm_id"] . '\')">';
                    echo $this->lang->line('norm_prefijo') . $value["norm_id"];
                    echo '</span>';
                    ?>
                </td>
                <td <?php echo 'id="tdagencia_' . $value["norm_id"] . '"'; ?> style="text-align: center;">
                    <?php echo $value["agencia_nombre"]; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $value["ejecutivo_nombre"]; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $value["cliente_nombre"]; ?>
                </td>
                <td <?php echo 'id="tdnumproceso_' . $value["norm_id"] . '"'; ?> style="text-align: center;">
                    <?php echo $value["registro_num_proceso"]; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $value["registro_consolidado"] . ($value["norm_ultimo_paso_check"] ? '<br /><i>¡Registro Pendiente!</i>' : ''); ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $value["norm_rel_cred"]; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $value["norm_finalizacion"]; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $value["fecha_registro"]; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $value["cv_fecha"]; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $value["cv_fecha_compromiso"] . ($value['cv_fecha_compromiso_check'] ? '<br />' . $this->lang->line('norm_reporte_vencido_error') : ''); ?>
                </td>

                <!-- Similar al EXCEL -->

                <td style="text-align: center;">
                    
                    <?php
                    // Sólo muestra la opción si tiene el Perfil
                    if((int)$value["registro_consolidado_codigo"] == 1)
                    {   
                    ?>
                        <span style="padding-top: 3px; padding-right: 35px;" class="BotonSimple" onclick="<?php echo 'Ajax_CargarAccion_NormModCampo(' . $value["norm_id"] . ', 1);'; ?>">
                            <?php echo $this->lang->line('TablaOpciones_norm_nro_operacion'); ?>
                        </span>
                    
                        <span style="padding-top: 3px; padding-right: 35px;" class="BotonSimple" onclick="<?php echo 'Ajax_CargarAccion_NormModCampo(' . $value["norm_id"] . ', 2);'; ?>">
                            <?php echo $this->lang->line('TablaOpciones_norm_agencia'); ?>
                        </span>
                    <?php
                    }
                    ?>
                    
                    <?php
                    // Sólo muestra la opción si tiene el Perfil
                    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
                    {   
                    ?>
                        <span style="padding-top: 3px; padding-right: 38px;" class="BotonSimple" onclick="<?php echo 'Ajax_CargarAccion_DocumentoProspectoReporte(' . $value["norm_id"] . ', 13);'; ?>">
                            <?php echo str_replace('<br />', '', $this->lang->line('TablaOpciones_revisar_documentacion')); ?>
                        </span>
                    <?php
                    }
                    ?>

                    <?php
                    // Sólo muestra la opción si tiene el Perfil
                    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_HISTORIAL))
                    {
                    ?>
                        <span style="padding-top: 3px; padding-right: 37px;" class="BotonSimple" onclick="Ajax_CargarAccion_Historial('<?php echo $value["norm_id"]; ?>', '13');">
                            <?php echo str_replace('<br />', '', $this->lang->line('TablaOpciones_ver_historial')); ?>
                        </span>
                    <?php
                    }
                    ?>

                </td>

            </tr>
        <?php
        }
        ?>
        </tbody>
</table>

    <?php
    if($resultado->cuenta < 1000)
    {
    ?>
        <a id="btnExportarPDF" class="BotonMinimalistaPequeno" style="width:100px!important;" onclick="return Exportar('pdf');"> <?php echo $this->lang->line('reportes_exportar_pdf'); ?> </a>
        &nbsp;&nbsp;
    <?php
    }
    ?>
    <a id="btnExportarExcel" class="BotonMinimalistaPequeno" style="width:100px!important;" onclick="return Exportar('excel');"> <?php echo $this->lang->line('reportes_exportar_excel'); ?> </a>