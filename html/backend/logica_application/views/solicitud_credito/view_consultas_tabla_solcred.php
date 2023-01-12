<script type="text/javascript">
    function Exportar(tipo) {
        var dataObj = <?php echo json_encode($parametros)?>;
        dataObj.exportar = tipo;
        var data = encodeURI(JSON.stringify(dataObj));
        var w = window.open('about:blank');
        w.document.write('<form method="post" id="formID" action="Consultas/Generar">Generando Reporte...<input type="hidden" value="' + data + '" name="parametros"/></form>');
        w.document.getElementById("formID").submit();
        return false;
    }
    
    $('#divDetalle').dataTable({
        "searching": true,
        "iDisplayLength": <?php echo PAGINADO_TABLA + 5; ?>,
        "bAutoWidth": false,
        "bLengthChange": false,
        "aaSorting": [[1, "asc"]], // Sort by first column descending,
        "oLanguage": idioma_table
    });
    
    $("#tabla_resultado").show();    
    $("#tabla_resultado_opcion").hide();

    // Set variable a utilizar
    var MyApp = {};
    
    function MostrarConfirmaciónTabla(id, user, ejecutivo_id, consolidado)
    {
        $("#tabla_resultado").hide();
        $("#tabla_resultado_opcion").fadeIn(300);
        $("#btnGenerarTabla").addClass("ocultar_elemento");
        
        MyApp.codigo = id;
        MyApp.usuario = user;
        MyApp.ejecutivo_id = ejecutivo_id;
        MyApp.consolidado = consolidado;
        
        if(consolidado == 0)
        {
            $("#RegistroReturn").hide();
        }
        else
        {
            $("#RegistroReturn").show();
        }
        
        document.getElementById("texto_codigo").innerHTML = "Más Opciones de SOL_"+MyApp.codigo;        
    }
    
    function OcultarConfirmaciónTabla()
    {
        $("#tabla_resultado").fadeIn(300);    
        $("#tabla_resultado_opcion").hide();
        $("#btnGenerarTabla").removeClass("ocultar_elemento");
    }
    
    function Ajax_CargarAccion_DetalleSolicitudCred(codigo, vista) {
        var strParametros = "&codigo=" + codigo + "&vista=" + vista;
        Ajax_CargadoGeneralPagina('SolWeb/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    <?php
    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS) && $this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_OBSERVAR_DOCUMENTOS) && $this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DEVOLVER_PROSPECTO))
    {
        echo '
                function Ajax_CargarAccion_RegistroReturn(codigo, codigo_tipo_persona) {
                    var strParametros = "&codigo=" + codigo + "&codigo_tipo_persona=" + codigo_tipo_persona;
                    Ajax_CargadoGeneralPagina("Prospecto/Documento/Ver", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
                }
            ';
    }
    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
    {        
        echo '
                function Ajax_CargarAccion_DocumentoProspectoReporte(codigo, codigo_tipo_persona) {
                    var strParametros = "&codigo=" + codigo + "&codigo_tipo_persona=6";
                    Ajax_CargadoGeneralPagina("Prospecto/Documento/Unico", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
                }
            ';
    }
    ?>
        
    <?php
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_HISTORIAL))
    {        
        echo '
                function Ajax_CargarAccion_Historial(codigo, codigo_tipo_persona) {
                    var strParametros = "&codigo=" + codigo + "&codigo_tipo_persona=6";
                    Ajax_CargadoGeneralPagina("Prospecto/Historial", "divElementoFlotante", "divErrorBusqueda", "", strParametros);
                }
            ';
    }
    ?>
    
</script>

