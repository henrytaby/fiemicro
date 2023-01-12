<script type="text/javascript">
    function Exportar(tipo) {
        var dataObj = <?php echo json_encode($parametros)?>;
        dataObj.exportar = tipo;
        var data = encodeURI(JSON.stringify(dataObj));
        var w = window.open('about:blank');
        w.document.write('<form method="post" id="formID" action="Agencia/Consultas/Generar">Generando Reporte...<input type="hidden" value="' + data + '" name="parametros"/></form>');
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
    
    function OcultarConfirmaciónTabla()
    {
        $("#tabla_resultado").fadeIn(300);    
        $("#tabla_resultado_opcion").hide();
        $("#btnGenerarTabla").removeClass("ocultar_elemento");
    }
    
    function Ajax_CargarAccion_DetalleSolicitudTerceros(codigo, estado) {
        var strParametros = "&codigo=" + codigo + "&estado=" + estado + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Afiliador/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
</script>

<div id="tabla_resultado">
    
    <div style="width: 100%; overflow-x: auto">
        <table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 100%;">
            <thead>
            <tr>
                <th style="width: 12%;"><?php echo $this->lang->line('agencia_r_fecha_proceso'); ?></th>
                <th style="width: 10%;"><?php echo $this->lang->line('agencia_r_fecha_actualizacion'); ?></th>
                <th style="width: 10%;"><?php echo $this->lang->line('agencia_r_cuenta_cobis'); ?></th>
                <th style="width: 7%;"><?php echo $this->lang->line('agencia_r_codigo'); ?></th>
                <th style="width: 13%;"><?php echo $this->lang->line('agencia_r_ci'); ?></th>
                <th style="width: 11%;"><?php echo $this->lang->line('agencia_r_solicitante'); ?></th>
                <th style="width: 10%;"><?php echo $this->lang->line('agencia_r_agencia'); ?></th>
                <th style="width: 10%;"><?php echo $this->lang->line('agencia_r_usuario'); ?></th>
                <th style="width: 10%;"><?php echo $this->lang->line('agencia_r_estado'); ?></th>
                <th style="width: 7%;"><?php echo $this->lang->line('agencia_r_dias_notificacion'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($resultado->filas as $fila):?>
                <tr class="FilaBlanca">
                    <td align="center"><?php echo $fila->agencia_r_fecha_proceso; ?></td>
                    <td align="center"><?php echo $fila->agencia_r_fecha_actualizacion; ?></td>
                    <td align="center"><?php echo $fila->agencia_r_cuenta_cobis; ?></td>
                    <td align="center"><span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleSolicitudTerceros('<?php echo $fila->terceros_id; ?>', 1)"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo PREFIJO_TERCEROS . $fila->terceros_id; ?></span></td>
                    <td align="center"><?php echo $fila->agencia_r_ci; ?></td>
                    <td align="center"><?php echo $fila->agencia_r_solicitante; ?></td>
                    <td align="center"><?php echo $fila->agencia_r_agencia; ?></td>
                    <td align="center"><?php echo $fila->agencia_r_usuario; ?></td>
                    <td align="center"><?php echo $fila->agencia_r_estado; ?></td>
                    <td align="center"><?php echo $fila->agencia_r_dias_notificacion; ?></td>
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