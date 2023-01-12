<?php if (!$resultado->error):?>
<?php $columnas_grupo = count($resultado->columnas_grupo);?>
<script type="text/javascript">
    var tablaReporte = null;
    var columnasGrupo = <?php echo $columnas_grupo?>;
    var columnas = <?php echo count($resultado->columnas)?>;
    var offset = 0;
    $(document).ready(function () {
        tablaReporte = $("#tbReporte").DataTable({
            "searching": true,
            "iDisplayLength": <?php echo PAGINADO_TABLA+5; ?>,
            "bAutoWidth": false,
            "bLengthChange": false,
            "ordering":<?php if ($resultado->tiene_detalles):?>true<?php else:?>false<?php endif;?>,
            "oLanguage": idioma_table,
            <?php if ($resultado->agrupar_filas):?>
            'rowsGroup': [<?php echo implode(", ",range(1,count($resultado->columnas_grupo)))?>],
            'columnDefs': [{
                "targets":[<?php echo implode(", ", range(-count($resultado->columnas),$columnas_grupo>1?0:-1))?>],
                "orderable":false,
            }],
            <?php endif;?>
        });
        $(window).resize(verificaAnchoPantalla);
        $("div.ControlColumnasReporte").insertAfter("#tbReporte_filter");
        verificaAnchoPantalla();

    });

    function Exportar(tipo) {
        var dataObj = <?php echo json_encode($parametros)?>;
        dataObj.exportar = tipo;
        var data = encodeURI(JSON.stringify(dataObj));
        var w = window.open('about:blank');
        w.document.write('<form method="post" id="formID" action="Reportes/Generar">Generando Reporte...<input type="hidden" value="' + data + '" name="parametros"/></form>');
        w.document.getElementById("formID").submit();
        return false;
    }
    function verificaAnchoPantalla() {
        if (tablaReporte==null) return;
        $("#tbReporte").css("width","100%");
        var colapsarTabla = ($(".ControlColumnasReporte").css("display")!="none");
        var anchoOcupado = 0;
        $("#tbReporte_wrapper").css("overflow-x",colapsarTabla?"hidden":"auto");
        if (offset<0) offset = 0;
        if (offset>columnas-1) offset = columnasGrupo-1;
        $("#botonOffsetAnterior").blur();
        $("#botonOffsetSiguiente").blur();
        $("#botonOffsetAnterior").removeClass("ui-helper-hidden");
        $("#botonOffsetSiguiente").removeClass("ui-helper-hidden");
        if (offset==0) $("#botonOffsetAnterior").addClass("ui-helper-hidden");
        if (offset==columnas-1) $("#botonOffsetSiguiente").addClass("ui-helper-hidden");
        if (columnasGrupo>1) {
            $("[col=0]").removeClass("sorting_asc").removeClass("sorting_desc");
            tablaReporte.column(0).visible(colapsarTabla);
            for (i = 1; i < columnasGrupo+1; i++) {
                tablaReporte.column(i).visible(!colapsarTabla);
            }
        }
        for (i = columnasGrupo; i < columnasGrupo + columnas; i++) {
            tablaReporte.column(i+(columnasGrupo>1?1:0)).visible(!(colapsarTabla && i!=offset+columnasGrupo));
        }


    }

    function Ajax_CargarAccion_DetalleProspectoReporte(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Prospecto/Detalle', 'divElementoFlotante', "divErrorBusqueda", 'SLASH', strParametros);
    }
    
</script>
    <?php $aux_prefijo = 0; if ($resultado->tiene_detalles): $aux_prefijo = 1; ?>
        <div style="text-align: left !important; margin-left: 8%;">

                <span class="EnlaceSimple" onclick="$('#resumen_reporte').slideToggle();">
                    <strong><?php echo $this->lang->line('reportes_toggle_resumen'); ?> </strong>
                </span>

        <div id="resumen_reporte" class="ResumenBandeja">

            <table class="tablaresultados Mayuscula" border="0">

                <tr class="FilaGris">

                    <td style="width: 33%; font-weight: bold; text-align: center;">
                        <?php echo $this->mfunciones_generales->TiempoEtapaColor(100); ?>
                        <?php echo $resultado->prospectos_a_tiempo; ?> A tiempo
                    </td>

                    <td style="width: 33%; font-weight: bold; text-align: center;">
                        <?php echo $this->mfunciones_generales->TiempoEtapaColor(50); ?>
                        <?php echo $resultado->prospectos_pendientes; ?> Pendiente(s)
                    </td>

                    <td style="width: 34%; font-weight: bold; text-align: center;">
                        <?php echo $this->mfunciones_generales->TiempoEtapaColor(-1); ?>
                        <?php echo $resultado->prospectos_atrasados; ?> Atrasado(s)
                    </td>

                </tr>

            </table>

        </div>

    </div>
