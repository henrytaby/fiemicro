<script type="text/javascript">

    // La Librería JS, jQuery (jquery-1.10.2.min.js), CSS y otros se carga en la cabecera del sistema

    function Ajax_GenerarReporte(dato_vista) {
        var campos_grupo = $('#selectCamposGrupo').val();
        var dato_mostrar = $('#selectDatoMostrar').val();
        var filtros = [];
        $("#tbParametrosReporte>tbody>tr").each(function () {filtros.push(JSON.parse($(this).attr("datos")));});
        var parametros = {"campos_grupo":campos_grupo,"funcion_mostrar":dato_mostrar,"vista":dato_vista,"filtros":filtros,"tipo":GetTipoReporte()};
        var strParametros = "&parametros=" + encodeURIComponent(JSON.stringify(parametros));
        Ajax_CargadoGeneralPagina('Reportes/Generar', 'divResultadoReporte', "divErrorBusqueda", 'SLASH', strParametros);
    }

    function AgregarFiltro() {
        Ajax_CargadoGeneralPagina('Reportes/AgregarFiltro', 'divElementoFlotante', "divErrorBusqueda", '', "");
    }

    function QuitarFiltro(obj) {
        $(obj).closest("tr").remove();
    }

    function RegistraFiltro(filtro) {
        var nuevoFiltro = $('<tr><td class="campoFiltroReportes"></td><td class="valorFiltroReportes"></td><td class="accionesFiltroReportes">' +
            '<button title="Quitar filtro" onclick="QuitarFiltro(this);"><i class="fa fa-trash" aria-hidden="true" title="Eliminar"></i></button>' +
            '</td></tr>');
        $(nuevoFiltro).find(".campoFiltroReportes").html("<b>"+filtro.titulo+"</b>");
        $(nuevoFiltro).find(".valorFiltroReportes").html(filtro.descripcion);
        nuevoFiltro.attr("campo",filtro.campo);
        nuevoFiltro.attr("datos",JSON.stringify(filtro));
        $("#tbParametrosReporte").find("[campo="+filtro.campo+"]").remove();
        $("#tbParametrosReporte").append(nuevoFiltro);
    }

    function GetTipoReporte() {
        return $("[name=tipoReporte]:checked").val();
    }

    function ActualizaTipoReporte() {
        var tipo = GetTipoReporte();
        if (tipo=="seguimiento") {
            $("tr.OpcionReporteSeguimiento").removeClass("ui-helper-hidden");
            $("#btnGenerarGrafica").removeClass("ui-helper-hidden");
            $("tr.OpcionReporteDocumentos").addClass("ui-helper-hidden");

        } else {
            $("tr.OpcionReporteDocumentos").removeClass("ui-helper-hidden");
            $("tr.OpcionReporteSeguimiento").addClass("ui-helper-hidden");
            $("#btnGenerarGrafica").addClass("ui-helper-hidden");
        }
    }

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('ReporteTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('ReporteSubtitulo'); ?></div>


        <div style="clear: both;"> </div>
            
        <br /><br />
        
        <form id="FormularioOpcionesReporte" method="post">
            
            <table class="tablaresultados Mayuscula" style="width: 95%;" border="0">
                <tr class="FilaGris OpcionReporteGeneral">
                    <td style="width:20%">
                        <strong> <?php echo $this->lang->line('reportes_tipo_reporte'); ?> </strong>
                    </td>
                    <td style="width:80%">
                        
                        <div style="float: left;">
                            
                            <input id="tipoReporteSeguimiento" name="tipoReporte" type="radio" value="seguimiento" CHECKED onchange="ActualizaTipoReporte();"/>
                            <label for="tipoReporteSeguimiento" class=""><span></span>Seguimiento de Registros/Etapa</label>
                            
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            
                        </div>
                        
                        <div style="float: left;">
                            
                            <input id="tipoReporteDocumentos" name="tipoReporte" type="radio" value="documentos" onchange="ActualizaTipoReporte();"/>
                            <label for="tipoReporteDocumentos" class=""><span></span>Top Documentos Observados</label>
                            
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            
                        </div>
                        
                    </td>
                </tr>
                <tr class="FilaGris OpcionReporteSeguimiento">
                    <td>
                        <strong> <?php echo $this->lang->line('reportes_opciones_agrupamiento'); ?> </strong>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="<?php echo $this->lang->line('reportes_opciones_agrupamiento_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>
                    <td style="width:100%">
                        <?php

                        if(count($opciones_grupo) > 0)
                        {
                            echo html_select('selectCamposGrupo', $opciones_grupo, 'codigo', 'valor', 'SINSELECCIONAR', 'prospecto_id');
                        }
                        else
                        {
                            echo $this->lang->line('TablaNoRegistros');
                        }
                        ?>
                    </td>
                </tr>
                <tr class="FilaGris OpcionReporteSeguimiento">
                    <td>
                        <strong> <?php echo $this->lang->line('reportes_opciones_dato_mostrar'); ?> </strong>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="<?php echo $this->lang->line('reportes_opciones_dato_mostrar_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>
                    <td style="width:100%">
                        <?php

                        if(count($opciones_funcion_mostrar) > 0)
                        {
                            echo html_select('selectDatoMostrar', $opciones_funcion_mostrar, 'codigo', 'valor', '', 'total');
                        }
                        else
                        {
                            echo $this->lang->line('TablaNoRegistros');
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </form>

        <br/>

        <div id="divParametrosReporte">
            <table id="tbParametrosReporte" class="tablaresultados TextoMayuscula">
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3">
                        <a id="btnAgregarFiltro" class="BotonSimple" style="display:inline-block; float:right; max-width:150px!important;" onclick="AgregarFiltro();"> <?php echo $this->lang->line('reportes_boton_agregar_filtro'); ?> </a>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        <br /><br />

        <div class="Botones2Opciones">
            
            <a id="btnGenerarTabla" class="BotonMinimalista" onclick="Ajax_GenerarReporte('tabla');"> <?php echo $this->lang->line('reportes_generar_tabla'); ?> </a>
            
        </div>
        
        <div class="Botones2Opciones">
            
            <a id="btnGenerarGrafica" class="BotonMinimalista" onclick="Ajax_GenerarReporte('grafica');"> <?php echo $this->lang->line('reportes_generar_grafica'); ?> </a>            
            
        </div>

        <div style="clear: both"></div>
        <?php // Si hay un error PHP o DB se mostra´ra en este DIV ?>
        <div id="divErrorBusqueda" class="mensajeBD">  </div>

        <div id="divResultadoReporte"></div>
        
    </div>
</div>
