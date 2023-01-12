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
    
    function MostrarConfirmaciónTabla(id, user, campana, ejecutivo_id, consolidado)
    {
        $("#tabla_resultado").hide();
        $("#tabla_resultado_opcion").fadeIn(300);
        $("#btnGenerarTabla").addClass("ocultar_elemento");
        
        MyApp.codigo = id;
        MyApp.usuario = user;
        MyApp.campana = campana;
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
        
        document.getElementById("texto_codigo").innerHTML = "Más Opciones del LEAD_"+MyApp.codigo;        
    }
    
    function OcultarConfirmaciónTabla()
    {
        $("#tabla_resultado").fadeIn(300);    
        $("#tabla_resultado_opcion").hide();
        $("#btnGenerarTabla").removeClass("ocultar_elemento");
    }
    
    <?php
    // Sólo muestra la opción si tiene el Perfil
    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS) && $this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_OBSERVAR_DOCUMENTOS) && $this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DEVOLVER_PROSPECTO))
    {
        echo '
                function Ajax_CargarAccion_RegistroReturn(codigo, codigo_tipo_persona) {
                    var strParametros = "&codigo=" + codigo + "&codigo_tipo_persona=" + codigo_tipo_persona;
                    Ajax_CargadoGeneralPagina("Prospecto/Documento/Ver", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
                }
            ';
    }
    
    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_PROSPECTO))
    {        
        echo '
                function Ajax_CargarAccion_DetalleProspectoReporte(codigo) {
                    var strParametros = "&codigo=" + codigo;
                    Ajax_CargadoGeneralPagina("Prospecto/Detalle", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
                }
            ';
    }
    ?>

    <?php
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
    {        
        echo '
                function Ajax_CargarAccion_DocumentoProspectoReporte(codigo) {
                    var strParametros = "&codigo=" + codigo;
                    Ajax_CargadoGeneralPagina("Prospecto/Documento/Unico", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
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
        
    <?php
    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_USUARIO))
    {        
        echo '
                function Ajax_CargarAccion_DetalleUsuarioReporte(tipo_codigo, codigo_usuario) {
                    var strParametros = "&tipo_codigo=" + tipo_codigo + "&codigo_usuario=" + codigo_usuario;
                    Ajax_CargadoGeneralPagina("Usuario/Detalle", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
                }
            ';
    }
    ?>

    <?php
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_HISTORIAL))
    {        
        echo '
                function Ajax_CargarAccion_HistorialReporte(codigo) {
                    var strParametros = "&codigo=" + codigo;
                    Ajax_CargadoGeneralPagina("Prospecto/Historial", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
                }
            ';
    }
    ?>
        
    <?php
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_SEGUIMIENTO))
    {        
        echo '
                function Ajax_CargarAccion_SeguimientoProspectoReporte(codigo) {
                    var strParametros = "&codigo=" + codigo;
                    Ajax_CargadoGeneralPagina("Prospecto/Seguimiento", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
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
                <th style="width: 5%;"> Código Cliente </th>
                <th style="width: 8%;"><?php echo $this->lang->line('terceros_columna1'); ?></th>
                <th style="width: 8%;"><?php echo $this->lang->line('general_solicitante_corto'); ?></th>
                <th style="width: 8%;"><?php echo $this->lang->line('general_ci'); ?></th>
                <th style="width: 8%;"><?php echo $this->lang->line('import_campana'); ?></th>
                <th style="width: 8%;"><?php echo $this->lang->line('import_agente'); ?></th>
                <th style="width: 8%;"><?php echo $this->lang->line('prospecto_etapa_actual'); ?></th>
                <th style="width: 8%;"><?php echo $this->lang->line('general_actividad'); ?></th>
                <th style="width: 10%;"><?php echo $this->lang->line('general_destino'); ?></th>
                <th style="width: 8%;"><?php echo $this->lang->line('prospecto_actividades'); ?></th>
                <th style="width: 8%;"><?php echo $this->lang->line('prospecto_jda_eval'); ?></th>
                <th style="width: 8%;"><?php echo $this->lang->line('sol_monto_bs'); ?></th>
                <th style="width: 5%;"><?php echo $this->lang->line('TablaOpciones'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($resultado->filas as $fila):?>
                <tr class="FilaBlanca">
                    <td align="center"><?php echo PREFIJO_PROSPECTO . $fila->prospecto_id?></td>
                    <td align="center"><?php echo $fila->estudio_agencia_nombre; ?></td>
                    <td align="center"><?php echo $fila->general_solicitante; ?></td>
                    <td align="center"><?php echo $fila->general_ci . ' ' . $this->mfunciones_generales->GetValorCatalogo($fila->general_ci_extension, 'extension_ci'); ?></td>
                    <td align="center"><?php echo $fila->camp_nombre?></td>
                    <td align="center"><?php echo $fila->ejecutivo_nombre?></td>
                    <td align="center"><?php echo $fila->etapa_nombre?></td>
                    <td align="center"><?php echo $fila->general_actividad?></td>
                    <td align="center"><?php echo $fila->general_destino?></td>
                    <td align="justify"><?php echo $this->mfunciones_generales->GetValorCatalogo($fila->prospecto_id, 'lead_actividades'); ?>
                    <td align="center"><?php echo $this->mfunciones_generales->GetValorCatalogo($fila->prospecto_jda_eval, 'prospecto_evaluacion'); ?></td>
                    <td align="center"><?php echo $fila->sol_monto_bs?></td>
                    <td align="center">
                    
                        <span class="BotonSimple" onclick="MostrarConfirmaciónTabla('<?php echo $fila->prospecto_id; ?>', '<?php echo $fila->usuario_id; ?>', '<?php echo $fila->camp_id; ?>', '<?php echo $fila->ejecutivo_id; ?>', '<?php echo $fila->prospecto_consolidado_codigo; ?>')">
                            <?php echo $this->lang->line('consulta_opciones'); ?>
                        </span>
                        
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>

    <br /><br />

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
    
        <span class="BotonSimpleGrande" onclick="Ajax_CargarAccion_DetalleProspectoReporte(MyApp.codigo)">
            <?php echo $this->lang->line('consulta_opciones_detalle'); ?>
        </span>
    <?php
    }
    ?>
    
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
    //if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_EMPRESA))
    if(1==1)
    {    
    ?>
        <span class="BotonSimpleGrande" onclick="Ajax_CargarAccion_InformeFinal(MyApp.codigo)">
            <?php echo $this->lang->line('consulta_opciones_empresa'); ?>
        </span>
    <?php
    }
    ?>
    
    <?php    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_USUARIO))
    {    
    ?>
        <span class="BotonSimpleGrande" onclick="Ajax_CargarAccion_DetalleUsuarioReporte(0, MyApp.usuario)">
            <?php echo $this->lang->line('consulta_opciones_ejecutivo'); ?>
        </span>
    <?php
    }
    ?>
    
    <?php    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_HISTORIAL))
    {    
    ?>
        <span class="BotonSimpleGrande" onclick="Ajax_CargarAccion_HistorialReporte(MyApp.codigo)">
            <?php echo $this->lang->line('consulta_opciones_observaciones'); ?>
        </span>
    <?php
    }
    ?>
    
    <?php    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_SEGUIMIENTO))
    {    
    ?>
        <span class="BotonSimpleGrande" onclick="Ajax_CargarAccion_SeguimientoProspectoReporte(MyApp.codigo)">
            <?php echo $this->lang->line('consulta_opciones_seguimiento'); ?>
        </span>
    <?php
    }
    ?>
    
    <?php    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS) && $this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_OBSERVAR_DOCUMENTOS) && $this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DEVOLVER_PROSPECTO))
    {    
    ?>
        <span id="RegistroReturn" class="BotonSimpleGrande" onclick="Ajax_CargarAccion_RegistroReturn(MyApp.codigo, 1)">
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