<div id="tabla_resultado">
    <div style="width: 100%; overflow-x: auto">
        <table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
            <thead>
            <tr>
                <th style="width: 5%;"> Código Solicitud </th>
                <th style="width: 8%;"><?php echo $this->lang->line('terceros_columna1'); ?></th>
                <th style="width: 8%;"><?php echo $this->lang->line('import_agente'); ?></th>
                <th style="width: 8%;"><?php echo $this->lang->line('sol_ci'); ?></th>
                <th style="width: 8%;"><?php echo $this->lang->line('import_campana'); ?></th>
                <th style="width: 9%;"><?php echo $this->lang->line('sol_nombre_completo'); ?></th>
                <th style="width: 8%;"><?php echo $this->lang->line('terceros_columna5'); ?></th>
                <th style="width: 8%;"><?php echo $this->lang->line('sol_monto'); ?></th>
                <th style="width: 14%;"><?php echo $this->lang->line('sol_detalle'); ?></th>
                <th style="width: 8%;"><?php echo $this->lang->line('terceros_columna8'); ?></th>
                <th style="width: 8%;"><?php echo $this->lang->line('terceros_columna7'); ?></th>
                <th style="width: 8%;"><?php echo $this->lang->line('TablaOpciones'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($resultado->filas as $fila):?>
                <tr class="FilaBlanca">
                    <td align="center">SOL_<?php echo $fila->sol_id?></td>
                    <td align="center"><?php echo $fila->codigo_agencia_fie; ?></td>
                    <td align="center"><?php echo $fila->ejecutivo_nombre; ?></td>
                    <td align="center"><?php echo $fila->sol_ci?></td>
                    <td align="center"><?php echo $fila->sol_codigo_rubro?></td>
                    <td align="center"><?php echo $fila->sol_nombre_completo?></td>
                    <td align="center"><?php echo $fila->sol_correo . (str_replace(' ', '', $fila->sol_correo)=='' ? '' : '<br />') . $fila->sol_cel ?></td>
                    <td align="center"><?php echo $fila->sol_monto?></td>
                    <td align="center"><?php echo $fila->sol_detalle?></td>
                    <td align="center"><?php echo $fila->sol_fecha?></td>
                    <td align="center"><?php echo $fila->sol_estado . $fila->sol_estado_aux?></td>
                    
                    <td align="center">
                    
                        <span class="BotonSimple" onclick="MostrarConfirmaciónTabla('<?php echo $fila->sol_id; ?>', '<?php echo $fila->usuario_id; ?>', '<?php echo $fila->ejecutivo_id; ?>', '<?php echo $fila->sol_consolidado_codigo; ?>')">
                            <?php echo $this->lang->line('consulta_opciones'); ?>
                        </span>
                        
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>

    <br /><br />

    <a id="btnExportarPDF" class="BotonMinimalistaPequeno" style="width:100px!important;" onclick="return Exportar('pdf');"> <?php echo $this->lang->line('reportes_exportar_pdf'); ?> </a>
    &nbsp;&nbsp;
    <a id="btnExportarExcel" class="BotonMinimalistaPequeno" style="width:100px!important;" onclick="return Exportar('excel');"> <?php echo $this->lang->line('reportes_exportar_excel'); ?> </a>

</div>



<div id="tabla_resultado_opcion" style="width: 90%;">

    <div class="FormularioSubtitulo" style="float: none !important; text-align: center !important;">
        
        <i class="fa fa-binoculars" aria-hidden="true"></i> <span id="texto_codigo"></span>
        
    </div>
    
    <br /><br /><br /><br />
    
    <div style="clear: both"></div>
    
    <span class="BotonSimpleGrande" onclick="Ajax_CargarAccion_DetalleSolicitudCred(MyApp.codigo, 0)">
        <?php echo $this->lang->line('consulta_opciones_detalle'); ?>
    </span>
    
    <?php    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
    {    
    ?>
        <span class="BotonSimpleGrande" onclick="Ajax_CargarAccion_DocumentoProspectoReporte(MyApp.codigo)">
            <?php echo $this->lang->line('consulta_opciones_documentos'); ?>
        </span>
    <?php
    }
    ?>
    
    <?php    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_HISTORIAL))
    {    
    ?>
        <span class="BotonSimpleGrande" onclick="Ajax_CargarAccion_Historial(MyApp.codigo)">
            <?php echo $this->lang->line('consulta_opciones_observaciones'); ?>
        </span>
    <?php
    }
    ?>
    
    <?php    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS) && $this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_OBSERVAR_DOCUMENTOS) && $this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DEVOLVER_PROSPECTO))
    {    
    ?>
        <span id="RegistroReturn" class="BotonSimpleGrande" onclick="Ajax_CargarAccion_RegistroReturn(MyApp.codigo, 6)">
            <?php echo $this->lang->line('consulta_opciones_devolver'); ?>
        </span>
    <?php
    }
    ?>
    
    <div style="clear: both"></div>
    
    <br /><br />
    
    <div>
        <a onclick="OcultarConfirmaciónTabla();" class="BotonMinimalista"> <?php echo $this->lang->line('consulta_volver'); ?> </a>
    </div>
    
    <div style="clear: both"></div>

    <br />

    <?php if (isset($respuesta)) { ?>
        <div class="mensajeBD"> 
            <div style="padding: 15px;">
                <?php echo $respuesta ?>
            </div>
        </div>
    <?php } ?>

    <div id="divErrorListaResultado" class="mensajeBD"> </div>

</div>