<?php endif;?>
    <div class="ControlColumnasReporte">
            <a id="botonOffsetAnterior" class="BotonMinimalistaPequeno" style="float:left" onclick="offset-=1; verificaAnchoPantalla();">Etapa anterior</a>
            <a id="botonOffsetSiguiente" class="BotonMinimalistaPequeno" style="float:right" onclick="offset+=1; verificaAnchoPantalla();">Etapa siguiente</a>
    </div>
    <?php // Crea las cabeceras de grupos para etapas
    $grupos = array();
    foreach ($resultado->columnas as $columna) {
        $grupos[$columna->grupo] = isset($grupos[$columna->grupo])?$grupos[$columna->grupo]+1:1;
    }
    ?>
    <div style="width:100%; overflow: hidden" id="divContenedorReporte">

    <table id="tbReporte" class="tblListas Centrado responsive" cellspacing="0" border="1" >
    <thead>
    <tr class="FilaCabecera">
        <?php if ($columnas_grupo>1):?>
        <th></th>
        <?php endif;?>
        <th colspan="<?php echo count($resultado->columnas_grupo);?>"></th>
        <?php foreach ($grupos as $grupo=>$columnas):?>
            <th colspan="<?php echo $columnas;?>"><?php echo $grupo;?></th>
        <?php endforeach;?>

    </tr>
    <?php $columna_actual = 0;?>
    <tr class="FilaCabecera">
        <?php if ($columnas_grupo>1):?>
        <th class="dt-head-left" col="<?php echo $columna_actual++;?>">
            <?php foreach ($resultado->columnas_grupo as $key=>$grupo):?>
                <p style="padding-left:<?php echo $key*10?>px"><?php echo $grupo->titulo;?></p>
            <?php endforeach;?>
        </th>
        <?php endif;?>
        <?php foreach ($resultado->columnas_grupo as $grupo):?>
            <th col="<?php echo $columna_actual++;?>"><?php echo $grupo->titulo;?></th>
        <?php endforeach;?>
        <?php foreach ($resultado->columnas as $columa):?>
            <th col="<?php echo $columna_actual++;?>" style="text-align: center;"><?php echo $columa->titulo;?></th>
        <?php endforeach;?>
    </tr>
    </thead>
    <tbody>
        <?php $funcion_mostrar = $resultado->funcion_mostrar;?>
        <?php foreach ($resultado->filas as $fila):?>
            <tr class="FilaBlanca">
                <?php if ($columnas_grupo>1):?>
                <td  class="dt-body-left">
                    <?php foreach ($resultado->columnas_grupo as $key=>$grupo):?>
                        <p style="padding-left:<?php echo $key*10?>px"><?php echo $fila->{"campo_grupo_".$grupo->alias_sql};?></p>
                    <?php endforeach;?>
                </td>
                <?php endif;?>
                <?php foreach ($resultado->columnas_grupo as $grupo):?>
                
                    <?php                    
                    if($aux_prefijo == 1)
                    {                    
                    ?>
                        <td align="center">
                            <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleProspectoReporte('<?php echo $fila->{"campo_grupo_".$grupo->alias_sql}; ?>')">
                                <?php echo $this->mfunciones_generales->ObtenerSolicitanteData($fila->{"campo_grupo_".$grupo->alias_sql}, 'general_solicitante');?>
                            </span>
                        </td>
                    <?php
                    }
                    else
                    {
                    ?>
                        <td align="center"><?php echo $fila->{"campo_grupo_".$grupo->alias_sql};?></td>
                    <?php
                    }
                    ?>
                <?php endforeach;?>
                <?php foreach ($resultado->columnas as $key=>$columa):?>
                    <?php $dato = $funcion_mostrar($fila->etapas, $key);?>
                    <td style="text-align: center;<?php if (!isset($fila->etapas[$key])):?>background-color:#eeeeee<?php endif;?>"><?php echo $dato?>
                        <?php if ($resultado->tiene_detalles && isset($fila->etapas[$key])):?>
                            <?php $dato = $fila->etapas[$key]?>
                            <?php if ($dato->bandera_a_tiempo):?><span class="tiempo_100" title="A tiempo"><i class="fa fa-flag" aria-hidden="true"></i> </span><?php endif;?>
                            <?php if ($dato->bandera_pendiente):?><span class="tiempo_50" title="Pendiente"><i class="fa fa-flag" aria-hidden="true"></i> </span><?php endif;?>
                            <?php if ($dato->bandera_atrasado):?><span class="tiempo_0" title="Atrasado"><i class="fa fa-flag" aria-hidden="true"></i> </span><?php endif;?>
                        <?php endif;?>
                    </td>
                <?php endforeach;?>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>

    </div>
    
    <br /><br />

    <a id="btnExportarPDF" class="BotonMinimalistaPequeno" style="width:100px!important;" onclick="return Exportar('pdf');"> <?php echo $this->lang->line('reportes_exportar_pdf'); ?> </a>
    &nbsp;&nbsp;
    <a id="btnExportarExcel" class="BotonMinimalistaPequeno" style="width:100px!important;" onclick="return Exportar('excel');"> <?php echo $this->lang->line('reportes_exportar_excel'); ?> </a>

<?php endif;?>
