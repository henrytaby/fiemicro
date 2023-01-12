<script type="text/javascript">
    function Exportar(tipo) {
        var dataObj = <?php echo json_encode($parametros)?>;
        dataObj.exportar = tipo;
        var data = encodeURI(JSON.stringify(dataObj));
        var w = window.open('about:blank');
        w.document.write('<form method="post" id="formID" action="Afiliador/Consultas/Generar">Generando Reporte...<input type="hidden" value="' + data + '" name="parametros"/></form>');
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
    
    function MostrarConfirmaciónTabla(id, user)
    {
        $("#tabla_resultado").hide();
        $("#tabla_resultado_opcion").fadeIn(300);
        $("#btnGenerarTabla").addClass("ocultar_elemento");
        
        MyApp.codigo = id;
        MyApp.usuario = user;
        
        document.getElementById("texto_codigo").innerHTML = "Más Opciones del COD_"+MyApp.codigo;        
    }
    
    function OcultarConfirmaciónTabla()
    {
        $("#tabla_resultado").fadeIn(300);    
        $("#tabla_resultado_opcion").hide();
        $("#btnGenerarTabla").removeClass("ocultar_elemento");
    }
    
    <?php
    // Sólo muestra la opción si tiene el Perfil
    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_PROSPECTO))
    {        
        echo '
                function Ajax_CargarAccion_DetalleSolicitudTerceros(codigo) {
                    var strParametros = "&codigo=" + codigo + "&estado=0" + "&tipo_accion=1";
                    Ajax_CargadoGeneralPagina("Afiliador/Detalle", "divElementoFlotante", "divErrorBusqueda", "", strParametros);
                }
            ';
    }
    ?>

    <?php
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
    {        
        echo '
                function Ajax_CargarAccion_DocumentoTerceros(codigo) {
                    var strParametros = "&codigo=" + codigo;
                    Ajax_CargadoGeneralPagina("Afiliador/Documento/Ver", "divElementoFlotante", "divErrorBusqueda", "", strParametros);
                }
            ';
    }
    ?>

    <?php
    
    //if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_EMPRESA))
    if(1==1)
    {        
        echo '
                function Ajax_CargarAccion_InformeFinal(codigo) {
                    var strParametros = "&codigo=" + codigo;
                    Ajax_CargadoGeneralPagina("Prospecto/Documento/Informe", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
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
                <th style="width: 8%;">Código Cliente</th>
                <th style="width: 10%;"><?php echo $this->lang->line('terceros_columna1'); ?></th>
                <th style="width: 12%;"><?php echo $this->lang->line('terceros_columna2'); ?></th>
                <th style="width: 12%;"><?php echo $this->lang->line('terceros_columna3'); ?></th>
                <th style="width: 12%;"><?php echo $this->lang->line('terceros_columna4'); ?></th>
                <th style="width: 12%;"><?php echo $this->lang->line('terceros_columna5'); ?></th>
                <th style="width: 12%;"><?php echo $this->lang->line('terceros_columna7'); ?></th>
                <th style="width: 12%;"><?php echo $this->lang->line('terceros_columna8'); ?></th>
                <th style="width: 10%;"><?php echo $this->lang->line('TablaOpciones'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($resultado->filas as $fila):?>
                <tr class="FilaBlanca">
                    <td align="center"><?php echo PREFIJO_TERCEROS . $fila->terceros_id . '<br /><span style="font-size: 0.9em; font-style: italic;">' . $fila->tercero_asistencia . '<br />' . $fila->tipo_cuenta . '</span>'; ?></td>
                    <td align="center"><?php echo $fila->terceros_columna1; ?></td>
                    <td align="center"><?php echo $fila->terceros_columna2; ?></td>
                    <td align="center"><?php echo $fila->terceros_columna3; ?></td>
                    <td align="center"><?php echo $fila->terceros_columna4; ?></td>
                    <td align="center"><?php echo $fila->terceros_columna5; ?></td>
                    <td align="center"><?php echo $fila->terceros_columna7; ?></td>
                    <td align="center"><?php echo $fila->terceros_columna8; ?></td>

                    </td>
                    <td align="center">
                    
                        <span class="BotonSimple" onclick="MostrarConfirmaciónTabla('<?php echo $fila->terceros_id; ?>', '<?php echo $fila->codigo_usuario; ?>')">
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
    
    <?php    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_PROSPECTO))
    {    
    ?>
    
        <span class="BotonSimpleGrande" onclick="Ajax_CargarAccion_DetalleSolicitudTerceros(MyApp.codigo)">
            <?php echo $this->lang->line('consulta_opciones_detalle'); ?>
        </span>
    <?php
    }
    ?>
    
    <?php    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
    {    
    ?>
        <span class="BotonSimpleGrande" onclick="Ajax_CargarAccion_DocumentoTerceros(MyApp.codigo)">
            <?php echo $this->lang->line('consulta_opciones_documentos'); ?>
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